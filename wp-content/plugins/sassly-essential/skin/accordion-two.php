<?php

namespace SasslyEssentialApp\Skin\Accordion;

use Elementor\Widget_Base;
use Elementor\Skin_Base;
use Elementor\Controls_Manager;
use Elementor\Icons_Manager;
use Elementor\Utils;
use Elementor\Repeater;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

class Skin_Accordion_Two extends Skin_Base {

	/**
	 * Skin base constructor.
	 *
	 * Initializing the skin base class by setting parent widget and registering
	 * controls actions.
	 *
	 * @param Widget_Base $parent
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function __construct( Widget_Base $parent ) {
		$this->parent = $parent;
		add_filter( 'elementor/widget/print_template', array( $this, 'skin_print_template' ), 10, 2 );
		add_action( 'elementor/element/accordion/section_toggle_style_title/after_section_end', [
			$this,
			'highlight_style_control'
		] );
		add_action( 'elementor/element/accordion/section_toggle_style_title/after_section_end', [
			$this,
			'title_number_style'
		] );
		add_action( 'elementor/element/accordion/section_title/before_section_end', [ $this, 'update_controls' ] );
		add_action( 'elementor/element/accordion/section_title/after_section_end', [ $this, 'new_feature_controls' ] );

	}


	/**
	 * Get skin ID.
	 *
	 * Retrieve the skin ID.
	 *
	 * @since 1.0.0
	 * @access public
	 * @abstract
	 */
	public function get_id() {
		return 'skin-wcf-accordion-two';
	}

	/**
	 * Get skin title.
	 *
	 * Retrieve the skin title.
	 *
	 * @since 1.0.0
	 * @access public
	 * @abstract
	 */
	public function get_title() {
		return __( 'WCF Style Two', 'sassly-essential' );
	}

	/**
	 * Add skin controls
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function inner_title_style_control( $element ) {
		$element->start_controls_section(
			'wcf_stitleicon2_section',
			[
				'label'     => esc_html__( 'Title Icon', 'sassly-essential' ),
				'tab'       => \Elementor\Controls_Manager::TAB_STYLE,
				'condition' => [
					'_skin' => 'skin-wcf-accordion-two',
				],
			]
		);

		$element->add_responsive_control(
			'wcf_title2_icon_space',
			[
				'label'     => esc_html__( 'Spacing', 'sassly-essential' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 300,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-accordion-title' => 'gap: {{SIZE}}{{UNIT}};display:flex;',

				],
			]
		);

		$element->add_control(
			'wcf_title2_icon_color',
			[
				'label'     => esc_html__( 'Color', 'sassly-essential' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elementor-accordion-title-icon i'        => 'color: {{VALUE}}',
					'{{WRAPPER}} .elementor-accordion-title-icon svg'      => 'fill: {{VALUE}}',
					'{{WRAPPER}} .elementor-accordion-title-icon svg path' => 'fill: {{VALUE}}',
				],
			]
		);

		$element->add_control(
			'wcf_title2_icon_hover_color',
			[
				'label'     => esc_html__( 'Hover Color', 'sassly-essential' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elementor-accordion-title-icon:hover i'        => 'color: {{VALUE}}',
					'{{WRAPPER}} .elementor-accordion-title-icon:hover svg'      => 'fill: {{VALUE}}',
					'{{WRAPPER}} .elementor-accordion-title-icon:hover svg path' => 'fill: {{VALUE}}',
				],
			]
		);

		$element->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'     => 'wcf_title2_icon_typography',
				'selector' => '{{WRAPPER}} .elementor-accordion-title-icon i',
			]
		);

		$element->end_controls_section();

	}

	public function inner_content_style_control( $element ) {

		$element->start_controls_section(
			'wcf_styleicon2_section',
			[
				'label'     => esc_html__( 'Inner Content', 'sassly-essential' ),
				'tab'       => \Elementor\Controls_Manager::TAB_STYLE,
				'condition' => [
					'_skin' => 'skin-wcf-accordion-two',
				],
			]
		);


		$element->start_controls_tabs(
			'wcf_icont2_style_tabs'
		);

		$element->start_controls_tab(
			'wcf_icont2_style_icon_tab',
			[
				'label' => esc_html__( 'Icon', 'sassly-essential' ),
			]
		);

		$element->add_control(
			'wcf_cicon2_color',
			[
				'label'     => esc_html__( 'Icon Color', 'sassly-essential' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wcf--inner--accrodion--body i'        => 'color: {{VALUE}}',
					'{{WRAPPER}} .wcf--inner--accrodion--body svg'      => 'fill: {{VALUE}}',
					'{{WRAPPER}} .wcf--inner--accrodion--body svg path' => 'fill: {{VALUE}}',
				],
			]
		);

		$element->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'     => 'wcf_ci2_typography',
				'selector' => '{{WRAPPER}} .wcf--inner--accrodion--body i',
			]
		);

		$element->add_responsive_control(
			'wcf_inner2_space',
			[
				'label'     => esc_html__( 'Icon Spacing', 'sassly-essential' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 300,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .wcf--inner--accrodion--body' => 'gap: {{SIZE}}{{UNIT}};',

				],
			]
		);


		$element->end_controls_tab();

		$element->start_controls_tab(
			'wcf_icont_style2__tab',
			[
				'label' => esc_html__( 'Content', 'sassly-essential' ),
			]
		);

		$element->add_control(
			'wcf_inner2_padding',
			[
				'label'      => esc_html__( 'Content Padding', 'sassly-essential' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', 'rem' ],
				'selectors'  => [
					'{{WRAPPER}} .wcf--inner--accrodion--body' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$element->end_controls_tab();

		$element->end_controls_tabs();

		$element->end_controls_section();

	}

	public function icon_style_control( $element ) {
		$element->add_responsive_control(
			'wcf_dicon2_size',
			[
				'label'     => esc_html__( 'Icon Size', 'sassly-essential' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 300,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-accordion-icon-opened svg' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .elementor-accordion-icon-closed svg' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .elementor-accordion-icon-opened i'   => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .elementor-accordion-icon-closed i'   => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);
	}

	public function content_style_control( $element ) {

		$element->add_responsive_control(
			'wcf_content2__margin',
			[
				'label'      => esc_html__( 'Margin', 'sassly-essential' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .wcf--inner--accrodion--body' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$element->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name'     => 'wcf_content2_border',
				'label'    => esc_html__( 'Border', 'sassly-essential' ),
				'selector' => '{{WRAPPER}} .elementor-tab-content',
			]
		);

	}

	public function title_style_control( $element ) {

		$element->add_control(
			'title_icon2_align',
			[
				'label'     => esc_html__( 'Alignment', 'sassly-essential' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => [
					'left'  => [
						'title' => esc_html__( 'Start', 'sassly-essential' ),
						'icon'  => 'eicon-h-align-left',
					],
					'right' => [
						'title' => esc_html__( 'End', 'sassly-essential' ),
						'icon'  => 'eicon-h-align-right',
					],
				],
				'default'   => is_rtl() ? 'right' : 'left',
				'toggle'    => false,
				'selectors' => [
					'{{WRAPPER}} .elementor-tab-title' => 'text-align: {{VALUE}};',
				],
			]
		);

		$element->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name'     => 'wcf_title2_border',
				'label'    => esc_html__( 'Border', 'sassly-essential' ),
				'selector' => '{{WRAPPER}} .elementor-accordion-item',
			]
		);

		$element->add_responsive_control(
			'wcf_title2_space',
			[
				'label'     => esc_html__( 'Spacing', 'sassly-essential' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 300,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-accordion-title' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .elementor-accordion-title' => 'margin-left: {{SIZE}}{{UNIT}};',
				],
			]
		);

	}

	public function highlight_style_control( $element ) {
		$element->start_controls_section(
			'sec_highlight_style',
			[
				'label'     => esc_html__( 'Title Highlight', 'sassly-essential' ),
				'tab'       => \Elementor\Controls_Manager::TAB_STYLE,
				'condition' => [
					'_skin' => 'skin-wcf-accordion-two',
				],
			]
		);

		$element->add_control(
			'highlight_color',
			[
				'label'     => esc_html__( 'Color', 'sassly-essential' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '#f00000',
				'selectors' => [
					'{{WRAPPER}} .highlight' => 'color: {{VALUE}};',
				],
			]
		);

		$element->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'     => 'highlight_typography',
				'selector' => '{{WRAPPER}} .highlight',
			]
		);

		$element->end_controls_section();
	}

	public function title_number_style( $element ) {
		$element->start_controls_section(
			'sec_title_number_style',
			[
				'label'     => esc_html__( 'Title Number', 'sassly-essential' ),
				'tab'       => \Elementor\Controls_Manager::TAB_STYLE,
				'condition' => [
					'_skin' => 'skin-wcf-accordion-two',
				],
			]
		);

		$element->add_control(
			'faq_number_color',
			[
				'label'     => esc_html__( 'Color', 'sassly-essential' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elementor-accordion-title .number' => 'color: {{VALUE}};',
				],
			]
		);

		$element->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'     => 'faq_number_typography',
				'selector' => '{{WRAPPER}} .elementor-accordion-title .number',
			]
		);

		$element->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name'     => 'number_bg',
				'types'    => [ 'classic', 'gradient' ],
				'exclude'  => [ 'image' ],
				'selector' => '{{WRAPPER}} .elementor-accordion-title .number::after',
			]
		);

		$element->add_responsive_control(
			'number_padding',
			[
				'label'      => esc_html__( 'Padding', 'sassly-essential' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'selectors'  => [
					'{{WRAPPER}} .elementor-accordion-title .number' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$element->add_responsive_control(
			'number_margin',
			[
				'label'      => esc_html__( 'Margin', 'sassly-essential' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'selectors'  => [
					'{{WRAPPER}} .elementor-accordion-title .number' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$element->add_control(
			'line_bar_color',
			[
				'label'     => esc_html__( 'Line Color', 'sassly-essential' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wcf--accordion-two .elementor-accordion-item::before' => 'background-color: {{VALUE}};',
				],
			]
		);

		$element->add_responsive_control(
			'line_bar_pos',
			[
				'label'      => esc_html__( 'Line Position', 'sassly-essential' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
					'%'  => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .wcf--accordion-two .elementor-accordion-item::before' => 'left: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$element->end_controls_section();
	}

	public function new_feature_controls( $element ) {

		$element->start_controls_section(
			'wcf2_settings',
			[
				'label'     => esc_html__( 'WCF General', 'sassly-essential' ),
				'condition' => [
					'_skin' => 'skin-wcf-accordion-two',
				],
			]
		);

		$element->add_control(
			'default2_active',
			[
				'label'        => esc_html__( 'Default Active', 'sassly-essential' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'sassly-essential' ),
				'label_off'    => esc_html__( 'No', 'sassly-essential' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'condition'    => [
					'_skin' => 'skin-wcf-accordion-two',
				],
			]
		);

		$element->add_control(
			'title2_icon_after',
			[
				'label'        => esc_html__( 'Title Icon After Title', 'sassly-essential' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'sassly-essential' ),
				'label_off'    => esc_html__( 'No', 'sassly-essential' ),
				'return_value' => 'yes',
				'default'      => '',
				'condition'    => [
					'_skin' => 'skin-wcf-accordion-two',
				],
			]
		);

		$element->add_control(
			'innericon2_align',
			[
				'label'     => esc_html__( 'Content Alignment', 'sassly-essential' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => [
					'row'         => [
						'title' => esc_html__( 'Start', 'sassly-essential' ),
						'icon'  => 'eicon-h-align-left',
					],
					'row-reverse' => [
						'title' => esc_html__( 'End', 'sassly-essential' ),
						'icon'  => 'eicon-h-align-right',
					],
				],
				'default'   => is_rtl() ? 'row-reverse' : 'row',
				'toggle'    => false,
				'selectors' => [
					'{{WRAPPER}} .wcf--inner--accrodion--body' => 'flex-direction: {{VALUE}};',
				],
			]
		);

		$element->end_controls_section();

	}


	/**
	 * Update parent widget controls
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function update_controls( $element ) {

		$repeater = new Repeater();

		$repeater->add_control(
			'tab_number',
			[
				'label'   => esc_html__( 'Number', 'sassly-essential' ),
				'type'    => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( '01', 'sassly-essential' ),
			]
		);

		$repeater->add_control(
			'tab_title',
			[
				'label'       => esc_html__( 'Title', 'sassly-essential' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => esc_html__( 'Accordion Title', 'sassly-essential' ),
				'dynamic'     => [
					'active' => true,
				],
				'label_block' => true,
				'description' => 'For Highlight, keep text in [ ]. Ex. [ Text ]',
			]
		);

		$repeater->add_control(
			'wcf_title_icon',
			[
				'label' => esc_html__( 'Title Icon', 'sassly-essential' ),
				'type'  => \Elementor\Controls_Manager::ICONS,
			]
		);

		$repeater->add_control(
			'tab_content',
			[
				'label'   => esc_html__( 'Content', 'sassly-essential' ),
				'type'    => \Elementor\Controls_Manager::WYSIWYG,
				'default' => esc_html__( 'Accordion Content', 'sassly-essential' ),
			]
		);

		$repeater->add_control(
			'wcf_content_icon',
			[
				'label' => esc_html__( 'Content Icon', 'sassly-essential' ),
				'type'  => \Elementor\Controls_Manager::ICONS,
			]
		);

		$repeater->add_control(
			'wcf_accordion_reapeater_hidden_id',
			[
				'label' => esc_html__( 'View', 'sassly-essential' ),
				'type'  => \Elementor\Controls_Manager::HIDDEN,
			]
		);

		// add skin condition on widget Icon controls => show if skin != skin-simple
		$this->parent->update_control(
			'tabs',
			[
				'fields' => $repeater->get_controls(),
			]
		);

		$this->parent->update_control(
			'border_color',
			[
				'condition' => [
					'_skin!' => 'skin-wcf-accordion-two',
				],
			]
		);

		$this->parent->update_control(
			'border_width',
			[
				'condition' => [
					'_skin!' => 'skin-wcf-accordion-two',
				],
			]
		);

	}

	protected function get_repeater_setting_key( $setting_key, $repeater_key, $repeater_item_index ) {
		return implode( '.', [ $repeater_key, $repeater_item_index, $setting_key ] );
	}

	/**
	 * Render accordion widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	public function render() {
		$settings = $this->parent->get_settings();
		$migrated = isset( $settings['__fa4_migrated']['selected_icon'] );

		if ( ! isset( $settings['icon'] ) && ! \Elementor\Icons_Manager::is_migration_allowed() ) {
			// @todo: remove when deprecated
			// added as bc in 2.6
			// add old default
			$settings['icon']        = 'fa fa-plus';
			$settings['icon_active'] = 'fa fa-minus';
			$settings['icon_align']  = $this->get_settings( 'icon_align' );
		}
		$this->parent->add_render_attribute(
			'wcf_wrapper',
			[

				'class'       => [ 'elementor-accordion', 'wcf--accordion-two' ],
				'data-active' => [ $settings['default2_active'] ],

			]
		);
		$default2_active = $settings['default2_active'];
		$is_new          = empty( $settings['icon'] ) && \Elementor\Icons_Manager::is_migration_allowed();
		$has_icon        = ( ! $is_new || ! empty( $settings['selected_icon']['value'] ) );
		$id_int          = substr( $this->parent->get_id_int(), 0, 3 );

		?>
        <style>
            .wcf--inner--accrodion--body {
                gap: 60px;
                display: -webkit-box;
                display: -ms-flexbox;
                display: flex;
                padding: 0;
                padding-top: 10px;
                padding-bottom: 25px;
            }

            .wcf--accordion-two .elementor-accordion-title {
                gap: 3px;
                display: flex;
            }

            .wcf--accordion-two .elementor-accordion-title .number {
                position: relative;
                z-index: 1;
            }

            .wcf--accordion-two .elementor-accordion-title .number::after {
                position: absolute;
                content: "";
                width: 100%;
                height: 40px;
                background: #fff;
                left: 0;
                z-index: -1;
                top: -13px;
            }

            .wcf--accordion-two .elementor-accordion-item {
                position: relative;
            }

            .wcf--accordion-two .elementor-accordion-item::before {
                position: absolute;
                content: "";
                width: 1px;
                height: 100%;
                background: #ddd;
                left: 8px;
                top: 0px;
            }


        </style>
        <div <?php echo $this->parent->get_render_attribute_string( 'wcf_wrapper' ); ?>>
			<?php
			foreach ( $settings['tabs'] as $index => $item ) :
			$tab_count = $index + 1;

			$tab_title_setting_key   = $this->get_repeater_setting_key( 'tab_title', 'tabs', $index );
			$tab_content_setting_key = $this->get_repeater_setting_key( 'tab_content', 'tabs', $index );

			$this->parent->add_render_attribute( $tab_title_setting_key, [
				'id'            => 'elementor-tab-title-' . $id_int . $tab_count,
				'class'         => [ 'elementor-tab-title' ],
				'data-active'   => $index == 0 ? 'yes' : '',
				'data-indexkey' => $this->parent->get_id() . '_' . $index
			] );

			$this->parent->add_render_attribute( $tab_content_setting_key, [
				'id'            => 'elementor-tab-content-' . $id_int . $tab_count,
				'class'         => [ 'elementor-tab-content', 'elementor-clearfix' ],
				'data-active'   => $index == 0 ? 'yes' : '',
				'data-indexkey' => $this->parent->get_id() . '_' . $index
			] );

			?>
            <div class="elementor-accordion-item">
                <<?php Utils::print_validated_html_tag( $settings['title_html_tag'] ); ?> <?php $this->parent->print_render_attribute_string( $tab_title_setting_key ); ?>
                >
				<?php if ( $has_icon ) : ?>
                    <span class="elementor-accordion-icon elementor-accordion-icon-<?php echo esc_attr( $settings['icon_align'] ); ?>"
                          aria-hidden="true">
							<?php
							if ( $is_new || $migrated ) { ?>
                                <span class="elementor-accordion-icon-closed"><?php \Elementor\Icons_Manager::render_icon( $settings['selected_icon'] ); ?></span>
                                <span class="elementor-accordion-icon-opened"><?php \Elementor\Icons_Manager::render_icon( $settings['selected_active_icon'] ); ?></span>
							<?php } else { ?>
                                <i class="elementor-accordion-icon-closed <?php echo esc_attr( $settings['icon'] ); ?>"></i>
                                <i class="elementor-accordion-icon-opened <?php echo esc_attr( $settings['icon_active'] ); ?>"></i>
							<?php } ?>
							</span>
				<?php endif; ?>
                <a class="elementor-accordion-title" tabindex="0">
					<?php if ( $settings['title_icon_after'] === '' ) { ?>
                        <span class="elementor-accordion-title-icon"><?php \Elementor\Icons_Manager::render_icon( $item['wcf_title_icon'] ); ?></span>
					<?php } ?>
					<?php
					$faq_title = $item['tab_title'];
					preg_match_all( '/\[([^\]]*)\]/', $faq_title, $matches );
					foreach ( $matches[0] as $key => $value ) {
						$faq_title = str_replace( $value, '<span class="highlight">' . $matches[1][ $key ] . '</span>', $faq_title, );
					}
					?>
                    <span class="number"><?php echo $item['tab_number']; ?></span>
                    <div class="title-wrap"><?php echo $faq_title; ?></div>
					<?php if ( $settings['title_icon_after'] === 'yes' ) { ?>
                        <span class="elementor-accordion-title-icon"><?php \Elementor\Icons_Manager::render_icon( $item['wcf_title_icon'] ); ?></span>
					<?php } ?>
                </a>
            </<?php Utils::print_validated_html_tag( $settings['title_html_tag'] ); ?>>
            <div <?php $this->parent->print_render_attribute_string( $tab_content_setting_key ); ?>>
                <div class="wcf--inner--accrodion--body">
					<?php if ( isset( $item['wcf_content_icon']['value'] ) && $item['wcf_content_icon']['value'] != '' ) { ?>
                        <span class="wcf-content-icon">
									<?php \Elementor\Icons_Manager::render_icon( $item['wcf_content_icon'] ); ?>
								</span>
					<?php } ?>
                    <div class="wcf--content"><?php echo $item['tab_content']; ?></div>
                </div>
            </div>
        </div>
	<?php endforeach; ?>
		<?php
		if ( isset( $settings['faq_schema'] ) && 'yes' === $settings['faq_schema'] ) {
			$json = [
				'@context'   => 'https://schema.org',
				'@type'      => 'FAQPage',
				'mainEntity' => [],
			];

			foreach ( $settings['tabs'] as $index => $item ) {
				$json['mainEntity'][] = [
					'@type'          => 'Question',
					'name'           => wp_strip_all_tags( $item['tab_title'] ),
					'acceptedAnswer' => [
						'@type' => 'Answer',
						'text'  => $this->parse_text_editor( $item['tab_content'] ),
					],
				];
			}
			?>
            <script type="application/ld+json"><?php echo wp_json_encode( $json ); ?></script>
		<?php } ?>
        </div>
		<?php
	}


	/**
	 * Return empty for _content_template to force PHP rendering and update editor template
	 * _content_template isn't supported in Skin
	 * @return string The JavaScript template output.
	 */

	public function skin_print_template( $content, $button ) {
		if ( 'accordion' == $button->get_name() ) {
			return;
		}

		return $content;
	}


}