<?php
/**
 * Theme functions and definitions.
 */
function sassly_child_enqueue_styles() {
    
    wp_enqueue_style( 'sassly-child-style',
        get_stylesheet_directory_uri() . '/style.css',
        [],
        wp_get_theme()->get('Version')
    );
}

add_action(  'wp_enqueue_scripts', 'sassly_child_enqueue_styles', 16);