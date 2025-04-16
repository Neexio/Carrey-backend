<?php
/**  
 * Landing Page
 * landing-page.php
 * Template Name: Landing Page Template
 **/

	get_header();
    ?>
    <main>
		<div class="default-blog__area wcf-landing-page">
			<div class="container">
				<div class="default-blog__grid no-sidebar">              
					<div class="default-blog__item-content">
	              		<?php if ( have_posts() ) : ?>
	              			<?php while ( have_posts() ) : the_post(); ?>
	    						    <?php the_content();?>	    							
	              			<?php endwhile; ?>
	              			<?php else : ?>
	              				<?php get_template_part( 'template-parts/blog/content', 'none' ); ?>
	              		<?php endif; ?>          			
					</div>            
				</div><!--grid -->
		</div><!-- container -->
	</main><!-- #main-content -->
    <?php
	get_footer();