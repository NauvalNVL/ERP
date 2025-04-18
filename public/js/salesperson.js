// Salesperson Management JavaScript
// This file provides functionality for the salesperson management page

// Define seed data for salespersons (used if no data in database)
const salespersonSeedData = [
    { code: 'S101', name: 'ABENG', sales_team_id: 1, position: 'E - Executive', user_id: 'root', is_active: 1 },
    { code: 'S102', name: 'AGUNG', sales_team_id: 1, position: 'M - Manager', user_id: 'SLS', is_active: 1 },
    { code: 'S103', name: 'EKO', sales_team_id: 2, position: 'S - Supervisor', user_id: 'SLS', is_active: 1 },
    { code: 'S104', name: 'ELIAS', sales_team_id: 2, position: 'E - Executive', user_id: 'SLS', is_active: 1 },
    { code: 'S105', name: 'FEBBY', sales_team_id: 3, position: 'M - Manager', user_id: 'SLS', is_active: 1 },
    { code: 'S106', name: 'HENGKY', sales_team_id: 3, position: 'S - Supervisor', user_id: 'SLS', is_active: 1 }
];

// Mapping for sales team IDs to names
const salesTeamNames = {
    1: 'MBI',
    2: 'MANAGEMENT LOCAL',
    3: 'MANAGEMENT MNC'
};

// Initialize and setup when document is ready
document.addEventListener('DOMContentLoaded', function() {
    console.log('Salesperson JS loaded');
    
    // Setup load data button if it exists
    const loadDataBtn = document.getElementById('loadDataJsBtn');
    if (loadDataBtn) {
        loadDataBtn.addEventListener('click', function() {
            loadSeedData();
        });
    }
    
    // Setup initial table row events
    setupTableRowEvents();
});

// Function to load seed data into the table
function loadSeedData() {
    showLoadingOverlay();
    
    // Simulate loading time
    setTimeout(function() {
        populateSalespersonTable();
        hideLoadingOverlay();
    }, 800);
}

// Function to populate salesperson table with seed data if needed
function populateSalespersonTable() {
    const table = document.getElementById('salespersonDataTable');
    if (!table) {
        console.error('Salesperson table not found in DOM');
        return;
    }
    
    const tbody = table.querySelector('tbody');
    
    // Only populate if table is empty or has just one row with "no data" message
    if (tbody.children.length <= 1 || (tbody.children.length === 1 && tbody.children[0].cells.length === 1)) {
        console.log('Populating salesperson table with seed data');
        tbody.innerHTML = '';
        
        salespersonSeedData.forEach(function(person) {
            const row = document.createElement('tr');
            row.className = 'hover:bg-blue-50 cursor-pointer';
            row.setAttribute('data-person-code', person.code);
            row.setAttribute('data-person-name', person.name);
            row.setAttribute('data-person-team', person.sales_team_id);
            row.setAttribute('data-person-position', person.position);
            row.setAttribute('data-person-user-id', person.user_id);
            row.setAttribute('data-person-is-active', person.is_active);
            
            row.onclick = function(e) {
                selectRow(this);
                e.stopPropagation();
            };
            
            row.ondblclick = function() {
                openEditSalespersonModal(this);
            };
            
            row.innerHTML = `
                <td class="px-4 py-3 whitespace-nowrap font-medium text-gray-900">${person.code}</td>
                <td class="px-4 py-3 whitespace-nowrap text-gray-700">${person.name}</td>
                <td class="px-4 py-3 whitespace-nowrap text-gray-700">${salesTeamNames[person.sales_team_id]}</td>
                <td class="px-4 py-3 whitespace-nowrap text-gray-700">${person.position}</td>
            `;
            
            tbody.appendChild(row);
        });
    }
}

// Function to sort the table by column
function sortTableDirectly(columnIndex) {
    const table = document.getElementById('salespersonDataTable');
    if (!table) return;
    
    const tbody = table.querySelector('tbody');
    const rows = Array.from(tbody.querySelectorAll('tr'));
    
    let sortDirection = 1;
    
    // Check if we're already sorted on this column and reverse direction if so
    if (table.getAttribute('data-sort-col') == columnIndex) {
        sortDirection = table.getAttribute('data-sort-dir') == '1' ? -1 : 1;
    }
    
    // Store sort state
    table.setAttribute('data-sort-col', columnIndex);
    table.setAttribute('data-sort-dir', sortDirection);
    
    // Sort the rows
    rows.sort((a, b) => {
        const cellA = a.cells[columnIndex].textContent.trim().toUpperCase();
        const cellB = b.cells[columnIndex].textContent.trim().toUpperCase();
        
        if (cellA < cellB) return -1 * sortDirection;
        if (cellA > cellB) return 1 * sortDirection;
        return 0;
    });
    
    // Remove all rows and re-add in sorted order
    rows.forEach(row => tbody.removeChild(row));
    rows.forEach(row => tbody.appendChild(row));
    
    // Re-setup row events after sorting
    setupTableRowEvents();
}

// Function to setup row events
function setupTableRowEvents() {
    const rows = document.querySelectorAll('#salespersonDataTable tbody tr');
    rows.forEach(row => {
        row.onclick = function(e) {
            selectRow(this);
            e.stopPropagation();
        };
        
        row.ondblclick = function() {
            openEditSalespersonModal(this);
        };
    });
}

// Function to select a row
function selectRow(row) {
    // Clear previous selection
    const allRows = document.querySelectorAll('#salespersonDataTable tbody tr');
    allRows.forEach(r => r.classList.remove('bg-blue-200'));
    
    // Add selected class to this row
    row.classList.add('bg-blue-200');
}

// Function to edit the selected row
function editSelectedRow() {
    const selectedRow = document.querySelector('#salespersonDataTable tbody tr.bg-blue-200');
    if (selectedRow) {
        openEditSalespersonModal(selectedRow);
    } else {
        alert('Please select a salesperson to edit');
    }
}

// Open salesperson table modal
function openSalespersonModal() {
    const modal = document.getElementById('salespersonTableWindow');
    if (modal) {
        modal.classList.remove('hidden');
        populateSalespersonTable();
        sortTableDirectly(0); // Default sort by first column
        
        // Filter functionality for search input
        const searchInput = document.getElementById('searchSalespersonInput');
        if (searchInput) {
            searchInput.value = ''; // Clear previous search
            searchInput.focus();
            
            // Add event listener if not already added
            if (!searchInput.hasAttribute('data-listener-added')) {
                searchInput.setAttribute('data-listener-added', 'true');
                searchInput.addEventListener('input', function() {
                    filterSalespersonTable(this.value);
                });
            }
        }
    }
}

// Close salesperson table modal
function closeSalespersonModal() {
    const modal = document.getElementById('salespersonTableWindow');
    if (modal) {
        modal.classList.add('hidden');
    }
}

// Function for the X button in the modal
function closeModalX() {
    closeSalespersonModal();
}

// Filter salesperson table based on search input
function filterSalespersonTable(searchTerm) {
    const rows = document.querySelectorAll('#salespersonDataTable tbody tr');
    searchTerm = searchTerm.toLowerCase();
    
    rows.forEach(row => {
        const code = row.getAttribute('data-person-code').toLowerCase();
        const name = row.getAttribute('data-person-name').toLowerCase();
        const teamId = row.getAttribute('data-person-team');
        const team = salesTeamNames[teamId].toLowerCase();
        const position = row.getAttribute('data-person-position').toLowerCase();
        
        // Show row if any field contains the search term
        if (code.includes(searchTerm) || 
            name.includes(searchTerm) || 
            team.includes(searchTerm) || 
            position.includes(searchTerm)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

// Open edit salesperson modal
function openEditSalespersonModal(row) {
    const modal = document.getElementById('editSalespersonModal');
    if (!modal) return;
    
    // Get data from row
    const code = row.getAttribute('data-person-code');
    const name = row.getAttribute('data-person-name');
    const team = row.getAttribute('data-person-team');
    const position = row.getAttribute('data-person-position');
    const userId = row.getAttribute('data-person-user-id');
    const isActive = row.getAttribute('data-person-is-active');
    
    // Populate form
    document.getElementById('edit_person_code').value = code;
    document.getElementById('edit_person_name').value = name;
    document.getElementById('edit_person_team').value = team;
    document.getElementById('edit_person_position').value = position;
    document.getElementById('edit_person_user_id').value = userId;
    document.getElementById('edit_person_is_active').value = isActive;
    
    // Show modal
    modal.classList.remove('hidden');
    
    // Focus first field
    document.getElementById('edit_person_code').focus();
}

// Close edit salesperson modal
function closeEditSalespersonModal() {
    const modal = document.getElementById('editSalespersonModal');
    if (modal) {
        modal.classList.add('hidden');
    }
}

// Save salesperson changes
function saveSalespersonChanges() {
    // Get form values
    const code = document.getElementById('edit_person_code').value;
    const name = document.getElementById('edit_person_name').value;
    const team = document.getElementById('edit_person_team').value;
    const position = document.getElementById('edit_person_position').value;
    const userId = document.getElementById('edit_person_user_id').value;
    const isActive = document.getElementById('edit_person_is_active').value;
    
    // Validation
    if (!code || !name) {
        alert('Code and Name are required fields');
        return;
    }
    
    // Show loading overlay
    showLoadingOverlay();
    
    // Simulate server request
    setTimeout(function() {
        // In a real app, you'd send this data to the server via AJAX
        console.log('Saving salesperson:', { code, name, team, position, userId, isActive });
        
        // Update row in the table (if it exists)
        const selectedRow = document.querySelector('#salespersonDataTable tbody tr.bg-blue-200');
        if (selectedRow) {
            selectedRow.setAttribute('data-person-code', code);
            selectedRow.setAttribute('data-person-name', name);
            selectedRow.setAttribute('data-person-team', team);
            selectedRow.setAttribute('data-person-position', position);
            selectedRow.setAttribute('data-person-user-id', userId);
            selectedRow.setAttribute('data-person-is-active', isActive);
            
            // Update visible cell text
            selectedRow.cells[0].textContent = code;
            selectedRow.cells[1].textContent = name;
            selectedRow.cells[2].textContent = salesTeamNames[team];
            selectedRow.cells[3].textContent = position;
        }
        
        // Hide loading overlay and close modal
        hideLoadingOverlay();
        closeEditSalespersonModal();
    }, 800);
    
    // Prevent form submission
    return false;
}

// Show loading overlay
function showLoadingOverlay() {
    const overlay = document.getElementById('loadingOverlay');
    if (overlay) {
        overlay.classList.remove('hidden');
    }
}

// Hide loading overlay
function hideLoadingOverlay() {
    const overlay = document.getElementById('loadingOverlay');
    if (overlay) {
        overlay.classList.add('hidden');
    }
}
