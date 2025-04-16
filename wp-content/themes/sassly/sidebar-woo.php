<?php
/**
 * displays sidebar
 */
?>

<?php if ( is_active_sidebar( 'woo' ) ) { ?>
   <div class="wcf-woo--sidebar-wrapper">
      <aside class="wcf-woo--sidebar">
         <?php dynamic_sidebar( 'woo' ); ?>
      </aside><!-- Sidebar col end -->
   </div><!-- Sidebar col end -->
<?php } ?>