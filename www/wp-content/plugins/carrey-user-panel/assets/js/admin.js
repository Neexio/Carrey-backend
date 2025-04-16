/**
 * Carrey User Panel Admin JavaScript
 */
(function($) {
    'use strict';

    class CarreyUserPanelAdmin {
        constructor() {
            this.init();
        }

        init() {
            this.bindEvents();
            this.initializeAdminFeatures();
        }

        bindEvents() {
            // Bind event handlers
            $(document).on('click', '.carrey-admin-action', this.handleAdminAction.bind(this));
            $(document).on('submit', '.carrey-admin-form', this.handleAdminFormSubmit.bind(this));
            $(document).on('change', '.carrey-admin-toggle', this.handleAdminToggle.bind(this));
        }

        initializeAdminFeatures() {
            // Initialize any admin-specific features
            this.initializeAdminCharts();
            this.initializeAdminTables();
        }

        async handleAdminAction(e) {
            e.preventDefault();
            const action = $(e.currentTarget).data('action');
            const confirmMessage = $(e.currentTarget).data('confirm');

            if (confirmMessage && !confirm(confirmMessage)) {
                return;
            }

            try {
                const response = await fetch(carreyUserPanel.apiUrl + '/admin/' + action, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-WP-Nonce': carreyUserPanel.nonce
                    }
                });

                if (!response.ok) {
                    throw new Error('Action failed');
                }

                const result = await response.json();
                this.showAdminSuccess('Action completed successfully');
                this.updateAdminUI(result);
            } catch (error) {
                console.error('Error performing admin action:', error);
                this.showAdminError('Failed to perform action. Please try again.');
            }
        }

        async handleAdminFormSubmit(e) {
            e.preventDefault();
            const form = $(e.currentTarget);
            const formData = new FormData(form[0]);
            const data = Object.fromEntries(formData.entries());

            try {
                const response = await fetch(carreyUserPanel.apiUrl + '/admin/settings', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-WP-Nonce': carreyUserPanel.nonce
                    },
                    body: JSON.stringify(data)
                });

                if (!response.ok) {
                    throw new Error('Failed to save settings');
                }

                this.showAdminSuccess('Settings saved successfully');
            } catch (error) {
                console.error('Error saving admin settings:', error);
                this.showAdminError('Failed to save settings. Please try again.');
            }
        }

        async handleAdminToggle(e) {
            const toggle = $(e.currentTarget);
            const setting = toggle.data('setting');
            const value = toggle.is(':checked');

            try {
                const response = await fetch(carreyUserPanel.apiUrl + '/admin/toggle', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-WP-Nonce': carreyUserPanel.nonce
                    },
                    body: JSON.stringify({
                        setting,
                        value
                    })
                });

                if (!response.ok) {
                    throw new Error('Failed to update setting');
                }

                this.showAdminSuccess('Setting updated successfully');
            } catch (error) {
                console.error('Error updating admin setting:', error);
                this.showAdminError('Failed to update setting. Please try again.');
                toggle.prop('checked', !value); // Revert toggle state
            }
        }

        initializeAdminCharts() {
            if (typeof Chart !== 'undefined') {
                this.initializeUserStatsChart();
                this.initializeFeatureUsageChart();
            }
        }

        initializeUserStatsChart() {
            const ctx = document.getElementById('admin-user-stats-chart');
            if (ctx) {
                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                        datasets: [{
                            label: 'Active Users',
                            data: [65, 59, 80, 81, 56, 55],
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

        initializeFeatureUsageChart() {
            const ctx = document.getElementById('admin-feature-usage-chart');
            if (ctx) {
                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: ['SEO', 'Content', 'Performance'],
                        datasets: [{
                            label: 'Feature Usage',
                            data: [30, 40, 30],
                            backgroundColor: ['#00A3FF', '#00FFA3', '#FFA300']
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

        initializeAdminTables() {
            if ($.fn.DataTable) {
                $('.carrey-admin-table').DataTable({
                    responsive: true,
                    language: {
                        search: "Search:",
                        lengthMenu: "Show _MENU_ entries",
                        info: "Showing _START_ to _END_ of _TOTAL_ entries",
                        infoEmpty: "Showing 0 to 0 of 0 entries",
                        infoFiltered: "(filtered from _MAX_ total entries)"
                    }
                });
            }
        }

        updateAdminUI(data) {
            // Update admin UI elements based on response data
            if (data.stats) {
                this.updateAdminStats(data.stats);
            }
            if (data.features) {
                this.updateAdminFeatures(data.features);
            }
        }

        updateAdminStats(stats) {
            Object.entries(stats).forEach(([key, value]) => {
                const element = $(`.admin-stat-${key}`);
                if (element.length) {
                    element.text(value);
                }
            });
        }

        updateAdminFeatures(features) {
            Object.entries(features).forEach(([key, value]) => {
                const element = $(`.admin-feature-${key}`);
                if (element.length) {
                    element.prop('checked', value);
                }
            });
        }

        showAdminSuccess(message) {
            this.showAdminNotification(message, 'success');
        }

        showAdminError(message) {
            this.showAdminNotification(message, 'error');
        }

        showAdminNotification(message, type) {
            const notification = $('<div>')
                .addClass(`carrey-admin-notification carrey-admin-notification-${type}`)
                .text(message)
                .appendTo('.carrey-admin-content');

            setTimeout(() => {
                notification.fadeOut(() => notification.remove());
            }, 3000);
        }
    }

    // Initialize when document is ready
    $(document).ready(() => {
        new CarreyUserPanelAdmin();
    });

})(jQuery); 