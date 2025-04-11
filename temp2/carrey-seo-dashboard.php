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
    'admin/templates/dashboard.php'
);

$missing_files = array();
foreach ($required_files as $file) {
    $file_path = CARREY_SEO_PLUGIN_DIR . $file;
    if (!file_exists($file_path)) {
        $missing_files[] = $file;
    }
}

// Vis feilmelding hvis noen filer mangler
if (!empty($missing_files)) {
    add_action('admin_notices', function() use ($missing_files) {
        echo '<div class="error"><p>Carrey SEO Dashboard: Følgende filer mangler: ' . esc_html(implode(', ', $missing_files)) . '</p></div>';
    });
    return;
}

// Inkluder dashboard-klassen
require_once CARREY_SEO_PLUGIN_DIR . 'admin/class-carrey-dashboard.php';

// Initialiser plugin
function carrey_seo_dashboard_init() {
    if (class_exists('Carrey_Dashboard')) {
        Carrey_Dashboard::get_instance();
    }
}
add_action('plugins_loaded', 'carrey_seo_dashboard_init');

// Aktiveringshook
register_activation_hook(__FILE__, 'carrey_seo_dashboard_activate');
function carrey_seo_dashboard_activate() {
    // Sjekk om WordPress-versjonen er kompatibel
    if (version_compare(get_bloginfo('version'), '5.0', '<')) {
        wp_die('Carrey SEO Dashboard krever WordPress 5.0 eller nyere.');
    }
    
    // Opprett nødvendige databasetabeller eller innstillinger her
    add_option('carrey_seo_dashboard_version', CARREY_SEO_VERSION);
}

// Deaktiveringshook
register_deactivation_hook(__FILE__, 'carrey_seo_dashboard_deactivate');
function carrey_seo_dashboard_deactivate() {
    // Rydd opp ved deaktivering
    delete_option('carrey_seo_dashboard_version');
} 