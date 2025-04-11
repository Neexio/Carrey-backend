<?php

/**
 * full-container-wdith.php
 *
 * Template Name: Full Container Width Template
 * Template Post Type: page, post
 */
	get_header(); 
	get_template_part( 'template-parts/banner/content', 'banner-page' );
	$blog_sidebar    = sassly_option('blog_sidebar','left-sidebar');   
	$blog_sidebar    = $blog_sidebar == ''? 'left-sidebar' : $blog_sidebar;
	$sidebar_cls[$blog_sidebar]   = is_active_sidebar('sidebar-1') ? $blog_sidebar : 'no-sidebar';
	
	$disable_banner  = sassly_meta_option( get_the_ID(), 'disable_banner' );
	$container_cls = apply_filters('sassly_blog_content_container_cls','pt-150 pb-150');
	if($disable_banner && is_page()){	  
		$container_cls = '';
	}
?>
	<main>
		<div class="default-blog__area <?php echo esc_attr($container_cls); ?>">
			<div class="container">
				<div class="default-blog__grid no-sidebar">              
					<div class="default-blog__item-content">
	              		<?php if ( have_posts() ) : ?>
	              			<?php while ( have_posts() ) : the_post(); ?>
	    						    <?php the_content();?>
	    							<?php
	    								if ( is_user_logged_in() ) {
	    									echo '<p>';
	    										edit_post_link( 
	    											esc_html__( 'Edit', 'sassly' ), 
	    											'<span class="meta-edit">', 
	    											'</span>'
	    										);
	    									echo '</p>';
	    								}
	    							?>
	              			<?php endwhile; ?>
	              			<?php else : ?>
	              				<?php get_template_part( 'template-parts/blog/content', 'none' ); ?>
	              		<?php endif; ?>          			
					</div>            
				</div><!--grid -->
			</div><!--grid -->
		</div><!-- container -->
	</main><!-- #main-content -->
	<?php get_footer(); ?>