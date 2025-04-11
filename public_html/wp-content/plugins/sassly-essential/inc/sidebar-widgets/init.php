<?php 

    include_once(SASSLY_ESSENTIAL_DIR_PATH.'inc/sidebar-widgets/recent-post.php');
    include_once(SASSLY_ESSENTIAL_DIR_PATH.'inc/sidebar-widgets/social.php');
    include_once(SASSLY_ESSENTIAL_DIR_PATH.'inc/sidebar-widgets/cta-banner.php');
    
    add_action( 'widgets_init', 'sassly_register_sidebar_widgets' );
    
    function sassly_register_sidebar_widgets() {
    	register_widget( 'Sassly_Recent_Post' );
    	//register_widget( 'sassly_Theme_Social' );
    	register_widget( 'Sassly_Banner_Widget' );
    }
