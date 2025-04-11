<?php

/**
 * full-wdith.php
 *
 * Template Name: FullWidth Template
 * Template Post Type: page, post
 */
	get_header(); 
	get_template_part( 'template-parts/banner/content', 'banner-page' );

?>
	<main>
		<div class="default-blog__area">
			<?php if ( have_posts() ) : ?>
		        <?php while ( have_posts() ) : the_post(); ?>
		    		<?php the_content();?>
		    	    <?php endwhile; ?>
		        <?php else : ?>
		            <?php get_template_part( 'template-parts/blog/content', 'none' ); ?>
		    <?php endif; ?> 				
		</div><!-- blog__area -->
	</main>
	<?php get_footer(); ?>