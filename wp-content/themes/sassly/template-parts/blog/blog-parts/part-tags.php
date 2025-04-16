<?php 
   $tag_list = sassly_has_post_tags();
  
?> 
 
  <ul class="default-details-tags justify-content-start">
      <?php foreach($tag_list as $item): ?>
         <li>
            <a href="<?php echo esc_url(get_term_link($item->term_id)); ?>">
               <?php 
                  echo esc_html($item->name);
               ?>
            </a>
         </li>
      <?php endforeach; ?>
  </ul>
   