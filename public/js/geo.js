// Geo.js - Functions for geographical data management

// Global variables
let selectedRow = null;
let geoData = [];

// Initialize when document is ready
document.addEventListener('DOMContentLoaded', function() {
    console.log('Initializing geo.js');
    
    // Initialize geo data
    initializeGeoData();
    
    // Setup sort buttons
    setupSortButtons();
    
    // Setup modal triggers
    const showGeoTableBtn = document.getElementById('showGeoTableBtn');
    if (showGeoTableBtn) {
        showGeoTableBtn.addEventListener('click', function() {
            const geoModal = document.getElementById('geoModal');
            if (geoModal) {
                geoModal.classList.remove('hidden');
                // Refresh data when opening modal
                initializeGeoData();
            }
        });
    }
    
    // Setup review button
    const btnReview = document.getElementById('btnReview');
    if (btnReview) {
        btnReview.addEventListener('click', function(e) {
            e.preventDefault();
            reviewGeoData();
        });
    }
    
    // Setup modal close buttons
    document.querySelectorAll('[onclick*="toggleModal"]').forEach(button => {
        const modalId = button.getAttribute('onclick').match(/'([^']+)'/)?.[1];
        if (modalId) {
            button.onclick = (e) => {
                e.preventDefault();
                const modal = document.getElementById(modalId);
                if (modal) {
                    modal.classList.add('hidden');
                }
            };
        }
    });
    
    // Setup click outside modal to close
    window.addEventListener('click', function(e) {
        const geoModal = document.getElementById('geoModal');
        const editGeoModal = document.getElementById('editGeoModal');
        const reviewGeoModal = document.getElementById('reviewGeoModal');
        
        [geoModal, editGeoModal, reviewGeoModal].forEach(modal => {
            if (modal && e.target === modal) {
                modal.classList.add('hidden');
            }
        });
    });
    
    // Setup search functionality
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            filterGeoTable(this.value);
        });
    }
});

// Initialize geo data from database
function initializeGeoData() {
    const tbody = document.querySelector('#geoTableBody');
    if (!tbody) return;
    
    // Show loading message
    tbody.innerHTML = '<tr><td colspan="7" class="px-4 py-4 text-center text-gray-500">Loading data...</td></tr>';
    
    // Get CSRF token
    const token = document.querySelector('meta[name="csrf-token"]')?.content;
    
    if (!token) {
        tbody.innerHTML = '<tr><td colspan="7" class="px-4 py-4 text-center text-red-500">Error: CSRF token not found</td></tr>';
        return;
    }
    
    // Fetch data from the database
    fetch('/geo', {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': token
        },
        credentials: 'same-origin'
    })
    .then(response => {
        if (!response.ok) {
            return response.text().then(text => {
                throw new Error(`HTTP error! status: ${response.status}, body: ${text}`);
            });
        }
        return response.json();
    })
    .then(data => {
        // Store data globally
        window.geoData = data;
        
        // Clear loading message
        tbody.innerHTML = '';
        
        if (!Array.isArray(data)) {
            if (data.error) {
                throw new Error(data.error);
            }
            throw new Error('Invalid response format');
        }
        
        if (data.length === 0) {
            tbody.innerHTML = '<tr><td colspan="7" class="px-4 py-4 text-center text-gray-500">Tidak ada data geo yang tersedia.</td></tr>';
            return;
        }

        // Fill table with data
        data.forEach((geo, index) => {
            const row = document.createElement('tr');
            row.className = 'hover:bg-blue-50 cursor-pointer';
            row.setAttribute('data-code', geo.code);
            row.setAttribute('data-area', geo.area);
            
            // Add click handlers
            row.onclick = (e) => {
                e.stopPropagation();
                selectGeoRow(row);
            };
            
            row.ondblclick = (e) => {
                e.stopPropagation();
                editGeoData(geo.code);
            };
            
            row.innerHTML = `
                <td class="px-4 py-3 whitespace-nowrap">${index + 1}</td>
                <td class="px-4 py-3 whitespace-nowrap font-medium text-gray-900">${geo.code}</td>
                <td class="px-4 py-3 whitespace-nowrap text-gray-700">${geo.country}</td>
                <td class="px-4 py-3 whitespace-nowrap text-gray-700">${geo.state}</td>
                <td class="px-4 py-3 whitespace-nowrap text-gray-700">${geo.town}</td>
                <td class="px-4 py-3 whitespace-nowrap text-gray-700">${geo.town_section}</td>
                <td class="px-4 py-3 whitespace-nowrap">
                    <span class="px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">${geo.area}</span>
                </td>
            `;
            
            tbody.appendChild(row);
        });
        
        // Update status message
        const dbStatusElement = document.querySelector('.bg-yellow-100, .bg-green-100');
        if (dbStatusElement) {
            dbStatusElement.className = 'mt-4 bg-green-100 p-3 rounded';
            dbStatusElement.innerHTML = `
                <p class="text-sm font-medium text-green-800">Data tersedia: ${data.length} geo ditemukan.</p>
            `;
        }

        // Setup event handlers for the table
        setupTableEventHandlers();
    })
    .catch(error => {
        console.error('Error fetching geo data:', error);
        tbody.innerHTML = `<tr><td colspan="7" class="px-4 py-4 text-center text-red-500">
            Error loading data from database: ${error.message}
            <br>
            <button onclick="initializeGeoData()" class="mt-2 px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                <i class="fas fa-sync-alt mr-2"></i>Coba Lagi
            </button>
        </td></tr>`;
    });
}

// Function to setup modal triggers
function setupModalTriggers() {
    // Setup show table button
    const showTableBtn = document.getElementById('showGeoTableBtn');
    if (showTableBtn) {
        showTableBtn.addEventListener('click', function() {
            toggleModal('geoModal');
        });
    }
    
    // Setup close buttons
    document.querySelectorAll('[data-modal-close]').forEach(button => {
        button.addEventListener('click', function() {
            const modalId = this.getAttribute('data-modal-close');
            toggleModal(modalId);
        });
    });
}

// Function to setup table event handlers
function setupTableEventHandlers() {
    const tbody = document.querySelector('#geoTableBody');
    if (!tbody) return;

    // Remove existing event listeners
    const oldRows = tbody.getElementsByTagName('tr');
    Array.from(oldRows).forEach(row => {
        row.onclick = null;
        row.ondblclick = null;
    });

    // Add new event listeners
    const rows = tbody.getElementsByTagName('tr');
    Array.from(rows).forEach(row => {
        if (!row.querySelector('td[colspan]')) { // Skip message rows
            row.onclick = (e) => {
                e.stopPropagation();
                selectGeoRow(row);
            };
            
            row.ondblclick = (e) => {
                e.stopPropagation();
                const code = row.getAttribute('data-code');
                if (code) {
                    editGeoData(code);
                }
            };
        }
    });
}

// Function to initialize search functionality
function initializeSearch() {
    const searchInput = document.querySelector('#geoModal input[type="search"]');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            filterTable(this.value);
        });
    }
}

// Function to toggle modal visibility
function toggleModal(modalId) {
    console.log('Toggling modal:', modalId);
    const modal = document.getElementById(modalId);
    if (!modal) {
        console.error('Modal not found:', modalId);
        return;
    }
    
    const isHidden = modal.classList.contains('hidden');
    
    // Hide all other modals first
    ['geoModal', 'editGeoModal', 'reviewGeoModal'].forEach(id => {
        if (id !== modalId) {
            const otherModal = document.getElementById(id);
            if (otherModal) {
                otherModal.classList.add('hidden');
            }
        }
    });
    
    // Toggle target modal
    if (isHidden) {
        modal.classList.remove('hidden');
        if (modalId === 'geoModal') {
            // Refresh data when opening main geo modal
            initializeGeoData();
        }
    } else {
        modal.classList.add('hidden');
    }
    
    console.log(`Modal ${modalId} is now ${isHidden ? 'visible' : 'hidden'}`);
}

// Function to edit geo data
function editGeoData(code) {
    console.log('Editing geo data for code:', code);
    
    // Find the geo item in data
    const geoItem = window.geoData?.find(item => item.code === code);
    
    if (geoItem) {
        // Fill the form with data
        const editGeoCode = document.getElementById('editGeoCode');
        if (!editGeoCode) {
            console.error('Edit form elements not found');
            showNotification('error', 'Terjadi kesalahan saat membuka form edit');
            return;
        }

        try {
            // Fill form fields
            editGeoCode.value = geoItem.code;
            editGeoCode.setAttribute('data-original-code', geoItem.code);
            
            document.getElementById('editCountry').value = geoItem.country;
            document.getElementById('editState').value = geoItem.state;
            document.getElementById('editTown').value = geoItem.town;
            document.getElementById('editTownSection').value = geoItem.town_section;
            document.getElementById('editGeoArea').value = geoItem.area;
            
            // Close geo table modal first if it's open
            const geoModal = document.getElementById('geoModal');
            if (geoModal && !geoModal.classList.contains('hidden')) {
                geoModal.classList.add('hidden');
            }
            
            // Show edit modal
            const editModal = document.getElementById('editGeoModal');
            if (editModal) {
                editModal.classList.remove('hidden');
                console.log('Edit modal opened successfully');
            } else {
                console.error('Edit modal element not found');
                showNotification('error', 'Terjadi kesalahan saat membuka form edit');
            }
        } catch (error) {
            console.error('Error while setting up edit form:', error);
            showNotification('error', 'Terjadi kesalahan saat menyiapkan form edit');
        }
    } else {
        console.error('Geo data not found for code:', code);
        showNotification('error', 'Data geo tidak ditemukan');
    }
}

// Function to select a row in the table
function selectGeoRow(row) {
    console.log('Selecting row:', row);
    
    // Remove highlight from previously selected row
    const previouslySelected = document.querySelector('#geoTableBody tr.selected');
    if (previouslySelected) {
        previouslySelected.classList.remove('selected', 'bg-blue-100');
    }
    
    // Add highlight to selected row
    row.classList.add('selected', 'bg-blue-100');
    
    // Update the main input field with the selected code
    const code = row.getAttribute('data-code');
    if (code) {
        document.getElementById('geoCode').value = code;
    }
}

// Function to filter table based on search input
function filterTable(searchTerm) {
    const tbody = document.querySelector('#geoTableBody');
    if (!tbody) return;
    
    const rows = tbody.getElementsByTagName('tr');
    const searchTermLower = searchTerm.toLowerCase();
    let hasResults = false;
    
    for (const row of rows) {
        // Skip "no results" row
        if (row.classList.contains('no-results')) continue;
        
        const text = row.textContent.toLowerCase();
        if (text.includes(searchTermLower)) {
            row.style.display = '';
            hasResults = true;
        } else {
            row.style.display = 'none';
        }
    }
    
    // Show/hide no results message
    let noResultsRow = tbody.querySelector('.no-results');
    if (!hasResults) {
        if (!noResultsRow) {
            noResultsRow = document.createElement('tr');
            noResultsRow.className = 'no-results';
            noResultsRow.innerHTML = `
                <td colspan="7" class="px-4 py-4 text-center text-gray-500">
                    Tidak ada hasil untuk pencarian "${searchTerm}"
                </td>
            `;
            tbody.appendChild(noResultsRow);
        } else {
            noResultsRow.style.display = '';
        }
    } else if (noResultsRow) {
        noResultsRow.style.display = 'none';
    }
}

// Function to search for geo code
function searchGeoCode(code) {
    if (code.trim() === '') {
        document.getElementById('geoCodeResult').innerHTML = '';
        return;
    }
    
    // Search for matches in geoData
    const matches = geoData.filter(item => 
        item.code.toLowerCase().includes(code.toLowerCase())
    );
    
    displaySearchResults(code, matches);
}

// Display search results in the result div
function displaySearchResults(code, matches) {
    const resultDiv = document.getElementById('geoCodeResult');
    
    if (matches.length > 0) {
        if (matches.length === 1 && matches[0].code.toLowerCase() === code.toLowerCase()) {
            // Exact match - show details and edit button
            resultDiv.innerHTML = `
                <div class="p-3 bg-green-100 rounded-lg mt-2">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-sm text-green-800">Data ditemukan: <span class="font-semibold">${matches[0].code}</span> - ${matches[0].area}</p>
                            <p class="text-xs text-green-700">${matches[0].country}, ${matches[0].state}, ${matches[0].town}</p>
                        </div>
                        <button class="px-2 py-1 bg-blue-500 text-white text-xs rounded hover:bg-blue-600" 
                            onclick="editGeoData('${matches[0].code}')">
                            Edit
                        </button>
                    </div>
                </div>
            `;
        } else if (matches.length <= 5) {
            // Multiple matches (up to 5) - show list
            let html = `<div class="p-3 bg-blue-100 rounded-lg mt-2">
                <p class="text-sm text-blue-800 mb-2">Beberapa kode ditemukan:</p>
                <ul class="space-y-1">`;
            
            matches.forEach(match => {
                html += `<li class="flex justify-between items-center">
                    <button class="text-sm text-blue-700 hover:text-blue-900 hover:underline focus:outline-none" 
                        onclick="selectGeoByCode('${match.code}')">
                        ${match.code} - ${match.area}
                    </button>
                    <button class="px-2 py-1 bg-blue-500 text-white text-xs rounded hover:bg-blue-600" 
                        onclick="editGeoData('${match.code}')">
                        Edit
                    </button>
                </li>`;
            });
            
            html += `</ul></div>`;
            resultDiv.innerHTML = html;
        } else {
            // Too many matches
            resultDiv.innerHTML = `
                <div class="p-3 bg-yellow-100 rounded-lg mt-2">
                    <p class="text-sm text-yellow-800">Ditemukan ${matches.length} kode yang cocok. Silakan lebih spesifik atau gunakan tombol Cari Data.</p>
                </div>
            `;
        }
    } else {
        // No matches - show create option
        resultDiv.innerHTML = `
            <div class="p-3 bg-red-100 rounded-lg mt-2">
                <div class="flex justify-between items-start">
                    <p class="text-sm text-red-800">Tidak ditemukan kode geo "${code}". Periksa kembali kode atau buat data baru.</p>
                    <button class="px-2 py-1 bg-green-500 text-white text-xs rounded hover:bg-green-600" 
                        onclick="createNewGeo('${code}')">
                        Buat Baru
                    </button>
                </div>
            </div>
        `;
    }
}

// Function to select geo by code
function selectGeoByCode(code) {
    document.getElementById('geoCode').value = code;
    
    const match = geoData.find(item => item.code === code);
    if (match) {
        document.getElementById('geoCodeResult').innerHTML = `
            <div class="p-3 bg-green-100 rounded-lg mt-2">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm text-green-800">Data dipilih: <span class="font-semibold">${match.code}</span> - ${match.area}</p>
                        <p class="text-xs text-green-700">${match.country}, ${match.state}, ${match.town}</p>
                    </div>
                    <button class="px-2 py-1 bg-blue-500 text-white text-xs rounded hover:bg-blue-600" 
                        onclick="editGeoData('${match.code}')">
                        Edit
                    </button>
                </div>
            </div>
        `;
    }
}

// Function to create new geo
function createNewGeo(code) {
    // Reset form
    document.getElementById('editGeoForm').reset();
    
    // Set the code field
    document.getElementById('editGeoCode').value = code;
    
    // Show the edit modal
    toggleModal('editGeoModal');
}

// Function to review geo data before saving
function reviewGeoData() {
    // Get form data
    const geoCode = document.getElementById('editGeoCode').value;
    const country = document.getElementById('editCountry').value;
    const state = document.getElementById('editState').value;
    const town = document.getElementById('editTown').value;
    const townSection = document.getElementById('editTownSection').value;
    const geoArea = document.getElementById('editGeoArea').value;

    // Validate data
    if (!geoCode || !country) {
        showNotification('error', 'Kode Geo dan Negara harus diisi!');
        return;
    }
    
    // Fill review modal fields
    document.getElementById('reviewGeoCode').textContent = geoCode;
    document.getElementById('reviewCountry').textContent = country;
    document.getElementById('reviewState').textContent = state;
    document.getElementById('reviewTown').textContent = town;
    document.getElementById('reviewTownSection').textContent = townSection;
    document.getElementById('reviewGeoArea').textContent = geoArea;
    
    // Hide edit modal, show review modal
    toggleModal('editGeoModal');
    toggleModal('reviewGeoModal');
}

// Function to confirm and save data
function confirmAndSave() {
    toggleModal('reviewGeoModal');
    saveGeoData();
}

// Function to validate geo code before saving
function validateGeoCode(code, isEdit = false, originalCode = null) {
    // If editing, skip validation if code hasn't changed
    if (isEdit && code === originalCode) {
        return { valid: true };
    }

    // Check if code already exists in current data
    const existingGeo = window.geoData?.find(item => item.code === code);
    if (existingGeo) {
        return {
            valid: false,
            message: 'Kode geo sudah digunakan. Silakan gunakan kode lain.'
        };
    }
    return { valid: true };
}

// Function to save geo data
function saveGeoData() {
    // Get form data
    const geoCode = document.getElementById('editGeoCode').value;
    const originalCode = document.getElementById('editGeoCode').getAttribute('data-original-code');
    const country = document.getElementById('editCountry').value;
    const state = document.getElementById('editState').value;
    const town = document.getElementById('editTown').value;
    const townSection = document.getElementById('editTownSection').value;
    const geoArea = document.getElementById('editGeoArea').value;
    
    // Basic validation
    if (!geoCode || !country) {
        showNotification('error', 'Kode Geo dan Negara harus diisi!');
        return;
    }

    // Validate geo code format
    if (!/^[A-Z0-9]+$/i.test(geoCode)) {
        showNotification('error', 'Kode Geo hanya boleh berisi huruf dan angka');
        return;
    }

    // Show loading overlay with message
    const loadingOverlay = document.getElementById('loadingOverlay');
    if (loadingOverlay) {
        loadingOverlay.innerHTML = `
            <div class="fixed inset-0 bg-black bg-opacity-50 flex flex-col justify-center items-center z-50">
                <div class="w-12 h-12 border-4 border-solid border-blue-500 border-t-transparent rounded-full animate-spin mb-4"></div>
                <p class="text-white text-lg">Menyimpan perubahan...</p>
            </div>
        `;
        loadingOverlay.classList.remove('hidden');
    }

    // Cache the current table state
    const currentTableState = document.querySelector('#geoTableBody').innerHTML;
    
    // Prepare data for API
    const geoData = {
        code: geoCode,
        country: country,
        state: state,
        town: town,
        town_section: townSection,
        area: geoArea
    };
        
    // Determine if this is a new record or update
    const isEdit = originalCode != null;
    const isNew = !isEdit;

    // Validate code uniqueness
    const validation = validateGeoCode(geoCode, isEdit, originalCode);
    if (!validation.valid) {
        showNotification('error', validation.message);
        if (loadingOverlay) loadingOverlay.classList.add('hidden');
        return;
    }

    const method = isNew ? 'POST' : 'PUT';
    const url = isNew ? '/geo' : `/geo/${originalCode || geoCode}`;
    
    // Send request to server
    fetch(url, {
        method: method,
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify(geoData)
    })
    .then(response => {
        if (!response.ok) {
            return response.json().then(err => {
                throw new Error(err.message || 'Terjadi kesalahan saat menyimpan data');
            });
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            // Update local data
            if (isNew) {
                window.geoData = window.geoData || [];
                window.geoData.push(geoData);
            } else {
                const index = window.geoData.findIndex(item => item.code === originalCode);
                if (index !== -1) {
                    window.geoData[index] = geoData;
                }
            }

            // Update UI immediately
            updateTableRow(geoData, isNew, originalCode);
            
            // Set code to input field
            document.getElementById('geoCode').value = geoCode;
        
            // Show success message
            document.getElementById('geoCodeResult').innerHTML = `
                <div class="p-3 bg-green-100 rounded-lg mt-2">
                    <p class="text-sm text-green-800">Data berhasil ${isNew ? 'ditambahkan' : 'diperbarui'}: <span class="font-semibold">${geoCode}</span> - ${geoArea}</p>
                </div>
            `;
            
            // Close modal
            toggleModal('editGeoModal');
            
            // Show success notification
            showNotification('success', `Data geo berhasil ${isNew ? 'ditambahkan' : 'diperbarui'}!`);
        } else {
            throw new Error(data.message || 'Terjadi kesalahan saat menyimpan data');
        }
    })
    .catch(error => {
        console.error('Error saving geo data:', error);
        // Restore previous table state on error
        document.querySelector('#geoTableBody').innerHTML = currentTableState;
        
        // Show user-friendly error message
        let errorMessage = error.message;
        if (error.message.includes('already been taken')) {
            errorMessage = 'Kode geo sudah digunakan. Silakan gunakan kode lain.';
        }
        showNotification('error', errorMessage);
    })
    .finally(() => {
        // Hide loading overlay
        if (loadingOverlay) {
            loadingOverlay.classList.add('hidden');
        }
    });
}

// Function to update a single table row
function updateTableRow(geoData, isNew, originalCode = null) {
    const tbody = document.querySelector('#geoTableBody');
    const existingRow = tbody.querySelector(`tr[data-code="${originalCode || geoData.code}"]`);
    
    const row = document.createElement('tr');
    row.className = 'hover:bg-blue-50 cursor-pointer';
    row.setAttribute('data-code', geoData.code);
    row.setAttribute('data-area', geoData.area);
    row.onclick = function() { selectGeoRow(this); };
    row.ondblclick = function() { editGeoData(geoData.code); };
    
    const rowNumber = isNew ? tbody.children.length + 1 : Array.from(tbody.children).indexOf(existingRow) + 1;
    
    row.innerHTML = `
        <td class="px-4 py-3 whitespace-nowrap">${rowNumber}</td>
        <td class="px-4 py-3 whitespace-nowrap font-medium text-gray-900">${geoData.code}</td>
        <td class="px-4 py-3 whitespace-nowrap text-gray-700">${geoData.country}</td>
        <td class="px-4 py-3 whitespace-nowrap text-gray-700">${geoData.state}</td>
        <td class="px-4 py-3 whitespace-nowrap text-gray-700">${geoData.town}</td>
        <td class="px-4 py-3 whitespace-nowrap text-gray-700">${geoData.town_section}</td>
        <td class="px-4 py-3 whitespace-nowrap">
            <span class="px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">${geoData.area}</span>
        </td>
    `;
    
    if (existingRow) {
        tbody.replaceChild(row, existingRow);
    } else {
        tbody.appendChild(row);
    }
}

// Function to show notification with auto-dismiss and optional action button
function showNotification(type, message, action = null) {
    // Remove any existing notifications first
    const existingNotifications = document.querySelectorAll('.notification-toast');
    existingNotifications.forEach(notification => notification.remove());

    const notification = document.createElement('div');
    notification.className = `notification-toast fixed bottom-4 right-4 p-4 rounded-lg shadow-lg z-50 ${
        type === 'success' ? 'bg-green-500' : 'bg-red-500'
    } text-white max-w-md transform transition-all duration-300 ease-in-out`;
    
    let notificationContent = `
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'} mr-2"></i>
                <p class="pr-4">${message}</p>
            </div>
            <button onclick="this.parentElement.parentElement.remove()" class="ml-4 text-white hover:text-gray-200">
                <i class="fas fa-times"></i>
            </button>
        </div>
    `;

    if (action) {
        notificationContent += `
            <div class="mt-2 flex justify-end">
                <button onclick="${action.handler}" class="px-4 py-2 bg-white text-${
                    type === 'success' ? 'green' : 'red'
                }-500 rounded hover:bg-gray-100">
                    ${action.text}
                </button>
            </div>
        `;
    }

    notification.innerHTML = notificationContent;
    document.body.appendChild(notification);

    // Animate in
    setTimeout(() => {
        notification.classList.add('translate-y-0');
        notification.classList.remove('translate-y-full');
    }, 100);

    // Auto dismiss after 5 seconds if it's a success message
    if (type === 'success') {
        setTimeout(() => {
            notification.classList.add('translate-y-full');
            notification.classList.add('opacity-0');
            setTimeout(() => notification.remove(), 300);
        }, 5000);
    }
}

// Function to confirm selection and close modal
function selectGeo() {
    if (selectedRow) {
        const code = selectedRow.getAttribute('data-code');
        const area = selectedRow.getAttribute('data-area');
        
        // Set the code to the input field
        document.getElementById('geoCode').value = code;
        
        // Close modal
        toggleModal('geoModal');
        
        // Reset selection
        selectedRow = null;
        
        // Show the search result for the selected code
        searchGeoCode(code);
    } else {
        alert('Silakan pilih data terlebih dahulu!');
    }
}

// Filter table based on search input
function filterGeoTable(searchValue) {
    searchValue = searchValue.toLowerCase();
    const rows = document.getElementById('geoTableBody').getElementsByTagName('tr');
    let visibleCount = 0;
    
    for (let i = 0; i < rows.length; i++) {
        const cols = rows[i].getElementsByTagName('td');
        let matchFound = false;
        
        // Skip if row is "no data" message
        if (cols.length === 1 && cols[0].colSpan === 7) continue;
        
        // Check all columns for a match
        for (let j = 0; j < cols.length; j++) {
            if (cols[j].textContent.toLowerCase().includes(searchValue)) {
                matchFound = true;
                break;
            }
        }
        
        // Show or hide row based on match
        if (matchFound) {
            rows[i].style.display = '';
            visibleCount++;
        } else {
            rows[i].style.display = 'none';
        }
    }
    
    // Show "no results" message if needed
    updateNoResultsMessage(searchValue, visibleCount);
    
    return visibleCount;
}

// Update or create the "no results" message
function updateNoResultsMessage(searchTerm, visibleCount) {
    const tbody = document.getElementById('geoTableBody');
    let emptyRow = document.querySelector('#geoTableBody tr.empty-message');
    
    if (visibleCount === 0) {
        if (!emptyRow) {
            emptyRow = document.createElement('tr');
            emptyRow.className = 'empty-message';
            emptyRow.innerHTML = '<td colspan="7" class="py-6 px-6 text-center text-gray-500">Tidak ditemukan hasil untuk "' + searchTerm + '"</td>';
            tbody.appendChild(emptyRow);
        } else {
            emptyRow.style.display = '';
            emptyRow.querySelector('td').textContent = 'Tidak ditemukan hasil untuk "' + searchTerm + '"';
        }
    } else if (emptyRow) {
        emptyRow.style.display = 'none';
    }
}

// Select a single result when there's only one row visible
function selectSingleResult() {
    const rows = Array.from(document.querySelectorAll('#geoTableBody tr')).filter(row => 
        row.style.display !== 'none' && !row.classList.contains('empty-message')
    );
    
    if (rows.length === 1) {
        selectGeoRow(rows[0]);
        selectGeo();
    }
}

// Handle keyboard navigation in the modal
function handleKeyboardNavigation(e) {
    const modal = document.getElementById('geoModal');
    if (modal && !modal.classList.contains('hidden')) {
        const rows = Array.from(document.querySelectorAll('#geoTableBody tr')).filter(row => 
            row.style.display !== 'none' && !row.classList.contains('empty-message')
        );
        
        if (rows.length === 0) return;
        
        const currentIndex = selectedRow ? rows.indexOf(selectedRow) : -1;
        
        // Arrow keys navigation
        if (e.key === 'ArrowDown' && currentIndex < rows.length - 1) {
            e.preventDefault();
            const nextRow = rows[currentIndex + 1];
            selectGeoRow(nextRow);
            nextRow.scrollIntoView({block: 'nearest'});
        } else if (e.key === 'ArrowUp' && currentIndex > 0) {
            e.preventDefault();
            const prevRow = rows[currentIndex - 1];
            selectGeoRow(prevRow);
            prevRow.scrollIntoView({block: 'nearest'});
        } else if (e.key === 'Enter' && selectedRow) {
            e.preventDefault();
            selectGeo();
        } else if (e.key === 'Escape') {
            toggleModal('geoModal');
        }
    }
}

function closePreviewModal() {
    const previewModal = document.getElementById('previewModal');
    if (previewModal) {
        previewModal.classList.add('hidden');
    }
}

// Function to sort table by column
function sortTableByColumn(columnIndex) {
    const tbody = document.querySelector('#geoTableBody');
    if (!tbody) return;

    const rows = Array.from(tbody.getElementsByTagName('tr'));
    
    // Skip if no rows or only one row
    if (rows.length <= 1) return;

    // Get the current sort direction from the button
    const button = document.querySelector(`button[data-sort="${columnIndex}"]`);
    const currentDirection = button.getAttribute('data-direction') || 'asc';
    const newDirection = currentDirection === 'asc' ? 'desc' : 'asc';
    
    // Update button direction
    button.setAttribute('data-direction', newDirection);
    
    // Update button icon
    const icon = button.querySelector('i');
    if (icon) {
        icon.className = `fas fa-sort-${newDirection === 'asc' ? 'up' : 'down'} mr-1`;
    }

    // Sort the rows
    rows.sort((a, b) => {
        const aValue = a.cells[columnIndex].textContent.trim();
        const bValue = b.cells[columnIndex].textContent.trim();
        
        if (newDirection === 'asc') {
            return aValue.localeCompare(bValue);
        } else {
            return bValue.localeCompare(aValue);
        }
    });

    // Reorder the rows in the table
    rows.forEach(row => tbody.appendChild(row));
}

// Function to setup sort buttons
function setupSortButtons() {
    // Setup By Code button
    const byCodeBtn = document.querySelector('button[onclick="sortTableByColumn(1)"]');
    if (byCodeBtn) {
        byCodeBtn.setAttribute('data-sort', '1');
        byCodeBtn.setAttribute('data-direction', 'asc');
        byCodeBtn.innerHTML = '<i class="fas fa-sort mr-1"></i>By Code';
    }

    // Setup By Country button
    const byCountryBtn = document.querySelector('button[onclick="sortTableByColumn(2)"]');
    if (byCountryBtn) {
        byCountryBtn.setAttribute('data-sort', '2');
        byCountryBtn.setAttribute('data-direction', 'asc');
        byCountryBtn.innerHTML = '<i class="fas fa-sort mr-1"></i>By Country';
    }
}
