// SalespersonTeam.js - Functions for salesperson team management

// Function to populate search table with data from database
function populateSearchTable() {
    console.log('Populating search table');
    
    const tbody = document.querySelector('#searchSalespersonTable tbody');
    if (!tbody) {
        console.error('Table body not found');
        return;
    }

    // The table will be populated automatically from the blade template
    // using the @foreach loop with data from the database
    console.log('Search table will use data from database');
}

// Function to open search modal
function openSearchModal() {
    const modal = document.getElementById('searchModal');
    if (modal) {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }
}

// Function to close search modal
function closeSearchModal() {
    const modal = document.getElementById('searchModal');
    if (modal) {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
}

// Function to filter search table
function filterSearchTable(searchValue) {
    const searchValueLower = searchValue.toLowerCase();
    const rows = document.querySelectorAll('#searchSalespersonTable tbody tr');
    
    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        if (text.includes(searchValueLower)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

// Function to sort search table
function sortSearchTable(columnIndex) {
    console.log("Sorting search table by column: " + columnIndex);
    
    const table = document.getElementById('searchSalespersonTable');
    if (!table) {
        console.error("Search table not found");
        return;
    }
    
    const tbody = table.querySelector('tbody');
    if (!tbody) {
        console.error("Search table body not found");
        return;
    }
    
    const rows = Array.from(tbody.querySelectorAll('tr'));
    
    // Don't sort if there's no data or only a "no data" message
    if (rows.length === 0 || (rows.length === 1 && rows[0].querySelector('td[colspan]'))) {
        console.log("No data to sort");
        return;
    }
    
    // Sort rows based on column content
    rows.sort(function(a, b) {
        if (!a.cells[columnIndex] || !b.cells[columnIndex]) {
            return 0;
        }
        
        const textA = a.cells[columnIndex].textContent.trim();
        const textB = b.cells[columnIndex].textContent.trim();
        
        // Numeric-based sort for Code columns
        if (columnIndex === 0 || columnIndex === 2) {
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
}

// Function to select a row in search table
function selectSearchRow(row) {
    // Remove highlight from all rows
    const allRows = document.querySelectorAll('#searchSalespersonTable tbody tr');
    allRows.forEach(function(r) {
        r.classList.remove('bg-blue-600', 'text-white');
    });
    
    // Add highlighting to selected row
    row.classList.add('bg-blue-600', 'text-white');
}

// Function to select search result
function selectSearchResult(row = null) {
    // Jika row tidak dikirim, ambil yang ter-highlight
    if (!row) {
        row = document.querySelector('#searchSalespersonTable tbody tr.bg-blue-600');
    }
    if (!row) {
        alert("Please select a salesperson team first");
        return;
    }

    // Ambil data dari baris
    const code = row.getAttribute('data-code');
    const name = row.getAttribute('data-name');
    const teamCode = row.getAttribute('data-team-code');
    const teamName = row.getAttribute('data-team-name');
    const position = row.getAttribute('data-position');
    const id = row.getAttribute('data-id');

    // Tutup modal search
    closeSearchModal();

    // Get edit form and set data-id
    const editForm = document.getElementById('editForm');
    if (editForm) {
        editForm.setAttribute('data-id', id);
    }

    // Isi field di modal edit
    document.getElementById('edit_salesperson_code').value = code;
    document.getElementById('edit_salesperson_name').value = name;

    // Pilih sales team yang sesuai
    const teamSelect = document.getElementById('edit_sales_team_id');
    for (let i = 0; i < teamSelect.options.length; i++) {
        if (teamSelect.options[i].text.startsWith(teamCode)) {
            teamSelect.selectedIndex = i;
            break;
        }
    }

    // Pilih posisi yang sesuai
    const posSelect = document.getElementById('edit_position');
    for (let i = 0; i < posSelect.options.length; i++) {
        if (posSelect.options[i].value === position) {
            posSelect.selectedIndex = i;
            break;
        }
    }

    // Set action form edit (jika pakai form action dinamis)
    editForm.action = `/salesperson-team/${id}`;

    // Tampilkan modal edit
    showEditModal();
}

// Function to show/hide add modal
function showAddModal() {
    const modal = document.getElementById('addModal');
    if (modal) {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }
}

function hideAddModal() {
    const modal = document.getElementById('addModal');
    if (modal) {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
}

// Function to show/hide edit modal
function showEditModal() {
    const modal = document.getElementById('editModal');
    if (modal) {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }
}

function hideEditModal() {
    const modal = document.getElementById('editModal');
    if (modal) {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
}

// Function to show/hide delete modal
function showDeleteModal() {
    const modal = document.getElementById('deleteModal');
    if (modal) {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }
}

function hideDeleteModal() {
    const modal = document.getElementById('deleteModal');
    if (modal) {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
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

// Function to handle double-click on search table row
function handleRowDoubleClick(row) {
    const id = row.getAttribute('data-id');
    const code = row.getAttribute('data-code');
    const name = row.getAttribute('data-name');
    const teamCode = row.getAttribute('data-team-code');
    const teamName = row.getAttribute('data-team-name');
    const position = row.getAttribute('data-position');

    // Close search modal
    closeSearchModal();

    // Populate edit form
    const editForm = document.getElementById('editForm');
    editForm.setAttribute('data-id', id);
    document.getElementById('edit_s_person_code').value = code;
    document.getElementById('edit_salesperson_name').value = name;

    // Set sales team selection
    const salesTeamSelect = document.getElementById('edit_sales_team_id');
    for (let i = 0; i < salesTeamSelect.options.length; i++) {
        const option = salesTeamSelect.options[i];
        if (option.text.includes(teamCode)) {
            salesTeamSelect.selectedIndex = i;
            break;
        }
    }

    // Set position selection
    const positionSelect = document.getElementById('edit_position');
    for (let i = 0; i < positionSelect.options.length; i++) {
        const option = positionSelect.options[i];
        if (option.value === position) {
            positionSelect.selectedIndex = i;
            break;
        }
    }

    // Show edit modal
    document.getElementById('editModal').classList.remove('hidden');
}

// Function to save changes
function saveSalespersonTeamChanges(form) {
    event.preventDefault();
    
    const id = form.getAttribute('data-id');
    if (!id) {
        console.error('No ID found for form');
        alert('Error: No ID found for the record');
        return false;
    }

    const formData = new FormData(form);
    
    // Get selected sales team info
    const salesTeamSelect = document.getElementById('edit_sales_team_id');
    const selectedOption = salesTeamSelect.options[salesTeamSelect.selectedIndex];
    if (!selectedOption || !selectedOption.text) {
        alert('Please select a sales team');
        return false;
    }

    const [teamCode, teamName] = selectedOption.text.split(' - ');
    
    // Add team info to form data
    formData.append('st_code', teamCode);
    formData.append('sales_team_name', teamName);
    formData.append('sales_team_position', document.getElementById('edit_position').value);
    
    // Add CSRF token
    const token = document.querySelector('meta[name="csrf-token"]');
    if (!token) {
        console.error('CSRF token not found');
        alert('Error: CSRF token not found');
        return false;
    }
    formData.append('_token', token.getAttribute('content'));
    formData.append('_method', 'PUT');

    // Show loading state
    const saveButton = form.querySelector('button[type="button"]');
    const originalText = saveButton.innerHTML;
    saveButton.disabled = true;
    saveButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Saving...';

    fetch(`/salesperson-team/${id}`, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update the table row with new data
            const row = document.querySelector(`tr[data-id="${id}"]`);
            if (row) {
                row.setAttribute('data-code', formData.get('s_person_code'));
                row.setAttribute('data-name', formData.get('salesperson_name'));
                row.setAttribute('data-team-code', teamCode);
                row.setAttribute('data-team-name', teamName);
                row.setAttribute('data-position', formData.get('sales_team_position'));

                // Update visible cells
                const cells = row.cells;
                cells[0].textContent = formData.get('s_person_code');
                cells[1].textContent = formData.get('salesperson_name');
                cells[2].textContent = teamCode;
                cells[3].textContent = teamName;
                cells[4].textContent = formData.get('sales_team_position');
            }
            
            // Show success message and close modal
            alert('Data berhasil diperbarui');
            closeEditModal();
        } else {
            alert(data.message || 'Gagal memperbarui data');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat memperbarui data');
    })
    .finally(() => {
        // Restore button state
        saveButton.disabled = false;
        saveButton.innerHTML = originalText;
    });

    return false;
}

// Function to close edit modal
function closeEditModal() {
    document.getElementById('editModal').classList.add('hidden');
}

// Initialize when document loads
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM content loaded for Salesperson Team');
    
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

    // Setup edit form submission
    const editForm = document.getElementById('editForm');
    if (editForm) {
        editForm.addEventListener('submit', function(event) {
            event.preventDefault();
            saveSalespersonTeamChanges(this);
        });
    }
});
