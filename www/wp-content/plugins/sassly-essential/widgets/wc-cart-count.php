<?php

namespace SasslyEssentialApp\Widgets;

use Elementor\Controls_Manager;
use Elementor\Control_Media;
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

if ( ! defined( 'ABSPATH' ) ) {
	exit;   // Exit if accessed directly.
}

class WC_Cart_Count extends \Elementor\Widget_Base {

	public function get_name() {
		return 'wcf--cart-count';
	}

	public function get_title() {
		return wcf_elementor_widget_concat_prefix( 'Cart Count' );
	}

	public function get_icon() {
		return 'wcf eicon-cart';
	}

	public function get_categories() {
		return [ 'weal-coder-addon' ];
	}

	public function get_keywords() {
		return [ 'wc', 'cart','count', 'header' ];
	}
	
	public function register_content_controls(){
        $this->start_controls_section(
			'section_content',
			[
				'label' => __( 'Settings', 'sassly-essential' ),
			]
		);
		
		$this->add_control(
			'cart_text',
			[
				'label' => esc_html__( 'Cart Content', 'sassly-essential' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'Cart', 'sassly-essential' ),
				'placeholder' => esc_html__( 'Type your cart text here', 'sassly-essential' ),
			]
		);		
		
		$this->add_control(
			'cart_icon',
			[
				'label' => esc_html__( 'Icon', 'sassly-essential' ),
				'type' => \Elementor\Controls_Manager::ICONS,
				'default' => [
					'value' => 'fa-solid fa-cart-shopping',
					'library' => 'fa-solid',
				],
			]
		);
		
		$this->add_control(
			'cart_link',
			[
				'label' => esc_html__( 'Link', 'sassly-essential' ),
				'type' => \Elementor\Controls_Manager::URL,					
				'label_block' => true,
			]
		);
		
		$this->end_controls_section();
       
	}
	
	public function register_style_controls(){
	
        $this->start_controls_section(
            'section_style_container',
            [
                'label' => esc_html__( 'Container', 'sassly-essential' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );	
                
            $this->start_controls_tabs( 'container' );
        
                $this->start_controls_tab( 'arrownormal',
                    [
                        'label' => esc_html__( 'Normal', 'sassly-essential' ),
                    ]
                );
                
                $this->add_group_control(
                    \Elementor\Group_Control_Background::get_type(),
                    [
                        'name' => 'container_background',
                        'types' => [ 'classic', 'gradient' ],
                        'selector' => '{{WRAPPER}} a',
                    ]
                );
                
                $this->add_group_control(
                    \Elementor\Group_Control_Border::get_type(),
                    [
                        'name' => 'container_border',
                        'selector' => '{{WRAPPER}} a',
                    ]
                );
                
                $this->add_control(
                    'container_border_rad',
                    [
                        'label' => esc_html__( 'Border Radius', 'textdomain' ),
                        'type' => \Elementor\Controls_Manager::SLIDER,
                        'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
                        'range' => [
                            'px' => [
                                'min' => 0,
                                'max' => 1000,
                                'step' => 5,
                            ],
                            '%' => [
                                'min' => 0,
                                'max' => 100,
                            ],
                        ],                        
                        'selectors' => [
                            '{{WRAPPER}} a' => 'border-radius: {{SIZE}}{{UNIT}};',
                        ],
                    ]
                );
                
                $this->add_control(
                    'container_padding',
                    [
                        'label' => esc_html__( 'Padding', 'sassly-essential' ),
                        'type' => \Elementor\Controls_Manager::DIMENSIONS,
                        'size_units' => [ 'px', '%', 'em', 'rem'],
                        'selectors' => [
                            '{{WRAPPER}} a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        ],
                    ]
                );
                
                $this->end_controls_tab();
        
                $this->start_controls_tab( 'iconhover',
                    [
                        'label' => esc_html__( 'Hover', 'sassly-essential' ),
                    ]
                );
        
                $this->add_group_control(
                    \Elementor\Group_Control_Background::get_type(),
                    [
                        'name' => 'container_h_background',
                        'types' => [ 'classic', 'gradient' ],
                        'selector' => '{{WRAPPER}} a:hover',
                    ]
                );
                
                $this->add_group_control(
                    \Elementor\Group_Control_Border::get_type(),
                    [
                        'name' => 'container_h_border',
                        'selector' => '{{WRAPPER}} a:hover',
                    ]
                );
                
                $this->end_controls_tab();
        
            $this->end_controls_tabs();            
        $this->end_controls_section();
        
        // icon 
        $this->start_controls_section(
            'section_style_icon',
            [
                'label' => esc_html__( 'Icon', 'sassly-essential' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );	
                
            $this->start_controls_tabs( 'icon' );
        
                $this->start_controls_tab( 'icon_normal',
                    [
                        'label' => esc_html__( 'Normal', 'sassly-essential' ),
                    ]
                );
                
                    $this->add_control(
                        'icon_color',
                        [
                            'label' => esc_html__( 'Color', 'sassly-essential' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} a i' => 'color: {{VALUE}}',
                            ],
                        ]
                    );
                    
                    $this->add_group_control(
                        \Elementor\Group_Control_Typography::get_type(),
                        [
                            'name' => 'iocn_typography',
                            'selector' => '{{WRAPPER}} a',
                        ]
                    );
                    
                    $this->add_control(
                        'iconmargin',
                        [
                            'label' => esc_html__( 'Margin', 'sassly-essential' ),
                            'type' => \Elementor\Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em', 'rem' ],
                            'selectors' => [
                                '{{WRAPPER}} a i' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                                '{{WRAPPER}} a svg' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                        ]
                    );
              
                $this->end_controls_tab();
        
                $this->start_controls_tab( 'icona_hover',
                    [
                        'label' => esc_html__( 'Hover', 'sassly-essential' ),
                    ]
                ); 
                
                $this->add_control(
                    'icon_h_color',
                    [
                        'label' => esc_html__( 'Color', 'sassly-essential' ),
                        'type' => \Elementor\Controls_Manager::COLOR,
                        'selectors' => [
                            '{{WRAPPER}} a:hover i' => 'color: {{VALUE}}',
                        ],
                    ]
                );                
                
                $this->end_controls_tab();
        
            $this->end_controls_tabs();            
        $this->end_controls_section();
        // text
        
        $this->start_controls_section(
            'section_style_text',
            [
                'label' => esc_html__( 'Text', 'sassly-essential' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );	
                
            $this->start_controls_tabs( 'text' );
        
                $this->start_controls_tab( 'text_normal',
                    [
                        'label' => esc_html__( 'Normal', 'sassly-essential' ),
                    ]
                );
                
                    $this->add_control(
                        'text_color',
                        [
                            'label' => esc_html__( 'Color', 'sassly-essential' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} a .wcf-text' => 'color: {{VALUE}}',
                            ],
                        ]
                    );
                    
                    $this->add_group_control(
                        \Elementor\Group_Control_Text_Stroke::get_type(),
                        [
                            'name' => 'text_stroke',
                            'selector' => '{{WRAPPER}} a .wcf-text',
                        ]
                    );
                    
                    $this->add_group_control(
                        \Elementor\Group_Control_Text_Shadow::get_type(),
                        [
                            'name' => 'text_shadow',
                            'selector' => '{{WRAPPER}} a .wcf-text',
                        ]
                    );
                    
                    $this->add_group_control(
                        \Elementor\Group_Control_Typography::get_type(),
                        [
                            'name' => 'text_typography',
                            'selector' => '{{WRAPPER}} a .wcf-text',
                        ]
                    );
                    
                    $this->add_control(
                        'text_margin',
                        [
                            'label' => esc_html__( 'Margin', 'sassly-essential' ),
                            'type' => \Elementor\Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em', 'rem' ],
                            'selectors' => [
                                '{{WRAPPER}} a .wcf-text' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',                               
                            ],
                        ]
                    );
              
                $this->end_controls_tab();
        
                $this->start_controls_tab( 'text_hover',
                    [
                        'label' => esc_html__( 'Hover', 'sassly-essential' ),
                    ]
                ); 
                
                    $this->add_control(
                        'text_h_color',
                        [
                            'label' => esc_html__( 'Color', 'sassly-essential' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} a:hover .wcf-text' => 'color: {{VALUE}}',
                            ],
                        ]
                    );                
                
                $this->end_controls_tab();
        
            $this->end_controls_tabs();            
        $this->end_controls_section();
        
        // count
        $this->start_controls_section(
            'section_style_counter',
            [
                'label' => esc_html__( 'Cart Count', 'sassly-essential' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );	
                
            $this->start_controls_tabs( 'count' );
        
                $this->start_controls_tab( 'count_normal',
                    [
                        'label' => esc_html__( 'Normal', 'sassly-essential' ),
                    ]
                );
                
                    $this->add_control(
                        'count_color',
                        [
                            'label' => esc_html__( 'Color', 'sassly-essential' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} a .wcf-wc-cart-fragment-content' => 'color: {{VALUE}}',
                            ],
                        ]
                    );                   
                    
                    $this->add_group_control(
                        \Elementor\Group_Control_Typography::get_type(),
                        [
                            'name' => 'count_typography',
                            'selector' => '{{WRAPPER}} a .wcf-wc-cart-fragment-content',
                        ]
                    );
                    
                    $this->add_control(
                        'txt_border_rad',
                        [
                            'label' => esc_html__( 'Border Radius', 'textdomain' ),
                            'type' => \Elementor\Controls_Manager::SLIDER,
                            'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
                            'range' => [
                                'px' => [
                                    'min' => 0,
                                    'max' => 1000,
                                    'step' => 5,
                                ],
                                '%' => [
                                    'min' => 0,
                                    'max' => 100,
                                ],
                            ],                        
                            'selectors' => [
                                '{{WRAPPER}}  a .wcf-wc-cart-fragment-content' => 'border-radius: {{SIZE}}{{UNIT}};',
                            ],
                        ]
                    );
                    
                    $this->add_group_control(
                        \Elementor\Group_Control_Background::get_type(),
                        [
                            'name' => 'count_background',
                            'types' => [ 'classic', 'gradient' ],
                            'selector' => '{{WRAPPER}} a .wcf-wc-cart-fragment-content',
                        ]
                    );                    
                   
                    $this->add_responsive_control(
                        'count_margin',
                        [
                            'label' => esc_html__( 'Margin', 'sassly-essential' ),
                            'type' => \Elementor\Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em', 'rem' ],
                            'selectors' => [
                                '{{WRAPPER}} a .wcf-wc-cart-fragment-content' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',                               
                            ],
                        ]
                    );
                    
                    $this->add_responsive_control(
                        'count_padding',
                        [
                            'label' => esc_html__( 'Padding', 'sassly-essential' ),
                            'type' => \Elementor\Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em', 'rem' ],
                            'selectors' => [
                                '{{WRAPPER}} a .wcf-wc-cart-fragment-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',                               
                            ],
                        ]
                    );
                    
                    $this->add_control(
                        'popover-toggle',
                        [
                            'label' => esc_html__( 'Position', 'sassly-essential' ),
                            'type' => \Elementor\Controls_Manager::POPOVER_TOGGLE,
                            'label_off' => esc_html__( 'Default', 'sassly-essential' ),
                            'label_on' => esc_html__( 'Custom', 'sassly-essential' ),
                            'return_value' => 'yes',
                        ]
                    );
            
                    $this->start_popover();
                    
                    $this->add_control(
                        'icon_position',
                        [
                            'label' => esc_html__( 'Icon Position', 'textdomain' ),
                            'type' => \Elementor\Controls_Manager::SELECT,
                            'default' => '',
                            'options' => [
                                '' => esc_html__( 'Default', 'textdomain' ),
                                'absolute' => esc_html__( 'Absolute', 'textdomain' ),
                                'relative'  => esc_html__( 'Relative', 'textdomain' ),                          
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .wcf-wc-cart-fragment-content' => 'position: {{VALUE}};',
                            ],
                        ]
                    );
            
                    $this->add_responsive_control(
                        'popover_pos_left',
                        [
                            'label' => esc_html__( 'Left', 'sassly-essential' ),
                            'type' => \Elementor\Controls_Manager::SLIDER,
                            'size_units' => [ 'px', '%', 'em', 'rem' ],
                            'range' => [
                                'px' => [
                                    'min' => -300,
                                    'max' => 300,
                                    'step' => 1,
                                ],
                                '%' => [
                                    'min' => 0,
                                    'max' => 100,
                                ],
                            ],                           
                            'selectors' => [
                                '{{WRAPPER}} .wcf-wc-cart-fragment-content' => 'left: {{SIZE}}{{UNIT}};',
                            ],
                        ]
                    );
                    
                    $this->add_responsive_control(
                        'popover_pos_top',
                        [
                            'label' => esc_html__( 'Top', 'sassly-essential' ),
                            'type' => \Elementor\Controls_Manager::SLIDER,
                            'size_units' => [ 'px', '%', 'em', 'rem' ],
                            'range' => [
                                'px' => [
                                    'min' => -300,
                                    'max' => 300,
                                    'step' => 1,
                                ],
                                '%' => [
                                    'min' => 0,
                                    'max' => 100,
                                ],
                            ],                           
                            'selectors' => [
                                '{{WRAPPER}} .wcf-wc-cart-fragment-content' => 'top: {{SIZE}}{{UNIT}};',
                            ],
                        ]
                    );
            
                    $this->end_popover();
              
                $this->end_controls_tab();
        
                $this->start_controls_tab( 'count_hover',
                    [
                        'label' => esc_html__( 'Hover', 'sassly-essential' ),
                    ]
                ); 
                
                    $this->add_control(
                        'count_h_color',
                        [
                            'label' => esc_html__( 'Color', 'sassly-essential' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} a:hover .wcf-wc-cart-fragment-content' => 'color: {{VALUE}}',
                            ],
                        ]
                    ); 
                    
                    $this->add_group_control(
                        \Elementor\Group_Control_Background::get_type(),
                        [
                            'name' => 'count_h_background',
                            'types' => [ 'classic', 'gradient' ],
                            'selector' => '{{WRAPPER}} a:hover .wcf-wc-cart-fragment-content',
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
        
        if ( !class_exists( 'woocommerce' ) ) { return false; } 
        
        if ( ! empty( $settings['cart_link']['url'] ) ) {
			$this->add_link_attributes( 'cart_link', $settings['cart_link'] );
		}
		
		$this->add_render_attribute(
            'cart_link',
            [                
                'class' => [ 'position-relative' ]             
            ]
        );

		?>
        <div class="health-header__cart-2">
            <a <?php echo $this->get_render_attribute_string( 'cart_link' ); ?>>            
            <?php \Elementor\Icons_Manager::render_icon( $settings['cart_icon'], [ 'aria-hidden' => 'true' ] ); ?>
            <div class="wcf-text d-inline"><?php echo $settings[ 'cart_text' ] ?></div>
            <span class="wcf-wc-cart-fragment-content d-inline">
            <?php echo WC()->cart !==null ? str_pad(WC()->cart->get_cart_contents_count(), 2, "0", STR_PAD_LEFT) : '00'; ?>
            </span></a>           
        </div>      
		<?php
	}
}