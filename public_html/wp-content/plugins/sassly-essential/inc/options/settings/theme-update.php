<?php 

   // Theme Update
   CSF::createSection( 'sassly_settings', array(
           
    'title'  => esc_html__( 'Theme Update', 'sassly-essential' ),
    'icon'   => 'fa fa-share-square-o',
    'fields' => array(
        // A Heading
        array(
            'type'    => 'heading',
            'content' => esc_html__('Theme Update','sassly-essential'),
        ),
        
        array(
            'type'    => 'notice',
            'style'   => 'warning',
            'content' => '<p>Check Latest Theme Update</p> <a class="button" id="wcf--check-theme-update-status">Check Update</a>',
          ),
  
        array(
            'type'     => 'callback',
            'function' => 'wcf__theme__update__html',
          ),
    ),
) );