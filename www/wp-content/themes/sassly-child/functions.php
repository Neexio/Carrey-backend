<?php
/**
 * Theme functions and definitions.
 */

// Enable JavaScript error logging
add_action('wp_head', function() {
    if (current_user_can('administrator')) {
        echo '<script>
            window.onerror = function(msg, url, lineNo, columnNo, error) {
                console.log("Error: " + msg + "\nURL: " + url + "\nLine: " + lineNo + "\nColumn: " + columnNo + "\nError object: " + JSON.stringify(error));
                return false;
            };
        </script>';
    }
});

// Deactivate preloader
add_action('wp_enqueue_scripts', function() {
    // Dequeue common preloader scripts
    wp_dequeue_script('preloader-script');
    wp_dequeue_script('preloader-plus');
    wp_dequeue_script('wp-smart-preloader');
    wp_dequeue_script('sassly-preloader');
    
    // Remove preloader styles
    wp_dequeue_style('preloader-style');
    wp_dequeue_style('preloader-plus');
    wp_dequeue_style('wp-smart-preloader');
    wp_dequeue_style('sassly-preloader');
}, 99);

// Enqueue child theme styles
function sassly_child_enqueue_styles() {
    wp_enqueue_style('sassly-child-style',
        get_stylesheet_directory_uri() . '/style.css',
        array('sassly-style'),
        wp_get_theme()->get('Version')
    );
}
add_action('wp_enqueue_scripts', 'sassly_child_enqueue_styles');

// Add debug information to admin bar
add_action('admin_bar_menu', function($wp_admin_bar) {
    if (!current_user_can('administrator')) return;
    
    $wp_admin_bar->add_node(array(
        'id'    => 'debug-info',
        'title' => 'Debug Info',
        'href'  => '#',
        'meta'  => array(
            'html' => '<div style="background: #fff; padding: 10px; margin: 5px; border: 1px solid #ccc;">
                <h3>Theme Debug Info</h3>
                <p>Child Theme: ' . wp_get_theme()->get('Name') . '</p>
                <p>Version: ' . wp_get_theme()->get('Version') . '</p>
                <p>Template: ' . wp_get_theme()->get('Template') . '</p>
            </div>'
        )
    ));
}, 100);
