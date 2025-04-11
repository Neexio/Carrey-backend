<?php
  
   $blog_author             = sassly_option( 'blog_single_author_box' , 0 ); 
   $user_id                 = get_the_author_meta( 'ID' );
   $sassly_profile_options    = get_user_meta( get_the_author_meta( 'ID' ) , 'sassly_profile_options', true );
   $social                  = isset( $sassly_profile_options[ 'social_share' ] ) ? $sassly_profile_options[ 'social_share' ]:'';

?> 
<?php if( $blog_author ): ?>
       <div class="blog-post-author d-block">
            <div class="thumb">
              <?php echo get_avatar( get_the_author_meta( 'ID' ), 120 ); ?>
            </div>
            <div class="content">
                <h4 class="title"><?php echo get_the_author(); ?></h4>
                <p> <?php echo get_the_author_meta('user_description'); ?> </p>
                <?php if(is_array($social)):  ?>
                    <ul>
                        <?php foreach($social as $item): ?>
                            <li><a href="<?php echo esc_url($item['bookmark_url']); ?>"><i class="<?php echo esc_attr($item['bookmark_icon']); ?>"></i></a></li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
        </div>
        
<?php endif; ?>