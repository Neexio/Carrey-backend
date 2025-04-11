<?php
/**
 * Plugin Name: Carrey SEO Tool
 * Plugin URI: https://carrey.ai
 * Description: An advanced SEO analysis tool for WordPress
 * Version: 1.0.0
 * Author: Carrey
 * Author URI: https://carrey.ai
 * License: GPL v2 or later
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Security enhancements
define('CARREY_SEO_VERSION', '1.0.0');
define('CARREY_SEO_MINIMUM_WP_VERSION', '5.0');
define('CARREY_SEO_PLUGIN_DIR', plugin_dir_path(__FILE__));

// Initialize plugin
function carrey_seo_init() {
    // Check WordPress version
    if (version_compare($GLOBALS['wp_version'], CARREY_SEO_MINIMUM_WP_VERSION, '<')) {
        deactivate_plugins(plugin_basename(__FILE__));
        wp_die(
            sprintf(
                __('Carrey SEO Tool requires WordPress version %s or higher.', 'carrey-seo-tool'),
                CARREY_SEO_MINIMUM_WP_VERSION
            )
        );
    }

    // Initialize error logging
    if (!file_exists(CARREY_SEO_PLUGIN_DIR . 'logs')) {
        mkdir(CARREY_SEO_PLUGIN_DIR . 'logs', 0755, true);
    }

    // Load translations
    load_plugin_textdomain('carrey-seo-tool', false, dirname(plugin_basename(__FILE__)) . '/languages/');
}
add_action('init', 'carrey_seo_init');

// Enhanced error handling
function carrey_log_error($message, $data = null) {
    $log_file = CARREY_SEO_PLUGIN_DIR . 'logs/error.log';
    $timestamp = current_time('mysql');
    $log_message = sprintf("[%s] %s\n", $timestamp, $message);
    
    if ($data !== null) {
        $log_message .= print_r($data, true) . "\n";
    }
    
    error_log($log_message, 3, $log_file);
}

// API Rate limiting
class Carrey_Rate_Limiter {
    private $transient_name = 'carrey_rate_limit_';
    private $limit = 60; // requests per minute
    private $period = 60; // seconds

    public function check_limit($user_id) {
        $transient = get_transient($this->transient_name . $user_id);
        
        if (false === $transient) {
            set_transient($this->transient_name . $user_id, 1, $this->period);
            return true;
        }
        
        if ($transient >= $this->limit) {
            return false;
        }
        
        set_transient($this->transient_name . $user_id, $transient + 1, $this->period);
        return true;
    }
}

// Enhanced AJAX handler
function carrey_seo_analyze_ajax() {
    check_ajax_referer('carrey_seo_nonce', 'nonce');

    if (!current_user_can('edit_posts')) {
        wp_send_json_error(['message' => __('Unauthorized access', 'carrey-seo-tool')]);
    }

    $rate_limiter = new Carrey_Rate_Limiter();
    if (!$rate_limiter->check_limit(get_current_user_id())) {
        wp_send_json_error(['message' => __('Rate limit exceeded. Please try again later.', 'carrey-seo-tool')]);
    }

    $url = isset($_POST['url']) ? esc_url_raw($_POST['url']) : '';
    if (empty($url)) {
        wp_send_json_error(['message' => __('Invalid URL provided', 'carrey-seo-tool')]);
    }

    try {
        $analysis_result = carrey_perform_seo_analysis($url);
        wp_send_json_success($analysis_result);
    } catch (Exception $e) {
        carrey_log_error('Analysis failed: ' . $e->getMessage(), ['url' => $url]);
        wp_send_json_error(['message' => __('Analysis failed. Please try again.', 'carrey-seo-tool')]);
    }
}
add_action('wp_ajax_carrey_seo_analyze', 'carrey_seo_analyze_ajax');

// Performance optimization
function carrey_seo_enqueue_scripts($hook) {
    if (!in_array($hook, ['post.php', 'post-new.php', 'toplevel_page_carrey-seo-tool'])) {
        return;
    }

    wp_enqueue_style(
        'carrey-seo-style',
        plugins_url('assets/css/style.min.css', __FILE__),
        [],
        CARREY_SEO_VERSION
    );

    wp_enqueue_script(
        'carrey-seo-script',
        plugins_url('assets/js/script.min.js', __FILE__),
        ['jquery'],
        CARREY_SEO_VERSION,
        true
    );

    wp_localize_script('carrey-seo-script', 'carreySeoAjax', [
        'ajaxurl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('carrey_seo_nonce'),
        'messages' => [
            'analyzing' => __('Analyzing...', 'carrey-seo-tool'),
            'error' => __('An error occurred', 'carrey-seo-tool'),
            'success' => __('Analysis complete', 'carrey-seo-tool')
        ]
    ]);
}
add_action('admin_enqueue_scripts', 'carrey_seo_enqueue_scripts');

// Add shortcode
function carrey_seo_tool_shortcode() {
    ob_start();
    ?>
    <div class="carrey-seo-tool">
        <div class="seo-header">
            <h2>SEO Analysis Tool</h2>
            <p>Enter the URL of the page you want to analyze</p>
        </div>
        
        <form id="seo-analysis-form" method="post" onsubmit="return false;">
            <div class="input-section">
                <input type="text" 
                       id="website-url" 
                       name="website_url" 
                       placeholder="Enter URL (e.g. example.com)" 
                       required 
                       pattern="[a-zA-Z0-9][a-zA-Z0-9-]{1,61}[a-zA-Z0-9](?:\.[a-zA-Z]{2,})+" 
                       title="Please enter a valid domain name" />
                <button type="button" id="analyze-btn" class="carrey-button">Optimize</button>
            </div>
        </form>

        <div id="loading-spinner" style="display: none;">
            <div class="spinner"></div>
            <p>Analyzing website...</p>
        </div>

        <div id="error-message" style="display: none; color: red; margin: 10px 0;"></div>

        <div id="results" style="display: none;">
            <!-- Results will be displayed here -->
        </div>
    </div>

    <style>
    .carrey-seo-tool {
        max-width: 800px;
        margin: 20px auto;
        padding: 30px;
        background: #fff;
        border-radius: 50px;
        box-shadow: 0 10px 20px rgba(0,0,0,0.05);
        transition: all 0.3s ease;
    }

    .carrey-seo-tool:hover {
        transform: translateY(-2px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.1);
    }

    .seo-header {
        text-align: center;
        margin-bottom: 30px;
    }

    .seo-header h2 {
        color: #1a1a1a;
        font-size: 24px;
        margin-bottom: 10px;
    }

    .seo-header p {
        color: #666;
        font-size: 16px;
    }

    .input-section {
        display: flex;
        gap: 10px;
        margin-bottom: 20px;
    }

    .input-section input {
        flex: 1;
        padding: 12px 20px;
        border: 2px solid #e0e0e0;
        border-radius: 50px;
        font-size: 16px;
        transition: all 0.3s ease;
    }

    .input-section input:focus {
        border-color: #21D37E;
        box-shadow: 0 0 0 3px rgba(33, 211, 126, 0.1);
        outline: none;
    }

    .carrey-button {
        background: linear-gradient(135deg, #21D37E 0%, #1ABF6E 100%);
        color: white;
        border: none;
        padding: 12px 30px;
        border-radius: 50px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 4px 6px rgba(26, 191, 110, 0.2);
    }

    .carrey-button:hover {
        background: linear-gradient(135deg, #1ABF6E 0%, #21D37E 100%);
        transform: translateY(-2px);
        box-shadow: 0 6px 12px rgba(26, 191, 110, 0.3);
    }

    .carrey-button:active {
        transform: translateY(0);
        box-shadow: 0 2px 4px rgba(26, 191, 110, 0.2);
    }

    .spinner {
        border: 3px solid rgba(33, 211, 126, 0.1);
        border-top: 3px solid #21D37E;
        border-radius: 50%;
        width: 40px;
        height: 40px;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    .seo-score {
        text-align: center;
        margin: 30px 0;
        padding: 25px;
        background: #f8f9fa;
        border-radius: 12px;
    }

    .score-circle {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        background: #fff;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        margin: 0 auto;
        border: 5px solid #0073aa;
    }

    .score-value {
        font-size: 36px;
        font-weight: bold;
        color: #0073aa;
    }

    .score-label {
        font-size: 14px;
        color: #666;
    }

    .seo-details {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 20px;
        margin: 20px 0;
    }

    .seo-item {
        padding: 20px;
        background: #f8f9fa;
        border-radius: 12px;
        transition: transform 0.3s ease;
    }

    .seo-item:hover {
        transform: translateY(-5px);
    }

    .seo-item.good {
        border-left: 4px solid #4CAF50;
    }

    .seo-item.bad {
        border-left: 4px solid #f44336;
    }

    .seo-item.warning {
        border-left: 4px solid #FFC107;
    }

    .seo-item h4 {
        color: #1a1a1a;
        margin-bottom: 10px;
        font-size: 18px;
    }

    .details {
        font-size: 14px;
        color: #666;
        margin-top: 10px;
    }

    .warning {
        color: #f44336;
    }

    .success {
        color: #4CAF50;
    }

    .seo-issues {
        background: #fff3cd;
        border-left: 4px solid #ffc107;
        padding: 20px;
        margin: 30px 0;
        border-radius: 12px;
    }

    .premium-features {
        background: #e8f4f8;
        border-left: 4px solid #0073aa;
        padding: 20px;
        margin: 30px 0;
        border-radius: 12px;
    }

    .premium-features h4 {
        color: #1a1a1a;
        margin-bottom: 15px;
        font-size: 18px;
    }

    .premium-features ul {
        list-style: none;
        padding: 0;
        margin: 0 0 20px 0;
    }

    .premium-features li {
        margin-bottom: 10px;
        color: #666;
    }

    .premium-features li:before {
        content: "✓";
        color: #4CAF50;
        margin-right: 10px;
    }

    .improvement-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 15px;
        margin: 20px 0;
        padding: 20px;
        background: #f8f9fa;
        border-radius: 8px;
    }

    .stat-item {
        text-align: center;
        padding: 15px;
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .stat-value {
        display: block;
        font-size: 24px;
        font-weight: bold;
        color: #21D37E;
        margin-bottom: 5px;
    }

    .stat-label {
        font-size: 14px;
        color: #666;
    }

    .ai-features {
        list-style: none;
        padding: 0;
        margin: 20px 0;
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 15px;
    }

    .ai-features li {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px;
        background: #fff;
        border-radius: 6px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }

    .ai-features .dashicons {
        color: #21D37E;
    }

    .premium-cta {
        display: inline-block;
        padding: 15px 35px;
        background: linear-gradient(135deg, #21D37E, #1ABF6E);
        color: white;
        border: none;
        border-radius: 50px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s ease;
        box-shadow: 0 4px 6px rgba(26, 191, 110, 0.2);
    }

    .premium-cta:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 12px rgba(26, 191, 110, 0.3);
    }

    .button-text {
        font-size: 18px;
    }

    @media (max-width: 768px) {
        .improvement-stats {
            grid-template-columns: 1fr;
        }

        .ai-features {
            grid-template-columns: 1fr;
        }

        .premium-cta {
            width: 100%;
            max-width: none;
        }

        .carrey-seo-tool {
            padding: 20px;
            margin: 10px;
            border-radius: 30px;
        }

        .input-section {
            flex-direction: column;
            gap: 15px;
        }

        .input-section input,
        .carrey-button {
            width: 100%;
        }

        .unlock-content {
            padding: 20px;
        }
    }

    .blurred-content {
        position: relative;
        filter: blur(4px);
        pointer-events: none;
        user-select: none;
        background: rgba(255, 255, 255, 0.1);
        padding: 20px;
        border-radius: 12px;
        margin-bottom: 20px;
    }

    .blurred-text {
        color: #333;
        font-size: 16px;
        margin-bottom: 10px;
        opacity: 0.7;
    }

    .unlock-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(255, 255, 255, 0.97);
        display: flex;
        align-items: center;
        justify-content: center;
        pointer-events: auto;
        border-radius: 12px;
        backdrop-filter: blur(5px);
    }

    .unlock-content {
        text-align: center;
        padding: 30px;
        max-width: 450px;
    }

    .unlock-content h3 {
        color: #1a1a1a;
        font-size: 24px;
        margin-bottom: 15px;
        font-weight: 700;
    }

    .unlock-content p {
        color: #666;
        margin-bottom: 25px;
        line-height: 1.6;
    }
    </style>

    <script>
    jQuery(document).ready(function($) {
        // Ensure ajaxurl is available
        if (typeof ajaxurl === 'undefined') {
            ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
        }

        const form = $('#seo-analysis-form');
        const analyzeBtn = $('#analyze-btn');
        const urlInput = $('#website-url');
        const loadingSpinner = $('#loading-spinner');
        const resultsDiv = $('#results');
        const errorDiv = $('#error-message');

        // Håndter skjema-innsending
        form.on('submit', function(e) {
            e.preventDefault(); // Forhindrer standard skjema-innsending
            analyzeWebsite();
        });

        // Håndter enter-tast
        urlInput.on('keypress', function(e) {
            if (e.which === 13) {
                e.preventDefault(); // Forhindrer standard enter-oppførsel
                analyzeWebsite();
            }
        });

        // Debounce function for input validation
        const debounce = (func, wait) => {
            let timeout;
            return function executedFunction(...args) {
                const later = () => {
                    clearTimeout(timeout);
                    func(...args);
                };
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
            };
        };

        // Validate URL as user types
        urlInput.on('input', debounce(function() {
            const url = $(this).val().trim();
            if (url && !isValidUrl(url)) {
                $(this).addClass('invalid');
                showError('Please enter a valid URL');
            } else {
                $(this).removeClass('invalid');
                hideError();
            }
        }, 500));

        function isValidUrl(string) {
            try {
                new URL(string.startsWith('http') ? string : 'https://' + string);
                return true;
            } catch (e) {
                return false;
            }
        }

        // Forbedret analyselogikk
        async function analyzeWebsite() {
            let url = urlInput.val().trim();
            
            if (!url.startsWith('http://') && !url.startsWith('https://')) {
                url = 'https://' + url;
            }

            if (!isValidUrl(url)) {
                showError('Please enter a valid URL');
                return;
            }

            showLoading();
            clearResults();
            hideError();

            try {
                console.log('Analyzing URL:', url); // Debug logging

                const response = await $.ajax({
                    url: ajaxurl,
                    type: 'POST',
                    dataType: 'json',
                    timeout: 30000,
                    data: {
                        action: 'carrey_seo_analyze',
                        url: url,
                        nonce: '<?php echo wp_create_nonce('carrey_seo_analyze'); ?>'
                    }
                });

                console.log('Server response:', response); // Debug logging

                if (response && response.success && response.data) {
                    displayResults(response.data);
                } else {
                    const errorMessage = response?.data?.message || 'Could not analyze the website. Please try again.';
                    showError(errorMessage);
                }
            } catch (error) {
                console.error('Analysis error:', error);
                let errorMessage = 'An error occurred during analysis.';
                
                if (error.statusText === 'timeout') {
                    errorMessage = 'Analysis took too long. Please try again.';
                } else if (error.responseJSON?.data?.message) {
                    errorMessage = error.responseJSON.data.message;
                } else if (error.status === 0) {
                    errorMessage = 'Could not connect to the server. Please check your internet connection.';
                } else if (error.status === 403) {
                    errorMessage = 'Access denied. Please refresh the page and try again.';
                } else if (error.status === 404) {
                    errorMessage = 'The analysis service is currently unavailable. Please try again later.';
                } else if (error.status >= 500) {
                    errorMessage = 'Server error. Please try again later.';
                }
                
                showError(errorMessage);
            } finally {
                hideLoading();
            }
        }

        function showLoading() {
            loadingSpinner.show();
            analyzeBtn.prop('disabled', true);
        }

        function hideLoading() {
            loadingSpinner.hide();
            analyzeBtn.prop('disabled', false);
        }

        function showError(message) {
            console.log('Error:', message); // For debugging
            errorDiv.html(`<div class="error-message">${message}</div>`).show();
        }

        function hideError() {
            errorDiv.hide();
        }

        function clearResults() {
            resultsDiv.empty().hide();
        }

        function displayResults(data) {
            console.log('Received data:', data);

            if (!data) {
                showError('No data received from server');
                return;
            }

            const { score, issues, metrics } = data;

            let scoreClass = 'poor';
            if (score >= 90) scoreClass = 'excellent';
            else if (score >= 70) scoreClass = 'good';
            else if (score >= 50) scoreClass = 'fair';

            let resultsHTML = `
                <div class="seo-score ${scoreClass}">
                    <div class="score-circle">
                        <span class="score-number">${score}</span>
                        <span class="score-label">SEO Score</span>
                    </div>
                    <div class="score-message">
                        ${getScoreMessage(score)}
                    </div>
                </div>

                <div class="seo-metrics">
                    <div class="metrics-grid">
                        <div class="metric-item">
                            <span class="metric-value">55%</span>
                            <span class="metric-label">Average Traffic Increase</span>
                        </div>
                        <div class="metric-item">
                            <span class="metric-value">39%</span>
                            <span class="metric-label">Conversion Rate Boost</span>
                        </div>
                        <div class="metric-item">
                            <span class="metric-value">48%</span>
                            <span class="metric-label">Search Ranking Improvement</span>
                        </div>
                    </div>
                </div>

                <div class="seo-issues">
                    <h4>Issues to fix:</h4>
                    <div class="blurred-content">
                        <ul>
                            <li class="blurred-text">No meta description found</li>
                            <li class="blurred-text">Some images are missing alt text</li>
                            <li class="blurred-text">Website is not optimized for mobile</li>
                            <li class="blurred-text">No internal links found</li>
                        </ul>
                        <div class="unlock-overlay">
                            <div class="unlock-content">
                                <h3>Unlock Your Website's Full Potential</h3>
                                <p>We've identified several opportunities to significantly improve your online presence</p>
                                <a href="https://carrey.ai/pricing/" class="premium-cta" target="_blank">
                                    <span class="button-text">Optimize Now</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="premium-features">
                    <h4>Unlock AI-Powered SEO Optimization:</h4>
                    <div class="ai-features">
                        <div class="feature-item">
                            <span class="feature-check">✓</span>
                            <span>AI-Powered Content Optimization</span>
                        </div>
                        <div class="feature-item">
                            <span class="feature-check">✓</span>
                            <span>Smart Keyword Analysis & Suggestions</span>
                        </div>
                        <div class="feature-item">
                            <span class="feature-check">✓</span>
                            <span>Automated Technical SEO Fixes</span>
                        </div>
                        <div class="feature-item">
                            <span class="feature-check">✓</span>
                            <span>Real-time Performance Monitoring</span>
                        </div>
                        <div class="feature-item">
                            <span class="feature-check">✓</span>
                            <span>AI-Driven Content Recommendations</span>
                        </div>
                        <div class="feature-item">
                            <span class="feature-check">✓</span>
                            <span>Automated Meta Tag Optimization</span>
                        </div>
                        <div class="feature-item">
                            <span class="feature-check">✓</span>
                            <span>Smart Internal Linking Suggestions</span>
                        </div>
                        <div class="feature-item">
                            <span class="feature-check">✓</span>
                            <span>AI-Powered Competitor Analysis</span>
                        </div>
                    </div>

                    <div class="cta-section">
                        <a href="https://carrey.ai/pricing/" class="premium-cta" target="_blank">
                            <span class="button-text">Optimize Today</span>
                        </a>
                    </div>
                </div>`;

            resultsDiv.html(resultsHTML).show();
        }

        function getScoreMessage(score) {
            if (score >= 90) return 'Excellent! Your website is well optimized.';
            if (score >= 70) return 'Good! A few improvements can be made.';
            if (score >= 50) return 'There is room for improvement.';
            return 'Your website needs optimization.';
        }

        // Add click handler for the analyze button
        analyzeBtn.on('click', analyzeWebsite);

        // Add new styles
        $('<style>')
            .text(`
                .seo-metrics {
                    margin: 20px 0;
                    padding: 20px;
                    background: #f8f9fa;
                    border-radius: 8px;
                }

                .metrics-grid {
                    display: grid;
                    grid-template-columns: repeat(3, 1fr);
                    gap: 20px;
                }

                .metric-item {
                    text-align: center;
                    padding: 20px;
                    background: #fff;
                    border-radius: 8px;
                    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
                }

                .metric-value {
                    display: block;
                    font-size: 28px;
                    font-weight: bold;
                    color: #21D37E;
                    margin-bottom: 8px;
                }

                .metric-label {
                    font-size: 14px;
                    color: #666;
                }

                .blurred-content {
                    position: relative;
                    filter: blur(4px);
                    pointer-events: none;
                    user-select: none;
                    background: rgba(255, 255, 255, 0.1);
                    padding: 20px;
                    border-radius: 12px;
                    margin-bottom: 20px;
                }

                .blurred-text {
                    color: #333;
                    font-size: 16px;
                    margin-bottom: 10px;
                    opacity: 0.7;
                }
            `)
            .appendTo('head');
    });
    </script>
    <?php
    return ob_get_clean();
}
add_shortcode('carrey_seo_tool', 'carrey_seo_tool_shortcode');

// Enhanced CRM Dashboard
function carrey_crm_dashboard() {
    add_menu_page(
        __('Carrey CRM', 'carrey-seo-tool'),
        __('Carrey CRM', 'carrey-seo-tool'),
        'manage_options',
        'carrey-crm',
        'carrey_crm_dashboard_page',
        'dashicons-chart-area',
        30
    );
}
add_action('admin_menu', 'carrey_crm_dashboard');

function carrey_crm_dashboard_page() {
    // Security check
    if (!current_user_can('manage_options')) {
        wp_die(__('You do not have sufficient permissions to access this page.', 'carrey-seo-tool'));
    }

    // Get analytics data
    $analytics = carrey_get_analytics_data();
    $recent_activities = carrey_get_recent_activities();
    
    ?>
    <div class="wrap carrey-dashboard">
        <h1><?php _e('Carrey CRM Dashboard', 'carrey-seo-tool'); ?></h1>
        
        <div class="carrey-dashboard-grid">
            <!-- Overview Cards -->
            <div class="overview-cards">
                <div class="card">
                    <h3><?php _e('Total Websites', 'carrey-seo-tool'); ?></h3>
                    <div class="number"><?php echo esc_html($analytics['total_websites']); ?></div>
                    <div class="trend up">+<?php echo esc_html($analytics['website_growth']); ?>%</div>
                </div>
                
                <div class="card">
                    <h3><?php _e('Average SEO Score', 'carrey-seo-tool'); ?></h3>
                    <div class="number"><?php echo esc_html($analytics['avg_seo_score']); ?></div>
                    <div class="trend up">+<?php echo esc_html($analytics['score_improvement']); ?>%</div>
                </div>
                
                <div class="card">
                    <h3><?php _e('Active Users', 'carrey-seo-tool'); ?></h3>
                    <div class="number"><?php echo esc_html($analytics['active_users']); ?></div>
                    <div class="trend up">+<?php echo esc_html($analytics['user_growth']); ?>%</div>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="recent-activity">
                <h2><?php _e('Recent Activity', 'carrey-seo-tool'); ?></h2>
                <div class="activity-list">
                    <?php foreach ($recent_activities as $activity): ?>
                    <div class="activity-item">
                        <div class="activity-icon <?php echo esc_attr($activity['type']); ?>"></div>
                        <div class="activity-content">
                            <div class="activity-title"><?php echo esc_html($activity['title']); ?></div>
                            <div class="activity-time"><?php echo esc_html($activity['time']); ?></div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="quick-actions">
                <h2><?php _e('Quick Actions', 'carrey-seo-tool'); ?></h2>
                <div class="action-buttons">
                    <button class="action-button" data-action="new-analysis">
                        <?php _e('New Analysis', 'carrey-seo-tool'); ?>
                    </button>
                    <button class="action-button" data-action="export-report">
                        <?php _e('Export Report', 'carrey-seo-tool'); ?>
                    </button>
                    <button class="action-button" data-action="settings">
                        <?php _e('Settings', 'carrey-seo-tool'); ?>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <style>
    .carrey-dashboard {
        padding: 20px;
    }

    .carrey-dashboard-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 20px;
        margin-top: 20px;
    }

    .overview-cards {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 15px;
    }

    .card {
        background: white;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        transition: transform 0.3s ease;
    }

    .card:hover {
        transform: translateY(-5px);
    }

    .card h3 {
        margin: 0 0 10px 0;
        color: #666;
        font-size: 14px;
    }

    .card .number {
        font-size: 24px;
        font-weight: bold;
        color: #333;
    }

    .card .trend {
        margin-top: 10px;
        font-size: 12px;
    }

    .trend.up {
        color: #4CAF50;
    }

    .trend.down {
        color: #f44336;
    }

    .activity-list {
        background: white;
        border-radius: 10px;
        padding: 15px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .activity-item {
        display: flex;
        align-items: center;
        padding: 10px;
        border-bottom: 1px solid #eee;
    }

    .activity-item:last-child {
        border-bottom: none;
    }

    .activity-icon {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        margin-right: 15px;
        background: #e0e0e0;
    }

    .quick-actions {
        background: white;
        border-radius: 10px;
        padding: 20px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .action-buttons {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 10px;
        margin-top: 15px;
    }

    .action-button {
        background: #21D37E;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 5px;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .action-button:hover {
        background: #1ABF6E;
        transform: translateY(-2px);
    }
    </style>
    <?php
}

// Helper functions for dashboard
function carrey_get_analytics_data() {
    // In a real implementation, this would fetch data from your analytics system
    return [
        'total_websites' => get_option('carrey_total_websites', 0),
        'website_growth' => get_option('carrey_website_growth', 15),
        'avg_seo_score' => get_option('carrey_avg_seo_score', 85),
        'score_improvement' => get_option('carrey_score_improvement', 12),
        'active_users' => get_option('carrey_active_users', 250),
        'user_growth' => get_option('carrey_user_growth', 8)
    ];
}

function carrey_get_recent_activities() {
    // In a real implementation, this would fetch from your activity log
    return [
        [
            'type' => 'analysis',
            'title' => __('New SEO Analysis Completed', 'carrey-seo-tool'),
            'time' => '5 minutes ago'
        ],
        [
            'type' => 'update',
            'title' => __('Website Score Updated', 'carrey-seo-tool'),
            'time' => '1 hour ago'
        ],
        [
            'type' => 'alert',
            'title' => __('New SEO Issue Detected', 'carrey-seo-tool'),
            'time' => '2 hours ago'
        ]
    ];
}

// Add custom login page styling
function carrey_custom_login_style() {
    ?>
    <style type="text/css">
        .wp-core-ui .button-primary {
            background: #21D37E !important;
            border-color: #1ABF6E !important;
            color: #fff !important;
            box-shadow: 0 4px 6px rgba(26, 191, 110, 0.2) !important;
        }
        .wp-core-ui .button-primary:hover {
            background: linear-gradient(135deg, #21D37E 0%, #1ABF6E 100%) !important;
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(26, 191, 110, 0.3) !important;
        }
        #login h1 a {
            background-image: url('<?php echo plugins_url("assets/carrey-logo.png", __FILE__); ?>');
        }
        .login #backtoblog a, .login #nav a {
            color: #21D37E !important;
        }
        .login #backtoblog a:hover, .login #nav a:hover {
            color: #1ABF6E !important;
        }
    </style>
    <?php
}
add_action('login_enqueue_scripts', 'carrey_custom_login_style');

// Add custom admin styling
function carrey_custom_admin_style() {
    ?>
    <style type="text/css">
        /* Primary Button Styling */
        .wp-core-ui .button-primary {
            background: #21D37E !important;
            border-color: #1ABF6E !important;
            color: #fff !important;
        }
        .wp-core-ui .button-primary:hover {
            background: linear-gradient(135deg, #21D37E 0%, #1ABF6E 100%) !important;
        }

        /* Admin Menu Highlighting */
        #adminmenu .wp-has-current-submenu .wp-submenu .wp-submenu-head,
        #adminmenu .wp-menu-arrow,
        #adminmenu .wp-menu-arrow div,
        #adminmenu li.current a.menu-top,
        #adminmenu li.wp-has-current-submenu a.wp-has-current-submenu {
            background: #21D37E !important;
        }

        /* Custom CRM Dashboard Styling */
        .crm-stats .stat-box {
            border-left: 4px solid #21D37E;
        }
        
        .stat-number {
            color: #21D37E !important;
        }

        .premium-feature {
            border-left: 4px solid #21D37E;
            padding: 15px;
            margin: 10px 0;
            background: #f8f9fa;
            border-radius: 8px;
        }

        .premium-cta {
            background: linear-gradient(135deg, #21D37E 0%, #1ABF6E 100%) !important;
        }
    </style>
    <?php
}
add_action('admin_head', 'carrey_custom_admin_style');

// Enhanced SEO Analysis
function carrey_perform_seo_analysis($url) {
    try {
        // Validate URL
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            throw new Exception(__('Invalid URL format', 'carrey-seo-tool'));
        }

        // Check cache first
        $cache_key = 'carrey_seo_' . md5($url);
        $cached_result = get_transient($cache_key);
        
        if (false !== $cached_result) {
            return $cached_result;
        }

        // Initialize metrics
        $metrics = [
            'title' => '',
            'meta_description' => '',
            'headings' => [],
            'images' => [],
            'links' => [],
            'performance' => [],
            'mobile_friendly' => false,
            'ssl_enabled' => false,
            'load_time' => 0
        ];

        // Fetch and analyze the URL
        $response = wp_remote_get($url, [
            'timeout' => 30,
            'sslverify' => false
        ]);

        if (is_wp_error($response)) {
            throw new Exception($response->get_error_message());
        }

        $body = wp_remote_retrieve_body($response);
        $dom = new DOMDocument();
        @$dom->loadHTML($body, LIBXML_NOERROR);
        $xpath = new DOMXPath($dom);

        // Analyze title
        $title_node = $xpath->query('//title');
        if ($title_node->length > 0) {
            $metrics['title'] = trim($title_node->item(0)->nodeValue);
        }

        // Analyze meta description
        $meta_desc = $xpath->query('//meta[@name="description"]/@content');
        if ($meta_desc->length > 0) {
            $metrics['meta_description'] = trim($meta_desc->item(0)->nodeValue);
        }

        // Analyze headings
        foreach (['h1', 'h2', 'h3'] as $heading) {
            $heading_nodes = $xpath->query("//{$heading}");
            $metrics['headings'][$heading] = [];
            foreach ($heading_nodes as $node) {
                $metrics['headings'][$heading][] = trim($node->nodeValue);
            }
        }

        // Analyze images
        $images = $xpath->query('//img');
        foreach ($images as $img) {
            $metrics['images'][] = [
                'src' => $img->getAttribute('src'),
                'alt' => $img->getAttribute('alt'),
                'has_alt' => $img->hasAttribute('alt')
            ];
        }

        // Analyze links
        $links = $xpath->query('//a[@href]');
        foreach ($links as $link) {
            $href = $link->getAttribute('href');
            if (strpos($href, 'http') === 0) {
                $metrics['links'][] = [
                    'url' => $href,
                    'text' => trim($link->nodeValue),
                    'is_external' => strpos($href, $url) !== 0
                ];
            }
        }

        // Check SSL
        $metrics['ssl_enabled'] = strpos($url, 'https://') === 0;

        // Calculate scores
        $scores = carrey_calculate_seo_scores($metrics);

        // Prepare recommendations
        $recommendations = carrey_generate_recommendations($metrics, $scores);

        // Prepare final result
        $result = [
            'url' => $url,
            'metrics' => $metrics,
            'scores' => $scores,
            'recommendations' => $recommendations,
            'timestamp' => current_time('mysql')
        ];

        // Cache the result
        set_transient($cache_key, $result, HOUR_IN_SECONDS);

        return $result;

    } catch (Exception $e) {
        carrey_log_error('SEO Analysis failed: ' . $e->getMessage(), ['url' => $url]);
        throw $e;
    }
}

function carrey_calculate_seo_scores($metrics) {
    $scores = [
        'title' => 0,
        'meta_description' => 0,
        'headings' => 0,
        'images' => 0,
        'links' => 0,
        'technical' => 0
    ];

    // Title score
    if (!empty($metrics['title'])) {
        $title_length = strlen($metrics['title']);
        $scores['title'] = ($title_length >= 30 && $title_length <= 60) ? 100 : 50;
    }

    // Meta description score
    if (!empty($metrics['meta_description'])) {
        $desc_length = strlen($metrics['meta_description']);
        $scores['meta_description'] = ($desc_length >= 120 && $desc_length <= 160) ? 100 : 50;
    }

    // Headings score
    if (!empty($metrics['headings']['h1']) && count($metrics['headings']['h1']) === 1) {
        $scores['headings'] += 50;
    }
    if (!empty($metrics['headings']['h2']) && !empty($metrics['headings']['h3'])) {
        $scores['headings'] += 50;
    }

    // Images score
    $total_images = count($metrics['images']);
    $images_with_alt = 0;
    foreach ($metrics['images'] as $image) {
        if ($image['has_alt']) {
            $images_with_alt++;
        }
    }
    $scores['images'] = $total_images > 0 ? ($images_with_alt / $total_images) * 100 : 100;

    // Links score
    $total_links = count($metrics['links']);
    $scores['links'] = $total_links > 0 ? 100 : 50;

    // Technical score
    $scores['technical'] = $metrics['ssl_enabled'] ? 100 : 0;

    // Calculate overall score
    $scores['overall'] = array_sum($scores) / count($scores);

    return $scores;
}

function carrey_generate_recommendations($metrics, $scores) {
    $recommendations = [];

    // Title recommendations
    if ($scores['title'] < 100) {
        $recommendations[] = [
            'type' => 'title',
            'severity' => 'high',
            'message' => __('Optimize your title length (30-60 characters) for better SEO performance.', 'carrey-seo-tool')
        ];
    }

    // Meta description recommendations
    if ($scores['meta_description'] < 100) {
        $recommendations[] = [
            'type' => 'meta_description',
            'severity' => 'high',
            'message' => __('Improve your meta description length (120-160 characters) for better click-through rates.', 'carrey-seo-tool')
        ];
    }

    // Headings recommendations
    if (empty($metrics['headings']['h1'])) {
        $recommendations[] = [
            'type' => 'headings',
            'severity' => 'high',
            'message' => __('Add an H1 heading to your page for better structure and SEO.', 'carrey-seo-tool')
        ];
    }

    // Images recommendations
    $images_without_alt = 0;
    foreach ($metrics['images'] as $image) {
        if (!$image['has_alt']) {
            $images_without_alt++;
        }
    }
    if ($images_without_alt > 0) {
        $recommendations[] = [
            'type' => 'images',
            'severity' => 'medium',
            'message' => sprintf(
                __('Add alt text to %d images for better accessibility and SEO.', 'carrey-seo-tool'),
                $images_without_alt
            )
        ];
    }

    // SSL recommendations
    if (!$metrics['ssl_enabled']) {
        $recommendations[] = [
            'type' => 'ssl',
            'severity' => 'high',
            'message' => __('Enable SSL (HTTPS) for better security and SEO performance.', 'carrey-seo-tool')
        ];
    }

    return $recommendations;
} 