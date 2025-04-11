<?php 
  
   $banner_title             = esc_html__( '404 Error', 'sassly' );
   $settings                 = sassly_option( 'opt-tabbed-banner');
   $show                     = isset( $settings['404_page_banner_show'] ) ? $settings['404_page_banner_show'] : 0;
   $show_breadcrumb          = isset( $settings['404_page_show_breadcrumb'] ) ? $settings['404_page_show_breadcrumb'] : 1;
   $shortcode                = isset( $settings[ '404_elementor_shortcode' ] ) ? $settings[ '404_elementor_shortcode' ] : 0;
   $shortcode_id                = isset( $settings[ '404_banner_shortcode' ] ) ? $settings[ '404_banner_shortcode' ] : '';
   
  /* Title start */   
   if( isset($settings['404_banner_page_title']) && $settings['404_banner_page_title'] != '' ){
     $banner_title = $settings['404_banner_page_title'];
   }
   /* Title end */    
?>
 <!-- default banner start -->
<?php if($show): ?>   
   <?php if($shortcode && $shortcode_id !='' && shortcode_exists('WCF_ELEMENTOR_TPL')){ ?>
      <?php echo do_shortcode($shortcode_id); ?>
   <?php }else{ ?>
    <div class="default-breadcrumb__area pt-250 pb-200">
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