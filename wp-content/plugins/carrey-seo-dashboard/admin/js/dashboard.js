jQuery(document).ready(function($) {
    // Initialize performance chart
    const performanceCtx = document.getElementById('seo-performance-chart');
    if (performanceCtx) {
        new Chart(performanceCtx.getContext('2d'), {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [{
                    label: 'SEO Score',
                    data: [65, 70, 75, 80, 85, 90],
                    borderColor: '#2271b1',
                    backgroundColor: 'rgba(34, 113, 177, 0.1)',
                    tension: 0.1,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.1)'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    }

    // Initialize traffic chart
    const trafficCtx = document.getElementById('traffic-chart');
    if (trafficCtx) {
        new Chart(trafficCtx.getContext('2d'), {
            type: 'bar',
            data: {
                labels: ['Organic', 'Direct', 'Referral'],
                datasets: [{
                    label: 'Traffic',
                    data: [1500, 800, 300],
                    backgroundColor: [
                        '#2271b1',
                        '#00a32a',
                        '#d63638'
                    ],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.1)'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    }

    // Handle automation actions
    $('.automate-action').on('click', function() {
        const action = $(this).data('action');
        const button = $(this);
        const card = button.closest('.recommendation-card');
        
        button.prop('disabled', true).text('Processing...');
        card.addClass('processing');
        
        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: {
                action: 'carrey_automate_seo',
                nonce: carreyDashboard.nonce,
                action_type: action
            },
            success: function(response) {
                if (response.success) {
                    button.text('Completed!').addClass('button-secondary');
                    card.removeClass('processing').addClass('completed');
                    setTimeout(() => {
                        button.prop('disabled', false)
                            .text('Start Automation')
                            .removeClass('button-secondary');
                        card.removeClass('completed');
                    }, 3000);
                } else {
                    button.prop('disabled', false).text('Start Automation');
                    card.removeClass('processing');
                    alert(response.data.message || 'An error occurred during automation.');
                }
            },
            error: function() {
                button.prop('disabled', false).text('Start Automation');
                card.removeClass('processing');
                alert('An error occurred during automation.');
            }
        });
    });

    // Add hover effects for stat boxes
    $('.carrey-stat-box').hover(
        function() {
            $(this).css('transform', 'translateY(-5px)');
        },
        function() {
            $(this).css('transform', 'translateY(0)');
        }
    );

    // Add click handlers for recommendation cards
    $('.recommendation-card').on('click', function(e) {
        if (!$(e.target).is('button')) {
            const title = $(this).find('h3').text();
            const description = $(this).find('p').text();
            
            // Show modal with details
            const modal = $('<div class="carrey-modal">')
                .append($('<div class="carrey-modal-content">')
                    .append($('<h3>').text(title))
                    .append($('<p>').text(description))
                    .append($('<button class="button button-primary">').text('Close')));
            
            $('body').append(modal);
            
            // Close modal on button click
            modal.find('button').on('click', function() {
                modal.remove();
            });
        }
    });
}); 