<?php

   $blog_readmore__icon   = SASSLY_IMG .'/default-blog/arrow-right-1.png';
   $read_more_icon_option = sassly_option('blog_readmore__icon');
   $feature_audio         = sassly_meta_option(get_the_id(),'feature_audio');
   $icon = '<i class="icon-wcf-arrow-right"></i>';
   if(is_array($read_more_icon_option) && isset($read_more_icon_option['url']) && $read_more_icon_option['url'] !=''){
    $blog_readmore__icon = $read_more_icon_option['url'];
    $icon = sprintf('<img src="%s" alt="%s">', esc_url($blog_readmore__icon), esc_attr__('arrow right','sassly'));
   }
 
?>

<?php if(sassly_option('blog_layout') === 'style-2'):  ?>
  <article <?php post_class('default-blog__style-2'); ?>>
    <?php if( sassly_option('blog_author','1')  ): ?>
      <div class="author">
      <?php if( sassly_option('blog_author_image','1')  ): ?>
          <div class="author-img">
            <?php echo wp_kses_post( get_avatar( get_the_author_meta( 'ID' ), 60 ) ); ?>
          </div>
        <?php endif; ?>
        <div class="author-bio">
          <h4><?php esc_html_e( 'Written by ', 'sassly' ); ?></h4>
          <a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>"><?php the_author(); ?></a>
        </div>
      </div>
    <?php endif; ?>

    <div class="content">
    <?php if( sassly_option('blog_thumb','1')  ): ?>
      <div class="thumb">
      <?php if($feature_audio !=''): ?>
        <?php echo wp_oembed_get( $feature_audio ); ?>
      <?php endif; ?>
    </div>
    <?php endif; ?>
      <?php echo sassly_post_meta_2(); ?>
      <h2 class="title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
      <div class="cf_text">
        <?php sassly_excerpt( 40, null ); ?>
      </div>
    </div>

    <?php if(sassly_option('blog_readmore',1)): ?>
    <div class="cf_btn">
      <a href="<?php the_permalink(); ?>" class="link"><?php echo sassly_kses($icon); ?></a>
    </div>
    <?php endif; ?>

  </article>
<?php else: ?>
  <article <?php post_class('default-blog__item-single'); ?>>
    <?php if( sassly_option('blog_thumb','1')  ): ?>
      <?php if($feature_audio !=''): ?>
        <?php echo wp_oembed_get( $feature_audio ); ?>
      <?php endif; ?>
    <?php endif; ?>
      <div class="default-blog__content">
          <?php echo sassly_post_meta(); ?>
            <h2 class="default-blog__item-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
            <div class="cf_text">
              <?php sassly_excerpt( 40, null ); ?>
            </div>
            <?php if(sassly_option('blog_readmore',1)): ?>
            <div class="cf_btn">
              <a href="<?php the_permalink(); ?>" class="wc-btn-underline">
                  <span class="screen-reader-text"><?php echo esc_html__('Read the blog details', 'sassly'); ?></span>
                  <?php echo esc_html(sassly_option('blog_readmore_text','Read more')); ?><img src="<?php echo esc_url($blog_readmore__icon); ?>" alt="<?php esc_attr__('arrow right','sassly'); ?>"> </a>
            </div>
          <?php endif; ?>
      </div>
  </article>
<?php endif; ?>