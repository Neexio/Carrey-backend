<?php

namespace SasslyEssentialApp\Widgets;

use Elementor\Control_Media;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Icons_Manager;
use Elementor\Plugin;
use Elementor\Repeater;
use Elementor\Utils;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Icon Box
 *
 * Elementor widget for testimonial.
 *
 * @since 1.0.0
 */
class Sassly_Notification extends Widget_Base {

	/**
	 * Retrieve the widget name.
	 *
	 * @return string Widget name.
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function get_name() {
		return 'sassly--notification';
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
		return esc_html__( 'Sassly Notification', 'sassly-essential' );
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
		return 'wcf eicon-notification';
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

	public function get_style_depends() {
		wp_register_style( 'sassly-notification', SASSLY_ESSENTIAL_ASSETS_URL . 'css/notification.css' );
		return [ 'sassly-notification' ];
	}

	public function get_script_depends() {
		wp_register_script( 'sassly-notification', SASSLY_ESSENTIAL_ASSETS_URL . '/js/widgets/notification.js', [ 'jquery' ], false, true );

		return [ 'sassly-notification' ];
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
		$this->start_controls_section(
			'section_notify',
			[
				'label' => esc_html__( 'Notification', 'sassly-essential' ),
			]
		);

		$this->add_control(
			'notify_text',
			[
				'label' => esc_html__( 'Text', 'sassly-essential' ),
				'type' => Controls_Manager::TEXTAREA,
				'default' => esc_html__( 'All plans have 30% OFF For this week.', 'sassly-essential' ),
				'description' => 'For Highlight, keep text in [ ]. Ex. [ Text ]',
			]
		);

		$this->add_control(
			'btn_text',
			[
				'label' => esc_html__( 'Button Text', 'sassly-essential' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Claim', 'sassly-essential' ),
			]
		);

		$this->add_control(
			'btn_link',
			[
				'label' => esc_html__( 'Button Link', 'sassly-essential' ),
				'type' => Controls_Manager::URL,
				'options' => [ 'url', 'is_external', 'nofollow' ],
				'label_block' => true,
			]
		);

		$this->add_responsive_control(
			'notify_align',
			[
				'label' => esc_html__( 'Alignment', 'sassly-essential' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'sassly-essential' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'sassly-essential' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'sassly-essential' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'toggle' => true,
				'selectors' => [
					'{{WRAPPER}} .sassly-notification' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'close_icon',
			[
				'label' => esc_html__( 'Close Icon', 'sassly-essential' ),
				'type' => Controls_Manager::ICONS,
				'skin' => 'inline',
				'label_block' => false,
				'default' => [
					'value' => 'fas e-far-window-close',
					'library' => 'fa-solid',
				],
			]
		);

		$this->add_control(
			'close_icon_pos',
			[
				'label' => esc_html__( 'Icon Position', 'sassly-essential' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'right',
				'prefix_class' => 'notify-icon-pos-',
				'options' => [
					'left' => esc_html__( 'Left', 'sassly-essential' ),
					'right' => esc_html__( 'Right', 'sassly-essential' ),
				],
			]
		);

		$this->end_controls_section();


		// Notification Style.
		$this->start_controls_section(
			'sec_style_notify',
			[
				'label' => esc_html__( 'Notification', 'sassly-essential' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'notify_bg',
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .sassly-notification',
			]
		);

		$this->add_responsive_control(
			'notify_padding',
			[
				'label' => esc_html__( 'Spacing', 'sassly-essential' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .sassly-notification' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		// Text Style.
		$this->start_controls_section(
			'sec_style_text',
			[
				'label' => esc_html__( 'Text', 'sassly-essential' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'text_color',
			[
				'label' => esc_html__( 'Color', 'sassly-essential' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .text' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'text_typo',
				'selector' => '{{WRAPPER}} .text',
			]
		);

		$this->add_responsive_control(
			'text_margin',
			[
				'label' => esc_html__( 'Spacing', 'sassly-essential' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .text' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'text_highlight',
			[
				'label' => esc_html__( 'Highlight', 'sassly-essential' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'highlight_color',
			[
				'label' => esc_html__( 'Color', 'sassly-essential' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .highlight' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'highlight_typo',
				'selector' => '{{WRAPPER}} .highlight',
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'highlight_bg',
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .highlight',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'highlight_border',
				'selector' => '{{WRAPPER}} .highlight',
			]
		);

		$this->add_responsive_control(
			'highlight_b_radius',
			[
				'label' => esc_html__( 'Border Radius', 'sassly-essential' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .highlight' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'highlight_padding',
			[
				'label' => esc_html__( 'Padding', 'sassly-essential' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .highlight' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		// Button Style.
		$this->start_controls_section(
			'sec_style_button',
			[
				'label' => esc_html__( 'Button', 'sassly-essential' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'btn_type',
			[
				'label' => esc_html__( 'Button Type', 'sassly-essential' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'inline-block',
				'prefix_class' => 'notify-icon-pos-',
				'options' => [
					'inline-block' => esc_html__( 'Inline', 'sassly-essential' ),
					'block' => esc_html__( 'Block', 'sassly-essential' ),
				],
				'selectors' => [
					'{{WRAPPER}} .text' => 'display: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'btn_typo',
				'selector' => '{{WRAPPER}} .notify-btn',
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'btn_bg',
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .notify-btn',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'btn_border',
				'selector' => '{{WRAPPER}} .notify-btn',
			]
		);

		$this->add_responsive_control(
			'btn_b_radius',
			[
				'label' => esc_html__( 'Border Radius', 'sassly-essential' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .notify-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'btn_padding',
			[
				'label' => esc_html__( 'Padding', 'sassly-essential' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .notify-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->start_controls_tabs(
			'btn_style_tabs'
		);

		// Normal
		$this->start_controls_tab(
			'btn_style_normal_tab',
			[
				'label' => esc_html__( 'Normal', 'sassly-essential' ),
			]
		);

		$this->add_control(
			'btn_color',
			[
				'label' => esc_html__( 'Color', 'sassly-essential' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .notify-btn' => 'color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		// Hover Tab
		$this->start_controls_tab(
			'btn_style_hover_tab',
			[
				'label' => esc_html__( 'Hover', 'sassly-essential' ),
			]
		);

		$this->add_control(
			'btn_h_color',
			[
				'label' => esc_html__( 'Color', 'sassly-essential' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .notify-btn:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'btn_b_h_color',
			[
				'label' => esc_html__( 'Border Color', 'sassly-essential' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .notify-btn:hover' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'btn_h_bg',
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .notify-btn:hover',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		// Close Icon Style.
		$this->start_controls_section(
			'style_close_icon',
			[
				'label' => esc_html__( 'Close Icon', 'sassly-essential' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'icon_color',
			[
				'label' => esc_html__( 'Color', 'sassly-essential' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .close-icon' => 'color: {{VALUE}}; fill: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'icon_h_color',
			[
				'label' => esc_html__( 'Hover Color', 'sassly-essential' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .close-icon:hover' => 'color: {{VALUE}}; fill: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'icon_size',
			[
				'label' => esc_html__( 'Icon Size', 'sassly-essential' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .close-icon' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'close_icon_space',
			[
				'label' => esc_html__( 'Spacing', 'sassly-essential' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .close-icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
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

		if ( ! empty( $settings['btn_link']['url'] ) ) {
			$this->add_link_attributes( 'btn_link', $settings['btn_link'] );
		}

		$text = $settings['notify_text'];
		preg_match_all( '/\[([^\]]*)\]/', $text, $matches );
		foreach ( $matches[0] as $key => $value ) {
			$text = str_replace( $value, '<span class="highlight">' . $matches[1][ $key ] . '</span>', $text, );
		}

		?>
        <div class="sassly-notification">
            <p class="text">
				<?php echo wp_kses_post( $text ); ?>
            </p>
            <a class="notify-btn" <?php $this->print_render_attribute_string( 'btn_link' ); ?>>
				<?php echo esc_html( $settings['btn_text'] ); ?>
            </a>
            <div class="close-icon">
	            <?php Icons_Manager::render_icon( $settings['close_icon'], [ 'aria-hidden' => 'true' ] ); ?>
            </div>
        </div>
		<?php

	}
}
