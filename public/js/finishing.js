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

// Function to populate finishing table with data from database
function populateFinishingTable() {
    console.log('Populating finishing table');
    
    // Remove "no data" message if present
    const tbody = document.querySelector('#finishingDataTable tbody');
    tbody.innerHTML = '';
    
    // Show loading message
    tbody.innerHTML = '<tr><td colspan="4" class="px-4 py-4 text-center text-gray-500">Loading data...</td></tr>';
    
    // Fetch data from the database
    fetch('/finishing', {
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        // Clear loading message
        tbody.innerHTML = '';
        
        if (!Array.isArray(data) || data.length === 0) {
            tbody.innerHTML = '<tr><td colspan="4" class="px-4 py-4 text-center text-gray-500">Tidak ada data finishing yang tersedia.</td></tr>';
            return;
        }

        // Fill table with data from database
        data.forEach(finish => {
            const row = document.createElement('tr');
            row.className = 'hover:bg-blue-50 cursor-pointer';
            row.setAttribute('data-code', finish.code);
            row.setAttribute('data-description', finish.description);
            row.onclick = function(e) { selectRow(this); e.stopPropagation(); };
            
            row.innerHTML = `
                <td class="px-4 py-3 whitespace-nowrap font-medium text-gray-900">${finish.code}</td>
                <td class="px-4 py-3 whitespace-nowrap text-gray-700">${finish.description}</td>
                <td class="px-4 py-3 whitespace-nowrap">
                    <span class="px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">N/A</span>
                </td>
                <td class="px-4 py-3 whitespace-nowrap text-gray-700">N/A</td>
            `;
            
            tbody.appendChild(row);
        });
        
        console.log('Table populated with database data:', data);
        
        // Update the notification area
        const notificationArea = document.querySelector('.bg-yellow-100');
        if (notificationArea) {
            notificationArea.classList.remove('bg-yellow-100');
            notificationArea.classList.add('bg-green-100');
            notificationArea.innerHTML = `
                <p class="text-sm font-medium text-green-800">Data tersedia: ${data.length} finishing ditemukan.</p>
            `;
        }
        
        // Update the global array
        captureExistingData();
    })
    .catch(error => {
        console.error('Error fetching database data:', error);
        tbody.innerHTML = `<tr><td colspan="4" class="px-4 py-4 text-center text-red-500">
            Error loading data from database. ${error.message}
            <br>
            <button onclick="populateFinishingTable()" class="mt-2 px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                <i class="fas fa-sync-alt mr-2"></i>Coba Lagi
            </button>
        </td></tr>`;
    });
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
                description: row.getAttribute('data-description')
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
        
        if (code.includes(searchValue) || description.includes(searchValue)) {
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
    
    // Send update to server
    fetch('/finishing/' + code, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            code: code,
            description: description
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update the table row if it exists
            const table = document.getElementById('finishingDataTable');
            const rows = table.querySelectorAll('tbody tr');
            let rowFound = false;
            
            rows.forEach(row => {
                if (row.getAttribute('data-code') === code) {
                    row.setAttribute('data-description', description);
                    
                    // Update the displayed text
                    row.cells[1].textContent = description;
                    
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
                newRow.onclick = function(e) { selectRow(this); e.stopPropagation(); };
                
                newRow.innerHTML = `
                    <td class="px-4 py-3 whitespace-nowrap font-medium text-gray-900">${code}</td>
                    <td class="px-4 py-3 whitespace-nowrap text-gray-700">${description}</td>
                    <td class="px-4 py-3 whitespace-nowrap">
                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">N/A</span>
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap text-gray-700">N/A</td>
                `;
                
                tbody.appendChild(newRow);
            }
            
            // Update the captured data
            captureExistingData();
            
            // Close the edit modal
            closeEditFinishingModal();
            
            alert('Data finishing berhasil diperbarui');
        } else {
            alert('Error updating finishing: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error updating finishing:', error);
        alert('Error updating finishing. Please try again.');
    })
    .finally(() => {
        hideLoading();
    });
    
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
        fetch('/run-finishing-seeder', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                populateFinishingTable();
                alert('Seed data loaded successfully');
            } else {
                alert('Error loading seed data: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error loading seed data:', error);
            alert('Error loading seed data. Please try again.');
        });
    }
} 