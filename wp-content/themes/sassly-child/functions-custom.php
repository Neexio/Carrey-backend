<?php
// ==========================
// functions-custom.php
// ==========================

// 🔥 Remove WordPress emoji scripts
remove_action('wp_head', 'print_emoji_detection_script', 7);
remove_action('wp_print_styles', 'print_emoji_styles');

// ✅ Debug log to confirm file is loaded
add_action('init', function () {
    error_log('🔥 functions-custom.php loaded ✅');
});

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

// ⚙️ Load dashboard JS only on /tools page
function carrey_load_dashboard_scripts() {
    if (is_page('tools')) { // Update slug if needed
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

// 🔗 Register REST API endpoint for GPT-powered analyzer
add_action('rest_api_init', function () {
    register_rest_route('carrey/v1', '/analyze', [
        'methods' => 'POST',
        'callback' => 'carrey_analyze_with_gpt',
        'permission_callback' => '__return_true'
    ]);
});

// 🔍 GPT-4 analyzer callback
function carrey_analyze_with_gpt($request) {
    $url = esc_url_raw($request->get_param('url'));
    if (!$url) {
        return new WP_Error('invalid_url', 'URL is required', ['status' => 400]);
    }

    $prompt = "Analyze the SEO of this website: {$url}. Provide:
1. An SEO score (0-100),
2. A short summary,
3. 3 actionable recommendations.
Format the response as JSON: {\"score\":X, \"summary\":\"...\", \"tips\":[\"...\", \"...\", \"...\"]}";

    $response = wp_remote_post('https://api.openai.com/v1/chat/completions', [
        'headers' => [
            'Authorization' => 'Bearer ' . OPENAI_KEY,
            'Content-Type'  => 'application/json',
        ],
        'body' => json_encode([
            'model' => 'gpt-4',
            'messages' => [
                ['role' => 'system', 'content' => 'You are an expert SEO consultant.'],
                ['role' => 'user', 'content' => $prompt],
            ],
            'temperature' => 0.7,
        ]),
        'timeout' => 20
    ]);

    if (is_wp_error($response)) {
        return new WP_Error('gpt_error', $response->get_error_message(), ['status' => 500]);
    }

    $body = json_decode(wp_remote_retrieve_body($response), true);

    if (!isset($body['choices'][0]['message']['content'])) {
        return new WP_Error('invalid_response', 'OpenAI did not return a valid message', ['status' => 500]);
    }

    $content = json_decode($body['choices'][0]['message']['content'], true);

    if (!isset($content['score']) || !isset($content['summary']) || !isset($content['tips'])) {
        return new WP_Error('parse_error', 'Failed to parse GPT response', ['status' => 500]);
    }

    return [
        'success' => true,
        'url'     => $url,
        'score'   => $content['score'],
        'summary' => $content['summary'],
        'tips'    => $content['tips'],
    ];
}
