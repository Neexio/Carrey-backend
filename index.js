const express = require('express');
const axios = require('axios');
const cheerio = require('cheerio');
const cors = require('cors');
const app = express();

app.use(cors());
app.use(express.json());

const visited = new Set();

async function analyzePage(url) {
  try {
    const { data: html } = await axios.get(url);
    const $ = cheerio.load(html);

    const metaTitle = $('title').text() || 'Missing';
    const metaDesc = $('meta[name="description"]').attr('content') || 'Missing';
    const canonical = $('link[rel="canonical"]').attr('href') || 'Missing';
    const h1Count = $('h1').length;
    const schema = $('script[type="application/ld+json"]').length;
    const images = $('img');
    const altMissing = [...images].filter(img => !$(img).attr('alt')).length;

    let issues = [];
    if (!metaTitle || metaTitle.length < 20 || metaTitle.length > 70) issues.push('Fix meta title');
    if (!metaDesc || metaDesc.length < 100) issues.push('Fix meta description');
    if (h1Count !== 1) issues.push('Ensure exactly one H1');
    if (!canonical) issues.push('Missing canonical tag');
    if (schema === 0) issues.push('Missing schema markup');
    if (altMissing > 0) issues.push(`${altMissing} images missing alt`);

    return { url, metaTitle, issues };
  } catch (err) {
    return { url, error: err.message };
  }
}

app.post('/analyze', async (req, res) => {
  const baseUrl = req.body.url;
  if (!baseUrl) return res.status(400).json({ error: 'URL required' });

  try {
    const { data: html } = await axios.get(baseUrl);
    const $ = cheerio.load(html);

    const links = new Set();
    $('a').each((_, a) => {
      const href = $(a).attr('href');
      if (href && href.startsWith('/') && !href.includes('#')) {
        links.add(new URL(href, baseUrl).toString());
      }
    });

    visited.clear();
    visited.add(baseUrl);

    const pagesToCheck = Array.from(links).slice(0, 10); // scan up to 10 pages
    const results = [];

    for (let page of [baseUrl, ...pagesToCheck]) {
      if (!visited.has(page)) {
        visited.add(page);
        const result = await analyzePage(page);
        results.push(result);
      }
    }

    res.json({
      site: baseUrl,
      pagesAnalyzed: results.length,
      results
    });
  } catch (err) {
    res.status(500).json({ error: 'Failed to crawl site', details: err.message });
  }
});

const PORT = process.env.PORT || 3001;
app.listen(PORT, () => console.log(`✅ Carrey crawler running on port ${PORT}`));
