@extends('layouts.app')

@section('title', 'Define Customer Group')

@section('content')
<!-- Header Section -->
<div class="bg-gradient-to-r from-cyan-700 to-blue-600 p-6 rounded-t-lg shadow-lg mb-0">
    <h2 class="text-2xl font-bold text-white mb-2 flex items-center">
        <i class="fas fa-users mr-3"></i> Define Customer Group
    </h2>
    <p class="text-cyan-100">Definisikan kelompok pelanggan untuk pengelompokan customer di sistem</p>
</div>

<div class="bg-white rounded-b-lg shadow-lg p-6 mb-6">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Left Column - Main Content -->
        <div class="lg:col-span-2">
            <div class="bg-white p-6 rounded-lg shadow-md border-t-4 border-blue-500">
                <div class="flex items-center mb-6 pb-2 border-b border-gray-200">
                    <div class="p-2 bg-blue-500 rounded-lg mr-3">
                        <i class="fas fa-edit text-white"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800">Customer Group Management</h3>
                </div>

                <!-- Header with navigation buttons -->
                <div class="flex items-center space-x-2 mb-6">
                    <button type="button" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded flex items-center space-x-2 transform active:translate-y-px" onclick="window.location.href='{{ route('dashboard') }}'">
                        <i class="fas fa-power-off"></i>
                    </button>
                    <button type="button" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded flex items-center space-x-2 transform active:translate-y-px">
                        <i class="fas fa-arrow-right"></i>
                    </button>
                    <button type="button" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded flex items-center space-x-2 transform active:translate-y-px">
                        <i class="fas fa-arrow-left"></i>
                    </button>
                    <button type="button" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded flex items-center space-x-2 transform active:translate-y-px">
                        <i class="fas fa-search"></i>
                    </button>
                    <button type="button" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded flex items-center space-x-2 transform active:translate-y-px">
                        <i class="fas fa-plus"></i>
                    </button>
                    <button type="button" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded flex items-center space-x-2 transform active:translate-y-px">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>

                <!-- Form Section -->
                <form action="{{ route('customer-group.store') }}" method="POST" class="space-y-6">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="group_code" class="block text-sm font-medium text-gray-700 mb-1">Grouping Code</label>
                            <div class="relative flex">
                                <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500">
                                    <i class="fas fa-users"></i>
                                </span>
                                <input type="text" name="group_code" id="group_code" required maxlength="20"
                                    class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none border border-gray-300 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                <button type="button" class="inline-flex items-center px-3 py-2 border border-l-0 border-gray-300 bg-blue-500 hover:bg-blue-600 text-white rounded-r-md transition-colors transform active:translate-y-px">
                                    <i class="fas fa-table"></i>
                                </button>
                            </div>
                            @error('group_code')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                            <input type="text" name="description" id="description" required maxlength="100"
                                class="block w-full px-3 py-2 rounded-md border border-gray-300 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="flex justify-end">
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                            Save
                        </button>
                    </div>
                </form>

                <!-- Table Section (optional, for listing) -->
                {{-- Customer Groups List table removed as requested --}}
            </div>
        </div>

        <!-- Right Column - Info/Help -->
        <div class="lg:col-span-1">
            <div class="bg-white p-6 rounded-lg shadow-md border-t-4 border-teal-500 mb-6">
                <div class="flex items-center mb-4 pb-2 border-b border-gray-200">
                    <div class="p-2 bg-teal-500 rounded-lg mr-3">
                        <i class="fas fa-info-circle text-white"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800">Info Customer Group</h3>
                </div>
                <div class="p-4 bg-teal-50 rounded-lg">
                    <h4 class="text-sm font-semibold text-teal-800 uppercase tracking-wider mb-2">Petunjuk</h4>
                    <ul class="list-disc pl-5 text-sm text-gray-600 space-y-1">
                        <li>Kode customer group harus unik dan tidak berubah</li>
                        <li>Gunakan tombol <span class="font-medium">search</span> untuk memilih customer group</li>
                        <li>Perubahan apa pun harus disimpan</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div id="editModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium leading-6 text-gray-900">Edit Customer Group</h3>
            <form id="editForm" method="POST" class="mt-4">
                @csrf
                @method('PUT')
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Grouping Code</label>
                    <input type="text" id="edit_group_code" disabled class="mt-1 block w-full rounded-md border-gray-300 bg-gray-100">
                </div>
                <div class="mb-4">
                    <label for="edit_description" class="block text-sm font-medium text-gray-700">Description</label>
                    <input type="text" name="description" id="edit_description" required maxlength="100" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeEditModal()" class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600">Cancel</button>
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Customer Group Table -->
<div id="customerGroupTableModal" class="fixed inset-0 z-50 bg-black bg-opacity-50 flex items-center justify-center hidden">
    <div class="bg-white rounded-lg shadow-xl w-11/12 md:w-2/3 lg:w-3/4 max-w-4xl mx-auto">
        <!-- Modal Header -->
        <div class="flex items-center justify-between p-4 border-b border-gray-200 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-t-lg">
            <h3 class="text-xl font-semibold flex items-center"><i class="fas fa-list mr-3"></i>Customer Group Table</h3>
            <button type="button" onclick="closeCustomerGroupTableModal()" class="text-white hover:text-gray-200 focus:outline-none">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <!-- Modal Body -->
        <div class="p-2 overflow-y-auto" style="max-height: 60vh;">
            <table class="min-w-full text-xs border border-gray-300" id="customerGroupModalTable">
                <thead class="bg-gray-200 sticky top-0">
                    <tr>
                        <th class="px-2 py-1 border border-gray-300 text-left">Group Code</th>
                        <th class="px-2 py-1 border border-gray-300 text-left">Description</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($customerGroups as $group)
                    <tr class="hover:bg-blue-100 cursor-pointer modal-row" onclick="selectCustomerGroupRow(this)"
                        data-code="{{ $group->group_code }}"
                        data-description="{{ $group->description }}">
                        <td class="px-2 py-1 border border-gray-300 font-medium text-gray-900">{{ $group->group_code }}</td>
                        <td class="px-2 py-1 border border-gray-300">{{ $group->description }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <!-- Modal Footer -->
        <div class="flex items-center justify-end gap-2 p-2 border-t border-gray-200 bg-gray-100 rounded-b-lg">
            <button type="button" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-1 px-3 rounded text-xs">More Options</button>
            <button type="button" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-1 px-3 rounded text-xs">Zoom</button>
            <button id="selectCustomerGroupBtn" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-3 rounded text-xs" disabled>Select</button>
            <button type="button" onclick="closeCustomerGroupTableModal()" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-1 px-3 rounded text-xs">Exit</button>
        </div>
    </div>
</div>

<script>
function openEditModal(groupCode, description) {
    document.getElementById('editModal').classList.remove('hidden');
    document.getElementById('edit_group_code').value = groupCode;
    document.getElementById('edit_description').value = description;
    document.getElementById('editForm').action = `/customer-group/${groupCode}`;
}
function closeEditModal() {
    document.getElementById('editModal').classList.add('hidden');
}

// Modal logic
let selectedCustomerGroupRow = null;
function openCustomerGroupTableModal() {
    console.log('Opening modal...');
    const modal = document.getElementById('customerGroupTableModal');
    if (!modal) {
        console.error('Customer group modal not found');
        return;
    }
    modal.classList.remove('hidden');
    if(selectedCustomerGroupRow) selectedCustomerGroupRow.classList.remove('bg-blue-200');
    selectedCustomerGroupRow = null;
    const selectBtn = document.getElementById('selectCustomerGroupBtn');
    if (selectBtn) selectBtn.disabled = true;
}
function closeCustomerGroupTableModal() {
    console.log('Closing modal...');
    const modal = document.getElementById('customerGroupTableModal');
    if (modal) modal.classList.add('hidden');
}
function selectCustomerGroupRow(row) {
    console.log('Selecting row:', row);
    if (!row) return;
    document.querySelectorAll('#customerGroupModalTable .modal-row').forEach(r => r.classList.remove('bg-blue-200'));
    selectedCustomerGroupRow = row;
    row.classList.add('bg-blue-200');
    const selectBtn = document.getElementById('selectCustomerGroupBtn');
    if (selectBtn) selectBtn.disabled = false;
}
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, initializing...');
    
    // Find the table button
    const tableBtn = document.querySelector('button[type="button"] i.fas.fa-table')?.closest('button');
    console.log('Table button found:', tableBtn);
    
    if (tableBtn) {
        tableBtn.addEventListener('click', function(e) {
            console.log('Table button clicked');
            e.preventDefault();
            openCustomerGroupTableModal();
        });
    } else {
        console.error('Table button not found');
    }

    // Set up select button
    const selectBtn = document.getElementById('selectCustomerGroupBtn');
    console.log('Select button found:', selectBtn);
    
    if (selectBtn) {
        selectBtn.addEventListener('click', function() {
            console.log('Select button clicked');
            if(selectedCustomerGroupRow) {
                const code = selectedCustomerGroupRow.getAttribute('data-code');
                const desc = selectedCustomerGroupRow.getAttribute('data-description');
                console.log('Selected group:', { code, desc });
                
                const codeInput = document.getElementById('group_code');
                const descInput = document.getElementById('description');
                
                if (codeInput) codeInput.value = code || '';
                if (descInput) descInput.value = desc || '';
                
                closeCustomerGroupTableModal();
            }
        });
    } else {
        console.error('Select button not found');
    }
});
</script>
@endsection 