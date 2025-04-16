<?php
// Custom functions split out from functions.php for modularity

// Remove WP emoji scripts
remove_action('wp_head', 'print_emoji_detection_script', 7);
remove_action('wp_print_styles', 'print_emoji_styles');

// Disable jQuery migrate
function carrey_remove_jquery_migrate( $scripts ) {
    if ( ! is_admin() && isset( $scripts->registered['jquery'] ) ) {
        $script = $scripts->registered['jquery'];
        if ( $script->deps ) {
            $script->deps = array_diff( $script->deps, array( 'jquery-migrate' ) );
        }
    }
}
add_filter( 'wp_default_scripts', 'carrey_remove_jquery_migrate' );

// Lazy load embeds and iframes
add_filter('embed_oembed_html', function ($html) {
    return str_replace('src=', 'loading="lazy" src=', $html);
}, PHP_INT_MAX);

// Optional: Load tools dashboard script only on /tools/
add_action('wp_enqueue_scripts', function () {
    if (is_page('tools')) {
        wp_enqueue_script('carrey-tools-dashboard', get_stylesheet_directory_uri() . '/assets/js/dashboard-tools.js', [], null, true);
    }
});
