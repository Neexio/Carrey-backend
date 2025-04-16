<?php
/**
 * The main template file
 */

	get_header(); 
	get_template_part( 'template-parts/banner/content', 'banner-blog' ); 
	$blog_sidebar    = sassly_option('blog_sidebar','left-sidebar');
   
	$blog_sidebar    = $blog_sidebar == ''? 'left-sidebar' : $blog_sidebar;
	$sidebar_cls[$blog_sidebar]   = is_active_sidebar('sidebar-1') ? $blog_sidebar : 'no-sidebar';
	
?>

	<main id="content-area">
		<div class="default-blog__area pt-150 pb-150">
            <div class="container">
				<div class="default-blog__grid <?php echo esc_attr( implode(' ', $sidebar_cls) ); ?>">
                <?php 
                    //Sidebar
					if($blog_sidebar == 'left-sidebar'){
						get_sidebar();
					}
				?>
               <div class="default-blog__item-content">
          			<?php if ( have_posts() ) : ?>
          				<?php while ( have_posts() ) : the_post(); ?>
          					<?php get_template_part( 'template-parts/blog/content'); ?>
          				<?php endwhile; ?>
          				<?php else : ?>
          				<?php get_template_part( 'template-parts/blog/content', 'none' ); ?>
          			<?php endif; ?>
          			<!-- Pagination -->
				    <?php get_template_part( 'template-parts/blog/paginations/pagination', 'style1' ); ?>
	            </div>
              <?php 
                    // Sidebar
				if($blog_sidebar == 'right-sidebar'){
					get_sidebar();
				}
				?>
            </div><!--grid -->
        </div><!-- container -->
    </main><!-- #main-content -->
	<?php get_footer(); ?>
