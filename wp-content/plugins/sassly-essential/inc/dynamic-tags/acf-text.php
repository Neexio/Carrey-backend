<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class ACF_Text extends Tag {

	public function get_name() {
		return 'acf-text';
	}

	public function get_title() {
		return esc_html__( 'ACF', 'sassly-essential' ) . ' ' . esc_html__( 'Field', 'sassly-essential' );
	}

	public function get_group() {
		return [ 'wcf' ];
	}

	public function get_categories() {
		return [
			'text','post_meta'
		];
	}

	public function render() {
		list( $field, $meta_key ) = Module::get_tag_value_field( $this );

		if ( $field && ! empty( $field['type'] ) ) {
			$value = $field['value'];

			switch ( $field['type'] ) {
				case 'radio':
					if ( isset( $field['choices'][ $value ] ) ) {
						$value = $field['choices'][ $value ];
					}
					break;
				case 'select':
					// Use as array for `multiple=true` or `return_format=array`.
					$values = (array) $value;

					foreach ( $values as $key => $item ) {
						if ( isset( $field['choices'][ $item ] ) ) {
							$values[ $key ] = $field['choices'][ $item ];
						}
					}

					$value = implode( ', ', $values );

					break;
				case 'checkbox':
					$value = (array) $value;
					$values = [];
					foreach ( $value as $item ) {
						if ( isset( $field['choices'][ $item ] ) ) {
							$values[] = $field['choices'][ $item ];
						} else {
							$values[] = $item;
						}
					}

					$value = implode( ', ', $values );

					break;
				case 'oembed':
					// Get from db without formatting.
					$value = $this->get_queried_object_meta( $meta_key );
					break;
				case 'google_map':
					$meta = $this->get_queried_object_meta( $meta_key );
					$value = isset( $meta['address'] ) ? $meta['address'] : '';
					break;
			} // End switch().
		} else {
			// Field settings has been deleted or not available.
			$value = get_field( $meta_key );
		} // End if().

		echo wp_kses_post( $value );
	}

	public function get_panel_template_setting_key() {
		return 'key';
	}

	protected function register_controls() {
		Module::add_key_control( $this );
	}

	public function get_supported_fields() {
		return [
			'text',
			'textarea',
			'number',
			'email',
			'password',
			'wysiwyg',
			'select',
			'checkbox',
			'radio',
			'true_false',

			// Pro
			'oembed',
			'google_map',
			'date_picker',
			'time_picker',
			'date_time_picker',
			'color_picker',
		];
	}

	private function get_queried_object_meta( $meta_key ) {
		$value = '';
		if ( is_singular() ) {
			$value = get_post_meta( get_the_ID(), $meta_key, true );
		} elseif ( is_tax() || is_category() || is_tag() ) {
			$value = get_term_meta( get_queried_object_id(), $meta_key, true );
		}

		return $value;
	}
}
