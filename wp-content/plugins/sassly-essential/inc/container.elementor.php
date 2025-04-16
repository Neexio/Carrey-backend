<?php

namespace SasslyEssentialApp\Inc;
use \Elementor\Controls_Manager;

class sassly_Section_Settings {

    public function __construct(){
        // add_action( 'elementor/element/section/section_advanced/after_section_end', [ $this, 'add_controls_section' ],50);
        // add_action( 'elementor/element/container/section_layout/after_section_end', [ $this, 'add_controls_section' ],50);
        add_action( 'elementor/element/icon-box/section_icon/before_section_end', [ $this, 'bottom_top_scroll' ] );
        add_action( 'elementor/element/wcf--button/section_content/before_section_end', [ $this, 'bottom_top_scroll' ] );
        add_action( 'elementor/element/button/section_button/before_section_end', [ $this, 'bottom_top_scroll' ] );
    }
    
    public function bottom_top_scroll( $element ) {
	
        $element->add_control(
			'wcf_enable_bottom_top_scroll',
			[
				'label' => esc_html__( 'Enable ScrollTo', 'sassly-essential' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'sassly-essential' ),
				'label_off' => esc_html__( 'NO', 'sassly-essential' ),
				'return_value' => 'yes',
				'default' => '',
				'frontend_available' => true,
			]
		);
		
    }

    public function add_controls_section( $element ){

        // $element->start_controls_section(
        //     'wcf_isolation_custom_sticky_section',
        //     [
        //         'tab'           =>  \Elementor\Controls_Manager::TAB_ADVANCED,
        //         'label' => esc_html__( 'WCF Isolation', 'sassly-essential' ),
        //     ]
        // );

        //     $element->add_control(
        //         'wcf_pro_isolation_type',
        //         [
        //             'label' => esc_html__( 'Isolation', 'sassly-essential' ),
        //             'type' => \Elementor\Controls_Manager::SELECT,
        //             'default' => '',
        //             'options' => [

        //                 'isolate'      => esc_html__( 'Isolate', 'sassly-essential' ),
        //                 'revert'       => esc_html__( 'Revert', 'sassly-essential' ),
        //                 'revert-layer' => esc_html__( 'Revert layer', 'sassly-essential' ),
        //                 'auto'         => esc_html__( 'Auto', 'sassly-essential' ),
        //                 'initial'      => esc_html__( 'Initial', 'sassly-essential' ),
        //                 ''             => esc_html__( 'None', 'sassly-essential' ),
                
        //             ],
        //             'selectors' => [
        //                 '{{WRAPPER}}' => 'isolation: {{VALUE}}',
        //             ],
        //         ]
        //     );
 
       // $element->end_controls_section();
        
    }

   
}

new sassly_Section_Settings();