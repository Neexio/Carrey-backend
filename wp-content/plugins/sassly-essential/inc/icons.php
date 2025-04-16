<?php 

namespace SasslyEssentialApp\Inc;

Class sassly_Additional_Icon{

    public function __construct(){
        add_filter( 'elementor/icons_manager/additional_tabs', [$this,'theme_custom_icon_manager']);
        add_filter( 'csf_field_icon_add_icons', [$this, 'csf_icon_field'] );        
        
    }
    
    public function theme_custom_icon_manager($settings){
    
        if(!defined('SASSLY_CSS')){
          return $settings;
        }
  
        $json_data = SASSLY_ESSENTIAL_URL . 'assets/js/elementor-icon.js';

        $settings['sassly-icon-set'] = [
           'name'          => 'wcf-icon-set',
           'label'         => esc_html__( 'Sassly Icons', 'sassly-essential' ),
           'url'           => SASSLY_CSS . '/custom-icons.css',
           'enqueue'       => [ SASSLY_CSS . '/custom-icons.css' ],
           'prefix'        => 'sassly-',
           'displayPrefix' => 'sassly-theme',
           'labelIcon'     => 'fab fa-font-awesome-alt',
           'ver'           => '2.0',
           'fetchJson'     => $json_data
        ];
       
        return $settings;  
    }
    
    public function csf_icon_field($icons){
 
        $newicons[]  = array(
            'title' => 'Wcf Icons',
            'icons' => $this->icons_array()
          );
       
         // $icons = array_reverse( $icons );
          return $newicons;
     }
     
     public function icons_array(){
         return array(
            'wcf-icon icon-wcf-apple-store',
            'wcf-icon icon-wcf-arrow-left1',
            'wcf-icon icon-wcf-arrow-right1',
            'wcf-icon icon-wcf-arrow-up1',
            'wcf-icon icon-wcf-check1',
            'wcf-icon icon-wcf-check-fill',
            'wcf-icon icon-wcf-cross',
            'wcf-icon icon-wcf-envelop1',
            'wcf-icon icon-wcf-kite',
            'wcf-icon icon-wcf-play-icon',
            'wcf-icon icon-wcf-quote1',
            'wcf-icon icon-wcf-quote-style-2',
            'wcf-icon icon-wcf-snapchat',
            'wcf-icon icon-wcf-tiktok',
            'wcf-icon icon-wcf-arrow-down',
            'wcf-icon icon-wcf-arrow-long-down',
            'wcf-icon icon-wcf-arrow-right',
            'wcf-icon icon-wcf-arrow-right-2',
            'wcf-icon icon-wcf-arrow-right-3',
            'wcf-icon icon-wcf-arrow-right-4',
            'wcf-icon icon-wcf-arrow-up',
            'wcf-icon icon-wcf-arrow-up-2',
            'wcf-icon icon-wcf-arrow-up-3',
            'wcf-icon icon-wcf-arrow-up-4',
            'wcf-icon icon-wcf-arrow-up-5',
            'wcf-icon icon-wcf-check',
            'wcf-icon icon-wcf-check-2',
            'wcf-icon icon-wcf-close',
            'wcf-icon icon-wcf-location',
            'wcf-icon icon-wcf-menu-bar-1',
            'wcf-icon icon-wcf-menu-bar-2',
            'wcf-icon icon-wcf-paper-plane',
            'wcf-icon icon-wcf-phone',
            'wcf-icon icon-wcf-play-2',
            'wcf-icon icon-wcf-plus',
            'wcf-icon icon-wcf-quote',
            'wcf-icon icon-wcf-search',
            'wcf-icon icon-wcf-star-2',
            'wcf-icon icon-wcf-star-3',
            'wcf-icon icon-wcf-check-circle',
            'wcf-icon icon-wcf-wcf-Search',
            'wcf-icon icon-wcf-wcf-wcf-dribbble',
            'wcf-icon icon-wcf-youtube',
            'wcf-icon icon-wcf-xing',
            'wcf-icon icon-wcf-wordpress',
            'wcf-icon icon-wcf-whatsup',
            'wcf-icon icon-wcf-video',
            'wcf-icon icon-wcf-user-group',
            'wcf-icon icon-wcf-user',
            'wcf-icon icon-wcf-twitter-sq',
            'wcf-icon icon-wcf-twitter',
            'wcf-icon icon-wcf-tumblr',
            'wcf-icon icon-wcf-tags',
            'wcf-icon icon-wcf-sticky',
            'wcf-icon icon-wcf-share',
            'wcf-icon icon-wcf-wcf-search',
            'wcf-icon icon-wcf-reply',
            'wcf-icon icon-wcf-wcf-quote',
            'wcf-icon icon-wcf-wcf-plus',
            'wcf-icon icon-wcf-play-fill',
            'wcf-icon icon-wcf-pinterest',
            'wcf-icon icon-wcf-minus',
            'wcf-icon icon-wcf-mail',
            'wcf-icon icon-wcf-wcf-phone',
	         'wcf-icon icon-wcf-phone-fill',
	         'wcf-icon icon-wcf-love-fill',
	         'wcf-icon icon-wcf-love',
	         'wcf-icon icon-wcf-wcf-location',
	         'wcf-icon icon-wcf-linkdin-fill',
	         'wcf-icon icon-wcf-linkdin',
	         'wcf-icon icon-wcf-instragram',
	         'wcf-icon icon-wcf-hash',
	         'wcf-icon icon-wcf-facebook',
	         'wcf-icon icon-wcf-facebook-fill',
	         'wcf-icon icon-wcf-facebook-messenger',
	         'wcf-icon icon-wcf-envelop',
	         'wcf-icon icon-wcf-envelop-fill',
	         'wcf-icon icon-wcf-eye',
	         'wcf-icon icon-wcf-digg',
	         'wcf-icon icon-wcf-delicious',
	         'wcf-icon icon-wcf-calender',
	         'wcf-icon icon-wcf-checvron-right',
	         'wcf-icon icon-wcf-chevron-down',
	         'wcf-icon icon-wcf-chevron-left',
	         'wcf-icon icon-wcf-chevron-up',
	         'wcf-icon icon-wcf-clock',
	         'wcf-icon icon-wcf-wcf-close',
	         'wcf-icon icon-wcf-close-circle',
	         'wcf-icon icon-wcf-comment',
	         'wcf-icon icon-wcf-comment-fill',
	         'wcf-icon icon-wcf-comment-sq',
	         'wcf-icon icon-wcf-archive',
	         'wcf-icon icon-wcf-archive-fill',
	         'wcf-icon icon-wcf-arrow-down-1',
	         'wcf-icon icon-wcf-arrow-left',
	         'wcf-icon icon-wcf-arrow-right-1',
	         'wcf-icon icon-wcf-arrow-up-1',
	         'wcf-icon icon-wcf-at',
	         'wcf-icon icon-wcf-bar',
	         'wcf-icon icon-wcf-behance',
	         'wcf-icon icon-wcf-blogger',
	         'wcf-icon icon-wcf-angle-up',
	         'wcf-icon icon-wcf-angle-right',
	         'wcf-icon icon-wcf-angle-left',
	         'wcf-icon icon-wcf-angle-down',
	         'wcf-icon icon-wcf-wcf-menu',
	         'wcf-icon icon-wcf-volume-medium',
	         'wcf-icon icon-wcf-arrow-up-right2',
	         'wcf-icon icon-wcf-arrow-down-left2',
	         'wcf-icon icon-wcf-circle-right',
	         'wcf-icon icon-wcf-circle-left',
         );
     }

}

new sassly_Additional_Icon();