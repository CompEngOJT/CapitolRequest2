function initRefreshButton() {
    $('.refresh-btn').off('click').on('click', function() {
        $(this).find('i.fa-sync-alt').addClass('rotating');
        refreshInventoryTable();
    });
}

function refreshInventoryTable() {
    $('.table-container').addClass('loading');
    const url = window.location.href;

    $.ajax({
        url: url,
        type: 'GET',
        dataType: 'html',
        success: function(response) {
            const newTable = $(response).find('.table-container').html();
            $('.table-container').html(newTable);
            showNotification('Table refreshed successfully', 'success');
            $(document).trigger('tableRefreshed'); // Trigger custom event
        },
        error: function(xhr, status, error) {
            console.error('Error refreshing table:', error);
            showNotification('Failed to refresh table. Please try again.', 'error');
        },
        complete: function() {
            $('.table-container').removeClass('loading');
            setTimeout(function() {
                $('.refresh-btn i.fa-sync-alt').removeClass('rotating');
            }, 500);
        }
    });
}

function showNotification(message, type = 'info') {
    if ($('#notification').length === 0) {
        $('body').append('<div id="notification" class="notification"></div>');
    }
    const notification = $('#notification');
    notification.text(message)
               .removeClass()
               .addClass('notification')
               .addClass(type)
               .fadeIn(300).delay(3000).fadeOut(500);
}