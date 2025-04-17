@extends('layouts.app')

@section('title', 'Define Product Group')

<!-- Add direct script for opening modal -->
<script>
// Data dari ProductGroupSeeder langsung (sebagai fallback jika tidak ada data dari database)
const seedProductGroups = [
    {
        product_group_id: 'B',
        product_group_name: 'BOX',
        is_active: true
    },
    {
        product_group_id: 'OF',
        product_group_name: 'OFFSET',
        is_active: true
    },
    {
        product_group_id: 'OT',
        product_group_name: 'OTHER',
        is_active: true
    },
    {
        product_group_id: 'R',
        product_group_name: 'PAPER ROLL',
        is_active: true
    },
    {
        product_group_id: 'S',
        product_group_name: 'SHEET BOARD',
        is_active: true
    }
];

// Fungsi untuk mengisi tabel dengan data dari seedProductGroups jika tidak ada data dari database
function populateProductGroupTable() {
    console.log('Populating product group table');
    
    // Cek apakah tabel sudah memiliki data
    const tbody = document.querySelector('#productGroupDataTable tbody');
    const rows = tbody.querySelectorAll('tr');
    const isEmpty = rows.length === 0 || (rows.length === 1 && rows[0].querySelector('td[colspan]'));
    
    if (isEmpty) {
        console.log('Table is empty, populating with seed data');
        
        // Hapus pesan "tidak ada data" jika ada
        tbody.innerHTML = '';
        
        // Isi tabel dengan data dari seedProductGroups
        seedProductGroups.forEach(group => {
            const row = document.createElement('tr');
            row.className = 'cursor-pointer';
            row.setAttribute('data-pg-id', group.product_group_id);
            row.setAttribute('data-pg-name', group.product_group_name);
            row.setAttribute('data-pg-active', group.is_active);
            row.onclick = function(e) { selectRow(this); e.stopPropagation(); };
            row.ondblclick = function() { openEditProductGroupModal(this); };
            
            // Buat kolom untuk setiap sel
            const idCell = document.createElement('td');
            idCell.className = 'px-2 py-0.5 border-b border-r border-gray-300';
            idCell.textContent = group.product_group_id;
            
            const nameCell = document.createElement('td');
            nameCell.className = 'px-2 py-0.5 border-b border-r border-gray-300';
            nameCell.textContent = group.product_group_name;
            
            const statusCell = document.createElement('td');
            statusCell.className = 'px-2 py-0.5 border-b border-gray-300';
            statusCell.textContent = group.is_active ? 'Active' : 'Inactive';
            
            // Tambahkan semua sel ke baris
            row.appendChild(idCell);
            row.appendChild(nameCell);
            row.appendChild(statusCell);
            
            // Tambahkan baris ke tabel
            tbody.appendChild(row);
        });
        
        console.log('Table populated with seed data');
        
        // Setup event handlers for the table rows
        if (typeof setupTableRowEvents === 'function') {
            setupTableRowEvents();
        }
        
        // Urutkan tabel berdasarkan PG#
        sortTableDirectly(0);
    } else {
        console.log('Table already has data, no need to populate');
    }
}

// Fungsi mengurutkan tabel
function sortTableDirectly(columnIndex) {
    console.log("Sorting by column: " + columnIndex);
    
    var table = document.getElementById('productGroupDataTable');
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
    
    // Jangan urutkan jika tidak ada data atau hanya ada pesan "tidak ada data"
    if (rows.length === 0 || (rows.length === 1 && rows[0].querySelector('td[colspan]'))) {
        console.log("No data to sort");
        return;
    }
    
    // Urutkan baris berdasarkan isi kolom
    rows.sort(function(a, b) {
        // Periksa apakah kolom tersedia
        if (!a.cells[columnIndex] || !b.cells[columnIndex]) {
            return 0;
        }
        
        var textA = a.cells[columnIndex].textContent.trim();
        var textB = b.cells[columnIndex].textContent.trim();
        
        // Pengurutan berbasis numerik untuk kolom ID
        if (columnIndex === 0) {
            return textA.localeCompare(textB, undefined, { numeric: true });
        }
        
        // Pengurutan teks biasa untuk kolom lainnya
        return textA.localeCompare(textB);
    });
    
    // Hapus semua baris dari tabel
    while (tbody.firstChild) {
        tbody.removeChild(tbody.firstChild);
    }
    
    // Tambahkan baris yang sudah diurutkan ke tabel
    rows.forEach(function(row) {
        tbody.appendChild(row);
    });
    
    // Highlight baris pertama setelah pengurutan
    if (rows.length > 0) {
        rows.forEach(function(row) {
            row.classList.remove('selected');
        });
        rows[0].classList.add('selected');
    }
    
    console.log("Sorting complete");
}

// Fungsi untuk mengedit baris terpilih (Edit button)
function editSelectedRow() {
    var selectedRow = document.querySelector('#productGroupDataTable tbody tr.selected');
    if (!selectedRow) {
        console.log("No row selected");
        alert("Silahkan pilih product group terlebih dahulu");
        return;
    }
    
    console.log("Selected row for editing:", selectedRow);
    
    // Buka modal edit untuk baris terpilih
    openEditProductGroupModal(selectedRow);
}

// Fungsi untuk menggunakan product group yang terpilih (Select button)
function useSelectedProductGroup() {
    var selectedRow = document.querySelector('#productGroupDataTable tbody tr.selected');
    if (!selectedRow) {
        console.log("No row selected");
        alert("Silahkan pilih product group terlebih dahulu");
        return;
    }
    
    var pgId = selectedRow.cells[0].textContent.trim();
    var pgName = selectedRow.cells[1].textContent.trim();
    console.log("Selected product group ID: " + pgId);
    
    // Pilih product group dan tutup modal
    selectProductGroup(pgId, pgName);
}

// Fungsi memilih baris saat ini dan menutup modal
function selectCurrentRow() {
    var selectedRow = document.querySelector('#productGroupDataTable tbody tr.selected');
    if (!selectedRow) {
        console.log("No row selected");
        alert("Silahkan pilih product group terlebih dahulu");
        return;
    }
    
    var pgId = selectedRow.cells[0].textContent.trim();
    var pgName = selectedRow.cells[1].textContent.trim();
    console.log("Selected product group ID: " + pgId);
    
    // Pilih product group dan tutup modal
    selectProductGroup(pgId, pgName);
}

// Fungsi memilih product group
function selectProductGroup(pgId, pgName) {
    console.log('Selecting product group: ' + pgId + ' - ' + pgName);
    
    // Isi input form dengan ID product group yang dipilih
    var pgInput = document.querySelector('input[type="text"]');
    if (pgInput) {
        pgInput.value = pgId;
    }
    
    // Tutup modal
    closeProductGroupModal();
}

// Fungsi menutup modal
function closeProductGroupModal() {
    var modal = document.getElementById('productGroupTableWindow');
    if (modal) {
        modal.style.display = 'none';
        modal.classList.add('hidden');
    }
}

// Fungsi memilih baris
function selectRow(row) {
    // Hapus kelas 'selected' dari semua baris
    var allRows = document.querySelectorAll('#productGroupDataTable tbody tr');
    allRows.forEach(function(r) {
        r.classList.remove('selected');
    });
    
    // Tambahkan kelas 'selected' ke baris yang dipilih
    row.classList.add('selected');
}

// Fungsi untuk membuka modal edit
function openEditProductGroupModal(row) {
    console.log('Opening edit product group modal for row:', row);
    
    const pgId = row.getAttribute('data-pg-id');
    const pgName = row.getAttribute('data-pg-name');
    const pgActive = row.getAttribute('data-pg-active');
    
    console.log('Product group data:', { pgId, pgName, pgActive });
    
    document.getElementById('edit_pg_id').value = pgId;
    document.getElementById('edit_pg_name').value = pgName;
    document.getElementById('edit_pg_active').value = pgActive;
    
    const editModal = document.getElementById('editProductGroupModal');
    editModal.classList.remove('hidden');
    editModal.style.display = 'block';
    
    console.log('Edit modal opened');
}

// Fungsi untuk menutup modal edit
function closeEditProductGroupModal() {
    console.log('Closing edit product group modal');
    const editModal = document.getElementById('editProductGroupModal');
    editModal.classList.add('hidden');
    editModal.style.display = 'none';
}

// Fungsi untuk menampilkan modal
function openProductGroupModal() {
    console.log('Opening product group modal');
    var modal = document.getElementById('productGroupTableWindow');
    if (!modal) {
        console.error('Modal element not found');
        return;
    }
    
    // Tampilkan modal
    modal.style.display = 'block';
    modal.classList.remove('hidden');
    
    // Populasi tabel dengan data dari ProductGroupSeeder jika perlu
    populateProductGroupTable();
    
    // Urutkan berdasarkan PG# secara default
    sortTableDirectly(0);
    
    console.log('Modal opened successfully');
}

function closeModalX() {
    closeProductGroupModal();
}

// Fungsi untuk membuat data tersedia tanpa database
function loadSeedData() {
    // Periksa apakah data sudah dimuat
    const tbody = document.querySelector('#productGroupDataTable tbody');
    if (!tbody) return;
    
    // Tampilkan loading overlay
    const loadingOverlay = document.getElementById('loadingOverlay');
    if (loadingOverlay) {
        loadingOverlay.classList.remove('hidden');
    }
    
    // Kosongkan tabel
    tbody.innerHTML = '';
    
    // Simulasi loading
    setTimeout(() => {
        // Isi tabel dengan data dari seedProductGroups
        populateProductGroupTable();
        
        // Tampilkan notifikasi
        alert('Data product group berhasil dimuat dari ProductGroupSeeder');
        
        // Sembunyikan loading overlay
        if (loadingOverlay) {
            loadingOverlay.classList.add('hidden');
        }
        
        // Update notification di halaman utama
        const dbStatusElement = document.querySelector('.bg-yellow-100');
        if (dbStatusElement) {
            dbStatusElement.classList.remove('bg-yellow-100');
            dbStatusElement.classList.add('bg-green-100');
            dbStatusElement.innerHTML = `
                <p class="text-sm font-medium text-green-800">Data tersedia: ${seedProductGroups.length} product group ditemukan (dari JavaScript).</p>
            `;
        }
        
        // Buka modal untuk menampilkan data
        openProductGroupModal();
    }, 1000);
}

// Fungsi untuk menyimpan perubahan product group
function saveProductGroupChanges() {
    console.log('Saving product group changes');
    
    const pgId = document.getElementById('edit_pg_id').value;
    const pgName = document.getElementById('edit_pg_name').value;
    const pgActive = document.getElementById('edit_pg_active').value === 'true';
    
    console.log('Form data to save:', { pgId, pgName, pgActive });
    
    // Validasi input sudah ditangani oleh atribut required di form
    
    // Tampilkan indikator loading pada tombol dan overlay
    const saveButton = document.querySelector('#editProductGroupForm button[type="submit"]');
    const originalText = saveButton.innerText;
    saveButton.innerText = 'Menyimpan...';
    saveButton.disabled = true;
    
    // Tampilkan loading overlay
    document.getElementById('loadingOverlay').classList.remove('hidden');
    
    // Update row in table immediately to provide visual feedback
    const row = document.querySelector(`#productGroupDataTable tbody tr[data-pg-id="${pgId}"]`);
    if (row) {
        console.log('Found row to update:', row);
        
        try {
            // Direct DOM manipulation for cell updates
            const cells = row.cells;
            console.log('Row has cells:', cells.length);
            
            if (cells.length >= 3) {
                console.log('Original cell values:');
                console.log('- Cell 0 (PG#):', cells[0].textContent);
                console.log('- Cell 1 (PG Name):', cells[1].textContent);
                console.log('- Cell 2 (Status):', cells[2].textContent);
                
                // Update cell text directly
                cells[0].textContent = pgId;
                cells[1].textContent = pgName;
                cells[2].textContent = pgActive ? 'Active' : 'Inactive';
                
                console.log('After update:');
                console.log('- Cell 0 (PG#):', cells[0].textContent);
                console.log('- Cell 1 (PG Name):', cells[1].textContent);
                console.log('- Cell 2 (Status):', cells[2].textContent);
                
                // Update data attributes
                row.setAttribute('data-pg-id', pgId);
                row.setAttribute('data-pg-name', pgName);
                row.setAttribute('data-pg-active', pgActive.toString());
                
                // Re-apply selected class to ensure visibility
                row.classList.add('selected');
                
                // Also update seedProductGroups array to keep data in sync
                updateSeedProductGroupData(pgId, pgName, pgActive);
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
        const tbody = document.querySelector('#productGroupDataTable tbody');
        
        // Create a new row
        const newRow = document.createElement('tr');
        newRow.className = 'cursor-pointer';
        newRow.setAttribute('data-pg-id', pgId);
        newRow.setAttribute('data-pg-name', pgName);
        newRow.setAttribute('data-pg-active', pgActive.toString());
        newRow.onclick = function(e) { selectRow(this); e.stopPropagation(); };
        newRow.ondblclick = function() { openEditProductGroupModal(this); };
        
        // Create cells
        const idCell = document.createElement('td');
        idCell.className = 'px-2 py-0.5 border-b border-r border-gray-300';
        idCell.textContent = pgId;
        
        const nameCell = document.createElement('td');
        nameCell.className = 'px-2 py-0.5 border-b border-r border-gray-300';
        nameCell.textContent = pgName;
        
        const statusCell = document.createElement('td');
        statusCell.className = 'px-2 py-0.5 border-b border-gray-300';
        statusCell.textContent = pgActive ? 'Active' : 'Inactive';
        
        // Add cells to row
        newRow.appendChild(idCell);
        newRow.appendChild(nameCell);
        newRow.appendChild(statusCell);
        
        // Add row to table
        tbody.appendChild(newRow);
        
        // Select the new row
        selectRow(newRow);
        
        // Add to seedProductGroups
        updateSeedProductGroupData(pgId, pgName, pgActive);
    }
    
    // Show success message and close modal
    alert('Data product group berhasil diperbarui');
    closeEditProductGroupModal();
    
    // Reset button state and hide loading overlay after a short delay
    setTimeout(() => {
        saveButton.innerText = originalText;
        saveButton.disabled = false;
        document.getElementById('loadingOverlay').classList.add('hidden');
    }, 500);
}

// Fungsi untuk memperbarui data di seedProductGroups array
function updateSeedProductGroupData(pgId, pgName, pgActive) {
    // Cari product group dengan ID yang sesuai di seedProductGroups array
    const groupIndex = seedProductGroups.findIndex(group => group.product_group_id === pgId);
    
    if (groupIndex !== -1) {
        console.log(`Updating seedProductGroups[${groupIndex}] with new data`);
        
        // Update data di array
        seedProductGroups[groupIndex].product_group_name = pgName;
        seedProductGroups[groupIndex].is_active = pgActive;
        
        console.log('Updated seedProductGroups:', seedProductGroups[groupIndex]);
    } else {
        console.log(`Product group with ID ${pgId} not found in seedProductGroups array`);
        
        // Jika tidak ditemukan, tambahkan sebagai item baru
        seedProductGroups.push({
            product_group_id: pgId,
            product_group_name: pgName,
            is_active: pgActive
        });
        
        console.log('Added new product group to seedProductGroups array');
    }
}

// Fungsi untuk mengatur event pada baris tabel
function setupTableRowEvents() {
    console.log('Setting up table row events');
    var tableRows = document.querySelectorAll('#productGroupDataTable tbody tr');
    console.log('Table rows found:', tableRows.length);
    
    tableRows.forEach(function(row) {
        // Lewati baris "tidak ada data"
        if (row.querySelector('td[colspan]')) return;
        
        // Event klik untuk memilih baris
        row.onclick = function(e) {
            e.stopPropagation();
            selectRow(this);
        };
        
        // Event klik ganda untuk membuka modal edit
        row.ondblclick = function() {
            console.log('Double-click detected on row:', this);
            openEditProductGroupModal(this);
        };
    });
}

// Inisialisasi event handler saat dokumen dimuat
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM content loaded');
    
    // Test if the edit modal exists
    const editModal = document.getElementById('editProductGroupModal');
    if (editModal) {
        console.log('Edit modal found in DOM');
    } else {
        console.error('Edit modal not found in DOM');
    }
    
    // Dapatkan tombol dan modal
    var showBtn = document.getElementById('showProductGroupTableBtn');
    var productGroupModal = document.getElementById('productGroupTableWindow');
    
    // Tambahkan event listener ke tombol untuk menampilkan modal
    if (showBtn) {
        showBtn.onclick = function() {
            if (productGroupModal) {
                productGroupModal.style.display = 'block';
                productGroupModal.classList.remove('hidden');
                
                // Populasi tabel dengan data dari ProductGroupSeeder jika perlu
                populateProductGroupTable();
                
                // Urutkan berdasarkan kolom pertama secara default
                sortTableDirectly(0);
            }
        };
    }
    
    // Setup row events initially
    setupTableRowEvents();
    
    // Override the sortTableDirectly function to setup events after sort
    const originalSortFunction = window.sortTableDirectly;
    if (typeof originalSortFunction === 'function') {
        window.sortTableDirectly = function(columnIndex) {
            originalSortFunction(columnIndex);
            setupTableRowEvents(); // Re-attach events after sorting
        };
    }
    
    // Tutup modal saat mengklik area di luar tabel
    if (productGroupModal) {
        productGroupModal.onclick = function(e) {
            if (e.target === productGroupModal) {
                closeProductGroupModal();
            }
        };
    }
    
    // Event untuk menutup modal saat mengklik di luar
    window.onclick = function(event) {
        const editModal = document.getElementById('editProductGroupModal');
        if (event.target === editModal) {
            closeEditProductGroupModal();
        }
    };
    
    // Add a direct test button if needed
    var testBtn = document.createElement('button');
    testBtn.innerText = 'Test Edit Modal';
    testBtn.style.position = 'fixed';
    testBtn.style.bottom = '10px';
    testBtn.style.right = '10px';
    testBtn.style.zIndex = '9999';
    testBtn.style.padding = '5px 10px';
    testBtn.style.backgroundColor = '#4299e1';
    testBtn.style.color = 'white';
    testBtn.style.border = 'none';
    testBtn.style.borderRadius = '4px';
    testBtn.onclick = function() {
        // Get the first row from the table
        var firstRow = document.querySelector('#productGroupDataTable tbody tr');
        if (firstRow) {
            openEditProductGroupModal(firstRow);
        } else {
            console.error('No rows found to test with');
        }
    };
    document.body.appendChild(testBtn);
});
</script>

@push('styles')
<style>
    /* Modal window styles */
    #productGroupTableWindow, #editProductGroupModal {
        background-color: rgba(0, 0, 0, 0.2);
        z-index: 9999;
    }
    
    #productGroupTableWindow:not(.hidden), #editProductGroupModal:not(.hidden) {
        display: block;
    }
    
    #productGroupTableWindow.hidden, #editProductGroupModal.hidden {
        display: none;
    }
    
    /* Loading overlay */
    .loading-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 10000;
    }
    
    .loading-spinner {
        border: 5px solid #f3f3f3;
        border-top: 5px solid #3498db;
        border-radius: 50%;
        width: 50px;
        height: 50px;
        animation: spin 2s linear infinite;
    }
    
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    
    /* Table styles */
    #productGroupDataTable {
        border-collapse: collapse;
        width: 100%;
    }
    
    #productGroupDataTable tbody {
        display: block;
        max-height: 250px;
        overflow-y: auto;
        overflow-x: hidden;
    }
    
    #productGroupDataTable thead {
        display: table;
        width: calc(100% - 17px); /* Adjust for scrollbar width */
        table-layout: fixed;
    }
    
    #productGroupDataTable tbody tr {
        display: table;
        width: 100%;
        table-layout: fixed;
    }
    
    /* Selected row */
    #productGroupDataTable tbody tr.selected {
        background-color: #0078d7;
        color: white;
    }
    
    #productGroupDataTable tbody tr:hover {
        background-color: #0078d7;
        color: white;
    }
    
    /* Classic Windows button styles */
    button {
        cursor: pointer;
    }
    
    button:active {
        transform: translateY(1px);
    }
    
    /* Sort button active style */
    .sort-btn.active-sort {
        background-color: #4299e1;
        color: white;
        font-weight: bold;
    }
    
    /* Classic Windows scrollbar style */
    #productGroupDataTable tbody::-webkit-scrollbar {
        width: 16px;
    }
    
    #productGroupDataTable tbody::-webkit-scrollbar-track {
        background: #f1f1f1;
    }
    
    #productGroupDataTable tbody::-webkit-scrollbar-thumb {
        background: #c1c1c1;
        border: 1px solid #a1a1a1;
    }
    
    #productGroupDataTable tbody::-webkit-scrollbar-thumb:hover {
        background: #a1a1a1;
    }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 p-6">
    <div class="container mx-auto max-w-7xl">
        <!-- Loading Overlay -->
        <div id="loadingOverlay" class="loading-overlay hidden">
            <div class="loading-spinner"></div>
        </div>

        <div class="bg-white rounded-lg shadow-lg p-6">
            <!-- Header with navigation buttons -->
            <div class="flex items-center space-x-2 mb-6">
                <button type="button" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded flex items-center space-x-2">
                    <i class="fas fa-power-off"></i>
                </button>
                <button type="button" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded flex items-center space-x-2">
                    <i class="fas fa-arrow-right"></i>
                </button>
                <button type="button" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded flex items-center space-x-2">
                    <i class="fas fa-arrow-left"></i>
                </button>
                <button type="button" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded flex items-center space-x-2">
                    <i class="fas fa-search"></i>
                    </button>
                <button type="button" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded flex items-center space-x-2">
                    <i class="fas fa-save"></i>
                </button>
    </div>

            <!-- Search Section -->
            <div class="flex justify-between items-center mb-6">
                <div class="flex items-center space-x-2">
                    <label class="text-gray-600">Product Group#:</label>
                    <input type="text" class="border border-gray-300 rounded px-3 py-2 w-48">
                    <button type="button" id="showProductGroupTableBtn" class="bg-gray-200 hover:bg-gray-300 px-2 py-2 border border-gray-400" onclick="openProductGroupModal()">
                        <i class="fas fa-table text-gray-600"></i>
                    </button>
                </div>
                <div class="flex items-center space-x-2">
                    <span class="text-gray-600">Record:</span>
                    <button type="button" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 border border-gray-300">
                        Select
            </button>
    </div>
</div>

            <!-- Debug Information -->
            @if(empty($productGroups) || count($productGroups) === 0)
            <div class="mt-4 bg-yellow-100 p-3 rounded">
                <p class="text-sm font-medium text-yellow-800">Tidak ada data product group yang tersedia.</p>
                <p class="text-xs text-yellow-700 mt-1">Pastikan database telah diatur dengan benar dan data seeder telah dijalankan.</p>
                <div class="mt-2 flex items-center space-x-3">
                    <a href="{{ route('run.productgroup.seeder') }}" class="bg-blue-500 hover:bg-blue-600 text-white text-xs px-3 py-1 rounded">
                        Run Product Group Seeder (DB)
                    </a>
                    <button onclick="loadSeedData()" class="bg-green-500 hover:bg-green-600 text-white text-xs px-3 py-1 rounded">
                        Load Product Group Seeder Data (JS)
                        </button>
                </div>
            </div>
            @elseif(count($productGroups) > 0)
            <div class="mt-4 bg-green-100 p-3 rounded">
                <p class="text-sm font-medium text-green-800">Data tersedia: {{ count($productGroups) }} product group ditemukan.</p>
        </div>
            @endif
        </div>
    </div>
</div>

<!-- Product Group Table Window -->
<div id="productGroupTableWindow" class="hidden fixed inset-0 z-50">
    <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white border-2 border-gray-500 shadow-lg" style="width: 560px;">
        <!-- Modal Header - Title Bar -->
        <div class="bg-blue-800 px-1 py-0.5 text-white flex justify-between items-center">
            <h3 class="text-xs font-normal">Product Group Table</h3>
            <button type="button" onclick="closeModalX()" class="text-white hover:text-gray-200 focus:outline-none">
                <span class="text-lg">×</span>
            </button>
        </div>

        <!-- Table Content -->
        <div class="p-1">
            <div class="border border-gray-300 overflow-hidden">
                <table class="w-full" id="productGroupDataTable">
                    <thead>
                        <tr>
                            <th class="px-2 py-0.5 bg-gray-100 border-b border-r border-gray-300 text-left text-xs font-semibold">PG#</th>
                            <th class="px-2 py-0.5 bg-gray-100 border-b border-r border-gray-300 text-left text-xs font-semibold">PG Name</th>
                            <th class="px-2 py-0.5 bg-gray-100 border-b border-gray-300 text-left text-xs font-semibold">Status</th>
                        </tr>
                    </thead>
                    <tbody class="text-xs">
                        <!-- Data dari database akan ditampilkan di sini jika tersedia -->
                        <!-- Jika tidak ada data dari database, JavaScript akan mengisi dengan data dari ProductGroupSeeder -->
                        @if(isset($productGroups) && count($productGroups) > 0)
                        @foreach($productGroups as $group)
                            <tr class="cursor-pointer" 
                                data-pg-id="{{ $group->product_group_id }}"
                                data-pg-name="{{ $group->product_group_name }}"
                                data-pg-active="{{ $group->is_active ? 'true' : 'false' }}"
                                onclick="selectRow(this); event.stopPropagation();"
                                ondblclick="openEditProductGroupModal(this)">
                                <td class="px-2 py-0.5 border-b border-r border-gray-300">{{ $group->product_group_id }}</td>
                                <td class="px-2 py-0.5 border-b border-r border-gray-300">{{ $group->product_group_name }}</td>
                                <td class="px-2 py-0.5 border-b border-gray-300">{{ $group->is_active ? 'Active' : 'Inactive' }}</td>
                        </tr>
                        @endforeach
                        @else
                            <tr>
                                <td colspan="3" class="px-2 py-4 text-center border-b border-gray-300">Tidak ada data product group yang tersedia.</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>

            <!-- Bottom Buttons with equal spacing -->
            <div class="flex justify-between mt-2">
                <button type="button" onclick="sortTableDirectly(0)" class="py-0.5 px-2 bg-gray-100 border border-gray-400 hover:bg-gray-200 text-xs w-20">By PG#</button>
                <button type="button" onclick="sortTableDirectly(1)" class="py-0.5 px-2 bg-gray-100 border border-gray-400 hover:bg-gray-200 text-xs w-24">By PG Name</button>
                <button type="button" onclick="sortTableDirectly(2)" class="py-0.5 px-2 bg-gray-100 border border-gray-400 hover:bg-gray-200 text-xs w-28">By Status</button>
                <button type="button" onclick="editSelectedRow()" class="py-0.5 px-2 bg-gray-100 border border-gray-400 hover:bg-gray-200 text-xs w-16">Select</button>
                <button type="button" onclick="closeProductGroupModal()" class="py-0.5 px-2 bg-gray-100 border border-gray-400 hover:bg-gray-200 text-xs w-16">Exit</button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Product Group Modal -->
<div id="editProductGroupModal" class="hidden fixed inset-0 z-50 bg-black bg-opacity-30">
    <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white border-2 border-gray-500 shadow-lg" style="width: 350px;">
        <!-- Modal Header - Title Bar -->
        <div class="bg-blue-800 px-1 py-0.5 text-white flex justify-between items-center">
            <h3 class="text-xs font-normal">Define Product Group</h3>
            <button type="button" onclick="closeEditProductGroupModal()" class="text-white hover:text-gray-200 focus:outline-none">
                <span class="text-lg">×</span>
            </button>
        </div>

        <!-- Form Content -->
        <div class="p-4">
            <form id="editProductGroupForm" onsubmit="saveProductGroupChanges(); return false;">
                <div class="space-y-3">
                    <div class="flex items-center">
                        <label class="w-24 text-xs text-gray-600">PG#:</label>
                        <input id="edit_pg_id" type="text" class="border border-gray-300 rounded px-2 py-1 text-xs w-24" required>
                    </div>
                    <div class="flex items-center">
                        <label class="w-24 text-xs text-gray-600">PG Name:</label>
                        <input id="edit_pg_name" type="text" class="border border-gray-300 rounded px-2 py-1 text-xs w-full" required>
                    </div>
                    <div class="flex items-center">
                        <label class="w-24 text-xs text-gray-600">Status:</label>
                        <select id="edit_pg_active" class="border border-gray-300 rounded px-2 py-1 text-xs w-32" required>
                            <option value="true">Active</option>
                            <option value="false">Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="flex justify-end mt-4">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white text-xs px-4 py-1 rounded mr-2">Save</button>
                    <button type="button" onclick="closeEditProductGroupModal()" class="bg-gray-300 hover:bg-gray-400 text-gray-800 text-xs px-4 py-1 rounded">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
