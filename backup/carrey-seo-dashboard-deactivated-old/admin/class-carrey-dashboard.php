<?php
/**
 * Dashboard Class
 *
 * @package Carrey_SEO_Dashboard
 */

if (!defined('ABSPATH')) {
    exit;
}

class Carrey_Dashboard {
    private static $instance = null;
    private $user_websites = array();
    private $seo_stats = array();
    private $automation_settings = array();

    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        add_action('admin_menu', array($this, 'add_menu_pages'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_scripts'));
        add_action('wp_ajax_carrey_analyze_website', array($this, 'analyze_website'));
        add_action('wp_ajax_carrey_automate_seo', array($this, 'automate_seo'));
        $this->load_user_data();
        $this->load_automation_settings();
    }

    public function add_menu_pages() {
        add_menu_page(
            'Carrey SEO',
            'Carrey SEO',
            'manage_options',
            'carrey-seo',
            array($this, 'render_dashboard_page'),
            'dashicons-chart-line',
            30
        );
        
        add_submenu_page(
            'carrey-seo',
            'Register',
            'Register',
            'manage_options',
            'carrey-register',
            array($this, 'render_register_page')
        );
        
        add_submenu_page(
            'carrey-seo',
            'Subscription',
            'Subscription',
            'manage_options',
            'carrey-subscription',
            array($this, 'render_subscription_page')
        );

        add_submenu_page(
            'carrey-seo',
            'SEO Analysis',
            'SEO Analysis',
            'manage_options',
            'carrey-analysis',
            array($this, 'render_analysis_page')
        );

        add_submenu_page(
            'carrey-seo',
            'Automation',
            'Automation',
            'manage_options',
            'carrey-automation',
            array($this, 'render_automation_page')
        );

        add_submenu_page(
            'carrey-seo',
            'Reports',
            'Reports',
            'manage_options',
            'carrey-reports',
            array($this, 'render_reports_page')
        );
    }

    public function enqueue_scripts($hook) {
        if (strpos($hook, 'carrey-seo') === false) {
            return;
        }

        wp_enqueue_style(
            'carrey-dashboard-style',
            plugins_url('admin/css/dashboard.css', dirname(__FILE__)),
            array(),
            CARREY_SEO_VERSION
        );

        wp_enqueue_script(
            'chart-js',
            'https://cdn.jsdelivr.net/npm/chart.js',
            array(),
            '3.7.0',
            true
        );

        wp_enqueue_script(
            'carrey-dashboard-script',
            plugins_url('admin/js/dashboard.js', dirname(__FILE__)),
            array('jquery', 'chart-js'),
            CARREY_SEO_VERSION,
            true
        );

        wp_localize_script('carrey-dashboard-script', 'carreyDashboard', array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('carrey_seo_nonce')
        ));
    }

    private function load_user_data() {
        // Simulert data - i produksjon vil dette komme fra API eller database
        $this->user_websites = array(
            array(
                'url' => 'https://example.com',
                'name' => 'Example Site',
                'seo_score' => 85,
                'issues' => array(
                    'Missing meta descriptions',
                    'Slow page load time',
                    'Missing alt tags on images'
                ),
                'keywords' => array(
                    array('keyword' => 'SEO tools', 'position' => 3, 'change' => '+2'),
                    array('keyword' => 'WordPress SEO', 'position' => 5, 'change' => '-1')
                ),
                'traffic' => array(
                    'organic' => 1500,
                    'direct' => 800,
                    'referral' => 300
                ),
                'performance' => array(
                    'load_time' => 2.5,
                    'ttfb' => 0.8,
                    'page_size' => 1.8
                )
            )
        );

        $this->calculate_seo_stats();
    }

    private function calculate_seo_stats() {
        $this->seo_stats = array(
            'total_websites' => count($this->user_websites),
            'total_issues' => 0,
            'avg_seo_score' => 0,
            'total_keywords' => 0,
            'avg_position' => 0,
            'total_traffic' => 0,
            'avg_load_time' => 0
        );

        foreach ($this->user_websites as $website) {
            $this->seo_stats['total_issues'] += count($website['issues']);
            $this->seo_stats['avg_seo_score'] += $website['seo_score'];
            $this->seo_stats['total_keywords'] += count($website['keywords']);
            
            foreach ($website['keywords'] as $keyword) {
                $this->seo_stats['avg_position'] += $keyword['position'];
            }

            $this->seo_stats['total_traffic'] += array_sum($website['traffic']);
            $this->seo_stats['avg_load_time'] += $website['performance']['load_time'];
        }

        if ($this->seo_stats['total_websites'] > 0) {
            $this->seo_stats['avg_seo_score'] /= $this->seo_stats['total_websites'];
            $this->seo_stats['avg_position'] /= $this->seo_stats['total_keywords'];
            $this->seo_stats['avg_load_time'] /= $this->seo_stats['total_websites'];
        }
    }

    private function load_automation_settings() {
        $this->automation_settings = array(
            'auto_optimize_content' => true,
            'auto_fix_issues' => true,
            'auto_update_meta' => true,
            'auto_monitor_keywords' => true,
            'auto_analyze_competitors' => true,
            'auto_generate_reports' => true
        );
    }

    public function analyze_website() {
        check_ajax_referer('carrey_seo_nonce', 'nonce');
        
        $website_url = sanitize_text_field($_POST['url']);
        $analysis_results = $this->perform_deep_analysis($website_url);
        
        wp_send_json_success($analysis_results);
    }

    private function perform_deep_analysis($url) {
        // Simulert dybdeanalyse
        return array(
            'technical_seo' => array(
                'crawlability' => 90,
                'indexability' => 85,
                'mobile_friendliness' => 95
            ),
            'content_analysis' => array(
                'keyword_density' => 2.5,
                'content_length' => 1200,
                'readability_score' => 80
            ),
            'backlink_profile' => array(
                'total_links' => 150,
                'domain_authority' => 45,
                'spam_score' => 2
            ),
            'performance_metrics' => array(
                'load_time' => 2.3,
                'ttfb' => 0.7,
                'page_size' => 1.5
            )
        );
    }

    public function automate_seo() {
        check_ajax_referer('carrey_seo_nonce', 'nonce');
        
        $action = sanitize_text_field($_POST['action']);
        $result = $this->perform_automation($action);
        
        wp_send_json_success($result);
    }

    private function perform_automation($action) {
        // Simulert automatisering
        switch ($action) {
            case 'optimize_content':
                return array('status' => 'success', 'message' => 'Content optimized successfully');
            case 'fix_issues':
                return array('status' => 'success', 'message' => 'Issues fixed successfully');
            case 'update_meta':
                return array('status' => 'success', 'message' => 'Meta tags updated successfully');
            default:
                return array('status' => 'error', 'message' => 'Invalid action');
        }
    }

    public function render_dashboard_page() {
        require_once dirname(__FILE__) . '/templates/dashboard.php';
    }

    public function render_analysis_page() {
        require_once dirname(__FILE__) . '/templates/analysis.php';
    }

    public function render_automation_page() {
        require_once dirname(__FILE__) . '/templates/automation.php';
    }

    public function render_reports_page() {
        require_once dirname(__FILE__) . '/templates/reports.php';
    }

    public function render_register_page() {
        include CARREY_SEO_PATH . 'admin/templates/register.php';
    }

    public function get_user_data() {
        if (empty($this->user_websites)) {
            $this->load_user_data();
        }
        return $this->user_websites;
    }

    public function get_average_seo_score() {
        if (empty($this->seo_stats)) {
            $this->calculate_seo_stats();
        }
        return $this->seo_stats['avg_seo_score'] ?? 0;
    }

    public function get_total_keywords() {
        if (empty($this->seo_stats)) {
            $this->calculate_seo_stats();
        }
        return $this->seo_stats['total_keywords'] ?? 0;
    }

    public function get_average_position() {
        if (empty($this->seo_stats)) {
            $this->calculate_seo_stats();
        }
        return $this->seo_stats['avg_position'] ?? 0;
    }

    public function get_identified_issues() {
        $issues = array();
        foreach ($this->user_websites as $website) {
            foreach ($website['issues'] as $issue) {
                $issues[] = array(
                    'title' => $issue,
                    'description' => 'Issue found on ' . $website['name'],
                    'severity' => $this->determine_issue_severity($issue)
                );
            }
        }
        return $issues;
    }

    public function get_automated_recommendations() {
        return array(
            array(
                'title' => 'Optimize Content',
                'description' => 'Update content based on keyword analysis',
                'action' => 'optimize_content'
            ),
            array(
                'title' => 'Fix Technical Issues',
                'description' => 'Address identified technical SEO problems',
                'action' => 'fix_technical'
            )
        );
    }

    private function determine_issue_severity($issue) {
        $high_severity = array('Slow Page Load', 'Missing Meta Description');
        $medium_severity = array('Missing Alt Tags');
        
        if (in_array($issue, $high_severity)) {
            return 'high';
        } elseif (in_array($issue, $medium_severity)) {
            return 'medium';
        } else {
            return 'low';
        }
    }
} 