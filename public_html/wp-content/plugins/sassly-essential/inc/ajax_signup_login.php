<?php 

namespace SasslyEssentialApp\Inc;

Class Ajax_SignUp_LogIn{

    public function __construct(){
        // username
        add_action('wp_ajax_nopriv_sassly_user_register_username_validation', [ $this , 'username_availability' ]);    
        add_action('wp_ajax_sassly_user_register_username_validation', [ $this , 'username_availability' ] );
         // email
        add_action('wp_ajax_nopriv_sassly_user_register_email_validation', [ $this , 'email_availability' ]);    
        add_action('wp_ajax_sassly_user_register_email_validation', [ $this , 'email_availability' ] );
        
        add_action('wp_ajax_nopriv_sassly_user_register_form_submit', [ $this , 'register_form_submit' ]);    
        add_action('wp_ajax_sassly_user_register_form_submit', [ $this , 'register_form_submit' ] );
        
        add_action('user_register', [ $this ,'custom_registration_email_notification' ]);
        add_action('init', [ $this ,'login' ]);
    }
    
    function login(){
        if(isset($_POST['sassly-submit'])){
			$creds = array();
			$creds['user_login'] = $_POST['log'];
			$creds['user_password'] = $_POST['pwd'];
			$creds['remember'] = true;
			$user = wp_signon( $creds, false );
			if ( is_wp_error($user) ){
			    $GLOBALS['sassly_login_errors'] =  $user->get_error_message();
			}		
			
			// Redirect URL //
			if ( !is_wp_error( $user ) )
			{
			    wp_clear_auth_cookie();
			    wp_set_current_user ( $user->ID );
			    wp_set_auth_cookie ( $user->ID );	
			
			    if( 
			    isset($_POST['sassly_sucess_enable_redirect']) &&
			    $_POST['sassly_sucess_enable_redirect'] == 'yes' && 
			    isset($_POST['sassly_sucess_redirect']) && $_POST['sassly_sucess_redirect'] !=''
			    ){
                    wp_safe_redirect( $_POST['sassly_sucess_redirect'] );
			    }else{
                    wp_safe_redirect( $_POST['redirect_to'] );
			    }
			   
			    exit();
			}
		}
    }
    
    function custom_registration_email_notification($user_id) {
        $user = get_userdata($user_id);
        $user_email = $user->user_email;
        error_log('Email Sent');
        // Send email to the user
        $subject = 'Welcome to ' . get_bloginfo('name');
        $message = 'Hello ' . $user->first_name . ',<br>Welcome to ' . get_bloginfo('name') . '!<br>Your username is: ' . $user->user_login;
        wp_mail($user_email, $subject, $message);
    
        // Send email to the admin
        $admin_email = get_option('admin_email');
        $admin_subject = 'New User Registration';
        $admin_message = 'A new user has registered on ' . get_bloginfo('name') . '.<br>Username: ' . $user->user_login . '<br>Email: ' . $user_email;
        wp_mail($admin_email, $admin_subject, $admin_message);
    }
    
    function register_form_submit(){
    
        if ( ! wp_verify_nonce( $_POST['nonce'], 'sassly-security-nonce' ) ) {
            die ( 'dump!');
        }   
        
		$return_msg = '';
		$return_msg = esc_html__( 'Registration Failed','sassly-essential' );
		$text_cls   = 'error';
		$firstName  = sanitize_text_field( $_POST[ 'name' ] );
		$username   = trim(sanitize_user( $_POST[ 'username' ] ));
		$email      = trim(sanitize_email( $_POST[ 'email' ] ));
		$password   = trim(sanitize_text_field( $_POST[ 'password' ] ));
		$user_id    = wp_create_user( $username, $password, $email );     
       
	    if( $user_id ){	           
            wp_new_user_notification($user_id);
            
            wp_update_user([
                'ID'         => $user_id,     // this is the ID of the user you want to update.
                'first_name' => $firstName,
            ]);            
            $return_msg = esc_html__('Registration complete. Please check your email.','sassly-essential');
        	$text_cls = 'valid';
	    }else{
            $return_msg = 'Error: ' . $user_id->get_error_message();	    
	    }
	    
        wp_send_json_success( array(                  
            'msg' => sprintf('<h2 class="%s msg-color">%s</h2>', $text_cls , $return_msg ),            
            'cls' =>  $text_cls            
        ), 200 );
	   
    }
    
    function email_availability(){
    
        if ( ! wp_verify_nonce( $_POST['nonce'], 'sassly-security-nonce' ) ) {
            die ( 'dump!');
        }
        
        $email = trim(sanitize_email( $_POST['email'] ));
        $return_msg = '';
        $text_cls = 'r';
        if ( email_exists( $email ) ) {
        	$return_msg = esc_html__('Email In Use!','sassly-essential');
            $text_cls = 'error';
        } else {
        	$return_msg = esc_html__('Available','sassly-essential');
        	$text_cls = 'valid';
        }
        
        wp_send_json_success( array(                  
            'msg' => sprintf('<span class="%s msg-color">%s</span>', $text_cls , $return_msg ),            
            'cls' =>  $text_cls,            
        ), 200 );
    }
    
    function username_availability(){
    
        if ( ! wp_verify_nonce( $_POST['nonce'], 'sassly-security-nonce' ) ) {
            die ( 'dump!');
        }
        
        $username = trim(sanitize_user( $_POST['username'] ));
        $return_msg = '';
        $text_cls = 'r';
        if ( username_exists( $username ) ) {
        	$return_msg = esc_html__('Username In Use!','sassly-essential');
            $text_cls = 'error';
        } else {
        	$return_msg = esc_html__('Available','sassly-essential');
        	$text_cls = 'valid';
        }
        
        wp_send_json_success( array(                  
            'msg' => sprintf('<span class="%s msg-color">%s</span>', $text_cls , $return_msg ),            
            'cls' =>  $text_cls,            
        ), 200 );
        
    }
  }

new Ajax_SignUp_LogIn();