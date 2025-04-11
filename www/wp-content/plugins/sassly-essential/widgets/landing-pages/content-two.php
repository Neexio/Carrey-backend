<!-- Header area start -->
<div class="pd-header layout-2"> 
    <?php if($settings['custom_mobile_image']['url'] !=''){ ?>
			<a href="<?php echo esc_url(home_url('/')); ?>">
				<img src="<?php echo esc_url($settings['custom_mobile_image']['url']); ?>" class="show-dark" alt="<?php echo esc_attr__('Site Logo','sassly-essential') ?>">          
      </a>
    <?php } ?>
    <button class="header__navicon-2">             
        <?php if($bar !=''){ ?>
		        <img id="header_navicon_2" class="icon" src="<?php echo esc_url($bar); ?>" alt="<?php echo esc_html__('custom bar','sassly-essential'); ?>"/>
		    <?php } ?>
    </button>
</div>
<<?php echo esc_attr($settings['header_size']); ?> class="wcf-header-pin-js layout-2 header__area-2 wcf-landing-page-padding <?php echo esc_attr($settings['mobile_direction']); ?> ">
    <div class="header__inner-2" data-urlattr="<?php echo esc_attr($settings['enable_url_attr']); ?>">
      <?php $close_icon = sassly_render_elementor_icons($settings['close_icon']); ?>
      <button class="header__navicon-2">
        <span id="header_naviconclose_2" class="close">
        <?php if($close_icon =='' ){ ?>
          <i class="icon-wcf-close"></i>
        <?php }else{         
          \Elementor\Icons_Manager::render_icon( $settings['close_icon'], [ 'aria-hidden' => 'true' ] );
         } ?>
        </span>
      </button>
      <?php if($site_image !=''){ ?>
      <div class="header__logo-2 resume-header__logo">
        <a href="<?php echo esc_url(home_url('/')); ?>">
			    <img src="<?php echo esc_url($site_image); ?>" class="show-dark" alt="<?php echo esc_attr__('Site Logo','sassly-essential') ?>">          
        </a>
      </div>           
      <?php } ?>
      <?php if(!empty($settings['menu_list'])) { ?>
        <div class="header__nav-2 resume-menu">
	        <ul class="sidebar-menu hover-space">
	           <?php foreach($settings['menu_list'] as $item) { 
	                
	                if ( ! empty( $item['website_link']['url'] ) ) {
						$this->add_link_attributes( 'website_link', $item['website_link'], true );
					}
			?>         
	            <li><a <?php echo $this->get_render_attribute_string( 'website_link' ); ?>><?php \Elementor\Icons_Manager::render_icon( $item['icon'], [ 'aria-hidden' => 'true' ] ); ?><?php echo esc_html($item['list_title']); ?></a></li>
	        <?php } ?>
	        </ul>
        </div>
      <?php } ?> 
      <?php if($settings['show_search'] == 'yes') { 
      $search_icon = sassly_render_elementor_icons($settings['search_icon']);
      ?>
      <div class="header__search-2">
        <form method="get" class="search-form" action="<?php echo home_url( '/' ); ?>">
          <input type="text" placeholder="search" value="<?php echo get_search_query() ?>" name="s">
          <button class="icon-search">
            <?php if($search_icon =='' ){ ?>
              <i class="fa-solid fa-magnifying-glass"></i>
              <?php }else{             
                \Elementor\Icons_Manager::render_icon( $settings['search_icon'] , [ 'aria-hidden' => 'true' ] );
             } ?>
          </button>
        </form>
      </div>
      <?php } ?>
      <?php   
      if ( ! empty( $settings['cta_website_link']['url'] ) ) {
        $this->add_link_attributes( 'cta_website_link', $settings['cta_website_link'] );        
      }
      $cta_icon = sassly_render_elementor_icons($settings['cta_icon']);
      ?>
      <?php if($settings['show_cta_button'] == 'yes') { ?>
        <a <?php echo $this->get_render_attribute_string( 'cta_website_link' ); ?> class="resume-header__hire-btn">
          <?php if($settings['show_cta_icon'] == 'yes') { ?>
            <span class="icon-img">
                <?php if($cta_icon){                
                  \Elementor\Icons_Manager::render_icon( $settings['cta_icon'], [ 'aria-hidden' => 'true' ] );
                 }else{ ?>
                  <svg width="13" height="13" viewBox="0 0 13 13" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M7.18175 3.38194C5.76314 3.23606 4.39077 2.61299 3.39324 1.82447L4.13169 0.858612C4.94517 1.50165 6.08214 2.01648 7.23501 2.13503C8.36884 2.25163 9.49205 1.98537 10.3735 1.11015C10.3773 1.10631 10.3811 1.10247 10.3849 1.09864L11.2964 2.0113L11.2898 2.01796L11.2831 2.02465C10.4384 2.8805 10.2324 4.05683 10.4574 5.32141C10.6853 6.6017 11.3491 7.90701 12.1366 8.87264L11.1863 9.62981C10.2742 8.51154 9.49786 6.99988 9.22534 5.46859C9.14984 5.04435 9.11258 4.61377 9.12336 4.18732L1.15794 12.1636L0.246514 11.2509L8.07918 3.4076C7.7793 3.4221 7.47896 3.41251 7.18175 3.38194Z"/>
                  </svg>
                <?php } ?>
            </span>
          <?php } ?>
          <?php echo esc_html($settings['cta_text']); ?>
        </a>
      <?php } ?>
     </div>
</<?php echo esc_attr($settings['header_size']); ?>>
<!-- Header area end -->