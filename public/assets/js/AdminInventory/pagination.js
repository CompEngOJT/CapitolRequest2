function initPagination() {
    $(document).off('click', '.pagination a').on('click', '.pagination a', handlePagination);
}

function handlePagination(e) {
    e.preventDefault();

    var url = $(this).attr('href');
    const currentParams = new URLSearchParams(window.location.search);
    const targetUrl = new URL(url, window.location.origin);

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
            const newTable = $(response).find('.table-container').html();
            const newPagination = $(response).find('.pagination-container').html();

            $('.table-container').html(newTable);
            $('.pagination-container').html(newPagination);

            window.history.pushState({}, '', targetUrl.toString());
            $(document).trigger('paginationComplete'); // Trigger custom event
        },
        error: function(xhr) {
            console.error('Error loading data:', xhr.responseText);
            alert('Error loading data!');
        }
    });
}