
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
  <?php sassly_post_thumbnail() ?> 
    <?php if( !sassly_option('page_banner_show',0)): ?>
    <div class="default-details__content">
        <h2 class="blog__details_title pt-50"><?php the_title(); ?></h2>
    </div>  
    <?php endif; ?>
	<?php
		if ( is_search() ) {
			the_excerpt();
			} else {
			the_content( esc_html__( 'Continue reading &rarr;', 'sassly' ) );
			sassly_link_pages();
		}
	?>
 </article>
