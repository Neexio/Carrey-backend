<?php if (!defined('ABSPATH')) die('Direct access forbidden.');
/**
 * recent post widget
 */
class Sassly_Recent_Post extends WP_Widget {

	function __construct() {

		$widget_opt = array(
			'classname'		 => 'joya-widget blog-box blog-post',
			'description'	 => esc_html__('Info Recent post with thumbnail','sassly-essential')
		);

		parent::__construct( 'joya-recent-post', esc_html__( 'Sassly recent post', 'sassly-essential' ), $widget_opt );
	}

	function widget( $args, $instance ) {

		global $wp_query;

		echo $args[ 'before_widget' ];

		if ( !empty( $instance[ 'title' ] ) ) {

			echo $args[ 'before_title' ] . apply_filters( 'widget_title', $instance[ 'title' ] ) . $args[ 'after_title' ];
		}

		if ( !empty( $instance[ 'number_of_posts' ] ) ) {
			$no_of_post = $instance[ 'number_of_posts' ];
		} else {
			$no_of_post = 3;
		}

		if ( !empty( $instance[ 'image_show' ] ) ) {
			$image_show = $instance[ 'image_show' ];
		} else {
			$image_show = '';
		}

		if ( !empty( $instance[ 'posts_title_limit' ] ) ) {
			$posts_title_limit = $instance[ 'posts_title_limit' ];
		} else {
			$posts_title_limit = 8;
		}
		
		if ( !empty( $instance[ 'posts_excerp_limit' ] ) ) {
			$posts_excerp_limit = $instance[ 'posts_excerp_limit' ];
		} else {
			$posts_excerp_limit = 18;
        }
      
        if ( !empty( $instance[ 'sassly_post_type' ] ) ) {
			$sassly_post_type = $instance[ 'sassly_post_type' ];
		} else {
			$sassly_post_type = 'recent';
		} 
		
		if ( !empty( $instance[ 'posts_date_format' ] ) ) {
			$posts_date_format = $instance[ 'posts_date_format' ];
		} else {
			$posts_date_format = get_option( 'date_format' );
		}

		$query = array(
			'post_type'		 => array( 'post' ),
			'post_status'	 => array( 'publish' ),
			'orderby'		 => 'date',
			'order'			 => 'DESC',
			'posts_per_page' => $no_of_post
		); 

		if($sassly_post_type=="populer"){
			$query['orderby'] = "comment_count"; 
		}
 
        $loop = new WP_Query( $query );
      
		?>
		 
		 <?php
				if ( $loop->have_posts() ):
						while ( $loop->have_posts() ):
							$loop->the_post();
							?>	
								<div class="default-sidebar__recent-item">
									<?php
									    $thumbnail = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'medium' );
										if($image_show !='' && isset($thumbnail[0])): 
											$img = $thumbnail[ 0 ];
										    echo '<a class="d-block mb-10" href="'.get_the_permalink().'"><img src="' . esc_url( $img ) . '" alt="' . esc_attr__('thumb','sassly-essential') . '"></a>';
										endif;
										
									?>
									<i><?php echo get_the_date($posts_date_format); ?></i>
									<a href="<?php echo get_the_permalink(); ?>" ><?php echo wpautop( wp_trim_words(get_the_title(),$posts_title_limit,' ') ); ?></a>
								</div>

						<?php endwhile; ?>
    			<?php endif; ?> 
			<?php
			wp_reset_postdata();
			echo $args[ 'after_widget' ];
	}

	function update( $new_instance, $old_instance ) {

		$old_instance[ 'title' ]              = strip_tags( $new_instance[ 'title' ] );
		$old_instance[ 'number_of_posts' ]    = $new_instance[ 'number_of_posts' ];
		$old_instance[ 'sassly_post_type' ] = $new_instance[ 'sassly_post_type' ];
		$old_instance[ 'posts_title_limit' ]  = $new_instance[ 'posts_title_limit' ];
		$old_instance[ 'posts_excerp_limit' ] = $new_instance[ 'posts_excerp_limit' ];
		$old_instance[ 'posts_date_format' ] = $new_instance[ 'posts_date_format' ];
		$old_instance[ 'image_show' ]         = $new_instance[ 'image_show' ];

		return $old_instance;
	}

	function form( $instance ) {

     
        if ( isset( $instance[ 'sassly_post_type' ] ) ) {
             $sassly_post_type = $instance['sassly_post_type'];  
        }else{
             $sassly_post_type = 'recent';  
        } 
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		} else {
			$title = esc_html__( 'Recent posts', 'sassly-essential' );
		}
		if ( isset( $instance[ 'number_of_posts' ] ) ) {
			$no_of_post = $instance[ 'number_of_posts' ];
		} else {
			$no_of_post = 3;
		}
		if ( isset( $instance[ 'posts_title_limit' ] ) ) {
			$posts_title_limit = $instance[ 'posts_title_limit' ];
		} else {
			$posts_title_limit = 8;
		}
		if ( isset( $instance[ 'posts_excerp_limit' ] ) ) {
			$posts_excerp_limit = $instance[ 'posts_excerp_limit' ];
		} else {
			$posts_excerp_limit = 20;
		}
		if ( isset( $instance[ 'posts_date_format' ] ) ) {
			$posts_date_format = $instance[ 'posts_date_format' ];
		} else {
			$posts_date_format = get_option( 'date_format' );
		}

		if ( isset( $instance[ 'image_show' ] ) ) {
			$image_show = $instance[ 'image_show' ];
		} else {
			$image_show = '';
		}
         
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'sassly-essential' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
        <p>
		    <select class='widefat' id="<?php echo $this->get_field_id('sassly_post_type'); ?>"
					name="<?php echo $this->get_field_name('sassly_post_type'); ?>" type="text">
					<option
						value='recent' 
						<?php echo ($sassly_post_type=='recent')?'selected':''; ?>>
						<?php echo esc_html__('Recent post','sassly-essential'); ?>
					</option>
					<option 
						value='populer'
						<?php echo ($sassly_post_type=='populer')?'selected':''; ?>>
						<?php echo esc_html__('Populer post','sassly-essential'); ?>
					</option> 
			</select>                
        </p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'number_of_posts' ) ); ?>"><?php esc_html_e( 'Number of posts:', 'sassly-essential' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'number_of_posts' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'number_of_posts' ) ); ?>" type="text" value="<?php echo esc_attr( $no_of_post ); ?>" />
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'posts_title_limit' ) ); ?>"><?php esc_html_e( 'Title limit:', 'sassly-essential' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'posts_title_limit' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'posts_title_limit' ) ); ?>" type="text" value="<?php echo esc_attr( $posts_title_limit ); ?>" />
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'posts_excerp_limit' ) ); ?>"><?php esc_html_e( 'Content limit:', 'sassly-essential' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'posts_excerp_limit' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'posts_excerp_limit' ) ); ?>" type="text" value="<?php echo esc_attr( $posts_excerp_limit ); ?>" />
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'posts_date_format' ) ); ?>"><?php esc_html_e( 'Date Format:', 'sassly-essential' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'posts_date_format' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'posts_date_format' ) ); ?>" type="text" value="<?php echo esc_attr( $posts_date_format ); ?>" />
		</p>
		<p>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'image_show' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'image_show' ) ); ?>" type="checkbox" value="1" <?php checked( $image_show, 1 ); ?>>
            <label for="<?php echo esc_attr( $this->get_field_id( 'image_show' ) ); ?>"><?php _e( esc_attr( 'Image show' ) ); ?> </label> 
		</p>
		
		<?php
	}

}

