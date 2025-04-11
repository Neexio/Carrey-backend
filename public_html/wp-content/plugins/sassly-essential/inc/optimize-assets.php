<?php 

namespace SasslyEssentialApp\Inc;

Class sassly_Optimize_Assets{

 public $directory_path = '';
 public $directory_url = '';

 public function __construct(){
     
    $upload_dir           = wp_upload_dir();
    $this->directory_path = $upload_dir['basedir'].'/wcfcache/css';
    $this->directory_url  = $upload_dir['baseurl'].'/wcfcache/css';
    
    // add_action('elementor/frontend/after_enqueue_styles', [$this,'info_optimize_theme_css'],PHP_INT_MAX);
    // add_action('wp_print_styles', [$this,'info_optimize_theme_css']);    
    add_action('wp_print_styles', [$this,'enqueue_pure_styles'], PHP_INT_MAX);
    
    // Update Cache file
    add_action( 'elementor/editor/after_save', [ $this , 'saved_elementor_data' ] ,20, 1 );
    add_action( 'post_updated', [ $this , 'saved_elementor_data' ] ,20, 1 );
    // contact form 7
    if( sassly_option('ondemand_contact_form_7', true ) ){
        add_filter( 'wpcf7_load_css', '__return_false' );
        add_filter( 'wpcf7_load_js', '__return_false' );    
        add_filter( 'pre_do_shortcode_tag' , [ $this , 'enqueue_wpcf7_css_js_as_needed' ], 10, 2 );
    }
    
    add_filter( 'csf_fa4', '__return_true' );    
    add_action( 'admin_enqueue_scripts' , [$this,'enqueue_scripts']);
    
    if(sassly_option( 'defer_js_and_css' , false) ){
        add_filter( 'script_loader_tag',  [$this,'front_end_enqueue_scripts'],500,3);
        add_filter( 'style_loader_tag', [$this,'defer_styles'], 999, 3 );
    }
   
 }
 
 public function defer_styles($tag, $handle, $src){
    if(
        $handle === 'wcf--brand-slider' || 
        $handle === 'goodshare' || 
        $handle === 'sassly-header-offcanvas' ||
        $handle === 'vidbacking' ||
        $handle === 'magnific-popup' ||
        $handle === 'bootstrap'
        ){
            return str_replace( ' href=', ' defer href=', $tag );
        } 
    return $tag;
 }
 public function front_end_enqueue_scripts($tag, $handle, $src){
  
    if(
    $handle === 'wcf--mailchimp' || 
    $handle === 'goodshare' || 
    $handle === 'wcf-offcanvas-menu' || 
    $handle === 'wcf--tabs' || 
    $handle === 'elementor-waypoints' || 
    $handle === 'popper' || 
    $handle === 'magnific-popup' || 
    $handle === 'bootstrap'
    ){
        return str_replace( ' src=', ' defer src=', $tag );
    }    
    
    return $tag;
 }
  
 public function enqueue_scripts(){    
    if(!defined('SASSLY_CSS')){
       return;
    }
    wp_register_style( 'sassly-custom-icons' , SASSLY_CSS . '/custom-icons.min.css', null, SASSLY_ESSENTIAL_VERSION );
    wp_enqueue_style( 'sassly-custom-icons' );  
 }
 
 public function enqueue_wpcf7_css_js_as_needed($output, $shortcode){
    
    if(!function_exists('wpcf7_enqueue_scripts')){
        return $output;
    }
    
    if(!function_exists('wpcf7_recaptcha_enqueue_scripts')){
        return $output;
    }
 
    if ( 'contact-form-7' == $shortcode ) {
        \wpcf7_recaptcha_enqueue_scripts();
        \wpcf7_enqueue_scripts();
        \wpcf7_enqueue_styles();
    }
    return $output;
 }

 function saved_elementor_data( $post_id ){
   try{
    $url = add_query_arg( array(
        'wcf-cache'     => 'generate',
        'wcf-post-id'   => $post_id
    ), get_the_permalink($post_id));
    $this->info_optimize_theme_css($post_id);  
    }catch(\Exception $e) {}  
}
 
 function info_optimize_theme_css($post_id)
  {
    if(is_user_logged_in()){
        return;
    }
    
    if(!function_exists('sassly_option')){
        return;
    }
    
    if(!sassly_option('optimize_asset_enable')){
        return;
    }
    
    global $wp_styles;
    $prefix              = ['wcf-'];
    $deque               = [];
    $css_content         = '';
    $optimize_minify_css = sassly_option('optimize_asset_enable');
    global $wp_filesystem;
    require_once ( ABSPATH . '/wp-admin/includes/file.php' );
    WP_Filesystem();     
    
    $dir_path = $this->directory_path.'/styles-'.$post_id.'-bundle.css';
   
    sassly_new_directory($this->directory_path);
    foreach( $wp_styles->queue as $style ) {
        if( sassly_has_string_prefix( $style , $prefix ) && isset( $wp_styles->registered[ $style ] ) ){   
          $deque[$style] = $wp_styles->registered[$style]->src;             
        }             
    }
    
    foreach( $wp_styles->registered as $key => $style ) {          
        if( sassly_has_string_prefix( $key , $prefix ) ){                 
            $deque[$key] = $style->src;
        }             
    }
   
    foreach($deque as $path){
        $css_content .= $wp_filesystem->get_contents($path);       
    }
   
    if( $css_content !='' ){
        if($optimize_minify_css && 1==2){
            $wp_filesystem->put_contents($dir_path , sassly_minify_CSS( $css_content ) , 755 );
        }else{
            $wp_filesystem->put_contents($dir_path , $css_content , 755 );
        }            
    }       
    
     
  }
   
  // Load file from Cache directory
  function enqueue_pure_styles() { 
  
    if( is_user_logged_in() ){
        return;
    }
    
    if( !function_exists( 'sassly_option' )){
        return;
    }
    
    if( !sassly_option( 'optimize_asset_enable' ) ){
      return;
    }

    $path    = $this->directory_url.'/styles-'.get_queried_object_id().'-bundle.css';
    $dirpath = $this->directory_path.'/styles-'.get_queried_object_id().'-bundle.css';
   
    if( file_exists( $dirpath ) ){       
        
        global $wp_styles;
        $prefix              = ['wcf-'];
        $deque               = [];
        
        foreach( $wp_styles->queue as $style ) { 
        
            $deque[$style] = $wp_styles->registered[$style]->src;
            
            if( sassly_has_string_prefix( $style , $prefix ) ){   
            
              $deque[$style] = $wp_styles->registered[$style]->src;
              wp_dequeue_style($wp_styles->registered[$style]->handle);              
            } 
            
        }
             
        foreach( $wp_styles->registered as $key => $style ) {  
        
            if( sassly_has_string_prefix( $key , $prefix ) ){   
              unset( $wp_styles->registered[$key] );              
            }  
            
        }
        
        wp_enqueue_style('sassly-styles-'.get_queried_object_id(), $path);
    }
    
  }
  
}

new sassly_Optimize_Assets();

