<?php
/**
 * Theme functions and definitions.
 */
// Test if child theme is loaded
add_action('wp_head', 'sassly_child_theme_test');
function sassly_child_theme_test() {
    echo '<!-- Child theme is loaded -->';
}

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
        .site-header { opacity: 1; }
        .main-content { opacity: 1; }
    </style>
    <?php
}

// Hjelpefunksjon for å fikse plugin-listen
add_action('admin_init', function () {
    if (isset($_GET['fix_plugins']) && current_user_can('manage_options')) {
        update_option('active_plugins', ['carrey-user-panel/carrey-user-panel.php']);
        wp_cache_flush();
        echo "<div class='notice notice-success'><p>Plugin-listen er nå oppdatert!</p></div>";
    }
});

// Legg til Font Awesome
function carrey_enqueue_font_awesome() {
    wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css', [], null);
}
add_action('wp_enqueue_scripts', 'carrey_enqueue_font_awesome');

// Optimaliser ikoner
function carrey_optimize_icons() {
    add_filter('wp_get_attachment_image_attributes', function($attr, $attachment) {
        if (isset($attr['class']) && (
            strpos($attr['class'], 'icon') !== false || 
            strpos($attr['class'], 'social-icon') !== false || 
            strpos($attr['class'], 'feature-icon') !== false
        )) {
            $attr['style'] = 'width: 24px; height: 24px; max-width: 24px; max-height: 24px;';
        }
        return $attr;
    }, 10, 2);
}
add_action('init', 'carrey_optimize_icons');

// Legg til JavaScript-fiks for ikoner
function carrey_fix_icons_js() {
    ?>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Fiks for diplomikonet og andre store ikoner
        const fixLargeIcons = () => {
            const icons = document.querySelectorAll('svg, i.icon, img[class*="icon"], .diploma-icon, [class*="diploma"], [class*="trophy"], [class*="certificate"]');
            icons.forEach(icon => {
                icon.style.maxWidth = '50px';
                icon.style.maxHeight = '50px';
                icon.style.width = 'auto';
                icon.style.height = 'auto';
                icon.style.display = 'inline-block';
                icon.style.overflow = 'hidden';
            });
        };

        // Kjør ved lasting og ved resize
        fixLargeIcons();
        window.addEventListener('resize', fixLargeIcons);
    });
    </script>
    <?php
}
add_action('wp_footer', 'carrey_fix_icons_js');
