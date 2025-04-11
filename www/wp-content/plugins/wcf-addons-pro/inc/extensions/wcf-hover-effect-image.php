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

class WCF_Hover_Effect_Image {

	public static function init() {
		add_action( 'elementor/element/container/section_layout/after_section_end', [
			__CLASS__,
			'register_hover_image_controls'
		] );
	}

	public static function enqueue_scripts() {

	}

	public static function register_hover_image_controls( $element ) {
		$element->start_controls_section(
			'_section_wcf_hover_image_area',
			[
				'label' =>  sprintf('%s <i class="wcf-logo"></i>', __('Hover effect image', 'wcf-addons-pro')),
				'tab' => Controls_Manager::TAB_LAYOUT,
			]
		);

		$element->add_control(
			'wcf_enable_hover_image',
			[
				'label'              => esc_html__( 'Enable', 'wcf-addons-pro' ),
				'type'               => Controls_Manager::SWITCHER,
				'frontend_available' => true,
				'return_value'       => 'yes',
			]
		);

		$element->add_control(
			'wcf_enable_hover_image_editor',
			[
				'label'              => esc_html__( 'Enable On Editor', 'wcf-addons-pro' ),
				'type'               => Controls_Manager::SWITCHER,
				'frontend_available' => true,
				'return_value'       => 'yes',
				'condition' => [ 'wcf_enable_hover_image!' => '' ]
			]
		);

		$element->add_control(
			'wcf_hover_image',
			[
				'label' => esc_html__( 'Choose Image', 'wcf-addons-pro' ),
				'type' => Controls_Manager::MEDIA,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
				'selectors'  => [
					'{{WRAPPER}} .wcf-image-hover' => 'background-image: url( {{URL}} );',
				],
			]
		);

		$element->add_responsive_control(
			'wcf_hover_image_width',
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
					'{{WRAPPER}} .wcf-image-hover' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$element->add_responsive_control(
			'wcf_hover_image_height',
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
					'{{WRAPPER}} .wcf-image-hover' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$element->add_responsive_control(
			'wcf_hover_image_position_top',
			[
				'label'      => esc_html__( 'Position Top', 'wcf-addons-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range'      => [
					'px' => [
						'min' => -1000,
						'max' => 1000,
					],
					'%'  => [
						'min' => -100,
						'max' => 100,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .wcf-image-hover' => 'top: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$element->add_responsive_control(
			'wcf_hover_image_position_left',
			[
				'label'      => esc_html__( 'Position Left', 'wcf-addons-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range'      => [
					'px' => [
						'min' => - 1000,
						'max' => 1000,
					],
					'%'  => [
						'min' => - 100,
						'max' => 100,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .wcf-image-hover' => 'left: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$element->add_control(
			'wcf_hover_image_zindex',
			[
				'label' => esc_html__( 'Z-index', 'wcf-addons-pro' ),
				'type'  => Controls_Manager::NUMBER,
				'min'   => - 9999,
				'max'   => 9999,
				'selectors'  => [
					'{{WRAPPER}} .wcf-image-hover' => 'z-index: {{VALUE}};',
				],
			]
		);

		$element->end_controls_section();
	}
}

WCF_Hover_Effect_Image::init();
