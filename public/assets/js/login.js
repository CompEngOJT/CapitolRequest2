document.addEventListener('DOMContentLoaded', function() {
    // Get the login form
    const loginForm = document.querySelector('form');
    
    // Get CSRF token
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    // Handle form submission
    loginForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Get form values
        const username = document.getElementById('username').value;
        const password = document.getElementById('password').value;
        
        // Validate input
        if (!username || !password) {
            showError('Please enter both username and password');
            return;
        }
        
        // Show loading state
        const loginBtn = document.querySelector('.login-btn');
        const originalBtnText = loginBtn.textContent;
        loginBtn.textContent = 'LOGGING IN...';
        loginBtn.disabled = true;
        
        // Get the form data
        const formData = new FormData(loginForm);
        
        // Ensure CSRF token is included
        if (!formData.has('_token')) {
            formData.append('_token', csrfToken);
        }
        
        // Send login request - ensure it's POST
        fetch('/login', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        })
        .then(response => {
            const contentType = response.headers.get('content-type');
            if (contentType && contentType.includes('application/json')) {
                return response.json().then(data => ({
                    ok: response.ok,
                    status: response.status,
                    data
                }));
            } else {
                return response.text().then(text => {
                    console.error('Server returned non-JSON response:', text.substring(0, 200) + '...');
                    
                    // Check if it's the method not supported error
                    if (text.includes('The GET method is not supported')) {
                        throw new Error('Form is submitting as GET instead of POST. Check your form method attribute.');
                    } else {
                        throw new Error('Server error occurred. Check the console for details.');
                    }
                });
            }
        })
        .then(result => {
            if (!result.ok) {
                throw new Error(result.data.message || 'Error ' + result.status);
            }
            
            // Login successful
            showSuccess(result.data.message || 'Login successful! Redirecting...');
            
            // Redirect to dashboard after short delay
            setTimeout(() => {
                window.location.href = result.data.redirect || '/admin/dashboard';
            }, 1000);
        })
        .catch(error => {
            console.error('Login error:', error);
            showError(error.message || 'Invalid username or password. Please try again.');
            resetLoginButton();
        });
        
        function resetLoginButton() {
            loginBtn.textContent = originalBtnText;
            loginBtn.disabled = false;
        }
    });
    
    // Function to show error message with fade out effect
    function showError(message) {
        // Clear any existing message timers
        clearMessageTimers();
        
        // Check if error message element exists, if not create it
        let errorElement = document.querySelector('.error-message');
        if (!errorElement) {
            errorElement = document.createElement('div');
            errorElement.className = 'error-message';
            errorElement.style.color = 'red';
            errorElement.style.marginTop = '10px';
            errorElement.style.textAlign = 'center';
            errorElement.style.transition = 'opacity 1s ease-out';
            const formGroups = document.querySelectorAll('.form-group');
            formGroups[formGroups.length - 1].after(errorElement);
        }
        
        // Hide any success message
        hideElement('.success-message');
        
        // Show error message
        errorElement.textContent = message;
        errorElement.style.opacity = '1';
        errorElement.style.display = 'block';
        
        // Set timeout to fade away
        window.errorMessageTimer = setTimeout(() => {
            fadeOutElement(errorElement);
        }, 3000);
    }
    
    // Function to show success message with fade out effect
    function showSuccess(message) {
        // Clear any existing message timers
        clearMessageTimers();
        
        // Check if success message element exists, if not create it
        let successElement = document.querySelector('.success-message');
        if (!successElement) {
            successElement = document.createElement('div');
            successElement.className = 'success-message';
            successElement.style.color = 'green';
            successElement.style.marginTop = '10px';
            successElement.style.textAlign = 'center';
            successElement.style.transition = 'opacity 1s ease-out';
            const formGroups = document.querySelectorAll('.form-group');
            formGroups[formGroups.length - 1].after(successElement);
        }
        
        // Hide any error message
        hideElement('.error-message');
        
        // Show success message
        successElement.textContent = message;
        successElement.style.opacity = '1';
        successElement.style.display = 'block';
        
        // Set timeout to fade away
        window.successMessageTimer = setTimeout(() => {
            fadeOutElement(successElement);
        }, 3000);
    }
    
    // Helper function to fade out an element
    function fadeOutElement(element) {
        if (element) {
            element.style.opacity = '0';
            setTimeout(() => {
                element.style.display = 'none';
            }, 1000); // Wait for the transition to complete
        }
    }
    
    // Helper function to hide an element
    function hideElement(selector) {
        const element = document.querySelector(selector);
        if (element) {
            element.style.display = 'none';
        }
    }
    
    // Function to clear any existing message timers
    function clearMessageTimers() {
        if (window.errorMessageTimer) {
            clearTimeout(window.errorMessageTimer);
        }
        if (window.successMessageTimer) {
            clearTimeout(window.successMessageTimer);
        }
    }
});