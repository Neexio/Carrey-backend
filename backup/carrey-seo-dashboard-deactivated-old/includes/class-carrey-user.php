<?php
if (!defined('ABSPATH')) {
    exit;
}

class Carrey_User {
    private static $instance = null;
    private $user_data = array();

    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        add_action('init', array($this, 'init'));
        add_action('wp_ajax_carrey_login', array($this, 'handle_login'));
        add_action('wp_ajax_carrey_register', array($this, 'handle_registration'));
        add_action('wp_ajax_carrey_reset_password', array($this, 'handle_password_reset'));
    }

    public function init() {
        if (is_user_logged_in()) {
            $this->load_user_data();
        }
    }

    public function load_user_data() {
        $user_id = get_current_user_id();
        $this->user_data = array(
            'websites' => get_user_meta($user_id, 'carrey_websites', true) ?: array(),
            'subscription' => get_user_meta($user_id, 'carrey_subscription', true) ?: array(),
            'seo_stats' => get_user_meta($user_id, 'carrey_seo_stats', true) ?: array(),
            'keywords' => get_user_meta($user_id, 'carrey_keywords', true) ?: array()
        );
    }

    public function handle_login() {
        check_ajax_referer('carrey_login_nonce', 'nonce');

        $credentials = array(
            'user_login' => sanitize_text_field($_POST['username']),
            'user_password' => $_POST['password'],
            'remember' => isset($_POST['remember'])
        );

        $user = wp_signon($credentials);

        if (is_wp_error($user)) {
            wp_send_json_error(array('message' => $user->get_error_message()));
        }

        wp_send_json_success(array('redirect' => admin_url('admin.php?page=carrey-dashboard')));
    }

    public function handle_registration() {
        check_ajax_referer('carrey_register_nonce', 'nonce');

        $username = sanitize_text_field($_POST['username']);
        $email = sanitize_email($_POST['email']);
        $password = $_POST['password'];

        $user_id = wp_create_user($username, $password, $email);

        if (is_wp_error($user_id)) {
            wp_send_json_error(array('message' => $user_id->get_error_message()));
        }

        // Set default user role
        $user = new WP_User($user_id);
        $user->set_role('carrey_user');

        // Send welcome email
        wp_new_user_notification($user_id, null, 'user');

        wp_send_json_success(array('message' => 'Registration successful!'));
    }

    public function handle_password_reset() {
        check_ajax_referer('carrey_reset_password_nonce', 'nonce');

        $email = sanitize_email($_POST['email']);
        $user = get_user_by('email', $email);

        if (!$user) {
            wp_send_json_error(array('message' => 'No user found with this email address.'));
        }

        $key = get_password_reset_key($user);
        if (is_wp_error($key)) {
            wp_send_json_error(array('message' => 'Error generating password reset key.'));
        }

        $message = __('Someone has requested a password reset for the following account:') . "\r\n\r\n";
        $message .= network_home_url('/') . "\r\n\r\n";
        $message .= sprintf(__('Username: %s'), $user->user_login) . "\r\n\r\n";
        $message .= __('If this was a mistake, just ignore this email and nothing will happen.') . "\r\n\r\n";
        $message .= __('To reset your password, visit the following address:') . "\r\n\r\n";
        $message .= network_site_url("wp-login.php?action=rp&key=$key&login=" . rawurlencode($user->user_login), 'login') . "\r\n";

        if (wp_mail($email, __('Password Reset Request'), $message)) {
            wp_send_json_success(array('message' => 'Password reset email sent.'));
        } else {
            wp_send_json_error(array('message' => 'Error sending password reset email.'));
        }
    }

    public function get_user_data() {
        return $this->user_data;
    }

    public function update_user_data($data) {
        $user_id = get_current_user_id();
        foreach ($data as $key => $value) {
            update_user_meta($user_id, 'carrey_' . $key, $value);
        }
        $this->load_user_data();
    }

    public function add_website($website_data) {
        $user_id = get_current_user_id();
        $websites = get_user_meta($user_id, 'carrey_websites', true) ?: array();
        $websites[] = $website_data;
        update_user_meta($user_id, 'carrey_websites', $websites);
        $this->load_user_data();
    }

    public function remove_website($website_id) {
        $user_id = get_current_user_id();
        $websites = get_user_meta($user_id, 'carrey_websites', true) ?: array();
        $websites = array_filter($websites, function($website) use ($website_id) {
            return $website['id'] !== $website_id;
        });
        update_user_meta($user_id, 'carrey_websites', $websites);
        $this->load_user_data();
    }

    public function update_keywords($website_id, $keywords) {
        $user_id = get_current_user_id();
        $user_keywords = get_user_meta($user_id, 'carrey_keywords', true) ?: array();
        $user_keywords[$website_id] = $keywords;
        update_user_meta($user_id, 'carrey_keywords', $user_keywords);
        $this->load_user_data();
    }

    public function register_user() {
        check_ajax_referer('carrey_payment_nonce', 'nonce');
        
        $username = sanitize_user($_POST['username']);
        $email = sanitize_email($_POST['email']);
        $password = $_POST['password'];
        
        // Validate input
        if (empty($username) || empty($email) || empty($password)) {
            wp_send_json_error(array('message' => 'All fields are required'));
            return;
        }
        
        if (!is_email($email)) {
            wp_send_json_error(array('message' => 'Invalid email address'));
            return;
        }
        
        if (username_exists($username)) {
            wp_send_json_error(array('message' => 'Username already exists'));
            return;
        }
        
        if (email_exists($email)) {
            wp_send_json_error(array('message' => 'Email already exists'));
            return;
        }
        
        // Create user
        $user_id = wp_create_user($username, $password, $email);
        
        if (is_wp_error($user_id)) {
            wp_send_json_error(array('message' => $user_id->get_error_message()));
            return;
        }
        
        // Set user role
        $user = new WP_User($user_id);
        $user->set_role('carrey_seo_user');
        
        // Log the user in
        wp_set_current_user($user_id);
        wp_set_auth_cookie($user_id);
        
        wp_send_json_success(array(
            'message' => 'Registration successful!',
            'redirect' => admin_url('admin.php?page=carrey-subscription')
        ));
    }
} 