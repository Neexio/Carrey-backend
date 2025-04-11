<?php 

  $args = array(
    'numberposts' => 4,
    'post_type' => ['wcf-blog-tpl'],
    'post_status' => array('publish'),
    'meta_key' => 'wcf_essential_settings_blog_layoutactivate',
    'orderby' => 'meta_value_num',
  );
  
  $latest_posts = get_posts( $args );
  $active_id = get_option('wcf-elementor-blog-layout-id');
  
  $hf_page  = admin_url( 'edit.php?post_type=wcf-blog-tpl' );
  $_hf_html = sprintf( '<a href="%s" target="_blank">%s</a>' , esc_url($hf_page), esc_html__('There is no layout manage From Builder Page','sassly-essential') );
?>
<style>
.wcf-sing-tpl-wrapper .wcf--blog-builder-list {
    display: flex;
    gap: 80px;
    flex-wrap: wrap;
}
.wcf-image-tpl {
    position: relative;
}
.wcf-image-tpl.active{
  border: 3px solid #db4d4d;
}
.wcf-image-tpl img {
    height: 100%;
}

.wcf-tp-max-width img{
  max-width: 250px;
}
.wcf--blog-builder-list.theme-iptio > div {
  display: flex;
  flex-direction: column;
  gap: 9px;
}
</style>

<div class="wcf-sing-tpl-wrapper">
    <h4><a class="wcf-post-layout-import-modal button button-primary csf--button" data-id="blog_layout" href="javascript:void(0)"><?php echo esc_html__('Import From Library','sassly-essential') ?></a> </h4>
    <div class="wcf--blog-builder-list theme-iptio">
      <?php if($active_id){ ?> 
         <?php foreach($latest_posts as $item){ ?> 
          <div class="wcf-tp-max-width <?php echo $active_id == $item->ID ? 'active' : ''; ?>">
              <img src="<?php echo get_the_post_thumbnail_url($item->ID); ?>" />
              <label><?php echo get_the_title($item->ID); ?></label>
          </div>
          <?php } ?>       
        <?php } ?>       
    </div>
</div>

