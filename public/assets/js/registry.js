document.addEventListener('DOMContentLoaded', function () {
    // Popup Elements for Driver
    const squareButton = document.getElementById('square');
    const driverPopup = document.getElementById('driver-popup');
    const cancelButton = document.getElementById('cancel-button');
    const driverForm = document.querySelector('#driver-popup form');
    const driverInputs = driverForm.querySelectorAll('input, select');
    const addDriverButton = document.getElementById('add-driver-button'); // Updated ID
    const driverSuccessPopup = document.getElementById('driver-success-popup');
    const driverDashboardButton = document.getElementById('driver-dashboard-button');

    // Popup Elements for Vehicle
    const squareButton2 = document.getElementById('square2');
    const vehiclePopup = document.getElementById('vehicle-popup');
    const cancelVehicleButton = document.getElementById('cancel-vehicle-button');
    const vehicleForm = document.querySelector('#vehicle-popup form');
    const vehicleInputs = vehicleForm.querySelectorAll('input');
    const addVehicleButton = document.getElementById('add-vehicle-button'); // Updated ID
    const vehicleSuccessPopup = document.getElementById('vehicle-success-popup');
    const vehicleDashboardButton = document.getElementById('vehicle-dashboard-button');

    // Background Content Elements
    const squareButton3 = document.getElementById('square3');
    const squareButton4 = document.getElementById('square4');

    // Sidebar Elements
    const toggleButton = document.getElementById('sidebar-toggle');
    const slidingSidebar = document.getElementById('sliding-sidebar');
    const body = document.body;

    // Get the registry URL from a data attribute or directly
    const registryUrl = '/registry'; // Default fallback path

    // Show popup when square button is clicked (Driver)
    squareButton.addEventListener('click', function () {
        driverPopup.style.display = 'flex';
    });

    // Show popup when square3 is clicked (Driver - Background Content)
    if (squareButton3) {
        squareButton3.addEventListener('click', function () {
            driverPopup.style.display = 'flex';
        });
    }

    // Hide popup when cancel button is clicked (Driver)
    cancelButton.addEventListener('click', function () {
        driverPopup.style.display = 'none';
    });

    // Show popup when square button2 is clicked (Vehicle)
    squareButton2.addEventListener('click', function () {
        vehiclePopup.style.display = 'flex';
    });

    // Show popup when square4 is clicked (Vehicle - Background Content)
    if (squareButton4) {
        squareButton4.addEventListener('click', function () {
            vehiclePopup.style.display = 'flex';
        });
    }

    // Hide popup when cancel button is clicked (Vehicle)
    cancelVehicleButton.addEventListener('click', function () {
        vehiclePopup.style.display = 'none';
    });

    // Hide popups when clicking outside the popup content
    window.addEventListener('click', function (event) {
        if (event.target === driverPopup) {
            driverPopup.style.display = 'none';
        }
        if (event.target === vehiclePopup) {
            vehiclePopup.style.display = 'none';
        }
        if (event.target === driverSuccessPopup) {
            driverSuccessPopup.style.display = 'none';
        }
        if (event.target === vehicleSuccessPopup) {
            vehicleSuccessPopup.style.display = 'none';
        }
    });

    // Function to check if all inputs are filled (Driver)
    function checkDriverInputs() {
        let allFilled = true;
        driverInputs.forEach(input => {
            if (!input.value.trim()) {
                allFilled = false;
            }
        });
        addDriverButton.disabled = !allFilled;
    }

    // Function to check if all inputs are filled (Vehicle)
    function checkVehicleInputs() {
        let allFilled = true;
        vehicleInputs.forEach(input => {
            if (!input.value.trim()) {
                allFilled = false;
            }
        });
        addVehicleButton.disabled = !allFilled;
    }

    // Add event listeners to all inputs (Driver)
    driverInputs.forEach(input => {
        input.addEventListener('input', checkDriverInputs);
    });

    // Add event listeners to all inputs (Vehicle)
    vehicleInputs.forEach(input => {
        input.addEventListener('input', checkVehicleInputs);
    });

    // Initial check in case any inputs are pre-filled
    checkDriverInputs();
    checkVehicleInputs();

    // Add event listener to the "Add" button (Driver)
    if (addDriverButton) {
        addDriverButton.addEventListener('click', function() {
            // Show the driver success popup
            driverSuccessPopup.style.display = 'flex';
        });
    }

    // Add event listener to the "Add" button (Vehicle)
    if (addVehicleButton) {
        addVehicleButton.addEventListener('click', function() {
            // Show the vehicle success popup
            vehicleSuccessPopup.style.display = 'flex';
        });
    }

    // Add event listener to the "Dashboard" button to submit the form via AJAX (Driver)
    if (driverDashboardButton) {
        driverDashboardButton.addEventListener('click', function() {
            // Get form data
            const formData = new FormData(driverForm);
            
            // Create AJAX request
            fetch(driverForm.getAttribute('action'), {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(data => {
                        throw new Error(data.message || 'Network response was not ok');
                    });
                }
                return response.json();
            })
            .then(data => {
                // Hide the success popup
                driverSuccessPopup.style.display = 'none';
                
                if (data.success) {
                    // Clear form fields
                    driverForm.reset();
                    
                    // Reset the initial form popup
                    driverPopup.style.display = 'none';
                    
                    // Redirect to the registry page - fixed path
                    window.location.href = registryUrl;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                // Hide success popup
                driverSuccessPopup.style.display = 'none';
                // Show error message
                alert(error.message || 'There was a problem adding the driver. Please try again.');
            });
        });
    }

    // Add event listener to the "Dashboard" button to submit the form via AJAX (Vehicle)
    if (vehicleDashboardButton) {
        vehicleDashboardButton.addEventListener('click', function() {
            // Get form data
            const formData = new FormData(vehicleForm);
            
            // Create AJAX request
            fetch(vehicleForm.getAttribute('action'), {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(data => {
                        throw new Error(data.message || 'Network response was not ok');
                    });
                }
                return response.json();
            })
            .then(data => {
                // Hide the success popup
                vehicleSuccessPopup.style.display = 'none';
                
                if (data.success) {
                    // Clear form fields
                    vehicleForm.reset();
                    
                    // Reset the initial form popup
                    vehiclePopup.style.display = 'none';
                    
                    // Redirect to the registry page - fixed path
                    window.location.href = registryUrl;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                // Hide success popup
                vehicleSuccessPopup.style.display = 'none';
                // Show error message
                alert(error.message || 'There was a problem adding the vehicle. Please try again.');
            });
        });
    }
});