<?php
/**
 * License Handler Class
 *
 * @package Carrey_SEO_Dashboard
 */

if (!defined('ABSPATH')) {
    exit;
}

class Carrey_License_Handler {
    private static $instance = null;
    private $license_key = '';
    private $license_status = '';
    private $license_data = array();

    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        $this->license_key = get_option('carrey_license_key', '');
        $this->license_status = get_option('carrey_license_status', '');
        $this->license_data = get_option('carrey_license_data', array());
    }

    public function init() {
        add_action('admin_init', array($this, 'register_settings'));
        add_action('admin_init', array($this, 'check_license'));
        add_action('admin_menu', array($this, 'add_license_menu'));
    }

    public function register_settings() {
        register_setting('carrey_license', 'carrey_license_key');
        register_setting('carrey_license', 'carrey_license_status');
        register_setting('carrey_license', 'carrey_license_data');
    }

    public function add_license_menu() {
        add_submenu_page(
            'carrey-seo-dashboard',
            'License',
            'License',
            'manage_options',
            'carrey-license',
            array($this, 'render_license_page')
        );
    }

    public function render_license_page() {
        ?>
        <div class="wrap">
            <h1>Carrey SEO License</h1>
            <form method="post" action="options.php">
                <?php 
                settings_fields('carrey_license');
                do_settings_sections('carrey_license');
                ?>
                <table class="form-table">
                    <tr>
                        <th scope="row">License Key</th>
                        <td>
                            <input type="text" name="carrey_license_key" value="<?php echo esc_attr($this->license_key); ?>" class="regular-text">
                            <p class="description">Enter your license key to activate the plugin.</p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">License Status</th>
                        <td>
                            <span class="license-status <?php echo esc_attr($this->license_status); ?>">
                                <?php echo esc_html(ucfirst($this->license_status)); ?>
                            </span>
                        </td>
                    </tr>
                </table>
                <?php submit_button('Save License'); ?>
            </form>
        </div>
        <style>
            .license-status {
                padding: 5px 10px;
                border-radius: 3px;
                font-weight: bold;
            }
            .license-status.valid {
                background-color: #d4edda;
                color: #155724;
            }
            .license-status.invalid {
                background-color: #f8d7da;
                color: #721c24;
            }
        </style>
        <?php
    }

    public function check_license() {
        if (empty($this->license_key)) {
            $this->license_status = 'invalid';
            update_option('carrey_license_status', 'invalid');
            return false;
        }

        // Here you would typically make an API call to validate the license
        // For now, we'll just set it as valid if a key exists
        $this->license_status = 'valid';
        update_option('carrey_license_status', 'valid');
        return true;
    }

    public function get_license_status() {
        return $this->license_status;
    }

    public function is_valid() {
        return $this->license_status === 'valid';
    }
} 