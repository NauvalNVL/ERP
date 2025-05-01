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

// Function to populate color group table with data from database
function populateColorGroupTable() {
    console.log('Populating color group table');
    
    // Remove "no data" message if present
    const tbody = document.querySelector('#colorGroupDataTable tbody');
    tbody.innerHTML = '';
    
    // Show loading message
    tbody.innerHTML = '<tr><td colspan="3" class="px-4 py-4 text-center text-gray-500">Loading data...</td></tr>';
    
    // Fetch data from the database
    fetch('/color-group', {
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
                tbody.innerHTML = '<tr><td colspan="3" class="px-4 py-4 text-center text-gray-500">Tidak ada data color group yang tersedia.</td></tr>';
                return;
            }

            // Fill table with data from database
            data.forEach(group => {
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
    
            console.log('Table populated with database data:', data);
            
            // Update the data status message
            const dbStatusElement = document.querySelector('.bg-yellow-100, .bg-green-100');
            if (dbStatusElement) {
                dbStatusElement.className = 'mt-4 bg-green-100 p-3 rounded';
                dbStatusElement.innerHTML = `
                    <p class="text-sm font-medium text-green-800">Data tersedia: ${data.length} color group ditemukan.</p>
                `;
            }
            
            // Capture the data for sorting
    captureExistingData();
        })
        .catch(error => {
            console.error('Error fetching database data:', error);
            tbody.innerHTML = `<tr><td colspan="3" class="px-4 py-4 text-center text-red-500">
                Error loading data from database. ${error.message}
                <br>
                <button onclick="populateColorGroupTable()" class="mt-2 px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                    <i class="fas fa-sync-alt mr-2"></i>Coba Lagi
                </button>
            </td></tr>`;
        });
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
    console.log('Opening edit color group modal for row:', row);
    
    const cgId = row.getAttribute('data-cg-id');
    const cgName = row.getAttribute('data-cg-name');
    const cgType = row.getAttribute('data-cg-type');
    
    console.log('Color group data:', { cgId, cgName, cgType });
    
    // Populate the form with the row data
    document.getElementById('edit_cg_id').value = cgId;
    document.getElementById('edit_cg_name').value = cgName;
    document.getElementById('edit_cg_type').value = cgType;
    
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
    
    console.log('Edit modal opened');
}

// Function to close the edit color group modal
function closeEditColorGroupModal() {
    console.log('Closing edit color group modal');
    const modal = document.getElementById('editColorGroupModal');
    if (modal) {
        modal.classList.add('hidden');
    }
    
    // Reset form fields
    document.getElementById('edit_cg_id').value = '';
    document.getElementById('edit_cg_name').value = '';
    document.getElementById('edit_cg_type').value = '';
    document.getElementById('edit_cg_id').readOnly = false;
    document.getElementById('edit_cg_id').classList.remove('bg-gray-100');
}

// Function to save color group changes
function saveColorGroupChanges() {
    showLoading();
    
    const cgId = document.getElementById('edit_cg_id').value;
    const cgName = document.getElementById('edit_cg_name').value;
    const cgType = document.getElementById('edit_cg_type').value;
    
    // Validate input
    if (!cgName || !cgType) {
        alert('Please fill in all required fields');
        hideLoading();
        return;
    }
    
    // Display loading indicator on button and overlay
    const saveButton = document.querySelector('#editColorGroupForm button[type="submit"]');
    const originalText = saveButton.innerHTML;
    saveButton.innerHTML = '<i class="fas fa-circle-notch fa-spin mr-2"></i>Menyimpan...';
    saveButton.disabled = true;
    
    // Send update to server
    fetch('/color-group/' + cgId, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            cg: cgId,
            cg_name: cgName,
            cg_type: cgType
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
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
        
            // Show success message
            alert('Color group updated successfully');
            
            // Refresh the table data
            populateColorGroupTable();
        } else {
            alert('Error updating color group: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error updating color group:', error);
        alert('Error updating color group. Please try again.');
    })
    .finally(() => {
        // Reset button state and hide loading overlay
        saveButton.innerHTML = originalText;
        saveButton.disabled = false;
        hideLoading();
    });
}

// Function to delete a color group
function deleteColorGroup(cgId) {
    if (confirm(`Are you sure you want to delete color group "${cgId}"?`)) {
        showLoading();
        
        fetch('/color-group/' + cgId, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
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
            
                // Show success message
                alert('Color group deleted successfully');
                
                // Refresh the table data
                populateColorGroupTable();
            } else {
                alert('Error deleting color group: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error deleting color group:', error);
            alert('Error deleting color group. Please try again.');
        })
        .finally(() => {
            hideLoading();
        });
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