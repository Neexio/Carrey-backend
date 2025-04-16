<?php

namespace SasslyEssentialApp\Widgets;

use Elementor\Control_Media;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Css_Filter;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Text_Stroke;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Icons_Manager;
use Elementor\Plugin;
use Elementor\Utils;
use Elementor\Repeater;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use SasslyEssentialApp\Inc\WCF_Walker_Elementor_Nav;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

/**
 * Image
 *
 * Elementor widget for image.
 *
 * @since 1.0.0
 */
class Header_Preset extends Widget_Base {

    public $default_bar = '';

	/**
	 * Retrieve the widget name.
	 *
	 * @return string Widget name.
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 */
	public function get_name() {
		return 'wcf--header-preset';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @return string Widget title.
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 */
	public function get_title() {
		return wcf_elementor_widget_concat_prefix( 'Header' );
	}

	/**
	 * Retrieve the widget icon.
	 *
	 * @return string Widget icon.
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 */
	public function get_icon() {
		return 'wcf eicon-header';
	}
	
	public function get_all_images_urls($path='bars'){
		$returns_data = [];
		$media_dir = SASSLY_ESSENTIAL_DIR_PATH . "assets/images/{$path}/";
		$url_path = SASSLY_ESSENTIAL_ASSETS_URL ."images/{$path}/";
		try{
		
			if(defined('GLOB_BRACE')){			
				$imagesFiles = glob($media_dir."*.{jpg,jpeg,png,gif,svg,bmp,webp}",GLOB_BRACE);    
				foreach($imagesFiles as $key => $item)
				{
					$returns_data[basename($item)] =  [
						'title' => basename($item),
						'url' => $url_path . basename($item),                
					];
				} 
			}else{
			    if(function_exists('list_files')){
			    
					$files = list_files( $media_dir , 2 );       
					foreach ( $files as $file ) {
					
						if ( is_file( $file ) ) {				
							$filename = basename( $file ); 				
							$returns_data[$filename] =  [
								'title' => $filename,
								'url' => $url_path . $filename,                
							];
						}
						
					}
			    }
				
			}
		
		}catch (\Exception $e) {}
	
				
		return $returns_data;        
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
	 *
	 */
	public function get_categories() {
		return [ 'weal-coder-addon' ];
	}
	
	public function get_style_depends() {		
		return [ 'wcf--button' ];
	}
	
	public function get_script_depends() {
	    wp_register_script( 'wcf-header-preset', SASSLY_ESSENTIAL_ASSETS_URL. 'js/widgets/header-preset.js' , [ 'jquery','meanmenu' ], false, true );
		return [ 'wcf-header-preset' ];
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
				'label' => __( 'Settings', 'sassly-essential' ),
			]
		);
		
		$this->add_control(
			'layout_style',
			[
				'label' => esc_html__('Preset Layout', 'sassly-essential'),
				'type' => \SasslyEssentialApp\CustomControl\ImageSelector_Control::ImageSelector,
				'options' => $this->get_all_images_urls('header-preset'),
				'bgcolor' => '#1C1D20',
				'default' => 'wcf--header-2.png',
				'col' => 1
			]
		);

		$this->add_responsive_control(
			'header_type',
			[
				'label' => esc_html__( 'Header Type', 'sassly-essential' ),
				'type' => Controls_Manager::SELECT,
				'default' => '',
				'options' => [
					'' => esc_html__( 'Default', 'sassly-essential' ),
					'absolute'  => esc_html__( 'Absolute', 'sassly-essential' ),
				],
				'selectors' => [
					'{{WRAPPER}} .wcf-default-header-layout' => 'position: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'header_space_top',
			[
				'label' => esc_html__( 'Top', 'sassly-essential' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 200,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .wcf-default-header-layout' => 'top: {{SIZE}}{{UNIT}};',
				],
                'condition' => [
                        'header_type' => 'absolute',
                ],
			]
		);

		$this->add_control(
			'hamburger_icon_heading',
			[
				'label' => esc_html__( 'Hamburger Icon', 'sassly-essential' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'custom_bar',
			[
				'label'       => __( 'Custom Icon', 'sassly-essential' ),
				'type'        => Controls_Manager::SWITCHER,
				'yes'         => __( 'Yes', 'sassly-essential' ),
				'no'          => __( 'No', 'sassly-essential' ),
				'default'     => '',
				
			]
		);

		//Image selector
		$this->add_control(
			'bar',
			[
				'label' => esc_html__('Prest Icon', 'sassly-essential'),
				'type' => \SasslyEssentialApp\CustomControl\ImageSelector_Control::ImageSelector,
				'options' => $this->get_all_images_urls(),
				'bgcolor' => '#D2EAF1',
				'col' => 3,
				'default' => 'hamburger-icon-8.png',
				'condition' => [
					'custom_bar' => '',
				],
			]
		);

		$this->add_control(
			'custom_bar_image',
			[
				'label' => esc_html__( 'Choose Bar Image', 'sassly-essential' ),
				'type' => \Elementor\Controls_Manager::MEDIA,	
				'condition' => [
					'custom_bar' => 'yes',
				],
			]
		);
		
		$this->add_control(
			'custom_sticky_bar',
			[
				'label' => esc_html__( 'Sticky Header Bar', 'sassly-essential' ),
				'type' => \Elementor\Controls_Manager::MEDIA,
			]
		);

		$this->end_controls_section();

		// Logo
		$this->start_controls_section(
			'section_logo',
			[
				'label' => __( 'Logo', 'sassly-essential' ),
			]
		);

		$this->add_control(
			'header_logo',
			[
				'label' => esc_html__( 'Logo', 'sassly-essential' ),
				'type' => Controls_Manager::MEDIA,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
			]
		);
		
		$this->add_control(
			'sticky_logo',
			[
				'label' => esc_html__( 'Sticky Header Logo', 'sassly-essential' ),
				'type' => Controls_Manager::MEDIA,				
			]
		);
		
		$this->end_controls_section();

		// Menu
		$this->start_controls_section(
			'section_menu',
			[
				'label' => __( 'Menu', 'sassly-essential' ),
			]
		);
		
		$this->add_control(
			'menu_selected',
			[
					'label' => esc_html__( 'Menu', 'sassly-essential' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'default' => '',
					'options' => sassly_menu_list()
			]
		);

		$this->add_control(
			'custom_direction',
			[
				'label' => esc_html__( 'Custom Direction Icon', 'sassly-essential' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'sassly-essential' ),
				'label_off' => esc_html__( 'No', 'sassly-essential' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);
		
		$this->add_control(
			'menu_down_icon',
			[
				'label' => esc_html__( 'Nav Down Arrow Icon', 'sassly-essential' ),
				'type' => \Elementor\Controls_Manager::ICONS,
				'condition' => ['custom_direction'=>'yes']
			]
		);
		
		$this->add_control(
			'menu_right_icon',
			[
				'label' => esc_html__( 'Nav Right Arrow Icon', 'sassly-essential' ),
				'type' => \Elementor\Controls_Manager::ICONS,
				'condition' => ['custom_direction'=>'yes']
			]
		);
		
		$this->add_responsive_control(
			'icon_gap',
			[
				'label' => esc_html__( 'Icon Gap', 'sassly-essential' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', 'rem' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					],					
				],	
				'condition' => [ 'custom_direction' => '' ],
				'selectors' => [
					'{{WRAPPER}} .main-menu li.menu-item-has-children > a:after' => 'margin-inline-start: {{SIZE}}{{UNIT}};',					
				],
			]
		);
		
		$this->add_control(
			'icon_cusdown_padding',
			[
				'label' => esc_html__( 'Down Arrow margin', 'sassly-essential' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem' ],
				'condition' => ['custom_direction'=>'yes'],
				'selectors' => [
					'{{WRAPPER}} li.menu-item-has-children.dropdown > a svg' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} li.menu-item-has-children.dropdown > a i' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->add_control(
			'icon_cusr_padding',
			[
				'label' => esc_html__( 'Right Arrow margin', 'sassly-essential' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem' ],
				'condition' => ['custom_direction'=>'yes'],
				'selectors' => [
					'{{WRAPPER}} .dp-menu li.menu-item-has-children > a svg' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .dp-menu li.menu-item-has-children > a i' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();


		// More Actions
		$this->start_controls_section(
			'section_more_action',
			[
				'label' => esc_html__( 'More Actions', 'sassly-essential' ),
			]
		);

		$repeater_2 = new Repeater();

		$action_option = [
			'button' => esc_html__( 'Button', 'sassly-essential' ),
			'search' => esc_html__( 'Search', 'sassly-essential' ),
			'link' => esc_html__( 'Link', 'sassly-essential' ),
		];

		if ( class_exists( 'woocommerce' ) ) { 
			$action_option['cart'] = esc_html__( 'Cart', 'sassly-essential' );
		}

		$repeater_2->add_control(
			'action_type',
			[
				'label' => esc_html__( 'Action Type', 'sassly-essential' ),
				'type' => Controls_Manager::SELECT,
				'options' => $action_option,
			],
		);

		$this->register_button_controls( $repeater_2 );

		$this->register_searchIcon_controls( $repeater_2 );

		$this->register_link_controls( $repeater_2 );

		$this->register_cart_controls( $repeater_2 );

		$repeater_2->add_control(
			'separator',
			[
				'label' => esc_html__( 'Separator?', 'sassly-essential' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'sassly-essential' ),
				'label_off' => esc_html__( 'No', 'sassly-essential' ),
				'return_value' => 'yes',
				'default' => '',
			]
		);


		$this->add_control(
			'action',
			[
				'label' => esc_html__( 'More Actions', 'sassly-essential' ),
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater_2->get_controls(),
				'title_field' => '{{{ action_type }}}',
			]
		);

		$this->end_controls_section();


		// Language
		$this->start_controls_section(
			'section_language',
			[
				'label' => esc_html__( 'Language', 'sassly-essential' ),
			]
		);

		$this->register_language_controls(); 

		$this->end_controls_section();


		// Offcanvas
		$this->start_controls_section(
			'section_offcanvas_content',
			[
				'label' => __( 'Offcanvas Settings', 'sassly-essential' ),
			]
		);
		
		$this->add_control(
			'max-width',
			[
				'label' => esc_html__( 'Max Width', 'sassly-essential' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1600,
						'step' => 5,
					],					
				],
				'default' => [
					'unit' => 'px',
					'size' => 1024,
				],
				
			]
		);

		$this->add_control(
			'hamburger_icon_switch',
			[
				'label' => esc_html__( 'Hamburger Icon', 'sassly-essential' ),
				'type' => Controls_Manager::SELECT,
				'render_type'=> 'template',
				'default' => 'block',
				'options' => [
					'block' => esc_html__( 'Always Show', 'sassly-essential' ),
					'none' => esc_html__( 'Hide', 'sassly-essential' ),
				],
				'selectors' => [
					'{{WRAPPER}} .wcf-header--offcanvas--icon' => 'display: {{VALUE}};',
				],
                'frontend_available' => true,
			]
		);

		$this->add_control(
			'content_source',
			[
				'label' => esc_html__( 'Content Source', 'sassly-essential' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'theme',
				'options' => [
					'theme' => esc_html__( 'Theme' , 'sassly-essential' ),
					'self'  => esc_html__( 'Custom' , 'sassly-essential' ),							
				]
				
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_custom_content',
			[
				'label' => esc_html__( 'Custom Content', 'sassly-essential' ),
				'condition' => [
						'content_source' => 'self',
				],
			]
		);
				
		$this->add_control(
			'logo',
			[
				'label' => esc_html__( 'Choose Logo', 'sassly-essential' ),
				'type' => \Elementor\Controls_Manager::MEDIA,					
			]
		);
	
		$this->add_control(
			'description',
			[
				'label' => esc_html__( 'Description', 'sassly-essential' ),
				'type' => \Elementor\Controls_Manager::TEXTAREA,
				'rows' => 10,
				'default' => esc_html__( 'Default description', 'sassly-essential' ),
				'placeholder' => esc_html__( 'Type your description here', 'sassly-essential' ),
			]
		);
	
		$this->add_control(
			'gal_title',
			[
				'label' => esc_html__( 'Gallery Title', 'sassly-essential' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'Cart', 'sassly-essential' ),
				'placeholder' => esc_html__( 'Type your cart text here', 'sassly-essential' ),
			]
		);
		
		$repeater = new \Elementor\Repeater();

		$repeater->add_control(
			'title',
			[
				'label' => esc_html__( 'Text', 'elementor-list-widget' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'List Item', 'elementor-list-widget' ),
				'default' => esc_html__( 'List Item', 'elementor-list-widget' ),
				'label_block' => true,
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$repeater->add_control(
			'image',
			[
				'label' => esc_html__( 'Link', 'elementor-list-widget' ),
				'type' => \Elementor\Controls_Manager::MEDIA,
				'placeholder' => esc_html__( 'https://your-link.com', 'elementor-list-widget' ),				
			]
		);

		/* End repeater */

		$this->add_control(
			'gallery_list',
			[
				'label' => esc_html__( 'Gallery Items', 'elementor-list-widget' ),
				'type' => \Elementor\Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),				
				'title_field' => '{{{ title }}}',
			]
		);
	
		$this->add_control(
			'social_title',
			[
				'label' => esc_html__( 'Social Title', 'sassly-essential' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'Social', 'sassly-essential' ),
				'placeholder' => esc_html__( 'Type your social text here', 'sassly-essential' ),
			]
		);
	
		$repeater = new \Elementor\Repeater();

		$repeater->add_control(
			'title',
			[
				'label' => esc_html__( 'Text', 'sassly-essential' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'List Item', 'sassly-essential' ),
				'default' => esc_html__( 'List Item', 'sassly-essential' ),
				'label_block' => true,
				'dynamic' => [
					'active' => true,
				],
			]
		);
	
		$repeater->add_control(
			'website_link',
			[
				'label' => esc_html__( 'Link', 'sassly-essential' ),
				'type' => \Elementor\Controls_Manager::URL,
				'options' => [ 'url', 'is_external', 'nofollow' ],
				'default' => [
					'url' => '',
					'is_external' => true,
					'nofollow' => true,
					// 'custom_attributes' => '',
				],
				'label_block' => true,
			]
		);
	
		$repeater->add_control(
			'icon',
			[
				'label' => esc_html__( 'Icon', 'sassly-essential' ),
				'type' => \Elementor\Controls_Manager::ICONS,
				'default' => [
					'value' => 'fas fa-circle',
					'library' => 'fa-solid',
				],				
			]
		);
		/* End repeater */

		$this->add_control(
			'social_list',
			[
				'label' => esc_html__( 'Social Items', 'sassly-essential' ),
				'type' => \Elementor\Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),				
				'title_field' => '{{{ title }}}',
			]
		);
		
		$this->end_controls_section();


		// Layout Style
		$this->start_controls_section(
			'section_style_layout',
			[
				'label' => esc_html__( 'Layout', 'sassly-essential' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'layout_bg',
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .wcf-default-header-layout, {{WRAPPER}} .header__lang select, {{WRAPPER}} .info--search, {{WRAPPER}} .wcf--header-5 .header__inner',
			]
		);

		$this->add_responsive_control(
			'layout_item_gap',
			[
				'label' => esc_html__( 'Gap', 'sassly-essential' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 300,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .header__inner' => 'gap: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .header__logo::after' => '--gap: -{{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .header__others::before' => '--gap: -{{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'layout_padding',
			[
				'label' => esc_html__( 'Padding', 'sassly-essential' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .wcf-default-header-layout' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'shape_image_color',
			[
				'label' => esc_html__( 'Border Color', 'sassly-essential' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .shape-img svg' => 'color: {{VALUE}}; fill: {{VALUE}};',
					'{{WRAPPER}} .header__logo::after' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .item::after' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .header__others::before' => 'background-color: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'hide_right_border',
			[
				'label' => esc_html__( 'Right Border', 'sassly-essential' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'block',
				'options' => [
					'block' => esc_html__( 'Enable', 'sassly-essential' ),
					'none' => esc_html__( 'Disabled', 'sassly-essential' ),
				],
				'selectors' => [
					'{{WRAPPER}} .header__others .item::after' => 'display: {{VALUE}};',
					'{{WRAPPER}} .header__others::before' => 'display: {{VALUE}};',
					'{{WRAPPER}} .header__others .shape-img' => 'display: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'hide_left_border',
			[
				'label' => esc_html__( 'Left Border', 'sassly-essential' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'block',
				'options' => [
					'block' => esc_html__( 'Enable', 'sassly-essential' ),
					'none' => esc_html__( 'Disabled', 'sassly-essential' ),
				],
				'selectors' => [
					'{{WRAPPER}} .header__logo::after' => 'display: {{VALUE}};',
					'{{WRAPPER}} .header__logo .shape-img' => 'display: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'header_heading',
			[
				'label' => esc_html__( 'Header', 'sassly-essential' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'header_padding',
			[
				'label' => esc_html__( 'Padding', 'sassly-essential' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .wcf-default-header-layout' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'header_border',
				'selector' => '{{WRAPPER}} .wcf-default-header-layout',
			]
		);

		$this->add_control(
			'header_container_heading',
			[
				'label' => esc_html__( 'Header Container', 'sassly-essential' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'container_padding',
			[
				'label' => esc_html__( 'Padding', 'sassly-essential' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .header__inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'container_border',
				'selector' => '{{WRAPPER}} .header__inner',
			]
		);

		$this->add_responsive_control(
			'action_items_gap',
			[
				'label' => esc_html__( 'More Actions Gap', 'sassly-essential' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 300,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .header__others, {{WRAPPER}} .more-actions' => 'gap: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .header__others .item::after' => '--gap: -{{SIZE}}{{UNIT}};',
				],
                'separator' => 'before',
			]
		);

		$this->end_controls_section();


		// Logo Style
		$this->start_controls_section(
			'section_style_logo',
			[
				'label' => esc_html__( 'Logo', 'sassly-essential' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'logo_width',
			[
				'label' => esc_html__( 'Width', 'sassly-essential' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 300,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .header__logo img' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'logo_height',
			[
				'label' => esc_html__( 'Height', 'sassly-essential' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 300,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .header__logo img' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();


		// Menu Style
		$this->start_controls_section(
			'section_style_menu',
			[
				'label' => esc_html__( 'Menu', 'sassly-essential' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'menu_align',
			[
				'label' => esc_html__( 'Alignment', 'sassly-essential' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'start' => [
						'title' => esc_html__( 'Left', 'sassly-essential' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'sassly-essential' ),
						'icon' => 'eicon-text-align-center',
					],
					'end' => [
						'title' => esc_html__( 'Right', 'sassly-essential' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'default' => 'center',
				'toggle' => false,
				'prefix_class' => 'info--menu-alignment-',
				'selectors' => [
					'{{WRAPPER}} .wcf--elementor--menu' => 'justify-content: {{VALUE}};',
				],

			]
		);
		
		$this->start_controls_tabs( 'headermenu' );

		$this->start_controls_tab( 'normal',
			[
				'label' => esc_html__( 'Normal', 'sassly-essential' ),
			]
		);
		
			$this->add_control(
				'header_nav_list_color',
				[
					'label' => esc_html__( 'Color', 'sassly-essential' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .wcf-default-header-layout ul li a' => 'color: {{VALUE}};fill: {{VALUE}}',
						'{{WRAPPER}} .wcf-default-header-layout ul li' => 'color: {{VALUE}};fill: {{VALUE}}',
					],
				]
			);
			
			$this->add_group_control(
				\Elementor\Group_Control_Typography::get_type(),
				[
					'name' => 'header_nav_list_typo',
					'selector' => '{{WRAPPER}} .wcf-default-header-layout ul li a, {{WRAPPER}} .wcf-default-header-layout ul li',
				]
			);	
			
			
			$this->add_responsive_control(
				'menu_mpading',
				[
					'label' => esc_html__( 'item Padding', 'sassly-essential' ),
					'type' => \Elementor\Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', 'em', 'rem' ],
					'selectors' => [
						'{{WRAPPER}} .wcf-default-header-layout ul > li a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			
			$this->add_group_control(
				\Elementor\Group_Control_Background::get_type(),
				[
					'name' => 'menubackground',
					'types' => [ 'classic', 'gradient'],
					'selector' => '{{WRAPPER}} .wcf-default-header-layout nav > ul',
				]
			);
	
		$this->end_controls_tab();

		$this->start_controls_tab( 'hover',
			[
				'label' => esc_html__( 'Hover', 'sassly-essential' ),
			]
		);

		$this->add_control(
			'header_nav_list_hover_color',
			[
				'label' => esc_html__( 'Color', 'sassly-essential' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wcf-default-header-layout ul li a:hover' => 'color: {{VALUE}};fill: {{VALUE}}',
					'{{WRAPPER}} .wcf-default-header-layout ul li.active a' => 'color: {{VALUE}};fill: {{VALUE}}',
				],
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'header_nav_list_htypo',
				'selector' => '{{WRAPPER}} .wcf-default-header-layout ul li a:hover',
			]
		);	
		
		$this->end_controls_tab();

		$this->end_controls_tabs();
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_style_ddown_container',
			[
				'label' => esc_html__( 'Dropdown Container', 'sassly-essential' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);	
		
		$this->add_responsive_control(
			'dropdown_width',
			[
				'label' => esc_html__( 'Width', 'sassly-essential' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', 'rem' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1200,
						'step' => 1,
					],					
				],				
				'selectors' => [
					'{{WRAPPER}} .wcf-default-header-layout .dp-menu' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		
		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'd_menubackground',
				'types' => [ 'classic', 'gradient'],
				'selector' => '{{WRAPPER}} .wcf-default-header-layout ul.dp-menu',
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'dropdown_border',
				'selector' => '{{WRAPPER}} .wcf-default-header-layout ul.dp-menu',
			]
		);
		
		$this->add_responsive_control(
			'dmencontainer_mpading',
			[
				'label' => esc_html__( 'Padding', 'sassly-essential' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', 'rem' ],
				'selectors' => [
					'{{WRAPPER}} .wcf-default-header-layout ul.dp-menu' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->end_controls_section();
		
		/** Dropdown menu */
		
		$this->start_controls_section(
			'section_style_ddown_menu',
			[
				'label' => esc_html__( 'Dropdown', 'sassly-essential' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);	
		
		$this->start_controls_tabs( 'headerdmenu' );

		$this->start_controls_tab( 'dnormal',
			[
				'label' => esc_html__( 'Normal', 'sassly-essential' ),
			]
		);
		
        $this->add_control(
            'dheader_nav_list_color',
            [
                'label' => esc_html__( 'Color', 'sassly-essential' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wcf-default-header-layout ul.dp-menu li a' => 'color: {{VALUE}};fill: {{VALUE}}',
                    '{{WRAPPER}} .wcf-default-header-layout ul.dp-menu li' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'dheader_nav_list_typo',
                'selector' => '{{WRAPPER}} .wcf-default-header-layout ul.dp-menu li a, {{WRAPPER}} .wcf-default-header-layout ul.dp-menu li',
            ]
        );

        $this->add_responsive_control(
            'dicon_gap',
            [
                'label' => esc_html__( 'Icon Gap', 'sassly-essential' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em', 'rem' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .wcf-default-header-layout .dp-menu li.menu-item-has-children > a:after' => 'margin-inline-start: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'dmenu_mpading',
            [
                'label' => esc_html__( 'item Padding', 'sassly-essential' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', 'rem' ],
                'selectors' => [
                    '{{WRAPPER}} .wcf-default-header-layout ul.dp-menu li a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

		$this->end_controls_tab();

		$this->start_controls_tab( 'dhover',
			[
				'label' => esc_html__( 'Hover', 'sassly-essential' ),
			]
		);

		$this->add_control(
			'dheader_nav_list_hover_color',
			[
				'label' => esc_html__( 'Color', 'sassly-essential' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wcf-default-header-layout ul.dp-menu li a:hover' => 'color: {{VALUE}}',
				],
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'dheader_nav_list_htypo',
				'selector' => '{{WRAPPER}} .wcf-default-header-layout ul li a:hover',
			]
		);	
		
		$this->end_controls_tab();

		$this->end_controls_tabs();
		
		$this->end_controls_section();
			
		/** Dropdown menu */
		
		$this->start_controls_section(
				'section_style_custom_icon',
				[
					'label' => esc_html__( 'Menu Arrow Icon', 'sassly-essential' ),
					'tab'   => Controls_Manager::TAB_STYLE,
				]
			);	
				
        $this->start_controls_tabs( 'arrowicon' );

        $this->start_controls_tab( 'arrownormal',
            [
                'label' => esc_html__( 'Normal', 'sassly-essential' ),
            ]
        );

        $this->add_control(
            'icon_list_color',
            [
                'label' => esc_html__( 'Color', 'sassly-essential' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wcf-default-header-layout ul li a i' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .wcf-default-header-layout ul li a svg' => 'fill: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'icon_list_typo',
                'selector' => '{{WRAPPER}} .wcf-default-header-layout ul li a i, {{WRAPPER}} .wcf-default-header-layout ul li a svg',
            ]
        );
        $this->end_controls_tab();

        $this->start_controls_tab( 'iconhover',
            [
                'label' => esc_html__( 'Hover', 'sassly-essential' ),
            ]
        );

        $this->add_control(
            'icon_list_hover_color',
            [
                'label' => esc_html__( 'Color', 'sassly-essential' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wcf-default-header-layout ul li a:hover i' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .wcf-default-header-layout ul li a:hover svg' => 'fill: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'icon_nav_list_htypo',
                'selector' => '{{WRAPPER}} .wcf-default-header-layout ul li a:hover i',
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();
				
		$this->end_controls_section();

		// Hamburger Icon
		$this->start_controls_section(
			'section_style_hamburger_icon',
			[
				'label' => esc_html__( 'Hamburger Icon', 'sassly-essential' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'hamburger_width',
			[
				'label' => esc_html__( 'Width', 'sassly-essential' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .wcf-header--offcanvas--icon img' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'hamburger_size',
			[
				'label' => esc_html__( 'Background Size', 'sassly-essential' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 300,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .wcf-header--offcanvas--icon' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'hamburger_padding',
			[
				'label' => esc_html__( 'Padding', 'sassly-essential' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .wcf-header--offcanvas--icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'hamburger_radius',
			[
				'label' => esc_html__( 'Border Radius', 'sassly-essential' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .wcf-header--offcanvas--icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->start_controls_tabs(
			'hamburger_style_tabs'
		);

		$this->start_controls_tab(
			'hamburger_normal_tab',
			[
				'label' => esc_html__( 'Normal', 'sassly-essential' ),
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'hamburger_normal_bg',
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .wcf-header--offcanvas--icon',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'hamburger_hover_tab',
			[
				'label' => esc_html__( 'Hover', 'sassly-essential' ),
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'hamburger_hover_bg',
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .wcf-header--offcanvas--icon:hover',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		
		$this->start_controls_section(
			'section_style_offcanvas_icon',
			[
				'label' => esc_html__( 'Offcanvas', 'sassly-essential' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);	
		
		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'd_offcanvasckground',
				'types' => [ 'classic', 'gradient'],
				'selector' => '{{WRAPPER}} .offcanvas__area .offcanvas',
			]
		);
		
		$this->add_responsive_control(
			'md_offcanvayu_padding',
			[
				'label' => esc_html__( 'Padding', 'sassly-essential' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .offcanvas__area .offcanvas' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				],
			]
		);
		// offcanvas__close
		
		$this->add_control(
			'offclose_icon_color',
			[
				'label' => esc_html__( 'Close Icon Color', 'sassly-essential' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .offcanvas__area .offcanvas__close' => 'color: {{VALUE}} !important;fill: {{VALUE}} !important;',					
				],
			]
		);
		$this->end_controls_section();

		// Mobile Menu
		$this->start_controls_section(
			'section_mobile_menu',
			[
				'label' => esc_html__( 'Mobile Menu', 'sassly-essential' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		
		$this->add_responsive_control(
			'mobile_menu_container_margin',
			[
				'label' => esc_html__( 'Container Margin', 'sassly-essential' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .offcanvas__menu-area' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				],
			]
		);

		$this->add_responsive_control(
			'mobile_text_padding',
			[
				'label' => esc_html__( 'Padding', 'sassly-essential' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .mean-container .mean-nav ul li a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				],
			]
		);

		$this->add_control(
			'mobile_text_color',
			[
				'label' => esc_html__( 'Text Color', 'sassly-essential' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mean-container .mean-nav ul li a' => 'color: {{VALUE}} !important;',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'mobile_text_typo',
				'selector' => '{{WRAPPER}} .mean-container .mean-nav ul li a',
			]
		);

		$this->add_control(
			'mobile_border_color',
			[
				'label' => esc_html__( 'Border Color', 'sassly-essential' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mean-container .mean-nav ul li a, {{WRAPPER}} .mean-container .mean-nav ul li li:first-child, {{WRAPPER}} .mean-container .mean-nav ul li li li:last-child' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'mobile_sub_menu_heading',
			[
				'label' => esc_html__( 'Sub Menu', 'sassly-essential' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'mobile_sub_text_padding',
			[
				'label' => esc_html__( 'Padding', 'sassly-essential' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .mean-container .mean-nav ul li li a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				],
			]
		);

		$this->add_control(
			'mobile_3rd_menu_heading',
			[
				'label' => esc_html__( '3rd Step Sub Menu', 'sassly-essential' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'mobile_3rd_sub_text_padding',
			[
				'label' => esc_html__( 'Padding', 'sassly-essential' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .mean-container .mean-nav ul li li li a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				],
			]
		);

		$this->add_control(
			'mobile_menu_arrow_heading',
			[
				'label' => esc_html__( 'Arrow Icon', 'sassly-essential' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'mobile_icon_position',
			[
				'label' => esc_html__( 'Icon Position', 'sassly-essential' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 200,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .mean-container .mean-nav ul li a.mean-expand' => 'height: {{SIZE}}{{UNIT}} !important;',
				],
			]
		);

		$this->add_responsive_control(
			'mobile_3rd_icon_position',
			[
				'label' => esc_html__( '2nd Step Icon Position', 'sassly-essential' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 200,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .mean-container .mean-nav ul li li a.mean-expand' => 'height: {{SIZE}}{{UNIT}} !important;',
				],
			]
		);

		$this->add_responsive_control(
			'mobile_icon_size',
			[
				'label' => esc_html__( 'Size', 'sassly-essential' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .mean-container .mean-nav ul li a.mean-expand' => 'font-size: {{SIZE}}{{UNIT}} !important;',
				],
			]
		);

		$this->add_control(
			'mobile_icon_color',
			[
				'label' => esc_html__( 'Color', 'sassly-essential' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mean-container .mean-nav ul li a.mean-expand' => 'color: {{VALUE}} !important;',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'mobile_icon_bg',
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .mean-container .mean-nav ul li a.mean-expand',
			]
		);
		$this->end_controls_section();

		// Link Style
		$this->register_link_styles();

		// Cart Style
        $this->register_cart_styles();

        // Button Style
        $this->register_button_styles();

        // Language Style
        $this->register_language_styles();

        // Language Style
        $this->register_search_styles();

	}


	// Button Controls
	protected function register_button_controls( $repeater_2 ){
		$repeater_2->add_control(
			'btn_hover_list',
			[
				'label'   => esc_html__( 'Hover Style', 'SASSLY_ESSENTIAL' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'hover-none',
				'options' => [
					'hover-none'      => esc_html__( 'None', 'SASSLY_ESSENTIAL' ),
					'hover-divide'    => esc_html__( 'Divided', 'SASSLY_ESSENTIAL' ),
					'hover-cross'     => esc_html__( 'Cross', 'SASSLY_ESSENTIAL' ),
					'hover-cropping'  => esc_html__( 'Cropping', 'SASSLY_ESSENTIAL' ),
					'rollover-top'    => esc_html__( 'Rollover Top', 'SASSLY_ESSENTIAL' ),
					'rollover-left'   => esc_html__( 'Rollover Left', 'SASSLY_ESSENTIAL' ),
					'parallal-border' => esc_html__( 'Parallel Border', 'SASSLY_ESSENTIAL' ),
					'rollover-cross'  => esc_html__( 'Rollover Cross', 'SASSLY_ESSENTIAL' ),
				],
				'condition' => ['action_type' => 'button'],
			]
		);

		$repeater_2->add_control(
			'btn_text',
			[
				'label' => esc_html__( 'Label', 'sassly-essential' ),
				'type' => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Type your label', 'sassly-essential' ),
				'default' => esc_html__( 'Button', 'sassly-essential' ),
				'condition' => ['action_type' => 'button'],
			]
		);

		$repeater_2->add_control(
			'btn_link',
			[
				'label' => esc_html__( 'Link', 'sassly-essential' ),
				'type' => Controls_Manager::URL,
				'options' => [ 'url', 'is_external', 'nofollow' ],
				'default' => [
					'url' => '',
					'is_external' => true,
					'nofollow' => true,
				],
				'label_block' => true,
				'condition' => ['action_type' => 'button'],
			]
		);

		$repeater_2->add_control(
			'btn_icon',
			[
				'label' => esc_html__( 'Icon', 'sassly-essential' ),
				'type' => Controls_Manager::ICONS,
				'condition' => ['action_type' => 'button'],
			],
		);

        $repeater_2->add_control(
	        'icon_before',
	        [
		        'label' => esc_html__( 'Icon Before?', 'sassly-essential' ),
		        'type' => Controls_Manager::SWITCHER,
		        'label_on' => esc_html__( 'Yes', 'sassly-essential' ),
		        'label_off' => esc_html__( 'No', 'sassly-essential' ),
		        'return_value' => 'yes',
		        'default' => '',
		        'condition' => ['action_type' => ['button', 'link']],
	        ]
        );
	}

	protected function register_button_styles(){
		$this->start_controls_section(
			'section_btn_style',
			[
				'label' => esc_html__( 'Button', 'sassly-essential' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'button_switch',
			[
				'label' => esc_html__( 'Show/Hide', 'textdomain' ),
				'type' => Controls_Manager::SELECT,
				'default' => '',
				'options' => [
					'flex' => esc_html__( 'Show', 'textdomain' ),
					'none' => esc_html__( 'Hide', 'textdomain' ),
				],
				'selectors' => [
					'{{WRAPPER}} .more-actions .item.button' => 'display: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'btn_typography',
				'selector' => '{{WRAPPER}} .wcf-default-header-layout .item.button a',
			]
		);

		$this->add_responsive_control(
			'btn_icon_size',
			[
				'label' => esc_html__( 'Icon Size', 'sassly-essential' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 200,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .wcf-default-header-layout .item.button a i' => 'font-size: {{SIZE}}{{UNIT}};',
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
					'{{WRAPPER}} .wcf-default-header-layout .item.button a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'btn_border',
				'selector' => '{{WRAPPER}} .wcf-default-header-layout .item.button a',
			]
		);

		$this->add_responsive_control(
			'btn_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'sassly-essential' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .wcf-default-header-layout .item.button a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'btn_box_shadow',
				'selector' => '{{WRAPPER}} .more-actions .button a',
			]
		);

		$this->start_controls_tabs(
			'btn_style_tabs'
		);

		$this->start_controls_tab(
			'btn_style_normal_tab',
			[
				'label' => esc_html__( 'Normal', 'sassly-essential' ),
			]
		);

		$this->add_control(
			'btn_text_color',
			[
				'label' => esc_html__( 'Color', 'sassly-essential' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wcf-default-header-layout .item.button a' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'btn_bg',
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .item.button a, {{WRAPPER}} .item.button a:not(.wcf-btn-ellipse), {{WRAPPER}} .item.button a.wcf-btn-mask:after, {{WRAPPER}} .item.button a.wcf-btn-ellipse:before',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'btn_style_hover_tab',
			[
				'label' => esc_html__( 'Hover', 'sassly-essential' ),
			]
		);

		$this->add_control(
			'btn_hover_color',
			[
				'label' => esc_html__( 'Color', 'sassly-essential' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wcf-default-header-layout .item.button a:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'btn_hover_bg',
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} a:not(.btn-item, .btn-parallal-border, .btn-rollover-cross, .wcf-btn-ellipse):after, {{WRAPPER}} .btn-hover-bgchange span, {{WRAPPER}} .btn-rollover-cross:hover, {{WRAPPER}} .btn-parallal-border:hover, {{WRAPPER}} a.wcf-btn-ellipse:hover:before,{{WRAPPER}} a.btn-hover-none:hover',
			]
		);

		$this->add_control(
			'btn_border_hover_color',
			[
				'label' => esc_html__( 'Border Color', 'sassly-essential' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wcf-default-header-layout .item.button a:hover' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} a:hover, {{WRAPPER}} a:focus, {{WRAPPER}} a:hover.btn-parallal-border:before, {{WRAPPER}} a:hover.btn-parallal-border:after, {{WRAPPER}} a:hover.btn-rollover-cross:before, {{WRAPPER}} a:hover.btn-rollover-cross:after, {{WRAPPER}} a.btn-hover-none:hover' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'btn_hover_box_shadow',
				'selector' => '{{WRAPPER}} .more-actions .button a:hover',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}


	// Search Icon Controls
	protected function register_searchIcon_controls( $repeater_2 ){
		$repeater_2->add_control(
			'search_placeholder',
			[
				'label' => esc_html__( 'Placeholder Text', 'sassly-essential' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Search...', 'sassly-essential' ),
				'condition' => ['action_type' => 'search'],
			]
		);

		$repeater_2->add_control(
			'search_icon',
			[
				'label' => esc_html__( 'Icon', 'sassly-essential' ),
				'type' => Controls_Manager::ICONS,
				'condition' => ['action_type' => 'search'],
			],
		);
	}

	protected function register_search_styles(){
		$this->start_controls_section(
			'section_search_style',
			[
				'label' => esc_html__( 'Search', 'sassly-essential' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'search_switch',
			[
				'label' => esc_html__( 'Show/Hide', 'sassly-essential' ),
				'type' => Controls_Manager::SELECT,
				'default' => '',
				'options' => [
					'flex' => esc_html__( 'Show', 'sassly-essential' ),
					'none' => esc_html__( 'Hide', 'sassly-essential' ),
				],
				'selectors' => [
					'{{WRAPPER}} .more-actions .item.search' => 'display: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'search_icon_size',
			[
				'label' => esc_html__( 'Size', 'sassly-essential' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 200,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .search-icon' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .search-close' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'sinput_border',
				'selector' => '{{WRAPPER}} .info--search input',
			]
		);

		$this->start_controls_tabs(
			'search_style_tabs'
		);

		$this->start_controls_tab(
			'search_style_normal_tab',
			[
				'label' => esc_html__( 'Normal', 'sassly-essential' ),
			]
		);

		$this->add_control(
			'search_icon_color',
			[
				'label' => esc_html__( 'Color', 'sassly-essential' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .search-icon' => 'color: {{VALUE}}; fill: {{VALUE}}',
                    '{{WRAPPER}} .search-close' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .info--search-btn' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .info--search input' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .info--search input::-webkit-input-placeholder' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .info--search input::-moz-placeholder' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .info--search input::-ms-input-placeholder' => 'color: {{VALUE}}',
				],
			]
		);
		$this->end_controls_tab();

		$this->start_controls_tab(
			'search_style_hover_tab',
			[
				'label' => esc_html__( 'Hover', 'sassly-essential' ),
			]
		);

		$this->add_control(
			'search_icon_hover_color',
			[
				'label' => esc_html__( 'Color', 'sassly-essential' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .search-icon:hover' => 'color: {{VALUE}}',
					'{{WRAPPER}} .search-close:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();
		$this->end_controls_section();
	}

	// Link Controls
	protected function register_link_controls( $repeater_2 ){
		$repeater_2->add_control(
			'link_label',
			[
				'label' => esc_html__( 'Label', 'sassly-essential' ),
				'type' => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Type your label', 'sassly-essential' ),
				'condition' => ['action_type' => 'link'],
			]
		);

		$repeater_2->add_control(
			'link_link',
			[
				'label' => esc_html__( 'Link', 'sassly-essential' ),
				'type' => Controls_Manager::URL,
				'options' => [ 'url', 'is_external', 'nofollow' ],
				'default' => [
					'url' => '',
					'is_external' => false,
					'nofollow' => false,
				],
				'label_block' => true,
				'condition' => ['action_type' => 'link'],
			]
		);

		$repeater_2->add_control(
			'link_icon',
			[
				'label' => esc_html__( 'Icon', 'sassly-essential' ),
				'type' => Controls_Manager::ICONS,
				'condition' => ['action_type' => 'link'],
			],
		);
	}

	protected function register_link_styles(){
		$this->start_controls_section(
			'section_link_style',
			[
				'label' => esc_html__( 'Link', 'sassly-essential' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'link_switch',
			[
				'label' => esc_html__( 'Show/Hide', 'textdomain' ),
				'type' => Controls_Manager::SELECT,
				'default' => '',
				'options' => [
					'flex' => esc_html__( 'Show', 'textdomain' ),
					'none' => esc_html__( 'Hide', 'textdomain' ),
				],
				'selectors' => [
					'{{WRAPPER}} .more-actions .item.link' => 'display: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'link_typography',
				'selector' => '{{WRAPPER}} .wcf-default-header-layout .item.link a',
			]
		);

		$this->add_control(
			'link_icon_size',
			[
				'label' => esc_html__( 'Icon Size', 'sassly-essential' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 200,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .wcf-default-header-layout .item.link a .icon' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->start_controls_tabs(
			'link_style_tabs'
		);

		$this->start_controls_tab(
			'link_style_normal_tab',
			[
				'label' => esc_html__( 'Normal', 'sassly-essential' ),
			]
		);

		$this->add_control(
			'link_text_color',
			[
				'label' => esc_html__( 'Color', 'sassly-essential' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wcf-default-header-layout .item.link a' => 'color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'link_style_hover_tab',
			[
				'label' => esc_html__( 'Hover', 'sassly-essential' ),
			]
		);

		$this->add_control(
			'link_text_hover_color',
			[
				'label' => esc_html__( 'Color', 'sassly-essential' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wcf-default-header-layout .item.link a:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}


	// Cart Controls
	protected function register_cart_controls( $repeater_2 ){

		$repeater_2->add_control(
			'cart_text',
			[
				'label' => esc_html__( 'Label', 'sassly-essential' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Cart', 'sassly-essential' ),
				'placeholder' => esc_html__( 'Type your cart text here', 'sassly-essential' ),
				'condition' => ['action_type' => 'cart'],
			]
		);		
	
		$repeater_2->add_control(
			'cart_icon',
			[
				'label' => esc_html__( 'Icon', 'sassly-essential' ),
				'type' => Controls_Manager::ICONS,
				'default' => [
					'value' => 'fa-solid fa-cart-shopping',
					'library' => 'fa-solid',
				],
				'condition' => ['action_type' => 'cart'],
			]
		);
	
		$repeater_2->add_control(
			'cart_link',
			[
				'label' => esc_html__( 'Link', 'sassly-essential' ),
				'type' => Controls_Manager::URL,					
				'label_block' => true,
				'condition' => ['action_type' => 'cart'],
			]
		);
	}

	protected function register_cart_styles(){
		$this->start_controls_section(
			'section_cart_style',
			[
				'label' => esc_html__( 'Cart', 'sassly-essential' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'cart_switch',
			[
				'label' => esc_html__( 'Show/Hide', 'textdomain' ),
				'type' => Controls_Manager::SELECT,
				'default' => '',
				'options' => [
					'flex' => esc_html__( 'Show', 'textdomain' ),
					'none' => esc_html__( 'Hide', 'textdomain' ),
				],
				'selectors' => [
					'{{WRAPPER}} .more-actions .item.cart' => 'display: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'cart_type',
			[
				'label' => esc_html__( 'Cart Type', 'sassly-essential' ),
				'type' => Controls_Manager::SELECT,
				'default' => '',
				'options' => [
					'' => esc_html__( 'Default', 'sassly-essential' ),
					'absolute'  => esc_html__( 'Absolute', 'sassly-essential' ),
				],
				'selectors' => [
					'{{WRAPPER}} .wcf-wc-cart-fragment-content' => 'position: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'cart_position_top',
			[
				'label' => esc_html__( 'Top', 'sassly-essential' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => -100,
						'max' => 100,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .wcf-wc-cart-fragment-content' => 'top: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'cart_type' => 'absolute',
				],
			]
		);

		$this->add_responsive_control(
			'cart_position_right',
			[
				'label' => esc_html__( 'Right', 'sassly-essential' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => -100,
						'max' => 100,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .wcf-wc-cart-fragment-content' => 'inset-inline-end: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'cart_type' => 'absolute',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'cart_typography',
				'selector' => '{{WRAPPER}} .wcf-default-header-layout .item.cart a',
			]
		);

		$this->add_control(
			'cart_icon_size',
			[
				'label' => esc_html__( 'Icon Size', 'sassly-essential' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 200,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .wcf-default-header-layout .item.cart a i' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'cart_padding',
			[
				'label' => esc_html__( 'Padding', 'sassly-essential' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .item.cart a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'cart_border',
				'selector' => '{{WRAPPER}} .item.cart a',
			]
		);

		$this->add_control(
			'cart_radius',
			[
				'label' => esc_html__( 'Border Radius', 'sassly-essential' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .item.cart a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->start_controls_tabs(
			'cart_style_tabs'
		);

		$this->start_controls_tab(
			'cart_style_normal_tab',
			[
				'label' => esc_html__( 'Normal', 'sassly-essential' ),
			]
		);

		$this->add_control(
			'cart_text_color',
			[
				'label' => esc_html__( 'Color', 'sassly-essential' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .item.cart a' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'cart_count_color',
			[
				'label' => esc_html__( 'Count Color', 'sassly-essential' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .item.cart .wcf-wc-cart-fragment-content' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'cart_count_bg',
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .item.cart .wcf-wc-cart-fragment-content',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'cart_style_hover_tab',
			[
				'label' => esc_html__( 'Hover', 'sassly-essential' ),
			]
		);

		$this->add_control(
			'cart_text_hover_color',
			[
				'label' => esc_html__( 'Color', 'sassly-essential' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .item.cart a:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'cart_count_hover_color',
			[
				'label' => esc_html__( 'Count Color', 'sassly-essential' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .item.cart a:hover .wcf-wc-cart-fragment-content' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'cart_hover_border_color',
			[
				'label' => esc_html__( 'Border Color', 'sassly-essential' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .item.cart a:hover' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'cart_count_hover_bg',
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .item.cart a:hover .wcf-wc-cart-fragment-content',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}
	
	
	// Language Controls
	protected function register_language_controls(){
        
		$repeater_lang = new Repeater();

		$repeater_lang->add_control(
			'language',
			[
				'label' => esc_html__( 'Language', 'sassly-essential' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
			]
		);

		$this->add_control(
			'show_language',
			[
				'label' => esc_html__( 'Language', 'sassly-essential' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'sassly-essential' ),
				'label_off' => esc_html__( 'No', 'sassly-essential' ),
				'return_value' => 'yes',
			]
		);
		
		$this->add_control(
			'language_list',
			[
				'label' => esc_html__( 'Language Options', 'sassly-essential' ),
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater_lang->get_controls(),
				'default' => [
					[
						'language' => esc_html__( 'English', 'sassly-essential' ),						
					],					
				],
				'title_field' => '{{{ language }}}',
			]
		);
		
       
	}

	protected function register_language_styles(){
		$this->start_controls_section(
			'section_language_style',
			[
				'label' => esc_html__( 'Language', 'sassly-essential' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'hide_language',
			[
				'label' => esc_html__( 'Show/Hide', 'textdomain' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => '',
				'options' => [
					'' => esc_html__( 'Default', 'textdomain' ),
					'none' => esc_html__( 'None', 'textdomain' ),
				],
				'selectors' => [
					'{{WRAPPER}} .header__lang' => 'display: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'language_typography',
				'selector' => '{{WRAPPER}} .header__lang select',
			]
		);

		$this->add_control(
			'language_text_color',
			[
				'label' => esc_html__( 'Text Color', 'sassly-essential' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .header__lang select' => 'color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_section();
	}



	/**
	 * Retrieves the name of the highest priority template file that exists.
	 *
	 * @param string|array $template_names Template file(s) to search for, in order.
	 *
	 * @return string The template filename if one is located.
	 * @since 1.0.0
	 *
	 */
	protected function locate_template( $template_names ) {
		$located = '';
		foreach ( (array) $template_names as $template_name ) {

			if ( ! $template_name ) {
				continue;
			}

			if ( file_exists( dirname( __FILE__ ) . '/templates/' . $template_name ) ) {
				$located = dirname( __FILE__ ) . '/templates/' . $template_name;
				break;
			}
		}

		return $located;
	}

	/**
	 * Requires the template file with WordPress environment.
	 *
	 *
	 * @param string $_template_file Path to template file.
	 * @param bool $load_once Whether to require_once or require. Default true.
	 * @param array $args Optional. Additional arguments passed to the template.
	 *                               Default empty array.
	 *
	 * @since 1.0.0
	 *
	 */
	protected function load_template( $_template_file, $settings = array(), $load_once = false ) {
		if ( ! file_exists( $_template_file ) ) {
			return;
		}
		if ( $load_once ) {
			require_once $_template_file;
		} else {
			require $_template_file;
		}

	}

	/**
	 * Find template to render and includes it.
	 *
	 * @param string|array $slug template slug or args.
	 *
	 * @return void
	 */
	protected function render_template( $slug ) {

		$settings = $this->get_settings_for_display();

		$found_template = $this->locate_template( $slug );

		if ( ! $found_template ) {
			error_log(
				sprintf( '%s trying to load a template that is not exists.',
					$this->get_name(),
				)
			);
		}

		$this->load_template( $found_template, $settings );
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
	protected function render() {		$settings = $this->get_settings_for_display();

		$preset =  explode( '.', basename($settings['layout_style']) );		
		$preset = is_array($preset) ? $preset[0] : $preset;	
		$max_width  = isset($settings['max-width']['size']) ? $settings['max-width']['size'] : 991;	
		$rapper_attr =[
			'class' => [$preset, 'wcf-default-header-layout'],
			'data-maxwidth' => $max_width,
            'data-hamburger-icon' => $settings['hamburger_icon_switch']
		];

		$this->add_render_attribute( 'wrapper', $rapper_attr );	

		$this->add_render_attribute( 'wrapper', 'class', 'wcf-offcanvas-show' );

		 $offcanvas_gallery = $settings['gallery_list'];
		 $offcanvas_social = $settings['social_list'];

		$tpl_data = [
			'target' => 'offcanvasOne'
		];
         
		?>
		<style>
			<?php if($settings['sticky_logo']['url'] !=''){ ?>
				
				.wcf-default-header-layout .wcf-sticky-logo,			
				.wcf-is-sticky .wcf-default-header-layout .header__logo img
				{
				    display: none;
				}				
				.wcf-is-sticky .wcf-default-header-layout .wcf-sticky-logo
				{
				    display: block !important;
				}
			<?php } ?>
			
			<?php if($settings['custom_sticky_bar']['url'] !=''){ ?>
				.wcf-default-header-layout .wcf-sticky-bar,			
				.wcf-is-sticky .wcf-default-header-layout .wcf-header--offcanvas--icon img			
				{
				    display: none;
				}
				.wcf-is-sticky .wcf-default-header-layout .wcf-sticky-bar				
				{
				    display: block !important;
				}
			<?php } ?>
			.more-actions .item.cart a,.more-actions .style-2,.wcf--header-2 .header__lang select{text-transform:uppercase}.header--search{position:relative}.info--search{position:absolute;width:300px;inset-inline-end:-50px;top:50px;padding:15px 20px;background-color:transparent;border-radius:10px;z-index:9;opacity:0;visibility:hidden;transition:.5s;border:1px solid #fff}.more-actions .style-2,.offcanvas__area,.wcf--header-3 .header__logo,.wcf--header-3 .item,.wcf--header-4 .header__logo,.wcf--header-4 .header__others,.wcf--header-5 .header__inner,.wcf--header-5 .header__logo,.wcf--header-5 .header__others{position:relative}.info--search.visible{opacity:1;visibility:visible;top:40px}.info--search form{display:grid;grid-template-columns:1fr 20px;gap:10px}.info--search input{width:100%;border:0;background:0 0;color:#fff}.info--search input::placeholder{font-size:14px;color:#fff;opacity:.7}.info--search input:focus{outline:0}.info--search .search-icon{width:15px}.search-close{display:none;cursor:pointer}.header__logo{height:100%;min-width:80px;display:flex;align-items:center}.more-actions .item,.more-actions .item.cart a,.wcf-default-header-layout .btn-wrap{align-items:center;display:flex}.header__logo img{min-width:60px}.wcf-header--offcanvas--icon img{min-width:auto}.wcf-header--offcanvas--icon{transition:.3s;min-width:30px}.more-actions .style-2{color:#1c1d20;background:#ffc700;border:3px solid #1c1d20;border-radius:25px;padding:12px 20px;box-shadow:5px 5px}.more-actions .style-2:hover{box-shadow:none}.wcf-wc-cart-fragment-content{background:#e8bf96;width:20px;height:20px;border-radius:100%;line-height:20px;text-align:center;color:#fff;font-size:11px;transition:.3s}.more-actions{display:flex}.more-actions .item.cart a{gap:10px;padding:14px 20px;border:1px solid #1c1d20;border-radius:25px;font-weight:400;font-size:22px;line-height:22px;color:#1c1d20}.wcf--header-1 .search-close,.wcf--header-1 .search-icon,.wcf-default-header-layout .item.link a{fill:#202C58;color:#202c58;transition:.3s}.wcf-default-header-layout .item.link a{gap:15px;display:flex;align-items:center;text-align:right;font-size:14px;font-weight:500;line-height:1.5;text-transform:uppercase}.wcf-default-header-layout .item.link a:hover{color:#e8bf96;fill:#E8BF96}.wcf-default-header-layout{padding:20px 100px;width:100%;left:0;top:0;z-index:99}.wcf--header-2,.wcf--header-3{padding:0 100px}.offcanvas__area{z-index:9999}.wcf--header-1 i,.wcf--header-1 svg{width:1em;height:1em}.wcf--header-1 .header__inner{gap:30px;display:grid;align-items:center;grid-template-columns:1fr 2.5fr 1fr}.wcf--header-1 .header__logo img{object-fit:cover}.wcf--header-1 .header__others{gap:20px;display:flex;height:100%;align-items:center;justify-content:flex-end}.wcf--header-1 .more-actions{gap:20px;display:flex}.wcf--header-1 .item.link a span{text-align:right}.wcf--header-1 .item.link a .icon{font-size:20px}.wcf--header-1 .header__lang select,.wcf--header-2 .header__lang select{background:0 0;border:none;border-radius:0;padding:10px}.elementor-widget-wcf--header-preset.info--menu-alignment-end .header__inner,.elementor-widget-wcf--header-preset.info--menu-alignment-start .header__inner{grid-template-columns:auto 1fr auto}.elementor-widget-wcf--header-preset.info--menu-alignment-end .header__inner.wcf-mobile-nav-active,.elementor-widget-wcf--header-preset.info--menu-alignment-start .header__inner.wcf-mobile-nav-active{display:flex;align-items:center;justify-content:space-between}.elementor-widget-wcf--header-preset.info--menu-alignment-center .header__inner{grid-template-columns:1fr 2.5fr 1fr}.elementor-widget-wcf--header-preset.info--menu-alignment-center .header__inner.wcf-mobile-nav-active{display:flex;align-items:center;justify-content:space-between}.header__inner.wcf-mobile-nav-active .header__nav,.wcf--header-3 .item:last-child::after{display:none}.wcf--header-2 .header__inner{display:grid;border-bottom:1px solid #1c1d20}.wcf--header-2 .header__logo,.wcf--header-2 .header__others{gap:30px;display:flex;align-items:center;position:relative}.wcf--header-2 .item.cart a:hover{color:#f0b849;border-color:#f0b849}.wcf--header-2 .wcf-wc-cart-fragment-content{background:var(--orange-2)}.wcf--header-2 .main-menu>ul>li>a{color:#2c3034;padding:30px 15px}.wcf--header-2 .main-menu>ul>li>a:hover{color:#f0b849}.wcf--header-3{border-bottom:1px solid #e4ddd5}.wcf--header-3 .info--search{background-color:#fbf8f3}.wcf--header-3 .info--search input,.wcf--header-3 .info--search input::placeholder{color:#555}.wcf--header-3 .main-menu>ul>li>a{color:#555}.wcf--header-4 .main-menu>ul>li>a,.wcf--header-5 .main-menu>ul>li>a{color:#1c1d20}.wcf--header-3 .header__logo{gap:50px}.wcf--header-3 .line{width:1px;background-color:#e4ddd5}.wcf--header-3 .item.button a{color:#fff;padding:13px 36px;background-color:#6a6bbf}.wcf--header-3 .header__logo::after{position:absolute;content:"";width:1px;height:100%;inset-inline-end:calc(var(--gap,-50px)/ 2);top:0;background-color:#e4ddd5}.wcf--header-3 .wcf-separator::after{position:absolute;content:"";top:0;inset-inline-end:calc(var(--gap,-20px)/ 2);width:1px;height:100%;background-color:#e4ddd5}.wcf--header-4 .header__logo::after,.wcf--header-4 .header__others::before{position:absolute;content:"";width:1px;height:100%;top:0;background-color:#e7cfbe}.wcf--header-4{padding:0 150px}.wcf--header-4 .header__inner{gap:100px;border-bottom:1px solid #e7cfbe}.wcf--header-4 .header__logo::after{inset-inline-end:calc(var(--gap,-100px)/ 2)}.wcf--header-4 .header__others::before{inset-inline-start:calc(var(--gap,-100px)/ 2)}.wcf--header-5 .header__logo::after,.wcf--header-5 .header__others::before{position:absolute;content:"";width:3px;height:100%;top:0;background-color:#1c1d20}.wcf--header-5{padding:0}.wcf--header-5 .header__inner{padding:0 30px;background-color:#fff;border:3px solid var(--black-2);border-top:none;border-bottom-width:10px;border-radius:0 0 45px 45px;gap:80px}.wcf--header-5 .header__logo::after{inset-inline-end:calc(var(--gap,-80px)/ 2)}.wcf--header-5 .header__others::before{inset-inline-start:calc(var(--gap,-80px)/ 2)}.elementor-widget-wcf--header-preset.info--menu-alignment-center .wcf--header-5 .header__inner{grid-template-columns:1fr 4fr 1fr}.elementor-widget-wcf--header-preset.info--menu-alignment-center .wcf--header-5 .header__inner.wcf-mobile-nav-active{display:flex;align-items:center;justify-content:space-between}@media (max-width:1399px){.wcf-default-header-layout{padding:20px 50px}}@media (max-width:1199px){.wcf-default-header-layout{padding:15px 30px}}@media (max-width:991px){.wcf-default-header-layout{padding:15px 30px}}@media (max-width:767px){.wcf-default-header-layout{padding:15px}.wcf--header-2 .header__logo{justify-content:space-between;width:100%}.info--search{inset-inline-end:-120px}}
		</style>
		<?php
		$template = 'template-' . preg_replace( "/[^0-9]/", '', $preset ) . '.php';
		$this->render_template( $template );
		?>

		<?php if($settings['content_source'] === 'theme'){ ?>
			<?php get_template_part('template-parts/headers/content','offcanvas', $tpl_data); ?>
			<?php } else { ?>
				<!-- Offcanves start -->
                <div class="offcanvas__area">
                    <div class="offcanvas offcanvas-end" tabindex="-1" id="<?php echo esc_attr($tpl_data['target']); ?>">
                        <button class="offcanvas__close" data-bs-dismiss="offcanvas"><i class="icon-wcf-close"></i></button>
                        <div class="offcanvas__body">
                            <div class="offcanvas__logo">
                                <?php if(isset( $settings['logo']['url'] ) && $settings['logo']['url'] !=''): ?>
                                    <a href="<?php echo esc_url(home_url('/')); ?>"><img src="<?php echo esc_url($settings['logo']['url']); ?>" alt="<?php echo esc_attr__('Offcanvas Logo','sassly-essential') ?>"></a>
                                <?php endif; ?>
                                <div class="desc">
	                                <?php echo wp_kses_post( wpautop( $settings['description'] ) ); ?>
                                </div>
                            </div>
                            <div class="offcanvas__menu-area">
                                <div class="offcanvas__menu-wrapper">

                                </div>
                            </div>
                            <?php if ( ! empty( $offcanvas_gallery ) ) { ?>
                            <div class="offcanvas__gallery">
                                <h2 class="offcanvas__title"><?php echo esc_html($settings['gal_title']); ?></h2>
                                <div class="gallery__items">
                                    <?php foreach ( $offcanvas_gallery as $gallery_item ) { ?>
                                            <div class="gallery__item">
                                                <a href="<?php echo esc_url($gallery_item['image']['url']); ?>"><img src="<?php echo esc_url($gallery_item['image']['url']); ?>" alt="<?php echo esc_attr($gallery_item['title']); ?>">
                                                    <span><i class="fa-brands fa-instagram"></i></span></a>
                                            </div>
                                    <?php } ?>
                                </div>
                            </div>
                            <?php } ?>
                            <?php if(! empty($settings['social_title']) ){ ?>
                                <div class="offcanvas__media">
                                    <h2 class="offcanvas__title"><?php echo esc_html($settings['social_title']); ?></h2>
                                    <ul>
                                    <?php foreach ( $offcanvas_social as $social_item ) { ?>
                                        <li><a href="<?php echo $social_item['website_link']['url']; ?>">
                                                <?php \Elementor\Icons_Manager::render_icon( $social_item['icon'], [ 'aria-hidden' => 'true' ] ); ?>
                                        </a></li>
                                    <?php } ?>
                                    </ul>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <!-- Offcanves end -->
			<?php } ?>  
		<?php
	}

	protected function render_action( $item, $key, $settings ) {
		if ( empty( $item ) ) {
			return;
		}

		$this->render_button( $item, $key, $settings );

		$this->render_search( $item, $key );

		$this->render_link( $item, $key );

		$this->render_wc_cart( $item, $key );
	}

	protected function render_wc_cart( $item, $key ) {

		if ( 'cart' !== $item['action_type'] ) {
			return;
		}

		if ( !class_exists( 'woocommerce' ) ) { return false; }

		if ( ! empty( $item['cart_link']['url'] ) ) {
			$this->add_link_attributes( 'cart_link'.$key, $item['cart_link'] );
		}

		$this->add_render_attribute(
			'cart_link'.$key,
			[
				'class' => [ 'position-relative' ]
			]
		);

		?>
        <div class="header-cart">
            <a <?php $this->print_render_attribute_string( 'cart_link'.$key ); ?>>
				<?php Icons_Manager::render_icon( $item['cart_icon'], [ 'aria-hidden' => 'true' ] ); ?>
                <div class="wcf-text d-inline"><?php echo $item[ 'cart_text' ] ?></div>
                <span class="wcf-wc-cart-fragment-content d-inline">
            <?php echo WC()->cart !==null ? str_pad(WC()->cart->get_cart_contents_count(), 2, "0", STR_PAD_LEFT) : '00'; ?>
            </span></a>
        </div>
		<?php
    }

    protected function render_button( $item, $key, $settings ) {
	    if ( 'button' !== $item['action_type'] ) {
		    return;
	    }

	    if ( ! empty( $item['btn_link']['url'] ) ) {
		    $this->add_link_attributes( 'btn_link', $item['btn_link'] );
	    }
	    ?>
        <a <?php $this->print_render_attribute_string( 'btn_link' ); ?>
                class="wcf-btn-default btn-<?php echo $item['btn_hover_list']; ?>">
		    <?php if ( $item['icon_before'] === 'yes' ) { ?>
                <span class="icon"><?php Icons_Manager::render_icon( $item['btn_icon'], [ 'aria-hidden' => 'true' ] ); ?></span>
		    <?php } ?>
		    <?php echo $item['btn_text']; ?>
		    <?php if ( $item['icon_before'] === '' ) { ?>
                <span class="icon"><?php Icons_Manager::render_icon( $item['btn_icon'], [ 'aria-hidden' => 'true' ] ); ?></span>
		    <?php } ?>
        </a>
        <?php
    }

	protected function render_link( $item, $key ) {
		if ( 'link' !== $item['action_type'] ) {
			return;
		}

		if ( ! empty( $item['link_link']['url'] ) ) {
			$this->add_link_attributes( 'link_link'.$key, $item['link_link'] );
		}

		?>
        <a  <?php $this->print_render_attribute_string( 'link_link'.$key ); ?>>
            <span><?php echo $item['link_label']; ?></span>
            <div class="icon"><?php Icons_Manager::render_icon( $item['link_icon'], [ 'aria-hidden' => 'true' ] ); ?></div>
        </a>
		<?php
	}

	protected function render_search( $item, $key ) {
		if ( 'search' !== $item['action_type'] ) {
			return;
		}

		?>
        <div class="header--search">
            <button class="search-icon"><?php Icons_Manager::render_icon( $item['search_icon'], [ 'aria-hidden' => 'true' ] ); ?></button>
            <button class="search-close"><i class="wcf-icon icon-wcf-close"></i></button>

            <div class="info--search">
                <form role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
                    <input type="text" placeholder="<?php echo esc_html($item['search_placeholder']); ?>" value="<?php echo get_search_query(); ?>" name="s" />
                    <button  type="submit" class="info--search-btn">
                        <span class="elementor-screen-only">
                            <?php _e('Search', 'sassly-essential');  ?>
                        </span>                     
                    </button>
                </form>
            </div>
        </div>
		<?php
	}
}
