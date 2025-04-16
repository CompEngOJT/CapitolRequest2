/**
 * Chart functionality for admin overview
 */

let fuelChart; // Global variable to access the chart

$(document).ready(function() {
    // Initialize Chart with default view
    initFuelUsageChart('month', '');
    
    // Handle date range change
    $('#dateRangeSelect').change(function() {
        var selectedRange = $(this).val();
        var selectedProductType = $('#productTypeSelect').val();
        updateFuelUsageChart(selectedRange, selectedProductType);
    });
    
    // Handle product type selection change
    $('#productTypeSelect').change(function() {
        var selectedRange = $('#dateRangeSelect').val();
        var selectedProductType = $(this).val();
        updateFuelUsageChart(selectedRange, selectedProductType);
    });
});

function initFuelUsageChart(range, productType) {
    console.log('Initializing chart...');
    try {
        var ctx = document.getElementById('fuelUsageChart');
        if (!ctx) {
            console.error('Chart canvas element not found');
            return;
        }
        
        ctx = ctx.getContext('2d');
        
        // Create empty chart initially
        fuelChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: [],
                datasets: [{
                    label: 'Loading...',
                    data: [],
                    backgroundColor: 'rgba(54, 162, 235, 0.7)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Quantity'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Date'
                        }
                    }
                }
            }
        });
        
        console.log('Chart initialized successfully');
        // Fetch actual data
        updateFuelUsageChart(range, productType);
    } catch (e) {
        console.error('Error initializing chart:', e);
    }
}

function updateFuelUsageChart(range, productType) {
    // Show a loading state if needed
    if (fuelChart) {
        fuelChart.data.datasets[0].label = 'Loading...';
        fuelChart.update();
    }
    
    // Make AJAX request to get chart data
    $.ajax({
        url: "/admin/fuel-usage-data",
        method: 'GET',
        dataType: 'json',
        data: { 
            range: range,
            product_type: productType === '' ? 'all' : productType
        },
        success: function(response) {
            handleChartData(response, range, productType);
        },
        error: function(xhr, status, error) {
            console.error('Error fetching chart data:', error);
            handleChartError(range, productType);
        }
    });
}

function handleChartData(response, range, productType) {
    if (!fuelChart) {
        console.error('Chart not initialized');
        return;
    }
    
    // Destroy existing chart to create a new one
    fuelChart.destroy();
    
    var ctx = document.getElementById('fuelUsageChart').getContext('2d');
    
    if (productType === '') {
        // "All Products" - create multiple datasets
        let datasets = [];
        
        // Color palette for different product types
        const colors = [
            'rgba(54, 162, 235, 0.7)',  // blue
            'rgba(255, 99, 132, 0.7)',   // red
            'rgba(75, 192, 192, 0.7)',   // green
            'rgba(255, 206, 86, 0.7)',   // yellow
            'rgba(153, 102, 255, 0.7)',  // purple
            'rgba(255, 159, 64, 0.7)',   // orange
            'rgba(201, 203, 207, 0.7)',  // grey
            'rgba(255, 105, 180, 0.7)',  // pink
            'rgba(139, 69, 19, 0.7)',    // brown
            'rgba(0, 128, 0, 0.7)'       // dark green
        ];
        
        if (response.productData) {
            let colorIndex = 0;
            // Create a dataset for each product type
            for (const [productName, productValues] of Object.entries(response.productData)) {
                const color = colors[colorIndex % colors.length];
                datasets.push({
                    label: productName,
                    data: productValues,
                    backgroundColor: color,
                    borderColor: color.replace('0.7', '1'),
                    borderWidth: 1
                });
                colorIndex++;
            }
        }
        
        // Create a new chart with multiple datasets
        fuelChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: response.labels,
                datasets: datasets
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Quantity'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: getXAxisTitle(range)
                        }
                    }
                },
                interaction: {
                    mode: 'index',
                    intersect: false
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            title: function(tooltipItems) {
                                return getLabelForTooltip(tooltipItems[0].label, range);
                            }
                        }
                    },
                    legend: {
                        position: 'top',
                        labels: {
                            boxWidth: 12
                        }
                    }
                }
            }
        });
    } else {
        // Specific product type - use a single dataset
        fuelChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: response.labels,
                datasets: [{
                    label: $('#productTypeSelect option:selected').text(),
                    data: response.data,
                    backgroundColor: 'rgba(54, 162, 235, 0.7)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Quantity'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: getXAxisTitle(range)
                        }
                    }
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            title: function(tooltipItems) {
                                return getLabelForTooltip(tooltipItems[0].label, range);
                            }
                        }
                    }
                }
            }
        });
    }
}

function handleChartError(range, productType) {
    // Create fallback data in case of error
    var labels, data;
    
    if (range === 'week') {
        labels = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
        data = [0, 0, 0, 0, 0, 0, 0]; // Zero data as fallback
    } else if (range === 'year') {
        labels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        data = Array(12).fill(0); // Zero data for all months
    } else { // month
        // Create an array of day numbers for current month
        const daysInMonth = new Date(new Date().getFullYear(), new Date().getMonth() + 1, 0).getDate();
        labels = [...Array(daysInMonth).keys()].map(i => (i + 1).toString());
        data = Array(daysInMonth).fill(0); // Zero data for all days
    }
    
    if (fuelChart) {
        fuelChart.destroy();
        
        var ctx = document.getElementById('fuelUsageChart').getContext('2d');
        fuelChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'No Data Available',
                    data: data,
                    backgroundColor: 'rgba(200, 200, 200, 0.7)',
                    borderColor: 'rgba(200, 200, 200, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Quantity'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: getXAxisTitle(range)
                        }
                    }
                }
            }
        });
    }
}

// Helper function to get appropriate X-axis title based on range
function getXAxisTitle(range) {
    switch(range) {
        case 'week':
            return 'Day of Week';
        case 'year':
            return 'Month';
        default:
            return 'Day of Month';
    }
}

// Helper function to format tooltip labels
function getLabelForTooltip(label, range) {
    const currentDate = new Date();
    
    switch(range) {
        case 'week':
            // Convert day abbreviation to full day name
            const days = {'Mon': 'Monday', 'Tue': 'Tuesday', 'Wed': 'Wednesday', 
                         'Thu': 'Thursday', 'Fri': 'Friday', 'Sat': 'Saturday', 'Sun': 'Sunday'};
            return days[label] || label;
            
        case 'year':
            // Convert month abbreviation to full month name and year
            const months = {'Jan': 'January', 'Feb': 'February', 'Mar': 'March', 
                           'Apr': 'April', 'May': 'May', 'Jun': 'June',
                           'Jul': 'July', 'Aug': 'August', 'Sep': 'September', 
                           'Oct': 'October', 'Nov': 'November', 'Dec': 'December'};
            return `${months[label] || label} ${currentDate.getFullYear()}`;
            
        default:
            // For month view, add month name to day number
            const monthNames = ['January', 'February', 'March', 'April', 'May', 'June',
                              'July', 'August', 'September', 'October', 'November', 'December'];
            const month = monthNames[currentDate.getMonth()];
            return `${month} ${label}, ${currentDate.getFullYear()}`;
    }
}