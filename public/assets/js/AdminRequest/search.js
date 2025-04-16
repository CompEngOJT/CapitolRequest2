function initSearchFunctionality() {
    const searchInput = $('.search-input');
    const searchButton = $('.fa-search');
    
    // Remove existing event listeners to prevent duplicates
    searchInput.off('keypress');
    searchButton.off('click');
    
    // Perform search when Enter key is pressed in the search input
    searchInput.on('keypress', function(e) {
        if (e.which === 13) { // Enter key
            performSearch();
        }
    });
    
    // Perform search when search icon is clicked
    searchButton.on('click', function() {
        performSearch();
    });
    
    function performSearch() {
        const searchQuery = searchInput.val().trim();
        
        // Get current URL and parameters
        const currentUrl = window.location.pathname;
        const urlParams = new URLSearchParams(window.location.search);
        
        // Update or add search parameter
        if (searchQuery) {
            urlParams.set('search', searchQuery);
        } else {
            urlParams.delete('search');
        }
        
        // Build new URL
        const queryString = urlParams.toString();
        const url = queryString ? `${currentUrl}?${queryString}` : currentUrl;
        
        // Update browser history without refreshing
        window.history.pushState({}, '', url);
        
        // Show loading indicator (optional)
        // $('#loading-indicator').show();
        
        // Fetch filtered data using AJAX
        $.ajax({
            url: url,
            type: "GET",
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                // Update the table and pagination
                $('.table-container').html($(response).find('.table-container').html());
                $('.pagination-container').html($(response).find('.pagination-container').html());
                
                // Reinitialize components
                initRequestDetailsModal();
                initDeleteFunctionality();
                initPagination();
                
                // Hide loading indicator (optional)
                // $('#loading-indicator').hide();
            },
            error: function(xhr) {
                console.error('Error performing search:', xhr.responseText);
                alert('Error loading search results!');
            }
        });
    }
}