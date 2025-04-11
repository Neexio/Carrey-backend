<?php
/**
 * Plugin Name: Animation Addon for Elementor(Pro)
 * Description: Elementor addons plugin for Animation Addon for Elementor Plugin.
 * Plugin URI:  https://wealcoder.com//
 * Version:     1.0.0
 * Author:      wealcoder
 * Author URI:  https://wealcoder.com//
 * Text Domain: wcf-addons-pro
 * Elementor tested up to: 3.5.0
 * Elementor Pro tested up to: 3.5.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

if ( ! defined( 'WCF_ADDONS_PRO_VERSION' ) ) {
	/**
	 * Plugin Version.
	 */
	define( 'WCF_ADDONS_PRO_VERSION', '1.0.0' );
}
if ( ! defined( 'WCF_ADDONS_PRO_FILE' ) ) {
	/**
	 * Plugin File Ref.
	 */
	define( 'WCF_ADDONS_PRO_FILE', __FILE__ );
}
if ( ! defined( 'WCF_ADDONS_PRO_BASE' ) ) {
	/**
	 * Plugin Base Name.
	 */
	define( 'WCF_ADDONS_PRO_BASE', plugin_basename( WCF_ADDONS_PRO_FILE ) );
}
if ( ! defined( 'WCF_ADDONS_PRO_PATH' ) ) {
	/**
	 * Plugin Dir Ref.
	 */
	define( 'WCF_ADDONS_PRO_PATH', plugin_dir_path( WCF_ADDONS_PRO_FILE ) );
}
if ( ! defined( 'WCF_ADDONS_PRO_URL' ) ) {
	/**
	 * Plugin URL.
	 */
	define( 'WCF_ADDONS_PRO_URL', plugin_dir_url( WCF_ADDONS_PRO_FILE ) );
}
if ( ! defined( 'WCF_ADDONS_PRO_WIDGETS_PATH' ) ) {
	/**
	 * Widgets Dir Ref.
	 */
	define( 'WCF_ADDONS_PRO_WIDGETS_PATH', WCF_ADDONS_PRO_PATH . 'widgets/' );
}

/**
 * Main WCF_ADDONS_Plugin_Pro Class
 *
 * The init class that runs the Hello World plugin.
 * Intended To make sure that the plugin's minimum requirements are met.
 *
 * You should only modify the constants to match your plugin's needs.
 *
 * Any custom code should go inside Plugin Class in the plugin.php file.
 *
 * @since 1.2.0
 */
final class WCF_ADDONS_Plugin_Pro {

	/**
	 * Plugin Version
	 *
	 * @since 1.0.0
	 * @var string The plugin version.
	 */
	const VERSION = '1.0.0';

	/**
	 * Minimum Elementor Version
	 *
	 * @since 1.0.0
	 * @var string Minimum Elementor version required to run the plugin.
	 */
	const MINIMUM_ELEMENTOR_VERSION = '3.0.0';

	/**
	 * Minimum PHP Version
	 *
	 * @since 1.2.0
	 * @var string Minimum PHP version required to run the plugin.
	 */
	const MINIMUM_PHP_VERSION = '7.4';

	/**
	 * Constructor
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function __construct() {

		// Init Plugin
		add_action( 'plugins_loaded', array( $this, 'init' ) );
	}

	/**
	 * Initialize the plugin
	 *
	 * Validates that Elementor is already loaded.
	 * Checks for basic plugin requirements, if one check fail don't continue,
	 * if all check have passed include the plugin class.
	 *
	 * Fired by `plugins_loaded` action hook.
	 *
	 * @since 1.2.0
	 * @access public
	 */
	public function init() {

		load_plugin_textdomain( 'wcf-addons-pro' );

		// Check if Elementor installed and activated
		if ( ! did_action( 'elementor/loaded' ) ) {
			add_action( 'admin_notices', array( $this, 'admin_notice_missing_main_plugin' ) );

			return;
		}

		// Check if Animation Addon for Elementor installed and activated
		if ( ! class_exists( 'WCF_ADDONS_Plugin' ) ) {
			add_action( 'admin_notices', array( $this, 'admin_notice_missing_wcf_addons_plugin' ) );

			return;
		}

		// Once we get here, We have passed all validation checks so we can safely include our plugin
		require_once( 'class-plugin.php' );

		//wcf plugin loaded
		do_action( 'wcf_plugins_pro_loaded' );
	}

	/**
	 * Admin notice
	 *
	 * Warning when the site doesn't have Elementor installed or activated.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function admin_notice_missing_main_plugin() {
		if ( isset( $_GET['activate'] ) ) {
			unset( $_GET['activate'] );
		}

		$message = sprintf(
		/* translators: 1: Plugin name 2: Elementor */
			esc_html__( '"%1$s" requires "%2$s" to be installed and activated.', 'wcf-addons-pro' ),
			'<strong>' . esc_html__( 'Animation Addon for Elementor(Pro)', 'wcf-addons-pro' ) . '</strong>',
			'<strong>' . esc_html__( 'Elementor', 'wcf-addons-pro' ) . '</strong>'
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
	}

	/**
	 * Admin notice
	 *
	 * Warning when the site doesn't have Animation Addon for Elementor installed or activated.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function admin_notice_missing_wcf_addons_plugin() {
		if ( isset( $_GET['activate'] ) ) {
			unset( $_GET['activate'] );
		}

		$message = sprintf(
		/* translators: 1: Plugin name 2: Elementor */
			esc_html__( '"%1$s" requires "%2$s" to be installed and activated.', 'wcf-addons-pro' ),
			'<strong>' . esc_html__( 'Animation Addon for Elementor(Pro)', 'wcf-addons-pro' ) . '</strong>',
			'<strong>' . esc_html__( 'Animation Addon for Elementor', 'wcf-addons-pro' ) . '</strong>'
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
	}

}

// Instantiate WCF_ADDONS_Plugin_Pro.
new WCF_ADDONS_Plugin_Pro();
