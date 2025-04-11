<?php
/**
 * API Class
 *
 * @package Carrey_SEO_Dashboard
 */

if (!defined('ABSPATH')) {
    exit;
}

class Carrey_API {
    private static $instance = null;
    private $api_url;
    private $api_key;
    private $cache_time;
    private $logger;

    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        $this->api_url = 'https://api.carreyseo.com/v1/';
        $this->api_key = get_option('carrey_api_key', '');
        $this->cache_time = 3600; // 1 hour
        $this->logger = Carrey_Logger::get_instance();
    }

    public function init() {
        add_action('admin_init', array($this, 'verify_api_connection'));
    }

    public function verify_api_connection() {
        $response = $this->make_request('ping');
        if (is_wp_error($response)) {
            $this->logger->log_error('API connection failed: ' . $response->get_error_message());
            add_action('admin_notices', array($this, 'display_api_error'));
        }
    }

    public function display_api_error() {
        ?>
        <div class="notice notice-error">
            <p><?php _e('Carrey SEO Dashboard: Could not connect to API. Please check your API key.', 'carrey-seo-dashboard'); ?></p>
        </div>
        <?php
    }

    public function make_request($endpoint, $method = 'GET', $data = array()) {
        $cache_key = 'carrey_api_' . md5($endpoint . serialize($data));
        $cached_response = get_transient($cache_key);

        if ($cached_response !== false) {
            return $cached_response;
        }

        $url = $this->api_url . $endpoint;
        $args = array(
            'method' => $method,
            'headers' => array(
                'Authorization' => 'Bearer ' . $this->api_key,
                'Content-Type' => 'application/json'
            ),
            'timeout' => 30
        );

        if (!empty($data)) {
            $args['body'] = json_encode($data);
        }

        $response = wp_remote_request($url, $args);

        if (is_wp_error($response)) {
            $this->logger->log_error('API request failed: ' . $response->get_error_message());
            return $response;
        }

        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->logger->log_error('Could not decode API response: ' . json_last_error_msg());
            return new WP_Error('json_decode_error', 'Could not decode API response');
        }

        set_transient($cache_key, $data, $this->cache_time);
        return $data;
    }

    public function get_seo_data($url) {
        $endpoint = 'seo/analyze';
        $data = array('url' => $url);
        return $this->make_request($endpoint, 'POST', $data);
    }

    public function get_keyword_data($keyword) {
        $endpoint = 'keywords/analyze';
        $data = array('keyword' => $keyword);
        return $this->make_request($endpoint, 'POST', $data);
    }

    public function get_competitor_data($url) {
        $endpoint = 'competitors/analyze';
        $data = array('url' => $url);
        return $this->make_request($endpoint, 'POST', $data);
    }

    public function get_backlink_data($url) {
        $endpoint = 'backlinks/analyze';
        $data = array('url' => $url);
        return $this->make_request($endpoint, 'POST', $data);
    }

    public function validate_api_key($key) {
        $endpoint = 'validate';
        $data = array('api_key' => $key);
        $response = $this->make_request($endpoint, 'POST', $data);
        
        if (is_wp_error($response)) {
            return false;
        }

        return isset($response['valid']) && $response['valid'] === true;
    }
} 