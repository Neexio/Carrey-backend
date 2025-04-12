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
        <h1>Unlock AI-powered SEO Optimization</h1>
        <p class="subtitle">Boost your website's performance with our advanced AI technology</p>
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
            <div class="carrey-plan-card <?php echo $plan['featured'] ? 'featured' : ''; ?>">
                <?php if ($plan['featured']): ?>
                    <div class="featured-badge">Most Popular</div>
                <?php endif; ?>
                <h3><?php echo esc_html($plan['name']); ?></h3>
                <div class="plan-price">
                    <span class="amount"><?php echo esc_html($plan['price']); ?></span>
                    <span class="period">kr/month</span>
                </div>
                <ul class="plan-features">
                    <?php foreach ($plan['features'] as $feature): ?>
                        <li>
                            <span class="feature-icon">✓</span>
                            <?php echo esc_html($feature); ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
                <button class="button button-primary select-plan" data-plan="<?php echo esc_attr($plan_id); ?>">
                    Get Started
                </button>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="carrey-benefits">
        <h2>Why Choose Carrey SEO?</h2>
        <div class="benefits-grid">
            <div class="benefit-card">
                <div class="benefit-icon">🤖</div>
                <h3>AI-Powered Analysis</h3>
                <p>Get deep insights into your website's performance with our advanced AI technology</p>
            </div>
            <div class="benefit-card">
                <div class="benefit-icon">📈</div>
                <h3>Real-time Optimization</h3>
                <p>Automatically optimize your content and technical SEO in real-time</p>
            </div>
            <div class="benefit-card">
                <div class="benefit-icon">🎯</div>
                <h3>Keyword Tracking</h3>
                <p>Track your keyword rankings and get actionable recommendations</p>
            </div>
            <div class="benefit-card">
                <div class="benefit-icon">📱</div>
                <h3>Mobile Optimization</h3>
                <p>Ensure your website performs perfectly on all devices</p>
            </div>
        </div>
    </div>

    <div id="carrey-payment-form" style="display: none;">
        <h2>Complete Your Subscription</h2>
        <form id="stripe-payment-form">
            <div class="form-row">
                <label for="card-element">Credit Card</label>
                <div id="card-element"></div>
                <div id="card-errors" role="alert"></div>
            </div>
            <button type="submit" class="button button-primary">Subscribe Now</button>
        </form>
    </div>
</div>

<style>
.carrey-subscription {
    max-width: 1200px;
    margin: 20px auto;
}

.carrey-header {
    text-align: center;
    margin-bottom: 50px;
    padding: 40px 20px;
    background: linear-gradient(135deg, #2271b1 0%, #135e96 100%);
    color: white;
    border-radius: 12px;
}

.carrey-header h1 {
    font-size: 2.5em;
    margin: 0 0 10px;
}

.carrey-header .subtitle {
    font-size: 1.2em;
    opacity: 0.9;
    margin: 0;
}

.carrey-current-subscription {
    background: #fff;
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    margin-bottom: 40px;
}

.subscription-details {
    margin-top: 20px;
}

.carrey-plans-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 30px;
    margin-bottom: 50px;
}

.carrey-plan-card {
    background: #fff;
    padding: 40px 30px;
    border-radius: 12px;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    text-align: center;
    transition: all 0.3s ease;
    position: relative;
    border: 2px solid transparent;
}

.carrey-plan-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 8px 15px rgba(0,0,0,0.1);
}

.carrey-plan-card.featured {
    border-color: #2271b1;
    transform: scale(1.05);
}

.carrey-plan-card.featured:hover {
    transform: scale(1.05) translateY(-10px);
}

.featured-badge {
    position: absolute;
    top: -12px;
    right: 20px;
    background: #2271b1;
    color: white;
    padding: 5px 15px;
    border-radius: 20px;
    font-size: 0.9em;
    font-weight: bold;
}

.plan-price {
    margin: 30px 0;
    font-size: 2.5em;
    font-weight: bold;
}

.plan-price .amount {
    color: #2271b1;
}

.plan-features {
    list-style: none;
    padding: 0;
    margin: 30px 0;
    text-align: left;
}

.plan-features li {
    padding: 15px 0;
    border-bottom: 1px solid #eee;
    display: flex;
    align-items: center;
}

.plan-features li:last-child {
    border-bottom: none;
}

.feature-icon {
    color: #2271b1;
    margin-right: 10px;
    font-weight: bold;
}

.carrey-benefits {
    margin: 60px 0;
    text-align: center;
}

.carrey-benefits h2 {
    margin-bottom: 40px;
}

.benefits-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 30px;
}

.benefit-card {
    background: #fff;
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    transition: transform 0.3s ease;
}

.benefit-card:hover {
    transform: translateY(-5px);
}

.benefit-icon {
    font-size: 2.5em;
    margin-bottom: 20px;
}

.benefit-card h3 {
    margin: 0 0 15px;
    color: #2271b1;
}

.benefit-card p {
    margin: 0;
    color: #666;
    line-height: 1.5;
}

#carrey-payment-form {
    background: #fff;
    padding: 40px;
    border-radius: 12px;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    margin-top: 50px;
}

.form-row {
    margin-bottom: 30px;
}

#card-element {
    padding: 15px;
    border: 1px solid #ddd;
    border-radius: 8px;
    background: #f8f9fa;
}

#card-errors {
    color: #dc3232;
    margin-top: 10px;
}

.button-primary {
    background: #2271b1;
    border-color: #2271b1;
    color: white;
    padding: 12px 24px;
    font-size: 1.1em;
    border-radius: 6px;
    transition: all 0.3s ease;
}

.button-primary:hover {
    background: #135e96;
    border-color: #135e96;
    transform: translateY(-2px);
}

.button-secondary {
    background: #f0f0f1;
    border-color: #2271b1;
    color: #2271b1;
    padding: 10px 20px;
    border-radius: 6px;
    transition: all 0.3s ease;
}

.button-secondary:hover {
    background: #e2e4e7;
    transform: translateY(-2px);
}
</style>

<script>
jQuery(document).ready(function($) {
    var stripe = Stripe('<?php echo esc_js($payment->get_stripe_public_key()); ?>');
    var elements = stripe.elements();
    var card = elements.create('card', {
        style: {
            base: {
                fontSize: '16px',
                color: '#32325d',
                '::placeholder': {
                    color: '#aab7c4'
                }
            },
            invalid: {
                color: '#dc3232'
            }
        }
    });
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