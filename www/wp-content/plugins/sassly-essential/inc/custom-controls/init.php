<?php
namespace SasslyEssentialApp;
if (!defined('ABSPATH')) exit; // Exit if accessed directly

class Elementor_Custom_Controls {

	public function includes() {	
		require_once( __DIR__ . '/image-selector-control.php' );
	}

	public function register_controls() {
		$this->includes();
		$controls_manager = \Elementor\Plugin::$instance->controls_manager;
		$controls_manager->register_control(\SasslyEssentialApp\CustomControl\ImageSelector_Control::ImageSelector, new \SasslyEssentialApp\CustomControl\ImageSelector_Control());
	}

	public function __construct() {
		add_action('elementor/controls/controls_registered', [$this, 'register_controls']);
	}

}
new Elementor_Custom_Controls();