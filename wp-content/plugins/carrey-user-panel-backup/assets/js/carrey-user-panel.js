/**
 * Carrey User Panel Frontend JavaScript
 */
(function($) {
    'use strict';

    class CarreyUserPanel {
        constructor() {
            this.init();
        }

        init() {
            this.bindEvents();
            this.loadUserSettings();
            this.initializeCharts();
        }

        bindEvents() {
            // Bind event handlers
            $(document).on('click', '.carrey-action-button', this.handleActionClick.bind(this));
            $(document).on('submit', '.carrey-settings-form', this.handleSettingsSubmit.bind(this));
            $(document).on('change', '.carrey-feature-toggle', this.handleFeatureToggle.bind(this));
        }

        async loadUserSettings() {
            try {
                const response = await fetch(carreyUserPanel.apiUrl + '/settings', {
                    headers: {
                        'X-WP-Nonce': carreyUserPanel.nonce
                    }
                });
                
                if (!response.ok) {
                    throw new Error('Failed to load settings');
                }

                const settings = await response.json();
                this.updateUI(settings);
            } catch (error) {
                console.error('Error loading settings:', error);
                this.showError('Failed to load settings. Please try again.');
            }
        }

        async handleActionClick(e) {
            e.preventDefault();
            const action = $(e.currentTarget).data('action');
            
            try {
                const response = await fetch(carreyUserPanel.apiUrl + '/actions', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-WP-Nonce': carreyUserPanel.nonce
                    },
                    body: JSON.stringify({ action })
                });

                if (!response.ok) {
                    throw new Error('Action failed');
                }

                const result = await response.json();
                this.showSuccess('Action completed successfully');
                this.updateStats(result.stats);
            } catch (error) {
                console.error('Error performing action:', error);
                this.showError('Failed to perform action. Please try again.');
            }
        }

        async handleSettingsSubmit(e) {
            e.preventDefault();
            const formData = new FormData(e.currentTarget);
            const settings = Object.fromEntries(formData.entries());

            try {
                const response = await fetch(carreyUserPanel.apiUrl + '/settings', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-WP-Nonce': carreyUserPanel.nonce
                    },
                    body: JSON.stringify(settings)
                });

                if (!response.ok) {
                    throw new Error('Failed to save settings');
                }

                this.showSuccess('Settings saved successfully');
            } catch (error) {
                console.error('Error saving settings:', error);
                this.showError('Failed to save settings. Please try again.');
            }
        }

        async handleFeatureToggle(e) {
            const feature = $(e.currentTarget).data('feature');
            const enabled = $(e.currentTarget).is(':checked');

            try {
                const response = await fetch(carreyUserPanel.apiUrl + '/features', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-WP-Nonce': carreyUserPanel.nonce
                    },
                    body: JSON.stringify({ feature, enabled })
                });

                if (!response.ok) {
                    throw new Error('Failed to update feature');
                }

                this.showSuccess('Feature updated successfully');
            } catch (error) {
                console.error('Error updating feature:', error);
                this.showError('Failed to update feature. Please try again.');
            }
        }

        updateUI(settings) {
            // Update UI elements based on settings
            Object.entries(settings).forEach(([key, value]) => {
                const element = $(`[data-setting="${key}"]`);
                if (element.length) {
                    if (element.is('input[type="checkbox"]')) {
                        element.prop('checked', value);
                    } else {
                        element.val(value);
                    }
                }
            });
        }

        updateStats(stats) {
            // Update statistics display
            Object.entries(stats).forEach(([key, value]) => {
                const element = $(`.stat-${key}`);
                if (element.length) {
                    element.text(value);
                }
            });
        }

        initializeCharts() {
            // Initialize any charts or visualizations
            if (typeof Chart !== 'undefined') {
                this.initializePerformanceChart();
                this.initializeUsageChart();
            }
        }

        initializePerformanceChart() {
            const ctx = document.getElementById('performance-chart');
            if (ctx) {
                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                        datasets: [{
                            label: 'Performance Score',
                            data: [65, 59, 80, 81, 56, 55, 40],
                            borderColor: '#00A3FF',
                            tension: 0.1
                        }]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            }
        }

        initializeUsageChart() {
            const ctx = document.getElementById('usage-chart');
            if (ctx) {
                new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        labels: ['SEO', 'Content', 'Performance'],
                        datasets: [{
                            data: [30, 40, 30],
                            backgroundColor: ['#00A3FF', '#00FFA3', '#FFA300']
                        }]
                    },
                    options: {
                        responsive: true
                    }
                });
            }
        }

        showSuccess(message) {
            this.showNotification(message, 'success');
        }

        showError(message) {
            this.showNotification(message, 'error');
        }

        showNotification(message, type) {
            const notification = $('<div>')
                .addClass(`carrey-notification carrey-notification-${type}`)
                .text(message)
                .appendTo('body');

            setTimeout(() => {
                notification.fadeOut(() => notification.remove());
            }, 3000);
        }
    }

    // Initialize when document is ready
    $(document).ready(() => {
        new CarreyUserPanel();
    });

})(jQuery); 