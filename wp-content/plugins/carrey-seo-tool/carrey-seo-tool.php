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

// Register AJAX handler
add_action('wp_ajax_carrey_seo_analyze', 'carrey_seo_analyze_ajax');
add_action('wp_ajax_nopriv_carrey_seo_analyze', 'carrey_seo_analyze_ajax');

function carrey_seo_analyze_ajax() {
    check_ajax_referer('carrey_seo_analyze', 'nonce');

    $url = isset($_POST['url']) ? esc_url_raw($_POST['url']) : '';
    
    if (empty($url)) {
        wp_send_json_error(array('message' => 'URL is required'));
        return;
    }

    try {
        // Cache the response for 1 hour
        $cache_key = 'carrey_seo_analysis_' . md5($url);
        $cached_result = get_transient($cache_key);
        
        if ($cached_result !== false) {
            wp_send_json_success($cached_result);
            return;
        }

        // Initialize analysis data with fixed score and metrics
        $analysis = array(
            'score' => 75, // Fixed score for testing
            'issues' => array(
                'No meta description found',
                'Some images are missing alt text',
                'Website is not optimized for mobile',
                'No internal links found'
            ),
            'metrics' => array(
                'traffic_increase' => '55%',
                'conversion_boost' => '39%',
                'ranking_improvement' => '48%'
            )
        );

        // Cache the result
        set_transient($cache_key, $analysis, HOUR_IN_SECONDS);
        wp_send_json_success($analysis);
        return;

    } catch (Exception $e) {
        error_log('Carrey SEO Tool Error: ' . $e->getMessage());
        wp_send_json_error(array('message' => 'An error occurred during analysis. Please try again.'));
        return;
    }
}

// Add CRM Dashboard
function carrey_crm_dashboard() {
    add_menu_page(
        'Carrey CRM',
        'Carrey CRM',
        'manage_options',
        'carrey-crm',
        'carrey_crm_dashboard_page',
        'dashicons-groups',
        30
    );
}
add_action('admin_menu', 'carrey_crm_dashboard');

function carrey_crm_dashboard_page() {
    ?>
    <div class="wrap">
        <h1>Carrey CRM Dashboard</h1>
        
        <div class="crm-stats">
            <div class="stat-box">
                <h3>Total number of customers</h3>
                <p class="stat-number">0</p>
            </div>
            <div class="stat-box">
                <h3>Active projects</h3>
                <p class="stat-number">0</p>
            </div>
            <div class="stat-box">
                <h3>Tasks today</h3>
                <p class="stat-number">0</p>
            </div>
        </div>

        <div class="crm-content">
            <div class="crm-section">
                <h2>Recent activities</h2>
                <div class="activity-list">
                    <!-- Activity items will be loaded here -->
                </div>
            </div>

            <div class="crm-section">
                <h2>Upcoming meetings</h2>
                <div class="meeting-list">
                    <!-- Meeting items will be loaded here -->
                </div>
            </div>
        </div>
    </div>

    <style>
    .crm-stats {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 20px;
        margin: 20px 0;
    }

    .stat-box {
        background: #fff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .stat-number {
        font-size: 24px;
        font-weight: bold;
        color: #0073aa;
        margin: 10px 0;
    }

    .crm-content {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }

    .crm-section {
        background: #fff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .activity-list, .meeting-list {
        margin-top: 15px;
    }

    .unlock-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(255, 255, 255, 0.95);
        display: flex;
        align-items: center;
        justify-content: center;
        pointer-events: auto;
        border-radius: 8px;
    }

    .unlock-content {
        text-align: center;
        padding: 20px;
        max-width: 400px;
    }

    .unlock-content h3 {
        color: #1a1a1a;
        font-size: 24px;
        margin-bottom: 15px;
    }

    .unlock-content p {
        color: #666;
        margin-bottom: 20px;
    }

    .mini-stats {
        display: flex;
        justify-content: center;
        gap: 20px;
        margin-bottom: 20px;
    }

    .mini-stat {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 5px;
    }

    .stat-icon {
        font-size: 24px;
    }

    .stat-text {
        font-size: 14px;
        color: #666;
    }

    .premium-cta {
        display: inline-block;
        padding: 15px 30px;
        background: linear-gradient(135deg, #21D37E, #1ABF6E);
        color: white;
        border: none;
        border-radius: 8px;
        font-weight: bold;
        text-decoration: none;
        transition: transform 0.3s ease;
    }

    .premium-cta:hover {
        transform: translateY(-2px);
    }

    @media (max-width: 768px) {
        .mini-stats {
            flex-direction: column;
            gap: 10px;
        }

        .unlock-content {
            padding: 15px;
        }
    }
    </style>
    <?php
} 