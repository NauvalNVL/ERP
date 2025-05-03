// Color.js - Functions for color management

let currentlySelectedColorRow = null; // Variabel untuk menyimpan baris terpilih

// Function to populate color table
function populateColorTable() {
    console.log('Populating color table');
    
    // Get the table body
    const tbody = document.querySelector('#colorDataTable tbody');
    if (!tbody) {
        console.error('Color table body not found');
        return;
    }
    
    // Remove "no data" message if present
    tbody.innerHTML = '';
    
    // Show loading message
    tbody.innerHTML = '<tr><td colspan="6" class="px-4 py-4 text-center text-gray-500">Loading data...</td></tr>';
    
    // Fetch data from the database
    fetch('/color', {
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
                tbody.innerHTML = '<tr><td colspan="6" class="px-4 py-4 text-center text-gray-500">Tidak ada data warna yang tersedia.</td></tr>';
                return;
            }
            
            // Fill table with data from database
            data.forEach(color => {
                const row = document.createElement('tr');
                row.className = 'hover:bg-blue-50 cursor-pointer';
                row.setAttribute('data-color-id', color.color_id);
                row.setAttribute('data-color-name', color.color_name);
                row.setAttribute('data-origin', color.origin);
                row.setAttribute('data-cg-id', color.color_group_id);
                row.setAttribute('data-cg-type', color.cg_type || 'X-Flexo');
                
                // --- Tambahkan Listener Langsung Di Sini ---
                row.onclick = function(e) { 
                    selectColorRow(this); 
                    e.stopPropagation(); 
                };
                row.ondblclick = function() { 
                    openEditColorModal(this); 
                };
                console.log('Attached listeners to row for color:', color.color_id);
                // --- Akhir Penambahan Listener ---
                
                // Create columns for each cell
                const idCell = document.createElement('td');
                idCell.className = 'px-4 py-3 whitespace-nowrap font-medium text-gray-900';
                idCell.textContent = color.color_id;
                
                const nameCell = document.createElement('td');
                nameCell.className = 'px-4 py-3 whitespace-nowrap text-gray-700';
                nameCell.textContent = color.color_name;
                
                const originCell = document.createElement('td');
                originCell.className = 'px-4 py-3 whitespace-nowrap text-gray-700';
                originCell.textContent = color.origin;
                
                const cgIdCell = document.createElement('td');
                cgIdCell.className = 'px-4 py-3 whitespace-nowrap';
                cgIdCell.innerHTML = `<span class="px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">${color.color_group_id}</span>`;
                
                const cgNameCell = document.createElement('td');
                cgNameCell.className = 'px-4 py-3 whitespace-nowrap text-gray-700';
                cgNameCell.textContent = color.color_group ? color.color_group.cg_name : '';
                
                const cgTypeCell = document.createElement('td');
                cgTypeCell.className = 'px-4 py-3 whitespace-nowrap text-gray-700';
                cgTypeCell.textContent = color.cg_type || 'X-Flexo';
                
                // Add all cells to row
                row.appendChild(idCell);
                row.appendChild(nameCell);
                row.appendChild(originCell);
                row.appendChild(cgIdCell);
                row.appendChild(cgNameCell);
                row.appendChild(cgTypeCell);
                
                // Add row to table
                tbody.appendChild(row);
            });
            
            console.log('Table populated with database data:', data);
            
            // Apply blue highlighting to rows
            highlightBlueRows();
            
            // Sort table by Color# by default
            sortTableDirectly(0);
            
            // Update the data status message
            const dbStatusElement = document.querySelector('.bg-yellow-100, .bg-green-100');
            if (dbStatusElement) {
                dbStatusElement.className = 'mt-4 bg-green-100 p-3 rounded';
                dbStatusElement.innerHTML = `
                    <p class="text-sm font-medium text-green-800">Data tersedia: ${data.length} warna ditemukan.</p>
                `;
            }
        })
        .catch(error => {
            console.error('Error fetching database data:', error);
            tbody.innerHTML = `<tr><td colspan="6" class="px-4 py-4 text-center text-red-500">
                Error loading data from database. ${error.message}
                <br>
                <button onclick="populateColorTable()" class="mt-2 px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                    <i class="fas fa-sync-alt mr-2"></i>Coba Lagi
                </button>
            </td></tr>`;
        });
}

// Function to sort table
function sortTableDirectly(columnIndex) {
    console.log("Sorting by column: " + columnIndex);
    
    var table = document.getElementById('colorDataTable');
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
    
    // Highlight color rows based on color group
    highlightBlueRows();
    
    console.log("Sorting complete");
}

// Function to sort by Color#
function sortByColorId() {
    console.log("Sorting by Color#");
    var table = document.getElementById('colorDataTable');
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
    
    // Sort rows based on color ID (column 0)
    rows.sort(function(a, b) {
        var idA = a.cells[0].textContent.trim();
        var idB = b.cells[0].textContent.trim();
        return idA.localeCompare(idB, undefined, { numeric: true });
    });
    
    // Remove all rows from table
    while (tbody.firstChild) {
        tbody.removeChild(tbody.firstChild);
    }
    
    // Add sorted rows to table
    rows.forEach(function(row) {
        tbody.appendChild(row);
    });
    
    // Update button styles
    updateSortButtonStyles('colorId');
    
    // Highlight color rows based on color group
    highlightBlueRows();
}

// Function to sort by CG# + Color#
function sortByCGAndColor() {
    console.log("Sorting by CG# + Color#");
    var table = document.getElementById('colorDataTable');
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
    
    // Sort rows based on CG# (column 3) and then Color# (column 0)
    rows.sort(function(a, b) {
        var cgA = a.cells[3].textContent.trim();
        var cgB = b.cells[3].textContent.trim();
        
        if (cgA !== cgB) {
            return cgA.localeCompare(cgB, undefined, { numeric: true });
        }
        
        var idA = a.cells[0].textContent.trim();
        var idB = b.cells[0].textContent.trim();
        return idA.localeCompare(idB, undefined, { numeric: true });
    });
    
    // Remove all rows from table
    while (tbody.firstChild) {
        tbody.removeChild(tbody.firstChild);
    }
    
    // Add sorted rows to table
    rows.forEach(function(row) {
        tbody.appendChild(row);
    });
    
    // Update button styles
    updateSortButtonStyles('cgAndColor');
    
    // Highlight color rows based on color group
    highlightBlueRows();
}

// Function to sort by CG Type + Color#
function sortByCGTypeAndColor() {
    console.log("Sorting by CG Type + Color#");
    var table = document.getElementById('colorDataTable');
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
    
    // Sort rows based on CG Type (column 5) and then Color# (column 0)
    rows.sort(function(a, b) {
        var typeA = a.cells[5].textContent.trim();
        var typeB = b.cells[5].textContent.trim();
        
        if (typeA !== typeB) {
            return typeA.localeCompare(typeB);
        }
        
        var idA = a.cells[0].textContent.trim();
        var idB = b.cells[0].textContent.trim();
        return idA.localeCompare(idB, undefined, { numeric: true });
    });
    
    // Remove all rows from table
    while (tbody.firstChild) {
        tbody.removeChild(tbody.firstChild);
    }
    
    // Add sorted rows to table
    rows.forEach(function(row) {
        tbody.appendChild(row);
    });
    
    // Update button styles
    updateSortButtonStyles('cgTypeAndColor');
    
    // Highlight color rows based on color group
    highlightBlueRows();
}

// Function to update sort button styles
function updateSortButtonStyles(activeSort) {
    // Reset all sort buttons
    const sortButtons = document.querySelectorAll('button[onclick^="sort"]');
    sortButtons.forEach(button => {
        button.classList.remove('bg-blue-500', 'text-white');
        button.classList.add('bg-gray-100', 'text-gray-800');
    });
    
    // Highlight active sort button
    let activeButton;
    switch(activeSort) {
        case 'colorId':
            activeButton = document.querySelector('button[onclick="sortByColorId()"]');
            break;
        case 'cgAndColor':
            activeButton = document.querySelector('button[onclick="sortByCGAndColor()"]');
            break;
        case 'cgTypeAndColor':
            activeButton = document.querySelector('button[onclick="sortByCGTypeAndColor()"]');
            break;
    }
    
    if (activeButton) {
        activeButton.classList.remove('bg-gray-100', 'text-gray-800');
        activeButton.classList.add('bg-blue-500', 'text-white');
    }
}

// Function to edit selected row (Edit button)
function editSelectedColorRow() {
    var selectedRow = document.querySelector('#colorDataTable tbody tr.bg-blue-600');
    if (!selectedRow) {
        console.log("No row selected");
        alert("Silahkan pilih warna terlebih dahulu");
        return;
    }
    
    console.log("Selected row for editing:", selectedRow);
    
    // Open edit modal for selected row
    openEditColorModal(selectedRow);
}

// Function to select a row
function selectColorRow(row) {
    console.log('selectColorRow called for:', row);
    // Remove highlight from previously selected row if exists
    if (currentlySelectedColorRow) {
        currentlySelectedColorRow.classList.remove('bg-blue-600', 'text-white');
        currentlySelectedColorRow.classList.remove('selected'); // Pastikan kelas 'selected' juga dihapus jika digunakan
    }

    // Add highlighting to the newly selected row
    row.classList.add('bg-blue-600', 'text-white');

    // Update the reference to the currently selected row
    currentlySelectedColorRow = row;
    console.log('currentlySelectedColorRow is now:', currentlySelectedColorRow);
}

// Function to open edit modal
function openEditColorModal(row) {
    console.log('openEditColorModal called with row:', row);
    console.log('Opening edit color modal for row:', row);
    
    const colorId = row.getAttribute('data-color-id');
    const colorName = row.getAttribute('data-color-name');
    const origin = row.getAttribute('data-origin');
    const cgId = row.getAttribute('data-cg-id');
    
    console.log('Color data:', { colorId, colorName, origin, cgId });
    
    document.getElementById('edit_color_id').value = colorId;
    document.getElementById('edit_color_name').value = colorName;
    document.getElementById('edit_color_origin').value = origin;
    document.getElementById('edit_color_group').value = cgId;
    document.getElementById('edit_cg_type').value = "X-Flexo"; // Always set to X-Flexo
    
    const editModal = document.getElementById('editColorModal');
    editModal.style.display = 'block';
    editModal.classList.remove('hidden');
    
    console.log('Edit modal opened');
}

// Function to close edit modal
function closeEditColorModal() {
    console.log('Closing edit color modal');
    const editModal = document.getElementById('editColorModal');
    editModal.classList.add('hidden');
    editModal.style.display = 'none';
}

// Function to open color modal
function openColorModal() {
    console.log('Opening color modal');
    var modal = document.getElementById('colorTableWindow');
    if (!modal) {
        console.error('Modal element not found');
        return;
    }
    
    // Show modal
    modal.style.display = 'block';
    modal.classList.remove('hidden');
    
    // Populate table with data from database
    populateColorTable();
    
    console.log('Modal opened successfully');
}

// Function to close modal
function closeColorModal() {
    var modal = document.getElementById('colorTableWindow');
    if (modal) {
        modal.style.display = 'none';
        modal.classList.add('hidden');
    }
}

function closeModalX() {
    closeColorModal();
}

// Function to apply blue color to certain rows
function highlightBlueRows() {
    // Color rows based on the pattern shown in the image
    var rows = document.querySelectorAll('#colorDataTable tbody tr');
    rows.forEach(function(row) {
        // Skip the "no data" row if present
        if (row.querySelector('td[colspan="6"]')) return;
        
        // Clear any existing color classes
        row.classList.remove('blue-row');
        row.classList.remove('bg-blue-100');
        
        // Get the color group ID from the row
        var cgId = row.getAttribute('data-cg-id');
        
        // Apply blue-row class based on the colorGroup patterns
        // Using the pattern from the image (blue for groups 01, 04, 06, 08)
        if (cgId === '01' || cgId === '04' || cgId === '06' || cgId === '08') {
            row.classList.add('bg-blue-100');
        }
    });
}

// Mapping of Color Group names based on ID
function getCGName(cgId) {
    const colorGroup = seedColorGroups.find(cg => cg.cg === cgId);
    return colorGroup ? colorGroup.cg_name : 'Unknown';
}

// Function to save color changes
function saveColorChanges() {
    console.log('Saving color changes');
    
    const colorId = document.getElementById('edit_color_id').value;
    const colorName = document.getElementById('edit_color_name').value;
    const origin = document.getElementById('edit_color_origin').value;
    const colorGroup = document.getElementById('edit_color_group').value;
    const cgType = "X-Flexo"; // Always use X-Flexo regardless of what's in the field
    const kgPerM2 = document.getElementById('edit_kg_per_m2').value || '1.0000';
    
    console.log('Form data to save:', { colorId, colorName, origin, colorGroup, cgType, kgPerM2 });
    
    // Validate input
    if (!colorName) {
        alert('Nama warna tidak boleh kosong');
        return;
    }
    
    // Display loading indicator on button and overlay
    const saveButton = document.querySelector('#editColorForm button[type="submit"]');
    const originalText = saveButton.innerHTML;
    saveButton.innerHTML = '<i class="fas fa-circle-notch fa-spin mr-2"></i>Menyimpan...';
    saveButton.disabled = true;
    
    // Show loading overlay
    document.getElementById('loadingOverlay').classList.remove('hidden');
    
    // Prepare form data
    const formData = new FormData();
    formData.append('color_name', colorName);
    formData.append('origin', origin);
    formData.append('color_group_id', colorGroup);
    formData.append('cg_type', cgType);
    formData.append('_method', 'PUT'); // For Laravel method spoofing
    
    // Send data to server
    fetch(`/color/${colorId}`, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        console.log('Response status:', response.status);
        return response.json().then(data => {
            if (!response.ok) {
                throw new Error(data.message || 'Network response was not ok');
            }
            return data;
        });
    })
    .then(data => {
        console.log('Server response:', data);
        
        if (!data.success) {
            throw new Error(data.message || 'Unknown error occurred');
        }
        
        // Update row in table
        const row = document.querySelector(`#colorDataTable tbody tr[data-color-id="${colorId}"]`);
        if (row) {
            console.log('Found row to update:', row);
            
            try {
                // Direct DOM manipulation for cell updates
                const cells = row.cells;
                console.log('Row has cells:', cells.length);
                
                if (cells.length >= 6) {
                    // Update cell text directly
                    cells[1].textContent = colorName;
                    cells[2].textContent = origin;
                    cells[3].innerHTML = `<span class="px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">${colorGroup}</span>`;
                    cells[5].textContent = cgType;
                    
                    // Get the color group name from JS function
                    const cgName = getCGName(colorGroup);
                    cells[4].textContent = cgName;
                    
                    // Update data attributes
                    row.setAttribute('data-color-name', colorName);
                    row.setAttribute('data-origin', origin);
                    row.setAttribute('data-cg-id', colorGroup);
                    row.setAttribute('data-cg-type', cgType);
                    
                    // Highlight row with Tailwind classes to ensure visibility
                    row.classList.add('bg-blue-600', 'text-white');
                    
                    // Reapply blue row highlighting
                    highlightBlueRows();
                } else {
                    console.error('Row does not have enough cells:', cells.length);
                }
            } catch (error) {
                console.error('Error updating row cells:', error);
            }
            
            console.log('Row updated successfully in the table');
        } else {
            console.error('Row not found in table for color ID:', colorId);
        }
        
        // Show success message and close modal
        alert('Data warna berhasil diperbarui');
        closeEditColorModal();
        
        // Refresh the table data
        populateColorTable();
    })
    .catch(error => {
        console.error('Error saving data:', error);
        alert('Error menyimpan data: ' + error.message);
    })
    .finally(() => {
        // Reset button state and hide loading overlay
        saveButton.innerHTML = originalText;
        saveButton.disabled = false;
        document.getElementById('loadingOverlay').classList.add('hidden');
    });
}

// Function to load data from database
function loadSeedData() {
    // Check if table body exists
    const tbody = document.querySelector('#colorDataTable tbody');
    if (!tbody) return;
    
    // Show loading overlay
    const loadingOverlay = document.getElementById('loadingOverlay');
    if (loadingOverlay) {
        loadingOverlay.classList.remove('hidden');
    }
    
    // Use database seeder if available
    fetch('/run-color-seeder')
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            console.log('Seeder response:', data);
            
            // Refresh color table with fresh data
            populateColorTable();
            
            // Show notification
            alert('Data warna berhasil dimuat dari database');
            
            // Hide loading overlay
            if (loadingOverlay) {
                loadingOverlay.classList.add('hidden');
            }
            
            // Open modal to display data
            openColorModal();
        })
        .catch(error => {
            console.error('Error running seeder:', error);
            
            // Fallback to just loading whatever data is in the database
            populateColorTable();
            
            // Show notification
            alert('Tidak dapat menjalankan seeder, memuat data yang tersedia dari database');
            
            // Hide loading overlay
            if (loadingOverlay) {
                loadingOverlay.classList.add('hidden');
            }
            
            // Open modal to display data
            openColorModal();
        });
}

// Initialize event handlers when document loads
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM content loaded for color management');
    
    // Initialize color table if open button exists
    const showBtn = document.getElementById('showColorTableBtn');
    if (showBtn) {
        showBtn.addEventListener('click', openColorModal);
    }
    
    // Setup load data button if it exists
    const loadDataBtn = document.querySelector('button[onclick="loadSeedData()"]');
    if (loadDataBtn) {
        loadDataBtn.addEventListener('click', loadSeedData);
    }
    
    // Close modal when clicking outside table
    const colorModal = document.getElementById('colorTableWindow');
    if (colorModal) {
        colorModal.onclick = function(e) {
            if (e.target === colorModal) {
                closeColorModal();
            }
        };
    }
    
    // Event to close modal when clicking outside
    window.onclick = function(event) {
        const editModal = document.getElementById('editColorModal');
        if (event.target === editModal) {
            closeEditColorModal();
        }
    };
    
    // Add event listener for search functionality
    const searchInput = document.getElementById('searchColorInput');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const rows = document.querySelectorAll('#colorDataTable tbody tr');
            
            rows.forEach(row => {
                // Skip the "no data" row if it exists
                if (row.querySelector('td[colspan="6"]')) return;
                
                const colorId = row.querySelector('td:nth-child(1)').textContent.toLowerCase();
                const colorName = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
                const origin = row.querySelector('td:nth-child(3)').textContent.toLowerCase();
                const cgId = row.querySelector('td:nth-child(4)').textContent.toLowerCase();
                
                if (colorId.includes(searchTerm) || 
                    colorName.includes(searchTerm) || 
                    origin.includes(searchTerm) || 
                    cgId.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    }
    
    // Setup Color Group search when the input exists
    const colorGroupSearchInput = document.getElementById('searchColorGroupInput');
    if (colorGroupSearchInput) {
        colorGroupSearchInput.addEventListener('input', function() {
            const searchText = this.value.toLowerCase();
            const rows = document.querySelectorAll('#colorGroupDataTable tbody tr');
            
            rows.forEach(row => {
                const cg = row.cells[0].textContent.toLowerCase();
                const name = row.cells[1].textContent.toLowerCase();
                const type = row.cells[2].textContent.toLowerCase();
                
                if (cg.includes(searchText) || name.includes(searchText) || type.includes(searchText)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    }
    
    // Close Color Group modal when clicking outside
    const colorGroupModal = document.getElementById('colorGroupTableModal');
    if (colorGroupModal) {
        colorGroupModal.addEventListener('click', function(e) {
            if (e.target === this) {
                closeColorGroupModal();
            }
        });
    }
    
    // Add event listener for the Select button inside the color table modal
    const modalSelectButton = document.getElementById('modalSelectColorButton');
    if (modalSelectButton) {
        modalSelectButton.addEventListener('click', function() {
            console.log('#modalSelectColorButton clicked. Checking currentlySelectedColorRow:', currentlySelectedColorRow);
            if (currentlySelectedColorRow) {
                console.log('Selected row found. Calling openEditColorModal.');
                openEditColorModal(currentlySelectedColorRow);
                closeColorModal(); // Close the table modal after selecting
            } else {
                alert('Silahkan pilih baris warna terlebih dahulu.');
            }
        });
    }
});

// Function to handle the Color Group modal

// Variable to store the currently selected color group row
let selectedColorGroupRow = null;

// Function to open the Color Group modal
function openColorGroupModal() {
    console.log('Opening color group modal');
    const modal = document.getElementById('colorGroupTableModal');
    if (modal) {
        modal.classList.remove('hidden');
        populateColorGroupTable();
    }
}

// Function to close the Color Group modal
function closeColorGroupModal() {
    console.log('Closing color group modal');
    const modal = document.getElementById('colorGroupTableModal');
    if (modal) {
        modal.classList.add('hidden');
    }
}

// Function to populate the Color Group table
function populateColorGroupTable() {
    console.log('Populating color group table');
    
    // Get the table body
    const tbody = document.querySelector('#colorGroupDataTable tbody');
    if (!tbody) {
        console.error('Color group table body not found');
        return;
    }
    
    // Clear existing rows
    tbody.innerHTML = '';
    
    // Show loading message
    tbody.innerHTML = '<tr><td colspan="3" class="px-4 py-4 text-center text-gray-500">Loading data...</td></tr>';
    
    // Fetch color groups from database
    fetch('/color-group', {
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        console.log('Response status:', response.status);
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        console.log('Color group data received:', data);
        
        // Clear loading message
        tbody.innerHTML = '';
        
        if (!Array.isArray(data) || data.length === 0) {
            tbody.innerHTML = '<tr><td colspan="3" class="px-4 py-4 text-center text-gray-500">Tidak ada data grup warna yang tersedia.</td></tr>';
            return;
        }
        
        // Add rows from database
        data.forEach(group => {
            const row = document.createElement('tr');
            row.className = 'hover:bg-blue-50 cursor-pointer';
            row.setAttribute('data-cg', group.cg_id);
            row.setAttribute('data-cg-name', group.cg_name);
            row.setAttribute('data-cg-type', group.cg_type);
            
            // Set click event to select row
            row.onclick = function(e) {
                selectColorGroupRow(this);
                e.stopPropagation();
            };
            
            // Set double-click event to select and close modal
            row.ondblclick = function() {
                selectColorGroupRow(this);
                selectColorGroup();
            };
            
            // Create cells
            const cgCell = document.createElement('td');
            cgCell.className = 'px-4 py-3 whitespace-nowrap font-medium text-gray-900';
            cgCell.textContent = group.cg_id;
            
            const nameCell = document.createElement('td');
            nameCell.className = 'px-4 py-3 whitespace-nowrap text-gray-700';
            nameCell.textContent = group.cg_name;
            
            const typeCell = document.createElement('td');
            typeCell.className = 'px-4 py-3 whitespace-nowrap text-gray-700';
            typeCell.textContent = group.cg_type;
            
            // Add cells to row
            row.appendChild(cgCell);
            row.appendChild(nameCell);
            row.appendChild(typeCell);
            
            // Add row to table
            tbody.appendChild(row);
        });
        
        // Add search functionality
        setupColorGroupSearch();
    })
    .catch(error => {
        console.error('Error fetching color groups:', error);
        tbody.innerHTML = `<tr><td colspan="3" class="px-4 py-4 text-center text-red-500">
            Error loading color groups: ${error.message}
            <br>
            <button onclick="populateColorGroupTable()" class="mt-2 px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                <i class="fas fa-sync-alt mr-2"></i>Coba Lagi
            </button>
        </td></tr>`;
    });
}

// Function to select a color group row
function selectColorGroupRow(row) {
    // Remove highlight from previously selected row
    if (selectedColorGroupRow) {
        selectedColorGroupRow.classList.remove('bg-blue-600', 'text-white');
    }
    
    // Highlight the new selected row
    row.classList.add('bg-blue-600', 'text-white');
    selectedColorGroupRow = row;
}

// Function to use the selected color group
function selectColorGroup() {
    if (!selectedColorGroupRow) {
        alert('Please select a color group first');
        return;
    }
    
    // Get data from selected row
    const cg = selectedColorGroupRow.getAttribute('data-cg');
    const cgName = selectedColorGroupRow.getAttribute('data-cg-name');
    const cgType = selectedColorGroupRow.getAttribute('data-cg-type');
    
    // Update the edit form
    document.getElementById('edit_color_group').value = cg;
    document.getElementById('edit_cg_type').value = cgType;
    
    // Close the modal
    closeColorGroupModal();
}

// Function to setup search functionality for color group table
function setupColorGroupSearch() {
    const searchInput = document.getElementById('searchColorGroupInput');
    if (!searchInput) return;
    
    searchInput.addEventListener('input', function() {
        const searchText = this.value.toLowerCase();
        const rows = document.querySelectorAll('#colorGroupDataTable tbody tr');
        
        rows.forEach(row => {
            const cg = row.getAttribute('data-cg').toLowerCase();
            const name = row.getAttribute('data-cg-name').toLowerCase();
            const type = row.getAttribute('data-cg-type').toLowerCase();
            
            // Show/hide rows based on search text
            if (cg.includes(searchText) || name.includes(searchText) || type.includes(searchText)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });
} 