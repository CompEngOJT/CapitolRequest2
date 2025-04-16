function initAddProductPopup() {
    // Get the popup elements
    const addProductPopup = document.getElementById('addProductPopup');
    const addProductBtn = document.querySelector('.add-product-button');
    const closePopupBtn = document.querySelector('.close-popup');
    const cancelBtn = document.querySelector('.cancel-btn');
    const addProductForm = document.getElementById('addProductForm');

    // Function to open the popup
    function openPopup() {
        addProductPopup.style.display = 'flex';
    }

    // Function to close the popup
    function closePopup() {
        addProductPopup.style.display = 'none';
        addProductForm.reset(); // Reset the form when closing
    }

    // Event listeners for opening and closing the popup
    addProductBtn.addEventListener('click', openPopup);
    closePopupBtn.addEventListener('click', closePopup);
    cancelBtn.addEventListener('click', closePopup);

    // Close popup when clicking outside of it
    window.addEventListener('click', function(event) {
        if (event.target === addProductPopup) {
            closePopup();
        }
    });

    // Handle form submission with AJAX
    addProductForm.addEventListener('submit', function(e) {
        e.preventDefault(); // Prevent normal form submission
        
        // Get form data
        const formData = new FormData(addProductForm);
        
        // Get CSRF token from the meta tag
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        // Send AJAX request
        $.ajax({
            url: addProductForm.getAttribute('action'),
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': token
            },
            success: function(response) {
                // Show success message
                alert('Product added successfully!');
                
                // Close the popup
                closePopup();
                
                // Refresh the table to show the new product
                refreshTable();
            },
            error: function(xhr) {
                // Handle errors
                let errorMessage = 'Failed to add product';
                
                if (xhr.responseJSON && xhr.responseJSON.errors) {
                    // Get the first error message
                    const errors = xhr.responseJSON.errors;
                    errorMessage = Object.values(errors)[0][0];
                }
                
                console.error('Error:', errorMessage);
                alert('Error: ' + errorMessage);
            }
        });
    });
    
    // Function to refresh the table
    function refreshTable() {
        $.ajax({
            url: window.location.href,
            type: 'GET',
            success: function(response) {
                // Extract the table HTML from the response
                const parser = new DOMParser();
                const doc = parser.parseFromString(response, 'text/html');
                const newTable = doc.querySelector('.table-container').innerHTML;
                const newPagination = doc.querySelector('.pagination-container').innerHTML;
                
                // Update the table and pagination
                document.querySelector('.table-container').innerHTML = newTable;
                document.querySelector('.pagination-container').innerHTML = newPagination;
                
                // Reinitialize pagination
                initPagination();
            }
        });
    }
}