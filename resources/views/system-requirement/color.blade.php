@extends('layouts.app')

@section('title', 'Define Color')

<!-- Add direct script for opening modal -->
<script>
// Fungsi mengurutkan tabel
function sortTableDirectly(columnIndex) {
    console.log("Sorting by column: " + columnIndex);
    
    var table = document.getElementById('colorDataTable');
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
        if (columnIndex === 0 || columnIndex === 3) {
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
    
    // Highlight baris warna berdasarkan color group
    highlightBlueRows();
    
    console.log("Sorting complete");
}

// Fungi memilih baris saat ini dan menutup modal
function selectCurrentRow() {
    var selectedRow = document.querySelector('#colorDataTable tbody tr.selected');
    if (!selectedRow) {
        console.log("No row selected");
        return;
    }
    
    var colorId = selectedRow.cells[0].textContent.trim();
    var colorName = selectedRow.cells[1].textContent.trim();
    console.log("Selected color ID: " + colorId);
    
    // Pilih warna dan tutup modal
    selectColor(colorId, colorName);
}

// Fungsi memilih warna
function selectColor(colorId, colorName) {
    console.log('Selecting color: ' + colorId + ' - ' + colorName);
    
    // Isi input form dengan ID warna yang dipilih
    var colorInput = document.querySelector('input[type="text"]');
    if (colorInput) {
        colorInput.value = colorId;
    }
    
    // Tutup modal
    closeColorModal();
}

// Fungsi menutup modal
function closeColorModal() {
    var modal = document.getElementById('colorTableWindow');
    if (modal) {
        modal.style.display = 'none';
        modal.classList.add('hidden');
    }
}

// Fungsi memilih baris
function selectRow(row) {
    // Hapus kelas 'selected' dari semua baris
    var allRows = document.querySelectorAll('#colorDataTable tbody tr');
    allRows.forEach(function(r) {
        r.classList.remove('selected');
    });
    
    // Tambahkan kelas 'selected' ke baris yang dipilih
    row.classList.add('selected');
}

// Fungsi untuk menampilkan modal
function openColorModal() {
    console.log('Opening color modal');
    var modal = document.getElementById('colorTableWindow');
    if (!modal) {
        console.error('Modal element not found');
        return;
    }
    
    // Tampilkan modal
    modal.style.display = 'block';
    modal.classList.remove('hidden');
    
    // Urutkan berdasarkan Color# secara default
    sortTableDirectly(0);
    
    console.log('Modal opened successfully');
}

// Fungsi untuk memberikan warna biru pada baris tertentu
function highlightBlueRows() {
    // Color rows based on the pattern shown in the image
    var rows = document.querySelectorAll('#colorDataTable tbody tr');
    rows.forEach(function(row) {
        // Skip the "no data" row if present
        if (row.querySelector('td[colspan="6"]')) return;
        
        // Clear any existing color classes
        row.classList.remove('blue-row');
        
        // Get the color group ID from the row
        var cgId = row.getAttribute('data-cg-id');
        
        // Apply blue-row class based on the colorGroup patterns
        // Using the pattern from the image (blue for groups 01, 04, 06, 08)
        if (cgId === '01' || cgId === '04' || cgId === '06' || cgId === '08') {
            row.classList.add('blue-row');
        }
    });
}

// Mapping nama Color Group berdasarkan ID
function getCGName(cgId) {
    // Map of color group IDs to names based on ColorGroupSeeder
    const cgNames = {
        '01': 'BLACK',
        '02': 'WHITE',
        '03': 'RED',
        '04': 'BLUE',
        '05': 'GREEN',
        '06': 'CYAN',
        '07': 'MAGENTA',
        '08': 'PINK'
    };
    
    return cgNames[cgId] || '';
}

function closeModalX() {
    closeColorModal();
}
</script>

@section('content')
<div class="container mx-auto px-4 py-6">
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
                <label class="text-gray-600">Color#:</label>
                <input type="text" class="border border-gray-300 rounded px-3 py-2 w-48">
                <button type="button" id="showColorTableBtn" class="bg-gray-200 hover:bg-gray-300 px-2 py-2 border border-gray-400" onclick="openColorModal()">
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
        @if(empty($colors) || count($colors) === 0)
        <div class="mt-4 bg-yellow-100 p-3 rounded">
            <p class="text-sm font-medium text-yellow-800">Tidak ada data warna yang tersedia.</p>
            <p class="text-xs text-yellow-700 mt-1">Pastikan database telah diatur dengan benar dan data seeder telah dijalankan.</p>
            <div class="mt-2 flex items-center space-x-3">
                <a href="{{ route('run.color.seeder') }}" class="bg-blue-500 hover:bg-blue-600 text-white text-xs px-3 py-1 rounded">
                    Run Color Seeder
                </a>
                <span class="text-xs text-yellow-700">atau jalankan: <code class="bg-yellow-200 px-1 py-0.5 rounded">php artisan db:seed --class=ColorSeeder</code></span>
            </div>
        </div>
        @elseif(count($colors) > 0)
        <div class="mt-4 bg-green-100 p-3 rounded">
            <p class="text-sm font-medium text-green-800">Data tersedia: {{ count($colors) }} warna ditemukan.</p>
                    </div>
        @endif
                    </div>
            </div>

<!-- Color Table Window -->
<div id="colorTableWindow" class="hidden fixed inset-0 z-50">
    <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white border-2 border-gray-500 shadow-lg" style="width: 560px;">
        <!-- Modal Header - Title Bar -->
        <div class="bg-blue-800 px-1 py-0.5 text-white flex justify-between items-center">
            <h3 class="text-xs font-normal">Color Table</h3>
            <button type="button" onclick="closeModalX()" class="text-white hover:text-gray-200 focus:outline-none">
                <span class="text-lg">×</span>
            </button>
        </div>

        <!-- Table Content -->
        <div class="p-1">
            <div class="border border-gray-300 overflow-hidden">
                <table class="w-full" id="colorDataTable">
                    <thead>
                        <tr>
                            <th class="px-2 py-0.5 bg-gray-100 border-b border-r border-gray-300 text-left text-xs font-semibold">Color#</th>
                            <th class="px-2 py-0.5 bg-gray-100 border-b border-r border-gray-300 text-left text-xs font-semibold">Color Name</th>
                            <th class="px-2 py-0.5 bg-gray-100 border-b border-r border-gray-300 text-left text-xs font-semibold">Origin</th>
                            <th class="px-2 py-0.5 bg-gray-100 border-b border-r border-gray-300 text-left text-xs font-semibold">CG#</th>
                            <th class="px-2 py-0.5 bg-gray-100 border-b border-r border-gray-300 text-left text-xs font-semibold">CG Name</th>
                            <th class="px-2 py-0.5 bg-gray-100 border-b border-gray-300 text-left text-xs font-semibold">CG Type</th>
                    </tr>
                </thead>
                    <tbody class="text-xs">
                        @if(isset($colors) && count($colors) > 0)
                    @foreach($colors as $color)
                            <tr class="cursor-pointer" 
                                data-color-id="{{ $color->color_id }}"
                                data-color-name="{{ $color->color_name }}"
                                data-origin="{{ $color->origin }}"
                                data-cg-id="{{ $color->color_group_id }}"
                                data-cg-type="{{ $color->cg_type ?? '' }}"
                                onclick="selectRow(this); event.stopPropagation();"
                                ondblclick="selectColor('{{ $color->color_id }}', '{{ $color->color_name }}')">
                                <td class="px-2 py-0.5 border-b border-r border-gray-300">{{ $color->color_id }}</td>
                                <td class="px-2 py-0.5 border-b border-r border-gray-300">{{ $color->color_name }}</td>
                                <td class="px-2 py-0.5 border-b border-r border-gray-300">{{ $color->origin }}</td>
                                <td class="px-2 py-0.5 border-b border-r border-gray-300">{{ $color->color_group_id }}</td>
                                <td class="px-2 py-0.5 border-b border-r border-gray-300">
                                    @if(isset($colorGroups) && count($colorGroups) > 0)
                                        @php $cgName = ''; @endphp
                                        @foreach($colorGroups as $cg)
                                            @if(isset($cg->cg) && $cg->cg == $color->color_group_id)
                                                @php $cgName = $cg->cg_name; @endphp
                                            @endif
                                        @endforeach
                                        {{ $cgName }}
                                    @else
                                        {{ $color->color_group_id }}
                                    @endif
                        </td>
                                <td class="px-2 py-0.5 border-b border-gray-300">{{ $color->cg_type ?? '' }}</td>
                    </tr>
                    @endforeach
                        @else
                            <tr>
                                <td colspan="6" class="px-2 py-4 text-center border-b border-gray-300">Tidak ada data warna yang tersedia.</td>
                            </tr>
                        @endif
                </tbody>
            </table>
        </div>

            <!-- Bottom Buttons with equal spacing -->
            <div class="flex justify-between mt-2">
                <button type="button" onclick="sortTableDirectly(0)" class="py-0.5 px-2 bg-gray-100 border border-gray-400 hover:bg-gray-200 text-xs w-20">By Color#</button>
                <button type="button" onclick="sortTableDirectly(3)" class="py-0.5 px-2 bg-gray-100 border border-gray-400 hover:bg-gray-200 text-xs w-24">By CG# + Color#</button>
                <button type="button" onclick="sortTableDirectly(5)" class="py-0.5 px-2 bg-gray-100 border border-gray-400 hover:bg-gray-200 text-xs w-28">By CG Type + Color#</button>
                <button type="button" onclick="selectCurrentRow()" class="py-0.5 px-2 bg-gray-100 border border-gray-400 hover:bg-gray-200 text-xs w-16">Select</button>
                <button type="button" onclick="closeColorModal()" class="py-0.5 px-2 bg-gray-100 border border-gray-400 hover:bg-gray-200 text-xs w-16">Exit</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Fungsi untuk mengurutkan tabel
function sortTableDirectly(columnIndex) {
    console.log("Sorting by column: " + columnIndex);
    
    var table = document.getElementById('colorDataTable');
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
        if (columnIndex === 0 || columnIndex === 3) {
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
    
    // Highlight baris warna berdasarkan color group
    highlightBlueRows();
    
    console.log("Sorting complete");
}

// Fungsi untuk memilih baris saat ini dan menutup modal
function selectCurrentRow() {
    var selectedRow = document.querySelector('#colorDataTable tbody tr.selected');
    if (!selectedRow) {
        console.log("No row selected");
        return;
    }
    
    var colorId = selectedRow.cells[0].textContent.trim();
    var colorName = selectedRow.cells[1].textContent.trim();
    console.log("Selected color ID: " + colorId);
    
    // Pilih warna dan tutup modal
    selectColor(colorId, colorName);
    }

    // Fungsi untuk menutup modal
    function closeColorModal() {
    var modal = document.getElementById('colorTableWindow');
    if (modal) {
        modal.style.display = 'none';
        modal.classList.add('hidden');
    }
}

// Fungsi untuk memilih baris
function selectRow(row) {
    // Hapus kelas 'selected' dari semua baris
    var allRows = document.querySelectorAll('#colorDataTable tbody tr');
    allRows.forEach(function(r) {
        r.classList.remove('selected');
    });
    
    // Tambahkan kelas 'selected' ke baris yang dipilih
    row.classList.add('selected');
}

// Inisialisasi event handler saat dokumen dimuat
document.addEventListener('DOMContentLoaded', function() {
    // Dapatkan tombol dan modal
    var showBtn = document.getElementById('showColorTableBtn');
    var colorModal = document.getElementById('colorTableWindow');
    
    // Tambahkan event listener ke tombol untuk menampilkan modal
    if (showBtn) {
        showBtn.onclick = function() {
            if (colorModal) {
                colorModal.style.display = 'block';
                colorModal.classList.remove('hidden');
                
                // Urutkan berdasarkan kolom pertama secara default
                sortTableDirectly(0);
            }
        };
    }
    
    // Atur event untuk baris tabel
    var tableRows = document.querySelectorAll('#colorDataTable tbody tr');
    tableRows.forEach(function(row) {
        // Lewati baris "tidak ada data"
        if (row.querySelector('td[colspan]')) return;
        
        // Event klik untuk memilih baris
        row.onclick = function(e) {
            e.stopPropagation();
            selectRow(this);
        };
        
        // Event klik ganda untuk memilih warna
        row.ondblclick = function() {
            var colorId = this.cells[0].textContent.trim();
            
            // Isi input form
            var colorInput = document.querySelector('input[type="text"]');
            if (colorInput) {
                colorInput.value = colorId;
            }
            
            // Tutup modal
            closeColorModal();
        };
    });
    
    // Tutup modal saat mengklik area di luar tabel
    if (colorModal) {
        colorModal.onclick = function(e) {
            if (e.target === colorModal) {
                closeColorModal();
            }
        };
    }
});
</script>
@endpush

@push('styles')
<style>
    /* Modal window styles */
    #colorTableWindow {
        background-color: rgba(0, 0, 0, 0.2);
        z-index: 9999;
    }
    
    #colorTableWindow:not(.hidden) {
        display: block;
    }
    
    #colorTableWindow.hidden {
        display: none;
    }
    
    /* Table styles */
    #colorDataTable {
        border-collapse: collapse;
        width: 100%;
    }
    
    #colorDataTable tbody {
        display: block;
        max-height: 250px;
        overflow-y: auto;
        overflow-x: hidden;
    }
    
    #colorDataTable thead {
        display: table;
        width: calc(100% - 17px); /* Adjust for scrollbar width */
        table-layout: fixed;
    }
    
    #colorDataTable tbody tr {
        display: table;
        width: 100%;
        table-layout: fixed;
    }
    
    /* Selected row */
    #colorDataTable tbody tr.selected {
        background-color: #0078d7;
        color: white;
    }
    
    #colorDataTable tbody tr:hover {
        background-color: #0078d7;
        color: white;
    }
    
    /* Blue rows (as in the image) */
    #colorDataTable tbody tr.blue-row {
        background-color: #d0e7ff;
    }
    
    #colorDataTable tbody tr.blue-row:hover {
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
    #colorDataTable tbody::-webkit-scrollbar {
        width: 16px;
    }
    
    #colorDataTable tbody::-webkit-scrollbar-track {
        background: #f1f1f1;
    }
    
    #colorDataTable tbody::-webkit-scrollbar-thumb {
        background: #c1c1c1;
        border: 1px solid #a1a1a1;
    }
    
    #colorDataTable tbody::-webkit-scrollbar-thumb:hover {
        background: #a1a1a1;
    }
</style>
@endpush
@endsection