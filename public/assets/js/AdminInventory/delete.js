function initDeleteFunctionality() {
    console.log('Initializing delete functionality');
    
    // Remove any existing event handlers to prevent duplicates
    $(document).off('click', '.delete-btn-small');
    
    // Add click event handler for delete buttons
    $(document).on('click', '.delete-btn-small', function() {
        const row = $(this).closest('tr');
        const productId = row.data('product-id');
        const productName = row.find('td:first').text();
        
        console.log('Delete clicked for product ID:', productId);
        
        if (!productId) {
            console.error('Product ID not found');
            alert('Error: Product ID not found');
            return;
        }
        
        // Use the browser's built-in confirm dialog
        if (confirm(`Are you sure you want to delete "${productName}"?`)) {
            // Show loading state
            row.addClass('deleting');
            
            // Delete the product using the DELETE method
            $.ajax({
                url: `/admin/inventory/delete-product/${productId}`,
                type: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    console.log('Delete response:', response);
                    
                    if (response.success) {
                        // Remove the row from the table
                        row.fadeOut(300, function() {
                            $(this).remove();
                            alert('Product deleted successfully!');
                            
                            // If table is empty after deletion, add the "No products found" row
                            if ($('.request-table tbody tr').length === 0) {
                                $('.request-table tbody').append('<tr><td colspan="5" class="text-center">No inventory products found.</td></tr>');
                            }
                        });
                    } else {
                        // Remove loading state
                        row.removeClass('deleting');
                        alert('Error: ' + (response.message || 'Failed to delete product.'));
                    }
                },
                error: function(xhr) {
                    console.error('Delete request failed:', xhr.responseText);
                    
                    // Remove loading state
                    row.removeClass('deleting');
                    alert('Error: Something went wrong while deleting the product.');
                }
            });
        }
    });
}

// Make sure the function is called when the page loads
$(document).ready(function() {
    initDeleteFunctionality();
});