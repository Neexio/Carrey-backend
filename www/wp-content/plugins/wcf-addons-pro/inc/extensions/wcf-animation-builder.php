<?php
/**
 * Animation Builder extension class.
 */

namespace WCFAddonsPro\Extensions;

use Elementor\Controls_Manager;
use Elementor\Repeater;

defined( 'ABSPATH' ) || die();

class WCF_Animation_Builder {

	public static function init() {
		add_action( 'elementor/element/common/_section_style/after_section_end', [
			__CLASS__,
			'add_controls_section'
		], 1 );

		add_action( 'elementor/element/container/section_layout/after_section_end', [
			__CLASS__,
			'add_controls_section'
		], 1 );
	}

	public static function add_controls_section( $element ) {

		$element->start_controls_section(
			'_section_wcf_animation_builder',
			[
				'label' => sprintf( '%s <i class="wcf-logo"></i>', __( 'Animation Builder', 'wcf-addons-pro' ) ),
				'tab'   => Controls_Manager::TAB_ADVANCED,
			]
		);

		$element->add_control(
			'wcf_animation_builder_enable',
			[
				'label'              => __( 'Enable?', 'wcf-addons-pro' ),
				'type'               => Controls_Manager::SWITCHER,
				'label_on'           => __( 'On', 'wcf-addons-pro' ),
				'label_off'          => __( 'Off', 'wcf-addons-pro' ),
				'return_value'       => 'enable',
				'default'            => '',
				'render_type'        => 'none', // template
				'frontend_available' => true,
			]
		);

		$element->add_control(
			'wcf_animation_builder_editor',
			[
				'label'              => esc_html__( 'Enable On Editor', 'extension-for-animation-addons' ),
				'type'               => Controls_Manager::SWITCHER,
				'frontend_available' => true,
				'return_value'       => 'yes',
				'condition'          => [ 'wcf_animation_builder_enable' => 'enable' ],
			]
		);

		$element->add_control(
			'play_animation',
			[
				'label'       => esc_html__( 'Play Animation', 'extension-for-animation-addons' ),
				'type'        => \Elementor\Controls_Manager::BUTTON,
				'separator'   => 'before',
				'button_type' => 'success',
				'text'        => esc_html__( 'Play', 'extension-for-animation-addons' ),
				'event'       => 'wcf:editor:play_animation',
				'condition'   => [
					'wcf_animation_builder_enable' => 'enable',
					'wcf_animation_builder_editor' => 'yes'
				],
			]
		);

		$element->start_controls_tabs(
			'wcf_anim_args'
		);

		self::set_arg_controls( $element );

		self::to_from_arg_controls( $element );

		$element->end_controls_tabs();

		self::scroll_trigger_controls( $element );

		$element->end_controls_section();
	}

	public static function set_arg_controls( $element ) {

		$element->start_controls_tab(
			'wcf_anim_arg_set',
			[
				'label'     => esc_html__( 'Set', 'textdomain' ),
				'condition' => [ 'wcf_animation_builder_enable' => 'enable' ],
			]
		);

		$element->add_control( 'enable_set', [
			'label'              => esc_html__( 'Enable Set', 'textdomain' ),
			'type'               => Controls_Manager::SWITCHER,
			'label_on'           => esc_html__( 'Yes', 'textdomain' ),
			'label_off'          => esc_html__( 'No', 'textdomain' ),
			'return_value'       => 'yes',
			'render_type'        => 'none', // template
			'frontend_available' => true,
			'condition'          => [ 'wcf_animation_builder_enable' => 'enable' ],
		] );

		$repeaterSet = new Repeater();

		$repeaterSet->add_control(
			'set_type',
			[
				'label'       => esc_html__( 'Parameter Types', 'textdomain' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'scale',
				'options'     => [
					'scale'    => esc_html__( 'Scale', 'textdomain' ),
					'scaleX'   => esc_html__( 'ScaleX', 'textdomain' ),
					'scaleY'   => esc_html__( 'ScaleY', 'textdomain' ),
					'x'        => esc_html__( 'X', 'textdomain' ),
					'y'        => esc_html__( 'Y', 'textdomain' ),
					'rotation' => esc_html__( 'Rotation', 'textdomain' ),
					'opacity'  => esc_html__( 'Opacity', 'textdomain' ),
					'custom'   => esc_html__( 'Custom', 'textdomain' ),
				],
				'render_type' => 'none', // template
			]
		);

		$repeaterSet->add_control(
			'set_scale',
			[
				'label'       => esc_html__( 'Scale', 'extension-for-animation-addons' ),
				'type'        => Controls_Manager::NUMBER,
				'default'     => 1,
				'condition'   => [ 'set_type' => [ 'scale', 'scaleX', 'scaleY' ] ],
				'render_type' => 'none', // template
			]
		);

		$repeaterSet->add_control(
			'set_transform',
			[
				'label'       => esc_html__( 'Transform', 'extension-for-animation-addons' ),
				'type'        => Controls_Manager::NUMBER,
				'default'     => 1,
				'condition'   => [ 'set_type' => [ 'x', 'y', 'rotation' ] ],
				'render_type' => 'none', // template
			]
		);

		$repeaterSet->add_control(
			'set_opacity',
			[
				'label'       => esc_html__( 'Opacity', 'extension-for-animation-addons' ),
				'type'        => Controls_Manager::NUMBER,
				'min'         => 0,
				'max'         => 1,
				'step'        => 0.1,
				'render_type' => 'none', // template
				'condition'   => [ 'set_type' => 'opacity' ],
			]
		);

		$repeaterSet->add_control(
			'set_custom',
			[
				'label'       => esc_html__( 'Custom', 'textdomain' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'key|value', 'textdomain' ),
				'render_type' => 'none', // template
				'condition'   => [ 'set_type' => 'custom' ],
			]
		);

		$element->add_control(
			'set_list',
			[
				'label'              => esc_html__( 'Arguments', 'animation-addons-for-elementor' ),
				'type'               => Controls_Manager::REPEATER,
				'fields'             => $repeaterSet->get_controls(),
				'default'            => [ [] ],
				'title_field'        => '{{{ set_type }}}',//phpcs:ignore
				'frontend_available' => true,
				'render_type'        => 'none', // template
				'condition'          => [ 'enable_set!' => '' ]
			]
		);

		$element->end_controls_tab();
	}

	public static function to_from_arg_controls( $element ) {

		$element->start_controls_tab(
			'wcf_anim_arg_to',
			[
				'label'     => esc_html__( 'To/From', 'textdomain' ),
				'condition' => [ 'wcf_animation_builder_enable' => 'enable' ],
			]
		);

		$element->add_control(
			'enable_to',
			[
				'label'              => esc_html__( 'Enable To/From', 'textdomain' ),
				'type'               => Controls_Manager::SWITCHER,
				'label_on'           => esc_html__( 'Yes', 'textdomain' ),
				'label_off'          => esc_html__( 'No', 'textdomain' ),
				'return_value'       => 'yes',
				'render_type'        => 'none', // template
				'frontend_available' => true,
				'condition'          => [ 'wcf_animation_builder_enable' => 'enable' ],
			]
		);

		$element->add_control(
			'method_type',
			[
				'label'              => esc_html__( 'Method', 'textdomain' ),
				'type'               => Controls_Manager::SELECT,
				'default'            => 'to',
				'options'            => [
					'to'   => esc_html__( 'To', 'textdomain' ),
					'from' => esc_html__( 'From', 'textdomain' ),
				],
				'condition'          => [ 'enable_to!' => '' ],
				'render_type'        => 'none', // template
				'frontend_available' => true,
			]
		);

		$repeaterTO = new Repeater();

		$repeaterTO->add_control(
			'set_type',
			[
				'label'       => esc_html__( 'Parameter Types', 'textdomain' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'duration',
				'options'     => [
					'duration'    => esc_html__( 'Duration', 'textdomain' ),
					'delay'       => esc_html__( 'Delay', 'textdomain' ),
					'ease'        => esc_html__( 'Ease', 'textdomain' ),
					'yoyo'        => esc_html__( 'YoYo', 'textdomain' ),
					'repeat'      => esc_html__( 'Repeat', 'textdomain' ),
					'repeatDelay' => esc_html__( 'Repeat Delay', 'textdomain' ),
					'force3D'     => esc_html__( 'Force 3D', 'textdomain' ),
					'scale'       => esc_html__( 'Scale', 'textdomain' ),
					'scaleX'      => esc_html__( 'ScaleX', 'textdomain' ),
					'scaleY'      => esc_html__( 'ScaleY', 'textdomain' ),
					'x'           => esc_html__( 'X', 'textdomain' ),
					'y'           => esc_html__( 'Y', 'textdomain' ),
					'rotation'    => esc_html__( 'Rotation', 'textdomain' ),
					'opacity'     => esc_html__( 'Opacity', 'textdomain' ),
					'custom'      => esc_html__( 'Custom', 'textdomain' ),
				],
				'render_type' => 'none', // template
			]
		);

		$repeaterTO->add_control(
			'set_duration',
			[
				'label'       => esc_html__( 'Duration', 'extension-for-animation-addons' ),
				'type'        => Controls_Manager::NUMBER,
				'default'     => 1.5,
				'condition'   => [ 'set_type' => 'duration' ],
				'render_type' => 'none', // template
			]
		);

		$repeaterTO->add_control(
			'set_delay',
			[
				'label'       => esc_html__( 'Delay', 'extension-for-animation-addons' ),
				'type'        => Controls_Manager::NUMBER,
				'min'         => 0,
				'max'         => 10,
				'step'        => 0.1,
				'default'     => .15,
				'render_type' => 'none', // template
				'condition'   => [ 'set_type' => 'delay' ],
			]
		);

		$repeaterTO->add_control(
			'set_ease',
			[
				'label'       => esc_html__( 'Ease', 'extension-for-animation-addons' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'power2.out',
				'options'     => [
					'power2.out' => esc_html__( 'Power2.out', 'extension-for-animation-addons' ),
					'bounce'     => esc_html__( 'Bounce', 'extension-for-animation-addons' ),
					'back'       => esc_html__( 'Back', 'extension-for-animation-addons' ),
					'elastic'    => esc_html__( 'Elastic', 'extension-for-animation-addons' ),
					'slowmo'     => esc_html__( 'Slowmo', 'extension-for-animation-addons' ),
					'stepped'    => esc_html__( 'Stepped', 'extension-for-animation-addons' ),
					'sine'       => esc_html__( 'Sine', 'extension-for-animation-addons' ),
					'expo'       => esc_html__( 'Expo', 'extension-for-animation-addons' ),
				],
				'condition'   => [ 'set_type' => 'ease' ],
				'render_type' => 'none', // template
			]
		);

		$repeaterTO->add_control(
			'set_yoyo',
			[
				'label'       => esc_html__( 'YoYo', 'textdomain' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'false',
				'options'     => [
					'true'  => esc_html__( 'True', 'extension-for-animation-addons' ),
					'false' => esc_html__( 'False', 'extension-for-animation-addons' ),
				],
				'condition'   => [ 'set_type' => 'yoyo' ],
				'render_type' => 'none', // template
			]
		);

		$repeaterTO->add_control(
			'set_repeat',
			[
				'label'       => esc_html__( 'Repeat', 'extension-for-animation-addons' ),
				'type'        => Controls_Manager::NUMBER,
				'default'     => 1,
				'condition'   => [ 'set_type' => 'repeat' ],
				'render_type' => 'none', // template
			]
		);

		$repeaterTO->add_control(
			'set_repeat_delay',
			[
				'label'       => esc_html__( 'Repeat Delay', 'extension-for-animation-addons' ),
				'type'        => Controls_Manager::NUMBER,
				'default'     => 1,
				'condition'   => [ 'set_type' => 'repeatDelay' ],
				'render_type' => 'none', // template
			]
		);

		$repeaterTO->add_control(
			'set_3d',
			[
				'label'        => esc_html__( 'Force 3D', 'textdomain' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'textdomain' ),
				'label_off'    => esc_html__( 'No', 'textdomain' ),
				'return_value' => 'true',
				'default'      => '',
				'condition'    => [ 'set_type' => 'force3D' ],
				'render_type'  => 'none', // template
			]
		);

		$repeaterTO->add_control(
			'set_scale',
			[
				'label'       => esc_html__( 'Scale', 'extension-for-animation-addons' ),
				'type'        => Controls_Manager::NUMBER,
				'default'     => 1,
				'condition'   => [ 'set_type' => [ 'scale', 'scaleX', 'scaleY' ] ],
				'render_type' => 'none', // template
			]
		);

		$repeaterTO->add_control(
			'set_transform',
			[
				'label'       => esc_html__( 'Transform', 'extension-for-animation-addons' ),
				'type'        => Controls_Manager::NUMBER,
				'default'     => 1,
				'condition'   => [ 'set_type' => [ 'x', 'y', 'rotation' ] ],
				'render_type' => 'none', // template
			]
		);

		$repeaterTO->add_control(
			'set_opacity',
			[
				'label'       => esc_html__( 'Opacity', 'extension-for-animation-addons' ),
				'type'        => Controls_Manager::NUMBER,
				'min'         => 0,
				'max'         => 1,
				'step'        => 0.1,
				'render_type' => 'none', // template
				'condition'   => [ 'set_type' => 'opacity' ],
			]
		);

		$repeaterTO->add_control(
			'set_custom',
			[
				'label'       => esc_html__( 'Custom', 'textdomain' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'key|value', 'textdomain' ),
				'render_type' => 'none', // template
				'condition'   => [ 'set_type' => 'custom' ],
			]
		);

		$element->add_control(
			'to_list',
			[
				'label'              => esc_html__( 'Arguments', 'animation-addons-for-elementor' ),
				'type'               => Controls_Manager::REPEATER,
				'fields'             => $repeaterTO->get_controls(),
				'default'            => [ [] ],
				'title_field'        => '{{{ set_type }}}',//phpcs:ignore
				'frontend_available' => true,
				'render_type'        => 'none', // template
				'condition'          => [ 'enable_to!' => '' ]
			]
		);

		$element->end_controls_tab();
	}

	public static function scroll_trigger_controls( $element ) {

		$element->add_control( 'enable_scroll_trigger', [
			'label'              => esc_html__( 'Enable Scroll Trigger', 'textdomain' ),
			'type'               => Controls_Manager::SWITCHER,
			'label_on'           => esc_html__( 'Yes', 'textdomain' ),
			'label_off'          => esc_html__( 'No', 'textdomain' ),
			'separator'          => 'before',
			'return_value'       => 'yes',
			'render_type'        => 'none', // template
			'frontend_available' => true,
			'condition'          => [ 'wcf_animation_builder_enable' => 'enable' ],
		] );

		$repeaterST = new Repeater();

		$repeaterST->add_control(
			'set_type',
			[
				'label'       => esc_html__( 'Parameter Types', 'textdomain' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'trigger',
				'options'     => [
					'trigger'         => esc_html__( 'Trigger', 'textdomain' ),
					'endTrigger'      => esc_html__( 'End Trigger', 'textdomain' ),
					'start'           => esc_html__( 'Start', 'textdomain' ),
					'end'             => esc_html__( 'End', 'textdomain' ),
					'scrub'           => esc_html__( 'Scrub', 'textdomain' ),
					'pin'             => esc_html__( 'Pin', 'textdomain' ),
					'pinSpacing'      => esc_html__( 'Pin Spacing', 'textdomain' ),
					'pinType'         => esc_html__( 'Pin Type', 'textdomain' ),
					'pinnedContainer' => esc_html__( 'Pinned Container', 'textdomain' ),
					'anticipatePin'   => esc_html__( 'Anticipate Pin', 'textdomain' ),
					'markers'         => esc_html__( 'Markers', 'textdomain' ),
					'custom'          => esc_html__( 'Custom', 'textdomain' ),
				],
				'render_type' => 'none', // template
			]
		);

		$repeaterST->add_control(
			'set_scrub',
			[
				'label'       => esc_html__( 'Scrub', 'textdomain' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'false',
				'options'     => [
					'true'  => esc_html__( 'True', 'extension-for-animation-addons' ),
					'false' => esc_html__( 'False', 'extension-for-animation-addons' ),
				],
				'condition'   => [ 'set_type' => 'scrub' ],
				'render_type' => 'none', // template
			]
		);

		$repeaterST->add_control(
			'set_pin',
			[
				'label'       => esc_html__( 'Pin', 'textdomain' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'false',
				'options'     => [
					'true'  => esc_html__( 'True', 'extension-for-animation-addons' ),
					'false' => esc_html__( 'False', 'extension-for-animation-addons' ),
				],
				'condition'   => [ 'set_type' => 'pin' ],
				'render_type' => 'none', // template
			]
		);

		$repeaterST->add_control(
			'set_pinSpacing',
			[
				'label'       => esc_html__( 'Pin Spacing', 'textdomain' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'false',
				'options'     => [
					'true'  => esc_html__( 'True', 'extension-for-animation-addons' ),
					'false' => esc_html__( 'False', 'extension-for-animation-addons' ),
				],
				'condition'   => [ 'set_type' => 'pinSpacing' ],
				'render_type' => 'none', // template
			]
		);

		$repeaterST->add_control(
			'set_markers',
			[
				'label'       => esc_html__( 'Markers', 'textdomain' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'false',
				'options'     => [
					'true'  => esc_html__( 'True', 'extension-for-animation-addons' ),
					'false' => esc_html__( 'False', 'extension-for-animation-addons' ),
				],
				'condition'   => [ 'set_type' => 'markers' ],
				'render_type' => 'none', // template
			]
		);

		$repeaterST->add_control(
			'set_pinType',
			[
				'label'       => esc_html__( 'Pin Type', 'extension-for-animation-addons' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'transform',
				'options'     => [
					'transform' => esc_html__( 'Transform', 'extension-for-animation-addons' ),
					'fixed'     => esc_html__( 'Fixed', 'extension-for-animation-addons' ),
				],
				'condition'   => [ 'set_type' => 'pinType' ],
				'render_type' => 'none', // template
			]
		);

		//start trigger
		$repeaterST->add_control(
			'set_trigger',
			[
				'label'       => esc_html__( 'Trigger', 'extension-for-animation-addons' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'default',
				'options'     => [
					'default' => esc_html__( 'Default', 'extension-for-animation-addons' ),
					'custom'  => esc_html__( 'Custom', 'extension-for-animation-addons' ),
				],
				'condition'   => [ 'set_type' => 'trigger' ],
				'render_type' => 'none', // template
			]
		);

		$repeaterST->add_control(
			'set_trigger_custom',
			[
				'label'       => esc_html__( 'Selector', 'textdomain' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => esc_html__( '.selector', 'textdomain' ),
				'condition'   => [
					'set_type'    => 'trigger',
					'set_trigger' => 'custom'
				],
			]
		);

		//end trigger
		$repeaterST->add_control(
			'set_endTrigger',
			[
				'label'       => esc_html__( 'End Trigger', 'extension-for-animation-addons' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'default',
				'options'     => [
					'default' => esc_html__( 'Default', 'extension-for-animation-addons' ),
					'custom'  => esc_html__( 'Custom', 'extension-for-animation-addons' ),
				],
				'condition'   => [ 'set_type' => 'endTrigger' ],
				'render_type' => 'none', // template
			]
		);

		$repeaterST->add_control(
			'set_endTrigger_custom',
			[
				'label'       => esc_html__( 'Selector', 'textdomain' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => esc_html__( '.selector', 'textdomain' ),
				'condition'   => [
					'set_type'       => 'endTrigger',
					'set_endTrigger' => 'custom'
				],
			]
		);

		//pined container
		$repeaterST->add_control(
			'set_pinnedContainer',
			[
				'label'       => esc_html__( 'Pinned Container', 'extension-for-animation-addons' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'default',
				'options'     => [
					'default' => esc_html__( 'Default', 'extension-for-animation-addons' ),
					'custom'  => esc_html__( 'Custom', 'extension-for-animation-addons' ),
				],
				'condition'   => [ 'set_type' => 'pinnedContainer' ],
				'render_type' => 'none', // template
			]
		);

		$repeaterST->add_control(
			'set_pinnedContainer_custom',
			[
				'label'       => esc_html__( 'Selector', 'textdomain' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => esc_html__( '.selector', 'textdomain' ),
				'condition'   => [
					'set_type'            => 'pinnedContainer',
					'set_pinnedContainer' => 'custom'
				],
			]
		);

		$repeaterST->add_control(
			'set_start',
			[
				'label'       => esc_html__( 'Start', 'textdomain' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'top center', 'textdomain' ),
				'default'     => esc_html__( 'top center', 'textdomain' ),
				'condition'   => [ 'set_type' => 'start' ],
			]
		);

		$repeaterST->add_control(
			'set_end',
			[
				'label'       => esc_html__( 'End', 'textdomain' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'top center', 'textdomain' ),
				'default'     => esc_html__( 'top center', 'textdomain' ),
				'condition'   => [ 'set_type' => 'end' ],
			]
		);

		$repeaterST->add_control(
			'set_anticipatePin',
			[
				'label'       => esc_html__( 'Anticipate Pin', 'extension-for-animation-addons' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => '0',
				'options'     => [
					'0' => esc_html__( '0', 'extension-for-animation-addons' ),
					'1' => esc_html__( '1', 'extension-for-animation-addons' ),
				],
				'condition'   => [ 'set_type' => 'anticipatePin' ],
				'render_type' => 'none', // template
			]
		);

		$repeaterST->add_control(
			'set_custom',
			[
				'label'       => esc_html__( 'Custom', 'textdomain' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'key|value', 'textdomain' ),
				'render_type' => 'none', // template
				'condition'   => [ 'set_type' => 'custom' ],
			]
		);

		$element->add_control(
			'st_list',
			[
				'label'              => esc_html__( 'Arguments', 'animation-addons-for-elementor' ),
				'type'               => Controls_Manager::REPEATER,
				'fields'             => $repeaterST->get_controls(),
				'default'            => [ [] ],
				'title_field'        => '{{{ set_type }}}',//phpcs:ignore
				'frontend_available' => true,
				'render_type'        => 'none', // template
				'condition'          => [ 'enable_scroll_trigger!' => '' ]
			]
		);
	}
}

WCF_Animation_Builder::init();
