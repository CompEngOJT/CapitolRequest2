document.addEventListener('DOMContentLoaded', function () {
  // Target both forms
  const forms = document.querySelectorAll('.document-request-form');

  forms.forEach(form => {
      const submitButton = form.querySelector('.submit-button');
      const popupOverlay = form.querySelector('.popup-overlay');
      const backToDashboardButton = form.querySelector('.back-to-dashboard');
      const requiredFields = form.querySelectorAll('input[required], select[required], textarea[required]');

      // Function to validate a single field
      function validateField(field) {
          if (field.value.trim()) {
              field.classList.remove('invalid');
              field.classList.add('valid');
              return true;
          } else {
              field.classList.remove('valid');
              field.classList.add('invalid');
              return false;
          }
      }

      // Add CSS for invalid fields
      const style = document.createElement('style');
      style.textContent = `
          .invalid {
              border-color: #ff3860 !important;
              box-shadow: 0 0 0 2px rgba(255, 56, 96, 0.2) !important;
          }
          .form-control.invalid + label {
              color: #ff3860 !important;
          }
          .valid {
              border-color: #23d160 !important;
          }
          .form-control.valid + label {
              color: #23d160 !important;
          }
          .popup-overlay.visible {
              display: flex !important;
              opacity: 1 !important;
              visibility: visible !important;
          }
      `;
      document.head.appendChild(style);

      // Validate fields on input
      requiredFields.forEach(field => {
          field.addEventListener('input', () => validateField(field));
          field.addEventListener('blur', () => validateField(field));
      });

      // Handle form submission with AJAX
      form.addEventListener('submit', function (e) {
          e.preventDefault();
          
          // Validate all fields
          let allFieldsValid = true;
          requiredFields.forEach(field => {
              if (!validateField(field)) {
                  allFieldsValid = false;
              }
          });

          if (!allFieldsValid) {
              // Show error message
              alert('Please fill out all required fields before submitting.');
              return;
          }

          // Create FormData object
          const formData = new FormData(form);
          
          // Show loading state
          submitButton.disabled = true;
          submitButton.innerHTML = 'Submitting...';
          
          // Send AJAX request
          fetch(form.getAttribute('action'), {
              method: 'POST',
              body: formData,
              headers: {
                  'X-Requested-With': 'XMLHttpRequest'
              }
          })
          .then(response => response.json())
          .then(data => {
              // Handle successful response
              if (data.success) {
                  // Show success popup
                  popupOverlay.classList.add('visible');
              } else {
                  // Show error message
                  alert(data.message || 'An error occurred. Please try again.');
              }
          })
          .catch(error => {
              console.error('Error:', error);
              alert('An error occurred. Please try again.');
          })
          .finally(() => {
              // Reset button state
              submitButton.disabled = false;
              submitButton.innerHTML = 'Submit';
          });
      });

// Back to dashboard button with AJAX
if (backToDashboardButton) {
  backToDashboardButton.addEventListener('click', function (e) {
      e.preventDefault();
      
      // Show loading state
      backToDashboardButton.disabled = true;
      backToDashboardButton.innerHTML = 'Loading...';
      
      // Fetch dashboard page with AJAX
      fetch('/request', {
          method: 'GET',
          headers: {
              'X-Requested-With': 'XMLHttpRequest',
              'Accept': 'text/html'
          }
      })
      .then(response => response.text())
      .then(html => {
          // Update the page content without a full reload
          document.body.innerHTML = html;
          
          // Update browser URL without reloading
          history.pushState({}, '', '/request');
          
          // Re-initialize any event listeners on the new content
          document.dispatchEvent(new Event('DOMContentLoaded'));
      })
      .catch(error => {
          console.error('Error:', error);
          // Fallback to regular redirect
          window.location.href = '/request';
      });
  });
}
  });
});