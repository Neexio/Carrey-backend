<?php

namespace WCFAddonsPro\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Icons_Manager;
use Elementor\Plugin;
use Elementor\Repeater;
use Elementor\Utils;
use Elementor\Widget_Base;
use WCF_ADDONS\WCF_Button_Trait;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Advance_Slider extends Widget_Base {

	use  WCF_Button_Trait;

	public function get_name() {
		return 'wcf--advance-slider';
	}

	public function get_title() {
		return esc_html__( 'WCF Advance Slider', 'wcf-addons-pro' );
	}

	public function get_icon() {
		return 'wcf eicon-post-slider';
	}

	public function get_categories() {
		return [ 'wcf-addons-pro' ];
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
		return [ 'swiper', 'advance-slider-effects', 'wcf--advance-slider' ];
	}

	/**
	 * Requires css files.
	 *
	 * @return array
	 */
	public function get_style_depends() {
		return [ 'wcf--advance-slider', 'wcf--button' ];
	}

	protected function register_controls() {
		$this->start_controls_section(
			'section_content',
			[
				'label' => esc_html__( 'Slider', 'wcf-addons-pro' ),
			]
		);

		$this->add_control(
			'element_list',
			[
				'label'   => esc_html__( 'Slider Layout', 'wcf-addons-pro' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'shaders',
				'options' => [
					'shaders'  => esc_html__( 'Shaders Slider', 'wcf-addons-pro' ),
					'slicer'   => esc_html__( 'Slicer Slider', 'wcf-addons-pro' ),
					'shutters' => esc_html__( 'Shutters Slider', 'wcf-addons-pro' ),
					'fashion'  => esc_html__( 'Fashion Slider', 'wcf-addons-pro' ),
					'spring'   => esc_html__( 'Spring Slider', 'wcf-addons-pro' ),
					'material' => esc_html__( 'Material Slider', 'wcf-addons-pro' ),
					'posters'  => esc_html__( 'Posters Slider', 'wcf-addons-pro' ),
					'carousel' => esc_html__( 'Carousel Slider', 'wcf-addons-pro' ),
				],
			]
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'slide_image',
			[
				'label'   => esc_html__( 'Choose Image', 'wcf-addons-pro' ),
				'type'    => Controls_Manager::MEDIA,
				'dynamic' => [
					'active' => true,
				],
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
			]
		);

		$repeater->add_control(
			'slide_title',
			[
				'label'   => esc_html__( 'Title', 'wcf-addons-pro' ),
				'type'    => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Doctor Strange',
			]
		);

		$repeater->add_control(
			'slide_content',
			[
				'label'   => esc_html__( 'Content', 'wcf-addons-pro' ),
				'type'    => Controls_Manager::TEXTAREA,
				'dynamic' => [
					'active' => true,
				],
				'rows'    => '10',
				'default' => esc_html__( 'America Chavez and a version of Stephen Strange are chased by a demon in the space between universes while searching for the Book of Vishanti.', 'wcf-addons-pro' ),
			]
		);

		$repeater->add_control(
			'slide_link',
			[
				'label'       => esc_html__( 'Link', 'wcf-addons-pro' ),
				'type'        => Controls_Manager::URL,
				'dynamic'     => [
					'active' => true,
				],
				'placeholder' => esc_html__( 'https://your-link.com', 'wcf-addons-pro' ),
			]
		);

		$repeater->add_control(
			'slide_color',
			[
				'label' => esc_html__( 'Slide Background', 'wcf-addons-pro' ),
				'description' => esc_html__( 'This settings only for layout "Fashion slider"', 'wcf-addons-pro' ),
				'type'  => Controls_Manager::COLOR,
			]
		);

		$this->add_control(
			'slider',
			[
				'label'   => esc_html__( 'Testimonials', 'wcf-addons-pro' ),
				'type'    => Controls_Manager::REPEATER,
				'fields'  => $repeater->get_controls(),
				'default' => [ [], [], [], [], [] ],
			]
		);

		$this->add_control(
			'title_tag',
			[
				'label'   => esc_html__( 'Title Tag', 'wcf-addons-pro' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'h1'   => 'H1',
					'h2'   => 'H2',
					'h3'   => 'H3',
					'h4'   => 'H4',
					'h5'   => 'H5',
					'h6'   => 'H6',
					'div'  => 'div',
					'span' => 'span',
					'p'    => 'p',
				],
				'default' => 'h4',
			]
		);

		$this->add_control(
			'title_position',
			[
				'label'        => esc_html__( 'Title Position', 'wcf-addons-pro' ),
				'type'         => Controls_Manager::CHOOSE,
				'options'      => [
					'before' => [
						'title' => esc_html__( 'Before Content', 'wcf-addons-pro' ),
						'icon'  => 'eicon-arrow-up',
					],
					'after'  => [
						'title' => esc_html__( 'After Content', 'wcf-addons-pro' ),
						'icon'  => 'eicon-arrow-down',
					],
				],
				'toggle'       => true,
				'prefix_class' => 'title-position-'
			]
		);

		$this->add_control(
			'link_type',
			[
				'label'     => esc_html__( 'Link Type', 'wcf-addons-pro' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'title',
				'separator' => 'before',
				'options'   => [
					'none'   => esc_html__( 'None', 'wcf-addons-pro' ),
					'button' => esc_html__( 'Button', 'wcf-addons-pro' ),
					'title'  => esc_html__( 'Title', 'wcf-addons-pro' ),
				],
			]
		);

		$this->end_controls_section();

		//button
		$this->start_controls_section(
			'section_button_content',
			[
				'label'     => esc_html__( 'Button', 'wcf-addons-pro' ),
				'condition' => [
					'link_type' => 'button',
				],
			]
		);

		$this->register_button_content_controls( [ 'btn_text' => 'Reade More ' ], [ 'btn_link' => false ] );

		$this->end_controls_section();

		//slider settings
		$this->register_slider_settings_controls();

		//style controls
		$this->register_content_style_controls();

		//button style
		$this->start_controls_section(
			'section_btn_style',
			[
				'label'     => esc_html__( 'Button', 'wcf-addons-pro' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'link_type' => 'button',
				],
			]
		);

		$this->register_button_style_controls();

		$this->end_controls_section();

		$this->slider_navigation_style_controls();

		$this->slider_pagination_style_controls();
	}

	protected function register_slider_settings_controls() {
		$this->start_controls_section(
			'section_slider_settings',
			[
				'label' => esc_html__( 'Slider Settings', 'wcf-addons-pro' ),
			]
		);

		//shader style
		$this->add_control(
			'shader_style',
			[
				'label'     => esc_html__( 'Shader Style', 'wcf-addons-pro' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'random',
				'options'   => [
					'random'         => esc_html__( 'random', 'wcf-addons-pro' ),
					'dots'           => esc_html__( 'dots', 'wcf-addons-pro' ),
					'flyeye'         => esc_html__( 'flyeye', 'wcf-addons-pro' ),
					'morph-x'        => esc_html__( 'morph-x', 'wcf-addons-pro' ),
					'morph-y'        => esc_html__( 'morph-y', 'wcf-addons-pro' ),
					'page-curl'      => esc_html__( 'page-curl', 'wcf-addons-pro' ),
					'peel-x'         => esc_html__( 'peel-x', 'wcf-addons-pro' ),
					'peel-y'         => esc_html__( 'peel-y', 'wcf-addons-pro' ),
					'polygons-fall'  => esc_html__( 'polygons-fall', 'wcf-addons-pro' ),
					'polygons-morph' => esc_html__( 'polygons-morph', 'wcf-addons-pro' ),
					'polygons-wind'  => esc_html__( 'polygons-wind', 'wcf-addons-pro' ),
					'pixelize'       => esc_html__( 'pixelize', 'wcf-addons-pro' ),
					'ripple'         => esc_html__( 'ripple', 'wcf-addons-pro' ),
					'shutters'       => esc_html__( 'shutters', 'wcf-addons-pro' ),
					'slices'         => esc_html__( 'slices', 'wcf-addons-pro' ),
					'squares'        => esc_html__( 'squares', 'wcf-addons-pro' ),
					'stretch'        => esc_html__( 'stretch', 'wcf-addons-pro' ),
					'wave-x'         => esc_html__( 'wave-x', 'wcf-addons-pro' ),
					'wind'           => esc_html__( 'squares', 'wcf-addons-pro' ),
				],
				'condition' => [ 'element_list' => 'shaders' ]
			]
		);

		//slicer/shutter
		$this->add_control(
			'slide_split',
			[
				'label'     => esc_html__( 'Split', 'wcf-addons-pro' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => '5',
				'options'   => [
					'2'  => esc_html__( '2', 'wcf-addons-pro' ),
					'3'  => esc_html__( '3', 'wcf-addons-pro' ),
					'4'  => esc_html__( '4', 'wcf-addons-pro' ),
					'5'  => esc_html__( '5', 'wcf-addons-pro' ),
					'6'  => esc_html__( '6', 'wcf-addons-pro' ),
					'7'  => esc_html__( '7', 'wcf-addons-pro' ),
					'8'  => esc_html__( '8', 'wcf-addons-pro' ),
					'9'  => esc_html__( '9', 'wcf-addons-pro' ),
					'10' => esc_html__( '10', 'wcf-addons-pro' ),
					'11' => esc_html__( '11', 'wcf-addons-pro' ),
					'12' => esc_html__( '12', 'wcf-addons-pro' ),
					'13' => esc_html__( '13', 'wcf-addons-pro' ),
					'14' => esc_html__( '14', 'wcf-addons-pro' ),
					'15' => esc_html__( '15', 'wcf-addons-pro' ),
				],
				'condition' => [ 'element_list' => ['slicer', 'shutters'] ]
			]
		);

		$this->add_control(
			'slide_direction',
			[
				'label'     => esc_html__( 'Direction', 'wcf-addons-pro' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'vertical',
				'options'   => [
					'vertical'   => esc_html__( 'Vertical', 'wcf-addons-pro' ),
					'horizontal' => esc_html__( 'Horizontal', 'wcf-addons-pro' ),
				],
				'condition' => [ 'element_list' => ['slicer', 'shutters'] ]
			]
		);

		//spring slider
		$slides_to_show = range( 1, 10 );
		$slides_to_show = array_combine( $slides_to_show, $slides_to_show );

		$this->add_responsive_control(
			'slides_to_show',
			[
				'label'       => esc_html__( 'Slides to Show', 'wcf-addons-pro' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => '4',
				'required'    => true,
				'options'     => [
					                 'auto' => esc_html__( 'Auto', 'wcf-addons-pro' ),
				                 ] + $slides_to_show,
				'render_type' => 'template',
				'condition'   => [ 'element_list' => [ 'spring', 'carousel' ] ]
			]
		);

		//material slider
		$this->add_control(
			'center_slide',
			[
				'label'        => esc_html__( 'Center Slide', 'wcf-addons-pro' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'wcf-addons-pro' ),
				'label_off'    => esc_html__( 'No', 'wcf-addons-pro' ),
				'return_value' => 'yes',
				'condition'    => [ 'element_list' => [ 'material' ] ]
			]
		);

		$this->add_control(
			'mousewheel',
			[
				'label'       => esc_html__( 'Mousewheel', 'wcf-addons-pro' ),
				'type'        => Controls_Manager::SWITCHER,
				'label_on'    => esc_html__( 'Show', 'wcf-addons-pro' ),
				'label_off'   => esc_html__( 'Hide', 'wcf-addons-pro' ),
				'default'     => '',
			]
		);

		//slider navigation
		$this->add_control(
			'navigation',
			[
				'label'     => esc_html__( 'Navigation', 'wcf-addons-pro' ),
				'type'      => Controls_Manager::SELECT,
				'separator' => 'before',
				'default'   => 'both',
				'options'   => [
					'both'   => esc_html__( 'Arrows and Pagination', 'wcf-addons-pro' ),
					'arrows' => esc_html__( 'Arrows', 'wcf-addons-pro' ),
					'dots'   => esc_html__( 'Pagination', 'wcf-addons-pro' ),
					'none'   => esc_html__( 'None', 'wcf-addons-pro' ),
				],
			]
		);

		$this->add_control(
			'navigation_previous_icon',
			[
				'label'            => esc_html__( 'Previous Arrow Icon', 'wcf-addons-pro' ),
				'type'             => Controls_Manager::ICONS,
				'fa4compatibility' => 'icon',
				'skin'             => 'inline',
				'label_block'      => false,
				'skin_settings'    => [
					'inline' => [
						'none' => [
							'label' => 'Default',
							'icon'  => 'eicon-chevron-left',
						],
						'icon' => [
							'icon' => 'eicon-star',
						],
					],
				],
				'recommended'      => [
					'fa-regular' => [
						'arrow-alt-circle-left',
						'caret-square-left',
					],
					'fa-solid'   => [
						'angle-double-left',
						'angle-left',
						'arrow-alt-circle-left',
						'arrow-circle-left',
						'arrow-left',
						'caret-left',
						'caret-square-left',
						'chevron-circle-left',
						'chevron-left',
						'long-arrow-alt-left',
					],
				],
				'conditions'       => [
					'relation' => 'or',
					'terms'    => [
						[
							'name'     => 'navigation',
							'operator' => '=',
							'value'    => 'both',
						],
						[
							'name'     => 'navigation',
							'operator' => '=',
							'value'    => 'arrows',
						],
					],
				],
			]
		);

		$this->add_control(
			'navigation_next_icon',
			[
				'label'            => esc_html__( 'Next Arrow Icon', 'wcf-addons-pro' ),
				'type'             => Controls_Manager::ICONS,
				'fa4compatibility' => 'icon',
				'skin'             => 'inline',
				'label_block'      => false,
				'skin_settings'    => [
					'inline' => [
						'none' => [
							'label' => 'Default',
							'icon'  => 'eicon-chevron-right',
						],
						'icon' => [
							'icon' => 'eicon-star',
						],
					],
				],
				'recommended'      => [
					'fa-regular' => [
						'arrow-alt-circle-right',
						'caret-square-right',
					],
					'fa-solid'   => [
						'angle-double-right',
						'angle-right',
						'arrow-alt-circle-right',
						'arrow-circle-right',
						'arrow-right',
						'caret-right',
						'caret-square-right',
						'chevron-circle-right',
						'chevron-right',
						'long-arrow-alt-right',
					],
				],
				'conditions'       => [
					'relation' => 'or',
					'terms'    => [
						[
							'name'     => 'navigation',
							'operator' => '=',
							'value'    => 'both',
						],
						[
							'name'     => 'navigation',
							'operator' => '=',
							'value'    => 'arrows',
						],
					],
				],
			]
		);

		$this->add_responsive_control(
			'slider_height',
			[
				'label'      => esc_html__( 'Height', 'wcf-addons-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'separator'  => 'before',
				'size_units' => [ 'px', '%', 'em', 'vh', 'custom' ],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 1000,
						'step' => 5,
					],
					'%'  => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .advance_slider ' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'slider_max_width',
			[
				'label'      => esc_html__( 'Max Width', 'wcf-addons-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 1000,
						'step' => 5,
					],
					'%'  => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .advance_slider' => 'max-width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'slide_gap',
			[
				'label'      => esc_html__( 'Slide Gap', 'wcf-addons-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 300,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .swiper-slide' => 'padding-left: {{SIZE}}{{UNIT}}; padding-right: {{SIZE}}{{UNIT}};',
				],
				'condition'  => [ 'element_list' => 'spring' ]
			]
		);

		$this->add_responsive_control(
			'slide_border_radius',
			[
				'label'      => esc_html__( 'Slide Border Radius', 'wcf-addons-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
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
					'{{WRAPPER}} .swiper-slide' => 'border-radius: {{SIZE}}{{UNIT}}; --swiper-material-slide-border-radius: {{SIZE}}{{UNIT}};',
				],
				'condition'  => [ 'element_list' => [ 'carousel', 'posters', 'material' ] ]
			]
		);

		$this->end_controls_section();
	}

	// Navigation
	private function slider_navigation_style_controls() {
		$this->start_controls_section(
			'style_navigation',
			[
				'label'     => esc_html__( 'Navigation', 'wcf-addons-pro' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'navigation' => [ 'arrows', 'both' ],
				],
			]
		);

		$this->add_responsive_control(
			'arrows_bottom_position',
			[
				'label'      => esc_html__( 'Arrow Position', 'wcf-addons-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range'      => [
					'px' => [
						'min'  => -500,
						'max'  => 1500,
						'step' => 5,
					],
					'%'  => [
						'min' => -100,
						'max' => 100,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .ts-navigation' => 'bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'arrows_max_width',
			[
				'label'      => esc_html__( 'Arrow Max Width', 'wcf-addons-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'separator'  => 'after',
				'size_units' => [ 'px', '%' ],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 1000,
						'step' => 5,
					],
					'%'  => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .ts-navigation' => 'max-width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'arrows_size',
			[
				'label'     => esc_html__( 'Size', 'wcf-addons-pro' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min' => 20,
						'max' => 60,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .wcf-arrow.wcf-arrow-prev, {{WRAPPER}} .wcf-arrow.wcf-arrow-next' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'arrows_border',
				'selector' => '{{WRAPPER}} .wcf-arrow.wcf-arrow-prev, {{WRAPPER}} .wcf-arrow.wcf-arrow-next',
			]
		);

		$this->add_control(
			'arrows_b_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'wcf-addons-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .wcf-arrow.wcf-arrow-prev, {{WRAPPER}} .wcf-arrow.wcf-arrow-next' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->start_controls_tabs(
			'arrows_style_tabs'
		);

		$this->start_controls_tab(
			'arrows_style_normal_tab',
			[
				'label' => esc_html__( 'Normal', 'wcf-addons-pro' ),
			]
		);

		$this->add_control(
			'arrows_color',
			[
				'label'     => esc_html__( 'Color', 'wcf-addons-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wcf-arrow.wcf-arrow-prev, {{WRAPPER}} .wcf-arrow.wcf-arrow-next'         => 'color: {{VALUE}};',
					'{{WRAPPER}} .wcf-arrow.wcf-arrow-prev svg, {{WRAPPER}} .wcf-arrow.wcf-arrow-next svg' => 'fill: {{VALUE}};',
					'{{WRAPPER}} .swiper-button-disabled::after'                                           => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'arrows_bg_color',
				'types'    => [ 'classic', 'gradient' ],
				'exclude'  => [ 'image' ],
				'selector' => '{{WRAPPER}} .wcf-arrow.wcf-arrow-prev, {{WRAPPER}} .wcf-arrow.wcf-arrow-next',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'arrows_style_hover_tab',
			[
				'label' => esc_html__( 'Hover', 'wcf-addons-pro' ),
			]
		);

		$this->add_control(
			'arrows_h_color',
			[
				'label'     => esc_html__( 'Color', 'wcf-addons-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wcf-arrow.wcf-arrow-prev:hover, {{WRAPPER}} .wcf-arrow.wcf-arrow-next:hover'         => 'color: {{VALUE}};',
					'{{WRAPPER}} .wcf-arrow.wcf-arrow-prev:hover svg, {{WRAPPER}} .wcf-arrow.wcf-arrow-next:hover svg' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'arrows_h_bg_color',
				'types'    => [ 'classic', 'gradient' ],
				'exclude'  => [ 'image' ],
				'selector' => '{{WRAPPER}} .wcf-arrow.wcf-arrow-prev:hover, {{WRAPPER}} .wcf-arrow.wcf-arrow-next:hover',
			]
		);

		$this->add_control(
			'arrows_hb_color',
			[
				'label'     => esc_html__( 'Border Color', 'wcf-addons-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wcf-arrow.wcf-arrow-prev:hover, {{WRAPPER}} .wcf-arrow.wcf-arrow-next:hover' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	// Pagination
	private function slider_pagination_style_controls() {
		$this->start_controls_section(
			'style_pagination',
			[
				'label'     => esc_html__( 'Pagination', 'wcf-addons-pro' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'navigation' => [ 'dots', 'both' ],
				],
			]
		);

		$this->add_responsive_control(
			'pagination_bottom_position',
			[
				'label'      => esc_html__( 'Pagination Position', 'wcf-addons-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range'      => [
					'px' => [
						'min'  => - 500,
						'max'  => 1500,
						'step' => 5,
					],
					'%'  => [
						'min' => - 100,
						'max' => 100,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .ts-pagination .swiper-pagination-horizontal' => 'bottom: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .ts-pagination .swiper-pagination-vertical'   => 'right: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'bullets_width',
			[
				'label'     => esc_html__( 'Width', 'wcf-addons-pro' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min' => 2,
						'max' => 200,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .ts-pagination .swiper-pagination-bullet' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'bullets_height',
			[
				'label'     => esc_html__( 'Height', 'wcf-addons-pro' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min' => 2,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .ts-pagination .swiper-pagination-bullet' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'bullets_inactive_color',
			[
				'label'     => esc_html__( 'Color', 'wcf-addons-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ts-pagination .swiper-pagination-bullet' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'bullets_color',
			[
				'label'     => esc_html__( 'Active Color', 'wcf-addons-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ts-pagination .swiper-pagination-bullet.swiper-pagination-bullet-active' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function register_content_style_controls() {
		$this->start_controls_section(
			'section_content_style',
			[
				'label' => __( 'Content', 'wcf-addons-pro' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'content_padding',
			[
				'label'      => esc_html__( 'Padding', 'wcf-addons-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', 'rem' ],
				'selectors'  => [
					'{{WRAPPER}} .swiper-slide-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'content_background',
				'types'    => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .swiper-slide-content',
			]
		);

		// Title
		$this->add_control(
			'title_heading',
			[
				'label'     => esc_html__( 'Title', 'wcf-addons-pro' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'title_color',
			[
				'label'     => esc_html__( 'Title Color', 'wcf-addons-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .swiper-slide-title' => 'color: {{VALUE}}; --color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'title_typography',
				'selector' => '{{WRAPPER}} .swiper-slide-title',
			]
		);

		$this->add_control(
			'title_margin',
			[
				'label'      => esc_html__( 'Margin', 'wcf-addons-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', ],
				'selectors'  => [
					'{{WRAPPER}} .swiper-slide-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// description
		$this->add_control(
			'description_heading',
			[
				'label'     => esc_html__(  'Description', 'wcf-addons-pro' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'description_color',
			[
				'label'     => esc_html__( 'Color', 'wcf-addons-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .swiper-slide-desc' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'description_typography',
				'selector' => '{{WRAPPER}} .swiper-slide-desc',
			]
		);

		$this->add_control(
			'description_margin',
			[
				'label'      => esc_html__( 'Margin', 'wcf-addons-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', ],
				'selectors'  => [
					'{{WRAPPER}} .swiper-slide-desc' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

	}

	protected function render() {

		$settings = $this->get_settings_for_display();

		if ( empty( $settings['slider'] ) ) {
			return;
		}

		//slider settings
		$show_dots   = ( in_array( $settings['navigation'], [ 'dots', 'both' ] ) );
		$show_arrows = ( in_array( $settings['navigation'], [ 'arrows', 'both' ] ) );

		$slider_settings = $this->get_slider_settings( $settings );

		if ( $show_arrows ) {
			$slider_settings['navigation'] = [
				'nextEl' => '.elementor-element-' . $this->get_id() . ' .wcf-arrow-next',
				'prevEl' => '.elementor-element-' . $this->get_id() . ' .wcf-arrow-prev',
			];
		}

		if ( $show_dots ) {
			$slider_settings['pagination'] = [
				'el'        => '.elementor-element-' . $this->get_id() . ' .swiper-pagination',
				'type'      => 'bullets',
				'clickable' => true,
			];
		}

		$this->add_render_attribute(
			'wrapper',
			[
				'class'         => [ 'advance_slider_wrapper' ],
				'slider-type'   => $settings['element_list'],
				'data-settings' => json_encode( $slider_settings ), //phpcs:ignore
			]
		);

		$swiper_class = Plugin::$instance->experiments->is_feature_active( 'e_swiper_latest' ) ? 'swiper' : 'swiper-container';

		$this->add_render_attribute(
			'carousel-wrapper',
			[
				'class' => 'advance_slider ' . $swiper_class,
			]
		);
		?>
        <div <?php $this->print_render_attribute_string( 'wrapper' ); ?>>
            <div <?php $this->print_render_attribute_string( 'carousel-wrapper' ); ?>>
                <div class="swiper-wrapper">
					<?php
					foreach ( $settings['slider'] as $index => $item ) {
						if ( 'shaders' === $settings['element_list'] ) {
							$this->render_single_shader_slide( $settings, $item, $index );
						}

						if ( 'slicer' === $settings['element_list'] ) {
							$this->render_single_slicer_slide( $settings, $item, $index );
						}

						if ( 'shutters' === $settings['element_list'] ) {
							$this->render_single_shutter_slide( $settings, $item, $index );
						}

						if ( 'fashion' === $settings['element_list'] ) {
							$this->render_single_fashion_slide( $settings, $item, $index );
						}

						if ( 'spring' === $settings['element_list'] ) {
							$this->render_single_spring_slide( $settings, $item, $index );
						}

						if ( 'carousel' === $settings['element_list'] ) {
							$this->render_single_carousel_slide( $settings, $item, $index );
						}

						if ( 'posters' === $settings['element_list'] ) {
							$this->render_single_poster_slide( $settings, $item, $index );
						}

						if ( 'material' === $settings['element_list'] ) {
							$this->render_single_material_slide( $settings, $item, $index );
						}
					}
					?>
                </div>
            </div>

			<?php if ( 1 < count( $settings['slider'] ) ) : ?>
				<?php if ( $show_arrows ) : ?>
                    <div class="ts-navigation">
                        <div class="wcf-arrow wcf-arrow-prev" role="button" tabindex="0">
							<?php $this->render_swiper_button( 'previous' ); ?>
                        </div>
                        <div class="wcf-arrow wcf-arrow-next" role="button" tabindex="0">
							<?php $this->render_swiper_button( 'next' ); ?>
                        </div>
                    </div>
				<?php endif; ?>
				<?php if ( $show_dots ) : ?>
                    <div class="ts-pagination">
                        <div class="swiper-pagination"></div>
                    </div>
				<?php endif; ?>
			<?php endif; ?>
        </div>
		<?php
	}

	protected function get_slider_settings( $settings ) {
		$slider_settings = [];

		//slider breakpoints
		$active_breakpoints = Plugin::$instance->breakpoints->get_active_breakpoints();

		//shader slider
		if ( 'shaders' === $settings['element_list'] ) {
			$this->add_render_attribute( 'carousel-wrapper', [ 'class' => 'swiper-gl'] );
			$slider_settings = [
				'speed'  => 1000,
				'effect' => 'gl',
				'gl'     => [
					'shader' => $settings['shader_style'],
				]
			];
		}

		//slicer slider
		if ( 'slicer' === $settings['element_list'] ) {
			$this->add_render_attribute( 'carousel-wrapper', [ 'class' => 'swiper-slicer'] );
			$slider_settings = [
				'effect'       => 'slicer',
				'slicerEffect' => [
					'split' => $settings['slide_split'],
				],
				'direction'    => $settings['slide_direction'],
				'speed'        => 600,
				'grabCursor'   => true,
			];
		}

		//slicer slider
		if ( 'shutters' === $settings['element_list'] ) {
			$this->add_render_attribute( 'carousel-wrapper', [ 'class' => 'swiper-shutters'] );
			$slider_settings = [
				'effect'       => 'shutters',
				'slicerEffect' => [
					'split' => $settings['slide_split'],
				],
				'direction'    => $settings['slide_direction'],
				'speed'        => 900,
			];
		}

		//fashion slider
		if ( 'fashion' === $settings['element_list'] ) {
			$this->add_render_attribute( 'carousel-wrapper', [ 'class' => 'swiper-fashion'] );
			$slider_settings = [
				'effect'         => 'fashion',
				'slicerEffect'   => [
					'split' => $settings['slide_split'],
				],
				'speed'          => 1300,
				'allowTouchMove' => false,
				'parallax'       => true,
			];
		}

		//spring slider
		if ( 'spring' === $settings['element_list'] ) {
			$this->add_render_attribute( 'carousel-wrapper', [ 'class' => 'swiper-spring'] );
			$slider_settings = [
				"effect"         => 'creative',
				'speed'          => 720,
				'followFinger'   => false,
				'creativeEffect' => [
					'limitProgress' => 100,
					'prev'          => [
						'translate' => [ '-100%', 0, 0 ],
					],
					'next'          => [
						'translate' => [ '100%', 0, 0 ],
					],
				],
				'slidesPerView'  => 4,
			];
		}

		//carousel slider
		if ( 'carousel' === $settings['element_list'] ) {
			$this->add_render_attribute( 'carousel-wrapper', [ 'class' => 'swiper-carousel'] );
			$slider_settings = [
				'autoplay'              => [
					'delay' => 3000,
				],
				'loop'                  => true,
				'slidesPerView'         => 5,
				'initialSlide'          => 5,
				'centeredSlides'        => true,
				'lazyLoadingInPrevNext' => true,
				'effect'                => 'coverflow',
				'coverflowEffect'       => [
					'rotate'       => 0,
					'stretch'      => 50,
					'depth'        => 250,
					'modifier'     => .4,
					'slideShadows' => true,
				],
			];
		}

		//posters slider
		if ( 'posters' === $settings['element_list'] ) {
			$this->add_render_attribute( 'carousel-wrapper', [ 'class' => 'swiper-poster'] );
			$slider_settings = [
				'effect'          => 'creative',
				'speed'           => 600,
				'resistanceRatio' => 0,
				'grabCursor'      => true,
				'parallax'        => true,
				'creativeEffect' => [
					'limitProgress'     => 3,
					'perspective'       => true,
					'shadowPerProgress' => true,
					'prev'              => [
						'shadow'    => true,
						'translate' => [ '-15%', 0, - 200 ],
					],
					'next' => [
						'translate' => [ 1500, 0, 0 ],
					],
				],
			];
		}

		//posters slider
		if ( 'material' === $settings['element_list'] ) {
			$this->add_render_attribute( 'carousel-wrapper', [ 'class' => 'swiper-material' ] );
			$slider_settings = [
				'effect'         => 'material',
				'materialEffect' => [
					'slideSplitRatio' => 0.65,
				],
				'grabCursor'     => true,
				'slidesPerView'  => 2,
				'spaceBetween'   => 16,
				'speed'          => 600,
			];
		}

		if ( isset( $settings['slides_to_show'] ) && ! empty( $settings['slides_to_show'] ) ) {
			$slider_settings['slidesPerView'] = $settings['slides_to_show'];

			foreach ( $active_breakpoints as $breakpoint_name => $breakpoint ) {
				$slides_to_show = ! empty( $settings[ 'slides_to_show_' . $breakpoint_name ] ) ? $settings[ 'slides_to_show_' . $breakpoint_name ] : $settings['slides_to_show'];

				$slider_settings['breakpoints'][ $breakpoint->get_value() ]['slidesPerView'] = $slides_to_show;
			}
		}

		if ( isset( $settings['center_slide'] ) && ! empty( $settings['center_slide'] ) ) {
			$slider_settings['centeredSlides'] = $settings['center_slide'];
		}

		if ( ! empty( $settings['mousewheel'] ) ) {
			$slider_settings['mousewheel'] = [
				'releaseOnEdges' => true,
			];
		}

		return $slider_settings;
	}

	/**
	 * Render swiper button.
	 *
	 * @since 1.0.0
	 *
	 * @access private
	 */
	private function render_swiper_button( $type ) {
		$direction     = 'next' === $type ? 'right' : 'left';
		$icon_settings = $this->get_settings_for_display( 'navigation_' . $type . '_icon' );

		if ( empty( $icon_settings['value'] ) ) {
			$icon_settings = [
				'library' => 'eicons',
				'value'   => 'eicon-chevron-' . $direction,
			];
		}

		Icons_Manager::render_icon( $icon_settings, [ 'aria-hidden' => 'true' ] );
	}

	//shader
	protected function render_single_shader_slide( $settings, $item, $index ) {
		?>
        <div class="swiper-slide">
            <img class="swiper-gl-image" src="<?php echo esc_url( $item['slide_image']['url'] ); ?>" alt="<?php echo esc_html( $item['slide_title'] ); ?>">

            <div class="swiper-slide-content">
				<?php $this->render_slide_title( $settings, $item, $index ); ?>
				<?php $this->render_slide_description( $settings, $item, $index ); ?>
				<?php
				if ( 'button' === $settings['link_type'] ) {
					$this->render_button( $settings, 'slide_link', 'slider', $index );
				}
				?>
            </div>
        </div>
		<?php
	}

	//slicer
	protected function render_single_slicer_slide( $settings, $item, $index ) {
		?>
        <div class="swiper-slide">
            <img class="swiper-slicer-image" src="<?php echo esc_url( $item['slide_image']['url'] ); ?>" alt="<?php echo esc_html( $item['slide_title'] ); ?>">

            <div class="swiper-slide-content">
	            <?php $this->render_slide_title( $settings, $item, $index ); ?>
	            <?php $this->render_slide_description( $settings, $item, $index ); ?>
	            <?php
	            if ( 'button' === $settings['link_type'] ) {
		            $this->render_button( $settings, 'slide_link', 'slider', $index );
	            }
	            ?>
            </div>
        </div>
		<?php
	}

	//shutters
	protected function render_single_shutter_slide( $settings, $item, $index ) {
		?>
        <div class="swiper-slide">

            <img class="swiper-shutters-image" src="<?php echo esc_url( $item['slide_image']['url'] ); ?>" alt="<?php echo esc_html( $item['slide_title'] ); ?>">

            <div class="swiper-slide-content">
		        <?php $this->render_slide_title( $settings, $item, $index ); ?>
		        <?php $this->render_slide_description( $settings, $item, $index ); ?>
		        <?php
		        if ( 'button' === $settings['link_type'] ) {
			        $this->render_button( $settings, 'slide_link', 'slider', $index );
		        }
		        ?>
            </div>
        </div>
		<?php
	}

	//fashion
	protected function render_single_fashion_slide( $settings, $item, $index ) {
		?>
        <div class="swiper-slide" data-slide-bg-color="<?php echo esc_attr( $item['slide_color'] ); ?>">

            <div class="swiper-slide-content" data-swiper-parallax="-130%" data-title-color="<?php echo esc_attr( $settings['title_color'] ); ?>">
		        <?php $this->render_slide_title( $settings, $item, $index ); ?>
		        <?php $this->render_slide_description( $settings, $item, $index ); ?>
		        <?php
		        if ( 'button' === $settings['link_type'] ) {
			        $this->render_button( $settings, 'slide_link', 'slider', $index );
		        }
		        ?>
            </div>

            <!-- slide image wrap -->
            <div class="fashion-slider-scale">
                <!-- slide image -->
                <img src="<?php echo esc_url( $item['slide_image']['url'] ); ?>" alt="<?php echo esc_html( $item['slide_title'] ); ?>">
            </div>
        </div>
		<?php
	}

	//spring
	protected function render_single_spring_slide( $settings, $item, $index ) {
		?>
        <div class="swiper-slide">
            <img src="<?php echo esc_url( $item['slide_image']['url'] ); ?>" alt="<?php echo esc_html( $item['slide_title'] ); ?>">

            <div class="swiper-slide-content">
	            <?php $this->render_slide_title( $settings, $item, $index ); ?>
	            <?php $this->render_slide_description( $settings, $item, $index ); ?>
	            <?php
	            if ( 'button' === $settings['link_type'] ) {
		            $this->render_button( $settings, 'slide_link', 'slider', $index );
	            }
	            ?>
            </div>
        </div>
		<?php
	}

	//carousel
	protected function render_single_carousel_slide( $settings, $item, $index ) {
		?>
        <div class="swiper-slide">
            <img src="<?php echo esc_url( $item['slide_image']['url'] ); ?>" alt="<?php echo esc_html( $item['slide_title'] ); ?>">

            <div class="swiper-slide-content">
	            <?php $this->render_slide_title( $settings, $item, $index ); ?>
	            <?php $this->render_slide_description( $settings, $item, $index ); ?>
	            <?php
	            if ( 'button' === $settings['link_type'] ) {
		            $this->render_button( $settings, 'slide_link', 'slider', $index );
	            }
	            ?>
            </div>
        </div>
		<?php
	}

	//poster
	protected function render_single_poster_slide( $settings, $item, $index ) {
		?>
        <div class="swiper-slide">
            <img src="<?php echo esc_url( $item['slide_image']['url'] ); ?>" data-swiper-parallax-scale="1.1"  alt="<?php echo esc_html( $item['slide_title'] ); ?>">

            <div class="swiper-slide-content">
				<?php $this->render_slide_title( $settings, $item, $index ); ?>
				<?php $this->render_slide_description( $settings, $item, $index ); ?>
				<?php
				if ( 'button' === $settings['link_type'] ) {
					$this->render_button( $settings, 'slide_link', 'slider', $index );
				}
				?>
            </div>
        </div>
		<?php
	}

	//material
	protected function render_single_material_slide( $settings, $item, $index ) {
		?>
        <div class="swiper-slide">
            <div class="swiper-material-wrapper">
                <div class="swiper-material-content">
                    <img src="<?php echo esc_url( $item['slide_image']['url'] ); ?>" data-swiper-material-scale="1.25" alt="<?php echo esc_html( $item['slide_title'] ); ?>">
                    <div class="swiper-slide-content swiper-material-animate-opacity">
		                <?php $this->render_slide_title( $settings, $item, $index ); ?>
		                <?php $this->render_slide_description( $settings, $item, $index ); ?>
		                <?php
		                if ( 'button' === $settings['link_type'] ) {
			                $this->render_button( $settings, 'slide_link', 'slider', $index );
		                }
		                ?>
                    </div>
                </div>
            </div>
        </div>
		<?php
	}

	protected function render_slide_title( $settings, $item, $index ) {
		if ( empty( $item['slide_title'] ) ) {
		    return;
		}

		$link_key = 'link_' . $index;
		if ( ! empty( $item['slide_link']['url'] ) && 'title' === $settings['link_type'] ) {
			$this->add_link_attributes( $link_key, $item['slide_link'] );
			?>
            <<?php Utils::print_validated_html_tag( $settings['title_tag'] ); ?> class="swiper-slide-title">
                <a <?php $this->print_render_attribute_string( $link_key ); ?>>
                    <?php $this->print_unescaped_setting( 'slide_title', 'slider', $index ); ?>
                </a>
            </<?php Utils::print_validated_html_tag( $settings['title_tag'] ); ?>>
			<?php
		} else {
			?>
            <<?php Utils::print_validated_html_tag( $settings['title_tag'] ); ?> class="swiper-slide-title">
			<?php $this->print_unescaped_setting( 'slide_title', 'slider', $index ); ?>
            </<?php Utils::print_validated_html_tag( $settings['title_tag'] ); ?>>
			<?php
		}
	}

	protected function render_slide_description( $settings, $item, $index ) {
		if ( empty( $item['slide_content'] ) ) {
			return;
		}

		?>
        <div class="swiper-slide-desc">
			<?php $this->print_unescaped_setting( 'slide_content', 'slider', $index ); ?>
        </div>
		<?php
	}
}
