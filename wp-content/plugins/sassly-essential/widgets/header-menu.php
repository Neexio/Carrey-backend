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
use Elementor\Plugin;
use Elementor\Utils;
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
class Header_Menu extends Widget_Base {

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
		return 'wcf--header-menu';
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
		return wcf_elementor_widget_concat_prefix( 'Header Navigation' );
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
		return 'wcf eicon-navigation-horizontal';
	}
	
	public function get_all_images_urls(){
	     $returns_data = [];
	     $media_dir    = SASSLY_ESSENTIAL_DIR_PATH . 'assets/images/bars/';
	     $url_path     = SASSLY_ESSENTIAL_ASSETS_URL .'images/bars/';	     
	    
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
	
	// public function get_style_depends() {
	// 	wp_register_style( 'info-header-menu', SASSLY_ESSENTIAL_ASSETS_URL . 'css/header.css' );
	// 	return [ 'info-header-menu' ];
	// }
	
	public function get_script_depends() {
	    wp_register_script( 'wcf-header-menu', SASSLY_ESSENTIAL_ASSETS_URL. '/js/widgets/header-menu.js' , [ 'jquery','meanmenu' ], false, true );
		return [ 'wcf-header-menu' ];
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
				'label' => esc_html__( 'Layout Style', 'sassly-essential' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'athletic__header-menu',
				'options' => [
					'athletic__header-menu' => esc_html__( 'Athletic', 'sassly-essential' ),
					'fashion__header-menu' => esc_html__( 'Fasion', 'sassly-essential' ),					
				],
			]
		);
		
		$this->add_control(
            'menu_selected',
            [
                'label' => esc_html__( 'Menu', 'sassly-essential' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'primary',
                'options' => sassly_menu_list()
            ]
        );
        
        $this->add_control(
			'show_offcanvas',
			[
				'label' => esc_html__( 'Show menu in Offcanvas', 'sassly-essential' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'sassly-essential' ),
				'label_off' => esc_html__( 'No', 'sassly-essential' ),
				'return_value' => 'yes',
				'default' => '',
			]
		);
      
		$this->add_responsive_control(
            'wcf_c_m_algn',
            [
                'label'   => esc_html__('Alignment', 'sassly-essential'),
                'type'    => Controls_Manager::CHOOSE,
                'options' => [

                    'flex-start'         => [
                        'title' => esc_html__('Left', 'sassly-essential'),
                        'icon'  => 'eicon-h-align-left',
                    ],
                    'center'         => [
                        'title' => esc_html__('Center', 'sassly-essential'),
                        'icon'  => 'eicon-h-align-center',
                    ],
                    'flex-end'     => [
                        'title' => esc_html__('Right', 'sassly-essential'),
                        'icon'  => ' eicon-h-align-right',
                    ],                   
                    'space-around'     => [
                        'title' => esc_html__('Around', 'sassly-essential'),
                        'icon'  => 'eicon-justify-space-around-h',
                    ],                     
                    'space-between'     => [
                        'title' => esc_html__('between', 'sassly-essential'),
                        'icon'  => 'eicon-justify-space-between-h',
                    ],                    
                    'space-evenly'     => [
                        'title' => esc_html__('evenly', 'sassly-essential'),
                        'icon'  => 'eicon-justify-space-evenly-h',
                    ],
                ],                
                'selectors' => [
                    '{{WRAPPER}} nav > ul'      => 'justify-content: {{VALUE}};',                    
                ],
                'condition' => [
                 'layout_style' => ['athletic__header-menu']
                ]
            ]
        ); //Responsive control end .fashion__header-menu nav ul
        
        $this->add_responsive_control(
            'wcf_c_m_algn_fas',
            [
                'label'   => esc_html__('Alignment', 'sassly-essential'),
                'type'    => Controls_Manager::CHOOSE,
                'options' => [

                    'flex-start'         => [
                        'title' => esc_html__('Left', 'sassly-essential'),
                        'icon'  => 'eicon-h-align-left',
                    ],
                    'center'         => [
                        'title' => esc_html__('Center', 'sassly-essential'),
                        'icon'  => 'eicon-h-align-center',
                    ],
                    'flex-end'     => [
                        'title' => esc_html__('Right', 'sassly-essential'),
                        'icon'  => ' eicon-h-align-right',
                    ],                   
                   
                ],                
                'selectors' => [
                    '{{WRAPPER}} .fashion__header-menu nav ul'      => 'align-items: {{VALUE}};',                    
                ],
                'condition' => [
					'layout_style' => ['fashion__header-menu']
				   ]
            ]
        ); //Responsive control end 

		$this->add_responsive_control(
			'item_gap',
			[
				'label' => esc_html__( 'Menu Gap', 'sassly-essential' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', 'rem' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 300,
						'step' => 1,
					],					
				],				
				'selectors' => [
					'{{WRAPPER}} nav > ul' => 'gap: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		$this->add_responsive_control(
			'item_padding',
			[
				'label' => esc_html__( 'Padding', 'textdomain' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],				
				'selectors' => [
					'{{WRAPPER}} .wcf-default-header-layout' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_offcanvas_content',
			[
				'label' => __( 'Offcanvas Settings', 'sassly-essential' ),
				'condition' => ['show_offcanvas' => 'yes']
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
							'max' => 5000,
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
				'custom_bar',
				[
					'label'       => __( 'Custom Bar', 'sassly-essential' ),
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
					'label'     => esc_html__('Bar', 'sassly-essential'),
					'type'      => \SasslyEssentialApp\CustomControl\ImageSelector_Control::ImageSelector,
					'options'   => $this->get_all_images_urls(),
					'bgcolor'   => '#D2EAF1',
					'col'       => 3,
					'default'   => 'hamburger-icon-0.png',
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
			$this->add_control(
				'offcanvas_align',
				[
					'label'   => esc_html__('Alignment', 'sassly-essential'),
					'type'    => Controls_Manager::CHOOSE,
					'default' => 'offcanvas-end',
					'options' => [
	
						'offcanvas-start'         => [
							'title' => esc_html__('Left', 'sassly-essential'),
							'icon'  => 'eicon-h-align-left',
						],					
						'offcanvas-end'     => [
							'title' => esc_html__('Right', 'sassly-essential'),
							'icon'  => ' eicon-h-align-right',
						],                   
					   
					]                
					
				]
			); //Responsive control end 
		$this->end_controls_section();
		$this->start_controls_section(
			'section_custom_content',
			[
				'label' => esc_html__( 'Offcanvas Content', 'sassly-essential' ),
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
					'label'       => esc_html__( 'Description', 'sassly-essential' ),
					'type'        => \Elementor\Controls_Manager::WYSIWYG,
					'rows'        => 10,
					'default'     => esc_html__( 'Default description', 'sassly-essential' ),
					'placeholder' => esc_html__( 'Type your description here', 'sassly-essential' ),
				]
			);

			$this->add_control(
				'show_gallery',
				[
					'label'        => esc_html__( 'Show Gallery', 'sassly-essential' ),
					'type'         => Controls_Manager::SWITCHER,
					'label_on'     => esc_html__( 'Show', 'sassly-essential' ),
					'label_off'    => esc_html__( 'Hide', 'sassly-essential' ),
					'return_value' => 'yes',
					'default'      => 'no',
				]
			);
		
			$this->add_control(
				'gal_title',
				[
					'label' => esc_html__( 'Gallery Title', 'sassly-essential' ),
					'type' => Controls_Manager::TEXTAREA,
					'default' => esc_html__( 'Cart', 'sassly-essential' ),
					'placeholder' => esc_html__( 'Type your cart text here', 'sassly-essential' ),
					'condition' => [
						'show_gallery'  => 'yes',
					]
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
					'condition' => [
						'show_gallery'  => 'yes',
					]
				]
			);

		$this->add_control(
			'show_socials',
			[
				'label' => esc_html__( 'Show Social Icon', 'sassly-essential' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Show', 'sassly-essential' ),
				'label_off' => esc_html__( 'Hide', 'sassly-essential' ),
				'return_value' => 'yes',
				'default' => 'no',
			]
		);
		
			$this->add_control(
				'social_title',
				[
					'label' => esc_html__( 'Social Title', 'sassly-essential' ),
					'type' => \Elementor\Controls_Manager::TEXT,
					'default' => esc_html__( 'Social', 'sassly-essential' ),
					'placeholder' => esc_html__( 'Type your social text here', 'sassly-essential' ),
                    'condition' => [
                            'show_socials'  => 'yes',
                    ]
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
					'condition' => [
						'show_socials'  => 'yes',
					]
				]
			);
		
		$this->end_controls_section();
		$this->start_controls_section(
			'section_icon_content',
			[
				'label' => __( 'Icon Settings', 'sassly-essential' ),
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
			
			$this->add_responsive_control(
				'icon_height',
				[
					'label' => esc_html__( 'Icon Height', 'sassly-essential' ),
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
						'body .offcanvas__menu-wrapper.mean-container .mean-nav ul li a.mean-expand' => 'height: {{SIZE}}{{UNIT}};',					
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


		$this->start_controls_section(
			'section_style_menu',
			[
				'label' => esc_html__( 'Menu', 'sassly-essential' ),
				'tab'   => Controls_Manager::TAB_STYLE,
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
						'{{WRAPPER}} .wcf-default-header-layout ul li' => 'color: {{VALUE}}',
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
					'label' => esc_html__( 'Item Padding', 'sassly-essential' ),
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
					'{{WRAPPER}} .wcf-default-header-layout ul li a:hover' => 'color: {{VALUE}}',
					'{{WRAPPER}} .wcf-default-header-layout ul li a:hover svg' => 'fill: {{VALUE}}',
					'{{WRAPPER}} .wcf-default-header-layout ul li.active a' => 'color: {{VALUE}}',
					'{{WRAPPER}} .wcf-default-header-layout ul li.active a svg' => 'fill: {{VALUE}}',
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
						'{{WRAPPER}} .wcf-default-header-layout ul.dp-menu li a svg' => 'fill: {{VALUE}}',
						'{{WRAPPER}} .wcf-default-header-layout ul.dp-menu li a' => 'color: {{VALUE}}',
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
					'{{WRAPPER}} .wcf-default-header-layout ul.dp-menu li a:hover svg' => 'fill: {{VALUE}}',
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
					'label' => esc_html__( 'Arrow Icon', 'sassly-essential' ),
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
					
						
					$this->add_responsive_control(
						'dearrow_iucon_width',
						[
							'label' => esc_html__( 'Svg Width', 'sassly-essential' ),
							'type' => \Elementor\Controls_Manager::SLIDER,
							'size_units' => [ 'px', 'em', 'rem' ],
							'range' => [
								'px' => [
									'min' => 0,
									'max' => 1200,
									'step' => 1,
								],					
							],	
							'default' => [
								'unit' => 'px',
								'size' => 15,
							],
							'selectors' => [
								'{{WRAPPER}} .wcf-default-header-layout ul li a svg' => 'width: {{SIZE}}{{UNIT}};',
							],
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
						'selector' => '{{WRAPPER}} .wcf-default-header-layout ul li a:hover i, {{WRAPPER}} .wcf-default-header-layout ul li a:hover svg',
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

		$this->add_control(
			'offcanvas_content_align',
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
				'default' => 'center',
				'toggle' => true,
				'selectors' => [
					'.offcanvas__area .offcanvas' => 'text-align: {{VALUE}} !important;',
				],
			]
		);
		
		$this->end_controls_section();

		// Hamburger Icon
		$this->start_controls_section(
			'style_hamburger_icon',
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


		// Mobile Menu
		$this->start_controls_section(
			'section_mobile_menu',
			[
				'label' => esc_html__( 'Mobile Menu', 'sassly-essential' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'mobile_text_padding',
			[
				'label' => esc_html__( 'Padding', 'sassly-essential' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .mean-container .mean-nav ul li a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'mobile_text_color',
			[
				'label' => esc_html__( 'Text Color', 'sassly-essential' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mean-container .mean-nav ul li a' => 'color: {{VALUE}}',
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
					'{{WRAPPER}} .mean-container .mean-nav ul li li a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .mean-container .mean-nav ul li li li a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .mean-container .mean-nav ul li a.mean-expand' => 'color: {{VALUE}}',
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
		$max_width  = isset($settings['max-width']['size']) ? $settings['max-width']['size'] : 991;
		$max_width  = $settings['show_offcanvas'] == 'yes' ? $max_width : 100;
		
		$this->add_render_attribute( 'wrapper', 'class', $settings['layout_style'] );	
		$this->add_render_attribute( 'wrapper', 'class', 'wcf-default-header-layout' );	
		$this->add_render_attribute( 'wrapper', 'class', $settings['show_offcanvas'] =='yes' ? 'wcf-offcanvas-show' : '' );	
		$this->add_render_attribute( 'wrapper', 'data-maxwidth', $max_width );	
		$menu_selected = $settings[ 'menu_selected' ];
		
		if($menu_selected == ''){
			return;
		}
		
		$nav_walker_default = [	
		    'custom_icon'               => $settings[ 'custom_direction' ],
		    'menu_down_icon'            =>  $settings[ 'menu_down_icon' ],
		    'menu_right_icon'           =>  $settings[ 'menu_right_icon' ],
		    'has_dropdown_arrow_icon'   => (isset($settings[ 'menu_down_icon' ][ 'value' ]) && $settings[ 'menu_down_icon' ][ 'value' ] !='') || (isset( $settings[ 'menu_down_icon' ]['value']) && is_array( $settings[ 'menu_down_icon' ]['value'] ) ) ? true : false,
		    'has_right_arrow_icon'      => (isset($settings[ 'menu_right_icon' ][ 'value' ]) && $settings[ 'menu_right_icon' ][ 'value' ] !='') || (isset( $settings[ 'menu_right_icon' ]['value']) && is_array( $settings[ 'menu_right_icon' ]['value'] ) ) ? true : false
		];
		
		$args = [
           'menu'            => $menu_selected,
           'container'       => 'nav', 
           'container_class' => 'main-menu', 
           'menu_class'      =>  'wcf--elementor--menu', 
           'walker'          => new WCF_Walker_Elementor_Nav( $nav_walker_default )        
        ];
        
		$tpl_data = [
			'target'    => 'offcanvasOne',
			'align'     => $settings['offcanvas_align']
		 ];         
		 $bar  = SASSLY_ESSENTIAL_ASSETS_URL .'images/bars/'.$settings['bar'];
		 if($settings['custom_bar'] == 'yes' && isset($settings['custom_bar_image']['url'])){
			 $bar = $settings['custom_bar_image']['url'];
		 }

		 $offcanvas_gallery = $settings['gallery_list'];     
		 $offcanvas_social = $settings['social_list'];      
         
		?>
		
        <style>
            <?php if($settings['custom_direction'] == 'yes'){ ?>
	        .elementor-element-<?php echo $this->get_id(); ?> .main-menu li.menu-item-has-children > a.remove-default-icon:after{
	            display:none;
	        }
	        .wcf-header--offcanvas--icon {
			    display: none;
			}
	        @media only screen and (max-width: <?php echo $max_width; ?>px){
				.wcf-header--offcanvas--icon {
			        display: block;
				}
	        }	
			<?php } ?>
	        .fashion__header-menu{text-align:right;z-index:9}.fashion__header-menu nav ul{flex-direction:column;align-items:flex-end}.fashion__header-menu nav ul li:hover .dp-menu{right:100%;left:auto}.fashion__header-menu nav ul li:hover a::after{left:auto;right:auto}.fashion__header-menu nav ul .dp-menu{right:calc(100% + 20px);left:auto;top:0}.fashion__header-menu nav ul .dp-menu a::after{display:none}@media only screen and (max-width:1199px){.fashion__header-menu{display:none}}.fashion__header-menu li{margin-bottom:10px}.fashion__header-menu li a{color:var(--white);text-transform:uppercase;font-size:14px;padding:5px 0}.fashion__header-menu li a:hover{color:var(--white-5)}
        </style>
       
		<div <?php $this->print_render_attribute_string( 'wrapper' ); ?>>               
			<?php wp_nav_menu($args); ?>
        </div>
        <?php if($settings['show_offcanvas'] == 'yes'){ ?> 
		<button class="wcf-header--offcanvas--icon" data-bs-toggle="offcanvas" data-bs-target="#<?php echo esc_attr($tpl_data['target']); ?>">            
            <?php if($bar !=''){ ?>
                <img src="<?php echo esc_url($bar); ?>" />
            <?php }else{ ?>
                <span class="menu-icon"><span></span></span> 
            <?php } ?>
        </button>          
		<?php if($settings['content_source'] === 'theme'){ ?>
            <?php get_template_part('template-parts/headers/content','offcanvas', $tpl_data); ?>
        <?php } else { ?>
          <!-- Offcanves start -->
              <div class="offcanvas__area wcf-hmw">
                <div class="offcanvas <?php echo esc_attr($settings['offcanvas_align']); ?>" tabindex="-1" id="<?php echo esc_attr($tpl_data['target']); ?>">
                  <button class="offcanvas__close" data-bs-dismiss="offcanvas"><i class="icon-wcf-close"></i></button>
                  <div class="offcanvas__body">
                    <div class="offcanvas__logo">
                      <?php if(isset( $settings['logo']['url'] ) && $settings['logo']['url'] !=''): ?> 
                        <a href="<?php echo esc_url(home_url('/')); ?>"><img src="<?php echo esc_url($settings['logo']['url']); ?>" alt="<?php echo esc_attr__('Offcanvas Logo','sassly-essential') ?>"></a>
                      <?php endif; ?>
                        <?php if( !empty($settings['description']) ): ?>
                          <div class="desc">
                              <?php echo wp_kses_post( wpautop( $settings['description'] ) ); ?>
                          </div>
                        <?php endif; ?>
                    </div>            
                    <div class="offcanvas__menu-area">
                      <div class="offcanvas__menu-wrapper">                         
                      </div>
                    </div>
	                  <?php if( $settings['show_gallery'] == 'yes' ){ ?>
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
                    <?php if( $settings['show_socials'] == 'yes' ){ ?>
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
        <?php 
			}
        } ?>  
		<?php

	}
}
