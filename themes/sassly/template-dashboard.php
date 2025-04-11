<?php
/**
 * Template Name: User Dashboard (Self-Contained)
 * 
 * This template is used for the user's personal dashboard.
 */

// Redirect non-logged in users
if (!is_user_logged_in()) {
    wp_redirect(wp_login_url(get_permalink())); // Redirect to login page, then back here
    exit;
}

$current_user = wp_get_current_user();

?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php wp_title( '|', true, 'right' ); ?> <?php bloginfo( 'name' ); ?></title>
    <link rel="stylesheet" id="carrey-dashboard-styles" href="<?php echo get_template_directory_uri(); ?>/assets/css/dashboard-standalone.css?ver=1.0.0" type="text/css" media="all" />
    <?php 
    // Minimal wp_head for essential WP hooks/scripts
    // Avoids pulling in potentially problematic theme header functions
    wp_enqueue_script('jquery'); 
    wp_head(); 
    ?> 
</head>
<body <?php body_class('dashboard-page'); ?>>
<?php wp_body_open(); ?>

<div class="carrey-dashboard-wrap">
    <div class="carrey-container dashboard-container">
        
        <h1><?php printf(esc_html__('Welcome, %s!', 'carrey'), esc_html($current_user->display_name)); ?></h1>
        <p><?php esc_html_e('This is your personal dashboard. Manage your websites and track their performance below.', 'carrey'); ?></p>

        <div class="dashboard-content">
            
            <div class="dashboard-main">
                <h2><?php esc_html_e('Your Websites', 'carrey'); ?></h2>
                
                <!-- Form to add a new website -->
                <div class="add-website-form-wrap">
                    <h3><?php esc_html_e('Add a New Website', 'carrey'); ?></h3>
                    <form id="add-website-form" method="post">
                        <?php wp_nonce_field('carrey_add_website_nonce', 'carrey_add_website_nonce_field'); ?>
                        <div class="form-field">
                            <label for="website_url"><?php esc_html_e('Website URL:', 'carrey'); ?></label>
                            <input type="url" id="website_url" name="website_url" placeholder="https://example.com" required>
                        </div>
                        <button type="submit" class="carrey-button carrey-button-primary"><?php esc_html_e('Add Website', 'carrey'); ?></button>
                        <span class="spinner"></span> <!-- For AJAX loading indicator -->
                    </form>
                    <div id="add-website-message"></div> <!-- For feedback -->
                </div>

                <hr>

                <p><?php esc_html_e('Your added websites will be listed here.', 'carrey'); ?></p>
                <!-- Section to display the user's websites -->
                <div id="user-websites-list">
                    <!-- Websites load here (e.g., via AJAX) -->
                </div>

            </div>

            <div class="dashboard-sidebar">
                 <h3><?php esc_html_e('Account', 'carrey'); ?></h3>
                 <p><?php esc_html_e('Account details and quick links.', 'carrey'); ?></p>
                 <ul>
                    <li><a href="#"><?php esc_html_e('Manage Subscription', 'carrey'); ?></a></li>
                    <li><a href="<?php echo wp_logout_url(home_url()); ?>"><?php esc_html_e('Log Out', 'carrey'); ?></a></li>
                 </ul>
                 <!-- Other sidebar elements -->
            </div>

        </div> 

    </div>
</div>

<?php 
// Minimal wp_footer for scripts
wp_footer(); 
?> 
</body>
</html> 