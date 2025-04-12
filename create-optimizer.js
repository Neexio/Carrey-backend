const fs = require('fs');
const path = require('path');

// Create directory structure
const directories = [
    'optimizer',
    'optimizer/functions',
    'optimizer/js',
    'optimizer/css',
    'optimizer/htaccess',
    'optimizer/critical'
];

directories.forEach(dir => {
    if (!fs.existsSync(dir)) {
        fs.mkdirSync(dir, { recursive: true });
    }
});

// Create files
const files = {
    'LICENSE': 'MIT License - Carrey Optimizer',
    'README.md': '# Carrey Optimizer Toolkit\n\nBuilt to improve SEO, performance, accessibility, and security on WordPress websites.',
    
    'functions/remove-unused-assets.php': `<?php
function carrey_remove_unused_assets() {
  if (is_front_page()) {
    wp_dequeue_script('jquery-migrate');
    wp_dequeue_style('wcf-addons-pro');
    wp_dequeue_style('bootstrap');
    wp_dequeue_script('mixitup');
  }
}
add_action('wp_enqueue_scripts', 'carrey_remove_unused_assets', 100);`,
    
    'functions/inline-critical-css.php': `<?php
function carrey_inline_critical_css() {
  if (is_front_page()) {
    echo '<style>';
    readfile(get_template_directory() . '/optimizer/critical/critical-home.css');
    echo '</style>';
    echo '<link rel="preload" href="/wp-content/themes/yourtheme/style.css" as="style" onload="this.onload=null;this.rel=\\'stylesheet\\'">';
  }
}
add_action('wp_head', 'carrey_inline_critical_css');`,
    
    'functions/preload-lcp-image.php': `<?php
function preload_lcp_image() {
  echo '<link rel="preload" as="image" href="https://carrey.ai/path-to-hero-image.webp">';
}
add_action('wp_head', 'preload_lcp_image');`,
    
    'functions/fix-https-mixed-content.php': `<?php
function force_https_urls($content) {
  return str_replace('http://carrey.ai', 'https://carrey.ai', $content);
}
add_filter('the_content', 'force_https_urls');
add_filter('style_loader_src', 'force_https_urls');
add_filter('script_loader_src', 'force_https_urls');`,
    
    'functions/add-schema.php': `<?php
function add_schema_to_head() {
?>
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "Organization",
  "name": "Carrey AI",
  "url": "https://carrey.ai",
  "sameAs": [
    "https://www.linkedin.com/company/carreyai",
    "https://www.instagram.com/carreyai"
  ]
}
</script>
<?php } 
add_action('wp_head', 'add_schema_to_head');`,
    
    'js/defer-scripts.js': `document.addEventListener("DOMContentLoaded", () => {
  const scripts = document.querySelectorAll("script[src]");
  scripts.forEach(script => {
    const src = script.getAttribute("src");
    if (!src.includes("jquery") && !src.includes("elementor") && !script.defer && !script.async) {
      script.setAttribute("defer", true);
    }
  });
});`,
    
    'js/lazyload-fix.js': `document.querySelectorAll('img:not([loading])').forEach(img => {
  img.setAttribute('loading', 'lazy');
  img.setAttribute('decoding', 'async');
});`,
    
    'js/seo-crawlable-links-fix.js': `document.querySelectorAll('a.wcf-nav-item:not([href])').forEach(el => {
  el.setAttribute('href', '#');
  el.textContent = el.textContent || "Navigation Link";
});`,
    
    'css/fix-contrast.css': `.carrey-seo-button {
  background-color: #1e8449 !important;
  color: #ffffff !important;
}
.highlight {
  color: #000000;
}`,
    
    'htaccess/headers-security.htaccess': `<IfModule mod_headers.c>
  Header always set Strict-Transport-Security "max-age=31536000; includeSubDomains; preload"
  Header always set X-Content-Type-Options "nosniff"
  Header always set X-Frame-Options "SAMEORIGIN"
  Header set Content-Security-Policy "default-src 'self'; script-src 'self' 'unsafe-inline'; style-src 'self' 'unsafe-inline';"
</IfModule>`,
    
    'critical/critical-home.css': '/* Critical CSS goes here */'
};

// Write files
Object.entries(files).forEach(([filePath, content]) => {
    const fullPath = path.join('optimizer', filePath);
    fs.writeFileSync(fullPath, content);
    console.log(`Created: ${fullPath}`);
});

console.log('✅ Carrey Optimizer files created in ./optimizer'); 