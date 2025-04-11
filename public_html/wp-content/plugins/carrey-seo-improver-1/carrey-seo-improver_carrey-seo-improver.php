<?php
/**
 * Plugin Name: Carrey SEO Improver
 * Plugin URI: https://carrey.no
 * Description: Viser kunder hvordan deres nettside kan forbedres med våre SEO-tjenester
 * Version: 1.0.0
 * Author: Carrey
 * Author URI: https://carrey.no
 * Text Domain: carrey-seo-improver
 * Domain Path: /languages
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

function carrey_seo_improver_shortcode() {
    ob_start();
    ?>
    <div class="carrey-seo-improver">
        <div class="seo-header">
            <h2>SEO Potensial Analyse</h2>
            <p>Se hvordan vi kan forbedre din nettside</p>
        </div>
        
        <div class="input-section">
            <input type="text" id="website-url" placeholder="Skriv inn nettstedets URL" />
            <button id="analyze-btn">Analyser Nettsted</button>
        </div>

        <div class="loading" style="display: none;">
            <div class="spinner"></div>
            <p>Analyserer nettstedet...</p>
        </div>

        <div class="results-section" style="display: none;">
            <div class="current-score">
                <h3>Din Nåværende SEO-Score</h3>
                <div class="score-circle">
                    <span class="score">0</span>
                    <span class="score-label">/100</span>
                </div>
            </div>

            <div class="improvement-potential">
                <h3>Potensielle Forbedringer</h3>
                <div class="improvement-list">
                    <!-- Vil bli fylt dynamisk -->
                </div>
            </div>

            <div class="benefits-section">
                <h3>Hva Du Får Med Våre Tjenester</h3>
                <div class="benefits-grid">
                    <div class="benefit-card">
                        <div class="benefit-icon">📈</div>
                        <h4>Økt Trafikk</h4>
                        <p>Potensiell økning: <span class="traffic-increase">0%</span></p>
                    </div>
                    <div class="benefit-card">
                        <div class="benefit-icon">🔍</div>
                        <h4>Bedre Søkeresultater</h4>
                        <p>Forbedret ranking for <span class="keyword-count">0</span> nøkkelord</p>
                    </div>
                    <div class="benefit-card">
                        <div class="benefit-icon">💰</div>
                        <h4>Høyere Konvertering</h4>
                        <p>Estimert økning: <span class="conversion-increase">0%</span></p>
                    </div>
                </div>
            </div>

            <div class="cta-section">
                <button class="cta-button">Start SEO-Optimering</button>
                <p class="cta-text">La oss hjelpe deg med å nå dine mål</p>
            </div>
        </div>
    </div>

    <style>
    .carrey-seo-improver {
        max-width: 800px;
        margin: 0 auto;
        padding: 20px;
        font-family: Arial, sans-serif;
    }

    .seo-header {
        text-align: center;
        margin-bottom: 30px;
    }

    .seo-header h2 {
        color: #2c3e50;
        font-size: 28px;
        margin-bottom: 10px;
    }

    .input-section {
        display: flex;
        gap: 10px;
        margin-bottom: 30px;
    }

    #website-url {
        flex: 1;
        padding: 12px;
        border: 2px solid #e0e0e0;
        border-radius: 6px;
        font-size: 16px;
    }

    #analyze-btn {
        padding: 12px 24px;
        background-color: #3498db;
        color: white;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-size: 16px;
        transition: background-color 0.3s;
    }

    #analyze-btn:hover {
        background-color: #2980b9;
    }

    .loading {
        text-align: center;
        padding: 20px;
    }

    .spinner {
        border: 4px solid #f3f3f3;
        border-top: 4px solid #3498db;
        border-radius: 50%;
        width: 40px;
        height: 40px;
        animation: spin 1s linear infinite;
        margin: 0 auto 10px;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    .current-score {
        text-align: center;
        margin-bottom: 30px;
    }

    .score-circle {
        width: 150px;
        height: 150px;
        border-radius: 50%;
        background: #f8f9fa;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        margin: 20px auto;
        border: 5px solid #e0e0e0;
    }

    .score {
        font-size: 48px;
        font-weight: bold;
        color: #2c3e50;
    }

    .score-label {
        font-size: 18px;
        color: #7f8c8d;
    }

    .improvement-list {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 8px;
        margin-bottom: 30px;
    }

    .improvement-item {
        display: flex;
        align-items: center;
        padding: 15px;
        border-bottom: 1px solid #e0e0e0;
    }

    .improvement-item:last-child {
        border-bottom: none;
    }

    .improvement-icon {
        margin-right: 15px;
        font-size: 24px;
    }

    .improvement-content {
        flex: 1;
    }

    .improvement-title {
        font-weight: bold;
        margin-bottom: 5px;
    }

    .improvement-description {
        color: #666;
        font-size: 14px;
    }

    .benefits-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin: 30px 0;
    }

    .benefit-card {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 8px;
        text-align: center;
    }

    .benefit-icon {
        font-size: 32px;
        margin-bottom: 15px;
    }

    .benefit-card h4 {
        margin: 10px 0;
        color: #2c3e50;
    }

    .cta-section {
        text-align: center;
        margin-top: 40px;
    }

    .cta-button {
        padding: 15px 30px;
        background-color: #2ecc71;
        color: white;
        border: none;
        border-radius: 6px;
        font-size: 18px;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    .cta-button:hover {
        background-color: #27ae60;
    }

    .cta-text {
        margin-top: 10px;
        color: #666;
    }
    </style>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const analyzeBtn = document.getElementById('analyze-btn');
        const websiteUrl = document.getElementById('website-url');
        const loading = document.querySelector('.loading');
        const results = document.querySelector('.results-section');
        const scoreElement = document.querySelector('.score');
        const improvementList = document.querySelector('.improvement-list');
        const trafficIncrease = document.querySelector('.traffic-increase');
        const keywordCount = document.querySelector('.keyword-count');
        const conversionIncrease = document.querySelector('.conversion-increase');

        function analyzeWebsite() {
            const url = websiteUrl.value.trim();
            if (!url) {
                alert('Vennligst skriv inn en gyldig URL');
                return;
            }

            loading.style.display = 'block';
            results.style.display = 'none';

            // Simulerer analyse (i virkeligheten ville dette vært en API-kall)
            setTimeout(() => {
                loading.style.display = 'none';
                results.style.display = 'block';

                // Simulerte resultater
                const currentScore = Math.floor(Math.random() * 40) + 30;
                scoreElement.textContent = currentScore;

                // Oppdater forbedringspotensial
                improvementList.innerHTML = `
                    <div class="improvement-item">
                        <div class="improvement-icon">🔍</div>
                        <div class="improvement-content">
                            <div class="improvement-title">Meta Beskrivelser</div>
                            <div class="improvement-description">Forbedre meta-beskrivelser for bedre klikk-rate i søkeresultater</div>
                        </div>
                    </div>
                    <div class="improvement-item">
                        <div class="improvement-icon">📱</div>
                        <div class="improvement-content">
                            <div class="improvement-title">Mobil Optimalisering</div>
                            <div class="improvement-description">Forbedre brukeropplevelsen på mobile enheter</div>
                        </div>
                    </div>
                    <div class="improvement-item">
                        <div class="improvement-icon">⚡</div>
                        <div class="improvement-content">
                            <div class="improvement-title">Lastehastighet</div>
                            <div class="improvement-description">Optimaliser bilder og kode for raskere lasting</div>
                        </div>
                    </div>
                `;

                // Oppdater forventede forbedringer
                trafficIncrease.textContent = '45%';
                keywordCount.textContent = '25';
                conversionIncrease.textContent = '30%';
            }, 2000);
        }

        analyzeBtn.addEventListener('click', analyzeWebsite);
        websiteUrl.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                analyzeWebsite();
            }
        });
    });
    </script>
    <?php
    return ob_get_clean();
}

add_shortcode('carrey_seo_improver', 'carrey_seo_improver_shortcode'); 