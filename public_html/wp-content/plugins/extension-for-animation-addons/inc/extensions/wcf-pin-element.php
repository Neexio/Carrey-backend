<?php
/**
 * Animation Effects extension class.
 */

namespace WCFAddonsEX\Extensions;

use Elementor\Controls_Manager;
use Elementor\Plugin;

defined( 'ABSPATH' ) || die();

class WCF_Pin_Effects {

	public static function init() {
		//ping area controls
		add_action( 'elementor/element/section/section_advanced/after_section_end', [
			__CLASS__,
			'register_ping_area_controls'
		] );

		add_action( 'elementor/element/container/section_layout/after_section_end', [
			__CLASS__,
			'register_ping_area_controls'
		] );
	}

	public static function register_ping_area_controls( $element ) {
		$element->start_controls_section(
			'_section_pin-area',
			[
				'label' => sprintf( '%s <i class="wcf-logo"></i>', __( 'Pin Element', 'extension-for-animation-addons' ) ),
				'tab'   => Controls_Manager::TAB_ADVANCED,
			]
		);

		$element->add_control(
			'wcf_enable_pin_area',
			[
				'label'              => esc_html__( 'Enable Pin', 'extension-for-animation-addons' ),
				'type'               => Controls_Manager::SWITCHER,
				'frontend_available' => true,
				'render_type'        => 'none',
				'return_value'       => 'yes',
			]
		);

		$element->add_control(
			'wcf_pin_alert',
			[
				'label'           => esc_html__( 'Important Note', 'wcf-addons-pro' ),
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => esc_html__( 'Please use full width Container to work properly and see the result in view mode.', 'wcf-addons-pro' ),
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-warning',
				'condition'       => [ 'wcf_enable_pin_area!' => '' ],
				'render_type'     => 'none',
			]
		);

		$element->add_control(
			'wcf_pin_area_trigger',
			[
				'label'       => esc_html__( 'Pin Wrapper', 'extension-for-animation-addons' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => '',
				'options'     => [
					''       => esc_html__( 'Default', 'extension-for-animation-addons' ),
					'custom' => esc_html__( 'Custom', 'extension-for-animation-addons' ),
				],
				'condition'   => [ 'wcf_enable_pin_area!' => '' ],
				'render_type' => 'none',
			]
		);

		$element->add_control(
			'wcf_custom_pin_area',
			[
				'label'              => esc_html__( 'Custom Pin Area', 'extension-for-animation-addons' ),
				'description'        => esc_html__( 'Add the section class where the element will be pin. please use the parent section or container class.', 'extension-for-animation-addons' ),
				'type'               => Controls_Manager::TEXT,
				'ai'                 => false,
				'placeholder'        => esc_html__( '.pin_area', 'extension-for-animation-addons' ),
				'frontend_available' => true,
				'render_type'        => 'none',
				'condition'          => [
					'wcf_pin_area_trigger' => 'custom',
					'wcf_enable_pin_area!' => '',
				]
			]
		);

		$element->add_control(
			'wcf_pin_end_trigger',
			[
				'label'              => esc_html__( 'End Trigger', 'extension-for-animation-addons' ),
				'type'               => Controls_Manager::TEXT,
				'ai'                 => false,
				'placeholder'        => esc_html__( '.end_trigger', 'extension-for-animation-addons' ),
				'frontend_available' => true,
				'render_type'        => 'none',
				'condition'          => [
					'wcf_enable_pin_area!' => '',
				]
			]
		);

		$element->add_control(
			'wcf_pin_area_start',
			[
				'label'              => esc_html__( 'Start', 'extension-for-animation-addons' ),
				'description'        => esc_html__( 'First value is element position, Second value is display position', 'extension-for-animation-addons' ),
				'type'               => Controls_Manager::SELECT,
				'separator'          => 'before',
				'default'            => 'top top',
				'frontend_available' => true,
				'options'            => [
					'top top'       => esc_html__( 'Top Top', 'extension-for-animation-addons' ),
					'top center'    => esc_html__( 'Top Center', 'extension-for-animation-addons' ),
					'top bottom'    => esc_html__( 'Top Bottom', 'extension-for-animation-addons' ),
					'center top'    => esc_html__( 'Center Top', 'extension-for-animation-addons' ),
					'center center' => esc_html__( 'Center Center', 'extension-for-animation-addons' ),
					'center bottom' => esc_html__( 'Center Bottom', 'extension-for-animation-addons' ),
					'bottom top'    => esc_html__( 'Bottom Top', 'extension-for-animation-addons' ),
					'bottom center' => esc_html__( 'Bottom Center', 'extension-for-animation-addons' ),
					'bottom bottom' => esc_html__( 'Bottom Bottom', 'extension-for-animation-addons' ),
					'custom'        => esc_html__( 'custom', 'extension-for-animation-addons' ),
				],
				'render_type'        => 'none',
				'condition'          => [ 'wcf_enable_pin_area!' => '' ],

			]
		);

		$element->add_control(
			'wcf_pin_area_start_custom',
			[
				'label'              => esc_html__( 'Custom', 'extension-for-animation-addons' ),
				'type'               => Controls_Manager::TEXT,
				'default'            => esc_html__( 'top top', 'extension-for-animation-addons' ),
				'placeholder'        => esc_html__( 'top top+=100', 'extension-for-animation-addons' ),
				'frontend_available' => true,
				'render_type'        => 'none',
				'condition'          => [
					'wcf_enable_pin_area!' => '',
					'wcf_pin_area_start'   => 'custom',
				],
			]
		);

		$element->add_control(
			'wcf_pin_area_end',
			[
				'label'              => esc_html__( 'End', 'extension-for-animation-addons' ),
				'description'        => esc_html__( 'First value is element position, Second value is display position', 'extension-for-animation-addons' ),
				'type'               => Controls_Manager::SELECT,
				'separator'          => 'before',
				'default'            => 'bottom top',
				'frontend_available' => true,
				'render_type'        => 'none',
				'options'            => [
					'top top'       => esc_html__( 'Top Top', 'extension-for-animation-addons' ),
					'top center'    => esc_html__( 'Top Center', 'extension-for-animation-addons' ),
					'top bottom'    => esc_html__( 'Top Bottom', 'extension-for-animation-addons' ),
					'center top'    => esc_html__( 'Center Top', 'extension-for-animation-addons' ),
					'center center' => esc_html__( 'Center Center', 'extension-for-animation-addons' ),
					'center bottom' => esc_html__( 'Center Bottom', 'extension-for-animation-addons' ),
					'bottom top'    => esc_html__( 'Bottom Top', 'extension-for-animation-addons' ),
					'bottom center' => esc_html__( 'Bottom Center', 'extension-for-animation-addons' ),
					'bottom bottom' => esc_html__( 'Bottom Bottom', 'extension-for-animation-addons' ),
					'custom'        => esc_html__( 'custom', 'extension-for-animation-addons' ),
				],
				'condition'          => [ 'wcf_enable_pin_area!' => '' ],
			]
		);

		$element->add_control(
			'wcf_pin_area_end_custom',
			[
				'label'              => esc_html__( 'Custom', 'extension-for-animation-addons' ),
				'type'               => Controls_Manager::TEXT,
				'frontend_available' => true,
				'render_type'        => 'none',
				'default'            => esc_html__( 'bottom top', 'extension-for-animation-addons' ),
				'placeholder'        => esc_html__( 'bottom top+=100', 'extension-for-animation-addons' ),
				'condition'          => [
					'wcf_enable_pin_area!' => '',
					'wcf_pin_area_end'     => 'custom',
				],
			]
		);

		$dropdown_options = [
			'' => esc_html__( 'None', 'extension-for-animation-addons' ),
		];

		$excluded_breakpoints = [
			'laptop',
			'tablet_extra',
			'widescreen',
		];

		foreach ( Plugin::$instance->breakpoints->get_active_breakpoints() as $breakpoint_key => $breakpoint_instance ) {
			// Exclude the larger breakpoints from the dropdown selector.
			if ( in_array( $breakpoint_key, $excluded_breakpoints, true ) ) {
				continue;
			}

			$dropdown_options[ $breakpoint_key ] = sprintf(
			/* translators: 1: Breakpoint label, 2: `>` character, 3: Breakpoint value. */
				esc_html__( '%1$s (%2$s %3$dpx)', 'extension-for-animation-addons' ),
				$breakpoint_instance->get_label(),
				'>',
				$breakpoint_instance->get_value()
			);
		}

		$element->add_control(
			'wcf_pin_breakpoint',
			[
				'label'              => esc_html__( 'Breakpoint', 'extension-for-animation-addons' ),
				'type'               => Controls_Manager::SELECT,
				'separator'          => 'before',
				'description'        => esc_html__( 'Note: Choose at which breakpoint Pin element will work.', 'extension-for-animation-addons' ),
				'options'            => $dropdown_options,
				'frontend_available' => true,
				'render_type'        => 'none',
				'default'            => 'mobile',
				'condition'          => [ 'wcf_enable_pin_area!' => '' ],
			]
		);

		$element->end_controls_section();
	}
}

WCF_Pin_Effects::init();
