/**
 * Enqueue scripts and styles for the theme.
 */
function sassly_carrey_scripts() {
    // Enqueue original theme styles/scripts here if needed...
    // wp_enqueue_style( 'sassly-parent-style', get_template_directory_uri() . '/style.css' );

    // Enqueue dashboard script only on the page using the dashboard template
    if (is_page() && 'template-dashboard.php' === get_page_template_slug(get_queried_object_id())) {
        // Enqueue dashboard standalone CSS
        wp_enqueue_style('carrey-dashboard-standalone', get_template_directory_uri() . '/assets/css/dashboard-standalone.css', array(), '1.0.0');

        // Enqueue dashboard JS
        wp_enqueue_script('carrey-dashboard', get_template_directory_uri() . '/assets/js/dashboard.js', array('jquery'), '1.0.0', true);
        
        // Send data to our script
        wp_localize_script('carrey-dashboard', 'carreyDashboardAjax', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'add_nonce'    => wp_create_nonce('carrey_add_website_nonce'),
            'get_nonce'    => wp_create_nonce('carrey_get_websites_nonce') 
        ));
    }
}
add_action('wp_enqueue_scripts', 'sassly_carrey_scripts');

/**
 * AJAX handler for adding a website from the user dashboard.
 */
function carrey_ajax_add_website() {
    check_ajax_referer('carrey_add_website_nonce', 'nonce');
    if (!is_user_logged_in()) {
        wp_send_json_error(['message' => __('You must be logged in.', 'sassly')]); // Use theme text domain
        return;
    }

    if (!isset($_POST['website_url']) || empty($_POST['website_url'])){
        wp_send_json_error(['message' => __('Website URL is required.', 'sassly')]);
        return;
    }    
    $website_url = sanitize_url($_POST['website_url']);
    if (!filter_var($website_url, FILTER_VALIDATE_URL)) {
         wp_send_json_error(['message' => __('Invalid Website URL format.', 'sassly')]);
        return;
    }
    
    $user_id = get_current_user_id();
    $user_websites = get_user_meta($user_id, 'carrey_user_websites', true);
    if (!is_array($user_websites)) {
        $user_websites = [];
    }

    if (in_array($website_url, $user_websites)) {
        wp_send_json_error(['message' => __('This website has already been added.', 'sassly')]);
        return;
    }
    
    $user_websites[] = $website_url;
    $update_result = update_user_meta($user_id, 'carrey_user_websites', $user_websites);

    if ($update_result) {
        wp_send_json_success(['message' => __('Website added successfully!', 'sassly')]);
    } else {
         wp_send_json_error(['message' => __('Could not save the website. Please try again.', 'sassly')]);
    }
}
add_action('wp_ajax_carrey_add_website', 'carrey_ajax_add_website');

/**
 * AJAX handler for getting the current user's websites.
 */
function carrey_ajax_get_websites() {
    if (!is_user_logged_in()) {
        wp_send_json_error(['message' => __('You must be logged in.', 'sassly')]);
        return;
    }

    $user_id = get_current_user_id();
    $user_websites = get_user_meta($user_id, 'carrey_user_websites', true);

    if (!is_array($user_websites)) {
        $user_websites = [];
    }

    wp_send_json_success(['websites' => $user_websites]);

}
add_action('wp_ajax_carrey_get_websites', 'carrey_ajax_get_websites'); 