// Paper Quality Management JavaScript

document.addEventListener('DOMContentLoaded', function() {
    console.log('Paper Quality JS loaded');
    
    // Initialize search functionality
    initSearch();
    
    // Add event listeners to navigation buttons if they exist
    addButtonListeners();
    
    // Add event listeners to status radio buttons
    setupStatusRadioListeners();
});

function setupStatusRadioListeners() {
    const statusRadios = document.querySelectorAll('input[name="status"]');
    const isActiveField = document.getElementById('is_active');
    
    if (statusRadios.length > 0 && isActiveField) {
        statusRadios.forEach(radio => {
            radio.addEventListener('change', function() {
                // Update is_active field based on status selection
                isActiveField.value = (this.value === 'Act') ? '1' : '0';
            });
        });
    }
}

function initSearch() {
    const searchInput = document.getElementById('searchPaperQualityInput');
    if (searchInput) {
        searchInput.addEventListener('keyup', function() {
            const searchText = this.value.toLowerCase();
            const rows = document.querySelectorAll('#paperQualityTableBody tr');
            
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(searchText) ? '' : 'none';
            });
        });
    }
}

function addButtonListeners() {
    // Show modal button
    const showModalBtn = document.getElementById('showPaperQualityTableBtn');
    if (showModalBtn) {
        showModalBtn.addEventListener('click', openPaperQualityModal);
    }
    
    // Close modal buttons
    const closeButtons = document.querySelectorAll('[onclick="closeModal()"], [onclick="closeModalX()"]');
    closeButtons.forEach(button => {
        button.addEventListener('click', function() {
            closeModal();
        });
    });
    
    // Load seed data button
    const loadSeedBtn = document.querySelector('button[onclick="loadSeedData()"]');
    if (loadSeedBtn) {
        loadSeedBtn.addEventListener('click', loadSeedData);
    }
}

function openPaperQualityModal() {
    const modal = document.getElementById('paperQualityTableWindow');
    if (modal) {
        modal.classList.remove('hidden');
    }
}

function closeModal() {
    const modal = document.getElementById('paperQualityTableWindow');
    if (modal) {
        modal.classList.add('hidden');
    }
}

function closeModalX() {
    closeModal();
}

function selectRow(row) {
    // Highlight the selected row
    const rows = document.querySelectorAll('#paperQualityTableBody tr');
    rows.forEach(r => r.classList.remove('bg-blue-100'));
    row.classList.add('bg-blue-100');
}

function selectAndClosePaperQualityModal(row) {
    // Fill form with row data
    document.getElementById('paper_quality').value = row.getAttribute('data-paper-quality');
    document.getElementById('paper_name').value = row.getAttribute('data-paper-name');
    document.getElementById('weight_kg_m').value = row.getAttribute('data-weight-kg-m');
    document.getElementById('commercial_code').value = row.getAttribute('data-commercial-code') || '';
    document.getElementById('wet_end_code').value = row.getAttribute('data-wet-end-code') || '';
    document.getElementById('decc_code').value = row.getAttribute('data-decc-code') || '';
    
    const status = row.getAttribute('data-status');
    const statusRadios = document.querySelectorAll('input[name="status"]');
    
    if (statusRadios.length > 0) {
        statusRadios.forEach(radio => {
            radio.checked = (radio.value === status);
        });
    }
    
    // Update is_active hidden field based on status
    const isActiveField = document.getElementById('is_active');
    if (isActiveField) {
        isActiveField.value = (status === 'Act') ? '1' : '0';
    }
    
    closeModal();
}

function createNewPaperQuality() {
    // Clear form fields for a new entry
    const form = document.getElementById('paperQualityForm');
    if (form) {
        form.reset();
        
        // Set default values
        const isActiveField = document.getElementById('is_active');
        if (isActiveField) {
            isActiveField.value = '1';
        }
    }
    closeModal();
}

function editPaperQuality(id) {
    // Redirect to edit page
    window.location.href = `/paper-quality/${id}/edit`;
}

function loadSeedData() {
    // Show loading indicator
    const loadButton = event.target;
    const originalText = loadButton.innerHTML;
    loadButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Loading...';
    loadButton.disabled = true;
    
    // AJAX call to load seed data
    fetch('/run-paper-quality-seeder', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Show success message
            alert(`Data seeded successfully. ${data.count} paper qualities loaded.`);
            // Reload page to show the new data
            window.location.reload();
        } else {
            alert('Error: ' + data.message);
            loadButton.innerHTML = originalText;
            loadButton.disabled = false;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while seeding data.');
        loadButton.innerHTML = originalText;
        loadButton.disabled = false;
    });
} 