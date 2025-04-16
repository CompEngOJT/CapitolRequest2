document.addEventListener('DOMContentLoaded', function() {
    // Initialize the view details modal
    initViewDetailsModal();
    
    // Listen for pagination events to reattach view button handlers
    document.addEventListener('paginationComplete', function() {
        initViewDetailsModal();
    });
});

function initViewDetailsModal() {
    const modal = document.getElementById("viewDetailsModal");
    const closeBtn = document.querySelector(".close-modal");
    let currentProductName = '';
    let currentPage = 1;

    if (closeBtn) {
        closeBtn.onclick = function() {
            modal.style.display = "none";
        }
    }

    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }

    // Date filter buttons
    const applyDateFilterBtn = document.getElementById('applyDateFilter');
    const resetDateFilterBtn = document.getElementById('resetDateFilter');
    
    if (applyDateFilterBtn) {
        applyDateFilterBtn.addEventListener('click', function() {
            currentPage = 1;
            fetchWithdrawalRequests(currentProductName, currentPage);
        });
    }
    
    if (resetDateFilterBtn) {
        resetDateFilterBtn.addEventListener('click', function() {
            document.getElementById('startDate').value = '';
            document.getElementById('endDate').value = '';
            currentPage = 1;
            fetchWithdrawalRequests(currentProductName, currentPage);
        });
    }
    
    // Pagination buttons
    const prevPageBtn = document.getElementById('prevPageBtn');
    const nextPageBtn = document.getElementById('nextPageBtn');
    
    if (prevPageBtn) {
        prevPageBtn.addEventListener('click', function() {
            if (currentPage > 1) {
                currentPage--;
                fetchWithdrawalRequests(currentProductName, currentPage);
            }
        });
    }
    
    if (nextPageBtn) {
        nextPageBtn.addEventListener('click', function() {
            const totalPages = parseInt(document.getElementById('totalPages').textContent);
            if (currentPage < totalPages) {
                currentPage++;
                fetchWithdrawalRequests(currentProductName, currentPage);
            }
        });
    }

    // Remove previous event listeners from view buttons to prevent duplicates
    document.querySelectorAll('.view-btn').forEach(button => {
        button.replaceWith(button.cloneNode(true));
    });

    // Add event listeners to new view buttons
    document.querySelectorAll('.view-btn').forEach(function(button) {
        button.addEventListener('click', function() {
            const row = this.closest('tr');
            const productName = row.querySelector('td:first-child').textContent;
            const totalStocks = row.querySelector('td:nth-child(2)').textContent;
            const lastWithdrawal = row.querySelector('td:nth-child(3)').textContent;
            const stockRemaining = row.querySelector('td:nth-child(4)').textContent;

            currentProductName = productName;
            currentPage = 1;

            document.getElementById('modalProductName').textContent = productName + " Details";
            document.getElementById('modalTotalStocks').textContent = totalStocks;
            document.getElementById('modalLastWithdrawal').textContent = lastWithdrawal;
            document.getElementById('modalStockRemaining').textContent = stockRemaining;

            // Clear date filters when opening a new product
            document.getElementById('startDate').value = '';
            document.getElementById('endDate').value = '';

            document.getElementById('loadingRequests').style.display = 'block';
            document.getElementById('withdrawalRequestsBody').innerHTML = '';
            document.getElementById('noRequestsMessage').classList.add('hidden');

            modal.style.display = "block";
            fetchWithdrawalRequests(productName, currentPage);
        });
    });
}

function fetchWithdrawalRequests(productName, page = 1) {
    const tokenElement = document.querySelector('meta[name="csrf-token"]');
    const token = tokenElement ? tokenElement.getAttribute('content') : '';
    
    const startDate = document.getElementById('startDate').value;
    const endDate = document.getElementById('endDate').value;

    document.getElementById('loadingRequests').style.display = 'block';
    document.getElementById('noRequestsMessage').classList.add('hidden');
    document.getElementById('withdrawalRequestsBody').innerHTML = '';

    // Update pagination display
    document.getElementById('currentPage').textContent = page;

    console.log('Sending request:', {
        product_name: productName,
        page: page,
        start_date: startDate,
        end_date: endDate
    });

    fetch('/inventory/withdrawal-details', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': token
        },
        body: JSON.stringify({
            product_name: productName,
            page: page,
            start_date: startDate,
            end_date: endDate
        })
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! Status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        document.getElementById('loadingRequests').style.display = 'none';
        console.log('Received data:', data);

        if (data.success) {
            // Update pagination info
            document.getElementById('totalPages').textContent = data.pagination.total_pages;
            document.getElementById('currentPage').textContent = data.pagination.current_page;
            
            // Enable/disable pagination buttons
            const prevBtn = document.getElementById('prevPageBtn');
            const nextBtn = document.getElementById('nextPageBtn');
            
            if (prevBtn) {
                prevBtn.disabled = data.pagination.current_page <= 1;
            }
            
            if (nextBtn) {
                nextBtn.disabled = data.pagination.current_page >= data.pagination.total_pages;
            }

            if (data.requests && data.requests.length > 0) {
                const tbody = document.getElementById('withdrawalRequestsBody');
                tbody.innerHTML = '';

                data.requests.forEach(request => {
                    const row = document.createElement('tr');
                    
                    row.innerHTML = `
                        <td>${request.created_at}</td>
                        <td>${request.government_car_number || 'N/A'}</td>
                        <td>${request.driver_name}</td>
                        <td>${request.stock_before}</td>
                        <td>${request.quantity}</td>
                        <td>${request.stock_after}</td>
                    `;
                    tbody.appendChild(row);
                });
            } else {
                document.getElementById('noRequestsMessage').classList.remove('hidden');
            }
        } else {
            console.error('Error in response:', data.message);
            document.getElementById('noRequestsMessage').classList.remove('hidden');
            document.getElementById('noRequestsMessage').textContent = 'Error: ' + data.message;
        }
    })
    .catch(error => {
        console.error('Detailed fetch error:', error);
        document.getElementById('loadingRequests').style.display = 'none';
        document.getElementById('noRequestsMessage').classList.remove('hidden');
        document.getElementById('noRequestsMessage').textContent = 'Error loading requests: ' + error.message;
    });
}