<?php
//Enable smooth scroll If Current theme not Info
//$themes = [ 'Info', 'Axtra' ];
//if ( ! in_array( wp_get_theme()->get( 'Name' ), $themes ) ) {
	add_action( 'wp_body_open', function () {
		echo '<div id="smooth-wrapper"><div id="smooth-content">';
	} );
	add_action( 'wp_footer', function () {
		echo '</div></div>';
	}, - 1 );
//}

