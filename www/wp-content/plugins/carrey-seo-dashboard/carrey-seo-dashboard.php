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
    'admin/class-carrey-dashboard.php',
    'admin/templates/dashboard.php',
    'includes/class-carrey-payment.php',
    'includes/class-carrey-user.php',
    'includes/class-carrey-seo.php',
    'includes/class-carrey-license-handler.php'
);

$missing_files = array();
foreach ($required_files as $file) {
    $file_path = CARREY_SEO_PLUGIN_DIR . $file;
    if (!file_exists($file_path)) {
        $missing_files[] = $file;
    }
}

// Show error message if any files are missing
if (!empty($missing_files)) {
    add_action('admin_notices', function() use ($missing_files) {
        echo '<div class="error"><p>Carrey SEO Dashboard: Following files are missing: ' . esc_html(implode(', ', $missing_files)) . '</p></div>';
    });
    return;
}

// Include required classes
require_once CARREY_SEO_PLUGIN_DIR . 'admin/class-carrey-dashboard.php';
require_once CARREY_SEO_PLUGIN_DIR . 'includes/class-carrey-payment.php';
require_once CARREY_SEO_PLUGIN_DIR . 'includes/class-carrey-user.php';
require_once CARREY_SEO_PLUGIN_DIR . 'includes/class-carrey-seo.php';
require_once CARREY_SEO_PLUGIN_DIR . 'includes/class-carrey-license-handler.php';

// Include Stripe library
if (file_exists(CARREY_SEO_PLUGIN_DIR . 'vendor/autoload.php')) {
    require_once CARREY_SEO_PLUGIN_DIR . 'vendor/autoload.php';
} else {
    error_log('Carrey SEO Dashboard: Stripe autoloader not found');
    add_action('admin_notices', function() {
        echo '<div class="error"><p>Carrey SEO Dashboard: Stripe library not found. Please reinstall the plugin.</p></div>';
    });
    return;
}

// Initialize plugin
function carrey_seo_dashboard_init() {
    if (class_exists('Carrey_Dashboard')) {
        Carrey_Dashboard::get_instance();
    }
    if (class_exists('Carrey_Payment')) {
        Carrey_Payment::get_instance();
    }
    if (class_exists('Carrey_User')) {
        Carrey_User::get_instance();
    }
    if (class_exists('Carrey_SEO')) {
        Carrey_SEO::get_instance();
    }
    if (class_exists('Carrey_License_Handler')) {
        $license_handler = Carrey_License_Handler::get_instance();
        $license_handler->init();
    }
}
add_action('plugins_loaded', 'carrey_seo_dashboard_init');

// Activation hook
register_activation_hook(__FILE__, 'carrey_seo_dashboard_activate');
function carrey_seo_dashboard_activate() {
    // Check if WordPress version is compatible
    if (version_compare(get_bloginfo('version'), '5.0', '<')) {
        wp_die('Carrey SEO Dashboard requires WordPress 5.0 or newer.');
    }
    
    // Create necessary database tables or settings here
    add_option('carrey_seo_dashboard_version', CARREY_SEO_VERSION);
    
    // Initialize default settings
    add_option('carrey_seo_dashboard_settings', array(
        'api_key' => '',
        'stripe_public_key' => '',
        'stripe_secret_key' => '',
        'subscription_plans' => array(
            'basic' => array(
                'name' => 'Basic',
                'price' => 299,
                'features' => array('Basic SEO Analysis', 'Keyword Tracking')
            ),
            'pro' => array(
                'name' => 'Professional',
                'price' => 599,
                'features' => array('Advanced SEO Analysis', 'Keyword Tracking', 'Competitor Analysis')
            ),
            'enterprise' => array(
                'name' => 'Enterprise',
                'price' => 999,
                'features' => array('All Features', 'Priority Support', 'Custom Solutions')
            )
        )
    ));

    // Create Stripe products and prices
    $payment = Carrey_Payment::get_instance();
    $payment->create_stripe_products();
}

// Deactivation hook
register_deactivation_hook(__FILE__, 'carrey_seo_dashboard_deactivate');
function carrey_seo_dashboard_deactivate() {
    // Clean up on deactivation
    delete_option('carrey_seo_dashboard_version');
    delete_option('carrey_seo_dashboard_settings');
} 