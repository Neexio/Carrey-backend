<?php

namespace sassly\Core;

/**
 * Tags.
 */
class Wcf_Woo {
	/**
	 * register default hooks and actions for WordPress
	 * @return
	 */
	public function register() {

		add_action( 'woocommerce_before_quantity_input_field', [ $this, '_before_quantity_input_field' ] );
		add_action( 'woocommerce_after_quantity_input_field', [ $this, '_after_quantity_input_field' ] );
		
		add_action( 'woocommerce_checkout_before_order_review_heading', [ $this, 'review_heading' ] );
		add_action( 'woocommerce_checkout_after_order_review', [ $this, 'after_order_review' ] );
		
		add_action( 'woocommerce_checkout_before_customer_details', [ $this, 'before_customer_details' ] );
		add_action( 'woocommerce_checkout_after_customer_details', [ $this, 'after_customer_details' ] );
		
		remove_action( 'woocommerce_cart_collaterals', 'woocommerce_cross_sell_display' );
		add_action( 'woocommerce_after_cart', 'woocommerce_cross_sell_display' );
		add_filter( 'single_product_archive_thumbnail_size', [$this, 'single_product_archive_thumbnail_size'] );

		add_filter( 'woocommerce_output_related_products_args', [$this, '_output_related_products_args'] );

	}
	
	public function single_product_archive_thumbnail_size( $size ){	
		return 'full';
	}
	public function before_customer_details(){
		echo '<div class="wcf--checkout"><div class="wcf--checkout-customer-details">';
	}
	public function after_customer_details(){
		echo '</div>';
	}
	public function review_heading(){
		echo '<div class="wcf--checkout-review">';
	}
	public function after_order_review(){
		echo '</div></div>';
	}
	public function _after_quantity_input_field(){
		echo '<button type="button" value="+" class="plus">+</button>';
	}
	public function _before_quantity_input_field(){
		echo '<button type="button" value="-" class="minus">-</button>';
	}

	public function  _output_related_products_args($args) {
		if( sassly_option('wcf_rel_product_cols') != '' && is_numeric(sassly_option('wcf_rel_product_cols'))) {
			$args['posts_per_page'] = sassly_option('wcf_rel_product_cols');
			$args['columns'] = sassly_option('wcf_rel_product_cols');
		}
		return $args;
	}

}




