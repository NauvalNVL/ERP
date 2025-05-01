// Paperflute.js - Functions for paper flute management

// Function to populate flute table with data from database
function populateFluteTable() {
    console.log('Populating flute table');
    
    // Remove "no data" message if present
    const tbody = document.querySelector('#fluteDataTable tbody');
    tbody.innerHTML = '';
    
    // Show loading message
    tbody.innerHTML = '<tr><td colspan="5" class="px-4 py-4 text-center text-gray-500">Loading data...</td></tr>';
    
    // Fetch data from the database
    fetch('/paper-flute', {
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
                tbody.innerHTML = '<tr><td colspan="5" class="px-4 py-4 text-center text-gray-500">Tidak ada data paper flute yang tersedia.</td></tr>';
                return;
            }

            // Fill table with data from database
            data.forEach(flute => {
                const row = document.createElement('tr');
                row.className = 'hover:bg-blue-50 cursor-pointer';
                row.setAttribute('data-flute-id', flute.code);
                row.setAttribute('data-flute-name', flute.name);
                row.setAttribute('data-description', flute.description);
                row.setAttribute('data-flute-height', flute.flute_height);
                row.setAttribute('data-is-active', flute.is_active);
                row.onclick = function(e) { selectRow(this); e.stopPropagation(); };
                row.ondblclick = function() { openEditFluteModal(this); };
                
                // Create columns for each cell
                const idCell = document.createElement('td');
                idCell.className = 'px-4 py-3 whitespace-nowrap font-medium text-gray-900';
                idCell.textContent = flute.code;
                
                const nameCell = document.createElement('td');
                nameCell.className = 'px-4 py-3 whitespace-nowrap text-gray-700';
                nameCell.textContent = flute.name;
                
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
                    <p class="text-sm font-medium text-green-800">Data tersedia: ${data.length} paper flute ditemukan.</p>
                `;
            }
        })
        .catch(error => {
            console.error('Error fetching database data:', error);
            tbody.innerHTML = `<tr><td colspan="5" class="px-4 py-4 text-center text-red-500">
                Error loading data from database. ${error.message}
                <br>
                <button onclick="populateFluteTable()" class="mt-2 px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                    <i class="fas fa-sync-alt mr-2"></i>Coba Lagi
                </button>
            </td></tr>`;
        });
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

// Function to edit selected row
function editSelectedRow() {
    var selectedRow = document.querySelector('#fluteDataTable tbody tr.bg-blue-600');
    if (!selectedRow) {
        console.log("No row selected");
        alert("Silahkan pilih paper flute terlebih dahulu");
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

    // Update the main code input field with the selected row's code
    const code = row.getAttribute('data-flute-id');
    document.getElementById('code').value = code;
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
    editModal.classList.remove('hidden');
    
    console.log('Edit modal opened');
}

// Function to close edit modal
function closeEditFluteModal() {
    console.log('Closing edit flute modal');
    const editModal = document.getElementById('editFluteModal');
    editModal.classList.add('hidden');
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
    modal.classList.remove('hidden');
    
    // Populate table with data if needed
    populateFluteTable();
    
    // Sort by Code by default
    sortTableDirectly(0);
    
    console.log('Modal opened successfully');
}

// Function to close modal
function closeFluteModal() {
    var modal = document.getElementById('fluteTableWindow');
    if (modal) {
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
    
    // Get form elements
    const editFluteId = document.getElementById('edit_flute_id');
    const editFluteName = document.getElementById('edit_flute_name');
    const editDescription = document.getElementById('edit_description');
    const editFluteHeight = document.getElementById('edit_flute_height');
    const editIsActive = document.getElementById('edit_is_active');
    
    // Check if all required elements exist
    if (!editFluteId || !editFluteName || !editDescription || !editFluteHeight || !editIsActive) {
        console.error('Required form elements not found');
        alert('Error: Form elements not found. Please refresh the page and try again.');
        return;
    }
    
    const code = editFluteId.value;
    const name = editFluteName.value;
    const description = editDescription.value;
    const fluteHeight = editFluteHeight.value;
    const isActive = editIsActive.checked;
    
    console.log('Form data to save:', { code, name, description, fluteHeight, isActive });
    
    // Validate input
    if (!name) {
        alert('Nama paper flute tidak boleh kosong');
        return;
    }
    
    // Get save button and loading overlay
    const saveButton = document.querySelector('#editFluteForm button[type="submit"]');
    const loadingOverlay = document.getElementById('loadingOverlay');
    
    if (!saveButton || !loadingOverlay) {
        console.error('Required UI elements not found');
        alert('Error: UI elements not found. Please refresh the page and try again.');
        return;
    }
    
    // Display loading indicator on button and overlay
    const originalText = saveButton.innerHTML;
    saveButton.innerHTML = '<i class="fas fa-circle-notch fa-spin mr-2"></i>Menyimpan...';
    saveButton.disabled = true;
    
    // Show loading overlay
    loadingOverlay.classList.remove('hidden');
    
    // Get CSRF token
    const csrfToken = document.querySelector('meta[name="csrf-token"]');
    if (!csrfToken) {
        console.error('CSRF token not found');
        alert('Error: Security token not found. Please refresh the page and try again.');
        return;
    }
    
    // Send update to server
    fetch('/paper-flute/' + code, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken.content,
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            code: code,
            name: name,
            description: description,
            flute_height: fluteHeight,
            is_active: isActive,
            tur_l2b: 1.00,
            tur_l3: 1.40,
            tur_l1: 1.00,
            tur_ace: 1.50,
            tur_2l: 1.00,
            starch_consumption: 0.00
        })
    })
    .then(response => {
        if (!response.ok) {
            return response.json().then(data => {
                throw new Error(data.message || 'Error updating paper flute');
            });
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            console.log('Paper flute updated successfully:', data);
            
            // Update row in table
            const row = document.querySelector(`#fluteDataTable tbody tr[data-flute-id="${code}"]`);
            if (row) {
                const cells = row.cells;
                if (cells.length >= 5) {
                    cells[1].textContent = name;
                    cells[2].textContent = description;
                    cells[3].textContent = fluteHeight;
                    
                    // Update status badge
                    const statusBadge = cells[4].querySelector('span');
                    if (statusBadge) {
                        statusBadge.className = isActive 
                            ? 'px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800' 
                            : 'px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800';
                        statusBadge.textContent = isActive ? 'Active' : 'Inactive';
                    }
                    
                    // Update data attributes
                    row.setAttribute('data-flute-name', name);
                    row.setAttribute('data-description', description);
                    row.setAttribute('data-flute-height', fluteHeight);
                    row.setAttribute('data-is-active', isActive);
                }
            }
            
            // Update the main code input field
            const mainCodeInput = document.getElementById('code');
            if (mainCodeInput) {
                mainCodeInput.value = code;
            }
            
            // Close the edit modal
            closeEditFluteModal();
            
            // Refresh the table data
            populateFluteTable();
            
            alert('Data paper flute berhasil diperbarui');
        } else {
            throw new Error(data.message || 'Error updating paper flute');
        }
    })
    .catch(error => {
        console.error('Error updating paper flute:', error);
        alert('Error updating paper flute: ' + error.message);
    })
    .finally(() => {
        // Reset button state and hide loading overlay
        if (saveButton) {
            saveButton.innerHTML = originalText;
            saveButton.disabled = false;
        }
        if (loadingOverlay) {
            loadingOverlay.classList.add('hidden');
        }
    });
}

// Function to load seed data
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
    
    // Fetch data from the database
    fetch('/paper-flute')
        .then(response => response.json())
        .then(data => {
            // Fill table with data from database
            data.forEach(flute => {
                const row = document.createElement('tr');
                row.className = 'hover:bg-blue-50 cursor-pointer';
                row.setAttribute('data-flute-id', flute.code);
                row.setAttribute('data-flute-name', flute.name);
                row.setAttribute('data-description', flute.description);
                row.setAttribute('data-flute-height', flute.flute_height);
                row.setAttribute('data-is-active', flute.is_active);
                row.onclick = function(e) { selectRow(this); e.stopPropagation(); };
                row.ondblclick = function() { openEditFluteModal(this); };
                
                // Create cells
                const idCell = document.createElement('td');
                idCell.className = 'px-4 py-3 whitespace-nowrap font-medium text-gray-900';
                idCell.textContent = flute.code;
                
                const nameCell = document.createElement('td');
                nameCell.className = 'px-4 py-3 whitespace-nowrap text-gray-700';
                nameCell.textContent = flute.name;
                
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
                
                // Add cells to row
                row.appendChild(idCell);
                row.appendChild(nameCell);
                row.appendChild(descriptionCell);
                row.appendChild(heightCell);
                row.appendChild(statusCell);
                
                // Add row to table
                tbody.appendChild(row);
            });
            
            // Show notification
            alert('Data paper flute berhasil dimuat dari database');
            
            // Hide loading overlay
            if (loadingOverlay) {
                loadingOverlay.classList.add('hidden');
            }
            
            // Open modal to display data
            openFluteModal();
        })
        .catch(error => {
            console.error('Error loading data:', error);
            tbody.innerHTML = '<tr><td colspan="5" class="px-4 py-4 text-center text-gray-500">Error loading data from database.</td></tr>';
            
            // Hide loading overlay
            if (loadingOverlay) {
                loadingOverlay.classList.add('hidden');
            }
            
            alert('Error loading data from database. Please try again.');
        });
}

// Initialize event handlers when document loads
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM content loaded for paper flute');
    
    // Setup row events initially
    setupTableRowEvents();
    
    // Initialize flute table if show button exists
    const showBtn = document.getElementById('showFluteTableBtn');
    if (showBtn) {
        showBtn.addEventListener('click', openFluteModal);
    }
    
    // Setup load data button if it exists
    const loadDataBtn = document.getElementById('loadDataJsBtn');
    if (loadDataBtn) {
        loadDataBtn.addEventListener('click', loadSeedData);
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
});

