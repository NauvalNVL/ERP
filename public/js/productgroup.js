// ProductGroup.js - Functions for product group management

async function populateProductGroupTable() {
    try {
        const response = await fetch('/product-group', {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        });

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const data = await response.json();
        console.log('Product groups data from server:', data);
        
        if (data.success) {
            const productGroups = data.data;
            const tbody = document.querySelector('#productGroupDataTable tbody');
            if (!tbody) {
                throw new Error('Table body element not found');
            }
            tbody.innerHTML = '';

            productGroups.forEach(group => {
                // Convert is_active to a boolean if it's not already
                const isActive = typeof group.is_active === 'boolean' ? group.is_active : 
                               (group.is_active === 1 || group.is_active === '1' || group.is_active === 'true');
                
                console.log(`Product Group ${group.product_group_id}: is_active = ${group.is_active}, converted to ${isActive}`);
                
                const row = document.createElement('tr');
                row.className = 'hover:bg-blue-50 cursor-pointer';
                row.setAttribute('data-pg-id', group.product_group_id);
                row.setAttribute('data-pg-name', group.product_group_name);
                row.setAttribute('data-pg-active', isActive);
                row.onclick = function(e) { selectRow(this); e.stopPropagation(); };
                row.ondblclick = function() { openEditProductGroupModal(this); };
                
                row.innerHTML = `
                    <td class="px-4 py-3 whitespace-nowrap font-medium text-gray-900">${group.product_group_id}</td>
                    <td class="px-4 py-3 whitespace-nowrap text-gray-700">${group.product_group_name}</td>
                    <td class="px-4 py-3 whitespace-nowrap text-gray-700">${isActive ? 'Active' : 'Inactive'}</td>
                `;
                tbody.appendChild(row);
            });

            // Setup event handlers for the table rows
            setupTableRowEvents();
            
            // Sort table based on PG# by default
            sortTableDirectly(0);
            
            // Update the data status message
            const dbStatusElement = document.querySelector('.bg-yellow-100, .bg-green-100');
            if (dbStatusElement) {
                dbStatusElement.className = 'mt-4 bg-green-100 p-3 rounded';
                dbStatusElement.innerHTML = `
                    <p class="text-sm font-medium text-green-800">Data tersedia: ${productGroups.length} product group ditemukan.</p>
                `;
            }
        } else {
            throw new Error(data.message || 'Failed to load data');
        }
    } catch (error) {
        console.error('Error loading data:', error);
        const tbody = document.querySelector('#productGroupDataTable tbody');
        if (tbody) {
            tbody.innerHTML = `<tr><td colspan="3" class="px-4 py-4 text-center text-red-500">
                Error loading data from database. ${error.message}
                <br>
                <button onclick="populateProductGroupTable()" class="mt-2 px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                    <i class="fas fa-sync-alt mr-2"></i>Coba Lagi
                </button>
            </td></tr>`;
        }
    }
}

async function saveProductGroup(event) {
    event.preventDefault();
    const id = document.getElementById('productGroupId').value;
    const name = document.getElementById('productGroupName').value;
    const description = document.getElementById('productGroupDescription').value;

    try {
        const url = id ? `/product-group/${id}` : '/product-group';
        const method = id ? 'PUT' : 'POST';
        const response = await fetch(url, {
            method: method,
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                product_group_name: name,
                product_group_description: description
            })
        });

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const data = await response.json();
        if (data.success) {
            alert(id ? 'Product group updated successfully!' : 'Product group created successfully!');
            document.getElementById('productGroupForm').reset();
            document.getElementById('productGroupId').value = '';
            document.getElementById('saveBtn').textContent = 'Save';
            populateProductGroupTable();
        } else {
            throw new Error(data.message || 'Failed to save data');
        }
    } catch (error) {
        console.error('Error saving data:', error);
        alert('Error saving data. ' + error.message);
    }
}

async function deleteProductGroup() {
    const selectedRow = document.querySelector('#productGroupDataTable tbody tr.bg-blue-600');
    if (!selectedRow) {
        alert('Please select a product group first');
        return;
    }
    
    const pgId = selectedRow.getAttribute('data-pg-id');
    if (!confirm(`Are you sure you want to delete product group ${pgId}?`)) {
        return;
    }
    
    // Show loading overlay
    showLoadingOverlay();
    
    fetch(`/product-group/${pgId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            // Refresh the table
            populateProductGroupTable();
            
            // Show success message
            alert('Product group deleted successfully!');
        } else {
            throw new Error(data.message || 'Failed to delete data');
        }
    })
    .catch(error => {
        console.error('Error deleting product group:', error);
        alert('Error: ' + error.message);
    })
    .finally(() => {
        // Hide loading overlay
        hideLoadingOverlay();
    });
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
    
    const idField = document.getElementById('edit_pg_id');
    idField.value = pgId;
    idField.readOnly = true; // Set to read-only for edit mode
    
    document.getElementById('edit_pg_name').value = pgName;
    document.getElementById('edit_pg_active').value = pgActive;
    
    // Change button text for edit mode
    const saveButton = document.querySelector('#editProductGroupForm button[type="submit"]');
    saveButton.innerHTML = '<i class="fas fa-save mr-2"></i>Update';
    
    const editModal = document.getElementById('editProductGroupModal');
    editModal.style.display = 'flex';
    
    console.log('Edit modal opened');
}

// Function to close edit modal
function closeEditProductGroupModal() {
    console.log('Closing edit product group modal');
    const editModal = document.getElementById('editProductGroupModal');
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
    modal.style.display = 'flex';
    
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
    }
}

function closeModalX() {
    closeProductGroupModal();
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

// Function to show loading overlay
function showLoadingOverlay() {
    document.getElementById('loadingOverlay').style.display = 'flex';
}

// Function to hide loading overlay
function hideLoadingOverlay() {
    document.getElementById('loadingOverlay').style.display = 'none';
}

// Enhancement to handle both add and edit
function handleProductGroupFormSubmit(event) {
    event.preventDefault();
    
    const pgId = document.getElementById('edit_pg_id').value;
    const pgName = document.getElementById('edit_pg_name').value;
    // Get the value from the dropdown
    const pgActiveValue = document.getElementById('edit_pg_active').value;
    // Convert string to boolean for server
    const pgActive = pgActiveValue === 'true';
    
    console.log(`Form data: pgActiveValue=${pgActiveValue}, converted to pgActive=${pgActive}`);
    
    // Validate input
    if (!pgId) {
        alert('Product Group ID tidak boleh kosong');
        return;
    }
    
    if (!pgName) {
        alert('Product Group Name tidak boleh kosong');
        return;
    }
    
    // Display loading indicator
    const saveButton = document.querySelector('#editProductGroupForm button[type="submit"]');
    const originalText = saveButton.innerHTML;
    saveButton.innerHTML = '<i class="fas fa-circle-notch fa-spin mr-2"></i>Menyimpan...';
    saveButton.disabled = true;
    
    // Show loading overlay
    showLoadingOverlay();
    
    // Determine if this is an add or update operation
    const isAdd = document.getElementById('edit_pg_id').readOnly === false;
    const method = isAdd ? 'POST' : 'PUT';
    const url = isAdd ? '/product-group' : `/product-group/${pgId}`;
    
    console.log('Submitting data:', { 
        url, 
        method, 
        isAdd,
        data: {
            product_group_id: pgId,
            product_group_name: pgName,
            is_active: pgActive
        }
    });
    
    fetch(url, {
        method: method,
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({
            product_group_id: pgId,
            product_group_name: pgName,
            is_active: pgActive
        })
    })
    .then(response => {
        if (!response.ok) {
            return response.json().then(data => {
                throw new Error(data.message || `HTTP error! status: ${response.status}`);
            });
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            console.log('Save successful, server response:', data);
            
            // If this was an update, update the row in the table
            if (!isAdd && data.data) {
                const updatedGroup = data.data;
                const isActive = typeof updatedGroup.is_active === 'boolean' ? updatedGroup.is_active : 
                               (updatedGroup.is_active === 1 || updatedGroup.is_active === '1' || updatedGroup.is_active === 'true');
                
                // Update the row in the table
                const row = document.querySelector(`#productGroupDataTable tbody tr[data-pg-id="${pgId}"]`);
                if (row) {
                    // Update the row attributes
                    row.setAttribute('data-pg-name', updatedGroup.product_group_name);
                    row.setAttribute('data-pg-active', isActive);
                    
                    // Update the cells
                    const cells = row.cells;
                    if (cells.length >= 3) {
                        cells[1].textContent = updatedGroup.product_group_name;
                        cells[2].textContent = isActive ? 'Active' : 'Inactive';
                    }
                }
            }
            
            // Close the modal
            closeEditProductGroupModal();
            
            // Refresh the table to ensure it's up to date
            populateProductGroupTable();
            
            // Show success message
            alert(isAdd ? 'Product group added successfully!' : 'Product group updated successfully!');
        } else {
            throw new Error(data.message || 'Failed to save data');
        }
    })
    .catch(error => {
        console.error('Error saving product group:', error);
        alert('Error: ' + error.message);
    })
    .finally(() => {
        // Reset button state
        saveButton.innerHTML = originalText;
        saveButton.disabled = false;
        
        // Hide loading overlay
        hideLoadingOverlay();
    });
}

// Function to add a new product group
function openAddProductGroupModal() {
    // Clear form
    document.getElementById('edit_pg_id').value = '';
    document.getElementById('edit_pg_id').readOnly = false;
    document.getElementById('edit_pg_name').value = '';
    document.getElementById('edit_pg_active').value = 'true';
    
    // Change button text
    const saveButton = document.querySelector('#editProductGroupForm button[type="submit"]');
    saveButton.innerHTML = '<i class="fas fa-plus mr-2"></i>Add';
    
    // Show modal
    const editModal = document.getElementById('editProductGroupModal');
    editModal.style.display = 'flex';
    
    // Set focus to the first field
    document.getElementById('edit_pg_id').focus();
}

// Initialize event handlers when document loads
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM content loaded for product group');
    
    // Setup row events initially
    setupTableRowEvents();
    
    // Initialize product group table if open button exists
    const showBtn = document.getElementById('showProductGroupTableBtn');
    if (showBtn) {
        console.log('Show button found, setting up event listener');
        // showBtn is already bound in HTML with onclick="openProductGroupModal()"
    }
    
    // Add New button functionality
    const addNewBtn = document.getElementById('addProductGroupBtn');
    if (addNewBtn) {
        addNewBtn.addEventListener('click', openAddProductGroupModal);
    }
    
    // Delete button functionality
    const deleteBtn = document.getElementById('deleteProductGroupBtn');
    if (deleteBtn) {
        deleteBtn.addEventListener('click', deleteProductGroup);
    }
    
    // Override the form submit handler
    const editForm = document.getElementById('editProductGroupForm');
    if (editForm) {
        editForm.onsubmit = handleProductGroupFormSubmit;
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
                if (row.querySelector('td[colspan]')) return;
                
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
