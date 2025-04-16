   <?php
   
        if(is_404()){
            return;
        }
        
        if(is_page()){
            
            if(!sassly_meta_option(get_the_id(),'newslatter_enable',0)){
               return;     
            }
        }else{
            if( !sassly_option('newslatter_enable','0') ){
                return;  
            }
        }
        
       
   ?>
   
   <!--====== SUBSCRIBE PART START ======-->

   <div class="subscribe-area subscribe-3-area">
        <div class="container">
            <div class="row">
                <div class="col-lg-12"> 
                    <div class="subscribe-content text-center">
                        <h3 class="title"> <?php echo esc_html( sassly_option('newslatter_title_line_1') ); ?> <br> <?php echo esc_html( sassly_option('newslatter_title_line_2') ); ?> </h3>
                        <a class="main-btn" href="<?php echo esc_html( sassly_option('newslatter_button_link') ); ?>"> <?php echo esc_html( sassly_option('newslatter_button_text') ); ?> </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="subscribe-bg">
            <div class="subscribe-shape-1"></div>
        </div>
    </div>

    <!--====== SUBSCRIBE PART ENDS ======-->