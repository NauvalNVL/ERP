// Paperflute.js - Functions for paper flute management

// Define seed data for paper flutes
const seedFlutes = [
    { flute_id: 'F001', flute_name: 'Single Face', description: 'Standard fluting paper', flute_height: '3.0', is_active: true },
    { flute_id: 'F002', flute_name: 'Single Wall', description: 'Medium strength fluting', flute_height: '3.6', is_active: true },
    { flute_id: 'F003', flute_name: 'Double Wall', description: 'Heavy duty fluting paper', flute_height: '7.0', is_active: true },
    { flute_id: 'F004', flute_name: 'Triple Wall', description: 'Extra heavy duty fluting', flute_height: '10.5', is_active: true },
    { flute_id: 'F005', flute_name: 'A Flute', description: 'Offers excellent cushioning', flute_height: '4.8', is_active: true },
    { flute_id: 'F006', flute_name: 'B Flute', description: 'Good crush resistance', flute_height: '3.2', is_active: true },
    { flute_id: 'F007', flute_name: 'C Flute', description: 'Standard shipping boxes', flute_height: '4.0', is_active: true },
    { flute_id: 'F008', flute_name: 'E Flute', description: 'Thin profile, good printability', flute_height: '1.6', is_active: true },
    { flute_id: 'F009', flute_name: 'F Flute', description: 'Very thin profile', flute_height: '0.8', is_active: true },
    { flute_id: 'F010', flute_name: 'BC Flute', description: 'Combined B and C flutes', flute_height: '6.5', is_active: false }
];

function populateFluteTable() {
    console.log('Populating flute table');
    
    // Check if table already has data
    const tbody = document.querySelector('#fluteDataTable tbody');
    const rows = tbody.querySelectorAll('tr');
    const isEmpty = rows.length === 0 || (rows.length === 1 && rows[0].querySelector('td[colspan]'));
    
    if (isEmpty) {
        console.log('Table is empty, populating with seed data');
        
        // Remove "no data" message if present
        tbody.innerHTML = '';
        
        // Fill table with data from seedFlutes
        seedFlutes.forEach(flute => {
            const row = document.createElement('tr');
            row.className = 'cursor-pointer hover:bg-blue-50';
            row.setAttribute('data-flute-id', flute.flute_id);
            row.setAttribute('data-flute-name', flute.flute_name);
            row.setAttribute('data-description', flute.description);
            row.setAttribute('data-flute-height', flute.flute_height);
            row.setAttribute('data-is-active', flute.is_active);
            row.onclick = function(e) { selectRow(this); e.stopPropagation(); };
            row.ondblclick = function() { openEditFluteModal(this); };
            
            // Create columns for each cell
            const idCell = document.createElement('td');
            idCell.className = 'px-4 py-3 whitespace-nowrap font-medium text-gray-900';
            idCell.textContent = flute.flute_id;
            
            const nameCell = document.createElement('td');
            nameCell.className = 'px-4 py-3 whitespace-nowrap text-gray-700';
            nameCell.textContent = flute.flute_name;
            
            const descriptionCell = document.createElement('td');
            descriptionCell.className = 'px-4 py-3 whitespace-nowrap text-gray-700';
            descriptionCell.textContent = flute.description;
            
            const heightCell = document.createElement('td');
            heightCell.className = 'px-4 py-3 whitespace-nowrap text-gray-700';
            heightCell.textContent = flute.flute_height;
            
            const statusCell = document.createElement('td');
            statusCell.className = 'px-4 py-3 whitespace-nowrap';
            
            const statusBadge = document.createElement('span');
            statusBadge.className = flute.is_active 
                ? 'px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800' 
                : 'px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800';
            statusBadge.textContent = flute.is_active ? 'Active' : 'Inactive';
            statusCell.appendChild(statusBadge);
            
            // Add all cells to row
            row.appendChild(idCell);
            row.appendChild(nameCell);
            row.appendChild(descriptionCell);
            row.appendChild(heightCell);
            row.appendChild(statusCell);
            
            // Add row to table
            tbody.appendChild(row);
        });
        
        console.log('Table populated with seed data');
        
        // Setup event handlers for the table rows
        setupTableRowEvents();
        
        // Sort table by Flute ID
        sortTableDirectly(0);
    } else {
        console.log('Table already has data, no need to populate');
    }
}

// Function to sort table
function sortTableDirectly(columnIndex) {
    console.log("Sorting by column: " + columnIndex);
    
    var table = document.getElementById('fluteDataTable');
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
        
        // Numeric-based sort for ID and height columns
        if (columnIndex === 0 || columnIndex === 3) {
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
    var selectedRow = document.querySelector('#fluteDataTable tbody tr.bg-blue-600');
    if (!selectedRow) {
        console.log("No row selected");
        alert("Please select a flute first");
        return;
    }
    
    console.log("Selected row for editing:", selectedRow);
    
    // Open edit modal for selected row
    openEditFluteModal(selectedRow);
}

// Function to select a row
function selectRow(row) {
    // Remove highlight from all rows
    var allRows = document.querySelectorAll('#fluteDataTable tbody tr');
    allRows.forEach(function(r) {
        r.classList.remove('selected');
        r.classList.remove('bg-blue-600', 'text-white');
    });
    
    // Add highlighting to selected row
    row.classList.add('bg-blue-600', 'text-white');
}

// Function to open edit modal
function openEditFluteModal(row) {
    console.log('Opening edit flute modal for row:', row);
    
    const fluteId = row.getAttribute('data-flute-id');
    const fluteName = row.getAttribute('data-flute-name');
    const description = row.getAttribute('data-description');
    const fluteHeight = row.getAttribute('data-flute-height');
    const isActive = row.getAttribute('data-is-active') === 'true';
    
    console.log('Flute data:', { fluteId, fluteName, description, fluteHeight, isActive });
    
    document.getElementById('edit_flute_id').value = fluteId;
    document.getElementById('edit_flute_name').value = fluteName;
    document.getElementById('edit_description').value = description;
    document.getElementById('edit_flute_height').value = fluteHeight;
    document.getElementById('edit_is_active').checked = isActive;
    
    const editModal = document.getElementById('editFluteModal');
    editModal.style.display = 'block';
    editModal.classList.remove('hidden');
    
    console.log('Edit modal opened');
}

// Function to close edit modal
function closeEditFluteModal() {
    console.log('Closing edit flute modal');
    const editModal = document.getElementById('editFluteModal');
    editModal.classList.add('hidden');
    editModal.style.display = 'none';
}

// Function to display modal
function openFluteModal() {
    console.log('Opening flute modal');
    var modal = document.getElementById('fluteTableWindow');
    if (!modal) {
        console.error('Modal element not found');
        return;
    }
    
    // Show modal
    modal.style.display = 'block';
    modal.classList.remove('hidden');
    
    // Populate table with data from seed data if needed
    populateFluteTable();
    
    // Sort by Flute ID by default
    sortTableDirectly(0);
    
    console.log('Modal opened successfully');
}

// Function to close modal
function closeFluteModal() {
    var modal = document.getElementById('fluteTableWindow');
    if (modal) {
        modal.style.display = 'none';
        modal.classList.add('hidden');
    }
}

function closeModalX() {
    closeFluteModal();
}

// Function to set up events on table rows
function setupTableRowEvents() {
    console.log('Setting up table row events');
    var tableRows = document.querySelectorAll('#fluteDataTable tbody tr');
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
            openEditFluteModal(this);
        };
    });
}

// Function to save flute changes
function saveFluteChanges() {
    console.log('Saving flute changes');
    
    const fluteId = document.getElementById('edit_flute_id').value;
    const fluteName = document.getElementById('edit_flute_name').value;
    const description = document.getElementById('edit_description').value;
    const fluteHeight = document.getElementById('edit_flute_height').value;
    const isActive = document.getElementById('edit_is_active').checked;
    
    console.log('Form data to save:', { fluteId, fluteName, description, fluteHeight, isActive });
    
    // Display loading indicator on button and overlay
    const saveButton = document.querySelector('#editFluteForm button[type="submit"]');
    const originalText = saveButton.innerText;
    saveButton.innerText = 'Saving...';
    saveButton.disabled = true;
    
    // Show loading overlay
    document.getElementById('loadingOverlay').classList.remove('hidden');
    
    // Update row in table immediately to provide visual feedback
    const row = document.querySelector(`#fluteDataTable tbody tr[data-flute-id="${fluteId}"]`);
    if (row) {
        console.log('Found row to update:', row);
        
        try {
            // Direct DOM manipulation for cell updates
            const cells = row.cells;
            console.log('Row has cells:', cells.length);
            
            if (cells.length >= 5) {
                // Update cell text directly
                cells[1].textContent = fluteName;
                cells[2].textContent = description;
                cells[3].textContent = fluteHeight;
                
                // Update status badge
                const statusBadge = cells[4].querySelector('span');
                statusBadge.className = isActive 
                    ? 'px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800' 
                    : 'px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800';
                statusBadge.textContent = isActive ? 'Active' : 'Inactive';
                
                // Update data attributes
                row.setAttribute('data-flute-name', fluteName);
                row.setAttribute('data-description', description);
                row.setAttribute('data-flute-height', fluteHeight);
                row.setAttribute('data-is-active', isActive);
                
                // Highlight row with Tailwind classes to ensure visibility
                row.classList.add('bg-blue-600', 'text-white');
                
                // Also update seedFlutes array to keep data in sync
                updateSeedFluteData(fluteId, fluteName, description, fluteHeight, isActive);
            } else {
                console.error('Row does not have enough cells:', cells.length);
            }
        } catch (error) {
            console.error('Error updating row cells:', error);
        }
        
        console.log('Row updated successfully in the table');
    } else {
        console.error('Row not found in table for flute ID:', fluteId);
    }
    
    // Show success message and close modal
    alert('Paper flute data updated successfully');
    closeEditFluteModal();
    
    // Reset button state and hide loading overlay after a short delay
    setTimeout(() => {
        saveButton.innerText = originalText;
        saveButton.disabled = false;
        document.getElementById('loadingOverlay').classList.add('hidden');
    }, 500);
}

// Function to update data in seedFlutes array
function updateSeedFluteData(fluteId, fluteName, description, fluteHeight, isActive) {
    // Find flute with matching ID in seedFlutes array
    const fluteIndex = seedFlutes.findIndex(flute => flute.flute_id === fluteId);
    
    if (fluteIndex !== -1) {
        console.log(`Updating seedFlutes[${fluteIndex}] with new data`);
        
        // Update data in array
        seedFlutes[fluteIndex].flute_name = fluteName;
        seedFlutes[fluteIndex].description = description;
        seedFlutes[fluteIndex].flute_height = fluteHeight;
        seedFlutes[fluteIndex].is_active = isActive;
        
        console.log('Updated seedFlutes:', seedFlutes[fluteIndex]);
    } else {
        console.log(`Flute with ID ${fluteId} not found in seedFlutes array`);
        
        // If not found, add as new item
        seedFlutes.push({
            flute_id: fluteId,
            flute_name: fluteName,
            description: description,
            flute_height: fluteHeight,
            is_active: isActive
        });
        
        console.log('Added new flute to seedFlutes array');
    }
}

// Function to make seed data available without database
function loadSeedData() {
    // Check if data is already loaded
    const tbody = document.querySelector('#fluteDataTable tbody');
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
        // Fill table with data from seedFlutes
        populateFluteTable();
        
        // Show notification
        alert('Paper flute data loaded successfully');
        
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
                <p class="text-sm font-medium text-green-800">Data available: ${seedFlutes.length} paper flutes found (from JavaScript).</p>
            `;
        }
        
        // Open modal to display data
        openFluteModal();
    }, 1000);
}

// Initialize event handlers when document loads
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM content loaded for Paper Flute');
    
    // Setup row events initially
    setupTableRowEvents();
    
    // Initialize flute table if open button exists
    const showBtn = document.getElementById('showFluteTableBtn');
    if (showBtn) {
        console.log('Show button found, setting up event listener');
        showBtn.addEventListener('click', openFluteModal);
    }
    
    // Close modal when clicking outside table
    const fluteModal = document.getElementById('fluteTableWindow');
    if (fluteModal) {
        fluteModal.onclick = function(e) {
            if (e.target === fluteModal) {
                closeFluteModal();
            }
        };
    }
    
    // Event to close modal when clicking outside
    window.onclick = function(event) {
        const editModal = document.getElementById('editFluteModal');
        if (event.target === editModal) {
            closeEditFluteModal();
        }
    };
    
    // Add event listener for search functionality
    const searchInput = document.getElementById('searchFluteInput');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const rows = document.querySelectorAll('#fluteDataTable tbody tr');
            
            rows.forEach(row => {
                // Skip the "no data" row if it exists
                if (row.querySelector('td[colspan]')) return;
                
                const fluteId = row.querySelector('td:nth-child(1)').textContent.toLowerCase();
                const fluteName = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
                const description = row.querySelector('td:nth-child(3)').textContent.toLowerCase();
                
                if (fluteId.includes(searchTerm) || 
                    fluteName.includes(searchTerm) || 
                    description.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    }
});
