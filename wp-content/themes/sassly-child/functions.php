<?php
// Load Font Awesome
function carrey_enqueue_font_awesome() {
    wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css', [], null);
}
add_action('wp_enqueue_scripts', 'carrey_enqueue_font_awesome');

// JavaScript fallback for large icons
function carrey_fix_icon_sizes_js() {
    ?>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const icons = document.querySelectorAll('svg, i.icon, img[class*="icon"], .diploma-icon, [class*="trophy"], [class*="certificate"]');
        icons.forEach(icon => {
            icon.style.maxWidth = '50px';
            icon.style.maxHeight = '50px';
            icon.style.width = 'auto';
            icon.style.height = 'auto';
            icon.style.display = 'inline-block';
            icon.style.overflow = 'hidden';
        });
    });
    </script>
    <?php
}
add_action('wp_footer', 'carrey_fix_icon_sizes_js');

// Nuke SVG path-based diploma-like loader
function nuke_diploma_path_loader() {
    ?>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const loaderPath = document.querySelector('svg path[d^="M50,1"]');
        if (loaderPath) {
            loaderPath.style.display = 'none';
            loaderPath.style.visibility = 'hidden';
            loaderPath.style.stroke = 'none';
        }
    });
    </script>
    <?php
}
add_action('wp_footer', 'nuke_diploma_path_loader');

// Delay showing the popup until page is ready
function defer_diploma_popup() {
    ?>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const popup = document.querySelector('.wcf-image-generator-popup');
        if (popup) {
            popup.style.display = 'block';
        }
    });
    </script>
    <?php
}
add_action('wp_footer', 'defer_diploma_popup');

// Load child theme styles
function sassly_child_enqueue_styles() {
    wp_enqueue_style('sassly-child-style',
        get_stylesheet_directory_uri() . '/style.css',
        [],
        wp_get_theme()->get('Version')
    );
}
add_action('wp_enqueue_scripts', 'sassly_child_enqueue_styles', 16);
