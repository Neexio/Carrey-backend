<?php if ( ! defined( 'FW' ) ) {
	die( 'Forbidden' );
}

/**
 * Backend functionality
 */
final class _FW_Component_Backend {

	/** @var FW_Settings_Form */
	private $settings_form;

	private $available_render_designs = array(
		
	);

	private $default_render_design = 'default';
	
	private $markdown_parser = null;

	/**
	 * Contains all option types
	 * @var FW_Option_Type[]
	 */
	private $option_types = array();

	/**
	 * @var FW_Option_Type_Undefined
	 */
	private $undefined_option_type;
	private $container_types_pending_registration = array();

	/**
	 * Contains all container types
	 * @var FW_Container_Type[]
	 */
	private $container_types = array();

	/**
	 * @var FW_Container_Type_Undefined
	 */
	private $undefined_container_type;

	private $static_registered = false;

	/**
	 * @var FW_Access_Key
	 */
	private $access_key;

	/**
	 * @internal
	 */
	public function _get_settings_page_slug() {
		return apply_filters( 'fw_get_settings_page_slug', 'fw-settings' );
	}

	/**
	 * @return string
	 * @since 2.6.3
	 */
	public function get_options_name_attr_prefix() {
		return 'fw_options';
	}

	/**
	 * @return string
	 * @since 2.6.3
	 */
	public function get_options_id_attr_prefix() {
		return 'fw-option-';
	}

	private function get_current_edit_taxonomy() {
		static $cache_current_taxonomy_data = null;

		if ( $cache_current_taxonomy_data !== null ) {
			return $cache_current_taxonomy_data;
		}

		$result = array(
			'taxonomy' => null,
			'term_id'  => 0,
		);

		do {
			if ( ! is_admin() ) {
				break;
			}

			// code from /wp-admin/admin.php line 110
			{
				if ( isset( $_REQUEST['taxonomy'] ) && taxonomy_exists( $_REQUEST['taxonomy'] ) ) {
					$taxnow = $_REQUEST['taxonomy'];
				} else {
					$taxnow = '';
				}
			}

			if ( empty( $taxnow ) ) {
				break;
			}

			$result['taxonomy'] = $taxnow;

			if ( empty( $_REQUEST['tag_ID'] ) ) {
				return $result;
			}

			// code from /wp-admin/edit-tags.php
			{
				$tag_ID = (int) $_REQUEST['tag_ID'];
			}

			$result['term_id'] = $tag_ID;
		} while ( false );

		$cache_current_taxonomy_data = $result;

		return $cache_current_taxonomy_data;
	}

	public function __construct() {}

	/**
	 * @internal
	 */
	public function _init() {
		if ( is_admin() ) {
			$this->settings_form = new FW_Settings_Form_Theme('theme-settings');
		}

		$this->add_actions();
	
	}

	/**
	 * @internal
	 */
	public function _after_components_init() {}

	private function get_access_key()
	{
		if (!$this->access_key) {
			$this->access_key = new FW_Access_Key('fw_backend');
		}

		return $this->access_key;
	}

	private function add_actions() {
		if ( is_admin() ) {
			
			//add_action('init', array($this, '_action_init'), 20);
			add_action('admin_enqueue_scripts', array($this, '_action_admin_register_scripts'),
			
				8
			);
		
			
			
		}

		//add_action('save_post', array($this, '_action_save_post'), 7, 3);
		add_action('wp_restore_post_revision', array($this, '_action_restore_post_revision'), 10, 2);
		add_action( '_wp_put_post_revision', array( $this, '_action__wp_put_post_revision' ) );

		
	}


	/**
	 * @param string|FW_Option_Type $option_type_class
	 * @param string|null $type
	 *
	 * @internal
	 */
	private function register_option_type( $option_type_class, $type = null ) {
		
	}

	/**
	 * @param string|FW_Container_Type $container_type_class
	 * @param string|null $type
	 *
	 * @internal
	 */
	private function register_container_type( $container_type_class, $type = null ) {
		
	}

	private function register_static() {
		if (
			!doing_action('admin_enqueue_scripts')
			&&
			!did_action('admin_enqueue_scripts')
		) {
			
			return;
		}

		if ( $this->static_registered ) {
			return;
		}
		
		if ( ! is_admin() ) {
			$this->static_registered = true;

			return;
		}

		wp_register_script(
			'fw-events',
			fw_get_framework_directory_uri( '/static/js/fw-events.js' ),
			array(),
			fw()->manifest->get_version(),
			true
		);


		{
			wp_register_style(
				'qtip',
				fw_get_framework_directory_uri( '/static/libs/qtip/css/jquery.qtip.min.css' ),
				array(),
				fw()->manifest->get_version()
			);
			wp_register_script(
				'qtip',
				fw_get_framework_directory_uri( '/static/libs/qtip/jquery.qtip.min.js' ),
				array( 'jquery' ),
				fw()->manifest->get_version()
			);
		}

		/**
		 * Important!
		 * Call wp_enqueue_media() before wp_enqueue_script('fw') (or using 'fw' in your script dependencies)
		 * otherwise fw.OptionsModal won't work
		 */
		{
			wp_register_style(
				'fw',
				fw_get_framework_directory_uri( '/static/css/wcf.css' ),
				array( ),
				fw()->manifest->get_version()
			);

			

			wp_register_script(
				'fw',
				fw_get_framework_directory_uri( '/static/js/wcf.js' ),
				array( 'jquery', 'fw-events', 'backbone' ),
				fw()->manifest->get_version(),
				false 
			);

			wp_localize_script( 'fw', '_fw_localized', array(
				'FW_URI'     => fw_get_framework_directory_uri(),
				'SITE_URI'   => site_url(),
				'LOADER_URI' => apply_filters( 'fw_loader_image', fw_get_framework_directory_uri() . '/static/img/logo.svg' ),
				'l10n'       => array_merge(
					$l10n = array(
						'modal_save_btn' => __( 'Save', 'fw' ),
						'done'     => __( 'Done', 'fw' ),
						'ah_sorry' => __( 'Ah, Sorry', 'fw' ),
						'reset'    => __( 'Reset', 'fw' ),
						'apply'    => __( 'Apply', 'fw' ),
						'cancel'   => __( 'Cancel', 'fw' ),
						'ok'       => __( 'Ok', 'fw' )
					),
					/**
					 * 
					 * @since 2.6.14
					 */
					apply_filters('fw_js_l10n', $l10n)
				),
				'options_modal' => array(
					/** @since 2.6.13 */
					'default_reset_bnt_disabled' => apply_filters('fw:option-modal:default:reset-btn-disabled', false)
				),
			) );
		}
		

		$this->static_registered = true;
	}

	/**
	 * @param $class
	 *
	 * @return FW_Option_Type
	 * @throws FW_Option_Type_Exception_Invalid_Class
	 */
	protected function get_instance( $class ) {
		if ( ! class_exists( $class )
		     || (
			     ! is_subclass_of( $class, 'FW_Option_Type' )
			     &&
			     ! is_subclass_of( $class, 'FW_Container_Type' )
		     )
		) {
			throw new FW_Option_Type_Exception_Invalid_Class( $class );
		}

		return new $class;
	}

	public function _filter_admin_footer_text( $html ) {
		
	}

	/**
	 * Print framework version in the admin footer
	 *
	 * @param string $html
	 *
	 * @return string
	 * @internal
	 */
	public function _filter_footer_version( $html ) {
		return $html;
	}
	

	public function render_meta_box( $post, $args ) {
	
	}

	/**
	 * @param object $term
	 */
	public function _action_create_taxonomy_options( $term ) {
		
	}

	/**
	 * @param string $taxonomy
	 */
	public function _action_create_add_taxonomy_options( $taxonomy ) {
		
	}

	public function _action_init() {
	
	
	}

	/**
	 * Save meta from $_POST to fw options (post meta)
	 * @param int $post_id
	 * @param WP_Post $post
	 * @param bool $update
	 */
	public function _action_save_post( $post_id, $post, $update ) {
		
	}

	/**
	 * @param $revision_id
	 */
	public function _action__wp_put_post_revision( $revision_id ) {
		if(!function_exists('fw_set_db_post_option')){
			return;
		}
		/**
		 * Copy options meta from post to revision
		 */
		fw_set_db_post_option(
			$revision_id,
			null,
			(array) fw_get_db_post_option(
				wp_is_post_revision( $revision_id ),
				null,
				array()
			)
		);
	}

	/**
	 * @param $post_id
	 * @param $revision_id
	 */
	public function _action_restore_post_revision($post_id, $revision_id)
	{
		/**
		 * Copy options meta from revision to post
		 */
		if(!function_exists('fw_set_db_post_option')){
			return;
		}
		fw_set_db_post_option(
			$post_id,
			null,
			(array)fw_get_db_post_option($revision_id, null, array())
		);
	}

	/**
	 * Update all post meta `fw_option:<option-id>` with values from post options that has the 'save-in-separate-meta' parameter
	 *
	 * @param int $post_id
	 *
	 * @return bool
	 * @deprecated since 2.5.0
	 */
	public function _sync_post_separate_meta( $post_id ) {
		if ( ! ( $post_type = get_post_type( $post_id ) ) ) {
			return false;
		}

		$meta_prefix = 'fw_option:';
		$only_options = fw_extract_only_options( fw()->theme->get_post_options( $post_type ) );
		$separate_meta_options = array();

		// Collect all options that needs to be saved in separate meta
		{
			$options_values = fw_get_db_post_option( $post_id );

			foreach ($only_options as $option_id => $option) {
				if (
					isset( $option['save-in-separate-meta'] )
					&&
					$option['save-in-separate-meta']
					&&
					array_key_exists( $option_id, $options_values )
				) {
					if (defined('WP_DEBUG') && WP_DEBUG) {
						FW_Flash_Messages::add(
							'save-in-separate-meta:deprecated',
							'<p>The <code>save-in-separate-meta</code> option parameter is <strong>deprecated</strong>.</p>'
							.'<p>Please replace</p>'
							.'<pre>\'save-in-separate-meta\' => true</pre>'
							.'<p>with</p>'
							.'<pre>\'fw-storage\' => array('
							."\n	'type' => 'post-meta',"
							."\n	'post-meta' => 'fw_option:{your-option-id}',"
							."\n)</pre>"
							.'<p>in <code>{theme}'. fw_get_framework_customizations_dir_rel_path('/theme/options/posts/'. $post_type .'.php') .'</code></p>'
							.'<p><a href="'. esc_attr('http://manual.unyson.io/en/latest/options/storage.html#content') .'" target="_blank">'. esc_html__('Info about fw-storage', 'fw') .'</a></p>',
							'warning'
						);
					}

					$separate_meta_options[ $meta_prefix . $option_id ] = $options_values[ $option_id ];
				}
			}

			unset( $options_values );
		}

		// Delete meta that starts with $meta_prefix
		{
			/** @var wpdb $wpdb */
			global $wpdb;

			foreach (
				$wpdb->get_results(
					$wpdb->prepare(
						"SELECT meta_key " .
						"FROM {$wpdb->postmeta} " .
						"WHERE meta_key LIKE %s AND post_id = %d",
						$wpdb->esc_like( $meta_prefix ) . '%',
						$post_id
					)
				) as $row
			) {
				if (
					array_key_exists( $row->meta_key, $separate_meta_options )
					||
					( // skip options containing 'fw-storage'
						($option_id = substr($row->meta_key, 10))
						&&
						isset($only_options[$option_id]['fw-storage'])
					)
				) {
					/**
					 * This meta exists and will be updated below.
					 * Do not delete for performance reasons, instead of delete->insert will be performed only update
					 */
					continue;
				} else {
					// this option does not exist anymore
					delete_post_meta( $post_id, $row->meta_key );
				}
			}
		}

		foreach ( $separate_meta_options as $meta_key => $option_value ) {
			fw_update_post_meta($post_id, $meta_key, $option_value );
		}

		return true;
	}

	/**
	 * @param int $term_id
	 */
	public function _action_save_taxonomy_fields( $term_id ) {
		
	}

	public function _action_term_edit( $term_id, $tt_id, $taxonomy ) {
		
	}

	public function _action_admin_register_scripts() {
		$this->register_static();
	}

	public function _action_admin_enqueue_scripts() {
		/**
		 * Enqueue settings options static in <head>
		 * @see FW_Settings_Form_Theme::_action_admin_enqueue_scripts()
		 */

		/**
		 * Enqueue post options static in <head>
		 */
		{
			if ( 'post' === get_current_screen()->base && get_the_ID() ) {
				fw()->backend->enqueue_options_static(
					fw()->theme->get_post_options( get_post_type() )
				);

				do_action( 'fw_admin_enqueue_scripts:post', get_post() );
			}
		}

	}

	/**
	 * Render options array and return the generated HTML
	 *
	 * @param array $options
	 * @param array $values Correct values returned by fw_get_options_values_from_input()
	 * @param array $options_data {id_prefix => ..., name_prefix => ...}
	 * @param string $design
	 *
	 * @return string HTML
	 */
	public function render_options( $options, $values = array(), $options_data = array(), $design = null ) {
		
		return '';
	}

	/**
	 * Enqueue options static
	 *
	 * Useful when you have dynamic options html on the page (for e.g. options modal)
	 * and in order to initialize that html properly, the option types scripts styles must be enqueued on the page
	 *
	 * @param array $options
	 */
	public function enqueue_options_static( $options ) {
			
	}

	/**
	 * @internal
	 * @param array $data
	 */
	public static function _callback_fw_collect_options_enqueue_static($data) {
		
	}

	/**
	 * Render option enclosed in backend design
	 *
	 * @param string $id
	 * @param array $option
	 * @param array $data
	 * @param string $design default or taxonomy
	 *
	 * @return string
	 */
	public function render_option( $id, $option, $data = array(), $design = null ) {

		
	}

	/**
	 * Render a meta box
	 *
	 * @param string $id
	 * @param string $title
	 * @param string $content HTML
	 * @param array $other Optional elements
	 *
	 * @return string Generated meta box html
	 */
	public function render_box( $id, $title, $content, $other = array() ) {
		
	}

	/**
	 * @param FW_Access_Key $access_key
	 * @param string|FW_Option_Type $option_type_class
	 *
	 * @internal
	 */
	public function _register_option_type( FW_Access_Key $access_key, $option_type_class, $type= null ) {
		if ( $access_key->get_key() !== 'fw_option_type' ) {
			trigger_error( 'Call denied', E_USER_ERROR );
		}

		//$this->register_option_type( $option_type_class, $type );
	}

	/**
	 * @param FW_Access_Key $access_key
	 * @param string|FW_Container_Type $container_type_class
	 *
	 * @internal
	 */
	public function _register_container_type( FW_Access_Key $access_key, $container_type_class ) {
	
	}

	/**
	 * @param string $type
	 * @return FW_Option_Type
	 */
	public function option_type( $type ) {
		
	}

	/**
	 * Return an array with all option types names
	 *
	 * @return array
	 *
	 * @since 2.6.11
	 */
	public function get_option_types() {
		
	
	}

	/**
	 * Return an array with all container types names
	 *
	 * @return array
	 *
	 * @since 2.6.11
	 */
	public function get_container_types() {
	
	}

	/**
	 * @param string $type
	 * @return FW_Container_Type
	 */
	public function container_type( $type ) {
		
	}

	

	/**
	 * @internal
	 */
	public function _action_enqueue_customizer_static()
	{
		
		
	}

	/**
	 * Get markdown parser with autoloading and caching
	 *
	 * Usage:
	 *   fw()->backend->get_markdown_parser()
	 *
	 * @param bool $fresh_instance Whether to force return a fresh instance of the class
	 *
	 * @return Parsedown
	 *
	 * @since 2.6.9
	 */
	public function get_markdown_parser($fresh_instance = false) {		

		return '';
	}
}
