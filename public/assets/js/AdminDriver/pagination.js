/**
 * Initialize pagination click handlers
 */
function initPagination() {
    $(document).off('click', '.pagination a').on('click', '.pagination a', handlePagination);
}

/**
 * Handle pagination link clicks
 * @param {Event} e - Click event
 */
function handlePagination(e) {
    e.preventDefault();

    var url = $(this).attr('href');
    const currentParams = new URLSearchParams(window.location.search);
    const targetUrl = new URL(url, window.location.origin);

    // Preserve any existing query parameters
    currentParams.forEach((value, key) => {
        if (!targetUrl.searchParams.has(key)) {
            targetUrl.searchParams.append(key, value);
        }
    });

    // Show loading indicator if needed
    $('.table-container').addClass('loading');

    $.ajax({
        url: targetUrl.toString(),
        type: "GET",
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        success: function(response) {
            const newTable = $(response).find('.table-container').html();
            const newPagination = $(response).find('.pagination-container').html();

            $('.table-container').html(newTable);
            $('.pagination-container').html(newPagination);

            // Update URL without full page reload
            window.history.pushState({}, '', targetUrl.toString());
            
            // Trigger custom event for other components to react
            $(document).trigger('paginationComplete');
            
            // Remove loading indicator
            $('.table-container').removeClass('loading');
        },
        error: function(xhr) {
            console.error('Error loading driver data:', xhr.responseText);
            alert('Error loading driver data!');
            $('.table-container').removeClass('loading');
        }
    });
}

// Initialize pagination when document is ready
$(document).ready(function() {
    initPagination();
    
    // Reinitialize after any dynamic content updates
    $(document).on('paginationComplete', function() {
        initPagination();
    });
});