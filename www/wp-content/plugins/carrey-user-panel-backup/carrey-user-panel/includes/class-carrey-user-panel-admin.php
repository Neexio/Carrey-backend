<?php
/**
 * Admin class for the plugin
 */
class Carrey_User_Panel_Admin {
    /**
     * Initialize admin functionality
     */
    public function init() {
        add_action('admin_init', array($this, 'register_settings'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_styles'));
    }

    /**
     * Register plugin settings
     */
    public function register_settings() {
        register_setting('carrey_user_panel_options', 'carrey_user_panel_settings');

        add_settings_section(
            'carrey_user_panel_main',
            'Main Settings',
            array($this, 'render_main_section'),
            'carrey-user-panel'
        );

        add_settings_field(
            'enable_user_panel',
            'Enable User Panel',
            array($this, 'render_enable_field'),
            'carrey-user-panel',
            'carrey_user_panel_main'
        );

        add_settings_field(
            'allowed_roles',
            'Allowed User Roles',
            array($this, 'render_roles_field'),
            'carrey-user-panel',
            'carrey_user_panel_main'
        );
    }

    /**
     * Render main section
     */
    public function render_main_section() {
        echo '<p>Configure the Carrey User Panel settings below.</p>';
    }

    /**
     * Render enable field
     */
    public function render_enable_field() {
        $options = get_option('carrey_user_panel_settings');
        $enabled = isset($options['enable_user_panel']) ? $options['enable_user_panel'] : false;
        ?>
        <input type="checkbox" 
               name="carrey_user_panel_settings[enable_user_panel]" 
               value="1" 
               <?php checked(1, $enabled, true); ?> />
        <?php
    }

    /**
     * Render roles field
     */
    public function render_roles_field() {
        $options = get_option('carrey_user_panel_settings');
        $allowed_roles = isset($options['allowed_roles']) ? $options['allowed_roles'] : array();
        $roles = get_editable_roles();

        foreach ($roles as $role => $details) {
            $checked = in_array($role, $allowed_roles) ? 'checked' : '';
            ?>
            <label>
                <input type="checkbox" 
                       name="carrey_user_panel_settings[allowed_roles][]" 
                       value="<?php echo esc_attr($role); ?>" 
                       <?php echo $checked; ?> />
                <?php echo esc_html($details['name']); ?>
            </label><br>
            <?php
        }
    }

    /**
     * Enqueue admin scripts
     */
    public function enqueue_admin_scripts() {
        wp_enqueue_script(
            'carrey-user-panel-admin',
            CARREY_USER_PANEL_URL . 'assets/js/admin.js',
            array('jquery'),
            CARREY_USER_PANEL_VERSION,
            true
        );
    }

    /**
     * Enqueue admin styles
     */
    public function enqueue_admin_styles() {
        wp_enqueue_style(
            'carrey-user-panel-admin',
            CARREY_USER_PANEL_URL . 'assets/css/admin.css',
            array(),
            CARREY_USER_PANEL_VERSION
        );
    }
} 