<?php
namespace WCFAddonsPro\Widgets;

use Elementor\Controls_Manager;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Repeater;
use Elementor\Widget_Base;
use WCF_ADDONS\WCF_Button_Trait;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Advance Portfolio
 *
 * Elementor widget for Posts.
 *
 * @since 1.0.0
 */
class Advance_Pricing_Table extends Widget_Base {

	use WCF_Button_Trait;

	protected $_has_template_content = false;

	/**
	 * Retrieve the widget name.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'wcf--a-pricing-table';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'WCF Advance Pricing Table', 'wcf-addons-pro' );
	}

	/**
	 * Retrieve the widget icon.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'wcf eicon-price-table';
	}

	/**
	 * Retrieve the list of categories the widget belongs to.
	 *
	 * Used to determine where to display the widget in the editor.
	 *
	 * Note that currently Elementor supports only one category.
	 * When multiple categories passed, Elementor uses the first one.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return array Widget categories.
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
		return array(
			'wcf--advance-pricing-table',
			'wcf--button',
		);
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
			'section_layout',
			[
				'label' => __( 'Pricing Table', 'wcf-addons-pro' ),
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_layout_style',

			[
				'label' => __( 'Pricing Table', 'wcf-addons-pro' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'layout_padding',
			[
				'label' => esc_html__( 'Padding', 'wcf-addons-pro' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .wcf__priceTable' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->end_controls_section();

		// Header
		$this->header_controls();

		// Pricing
		$this->pricing_element_controls();

		// Features
		$this->feature_list_controls();

		// Button
		$this->start_controls_section(
			'section_button',
			[
				'label' => esc_html__( 'Button', 'wcf-addons-pro' ),
			]
		);

		$this->register_button_content_controls();

		$this->end_controls_section();

		// Button Style
		$this->start_controls_section(
			'section_btn_style',
			[
				'label'     => esc_html__( 'Button', 'wcf-addons-pro' ),
				'tab'       => Controls_Manager::TAB_STYLE,
			]
		);

		$this->register_button_style_controls();

		$this->add_control(
			'button_align',
			[
				'label' => esc_html__( 'Alignment', 'wcf-addons-pro' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'wcf-addons-pro' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'wcf-addons-pro' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'wcf-addons-pro' ),
						'icon' => 'eicon-text-align-right',
					],
					'stretch' => [
						'title' => esc_html__( 'Stretch', 'wcf-addons-pro' ),
						'icon' => 'eicon-align-stretch-h',
					],
				],
				'prefix_class' => 'btn-align-',
				'toggle' => true,
				'selectors' => [
					'{{WRAPPER}} .wcf__btn' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'button_margin',
			[
				'label' => esc_html__( 'Margin', 'wcf-addons-pro' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem' ],
				'selectors' => [
					'{{WRAPPER}} .wcf__btn' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		// Ribbon
		$this->ribbon_controls();
	}

	// Header Controls
	private function header_controls() {
		$this->start_controls_section(
			'section_header',
			[
				'label' => esc_html__( 'Header', 'wcf-addons-pro' ),
			]
		);

		$this->add_control(
			'title',
			[
				'label'   => esc_html__( 'Title', 'wcf-addons-pro' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'Enter your title', 'wcf-addons-pro' ),
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$this->add_control(
			'title_tag',
			[
				'label'   => esc_html__( 'Title HTML Tag', 'wcf-addons-pro' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'h2' => 'H2',
					'h3' => 'H3',
					'h4' => 'H4',
					'h5' => 'H5',
					'h6' => 'H6',
				],
				'default' => 'h3',
			]
		);

		$this->add_control(
			'sub_title',
			[
				'label'   => esc_html__( 'Sub Title', 'wcf-addons-pro' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'Enter your subtitle', 'wcf-addons-pro' ),
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$this->end_controls_section();

		// Style
		$this->start_controls_section(
			'section_header_style',
			[
				'label'      => esc_html__( 'Header', 'wcf-addons-pro' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->add_control(
			'heading_heading_style',
			[
				'label' => esc_html__( 'Title', 'wcf-addons-pro' ),
				'type'  => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'heading_color',
			[
				'label'     => esc_html__( 'Color', 'wcf-addons-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .title' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'heading_typography',
				'selector' => '{{WRAPPER}} .title',
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'title_bg',
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .title',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'title_border',
				'selector' => '{{WRAPPER}} .title',
			]
		);

		$this->add_responsive_control(
			'title_b_radius',
			[
				'label' => esc_html__( 'Border Radius', 'wcf-addons-pro' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem' ],
				'selectors' => [
					'{{WRAPPER}} .title' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'title_padding',
			[
				'label' => esc_html__( 'Padding', 'wcf-addons-pro' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem' ],
				'selectors' => [
					'{{WRAPPER}} .title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'title_align',
			[
				'label' => esc_html__( 'Alignment', 'wcf-addons-pro' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'wcf-addons-pro' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'wcf-addons-pro' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'wcf-addons-pro' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'toggle' => true,
				'selectors' => [
					'{{WRAPPER}} .title-wrap' => 'text-align: {{VALUE}};',
				],
			]
		);

		// Sub Title
		$this->add_control(
			'heading_subtitle_style',
			[
				'label'     => esc_html__( 'Sub Title', 'wcf-addons-pro' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'subtitle_color',
			[
				'label'     => esc_html__( 'Color', 'wcf-addons-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .sub-title' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'subtitle_typography',
				'selector'  => '{{WRAPPER}} .sub-title',
			]
		);

		$this->add_responsive_control(
			'subtitle_padding',
			[
				'label' => esc_html__( 'Padding', 'wcf-addons-pro' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem' ],
				'selectors' => [
					'{{WRAPPER}} .sub-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'subtitle_align',
			[
				'label' => esc_html__( 'Alignment', 'wcf-addons-pro' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'wcf-addons-pro' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'wcf-addons-pro' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'wcf-addons-pro' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'toggle' => true,
				'selectors' => [
					'{{WRAPPER}} .sub-title' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();
	}

	// Pricing Controls
	private function pricing_element_controls() {
		$this->start_controls_section(
			'section_pricing',
			[
				'label' => esc_html__( 'Pricing', 'wcf-addons-pro' ),
			]
		);

		$this->add_control(
			'currency_symbol',
			[
				'label'   => esc_html__( 'Currency Symbol', 'wcf-addons-pro' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					''             => esc_html__( 'None', 'wcf-addons-pro' ),
					'dollar'       => '&#36; ' . _x( 'Dollar', 'Currency', 'wcf-addons-pro' ),
					'euro'         => '&#128; ' . _x( 'Euro', 'Currency', 'wcf-addons-pro' ),
					'baht'         => '&#3647; ' . _x( 'Baht', 'Currency', 'wcf-addons-pro' ),
					'franc'        => '&#8355; ' . _x( 'Franc', 'Currency', 'wcf-addons-pro' ),
					'guilder'      => '&fnof; ' . _x( 'Guilder', 'Currency', 'wcf-addons-pro' ),
					'krona'        => 'kr ' . _x( 'Krona', 'Currency', 'wcf-addons-pro' ),
					'lira'         => '&#8356; ' . _x( 'Lira', 'Currency', 'wcf-addons-pro' ),
					'peseta'       => '&#8359 ' . _x( 'Peseta', 'Currency', 'wcf-addons-pro' ),
					'peso'         => '&#8369; ' . _x( 'Peso', 'Currency', 'wcf-addons-pro' ),
					'pound'        => '&#163; ' . _x( 'Pound Sterling', 'Currency', 'wcf-addons-pro' ),
					'real'         => 'R$ ' . _x( 'Real', 'Currency', 'wcf-addons-pro' ),
					'ruble'        => '&#8381; ' . _x( 'Ruble', 'Currency', 'wcf-addons-pro' ),
					'rupee'        => '&#8360; ' . _x( 'Rupee', 'Currency', 'wcf-addons-pro' ),
					'indian_rupee' => '&#8377; ' . _x( 'Rupee (Indian)', 'Currency', 'wcf-addons-pro' ),
					'shekel'       => '&#8362; ' . _x( 'Shekel', 'Currency', 'wcf-addons-pro' ),
					'yen'          => '&#165; ' . _x( 'Yen/Yuan', 'Currency', 'wcf-addons-pro' ),
					'won'          => '&#8361; ' . _x( 'Won', 'Currency', 'wcf-addons-pro' ),
					'custom'       => esc_html__( 'Custom', 'wcf-addons-pro' ),
				],
				'default' => 'dollar',
			]
		);

		$this->add_control(
			'currency_symbol_custom',
			[
				'label'     => esc_html__( 'Custom Symbol', 'wcf-addons-pro' ),
				'type'      => Controls_Manager::TEXT,
				'condition' => [
					'currency_symbol' => 'custom',
				],
			]
		);

		$this->add_control(
			'price',
			[
				'label'   => esc_html__( 'Price', 'wcf-addons-pro' ),
				'type'    => Controls_Manager::TEXT,
				'default' => '9.99',
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$this->add_control(
			'sale',
			[
				'label'     => esc_html__( 'Sale', 'wcf-addons-pro' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => esc_html__( 'On', 'wcf-addons-pro' ),
				'label_off' => esc_html__( 'Off', 'wcf-addons-pro' ),
				'default'   => '',
			]
		);

		$this->add_control(
			'original_price',
			[
				'label'     => esc_html__( 'Original Price', 'wcf-addons-pro' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => '19.99',
				'condition' => [
					'sale' => 'yes',
				],
				'dynamic'   => [
					'active' => true,
				],
			]
		);

		$this->add_control(
			'period',
			[
				'label'   => esc_html__( 'Period', 'wcf-addons-pro' ),
				'type'    => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => esc_html__( 'Monthly', 'wcf-addons-pro' ),
			]
		);

		$this->end_controls_section();

		// Style
		$this->start_controls_section(
			'section_pricing_element_style',
			[
				'label'      => esc_html__( 'Pricing', 'wcf-addons-pro' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->add_control(
			'price_color',
			[
				'label'     => esc_html__( 'Color', 'wcf-addons-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .pt-sale-price, {{WRAPPER}} .pt-currency' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'price_typography',
				'selector' => '{{WRAPPER}} .pt-sale-price',
				'global'   => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
			]
		);

		$this->add_control(
			'heading_currency_style',
			[
				'label'     => esc_html__( 'Currency Symbol', 'wcf-addons-pro' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'currency_symbol!' => '',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'currency_typography',
				'selector'  => '{{WRAPPER}} .pt-currency',
				'exclude' => ['text_transform', 'text_decoration', 'letter_spacing', 'word_spacing'],
				'condition' => [
					'currency_symbol!' => '',
				],
			]
		);

		$this->add_responsive_control(
			'currency_align',
			[
				'label' => esc_html__( 'Vertical Alignment', 'wcf-addons-pro' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'flex-start' => [
						'title' => esc_html__( 'Top', 'wcf-addons-pro' ),
						'icon' => 'eicon-v-align-top',
					],
					'center' => [
						'title' => esc_html__( 'Middle', 'wcf-addons-pro' ),
						'icon' => 'eicon-v-align-middle',
					],
					'flex-end' => [
						'title' => esc_html__( 'Bottom', 'wcf-addons-pro' ),
						'icon' => 'eicon-v-align-bottom',
					],
				],
				'toggle' => true,
				'selectors' => [
					'{{WRAPPER}} .pt-currency' => 'align-self: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'heading_original_price_style',
			[
				'label'     => esc_html__( 'Original Price', 'wcf-addons-pro' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'sale'            => 'yes',
					'original_price!' => '',
				],
			]
		);

		$this->add_control(
			'original_price_color',
			[
				'label'     => esc_html__( 'Color', 'wcf-addons-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .pt-org-price' => 'color: {{VALUE}}',
				],
				'condition' => [
					'sale'            => 'yes',
					'original_price!' => '',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'original_price_typography',
				'selector'  => '{{WRAPPER}} .pt-org-price',
				'condition' => [
					'sale'            => 'yes',
					'original_price!' => '',
				],
			]
		);

		$this->add_control(
			'heading_period_style',
			[
				'label'     => esc_html__( 'Period', 'wcf-addons-pro' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'period!' => '',
				],
			]
		);

		$this->add_control(
			'period_color',
			[
				'label'     => esc_html__( 'Color', 'wcf-addons-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .pt-period' => 'color: {{VALUE}}',
				],
				'condition' => [
					'period!' => '',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'period_typography',
				'selector'  => '{{WRAPPER}} .pt-period',
				'condition' => [
					'period!' => '',
				],
			]
		);

		$this->add_control(
			'price_bg_color',
			[
				'label'     => esc_html__( 'Background Color', 'wcf-addons-pro' ),
				'type'      => Controls_Manager::COLOR,
				'separator' => 'before',
				'selectors' => [
					'{{WRAPPER}} .price-wrap' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .pt-header' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'header_btm_padding',
			[
				'label' => esc_html__( 'Padding', 'wcf-addons-pro' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem' ],
				'selectors' => [
					'{{WRAPPER}} .price-wrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'price_align',
			[
				'label' => esc_html__( 'Alignment', 'wcf-addons-pro' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'start' => [
						'title' => esc_html__( 'Left', 'wcf-addons-pro' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'wcf-addons-pro' ),
						'icon' => 'eicon-text-align-center',
					],
					'end' => [
						'title' => esc_html__( 'Right', 'wcf-addons-pro' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'toggle' => true,
				'selectors' => [
					'{{WRAPPER}} .price-wrap' => 'text-align: {{VALUE}}; justify-content: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section();
	}

	// Feature Controls
	protected function feature_list_controls() {
		$this->start_controls_section(
			'section_features',
			[
				'label' => esc_html__( 'Features', 'wcf-addons-pro' ),
			]
		);

		$this->add_control(
			'feature_title',
			[
				'label'       => esc_html__( 'Title', 'wcf-addons-pro' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Advantages', 'wcf-addons-pro' ),
				'placeholder' => esc_html__( 'Advantages', 'wcf-addons-pro' ),
			]
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'item_text',
			[
				'label'   => esc_html__( 'Text', 'wcf-addons-pro' ),
				'type'    => Controls_Manager::TEXT,
				'label_block' => true,
				'dynamic' => [
					'active' => true,
				],
				'default' => esc_html__( 'List Item', 'wcf-addons-pro' ),
			]
		);

		$default_icon = [
			'value'   => 'fas fa-check',
			'library' => 'fa-solid',
		];

		$repeater->add_control(
			'selected_item_icon',
			[
				'label'            => esc_html__( 'Icon', 'wcf-addons-pro' ),
				'type'             => Controls_Manager::ICONS,
				'skin'             => 'inline',
				'label_block'      => false,
				'default'          => $default_icon,
			]
		);

		$repeater->add_control(
			'item_text_color',
			[
				'label'     => esc_html__( 'Text Color', 'wcf-addons-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}}'   => 'color: {{VALUE}} !important',
				],
			]
		);

		$repeater->add_control(
			'item_icon_color',
			[
				'label'     => esc_html__( 'Icon Color', 'wcf-addons-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} i'   => 'color: {{VALUE}}',
					'{{WRAPPER}} {{CURRENT_ITEM}} svg' => 'fill: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'features_list',
			[
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'prevent_empty' => false,
				'default'     => [
					[
						'item_text'          => esc_html__( 'Starter Pack Included', 'wcf-addons-pro' ),
						'selected_item_icon' => $default_icon,
					],
					[
						'item_text'          => esc_html__( ' Budget Minimization', 'wcf-addons-pro' ),
						'selected_item_icon' => $default_icon,
					],
					[
						'item_text'          => esc_html__( 'Venue Booking', 'wcf-addons-pro' ),
						'selected_item_icon' => $default_icon,
					],
					[
						'item_text'          => esc_html__( 'Personal Trainer', 'wcf-addons-pro' ),
						'selected_item_icon' => $default_icon,
					],
				],
				'title_field' => '{{{ item_text }}}',
			]
		);

		$this->end_controls_section();

		// Style
		$this->start_controls_section(
			'section_features_list_style',
			[
				'label'      => esc_html__( 'Features', 'wcf-addons-pro' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->add_control(
			'feature_title_heading',
			[
				'label' => esc_html__( 'Title', 'wcf-addons-pro' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'feature_title_color',
			[
				'label'     => esc_html__( 'Color', 'wcf-addons-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .feature-title' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'feature_title_typo',
				'selector' => '{{WRAPPER}} .feature-title',
			]
		);

		$this->add_responsive_control(
			'feature_title_margin',
			[
				'label' => esc_html__( 'Margin', 'wcf-addons-pro' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 10,
					'left' => 0,
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .feature-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Text
		$this->add_control(
			'feature_text_heading',
			[
				'label' => esc_html__( 'List Text', 'wcf-addons-pro' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'features_list_color',
			[
				'label'     => esc_html__( 'Color', 'wcf-addons-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .pt-feature li' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'features_list_typography',
				'selector' => '{{WRAPPER}} .pt-feature li',
			]
		);

		$this->add_responsive_control(
			'features_icon_size',
			[
				'label'      => esc_html__( 'Icon Size', 'wcf-addons-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', 'rem', 'custom' ],
				'range'      => [
					'px' => [
						'min' => 1,
						'max' => 50,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .pt-feature li i, {{WRAPPER}} .pt-feature li svg' => 'font-size: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_responsive_control(
			'features_icon_spacing',
			[
				'label'      => esc_html__( 'Icon spacing', 'wcf-addons-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', 'rem', 'custom' ],
				'range'      => [
					'px' => [
						'min' => 1,
						'max' => 50,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .pt-feature li i, {{WRAPPER}} .pt-feature li svg' => 'margin-right: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_responsive_control(
			'features_list_gap',
			[
				'label'      => esc_html__( 'Gap', 'wcf-addons-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', 'rem', 'custom' ],
				'range'      => [
					'px' => [
						'min' => 1,
						'max' => 50,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .pt-feature li' => 'padding-bottom: {{SIZE}}{{UNIT}}',
				],
			]
		);

		// Wrapper
		$this->add_responsive_control(
			'feature_padding',
			[
				'label' => esc_html__( 'Padding', 'wcf-addons-pro' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem' ],
				'selectors' => [
					'{{WRAPPER}} .pt-feature' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'feature_border',
				'selector' => '{{WRAPPER}} .pt-feature',
			]
		);

		$this->add_control(
			'feature_align',
			[
				'label' => esc_html__( 'Alignment', 'wcf-addons-pro' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'wcf-addons-pro' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'wcf-addons-pro' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'wcf-addons-pro' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'toggle' => true,
				'selectors' => [
					'{{WRAPPER}} .pt-feature' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();
	}

	// Ribbon Controls
	protected function ribbon_controls() {
		$this->start_controls_section(
			'section_ribbon',
			[
				'label' => esc_html__( 'Ribbon', 'wcf-addons-pro' ),
			]
		);

		$this->add_control(
			'show_ribbon',
			[
				'label'     => esc_html__( 'Show', 'wcf-addons-pro' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'yes',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'ribbon_title',
			[
				'label'     => esc_html__( 'Title', 'wcf-addons-pro' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => esc_html__( 'Popular', 'wcf-addons-pro' ),
				'condition' => [
					'show_ribbon' => 'yes',
				],
				'dynamic'   => [
					'active' => true,
				],
			]
		);

		$this->end_controls_section();

		// Style
		$this->start_controls_section(
			'section_ribbon_style',
			[
				'label'      => esc_html__( 'Ribbon', 'wcf-addons-pro' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
				'condition'  => [
					'show_ribbon' => 'yes',
				],
			]
		);

		$this->add_control(
			'ribbon_text_color',
			[
				'label'     => esc_html__( 'Text Color', 'wcf-addons-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ribbon' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'ribbon_typo',
				'selector' => '{{WRAPPER}} .ribbon',
			]
		);

		$this->add_control(
			'ribbon_bg_color',
			[
				'label'     => esc_html__( 'Background Color', 'wcf-addons-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ribbon'   => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'ribbon_position',
			[
				'label' => esc_html__( 'Transform', 'wcf-addons-pro' ),
				'type' => Controls_Manager::POPOVER_TOGGLE,
				'label_off' => esc_html__( 'Default', 'wcf-addons-pro' ),
				'label_on' => esc_html__( 'Custom', 'wcf-addons-pro' ),
			]
		);

		$this->start_popover();

		$this->add_control(
			'ribbon_position_point',
			[
				'label' => esc_html__( 'Ribbon Start', 'wcf-addons-pro' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'left',
				'prefix_class' => 'ribbon-position-',
				'options' => [
					'left' => esc_html__( 'Left', 'wcf-addons-pro' ),
					'right' => esc_html__( 'Right', 'wcf-addons-pro' ),
				],
			]
		);

		$this->add_responsive_control(
			'ribbon_position_x',
			[
				'label' => esc_html__( 'TranslateX', 'wcf-addons-pro' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => -1000,
						'max' => 1000,
					],
					'%' => [
						'min' => -100,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}}.ribbon-position-left .ribbon' => 'left: {{SIZE}}{{UNIT}}; right: auto;',
					'{{WRAPPER}}.ribbon-position-right .ribbon' => 'right: {{SIZE}}{{UNIT}}; left: auto;',
				],
			]
		);

		$this->add_responsive_control(
			'ribbon_position_y',
			[
				'label' => esc_html__( 'TranslateY', 'wcf-addons-pro' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => -1000,
						'max' => 1000,
					],
					'%' => [
						'min' => -100,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .ribbon' => 'top: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'ribbon_rotate',
			[
				'label' => esc_html__( 'Rotate', 'wcf-addons-pro' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => -360,
						'max' => 360,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .ribbon' => 'transform: rotate({{SIZE}}deg);',
				],
			]
		);

		$this->end_popover();

		$this->add_responsive_control(
			'ribbon_b_radius',
			[
				'label' => esc_html__( 'Border Radius', 'wcf-addons-pro' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .ribbon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'ribbon_padding',
			[
				'label' => esc_html__( 'Padding', 'wcf-addons-pro' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .ribbon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}
}
