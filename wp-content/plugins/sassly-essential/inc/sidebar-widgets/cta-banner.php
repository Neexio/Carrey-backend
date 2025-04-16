<?php

class Sassly_Banner_Widget extends WP_Widget {

	public function __construct() {
		$widget_opt = array(
			'classname'		 => 'joya-widget cta-ads-banner',
			'description'	 => esc_html__('CTA Banner','sassly-essential')
		);

		parent::__construct( 'joya-cta-banner', esc_html__( 'Sassly Cta Banner', 'sassly-essential' ), $widget_opt );
	}

	public function widget( $args, $instance ) {
        echo $args[ 'before_widget' ];           
       
        if ( !empty( $instance[ 'title' ] ) ) {
			echo $args[ 'before_title' ] . apply_filters( 'widget_title', $instance[ 'title' ] ) . $args[ 'after_title' ];
		}
		
		if ( !empty( $instance[ 'top_title' ] ) ) {
			$top_title = $instance[ 'top_title' ];
		} else {
			$top_title = '';
		}

		if ( !empty( $instance[ 'query_text' ] ) ) {
			$query_text = $instance[ 'query_text' ];
		} else {
			$query_text = '';
		}

		if ( !empty( $instance[ 'button_text' ] ) ) {
			$button_text = $instance[ 'button_text' ];
		} else {
			$button_text = '';
		}
		
		if ( !empty( $instance[ 'button_link' ] ) ) {
			$button_link = $instance[ 'button_link' ];
		} else {
			$button_link = '#';
        }
		
		?>
		<div class="default-sidebar__widget baner">
          <div class="default-sidebar__content">
            <?php if($top_title !=''){ ?>
                <p class="subtitle"><?php echo wp_kses_post($top_title); ?></p>
            <?php } ?>
            <?php if($query_text !=''){ ?>
                <h2 class="title"><?php echo wp_kses_post($query_text); ?></h2>
            <?php } ?>
            <?php if($button_text !=''){ ?>
                <a href="<?php echo esc_url($button_link); ?>" class="wc-btn-primary btn-hover-cross"><?php echo wp_kses_post($button_text); ?></a>
            <?php } ?>
          </div>
        </div>
		<?php
		
		echo $args[ 'after_widget' ];
	}

	public function form( $instance ) {
	
		if ( isset( $instance[ 'top_title' ] ) ) {
            $top_title = $instance['top_title'];  
       }else{
            $top_title = 'Contact for inquery';  
       } 
       if ( isset( $instance[ 'title' ] ) ) {
           $title = $instance[ 'title' ];
       } else {
           $title = '';
       }
       if ( isset( $instance[ 'query_text' ] ) ) {
           $query_text = $instance[ 'query_text' ];
       } else {
           $query_text = 'Want to know the more details?';
       }
       if ( isset( $instance[ 'button_text' ] ) ) {
           $button_text = $instance[ 'button_text' ];
       } else {
           $button_text = 'Call me';
       }
       if ( isset( $instance[ 'button_link' ] ) ) {
           $button_link = $instance[ 'button_link' ];
       } else {
           $button_link = '#';
       }
       
       ?>
       
        <p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'sassly-essential' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
        <p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'top_title' ) ); ?>"><?php esc_html_e( 'SubTitle:', 'sassly-essential' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'top_title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'top_title' ) ); ?>" type="text" value="<?php echo esc_attr( $top_title ); ?>" />
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'query_text' ) ); ?>"><?php esc_html_e( 'Query Text:', 'sassly-essential' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'query_text' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'query_text' ) ); ?>" type="text" value="<?php echo esc_attr( $query_text ); ?>" />
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'button_text' ) ); ?>"><?php esc_html_e( 'Button Label:', 'sassly-essential' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'button_text' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'button_text' ) ); ?>" type="text" value="<?php echo esc_attr( $button_text ); ?>" />
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'button_link' ) ); ?>"><?php esc_html_e( 'Button Link:', 'sassly-essential' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'button_link' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'button_link' ) ); ?>" type="text" value="<?php echo esc_attr( $button_link ); ?>" />
		</p>
		
		<?php
	}

	public function update( $new_instance, $old_instance ) {
		$old_instance[ 'title' ]        = strip_tags( $new_instance[ 'title' ] );
		$old_instance[ 'top_title' ]    = $new_instance[ 'top_title' ];
		$old_instance[ 'query_text' ]   = $new_instance[ 'query_text' ];
		$old_instance[ 'button_text' ]  = $new_instance[ 'button_text' ];
		$old_instance[ 'button_link' ]  = $new_instance[ 'button_link' ];
		
		return $old_instance;
	}
}