<?php
/**
 * the template for displaying all posts.
 */

  get_header();   
  $option = sassly_option( 'blog_post_preset_grp' );   
  if( isset($option['preset_blog_banner']) && $option['preset_blog_banner'] ){
    get_template_part( 'template-parts/banner/content', 'banner-blog' );
  }  
  $preset_blog_view             = isset($option['preset_blog_view']) ? $option['preset_blog_view'] : 1;    
	$has_social_share             = sassly_social_share();
	$general_social_share         = sassly_social_share();   
?>
<style>
  .athletic__blogdetails {
      background-color: #F6EEE;
  }
  .plr-100 {
      -webkit-padding-start: 100px;
      padding-inline-start: 100px;
      -webkit-padding-end: 100px;
      padding-inline-end: 100px;
  }
  .athletic__blogdetails-wrapper {
      display: -ms-grid;
      display: grid;
      -ms-grid-columns: 1fr 10px 1fr;
      grid-template-columns: repeat(2, 1fr);
      gap: 10px;
  }
  .athletic__blogdetails-thumb img {
      width: 100%;
      -webkit-margin-start: -100px;
      margin-inline-start: -100px;
  }
  .athletic__blogdetails-meta {
      display: -webkit-box;
      display: -ms-flexbox;
      display: flex;
      gap: 45px;
      padding-bottom: 15px;
  }
  
  .athletic__blogdetails-meta li {  
      color: #555555;
      font-weight: 400;
      font-size: 18px;
      line-height: 1.5;
      text-transform: uppercase;
      position: relative;
  }
  .athletic__blogdetails-meta li::before {
      position: absolute;
      content: "";
      inset-inline-end: -32px;
      top: 50%;
      height: 1px;
      width: 24px;
      background-color: #555555;
  }
  .athletic__blogdetails-titlewrapper {
      padding-bottom: 15px;
  }
  .abd-blog__title {
      font-weight: 500;
      font-size: 90px;
      line-height: 1;
      text-transform: uppercase;
      color: #1C1D20;
  }
  .social-share__title {
      font-weight: 400;
      font-size: 18px;
      line-height: 12px;
      color: #1C1D20;
  }
  .social-share__media {
      gap: 10px;
      display: -webkit-box;
      display: -ms-flexbox;
      display: flex;
      -ms-flex-wrap: wrap;
      flex-wrap: wrap;
  }
  .social-share__media li a {
      color: #1C1D20;
      text-transform: uppercase;
      font-weight: 500;
      font-size: 10px;
      line-height: 1;
      border: 1px solid #1C1D20;
      border-radius: 50px;
      padding: 7px 15px;
      display: inline-block;
  }
  .social-share__media li a i {
      -webkit-padding-end: 4px;
      padding-inline-end: 4px;
  }
  .social-share__wrapper {
      gap: 15px;
      display: -webkit-box;
      display: -ms-flexbox;
      display: flex;
      -webkit-box-align: center;
      -ms-flex-align: center;
      align-items: center;
  }
</style>
 <main class="athletic__main">
  <!-- Blog Details start -->
  <div class="athletic__blogdetails plr-100 pin__area">
            <div class="athletic__blogdetails-wrapper">
              <div class="athletic__blogdetails-left">
                <div class="athletic__blogdetails-thumb cf_image pin__element">
                  <?php the_post_thumbnail('full', ['class' => 'responsive--full']); ?>
                </div>
              </div>

              <div class="athletic__blogdetails-right pt-100">
                <ul class="athletic__blogdetails-meta">
                  <li>
                    <?php                   
                      if(sassly_option('blog_author',0)){
                        echo esc_html__('by','sassly-essential') .' '. get_the_author(); 
                      }
                    ?>
                  </li>
                  <?php if(sassly_option('blog_date','1')){ ?>
                  <li><?php echo get_the_date(get_option( 'date_format' )); ?></li>
                  <?php } ?>
                  <?php if($preset_blog_view){ ?>
                  <li><?php echo get_post_meta(get_the_id(),'sassly_post_views_count',true) ?> <?php echo esc_html__('Views','sassly-essential'); ?></li>
                  <?php } ?>
                </ul>

                <div class="athletic__blogdetails-titlewrapper">
                  <h1 class="abd-blog__title"><?php the_title(); ?></h1>
                </div>
                
                <article id="post-<?php the_ID(); ?>" <?php post_class(['blog-details__fullBody']); ?>>
                  <?php sassly_get_template('content-single.php',[], SASSLY_ESSENTIAL_DIR_PATH , SASSLY_ESSENTIAL_DIR_PATH . '/templates/single/'); ?>
                </article>
                <?php if($general_social_share){ ?>
                  <div class="social-share__wrapper mt-40">
                    <h2 class="social-share__title">
                      <i class="fa-solid fa-share-nodes"></i> <?php echo esc_html__('Shares','sassly-essential') ?>
                    </h2>
                    <ul class="social-share__media">
                    <?php foreach($general_social_share as $share){ ?>
                        <li><a href="<?php echo esc_url(get_the_permalink()); ?>" data-social="<?php echo esc_attr($share['social_type']); ?>"><i class="<?php echo esc_attr($share['bookmark_icon']); ?>"></i></a> </li>
                      <?php } ?>
                    </ul>
                  </div>
                <?php } ?>
                <?php
                  get_template_part( 'template-parts/blog/blog-parts/part', 'author' );
                  sassly_post_nav();
                  comments_template();
                ?>
              </div>
            </div>
          </div>
          <!-- Blog Details end -->
<?php get_footer(); ?>