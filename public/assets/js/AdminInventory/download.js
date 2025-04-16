document.getElementById('downloadPdfBtn').addEventListener('click', function() {
    // Create new jsPDF instance
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();
    
    // Get product details
    const productName = document.getElementById('modalProductName').textContent.replace(" Details", "");
    const stockRemaining = document.getElementById('modalStockRemaining').textContent;
    
    // Get date filter values
    const startDate = document.getElementById('startDate').value;
    const endDate = document.getElementById('endDate').value;
    
    // Format dates for display if they exist
    const formattedStartDate = startDate ? new Date(startDate).toLocaleDateString() : 'All';
    const formattedEndDate = endDate ? new Date(endDate).toLocaleDateString() : 'All';
    
    // Add logo at the top
    const img = new Image();
    img.src = 'images/logo.png';
    
    img.onload = function() {
        // Set up the document with letterhead styling
        
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
        
        // Get table data from current page
        const tableBody = [];
        const tableRows = document.getElementById('withdrawalRequestsBody').querySelectorAll('tr');
        
        tableRows.forEach(row => {
            const cells = row.querySelectorAll('td');
            tableBody.push([
                cells[0].textContent.trim(), // Date
                cells[1].textContent.trim(), // Plate Number
                cells[2].textContent.trim(), // Driver
                cells[3].textContent.trim(), // Stock Before
                cells[4].textContent.trim(), // Withdrawal
                cells[5].textContent.trim()  // Balance/Stock After
            ]);
        });
        
        // Create table with headers - moved up due to saved space
        doc.autoTable({
            head: [['Date', 'Plate Number', 'Driver', 'Stock Before', 'Withdrawal', 'Balance']],
            body: tableBody,
            startY: textStartY + 55, // Reduced from 70 to 55 due to saved space
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
        let filename = productName.replace(/\s+/g, '_');
        if (startDate && endDate) {
            const startDateForFile = startDate.replace(/-/g, '');
            const endDateForFile = endDate.replace(/-/g, '');
            filename += `_${startDateForFile}_to_${endDateForFile}`;
        }
        doc.save(`${filename}_Official_Report.pdf`);
    };
    
    img.onerror = function() {
        console.error("Error loading logo image");
        // Continue without the logo
        createPDFWithoutLogo();
    };
    
    // Fallback function if logo fails to load
    function createPDFWithoutLogo() {
        // Similar to above but without the logo
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
        
        // Get table data and create table
        const tableBody = [];
        const tableRows = document.getElementById('withdrawalRequestsBody').querySelectorAll('tr');
        
        tableRows.forEach(row => {
            const cells = row.querySelectorAll('td');
            tableBody.push([
                cells[0].textContent.trim(),
                cells[1].textContent.trim(),
                cells[2].textContent.trim(),
                cells[3].textContent.trim(),
                cells[4].textContent.trim(),
                cells[5].textContent.trim()
            ]);
        });
        
        // Create table with headers
        doc.autoTable({
            head: [['Date', 'Plate Number', 'Driver', 'Stock Before', 'Withdrawal', 'Balance']],
            body: tableBody,
            startY: 75, // Reduced due to saved space
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
        
        // Add footer with page number
        const pageCount = doc.internal.getNumberOfPages();
        for(let i = 1; i <= pageCount; i++) {
            doc.setPage(i);
            doc.setDrawColor(0, 0, 0);
            doc.setLineWidth(0.5);
            doc.line(15, 280, 195, 280);
            doc.setFontSize(8);
            doc.text('This is an official document. All inventory transactions are subject to audit.', 105, 287, { align: 'center' });
            doc.text(`Page ${i} of ${pageCount}`, 195, 287, { align: 'right' });
        }
        
        // Save the PDF
        let filename = productName.replace(/\s+/g, '_');
        if (startDate && endDate) {
            const startDateForFile = startDate.replace(/-/g, '');
            const endDateForFile = endDate.replace(/-/g, '');
            filename += `_${startDateForFile}_to_${endDateForFile}`;
        }
        doc.save(`${filename}_Official_Report.pdf`);
    }
});