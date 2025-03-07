document.addEventListener('DOMContentLoaded', function () {
    // Target both forms
    const forms = document.querySelectorAll('.document-request-form');
  
    forms.forEach(form => {
      const showPopupButton = form.querySelector('.show-popup');
      const popupOverlay = form.querySelector('.popup-overlay');
      const backToDashboardButton = form.querySelector('.back-to-dashboard');
      const requiredFields = form.querySelectorAll('input[required], select[required], textarea[required]');
  
      // Function to validate a single field
      function validateField(field) {
        if (field.value.trim()) {
          field.classList.remove('invalid');
          field.classList.add('valid');
        } else {
          field.classList.remove('valid');
          field.classList.add('invalid');
        }
      }
  
      // Validate fields on input
      requiredFields.forEach(field => {
        field.addEventListener('input', () => validateField(field));
      });
  
      // Show the popup only if all required fields are filled
      showPopupButton.addEventListener('click', function () {
        let allFieldsValid = true;
  
        requiredFields.forEach(field => {
          validateField(field);
          if (!field.value.trim()) {
            allFieldsValid = false;
          }
        });
  
        if (allFieldsValid) {
          popupOverlay.classList.add('visible'); // Show the popup
        } else {
          alert('Please fill out all required fields before submitting.');
        }
      });
  
      // Submit the form when the "Back to Dashboard" button is clicked
      backToDashboardButton.addEventListener('click', function (e) {
        e.preventDefault(); // Prevent default form submission
        form.submit(); // Manually submit the form
      });
    });
  });

    const toggleButton = document.getElementById('sidebar-toggle');
    const slidingSidebar = document.getElementById('sliding-sidebar');
    const body = document.body;

    if (toggleButton && slidingSidebar) {
        toggleButton.addEventListener('click', function () {
            if (slidingSidebar.style.left === '0px') {
                slidingSidebar.style.left = '-350px';
                body.classList.remove('show-sidebar');
            } else {
                slidingSidebar.style.left = '0px';
                body.classList.add('show-sidebar');
            }
        });

        document.addEventListener('click', function (event) {
            if (!slidingSidebar.contains(event.target) &&
                !toggleButton.contains(event.target)) {
                slidingSidebar.style.left = '-350px';
                body.classList.remove('show-sidebar');
            }
        });
    }
