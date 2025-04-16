document.addEventListener('DOMContentLoaded', function() {
    // Main container where content will be loaded
    const tableContainer = document.getElementById('table-container');
    const tableBody = document.getElementById('table-body');
    const paginationContainer = document.querySelector('.pagination-container');
    
    // Function to handle pagination clicks
    function handlePaginationClick(e) {
        e.preventDefault();
        
        const url = this.getAttribute('href');
        
        fetch(url)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.text();
            })
            .then(html => {
                // Update the URL in the browser's address bar without reloading
                history.pushState(null, '', url);
                
                // Create a temporary div to parse the HTML
                const tempDiv = document.createElement('div');
                tempDiv.innerHTML = html;
                
                // Extract just the table body content
                const newTableBody = tempDiv.querySelector('#table-body');
                if (newTableBody) {
                    tableBody.innerHTML = newTableBody.innerHTML;
                }
                
                // Extract just the pagination content
                const newPagination = tempDiv.querySelector('.pagination-container');
                if (newPagination) {
                    paginationContainer.innerHTML = newPagination.innerHTML;
                }
                
                // Re-attach event listeners
                attachPaginationListeners();
                
                // Trigger a custom event that we can listen for
                document.dispatchEvent(new CustomEvent('paginationComplete'));
            })
            .catch(error => {
                console.error('Fetch error:', error);
                // Optional: Display error message to user
                tableBody.innerHTML = `<tr><td colspan="5" class="text-center">Failed to load content. Please try again.</td></tr>`;
            });
    }
    
    // Function to attach event listeners to pagination links
    function attachPaginationListeners() {
        const paginationLinks = document.querySelectorAll('.pagination a');
        
        paginationLinks.forEach(link => {
            link.addEventListener('click', handlePaginationClick);
        });
    }
    
    // Initial attachment of event listeners
    attachPaginationListeners();
    
    // Handle browser back/forward buttons
    window.addEventListener('popstate', function() {
        fetch(window.location.href)
            .then(response => response.text())
            .then(html => {
                const tempDiv = document.createElement('div');
                tempDiv.innerHTML = html;
                
                const newTableBody = tempDiv.querySelector('#table-body');
                if (newTableBody) {
                    tableBody.innerHTML = newTableBody.innerHTML;
                }
                
                const newPagination = tempDiv.querySelector('.pagination-container');
                if (newPagination) {
                    paginationContainer.innerHTML = newPagination.innerHTML;
                }
                
                attachPaginationListeners();
                
                // Dispatch event for other scripts that might need to know pagination is complete
                document.dispatchEvent(new CustomEvent('paginationComplete'));
            });
    });
});