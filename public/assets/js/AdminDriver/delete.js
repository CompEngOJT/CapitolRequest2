function initDeleteFunctionality() {
    console.log('Initializing driver delete functionality');
    
    // Remove any existing event handlers to prevent duplicates
    $(document).off('click', '.delete-btn-small');
    
    // Add click event handler for delete buttons
    $(document).on('click', '.delete-btn-small', function() {
        const row = $(this).closest('tr');
        const driverId = row.data('driver-id');
        const driverName = row.find('td:first').text();
        
        console.log('Delete clicked for driver ID:', driverId);
        
        if (!driverId) {
            console.error('Driver ID not found');
            alert('Error: Driver ID not found');
            return;
        }
        
        // Use the browser's built-in confirm dialog
        if (confirm(`Are you sure you want to delete "${driverName}" and any duplicates?`)) {
            // Show loading state
            row.addClass('deleting');
            
            // Delete the driver using the DELETE method
            $.ajax({
                url: `/admin/driver/delete/${driverId}?remove_duplicates=true`,
                type: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    console.log('Delete response:', response);
                    
                    if (response.success) {
                        // If duplicates were deleted, we might need to refresh the page
                        if (response.duplicatesRemoved && response.duplicatesRemoved > 0) {
                            alert(`Driver and ${response.duplicatesRemoved} duplicate(s) deleted successfully!`);
                            location.reload(); // Reload the page to reflect all changes
                        } else {
                            // Remove just the current row from the table
                            row.fadeOut(300, function() {
                                $(this).remove();
                                alert('Driver deleted successfully!');
                                
                                // If table is empty after deletion, add the "No drivers found" row
                                if ($('.drivers-table tbody tr').length === 0) {
                                    $('.drivers-table tbody').append('<tr><td colspan="3" class="text-center">No drivers found.</td></tr>');
                                }
                            });
                        }
                    } else {
                        // Remove loading state
                        row.removeClass('deleting');
                        alert('Error: ' + (response.message || 'Failed to delete driver.'));
                    }
                },
                error: function(xhr) {
                    console.error('Delete request failed:', xhr.responseText);
                    
                    // Remove loading state
                    row.removeClass('deleting');
                    alert('Error: Something went wrong while deleting the driver.');
                }
            });
        }
    });
}

// Make sure the function is called when the page loads
$(document).ready(function() {
    initDeleteFunctionality();
});