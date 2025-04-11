  <?php  
      if ( is_search() ) {
            the_excerpt();
          } else {
          the_content( esc_html__( 'Continue reading &rarr;', 'sassly-essential' ) );
          sassly_link_pages();
      }
      
      $has_tags = sassly_has_post_tags();      
      
      $flex_cls = 'col-lg-12';
  ?>
  <div class="row align-items-center pt-70 pb-140">
      <?php if($has_tags): ?>
        <div class="<?php echo esc_attr($flex_cls); ?>">
          <?php get_template_part( 'template-parts/blog/blog-parts/part', 'tags' ); ?>
        </div>
      <?php endif ?>      
  </div>

    

  
 
  
  
    