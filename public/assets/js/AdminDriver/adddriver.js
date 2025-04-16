document.addEventListener('DOMContentLoaded', function() {
    // DOM Elements
    const newDriverBtn = document.querySelector('.new-driver-btn');
    const addDriverModal = document.getElementById('addDriverModal');
    const closeBtn = addDriverModal.querySelector('.close');
    const cancelBtn = addDriverModal.querySelector('.cancel-btn');
    const addDriverForm = document.getElementById('addDriverForm');
    
    // Open modal when clicking "New Driver" button
    newDriverBtn.addEventListener('click', function() {
        addDriverModal.style.display = 'block';
        // Reset form when opening
        addDriverForm.reset();
    });
    
    // Close modal when clicking the X button
    closeBtn.addEventListener('click', function() {
        addDriverModal.style.display = 'none';
    });
    
    // Close modal when clicking the Cancel button
    cancelBtn.addEventListener('click', function() {
        addDriverModal.style.display = 'none';
    });
    
    // Close modal when clicking outside the modal content
    window.addEventListener('click', function(event) {
        if (event.target === addDriverModal) {
            addDriverModal.style.display = 'none';
        }
    });
    
    // Form submission using AJAX
    addDriverForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(addDriverForm);
        
        fetch(addDriverForm.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                // Show success message
                alert('Driver added successfully!');
                
                // Close the modal
                addDriverModal.style.display = 'none';
                
                // Refresh the page to show the newly added driver
                window.location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while adding the driver.');
        });
    });
});