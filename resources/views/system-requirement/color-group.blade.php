@extends('layouts.app')

@section('title', 'Define Color Group')

<!-- Add direct script for opening modal -->
<script>
// Data dari ColorGroupSeeder langsung (sebagai fallback jika tidak ada data dari database)
const seedColorGroups = [
    {
        cg: '01',
        cg_name: 'BLACK', 
        cg_type: 'X-Flex'
    },
    {
        cg: '02',
        cg_name: 'WHITE',
        cg_type: 'X-Flex'
    },
    {
        cg: '03',
        cg_name: 'RED',
        cg_type: 'X-Flex'
    },
    {
        cg: '04',
        cg_name: 'BLUE',
        cg_type: 'X-Flex'
    },
    {
        cg: '05',
        cg_name: 'GREEN',
        cg_type: 'X-Flex'
    },
    {
        cg: '06',
        cg_name: 'YELLOW',
        cg_type: 'X-Flex'
    },
    {
        cg: '07',
        cg_name: 'MAGENTA',
        cg_type: 'X-Flex'
    },
    {
        cg: '08',
        cg_name: 'CYAN',
        cg_type: 'X-Flex'
    },
    {
        cg: '09',
        cg_name: 'ORANGE',
        cg_type: 'C-Coating'
    },
    {
        cg: '10',
        cg_name: 'BROWN',
        cg_type: 'S-Offset'
    },
    {
        cg: '11',
        cg_name: 'GRAY',
        cg_type: 'S-Offset'
    },
    {
        cg: '12',
        cg_name: 'PURPLE',
        cg_type: 'C-Coating'
    },
    {
        cg: '13',
        cg_name: 'VIOLET',
        cg_type: 'S-Offset'
    },
    {
        cg: '14',
        cg_name: 'CREAM',
        cg_type: 'S-Offset'
    },
    {
        cg: '15',
        cg_name: 'PANTONE',
        cg_type: 'S-Offset'
    }
];

// Fungsi untuk mengisi tabel dengan data dari seedColorGroups jika tidak ada data dari database
function populateColorGroupTable() {
    console.log('Populating color group table');
    
    // Cek apakah tabel sudah memiliki data
    const tbody = document.querySelector('#colorGroupDataTable tbody');
    const rows = tbody.querySelectorAll('tr');
    const isEmpty = rows.length === 0 || (rows.length === 1 && rows[0].querySelector('td[colspan]'));
    
    if (isEmpty) {
        console.log('Table is empty, populating with seed data');
        
        // Hapus pesan "tidak ada data" jika ada
        tbody.innerHTML = '';
        
        // Isi tabel dengan data dari seedColorGroups
        seedColorGroups.forEach(group => {
            const row = document.createElement('tr');
            row.className = 'cursor-pointer';
            row.setAttribute('data-cg-id', group.cg);
            row.setAttribute('data-cg-name', group.cg_name);
            row.setAttribute('data-cg-type', group.cg_type);
            row.onclick = function(e) { selectRow(this); e.stopPropagation(); };
            row.ondblclick = function() { openEditColorGroupModal(this); };
            
            // Buat kolom untuk setiap sel
            const idCell = document.createElement('td');
            idCell.className = 'px-2 py-0.5 border-b border-r border-gray-300';
            idCell.textContent = group.cg;
            
            const nameCell = document.createElement('td');
            nameCell.className = 'px-2 py-0.5 border-b border-r border-gray-300';
            nameCell.textContent = group.cg_name;
            
            const typeCell = document.createElement('td');
            typeCell.className = 'px-2 py-0.5 border-b border-gray-300';
            typeCell.textContent = group.cg_type;
            
            // Tambahkan semua sel ke baris
            row.appendChild(idCell);
            row.appendChild(nameCell);
            row.appendChild(typeCell);
            
            // Tambahkan baris ke tabel
            tbody.appendChild(row);
        });
        
        console.log('Table populated with seed data');
        
        // Setup event handlers for the table rows
        if (typeof setupTableRowEvents === 'function') {
            setupTableRowEvents();
        }
        
        // Urutkan tabel berdasarkan CG#
        sortTableDirectly(0);
    } else {
        console.log('Table already has data, no need to populate');
    }
}

// Fungsi mengurutkan tabel
function sortTableDirectly(columnIndex) {
    console.log("Sorting by column: " + columnIndex);
    
    var table = document.getElementById('colorGroupDataTable');
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
    var selectedRow = document.querySelector('#colorGroupDataTable tbody tr.selected');
    if (!selectedRow) {
        console.log("No row selected");
        alert("Silahkan pilih color group terlebih dahulu");
        return;
    }
    
    console.log("Selected row for editing:", selectedRow);
    
    // Buka modal edit untuk baris terpilih
    openEditColorGroupModal(selectedRow);
}

// Fungsi untuk menggunakan color group yang terpilih (Select button)
function useSelectedColorGroup() {
    var selectedRow = document.querySelector('#colorGroupDataTable tbody tr.selected');
    if (!selectedRow) {
        console.log("No row selected");
        alert("Silahkan pilih color group terlebih dahulu");
        return;
    }
    
    var cgId = selectedRow.cells[0].textContent.trim();
    var cgName = selectedRow.cells[1].textContent.trim();
    console.log("Selected color group ID: " + cgId);
    
    // Pilih color group dan tutup modal
    selectColorGroup(cgId, cgName);
}

// Fungsi memilih baris saat ini dan menutup modal
function selectCurrentRow() {
    var selectedRow = document.querySelector('#colorGroupDataTable tbody tr.selected');
    if (!selectedRow) {
        console.log("No row selected");
        alert("Silahkan pilih color group terlebih dahulu");
        return;
    }
    
    var cgId = selectedRow.cells[0].textContent.trim();
    var cgName = selectedRow.cells[1].textContent.trim();
    console.log("Selected color group ID: " + cgId);
    
    // Pilih color group dan tutup modal
    selectColorGroup(cgId, cgName);
}

// Fungsi memilih color group
function selectColorGroup(cgId, cgName) {
    console.log('Selecting color group: ' + cgId + ' - ' + cgName);
    
    // Isi input form dengan ID color group yang dipilih
    var cgInput = document.querySelector('input[type="text"]');
    if (cgInput) {
        cgInput.value = cgId;
    }
    
    // Tutup modal
    closeColorGroupModal();
}

// Fungsi menutup modal
function closeColorGroupModal() {
    var modal = document.getElementById('colorGroupTableWindow');
    if (modal) {
        modal.style.display = 'none';
        modal.classList.add('hidden');
    }
}

// Fungsi memilih baris
function selectRow(row) {
    // Hapus kelas 'selected' dari semua baris
    var allRows = document.querySelectorAll('#colorGroupDataTable tbody tr');
    allRows.forEach(function(r) {
        r.classList.remove('selected');
    });
    
    // Tambahkan kelas 'selected' ke baris yang dipilih
    row.classList.add('selected');
}

// Fungsi untuk membuka modal edit
function openEditColorGroupModal(row) {
    console.log('Opening edit color group modal for row:', row);
    
    const cgId = row.getAttribute('data-cg-id');
    const cgName = row.getAttribute('data-cg-name');
    const cgType = row.getAttribute('data-cg-type');
    
    console.log('Color group data:', { cgId, cgName, cgType });
    
    document.getElementById('edit_cg_id').value = cgId;
    document.getElementById('edit_cg_name').value = cgName;
    document.getElementById('edit_cg_type').value = cgType;
    
    const editModal = document.getElementById('editColorGroupModal');
    editModal.classList.remove('hidden');
    editModal.style.display = 'block';
    
    console.log('Edit modal opened');
}

// Fungsi untuk menutup modal edit
function closeEditColorGroupModal() {
    console.log('Closing edit color group modal');
    const editModal = document.getElementById('editColorGroupModal');
    editModal.classList.add('hidden');
    editModal.style.display = 'none';
}

// Fungsi untuk menampilkan modal
function openColorGroupModal() {
    console.log('Opening color group modal');
    var modal = document.getElementById('colorGroupTableWindow');
    if (!modal) {
        console.error('Modal element not found');
        return;
    }
    
    // Tampilkan modal
    modal.style.display = 'block';
    modal.classList.remove('hidden');
    
    // Populasi tabel dengan data dari ColorGroupSeeder jika perlu
    populateColorGroupTable();
    
    // Urutkan berdasarkan CG# secara default
    sortTableDirectly(0);
    
    console.log('Modal opened successfully');
}

function closeModalX() {
    closeColorGroupModal();
}

// Fungsi untuk membuat data tersedia tanpa database
function loadSeedData() {
    // Periksa apakah data sudah dimuat
    const tbody = document.querySelector('#colorGroupDataTable tbody');
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
        // Isi tabel dengan data dari seedColorGroups
        populateColorGroupTable();
        
        // Tampilkan notifikasi
        alert('Data color group berhasil dimuat dari ColorGroupSeeder');
        
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
                <p class="text-sm font-medium text-green-800">Data tersedia: ${seedColorGroups.length} color group ditemukan (dari JavaScript).</p>
            `;
        }
        
        // Buka modal untuk menampilkan data
        openColorGroupModal();
    }, 1000);
}

// Fungsi untuk menyimpan perubahan color group
function saveColorGroupChanges() {
    console.log('Saving color group changes');
    
    const cgId = document.getElementById('edit_cg_id').value;
    const cgName = document.getElementById('edit_cg_name').value;
    const cgType = document.getElementById('edit_cg_type').value;
    
    console.log('Form data to save:', { cgId, cgName, cgType });
    
    // Validasi input sudah ditangani oleh atribut required di form
    
    // Tampilkan indikator loading pada tombol dan overlay
    const saveButton = document.querySelector('#editColorGroupForm button[type="submit"]');
    const originalText = saveButton.innerText;
    saveButton.innerText = 'Menyimpan...';
    saveButton.disabled = true;
    
    // Tampilkan loading overlay
    document.getElementById('loadingOverlay').classList.remove('hidden');
    
    // Update row in table immediately to provide visual feedback
    const row = document.querySelector(`#colorGroupDataTable tbody tr[data-cg-id="${cgId}"]`);
    if (row) {
        console.log('Found row to update:', row);
        
        try {
            // Direct DOM manipulation for cell updates
            const cells = row.cells;
            console.log('Row has cells:', cells.length);
            
            if (cells.length >= 3) {
                console.log('Original cell values:');
                console.log('- Cell 0 (CG#):', cells[0].textContent);
                console.log('- Cell 1 (CG Name):', cells[1].textContent);
                console.log('- Cell 2 (CG Type):', cells[2].textContent);
                
                // Update cell text directly
                cells[0].textContent = cgId;
                cells[1].textContent = cgName;
                cells[2].textContent = cgType;
                
                console.log('After update:');
                console.log('- Cell 0 (CG#):', cells[0].textContent);
                console.log('- Cell 1 (CG Name):', cells[1].textContent);
                console.log('- Cell 2 (CG Type):', cells[2].textContent);
                
                // Update data attributes
                row.setAttribute('data-cg-id', cgId);
                row.setAttribute('data-cg-name', cgName);
                row.setAttribute('data-cg-type', cgType);
                
                // Re-apply selected class to ensure visibility
                row.classList.add('selected');
                
                // Also update seedColorGroups array to keep data in sync
                updateSeedColorGroupData(cgId, cgName, cgType);
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
        const tbody = document.querySelector('#colorGroupDataTable tbody');
        
        // Create a new row
        const newRow = document.createElement('tr');
        newRow.className = 'cursor-pointer';
        newRow.setAttribute('data-cg-id', cgId);
        newRow.setAttribute('data-cg-name', cgName);
        newRow.setAttribute('data-cg-type', cgType);
        newRow.onclick = function(e) { selectRow(this); e.stopPropagation(); };
        newRow.ondblclick = function() { openEditColorGroupModal(this); };
        
        // Create cells
        const idCell = document.createElement('td');
        idCell.className = 'px-2 py-0.5 border-b border-r border-gray-300';
        idCell.textContent = cgId;
        
        const nameCell = document.createElement('td');
        nameCell.className = 'px-2 py-0.5 border-b border-r border-gray-300';
        nameCell.textContent = cgName;
        
        const typeCell = document.createElement('td');
        typeCell.className = 'px-2 py-0.5 border-b border-gray-300';
        typeCell.textContent = cgType;
        
        // Add cells to row
        newRow.appendChild(idCell);
        newRow.appendChild(nameCell);
        newRow.appendChild(typeCell);
        
        // Add row to table
        tbody.appendChild(newRow);
        
        // Select the new row
        selectRow(newRow);
        
        // Add to seedColorGroups
        updateSeedColorGroupData(cgId, cgName, cgType);
    }
    
    // Show success message and close modal
    alert('Data color group berhasil diperbarui');
    closeEditColorGroupModal();
    
    // Reset button state and hide loading overlay after a short delay
    setTimeout(() => {
        saveButton.innerText = originalText;
        saveButton.disabled = false;
        document.getElementById('loadingOverlay').classList.add('hidden');
    }, 500);
}

// Fungsi untuk memperbarui data di seedColorGroups array
function updateSeedColorGroupData(cgId, cgName, cgType) {
    // Cari color group dengan ID yang sesuai di seedColorGroups array
    const groupIndex = seedColorGroups.findIndex(group => group.cg === cgId);
    
    if (groupIndex !== -1) {
        console.log(`Updating seedColorGroups[${groupIndex}] with new data`);
        
        // Update data di array
        seedColorGroups[groupIndex].cg_name = cgName;
        seedColorGroups[groupIndex].cg_type = cgType;
        
        console.log('Updated seedColorGroups:', seedColorGroups[groupIndex]);
    } else {
        console.log(`Color group with ID ${cgId} not found in seedColorGroups array`);
        
        // Jika tidak ditemukan, tambahkan sebagai item baru
        seedColorGroups.push({
            cg: cgId,
            cg_name: cgName,
            cg_type: cgType
        });
        
        console.log('Added new color group to seedColorGroups array');
    }
}

// Fungsi untuk mengatur event pada baris tabel
function setupTableRowEvents() {
    console.log('Setting up table row events');
    var tableRows = document.querySelectorAll('#colorGroupDataTable tbody tr');
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
            openEditColorGroupModal(this);
        };
    });
}
</script>

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
                    <label class="text-gray-600">Color Group#:</label>
                    <input type="text" class="border border-gray-300 rounded px-3 py-2 w-48">
                    <button type="button" id="showColorGroupTableBtn" class="bg-gray-200 hover:bg-gray-300 px-2 py-2 border border-gray-400" onclick="openColorGroupModal()">
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
            @if(empty($colorGroups) || count($colorGroups) === 0)
            <div class="mt-4 bg-yellow-100 p-3 rounded">
                <p class="text-sm font-medium text-yellow-800">Tidak ada data color group yang tersedia.</p>
                <p class="text-xs text-yellow-700 mt-1">Pastikan database telah diatur dengan benar dan data seeder telah dijalankan.</p>
                <div class="mt-2 flex items-center space-x-3">
                    <a href="{{ route('run.colorgroup.seeder') }}" class="bg-blue-500 hover:bg-blue-600 text-white text-xs px-3 py-1 rounded">
                        Run Color Group Seeder (DB)
                    </a>
                    <button onclick="loadSeedData()" class="bg-green-500 hover:bg-green-600 text-white text-xs px-3 py-1 rounded">
                        Load Color Group Seeder Data (JS)
                    </button>
                </div>
            </div>
            @elseif(count($colorGroups) > 0)
            <div class="mt-4 bg-green-100 p-3 rounded">
                <p class="text-sm font-medium text-green-800">Data tersedia: {{ count($colorGroups) }} color group ditemukan.</p>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Color Group Table Window -->
<div id="colorGroupTableWindow" class="hidden fixed inset-0 z-50">
    <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white border-2 border-gray-500 shadow-lg" style="width: 560px;">
        <!-- Modal Header - Title Bar -->
        <div class="bg-blue-800 px-1 py-0.5 text-white flex justify-between items-center">
            <h3 class="text-xs font-normal">Color Group Table</h3>
            <button type="button" onclick="closeModalX()" class="text-white hover:text-gray-200 focus:outline-none">
                <span class="text-lg">×</span>
            </button>
        </div>

        <!-- Table Content -->
        <div class="p-1">
            <div class="border border-gray-300 overflow-hidden">
                <table class="w-full" id="colorGroupDataTable">
                    <thead>
                        <tr>
                            <th class="px-2 py-0.5 bg-gray-100 border-b border-r border-gray-300 text-left text-xs font-semibold">CG#</th>
                            <th class="px-2 py-0.5 bg-gray-100 border-b border-r border-gray-300 text-left text-xs font-semibold">CG Name</th>
                            <th class="px-2 py-0.5 bg-gray-100 border-b border-gray-300 text-left text-xs font-semibold">CG Type</th>
                        </tr>
                    </thead>
                    <tbody class="text-xs">
                        <!-- Data dari database akan ditampilkan di sini jika tersedia -->
                        <!-- Jika tidak ada data dari database, JavaScript akan mengisi dengan data dari ColorGroupSeeder -->
                        @if(isset($colorGroups) && count($colorGroups) > 0)
                        @foreach($colorGroups as $group)
                            <tr class="cursor-pointer" 
                                data-cg-id="{{ $group->cg }}"
                                data-cg-name="{{ $group->cg_name }}"
                                data-cg-type="{{ $group->cg_type }}"
                                onclick="selectRow(this); event.stopPropagation();"
                                ondblclick="openEditColorGroupModal(this)">
                                <td class="px-2 py-0.5 border-b border-r border-gray-300">{{ $group->cg }}</td>
                                <td class="px-2 py-0.5 border-b border-r border-gray-300">{{ $group->cg_name }}</td>
                                <td class="px-2 py-0.5 border-b border-gray-300">{{ $group->cg_type }}</td>
                            </tr>
                        @endforeach
                        @else
                            <tr>
                                <td colspan="3" class="px-2 py-4 text-center border-b border-gray-300">Tidak ada data color group yang tersedia.</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>

            <!-- Bottom Buttons with equal spacing -->
            <div class="flex justify-between mt-2">
                <button type="button" onclick="sortTableDirectly(0)" class="py-0.5 px-2 bg-gray-100 border border-gray-400 hover:bg-gray-200 text-xs w-20">By CG#</button>
                <button type="button" onclick="sortTableDirectly(1)" class="py-0.5 px-2 bg-gray-100 border border-gray-400 hover:bg-gray-200 text-xs w-24">By CG Name</button>
                <button type="button" onclick="sortTableDirectly(2)" class="py-0.5 px-2 bg-gray-100 border border-gray-400 hover:bg-gray-200 text-xs w-28">By CG Type</button>
                <button type="button" onclick="editSelectedRow()" class="py-0.5 px-2 bg-gray-100 border border-gray-400 hover:bg-gray-200 text-xs w-16">Select</button>
                <button type="button" onclick="closeColorGroupModal()" class="py-0.5 px-2 bg-gray-100 border border-gray-400 hover:bg-gray-200 text-xs w-16">Exit</button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Color Group Modal -->
<div id="editColorGroupModal" class="hidden fixed inset-0 z-50 bg-black bg-opacity-30">
    <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white border-2 border-gray-500 shadow-lg" style="width: 350px;">
        <!-- Modal Header - Title Bar -->
        <div class="bg-blue-800 px-1 py-0.5 text-white flex justify-between items-center">
            <h3 class="text-xs font-normal">Define Color Group</h3>
            <button type="button" onclick="closeEditColorGroupModal()" class="text-white hover:text-gray-200 focus:outline-none">
                <span class="text-lg">×</span>
            </button>
        </div>

        <!-- Form Content -->
        <div class="p-4">
            <form id="editColorGroupForm" onsubmit="saveColorGroupChanges(); return false;">
                <div class="space-y-3">
                    <div class="flex items-center">
                        <label class="w-24 text-xs text-gray-600">CG#:</label>
                        <input id="edit_cg_id" type="text" class="border border-gray-300 rounded px-2 py-1 text-xs w-24" required>
                    </div>
                    <div class="flex items-center">
                        <label class="w-24 text-xs text-gray-600">CG Name:</label>
                        <input id="edit_cg_name" type="text" class="border border-gray-300 rounded px-2 py-1 text-xs w-full" required>
                    </div>
                    <div class="flex items-center">
                        <label class="w-24 text-xs text-gray-600">CG Type:</label>
                        <select id="edit_cg_type" class="border border-gray-300 rounded px-2 py-1 text-xs w-32" required>
                            <option value="X-Flex">X-Flex</option>
                            <option value="C-Coating">C-Coating</option>
                            <option value="S-Offset">S-Offset</option>
                        </select>
                    </div>
                </div>
                <div class="flex justify-end mt-4">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white text-xs px-4 py-1 rounded mr-2">Save</button>
                    <button type="button" onclick="closeEditColorGroupModal()" class="bg-gray-300 hover:bg-gray-400 text-gray-800 text-xs px-4 py-1 rounded">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Inisialisasi event handler saat dokumen dimuat
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM content loaded');
    
    // Test if the edit modal exists
    const editModal = document.getElementById('editColorGroupModal');
    if (editModal) {
        console.log('Edit modal found in DOM');
    } else {
        console.error('Edit modal not found in DOM');
    }
    
    // Dapatkan tombol dan modal
    var showBtn = document.getElementById('showColorGroupTableBtn');
    var colorGroupModal = document.getElementById('colorGroupTableWindow');
    
    // Tambahkan event listener ke tombol untuk menampilkan modal
    if (showBtn) {
        showBtn.onclick = function() {
            if (colorGroupModal) {
                colorGroupModal.style.display = 'block';
                colorGroupModal.classList.remove('hidden');
                
                // Populasi tabel dengan data dari ColorGroupSeeder jika perlu
                populateColorGroupTable();
                
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
    if (colorGroupModal) {
        colorGroupModal.onclick = function(e) {
            if (e.target === colorGroupModal) {
                closeColorGroupModal();
            }
        };
    }
    
    // Event untuk menutup modal saat mengklik di luar
    window.onclick = function(event) {
        const editModal = document.getElementById('editColorGroupModal');
        if (event.target === editModal) {
            closeEditColorGroupModal();
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
        var firstRow = document.querySelector('#colorGroupDataTable tbody tr');
        if (firstRow) {
            openEditColorGroupModal(firstRow);
        } else {
            console.error('No rows found to test with');
        }
    };
    document.body.appendChild(testBtn);
});
</script>
@endpush

@push('styles')
<style>
    /* Modal window styles */
    #colorGroupTableWindow, #editColorGroupModal {
        background-color: rgba(0, 0, 0, 0.2);
        z-index: 9999;
    }
    
    #colorGroupTableWindow:not(.hidden), #editColorGroupModal:not(.hidden) {
        display: block;
    }
    
    #colorGroupTableWindow.hidden, #editColorGroupModal.hidden {
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
    #colorGroupDataTable {
        border-collapse: collapse;
        width: 100%;
    }
    
    #colorGroupDataTable tbody {
        display: block;
        max-height: 250px;
        overflow-y: auto;
        overflow-x: hidden;
    }
    
    #colorGroupDataTable thead {
        display: table;
        width: calc(100% - 17px); /* Adjust for scrollbar width */
        table-layout: fixed;
    }
    
    #colorGroupDataTable tbody tr {
        display: table;
        width: 100%;
        table-layout: fixed;
    }
    
    /* Selected row */
    #colorGroupDataTable tbody tr.selected {
        background-color: #0078d7;
        color: white;
    }
    
    #colorGroupDataTable tbody tr:hover {
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
    #colorGroupDataTable tbody::-webkit-scrollbar {
        width: 16px;
    }
    
    #colorGroupDataTable tbody::-webkit-scrollbar-track {
        background: #f1f1f1;
    }
    
    #colorGroupDataTable tbody::-webkit-scrollbar-thumb {
        background: #c1c1c1;
        border: 1px solid #a1a1a1;
    }
    
    #colorGroupDataTable tbody::-webkit-scrollbar-thumb:hover {
        background: #a1a1a1;
    }
</style>
@endpush
@endsection 