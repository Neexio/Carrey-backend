<?php 
  
   $banner_title             = get_bloginfo( 'name' );
   $settings                 = sassly_option( 'opt-tabbed-banner');
   $show                     = isset( $settings[ 'blog_banner_show' ] ) ? $settings[ 'blog_banner_show' ] : 1;
   $show_breadcrumb          = isset( $settings[ 'blog_show_breadcrumb' ] ) ? $settings[ 'blog_show_breadcrumb' ] : 1;
   $shortcode                = isset( $settings[ 'blog_elementor_shortcode' ] ) ? $settings[ 'blog_elementor_shortcode' ] : 0;
   $shortcode_id                = isset( $settings[ 'blog_banner_shortcode' ] ) ? $settings[ 'blog_banner_shortcode' ] : '';
   $banner_cls               = [];
   /* Title start */   
   if( isset($settings['banner_blog_title']) && $settings['banner_blog_title'] != '' ){
     $banner_title = $settings['banner_blog_title'];
   }
   /* Title end */  
   /*Override Search banner content */
   if(is_search() && isset($settings[ 'search_page_banner_show' ])){
   
      $show            = $settings[ 'search_page_banner_show' ];
      $show_breadcrumb = $settings[ 'search_page_show_breadcrumb' ];
      
      if($settings['search_banner_page_title'] !=''){
         $banner_title = $settings['search_banner_page_title'];
      }
      
   }


   
   if(sassly_is_transparent_header()){
      $banner_cls[] = 'pt-250';
   }else{
      $banner_cls[] = 'pt-200';
   }
     
?>
 <!-- default banner start -->
<?php if($show): ?>
   <?php if($shortcode && $shortcode_id !='' && shortcode_exists('WCF_ELEMENTOR_TPL')){ ?>
      <?php echo do_shortcode($shortcode_id); ?>
   <?php }else{ ?>
         <div class="default-breadcrumb__area pb-200 <?php echo esc_attr(implode(' ', $banner_cls )) ?>">
           <div class="container">
           <?php if($banner_title !=''): ?>  
             <h1 class="default-breadcrumb__title"><?php echo esc_html($banner_title); ?></h1>
           <?php endif; ?>
           <?php if($show_breadcrumb): ?>
              <?php sassly_get_breadcrumbs('<i class="icon-wcf-checvron-right"></i>'); ?>
           <?php endif; ?>
           </div>
         </div>
    <?php } ?>
<?php endif; ?>
   <!-- default banner end -->
