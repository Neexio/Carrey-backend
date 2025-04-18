<?php

use Elementor\Controls_Manager;


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class ACF_Image extends Data_Tag {

	public function get_name() {
		return 'acf-image';
	}

	public function get_title() {
		return esc_html__( 'ACF', 'sassly-essential' ) . ' ' . esc_html__( 'Image Field', 'sassly-essential' );
	}

	public function get_group() {
		return [ 'wcf' ];
	}

	public function get_categories() {
		return [ 'image' ];
	}

	public function get_panel_template_setting_key() {
		return 'key';
	}

	public function get_value( array $options = [] ) {
		$image_data = [
			'id' => null,
			'url' => '',
		];

		list( $field, $meta_key ) = Module::get_tag_value_field( $this );

		if ( $field && is_array( $field ) ) {
			$field['return_format'] = isset( $field['save_format'] ) ? $field['save_format'] : $field['return_format'];
			switch ( $field['return_format'] ) {
				case 'object':
				case 'array':
					$value = $field['value'];
					break;
				case 'url':
					$value = [
						'id' => 0,
						'url' => $field['value'],
					];
					break;
				case 'id':
					$src = wp_get_attachment_image_src( $field['value'], $field['preview_size'] );
					$value = [
						'id' => $field['value'],
						'url' => $src[0],
					];
					break;
			}
		}

		if ( ! isset( $value ) ) {
			// Field settings has been deleted or not available.
			$value = get_field( $meta_key );
		}

		if ( empty( $value ) && $this->get_settings( 'fallback' ) ) {
			$value = htmlentities( $this->get_settings( 'fallback' ) );
		}

		if ( ! empty( $value ) && is_array( $value ) ) {
			$image_data['id'] = $value['id'];
			$image_data['url'] = $value['url'];
		}

		return $image_data;
	}

	protected function register_controls() {
		Module::add_key_control( $this );

		$this->add_control(
			'fallback',
			[
				'label' => esc_html__( 'Fallback', 'sassly-essential' ),
				'type' => Controls_Manager::MEDIA,
			]
		);
	}

	public function get_supported_fields() {
		return [
			'image',
		];
	}
}
