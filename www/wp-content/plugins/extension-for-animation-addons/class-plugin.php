<?php

namespace WCFAddonsEX;

use Elementor\Plugin as ElementorPlugin;
use WCF_ADDONS\Plugin as WCFAddonsPlugin;

/**
 * Class Plugin
 *
 * Main Plugin class
 *
 * @since 1.2.0
 */
class Plugin {

	/**
	 * Instance
	 *
	 * @since 1.0.0
	 * @access private
	 * @static
	 *
	 * @var Plugin The single instance of the class.
	 */
	private static $instance = null;

	/**
	 * Instance
	 *
	 * Ensures only one instance of the class is loaded or can be loaded.
	 *
	 * @return Plugin An instance of the class.
	 * @since 1.2.0
	 * @access public
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Widget_scripts
	 *
	 * Load required plugin core files.
	 *
	 * @since 1.2.0
	 * @access public
	 */
	public function widget_scripts() {

		foreach ( self::get_library_scripts() as $key => $script ) {
			wp_register_script( $script['handler'], plugins_url( '/assets/lib/' . $script['src'], __FILE__ ), $script['dep'], $script['version'], $script['arg'] );

			wp_enqueue_script( $script['handler'] );
		}

		//widget scripts
		foreach ( self::get_widget_scripts() as $key => $script ) {
			wp_register_script( $script['handler'], plugins_url( '/assets/js/' . $script['src'], __FILE__ ), $script['dep'], $script['version'], $script['arg'] );
		}

		//main scripts
		wp_enqueue_script( 'wcf--addons-ex' );
	}

	/**
	 * Function widget_styles
	 *
	 * Load required plugin core files.
	 *
	 * @since 1.2.0
	 * @access public
	 */
	public static function widget_styles() {
		//widget style
		foreach ( self::get_widget_style() as $key => $style ) {
			wp_register_style( $style['handler'], plugins_url( '/assets/css/' . $style['src'], __FILE__ ), $style['dep'], $style['version'], $style['media'] );
		}

		wp_enqueue_style( 'magnific-popup' );

		wp_enqueue_style( 'wcf--addons-ex' );
	}

	/**
	 * Editor scripts
	 *
	 * Enqueue plugin javascripts integrations for Elementor editor.
	 *
	 * @since 1.2.1
	 * @access public
	 */
	public function editor_scripts() {
		wp_enqueue_script( 'wcf-ex-editor', plugins_url( '/assets/js/editor.js', __FILE__ ), [
			'elementor-editor',
		], WCF_ADDONS_VERSION, true );
	}

	/**
	 * Function widget_scripts
	 *
	 * Load required plugin core files.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public static function get_widget_scripts() {
		return [
			'wcf-addons-ex' => [
				'handler' => 'wcf--addons-ex',
				'src'     => 'wcf-addons-ex.js',
				'dep'     => [],
				'version' => false,
				'arg'     => true,
			],
			'portfolio'     => [
				'handler' => 'wcf--portfolio',
				'src'     => 'portfolio.js',
				'dep'     => [ 'mixitup' ],
				'version' => false,
				'arg'     => true,
			],
			'mailchimp'     => [
				'handler' => 'wcf--mailchimp',
				'src'     => 'mailchimp.js',
				'dep'     => [],
				'version' => false,
				'arg'     => true,
			],
		];
	}

	/**
	 * Function lib_scripts
	 *
	 * Load required plugin core files.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public static function get_library_scripts() {

		$scripts = [
			'gsap'            => [
				'handler' => 'gsap',
				'src'     => 'gsap.min.js',
				'dep'     => [],
				'version' => false,
				'arg'     => true,
			],
			'scroll-smoother' => [
				'handler' => 'scrollSmoother',
				'src'     => 'ScrollSmoother.min.js',
				'dep'     => [ 'gsap' ],
				'version' => false,
				'arg'     => true,
			],
			'scroll-to' => [
				'handler' => 'scrollTo',
				'src'     => 'ScrollToPlugin.min.js',
				'dep'     => [ 'gsap' ],
				'version' => false,
				'arg'     => true,
			],
			'scroll-trigger'  => [
				'handler' => 'scrollTrigger',
				'src'     => 'ScrollTrigger.min.js',
				'dep'     => [ 'gsap' ],
				'version' => false,
				'arg'     => true,
			],
			'split-text'      => [
				'handler' => 'split-text',
				'src'     => 'SplitText.min.js',
				'dep'     => [ 'gsap' ],
				'version' => false,
				'arg'     => true,
			],
			'magnific-popup'  => [
				'handler' => 'magnific-popup',
				'src'     => 'jquery.magnific-popup.min.js',
				'dep'     => [],
				'version' => false,
				'arg'     => true,
			],
			'mixitup'         => [
				'handler' => 'mixitup',
				'src'     => 'mixitup.min.js',
				'dep'     => [],
				'version' => false,
				'arg'     => true,
			],
		];

		if ( ! wcf_addons_get_settings( 'wcf_save_extensions', 'wcf-smooth-scroller' ) ) {
			unset( $scripts['scroll-smoother'] );
		}

		if ( ! wcf_addons_get_settings( 'wcf_save_extensions', 'wcf-gsap' ) ) {

			unset( $scripts['gsap'] );
		}

		return $scripts;
	}

	/**
	 * Function widget_style
	 *
	 * Load required plugin core files.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public static function get_widget_style() {
		return [
			'wcf-addons-ex'    => [
				'handler' => 'wcf--addons-ex',
				'src'     => 'wcf-addons-ex.css',
				'dep'     => [],
				'version' => false,
				'media'   => 'all',
			],
			'magnific-popup'   => [
				'handler' => 'magnific-popup',
				'src'     => 'magnific-popup.css',
				'dep'     => [],
				'version' => false,
				'media'   => 'all',
			],
			'video-box'        => [
				'handler' => 'wcf--video-box',
				'src'     => 'widgets/video-box.css',
				'dep'     => [],
				'version' => false,
				'media'   => 'all',
			],
			'video-box-slider' => [
				'handler' => 'wcf--video-box-slider',
				'src'     => 'widgets/video-box-slider.css',
				'dep'     => [],
				'version' => false,
				'media'   => 'all',
			],
			'mailchimp'        => [
				'handler' => 'wcf--mailchimp',
				'src'     => 'widgets/mailchimp.css',
				'dep'     => [],
				'version' => false,
				'media'   => 'all',
			],
			'portfolio'        => [
				'handler' => 'wcf--portfolio',
				'src'     => 'widgets/portfolio.css',
				'dep'     => [],
				'version' => false,
				'media'   => 'all',
			],
			'video-mask'       => [
				'handler' => 'wcf--video-mask',
				'src'     => 'widgets/video-mask.css',
				'dep'     => [],
				'version' => false,
				'media'   => 'all',
			],
		];
	}

	/**
	 * Register Widgets
	 *
	 * Register new Elementor widgets.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function register_widgets() {
		foreach ( WCFAddonsPlugin::get_widgets() as $slug => $data ) {

			// If upcoming don't register.
			if ( $data['is_upcoming'] ) {
				continue;
			}

			if ( $data['is_extension'] ) {
				if ( is_dir( __DIR__ . '/widgets/' . $slug ) ) {
					require_once( __DIR__ . '/widgets/' . $slug . '/' . $slug . '.php' );
				} else {
					require_once( __DIR__ . '/widgets/' . $slug . '.php' );
				}


				$class = explode( '-', $slug );
				$class = array_map( 'ucfirst', $class );
				$class = implode( '_', $class );
				$class = 'WCFAddonsEX\\Widgets\\' . $class;
				ElementorPlugin::instance()->widgets_manager->register( new $class() );
			}
		}
	}

	/**
	 * Register Widgets
	 *
	 * Register new Elementor Extensions.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function register_extensions() {
		foreach ( WCFAddonsPlugin::get_extensions() as $slug => $data ) {

			// If upcoming don't register.
			if ( $data['is_upcoming'] ) {
				continue;
			}

			if ( $data['is_extension'] ) {
				include_once WCF_ADDONS_EX_PATH . 'inc/extensions/wcf-' . $slug . '.php';
			}
		}
	}

	/**
	 * Include Plugin files
	 *
	 * @access private
	 */
	private function include_files() {
		require_once WCF_ADDONS_EX_PATH . 'inc/helper.php';		
		require_once WCF_ADDONS_EX_PATH . 'inc/hook.php';
		require_once WCF_ADDONS_EX_PATH . 'inc/ajax-handler.php';

		//extensions
		$this->register_extensions();
	}

	/**
	 *  Plugin class constructor
	 *
	 * Register plugin action hooks and filters
	 *
	 * @since 1.2.0
	 * @access public
	 */
	public function __construct() {

		// Register widget scripts
		add_action( 'wp_enqueue_scripts', [ $this, 'widget_scripts' ] );

		// Register widget style
		add_action( 'wp_enqueue_scripts', [ $this, 'widget_styles' ] );

		// Register widgets
		add_action( 'elementor/widgets/register', [ $this, 'register_widgets' ] );

		// Register editor scripts
		add_action( 'elementor/editor/after_enqueue_scripts', [ $this, 'editor_scripts' ] );

		$this->include_files();
	}
}

// Instantiate Plugin Class
Plugin::instance();
