$(document).ready(function() {
    let searchTimer;
    const searchDelay = 500; // Delay in milliseconds

    // Handle search input with debounce
    $('#searchInput').on('keyup', function() {
        clearTimeout(searchTimer);
        
        searchTimer = setTimeout(function() {
            const searchText = $('#searchInput').val().trim();
            performSearch(searchText);
        }, searchDelay);
    });

    // Function to perform AJAX search
    function performSearch(searchText) {
        // Show loading indicator
        $('.table-container').addClass('loading');
        
        // Get the current URL
        const currentUrl = window.location.href.split('?')[0];
        
        // Build the search URL
        const searchUrl = currentUrl + (searchText ? '?search=' + encodeURIComponent(searchText) : '');
        
        // Perform AJAX request
        $.ajax({
            url: searchUrl,
            type: 'GET',
            success: function(response) {
                // Parse the HTML response
                const parser = new DOMParser();
                const doc = parser.parseFromString(response, 'text/html');
                
                // Extract the table content and pagination
                const newTable = doc.querySelector('.table-container').innerHTML;
                const newPagination = doc.querySelector('.pagination-container').innerHTML;
                
                // Update the table and pagination in the DOM
                document.querySelector('.table-container').innerHTML = newTable;
                document.querySelector('.pagination-container').innerHTML = newPagination;
                
                // Remove loading indicator
                $('.table-container').removeClass('loading');
                
                // Reinitialize pagination
                if (typeof initPagination === 'function') {
                    initPagination();
                }
            },
            error: function(xhr, status, error) {
                console.error('Search error:', error);
                alert('Error performing search. Please try again.');
                
                // Remove loading indicator
                $('.table-container').removeClass('loading');
            }
        });
    }
});