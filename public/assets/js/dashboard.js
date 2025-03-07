document.addEventListener('DOMContentLoaded', function () {
    const toggleButton = document.getElementById('sidebar-toggle');
    const slidingSidebar = document.getElementById('sliding-sidebar');
    const mobileContainer = document.getElementById('mobile-container');
    const backgroundContent = document.getElementById('background-content');
    const rectangleContainer = document.getElementById('rectangle-container');
    const body = document.body;

    toggleButton.addEventListener('click', function () {
        if (slidingSidebar.style.left === '0px') {
            slidingSidebar.style.left = '-350px'; // Hide sidebar
            body.classList.remove('show-sidebar'); // Remove the class to unblur
        } else {
            slidingSidebar.style.left = '0px'; // Show sidebar
            body.classList.add('show-sidebar'); // Add the class to blur
        }
    });

    document.addEventListener('click', function(event) {
        const sidebar = document.getElementById('sliding-sidebar');
        const sidebarToggle = document.getElementById('sidebar-toggle');
        const menuIcon = document.getElementById('menu-icon');

        // Check if the click is outside the sidebar and not on the toggle button/menu icon
        if (
            !sidebar.contains(event.target) && // Click is outside the sidebar
            !sidebarToggle.contains(event.target) && // Click is not on the toggle button
            !menuIcon.contains(event.target) // Click is not on the menu icon
        ) {
            slidingSidebar.style.left = '-350px'; // Hide the sidebar
            body.classList.remove('show-sidebar'); // Remove the class to unblur
        }
    });
});