<?php
/**
 * Animation Effects extension class.
 */

namespace WCFAddonsEX\Extensions;

use Elementor\Controls_Manager;

defined( 'ABSPATH' ) || die();

class WCF_Popup {

	public static function init() {
		//popup controls
		add_action( 'elementor/element/container/section_layout/after_section_end', [
			__CLASS__,
			'register_popup_controls'
		] );
	}

	public static function register_popup_controls( $element ) {
		$element->start_controls_section(
			'_section_wcf_popup_area',
			[
				'label' => sprintf( '%s <i class="wcf-logo"></i>', __( 'Popup', 'extension-for-animation-addons' ) ),
				'tab'   => Controls_Manager::TAB_LAYOUT,
			]
		);

		$element->add_control(
			'wcf_enable_popup',
			[
				'label'              => esc_html__( 'Enable Popup', 'extension-for-animation-addons' ),
				'type'               => Controls_Manager::SWITCHER,
				'frontend_available' => true,
				'return_value'       => 'yes',
			]
		);

		$element->add_control(
			'wcf_enable_popup_editor',
			[
				'label'              => esc_html__( 'Enable On Editor', 'extension-for-animation-addons' ),
				'type'               => Controls_Manager::SWITCHER,
				'frontend_available' => true,
				'return_value'       => 'yes',
				'condition'          => [ 'wcf_enable_popup!' => '' ]
			]
		);

		$element->add_control(
			'popup_content_type',
			[
				'label'     => esc_html__( 'Content Type', 'extension-for-animation-addons' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => [
					'content'  => esc_html__( 'Content', 'extension-for-animation-addons' ),
					'template' => esc_html__( 'Saved Templates', 'extension-for-animation-addons' ),
				],
				'default'   => 'content',
				'condition' => [ 'wcf_enable_popup!' => '' ]
			]
		);

		$element->add_control(
			'popup_elementor_templates',
			[
				'label'       => esc_html__( 'Save Template', 'extension-for-animation-addons' ),
				'type'        => Controls_Manager::SELECT2,
				'label_block' => false,
				'multiple'    => false,
				'options'     => wcf_addons_get_saved_template_list(),
				'condition'   => [
					'popup_content_type' => 'template',
					'wcf_enable_popup!'  => '',
				],
			]
		);

		$element->add_control(
			'popup_content',
			[
				'label'     => esc_html__( 'Content', 'extension-for-animation-addons' ),
				'default'   => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'extension-for-animation-addons' ),
				'type'      => Controls_Manager::WYSIWYG,
				'condition' => [
					'popup_content_type' => 'content',
					'wcf_enable_popup!'  => '',
				],
			]
		);

		$element->add_control(
			'popup_trigger_cursor',
			[
				'label'     => esc_html__( 'Cursor', 'extension-for-animation-addons' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'default',
				'options'   => [
					'default'  => esc_html__( 'Default', 'extension-for-animation-addons' ),
					'none'     => esc_html__( 'None', 'extension-for-animation-addons' ),
					'pointer'  => esc_html__( 'Pointer', 'extension-for-animation-addons' ),
					'grabbing' => esc_html__( 'Grabbing', 'extension-for-animation-addons' ),
					'move'     => esc_html__( 'Move', 'extension-for-animation-addons' ),
					'text'     => esc_html__( 'Text', 'extension-for-animation-addons' ),
				],
				'selectors' => [
					'{{WRAPPER}}' => 'cursor: {{VALUE}};',
				],
				'condition' => [ 'wcf_enable_popup!' => '' ],
			]
		);

		$element->add_control(
			'popup_animation',
			[
				'label'              => esc_html__( 'Animation', 'extension-for-animation-addons' ),
				'type'               => Controls_Manager::SELECT,
				'frontend_available' => true,
				'default'            => 'default',
				'options'            => [
					'default'             => esc_html__( 'Default', 'extension-for-animation-addons' ),
					'mfp-zoom-in'         => esc_html__( 'Zoom', 'extension-for-animation-addons' ),
					'mfp-zoom-out'        => esc_html__( 'Zoom-out', 'extension-for-animation-addons' ),
					'mfp-newspaper'       => esc_html__( 'Newspaper', 'extension-for-animation-addons' ),
					'mfp-move-horizontal' => esc_html__( 'Horizontal move', 'extension-for-animation-addons' ),
					'mfp-move-from-top'   => esc_html__( 'Move from top', 'extension-for-animation-addons' ),
					'mfp-3d-unfold'       => esc_html__( '3d unfold', 'extension-for-animation-addons' ),
				],
				'condition'          => [ 'wcf_enable_popup!' => '' ],
			]
		);

		$element->add_control(
			'popup_animation_delay',
			[
				'label'              => esc_html__( 'Removal Delay', 'extension-for-animation-addons' ),
				'type'               => Controls_Manager::NUMBER,
				'frontend_available' => true,
				'default'            => 500,
				'condition'          => [ 'wcf_enable_popup!' => '' ],
			]
		);

		$element->end_controls_section();
	}
}

WCF_Popup::init();
