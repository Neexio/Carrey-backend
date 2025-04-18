<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class ACF_COLOR extends Data_Tag {

	public function get_name() {
		return 'acf-color';
	}

	public function get_title() {
		return esc_html__( 'ACF', 'sassly-essential' ) . ' ' . esc_html__( 'Color Picker Field', 'sassly-essential' );
	}

	public function get_group() {
		return [ 'wcf' ];
	}

	public function get_categories() {
		return [ 'color' ];
	}

	public function get_panel_template_setting_key() {
		return 'key';
	}

	public function get_value( array $options = [] ) {
		list( $field, $meta_key ) = Module::get_tag_value_field( $this );

		if ( $field ) {
			$value = $field['value'];
		} else {
			// Field settings has been deleted or not available.
			$value = get_field( $meta_key );
		}

		if ( empty( $value ) && $this->get_settings( 'fallback' ) ) {
			$value = sanitize_text_field( $this->get_settings( 'fallback' ) );
		}

		return $value;
	}

	protected function register_controls() {
		Module::add_key_control( $this );
	}

	public function get_supported_fields() {
		return [
			'color_picker',
		];
	}
}
