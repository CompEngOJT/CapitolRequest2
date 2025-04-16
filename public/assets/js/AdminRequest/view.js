function initRequestDetailsModal() {
    let currentRequestId = null; // Store the current request ID
    let isEditMode = false; // Track whether the modal is in edit mode

    // Add click event listeners to view buttons
    $('.view-btn').off('click').on('click', function(e) {
        e.stopPropagation();
        currentRequestId = $(this).data('id');
        loadRequestDetails(currentRequestId);
    });

    // Handle modal close events
    const modal = $('#request-details-modal');
    modal.find('.close').off('click').on('click', function() {
        modal.hide();
        resetModal(); // Reset modal to view mode when closed
    });

    $('#close-modal-btn').off('click').on('click', function() {
        modal.hide();
        resetModal(); // Reset modal to view mode when closed
    });

    // Close modal when clicking outside of it
    $(window).off('click.requestModal').on('click.requestModal', function(event) {
        if ($(event.target).is(modal)) {
            modal.hide();
            resetModal(); // Reset modal to view mode when closed
        }
    });

    // Edit button functionality
    $('#edit-modal-btn').off('click').on('click', function() {
        if (currentRequestId) {
            toggleEditMode(true); // Enable edit mode
        }
    });

    // Save button functionality
    $('#save-modal-btn').off('click').on('click', function() {
        if (currentRequestId) {
            saveRequestDetails(currentRequestId);
        }
    });

    // Cancel button functionality
    $('#cancel-modal-btn').off('click').on('click', function() {
        toggleEditMode(false); // Disable edit mode
        loadRequestDetails(currentRequestId); // Reload original data
    });
    
    // Add approve button functionality
    $('#approve-modal-btn').off('click').on('click', function() {
        if (currentRequestId) {
            updateRequestStatus(currentRequestId, 'Approved');
        }
    });
    
    // Add disapprove button functionality
    $('#disapprove-modal-btn').off('click').on('click', function() {
        if (currentRequestId) {
            updateRequestStatus(currentRequestId, 'Disapproved');
        }
    });
}

function loadRequestDetails(requestId) {
    // Show loading state
    $('#detail-driver-name').text('Loading...');
    $('#request-details-modal').show();

    // Load dropdown options
    loadDropdownOptions();

    // Fetch request details
    $.ajax({
        url: `/request-details/${requestId}`,
        type: "GET",
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        dataType: 'json',
        success: function(data) {
            populateModal(data);
            toggleEditMode(false); // Ensure modal is in view mode
        },
        error: function(xhr) {
            console.error('Error fetching request details:', xhr.responseText);
            $('#detail-driver-name').text('Error loading details');
            alert('Failed to load request details. Please try again.');
        }
    });
}

function populateModal(data) {
    // Populate text fields
    $('#detail-driver-name').text(data.driver_name);
    $('#detail-date').text(formatDate(data.date));
    $('#detail-type').text(data.type);
    $('#detail-quantity').text(data.quantity);
    $('#detail-government-car').text(data.government_car_used);
    $('#detail-plate-number').text(data.government_car_number);
    $('#detail-place-to-visit').text(data.place_to_visit);
    $('#detail-purpose').text(data.purpose);
    $('#detail-requested-by').text(data.requested_by);
    $('#detail-division').text(data.division);

    // Set selected values for dropdowns
    $('#edit-driver-name').val(data.driver_name);
    $('#edit-date').val(data.date.split('T')[0]); // Format date for input[type="date"]
    $('#edit-type').val(data.type);
    $('#edit-quantity').val(data.quantity);
    $('#edit-government-car').val(data.government_car_used);
    $('#edit-plate-number').val(data.government_car_number);
    $('#edit-place-to-visit').val(data.place_to_visit);
    $('#edit-purpose').val(data.purpose);
    $('#edit-requested-by').val(data.requested_by);
    $('#edit-division').val(data.division);

    // Apply status color
    const statusElement = $('#detail-status');
    statusElement.text(data.status);
    if (data.status.toLowerCase() === 'approved') {
        statusElement.css('color', '#28a745'); // Green color
    } else if (data.status.toLowerCase() === 'disapproved') {
        statusElement.css('color', '#dc3545'); // Red color
    } else {
        statusElement.css('color', ''); // Reset to default color
    }

    // Update approve/disapprove buttons visibility
    updateActionButtonsVisibility(data.status);
}
function applyStatusColors() {
    // Apply colors to status cells in the table
    $('.request-table tbody tr').each(function() {
        const statusCell = $(this).find('td:nth-child(7)'); // 7th column is the status column
        const status = statusCell.text().trim().toLowerCase();
        
        if (status === 'approved') {
            statusCell.css('color', '#28a745'); // Green color
        } else if (status === 'disapproved') {
            statusCell.css('color', '#dc3545'); // Red color
        }
    });
}

function updateActionButtonsVisibility(status) {
    const disabledStatuses = ['approved', 'disapproved']; // Add statuses where buttons should be disabled
    $('#approve-modal-btn, #disapprove-modal-btn').show(); // Always show buttons

    if (disabledStatuses.includes(status.toLowerCase())) {
        $('#approve-modal-btn, #disapprove-modal-btn').prop('disabled', true); // Disable buttons for specific statuses
    } else {
        $('#approve-modal-btn, #disapprove-modal-btn').prop('disabled', false); // Enable buttons for other statuses
    }
}

function toggleEditMode(enable) {
    isEditMode = enable;
    if (enable) {
        // Hide view elements, show edit elements
        $('.detail-group p').hide();
        $('.detail-group input, .detail-group select').show();
        $('#edit-modal-btn').hide();
        $('#save-modal-btn, #cancel-modal-btn').show();
        // Hide approve/disapprove buttons in edit mode
        $('#approve-modal-btn, #disapprove-modal-btn').hide();
    } else {
        // Hide edit elements, show view elements
        $('.detail-group p').show();
        $('.detail-group input, .detail-group select').hide();
        $('#edit-modal-btn').show();
        $('#save-modal-btn, #cancel-modal-btn').hide();
        // Show approve/disapprove buttons based on status
        const status = $('#detail-status').text();
        updateActionButtonsVisibility(status);
    }
}

function saveRequestDetails(requestId) {
    const updatedData = {
        driver_name: $('#edit-driver-name').val(),
        date: $('#edit-date').val(),
        type: $('#edit-type').val(),
        quantity: $('#edit-quantity').val(),
        government_car_used: $('#edit-government-car').val(),
        government_car_number: $('#edit-plate-number').val(),
        place_to_visit: $('#edit-place-to-visit').val(),
        purpose: $('#edit-purpose').val(),
        requested_by: $('#edit-requested-by').val(),
        division: $('#edit-division').val(),
        _token: $('meta[name="csrf-token"]').attr('content') // Add CSRF token
    };

    $.ajax({
        url: `/update-request-details/${requestId}`,
        type: "POST",
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        data: updatedData,
        success: function(response) {
            alert('Request details updated successfully!');
            loadRequestDetails(requestId); // Reload details
            toggleEditMode(false); // Switch back to view mode
            refreshTableData(); // Refresh the table
        },
        error: function(xhr) {
            console.error('Error updating request details:', xhr.responseText);
            alert('Failed to update request details. Please try again.');
        }
    });
}

function updateRequestStatus(requestId, status) {
    if (confirm(`Are you sure you want to ${status.toLowerCase()} this request?`)) {
        $.ajax({
            url: `/update-request-status/${requestId}`,
            type: "POST",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'X-Requested-With': 'XMLHttpRequest'
            },
            data: {
                status: status
            },
            success: function(response) {
                if (response.success) {
                    alert(`Request ${status.toLowerCase()} successfully!`);

                    // Update the status in the modal dynamically
                    $('#detail-status').text(status);

                    // Hide the approve/disapprove buttons if the status is no longer "pending"
                    if (status.toLowerCase() !== 'pending') {
                        $('#approve-modal-btn, #disapprove-modal-btn').hide();
                    }

                    // Refresh the table data (optional, if you want to update the table as well)
                    refreshTableData();
                } else {
                    alert('Error: ' + (response.message || `Failed to ${status.toLowerCase()} request`));
                }
            },
            error: function(xhr) {
                console.error(`Error ${status.toLowerCase()}ing request:`, xhr.responseText);
                alert(`Failed to ${status.toLowerCase()} request. Please try again.`);
            }
        });
    }
}
function loadDropdownOptions() {
    $.ajax({
        url: '/get-dropdown-options',
        type: "GET",
        success: function(data) {
            // Populate driver names
            $('#edit-driver-name').empty();
            $('#edit-driver-name').append('<option value="">Select Driver</option>');
            data.drivers.forEach(driver => {
                $('#edit-driver-name').append(`<option value="${driver.full_name}">${driver.full_name}</option>`);
            });

            // Populate government cars (equipment)
            $('#edit-government-car').empty();
            $('#edit-government-car').append('<option value="">Select Vehicle</option>');
            data.equipments.forEach(equipment => {
                $('#edit-government-car').append(`<option value="${equipment.name}">${equipment.name}</option>`);
            });

            // Populate plate numbers (units)
            $('#edit-plate-number').empty();
            $('#edit-plate-number').append('<option value="">Select Plate Number</option>');
            data.units.forEach(unit => {
                $('#edit-plate-number').append(`<option value="${unit.name}">${unit.name}</option>`);
            });

            // Populate divisions
            $('#edit-division').empty();
            $('#edit-division').append('<option value="">Select Division</option>');
            data.divisions.forEach(division => {
                $('#edit-division').append(`<option value="${division.name}">${division.name}</option>`);
            });

            // Populate types (inventory products)
            $('#edit-type').empty();
            $('#edit-type').append('<option value="">Select Type</option>');
            data.inventoryProducts.forEach(product => {
                $('#edit-type').append(`<option value="${product.product_name}">${product.product_name}</option>`);
            });
        },
        error: function(xhr) {
            console.error('Error loading dropdown options:', xhr.responseText);
        }
    });
}
function loadRequestDetails(requestId) {
    // Show loading state
    $('#detail-driver-name').text('Loading...');
    $('#request-details-modal').show();

    // Load dropdown options
    loadDropdownOptions();

    // Fetch request details
    $.ajax({
        url: `/request-details/${requestId}`,
        type: "GET",
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        dataType: 'json',
        success: function(data) {
            populateModal(data);
            toggleEditMode(false); // Ensure modal is in view mode
        },
        error: function(xhr) {
            console.error('Error fetching request details:', xhr.responseText);
            $('#detail-driver-name').text('Error loading details');
            alert('Failed to load request details. Please try again.');
        }
    });
}
function refreshTableData() {
    // Get current URL to maintain any active filters
    const currentUrl = window.location.href;
    
    $.ajax({
        url: currentUrl,
        type: "GET",
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        success: function(data) {
            $('#request-table-container').html($(data).find('#request-table-container').html());
            // Reinitialize event handlers
            initRequestDetailsModal();
            initDeleteFunctionality();
        },
        error: function(xhr) {
            console.error('Error refreshing table data:', xhr.responseText);
        }
    });
}

function resetModal() {
    toggleEditMode(false);
}

function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString('en-US', {
        month: '2-digit',
        day: '2-digit',
        year: 'numeric'
    });
}