document.addEventListener('DOMContentLoaded', function() {
    const sidebarToggle = document.getElementById('sidebarToggle');
    const container = document.getElementById('container');
    const sidebar = document.getElementById('sidebar');
    const middle = document.getElementById('middle');
    
    // Check for responsive view on load
    checkResponsiveView();
    
    // Check for responsive view on resize
    window.addEventListener('resize', checkResponsiveView);
    
    // Toggle sidebar when button is clicked
    sidebarToggle.addEventListener('click', function() {
        container.classList.toggle('sidebar-collapsed');
        sidebar.classList.toggle('collapsed');
        
        // Show overlay when sidebar is open on mobile
        if (window.innerWidth <= 991 && !sidebar.classList.contains('collapsed')) {
            createOverlay();
        } else {
            removeOverlay();
        }
    });
    
    // Function to check if we're in responsive view
    function checkResponsiveView() {
        // Toggle button visibility based on screen width
        if (sidebarToggle) {
            sidebarToggle.style.display = window.innerWidth <= 991 ? 'block' : 'none';
        }
        
        if (window.innerWidth <= 991) {
            // Responsive view - sidebar should be hidden by default
            if (container) {
                container.classList.add('sidebar-collapsed');
            }
            if (sidebar) {
                sidebar.classList.add('collapsed');
                sidebar.classList.add('mobile-view');
            }
            
            // Adjust middle section to take full width in mobile view
            if (middle) {
                middle.style.width = '100%';
                middle.style.left = '0';
                middle.style.right = 'auto';
                middle.style.display = 'block'; // Ensure it's visible
            }
        } else {
            // Desktop view - sidebar should be visible by default
            if (container) {
                container.classList.remove('sidebar-collapsed');
            }
            if (sidebar) {
                sidebar.classList.remove('collapsed');
                sidebar.classList.remove('mobile-view');
            }
            
            // Reset middle section position for desktop
            if (middle) {
                middle.style.width = '75%';
                middle.style.right = '0';
                middle.style.left = 'auto';
                middle.style.display = 'block';
            }
            
            removeOverlay();
        }
    }
    
    // Create overlay for mobile
    function createOverlay() {
        // Remove existing overlay if any
        removeOverlay();
        
        // Create new overlay
        const overlay = document.createElement('div');
        overlay.id = 'sidebar-overlay';
        overlay.style.position = 'fixed';
        overlay.style.top = '0';
        overlay.style.left = '0';
        overlay.style.right = '0';
        overlay.style.bottom = '0';
        overlay.style.backgroundColor = 'rgba(0, 0, 0, 0.5)';
        overlay.style.zIndex = '99';
        document.body.appendChild(overlay);
        
        // Close sidebar when clicking overlay
        overlay.addEventListener('click', function() {
            if (container) container.classList.add('sidebar-collapsed');
            if (sidebar) sidebar.classList.add('collapsed');
            removeOverlay();
        });
    }
    
    // Remove overlay
    function removeOverlay() {
        const overlay = document.getElementById('sidebar-overlay');
        if (overlay) {
            overlay.remove();
        }
    }
    
    // Close sidebar when clicking a link on mobile
    const sidebarLinks = document.querySelectorAll('.sidebar-link');
    sidebarLinks.forEach(link => {
        link.addEventListener('click', function() {
            if (window.innerWidth <= 991) {
                if (container) container.classList.add('sidebar-collapsed');
                if (sidebar) sidebar.classList.add('collapsed');
                removeOverlay();
            }
        });
    });
    
    // Trigger resize event to apply correct styles initially
    window.dispatchEvent(new Event('resize'));
});