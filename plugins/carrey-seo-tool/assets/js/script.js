jQuery(document).ready(function($) {
    const seoTool = {
        init: function() {
            this.bindEvents();
            this.initializeCharts();
        },

        bindEvents: function() {
            $('#analyze-btn').on('click', this.handleAnalysis.bind(this));
            $('.action-button').on('click', this.handleQuickAction.bind(this));
            this.initializeTooltips();
        },

        handleAnalysis: function(e) {
            e.preventDefault();
            const url = $('#website-url').val();
            
            if (!url) {
                this.showError(carreySeoAjax.messages.urlRequired);
                return;
            }

            this.showLoading();

            $.ajax({
                url: carreySeoAjax.ajaxurl,
                type: 'POST',
                data: {
                    action: 'carrey_seo_analyze',
                    nonce: carreySeoAjax.nonce,
                    url: url
                },
                success: this.handleAnalysisSuccess.bind(this),
                error: this.handleAnalysisError.bind(this)
            });
        },

        handleAnalysisSuccess: function(response) {
            this.hideLoading();
            
            if (!response.success) {
                this.showError(response.data.message);
                return;
            }

            const data = response.data;
            this.updateResults(data);
            this.updateCharts(data);
            this.showRecommendations(data.recommendations);
        },

        handleAnalysisError: function(xhr, status, error) {
            this.hideLoading();
            this.showError(carreySeoAjax.messages.error);
        },

        updateResults: function(data) {
            const $results = $('#results');
            $results.html('').show();

            // Overall score
            $results.append(`
                <div class="seo-score">
                    <div class="score-circle">
                        <div class="score-value">${Math.round(data.scores.overall)}</div>
                        <div class="score-label">SEO Score</div>
                    </div>
                </div>
            `);

            // Detailed metrics
            const metrics = [
                { key: 'title', label: 'Title' },
                { key: 'meta_description', label: 'Meta Description' },
                { key: 'headings', label: 'Headings' },
                { key: 'images', label: 'Images' },
                { key: 'links', label: 'Links' },
                { key: 'technical', label: 'Technical' }
            ];

            const $details = $('<div class="seo-details"></div>');
            metrics.forEach(metric => {
                const score = data.scores[metric.key];
                const className = score >= 80 ? 'good' : 'bad';
                
                $details.append(`
                    <div class="seo-item ${className}">
                        <h3>${metric.label}</h3>
                        <div class="score">${Math.round(score)}/100</div>
                    </div>
                `);
            });

            $results.append($details);
        },

        showRecommendations: function(recommendations) {
            if (!recommendations || !recommendations.length) {
                return;
            }

            const $recommendations = $('<div class="recommendations"></div>');
            $recommendations.append('<h2>Recommendations</h2>');

            recommendations.forEach(rec => {
                $recommendations.append(`
                    <div class="recommendation-item ${rec.severity}">
                        <div class="recommendation-icon"></div>
                        <div class="recommendation-content">
                            <p>${rec.message}</p>
                        </div>
                    </div>
                `);
            });

            $('#results').append($recommendations);
        },

        initializeCharts: function() {
            // Initialize any charts needed for the dashboard
            if ($('#seoTrendsChart').length) {
                new Chart($('#seoTrendsChart'), {
                    type: 'line',
                    data: {
                        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                        datasets: [{
                            label: 'SEO Score Trend',
                            data: [65, 70, 75, 80, 85, 90],
                            borderColor: '#21D37E',
                            tension: 0.4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false
                    }
                });
            }
        },

        handleQuickAction: function(e) {
            const action = $(e.currentTarget).data('action');
            
            switch(action) {
                case 'new-analysis':
                    this.resetForm();
                    break;
                case 'export-report':
                    this.exportReport();
                    break;
                case 'settings':
                    this.openSettings();
                    break;
            }
        },

        exportReport: function() {
            const data = $('#results').data('analysis-results');
            if (!data) {
                this.showError('No analysis data to export');
                return;
            }

            const blob = new Blob([JSON.stringify(data, null, 2)], {type: 'application/json'});
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = 'seo-analysis-report.json';
            document.body.appendChild(a);
            a.click();
            window.URL.revokeObjectURL(url);
            document.body.removeChild(a);
        },

        openSettings: function() {
            // Implement settings modal
        },

        resetForm: function() {
            $('#website-url').val('');
            $('#results').hide().html('');
            $('#error-message').hide();
        },

        showLoading: function() {
            $('#loading-spinner').show();
            $('#error-message').hide();
            $('#results').hide();
        },

        hideLoading: function() {
            $('#loading-spinner').hide();
        },

        showError: function(message) {
            $('#error-message').html(message).show();
        },

        initializeTooltips: function() {
            $('[data-tooltip]').each(function() {
                const $this = $(this);
                const tooltip = $this.data('tooltip');
                
                $this.tooltip({
                    title: tooltip,
                    placement: 'top',
                    trigger: 'hover'
                });
            });
        }
    };

    // Initialize the SEO tool
    seoTool.init();
}); 