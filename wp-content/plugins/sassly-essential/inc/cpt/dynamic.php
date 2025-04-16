<?php

namespace Wcf\Base\Custom_Post_Type;
use Wcf\Cpt\Custom_Post;

class Wcf_Dynamic_Cpt extends Custom_Post
{
    public $post_types_options = [];
	public function __construct() {   
      add_action( 'init', array( $this, 'create_post_type' ) ); 
      add_action( 'init', array( $this, 'create_taxonomy' ) ); 
      
      add_filter('pre_get_posts',[$this,'isearch_filter_post_type'], 200);

    }
    
    function isearch_filter_post_type($query) {
    
        if ( ! is_admin() && $query->is_main_query() ) {
        
            $search_result_post_types = sassly_option('search_result_post_types', false);            
            if ( $query->is_search && is_array($search_result_post_types)) {               
                $query->set('post_type', $search_result_post_types);
            }
            
        }
        
        return $query;
    }
    
    public function create_taxonomy(){
       
        $cpt_options = get_option(SASSLY_OPTION_KEY);
        if( isset( $cpt_options[ 'cpt_taxonomy_options' ] ) && is_array($cpt_options[ 'cpt_taxonomy_options' ]) && count($cpt_options[ 'cpt_taxonomy_options' ]) > 0){
            $options = $cpt_options[ 'cpt_taxonomy_options' ];
            foreach($options as $option){
            
                if($option['taxonomy_name']  != '' && isset($option['post_types'])){
                    $plural_label = $option['taxonomy_plural_label'];
                    $singular_label =  $option['taxonomy_label'];
                    $labels = array(
                        'name'              => _x( $option['taxonomy_plural_label'], $option['taxonomy_plural_label'], 'sassly-essential' ),
                        'singular_name'     => _x( $option['taxonomy_label'], $option['taxonomy_label'] , 'sassly-essential' ),
                        'search_items'      => __( "Search $plural_label", 'sassly-essential' ),
                        'all_items'         => __( "All $plural_label", 'sassly-essential' ),
                        'parent_item'       => __( "Parent $singular_label ", 'sassly-essential' ),
                        'parent_item_colon' => __( "Parent $singular_label :", 'sassly-essential' ),
                        'edit_item'         => __( "Edit $singular_label", 'sassly-essential' ),
                        'update_item'       => __( "Update $singular_label ", 'sassly-essential' ),
                        'add_new_item'      => __( "Add New $singular_label ", 'sassly-essential' ),
                        'new_item_name'     => __( "New $singular_label ", 'sassly-essential' ),
                        'menu_name'         => __( $singular_label , 'sassly-essential' ),
                    );
                
                    $args = array(
                        'hierarchical'       => true,
                        'labels'             => $labels,
                        'show_ui'            => true,
                        'show_admin_column'  => true,                    
                        'query_var'          => true,                       
                        'rewrite'            => isset($option[ 'slug' ]) && $option[ 'slug' ] !='' ? array( 'slug' => $option[ 'slug' ], 'with_front' => false) : false,
                        'publicly_queryable' => isset($option[ 'publicly_queryable' ]) && $option[ 'publicly_queryable' ] == '1' ? true : false,    
                        'show_in_menu'       => isset($option[ 'show_in_menu' ]) && $option[ 'show_in_menu' ] == '1' ? true : false,
                        'show_in_nav_menus'  => isset($option[ 'show_in_nav_menus' ]) && $option[ 'show_in_nav_menus' ] == '1' ? true : false,
                        'show_in_rest'       => isset($option[ 'show_in_rest' ]) && $option[ 'show_in_rest' ] == '1' ? true : false,
                        'show_ui'            => isset($option[ 'show_ui' ]) && $option[ 'show_ui' ] == '1' ? true : false,
                    );
                                    
                    register_taxonomy( $option['taxonomy_name'] , $option['post_types'] , $args );
                }
            }
            
        }
    }
    public function create_post_type(){
        $cpt_options = get_option(SASSLY_OPTION_KEY);
        
        if( isset( $cpt_options[ 'cpt_options' ] ) && is_array($cpt_options[ 'cpt_options' ]) && count($cpt_options[ 'cpt_options' ]) > 0){
            
            $options = $cpt_options[ 'cpt_options' ];
            foreach($options as $option){
                
                if($option[ 'posttype' ] !='' && $option[ 'singular_name' ] !='' && $option[ 'plural_name' ] !='' ){
                   
                    $this->post_types_options[] = [
                        'name'              => $option[ 'posttype' ],
                        'singular_title'    => $option[ 'singular_name' ],
                        'plural_title'      => $option[ 'plural_name' ],
                        'args' => array( 
                            'menu_icon'           => 'dashicons-text-page',
                            'supports'            => isset($option[ 'supports' ]) && count($option[ 'supports' ]) > 0 ? $option[ 'supports' ] : [ 'title' ],
                            'rewrite'             => isset($option[ 'slug' ]) && $option['slug'] !='' ? array( 'slug' => $option['slug'] ) : [],
                            'exclude_from_search' => $option[ 'exclude_from_search' ] == '1' ? true : false,
                            'has_archive'         => $option[ 'has_archive' ] == '1' ? true : false,     
                            'publicly_queryable'  => $option[ 'publicly_queryable' ] == '1' ? true : false,     
                            'hierarchical'        => false,
                            'show_in_menu'        => $option[ 'show_in_menu' ] == '1' ? true : false,
                            'show_in_nav_menus'   => isset($option[ 'show_in_nav_menus' ]) && $option[ 'show_in_nav_menus' ] == '1' ? true : false,
                            'menu_icon'           => isset( $option['icon']['url'] ) && $option[ 'icon' ][ 'url' ] !='' ? $option['icon']['url'] : false
                         ) 
                     ];
                     
                }
                
            }    
          
             
             foreach($this->post_types_options as $item ){        
                 $this->init( $item[ 'name' ], $item[ 'singular_title' ], $item[ 'plural_title' ],  $item[ 'args' ] ); 
             }
         
            $this->register_custom_post();    
        } 
            
    }
}

new Wcf_Dynamic_Cpt();