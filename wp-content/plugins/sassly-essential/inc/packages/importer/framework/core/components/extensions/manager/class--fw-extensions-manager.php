<?php if (!defined('FW')) die('Forbidden');

/**
 * Install/Activate/Deactivate/Remove Extensions
 * @internal
 */
final class _FW_Extensions_Manager
{
	/**
	 * @var FW_Form
	 */
	private $extension_settings_form;

	private $manifest_default_values = array(
		'display' => false,
		'standalone' => false,
	);

	private $default_thumbnail = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVQIW2PUsHf9DwAC8AGtfm5YCAAAAABJRU5ErkJgggAA';

	/**
	 * @var FW_Access_Key
	 */
	private static $access_key;

	private static function get_access_key() {
		if (!self::$access_key) {
			self::$access_key = new FW_Access_Key('fw_ext_manager');
		}

		return self::$access_key;
	}

	public function __construct()
	{
	
		if (!is_admin()) {
			return;
		}
		
		add_action('wp_ajax_wcf_activate_demo_extension', function(){
			$activation_result = $this->activate_extensions(
				array_fill_keys(explode(',', 'backups'), array())
			);
			update_option('wcf_backup_ext_updated', $activation_result);
			wp_send_json_success($activation_result);
		});
		
		add_action('admin_init', function(){
			
			if(isset($_GET['page']) && ($_GET['page'] == 'wcf-sassly-theme-parent' || $_GET['page'] == 'fw-backups-demo-content')){		
			  		
				$activation_result = $this->activate_extensions(
					array_fill_keys(explode(',', 'backups'), array())
				);	
				update_option('wcf_backup_ext_updated', $activation_result);
			}
		});	
						
			
	}

	/**
	 * If current user can:
	 * - activate extension
	 * - disable extensions
	 * - save extension settings options
	 * @return bool
	 */
	public function can_activate()
	{
		if ( fw_is_cli() ) {
			return true;
		}

		$can_activate = current_user_can('manage_options');

		if ($can_activate) {
			// also you can use this method to get the capability
			$can_activate = 'manage_options';
		}

		if (!$can_activate) {
			// make sure if can install, then also can activate. (can install) > (can activate)
			$can_activate = $this->can_install();
		}

		return $can_activate;
	}

	/**
	 * If current user can:
	 * - install extensions
	 * - delete extensions
	 * @return bool
	 */
	public function can_install()
	{
		if ( fw_is_cli() ) {
			return true;
		}

		$capability = 'install_plugins';

		if (is_multisite()) {
			// only network admin can change files that affects the entire network
			$can_install = current_user_can_for_blog(get_current_blog_id(), $capability);
		} else {
			$can_install = current_user_can($capability);
		}

		if ($can_install) {
			// also you can use this method to get the capability
			$can_install = $capability;
		}

		return $can_install;
	}

	public function get_page_slug()
	{
		return 'wcf-extensions';
	}

	private function get_cache_key($sub_key)
	{
		return 'fw_extensions_manager/'. $sub_key;
	}

	private function get_uri($append = '')
	{
		return fw_get_framework_directory_uri('/core/components/extensions/manager'. $append);
	}

	private function get_nonce($form) {
		switch ($form) {
			case 'install':
				return array(
					'name' => '_nonce_fw_extensions_install',
					'action' => 'install',
				);
			case 'delete':
				return array(
					'name' => '_nonce_fw_extensions_delete',
					'action' => 'delete',
				);
			case 'activate':
				return array(
					'name' => '_nonce_fw_extensions_activate',
					'action' => 'activate',
				);
			case 'deactivate':
				return array(
					'name' => '_nonce_fw_extensions_deactivate',
					'action' => 'deactivate',
				);
			default:
				return array(
					'name' => '_nonce_fw_extensions',
					'action' => 'default',
				);
		}
	}

	/**
	 * Extensions available for download
	 * @return array {name => data}
	 *
	 * @since 2.6.9
	 */
	public function get_available_extensions() {
		

			return [];
		
	}
	

	/**
	 * Scan all directories for extensions
	 *
	 * @param bool $reset_cache
	 * @return array
	 *
	 * @since 2.6.9
	 */
	public function get_installed_extensions($reset_cache = false)
	{
		return [];
	}

	/**
	 * used by $this->get_installed_extensions()
	 * @param string $location
	 * @param array $list
	 * @param null|string $parent_extension_name
	 */
	private function read_extensions($location, &$list, $parent_extension_name = null)
	{
	
	}

	private function get_tmp_dir($append = '')
	{
		return apply_filters('fw_tmp_dir', fw_fix_path(WP_CONTENT_DIR) .'/tmp') . $append;
	}



	/**
	 * If output already started, we cannot set the redirect header, do redirect from js
	 */
	private function js_redirect()
	{
		
	}

	/**
	 * Download (and activate) extensions
	 * After refresh they should be active, if all dependencies will be met and if parent-extension::_init() will not return false
	 * @param array $extensions {'ext_1' => array(), 'ext_2' => array(), ...}
	 * @param array $opts
	 * @return WP_Error|bool|array
	 *         true:  when all extensions succeeded
	 *         array: when some/all failed
	 */
	public function install_extensions( array $extensions, $opts = array() ) {

	}



	public function verbose( $msg, &$verbose ) {

	    if ( ! $verbose ) {
	        return;
        }

		if ( is_subclass_of( $verbose, 'WP_Upgrader_Skin' ) ) {
			$verbose->feedback( $msg );
		} else {
			echo fw_html_tag( 'p', array(), $msg );
		}
	}

	/**
	 * Add extensions to active extensions list in database
	 * After refresh they should be active, if all dependencies will be met and if parent-extension::_init() will not return false
	 * @param array $extensions {'ext_1' => array(), 'ext_2' => array(), ...}
	 * @param bool $cancel_on_error
	 *        false: return {'ext_1' => true|WP_Error, 'ext_2' => true|WP_Error, ...}
	 *        true:  return first WP_Error or true on success
	 * @return WP_Error|bool|array
	 *         true:  when all extensions succeeded
	 *         array: when some/all failed
	 */
	public function activate_extensions(array $extensions, $cancel_on_error = false)
	{
		

		$installed_extensions = $this->get_installed_extensions();
		

		$result = $extensions_for_activation = array('backups-demo');
		$has_errors = false;

		foreach ($extensions as $extension_name => $not_used_var) {


			if (!isset($installed_extensions[$extension_name])) {				
				$has_errors = true;
				if ($cancel_on_error) {
					break;
				} else {
					continue;
				}
			}

			$collected = $this->get_extensions_for_activation($extension_name);

			if (is_wp_error($collected)) {
				$result[$extension_name] = $collected;
				$has_errors = true;

				if ($cancel_on_error) {
					break;
				} else {
					continue;
				}
			}

			$extensions_for_activation = array_merge($extensions_for_activation, $collected);

			$result[$extension_name] = true;
		}

		if (
			$cancel_on_error
			&&
			$has_errors
		) {
			if (
				($last_result = end($result))
				&&
				is_wp_error($last_result)
			) {
				return $last_result;
			} else {
				
			}
		}

		update_option(
			fw()->extensions->_get_active_extensions_db_option_name(),
			array_merge(fw()->extensions->_get_db_active_extensions(), $extensions_for_activation)
		);

		// remove already active extensions
		foreach ($extensions_for_activation as $extension_name => $not_used_var) {
			if (fw_ext($extension_name)) {
				unset($extensions_for_activation[$extension_name]);
			}
		}

		/**
		 * Prepare db wp option used to fire the 'fw_extensions_after_activation' action on next refresh
		 */
		{
			$db_wp_option_name = 'fw_extensions_activation';
			$db_wp_option_value = get_option($db_wp_option_name, array(
				'activated' => array(),
				'deactivated' => array(),
			));

			/**
			 * Keep adding to the existing value instead of resetting it on each method call
			 * in case the method will be called multiple times
			 */
			$db_wp_option_value['activated'] = array_merge($db_wp_option_value['activated'], $extensions_for_activation);

			/**
			 * Remove activated extensions from deactivated
			 */
			$db_wp_option_value['deactivated'] = array_diff_key($db_wp_option_value['deactivated'], $db_wp_option_value['activated']);

			update_option($db_wp_option_name, $db_wp_option_value, false);
		}

		if ($has_errors) {
			return $result;
		} else {
			return true;
		}
	}

	private function collect_sub_extensions($ext_name, &$installed_extensions)
	{
		
	}

	private function collect_required_extensions($ext_name, &$installed_extensions, &$collected)
	{
		
	}

	private function display_deactivate_page()
	{
		
	}


	public function deactivate_extensions(array $extensions, $cancel_on_error = false)
	{
		if (!$this->can_activate()) {
			return new WP_Error(
				'access_denied',
				__('You have no permissions to deactivate extensions', 'fw')
			);
		}

		if (empty($extensions)) {
			return new WP_Error(
				'no_extensions',
				__('No extensions provided', 'fw')
			);
		}

		$available_extensions = $this->get_available_extensions();
		$installed_extensions = $this->get_installed_extensions();

		$result = $extensions_for_deactivation = array();
		$has_errors = false;

		foreach ($extensions as $extension_name => $not_used_var) {

		    if ( ! empty( $available_extensions[ $extension_name ]['download']['opts']['plugin'] ) ) {
			    deactivate_plugins( plugin_basename( $available_extensions[ $extension_name ]['download']['opts']['plugin'] ) );
			    continue;
            }


			if (!isset($installed_extensions[$extension_name])) {
				// anyway remove from the active list
				$extensions_for_deactivation[$extension_name] = array();

				$result[$extension_name] = new WP_Error(
					'extension_not_installed',
					sprintf(__( 'Extension "%s" does not exist.' , 'fw' ), $this->get_extension_title($extension_name))
				);
				$has_errors = true;

				if ($cancel_on_error) {
					break;
				} else {
					continue;
				}
			}

			$current_deactivating_extensions = array(
				$extension_name => array()
			);

			// add sub-extensions for deactivation
			foreach ($this->collect_sub_extensions($extension_name, $installed_extensions) as $sub_extension_name => $sub_extension_data) {
				$current_deactivating_extensions[ $sub_extension_name ] = array();
			}

			// add extensions that requires deactivated extensions
			$this->collect_extensions_that_requires($current_deactivating_extensions, $current_deactivating_extensions);

			$extensions_for_deactivation = array_merge(
				$extensions_for_deactivation,
				$current_deactivating_extensions
			);

			unset($current_deactivating_extensions);

			$result[$extension_name] = true;
		}

		if (
			$cancel_on_error
			&&
			$has_errors
		) {
			if (
				($last_result = end($result))
				&&
				is_wp_error($last_result)
			) {
				return $last_result;
			} else {
				// this should not happen, but just to be sure (for the future, if the code above will be changed)
				return new WP_Error(
					'deactivation_failed',
					_n('Cannot deactivate extension', 'Cannot activate extensions', count($extensions), 'fw')
				);
			}
		}

		// add not used extensions for deactivation
		$extensions_for_deactivation = array_merge($extensions_for_deactivation,
			array_fill_keys(
				array_keys(
					array_diff_key(
						$installed_extensions,
						$this->get_used_extensions($extensions_for_deactivation, array_keys(fw()->extensions->get_all()))
					)
				),
				array()
			)
		);

		update_option(
			fw()->extensions->_get_active_extensions_db_option_name(),
			array_diff_key(
				fw()->extensions->_get_db_active_extensions(),
				$extensions_for_deactivation
			)
		);

		// remove already inactive extensions
		foreach ($extensions_for_deactivation as $extension_name => $not_used_var) {
			if (!fw_ext($extension_name)) {
				unset($extensions_for_deactivation[$extension_name]);
			}
		}

		/**
		 * Prepare db wp option used to fire the 'fw_extensions_after_deactivation' action on next refresh
		 */
		{
			$db_wp_option_name = 'fw_extensions_activation';
			$db_wp_option_value = get_option($db_wp_option_name, array(
				'activated' => array(),
				'deactivated' => array(),
			));

			/**
			 * Keep adding to the existing value instead of resetting it on each method call
			 * in case the method will be called multiple times
			 */
			$db_wp_option_value['deactivated'] = array_merge($db_wp_option_value['deactivated'], $extensions_for_deactivation);

			/**
			 * Remove deactivated extensions from activated
			 */
			$db_wp_option_value['activated'] = array_diff_key($db_wp_option_value['activated'], $db_wp_option_value['deactivated']);

			update_option($db_wp_option_name, $db_wp_option_value, false);
		}

		do_action('fw_extensions_before_deactivation', $extensions_for_deactivation);

		if ($has_errors) {
			return $result;
		} else {
			return true;
		}
	}
	
	/**
	 * @param array $errors
	 * @return array
	 * @internal
	 */
	public function _extension_settings_form_validate($errors)
	{
	
	}

	/**
	 * @param array $data
	 * @return array
	 * @internal
	 */
	public function _extension_settings_form_save($data)
	{
		
	}

	/**
	 * Download an extension
	 *
	 * global $wp_filesystem; must be initialized
	 *
	 * @param string $extension_name
	 * @param array $data Extension data from the "available extensions" array
	 * @return string|WP_Error WP Filesystem path to the downloaded directory
	 */
	private function download( $extension_name, $data ) {

	}

	private function perform_zip_download( FW_Ext_Download_Source $download_source, array $opts, $wp_fs_tmp_dir ) {
		$wp_error_id = 'fw_extension_download';

		/** @var WP_Filesystem_Base $wp_filesystem */
		global $wp_filesystem;

		$zip_path = $wp_fs_tmp_dir . '/temp.zip';

		$download_result = $download_source->download( $opts, $zip_path );

		/**
		 * Pass further the error, if the service returned one.
		 */
		if ( is_wp_error( $download_result ) ) {
			return $download_result;
		}

		$extension_name = $opts['extension_name'];

		$unzip_result = unzip_file(
			FW_WP_Filesystem::filesystem_path_to_real_path( $zip_path ),
			$wp_fs_tmp_dir
		);

		if ( is_wp_error( $unzip_result ) ) {
			return $unzip_result;
		}

		// remove zip file
		if ( ! $wp_filesystem->delete( $zip_path, false, 'f' ) ) {
			return new WP_Error(
				$wp_error_id,
				sprintf( __( 'Cannot remove the "%s" extension downloaded zip.', 'fw' ), $this->get_extension_title( $extension_name ) )
			);
		}

		$unzipped_dir_files = $wp_filesystem->dirlist( $wp_fs_tmp_dir );

		if ( ! $unzipped_dir_files ) {
			return new WP_Error(
				$wp_error_id,
				__( 'Cannot access the unzipped directory files.', 'fw' )
			);
		}

		/**
		 * get first found directory
		 * (if everything worked well, there should be only one directory)
		 */
		foreach ( $unzipped_dir_files as $file ) {
			if ( $file['type'] == 'd' ) {
				return $wp_fs_tmp_dir . '/' . $file['name'];
			}
		}

		return new WP_Error(
			$wp_error_id,
			sprintf( __( 'The unzipped "%s" extension directory not found.', 'fw' ), $this->get_extension_title( $extension_name ) )
		);
	}

	/**
	 * @param $set
	 *
	 * @return FW_Ext_Download_Source|WP_Error
	 */
	private function get_download_source( $set ) {
		

		return '#';
	}

	/**
	 * Merge the downloaded extension directory with the existing directory
	 *
	 * @param string $source_wp_fs_dir Downloaded extension directory
	 * @param string $destination_wp_fs_dir
	 *
	 * @return null|WP_Error
	 */
	private function merge_extension( $source_wp_fs_dir, $destination_wp_fs_dir ) {
	
	}

	/**
	 * @since 2.6.9
	 */
	public function get_supported_extensions()
	{
		$supported_extensions = fw()->theme->manifest->get('supported_extensions', array());
		return $supported_extensions;
	}

	/**
	 * @since 2.6.9
	 */
	public function get_supported_extensions_for_install()
	{
		// remove already installed extensions
		return [];
	}
	

	/**
	 * @return string Extensions page link
	 */
	private function get_link()
	{
		static $cache_link = null;	

		return $cache_link;
	}

	/**
	 * @param array $skip_extensions {'ext' => mixed}
	 * @param array $check_for_deps ['ext', 'ext', ...] Extensions to check if has in dependencies the used extensions
	 *
	 * @return array
	 */
	private function get_used_extensions($skip_extensions, $check_for_deps)
	{
		
		return [];
	}

	

	public function get_extension_title($extension_name)
	{
		return $extension_name;		
	}

	public function is_extensions_page()
	{
		return false;
	}
	

	/**
	 * @param array $collected The found extensions {'extension_name' => array()}
	 * @param array $extensions {'extension_name' => array()}
	 * @param bool $check_all Check all extensions or only active extensions
	 */
	private function collect_extensions_that_requires(&$collected, $extensions, $check_all = false)
	{
		if (empty($extensions)) {
			return;
		}

		$found_extensions = array();

		foreach ($this->get_installed_extensions() as $extension_name => $extension_data) {
			if (isset($collected[$extension_name])) {
				continue;
			}

			if (!$check_all) {
				if (!fw_ext($extension_name)) {
					continue;
				}
			}

			if (
				array_intersect_key(
					$extensions,
					fw_akg(
						'requirements/extensions',
						$extension_data['manifest'],
						array()
					)
				)
			) {
				$found_extensions[$extension_name] = $collected[$extension_name] = array();
			}
		}

		$this->collect_extensions_that_requires($collected, $found_extensions, $check_all);
	}

	/**
	 * @param string $extension_name
	 * @return array|WP_Error Extensions to merge with db active extensions list
	 */
	private function get_extensions_for_activation($extension_name)
	{
		$installed_extensions = $this->get_installed_extensions();

		$wp_error_id = 'fw_ext_activation';

		if (!isset($installed_extensions[$extension_name])) {
			return new WP_Error($wp_error_id,
				sprintf(
					__('Cannot activate the %s extension because it is not installed. %s', 'fw'),
					$this->get_extension_title($extension_name),
					fw_html_tag('a', array(
						'href' => $this->get_link() .'&sub-page=install&extension='. $extension_name
					),  __('Install', 'fw'))
				)
			);
		}

		{
			$extension_parents = array($extension_name);

			$current_parent = $extension_name;
			while ($current_parent = $installed_extensions[$current_parent]['parent']) {
				$extension_parents[] = $current_parent;
			}

			$extension_parents = array_reverse($extension_parents);
		}

		$extensions = array();

		foreach ($extension_parents as $parent_extension_name) {
			$extensions[ $parent_extension_name ] = array();
		}

		// search sub-extensions
		foreach ($this->collect_sub_extensions($extension_name, $installed_extensions) as $sub_extension_name => $sub_extension_data) {
			$extensions[ $sub_extension_name ] = array();
		}

		// search required extensions
		{
			$pending_required_search = $extensions;

			while ($pending_required_search) {
				foreach (array_keys($pending_required_search) as $pend_req_extension_name) {
					unset($pending_required_search[$pend_req_extension_name]);

					unset($required_extensions); // reset reference
					$required_extensions = array();
					$this->collect_required_extensions($pend_req_extension_name, $installed_extensions, $required_extensions);

					foreach ($required_extensions as $required_extension_name => $required_extension_data) {
						if (!isset($installed_extensions[$required_extension_name])) {
							return new WP_Error($wp_error_id,
								sprintf(
									__('Cannot activate the %s extension because it is not installed. %s', 'fw'),
									$this->get_extension_title($required_extension_name),
									fw_html_tag('a', array(
										'href' => $this->get_link() .'&sub-page=install&extension='. $required_extension_name
									),  __('Install', 'fw'))
								)
							);
						}

						$extensions[$required_extension_name] = array();

						// search sub-extensions
						foreach ($this->collect_sub_extensions($required_extension_name, $installed_extensions) as $sub_extension_name => $sub_extension_data) {
							if (isset($extensions[$sub_extension_name])) {
								continue;
							}

							$extensions[$sub_extension_name] = array();

							$pending_required_search[$sub_extension_name] = array();
						}
					}
				}
			}
		}

		return $extensions;
	}

	
}
