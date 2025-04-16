/**
 * Initialize edit button functionality
 */
function initEditButtons() {
    // Remove previous event handlers to prevent duplicates
    $(document).off('click', '.edit-btn');
    
    // Add event listeners to edit buttons
    $(document).on('click', '.edit-btn', function() {
        const driverId = $(this).closest('tr').data('driver-id');
        openEditDriverModal(driverId);
    });
}

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

/**
 * Function to open edit modal and load driver data
 */
function openEditDriverModal(driverId) {
    // Show loading state
    $('#editDriverModal').show();
    
    // Set the form action
    $('#editDriverForm').attr('action', `/admin/driver/${driverId}`);
    
    // Get CSRF token
    const csrfToken = $('meta[name="csrf-token"]').attr('content');
    
    // Fetch driver data using AJAX
    $.ajax({
        url: `/admin/driver/${driverId}/edit`,
        type: 'GET',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        },
        success: function(data) {
            // Populate the form with driver data
            $('#edit_driver_id').val(data.id);
            $('#edit_first_name').val(data.first_name);
            $('#edit_last_name').val(data.last_name);
            $('#edit_position').val(data.position);
            $('#edit_status').val(data.status);
        },
        error: function(xhr, status, error) {
            console.error('Error fetching driver data:', error);
            // Show error message
            const errorMsg = $('<div>')
                .addClass('edit-error-message')
                .text('Failed to load driver data. Please try again.');
            $('#editDriverForm').prepend(errorMsg);
        }
    });
}

// Initialize when document is ready
$(document).ready(function() {
    // Initialize both edit buttons and pagination
    initEditButtons();
    initPagination();
    
    // Set up event handler for edit form submission
    $('#editDriverForm').on('submit', function(e) {
        e.preventDefault();
        
        // Remove any existing messages
        $('.edit-success-message, .edit-error-message').remove();
        
        // Get form data
        const formData = new FormData(this);
        const driverId = $('#edit_driver_id').val();
        const csrfToken = $('meta[name="csrf-token"]').attr('content');
        
        // Submit form data with AJAX
        $.ajax({
            url: `/admin/driver/${driverId}`,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            success: function(data) {
                // Show success message
                const successMsg = $('<div>')
                    .addClass('edit-success-message')
                    .text('Driver updated successfully!');
                $('#editDriverForm').prepend(successMsg);
                
                // Update the table row with new data
                updateDriverRow(driverId, data.driver);
                
                // Close modal after 1.5 seconds
                setTimeout(closeEditModal, 1500);
            },
            error: function(xhr, status, error) {
                console.error('Error updating driver:', error);
                let errorMessage = 'Failed to update driver. Please try again.';
                
                // Try to extract error message from response
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                
                // Show error message
                const errorMsg = $('<div>')
                    .addClass('edit-error-message')
                    .text(errorMessage);
                $('#editDriverForm').prepend(errorMsg);
            }
        });
    });
    
    // Set up close modal functionality
    function closeEditModal() {
        $('#editDriverModal').hide();
        // Reset form
        $('#editDriverForm')[0].reset();
        // Remove any success or error messages
        $('.edit-success-message, .edit-error-message').remove();
    }
    
    // Close modal when clicking on X or Cancel
    $('.edit-close, .edit-cancel-btn').on('click', closeEditModal);
    
    // Close modal when clicking outside the modal content
    $(window).on('click', function(event) {
        if (event.target === $('#editDriverModal')[0]) {
            closeEditModal();
        }
    });
    
    // Function to update the table row with new data
    function updateDriverRow(driverId, driver) {
        const $row = $(`tr[data-driver-id="${driverId}"]`);
        if ($row.length) {
            // Update the name and position cells
            $row.find('td:eq(0)').text(`${driver.first_name} ${driver.last_name}`);
            $row.find('td:eq(1)').text(driver.position);
            
            // Handle status indicator
            if (driver.status === 'inactive') {
                $row.addClass('inactive-driver');
            } else {
                $row.removeClass('inactive-driver');
            }
        }
    }
    
    // Reinitialize components after any dynamic content updates
    $(document).on('paginationComplete', function() {
        initPagination();
        initEditButtons();
    });
});