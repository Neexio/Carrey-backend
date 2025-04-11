<?php

// Control core classes for avoid errors
if( class_exists( 'CSF' ) ) {

    //
    // Set a unique slug-like ID
    $prefix = '_crowdyflow_menu_options';
  
    //
    // Create profile options
    CSF::createNavMenuOptions( $prefix, array(
      'data_type' => 'unserialize',
    ) );
  
    //
    // Create a section
    CSF::createSection( $prefix, array(
      'fields' => array(
   
        array(
          'id'    => '_crowdyflow_theme_menu_item_cls',
          'type'  => 'text',
          'title' => 'Menu Classes',
        ),
  
      )
    ) );
  
  }
  