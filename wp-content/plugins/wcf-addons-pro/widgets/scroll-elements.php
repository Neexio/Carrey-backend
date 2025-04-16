<?php

namespace WCFAddonsPro\Widgets;

use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Icons_Manager;
use Elementor\Plugin;
use Elementor\Repeater;
use Elementor\Utils;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

/**
 * Title
 *
 * Elementor widget for title.
 *
 * @since 1.0.0
 */
class Scroll_Elements extends Widget_Base {

	/**
	 * Retrieve the widget name.
	 *
	 * @return string Widget name.
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function get_name() {
		return 'wcf--scroll-elements';
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
		return esc_html__( 'WCF Scroll Elements', 'wcf-addons-pro' );
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
		return 'wcf eicon-scroll';
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
		return [ 'wcf-addons-pro' ];
	}

	/**
	 * Requires css files.
	 *
	 * @return array
	 */
	public function get_style_depends() {
		return [ 'wcf--scroll-elements' ];
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
			'section_content',
			[
				'label' => __( 'Scroll Contents', 'wcf-addons-pro' ),
			]
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'scroll_icon',
			[
				'label'       => esc_html__( 'Icon', 'wcf-addons-pro' ),
				'type'        => Controls_Manager::ICONS,
				'default'     => [
					'value'   => 'fas fas fa-home',
					'library' => 'fa-solid',
				],
				'skin'        => 'inline',
				'label_block' => false,
			]
		);

		$repeater->add_control(
			'scroll_title',
			[
				'label'       => esc_html__( 'Title', 'wcf-addons-pro' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Scroll Title', 'wcf-addons-pro' ),
				'separator' => 'after',
				'label_block' => true,
				'dynamic'     => [
					'active' => true,
				],
			]
		);

		$repeater->add_control(
			'scroll_image',
			[
				'label' => esc_html__( 'Image', 'wcf-addons-pro' ),
				'type' => Controls_Manager::MEDIA,
				'separator' => 'after',
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
			]
		);

		$repeater->add_control(
			'scroll_content_type',
			[
				'label'   => esc_html__('Content Type', 'wcf-addons-pro'),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'content'  => esc_html__('Content', 'wcf-addons-pro'),
					'template' => esc_html__('Saved Templates', 'wcf-addons-pro'),
				],
				'default' => 'content',
			]
		);

		$repeater->add_control(
			'elementor_templates',
			[
				'label'       => esc_html__( 'Save Template', 'wcf-addons-pro' ),
				'type'        => Controls_Manager::SELECT2,
				'label_block' => false,
				'multiple'    => false,
				'options'     => wcf_addons_get_saved_template_list(),
				'condition'   => [
					'scroll_content_type' => 'template',
				],
			]
		);

		$repeater->add_control(
			'scroll_content',
			[
				'label'       => esc_html__( 'Content', 'wcf-addons-pro' ),
				'default'     => esc_html__( 'Tab Content', 'wcf-addons-pro' ),
				'type'        => Controls_Manager::WYSIWYG,
				'condition'   => [
					'scroll_content_type' => 'content',
				],
			]
		);

		$this->add_control(
			'scroll_items',
			[
				'label'       => esc_html__( 'Scroll Items', 'wcf-addons-pro' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'default'     => [
					[
						'scroll_title'   => esc_html__( 'Scroll Title 1', 'wcf-addons-pro' ),
						'scroll_content' => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'wcf-addons-pro' ),
					],
					[
						'scroll_title'   => esc_html__( 'Scroll Title 2', 'wcf-addons-pro' ),
						'scroll_content' => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'wcf-addons-pro' ),
					],
					[
						'scroll_title'   => esc_html__( 'Scroll Title 3', 'wcf-addons-pro' ),
						'scroll_content' => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'wcf-addons-pro' ),
					],
				],
				'title_field' => '{{{ scroll_title }}}',
			]
		);

		$this->end_controls_section();

		//settings
		$this->start_controls_section( 'section_scroll_setting', [
			'label' => esc_html__( 'Settings', 'wcf-addons-pro' ),
		] );

		$this->add_control(
			'show_navigation',
			[
				'label'        => esc_html__( 'Show Navigation/Title', 'wcf-addons-pro' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'wcf-addons-pro' ),
				'label_off'    => esc_html__( 'Hide', 'wcf-addons-pro' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->add_control(
			'show_image',
			[
				'label'        => esc_html__( 'Show Image', 'wcf-addons-pro' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'wcf-addons-pro' ),
				'label_off'    => esc_html__( 'Hide', 'wcf-addons-pro' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$dropdown_options     = [
			'none' => esc_html__( 'None', 'wcf-addons-pro' ),
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
				esc_html__( '%1$s (%2$s %3$dpx)', 'wcf-addons-pro' ),
				$breakpoint_instance->get_label(),
				'>',
				$breakpoint_instance->get_value()
			);
		}

		$this->add_control(
			'scroll_elements_breakpoint_selector',
			[
				'label'        => esc_html__( 'Breakpoint', 'wcf-addons-pro' ),
				'type'         => Controls_Manager::SELECT,
				'description'  => esc_html__( 'Note: Choose at which breakpoint Scroll navigation and Image will automatically display None.', 'wcf-addons-pro' ),
				'options'      => $dropdown_options,
				'default'      => 'mobile',
				'prefix_class' => 'wcf-scroll-',
			]
		);

		$this->end_controls_section();

		$this->register_navigation_controls();

        $this->register_navigation_style_controls();

        $this->register_scroll_image_style_controls();

        $this->register_scroll_content_style_controls();
	}

	protected function register_navigation_controls() {

		$this->start_controls_section(
			'section_scroll_nav',
			[
				'label'     => esc_html__( 'Scroll Navigation/Title', 'wcf-addons-pro' ),
				'condition' => [ 'show_navigation!' => '' ]
			]
		);

		$this->add_responsive_control(
			'scroll_tittle_align',
			[
				'label'     => esc_html__( 'Navigation Alignment', 'wcf-addons-pro' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => [
					'start'  => [
						'title' => esc_html__( 'Left', 'wcf-addons-pro' ),
						'icon'  => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'wcf-addons-pro' ),
						'icon'  => 'eicon-text-align-center',
					],
					'end'    => [
						'title' => esc_html__( 'Right', 'wcf-addons-pro' ),
						'icon'  => 'eicon-text-align-right',
					],
				],
				'default'   => 'center',
				'selectors' => [
					'{{WRAPPER}} .scroll-title' => 'text-align: {{VALUE}}; justify-content:{{VALUE}};',
				],
			]
		);

		$this->add_control( 'scroll_navigation_direction',
			[
				'label'     => esc_html__( 'Navigation Direction', 'wcf-addons-pro' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => [
					'row-reverse' => [
						'title' => esc_html__( 'After', 'wcf-addons-pro' ),
						'icon'  => 'eicon-h-align-right'
					],
					'row'         => [
						'title' => esc_html__( 'Before', 'wcf-addons-pro' ),
						'icon'  => 'eicon-h-align-left'
					],
				],
				'selectors' => [
					'{{WRAPPER}} .wcf--scroll-elements' => 'flex-direction: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'scroll_navigation_align',
			[
				'label'     => esc_html__( 'Navigation Alignment', 'wcf-addons-pro' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => [
					''              => esc_html__( 'Start', 'wcf-addons-pro' ),
					'center'        => esc_html__( 'Center', 'wcf-addons-pro' ),
					'end'           => esc_html__( 'End', 'wcf-addons-pro' ),
					'space-between' => esc_html__( 'Space Between', 'wcf-addons-pro' ),
				],
				'selectors' => [
					'{{WRAPPER}} .scroll-nav-bar' => 'justify-content: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'navigation_width',
			[
				'label'     => esc_html__( 'Width', 'wcf-addons-pro' ),
				'type'      => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'separator' => 'before',
				'default'   => [
					'unit' => '%',
				],
				'range'     => [
					'%' => [
						'min' => 10,
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .scroll-nav-bar' => 'width: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_responsive_control(
			'navigation_gap',
			[
				'label' => esc_html__( 'Gap', 'wcf-addons-pro' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 500,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .wcf--scroll-elements' => 'gap: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function register_navigation_style_controls() {
		$this->start_controls_section(
			'section_scroll_nav_style',
			[
				'label' => esc_html__( 'Scroll Navigation/Title', 'wcf-addons-pro' ),
				'tab'   => Controls_Manager::TAB_STYLE,
				'condition' => [ 'show_navigation!' => '' ]
			]
		);

		$this->add_responsive_control( 'scroll_title_space_between',
			[
				'label'      => esc_html__( 'Gap between Titles', 'wcf-addons-pro' ),
				'separator'  => 'before',
				'type'       => Controls_Manager::SLIDER,
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 400,
					],
				],
				'size_units' => [ 'px' ],
				'selectors'  => [
					'{{WRAPPER}} .scroll-nav-bar' => 'gap: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'title_typography',
				'selector' => '{{WRAPPER}} .scroll-title',
			]
		);

		$this->add_responsive_control(
			'title_icon_size',
			[
				'label'      => esc_html__( 'Icon Size', 'wcf-addons-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range'      => [
					'px' => [
						'min'  => 1,
						'max'  => 100,
						'step' => 1,
					],
					'%'  => [
						'min' => 1,
						'max' => 100,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .scroll-title i'   => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .scroll-title svg' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'title_icon_margin',
			[
				'label'      => esc_html__( 'Icon Margin', 'wcf-addons-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .scroll-title i, {{WRAPPER}} .scroll-title svg' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->start_controls_tabs( 'scroll_title_style' );

		$this->start_controls_tab(
			'scroll_title_normal',
			[
				'label' => esc_html__( 'Normal', 'wcf-addons-pro' ),
			]
		);

		$this->add_control(
			'title_text_color',
			[
				'label'     => esc_html__( 'Text Color', 'wcf-addons-pro' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .scroll-title' => 'fill: {{VALUE}}; color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'title_background',
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .scroll-title',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'scroll_title_hover',
			[
				'label' => esc_html__( 'Hover/Active', 'wcf-addons-pro' ),
			]
		);

		$this->add_control(
			'title_text_hover_color',
			[
				'label'     => esc_html__( 'Text Color', 'wcf-addons-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .scroll-title:hover, {{WRAPPER}} .scroll-title.active' => 'color: {{VALUE}};fill: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'title_hover_background',
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .scroll-title:hover, {{WRAPPER}} .scroll-title.active',
			]
		);

		$this->add_control(
			'title_hover_border_color',
			[
				'label'     => esc_html__( 'Border Color', 'wcf-addons-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .scroll-title:hover, {{WRAPPER}} .scroll-title.active' => 'border-color: {{VALUE}};',
				],
				'condition' => [
					'title_border_border!' => '',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'      => 'title_border',
				'selector'  => '{{WRAPPER}} .scroll-title',
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'title_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'wcf-addons-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .scroll-title' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',

				],
			]
		);

		$this->add_responsive_control(
			'title_padding',
			[
				'label'      => esc_html__( 'Padding', 'wcf-addons-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .scroll-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator'  => 'before',
			]
		);

		$this->end_controls_section();
	}

	protected function register_scroll_image_style_controls() {
		$this->start_controls_section( 'section_image_style', [
			'label'     => esc_html__( 'Image', 'wcf-addons-pro' ),
			'tab'       => Controls_Manager::TAB_STYLE,
			'condition' => [ 'show_image!' => '' ]
		] );

		$this->add_responsive_control(
			'image_width',
			[
				'label'     => esc_html__( 'Width', 'wcf-addons-pro' ),
				'type'      => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .scroll-images' => 'width: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_responsive_control(
			'image_height',
			[
				'label'      => esc_html__( 'Height', 'wcf-addons-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'vh', 'em', 'rem', 'custom' ],
				'range'      => [
					'px' => [
						'min' => 100,
						'max' => 1000,
					],
					'%'  => [
						'min' => 10,
						'max' => 100,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .image-wrap' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'image_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'wcf-addons-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .image-wrap' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control( 'image_direction',
			[
				'label'     => esc_html__( 'Image Direction', 'wcf-addons-pro' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => [
					'row-reverse' => [
						'title' => esc_html__( 'After', 'wcf-addons-pro' ),
						'icon'  => 'eicon-h-align-right'
					],
					'row'         => [
						'title' => esc_html__( 'Before', 'wcf-addons-pro' ),
						'icon'  => 'eicon-h-align-left'
					],
				],
				'selectors' => [
					'{{WRAPPER}} .scroll-content-wrap' => 'flex-direction: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'image_gap',
			[
				'label'      => esc_html__( 'Gap', 'wcf-addons-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 500,
					],
					'%'  => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .scroll-content-wrap' => 'gap: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function register_scroll_content_style_controls() {
		$this->start_controls_section( 'section_content_style', [
			'label' => esc_html__( 'Content', 'wcf-addons-pro' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'content_background_color',
				'types'    => [ 'classic', 'gradient' ],
				'exclude'  => [ 'image' ],
				'selector' => '{{WRAPPER}} .single-content',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'content_border',
				'selector' => '{{WRAPPER}} .single-content',
			]
		);

		$this->add_responsive_control(
			'content_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'wcf-addons-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .single-content' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'content_padding',
			[
				'label'      => esc_html__( 'Padding', 'wcf-addons-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .single-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
		$settings     = $this->get_settings_for_display();
		$scroll_items = $this->get_settings_for_display( 'scroll_items' );
		$this->add_render_attribute( 'wrapper', [
				'class'           => 'wcf--scroll-elements',
				'data-navigation' => $settings['show_navigation'],
				'data-image'      => $settings['show_image']
			]
		);
		?>
        <div <?php $this->print_render_attribute_string( 'wrapper' ); ?>>
            <?php if ( 'yes' === $settings['show_navigation'] ) :  ?>
			<div class="scroll-nav-bar">
				<?php
				foreach ( $scroll_items as $index => $item ) :
					$tab_count             = $index + 1;
					$tab_setting_key = $this->get_repeater_setting_key( 'scroll_title', 'scroll_items', $index );
					$this->add_render_attribute( $tab_setting_key, [
						'class'         => [ 'scroll-title', 1 === $tab_count ? 'active' : '' ],
					] );
					?>
                    <div <?php $this->print_render_attribute_string( $tab_setting_key ); ?>>
						<?php Icons_Manager::render_icon( $item['scroll_icon'], [ 'aria-hidden' => 'true' ] ); ?>
						<?php $this->print_unescaped_setting( 'scroll_title', 'scroll_items', $index ); ?>
                    </div>
				<?php endforeach; ?>
			</div>
            <?php endif; ?>

            <div class="scroll-content-wrap">
	            <?php if ( 'yes' === $settings['show_image'] ) : ?>
                    <div class="scroll-images">
			            <?php
			            foreach ( $scroll_items as $index => $item ) :
				            ?>
                            <div class="image-wrap">
                                <img src="<?php echo esc_url( $item['scroll_image']['url'] ); ?>"
                                     alt="<?php echo esc_attr( $item['scroll_title'] ); ?>">
                            </div>
			            <?php endforeach; ?>
                    </div>
	            <?php endif; ?>
                <div class="scroll-contents">
		            <?php
		            foreach ( $scroll_items as $index => $item ) :
			            ?>
                        <div class="single-content">
				            <?php
				            if ( 'content' === $item['scroll_content_type'] ) {
					            $this->print_text_editor( $item['scroll_content'] );
				            } else {
					            if ( ! empty( $item['elementor_templates'] ) ) {
						            echo Plugin::$instance->frontend->get_builder_content( $item['elementor_templates'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					            }
				            }
				            ?>
                        </div>
		            <?php endforeach; ?>
                </div>
            </div>
		</div>
		<?php
	}
}
