<?php defined( 'FW' ) or die();

/**
 * Theme Component
 * Works with framework customizations / theme directory
 */
final class _FW_Component_Theme {
	private static $cache_key = 'fw_theme';

	/**
	 * @var FW_Theme_Manifest
	 */
	public $manifest;

	public function __construct() {
		$manifest = array();

		if ( ( $manifest_file = apply_filters( 'fw_framework_manifest_path', fw_get_template_customizations_directory( '/theme/manifest.php' ) ) ) && is_file( $manifest_file ) ) {
			@include $manifest_file;
		}

		if ( is_child_theme() && ( $manifest_file = fw_get_stylesheet_customizations_directory( '/theme/manifest.php' ) ) && is_file( $manifest_file ) ) {
			$extracted = fw_get_variables_from_file( $manifest_file, array( 'manifest' => array() ) );
			if ( isset( $extracted['manifest'] ) ) {
				$manifest = array_merge( $manifest, $extracted['manifest'] );
			}
		}

		$this->manifest = new FW_Theme_Manifest( $manifest );
	}

	/**
	 * @internal
	 */
	public function _init() {		
		
	}

	/**
	 * @internal
	 */
	public function _after_components_init() {
	}

	/**
	 * Search relative path in: child theme -> parent "theme" directory and return full path
	 *
	 * @param string $rel_path
	 *
	 * @return false|string
	 */
	public function locate_path( $rel_path ) {
		if ( is_child_theme() && file_exists( fw_get_stylesheet_customizations_directory( '/theme' . $rel_path ) ) ) {
			return fw_get_stylesheet_customizations_directory( '/theme' . $rel_path );
		} elseif ( file_exists( fw_get_template_customizations_directory( '/theme' . $rel_path ) ) ) {
			return fw_get_template_customizations_directory( '/theme' . $rel_path );
		} else {
			return false;
		}
	}

	/**
	 * Return array with options from specified name/path
	 *
	 * @param string $name '{theme}/framework-customizations/theme/options/{$name}.php'
	 * @param array  $variables These will be available in options file (like variables for view)
	 *
	 * @return array
	 */
	public function get_options( $name, array $variables = array() ) {
		
	}

	public function get_settings_options() {
		
	}

	public function get_customizer_options() {
		
	}

	public function get_post_options( $post_type ) {
	
	}

	public function get_taxonomy_options( $taxonomy ) {
		
	}

	/**
	 * Return config key value, or entire config array
	 * Config array is merged from child configs
	 *
	 * @param string|null $key Multi key format accepted: 'a/b/c'
	 * @param mixed       $default_value
	 *
	 * @return mixed|null
	 */
	final public function get_config( $key = null, $default_value = null ) {
		
	}

	
}
