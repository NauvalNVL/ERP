@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 p-6">
    <div class="container mx-auto max-w-7xl space-y-6">
        {{-- Header Section --}}
        <div class="flex justify-between items-center">
            <h1 class="text-3xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-indigo-600">
                Define Color
            </h1>
            <div class="flex space-x-3">
                <button id="addColorBtn" class="btn-action bg-blue-500 hover:bg-blue-600">
                    <i class="fas fa-plus"></i>
                </button>
                <button id="saveColorBtn" class="btn-action bg-green-500 hover:bg-green-600">
                    <i class="fas fa-save"></i>
                </button>
                <button id="resetColorBtn" class="btn-action bg-red-500 hover:bg-red-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>

        {{-- Search & Filter Section --}}
        <div class="bg-white shadow-md rounded-lg p-6">
            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Color Search</label>
                    <div class="flex">
                        <input 
                            type="text" 
                            id="colorSearchInput"
                            placeholder="Search Color ID or Name" 
                            class="flex-grow px-4 py-2 border border-gray-300 rounded-l-md focus:ring-2 focus:ring-blue-500"
                        >
                        <button id="searchColorBtn" class="px-4 py-2 bg-blue-500 text-white rounded-r-md hover:bg-blue-600 transition-colors">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Filter</label>
                    <select id="colorFilterSelect" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500">
                        <option value="">All Colors</option>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
            </div>
        </div>

        {{-- Color Entry Form --}}
        <div class="grid md:grid-cols-3 gap-6">
            <div class="md:col-span-2 bg-white shadow-md rounded-lg p-6">
                <form id="colorForm" class="grid md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Color ID</label>
                        <input 
                            type="text" 
                            id="colorId"
                            name="color_id"
                            class="form-input"
                            required
                            pattern="[A-Za-z0-9]+"
                            title="Only alphanumeric characters allowed"
                        >
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Color Name</label>
                        <input 
                            type="text" 
                            id="colorName"
                            name="color_name"
                            class="form-input"
                            required
                            minlength="2"
                            maxlength="50"
                        >
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Color Origin</label>
                        <input 
                            type="text" 
                            id="colorOrigin"
                            name="origin"
                            class="form-input"
                            maxlength="100"
                        >
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Color Group</label>
                        <select 
                            id="colorGroup" 
                            name="color_group_id"
                            class="form-input"
                        >
                            <option value="">Select Color Group</option>
                            @foreach($colorGroups as $group)
                                <option value="{{ $group->id }}">{{ $group->cg_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </form>
            </div>

            {{-- Color Preview --}}
            <div class="bg-white shadow-md rounded-lg p-6 flex flex-col items-center justify-center">
                <div id="colorPreview" class="w-32 h-32 rounded-full border-4 border-gray-200 mb-4" style="background-color: #3B82F6;"></div>
                <h3 class="text-lg font-semibold text-gray-800">Color Preview</h3>
                <p id="colorHexValue" class="text-sm text-gray-500">#3B82F6</p>
            </div>
        </div>

        {{-- Color Table --}}
        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <table class="w-full" id="colorTable">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Color ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Color Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Color Group</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Origin</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($colors as $color)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">{{ $color->color_id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $color->color_name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ optional($color->colorGroup)->cg_name ?? 'Tidak Diketahui' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $color->origin }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex space-x-2">
                                <button class="edit-color-btn text-blue-500 hover:text-blue-700" data-id="{{ $color->id }}">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="delete-color-btn text-red-500 hover:text-red-700" data-id="{{ $color->id }}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Modal untuk Tambah/Edit Warna -->
        <div id="colorModal" class="fixed inset-0 bg-black bg-opacity-50 hidden">
            <div class="flex items-center justify-center min-h-screen">
                <div class="bg-white rounded-lg p-6 w-96">
                    <h2 class="text-xl font-semibold mb-4">Add/Edit Color</h2>
                    <form id="colorForm">
                        @csrf
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Color ID</label>
                            <input type="text" id="colorId" name="color_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Color Name</label>
                            <input type="text" id="colorName" name="color_name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Color Group</label>
                            <select id="colorGroup" name="color_group_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                <option value="">Select Color Group</option>
                                @foreach($colorGroups as $group)
                                    <option value="{{ $group->id }}">{{ $group->cg_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Origin</label>
                            <input type="text" id="colorOrigin" name="origin" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        </div>
                        <div class="flex justify-end space-x-2">
                            <button type="button" id="cancelColorBtn" class="btn-secondary">Cancel</button>
                            <button type="button" id="saveColorBtn" class="btn-primary">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Elemen utama
    const colorForm = document.getElementById('colorForm');
    const colorPreview = document.getElementById('colorPreview');
    const colorHexValue = document.getElementById('colorHexValue');
    const colorSearchInput = document.getElementById('colorSearchInput');
    const colorFilterSelect = document.getElementById('colorFilterSelect');
    const searchColorBtn = document.getElementById('searchColorBtn');
    const saveColorBtn = document.getElementById('saveColorBtn');
    const resetColorBtn = document.getElementById('resetColorBtn');
    const addColorBtn = document.getElementById('addColorBtn');
    const colorModal = document.getElementById('colorModal');
    const cancelColorBtn = document.getElementById('cancelColorBtn');

    // Validasi form sebelum submit
    function validateForm() {
        const colorId = document.getElementById('colorId');
        const colorName = document.getElementById('colorName');

        // Reset previous error states
        [colorId, colorName].forEach(input => {
            input.classList.remove('border-red-500');
        });

        let isValid = true;

        // Validasi Color ID
        if (!colorId.value.trim()) {
            colorId.classList.add('border-red-500');
            isValid = false;
        }

        // Validasi Color Name
        if (!colorName.value.trim()) {
            colorName.classList.add('border-red-500');
            isValid = false;
        }

        return isValid;
    }

    // Simpan warna
    function saveColor() {
        if (!validateForm()) {
            alert('Harap isi semua field yang wajib!');
            return;
        }

        const formData = new FormData(colorForm);
        
        fetch('{{ route("colors.store") }}', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                closeColorModal();
                location.reload();
            } else {
                alert(data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat menyimpan warna');
        });
    }

    // Event Listeners
    saveColorBtn.addEventListener('click', saveColor);
    
    // Reset form
    resetColorBtn.addEventListener('click', function() {
        colorForm.reset();
        colorPreview.style.backgroundColor = '#3B82F6';
        colorHexValue.textContent = '#3B82F6';
    });

    // Pencarian dan filter
    searchColorBtn.addEventListener('click', function() {
        const searchTerm = colorSearchInput.value.toLowerCase();
        const filterValue = colorFilterSelect.value;
        filterColors(searchTerm, filterValue);
    });

    // Preview warna dinamis
    const colorInputs = document.querySelectorAll('input[type="text"]');
    colorInputs.forEach(input => {
        input.addEventListener('input', function() {
            const randomColor = Math.floor(Math.random()*16777215).toString(16).padStart(6, '0');
            colorPreview.style.backgroundColor = `#${randomColor}`;
            colorHexValue.textContent = `#${randomColor}`;
        });
    });

    // Edit Color Buttons
    document.querySelectorAll('.edit-color-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const colorId = this.getAttribute('data-id');
            fetchColorDetails(colorId);
        });
    });

    // Delete Color Buttons
    document.querySelectorAll('.delete-color-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const colorId = this.getAttribute('data-id');
            if (confirm('Are you sure you want to delete this color?')) {
                deleteColor(colorId);
            }
        });
    });

    // Fungsi untuk membuka modal
    function openColorModal() {
        colorModal.classList.remove('hidden');
    }

    // Fungsi untuk menutup modal
    function closeColorModal() {
        colorModal.classList.add('hidden');
        colorForm.reset();
    }

    // Event listener untuk tombol tambah
    addColorBtn.addEventListener('click', openColorModal);

    // Event listener untuk tombol batal
    cancelColorBtn.addEventListener('click', closeColorModal);

    // Function to filter colors
    function filterColors(searchTerm, filterValue) {
        const rows = document.querySelectorAll('#colorTable tbody tr');
        
        rows.forEach(row => {
            const colorId = row.querySelector('td:first-child').textContent.toLowerCase();
            const colorName = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
            
            const matchesSearch = searchTerm === '' || 
                colorId.includes(searchTerm) || 
                colorName.includes(searchTerm);
            
            const matchesFilter = filterValue === '' || 
                row.getAttribute('data-status') === filterValue;
            
            row.style.display = matchesSearch && matchesFilter ? '' : 'none';
        });
    }

    // Function to fetch color details for editing
    function fetchColorDetails(colorId) {
        fetch(`/colors/${colorId}`)
            .then(response => response.json())
            .then(color => {
                // Populate form with color details
                document.getElementById('colorId').value = color.color_id;
                document.getElementById('colorName').value = color.color_name;
                document.getElementById('colorOrigin').value = color.origin;
                document.getElementById('colorGroup').value = color.color_group_id;
                
                // Update color preview
                colorPreview.style.backgroundColor = `#${color.color_id.substr(0, 6)}`;
                colorHexValue.textContent = `#${color.color_id.substr(0, 6)}`;
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Failed to fetch color details');
            });
    }

    // Function to delete color
    function deleteColor(colorId) {
        fetch(`/colors/${colorId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Remove row from table
                const row = document.querySelector(`tr[data-id="${colorId}"]`);
                if (row) row.remove();
                alert('Color deleted successfully');
            } else {
                alert('Error deleting color');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while deleting the color');
        });
    }
});
</script>
@endpush

@push('styles')
<style>
    .btn-action {
        @apply w-10 h-10 flex items-center justify-center rounded-full text-white transition-all duration-300 transform hover:scale-105 shadow-md;
    }
    .form-input {
        @apply w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500;
    }
    .table-header {
        @apply px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider;
    }
    .table-cell {
        @apply px-6 py-4 whitespace-nowrap;
    }
    .color-row {
        @apply hover:bg-gray-50 transition;
    }
</style>
@endpush
@endsection