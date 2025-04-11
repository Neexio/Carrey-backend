  <?php
  
   $copyright_text = sassly_option('copyright_text','© 2022 - 2024 | Alrights reserved by crowdyTheme');
   $is_footer_sidebar_active = is_active_sidebar('footer-one') || is_active_sidebar('footer-two') || is_active_sidebar('footer-three') || is_active_sidebar('footer-four');
  ?>

  <footer class="footer__area-8 jfooter-wrapper <?php echo esc_attr($is_footer_sidebar_active ? 'pt-130' : ''); ?>">
      <?php if( $is_footer_sidebar_active ) { ?>
        <div class="container">
            <div class="footer__wrapper-8">
                <div class="footer__item-8">
                  <?php dynamic_sidebar( 'footer-one' ); ?>
                </div>
                <div class="footer__item-8">
                  <?php dynamic_sidebar( 'footer-two' ); ?>
                </div>
                <div class="footer__item-8">
                  <?php dynamic_sidebar( 'footer-three' ); ?>
                </div> 
                <div class="footer__item-8">
                  <?php dynamic_sidebar( 'footer-four' ); ?>
                </div>
            </div>
        </div>  
      <?php } ?>
      <div class="copyright__area-8 jcopyright <?php echo esc_attr(!$is_footer_sidebar_active ? 'mt-0' : ''); ?>">
          <p class="copyright__fees"><?php echo sassly_kses(str_replace(['{','}'],['<span>','</span>'],$copyright_text)); ?></p>
    </div>
  </footer>