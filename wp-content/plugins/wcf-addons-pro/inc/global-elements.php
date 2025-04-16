<?php

namespace WCF_ADDONS;

use Elementor\Controls_Manager;
use Elementor\Plugin;

defined( 'ABSPATH' ) || die();

class Global_Elements {

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
	 *  Plugin class constructor
	 *
	 * Register plugin action hooks and filters
	 *
	 * @since 1.2.0
	 * @access public
	 */
	public function __construct() {
		add_action( 'wp_footer', [ $this, 'render_scroll_to_top' ] );
		add_action( 'wp_body_open', [ $this, 'render_preloader' ], - 1 );
		add_action( 'wp_footer', [ $this, 'render_scroll_indicator' ] );
		add_action( 'wp_footer', [ $this, 'render_cursor' ] );
		add_filter( 'body_class', [ $this, 'body_classes' ] );

		//header
		add_action( 'elementor/element/after_section_end', [ $this, 'register_header_options' ], 250, 2 );
		add_action( 'wcf_header_builder_content', [ $this, 'header_settings' ] );
	}

	public function body_classes( $classes ) {

		$settings = $this->get_site_settings();

		if ( empty( $settings ) ) {
			return $classes;
		}

		//preloader active class
		if ( ! empty( $settings['wcf_enable_preloader'] ) ) {
			$classes[] = 'wcf-preloader-active';
		}

		return $classes;
	}

	private function get_site_settings() {
		$settings = [];

		$kit = Plugin::$instance->documents->get( Plugin::$instance->kits_manager->get_active_id(), false );

		if ( $kit ) {
			$settings = $kit->get_settings();
		}

		return $settings;
	}

	public function header_settings() {
		$template_id   = WCF_Theme_Builder::$_instance->get_template_id( 'header' );
		$document      = Plugin::instance()->documents->get( $template_id );
		$settings_data = [];

		if ( is_object( $document ) ) {
			$settings_data = $document->get_settings();
		}

		if ( empty( $settings_data) ){
			return;
		}

		if ( empty( $settings_data['wcf_header_sticky'] ) ) {
			return;
		}

		?>
		<script id="header-<?php echo esc_attr( $template_id ) ?>">
            let header = document.getElementsByClassName("elementor-<?php echo esc_attr( $template_id ) ?>")[0];

            window.wcf_header_settings = {
                'id': <?php echo esc_attr( $template_id ) ?>,
            }

            document.addEventListener("DOMContentLoaded", (event) => {
                let headerHeight = header.offsetHeight;
                addEventListener("scroll", (event) => {
                    if (document.body.scrollTop >= headerHeight || document.documentElement.scrollTop >= headerHeight) {
                        header.classList.add('wcf-sticky-header');
                    } else {
                        header.classList.remove('wcf-sticky-header');
                    }
                });
            });

		</script>
		<style id="header-<?php echo esc_attr( $template_id ) ?>-style">
            <?php $zIndxe = isset($settings_data['wcf_header_sticky_z_index'] ) ? $settings_data['wcf_header_sticky_z_index'] : 99; ?>

            .elementor-<?php echo esc_attr( $template_id ) ?> {
                z-index: <?php echo esc_attr( $zIndxe )?>;
            }

            <?php if ( !wcf_addons_get_settings( 'wcf_save_extensions', 'wcf-smooth-scroller' ) ) { ?>
            @keyframes wcfHeaderSlideDown {
                from {
                    transform: translateY(-100%);
                }
                to {
                    transform: translateY(0);
                }
            }

            .wcf-sticky-header {
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                width: 100%;
                animation: wcfHeaderSlideDown 0.5s ease-out;
            }

            <?php } ?>
		</style>
		<?php

	}

	public function register_header_options( $element, $section_id ) {
		if ( 'document_settings' !== $section_id ) {
			return;
		}

		$tmpType = get_post_meta( get_the_ID(), 'wcf-addons-template-meta_type', true );

		if ( 'header' !== $tmpType ) {
			return;
		}


		// Header Design Options
		$element->start_controls_section(
			'wcf_header_options',
			array(
				'label' => sprintf( '%s <i class="wcf-logo"></i>', __( 'Header Options', 'wcf-addons-pro' ) ),
				'tab'   => Controls_Manager::TAB_SETTINGS,
			)
		);

		$element->add_control(
			'wcf_header_sticky',
			[
				'label' => __( 'Enable sticky header?', 'wcf-addons-pro' ),
				'type'  => Controls_Manager::SWITCHER,
			]
		);

		$element->add_control(
			'wcf_header_sticky_z_index',
			[
				'label'     => esc_html__( 'Z-index', 'wcf-addons-pro' ),
				'type'      => Controls_Manager::NUMBER,
				'min'       => 1,
				'max'       => 999999,
				'step'      => 5,
				'default'   => 99,
				'condition' => [
					'wcf_header_sticky!' => ''
				]
			]
		);

		$element->end_controls_section();

	}

	public function render_cursor() {

		$settings = $this->get_site_settings();

		if ( empty( $settings ) ) {
			return;
		}

		if ( empty( $settings['wcf_enable_cursor'] ) ) {
			return;
		}

		$this->scroll_cursor_global_css( $settings );

		$html = '<div class="wcf-cursor"></div><div class="wcf-cursor-follower"></div>';

		printf( '%1$s', $html );

	}

	public function render_scroll_to_top() {

		$settings = $this->get_site_settings();

		if ( empty( $settings ) ) {
			return;
		}

		if ( empty( $settings['wcf_enable_scroll_to_top'] ) ) {
			return;
		}

		$this->scroll_to_top_global_css( $settings );

		$html = '';
		$icon = \Elementor\Icons_Manager::try_get_icon_html( $settings['scroll_to_icon'], [ 'aria-hidden' => 'true' ] );

		$progressCircle = '';
		if ( 'circle' === $settings['wcf_scroll_to_top_layout'] ) {
			$progressCircle = '<svg class="progress-circle svg-content" width="100%" height="100%" viewBox="-1 -1 102 102"><path d="M50,1 a49,49 0 0,1 0,98 a49,49 0 0,1 0,-98" /></svg>';
		}

		$html .= "<div class='wcf-scroll-to-top scroll-to-" . $settings['wcf_scroll_to_top_layout'] . "'>" . $progressCircle . $icon . "</div>";

		printf( '%1$s', $html );

	}

	public function render_preloader() {

		$settings = $this->get_site_settings();

		if ( empty( $settings ) ) {
			return;
		}

		if ( empty( $settings['wcf_enable_preloader'] ) ) {
			return;
		}

		$this->scroll_preloader_global_css( $settings );

		$html = '';

		//preloader whirlpool
		if ( 'whirlpool' === $settings['wcf_preloader_layout'] ) {
			$html = '<div class="whirlpool"></div>';
		}

		//preloader whirlpool
		if ( 'floating-circles' === $settings['wcf_preloader_layout'] ) {
			$html = '<div class="floating-circles">
              <div class="f_circleG" id="frotateG_01"></div>
              <div class="f_circleG" id="frotateG_02"></div>
              <div class="f_circleG" id="frotateG_03"></div>
              <div class="f_circleG" id="frotateG_04"></div>
              <div class="f_circleG" id="frotateG_05"></div>
              <div class="f_circleG" id="frotateG_06"></div>
              <div class="f_circleG" id="frotateG_07"></div>
              <div class="f_circleG" id="frotateG_08"></div>
            </div>';
		}

		//eight-spinning
		if ( 'eight-spinning' === $settings['wcf_preloader_layout'] ) {
			$html = '<div class="eight-spinning">
              <div class="cssload-lt"></div>
              <div class="cssload-rt"></div>
              <div class="cssload-lb"></div>
              <div class="cssload-rb"></div>
            </div>';
		}

		//preloader double-torus
		if ( 'double-torus' === $settings['wcf_preloader_layout'] ) {
			$html = '<div class="double-torus"></div>';
		}

		//preloader tube-tunnel
		if ( 'tube-tunnel' === $settings['wcf_preloader_layout'] ) {
			$html = '<div class="tube-tunnel"></div>';
		}

		//preloader speeding-wheel
		if ( 'speeding-wheel' === $settings['wcf_preloader_layout'] ) {
			$html = '<div class="speeding-wheel"></div>';
		}

		//preloader loading
		if ( 'loading' === $settings['wcf_preloader_layout'] ) {
			$html = '<div class="loading-wrapper"><div class="cssload-loading"><i></i><i></i></div></div>';
		}

		//preloader dot loading
		if ( 'dot-loading' === $settings['wcf_preloader_layout'] ) {
			$html = '<div class="dot-loading"><div class="cssload-loading"><i></i><i></i><i></i><i></i></div></div>';
		}

		//preloader fountainTextG
		if ( 'fountainTextG' === $settings['wcf_preloader_layout'] ) {
			$html = '<div class="fountainTextG">
	<div id="textG_1" class="textG">L</div>
	<div id="textG_2" class="textG">o</div>
	<div id="textG_3" class="textG">a</div>
	<div id="textG_4" class="textG">d</div>
	<div id="textG_5" class="textG">i</div>
	<div id="textG_6" class="textG">n</div>
	<div id="textG_7" class="textG">g</div>
</div>';
		}

		//preloader circle loading
		if ( 'circle-loading' === $settings['wcf_preloader_layout'] ) {
			$html = '<div class="circle-loading-wrapper"><div class="cssload-loader"></div></div>';
		}

		//preloader dot circle rotator
		if ( 'dot-circle-rotator' === $settings['wcf_preloader_layout'] ) {
			$html = '<div class="dot-circle-rotator"></div>';
		}

		//preloader bubblingG
		if ( 'bubblingG' === $settings['wcf_preloader_layout'] ) {
			$html = '<div class="bubblingG"><span id="bubblingG_1"></span><span id="bubblingG_2"></span><span id="bubblingG_3"></span></div>';
		}

		//preloader coffee
		if ( 'coffee' === $settings['wcf_preloader_layout'] ) {
			$html = '<div class="coffee"></div>';
		}

		//preloader orbit loading
		if ( 'orbit-loading' === $settings['wcf_preloader_layout'] ) {
			$html = '<div class="orbit-loading">
	<div class="cssload-inner cssload-one"></div>
	<div class="cssload-inner cssload-two"></div>
	<div class="cssload-inner cssload-three"></div>
</div>';
		}

		//preloader battery
		if ( 'battery' === $settings['wcf_preloader_layout'] ) {
			$html = '<div class="battery"><div class="cssload-liquid"></div></div>';
		}

		//preloader equalizer
		if ( 'equalizer' === $settings['wcf_preloader_layout'] ) {
			$html = '<div class="equalizer"><ul><li></li><li></li><li></li><li></li><li></li><li></li></ul></div>';
		}

		//preloader square-swapping
		if ( 'square-swapping' === $settings['wcf_preloader_layout'] ) {
			$html = '<div class="square-swapping">
	<div class="cssload-square-part cssload-square-green"></div>
	<div class="cssload-square-part cssload-square-pink"></div>
	<div class="cssload-square-blend"></div>
</div>';
		}

		//preloader jackhammer
		if ( 'jackhammer' === $settings['wcf_preloader_layout'] ) {
			$html = '<div class="jackhammer"><ul class="cssload-flex-container"><li><span class="cssload-loading"></span></li></div></div>';
		}

		$html = "<div class='wcf-preloader preloader-" . $settings['wcf_preloader_layout'] . "'>$html</div>";

		printf( '%1$s', $html );

	}

	public function render_scroll_indicator() {

		$settings = $this->get_site_settings();

		if ( empty( $settings ) ) {
			return;
		}

		if ( empty( $settings['wcf_enable_scroll_indicator'] ) ) {
			return;
		}

		$scroll_indicator = false;

		if ( 'entire-website' === $settings['wcf_scroll_indicator_display'] ) {
			$scroll_indicator = true;
		}

		if ( 'specific-pages' === $settings['wcf_scroll_indicator_display'] && ! empty( $settings['wcf_scroll_indicator_s_pages'] ) ) {
			$key = array_search( get_the_ID(), $settings['wcf_scroll_indicator_s_pages'] );
			if ( false !== $key ) {
				$scroll_indicator = true;
			}
		}

		if ( is_singular() && 'specific-s-post-types' === $settings['wcf_scroll_indicator_display'] && ! empty( $settings['wcf_scroll_indicator_s_s_post_types'] ) ) {
			$key = array_search( get_post_type(), $settings['wcf_scroll_indicator_s_s_post_types'] );
			if ( false !== $key ) {
				$scroll_indicator = true;
			}
		}

		if ( ! $scroll_indicator ) {
			return;
		}

		$this->scroll_indicator_global_css( $settings );

		$html = "<div class='wcf-scroll-indicator scroll-indicator-".$settings['wcf_scroll_indicator_position']."'><div class='indicator-bar'></div></div>";

		printf( '%1$s', $html );

	}

	/**
	 * @return string|void
	 */
	public function scroll_to_top_global_css( $settings ) {
		if ( ! is_array( $settings ) ) {
			return false;
		}

		if ( empty( $settings['wcf_enable_scroll_to_top'] ) ) {
			return false;
		}

		$stt_position             = $settings['wcf_scroll_to_top_position'];
		$stt_position_bottom_size = isset( $settings['wcf_scroll_to_top_position_bottom']['size'] ) ? $settings['wcf_scroll_to_top_position_bottom']['size'] : 5;
		$stt_position_bottom_unit = isset( $settings['wcf_scroll_to_top_position_bottom']['unit'] ) ? $settings['wcf_scroll_to_top_position_bottom']['unit'] : 'px';
		$stt_position_left_size   = isset( $settings['wcf_scroll_to_top_position_left']['size'] ) ? $settings['wcf_scroll_to_top_position_left']['size'] : 15;
		$stt_position_left_unit   = isset( $settings['wcf_scroll_to_top_position_left']['unit'] ) ? $settings['wcf_scroll_to_top_position_left']['unit'] : 'px';
		$stt_position_right_size  = isset( $settings['wcf_scroll_to_top_position_right']['size'] ) ? $settings['wcf_scroll_to_top_position_right']['size'] : 15;
		$stt_position_right_unit  = isset( $settings['wcf_scroll_to_top_position_right']['unit'] ) ? $settings['wcf_scroll_to_top_position_right']['unit'] : 'px';

		$stt_button_width_size  = isset( $settings['wcf_scroll_to_top_width']['size'] ) ? $settings['wcf_scroll_to_top_width']['size'] : 50;
		$stt_button_width_unit  = isset( $settings['wcf_scroll_to_top_width']['unit'] ) ? $settings['wcf_scroll_to_top_width']['unit'] : 'px';
		$stt_button_height_size = isset( $settings['wcf_scroll_to_top_height']['size'] ) ? $settings['wcf_scroll_to_top_height']['size'] : 50;
		$stt_button_height_unit = isset( $settings['wcf_scroll_to_top_height']['unit'] ) ? $settings['wcf_scroll_to_top_height']['unit'] : 'px';
		$stt_z_index_size       = isset( $settings['wcf_scroll_to_top_z_index']['size'] ) ? $settings['wcf_scroll_to_top_z_index']['size'] : 9999;

		$stt_icon_size_size = isset( $settings['wcf_scroll_to_top_icon_size']['size'] ) ? $settings['wcf_scroll_to_top_icon_size']['size'] : 16;
		$stt_icon_size_unit = isset( $settings['wcf_scroll_to_top_icon_size']['unit'] ) ? $settings['wcf_scroll_to_top_icon_size']['unit'] : 'px';

		$stt_button_icon_color         = isset( $settings['wcf_scroll_to_top_icon_color'] ) ? $settings['wcf_scroll_to_top_icon_color'] : '#fff';
		$stt_button_bg_color           = isset( $settings['wcf_scroll_to_top_bg_color'] ) ? $settings['wcf_scroll_to_top_bg_color'] : '#000';
		$stt_button_border_radius_size = isset( $settings['wcf_scroll_to_top_border_radius']['size'] ) ? $settings['wcf_scroll_to_top_border_radius']['size'] : 5;
		$stt_button_border_radius_unit = isset( $settings['wcf_scroll_to_top_border_radius']['unit'] ) ? $settings['wcf_scroll_to_top_border_radius']['unit'] : 'px';
		$stt_button_blend_mode         = isset( $settings['wcf_scroll_to_top_blend_mode'] ) ? $settings['wcf_scroll_to_top_blend_mode'] : 'normal';

		$stt_position_left_right_key   = $stt_position == 'bottom-left' ? 'left' : 'right';
		$stt_position_left_right_value = $stt_position == 'bottom-left' ? $stt_position_left_size . $stt_position_left_unit : $stt_position_right_size . $stt_position_right_unit;

		$scroll_to_top_global_css = "
            .wcf-scroll-to-top {
                bottom: {$stt_position_bottom_size}{$stt_position_bottom_unit};
                {$stt_position_left_right_key}: {$stt_position_left_right_value};
                width: {$stt_button_width_size}{$stt_button_width_unit};
                height: {$stt_button_height_size}{$stt_button_height_unit};
                z-index: {$stt_z_index_size};
                background-color: {$stt_button_bg_color};
                border-radius: {$stt_button_border_radius_size}{$stt_button_border_radius_unit};
                font-size: {$stt_icon_size_size}{$stt_icon_size_unit};
                color: {$stt_button_icon_color};
                fill: {$stt_button_icon_color};
                mix-blend-mode: $stt_button_blend_mode;
            }
            .wcf-scroll-to-top.scroll-to-circle {
                width: {$stt_button_width_size}{$stt_button_width_unit};
                height: {$stt_button_width_size}{$stt_button_width_unit};
            }
        ";

		wp_register_style( 'wcf-scroll-to-top', false );
		wp_enqueue_style( 'wcf-scroll-to-top' );
		wp_add_inline_style( 'wcf-scroll-to-top', $scroll_to_top_global_css );
	}

	/**
	 * @return string|void
	 */
	public function scroll_indicator_global_css( $settings ) {
		if ( ! is_array( $settings ) ) {
			return false;
		}

		if ( empty( $settings['wcf_enable_scroll_indicator'] ) ) {
			return;
		}

		$height_size        = isset( $settings['wcf_scroll_indicator_height']['size'] ) ? $settings['wcf_scroll_indicator_height']['size'] : 50;
		$height_unit        = isset( $settings['wcf_scroll_indicator_height']['unit'] ) ? $settings['wcf_scroll_indicator_height']['unit'] : 'px';
		$z_index_size       = isset( $settings['wcf_scroll_indicator_z_index'] ) ? $settings['wcf_scroll_indicator_z_index'] : 99;
		$indicator_bg_color = $settings['wcf_scroll_indicator_background'];
		$indicator_color    = $settings['wcf_scroll_indicator_color'];

		$scroll_indicator_global_css = "
            .wcf-scroll-indicator {
                height: {$height_size}{$height_unit};
                z-index: {$z_index_size};
                background-color: {$indicator_bg_color};
            }
            .wcf-scroll-indicator .indicator-bar {
                background-color: {$indicator_color};
            }
        ";

		wp_register_style( 'wcf-scroll-indicator', false );
		wp_enqueue_style( 'wcf-scroll-indicator' );
		wp_add_inline_style( 'wcf-scroll-indicator', $scroll_indicator_global_css );
	}

	/**
	 * @return string|void
	 */
	public function scroll_preloader_global_css( $settings ) {
		if ( ! is_array( $settings ) ) {
			return false;
		}

		if ( empty( $settings['wcf_enable_preloader'] ) ) {
			return;
		}

		$bg_color = $settings['wcf_preloader_background'];
		$color    = $settings['wcf_preloader_color'];
		$color2   = $settings['wcf_preloader_color2'];

		$preloader_global_css = '.wcf-preloader { ';
		if ( ! empty( $bg_color ) ) {
			$preloader_global_css .= "background-color: {$bg_color};";
		}

		if ( ! empty( $color ) ) {
			$preloader_global_css .= "--color: {$color};";
		}

		if ( ! empty( $color2 ) ) {
			$preloader_global_css .= "--color2: {$color2};";
		}

		$preloader_global_css .= '}';

		wp_register_style( 'wcf-preloader', false );
		wp_enqueue_style( 'wcf-preloader' );
		wp_add_inline_style( 'wcf-preloader', $preloader_global_css );
	}

	/**
	 * @return string|void
	 */
	public function scroll_cursor_global_css( $settings ) {
		if ( ! is_array( $settings ) ) {
			return false;
		}

		if ( empty( $settings['wcf_enable_cursor'] ) ) {
			return;
		}

		$cursor_size           = isset( $settings['wcf_cursor_size']['size'] ) ? $settings['wcf_cursor_size']['size'] . 'px' : '';
		$cursor_follower_size  = isset( $settings['wcf_cursor_follower_size']['size'] ) ? $settings['wcf_cursor_follower_size']['size'] . 'px' : '';
		$cursor_color          = $settings['wcf_cursor_color'];
		$cursor_follower_color = $settings['wcf_cursor_follower_color'];
		$blend_mode            = $settings['wcf_cursor_blend_mode'];

		$scroll_cursor_global_css = '';

		//cursor
		if ( ! empty( $cursor_size ) || ! empty( $cursor_color ) || ! empty( $blend_mode ) ) {
			$scroll_cursor_global_css .= '.wcf-cursor {';

			if ( ! empty( $cursor_size ) ) {
				$scroll_cursor_global_css .= "width: {$cursor_size};";
				$scroll_cursor_global_css .= "height: {$cursor_size};";
			}

			if ( ! empty( $cursor_color ) ) {
				$scroll_cursor_global_css .= "border-color: {$cursor_color};";
			}

			if ( ! empty( $blend_mode ) ) {
				$scroll_cursor_global_css .= "mix-blend-mode: $blend_mode;";
			}

			$scroll_cursor_global_css .= '}';
		}

		//follower
		if ( ! empty( $cursor_follower_size ) || ! empty( $cursor_follower_color ) || ! empty( $blend_mode ) ) {

			$scroll_cursor_global_css .= '.wcf-cursor-follower {';

			if ( ! empty( $cursor_follower_size ) ) {
				$scroll_cursor_global_css .= "width: {$cursor_follower_size};";
				$scroll_cursor_global_css .= "height: {$cursor_follower_size};";
			}

			if ( ! empty( $cursor_color ) ) {
				$scroll_cursor_global_css .= "background-color: {$cursor_follower_color};";
			}

			if ( ! empty( $blend_mode ) ) {
				$scroll_cursor_global_css .= "mix-blend-mode: $blend_mode;";
			}

			$scroll_cursor_global_css .= '}';
		}

		wp_register_style( 'wcf-cursor', false );
		wp_enqueue_style( 'wcf-cursor' );
		wp_add_inline_style( 'wcf-cursor', $scroll_cursor_global_css );
	}

}

Global_Elements::instance();

