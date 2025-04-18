// ProductGroup.js - Functions for product group management

// Define seed data for product groups
const seedProductGroups = [
    {
        product_group_id: 'B',
        product_group_name: 'BOX',
        is_active: true
    },
    {
        product_group_id: 'OF',
        product_group_name: 'OFFSET',
        is_active: true
    },
    {
        product_group_id: 'OT',
        product_group_name: 'OTHER',
        is_active: true
    },
    {
        product_group_id: 'R',
        product_group_name: 'PAPER ROLL',
        is_active: true
    },
    {
        product_group_id: 'S',
        product_group_name: 'SHEET BOARD',
        is_active: true
    }
];

function populateProductGroupTable() {
    console.log('Populating product group table');
    
    // Check if table already has data
    const tbody = document.querySelector('#productGroupDataTable tbody');
    const rows = tbody.querySelectorAll('tr');
    const isEmpty = rows.length === 0 || (rows.length === 1 && rows[0].querySelector('td[colspan]'));
    
    if (isEmpty) {
        console.log('Table is empty, populating with seed data');
        
        // Remove "no data" message if present
        tbody.innerHTML = '';
        
        // Fill table with data from seedProductGroups
        seedProductGroups.forEach(group => {
            const row = document.createElement('tr');
            row.className = 'hover:bg-blue-50 cursor-pointer';
            row.setAttribute('data-pg-id', group.product_group_id);
            row.setAttribute('data-pg-name', group.product_group_name);
            row.setAttribute('data-pg-active', group.is_active);
            row.onclick = function(e) { selectRow(this); e.stopPropagation(); };
            row.ondblclick = function() { openEditProductGroupModal(this); };
            
            // Create cells
            const idCell = document.createElement('td');
            idCell.className = 'px-4 py-3 whitespace-nowrap font-medium text-gray-900';
            idCell.textContent = group.product_group_id;
            
            const nameCell = document.createElement('td');
            nameCell.className = 'px-4 py-3 whitespace-nowrap text-gray-700';
            nameCell.textContent = group.product_group_name;
            
            const statusCell = document.createElement('td');
            statusCell.className = 'px-4 py-3 whitespace-nowrap text-gray-700';
            statusCell.textContent = group.is_active ? 'Active' : 'Inactive';
            
            // Add cells to row
            row.appendChild(idCell);
            row.appendChild(nameCell);
            row.appendChild(statusCell);
            
            // Add row to table
            tbody.appendChild(row);
        });
        
        console.log('Table populated with seed data');
        
        // Setup event handlers for the table rows
        setupTableRowEvents();
        
        // Sort table based on PG#
        sortTableDirectly(0);
    } else {
        console.log('Table already has data, no need to populate');
    }
}

// Function to sort table
function sortTableDirectly(columnIndex) {
    console.log("Sorting by column: " + columnIndex);
    
    var table = document.getElementById('productGroupDataTable');
    if (!table) {
        console.error("Table not found");
        return;
    }
    
    var tbody = table.querySelector('tbody');
    if (!tbody) {
        console.error("Table body not found");
        return;
    }
    
    var rows = Array.from(tbody.querySelectorAll('tr'));
    
    // Don't sort if there's no data or only a "no data" message
    if (rows.length === 0 || (rows.length === 1 && rows[0].querySelector('td[colspan]'))) {
        console.log("No data to sort");
        return;
    }
    
    // Sort rows based on column content
    rows.sort(function(a, b) {
        // Check if column is available
        if (!a.cells[columnIndex] || !b.cells[columnIndex]) {
            return 0;
        }
        
        var textA = a.cells[columnIndex].textContent.trim();
        var textB = b.cells[columnIndex].textContent.trim();
        
        // Numeric-based sort for ID column
        if (columnIndex === 0) {
            return textA.localeCompare(textB, undefined, { numeric: true });
        }
        
        // Regular text sort for other columns
        return textA.localeCompare(textB);
    });
    
    // Remove all rows from table
    while (tbody.firstChild) {
        tbody.removeChild(tbody.firstChild);
    }
    
    // Add sorted rows to table
    rows.forEach(function(row) {
        tbody.appendChild(row);
    });
    
    // Highlight first row after sorting
    if (rows.length > 0) {
        rows.forEach(function(row) {
            row.classList.remove('bg-blue-600', 'text-white');
        });
        rows[0].classList.add('bg-blue-600', 'text-white');
    }
    
    console.log("Sorting complete");
}

// Function to edit selected row (Edit button)
function editSelectedRow() {
    var selectedRow = document.querySelector('#productGroupDataTable tbody tr.bg-blue-600');
    if (!selectedRow) {
        console.log("No row selected");
        alert("Please select a product group first");
        return;
    }
    
    console.log("Selected row for editing:", selectedRow);
    
    // Open edit modal for selected row
    openEditProductGroupModal(selectedRow);
}

// Function to use selected product group (Select button)
function useSelectedProductGroup() {
    var selectedRow = document.querySelector('#productGroupDataTable tbody tr.bg-blue-600');
    if (!selectedRow) {
        console.log("No row selected");
        alert("Please select a product group first");
        return;
    }
    
    var pgId = selectedRow.cells[0].textContent.trim();
    var pgName = selectedRow.cells[1].textContent.trim();
    console.log("Selected product group ID: " + pgId);
    
    // Select product group and close modal
    selectProductGroup(pgId, pgName);
}

// Function to select current row
function selectCurrentRow() {
    var selectedRow = document.querySelector('#productGroupDataTable tbody tr.bg-blue-600');
    if (!selectedRow) {
        console.log("No row selected");
        alert("Please select a product group first");
        return;
    }
    
    var pgId = selectedRow.cells[0].textContent.trim();
    var pgName = selectedRow.cells[1].textContent.trim();
    console.log("Selected product group ID: " + pgId);
    
    // Select product group and close modal
    selectProductGroup(pgId, pgName);
}

// Function to select product group
function selectProductGroup(pgId, pgName) {
    console.log('Selecting product group: ' + pgId + ' - ' + pgName);
    
    // Fill input form with selected product group ID
    var pgInput = document.querySelector('input[type="text"]');
    if (pgInput) {
        pgInput.value = pgId;
    }
    
    // Close modal
    closeProductGroupModal();
}

// Function to select a row
function selectRow(row) {
    // Remove highlight from all rows
    var allRows = document.querySelectorAll('#productGroupDataTable tbody tr');
    allRows.forEach(function(r) {
        r.classList.remove('selected');
        r.classList.remove('bg-blue-600', 'text-white');
    });
    
    // Add highlighting to selected row
    row.classList.add('bg-blue-600', 'text-white');
}

// Function to open edit modal
function openEditProductGroupModal(row) {
    console.log('Opening edit product group modal for row:', row);
    
    const pgId = row.getAttribute('data-pg-id');
    const pgName = row.getAttribute('data-pg-name');
    const pgActive = row.getAttribute('data-pg-active');
    
    console.log('Product group data:', { pgId, pgName, pgActive });
    
    document.getElementById('edit_pg_id').value = pgId;
    document.getElementById('edit_pg_name').value = pgName;
    document.getElementById('edit_pg_active').value = pgActive;
    
    const editModal = document.getElementById('editProductGroupModal');
    editModal.style.display = 'block';
    editModal.classList.remove('hidden');
    
    console.log('Edit modal opened');
}

// Function to close edit modal
function closeEditProductGroupModal() {
    console.log('Closing edit product group modal');
    const editModal = document.getElementById('editProductGroupModal');
    editModal.classList.add('hidden');
    editModal.style.display = 'none';
}

// Function to display modal
function openProductGroupModal() {
    console.log('Opening product group modal');
    var modal = document.getElementById('productGroupTableWindow');
    if (!modal) {
        console.error('Modal element not found');
        return;
    }
    
    // Show modal
    modal.style.display = 'block';
    modal.classList.remove('hidden');
    
    // Populate table with data if needed
    populateProductGroupTable();
    
    // Sort by PG# by default
    sortTableDirectly(0);
    
    console.log('Modal opened successfully');
}

// Function to close modal
function closeProductGroupModal() {
    var modal = document.getElementById('productGroupTableWindow');
    if (modal) {
        modal.style.display = 'none';
        modal.classList.add('hidden');
    }
}

function closeModalX() {
    closeProductGroupModal();
}

// Function to make seed data available without database
function loadSeedData() {
    // Check if data is already loaded
    const tbody = document.querySelector('#productGroupDataTable tbody');
    if (!tbody) return;
    
    // Show loading overlay
    const loadingOverlay = document.getElementById('loadingOverlay');
    if (loadingOverlay) {
        loadingOverlay.classList.remove('hidden');
    }
    
    // Empty the table
    tbody.innerHTML = '';
    
    // Simulate loading
    setTimeout(() => {
        // Fill table with data from seedProductGroups
        populateProductGroupTable();
        
        // Show notification
        alert('Product group data loaded successfully from ProductGroupSeeder');
        
        // Hide loading overlay
        if (loadingOverlay) {
            loadingOverlay.classList.add('hidden');
        }
        
        // Update notification on main page
        const dbStatusElement = document.querySelector('.bg-yellow-100');
        if (dbStatusElement) {
            dbStatusElement.classList.remove('bg-yellow-100');
            dbStatusElement.classList.add('bg-green-100');
            dbStatusElement.innerHTML = `
                <p class="text-sm font-medium text-green-800">Data available: ${seedProductGroups.length} product groups found (from JavaScript).</p>
            `;
        }
        
        // Open modal to display data
        openProductGroupModal();
    }, 1000);
}

// Function to save product group changes
function saveProductGroupChanges() {
    console.log('Saving product group changes');
    
    const pgId = document.getElementById('edit_pg_id').value;
    const pgName = document.getElementById('edit_pg_name').value;
    const pgActive = document.getElementById('edit_pg_active').value === 'true';
    
    console.log('Form data to save:', { pgId, pgName, pgActive });
    
    // Display loading indicator on button and overlay
    const saveButton = document.querySelector('#editProductGroupForm button[type="submit"]');
    const originalText = saveButton.innerText;
    saveButton.innerText = 'Saving...';
    saveButton.disabled = true;
    
    // Show loading overlay
    document.getElementById('loadingOverlay').classList.remove('hidden');
    
    // Update row in table immediately to provide visual feedback
    const row = document.querySelector(`#productGroupDataTable tbody tr[data-pg-id="${pgId}"]`);
    if (row) {
        console.log('Found row to update:', row);
        
        try {
            // Direct DOM manipulation for cell updates
            const cells = row.cells;
            console.log('Row has cells:', cells.length);
            
            if (cells.length >= 3) {
                console.log('Original cell values:');
                console.log('- Cell 0 (PG#):', cells[0].textContent);
                console.log('- Cell 1 (PG Name):', cells[1].textContent);
                console.log('- Cell 2 (Status):', cells[2].textContent);
                
                // Update cell text directly
                cells[0].textContent = pgId;
                cells[1].textContent = pgName;
                cells[2].textContent = pgActive ? 'Active' : 'Inactive';
                
                console.log('After update:');
                console.log('- Cell 0 (PG#):', cells[0].textContent);
                console.log('- Cell 1 (PG Name):', cells[1].textContent);
                console.log('- Cell 2 (Status):', cells[2].textContent);
                
                // Update data attributes
                row.setAttribute('data-pg-id', pgId);
                row.setAttribute('data-pg-name', pgName);
                row.setAttribute('data-pg-active', pgActive.toString());
                
                // Highlight row with Tailwind classes to ensure visibility
                row.classList.add('bg-blue-600', 'text-white');
                
                // Also update seedProductGroups array to keep data in sync
                updateSeedProductGroupData(pgId, pgName, pgActive);
            } else {
                console.error('Row does not have enough cells:', cells.length);
            }
        } catch (error) {
            console.error('Error updating row cells:', error);
        }
        
        console.log('Row updated successfully in the table');
    } else {
        console.log('Row not found, creating new row');
        
        // Create a new row if it doesn't exist
        const tbody = document.querySelector('#productGroupDataTable tbody');
        
        // Create a new row
        const newRow = document.createElement('tr');
        newRow.className = 'hover:bg-blue-50 cursor-pointer';
        newRow.setAttribute('data-pg-id', pgId);
        newRow.setAttribute('data-pg-name', pgName);
        newRow.setAttribute('data-pg-active', pgActive.toString());
        newRow.onclick = function(e) { selectRow(this); e.stopPropagation(); };
        newRow.ondblclick = function() { openEditProductGroupModal(this); };
        
        // Create cells
        const idCell = document.createElement('td');
        idCell.className = 'px-4 py-3 whitespace-nowrap font-medium text-gray-900';
        idCell.textContent = pgId;
        
        const nameCell = document.createElement('td');
        nameCell.className = 'px-4 py-3 whitespace-nowrap text-gray-700';
        nameCell.textContent = pgName;
        
        const statusCell = document.createElement('td');
        statusCell.className = 'px-4 py-3 whitespace-nowrap text-gray-700';
        statusCell.textContent = pgActive ? 'Active' : 'Inactive';
        
        // Add cells to row
        newRow.appendChild(idCell);
        newRow.appendChild(nameCell);
        newRow.appendChild(statusCell);
        
        // Add row to table
        tbody.appendChild(newRow);
        
        // Select the new row
        selectRow(newRow);
        
        // Add to seedProductGroups
        updateSeedProductGroupData(pgId, pgName, pgActive);
    }
    
    // Show success message and close modal
    alert('Product group data updated successfully');
    closeEditProductGroupModal();
    
    // Reset button state and hide loading overlay after a short delay
    setTimeout(() => {
        saveButton.innerText = originalText;
        saveButton.disabled = false;
        document.getElementById('loadingOverlay').classList.add('hidden');
    }, 500);
}

// Function to update data in seedProductGroups array
function updateSeedProductGroupData(pgId, pgName, pgActive) {
    // Find product group with matching ID in seedProductGroups array
    const groupIndex = seedProductGroups.findIndex(group => group.product_group_id === pgId);
    
    if (groupIndex !== -1) {
        console.log(`Updating seedProductGroups[${groupIndex}] with new data`);
        
        // Update data in array
        seedProductGroups[groupIndex].product_group_name = pgName;
        seedProductGroups[groupIndex].is_active = pgActive;
        
        console.log('Updated seedProductGroups:', seedProductGroups[groupIndex]);
    } else {
        console.log(`Product group with ID ${pgId} not found in seedProductGroups array`);
        
        // If not found, add as new item
        seedProductGroups.push({
            product_group_id: pgId,
            product_group_name: pgName,
            is_active: pgActive
        });
        
        console.log('Added new product group to seedProductGroups array');
    }
}

// Function to set up events on table rows
function setupTableRowEvents() {
    console.log('Setting up table row events');
    var tableRows = document.querySelectorAll('#productGroupDataTable tbody tr');
    console.log('Table rows found:', tableRows.length);
    
    tableRows.forEach(function(row) {
        // Skip "no data" row
        if (row.querySelector('td[colspan]')) return;
        
        // Click event to select row
        row.onclick = function(e) {
            e.stopPropagation();
            selectRow(this);
        };
        
        // Double-click event to open edit modal
        row.ondblclick = function() {
            console.log('Double-click detected on row:', this);
            openEditProductGroupModal(this);
        };
    });
}

// Initialize event handlers when document loads
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM content loaded');
    
    // Setup row events initially
    setupTableRowEvents();
    
    // Initialize product group table if open button exists
    const showBtn = document.getElementById('showProductGroupTableBtn');
    if (showBtn) {
        console.log('Show button found, setting up event listener');
    }
    
    // Close modal when clicking outside table
    const productGroupModal = document.getElementById('productGroupTableWindow');
    if (productGroupModal) {
        productGroupModal.onclick = function(e) {
            if (e.target === productGroupModal) {
                closeProductGroupModal();
            }
        };
    }
    
    // Event to close modal when clicking outside
    window.onclick = function(event) {
        const editModal = document.getElementById('editProductGroupModal');
        if (event.target === editModal) {
            closeEditProductGroupModal();
        }
    };
    
    // Add event listener for search functionality
    const searchInput = document.getElementById('searchProductGroupInput');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const rows = document.querySelectorAll('#productGroupDataTable tbody tr');
            
            rows.forEach(row => {
                // Skip the "no data" row if it exists
                if (row.querySelector('td[colspan="3"]')) return;
                
                const pgId = row.querySelector('td:nth-child(1)').textContent.toLowerCase();
                const pgName = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
                const status = row.querySelector('td:nth-child(3)').textContent.toLowerCase();
                
                if (pgId.includes(searchTerm) || 
                    pgName.includes(searchTerm) || 
                    status.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    }
});
