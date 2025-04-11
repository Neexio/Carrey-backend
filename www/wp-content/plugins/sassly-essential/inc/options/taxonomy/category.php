<?php 

// Control core classes for avoid errors
if( class_exists( 'CSF' ) ) {

    //
    // Set a unique slug-like ID
    $cat_prefix = 'sassly_category_options';
  
    //
    // Create taxonomy options
    CSF::createTaxonomyOptions( $cat_prefix, array(
      'taxonomy'  => 'category',
      'data_type' => 'serialize', // The type of the database save options. `serialize` or `unserialize`
    ) );
  
    //
    // Create a section
    CSF::createSection( $cat_prefix, array(
      'fields' => array(
  
       
  
      )
    ) );
  
  }
  