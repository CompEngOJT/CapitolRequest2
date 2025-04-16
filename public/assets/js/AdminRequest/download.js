document.addEventListener('DOMContentLoaded', function() {
    const downloadBtn = document.querySelector('.download-btn');
    const downloadModal = document.getElementById('download-modal');
    const requestTable = document.querySelector('.request-table');
    
    if (downloadBtn && downloadModal && requestTable) {
        // Set default dates (today and 30 days ago)
        const setDefaultDates = function() {
            const today = new Date();
            const thirtyDaysAgo = new Date();
            thirtyDaysAgo.setDate(today.getDate() - 30);
            
            document.getElementById('start-date').value = formatDateForInput(thirtyDaysAgo);
            document.getElementById('end-date').value = formatDateForInput(today);
        };
        

        // Show modal when download button is clicked
        downloadBtn.addEventListener('click', function() {
            setDefaultDates();
            populateRequestList();
            downloadModal.style.display = 'block';
            
            // Apply initial filter
            document.getElementById('apply-filter').click();
            
            // Filter for fuel/oil/grease/brake fluid requests
            filterRequestsByType(['Fuel', 'Oil', 'Grease', 'Brake Fluid']);
        });
        
        // Close the modal when clicking on the close button
        const closeBtn = downloadModal.querySelector('.close');
        closeBtn.addEventListener('click', function() {
            downloadModal.style.display = 'none';
        });
        
        // Close the modal when clicking on the cancel button
        const cancelBtn = document.getElementById('cancel-download');
        cancelBtn.addEventListener('click', function() {
            downloadModal.style.display = 'none';
        });
        
        // Handle select all checkbox
        const selectAllCheckbox = document.getElementById('select-all-requests');
        selectAllCheckbox.addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.request-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.checked = selectAllCheckbox.checked;
            });
        });
        
        // Handle date range filter
        const applyFilterBtn = document.getElementById('apply-filter');
        applyFilterBtn.addEventListener('click', function() {
            const startDate = new Date(document.getElementById('start-date').value);
            const endDate = new Date(document.getElementById('end-date').value);
            
            // Add one day to end date to include the end date in the range
            endDate.setDate(endDate.getDate() + 1);
            
            filterRequestsByDateRange(startDate, endDate);
        });
        
        // Handle download button click
        const confirmDownloadBtn = document.getElementById('confirm-download');
        confirmDownloadBtn.addEventListener('click', function() {
            const selectedRows = getSelectedRows();
            const format = document.getElementById('download-format').value;
            
            if (selectedRows.length === 0) {
                alert('Please select at least one request to download.');
                return;
            }
            
            if (format === 'pdf') {
                generatePDF(selectedRows);
            } else {
                generateExcel(selectedRows);
            }
            
            downloadModal.style.display = 'none';
        });
    }
    function filterRequestsByType(allowedTypes) {
        const requestItems = document.querySelectorAll('.request-item');
        const requestList = document.querySelector('.request-list');
        let visibleCount = 0;
        
        // Remove any previous no-results message
        const noResults = document.querySelector('.no-results-message');
        if (noResults) {
            noResults.remove();
        }
        
        requestItems.forEach(item => {
            const requestInfo = item.querySelector('.request-details');
            const requestType = requestInfo ? requestInfo.textContent.split('|')[2].trim() : '';
            
            // Check if the type is in the allowed types list (case insensitive)
            const isAllowedType = allowedTypes.some(type => 
                requestType.toLowerCase().includes(type.toLowerCase())
            );
            
            if (isAllowedType) {
                item.style.display = '';
                visibleCount++;
            } else {
                item.style.display = 'none';
            }
        });
        
        // Update select all checkbox
        const selectAllCheckbox = document.getElementById('select-all-requests');
        selectAllCheckbox.checked = false;
        
        if (visibleCount === 0) {
            const noResults = document.createElement('p');
            noResults.className = 'no-results-message';
            noResults.textContent = 'No fuel/oil/grease/brake fluid requests found.';
            requestList.appendChild(noResults);
        }
    }
    
    function formatDateForInput(date) {
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        return `${year}-${month}-${day}`;
    }
    
    function populateRequestList() {
        const rows = document.querySelectorAll('.request-table tbody tr');
        const requestList = document.querySelector('.request-list');
        const allowedTypes = ["Fuel", "Oil", "Grease", "Brake Fluid"];
        
        // Clear existing content
        requestList.innerHTML = '';
        
        if (rows.length === 0) {
            requestList.innerHTML = '<p>No requests available.</p>';
            return;
        }
        
        let visibleCount = 0;
        
        rows.forEach((row, index) => {
            const requestId = row.getAttribute('data-request-id');
            const date = row.cells[0].textContent;
            const plateNo = row.cells[1].textContent;
            const driver = row.cells[2].textContent;
            const type = row.cells[3].textContent.trim();
            const status = row.cells[6].textContent; // Get the status from the table row
            
            // Check if this is one of the allowed types
            const isAllowedType = allowedTypes.some(allowedType => 
                type.toLowerCase() === allowedType.toLowerCase()
            );
            
            // Only proceed if it's an allowed type
            if (isAllowedType) {
                visibleCount++;
                
                // Add this status classification code here
                let statusClass = '';
                const statusLower = status.toLowerCase().trim();
                if (statusLower === 'approved') {
                    statusClass = 'approved';
                } else if (statusLower === 'pending') {
                    statusClass = 'pending';
                } else if (statusLower === 'rejected') {
                    statusClass = 'rejected';
                }
                
                const requestItem = document.createElement('div');
                requestItem.className = 'request-item';
                requestItem.setAttribute('data-date', date);
                requestItem.innerHTML = `
                    <label class="request-checkbox-container">
                        <input type="checkbox" class="request-checkbox" data-id="${requestId}" data-row-index="${index}">
                        <span class="checkmark"></span>
                        <div class="request-info">
                            <span class="request-id">${date}</span>
                            <span class="request-details">${plateNo} | ${driver} | ${type}</span>
                            <span class="request-status ${statusClass}">${status}</span>
                        </div>
                    </label>
                `;
                
                requestList.appendChild(requestItem);
            }
        });
        
        // Show a message if no matching requests are found
        if (visibleCount === 0) {
            requestList.innerHTML = '<p>No fuel, oil, grease, or brake fluid requests available.</p>';
        }
    }
    
    function filterRequestsByDateRange(startDate, endDate) {
        const requestItems = document.querySelectorAll('.request-item');
        const requestList = document.querySelector('.request-list');
        let visibleCount = 0;
        
        // Remove any previous no-results message
        const noResults = document.querySelector('.no-results-message');
        if (noResults) {
            noResults.remove();
        }
        
        requestItems.forEach(item => {
            const dateStr = item.getAttribute('data-date');
            const itemDate = parseDate(dateStr);
            
            if (itemDate >= startDate && itemDate < endDate) {
                item.style.display = '';
                visibleCount++;
            } else {
                item.style.display = 'none';
            }
        });
        
        // Update select all checkbox
        const selectAllCheckbox = document.getElementById('select-all-requests');
        selectAllCheckbox.checked = false;
        
        if (visibleCount === 0) {
            const noResults = document.createElement('p');
            noResults.className = 'no-results-message';
            noResults.textContent = 'No requests found in the selected date range.';
            requestList.appendChild(noResults);
        }
    }
    
    function parseDate(dateStr) {
        // Parse the date string based on the format in your table
        // Assuming a format like "MM/DD/YYYY"
        if (dateStr.includes('/')) {
            const parts = dateStr.split('/');
            return new Date(parseInt(parts[2]), parseInt(parts[0]) - 1, parseInt(parts[1]));
        } 
        // Handle other formats if needed
        else if (dateStr.includes('-')) {
            return new Date(dateStr);
        }
        else {
            try {
                return new Date(dateStr);
            } catch (e) {
                console.error('Could not parse date:', dateStr);
                return new Date(0);
            }
        }
    }
    
    function getSelectedRows() {
        const checkboxes = document.querySelectorAll('.request-checkbox:checked');
        const rows = document.querySelectorAll('.request-table tbody tr');
        const selectedRows = [];
        
        checkboxes.forEach(checkbox => {
            const rowIndex = checkbox.getAttribute('data-row-index');
            const row = rows[rowIndex];
            
            // Extract data from the row
            const rowData = {
                id: row.getAttribute('data-request-id'),
                date: row.cells[0].textContent.trim(),
                plateNo: row.cells[1].textContent.trim(),
                driver: row.cells[2].textContent.trim(),
                type: row.cells[3].textContent.trim(),
                division: row.cells[4].textContent.trim(),
                requestedBy: row.cells[5].textContent.trim(),
                status: row.cells[6].textContent.trim(),
                // Get the hidden fields from data attributes
                government_car_used: row.getAttribute('data-government-car-used') || '',
                purpose: row.getAttribute('data-purpose') || '',
                quantity: row.getAttribute('data-quantity') || ''
            };
            
            selectedRows.push(rowData);
        });
        
        return selectedRows;
    }
    
    function generatePDF(selectedRows) {
        // Load jsPDF and html2canvas libraries dynamically
        const loadScripts = [
            'https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js',
            'https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js'
        ];
        
        Promise.all(loadScripts.map(url => {
            return new Promise((resolve, reject) => {
                const script = document.createElement('script');
                script.src = url;
                script.onload = resolve;
                script.onerror = reject;
                document.head.appendChild(script);
            });
        }))
        .then(() => {
            // Create temporary container for PDF content
            const container = document.createElement('div');
            container.style.position = 'absolute';
            container.style.left = '-9999px';
            container.style.top = '-9999px';
            container.style.width = '800px';
            document.body.appendChild(container);
            
            // For each selected row, create a new request form
            selectedRows.forEach((row, index) => {
                // Create the form HTML template with proper layout
                const formHtml = `
                    <div style="font-family: Arial, sans-serif; padding: 15px; page-break-after: always; font-size: 10px;">
                        <div style="text-align: center; margin-bottom: 8px;">
                            <div style="font-size: 12px; font-weight: bold;">Republic of the Philippines</div>
                            <div style="font-size: 12px; font-weight: bold;">PROVINCE OF MISAMIS ORIENTAL</div>
                            <div style="font-size: 12px; font-weight: bold;">City of Cagayan de Oro</div>
                            <h1 style="margin: 0; font-size: 16px; font-weight: bold;">OFFICE OF THE PROVINCIAL ENGINEER</h1>
                        </div>
                        
                        <div style="border: 1px solid black; padding: 8px; margin-bottom: 8px;">
                            <div style="text-align: center; font-weight: bold; border: 1px solid black; padding: 4px; margin-bottom: 8px; font-size: 13px; background-color: #f0f0f0;">
                                REQUEST FOR FUEL/OIL/GREASE/BRAKE FLUID ISSUANCE
                            </div>
                            
                            <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                                <div style="width: 33%;">
                                    <strong>TO:</strong> ________________________________
                                </div>
                                <div style="width: 33%; text-align: center;">
                                    <strong>DATE:</strong> ${row.date || '_________'}
                                </div>
                                <div style="width: 33%; text-align: right; margin-right: 50px;">
                                    <strong>CONTROL NO:</strong> ${row.id || '_________'}
                                </div>
                            </div>
                            
                            <div style="margin-bottom: 8px; font-style: italic;">
                                The release to fuel/oil/lubricants is authorized chargeable to specific office appropriation
                            </div>
                            
                            <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                                <div style="width: 48%;">
                                    <strong>OFFICE:</strong> ${row.division || '_________________'}
                                </div>
                                <div style="width: 48%; text-align: left; margin-left: 80px;">
                                    <strong>REQUESTED BY:</strong> ${row.requestedBy || '_________________'}
                                </div>
                            </div>
                            
                            <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                                <div style="width: 48%;">
                                    <strong>VEHICLE:</strong> ${row.government_car_used ? `${row.government_car_used} ${row.plateNo}` : row.plateNo || '_________________'}
                                </div>
                            </div>
                            
                            <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                                <div style="width: 48%;">
                                    <strong>CONSUMPTION RATE:</strong> _________________
                                </div>
                                <div style="width: 48%; text-align: left; margin-left: 80px;">
                                    <strong>APPROVED BY:</strong>
                                </div>
                            </div>
                            
                            <div style="display: flex; justify-content: flex-start; margin-bottom: 8px;">
                                <!-- Table with fixed width instead of 100% width -->
<table style="width: 45%; border-collapse: collapse; margin-bottom: 8px;">
    <tr>
        <th style="border: 1px solid black; padding: 3px; background-color: #f0f0f0;">REQUESTED</th>
        <th style="border: 1px solid black; padding: 3px; background-color: #f0f0f0;">UNIT</th>
        <th style="border: 1px solid black; padding: 3px; background-color: #f0f0f0;">GALLON</th>
        <th style="border: 1px solid black; padding: 3px; background-color: #f0f0f0;">LITERS</th>
    </tr>
    <tr>
        <td style="border: 1px solid black; padding: 3px;">FUEL</td>
        <td style="border: 1px solid black; padding: 3px;">${row.type.toLowerCase() === 'fuel' ? row.quantity : ''}</td>
        <td style="border: 1px solid black; padding: 3px;"></td>
        <td style="border: 1px solid black; padding: 3px;"></td>
    </tr>
    <tr>
        <td style="border: 1px solid black; padding: 3px;">OIL</td>
        <td style="border: 1px solid black; padding: 3px;">${row.type.toLowerCase() === 'oil' ? row.quantity : ''}</td>
        <td style="border: 1px solid black; padding: 3px;"></td>
        <td style="border: 1px solid black; padding: 3px;"></td>
    </tr>
    <tr>
        <td style="border: 1px solid black; padding: 3px;">GREASE</td>
        <td style="border: 1px solid black; padding: 3px;">${row.type.toLowerCase() === 'grease' ? row.quantity : ''}</td>
        <td style="border: 1px solid black; padding: 3px;"></td>
        <td style="border: 1px solid black; padding: 3px;"></td>
    </tr>
    <tr>
        <td style="border: 1px solid black; padding: 3px;">BRAKE FLUID</td>
        <td style="border: 1px solid black; padding: 3px;">${row.type.toLowerCase() === 'brake fluid' ? row.quantity : ''}</td>
        <td style="border: 1px solid black; padding: 3px;"></td>
        <td style="border: 1px solid black; padding: 3px;"></td>
    </tr>
</table>
                                <!-- Added an empty div to balance the layout -->
                                <div style="width: 55%; text-align: center; margin-left:70px">
                                    <div style="text-align: center; ">
                                        <h3 style="margin: 0; font-size: 13px; font-weight: bold;">PETER M. UNABIA</h3>
                                        <div style="padding-top: 2px; font-size: 9px;">
                                            Provincial Governor
                                        </div>
                                        <div style="text-align: left; margin-top: 60px; margin-left:50px">
                                            <strong>BY AUTHORITY OF THE GOVERNOR</strong>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                                <div style="width: 48%;">
                                    <div style="margin-bottom: 4px;">
                                        <strong>SPECIFICATION OF REQUEST:</strong> ______________
                                    </div>
                                    <div style="margin-bottom: 4px;">
                                        <strong>PERIOD OF TRAVEL:</strong> _________________
                                    </div>
                                    <div style="margin-bottom: 4px;">
                                        <strong>REMARKS:</strong> _________________
                                    </div>
                                </div>
                                <div style="width: 48%; ">
                                    <div style="text-align: center; margin-top:-5px; ">
                                        <h3 style="margin: 0; font-size: 13px; font-weight: bold;">JOHN VENICE L. LADAGA</h3>
                                        <div style=" padding-top: 2px; font-size: 9px;">
                                            Provincial Administrator
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div style="display: flex; justify-content: center; margin-top: 10px;">
                                <div style="text-align: center;">
                                    <h3 style="margin: 0; font-size: 13px; font-weight: bold;">ROY T. KILAT</h3>
                                    <div style="padding-top: 2px; font-size: 9px;">
                                        Supply In-Charge - Designate
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div style="text-align: center; margin-bottom: 8px;">
                            <h1 style="margin: 0; font-size: 16px; font-weight: bold;">DRIVER'S TRIP TICKET</h1>
                        </div>
                        
                        <div style="margin-bottom: 5px; font-style: italic; text-align: left;">
                            To be filled by the Administrative Official/Authorized Officer.
                        </div>
                        
                        <div style="margin-bottom: 12px;">
                            <ol style="padding-left: 20px;">
                                <li style="margin-bottom: 4px;">
                                    <strong>Name of the Driver:</strong> ${row.driver || '_________________'}
                                </li>
                                <li style="margin-bottom: 4px;">
                                    <strong>Government Car Used and Number:</strong> ${row.government_car_used ? `${row.government_car_used} ${row.plateNo}` : row.plateNo || '_________________'}
                                </li>
                                <li style="margin-bottom: 4px;">
                                    <strong>Name of Authorized Passenger:</strong> _______________________________
                                </li>
                                <li style="margin-bottom: 4px;">
                                    <strong>Place to be Visited:</strong> ______________________________________
                                </li>
                                <li style="margin-bottom: 4px;">
                                    <strong>Purpose:</strong> ${row.purpose || row.type || '_________________'}
                                </li>
                            </ol>
                        </div>
                        
                        <div style="display: flex; justify-content: space-between; margin-bottom: 15px;">
                            <div style="width: 48%;">
                                <div style="margin-bottom: 4px; margin-right:20px">
                                    <strong>Approved by:</strong>
                                </div>
                                <div style="text-align: center; margin-top: 10px;">
                                    <h3 style="margin: 0; font-size: 13px; font-weight: bold;">PRISCO G. VALMORIA</h3>
                                    <div style=" padding-top: 2px; font-size: 9px;">
                                        Provincial Engineer
                                    </div>
                                </div>
                            </div>
                            <div style="width: 48%;">
                                <div style="text-align: center; margin-bottom: 4px; margin-right">
                                    <strong>BY AUTHORITY OF THE GOVERNOR</strong>
                                </div>
                                <div style="text-align: center; margin-top: 10px;">
                                    <h3 style="margin: 0; font-size: 13px; font-weight: bold;">JOHN VENICE L. LADAGA</h3>
                                    <div style=" padding-top: 2px; font-size: 9px;">
                                        Provincial Administrator
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Updated "To be filled up by DRIVERS" section with longer blank lines -->
                        <div style="margin-bottom: 5px; font-style: italic;">
                            <strong>To be filled up by "DRIVERS"</strong>
                        </div>
                        
                        <div style="margin-bottom: 8px;">
                            <ol style="padding-left: 20px;">
                                <li style="margin-bottom: 8px;">
                                    <strong>Time of departure from office/garage:</strong> ________ __________________________________________ __________________________________________a.m/p.m
                                </li>
                                <li style="margin-bottom: 8px;">
                                    <strong>Time of (as per no. above):</strong> ________ __________________________________________ __________________________________________ _________a.m/p.m
                                </li>
                                <li style="margin-bottom: 8px;">
                                    <strong>Name of departure (as per no. 4 above):</strong> ________ __________________________________________ _________________________________________a.m/p.m
                                </li>
                                <li style="margin-bottom: 8px;">
                                    <strong>Time of arrival/back to office/garage:</strong> ________ __________________________________________ ___________________________________________a.m/p.m
                                </li>
                                <li style="margin-bottom: 8px;">
                                    <strong>Appropriate time traveled to and from:</strong> ________ __________________________________________ __________________________________________a.m/p.m
                                </li>
                                <li style="margin-bottom: 8px;">
                                    <strong>Gasoline issued purchased and used</strong>
                                    <ol type="a" style="padding-left: 80px;">
                                        <li style="margin-bottom: 8px;">
                                            <strong>Balance in tank:</strong> ________ __________________________________________ ___________________________________________liters
                                        </li>
                                        <li style="margin-bottom: 6px;">
                                            <strong>Add purchased during the trip:</strong> ________ __________________________________________ ______________________________liters
                                        </li>
                                        <li style="margin-bottom: 6px;">
                                            <strong>Total:</strong> ________ __________________________________________ __________________________________________ _________liters
                                        </li>
                                        <li style="margin-bottom: 6px;">
                                            <strong>Less: Used during the trip (from & to):</strong> ________ __________________________________________ ________________________liters
                                        </li>
                                        <li style="margin-bottom: 6px;">
                                            <strong>Total balance in tank at the end of the trip:</strong> ________ __________________________________________ ____________________liters
                                        </li>
                                    </ol>
                                </li>
                                <li style="margin-bottom: 8px;">
                                    <strong>Gear oil used:</strong> ________ __________________________________________ __________________________________________ ____________________quarts
                                </li>
                                <li style="margin-bottom: 8px;">
                                    <strong>Lubricating oil used:</strong> ________ __________________________________________ __________________________________________ ______________quarts
                                </li>
                                <li style="margin-bottom: 8px;">
                                    <strong>Grease oil used:</strong> ________ __________________________________________ __________________________________________ __________________quarts
                                </li>
                                <li style="margin-bottom: 8px;">
                                    <strong>Brake fluid used:</strong> ________ __________________________________________ __________________________________________ __________________quarts
                                </li>
                                <li style="margin-bottom: 8px;">
                                    <strong>Speedometer reading, if any:</strong> ________ __________________________________________ __________________________________________ ________kms/hr.
                                </li>
                                <li style="margin-bottom: 8px;">
                                    <strong>End of the trip:</strong> ________ __________________________________________ __________________________________________ ____________________kms.
                                </li>
                                <li style="margin-bottom: 8px;">
                                    <strong>Beginning of the trip:</strong> ________ __________________________________________ __________________________________________ _______________kms.
                                </li>
                                <li style="margin-bottom: 20px;">
                                    <strong>Total distance traveled (per no. above):</strong> ________ __________________________________________ __________________________________________kms.
                                </li>
                            </ol>
                        </div>
                        
                        <!-- Centered Driver's Name and Signature with space from the last item -->
                        <div style="margin-top: 10px; text-align: center;">
                            <div style="width: 250px; display: inline-block; text-align: center;">
                                <div style="border-top: 1px solid black; padding-top: 2px; font-size: 9px;">
                                    Driver's Name and Signature
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                
                // Add the form to the container
                const formDiv = document.createElement('div');
                formDiv.innerHTML = formHtml;
                container.appendChild(formDiv);
            });
            
            // Now generate the PDF
            // Use setTimeout to ensure the container is fully rendered
            setTimeout(() => {
                const { jsPDF } = window.jspdf;
                const pdf = new jsPDF('p', 'pt', 'a4');
                
                // Define recursive function to handle multiple pages
                const processPage = (pageIndex) => {
                    if (pageIndex >= selectedRows.length) {
                        // All pages processed, save the PDF
                        pdf.save('Request_Forms.pdf');
                        // Clean up
                        document.body.removeChild(container);
                        return;
                    }
                    
                    // Get the current form element
                    const formElement = container.children[pageIndex];
                    
                    // Use html2canvas to convert the form to an image
                    window.html2canvas(formElement, {
                        scale: 2, // Higher scale for better quality
                        useCORS: true,
                        logging: false
                    }).then(canvas => {
                        const imgData = canvas.toDataURL('image/jpeg', 1.0);
                        
                        // Add page if not the first page
                        if (pageIndex > 0) {
                            pdf.addPage();
                        }
                        
                        // Add the image to the PDF
                        const imgWidth = 530; // slightly less than A4 width
                        const imgHeight = canvas.height * imgWidth / canvas.width;
                        pdf.addImage(imgData, 'JPEG', 30, 30, imgWidth, imgHeight);
                        
                        // Process the next page
                        processPage(pageIndex + 1);
                    });
                };
                
                // Start processing from the first page
                processPage(0);
            }, 500);
        })
        .catch(error => {
            console.error('Error loading scripts:', error);
            alert('Error generating PDF. Please try again later.');
        });
    }
    
    function generateExcel(selectedRows) {
        // Load SheetJS (XLSX) library dynamically
        const script = document.createElement('script');
        script.src = 'https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js';
        script.onload = function() {
            // Prepare data for Excel
            const headers = ['Date', 'Plate No.', 'Driver', 'Type', 'Division', 'Requested by', 'Status'];
            const data = selectedRows.map(row => [
                row.date,
                row.plateNo,
                row.driver,
                row.type,
                row.division,
                row.requestedBy,
                row.status
            ]);
            
            // Add headers to data array
            data.unshift(headers);
            
            // Create workbook and worksheet
            const wb = XLSX.utils.book_new();
            const ws = XLSX.utils.aoa_to_sheet(data);
            
            // Add worksheet to workbook
            XLSX.utils.book_append_sheet(wb, ws, 'Request Records');
            
            // Generate Excel file and trigger download
            XLSX.writeFile(wb, `request_records_${new Date().toISOString().slice(0, 10)}.xlsx`);
        };
        
        script.onerror = function() {
            console.error('Error loading Excel library');
            alert('Could not generate Excel file. Please try again.');
        };
        
        document.head.appendChild(script);
    }
});