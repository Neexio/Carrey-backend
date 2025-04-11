<?php

namespace SasslyEssentialApp\Inc;

class Wcf_Elementor_Templates_Library {

    private static $instance = null;
   
    public function __construct() {
        
        if( !sassly_theme_service_pass() ){
            return;
        }
        
        add_filter('wcf_pro_template_status', [$this, 'update_template_st']);
    }

    public static function getInstance(){
      
        if (self::$instance == null){
          self::$instance = new self();
        }
        
        return self::$instance;    
    }
    
    public function update_template_st(){  
        return false;
    }
      
}


Wcf_Elementor_Templates_Library::getInstance();

