function add_image_dimensions($html, $post_id, $post_thumbnail_id, $size, $attr) {
    if (empty($html)) return $html;
    
    $dom = new DOMDocument();
    @$dom->loadHTML($html, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
    $images = $dom->getElementsByTagName('img');
    
    foreach ($images as $img) {
        if (!$img->hasAttribute('width') || !$img->hasAttribute('height')) {
            $src = $img->getAttribute('src');
            $image_size = getimagesize($src);
            if ($image_size) {
                $img->setAttribute('width', $image_size[0]);
                $img->setAttribute('height', $image_size[1]);
            }
        }
    }
    
    return $dom->saveHTML();
}
add_filter('post_thumbnail_html', 'add_image_dimensions', 10, 5);

function optimize_scripts_loading() {
    // Defer non-critical JavaScript
    add_filter('script_loader_tag', function($tag, $handle) {
        if (is_admin()) return $tag;
        
        $defer_scripts = array(
            'jquery',
            'wp-embed',
            'comment-reply'
        );
        
        if (in_array($handle, $defer_scripts)) {
            return str_replace(' src', ' defer src', $tag);
        }
        
        return $tag;
    }, 10, 2);
    
    // Inline critical CSS
    add_action('wp_head', function() {
        $critical_css = file_get_contents(get_template_directory() . '/assets/css/critical.css');
        if ($critical_css) {
            echo '<style>' . $critical_css . '</style>';
        }
    }, 1);
}
add_action('init', 'optimize_scripts_loading');

function optimize_images_for_mobile() {
    // Lazy loading for bilder
    add_filter('wp_get_attachment_image_attributes', function($attr, $attachment) {
        $attr['loading'] = 'lazy';
        $attr['decoding'] = 'async';
        $attr['sizes'] = '(max-width: 768px) 100vw, (max-width: 1200px) 50vw, 33vw';
        return $attr;
    }, 10, 2);
    
    // Responsive bilder
    add_filter('wp_calculate_image_srcset', function($sources, $size_array, $image_src, $image_meta, $attachment_id) {
        if (wp_is_mobile()) {
            $max_width = 768;
            foreach ($sources as $width => $source) {
                if ($width > $max_width) {
                    unset($sources[$width]);
                }
            }
        }
        return $sources;
    }, 10, 5);
    
    // Legg til mobil-støtte for bilder
    add_filter('the_content', function($content) {
        if (wp_is_mobile()) {
            $content = preg_replace('/<img(.*?)width=["\']\d+["\'](.*?)height=["\']\d+["\'](.*?)>/i', '<img$1$2$3>', $content);
        }
        return $content;
    });
}
add_action('init', 'optimize_images_for_mobile');

// Fjern loading-skjermen
function remove_loading_screen() {
    wp_enqueue_script('remove-loading', get_template_directory_uri() . '/assets/js/remove-loading.js', array(), '1.0', true);
}
add_action('wp_enqueue_scripts', 'remove_loading_screen');

// Legg til mobil-CSS
function carrey_enqueue_mobile_styles() {
    wp_enqueue_style('carrey-mobile', get_template_directory_uri() . '/assets/css/mobile.css', array(), '1.0', 'all');
}
add_action('wp_enqueue_scripts', 'carrey_enqueue_mobile_styles');

// Optimaliser ikoner for mobil
function carrey_optimize_icons() {
    add_filter('wp_get_attachment_image_attributes', function($attr, $attachment) {
        // Sjekk om bildet er et ikon
        if (strpos($attr['class'], 'icon') !== false || 
            strpos($attr['class'], 'social-icon') !== false || 
            strpos($attr['class'], 'feature-icon') !== false) {
            $attr['style'] = 'width: 24px; height: 24px; max-width: 24px; max-height: 24px;';
        }
        return $attr;
    }, 10, 2);
}
add_action('init', 'carrey_optimize_icons');

// Defer non-critical JavaScript
function defer_parsing_of_js($url) {
    if (is_admin()) return $url;
    if (false === strpos($url, '.js')) return $url;
    if (strpos($url, 'jquery.js')) return $url;
    return str_replace(' src', ' defer src', $url);
}
add_filter('script_loader_tag', 'defer_parsing_of_js', 10);

// Remove jQuery migrate
function remove_jquery_migrate($scripts) {
    if (!is_admin() && isset($scripts->registered['jquery'])) {
        $script = $scripts->registered['jquery'];
        if ($script->deps) {
            $script->deps = array_diff($script->deps, array('jquery-migrate'));
        }
    }
}
add_action('wp_default_scripts', 'remove_jquery_migrate');

// Optimize CSS loading
function optimize_css_loading() {
    if (!is_admin()) {
        // Remove unused CSS
        wp_dequeue_style('dashicons');
        wp_dequeue_style('wp-block-library');
        wp_dequeue_style('wp-block-library-theme');
        
        // Load critical CSS inline
        add_action('wp_head', 'load_critical_css', 1);
    }
}
add_action('wp_enqueue_scripts', 'optimize_css_loading', 99);

function load_critical_css() {
    ?>
    <style>
        /* Critical CSS rules here */
        body { visibility: hidden; }
        .site-header { opacity: 1; }
        .main-content { opacity: 1; }
    </style>
    <?php
} 