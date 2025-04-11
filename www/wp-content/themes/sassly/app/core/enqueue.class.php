<?php
namespace sassly\Core;

/**
 * Enqueue.
 */
class Enqueue 
{

	/**
	 * register default hooks and actions for WordPress
	 * @return
	 */
	public function register() 
	{
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );		    
	}
       
	public function enqueue_scripts() 
	{
	
    	// stylesheets
    	wp_register_style( 'wcf-custom-icons', SASSLY_CSS . '/custom-icons.min.css', null, SASSLY_VERSION );
    	wp_register_style( 'magnific-popup', SASSLY_CSS . '/magnific-popup.min.css', null, SASSLY_VERSION );
    	wp_register_style( 'vidbacking', SASSLY_CSS . '/jquery.vidbacking.css', null, SASSLY_VERSION );
    	wp_register_style( 'bootstrap', SASSLY_CSS . '/bootstrap.min.css', null, SASSLY_VERSION );
		// ::::::::::::::::::::::::::::::::::::::::::
		if ( !is_admin() ) {			

			// 3rd party css
			wp_enqueue_style( 'sassly-fonts', sassly_google_fonts_url(['DM Sans:300,400;500,600,700,800,900', 'PT Serif:400;500,600,700']), null, SASSLY_VERSION );			
			wp_enqueue_style( 'meanmenu', SASSLY_CSS . '/meanmenu.min.css', null, SASSLY_VERSION );	
			wp_enqueue_style( 'bootstrap' );
			wp_enqueue_style( 'magnific-popup' );
		   
		    // Theme style
			wp_enqueue_style( 'sassly-style', SASSLY_CSS . '/master.css', null, SASSLY_VERSION );		
			
			// WooCommerce
			if( class_exists('WooCommerce') ){
				wp_enqueue_style( 'sassly-woo', SASSLY_CSS . '/woo.css', null, SASSLY_VERSION );
			}

			wp_enqueue_style( 'wcf-custom-icons' );			

		}

		// javascripts
		// :::::::::::::::::::::::::::::::::::::::::::::::
		if ( !is_admin() ) {
			
			// 3rd party scripts
				
			wp_enqueue_script( 'bootstrap', SASSLY_JS . '/bootstrap.bundle.min.js', array( 'jquery' ), SASSLY_VERSION, true );	
			wp_enqueue_script( 'meanmenu', SASSLY_JS . '/jquery.meanmenu.min.js', array( 'jquery' ), SASSLY_VERSION, true );		
			wp_enqueue_script( 'magnific-popup', SASSLY_JS . '/jquery.magnific-popup.min.js', array( 'jquery' ), SASSLY_VERSION, true );			
            if(is_singular('post')){
				wp_enqueue_script( 'goodshare', SASSLY_JS . '/goodshare.min.js', array( 'jquery' ), SASSLY_VERSION, true );
			}
			// theme scripts			
			wp_enqueue_script( 'sassly-script', SASSLY_JS . '/script.js', array( 'jquery','bootstrap'), SASSLY_VERSION, true );
		
			$sassly_data = apply_filters('sassly/script/custom/data',[
				 'ajax_url' => admin_url( 'admin-ajax.php' ),
				 'cart_update_qty_change' => sassly_option('cart_uwq_change', false),
			]);
			
			wp_localize_script( 'sassly-script', 'sassly_obj', $sassly_data);
			// Load WordPress Comment js
			if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		    	wp_enqueue_script( 'comment-reply' );
			}

		}
    }
}
