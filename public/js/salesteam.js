// SalesTeam.js - Functions for sales team management

// Define seed data for sales teams
const seedSalesTeams = [
    { code: '01', name: 'MBI' },
    { code: '02', name: 'MANAGEMENT LOCAL' },
    { code: '03', name: 'MANAGEMENT MNC' }
];

// Function to populate sales team table with seed data
function populateSalesTeamTable() {
    console.log('Populating sales team table');
    
    // Check if table already has data
    const tbody = document.querySelector('#salesTeamDataTable tbody');
    const rows = tbody.querySelectorAll('tr');
    const isEmpty = rows.length === 0 || (rows.length === 1 && rows[0].querySelector('td[colspan]'));
    
    if (isEmpty) {
        console.log('Table is empty, populating with seed data');
        
        // Remove "no data" message if present
        tbody.innerHTML = '';
        
        // Fill table with data from seedSalesTeams
        seedSalesTeams.forEach(team => {
            const row = document.createElement('tr');
            row.className = 'cursor-pointer';
            row.setAttribute('data-team-code', team.code);
            row.setAttribute('data-team-name', team.name);
            row.onclick = function(e) { selectRow(this); e.stopPropagation(); };
            row.ondblclick = function() { openEditSalesTeamModal(this); };
            
            // Create columns for each cell
            const codeCell = document.createElement('td');
            codeCell.className = 'px-2 py-0.5 border-b border-r border-gray-300';
            codeCell.textContent = team.code;
            
            const nameCell = document.createElement('td');
            nameCell.className = 'px-2 py-0.5 border-b border-gray-300';
            nameCell.textContent = team.name;
            
            // Add all cells to row
            row.appendChild(codeCell);
            row.appendChild(nameCell);
            
            // Add row to table
            tbody.appendChild(row);
        });
        
        console.log('Table populated with seed data');
        
        // Setup event handlers for the table rows
        setupTableRowEvents();
        
        // Sort table by Code by default
        sortTableDirectly(0);
    } else {
        console.log('Table already has data, no need to populate');
    }
}

// Function to sort table
function sortTableDirectly(columnIndex) {
    console.log("Sorting by column: " + columnIndex);
    
    var table = document.getElementById('salesTeamDataTable');
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
        
        // Numeric-based sort for ID columns
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
    var selectedRow = document.querySelector('#salesTeamDataTable tbody tr.bg-blue-600');
    if (!selectedRow) {
        console.log("No row selected");
        alert("Silahkan pilih sales team terlebih dahulu");
        return;
    }
    
    console.log("Selected row for editing:", selectedRow);
    
    // Open edit modal for selected row
    openEditSalesTeamModal(selectedRow);
}

// Function to select a row
function selectRow(row) {
    // Remove highlight from all rows
    var allRows = document.querySelectorAll('#salesTeamDataTable tbody tr');
    allRows.forEach(function(r) {
        r.classList.remove('selected');
        r.classList.remove('bg-blue-600', 'text-white');
    });
    
    // Add highlighting to selected row
    row.classList.add('bg-blue-600', 'text-white');
}

// Function to open edit modal
function openEditSalesTeamModal(row) {
    console.log('Opening edit sales team modal for row:', row);
    
    const code = row.getAttribute('data-team-code');
    const name = row.getAttribute('data-team-name');
    
    console.log('Sales team data:', { code, name });
    
    document.getElementById('edit_team_code').value = code;
    document.getElementById('edit_team_name').value = name;
    
    const editModal = document.getElementById('editSalesTeamModal');
    editModal.style.display = 'block';
    editModal.classList.remove('hidden');
    
    console.log('Edit modal opened');
}

// Function to close edit modal
function closeEditSalesTeamModal() {
    console.log('Closing edit sales team modal');
    const editModal = document.getElementById('editSalesTeamModal');
    editModal.classList.add('hidden');
    editModal.style.display = 'none';
}

// Function to display modal
function openSalesTeamModal() {
    console.log('Opening sales team modal');
    var modal = document.getElementById('salesTeamTableWindow');
    if (!modal) {
        console.error('Modal element not found');
        return;
    }
    
    // Show modal
    modal.style.display = 'block';
    modal.classList.remove('hidden');
    
    // Populate table with data from SalesTeamSeeder if needed
    populateSalesTeamTable();
    
    // Sort by Code by default
    sortTableDirectly(0);
    
    console.log('Modal opened successfully');
}

// Function to close modal
function closeSalesTeamModal() {
    var modal = document.getElementById('salesTeamTableWindow');
    if (modal) {
        modal.style.display = 'none';
        modal.classList.add('hidden');
    }
}

function closeModalX() {
    closeSalesTeamModal();
}

// Function to set up events on table rows
function setupTableRowEvents() {
    console.log('Setting up table row events');
    var tableRows = document.querySelectorAll('#salesTeamDataTable tbody tr');
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
            openEditSalesTeamModal(this);
        };
    });
}

// Function to save sales team changes
function saveSalesTeamChanges() {
    console.log('Saving sales team changes');
    
    const code = document.getElementById('edit_team_code').value;
    const name = document.getElementById('edit_team_name').value;
    
    console.log('Form data to save:', { code, name });
    
    // Display loading indicator on button and overlay
    const saveButton = document.querySelector('#editSalesTeamForm button[type="submit"]');
    const originalText = saveButton.innerText;
    saveButton.innerText = 'Menyimpan...';
    saveButton.disabled = true;
    
    // Show loading overlay
    document.getElementById('loadingOverlay').classList.remove('hidden');
    
    // Update row in table immediately to provide visual feedback
    const row = document.querySelector(`#salesTeamDataTable tbody tr[data-team-code="${code}"]`);
    if (row) {
        console.log('Found row to update:', row);
        
        try {
            // Direct DOM manipulation for cell updates
            const cells = row.cells;
            console.log('Row has cells:', cells.length);
            
            if (cells.length >= 2) {
                console.log('Original cell values:');
                console.log('- Cell 0 (Code):', cells[0].textContent);
                console.log('- Cell 1 (Name):', cells[1].textContent);
                
                // Update cell text directly
                cells[0].textContent = code;
                cells[1].textContent = name;
                
                console.log('After update:');
                console.log('- Cell 0 (Code):', cells[0].textContent);
                console.log('- Cell 1 (Name):', cells[1].textContent);
                
                // Update data attributes
                row.setAttribute('data-team-code', code);
                row.setAttribute('data-team-name', name);
                
                // Highlight row with Tailwind classes to ensure visibility
                row.classList.add('bg-blue-600', 'text-white');
                
                // Also update seedSalesTeams array to keep data in sync
                updateSeedSalesTeamData(code, name);
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
        const tbody = document.querySelector('#salesTeamDataTable tbody');
        
        // Create a new row
        const newRow = document.createElement('tr');
        newRow.className = 'cursor-pointer';
        newRow.setAttribute('data-team-code', code);
        newRow.setAttribute('data-team-name', name);
        newRow.onclick = function(e) { selectRow(this); e.stopPropagation(); };
        newRow.ondblclick = function() { openEditSalesTeamModal(this); };
        
        // Create cells
        const codeCell = document.createElement('td');
        codeCell.className = 'px-2 py-0.5 border-b border-r border-gray-300';
        codeCell.textContent = code;
        
        const nameCell = document.createElement('td');
        nameCell.className = 'px-2 py-0.5 border-b border-gray-300';
        nameCell.textContent = name;
        
        // Add cells to row
        newRow.appendChild(codeCell);
        newRow.appendChild(nameCell);
        
        // Add row to table
        tbody.appendChild(newRow);
        
        // Select the new row
        selectRow(newRow);
        
        // Add to seedSalesTeams
        updateSeedSalesTeamData(code, name);
    }
    
    // Show success message and close modal
    alert('Data sales team berhasil diperbarui');
    closeEditSalesTeamModal();
    
    // Reset button state and hide loading overlay after a short delay
    setTimeout(() => {
        saveButton.innerText = originalText;
        saveButton.disabled = false;
        document.getElementById('loadingOverlay').classList.add('hidden');
    }, 500);
}

// Function to update data in seedSalesTeams array
function updateSeedSalesTeamData(code, name) {
    // Find team with matching code in seedSalesTeams array
    const teamIndex = seedSalesTeams.findIndex(team => team.code === code);
    
    if (teamIndex !== -1) {
        console.log(`Updating seedSalesTeams[${teamIndex}] with new data`);
        
        // Update data in array
        seedSalesTeams[teamIndex].name = name;
        
        console.log('Updated seedSalesTeams:', seedSalesTeams[teamIndex]);
    } else {
        console.log(`Sales team with code ${code} not found in seedSalesTeams array`);
        
        // If not found, add as new item
        seedSalesTeams.push({
            code: code,
            name: name
        });
        
        console.log('Added new sales team to seedSalesTeams array');
    }
}

// Function to make seed data available without database
function loadSeedData() {
    // Check if data is already loaded
    const tbody = document.querySelector('#salesTeamDataTable tbody');
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
        // Fill table with data from seedSalesTeams
        populateSalesTeamTable();
        
        // Show notification
        alert('Data sales team berhasil dimuat dari SalesTeamSeeder');
        
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
                <p class="text-sm font-medium text-green-800">Data tersedia: ${seedSalesTeams.length} sales team ditemukan (dari JavaScript).</p>
            `;
        }
        
        // Open modal to display data
        openSalesTeamModal();
    }, 1000);
}

// Initialize event handlers when document loads
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM content loaded');
    
    // Setup row events initially
    setupTableRowEvents();
    
    // Initialize sales team table if open button exists
    const showBtn = document.getElementById('showSalesTeamTableBtn');
    if (showBtn) {
        console.log('Show button found, setting up event listener');
    }
    
    // Setup load data button if it exists
    const loadDataBtn = document.getElementById('loadDataJsBtn');
    if (loadDataBtn) {
        loadDataBtn.addEventListener('click', loadSeedData);
    }
    
    // Close modal when clicking outside table
    const salesTeamModal = document.getElementById('salesTeamTableWindow');
    if (salesTeamModal) {
        salesTeamModal.onclick = function(e) {
            if (e.target === salesTeamModal) {
                closeSalesTeamModal();
            }
        };
    }
    
    // Event to close modal when clicking outside
    window.onclick = function(event) {
        const editModal = document.getElementById('editSalesTeamModal');
        if (event.target === editModal) {
            closeEditSalesTeamModal();
        }
    };
});
