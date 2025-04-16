// Function to load HTML2Canvas since it's not in your script tags
function loadHtml2Canvas() {
    return new Promise((resolve, reject) => {
        if (window.html2canvas) {
            resolve(window.html2canvas);
            return;
        }
        
        const script = document.createElement('script');
        script.src = 'https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js';
        script.integrity = 'sha512-BNaRQnYJYiPSqHHDb58B0yaPfCu+Wgds8Gp/gU33kqBtgNS4tSPHuGibyoeqMV/TJlSKda6FXzoEyYGjTe+vXA==';
        script.crossOrigin = 'anonymous';
        script.onload = () => resolve(window.html2canvas);
        script.onerror = (e) => {
            console.error("Failed to load html2canvas:", e);
            reject(new Error("Failed to load html2canvas"));
        };
        document.head.appendChild(script);
    });
}

// Helper function to detect which jsPDF instance is available
function getJsPDF() {
    // Different ways jsPDF might be exposed
    if (window.jspdf && window.jspdf.jsPDF) {
        return window.jspdf.jsPDF;
    } else if (window.jsPDF) {
        return window.jsPDF;
    } else if (typeof jspdf !== 'undefined' && jspdf.jsPDF) {
        return jspdf.jsPDF;
    } else if (typeof jsPDF !== 'undefined') {
        return jsPDF;
    }
    
    throw new Error("jsPDF library not found. Make sure it's properly loaded in your HTML.");
}

// Extend your existing initViewDetailsModal function to call the new function
const originalInitViewDetailsModal = typeof initViewDetailsModal !== 'undefined' ? initViewDetailsModal : function() {};
initViewDetailsModal = function() {
    originalInitViewDetailsModal();
    initPdfExport();
};

// Add this function to your initViewDetailsModal function or wherever appropriate
function initPdfExport() {
    const exportBtn = document.getElementById('exportPdfBtn');
    
    if (exportBtn) {
        exportBtn.addEventListener('click', async function() {
            // Show loading indicator
            const loadingIndicator = document.getElementById('loadingRequests');
            if (loadingIndicator) {
                loadingIndicator.style.display = 'block';
                loadingIndicator.textContent = 'Generating PDF...';
            }
            
            try {
                // Try to get jsPDF constructor from whatever form it's available
                let jsPDFConstructor;
                try {
                    jsPDFConstructor = getJsPDF();
                    console.log("jsPDF constructor found:", jsPDFConstructor);
                } catch (err) {
                    console.error("Error finding jsPDF:", err);
                    throw new Error("jsPDF library not found. Please check that the script is loaded correctly.");
                }
                
                // Create a new document instance
                const doc = new jsPDFConstructor();
                
                // Verify autoTable is available
                if (typeof doc.autoTable !== 'function') {
                    console.error("autoTable not found on doc object:", doc);
                    throw new Error("jsPDF autoTable plugin not properly loaded");
                }
                
                // Load html2canvas as it's still needed
                await loadHtml2Canvas().catch(err => {
                    console.error("Failed to load html2canvas:", err);
                    throw new Error("Failed to load canvas library. Please check your internet connection and try again.");
                });
                
                // Safely get DOM elements with error checking
                const modalProductNameEl = document.getElementById('modalProductName');
                const modalStockRemainingEl = document.getElementById('modalStockRemaining');
                const startDateEl = document.getElementById('startDate');
                const endDateEl = document.getElementById('endDate');
                
                if (!modalProductNameEl || !modalStockRemainingEl) {
                    throw new Error("Required product information elements not found");
                }
                
                // Get product details with fallbacks
                const productName = modalProductNameEl ? modalProductNameEl.textContent.replace(" Details", "") : "Unknown Product";
                const stockRemaining = modalStockRemainingEl ? modalStockRemainingEl.textContent : "N/A";
                
                // Get date filter values with fallbacks
                const startDate = startDateEl && startDateEl.value ? startDateEl.value : '';
                const endDate = endDateEl && endDateEl.value ? endDateEl.value : '';
                
                // Format dates for display
                const formattedStartDate = startDate ? new Date(startDate).toLocaleDateString() : 'All';
                const formattedEndDate = endDate ? new Date(endDate).toLocaleDateString() : 'All';
                
                // Preload image to test if it's available
                const img = new Image();
                img.src = 'images/logo.png';
                
                // Set a timeout to prevent hanging if image loading takes too long
                const imageLoadPromise = new Promise((resolve, reject) => {
                    img.onload = () => resolve(true);
                    img.onerror = () => resolve(false); // Don't reject, we'll handle this case
                    
                    // Set timeout for image loading
                    setTimeout(() => resolve(false), 3000); // 3 second timeout
                });
                
                // Wait for image to load or timeout
                const imageLoaded = await imageLoadPromise;
                
                if (imageLoaded) {
                    // Image loaded successfully, add it to PDF
                    createPDFWithLogo(doc, img, productName, stockRemaining, 
                                    formattedStartDate, formattedEndDate);
                } else {
                    // Image failed to load, create PDF without logo
                    console.warn("Logo image failed to load, creating PDF without logo");
                    createPDFWithoutLogo(doc, productName, stockRemaining, 
                                      formattedStartDate, formattedEndDate);
                }
                
            } catch (error) {
                console.error('Error generating PDF:', error);
                alert('Error generating PDF: ' + error.message);
                
                // Hide loading indicator
                const loadingIndicator = document.getElementById('loadingRequests');
                if (loadingIndicator) {
                    loadingIndicator.style.display = 'none';
                    loadingIndicator.textContent = 'Loading...';
                }
            }
        });
    } else {
        console.warn("Export PDF button not found in the DOM");
    }
}

// Function to create PDF with logo
function createPDFWithLogo(doc, img, productName, stockRemaining, formattedStartDate, formattedEndDate) {
    // Calculate logo dimensions while preserving aspect ratio
    const maxLogoWidth = 20;
    const logoAspectRatio = img.width / img.height;
    const logoWidth = maxLogoWidth;
    const logoHeight = logoWidth / logoAspectRatio;
    
    // Add logo centered at the top
    doc.addImage(img, 'PNG', (doc.internal.pageSize.width - logoWidth) / 2, 10, logoWidth, logoHeight);
    
    // Add government header text - moved down to accommodate centered logo
    const textStartY = 10 + logoHeight + 10; // Logo Y position + logo height + spacing
    
    doc.setFontSize(14);
    doc.setFont('helvetica', 'bold');
    doc.text("MISAMIS ORIENTAL PROVINCIAL CAPITOL", 105, textStartY, { align: 'center' });
    
    doc.setFontSize(12);
    doc.setFont('helvetica', 'normal');
    doc.text("PROVINCIAL ENGINEERING OFFICE", 105, textStartY + 8, { align: 'center' });
    
    doc.setFontSize(10);
    doc.text("INVENTORY REPORT", 105, textStartY + 15, { align: 'center' });
    
    // Add horizontal line
    doc.setDrawColor(0, 0, 0);
    doc.setLineWidth(0.5);
    doc.line(15, textStartY + 20, 195, textStartY + 20);
    
    // Add document title
    doc.setFontSize(12);
    doc.setFont('helvetica', 'bold');
    doc.text(`${productName} - INVENTORY WITHDRAWAL REPORT`, 105, textStartY + 30, { align: 'center' });
    
    // Add report metadata in two rows instead of four
    doc.setFontSize(8);
    doc.setFont('helvetica', 'normal');
    
    // First row: Date Range and Report Generated side by side
    doc.text(`Date Range: ${formattedStartDate} to ${formattedEndDate}`, 15, textStartY + 40);
    doc.text(`Report Generated: ${new Date().toLocaleDateString()}`, 105, textStartY + 40);
    
    // Second row: Stock Remaining and Document ID side by side
    doc.text(`Stock Remaining: ${stockRemaining}`, 15, textStartY + 47);
    doc.text(`Document ID: INV-${Date.now().toString().substring(5)}`, 105, textStartY + 47);
    
    // Add table data
    addTableToDocument(doc, textStartY + 55);
}

// Function to create PDF without logo
function createPDFWithoutLogo(doc, productName, stockRemaining, formattedStartDate, formattedEndDate) {
    // Set up document header
    doc.setFontSize(16);
    doc.setFont('helvetica', 'bold');
    doc.text("MISAMIS ORIENTAL PROVINCIAL CAPITOL", 105, 20, { align: 'center' });
    
    doc.setFontSize(14);
    doc.setFont('helvetica', 'normal');
    doc.text("PROVINCIAL ENGINEERING OFFICE", 105, 28, { align: 'center' });
    
    doc.setFontSize(12);
    doc.text("INVENTORY REPORT", 105, 35, { align: 'center' });
    
    // Add horizontal line
    doc.setDrawColor(0, 0, 0);
    doc.setLineWidth(0.5);
    doc.line(15, 40, 195, 40);
    
    // Add document title
    doc.setFontSize(14);
    doc.setFont('helvetica', 'bold');
    doc.text(`${productName} - INVENTORY WITHDRAWAL REPORT`, 105, 50, { align: 'center' });
    
    // Add report metadata in two rows instead of four
    doc.setFontSize(10);
    doc.setFont('helvetica', 'normal');
    
    // First row: Date Range and Report Generated side by side
    doc.text(`Date Range: ${formattedStartDate} to ${formattedEndDate}`, 15, 60);
    doc.text(`Report Generated: ${new Date().toLocaleDateString()}`, 105, 60);
    
    // Second row: Stock Remaining and Document ID side by side
    doc.text(`Stock Remaining: ${stockRemaining}`, 15, 67);
    doc.text(`Document ID: INV-${Date.now().toString().substring(5)}`, 105, 67);
    
    // Add table data
    addTableToDocument(doc, 75);
}

// Common function to add table data to the document
function addTableToDocument(doc, startY) {
    try {
        // Get table data from current page
        const tableBody = [];
        const tableRows = document.getElementById('withdrawalRequestsBody');
        
        if (!tableRows) {
            throw new Error("Withdrawal requests table not found");
        }
        
        const rows = tableRows.querySelectorAll('tr');
        
        if (rows.length === 0) {
            // Handle empty table case
            doc.setFontSize(12);
            doc.setFont('helvetica', 'italic');
            doc.text("No withdrawal data available for the selected period", 105, startY + 20, { align: 'center' });
        } else {
            // Process table rows
            rows.forEach(row => {
                const cells = row.querySelectorAll('td');
                if (cells.length >= 6) {
                    tableBody.push([
                        cells[0].textContent.trim(), // Date
                        cells[1].textContent.trim(), // Plate Number
                        cells[2].textContent.trim(), // Driver
                        cells[3].textContent.trim(), // Stock Before
                        cells[4].textContent.trim(), // Withdrawal
                        cells[5].textContent.trim()  // Balance/Stock After
                    ]);
                }
            });
            
            // Create table with headers using jspdf-autotable
            doc.autoTable({
                head: [['Date', 'Plate Number', 'Driver', 'Stock Before', 'Withdrawal', 'Balance']],
                body: tableBody,
                startY: startY,
                theme: 'grid',
                headStyles: { 
                    fillColor: [70, 70, 70],
                    textColor: [255, 255, 255],
                    fontStyle: 'bold'
                },
                styles: { 
                    fontSize: 9,
                    cellPadding: 3
                },
                alternateRowStyles: {
                    fillColor: [240, 240, 240]
                }
            });
        }
        
        // Add footer with page number
        const pageCount = doc.internal.getNumberOfPages();
        for(let i = 1; i <= pageCount; i++) {
            doc.setPage(i);
            
            // Footer line
            doc.setDrawColor(0, 0, 0);
            doc.setLineWidth(0.5);
            doc.line(15, 280, 195, 280);
            
            // Footer text
            doc.setFontSize(8);
            doc.text('This is an official document. All inventory transactions are subject to audit.', 105, 287, { align: 'center' });
            doc.text(`Page ${i} of ${pageCount}`, 195, 287, { align: 'right' });
        }
        
        // Save the PDF
        const modalProductNameEl = document.getElementById('modalProductName');
        const startDateEl = document.getElementById('startDate');
        const endDateEl = document.getElementById('endDate');
        
        const product = modalProductNameEl ? modalProductNameEl.textContent.replace(" Details", "") : "Product";
        let filename = product.replace(/\s+/g, '_');
        
        if (startDateEl && endDateEl && startDateEl.value && endDateEl.value) {
            const startDateForFile = startDateEl.value.replace(/-/g, '');
            const endDateForFile = endDateEl.value.replace(/-/g, '');
            filename += `_${startDateForFile}_to_${endDateForFile}`;
        }
        
        doc.save(`${filename}_Official_Report.pdf`);
        
        // Hide loading indicator
        const loadingIndicator = document.getElementById('loadingRequests');
        if (loadingIndicator) {
            loadingIndicator.style.display = 'none';
            loadingIndicator.textContent = 'Loading...';
        }
        
    } catch (error) {
        console.error("Error adding table to document:", error);
        
        // Add error message to PDF
        doc.setFontSize(12);
        doc.setFont('helvetica', 'italic');
        doc.setTextColor(255, 0, 0);
        doc.text("Error processing table data: " + error.message, 105, startY + 30, { align: 'center' });
        
        // Save the PDF anyway with error message
        doc.save(`Inventory_Report_With_Errors.pdf`);
        
        // Hide loading indicator
        const loadingIndicator = document.getElementById('loadingRequests');
        if (loadingIndicator) {
            loadingIndicator.style.display = 'none';
            loadingIndicator.textContent = 'Loading...';
        }
        
        // Throw the error so it can be caught by the main function
        throw error;
    }
}

// If the page is already loaded, initialize the PDF export functionality
if (document.readyState === 'complete' || document.readyState === 'interactive') {
    initPdfExport();
}