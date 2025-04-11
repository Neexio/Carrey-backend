<?php
namespace SasslyEssentialApp;

use Elementor\Plugin as ElementorPlugin;
use SasslyEssentialApp\PageSettings\Page_Settings;


/**
 * Class Plugin
 *
 * Main Plugin class
 * @since 1.2.0
 */
class Plugin {

	/**
	 * Instance
	 *
	 * @since 1.2.0
	 * @access private
	 * @static
	 *
	 * @var Plugin The single instance of the class.
	 */
	private static $_instance = null;

	/**
	 * Instance
	 *
	 * Ensures only one instance of the class is loaded or can be loaded.
	 *
	 * @since 1.2.0
	 * @access public
	 *
	 * @return Plugin An instance of the class.
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * widget_scripts
	 *
	 * Load required plugin core files.
	 *
	 * @since 1.2.0
	 * @access public
	 */
	public function after_register_styles() {
		wp_register_style( 'sassly-header-preset', SASSLY_ESSENTIAL_ASSETS_URL . 'css/header-preset.css' );
		wp_register_style( 'sassly-landing-page', SASSLY_ESSENTIAL_ASSETS_URL . 'css/landing-page.css' , array(), '0.1.0', 'all');
		wp_register_style( 'sassly-header-offcanvas', SASSLY_ESSENTIAL_ASSETS_URL . 'css/offcanvas.css' );

	}
	public function widget_scripts() {

		wp_register_script(
			'wcf-lottie-player',
			SASSLY_ESSENTIAL_ASSETS_URL. 'js/lottie-player.js',
			//'https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js',
			[],
			false,
			true
		);
		wp_register_script(
			'wcf-lottie-interactivity',
			SASSLY_ESSENTIAL_ASSETS_URL. 'js/lottie-interactivity.min.js',
			//'https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js',
			[],
			false,
			true
		);
		wp_register_script( 'wcf-lottie', SASSLY_ESSENTIAL_ASSETS_URL. 'js/widgets/lottie.js' , [ 'wcf-lottie-player','wcf-lottie-interactivity' ], false, true );
		wp_register_script( 'wcf-offcanvas-menu', SASSLY_ESSENTIAL_ASSETS_URL. '/js/widgets/offcanvas-menu.js' , [ 'jquery' ], false, true );
		wp_register_script( 'wcf-sticky-container', SASSLY_ESSENTIAL_ASSETS_URL. 'js/elementor.sticky-section.js' , [ 'jquery'  ], false, true );
		// wp_register_script( 'gsap', plugins_url( '/assets/js/gsap.min.js', __FILE__ ), [ 'jquery' ], false, true );
		// wp_register_script( 'scrollTrigger', plugins_url( '/assets/js/ScrollTrigger.min.js', __FILE__ ), array( 'jquery','gsap' ), false , true );
		// wp_register_script( 'scrollToPlugin', plugins_url( '/assets/js/ScrollToPlugin.min.js', __FILE__ ), array( 'jquery','gsap' ), false , true );
		wp_register_script( 'meanmenu', plugins_url( '/assets/js/jquery.meanmenu.min.js', __FILE__ ), array( 'jquery' ), false , true );
		wp_register_script( 'sassly-essential--global-core', plugins_url( '/assets/js/wcf--global-core.min.js', __FILE__ ), [ 'jquery' ], false, true );
		wp_register_script( 'wcf-landing-page', SASSLY_ESSENTIAL_ASSETS_URL. '/js/widgets/landing-page.js' , [ 'jquery' ], false, true );

		$data = [
			'ajaxUrl'  => admin_url( 'admin-ajax.php' ),
			'_wpnonce' => wp_create_nonce( 'sassly--addons-frontend' )
		];
		wp_localize_script( 'sassly-essential--global-core', 'SASSLY_ADDONS_JS', $data );
		wp_enqueue_script( 'sassly-essential--global-core' );

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
		add_filter( 'script_loader_tag', [ $this, 'editor_scripts_as_a_module' ], 10, 2 );

		wp_enqueue_script(
			'wcf--elementor--editor',
			plugins_url( '/assets/js/editor/editor.js', __FILE__ ),
			[
				'elementor-editor',
			],
			time(),
			true
		);
	}

	/**
	 * Force load editor script as a module
	 *
	 * @since 1.2.1
	 *
	 * @param string $tag
	 * @param string $handle
	 *
	 * @return string
	 */
	public function editor_scripts_as_a_module( $tag, $handle ) {
		if ( 'wcf--elementor--editor' === $handle ) {
			$tag = str_replace( '<script', '<script type="module"', $tag );
		}

		return $tag;
	}

	/**
	 * Register Widgets
	 *
	 * Register new Elementor widgets.
	 *
	 * @since 1.2.0
	 * @access public
	 *
	 * @param Widgets_Manager $widgets_manager Elementor widgets manager.
	 */
	public function register_widgets( $widgets_manager ) {

		//@todo need follow the convention to create widget file
		// Its is now safe to include Widgets files
		//require_once( __DIR__ . '/widgets/image-box.php' );

		// Register Widgets
		//$widgets_manager->register( new Widgets\Image_Box() );

		foreach ( self::get_widgets() as $slug => $data ) {

			// If upcoming don't register.
			if ( $data['is_upcoming'] ) {
				continue;
			}

			if ( $data['is_active'] ) {
				if ( is_dir( __DIR__ . '/widgets/' . $slug ) ) {
					require_once( __DIR__ . '/widgets/' . $slug . '/' . $slug . '.php' );
				} else {
					require_once( __DIR__ . '/widgets/' . $slug . '.php' );
				}


				$class = explode( '-', $slug );
				$class = array_map( 'ucfirst', $class );
				$class = implode( '_', $class );
				$class = 'SasslyEssentialApp\\Widgets\\' . $class;
				ElementorPlugin::instance()->widgets_manager->register( new $class() );
			}
		}

	}
	/**
	 * Get Widgets List.
	 *
	 * @return array
	 */
	public static function get_widgets() {

		return apply_filters( 'sassly-essential/widgets', [
			'header-menu'           => [
				'label'       => __( 'WCF Navigation', 'sassly-essential' ),
				'is_active'   => true,
				'is_upcoming' => false,
				'demo_url'    => '',
				'doc_url'     => '',
				'youtube_url' => '',
			],
			'header-preset'         => [
				'label'       => __( 'WCF Header', 'sassly-essential' ),
				'is_active'   => true,
				'is_upcoming' => false,
				'demo_url'    => '',
				'doc_url'     => '',
				'youtube_url' => '',
			],
			'footer-menu'           => [
				'label'       => __( 'WCF Footer Navigation', 'sassly-essential' ),
				'is_active'   => true,
				'is_upcoming' => false,
				'demo_url'    => '',
				'doc_url'     => '',
				'youtube_url' => '',
			],
			'banner-breadcrumb'     => [
				'label'       => __( 'WCF Banner Breadcrumb', 'sassly-essential' ),
				'is_active'   => true,
				'is_upcoming' => false,
				'demo_url'    => '',
				'doc_url'     => '',
				'youtube_url' => '',
			],
			'blog-post-tags'        => [
				'label'       => __( 'WCF Post Tags', 'sassly-essential' ),
				'is_active'   => true,
				'is_upcoming' => false,
				'demo_url'    => '',
				'doc_url'     => '',
				'youtube_url' => '',
			],
			'dropdown'              => [
				'label'       => __( 'WCF Dropdown', 'sassly-essential' ),
				'is_active'   => true,
				'is_upcoming' => false,
				'demo_url'    => '',
				'doc_url'     => '',
				'youtube_url' => '',
			],
			'wc-cart-count'         => [
				'label'       => __( 'WCF Cart Count', 'sassly-essential' ),
				'is_active'   => true,
				'is_upcoming' => false,
				'demo_url'    => '',
				'doc_url'     => '',
				'youtube_url' => '',
			],
			'offcanvas-menu'        => [
				'label'       => __( 'WCF Offcanvas Menu', 'sassly-essential' ),
				'is_active'   => true,
				'is_upcoming' => false,
				'demo_url'    => '',
				'doc_url'     => '',
				'youtube_url' => '',
			],
			'landing-page'          => [
				'label'       => __( 'WCF Landing', 'sassly-essential' ),
				'is_active'   => true,
				'is_upcoming' => false,
				'demo_url'    => '',
				'doc_url'     => '',
				'youtube_url' => '',
			],
			'lottie'                => [
				'label'       => __( 'WCF Lottie', 'sassly-essential' ),
				'is_active'   => true,
				'is_upcoming' => false,
				'demo_url'    => '',
				'doc_url'     => '',
				'youtube_url' => '',
			],
			'sassly-button'         => [
				'label'       => __( 'Sassly Button', 'sassly-essential' ),
				'is_active'   => true,
				'is_upcoming' => false,
				'demo_url'    => '',
				'doc_url'     => '',
				'youtube_url' => '',
			],
			'sassly-testimonial'    => [
				'label'       => __( 'Sassly Testimonial', 'sassly-essential' ),
				'is_active'   => true,
				'is_upcoming' => false,
				'demo_url'    => '',
				'doc_url'     => '',
				'youtube_url' => '',
			],
			'sassly-icon-box'       => [
				'label'       => __( 'Sassly Icon Box', 'sassly-essential' ),
				'is_active'   => true,
				'is_upcoming' => false,
				'demo_url'    => '',
				'doc_url'     => '',
				'youtube_url' => '',
			],
			'sassly-notification'   => [
				'label'       => __( 'Sassly Notification', 'sassly-essential' ),
				'is_active'   => true,
				'is_upcoming' => false,
				'demo_url'    => '',
				'doc_url'     => '',
				'youtube_url' => '',
			],
			'sassly-draggable-item' => [
				'label'       => __( 'Sassly Draggable Item', 'sassly-essential' ),
				'is_active'   => true,
				'is_upcoming' => false,
				'demo_url'    => '',
				'doc_url'     => '',
				'youtube_url' => '',
			],
			'image-generator'       => [
				'label'       => __( 'Image Generator', 'sassly-essential' ),
				'is_active'   => true,
				'is_upcoming' => false,
				'demo_url'    => '',
				'doc_url'     => '',
				'youtube_url' => '',
			],
			'sassly-user-login'       => [
				'label'       => __( 'Sassly User Login', 'sassly-essential' ),
				'is_active'   => true,
				'is_upcoming' => false,
				'demo_url'    => '',
				'doc_url'     => '',
				'youtube_url' => '',
			],
			'sassly-user-registration'       => [
				'label'       => __( 'Sassly User Registration', 'sassly-essential' ),
				'is_active'   => true,
				'is_upcoming' => false,
				'demo_url'    => '',
				'doc_url'     => '',
				'youtube_url' => '',
			],
		] );
	}

	/**
	 * Add page settings controls
	 *
	 * Register new settings for a document page settings.
	 *
	 * @since 1.2.1
	 * @access private
	 */
	private function add_page_settings_controls() {
		require_once( __DIR__ . '/page-settings/manager.php' );
		new Page_Settings();
	}

	function add_elementor_widget_categories( $elements_manager ) {

		$elements_manager->add_category(
			'weal-coder-addon',
			[
				'title' => esc_html__( 'WCF', 'sassly-essential' ),
				'icon' => 'fa fa-plug',
			]
		);

		$elements_manager->add_category(
			'wcf-blog-single',
			[
				'title' => esc_html__( 'WCF Single', 'sassly-essential' ),
				'icon' => 'fa fa-plug',
			]
		);

		$elements_manager->add_category(
			'wcf-blog-search',
			[
				'title' => esc_html__( 'WCF Blog Search', 'sassly-essential' ),
				'icon' => 'fa fa-plug',
			]
		);

	}

	public function elementor_init() {
		// Its is now safe to include Widgets skins
		require_once( __DIR__ . '/skin//accordion.php' );
		require_once( __DIR__ . '/skin//accordion-two.php' );
		// Register skin
		add_action( 'elementor/widget/accordion/skins_init', function( $widget ) {
		   $widget->add_skin( new Skin\Accordion\Skin_Accordion($widget) );
		   $widget->add_skin( new Skin\Accordion\Skin_Accordion_Two($widget) );
		} );
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
		add_action( 'elementor/elements/categories_registered', [$this,'add_elementor_widget_categories'], 12 );
		// Register widget scripts
		add_action( 'elementor/frontend/after_register_scripts', [ $this, 'widget_scripts' ] );
		add_action( 'wp_enqueue_scripts', [ $this, 'after_register_styles' ], 12 );


		// Register widgets
		add_action( 'elementor/widgets/register', [ $this, 'register_widgets' ] );

		// Register editor scripts
		add_action( 'elementor/editor/after_enqueue_scripts', [ $this, 'editor_scripts' ] );

		$this->add_page_settings_controls();
		add_action( 'elementor/init', [ $this, 'elementor_init' ], 0 );

		add_action( 'elementor/init', function () {
			require_once( __DIR__ . '/widgets/image-generator/image-generator-handler.php' );
		} );

	}

}

// Instantiate Plugin Class
Plugin::instance();
