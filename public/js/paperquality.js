// Paper Quality Management JavaScript

document.addEventListener('DOMContentLoaded', function() {
    console.log('Paper Quality JS loaded');
    
    // Initialize search functionality
    initSearch();
    
    // Add event listeners to navigation buttons if they exist
    addButtonListeners();
});

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
    document.getElementById('code').value = row.getAttribute('data-quality-code');
    document.getElementById('name').value = row.getAttribute('data-quality-name');
    document.getElementById('description').value = row.getAttribute('data-description');
    document.getElementById('gsm').value = row.getAttribute('data-gsm');
    document.getElementById('paper_type').value = row.getAttribute('data-paper-type');
    
    const isActive = row.getAttribute('data-is-active') === 'true';
    document.querySelector('input[name="is_active"][value="1"]').checked = isActive;
    document.querySelector('input[name="is_active"][value="0"]').checked = !isActive;
    
    closeModal();
}

function createNewPaperQuality() {
    // Clear form fields for a new entry
    const form = document.getElementById('paperQualityForm');
    if (form) {
        form.reset();
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