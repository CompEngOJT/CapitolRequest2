function initPagination() {
    $(document).on('click', '.pagination a', handlePagination);
}

// Function to handle pagination
function handlePagination(e) {
    e.preventDefault();

    var url = $(this).attr('href');

    // Preserve any existing filter parameters
    const currentParams = new URLSearchParams(window.location.search);
    const targetUrl = new URL(url, window.location.origin);

    // Add all current filter parameters to the pagination URL
    currentParams.forEach((value, key) => {
        if (!targetUrl.searchParams.has(key)) {
            targetUrl.searchParams.append(key, value);
        }
    });

    $.ajax({
        url: targetUrl.toString(),
        type: "GET",
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        success: function(response) {
            // Target the table-container and pagination-container
            var newTable = $(response).find('.table-container').html();
            var newPagination = $(response).find('.pagination-container').html();

            // Update the table and pagination
            $('.table-container').html(newTable);
            $('.pagination-container').html(newPagination);

            // Update browser history
            window.history.pushState({}, '', targetUrl.toString());

            // Reinitialize modules
            initFilterPopup();
            initRequestDetailsModal();
            initDeleteFunctionality();
        },
        error: function(xhr) {
            console.error('Error loading data:', xhr.responseText);
            alert('Error loading data!');
        }
    });
}