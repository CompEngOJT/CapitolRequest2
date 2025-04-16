document.addEventListener('DOMContentLoaded', function() {
    const darkModeToggle = document.getElementById('header-darkmode');
    const body = document.body;
    
    // Check if user has previously set dark mode preference
    const isDarkMode = localStorage.getItem('darkMode') === 'enabled';
    
    // Apply dark mode if it was previously enabled
    if (isDarkMode) {
      enableDarkMode();
    }
    
    // Toggle dark mode when the icon is clicked
    darkModeToggle.addEventListener('click', function() {
      if (body.classList.contains('dark-mode')) {
        disableDarkMode();
      } else {
        enableDarkMode();
      }
    });
    
    // Function to enable dark mode
    function enableDarkMode() {
      body.classList.add('dark-mode');
      localStorage.setItem('darkMode', 'enabled');
      // Change moon icon to sun icon
      darkModeToggle.querySelector('i').classList.remove('fa-moon');
      darkModeToggle.querySelector('i').classList.add('fa-sun');
      darkModeToggle.setAttribute('title', 'Light Mode');
    }
    
    // Function to disable dark mode
    function disableDarkMode() {
      body.classList.remove('dark-mode');
      localStorage.setItem('darkMode', 'disabled');
      // Change sun icon back to moon icon
      darkModeToggle.querySelector('i').classList.remove('fa-sun');
      darkModeToggle.querySelector('i').classList.add('fa-moon');
      darkModeToggle.setAttribute('title', 'Dark Mode');
    }
  });