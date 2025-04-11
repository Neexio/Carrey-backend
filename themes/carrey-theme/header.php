<?php
/**
 * The header for our theme
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<header class="carrey-header">
    <div class="carrey-container">
        <div class="carrey-logo">
            <?php if (has_custom_logo()): ?>
                <?php the_custom_logo(); ?>
            <?php else: ?>
                <a href="<?php echo esc_url(home_url('/')); ?>">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/logo.png" alt="<?php bloginfo('name'); ?>">
                </a>
            <?php endif; ?>
        </div>

        <div class="carrey-header-actions">
            <a href="<?php echo esc_url(home_url('/login')); ?>" class="carrey-button carrey-button-login">Logg inn</a>
            <a href="<?php echo esc_url(home_url('/pricing')); ?>" class="carrey-button carrey-button-primary">Start nå</a>
        </div>
    </div>
</header>

<main class="carrey-main"> 