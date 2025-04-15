<?php
/**
 * Theme functions and definitions.
 */
// Properly enqueue parent and child theme styles
function sassly_child_enqueue_styles() {
    // Parent theme style
    wp_enqueue_style(
        'sassly-parent-style',
        get_template_directory_uri() . '/style.css'
    );

    // Child theme style
    wp_enqueue_style(
        'sassly-child-style',
        get_stylesheet_directory_uri() . '/style.css',
        array('sassly-parent-style'),
        wp_get_theme()->get('Version')
    );
}

add_action('wp_enqueue_scripts', 'sassly_child_enqueue_styles');

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
