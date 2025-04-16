<?php
/**
 * the template for displaying all posts.
 */

  get_header(); 
  
  $option = sassly_option('blog_post_preset_grp');   
  
  if(isset($option['preset_blog_banner']) && $option['preset_blog_banner']){
    get_template_part( 'template-parts/banner/content', 'banner-blog' );
  }
  
  $preset_blog_view = isset($option['preset_blog_view']) ? $option['preset_blog_view'] : 1;
  
  sassly_get_template('health-top-header.php',[], SASSLY_ESSENTIAL_DIR_PATH , SASSLY_ESSENTIAL_DIR_PATH . '/templates/single/template-parts/');
  
	$blog_sidebar = isset($option['preset_blog_sidebar']) ? $option['preset_blog_sidebar'] : 1;
	$sidebar_active   = is_active_sidebar('sidebar-1') && $blog_sidebar ? 'col-3' : '';
	$has_social_share             = sassly_social_share();
	$general_social_share         = sassly_social_share(); 
  
	if(($general_social_share || $preset_blog_view) && $sidebar_active){
	  $row_cls = ['meta'=>'col-2','content'=>'col-7','sidebar'=>'col-3']; 
	}elseif(!($general_social_share || $preset_blog_view) && $sidebar_active){
    $row_cls = ['meta'=>'','content'=>'col-9','sidebar'=>'col-3'];
	}elseif(($general_social_share || $preset_blog_view) && !$sidebar_active){
    $row_cls = ['meta'=>'col-2','content'=>'col-10','sidebar'=>''];
	}elseif(!($general_social_share || $preset_blog_view) && !$sidebar_active){
    $row_cls = ['meta'=>'','content'=>'col-12','sidebar'=>''];
	}
	
?>
<style>
  .health__details {
      background-color: #F3F0E4;
  }
 
  .dancer__blogdetails-overview {
      display: -webkit-box;
      display: -ms-flexbox;
      display: flex;
      gap: 20px;
      -webkit-box-orient: vertical;
      -webkit-box-direction: normal;
      -ms-flex-direction: column;
      flex-direction: column;
      max-width: 80px;
      -webkit-border-end: 1px solid #1C1D20;
      border-inline-end: 1px solid #1C1D20;
  }
  .dancer__blogdetails-overview li:first-child {
      margin-bottom: 30px;
  }
  
  .dancer__blogdetails-overview li {
      text-align: center;
      position: relative;
      line-height: 1.1;
  }
  
  .dancer__blogdetails-overview li:first-child::before {
      content: "";
      position: absolute;
      inset-inline-end: 0;
      bottom: -25px;
      width: 80px;
      height: 1px;
      background: #1C1D20;
  }
  .dancer__blogdetails-overview.dark-overview li i {
      color: #1C1D20;
  }
  .dancer__blogdetails-overview.dark-overview li i {
      color: #1C1D20;
  }
  .dancer__blogdetails-overview li i {
      display: block;
      padding-bottom: 3px;
  }
  .light .dancer__blogdetails-overview.dark-overview li span {
      color: #1C1D20;
  }
  
  .dancer__blogdetails-overview.dark-overview li span {
      color: #1C1D20;
  }
  .dancer__blogdetails-overview li span {
      font-weight: 400;
      font-size: 12px;
      line-height: 1;
      color: #1C1D20;
  }
   .default-details-tags li{
      background: #EAE5D3;
      color: #1C1D20;
  }
</style>
  <main>
   <!-- blog details start  -->
   <section class="health__details pt-50 default-blog__area">
          <div class="container">
            <div class="dancer__blogdetails-body">
              <div class="doctor-blogdetails__wrapper row">
                <?php if($general_social_share || $preset_blog_view){ ?>
                <div class="dancer__blogdetails-contentleft <?php echo esc_attr($row_cls['meta']); ?>">                 
                         
                      <ul class="dancer__blogdetails-overview">
                        <?php if($preset_blog_view){ ?>
                        <li>
                          <i class="fa-solid fa-chart-simple"></i>
                          <span><?php echo get_post_meta(get_the_id(),'sassly_post_views_count',true) ?><br>
                            <?php echo esc_html__('Views','sassly-essential'); ?></span>
                        </li>
                        <?php } ?>
                        <?php if($general_social_share){ ?>
                          <li>
                            <i class="fa-solid fa-share-nodes"></i>
                            <span>
                            <!-- 14 <br> -->
                            <?php echo esc_html__('Shares','sassly-essential') ?></span>
                          </li>
                          <?php foreach($general_social_share as $share){ ?>
                            <li><a href="<?php echo esc_url(get_the_permalink()); ?>" data-social="<?php echo esc_attr($share['social_type']); ?>"><i class="<?php echo esc_attr($share['bookmark_icon']); ?>"></i></a> </li>
                          <?php } ?>
                        <?php } ?>
                      </ul>                        
                     <?php } ?>
                </div>
                
                <div class="dancer__blogdetails-contentright <?php echo esc_attr($row_cls['content']); ?>">                
                    <?php while ( have_posts() ) : the_post(); ?>
                          <div class="default-blog__details-content">
                            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                              <?php sassly_get_template('content-single.php',[], SASSLY_ESSENTIAL_DIR_PATH , SASSLY_ESSENTIAL_DIR_PATH . '/templates/single/'); ?>
                            </article>
                            <?php
                              get_template_part( 'template-parts/blog/blog-parts/part', 'author' );
                              sassly_post_nav();
                              comments_template();
                            ?>
                          </div>
                    <?php endwhile; ?>
                  
                </div>
                <?php if($sidebar_active){ ?>
                <div class="health__details-sidebar <?php echo esc_attr($row_cls['sidebar']); ?>">                  
                   <?php get_sidebar(); ?>
                </div>
                <?php } ?>
              </div>
            </div>
          </div>
        </section>
        <!-- blog details end  -->  
  </main> <!--#main-content -->
<?php get_footer(); ?>