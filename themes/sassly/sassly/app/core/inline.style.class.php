<?php

namespace sassly\Core;

/**
* Enqueue.
*/

class InlineStyle  {
    /**
    * register default hooks and actions for WordPress
    * @return
    */

    public function register()
    {
        add_action( 'wp_enqueue_scripts',  array( $this, 'dynamic_style' ) );       
        add_action( 'wp_enqueue_scripts',  array( $this, 'blog_dynamic_style' ) , 20 );       
    }

    public function dynamic_style() {
       
        $unit                                = 'px';
       
        $gl_style                            = sassly_option('opt-tabbed-general');
   
        $footer_widget_title_color           = sassly_option('footer_widget_title_color');
        $sidebar_widget_title_margin_bottom  = sassly_option('sidebar_widget_title_margin_bottom');
        $sidebar_widget_title_color          = sassly_option('news__sidebars_widget_title_color');
        $sidebar_widget_title_margin_top     = sassly_option('sidebar_widget_title_margin_top');
        $sidebar_padding_top                 = sassly_option('news__sidebars_padding_top');
        $sidebar_padding_bottom              = sassly_option('news__sidebars_padding_bottom');
    
        $footer_widget_title_margin_bottom = sassly_option('footer_widget_title_margin_bottom');
        $footer_widget_title_margin_top    = sassly_option('footer_widget_title_margin_top');
        $footer_padding_top                = sassly_option('footer_padding_top');
        $footer_padding_bottom             = sassly_option('footer_padding_bottom');
        $footer_inner_padding_bottom       = sassly_option('footer_inner_padding_bottom');
        $copyright_padding_top             = sassly_option('copyright_padding_top');
        $copyright_padding_bottom          = sassly_option('copyright_padding_bottom');
        $copyright_margin_top              = sassly_option('copyright_margin_top');
        $footer_back_button_color          = sassly_option('footer_copyright_back_button_color');
        $back_button_progress_color        = sassly_option('footer_back_button_progress_color');
        $blog_content_container_size       = sassly_option('blog_content_container_size'); 
        $blog_admin_bar_top                = sassly_option('wp_admin_top_margin','32'); 
        
        $preloader_spinner_color1                = sassly_option('preloader_spinner_color1',''); 
        $preloader_spinner_color2                = sassly_option('preloader_spinner_color2',''); 
           
        $footer_widget_content_bottom_margin = sassly_option('footer_widget_content_bottom_margin');
        $opt_bannar_style                   = sassly_option('opt-bannar-style');
        $off_canvas_content_align           = sassly_option('offcanvas_content_alignment','left');
        $offcanvas_container_color          = is_page() ? sassly_meta_option(get_the_id(),'offcanvas_container_color') : sassly_option('offcanvas_container_color');   
 
        
        // preloader
        $custom_css = '';
        
        if($off_canvas_content_align != ''){
            $custom_css .="
            .offcanvas__area .offcanvas,
            .offcanvas__area .offcanvas__body{
                text-align:$off_canvas_content_align;
              }
           ";
        }
        if($preloader_spinner_color2 != ''){
            $custom_css .="
            .container-preloader .animation-preloader .spinner{
                border-color:$preloader_spinner_color2;
              }
          ";
        }
        
        if($preloader_spinner_color1 != ''){
            $custom_css .="
            .container-preloader .animation-preloader .spinner{
                border-top-color:$preloader_spinner_color1;
              }
          ";
        }
        
        
       // Banner page Overlay Color Opacity
       if(is_page()){

            $banner_page_image_overlay  = '';
            $banner_page_image_opacity  = '';
            $banner_page_image  = '';

            if(sassly_meta_option( get_the_ID(), 'banner_page_image' ) != ''){
              $banner_page_image = sassly_meta_option( get_the_ID(), 'banner_page_image' );
            }
            
            if(sassly_meta_option( get_the_ID(), 'banner_page_image_overlay' ) != ''){
                $banner_page_image_overlay =  sassly_meta_option( get_the_ID(), 'banner_page_image_overlay' );
            }elseif(sassly_option( 'banner_page_image_overlay' ) !=''){
              $banner_page_image_overlay =  sassly_option( 'banner_page_image_overlay' );
            }
                   
            if(sassly_meta_option( get_the_ID(), 'banner_page_image_opacity' ) != ''){
                $banner_page_image_opacity =  sassly_meta_option( get_the_ID(), 'banner_page_image_opacity' );
            }elseif(isset($opt_bannar_style[ 'banner_page_image_opacity' ]) && $opt_bannar_style[ 'banner_page_image_opacity' ] !=''){
              $banner_page_image_opacity =  $opt_bannar_style[ 'banner_page_image_opacity' ];
             
            }
            
            // color
            if($banner_page_image_overlay != ''){
                $custom_css .="
                .page .default-breadcrumb__area::before{
                      background:$banner_page_image_overlay;
                  }
              ";
            }
             // opacity
            if($banner_page_image_opacity != ''){
                $custom_css .="
                .page .default-breadcrumb__area::before{
                      opacity:$banner_page_image_opacity;
                  }
              ";
            }
          
          
       }else{
           
        $banner_blog_image_overlay  = '';
        $banner_blog_image_opacity  = '';

        if(sassly_option( 'banner_blog_image_overlay' ) !=''){
          $banner_blog_image_overlay =  sassly_option( 'banner_blog_image_overlay' );
        }

        if(sassly_option( 'banner_blog_image_opacity' ) !=''){
          $banner_blog_image_opacity =  sassly_option( 'banner_blog_image_opacity' );
        }

        // color
        if($banner_blog_image_overlay != ''){
            $custom_css .="
            .blog-banner .bg-overlay:before{
                  background:$banner_blog_image_overlay;
              }
          ";
        }
         // opacity
        if($banner_blog_image_opacity != ''){
            $custom_css .="
            .blog-banner .bg-overlay:before{
                  opacity:$banner_blog_image_opacity;
              }
          ";
        }  

       } 
       // page banner end

       if($sidebar_widget_title_margin_bottom != ''){
            $custom_css .="
            .default-sidebar__wrapper .widget .widget-title{
                  margin-bottom:$sidebar_widget_title_margin_bottom$unit
              }
          ";
        }
        
        if($sidebar_widget_title_margin_top != ''){
            $custom_css .="
            .default-sidebar__wrapper .widget .widget-title{
                  margin-top:$sidebar_widget_title_margin_top$unit
              }
          ";
        } 
       
        if($sidebar_padding_top != ''){
            $custom_css .="
            .default-sidebar__wrapper .widget{
                  padding-top: $sidebar_padding_top$unit
              }
          ";
        } 
        
        if($sidebar_padding_bottom != ''){
            $custom_css .="
            .default-sidebar__wrapper .widget{
                  padding-bottom: $sidebar_padding_bottom$unit
              }
          ";
        }
    
  
       // footer 
       if($footer_widget_title_margin_bottom != ''){
            $custom_css .="
            .jfooter-wrapper .widget-title{
                  margin-bottom:$footer_widget_title_margin_bottom$unit
              }
          ";
        }
        
        if($footer_widget_title_margin_top != ''){
            $custom_css .="
            .jfooter-wrapper .widget-title{
                  margin-top:$footer_widget_title_margin_top$unit
              }
          ";
        } 
       
        if($footer_padding_top != ''){
            $custom_css .="           
             body .jfooter-wrapper{
                  padding-top: $footer_padding_top$unit
              }
          ";
        } 
        if($footer_padding_bottom != ''){
            $custom_css .="           
             body .jfooter-wrapper{
                  padding-bottom: $footer_padding_bottom$unit
              }
          ";
        }

        if($footer_inner_padding_bottom != ''){
            $custom_css .="
            body .jfooter-wrapper .footer__item-8 {
                  padding-bottom: $footer_inner_padding_bottom$unit
              }
          ";
        }
        
        if($footer_widget_content_bottom_margin != ''){
            $custom_css .="
            .jfooter-wrapper .widget{
                  margin-bottom: $footer_widget_content_bottom_margin$unit
              }
          ";
        }
        
        if($footer_widget_title_color != ''){
            $custom_css .="
            .jfooter-wrapper .widget-title{
                text-decoration-color: $footer_widget_title_color
            }
          ";
        }
        // copyright
        if($copyright_margin_top != ''){
            $custom_css .="
            .jfooter-wrapper .jcopyright{
                margin-top: $copyright_margin_top$unit
              }
          ";
        } 
        
        if($copyright_padding_top != ''){
            $custom_css .="
            .jfooter-wrapper .jcopyright{
                padding-top: $copyright_padding_top$unit
              }
          ";
        }
        
        if($copyright_padding_bottom != ''){
            $custom_css .="
            .jfooter-wrapper .jcopyright{
                  padding-bottom: $copyright_padding_bottom$unit
              }
          ";
        }
       
        if(isset($gl_style['body_primary_color']) && $gl_style['body_primary_color'] != ''){
            $body_primary_color = $gl_style['body_primary_color'];
            $custom_css .="
            body.joya-gl-blog .default-blog__item-meta span,
            body.joya-gl-blog .wc-btn-underline::after
           {
                background: $body_primary_color;
                background-color: $body_primary_color;
            }
          ";

	        $custom_css .= "
            body {
                color: $body_primary_color;                
            }
          ";
        }
        
        if(isset($gl_style['body_secondary_color']) && $gl_style['body_secondary_color'] != ''){
        
            $body_secondary_color = $gl_style['body_secondary_color'];
                  
            $custom_css .="
                body.joya-gl-blog a.readmore__btn,       
                body.joya-gl-blog a:hover,               
                body.joya-gl-blog .post-meta span i,
                body.joya-gl-blog .wc-btn-primary:hover,
                body.joya-gl-blog .joya--entry-header .joya--entry-title a:hover{
                    color: $body_secondary_color;                
                }
            ";
            $custom_css .="
            .joya-gl-blog .default-details-tags li{
                background: $body_secondary_color;                
            }
        ";
            
            
        }
        
        if($footer_back_button_color != ''){
            $custom_css .="
            .progress-wrap svg path{
                  fill: $footer_back_button_color;
              }
          ";
        }
        
        if($back_button_progress_color != ''){
            $custom_css .="
            .progress-wrap svg.progress-circle path{
                stroke: $back_button_progress_color;
              }
          ";
        } 
       
       // 404 page banner
     
        if(isset($opt_bannar_style['banner_404_image_opacity']) && $opt_bannar_style['banner_404_image_opacity'] !=''){
            $custom_css .="
            .error404 .default-breadcrumb__area::before{
                  opacity: {$opt_bannar_style["banner_404_image_opacity"]}
              }
          ";
        }
  
        // Search blog
        if(isset($opt_bannar_style['banner_blog_image_opacity']) && $opt_bannar_style['banner_blog_image_opacity'] !=''){
            $custom_css .="
                .blog .default-breadcrumb__area::before,
                .search .default-breadcrumb__area::before{
                      opacity: {$opt_bannar_style["banner_blog_image_opacity"]}
                  }
            ";
        }
        
        if(is_search() && isset($opt_bannar_style['banner_search_image_opacity']) && $opt_bannar_style['banner_search_image_opacity'] !=''){
            $custom_css .="
                body.search .default-breadcrumb__area::before{
                      opacity: {$opt_bannar_style["banner_search_image_opacity"]}
                  }
            ";  
        }
      
       if($offcanvas_container_color !=''){
            $custom_css .="
              .offcanvas__gallery::after,
               body .offcanvas__logo::after{
                background-color: {$offcanvas_container_color}
                }
            ";  
       } 
       if($blog_admin_bar_top !='' && sassly_is_transparent_header()){
            $custom_css .="            
                .admin-bar > header:not(.sassly-theme-elementor-header-section){
                    margin-top: {$blog_admin_bar_top}px;
                }                
            ";  
       } 
       
       if($sidebar_widget_title_color !=''){
            $custom_css .="
              .default-sidebar__wrapper .default-sidebar__w-title{
                text-decoration-color: {$sidebar_widget_title_color};
                }
            ";
       }
       
       wp_add_inline_style( 'sassly-style', $custom_css );
    }
    public function blog_dynamic_style() {
        //SASSLY_ASSETS
        $custom_css = '';
        $fontlist = apply_filters('sassly_custom_webfonts',[
         
        ]);  
     
        foreach($fontlist as $font_family => $fonts){          
            foreach($fonts as $font){               
                $custom_css .= sprintf('
                        @font-face {
                          font-family: %s;
                          src: url(%s);
                          font-weight: %s;                         
                        }',$font_family,$font['src'],$font['weight']
                    );
            }        
        }       
       
       wp_add_inline_style( 'sassly-style', $custom_css );
    }

}