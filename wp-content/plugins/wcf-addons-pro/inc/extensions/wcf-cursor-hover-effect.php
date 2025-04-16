<?php
/**
 * Test Effects extension class.
 */

namespace WCFAddonsPro\Extensions;

use Elementor\Element_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Plugin;
use Elementor\Utils;

defined( 'ABSPATH' ) || die();

class WCF_Cursor_Hover_Effects {

	public static function init() {
		add_action( 'elementor/element/container/section_layout/after_section_end', [
			__CLASS__,
			'register_cursor_hover_effect_controls'
		] );

		add_action( 'elementor/element/wcf--a-portfolio/section_layout/after_section_end', [
			__CLASS__,
			'register_cursor_hover_effect_controls'
		] );
	}

	public static function enqueue_scripts() {

	}

	public static function register_cursor_hover_effect_controls( $element ) {
		$tab  = Controls_Manager::TAB_CONTENT;

		if ( 'container' === $element->get_name() ) {
			$tab = Controls_Manager::TAB_LAYOUT;
		}

		$element->start_controls_section(
			'_section_wcf_cursor_hover_area',
			[
				'label' => sprintf( '%s <i class="wcf-logo"></i>', __( 'Cursor hover effect', 'wcf-addons-pro' ) ),
				'tab'   => $tab,
			]
		);

		$element->add_control(
			'wcf_enable_cursor_hover_effect',
			[
				'label'              => esc_html__( 'Enable', 'wcf-addons-pro' ),
				'type'               => Controls_Manager::SWITCHER,
				'frontend_available' => true,
				'return_value'       => 'yes',
			]
		);

		$element->add_control(
			'wcf_enable_cursor_hover_effect_editor',
			[
				'label'              => esc_html__( 'Enable On Editor', 'wcf-addons-pro' ),
				'type'               => Controls_Manager::SWITCHER,
				'frontend_available' => true,
				'return_value'       => 'yes',
				'condition'          => [ 'wcf_enable_cursor_hover_effect!' => '' ]
			]
		);

		$element->add_control(
			'wcf_enable_cursor_hover_effect_text',
			[
				'label'              => esc_html__( 'Text', 'wcf-addons-pro' ),
				'type'               => Controls_Manager::TEXT,
				'frontend_available' => true,
				'separator'          => 'after',
				'default'            => esc_html__( 'View', 'wcf-addons-pro' ),
			]
		);

		$element->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'wcf_cursor_hover_cursor_typography',
				'selector' => '.wcf-hover-cursor-effect.active-{{ID}}',
			]
		);

		$element->add_control(
			'wcf_cursor_hover_cursor_color',
			[
				'label'     => esc_html__( 'Text Color', 'wcf-addons-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'.wcf-hover-cursor-effect.active-{{ID}}' => 'color: {{VALUE}}',
				],
			]
		);

		$element->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'wcf_cursor_hover_cursor_background',
				'types'    => [ 'classic', 'gradient' ],
				'selector' => '.wcf-hover-cursor-effect.active-{{ID}}',
			]
		);

		$element->add_responsive_control(
			'wcf_cursor_hover_cursor_width',
			[
				'label'      => esc_html__( 'Width', 'wcf-addons-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem' ],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 1000,
					],
					'%'  => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors'  => [
					'.wcf-hover-cursor-effect.active-{{ID}}' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$element->add_responsive_control(
			'wcf_cursor_hover_cursor_height',
			[
				'label'      => esc_html__( 'Height', 'wcf-addons-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem' ],
				'separator'  => 'after',
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 1000,
					],
					'%'  => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors'  => [
					'.wcf-hover-cursor-effect.active-{{ID}}' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$element->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'wcf_cursor_hover_cursor_border',
				'selector' => '.wcf-hover-cursor-effect.active-{{ID}}',
			]
		);

		$element->add_control(
			'wcf_cursor_hover_cursor_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'wcf-addons-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors'  => [
					'.wcf-hover-cursor-effect.active-{{ID}}' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$element->end_controls_section();
	}
}

WCF_Cursor_Hover_Effects::init();
