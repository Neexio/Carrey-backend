

<?php
	// If the current post is protected by a password and the visitor has not yet 
	// entered the password we will return early without loading the comments.
	// ----------------------------------------------------------------------------------------
	if ( post_password_required() ) {
		return;
	}
?>

<?php if ( have_comments() || comments_open()) : ?>
	<div id="comments" class="joya--comment joya--blog-post-comment font-heading-prata">
		<?php if ( have_comments()) : ?>

			<h3 class="comment-num mb-50">
				<?php

					if(get_comments_number() < 1){
						printf( '%1$s ' . esc_html__( 'Comment', 'sassly' ), get_comments_number() );
					}else{
						printf( '%1$s ' . esc_html__( 'Comments', 'sassly' ), get_comments_number() );
					}
				
				?>
			</h3>

			<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : ?>
				<nav id="comment-nav-above" class="navigation comment-navigation" role="navigation">

					<h1 class="screen-reader-text">
						<?php esc_html_e( 'Comment navigation', 'sassly' ); ?>
					</h1>
					<div class="nav-previous">
						<?php previous_comments_link( esc_html__( '&larr; Older Comments', 'sassly' ) ); ?>
					</div>
					<div class="nav-next">
						<?php next_comments_link( esc_html__( 'Newer Comments &rarr;', 'sassly' ) ); ?>
					</div>
				
				</nav><!-- #comment-nav-above -->
			<?php endif; //check for comment navigation ?>

			<ul class="joya--comments-list comments-list ">
				<?php
						wp_list_comments( array(
							'reply_text'        => sprintf('<svg xmlns="http://www.w3.org/2000/svg" height="16" width="16" viewBox="0 0 512 512"><!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2023 Fonticons, Inc.--><path d="M205 34.8c11.5 5.1 19 16.6 19 29.2v64H336c97.2 0 176 78.8 176 176c0 113.3-81.5 163.9-100.2 174.1c-2.5 1.4-5.3 1.9-8.1 1.9c-10.9 0-19.7-8.9-19.7-19.7c0-7.5 4.3-14.4 9.8-19.5c9.4-8.8 22.2-26.4 22.2-56.7c0-53-43-96-96-96H224v64c0 12.6-7.4 24.1-19 29.2s-25 3-34.4-5.4l-160-144C3.9 225.7 0 217.1 0 208s3.9-17.7 10.6-23.8l160-144c9.4-8.5 22.9-10.6 34.4-5.4z"/></svg> %s',esc_html__('Reply','sassly')),
								'callback'          => 'sassly_comment_style',
								'style'			 => 'ul',
								'short_ping'	 => false,
								'type'              => 'all',
								'format'            => current_theme_supports( 'html5', 'comment-list' ) ? 'html5' : 'xhtml',
								'avatar_size'	 => 60,
						) );
				?>
			</ul><!-- .comment-list -->

			<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : ?>
						<nav id="comment-nav-bellow" class="navigation comment-navigation" role="navigation">
						
							<h1 class="screen-reader-text">
								<?php esc_html_e( 'Comment navigation', 'sassly' ); ?>
							</h1>

							<div class="nav-previous">
								<?php previous_comments_link( esc_html__( '&larr; Older Comments', 'sassly' ) ); ?>
							</div>
							
							<div class="nav-next">
								<?php next_comments_link( esc_html__( 'Newer Comments &rarr;', 'sassly' ) ); ?>
							</div>
						</nav><!-- #comment-nav-bellow -->
			<?php endif; //check for comment navigation ?>

			<?php if ( !comments_open() ) : ?>
				<p class="no-comments"><?php esc_html_e( 'Comments are closed.', 'sassly' ); ?></p>
			<?php endif; ?>

		<?php endif; // comments have end ?>

		<?php

			$post_id = '';
			if ( null === $post_id )
				$post_id = get_the_ID();
			else
				$id		 = $post_id;

			$commenter		 = wp_get_current_commenter();
			$user			 = wp_get_current_user();
			$user_identity	 = $user->exists() ? $user->display_name : '';
	
			$req		 = get_option( 'require_name_email' );
			$aria_req	 = ( $req ? " aria-required='true'" : '' );

			$fields = array(
				'author' => '<div class="row"><div class="col-lg-6"><div class="elc-inbd-comment__field mb-30"><label for="author">' . esc_html__( 'Name*' ,'sassly' ) . '</label> <input placeholder="'.  esc_attr__('Enter Name', 'sassly').'" id="author" class="form-input" name="author" type="text" value="' . esc_attr( $commenter[ 'comment_author' ] ) . '" size="30"' . $aria_req . ' /></div></div>',
				'email'	 => '<div class="col-lg-6"><div class="elc-inbd-comment__field mb-30"><label for="email">' . esc_html__( 'Email*' ,'sassly' ) . '</label><input placeholder="'.  esc_attr__('Enter Email', 'sassly').'" id="email" name="email" class="form-input" type="email" value="' . esc_attr( $commenter[ 'comment_author_email' ] ) . '" size="30"' . $aria_req . ' /></div></div> </div>',
				//'url'	 => '<div class="col-md-12"><input placeholder="'.  esc_attr__('Enter Website', 'sassly').'" id="url" name="url" class="form-control" type="url" value="' . esc_attr( $commenter[ 'comment_author_url' ] ) . '" size="30" /></div></div>',
			);

			if ( is_user_logged_in() ) {
				$cl = 'loginformuser';
			} else {
				$cl = '';
			}
			$sassly_option = sassly_option('opt-tabbed-general');
			$button_style = isset($sassly_option['gl_button_style']) ? $sassly_option['gl_button_style']: 'btn-hover-divide';
			$defaults = [
				'fields'			 => $fields,
				'comment_field'		 => '
							<div class="elc-inbd-comment__field order-4">
	                              <label for="name">'. esc_html__( 'Comment', 'sassly' ) .'*</label>
	                              <textarea
	                              id="comment" 
								  name="comment"
								  aria-required="true"
								  placeholder="'.  esc_attr__('Write your comments......', 'sassly').'" 
								  ></textarea>
	                        </div>
				',
				/** This filter is documented in wp-includes/link-template.php */
				'must_log_in'		 => '
					<p class="must-log-in">
					'.esc_html__('You must be','sassly').' <a href="'.esc_url(wp_login_url( apply_filters( 'the_permalink', get_permalink( $post_id ) ) )).'">'.esc_html__('logged in','sassly').'</a> '.esc_html__('to post a comment.','sassly').'
					</p>',
				/** This filter is documented in wp-includes/link-template.php */
				'logged_in_as'		 => '
					<p class="logged-in-as">
					'.esc_html__('Logged in as','sassly').' <a href="'.esc_url(get_edit_user_link()).'">'.esc_html($user_identity).'</a>. <a href="'.esc_url(wp_logout_url( apply_filters( 'the_permalink', get_permalink( $post_id ) ) )).'" title="'.esc_attr__('Log out of this account','sassly').'">'.esc_html__('Log out?','sassly').'</a>
					</p>',
				'id_form'			 => 'commentform',
				'id_submit'			 => 'submit',
				'class_form'         => 'd-flex flex-column comment-form',
				'class_submit'		 => sprintf("wcf--theme-btn wc-btn-primary %s",esc_attr( $button_style )),
				'title_reply_before' => '<h3 id="reply-title" class="elc-inbd-comment__title mb-10">',
				'title_reply'		 => esc_html__( 'Leave a Reply', 'sassly' ),
				'title_reply_to'	 => esc_html__( 'Leave a Reply to %s', 'sassly' ),
				'cancel_reply_link'	 => esc_html__( 'Cancel Reply', 'sassly' ),
				'label_submit'		 => esc_html__( 'Submit Now', 'sassly' ),
				'submit_field' =>  '<div class="cf_btn default-details__cmtbtn mt-45 mb-45 order-5">%1$s %2$s</div>',
				'submit_button' => '<button name="%1$s" type="submit" id="%2$s" class="%3$s" > %4$s <i class="icon-wcf-checvron-right"></i></button>',
				'format'			 => 'xhtml',
			];

			comment_form( $defaults );
		?>

	</div><!-- #comments -->
<?php endif;