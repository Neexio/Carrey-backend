<?php

add_filter( 'woocommerce_add_to_cart_fragments', 'sassly_woocommerce_header_add_to_cart_fragment' );

function sassly_woocommerce_header_add_to_cart_fragment( $fragments ) {
	global $woocommerce;
	ob_start();

	?>
	 <span class="wcf-wc-cart-fragment-content">
    	 <?php 
    	     echo str_pad(WC()->cart->get_cart_contents_count(), 2, "0", STR_PAD_LEFT);
    	 ?>
	 </span>	
	<?php
	$fragments['.wcf-wc-cart-fragment-content'] = ob_get_clean();
	return $fragments;
}