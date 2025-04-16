// Script for AJAX pagination and table interactions
document.addEventListener('DOMContentLoaded', function () {
    // Initialize table row hover effects
    initializeTableHoverEffects();
    
    // Initialize filter modal functionality
    initializeFilterModal();
    
    // Initialize AJAX pagination
    initializeAjaxPagination();
    
    // Apply initial row styling
    applyRowStyling();
});

// Function to handle table row hover effects
function initializeTableHoverEffects() {
    applyHoverEffects();
}

function applyHoverEffects() {
    const rows = document.querySelectorAll('.request-table tbody tr');
    
    rows.forEach(row => {
        row.addEventListener('mouseenter', () => {
            row.style.backgroundColor = '#D9E8F5'; // Light blue hover color
        });

        row.addEventListener('mouseleave', () => {
            // Reset to original alternating colors
            const isEven = Array.from(rows).indexOf(row) % 2 === 0;
            row.style.backgroundColor = isEven ? '#E9F0F6' : 'white';
        });
    });
}

// Function to apply alternating row styling
function applyRowStyling() {
    const rows = document.querySelectorAll('.request-table tbody tr');
    
    rows.forEach((row, index) => {
        const isEven = index % 2 === 0;
        row.style.backgroundColor = isEven ? '#E9F0F6' : 'white';
    });
}

// Function to initialize filter modal
function initializeFilterModal() {
    const filterModal = document.getElementById('filter-modal');
    const closeModal = document.querySelector('.close');
    const filterForm = document.getElementById('filter-form');
    const revertButton = document.getElementById('revert-button');
    const filterButtons = document.querySelectorAll('#filter-button');

    // Open modal for all filter buttons
    filterButtons.forEach(button => {
        button.addEventListener('click', () => {
            filterModal.style.display = 'flex';
        });
    });

    // Close modal
    if (closeModal) {
        closeModal.addEventListener('click', () => {
            filterModal.style.display = 'none';
        });
    }

    // Close modal when clicking outside
    window.addEventListener('click', (event) => {
        if (event.target === filterModal) {
            filterModal.style.display = 'none';
        }
    });

    // Handle form submission with AJAX
    if (filterForm) {
        filterForm.addEventListener('submit', function (e) {
            e.preventDefault(); // Prevent default form submission
            
            const formData = new FormData(this);
            const params = new URLSearchParams(formData).toString();
            
            // Update URL without page refresh
            window.history.pushState({}, '', `/history?${params}`);
            
            // Hide the modal after submitting
            filterModal.style.display = 'none';
            
            // Load filtered data via AJAX
            loadTableData(`/history/data?${params}`);
        });
    }

    // Revert button functionality with AJAX
    if (revertButton) {
        revertButton.addEventListener('click', (e) => {
            e.preventDefault();
            
            // Update URL without page refresh
            window.history.pushState({}, '', '/history');
            
            // Hide the modal after clicking revert
            filterModal.style.display = 'none';
            
            // Load unfiltered data
            loadTableData('/history/data');
        });
    }
}

// Function to initialize AJAX pagination
function initializeAjaxPagination() {
    // Add event listeners to pagination links
    document.addEventListener('click', function(e) {
        const target = e.target;
        
        // Check if the clicked element is a pagination link
        if (target.tagName === 'A' && target.closest('.pagination')) {
            e.preventDefault();
            
            const pageUrl = target.getAttribute('href');
            if (pageUrl) {
                // Update browser URL without page refresh
                window.history.pushState({}, '', pageUrl);
                
                // Load the new page data via AJAX
                // Extract the query parameters from the page URL
                const url = new URL(pageUrl, window.location.origin);
                const ajaxUrl = `/history/data${url.search}`;
                loadTableData(ajaxUrl);
            }
        }
    });
}

// Function to load table data via AJAX
function loadTableData(url) {
    fetch(url, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        // Update table body
        const tableBody = document.querySelector('.request-table tbody');
        if (tableBody) {
            tableBody.innerHTML = '';
            
            if (data.requests && data.requests.data && data.requests.data.length > 0) {
                // Populate with request data
                data.requests.data.forEach(request => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${request.date || ''}</td>
                        <td>${request.government_car_number || ''}</td>
                        <td>${request.driver_name || ''}</td>
                        <td>${request.type || ''}</td>
                        <td>${request.division || ''}</td>
                        <td>${request.quantity || ''}</td>
                        <td>${request.status || ''}</td>
                    `;
                    tableBody.appendChild(row);
                });
            } else {
                // Create empty rows if no data
                const emptyRow = document.createElement('tr');
                emptyRow.innerHTML = '<td colspan="7" class="text-center">No data available</td>';
                tableBody.appendChild(emptyRow);
            }
            
            // Update pagination links only if we have more than 10 results
            const paginationContainer = document.querySelector('.pagination');
            if (paginationContainer) {
                if (data.requests && data.requests.total > data.requests.per_page) {
                    paginationContainer.innerHTML = data.pagination;
                    paginationContainer.style.display = 'flex'; // or whatever your display style is
                } else {
                    paginationContainer.innerHTML = '';
                    paginationContainer.style.display = 'none';
                }
            }
            
            // Reapply styling and hover effects
            applyRowStyling();
            applyHoverEffects();
        }
    })
    .catch(error => {
        console.error('Error loading data:', error);
        // Display error message in table
        const tableBody = document.querySelector('.request-table tbody');
        if (tableBody) {
            tableBody.innerHTML = `<tr><td colspan="7" class="text-center text-danger">Error loading data. Please try again.</td></tr>`;
        }
    });
}