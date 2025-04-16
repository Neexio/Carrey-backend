<?php
  
  $header_cls = [];
  $header_menu_icon         = SASSLY_IMG . '/icons/menu.svg';
  $logo                     = SASSLY_IMG . '/logo-light.svg';
  $light_logo               = SASSLY_IMG . '/logo-dark.svg';
  $sassly_light_logo_url      = sassly_option( 'logo' ) == '' ? $light_logo : sassly_option( 'logo' );  
  $button_enable            = sassly_option('button_enable',1);
  $button_text              = sassly_option('button_text', 'Consultation');
  $button_link              = sassly_option('button_link', '#');
  $header_tel               = sassly_option('header_tel_text', '');
  $header_tel_number        = sassly_option('header_tel_number', '0282541320');
  $menu_icon                = sassly_option('header_menu_icon');
  
  if( is_array( $menu_icon)  && isset( $menu_icon[ 'url' ] ) && $menu_icon[ 'url' ]  !='' ){
    $header_menu_icon = sassly_option('header_menu_icon')['url'];
  }
  
  $sassly_option = sassly_option('opt-tabbed-general');
	$button_style = isset($sassly_option['gl_button_style']) ? $sassly_option['gl_button_style']: 'btn-hover-divide';
	
  if( sassly_is_transparent_header() ) {
    $header_cls[] = 'bg-transparent';
    $header_cls[] = 'zi-3';
    $header_cls[] = 'pos-abs';
  } else {
    $header_cls[] = 'position-relative';
    $header_cls[] = 'zi-3';
  }
    
  $header_cls[] = sassly_is_blog_banner_active();   
    
?>
 <?php get_template_part('template-parts/headers/content','offcanvas'); ?>
  <!-- Header area start -->
  <header class="header__area-8 default-blog-header plr-150 <?php echo esc_attr(implode( ' ' , $header_cls )); ?>">
    <div class="lawyer-header__inner">
      <div class="header__logo-8">
        <?php echo sassly_text_logo()?'<h1 class="logo-title">':''; ?> 
              <a href="<?php echo esc_url(home_url('/')); ?>">
                  <?php if(sassly_text_logo()): ?> 
                      <?php echo esc_html(sassly_text_logo()); ?>
                  <?php else: ?>
                      <img class="show-light" src="<?php echo esc_url($sassly_light_logo_url); ?>" alt="<?php echo get_bloginfo('name') ?>">
                  <?php endif; ?>
              </a>
          <?php echo sassly_text_logo()?'</h1>':''; ?> 
      </div>
      <div class="header__nav-8">
        <nav class="main-menu main-menu-js">
          <?php get_template_part( 'template-parts/navigations/nav', 'primary' ); ?>
        </nav>
      </div>
      <div class="header__nav-icon-8">
        <?php if($header_tel !=''): ?>
        <a class="phone" href="tel:<?php echo esc_attr($header_tel_number); ?>"><?php echo esc_html($header_tel); ?></a>
        <?php endif; ?>
        <?php if($button_enable): ?>
        <div class="header-btn-8">
          <a class="wcf--theme-btn wc-btn-primary <?php echo esc_attr($button_style); ?>" href="<?php echo esc_url($button_link); ?>"><?php echo esc_html($button_text); ?></a>
        </div>
        <?php endif ?>       
        <button class="menu-icon-8 info-default-offcanvas" data-bs-toggle="offcanvas" data-bs-target="#offcanvasOne">
          <img src="<?php echo esc_url($header_menu_icon); ?>" alt="<?php echo esc_attr__('Offcanvas menu','sassly'); ?>">
        </button>       
      </div>
    </div>
  </header>
  <!-- Header area end -->




