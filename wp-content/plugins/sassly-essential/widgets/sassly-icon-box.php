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
class Sassly_Icon_Box extends Widget_Base {

	/**
	 * Retrieve the widget name.
	 *
	 * @return string Widget name.
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function get_name() {
		return 'sassly--icon-box';
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
		return esc_html__( 'Sassly Icon Box', 'sassly-essential' );
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
		return 'wcf eicon-icon-box';
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
		wp_register_style( 'sassly-icon-box', SASSLY_ESSENTIAL_ASSETS_URL . 'css/icon-box.css' );
		return [ 'sassly-icon-box' ];
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
			'sec_icon_box',
			[
				'label' => esc_html__( 'Icon Box', 'sassly-essential' ),
			]
		);

		$this->add_control(
			'box_icon',
			[
				'label' => esc_html__( 'Icon', 'sassly-essential' ),
				'type' => Controls_Manager::ICONS,
				'skin' => 'inline',
				'label_block' => false,
				'default' => [
					'value' => 'fas fa-circle',
					'library' => 'fa-solid',
				],
			]
		);

		$this->add_control(
			'box_title',
			[
				'label' => esc_html__( 'Title', 'sassly-essential' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'default' => esc_html__( 'Default title', 'sassly-essential' ),
				'placeholder' => esc_html__( 'Type your title here', 'sassly-essential' ),
			]
		);

		$this->add_control(
			'box_desc',
			[
				'label' => esc_html__( 'Description', 'sassly-essential' ),
				'type' => Controls_Manager::TEXTAREA,
				'placeholder' => esc_html__( 'Type your description here', 'sassly-essential' ),
			]
		);

		$this->add_control(
			'box_link',
			[
				'label' => esc_html__( 'Link', 'sassly-essential' ),
				'type' => Controls_Manager::URL,
				'options' => [ 'url', 'is_external', 'nofollow' ],
				'label_block' => true,
			]
		);

		$this->add_responsive_control(
			'box_align',
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
					'{{WRAPPER}} .sassly-icon-box' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'box_item_order',
			[
				'label' => esc_html__( 'Item Order', 'sassly-essential' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'icon_order',
			[
				'label' => esc_html__( 'Icon', 'sassly-essential' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 1,
				'max' => 3,
				'selectors' => [
					'{{WRAPPER}} .icon' => 'order: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'title_order',
			[
				'label' => esc_html__( 'Title', 'sassly-essential' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 1,
				'max' => 3,
				'selectors' => [
					'{{WRAPPER}} .title-wrap' => 'order: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'desc_order',
			[
				'label' => esc_html__( 'Desc', 'sassly-essential' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 1,
				'max' => 3,
				'selectors' => [
					'{{WRAPPER}} .desc' => 'order: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();

		//Icon Box Style
		$this->start_controls_section(
			'sec_style_icon_box',
			[
				'label' => esc_html__( 'Icon Box', 'sassly-essential' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'box_bg',
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .sassly-icon-box',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'box_border',
				'selector' => '{{WRAPPER}} .sassly-icon-box',
			]
		);

		$this->add_responsive_control(
			'box_b_radius',
			[
				'label' => esc_html__( 'Border Radius', 'sassly-essential' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors' => [
					'{{WRAPPER}} .sassly-icon-box' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'box_padding',
			[
				'label' => esc_html__( 'Padding', 'sassly-essential' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors' => [
					'{{WRAPPER}} .sassly-icon-box' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		// Icon Style.
		$this->start_controls_section(
			'sec_style_box_icon',
			[
				'label' => esc_html__( 'Icon', 'sassly-essential' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'icon_color',
			[
				'label' => esc_html__( 'Color', 'sassly-essential' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .icon' => 'color: {{VALUE}}; fill: {{VALUE}}',
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
					'{{WRAPPER}} .icon' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'icon_bg',
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .icon',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'icon_border',
				'selector' => '{{WRAPPER}} .icon',
			]
		);

		$this->add_responsive_control(
			'icon_b_radius',
			[
				'label' => esc_html__( 'Border Radius', 'sassly-essential' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors' => [
					'{{WRAPPER}} .icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'icon_style',
			[
				'label' => esc_html__( 'Icon Style', 'sassly-essential' ),
				'type' => Controls_Manager::SELECT,
				'prefix_class' => 'sassly-icon-box-icon-',
				'default' => '',
				'options' => [
					'' => esc_html__( 'Default', 'sassly-essential' ),
					'circle' => esc_html__( 'Circle', 'sassly-essential' ),
				],
			]
		);

		$this->add_responsive_control(
			'icon_wrapper',
			[
				'label' => esc_html__( 'Wrapper Size', 'sassly-essential' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'condition' => [
				        'icon_style' => 'circle',
                ],
				'selectors' => [
					'{{WRAPPER}} .icon' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'icon_margin',
			[
				'label' => esc_html__( 'Spacing', 'sassly-essential' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'icon_align',
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
					'{{WRAPPER}} .icon' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();

		// Title Style.
		$this->start_controls_section(
			'sec_style_title',
			[
				'label' => esc_html__( 'Title', 'sassly-essential' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'title_color',
			[
				'label' => esc_html__( 'Color', 'sassly-essential' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .title' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'title_typo',
				'selector' => '{{WRAPPER}} .title',
			]
		);

		$this->add_responsive_control(
			'title_margin',
			[
				'label' => esc_html__( 'Spacing', 'sassly-essential' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'title_align',
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
					'{{WRAPPER}} .title' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();

		// Desc Style.
		$this->start_controls_section(
			'sec_style_desc',
			[
				'label' => esc_html__( 'Description', 'sassly-essential' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'desc_color',
			[
				'label' => esc_html__( 'Color', 'sassly-essential' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .desc' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'desc_typo',
				'selector' => '{{WRAPPER}} .desc',
			]
		);

		$this->add_responsive_control(
			'desc_margin',
			[
				'label' => esc_html__( 'Spacing', 'sassly-essential' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .desc' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'desc_align',
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
					'{{WRAPPER}} .desc' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();

		// Hover Style.
		$this->start_controls_section(
			'icon_box_h_style',
			[
				'label' => esc_html__( 'Hover', 'sassly-essential' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'box_h_b_color',
			[
				'label' => esc_html__( 'Border Color', 'sassly-essential' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .sassly-icon-box:hover' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'box_h_bg_color',
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .sassly-icon-box:hover',
			]
		);

		$this->add_control(
			'box_h_icon_color',
			[
				'label' => esc_html__( 'Icon Color', 'sassly-essential' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .sassly-icon-box:hover .icon' => 'color: {{VALUE}}; fill: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'box_h_icon_b_color',
			[
				'label' => esc_html__( 'Icon Border Color', 'sassly-essential' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .sassly-icon-box:hover .icon' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'box_h_icon_bg',
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .sassly-icon-box:hover .icon',
			]
		);

		$this->add_control(
			'box_h_title_color',
			[
				'label' => esc_html__( 'Title Color', 'sassly-essential' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .sassly-icon-box:hover .title' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'box_h_desc_color',
			[
				'label' => esc_html__( 'Desc Color', 'sassly-essential' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .sassly-icon-box:hover .desc' => 'color: {{VALUE}}',
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

		if ( ! empty( $settings['box_link']['url'] ) ) {
			$this->add_link_attributes( 'box_link', $settings['box_link'] );
		}

		?>
        <a <?php $this->print_render_attribute_string( 'box_link' ); ?> class="sassly-icon-box">
            <div class="icon">
	            <?php Icons_Manager::render_icon( $settings['box_icon'], [ 'aria-hidden' => 'true' ] ); ?>
            </div>
            <div class="title-wrap">
                <h2 class="title"><?php echo esc_html($settings['box_title']); ?></h2>
            </div>
            <div class="desc">
                <p><?php echo esc_html($settings['box_desc']); ?></p>
            </div>
        </a>
		<?php

	}


}
