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

/**
 * Landing_Page
 *
 * Landing_Page.
 *
 * @since 1.3.0
 */
class Landing_Page extends Widget_Base {

	/**
	 * Retrieve the widget name.
	 *
	 * @since 1.3.0
	 *
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'wcf--site-landing-page';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @since 1.3.0
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return wcf_elementor_widget_concat_prefix( 'Side Header' );
	}

	/**
	 * Retrieve the widget icon.
	 *
	 * @since 1.3.0
	 *
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'wcf eicon-text';
	}

	/**
	 * Retrieve the list of categories the widget belongs to.
	 *
	 * Used to determine where to display the widget in the editor.
	 *
	 * Note that currently Elementor supports only one category.
	 * When multiple categories passed, Elementor uses the first one.
	 *
	 * @since 1.3.0
	 *
	 * @access public
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return [ 'weal-coder-addon' ];
	}
	
	public function get_keywords() {
		return [ 'landing', 'page' , 'scroll' , 'menu', 'navigation'];
	}
	
	public function get_style_depends() {		
		return [ 'sassly-landing-page' ];
	}
	
	public function get_script_depends() {	   
		return [ 'wcf-landing-page' ];
	}

	/**
	 * Register Landing Page controls.
	 *
	 * @since 1.5.7
	 * @access protected
	 */
	protected function register_controls() {
		$this->register_content_site_logo_controls();
		$this->start_controls_section(
			'content_style_m_section',
			[
				'label' => esc_html__( 'Main Container', 'sassly-essential' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

			$this->add_group_control(
				\Elementor\Group_Control_Background::get_type(),
				[
					'name' => 'main_background',
					'types' => [ 'classic', 'gradient'],
					'selector' => '{{WRAPPER}} .header__area-2',
				]
			);
			
			$this->add_responsive_control(
				'wcfin_se_pg',
				[
					'label' => esc_html__( 'Main Padding', 'sassly-essential' ),
					'type' => \Elementor\Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em', 'rem' ],					
					'selectors' => [
						'{{WRAPPER}} .header__inner-2' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

		$this->end_controls_section();
		
		$this->register_site_logo_styling_controls();
		$this->register_site_logo_caption_styling_controls();
		
		$this->start_controls_section(
			'contyle_search_section',
			[
				'label' => esc_html__( 'Search', 'sassly-essential' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,			
			]
		);
		
		$this->add_control(
			'n_search_color',
			[
				'label' => esc_html__( 'Search Input Color', 'sassly-essential' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [							
					'{{WRAPPER}} .header__search-2 .icon-search' => 'color: {{VALUE}};',		
					'{{WRAPPER}} .header__search-2 input' => 'color: {{VALUE}};',		
					'{{WRAPPER}} .header__search-2 input' => 'border-bottom: {{VALUE}};',		
					'{{WRAPPER}} .header__search-2 input' => 'color: {{VALUE}};border-color:{{VALUE}}',		
					'{{WRAPPER}} .header__search-2 input::placeholder' => 'color: {{VALUE}};',		
					'{{WRAPPER}} .header__search-2 inputt::-ms-input-placeholder' => 'color: {{VALUE}};',		
				],
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'nosearch__typho',
				'selector' => '{{WRAPPER}} .header__search-2 input',
			]
		);
		
		$this->add_control(
			'button_iucon_size',
			[
				'label' => esc_html__( 'Icon Size', 'sassly-essential' ),
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
					'{{WRAPPER}} .header__area-2 .icon-search *' => 'font-size: {{SIZE}}{{UNIT}};',					
				],
			]
		);
		
		$this->add_responsive_control(
			'wcfin_serch_pg',
			[
				'label' => esc_html__( 'Main Padding', 'sassly-essential' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem' ],					
				'selectors' => [
					'{{WRAPPER}} .header__area-2 .header__search-2' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->end_controls_section();
		$this->start_controls_section(
			'content_style_cta_section',
			[
				'label' => esc_html__( 'CTA Button', 'sassly-essential' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
				'condition' => ['preset_style' => ['two']]
			]
		);
		
			$this->add_control(
				'cta_text_color',
				[
					'label' => esc_html__( 'Text Color', 'sassly-essential' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .resume-header__hire-btn' => 'color: {{VALUE}}',
					],
				]
			);
			
			$this->add_control(
				'cta_text_hover_color',
				[
					'label' => esc_html__( 'Text Hover Color', 'sassly-essential' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .resume-header__hire-btn:hover' => 'color: {{VALUE}}',
					],
				]
			);
			
			$this->add_group_control(
				\Elementor\Group_Control_Typography::get_type(),
				[
					'name' => 'cta_text_c_typography',
					'selector' => '{{WRAPPER}} .resume-header__hire-btn',
				]
			);			
			
			$this->add_group_control(
				\Elementor\Group_Control_Background::get_type(),
				[
					'name' => 'main_cta_background',
					'types' => [ 'classic', 'gradient'],
					'selector' => '{{WRAPPER}} .resume-header__hire-btn',
				]
			);
			
			$this->add_group_control(
				\Elementor\Group_Control_Border::get_type(),
				[
					'name' => 'cta_border',
					'selector' => '{{WRAPPER}} .resume-header__hire-btn',
				]
			);
			
			$this->add_control(
				'border_radius_cta',
				[
					'label' => esc_html__( 'Border Radius', 'sassly-essential' ),
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
						'{{WRAPPER}} .resume-header__hire-btn' => 'border-radius: {{SIZE}}{{UNIT}};',
					],
				]
			);
			
			$this->add_control(
				'cta_padding_button',
				[
					'label' => esc_html__( 'Padding', 'sassly-essential' ),
					'type' => \Elementor\Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],				
					'selectors' => [
						'{{WRAPPER}} .resume-header__hire-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			
			$this->add_control(
				'more_icons_options',
				[
					'label' => esc_html__( 'Icon', 'sassly-essential' ),
					'type' => \Elementor\Controls_Manager::HEADING,
					'separator' => 'before',
				]
			);
			
			$this->add_control(
				'cta_icon_color',
				[
					'label' => esc_html__( 'Icon Color', 'sassly-essential' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .resume-header__hire-btn i' => 'color: {{VALUE}}',
						'{{WRAPPER}} .resume-header__hire-btn svg' => 'fill: {{VALUE}}',
					],
				]
			);
			
			$this->add_control(
				'border_radius_icon_cta',
				[
					'label' => esc_html__( 'Border Radius', 'sassly-essential' ),
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
						'{{WRAPPER}} .resume-header__hire-btn .icon-img' => 'border-radius: {{SIZE}}{{UNIT}};',
					],
				]
			);
			
			$this->add_group_control(
				\Elementor\Group_Control_Background::get_type(),
				[
					'name' => 'main_cta_icon_background',
					'types' => [ 'classic', 'gradient'],
					'selector' => '{{WRAPPER}} .resume-header__hire-btn .icon-img',
				]
			);			

		$this->end_controls_section();
		$this->start_controls_section(
			'content_style_mobile_section',
			[
				'label' => esc_html__( 'Mobile Interface', 'sassly-essential' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);
		
		$this->start_controls_tabs(
			'style_mobile_inter_gen_tabs'
		);
		
		$this->start_controls_tab(
			'style_mobile_interface_gen_tab',
			[
				'label' => esc_html__( 'General', 'sassly-essential' ),
			]
		);
		
			$this->add_responsive_control(
				'mobile_logo_width',
				[
					'label'              => __( 'Mobile Bar Width', 'sassly-essential' ),
					'type'               => Controls_Manager::SLIDER,
					'default'            => [
						'unit' => 'px',
					],
					
					'size_units'         => [ '%', 'px' ],
					'range'              => [
						'%'  => [
							'min' => 1,
							'max' => 100,
						],
						'px' => [
							'min' => 1,
							'max' => 300,
						],					
					],
					'selectors'          => [
						'{{WRAPPER}} .header__navicon-2 img' => 'width: {{SIZE}}{{UNIT}};',
						'{{WRAPPER}} .header__navicon-2 svg' => 'width: {{SIZE}}{{UNIT}};',
					],
					
				]
			);
		
			$this->add_responsive_control(
				'containersty_width',
				[
					'label'              => __( 'Main Container Width', 'sassly-essential' ),
					'type'               => Controls_Manager::SLIDER,
					'default'            => [
						'unit' => '%',
					],
					'tablet_default'     => [
						'unit' => '%',
					],
					'mobile_default'     => [
						'unit' => '%',
					],
					'size_units'         => [ '%', 'px' ],
					'range'              => [
						'%'  => [
							'min' => 1,
							'max' => 100,
						],
						'px' => [
							'min' => 1,
							'max' => 1000,
						],					
					],
					'selectors'          => [
						'{{WRAPPER}} .header__area-2' => 'width: {{SIZE}}{{UNIT}};',
					],
					
				]
			);
			
			$this->add_control(
				'mobile_bar_transparent',
				[
					'label' => esc_html__( 'Transparent Menu ?', 'sassly-essential' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'default' => '',
					'options' => [
						'' => esc_html__( 'Yes', 'sassly-essential' ),
						'relative' => esc_html__( 'No', 'sassly-essential' ),					
						
					],
					'selectors' => [
						'{{WRAPPER}} .pd-header' => 'position: {{VALUE}};',
					],
				]
			);
			
			$this->add_control(
				'mobile_bar_direc',
				[
					'label' => esc_html__( 'Bar Direction', 'sassly-essential' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'default' => '',
					'options' => [
						'' => esc_html__( 'Default', 'sassly-essential' ),
						'row' => esc_html__( 'Row', 'sassly-essential' ),
						'row-reverse'  => esc_html__( 'Row Reverse', 'sassly-essential' ),
						'column' => esc_html__( 'Column', 'sassly-essential' ),
						'column-reverse' => esc_html__( 'Column Reverse', 'sassly-essential' ),
						
					],
					'selectors' => [
						'{{WRAPPER}} .pd-header' => 'flex-direction: {{VALUE}};',
					],
				]
			);
			
			$this->add_responsive_control(
				'mobile_logo_gap',
				[
					'label'              => __( 'Gap', 'sassly-essential' ),
					'type'               => Controls_Manager::SLIDER,
					'default'            => [
						'unit' => 'px',
					],					
					'size_units'         => [ 'px' ],
					'range'              => [						
						'px' => [
							'min' => 0,
							'max' => 500,
						],					
					],
					'selectors'          => [
						'{{WRAPPER}} .pd-header' => 'gap: {{SIZE}}{{UNIT}};',						
					],
					
				]
			);
			
			$this->add_control(
				'main_section_mobile_padding',
				[
					'label' => esc_html__( 'Mobile Padding', 'sassly-essential' ),
					'type' => \Elementor\Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],					
					'selectors' => [
						'{{WRAPPER}} .pd-header' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			
			$this->add_group_control(
				\Elementor\Group_Control_Background::get_type(),
				[
					'name' => 'main_mobill_background',
					'types' => [ 'classic', 'gradient'],
					'selector' => '{{WRAPPER}} .pd-header',
				]
			);
		
		$this->end_controls_tab();
		$this->start_controls_tab(
			'style_mobile_interface_close_tab',
			[
				'label' => esc_html__( 'Close Button', 'sassly-essential' ),
			]
		);
		
			$this->add_control(
				'close_text_color',
				[
					'label' => esc_html__( 'Text Color', 'sassly-essential' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .header__navicon-2 .close' => 'color: {{VALUE}}',
					],
				]
			);
			
			$this->add_group_control(
				\Elementor\Group_Control_Typography::get_type(),
				[
					'name' => 'close_icon_typography',
					'selector' => '{{WRAPPER}} .header__navicon-2 .close',
				]
			);
			
			$this->add_responsive_control(
				'close_button_right_pos',
				[
					'label' => esc_html__( 'Position Right', 'textdomain' ),
					'type' => \Elementor\Controls_Manager::SLIDER,
					'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
					'range' => [
						'px' => [
							'min' => -400,
							'max' => 600,
							'step' => 5,
						],
						'%' => [
							'min' => -100,
							'max' => 100,
						],
					],					
					'selectors' => [
						'{{WRAPPER}} .header__navicon-2 .close' => 'right: {{SIZE}}{{UNIT}};',
					],
				]
			);
			
			$this->add_responsive_control(
				'close_button_top_pos',
				[
					'label' => esc_html__( 'Position Top', 'textdomain' ),
					'type' => \Elementor\Controls_Manager::SLIDER,
					'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
					'range' => [
						'px' => [
							'min' => -400,
							'max' => 600,
							'step' => 5,
						],
						'%' => [
							'min' => -100,
							'max' => 100,
						],
					],					
					'selectors' => [
						'{{WRAPPER}} .header__navicon-2 .close' => 'top: {{SIZE}}{{UNIT}};',
					],
				]
			);
		
		$this->end_controls_tab();
		
		$this->end_controls_tabs();		
		
		$this->end_controls_section();
		$this->start_controls_section(
			'content_scopyright_s_section',
			[
				'label' => esc_html__( 'Copyright', 'sassly-essential' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
				'condition' => ['preset_style' => ['one']]
			]
		);
		
		$this->add_control(
			'copyright_text_color',
			[
				'label' => esc_html__( 'Color', 'sassly-essential' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .copyright' => 'color: {{VALUE}}',
					'{{WRAPPER}} .copyright p' => 'color: {{VALUE}}',
				],
			]
		);
		
		$this->add_control(
			'copyright_text_link_color',
			[
				'label' => esc_html__( 'Link Color', 'sassly-essential' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .copyright a' => 'color: {{VALUE}}',
					'{{WRAPPER}} .copyright p a' => 'color: {{VALUE}}',
				],
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'copyrights_typography',
				'selector' => '{{WRAPPER}} .copyright, {{WRAPPER}} .copyright p',
			]
		);
		
		$this->add_responsive_control(
			'wcfin_cright_pg',
			[
				'label' => esc_html__( 'Main Padding', 'sassly-essential' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem' ],					
				'selectors' => [
					'{{WRAPPER}} .header__area-2 .copyright' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->end_controls_section();
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

	/**
	 * Register Landing Page General Controls.
	 *
	 * @since 1.3.0
	 * @access protected
	 */
	protected function register_content_site_logo_controls() {
	
		$this->start_controls_section(
			'section_layout_image',
			[
				'label' => __( 'Layout', 'sassly-essential' ),
			]
		);
		
			$this->add_control(
				'preset_style',
				[
					'label' => esc_html__( 'Style', 'sassly-essential' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'default' => 'one',
					'options' => [
						'one'   => esc_html__( 'One' , 'sassly-essential' ),
						'two'       => esc_html__( 'Two' , 'sassly-essential' )		
					],
					
				]
			);	
		
		$this->end_controls_section();	
		$this->start_controls_section(
			'section_site_image',
			[
				'label' => __( 'Menu Settings', 'sassly-essential' ),
			]
		);
	
		$this->add_control(
			'custom_image',
			[
				'label'     => __( 'Logo / Image', 'sassly-essential' ),
				'type'      => Controls_Manager::MEDIA,
				'dynamic'   => [
					'active' => true,
				],
				'default'   => [
					'url' => Utils::get_placeholder_image_src(),
				],				
			]
		);
		
		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name'    => 'site_logo_size',
				'label'   => __( 'Image Size', 'sassly-essential' ),
				'default' => 'medium',				
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
			'website_link',
			[
				'label' => esc_html__( 'Link', 'sassly-essential' ),
				'type' => \Elementor\Controls_Manager::URL,
				'options' => [ 'url', 'is_external', 'nofollow' ],
				'default' => [
					'url' => '#home',				
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

		$this->add_control(
			'menu_list',
			[
				'label' => esc_html__( 'Menu List', 'sassly-essential' ),
				'type' => \Elementor\Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'list_title' => esc_html__( 'Home', 'sassly-essential' ),						
					],					
				],
				'title_field' => '{{{ list_title }}}',
			]
		);
	
		$this->add_control(
			'copyright_texts',
			[
				'label' => esc_html__( 'Copyright', 'sassly-essential' ),
				'type' => \Elementor\Controls_Manager::WYSIWYG,
				'condition' => ['preset_style' => ['one']],
				'default' => '© Alrights reserved <br> by <a href="https://crowdyflow.com/" target="_blank">CrowdyFlow</a>',
				'placeholder' => esc_html__( 'Type your description here', 'sassly-essential' ),				
			]
		);
		
		$this->end_controls_section();	
		
		$this->start_controls_section(
			'section_logoimage',
			[
				'label' => __( 'Mobile Interface', 'sassly-essential' ),
			]
		);
		
		$this->start_controls_tabs(
			'style_m_interface_tabs'
		);
		
		$this->start_controls_tab(
			'style_mobile_normal_tab',
			[
				'label' => esc_html__( 'General', 'textdomain' ),
			]
		);
	
		
			$this->add_control(
				'custom_mobile_image',
				[
					'label'     => __( 'Mobile Logo', 'sassly-essential' ),
					'type'      => Controls_Manager::MEDIA,
					'dynamic'   => [
						'active' => true,
					],
					'default'   => [
						'url' => Utils::get_placeholder_image_src(),
					],				
				]
			);	
		
			$this->add_control(
				'custom_bar',
				[
					'label'       => __( 'Custom Mobile Bar', 'sassly-essential' ),
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
					'label' => esc_html__('Mobile Bar', 'sassly-essential'),
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
					'label' => esc_html__( 'Choose mobile Bar Image', 'sassly-essential' ),
					'type' => \Elementor\Controls_Manager::MEDIA,	
					'condition' => [
						'custom_bar' => 'yes',
					],
				]
			);
		
			
		$this->end_controls_tab();
		
		$this->start_controls_tab(
			'style_mobile_close_tab',
			[
				'label' => esc_html__( 'Close Button', 'sassly-essential' ),
			]
		);
		
			$this->add_control(
				'close_icon',
				[
					'label' => esc_html__( 'Close Icon', 'sassly-essential' ),
					'type' => \Elementor\Controls_Manager::ICONS,
					'default' => [					
					]				
				]
			);
		
		$this->end_controls_tab();
		
		$this->end_controls_tabs();
		
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_cta_button',
			[
				'label' => __( 'Cta Button', 'sassly-essential' ),
				'condition' => ['preset_style' => ['two']]
			]
		);
		
			$this->add_control(
				'show_cta_button',
				[
					'label' => esc_html__( 'Cta Button', 'sassly-essential' ),
					'type' => \Elementor\Controls_Manager::SWITCHER,
					'label_on' => esc_html__( 'Show', 'sassly-essential' ),
					'label_off' => esc_html__( 'Hide', 'sassly-essential' ),
					'return_value' => 'yes',
					'default' => 'yes',
				]
			);
			
			$this->add_control(
				'cta_text',
				[
					'label' => esc_html__( 'Button Text', 'sassly-essential' ),
					'type' => \Elementor\Controls_Manager::TEXT,
					'default' => esc_html__( 'Get Started Now', 'sassly-essential' ),
					'placeholder' => esc_html__( 'Get Started Now', 'sassly-essential' ),
				]
			);
			
			$this->add_control(
				'cta_website_link',
				[
					'label' => esc_html__( 'Link', 'sassly-essential' ),
					'type' => \Elementor\Controls_Manager::URL,
					'options' => [ 'url', 'is_external', 'nofollow' ],
					'default' => [
						'url' => '',
						'is_external' => false,
						'nofollow' => false,
						// 'custom_attributes' => '',
					],
					'label_block' => true,
				]
			);
			
			$this->add_control(
				'show_cta_icon',
				[
					'label' => esc_html__( 'Cta Cta Icon', 'sassly-essential' ),
					'type' => \Elementor\Controls_Manager::SWITCHER,
					'label_on' => esc_html__( 'Show', 'sassly-essential' ),
					'label_off' => esc_html__( 'Hide', 'sassly-essential' ),
					'return_value' => 'yes',
					'default' => 'yes',
				]
			);
			
			$this->add_control(
				'cta_icon',
				[
					'label' => esc_html__( 'Icon', 'textdomain' ),
					'type' => \Elementor\Controls_Manager::ICONS,
					'default' => [
						
					],
				
				]
			);
			
		$this->end_controls_section();	
		
		$this->start_controls_section(
			'section_additional',
			[
				'label' => __( 'Additional Settings', 'sassly-essential' ),
			]
		);
		
		$this->add_responsive_control(
			'align',
			[
				'label'              => __( 'Alignment', 'sassly-essential' ),
				'type'               => Controls_Manager::CHOOSE,
				'options'            => [
					'left'   => [
						'title' => __( 'Left', 'sassly-essential' ),
						'icon'  => 'eicon-text-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'sassly-essential' ),
						'icon'  => 'eicon-text-align-center',
					],
					'right'  => [
						'title' => __( 'Right', 'sassly-essential' ),
						'icon'  => 'eicon-text-align-right',
					],
				],
				'default'            => 'left',
				'selectors'          => [
					'{{WRAPPER}} .header__inner-2' => 'text-align: {{VALUE}};',
				],
				
			]
		);
		
		$this->add_control(
			'header_size',
			[
				'label'   => esc_html__( 'HTML Tag', 'wcf' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					
					'section'   => 'Section',
					'header'    => 'Header',
					'div'       => 'div',				
					
				],
				'default' => 'header',
			]
		);
		
		$this->add_control(
			'show_search',
			[
				'label' => esc_html__( 'Search Form', 'textdomain' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Show', 'textdomain' ),
				'label_off' => esc_html__( 'Hide', 'textdomain' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);
		
		$this->add_control(
			'enable_url_attr',
			[
				'label' => esc_html__( 'Enable Url Attribute', 'sassly-essential' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'sassly-essential' ),
				'label_off' => esc_html__( 'no', 'sassly-essential' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);
		
		$this->add_control(
			'mobile_direction',
			[
				'label' => esc_html__( 'Mobile Content Direction', 'sassly-essential' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'left',
				'options' => [
					'left' => esc_html__( 'Left', 'sassly-essential' ),
					'right' => esc_html__( 'Right', 'sassly-essential' ),							
				],
			
			]
		);
		
		$this->add_control(
			'search_icon',
			[
				'label' => esc_html__( 'Search Icon', 'textdomain' ),
				'type' => \Elementor\Controls_Manager::ICONS,
				'condition' => ['show_search' => ['yes']],
				'default' => [					
				],
			
			]
		);
		
		$this->end_controls_section();
	}
	/**
	 * Register Site Image Style Controls.
	 *
	 * @since 1.3.0
	 * @access protected
	 */
	protected function register_site_logo_styling_controls() {
		$this->start_controls_section(
			'section_style_site_logo_image',
			[
				'label' => __( 'Site logo', 'sassly-essential' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		
		$this->add_responsive_control(
			'log_conainer_padding',
			[
				'label' => esc_html__( 'Container Padding', 'sassly-essential' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],				
				'selectors' => [
					'{{WRAPPER}} .header__logo-2' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->add_responsive_control(
			'log_aspect_rr',
			[
				'label' => esc_html__( 'Aspect Ratio', 'v' ),
				'condition' => ['preset_style' => ['two']],
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => '',
				'options' => [
					'unset' => esc_html__( 'Default', 'sassly-essential' ),
					'100/100' => esc_html__( '100/100', 'sassly-essential' ),			
				],
				'selectors' => [
					'{{WRAPPER}} .resume-header__logo' => 'aspect-ratio: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'width',
			[
				'label'              => __( 'Width', 'sassly-essential' ),
				'type'               => Controls_Manager::SLIDER,
				'default'            => [
					'unit' => '%',
				],
				'tablet_default'     => [
					'unit' => '%',
				],
				'mobile_default'     => [
					'unit' => '%',
				],
				'size_units'         => [ '%', 'px', 'vw' ],
				'range'              => [
					'%'  => [
						'min' => 1,
						'max' => 100,
					],
					'px' => [
						'min' => 1,
						'max' => 1000,
					],
					'vw' => [
						'min' => 1,
						'max' => 100,
					],
				],
				'selectors'          => [
					'{{WRAPPER}} .header__logo-2 img' => 'width: {{SIZE}}{{UNIT}};',
				],
				
			]
		);

		$this->add_responsive_control(
			'space',
			[
				'label'              => __( 'Max Width', 'sassly-essential' ) . ' (%)',
				'type'               => Controls_Manager::SLIDER,
				'default'            => [
					'unit' => '%',
				],
				'tablet_default'     => [
					'unit' => '%',
				],
				'mobile_default'     => [
					'unit' => '%',
				],
				'size_units'         => [ '%' ],
				'range'              => [
					'%' => [
						'min' => 1,
						'max' => 100,
					],
				],
				'selectors'          => [
					'{{WRAPPER}} .header__logo-2 img' => 'max-width: {{SIZE}}{{UNIT}};',
				],
			
			]
		);
		
		
		//
		$this->add_control(
			'logo_text_color',
			[
				'label'     => __( 'Border Color', 'sassly-essential' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'condition' => ['preset_style' => ['two']],
				'selectors' => [
					'{{WRAPPER}} .resume-header__logo' => 'border-color: {{VALUE}};',				
				],
				
			]
		);
		$this->add_control(
			'separator_panel_style',
			[
				'type'  => Controls_Manager::DIVIDER,
				'style' => 'thick',
			]
		);	

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'image_box_shadow',
				'exclude'  => [
					'box_shadow_position',
				],
				'selector' => '{{WRAPPER}} .header__logo-2 img',
			]
		);

		$this->start_controls_tabs( 'image_effects' );

		$this->start_controls_tab(
			'normal',
			[
				'label' => __( 'Normal', 'sassly-essential' ),
			]
		);

			$this->add_control(
				'opacity',
				[
					'label'     => __( 'Opacity', 'sassly-essential' ),
					'type'      => Controls_Manager::SLIDER,
					'range'     => [
						'px' => [
							'max'  => 1,
							'min'  => 0.10,
							'step' => 0.01,
						],
					],
					'selectors' => [
						'{{WRAPPER}} .header__logo-2 img' => 'opacity: {{SIZE}};',
					],
				]
			);

			$this->add_group_control(
				Group_Control_Css_Filter::get_type(),
				[
					'name'     => 'css_filters',
					'selector' => '{{WRAPPER}} .header__logo-2 img',
				]
			);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'hover',
			[
				'label' => __( 'Hover', 'sassly-essential' ),
			]
		);
		
			$this->add_control(
				'opacity_hover',
				[
					'label'     => __( 'Opacity', 'sassly-essential' ),
					'type'      => Controls_Manager::SLIDER,
					'range'     => [
						'px' => [
							'max'  => 1,
							'min'  => 0.10,
							'step' => 0.01,
						],
					],
					'selectors' => [
						'{{WRAPPER}} .header__logo-2:hover img' => 'opacity: {{SIZE}};',
					],
				]
			);
			
			$this->add_control(
				'background_hover_transition',
				[
					'label'     => __( 'Transition Duration', 'sassly-essential' ),
					'type'      => Controls_Manager::SLIDER,
					'range'     => [
						'px' => [
							'max'  => 3,
							'step' => 0.1,
						],
					],
					'selectors' => [
						'{{WRAPPER}} .header__logo-2 img' => 'transition-duration: {{SIZE}}s',
					],
				]
			);

			$this->add_group_control(
				Group_Control_Css_Filter::get_type(),
				[
					'name'     => 'css_filters_hover',
					'selector' => '{{WRAPPER}} .header__logo-2:hover img',
				]
			);

		
		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}
	/**
	 * Register Site Logo style Controls.
	 *
	 * @since 1.3.0
	 * @access protected
	 */
	protected function register_site_logo_caption_styling_controls() {
		$this->start_controls_section(
			'section_style_caption',
			[
				'label'     => __( 'Menu', 'sassly-essential' ),
				'tab'       => Controls_Manager::TAB_STYLE,				
			]
		);
		
		$this->add_responsive_control(
			'menu_item_direction',
			[
				'label' => esc_html__( 'Direction', 'textdomain' ),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'flex-direction:row;' => [
						'title' => esc_html__( 'Left', 'textdomain' ),
						'icon' => 'eicon-h-align-left',
					],
					'flex-direction:column;' => [
						'title' => esc_html__( 'Center', 'textdomain' ),
						'icon' => 'eicon-v-align-top',
					],
					'flex-direction:row-reverse;' => [
						'title' => esc_html__( 'Right', 'textdomain' ),
						'icon' => ' eicon-h-align-right',
					],
					'flex-direction:column-reverse;' => [
						'title' => esc_html__( 'Right', 'textdomain' ),
						'icon' => 'eicon-v-align-bottom',
					],
				],
				'condition' => ['preset_style' => ['two']],
				'default' => '',
				'toggle' => true,
				'selectors' => [
					'{{WRAPPER}} .header__area-2 .sidebar-menu li a' => '{{VALUE}};',
				],
			]
		);
		
		$this->add_responsive_control(
			'menu_item_alignment',
			[
				'label' => esc_html__( 'Alignment', 'sassly-essential' ),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'align-items:start;' => [
						'title' => esc_html__( 'Left', 'sassly-essential' ),
						'icon' => 'eicon-v-align-top',
					],
					'align-items:center;' => [
						'title' => esc_html__( 'Center', 'sassly-essential' ),
						'icon' => ' eicon-h-align-center',
					],
					'align-items:end;' => [
						'title' => esc_html__( 'Right', 'sassly-essential' ),
						'icon' => ' eicon-v-align-bottom',
					]					
				],
				'condition' => ['preset_style' => ['two']],
				'default' => '',
				'toggle' => true,
				'selectors' => [
					'{{WRAPPER}} .header__area-2 .sidebar-menu li a' => '{{VALUE}};',
				],
			]
		);


		$this->add_control(
			'text_color',
			[
				'label'     => __( 'Text Color', 'sassly-essential' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .sidebar-menu li a' => 'color: {{VALUE}};',
					'{{WRAPPER}} .sidebar-menu li a svg' => 'fill: {{VALUE}};',
				],
				
			]
		);
		
		$this->add_control(
			'text_active_color',
			[
				'label'     => __( 'Active Border Color', 'sassly-essential' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [				
					'{{WRAPPER}} .resume-menu li a.active:after' => 'background: {{VALUE}};',
					'{{WRAPPER}} .resume-menu li a.active:before' => 'background: {{VALUE}};',
				],
				
			]
		);
						
		$this->add_control(
			'text_active_text_color',
			[
				'label'     => __( 'Active Text & Icon Color', 'sassly-essential' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .resume-menu .sidebar-menu li a.active svg' => 'fill: {{VALUE}};',					
					'{{WRAPPER}} .resume-menu .sidebar-menu li a.active' => 'color: {{VALUE}};',					
					'{{WRAPPER}} .layout-1 .sidebar-menu li a.active' => 'color: {{VALUE}};',					
				],
				
			]
		);	
		
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'caption_typography',
				'label'     => __( 'Typhography', 'sassly-essential' ),
				'selector' => '{{WRAPPER}} .sidebar-menu li a',				
			]
		);
		
		$this->add_control(
			'text_hover_color',
			[
				'label'     => __( 'Text Hover Color', 'sassly-essential' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .sidebar-menu li a:hover' => 'color: {{VALUE}};',
				],				
			]
		);
		
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'caption_ho_typography',
				'label'     => __( 'Hover Typhography', 'sassly-essential' ),
				'selector' => '{{WRAPPER}} .sidebar-menu li a:hover',				
			]
		);

		$this->add_control(
			'caption_background_color',
			[
				'label'     => __( 'Background Color', 'sassly-essential' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .sidebar-menu li a' => 'background-color: {{VALUE}};',
				],
			]
		);
		

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name'     => 'caption_text_shadow',
				'selector' => '{{WRAPPER}} .sidebar-menu li a',
			]
		);

		$this->add_responsive_control(
			'caption_padding',
			[
				'label'              => __( 'Item Padding', 'sassly-essential' ),
				'type'               => Controls_Manager::DIMENSIONS,
				'size_units'         => [ 'px', 'em', '%' ],
				'selectors'          => [
					'{{WRAPPER}} .sidebar-menu li a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],			
			]
		);
		
		$this->add_responsive_control(
			'cmenu_wrap_marg',
			[
				'label'              => __( 'Menu Wrapper Margin', 'sassly-essential' ),
				'type'               => Controls_Manager::DIMENSIONS,
				'size_units'         => [ 'px', 'em', '%' ],
				'selectors'          => [
					'{{WRAPPER}} .sidebar-menu' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],			
			]
		);
		
		$this->add_control(
			'background_hr_transition',
			[
				'label'     => __( 'Transition Duration', 'sassly-essential' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'max'  => 3,
						'step' => 0.1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .sidebar-menu li a' => 'transition-duration: {{SIZE}}s',
				],
			]
		);
		

		$this->end_controls_section();
	}


	/**
	 * Render Site Image output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.3.0
	 * @param array $size returns the size of an image.
	 * @access public
	 */
	public function site_image_url( $size ) {
		$settings = $this->get_settings_for_display();
		if ( ! empty( $settings['custom_image']['url'] ) ) {
			$logo = wp_get_attachment_image_src( $settings['custom_image']['id'], $size, true );
			return $logo[0];
		} else {
			$light_logo      = sassly_IMG . '/lawyer-black-logo.png';		
			$logo            = sassly_option( 'logo', $light_logo ); 				
			return $logo;
		}
		
	}

	/**
	 * Render Site Image output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.3.0
	 * @access protected
	 */
	protected function render() {
	
		$link           = '';
		$settings       = $this->get_settings_for_display();
        $preset_style   = $settings[ 'preset_style' ];	    
	    
		$this->add_render_attribute( 'wrapper', 'class', 'hfe-site-logo' );
		$size       = $settings['site_logo_size_size'];
		$site_image = $this->site_image_url( $size );
		
		$bar = '';
        
        if($settings['bar'] !=''){
			$bar = SASSLY_ESSENTIAL_ASSETS_URL .'images/bars/'.$settings['bar'];  
        }
	         
        if($settings['custom_bar'] == 'yes' && isset($settings['custom_bar_image']['url'])){
            $bar = $settings['custom_bar_image']['url'];
        }
		
		if(file_exists(SASSLY_ESSENTIAL_DIR_PATH."widgets/landing-pages/content-$preset_style".'.php')){ 
			include_once(SASSLY_ESSENTIAL_DIR_PATH."widgets/landing-pages/content-$preset_style".'.php'); 
		}	
	}

	/**
	 * Retrieve Site Logo widget link URL.
	 *
	 * @since 1.3.0
	 * @access private
	 *
	 * @param array $settings returns settings.
	 * @return array|string|false An array/string containing the link URL, or false if no link.
	 */
	private function get_link_url( $settings ) {
	
		if ( 'none' === $settings['link_to'] ) {
			return false;
		}

		if ( 'custom' === $settings['link_to'] ) {
			if ( empty( $settings['link']['url'] ) ) {
				return false;
			}
			return $settings['link'];
		}

		if ( 'default' === $settings['link_to'] ) {
			if ( empty( $settings['link']['url'] ) ) {
				return false;
			}
			return site_url();
		}
		
	}
}
