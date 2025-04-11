const express = require('express');
const cors = require('cors');
const axios = require('axios');
const cheerio = require('cheerio');

const app = express();
const port = 8080;

// Enable CORS
app.use(cors());
app.use(express.json());

// Welcome message
app.get('/', (req, res) => {
    res.send('SEO Analysis Server is running');
});

// Handle both GET and POST requests for /api/analyze
app.all('/api/analyze', async (req, res) => {
    console.log('Received analysis request');
    
    const url = req.query.url || (req.body && req.body.url);
    
    if (!url) {
        console.log('No URL provided');
        return res.status(400).json({ error: 'URL is required' });
    }

    console.log('Analyzing URL:', url);

    try {
        // Fetch website content
        const response = await axios.get(url);
        const html = response.data;
        const $ = cheerio.load(html);

        // Basic SEO elements
        const title = $('title').text();
        const metaDescription = $('meta[name="description"]').attr('content');
        const metaKeywords = $('meta[name="keywords"]').attr('content');
        const canonicalUrl = $('link[rel="canonical"]').attr('href');
        const robotsMeta = $('meta[name="robots"]').attr('content');
        const viewportMeta = $('meta[name="viewport"]').attr('content');
        const charsetMeta = $('meta[charset]').attr('charset');

        // Headings
        const headings = {
            h1: [],
            h2: [],
            h3: [],
            h4: [],
            h5: [],
            h6: []
        };

        $('h1, h2, h3, h4, h5, h6').each((i, el) => {
            const tagName = el.tagName.toLowerCase();
            headings[tagName].push($(el).text());
        });

        // Images analysis
        const images = {
            total: 0,
            withAlt: 0,
            withoutAlt: 0,
            largeImages: 0
        };

        $('img').each((i, el) => {
            images.total++;
            if ($(el).attr('alt')) {
                images.withAlt++;
            } else {
                images.withoutAlt++;
            }
            if ($(el).attr('width') > 1000 || $(el).attr('height') > 1000) {
                images.largeImages++;
            }
        });

        // Links analysis
        const links = {
            total: 0,
            internal: 0,
            external: 0,
            noFollow: 0,
            broken: 0
        };

        $('a').each((i, el) => {
            links.total++;
            const href = $(el).attr('href');
            if (href) {
                if (href.startsWith('http')) {
                    links.external++;
                } else {
                    links.internal++;
                }
                if ($(el).attr('rel') === 'nofollow') {
                    links.noFollow++;
                }
            }
        });

        // Content analysis
        const content = {
            wordCount: $('body').text().trim().split(/\s+/).length,
            paragraphCount: $('p').length,
            listCount: $('ul, ol').length,
            tableCount: $('table').length
        };

        // Performance indicators
        const performance = {
            scripts: $('script').length,
            stylesheets: $('link[rel="stylesheet"]').length,
            inlineStyles: $('style').length,
            iframes: $('iframe').length
        };

        // Calculate SEO score
        let score = 0;
        const issues = [];
        const recommendations = [];

        // Title analysis (30 points)
        if (title) {
            score += 20;
            const titleLength = title.length;
            if (titleLength < 10) {
                issues.push('Title is too short (less than 10 characters)');
            } else if (titleLength > 60) {
                issues.push('Title is too long (more than 60 characters)');
            } else {
                score += 10;
            }
        } else {
            issues.push('Missing title tag');
        }

        // Meta description analysis (20 points)
        if (metaDescription) {
            score += 10;
            const descLength = metaDescription.length;
            if (descLength < 50) {
                issues.push('Meta description is too short (less than 50 characters)');
            } else if (descLength > 160) {
                issues.push('Meta description is too long (more than 160 characters)');
            } else {
                score += 10;
            }
        } else {
            issues.push('Missing meta description');
        }

        // Headings analysis (15 points)
        if (headings.h1.length === 1) {
            score += 5;
        } else if (headings.h1.length === 0) {
            issues.push('No H1 tag found');
        } else {
            issues.push('Multiple H1 tags found');
        }
        if (headings.h2.length > 0) score += 5;
        if (headings.h3.length > 0) score += 5;

        // Images analysis (10 points)
        if (images.total > 0) {
            score += 5;
            if (images.withAlt === images.total) {
                score += 5;
            } else {
                issues.push(`${images.withoutAlt} images without alt text`);
            }
            if (images.largeImages > 0) {
                issues.push(`${images.largeImages} large images that may affect performance`);
            }
        }

        // Links analysis (10 points)
        if (links.total > 0) {
            score += 5;
            if (links.broken === 0) {
                score += 5;
            } else {
                issues.push(`${links.broken} broken links found`);
            }
        }

        // Content analysis (15 points)
        if (content.wordCount > 300) {
            score += 5;
        } else {
            issues.push('Content is too short (less than 300 words)');
        }
        if (content.paragraphCount > 3) score += 5;
        if (content.listCount > 0) score += 5;

        // Add recommendations based on analysis
        if (score < 70) {
            recommendations.push('Consider adding more content to improve SEO');
            recommendations.push('Optimize images for better performance');
            recommendations.push('Ensure all images have descriptive alt text');
            recommendations.push('Check and fix any broken links');
        }

        console.log('Analysis complete, score:', score);

        res.json({
            score,
            issues,
            recommendations,
            elements: {
                title,
                metaDescription,
                metaKeywords,
                canonicalUrl,
                robotsMeta,
                viewportMeta,
                charsetMeta,
                headings,
                images,
                links,
                content,
                performance
            }
        });
    } catch (error) {
        console.error('Error during analysis:', error.message);
        res.status(500).json({ 
            error: 'Could not analyze website',
            details: error.message
        });
    }
});

app.listen(port, () => {
    console.log(`SEO Analysis Server is running on port ${port}`);
}); 