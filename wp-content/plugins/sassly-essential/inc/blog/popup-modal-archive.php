<?php 

  $json_data = sassly_get_config_value_by_name('elementor/archive');
  $args = array(
    'numberposts' => 15,
    'post_type' => ['wcf-single-post'],
    'post_status' => array('publish')    
  );

  $latest_posts = get_posts( $args );
  $active_id = get_option('wcf-elementor-post-layout-id');
  
  $hf_page  = admin_url( 'edit.php?post_type=wcf-single-post' );
  $_hf_html = sprintf( '<a href="%s" target="_blank">%s</a>' , esc_url($hf_page), esc_html__('There is no layout manage From Builder Page','sassly-essential') );
?>
<style>
  .wcf-single-tpl-wrapper .wcf--blog-builder-list {
      display: flex;
      gap: 80px;
      flex-wrap: wrap;
  }
  .wcf-hover-element{
      visibility: hidden;
      opacity: 0;
      transition: 0.3s;
  }
  .wcf-image-tpl:hover .wcf-hover-element{
     visibility: visible;
     opacity: 1;
  }
  .wcf-image-tpl {
      position: relative;
  }
  .wcf-image-tpl.active{
    border: 3px solid #db4d4d;
  }
  
  .wcf-image-tpl::after {
      position: absolute;
      width: 100%;
      height: 100%;
      left: 0;
      top: 0;
      background: #663399cf;
      content: "";
      z-index: 0;
      opacity: 0;
      transition: all 0.3s;
  }
  .wcf-image-tpl:hover:after {
      opacity: 1;
  }
  .wcf-image-tpl img {
      height: 100%;
  }
  .wcf-hover-element {
      cursor: pointer;
      visibility: hidden;
      opacity: 0;
      transition: 0.3s;
      position: absolute;
      width: 100%;
      height: 100%;
      display: flex;
      align-items: center;
      justify-content: center;
      top: 0;
      left: 0;
      z-index: 5;
      color: white;
  }
  
  /** Modal Popup */
  
  /* [Object] Modal
   * =============================== */
  .wcf-modal {
    opacity: 0;
    visibility: hidden;
    position: fixed;
    top: 0;
    right: 0;
    bottom: 0;
    left: 0;
    text-align: left;
    background: rgba(0,0,0, .9);
    transition: opacity .25s ease;
    text-align: center;
    z-index: 99999;
  }
  
  .wcf-modal h2 {
      border-bottom: 1px solid #e1e1e1;
      padding-top: 5px;
      padding-bottom: 20px;
      margin: 0;
  }
  
  .wcf-modal__bg {
    position: absolute;
    top: 0;
    right: 0;
    bottom: 0;
    left: 0;
    cursor: pointer;
  }
  
  .wcf-modal-state,
  .wcf-modal-state{
    display: none !important;
  }
  
  .wcf-modal-state:checked + .wcf-modal {
    opacity: 1;
    visibility: visible;
  }
  
  .wcf-modal-state:checked + .wcf-modal .wcf-modal__inner {
    top: 0;
  }
  
  .wcf-modal__inner {
      transition: top .25s ease;
      position: absolute;
      top: 0;
      right: 0;
      bottom: 0;
      left: 0;
      width: 100%;
      margin: auto;
      overflow: auto;
      background: #fff;
      border-radius: 5px;
      padding: 1em 2em;
      height: 100%;
  }
  
  .wcf-modal__close-import {
    position: absolute;
    right: 100px;
    top: 26px;
    width: 1.1em;
    height: 1.1em;
    cursor: pointer;
  }
  
  .wcf-modal__close-import:after,
  .wcf-modal__close-import:before {
    content: '';
    position: absolute;
    width: 2px;
    height: 1.5em;
    background: #ccc;
    display: block;
    transform: rotate(45deg);
    left: 50%;
    margin: -3px 0 0 -1px;
    top: 0;
  }
  
  .wcf-modal__close-import:hover:after,
  .wcf-modal__close-import:hover:before {
    background: #aaa;
  }
  
  .wcf-modal__close-import:before {
    transform: rotate(-45deg);
  }
  
  @media screen and (max-width: 768px) {
  	
    .wcf-modal__inner {
      width: 70%;
      height: 70%;
      box-sizing: border-box;
    }
  }
  
  .wcf-content-install h1{
   line-height: 1.2;
  }

</style>
<script>
 // 

 document.addEventListener("DOMContentLoaded", function(event) {
    var ajax_path = '<?php echo admin_url( 'admin-ajax.php' ) ?>';
    jQuery(document).on('click', '.wcf--blog-tpl-install-remote' ,function(e){     
       jQuery('.wcf-single-tpl-wrapper h1').html('Template Importing . Please Wait');     
       jQuery('.wcf-image-tpl').removeClass('active');
       jQuery.ajax({
         type : 'post',        
         url : ajax_path,
         data : {action: "wcf_post_tpl_remote_import", tpl_id : jQuery(this).attr('data-id')},
         success: function(response) {            
            jQuery('.wcf-single-tpl-wrapper h1').html(response.message);
            setTimeout(() => {
                jQuery('.wcf-single-tpl-wrapper h1').html('');
            }, 3000);           
         }
      });
    });

    jQuery(document).on('click', ".wcf-post-layout-import-modal" ,function(){
      jQuery('.wcf--remote-layouts').css({opacity : 1,visibility: 'visible'});
    }); 
    
    jQuery(document).on('click', ".wcf-modal__close-import" ,function(){
        jQuery('.wcf--remote-layouts').css({opacity : 0,visibility: 'hidden'});
    });
    
});
</script>
<div class="wcf-modal wcf--remote-layouts">
  <label class="wcf-modal__bg" for="wcf-elementor-popup"></label>
  <div class="wcf-modal__inner">
    <label class="wcf-modal__close-import" for="wcf-elementor-popup"></label>
    <h2><?php echo esc_html__('Blog Template Layout Import','sassly-essential') ?></h2>
      <div class="wcf-content-install">
      <div class="wcf-single-tpl-wrapper">
            <h4><?php // echo esc_html__('Elementor Layout','sassly-essential') ?></h4>
            <h1></h1>
            <div class="wcf--blog-builder-list">
            <?php foreach($json_data as $item){ ?> 
                <div class="wcf-image-tpl <?php //echo $active_id == $item->ID ? 'active' : ''; ?>">
                    <img src="<?php echo $item['preview_image_big']; ?>" />
                    <div class="wcf-hover-element">
                        <span data-id="<?php echo $item['uniq_id']; ?>" class="wcf--blog-tpl-install-remote"><?php echo esc_html__('Activate','sassly-essential'); ?></span>
                    </div>
                </div>
                <?php } ?>
                <?php echo empty($json_data) ? $_hf_html : ''; ?>
            </div>
        </div>
      </div>
  </div>
</div>