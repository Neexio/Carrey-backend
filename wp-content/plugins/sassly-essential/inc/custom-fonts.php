<?php 

namespace SasslyEssentialApp\Inc;

Class sassly_Custom_Fonts_Family{
    
    public $elementor_local_font = [];
    public $configs              = [];
    public $font_group_key       = 'sassly-custom';
    public $font_group_label     = 'sassly';
    public $meta_key             = 'wcf_custom_fonts';
    public $post_type            = 'wcf-custom-font';
    
    
    public function __construct(){ 
    
        $this->configs = sassly_get_config_value_by_name('fonts');
        add_filter( 'csf_field_typography_customwebfonts' , [ $this, 'typography_customwebfonts' ] , 12 );
        add_filter( 'elementor/fonts/additional_fonts' , [ $this, 'elementor_additional_fonts' ] , 12 );
        add_filter( 'elementor/fonts/groups' , [ $this, 'elementor_fonts_group' ] , 12 );        
        add_filter( 'sassly_custom_webfonts' , [ $this, 'sassly_custom_webfonts_from_theme' ] , 4 );
        add_filter( 'elementor/document/urls/preview' , [ $this, 'sassly_elementor_editor_url' ] , 4 );
        add_filter( 'elementor/document/urls/wp_preview' , [ $this, 'sassly_elementor_editor_url' ] , 4 );
        add_action( 'elementor/frontend/before_get_builder_content' , [ $this, 'before_get_builder_content' ] , 15 );
        add_action( 'elementor/editor/after_save' , [ $this, 'editor_after_save' ] , 15 , 2 );
        
        // Custom Fonts
        add_action( 'init', [ $this,'custom_post_type' ]);
        add_action( 'admin_menu', [ $this, 'register_sub_menu_post' ] );
       
        add_filter( 'wp_check_filetype_and_ext', [ $this , 'font_correct_filetypes' ] , 10 , 5 );
        add_filter( 'upload_mimes', [$this,'allow_custom_mime_types'] );
    } 
    
    function allow_custom_mime_types( $mimes ) { 
  
        $mimes[ 'ttf' ]   = 'font/ttf';         
        $mimes[ 'otf' ]   = 'font/otf';         
        $mimes[ 'woff' ]  = 'font/woff';         
        $mimes[ 'woff2' ] = 'font/woff2';         
        $mimes[ 'eot' ]   = 'font/eot';        
        
        return $mimes;
    }
    
    function font_correct_filetypes( $data, $file, $filename, $mimes, $real_mime ) {

        if ( ! empty( $data['ext'] ) && ! empty( $data['type'] ) ) {
            return $data;
        }
        
        $wp_file_type = wp_check_filetype( $filename, $mimes );
        
        if ( 'ttf' === $wp_file_type['ext'] ) {
            $data['ext'] = 'ttf';
            $data['type'] = 'font/ttf';
        } 
        
        if ( 'otf' === $wp_file_type['ext'] ) {
            $data['ext'] = 'otf';
            $data['type'] = 'font/otf';
        } 
        
        if ( 'woff' === $wp_file_type['ext'] ) {
            $data['ext'] = 'woff';
            $data['type'] = 'font/woff';
        } 
        
        if ( 'woff2' === $wp_file_type['ext'] ) {
            $data['ext'] = 'woff2';
            $data['type'] = 'font/woff2';
        } 
        
        if ( 'eot' === $wp_file_type['ext'] ) {
            $data['ext'] = 'eot';
            $data['type'] = 'font/eot';
        } 
        
        return $data;
    }
    
    public function register_sub_menu_post() { 
        add_submenu_page( 'wcf-sassly-theme-parent' , 'Custom Fonts' , 'Custom Fonts' , 'manage_options' , "edit.php?post_type=$this->post_type" );      
    }
    
    function custom_post_type(){
  
        register_post_type($this->post_type,
          array(
            'labels'      => array(
              'name'          => __('Custom Fonts', 'SASSLY_ESSENTIAL'),
              'singular_name' => __('Custom Font', 'SASSLY_ESSENTIAL'),
            ),
              'public'              => true,
              'menu_icon'           => 'dashicons-text-page',
              'supports'            => [ 'title'],            
              'exclude_from_search' => false,
              'has_archive'         => false,     
              'publicly_queryable'  => false,     
              'hierarchical'        => false,
              'show_in_menu'        => false,
          )
        );        
       
      }
    
    public function editor_after_save( $id , $editor_data ){  
        try {
            $url = add_query_arg( 'wcf-edit', 1 , get_the_permalink( $id ));       
           // get_headers( $url );
        }catch(\Exception $e) {}      
    } 
    
    public function before_get_builder_content( $document ){ 
    
        $_elementor_data = get_post_meta($document->get_post()->ID,'_elementor_data', true);  
       
        foreach( $this->configs as $font => $val ){
            if( is_string($_elementor_data) && str_contains($_elementor_data,$font) ){
                $this->elementor_local_font[$font] = $font;
            }
        }    
        
        if( is_archive() ){
            update_term_meta(get_queried_object_id() , $this->meta_key , $this->elementor_local_font );
        }else if( is_search() ){
            update_option( $this->meta_key.'_search' , $this->elementor_local_font );
        }else if( is_404() ){
            update_option( $this->meta_key.'_error' , $this->elementor_local_font );
        }else{
            if ( get_queried_object_id() ) {
                update_post_meta( get_queried_object_id() , $this->meta_key , $this->elementor_local_font ); 
            } 
        }
             
    }
    
    public function sassly_elementor_editor_url( $url ){
      return add_query_arg( 'wcf-edit', 1 , $url ); 
    }
    
    function sassly_custom_webfonts_from_theme( $return_fonts ){
           
        $body_font_typho      = sassly_option('opt-tabbed-general');
        // path inc/options/settings/style.php
        $settings_from_option = [
          'body_font_typho',
          'h1_font_typho',
          'h2_font_typho',
          'h3_font_typho',
          'h4_font_typho',
          'h5_font_typho',
          'menu-offcanvas-typography',
          'h6_font_typho'
        ];
      
        foreach( $settings_from_option as $font_option ){
          if(isset($body_font_typho[$font_option]) && isset($body_font_typho[$font_option]['font-family'])){        
            if(isset($this->configs[$body_font_typho[$font_option]['font-family']])){
                $return_fonts[ $body_font_typho[ $font_option ][ 'font-family' ] ] = $this->configs[ $body_font_typho[ $font_option ][ 'font-family' ] ];
            }        
          }
        }     
     
        if(isset($_GET['wcf-edit'])){
            foreach($this->configs as $key => $item){
                $return_fonts[ $key ] = $item;
            }
        }
        
        // Frontend Elementor 
        if(is_archive() || is_tax()){
            $elementor_fonts = get_term_meta(get_queried_object_id(),$this->meta_key,true);
        }else if( is_search() ){
              $elementor_fonts = get_option($this->meta_key.'_search');               
        }else if( is_404() ){
            $elementor_fonts = get_option($this->meta_key.'_error');            
        } else{
            $elementor_fonts = get_post_meta(get_queried_object_id(),$this->meta_key,true);
        }
        
        if(is_array($elementor_fonts)){
          foreach($elementor_fonts as $item){
            $return_fonts[ $item ] = $this->configs[ $item ];
          }
        }
      
        return $return_fonts;
    }
    function elementor_fonts_group($group){
        $group[ $this->font_group_key ] = $this->font_group_label;
        return $group;
    }
    
    function elementor_additional_fonts($fonts){  
        $this->get_custom_font_from_user();
        foreach( $this->configs as $font => $value ){
            $fonts[ $font ] = $this->font_group_key; 
        }
        
       return $fonts;
    }
    
    function typography_customwebfonts( $value ){
       
         $this->get_custom_font_from_user();
        
        foreach( $this->configs as $font => $val ) {    
            if(is_array($val)){
                $value[ $font ] = wp_list_pluck( $val , 'weight' );   
            }                     
        }      
    
    
        return $value;
    }
    
    public function get_custom_font_from_user(){
    
        $args = array(
            'numberposts' => 15,
            'post_status' => 'publish',
            'post_type'   => $this->post_type
        );
          
        $latest_posts = get_posts( $args );
       
        if(!is_array($latest_posts)){
            return [];
        }
       
        if(empty($latest_posts)){
            return [];
        }
 
        $arr = [];
        
        foreach($latest_posts as $item){
           
            $variation = get_post_meta( $item->ID , 'sassly_custom_fonts_options', true);
            
            if(is_array($variation) && isset($variation['wcf_font_variation'])){
              $variation = $variation['wcf_font_variation'];
              
              foreach($variation as $font){
              
                if($font['font_weight'] !== ''){
                
                    if(isset($font['ttf_file']) && $font['ttf_file'] !=''){
                        $arr[$item->post_title][] = [
                            'weight' => $font['font_weight'],
                            'src' => $font['ttf_file']
                        ];
                    }
                
                    if(isset($font['eot_file']) && $font['eot_file'] !=''){
                        $arr[$item->post_title][] = [
                            'weight' => $font['font_weight'],
                            'src' => $font['eot_file']
                        ];
                    }
                    
                    if(isset($font['woff2_file']) && $font['woff2_file'] !=''){
                        $arr[$item->post_title][] = [
                            'weight' => $font['font_weight'],
                            'src' => $font['woff2_file']
                        ];
                    }
                
                    if(isset($font['woff_file']) && $font['woff_file'] !=''){
                        $arr[$item->post_title][] = [
                            'weight' => $font['font_weight'],
                            'src' => $font['woff_file']
                        ];
                    }
                    
                }
              }  
              
            }         
          
        }
        
        if( is_array($arr) ){
            $this->configs = array_merge($this->configs, $arr);
        }        
       
        return $arr;
    }
 }
 new sassly_Custom_Fonts_Family();

