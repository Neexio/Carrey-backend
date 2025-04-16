<?php if (!defined('FW')) die('Forbidden');

// Process the `fw-storage` option parameter




/**
 * @param null|string $type
 * @return FW_Option_Storage_Type|FW_Option_Storage_Type[]|null
 * @since 2.5.0
 */
function fw_db_option_storage_type($type = null) {
	static $types = null;

	if (is_null($types)) {
		$access_key = new FW_Access_Key('fw:option-storage-register');
		$register = new _FW_Option_Storage_Type_Register($access_key->get_key());

		{
			$register->register(new FW_Option_Storage_Type_WP_Option());
			$register->register(new FW_Option_Storage_Type_Post_Meta());
			$register->register(new FW_Option_Storage_Type_Term_Meta());
		}
	

		$types = $register->_get_types($access_key);
	}

	if (empty($type)) {
		return $types;
	} elseif (isset($types[$type])) {
		return $types[$type];
	} else {
		return null;
	}
}
