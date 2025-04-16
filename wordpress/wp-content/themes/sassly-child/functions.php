<?php
/**
 * Enqueue parent and child theme styles
 */
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

// Force refresh of styles and clear all caches
function sassly_force_style_refresh() {
    // Clear WordPress cache
    wp_cache_flush();
    
    // Clear LiteSpeed cache
    if (function_exists('litespeed_purge_all')) {
        litespeed_purge_all();
    }
    
    // Clear W3 Total Cache if exists
    if (function_exists('w3tc_flush_all')) {
        w3tc_flush_all();
    }
    
    // Clear WP Super Cache if exists
    if (function_exists('wp_cache_clear_cache')) {
        wp_cache_clear_cache();
    }
    
    // Clear browser cache headers
    header("Cache-Control: no-cache, no-store, must-revalidate");
    header("Pragma: no-cache");
    header("Expires: 0");
    
    // Clear cache directories
    $cache_dirs = array(
        WP_CONTENT_DIR . '/cache',
        WP_CONTENT_DIR . '/litespeed',
        WP_CONTENT_DIR . '/cache/litespeed'
    );
    
    foreach ($cache_dirs as $dir) {
        if (is_dir($dir)) {
            array_map('unlink', glob("$dir/*.*"));
        }
    }
}
add_action('init', 'sassly_force_style_refresh'); 