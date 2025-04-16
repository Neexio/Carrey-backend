  <!-- offcanvas start  -->
  <div class="open-offcanvas wcf--info-animated-offcanvas" data-content_source="<?php echo esc_attr($settings['content_source']); ?>" data-preset="<?php echo esc_attr($preset_style); ?>">
        <?php if( $settings['menu_button_text'] == '' ){ ?>
					    <?php if($bar !=''){ ?>
		                <img src="<?php echo esc_url($bar); ?>" />
		                <?php echo sassly_get_attachment_image_html( $settings, 'thumbnail', 'sticky_bar', ['class' => 'wcf-sticky-bar'] ); ?>
		            <?php }else{ ?>
		                <span class="menu-icon-2 light-dash"><span></span></span>
		            <?php } ?>
	            <?php }else{ ?>
	                <?php echo esc_html($settings['menu_button_text']); ?>
	      <?php } ?>
	</div>
  <div class="offcanvas-4__area wcf-element-transfer-to-body wcf-offcanvas-gl-style">
    <div class="offcanvas-4__inner">
      <div class="offcanvas-4__button-wrapper offcanvas-close__button-wrapper offcanvas--close--button-js">
        <button class="text-close-button">       
           <?php if($settings['default_close_contentss'] =='yes') { ?>
                <div class="bars">
                  <?php echo esc_html__( 'Close' , 'sassly-essential' ); ?>
                  <span></span>
                  <span></span>
                </div>
					      <?php }else{ ?>
                  <?php echo esc_html( $settings[ 'close_text' ] ); ?>
                  <?php if(sassly_render_elementor_icons($settings['close_icon'])){ ?>
  				          <div class="off-close-icon">
  						        <?php echo \SasslyEssentialApp\Inc\Iinherit\Wcf_Icon_Manager::render_icon( $settings['close_icon'], [ 'aria-hidden' => 'true' ] );
  						        ?>
  				          </div>
  					      <?php } ?>
					      <?php } ?>
        </button>        
      </div>
      <div class="offcanvas-4__content-wrapper">
        <?php if(!empty($settings['follow_info'])){ ?>
          <div class="offcanvas-4__social d-none d-md-flex">
            <div class="offcanvas-4__social-links offcanvas__social__links">
              <?php foreach($settings['follow_info'] as $fol) {
               if ( ! empty( $fol['link']['url'] ) ) {               
                $this->add_link_attributes( 'follow_me_link', $fol['link'] );
              }
              ?>
                <a class="elementor-repeater-item-<?php echo esc_attr( $fol['_id'] ); ?>" <?php echo $this->get_render_attribute_string( 'follow_me_link' ); ?>><?php echo \SasslyEssentialApp\Inc\Iinherit\Wcf_Icon_Manager::render_icon( $fol['icon'] , [ 'aria-hidden' => 'true' ] ); ?></a>
              <?php } ?>              
            </div>
            <h4 class="offcanvas-4__social-title has-left-line"><?php echo $settings['social_heading']; ?></h4>
          </div>
        <?php } ?>
        <?php if($settings['show_logo'] == 'yes'){ ?>
        <div class="offcanvas-4__thumb d-none d-lg-block">
        <img src="<?php echo esc_url($this->logo_image_url($size)); ?>" alt="Image">
        </div>
        <?php } ?>
        <div class="offcanvas-4__menu-wrapper">         
          <?php          
            wp_nav_menu( array(
              'menu'            =>  $menu_selected,
              'container'       => 'nav', 
              'container_class' => 'offcanvas-4__menu hover-border-move', 
            ) );
          ?>
        </div>
        <?php if(!empty($contact_info)){ ?>
        <div class="offcanvas-4__meta">
          <ul>
            <li class="d-flex flex-column">
              <?php foreach($contact_info as $contact){ ?>                
                  <?php if($contact['list_type'] ==='email'){ ?>
                    <a class="" href="mailto:<?php echo esc_attr($contact['link']); ?>"><?php echo esc_html($contact['list_content']) ?></a>
                  <?php } ?>
                  <?php if($contact['list_type'] ==='phone'){ ?>
                    <a href="tel:<?php echo esc_attr($contact['link']); ?>"><?php echo esc_html($contact['list_content']) ?></a>
                  <?php } ?>                       
              <?php } ?>      
            </li>
            <?php foreach($contact_info as $contact){ ?> 
              <li>  
                  <?php if($contact['list_type'] ==='address'){ ?>
                    <a><?php echo nl2br($contact['list_content']) ?></a>
                  <?php } ?>
              </li>   
            <?php } ?>
          </ul>
        </div>
        <?php } ?>
      </div>
    </div>
  </div>
  <!-- offcanvas end  -->