$(document).ready(function() {
    // Initialize all modules
    initPagination();
    initAddProductPopup();
    initRefreshButton();
    initViewDetailsModal();
    initDeleteFunctionality();



    // Reinitialize modules after DOM updates
    $(document).on('paginationComplete tableRefreshed productAdded', function() {
        initPagination();
        initAddProductPopup();
        initRefreshButton();
        initViewDetailsModal();
        initDeleteFunctionality();

    });
});