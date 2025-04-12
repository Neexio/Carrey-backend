<?php
if (!defined('ABSPATH')) {
    exit;
}

$seo = Carrey_SEO::get_instance();
$test_sites = $seo->get_test_sites();
$test_mode = $seo->is_test_mode_enabled();
?>

<div class="wrap">
    <h1>Carrey SEO Test Environment</h1>
    
    <div class="card">
        <h2>Test Mode Status</h2>
        <p>Current status: <strong><?php echo $test_mode ? 'Enabled' : 'Disabled'; ?></strong></p>
        <form method="post" action="">
            <?php wp_nonce_field('carrey_test_mode_toggle', 'carrey_test_mode_nonce'); ?>
            <input type="hidden" name="action" value="toggle_test_mode">
            <button type="submit" class="button button-primary">
                <?php echo $test_mode ? 'Disable Test Mode' : 'Enable Test Mode'; ?>
            </button>
        </form>
    </div>

    <div class="card">
        <h2>Test Sites</h2>
        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th>URL</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($test_sites as $site): ?>
                <tr>
                    <td><?php echo esc_url($site); ?></td>
                    <td>
                        <form method="post" action="" style="display: inline;">
                            <?php wp_nonce_field('carrey_remove_test_site', 'carrey_test_site_nonce'); ?>
                            <input type="hidden" name="action" value="remove_test_site">
                            <input type="hidden" name="site_url" value="<?php echo esc_attr($site); ?>">
                            <button type="submit" class="button button-small">Remove</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <h3>Add New Test Site</h3>
        <form method="post" action="">
            <?php wp_nonce_field('carrey_add_test_site', 'carrey_add_site_nonce'); ?>
            <input type="hidden" name="action" value="add_test_site">
            <input type="url" name="site_url" placeholder="https://example.com" required>
            <button type="submit" class="button button-primary">Add Site</button>
        </form>
    </div>

    <div class="card">
        <h2>Test Analysis</h2>
        <form method="post" action="">
            <?php wp_nonce_field('carrey_test_analysis', 'carrey_analysis_nonce'); ?>
            <input type="hidden" name="action" value="test_analysis">
            <input type="url" name="analysis_url" placeholder="https://example.com" required>
            <button type="submit" class="button button-primary">Run Analysis</button>
        </form>
    </div>
</div>

<style>
.card {
    background: #fff;
    border: 1px solid #ccd0d4;
    box-shadow: 0 1px 1px rgba(0,0,0,.04);
    margin: 20px 0;
    padding: 20px;
}
.card h2 {
    margin-top: 0;
}
</style> 