<?php

namespace SasslyEssentialApp\Elemetor_Template;

class WCF_Elementor_Template_Preview
{
    public $option = null;
    
    function __construct() {
        add_filter( 'template_include', [ $this , 'preview_template_override' ], 9999 );
        add_filter( 'single_template', [ $this , 'preview_template_override' ], 9999 );
        add_filter( 'post_row_actions', [ $this ,'add_list_row_actions' ], 10, 2 );
        add_action( 'init', [$this,'custom_preview_route'] );
        add_filter( 'query_vars', [$this, 'custom_query_var']);        
       
    }
    
    public function custom_query_var( $query_vars ) {
        $query_vars[] = 'wcf-template-slug';
        return $query_vars;
    }
    
    function custom_preview_route(){
        add_rewrite_rule( 'wcf-elementor-template-preview/([a-z0-9-]+)[/]?$', 'index.php?wcf-template-slug=$matches[1]', 'top' );
    }
    
    public function preview_template_override($template){      
        
        if ( get_query_var( 'wcf-template-slug' ) && isset($_GET['source']) && $_GET['source']  === 'wcf-templates') {   
            $uri = $_SERVER['REQUEST_URI'];
            if (strpos($uri, 'wcf-elementor-template-preview') !== false) {
                $new_template = SASSLY_ESSENTIAL_DIR_PATH . "templates/elementor-public-preview.php";            
                return $new_template;
            }
        }
    
        if(isset($_GET['elementor_library']) && get_post_type() == 'elementor_library'){       
            $template =SASSLY_ESSENTIAL_DIR_PATH . "templates/elementor-preview.php";
            return $template;
        }
        
        return $template;
    }
    function add_list_row_actions( $actions, $post ) {       
        // Check for your post type.
        if ( $post->post_type == "elementor_library" ) {               
            $url = home_url(). '/wcf-elementor-template-preview/'.$post->post_name;
            $edit_link = add_query_arg( array( 'source' => 'wcf-templates','post' => $post->ID), $url );       
            $actions['wcf-preview'] = sprintf(
                '<a href="%1$s">%2$s</a>',
                $edit_link,
                esc_html__('WCF Preview','sassly-essential')
            );    
        }
    
        return $actions;
    }    
    
}

new WCF_Elementor_Template_Preview();