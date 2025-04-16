<?php
/**
 * displays sidebar
 */
?>


<?php if ( is_active_sidebar( 'sidebar-1' ) ) { ?>
   <div class="default-blog__item">
      <aside class="default-sidebar__wrapper">
         <?php dynamic_sidebar( 'sidebar-1' ); ?>
      </aside><!-- Sidebar col end -->
   </div><!-- Sidebar col end -->
<?php } ?>