// Logout functionality with back button prevention
document.addEventListener('DOMContentLoaded', function() {
    // Check if page is being accessed after a logout via back button
    window.addEventListener('pageshow', function(event) {
        // If the page is loaded from cache (back button)
        if (event.persisted) {
            // Redirect to login page
            window.location.href = '/login';
        }
    });
    
    // Handle logout button click
    const logoutIcon = document.getElementById('header-logout');
    if (logoutIcon) {
        logoutIcon.addEventListener('click', function() {
            // Clear any client-side storage
            sessionStorage.clear();
            localStorage.clear();
            
            // Flag to indicate logout has occurred
            sessionStorage.setItem('justLoggedOut', 'true');
            
            // Create a form to submit POST request
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '/admin/logout'; // Your Laravel logout route
            
            // Add CSRF token
            const csrfToken = document.querySelector('meta[name="csrf-token"]');
            if (csrfToken) {
                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = csrfToken.content;
                form.appendChild(csrfInput);
            }
            
            // Append form to body, submit it, and remove it
            document.body.appendChild(form);
            form.submit();
            document.body.removeChild(form);
        });
    }
    
    // Check if we're on a protected page
    if (document.getElementById('main-header')) {
        // Disable navigation caching for protected pages
        if (window.performance && window.performance.navigation.type === 2) {
            // Type 2 is back button navigation
            window.location.reload();
        }
    }
});