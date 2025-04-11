<?php
/**
 * Animation Effects extension class.
 */

namespace WCFAddonsEX\Extensions;

use Elementor\Element_Base;
use Elementor\Controls_Manager;
use Elementor\Plugin;

defined( 'ABSPATH' ) || die();

class WCF_Animation_Effects {

	public static function init() {

		//animation controls
		add_action( 'elementor/element/common/_section_style/after_section_end', [
			__CLASS__,
			'register_animation_controls',
		] );

		add_action( 'elementor/element/container/section_layout/after_section_end', [
			__CLASS__,
			'register_animation_controls'
		] );

		add_action( 'elementor/frontend/widget/before_render', [ __CLASS__, 'wcf_attributes' ] );
		add_action( 'elementor/frontend/container/before_render', [ __CLASS__, 'wcf_attributes' ] );

		add_action( 'elementor/preview/enqueue_scripts', [ __CLASS__, 'enqueue_scripts' ] );
	}

	public static function enqueue_scripts() {

	}

	/**
	 * Set attributes based extension settings
	 *
	 * @param Element_Base $section
	 *
	 * @return void
	 */
	public static function wcf_attributes( $element ) {
		if ( ! empty( $element->get_settings( 'wcf_enable_scroll_smoother' ) ) ) {
			$attributes = [];

			if ( ! empty( $element->get_settings( 'data-speed' ) ) ) {
				$attributes['data-speed'] = $element->get_settings( 'data-speed' );
			}
			if ( ! empty( $element->get_settings( 'data-lag' ) ) ) {
				$attributes['data-lag'] = $element->get_settings( 'data-lag' );
			}

			$element->add_render_attribute( '_wrapper', $attributes );
		}
	}

	public static function register_animation_controls( $element ) {
		$element->start_controls_section(
			'_section_wcf_animation',
			[
				'label' =>  sprintf('%s <i class="wcf-logo"></i>', __('Animation', 'extension-for-animation-addons')),
				'tab'   => Controls_Manager::TAB_ADVANCED,
			]
		);

		$element->add_control(
			'wcf-animation',
			[
				'label'              => esc_html__( 'Animation', 'extension-for-animation-addons' ),
				'type'               => Controls_Manager::SELECT,
				'default'            => 'none',
				'separator'          => 'before',
				'options'            => [
					'none' => esc_html__( 'none', 'extension-for-animation-addons' ),
					'fade' => esc_html__( 'fade animation', 'extension-for-animation-addons' ),
					'move'  => esc_html__( '3D Move', 'extension-for-animation-addons' ),
				],
				'render_type'        => 'none', // template
				'frontend_available' => true,
			]
		);

		$element->add_control(
			'wcf_enable_animation_editor',
			[
				'label'              => esc_html__( 'Enable On Editor', 'extension-for-animation-addons' ),
				'description'        => esc_html__( 'For better performance in editor mode, keep the setting turned off.', 'extension-for-animation-addons' ),
				'type'               => Controls_Manager::SWITCHER,
				'frontend_available' => true,
				'return_value'       => 'yes',
				'condition'          => [
					'wcf-animation!' => 'none',
				],
			]
		);

		$element->add_control(
			'play_animation_content',
			[
				'label' => esc_html__( 'Play Animation', 'extension-for-animation-addons' ),
				'type' => \Elementor\Controls_Manager::BUTTON,
				'separator' => 'before',
				'button_type' => 'success',
				'text' => esc_html__( 'Play', 'extension-for-animation-addons' ),
				'event' => 'wcf:editor:play_animation',
				'condition'          => [
					'wcf-animation!' => 'none',
					'wcf_enable_animation_editor' => 'yes'
				],
			]
		);

		$element->add_control(
			'delay',
			[
				'label'              => esc_html__( 'Delay', 'extension-for-animation-addons' ),
				'type'               => Controls_Manager::NUMBER,
				'min'                => 0,
				'max'                => 10,
				'step'               => 0.1,
				'default'            => .15,
				'render_type'        => 'none', // template
				'condition'          => [
					'wcf-animation!' => 'none',
				],
				'frontend_available' => true,
			]
		);

		$element->add_control(
			'on-scroll',
			[
				'label'              => esc_html__( 'Animation on scroll', 'extension-for-animation-addons' ),
				'type'               => Controls_Manager::SWITCHER,
				'label_on'           => esc_html__( 'Yes', 'extension-for-animation-addons' ),
				'label_off'          => esc_html__( 'No', 'extension-for-animation-addons' ),
				'return_value'       => 1,
				'default'            => 1,
				'render_type'        => 'none', // template
				'frontend_available' => true,
				'condition'          => [
					'wcf-animation!' => 'none',
				],
			]
		);

		$element->add_control(
			'fade-from',
			[
				'label'              => esc_html__( 'Fade from', 'extension-for-animation-addons' ),
				'type'               => Controls_Manager::SELECT,
				'default'            => 'bottom',
				'render_type'        => 'none', // template
				'options'            => [
					'top'    => esc_html__( 'Top', 'extension-for-animation-addons' ),
					'bottom' => esc_html__( 'Bottom', 'extension-for-animation-addons' ),
					'left'   => esc_html__( 'Left', 'extension-for-animation-addons' ),
					'right'  => esc_html__( 'Right', 'extension-for-animation-addons' ),
					'in'     => esc_html__( 'In', 'extension-for-animation-addons' ),
					'scale'  => esc_html__( 'Zoom', 'extension-for-animation-addons' ),
				],
				'frontend_available' => true,
				'condition'          => [
					'wcf-animation' => 'fade',
				],
			]
		);

		$element->add_control(
			'data-duration',
			[
				'label'              => esc_html__( 'Duration', 'extension-for-animation-addons' ),
				'type'               => Controls_Manager::NUMBER,
				'default'            => 1.5,
				'render_type'        => 'none', // template
				'condition'          => [
					'wcf-animation!' => 'none',
				],
				'frontend_available' => true,
			]
		);

		$element->add_control(
			'ease',
			[
				'label'              => esc_html__( 'Ease', 'extension-for-animation-addons' ),
				'type'               => Controls_Manager::SELECT,
				'default'            => 'power2.out',
				'render_type'        => 'none', // template
				'options'            => [
					'power2.out' => esc_html__( 'Power2.out', 'extension-for-animation-addons' ),
					'bounce'     => esc_html__( 'Bounce', 'extension-for-animation-addons' ),
					'back'       => esc_html__( 'Back', 'extension-for-animation-addons' ),
					'elastic'    => esc_html__( 'Elastic', 'extension-for-animation-addons' ),
					'slowmo'     => esc_html__( 'Slowmo', 'extension-for-animation-addons' ),
					'stepped'    => esc_html__( 'Stepped', 'extension-for-animation-addons' ),
					'sine'       => esc_html__( 'Sine', 'extension-for-animation-addons' ),
					'expo'       => esc_html__( 'Expo', 'extension-for-animation-addons' ),
				],
				'condition'          => [
					'wcf-animation!' => 'none',
				],
				'frontend_available' => true,
			]
		);

		$element->add_control(
			'fade-offset',
			[
				'label'              => esc_html__( 'Fade offset', 'extension-for-animation-addons' ),
				'type'               => Controls_Manager::NUMBER,
				'default'            => 50,
				'render_type'        => 'none', // template
				'condition'          => [
					'fade-from!' => [ 'in', 'scale' ],
					'wcf-animation' => 'fade',
				],
				'frontend_available' => true,
			]
		);

		//scale
		$element->add_control(
			'wcf-a-scale',
			[
				'label'              => esc_html__( 'Start Scale', 'extension-for-animation-addons' ),
				'type'               => Controls_Manager::NUMBER,
				'default'            => 0.7,
				'condition'          => [
					'fade-from' => 'scale',
					'wcf-animation' => 'fade',
				],
				'render_type'        => 'none',
				'frontend_available' => true,
			]
		);

		//move
		$element->add_control(
			'wcf_a_rotation_di',
			[
				'label'              => esc_html__( 'Rotation Direction', 'extension-for-animation-addons' ),
				'type'               => Controls_Manager::SELECT,
				'default'            => 'x',
				'separator'          => 'before',
				'options'            => [
					'x' => esc_html__( 'X', 'extension-for-animation-addons' ),
					'y' => esc_html__( 'Y', 'extension-for-animation-addons' ),
				],
				'condition'          => [
					'wcf-animation' => 'move',
				],
				'frontend_available' => true,
				'render_type'        => 'none',
			]
		);

		$element->add_control(
			'wcf_a_rotation',
			[
				'label'              => esc_html__( 'Rotation Value', 'extension-for-animation-addons' ),
				'type'               => Controls_Manager::NUMBER,
				'default'            => '-80',
				'condition'          => [
					'wcf-animation' => 'move',
				],
				'frontend_available' => true,
				'render_type'        => 'none',
			]
		);

		$element->add_control(
			'wcf_a_transform_origin',
			[
				'label'              => esc_html__( 'transformOrigin', 'extension-for-animation-addons' ),
				'type'               => Controls_Manager::TEXT,
				'frontend_available' => true,
				'default'            => esc_html__( 'top center -50', 'extension-for-animation-addons' ),
				'placeholder'        => esc_html__( 'top center', 'extension-for-animation-addons' ),
				'condition'          => [
					'wcf-animation' => 'move',
				],
				'render_type'        => 'none',
			]
		);

		$dropdown_options = [
			'' => esc_html__( 'All', 'extension-for-animation-addons' ),
		];

		foreach ( Plugin::$instance->breakpoints->get_active_breakpoints() as $breakpoint_key => $breakpoint_instance ) {

			$dropdown_options[ $breakpoint_key ] = sprintf(
			/* translators: 1: Breakpoint label, 2: `>` character, 3: Breakpoint value. */
				esc_html__( '%1$s (%2$dpx)', 'extension-for-animation-addons' ),
				$breakpoint_instance->get_label(),
				$breakpoint_instance->get_value()
			);
		}

		$element->add_control(
			'fade_animation_breakpoint',
			[
				'label'              => esc_html__( 'Breakpoint', 'extension-for-animation-addons' ),
				'type'               => Controls_Manager::SELECT,
				'description'        => esc_html__( 'Note: Choose at which breakpoint animation will work.', 'extension-for-animation-addons' ),
				'options'            => $dropdown_options,
				'frontend_available' => true,
				'render_type'        => 'none', // template
				'default'            => '',
				'condition'          => [
					'wcf-animation!' => 'none',
				],
			]
		);

		$element->add_control(
			'fade_breakpoint_min_max',
			[
				'label'     => esc_html__( 'Breakpoint Min/Max', 'extension-for-animation-addons' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'min',
				'render_type'        => 'none', // template
				'options'   => [
					'min' => esc_html__( 'Min(>)', 'extension-for-animation-addons' ),
					'max' => esc_html__( 'Max(<)', 'extension-for-animation-addons' ),
				],
				'frontend_available' => true,
				'condition' => [
					'wcf-animation!'        => 'none',
					'fade_animation_breakpoint!' => '',
				],
			]
		);

		//smooth scroll animation
		$element->add_control(
			'wcf_enable_scroll_smoother',
			[
				'label'        => esc_html__( 'Enable Scroll Smoother', 'extension-for-animation-addons' ),
				'description'  => esc_html__( 'If you want to use scroll smooth, please enable global settings first', 'extension-for-animation-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'extension-for-animation-addons' ),
				'label_off'    => esc_html__( 'No', 'extension-for-animation-addons' ),
				'return_value' => 'yes',
				'render_type'        => 'none', // template
				'separator'    => 'before',
			]
		);

		$element->add_control(
			'data-speed',
			[
				'label'     => esc_html__( 'Speed', 'extension-for-animation-addons' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => 0.9,
				'render_type'        => 'none', // template
				'condition' => [ 'wcf_enable_scroll_smoother' => 'yes' ],
			]
		);

		$element->add_control(
			'data-lag',
			[
				'label'     => esc_html__( 'Lag', 'extension-for-animation-addons' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => 0,
				'render_type'        => 'none', // template
				'condition' => [ 'wcf_enable_scroll_smoother' => 'yes' ],
			]
		);

		$element->end_controls_section();
	}

}

WCF_Animation_Effects::init();


