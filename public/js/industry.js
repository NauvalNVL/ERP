// Industry.js - Functions for industry management

// Initialize event handlers when document loads
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM content loaded for Industry.js');
    
    // Industry Dialog
    const searchBtn = document.querySelector('button[type="button"] i.fas.fa-search').parentElement;
    const showIndustryTableBtn = document.getElementById('showIndustryTableBtn');
    const industryDialog = document.getElementById('industryDialog');
    const closeDialog = document.getElementById('closeDialog');
    const cancelSelectBtn = document.getElementById('cancelSelectBtn');
    const selectIndustryBtn = document.getElementById('selectIndustryBtn');
    const searchInput = document.getElementById('searchInput');
    const tableSearchInput = document.getElementById('tableSearchInput');
    const industryTableBody = document.getElementById('industryTableBody');

    // Industry Form Modal
    const addIndustryBtn = document.getElementById('addIndustryBtn');
    const industryFormModal = document.getElementById('industryFormModal');
    const closeFormModal = document.getElementById('closeFormModal');
    const cancelForm = document.getElementById('cancelForm');
    const industryForm = document.getElementById('industryForm');
    const modalTitle = document.getElementById('modalTitle');
    const methodField = document.getElementById('methodField');
    const codeInput = document.getElementById('code');
    const nameInput = document.getElementById('name');

    // Edit buttons
    const editButtons = document.querySelectorAll('.edit-btn');

    // Set up event listeners
    setupEventListeners();

    function setupEventListeners() {
        // Open industry modal
        if (showIndustryTableBtn) {
            showIndustryTableBtn.addEventListener('click', openIndustryModal);
        }

        if (searchBtn) {
            searchBtn.addEventListener('click', openIndustryModal);
        }

        // Close industry dialog
        if (closeDialog && cancelSelectBtn) {
            [closeDialog, cancelSelectBtn].forEach(btn => {
                btn.addEventListener('click', () => closeIndustryModal());
            });
        }

        // Add industry modal
        if (addIndustryBtn) {
            addIndustryBtn.addEventListener('click', showAddIndustryModal);
        }

        // Close industry form modal
        if (closeFormModal && cancelForm) {
            [closeFormModal, cancelForm].forEach(btn => {
                btn.addEventListener('click', closeIndustryFormModal);
            });
        }

        // Edit industry
        if (editButtons && editButtons.length > 0) {
            editButtons.forEach(btn => {
                btn.addEventListener('click', function() {
                    const industryId = this.dataset.id;
                    editIndustry(industryId);
                });
            });
        }

        // Filter industry table
        if (tableSearchInput && industryTableBody) {
            tableSearchInput.addEventListener('input', filterIndustryTable);
        }

        // Select industry row and handle double-click
        if (industryTableBody) {
            const tableRows = industryTableBody.querySelectorAll('tr');
            tableRows.forEach(row => {
                row.addEventListener('click', function() {
                    selectIndustryRow(this);
                });

                row.addEventListener('dblclick', function() {
                    selectAndCloseModal(this);
                });
            });
        }

        // Select button in dialog
        if (selectIndustryBtn) {
            selectIndustryBtn.addEventListener('click', selectIndustryFromTable);
        }

        // Close modals when clicking outside
        window.addEventListener('click', function(event) {
            if (event.target === industryDialog) {
                closeIndustryModal();
            }
            if (event.target === industryFormModal) {
                closeIndustryFormModal();
            }
        });
    }

    // Open industry modal
    function openIndustryModal() {
        if (industryDialog) {
            industryDialog.classList.remove('hidden');
            
            // Focus search input
            if (tableSearchInput) {
                setTimeout(() => {
                    tableSearchInput.focus();
                }, 100);
            }
        }
    }

    // Close industry modal
    function closeIndustryModal() {
        if (industryDialog) {
            industryDialog.classList.add('hidden');
        }
    }

    // Show add industry modal
    function showAddIndustryModal() {
        if (modalTitle) modalTitle.textContent = 'Add Industry';
        if (methodField) methodField.innerHTML = '';
        if (industryForm) {
            // Get the base URL from the action attribute
            const baseUrl = industryForm.getAttribute('data-store-url') || '/industry';
            industryForm.action = baseUrl;
        }
        if (codeInput) codeInput.value = '';
        if (nameInput) nameInput.value = '';
        if (industryFormModal) industryFormModal.classList.remove('hidden');
    }

    // Close industry form modal
    function closeIndustryFormModal() {
        if (industryFormModal) {
            industryFormModal.classList.add('hidden');
        }
    }

    // Edit industry
    async function editIndustry(industryId) {
        if (modalTitle) modalTitle.textContent = 'Edit Industry';
        if (methodField) methodField.innerHTML = `<input type="hidden" name="_method" value="PUT">`;
        if (industryForm) {
            const baseUrl = industryForm.getAttribute('data-base-url') || '/industry';
            industryForm.action = `${baseUrl}/${industryId}`;
        }

        try {
            // Show loading overlay
            showLoading();
            
            const response = await fetch(`/industry/${industryId}/edit`);
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            const industry = await response.json();
            
            if (codeInput) codeInput.value = industry.code;
            if (nameInput) nameInput.value = industry.name;
            
            if (industryFormModal) industryFormModal.classList.remove('hidden');
        } catch (error) {
            console.error('Error fetching industry data:', error);
            alert('Error loading industry data. Please try again.');
        } finally {
            // Hide loading overlay
            hideLoading();
        }
    }

    // Filter industry table
    function filterIndustryTable() {
        const searchTerm = tableSearchInput.value.toLowerCase();
        const rows = industryTableBody.querySelectorAll('tr');

        rows.forEach(row => {
            const codeCell = row.cells[0];
            const nameCell = row.cells[1];
            
            if (codeCell && nameCell) {
                const codeText = codeCell.textContent || codeCell.innerText;
                const nameText = nameCell.textContent || nameCell.innerText;
                
                if (codeText.toLowerCase().includes(searchTerm) || 
                    nameText.toLowerCase().includes(searchTerm)) {
                    row.style.display = "";
                } else {
                    row.style.display = "none";
                }
            }
        });
    }

    // Select industry row
    function selectIndustryRow(row) {
        const tableRows = industryTableBody.querySelectorAll('tr');
        tableRows.forEach(r => {
            r.classList.remove('bg-blue-600', 'text-white');
        });
        row.classList.add('bg-blue-600', 'text-white');
    }

    // Select and close modal (double-click)
    function selectAndCloseModal(row) {
        const codeCell = row.cells[0];
        if (searchInput && codeCell) {
            searchInput.value = codeCell.textContent.trim();
            closeIndustryModal();
        }
    }

    // Select industry from table (select button)
    function selectIndustryFromTable() {
        if (industryTableBody) {
            const selectedRow = industryTableBody.querySelector('tr.bg-blue-600');
            if (selectedRow) {
                const codeCell = selectedRow.cells[0];
                if (searchInput && codeCell) {
                    searchInput.value = codeCell.textContent.trim();
                    closeIndustryModal();
                }
            } else {
                alert('Please select an industry first');
            }
        }
    }

    // Show loading overlay
    function showLoading() {
        const loadingOverlay = document.getElementById('loadingOverlay');
        if (loadingOverlay) {
            loadingOverlay.classList.remove('hidden');
        }
    }

    // Hide loading overlay
    function hideLoading() {
        const loadingOverlay = document.getElementById('loadingOverlay');
        if (loadingOverlay) {
            loadingOverlay.classList.add('hidden');
        }
    }
});
