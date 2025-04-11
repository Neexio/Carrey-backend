<!-- Header area start -->
<div class="pd-header"> 
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
<<?php echo esc_attr($settings['header_size']); ?> class="wcf-header-pin-js layout-1 header__area-2 wcf-landing-page-padding <?php echo esc_attr($settings['mobile_direction']); ?> ">
    <div class="header__inner-2" data-urlattr="<?php echo esc_attr($settings['enable_url_attr']); ?>">
      <button class="header__navicon-2">
      <?php $close_icon = sassly_render_elementor_icons($settings['close_icon']); ?>
        <span id="header_naviconclose_2" class="close">
          <?php if($close_icon =='' ){ ?>
            <i class="icon-wcf-close"></i>
          <?php }else{           
            \Elementor\Icons_Manager::render_icon( $settings['close_icon'], [ 'aria-hidden' => 'true' ] );
           } ?>
        </span>
      </button>
      <?php if($site_image !=''){ ?>
      <div class="header__logo-2">
        <a href="<?php echo esc_url(home_url('/')); ?>">
			    <img src="<?php echo esc_url($site_image); ?>" class="show-dark" alt="<?php echo esc_attr__('Site Logo','sassly-essential') ?>">          
        </a>
      </div>           
      <?php } ?>
      <?php if(!empty($settings['menu_list'])) { ?>
        <div class="header__nav-2">
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
               \Elementor\Icons_Manager::render_icon( $settings['search_icon'], [ 'aria-hidden' => 'true' ] );
             } ?>
          </button>
        </form>
      </div>
      <?php } ?>
      <?php if($settings['copyright_texts'] !=''){ ?>
	      <div class="copyright">
		    <?php echo wpautop($settings['copyright_texts']); ?>
	      </div>
      <?php } ?>
    </div>
</<?php echo esc_attr($settings['header_size']); ?>>
<!-- Header area end -->

