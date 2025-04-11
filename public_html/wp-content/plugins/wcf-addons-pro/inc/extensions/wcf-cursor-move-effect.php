<?php
/**
 * Test Effects extension class.
 */

namespace WCFAddonsPro\Extensions;

use Elementor\Controls_Manager;

defined( 'ABSPATH' ) || die();

class WCF_Cursor_Move_Effects {

	public static function init() {
		add_action( 'elementor/element/common/_section_style/after_section_end', [
			__CLASS__,
			'register_mouse_move_effect_controls',
		] );

		add_action( 'elementor/element/container/section_layout/after_section_end', [
			__CLASS__,
			'register_mouse_move_effect_controls'
		] );
	}

	public static function register_mouse_move_effect_controls( $element ) {

		$element->start_controls_section(
			'_section_wcf_mouse_move_area',
			[
				'label' => sprintf( '%s <i class="wcf-logo"></i>', __( 'Mouse Move Effect', 'wcf-addons-pro' ) ),
				'tab'   => Controls_Manager::TAB_ADVANCED,
			]
		);

		$element->add_control(
			'wcf_enable_mouse_move_effect',
			[
				'label'              => esc_html__( 'Enable', 'wcf-addons-pro' ),
				'type'               => Controls_Manager::SWITCHER,
				'frontend_available' => true,
				'return_value'       => 'yes',
			]
		);

		$element->add_control(
			'wcf_mouse_move_area_trigger',
			[
				'label'              => esc_html__( 'Movement Wrapper', 'wcf-addons-pro' ),
				'type'               => Controls_Manager::SELECT,
				'default'            => '',
				'options'            => [
					''       => esc_html__( 'Default', 'wcf-addons-pro' ),
					'custom' => esc_html__( 'Custom', 'wcf-addons-pro' ),
				],
				'condition'          => [ 'wcf_enable_mouse_move_effect!' => '' ],
				'frontend_available' => true,
				'render_type'        => 'none',
			]
		);

		$element->add_control(
			'wcf_custom_mouse_move_area',
			[
				'label'              => esc_html__( 'Custom Area', 'wcf-addons-pro' ),
				'description'        => esc_html__( 'Please use the parent section or container class where the element will be movable.', 'wcf-addons-pro' ),
				'type'               => Controls_Manager::TEXT,
				'ai'                 => false,
				'placeholder'        => esc_html__( '.movement_area', 'wcf-addons-pro' ),
				'frontend_available' => true,
				'render_type'        => 'none',
				'condition'          => [
					'wcf_mouse_move_area_trigger'   => 'custom',
					'wcf_enable_mouse_move_effect!' => '',
				]
			]
		);

		$element->add_control(
			'wcf_mouse_move_x',
			[
				'label'              => esc_html__( 'Move X', 'wcf-addons-pro' ),
				'type'               => Controls_Manager::NUMBER,
				'default'            => 70,
				'frontend_available' => true,
				'condition'          => [ 'wcf_enable_mouse_move_effect!' => '' ],
				'render_type'        => 'none',
			]
		);

		$element->add_control(
			'wcf_mouse_move_y',
			[
				'label'              => esc_html__( 'Move Y', 'wcf-addons-pro' ),
				'type'               => Controls_Manager::NUMBER,
				'default'            => 70,
				'frontend_available' => true,
				'condition'          => [ 'wcf_enable_mouse_move_effect!' => '' ],
				'render_type'        => 'none',
			]
		);

		$element->add_control(
			'wcf_mouse_move_duration',
			[
				'label'              => esc_html__( 'Duration', 'wcf-addons-pro' ),
				'type'               => Controls_Manager::NUMBER,
				'default'            => 0.5,
				'render_type'        => 'none', // template
				'condition'          => [
					'wcf_enable_mouse_move_effect!' => '',
				],
				'frontend_available' => true,
			]
		);

		$element->add_control(
			'wcf_mouse_move_custom',
			[
				'label'       => esc_html__( 'Customs', 'wcf-addons-pro' ),
				'type'        => Controls_Manager::TEXTAREA,
				'rows'        => 5,
				'placeholder' => esc_html__( 'property:value, property2:value2', 'wcf-addons-pro' ),
				'render_type' => 'none', // template
				'frontend_available' => true,
				'condition'   => [
					'wcf_enable_mouse_move_effect!' => '',
				],
			]
		);

		$element->end_controls_section();
	}
}

WCF_Cursor_Move_Effects::init();
