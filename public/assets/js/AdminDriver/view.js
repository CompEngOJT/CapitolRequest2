document.addEventListener('DOMContentLoaded', function() {
    // Class to handle Driver Consumption Report functionality
    class DriverConsumptionReport {
        constructor() {
            // DOM elements
            this.modal = document.getElementById('driverConsumptionModal');
            this.productsSection = document.getElementById('productsMainSection');
            this.detailsSection = document.getElementById('productDetailsSection');
            this.productList = document.getElementById('consumptionProductList');
            this.requestsList = document.getElementById('productRequestsList');
            this.driverNameDisplay = document.getElementById('driverNameDisplay');
            this.productNameDisplay = document.getElementById('productNameDisplay');
            this.driverInfoElement = document.getElementById('consumptionDriverInfo');
            this.requestPagination = document.getElementById('requestPagination');
            this.startDateInput = document.getElementById('consumptionStartDate');
            this.endDateInput = document.getElementById('consumptionEndDate');
            this.applyFiltersBtn = document.getElementById('applyDateFiltersBtn');
            this.backToProductsBtn = document.getElementById('backToProductsBtn');

            // Initialize state
            this.currentProductPage = 1;
            this.currentRequestPage = 1;
            this.productsPerPage = 4;
            this.allProductsData = [];
            
            // Initialize the component
            this.initialize();
        }
        
        initialize() {
            if (!this.modal) {
                console.error('Consumption modal not found in the DOM');
                return;
            }
            
            this.setupModalEvents();
            this.setupBackButton();
            this.setupViewButtonsDelegation();
        }
        
        setupModalEvents() {
            // Close button in header
            const closeBtn = this.modal.querySelector('.close');
            if (closeBtn) {
                closeBtn.addEventListener('click', () => this.closeModal());
            }
            
            // Close button in footer
            const closeBtnFooter = this.modal.querySelector('.consumption-close-btn');
            if (closeBtnFooter) {
                closeBtnFooter.addEventListener('click', () => this.closeModal());
            }
            
            // Close when clicking outside
            window.addEventListener('click', (event) => {
                if (event.target === this.modal) {
                    this.closeModal();
                }
            });
        }
        
        closeModal() {
            this.modal.style.display = 'none';
        }
        
        setupBackButton() {
            if (this.backToProductsBtn) {
                this.backToProductsBtn.addEventListener('click', () => {
                    this.detailsSection.style.display = 'none';
                    this.productsSection.style.display = 'block';
                });
            }
        }
        
        setupViewButtonsDelegation() {
            // Use document as the delegation parent
            document.addEventListener('click', (e) => {
                // Find closest .view-btn if the event target is the button or an icon inside it
                const viewBtn = e.target.closest('.view-btn');
                
                if (!viewBtn) return; // Not a view button click
                
                console.log('View button clicked via delegation');
                const row = viewBtn.closest('tr');
                if (!row) {
                    console.error('Could not find parent row');
                    return;
                }
                
                const driverId = row.dataset.driverId;
                if (!driverId) {
                    console.error('No driver ID found in the row');
                    return;
                }
                
                console.log('Viewing driver with ID:', driverId);
                const driverName = row.querySelector('td:first-child').textContent;
                
                // Update UI
                this.driverNameDisplay.textContent = driverName;
                this.driverInfoElement.dataset.driverId = driverId;
                
                // Reset view to products section
                this.productsSection.style.display = 'block';
                this.detailsSection.style.display = 'none';
                
                // Fetch consumption data
                this.fetchDriverConsumption(driverId);
                
                // Show the modal
                this.modal.style.display = 'block';
            });
        }
        
        setupDateFilters(driverId) {
            if (!this.applyFiltersBtn) {
                console.error('Apply filters button not found');
                return;
            }
            
            // Remove any existing event listeners with clone-and-replace approach
            const oldButton = this.applyFiltersBtn;
            const newButton = oldButton.cloneNode(true);
            oldButton.parentNode.replaceChild(newButton, oldButton);
            this.applyFiltersBtn = newButton;
            
            // Add new event listener
            this.applyFiltersBtn.addEventListener('click', () => {
                this.filterConsumptionByDate(
                    driverId, 
                    this.startDateInput.value, 
                    this.endDateInput.value
                );
            });
        }
        
        async fetchDriverConsumption(driverId) {
            try {
                // Show loading state
                this.productList.innerHTML = '<p>Loading consumption data...</p>';
                
                const data = await this.makeRequest(
                    `GET`, 
                    `/admin/driver/${driverId}/consumption`
                );
                
                console.log('Received consumption data:', data);
                
                // Store all products data
                this.allProductsData = data.consumption || [];
                
                // Reset to first page
                this.currentProductPage = 1;
                
                // Display the consumption data with pagination
                this.displayProductsWithPagination();
                
                // Add event listeners to date filters
                this.setupDateFilters(driverId);
            } catch (error) {
                console.error('Error fetching consumption data:', error);
                this.productList.innerHTML = 
                    `<p class="error">Error loading consumption data: ${error.message || 'Unknown server error'}</p>`;
            }
        }
        
        displayProductsWithPagination() {
            if (!this.productList) {
                console.error('Product list container not found');
                return;
            }
            
            this.productList.innerHTML = '';
            
            if (this.allProductsData.length === 0) {
                this.productList.innerHTML = '<p>No products available.</p>';
                return;
            }
            
            // Calculate start and end indices for current page
            const startIndex = (this.currentProductPage - 1) * this.productsPerPage;
            const endIndex = Math.min(startIndex + this.productsPerPage, this.allProductsData.length);
            
            // Get products for current page
            const currentPageProducts = this.allProductsData.slice(startIndex, endIndex);
            
            // Create consumption items
            currentPageProducts.forEach(item => {
                const consumptionItem = document.createElement('div');
                consumptionItem.className = 'consumption-item';
                consumptionItem.dataset.productId = item.product_id;
                
                consumptionItem.innerHTML = `
                    <div class="product-info">
                        <div class="product-name">${item.product_name}</div>
                        <div class="consumption-amount">${item.total_consumption || 0} Liters</div>
                    </div>
                    <button class="view-product-details-btn">View Details</button>
                `;
                
                this.productList.appendChild(consumptionItem);
                
                // Add click event to view product details
                const viewDetailsBtn = consumptionItem.querySelector('.view-product-details-btn');
                viewDetailsBtn.addEventListener('click', () => {
                    const productId = consumptionItem.dataset.productId;
                    const driverId = this.driverInfoElement.dataset.driverId;
                    
                    // Get date filters
                    const startDate = this.startDateInput.value;
                    const endDate = this.endDateInput.value;
                    
                    // Show product details
                    this.fetchProductRequestDetails(driverId, productId, startDate, endDate);
                });
            });
            
            // Add pagination controls
            const totalPages = Math.ceil(this.allProductsData.length / this.productsPerPage);
            
            if (totalPages > 1) {
                const paginationContainer = document.createElement('div');
                paginationContainer.className = 'pagination-controls';
                
                paginationContainer.innerHTML = `
                    <button id="prevPageBtn" ${this.currentProductPage === 1 ? 'disabled' : ''}>Previous</button>
                    <span>Page ${this.currentProductPage} of ${totalPages}</span>
                    <button id="nextPageBtn" ${this.currentProductPage === totalPages ? 'disabled' : ''}>Next</button>
                `;
                
                this.productList.appendChild(paginationContainer);
                
                // Add event listeners to pagination buttons
                document.getElementById('prevPageBtn').addEventListener('click', () => {
                    if (this.currentProductPage > 1) {
                        this.currentProductPage--;
                        this.displayProductsWithPagination();
                    }
                });
                
                document.getElementById('nextPageBtn').addEventListener('click', () => {
                    if (this.currentProductPage < totalPages) {
                        this.currentProductPage++;
                        this.displayProductsWithPagination();
                    }
                });
            }
        }
        
        async filterConsumptionByDate(driverId, startDate, endDate) {
            try {
                // Show loading state
                this.productList.innerHTML = '<p>Loading filtered data...</p>';
                
                // Prepare query parameters
                let queryParams = new URLSearchParams();
                if (startDate) queryParams.append('start_date', startDate);
                if (endDate) queryParams.append('end_date', endDate);
                
                console.log('Filtering consumption data with dates:', startDate, endDate);
                
                const data = await this.makeRequest(
                    'GET', 
                    `/admin/driver/${driverId}/consumption?${queryParams.toString()}`
                );
                
                // Store all products data
                this.allProductsData = data.consumption || [];
                
                // Reset to first page
                this.currentProductPage = 1;
                
                // Display the filtered consumption data with pagination
                this.displayProductsWithPagination();
                
                // If we're in product details view, update it
                if (this.detailsSection && this.detailsSection.style.display !== 'none') {
                    const productId = this.detailsSection.dataset.productId;
                    if (productId) {
                        this.fetchProductRequestDetails(driverId, productId, startDate, endDate);
                    }
                }
            } catch (error) {
                console.error('Error fetching filtered data:', error);
                this.productList.innerHTML = 
                    `<p class="error">Error loading filtered data: ${error.message || 'Unknown server error'}</p>`;
            }
        }
        
        async fetchProductRequestDetails(driverId, productId, startDate, endDate) {
            try {
                // Switch to details view
                this.productsSection.style.display = 'none';
                
                // Set product ID on details section for reference
                this.detailsSection.dataset.productId = productId;
                
                // Show loading
                this.requestsList.innerHTML = '<p>Loading request details...</p>';
                this.detailsSection.style.display = 'block';
                
                // Reset to first page
                this.currentRequestPage = 1;
                
                // Prepare query parameters
                let queryParams = new URLSearchParams();
                queryParams.append('page', this.currentRequestPage);
                if (startDate) queryParams.append('start_date', startDate);
                if (endDate) queryParams.append('end_date', endDate);
                
                console.log('Fetching product details with ID:', productId);
                
                const data = await this.makeRequest(
                    'GET', 
                    `/admin/driver/${driverId}/product/${productId}/requests?${queryParams.toString()}`
                );
                
                console.log('Received product request data:', data);
                
                // Display product name
                this.productNameDisplay.textContent = data.product.name;
                
                // Display request details
                this.displayProductRequests(data.requests, data.pagination);
            } catch (error) {
                console.error('Error fetching product details:', error);
                this.requestsList.innerHTML = 
                    `<p class="error">Error loading request details: ${error.message || 'Unknown server error'}</p>`;
            }
        }
        
        displayProductRequests(requests, pagination) {
            this.requestsList.innerHTML = '';
            
            if (requests.length === 0) {
                this.requestsList.innerHTML = '<p>No request records found for this product.</p>';
                this.requestPagination.innerHTML = '';
                return;
            }
            
            // Create table for requests
            const table = document.createElement('table');
            table.className = 'requests-table';
            
            // Add header
            table.innerHTML = `
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Quantity</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody></tbody>
            `;
            
            // Add rows
            const tbody = table.querySelector('tbody');
            requests.forEach(request => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${request.date}</td>
                    <td>${request.time}</td>
                    <td>${request.quantity} Liters</td>
                    <td><span class="status-badge status-${request.status.toLowerCase()}">${request.status}</span></td>
                `;
                tbody.appendChild(row);
            });
            
            this.requestsList.appendChild(table);
            
            // Update pagination controls
            if (pagination.last_page > 1) {
                this.requestPagination.innerHTML = `
                    <button id="prevRequestPageBtn" ${pagination.current_page === 1 ? 'disabled' : ''}>Previous</button>
                    <span>Page ${pagination.current_page} of ${pagination.last_page}</span>
                    <button id="nextRequestPageBtn" ${pagination.current_page === pagination.last_page ? 'disabled' : ''}>Next</button>
                `;
                
                // Add event listeners to pagination buttons
                document.getElementById('prevRequestPageBtn').addEventListener('click', () => {
                    if (pagination.current_page > 1) {
                        this.loadRequestPage(pagination.current_page - 1);
                    }
                });
                
                document.getElementById('nextRequestPageBtn').addEventListener('click', () => {
                    if (pagination.current_page < pagination.last_page) {
                        this.loadRequestPage(pagination.current_page + 1);
                    }
                });
            } else {
                this.requestPagination.innerHTML = '';
            }
        }
        
        async loadRequestPage(page) {
            try {
                this.currentRequestPage = page;
                
                const productId = this.detailsSection.dataset.productId;
                const driverId = this.driverInfoElement.dataset.driverId;
                
                // Get date filters
                const startDate = this.startDateInput.value;
                const endDate = this.endDateInput.value;
                
                // Prepare query parameters
                let queryParams = new URLSearchParams();
                queryParams.append('page', page);
                if (startDate) queryParams.append('start_date', startDate);
                if (endDate) queryParams.append('end_date', endDate);
                
                // Show loading
                this.requestsList.innerHTML = '<p>Loading request details...</p>';
                
                const data = await this.makeRequest(
                    'GET', 
                    `/admin/driver/${driverId}/product/${productId}/requests?${queryParams.toString()}`
                );
                
                // Display updated request details
                this.displayProductRequests(data.requests, data.pagination);
            } catch (error) {
                console.error('Error fetching product details page:', error);
                this.requestsList.innerHTML = 
                    `<p class="error">Error loading request details: ${error.message || 'Unknown server error'}</p>`;
            }
        }
        
        // Utility method for making AJAX requests
        async makeRequest(method, url, data = null) {
            // Get CSRF token
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            
            const options = {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json'
                }
            };
            
            if (data && method !== 'GET') {
                options.body = JSON.stringify(data);
            }
            
            const response = await fetch(url, options);
            
            if (!response.ok) {
                throw new Error(`Server responded with status: ${response.status}`);
            }
            
            const responseData = await response.json();
            
            if (!responseData.success) {
                throw new Error(responseData.message || 'Unknown error occurred');
            }
            
            return responseData;
        }
    }
    
    // === Pagination handling ===
    function initPagination() {
        $(document).off('click', '.pagination a').on('click', '.pagination a', handlePagination);
    }
    
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
    
    // Create search functionality 
    function setupSearch() {
        const searchInput = document.getElementById('driverSearch');
        if (!searchInput) return;
        
        // Debounce function to limit API calls during typing
        function debounce(func, wait) {
            let timeout;
            return function(...args) {
                clearTimeout(timeout);
                timeout = setTimeout(() => func.apply(this, args), wait);
            };
        }
        
        // Perform search when user types
        searchInput.addEventListener('input', debounce(function() {
            const searchTerm = this.value.trim();
            
            // Get current URL and add search parameter
            const currentUrl = new URL(window.location.href);
            
            if (searchTerm) {
                currentUrl.searchParams.set('search', searchTerm);
            } else {
                currentUrl.searchParams.delete('search');
            }
            
            // Show loading indicator
            $('.table-container').addClass('loading');
            
            // Make AJAX request to get filtered results
            $.ajax({
                url: currentUrl.toString(),
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
                    window.history.pushState({}, '', currentUrl.toString());
                    
                    // Remove loading indicator
                    $('.table-container').removeClass('loading');
                },
                error: function(xhr) {
                    console.error('Error searching driver data:', xhr.responseText);
                    $('.table-container').removeClass('loading');
                }
            });
        }, 500)); // 500ms debounce
    }
    
    // Initialize everything when document is ready
    $(document).ready(function() {
        // Initialize the driver consumption report functionality
        const driverConsumption = new DriverConsumptionReport();
        
        // Initialize pagination
        initPagination();
        
        // Initialize search
        setupSearch();
        
        // Reinitialize pagination after any dynamic content updates
        $(document).on('paginationComplete', function() {
            initPagination();
            // No need to reinitialize view buttons as they now use event delegation
        });
        
        // Setup delete button handlers with event delegation
        $(document).on('click', '.delete-btn-small', function() {
            const row = $(this).closest('tr');
            const driverId = row.data('driver-id');
            
            if (confirm('Are you sure you want to delete this driver?')) {
                // Show loading state
                row.addClass('deleting');
                
                // Get CSRF token
                const token = $('meta[name="csrf-token"]').attr('content');
                
                // Send delete request
                $.ajax({
                    url: `/admin/driver/${driverId}`,
                    type: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': token
                    },
                    success: function(response) {
                        if (response.success) {
                            // Remove the row with animation
                            row.fadeOut(300, function() {
                                $(this).remove();
                            });
                        } else {
                            alert(response.message || 'Failed to delete driver');
                            row.removeClass('deleting');
                        }
                    },
                    error: function(xhr) {
                        console.error('Error deleting driver:', xhr.responseText);
                        alert('Error deleting driver!');
                        row.removeClass('deleting');
                    }
                });
            }
        });
        
        // New driver button handler
        $('.new-driver-btn').on('click', function() {
            // Redirect to the new driver form or show a modal
            window.location.href = '/admin/driver/create';
        });
    });
});