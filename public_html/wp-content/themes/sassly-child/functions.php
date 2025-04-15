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
