<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Enqueue theme styles and scripts
function carrey_theme_scripts() {
    wp_enqueue_style('carrey-theme-style', get_stylesheet_uri());
    wp_enqueue_style('carrey-main-style', get_template_directory_uri() . '/assets/css/main.css', array(), '1.0.0');
}
add_action('wp_enqueue_scripts', 'carrey_theme_scripts');

// Register custom template
function carrey_register_template() {
    $post_type_object = get_post_type_object('page');
    $post_type_object->template = array(
        array('carrey/seo-analysis-section')
    );
}
add_action('init', 'carrey_register_template');

// Add theme support
function carrey_theme_support() {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('custom-logo');
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
    ));
}
add_action('after_setup_theme', 'carrey_theme_support'); 