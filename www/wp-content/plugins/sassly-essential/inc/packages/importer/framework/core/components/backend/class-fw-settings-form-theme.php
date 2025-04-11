<?php if (!defined('FW')) die('Forbidden');

/**
 * Used in fw()->backend
 * @internal
 */
class FW_Settings_Form_Theme extends FW_Settings_Form {
	protected function _init() {
	
	}

	public function get_options() {
		return fw()->theme->get_settings_options();
	}

	public function set_values($values) {
		fw_set_db_settings_option(null, $values);

		return $this;
	}

	public function get_values() {
		return fw_get_db_settings_option();
	}

	/**
	 * User can overwrite Theme Settings menu, move it and change its title
	 * extract that title from WP menu
	 * @internal
	 */
	public function _action_get_title_from_menu() {

	}

	/**
	 * @internal
	 */
	public function _action_admin_menu() {		
	
	}

	/**
	 * @internal
	 */
	public function _action_admin_change_theme_settings_order() {
		
	}

	/**
	 * @internal
	 */
	public function _action_admin_enqueue_scripts()
	{
		global $plugin_page;

		/**
		 * Enqueue settings options static in <head>
		 */
		{
			if (fw()->backend->_get_settings_page_slug() === $plugin_page) {
				$this->enqueue_static();

				do_action('fw_admin_enqueue_scripts:settings');
			}
		}
	}
}
