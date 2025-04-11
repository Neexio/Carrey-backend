<?php
/**
 * Horizontal Scroll extension class.
 */

namespace WCFAddonsPro\Extensions;

use Elementor\Element_Base;
use Elementor\Controls_Manager;
use Elementor\Plugin;
use Elementor\Utils;

defined( 'ABSPATH' ) || die();

class WCF_Tilt {

	public static function init() {
		add_action( 'elementor/element/container/section_layout/after_section_end', [
			__CLASS__,
			'register_tilt_controls'
		] );
	}

	public static function enqueue_scripts() {

	}

	public static function register_tilt_controls( $element ) {

		$element->start_controls_section(
			'_section_wcf_tilt_area',
			[
				'label' => sprintf( '%s <i class="wcf-logo"></i>', __( 'Tilt', 'wcf-addons-pro' ) ),
				'tab'   => Controls_Manager::TAB_ADVANCED,
			]
		);

		$element->add_control(
			'wcf_enable_tilt',
			[
				'label'              => esc_html__( 'Enable', 'wcf-addons-pro' ),
				'type'               => Controls_Manager::SWITCHER,
				'render_type'  => 'template',
				'frontend_available' => true,
				'return_value'       => 'yes',
			]
		);

		$element->add_control(
			'wcf_enable_tilt_editor',
			[
				'label'              => esc_html__( 'Enable On Editor', 'wcf-addons-pro' ),
				'description'        => esc_html__( 'For better performance in editor mode, keep the setting turned off.', 'wcf-addons-pro' ),
				'type'               => Controls_Manager::SWITCHER,
				'frontend_available' => true,
				'return_value'       => 'yes',
				'condition'          => [
					'wcf_enable_tilt!' => '',
				],
			]
		);

		$element->add_control(
			'wcf_max_tilt',
			[
				'label'              => esc_html__( 'maxTilt', 'wcf-addons-pro' ),
				'type'               => Controls_Manager::NUMBER,
				'min'                => 5,
				'max'                => 50,
				'default'            => 20,
				'frontend_available' => true,
				'condition'          => [ 'wcf_enable_tilt!' => '' ]
			]
		);

		$element->add_control(
			'wcf_tilt_perspective',
			[
				'label'              => esc_html__( 'Perspective', 'wcf-addons-pro' ),
				'type'               => Controls_Manager::NUMBER,
				'default'            => 1000,
				'frontend_available' => true,
				'condition'          => [ 'wcf_enable_tilt!' => '' ]
			]
		);

		$element->add_control(
			'wcf_tilt_scale',
			[
				'label'              => esc_html__( 'Scale', 'wcf-addons-pro' ),
				'type'               => Controls_Manager::NUMBER,
				'min'                => 1,
				'max'                => 10,
				'default'            => 1,
				'frontend_available' => true,
				'condition'          => [ 'wcf_enable_tilt!' => '' ]
			]
		);

		$element->add_control(
			'wcf_tilt_speed',
			[
				'label'              => esc_html__( 'Speed', 'animation-addons-for-elementor' ),
				'type'               => Controls_Manager::NUMBER,
				'default'            => 3000,
				'frontend_available' => true,
				'condition'          => [ 'wcf_enable_tilt!' => '' ]
			]
		);

		$element->end_controls_section();
	}
}

WCF_Tilt::init();
