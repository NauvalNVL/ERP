@extends('layouts.app')

@section('title', 'Define Color')

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
                <button type="button" id="showColorTableBtn" class="bg-gray-200 hover:bg-gray-300 px-2 py-2 border border-gray-400">
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
    </div>
</div>

<!-- Color Table Window -->
<div id="colorTableWindow" class="hidden fixed inset-0 z-50">
    <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white border border-gray-400 shadow-lg" style="width: 800px;">
        <!-- Table Content -->
        <div class="p-2">
            <div class="border border-gray-300">
                <table class="w-full" id="colorDataTable">
                    <thead>
                        <tr>
                            <th class="px-2 py-1 bg-gray-100 border-b border-r border-gray-300 text-left text-sm font-normal">Color#</th>
                            <th class="px-2 py-1 bg-gray-100 border-b border-r border-gray-300 text-left text-sm font-normal">Color Name</th>
                            <th class="px-2 py-1 bg-gray-100 border-b border-r border-gray-300 text-left text-sm font-normal">Origin</th>
                            <th class="px-2 py-1 bg-gray-100 border-b border-r border-gray-300 text-left text-sm font-normal">CG#</th>
                            <th class="px-2 py-1 bg-gray-100 border-b border-r border-gray-300 text-left text-sm font-normal">CG Name</th>
                            <th class="px-2 py-1 bg-gray-100 border-b border-gray-300 text-left text-sm font-normal">CG Type</th>
                        </tr>
                    </thead>
                    <tbody style="max-height: 400px; overflow-y: auto;" class="text-sm">
                        @foreach($colors as $color)
                        <tr class="hover:bg-[#0078d7] hover:text-white cursor-pointer color-row" 
                            data-color-id="{{ $color->color_id }}"
                            data-color-name="{{ $color->color_name }}"
                            data-origin="{{ $color->origin }}"
                            data-cg-id="{{ optional($color->colorGroup)->id }}"
                            data-cg-name="{{ optional($color->colorGroup)->cg_name }}"
                            data-cg-type="{{ $color->cg_type }}"
                            onclick="selectColor('{{ $color->color_id }}')">
                            <td class="px-2 py-1 border-b border-r border-gray-300">{{ $color->color_id }}</td>
                            <td class="px-2 py-1 border-b border-r border-gray-300">{{ $color->color_name }}</td>
                            <td class="px-2 py-1 border-b border-r border-gray-300">{{ $color->origin }}</td>
                            <td class="px-2 py-1 border-b border-r border-gray-300">{{ optional($color->colorGroup)->id }}</td>
                            <td class="px-2 py-1 border-b border-r border-gray-300">{{ optional($color->colorGroup)->cg_name }}</td>
                            <td class="px-2 py-1 border-b border-gray-300">{{ $color->cg_type }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Bottom Buttons -->
            <div class="flex justify-between mt-2">
                <div class="space-x-1">
                    <button onclick="sortByColor()" class="px-3 py-1 bg-gray-100 border border-gray-400 hover:bg-gray-200 text-sm">By Color#</button>
                    <button onclick="sortByCGAndColor()" class="px-3 py-1 bg-gray-100 border border-gray-400 hover:bg-gray-200 text-sm">By CG# + Color#</button>
                    <button onclick="sortByCGTypeAndColor()" class="px-3 py-1 bg-gray-100 border border-gray-400 hover:bg-gray-200 text-sm">By CG Type + Color#</button>
                    <button onclick="selectCurrentRow()" class="px-3 py-1 bg-gray-100 border border-gray-400 hover:bg-gray-200 text-sm">Select</button>
                </div>
                <button onclick="closeColorTable()" class="px-3 py-1 bg-gray-100 border border-gray-400 hover:bg-gray-200 text-sm">Exit</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const colorTableWindow = document.getElementById('colorTableWindow');
    const showColorTableBtn = document.getElementById('showColorTableBtn');
    let selectedRow = null;

    showColorTableBtn.addEventListener('click', function() {
        colorTableWindow.classList.remove('hidden');
        // Select first row by default
        const firstRow = document.querySelector('#colorDataTable tbody tr');
        if (firstRow) {
            firstRow.classList.add('selected');
            selectedRow = firstRow;
        }
    });

    // Close color table when clicking outside
    colorTableWindow.addEventListener('click', function(e) {
        if (e.target === colorTableWindow) {
            closeColorTable();
        }
    });
});

function closeColorTable() {
    document.getElementById('colorTableWindow').classList.add('hidden');
    // Clear selection
    const selectedRow = document.querySelector('#colorDataTable tbody tr.selected');
    if (selectedRow) {
        selectedRow.classList.remove('selected');
    }
}

function selectColor(colorId) {
    document.querySelector('input[type="text"]').value = colorId;
    closeColorTable();
}

function sortTableRows(sortFunction) {
    const tbody = document.querySelector('#colorDataTable tbody');
    const rows = Array.from(tbody.querySelectorAll('tr'));
    
    rows.sort(sortFunction);
    
    tbody.innerHTML = '';
    rows.forEach(row => tbody.appendChild(row));
    
    // Reselect first row after sorting
    const firstRow = tbody.querySelector('tr');
    if (firstRow) {
        firstRow.classList.add('selected');
    }
}

function sortByColor() {
    sortTableRows((a, b) => {
        const aColor = a.getAttribute('data-color-id');
        const bColor = b.getAttribute('data-color-id');
        return aColor.localeCompare(bColor);
    });
}

function sortByCGAndColor() {
    sortTableRows((a, b) => {
        const aCG = a.getAttribute('data-cg-id');
        const bCG = b.getAttribute('data-cg-id');
        const aColor = a.getAttribute('data-color-id');
        const bColor = b.getAttribute('data-color-id');
        
        if (aCG === bCG) {
            return aColor.localeCompare(bColor);
        }
        return aCG.localeCompare(bCG);
    });
}

function sortByCGTypeAndColor() {
    sortTableRows((a, b) => {
        const aCGType = a.getAttribute('data-cg-type');
        const bCGType = b.getAttribute('data-cg-type');
        const aColor = a.getAttribute('data-color-id');
        const bColor = b.getAttribute('data-color-id');
        
        if (aCGType === bCGType) {
            return aColor.localeCompare(bColor);
        }
        return aCGType.localeCompare(bCGType);
    });
}

function selectCurrentRow() {
    const selectedRow = document.querySelector('#colorDataTable tbody tr.selected');
    if (selectedRow) {
        selectColor(selectedRow.getAttribute('data-color-id'));
    }
}

// Keyboard navigation
document.addEventListener('keydown', function(e) {
    if (document.getElementById('colorTableWindow').classList.contains('hidden')) return;
    
    const tbody = document.querySelector('#colorDataTable tbody');
    const rows = Array.from(tbody.querySelectorAll('tr'));
    const currentRow = tbody.querySelector('tr.selected');
    let currentIndex = currentRow ? rows.indexOf(currentRow) : -1;
    
    switch(e.key) {
        case 'ArrowUp':
            e.preventDefault();
            if (currentIndex > 0) {
                rows[currentIndex].classList.remove('selected');
                currentIndex--;
                rows[currentIndex].classList.add('selected');
                rows[currentIndex].scrollIntoView({ block: 'nearest' });
            }
            break;
        case 'ArrowDown':
            e.preventDefault();
            if (currentIndex < rows.length - 1) {
                if (currentRow) rows[currentIndex].classList.remove('selected');
                currentIndex = currentIndex === -1 ? 0 : currentIndex + 1;
                rows[currentIndex].classList.add('selected');
                rows[currentIndex].scrollIntoView({ block: 'nearest' });
            }
            break;
        case 'Enter':
            if (currentRow) {
                selectColor(currentRow.getAttribute('data-color-id'));
            }
            break;
        case 'Escape':
            closeColorTable();
            break;
    }
});

// Row selection on mouse hover
document.querySelectorAll('#colorDataTable tbody tr').forEach(row => {
    row.addEventListener('mouseenter', function() {
        const currentSelected = document.querySelector('#colorDataTable tbody tr.selected');
        if (currentSelected) {
            currentSelected.classList.remove('selected');
        }
        this.classList.add('selected');
    });
});
</script>
@endpush

@push('styles')
<style>
    #colorTableWindow {
        background: rgba(0, 0, 0, 0.1);
    }
    
    #colorDataTable {
        border-collapse: collapse;
    }
    
    #colorDataTable tbody {
        display: block;
        max-height: 400px;
        overflow-y: auto;
    }
    
    #colorDataTable thead, #colorDataTable tbody tr {
        display: table;
        width: 100%;
        table-layout: fixed;
    }
    
    #colorDataTable tbody tr.selected {
        background-color: #0078d7;
        color: white;
    }
    
    /* Classic Windows scrollbar style */
    #colorDataTable tbody::-webkit-scrollbar {
        width: 16px;
    }
    
    #colorDataTable tbody::-webkit-scrollbar-track {
        background: #f1f1f1;
        border: 1px solid #c1c1c1;
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