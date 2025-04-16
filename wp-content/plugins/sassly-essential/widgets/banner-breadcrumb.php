<?php

namespace SasslyEssentialApp\Widgets;

use Elementor\Controls_Manager;
use Elementor\Control_Media;
use Elementor\Utils;
use Elementor\Group_Control_Text_Stroke;
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

class Banner_Breadcrumb extends \Elementor\Widget_Base {
   
	public function get_name() {
		return 'wcf--blog--banner--breadcrumb';
	}

	public function get_title() {
		return wcf_elementor_widget_concat_prefix( 'Banner Breadcrumb' );
	}
	
	public function get_icon() {
		return 'wcf eicon-product-breadcrumbs';
	}

	public function get_categories() {
		return [ 'weal-coder-addon' ];
	}

	public function get_keywords() {
		return ['banner','breadcrumbs', 'Breadcrumb' ];
	}
	  	
	protected function register_controls() {
	
		$this->start_controls_section(
			'section_breadcrumb_style',
			[
				'label' => __( 'Breadcrumb', 'sassly-essential' ),				
			]
		);
		
		$this->add_control(
			'separetor_icon',
			[
				'label' => esc_html__( 'Separetor Icon', 'sassly-essential' ),
				'type' => \Elementor\Controls_Manager::ICONS,
				'default' => [
					'value' => 'fas fa-circle',
					'library' => 'fa-solid',
				],
			]
		);
		
		$this->add_control(
			'author_text',
			[
				'label'   => esc_html__( 'Author Heading', 'sassly-essential' ),
				'type'    => Controls_Manager::TEXT,
				'default' => 'Blogs for {author_name}',		
				'placeholder' => 'Blogs for {author_name}',			
			]
		);
		
		$this->add_control(
			'error_text',
			[
				'label'   => esc_html__( '404 Heading', 'sassly-essential' ),
				'type'    => Controls_Manager::TEXT,
				'default' => '404 Error',	
				'placeholder' => '404',	
			]
		);
		
		$this->add_control(
			'search_text',
			[
				'label'   => esc_html__( 'Search Heading', 'sassly-essential' ),
				'type'    => Controls_Manager::TEXT,
				'default' => 'Search Result',
				'placeholder' => 'Search Result',	
			]
		);
		
		$this->add_responsive_control(
			'breadcrumb_align',
			[
				'label'     => esc_html__( 'Alignment', 'sassly-essential' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => [
					'left'    => [
						'title' => esc_html__( 'Left', 'sassly-essential' ),
						'icon'  => 'eicon-text-align-left',
					],
					'center'  => [
						'title' => esc_html__( 'Center', 'sassly-essential' ),
						'icon'  => 'eicon-text-align-center',
					],
					'right'   => [
						'title' => esc_html__( 'Right', 'sassly-essential' ),
						'icon'  => 'eicon-text-align-right',
					],					
				],
				'default'   => '',
				'separator' => 'before',
				'selectors' => [
					'{{WRAPPER}} .default-breadcrumb__list' => 'justify-content: {{VALUE}};',
				],
			]
		);		
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_content',
			[
				'label' => __( 'Title', 'wcf' ),
			]
		);
	
        $this->add_control(
			'show_title',
			[
				'label' => esc_html__( 'Show Heading', 'sassly-essential' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Show', 'sassly-essential' ),
				'label_off' => esc_html__( 'Hide', 'sassly-essential' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);
		
		$this->add_control(
			'dynamic_title',
			[
				'label' => esc_html__( 'Dynamic', 'sassly-essential' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'sassly-essential' ),
				'label_off' => esc_html__( 'No', 'sassly-essential' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);
		
        $this->add_control(
			'static_text',
			[
				'label'   => esc_html__( 'Heading Text', 'sassly-essential' ),
				'type'    => Controls_Manager::TEXT,
				'default' => get_bloginfo( 'name' ),
				'ai'      => [
					'active' => false,
				],
				'dynamic' => [
					'active' => false,
				],
				'condition' => ['show_title' => ['yes']]
			]
		);

		$this->add_control(
			'header_size',
			[
				'label'   => esc_html__( 'HTML Tag', 'sassly-essential' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'h1'   => 'H1',
					'h2'   => 'H2',
					'h3'   => 'H3',
					'h4'   => 'H4',
					'h5'   => 'H5',
					'h6'   => 'H6',
					'div'  => 'div',
					'span' => 'span',
					'p'    => 'p',
				],
				'default' => 'h2',
				'condition' => ['show_title' => ['yes']]
			]
		);

		$this->add_control(
			'classes',
			[
				'label'   => esc_html__( 'CSS Classes', 'sassly-essential' ),
				'type'    => Controls_Manager::TEXT,
				'default' => '',
				'ai'      => [
					'active' => false,
				],
				'dynamic' => [
					'active' => false,
				],
				'condition' => ['show_title' => ['yes']]
			]
		);

		$this->add_responsive_control(
			'align',
			[
				'label'     => esc_html__( 'Alignment', 'sassly-essential' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => [
					'left'    => [
						'title' => esc_html__( 'Left', 'sassly-essential' ),
						'icon'  => 'eicon-text-align-left',
					],
					'center'  => [
						'title' => esc_html__( 'Center', 'sassly-essential' ),
						'icon'  => 'eicon-text-align-center',
					],
					'right'   => [
						'title' => esc_html__( 'Right', 'sassly-essential' ),
						'icon'  => 'eicon-text-align-right',
					],
					'justify' => [
						'title' => esc_html__( 'Justified', 'sassly-essential' ),
						'icon'  => 'eicon-text-align-justify',
					],
				],
				'default'   => '',
				'separator' => 'before',
				'selectors' => [
					'{{WRAPPER}}' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();
		
		
		$this->start_controls_section(
			'section_bread_style',
			[
				'label' => __( 'Breadcrumb Style', 'sassly-essential' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		
    		$this->add_control(
    			'title_bread_color',
    			[
    				'label'     => esc_html__( 'Text Color', 'sassly-essential' ),
    				'type'      => Controls_Manager::COLOR,
    				'selectors' => [
    					'{{WRAPPER}} .default-breadcrumb__list li' => 'color: {{VALUE}};',
    				],
    			]
    		);
    		
    		$this->add_control(
    			'title_active_color',
    			[
    				'label'     => esc_html__( 'Active Color', 'sassly-essential' ),
    				'type'      => Controls_Manager::COLOR,
    				'selectors' => [
    					'{{WRAPPER}} .default-breadcrumb__list li.active' => 'color: {{VALUE}};',
    				],
    			]
    		);
    		
    		$this->add_control(
    			'link_hover_color',
    			[
    				'label'     => esc_html__( 'Link Hover Color', 'sassly-essential' ),
    				'type'      => Controls_Manager::COLOR,
    				'selectors' => [
    					'{{WRAPPER}} .default-breadcrumb__list li:hover a' => 'color: {{VALUE}};',
    				],
    			]
    		);
    		
    		$this->add_group_control(
    			Group_Control_Typography::get_type(),
    			[
    				'name'     => 'bread_li_typography',
    				'selector' => '{{WRAPPER}} .default-breadcrumb__list li',
    			]
    		);
    	      
            $this->add_control(
                'icon_heading_options',
                [
                    'label' => esc_html__( 'Icon Options', 'sassly-essential' ),
                    'type' => \Elementor\Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );
            
            $this->add_control(
    			'title_sepe_color',
    			[
    				'label'     => esc_html__( 'Separetor Color', 'sassly-essential' ),
    				'type'      => Controls_Manager::COLOR,
    				'selectors' => [
    					'{{WRAPPER}} .default-breadcrumb__list li i' => 'color: {{VALUE}};',
    					'{{WRAPPER}} .default-breadcrumb__list li svg' => 'fill: {{VALUE}};',
    				],
    			]
    		);
            
    		$this->add_group_control(
    			Group_Control_Typography::get_type(),
    			[
    				'name'     => 'icon_typography',
    				'selector' => '{{WRAPPER}} .default-breadcrumb__list li i',
    			]
    		);
    		
    		$this->add_control(
    			'sepe_icon',
    			[
    				'label' => esc_html__( 'Icon Padding', 'sassly-essential' ),
    				'type' => \Elementor\Controls_Manager::DIMENSIONS,
    				'size_units' => [ 'px','em' ],
    				'selectors' => [
    					'{{WRAPPER}} .default-breadcrumb__list li i' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
    					'{{WRAPPER}} .default-breadcrumb__list li svg' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
    				],
    			]
    		);
    		
            $this->add_control(
                'wrapper_options',
                [
                    'label' => esc_html__( 'Wrapper', 'sassly-essential' ),
                    'type' => \Elementor\Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );
            
            $this->add_group_control(
                \Elementor\Group_Control_Background::get_type(),
                [
                    'name' => 'wrap_background',
                    'types' => [ 'classic', 'gradient', 'video' ],
                    'selector' => '{{WRAPPER}} .default-breadcrumb__list',
                ]
            );
            
            $this->add_control(
    			'section_wrapper',
    			[
    				'label' => esc_html__( 'Padding', 'sassly-essential' ),
    				'type' => \Elementor\Controls_Manager::DIMENSIONS,
    				'size_units' => [ 'px','em' ],
    				'selectors' => [
    					'{{WRAPPER}} .default-breadcrumb__list' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'    					
    				],
    			]
    		);
		
		$this->end_controls_section();
	
		$this->start_controls_section(
			'section_style',
			[
				'label' => __( 'Title Style', 'sassly-essential' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'title_color',
			[
				'label'     => esc_html__( 'Text Color', 'sassly-essential' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wcf--title' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'typography',
				'selector' => '{{WRAPPER}} .wcf--title',
			]
		);

		$this->add_group_control(
			Group_Control_Text_Stroke::get_type(),
			[
				'name'     => 'text_stroke',
				'selector' => '{{WRAPPER}} .wcf--title',
			]
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name'     => 'text_shadow',
				'selector' => '{{WRAPPER}} .wcf--title',
			]
		);

		$this->add_control(
			'blend_mode',
			[
				'label'     => esc_html__( 'Blend Mode', 'sassly-essential' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => [
					''            => esc_html__( 'Normal', 'sassly-essential' ),
					'multiply'    => 'Multiply',
					'screen'      => 'Screen',
					'overlay'     => 'Overlay',
					'darken'      => 'Darken',
					'lighten'     => 'Lighten',
					'color-dodge' => 'Color Dodge',
					'saturation'  => 'Saturation',
					'color'       => 'Color',
					'difference'  => 'Difference',
					'exclusion'   => 'Exclusion',
					'hue'         => 'Hue',
					'luminosity'  => 'Luminosity',
				],
				'selectors' => [
					'{{WRAPPER}} .wcf--title' => 'mix-blend-mode: {{VALUE}}',
				],
				'separator' => 'none',
			]
		);

		$this->end_controls_section();
	}
	
	public function get_dynamic_heading(){
        $settings = $this->get_settings_for_display();
	    
	   if(is_page()){
	      return get_the_title(get_queried_object_id());
	   }
	   
       if(is_single()){
        return get_the_title(get_queried_object_id());
       }
       
       if(is_category()){
            $category = get_queried_object();
        return $category->name;
       }
       
       if(is_tag()){
            $tag = get_queried_object();
        return $tag->name;
       }
       
        if(is_author()){
            $author_text   = $settings['author_text']; 
            $author_id     = get_the_author_meta( 'ID' );         
            $author_text   = str_replace('{author_name}', get_the_author_meta( 'nicename', $author_id ), $author_text);
            return $author_text;
        }
       
       if ( is_search() ) {
          return $settings['search_text'];
       }
       
       if ( is_404() ) {
          return $settings['error_text'];
       }
       
       if ( is_day() ) {
          return get_the_time( 'F jS, Y' );
       } elseif ( is_month() ) {
          return get_the_time( 'F, Y' );
       } elseif ( is_year() ) {
          return get_the_time( 'Y' );
       }
	   
	   return '';
	}
	
	protected function render() {
	
        $settings = $this->get_settings_for_display();
        $title    = $settings['static_text']; 
        
        if($settings['dynamic_title'] == 'yes'){         
            if(\Elementor\Plugin::$instance->editor->is_edit_mode() || \Elementor\Plugin::$instance->preview->is_preview_mode() || (isset($_GET['preview_id']) && isset($_GET['preview_nonce']))) {
                $title = get_the_title();
            }else{
               $title = $this->get_dynamic_heading();
            }        
        }
        
		$this->add_render_attribute( 'title', 'class', 'wcf--title' );

		if ( ! empty( $settings['classes'] ) ) {
			$this->add_render_attribute( 'title', 'class', $settings['classes'] );
		}  
		
		if($settings['show_title'] === 'yes' ){
		    $title_html = sprintf( '<%1$s %2$s>%3$s</%1$s>', Utils::validate_html_tag( $settings['header_size'] ), $this->get_render_attribute_string( 'title' ), $title );
    	    echo $title_html; 
        }
        $separete_icon = sassly_render_elementor_icons($settings['separetor_icon']);
        wcf_get_breadcrumbs($separete_icon,30);
		
	?>
	    
		<?php
	}
}