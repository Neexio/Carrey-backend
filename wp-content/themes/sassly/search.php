
	<?php
	/**
	 * The main template file
	 */
	get_header(); 
	get_template_part( 'template-parts/banner/content', 'banner-search' ); 
	
	$search_found_title     = sassly_option('search_found_title',esc_html__('Search Results for:','sassly'));
	$search_sidebar         = sassly_option('enable_search_sidebar',0);
	$blog_sidebar           = 'no-sidebar';
    $sidebar_cls            = ['no-sidebar'];
	if($search_sidebar){
		$blog_sidebar           = sassly_option('blog_sidebar','left-sidebar');
		$blog_sidebar           = $blog_sidebar == ''? 'left-sidebar' : $blog_sidebar;
		$sidebar_cls[$blog_sidebar]   = is_active_sidebar('sidebar-1') ? $blog_sidebar : 'no-sidebar';
	}
	
	?>
	<main id="content-area">
		<div class="default-blog__area pt-150 pb-150">
            <div class="container">
				<div class="default-blog__grid <?php echo esc_attr( implode(' ', $sidebar_cls) ); ?>">
				<?php
					if($blog_sidebar == 'left-sidebar'){
						get_sidebar();
					} 
				?>
		
				<div class="default-blog__item-content">
					<?php if ( have_posts() ) : ?>
				            <div class="default-search-title-wrapper pt-20 pb-50 text-center">
		                      <h2 class="default-search-title"><?php printf(esc_html__('%s %s', 'sassly'), esc_html($search_found_title) , get_search_query()); ?></h2>
		                    </div>
		                	<?php while ( have_posts() ) : the_post(); ?>
								<?php get_template_part( 'template-parts/blog/content' ); ?>
							<?php endwhile; ?>
							<div class="blog__pagination pt-120">
			                  <?php get_template_part( 'template-parts/blog/paginations/pagination', 'style1' ); ?>
			                </div>
					<?php else : ?>
						<?php get_template_part( 'template-parts/blog/content', 'none' ); ?>
					<?php endif; ?>
				</div><!-- search__content -->
				
				<?php 
					if($blog_sidebar == 'right-sidebar'){
						get_sidebar();
					}
				?>
		        </div>
            </div>
        </div>
	</main><!-- #main-content -->
<?php get_footer(); ?>