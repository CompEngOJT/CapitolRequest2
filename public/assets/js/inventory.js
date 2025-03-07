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
// Sample Data
const tableData = [
    { product: "Product A", totalStocks: 100, lastWithdrawal: "2023-10-01", stockRemaining: 80 },
    { product: "Product B", totalStocks: 150, lastWithdrawal: "2023-10-02", stockRemaining: 120 },
    { product: "Product C", totalStocks: 200, lastWithdrawal: "2023-10-03", stockRemaining: 180 },
    { product: "Product D", totalStocks: 250, lastWithdrawal: "2023-10-04", stockRemaining: 200 },
    { product: "Product E", totalStocks: 300, lastWithdrawal: "2023-10-05", stockRemaining: 250 },
    { product: "Product F", totalStocks: 350, lastWithdrawal: "2023-10-06", stockRemaining: 300 },
    { product: "Product G", totalStocks: 400, lastWithdrawal: "2023-10-07", stockRemaining: 350 },
    { product: "Product H", totalStocks: 450, lastWithdrawal: "2023-10-08", stockRemaining: 400 },
    { product: "Product I", totalStocks: 500, lastWithdrawal: "2023-10-09", stockRemaining: 450 },
    { product: "Product J", totalStocks: 550, lastWithdrawal: "2023-10-10", stockRemaining: 500 },
    { product: "Product K", totalStocks: 600, lastWithdrawal: "2023-10-11", stockRemaining: 550 },
    { product: "Product L", totalStocks: 650, lastWithdrawal: "2023-10-12", stockRemaining: 600 },
];

// Pagination Variables
const rowsPerPage = 7;
let currentPage = 1;

// DOM Elements
const tableBody = document.getElementById("table-body");
const prevPageButton = document.getElementById("prev-page");
const nextPageButton = document.getElementById("next-page");
const pageIndicator = document.getElementById("page-indicator");

// Function to Render Table Rows
function renderTableRows() {
    const startIndex = (currentPage - 1) * rowsPerPage;
    const endIndex = startIndex + rowsPerPage;
    const pageData = tableData.slice(startIndex, endIndex);

    tableBody.innerHTML = ""; // Clear existing rows

    // Add rows for the current page
    pageData.forEach((item) => {
        const row = document.createElement("tr");
        row.innerHTML = `
            <td>${item.product}</td>
            <td>${item.totalStocks}</td>
            <td>${item.lastWithdrawal}</td>
            <td>${item.stockRemaining}</td>
        `;
        tableBody.appendChild(row);
    });

    // Add empty rows if there are fewer than 8 rows
    const emptyRows = rowsPerPage - pageData.length;
    for (let i = 0; i < emptyRows; i++) {
        const row = document.createElement("tr");
        row.innerHTML = `
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        `;
        tableBody.appendChild(row);
    }

    // Update pagination controls
    pageIndicator.textContent = `Page ${currentPage}`;
    prevPageButton.disabled = currentPage === 1;
    nextPageButton.disabled = endIndex >= tableData.length;
}

// Event Listeners for Pagination
prevPageButton.addEventListener("click", () => {
    if (currentPage > 1) {
        currentPage--;
        renderTableRows();
    }
});

nextPageButton.addEventListener("click", () => {
    const totalPages = Math.ceil(tableData.length / rowsPerPage);
    if (currentPage < totalPages) {
        currentPage++;
        renderTableRows();
    }
});

// Initial Render
renderTableRows();