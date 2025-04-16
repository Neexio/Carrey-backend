<?php
/**
 * the template for displaying all pages.
 */
get_header();
//get_template_part( 'template-parts/banner/content', 'banner-shop' );

$sidebar = 'wcf_woo_sidebar';
if(function_exists('is_product') && is_product()){
	$sidebar = 'wcf_woo_product_sidebar';
}

$woo_sidebar    = sassly_option($sidebar,'left-sidebar');
$woo_sidebar    = $woo_sidebar == ''? 'left-sidebar' : $woo_sidebar;
$sidebar_cls[$woo_sidebar]   = is_active_sidebar('woo') ? $woo_sidebar : 'no-sidebar';

$disable_banner  = sassly_meta_option( get_the_ID(), 'disable_banner' );
$container_cls = 'pt-150 pb-150';
if($disable_banner){
	$container_cls = '';
}
?>

	<main id="content-area">
		<div class="wcf-woo--wrapper <?php echo esc_attr($container_cls); ?>">
			<div class="container">
				<div class="wcf-woo--grid wcf-woo--shop <?php echo esc_attr( implode(' ', $sidebar_cls) ); ?>">
					<?php
					//Sidebar
					if($woo_sidebar == 'left-sidebar'){
						get_sidebar('woo');
					}
					?>
					<div class="wcf-woo--content">
						<?php if ( have_posts() ) : ?>
							<?php woocommerce_content(); ?>
						<?php endif; ?>
					</div>
					<?php
					// Sidebar
					if($woo_sidebar == 'right-sidebar'){
						get_sidebar('woo');
					}
					?>
				</div><!--grid -->
			</div><!-- container -->
	</main><!-- #main-content -->


<?php get_footer(); ?>