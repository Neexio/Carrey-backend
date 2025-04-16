
<?php

// option blog-details.php 
$general_social_share = sassly_social_share();

if($general_social_share){
?>
    <div class="default-details-share__wrapper">
        <p><i class="icon-wcf-share"></i><?php echo esc_html__('Share','sassly'); ?></p>
        <ul class="default-details-social-media">
           <?php foreach($general_social_share as $share){ ?>
            <li><a href="<?php echo esc_url(get_the_permalink()); ?>" data-social="<?php echo esc_attr($share['social_type']); ?>"><i class="<?php echo esc_attr($share['bookmark_icon']); ?>"></i></a> </li>
          <?php } ?>
        </ul>
     </div>
 <?php } ?>