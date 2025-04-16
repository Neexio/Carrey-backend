<?php
/**
 * Plugin Name: Carrey SEO Dashboard
 * Plugin URI: https://carrey.ai
 * Description: Et avansert SEO dashboard for WordPress
 * Version: 1.0.0
 * Author: Carrey
 * Author URI: https://carrey.ai
 * License: GPL v2 or later
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('CARREY_SEO_VERSION', '1.0.0');
define('CARREY_SEO_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('CARREY_SEO_PLUGIN_URL', plugin_dir_url(__FILE__));

// Include required files
$required_files = array(
    'includes/class-carrey-payment.php',
    'includes/class-carrey-license.php',
    'includes/class-carrey-subscription.php'
);

foreach ($required_files as $file) {
    $file_path = CARREY_SEO_PLUGIN_DIR . $file;
    if (file_exists($file_path)) {
        require_once $file_path;
    } else {
        add_action('admin_notices', function() use ($file) {
            echo '<div class="error"><p>Carrey SEO Dashboard: Manglende fil: ' . esc_html($file) . '</p></div>';
        });
    }
}

// Add Dashboard Menu
function carrey_seo_dashboard_menu() {
    add_menu_page(
        'Carrey SEO Dashboard',
        'SEO Dashboard',
        'manage_options',
        'carrey-seo-dashboard',
        'carrey_seo_dashboard_page',
        'dashicons-chart-line',
        31
    );

    // Add submenu for optimization tools
    add_submenu_page(
        'carrey-seo-dashboard',
        'Optimaliseringsverktøy',
        'Optimaliseringsverktøy',
        'manage_options',
        'carrey-optimization-tools',
        'carrey_optimization_tools_page'
    );

    // Add submenu for reports
    add_submenu_page(
        'carrey-seo-dashboard',
        'Rapporter',
        'Rapporter',
        'manage_options',
        'carrey-reports',
        'carrey_reports_page'
    );

    // Add submenu for settings
    add_submenu_page(
        'carrey-seo-dashboard',
        'Innstillinger',
        'Innstillinger',
        'manage_options',
        'carrey-settings',
        'carrey_settings_page'
    );

    // Add submenu for subscription
    add_submenu_page(
        'carrey-seo-dashboard',
        'Abonnement',
        'Abonnement',
        'manage_options',
        'carrey-subscription',
        'carrey_subscription_page'
    );
}
add_action('admin_menu', 'carrey_seo_dashboard_menu');

function carrey_seo_dashboard_page() {
    // Inkluder Chart.js hvis den ikke allerede er inkludert
    if (!wp_script_is('chart-js', 'enqueued')) {
        wp_enqueue_script('chart-js', 'https://cdn.jsdelivr.net/npm/chart.js', array(), '3.7.0', true);
    }
    
    // Hent brukerdata
    $current_user = wp_get_current_user();
    $user_websites = get_user_meta($current_user->ID, 'carrey_websites', true);
    $user_websites = is_array($user_websites) ? $user_websites : array();
    
    // Hent SEO-statistikk
    $seo_stats = array(
        'total_pages' => count($user_websites),
        'optimized_pages' => 0,
        'issues_found' => 0,
        'improvement_score' => 0
    );
    
    foreach ($user_websites as $website) {
        if (isset($website['seo_score']) && $website['seo_score'] > 80) {
            $seo_stats['optimized_pages']++;
        }
        if (isset($website['issues'])) {
            $seo_stats['issues_found'] += count($website['issues']);
        }
        if (isset($website['improvement_score'])) {
            $seo_stats['improvement_score'] += $website['improvement_score'];
        }
    }
    
    if ($seo_stats['total_pages'] > 0) {
        $seo_stats['improvement_score'] = round($seo_stats['improvement_score'] / $seo_stats['total_pages']);
    }
    ?>
    <div class="wrap">
        <div class="dashboard-header">
            <h1>Velkommen til Carrey SEO Dashboard</h1>
            <p class="welcome-message">Hei <?php echo esc_html($current_user->display_name); ?>, her er oversikten over dine nettsider og SEO-ytelse.</p>
        </div>
        
        <div class="crm-stats">
            <div class="stat-box">
                <div class="stat-icon">🌐</div>
                <h3>Nettsider</h3>
                <p class="stat-number"><?php echo esc_html($seo_stats['total_pages']); ?></p>
                <p class="stat-description">Totalt antall nettsider</p>
            </div>
            <div class="stat-box">
                <div class="stat-icon">📈</div>
                <h3>SEO Score</h3>
                <p class="stat-number"><?php echo esc_html($seo_stats['improvement_score']); ?>%</p>
                <p class="stat-description">Gjennomsnittlig forbedring</p>
            </div>
            <div class="stat-box">
                <div class="stat-icon">⚠️</div>
                <h3>Åpne problemer</h3>
                <p class="stat-number"><?php echo esc_html($seo_stats['issues_found']); ?></p>
                <p class="stat-description">Må løses</p>
            </div>
        </div>

        <div class="crm-content">
            <div class="crm-section">
                <h2><span class="dashicons dashicons-chart-bar"></span> SEO Ytelse</h2>
                <div class="performance-chart">
                    <canvas id="seoPerformanceChart"></canvas>
                </div>
            </div>

            <div class="crm-section">
                <h2><span class="dashicons dashicons-warning"></span> Nylige problemer</h2>
                <div class="issues-list">
                    <?php
                    if (!empty($user_websites)) {
                        foreach ($user_websites as $website) {
                            if (isset($website['issues']) && !empty($website['issues'])) {
                                foreach ($website['issues'] as $issue) {
                                    echo '<div class="issue-item">';
                                    echo '<span class="issue-severity ' . esc_attr($issue['severity']) . '"></span>';
                                    echo '<div class="issue-content">';
                                    echo '<h4>' . esc_html($issue['title']) . '</h4>';
                                    echo '<p>' . esc_html($issue['description']) . '</p>';
                                    echo '</div>';
                                    echo '</div>';
                                }
                            }
                        }
                    } else {
                        echo '<p class="no-issues">Ingen problemer funnet. Alt ser bra ut!</p>';
                    }
                    ?>
                </div>
            </div>

            <div class="crm-section">
                <h2><span class="dashicons dashicons-admin-site"></span> Dine nettsider</h2>
                <div class="websites-list">
                    <?php
                    if (!empty($user_websites)) {
                        foreach ($user_websites as $website) {
                            echo '<div class="website-card">';
                            echo '<div class="website-header">';
                            echo '<h3>' . esc_html($website['name']) . '</h3>';
                            if (isset($website['seo_score'])) {
                                echo '<span class="seo-score ' . ($website['seo_score'] > 80 ? 'good' : 'needs-improvement') . '">';
                                echo esc_html($website['seo_score']) . '%';
                                echo '</span>';
                            }
                            echo '</div>';
                            echo '<p class="website-url">' . esc_html($website['url']) . '</p>';
                            echo '<div class="website-actions">';
                            echo '<a href="' . esc_url($website['url']) . '" target="_blank" class="button">Besøk nettside</a>';
                            echo '<a href="#" class="button button-primary analyze-website" data-website-id="' . esc_attr($website['id']) . '">Analyser på nytt</a>';
                            echo '</div>';
                            echo '</div>';
                        }
                    } else {
                        echo '<div class="no-websites">';
                        echo '<p>Du har ikke lagt til noen nettsider ennå.</p>';
                        echo '<a href="#" class="button button-primary add-website">Legg til nettside</a>';
                        echo '</div>';
                    }
                    ?>
                </div>
            </div>

            <div class="crm-section">
                <h2><span class="dashicons dashicons-lightbulb"></span> Anbefalinger</h2>
                <div class="recommendations-list">
                    <?php
                    $recommendations = array(
                        array(
                            'title' => 'Forbedre sidetitler',
                            'description' => 'Optimaliser sidetitlene for bedre søkemotoroptimalisering',
                            'priority' => 'high'
                        ),
                        array(
                            'title' => 'Legg til meta-beskrivelser',
                            'description' => 'Meta-beskrivelser hjelper med å forbedre klikkrate',
                            'priority' => 'medium'
                        )
                    );

                    foreach ($recommendations as $rec) {
                        echo '<div class="recommendation-item priority-' . esc_attr($rec['priority']) . '">';
                        echo '<h4>' . esc_html($rec['title']) . '</h4>';
                        echo '<p>' . esc_html($rec['description']) . '</p>';
                        echo '<a href="#" class="button">Implementer</a>';
                        echo '</div>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>

    <style>
    .dashboard-header {
        margin-bottom: 30px;
    }

    .welcome-message {
        color: #666;
        font-size: 16px;
        margin-top: 10px;
    }

    .crm-stats {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 20px;
        margin: 20px 0;
    }

    .stat-box {
        background: #fff;
        padding: 25px;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        text-align: center;
        transition: transform 0.3s ease;
    }

    .stat-box:hover {
        transform: translateY(-5px);
    }

    .stat-icon {
        font-size: 32px;
        margin-bottom: 15px;
    }

    .stat-number {
        font-size: 36px;
        font-weight: bold;
        color: #21D37E;
        margin: 10px 0;
    }

    .stat-description {
        color: #666;
        font-size: 14px;
    }

    .crm-content {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
        margin-top: 30px;
    }

    .crm-section {
        background: #fff;
        padding: 25px;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }

    .crm-section h2 {
        margin-top: 0;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .performance-chart {
        height: 300px;
        margin-top: 20px;
    }

    .issues-list, .recommendations-list {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }

    .issue-item, .recommendation-item {
        background: #f8f9fa;
        padding: 15px;
        border-radius: 8px;
        display: flex;
        align-items: flex-start;
        gap: 15px;
    }

    .issue-severity {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        margin-top: 5px;
    }

    .issue-severity.high {
        background: #ff4444;
    }

    .issue-severity.medium {
        background: #ffbb33;
    }

    .issue-severity.low {
        background: #00C851;
    }

    .website-card {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 8px;
        margin-bottom: 15px;
    }

    .website-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
    }

    .seo-score {
        padding: 5px 10px;
        border-radius: 20px;
        font-weight: bold;
    }

    .seo-score.good {
        background: #d4edda;
        color: #155724;
    }

    .seo-score.needs-improvement {
        background: #fff3cd;
        color: #856404;
    }

    .website-actions {
        display: flex;
        gap: 10px;
        margin-top: 15px;
    }

    .priority-high {
        border-left: 4px solid #ff4444;
    }

    .priority-medium {
        border-left: 4px solid #ffbb33;
    }

    .priority-low {
        border-left: 4px solid #00C851;
    }

    @media (max-width: 1200px) {
        .crm-content {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 768px) {
        .crm-stats {
            grid-template-columns: 1fr;
        }
    }
    </style>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize performance chart
        const ctx = document.getElementById('seoPerformanceChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mai', 'Jun'],
                datasets: [{
                    label: 'SEO Score',
                    data: [65, 70, 75, 80, 85, 90],
                    borderColor: '#21D37E',
                    tension: 0.1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100
                    }
                }
            }
        });

        // Add website button click handler
        document.querySelector('.add-website')?.addEventListener('click', function(e) {
            e.preventDefault();
            // Implement add website functionality
        });

        // Analyze website button click handler
        document.querySelectorAll('.analyze-website').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const websiteId = this.dataset.websiteId;
                // Implement analyze website functionality
            });
        });
    });
    </script>
    <?php
}

// Add custom admin styling
function carrey_seo_dashboard_style() {
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
    </style>
    <?php
}
add_action('admin_head', 'carrey_seo_dashboard_style');

// Optimization Tools Page
function carrey_optimization_tools_page() {
    $current_user = wp_get_current_user();
    $user_websites = get_user_meta($current_user->ID, 'carrey_websites', true);
    $user_websites = is_array($user_websites) ? $user_websites : array();
    ?>
    <div class="wrap">
        <h1>Optimaliseringsverktøy</h1>
        
        <div class="optimization-tools">
            <div class="tool-card">
                <div class="tool-icon">🔍</div>
                <h3>Nøkkelord Optimalisering</h3>
                <p>Analyser og optimaliser nøkkelord for bedre søkemotoroptimalisering</p>
                <div class="tool-actions">
                    <button class="button button-primary start-optimization" data-tool="keywords">Start Optimalisering</button>
                </div>
            </div>

            <div class="tool-card">
                <div class="tool-icon">📝</div>
                <h3>Innhold Optimalisering</h3>
                <p>Forbedre innholdet ditt med AI-drevne forslag</p>
                <div class="tool-actions">
                    <button class="button button-primary start-optimization" data-tool="content">Start Optimalisering</button>
                </div>
            </div>

            <div class="tool-card">
                <div class="tool-icon">🚀</div>
                <h3>Hastighet Optimalisering</h3>
                <p>Forbedre nettsidens hastighet og ytelse</p>
                <div class="tool-actions">
                    <button class="button button-primary start-optimization" data-tool="speed">Start Optimalisering</button>
                </div>
            </div>

            <div class="tool-card">
                <div class="tool-icon">📱</div>
                <h3>Mobil Optimalisering</h3>
                <p>Sikre at nettsiden fungerer bra på mobile enheter</p>
                <div class="tool-actions">
                    <button class="button button-primary start-optimization" data-tool="mobile">Start Optimalisering</button>
                </div>
            </div>
        </div>

        <div class="optimization-progress" style="display: none;">
            <h3>Optimalisering pågår</h3>
            <div class="progress-bar">
                <div class="progress"></div>
            </div>
            <p class="progress-status">Forbereder optimalisering...</p>
        </div>

        <div class="optimization-results" style="display: none;">
            <h3>Optimaliseringsresultater</h3>
            <div class="results-content"></div>
        </div>
    </div>

    <style>
    .optimization-tools {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
        margin-top: 20px;
    }

    .tool-card {
        background: #fff;
        padding: 25px;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        text-align: center;
        transition: transform 0.3s ease;
    }

    .tool-card:hover {
        transform: translateY(-5px);
    }

    .tool-icon {
        font-size: 40px;
        margin-bottom: 15px;
    }

    .tool-actions {
        margin-top: 20px;
    }

    .progress-bar {
        background: #f0f0f0;
        height: 20px;
        border-radius: 10px;
        margin: 20px 0;
        overflow: hidden;
    }

    .progress {
        background: #21D37E;
        height: 100%;
        width: 0%;
        transition: width 0.3s ease;
    }

    .progress-status {
        color: #666;
        text-align: center;
    }

    @media (max-width: 1200px) {
        .optimization-tools {
            grid-template-columns: 1fr;
        }
    }
    </style>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const optimizationButtons = document.querySelectorAll('.start-optimization');
        const progressSection = document.querySelector('.optimization-progress');
        const progressBar = document.querySelector('.progress');
        const progressStatus = document.querySelector('.progress-status');
        const resultsSection = document.querySelector('.optimization-results');

        optimizationButtons.forEach(button => {
            button.addEventListener('click', function() {
                const tool = this.dataset.tool;
                startOptimization(tool);
            });
        });

        function startOptimization(tool) {
            // Show progress section
            progressSection.style.display = 'block';
            resultsSection.style.display = 'none';

            // Simulate optimization progress
            let progress = 0;
            const interval = setInterval(() => {
                progress += 5;
                progressBar.style.width = progress + '%';
                
                if (progress <= 25) {
                    progressStatus.textContent = 'Analyserer nettside...';
                } else if (progress <= 50) {
                    progressStatus.textContent = 'Identifiserer forbedringsmuligheter...';
                } else if (progress <= 75) {
                    progressStatus.textContent = 'Implementerer optimaliseringer...';
                } else {
                    progressStatus.textContent = 'Fullfører optimalisering...';
                }

                if (progress >= 100) {
                    clearInterval(interval);
                    showResults(tool);
                }
            }, 500);
        }

        function showResults(tool) {
            progressSection.style.display = 'none';
            resultsSection.style.display = 'block';

            const resultsContent = document.querySelector('.results-content');
            let results = '';

            switch(tool) {
                case 'keywords':
                    results = `
                        <div class="result-item">
                            <h4>Nøkkelord Optimalisering</h4>
                            <p>10 nye nøkkelord identifisert</p>
                            <p>5 eksisterende nøkkelord optimalisert</p>
                            <p>Forventet trafikkøkning: 25%</p>
                        </div>
                    `;
                    break;
                case 'content':
                    results = `
                        <div class="result-item">
                            <h4>Innhold Optimalisering</h4>
                            <p>15 sider analysert</p>
                            <p>8 sider optimalisert</p>
                            <p>Forbedret lesbarhet: 40%</p>
                        </div>
                    `;
                    break;
                case 'speed':
                    results = `
                        <div class="result-item">
                            <h4>Hastighet Optimalisering</h4>
                            <p>Lastetid redusert med 3 sekunder</p>
                            <p>Bildeoptimalisering fullført</p>
                            <p>Cache implementert</p>
                        </div>
                    `;
                    break;
                case 'mobile':
                    results = `
                        <div class="result-item">
                            <h4>Mobil Optimalisering</h4>
                            <p>Responsivt design verifisert</p>
                            <p>Touch-interaksjoner optimalisert</p>
                            <p>Mobil hastighet forbedret</p>
                        </div>
                    `;
                    break;
            }

            resultsContent.innerHTML = results;
        }
    });
    </script>
    <?php
}

// Reports Page
function carrey_reports_page() {
    $current_user = wp_get_current_user();
    $user_websites = get_user_meta($current_user->ID, 'carrey_websites', true);
    $user_websites = is_array($user_websites) ? $user_websites : array();
    ?>
    <div class="wrap">
        <h1>Rapporter</h1>
        
        <div class="report-filters">
            <select id="report-period">
                <option value="7">Siste 7 dager</option>
                <option value="30">Siste 30 dager</option>
                <option value="90">Siste 90 dager</option>
                <option value="365">Siste år</option>
            </select>
            <select id="report-type">
                <option value="traffic">Trafikk</option>
                <option value="keywords">Nøkkelord</option>
                <option value="performance">Ytelse</option>
                <option value="seo">SEO</option>
            </select>
            <button class="button button-primary" id="generate-report">Generer Rapport</button>
        </div>

        <div class="report-content">
            <div class="report-charts">
                <canvas id="trafficChart"></canvas>
                <canvas id="keywordChart"></canvas>
            </div>

            <div class="report-details">
                <h3>Detaljerte Resultater</h3>
                <div class="report-table">
                    <table>
                        <thead>
                            <tr>
                                <th>Dato</th>
                                <th>Besøkende</th>
                                <th>Sidevisninger</th>
                                <th>Gjennomsnittlig tid</th>
                                <th>Konverteringsrate</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Data will be loaded here -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <style>
    .report-filters {
        display: flex;
        gap: 10px;
        margin: 20px 0;
    }

    .report-charts {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
        margin: 20px 0;
    }

    .report-charts canvas {
        background: #fff;
        padding: 20px;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }

    .report-table {
        background: #fff;
        padding: 20px;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        margin-top: 20px;
    }

    .report-table table {
        width: 100%;
        border-collapse: collapse;
    }

    .report-table th,
    .report-table td {
        padding: 10px;
        text-align: left;
        border-bottom: 1px solid #eee;
    }

    .report-table th {
        background: #f8f9fa;
        font-weight: bold;
    }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const generateButton = document.getElementById('generate-report');
        const trafficChart = document.getElementById('trafficChart');
        const keywordChart = document.getElementById('keywordChart');

        generateButton.addEventListener('click', function() {
            generateReport();
        });

        function generateReport() {
            // Initialize traffic chart
            new Chart(trafficChart, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mai', 'Jun'],
                    datasets: [{
                        label: 'Besøkende',
                        data: [1200, 1900, 3000, 5000, 2000, 3000],
                        borderColor: '#21D37E',
                        tension: 0.1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false
                }
            });

            // Initialize keyword chart
            new Chart(keywordChart, {
                type: 'bar',
                data: {
                    labels: ['Nøkkelord 1', 'Nøkkelord 2', 'Nøkkelord 3', 'Nøkkelord 4', 'Nøkkelord 5'],
                    datasets: [{
                        label: 'Klikk',
                        data: [12, 19, 3, 5, 2],
                        backgroundColor: '#21D37E'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false
                }
            });
        }
    });
    </script>
    <?php
}

// Settings Page
function carrey_settings_page() {
    $current_user = wp_get_current_user();
    $settings = get_user_meta($current_user->ID, 'carrey_settings', true);
    $settings = is_array($settings) ? $settings : array();
    ?>
    <div class="wrap">
        <h1>Innstillinger</h1>
        
        <form method="post" action="options.php">
            <?php settings_fields('carrey_settings'); ?>
            
            <div class="settings-section">
                <h3>Varslinger</h3>
                <table class="form-table">
                    <tr>
                        <th>E-post varslinger</th>
                        <td>
                            <label>
                                <input type="checkbox" name="carrey_settings[email_notifications]" 
                                    <?php checked(isset($settings['email_notifications']) ? $settings['email_notifications'] : true); ?>>
                                Motta e-post varslinger om SEO-endringer
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <th>Varslingsfrekvens</th>
                        <td>
                            <select name="carrey_settings[notification_frequency]">
                                <option value="daily" <?php selected(isset($settings['notification_frequency']) ? $settings['notification_frequency'] : 'weekly', 'daily'); ?>>Daglig</option>
                                <option value="weekly" <?php selected(isset($settings['notification_frequency']) ? $settings['notification_frequency'] : 'weekly', 'weekly'); ?>>Ukentlig</option>
                                <option value="monthly" <?php selected(isset($settings['notification_frequency']) ? $settings['notification_frequency'] : 'weekly', 'monthly'); ?>>Månedlig</option>
                            </select>
                        </td>
                    </tr>
                </table>
            </div>

            <div class="settings-section">
                <h3>Automatisk Optimalisering</h3>
                <table class="form-table">
                    <tr>
                        <th>Automatiske forbedringer</th>
                        <td>
                            <label>
                                <input type="checkbox" name="carrey_settings[auto_optimization]" 
                                    <?php checked(isset($settings['auto_optimization']) ? $settings['auto_optimization'] : false); ?>>
                                Aktiver automatiske SEO-forbedringer
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <th>Optimaliseringsnivå</th>
                        <td>
                            <select name="carrey_settings[optimization_level]">
                                <option value="basic" <?php selected(isset($settings['optimization_level']) ? $settings['optimization_level'] : 'basic', 'basic'); ?>>Grunnleggende</option>
                                <option value="advanced" <?php selected(isset($settings['optimization_level']) ? $settings['optimization_level'] : 'basic', 'advanced'); ?>>Avansert</option>
                                <option value="aggressive" <?php selected(isset($settings['optimization_level']) ? $settings['optimization_level'] : 'basic', 'aggressive'); ?>>Aggressiv</option>
                            </select>
                        </td>
                    </tr>
                </table>
            </div>

            <div class="settings-section">
                <h3>Rapporter</h3>
                <table class="form-table">
                    <tr>
                        <th>Rapportformat</th>
                        <td>
                            <select name="carrey_settings[report_format]">
                                <option value="html" <?php selected(isset($settings['report_format']) ? $settings['report_format'] : 'html', 'html'); ?>>HTML</option>
                                <option value="pdf" <?php selected(isset($settings['report_format']) ? $settings['report_format'] : 'html', 'pdf'); ?>>PDF</option>
                                <option value="csv" <?php selected(isset($settings['report_format']) ? $settings['report_format'] : 'html', 'csv'); ?>>CSV</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th>Automatiske rapporter</th>
                        <td>
                            <label>
                                <input type="checkbox" name="carrey_settings[auto_reports]" 
                                    <?php checked(isset($settings['auto_reports']) ? $settings['auto_reports'] : true); ?>>
                                Send automatiske rapporter
                            </label>
                        </td>
                    </tr>
                </table>
            </div>

            <?php submit_button('Lagre innstillinger'); ?>
        </form>
    </div>

    <style>
    .settings-section {
        background: #fff;
        padding: 20px;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        margin-bottom: 20px;
    }

    .settings-section h3 {
        margin-top: 0;
        padding-bottom: 10px;
        border-bottom: 1px solid #eee;
    }

    .form-table th {
        width: 200px;
    }

    .form-table td {
        padding: 15px 10px;
    }

    .form-table input[type="checkbox"] {
        margin-right: 10px;
    }

    .form-table select {
        min-width: 200px;
    }
    </style>
    <?php
}

// Register settings
function carrey_register_settings() {
    register_setting('carrey_settings', 'carrey_settings');
}
add_action('admin_init', 'carrey_register_settings');

// Initialize plugin
function carrey_seo_dashboard_init() {
    // Check license status
    $license_handler = new Carrey_License_Handler();
    if (!$license_handler->verify_license()) {
        add_action('admin_notices', 'carrey_seo_dashboard_license_notice');
    }

    // Check subscription status
    $subscription_handler = new Carrey_Subscription_Handler();
    $current_plan = $subscription_handler->get_current_plan();
    if ($current_plan === 'none') {
        add_action('admin_notices', 'carrey_seo_dashboard_subscription_notice');
    }
}
add_action('admin_init', 'carrey_seo_dashboard_init');

function carrey_seo_dashboard_license_notice() {
    ?>
    <div class="notice notice-warning">
        <p>Carrey SEO Dashboard krever en gyldig lisens. <a href="<?php echo esc_url(admin_url('admin.php?page=carrey-license')); ?>">Aktiver lisens nå</a></p>
    </div>
    <?php
}

function carrey_seo_dashboard_subscription_notice() {
    ?>
    <div class="notice notice-warning">
        <p>Carrey SEO Dashboard krever et aktivt abonnement. <a href="<?php echo esc_url(admin_url('admin.php?page=carrey-subscription')); ?>">Velg abonnementsplan</a></p>
    </div>
    <?php
}

// Register activation hook
register_activation_hook(__FILE__, 'carrey_seo_dashboard_activate');
function carrey_seo_dashboard_activate() {
    // Create necessary database tables
    global $wpdb;
    
    $charset_collate = $wpdb->get_charset_collate();
    
    $sql = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}carrey_licenses (
        id bigint(20) NOT NULL AUTO_INCREMENT,
        license_key varchar(255) NOT NULL,
        user_id bigint(20) NOT NULL,
        status varchar(20) NOT NULL,
        created_at datetime NOT NULL,
        expires_at datetime NOT NULL,
        PRIMARY KEY  (id),
        UNIQUE KEY license_key (license_key)
    ) $charset_collate;";
    
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}

// Register deactivation hook
register_deactivation_hook(__FILE__, 'carrey_seo_dashboard_deactivate');
function carrey_seo_dashboard_deactivate() {
    // Clean up if necessary
}

// Add website management functionality
function carrey_seo_dashboard_add_website() {
    check_ajax_referer('carrey_seo_nonce', 'nonce');
    
    $current_user = wp_get_current_user();
    $website_url = sanitize_text_field($_POST['website_url']);
    $website_name = sanitize_text_field($_POST['website_name']);
    
    // Validate website URL
    if (!filter_var($website_url, FILTER_VALIDATE_URL)) {
        wp_send_json_error('Ugyldig nettadresse');
    }
    
    // Check if website exists
    $user_websites = get_user_meta($current_user->ID, 'carrey_websites', true);
    $user_websites = is_array($user_websites) ? $user_websites : array();
    
    foreach ($user_websites as $website) {
        if ($website['url'] === $website_url) {
            wp_send_json_error('Denne nettsiden er allerede lagt til');
        }
    }
    
    // Add new website
    $new_website = array(
        'id' => uniqid(),
        'name' => $website_name,
        'url' => $website_url,
        'added_date' => current_time('mysql'),
        'seo_score' => 0,
        'issues' => array(),
        'improvement_score' => 0
    );
    
    $user_websites[] = $new_website;
    update_user_meta($current_user->ID, 'carrey_websites', $user_websites);
    
    // Initialize real-time data collection
    carrey_seo_dashboard_collect_data($new_website);
    
    wp_send_json_success('Nettside lagt til');
}
add_action('wp_ajax_carrey_seo_dashboard_add_website', 'carrey_seo_dashboard_add_website');

// Collect real-time data
function carrey_seo_dashboard_collect_data($website) {
    // Collect SEO data
    $seo_data = array(
        'title' => get_bloginfo('name'),
        'description' => get_bloginfo('description'),
        'keywords' => array(),
        'meta_tags' => array(),
        'content_analysis' => array(),
        'performance_metrics' => array()
    );
    
    // Update website data
    $current_user = wp_get_current_user();
    $user_websites = get_user_meta($current_user->ID, 'carrey_websites', true);
    
    foreach ($user_websites as &$site) {
        if ($site['id'] === $website['id']) {
            $site['seo_data'] = $seo_data;
            break;
        }
    }
    
    update_user_meta($current_user->ID, 'carrey_websites', $user_websites);
}

// Add WordPress integration
function carrey_seo_dashboard_wordpress_integration() {
    // Add SEO meta boxes to post editor
    add_meta_box(
        'carrey_seo_meta_box',
        'Carrey SEO',
        'carrey_seo_meta_box_callback',
        'post',
        'normal',
        'high'
    );
    
    // Add SEO settings to customizer
    add_action('customize_register', 'carrey_seo_customize_register');
}
add_action('admin_init', 'carrey_seo_dashboard_wordpress_integration');

function carrey_seo_meta_box_callback($post) {
    wp_nonce_field('carrey_seo_meta_box', 'carrey_seo_meta_box_nonce');
    
    $meta_title = get_post_meta($post->ID, '_carrey_seo_title', true);
    $meta_description = get_post_meta($post->ID, '_carrey_seo_description', true);
    $meta_keywords = get_post_meta($post->ID, '_carrey_seo_keywords', true);
    ?>
    <div class="carrey-seo-meta-box">
        <p>
            <label for="carrey_seo_title">Meta Tittel:</label>
            <input type="text" id="carrey_seo_title" name="carrey_seo_title" value="<?php echo esc_attr($meta_title); ?>" class="widefat">
        </p>
        <p>
            <label for="carrey_seo_description">Meta Beskrivelse:</label>
            <textarea id="carrey_seo_description" name="carrey_seo_description" class="widefat"><?php echo esc_textarea($meta_description); ?></textarea>
        </p>
        <p>
            <label for="carrey_seo_keywords">Nøkkelord:</label>
            <input type="text" id="carrey_seo_keywords" name="carrey_seo_keywords" value="<?php echo esc_attr($meta_keywords); ?>" class="widefat">
            <span class="description">Separer nøkkelord med komma</span>
        </p>
    </div>
    <?php
}

function carrey_seo_save_meta_box($post_id) {
    if (!isset($_POST['carrey_seo_meta_box_nonce'])) {
        return;
    }
    
    if (!wp_verify_nonce($_POST['carrey_seo_meta_box_nonce'], 'carrey_seo_meta_box')) {
        return;
    }
    
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    
    update_post_meta($post_id, '_carrey_seo_title', sanitize_text_field($_POST['carrey_seo_title']));
    update_post_meta($post_id, '_carrey_seo_description', sanitize_textarea_field($_POST['carrey_seo_description']));
    update_post_meta($post_id, '_carrey_seo_keywords', sanitize_text_field($_POST['carrey_seo_keywords']));
}
add_action('save_post', 'carrey_seo_save_meta_box');

function carrey_seo_customize_register($wp_customize) {
    $wp_customize->add_section('carrey_seo_settings', array(
        'title' => 'Carrey SEO Innstillinger',
        'priority' => 30,
    ));
    
    $wp_customize->add_setting('carrey_seo_default_title', array(
        'default' => '',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    
    $wp_customize->add_control('carrey_seo_default_title', array(
        'label' => 'Standard Meta Tittel',
        'section' => 'carrey_seo_settings',
        'type' => 'text',
    ));
    
    $wp_customize->add_setting('carrey_seo_default_description', array(
        'default' => '',
        'sanitize_callback' => 'sanitize_textarea_field',
    ));
    
    $wp_customize->add_control('carrey_seo_default_description', array(
        'label' => 'Standard Meta Beskrivelse',
        'section' => 'carrey_seo_settings',
        'type' => 'textarea',
    ));
}

// Add real-time data updates
function carrey_seo_dashboard_update_data() {
    $current_user = wp_get_current_user();
    $user_websites = get_user_meta($current_user->ID, 'carrey_websites', true);
    
    if (!empty($user_websites)) {
        foreach ($user_websites as &$website) {
            // Update SEO score
            $website['seo_score'] = carrey_calculate_seo_score($website);
            
            // Update issues
            $website['issues'] = carrey_check_seo_issues($website);
            
            // Update improvement score
            $website['improvement_score'] = carrey_calculate_improvement_score($website);
        }
        
        update_user_meta($current_user->ID, 'carrey_websites', $user_websites);
    }
}
add_action('carrey_seo_dashboard_update', 'carrey_seo_dashboard_update_data');

// Schedule data updates
function carrey_seo_dashboard_schedule_updates() {
    if (!wp_next_scheduled('carrey_seo_dashboard_update')) {
        wp_schedule_event(time(), 'hourly', 'carrey_seo_dashboard_update');
    }
}
add_action('wp', 'carrey_seo_dashboard_schedule_updates');

// Helper functions for SEO calculations
function carrey_calculate_seo_score($website) {
    $score = 0;
    
    // Check meta tags
    if (!empty($website['seo_data']['title'])) $score += 20;
    if (!empty($website['seo_data']['description'])) $score += 20;
    if (!empty($website['seo_data']['keywords'])) $score += 10;
    
    // Check content
    if (!empty($website['seo_data']['content_analysis'])) {
        $score += 30;
    }
    
    // Check performance
    if (!empty($website['seo_data']['performance_metrics'])) {
        $score += 20;
    }
    
    return $score;
}

function carrey_check_seo_issues($website) {
    $issues = array();
    
    // Check for missing meta tags
    if (empty($website['seo_data']['title'])) {
        $issues[] = array(
            'title' => 'Manglende meta tittel',
            'description' => 'Legg til en meta tittel for bedre SEO',
            'severity' => 'high'
        );
    }
    
    if (empty($website['seo_data']['description'])) {
        $issues[] = array(
            'title' => 'Manglende meta beskrivelse',
            'description' => 'Legg til en meta beskrivelse for bedre klikkrate',
            'severity' => 'medium'
        );
    }
    
    // Check content issues
    if (empty($website['seo_data']['content_analysis'])) {
        $issues[] = array(
            'title' => 'Manglende innholdsanalyse',
            'description' => 'Analyser innholdet for bedre SEO',
            'severity' => 'medium'
        );
    }
    
    return $issues;
}

function carrey_calculate_improvement_score($website) {
    $score = 0;
    
    // Calculate based on resolved issues
    $total_issues = count($website['issues']);
    $resolved_issues = count(array_filter($website['issues'], function($issue) {
        return isset($issue['resolved']) && $issue['resolved'];
    }));
    
    if ($total_issues > 0) {
        $score = ($resolved_issues / $total_issues) * 100;
    }
    
    return round($score);
}

// Add website form modal
function carrey_seo_dashboard_add_website_modal() {
    ?>
    <div id="add-website-modal" class="modal" style="display: none;">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Legg til ny nettside</h2>
            <form id="add-website-form">
                <?php wp_nonce_field('carrey_seo_nonce', 'carrey_seo_nonce'); ?>
                <div class="form-group">
                    <label for="website_name">Nettsidenavn</label>
                    <input type="text" id="website_name" name="website_name" required>
                </div>
                <div class="form-group">
                    <label for="website_url">Nettside URL</label>
                    <input type="url" id="website_url" name="website_url" required>
                </div>
                <div class="form-actions">
                    <button type="submit" class="button button-primary">Legg til</button>
                    <button type="button" class="button cancel">Avbryt</button>
                </div>
            </form>
        </div>
    </div>

    <style>
    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0,0,0,0.5);
    }

    .modal-content {
        background-color: #fff;
        margin: 15% auto;
        padding: 20px;
        border-radius: 8px;
        width: 50%;
        max-width: 500px;
        position: relative;
    }

    .close {
        position: absolute;
        right: 20px;
        top: 10px;
        font-size: 24px;
        cursor: pointer;
    }

    .form-group {
        margin-bottom: 15px;
    }

    .form-group label {
        display: block;
        margin-bottom: 5px;
    }

    .form-group input {
        width: 100%;
        padding: 8px;
        border: 1px solid #ddd;
        border-radius: 4px;
    }

    .form-actions {
        margin-top: 20px;
        text-align: right;
    }
    </style>

    <script>
    jQuery(document).ready(function($) {
        // Show modal
        $('.add-website').click(function(e) {
            e.preventDefault();
            $('#add-website-modal').show();
        });

        // Close modal
        $('.close, .cancel').click(function() {
            $('#add-website-modal').hide();
        });

        // Handle form submission
        $('#add-website-form').submit(function(e) {
            e.preventDefault();
            
            var formData = {
                action: 'carrey_seo_dashboard_add_website',
                nonce: $('#carrey_seo_nonce').val(),
                website_name: $('#website_name').val(),
                website_url: $('#website_url').val()
            };

            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: formData,
                success: function(response) {
                    if (response.success) {
                        location.reload();
                    } else {
                        alert(response.data);
                    }
                },
                error: function() {
                    alert('Det oppstod en feil. Vennligst prøv igjen.');
                }
            });
        });
    });
    </script>
    <?php
}
add_action('admin_footer', 'carrey_seo_dashboard_add_website_modal');

// Add AJAX handlers
function carrey_seo_dashboard_ajax_init() {
    add_action('wp_ajax_carrey_seo_dashboard_add_website', 'carrey_seo_dashboard_add_website');
    add_action('wp_ajax_carrey_seo_dashboard_analyze_website', 'carrey_seo_dashboard_analyze_website');
}
add_action('init', 'carrey_seo_dashboard_ajax_init');

// Analyze website function
function carrey_seo_dashboard_analyze_website() {
    check_ajax_referer('carrey_seo_nonce', 'nonce');
    
    $current_user = wp_get_current_user();
    $website_id = sanitize_text_field($_POST['website_id']);
    
    $user_websites = get_user_meta($current_user->ID, 'carrey_websites', true);
    $user_websites = is_array($user_websites) ? $user_websites : array();
    
    foreach ($user_websites as &$website) {
        if ($website['id'] === $website_id) {
            // Collect new data
            $website['seo_data'] = carrey_seo_dashboard_collect_data($website);
            
            // Update scores
            $website['seo_score'] = carrey_calculate_seo_score($website);
            $website['issues'] = carrey_check_seo_issues($website);
            $website['improvement_score'] = carrey_calculate_improvement_score($website);
            
            break;
        }
    }
    
    update_user_meta($current_user->ID, 'carrey_websites', $user_websites);
    
    wp_send_json_success('Nettside analysert på nytt');
} 