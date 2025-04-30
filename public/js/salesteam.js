// SalesTeam.js - Functions for sales team management

// Function to populate sales team table with data from database
function populateSalesTeamTable() {
    console.log('Populating sales team table');
    
    // Remove "no data" message if present
    const tbody = document.querySelector('#salesTeamDataTable tbody');
    if (!tbody) {
        console.error('Table body element not found');
        return;
    }
    
    // Show loading message
    tbody.innerHTML = '<tr><td colspan="2" class="px-4 py-4 text-center text-gray-500">Loading data...</td></tr>';
    
    // Fetch data from the database
    fetch('/sales-team', {
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
            tbody.innerHTML = '<tr><td colspan="2" class="px-4 py-4 text-center text-gray-500">Tidak ada data sales team yang tersedia.</td></tr>';
            return;
        }

        // Fill table with data from database
        data.forEach(team => {
            const row = document.createElement('tr');
            row.className = 'hover:bg-blue-50 cursor-pointer';
            row.setAttribute('data-team-code', team.code);
            row.setAttribute('data-team-name', team.name);
            row.onclick = function(e) { selectRow(this); e.stopPropagation(); };
            row.ondblclick = function() { openEditSalesTeamModal(this); };
            
            // Create columns for each cell
            const codeCell = document.createElement('td');
            codeCell.className = 'px-4 py-3 whitespace-nowrap font-medium text-gray-900';
            codeCell.textContent = team.code;
            
            const nameCell = document.createElement('td');
            nameCell.className = 'px-4 py-3 whitespace-nowrap text-gray-700';
            nameCell.textContent = team.name;
            
            // Add all cells to row
            row.appendChild(codeCell);
            row.appendChild(nameCell);
            
            // Add row to table
            tbody.appendChild(row);
        });
        
        console.log('Table populated with database data:', data);
        
        // Setup event handlers for the table rows
        setupTableRowEvents();
        
        // Sort table by Code by default
        sortTableDirectly(0);
        
        // Update the data status message
        const dbStatusElement = document.querySelector('.bg-yellow-100, .bg-green-100');
        if (dbStatusElement) {
            dbStatusElement.className = 'mt-4 bg-green-100 p-3 rounded';
            dbStatusElement.innerHTML = `
                <p class="text-sm font-medium text-green-800">Data tersedia: ${data.length} sales team ditemukan.</p>
            `;
        }
    })
    .catch(error => {
        console.error('Error fetching database data:', error);
        if (tbody) {
            tbody.innerHTML = `<tr><td colspan="2" class="px-4 py-4 text-center text-red-500">
                Error loading data from database. ${error.message}
                <br>
                <button onclick="populateSalesTeamTable()" class="mt-2 px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                    <i class="fas fa-sync-alt mr-2"></i>Coba Lagi
                </button>
            </td></tr>`;
        }
    });
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
        
        // Regular text sort for all columns
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

// Function to edit selected row
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
    if (!row) return;
    
    // Remove highlight from all rows
    const allRows = document.querySelectorAll('#salesTeamDataTable tbody tr');
    allRows.forEach(function(r) {
        r.classList.remove('selected');
        r.classList.remove('bg-blue-600', 'text-white');
    });
    
    // Add highlighting to selected row
    row.classList.add('bg-blue-600', 'text-white');

    // Update the main code input field with the selected row's code
    const code = row.getAttribute('data-team-code');
    const codeInput = document.getElementById('code');
    if (codeInput) {
        codeInput.value = code;
    }
}

// Function to open edit modal
function openEditSalesTeamModal(row) {
    if (!row) {
        console.error('No row provided to edit modal');
        return;
    }
    
    console.log('Opening edit sales team modal for row:', row);
    
    const code = row.getAttribute('data-team-code');
    const name = row.getAttribute('data-team-name');
    
    console.log('Sales team data:', { code, name });
    
    const codeInput = document.getElementById('edit_team_code');
    const nameInput = document.getElementById('edit_team_name');
    const editModal = document.getElementById('editSalesTeamModal');
    
    if (!codeInput || !nameInput || !editModal) {
        console.error('Required modal elements not found');
        return;
    }
    
    codeInput.value = code;
    nameInput.value = name;
    
    editModal.classList.remove('hidden');
    
    console.log('Edit modal opened');
}

// Function to close edit modal
function closeEditSalesTeamModal() {
    console.log('Closing edit sales team modal');
    const editModal = document.getElementById('editSalesTeamModal');
    editModal.classList.add('hidden');
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
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    
    // Populate table with data if needed
    populateSalesTeamTable();
    
    // Sort by Code by default
    sortTableDirectly(0);
    
    console.log('Modal opened successfully');
}

// Function to close modal
function closeSalesTeamModal() {
    var modal = document.getElementById('salesTeamTableWindow');
    if (modal) {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
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
    
    const codeInput = document.getElementById('edit_team_code');
    const nameInput = document.getElementById('edit_team_name');
    
    if (!codeInput || !nameInput) {
        console.error('Form inputs not found');
        return;
    }
    
    const code = codeInput.value;
    const name = nameInput.value;
    
    console.log('Form data to save:', { code, name });
    
    // Validate input
    if (!name) {
        alert('Nama sales team tidak boleh kosong');
        return;
    }
    
    // Display loading indicator on button and overlay
    const saveButton = document.querySelector('#editSalesTeamForm button[type="submit"]');
    const loadingOverlay = document.getElementById('loadingOverlay');
    
    if (!saveButton || !loadingOverlay) {
        console.error('Required elements not found');
        return;
    }
    
    const originalText = saveButton.innerHTML;
    saveButton.innerHTML = '<i class="fas fa-circle-notch fa-spin mr-2"></i>Menyimpan...';
    saveButton.disabled = true;
    
    // Show loading overlay
    loadingOverlay.classList.remove('hidden');
    
    // Get CSRF token
    const csrfToken = document.querySelector('meta[name="csrf-token"]');
    if (!csrfToken) {
        console.error('CSRF token not found');
        return;
    }
    
    // Send update to server
    fetch('/sales-team/' + code, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': csrfToken.content,
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({
            code: code,
            name: name
        })
    })
    .then(response => {
        if (!response.ok) {
            return response.json().then(err => Promise.reject(err));
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            console.log('Sales team updated successfully:', data);
            
            // Update row in table
            const row = document.querySelector(`#salesTeamDataTable tbody tr[data-team-code="${code}"]`);
            if (row) {
                const cells = row.cells;
                if (cells.length >= 2) {
                    cells[0].textContent = code;
                    cells[1].textContent = name;
                    
                    row.setAttribute('data-team-code', code);
                    row.setAttribute('data-team-name', name);
                }
            }
            
            // Update the main code input field
            const mainCodeInput = document.getElementById('code');
            if (mainCodeInput) {
                mainCodeInput.value = code;
            }
            
            // Close the edit modal
            closeEditSalesTeamModal();
            
            // Refresh the table data
            populateSalesTeamTable();
            
            alert('Data sales team berhasil diperbarui');
        } else {
            throw new Error(data.message || 'Error updating sales team');
        }
    })
    .catch(error => {
        console.error('Error updating sales team:', error);
        alert(error.message || 'Error updating sales team. Please try again.');
    })
    .finally(() => {
        // Reset button state and hide loading overlay
        if (saveButton && loadingOverlay) {
            saveButton.innerHTML = originalText;
            saveButton.disabled = false;
            loadingOverlay.classList.add('hidden');
        }
    });
}

// Initialize event handlers when document loads
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM content loaded for sales team');
    
    // Setup row events initially
    setupTableRowEvents();
    
    // Initialize sales team table if show button exists
    const showBtn = document.getElementById('showSalesTeamTableBtn');
    if (showBtn) {
        showBtn.addEventListener('click', openSalesTeamModal);
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
        if (editModal && event.target === editModal) {
            closeEditSalesTeamModal();
        }
    };
});
