document.addEventListener('DOMContentLoaded', function() {
    // Get DOM elements
    const addStockButton = document.querySelector('.add-stock-button');
    const addStockPopup = document.getElementById('addStockPopup');
    const closePopupBtn = document.querySelector('.close-popup-stock');
    const cancelBtn = document.querySelector('.cancel-btn-stock');
    const productSelect = document.getElementById('stock_product_name');
    const currentStockRemaining = document.getElementById('current_stock_remaining');
    const stockAmountInput = document.getElementById('stock_amount');
    const submitAddStockBtn = document.getElementById('submitAddStock');
    
    // Function to open the popup
    function openAddStockPopup() {
        addStockPopup.style.display = 'flex';
        loadProducts();
    }
    
    // Function to close the popup
    function closeAddStockPopup() {
        addStockPopup.style.display = 'none';
        resetForm();
    }
    
    // Function to reset the form
    function resetForm() {
        productSelect.value = '';
        currentStockRemaining.textContent = '-';
        stockAmountInput.value = '';
    }
    
    // Function to load products into dropdown
    function loadProducts() {
        // Clear existing options except the first one
        while (productSelect.options.length > 1) {
            productSelect.remove(1);
        }
        
        // Fetch products from the database
        fetch('/admin/inventory/products')
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok: ' + response.statusText);
                }
                return response.json();
            })
            .then(data => {
                if (Array.isArray(data)) {
                    data.forEach(product => {
                        const option = document.createElement('option');
                        option.value = product.id;
                        option.textContent = product.product_name;
                        productSelect.appendChild(option);
                    });
                } else {
                    console.error('Expected array of products but got:', data);
                }
            })
            .catch(error => {
                console.error('Error loading products:', error);
                alert('Failed to load products. Please check the console for details.');
            });
    }
    
    // Function to load product stock information
    function loadProductStockInfo(productId) {
        if (!productId) {
            currentStockRemaining.textContent = '-';
            return;
        }
        
        fetch(`/admin/inventory/products/${productId}/stock`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok: ' + response.statusText);
                }
                return response.json();
            })
            .then(data => {
                currentStockRemaining.textContent = data.stock_remaining !== undefined ? data.stock_remaining : '0';
            })
            .catch(error => {
                console.error('Error loading product stock info:', error);
                currentStockRemaining.textContent = 'Error';
            });
    }
    
    // Function to add stock
    function addStock(productId, amount) {
        fetch('/admin/inventory/stock/add', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                product_id: productId,
                stock_amount: amount
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Stock added successfully!');
                closeAddStockPopup();
                // Refresh the inventory table
                document.querySelector('.refresh-btn').click();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error adding stock:', error);
            alert('An error occurred while adding stock.');
        });
    }
    
    // Add event listeners
    addStockButton.addEventListener('click', openAddStockPopup);
    closePopupBtn.addEventListener('click', closeAddStockPopup);
    cancelBtn.addEventListener('click', closeAddStockPopup);
    
    productSelect.addEventListener('change', function() {
        loadProductStockInfo(this.value);
    });
    
    submitAddStockBtn.addEventListener('click', function() {
        const productId = productSelect.value;
        const stockAmount = parseInt(stockAmountInput.value);
        
        if (!productId) {
            alert('Please select a product.');
            return;
        }
        
        if (!stockAmount || stockAmount <= 0) {
            alert('Please enter a valid stock amount.');
            return;
        }
        
        addStock(productId, stockAmount);
    });
    
    // Close popup if clicked outside
    window.addEventListener('click', function(event) {
        if (event.target === addStockPopup) {
            closeAddStockPopup();
        }
    });
});