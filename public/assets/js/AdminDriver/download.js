// Function to load required libraries for PDF export
function loadPdfLibraries() {
    return new Promise((resolve, reject) => {
        // Check if libraries are already loaded
        if (window.html2canvas && (window.jsPDF || window.jspdf)) {
            console.log("PDF libraries already loaded");
            resolve();
            return;
        }
        
        console.log("Loading PDF libraries...");
        
        // Load html2canvas
        const html2canvasScript = document.createElement('script');
        html2canvasScript.src = 'https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js';
        html2canvasScript.integrity = 'sha512-BNaRQnYJYiPSqHHDb58B0yaPfCu+Wgds8Gp/gU33kqBtgNS4tSPHuGibyoeqMV/TJlSKda6FXzoEyYGjTe+vXA==';
        html2canvasScript.crossOrigin = 'anonymous';
        
        // Load jsPDF
        const jsPdfScript = document.createElement('script');
        jsPdfScript.src = 'https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js';
        jsPdfScript.integrity = 'sha512-qZvrmS2ekKPF2mSznTQsxqPgnpkI4DNTlrdUmTzrDgektczlKNRRhy5X5AAOnx5S09ydFYWWNSfcEqDTTHgtNA==';
        jsPdfScript.crossOrigin = 'anonymous';
        
        // Load jspdf-autotable plugin
        const autoTableScript = document.createElement('script');
        autoTableScript.src = 'https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.28/jspdf.plugin.autotable.min.js';
        autoTableScript.integrity = 'sha512-NHKtHRDx6HKD3M9cgZH7WSg3nzpQB5jKmr0DtCo6ZCe0l6oKFbG0GHJdgXIHEO4Ry7OOTZKZoJIpWPwYX4KkSw==';
        autoTableScript.crossOrigin = 'anonymous';
        
        // Add scripts to document head
        document.head.appendChild(html2canvasScript);
        document.head.appendChild(jsPdfScript);
        document.head.appendChild(autoTableScript);
        
        // Resolve promise when all scripts are loaded
        let loadedCount = 0;
        const onLoad = () => {
            loadedCount++;
            console.log(`Loaded library ${loadedCount}/3`);
            if (loadedCount === 3) {
                console.log("All PDF libraries loaded successfully");
                resolve();
            }
        };
        
        const onError = (e) => {
            console.error("Failed to load PDF library:", e);
            reject(new Error("Failed to load required PDF libraries"));
        };
        
        html2canvasScript.onload = onLoad;
        jsPdfScript.onload = onLoad;
        autoTableScript.onload = onLoad;
        
        html2canvasScript.onerror = onError;
        jsPdfScript.onerror = onError;
        autoTableScript.onerror = onError;
    });
}

// Helper function to detect which jsPDF instance is available
function getJsPDF() {
    // Different ways jsPDF might be exposed
    if (window.jspdf && window.jspdf.jsPDF) {
        console.log("Using jspdf.jsPDF");
        return window.jspdf.jsPDF;
    } else if (window.jsPDF) {
        console.log("Using window.jsPDF");
        return window.jsPDF;
    } else if (typeof jspdf !== 'undefined' && jspdf.jsPDF) {
        console.log("Using global jspdf.jsPDF");
        return jspdf.jsPDF;
    } else if (typeof jsPDF !== 'undefined') {
        console.log("Using global jsPDF");
        return jsPDF;
    }
    
    console.error("No jsPDF constructor found");
    throw new Error("jsPDF library not found. Make sure it's properly loaded in your HTML.");
}

// Function to initialize PDF export for driver consumption
function initDriverConsumptionPdfExport() {
    console.log("Initializing PDF export button");
    
    // Create download button if it doesn't exist already
    if (!document.getElementById('exportDriverConsumptionBtn')) {
        console.log("Creating export button");
        
        // Create download button
        const downloadBtn = document.createElement('button');
        downloadBtn.id = 'exportDriverConsumptionBtn';
        downloadBtn.className = 'apply-filter-btn';
        downloadBtn.style.marginLeft = 'auto'; // Push to right
        downloadBtn.innerHTML = 'Export to PDF';
        
        // Add button to the product details header
        const productDetailsHeader = document.querySelector('.product-details-header');
        if (productDetailsHeader) {
            productDetailsHeader.appendChild(downloadBtn);
            console.log("Export button added to product details header");
        } else {
            console.warn("Product details header not found");
        }
        
        // Add event listener to the download button
        downloadBtn.addEventListener('click', function() {
            console.log("Export button clicked");
            exportDriverConsumptionPdf();
        });
    } else {
        console.log("Export button already exists");
    }
}

// Function to show loading overlay
function showPdfLoadingOverlay() {
    // Check if overlay already exists
    if (document.getElementById('pdfLoadingOverlay')) {
      document.getElementById('pdfLoadingOverlay').style.display = 'flex';
      return;
    }
    
    // Create overlay container
    const overlay = document.createElement('div');
    overlay.id = 'pdfLoadingOverlay';
    overlay.className = 'pdf-loading-overlay';
    
    // Create content container
    const content = document.createElement('div');
    content.className = 'pdf-loading-content';
    
    // Create spinner
    const spinner = document.createElement('div');
    spinner.className = 'pdf-spinner';
    
    // Create text
    const text = document.createElement('div');
    text.textContent = 'Generating PDF, please wait...';
    text.style.fontWeight = 'bold';
    
    // Create subtext
    const subtext = document.createElement('div');
    subtext.textContent = 'This may take a few moments';
    subtext.style.fontSize = '14px';
    subtext.style.marginTop = '8px';
    subtext.style.color = '#666';
    
    // Assemble the overlay
    content.appendChild(spinner);
    content.appendChild(text);
    content.appendChild(subtext);
    overlay.appendChild(content);
    document.body.appendChild(overlay);
  }
  
  // Function to hide loading overlay
  function hidePdfLoadingOverlay() {
    const overlay = document.getElementById('pdfLoadingOverlay');
    if (overlay) {
      overlay.style.display = 'none';
    }
  }
  
  // Modify the exportDriverConsumptionPdf function to use the new loading overlay
  async function exportDriverConsumptionPdf() {
    console.log("Starting PDF export process");
    
    // Show loading overlay
    showPdfLoadingOverlay();
    
    try {
      // Load required libraries
      console.log("Loading PDF libraries");
      await loadPdfLibraries();
      
      // Get jsPDF constructor
      const jsPDFConstructor = getJsPDF();
      console.log("jsPDF constructor found:", jsPDFConstructor);
      
      // Create a new document instance
      const doc = new jsPDFConstructor();
      console.log("Created PDF document");
      
      // Get driver and product information
      const driverNameEl = document.getElementById('driverNameDisplay');
      const productNameEl = document.getElementById('productNameDisplay');
      const startDateEl = document.getElementById('consumptionStartDate');
      const endDateEl = document.getElementById('consumptionEndDate');
      
      if (!driverNameEl || !productNameEl) {
        throw new Error("Required elements not found. Make sure driver and product names are displayed.");
      }
      
      const driverName = driverNameEl.textContent;
      const productName = productNameEl.textContent;
      const startDate = startDateEl ? startDateEl.value : '';
      const endDate = endDateEl ? endDateEl.value : '';
      
      console.log("Report details:", { driverName, productName, startDate, endDate });
      
      // Format dates for display
      const formattedStartDate = startDate ? new Date(startDate).toLocaleDateString() : 'All';
      const formattedEndDate = endDate ? new Date(endDate).toLocaleDateString() : 'All';
      
      // Try to load logo with a timeout
      try {
        console.log("Attempting to load logo image");
        const img = new Image();
        img.src = 'images/logo.png';
        
        // Set a timeout to prevent hanging if image loading takes too long
        const imageLoadPromise = new Promise((resolve) => {
          img.onload = () => {
            console.log("Logo loaded successfully");
            resolve(true);
          };
          img.onerror = () => {
            console.warn("Logo failed to load");
            resolve(false);
          };
          setTimeout(() => {
            console.warn("Logo load timeout");
            resolve(false);
          }, 3000); // 3 second timeout
        });
        
        // Wait for image to load or timeout
        const imageLoaded = await imageLoadPromise;
        
        if (imageLoaded) {
          // Image loaded successfully, add it to PDF
          createDriverConsumptionPDFWithLogo(doc, img, driverName, productName, formattedStartDate, formattedEndDate);
        } else {
          // Image failed to load, create PDF without logo
          console.warn("Creating PDF without logo");
          createDriverConsumptionPDFWithoutLogo(doc, driverName, productName, formattedStartDate, formattedEndDate);
        }
      } catch (imageError) {
        console.error("Error handling logo image:", imageError);
        createDriverConsumptionPDFWithoutLogo(doc, driverName, productName, formattedStartDate, formattedEndDate);
      }
      
    } catch (error) {
      console.error('Error generating PDF:', error);
      alert('Error generating PDF: ' + error.message);
      
      // Hide loading overlay
      hidePdfLoadingOverlay();
    }
  }
// Function to create PDF with logo
function createDriverConsumptionPDFWithLogo(doc, img, driverName, productName, formattedStartDate, formattedEndDate) {
    console.log("Creating PDF with logo");
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
    doc.text("DRIVER CONSUMPTION REPORT", 105, textStartY + 15, { align: 'center' });
    
    // Add horizontal line
    doc.setDrawColor(0, 0, 0);
    doc.setLineWidth(0.5);
    doc.line(15, textStartY + 20, 195, textStartY + 20);
    
    // Add document title
    doc.setFontSize(12);
    doc.setFont('helvetica', 'bold');
    doc.text(`${driverName} - ${productName} REQUEST HISTORY`, 105, textStartY + 30, { align: 'center' });
    
    // Add report metadata in two rows
    doc.setFontSize(8);
    doc.setFont('helvetica', 'normal');
    
    // First row: Date Range and Report Generated side by side
    doc.text(`Date Range: ${formattedStartDate} to ${formattedEndDate}`, 15, textStartY + 40);
    doc.text(`Report Generated: ${new Date().toLocaleDateString()}`, 105, textStartY + 40);
    
    // Second row: Driver and Product side by side
    doc.text(`Driver: ${driverName}`, 15, textStartY + 47);
    doc.text(`Product: ${productName}`, 105, textStartY + 47);
    
    // Add table data
    addDriverConsumptionTableToDocument(doc, textStartY + 55);
}

// Function to create PDF without logo
function createDriverConsumptionPDFWithoutLogo(doc, driverName, productName, formattedStartDate, formattedEndDate) {
    console.log("Creating PDF without logo");
    // Set up document header
    doc.setFontSize(16);
    doc.setFont('helvetica', 'bold');
    doc.text("MISAMIS ORIENTAL PROVINCIAL CAPITOL", 105, 20, { align: 'center' });
    
    doc.setFontSize(14);
    doc.setFont('helvetica', 'normal');
    doc.text("PROVINCIAL ENGINEERING OFFICE", 105, 28, { align: 'center' });
    
    doc.setFontSize(12);
    doc.text("DRIVER CONSUMPTION REPORT", 105, 35, { align: 'center' });
    
    // Add horizontal line
    doc.setDrawColor(0, 0, 0);
    doc.setLineWidth(0.5);
    doc.line(15, 40, 195, 40);
    
    // Add document title
    doc.setFontSize(14);
    doc.setFont('helvetica', 'bold');
    doc.text(`${driverName} - ${productName} REQUEST HISTORY`, 105, 50, { align: 'center' });
    
    // Add report metadata in two rows
    doc.setFontSize(10);
    doc.setFont('helvetica', 'normal');
    
    // First row: Date Range and Report Generated side by side
    doc.text(`Date Range: ${formattedStartDate} to ${formattedEndDate}`, 15, 60);
    doc.text(`Report Generated: ${new Date().toLocaleDateString()}`, 105, 60);
    
    // Second row: Driver and Product side by side
    doc.text(`Driver: ${driverName}`, 15, 67);
    doc.text(`Product: ${productName}`, 105, 67);
    
    // Add table data
    addDriverConsumptionTableToDocument(doc, 75);
}

// Common function to add table data to the document
function addDriverConsumptionTableToDocument(doc, startY) {
    console.log("Adding table data to document");
    try {
      // Get table data from current page
      const tableBody = [];
      const tableRows = document.querySelectorAll('#productRequestsList table tbody tr');
      console.log(`Found ${tableRows.length} table rows`);
      
      if (!tableRows || tableRows.length === 0) {
        // Handle empty table case
        console.log("No table rows found");
        doc.setFontSize(12);
        doc.setFont('helvetica', 'italic');
        doc.text("No request data available for the selected period", 105, startY + 20, { align: 'center' });
      } else {
        // Process table rows
        tableRows.forEach(row => {
          const cells = row.querySelectorAll('td');
          if (cells.length >= 4) {
            tableBody.push([
              cells[0].textContent.trim(), // Date
              cells[1].textContent.trim(), // Time
              cells[2].textContent.trim(), // Quantity
              cells[3].textContent.trim()  // Status
            ]);
          }
        });
        
        console.log(`Processed ${tableBody.length} rows for table`);
        
        // Create table with headers using jspdf-autotable
        doc.autoTable({
          head: [['Date', 'Time', 'Quantity', 'Status']],
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
        doc.text('This is an official document. All consumption records are subject to audit.', 105, 287, { align: 'center' });
        doc.text(`Page ${i} of ${pageCount}`, 195, 287, { align: 'right' });
      }
      
      // Create a filename with driver name, product and date range
      const driverName = document.getElementById('driverNameDisplay').textContent;
      const productName = document.getElementById('productNameDisplay').textContent;
      const startDate = document.getElementById('consumptionStartDate').value;
      const endDate = document.getElementById('consumptionEndDate').value;
      
      let filename = `${driverName}_${productName}`.replace(/\s+/g, '_');
      
      if (startDate && endDate) {
        const startDateForFile = startDate.replace(/-/g, '');
        const endDateForFile = endDate.replace(/-/g, '');
        filename += `_${startDateForFile}_to_${endDateForFile}`;
      }
      
      const fullFilename = `${filename}_Consumption_Report.pdf`;
      console.log(`Saving PDF as: ${fullFilename}`);
      doc.save(fullFilename);
      
      // Hide loading indicators
      hidePdfLoadingOverlay();
      
      console.log("PDF generation completed successfully");
      
    } catch (error) {
      console.error("Error adding table to document:", error);
      
      // Add error message to PDF
      doc.setFontSize(12);
      doc.setFont('helvetica', 'italic');
      doc.setTextColor(255, 0, 0);
      doc.text("Error processing table data: " + error.message, 105, startY + 30, { align: 'center' });
      
      // Save the PDF anyway with error message
      doc.save(`Driver_Consumption_Report_With_Errors.pdf`);
      
      // Hide loading indicators
      hidePdfLoadingOverlay();
      
      // Throw the error so it can be caught by the main function
      throw error;
    }
  }

// Initialize the PDF export functionality when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    console.log("DOM content loaded, checking for export button");
    
    // Check if we're on the product details page
    const productDetailsSection = document.getElementById('productDetailsSection');
    if (productDetailsSection && window.getComputedStyle(productDetailsSection).display !== 'none') {
        console.log("Product details section is visible, initializing export button");
        initDriverConsumptionPdfExport();
    }
    
    // Check for the back button to reinitialize when needed
    const backBtn = document.getElementById('backToProductsBtn');
    if (backBtn) {
        console.log("Back button found, adding listener");
        backBtn.addEventListener('click', function() {
            console.log("Back button clicked");
            // When going back to products view, we'll need to add the button again when details are shown
            const detailsSection = document.getElementById('productDetailsSection');
            if (detailsSection) {
                // Listen for when the product details section becomes visible again
                const observer = new MutationObserver(function(mutations) {
                    mutations.forEach(function(mutation) {
                        if (mutation.type === 'attributes' && 
                            mutation.attributeName === 'style' && 
                            window.getComputedStyle(detailsSection).display !== 'none') {
                            console.log("Product details section became visible");
                            setTimeout(initDriverConsumptionPdfExport, 300);
                            observer.disconnect(); // Stop observing once initialized
                        }
                    });
                });
                
                // Start observing
                observer.observe(detailsSection, { attributes: true });
            }
        });
    }
    
    // If showProductDetails function exists, enhance it
    if (typeof window.showProductDetails === 'function') {
        console.log("Enhancing showProductDetails function");
        const originalShowProductDetails = window.showProductDetails;
        
        window.showProductDetails = function(productId) {
            console.log("Enhanced showProductDetails called for product ID:", productId);
            // Call the original function first
            originalShowProductDetails(productId);
            
            // Then initialize our PDF export with a delay to ensure content is loaded
            setTimeout(function() {
                console.log("Initializing PDF export after product details loaded");
                initDriverConsumptionPdfExport();
            }, 500);
        };
    }
    
    // Add direct event listener to export button if it already exists
    const exportBtn = document.getElementById('exportDriverConsumptionBtn');
    if (exportBtn) {
        console.log("Export button found, adding direct click listener");
        exportBtn.addEventListener('click', function() {
            console.log("Export button clicked directly");
            exportDriverConsumptionPdf();
        });
    }
});

// Add a failsafe direct event listener that runs after a short delay
setTimeout(function() {
    const exportBtn = document.getElementById('exportDriverConsumptionBtn');
    if (exportBtn) {
        console.log("Adding failsafe click listener to export button");
        exportBtn.addEventListener('click', function(event) {
            console.log("Export button clicked (failsafe handler)");
            event.preventDefault();
            event.stopPropagation();
            exportDriverConsumptionPdf();
        });
    }
}, 2000);