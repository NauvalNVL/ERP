// Papersize.js - Functions for paper size management

// Define seed data for paper sizes
const seedPaperSizes = [
    { size_id: 'PS001', size_mm: '210.00', size_inch: '8.27', description: 'A4 Width' },
    { size_id: 'PS002', size_mm: '297.00', size_inch: '11.69', description: 'A4 Height' },
    { size_id: 'PS003', size_mm: '148.00', size_inch: '5.83', description: 'A5 Width' },
    { size_id: 'PS004', size_mm: '210.00', size_inch: '8.27', description: 'A5 Height' },
    { size_id: 'PS005', size_mm: '105.00', size_inch: '4.13', description: 'A6 Width' },
    { size_id: 'PS006', size_mm: '148.00', size_inch: '5.83', description: 'A6 Height' },
    { size_id: 'PS007', size_mm: '279.40', size_inch: '11.00', description: 'Letter Width' },
    { size_id: 'PS008', size_mm: '215.90', size_inch: '8.50', description: 'Letter Height' },
    { size_id: 'PS009', size_mm: '431.80', size_inch: '17.00', description: 'Tabloid Width' },
    { size_id: 'PS010', size_mm: '279.40', size_inch: '11.00', description: 'Tabloid Height' }
];

function populatePaperSizeTable() {
    console.log('Populating paper size table');
    
    // Check if table already has data
    const tbody = document.querySelector('#paperSizeDataTable tbody');
    const rows = tbody.querySelectorAll('tr');
    const isEmpty = rows.length === 0 || (rows.length === 1 && rows[0].querySelector('td[colspan]'));
    
    if (isEmpty) {
        console.log('Table is empty, populating with seed data');
        
        // Remove "no data" message if present
        tbody.innerHTML = '';
        
        // Fill table with data from seedPaperSizes
        seedPaperSizes.forEach(size => {
            const row = document.createElement('tr');
            row.className = 'cursor-pointer hover:bg-blue-50';
            row.setAttribute('data-size-id', size.size_id);
            row.setAttribute('data-size-mm', size.size_mm);
            row.setAttribute('data-size-inch', size.size_inch);
            row.setAttribute('data-description', size.description);
            row.onclick = function(e) { selectRow(this); e.stopPropagation(); };
            row.ondblclick = function() { openEditPaperSizeModal(this); };
            
            // Create columns for each cell
            const idCell = document.createElement('td');
            idCell.className = 'px-4 py-3 whitespace-nowrap font-medium text-gray-900';
            idCell.textContent = size.size_id;
            
            const mmCell = document.createElement('td');
            mmCell.className = 'px-4 py-3 whitespace-nowrap text-gray-700';
            mmCell.textContent = size.size_mm;
            
            const inchCell = document.createElement('td');
            inchCell.className = 'px-4 py-3 whitespace-nowrap text-gray-700';
            inchCell.textContent = size.size_inch;
            
            const descriptionCell = document.createElement('td');
            descriptionCell.className = 'px-4 py-3 whitespace-nowrap text-gray-700';
            descriptionCell.textContent = size.description;
            
            // Add all cells to row
            row.appendChild(idCell);
            row.appendChild(mmCell);
            row.appendChild(inchCell);
            row.appendChild(descriptionCell);
            
            // Add row to table
            tbody.appendChild(row);
        });
        
        console.log('Table populated with seed data');
        
        // Setup event handlers for the table rows
        setupTableRowEvents();
        
        // Sort table by Size ID
        sortTableDirectly(0);
    } else {
        console.log('Table already has data, no need to populate');
    }
}

// Function to sort table
function sortTableDirectly(columnIndex) {
    console.log("Sorting by column: " + columnIndex);
    
    var table = document.getElementById('paperSizeDataTable');
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
        
        // Numeric-based sort for ID, MM and Inch columns
        if (columnIndex === 0 || columnIndex === 1 || columnIndex === 2) {
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
    var selectedRow = document.querySelector('#paperSizeDataTable tbody tr.bg-blue-600');
    if (!selectedRow) {
        console.log("No row selected");
        alert("Please select a paper size first");
        return;
    }
    
    console.log("Selected row for editing:", selectedRow);
    
    // Open edit modal for selected row
    openEditPaperSizeModal(selectedRow);
}

// Function to select a row
function selectRow(row) {
    // Remove highlight from all rows
    var allRows = document.querySelectorAll('#paperSizeDataTable tbody tr');
    allRows.forEach(function(r) {
        r.classList.remove('selected');
        r.classList.remove('bg-blue-600', 'text-white');
    });
    
    // Add highlighting to selected row
    row.classList.add('bg-blue-600', 'text-white');
}

// Function to open edit modal
function openEditPaperSizeModal(row) {
    console.log('Opening edit paper size modal for row:', row);
    
    const sizeId = row.getAttribute('data-size-id');
    const sizeMM = row.getAttribute('data-size-mm');
    const sizeInch = row.getAttribute('data-size-inch');
    const description = row.getAttribute('data-description');
    
    console.log('Paper size data:', { sizeId, sizeMM, sizeInch, description });
    
    document.getElementById('edit_size_id').value = sizeId;
    document.getElementById('edit_size_mm').value = sizeMM;
    document.getElementById('edit_size_inch').value = sizeInch;
    document.getElementById('edit_description').value = description;
    
    const editModal = document.getElementById('editPaperSizeModal');
    editModal.style.display = 'block';
    editModal.classList.remove('hidden');
    
    console.log('Edit modal opened');
}

// Function to close edit modal
function closeEditPaperSizeModal() {
    console.log('Closing edit paper size modal');
    const editModal = document.getElementById('editPaperSizeModal');
    editModal.classList.add('hidden');
    editModal.style.display = 'none';
}

// Function to display modal
function openPaperSizeModal() {
    console.log('Opening paper size modal');
    var modal = document.getElementById('paperSizeTableWindow');
    if (!modal) {
        console.error('Modal element not found');
        return;
    }
    
    // Show modal
    modal.style.display = 'block';
    modal.classList.remove('hidden');
    
    // Populate table with data from seedPaperSizes if needed
    populatePaperSizeTable();
    
    // Sort by Size ID by default
    sortTableDirectly(0);
    
    console.log('Modal opened successfully');
}

// Function to close modal
function closePaperSizeModal() {
    var modal = document.getElementById('paperSizeTableWindow');
    if (modal) {
        modal.style.display = 'none';
        modal.classList.add('hidden');
    }
}

function closeModalX() {
    closePaperSizeModal();
}

// Function to update inch from mm
function updateInchFromMM(mmValue) {
    if (!isNaN(mmValue) && mmValue > 0) {
        return (parseFloat(mmValue) / 25.4).toFixed(2);
    }
    return "0.00";
}

// Function to update mm from inch
function updateMMFromInch(inchValue) {
    if (!isNaN(inchValue) && inchValue > 0) {
        return (parseFloat(inchValue) * 25.4).toFixed(2);
    }
    return "0.00";
}

// Function to set up events on table rows
function setupTableRowEvents() {
    console.log('Setting up table row events');
    var tableRows = document.querySelectorAll('#paperSizeDataTable tbody tr');
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
            openEditPaperSizeModal(this);
        };
    });
}

// Function to save paper size changes
function savePaperSizeChanges() {
    console.log('Saving paper size changes');
    
    const sizeId = document.getElementById('edit_size_id').value;
    const sizeMM = document.getElementById('edit_size_mm').value;
    const sizeInch = document.getElementById('edit_size_inch').value;
    const description = document.getElementById('edit_description').value;
    
    console.log('Form data to save:', { sizeId, sizeMM, sizeInch, description });
    
    // Display loading indicator on button and overlay
    const saveButton = document.querySelector('#editPaperSizeForm button[type="submit"]');
    const originalText = saveButton.innerText;
    saveButton.innerText = 'Saving...';
    saveButton.disabled = true;
    
    // Show loading overlay
    document.getElementById('loadingOverlay').classList.remove('hidden');
    
    // Update row in table immediately to provide visual feedback
    const row = document.querySelector(`#paperSizeDataTable tbody tr[data-size-id="${sizeId}"]`);
    if (row) {
        console.log('Found row to update:', row);
        
        try {
            // Direct DOM manipulation for cell updates
            const cells = row.cells;
            console.log('Row has cells:', cells.length);
            
            if (cells.length >= 4) {
                // Update cell text directly
                cells[1].textContent = sizeMM;
                cells[2].textContent = sizeInch;
                cells[3].textContent = description;
                
                // Update data attributes
                row.setAttribute('data-size-mm', sizeMM);
                row.setAttribute('data-size-inch', sizeInch);
                row.setAttribute('data-description', description);
                
                // Highlight row with Tailwind classes to ensure visibility
                row.classList.add('bg-blue-600', 'text-white');
                
                // Also update seedPaperSizes array to keep data in sync
                updateSeedPaperSizeData(sizeId, sizeMM, sizeInch, description);
            } else {
                console.error('Row does not have enough cells:', cells.length);
            }
        } catch (error) {
            console.error('Error updating row cells:', error);
        }
        
        console.log('Row updated successfully in the table');
    } else {
        console.error('Row not found in table for size ID:', sizeId);
    }
    
    // Show success message and close modal
    alert('Paper size data updated successfully');
    closeEditPaperSizeModal();
    
    // Reset button state and hide loading overlay after a short delay
    setTimeout(() => {
        saveButton.innerText = originalText;
        saveButton.disabled = false;
        document.getElementById('loadingOverlay').classList.add('hidden');
    }, 500);
}

// Function to update data in seedPaperSizes array
function updateSeedPaperSizeData(sizeId, sizeMM, sizeInch, description) {
    // Find paper size with matching ID in seedPaperSizes array
    const sizeIndex = seedPaperSizes.findIndex(size => size.size_id === sizeId);
    
    if (sizeIndex !== -1) {
        console.log(`Updating seedPaperSizes[${sizeIndex}] with new data`);
        
        // Update data in array
        seedPaperSizes[sizeIndex].size_mm = sizeMM;
        seedPaperSizes[sizeIndex].size_inch = sizeInch;
        seedPaperSizes[sizeIndex].description = description;
        
        console.log('Updated seedPaperSizes:', seedPaperSizes[sizeIndex]);
    } else {
        console.log(`Paper Size with ID ${sizeId} not found in seedPaperSizes array`);
        
        // If not found, add as new item
        seedPaperSizes.push({
            size_id: sizeId,
            size_mm: sizeMM,
            size_inch: sizeInch,
            description: description
        });
        
        console.log('Added new paper size to seedPaperSizes array');
    }
}

// Function to make seed data available without database
function loadSeedData() {
    // Check if data is already loaded
    const tbody = document.querySelector('#paperSizeDataTable tbody');
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
        // Fill table with data from seedPaperSizes
        populatePaperSizeTable();
        
        // Show notification
        alert('Paper size data loaded successfully');
        
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
                <p class="text-sm font-medium text-green-800">Data available: ${seedPaperSizes.length} paper sizes found (from JavaScript).</p>
            `;
        }
        
        // Open modal to display data
        openPaperSizeModal();
    }, 1000);
}

// Initialize event handlers when document loads
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM content loaded for Paper Size');
    
    // Setup row events initially
    setupTableRowEvents();
    
    // Initialize paper size table if open button exists
    const showBtn = document.getElementById('showPaperSizeTableBtn');
    if (showBtn) {
        console.log('Show button found, setting up event listener');
        showBtn.addEventListener('click', openPaperSizeModal);
    }
    
    // Close modal when clicking outside table
    const paperSizeModal = document.getElementById('paperSizeTableWindow');
    if (paperSizeModal) {
        paperSizeModal.onclick = function(e) {
            if (e.target === paperSizeModal) {
                closePaperSizeModal();
            }
        };
    }

    // Connect size mm and inch inputs for automatic conversion
    const mmInput = document.getElementById('edit_size_mm');
    const inchInput = document.getElementById('edit_size_inch');
    
    if (mmInput && inchInput) {
        mmInput.addEventListener('input', function() {
            inchInput.value = updateInchFromMM(this.value);
        });
        
        inchInput.addEventListener('input', function() {
            mmInput.value = updateMMFromInch(this.value);
        });
    }
    
    // Event to close modal when clicking outside
    window.onclick = function(event) {
        const editModal = document.getElementById('editPaperSizeModal');
        if (event.target === editModal) {
            closeEditPaperSizeModal();
        }
    };
    
    // Add event listener for search functionality
    const searchInput = document.getElementById('searchPaperSizeInput');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const rows = document.querySelectorAll('#paperSizeDataTable tbody tr');
            
            rows.forEach(row => {
                // Skip the "no data" row if it exists
                if (row.querySelector('td[colspan]')) return;
                
                const sizeId = row.querySelector('td:nth-child(1)').textContent.toLowerCase();
                const sizeMM = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
                const sizeInch = row.querySelector('td:nth-child(3)').textContent.toLowerCase();
                const description = row.querySelector('td:nth-child(4)').textContent.toLowerCase();
                
                if (sizeId.includes(searchTerm) || 
                    sizeMM.includes(searchTerm) || 
                    sizeInch.includes(searchTerm) || 
                    description.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    }
});
