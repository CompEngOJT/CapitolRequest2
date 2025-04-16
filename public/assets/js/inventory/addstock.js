// Add this script at the end of your body tag or in a separate JS file
document.addEventListener('DOMContentLoaded', function() {
  // Get dialog elements with new IDs and class names
  const stockDialog = document.getElementById('addStockDialog');
  const addStockBtn = document.getElementById('add-stock-btn');
  const closeDialog = document.querySelector('.close-stock-dialog');
  const cancelBtn = document.querySelector('.stock-cancel-btn');
  const addStockForm = document.getElementById('add-stock-form');
  
  // Open dialog
  addStockBtn.addEventListener('click', function() {
    stockDialog.style.display = 'block';
    
    // Clear any previous success/error messages
    const existingAlert = stockDialog.querySelector('.alert');
    if (existingAlert) {
      existingAlert.remove();
    }
  });
  
  // Close dialog functions
  function closeDialogFunction() {
    stockDialog.style.display = 'none';
    addStockForm.reset();
    
    // Remove any alert messages
    const alertEl = stockDialog.querySelector('.alert');
    if (alertEl) {
      alertEl.remove();
    }
  }
  
  // Close dialog when clicking X or outside the dialog
  closeDialog.addEventListener('click', closeDialogFunction);
  window.addEventListener('click', function(event) {
    if (event.target == stockDialog) {
      closeDialogFunction();
    }
  });
  
  // Cancel button just resets the form but keeps the dialog open
  cancelBtn.addEventListener('click', function() {
    addStockForm.reset();
    
    // Remove any alert messages
    const alertEl = stockDialog.querySelector('.alert');
    if (alertEl) {
      alertEl.remove();
    }
  });
  
  // Form submission
  addStockForm.addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Show loading state
    const submitBtn = document.querySelector('.stock-submit-btn');
    const originalBtnText = submitBtn.innerHTML;
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Adding...';
    
    // Remove any existing alerts
    const existingAlert = stockDialog.querySelector('.alert');
    if (existingAlert) {
      existingAlert.remove();
    }
    
    // Get form values
    const product = document.getElementById('stockProduct').value;
    const quantity = document.getElementById('stockQuantity').value;
    
    // AJAX call to add stock
    fetch('/inventory/add-stock', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
      },
      body: JSON.stringify({
        product: product,
        quantity: quantity
      })
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        // Create success message within the dialog
        const successMessage = document.createElement('div');
        successMessage.className = 'alert alert-success';
        successMessage.innerHTML = `
          <i class="fas fa-check-circle"></i> 
          Stock added successfully! New total: ${data.new_total}, Remaining: ${data.new_remaining}
        `;
        
        // Insert the success message at the top of the form
        const dialogBody = document.querySelector('.stock-dialog-body');
        dialogBody.insertBefore(successMessage, addStockForm);
        
        // Auto remove the success message after 5 seconds
        setTimeout(() => {
          if (successMessage.parentNode) {
            successMessage.classList.add('fade-out');
            
            // Wait for fade animation to complete before removing
            setTimeout(() => {
              if (successMessage.parentNode) {
                successMessage.remove();
              }
            }, 300);
          }
        }, 5000);
        
        // Reset the form fields but keep dialog open
        addStockForm.reset();
        
        // Update the table with AJAX
        updateTableData();
      } else {
        // Create error message within the dialog
        const errorMessage = document.createElement('div');
        errorMessage.className = 'alert alert-danger';
        errorMessage.innerHTML = `
          <i class="fas fa-exclamation-circle"></i> 
          Error: ${data.message}
        `;
        
        // Insert the error message at the top of the form
        const dialogBody = document.querySelector('.stock-dialog-body');
        dialogBody.insertBefore(errorMessage, addStockForm);
        
        // Auto remove the error message after 5 seconds
        setTimeout(() => {
          if (errorMessage.parentNode) {
            errorMessage.classList.add('fade-out');
            
            // Wait for fade animation to complete before removing
            setTimeout(() => {
              if (errorMessage.parentNode) {
                errorMessage.remove();
              }
            }, 300);
          }
        }, 5000);
      }
    })
    .catch(error => {
      console.error('Error:', error);
      
      // Create error message within the dialog
      const errorMessage = document.createElement('div');
      errorMessage.className = 'alert alert-danger';
      errorMessage.innerHTML = `
        <i class="fas fa-exclamation-circle"></i> 
        An error occurred while adding stock.
      `;
      
      // Insert the error message at the top of the form
      const dialogBody = document.querySelector('.stock-dialog-body');
      dialogBody.insertBefore(errorMessage, addStockForm);
      
      // Auto remove the error message after 5 seconds
      setTimeout(() => {
        if (errorMessage.parentNode) {
          errorMessage.classList.add('fade-out');
          
          // Wait for fade animation to complete before removing
          setTimeout(() => {
            if (errorMessage.parentNode) {
              errorMessage.remove();
            }
          }, 300);
        }
      }, 5000);
    })
    .finally(() => {
      // Reset button state
      submitBtn.disabled = false;
      submitBtn.innerHTML = originalBtnText;
    });
  });

  // Function to update table data via AJAX
  function updateTableData() {
    // Get the current page from the URL if available
    const urlParams = new URLSearchParams(window.location.search);
    const page = urlParams.get('page') || 1;
    
    // Fetch updated table data
    fetch(`/inventory?page=${page}`, {
      headers: {
        'X-Requested-With': 'XMLHttpRequest'
      }
    })
    .then(response => response.text())
    .then(html => {
      // Get table body element
      const tableBody = document.getElementById('table-body');
      const tempDiv = document.createElement('div');
      tempDiv.innerHTML = html;
      
      // Extract the new table body from the response
      const newTableBody = tempDiv.querySelector('#table-body');
      
      if (newTableBody) {
        // Update the table body with new data
        tableBody.innerHTML = newTableBody.innerHTML;
        
        // Also update pagination if it exists
        const paginationContainer = document.querySelector('.pagination-container .pagination');
        const newPaginationContainer = tempDiv.querySelector('.pagination-container .pagination');
        
        if (paginationContainer && newPaginationContainer) {
          paginationContainer.innerHTML = newPaginationContainer.innerHTML;
        }
      }
    })
    .catch(error => {
      console.error('Error updating table:', error);
    });
  }
});