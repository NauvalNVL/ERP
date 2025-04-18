// Geo.js - Functions for geographical data management

// Global variables
let selectedRow = null;
let geoData = [];

// Sample seed data for geo areas
const seedGeoData = [
    { code: '0001', country: 'INDONESIA', state: 'ACEH', town: 'BANDA ACEH', town_section: 'BANDA ACEH', area: 'ACH' },
    { code: '0002', country: 'INDONESIA', state: 'ACEH', town: 'SABANG', town_section: 'SABANG', area: 'ACH' },
    { code: '0003', country: 'INDONESIA', state: 'ACEH', town: 'LANGSA', town_section: 'LANGSA', area: 'ACH' },
    { code: '0004', country: 'INDONESIA', state: 'SUMATERA', town: 'MEDAN', town_section: 'MEDAN', area: 'MDN' },
    { code: 'B110', country: 'INDONESIA', state: 'BANTEN', town: 'CILEGON', town_section: 'CILEGON', area: 'BTNW' },
    { code: 'B111', country: 'INDONESIA', state: 'BANTEN', town: 'CILEGON', town_section: 'CILEGON', area: 'BTNE' },
    { code: 'J001', country: 'INDONESIA', state: 'JAKARTA', town: 'JAKARTA PUSAT', town_section: 'MENTENG', area: 'JKT' },
    { code: 'J002', country: 'INDONESIA', state: 'JAKARTA', town: 'JAKARTA SELATAN', town_section: 'KEBAYORAN', area: 'JKT' },
    { code: 'S001', country: 'SINGAPORE', state: 'SINGAPORE', town: 'SINGAPORE', town_section: 'CENTRAL', area: 'SG' },
    { code: 'M001', country: 'MALAYSIA', state: 'KUALA LUMPUR', town: 'KUALA LUMPUR', town_section: 'CENTRAL', area: 'MY' }
];

// Initialize event handlers when document loads
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM content loaded for Geo.js');
    
    // Load geo data for local search and table population
    initializeGeoData();
    
    // Set up event listeners
    setupEventListeners();
});

// Initialize geo data from table or seed data
function initializeGeoData() {
    const rows = document.querySelectorAll('#geoTableBody tr:not(.empty-message)');
    
    // If no data in the table, we'll use our seed data
    if (rows.length === 0 || (rows.length === 1 && rows[0].querySelector('td[colspan]'))) {
        geoData = [...seedGeoData]; // Use seed data
        console.log('Using seed data for geo');
    } else {
        // Extract data from the existing table
        geoData = [];
        rows.forEach(row => {
            if (row.getAttribute('data-code')) {
                geoData.push({
                    code: row.getAttribute('data-code'),
                    area: row.getAttribute('data-area'),
                    country: row.querySelector('td:nth-child(3)').textContent.trim(),
                    state: row.querySelector('td:nth-child(4)').textContent.trim(),
                    town: row.querySelector('td:nth-child(5)').textContent.trim(),
                    town_section: row.querySelector('td:nth-child(6)').textContent.trim()
                });
            }
        });
        console.log('Extracted data from table, found ' + geoData.length + ' records');
    }
}

// Set up event listeners for various elements
function setupEventListeners() {
    // Search input in modal
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.addEventListener('keyup', function(e) {
            filterGeoTable(this.value);
            
            // Handle enter key for single result selection
            if (e.key === 'Enter') {
                selectSingleResult();
            }
        });
    }
    
    // Add double-click handler for table rows
    setupTableRowEvents();
    
    // Add keyboard navigation
    document.addEventListener('keydown', handleKeyboardNavigation);
    
    // Review button
    const btnReview = document.getElementById('btnReview');
    if (btnReview) {
        btnReview.addEventListener('click', reviewGeoData);
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

// Function to edit geo data
function editGeoData(code) {
    // Find the geo item in data
    const geoItem = geoData.find(item => item.code === code);
    
    if (geoItem) {
        // Fill the form with data
        document.getElementById('editGeoCode').value = geoItem.code;
        document.getElementById('editCountry').value = geoItem.country;
        document.getElementById('editState').value = geoItem.state;
        document.getElementById('editTown').value = geoItem.town;
        document.getElementById('editTownSection').value = geoItem.town_section;
        document.getElementById('editGeoArea').value = geoItem.area;
        
        // Show the edit modal
        toggleModal('editGeoModal');
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
        alert('Kode Geo dan Negara harus diisi!');
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

// Function to save geo data
function saveGeoData() {
    // Get form data
    const geoCode = document.getElementById('editGeoCode').value;
    const country = document.getElementById('editCountry').value;
    const state = document.getElementById('editState').value;
    const town = document.getElementById('editTown').value;
    const townSection = document.getElementById('editTownSection').value;
    const geoArea = document.getElementById('editGeoArea').value;
    
    // Validate data
    if (!geoCode || !country) {
        alert('Kode Geo dan Negara harus diisi!');
        return;
    }
    
    // Set code to input field
    document.getElementById('geoCode').value = geoCode;
    
    // Hide modal
    toggleModal('editGeoModal');
    
    // Check if this is an update or new record
    const existingIndex = geoData.findIndex(item => item.code === geoCode);
    
    if (existingIndex !== -1) {
        // Update existing record
        geoData[existingIndex] = {
            code: geoCode,
            country: country,
            state: state,
            town: town,
            town_section: townSection,
            area: geoArea
        };
        
        // Show success message
        document.getElementById('geoCodeResult').innerHTML = `
            <div class="p-3 bg-green-100 rounded-lg mt-2">
                <p class="text-sm text-green-800">Data berhasil diperbarui: <span class="font-semibold">${geoCode}</span> - ${geoArea}</p>
            </div>
        `;
    } else {
        // Add new record
        const newGeoData = {
            code: geoCode,
            country: country,
            state: state,
            town: town,
            town_section: townSection,
            area: geoArea
        };
        
        geoData.push(newGeoData);
        
        // Show success message
        document.getElementById('geoCodeResult').innerHTML = `
            <div class="p-3 bg-green-100 rounded-lg mt-2">
                <p class="text-sm text-green-800">Data baru berhasil ditambahkan: <span class="font-semibold">${geoCode}</span> - ${geoArea}</p>
            </div>
        `;
    }
    
    // Update table with new data
    updateGeoTable();
    
    // Show success message
    alert('Data berhasil disimpan!');
}

// Function to update the geo table with current data
function updateGeoTable() {
    const tableBody = document.getElementById('geoTableBody');
    
    // Clear table
    tableBody.innerHTML = '';
    
    // If no data, show message
    if (geoData.length === 0) {
        tableBody.innerHTML = '<tr><td colspan="7" class="py-6 px-6 text-center text-gray-500">Tidak ada data yang tersedia</td></tr>';
        return;
    }
    
    // Fill table with data
    geoData.forEach((geo, index) => {
        const row = document.createElement('tr');
        row.className = 'border-b border-gray-200 hover:bg-blue-50 cursor-pointer transition-colors duration-150';
        row.setAttribute('data-code', geo.code);
        row.setAttribute('data-area', geo.area);
        row.onclick = function() { selectGeoRow(this); };
        
        row.innerHTML = `
            <td class="py-3.5 px-6">${index + 1}</td>
            <td class="py-3.5 px-6 font-medium text-blue-800">${geo.code}</td>
            <td class="py-3.5 px-6">${geo.country}</td>
            <td class="py-3.5 px-6">${geo.state || ''}</td>
            <td class="py-3.5 px-6">${geo.town || ''}</td>
            <td class="py-3.5 px-6">${geo.town_section || ''}</td>
            <td class="py-3.5 px-6">${geo.area || ''}</td>
        `;
        
        tableBody.appendChild(row);
    });
    
    // Add event handlers to the new rows
    setupTableRowEvents();
}

// Toggle modal visibility with animation
function toggleModal(modalId) {
    const modal = document.getElementById(modalId);
    const modalContent = modal.querySelector('.modal-content');
    const modalOverlay = modal.querySelector('.modal-overlay');
    
    if (modal.classList.contains('hidden')) {
        // Show modal with animation
        modal.classList.remove('hidden');
        setTimeout(() => {
            modalOverlay.classList.add('opacity-100');
            modalContent.classList.add('opacity-100', 'scale-100');
            modalContent.classList.remove('opacity-0', 'scale-95');
        }, 10);
        
        // Focus search input if opening the geo table modal
        if (modalId === 'geoModal' && document.getElementById('searchInput')) {
            document.getElementById('searchInput').focus();
        }
    } else {
        // Hide modal with animation
        modalOverlay.classList.remove('opacity-100');
        modalContent.classList.remove('opacity-100', 'scale-100');
        modalContent.classList.add('opacity-0', 'scale-95');
        
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }
}

// Function to select a row in the table
function selectGeoRow(row) {
    // Remove highlight from previously selected row
    if (selectedRow) {
        selectedRow.classList.remove('bg-blue-100');
    }
    
    // Highlight the selected row
    selectedRow = row;
    selectedRow.classList.add('bg-blue-100');
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

// Set up event handlers for table rows
function setupTableRowEvents() {
    document.querySelectorAll('#geoTableBody tr').forEach(row => {
        // Skip if it's a "no data" message row
        if (row.querySelector('td[colspan]')) return;
        
        // Click to select
        row.addEventListener('click', function() {
            selectGeoRow(this);
        });
        
        // Double-click to select and close modal
        row.addEventListener('dblclick', function() {
            const code = this.getAttribute('data-code');
            if (code) {
                document.getElementById('geoCode').value = code;
                toggleModal('geoModal');
                editGeoData(code);
            }
        });
    });
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

// Function to load seed data if table is empty
function loadSeedData() {
    const tableBody = document.getElementById('geoTableBody');
    
    // Show loading overlay if available
    const loadingOverlay = document.getElementById('loadingOverlay');
    if (loadingOverlay) {
        loadingOverlay.classList.remove('hidden');
    }
    
    // Clear current data
    tableBody.innerHTML = '';
    geoData = [...seedGeoData];
    
    // Update the table
    setTimeout(() => {
        updateGeoTable();
        
        // Update notification on main page
        const dbStatusElement = document.querySelector('.bg-yellow-100');
        if (dbStatusElement) {
            dbStatusElement.classList.remove('bg-yellow-100');
            dbStatusElement.classList.add('bg-green-100');
            dbStatusElement.innerHTML = `
                <p class="text-sm font-medium text-green-800">Data tersedia: ${geoData.length} geo ditemukan (dari JavaScript).</p>
            `;
        }
        
        // Hide loading overlay
        if (loadingOverlay) {
            loadingOverlay.classList.add('hidden');
        }
        
        // Show success message
        alert('Data geo berhasil dimuat!');
    }, 500);
}
