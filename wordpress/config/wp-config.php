<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Enable WordPress debugging
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', true);

// Auto-recovery settings
define('WP_AUTO_RECOVERY', true);
define('WP_RECOVERY_MODE', true);

// Site URL configuration
define('WP_HOME', 'http://localhost:8080');
define('WP_SITEURL', 'http://localhost:8080');

// Force SSL for admin
define('FORCE_SSL_ADMIN', true);
define('FORCE_SSL_LOGIN', true);

// Database settings
define('DB_NAME', 'wordpress');
define('DB_USER', 'root');
define('DB_PASSWORD', '');
define('DB_HOST', 'localhost');
define('DB_CHARSET', 'utf8');
define('DB_COLLATE', '');

// Database connection test
try {
    $wpdb = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    if ($wpdb->connect_error) {
        error_log('Database connection error: ' . $wpdb->connect_error);
        // Attempt to restore from backup if available
        if (file_exists(ABSPATH . 'wp-content/backup-database.sql')) {
            exec('mysql -h ' . DB_HOST . ' -u ' . DB_USER . ' -p' . DB_PASSWORD . ' ' . DB_NAME . ' < ' . ABSPATH . 'wp-content/backup-database.sql');
        }
    }
} catch (Exception $e) {
    error_log('Database connection exception: ' . $e->getMessage());
}

// Plugin auto-recovery
function carrey_auto_recovery() {
    // Check if admin is accessible
    if (!is_admin() && !defined('DOING_CRON')) {
        $admin_url = admin_url();
        $response = wp_remote_get($admin_url);
        
        if (is_wp_error($response) || wp_remote_retrieve_response_code($response) !== 200) {
            // Deactivate problematic plugins
            $active_plugins = get_option('active_plugins');
            if (!empty($active_plugins)) {
                foreach ($active_plugins as $plugin) {
                    if (strpos($plugin, 'carrey') !== false) {
                        deactivate_plugins($plugin);
                        error_log('Auto-recovery: Deactivated plugin ' . $plugin);
                    }
                }
            }
            
            // Clear cache
            wp_cache_flush();
            
            // Attempt to restore from backup
            if (file_exists(ABSPATH . 'wp-content/backup-plugins.zip')) {
                $zip = new ZipArchive;
                if ($zip->open(ABSPATH . 'wp-content/backup-plugins.zip') === TRUE) {
                    $zip->extractTo(WP_PLUGIN_DIR);
                    $zip->close();
                    error_log('Auto-recovery: Restored plugins from backup');
                }
            }
        }
    }
}
add_action('init', 'carrey_auto_recovery');

// Auto-backup system
function carrey_auto_backup() {
    if (!defined('DOING_CRON')) return;
    
    // Backup database
    $backup_file = ABSPATH . 'wp-content/backup-database.sql';
    exec('mysqldump -h ' . DB_HOST . ' -u ' . DB_USER . ' -p' . DB_PASSWORD . ' ' . DB_NAME . ' > ' . $backup_file);
    
    // Backup plugins
    $plugins_dir = WP_PLUGIN_DIR;
    $backup_zip = ABSPATH . 'wp-content/backup-plugins.zip';
    $zip = new ZipArchive;
    if ($zip->open($backup_zip, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($plugins_dir),
            RecursiveIteratorIterator::LEAVES_ONLY
        );
        
        foreach ($files as $file) {
            if (!$file->isDir()) {
                $filePath = $file->getRealPath();
                $relativePath = substr($filePath, strlen($plugins_dir) + 1);
                $zip->addFile($filePath, $relativePath);
            }
        }
        $zip->close();
    }
}
add_action('wp_scheduled_delete', 'carrey_auto_backup');

// ... existing code ... 