// CSRF token setup for AJAX requests
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
// Global variables for withdrawal pagination
let currentProductName = '';
let currentPage = 1;
let totalPages = 1;

// Function to handle view button click
$(document).on('click', '.view-btn', function() {
    let productName = $(this).data('product-name');
    currentProductName = productName;
    currentPage = 1; // Reset to first page when opening modal
    
    // Set the product name in the modal header
    $('#modalProductName').text(productName + ' Details');
    
    // Clear previous data
    $('#withdrawalRequestsBody').empty();
    $('#noRequestsMessage').addClass('hidden');
    $('#loadingRequests').removeClass('hidden');
    
    // Show the modal
    $('#viewDetailsModal').css('display', 'block');
    
    // Load the first page of withdrawal details
    loadWithdrawalDetails(productName, currentPage);
});

// Function to load withdrawal details with pagination
function loadWithdrawalDetails(productName, page) {
    // Show loading indicator
    $('#loadingRequests').removeClass('hidden');
    
    $.ajax({
        url: '/admin/inventory/withdrawal-details',
        type: 'POST',
        data: {
            product_name: productName,
            page: page,
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        dataType: 'json',
        success: function(response) {
            // Hide loading indicator
            $('#loadingRequests').addClass('hidden');
            
            if (response.success) {
                // Update pagination info
                currentPage = response.pagination.current_page;
                totalPages = response.pagination.total_pages;
                
                // Update pagination display
                updatePaginationDisplay();
                
                // Update product details - use last_withdrawal directly from response
                $('#modalTotalStocks').text(response.current_stock + response.total_withdrawals);
                $('#modalLastWithdrawal').text(response.last_withdrawal); // Always use the proper last withdrawal
                $('#modalStockRemaining').text(response.current_stock);
                
                // Clear and populate withdrawal requests table
                $('#withdrawalRequestsBody').empty();
                
                if (response.requests.length > 0) {
                    response.requests.forEach(function(request) {
                        let row = `
                            <tr>
                                <td>${request.created_at}</td>
                                <td>${request.government_car_number}</td>
                                <td>${request.driver_name}</td>
                                <td>${request.stock_before}</td>
                                <td>${request.quantity}</td>
                                <td>${request.stock_after}</td>
                            </tr>
                        `;
                        $('#withdrawalRequestsBody').append(row);
                    });
                    $('#noRequestsMessage').addClass('hidden');
                } else {
                    $('#noRequestsMessage').removeClass('hidden');
                }
            } else {
                // Show error message
                $('#withdrawalRequestsBody').empty();
                $('#noRequestsMessage').removeClass('hidden').text('Error: ' + response.message);
            }
        },
        error: function(xhr, status, error) {
            // Enhanced error logging
            $('#loadingRequests').addClass('hidden');
            
            // Show more detailed error message
            $('#withdrawalRequestsBody').empty();
            $('#noRequestsMessage').removeClass('hidden').text('Error: ' + error + ' (Status: ' + xhr.status + ')');
            console.error('AJAX Error:', xhr.responseText);
        }
    });
}

// Function to update pagination display
function updatePaginationDisplay() {
    $('#currentPage').text(currentPage);
    $('#totalPages').text(totalPages);
    
    // Enable/disable previous button
    if (currentPage <= 1) {
        $('#prevPageBtn').prop('disabled', true).addClass('disabled');
    } else {
        $('#prevPageBtn').prop('disabled', false).removeClass('disabled');
    }
    
    // Enable/disable next button
    if (currentPage >= totalPages) {
        $('#nextPageBtn').prop('disabled', true).addClass('disabled');
    } else {
        $('#nextPageBtn').prop('disabled', false).removeClass('disabled');
    }
}

// Previous page button click handler
$(document).on('click', '#prevPageBtn', function() {
    if (currentPage > 1) {
        currentPage--;
        loadWithdrawalDetails(currentProductName, currentPage);
    }
});

// Next page button click handler
$(document).on('click', '#nextPageBtn', function() {
    if (currentPage < totalPages) {
        currentPage++;
        loadWithdrawalDetails(currentProductName, currentPage);
    }
});

// Close modal
$(document).on('click', '.close-modal', function() {
    $('#viewDetailsModal').css('display', 'none');
});

// Close modal when clicking outside
$(window).on('click', function(event) {
    if ($(event.target).is('#viewDetailsModal')) {
        $('#viewDetailsModal').css('display', 'none');
    }
});