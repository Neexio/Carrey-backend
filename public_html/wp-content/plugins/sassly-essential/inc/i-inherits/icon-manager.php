<?php 

namespace SasslyEssentialApp\Inc\Iinherit;
use Elementor\Icons_Manager;
Class Wcf_Icon_Manager extends Icons_Manager{
    public function __construct() {
        parent::__construct();
    }
    
    public static function render_icon( $icon, $attributes = [], $tag = 'i' ) {
		if ( empty( $icon['library'] ) ) {
			return false;
		}

		$output = static::get_icon_html( $icon, $attributes, $tag );       
		return $output;
	}
	
    private static function get_icon_html( array $icon, array $attributes, $tag ) {
		/**
		 * When the library value is svg it means that it's a SVG media attachment uploaded by the user.
		 * Otherwise, it's the name of the font family that the icon belongs to.
		 */
		if ( 'svg' === $icon['library'] ) {
			$output = self::render_uploaded_svg_icon( $icon['value'] );
		} else {
			$output = self::render_font_icon( $icon, $attributes, $tag );
		}
		return $output;
	}
}