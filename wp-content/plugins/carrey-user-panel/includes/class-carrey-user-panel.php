<?php
/**
 * Main plugin class
 */
class Carrey_User_Panel {
    /**
     * Initialize the plugin
     */
    public function init() {
        // Add actions and filters
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_styles'));
        add_action('rest_api_init', array($this, 'register_rest_routes'));
        
        // Add shortcode
        add_shortcode('carrey_user_panel', array($this, 'render_user_panel'));
    }

    /**
     * Add admin menu items
     */
    public function add_admin_menu() {
        add_menu_page(
            'Carrey User Panel',
            'Carrey Panel',
            'manage_options',
            'carrey-user-panel',
            array($this, 'render_admin_page'),
            'dashicons-admin-users',
            30
        );
    }

    /**
     * Render admin page
     */
    public function render_admin_page() {
        // Check user capabilities
        if (!current_user_can('manage_options')) {
            return;
        }

        // Include admin template
        include CARREY_USER_PANEL_PATH . 'templates/admin-page.php';
    }

    /**
     * Enqueue scripts
     */
    public function enqueue_scripts() {
        wp_enqueue_script(
            'carrey-user-panel',
            CARREY_USER_PANEL_URL . 'assets/js/carrey-user-panel.js',
            array('jquery'),
            CARREY_USER_PANEL_VERSION,
            true
        );

        // Localize script
        wp_localize_script('carrey-user-panel', 'carreyUserPanel', array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('carrey_user_panel_nonce'),
            'apiUrl' => rest_url('carrey-user-panel/v1')
        ));
    }

    /**
     * Enqueue styles
     */
    public function enqueue_styles() {
        wp_enqueue_style(
            'carrey-user-panel',
            CARREY_USER_PANEL_URL . 'assets/css/carrey-user-panel.css',
            array(),
            CARREY_USER_PANEL_VERSION
        );
    }

    /**
     * Register REST API routes
     */
    public function register_rest_routes() {
        register_rest_route('carrey-user-panel/v1', '/settings', array(
            'methods' => 'GET',
            'callback' => array($this, 'get_user_settings'),
            'permission_callback' => array($this, 'check_user_permission')
        ));

        register_rest_route('carrey-user-panel/v1', '/settings', array(
            'methods' => 'POST',
            'callback' => array($this, 'update_user_settings'),
            'permission_callback' => array($this, 'check_user_permission')
        ));
    }

    /**
     * Get user settings
     */
    public function get_user_settings($request) {
        global $wpdb;
        $user_id = get_current_user_id();
        
        $table_name = $wpdb->prefix . 'carrey_user_settings';
        $settings = $wpdb->get_var($wpdb->prepare(
            "SELECT settings FROM $table_name WHERE user_id = %d",
            $user_id
        ));

        return json_decode($settings, true) ?: array();
    }

    /**
     * Update user settings
     */
    public function update_user_settings($request) {
        global $wpdb;
        $user_id = get_current_user_id();
        $settings = $request->get_json_params();

        $table_name = $wpdb->prefix . 'carrey_user_settings';
        $wpdb->replace(
            $table_name,
            array(
                'user_id' => $user_id,
                'settings' => json_encode($settings)
            ),
            array('%d', '%s')
        );

        return array('success' => true);
    }

    /**
     * Check user permission
     */
    public function check_user_permission() {
        return is_user_logged_in();
    }

    /**
     * Render user panel shortcode
     */
    public function render_user_panel() {
        if (!is_user_logged_in()) {
            return '<div class="carrey-user-panel-login">
                <p>Vennligst logg inn for å få tilgang til brukerpanelet.</p>
                ' . wp_login_form(array('echo' => false)) . '
            </div>';
        }

        ob_start();
        include CARREY_USER_PANEL_PATH . 'templates/user-panel.php';
        return ob_get_clean();
    }
} 