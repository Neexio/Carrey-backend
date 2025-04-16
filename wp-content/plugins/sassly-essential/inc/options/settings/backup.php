<?php 

   // backup option
   CSF::createSection( 'sassly_settings', array(
           
    'title'  => esc_html__( 'Backup Options', 'sassly-essential' ),
    'icon'   => 'fa fa-share-square-o',
    'fields' => array(
        array(
            'id'    => 'backup_options',
            'type'  => 'backup',
            'title' => esc_html__( 'Backup Your All Options', 'sassly-essential' ),
            'desc'  => esc_html__( 'If you want to take backup your option you can backup here.', 'sassly-essential' ),
        ),
    ),
) );