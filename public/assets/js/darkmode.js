// Dark mode handling
document.addEventListener('DOMContentLoaded', function() {
    const themeToggle = document.getElementById('theme-toggle');
    
    // Skip if theme toggle doesn't exist
    if (!themeToggle) return;
    
    const body = document.body;
    const icon = themeToggle.querySelector('i');

    // Check localStorage first for theme preference
    const savedTheme = localStorage.getItem('themePreference');
    
    // If there's a saved theme in localStorage, apply it
    if (savedTheme) {
        if (savedTheme === 'dark') {
            body.classList.add('dark-mode');
        } else {
            body.classList.remove('dark-mode');
        }
    }
    
    // Initialize theme based on the body class
    const isDarkMode = body.classList.contains('dark-mode');
    updateIcon(isDarkMode);

    // Add event listener for theme toggle
    themeToggle.addEventListener('click', function() {
        body.classList.toggle('dark-mode');
        const isDarkMode = body.classList.contains('dark-mode');
        updateIcon(isDarkMode);

        // Save theme preference both to localStorage and to the database
        localStorage.setItem('themePreference', isDarkMode ? 'dark' : 'light');
        saveThemePreference(isDarkMode ? 'dark' : 'light');
    });

    function updateIcon(isDarkMode) {
        if (isDarkMode) {
            icon.classList.remove('fa-lightbulb');
            icon.classList.add('fa-moon');
        } else {
            icon.classList.remove('fa-moon');
            icon.classList.add('fa-lightbulb');
        }
    }

    function saveThemePreference(theme) {
        fetch('/save-theme-preference', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            },
            body: JSON.stringify({ theme }),
        })
        .then(response => {
            if (response.ok) {
                console.log('Theme preference saved successfully!');
            } else {
                console.error('Failed to save theme preference.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }
});