<?php

/**
 * Helpers methods
 * List all your static functions you wish to use globally on your theme
 */

if ( ! function_exists( 'sassly_header_style' ) ) :
	function sassly_header_style() {
		$header_text_color = get_header_textcolor();
	
		if ( get_theme_support( 'custom-header', 'default-text-color' ) === $header_text_color ) {
			return;
		}
   }
endif;

if ( ! function_exists( 'sassly_starts_with' ) ) {
	/**
	 * Determine if a given string starts with a given substring.
	 *
	 * @param  string  $haystack
	 * @param  string|array  $needles
	 * @return bool
	 */
	function sassly_starts_with($haystack, $needles)
	{
		foreach ((array) $needles as $needle) {
			if ($needle != '' && substr($haystack, 0, strlen($needle)) === (string) $needle) {
				return true;
			}
		}
		return false;
	}
}

/*-------------------------------
    DAY LINK TO ARCHIVE PAGE
---------------------------------*/
if ( !function_exists('sassly_day_link') ) {
   /**
    * sassly_day_link() archive link
    * @return string return sting url for post.
    */
    function sassly_day_link() {
        $archive_year   = get_the_time('Y');
        $archive_month  = get_the_time('m');
        $archive_day    = get_the_time('d');
        echo get_day_link( $archive_year, $archive_month, $archive_day);
    }
}

if(!function_exists('sassly_get_post_category')){
   function sassly_get_post_category($tax = 'category') {
   
      static $list = [];
      if( !count( $list ) ) {
         $categories = get_terms( $tax, array(
            'orderby'    => 'name', 
            'order'      => 'DESC',
            'hide_empty' => false,
            'number'     => 200
         ));
   
        foreach( $categories as $category ) {
           $list[$category->term_id] = $category->name;
        }
      }
    
      return $list;
   }
}

if(!function_exists('sassly_get_post_tags')){

   function sassly_get_post_tags($tax = 'post_tag') {
      
      static $list = [];
      if( !count( $list ) ) {
         $categories = get_terms( $tax, array(
            'orderby'       => 'name', 
            'order'         => 'DESC',
            'hide_empty'    => false,
            'number'        => 200
         
      ) );

      foreach( $categories as $category ) {
         $list[$category->term_id] = $category->name;
      }
      }
   
      return $list;
   }
}


function sassly_child_category_meta(){ 
   $post_child_cat = sassly_option('blog_child_cat_show',1);
   if( $post_child_cat ){
      return;
   }

   $sassly_cat_term     = get_queried_object();
   $sassly_cat_children = get_terms( $sassly_cat_term->taxonomy, array(
       'parent'     => $sassly_cat_term->term_id,
       'hide_empty' => false
   ) );

   if(!$sassly_cat_children){
     return;
   }

   if ( $sassly_cat_children ) { 

      echo '<div class="sub-category-list">';
         foreach( $sassly_cat_children as $sassly_subcat )
         {
            echo '<a class="post-cat" href="'. esc_url(get_term_link($sassly_subcat, esc_html($sassly_subcat->taxonomy))) .'" >'.
            esc_html($sassly_subcat->name). 
               '</a>';
         }
      echo '</div>';

   }

}

function sassly_category_meta(){
       
   $blog_cat_show   = sassly_option('blog_category','yes');
   $blog_cat_single = sassly_option('blog_category_single','no'); 
    
   if( $blog_cat_show != 'yes' ){
     return;
   }
  
   echo '<span class="category">';  

      $cat = get_the_category();
      if( $blog_cat_single == 'yes' ) {
         
         shuffle($cat);

         if ( isset($cat[0]) ) {

            echo  '<a 
                     class="post-cat" 
                     href="'. esc_url(get_category_link($cat[0]->term_id) ).'"
                     
                     >'.'<span class="before"></span>'.
                     
                     esc_html(get_cat_name($cat[0]->term_id)).
                     '<span class="after"></span> '. 
                  '</a>';

         }

         return; 
      }

      if( $cat ) {
         
         foreach( $cat as $value ):
            echo  '<a 
                     class="post-cat" 
                     href="'. esc_url (get_category_link($value->term_id) ) .'"
                     >'. 
                       esc_html(get_cat_name($value->term_id)).
                     '</a>';     
         endforeach;   
         
      }
   echo '</span>';
   
}

function sassly_single_category_meta(){
       
   $blog_cat_show   = sassly_option('blog_single_category','yes');
   $blog_cat_single = sassly_option('blog_category_single','no');
  
    
    if( $blog_cat_show != 'yes' ){
     return;
    }
  
      echo '<div class="page_category">';  

         $cat = get_the_category();
         if( $blog_cat_single == 'yes' ) {
            
            shuffle($cat);

            if ( isset($cat[0]) ) {

               echo  '<a 
                        class="post-cat" 
                        href="'. esc_url(get_category_link($cat[0]->term_id) ).'"
                        
                        >'.'<span class="before"></span>'.
                        
                        esc_html(get_cat_name($cat[0]->term_id)).
                        '<span class="after"></span> '. 
                     '</a>';

            }

            return; 
         }

         if( $cat ) {
            
                  foreach( $cat as $value ):

                     echo  '<a 
                              class="post-cat" 
                              href="'. esc_url (get_category_link($value->term_id) ) .'"
                              >'. 
                                esc_html(get_cat_name($value->term_id)).
                              '</a>';
              
                  endforeach;   
            
         }
      echo '</div>';
   
}

/*-----------------------------
	Info RANDOM SINGLE CATEGORY
------------------------------*/
if ( !function_exists( 'sassly_get_random_category' ) ): 

	function sassly_get_random_category(){

		$blog_cat_show   = sassly_option( 'blog_category', '1' );
		$single          = sassly_option( 'blog_category_single','0');
		$category_html = false;
		if( ! $blog_cat_show ){
          return false;
		}

		if ( 'post' === get_post_type() ) {

			$category        = get_the_category();
			$cat_count       = count($category);
			$single_cat      = $category[random_int( 0, $cat_count-1 )];

			if( !get_the_category() ){
               return false;
			}
		      
			foreach( $category as $value ):
				
				 $category_html = '<a 
							class="jpost-cat" 
							href="'. esc_url(get_category_link($value->term_id) ) .'"
							>'. 
							esc_html(get_cat_name($value->term_id)).
						'</a>';
				if ($single) {
					break;
				}	
					
			endforeach;  
			
		}
		
		return $category_html;
	}

endif;

/*------------------------------------------------------
   DISPLAY META INFORMATION FOR A SPECIFIC POST
-------------------------------------------------------*/
if ( ! function_exists( 'sassly_post_meta_2' ) ) :
   // post and post meta
  function sassly_post_meta_2() {
  
      $post_meta = [];
      $category = sassly_get_random_category();
      
      if ( get_post_type() === 'post' && sassly_option('blog_date','1') ) {
         $post_meta[] = get_the_date(get_option( 'date_format' ));
      }
      
      if($category){
         $post_meta[] = $category; 
      }
      
      if( sassly_option('blog_comment',0) ){ 
         $comments_number = get_comments_number();
         $post_meta[] = $comments_number > 1 ? sprintf(esc_html__('%s comments','sassly'),$comments_number) : sprintf(esc_html__('%s comment','sassly'),$comments_number);
      }
      
      if(is_array($post_meta) && !count($post_meta)){
        return; 
      }
      $meta_cls = is_single() ? 'mb-30 d-block' : '';
   ?>
      <i class="default-blog__item-meta <?php echo esc_attr($meta_cls); ?>">
         <?php 
            echo sassly_kses( implode('<span></span>',$post_meta) );            
         ?>
      </i>
   <?php }
 endif;  

/*------------------------------------------------------
   DISPLAY META INFORMATION FOR A SPECIFIC POST
-------------------------------------------------------*/
if ( ! function_exists( 'sassly_post_meta' ) ) :
   // post and post meta
  function sassly_post_meta() {
  
      $post_meta = [];
      $category = sassly_get_random_category();
      
      if( sassly_option('blog_author',0) ):
        $_posts_url = esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) );
        $get_author = get_the_author();
         if( sassly_option('blog_author_image',0) ):
            $avatar = get_avatar( get_the_author_meta( 'ID' ), 55 );
            $post_meta[] = "<a href='{$_posts_url}'>{$avatar} {$get_author}</a>";
         else:
            $post_meta[] = esc_html__('by','sassly')."<strong><a href='{$_posts_url}'>&nbsp;{$get_author}</a></strong>";
         endif;
      endif;
      
      if ( get_post_type() === 'post' && sassly_option('blog_date','1') ) {
         $post_meta[] = get_the_date(get_option( 'date_format' ));
      }
      
      if($category){
         $post_meta[] = $category; 
      }
      
      if( sassly_option('blog_comment',0) ){ 
         $comments_number = get_comments_number();
         $post_meta[] = $comments_number > 1 ? sprintf(esc_html__('%s comments','sassly'),$comments_number) : sprintf(esc_html__('%s comment','sassly'),$comments_number);
      }
      
      if(is_array($post_meta) && !count($post_meta)){
        return; 
      }
      $meta_cls = is_single() ? 'mb-30 d-block' : '';
   ?>
      <i class="default-blog__item-meta <?php echo esc_attr($meta_cls); ?>">
         <?php 
            echo sassly_kses( implode('<span></span>',$post_meta) );            
         ?>
      </i>
   <?php }
 endif;  

if ( !function_exists('sassly_link_pages') ):

   function sassly_link_pages() {

      $args = array(
         'before'			    => '<div class="page-links"><span class="page-link-text">' . esc_html__( 'More pages: ', 'sassly' ) . '</span>',
         'after'				 => '</div>',
         'link_before'		 => '<span class="page-link">',
         'link_after'		 => '</span>',
         'next_or_number'	 => 'number',
         'separator'			 => '  ',
         'nextpagelink'		 => esc_html__( 'Next ', 'sassly' ) . '<i class="icon-wcf-angle-right"></i>',
         'previouspagelink' => '<i class="icon-wcf-angle-left"></i>' . esc_html__( ' Previous', 'sassly' ),
      );
      
      wp_link_pages( $args );
   }

endif;

function sassly_title_limit($title, $limit=20){
      $title  =  wp_trim_words($title,$limit,'');
      echo esc_html($title);
}

/*----------------------------------------
   CUSTOM COMMENNS WALKER
-------------------------------------------*/
if ( !function_exists('sassly_comment_style') ):

   function sassly_comment_style( $comment, $args, $depth ) {
      if ( 'div' === $args[ 'style' ] ) {
         $tag		 = 'div';
         $add_below	 = 'comment';
      } else {
         $tag		 = 'li ';
         $add_below	 = 'div-comment';
      }
      ?>
     
      <<?php
      echo sassly_kses( $tag );
      comment_class( empty( $args[ 'has_children' ] ) ? 'no-reply' : 'parent has-reply'  );
      ?> id="comment-<?php comment_ID() ?>"><?php if ( 'div' != $args[ 'style' ] ) { ?>
         <div id="div-comment-<?php comment_ID() ?>" class="comment-body"><?php }
      ?>	
        
         <div class="default-details-comment-wrapper mb-50">
            <div class="default-details-comment-thumb">
               <?php
                  if ( $args[ 'avatar_size' ] != 0 ) {
                     echo get_avatar( $comment, $args[ 'avatar_size' ], '', '', array( 'class' => 'comment-avatar float-left' ) );
                  }
               ?>
            </div>
            <div class="default-details-comment-meta">
              <h3 class="default-details-comment-name">
                  <?php
                     echo get_comment_author_link();
                  ?>
              </h3>
              <p class="default-details-comment-date">
                 <?php
                    echo get_comment_date() .'<span></span>' . get_comment_time();
                  ?>
              </p>
              <div class="sassly-comment-text"><?php comment_text(); ?></div>
              <?php
                  comment_reply_link(
                  array_merge(
                  $args, array(
                     'add_below'	 => $add_below,
                     'depth'		 => $depth,
                     'max_depth'	 => $args[ 'max_depth' ]
                  ) ) );
               ?>
               <?php if ( $comment->comment_approved == '0' ) { ?>
               <p class="comment-awaiting-moderation"><?php esc_html_e( 'Your comment is awaiting moderation.', 'sassly' ); ?></p><br/><?php }
               ?>
            </div>
           
         </div>
         <?php if ( 'div' != $args[ 'style' ] ) : ?>
         </div><?php
      endif;
   }
endif;

/*---------------------------------------
   EXCERPT CUSTOM WORD COUNT
-----------------------------------------*/
function sassly_excerpt( $words = 40, $more = '' ) {

   if( $more == 'button' ){
      $more = '<a class="btn btn-primary">'.esc_html__('read more', 'sassly').'</a>';
   }
   $words = sassly_option('blog_excerpt_word', $words);
   $excerpt         = get_the_excerpt();
   $trimmed_content = wp_trim_words( $excerpt, $words, $more );
   echo wpautop( wp_kses_post( $trimmed_content ));
}

/*--------------------------------------
   SINGLE POST NAVIGATION
---------------------------------------*/
if ( !function_exists('sassly_post_nav') ):

// display navigation to the next/previous set of posts
// ----------------------------------------------------------------------------------------
function sassly_post_nav() {
   // Don't print empty markup if there's nowhere to navigate.
  
      if( !sassly_option('blog_post_nav','1') ){
         return;
      }

      $next_post	 = get_next_post();
      $pre_post	 = get_previous_post();
      if ( !$next_post && !$pre_post ) {
         return;
      }
   ?>
      <nav class="joya--post-navigation clearfix mb-140">
         <div class="post-previous">
            <?php if ( !empty( $pre_post ) ): ?>
               <a href="<?php echo get_the_permalink( $pre_post->ID ); ?>">
                  <h3><?php echo get_the_title( $pre_post->ID ) ?></h3>
                  <span><i class="icon-wcf-angle-left"></i><?php esc_html_e( 'Previous post', 'sassly' ) ?></span>
               </a>
            <?php endif; ?>
         </div>
         <div class="post-next">
            <?php if ( !empty( $next_post ) ): ?>
               <a href="<?php echo get_the_permalink( $next_post->ID ); ?>">
                  <h3><?php echo get_the_title( $next_post->ID ) ?></h3>
   
                  <span><?php esc_html_e( 'Next post', 'sassly' ) ?> <i class="icon-wcf-angle-right"></i></span>
               </a>
            <?php endif; ?>
         </div>
      </nav>
   <?php }
 endif;

/*
* get images sizes
* @return bool
*/
function sassly_get_all_image_sizes() {
	global $_wp_additional_image_sizes;

	$default_image_sizes = array( 'thumbnail', 'medium', 'large' );
	 
	foreach ( $default_image_sizes as $size ) {
		$image_sizes[$size]['width']	= intval( get_option( "{$size}_size_w") );
		$image_sizes[$size]['height'] = intval( get_option( "{$size}_size_h") );
		$image_sizes[$size]['crop']	= get_option( "{$size}_crop" ) ? get_option( "{$size}_crop" ) : false;
	}
	
	if ( isset( $_wp_additional_image_sizes ) && count( $_wp_additional_image_sizes ) )
		$image_sizes = array_merge( $image_sizes, $_wp_additional_image_sizes );
		
	return $image_sizes;
}

function sassly_social_share(){
   // option blog-details.php 
   $post_social_share_show = sassly_option('enable_social_share',0);
   
   if(!$post_social_share_show){
      return;
   }

   $general_social_share = sassly_option('social_share',[]);
   
   if( !is_array($general_social_share) ){
      return;  
   }
   
   if(empty($general_social_share) ){
      return;  
   }
   
   return $general_social_share;
}


if(!function_exists('sassly_header_footer__custom_ele_type')){

   function sassly_header_footer__custom_ele_type($type='type_header'){
      $default = ['' => esc_html__('Select a option','sassly')];
      $args = [
         'post_type'    => 'elementor-hf',
         'meta_key'     => 'ehf_template_type',
         'meta_value'   => $type,        
         'order'        => 'ASC',
         'meta_query'   => [
            'relation' => 'OR',
            [
               'key'     => 'ehf_template_type',
               'value'   => $type,
               'compare' => '==',               
            ],
         ],
      ];
      
      $template = get_posts( $args );
      
      if(is_wp_error($template)){
      
       return $default;
      }
      
      if(is_array( $template ) && count($template) ){
        return wp_list_pluck($template,'post_title','ID'); 
      }
      
      return $default;
     
   }   
   
}

function sassly_is_blog_banner_active(){

   $settings                 = sassly_option( 'opt-tabbed-banner' );
   $page_show                = isset( $settings[ 'page_banner_show' ] ) ? $settings[ 'page_banner_show' ] : 1;   
   $blog_show                = isset( $settings[ 'blog_banner_show' ] ) ? $settings[ 'blog_banner_show' ] : 1;
   $errorpage_show           = isset( $settings[ '404_page_banner_show' ] ) ? $settings[ '404_page_banner_show' ] : 1;   
   $default                   = 'no-banner';
   if(is_page() && $page_show){
      if(is_page_template('page-templates/homepage.php')){
         return $default;
      }
     return sassly_is_transparent_header() ? 'header-has-banner wcfpage' : '';
   }
   
   if((is_archive() || is_author() || is_category() || is_home() || is_single() || is_tag()) && 'post' == get_post_type() && $blog_show){
      return 'header-has-banner wcfblog';
   }
   
   if(is_404() && $errorpage_show){
      return 'header-has-banner';
   }
   
   if(is_search() && $blog_show){
      return 'header-has-banner'; 
   }
   
   return $default;
}

function sassly_is_transparent_header(){

   $transparent_header       = sassly_option( 'transparent_header' , 0 );
   $page_transparent_header  = sassly_meta_option( get_the_id() , 'transparent_header' , 0 );
   
   if(is_single()){
      if(!$page_transparent_header ){
         return false;
      }
   }
   
   
   return $transparent_header;
   
}





