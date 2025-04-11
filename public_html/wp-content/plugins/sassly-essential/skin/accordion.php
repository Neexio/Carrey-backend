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

class Skin_Accordion extends Skin_Base {

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
		add_action( 'elementor/element/accordion/section_title/after_section_start', [
			$this,
			'add_new_skin_controls'
		] );
		add_action( 'elementor/element/accordion/section_toggle_style_title/after_section_start', [
			$this,
			'title_style_control'
		] );
		add_action( 'elementor/element/accordion/section_title_style/after_section_start', [
			$this,
			'item_style_control'
		] );
		add_action( 'elementor/element/accordion/section_toggle_style_content/before_section_end', [
			$this,
			'content_style_control'
		] );
		add_action( 'elementor/element/accordion/section_toggle_style_icon/after_section_start', [
			$this,
			'icon_style_control'
		] );
		add_action( 'elementor/element/accordion/section_toggle_style_content/after_section_end', [
			$this,
			'inner_content_style_control'
		] );
		add_action( 'elementor/element/accordion/section_toggle_style_title/after_section_end', [
			$this,
			'inner_title_style_control'
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
		return 'skin-wcf-accordion';
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
		return __( 'WCF', 'sassly-essential' );
	}

	/**
	 * Add skin controls
	 *
	 * @since 1.0.0
	 * @access public
	 */

	public function item_style_control( $element ) {
		$element->add_responsive_control(
			'item_padding',
			[
				'label'      => esc_html__( 'Padding', 'sassly-essential' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .elementor-accordion-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$element->add_responsive_control(
			'item_b_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'sassly-essential' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .elementor-accordion-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
	}

	public function inner_title_style_control( $element ) {
		$element->start_controls_section(
			'wcf_stitleicon_section',
			[
				'label'     => esc_html__( 'Title Icon', 'sassly-essential' ),
				'tab'       => \Elementor\Controls_Manager::TAB_STYLE,
				'condition' => [
					'_skin' => 'skin-wcf-accordion',
				],
			]
		);

		$element->add_responsive_control(
			'wcf_title_icon_space',
			[
				'label'     => esc_html__( 'Spacing', 'elementor' ),
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
			'wcf_title_icon_color',
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
			'wcf_title_icon_hover_color',
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
				'name'     => 'wcf_title_icon_typography',
				'selector' => '{{WRAPPER}} .elementor-accordion-title-icon i',
			]
		);

		$element->end_controls_section();

	}

	public function inner_content_style_control( $element ) {

		$element->start_controls_section(
			'wcf_styleicon_section',
			[
				'label'     => esc_html__( 'Inner Content', 'sassly-essential' ),
				'tab'       => \Elementor\Controls_Manager::TAB_STYLE,
				'condition' => [
					'_skin' => 'skin-wcf-accordion',
				],
			]
		);


		$element->start_controls_tabs(
			'wcf_icont_style_tabs'
		);

		$element->start_controls_tab(
			'wcf_icont_style_icon_tab',
			[
				'label' => esc_html__( 'Icon', 'sassly-essential' ),
			]
		);

		$element->add_control(
			'wcf_cicon_color',
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
				'name'     => 'wcf_ci_typography',
				'selector' => '{{WRAPPER}} .wcf--inner--accrodion--body i',
			]
		);

		$element->add_responsive_control(
			'wcf_inner_space',
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
			'wcf_icont_style__tab',
			[
				'label' => esc_html__( 'Content', 'sassly-essential' ),
			]
		);

		$element->add_control(
			'wcf_inner_padding',
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
			'wcf_dicon_size',
			[
				'label'     => esc_html__( 'Icon Size', 'elementor' ),
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
			'wcf_content__margin',
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
				'name'     => 'wcf_content_border',
				'label'    => esc_html__( 'Border', 'elementor' ),
				'selector' => '{{WRAPPER}} .elementor-tab-content',
			]
		);

	}

	public function title_style_control( $element ) {

		$element->add_control(
			'title_icon_align',
			[
				'label'     => esc_html__( 'Alignment', 'elementor' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => [
					'left'  => [
						'title' => esc_html__( 'Start', 'elementor' ),
						'icon'  => 'eicon-h-align-left',
					],
					'right' => [
						'title' => esc_html__( 'End', 'elementor' ),
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
				'name'     => 'wcf_title_border',
				'label'    => esc_html__( 'Border', 'elementor' ),
				'selector' => '{{WRAPPER}} .elementor-accordion-item',
			]
		);

		$element->add_responsive_control(
			'wcf_title_space',
			[
				'label'     => esc_html__( 'Spacing', 'elementor' ),
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

	public function add_new_skin_controls( $element ) {

		// $element->add_control(
		// 	'default_active',
		// 	[
		// 		'label' => esc_html__( 'Default Active', 'sassly-essential' ),
		// 		'type' => \Elementor\Controls_Manager::SWITCHER,
		// 		'label_on' => esc_html__( 'Show', 'sassly-essential' ),
		// 		'label_off' => esc_html__( 'Hide', 'sassly-essential' ),
		// 		'return_value' => 'yes',
		// 		'default' => 'yes',
		// 	]
		// );

	}

	public function new_feature_controls( $element ) {

		$element->start_controls_section(
			'wcf_settings',
			[
				'label'     => esc_html__( 'WCF General', 'sassly-essential' ),
				'condition' => [
					'_skin' => 'skin-wcf-accordion',
				],
			]
		);

		$element->add_control(
			'default_active',
			[
				'label'        => esc_html__( 'Default Active', 'sassly-essential' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'sassly-essential' ),
				'label_off'    => esc_html__( 'No', 'sassly-essential' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'condition'    => [
					'_skin' => 'skin-wcf-accordion',
				],
			]
		);

		$element->add_control(
			'title_icon_after',
			[
				'label'        => esc_html__( 'Title Icon After Title', 'sassly-essential' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'sassly-essential' ),
				'label_off'    => esc_html__( 'No', 'sassly-essential' ),
				'return_value' => 'yes',
				'default'      => '',
				'condition'    => [
					'_skin' => 'skin-wcf-accordion',
				],
			]
		);

		$element->add_control(
			'innericon_align',
			[
				'label'     => esc_html__( 'Content Alignment', 'elementor' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => [
					'row'         => [
						'title' => esc_html__( 'Start', 'elementor' ),
						'icon'  => 'eicon-h-align-left',
					],
					'row-reverse' => [
						'title' => esc_html__( 'End', 'elementor' ),
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
			'tab_title',
			[
				'label'       => esc_html__( 'Title', 'sassly-essential' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => esc_html__( 'Accordion Title', 'sassly-essential' ),
				'dynamic'     => [
					'active' => true,
				],
				'label_block' => true,
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
				'label'   => esc_html__( 'Content', 'elementor' ),
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
					'_skin!' => 'skin-wcf-accordion',
				],
			]
		);

		$this->parent->update_control(
			'border_width',
			[
				'condition' => [
					'_skin!' => 'skin-wcf-accordion',
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

				'class'       => [ 'elementor-accordion' ],
				'data-active' => [ $settings['default_active'] ],

			]
		);
		$default_active = $settings['default_active'];
		$is_new         = empty( $settings['icon'] ) && \Elementor\Icons_Manager::is_migration_allowed();
		$has_icon       = ( ! $is_new || ! empty( $settings['selected_icon']['value'] ) );
		$id_int         = substr( $this->parent->get_id_int(), 0, 3 );

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

            .elementor-accordion-item {
                overflow: hidden;
                position: relative;
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
					echo $item['tab_title'];
					?>
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