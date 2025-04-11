<?php
/**
 * Auto Recovery Handler Class
 *
 * @package Carrey_SEO_Dashboard
 */

if (!defined('ABSPATH')) {
    exit;
}

class Carrey_Recovery {
    private static $instance = null;
    private $backup_dir;
    private $log_file;
    private $security_file;
    private $last_check_time;
    private $check_interval = 300; // 5 minutes

    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        $this->backup_dir = WP_CONTENT_DIR . '/carrey-backups';
        $this->log_file = WP_CONTENT_DIR . '/carrey-recovery.log';
        $this->security_file = WP_CONTENT_DIR . '/carrey-security.log';
        $this->last_check_time = get_option('carrey_last_check_time', 0);
        $this->init_hooks();
        $this->ensure_backup_dir();
    }

    private function init_hooks() {
        add_action('init', array($this, 'check_system_health'));
        add_action('wp_scheduled_delete', array($this, 'create_backup'));
        add_action('admin_init', array($this, 'monitor_admin_access'));
        add_filter('wp_redirect', array($this, 'handle_redirects'));
        add_action('wp_login_failed', array($this, 'handle_login_failure'));
        add_action('wp_login', array($this, 'handle_successful_login'));
        add_filter('authenticate', array($this, 'check_login_attempts'), 30, 3);
        add_action('init', array($this, 'check_ddos_protection'));
        add_action('wp_loaded', array($this, 'check_rate_limiting'));
        add_action('init', array($this, 'init_security_headers'));
        add_filter('query', array($this, 'sanitize_sql_query'));
        add_action('template_redirect', array($this, 'check_xss_protection'));
        add_action('wp_loaded', array($this, 'scan_for_malware'));
        add_action('init', array($this, 'check_file_integrity'));
        add_action('wp_loaded', array($this, 'monitor_file_changes'));
        add_action('init', array($this, 'check_suspicious_activity'));
        add_action('init', array($this, 'verify_database_connection'));
    }

    private function ensure_backup_dir() {
        if (!file_exists($this->backup_dir)) {
            wp_mkdir_p($this->backup_dir);
            // Set secure permissions
            chmod($this->backup_dir, 0700);
        }
    }

    public function check_system_health() {
        $current_time = time();
        if ($current_time - $this->last_check_time < $this->check_interval) {
            return;
        }

        // Update last check time
        update_option('carrey_last_check_time', $current_time);

        // Check database connection
        global $wpdb;
        if ($wpdb->last_error) {
            $this->log_error('Database error: ' . $wpdb->last_error);
            $this->attempt_database_recovery();
        }

        // Check plugin integrity
        $this->verify_plugin_integrity();

        // Check file permissions
        $this->check_file_permissions();

        // Check for suspicious files
        $this->scan_for_malware();

        // Check system resources
        $this->check_system_resources();

        // Verify WordPress core files
        $this->verify_wp_core();
    }

    public function monitor_admin_access() {
        if (is_admin() && !current_user_can('manage_options')) {
            $this->log_security_event('Unauthorized admin access attempt from IP: ' . $_SERVER['REMOTE_ADDR']);
            wp_redirect(home_url());
            exit;
        }
    }

    public function handle_redirects($location) {
        if (strpos($location, 'wp-admin') !== false) {
            $this->log_event('Admin redirect to: ' . $location);
            // Add security headers
            header('X-Frame-Options: SAMEORIGIN');
            header('X-XSS-Protection: 1; mode=block');
            header('X-Content-Type-Options: nosniff');
        }
        return $location;
    }

    public function handle_login_failure($username) {
        $this->log_security_event('Failed login attempt for username: ' . $username);
        $this->check_brute_force($username);
    }

    public function handle_successful_login($username) {
        $this->log_security_event('Successful login for username: ' . $username);
        $this->clear_login_attempts($username);
    }

    public function check_login_attempts($user, $username, $password) {
        if (is_wp_error($user)) {
            $attempts = get_option('carrey_login_attempts_' . $username, 0);
            if ($attempts >= 5) {
                $this->log_security_event('Too many login attempts for username: ' . $username);
                return new WP_Error('too_many_attempts', 'Too many login attempts. Please try again later.');
            }
            update_option('carrey_login_attempts_' . $username, $attempts + 1);
        }
        return $user;
    }

    private function check_brute_force($username) {
        $attempts = get_option('carrey_login_attempts_' . $username, 0);
        if ($attempts >= 5) {
            // Block IP for 30 minutes
            $blocked_ips = get_option('carrey_blocked_ips', array());
            $blocked_ips[$_SERVER['REMOTE_ADDR']] = time() + 1800;
            update_option('carrey_blocked_ips', $blocked_ips);
            $this->log_security_event('IP blocked due to brute force: ' . $_SERVER['REMOTE_ADDR']);
        }
    }

    private function clear_login_attempts($username) {
        delete_option('carrey_login_attempts_' . $username);
    }

    public function scan_for_malware() {
        $suspicious_patterns = array(
            'eval(',
            'base64_decode(',
            'shell_exec(',
            'system(',
            'exec(',
            'passthru('
        );

        $plugins_dir = WP_PLUGIN_DIR;
        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($plugins_dir),
            RecursiveIteratorIterator::LEAVES_ONLY
        );

        foreach ($files as $file) {
            if ($file->isFile() && in_array($file->getExtension(), array('php', 'js'))) {
                $content = file_get_contents($file->getRealPath());
                foreach ($suspicious_patterns as $pattern) {
                    if (strpos($content, $pattern) !== false) {
                        $this->log_security_event('Suspicious code found in: ' . $file->getRealPath());
                        // Quarantine the file
                        $this->quarantine_file($file->getRealPath());
                    }
                }
            }
        }
    }

    private function quarantine_file($file_path) {
        $quarantine_dir = $this->backup_dir . '/quarantine';
        if (!file_exists($quarantine_dir)) {
            wp_mkdir_p($quarantine_dir);
        }
        $new_path = $quarantine_dir . '/' . basename($file_path) . '.' . time();
        rename($file_path, $new_path);
        $this->log_security_event('File quarantined: ' . $file_path);
    }

    private function check_system_resources() {
        // Check memory usage
        $memory_usage = memory_get_usage(true);
        $memory_limit = ini_get('memory_limit');
        if ($memory_usage > 0.8 * $this->convert_to_bytes($memory_limit)) {
            $this->log_error('High memory usage detected: ' . $this->format_bytes($memory_usage));
        }

        // Check disk space
        $free_space = disk_free_space(ABSPATH);
        $total_space = disk_total_space(ABSPATH);
        if ($free_space < 0.1 * $total_space) {
            $this->log_error('Low disk space detected: ' . $this->format_bytes($free_space) . ' remaining');
        }
    }

    private function verify_wp_core() {
        $core_checksum = get_core_checksums();
        if (is_wp_error($core_checksum)) {
            $this->log_error('Failed to verify WordPress core files');
            return;
        }

        foreach ($core_checksum as $file => $checksum) {
            $file_path = ABSPATH . $file;
            if (file_exists($file_path)) {
                $file_checksum = md5_file($file_path);
                if ($file_checksum !== $checksum) {
                    $this->log_error('Core file modified: ' . $file);
                    $this->restore_core_file($file);
                }
            }
        }
    }

    private function restore_core_file($file) {
        $file_path = ABSPATH . $file;
        $backup_path = $this->backup_dir . '/core/' . $file;
        if (file_exists($backup_path)) {
            copy($backup_path, $file_path);
            $this->log_event('Restored core file: ' . $file);
        }
    }

    private function convert_to_bytes($value) {
        $unit = strtolower(substr($value, -1));
        $number = (int) substr($value, 0, -1);
        switch ($unit) {
            case 'g':
                $number *= 1024;
            case 'm':
                $number *= 1024;
            case 'k':
                $number *= 1024;
        }
        return $number;
    }

    private function format_bytes($bytes) {
        $units = array('B', 'KB', 'MB', 'GB', 'TB');
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);
        return round($bytes, 2) . ' ' . $units[$pow];
    }

    private function log_security_event($message, $level = 'info') {
        $timestamp = date('Y-m-d H:i:s');
        $ip = $_SERVER['REMOTE_ADDR'];
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        $request_uri = $_SERVER['REQUEST_URI'];
        
        $log_entry = sprintf(
            "[%s] [%s] [IP: %s] [UA: %s] [URI: %s] %s\n",
            $timestamp,
            strtoupper($level),
            $ip,
            $user_agent,
            $request_uri,
            $message
        );
        
        file_put_contents($this->security_file, $log_entry, FILE_APPEND);
        
        // Send alert for critical events
        if ($level === 'critical') {
            $this->send_security_alert($message);
        }
    }

    private function send_security_alert($message) {
        $admin_email = get_option('admin_email');
        $site_name = get_bloginfo('name');
        
        $subject = sprintf('[%s] Security Alert: %s', $site_name, substr($message, 0, 50));
        $body = sprintf(
            "A security event has been detected on %s:\n\n%s\n\nIP: %s\nTime: %s",
            $site_name,
            $message,
            $_SERVER['REMOTE_ADDR'],
            date('Y-m-d H:i:s')
        );
        
        wp_mail($admin_email, $subject, $body);
    }

    private function monitor_file_changes() {
        $watched_files = array(
            ABSPATH . 'wp-config.php',
            WP_CONTENT_DIR . '/plugins',
            WP_CONTENT_DIR . '/themes',
            WP_CONTENT_DIR . '/uploads'
        );
        
        foreach ($watched_files as $file) {
            if (file_exists($file)) {
                $current_hash = md5_file($file);
                $stored_hash = get_option('carrey_file_hash_' . md5($file));
                
                if ($stored_hash && $current_hash !== $stored_hash) {
                    $this->log_security_event(
                        sprintf('File modification detected: %s', $file),
                        'critical'
                    );
                }
                
                update_option('carrey_file_hash_' . md5($file), $current_hash);
            }
        }
    }

    private function check_suspicious_activity() {
        // Check for multiple failed login attempts
        $failed_logins = get_option('carrey_failed_logins', array());
        $ip = $_SERVER['REMOTE_ADDR'];
        
        if (isset($failed_logins[$ip]) && $failed_logins[$ip] > 5) {
            $this->log_security_event(
                sprintf('Multiple failed login attempts from IP: %s', $ip),
                'critical'
            );
            $this->block_ip($ip);
        }
        
        // Check for suspicious user agents
        $suspicious_agents = array(
            'curl',
            'wget',
            'python',
            'perl',
            'java'
        );
        
        $user_agent = strtolower($_SERVER['HTTP_USER_AGENT']);
        foreach ($suspicious_agents as $agent) {
            if (strpos($user_agent, $agent) !== false) {
                $this->log_security_event(
                    sprintf('Suspicious user agent detected: %s', $user_agent),
                    'warning'
                );
            }
        }
    }

    private function block_ip($ip) {
        $blocked_ips = get_option('carrey_blocked_ips', array());
        $blocked_ips[$ip] = time() + 3600; // Block for 1 hour
        update_option('carrey_blocked_ips', $blocked_ips);
        
        // Add to .htaccess if possible
        $htaccess_path = ABSPATH . '.htaccess';
        if (file_exists($htaccess_path) && is_writable($htaccess_path)) {
            $htaccess_content = file_get_contents($htaccess_path);
            $deny_rule = "\nDeny from " . $ip . "\n";
            if (strpos($htaccess_content, $deny_rule) === false) {
                file_put_contents($htaccess_path, $deny_rule, FILE_APPEND);
            }
        }
    }

    public function create_backup() {
        try {
            // Backup database
            $db_backup = $this->backup_dir . '/database_' . date('Y-m-d_H-i-s') . '.sql';
            exec('mysqldump -h ' . DB_HOST . ' -u ' . DB_USER . ' -p' . DB_PASSWORD . ' ' . DB_NAME . ' > ' . $db_backup);

            // Backup plugins
            $plugins_backup = $this->backup_dir . '/plugins_' . date('Y-m-d_H-i-s') . '.zip';
            $this->create_zip_backup(WP_PLUGIN_DIR, $plugins_backup);

            // Clean up old backups (keep last 5)
            $this->cleanup_old_backups();

            $this->log_event('Backup created successfully');
        } catch (Exception $e) {
            $this->log_error('Backup failed: ' . $e->getMessage());
        }
    }

    private function attempt_database_recovery() {
        global $wpdb;
        
        try {
            // Prøv å gjenopprette tilkoblingen
            $wpdb->db_connect();
            
            // Verifiser tilkoblingen
            if ($wpdb->check_connection()) {
                $this->log_event("Database-tilkobling gjenopprettet");
                return true;
            }
            
            // Hvis fortsatt ikke tilkoblet, prøv å reparere
            $this->log_error("Prøver å reparere database-tilkobling");
            
            // Sjekk om vi kan koble til med rå påloggingsdetaljer
            $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
            
            if ($mysqli->connect_error) {
                throw new Exception("Kunne ikke koble til database: " . $mysqli->connect_error);
            }
            
            // Test om vi kan kjøre spørringer
            if ($mysqli->query("SELECT 1")) {
                $this->log_event("Database-tilkobling verifisert med rå påloggingsdetaljer");
                
                // Oppdater WordPress database-tilkobling
                $wpdb->db_connect();
                
                if ($wpdb->check_connection()) {
                    $this->log_event("WordPress database-tilkobling gjenopprettet");
                    return true;
                }
            }
            
            $mysqli->close();
            
            // Hvis alt annet feiler, prøv å opprette database på nytt
            $this->recreate_database();
            
        } catch (Exception $e) {
            $this->log_error("Database-gjenoppretting feilet: " . $e->getMessage());
            return false;
        }
        
        return false;
    }

    private function recreate_database() {
        global $wpdb;
        
        try {
            // Opprett database hvis den ikke eksisterer
            $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD);
            
            if ($mysqli->connect_error) {
                throw new Exception("Kunne ikke koble til MySQL: " . $mysqli->connect_error);
            }
            
            // Opprett database
            $sql = "CREATE DATABASE IF NOT EXISTS " . DB_NAME . " CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";
            if (!$mysqli->query($sql)) {
                throw new Exception("Kunne ikke opprette database: " . $mysqli->error);
            }
            
            // Velg database
            $mysqli->select_db(DB_NAME);
            
            // Opprett tabeller på nytt
            $this->create_missing_tables();
            
            // Oppdater WordPress database-tilkobling
            $wpdb->db_connect();
            
            if ($wpdb->check_connection()) {
                $this->log_event("Database gjenopprettet og tabeller opprettet på nytt");
                return true;
            }
            
            $mysqli->close();
            
        } catch (Exception $e) {
            $this->log_error("Database-gjenoppretting feilet: " . $e->getMessage());
            return false;
        }
        
        return false;
    }

    private function verify_database_integrity() {
        global $wpdb;
        
        try {
            // Sjekk tabellstruktur
            $tables = array(
                $wpdb->prefix . 'carrey_websites',
                $wpdb->prefix . 'carrey_keywords',
                $wpdb->prefix . 'carrey_issues'
            );
            
            foreach ($tables as $table) {
                // Sjekk om tabellen eksisterer
                if ($wpdb->get_var("SHOW TABLES LIKE '$table'") != $table) {
                    $this->log_error("Tabell mangler: $table");
                    $this->create_missing_tables();
                    continue;
                }
                
                // Sjekk tabellstruktur
                $columns = $wpdb->get_results("SHOW COLUMNS FROM $table");
                if (empty($columns)) {
                    $this->log_error("Tabellstruktur er korrupt: $table");
                    $this->repair_table($table);
                }
            }
            
            // Sjekk indekser
            $this->verify_indexes();
            
            // Sjekk data-integritet
            $this->verify_data_integrity();
            
        } catch (Exception $e) {
            $this->log_error("Database-integritetssjekk feilet: " . $e->getMessage());
            return false;
        }
        
        return true;
    }

    private function repair_table($table) {
        global $wpdb;
        
        try {
            // Reparer tabell
            $wpdb->query("REPAIR TABLE $table");
            
            // Optimaliser tabell
            $wpdb->query("OPTIMIZE TABLE $table");
            
            // Sjekk tabellstatus
            $status = $wpdb->get_row("CHECK TABLE $table");
            
            if ($status->Msg_type == 'Error') {
                throw new Exception("Kunne ikke reparere tabell: " . $status->Msg_text);
            }
            
            $this->log_event("Tabell reparert: $table");
            
        } catch (Exception $e) {
            $this->log_error("Tabellreparasjon feilet: " . $e->getMessage());
            return false;
        }
        
        return true;
    }

    private function verify_indexes() {
        global $wpdb;
        
        $tables = array(
            $wpdb->prefix . 'carrey_websites' => array('user_id', 'url'),
            $wpdb->prefix . 'carrey_keywords' => array('website_id', 'keyword'),
            $wpdb->prefix . 'carrey_issues' => array('website_id', 'type', 'severity')
        );
        
        foreach ($tables as $table => $indexes) {
            foreach ($indexes as $index) {
                $result = $wpdb->get_row("SHOW INDEX FROM $table WHERE Key_name = '$index'");
                if (!$result) {
                    $this->log_error("Manglende indeks: $index i tabell $table");
                    $wpdb->query("ALTER TABLE $table ADD INDEX $index ($index)");
                }
            }
        }
    }

    private function verify_data_integrity() {
        global $wpdb;
        
        // Sjekk referanseintegritet
        $wpdb->query("
            DELETE k FROM {$wpdb->prefix}carrey_keywords k 
            LEFT JOIN {$wpdb->prefix}carrey_websites w ON k.website_id = w.id 
            WHERE w.id IS NULL
        ");
        
        $wpdb->query("
            DELETE i FROM {$wpdb->prefix}carrey_issues i 
            LEFT JOIN {$wpdb->prefix}carrey_websites w ON i.website_id = w.id 
            WHERE w.id IS NULL
        ");
        
        // Sjekk for duplikater
        $wpdb->query("
            DELETE t1 FROM {$wpdb->prefix}carrey_keywords t1
            INNER JOIN {$wpdb->prefix}carrey_keywords t2 
            WHERE t1.id > t2.id 
            AND t1.website_id = t2.website_id 
            AND t1.keyword = t2.keyword
        ");
    }

    private function verify_plugin_integrity() {
        $plugins = get_plugins();
        foreach ($plugins as $plugin_file => $plugin_data) {
            if (strpos($plugin_file, 'carrey') !== false) {
                $plugin_path = WP_PLUGIN_DIR . '/' . $plugin_file;
                if (!file_exists($plugin_path)) {
                    $this->log_error('Missing plugin file: ' . $plugin_file);
                    $this->restore_plugin($plugin_file);
                }
            }
        }
    }

    private function restore_plugin($plugin_file) {
        $backups = glob($this->backup_dir . '/plugins_*.zip');
        if (!empty($backups)) {
            $latest_backup = end($backups);
            $zip = new ZipArchive;
            if ($zip->open($latest_backup) === TRUE) {
                $zip->extractTo(WP_PLUGIN_DIR);
                $zip->close();
                $this->log_event('Plugin restored from backup: ' . $plugin_file);
            }
        }
    }

    private function check_file_permissions() {
        $critical_files = array(
            ABSPATH . 'wp-config.php',
            WP_CONTENT_DIR . '/plugins',
            WP_CONTENT_DIR . '/themes'
        );

        foreach ($critical_files as $file) {
            if (file_exists($file)) {
                $perms = fileperms($file);
                if ($perms !== 0755) {
                    chmod($file, 0755);
                    $this->log_event('Fixed permissions for: ' . $file);
                }
            }
        }
    }

    private function create_zip_backup($source, $destination) {
        $zip = new ZipArchive;
        if ($zip->open($destination, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
            $files = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($source),
                RecursiveIteratorIterator::LEAVES_ONLY
            );

            foreach ($files as $file) {
                if (!$file->isDir()) {
                    $filePath = $file->getRealPath();
                    $relativePath = substr($filePath, strlen($source) + 1);
                    $zip->addFile($filePath, $relativePath);
                }
            }
            $zip->close();
        }
    }

    private function cleanup_old_backups() {
        $backups = glob($this->backup_dir . '/*');
        if (count($backups) > 5) {
            usort($backups, function($a, $b) {
                return filemtime($b) - filemtime($a);
            });
            $old_backups = array_slice($backups, 5);
            foreach ($old_backups as $backup) {
                unlink($backup);
            }
        }
    }

    private function log_event($message) {
        $timestamp = date('Y-m-d H:i:s');
        file_put_contents($this->log_file, "[$timestamp] INFO: $message\n", FILE_APPEND);
    }

    private function log_error($message) {
        $timestamp = date('Y-m-d H:i:s');
        file_put_contents($this->log_file, "[$timestamp] ERROR: $message\n", FILE_APPEND);
    }

    public function check_ddos_protection() {
        $ip = $_SERVER['REMOTE_ADDR'];
        $request_count = get_transient('carrey_request_count_' . $ip);
        
        if ($request_count === false) {
            set_transient('carrey_request_count_' . $ip, 1, 60);
        } else {
            $request_count++;
            set_transient('carrey_request_count_' . $ip, $request_count, 60);
            
            // Dynamisk terskel basert på tid på døgnet
            $hour = date('G');
            $threshold = ($hour >= 0 && $hour < 6) ? 50 : 100;
            
            if ($request_count > $threshold) {
                $this->log_security_event(
                    sprintf('DDoS attempt detected from IP: %s (Requests: %d)', $ip, $request_count),
                    'critical'
                );
                $this->block_ip($ip);
                wp_die('Too many requests. Please try again later.', 'Rate Limit Exceeded', array('response' => 429));
            }
        }
    }

    public function check_rate_limiting() {
        $ip = $_SERVER['REMOTE_ADDR'];
        $endpoint = $_SERVER['REQUEST_URI'];
        $rate_key = 'carrey_rate_' . md5($ip . $endpoint);
        
        $rate_data = get_transient($rate_key);
        if ($rate_data === false) {
            $rate_data = array(
                'count' => 1,
                'first_request' => time(),
                'last_request' => time()
            );
            set_transient($rate_key, $rate_data, 300);
        } else {
            $rate_data['count']++;
            $rate_data['last_request'] = time();
            
            // Beregn tidsintervall mellom forespørsler
            $time_diff = $rate_data['last_request'] - $rate_data['first_request'];
            
            // Dynamisk terskel basert på endepunkt
            $threshold = strpos($endpoint, 'wp-admin') !== false ? 30 : 50;
            
            if ($rate_data['count'] > $threshold && $time_diff < 60) {
                $this->log_security_event(
                    sprintf('Rate limiting triggered for IP: %s on endpoint: %s (Count: %d)', 
                        $ip, $endpoint, $rate_data['count']),
                    'warning'
                );
                $this->block_ip($ip);
                wp_die('Rate limit exceeded. Please try again later.', 'Rate Limit Exceeded', array('response' => 429));
            }
            
            set_transient($rate_key, $rate_data, 300);
        }
    }

    private function unblock_ip($ip) {
        $blocked_ips = get_option('carrey_blocked_ips', array());
        if (isset($blocked_ips[$ip])) {
            unset($blocked_ips[$ip]);
            update_option('carrey_blocked_ips', $blocked_ips);
            
            // Remove from .htaccess if possible
            $htaccess_path = ABSPATH . '.htaccess';
            if (file_exists($htaccess_path) && is_writable($htaccess_path)) {
                $htaccess_content = file_get_contents($htaccess_path);
                $deny_rule = "Deny from " . $ip . "\n";
                $htaccess_content = str_replace($deny_rule, '', $htaccess_content);
                file_put_contents($htaccess_path, $htaccess_content);
            }
        }
    }

    public function init_security_headers() {
        if (!headers_sent()) {
            // Grunnleggende sikkerhetsheaders
            header('X-Content-Type-Options: nosniff');
            header('X-Frame-Options: SAMEORIGIN');
            header('X-XSS-Protection: 1; mode=block');
            header('Referrer-Policy: strict-origin-when-cross-origin');
            
            // Avansert CSP med støtte for eksterne ressurser
            $csp = "default-src 'self'; " .
                   "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://*.stripe.com; " .
                   "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com; " .
                   "img-src 'self' data: https://*.stripe.com; " .
                   "font-src 'self' https://fonts.gstatic.com; " .
                   "frame-src 'self' https://*.stripe.com; " .
                   "connect-src 'self' https://*.stripe.com;";
            
            header("Content-Security-Policy: " . $csp);
            header('Strict-Transport-Security: max-age=31536000; includeSubDomains; preload');
            header('Permissions-Policy: geolocation=(), microphone=(), camera=()');
        }
    }

    public function sanitize_sql_query($query) {
        // Utvidet SQL-injection beskyttelse
        $harmful_patterns = array(
            '/\b(?:DROP|DELETE|TRUNCATE|ALTER|CREATE|INSERT|UPDATE)\b/i',
            '/\b(?:UNION|SELECT|FROM|WHERE)\b.*?\b(?:UNION|SELECT|FROM|WHERE)\b/i',
            '/\b(?:--|#|\/\*.*?\*\/)/i',
            '/\b(?:EXEC|EXECUTE|DECLARE|CAST|CONVERT)\b/i',
            '/\b(?:WAITFOR|DELAY|SLEEP)\b/i',
            '/\b(?:LOAD_FILE|OUTFILE|DUMPFILE)\b/i'
        );

        foreach ($harmful_patterns as $pattern) {
            if (preg_match($pattern, $query)) {
                $this->log_security_event(
                    sprintf('SQL injection attempt detected in query: %s', $query),
                    'critical'
                );
                return '';
            }
        }

        // Sanitize query parameters
        $query = preg_replace('/[^\x20-\x7E]/', '', $query);
        return $query;
    }

    public function check_xss_protection() {
        // Utvidet XSS-beskyttelse
        $xss_patterns = array(
            '/<script\b[^>]*>(.*?)<\/script>/is',
            '/javascript:/i',
            '/vbscript:/i',
            '/on\w+\s*=/i',
            '/data:/i',
            '/expression\s*\(/i',
            '/<iframe\b[^>]*>/i',
            '/<object\b[^>]*>/i',
            '/<embed\b[^>]*>/i',
            '/<applet\b[^>]*>/i'
        );

        // Sjekk GET-parametere
        foreach ($_GET as $key => $value) {
            if (is_string($value)) {
                foreach ($xss_patterns as $pattern) {
                    if (preg_match($pattern, $value)) {
                        $this->log_security_event(
                            sprintf('XSS attempt detected in GET parameter %s: %s', $key, $value),
                            'critical'
                        );
                        wp_die('Invalid request detected', 'Security Error', array('response' => 403));
                    }
                }
            }
        }

        // Sjekk POST-parametere
        foreach ($_POST as $key => $value) {
            if (is_string($value)) {
                foreach ($xss_patterns as $pattern) {
                    if (preg_match($pattern, $value)) {
                        $this->log_security_event(
                            sprintf('XSS attempt detected in POST parameter %s: %s', $key, $value),
                            'critical'
                        );
                        wp_die('Invalid request detected', 'Security Error', array('response' => 403));
                    }
                }
            }
        }
    }

    public function check_file_integrity() {
        $critical_files = array(
            ABSPATH . 'wp-config.php',
            WP_CONTENT_DIR . '/plugins',
            WP_CONTENT_DIR . '/themes'
        );

        foreach ($critical_files as $file) {
            if (file_exists($file)) {
                $perms = fileperms($file);
                if ($perms !== 0755) {
                    chmod($file, 0755);
                    $this->log_event('Fixed permissions for: ' . $file);
                }
            }
        }
    }

    public function verify_database_connection() {
        global $wpdb;
        
        try {
            // Test database connection
            $wpdb->get_results("SELECT 1");
            
            // Check if tables exist
            $required_tables = array(
                $wpdb->prefix . 'carrey_websites',
                $wpdb->prefix . 'carrey_keywords',
                $wpdb->prefix . 'carrey_issues'
            );
            
            foreach ($required_tables as $table) {
                if ($wpdb->get_var("SHOW TABLES LIKE '$table'") != $table) {
                    $this->log_error("Required table missing: $table");
                    $this->create_missing_tables();
                }
            }
            
            // Verify database version
            $current_version = get_option('carrey_db_version', '0');
            if (version_compare($current_version, CARREY_DB_VERSION, '<')) {
                $this->log_event("Database needs update from $current_version to " . CARREY_DB_VERSION);
                $this->update_database();
            }
            
        } catch (Exception $e) {
            $this->log_error("Database connection error: " . $e->getMessage());
            $this->attempt_database_recovery();
        }
    }

    private function create_missing_tables() {
        global $wpdb;
        
        $charset_collate = $wpdb->get_charset_collate();
        
        // Create websites table
        $sql = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}carrey_websites (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            user_id bigint(20) NOT NULL,
            url varchar(255) NOT NULL,
            seo_score int(11) DEFAULT 0,
            last_checked datetime DEFAULT NULL,
            status varchar(20) DEFAULT 'active',
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY  (id),
            KEY user_id (user_id),
            KEY url (url)
        ) $charset_collate;";
        
        $wpdb->query($sql);
        
        // Create keywords table
        $sql = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}carrey_keywords (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            website_id bigint(20) NOT NULL,
            keyword varchar(255) NOT NULL,
            position int(11) DEFAULT 0,
            volume int(11) DEFAULT 0,
            difficulty int(11) DEFAULT 0,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY  (id),
            KEY website_id (website_id),
            KEY keyword (keyword)
        ) $charset_collate;";
        
        $wpdb->query($sql);
        
        // Create issues table
        $sql = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}carrey_issues (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            website_id bigint(20) NOT NULL,
            type varchar(50) NOT NULL,
            severity varchar(20) NOT NULL,
            description text NOT NULL,
            status varchar(20) DEFAULT 'open',
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY  (id),
            KEY website_id (website_id),
            KEY type (type),
            KEY severity (severity)
        ) $charset_collate;";
        
        $wpdb->query($sql);
        
        update_option('carrey_db_version', CARREY_DB_VERSION);
        $this->log_event("Created missing database tables");
    }

    private function update_database() {
        global $wpdb;
        $current_version = get_option('carrey_db_version', '0');
        
        // Add new columns or tables as needed
        if (version_compare($current_version, '1.1', '<')) {
            $wpdb->query("ALTER TABLE {$wpdb->prefix}carrey_websites ADD COLUMN IF NOT EXISTS ssl_status varchar(20) DEFAULT 'unknown'");
            $wpdb->query("ALTER TABLE {$wpdb->prefix}carrey_websites ADD COLUMN IF NOT EXISTS mobile_score int(11) DEFAULT 0");
        }
        
        if (version_compare($current_version, '1.2', '<')) {
            $wpdb->query("ALTER TABLE {$wpdb->prefix}carrey_keywords ADD COLUMN IF NOT EXISTS cpc decimal(10,2) DEFAULT 0");
            $wpdb->query("ALTER TABLE {$wpdb->prefix}carrey_keywords ADD COLUMN IF NOT EXISTS competition int(11) DEFAULT 0");
        }
        
        update_option('carrey_db_version', CARREY_DB_VERSION);
        $this->log_event("Updated database to version " . CARREY_DB_VERSION);
    }
} 