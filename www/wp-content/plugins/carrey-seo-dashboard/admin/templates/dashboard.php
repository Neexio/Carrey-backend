<?php
/**
 * Dashboard Template
 *
 * @package Carrey_SEO_Dashboard
 */

if (!defined('ABSPATH')) {
    exit;
}

$dashboard = Carrey_Dashboard::get_instance();
?>

<div class="wrap carrey-dashboard">
    <h1>Welcome to Carrey SEO Dashboard</h1>
    
    <div class="carrey-stats-grid">
        <div class="carrey-stat-box">
            <h3>Total Websites</h3>
            <p class="stat-number"><?php echo count($dashboard->get_user_data()); ?></p>
        </div>
        <div class="carrey-stat-box">
            <h3>Average SEO Score</h3>
            <p class="stat-number"><?php echo $dashboard->get_average_seo_score(); ?>%</p>
        </div>
        <div class="carrey-stat-box">
            <h3>Total Keywords</h3>
            <p class="stat-number"><?php echo $dashboard->get_total_keywords(); ?></p>
        </div>
        <div class="carrey-stat-box">
            <h3>Average Position</h3>
            <p class="stat-number">#<?php echo $dashboard->get_average_position(); ?></p>
        </div>
    </div>

    <div class="carrey-charts-grid">
        <div class="carrey-chart-box">
            <h3>SEO Performance</h3>
            <div class="chart-container">
                <canvas id="seo-performance-chart"></canvas>
            </div>
        </div>
        <div class="carrey-chart-box">
            <h3>Traffic Analysis</h3>
            <div class="chart-container">
                <canvas id="traffic-chart"></canvas>
            </div>
        </div>
    </div>

    <div class="carrey-issues-grid">
        <div class="carrey-issues-box">
            <h3>Identified Issues</h3>
            <div class="issues-list">
                <?php foreach ($dashboard->get_identified_issues() as $issue): ?>
                    <div class="issue-item">
                        <span class="issue-severity <?php echo $issue['severity']; ?>"></span>
                        <div class="issue-content">
                            <h4><?php echo esc_html($issue['title']); ?></h4>
                            <p><?php echo esc_html($issue['description']); ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="carrey-recommendations-box">
            <h3>Automated Recommendations</h3>
            <div class="recommendations-list">
                <?php foreach ($dashboard->get_automated_recommendations() as $recommendation): ?>
                    <div class="recommendation-card">
                        <h4><?php echo esc_html($recommendation['title']); ?></h4>
                        <p><?php echo esc_html($recommendation['description']); ?></p>
                        <button class="button button-primary automate-action" 
                                data-action="<?php echo esc_attr($recommendation['action']); ?>">
                            Start Automation
                        </button>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<script>
jQuery(document).ready(function($) {
    // Initialize performance chart
    const performanceCtx = document.getElementById('seo-performance-chart').getContext('2d');
    new Chart(performanceCtx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mai', 'Jun'],
            datasets: [{
                label: 'SEO Score',
                data: [65, 70, 75, 80, 85, 90],
                borderColor: '#2271b1',
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100
                }
            }
        }
    });

    // Initialize traffic chart
    const trafficCtx = document.getElementById('traffic-chart').getContext('2d');
    new Chart(trafficCtx, {
        type: 'bar',
        data: {
            labels: ['Organisk', 'Direkte', 'Henvisning'],
            datasets: [{
                label: 'Trafikk',
                data: [1500, 800, 300],
                backgroundColor: [
                    '#2271b1',
                    '#00a32a',
                    '#d63638'
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });

    // Handle automation actions
    $('.automate-action').on('click', function() {
        const action = $(this).data('action');
        const button = $(this);
        
        button.prop('disabled', true).text('Prosesserer...');
        
        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: {
                action: 'carrey_automate_seo',
                nonce: '<?php echo wp_create_nonce('carrey_seo_nonce'); ?>',
                action_type: action
            },
            success: function(response) {
                if (response.success) {
                    button.text('Fullført!').addClass('button-secondary');
                    setTimeout(() => {
                        button.prop('disabled', false).text('Start automatisering').removeClass('button-secondary');
                    }, 3000);
                }
            },
            error: function() {
                button.prop('disabled', false).text('Start automatisering');
                alert('Det oppstod en feil under automatiseringen.');
            }
        });
    });
});
</script> 