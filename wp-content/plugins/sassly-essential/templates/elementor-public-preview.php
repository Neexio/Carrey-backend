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
        <?php       
        
			global $wpdb;
			$post_name  = get_query_var( 'wcf-template-slug' );
	        $post       = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_name = %s", $post_name ));
       
        ?>
		<div class="wcf-elementor-preview" style="margin-top:50px;margin-bottom:50px;">
			<?php if ( is_numeric($post) && isset($_GET['post']) ) : ?>
	              	<?php if ( get_query_var( 'wcf-template-slug' )):  ?>
						<?php echo \Elementor\Plugin::instance()->frontend->get_builder_content( $_GET['post'] , true); ?>				
	              	<?php endif; ?>
              	<?php else : ?>
              	<?php get_template_part( 'template-parts/blog/content', 'none' ); ?>
	        <?php endif; ?> 
		</div>	
   <?php wp_footer(); ?> 
 </body>