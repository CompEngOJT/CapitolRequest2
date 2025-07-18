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
document.addEventListener('DOMContentLoaded', function () {
    // Password Masking for both containers
    const passwordBoxes = document.querySelectorAll('.password-info-box, .password-info-box');
    passwordBoxes.forEach(box => {
        box.textContent = '************'; // Always show fixed-length asterisks
    });

    // Reset Password Popup Handling for both containers
    const resetPasswordButtons = document.querySelectorAll('#middle-reset-password, #background-reset-password');
    const popupOverlay = document.getElementById('popup-overlay');
    const cancelResetButton = document.getElementById('cancel-reset');

    if (resetPasswordButtons.length > 0 && popupOverlay) {
        resetPasswordButtons.forEach(button => {
            button.addEventListener('click', function (e) {
                e.preventDefault(); // Prevent default behavior
                popupOverlay.style.display = 'flex'; // Show the popup
            });
        });

        // Hide popup when "Cancel" is clicked
        cancelResetButton.addEventListener('click', function () {
            popupOverlay.style.display = 'none'; // Hide the popup
        });
    } else {
        console.error('Reset Password buttons or popup overlay not found!');
    }

    // Password Validation and Success Popup Handling
    const driverSuccessPopup = document.getElementById('driver-success-popup');
    const driverDashboardButton = document.getElementById('driver-dashboard-button');
    const newPasswordInput = document.getElementById('new-password');
    const confirmPasswordInput = document.getElementById('confirm-password');
    const confirmResetButton = document.getElementById('confirm-reset');

    if (resetPasswordButtons.length > 0 && driverSuccessPopup) {
        // Validate password fields and update UI
        function validatePasswords() {
            const newPassword = newPasswordInput.value;
            const confirmPassword = confirmPasswordInput.value;

            // Check if passwords match and are at least 8 characters long
            const isLengthValid = newPassword.length >= 8;
            const doPasswordsMatch = newPassword === confirmPassword;

            if (doPasswordsMatch && isLengthValid) {
                // Passwords match and are valid
                newPasswordInput.style.borderColor = '#4CAF50'; // Green border
                confirmPasswordInput.style.borderColor = '#4CAF50'; // Green border
                confirmResetButton.disabled = false; // Enable the button
                confirmResetButton.style.opacity = '1'; // Make button visible
                confirmResetButton.style.cursor = 'pointer'; // Change cursor to pointer
                confirmResetButton.style.backgroundColor = '#367393'; // Reset button color
            } else {
                // Passwords do not match or are invalid
                newPasswordInput.style.borderColor = '#FF0000'; // Red border
                confirmPasswordInput.style.borderColor = '#FF0000'; // Red border
                confirmResetButton.disabled = true; // Disable the button
                confirmResetButton.style.opacity = '0.5'; // Make button transparent
                confirmResetButton.style.cursor = 'not-allowed'; // Change cursor to not-allowed
                confirmResetButton.style.backgroundColor = '#ccc'; // Gray out button
            }

            // Show error message if password is too short
            if (!isLengthValid) {
                newPasswordInput.setCustomValidity('Password must be at least 8 characters long.');
            } else {
                newPasswordInput.setCustomValidity(''); // Clear error message
            }
        }

        // Add input event listeners to both password fields
        newPasswordInput.addEventListener('input', validatePasswords);
        confirmPasswordInput.addEventListener('input', validatePasswords);

        // Initially disable the "Reset" button
        validatePasswords(); // Set initial state

        // Handle form submission
        const resetPasswordForm = document.getElementById('reset-password-form');
        if (resetPasswordForm) {
            resetPasswordForm.addEventListener('submit', function (e) {
                e.preventDefault(); // Prevent the default form submission

                const formData = new FormData(resetPasswordForm);

                fetch(resetPasswordForm.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json', // Ensure the response is JSON
                    },
                    body: formData,
                })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(err => { throw err; });
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        // Show success popup
                        driverSuccessPopup.style.display = 'flex';
                        popupOverlay.style.display = 'none'; // Hide the reset password popup
                    }
                })
                .catch(error => {
                    if (error.errors) {
                        // Handle validation errors
                        alert(Object.values(error.errors).join('\n'));
                    } else {
                        console.error('Error:', error);
                        alert('An error occurred. Please try again.');
                    }
                });
            });
        }

        // Handle "Dashboard" button click
        if (driverDashboardButton) {
            driverDashboardButton.addEventListener('click', function () {
                // Redirect to dashboard
                window.location.href = '/account';
            });
        }
    } else {
        console.error('Reset Password buttons or success popup not found!');
    }
});