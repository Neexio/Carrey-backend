<?php
namespace sassly\Core;
require_once( ABSPATH . 'wp-admin/includes/class-wp-upgrader.php' );
class WCF_Theme_Upgrader_Skin extends \WP_Upgrader_Skin {
    /*
     * Suppress normal upgrader feedback / output
     */
    public function feedback( $string, ...$args ) { 
    
    }    
    
	public function header() {
		ob_start();
	}
	
	public function footer() {
		$output = ob_get_clean();		
	}
}