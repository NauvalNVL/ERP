// SalespersonTeam.js - Functions for salesperson team management

// Define seed data for salesperson teams
const seedSalespersonTeams = [
    { id: 1, salesperson_code: 'S101', salesperson_name: 'ABENG', team_code: 'MBI', team_name: 'MBI', position: 'M - Manager' },
    { id: 2, salesperson_code: 'S102', salesperson_name: 'AGUNG', team_code: 'MBI', team_name: 'MBI', position: 'E - Executive' },
    { id: 3, salesperson_code: 'S103', salesperson_name: 'EKO', team_code: 'LOC', team_name: 'MANAGEMENT LOCAL', position: 'T - Team Leader' },
    { id: 4, salesperson_code: 'S104', salesperson_name: 'ELIAS', team_code: 'LOC', team_name: 'MANAGEMENT LOCAL', position: 'M - Manager' },
    { id: 5, salesperson_code: 'S105', salesperson_name: 'FEBBY', team_code: 'MNC', team_name: 'MANAGEMENT MNC', position: 'E - Executive' },
    { id: 6, salesperson_code: 'S106', salesperson_name: 'HENGKY', team_code: 'MNC', team_name: 'MANAGEMENT MNC', position: 'T - Team Leader' }
];

// Initialize selected items array
let selectedItems = [];

// Function to populate salesperson team table with seed data
function populateSalespersonTeamTable() {
    console.log('Populating salesperson team table');
    
    // Check if table already has data
    const tbody = document.querySelector('#salespersonTable tbody');
    if (!tbody) {
        console.error('Table body not found');
        return;
    }
    
    const rows = tbody.querySelectorAll('tr');
    const isEmpty = rows.length === 0 || (rows.length === 1 && rows[0].querySelector('td[colspan]'));
    
    if (isEmpty) {
        console.log('Table is empty, populating with seed data');
        
        // Remove "no data" message if present
        tbody.innerHTML = '';
        
        // Fill table with data from seedSalespersonTeams
        seedSalespersonTeams.forEach(team => {
            const row = document.createElement('tr');
            row.className = 'hover:bg-gray-50';
            row.setAttribute('data-id', team.id);
            
            row.innerHTML = `
                <td class="px-3 py-2 whitespace-nowrap">
                    <input type="checkbox" name="selected[]" value="${team.id}" class="selectItem rounded text-blue-500 focus:ring-blue-500">
                </td>
                <td class="px-3 py-2 whitespace-nowrap font-medium">
                    ${team.salesperson_code}
                </td>
                <td class="px-3 py-2 whitespace-nowrap">
                    ${team.salesperson_name}
                </td>
                <td class="px-3 py-2 whitespace-nowrap font-medium">
                    ${team.team_code}
                </td>
                <td class="px-3 py-2 whitespace-nowrap">
                    ${team.team_name}
                </td>
                <td class="px-3 py-2 whitespace-nowrap">
                    ${team.position}
                </td>
            `;
            
            tbody.appendChild(row);
        });
        
        console.log('Table populated with seed data');
        
        // Setup event handlers for the table rows and checkboxes
        setupEventHandlers();
    } else {
        console.log('Table already has data, no need to populate');
    }
}

// Handler for checkbox "Select All"
function setupEventHandlers() {
    console.log('Setting up event handlers');
    
    const selectAllCheckbox = document.getElementById('selectAll');
    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.selectItem');
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
            updateButtonState();
        });
    }

    // Handler for checkbox item individual
    document.querySelectorAll('.selectItem').forEach(checkbox => {
        checkbox.addEventListener('change', updateButtonState);
    });
    
    // Setup search input
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.addEventListener('keyup', function(e) {
            if (e.key === 'Enter') {
                searchData();
            }
        });
    }
    
    // Initialize button states
    updateButtonState();
}

// Function to update button states based on selection
function updateButtonState() {
    selectedItems = Array.from(document.querySelectorAll('.selectItem:checked')).map(cb => cb.value);
    
    const deleteButton = document.getElementById('deleteButton');
    const editButton = document.getElementById('editButton');
    
    if (!deleteButton || !editButton) return;
    
    deleteButton.disabled = selectedItems.length === 0;
    editButton.disabled = selectedItems.length !== 1;
    
    if (selectedItems.length === 0) {
        deleteButton.classList.add('opacity-50', 'cursor-not-allowed');
        editButton.classList.add('opacity-50', 'cursor-not-allowed');
    } else {
        deleteButton.classList.remove('opacity-50', 'cursor-not-allowed');
        
        if (selectedItems.length === 1) {
            editButton.classList.remove('opacity-50', 'cursor-not-allowed');
        } else {
            editButton.classList.add('opacity-50', 'cursor-not-allowed');
        }
    }
}

// Function to show/hide add modal
function showAddModal() {
    document.getElementById('addModal').classList.remove('hidden');
}

function hideAddModal() {
    document.getElementById('addModal').classList.add('hidden');
    document.getElementById('salesperson_code').value = '';
    document.getElementById('salesperson_name').value = '';
    document.getElementById('sales_team_id').selectedIndex = 0;
    document.getElementById('position').selectedIndex = 0;
}

// Function to show/hide edit modal
function showEditModal() {
    if (selectedItems.length !== 1) return;
    
    const id = selectedItems[0];
    
    // Get data from the selected row
    const selectedRow = document.querySelector(`.selectItem[value="${id}"]`).closest('tr');
    const cells = selectedRow.querySelectorAll('td');
    
    document.getElementById('edit_salesperson_code').value = cells[1].textContent.trim();
    document.getElementById('edit_salesperson_name').value = cells[2].textContent.trim();
    
    // Set sales team and position values based on displayed data
    const teamCode = cells[3].textContent.trim();
    const position = cells[5].textContent.trim();
    
    // Find the correct option in the select dropdown
    const teamOptions = document.getElementById('edit_sales_team_id').options;
    for (let i = 0; i < teamOptions.length; i++) {
        if (teamOptions[i].text.startsWith(teamCode)) {
            document.getElementById('edit_sales_team_id').selectedIndex = i;
            break;
        }
    }
    
    // Set position
    const positionOptions = document.getElementById('edit_position').options;
    for (let i = 0; i < positionOptions.length; i++) {
        if (positionOptions[i].value.includes(position.split(' ')[0])) {
            document.getElementById('edit_position').selectedIndex = i;
            break;
        }
    }
    
    // Set the form action URL
    document.getElementById('editForm').action = `/salesperson-team/${id}`;
    
    document.getElementById('editModal').classList.remove('hidden');
}

function hideEditModal() {
    document.getElementById('editModal').classList.add('hidden');
}

// Function to show/hide delete modal
function showDeleteModal() {
    if (selectedItems.length === 0) return;
    
    // Set the form action URL for the first selected item
    document.getElementById('deleteForm').action = `/salesperson-team/${selectedItems[0]}`;
    
    document.getElementById('deleteModal').classList.remove('hidden');
}

function hideDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
}

// Function for searching
function searchData() {
    const searchValue = document.getElementById('searchInput').value.toLowerCase();
    const rows = document.querySelectorAll('#salespersonTable tbody tr');
    
    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        if (text.includes(searchValue)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

// Function to load seed data for demo purposes
function loadSeedData() {
    // Show loading overlay if it exists
    const loadingOverlay = document.getElementById('loadingOverlay');
    if (loadingOverlay) {
        loadingOverlay.classList.remove('hidden');
    }
    
    // Simulate network request
    setTimeout(() => {
        populateSalespersonTeamTable();
        
        // Update status notification if it exists
        const statusElement = document.querySelector('.alert-danger');
        if (statusElement) {
            statusElement.classList.remove('bg-yellow-100', 'alert-danger');
            statusElement.classList.add('bg-green-100', 'alert-success');
            statusElement.innerHTML = `
                <p class="text-sm font-medium text-green-800">Data loaded successfully! ${seedSalespersonTeams.length} salesperson teams available.</p>
            `;
        }
        
        // Hide loading overlay
        if (loadingOverlay) {
            loadingOverlay.classList.add('hidden');
        }
    }, 1000);
}

// Initialize when document loads
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM content loaded for Salesperson Team');
    
    // Setup initial event handlers
    setupEventHandlers();
    
    // Setup load data button if it exists
    const loadDataBtn = document.getElementById('loadDataJsBtn');
    if (loadDataBtn) {
        loadDataBtn.addEventListener('click', loadSeedData);
    }
    
    // Auto-hide alerts after 5 seconds
    setTimeout(() => {
        const successAlert = document.getElementById('successAlert');
        const errorAlert = document.getElementById('errorAlert');
        
        if (successAlert) successAlert.style.display = 'none';
        if (errorAlert) errorAlert.style.display = 'none';
    }, 5000);
});
