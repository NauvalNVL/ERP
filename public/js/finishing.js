// Global variables
let selectedRow = null;
let finishings = [];
let originalFinishings = [];

// DOM Ready function
document.addEventListener('DOMContentLoaded', function() {
    // Check if table needs data
    const table = document.getElementById('finishingDataTable');
    if (table) {
        const tbody = table.querySelector('tbody');
        if (tbody && (!tbody.children.length || tbody.children[0].textContent.includes('Tidak ada data'))) {
            populateFinishingTable();
        } else {
            // If data exists, capture it for sorting later
            captureExistingData();
        }
    }

    // Add event listener to the search input
    const searchInput = document.getElementById('searchFinishingInput');
    if (searchInput) {
        searchInput.addEventListener('keyup', function() {
            filterFinishingTable();
        });
    }
});

// Function to populate finishing table with seed data if empty
function populateFinishingTable() {
    // Sample seed data
    const seedData = [
        { code: 'LAM001', description: 'Glossy Lamination', category: 'LAMINATION', cost: 2.50 },
        { code: 'LAM002', description: 'Matte Lamination', category: 'LAMINATION', cost: 2.75 },
        { code: 'VAR001', description: 'UV Varnish', category: 'VARNISH', cost: 1.80 },
        { code: 'EMB001', description: 'Emboss Pattern 1', category: 'EMBOSS', cost: 3.20 },
        { code: 'FOIL001', description: 'Gold Foil', category: 'FOIL', cost: 4.50 },
        { code: 'DIE001', description: 'Standard Die Cut', category: 'DIE_CUT', cost: 2.00 }
    ];
    
    const table = document.getElementById('finishingDataTable');
    const tbody = table.querySelector('tbody');
    
    // Clear any existing no-data message
    tbody.innerHTML = '';
    
    // Add seed data rows
    seedData.forEach(finish => {
        const row = document.createElement('tr');
        row.className = 'hover:bg-blue-50 cursor-pointer';
        row.setAttribute('data-code', finish.code);
        row.setAttribute('data-description', finish.description);
        row.setAttribute('data-category', finish.category);
        row.setAttribute('data-cost', finish.cost);
        row.onclick = function(e) { selectRow(this); e.stopPropagation(); };
        
        row.innerHTML = `
            <td class="px-4 py-3 whitespace-nowrap font-medium text-gray-900">${finish.code}</td>
            <td class="px-4 py-3 whitespace-nowrap text-gray-700">${finish.description}</td>
            <td class="px-4 py-3 whitespace-nowrap">
                <span class="px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">${finish.category}</span>
            </td>
            <td class="px-4 py-3 whitespace-nowrap text-gray-700">${finish.cost}</td>
        `;
        
        tbody.appendChild(row);
    });
    
    // Update the notification area
    const notificationArea = document.querySelector('.bg-yellow-100');
    if (notificationArea) {
        notificationArea.classList.remove('bg-yellow-100');
        notificationArea.classList.add('bg-green-100');
        notificationArea.innerHTML = `
            <p class="text-sm font-medium text-green-800">Data tersedia: ${seedData.length} finishing ditemukan (dari JavaScript).</p>
        `;
    }
    
    // Update the global array
    captureExistingData();
}

// Function to capture existing data from the table
function captureExistingData() {
    const table = document.getElementById('finishingDataTable');
    const rows = table.querySelectorAll('tbody tr');
    
    finishings = [];
    originalFinishings = [];
    
    rows.forEach(row => {
        if (!row.querySelector('td').textContent.includes('Tidak ada data')) {
            const finish = {
                code: row.getAttribute('data-code'),
                description: row.getAttribute('data-description'),
                category: row.getAttribute('data-category'),
                cost: row.getAttribute('data-cost')
            };
            finishings.push(finish);
            originalFinishings.push({...finish});
        }
    });
}

// Function to sort the table directly
function sortTable(columnIndex) {
    const table = document.getElementById('finishingDataTable');
    const tbody = table.querySelector('tbody');
    const rows = Array.from(tbody.querySelectorAll('tr'));
    
    // Skip if there's only one row with "No data" message
    if (rows.length === 1 && rows[0].querySelector('td').textContent.includes('Tidak ada data')) {
        return;
    }
    
    // Determine the sort key based on column index
    const getSortKey = (row) => {
        const cell = row.cells[columnIndex];
        return cell ? cell.textContent.trim().toLowerCase() : '';
    };
    
    // Sort direction - toggle if clicking the same column again
    const currentSortCol = table.getAttribute('data-sort-col');
    const currentSortDir = table.getAttribute('data-sort-dir');
    
    let sortDirection = 'asc';
    if (currentSortCol === columnIndex.toString() && currentSortDir === 'asc') {
        sortDirection = 'desc';
    }
    
    // Store the current sort state
    table.setAttribute('data-sort-col', columnIndex);
    table.setAttribute('data-sort-dir', sortDirection);
    
    // Sort the rows
    rows.sort((a, b) => {
        const keyA = getSortKey(a);
        const keyB = getSortKey(b);
        
        if (sortDirection === 'asc') {
            return keyA.localeCompare(keyB);
        } else {
            return keyB.localeCompare(keyA);
        }
    });
    
    // Remove all rows
    rows.forEach(row => row.remove());
    
    // Add sorted rows back
    rows.forEach(row => tbody.appendChild(row));
}

// Function to filter the finishing table based on search input
function filterFinishingTable() {
    const searchValue = document.getElementById('searchFinishingInput').value.toLowerCase();
    const table = document.getElementById('finishingDataTable');
    const rows = table.querySelectorAll('tbody tr');
    
    rows.forEach(row => {
        if (row.querySelector('td').textContent.includes('Tidak ada data')) {
            return;
        }
        
        const code = row.getAttribute('data-code').toLowerCase();
        const description = row.getAttribute('data-description').toLowerCase();
        const category = row.getAttribute('data-category').toLowerCase();
        
        if (code.includes(searchValue) || description.includes(searchValue) || category.includes(searchValue)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

// Function to select a row
function selectRow(row) {
    // Remove selection from previously selected row
    if (selectedRow) {
        selectedRow.classList.remove('bg-blue-100');
    }
    
    // Mark the new row as selected
    row.classList.add('bg-blue-100');
    selectedRow = row;
}

// Function to select the finishing
function selectFinishing() {
    if (selectedRow) {
        const code = selectedRow.getAttribute('data-code');
        document.getElementById('finishingCode').value = code;
        closeFinishingModal();
    } else {
        alert('Please select a finishing first');
    }
}

// Function to open the finishing modal
function openFinishingModal() {
    const modal = document.getElementById('finishingModal');
    if (modal) {
        modal.style.display = 'flex';
        // Focus on the search input
        const searchInput = document.getElementById('searchFinishingInput');
        if (searchInput) {
            searchInput.focus();
        }
    }
}

// Function to close the finishing modal
function closeFinishingModal() {
    const modal = document.getElementById('finishingModal');
    if (modal) {
        modal.style.display = 'none';
    }
}

// Function to open the edit finishing modal
function openEditFinishingModal(row) {
    // Populate the form with the row data
    document.getElementById('edit_code').value = row.getAttribute('data-code');
    document.getElementById('edit_description').value = row.getAttribute('data-description');
    document.getElementById('edit_category').value = row.getAttribute('data-category');
    document.getElementById('edit_cost').value = row.getAttribute('data-cost');
    
    // Show the modal
    const modal = document.getElementById('editFinishingModal');
    if (modal) {
        modal.classList.remove('hidden');
    }
    
    // Close the finishing table window
    closeFinishingModal();
}

// Function to close the edit finishing modal
function closeEditFinishingModal() {
    const modal = document.getElementById('editFinishingModal');
    if (modal) {
        modal.style.display = 'none';
    }
}

// Function to save finishing changes
function saveFinishingChanges() {
    showLoading();
    
    const code = document.getElementById('edit_code').value;
    const description = document.getElementById('edit_description').value;
    const category = document.getElementById('edit_category').value;
    const cost = document.getElementById('edit_cost').value;
    
    // Simulate an AJAX request
    setTimeout(() => {
        // Update the table row if it exists
        const table = document.getElementById('finishingDataTable');
        const rows = table.querySelectorAll('tbody tr');
        let rowFound = false;
        
        rows.forEach(row => {
            if (row.getAttribute('data-code') === code) {
                row.setAttribute('data-description', description);
                row.setAttribute('data-category', category);
                row.setAttribute('data-cost', cost);
                
                // Update the displayed text
                row.cells[1].textContent = description;
                row.cells[2].innerHTML = `<span class="px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">${category}</span>`;
                row.cells[3].textContent = cost;
                
                rowFound = true;
            }
        });
        
        // If row not found, add a new one
        if (!rowFound) {
            const tbody = table.querySelector('tbody');
            
            // Remove the "No data" row if it exists
            if (tbody.children.length === 1 && tbody.children[0].textContent.includes('Tidak ada data')) {
                tbody.innerHTML = '';
            }
            
            // Create a new row
            const newRow = document.createElement('tr');
            newRow.className = 'hover:bg-blue-50 cursor-pointer';
            newRow.setAttribute('data-code', code);
            newRow.setAttribute('data-description', description);
            newRow.setAttribute('data-category', category);
            newRow.setAttribute('data-cost', cost);
            newRow.onclick = function(e) { selectRow(this); e.stopPropagation(); };
            
            newRow.innerHTML = `
                <td class="px-4 py-3 whitespace-nowrap font-medium text-gray-900">${code}</td>
                <td class="px-4 py-3 whitespace-nowrap text-gray-700">${description}</td>
                <td class="px-4 py-3 whitespace-nowrap">
                    <span class="px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">${category}</span>
                </td>
                <td class="px-4 py-3 whitespace-nowrap text-gray-700">${cost}</td>
            `;
            
            tbody.appendChild(newRow);
        }
        
        // Update the captured data
        captureExistingData();
        
        // Close the edit modal
        closeEditFinishingModal();
        
        // Hide loading indicator
        hideLoading();
    }, 500);
    
    return false; // Prevent form submission
}

// Function to select a specific finishing item
function selectFinishingItem(code) {
    document.getElementById('finishingCode').value = code;
    closeFinishingModal();
    
    // Optionally highlight the selected row
    const rows = document.querySelectorAll('#finishingDataTable tbody tr');
    rows.forEach(row => {
        if (row.getAttribute('data-code') === code) {
            selectRow(row);
        }
    });
}

// Helper function to show loading indicator
function showLoading() {
    const loading = document.getElementById('loadingOverlay');
    if (loading) {
        loading.style.display = 'flex';
    }
}

// Helper function to hide loading indicator
function hideLoading() {
    const loading = document.getElementById('loadingOverlay');
    if (loading) {
        loading.style.display = 'none';
    }
}

// Function to load seed data (used by the button in the debug section)
function loadSeedData() {
    if (confirm('This will load seed data for finishings. Continue?')) {
        populateFinishingTable();
    }
} 