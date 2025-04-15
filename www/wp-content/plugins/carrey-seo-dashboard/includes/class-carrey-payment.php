<?php
/**
 * Payment Handler Class
 *
 * @package Carrey_SEO_Dashboard
 */

if (!defined('ABSPATH')) {
    exit;
}

class Carrey_Payment {
    private static $instance = null;
    private $stripe_secret_key;
    private $stripe_public_key;
    private $plans = array();

    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        $this->init_payment_settings();
        $this->init_plans();
        $this->init_hooks();
        $this->init_stripe();
    }

    private function init_payment_settings() {
        $this->stripe_secret_key = get_option('carrey_stripe_secret_key');
        $this->stripe_public_key = get_option('carrey_stripe_public_key');
    }

    private function init_plans() {
        $this->plans = array(
            'basic' => array(
                'name' => 'Basic',
                'price' => 49,
                'features' => array(
                    '1 website',
                    'Basic SEO analysis',
                    'Monthly report',
                    'Email support'
                ),
                'stripe_plan_id' => 'price_basic_monthly'
            ),
            'business' => array(
                'name' => 'Business',
                'price' => 149,
                'features' => array(
                    '3 websites',
                    'Advanced SEO analysis',
                    'Weekly report',
                    'Priority support',
                    'One-click site optimization',
                    'Automatic optimizations'
                ),
                'stripe_plan_id' => 'price_business_monthly'
            ),
            'enterprise' => array(
                'name' => 'Enterprise',
                'price' => 399,
                'features' => array(
                    'Unlimited websites',
                    'Full SEO analysis',
                    'Daily report',
                    'Dedicated support',
                    'API access',
                    'Custom reports',
                    'One-click site optimization',
                    'Advanced automation'
                ),
                'stripe_plan_id' => 'price_enterprise_monthly'
            )
        );
    }

    private function init_hooks() {
        add_action('admin_menu', array($this, 'add_payment_menu'));
        add_action('wp_ajax_carrey_create_subscription', array($this, 'create_subscription'));
        add_action('wp_ajax_carrey_cancel_subscription', array($this, 'cancel_subscription'));
        add_action('wp_ajax_carrey_update_subscription', array($this, 'update_subscription'));
    }

    private function init_stripe() {
        \Stripe\Stripe::setApiKey($this->stripe_secret_key);
    }

    public function add_payment_menu() {
        add_submenu_page(
            'carrey-seo-dashboard',
            'Subscription',
            'Subscription',
            'manage_options',
            'carrey-subscription',
            array($this, 'render_subscription_page')
        );
    }

    public function render_subscription_page() {
        $user_id = get_current_user_id();
        $subscription = $this->get_user_subscription($user_id);
        
        wp_enqueue_script('stripe-js', 'https://js.stripe.com/v3/');
        wp_enqueue_script('carrey-payment', plugins_url('admin/js/payment.js', dirname(__FILE__)), array('jquery', 'stripe-js'), CARREY_SEO_VERSION, true);
        wp_localize_script('carrey-payment', 'carreyPayment', array(
            'stripePublicKey' => $this->stripe_public_key,
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('carrey_payment_nonce')
        ));
        
        include CARREY_SEO_PATH . 'admin/templates/subscription.php';
    }

    public function create_stripe_products() {
        try {
            foreach ($this->plans as $plan_id => $plan) {
                // Create product if it doesn't exist
                $product = \Stripe\Product::create([
                    'name' => $plan['name'],
                    'description' => implode(', ', $plan['features'])
                ]);

                // Create price for the product
                $price = \Stripe\Price::create([
                    'product' => $product->id,
                    'unit_amount' => $plan['price'] * 100, // Convert to cents
                    'currency' => 'nok',
                    'recurring' => ['interval' => 'month'],
                ]);

                // Update plan with Stripe IDs
                $this->plans[$plan_id]['stripe_product_id'] = $product->id;
                $this->plans[$plan_id]['stripe_price_id'] = $price->id;
            }
        } catch (Exception $e) {
            error_log('Stripe product creation failed: ' . $e->getMessage());
        }
    }

    public function create_subscription() {
        check_ajax_referer('carrey_payment_nonce', 'nonce');
        
        $user_id = get_current_user_id();
        $plan_id = sanitize_text_field($_POST['plan_id']);
        
        if (!isset($this->plans[$plan_id])) {
            wp_send_json_error(array('message' => 'Invalid plan selected'));
            return;
        }
        
        try {
            // Create or retrieve customer
            $customer_id = get_user_meta($user_id, 'carrey_stripe_customer_id', true);
            if (!$customer_id) {
                $customer = \Stripe\Customer::create([
                    'email' => wp_get_current_user()->user_email,
                    'source' => $_POST['token']
                ]);
                $customer_id = $customer->id;
                update_user_meta($user_id, 'carrey_stripe_customer_id', $customer_id);
            }
            
            // Create subscription
            $subscription = \Stripe\Subscription::create([
                'customer' => $customer_id,
                'items' => [['price' => $this->plans[$plan_id]['stripe_price_id']]],
                'expand' => ['latest_invoice.payment_intent']
            ]);
            
            // Update user subscription data
            $this->update_user_subscription($user_id, $customer_id, $subscription->id, 'active');
            
            wp_send_json_success(array(
                'message' => 'Subscription created successfully!',
                'subscription' => $subscription
            ));
        } catch (Exception $e) {
            wp_send_json_error(array(
                'message' => $e->getMessage()
            ));
        }
    }

    public function cancel_subscription() {
        check_ajax_referer('carrey_payment_nonce', 'nonce');
        
        $user_id = get_current_user_id();
        $subscription_id = get_user_meta($user_id, 'carrey_subscription_id', true);
        
        try {
            $subscription = \Stripe\Subscription::retrieve($subscription_id);
            $subscription->cancel();
            
            $this->update_user_subscription($user_id, null, null, 'cancelled');
            
            wp_send_json_success(array(
                'message' => 'Subscription cancelled successfully!'
            ));
        } catch (Exception $e) {
            wp_send_json_error(array(
                'message' => $e->getMessage()
            ));
        }
    }

    public function update_subscription() {
        check_ajax_referer('carrey_payment_nonce', 'nonce');
        
        $user_id = get_current_user_id();
        $new_plan_id = sanitize_text_field($_POST['plan_id']);
        
        if (!isset($this->plans[$new_plan_id])) {
            wp_send_json_error(array('message' => 'Invalid plan selected'));
            return;
        }
        
        try {
            $subscription_id = get_user_meta($user_id, 'carrey_subscription_id', true);
            $subscription = \Stripe\Subscription::retrieve($subscription_id);
            
            $subscription->items = array(
                array(
                    'id' => $subscription->items->data[0]->id,
                    'price' => $this->plans[$new_plan_id]['stripe_price_id']
                )
            );
            
            $subscription->save();
            
            wp_send_json_success(array(
                'message' => 'Subscription updated successfully!'
            ));
        } catch (Exception $e) {
            wp_send_json_error(array(
                'message' => $e->getMessage()
            ));
        }
    }

    private function get_user_subscription($user_id) {
        $subscription_id = get_user_meta($user_id, 'carrey_subscription_id', true);
        $status = get_user_meta($user_id, 'carrey_subscription_status', true);
        
        if (!$subscription_id || $status !== 'active') {
            return null;
        }
        
        try {
            \Stripe\Stripe::setApiKey($this->stripe_secret_key);
            return \Stripe\Subscription::retrieve($subscription_id);
        } catch (Exception $e) {
            return null;
        }
    }

    private function update_user_subscription($user_id, $customer_id, $subscription_id, $status) {
        update_user_meta($user_id, 'carrey_stripe_customer_id', $customer_id);
        update_user_meta($user_id, 'carrey_subscription_id', $subscription_id);
        update_user_meta($user_id, 'carrey_subscription_status', $status);
    }

    public function get_plans() {
        return $this->plans;
    }

    public function get_stripe_public_key() {
        return $this->stripe_public_key;
    }

    public function get_stripe_secret_key() {
        return $this->stripe_secret_key;
    }
} 