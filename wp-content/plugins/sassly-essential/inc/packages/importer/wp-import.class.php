<?php
namespace SasslyEssentialApp\Importer;

global $pagenow;
if( $pagenow !='import.php' ){
class Wcf_Page_Import extends \WCF_WP_Import
    {
         public $lib_path    = SASSLY_ESSENTIAL_DEMO_PAGE_BASE_PATH.'wcf-page-%s-xml-library.json';
         public $import_path = SASSLY_ESSENTIAL_DEMO_PAGE_BASE_PATH.'wcf-page-tpl-%s.xml';
         public $xml_path    = null;
         public $upload_path = null;
         public $upload_settings_path = null;
         public $cache_name = 'crowdytheme_pages_templates_file_binox';
    	/**
    	 * register default hooks and actions for WordPress
    	 * @return
    	 */
    	public function __construct()
    	{
            if(!sassly_theme_service_pass()){
                return;
            }
            $this->lib_path = sprintf($this->lib_path, SASSLY_TPL_SLUG);        
            add_action( 'admin_enqueue_scripts',  array( $this, 'admin_enqueue_scripts' )  );
            add_action( 'manage_posts_extra_tablenav', [ $this , 'top_form_edit' ] );
            add_action( 'admin_footer' , [ $this,'_admin_footer']);
            add_action( 'wp_ajax_wcf_page_xml_file_import' , [$this,'wcf_page_xml_file_import'] );
     	}
      	
     	public function wcf_page_xml_file_import(){
     	    // Ajax
            if ( !wp_verify_nonce( $_REQUEST['nonce'], "wcf_page_import_secure")) {
                exit("No naughty business please");
            }
            
            $upload_dir = wp_upload_dir();
            $this->upload_path = $upload_dir['path'].'/wcf-page-tpl-temp.xml';           
            $this->upload_settings_path = $upload_dir['path'].'/wcf-wp-options-tpl-temp.xml';           
            
            $return_obj = [
                'action' => 'wcf_page_xml_file_import',
                'nonce'  => $_REQUEST['nonce'],
                'id'     => $_REQUEST['id'],
                'step'   => '',
                'html' => '',
                
            ];
            
            $id   = sanitize_text_field( isset($_POST['id']) ? $_POST['id'] : '');
            $path = sprintf($this->import_path,$id);
             // download step
            if( isset( $_REQUEST[ 'step' ] ) && $_REQUEST[ 'step' ] === 'download' ){
                ob_start();
                if( $this->downlolad_and_set_temp_file($path) ) {
                    $return_obj['step'] = 'import';
                    echo sprintf('<div class="wcf-page-imporing">Page: %s downloaded. Starting the page install</div>',$_REQUEST['page_title']);
                }else{
                    $return_obj['step'] = 'failed';
                    echo sprintf('<div class="wcf-page-imporing">File: %s download failed . <br/> May be file not exist or server busy . </br/>Try few miniute later.</div>',$_REQUEST['page_title']);
                }               
                $return_obj['html'] = ob_get_clean();
                wp_send_json_success($return_obj);
            }elseif(isset( $_REQUEST[ 'step' ] ) && $_REQUEST[ 'step' ] === 'import'){
                ob_start();
                $return_obj['step'] = 'done';
                $this->import($this->upload_path);                
                $return_obj['html'] = ob_get_clean();
                wp_send_json_success($return_obj);
            }
            ob_start();
            $return_obj['step'] = 'failed';
            $return_obj['action'] = 'null';
            echo '<p><strong>' . __( 'Sorry, there has been an error.', 'arolax-essential' ) . '</strong><br />';
            $return_obj['html'] = ob_get_clean();
            wp_send_json_error($return_obj);
            wp_die();
     	}
     	/*
     	* Downnload remote file
     	*/
     	public function downlolad_and_set_temp_file($path){
     	
            if (!defined('FS_METHOD')) {
                define('FS_METHOD', 'direct');
            }
            $response = wp_remote_get(
            $path,
            [
                'timeout'   => 180,
                'sslverify' => false
            ]
            );
            $content  = ['status'=> 403];
            if( is_wp_error( $response ) ) {
               return false;                 
            } 
            $content = wp_remote_retrieve_body( $response );             
            $path = wp_unslash($content);
            global $wp_filesystem;
            require_once ( ABSPATH . '/wp-admin/includes/file.php' );
            WP_Filesystem();         
         
            $wp_filesystem->put_contents($this->upload_path,'');            
            $wp_filesystem->put_contents($this->upload_path,$content, 644);  
            chmod($this->upload_path, 0777);
            if(file_exists($this->upload_path)){
             return true;
            }
            return false;
     	}
     	
     	/*
     	* Downnload remote file
     	*/
     	public function downlolad_settings_and_set_temp_file($path){
            $response = wp_remote_get(
            $path,
            [
                'timeout'   => 180,
                'sslverify' => false
            ]
            );
            $content  = ['status'=> 403];
            if( is_wp_error( $response ) ) {
               return false;                 
            } 
            $content = wp_remote_retrieve_body( $response );             
            $path = wp_unslash($content);
            global $wp_filesystem;
            require_once ( ABSPATH . '/wp-admin/includes/file.php' );
            WP_Filesystem();         
         
            $wp_filesystem->put_contents($this->upload_settings_path,'');            
            $wp_filesystem->put_contents($this->upload_settings_path,$content, 644);  
            chmod($this->upload_settings_path, 0777);
            if(file_exists($this->upload_settings_path)){
             return true;
            }
            return false;
     	}
     	
        public function admin_enqueue_scripts(){    
    
            wp_register_style( 'wcf-page-importer-admin', plugin_dir_url( __FILE__ ).'wordpress-importer/assets/admin.css', [], time() );            
            wp_register_script(
                'wcf-page-importer-admin',
                plugin_dir_url( __FILE__ ).'wordpress-importer/assets/admin.js',			
                ['jquery'],			
                time(),
                true
            );            
            $arolax_data =[
                'ajax_url'   => admin_url( 'admin-ajax.php' ),
                'ajax_nonce' => wp_create_nonce('wcf_page_import_secure'),
                'pages'      => $this->get_data()
            ];           
            wp_localize_script( 'wcf-page-importer-admin', 'wcf_import_obj', $arolax_data);           
            $current_screen = get_current_screen();
            if(isset($current_screen->id) && $current_screen->id === 'edit-page'){
                wp_enqueue_style('wcf-page-importer-admin');
                wp_enqueue_script('wcf-page-importer-admin');
            }
            
        }
     	
         function _admin_footer() {
            $current_screen = get_current_screen();
            if(isset($current_screen->id) && $current_screen->id === 'edit-page'){
                echo sprintf('
                       <!-- The Modal -->
                       <div id="wcf-page-importeri" class="wcf-page-modal">                   
                         <!-- Modal content -->
                         <div class="wcf-page-modal-content">
                           <span class="wcf-page-close">&times;</span>
                           <h3>%s</h3>
                           <div class="wcf-page-filter-area">
                                <div class="wcf-page-subtype" id="wcf-page-select-type">
                                    
                                </div>    
                                <input type="text" placeholder="Search here" class="wcf-page-search-js" />
                           </div>
                           <div id="wcf--page-dimporter--content-js">
                                <div class="wcf-dpage-xml-import-container">
                                    <div class="wcf-msg"></div> 
                                    <div class="wcf-templates-list-renderer"> </div>                                
                                </div>
                           </div>
                         </div>                   
                       </div>',
                    esc_html__('Crowdytheme Page Templates', 'arolax-essential'),        
                );
            }
        }
        
        function get_data() {
                $transient = get_transient( $this->cache_name );          
            if( ! empty( $transient ) ) {             
                  return $transient;        
            } else {
                         
                  // Call the API.
                $response = wp_remote_get( $this->lib_path, [
                    'timeout'   => 180,
                    'sslverify' => false
                ] );      
                
                if( !is_wp_error( $response ) ) {
                    $content = trim( wp_remote_retrieve_body( $response ) );
                    set_transient( $this->cache_name , $content , DAY_IN_SECONDS ); 
                    return $content;      
                }                           
                        
                return null;        
            }
        }
        function top_form_edit( ) {
        
            static $single_load = null;
            
            if(isset($_GET['post_type']) && $_GET['post_type'] == 'page' && is_null($single_load)){
              $single_load = 1; 
              ?>
               <script>  
               
                  var wcf_button_add = document.querySelector('.page-title-action');
                  var wcf_new_button = document.createElement("a");
                  wcf_new_button.innerHTML = 'Import Page';
                  wcf_new_button.classList = 'add-new-h2 wcf-page-default-import-modal';
                  wcf_new_button.setAttribute('href', 'javascript:void(0)');
                  wcf_new_button.setAttribute('data-id', 'wcf_wp_page');
                  
                  wcf_new_button.onclick = function(e){
                    jQuery('#wcf-page-importeri').show();
                    jQuery("#wcf-page-importeri").trigger("wcf:pagemodel:open");
                  };
                  if(wcf_button_add){             
                    wcf_button_add.after(wcf_new_button);
                  }
                  jQuery(document).on('click', ".wcf-page-modal-content .wcf-page-close" ,function(){                  
                    jQuery('#wcf-page-importeri').hide();
                    jQuery("#wcf-page-importeri").trigger("wcf:pagemodel:close");
                  });
               </script>         
              <?php 
      
            }
            
        }    	
    
}

new Wcf_Page_Import();
}






