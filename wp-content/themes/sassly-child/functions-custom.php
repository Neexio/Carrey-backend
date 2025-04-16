<?php
// ==========================
// functions-custom.php
// ==========================

// 🔥 Remove WordPress emoji scripts
remove_action('wp_head', 'print_emoji_detection_script', 7);
remove_action('wp_print_styles', 'print_emoji_styles');

// 🔧 Disable jQuery Migrate on frontend
function carrey_remove_jquery_migrate($scripts) {
    if (!is_admin() && isset($scripts->registered['jquery'])) {
        $script = $scripts->registered['jquery'];
        if ($script->deps) {
            $script->deps = array_diff($script->deps, ['jquery-migrate']);
        }
    }
}
add_filter('wp_default_scripts', 'carrey_remove_jquery_migrate');

// 💤 Lazy-load all embeds (like YouTube or oEmbed)
add_filter('embed_oembed_html', function ($html) {
    return str_replace('src=', 'loading="lazy" src=', $html);
}, PHP_INT_MAX);

// ⚙️ Load dashboard JS only on /tools/ page
function carrey_load_dashboard_scripts() {
    if (is_page('tools')) {
        wp_enqueue_script(
            'carrey-dashboard-js',
            get_stylesheet_directory_uri() . '/assets/js/dashboard-tools.js',
            [],
            null,
            true
        );
    }
}
add_action('wp_enqueue_scripts', 'carrey_load_dashboard_scripts');
