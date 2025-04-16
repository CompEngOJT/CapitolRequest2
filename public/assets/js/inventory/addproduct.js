document.addEventListener('DOMContentLoaded', function() {
    // DOM Elements
    const addProductBtn = document.getElementById('add-product-btn');
    const addProductDialog = document.getElementById('addProductDialog');
    const closeProductDialog = document.querySelector('.close-product-dialog');
    const productCancelBtn = document.querySelector('.product-cancel-btn');
    const addProductForm = document.getElementById('add-product-form');
    const productDialogAlert = document.getElementById('productDialogAlert');
    
    // Event Listeners
    addProductBtn.addEventListener('click', openAddProductDialog);
    closeProductDialog.addEventListener('click', closeAddProductDialog);
    productCancelBtn.addEventListener('click', closeAddProductDialog);
    addProductForm.addEventListener('submit', handleAddProductSubmit);
    
    // Close the dialog when clicking outside
    window.addEventListener('click', function(event) {
      if (event.target === addProductDialog) {
        closeAddProductDialog();
      }
    });
    
    // Functions
    function openAddProductDialog() {
      addProductDialog.style.display = 'block';
      document.body.style.overflow = 'hidden'; // Prevent scrolling behind modal
      
      // Clear the form
      addProductForm.reset();
      hideAlert();
    }
    
    function closeAddProductDialog() {
      addProductDialog.style.display = 'none';
      document.body.style.overflow = '';
      hideAlert();
    }
    
    function handleAddProductSubmit(e) {
      e.preventDefault();
      
      const productName = document.getElementById('productName').value.trim();
      const initialStock = document.getElementById('initialStock').value;
      
      // Validate input
      if (!productName) {
          showAlert('Please enter a product name', 'error');
          return;
      }
      
      if (!initialStock || initialStock < 0) {
          showAlert('Please enter a valid stock quantity', 'error');
          return;
      }
      
      // AJAX request to add product
      fetch('/products', {
          method: 'POST',
          headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
              'Accept': 'application/json'
          },
          body: JSON.stringify({
              product: productName,
              initialStock: initialStock
          })
      })
      .then(response => {
          if (!response.ok) {
              return response.json().then(err => { throw err; });
          }
          return response.json();
      })
      .then(data => {
          if (data.success) {
              showAlert(data.message || 'Product added successfully!', 'success');
              
              // Add the new product to the select dropdown in the Add Stock dialog
              const stockProductSelect = document.getElementById('stockProduct');
              const newOption = document.createElement('option');
              newOption.value = productName;
              newOption.textContent = productName;
              stockProductSelect.appendChild(newOption);
              
              // Clear the form and close dialog after success
              setTimeout(() => {
                  addProductForm.reset();
                  closeAddProductDialog();
                  window.location.reload(); // Refresh to show new product
              }, 1500);
          } else {
              showAlert(data.message || 'Failed to add product', 'error');
          }
      })
      .catch(error => {
          console.error('Error:', error);
          showAlert(error.message || 'An error occurred while adding the product', 'error');
      });
  }
    
    function addProductToTable(productName, initialStock) {
      const tableBody = document.getElementById('table-body');
      const noDataRow = tableBody.querySelector('td[colspan="5"]');
      
      if (noDataRow) {
        tableBody.innerHTML = '';
      }
      
      const newRow = document.createElement('tr');
      newRow.className = tableBody.children.length % 2 === 0 ? 'odd-row' : 'even-row';
      
      newRow.innerHTML = `
        <td>${productName}</td>
        <td>${initialStock}</td>
        <td>N/A</td>
        <td>${initialStock}</td>
        <td>
          <div class="action-icons">
            <button class="action-icon view-icon view-btn">
              <i class="fas fa-eye"></i>
            </button>
            <div class="action-icon delete-icon">
              <i class="fas fa-trash"></i>
            </div>
          </div>
        </td>
      `;
      
      tableBody.appendChild(newRow);
      
      // Add event listeners to the new action buttons
      const viewBtn = newRow.querySelector('.view-btn');
      const deleteIcon = newRow.querySelector('.delete-icon');
      
      viewBtn.addEventListener('click', function() {
        // You may want to connect this to your existing view functionality
      });
      
      deleteIcon.addEventListener('click', function() {
        // You may want to connect this to your existing delete functionality
      });
    }
    
    function showAlert(message, type) {
      productDialogAlert.textContent = message;
      productDialogAlert.className = `dialog-alert ${type}`;
      productDialogAlert.classList.remove('hidden');
    }
    
    function hideAlert() {
      productDialogAlert.textContent = '';
      productDialogAlert.className = 'dialog-alert hidden';
    }
  });