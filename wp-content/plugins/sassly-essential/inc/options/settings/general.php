<?php 

CSF::createSection( SASSLY_OPTION_KEY, array(
        'icon'   => 'fa fa-book',
        'title'  => esc_html__( 'General','sassly-essential'),
        'fields' => array(
            
            array(
                'id'      => 'general_full_site_background',
                'type'    => 'switcher',
                'title'   => esc_html__( 'FullSite Background Pattern', 'sassly-essential' ),
                'default' => false
            ), 
            
            array(
                'id'        => 'general_fullsite_background_preset',
                'type'      => 'image_select',
                'title'     => esc_html__('Background Pattern Select','sassly-essential'),
                'options'   => SASSLY_ESSENTIAL_get_background_patterns(),
                'dependency' => array( 'general_full_site_background', '==', 'true' ),
                'default'   => '',                
            ),

            array(
                'id'        => 'general_full_site_custom_background',
                'type'      => 'media',
                'preview'   => false,
                'library'   => 'image',
                'dependency' => array( 'general_fullsite_background_preset|general_full_site_background','==|==','custom|true' ),
                'title'     => esc_html__('Custom Background Pattern','sassly-essential'),
            ),
            
            array(
                'id'      => 'theme_demo_activate',
                'type'    => 'switcher',
                'title'   => esc_html__( 'Activate Theme Demo', 'sassly-essential' ),
                'default' => true,               
            ), 
              
            array(
                'id'      => 'hide_unwanted_warning',
                'type'    => 'switcher',
                'title'   => esc_html__( 'Hide Unwanted Warning', 'arolax-essential' ),
                'default' => true,               
            ), 
        )
    ) ); 