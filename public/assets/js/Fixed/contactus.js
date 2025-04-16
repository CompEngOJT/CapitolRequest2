// Contact Us Popup JavaScript
document.addEventListener('DOMContentLoaded', function() {
    // Get the contact icon and overlay
    const contactIcon = document.getElementById('header-contact');
    const contactusOverlay = document.getElementById('contactus-overlay');
    const closeContactus = document.getElementById('close-contactus');
    
    // Open contact popup with smooth animation
    contactIcon.addEventListener('click', function() {
      // Make the overlay visible first but fully transparent
      contactusOverlay.style.display = 'block';
      contactusOverlay.style.opacity = '0';
      
      // Force a reflow to ensure the transition works
      void contactusOverlay.offsetWidth;
      
      // Fade in the overlay
      contactusOverlay.style.opacity = '1';
      document.body.style.overflow = 'hidden'; // Prevent scrolling behind the popup
      
      // Add transition for smooth appearance
      contactusOverlay.style.transition = 'opacity 0.3s ease';
    });
  
    // Close contact popup with smooth animation
    function closeContactusPopup() {
      contactusOverlay.style.opacity = '0';
      
      // Wait for the fade-out animation to complete
      setTimeout(function() {
        contactusOverlay.style.display = 'none';
        document.body.style.overflow = ''; // Restore scrolling
      }, 300);
    }
    
    // Close button click event
    closeContactus.addEventListener('click', closeContactusPopup);
  
    // Close when clicking outside the popup
    contactusOverlay.addEventListener('click', function(e) {
      if (e.target === contactusOverlay) {
        closeContactusPopup();
      }
    });
    
    // Add hover effect to contact cards using JavaScript for better performance
    const contactCards = document.querySelectorAll('.contactus-card');
    contactCards.forEach(card => {
      card.addEventListener('mouseenter', function() {
        this.style.transform = 'translateY(-5px)';
        this.style.boxShadow = '0 8px 20px rgba(0, 0, 0, 0.12)';
      });
      
      card.addEventListener('mouseleave', function() {
        this.style.transform = 'translateY(0)';
        this.style.boxShadow = '0 3px 10px rgba(0, 0, 0, 0.08)';
      });
    });
  });
  
  // Close the popup when ESC key is pressed
  document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
      const contactusOverlay = document.getElementById('contactus-overlay');
      if (contactusOverlay && contactusOverlay.style.display === 'block') {
        contactusOverlay.style.opacity = '0';
        
        // Wait for the fade-out animation to complete
        setTimeout(function() {
          contactusOverlay.style.display = 'none';
          document.body.style.overflow = ''; // Restore scrolling
        }, 300);
      }
    }
  });