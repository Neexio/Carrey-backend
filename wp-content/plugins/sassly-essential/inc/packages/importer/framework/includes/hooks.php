<?php if ( ! defined( 'FW' ) ) {
	die( 'Forbidden' );
}

// FW_Form hooks
{
	if ( is_admin() ) {
		/**
		 * Display form errors in admin side
		 * @internal
		 */
		function _action_fw_form_show_errors_in_admin() {
			$form = FW_Form::get_submitted();

			if ( ! $form || $form->is_valid() ) {
				return;
			}

			foreach ( $form->get_errors() as $input_name => $error_message ) {
				FW_Flash_Messages::add( 'fw-form-admin-' . $input_name, $error_message, 'error' );
			}
		}

		add_action( 'wp_loaded', '_action_fw_form_show_errors_in_admin', 111 );
	} else {
		
	}
}

// FW_Flash_Messages hooks
{
	if ( is_admin() ) {
		/**
		 * Start the session before the content is sent to prevent the "headers already sent" warning
		 * @internal
		 */
		function _action_fw_flash_message_backend_prepare() {
			if ( apply_filters( 'fw_use_sessions', true ) && ! session_id()  ) {
				$active  = function_exists('sassly_option') ? sassly_option('theme_demo_activate', true) : false;
				if($active){
				session_start();
				}
			}
		}

		add_action( 'current_screen', '_action_fw_flash_message_backend_prepare', 9999 );

		/**
		 * Display flash messages in backend as notices
		 */
		add_action( 'admin_notices', array( 'FW_Flash_Messages', '_print_backend' ) );
	} else {
		/**
		 * Start the session before the content is sent to prevent the "headers already sent" warning
		 * @internal
		 */
		function _action_fw_flash_message_frontend_prepare() {
			if (
			    apply_filters( 'fw_use_sessions', true )
                &&			
				! ( defined( 'DOING_AJAX' ) && DOING_AJAX )
				&&
				! session_id()
			) {
				$active  = function_exists('sassly_option') ? sassly_option('theme_demo_activate', true) : false;
				if($active){
				session_start();
				}
			}
		}

		add_action( 'send_headers', '_action_fw_flash_message_frontend_prepare', 9999 );

	}
}


{
	if ( ! function_exists( 'fw_delete_resized_thumbnails' ) ) {
		function fw_delete_resized_thumbnails( $id ) {
			$images = wp_get_attachment_metadata( $id );
			if ( ! empty( $images['resizes'] ) ) {
				$uploads_dir = wp_upload_dir();
				foreach ( $images['resizes'] as $image ) {
					$file = $uploads_dir['basedir'] . '/' . $image;
					@unlink( $file );
				}
			}
		}

		add_action( 'delete_attachment', 'fw_delete_resized_thumbnails' );
	}
}

//WPML Hooks
{
	if ( is_admin() ) {
		add_action( 'icl_save_term_translation', '_fw_action_wpml_duplicate_term_options', 20, 2 );
		function _fw_action_wpml_duplicate_term_options( $original, $translated ) {
			$original_options = fw_get_db_term_option(
				fw_akg( 'term_id', $original ),
				fw_akg( 'taxonomy', $original )
			);

			if ( $original_options !== null ) {
				fw_set_db_term_option(
					fw_akg( 'term_id', $translated ),
					fw_akg( 'taxonomy', $original ),
					null,
					$original_options
				);
			}
		}
	}
}