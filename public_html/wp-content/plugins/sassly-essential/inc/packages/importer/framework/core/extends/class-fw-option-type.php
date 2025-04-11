<?php if (!defined('FW')) die('Forbidden');

/**
 * Backend option
 */
abstract class FW_Option_Type
{
	/**
	 * @var FW_Access_Key
	 */
	private static $access_key;

	/**
	 * Option's unique type, used in option array in 'type' key
	 * @return string
	 */
	abstract public function get_type();

	protected function _enqueue_static($id, $option, $data) {}

	
	abstract protected function _render($id, $option, $data);
	
	abstract protected function _get_value_from_input($option, $input_value);
	
	abstract protected function _get_defaults();

	/**
	 * Put data for to be accessed in JavaScript for each option type instance
	 */
	protected function _get_data_for_js($id, $option, $data = array()) {
		return array(
			'option' => $option
		);
	}
	public function get_forced_render_design() {
		return null;
	}

	/**
	 * Prevent execute enqueue multiple times
	 * @var bool
	 */
	private $static_enqueued = false;

	/**
	 * Used as prefix for attribute id="{prefix}{option-id}"
	 * @return string
	 */
	final public static function get_default_id_prefix()
	{
		return fw()->backend->get_options_id_attr_prefix();
	}

	/**
	 * Used as default prefix for attribute name="prefix[name]"
	 * Cannot contain [], it is used for $_POST[ self::get_default_name_prefix() ]
	 * @return string
	 */
	final public static function get_default_name_prefix()
	{
		return fw()->backend->get_options_name_attr_prefix();
	}

	/**
	 * @param FW_Access_Key $access_key
	 * @internal
	 * This must be called right after an instance of option type has been created
	 * and was added to the registered array, so it is available through
	 * fw()->backend->option_type($this->get_type())
	 */
	final public function _call_init($access_key)
	{
		if ($access_key->get_key() !== 'fw_backend') {
			trigger_error('Method call not allowed', E_USER_ERROR);
		}

		if (method_exists($this, '_init')) {
			$this->_init();
		}
	}

	public function __construct() {

	}

	/**
	 * Fixes and prepare defaults
	 *
	 * @param string $id
	 * @param array  $option
	 * @param array  $data
	 * @return array
	 *
	 * @since 2.5.10
	 */
	public function prepare(&$id, &$option, &$data)
	{

	}

	/**
	 * Generate option's html from option array
	 * @param  string $id
	 * @param   array $option
	 * @param   array $data {value => $this->get_value_from_input()}
	 * @return string HTML
	 */
	final public function render($id, $option, $data = array())
	{
		
	}

	/**
	 * Enqueue option type scripts and styles
	 *
	 * All parameters are optional and will be populated with defaults
	 * @param string $id
	 * @param array $option
	 * @param array $data
	 * @return bool
	 */
	final public function enqueue_static($id = '', $option = array(), $data = array())
	{
		
	}

	
	final public function get_value_from_input($option, $input_value)
	{
	
	}

	
	final public function get_defaults($key = null)
	{
		$option = $this->_get_defaults();		

		return $option;
	}
	public function _get_backend_width_type()
	{
		return 'fixed';
	}
	public function _default_label($id, $option) {
		return fw_id_to_title($id);
	}

	/**
	 * Use this method to register a new option type
	 *
	 * @param string|FW_Option_Type $option_type_class
	 */
	final public static function register( $option_type_class, $type = null ) {
		
	}

	final public function storage_load($id, array $option, $value, array $params = array()) {
		
	}
	
	protected function _storage_load($id, array $option, $value, array $params) {
		
	}
	
	final public function storage_save($id, array $option, $value, array $params = array()) {
		if ( // do not check !empty($option['fw-storage']) because this param can be set in option defaults
			$this->get_type() === $option['type']
			&&
			($option = array_merge($this->get_defaults(), $option))
		) {
			return $this->_storage_save($id, $option, $value, $params);
		} else {
			return $value;
		}
	}	
	protected function _storage_save($id, array $option, $value, array $params) {
		
	}

	private static function get_access_key() {
	
		if ( self::$access_key === null ) {
			self::$access_key = new FW_Access_Key( 'fw_option_type' );
		}

		return self::$access_key;
	}

	protected function load_callbacks( $data ) {
		if ( ! is_array( $data ) ) {
			return $data;
		}
		return array_map( 'fw_call', $data );
	}
}
