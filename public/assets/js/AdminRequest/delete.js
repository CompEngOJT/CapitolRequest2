function initDeleteFunctionality() {
    // Remove any existing event handlers to prevent duplicates
    $(document).off('click', '.delete-btn-small');
    
    // Add click event handler for delete buttons
    $(document).on('click', '.delete-btn-small', function() {
        const row = $(this).closest('tr');
        const requestId = row.data('request-id');
        const driverName = row.find('td:nth-child(3)').text(); // Assuming the driver name is in the 3rd column
        
        // Check if we have a request ID
        if (!requestId) {
            console.error('Request ID not found. Make sure data-request-id is set on the TR element.');
            alert('Error: Request ID not found.');
            return;
        }
        
        // Show confirmation dialog
        if (window.Swal) {
            // Use SweetAlert if available
            Swal.fire({
                title: 'Delete Request',
                text: `Are you sure you want to delete this request for "${driverName}"?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    deleteRequest(requestId, row);
                }
            });
        } else {
            // Use browser confirm if SweetAlert is not available
            if (confirm(`Are you sure you want to delete this request for "${driverName}"?`)) {
                deleteRequest(requestId, row);
            }
        }
    });
    
    // Function to handle the actual delete API call
    function deleteRequest(requestId, row) {
        // Show loading state
        row.addClass('deleting');
        
        // AJAX call to delete the request
        $.ajax({
            url: '/admin/requests/delete/' + requestId, // Updated to match your actual route
            type: 'DELETE', // Changed to DELETE since your route is using DELETE method
            data: {
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    // Remove the row from the table
                    row.fadeOut(300, function() {
                        $(this).remove();
                        
                        // Show success message
                        if (window.Swal) {
                            Swal.fire(
                                'Deleted!',
                                'Request has been deleted successfully.',
                                'success'
                            );
                        } else {
                            alert('Request deleted successfully!');
                        }
                        
                        // If table is empty after deletion, add the "No requests found" row
                        if ($('.request-table tbody tr').length === 0) {
                            $('.request-table tbody').append('<tr><td colspan="8" class="text-center">No requests found.</td></tr>');
                        }
                    });
                } else {
                    // Remove loading state
                    row.removeClass('deleting');
                    
                    // Show error message
                    if (window.Swal) {
                        Swal.fire(
                            'Error!',
                            response.message || 'Failed to delete request.',
                            'error'
                        );
                    } else {
                        alert('Error: ' + (response.message || 'Failed to delete request.'));
                    }
                }
            },
            error: function(xhr) {
                // Remove loading state
                row.removeClass('deleting');
                
                // Show error message and debug information
                console.error('Delete request failed:', xhr.responseText);
                console.error('Status:', xhr.status);
                console.error('Status text:', xhr.statusText);
                
                if (window.Swal) {
                    Swal.fire(
                        'Error!',
                        'Something went wrong while deleting the request. Check console for details.',
                        'error'
                    );
                } else {
                    alert('Error: Something went wrong while deleting the request. Check console for details.');
                }
            }
        });
    }
}

// Initialize delete functionality when the document is ready
$(document).ready(function() {
    initDeleteFunctionality();
});