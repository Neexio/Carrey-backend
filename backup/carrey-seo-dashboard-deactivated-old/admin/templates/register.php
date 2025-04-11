<?php
if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="wrap">
    <h1>Register for Carrey SEO</h1>
    
    <div class="carrey-register-form">
        <form id="carrey-register-form" method="post">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required>
            </div>
            
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            
            <div class="form-group">
                <label for="confirm_password">Confirm Password</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
            </div>
            
            <div class="form-group">
                <button type="submit" class="button button-primary">Register</button>
            </div>
        </form>
    </div>
</div>

<style>
.carrey-register-form {
    max-width: 500px;
    margin: 20px 0;
    padding: 20px;
    background: #fff;
    border-radius: 5px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    margin-bottom: 5px;
    font-weight: 600;
}

.form-group input {
    width: 100%;
    padding: 8px;
    border: 1px solid #ddd;
    border-radius: 4px;
}

.form-group button {
    width: 100%;
    padding: 10px;
    background: #0073aa;
    color: #fff;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

.form-group button:hover {
    background: #005177;
}
</style>

<script>
jQuery(document).ready(function($) {
    $('#carrey-register-form').on('submit', function(e) {
        e.preventDefault();
        
        const form = $(this);
        const submitButton = form.find('button[type="submit"]');
        
        // Validate passwords match
        if ($('#password').val() !== $('#confirm_password').val()) {
            alert('Passwords do not match');
            return;
        }
        
        submitButton.prop('disabled', true);
        submitButton.html('<span class="spinner"></span> Registering...');
        
        $.ajax({
            url: carreyPayment.ajaxUrl,
            type: 'POST',
            data: {
                action: 'carrey_register_user',
                nonce: carreyPayment.nonce,
                username: $('#username').val(),
                email: $('#email').val(),
                password: $('#password').val()
            },
            success: function(response) {
                if (response.success) {
                    window.location.href = '<?php echo admin_url('admin.php?page=carrey-subscription'); ?>';
                } else {
                    alert(response.data.message);
                    submitButton.prop('disabled', false);
                    submitButton.text('Register');
                }
            },
            error: function() {
                alert('An error occurred. Please try again.');
                submitButton.prop('disabled', false);
                submitButton.text('Register');
            }
        });
    });
});
</script> 