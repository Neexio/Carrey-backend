<?php 

    return [
        'layouts' => [
            [
                'type' => 'blog_layout',
                'base_api'          => 'https://crowdytheme.com/elementor/info-templates/wp-json/api/v1/blog-layouts',
                'action_fetch'      => 'wcf_blog_tpl_remote_import',
                'action_activate'   => 'wcf_blog_tpl_activate',
                'action_deactivate' => 'wcf_blog_tpl_dectivate',
                'action_status'     => 'wcf_blog_tpl_status',
                'content'           => '',
                'modal_heading'     => esc_html__('Blog Templates Import','sassly-essential'),
                'button_import'     => 'Import',
                'button_activate'   => 'Activate',
                'button_deactivate' => 'Deactivate'
            ],
            [
                'type' => 'search_layout',
                'base_api'          => 'https://crowdytheme.com/elementor/info-templates/wp-json/api/v1/search-layouts',
                'action_fetch'      => 'wcf_search_tpl_remote_import',
                'action_activate'   => 'wcf_search_tpl_activate',
                'action_deactivate' => 'wcf_search_tpl_dectivate',
                'action_status'     => 'wcf_search_tpl_status',
                'content'           => '',
                'modal_heading'     => esc_html__('Search Templates Import','sassly-essential'),
                'button_import'     => 'Import',
                'button_activate'   => 'Activate',
                'button_deactivate' => 'Deactivate'
            ],
            [
                'type' => 'error_layout',
                'base_api'          => 'https://crowdytheme.com/elementor/info-templates/wp-json/api/v1/error-layouts',
                'action_fetch'      => 'wcf_error_tpl_remote_import',
                'action_activate'   => 'wcf_error_tpl_activate',
                'action_deactivate' => 'wcf_error_tpl_dectivate',
                'action_status'     => 'wcf_error_tpl_status',
                'content'           => '',
                'modal_heading'     => esc_html__('404 Templates Import','sassly-essential'),
                'button_import'     => 'Import',
                'button_activate'   => 'Activate',
                'button_deactivate' => 'Deactivate'
            ],
            [
                'type' => 'post_layout',
                'base_api'          => 'https://crowdytheme.com/elementor/info-templates/wp-json/api/v1/post-layouts',
                'action_fetch'      => 'wcf_post_tpl_remote_import',
                'action_activate'   => 'wcf_post_tpl_activate',
                'action_status'     => 'wcf_post_tpl_status',
                'action_deactivate' => 'wcf_post_tpl_dectivate',
                'content'           => '',
                'modal_heading'     => esc_html__('Post Templates Import','sassly-essential'),
                'button_activate'   => 'Activate',
                'button_deactivate' => 'Deactivate',
                'button_import'   => 'Import',
            ],
        ],        
        'active' => false,
        [
            'type' => 'pagination',
            'perpage' => 10,
            'next'    => esc_html__('Next','sassly-essential'),
            'prev'    => esc_html__('Prev','sassly-essential'),
            'current' => 1
        ]
     
    ];
    
?>