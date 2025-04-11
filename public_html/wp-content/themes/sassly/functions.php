<?php

/*----------------------------------------------------
SHORTHAND CONTANTS FOR THEME VERSION
-----------------------------------------------------*/
if ( site_url() === 'http://localhost:8080/development' ) {
    define( 'SASSLY_VERSION', time() );
} else {
    define( 'SASSLY_VERSION', 2.0 );
    
}

/*----------------------------------------------------
SHORTHAND CONTANTS FOR THEME ASSETS URL
-----------------------------------------------------*/
define( 'SASSLY_THEME_URI', get_template_directory_uri() );
define( 'SASSLY_ASSETS', SASSLY_THEME_URI . '/assets/' );
define( 'SASSLY_IMG', SASSLY_THEME_URI . '/assets/imgs' );
define( 'SASSLY_CSS', SASSLY_THEME_URI . '/assets/css' );
define( 'SASSLY_JS', SASSLY_THEME_URI . '/assets/js' );

/*----------------------------------------------------
SHORTHAND CONTANTS FOR THEME ASSETS DIRECTORY PATH
-----------------------------------------------------*/
define( 'SASSLY_THEME_DIR', get_template_directory() );
define( 'SASSLY_IMG_DIR', SASSLY_THEME_DIR . '/assets/imgs' );
define( 'SASSLY_CSS_DIR', SASSLY_THEME_DIR . '/assets/css' );
define( 'SASSLY_JS_DIR', SASSLY_THEME_DIR . '/assets/js' );



/*----------------------------------------------------
LOAD Classes
-----------------------------------------------------*/
if ( file_exists( dirname( __FILE__ ) . '/app/loader.php' ) ):
    require_once dirname( __FILE__ ) . '/app/loader.php';    
endif;
/*----------------------------------------------------
SET UP THE CONTENT WIDTH VALUE BASED ON THE THEME'S DESIGN
-----------------------------------------------------*/
if ( !isset( $content_width ) ) {
    $content_width = 800;
}

add_filter( 'use_block_editor_for_post', '__return_false' );

// Disable Gutenberg for widgets.
add_filter( 'use_widgets_block_editor', '__return_false' );


//Woocommerce Supports
function sassly_add_woocommerce_support() {
	add_theme_support( 'woocommerce', array(
		'thumbnail_image_width' => 350,
		'single_image_width'    => 350,
		'product_grid'          => array(
			'default_rows'    => 3,
			'min_rows'        => 2,
			'max_rows'        => 8,
			'default_columns' => 4,
			'min_columns'     => 2,
			'max_columns'     => 5,
		),
	) );

	add_theme_support( 'wc-product-gallery-zoom' );
	add_theme_support( 'wc-product-gallery-lightbox' );
	add_theme_support( 'wc-product-gallery-slider' );


}

add_action( 'after_setup_theme', 'sassly_add_woocommerce_support' );



