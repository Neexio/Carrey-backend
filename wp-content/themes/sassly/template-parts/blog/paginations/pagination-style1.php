<?php 

if( !sassly_option('blog_post_nav', true)){
    return;
}

global $wp_query;

// stop execution if there's only 1 page
if ( $wp_query->max_num_pages <= 1 ){
    return;
}

$paged	 = get_query_var( 'paged' ) ? absint( get_query_var( 'paged' ) ) : 1;
$max	 = intval( $wp_query->max_num_pages );

// add current page to the array
if ( $paged >= 1 ){
    $links[] = $paged;
}

// add the pages around the current page to the array
if ( $paged >= 3 ) {
    $links[] = $paged - 1;
    $links[] = $paged - 2;
}

if ( ( $paged + 2 ) <= $max ) {
    $links[] = $paged + 2;
    $links[] = $paged + 1;
}
$alignment = sassly_option("blog_post_nav_alignment");
echo sprintf('<div class="pagination-wrapper"><ul class="pagination-circle %s">',esc_attr($alignment)) . "\n";

// previous Post Link
if ( get_previous_posts_link() ){

    $blog_prev_icon = sassly_option('blog_prev_icon');
    $prev_img_url = SASSLY_IMG.'/default-blog/arrow-left.png';
    if(is_array($blog_prev_icon) && isset($blog_prev_icon['url']) && $blog_prev_icon['url'] !=''){
        $prev_img_url = $blog_prev_icon['url'];
    }
    
    $prev_img = sprintf('<img src="%s" alt="%s">',esc_url($prev_img_url),esc_html__('prev','sassly'));
    printf( '<li class="prev">%s</li>' . "\n", get_previous_posts_link( $prev_img ) );
}

// link to first page, plus ellipses if necessary
if ( !in_array( 1, $links ) ) {
    $class = 1 == $paged ? 'active' : '';

    printf( '<li><a class="%s" href="%s">%s</a></li>' . "\n", $class, esc_url( get_pagenum_link( 1 ) ), '1' );

    if ( !in_array( 2, $links ) )
        echo '<li class="pagination-dots">…</li>';
}

// link to current page, plus 2 pages in either direction if necessary
sort( $links );
foreach ( (array) $links as $link ) {
    $class = $paged == $link ? 'active' : '';
    printf( '<li><a class="%s" href="%s">%s</a></li>' . "\n", $class, esc_url( get_pagenum_link( $link ) ), str_pad($link, 2, "0", STR_PAD_LEFT) );
}

// link to last page, plus ellipses if necessary
if ( !in_array( $max, $links ) ) {
    if ( !in_array( $max - 1, $links ) )
        echo '<li class="pagination-dots">…</li>' . "\n";

    $class = $paged == $max ? 'active' : '';
    printf( '<li ><a class="%s" href="%s" >%s</a></li>' . "\n", $class, esc_url( get_pagenum_link( $max ) ), str_pad($max, 2, "0", STR_PAD_LEFT) );
}

// next Post Link
if ( get_next_posts_link() ){

    $blog_next_icon = sassly_option('blog_next_icon');
    $next_img_url = SASSLY_IMG.'/default-blog/arrow-right.png';
    if(is_array($blog_next_icon) && isset($blog_next_icon['url']) && $blog_next_icon['url'] !=''){
        $next_img_url = $blog_next_icon['url'];
    }
    
    $next_img = sprintf('<img src="%s" alt="%s">',esc_url($next_img_url),esc_html__('next','sassly'));
    printf( '<li class="next">%s</li>' . "\n", get_next_posts_link( $next_img ) );
}

echo '</ul> </div>' . "\n";


