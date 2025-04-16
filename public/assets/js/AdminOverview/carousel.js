/**
 * Carousel Upload and Edit Functionality
 */

document.addEventListener("DOMContentLoaded", function () {
    // Wait for jQuery and Slick to be fully loaded
    setTimeout(function() {
        initCarousel();
        setupPopupHandlers();
    }, 300); // Increased delay to ensure everything is loaded
});

// Initialize Slick Carousel
function initCarousel() {
    console.log('Initializing carousel...');
    
    // First, check if carousel is already initialized
    if (typeof $.fn.slick !== 'undefined' && $('#imageCarousel').length) {
        if ($('#imageCarousel').hasClass('slick-initialized')) {
            $('#imageCarousel').slick('unslick');
        }
        
        try {
            // Initialize with proper settings
            $('#imageCarousel').slick({
                dots: true,
                infinite: true,
                speed: 500,
                fade: true, // Using fade for smoother transitions
                cssEase: 'linear',
                arrows: false, // Custom navigation buttons will handle this
                autoplay: true,
                autoplaySpeed: 4000,
                pauseOnHover: true,
                adaptiveHeight: false,
                draggable: true,
                slidesToShow: 1,
                slidesToScroll: 1
            });
            
            // Connect custom navigation buttons
            $('.prev-slide').on('click', function(e) {
                e.preventDefault();
                $('#imageCarousel').slick('slickPrev');
            });
            
            $('.next-slide').on('click', function(e) {
                e.preventDefault();
                $('#imageCarousel').slick('slickNext');
            });
            
            console.log('Carousel initialized successfully');
        } catch (e) {
            console.error('Error initializing carousel:', e);
        }
    } else {
        console.error('Slick carousel not loaded or carousel element not found!');
    }
}

function setupPopupHandlers() {
    // Tab switching functionality
    document.querySelectorAll('.tab-btn').forEach(button => {
        button.addEventListener('click', function() {
            // Remove active class from all tabs
            document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
            document.querySelectorAll('.tab-content').forEach(content => content.classList.remove('active'));
            
            // Add active class to current tab
            this.classList.add('active');
            document.getElementById(this.dataset.tab).classList.add('active');
        });
    });
    
    // Open upload popup when button clicked
    const addImageBtn = document.querySelector('.add-image-btn');
    if (addImageBtn) {
        addImageBtn.addEventListener('click', function() {
            // Open popup and switch to add tab
            document.getElementById('imagePopup').style.display = 'block';
            document.querySelector('.tab-btn[data-tab="add-tab"]').click();
        });
    }
    
    // Handle file input change for preview (Upload popup)
    const carouselImageInput = document.getElementById('carouselImageInput');
    if (carouselImageInput) {
        carouselImageInput.addEventListener('change', function(e) {
            const previewContainer = document.querySelector('#add-tab .preview-container');
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
    
    // Handle form submission via AJAX for Upload
    const carouselUploadForm = document.getElementById('carouselUploadForm');
    if (carouselUploadForm) {
        carouselUploadForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Show loading state
            document.getElementById('uploadStatus').innerHTML = 
                '<div class="alert alert-info">Uploading image...</div>';
            
            const formData = new FormData(this);
            
            // For debugging - log form data
            console.log('Submitting upload form to:', this.action);
            
            fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => {
                console.log('Response status:', response.status);
                if (!response.ok) {
                    throw new Error('Network response was not ok: ' + response.status);
                }
                return response.json();
            })
            .then(data => {
                console.log('Response data:', data);
                if (data.success) {
                    // Clear the file input
                    document.getElementById('carouselImageInput').value = '';
                    document.querySelector('#add-tab .preview-container').style.display = 'none';
                    
                    // Close the upload popup
                    document.getElementById('imagePopup').style.display = 'none';
                    
                    // Show success message
                    showNotification('Image uploaded successfully!', 'success');
                    
                    // Reload the page after a short delay
                    setTimeout(function() {
                        location.reload();
                    }, 1500);
                } else {
                    document.getElementById('uploadStatus').innerHTML = 
                        '<div class="alert alert-danger">' + (data.message || 'Error uploading image') + '</div>';
                }
            })
            .catch(error => {
                console.error('Upload error:', error);
                document.getElementById('uploadStatus').innerHTML = 
                    '<div class="alert alert-danger">An error occurred: ' + error.message + '</div>';
            });
        });
    }
    
    // Handle Edit Icon Click
    const triggerPopups = document.querySelectorAll('.trigger-popup');
    triggerPopups.forEach(element => {
        element.addEventListener('click', function() {
            const imageId = this.dataset.imageId;
            document.getElementById('editImageId').value = imageId;
            document.getElementById('imagePopup').style.display = 'block';
            
            // Switch to edit tab
            document.querySelector('.tab-btn[data-tab="edit-tab"]').click();
        });
    });
    
    // Handle Edit Image Preview
    const editImageInput = document.getElementById('editImageInput');
    if (editImageInput) {
        editImageInput.addEventListener('change', function(e) {
            const previewContainer = document.querySelector('#edit-tab .preview-container');
            const preview = document.getElementById('editImagePreview');
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
    
    // Close modals when clicking the close buttons
    const closeButtons = document.querySelectorAll('.close-btn');
    closeButtons.forEach(button => {
        button.addEventListener('click', function() {
            const popup = this.closest('.popup');
            if (popup) {
                popup.style.display = 'none';
            }
        });
    });
    
    // Close modals when clicking outside of content
    const popups = document.querySelectorAll('.popup');
    popups.forEach(popup => {
        popup.addEventListener('click', function(event) {
            if (event.target === this) {
                this.style.display = 'none';
            }
        });
    });
    
    // Handle Image Edit Form Submission
    const imageEditForm = document.getElementById('imageEditForm');
    if (imageEditForm) {
        imageEditForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // For debugging
            console.log('Edit form action:', this.action);
            
            // Show loading state
            const submitButton = this.querySelector('button[type="submit"]');
            const originalButtonText = submitButton.innerHTML;
            submitButton.innerHTML = 'Updating...';
            submitButton.disabled = true;
            
            const formData = new FormData(this);
            
            fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => {
                console.log('Edit response status:', response.status);
                if (!response.ok) {
                    throw new Error('Network response was not ok: ' + response.status);
                }
                return response.json();
            })
            .then(data => {
                console.log('Edit response data:', data);
                // Reset button state
                submitButton.innerHTML = originalButtonText;
                submitButton.disabled = false;
                
                if (data.success) {
                    // Close the popup
                    document.getElementById('imagePopup').style.display = 'none';
                    
                    // Reset the form
                    imageEditForm.reset();
                    document.querySelector('#edit-tab .preview-container').style.display = 'none';
                    
                    // Show success message
                    showNotification('Image updated successfully!', 'success');
                    
                    // Reload the page to reflect changes
                    setTimeout(function() {
                        location.reload();
                    }, 1500);
                } else {
                    showNotification(data.message || 'Error updating image', 'error');
                }
            })
            .catch(error => {
                // Reset button state
                submitButton.innerHTML = originalButtonText;
                submitButton.disabled = false;
                
                console.error('Error:', error);
                showNotification('An error occurred: ' + error.message, 'error');
            });
        });
    }
    
    // Delete button click handler
    const deleteImageBtn = document.getElementById('deleteImageBtn');
    if (deleteImageBtn) {
        deleteImageBtn.addEventListener('click', function(e) {
            e.preventDefault();
            
            if (confirm('Are you sure you want to delete this image?')) {
                const imageId = document.getElementById('editImageId').value;
                
                console.log('Deleting image ID:', imageId);
                
                // Send delete request
                fetch('/carousel/delete', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        image_id: imageId
                    })
                })
                .then(response => {
                    console.log('Delete response status:', response.status);
                    if (!response.ok) {
                        throw new Error('Network response was not ok: ' + response.status);
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Delete response data:', data);
                    if (data.success) {
                        // Close the popup
                        document.getElementById('imagePopup').style.display = 'none';
                        
                        // Show success message
                        showNotification('Image deleted successfully!', 'success');
                        
                        // Reload the page to reflect changes
                        setTimeout(function() {
                            location.reload();
                        }, 1500);
                    } else {
                        showNotification(data.message || 'Error deleting image', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showNotification('An error occurred: ' + error.message, 'error');
                });
            }
        });
    }
}

// Show notification function
function showNotification(message, type) {
    const notification = document.createElement('div');
    notification.className = `notification ${type}`;
    notification.innerText = message;
    document.body.appendChild(notification);
    
    // Auto-remove after 3 seconds
    setTimeout(function() {
        notification.classList.add('fadeout');
        setTimeout(function() {
            notification.remove();
        }, 500);
    }, 3000);
}