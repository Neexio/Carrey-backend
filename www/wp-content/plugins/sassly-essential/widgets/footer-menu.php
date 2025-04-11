<?php

namespace SasslyEssentialApp\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Icons_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Css_Filter;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Image_Size;
use Elementor\Modules\DynamicTags\Module as TagsModule;
use Elementor\Utils;
use Elementor\Plugin;
use Elementor\Repeater;
use SasslyEssentialApp\Inc\WCF_Walker_Elementor_Footer_Nav;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Footer_Menu extends Widget_Base {

    public function get_name() {
        return 'wcf--footer--menu';
    }
    
    public function get_title() {
        return wcf_elementor_widget_concat_prefix( 'Footer Nav' );
    }

    public function get_icon() {
        return 'wcf eicon-nav-menu';
    }

    public function get_categories() {
        return [ 'weal-coder-addon' ];
    }

    public function get_keywords() {
        return [ 'Nav Menu', 'footer Menu', 'Navigation' ];
    }

    public function get_style_depends() {
        wp_register_style( 'wcf-navigation-menu', SASSLY_ESSENTIAL_ASSETS_URL . 'css/navigation.css' );
		return [ 'wcf-navigation-menu' ];
    }

    private function get_available_menus() {

		$menus     = wp_get_nav_menus();
		$menulists = [];
        foreach ( $menus as $menu ) {
            $menulists[ $menu->slug ] = $menu->name;
        }
        return $menulists;

    }

    protected function register_controls() {

        /*------------------------
			MENU CONTENT SOURCE
        -------------------------*/
        $this->start_controls_section(
            'inline_menu_content',
            [
                'label' => esc_html__( 'Select Navigation & Style', 'sassly-essential' ),
            ]
        );
            $this->add_control(
                'inline_menu_style',
                [
                    'label'   => esc_html__( 'Style', 'sassly-essential' ),
                    'type'    => Controls_Manager::SELECT,
                    'default' => '1',
                    'options' => [
                        '1'      => esc_html__( 'Style One', 'sassly-essential' ),
                        '2'      => esc_html__( 'Style Two', 'sassly-essential' ),
                        '3'      => esc_html__( 'Style Three', 'sassly-essential' ),
                        // '4'      => esc_html__( 'Badge Menu', 'sassly-essential' ),
                        // 'custom' => esc_html__( 'Custom Style', 'sassly-essential' ),
                    ],
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
                    'condition' => ['inline_menu_style'=> ['3','2'] ]
                ]
            ); 
           
            
            $this->add_control(
                'menu_right_icon',
                [
                    'label' => esc_html__( 'Nav Arrow Icon', 'sassly-essential' ),
                    'type' => \Elementor\Controls_Manager::ICONS,
                    'condition' => ['custom_direction'=>'yes', 'inline_menu_style'=> ['3','2']]                  
                ]
            );
            
            $this->add_control(
                'icon_left',
                [
                    'label' => esc_html__( 'Icon Left ?', 'sassly-essential' ),
                    'type' => \Elementor\Controls_Manager::SWITCHER,
                    'label_on' => esc_html__( 'Yes', 'sassly-essential' ),
                    'label_off' => esc_html__( 'No', 'sassly-essential' ),
                    'return_value' => 'yes',
                    'default' => 'yes',
                    'condition' => ['inline_menu_style'=> ['3','2'] ]
                ]
            );            
            
            
            if ( ! empty( $this->get_available_menus() ) ) {
                $this->add_control(
                    'inline_menu_id',
                    [
                        'label'        => esc_html__( 'Menu', 'sassly-essential' ),
                        'type'         => Controls_Manager::SELECT,
                        'options'      => $this->get_available_menus(),
                        'default'      => array_keys( $this->get_available_menus() )[0],
                        'save_default' => true,
                        'description'  => sprintf( esc_html__( 'Go to the <a href="%s" target="_blank">Menus Option</a> to manage your menus.', 'sassly-essential' ), admin_url( 'nav-menus.php' ) ),
                        'separator'    => 'before',
                    ]
                );
            } else {
                $this->add_control(
                    'inline_menu_id',
                    [
                        'type'      => Controls_Manager::RAW_HTML,
                        'raw'       => sprintf( esc_html__( '<strong>There are no menus in your site.</strong><br>Go to the <a href="%s" target="_blank">Menus Option</a> to create one.', 'sassly-essential' ), admin_url( 'nav-menus.php?action=edit&menu=0' ) ),
                        'separator' => 'before',
                    ]
                );
            }
            $this->add_control(
                'menu_depth',
                [
                    'label' => esc_html__( 'Menu Depth', 'sassly-essential' ),
                    'type' => \Elementor\Controls_Manager::NUMBER,
                    'min' => 1,
                    'max' => 3,
                    'step' => 1,
                    'default' => 1,
                ]
            );
        $this->end_controls_section();
        /*------------------------
			MENU CONTENT SOURCE END
        -------------------------*/

        /*------------------------
			MENU ITEMS STYLE
        -------------------------*/
        $this->start_controls_section(
            'inline_menu_style_section',
            [
                'label' => esc_html__( 'Menu Items', 'sassly-essential' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );
            $this->add_responsive_control(
                'menu_items_display',
                [
                    'label'   => esc_html__( 'Display', 'sassly-essential' ),
                    'type'    => Controls_Manager::SELECT,
                    'default' => 'flex',
                    'options' => [
                        'initial'      => esc_html__( 'Initial', 'sassly-essential' ),
                        'block'        => esc_html__( 'Block', 'sassly-essential' ),
                        'inline-block' => esc_html__( 'Inline Block', 'sassly-essential' ),
                        'flex'         => esc_html__( 'Flex', 'sassly-essential' ),
                        'inline-flex'  => esc_html__( 'Inline Flex', 'sassly-essential' ),
                        'inherit'      => esc_html__( 'Inherit', 'sassly-essential' ),
                        'none'         => esc_html__( 'None', 'sassly-essential' ),
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .single__menu__nav .wcf__menu' => 'display: {{VALUE}};',
                    ],
                ]
            );
            $this->add_responsive_control(
                'menu_items_width',
                [
                    'label'      => esc_html__( 'Width', 'sassly-essential' ),
                    'type'       => Controls_Manager::SLIDER,
                    'size_units' => [ 'px', '%' ],
                    'range'      => [
                        'px' => [
                            'min'  => 0,
                            'max'  => 1000,
                            'step' => 1,
                        ],
                        '%' => [
                            'min' => 0,
                            'max' => 100,
                        ],
                    ],
                    'default' => [
                        'unit' => 'px',
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .single__menu__nav .wcf__menu' => 'width: {{SIZE}}{{UNIT}};',
                    ],
                    'separator' => 'before',
                ]
            );
            $this->add_responsive_control(
                'menu_items_height',
                [
                    'label'      => esc_html__( 'Height', 'sassly-essential' ),
                    'type'       => Controls_Manager::SLIDER,
                    'size_units' => [ 'px', '%' ],
                    'range'      => [
                        'px' => [
                            'min'  => 0,
                            'max'  => 1000,
                            'step' => 1,
                        ],
                        '%' => [
                            'min' => 0,
                            'max' => 100,
                        ],
                    ],
                    'default' => [
                        'unit' => 'px',
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .single__menu__nav .wcf__menu' => 'height: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );
            $this->add_responsive_control(
                'menu_items_float',
                [
                    'label'   => esc_html__( 'Float', 'sassly-essential' ),
                    'type'    => Controls_Manager::SELECT,
                    'default' => 'none',
                    'options' => [
                        'left'    => esc_html__( 'Left', 'sassly-essential' ),
                        'right'   => esc_html__( 'Right', 'sassly-essential' ),
                        'none'    => esc_html__( 'None', 'sassly-essential' ),
                        'inherit' => esc_html__( 'Inherit', 'sassly-essential' ),
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .single__menu__nav .wcf__menu' => 'float:{{VALUE}};',
                    ],
                    'separator' => 'before',
                ]
            );
                      
            $this->add_responsive_control(
                'menu_items_align',
                [
                    'label'   => esc_html__( 'Alignment', 'sassly-essential' ),
                    'type'    => Controls_Manager::CHOOSE,
                    'condition' => [
                        'menu_items_display!' => ['flex','inline-flex']
                    ],
                    'options' => [
                        'left' => [
                            'title' => esc_html__( 'Left', 'sassly-essential' ),
                            'icon'  => 'eicon-text-align-left',
                        ],
                        'center' => [
                            'title' => esc_html__( 'Center', 'sassly-essential' ),
                            'icon'  => 'eicon-text-align-center',
                        ],
                        'right' => [
                            'title' => esc_html__( 'Right', 'sassly-essential' ),
                            'icon'  => 'eicon-text-align-right',
                        ],
                        'justify' => [
                            'title' => esc_html__( 'Justify', 'sassly-essential' ),
                            'icon'  => 'eicon-text-align-justify',
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .single__menu__nav .wcf__menu' => 'text-align: {{VALUE}};',
                    ],
                    'default'   => '',
                    'separator' => 'before',
                ]
            );
            
            $this->add_responsive_control(
                'menu_items_align_flex',
                [
                    'label'   => esc_html__( 'Alignment', 'sassly-essential' ),
                    'type'    => Controls_Manager::CHOOSE,
                    'condition' => [
                        'menu_items_display' => ['flex','inline-flex']
                    ],
                    'options' => [
                        'flex-start' => [
                            'title' => esc_html__( 'Left', 'sassly-essential' ),
                            'icon'  => 'eicon-text-align-left',
                        ],
                        'center' => [
                            'title' => esc_html__( 'Center', 'sassly-essential' ),
                            'icon'  => 'eicon-text-align-center',
                        ],
                        'flex-end' => [
                            'title' => esc_html__( 'Right', 'sassly-essential' ),
                            'icon'  => 'eicon-text-align-right',
                        ],                        
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .single__menu__nav .wcf__menu' => ' align-items: {{VALUE}};',
                    ],
                    'default'   => '',
                    'separator' => 'before',
                ]
            );
            
            $this->add_responsive_control(
                'menu_items_margin',
                [
                    'label'      => esc_html__( 'Margin', 'sassly-essential' ),
                    'type'       => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors'  => [
                        '{{WRAPPER}} .single__menu__nav .wcf__menu li' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' => 'before',
                ]
            );
        $this->end_controls_section();
        /*------------------------
			MENU ITEMS STYLE
        -------------------------*/

        /*------------------------
			MENU ITEM STYLE
        -------------------------*/
        $this->start_controls_section(
            'inline_menu_item_style_section',
            [
                'label' => esc_html__( 'Single Menu Item', 'sassly-essential' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );
            // Menu Normal Tab
            $this->start_controls_tabs( 'menu_style_tabs' );

                $this->start_controls_tab(
                    'menu_style_normal_tab',
                    [
                        'label' => esc_html__( 'Normal', 'sassly-essential' ),
                    ]
                );
                
                    $this->add_control(
                        'menu_normal_color',
                        [
                            'label'     => esc_html__( 'Color', 'sassly-essential' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .single__menu__nav .wcf__menu li a' => 'color: {{VALUE}};',
                            ],
                            'separator' => 'before',
                        ]
                    );
                    
                    $this->add_group_control(
                        Group_Control_Background:: get_type(),
                        [
                            'name'     => 'menu_normal_background',
                            'label'    => esc_html__( 'Background', 'sassly-essential' ),
                            'types'    => [ 'classic', 'gradient' ],
                            'selector' => '{{WRAPPER}} .single__menu__nav .wcf__menu li a',
                        ]
                    );
                    
                    $this->add_group_control(
                        Group_Control_Typography:: get_type(),
                        [
                            'name'     => 'menu_typography',
                            'selector' => '{{WRAPPER}} .single__menu__nav .wcf__menu li a',
                        ]
                    );  
                    
                    $this->add_responsive_control(
                        'menu_display',
                        [
                            'label'   => esc_html__( 'Display', 'sassly-essential' ),
                            'type'    => Controls_Manager::SELECT,
                            'default' => 'block',
                            'options' => [
                                ''      => esc_html__( 'list-item', 'sassly-essential' ),
                                'block'        => esc_html__( 'Block', 'sassly-essential' ),
                                'inline-block' => esc_html__( 'Inline Block', 'sassly-essential' ),
                                'flex'         => esc_html__( 'Flex', 'sassly-essential' ),
                                'inline-flex'  => esc_html__( 'Inline Flex', 'sassly-essential' ),
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .single__menu__nav .wcf__menu li'   => 'display: {{VALUE}};',
                                '{{WRAPPER}} .single__menu__nav .wcf__menu li a' => 'display: {{VALUE}};',
                            ],
                            'separator' => 'before',
                        ]
                    );
                    
                    $this->add_responsive_control(
                        'menu_items_list_style',
                        [
                            'label'   => esc_html__( 'List Style', 'sassly-essential' ),
                            'type'    => Controls_Manager::SELECT,
                            'default' => 'none',
                            'condition' => [
                                'menu_display' => ['']
                            ],
                            'options' => [
                                'none'                 => esc_html__('None','sassly-essential'),
                                'disc'                 => esc_html__('Disc','sassly-essential'),
                                'circle'               => esc_html__('Circle','sassly-essential'),
                                'square'               => esc_html__('Square','sassly-essential'),
                                'decimal'              => esc_html__('Decimal','sassly-essential'),
                                'decimal-leading-zero' => esc_html__('Decimal-leading-zero','sassly-essential'),
                                'bengali'          => esc_html__('bengali','sassly-essential'),
                                'lower-roman'          => esc_html__('Lower Roman','sassly-essential'),
                                'upper-roman'          => esc_html__('Upper Roman','sassly-essential'),
                                'lower-greek'          => esc_html__('Lower Greek','sassly-essential'),
                                'lower-latin'          => esc_html__('Lower Latin','sassly-essential'),
                                'upper-latin'          => esc_html__('Upper Latin','sassly-essential'),
                                'armenian'             => esc_html__('Armenian','sassly-essential'),
                                'georgian'             => esc_html__('Georgian','sassly-essential'),
                                'lower-alpha'          => esc_html__('Lower Alpha','sassly-essential'),
                                'upper-alpha'          => esc_html__('Upper Alpha','sassly-essential'),
                            ],
                            'selectors' => [                                
                                '{{WRAPPER}} .single__menu__nav .wcf__menu li' => 'list-style: {{VALUE}};',
                            ],
                            'separator' => 'before',
                        ]
                    );
                    
                    $this->add_responsive_control(
                        'menu_position',
                        [
                            'label'   => esc_html__( 'Position', 'sassly-essential' ),
                            'type'    => Controls_Manager::SELECT,
                            'default' => 'relative',
                            'options' => [
                                'initial'  => esc_html__( 'Initial', 'sassly-essential' ),
                                'relative' => esc_html__( 'Relative', 'sassly-essential' ),
                                'static'   => esc_html__( 'Static', 'sassly-essential' ),
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .single__menu__nav .wcf__menu li a' => 'position: {{VALUE}};',
                            ],
                            'separator' => 'before',
                        ]
                    );
                    
                    $this->add_group_control(
                        Group_Control_Border:: get_type(),
                        [
                            'name'      => 'menu_normal_border',
                            'label'     => esc_html__( 'Border', 'sassly-essential' ),
                            'selector'  => '{{WRAPPER}} .single__menu__nav .wcf__menu li a',
                            'separator' => 'before',
                        ]
                    );
                    
                    $this->add_responsive_control(
                        'menu_normal_border_radius',
                        [
                            'label'     => esc_html__( 'Border Radius', 'sassly-essential' ),
                            'type'      => Controls_Manager::DIMENSIONS,
                            'selectors' => [
                                '{{WRAPPER}} .single__menu__nav .wcf__menu li a' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                            ],
                        ]
                    );
                    
                    $this->add_group_control(
                        Group_Control_Box_Shadow:: get_type(),
                        [
                            'name'      => 'menu_normal_box_shadow',
                            'label'     => esc_html__( 'Box Shadow', 'sassly-essential' ),
                            'selector'  => '{{WRAPPER}} .single__menu__nav .wcf__menu li a',
                            'separator' => 'before',
                        ]
                    );
                    
                    $this->add_group_control(
                        Group_Control_Text_Shadow:: get_type(),
                        [
                            'name'     => 'menu_normal_text_shadow',
                            'label'    => esc_html__( 'Text Shadow', 'sassly-essential' ),
                            'selector' => '{{WRAPPER}} .single__menu__nav .wcf__menu li a',
                        ]
                    );
                    
                    $this->add_responsive_control(
                        'menu_item_width',
                        [
                            'label'      => esc_html__( 'Width', 'sassly-essential' ),
                            'type'       => Controls_Manager::SLIDER,
                            'size_units' => [ 'px', '%' ],
                            'range'      => [
                                'px' => [
                                    'min'  => 0,
                                    'max'  => 1000,
                                    'step' => 1,
                                ],
                                '%' => [
                                    'min' => 0,
                                    'max' => 100,
                                ],
                            ],
                            'default' => [
                                'unit' => 'px',
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .single__menu__nav .wcf__menu li'   => 'width: {{SIZE}}{{UNIT}};',
                                '{{WRAPPER}} .single__menu__nav .wcf__menu li a' => 'width: {{SIZE}}{{UNIT}};',
                            ],
                            'separator' => 'before',
                        ]
                    );
                    
                    $this->add_responsive_control(
                        'menu_item_height',
                        [
                            'label'      => esc_html__( 'Height', 'sassly-essential' ),
                            'type'       => Controls_Manager::SLIDER,
                            'size_units' => [ 'px', '%' ],
                            'range'      => [
                                'px' => [
                                    'min'  => 0,
                                    'max'  => 1000,
                                    'step' => 1,
                                ],
                                '%' => [
                                    'min' => 0,
                                    'max' => 100,
                                ],
                            ],
                            'default' => [
                                'unit' => 'px',
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .single__menu__nav .wcf__menu li'   => 'height: {{SIZE}}{{UNIT}};',
                                '{{WRAPPER}} .single__menu__nav .wcf__menu li a' => 'height: {{SIZE}}{{UNIT}};',
                            ],
                        ]
                    );
                    $this->add_responsive_control(
                        'menu_normal_margin',
                        [
                            'label'      => esc_html__( 'Margin', 'sassly-essential' ),
                            'type'       => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em' ],
                            'selectors'  => [
                                '{{WRAPPER}} .single__menu__nav .wcf__menu li a' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                            'separator' => 'before',
                        ]
                    );
                    $this->add_responsive_control(
                        'menu_normal_padding',
                        [
                            'label'      => esc_html__( 'Padding', 'sassly-essential' ),
                            'type'       => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em' ],
                            'selectors'  => [
                                '{{WRAPPER}} .single__menu__nav .wcf__menu li a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                        ]
                    );

                $this->end_controls_tab();

                 // Menu Hover Tab
                $this->start_controls_tab(
                    'menu_style_hover_tab',
                    [
                        'label' => esc_html__( 'Hover', 'sassly-essential' ),
                    ]
                );
                    
                    $this->add_control(
                        'menu_hover_color',
                        [
                            'label'     => esc_html__( 'Color', 'sassly-essential' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .single__menu__nav .wcf__menu > li:hover > a' => 'color: {{VALUE}};',
                            ],
                            'separator' => 'before',
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Background:: get_type(),
                        [
                            'name'     => 'menu_hover_background',
                            'label'    => esc_html__( 'Background', 'sassly-essential' ),
                            'types'    => [ 'classic', 'gradient' ],
                            'selector' => '{{WRAPPER}} .single__menu__nav .wcf__menu > li:hover > a',
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border:: get_type(),
                        [
                            'name'     => 'menu_hover_border',
                            'label'    => esc_html__( 'Border', 'sassly-essential' ),
                            'selector' => '{{WRAPPER}} .single__menu__nav .wcf__menu > li:hover > a',
                        ]
                    );

                    $this->add_responsive_control(
                        'menu_hover_border_radius',
                        [
                            'label'     => esc_html__( 'Border Radius', 'sassly-essential' ),
                            'type'      => Controls_Manager::DIMENSIONS,
                            'selectors' => [
                                '{{WRAPPER}} .single__menu__nav .wcf__menu > li:hover > a' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                            ],
                            'separator' => 'after',
                        ]
                    );

                $this->end_controls_tab();

                // Menu Active Tab
                $this->start_controls_tab(
                    'menu_style_active_tab',
                    [
                        'label' => esc_html__( 'Active', 'sassly-essential' ),
                    ]
                );
                    
                    $this->add_control(
                        'menu_active_color',
                        [
                            'label'     => esc_html__( 'Color', 'sassly-essential' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .single__menu__nav .wcf__menu li.current-menu-item a' => 'color: {{VALUE}};',
                            ],
                            'separator' => 'before',
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Background:: get_type(),
                        [
                            'name'     => 'menu_active_background',
                            'label'    => esc_html__( 'Background', 'sassly-essential' ),
                            'types'    => [ 'classic', 'gradient' ],
                            'selector' => '{{WRAPPER}} .single__menu__nav .wcf__menu li.current-menu-item a',
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border:: get_type(),
                        [
                            'name'     => 'menu_active_border',
                            'label'    => esc_html__( 'Border', 'sassly-essential' ),
                            'selector' => '{{WRAPPER}} .single__menu__nav .wcf__menu li.current-menu-item a',
                        ]
                    );

                    $this->add_responsive_control(
                        'menu_active_border_radius',
                        [
                            'label'     => esc_html__( 'Border Radius', 'sassly-essential' ),
                            'type'      => Controls_Manager::DIMENSIONS,
                            'selectors' => [
                                '{{WRAPPER}} .single__menu__nav .wcf__menu li.current-menu-item a' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                            ],
                            'separator' => 'after',
                        ]
                    );

                $this->end_controls_tab();
            $this->end_controls_tabs();
        $this->end_controls_section();
        /*-------------------------
			MENU ITEM STYLE END
        --------------------------*/
        
        $this->start_controls_section(
            'inline_iconu_style_section',
            [
                'label' => esc_html__( 'Arrow Icon', 'sassly-essential' ),
                'tab'   => Controls_Manager::TAB_STYLE,                
                'condition' => ['custom_direction'=>'yes', 'inline_menu_style'=> ['3','2']]  
            ]
        );       
      
		
		$this->add_control(
			'icon_cusdown_padding',
			[
				'label' => esc_html__( 'Arrow margin', 'textdomain' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem' ],
				'condition' => ['custom_direction'=>'yes'],
				'selectors' => [
					'{{WRAPPER}} li > a svg' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} li > a i' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
        
        $this->end_controls_section();
        /*----------------------------
            BADGE STYLE
        -----------------------------*/
        $this->start_controls_section(
            'badge_style_section',
            [
                'label'     => esc_html__( 'Badge', 'sassly-essential' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'show_menu_bedge' => 'yes',
                ]
            ]
        );

            $this->start_controls_tabs( 'badge_tabs_style' );
                $this->start_controls_tab(
                    'badge_normal_tab',
                    [
                        'label' => esc_html__( 'Normal', 'sassly-essential' ),
                    ]
                );
                    $this->add_control(
                        'badge_color',
                        [
                            'label'     => esc_html__( 'Color', 'sassly-essential' ),
                            'type'      => Controls_Manager::COLOR,
                            'default'   => '',
                            'selectors' => [
                                '{{WRAPPER}} .single__menu__nav .wcf__menu li a .badge' => 'color: {{VALUE}};',
                            ],
                            'separator' => 'before',
                        ]
                    );
                    $this->add_group_control(
                        Group_Control_Background:: get_type(),
                        [
                            'name'     => 'badge_background',
                            'label'    => esc_html__( 'Background', 'sassly-essential' ),
                            'types'    => [ 'classic', 'gradient' ],
                            'selector' => '{{WRAPPER}} .single__menu__nav .wcf__menu li a .badge',
                        ]
                    );
                    $this->add_group_control(
                        Group_Control_Typography:: get_type(),
                        [
                            'name'     => 'badge_typography',
                            'selector' => '{{WRAPPER}} .single__menu__nav .wcf__menu li a .badge',
                        ]
                    );
                    $this->add_group_control(
                        Group_Control_Border:: get_type(),
                        [
                            'name'      => 'badge_border',
                            'label'     => esc_html__( 'Border', 'sassly-essential' ),
                            'selector'  => '{{WRAPPER}} .single__menu__nav .wcf__menu li a .badge',
                            'separator' => 'before',
                        ]
                    );
                    $this->add_responsive_control(
                        'badge_radius',
                        [
                            'label'      => esc_html__( 'Border Radius', 'sassly-essential' ),
                            'type'       => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em' ],
                            'selectors'  => [
                                '{{WRAPPER}} .single__menu__nav .wcf__menu li a .badge' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                        ]
                    );
                    $this->add_group_control(
                        Group_Control_Box_Shadow:: get_type(),
                        [
                            'name'     => 'badge_shadow',
                            'selector' => '{{WRAPPER}} .single__menu__nav .wcf__menu li a .badge',
                        ]
                    );
                    $this->add_responsive_control(
                        'badge_width',
                        [
                            'label'      => esc_html__( 'Width', 'sassly-essential' ),
                            'type'       => Controls_Manager::SLIDER,
                            'size_units' => [ 'px', '%' ],
                            'range'      => [
                                'px' => [
                                    'min'  => 0,
                                    'max'  => 1000,
                                    'step' => 1,
                                ],
                                '%' => [
                                    'min' => 0,
                                    'max' => 100,
                                ],
                            ],
                            'default' => [
                                'unit' => 'px',
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .single__menu__nav .wcf__menu li a .badge' => 'width: {{SIZE}}{{UNIT}};',
                            ],
                            'separator' => 'before',
                        ]
                    );
                    $this->add_responsive_control(
                        'badge_height',
                        [
                            'label'      => esc_html__( 'Height', 'sassly-essential' ),
                            'type'       => Controls_Manager::SLIDER,
                            'size_units' => [ 'px', '%' ],
                            'range'      => [
                                'px' => [
                                    'min'  => 0,
                                    'max'  => 1000,
                                    'step' => 1,
                                ],
                                '%' => [
                                    'min' => 0,
                                ],
                            ],
                            'default' => [
                                'unit' => 'px',
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .single__menu__nav .wcf__menu li a .badge' => 'height: {{SIZE}}{{UNIT}};',
                            ],
                        ]
                    );
                    $this->add_responsive_control(
                        'badge_margin',
                        [
                            'label'      => esc_html__( 'Margin', 'sassly-essential' ),
                            'type'       => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em' ],
                            'selectors'  => [
                                '{{WRAPPER}} .single__menu__nav .wcf__menu li a .badge' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                            'separator' => 'before',
                        ]
                    );
                    $this->add_responsive_control(
                        'badge_padding',
                        [
                            'label'      => esc_html__( 'Padding', 'sassly-essential' ),
                            'type'       => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em' ],
                            'selectors'  => [
                                '{{WRAPPER}} .single__menu__nav .wcf__menu li a .badge' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                        ]
                    );
                $this->end_controls_tab();
                $this->start_controls_tab(
                    'badge_hover_tab',
                    [
                        'label' => esc_html__( 'Hover', 'sassly-essential' ),
                    ]
                );
                    $this->add_control(
                        'hover_badge_color',
                        [
                            'label'     => esc_html__( 'Color', 'sassly-essential' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .single__menu__nav .wcf__menu li a:hover .badge' => 'color: {{VALUE}};',
                            ],
                            'separator' => 'before',
                        ]
                    );
                    $this->add_group_control(
                        Group_Control_Background:: get_type(),
                        [
                            'name'     => 'hover_badge_background',
                            'label'    => esc_html__( 'Background', 'sassly-essential' ),
                            'types'    => [ 'classic', 'gradient' ],
                            'selector' => '{{WRAPPER}} .single__menu__nav .wcf__menu li a:hover .badge',
                        ]
                    );
                    $this->add_group_control(
                        Group_Control_Border:: get_type(),
                        [
                            'name'      => 'hover_badge_border',
                            'label'     => esc_html__( 'Border', 'sassly-essential' ),
                            'selector'  => '{{WRAPPER}} .single__menu__nav .wcf__menu li a:hover .badge',
                            'separator' => 'before',
                        ]
                    );
                    $this->add_responsive_control(
                        'hover_badge_radius',
                        [
                            'label'      => esc_html__( 'Border Radius', 'sassly-essential' ),
                            'type'       => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em' ],
                            'selectors'  => [
                                '{{WRAPPER}} .single__menu__nav .wcf__menu li a:hover .badge' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                        ]
                    );
                    $this->add_group_control(
                        Group_Control_Box_Shadow:: get_type(),
                        [
                            'name'     => 'hover_badge_shadow',
                            'selector' => '{{WRAPPER}} .single__menu__nav .wcf__menu li a:hover .badge',
                        ]
                    );
                $this->end_controls_tab();
            $this->end_controls_tabs();
        $this->end_controls_section();
        /*----------------------------
            BADGE STYLE END
        -----------------------------*/
    }

    protected function render( $instance = [] ) {
        $settings = $this->get_settings_for_display();
        $id       = $this->get_id();        
        $this->add_render_attribute( 'wcf_menu_attr', 'class', 'wcf__menu__area wcf__menu__style__'.$settings['inline_menu_style'] );
        $menuargs = [
            'echo'        => false,
            'menu'        => isset( $settings['inline_menu_id'] ) ? $settings['inline_menu_id'] : 0,
            'menu_class'  => 'wcf__menu',
            'menu_id'     => 'menu-'. esc_attr($id),
            'fallback_cb' => '__return_empty_string',
            'container'   => '',
            'depth'       => isset($settings['menu_depth']) ? $settings['menu_depth'] : 1,
           
        ];
        
        $menuargs['items_wrap'] = '<ol id="%1$s" class="%2$s">%3$s</ol>';
        
        $nav_walker_default = [	
		    'custom_icon'                => $settings[ 'custom_direction' ],		   
		    'menu_down_icon'             =>  $settings[ 'menu_right_icon' ],
		    'has_right_arrow_icon'       => false,
		    'icon_left'       => isset($settings['icon_left']) && $settings['icon_left'] == 'yes' ? true : false,
		    'has_dropdown_arrow_icon'    => (isset($settings[ 'menu_right_icon' ][ 'value' ]) && $settings[ 'menu_right_icon' ][ 'value' ] !='') || (isset( $settings[ 'menu_right_icon' ]['value']) && is_array( $settings[ 'menu_right_icon' ]['value'] ) ) ? true : false
		];
        
        //if( 'yes' == $settings['show_menu_bedge'] ){
            $menuargs['walker'] = new WCF_Walker_Elementor_Footer_Nav($nav_walker_default);
        //}
        
        // General Menu.
        $menu_html = wp_nav_menu( $menuargs );

        ?>
            <div <?php echo $this->get_render_attribute_string('wcf_menu_attr'); ?> >
                <nav class = "single__menu__nav">
                    <?php
                        if( !empty( $menu_html ) ){
                            echo wp_kses_post( wp_nav_menu( $menuargs ) );
                        }
                    ?>
                </nav>
            </div>
        <?php
    }
}