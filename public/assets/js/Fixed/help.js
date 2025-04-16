document.addEventListener('DOMContentLoaded', function() {
    // Get the elements
    const helpButton = document.getElementById('header-help');
    const helpOverlay = document.getElementById('help-overlay');
    const closeButton = document.getElementById('close-help');
    
    // Show the popup when help button is clicked
    helpButton.addEventListener('click', function() {
      helpOverlay.style.display = 'flex';
      // Prevent scrolling of the background
      document.body.style.overflow = 'hidden';
    });
    
    // Hide the popup when close button is clicked
    closeButton.addEventListener('click', function() {
      helpOverlay.style.display = 'none';
      // Restore scrolling
      document.body.style.overflow = 'auto';
    });
    
    // Hide the popup when clicking outside the content
    helpOverlay.addEventListener('click', function(event) {
      if (event.target === helpOverlay) {
        helpOverlay.style.display = 'none';
        // Restore scrolling
        document.body.style.overflow = 'auto';
      }
    });
    
    // Close popup when ESC key is pressed
    document.addEventListener('keydown', function(event) {
      if (event.key === 'Escape' && helpOverlay.style.display === 'flex') {
        helpOverlay.style.display = 'none';
        // Restore scrolling
        document.body.style.overflow = 'auto';
      }
    });
  });