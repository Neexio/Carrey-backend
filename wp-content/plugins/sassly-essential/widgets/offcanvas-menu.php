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

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

class Offcanvas_Menu extends Widget_Base {

	public function get_name() {
		return 'wcf--offcanvas-menu';
	}

	public function get_title() {
		return wcf_elementor_widget_concat_prefix( 'Animated Offcanvas' );
	}

	public function get_icon() {
		return 'wcf eicon-menu-bar';
	}

	public function get_categories() {
		return [ 'weal-coder-addon' ];
	}

	public function get_keywords() {
		return [ 'offcanvas', 'menu'  ];
	}
	
	public function get_style_depends() {		
		return [ 'sassly-header-offcanvas' ];
	}
	
	public function get_script_depends() {	   
		return [ 'wcf-offcanvas-menu' ];
	}
	
	public function logo_image_url( $size ) {
		$settings = $this->get_settings_for_display();
		if ( ! empty( $settings['custom_image']['url'] ) ) {
			$logo = wp_get_attachment_image_src( $settings['custom_image']['id'], $size, true );
			return $logo[0];
		} else {
			$light_logo      = sassly_IMG . '/lawyer-black-logo.png';		
			$logo            = sassly_option( 'offcanvas_logo', $light_logo ); 				
			return $logo;
		}
		
	}
	
	public function get_all_images_urls(){
	    $returns_data = [];
        $media_dir = SASSLY_ESSENTIAL_DIR_PATH . 'assets/images/bars/';
        $url_path = SASSLY_ESSENTIAL_ASSETS_URL .'images/bars/';
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
	
    protected function register_controls() {
    
        $this->start_controls_section(
			'section_content',
			[
				'label' => __( 'Settings', 'sassly-essential' ),
			]
		);
			
			$this->add_control(
				'menu_button_text',
				[
					'label' => esc_html__( 'Button Text', 'sassly-essential' ),
					'type' => \Elementor\Controls_Manager::TEXT,
					'default' => '',
					'placeholder' => esc_html__( 'Menu', 'sassly-essential' ),
					
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
					'label' => esc_html__('Bar', 'sassly-essential'),
					'type' => \SasslyEssentialApp\CustomControl\ImageSelector_Control::ImageSelector,
					'options' => $this->get_all_images_urls(),
					'bgcolor' => '#D2EAF1',
					'col' => 3,
					'default' => 'hamburger-icon-0.png',
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
				'sticky_bar',
				[
					'label'     => __( 'Sticky Bar', 'sassly-essential' ),
					'type'      => Controls_Manager::MEDIA,
					'dynamic'   => [
						'active' => true,
					]				
				]
			);
			
			$this->add_control(
				'content_source',
				[
					'label' => esc_html__( 'Content Source', 'sassly-essential' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'default' => 'preset',
					'options' => [
					
						'preset' => esc_html__( 'Preset', 'sassly-essential' ),
						'elementor_shortcode'  => esc_html__( 'Custom Shortcode', 'sassly-essential' ),
					
					]				
				]
			);	
			
			$this->add_control(
				'preset_style',
				[
					'label' => esc_html__( 'Preset Style', 'sassly-essential' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'default' => 'two',
					'options' => [						
						'two'       => esc_html__( 'One' , 'sassly-essential' ),
						'three'     => esc_html__( 'Two' , 'sassly-essential' ),
						'four'      => esc_html__( 'Three' , 'sassly-essential' ),
						'five'      => esc_html__( 'Four' , 'sassly-essential' ),
						'six'       => esc_html__( 'Five' , 'sassly-essential' ),						
						
					],
					'condition' => ['content_source' => ['preset']]
				]
			);	
			
			$this->add_control(
				'shortcode',
				[
					'label' => esc_html__( 'Elementor Template ShortCode', 'sassly-essential' ),
					'type' => \Elementor\Controls_Manager::TEXT,
					'default' => '',
					'placeholder' => esc_html__( '[WCF_ELEMENTOR_TPL id="1951"]', 'sassly-essential' ),
					'condition' => ['content_source' => ['elementor_shortcode']]
				]
			);
		
		$this->end_controls_section();
		$this->start_controls_section(
			'section_custom_content',
			[
				'label' => esc_html__( 'Custom Content', 'sassly-essential' ),
				'condition' => ['content_source' => ['preset']]
			]
		);
		
			$this->add_control(
				'show_logo',
				[
					'label' => esc_html__( 'Show Logo / Custom Image', 'sassly-essential' ),
					'type' => \Elementor\Controls_Manager::SWITCHER,
					'label_on' => esc_html__( 'Show', 'sassly-essential' ),
					'label_off' => esc_html__( 'Hide', 'sassly-essential' ),
					'return_value' => 'yes',
					'default' => 'yes',
					'condition' => ['preset_style' => ['two','four', 'six']]
				]
			);
		
			$this->add_control(
				'custom_image',
				[
					'label'     => __( 'Add Logo / Image', 'header-footer-elementor' ),
					'type'      => Controls_Manager::MEDIA,
					'dynamic'   => [
						'active' => true,
					],
					'default'   => [
						'url' => Utils::get_placeholder_image_src(),
					],
					'condition' => ['show_logo' => ['yes'], 'preset_style' => ['two','four','six']]
				]
			);
		
			$this->add_group_control(
				Group_Control_Image_Size::get_type(),
				[
					'name'    => 'site_logo_size',
					'label'   => __( 'Image Size', 'header-footer-elementor' ),
					'default' => 'medium',	
					'condition' => ['show_logo' => ['yes'],'preset_style' => ['two','four','six']]
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
	        
	        $repeater = new \Elementor\Repeater();

			$repeater->add_control(
				'list_title',
				[
					'label' => esc_html__( 'Title', 'sassly-essential' ),
					'type' => \Elementor\Controls_Manager::TEXT,
					'default' => esc_html__( 'List Title' , 'sassly-essential' ),
					'label_block' => true,
				]
			);
			
			$repeater->add_control(
				'list_content',
				[
					'label' => esc_html__( 'Content', 'sassly-essential' ),
					'type' => \Elementor\Controls_Manager::TEXTAREA,
					'default' => esc_html__( 'Email' , 'sassly-essential' ),
					'label_block' => true,
				]
			);

			$repeater->add_control(
				'list_type',
				[
					'label' => esc_html__( 'Content Type', 'sassly-essential' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'default' => '',
					'options' => [
						'' => esc_html__( 'Default', 'sassly-essential' ),
						'email' => esc_html__( 'Email', 'sassly-essential' ),
						'phone'  => esc_html__( 'Phone', 'sassly-essential' ),
						'address' => esc_html__( 'Address', 'sassly-essential' ),					
					],
				]
			);
			
			$repeater->add_control(
				'link',
				[
					'label' => esc_html__( 'Link Value', 'sassly-essential' ),
					'type' => \Elementor\Controls_Manager::TEXT,
					'default' => '#',
					'label_block' => true,
					'condition' => ['list_type' => ['email','phone']]
				]
			);
			
			$this->add_control(
				'contact_heading',
				[
					'label' => esc_html__( 'Contact Heading', 'sassly-essential' ),
					'type' => \Elementor\Controls_Manager::TEXT,
					'default' => 'Get in Touch',
					'label_block' => true,	
					'condition' => ['preset_style' => [ 'six']]
				]
			);
			

			$this->add_control(
				'contact_info',
				[
					'label' => esc_html__( 'Contact Info', 'sassly-essential' ),
					'type' => \Elementor\Controls_Manager::REPEATER,
					'fields' => $repeater->get_controls(),					
					'title_field' => '{{{ list_title }}}',
				]
			);
			
			$language = new \Elementor\Repeater();

			$language->add_control(
				'list_title',
				[
					'label' => esc_html__( 'Title', 'sassly-essential' ),
					'type' => \Elementor\Controls_Manager::TEXT,
					'default' => esc_html__( 'List Title' , 'sassly-essential' ),
					'label_block' => true,
				]
			);
		
			$language->add_control(
				'link',
				[
					'label' => esc_html__( 'Link Value', 'sassly-essential' ),
					'type' => \Elementor\Controls_Manager::TEXT,
					'default' => '#',
					'label_block' => true,					
				]
			);
			
			$this->add_control(
				'language_info',
				[
					'label' => esc_html__( 'Language Info', 'sassly-essential' ),
					'type' => \Elementor\Controls_Manager::REPEATER,
					'fields' => $language->get_controls(),					
					'title_field' => '{{{ list_title }}}',
					'condition' => ['preset_style' => ['two','five']]
				]
			);
		
			$follow = new \Elementor\Repeater();

			$follow->add_control(
				'list_title',
				[
					'label' => esc_html__( 'Title', 'sassly-essential' ),
					'type' => \Elementor\Controls_Manager::TEXT,
					'default' => esc_html__( 'List Title' , 'sassly-essential' ),
					'label_block' => true,
				]
			);
		
			$follow->add_control(
				'link',
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
			
			$follow->add_control(
				'icon',
				[
					'label' => esc_html__( 'Icon', 'sassly-essential' ),
					'type' => \Elementor\Controls_Manager::ICONS,
					'default' => [
						'value' => 'fas fa-circle',
						'library' => 'fa-solid',
					],
					'recommended' => [
						'fa-solid' => [
							'circle',
							'dot-circle',
							'square-full',
						],
						'fa-regular' => [
							'circle',
							'dot-circle',
							'square-full',
						],
					],
				]
			);			
			
			$follow->add_control(
				'list_color',
				[
					'label' => esc_html__( 'Color', 'sassly-essential' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{CURRENT_ITEM}} i' => 'color: {{VALUE}}',
						'{{CURRENT_ITEM}} svg' => 'fill: {{VALUE}}'
					],
				]
			);
			
			$follow->add_control(
				'list_hover_color',
				[
					'label' => esc_html__( 'Hover Color', 'sassly-essential' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{CURRENT_ITEM}}:hover i' => 'color: {{VALUE}}',
						'{{CURRENT_ITEM}}:hover svg' => 'fill: {{VALUE}}'
					],
				]
			);
			
			$follow->add_responsive_control(
				'width',
				[
					'label'              => __( 'Width', 'sassly-essential' ),
					'type'               => Controls_Manager::SLIDER,
					'default'            => [
						'unit' => 'px',
					],
					'tablet_default'     => [
						'unit' => 'px',
					],
					'mobile_default'     => [
						'unit' => 'px',
					],
					'size_units'         => [ 'px'],
					'range'              => [						
						'px' => [
							'min' => 1,
							'max' => 100,
						],
					
					],
					'selectors'          => [
						'{{CURRENT_ITEM}} svg' => 'width: {{SIZE}}{{UNIT}};',
						'{{CURRENT_ITEM}} i' => 'font-size: {{SIZE}}{{UNIT}};',
					],
					'frontend_available' => true,
				]
			);
			
			$this->add_control(
				'social_heading',
				[
					'label' => esc_html__( 'Follow Heading', 'sassly-essential' ),
					'type' => \Elementor\Controls_Manager::TEXT,
					'default' => esc_html__( 'Follow Me' , 'sassly-essential' ),
					'label_block' => true,
					'condition' => ['preset_style' => ['three','four','five', 'six']]
				]
			);	
			
			$this->add_control(
				'follow_info',
				[
					'label' => esc_html__( 'Follow Info', 'sassly-essential' ),
					'type' => \Elementor\Controls_Manager::REPEATER,
					'fields' => $follow->get_controls(),					
					'title_field' => '{{{ list_title }}}',
				]
			);
			
			$this->add_control(
				'copyright_texts',
				[
					'label' => esc_html__( 'Copyright', 'sassly-essential' ),
					'type' => \Elementor\Controls_Manager::WYSIWYG,
					'default' => '© Alrights reserved <br> by <a href="https://crowdyflow.com/" target="_blank">CrowdyFlow</a>',
					'placeholder' => esc_html__( 'Type your description here', 'sassly-essential' ),
					'condition' => ['preset_style' => ['two']]
				]
			);
		
		$this->end_controls_section();
		$this->start_controls_section(
			'section_close_area',
			[
				'label' => esc_html__( 'Close Button', 'sassly-essential' ),
			]
		);
		
			$this->add_control(
					'default_close_contentss',
					[
						'label'       => __( 'Default?', 'sassly-essential' ),
						'type'        => Controls_Manager::SWITCHER,
						'yes'         => __( 'Yes', 'sassly-essential' ),
						'no'          => __( 'No', 'sassly-essential' ),
						'default'     => 'yes',					
					]
			);
		
			$this->add_control(
				'close_text',
				[
					'label' => esc_html__( 'Close Text', 'sassly-essential' ),
					'type' => \Elementor\Controls_Manager::TEXT,
					'default' => 'close',
					'placeholder' => esc_html__( 'close text', 'sassly-essential' ),
					'condition' => ['default_close_contentss!' => ['yes']]
				]
			);
			
			$this->add_control(
				'close_icon',
				[
					'label' => esc_html__( 'Icon', 'sassly-essential' ),
					'type' => \Elementor\Controls_Manager::ICONS,
					'condition' => ['default_close_contentss!' => ['yes']],
					'default' => []
				]
			);
			
			$this->add_responsive_control(
				'close_position_type',
				[
					'label' => esc_html__( 'Position', 'sassly-essential' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'default' => '',
					'options' => [
						'' => esc_html__( 'Default', 'sassly-essential' ),
						'position: absolute;right: 50px;top: 50px; z-index: 1;' => esc_html__( 'Right Top', 'sassly-essential' ),
						'position: absolute;left: 50px;top: 50px; z-index: 1;' => esc_html__( 'Left Top', 'sassly-essential' ),
						'position: absolute;left: 50px;bottom: 50px; z-index: 1;' => esc_html__( 'Left Bottom', 'sassly-essential' ),		
						'position: absolute;right: 50px;bottom: 50px; z-index: 1;' => esc_html__( 'Right Bottom', 'sassly-essential' ),		
					],
					'selectors' => [
						'.wcf-offcanvas-gl-style .offcanvas-close__button-wrapper' => '{{VALUE}};',
					],
				]
			);
			
			$this->add_responsive_control(
				'close_button_position_left',
				[
					'label' => esc_html__( 'Left', 'sassly-essential' ),
					'type' => \Elementor\Controls_Manager::SLIDER,
					'size_units' => [ 'px', 'em', 'rem' ],
					'range' => [
						'px' => [
							'min' => -500,
							'max' => 900,
							'step' => 1,
						],					
					],				
					'selectors' => [
						'body .wcf-offcanvas-gl-style .offcanvas-close__button-wrapper' => 'left: {{SIZE}}{{UNIT}};',
					]				
				]
			);
			
			$this->add_responsive_control(
				'close_button_position_top',
				[
					'label' => esc_html__( 'Top', 'sassly-essential' ),
					'type' => \Elementor\Controls_Manager::SLIDER,
					'size_units' => [ 'px', 'em', 'rem' ],
					'range' => [
						'px' => [
							'min' => -900,
							'max' => 900,
							'step' => 1,
						],					
					],				
					'selectors' => [
						'body .wcf-offcanvas-gl-style .offcanvas-close__button-wrapper' => 'top: {{SIZE}}{{UNIT}};',
					],
				]
			);
			
			$this->add_responsive_control(
				'close_button_position_right',
				[
					'label' => esc_html__( 'Right', 'sassly-essential' ),
					'type' => \Elementor\Controls_Manager::SLIDER,
					'size_units' => [ 'px', 'em', 'rem' ],
					'range' => [
						'px' => [
							'min' => -900,
							'max' => 900,
							'step' => 1,
						],					
					],				
					'selectors' => [
						'body .wcf-offcanvas-gl-style .offcanvas-close__button-wrapper' => 'right: {{SIZE}}{{UNIT}};',
					],
				]
			);
			
			$this->add_responsive_control(
				'close_button_position_bottom',
				[
					'label' => esc_html__( 'Bottom', 'sassly-essential' ),
					'type' => \Elementor\Controls_Manager::SLIDER,
					'size_units' => [ 'px', 'em', 'rem' ],
					'range' => [
						'px' => [
							'min' => -500,
							'max' => 900,
							'step' => 1,
						],					
					],				
					'selectors' => [
						'body .wcf-offcanvas-gl-style .offcanvas-close__button-wrapper' => 'bottom: {{SIZE}}{{UNIT}};',
					],
				]
			);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_offcanvas_area',
			[
				'label' => esc_html__( 'Main Container', 'sassly-essential' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
			
			$this->add_responsive_control(
				'offcanvas_m_padding',
				[
					'label' => esc_html__( 'Padding', 'sassly-essential' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px' ],
					'selectors' => [
						'body .wcf-offcanvas-gl-style' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			
			$this->add_group_control(
				\Elementor\Group_Control_Background::get_type(),
				[
					'name' => 'offcanvas_m_ubackground',
					'types' => ['classic', 'gradient', 'video' ],
					'selector' => 'body .wcf-offcanvas-gl-style ,body .offcanvas__left-2',
				]
			);
			
			$this->add_group_control(
				\Elementor\Group_Control_Background::get_type(),
				[
					'name'      => 'offcanvas_m_s_ubackground',
					'label'     => 'Second Background',
					'types'     => ['classic', 'gradient', 'video' ],
					'selector'  => 'body .offcanvas__right-2',
					'condition' => ['preset_style' => ['two']]
				]
			);			
			
			$this->add_group_control(
				\Elementor\Group_Control_Border::get_type(),
				[
					'name' => 'offcanvas_mborder',
					'selector' => 'body .wcf-offcanvas-gl-style',
				]
			);
		
		$this->end_controls_section();	
		
		$this->start_controls_section(
			'section_offcanvas_open_btn',
			[
				'label' => esc_html__( 'Open Text Button', 'sassly-essential' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		
			$this->add_control(
				'open_btn_color',
				[
					'label' => esc_html__( 'Color', 'sassly-essential' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [							
						'.wcf--info-animated-offcanvas' => 'color: {{VALUE}}',				
						'.offcanvas__close-2 button span' => 'background-color: {{VALUE}}',				
						
					],
				]
			);
		
			$this->add_group_control(
				\Elementor\Group_Control_Typography::get_type(),
				[
					'name' => 'open_icon_typo',
					'selector' => '.wcf--info-animated-offcanvas',
				]
			);	
			
			$this->add_control(
				'bcustom_text_sshadow',
				[
					'label' => esc_html__( 'Text Shadow', 'sassly-essential' ),
					'type' => \Elementor\Controls_Manager::TEXT_SHADOW,
					'selectors' => [
						'.wcf--info-animated-offcanvas' => 'text-shadow: {{HORIZONTAL}}px {{VERTICAL}}px {{BLUR}}px {{COLOR}};',
					],
				]
			);
		
		$this->end_controls_section();	
		
		$this->start_controls_section(
			'section_offcanvas_close_i_btn',
			[
				'label' => esc_html__( 'Close Icon Button', 'sassly-essential' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		   
			$this->add_control(
				'open_close_dc_color',
				[
					'label' => esc_html__( 'Color', 'sassly-essential' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [						
						'body .offcanvas__close-2 button span' => 'background-color: {{VALUE}}',				
						'body .text-close-button span' => 'background-color: {{VALUE}}',				
						'body .text-close-button .bars span' => 'background: {{VALUE}}',				
					],
					'condition' => ['default_close_contentss' => ['yes']]
				]
			);
			
			$this->add_control(
				'open_close_drops_color',
				[
					'label' => esc_html__( 'Dropshadow Color', 'sassly-essential' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [						
						'body .shadow-close-button' => 'filter: drop-shadow(5px 5px 0 {{VALUE}});',			
							
					],
					'condition' => ['preset_style' => ['six']]
				]
			);
		
			
			$this->add_group_control(
				\Elementor\Group_Control_Background::get_type(),
				[
					'name' => 'menubackground',
					'types' => [ 'classic', 'gradient'],
					'label' => esc_html__( 'Icon Background', 'sassly-essential' ),
					'selector' => '.offcanvas-close__button-wrapper .off-close-icon svg, .offcanvas-close__button-wrapper .off-close-icon i ,body .offcanvas__close-2 button,body .text-close-button',
				]
			);
		
			$this->add_responsive_control(
				'close_button_min_width',
				[
					'label' => esc_html__( 'Icon Width', 'sassly-essential' ),
					'type' => \Elementor\Controls_Manager::SLIDER,
					'size_units' => [ 'px', 'em', 'rem', '%' ],
					'range' => [
						'px' => [
							'min' => 15,
							'max' => 900,
							'step' => 1,
						],					
					],
					'default' => [
						'unit' => 'px',
						'size' => 80,
					],
					'selectors' => [
						'body .wcf-offcanvas-gl-style .text-close-button svg' => 'width: {{SIZE}}{{UNIT}};',
						'body .wcf-offcanvas-gl-style .text-close-button i' => 'width: {{SIZE}}{{UNIT}};',
					]				
				]
			);
		
			$this->add_responsive_control(
				'close_button_min_height',
				[
					'label' => esc_html__( 'Icon Height', 'sassly-essential' ),
					'type' => \Elementor\Controls_Manager::SLIDER,
					'size_units' => [ 'px', 'em', 'rem', '%' ],
					'range' => [
						'px' => [
							'min' => 15,
							'max' => 900,
							'step' => 1,
						],					
					],
					'default' => [
						'unit' => 'px',
						'size' => 80,
					],
					'selectors' => [
						'body .wcf-offcanvas-gl-style .text-close-button svg' => 'height: {{SIZE}}{{UNIT}};',
						'body .wcf-offcanvas-gl-style .text-close-button i' => 'height: {{SIZE}}{{UNIT}};',
					]				
				]
			);
		
			$this->add_group_control(
				\Elementor\Group_Control_Border::get_type(),
				[
					'name' => 'close_bt_mborder',
					'selector' => '.offcanvas-close__button-wrapper .off-close-icon svg, .offcanvas-close__button-wrapper .off-close-icon i,body .text-close-button',
				]
			);
		
		
			$this->add_control(
				'close_btn_radius',
				[
					'label' => esc_html__( 'Border Radius', 'sassly-essential' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px' ],
					'selectors' => [
						'.offcanvas-close__button-wrapper .off-close-icon i' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						'.offcanvas-close__button-wrapper .off-close-icon svg' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						'.offcanvas-close__button-wrapper .text-close-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);		
		
			$this->add_responsive_control(
				'close_icon_padding',
				[
					'label' => esc_html__( 'Padding', 'sassly-essential' ),
					'type' => \Elementor\Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', 'em', 'rem' ],
					'selectors' => [
						'.offcanvas-close__button-wrapper .off-close-icon svg' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						'.offcanvas-close__button-wrapper .off-close-icon i' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

		
		$this->end_controls_section();	
		
		$this->start_controls_section(
			'section_offcanvas_close_btn',
			[
				'label' => esc_html__( 'Close Text Button', 'sassly-essential' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		
			$this->add_control(
				'close_btn_color',
				[
					'label' => esc_html__( 'Color', 'sassly-essential' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [							
						'.offcanvas-close__button-wrapper .text-close-button' => 'color: {{VALUE}}',				
						'.offcanvas-close__button-wrapper i' => 'color: {{VALUE}}',				
						'.offcanvas-close__button-wrapper svg' => 'fill: {{VALUE}}'				
					],
				]
			);
		
			$this->add_group_control(
				\Elementor\Group_Control_Typography::get_type(),
				[
					'name' => 'close_icon_typo',
					'selector' => '.offcanvas-close__button-wrapper i, .offcanvas-close__button-wrapper svg,.offcanvas-close__button-wrapper .text-close-button',
				]
			);	
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_offcanvas_menu',
			[
				'label' => esc_html__( 'Menu', 'sassly-essential' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
			$this->add_control(
				'nav_off_menucolor',
				[
					'label' => esc_html__( 'Color', 'sassly-essential' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [							
						'.wcf-offcanvas-gl-style .offcanvas-6__menu a' => 'background-image: linear-gradient(90deg, #AF89FF, #AF89FF 50%, {{VALUE}} 0);',		
						'.wcf-offcanvas-gl-style nav ul li a' => 'color: {{VALUE}}',		
						'.wcf-offcanvas-gl-style .offcanvas-3__menu li a' => 'background-image: linear-gradient(90deg,{{VALUE}}, {{VALUE}} 50%, {{VALUE}})'		
								
					],
				]
			);
			
			$this->add_control(
				'nav_off_h_menucolor',
				[
					'label' => esc_html__( 'Hover Color', 'sassly-essential' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [							
						'.wcf-offcanvas-gl-style .current-menu-item a' => 'color: {{VALUE}}',									
						'.wcf-offcanvas-gl-style .offcanvas-6__menu a:hover' => 'color: {{VALUE}}',									
						'.wcf-offcanvas-gl-style .offcanvas-5__menu a:hover' => 'color: {{VALUE}}',		
						'.wcf-offcanvas-gl-style .offcanvas-4__menu li a:hover' => 'color: {{VALUE}}',
						'.wcf-offcanvas-gl-style .offcanvas__menu-2 li:hover>a' => 'color: {{VALUE}}',
						'.wcf-offcanvas-gl-style .offcanvas__menu-2 li:hover>a::before' => 'background-color: {{VALUE}}',
						'.wcf-offcanvas-gl-style .offcanvas-3__menu li:hover>a' => 'background-image: linear-gradient(90deg,{{VALUE}}, {{VALUE}} 50%, {{VALUE}})',
						'.wcf-offcanvas-gl-style .offcanvas-3__menu li:before' => 'background: {{VALUE}}',
					],
				]
			);
		
			$this->add_group_control(
				\Elementor\Group_Control_Typography::get_type(),
				[
					'name' => 'nav_off_menu_close',
					'selector' => '.wcf-offcanvas-gl-style nav ul li a',
				]
			);
			$this->add_responsive_control(
				'menu_hht_auto',
				[
					'label' => esc_html__( 'Height Auto', 'sassly-essential' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'default' => '',
					'options' => [
						'' => esc_html__( 'No', 'sassly-essential' ),
						'auto' => esc_html__( 'Yes', 'sassly-essential' ),					
				
					],
					'selectors' => [
						'body .wcf-offcanvas-gl-style .offcanvas__menu-2' => 'height: {{VALUE}} !important;',
					],
				]
			);
			//.offcanvas__menu-2
		$this->end_controls_section();
		$this->start_controls_section(
			'section_offcanvas_contact',
			[
				'label' => esc_html__( 'Contact', 'sassly-essential' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		
		$this->add_responsive_control(
			'contact_display',
			[
				'label' => esc_html__( 'Display', 'sassly-essential' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => '',
				'options' => [
					'' => esc_html__( 'Default', 'sassly-essential' ),
					'none' => esc_html__( 'None', 'sassly-essential' ),					
			
				],
				'selectors' => [
					'body .wcf-offcanvas-gl-style .offcanvas__contact' => 'display: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'nav_off_contact_color',
			[
				'label' => esc_html__( 'Heading Color', 'sassly-essential' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [							
					'body .wcf-offcanvas-gl-style .offcanvas-6__meta-title' => 'color: {{VALUE}};',		
					'body .wcf-offcanvas-gl-style .offcanvas__contact li p' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'nav_off_contact_h_typho',
				'selector' => 'body .wcf-offcanvas-gl-style .offcanvas-6__meta-title,body .wcf-offcanvas-gl-style .offcanvas__contact li p',
			]
		);
		
		
		$this->add_control(
			'nav_off_contact__color',
			[
				'label' => esc_html__( 'Body Color', 'sassly-essential' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [							
					'body .wcf-offcanvas-gl-style .offcanvas-6__meta li' => 'color: {{VALUE}};',		
					'body .wcf-offcanvas-gl-style .offcanvas-4__meta li' => 'color: {{VALUE}};',		
					'body .wcf-offcanvas-gl-style .offcanvas-3__meta li' => 'color: {{VALUE}};',		
					'body .wcf-offcanvas-gl-style .offcanvas__contact li a' => 'color: {{VALUE}};',		
					'body .wcf-offcanvas-gl-style .offcanvas__contact li span' => 'color: {{VALUE}};',		
								
				],
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'nav_off_contact__typho',
				'selector' => 'body .wcf-offcanvas-gl-style .offcanvas__contact li a,body .wcf-offcanvas-gl-style .offcanvas__contact li span,body .wcf-offcanvas-gl-style .offcanvas-6__meta li,.wcf-offcanvas-gl-style .offcanvas-4__meta li,body .wcf-offcanvas-gl-style .offcanvas-3__meta li',
			]
		);
		
		$this->end_controls_section();	
		
		$this->start_controls_section(
			'section_offcanvas_social_contact',
			[
				'label' => esc_html__( 'Social / Follow', 'sassly-essential' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		
			$this->add_control(
				'nav_off_social_h_color',
				[
					'label' => esc_html__( 'Heading Color', 'sassly-essential' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [							
						'body .wcf-offcanvas-gl-style .offcanvas-6__social-title' => 'color: {{VALUE}};',		
						'body .wcf-offcanvas-gl-style .offcanvas-4__social-title' => 'color: {{VALUE}};',		
						'body .wcf-offcanvas-gl-style .offcanvas-3__social .title' => 'color: {{VALUE}};',		
						'body .wcf-offcanvas-gl-style .offcanvas__follow-2 a' => 'color: {{VALUE}};',		
									
					],
				]
			);
			
			$this->add_group_control(
				\Elementor\Group_Control_Typography::get_type(),
				[
					'name' => 'nav_off_social_h_typho',
					'selector' => '.wcf-offcanvas-gl-style .offcanvas__follow-2 a,.wcf-offcanvas-gl-style .offcanvas-6__social-title,.wcf-offcanvas-gl-style .offcanvas-4__social-title,.wcf-offcanvas-gl-style .offcanvas-3__social .title',
				]
			);		
		
		$this->end_controls_section();	
		$this->start_controls_section(
			'section_offcanvas_copy_contact',
			[
				'label' => esc_html__( 'Copyright , Search , Lang', 'sassly-essential' ),
				'tab'   => Controls_Manager::TAB_STYLE,
				'condition' => ['preset_style' => ['two']]
			]
		);
		
		$this->add_responsive_control(
			'footer_c_display',
			[
				'label' => esc_html__( 'Copyright Display', 'sassly-essential' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => '',
				'options' => [
					'' => esc_html__( 'Default', 'sassly-essential' ),
					'none' => esc_html__( 'None', 'sassly-essential' ),					
			
				],
				'selectors' => [
					'body .wcf-offcanvas-gl-style .offcanvas__footer-2 p' => 'display: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'nav_off_copyright_color',
			[
				'label' => esc_html__( 'Copyright Color', 'sassly-essential' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [							
					'.wcf-offcanvas-gl-style .offcanvas__footer-2 p' => 'color: {{VALUE}};',	
				],
			]
		);
		
		$this->add_control(
			'nav_off_copyright_link_color',
			[
				'label' => esc_html__( 'Copyright Link Color', 'sassly-essential' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [							
					'.wcf-offcanvas-gl-style .offcanvas__footer-2 p a' => 'color: {{VALUE}};',	
								
				],
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'nav_offcopyright_h_typho',
				'selector' => '.wcf-offcanvas-gl-style .offcanvas__footer-2 p',
			]
		);
		
		$this->add_responsive_control(
			'footer_s_display',
			[
				'label' => esc_html__( 'Search Display', 'sassly-essential' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => '',
				'options' => [
					'' => esc_html__( 'Default', 'sassly-essential' ),
					'none' => esc_html__( 'None', 'sassly-essential' ),					
			
				],
				'selectors' => [
					'body .wcf-offcanvas-gl-style form' => 'display: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'nav_off_search_color',
			[
				'label' => esc_html__( 'Search Input Color', 'sassly-essential' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [							
					'.wcf-offcanvas-gl-style .offcanvas__footer-2 form button' => 'color: {{VALUE}};',		
					'.wcf-offcanvas-gl-style .offcanvas__footer-2 .default-search__again-form form input' => 'color: {{VALUE}};border-color:{{VALUE}}',		
					'.wcf-offcanvas-gl-style .offcanvas__footer-2 .default-search__again-form form input::placeholder' => 'color: {{VALUE}};',		
					'.wcf-offcanvas-gl-style .offcanvas__footer-2 .default-search__again-form form input::-ms-input-placeholder' => 'color: {{VALUE}};',		
				],
			]
		);
		
		$this->add_responsive_control(
			'footer_l_display',
			[
				'label' => esc_html__( 'Lang Display', 'sassly-essential' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => '',
				'options' => [
					'' => esc_html__( 'Default', 'sassly-essential' ),
					'none' => esc_html__( 'None', 'sassly-essential' ),					
			
				],
				'selectors' => [
					'body .wcf-offcanvas-gl-style .offcanvas__lang .language' => 'display: {{VALUE}};',
				],
			]
		);
		
		
		$this->add_control(
			'nav_off_lang_color',
			[
				'label' => esc_html__( 'Language Color', 'sassly-essential' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [							
					'.wcf-offcanvas-gl-style .offcanvas__lang .language li a' => 'color: {{VALUE}};',		
					'.wcf-offcanvas-gl-style .offcanvas__lang .language li a::after' => 'background-color: {{VALUE}};',		
				
								
				],
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'nav_offcopyright_lang_typho',
				'selector' => '.wcf-offcanvas-gl-style .offcanvas__lang .language li a',
			]
		);
		
		$this->end_controls_section();	
		
		$this->start_controls_section(
			'section_svg_style',
			[
				'label' => __( 'Svg', 'sassly-essential' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		
			$this->add_control(
				'icon_svg_width',
				[
					'label'      => esc_html__( 'Width', 'sassly-essential' ),
					'type'       => \Elementor\Controls_Manager::SLIDER,
					'size_units' => [ 'px', '%', 'em', 'rem' ],
					'range'      => [
						'px' => [
							'min'  => 0,
							'max'  => 200,
							'step' => 1,
						]
					],	
					'selectors' => [
						'.offcanvas__social__links svg' => 'width: {{SIZE}}{{UNIT}};',
					],
					'default' => [
						'unit' => 'px',
						'size' => 14,
					],
				]
			);
	
			$this->add_control(
				'icon_svg_height',
				[
					'label'      => esc_html__( 'Height', 'sassly-essential' ),
					'type'       => \Elementor\Controls_Manager::SLIDER,
					'size_units' => [ 'px', '%', 'em', 'rem' ],
					'range'      => [
						'px' => [
							'min'  => 0,
							'max'  => 200,
							'step' => 5,
						]
					],
	
					'selectors' => [
						'.offcanvas__social__links svg' => 'height: {{SIZE}}{{UNIT}};',
					],
				]
			);
			
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_right_section',
			[
				'label' => __( 'Offcanvas Right', 'sassly-essential' ),
				'tab'   => Controls_Manager::TAB_STYLE,
				'condition' => ['preset_style' => ['two']]
			]
		);
		
			$this->add_responsive_control(
				'offcan_right_margin',
				[
					'label' => esc_html__( 'Padding', 'sassly-essential' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px' ],
					'selectors' => [
						'body .offcanvas__right-2' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
					],
				]
			);
		
			$this->add_group_control(
				Group_Control_Background::get_type(),
				[
					'name' => 'rigtut_bg',
					'types' => [ 'classic', 'gradient' ],
					'selector' => 'body .offcanvas__right-2',
				]
			);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_left_section',
			[
				'label' => __( 'Offcanvas Left', 'sassly-essential' ),
				'tab'   => Controls_Manager::TAB_STYLE,
				'condition' => ['preset_style' => ['two']]
			]
		);
		
			$this->add_responsive_control(
				'offcan_left_margin',
				[
					'label' => esc_html__( 'Padding', 'sassly-essential' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px' ],
					'selectors' => [
						'body .offcanvas__left-2' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
					],
				]
			);
		
			$this->add_group_control(
				Group_Control_Background::get_type(),
				[
					'name' => 'lefttut_bg',
					'types' => [ 'classic', 'gradient' ],
					'selector' => 'body .offcanvas__left-2',
				]
			);
			
			$this->add_responsive_control(
				'leftio_display',
				[
					'label' => esc_html__( 'Display', 'sassly-essential' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'default' => '',
					'options' => [
						'' => esc_html__( 'Default', 'sassly-essential' ),
						'none' => esc_html__( 'None', 'sassly-essential' ),					
				
					],
					'selectors' => [
						'body .wcf-offcanvas-gl-style .offcanvas__left-2' => 'display: {{VALUE}};',
					],
				]
			);
		
		$this->end_controls_section();
      
	}
	
	protected function render() {	
        $settings   = $this->get_settings_for_display();  
        
        $bar = '';
        
        if($settings['bar'] !=''){
			$bar = SASSLY_ESSENTIAL_ASSETS_URL .'images/bars/'.$settings['bar'];  
        }
	         
        if($settings['custom_bar'] == 'yes' && isset($settings['custom_bar_image']['url'])){
            $bar = $settings['custom_bar_image']['url'];
        }
        
        $contact_info       = $settings[ 'contact_info' ];
        $menu_selected      = $settings[ 'menu_selected' ];
        $size               = $settings[ 'site_logo_size_size' ];
        $preset_style       = $settings[ 'preset_style' ];       
       
		?>
		<?php if($settings['sticky_bar']['url'] !=''){ ?>
			<style>
				.wcf--info-animated-offcanvas .wcf-sticky-bar,			
				.wcf-is-sticky .wcf--info-animated-offcanvas img
				{
				    display: none;
				}				
				.wcf-is-sticky .wcf--info-animated-offcanvas .wcf-sticky-bar
				{
				    display: block !important;
				}
			</style>
		<?php } ?>
		<?php if($settings['content_source'] === 'preset' && file_exists(SASSLY_ESSENTIAL_DIR_PATH."widgets/offcanvas-preset/content-$preset_style".'.php')){ ?>
				<?php include_once(SASSLY_ESSENTIAL_DIR_PATH."widgets/offcanvas-preset/content-$preset_style".'.php'); ?>
			<?php } else{  ?>
				<?php include_once(SASSLY_ESSENTIAL_DIR_PATH."widgets/offcanvas-preset/content-elementor.php"); ?>            
			<?php } ?>
		<?php
	}
}