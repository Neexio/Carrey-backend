<style>
    .health-blog__top-3 {
        position: relative;
    }
    .health-blog__top-3 .content {
        position: absolute;
        max-width: 1120px;
        bottom: 100px;
        z-index: 2;
    }
    .health-blog__top-3 .cat {
        color: #F3F0E4;
        font-size: 12px;
        font-weight: 500;
        line-height: 12px;
        border-radius: 6px;
        padding: 5px 10px;
        margin-bottom: 10px;
        display: inline-block;
        text-transform: capitalize;
        background: rgba(243, 240, 228, 0.25);
    }
    .health-blog__top-3 .title {
        color: #F3F0E4;
        font-family: Overlock;
        font-size: 80px;
        font-style: normal;
        font-weight: 400;
        line-height: 1;
    }
    .health-blog__top-3 p {
        color: #F3F0E4;
        font-size: 12px;
        font-weight: 400;
        padding-top: 20px;
    }
    .health-blog__top-3 p span {
        position: relative;
        display: inline-block;
        width: 30px;
    }
    .health-blog__top-3 p span::after {
        content: "";
        position: absolute;
        left: 5px;
        top: -3px;
        background: #fff;
        height: 1px;
        width: 20px;
    }
</style>

<?php

  $blog_cat_show    = sassly_option('blog_category','yes');
  $cat              = get_the_category();
  $blog_cat_single  = sassly_option('blog_category_single','no');
  $get_author       = get_the_author();
  
  if( $blog_cat_single == 'yes' ) {            
    shuffle($cat);
  }
  
?>

<?php if(has_post_thumbnail()){ ?>
    <section class="health-blog__top-3">
      <div class="cf_image">       
        <?php the_post_thumbnail('full', ['class' => 'image responsive--full']); ?>
      </div>
      <div class="container">
        <div class="row">
          <div class="col-xxl-12">
            <div class="content">
              <?php if( $blog_cat_show && isset($cat[0])){ ?>
              <a href="<?php echo esc_url(get_category_link($cat[0]->term_id) ); ?>" class="cat"><?php echo esc_html(get_cat_name($cat[0]->term_id)); ?></a>
              <?php } ?>
              <h1 class="title"><?php echo get_the_title(); ?></h1>
              <p><?php
                if(sassly_option('blog_author',0)){
                echo esc_html__('by','sassly-essential') .' '. $get_author; 
                }
              ?><span></span><?php echo get_the_date(get_option( 'date_format' )); ?>
               <?php if( sassly_option('blog_comment',0) ){ ?>
                <span></span>
                <?php $comments_number = get_comments_number();
                  echo $comments_number > 1 ? sprintf(esc_html__('%s comments','sassly-essential'),$comments_number) : sprintf(esc_html__('%s comment','sassly-essential'),$comments_number);
                } ?>
             
              </p>
            </div>
          </div>
        </div>
      </div>
    </section>
<?php } ?>