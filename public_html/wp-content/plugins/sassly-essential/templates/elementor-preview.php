<!DOCTYPE html>
<html <?php language_attributes(); ?>>
    <head>
        <meta charset="<?php bloginfo( 'charset' ); ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	    <?php wp_head(); ?>
	    <style id="wcf-elementor-preview">
	        body.wcf-remove-preloader-overflow{
				overflow: auto !important;
	        }
	    </style>
    </head>
    <body <?php body_class('wcf-remove-preloader-overflow'); ?> >   		            
		<div class="wcf-elementor-preview" style="margin-top:50px;margin-bottom:50px;">
			<?php if ( have_posts() ) : ?>
              	<?php while ( have_posts() ) : the_post(); ?>
    			<?php the_content();?>	    							
              	<?php endwhile; ?>
              	<?php else : ?>
              	<?php get_template_part( 'template-parts/blog/content', 'none' ); ?>
	        <?php endif; ?>     		         			
		</div>	
   <?php wp_footer(); ?> 
 </body>