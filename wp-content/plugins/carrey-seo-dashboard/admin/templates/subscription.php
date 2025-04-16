<?php
/**
 * Subscription Template
 *
 * @package Carrey_SEO_Dashboard
 */

if (!defined('ABSPATH')) {
    exit;
}

$payment = Carrey_Payment::get_instance();
$subscription = $payment->get_user_subscription(get_current_user_id());
$plans = $payment->get_plans();
?>

<div class="wrap carrey-subscription">
    <div class="carrey-header">
        <h1>Subscription</h1>
        <p>Choose a subscription plan that fits your needs</p>
    </div>

    <?php if ($subscription): ?>
        <div class="carrey-current-subscription">
            <h2>Your Current Subscription</h2>
            <div class="subscription-details">
                <p><strong>Plan:</strong> <?php echo esc_html($subscription->plan->nickname); ?></p>
                <p><strong>Status:</strong> <?php echo esc_html($subscription->status); ?></p>
                <p><strong>Next Billing:</strong> <?php echo date('d.m.Y', $subscription->current_period_end); ?></p>
                <button class="button button-secondary cancel-subscription">Cancel Subscription</button>
            </div>
        </div>
    <?php endif; ?>

    <div class="carrey-plans-grid">
        <?php foreach ($plans as $plan_id => $plan): ?>
            <div class="carrey-plan-card">
                <h3><?php echo esc_html($plan['name']); ?></h3>
                <div class="plan-price">
                    <span class="amount"><?php echo esc_html($plan['price']); ?></span>
                    <span class="period">kr/month</span>
                </div>
                <ul class="plan-features">
                    <?php foreach ($plan['features'] as $feature): ?>
                        <li><?php echo esc_html($feature); ?></li>
                    <?php endforeach; ?>
                </ul>
                <button class="button button-primary select-plan" data-plan="<?php echo esc_attr($plan_id); ?>">
                    Select Plan
                </button>
            </div>
        <?php endforeach; ?>
    </div>

    <div id="carrey-payment-form" style="display: none;">
        <h2>Payment Information</h2>
        <form id="stripe-payment-form">
            <div class="form-row">
                <label for="card-element">Credit Card</label>
                <div id="card-element"></div>
                <div id="card-errors" role="alert"></div>
            </div>
            <button type="submit" class="button button-primary">Pay Now</button>
        </form>
    </div>
</div>

<style>
.carrey-subscription {
    max-width: 1200px;
    margin: 20px auto;
}

.carrey-header {
    margin-bottom: 30px;
}

.carrey-current-subscription {
    background: #fff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    margin-bottom: 30px;
}

.subscription-details {
    margin-top: 20px;
}

.carrey-plans-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

.carrey-plan-card {
    background: #fff;
    padding: 30px;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    text-align: center;
    transition: transform 0.3s ease;
}

.carrey-plan-card:hover {
    transform: translateY(-5px);
}

.plan-price {
    margin: 20px 0;
    font-size: 24px;
    font-weight: bold;
}

.plan-price .amount {
    color: #2271b1;
}

.plan-features {
    list-style: none;
    padding: 0;
    margin: 20px 0;
}

.plan-features li {
    padding: 10px 0;
    border-bottom: 1px solid #eee;
}

.plan-features li:last-child {
    border-bottom: none;
}

#carrey-payment-form {
    background: #fff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    margin-top: 30px;
}

.form-row {
    margin-bottom: 20px;
}

#card-element {
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 4px;
}

#card-errors {
    color: #dc3232;
    margin-top: 10px;
}
</style>

<script>
jQuery(document).ready(function($) {
    var stripe = Stripe('<?php echo esc_js($payment->get_stripe_public_key()); ?>');
    var elements = stripe.elements();
    var card = elements.create('card');
    card.mount('#card-element');

    card.addEventListener('change', function(event) {
        var displayError = document.getElementById('card-errors');
        if (event.error) {
            displayError.textContent = event.error.message;
        } else {
            displayError.textContent = '';
        }
    });

    $('.select-plan').on('click', function() {
        var planId = $(this).data('plan');
        $('#carrey-payment-form').show();
        $('html, body').animate({
            scrollTop: $('#carrey-payment-form').offset().top - 100
        }, 500);
    });

    $('#stripe-payment-form').on('submit', function(e) {
        e.preventDefault();
        
        stripe.createToken(card).then(function(result) {
            if (result.error) {
                var errorElement = document.getElementById('card-errors');
                errorElement.textContent = result.error.message;
            } else {
                $.ajax({
                    url: ajaxurl,
                    type: 'POST',
                    data: {
                        action: 'carrey_create_subscription',
                        nonce: '<?php echo wp_create_nonce('carrey_payment_nonce'); ?>',
                        plan_id: $('.select-plan:focus').data('plan'),
                        token: result.token.id
                    },
                    success: function(response) {
                        if (response.success) {
                            location.reload();
                        } else {
                            $('#card-errors').text(response.data.message);
                        }
                    }
                });
            }
        });
    });

    $('.cancel-subscription').on('click', function() {
        if (confirm('Are you sure you want to cancel your subscription?')) {
            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'carrey_cancel_subscription',
                    nonce: '<?php echo wp_create_nonce('carrey_payment_nonce'); ?>'
                },
                success: function(response) {
                    if (response.success) {
                        location.reload();
                    } else {
                        alert(response.data.message);
                    }
                }
            });
        }
    });
});
</script> 