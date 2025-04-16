// pagination.js - AJAX pagination for AdminConsumption page

document.addEventListener('DOMContentLoaded', function() {
    // Initialize AJAX pagination
    initAjaxPagination();
});

function initAjaxPagination() {
    // Select the pagination container
    const paginationContainer = document.querySelector('.pagination-container');
    
    if (!paginationContainer) return;
    
    // Add event listener to pagination links
    paginationContainer.addEventListener('click', function(e) {
        // Check if the clicked element is a pagination link
        if (e.target.tagName === 'A' || e.target.closest('a')) {
            e.preventDefault();
            
            // Get the link that was clicked (handle both direct click and child element click)
            const link = e.target.tagName === 'A' ? e.target : e.target.closest('a');
            const url = link.getAttribute('href');
            
            if (url) {
                fetchPage(url);
            }
        }
    });
}

function fetchPage(url) {
    // Show loading state
    const contentArea = document.querySelector('.products-grid');
    contentArea.innerHTML = '<div class="loading-indicator">Loading...</div>';
    
    // Fetch the page content
    fetch(url, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.text();
    })
    .then(html => {
        // Parse the HTML
        const parser = new DOMParser();
        const doc = parser.parseFromString(html, 'text/html');
        
        // Extract the new products grid content
        const newProductsGrid = doc.querySelector('.products-grid');
        
        // Extract the new pagination content
        const newPagination = doc.querySelector('.pagination-container');
        
        // Update the DOM
        if (newProductsGrid) {
            document.querySelector('.products-grid').innerHTML = newProductsGrid.innerHTML;
        }
        
        if (newPagination) {
            document.querySelector('.pagination-container').innerHTML = newPagination.innerHTML;
        }
        
        // Update URL without reloading the page
        window.history.pushState({}, '', url);
    })
    .catch(error => {
        console.error('Error fetching page:', error);
        contentArea.innerHTML = '<div class="error">Error loading content. Please try again.</div>';
    });
}