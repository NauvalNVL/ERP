// ScoringTool.js - Functions for scoring tool management

// Define seed data for scoring tools
const seedScoringTools = [
    { code: '0', name: 'N/A', scores: 0.0, gap: 0.0 },
    { code: '1', name: 'MALE MALE', scores: 0.0, gap: 0.0 },
    { code: '2', name: 'MALE FEMALE 10MM', scores: 0.0, gap: 0.0 },
    { code: '3', name: 'MALE FLAT', scores: 0.0, gap: 0.0 },
    { code: '4', name: 'MALE FEMALE 8MM', scores: 0.0, gap: 0.0 }
];

// Function to populate scoring tool table with seed data
function populateScoringToolTable() {
    console.log('Populating scoring tool table');
    
    // Check if table already has data
    const tbody = document.querySelector('#scoringToolDataTable tbody');
    const rows = tbody.querySelectorAll('tr');
    const isEmpty = rows.length === 0 || (rows.length === 1 && rows[0].querySelector('td[colspan]'));
    
    if (isEmpty) {
        console.log('Table is empty, populating with seed data');
        
        // Remove "no data" message if present
        tbody.innerHTML = '';
        
        // Fill table with data from seedScoringTools
        seedScoringTools.forEach(tool => {
            const row = document.createElement('tr');
            row.className = 'hover:bg-blue-50 cursor-pointer';
            row.setAttribute('data-tool-code', tool.code);
            row.setAttribute('data-tool-name', tool.name);
            row.setAttribute('data-tool-scores', tool.scores);
            row.setAttribute('data-tool-gap', tool.gap);
            row.onclick = function(e) { selectRow(this); e.stopPropagation(); };
            row.ondblclick = function() { openEditScoringToolModal(this); };
            
            // Create columns for each cell
            const codeCell = document.createElement('td');
            codeCell.className = 'px-4 py-3 whitespace-nowrap font-medium text-gray-900';
            codeCell.textContent = tool.code;
            
            const nameCell = document.createElement('td');
            nameCell.className = 'px-4 py-3 whitespace-nowrap text-gray-700';
            nameCell.textContent = tool.name;
            
            const scoresCell = document.createElement('td');
            scoresCell.className = 'px-4 py-3 whitespace-nowrap text-gray-700 text-right';
            scoresCell.textContent = tool.scores.toFixed(1);
            
            const gapCell = document.createElement('td');
            gapCell.className = 'px-4 py-3 whitespace-nowrap text-gray-700 text-right';
            gapCell.textContent = tool.gap.toFixed(1);
            
            // Add all cells to row
            row.appendChild(codeCell);
            row.appendChild(nameCell);
            row.appendChild(scoresCell);
            row.appendChild(gapCell);
            
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
    
    var table = document.getElementById('scoringToolDataTable');
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
        
        // Numeric-based sort for numeric columns
        if (columnIndex === 0 || columnIndex === 2 || columnIndex === 3) {
            return parseFloat(textA) - parseFloat(textB);
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
    var selectedRow = document.querySelector('#scoringToolDataTable tbody tr.bg-blue-600');
    if (!selectedRow) {
        console.log("No row selected");
        alert("Silahkan pilih scoring tool terlebih dahulu");
        return;
    }
    
    console.log("Selected row for editing:", selectedRow);
    
    // Open edit modal for selected row
    openEditScoringToolModal(selectedRow);
}

// Function to select a row
function selectRow(row) {
    // Remove highlight from all rows
    var allRows = document.querySelectorAll('#scoringToolDataTable tbody tr');
    allRows.forEach(function(r) {
        r.classList.remove('selected');
        r.classList.remove('bg-blue-600', 'text-white');
    });
    
    // Add highlighting to selected row
    row.classList.add('bg-blue-600', 'text-white');
}

// Function to open edit modal
function openEditScoringToolModal(row) {
    console.log('Opening edit scoring tool modal for row:', row);
    
    const code = row.getAttribute('data-tool-code');
    const name = row.getAttribute('data-tool-name');
    const scores = row.getAttribute('data-tool-scores');
    const gap = row.getAttribute('data-tool-gap');
    
    console.log('Scoring tool data:', { code, name, scores, gap });
    
    document.getElementById('edit_tool_code').value = code;
    document.getElementById('edit_tool_name').value = name;
    document.getElementById('edit_tool_scores').value = scores;
    document.getElementById('edit_tool_gap').value = gap;
    
    const editModal = document.getElementById('editScoringToolModal');
    editModal.classList.remove('hidden');
    
    console.log('Edit modal opened');
}

// Function to close edit modal
function closeEditScoringToolModal() {
    console.log('Closing edit scoring tool modal');
    const editModal = document.getElementById('editScoringToolModal');
    editModal.classList.add('hidden');
}

// Function to display modal
function openScoringToolModal() {
    console.log('Opening scoring tool modal');
    var modal = document.getElementById('scoringToolTableWindow');
    if (!modal) {
        console.error('Modal element not found');
        return;
    }
    
    // Show modal
    modal.classList.remove('hidden');
    
    // Populate table with data if needed
    populateScoringToolTable();
    
    // Sort by Code by default
    sortTableDirectly(0);
    
    console.log('Modal opened successfully');
}

// Function to close modal
function closeScoringToolModal() {
    var modal = document.getElementById('scoringToolTableWindow');
    if (modal) {
        modal.classList.add('hidden');
    }
}

function closeModalX() {
    closeScoringToolModal();
}

// Function to set up events on table rows
function setupTableRowEvents() {
    console.log('Setting up table row events');
    var tableRows = document.querySelectorAll('#scoringToolDataTable tbody tr');
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
            openEditScoringToolModal(this);
        };
    });
}

// Function to save scoring tool changes
function saveScoringToolChanges() {
    console.log('Saving scoring tool changes');
    
    const code = document.getElementById('edit_tool_code').value;
    const name = document.getElementById('edit_tool_name').value;
    const scores = parseFloat(document.getElementById('edit_tool_scores').value);
    const gap = parseFloat(document.getElementById('edit_tool_gap').value);
    
    console.log('Form data to save:', { code, name, scores, gap });
    
    // Validate input
    if (!name) {
        alert('Nama scoring tool tidak boleh kosong');
        return;
    }
    
    // Display loading indicator on button and overlay
    const saveButton = document.querySelector('#editScoringToolForm button[type="submit"]');
    const originalText = saveButton.innerHTML;
    saveButton.innerHTML = '<i class="fas fa-circle-notch fa-spin mr-2"></i>Menyimpan...';
    saveButton.disabled = true;
    
    // Show loading overlay
    document.getElementById('loadingOverlay').classList.remove('hidden');
    
    // Update row in table immediately to provide visual feedback
    const row = document.querySelector(`#scoringToolDataTable tbody tr[data-tool-code="${code}"]`);
    if (row) {
        console.log('Found row to update:', row);
        
        try {
            // Direct DOM manipulation for cell updates
            const cells = row.cells;
            
            if (cells.length >= 4) {
                // Update cell text directly
                cells[1].textContent = name;
                cells[2].textContent = scores.toFixed(1);
                cells[3].textContent = gap.toFixed(1);
                
                // Update data attributes
                row.setAttribute('data-tool-code', code);
                row.setAttribute('data-tool-name', name);
                row.setAttribute('data-tool-scores', scores);
                row.setAttribute('data-tool-gap', gap);
                
                // Highlight row with Tailwind classes to ensure visibility
                row.classList.add('bg-blue-600', 'text-white');
                
                // Also update seedScoringTools array to keep data in sync
                updateSeedScoringToolData(code, name, scores, gap);
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
        const tbody = document.querySelector('#scoringToolDataTable tbody');
        
        // Create a new row
        const newRow = document.createElement('tr');
        newRow.className = 'hover:bg-blue-50 cursor-pointer';
        newRow.setAttribute('data-tool-code', code);
        newRow.setAttribute('data-tool-name', name);
        newRow.setAttribute('data-tool-scores', scores);
        newRow.setAttribute('data-tool-gap', gap);
        newRow.onclick = function(e) { selectRow(this); e.stopPropagation(); };
        newRow.ondblclick = function() { openEditScoringToolModal(this); };
        
        // Create cells
        const codeCell = document.createElement('td');
        codeCell.className = 'px-4 py-3 whitespace-nowrap font-medium text-gray-900';
        codeCell.textContent = code;
        
        const nameCell = document.createElement('td');
        nameCell.className = 'px-4 py-3 whitespace-nowrap text-gray-700';
        nameCell.textContent = name;
        
        const scoresCell = document.createElement('td');
        scoresCell.className = 'px-4 py-3 whitespace-nowrap text-gray-700 text-right';
        scoresCell.textContent = scores.toFixed(1);
        
        const gapCell = document.createElement('td');
        gapCell.className = 'px-4 py-3 whitespace-nowrap text-gray-700 text-right';
        gapCell.textContent = gap.toFixed(1);
        
        // Add cells to row
        newRow.appendChild(codeCell);
        newRow.appendChild(nameCell);
        newRow.appendChild(scoresCell);
        newRow.appendChild(gapCell);
        
        // Add row to table
        tbody.appendChild(newRow);
        
        // Select the new row
        selectRow(newRow);
        
        // Add to seedScoringTools
        updateSeedScoringToolData(code, name, scores, gap);
    }
    
    // Update main form inputs
    document.getElementById('code').value = code;
    document.getElementById('name').value = name;
    document.getElementById('scores').value = scores.toFixed(1);
    document.getElementById('gap').value = gap.toFixed(1);
    
    // Show success message and close modal
    setTimeout(() => {
        alert('Data scoring tool berhasil diperbarui');
        closeEditScoringToolModal();
        
        // Reset button state and hide loading overlay
        saveButton.innerHTML = originalText;
        saveButton.disabled = false;
        document.getElementById('loadingOverlay').classList.add('hidden');
    }, 500);
}

// Function to update data in seedScoringTools array
function updateSeedScoringToolData(code, name, scores, gap) {
    // Find tool with matching code in seedScoringTools array
    const toolIndex = seedScoringTools.findIndex(tool => tool.code === code);
    
    if (toolIndex !== -1) {
        console.log(`Updating seedScoringTools[${toolIndex}] with new data`);
        
        // Update data in array
        seedScoringTools[toolIndex].name = name;
        seedScoringTools[toolIndex].scores = scores;
        seedScoringTools[toolIndex].gap = gap;
        
        console.log('Updated seedScoringTools:', seedScoringTools[toolIndex]);
    } else {
        console.log(`Scoring tool with code ${code} not found in seedScoringTools array`);
        
        // If not found, add as new item
        seedScoringTools.push({
            code: code,
            name: name,
            scores: scores,
            gap: gap
        });
        
        console.log('Added new scoring tool to seedScoringTools array');
    }
}

// Function to make seed data available without database
function loadSeedData() {
    // Check if data is already loaded
    const tbody = document.querySelector('#scoringToolDataTable tbody');
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
        // Fill table with data from seedScoringTools
        populateScoringToolTable();
        
        // Show notification
        alert('Data scoring tool berhasil dimuat dari ScoringToolSeeder');
        
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
                <p class="text-sm font-medium text-green-800">Data tersedia: ${seedScoringTools.length} scoring tool ditemukan (dari JavaScript).</p>
            `;
        }
        
        // Open modal to display data
        openScoringToolModal();
    }, 1000);
}

// Initialize event handlers when document loads
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM content loaded for scoring tool');
    
    // Setup row events initially
    setupTableRowEvents();
    
    // Initialize scoring tool table if show button exists
    const showBtn = document.getElementById('showScoringToolTableBtn');
    if (showBtn) {
        showBtn.addEventListener('click', openScoringToolModal);
    }
    
    // Setup load data button if it exists
    const loadDataBtn = document.getElementById('loadDataJsBtn');
    if (loadDataBtn) {
        loadDataBtn.addEventListener('click', loadSeedData);
    }
    
    // Close modal when clicking outside table
    const toolModal = document.getElementById('scoringToolTableWindow');
    if (toolModal) {
        toolModal.onclick = function(e) {
            if (e.target === toolModal) {
                closeScoringToolModal();
            }
        };
    }
    
    // Event to close modal when clicking outside
    window.onclick = function(event) {
        const editModal = document.getElementById('editScoringToolModal');
        if (event.target === editModal) {
            closeEditScoringToolModal();
        }
    };
});
