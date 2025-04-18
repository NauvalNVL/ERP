// Color.js - Functions for color management

// Define seed data for colors
const seedColors = [
    { color_id: 'C001', color_name: 'Black Standard', origin: '1', color_group_id: '01', cg_type: 'X-Flexo' },
    { color_id: 'C002', color_name: 'White Premium', origin: '2', color_group_id: '02', cg_type: 'X-Flexo' },
    { color_id: 'C003', color_name: 'Red Bright', origin: '1', color_group_id: '03', cg_type: 'X-Flexo' },
    { color_id: 'C004', color_name: 'Blue Ocean', origin: '3', color_group_id: '04', cg_type: 'X-Flexo' },
    { color_id: 'C005', color_name: 'Green Forest', origin: '2', color_group_id: '05', cg_type: 'X-Flexo' },
    { color_id: 'C006', color_name: 'Cyan Sky', origin: '1', color_group_id: '06', cg_type: 'X-Flexo' },
    { color_id: 'C007', color_name: 'Magenta Deep', origin: '3', color_group_id: '07', cg_type: 'X-Flexo' },
    { color_id: 'C008', color_name: 'Pink Soft', origin: '2', color_group_id: '08', cg_type: 'X-Flexo' },
    { color_id: 'C009', color_name: 'Black Matte', origin: '1', color_group_id: '01', cg_type: 'X-Flexo' },
    { color_id: 'C010', color_name: 'White Matte', origin: '2', color_group_id: '02', cg_type: 'X-Flexo' }
];

function populateColorTable() {
    console.log('Populating color table');
    
    // Check if table already has data
    const tbody = document.querySelector('#colorDataTable tbody');
    const rows = tbody.querySelectorAll('tr');
    const isEmpty = rows.length === 0 || (rows.length === 1 && rows[0].querySelector('td[colspan]'));
    
    if (isEmpty) {
        console.log('Table is empty, populating with seed data');
        
        // Remove "no data" message if present
        tbody.innerHTML = '';
        
        // Fill table with data from seedColors
        seedColors.forEach(color => {
            const row = document.createElement('tr');
            row.className = 'cursor-pointer';
            row.setAttribute('data-color-id', color.color_id);
            row.setAttribute('data-color-name', color.color_name);
            row.setAttribute('data-origin', color.origin);
            row.setAttribute('data-cg-id', color.color_group_id);
            row.setAttribute('data-cg-type', color.cg_type);
            row.onclick = function(e) { selectRow(this); e.stopPropagation(); };
            row.ondblclick = function() { openEditColorModal(this); };
            
            // Create columns for each cell
            const idCell = document.createElement('td');
            idCell.className = 'px-2 py-0.5 border-b border-r border-gray-300';
            idCell.textContent = color.color_id;
            
            const nameCell = document.createElement('td');
            nameCell.className = 'px-2 py-0.5 border-b border-r border-gray-300';
            nameCell.textContent = color.color_name;
            
            const originCell = document.createElement('td');
            originCell.className = 'px-2 py-0.5 border-b border-r border-gray-300';
            originCell.textContent = color.origin;
            
            const cgIdCell = document.createElement('td');
            cgIdCell.className = 'px-2 py-0.5 border-b border-r border-gray-300';
            cgIdCell.textContent = color.color_group_id;
            
            const cgNameCell = document.createElement('td');
            cgNameCell.className = 'px-2 py-0.5 border-b border-r border-gray-300';
            cgNameCell.textContent = getCGName(color.color_group_id);
            
            const cgTypeCell = document.createElement('td');
            cgTypeCell.className = 'px-2 py-0.5 border-b border-gray-300';
            cgTypeCell.textContent = color.cg_type;
            
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
        
        console.log('Table populated with seed data');
        
        // Setup event handlers for the table rows
        setupTableRowEvents();
        
        // Apply blue highlighting to rows
        highlightBlueRows();
        
        // Sort table by Color#
        sortTableDirectly(0);
    } else {
        console.log('Table already has data, no need to populate');
    }
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

// Function to edit selected row (Edit button)
function editSelectedRow() {
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
function selectRow(row) {
    // Remove highlight from all rows
    var allRows = document.querySelectorAll('#colorDataTable tbody tr');
    allRows.forEach(function(r) {
        r.classList.remove('selected');
        r.classList.remove('bg-blue-600', 'text-white');
    });
    
    // Add highlighting to selected row
    row.classList.add('bg-blue-600', 'text-white');
}

// Function to open edit modal
function openEditColorModal(row) {
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

// Function to display modal
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
    
    // Populate table with data from ColorSeeder if needed
    populateColorTable();
    
    // Sort by Color# by default
    sortTableDirectly(0);
    
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
    // Map of color group IDs to names based on ColorGroupSeeder
    const cgNames = {
        '01': 'BLACK',
        '02': 'WHITE',
        '03': 'RED',
        '04': 'BLUE',
        '05': 'GREEN',
        '06': 'CYAN',
        '07': 'MAGENTA',
        '08': 'PINK'
    };
    
    return cgNames[cgId] || '';
}

// Function to set up events on table rows
function setupTableRowEvents() {
    console.log('Setting up table row events');
    var tableRows = document.querySelectorAll('#colorDataTable tbody tr');
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
            openEditColorModal(this);
        };
    });
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
    
    // Display loading indicator on button and overlay
    const saveButton = document.querySelector('#editColorForm button[type="submit"]');
    const originalText = saveButton.innerText;
    saveButton.innerText = 'Menyimpan...';
    saveButton.disabled = true;
    
    // Show loading overlay
    document.getElementById('loadingOverlay').classList.remove('hidden');
    
    // Update row in table immediately to provide visual feedback
    const row = document.querySelector(`#colorDataTable tbody tr[data-color-id="${colorId}"]`);
    if (row) {
        console.log('Found row to update:', row);
        
        try {
            // Direct DOM manipulation for cell updates
            const cells = row.cells;
            console.log('Row has cells:', cells.length);
            
            if (cells.length >= 6) {
                console.log('Original cell values:');
                console.log('- Cell 1 (Color Name):', cells[1].textContent);
                console.log('- Cell 2 (Origin):', cells[2].textContent);
                console.log('- Cell 3 (CG#):', cells[3].textContent);
                console.log('- Cell 5 (CG Type):', cells[5].textContent);
                
                // Update cell text directly
                cells[1].textContent = colorName;
                cells[2].textContent = origin;
                cells[3].textContent = colorGroup;
                cells[5].textContent = cgType;
                
                // Get the color group name from JS function
                const cgName = getCGName(colorGroup);
                cells[4].textContent = cgName;
                
                console.log('After update:');
                console.log('- Cell 1 (Color Name):', cells[1].textContent);
                console.log('- Cell 2 (Origin):', cells[2].textContent);
                console.log('- Cell 3 (CG#):', cells[3].textContent);
                console.log('- Cell 4 (CG Name):', cells[4].textContent);
                console.log('- Cell 5 (CG Type):', cells[5].textContent);
                
                // Update data attributes
                row.setAttribute('data-color-name', colorName);
                row.setAttribute('data-origin', origin);
                row.setAttribute('data-cg-id', colorGroup);
                row.setAttribute('data-cg-type', cgType);
                
                // Highlight row with Tailwind classes to ensure visibility
                row.classList.add('bg-blue-600', 'text-white');
                
                // Reapply blue row highlighting
                highlightBlueRows();
                
                // Also update seedColors array to keep data in sync
                updateSeedColorData(colorId, colorName, origin, colorGroup, cgType, kgPerM2);
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
    
    // Reset button state and hide loading overlay after a short delay
    setTimeout(() => {
        saveButton.innerText = originalText;
        saveButton.disabled = false;
        document.getElementById('loadingOverlay').classList.add('hidden');
    }, 500);
}

// Function to update data in seedColors array
function updateSeedColorData(colorId, colorName, origin, colorGroup, cgType, kgPerM2) {
    // Find color with matching ID in seedColors array
    const colorIndex = seedColors.findIndex(color => color.color_id === colorId);
    
    // Always use X-Flexo for cg_type
    const finalCgType = "X-Flexo";
    
    if (colorIndex !== -1) {
        console.log(`Updating seedColors[${colorIndex}] with new data`);
        
        // Update data in array
        seedColors[colorIndex].color_name = colorName;
        seedColors[colorIndex].origin = origin;
        seedColors[colorIndex].color_group_id = colorGroup;
        seedColors[colorIndex].cg_type = finalCgType;
        
        console.log('Updated seedColors:', seedColors[colorIndex]);
    } else {
        console.log(`Color with ID ${colorId} not found in seedColors array`);
        
        // If not found, add as new item
        seedColors.push({
            color_id: colorId,
            color_name: colorName,
            origin: origin,
            color_group_id: colorGroup,
            cg_type: finalCgType
        });
        
        console.log('Added new color to seedColors array');
    }
}

// Function to make seed data available without database
function loadSeedData() {
    // Check if data is already loaded
    const tbody = document.querySelector('#colorDataTable tbody');
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
        // Fill table with data from seedColors
        populateColorTable();
        
        // Show notification
        alert('Data warna berhasil dimuat dari ColorSeeder');
        
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
                <p class="text-sm font-medium text-green-800">Data tersedia: ${seedColors.length} warna ditemukan (dari JavaScript).</p>
            `;
        }
        
        // Open modal to display data
        openColorModal();
    }, 1000);
}

// Initialize event handlers when document loads
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM content loaded');
    
    // Setup row events initially
    setupTableRowEvents();
    
    // Initialize color table if open button exists
    const showBtn = document.getElementById('showColorTableBtn');
    if (showBtn) {
        console.log('Show button found, setting up event listener');
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
}); 