document.addEventListener("DOMContentLoaded", function () {
    const openPopupElements = document.querySelectorAll(".trigger-popup");
    const popup = document.getElementById("imagePopup");
    const closeBtn = document.querySelector(".close-btn");
    
    openPopupElements.forEach(element => {
        element.addEventListener("click", function () {
            // Show popup
            popup.style.display = "block";
        });
    });

    if (closeBtn) {
        closeBtn.addEventListener("click", function () {
            popup.style.display = "none";
        });
    }

    // Close modal when clicking outside of content
    window.addEventListener("click", function (event) {
        if (event.target === popup) {
            popup.style.display = "none";
        }
    });
    
    // Add preview functionality for the image
    const imageInput = document.getElementById('image');
    if (imageInput) {
        imageInput.addEventListener('change', function(e) {
            const previewContainer = document.querySelector('.preview-container');
            const preview = document.getElementById('imagePreview');
            const file = this.files[0];
            
            if (file) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    previewContainer.style.display = 'block';
                }
                
                reader.readAsDataURL(file);
            } else {
                previewContainer.style.display = 'none';
            }
        });
    }
    
    // Handle form submission
// Handle form submission
const imageForm = document.getElementById("imageUploadForm");
if (imageForm) {
    imageForm.addEventListener("submit", function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        
        // Show loading state
        const uploadButton = this.querySelector('.upload-button');
        const originalButtonText = uploadButton.innerHTML;
        uploadButton.innerHTML = 'Uploading...';
        uploadButton.disabled = true;
        
        fetch('/upload-image', {
            method: 'POST',
            body: formData,
            // Remove the headers section completely
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            // Reset button state
            uploadButton.innerHTML = originalButtonText;
            uploadButton.disabled = false;
            
            if (data.success) {
                // Update the image on the page without refreshing
                const uploadedImage = document.querySelector(".uploaded-image");
                if (uploadedImage && data.image_url) {
                    uploadedImage.src = data.image_url;
                }
                
                // Close the popup
                popup.style.display = "none";
                
                // Reset the form
                imageForm.reset();
                document.querySelector('.preview-container').style.display = 'none';
                
                // Show success message
                showNotification('Image updated successfully!', 'success');
            } else {
                showNotification(data.message || 'Error updating image', 'error');
            }
        })
        .catch(error => {
            // Reset button state
            uploadButton.innerHTML = originalButtonText;
            uploadButton.disabled = false;
            
            console.error('Error:', error);
            showNotification('An error occurred while uploading the image', 'error');
        });
    });
}
    
    // Add a simple notification function
    function showNotification(message, type) {
        const notification = document.createElement('div');
        notification.className = `notification ${type}`;
        notification.innerHTML = message;
        document.body.appendChild(notification);
        
        // Auto-remove after 3 seconds
        setTimeout(() => {
            notification.classList.add('fadeout');
            setTimeout(() => {
                document.body.removeChild(notification);
            }, 500);
        }, 3000);
    }
});