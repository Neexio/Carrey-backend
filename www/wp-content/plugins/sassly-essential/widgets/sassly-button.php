<?php

namespace SasslyEssentialApp\Widgets;

use Elementor\Controls_Manager;
use Elementor\Control_Media;
use Elementor\Group_Control_Background;
use Elementor\Utils;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Group_Control_Image_Size;
use Elementor\Repeater;
use Elementor\Group_Control_Css_Filter;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Plugin;
use Elementor\Widget_Base;
use Elementor\Icons_Manager;
use WP_Query;

if ( ! defined( 'ABSPATH' ) ) {
	exit;   // Exit if accessed directly.
}

class Sassly_Button extends \Elementor\Widget_Base {

	public function get_name() {
		return 'wcf--sassly-button';
	}

	public function get_title() {
		return esc_html__( 'Sassly Button', 'sassly-essential' );
	}

	public function get_icon() {
		return 'wcf eicon-button';
	}

	public function get_categories() {
		return [ 'weal-coder-addon' ];
	}

	public function get_style_depends() {
		wp_register_style( 'sassly-button', SASSLY_ESSENTIAL_ASSETS_URL . 'css/sassly-button.css' );

		return [ 'sassly-button' ];
	}

	public function register_content_controls() {
		$this->start_controls_section(
			'binox_sec_button',
			[
				'label' => __( 'Button', 'sassly-essential' ),
			]
		);

		$this->add_control(
			'btn_style',
			[
				'label' => esc_html__( 'Style', 'sassly-essential' ),
				'type' => Controls_Manager::SELECT,
				'default' => '1',
				'options' => [
					'1' => esc_html__( '1', 'sassly-essential' ),
					'2' => esc_html__( '2', 'sassly-essential' ),
					'3'  => esc_html__( '3', 'sassly-essential' ),
					'4'  => esc_html__( '4', 'sassly-essential' ),
				],
			]
		);


		$this->add_control(
			'btn_text',
			[
				'label'       => esc_html__( 'Text', 'sassly-essential' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Button', 'sassly-essential' ),
				'placeholder' => esc_html__( 'Type your text here', 'sassly-essential' ),
			]
		);

		$this->add_control(
			'btn_link',
			[
				'label'   => esc_html__( 'Link', 'sassly-essential' ),
				'type'    => Controls_Manager::URL,
				'dynamic' => [
					'active' => true,
				],
				'default' => [
					'url' => '#',
				],
			]
		);

		$this->add_control(
			'btn_icon',
			[
				'label'            => esc_html__( 'Icon', 'sassly-essential' ),
				'type'             => Controls_Manager::ICONS,
				'fa4compatibility' => 'icon',
				'skin'             => 'inline',
				'label_block'      => false,
				'default'          => [
					'value'   => 'fas fa-arrow-right',
					'library' => 'fa-solid',
				],
			]
		);

		$this->add_control(
			'btn_icon_position',
			[
				'label'        => esc_html__( 'Icon Position', 'sassly-essential' ),
				'type'         => Controls_Manager::SELECT,
				'default'      => 'row',
				'options'      => [
					'row'         => esc_html__( 'After', 'sassly-essential' ),
					'row-reverse' => esc_html__( 'Before', 'sassly-essential' ),
				],
				'selectors'    => [
					'{{WRAPPER}} .btn-text-flip' => 'flex-direction: {{VALUE}};',
				],
				'condition' => [
					'btn_style' => '3',
				],
			]
		);

		$this->add_responsive_control(
			'icon_gap',
			[
				'label' => esc_html__( 'Icon Gap', 'sassly-essential' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .btn-text-flip' => 'gap: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'btn_style' => '3',
				],
			]
		);

		$this->add_responsive_control(
			'btn_icon_width',
			[
				'label'     => esc_html__( 'Icon Width', 'sassly-essential' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .style-1 .wc-btn-play, {{WRAPPER}} .style-2 .wc-btn-play' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wc-btn-play' => '--icon-width: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'btn_style' => ['1', '2'],
				],
			]
		);

		$this->add_control(
			'btn_shape',
			[
				'label' => esc_html__( 'Choose Shape', 'sassly-essential' ),
				'type' => Controls_Manager::MEDIA,
				'condition' => [
					'btn_style' => '4',
				],
			]
		);

		$this->add_responsive_control(
			'btn_align',
			[
				'label'     => esc_html__( 'Alignment', 'sassly-essential' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => [
					'left'   => [
						'title' => esc_html__( 'Left', 'sassly-essential' ),
						'icon'  => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'sassly-essential' ),
						'icon'  => 'eicon-text-align-center',
					],
					'right'  => [
						'title' => esc_html__( 'Right', 'sassly-essential' ),
						'icon'  => 'eicon-text-align-right',
					],
				],
				'selectors' => [
					'{{WRAPPER}}' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();
	}


	public function register_style_controls() {
		// Text Style
		$this->start_controls_section(
			'binox_style_button',
			[
				'label' => esc_html__( 'Button', 'sassly-essential' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'btn_text_typo',
				'selector' => '{{WRAPPER}} .wc-btn-primary, {{WRAPPER}} .btn-border-crop',
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'btn_text_bg',
				'types'    => [ 'classic', 'gradient' ],
				'exclude'  => [ 'image' ],
				'selector' => '{{WRAPPER}} .wc-btn-primary',
				'condition' => [
					'btn_style' => ['1', '2', '3'],
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'btn_text_border',
				'selector' => '{{WRAPPER}} .wc-btn-primary',
				'condition' => [
					'btn_style' => ['1', '2', '3'],
				],
			]
		);

		$this->add_responsive_control(
			'text_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'sassly-essential' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .wc-btn-primary' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'btn_style' => ['1', '2', '3'],
				],
			]
		);

		$this->add_control(
			'btn_crop_b_color_1',
			[
				'label'     => esc_html__( 'Border Color One', 'sassly-essential' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .btn-border-crop:before' => '--b-color-1: {{VALUE}};',
				],
				'condition' => [
					'btn_style' => ['4'],
				],
			]
		);

		$this->add_control(
			'btn_crop_b_color_2',
			[
				'label'     => esc_html__( 'Border Color Two', 'sassly-essential' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .btn-border-crop:before' => '--b-color-2: {{VALUE}};',
				],
				'condition' => [
					'btn_style' => ['4'],
				],
			]
		);

		$this->add_control(
			'btn_crop_b_color_3',
			[
				'label'     => esc_html__( 'Border Color Three', 'sassly-essential' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .btn-border-crop:before' => '--b-color-3: {{VALUE}};',
				],
				'condition' => [
					'btn_style' => ['4'],
				],
			]
		);

		$this->add_control(
			'btn_crop_b_color_4',
			[
				'label'     => esc_html__( 'Border Color Four', 'sassly-essential' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .btn-border-crop:before' => '--b-color-4: {{VALUE}};',
				],
				'condition' => [
					'btn_style' => ['4'],
				],
			]
		);

		$this->add_responsive_control(
			'text_padding',
			[
				'label'      => esc_html__( 'Padding', 'sassly-essential' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .wc-btn-primary, {{WRAPPER}} .btn-border-crop' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Tabs
		$this->start_controls_tabs(
			'text_style_tabs'
		);

		$this->start_controls_tab(
			'text_normal_tab',
			[
				'label' => esc_html__( 'Normal', 'sassly-essential' ),
			]
		);

		$this->add_control(
			'btn_text_color',
			[
				'label'     => esc_html__( 'Color', 'sassly-essential' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wc-btn-primary, {{WRAPPER}} .btn-text-flip span, {{WRAPPER}} .btn-border-crop' => 'color: {{VALUE}}; fill: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'text_hover_tab',
			[
				'label' => esc_html__( 'Hover', 'sassly-essential' ),
			]
		);

		$this->add_control(
			'btn_text_h_color',
			[
				'label'     => esc_html__( 'Color', 'sassly-essential' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wc-btn-primary:hover, {{WRAPPER}} .btn-text-flip:hover span, {{WRAPPER}} .btn-border-crop:hover' => 'color: {{VALUE}}; fill: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'btn_text_h_bg',
				'types'    => [ 'classic', 'gradient' ],
				'exclude'  => [ 'image' ],
				'selector' => '{{WRAPPER}} .wc-btn-primary:hover',
				'condition' => [
					'btn_style' => ['1', '2', '3'],
				],
			]
		);

		$this->add_control(
			'btn_text_h_border',
			[
				'label'     => esc_html__( 'Border Color', 'sassly-essential' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wc-btn-primary:hover' => 'border-color: {{VALUE}}',
				],
				'condition' => [
					'btn_style' => ['1', '2', '3'],
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'btn_icon_size2',
			[
				'label'      => esc_html__( 'Icon Size', 'sassly-essential' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .btn-text-flip svg, {{WRAPPER}} .btn-text-flip i' => 'font-size: {{SIZE}}{{UNIT}};',
				],
                'separator' => 'before',
			]
		);

		$this->end_controls_section();


		// Icon Style
		$this->start_controls_section(
			'binox_style_btn_icon',
			[
				'label' => esc_html__( 'Icon', 'sassly-essential' ),
				'tab'   => Controls_Manager::TAB_STYLE,
				'condition' => [
					'btn_style' => ['1', '2'],
				],
			]
		);

		$this->add_responsive_control(
			'btn_icon_size',
			[
				'label'      => esc_html__( 'Size', 'sassly-essential' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .style-1 .wc-btn-play, {{WRAPPER}} .style-2 .wc-btn-play' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'btn_icon_bg',
				'types'    => [ 'classic', 'gradient' ],
				'exclude'  => [ 'image' ],
				'selector' => '{{WRAPPER}} .style-1 .wc-btn-play, {{WRAPPER}} .style-2 .wc-btn-play',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'btn_icon_border',
				'selector' => '{{WRAPPER}} .style-1 .wc-btn-play, {{WRAPPER}} .style-2 .wc-btn-play',
			]
		);

		$this->add_control(
			'icon_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'sassly-essential' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .style-1 .wc-btn-play, {{WRAPPER}} .style-2 .wc-btn-play' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Tabs
		$this->start_controls_tabs(
			'icon_style_tabs'
		);

		$this->start_controls_tab(
			'icon_normal_tab',
			[
				'label' => esc_html__( 'Normal', 'sassly-essential' ),
			]
		);

		$this->add_control(
			'btn_icon_color',
			[
				'label'     => esc_html__( 'Color', 'sassly-essential' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .style-1 .wc-btn-play, {{WRAPPER}} .style-2 .wc-btn-play' => 'color: {{VALUE}}; fill: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'icon_hover_tab',
			[
				'label' => esc_html__( 'Hover', 'sassly-essential' ),
			]
		);

		$this->add_control(
			'btn_icon_h_color',
			[
				'label'     => esc_html__( 'Color', 'sassly-essential' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .style-1 .wc-btn-play:hover, {{WRAPPER}} .style-2 .wc-btn-play:hover' => 'color: {{VALUE}}; fill: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'btn_icon_h_bg',
				'types'    => [ 'classic', 'gradient' ],
				'exclude'  => [ 'image' ],
				'selector' => '{{WRAPPER}} .style-1 .wc-btn-play:hover, {{WRAPPER}} .style-2 .wc-btn-play:hover',
			]
		);

		$this->add_control(
			'btn_icon_h_border',
			[
				'label'     => esc_html__( 'Border Color', 'sassly-essential' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .style-1 .wc-btn-play:hover, {{WRAPPER}} .style-2 .wc-btn-play:hover' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	protected function register_controls() {
		$this->register_content_controls();
		$this->register_style_controls();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		if ( ! empty( $settings['btn_link']['url'] ) ) {
			$this->add_link_attributes( 'btn_link', $settings['btn_link'] );
		}

		?>
        <div class="wc-btn-wrapper style-<?php echo esc_html( $settings['btn_style'] ); ?>">
	        <?php if ( '4' === $settings['btn_style'] ) {
		        $bg = empty( $settings['btn_shape']['url'] ) ? '' : " --btn-bg:url( " . $settings['btn_shape']['url'] . " )";
		        ?>
                <a <?php $this->print_render_attribute_string( 'btn_link' ); ?>
                        class="btn-text-flip btn-border-crop" style="<?php echo esc_attr($bg); ?>"
                >
                    <span data-text="<?php echo esc_html( $settings['btn_text'] ); ?>">
                        <?php echo esc_html( $settings['btn_text'] ); ?>
                    </span>
			        <?php Icons_Manager::render_icon( $settings['btn_icon'], [ 'aria-hidden' => 'true' ] ); ?>
                </a>
	        <?php } else if ( '3' === $settings['btn_style'] ) { ?>
                <a <?php $this->print_render_attribute_string( 'btn_link' ); ?> class="wc-btn-primary btn-text-flip">
                    <span data-text="<?php echo esc_html( $settings['btn_text'] ); ?>">
                        <?php echo esc_html( $settings['btn_text'] ); ?>
                    </span>
			        <?php Icons_Manager::render_icon( $settings['btn_icon'], [ 'aria-hidden' => 'true' ] ); ?>
                </a>
	        <?php } else { ?>
                <a <?php $this->print_render_attribute_string( 'btn_link' ); ?> class="wc-btn-group">
                <span class="wc-btn-play">
	                <?php Icons_Manager::render_icon( $settings['btn_icon'], [ 'aria-hidden' => 'true' ] ); ?>
                </span>
                    <span class="wc-btn-primary">
                    <?php echo esc_html( $settings['btn_text'] ); ?>
                </span>
                    <span class="wc-btn-play">
		            <?php Icons_Manager::render_icon( $settings['btn_icon'], [ 'aria-hidden' => 'true' ] ); ?>
                </span>
                </a>
			<?php } ?>
        </div>
		<?php
	}

}