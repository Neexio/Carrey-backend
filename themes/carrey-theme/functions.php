<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Enqueue theme styles and scripts
function carrey_theme_scripts() {
    wp_enqueue_style('carrey-theme-style', get_stylesheet_uri());
    wp_enqueue_style('carrey-main-style', get_template_directory_uri() . '/assets/css/main.css', array(), '1.0.0');
    
    // Enqueue dashboard script only on the page using the dashboard template
    // Use get_page_template_slug() for better reliability
    if (is_page() && 'template-dashboard.php' === get_page_template_slug(get_queried_object_id())) {
        wp_enqueue_script('carrey-dashboard', get_template_directory_uri() . '/assets/js/dashboard.js', array('jquery'), '1.0.0', true);
        
        // Send data to our script
        wp_localize_script('carrey-dashboard', 'carreyDashboardAjax', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            // Generate nonce here specifically for the dashboard actions
            'add_nonce'    => wp_create_nonce('carrey_add_website_nonce'),
            'get_nonce'    => wp_create_nonce('carrey_get_websites_nonce') // New nonce for getting list 
        ));
    }
}
add_action('wp_enqueue_scripts', 'carrey_theme_scripts');

// Register navigation menus
function carrey_register_menus() {
    register_nav_menus(array(
        'primary' => __('Primary Menu', 'carrey'),
        'footer' => __('Footer Menu', 'carrey'),
    ));
}
add_action('init', 'carrey_register_menus');

// Register custom template
function carrey_register_template() {
    $post_type_object = get_post_type_object('page');
    $post_type_object->template = array(
        array('carrey/seo-analysis-section')
    );
}
add_action('init', 'carrey_register_template');

// Add theme support
function carrey_theme_support() {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('custom-logo', array(
        'height'      => 60,
        'width'       => 200,
        'flex-height' => true,
        'flex-width'  => true,
    ));
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
    ));
}
add_action('after_setup_theme', 'carrey_theme_support');

/**
 * Register Carrey CRM Admin Menu.
 */
function carrey_crm_admin_menu() {
    add_menu_page(
        __('Carrey CRM', 'carrey'),           // Page title
        __('Carrey CRM', 'carrey'),           // Menu title
        'manage_options',                     // Capability required
        'carrey-crm',                         // Menu slug
        'carrey_crm_page_content',            // Callback function to display content
        'dashicons-analytics',                // Icon URL or dashicon class
        25                                    // Position in menu
    );
}
add_action('admin_menu', 'carrey_crm_admin_menu');

/**
 * Display the content for the Carrey CRM admin page.
 */
function carrey_crm_page_content() {
    // Check user capability
    if (!current_user_can('manage_options')) {
        wp_die(__('You do not have sufficient permissions to access this page.', 'carrey'));
    }

    // Start CRM page output
    echo '<div class="wrap carrey-crm-admin-page">';
    echo '<h1>' . esc_html__('Carrey CRM Dashboard', 'carrey') . '</h1>';

    // --- Main CRM Structure --- 
    echo '<div class="carrey-crm-layout">';
    
    // Main Content Area
    echo '<div class="crm-main-content">';
    echo '<h2>' . esc_html__('Recent Analyses', 'carrey') . '</h2>';
    
    // Placeholder Table for Analyses
    echo '<table class="wp-list-table widefat fixed striped pages">';
    echo '<thead>';
    echo '<tr>';
    echo '<th scope="col" class="manage-column">' . esc_html__('Website URL', 'carrey') . '</th>';
    echo '<th scope="col" class="manage-column">' . esc_html__('Date Analyzed', 'carrey') . '</th>';
    echo '<th scope="col" class="manage-column">' . esc_html__('SEO Score', 'carrey') . '</th>';
    echo '<th scope="col" class="manage-column">' . esc_html__('Actions', 'carrey') . '</th>';
    echo '</tr>';
    echo '</thead>';
    
    echo '<tbody id="the-list">';
    // Table rows with actual data will be added here later dynamically
    echo '<tr class="no-items"><td class="colspanchange" colspan="4">' . esc_html__('No analyses found.', 'carrey') . '</td></tr>';
    echo '</tbody>';
    
    echo '<tfoot>';
    echo '<tr>';
    echo '<th scope="col" class="manage-column">' . esc_html__('Website URL', 'carrey') . '</th>';
    echo '<th scope="col" class="manage-column">' . esc_html__('Date Analyzed', 'carrey') . '</th>';
    echo '<th scope="col" class="manage-column">' . esc_html__('SEO Score', 'carrey') . '</th>';
    echo '<th scope="col" class="manage-column">' . esc_html__('Actions', 'carrey') . '</th>';
    echo '</tr>';
    echo '</tfoot>';
    
    echo '</table>';

    echo '</div>'; // end .crm-main-content
    
    // Sidebar Area (Optional)
    echo '<div class="crm-sidebar">';
    echo '<h3>' . esc_html__('Quick Links / Filters', 'carrey') . '</h3>';
    echo '<p>' . esc_html__('Filters, actions, or quick links can go here.', 'carrey') . '</p>';
    // Placeholder for future filter options or links
    echo '</div>'; // end .crm-sidebar
    
    echo '</div>'; // end .carrey-crm-layout
    
    echo '</div>'; // end .wrap
}

/**
 * Enqueue admin styles for Carrey CRM.
 */
function carrey_crm_admin_styles($hook) {
    // Only load on our specific admin page
    if ('toplevel_page_carrey-crm' !== $hook) {
        return;
    }
    wp_enqueue_style('carrey-crm-admin-style', get_template_directory_uri() . '/assets/css/admin-crm.css', array(), '1.0.0');
}
add_action('admin_enqueue_scripts', 'carrey_crm_admin_styles');

/**
 * AJAX handler for adding a website from the user dashboard.
 */
function carrey_ajax_add_website() {
    check_ajax_referer('carrey_add_website_nonce', 'nonce');
    if (!is_user_logged_in()) {
        wp_send_json_error(['message' => __('You must be logged in.', 'carrey')]);
        return;
    }

    // 2. Get and validate URL
    if (!isset($_POST['website_url']) || empty($_POST['website_url'])){
        wp_send_json_error(['message' => __('Website URL is required.', 'carrey')]);
        return;
    }    
    $website_url = sanitize_url($_POST['website_url']);
    if (!filter_var($website_url, FILTER_VALIDATE_URL)) {
         wp_send_json_error(['message' => __('Invalid Website URL format.', 'carrey')]);
        return;
    }
    
    // 3. Logic to save the website (IMPORTANT: Implementation needed!)
    $user_id = get_current_user_id();
    // Example: Save the URL as user metadata
    // Get existing websites to avoid duplicates
    $user_websites = get_user_meta($user_id, 'carrey_user_websites', true);
    if (!is_array($user_websites)) {
        $user_websites = [];
    }

    // Check if the website is already added
    if (in_array($website_url, $user_websites)) {
        wp_send_json_error(['message' => __('This website has already been added.', 'carrey')]);
        return;
    }
    
    // Add the new website
    $user_websites[] = $website_url;
    
    // Save the updated list
    $update_result = update_user_meta($user_id, 'carrey_user_websites', $user_websites);

    // 4. Send response back to the frontend
    if ($update_result) {
        wp_send_json_success(['message' => __('Website added successfully!', 'carrey')]);
    } else {
         wp_send_json_error(['message' => __('Could not save the website. Please try again.', 'carrey')]);
    }
}
add_action('wp_ajax_carrey_add_website', 'carrey_ajax_add_website');

/**
 * AJAX handler for getting the current user's websites.
 */
function carrey_ajax_get_websites() {
    // No nonce check needed for simple GET in this case, but could add one:
    // check_ajax_referer('carrey_get_websites_nonce', 'nonce'); 
    if (!is_user_logged_in()) {
        wp_send_json_error(['message' => __('You must be logged in.', 'carrey')]);
        return;
    }

    // 2. Get User ID and Websites
    $user_id = get_current_user_id();
    $user_websites = get_user_meta($user_id, 'carrey_user_websites', true);

    // Ensure it's an array
    if (!is_array($user_websites)) {
        $user_websites = [];
    }

    // 3. Send the list back
    wp_send_json_success(['websites' => $user_websites]);

}
add_action('wp_ajax_carrey_get_websites', 'carrey_ajax_get_websites'); 