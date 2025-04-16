<?php
/**
 * Animation Effects extension class.
 */

namespace WCFAddonsEX\Extensions;

use Elementor\Element_Base;
use Elementor\Controls_Manager;
use Elementor\Plugin;

defined( 'ABSPATH' ) || die();

class WCF_Image_Animation_Effects {

	public static function init() {

		$image_elements = [
			[
				'name'    => 'image',
				'section' => 'section_image',
			],
			[
				'name'    => 'wcf--image',
				'section' => 'section_content',
			],
		];
		foreach ( $image_elements as $element ) {
			add_action( 'elementor/element/' . $element['name'] . '/' . $element['section'] . '/after_section_end', [
				__CLASS__,
				'register_image_animation_controls',
			], 10, 2 );
		}

		//image reveal
		$image_reveal_elements = [
			[
				'name'    => 'wcf--image-box',
				'section' => 'section_button_content',
			],
			[
				'name'    => 'wcf--timeline',
				'section' => 'section_timeline',
			],
		];
		foreach ( $image_reveal_elements as $element ) {
			add_action( 'elementor/element/' . $element['name'] . '/' . $element['section'] . '/after_section_end', [
				__CLASS__,
				'register_image_reveal_animation_controls',
			], 10, 2 );
		}
	}

	public static function register_image_animation_controls( $element ) {
		$element->start_controls_section(
			'_section_wcf_image_animation',
			[
				'label' =>  sprintf('%s <i class="wcf-logo"></i>', __('Image Animation', 'extension-for-animation-addons')),
			]
		);

		$element->add_control(
			'wcf-image-animation',
			[
				'label'              => esc_html__( 'Animation', 'extension-for-animation-addons' ),
				'type'               => Controls_Manager::SELECT,
				'default'            => 'none',
				'separator'          => 'before',
				'options'            => [
					'none'    => esc_html__( 'none', 'extension-for-animation-addons' ),
					'reveal'  => esc_html__( 'Reveal', 'extension-for-animation-addons' ),
					'scale'   => esc_html__( 'Scale', 'extension-for-animation-addons' ),
					'stretch' => esc_html__( 'Stretch', 'extension-for-animation-addons' ),
				],
				'render_type'        => 'none',
				'frontend_available' => true,
			]
		);

		$element->add_control(
			'wcf_img_animation_editor',
			[
				'label'              => esc_html__( 'Enable On Editor', 'extension-for-animation-addons' ),
				'description'        => esc_html__( 'For better performance in editor mode, keep the setting turned off.', 'extension-for-animation-addons' ),
				'type'               => Controls_Manager::SWITCHER,
				'frontend_available' => true,
				'return_value'       => 'yes',
				'condition'          => [
					'wcf-image-animation!' => 'none',
				],
			]
		);

		$element->add_control(
			'play_image_animation',
			[
				'label' => esc_html__( 'Play Animation', 'extension-for-animation-addons' ),
				'type' => Controls_Manager::BUTTON,
				'separator' => 'before',
				'button_type' => 'success',
				'text' => esc_html__( 'Play', 'extension-for-animation-addons' ),
				'event' => 'wcf:editor:play_animation',
				'condition'          => [
					'wcf-image-animation!' => 'none',
					'wcf_img_animation_editor' => 'yes'
				],
			]
		);

		$element->add_control(
			'wcf-scale-start',
			[
				'label'     => esc_html__( 'Start Scale', 'extension-for-animation-addons' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => 0.7,
				'condition' => [ 'wcf-image-animation' => 'scale' ],
				'render_type'        => 'none',
				'frontend_available' => true,
			]
		);

		$element->add_control(
			'wcf-scale-end',
			[
				'label'     => esc_html__( 'End Scale', 'extension-for-animation-addons' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => 1,
				'condition' => [ 'wcf-image-animation' => 'scale' ],
				'render_type'        => 'none',
				'frontend_available' => true,
			]
		);

		$element->add_control(
			'wcf-animation-start',
			[
				'label'              => esc_html__( 'Animation Start', 'extension-for-animation-addons' ),
				'description'        => esc_html__( 'First value is element position, Second value is display position', 'extension-for-animation-addons' ),
				'type'               => Controls_Manager::SELECT,
				'default'            => 'top top',
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
					'custom'        => esc_html__( 'Custom', 'extension-for-animation-addons' ),
				],
				'condition'          => [ 'wcf-image-animation' => 'scale' ],
			]
		);

		$element->add_control(
			'wcf_animation_custom_start',
			[
				'label'       => esc_html__( 'Custom', 'extension-for-animation-addons' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'top 90%', 'extension-for-animation-addons' ),
				'placeholder' => esc_html__( 'top 90%', 'extension-for-animation-addons' ),
				'render_type'        => 'none',
				'condition'   => [
					'wcf-image-animation' => 'scale',
					'wcf-animation-start' => 'custom'
				],
				'frontend_available' => true,
			]
		);

		$element->add_control(
			'image-ease',
			[
				'label'              => esc_html__( 'Data ease', 'extension-for-animation-addons' ),
				'type'               => Controls_Manager::SELECT,
				'default'            => 'power2.out',
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
				'condition'          => [ 'wcf-image-animation' => 'reveal' ],
				'render_type'        => 'none',
				'frontend_available' => true,
			]
		);

		$element->end_controls_section();
	}

	public static function register_image_reveal_animation_controls( $element ) {
		$element->start_controls_section(
			'_section_wcf_image_animation',
			[
				'label' =>  sprintf('%s <i class="wcf-logo"></i>', __('Image Animation', 'extension-for-animation-addons')),
			]
		);

		$element->add_control(
			'wcf-image-animation',
			[
				'label'              => esc_html__( 'Animation', 'extension-for-animation-addons' ),
				'type'               => Controls_Manager::SELECT,
				'default'            => 'none',
				'separator'          => 'before',
				'options'            => [
					'none'   => esc_html__( 'none', 'extension-for-animation-addons' ),
					'reveal' => esc_html__( 'Reveal', 'extension-for-animation-addons' ),
				],
				'frontend_available' => true,
			]
		);

		$element->add_control(
			'image-ease',
			[
				'label'              => esc_html__( 'Data ease', 'extension-for-animation-addons' ),
				'type'               => Controls_Manager::SELECT,
				'default'            => 'power2.out',
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
				'condition'          => [ 'wcf-image-animation' => 'reveal' ],
				'frontend_available' => true,
			]
		);

		$element->end_controls_section();
	}

}

WCF_Image_Animation_Effects::init();
