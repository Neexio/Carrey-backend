<?php
/**
 * User Panel Template
 */
if (!defined('ABSPATH')) {
    exit;
}

$user_id = get_current_user_id();
$user = get_userdata($user_id);
?>

<div class="carrey-user-panel">
    <div class="carrey-panel-header">
        <h1>Velkommen til Carrey User Panel</h1>
        <p class="user-info">Innlogget som: <?php echo esc_html($user->display_name); ?></p>
    </div>

    <div class="carrey-panel-content">
        <div class="features-grid">
            <div class="feature-box">
                <h3>SEO Analyse</h3>
                <p>Analyser og forbedre nettstedets SEO</p>
                <button class="carrey-button" onclick="startSEOAnalysis()">Start Analyse</button>
            </div>

            <div class="feature-box">
                <h3>Innhold Generator</h3>
                <p>Generer optimalisert innhold</p>
                <button class="carrey-button" onclick="startContentGeneration()">Generer Innhold</button>
            </div>

            <div class="feature-box">
                <h3>Ytelsesovervåking</h3>
                <p>Overvåk nettstedets ytelse</p>
                <button class="carrey-button" onclick="viewPerformance()">Se Statistikk</button>
            </div>
        </div>

        <div class="user-stats">
            <h2>Din Aktivitet</h2>
            <div class="stats-grid">
                <div class="stat-box">
                    <span class="stat-number" id="total-analyses">0</span>
                    <span class="stat-label">Totale Analyser</span>
                </div>
                <div class="stat-box">
                    <span class="stat-number" id="content-generated">0</span>
                    <span class="stat-label">Generert Innhold</span>
                </div>
                <div class="stat-box">
                    <span class="stat-number" id="performance-score">0</span>
                    <span class="stat-label">Ytelsespoeng</span>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.carrey-user-panel {
    max-width: 1200px;
    margin: 40px auto;
    padding: 20px;
}

.carrey-panel-header {
    text-align: center;
    margin-bottom: 40px;
}

.user-info {
    color: #666;
    font-size: 16px;
}

.features-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 30px;
    margin-bottom: 40px;
}

.feature-box {
    background: #fff;
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    text-align: center;
    transition: transform 0.3s ease;
}

.feature-box:hover {
    transform: translateY(-5px);
}

.feature-box h3 {
    color: #00A3FF;
    margin-bottom: 15px;
}

.feature-box p {
    color: #666;
    margin-bottom: 20px;
}

.carrey-button {
    background: #00A3FF;
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 5px;
    cursor: pointer;
    transition: background 0.3s ease;
}

.carrey-button:hover {
    background: #0093E6;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
}

.stat-box {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 8px;
    text-align: center;
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
</style>

<script>
jQuery(document).ready(function($) {
    // Load user stats
    $.ajax({
        url: carreyUserPanel.apiUrl + '/stats',
        method: 'GET',
        headers: {
            'X-WP-Nonce': carreyUserPanel.nonce
        },
        success: function(response) {
            $('#total-analyses').text(response.total_analyses);
            $('#content-generated').text(response.content_generated);
            $('#performance-score').text(response.performance_score);
        }
    });
});

function startSEOAnalysis() {
    // Implement SEO analysis functionality
    alert('SEO Analyse starter...');
}

function startContentGeneration() {
    // Implement content generation functionality
    alert('Innholdsgenerering starter...');
}

function viewPerformance() {
    // Implement performance view functionality
    alert('Viser ytelsesstatistikk...');
}
</script> 