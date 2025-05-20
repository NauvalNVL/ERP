// Salesperson Management JavaScript
// This file provides functionality for the salesperson management page

document.addEventListener('DOMContentLoaded', function() {
    console.log('Salesperson JS loaded');
    setupTableRowEvents();
    populateSalespersonTable();
});

function populateSalespersonTable() {
    console.log('Populating salesperson table');
    
    // Remove "no data" message if present
    const tbody = document.querySelector('#salespersonDataTable tbody');
    tbody.innerHTML = '';
    
    // Show loading message
    tbody.innerHTML = '<tr><td colspan="4" class="px-4 py-4 text-center text-gray-500">Loading data...</td></tr>';
    
    // Show loading overlay
    showLoadingOverlay();
    
    // Fetch data from the database
    fetch('/salesperson', {
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        tbody.innerHTML = '';
        
        if (data.length === 0) {
            tbody.innerHTML = '<tr><td colspan="4" class="px-4 py-4 text-center text-gray-500">Tidak ada data salesperson yang tersedia.</td></tr>';
            return;
        }
        
        data.forEach(person => {
            const tr = document.createElement('tr');
            tr.className = 'hover:bg-blue-50 cursor-pointer';
            tr.setAttribute('data-person-code', person.code);
            tr.setAttribute('data-person-name', person.name);
            tr.setAttribute('data-person-team', person.sales_team_id);
            tr.setAttribute('data-person-position', person.position);
            tr.setAttribute('data-person-user-id', person.user_id);
            tr.setAttribute('data-person-is-active', person.is_active ? '1' : '0');
            
            tr.onclick = function(event) {
                selectRow(this);
                event.stopPropagation();
            };
            
            tr.ondblclick = function() {
                openEditSalespersonModal(this);
            };
            
            tr.innerHTML = `
                <td class="px-4 py-3 whitespace-nowrap font-medium text-gray-900">${person.code}</td>
                <td class="px-4 py-3 whitespace-nowrap text-gray-700">${person.name}</td>
                <td class="px-4 py-3 whitespace-nowrap text-gray-700">${getTeamName(person.sales_team_id)}</td>
                <td class="px-4 py-3 whitespace-nowrap text-gray-700">${person.position}</td>
            `;
            
            tbody.appendChild(tr);
        });
    })
    .catch(error => {
        console.error('Error fetching salesperson data:', error);
        tbody.innerHTML = '<tr><td colspan="4" class="px-4 py-4 text-center text-red-500">Error loading data. Please try again.</td></tr>';
    })
    .finally(() => {
        hideLoadingOverlay();
    });
}

function sortTableDirectly(columnIndex) {
    const table = document.getElementById('salespersonDataTable');
    const tbody = table.querySelector('tbody');
    const rows = Array.from(tbody.querySelectorAll('tr'));
    
    // Remove existing sort indicators
    table.querySelectorAll('th i.fas').forEach(icon => {
        icon.remove();
    });
    
    // Get current sort direction
    const currentDir = tbody.getAttribute('data-sort-dir') === 'asc' ? 'desc' : 'asc';
    tbody.setAttribute('data-sort-dir', currentDir);
    
    // Add sort indicator to header
    const th = table.querySelectorAll('th')[columnIndex];
    const icon = document.createElement('i');
    icon.className = `fas fa-sort-${currentDir === 'asc' ? 'up' : 'down'} ml-1`;
    th.appendChild(icon);
    
    // Sort rows
    rows.sort((a, b) => {
        const aValue = a.cells[columnIndex].textContent.trim();
        const bValue = b.cells[columnIndex].textContent.trim();
        
        if (currentDir === 'asc') {
            return aValue.localeCompare(bValue);
        } else {
            return bValue.localeCompare(aValue);
        }
    });
    
    // Clear and re-append rows
    tbody.innerHTML = '';
    rows.forEach(row => tbody.appendChild(row));
}

function selectRow(row) {
    // Remove selection from other rows
    document.querySelectorAll('#salespersonDataTable tbody tr').forEach(tr => {
        tr.classList.remove('bg-blue-100');
    });
    
    // Add selection to clicked row
    row.classList.add('bg-blue-100');
    
    // Update code input field
    document.getElementById('code').value = row.getAttribute('data-person-code');
}

function editSelectedRow() {
    const selectedRow = document.querySelector('#salespersonDataTable tbody tr.bg-blue-100');
    if (selectedRow) {
        openEditSalespersonModal(selectedRow);
        closeSalespersonModal();
    } else {
        // Show error message if no row is selected
        const errorDiv = document.createElement('div');
        errorDiv.className = 'fixed top-4 right-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded z-50';
        errorDiv.innerHTML = `
            <strong class="font-bold">Error!</strong>
            <span class="block sm:inline">Please select a row first</span>
        `;
        document.body.appendChild(errorDiv);
        
        // Remove error message after 3 seconds
        setTimeout(() => {
            errorDiv.remove();
        }, 3000);
    }
}

function getTeamName(teamId) {
    const teams = {
        1: 'MBI',
        2: 'MANAGEMENT LOCAL',
        3: 'MANAGEMENT MNC'
    };
    return teams[teamId] || '';
}

function setupTableRowEvents() {
    const searchInput = document.getElementById('searchSalespersonInput');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            filterSalespersonTable(this.value);
        });
    }
}

function openSalespersonModal() {
    const modal = document.getElementById('salespersonTableWindow');
    if (modal) {
        modal.classList.remove('hidden');
        
        // Populate table if empty
        const tbody = document.querySelector('#salespersonDataTable tbody');
        if (!tbody.children.length || tbody.children[0].cells[0].textContent.includes('Loading')) {
            populateSalespersonTable();
        }
        
        // Clear search input
        const searchInput = document.getElementById('searchSalespersonInput');
        if (searchInput) {
            searchInput.value = '';
            filterSalespersonTable('');
        }
    }
}

function closeSalespersonModal() {
    const modal = document.getElementById('salespersonTableWindow');
    if (modal) {
        modal.classList.add('hidden');
    }
}

function filterSalespersonTable(searchTerm) {
    const rows = document.querySelectorAll('#salespersonDataTable tbody tr');
    const searchLower = searchTerm.toLowerCase();
    
    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(searchLower) ? '' : 'none';
    });
}

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
    
    // Set form values
    document.getElementById('edit_person_code').value = code;
    document.getElementById('edit_person_name').value = name;
    document.getElementById('edit_person_team').value = team;
    document.getElementById('edit_person_position').value = position;
    document.getElementById('edit_person_user_id').value = userId;
    document.getElementById('edit_person_is_active').value = isActive;
    
    // Show modal
    modal.classList.remove('hidden');
}

function closeEditSalespersonModal() {
    const modal = document.getElementById('editSalespersonModal');
    if (modal) {
        modal.classList.add('hidden');
    }
}

function saveSalespersonChanges() {
    // Get form values
    const code = document.getElementById('edit_person_code').value;
    const name = document.getElementById('edit_person_name').value;
    const team = document.getElementById('edit_person_team').value;
    const position = document.getElementById('edit_person_position').value;
    const userId = document.getElementById('edit_person_user_id').value;
    const isActive = document.getElementById('edit_person_is_active').value === '1';
    
    // Show loading overlay
    showLoadingOverlay();
    
    // Send update request
    fetch(`/salesperson/update/${code}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            name: name,
            sales_team_id: team,
            position: position,
            user_id: userId,
            is_active: isActive
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Close modal
            closeEditSalespersonModal();
            
            // Refresh table
            populateSalespersonTable();
            
            // Show success message
            const successDiv = document.createElement('div');
            successDiv.className = 'fixed top-4 right-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded z-50';
            successDiv.innerHTML = `
                <strong class="font-bold">Success!</strong>
                <span class="block sm:inline">${data.message}</span>
            `;
            document.body.appendChild(successDiv);
            
            // Remove success message after 3 seconds
            setTimeout(() => {
                successDiv.remove();
            }, 3000);
        } else {
            throw new Error(data.message);
        }
    })
    .catch(error => {
        console.error('Error updating salesperson:', error);
        
        // Show error message
        const errorDiv = document.createElement('div');
        errorDiv.className = 'fixed top-4 right-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded z-50';
        errorDiv.innerHTML = `
            <strong class="font-bold">Error!</strong>
            <span class="block sm:inline">${error.message}</span>
        `;
        document.body.appendChild(errorDiv);
        
        // Remove error message after 3 seconds
        setTimeout(() => {
            errorDiv.remove();
        }, 3000);
    })
    .finally(() => {
        hideLoadingOverlay();
    });
}

function showLoadingOverlay() {
    const overlay = document.getElementById('loadingOverlay');
    if (overlay) {
        overlay.classList.remove('hidden');
    }
}

function hideLoadingOverlay() {
    const overlay = document.getElementById('loadingOverlay');
    if (overlay) {
        overlay.classList.add('hidden');
    }
}

function loadSeedData() {
    showLoadingOverlay();
    
    fetch('/salesperson/seed', {
        method: 'POST',
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            populateSalespersonTable();
            
            // Show success message
            const successDiv = document.createElement('div');
            successDiv.className = 'fixed top-4 right-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded z-50';
            successDiv.innerHTML = `
                <strong class="font-bold">Success!</strong>
                <span class="block sm:inline">${data.message}</span>
            `;
            document.body.appendChild(successDiv);
            
            // Remove success message after 3 seconds
            setTimeout(() => {
                successDiv.remove();
            }, 3000);
        } else {
            throw new Error(data.message);
        }
    })
    .catch(error => {
        console.error('Error loading seed data:', error);
        
        // Show error message
        const errorDiv = document.createElement('div');
        errorDiv.className = 'fixed top-4 right-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded z-50';
        errorDiv.innerHTML = `
            <strong class="font-bold">Error!</strong>
            <span class="block sm:inline">${error.message}</span>
        `;
        document.body.appendChild(errorDiv);
        
        // Remove error message after 3 seconds
        setTimeout(() => {
            errorDiv.remove();
        }, 3000);
    })
    .finally(() => {
        hideLoadingOverlay();
    });
}
