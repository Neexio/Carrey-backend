jQuery(document).ready(function($) {
    function analyzeWebsite() {
        const widget = $('.carrey-seo-widget');
        const input = widget.find('.carrey-seo-input');
        const results = widget.find('.carrey-seo-results');
        const loading = widget.find('.carrey-seo-loading');
        const error = widget.find('.carrey-seo-error');
        const content = widget.find('.carrey-seo-content');
        
        const url = input.val().trim();
        if (!url) {
            error.text('Please enter a valid URL').show();
            return;
        }

        results.show();
        loading.show();
        error.hide();
        content.hide();

        const fullUrl = url.startsWith('http') ? url : 'https://' + url;

        fetch(`https://corsproxy.io/?${encodeURIComponent(fullUrl)}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Could not fetch website');
                }
                return response.text();
            })
            .then(html => {
                loading.hide();
                
                try {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');

                    const title = doc.querySelector('title')?.textContent || '';
                    const metaDescription = doc.querySelector('meta[name="description"]')?.getAttribute('content') || '';
                    const h1Tags = doc.querySelectorAll('h1');
                    const canonical = doc.querySelector('link[rel="canonical"]')?.getAttribute('href') || '';
                    const robots = doc.querySelector('meta[name="robots"]')?.getAttribute('content') || '';
                    const ogTags = Array.from(doc.querySelectorAll('meta[property^="og:"]'));
                    const schemaTags = Array.from(doc.querySelectorAll('script[type="application/ld+json"]'));

                    let score = 0;
                    let totalChecks = 0;

                    const titleLength = title.length;
                    const titleStatus = titleLength >= 30 && titleLength <= 60;
                    score += titleStatus ? 1 : 0;
                    totalChecks++;

                    const descLength = metaDescription.length;
                    const descStatus = descLength >= 120 && descLength <= 160;
                    score += descStatus ? 1 : 0;
                    totalChecks++;

                    const h1Status = h1Tags.length === 1;
                    score += h1Status ? 1 : 0;
                    totalChecks++;

                    const canonicalStatus = canonical !== '';
                    score += canonicalStatus ? 1 : 0;
                    totalChecks++;

                    const robotsStatus = robots !== '';
                    score += robotsStatus ? 1 : 0;
                    totalChecks++;

                    const ogStatus = ogTags.length >= 3;
                    score += ogStatus ? 1 : 0;
                    totalChecks++;

                    const schemaStatus = schemaTags.length > 0;
                    score += schemaStatus ? 1 : 0;
                    totalChecks++;

                    const finalScore = Math.round((score / totalChecks) * 100);

                    let html = `<div class="seo-score">SEO Score: ${finalScore}/100</div>`;

                    html += `<div class="seo-item ${titleStatus ? 'good' : 'bad'}">
                        <div class="seo-item-title">Title Tag</div>
                        <div class="seo-item-content">${title || 'No title found'}</div>
                        <div class="seo-item-details">Length: ${titleLength} characters (Recommended: 30-60)</div>
                    </div>`;

                    html += `<div class="seo-item ${descStatus ? 'good' : 'bad'}">
                        <div class="seo-item-title">Meta Description</div>
                        <div class="seo-item-content">${metaDescription || 'No meta description found'}</div>
                        <div class="seo-item-details">Length: ${descLength} characters (Recommended: 120-160)</div>
                    </div>`;

                    html += `<div class="seo-item ${h1Status ? 'good' : 'warning'}">
                        <div class="seo-item-title">H1 Tags</div>
                        <div class="seo-item-content">Found: ${h1Tags.length} H1 tag(s)</div>
                        <div class="seo-item-details">${h1Status ? 'Perfect! Only one H1 tag found.' : 'Warning: Should have exactly one H1 tag'}</div>
                    </div>`;

                    html += `<div class="seo-item ${canonicalStatus ? 'good' : 'warning'}">
                        <div class="seo-item-title">Canonical URL</div>
                        <div class="seo-item-content">${canonical || 'No canonical URL found'}</div>
                        <div class="seo-item-details">${canonicalStatus ? 'Good! Canonical URL is set.' : 'Warning: Consider adding a canonical URL'}</div>
                    </div>`;

                    html += `<div class="seo-item ${robotsStatus ? 'good' : 'warning'}">
                        <div class="seo-item-title">Robots Meta Tag</div>
                        <div class="seo-item-content">${robots || 'No robots meta tag found'}</div>
                        <div class="seo-item-details">${robotsStatus ? 'Good! Robots meta tag is set.' : 'Warning: Consider adding robots meta tag'}</div>
                    </div>`;

                    html += `<div class="seo-item ${ogStatus ? 'good' : 'warning'}">
                        <div class="seo-item-title">Open Graph Tags</div>
                        <div class="seo-item-content">Found: ${ogTags.length} OG tags</div>
                        <div class="seo-item-details">${ogStatus ? 'Good! Sufficient OG tags found.' : 'Warning: Consider adding more OG tags'}</div>
                    </div>`;

                    html += `<div class="seo-item ${schemaStatus ? 'good' : 'warning'}">
                        <div class="seo-item-title">Schema Markup</div>
                        <div class="seo-item-content">${schemaStatus ? 'Schema markup found' : 'No schema markup found'}</div>
                        <div class="seo-item-details">${schemaStatus ? 'Good! Schema markup is implemented.' : 'Warning: Consider adding schema markup'}</div>
                    </div>`;

                    content.html(html).show();
                } catch (parseError) {
                    throw new Error('Could not analyze website');
                }
            })
            .catch(err => {
                loading.hide();
                error.text('An error occurred while analyzing the website. Please try again later.').show();
                console.error('Crawler Error:', err);
            });
    }

    $('.carrey-seo-button').on('click', analyzeWebsite);

    $('.carrey-seo-input').on('keypress', function(e) {
        if (e.which === 13) {
            analyzeWebsite();
        }
    });
}); 