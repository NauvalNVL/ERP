@extends('layouts.app')

@section('title', 'Define Color')

<!-- Add direct script for opening modal -->
<script>
// Demo data for testing (will be removed when real data is working)
const demoColors = [
    { color_id: '00001', color_name: 'Black', origin: '01', color_group_id: '01', cg_type: 'X-Flexo' },
    { color_id: '00002', color_name: 'White', origin: '02', color_group_id: '02', cg_type: '' },
    { color_id: '00003', color_name: 'Red', origin: '03', color_group_id: '03', cg_type: '' },
    { color_id: '00004', color_name: 'Dark Red', origin: '03', color_group_id: '03', cg_type: '' },
    { color_id: '00005', color_name: 'Light Red', origin: '03', color_group_id: '03', cg_type: '' },
    { color_id: '00006', color_name: 'Blue', origin: '04', color_group_id: '04', cg_type: '' },
    { color_id: '00007', color_name: 'Dark Blue', origin: '04', color_group_id: '04', cg_type: '' },
    { color_id: '00008', color_name: 'Light Blue', origin: '04', color_group_id: '04', cg_type: '' },
    { color_id: '00009', color_name: 'Green', origin: '05', color_group_id: '05', cg_type: '' },
    { color_id: '00010', color_name: 'Dark Green', origin: '05', color_group_id: '05', cg_type: '' }
];

function openColorModal() {
    console.log('Opening color modal directly');
    var modal = document.getElementById('colorTableWindow');
    if (modal) {
        modal.style.display = 'block';
        modal.classList.remove('hidden');
        
        // Check if we have data in the table
        var tableBody = document.querySelector('#colorDataTable tbody');
        var rows = tableBody.querySelectorAll('tr');
        
        // If we have no real data, we can populate with demo data for testing
        if (rows.length === 1 && rows[0].querySelector('td[colspan="6"]')) {
            console.log('No data found - using demo data for testing');
            populateWithDemoData();
        } else {
            console.log('Found ' + rows.length + ' rows of real data');
        }
        
        // Default sort order - by Color#
        sortByColor();
        
        // Make sure the "By Color#" button is highlighted as the default sort
        highlightSortButton('byColor');
        
        // Highlight rows based on color group
        highlightBlueRows();
    } else {
        console.error('Modal element not found');
    }
}

function populateWithDemoData() {
    var tableBody = document.querySelector('#colorDataTable tbody');
    if (!tableBody) return;
    
    // Clear the "no data" message
    tableBody.innerHTML = '';
    
    // Add the demo rows
    demoColors.forEach(function(color) {
        var row = document.createElement('tr');
        row.className = 'cursor-pointer';
        row.setAttribute('data-color-id', color.color_id);
        row.setAttribute('data-color-name', color.color_name);
        row.setAttribute('data-origin', color.origin);
        row.setAttribute('data-cg-id', color.color_group_id);
        row.setAttribute('data-cg-type', color.cg_type);
        
        row.onclick = function(e) {
            selectRow(this);
            e.stopPropagation();
        };
        
        row.ondblclick = function() {
            selectColor(color.color_id);
        };
        
        row.innerHTML = `
            <td class="px-2 py-0.5 border-b border-r border-gray-300">${color.color_id}</td>
            <td class="px-2 py-0.5 border-b border-r border-gray-300">${color.color_name}</td>
            <td class="px-2 py-0.5 border-b border-r border-gray-300">${color.origin}</td>
            <td class="px-2 py-0.5 border-b border-r border-gray-300">${color.color_group_id}</td>
            <td class="px-2 py-0.5 border-b border-r border-gray-300">${getCGName(color.color_group_id)}</td>
            <td class="px-2 py-0.5 border-b border-gray-300">${color.cg_type}</td>
        `;
        
        tableBody.appendChild(row);
    });
}

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

function closeColorTable() {
    console.log('Closing color table modal');
    var modal = document.getElementById('colorTableWindow');
    if (modal) {
        modal.style.display = 'none';
        modal.classList.add('hidden');
    }
}

function selectColor(colorId) {
    console.log('Selecting color: ' + colorId);
    var input = document.querySelector('input[type="text"]');
    if (input) {
        input.value = colorId;
    }
    closeColorTable();
}

function selectRow(row) {
    // Remove selected class from all rows
    var rows = document.querySelectorAll('#colorDataTable tbody tr');
    rows.forEach(function(r) {
        r.classList.remove('selected');
    });
    
    // Add selected class to this row
    row.classList.add('selected');
}

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
            <button type="button" onclick="closeColorTable()" class="text-white hover:text-gray-200 focus:outline-none">
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
                                ondblclick="selectColor('{{ $color->color_id }}')">
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
                <button type="button" id="btnSortByColor" class="py-0.5 px-2 bg-gray-100 border border-gray-400 hover:bg-gray-200 text-xs w-20">By Color#</button>
                <button type="button" id="btnSortByCG" class="py-0.5 px-2 bg-gray-100 border border-gray-400 hover:bg-gray-200 text-xs w-24">By CG# + Color#</button>
                <button type="button" id="btnSortByCGType" class="py-0.5 px-2 bg-gray-100 border border-gray-400 hover:bg-gray-200 text-xs w-28">By CG Type + Color#</button>
                <button type="button" id="btnSelect" class="py-0.5 px-2 bg-gray-100 border border-gray-400 hover:bg-gray-200 text-xs w-16">Select</button>
                <button type="button" id="btnExit" class="py-0.5 px-2 bg-gray-100 border border-gray-400 hover:bg-gray-200 text-xs w-16">Exit</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded');
    
    // Modal handling
    var colorTableWindow = document.getElementById('colorTableWindow');
    var showColorTableBtn = document.getElementById('showColorTableBtn');
    var btnExit = document.getElementById('btnExit');
    
    // Sort buttons
    var btnSortByColor = document.getElementById('btnSortByColor');
    var btnSortByCG = document.getElementById('btnSortByCG');
    var btnSortByCGType = document.getElementById('btnSortByCGType');
    var btnSelect = document.getElementById('btnSelect');
    
    // Open modal
    if (showColorTableBtn) {
        showColorTableBtn.onclick = function() {
            console.log('Show button clicked');
            if (colorTableWindow) {
                colorTableWindow.style.display = 'block';
                colorTableWindow.classList.remove('hidden');
            }
        };
    }
    
    // Close modal
    if (btnExit) {
        btnExit.onclick = function() {
            console.log('Exit button clicked');
            closeModal();
        };
    }
    
    // Handle clicking outside to close
    if (colorTableWindow) {
        colorTableWindow.onclick = function(e) {
            if (e.target === colorTableWindow) {
                closeModal();
            }
        };
    }
    
    // Sorting functions
    if (btnSortByColor) {
        btnSortByColor.onclick = function() {
            console.log('Sort by Color# clicked');
            sortTableByColumn(0); // First column (Color#)
        };
    }
    
    if (btnSortByCG) {
        btnSortByCG.onclick = function() {
            console.log('Sort by CG# clicked');
            sortTableByColumn(3); // Fourth column (CG#)
        };
    }
    
    if (btnSortByCGType) {
        btnSortByCGType.onclick = function() {
            console.log('Sort by CG Type clicked');
            sortTableByColumn(5); // Sixth column (CG Type)
        };
    }
    
    // Select button
    if (btnSelect) {
        btnSelect.onclick = function() {
            console.log('Select button clicked');
            var selected = document.querySelector('#colorDataTable tbody tr.selected');
            if (selected) {
                var colorId = selected.cells[0].textContent.trim();
                selectColor(colorId);
            }
        };
    }
    
    // Set up row click events
    setupRowEvents();
});

function setupRowEvents() {
    var rows = document.querySelectorAll('#colorDataTable tbody tr');
    
    rows.forEach(function(row) {
        // Skip the no-data row
        if (row.cells.length === 1) return;
        
        // Click to select
        row.onclick = function(e) {
            e.stopPropagation();
            
            // Remove selection from all rows
            var allRows = document.querySelectorAll('#colorDataTable tbody tr');
            allRows.forEach(function(r) {
                r.classList.remove('selected');
            });
            
            // Add selected class to this row
            this.classList.add('selected');
        };
        
        // Double-click to select and close
        row.ondblclick = function() {
            var colorId = this.cells[0].textContent.trim();
            selectColor(colorId);
        };
    });
}

function closeModal() {
    var modal = document.getElementById('colorTableWindow');
    if (modal) {
        modal.style.display = 'none';
        modal.classList.add('hidden');
    }
}

function selectColor(colorId) {
    console.log('Selecting color: ' + colorId);
    var input = document.querySelector('input[type="text"]');
    if (input) {
        input.value = colorId;
    }
    closeModal();
}

function sortTableByColumn(columnIndex) {
    console.log('Sorting by column: ' + columnIndex);
    var table = document.getElementById('colorDataTable');
    var tbody = table.querySelector('tbody');
    var rows = Array.from(tbody.querySelectorAll('tr'));
    
    // Skip if there's only a "no data" message
    if (rows.length === 1 && rows[0].cells.length === 1) {
        return;
    }
    
    // Sort the rows based on the specified column
    rows.sort(function(rowA, rowB) {
        // Handle potential missing cells
        if (!rowA.cells[columnIndex] || !rowB.cells[columnIndex]) {
            return 0;
        }
        
        var textA = rowA.cells[columnIndex].textContent.trim();
        var textB = rowB.cells[columnIndex].textContent.trim();
        
        // If sorting by Color# (column 0) or CG# (column 3), do numeric sort
        if (columnIndex === 0 || columnIndex === 3) {
            // Remove leading zeros for numeric comparison
            textA = textA.replace(/^0+/, '');
            textB = textB.replace(/^0+/, '');
            
            // If both are numbers, compare numerically
            if (!isNaN(textA) && !isNaN(textB)) {
                return parseInt(textA) - parseInt(textB);
            }
        }
        
        // Default to string comparison
        return textA.localeCompare(textB);
    });
    
    // Clear the table and append the sorted rows
    rows.forEach(function(row) {
        tbody.appendChild(row);
    });
    
    // Select the first row after sorting
    if (rows.length > 0) {
        rows.forEach(function(row) {
            row.classList.remove('selected');
        });
        rows[0].classList.add('selected');
    }
}
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