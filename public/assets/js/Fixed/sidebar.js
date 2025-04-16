// assets/js/sidebar-ajax.js

document.addEventListener('DOMContentLoaded', function() {
    // Get all sidebar links
    const sidebarLinks = document.querySelectorAll('.sidebar-link');
    
    // Add click event to each sidebar link
    sidebarLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Get the URL from the href attribute
            const url = this.getAttribute('href');
            
            // Define the content container
            const contentContainer = document.querySelector('#main-content');
            if (!contentContainer) {
                // If no container is found, just navigate normally
                window.location.href = url;
                return;
            }
            
            // Show loading indicator
            contentContainer.innerHTML = '<div class="text-center p-5"><i class="fas fa-spinner fa-spin fa-3x"></i><p class="mt-3">Loading...</p></div>';
            
            // Remove active class from all links
            sidebarLinks.forEach(item => item.classList.remove('active'));
            
            // Add active class to clicked link
            this.classList.add('active');
            
            // Fetch the content with AJAX
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
                // Check if it's a full HTML page
                if (html.includes('<html') && html.includes('<body')) {
                    // It's a full page - we need to extract just the content
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    
                    // Try to find the main content
                    const contentSection = doc.querySelector('#main-content') || 
                                          doc.querySelector('main') || 
                                          doc.querySelector('.content') ||
                                          doc.querySelector('[role="main"]');
                    
                    if (contentSection) {
                        contentContainer.innerHTML = contentSection.innerHTML;
                    } else {
                        // If we can't find the content section, redirect
                        window.location.href = url;
                        return;
                    }
                } else {
                    // It's just content
                    contentContainer.innerHTML = html;
                }
                
                // Update browser URL
                window.history.pushState({url: url}, '', url);
                
                // Initialize any scripts the new content might need
                reinitializeScripts();
            })
            .catch(error => {
                console.error('Error loading content:', error);
                // Fallback to normal navigation
                window.location.href = url;
            });
        });
    });
    
    // Handle browser back/forward buttons
    window.addEventListener('popstate', function(event) {
        if (event.state && event.state.url) {
            // Fetch the content for the URL
            fetch(event.state.url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.text())
            .then(html => {
                const contentContainer = document.querySelector('#main-content');
                if (contentContainer) {
                    if (html.includes('<html') && html.includes('<body')) {
                        // It's a full page - extract content
                        const parser = new DOMParser();
                        const doc = parser.parseFromString(html, 'text/html');
                        const contentSection = doc.querySelector('#main-content') || 
                                              doc.querySelector('main') || 
                                              doc.querySelector('.content') ||
                                              doc.querySelector('[role="main"]');
                        
                        if (contentSection) {
                            contentContainer.innerHTML = contentSection.innerHTML;
                        } else {
                            window.location.reload();
                            return;
                        }
                    } else {
                        contentContainer.innerHTML = html;
                    }
                    
                    // Update active link in sidebar
                    updateActiveSidebarLink(event.state.url);
                    
                    // Reinitialize scripts
                    reinitializeScripts();
                } else {
                    window.location.reload();
                }
            })
            .catch(() => {
                window.location.reload();
            });
        } else {
            window.location.reload();
        }
    });
    
    // Function to update the active sidebar link
    function updateActiveSidebarLink(url) {
        sidebarLinks.forEach(link => {
            if (link.getAttribute('href') === url) {
                link.classList.add('active');
            } else {
                link.classList.remove('active');
            }
        });
    }
    
    // Function to reinitialize scripts after content load
    function reinitializeScripts() {
        // Reinitialize Bootstrap components if needed
        if (typeof bootstrap !== 'undefined') {
            const tooltips = document.querySelectorAll('[data-bs-toggle="tooltip"]');
            tooltips.forEach(tooltip => new bootstrap.Tooltip(tooltip));
            
            const popovers = document.querySelectorAll('[data-bs-toggle="popover"]');
            popovers.forEach(popover => new bootstrap.Popover(popover));
        }
        
        // Fire an event that other scripts can listen for
        document.dispatchEvent(new CustomEvent('contentLoaded'));
    }
});