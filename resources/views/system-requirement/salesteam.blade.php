@extends('layouts.app')

@section('title', 'Define Sales Team')

@section('header', 'Define Sales Team')

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
                    <label class="text-gray-600">Sales Team Code:</label>
                    <input type="text" class="border border-gray-300 rounded px-3 py-2 w-48">
                    <button type="button" id="showSalesTeamTableBtn" class="bg-gray-200 hover:bg-gray-300 px-2 py-2 border border-gray-400" onclick="openSalesTeamModal()">
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
            @if(empty($salesTeams) || count($salesTeams) === 0)
            <div class="mt-4 bg-yellow-100 p-3 rounded">
                <p class="text-sm font-medium text-yellow-800">Tidak ada data sales team yang tersedia.</p>
                <p class="text-xs text-yellow-700 mt-1">Pastikan database telah diatur dengan benar dan data seeder telah dijalankan.</p>
                <div class="mt-2 flex items-center space-x-3">
                    <a href="{{ route('run.salesteam.seeder') }}" class="bg-blue-500 hover:bg-blue-600 text-white text-xs px-3 py-1 rounded">
                        Run Sales Team Seeder (DB)
                    </a>
                    <button onclick="loadSeedData()" class="bg-green-500 hover:bg-green-600 text-white text-xs px-3 py-1 rounded">
                        Load Sales Team Seeder Data (JS)
                </button>
                </div>
            </div>
            @elseif(count($salesTeams) > 0)
            <div class="mt-4 bg-green-100 p-3 rounded">
                <p class="text-sm font-medium text-green-800">Data tersedia: {{ count($salesTeams) }} sales team ditemukan.</p>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Sales Team Table Window -->
<div id="salesTeamTableWindow" class="hidden fixed inset-0 z-50">
    <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white border-2 border-gray-500 shadow-lg" style="width: 560px;">
        <!-- Modal Header - Title Bar -->
        <div class="bg-blue-800 px-1 py-0.5 text-white flex justify-between items-center">
            <h3 class="text-xs font-normal">Sales Team Table</h3>
            <button type="button" onclick="closeModalX()" class="text-white hover:text-gray-200 focus:outline-none">
                <span class="text-lg">×</span>
            </button>
        </div>
        
        <!-- Table Content -->
        <div class="p-1">
            <div class="border border-gray-300 overflow-hidden">
                <table class="w-full" id="salesTeamDataTable">
                <thead>
                        <tr>
                            <th class="px-2 py-0.5 bg-gray-100 border-b border-r border-gray-300 text-left text-xs font-semibold">Code</th>
                            <th class="px-2 py-0.5 bg-gray-100 border-b border-gray-300 text-left text-xs font-semibold">Name</th>
                    </tr>
                </thead>
                    <tbody class="text-xs">
                        <!-- Data dari database akan ditampilkan di sini jika tersedia -->
                        <!-- Jika tidak ada data dari database, JavaScript akan mengisi dengan data dari SalesTeamSeeder -->
                        @if(isset($salesTeams) && count($salesTeams) > 0)
                    @foreach($salesTeams as $team)
                            <tr class="cursor-pointer" 
                                data-team-code="{{ $team->code }}"
                                data-team-name="{{ $team->name }}"
                                onclick="selectRow(this); event.stopPropagation();"
                                ondblclick="openEditSalesTeamModal(this)">
                                <td class="px-2 py-0.5 border-b border-r border-gray-300">{{ $team->code }}</td>
                                <td class="px-2 py-0.5 border-b border-gray-300">{{ $team->name }}</td>
                    </tr>
                    @endforeach
                        @else
                            <tr>
                                <td colspan="2" class="px-2 py-4 text-center border-b border-gray-300">Tidak ada data sales team yang tersedia.</td>
                            </tr>
                        @endif
                </tbody>
            </table>
        </div>
        
            <!-- Bottom Buttons with equal spacing -->
            <div class="flex justify-between mt-2">
                <button type="button" onclick="sortTableDirectly(0)" class="py-0.5 px-2 bg-gray-100 border border-gray-400 hover:bg-gray-200 text-xs w-24">By Code</button>
                <button type="button" onclick="sortTableDirectly(1)" class="py-0.5 px-2 bg-gray-100 border border-gray-400 hover:bg-gray-200 text-xs w-24">By Name</button>
                <button type="button" onclick="editSelectedRow()" class="py-0.5 px-2 bg-gray-100 border border-gray-400 hover:bg-gray-200 text-xs w-24">Select</button>
                <button type="button" onclick="closeSalesTeamModal()" class="py-0.5 px-2 bg-gray-100 border border-gray-400 hover:bg-gray-200 text-xs w-24">Exit</button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Sales Team Modal -->
<div id="editSalesTeamModal" class="hidden fixed inset-0 z-50 bg-black bg-opacity-30">
    <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white border-2 border-gray-500 shadow-lg" style="width: 350px;">
        <!-- Modal Header - Title Bar -->
        <div class="bg-blue-800 px-1 py-0.5 text-white flex justify-between items-center">
            <h3 class="text-xs font-normal">Define Sales Team</h3>
            <button type="button" onclick="closeEditSalesTeamModal()" class="text-white hover:text-gray-200 focus:outline-none">
                <span class="text-lg">×</span>
            </button>
        </div>

        <!-- Form Content -->
        <div class="p-4">
            <form id="editSalesTeamForm" onsubmit="saveSalesTeamChanges(); return false;">
                <div class="space-y-3">
                    <div class="flex items-center">
                        <label class="w-24 text-xs text-gray-600">Code:</label>
                        <input id="edit_team_code" type="text" class="border border-gray-300 rounded px-2 py-1 text-xs w-24" required>
                    </div>
                    <div class="flex items-center">
                        <label class="w-24 text-xs text-gray-600">Name:</label>
                        <input id="edit_team_name" type="text" class="border border-gray-300 rounded px-2 py-1 text-xs w-full" required>
                    </div>
                </div>
                <div class="flex justify-end mt-4">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white text-xs px-4 py-1 rounded mr-2">Save</button>
                    <button type="button" onclick="closeEditSalesTeamModal()" class="bg-gray-300 hover:bg-gray-400 text-gray-800 text-xs px-4 py-1 rounded">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
// Data dari SalesTeamSeeder langsung (sebagai fallback jika tidak ada data dari database)
const seedSalesTeams = [
    {
        code: '01',
        name: 'MBI'
    },
    {
        code: '02',
        name: 'MANAGEMENT LOCAL'
    },
    {
        code: '03',
        name: 'MANAGEMENT MNC'
    }
];

// Fungsi untuk mengisi tabel dengan data dari seedSalesTeams jika tidak ada data dari database
function populateSalesTeamTable() {
    console.log('Populating sales team table');
    
    // Cek apakah tabel sudah memiliki data
    const tbody = document.querySelector('#salesTeamDataTable tbody');
    const rows = tbody.querySelectorAll('tr');
    const isEmpty = rows.length === 0 || (rows.length === 1 && rows[0].querySelector('td[colspan]'));
    
    if (isEmpty) {
        console.log('Table is empty, populating with seed data');
        
        // Hapus pesan "tidak ada data" jika ada
        tbody.innerHTML = '';
        
        // Isi tabel dengan data dari seedSalesTeams
        seedSalesTeams.forEach(team => {
            const row = document.createElement('tr');
            row.className = 'cursor-pointer';
            row.setAttribute('data-team-code', team.code);
            row.setAttribute('data-team-name', team.name);
            row.onclick = function(e) { selectRow(this); e.stopPropagation(); };
            row.ondblclick = function() { openEditSalesTeamModal(this); };
            
            // Buat kolom untuk setiap sel
            const codeCell = document.createElement('td');
            codeCell.className = 'px-2 py-0.5 border-b border-r border-gray-300';
            codeCell.textContent = team.code;
            
            const nameCell = document.createElement('td');
            nameCell.className = 'px-2 py-0.5 border-b border-gray-300';
            nameCell.textContent = team.name;
            
            // Tambahkan semua sel ke baris
            row.appendChild(codeCell);
            row.appendChild(nameCell);
            
            // Tambahkan baris ke tabel
            tbody.appendChild(row);
        });
        
        console.log('Table populated with seed data');
        
        // Setup event handlers for the table rows
        if (typeof setupTableRowEvents === 'function') {
            setupTableRowEvents();
        }
        
        // Urutkan tabel berdasarkan Code
        sortTableDirectly(0);
    } else {
        console.log('Table already has data, no need to populate');
    }
}

// Fungsi mengurutkan tabel
function sortTableDirectly(columnIndex) {
    console.log("Sorting by column: " + columnIndex);
    
    var table = document.getElementById('salesTeamDataTable');
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

// Variable to store the currently selected row
let selectedRow = null;

// Function to select a row in the table
function selectRow(row) {
    // Remove the highlight from the previously selected row
    if (selectedRow) {
        selectedRow.classList.remove('bg-blue-100');
    }
    
    // Highlight the newly selected row
    row.classList.add('bg-blue-100');
    selectedRow = row;
}

// Function to open the sales team modal
function openSalesTeamModal() {
    console.log('Opening sales team modal');
    const modal = document.getElementById('salesTeamTableWindow');
    if (modal) {
        modal.style.display = 'block';
        modal.classList.remove('hidden');
        
        // Populate the table with seed data if needed
        populateSalesTeamTable();
        
        // Sort by the first column by default
        sortTableDirectly(0);
    } else {
        console.error('Sales team modal not found');
    }
}

// Function to close the sales team modal using the X button
function closeModalX() {
    closeSalesTeamModal();
}

// Function to close the sales team modal
function closeSalesTeamModal() {
    console.log('Closing sales team modal');
    const modal = document.getElementById('salesTeamTableWindow');
    if (modal) {
        modal.style.display = 'none';
        modal.classList.add('hidden');
    }
}

// Function to edit the selected row
function editSelectedRow() {
    console.log('Edit selected row function called');
    if (selectedRow) {
        openEditSalesTeamModal(selectedRow);
    } else {
        alert('Please select a row to edit');
    }
}

// Function to open the edit modal for a sales team
function openEditSalesTeamModal(row) {
    console.log('Opening edit modal for row', row);
    const code = row.getAttribute('data-team-code');
    const name = row.getAttribute('data-team-name');
    
    document.getElementById('edit_team_code').value = code;
    document.getElementById('edit_team_name').value = name;
    
    const modal = document.getElementById('editSalesTeamModal');
    if (modal) {
        modal.style.display = 'block';
        modal.classList.remove('hidden');
    } else {
        console.error('Edit modal not found');
    }
}

// Function to close the edit modal
function closeEditSalesTeamModal() {
    console.log('Closing edit modal');
    const modal = document.getElementById('editSalesTeamModal');
    if (modal) {
        modal.style.display = 'none';
        modal.classList.add('hidden');
    }
}

// Function to save the changes made in the edit modal
function saveSalesTeamChanges() {
    console.log('Saving sales team changes');
    
    const code = document.getElementById('edit_team_code').value;
    const name = document.getElementById('edit_team_name').value;
    
    console.log('Form data to save:', { code, name });
    
    // Validasi input sudah ditangani oleh atribut required di form
    
    // Tampilkan indikator loading pada tombol dan overlay
    const saveButton = document.querySelector('#editSalesTeamForm button[type="submit"]');
    const originalText = saveButton.innerText;
    saveButton.innerText = 'Menyimpan...';
    saveButton.disabled = true;
    
    // Tampilkan loading overlay
    document.getElementById('loadingOverlay').classList.remove('hidden');
    
    // Update row in table immediately to provide visual feedback
    const row = document.querySelector(`#salesTeamDataTable tbody tr[data-team-code="${code}"]`);
    if (row) {
        console.log('Found row to update:', row);
        
        try {
            // Direct DOM manipulation for cell updates
            const cells = row.cells;
            console.log('Row has cells:', cells.length);
            
            if (cells.length >= 2) {
                console.log('Original cell values:');
                console.log('- Cell 0 (Code):', cells[0].textContent);
                console.log('- Cell 1 (Name):', cells[1].textContent);
                
                // Update cell text directly
                cells[0].textContent = code;
                cells[1].textContent = name;
                
                console.log('After update:');
                console.log('- Cell 0 (Code):', cells[0].textContent);
                console.log('- Cell 1 (Name):', cells[1].textContent);
                
                // Update data attributes
                row.setAttribute('data-team-code', code);
                row.setAttribute('data-team-name', name);
                
                // Re-apply selected class to ensure visibility
                row.classList.add('selected');
                
                // Also update seedSalesTeams array to keep data in sync
                updateSeedSalesTeamData(code, name);
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
        const tbody = document.querySelector('#salesTeamDataTable tbody');
        
        // Create a new row
        const newRow = document.createElement('tr');
        newRow.className = 'cursor-pointer';
        newRow.setAttribute('data-team-code', code);
        newRow.setAttribute('data-team-name', name);
        newRow.onclick = function(e) { selectRow(this); e.stopPropagation(); };
        newRow.ondblclick = function() { openEditSalesTeamModal(this); };
        
        // Create cells
        const codeCell = document.createElement('td');
        codeCell.className = 'px-2 py-0.5 border-b border-r border-gray-300';
        codeCell.textContent = code;
        
        const nameCell = document.createElement('td');
        nameCell.className = 'px-2 py-0.5 border-b border-gray-300';
        nameCell.textContent = name;
        
        // Add cells to row
        newRow.appendChild(codeCell);
        newRow.appendChild(nameCell);
        
        // Add row to table
        tbody.appendChild(newRow);
        
        // Select the new row
        selectRow(newRow);
        
        // Add to seedSalesTeams
        updateSeedSalesTeamData(code, name);
    }
    
    // Show success message and close modal
    alert('Data sales team berhasil diperbarui');
    closeEditSalesTeamModal();
    
    // Reset button state and hide loading overlay after a short delay
    setTimeout(() => {
        saveButton.innerText = originalText;
        saveButton.disabled = false;
        document.getElementById('loadingOverlay').classList.add('hidden');
    }, 500);
}

// Fungsi untuk memperbarui data di seedSalesTeams array
function updateSeedSalesTeamData(code, name) {
    // Cari sales team dengan code yang sesuai di seedSalesTeams array
    const teamIndex = seedSalesTeams.findIndex(team => team.code === code);
    
    if (teamIndex !== -1) {
        console.log(`Updating seedSalesTeams[${teamIndex}] with new data`);
        
        // Update data di array
        seedSalesTeams[teamIndex].name = name;
        
        console.log('Updated seedSalesTeams:', seedSalesTeams[teamIndex]);
    } else {
        console.log(`Sales team with code ${code} not found in seedSalesTeams array`);
        
        // Jika tidak ditemukan, tambahkan sebagai item baru
        seedSalesTeams.push({
            code: code,
            name: name
        });
        
        console.log('Added new sales team to seedSalesTeams array');
    }
}

// Fungsi untuk mengatur event pada baris tabel
function setupTableRowEvents() {
    console.log('Setting up table row events');
    var tableRows = document.querySelectorAll('#salesTeamDataTable tbody tr');
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
            openEditSalesTeamModal(this);
        };
    });
}

// Inisialisasi event handler saat dokumen dimuat
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM content loaded');
    
    // Test if the edit modal exists
    const editModal = document.getElementById('editSalesTeamModal');
    if (editModal) {
        console.log('Edit modal found in DOM');
    } else {
        console.error('Edit modal not found in DOM');
    }
    
    // Dapatkan tombol dan modal
    var showBtn = document.getElementById('showSalesTeamTableBtn');
    var salesTeamModal = document.getElementById('salesTeamTableWindow');
    
    // Tambahkan event listener ke tombol untuk menampilkan modal
    if (showBtn) {
        showBtn.onclick = function() {
            if (salesTeamModal) {
                salesTeamModal.style.display = 'block';
                salesTeamModal.classList.remove('hidden');
                
                // Populasi tabel dengan data dari SalesTeamSeeder jika perlu
                populateSalesTeamTable();
                
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
    if (salesTeamModal) {
        salesTeamModal.onclick = function(e) {
            if (e.target === salesTeamModal) {
                closeSalesTeamModal();
            }
        };
    }
    
    // Event untuk menutup modal saat mengklik di luar
    window.onclick = function(event) {
        const editModal = document.getElementById('editSalesTeamModal');
        if (event.target === editModal) {
            closeEditSalesTeamModal();
        }
    };
});

// Fungsi untuk mengedit baris terpilih (Edit button)
function editSelectedRow() {
    var selectedRow = document.querySelector('#salesTeamDataTable tbody tr.selected');
    if (!selectedRow) {
        console.log("No row selected");
        alert("Silahkan pilih sales team terlebih dahulu");
        return;
    }
    
    console.log("Selected row for editing:", selectedRow);
    
    // Buka modal edit untuk baris terpilih
    openEditSalesTeamModal(selectedRow);
}

// Fungsi untuk menggunakan sales team yang terpilih (Select button)
function useSelectedSalesTeam() {
    var selectedRow = document.querySelector('#salesTeamDataTable tbody tr.selected');
    if (!selectedRow) {
        console.log("No row selected");
        alert("Silahkan pilih sales team terlebih dahulu");
        return;
    }
    
    var code = selectedRow.cells[0].textContent.trim();
    var name = selectedRow.cells[1].textContent.trim();
    console.log("Selected sales team code: " + code);
    
    // Pilih sales team dan tutup modal
    selectSalesTeam(code, name);
}

// Fungsi memilih baris saat ini dan menutup modal
function selectCurrentRow() {
    var selectedRow = document.querySelector('#salesTeamDataTable tbody tr.selected');
    if (!selectedRow) {
        console.log("No row selected");
        alert("Silahkan pilih sales team terlebih dahulu");
        return;
    }
    
    var code = selectedRow.cells[0].textContent.trim();
    var name = selectedRow.cells[1].textContent.trim();
    console.log("Selected sales team code: " + code);
    
    // Pilih sales team dan tutup modal
    selectSalesTeam(code, name);
}

// Fungsi memilih sales team
function selectSalesTeam(code, name) {
    console.log('Selecting sales team: ' + code + ' - ' + name);
    
    // Isi input form dengan ID sales team yang dipilih
    var codeInput = document.querySelector('input[type="text"]');
    if (codeInput) {
        codeInput.value = code;
    }
    
    // Tutup modal
    closeSalesTeamModal();
}

// Fungsi menutup modal
function closeSalesTeamModal() {
    var modal = document.getElementById('salesTeamTableWindow');
    if (modal) {
        modal.style.display = 'none';
        modal.classList.add('hidden');
    }
}

// Fungsi memilih baris
function selectRow(row) {
    // Hapus kelas 'selected' dari semua baris
    var allRows = document.querySelectorAll('#salesTeamDataTable tbody tr');
    allRows.forEach(function(r) {
        r.classList.remove('selected');
    });
    
    // Tambahkan kelas 'selected' ke baris yang dipilih
    row.classList.add('selected');
}

// Fungsi untuk membuka modal edit
function openEditSalesTeamModal(row) {
    console.log('Opening edit sales team modal for row:', row);
    
    const code = row.getAttribute('data-team-code');
    const name = row.getAttribute('data-team-name');
    
    console.log('Sales team data:', { code, name });
    
    document.getElementById('edit_team_code').value = code;
    document.getElementById('edit_team_name').value = name;
    
    const editModal = document.getElementById('editSalesTeamModal');
    editModal.classList.remove('hidden');
    editModal.style.display = 'block';
    
    console.log('Edit modal opened');
}

// Fungsi untuk menutup modal edit
function closeEditSalesTeamModal() {
    console.log('Closing edit sales team modal');
    const editModal = document.getElementById('editSalesTeamModal');
    editModal.classList.add('hidden');
    editModal.style.display = 'none';
}

// Fungsi untuk menampilkan modal
function openSalesTeamModal() {
    console.log('Opening sales team modal');
    var modal = document.getElementById('salesTeamTableWindow');
    if (!modal) {
        console.error('Modal element not found');
        return;
    }
    
    // Tampilkan modal
    modal.style.display = 'block';
    modal.classList.remove('hidden');
    
    // Populasi tabel dengan data dari SalesTeamSeeder jika perlu
    populateSalesTeamTable();
    
    // Urutkan berdasarkan Code secara default
    sortTableDirectly(0);
    
    console.log('Modal opened successfully');
}

function closeModalX() {
    closeSalesTeamModal();
}

// Fungsi untuk membuat data tersedia tanpa database
function loadSeedData() {
    // Periksa apakah data sudah dimuat
    const tbody = document.querySelector('#salesTeamDataTable tbody');
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
        // Isi tabel dengan data dari seedSalesTeams
        populateSalesTeamTable();
        
        // Tampilkan notifikasi
        alert('Data sales team berhasil dimuat dari SalesTeamSeeder');
        
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
                <p class="text-sm font-medium text-green-800">Data tersedia: ${seedSalesTeams.length} sales team ditemukan (dari JavaScript).</p>
            `;
        }
        
        // Buka modal untuk menampilkan data
        openSalesTeamModal();
    }, 1000);
}
</script>
@endsection

@push('styles')
<style>
    /* Modal window styles */
    #salesTeamTableWindow, #editSalesTeamModal {
        background-color: rgba(0, 0, 0, 0.2);
        z-index: 9999;
    }
    
    #salesTeamTableWindow:not(.hidden), #editSalesTeamModal:not(.hidden) {
        display: block;
    }
    
    #salesTeamTableWindow.hidden, #editSalesTeamModal.hidden {
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
    #salesTeamDataTable {
        border-collapse: collapse;
        width: 100%;
    }
    
    #salesTeamDataTable tbody {
        display: block;
        max-height: 250px;
        overflow-y: auto;
        overflow-x: hidden;
    }
    
    #salesTeamDataTable thead {
        display: table;
        width: calc(100% - 17px); /* Adjust for scrollbar width */
        table-layout: fixed;
    }
    
    #salesTeamDataTable tbody tr {
        display: table;
        width: 100%;
        table-layout: fixed;
    }
    
    /* Selected row */
    #salesTeamDataTable tbody tr.selected {
        background-color: #0078d7;
        color: white;
    }
    
    #salesTeamDataTable tbody tr:hover {
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
    #salesTeamDataTable tbody::-webkit-scrollbar {
        width: 16px;
    }
    
    #salesTeamDataTable tbody::-webkit-scrollbar-track {
        background: #f1f1f1;
    }
    
    #salesTeamDataTable tbody::-webkit-scrollbar-thumb {
        background: #c1c1c1;
        border: 1px solid #a1a1a1;
    }
    
    #salesTeamDataTable tbody::-webkit-scrollbar-thumb:hover {
        background: #a1a1a1;
    }
</style>
@endpush
