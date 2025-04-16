// Add this function to apply colors to status text
function applyStatusColors() {
    // Find all status cells in the table
    $('.request-table tbody tr').each(function() {
        const statusCell = $(this).find('td:contains("Approved"), td:contains("Disapproved")').eq(0);
        const statusText = statusCell.text().trim();
        
        // Apply color based on status
        if (statusText === 'Approved') {
            statusCell.css('color', '#28a745'); // Green color
        } else if (statusText === 'Disapproved') {
            statusCell.css('color', '#dc3545'); // Red color
        }
    });
}

// Modify your initRefreshButton function to call applyStatusColors
function initRefreshButton() {
    $('.refresh-btn').off('click').on('click', function() {
        // Show spinning animation
        const icon = $(this).find('i');
        icon.addClass('fa-spin');
        $(this).prop('disabled', true);
        
        // Get current URL to maintain any active filters
        const currentUrl = window.location.href;
        
        $.ajax({
            url: currentUrl,
            type: "GET",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'X-Requested-With': 'XMLHttpRequest'
            },
            success: function(data) {
                // Update the table container with new data
                $('.table-container').html($(data).find('.table-container').html());
                
                // Update pagination if it exists
                if ($(data).find('.pagination-container').length) {
                    $('.pagination-container').html($(data).find('.pagination-container').html());
                }
                
                // Reinitialize all event handlers
                initRequestDetailsModal();
                initDeleteFunctionality();
                initFilterPopup();
                initPagination();
                
                // Apply status colors
                applyStatusColors();
                
                // Stop spinning animation
                icon.removeClass('fa-spin');
                $('.refresh-btn').prop('disabled', false);
            },
            error: function(xhr) {
                console.error('Error refreshing data:', xhr.responseText);
                alert('Failed to refresh data. Please try again.');
                
                // Stop spinning animation
                icon.removeClass('fa-spin');
                $('.refresh-btn').prop('disabled', false);
            }
        });
    });
}

// Also apply status colors on initial page load
$(document).ready(function() {
    // Apply styles for the refresh button
    applyRefreshButtonStyles();
    
    // Initialize the refresh button functionality
    initRefreshButton();
    
    // Apply status colors on page load
    applyStatusColors();
    
    // Allow other scripts to call refreshTableData()
    window.refreshTableData = refreshTableData;
});



// Helper function to refresh table data
function refreshTableData() {
    // Trigger the refresh button click programmatically
    $('.refresh-btn').trigger('click');
}

// Initialize everything when the document is ready
$(document).ready(function() {
    // Apply styles for the refresh button
    applyRefreshButtonStyles();
    
    // Initialize the refresh button functionality
    initRefreshButton();
    
    // Allow other scripts to call refreshTableData()
    window.refreshTableData = refreshTableData;
});