function initFilterPopup() {
    const filterPopup = $('#filter-popup');
    const filterButton = $('.filter-button');

    // Remove existing event listeners to prevent duplicates
    filterButton.off('click');
    $('#close-filter').off('click');
    $('#apply-filter').off('click');
    $('#reset-filter').off('click');
    
    // Show filter popup when filter button is clicked
    filterButton.on('click', function() {
        filterPopup.css('display', 'flex');
        $(this).addClass('active');
    });

    // Close filter popup
    $('#close-filter').on('click', function() {
        filterPopup.hide();
        filterButton.removeClass('active');
    });

    // Apply filters using AJAX
    $('#apply-filter').on('click', function(e) {
        e.preventDefault();
        
        // Get filter values
        const driverId = $('#filter-driver').val();
        const dateFrom = $('#filter-date-from').val();
        const dateTo = $('#filter-date-to').val();
        const type = $('#filter-type').val();
        const division = $('#filter-division').val();

        // Build query string
        const params = new URLSearchParams();
        
        if (driverId) params.append('driver_id', driverId);
        if (dateFrom) params.append('date_from', dateFrom);
        if (dateTo) params.append('date_to', dateTo);
        if (type) params.append('type', type);
        if (division) params.append('division', division);
        
        // Get current path and build URL
        const currentUrl = window.location.pathname;
        const queryString = params.toString();
        const url = queryString ? `${currentUrl}?${queryString}` : currentUrl;
        
        // Update browser history without refreshing
        window.history.pushState({}, '', url);
        
        // Close popup
        filterPopup.hide();
        filterButton.removeClass('active');
        
        // Fetch filtered data using AJAX
        $.ajax({
            url: url,
            type: "GET",
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            success: function(response) {
                // Update the table and pagination
                $('.table-container').html($(response).find('.table-container').html());
                $('.pagination-container').html($(response).find('.pagination-container').html());
                
                // Reinitialize components
                initRequestDetailsModal();
                initDeleteFunctionality();
                initPagination();
                
                // Show loading indicator (optional)
                // $('#loading-indicator').hide();
            },
            error: function(xhr) {
                console.error('Error applying filters:', xhr.responseText);
                alert('Error loading filtered data!');
            }
        });
        
        // Optional: Show loading indicator
        // $('#loading-indicator').show();
    });

    // Reset filters
    $('#reset-filter').on('click', function() {
        // Clear all filter fields
        $('#filter-driver').val('');
        $('#filter-date-from').val('');
        $('#filter-date-to').val('');
        $('#filter-type').val('');
        $('#filter-division').val('');
        
        // Optionally, you can automatically apply the reset by triggering the apply filter
        // $('#apply-filter').trigger('click');
    });
}