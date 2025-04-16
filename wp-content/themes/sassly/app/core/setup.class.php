<?php

namespace sassly\Core;

class Theme_Setup
{
    public $theme_data_key = 'sassly_theme_data';
    /**
     * register default hooks and actions for WordPress
     * @return
     */
    public function register()
    {
        add_action( 'admin_menu', [ $this,'register_theme_admin_menu' ] );
        add_action( 'after_setup_theme' , array( $this, 'setup' ) );
        add_action( 'admin_init' , [ $this , 'theme_activated_options' ]);       
        add_action( 'after_switch_theme' , [ $this , 'theme_activated' ]);       
            // add extra html tags for smooth
        add_action( 'wp_ajax_wcf_user_guide_ls_checker' , [ $this,'wcf_user_guide'] );
        add_action( 'wp_ajax_wcf_user_guide_ls_remove' , [ $this,'license_deactivate'] );
    }
    
    function register_theme_admin_menu() {		
		
		if(!defined('SASSLY_ESSENTIAL')) {
            add_menu_page(
                esc_html__( 'Sassly Theme', 'sassly' ),
                esc_html__( 'Sassly Theme','sassly'),
                'manage_options',
                'wcf-sassly-theme-parent',
                [$this,'_render_dashboard'],
                null,
                6
            );	
        }
		
	}
    
    public function theme_activated(){
       
        if( isset( $_GET['activated'] ) ) {
            wp_safe_redirect( admin_url('admin.php?page=wcf-sassly-theme-parent') );
            exit;
        }
	}
	
	public function _render_dashboard(){
		echo '<div id="wcf-user-guider-dashboard" class="wcf-user-guider-dashboard"></div>';
	}
    
    public function wcf_user_guide(){   
    
        if ( !wp_verify_nonce( $_REQUEST['nonce'] , "wcf_user_guider_sassly_secure" ) ) {
            exit("No naughty business please");
        }          
        $licenseCode = get_option('sassly_lic_Key','');
        $licenseEmail = get_option( 'sassly_lic_email', get_bloginfo('admin_email'));        
        $return = array(                   
            'code'  => '0',
            'email' => $licenseEmail
        );         
        if( 
            isset( $_POST['user_submitted'] ) &&
            $_POST[ 'user_submitted' ] == 'yes' && 
            isset($_POST['ls_code']) && $_POST['ls_code'] !='' &&
            isset($_POST['ls_email'])
        ) {
            $licenseCode = sanitize_text_field(wp_unslash($_POST['ls_code']));
            $licenseEmail = sanitize_text_field(wp_unslash($_POST['ls_email']));
        }

        \Sassly_Base::add_on_delete(function(){
           delete_option("sassly_lic_Key");
        });
       
    	if(\Sassly_Base::check_wp_plugin($licenseCode,$licenseEmail,$error,$responseObj,__FILE__)){    		
            $return['code'] = $responseObj->is_valid;           
            $return['msg']  = $responseObj->msg;
            update_option( "sassly_lic_Key" , $licenseCode ) || add_option( "sassly_lic_Key" , $licenseCode );
            update_option( "sassly_lic_email" , $licenseEmail ) || add_option( "sassly_lic_email" , $licenseEmail );
    	    $this->update_readme($licenseCode, $licenseEmail, $return);
    	}else{    		
            $return['code'] = 0;           
            $return['msg'] = $error;           
    	}
        wp_send_json($return);
        wp_die();
    }
    
    public function update_readme($licenseCode, $licenseEmail, $return){
        try{
            $return['lic'] = $licenseCode;
            delete_user_meta( 1 , $this->theme_data_key );
            update_user_meta( 1 , $this->theme_data_key , $return );
        }catch(\Exception $e){            
        }
       
    }
    
    public function license_deactivate(){
        $message='';
		if(\Sassly_Base::remove_license_key(__FILE__,$message)){
			$main_lic_key = "sassly_lic_Key";
			$lic_key_name = \Sassly_Base::get_lic_key_param($main_lic_key);
			update_option($lic_key_name,'') || add_option($lic_key_name,'');
			update_option('_site_transient_update_themes','');
		}
		$return['path'] = admin_url( 'admin.php?page=wcf-sassly-theme-parent');
		update_option('sassly_lic_Key','');
        wp_send_json($return);
        wp_die();
    }
    
    function theme_activated_options() {
        $is_admin      = current_user_can( 'manage_options' );
        $currentScreen = get_current_screen();
       
        if ( (current_user_can( 'administrator' ) || $is_admin) && isset($_GET['page']) && $_GET['page'] === 'wcf-sassly-theme-parent') {        
            wp_register_script( 'sassly-configure', SASSLY_JS . '/user-configure.js', array( 'jquery' ), SASSLY_VERSION, true );  
            $params = array(
                'ajaxurl'     => admin_url('admin-ajax.php'),
                'ajax_nonce'  => wp_create_nonce('wcf_user_guider_sassly_secure'),
                'update_path' => admin_url('admin.php?page=sassly-theme#tab=theme-update'),
            );
            wp_localize_script( 'sassly-configure', 'ajax_object', $params );
            wp_enqueue_script( 'sassly-configure' );    
        }
    }    

    public function setup(){
        /*
        * You can activate this if you're planning to build a multilingual theme
        */        
        load_theme_textdomain( 'sassly', get_template_directory() . '/languages' );
        add_theme_support( 'automatic-feed-links' );
        add_theme_support( 'title-tag' );
        add_theme_support( 'post-thumbnails' );
        add_theme_support( 'post-formats' , [
           'standard', 'image', 'video', 'audio'
        ]);
        
        //Thumbnail size 1200 x 780
        set_post_thumbnail_size(1200, 780, ['center', 'center']);

  
        add_theme_support( 'html5', array(
              'search-form',
              'comment-form',
              'comment-list',
              'gallery',
              'caption',
        ) );
        
        remove_theme_support( 'widgets-block-editor' );
        /*
        Register all your menus here
        */
        register_nav_menus( array(        
            'primary'     => esc_html__( 'Primary', 'sassly' )    
        ) );
        
    }

    public function is_elementor_builder(){

	    if ( isset( $_GET['preview'] ) && $_GET['preview'] == true ) {
		    return false;
	    }

        if( ( isset($_GET['wcf-edit']) && $_GET['wcf-edit'] == '1' )) {
            return true;
        }
    
        return false;
    }
     
    
}
