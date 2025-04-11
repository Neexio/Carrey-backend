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

class Dropdown extends \Elementor\Widget_Base {
   
	public function get_name() {
		return 'wcf--dropdown';
	}

	public function get_title() {
		return wcf_elementor_widget_concat_prefix( 'Dropdown / Language ' );
	}

	public function get_icon() {
		return 'wcf eicon-select';
	}

	public function get_categories() {
		return [ 'weal-coder-addon' ];
	}

	public function get_keywords() {
		return ['option','language', 'select' ];
	}
	
    public function get_style_depends() {
        wp_register_style( 'wcf-thm-dropdown', SASSLY_ESSENTIAL_ASSETS_URL . 'css/dropdown.css' );
		return [ 'wcf-thm-dropdown' ];
    }
   
	
	public function register_content_controls(){
        $this->start_controls_section(
			'section_content',
			[
				'label' => __( 'Settings', 'sassly-essential' ),
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
			'list_url',
			[
				'label' => esc_html__( 'Url', 'sassly-essential' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => '#',
				'label_block' => true,
			]
		);
		
		$this->add_control(
			'polylang_switcher',
			[
				'label' => esc_html__( 'PolyLang Swicher?', 'sassly-essential' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'sassly-essential' ),
				'label_off' => esc_html__( 'No', 'sassly-essential' ),
				'return_value' => 'yes',
				'default' => '',
			]
		);
		
		$this->add_control(
			'list',
			[
				'label' => esc_html__( 'Dropdown Options', 'sassly-essential' ),
				'type' => \Elementor\Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'list_title' => esc_html__( 'Title #1', 'sassly-essential' ),						
					],					
				],
				'title_field' => '{{{ list_title }}}',
				'condition' => ['polylang_switcher!' => ['yes']]
			]
		);
		
		
		$this->end_controls_section();
       
	}
	
	public function register_style_controls(){
	
        $this->start_controls_section(
            'section_style_container',
            [
                'label' => esc_html__( 'Select', 'sassly-essential' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );	
                
            $this->start_controls_tabs( 'container' );
        
                $this->start_controls_tab( 'arownormal',
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
                            '{{WRAPPER}} .athletic__header-lang select' => 'color: {{VALUE}}',
                        ],
                    ]
                );
                
                $this->add_group_control(
                    \Elementor\Group_Control_Typography::get_type(),
                    [
                        'name' => 'iocn_typography',
                        'selector' => '{{WRAPPER}} select',
                    ]
                );
                
                $this->add_group_control(
                    \Elementor\Group_Control_Background::get_type(),
                    [
                        'name' => 'container_background',
                        'types' => [ 'classic', 'gradient' ],
                        'selector' => '{{WRAPPER}} .athletic__header-lang select',
                    ]
                );
                
                $this->add_group_control(
                    \Elementor\Group_Control_Border::get_type(),
                    [
                        'name' => 'container_border',
                        'selector' => '{{WRAPPER}} .athletic__header-lang select',
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
                            '{{WRAPPER}} .athletic__header-lang select' => 'border-radius: {{SIZE}}{{UNIT}};',
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
                            '{{WRAPPER}} .athletic__header-lang select' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        ],
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
        $list = $settings['list']; 
        $langs = [];	  
	    if($settings['polylang_switcher'] == 'yes' && defined( 'POLYLANG_VERSION' )){
            $instance = [];
            $instance['dropdown'] = 1;
            $instance['echo']     = 0;
            $instance['raw']      = 0;
            $langs                = pll_the_languages( $instance );            
	    }
	    
		?>
		
          <div class="athletic__header-lang">   
              <?php if($settings['polylang_switcher'] == 'yes' && defined( 'POLYLANG_VERSION' )){                
                echo $langs;
               }else{?>
                    <select id="lang">
                        <?php foreach($list as $option){ ?>
                            <option value="<?php echo esc_url($option['list_url']) ?>"><?php echo $option['list_title'] ?></option>
                        <?php } ?>                
                    </select>
                    <script type="text/javascript">
					    document.getElementById( "lang" ).addEventListener( "change", function ( event ) { location.href = event.currentTarget.value; } )
				    </script>
               <?php } ?>
            </div> 
		<?php
	}
}