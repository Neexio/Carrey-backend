<?php
if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="wrap carrey-dashboard">
    <h1>Carrey SEO Dashboard</h1>
    
    <div class="welcome-section">
        <h2>Velkommen til Carrey SEO Dashboard</h2>
        <p>Her kan du overvåke og forbedre SEO-ytelsen til dine nettsteder.</p>
    </div>

    <div class="stats-grid">
        <div class="stat-box">
            <h3>Nettsteder</h3>
            <div class="stat-value"><?php echo esc_html($seo_stats['total_pages']); ?></div>
            <div class="stat-description">Totalt antall nettsteder</div>
        </div>
        
        <div class="stat-box">
            <h3>Optimaliserte sider</h3>
            <div class="stat-value"><?php echo esc_html($seo_stats['optimized_pages']); ?></div>
            <div class="stat-description">Sider med god SEO-score</div>
        </div>
        
        <div class="stat-box">
            <h3>Identifiserte problemer</h3>
            <div class="stat-value"><?php echo esc_html($seo_stats['issues_found']); ?></div>
            <div class="stat-description">Problemer som trenger oppmerksomhet</div>
        </div>
        
        <div class="stat-box">
            <h3>Forbedringspotensial</h3>
            <div class="stat-value"><?php echo esc_html($seo_stats['improvement_score']); ?>%</div>
            <div class="stat-description">Gjennomsnittlig forbedringspotensial</div>
        </div>
    </div>

    <div class="performance-section">
        <h2>SEO Ytelse</h2>
        <canvas id="performanceChart"></canvas>
    </div>

    <div class="issues-section">
        <h2>Identifiserte problemer</h2>
        <div class="issues-list">
            <?php foreach ($user_websites as $website): ?>
                <?php if (!empty($website['issues'])): ?>
                    <div class="issue-card">
                        <h3><?php echo esc_html($website['url']); ?></h3>
                        <ul>
                            <?php foreach ($website['issues'] as $issue): ?>
                                <li><?php echo esc_html($issue); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="recommendations-section">
        <h2>SEO Anbefalinger</h2>
        <div class="recommendations-list">
            <div class="recommendation-card">
                <h3>Forbedre sidetitler</h3>
                <p>Optimaliser sidetitlene for bedre søkemotoroptimalisering.</p>
                <a href="#" class="button button-primary">Start optimalisering</a>
            </div>
            
            <div class="recommendation-card">
                <h3>Forbedre meta-beskrivelser</h3>
                <p>Oppdater meta-beskrivelsene for å øke klikkraten.</p>
                <a href="#" class="button button-primary">Start optimalisering</a>
            </div>
            
            <div class="recommendation-card">
                <h3>Optimaliser bilder</h3>
                <p>Komprimer og optimaliser bilder for raskere lasting.</p>
                <a href="#" class="button button-primary">Start optimalisering</a>
            </div>
        </div>
    </div>
</div>

<script>
jQuery(document).ready(function($) {
    const ctx = document.getElementById('performanceChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mai', 'Jun'],
            datasets: [{
                label: 'SEO Score',
                data: [65, 70, 75, 80, 85, 90],
                borderColor: '#0073aa',
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100
                }
            }
        }
    });
});
</script> 