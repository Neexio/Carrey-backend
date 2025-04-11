<?php
namespace sassly\Core;

/**
 * Sidebar and footer. widget 
 */
class Blog_Widgets
{
    /**
     * register default hooks and actions for WordPress
     * @return
     */
    public function register()
    {
        add_action( 'widgets_init', array( $this, 'widgets_init' ) );
    }

    /*
    *    Define the sidebar
    */
    public function widgets_init()
    {
       // Sidebar    
        register_sidebar( array(
                'name'          => esc_html__('Blog widget area', 'sassly'),
                'id'            => 'sidebar-1',
                'description'   => esc_html__('Appears on posts.', 'sassly'),
                'before_widget' => '<div id="%1$s" class="default-sidebar__content default-sidebar__widget widget mb-25 %2$s">',
                'after_widget'  => '</div>',
                'before_title'  => '<h3 class="widget-title default-sidebar__w-title mb-50">',
                'after_title'   => '</h3>',
        ) );

        // WooCommerce
	    register_sidebar( array(
		    'name'          => esc_html__('WooCommerce widget area', 'sassly'),
		    'id'            => 'woo',
		    'description'   => esc_html__('Appears on Shop.', 'sassly'),
		    'before_widget' => '<div id="%1$s" class="wcf-woo--widget %2$s">',
		    'after_widget'  => '</div>',
		    'before_title'  => '<h3 class="wcf-woo--title">',
		    'after_title'   => '</h3>',
	    ) );
        
        // Footer       
        register_sidebar(
            array(
                'name'          => esc_html__('Footer One', 'sassly'),
                'id'            => 'footer-one',
                'description'   => esc_html__('Footer one Widget.', 'sassly'),
                'before_widget' => '<div id="%1$s" class="footer-widget footer-1-widget widget %2$s">',
                'after_widget'  => '</div>',
                'before_title'  => '<h3 class="widget-title default-sidebar__w-title mb-50">',
                'after_title'   => '</h3>',
            )
        );

        register_sidebar(
            array(
                'name'          => esc_html__('Footer Two', 'sassly'),
                'id'            => 'footer-two',
                'description'   => esc_html__('Footer  widget.', 'sassly'),
                'before_widget' => '<div id="%1$s" class="footer-widget footer-2-widget widget %2$s">',
                'after_widget'  => '</div>',
                'before_title'  => '<h3 class="widget-title default-sidebar__w-title mb-50">',
                'after_title'   => '</h3>',
            )
        );

        register_sidebar(
            array(
                'name'          => esc_html__('Footer Three', 'sassly'),
                'id'            => 'footer-three',
                'description'   => esc_html__('Footer widget.', 'sassly'),
                'before_widget' => '<div id="%1$s" class="footer-widget footer-3-widget widget %2$s">',
                'after_widget'  => '</div>',
                'before_title'  => '<h3 class="widget-title default-sidebar__w-title mb-50">',
                'after_title'   => '</h3>',
            )
        );
        
        register_sidebar(
            array(
                'name'          => esc_html__('Footer Four', 'sassly'),
                'id'            => 'footer-four',
                'description'   => esc_html__('Footer widget.', 'sassly'),
                'before_widget' => '<div id="%1$s" class="footer-widget footer-4-widget widget %2$s">',
                'after_widget'  => '</div>',
                'before_title'  => '<h3 class="widget-title default-sidebar__w-title mb-50">',
                'after_title'   => '</h3>',
            )
        );    
      
    }
}
