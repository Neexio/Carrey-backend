<?php 

namespace Wcf\Notices;

Class Wcf_Service_Notice{

    public $url            = 'https://crowdytheme.com/manager/wp-json/wcf-notice/v1/pull?custom_action=theme-update&test=true';
    public $cache_key      = 'wstt_notice_custom_action_theme_update';
    public $notice_title   = '';
    public $notice_content = '';
    public $main_content   = [];
    
    public $pending_times    = 'wcf_theme_notice_pending_times';
    public $last_update_time = 'wcf_theme_notice_last_update'; // Last update from server
    public $wait_next_time   = 'wcf_theme_notice_next';
    
    public function __construct(){
        add_action( 'admin_notices', [ $this, 'admin_notice_for_remote_service' ] );
        add_action( 'admin_notices', [ $this, 'theme_checker' ] );
        add_action( 'wp_ajax_wcf_theme_notice_dismiss' , [ $this ,'wcf_theme_notice_dismiss' ]);
        add_action( 'admin_init' , [ $this, 'admin_theme_license_varify' ] );
    }
    public function theme_checker(){
		$licenseCode  = get_option('sassly_lic_Key','');
		$user_data    = get_user_meta( 1 , 'sassly_theme_data', true ); 
        if(is_null($licenseCode) || $licenseCode === ''){
            if( $user_data && isset($user_data['lic'])){
				$message = sprintf(
					wp_kses_post( '%1$s<p>%2$s</p>', 'sassly-essential' ),
					'<h2>Varify License</h2>',
					sprintf('<div class="wcf-license-varify"><a href="%s" class="btn button-primary">Please <b>click</b> here to varify your Sassly Theme license</a></div>' , add_query_arg( ['page' => 'wcf-sassly-theme-parent', 'wcf-theme-license-varify'=>1], admin_url('/')))
				);		
				printf( '<div id="wcf-remote-theme-update" class="notice notice-warning is-dismissible wcf-remote-notice"><p>%1$s</p></div>', $message );   
            }
		}
    }
    
    public function admin_theme_license_varify(){
       
        if(isset($_GET['wcf-theme-license-varify']) && isset($_GET['page']) && $_GET['page'] === 'wcf-sassly-theme-parent'){
			$user_data    = get_user_meta( 1 , 'sassly_theme_data', true ); 
			$licenseCode = $user_data['lic'];
			$licenseEmail = $user_data['email'];
			
			if(\Sassly_Base::check_wp_plugin($licenseCode,$licenseEmail,$error,$responseObj,__FILE__)){    		
				$return['code'] = $responseObj->is_valid;           
				$return['msg']  = $responseObj->msg;
				update_option( "sassly_lic_Key" , $licenseCode ) || add_option( "sassly_lic_Key" , $licenseCode );
				update_option( "sassly_lic_email" , $licenseEmail ) || add_option( "sassly_lic_email" , $licenseEmail );	
				wp_redirect(add_query_arg( ['page'=>'wcf-sassly-theme-parent'], admin_url('/')));
			}
			
        }
    }
    public function wcf_theme_notice_dismiss(){
    
		if ( !wp_verify_nonce( $_REQUEST['nonce'], "wcf_theme_secure")) {
            exit("No naughty business please");
        }
        
        $return['msg'] = '';
        $transient_value = get_transient( $this->cache_key );
		
		if ( false === $transient_value || $transient_value === '') {
			$return['msg'] = 'value not found';
			wp_send_json($return);
		}else{
			$this->update_for_next($transient_value);		    
		}
      
        wp_send_json($return);
        wp_die();
    }
    
    public function update_for_next($transient_value){
		$prev_data = (int) get_option($this->pending_times);	
		if(isset($transient_value[ 'time_fragment_hour' ]) && is_numeric($transient_value[ 'time_fragment_hour' ])){			    
			if(isset($transient_value[ 'repeat' ]) && $transient_value[ 'repeat' ] != 1){
				$transient_value[ 'recur_times' ] = $transient_value[ 'recur_times' ] - 1; 			
				if($prev_data){
					$prev_data = $prev_data - 1;
				}else{
					$prev_data = $transient_value[ 'recur_times' ];
				}
			}else{
				$prev_data = $transient_value[ 'recur_times' ];
			}
			
			update_option( $this->pending_times , $prev_data );			
			set_transient( $this->wait_next_time , $transient_value[ 'recur_times' ] , $transient_value[ 'time_fragment_hour' ] * 3600  );      
			    
		}
		
    }
    
    /**
	 * Admin notice
	 *
	 * Display when the new product comming or Update
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function admin_notice_for_remote_service() {
		
		if(!$this->is_renderable()){return;}
		
		if(!$this->is_renderable_time()){ return; }		
		
		$this->notice_title = $this->notice_title !='' ? '<h2>' . wp_kses_post($this->notice_title) . '</h2>' : $this->notice_title;
		
		if($this->notice_content == '' || $this->notice_content == false){
			return; 
		}
		
		$message = sprintf(
			wp_kses_post( '%1$s<p>%2$s</p>', 'sassly-essential' ),
			$this->notice_title,
			$this->notice_content
		);

		printf( '<div id="wcf-remote-theme-update" class="notice notice-warning is-dismissible wcf-remote-notice"><p>%1$s</p></div>', $message );
	}
	/**
	 * determine notice render
	 * @return bool 
	 */
	public function is_renderable(){
	
		$data = $this->get_remote_data();	
		
		if ( false === $data || $data === '') {
			return false;
		}
		
		/*
		**
		***
		***** Content found
		***
		**
		*/
		if( is_array( $data ) && isset( $data[ 'success' ]) && $data[ 'success' ] === true){
			
			if(!isset($data['notice_content'])){
				return false;
			}
			
			$this->main_content = $data;
			if($data['notice_content'] === ''){
				return false;
			}			
			
			if( $this->determined_display_area() ){
				return true;
			}
		}
				
		return false; 
	}
	
	public function determined_display_area(){
		
		$currentScreen = get_current_screen();
		$return  = false;
		
		if( !isset( $this->main_content[ 'display_area' ] ) ){
			return $return;
		}
	
		if( 
			$currentScreen && 
			$currentScreen->base === 'dashboard' && 
			$currentScreen->id === 'dashboard' && 
			in_array('wp_dash',$this->main_content[ 'display_area' ] ) 
		){
			$return = true;
		}elseif(
			$currentScreen && 
			$currentScreen->base === 'plugins' && 
			$currentScreen->id === 'plugins' && 
			in_array('plugin_dashboard',$this->main_content[ 'display_area' ] )
		){
			$return = true;
		}elseif(
			$currentScreen && 
			$currentScreen->base === 'toplevel_page_wcf-sassly-theme-parent' &&
			$currentScreen->id === 'toplevel_page_wcf-sassly-theme-parent' && 
			in_array('theme_dash',$this->main_content[ 'display_area' ] )
		){
			$return = true;
		}elseif(
			$currentScreen && 
			$currentScreen->base === 'themes' && 
			$currentScreen->id === 'themes' && 
			in_array('theme_upload',$this->main_content[ 'display_area' ] )
		){
			$return = true;
		}elseif(
			$currentScreen && 
			in_array('all',$this->main_content[ 'display_area' ] )
		){
			$return = true;
		}
		
		if($return){
			$this->set_content_data();
		}
		
		return $return;
	}
	
	public function is_renderable_time(){
	
		$wait          = get_transient($this->wait_next_time);
		$pending_times = get_option($this->pending_times);	
		
		if($pending_times == 0){
			return false;
		}		
		if($wait === false || $wait === '' || !$pending_times){
			return true;
		}
		
		return false;
	}
	
	public function set_content_data(){
		$this->notice_title  = $this->main_content['notice_heading'];
		$this->notice_content  = $this->main_content['notice_content'];	
	}
	
	public function get_remote_data(){		
		$transient_value = get_transient( $this->cache_key );
		if ( false === $transient_value || $transient_value === '') {
			try {
			
				$response = wp_remote_get( $this->url );
				$body     = wp_remote_retrieve_body( $response );
				$code     = wp_remote_retrieve_response_code($response);
				if($code === 200){
					
					$array_data   =  $transient_value    = json_decode($body, true);
					$update_check_after = isset($array_data[ 'update_check_after' ]) && is_numeric($array_data[ 'update_check_after' ]) ? $array_data[ 'update_check_after' ] * 3600 : 24 * 3600;
					
					// reset old recurrance
					$last_update = get_option($this->last_update_time, false);					
					
					if(is_string( $last_update ) && strlen($last_update) > 5 && $last_update != $array_data['post_modified']){						
						update_option($this->pending_times, $array_data[ 'recur_times' ]);							
					}
					
					if(isset($array_data['post_modified'])){
						update_option($this->last_update_time, $array_data['post_modified']);
					}					
					
					set_transient( $this->cache_key , $array_data , $update_check_after );
				}
			} catch (\Exception $e) {
				delete_transient( $this->cache_key );
			}
		}
		
		return $transient_value;
	}
}

new Wcf_Service_Notice();