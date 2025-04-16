jQuery(document).ready(function($) {
    // Initialize Stripe
    const stripe = Stripe(carreyPayment.stripePublicKey);
    const elements = stripe.elements();
    
    // Customize card element styling
    const style = {
        base: {
            color: '#32325d',
            fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
            fontSmoothing: 'antialiased',
            fontSize: '16px',
            '::placeholder': {
                color: '#aab7c4'
            }
        },
        invalid: {
            color: '#fa755a',
            iconColor: '#fa755a'
        }
    };

    const card = elements.create('card', { style: style });
    card.mount('#card-element');
    
    // Handle card input changes
    card.addEventListener('change', function(event) {
        const displayError = document.getElementById('card-errors');
        if (event.error) {
            displayError.textContent = event.error.message;
            displayError.classList.add('error');
        } else {
            displayError.textContent = '';
            displayError.classList.remove('error');
        }
    });

    // Handle plan selection
    $('.select-plan').on('click', function() {
        const planId = $(this).data('plan');
        const planName = $(this).data('plan-name');
        const planPrice = $(this).data('plan-price');
        
        // Update form with selected plan info
        $('#selected-plan-name').text(planName);
        $('#selected-plan-price').text(planPrice + ' kr/month');
        $('#selected-plan-id').val(planId);
        
        // Show payment form and scroll to it
        $('#carrey-payment-form').show();
        $('html, body').animate({
            scrollTop: $('#carrey-payment-form').offset().top - 100
        }, 500);
    });

    // Handle subscription creation
    $('#stripe-payment-form').on('submit', function(e) {
        e.preventDefault();
        
        // Disable submit button and show loading state
        const submitButton = $('#submit-payment');
        submitButton.prop('disabled', true);
        submitButton.html('<span class="spinner"></span> Processing payment...');
        
        stripe.createToken(card).then(function(result) {
            if (result.error) {
                const errorElement = document.getElementById('card-errors');
                errorElement.textContent = result.error.message;
                errorElement.classList.add('error');
                
                // Re-enable submit button
                submitButton.prop('disabled', false);
                submitButton.text('Pay now');
            } else {
                $.ajax({
                    url: carreyPayment.ajaxUrl,
                    type: 'POST',
                    data: {
                        action: 'carrey_create_subscription',
                        nonce: carreyPayment.nonce,
                        plan_id: $('#selected-plan-id').val(),
                        token: result.token.id
                    },
                    success: function(response) {
                        if (response.success) {
                            // Show success message
                            $('#payment-message').html(
                                '<div class="success-message">' +
                                '<h3>Thank you for your subscription!</h3>' +
                                '<p>Your payment has been confirmed and the subscription is now active.</p>' +
                                '</div>'
                            );
                            
                            // Reload page after 2 seconds
                            setTimeout(function() {
                                location.reload();
                            }, 2000);
                        } else {
                            $('#card-errors').text(response.data.message).addClass('error');
                            submitButton.prop('disabled', false);
                            submitButton.text('Pay now');
                        }
                    },
                    error: function() {
                        $('#card-errors').text('An error occurred. Please try again.').addClass('error');
                        submitButton.prop('disabled', false);
                        submitButton.text('Pay now');
                    }
                });
            }
        });
    });

    // Handle subscription cancellation
    $('.cancel-subscription').on('click', function() {
        if (confirm('Are you sure you want to cancel your subscription?')) {
            const cancelButton = $(this);
            cancelButton.prop('disabled', true);
            cancelButton.html('<span class="spinner"></span> Cancelling...');
            
            $.ajax({
                url: carreyPayment.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'carrey_cancel_subscription',
                    nonce: carreyPayment.nonce
                },
                success: function(response) {
                    if (response.success) {
                        location.reload();
                    } else {
                        alert(response.data.message);
                        cancelButton.prop('disabled', false);
                        cancelButton.text('Cancel subscription');
                    }
                },
                error: function() {
                    alert('An error occurred. Please try again.');
                    cancelButton.prop('disabled', false);
                    cancelButton.text('Cancel subscription');
                }
            });
        }
    });

    // Handle subscription update
    $('.update-subscription').on('click', function() {
        const newPlanId = $(this).data('plan');
        const newPlanName = $(this).data('plan-name');
        
        if (confirm(`Are you sure you want to switch to the ${newPlanName} plan?`)) {
            const updateButton = $(this);
            updateButton.prop('disabled', true);
            updateButton.html('<span class="spinner"></span> Updating...');
            
            $.ajax({
                url: carreyPayment.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'carrey_update_subscription',
                    nonce: carreyPayment.nonce,
                    plan_id: newPlanId
                },
                success: function(response) {
                    if (response.success) {
                        location.reload();
                    } else {
                        alert(response.data.message);
                        updateButton.prop('disabled', false);
                        updateButton.text('Update subscription');
                    }
                },
                error: function() {
                    alert('An error occurred. Please try again.');
                    updateButton.prop('disabled', false);
                    updateButton.text('Update subscription');
                }
            });
        }
    });
}); 