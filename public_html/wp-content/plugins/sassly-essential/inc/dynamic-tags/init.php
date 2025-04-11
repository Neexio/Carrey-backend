<?php


function sassly_register_site_dynamic_tag_group( $dynamic_tags_manager ) {

	$dynamic_tags_manager->register_group(
		'wcf',
		[
			'title' => esc_html__( 'WCF', 'sassly-essential' )
		]
	);

}
add_action( 'elementor/dynamic_tags/register', 'sassly_register_site_dynamic_tag_group' );

/**
 * Register Random Number Dynamic Tag.
 *
 * Include dynamic tag file and register tag class.
 *
 * @since 1.0.0
 * @param \Elementor\Core\DynamicTags\Manager $dynamic_tags_manager Elementor dynamic tags manager.
 * @return void
 */
function register_random_number_dynamic_tag( $dynamic_tags_manager ) {

	require_once( __DIR__ . '/logo.php' );
	$dynamic_tags_manager->register( new \Elementor_Dynamic_Tag_ACF_Average );

}
add_action( 'elementor/dynamic_tags/register', 'register_random_number_dynamic_tag' );


