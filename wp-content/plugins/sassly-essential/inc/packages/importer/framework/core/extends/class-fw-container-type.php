<?php if (!defined('FW')) die('Forbidden');

/**
 * Backend option container
 */
abstract class FW_Container_Type
{
	/**
	 * Container's unique type, used in option array in 'type' key
	 * @return string
	 */
	abstract public function get_type();

	abstract protected function _enqueue_static($id, $option, $values, $data);

	
	abstract protected function _render($containers, $values, $data);
	abstract protected function _get_defaults();

	/**
	 * Prevent execute enqueue multiple times
	 * @var bool
	 */
	private $static_enqueued = false;

	final public function __construct()
	{
		// does nothing at the moment, but maybe in the future will do something
	}

	final public function _call_init($access_key)
	{
		if ($access_key->get_key() !== 'fw_backend') {
			trigger_error('Method call not allowed', E_USER_ERROR);
		}

		if (method_exists($this, '_init')) {
			$this->_init();
		}
	}

	private function prepare($id, &$option, &$data)
	{
		$data = array_merge(
			array(
				'id_prefix'   => fw()->backend->get_options_id_attr_prefix(),   // attribute id prefix
				'name_prefix' => fw()->backend->get_options_name_attr_prefix(), // attribute name prefix
			),
			$data
		);

		$option = array_merge(
			$this->get_defaults(),
			$option,
			array(
				'type' => $this->get_type(),
			)
		);

		if (!isset($option['attr'])) {
			$option['attr'] = array();
		}

		if (!isset($option['title'])) {
			$option['title'] = fw_id_to_title($id);
		}

		$option['attr']['class'] = 'fw-container fw-container-type-'. $option['type'] .(
			isset($option['attr']['class'])
				? ' '. $option['attr']['class']
				: ''
			);
	}

	final public function render($options, $values = array(), $data = array())
	{
		$containers = array();

		foreach ($options as $id => &$option) {
			if (
				!isset($option['options'])
				||
				!isset($option['type'])
				||
				$option['type'] !== $this->get_type()
			) {
				continue;
			}

			$this->prepare($id, $option, $data);

			$this->enqueue_static($id, $option, $data);

			$containers[$id] = &$option;
		}

		return $this->_render($containers, $values, $data);
	}

	final public function enqueue_static($id = '', $option = array(), $values = array(), $data = array())
	{
		if (
			!doing_action('admin_enqueue_scripts')
			&&
			!did_action('admin_enqueue_scripts')
		) {
			
			return;
		}

		if ($this->static_enqueued) {
			return false;
		}

		$this->prepare($id, $option, $data);

		$call_next_time = $this->_enqueue_static($id, $option, $values, $data);

		$this->static_enqueued = !$call_next_time;

		return $call_next_time;
	}
	final public function get_defaults()
	{
		$option = $this->_get_defaults();

		$option['type'] = $this->get_type();

		return $option;
	}

	/**
	 * Use this method to register a new container type
	 * @param string|FW_Container_Type $container_type_class
	 */
	final public static function register($container_type_class) {
		static $registration_access_key = null;

		if ($registration_access_key === null) {
			$registration_access_key = new FW_Access_Key('fw_container_type');
		}

		fw()->backend->_register_container_type($registration_access_key, $container_type_class);
	}
}
