<?php
/**
 * Admin page template
 */
if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="wrap">
    <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
    
    <div class="carrey-user-panel-admin">
        <div class="carrey-admin-header">
            <img src="<?php echo esc_url(CARREY_USER_PANEL_URL . 'assets/images/carrey-logo.png'); ?>" alt="Carrey Logo" class="carrey-logo">
            <h2>User Panel Settings</h2>
        </div>

        <div class="carrey-admin-content">
            <form method="post" action="options.php">
                <?php
                settings_fields('carrey_user_panel_options');
                do_settings_sections('carrey-user-panel');
                submit_button('Save Settings');
                ?>
            </form>

            <div class="carrey-admin-stats">
                <h3>Panel Statistics</h3>
                <div class="stats-grid">
                    <div class="stat-box">
                        <span class="stat-number"><?php echo esc_html($this->get_active_users_count()); ?></span>
                        <span class="stat-label">Active Users</span>
                    </div>
                    <div class="stat-box">
                        <span class="stat-number"><?php echo esc_html($this->get_total_actions_count()); ?></span>
                        <span class="stat-label">Total Actions</span>
                    </div>
                    <div class="stat-box">
                        <span class="stat-number"><?php echo esc_html($this->get_average_usage_time()); ?></span>
                        <span class="stat-label">Avg. Usage Time</span>
                    </div>
                </div>
            </div>

            <div class="carrey-admin-features">
                <h3>Available Features</h3>
                <div class="features-grid">
                    <div class="feature-box">
                        <h4>SEO Analysis</h4>
                        <p>Analyze and improve website SEO</p>
                        <button class="button button-primary">Configure</button>
                    </div>
                    <div class="feature-box">
                        <h4>Content Generation</h4>
                        <p>Generate optimized content</p>
                        <button class="button button-primary">Configure</button>
                    </div>
                    <div class="feature-box">
                        <h4>Performance Monitoring</h4>
                        <p>Track website performance</p>
                        <button class="button button-primary">Configure</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.carrey-user-panel-admin {
    max-width: 1200px;
    margin: 20px 0;
}

.carrey-admin-header {
    display: flex;
    align-items: center;
    margin-bottom: 30px;
}

.carrey-logo {
    height: 40px;
    margin-right: 15px;
}

.stats-grid, .features-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    margin: 20px 0;
}

.stat-box, .feature-box {
    background: #fff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.stat-number {
    font-size: 24px;
    font-weight: bold;
    color: #00A3FF;
    display: block;
}

.stat-label {
    color: #666;
    font-size: 14px;
}

.feature-box h4 {
    margin: 0 0 10px;
    color: #333;
}

.feature-box p {
    margin: 0 0 15px;
    color: #666;
}

.button-primary {
    background: #00A3FF;
    border-color: #00A3FF;
}

.button-primary:hover {
    background: #0093E6;
    border-color: #0093E6;
}
</style> 