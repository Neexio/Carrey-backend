<?php
/**
 * Frontend template for the user panel
 */
if (!defined('ABSPATH')) {
    exit;
}

$current_user = wp_get_current_user();
?>

<div class="carrey-user-panel">
    <div class="carrey-panel-header">
        <div class="user-info">
            <img src="<?php echo get_avatar_url($current_user->ID); ?>" alt="<?php echo esc_attr($current_user->display_name); ?>" class="user-avatar">
            <div class="user-details">
                <h2><?php echo esc_html($current_user->display_name); ?></h2>
                <span class="user-role"><?php echo esc_html(ucfirst($current_user->roles[0])); ?></span>
            </div>
        </div>
        <div class="panel-actions">
            <button class="refresh-btn">Oppdater data</button>
            <button class="settings-btn">Innstillinger</button>
        </div>
    </div>

    <div class="carrey-panel-content">
        <div class="panel-section">
            <h3>SEO Analyse</h3>
            <div class="seo-analysis">
                <div class="analysis-input">
                    <input type="text" id="url-input" placeholder="Skriv inn URL for analyse">
                    <button class="analyze-btn">Analyser</button>
                </div>
                <div class="analysis-results" style="display: none;">
                    <!-- Resultater vil bli lagt til dynamisk -->
                </div>
            </div>
        </div>

        <div class="panel-section">
            <h3>Innholdsgenerering</h3>
            <div class="content-generation">
                <textarea id="content-prompt" placeholder="Beskriv innholdet du ønsker å generere"></textarea>
                <div class="generation-options">
                    <select id="content-type">
                        <option value="article">Artikkel</option>
                        <option value="blog">Blogginnlegg</option>
                        <option value="product">Produktbeskrivelse</option>
                    </select>
                    <button class="generate-btn">Generer</button>
                </div>
                <div class="generated-content" style="display: none;">
                    <!-- Generert innhold vil bli lagt til her -->
                </div>
            </div>
        </div>

        <div class="panel-section">
            <h3>Ytelsesovervåking</h3>
            <div class="performance-monitoring">
                <div class="performance-stats">
                    <div class="stat-card">
                        <span class="stat-label">Sidetid</span>
                        <span class="stat-value">2.3s</span>
                    </div>
                    <div class="stat-card">
                        <span class="stat-label">Mobil Score</span>
                        <span class="stat-value">89</span>
                    </div>
                    <div class="stat-card">
                        <span class="stat-label">Desktop Score</span>
                        <span class="stat-value">95</span>
                    </div>
                </div>
                <div class="performance-chart">
                    <!-- Graf vil bli lagt til her -->
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.carrey-user-panel {
    max-width: 1200px;
    margin: 20px auto;
    background: #fff;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    overflow: hidden;
}

.carrey-panel-header {
    background: #f8f9fa;
    padding: 20px;
    border-bottom: 1px solid #e9ecef;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.user-info {
    display: flex;
    align-items: center;
    gap: 15px;
}

.user-avatar {
    width: 50px;
    height: 50px;
    border-radius: 50%;
}

.user-details h2 {
    margin: 0;
    font-size: 1.2em;
    color: #333;
}

.user-role {
    color: #6c757d;
    font-size: 0.9em;
}

.panel-actions {
    display: flex;
    gap: 10px;
}

.panel-actions button {
    padding: 8px 15px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-weight: 500;
}

.refresh-btn {
    background: #e9ecef;
    color: #495057;
}

.settings-btn {
    background: #007bff;
    color: white;
}

.carrey-panel-content {
    padding: 20px;
}

.panel-section {
    margin-bottom: 30px;
}

.panel-section h3 {
    margin: 0 0 15px;
    color: #333;
}

.analysis-input {
    display: flex;
    gap: 10px;
    margin-bottom: 15px;
}

#url-input {
    flex: 1;
    padding: 10px;
    border: 1px solid #ced4da;
    border-radius: 5px;
}

.analyze-btn {
    background: #28a745;
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 5px;
    cursor: pointer;
}

.content-generation textarea {
    width: 100%;
    min-height: 100px;
    padding: 10px;
    border: 1px solid #ced4da;
    border-radius: 5px;
    margin-bottom: 10px;
}

.generation-options {
    display: flex;
    gap: 10px;
    margin-bottom: 15px;
}

#content-type {
    padding: 8px;
    border: 1px solid #ced4da;
    border-radius: 5px;
}

.generate-btn {
    background: #17a2b8;
    color: white;
    border: none;
    padding: 8px 20px;
    border-radius: 5px;
    cursor: pointer;
}

.performance-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 15px;
    margin-bottom: 20px;
}

.stat-card {
    background: #f8f9fa;
    padding: 15px;
    border-radius: 8px;
    text-align: center;
}

.stat-label {
    display: block;
    color: #6c757d;
    font-size: 0.9em;
    margin-bottom: 5px;
}

.stat-value {
    display: block;
    font-size: 1.5em;
    font-weight: bold;
    color: #333;
}

/* Responsive design */
@media (max-width: 768px) {
    .carrey-panel-header {
        flex-direction: column;
        text-align: center;
    }

    .user-info {
        margin-bottom: 15px;
    }

    .panel-actions {
        width: 100%;
        justify-content: center;
    }

    .analysis-input {
        flex-direction: column;
    }

    .generation-options {
        flex-direction: column;
    }

    #content-type {
        width: 100%;
    }
}
</style>

<script>
jQuery(document).ready(function($) {
    // Refresh button handler
    $('.refresh-btn').on('click', function() {
        // Implementer oppdatering av data
    });

    // Settings button handler
    $('.settings-btn').on('click', function() {
        // Implementer innstillinger-modal
    });

    // Analyze button handler
    $('.analyze-btn').on('click', function() {
        var url = $('#url-input').val();
        if (!url) return;

        // Vis lastindikator
        $(this).prop('disabled', true).text('Analyserer...');

        // Implementer SEO-analyse
        setTimeout(() => {
            $('.analysis-results').show();
            $(this).prop('disabled', false).text('Analyser');
        }, 2000);
    });

    // Generate content handler
    $('.generate-btn').on('click', function() {
        var prompt = $('#content-prompt').val();
        var type = $('#content-type').val();
        if (!prompt) return;

        // Vis lastindikator
        $(this).prop('disabled', true).text('Genererer...');

        // Implementer innholdsgenerering
        setTimeout(() => {
            $('.generated-content').show();
            $(this).prop('disabled', false).text('Generer');
        }, 2000);
    });
});
</script> 