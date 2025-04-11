<?php
/**
 * the template for displaying all posts.
 */

  get_header(); 
  
  get_template_part( 'template-parts/banner/content', 'banner-blog' );
  $blog_sidebar = sassly_option('blog_sidebar','left-sidebar');
  
	$blog_sidebar = $blog_sidebar == '' ? 'left-sidebar' : $blog_sidebar;
	$sidebar_cls[$blog_sidebar] = is_active_sidebar('sidebar-1') ? $blog_sidebar : 'no-sidebar';

  
?>
  <main id="content-area">
    <div class="default-blog__area pt-150 pb-150">
      <div class="container">
        <div class="default-blog__grid <?php echo esc_attr( implode(' ', $sidebar_cls) ); ?>">
            <?php
  						if($blog_sidebar == 'left-sidebar'){
                get_sidebar();
              }
  					?>
            <?php while ( have_posts() ) : the_post();
                $has_tags         = sassly_has_post_tags();
                $has_social_share = sassly_social_share();
                
                $flex_cls = $has_tags && $has_social_share ? 'col-lg-6' : 'col-lg-12';
            ?>
                  <div class="default-blog__details-content">
                    <article id="post-<?php the_ID(); ?>" <?php post_class([ "default-blog__item-single","post-single" ]); ?>>
                      <?php get_template_part( 'template-parts/blog/content', 'single' ); ?>
                    </article>
                    <div class="row align-items-center clearfix pt-70 pb-140">
                      <?php if($has_tags): ?>
                        <div class="<?php echo esc_attr($flex_cls); ?>">
                          <?php get_template_part( 'template-parts/blog/blog-parts/part', 'tags' ); ?>
                        </div>
                      <?php endif ?>
                      <?php if($has_social_share): ?>
                        <div class="<?php echo esc_attr($flex_cls); ?>">
                            <?php get_template_part( 'template-parts/blog/blog-parts/part', 'social' ); ?>
                        </div>
                      <?php endif; ?>
                  </div>
                    <?php
                      get_template_part( 'template-parts/blog/blog-parts/part', 'author' );
                      sassly_post_nav();
                      comments_template();
                    ?>
                  </div>
            <?php endwhile; ?>
            <?php 
  						if($blog_sidebar == 'right-sidebar'){
                get_sidebar();
              }
  					?>
          </div> <!-- .row -->
       </div> <!-- .grid -->
    </div> <!-- .container -->
  </main> <!--#main-content -->
<?php get_footer(); ?>