<?php 

   $disable_banner           = sassly_meta_option( get_the_ID(), 'disable_banner' );
   
   if($disable_banner && is_page()){
      return;
   }
   
   $banner_title             = get_bloginfo( 'name' );
   $settings                 = sassly_option( 'opt-tabbed-banner');
   $show                     = isset( $settings[ 'page_banner_show' ] ) ? $settings[ 'page_banner_show' ] : 1;
   $show_breadcrumb          = isset( $settings[ 'page_show_breadcrumb' ] ) ? $settings[ 'page_show_breadcrumb' ] : 1;
   $page_title               = sassly_meta_option( get_the_ID(), 'banner_page_title' );
   
   $shortcode                = isset( $settings[ 'page_elementor_shortcode' ] ) ? $settings[ 'page_elementor_shortcode' ] : 0;
   $shortcode_id             = isset( $settings[ 'page_banner_shortcode' ] ) ? $settings[ 'page_banner_shortcode' ] : '';
 
   $banner_cls               = [];
   /* Title start */   
   if( isset($settings['banner_page_title']) && $settings['banner_page_title'] != '' ){
     $banner_title = $settings['banner_page_title'];
   }
   
   if($page_title != ''){
    $banner_title = $page_title;
   }
  
   if(sassly_is_transparent_header()){
     $banner_cls[] = 'pt-250';
   }else{
      $banner_cls[] = 'pt-200';
   }
   /* Title end */   
   
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
