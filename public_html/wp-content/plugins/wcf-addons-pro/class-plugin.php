<?php

namespace WCFAddonsPro;

use Elementor\Plugin as ElementorPlugin;

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
		//widget scripts
		foreach ( self::get_widget_scripts() as $key => $script ) {
			wp_register_script( $script['handler'], plugins_url( '/assets/js/' . $script['src'], __FILE__ ), $script['dep'], $script['version'], $script['arg'] );
		}

		wp_enqueue_script( 'wcf--addons-pro' );
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

		wp_enqueue_style( 'wcf--addons-pro' );
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
			'wcf-addons-core'        => [
				'handler' => 'wcf--addons-pro',
				'src'     => 'wcf-addons-pro.js',
				'dep'     => [ 'wcf--addons' ],
				'version' => false,
				'arg'     => true,
			],
			'advance-slider'         => [
				'handler' => 'wcf--advance-slider',
				'src'     => 'advance-slider.js',
				'dep'     => [],
				'version' => false,
				'arg'     => true,
			],
			'advance-slider-effects' => [
				'handler' => 'advance-slider-effects',
				'src'     => 'advance-slider-effects.js',
				'dep'     => [],
				'version' => false,
				'arg'     => true,
			],
			'filterable-slider'      => [
				'handler' => 'wcf--filterable-slider',
				'src'     => 'filterable-slider.js',
				'dep'     => [],
				'version' => false,
				'arg'     => true,
			],
			'advance-accordion'      => [
				'handler' => 'wcf--a-accordion',
				'src'     => 'advance-accordion.js',
				'dep'     => [],
				'version' => false,
				'arg'     => true,
			],
		];
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
			'wcf-addons-pro'        => [
				'handler' => 'wcf--addons-pro',
				'src'     => 'wcf-addons-pro.css',
				'dep'     => [],
				'version' => false,
				'media'   => 'all',
			],
			'advance-pricing-table' => [
				'handler' => 'wcf--advance-pricing-table',
				'src'     => 'widgets/advance-pricing-table.css',
				'dep'     => [],
				'version' => false,
				'media'   => 'all',
			],
			'advance-portfolio'     => [
				'handler' => 'wcf--advance-portfolio',
				'src'     => 'widgets/advance-portfolio.css',
				'dep'     => [],
				'version' => false,
				'media'   => 'all',
			],
			'scroll-elements'       => [
				'handler' => 'wcf--scroll-elements',
				'src'     => 'widgets/scroll-elements.css',
				'dep'     => [],
				'version' => false,
				'media'   => 'all',
			],
			'toggle-switcher'       => [
				'handler' => 'wcf--toggle-switch',
				'src'     => 'widgets/toggle-switch.css',
				'dep'     => [],
				'version' => false,
				'media'   => 'all',
			],
			'filterable-gallery'    => [
				'handler' => 'wcf--filterable-gallery',
				'src'     => 'widgets/filterable-gallery.css',
				'dep'     => [],
				'version' => false,
				'media'   => 'all',
			],
			'table-of-content'      => [
				'handler' => 'wcf--table-of-content',
				'src'     => 'widgets/table-of-content.css',
				'dep'     => [],
				'version' => false,
				'media'   => 'all',
			],
			'image-accordion'       => [
				'handler' => 'wcf--image-accordion',
				'src'     => 'widgets/image-accordion.css',
				'dep'     => [],
				'version' => false,
				'media'   => 'all',
			],
			'author-box'            => [
				'handler' => 'wcf--author-box',
				'src'     => 'widgets/author-box.css',
				'dep'     => [],
				'version' => false,
				'media'   => 'all',
			],
			'flip-box'              => [
				'handler' => 'wcf--flip-box',
				'src'     => 'widgets/flip-box.css',
				'dep'     => [],
				'version' => false,
				'media'   => 'all',
			],
			'advance-slider'        => [
				'handler' => 'wcf--advance-slider',
				'src'     => 'widgets/advance-slider.css',
				'dep'     => [],
				'version' => false,
				'media'   => 'all',
			],
			'filterable-slider'        => [
				'handler' => 'wcf--filterable-slider',
				'src'     => 'widgets/filterable-slider.css',
				'dep'     => [],
				'version' => false,
				'media'   => 'all',
			],
			'advance-accordion'        => [
				'handler' => 'wcf--a-accordion',
				'src'     => 'widgets/advance-accordion.css',
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
				$class = 'WCFAddonsPro\\Widgets\\' . $class;
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
		$extensions = [
			'extensions' => [
				'general-extensions' => [
					'title'    => __( 'General Extension', 'wcf-addons-pro' ),
					'elements' => [
						'wrapper-link'     => [
							'label'        => esc_html__( 'Wrapper Link', 'wcf-addons-pro' ),
							'is_pro'       => true,
							'is_extension' => false,
							'is_upcoming'  => false,
							'demo_url'     => '',
							'doc_url'      => '',
							'youtube_url'  => '',
						],
						'tilt-effect'      => [
							'label'        => esc_html__( 'Tilt Effect', 'wcf-addons-pro' ),
							'is_pro'       => true,
							'is_extension' => false,
							'is_upcoming'  => false,
							'demo_url'     => '',
							'doc_url'      => '',
							'youtube_url'  => '',
						],
						'advanced-tooltip' => [
							'label'        => esc_html__( 'Advanced Tooltip', 'wcf-addons-pro' ),
							'is_pro'       => true,
							'is_extension' => false,
							'is_upcoming'  => false,
							'demo_url'     => '',
							'doc_url'      => '',
							'youtube_url'  => '',
						],
					]
				],
				'gsap-extensions'    => [
					'title'    => __( 'Gsap Extension', 'wcf-addons-pro' ),
					'elements' => [
						'cursor-hover-effect' => [
							'label'        => esc_html__( 'Cursor Hover Effect', 'wcf-addons-pro' ),
							'is_pro'       => true,
							'is_extension' => false,
							'is_upcoming'  => false,
							'demo_url'     => '',
							'doc_url'      => '',
							'youtube_url'  => '',
						],
						'horizontal-scroll'   => [
							'label'        => esc_html__( 'Horizontal', 'wcf-addons-pro' ),
							'is_pro'       => true,
							'is_extension' => false,
							'is_upcoming'  => false,
							'demo_url'     => '',
							'doc_url'      => '',
							'youtube_url'  => '',
						],
						'hover-effect-image'  => [
							'label'        => esc_html__( 'Hover Effect Image', 'wcf-addons-pro' ),
							'is_pro'       => true,
							'is_extension' => false,
							'is_upcoming'  => false,
							'demo_url'     => '',
							'doc_url'      => '',
							'youtube_url'  => '',
						],
						'cursor-move-effect' => [
							'label'        => esc_html__( 'Cursor Move Effect', 'wcf-addons-pro' ),
							'is_pro'       => true,
							'is_extension' => false,
							'is_upcoming'  => false,
							'demo_url'     => '',
							'doc_url'      => '',
							'youtube_url'  => '',
						],
					]
				],
			],
		];

		$allextensions = [];
		foreach ( $extensions['extensions'] as $index => $extension ) {
			//if gsap not enbale
			if ( 'gsap-extensions' === $index && ! wcf_addons_get_settings( 'wcf_save_extensions', 'wcf-gsap' ) ) {
				continue;
			}

			$allextensions = array_merge( $allextensions, $extension['elements'] );
		}

		foreach ( $allextensions as $slug => $data ) {

			// If upcoming don't register.
			if ( $data['is_upcoming'] ) {
				continue;
			}

			if ( $data['is_pro'] ) {
				include_once WCF_ADDONS_PRO_PATH . 'inc/extensions/wcf-' . $slug . '.php';
			}
		}
	}

	/**
	 * Include Widgets skins
	 *
	 * Load widgets skins
	 *
	 * @since 0.0.1
	 * @access private
	 */
	private function include_skins_files() {
		foreach ( self::get_widget_Skins() as $slug => $data ) {

			//is widget all skins are not active
			if ( ! $data['is_active'] ) {
				continue;
			}

			foreach ( $data['skins'] as $skin_slug => $skin ) {
				if ( ! $skin['is_active'] ) {
					continue;
				}

				require_once( WCF_ADDONS_PRO_WIDGETS_PATH . $slug .'/skins/'.$skin_slug.'.php' );

				$class = explode( '-', $skin_slug );
				$class = array_map( 'ucfirst', $class );
				$class = implode( '_', $class );
				$class = 'WCFAddonsPro\\Widgets\\Skin\\' . $class;

				//has base base skin dont need register
				if ( isset( $skin['is_base_skin'] ) ){
					continue;
				}

				add_action( 'elementor/widget/' . $data['widget_name'] . '/skins_init', function ( $widget ) use ( $class ) {
					$widget->add_skin( new $class( $widget ) );
				} );
			}
		}

	}

	/**
	 * Get Widgets List.
	 *
	 * @return array
	 */
	public static function get_widgets() {
		return [
			'toggle-switcher'       => [
				'label'       => __( 'Toggle Switcher', 'wcf-addons-pro' ),
				'is_active'   => true,
				'is_upcoming' => false,
				'demo_url'    => '',
				'doc_url'     => '',
				'youtube_url' => '',
			],
			'advance-pricing-table' => [
				'label'       => __( 'Advance Pricing Table', 'wcf-addons-pro' ),
				'is_active'   => true,
				'is_upcoming' => false,
				'demo_url'    => '',
				'doc_url'     => '',
				'youtube_url' => '',
			],
			'scroll-elements'       => [
				'label'       => __( 'Scroll Elements', 'wcf-addons-pro' ),
				'is_active'   => true,
				'is_upcoming' => false,
				'demo_url'    => '',
				'doc_url'     => '',
				'youtube_url' => '',
			],
			'advance-portfolio'     => [
				'label'       => __( 'Advance Portfolio', 'wcf-addons-pro' ),
				'is_active'   => true,
				'is_upcoming' => false,
				'demo_url'    => '',
				'doc_url'     => '',
				'youtube_url' => '',
			],
			'filterable-gallery'    => [
				'label'       => __( 'Filterable Gallery', 'wcf-addons-pro' ),
				'is_active'   => true,
				'is_upcoming' => false,
				'demo_url'    => '',
				'doc_url'     => '',
				'youtube_url' => '',
			],
			'breadcrumbs'           => [
				'label'       => __( 'Breadcrumbs', 'wcf-addons-pro' ),
				'is_active'   => true,
				'is_upcoming' => false,
				'demo_url'    => '',
				'doc_url'     => '',
				'youtube_url' => '',
			],
			'table-of-contents'     => [
				'label'       => __( 'Table Of Content', 'wcf-addons-pro' ),
				'is_active'   => true,
				'is_upcoming' => false,
				'demo_url'    => '',
				'doc_url'     => '',
				'youtube_url' => '',
			],
			'image-accordion'       => [
				'label'       => __( 'Image Accordion', 'wcf-addons-pro' ),
				'is_active'   => true,
				'is_upcoming' => false,
				'demo_url'    => '',
				'doc_url'     => '',
				'youtube_url' => '',
			],
			'author-box'            => [
				'label'       => __( 'Author Box', 'wcf-addons-pro' ),
				'is_active'   => true,
				'is_upcoming' => false,
				'demo_url'    => '',
				'doc_url'     => '',
				'youtube_url' => '',
			],
			'flip-box'              => [
				'label'       => __( 'Flip Box', 'wcf-addons-pro' ),
				'is_active'   => true,
				'is_upcoming' => false,
				'demo_url'    => '',
				'doc_url'     => '',
				'youtube_url' => '',
			],
			'advance-slider'        => [
				'label'       => __( 'Advance Slider', 'wcf-addons-pro' ),
				'is_active'   => true,
				'is_upcoming' => false,
				'demo_url'    => '',
				'doc_url'     => '',
				'youtube_url' => '',
			],
			'filterable-slider'     => [
				'label'       => __( 'Filterable Slider', 'wcf-addons-pro' ),
				'is_active'   => true,
				'is_upcoming' => false,
				'demo_url'    => '',
				'doc_url'     => '',
				'youtube_url' => '',
			],
			'advance-accordion'     => [
				'label'       => __( 'Advance Accordion', 'wcf-addons-pro' ),
				'is_active'   => true,
				'is_upcoming' => false,
				'demo_url'    => '',
				'doc_url'     => '',
				'youtube_url' => '',
			],
		];
	}

	/**
	 * Get Widget Skins List.
	 *
	 * @return array
	 */
	public static function get_widget_Skins() {
		return apply_filters( 'wcf_widget_skins', [
			'advance-pricing-table' => [ //widget file/dir name
				'label'       => __( 'Advance Pricing Table', 'wcf-addons-pro' ),
				'widget_name' => 'wcf--a-pricing-table',
				'is_active'   => true,
				'skins'       => [//skin file names
					'skin-pricing-table-base' => [ 'is_active' => true, 'is_base_skin' => true ],
					'skin-pricing-table-1'    => [ 'is_active' => true ],
					'skin-pricing-table-2'    => [ 'is_active' => true ],
				]
			],
			'advance-portfolio'     => [ //widget file/dir name
				'label'       => __( 'Advance Portfolio', 'wcf-addons-pro' ),
				'widget_name' => 'wcf--a-portfolio',
				'is_active'   => true,
				'skins'       => [
					'skin-portfolio-base'  => [ 'is_active' => true, 'is_base_skin' => true ],
					'skin-portfolio-one'   => [ 'is_active' => true ],
					'skin-portfolio-two'   => [ 'is_active' => true ],
					'skin-portfolio-three' => [ 'is_active' => true ],
					'skin-portfolio-four'  => [ 'is_active' => true ],
					'skin-portfolio-five'  => [ 'is_active' => true ],
					'skin-portfolio-six'   => [ 'is_active' => true ],
					'skin-portfolio-seven' => [ 'is_active' => true ],
					'skin-portfolio-eight' => [ 'is_active' => true ],
					'skin-portfolio-nine' => [ 'is_active' => true ],
				]
			],
		] );
	}

	public function widget_categories( $elements_manager ) {
		$categories = [];

		$categories['wcf-addons-pro'] = [
			'title' => esc_html__( 'WCF Pro', 'wcf-addons-pro' ),
			'icon'  => 'fa fa-plug',
		];

		$old_categories = $elements_manager->get_categories();
		$categories     = array_merge( $categories, $old_categories );

		$set_categories = function ( $categories ) {
			$this->categories = $categories;
		};

		$set_categories->call( $elements_manager, $categories );
	}

	/**
	 * Include Plugin files
	 *
	 * @access private
	 */
	private function include_files() {
		require_once WCF_ADDONS_PRO_PATH . 'inc/helper.php';
		require_once WCF_ADDONS_PRO_PATH . 'inc/hook.php';
		require_once WCF_ADDONS_PRO_PATH . 'inc/global-elements.php';
		require_once WCF_ADDONS_PRO_PATH . 'inc/mega-menu/init.php';

		//extensions
		$this->register_extensions();
	}

	/**
	 * Initialize the elementor plugin
	 *
	 * Validates that Elementor is already loaded.
	 * Checks for basic plugin requirements, if one check fail don't continue,
	 *
	 * Fired by `plugins_loaded` action hook.
	 *
	 * @since 1.2.0
	 * @access public
	 */
	public function elementor_init() {
		add_action( 'elementor/kit/register_tabs', [$this, 'register_setting_tabs'] );

		$this->include_skins_files();
	}

	public function register_setting_tabs( $base ) {
		$them_settings = [
			'preloader'        => [
				'label'       => __( 'Preloader', 'wcf-addons-pro' ),
				'is_active'   => true,
				'is_pro'      => true,
				'is_upcoming' => false,
				'demo_url'    => '',
				'doc_url'     => '',
				'youtube_url' => '',
			],
			'cursor'           => [
				'label'       => __( 'Cursor', 'wcf-addons-pro' ),
				'is_active'   => true,
				'is_pro'      => true,
				'is_upcoming' => false,
				'demo_url'    => '',
				'doc_url'     => '',
				'youtube_url' => '',
			],
			'scroll-to-top'    => [
				'label'       => __( 'Scroll to Top', 'wcf-addons-pro' ),
				'is_active'   => true,
				'is_pro'      => true,
				'is_upcoming' => false,
				'demo_url'    => '',
				'doc_url'     => '',
				'youtube_url' => '',
			],
			'scroll-indicator' => [
				'label'       => __( 'Scroll Indicator', 'wcf-addons-pro' ),
				'is_active'   => true,
				'is_pro'      => true,
				'is_upcoming' => false,
				'demo_url'    => '',
				'doc_url'     => '',
				'youtube_url' => '',
			],
		];

		foreach ( $them_settings as $slug => $data ) {

			// If upcoming don't register.
			if ( $data['is_upcoming'] ) {
				continue;
			}

			// If not pro don't register.
			if ( ! $data['is_pro'] ) {
				continue;
			}

			if ( $data['is_active'] ) {
				if ( is_dir( WCF_ADDONS_PRO_PATH . 'inc/settings/wcf-' . $slug ) ) {
					require_once( WCF_ADDONS_PRO_PATH . 'inc/settings/wcf-' . $slug . '/wcf-' . $slug . '.php' );
				} else {
					require_once( WCF_ADDONS_PRO_PATH . 'inc/settings/wcf-' . $slug . '.php' );
				}

				$key = 'settings-wcf-' . $slug;

				$class = explode( '-', $slug );
				$class = array_map( 'ucfirst', $class );
				$class = implode( '_', $class );
				$class = 'WCFAddonsPro\\Settings\\Tabs\\' . $class;
				$base->register_tab( $key, $class );
			}
		}
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
		add_action( 'elementor/elements/categories_registered', [ $this, 'widget_categories' ] );

		// Register widget scripts
		add_action( 'wp_enqueue_scripts', [ $this, 'widget_scripts' ] );

		// Register widget style
		add_action( 'wp_enqueue_scripts', [ $this, 'widget_styles' ] );

		// Register widgets
		add_action( 'elementor/widgets/register', [ $this, 'register_widgets' ] );

		// Register editor scripts
		add_action( 'elementor/editor/after_enqueue_scripts', [ $this, 'editor_scripts' ] );

		// elementor loaded
		add_action( 'elementor/init', [ $this, 'elementor_init' ], 0 );

		$this->include_files();
	}
}

// Instantiate Plugin Class
Plugin::instance();
