jQuery(document).ready(function($) {
    var form = $('#add-website-form');
    var messageDiv = $('#add-website-message');
    var spinner = form.find('.spinner');
    var websitesListDiv = $('#user-websites-list'); // Div to display the list

    form.on('submit', function(e) {
        e.preventDefault(); // Stop normal form submission

        messageDiv.hide().removeClass('success error').empty(); // Hide old messages
        form.addClass('loading'); // Show spinner
        spinner.css('visibility', 'visible'); // Ensure spinner is visible

        var websiteUrl = $('#website_url').val();

        $.ajax({
            url: carreyDashboardAjax.ajax_url,
            type: 'POST',
            data: {
                action: 'carrey_add_website', // Matches add_action in PHP
                nonce: carreyDashboardAjax.add_nonce, // Use specific nonce
                website_url: websiteUrl
            },
            success: function(response) {
                if (response.success) {
                    messageDiv.addClass('success').text(response.data.message).show();
                    form[0].reset(); // Clear the form
                    loadUserWebsites(); // Call function to reload the list
                } else {
                    messageDiv.addClass('error').text(response.data.message).show();
                }
            },
            error: function() {
                messageDiv.addClass('error').text('An error occurred. Please try again.').show();
            },
            complete: function() {
                form.removeClass('loading'); // Hide spinner
                spinner.css('visibility', 'hidden');
            }
        });
    });

    // Function to load the user's websites
    function loadUserWebsites() {
        websitesListDiv.html('<p><em>Loading websites...</em></p>'); // Show loading message

        $.ajax({
            url: carreyDashboardAjax.ajax_url,
            type: 'POST',
            data: {
                action: 'carrey_get_websites', // Matches the new action hook
                // Pass nonce if you decide to check it in PHP
                // nonce: carreyDashboardAjax.get_nonce 
            },
            success: function(response) {
                websitesListDiv.empty(); // Clear loading message or old list

                if (response.success && response.data.websites.length > 0) {
                    var listHtml = '<ul class="user-website-list-items">';
                    $.each(response.data.websites, function(index, websiteUrl) {
                        // Escape URL for safety when displaying
                        var escapedUrl = $('<div/>').text(websiteUrl).html(); 
                        listHtml += '<li>';
                        listHtml += '<span class="website-url">' + escapedUrl + '</span>';
                        // Add buttons for actions later (Analyze, Delete, etc.)
                        listHtml += '<div class="website-actions">';
                        listHtml += '<button class="button button-small analyze-button" data-url="' + escapedUrl + '">Analyze</button>';
                        listHtml += '<button class="button button-small delete-button" data-url="' + escapedUrl + '">Delete</button>';
                        listHtml += '</div>';
                        listHtml += '</li>';
                    });
                    listHtml += '</ul>';
                    websitesListDiv.html(listHtml);
                } else if (response.success) {
                    websitesListDiv.html('<p>' + 'No websites added yet.' + '</p>');
                } else {
                    // Handle potential error from the AJAX call itself
                    websitesListDiv.html('<p class="error">'+ 'Could not load websites.' + '</p>');
                }
            },
            error: function() {
                 websitesListDiv.html('<p class="error">'+ 'Could not load websites. An error occurred.' + '</p>');
            }
        });
    }

    // Load the list when the page loads
    loadUserWebsites();

    // --- EVENT LISTENERS FOR BUTTONS (Add later) --- 
    // Example for delete button listener:
    // websitesListDiv.on('click', '.delete-button', function() {
    //     var urlToDelete = $(this).data('url');
    //     if (confirm('Are you sure you want to delete ' + urlToDelete + '?')) {
    //         // Call AJAX function to delete website
    //     }
    // });

}); 