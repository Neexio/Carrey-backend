<?php 

namespace Wcf\Cpt;

abstract Class Custom_Post{
   public $posts = null;
    public function init( $type, $singular_label, $plural_label, $settings = array() ){
        
        $default_settings = array(

            'labels' => array(
                'name'               => $plural_label,
                'singular_name'      => $singular_label,
                'add_new_item'       => esc_html__('Add New '.$singular_label, 'wcf'),
                'edit_item'          => esc_html__('Edit '.$singular_label, 'wcf'),
                'new_item'           => esc_html__('New '.$singular_label, 'wcf'),
                'view_item'          => esc_html__('View '.$singular_label, 'wcf'),
                'search_items'       => esc_html__('Search '.$plural_label, 'wcf'),
                'not_found'          => esc_html__('No '.$plural_label.' found', 'wcf'),
                'not_found_in_trash' => esc_html__('No '.$plural_label.' found in trash', 'wcf'),
                'parent_item_colon'  => esc_html__('Parent '.$singular_label, 'wcf'),
                'menu_name'          => $plural_label
            ),
            'public'        => true,
            'has_archive'   => true,
            'menu_icon'     => '',
            'menu_position' => 20,
            'supports'      => array(
                'title',
                'editor',
                'thumbnail'
            ),
            'rewrite' => array(
                'slug' => sanitize_title_with_dashes($plural_label)
            )
        );

        $this->posts[$type] = array_merge($default_settings, $settings);
    }

    public function register_custom_post(){
        
        $post_types = [];
        foreach($this->posts as $key => $value) {
            register_post_type($key, $value);           
            add_post_type_support( strtolower($key), 'elementor' );
            flush_rewrite_rules( false );
        }
        

    }
}
