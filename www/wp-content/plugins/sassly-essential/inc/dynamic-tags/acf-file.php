<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class ACF_File extends ACF_Image {

	public function get_name() {
		return 'acf-file';
	}

	public function get_title() {
		return esc_html__( 'ACF', 'sassly-essential' ) . ' ' . esc_html__( 'File Field', 'sassly-essential' );
	}

	public function get_categories() {
		return [
			'media',
		];
	}

	public function get_supported_fields() {
		return [
			'file',
		];
	}
}
