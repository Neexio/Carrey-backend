<?php
/**
 * Plugin Name: Carrey User Panel
 * Plugin URI: https://carrey.ai
 * Description: Premium user panel for Carrey.ai customers
 * Version: 1.0.0
 * Author: Carrey
 * Author URI: https://carrey.ai
 * Text Domain: carrey-user-panel
 * License: GPL v2 or later
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('CARREY_USER_PANEL_VERSION', '1.0.0');
define('CARREY_USER_PANEL_PATH', plugin_dir_path(__FILE__));
define('CARREY_USER_PANEL_URL', plugin_dir_url(__FILE__));

// Include required files
require_once CARREY_USER_PANEL_PATH . 'includes/class-carrey-user-panel.php';
require_once CARREY_USER_PANEL_PATH . 'includes/class-carrey-user-panel-admin.php';

// Initialize the plugin
function carrey_user_panel_init() {
    $plugin = new Carrey_User_Panel();
    $plugin->init();
}
add_action('plugins_loaded', 'carrey_user_panel_init');

// Activation hook
register_activation_hook(__FILE__, 'carrey_user_panel_activate');
function carrey_user_panel_activate() {
    // Create necessary database tables
    global $wpdb;
    
    $charset_collate = $wpdb->get_charset_collate();
    
    // Create user settings table
    $table_name = $wpdb->prefix . 'carrey_user_settings';
    $sql = "CREATE TABLE IF NOT EXISTS $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        user_id bigint(20) NOT NULL,
        settings longtext NOT NULL,
        created_at datetime DEFAULT CURRENT_TIMESTAMP,
        updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY  (id)
    ) $charset_collate;";
    
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}

// Deactivation hook
register_deactivation_hook(__FILE__, 'carrey_user_panel_deactivate');
function carrey_user_panel_deactivate() {
    // Cleanup if necessary
} 