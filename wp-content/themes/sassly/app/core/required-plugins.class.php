<?php

namespace sassly\Core;

require_once( ABSPATH . 'wp-load.php' );
require_once( ABSPATH . 'wp-includes/pluggable.php');
require_once( ABSPATH . 'wp-admin/includes/plugin-install.php' );
require_once( ABSPATH . 'wp-admin/includes/file.php' );
require_once( ABSPATH . 'wp-admin/includes/misc.php' );
require_once( ABSPATH . 'wp-admin/includes/plugin.php' );

/**
 * Required Plugin.
 */
class Required_Plugins
{
	/**
	 * register default hooks and actions for WordPress
	 * @return
	 */
	public function register()
	{	
        add_action( 'tgmpa_register', [ $this,'register_required_plugins' ] );
        add_action( 'wp_ajax_wcf_user_guide_ls_installed_plugins' , [ $this , 'wcf_user_guide_plugins_install' ] );       
       
	}
	
	public function wcf_user_guide_plugins_install(){
        
        if ( !wp_verify_nonce( $_REQUEST['nonce'], "wcf_user_guider_sassly_secure")) {
            exit("No naughty business please");
        } 
       
        $plugins                = isset($_POST['plugins']) ? $_POST['plugins'] : [];
        $return                 = [
            'all_active'          => true,
            'current_message'     => '',
            'demo_path'           => '#',
            'recheck'             => false,
            'installable_plugins' => [],
            'activeable_plugins'  => []
        ];                 
               
        foreach( $plugins as $plugin ) {
            $plugin_mainfile = trailingslashit( WP_PLUGIN_DIR ) . $plugin['slug'];            
            $status          = '<svg width="8" height="8" viewBox="0 0 8 8" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M5.08651 4.00078L7.77348 1.31381C8.07498 1.01387 8.07498 0.528023 7.7766 0.22652C7.47666 -0.0749838 6.99082 -0.0749838 6.68932 0.223395L6.68776 0.224958L4.00078 2.91193L1.31224 0.22652C1.0123 -0.0734216 0.524897 -0.0734216 0.224956 0.22652C-0.0749853 0.526461 -0.0749853 1.01387 0.224956 1.31381L2.91193 4.00078L0.224956 6.68776C-0.0749853 6.9877 -0.0749853 7.4751 0.224956 7.77504C0.524897 8.07499 1.0123 8.07499 1.31224 7.77504L3.99922 5.08807L6.68619 7.77504C6.98613 8.07499 7.47354 8.07499 7.77348 7.77504C8.07342 7.4751 8.07342 6.9877 7.77348 6.68776L5.08651 4.00078Z" fill="#E24040"/>
            </svg>
            ';             
            if ( $this->is_plugin_installed( $plugin['slug'] ) ) {
                $status = '<svg width="7" height="6" viewBox="0 0 7 6" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" clip-rule="evenodd" d="M6.67022 1.50021C6.80685 1.33479 6.88254 1.11369 6.88254 0.879476C6.88254 0.643645 6.80685 0.424189 6.67022 0.257122C6.53221 0.0917052 6.34962 0 6.15612 0C5.96273 0 5.78013 0.0917052 5.64351 0.257122L4.53565 1.6001H4.53427L2.7203 3.79638C2.61479 3.92413 2.44299 3.92413 2.33748 3.79638L1.23903 2.4665C1.10112 2.30109 0.919812 2.20935 0.725031 2.20935C0.531636 2.20935 0.349041 2.30109 0.212417 2.4665C0.0757924 2.63354 0 2.853 0 3.08886C0 3.32304 0.0757924 3.54414 0.212417 3.70956L1.80584 5.63888C2.20353 6.12037 2.85286 6.12037 3.25194 5.63888L4.20554 4.48423L4.58836 4.02237L5.61508 2.77931L5.9979 2.31581L6.67022 1.50021Z" fill="#76E99D"/>
                </svg>
                ';                     
            }else{
                $return[ 'installable_plugins' ][] = [ 'slug' => $plugin['slug'] , 'source' => $plugin['remote-source'] ];
                $return[ 'all_active' ]            = false;
            } 
            // Activation Check
            if ( get_option('active_plugins') && in_array($plugin['slug'], array_map('dirname', get_option('active_plugins')), true)) {
                $status = '<svg width="11" height="6" viewBox="0 0 11 6" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" clip-rule="evenodd" d="M6.67022 1.50021C6.80685 1.33479 6.88254 1.11369 6.88254 0.879476C6.88254 0.643645 6.80685 0.424189 6.67022 0.257122C6.53221 0.0917052 6.34962 0 6.15612 0C5.96273 0 5.78013 0.0917052 5.64351 0.257122L4.53565 1.6001H4.53427L2.7203 3.79638C2.61479 3.92413 2.44299 3.92413 2.33748 3.79638L1.23903 2.4665C1.10112 2.30109 0.919812 2.20935 0.725031 2.20935C0.531636 2.20935 0.349041 2.30109 0.212417 2.4665C0.0757924 2.63354 0 2.853 0 3.08886C0 3.32304 0.0757924 3.54414 0.212417 3.70956L1.80584 5.63888C2.20353 6.12037 2.85286 6.12037 3.25194 5.63888L4.20554 4.48423L4.58836 4.02237L5.61508 2.77931L5.9979 2.31581L6.67022 1.50021ZM10.7876 1.50021C10.9242 1.33479 11 1.11369 11 0.879476C11 0.643645 10.9242 0.424189 10.7876 0.257122C10.651 0.0917052 10.4684 0 10.2749 0C10.0801 0 9.89888 0.0917052 9.76087 0.257122L6.83766 3.79638C6.73215 3.92413 6.56035 3.92413 6.45483 3.79638L5.99632 3.24117L4.9696 4.48423L5.9233 5.63888C6.32227 6.12037 6.97022 6.12037 7.3693 5.63888L10.7876 1.50021Z" fill="#76E99D"/>
                </svg>
                '; 
            }else{
                $return[ 'all_active' ]         = false;
                $return['activeable_plugins'][] = $plugin['slug'];
            }            
            $return[ 'plugin_status' ][]    = sprintf( "<li>%s %s</li>" , $plugin[ 'title' ] , $status);          
        }
        
        if( isset($_POST[ 'user_action' ]) && $_POST['user_action'] == 'yes' ){
        
            if(isset($return['installable_plugins']) && isset($return['installable_plugins'][0])){                
                $return[ 'recheck' ]         = true;
                $status                    = $return[ 'installable_plugins' ][0][ 'source' ] == "false" ? false : $return['installable_plugins'][0]['source'];
                $return[ 'current_message' ] = $return[ 'installable_plugins' ][0][ 'slug' ] . ' installed';
                $this->install_plugin( $return[ 'installable_plugins' ][0][ 'slug' ], $status );  // $return['installable_plugins'][0]['source']    
                if($return[ 'installable_plugins' ][ 0 ][ 'slug' ] == 'elementor'){
                  update_option( 'elementor_onboarded' , 1 );
                }
                wp_send_json( $return );
            }
        }
        
        if( isset($_POST['user_action']) && $_POST['user_action'] == 'yes' ){
        
            if(isset($return['activeable_plugins']) && !empty($return['activeable_plugins'])){            
                $return['recheck'] = true;   
                $return['current_message'] = $return['activeable_plugins'][0] . ' activated'; 
                $this->run_activate_plugin($return['activeable_plugins'][0]);
                wp_send_json( $return );
            }
        }
        
        if(isset($return['all_active']) && $return['all_active'] == true){
            $return['demo_path'] = admin_url( "admin.php?page=fw-backups-demo-content" );
        }
        
        wp_send_json( $return );
	    wp_die();
	}
	
	function run_activate_plugin( $slug ) {
        $pluginDir = WP_PLUGIN_DIR . '/' . $slug . '/';
        $activation_success = false;
    
        $files = scandir($pluginDir); 
        foreach($files as $file) {
            if(is_file($pluginDir.$file)) {
    
                $activation = activate_plugin($pluginDir.$file);
    
                if(!is_wp_error($activation)) {
                    $activation_success = true;
                }
    
            }
        }
    }
	
	function is_plugin_installed( $plugin ) {
        $plugins = \get_plugins( '/'.$this->get_plugin_dir( $plugin ) );
        if ( ! empty( $plugins ) ) {
            return true;
        }
        return false;
    }
    
    function get_plugin_dir( $plugin ) {
    
        $chunks = explode( '/', $plugin );
        if ( ! is_array( $chunks ) ) {
            $plugin_dir = $chunks;
        } else{
            $plugin_dir = $chunks[0];
        }
        return $plugin_dir;
    }
    
    
    /**
     * Intall a given plugin.     
     * @param  string $plugin Plugin basename.
     * @return null|string  Null when install was succesfull, otherwise error message.
     */
    function install_plugin( $plugin , $remote = true ) {
        $api = [];
        if(!$remote){
            $all_plugin_data = $this->get_plugin_config_data();
            $key             = array_search($plugin, array_column($all_plugin_data, 'slug'));
            $_dlink          = $all_plugin_data[$key]['source'];
            $download_link   = $_dlink;
        }else{
        
            $api = plugins_api(
                'plugin_information',
                array(
                    'slug'   => $this->get_plugin_dir( $plugin ),
                    'fields' => array(
                        'short_description' => false,
                        'requires'          => false,
                        'sections'          => false,
                        'rating'            => false,
                        'ratings'           => false,
                        'downloaded'        => false,
                        'last_updated'      => false,
                        'added'             => false,
                        'tags'              => false,
                        'compatibility'     => false,
                        'homepage'          => false,
                        'donate_link'       => false,
                    ),
                )
            );
          $download_link = $api->download_link;
        }
       
        // Replace new \Plugin_Installer_Skin with new Qu_Upgrader_Skin when output needs to be suppressed.
        $skin      = new WCF_Theme_Upgrader_Skin( array( 'api' => $api ) );
        $upgrader  = new \Plugin_Upgrader( $skin );      
        $error     = $upgrader->install( $download_link  );          
    }
        
    public function get_plugin_config_data(){
        $plugins	 = array( 
        
            array(
                'name'		 => esc_html__( 'Elementor', 'sassly' ),
                'slug'		 => 'elementor',
                'required'	 => true,
            ),
            
            array(
                'name'		 => esc_html__( 'Contact form 7', 'sassly' ),
                'slug'		 => 'contact-form-7',
                'required'	 => true,
            ),
           
            array(
                'name'		 => esc_html__( 'sassly Essential', 'sassly' ),
                'slug'		 => 'sassly-essential',
                'required'	 => true,
                'source'     => SASSLY_THEME_DIR .'/app/third-party/sassly-essential.zip', // The plugin source.               
            ),
            
            array(
                'name'		 => esc_html__( 'Animation Addons For Elementor', 'sassly' ),
                'slug'		 => 'animation-addons-for-elementor',
                'required'	 => true,
            ),
            
            array(
                'name'		 => esc_html__( 'WCF Element Library Pro', 'sassly' ),
                'slug'		 => 'wcf-addons-pro',
                'required'	 => true,
                'source'     => SASSLY_THEME_DIR .'/app/third-party/wcf-addons-pro.zip', // The plugin source.
            ),             
            array(
                'name'		 => esc_html__( 'WCF Element Library Extension', 'sassly' ),
                'slug'		 => 'extension-for-animation-addons',
                'required'	 => true,
                'source'     => SASSLY_THEME_DIR .'/app/third-party/extension-for-animation-addons.zip', // The plugin source.
            ),       
            
        );
        
        return $plugins;
    }    

	public function register_required_plugins() {
        //required plugins
       
        $plugins	 = $this->get_plugin_config_data();
    
    
        $config = array(
            'id'			 => 'sassly', // Unique ID for hashing notices for multiple instances of TGMPA.
            'default_path'	 => '', // Default absolute path to bundled plugins.
            'menu'			 => 'sassly-install-plugins', // Menu slug.
            'parent_slug'	 => 'themes.php', // Parent menu slug.
            'capability'	 => 'edit_theme_options', // Capability needed to view plugin install page, should be a capability associated with the parent menu used.
            'has_notices'	 => true, // Show admin notices or not.
            'dismissable'	 => true, // If false, a user cannot dismiss the nag message.
            'dismiss_msg'	 => '', // If 'dismissable' is false, this message will be output at top of nag.
            'is_automatic'	 => true, // Automatically activate plugins after installation or not.
            'message'		 => '', // Message to output right before the plugins table.
        );
    
        tgmpa( $plugins, $config );
    }

}




