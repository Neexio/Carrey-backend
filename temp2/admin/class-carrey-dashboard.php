<?php
/**
 * Carrey SEO Dashboard Handler
 *
 * @package Carrey_SEO_Dashboard
 */

if (!defined('ABSPATH')) {
    exit;
}

class Carrey_Dashboard {
    private static $instance = null;

    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        add_action('admin_menu', array($this, 'add_dashboard_menu'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_dashboard_scripts'));
        add_action('admin_init', array($this, 'register_settings'));
    }

    public function add_dashboard_menu() {
        add_menu_page(
            'Carrey SEO Dashboard',
            'SEO Dashboard',
            'manage_options',
            'carrey-seo-dashboard',
            array($this, 'render_dashboard_page'),
            'dashicons-chart-line',
            31
        );

        add_submenu_page(
            'carrey-seo-dashboard',
            'Abonnement',
            'Abonnement',
            'manage_options',
            'carrey-subscription',
            array($this, 'render_subscription_page')
        );

        add_submenu_page(
            'carrey-seo-dashboard',
            'Optimaliseringsverktøy',
            'Optimaliseringsverktøy',
            'manage_options',
            'carrey-optimization-tools',
            array($this, 'render_optimization_tools_page')
        );

        add_submenu_page(
            'carrey-seo-dashboard',
            'Rapporter',
            'Rapporter',
            'manage_options',
            'carrey-reports',
            array($this, 'render_reports_page')
        );

        add_submenu_page(
            'carrey-seo-dashboard',
            'Innstillinger',
            'Innstillinger',
            'manage_options',
            'carrey-settings',
            array($this, 'render_settings_page')
        );
    }

    public function enqueue_dashboard_scripts($hook) {
        if (strpos($hook, 'carrey-seo-dashboard') === false) {
            return;
        }

        wp_enqueue_script('chart-js', 'https://cdn.jsdelivr.net/npm/chart.js', array(), '3.7.0', true);
        wp_enqueue_style('carrey-dashboard-style', CARREY_SEO_PLUGIN_URL . 'admin/css/dashboard.css', array(), CARREY_SEO_VERSION);
        wp_enqueue_script('carrey-dashboard-script', CARREY_SEO_PLUGIN_URL . 'admin/js/dashboard.js', array('jquery', 'chart-js'), CARREY_SEO_VERSION, true);
    }

    public function register_settings() {
        register_setting('carrey_seo_settings', 'carrey_seo_settings', array(
            'default' => array(
                'api_key' => '',
                'auto_scan' => true,
                'scan_frequency' => 'daily',
                'email_notifications' => true,
                'notification_frequency' => 'weekly',
                'debug_mode' => false
            )
        ));
    }

    public function render_dashboard_page() {
        if (!current_user_can('manage_options')) {
            wp_die(__('Du har ikke tilgang til denne siden.'));
        }

        $template_path = CARREY_SEO_PLUGIN_DIR . 'admin/templates/dashboard.php';
        if (!file_exists($template_path)) {
            wp_die(__('Dashboard-malen mangler. Vennligst kontakt systemadministrator.'));
        }

        $current_user = wp_get_current_user();
        $user_websites = get_user_meta($current_user->ID, 'carrey_websites', true);
        $user_websites = is_array($user_websites) ? $user_websites : array();
        
        $seo_stats = $this->calculate_seo_stats($user_websites);
        
        include $template_path;
    }

    public function render_subscription_page() {
        if (!current_user_can('manage_options')) {
            wp_die(__('Du har ikke tilgang til denne siden.'));
        }

        $template_path = CARREY_SEO_PLUGIN_DIR . 'admin/templates/subscription.php';
        if (!file_exists($template_path)) {
            wp_die(__('Abonnementsmalen mangler. Vennligst kontakt systemadministrator.'));
        }

        include $template_path;
    }

    public function render_optimization_tools_page() {
        if (!current_user_can('manage_options')) {
            wp_die(__('Du har ikke tilgang til denne siden.'));
        }

        $template_path = CARREY_SEO_PLUGIN_DIR . 'admin/templates/optimization-tools.php';
        if (!file_exists($template_path)) {
            wp_die(__('Optimaliseringsverktøy-malen mangler. Vennligst kontakt systemadministrator.'));
        }

        include $template_path;
    }

    public function render_reports_page() {
        if (!current_user_can('manage_options')) {
            wp_die(__('Du har ikke tilgang til denne siden.'));
        }

        $template_path = CARREY_SEO_PLUGIN_DIR . 'admin/templates/reports.php';
        if (!file_exists($template_path)) {
            wp_die(__('Rapportmalen mangler. Vennligst kontakt systemadministrator.'));
        }

        include $template_path;
    }

    public function render_settings_page() {
        if (!current_user_can('manage_options')) {
            wp_die(__('Du har ikke tilgang til denne siden.'));
        }

        $template_path = CARREY_SEO_PLUGIN_DIR . 'admin/templates/settings.php';
        if (!file_exists($template_path)) {
            wp_die(__('Innstillingsmalen mangler. Vennligst kontakt systemadministrator.'));
        }

        include $template_path;
    }

    private function calculate_seo_stats($websites) {
        $stats = array(
            'total_pages' => count($websites),
            'optimized_pages' => 0,
            'issues_found' => 0,
            'improvement_score' => 0
        );
        
        foreach ($websites as $website) {
            if (isset($website['seo_score']) && $website['seo_score'] > 80) {
                $stats['optimized_pages']++;
            }
            if (isset($website['issues'])) {
                $stats['issues_found'] += count($website['issues']);
            }
            if (isset($website['improvement_score'])) {
                $stats['improvement_score'] += $website['improvement_score'];
            }
        }
        
        if ($stats['total_pages'] > 0) {
            $stats['improvement_score'] = round($stats['improvement_score'] / $stats['total_pages']);
        }

        return $stats;
    }
} 