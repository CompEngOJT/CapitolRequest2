// Add this to your existing JavaScript file or in a script tag at the bottom of your blade file

$(document).ready(function() {
    // Use event delegation to handle click events on delete icons (works for dynamically added elements)
    $(document).on('click', '.delete-icon', function() {
        const row = $(this).closest('tr');
        const productName = row.find('td:first').text().trim();
        
        if (confirm(`Are you sure you want to delete "${productName}" from inventory?`)) {
            deleteProduct(productName, row);
        }
    });
});

/**
 * Delete a product from the inventory using AJAX
 * @param {string} productName - The name of the product to delete
 * @param {jQuery} row - The table row jQuery element to remove on success
 */
function deleteProduct(productName, row) {
    $.ajax({
        url: '/admin/inventory/delete-product',
        type: 'POST',
        data: {
            product: productName,
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                // Remove the row with animation
                row.fadeOut(300, function() {
                    $(this).remove();
                    
                    // If the table is now empty, add the "No inventory data found" row
                    if ($('#table-body tr').length === 0) {
                        $('#table-body').append(`
                            <tr>
                                <td colspan="5" class="text-center">No inventory data found.</td>
                            </tr>
                        `);
                    }
                });
                
                // Show success message
                showNotification('Success', response.message || 'Product deleted successfully', 'success');
                
                // Update the pagination if necessary
                refreshPagination();
            } else {
                showNotification('Error', response.message || 'Failed to delete product', 'error');
            }
        },
        error: function(xhr) {
            let errorMessage = 'An unexpected error occurred';
            if (xhr.responseJSON && xhr.responseJSON.message) {
                errorMessage = xhr.responseJSON.message;
            }
            showNotification('Error', errorMessage, 'error');
        }
    });
}

/**
 * Show a notification to the user
 * @param {string} title - The notification title
 * @param {string} message - The notification message
 * @param {string} type - The notification type (success, error, info)
 */
function showNotification(title, message, type) {
    // Create notification element
    const notificationHtml = `
        <div class="notification ${type}">
            <strong>${title}</strong>
            <p>${message}</p>
        </div>
    `;
    
    // Remove any existing notifications
    $('.notification').remove();
    
    // Add the new notification
    $('body').append(notificationHtml);
    
    // Auto-remove after 5 seconds
    setTimeout(function() {
        $('.notification').fadeOut(500, function() {
            $(this).remove();
        });
    }, 5000);
}

/**
 * Refresh the pagination after deletion if necessary
 */
function refreshPagination() {
    // If we're on a page with only one item and it was deleted, go to previous page
    if ($('#table-body tr').length === 0 && $('.pagination .active').text() !== '1') {
        const currentPage = parseInt($('.pagination .active').text());
        const previousPage = currentPage - 1;
        
        // Get the URL for the previous page
        const previousPageUrl = $('.pagination .page-item:not(.active) .page-link')
            .filter(function() {
                return $(this).text() == previousPage;
            })
            .attr('href');
        
        if (previousPageUrl) {
            // Load data via AJAX
            $.ajax({
                url: previousPageUrl,
                type: 'GET',
                dataType: 'html',
                success: function(response) {
                    // Replace the table container with the new content
                    $('#table-container').html($(response).find('#table-container').html());
                    
                    // Update the URL without refreshing
                    history.pushState(null, '', previousPageUrl);
                }
            });
        }
    }
}