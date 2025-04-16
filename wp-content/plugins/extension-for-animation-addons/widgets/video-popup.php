<?php

namespace WCFAddonsEX\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Icons_Manager;
use Elementor\Utils;
use Elementor\Widget_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

/**
 * Video Popup
 *
 * Elementor widget for Video Popup.
 *
 * @since 1.0.0
 */
class Video_Popup extends Widget_Base {

	/**
	 * Retrieve the widget name.
	 *
	 * @return string Widget name.
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function get_name() {
		return 'wcf--video-popup';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @return string Widget title.
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function get_title() {
		return esc_html__( 'WCF Video Popup', 'extension-for-animation-addons' );
	}

	/**
	 * Retrieve the widget icon.
	 *
	 * @return string Widget icon.
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function get_icon() {
		return 'wcf eicon-youtube';
	}

	/**
	 * Retrieve the list of categories the widget belongs to.
	 *
	 * Used to determine where to display the widget in the editor.
	 *
	 * Note that currently Elementor supports only one category.
	 * When multiple categories passed, Elementor uses the first one.
	 *
	 * @return array Widget categories.
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function get_categories() {
		return [ 'weal-coder-addon' ];
	}

	/**
	 * Retrieve the list of scripts the widget depended on.
	 *
	 * Used to set scripts dependencies required to run the widget.
	 *
	 * @return array Widget scripts dependencies.
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function get_script_depends() {
		return [ 'magnific-popup' ];
	}

	/**
	 * Requires css files.
	 *
	 * @return array
	 */
	public function get_style_depends() {
		return [ 'magnific-popup' ];
	}

	/**
	 * Register the widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 */
	protected function register_controls() {
		$this->register_button_controls();

		$this->register_video_controls();
	}

	protected function register_button_controls() {
		$this->start_controls_section(
			'section_button',
			[
				'label' => __( 'Button', 'extension-for-animation-addons' ),
			]
		);

		$this->add_control(
			'btn_text',
			[
				'label'       => esc_html__( 'Text', 'extension-for-animation-addons' ),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => [
					'active' => true,
				],
				'default'     => esc_html__( 'Play', 'extension-for-animation-addons' ),
				'placeholder' => esc_html__( 'Play', 'extension-for-animation-addons' ),
			]
		);

		$this->add_control(
			'btn_icon',
			[
				'label'            => esc_html__( 'Icon', 'extension-for-animation-addons' ),
				'type'             => Controls_Manager::ICONS,
				'fa4compatibility' => 'icon',
				'skin'             => 'inline',
				'label_block'      => false,
			]
		);

		$this->add_control(
			'icon_indent',
			[
				'label'     => esc_html__( 'Icon Spacing', 'extension-for-animation-addons' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .wcf-popup-btn' => 'gap: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'active_ripple',
			[
				'label'        => esc_html__( 'Active Ripple', 'extension-for-animation-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'yes', 'extension-for-animation-addons' ),
				'label_off'    => esc_html__( 'No', 'extension-for-animation-addons' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->add_control(
			'ripple_color',
			[
				'label'     => esc_html__( 'Ripple Color', 'extension-for-animation-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wcf-popup-btn:before' => 'color: {{VALUE}}',
					'{{WRAPPER}} .wcf-popup-btn:after'  => 'color: {{VALUE}}',
				],
				'condition' => [ 'active_ripple' => 'yes' ],
			]
		);

		$this->add_control(
			'active_spinner',
			[
				'label'        => esc_html__( 'Active spinner', 'extension-for-animation-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'yes', 'extension-for-animation-addons' ),
				'label_off'    => esc_html__( 'No', 'extension-for-animation-addons' ),
				'return_value' => 'yes',
			]
		);

		$this->add_control(
			'sipper_image',
			[
				'label'     => esc_html__( 'Spinner Image', 'extension-for-animation-addons' ),
				'type'      => Controls_Manager::MEDIA,
				'default'   => [
					'url' => Utils::get_placeholder_image_src(),
				],
				'condition' => [ 'active_spinner' => 'yes' ],
			]
		);

		$this->add_responsive_control(
			'align',
			[
				'label'     => esc_html__( 'Alignment', 'extension-for-animation-addons' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => [
					'left'   => [
						'title' => esc_html__( 'Left', 'extension-for-animation-addons' ),
						'icon'  => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'extension-for-animation-addons' ),
						'icon'  => 'eicon-text-align-center',
					],
					'right'  => [
						'title' => esc_html__( 'Right', 'extension-for-animation-addons' ),
						'icon'  => 'eicon-text-align-right',
					],
				],
				'default'   => '',
				'separator' => 'before',
				'selectors' => [
					'{{WRAPPER}}' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();

		//style
		$this->start_controls_section(
			'section_button_style',
			[
				'label' => __( 'Button', 'extension-for-animation-addons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'button_typography',
				'selector' => '{{WRAPPER}} .wcf-popup-btn',
			]
		);

		$this->add_responsive_control(
			'button_width',
			[
				'label'      => esc_html__( 'Width', 'extension-for-animation-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'separator'  => 'before',
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 500,
						'step' => 5,
					],
					'%'  => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .wcf-popup-btn' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'button_height',
			[
				'label'      => esc_html__( 'Height', 'extension-for-animation-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 500,
						'step' => 5,
					],
					'%'  => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .wcf-popup-btn' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'      => 'button_border',
				'selector'  => '{{WRAPPER}} .wcf-popup-btn',
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'button_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'extension-for-animation-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .wcf-popup-btn'        => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .wcf-popup-btn:after'  => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .wcf-popup-btn:before' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .wcf-popup-btn .spinner_image' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'button_box_shadow',
				'selector' => '{{WRAPPER}} .wcf-popup-btn',
			]
		);

		$this->start_controls_tabs( 'tabs_button_style');

		$this->start_controls_tab(
			'tab_button_normal',
			[
				'label' => esc_html__( 'Normal', 'extension-for-animation-addons' ),
			]
		);

		$this->add_control(
			'button_text_color',
			[
				'label'     => esc_html__( 'Text Color', 'extension-for-animation-addons' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .wcf-popup-btn' => 'fill: {{VALUE}}; color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'button_background',
				'types'    => [ 'classic', 'gradient' ],
				'exclude'  => [ 'image' ],
				'selector' => '{{WRAPPER}} .wcf-popup-btn',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_button_hover',
			[
				'label' => esc_html__( 'Hover', 'extension-for-animation-addons' ),
			]
		);

		$this->add_control(
			'button_hover_text_color',
			[
				'label'     => esc_html__( 'Text Color', 'extension-for-animation-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wcf-popup-btn:hover, {{WRAPPER}} .wcf-popup-btn:focus' => 'color: {{VALUE}};',
					'{{WRAPPER}} .wcf-popup-btn:hover svg, {{WRAPPER}} .wcf-popup-btn:focus svg' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'button_background_hover',
				'types'    => [ 'classic', 'gradient' ],
				'exclude'  => [ 'image' ],
				'selector' => '{{WRAPPER}} .wcf-popup-btn:hover, {{WRAPPER}} .wcf-popup-btn:focus',
			]
		);

		$this->add_control(
			'button_hover_border_color',
			[
				'label'     => esc_html__( 'Border Color', 'extension-for-animation-addons' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => [
					'button_border_border!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .wcf-popup-btn:hover, {{WRAPPER}} .wcf-popup-btn:focus' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'hover_animation',
			[
				'label' => esc_html__( 'Hover Animation', 'extension-for-animation-addons' ),
				'type'  => Controls_Manager::HOVER_ANIMATION,
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	protected function register_video_controls() {
		$this->start_controls_section(
			'section_video_content',
			[
				'label' => __( 'Video', 'extension-for-animation-addons' ),
			]
		);

		$this->add_control(
			'video_link',
			[
				'label'       => esc_html__( 'Video Link', 'extension-for-animation-addons' ),
				'type'        => Controls_Manager::TEXT,
				'input_type'  => 'url',
				'placeholder' => 'https://www.youtube.com/watch?v=XHOmBV4js_E',
				'description' => esc_html__( 'YouTube/Vimeo link, or link to video file (mp4 is recommended).', 'extension-for-animation-addons' ),
				'label_block' => true,
				'default'     => '',
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Render the widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		$this->add_render_attribute( 'wrapper', 'class', 'wcf--video-popup' );
		?>
		<div <?php $this->print_render_attribute_string('wrapper'); ?>>
			<?php $this->render_popup_button( $settings ); ?>
		</div>
		<?php
	}

	protected function render_popup_button( $settings ) {
		$this->add_render_attribute( 'button', 'class', 'wcf-popup-btn ' );

		if ( ! empty( $settings['video_link'] ) ) {
			$this->add_render_attribute( 'button', 'href', $settings['video_link'] );
		} else {
			$this->add_render_attribute( 'button', 'role', 'button' );
		}

		if ( ! empty( $settings['active_ripple'] ) ) {
			$this->add_render_attribute( 'button', 'class', 'ripple' );
		}

		if ( ! empty( $settings['hover_animation'] ) ) {
			$this->add_render_attribute( 'button', 'class', 'elementor-animation-' . $settings['hover_animation'] );
		}

		$migrated = isset( $settings['__fa4_migrated']['btn_icon'] );
		$is_new   = empty( $settings['icon'] ) && Icons_Manager::is_migration_allowed();
		?>
		<a <?php $this->print_render_attribute_string('button'); ?> aria-label="<?php echo esc_html__('Popup Video Open Icon', 'extension-for-animation-addons'); ?>">
			<?php
			if ( ! empty( $settings['active_spinner'] ) ) {
				echo '<img class="spinner_image" src="' . esc_url( $settings['sipper_image']['url'] ) . '">';
			}
			?>
			<?php $this->print_unescaped_setting( 'btn_text' ); ?>
			<?php if ( $is_new || $migrated ) :
				Icons_Manager::render_icon( $settings['btn_icon'], [ 'aria-hidden' => 'true' ] );
			else : ?>
				<i class="<?php echo esc_attr( $settings['icon'] ); ?>" aria-hidden="true"></i>
			<?php endif; ?>
		</a>
		<?php
	}
}
