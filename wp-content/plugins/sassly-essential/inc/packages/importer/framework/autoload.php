<?php if ( ! defined( 'FW' ) ) {
	die( 'Forbidden' );
}

spl_autoload_register( '_fw_core_autoload' );
function _fw_core_autoload( $class ) {
	switch ( $class ) {
		case 'FW_Manifest' :
		case 'FW_Framework_Manifest' :
		case 'FW_Theme_Manifest' :
		case 'FW_Extension_Manifest' :
			require_once dirname( __FILE__ ) . '/core/class-fw-manifest.php';
			break;
	}
}

spl_autoload_register( '_fw_core_components_autoload' );
function _fw_core_components_autoload( $class ) {
	switch ( $class ) {
		case '_FW_Component_Backend' :
			require_once dirname( __FILE__ ) . '/core/components/backend.php';
			break;
		case '_FW_Component_Extensions' :
			require_once dirname( __FILE__ ) . '/core/components/extensions.php';
			break;
		case '_FW_Component_Theme' :
			require_once dirname( __FILE__ ) . '/core/components/theme.php';
			break;
		case 'FW_Settings_Form_Theme' :
			require_once dirname( __FILE__ ) . '/core/components/backend/class-fw-settings-form-theme.php';
			break;
	}
}

spl_autoload_register( '_fw_core_components_extensions_autoload' );
function _fw_core_components_extensions_autoload( $class ) {
	switch ( $class ) {
		case 'FW_Extension_Default' :
			require_once dirname( __FILE__ ) . '/core/components/extensions/class-fw-extension-default.php';
			break;
		case '_FW_Extensions_Manager' :
			require_once dirname( __FILE__ ) . '/core/components/extensions/manager/class--fw-extensions-manager.php';
			break;
		case '_FW_Extensions_Delete_Upgrader_Skin' :
			require_once dirname( __FILE__ ) . '/core/components/extensions/manager/includes/class--fw-extensions-delete-upgrader-skin.php';
			break;
		case '_FW_Extensions_Install_Upgrader_Skin' :
			require_once dirname( __FILE__ ) . '/core/components/extensions/manager/includes/class--fw-extensions-install-upgrader-skin.php';
			break;
		
		case 'FW_Ext_Download_Source_Custom' :
			require_once dirname( __FILE__ ) . '/core/components/extensions/manager/includes/download-source/types/class-fw-download-source-custom.php';
			break;
		case '_FW_Available_Extensions_Register' :
			require_once dirname( __FILE__ ) . '/core/components/extensions/manager/includes/available-ext/class--fw-available-extensions-register.php';
			break;
		case 'FW_Available_Extension' :
			require_once dirname( __FILE__ ) . '/core/components/extensions/manager/includes/available-ext/class-fw-available-extension.php';
			break;
	}
}

spl_autoload_register( '_fw_core_extends_autoload' );
function _fw_core_extends_autoload( $class ) {
	switch ( $class ) {
		case 'FW_Container_Type' :
			require_once dirname( __FILE__ ) . '/core/extends/class-fw-container-type.php';
			break;
		case 'FW_Option_Type' :
			require_once dirname( __FILE__ ) . '/core/extends/class-fw-option-type.php';
			break;
		case 'FW_Extension' :
			require_once dirname( __FILE__ ) . '/core/extends/class-fw-extension.php';
			break;
		case 'FW_Option_Handler' :
			require_once dirname( __FILE__ ) . '/core/extends/interface-fw-option-handler.php';
			break;
	}
}

spl_autoload_register( '_fw_code_exceptions_autoload' );
function _fw_code_exceptions_autoload( $class ) {
	switch ( $class ) {
		case 'FW_Option_Type_Exception' :
		case 'FW_Option_Type_Exception_Not_Found' :
		case 'FW_Option_Type_Exception_Invalid_Class' :
		case 'FW_Option_Type_Exception_Already_Registered' :
			require_once dirname( __FILE__ ) . '/core/exceptions/class-fw-option-type-exception.php';
			break;
	}
}

// Autoload helper classes
function _fw_autoload_helper_classes($class) {
	static $class_to_file = array(
	
		'FW_Cache' => 'class-fw-cache',
		'FW_Callback' => 'class-fw-callback',
		'FW_Access_Key' => 'class-fw-access-key',
		'FW_WP_Filesystem' => 'class-fw-wp-filesystem',
		'FW_Form' => 'class-fw-form',
		'FW_Form_Not_Found_Exception' => 'exceptions/class-fw-form-not-found-exception',
		'FW_Form_Invalid_Submission_Exception' => 'exceptions/class-fw-form-invalid-submission-exception',
		'FW_Settings_Form' => 'class-fw-settings-form',
		'FW_Request' => 'class-fw-request',
		'FW_Session' => 'class-fw-session',		
		'FW_Flash_Messages' => 'class-fw-flash-messages',
		'FW_Resize' => 'class-fw-resize',	
		'FW_Type' => 'type/class-fw-type',
		'FW_Type_Register' => 'type/class-fw-type-register',
	);

	if (isset($class_to_file[$class])) {
		require dirname(__FILE__) .'/helpers/'. $class_to_file[$class] .'.php';
	}
}
spl_autoload_register('_fw_autoload_helper_classes');







