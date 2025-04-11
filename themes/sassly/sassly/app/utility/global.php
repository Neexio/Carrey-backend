<?php 

/* post view count 
* function to display number of posts.
*/
function sassly_get_postview($postID){

   $count_key = 'sassly_post_views_count';
   $count     = get_post_meta($postID, $count_key, true);
   if($count==''){
       return "0";
   }
   return $count;
}

function sassly_google_fonts_url($font_families	 = []) {
	$fonts_url		 = '';
	/*
    Translators: If there are characters in your language that are not supported
    by chosen font(s), translate this to 'off'. Do not translate into your own language.
    */
	if ( $font_families && 'off' !== _x( 'on', 'Google font: on or off', 'sassly' ) ) { 
		$query_args = array(
			'family' => urlencode( implode( '|', $font_families ) )
		);

		$fonts_url = add_query_arg( $query_args, 'https://fonts.googleapis.com/css' );
	}
   
	return esc_url_raw( $fonts_url );
}

// function to count views.
function sassly_set_postview($postID=null) {

   if( is_null($postID) ){
      $postID = get_the_ID();
   }   

   $count_key = 'sassly_post_views_count';
   $count     = get_post_meta($postID, $count_key, true);

   if( $count=='' ){
       $count = 0;
       delete_post_meta( $postID, $count_key );
       add_post_meta( $postID, $count_key, '0' );  
      }else{  
         $count++;
         update_post_meta($postID, $count_key, $count);         
   }

}

/* return the specific value from theme options  */
if(!function_exists('sassly_option')){
   function sassly_option( $option = '', $default = '' , $parent = 'sassly_settings') {
      
      
      $options = get_option( $parent );

      return ( isset( $options[$option] ) ) ? $options[$option] : $default;
      
   }
} 

// return the specific value from metabox
// ----------------------------------------------------------------------------------------
if(!function_exists('sassly_meta_option')){

   function sassly_meta_option( $postid, $key, $default_value = '', $parent_key = 'sassly_post_options' ) {
      
      $post_key = $parent_key;
      // page meta
      if(get_post_type() == 'page' ){
         $post_key = 'sassly_page_options';
      }
       // post meta
      if(get_post_type() == 'post'){
         $post_key = 'sassly_post_options';
      }
    
     
      $options = get_post_meta( get_the_ID(), $post_key, true );
      return ( isset( $options[$key] ) ) ? $options[$key] : $default_value;          
      
   }
   
}


// WP kses allowed tags
// ----------------------------------------------------------------------------------------
function sassly_kses( $raw ) {

	$allowed_tags = array(
		'a'								 => array(
			'class'	 => array(),
			'href'	 => array(),
			'rel'	 => array(),
			'title'	 => array(),
			'target'	 => array(),
      ),
      'option' => array(
         'value'	 => array(),
		
      ),
		'abbr'							 => array(
			'title' => array(),
		),
		'b'								 => array(),
		'blockquote'					 => array(
			'cite' => array(),
		),
		'cite'							 => array(
			'title' => array(),
		),
		'code'							 => array(),
		'del'							 => array(
			'datetime'	 => array(),
			'title'		 => array(),
		),
		'dd'							 => array(),
		'div'							 => array(
			'class'	 => array(),
			'title'	 => array(),
			'style'	 => array(),
		),
		'dl'							 => array(),
		'dt'							 => array(),
		'em'							 => array(),
		'h1'							 => array(),
		'h2'							 => array(),
		'h3'							 => array(),
		'h4'							 => array(),
		'h5'							 => array(),
		'h6'							 => array(),
		'i'								 => array(
			'class' => array(),
		),
		'img'							 => array(
			'alt'	 => array(),
			'class'	 => array(),
			'height' => array(),
			'src'	 => array(),
			'width'	 => array(),
		),
		'li'							 => array(
			'class' => array(),
		),
		'ol'							 => array(
			'class' => array(),
		),
		'p'								 => array(
			'class' => array(),
		),
		'q'								 => array(
			'cite'	 => array(),
			'title'	 => array(),
		),
		'span'							 => array(
			'class'	 => array(),
			'title'	 => array(),
			'style'	 => array(),
		),
		'iframe'						 => array(
			'width'			 => array(),
			'height'		 => array(),
			'scrolling'		 => array(),
			'frameborder'	 => array(),
			'allow'			 => array(),
			'src'			 => array(),
		),
		'strike'						 => array(),
		'br'							 => array(),
		'strong'						 => array(),
		'data-wow-duration'				 => array(),
		'data-wow-delay'				 => array(),
		'data-wallpaper-options'		 => array(),
		'data-stellar-background-ratio'	 => array(),
		'ul'							 => array(
			'class' => array(),
		),
	);

	if ( function_exists( 'wp_kses' ) ) { // WP is here
		$allowed = wp_kses( $raw, $allowed_tags );
	} else {
		$allowed = $raw;
	}

	return $allowed;
}


function sassly_get_excerpt($count = 100 ) {
 
   $count = sassly_desc_limit($count);  
   $sassly_blog_read_more_text = esc_html__('Readmore','sassly');
   
   $excerpt = get_the_excerpt();
   $excerpt = esc_html($excerpt);
   $words   = str_word_count($excerpt, 2);
   $pos     = array_keys($words);

   if(count($words)>$count){
      $excerpt = substr($excerpt, 0, $pos[$count]); 
   }
 
   $excerpt = wp_kses_post($excerpt); 
   return $excerpt;
}

 function sassly_desc_limit($default){

      if(!is_single() && !is_page()) {
        
         if(sassly_option('sassly_categry_post_desc_lenght') ){
            return sassly_option('sassly_categry_post_desc_lenght');
         }else{
            return $default;
         }
         
      }
      
      return $default;
   }


 function sassly_src( $key, $default_value = '', $input_as_attachment = false ) { // for src
   
   if ( $input_as_attachment == true ) {
		$attachment = $key;
	} else {
      $attachment = sassly_option( $key );
     
	}

	if ( isset( $attachment[ 'url' ] ) && !empty( $attachment ) ) {
		return $attachment[ 'url' ];
	}

	return $default_value;
}

if(!function_exists('sassly_get_post_types')) {

   function sassly_get_post_types() {
      global $wp_post_types;
      $posts = array();
    
      foreach ($wp_post_types as $post_type) {
         $skip_posts_type = [
            'post',
            'page',        
            'custom_css',
            'wp_navigation',
            'wp_global_styles',
            'wp_template_part',
            'wp_template',
            'wp_block',
            'user_request',
            'oembed_cache',
            'customize_changeset',
            'revision',
            'attachment',
            'elementor_library'
         ]; 
         
         if(!in_array($post_type->name,$skip_posts_type)){
            $posts[$post_type->name] = $post_type->labels->singular_name;
         }
        
      }
      return $posts;
   }
   
}

if(!function_exists('sassly_get_all_custom_taxonomies')){
   
   function sassly_get_all_custom_taxonomies(){
      global $wp_taxonomies;
	   $taxonomies = array();
      foreach ($wp_taxonomies as $key => $cat_type) {
         $taxonomies[$key] = $cat_type->label; 
      }
      
      return $taxonomies;
   }
}

if(!function_exists('sassly_get_cache_post_types')) {
   
   function sassly_get_cache_post_types() {
      $data = get_option('sassly_get_post_types_cache');
       return $data ? $data : [];
   }

}

if(!function_exists('sassly_get_cache_tax_types')) {
   
   function sassly_get_cache_tax_types() {
       $data = get_option('sassly_get_all_custom_taxonomies_cache');
       return $data ? $data : [];
   }

}


if(!function_exists('sassly_social_share_list')){

   function sassly_social_share_list(){
   
      $data = array(
         ''              => '---',
         'facebook'      => esc_html__('Facebook', 'sassly'),
         'twitter'       => esc_html__('twitter', 'sassly'),
         'linkedin'      => esc_html__('linkedin', 'sassly'),
         'pinterest'     => esc_html__('pinterest ', 'sassly'),
         'digg'          => esc_html__('digg', 'sassly'),
         'tumblr'        => esc_html__('tumblr', 'sassly'),
         'blogger'       => esc_html__('blogger', 'sassly'),
         'reddit'        => esc_html__('reddit', 'sassly'),
         'delicious'     => esc_html__('delicious', 'sassly'),
         'flipboard'     => esc_html__('flipboard', 'sassly'),
         'vkontakte'     => esc_html__('vkontakte', 'sassly'),
         'odnoklassniki' => esc_html__('odnoklassniki', 'sassly'),
         'moimir'        => esc_html__('moimir', 'sassly'),
         'livejournal'   => esc_html__('livejournal', 'sassly'),
         'blogger'       => esc_html__('blogger', 'sassly'),
         'evernote'      => esc_html__('evernote', 'sassly'),
         'flipboard'     => esc_html__('flipboard', 'sassly'),
         'mix'           => esc_html__('mix', 'sassly'),
         'meneame'       => esc_html__('meneame ', 'sassly'),
         'pocket'        => esc_html__('pocket ', 'sassly'),
         'surfingbird'   => esc_html__('surfingbird ', 'sassly'),
         'liveinternet'  => esc_html__('liveinternet ', 'sassly'),
         'buffer'        => esc_html__('buffer ', 'sassly'),
         'instapaper'    => esc_html__('instapaper ', 'sassly'),
         'xing'          => esc_html__('xing ', 'sassly'),
         'wordpres'      => esc_html__('wordpres ', 'sassly'),
         'baidu'         => esc_html__('baidu ', 'sassly'),
         'renren'        => esc_html__('renren ', 'sassly'),
         'weibo'         => esc_html__('weibo ', 'sassly'),        
      );
   
      return $data;
   }
   
}

if( !function_exists( 'sassly_text_logo' ) ) {

	function sassly_text_logo(){
		
		$general_text_logo = sassly_option('general_text_logo',0);
		
		if($general_text_logo == '1' ){
         if ( ! class_exists( 'CSF' ) ) {
             return esc_html__( 'Blog', 'sassly' );
         }
         $general_blog_title = sassly_option('general_blog_title');
         return $general_blog_title;
      }
		
		return false;
    }
    
}


if( !function_exists('sassly_get_fb_share_count') ){

   function sassly_get_fb_share_count($post_id = null){
      
      $cache_key    = 'sassly_fb_share_' . $post_id;
      $url          = get_permalink( $post_id );
      $access_token = sassly_get_fb_secret_key();
     
      $api_url      = 'https://graph.facebook.com/v3.0/?id=' . urlencode( $url ) . '&fields=engagement&access_token=' . $access_token;
      $json_return  = wp_remote_get( $api_url );
      $responseBody = wp_remote_retrieve_body( $json_return );
      $result       = json_decode( $responseBody );
     
      if ( is_object( $result ) && ! is_wp_error( $result ) ) {
         
         if(isset($result->engagement)){
            $fb_share = $result->engagement;
            if(isset($fb_share->share_count)){
               return $fb_share;
            }
         }   
       
      }

      return false;
      
   }

}

// get facebook api key
function sassly_get_fb_secret_key(){

   $facebook_api  = sassly_option('facebook_api');
  
   if( isset($facebook_api['app_id']) && isset($facebook_api['secret_code']) ){
     if($facebook_api['app_id'] !='' && $facebook_api['secret_code'] !=''){
        return $facebook_api['app_id'].'|'.$facebook_api['secret_code'];
     } 
   }
   // default key
   return '3190052791219248|8604c5a80339a8db79877944e852227b';
}


function sassly_lessThanfewMonth($date,$valid = 30) {
   $earlier = new DateTime($date);
   $later   = new DateTime();
   return $later->diff($earlier)->format("%a") > 30?esc_html__('Old Writter','sassly'):esc_html__('New Writter','sassly');
  
}


function sassly_is_footer_widget_active(){

   $footer_widget = false;

    if( 
        is_active_sidebar('footer-one') 
       || is_active_sidebar('footer-two') 
       || is_active_sidebar('footer-three') 
       || is_active_sidebar('footer-four') 
   
      ){
         $footer_widget = true;  
       }else{
         $footer_widget = false;
      }  
    
   return $footer_widget;    
}



// ad allowed pages
if(!function_exists('sassly_footer_allowed_pages')){

   function sassly_footer_allowed_pages($option=null){
      // show in all over blog
      if(is_null($option)){
         return true;
      }
      //filter
      $current_option = []; 
      if(is_category()){
         $current_option[]= 'category'; 
      }

     if(is_tag()){
      $current_option[]= 'tags'; 
     }

     if(is_archive()){
      $current_option[]= 'archive'; 
     }

     if(is_singular('post')){
      $current_option[]= 'post'; 
     }

     if(is_author()){
      $current_option[]= 'author'; 
     }
     
     if(is_search()){
      $current_option[]= 'search'; 
     }
     
     if(is_404()){
      $current_option[]= '404'; 
     }
    
     
     if(is_singular('page')){
      
       $current_option[]= 'page';  
     }

     if(is_main_query()){
         $page_for_posts = get_option( 'page_for_posts' );
         if(get_queried_object_id() == $page_for_posts){
            $current_option[]= 'blog'; 
         }
     }
    
     $found = array_intersect($option, $current_option);
    
     if(is_array($found) && count($found)){
        return true; 
     }   
     return false;

   } 
   
}

if( !function_exists( 'sassly_get_dir_file_list' ) ){

   function sassly_get_dir_file_list($dir = 'dir',$ext = 'php'){
   
      if( ! is_dir($dir) ){
         return [];
      }      
      $files = [];      
      foreach (glob("$dir/*.$ext") as $filename) {
         $files[basename( dirname($filename) ) .'-'. basename($filename,'.'.$ext)] = $filename;
      }   
      return $files;   
   }
   
}


