<?php

namespace SasslyEssentialApp\Widgets;

use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Repeater;
use Elementor\Utils;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

/**
 * Image Generator
 *
 * Elementor widget for image generator
 *
 * @since 1.0.0
 */
class Image_Generator extends Widget_Base {

	/**
	 * Retrieve the widget name.
	 *
	 * @return string Widget name.
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function get_name() {
		return 'wcf--image-generator';
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
		return esc_html__( 'WCF Image Generator', 'sassly-essential' );
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
		return 'eicon-t-letter';
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
	 * Requires css files.
	 *
	 * @return array
	 */
	public function get_style_depends() {
		wp_register_style( 'sassly-image-generator', SASSLY_ESSENTIAL_ASSETS_URL . 'css/image-generator.css' );
		return [ 'sassly-image-generator' ];
	}

	public function get_script_depends() {

		wp_register_script( 'sassly-image-generator', SASSLY_ESSENTIAL_ASSETS_URL . '/js/widgets/image-generator.js', [ 'jquery' ], false, true );

		return [ 'sassly-image-generator' ];
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
		$this->register_search_controls();

		$this->register_search_tag_list();
	}

	public static function get_public_post_types( $args = [] ) {
		$post_type_args = [
			// Default is the value $public.
			'show_in_nav_menus' => true,
		];

		// Keep for backwards compatibility
		if ( ! empty( $args['post_type'] ) ) {
			$post_type_args['name'] = $args['post_type'];
			unset( $args['post_type'] );
		}

		$post_type_args = wp_parse_args( $post_type_args, $args );

		$_post_types = get_post_types( $post_type_args, 'objects' );

		$post_types = [];

		foreach ( $_post_types as $post_type => $object ) {
			$post_types[ $post_type ] = $object->label;
		}

		return $post_types;
	}

	protected function register_search_controls() {
		$this->start_controls_section(
			'section_image_generator',
			[
				'label' => __( 'Image Generator', 'sassly-essential' ),
			]
		);

		$this->add_control(
			'source_type',
			[
				'label'   => esc_html__( 'Source', 'sassly-essential' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'post_type',
				'options' => [
					'post_type' => esc_html__( 'Post Type', 'sassly-essential' ),
					'external'  => esc_html__( 'External', 'sassly-essential' ),
				],
			]
		);

		$this->add_control(
			'post_type',
			[
				'label'     => esc_html__( 'Source', 'sassly-essential' ),
				'type'      => Controls_Manager::SELECT2,
				'multiple'  => true,
				'default'   => 'post',
				'options'   => $this->get_public_post_types(),
				'condition' => [ 'source_type' => 'post_type' ],
			]
		);

		$this->add_control(
			'website_link',
			[
				'label'       => esc_html__( 'Link', 'sassly-essential' ),
				'type'        => Controls_Manager::URL,
				'options'     => [ 'url', 'is_external', 'nofollow' ],
				'default'     => [
					'url'         => '#',
					'is_external' => true,
					'nofollow'    => true,
				],
				'label_block' => false,
				'condition'   => [ 'source_type' => 'external' ],
			]
		);

		$this->add_responsive_control(
			'search_height',
			[
				'label'      => esc_html__( 'Height', 'sassly-essential' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', 'rem', 'custom' ],
				'range'      => [
					'px' => [
						'min' => 1,
						'max' => 500,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .search-form' => 'min-height: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'placeholder',
			[
				'label'   => esc_html__( 'Placeholder', 'sassly-essential' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'Search...', 'sassly-essential' ),
			]
		);

		//button
		$this->add_control(
			'heading_button_content',
			[
				'label'     => esc_html__( 'Button', 'sassly-essential' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'button_text',
			[
				'label'   => esc_html__( 'Text', 'sassly-essential' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'Search', 'sassly-essential' ),
			]
		);

		$this->end_controls_section();

		//search style
		$this->register_search_style_controls();

	}

	protected function register_search_style_controls() {

		//input style
		$this->start_controls_section(
			'section_input_style',
			[
				'label' => esc_html__( 'Input', 'sassly-essential' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'input_icon_color',
			[
				'label'     => esc_html__( 'Icon Color', 'sassly-essential' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .filter' => 'color: {{VALUE}}; fill: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'input_typography',
				'selector' => '{{WRAPPER}} .wcf-search-form__input',
			]
		);

		$this->start_controls_tabs( 'tabs_input_colors' );

		$this->start_controls_tab(
			'tab_input_normal',
			[
				'label' => esc_html__( 'Normal', 'sassly-essential' ),
			]
		);

		$this->add_control(
			'input_text_color',
			[
				'label'     => esc_html__( 'Text Color', 'sassly-essential' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wcf-search-form__input' => 'color: {{VALUE}}; fill: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'input_background_color',
			[
				'label'     => esc_html__( 'Background Color', 'sassly-essential' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .search-form' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'input_border_color',
			[
				'label'     => esc_html__( 'Border Color', 'sassly-essential' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .search-form' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'           => 'input_box_shadow',
				'selector'       => '{{WRAPPER}} .search-form',
				'fields_options' => [
					'box_shadow_type' => [
						'separator' => 'default',
					],
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_input_focus',
			[
				'label' => esc_html__( 'Focus', 'sassly-essential' ),
			]
		);

		$this->add_control(
			'input_text_color_focus',
			[
				'label'     => esc_html__( 'Text Color', 'sassly-essential' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wcf-search-form--focus .wcf-search-form__input' => 'color: {{VALUE}}; fill: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'input_background_color_focus',
			[
				'label'     => esc_html__( 'Background Color', 'sassly-essential' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .search-form.wcf-search-form--focus' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'input_border_color_focus',
			[
				'label'     => esc_html__( 'Border Color', 'sassly-essential' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .search-form.wcf-search-form--focus' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'           => 'input_box_shadow_focus',
				'selector'       => '{{WRAPPER}} .search-form.wcf-search-form--focus',
				'fields_options' => [
					'box_shadow_type' => [
						'separator' => 'default',
					],
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'button_border_width',
			[
				'label'      => esc_html__( 'Border Width', 'sassly-essential' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .search-form' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator'  => 'before',
			]
		);

		$this->add_responsive_control(
			'border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'sassly-essential' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 200,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .search-form' => 'border-radius: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->end_controls_section();

		//button style
		$this->start_controls_section(
			'section_button_style',
			[
				'label' => esc_html__( 'Button', 'sassly-essential' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'button_typography',
				'selector' => '{{WRAPPER}} .wcf-search-form__submit',
			]
		);

		$this->start_controls_tabs( 'tabs_button_colors' );

		$this->start_controls_tab(
			'tab_button_normal',
			[
				'label' => esc_html__( 'Normal', 'sassly-essential' ),
			]
		);

		$this->add_control(
			'button_text_color',
			[
				'label'     => esc_html__( 'Text Color', 'sassly-essential' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wcf-search-form__submit' => 'color: {{VALUE}}; fill: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'button_background_color',
			[
				'label'     => esc_html__( 'Background Color', 'sassly-essential' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wcf-search-form__submit' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_button_hover',
			[
				'label' => esc_html__( 'Hover', 'sassly-essential' ),
			]
		);

		$this->add_control(
			'button_text_color_hover',
			[
				'label'     => esc_html__( 'Text Color', 'sassly-essential' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wcf-search-form__submit:hover' => 'color: {{VALUE}}; fill: {{VALUE}}',
					'{{WRAPPER}} .wcf-search-form__submit:focus' => 'color: {{VALUE}}; fill: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'button_background_color_hover',
			[
				'label'     => esc_html__( 'Background Color', 'sassly-essential' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wcf-search-form__submit:hover' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .wcf-search-form__submit:focus' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'search_button_width',
			[
				'label'      => esc_html__( 'Width', 'sassly-essential' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', 'rem', 'custom' ],
				'range'      => [
					'px' => [
						'min' => 1,
						'max' => 200,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .search-form .wcf-search-form__submit' => 'min-width: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_responsive_control(
			'search_button_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'sassly-essential' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 200,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .search-form .wcf-search-form__submit' => 'border-radius: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function register_search_tag_list() {
		$this->start_controls_section(
			'section_search_tags',
			[
				'label' => esc_html__( 'Tag List', 'sassly-essential' ),
			]
		);

		$this->add_control(
			'enable_tag',
			[
				'label'        => esc_html__( 'Enable', 'sassly-essential' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'label_on'     => esc_html__( 'Yes', 'sassly-essential' ),
				'label_off'    => esc_html__( 'No', 'sassly-essential' ),
				'return_value' => 'yes',
			]
		);

		$this->add_control(
			'tag_title',
			[
				'label'     => esc_html__( 'Tag Title', 'sassly-essential' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => esc_html__( 'Tag:', 'sassly-essential' ),
				'condition' => [
					'enable_tag' => 'yes'
				]
			]
		);

		$repeater = new Repeater();

		// Title
		$repeater->add_control(
			'tag',
			[
				'label'   => esc_html__( 'Tag', 'sassly-essential' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'Creative', 'sassly-essential' ),
			]
		);

		$this->add_control(
			'tag_list',
			[
				'label'     => esc_html__( 'Tag List', 'sassly-essential' ),
				'type'      => Controls_Manager::REPEATER,
				'fields'    => $repeater->get_controls(),
				'default'   => [ [], [], [], [] ],
				'condition' => [
					'enable_tag' => 'yes'
				]
			]
		);

		$this->end_controls_section();

		//style
		$this->start_controls_section(
			'section_search_tags_style',
			[
				'label'     => esc_html__( 'Tag List', 'sassly-essential' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'enable_tag' => 'yes'
				]
			]
		);

		$this->add_responsive_control(
			'tag_spacing',
			[
				'label'      => esc_html__( 'Top Spacing', 'sassly-essential' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 200,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .wcf-image-generator-tags' => 'margin-top: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'tag_typography',
				'selector' => '{{WRAPPER}} .wcf-image-generator-tags li',
			]
		);

		$this->add_control(
			'tag_title_color',
			[
				'label'     => esc_html__( 'Tag Title Color', 'sassly-essential' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .tag-title' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'tag_text_color',
			[
				'label'     => esc_html__( 'Tag Color', 'sassly-essential' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .tag-item' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'tag_background_color',
			[
				'label'     => esc_html__( 'Tag Background Color', 'sassly-essential' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .tag-item' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'tag_padding',
			[
				'label'      => esc_html__( 'Padding', 'textdomain' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .tag-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'tag_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'sassly-essential' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 200,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .tag-item' => 'border-radius: {{SIZE}}{{UNIT}}',
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

		$this->add_render_attribute( 'wrapper', [ 'class' => 'wcf__image_generator', ] );

		$this->add_render_attribute(
			'form',
			[
				'class'      => 'search-form',
				'action'     => home_url(),
				'method'     => 'get',
				'role'       => 'search',
				'post_types' => $settings['post_type'] ?? 'post',
			]
		);

		$this->add_render_attribute(
			'label',
			[
				'class' => 'elementor-screen-only',
				'for'   => 'wcf-search-form-' . $this->get_id(),
			]
		);

		$this->add_render_attribute(
			'input',
			[
				'id'          => 'wcf-search-form-' . $this->get_id(),
				'placeholder' => $settings['placeholder'],
				'class'       => 'wcf-search-form__input',
				'type'        => 'text',
				'name'        => 's',
				'required'   => 'required',
			]
		);

		$this->add_render_attribute(
			'submit',
			[
				'id'         => 'wcf-search-form-' . $this->get_id(),
				'class'      => 'wcf-search-form__submit',
				'type'       => 'submit',
				'aria-label' => esc_html__( 'Search', 'sassly-essential' ),
			]
		);

		?>
        <div <?php $this->print_render_attribute_string( 'wrapper' ); ?>>

			<?php $this->render_search_form( $settings ); ?>

			<?php $this->render_search_tag_list( $settings ); ?>
        </div>
		<?php
	}

	protected function render_search_form( $settings ) {
		$link_tag = 'button';
		if ( ! empty( $settings['website_link']['url'] ) && 'external' === $settings['source_type'] ) {
			$link_tag = 'a';
			$this->add_link_attributes( 'submit', $settings['website_link'] );
		}
		?>
    <form <?php $this->print_render_attribute_string( 'form' ); ?>>
        <span class="filter">
                <svg width="23" height="18" viewBox="0 0 23 18" xmlns="http://www.w3.org/2000/svg">
                    <path d="M0.71875 3.62729H12.4215C12.7443 4.90185 13.9241 5.84976 15.3267 5.84976C16.7293 5.84976 17.9091 4.90185 18.2319 3.62729H22.2812C22.6782 3.62729 23 3.3128 23 2.9249C23 2.53701 22.6782 2.22252 22.2812 2.22252H18.2319C17.9091 0.947955 16.7293 0 15.3267 0C13.924 0 12.7442 0.947955 12.4214 2.22252H0.71875C0.32182 2.22252 0 2.53701 0 2.9249C0 3.3128 0.32182 3.62729 0.71875 3.62729ZM15.3267 1.40477C16.1845 1.40477 16.8823 2.0867 16.8823 2.92486C16.8823 3.76307 16.1845 4.44499 15.3267 4.44499C14.469 4.44499 13.7712 3.76307 13.7712 2.92486C13.7712 2.0867 14.469 1.40477 15.3267 1.40477ZM0.71875 9.70238H4.7681C5.09091 10.9769 6.27069 11.9249 7.67333 11.9249C9.07597 11.9249 10.2558 10.9769 10.5786 9.70238H22.2812C22.6782 9.70238 23 9.38789 23 9C23 8.61211 22.6782 8.29762 22.2812 8.29762H10.5785C10.2557 7.02305 9.07593 6.0751 7.67329 6.0751C6.27064 6.0751 5.09086 7.02305 4.76805 8.29762H0.71875C0.32182 8.29762 0 8.61211 0 9C0 9.38789 0.321775 9.70238 0.71875 9.70238ZM7.67329 7.47987C8.53102 7.47987 9.22884 8.16179 9.22884 9C9.22884 9.83816 8.53102 10.5201 7.67329 10.5201C6.81555 10.5201 6.11773 9.83816 6.11773 9C6.11773 8.16179 6.81555 7.47987 7.67329 7.47987ZM22.2812 14.3727H18.2319C17.9091 13.0981 16.7293 12.1502 15.3267 12.1502C13.924 12.1502 12.7442 13.0981 12.4214 14.3727H0.71875C0.32182 14.3727 0 14.6872 0 15.0751C0 15.463 0.32182 15.7775 0.71875 15.7775H12.4215C12.7443 17.052 13.9241 18 15.3267 18C16.7294 18 17.9091 17.052 18.2319 15.7775H22.2812C22.6782 15.7775 23 15.463 23 15.0751C23 14.6872 22.6782 14.3727 22.2812 14.3727ZM15.3267 16.5952C14.469 16.5952 13.7712 15.9133 13.7712 15.0751C13.7712 14.2369 14.469 13.555 15.3267 13.555C16.1845 13.555 16.8823 14.2369 16.8823 15.0751C16.8823 15.9133 16.1845 16.5952 15.3267 16.5952Z"/>
                </svg>
            </span>
        <label <?php $this->print_render_attribute_string( 'label' ); ?>>
			<?php esc_html_e( 'Search', 'sassly-essential' ); ?>
        </label>
        <input <?php $this->print_render_attribute_string( 'input' ); ?>>


        <<?php Utils::print_validated_html_tag($link_tag); ?> <?php $this->print_render_attribute_string( 'submit' ); ?>>
		<?php $this->print_unescaped_setting( 'button_text' ); ?>
        </<?php Utils::print_validated_html_tag($link_tag); ?>>

        </form>
		<?php
	}

	protected function render_search_tag_list( $settings ) {
		if ( empty( $settings['enable_tag'] ) ) {
			return;
		}
		?>
        <ul class="wcf-image-generator-tags">
            <li class="tag-title"><?php $this->print_unescaped_setting( 'tag_title' ); ?></li>
			<?php foreach ( $settings['tag_list'] as $index => $item ) { ?>
                <li class="tag-item"><?php $this->print_unescaped_setting( 'tag', 'tag_list', $index ); ?></li>
			<?php } ?>
        </ul>
		<?php
	}
}
