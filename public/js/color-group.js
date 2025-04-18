// Global variables
let selectedRow = null;
let colorGroups = [];
let originalColorGroups = [];

// DOM Ready function
document.addEventListener('DOMContentLoaded', function() {
    // Check if table needs data
    const table = document.getElementById('colorGroupDataTable');
    if (table) {
        const tbody = table.querySelector('tbody');
        if (tbody && (!tbody.children.length || tbody.children[0].textContent.includes('Tidak ada data'))) {
            populateColorGroupTable();
        } else {
            // If data exists, capture it for sorting later
            captureExistingData();
        }
    }

    // Add event listener to the search input
    const searchInput = document.getElementById('searchColorGroupInput');
    if (searchInput) {
        searchInput.addEventListener('keyup', function() {
            filterColorGroupTable();
        });
    }
});

// Function to populate color group table with seed data if empty
function populateColorGroupTable() {
    // Sample seed data
    const seedData = [
        { cg: 'F1', cg_name: 'Flexo Standard', cg_type: 'X-Flex' },
        { cg: 'C1', cg_name: 'Coating Gloss', cg_type: 'C-Coating' },
        { cg: 'S1', cg_name: 'Offset CMYK', cg_type: 'S-Offset' },
        { cg: 'D1', cg_name: 'Digital Print', cg_type: 'D-Digital' },
        { cg: 'P1', cg_name: 'Pantone Spot', cg_type: 'P-Pantone' }
    ];
    
    const table = document.getElementById('colorGroupDataTable');
    const tbody = table.querySelector('tbody');
    
    // Clear any existing no-data message
    tbody.innerHTML = '';
    
    // Add seed data rows
    seedData.forEach(group => {
        const row = document.createElement('tr');
        row.className = 'hover:bg-blue-50 cursor-pointer';
        row.setAttribute('data-cg-id', group.cg);
        row.setAttribute('data-cg-name', group.cg_name);
        row.setAttribute('data-cg-type', group.cg_type);
        row.onclick = function(e) { selectRow(this); e.stopPropagation(); };
        row.ondblclick = function() { openEditColorGroupModal(this); };
        
        row.innerHTML = `
            <td class="px-4 py-3 whitespace-nowrap font-medium text-gray-900">${group.cg}</td>
            <td class="px-4 py-3 whitespace-nowrap text-gray-700">${group.cg_name}</td>
            <td class="px-4 py-3 whitespace-nowrap text-gray-700">${group.cg_type}</td>
        `;
        
        tbody.appendChild(row);
    });
    
    // Update the global array
    captureExistingData();
}

// Function to capture existing data from the table
function captureExistingData() {
    const table = document.getElementById('colorGroupDataTable');
    const rows = table.querySelectorAll('tbody tr');
    
    colorGroups = [];
    originalColorGroups = [];
    
    rows.forEach(row => {
        if (!row.querySelector('td').textContent.includes('Tidak ada data')) {
            const group = {
                cg: row.getAttribute('data-cg-id'),
                cg_name: row.getAttribute('data-cg-name'),
                cg_type: row.getAttribute('data-cg-type')
            };
            colorGroups.push(group);
            originalColorGroups.push({...group});
        }
    });
}

// Function to sort the table directly
function sortTableDirectly(columnIndex) {
    const table = document.getElementById('colorGroupDataTable');
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

// Function to filter the color group table based on search input
function filterColorGroupTable() {
    const searchValue = document.getElementById('searchColorGroupInput').value.toLowerCase();
    const table = document.getElementById('colorGroupDataTable');
    const rows = table.querySelectorAll('tbody tr');
    
    rows.forEach(row => {
        if (row.querySelector('td').textContent.includes('Tidak ada data')) {
            return;
        }
        
        const cgId = row.getAttribute('data-cg-id').toLowerCase();
        const cgName = row.getAttribute('data-cg-name').toLowerCase();
        const cgType = row.getAttribute('data-cg-type').toLowerCase();
        
        if (cgId.includes(searchValue) || cgName.includes(searchValue) || cgType.includes(searchValue)) {
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

// Function to edit the selected row
function editSelectedRow() {
    if (selectedRow) {
        openEditColorGroupModal(selectedRow);
    } else {
        alert('Please select a color group first');
    }
}

// Function to open the color group modal
function openColorGroupModal() {
    const modal = document.getElementById('colorGroupTableWindow');
    if (modal) {
        modal.classList.remove('hidden');
        // Focus on the search input
        const searchInput = document.getElementById('searchColorGroupInput');
        if (searchInput) {
            searchInput.focus();
        }
    }
}

// Function to close the color group modal
function closeColorGroupModal() {
    const modal = document.getElementById('colorGroupTableWindow');
    if (modal) {
        modal.classList.add('hidden');
    }
}

// General function to close modals with X button
function closeModalX() {
    // This can close any modal - currently used for the color group table window
    closeColorGroupModal();
}

// Function to open the edit color group modal
function openEditColorGroupModal(row) {
    // Populate the form with the row data
    document.getElementById('edit_cg_id').value = row.getAttribute('data-cg-id');
    document.getElementById('edit_cg_name').value = row.getAttribute('data-cg-name');
    document.getElementById('edit_cg_type').value = row.getAttribute('data-cg-type');
    
    // Make the CG# field read-only for existing rows (editing)
    document.getElementById('edit_cg_id').readOnly = true;
    document.getElementById('edit_cg_id').classList.add('bg-gray-100');
    
    // Show the modal
    const modal = document.getElementById('editColorGroupModal');
    if (modal) {
        modal.classList.remove('hidden');
    }
    
    // Close the color group table window
    closeColorGroupModal();
}

// Function to close the edit color group modal
function closeEditColorGroupModal() {
    const modal = document.getElementById('editColorGroupModal');
    if (modal) {
        modal.classList.add('hidden');
    }
}

// Function to save color group changes
function saveColorGroupChanges() {
    showLoading();
    
    const cgId = document.getElementById('edit_cg_id').value;
    const cgName = document.getElementById('edit_cg_name').value;
    const cgType = document.getElementById('edit_cg_type').value;
    
    // Simulate an AJAX request
    setTimeout(() => {
        // Update the table row if it exists
        const table = document.getElementById('colorGroupDataTable');
        const rows = table.querySelectorAll('tbody tr');
        let rowFound = false;
        
        rows.forEach(row => {
            if (row.getAttribute('data-cg-id') === cgId) {
                row.setAttribute('data-cg-name', cgName);
                row.setAttribute('data-cg-type', cgType);
                
                // Update the displayed text
                row.cells[1].textContent = cgName;
                row.cells[2].textContent = cgType;
                
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
            newRow.setAttribute('data-cg-id', cgId);
            newRow.setAttribute('data-cg-name', cgName);
            newRow.setAttribute('data-cg-type', cgType);
            newRow.onclick = function(e) { selectRow(this); e.stopPropagation(); };
            newRow.ondblclick = function() { openEditColorGroupModal(this); };
            
            newRow.innerHTML = `
                <td class="px-4 py-3 whitespace-nowrap font-medium text-gray-900">${cgId}</td>
                <td class="px-4 py-3 whitespace-nowrap text-gray-700">${cgName}</td>
                <td class="px-4 py-3 whitespace-nowrap text-gray-700">${cgType}</td>
            `;
            
            tbody.appendChild(newRow);
        }
        
        // Update the captured data
        captureExistingData();
        
        // Close the edit modal
        closeEditColorGroupModal();
        
        // Hide loading indicator
        hideLoading();
    }, 500);
    
    return false; // Prevent form submission
}

// Function to delete a color group
function deleteColorGroup(cgId) {
    if (confirm(`Are you sure you want to delete color group "${cgId}"?`)) {
        showLoading();
        
        // Simulate an AJAX request
        setTimeout(() => {
            // Remove the row from the table
            const table = document.getElementById('colorGroupDataTable');
            const rows = table.querySelectorAll('tbody tr');
            
            rows.forEach(row => {
                if (row.getAttribute('data-cg-id') === cgId) {
                    row.remove();
                }
            });
            
            // Check if table is now empty
            const tbody = table.querySelector('tbody');
            if (tbody.children.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="3" class="px-4 py-4 text-center text-gray-500">Tidak ada data color group yang tersedia.</td>
                    </tr>
                `;
            }
            
            // Update the captured data
            captureExistingData();
            
            // Hide loading indicator
            hideLoading();
        }, 500);
    }
}

// Helper function to show loading indicator
function showLoading() {
    const loading = document.getElementById('loadingOverlay');
    if (loading) {
        loading.classList.remove('hidden');
    }
}

// Helper function to hide loading indicator
function hideLoading() {
    const loading = document.getElementById('loadingOverlay');
    if (loading) {
        loading.classList.add('hidden');
    }
}

// Function to load seed data (used by the button in the debug section)
function loadSeedData() {
    if (confirm('This will load seed data for color groups. Continue?')) {
        populateColorGroupTable();
    }
} 