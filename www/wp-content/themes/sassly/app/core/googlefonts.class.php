<?php 
namespace sassly\Core;
/**
 * hooks for adding google fonts
 */

class Google_Fonts {

	static private $data = array(
		'subset' => array(),
		'family' => array(),
	);
	public function register()
    {

        add_action( 'wp_enqueue_scripts', array( $this , 'output_url' ), 9999 );
       
    }
	public static function add_typography_v2( $value ) {
		if ( in_array( isset( $value[ 'google_font' ] ), array( true, 'true' ), true ) ) {
			self::$data[ 'family' ][ $value[ 'family' ] ][ 'variants' ][ (string) $value[ 'variation' ] ]	 = true;
			self::$data[ 'subset' ][ $value[ 'subset' ] ]													 = true;
		}
	}

	public static function clear() {
		self::$data = array();
	}

	public static function generate_url() {
		// generate google font url with dynamic fonts
		if ( empty( self::$data[ 'family' ] ) ) {
			return false;
		}

		$query = array(
			'family' => array(),
			'subset' => implode( ',', array_keys( self::$data[ 'subset' ] ) ),
		);

		foreach ( self::$data[ 'family' ] as $family => $family_data ) {
			if ( !empty( $family ) ) {
				$family_data[ 'variants' ][900] = $family_data[ 'variants' ][700] = $family_data[ 'variants' ][400] = 1;
				$query[ 'family' ][] = $family . ':' . implode( ',', array_keys( $family_data[ 'variants' ] ) );
			}
		}
		$query[ 'family' ] = implode( '|', $query[ 'family' ] );
		return add_query_arg( 'family', urlencode( $query[ 'family' ] ), "https://fonts.googleapis.com/css" );

	}

	public static function output_url() {
		if ( $url = self::generate_url() ):
		  wp_enqueue_style( 'sassly-google-fonts', esc_attr( $url ), null, SASSLY_VERSION );
		endif;
	}

}


