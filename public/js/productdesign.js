// ProductDesign.js - Functions for product design management

// Product Design data management
let productDesignData = [];
let currentProductDesignId = null;

// Seed data for Product Designs
const seedProductDesigns = [
    { pd_code: "PD001", pd_name: "Basic Square Box", product_code: "BOX001", dimension: "100x100x100", idc: "BSQ-100" },
    { pd_code: "PD002", pd_name: "Medium Rectangle Box", product_code: "BOX002", dimension: "200x150x100", idc: "MRB-200" },
    { pd_code: "PD003", pd_name: "Large Storage Box", product_code: "BOX003", dimension: "300x200x150", idc: "LSB-300" },
    { pd_code: "PD004", pd_name: "Slim Document Case", product_code: "DOC001", dimension: "330x240x30", idc: "SDC-330" },
    { pd_code: "PD005", pd_name: "Folder Design", product_code: "FOL001", dimension: "320x230x5", idc: "FD-320" },
    { pd_code: "PD006", pd_name: "Small Gift Box", product_code: "GFT001", dimension: "100x100x50", idc: "SGB-100" },
    { pd_code: "PD007", pd_name: "Premium Gift Box", product_code: "GFT002", dimension: "200x200x100", idc: "PGB-200" },
    { pd_code: "PD008", pd_name: "Envelope Standard", product_code: "ENV001", dimension: "220x110x0", idc: "ES-220" },
    { pd_code: "PD009", pd_name: "Business Card", product_code: "BC001", dimension: "90x55x0", idc: "BC-90" },
    { pd_code: "PD010", pd_name: "A4 Notebook Design", product_code: "NB001", dimension: "297x210x15", idc: "A4N-297" }
];

function populateProductDesignTable() {
    console.log('Populating product design table');
    
    // Check if table already has data
    const tbody = document.querySelector('#productDesignTable tbody');
    const rows = tbody.querySelectorAll('tr');
    const isEmpty = rows.length === 0 || (rows.length === 1 && rows[0].querySelector('td[colspan]'));
    
    if (isEmpty) {
        console.log('Table is empty, populating with seed data');
        
        // Remove "no data" message if present
        tbody.innerHTML = '';
        
        // Fill table with data from seedProductDesigns
        seedProductDesigns.forEach(design => {
            const row = document.createElement('tr');
            row.className = 'hover:bg-blue-50 cursor-pointer';
            row.setAttribute('data-pd-code', design.pd_code);
            row.setAttribute('data-pd-name', design.pd_name);
            row.setAttribute('data-product-code', design.product_code);
            row.setAttribute('data-dimension', design.dimension);
            row.setAttribute('data-idc', design.idc);
            row.onclick = function(e) { selectRow(this); e.stopPropagation(); };
            row.ondblclick = function() { openEditProductDesignModal(this); };
            
            // Create columns for each cell
            const codeCell = document.createElement('td');
            codeCell.className = 'px-4 py-3 whitespace-nowrap font-medium text-gray-900';
            codeCell.textContent = design.pd_code;
            
            const nameCell = document.createElement('td');
            nameCell.className = 'px-4 py-3 whitespace-nowrap text-gray-700';
            nameCell.textContent = design.pd_name;
            
            const productCodeCell = document.createElement('td');
            productCodeCell.className = 'px-4 py-3 whitespace-nowrap text-gray-700';
            productCodeCell.textContent = design.product_code;
            
            const dimensionCell = document.createElement('td');
            dimensionCell.className = 'px-4 py-3 whitespace-nowrap text-gray-700';
            dimensionCell.textContent = design.dimension;
            
            const idcCell = document.createElement('td');
            idcCell.className = 'px-4 py-3 whitespace-nowrap';
            
            // Create a span for the IDC with a nice badge style
            const idcSpan = document.createElement('span');
            idcSpan.className = 'px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800';
            idcSpan.textContent = design.idc;
            idcCell.appendChild(idcSpan);
            
            // Add all cells to row
            row.appendChild(codeCell);
            row.appendChild(nameCell);
            row.appendChild(productCodeCell);
            row.appendChild(dimensionCell);
            row.appendChild(idcCell);
            
            // Add row to table
            tbody.appendChild(row);
        });
        
        console.log('Table populated with seed data');
        
        // Setup event handlers for the table rows
        setupTableRowEvents();
        
        // Sort table by Product Design Code
        sortTableDirectly(0);
    } else {
        console.log('Table already has data, no need to populate');
    }
}

// Function to sort table
function sortTableDirectly(columnIndex) {
    console.log("Sorting by column: " + columnIndex);
    
    var table = document.getElementById('productDesignTable');
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
        
        // Regular text sort with numeric consideration
        return textA.localeCompare(textB, undefined, { numeric: true });
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

// Function to select current row (Edit button)
function selectCurrentRow() {
    var selectedRow = document.querySelector('#productDesignTable tbody tr.selected');
    if (!selectedRow) {
        console.log("No row selected");
        alert("Please select a product design first");
        return;
    }
    
    console.log("Selected row for editing:", selectedRow);
    
    // Extract data from selected row
    const pdCode = selectedRow.getAttribute('data-pd-code');
    const pdName = selectedRow.getAttribute('data-pd-name');
    
    // Use the selectDesign function to set the values
    selectDesign(pdCode, pdName);
}

// Function to select a row
function selectRow(row) {
    // Remove highlight from all rows
    var allRows = document.querySelectorAll('#productDesignTable tbody tr');
    allRows.forEach(function(r) {
        r.classList.remove('selected');
        r.classList.remove('bg-blue-600', 'text-white');
    });
    
    // Add highlighting to selected row
    row.classList.add('selected');
    row.classList.add('bg-blue-600', 'text-white');
}

// Function to select the design data and close modal
function selectDesign(code, name) {
    document.querySelector('input[type="text"]').value = code;
    closeProductDesignModal();
}

// Function to open edit modal
function openEditProductDesignModal(row) {
    console.log('Opening edit product design modal for row:', row);
    
    const pdCode = row.getAttribute('data-pd-code');
    const pdName = row.getAttribute('data-pd-name');
    const productCode = row.getAttribute('data-product-code');
    const dimension = row.getAttribute('data-dimension');
    const idc = row.getAttribute('data-idc');
    
    console.log('Product design data:', { pdCode, pdName, productCode, dimension, idc });
    
    document.getElementById('edit_pd_code').value = pdCode;
    document.getElementById('edit_pd_name').value = pdName;
    document.getElementById('edit_product_code').value = productCode;
    document.getElementById('edit_dimension').value = dimension;
    document.getElementById('edit_idc').value = idc;
    
    const editModal = document.getElementById('editProductDesignModal');
    editModal.style.display = 'block';
    editModal.classList.remove('hidden');
    
    console.log('Edit modal opened');
}

// Function to close edit modal
function closeEditModal() {
    console.log('Closing edit product design modal');
    const editModal = document.getElementById('editProductDesignModal');
    editModal.classList.add('hidden');
    editModal.style.display = 'none';
}

// Function to display modal
function openProductDesignModal() {
    console.log('Opening product design modal');
    var modal = document.getElementById('productDesignTableWindow');
    if (!modal) {
        console.error('Modal element not found');
        return;
    }
    
    // Show modal
    modal.style.display = 'block';
    modal.classList.remove('hidden');
    
    // Populate table with seed data if needed
    populateProductDesignTable();
    
    // Sort by Product Design Code by default
    sortTableDirectly(0);
    
    console.log('Modal opened successfully');
}

// Function to close modal
function closeProductDesignModal() {
    var modal = document.getElementById('productDesignTableWindow');
    if (modal) {
        modal.style.display = 'none';
        modal.classList.add('hidden');
    }
}

function closeModalX() {
    closeProductDesignModal();
}

// Function to set up events on table rows
function setupTableRowEvents() {
    console.log('Setting up table row events');
    var tableRows = document.querySelectorAll('#productDesignTable tbody tr');
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
            selectDesign(this.getAttribute('data-pd-code'), this.getAttribute('data-pd-name'));
        };
    });
}

// Function to save product design changes
function saveProductDesignChanges() {
    console.log('Saving product design changes');
    
    const pdCode = document.getElementById('edit_pd_code').value;
    const pdName = document.getElementById('edit_pd_name').value;
    const productCode = document.getElementById('edit_product_code').value;
    const dimension = document.getElementById('edit_dimension').value;
    const idc = document.getElementById('edit_idc').value;
    
    console.log('Form data to save:', { pdCode, pdName, productCode, dimension, idc });
    
    // Display loading indicator on button and overlay
    const saveButton = document.querySelector('#editProductDesignForm button[type="submit"]');
    const originalText = saveButton.innerText;
    saveButton.innerText = 'Saving...';
    saveButton.disabled = true;
    
    // Show loading overlay
    document.getElementById('loadingOverlay').classList.remove('hidden');
    
    // Update row in table immediately to provide visual feedback
    const row = document.querySelector(`#productDesignTable tbody tr[data-pd-code="${pdCode}"]`);
    if (row) {
        console.log('Found row to update:', row);
        
        try {
            // Direct DOM manipulation for cell updates
            const cells = row.cells;
            console.log('Row has cells:', cells.length);
            
            if (cells.length >= 5) {
                // Update cell text directly
                cells[1].textContent = pdName;
                cells[2].textContent = productCode;
                cells[3].textContent = dimension;
                
                // For the IDC cell, update the span inside it
                const idcSpan = cells[4].querySelector('span');
                if (idcSpan) {
                    idcSpan.textContent = idc;
                } else {
                    cells[4].textContent = idc;
                }
                
                // Update data attributes
                row.setAttribute('data-pd-name', pdName);
                row.setAttribute('data-product-code', productCode);
                row.setAttribute('data-dimension', dimension);
                row.setAttribute('data-idc', idc);
                
                // Highlight row with Tailwind classes to ensure visibility
                row.classList.add('bg-blue-600', 'text-white');
                
                // Also update seedProductDesigns array to keep data in sync
                updateSeedProductDesignData(pdCode, pdName, productCode, dimension, idc);
            } else {
                console.error('Row does not have enough cells:', cells.length);
            }
        } catch (error) {
            console.error('Error updating row cells:', error);
        }
        
        console.log('Row updated successfully in the table');
    } else {
        console.error('Row not found in table for product design code:', pdCode);
    }
    
    // Show success message and close modal
    alert('Product design data updated successfully');
    closeEditModal();
    
    // Reset button state and hide loading overlay after a short delay
    setTimeout(() => {
        saveButton.innerText = originalText;
        saveButton.disabled = false;
        document.getElementById('loadingOverlay').classList.add('hidden');
    }, 500);
}

// Function to update data in seedProductDesigns array
function updateSeedProductDesignData(pdCode, pdName, productCode, dimension, idc) {
    // Find product design with matching code in seedProductDesigns array
    const designIndex = seedProductDesigns.findIndex(design => design.pd_code === pdCode);
    
    if (designIndex !== -1) {
        console.log(`Updating seedProductDesigns[${designIndex}] with new data`);
        
        // Update data in array
        seedProductDesigns[designIndex].pd_name = pdName;
        seedProductDesigns[designIndex].product_code = productCode;
        seedProductDesigns[designIndex].dimension = dimension;
        seedProductDesigns[designIndex].idc = idc;
        
        console.log('Updated seedProductDesigns:', seedProductDesigns[designIndex]);
    } else {
        console.log(`Product design with code ${pdCode} not found in seedProductDesigns array`);
        
        // If not found, add as new item
        seedProductDesigns.push({
            pd_code: pdCode,
            pd_name: pdName,
            product_code: productCode,
            dimension: dimension,
            idc: idc
        });
        
        console.log('Added new product design to seedProductDesigns array');
    }
}

// Function to make seed data available without database
function loadSeedData() {
    // Check if data is already loaded
    const tbody = document.querySelector('#productDesignTable tbody');
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
        // Fill table with data from seedProductDesigns
        populateProductDesignTable();
        
        // Show notification
        alert('Product design data loaded successfully');
        
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
                <p class="text-sm font-medium text-green-800">Data available: ${seedProductDesigns.length} product designs found (from JavaScript).</p>
            `;
        }
        
        // Open modal to display data
        openProductDesignModal();
    }, 1000);
}

// Initialize event handlers when document loads
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM content loaded for Product Design');
    
    // Setup row events initially
    setupTableRowEvents();
    
    // Initialize product design table if open button exists
    const showBtn = document.getElementById('showProductDesignTableBtn');
    if (showBtn) {
        console.log('Show button found, setting up event listener');
        showBtn.addEventListener('click', openProductDesignModal);
    }
    
    // Close modal when clicking outside table
    const productDesignModal = document.getElementById('productDesignTableWindow');
    if (productDesignModal) {
        productDesignModal.onclick = function(e) {
            if (e.target === productDesignModal) {
                closeProductDesignModal();
            }
        };
    }
    
    // Event to close modal when clicking outside
    window.onclick = function(event) {
        const editModal = document.getElementById('editProductDesignModal');
        if (event.target === editModal) {
            closeEditModal();
        }
    };
    
    // Add event listener for search functionality
    const searchInput = document.getElementById('searchProductDesignInput');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const rows = document.querySelectorAll('#productDesignTable tbody tr');
            
            rows.forEach(row => {
                // Skip the "no data" row if it exists
                if (row.querySelector('td[colspan]')) return;
                
                const pdCode = row.querySelector('td:nth-child(1)').textContent.toLowerCase();
                const pdName = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
                const productCode = row.querySelector('td:nth-child(3)').textContent.toLowerCase();
                const dimension = row.querySelector('td:nth-child(4)').textContent.toLowerCase();
                
                if (pdCode.includes(searchTerm) || 
                    pdName.includes(searchTerm) || 
                    productCode.includes(searchTerm) || 
                    dimension.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    }
});
