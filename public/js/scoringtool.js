// ScoringTool.js - Functions for scoring tool management

// Function to populate scoring tool table with data from database
function populateScoringToolTable() {
    console.log('Populating scoring tool table');
    
    // Remove "no data" message if present
    const tbody = document.querySelector('#scoringToolDataTable tbody');
    tbody.innerHTML = '';
    
    // Show loading message
    tbody.innerHTML = '<tr><td colspan="4" class="px-4 py-4 text-center text-gray-500">Loading data...</td></tr>';
    
    // Fetch data from the database
    fetch('/scoring-tool', {
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
                tbody.innerHTML = '<tr><td colspan="4" class="px-4 py-4 text-center text-gray-500">Tidak ada data scoring tool yang tersedia.</td></tr>';
                return;
            }

            // Fill table with data from database
            data.forEach(tool => {
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
                scoresCell.textContent = parseFloat(tool.scores).toFixed(1);
                
                const gapCell = document.createElement('td');
                gapCell.className = 'px-4 py-3 whitespace-nowrap text-gray-700 text-right';
                gapCell.textContent = parseFloat(tool.gap).toFixed(1);
                
                // Add all cells to row
                row.appendChild(codeCell);
                row.appendChild(nameCell);
                row.appendChild(scoresCell);
                row.appendChild(gapCell);
                
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
                    <p class="text-sm font-medium text-green-800">Data tersedia: ${data.length} scoring tool ditemukan.</p>
                `;
            }
        })
        .catch(error => {
            console.error('Error fetching database data:', error);
            tbody.innerHTML = `<tr><td colspan="4" class="px-4 py-4 text-center text-red-500">
                Error loading data from database. ${error.message}
                <br>
                <button onclick="populateScoringToolTable()" class="mt-2 px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                    <i class="fas fa-sync-alt mr-2"></i>Coba Lagi
                </button>
            </td></tr>`;
        });
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

    // Update the main code input field with the selected row's code
    const code = row.getAttribute('data-tool-code');
    document.getElementById('code').value = code;
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
    
    // Send update to server
    fetch('/scoring-tool/' + code, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            code: code,
            name: name,
            scores: scores,
            gap: gap,
            specification: '',
            description: name,
            is_active: true
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            console.log('Scoring tool updated successfully:', data);
            
            // Update row in table
            const row = document.querySelector(`#scoringToolDataTable tbody tr[data-tool-code="${code}"]`);
            if (row) {
                const cells = row.cells;
                if (cells.length >= 4) {
                    cells[1].textContent = name;
                    cells[2].textContent = scores.toFixed(1);
                    cells[3].textContent = gap.toFixed(1);
                    
                    row.setAttribute('data-tool-name', name);
                    row.setAttribute('data-tool-scores', scores);
                    row.setAttribute('data-tool-gap', gap);
                }
            }
            
            // Update the main code input field
            document.getElementById('code').value = code;
            
            // Close the edit modal
            closeEditScoringToolModal();
            
            // Refresh the table data
            populateScoringToolTable();
            
            alert('Data scoring tool berhasil diperbarui');
        } else {
            console.error('Error updating scoring tool:', data.message);
            alert('Error updating scoring tool: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error updating scoring tool:', error);
        alert('Error updating scoring tool. Please try again.');
    })
    .finally(() => {
        // Reset button state and hide loading overlay
        saveButton.innerHTML = originalText;
        saveButton.disabled = false;
        document.getElementById('loadingOverlay').classList.add('hidden');
    });
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

// Function to load seed data
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
    
    // Fetch data from the database
    fetch('/scoring-tool')
        .then(response => response.json())
        .then(data => {
            // Fill table with data from database
            data.forEach(tool => {
                const row = document.createElement('tr');
                row.className = 'hover:bg-blue-50 cursor-pointer';
                row.setAttribute('data-tool-code', tool.code);
                row.setAttribute('data-tool-name', tool.name);
                row.setAttribute('data-tool-scores', tool.scores);
                row.setAttribute('data-tool-gap', tool.gap);
                row.onclick = function(e) { selectRow(this); e.stopPropagation(); };
                row.ondblclick = function() { openEditScoringToolModal(this); };
                
                // Create cells
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
                
                // Add cells to row
                row.appendChild(codeCell);
                row.appendChild(nameCell);
                row.appendChild(scoresCell);
                row.appendChild(gapCell);
                
                // Add row to table
                tbody.appendChild(row);
            });
            
            // Show notification
            alert('Data scoring tool berhasil dimuat dari database');
            
            // Hide loading overlay
            if (loadingOverlay) {
                loadingOverlay.classList.add('hidden');
            }
            
            // Open modal to display data
            openScoringToolModal();
        })
        .catch(error => {
            console.error('Error loading data:', error);
            tbody.innerHTML = '<tr><td colspan="4" class="px-4 py-4 text-center text-gray-500">Error loading data from database.</td></tr>';
            
            // Hide loading overlay
            if (loadingOverlay) {
                loadingOverlay.classList.add('hidden');
            }
            
            alert('Error loading data from database. Please try again.');
        });
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
