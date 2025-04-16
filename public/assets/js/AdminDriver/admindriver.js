document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('driverSearch');
    searchInput.addEventListener('keyup', function() {
        const filter = searchInput.value.toUpperCase();
        const table = document.querySelector('.drivers-table');
        const rows = table.getElementsByTagName('tr');
        
        for (let i = 1; i < rows.length; i++) { // Start from 1 to skip header
            const nameCell = rows[i].getElementsByTagName('td')[0];
            if (nameCell) {
                const txtValue = nameCell.textContent || nameCell.innerText;
                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                    rows[i].style.display = "";
                } else {
                    rows[i].style.display = "none";
                }
            }
        }
    });
});
