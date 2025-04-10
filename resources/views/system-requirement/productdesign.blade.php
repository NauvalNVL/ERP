@extends('layouts.app')

@section('title', 'Define Product Design')

@section('content')
<div class="container mx-auto p-6">
    <div class="bg-white shadow-lg rounded-lg">
        <div class="p-4">
            <!-- Navigation buttons -->
            <div class="flex items-center space-x-1 mb-4">
                <button type="button" class="p-1 hover:bg-gray-200 border border-gray-300">
                    <i class="fas fa-power-off text-red-600"></i>
                </button>
                <button type="button" class="p-1 hover:bg-gray-200 border border-gray-300">
                    <i class="fas fa-arrow-right text-blue-600"></i>
                </button>
                <button type="button" class="p-1 hover:bg-gray-200 border border-gray-300">
                    <i class="fas fa-arrow-left text-blue-600"></i>
                </button>
                <button type="button" class="p-1 hover:bg-gray-200 border border-gray-300">
                    <i class="fas fa-search text-blue-600"></i>
                </button>
                <button type="button" class="p-1 hover:bg-gray-200 border border-gray-300">
                    <i class="fas fa-save text-green-600"></i>
                </button>
            </div>

            <!-- Product Design Input Section -->
            <div class="mb-4">
                <div class="flex items-center space-x-2 mb-2">
                    <label class="w-32">P/Design Code:</label>
                    <div class="flex items-center border border-gray-300">
                        <input type="text" class="px-2 py-1 w-48 focus:outline-none" readonly>
                        <button type="button" id="showProductDesignTableBtn" class="px-2 py-1 bg-gray-100 border-l border-gray-300 hover:bg-gray-200">
                            <i class="fas fa-table text-gray-600"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Product Design Table Window -->
<div id="productDesignTableWindow" class="hidden fixed inset-0 z-50">
    <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white border-2 border-gray-500 shadow-lg" style="width: 800px;">
        <!-- Modal Header -->
        <div class="bg-blue-800 px-2 py-1 text-white flex justify-between items-center">
            <h3 class="text-sm font-normal">Product Design Table</h3>
            <button type="button" onclick="closeModal()" class="text-white hover:text-gray-200 focus:outline-none">
                <span class="text-lg">×</span>
            </button>
        </div>

        <!-- Table Content -->
        <div class="p-2">
            <div class="border border-gray-300">
                <table class="w-full" id="productDesignTable">
                    <thead>
                        <tr>
                            <th class="px-2 py-1 bg-gray-100 border-b border-r border-gray-300 text-left text-xs font-semibold">P/Design Code</th>
                            <th class="px-2 py-1 bg-gray-100 border-b border-r border-gray-300 text-left text-xs font-semibold">P/Design Name</th>
                            <th class="px-2 py-1 bg-gray-100 border-b border-r border-gray-300 text-left text-xs font-semibold">Product Code</th>
                            <th class="px-2 py-1 bg-gray-100 border-b border-gray-300 text-left text-xs font-semibold">IDC</th>
                        </tr>
                    </thead>
                    <tbody class="text-xs">
                        @foreach($productDesigns as $design)
                        <tr class="cursor-pointer hover:bg-[#0078d7] hover:text-white"
                            onclick="selectRow(this)"
                            ondblclick="selectDesign('{{ $design->pd_code }}', '{{ $design->pd_name }}')">
                            <td class="px-2 py-1 border-b border-r border-gray-300">{{ $design->pd_code }}</td>
                            <td class="px-2 py-1 border-b border-r border-gray-300">{{ $design->pd_name }}</td>
                            <td class="px-2 py-1 border-b border-r border-gray-300">{{ $design->product_code }}</td>
                            <td class="px-2 py-1 border-b border-gray-300">{{ $design->idc }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Bottom Buttons -->
            <div class="flex justify-between mt-2">
                <div class="space-x-1">
                    <button onclick="sortTable(0)" class="px-3 py-1 bg-gray-100 border border-gray-400 hover:bg-gray-200 text-xs">Zoom</button>
                    <button onclick="selectCurrentRow()" class="px-3 py-1 bg-gray-100 border border-gray-400 hover:bg-gray-200 text-xs">Select</button>
                </div>
                <button onclick="closeModal()" class="px-3 py-1 bg-gray-100 border border-gray-400 hover:bg-gray-200 text-xs">Exit</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const tableWindow = document.getElementById('productDesignTableWindow');
    const showTableBtn = document.getElementById('showProductDesignTableBtn');
    let selectedRow = null;

    showTableBtn.addEventListener('click', function() {
        tableWindow.classList.remove('hidden');
        // Select first row by default
        const firstRow = document.querySelector('#productDesignTable tbody tr');
        if (firstRow) {
            selectRow(firstRow);
        }
    });

    // Close modal when clicking outside
    tableWindow.addEventListener('click', function(e) {
        if (e.target === tableWindow) {
            closeModal();
        }
    });

    // Keyboard navigation
    document.addEventListener('keydown', function(e) {
        if (tableWindow.classList.contains('hidden')) return;

        const tbody = document.querySelector('#productDesignTable tbody');
        const rows = Array.from(tbody.querySelectorAll('tr'));
        const currentRow = tbody.querySelector('tr.selected');
        let currentIndex = currentRow ? rows.indexOf(currentRow) : -1;

        switch(e.key) {
            case 'ArrowUp':
                e.preventDefault();
                if (currentIndex > 0) {
                    selectRow(rows[currentIndex - 1]);
                    rows[currentIndex - 1].scrollIntoView({ block: 'nearest' });
                }
                break;
            case 'ArrowDown':
                e.preventDefault();
                if (currentIndex < rows.length - 1) {
                    selectRow(rows[currentIndex + 1]);
                    rows[currentIndex + 1].scrollIntoView({ block: 'nearest' });
                }
                break;
            case 'Enter':
                if (currentRow) {
                    const code = currentRow.cells[0].textContent;
                    const name = currentRow.cells[1].textContent;
                    selectDesign(code, name);
                }
                break;
            case 'Escape':
                closeModal();
                break;
        }
    });
});

function selectRow(row) {
    const allRows = document.querySelectorAll('#productDesignTable tbody tr');
    allRows.forEach(r => r.classList.remove('selected'));
    row.classList.add('selected');
}

function selectDesign(code, name) {
    document.querySelector('input[type="text"]').value = code;
    closeModal();
}

function closeModal() {
    const modal = document.getElementById('productDesignTableWindow');
    modal.classList.add('hidden');
}

function selectCurrentRow() {
    const selectedRow = document.querySelector('#productDesignTable tbody tr.selected');
    if (selectedRow) {
        const code = selectedRow.cells[0].textContent;
        const name = selectedRow.cells[1].textContent;
        selectDesign(code, name);
    }
}

function sortTable(columnIndex) {
    const table = document.getElementById('productDesignTable');
    const tbody = table.querySelector('tbody');
    const rows = Array.from(tbody.querySelectorAll('tr'));

    rows.sort((a, b) => {
        const aValue = a.cells[columnIndex].textContent;
        const bValue = b.cells[columnIndex].textContent;
        return aValue.localeCompare(bValue);
    });

    tbody.innerHTML = '';
    rows.forEach(row => tbody.appendChild(row));

    // Select first row after sorting
    if (rows.length > 0) {
        selectRow(rows[0]);
    }
}
</script>
@endpush

@push('styles')
<style>
#productDesignTableWindow {
    background-color: rgba(0, 0, 0, 0.2);
}

#productDesignTable {
    border-collapse: collapse;
}

#productDesignTable tbody {
    display: block;
    max-height: 400px;
    overflow-y: auto;
}

#productDesignTable thead, #productDesignTable tbody tr {
    display: table;
    width: 100%;
    table-layout: fixed;
}

#productDesignTable tbody tr.selected {
    background-color: #0078d7;
    color: white;
}

/* Classic Windows scrollbar style */
#productDesignTable tbody::-webkit-scrollbar {
    width: 16px;
}

#productDesignTable tbody::-webkit-scrollbar-track {
    background: #f1f1f1;
}

#productDesignTable tbody::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border: 1px solid #a1a1a1;
}

#productDesignTable tbody::-webkit-scrollbar-thumb:hover {
    background: #a1a1a1;
}
</style>
@endpush
@endsection 