@extends('layouts.app')

@section('title', 'Define Product Group')

@section('header', 'Define Product Group')

@section('content')
<div class="bg-gradient-to-r from-teal-700 to-teal-500 p-6 rounded-lg shadow-lg mb-6">
    <h2 class="text-2xl font-bold text-white mb-2">Define Product Group</h2>
    <p class="text-teal-100">Kelola kelompok produk untuk kategorisasi yang lebih baik</p>
</div>

@if(session('success'))
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow-md animate__animated animate__fadeIn" role="alert">
        <div class="flex">
            <div class="py-1">
                <svg class="w-6 h-6 mr-4 text-green-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <p class="font-bold">Success!</p>
                <p class="text-sm">{{ session('success') }}</p>
            </div>
        </div>
    </div>
@endif

<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
    <!-- Form Card -->
    <div class="bg-white p-6 rounded-lg shadow-lg border-t-4 border-teal-500 transform transition duration-300 hover:scale-[1.01]">
        <div class="flex items-center mb-4 pb-2 border-b border-gray-200">
            <div class="p-2 bg-teal-500 rounded-lg mr-3">
                <svg class="w-6 h-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
            </div>
            <h3 class="text-xl font-semibold text-gray-800">{{ isset($productGroup) ? 'Edit Product Group' : 'New Product Group' }}</h3>
        </div>

        <form action="{{ isset($productGroup) ? route('product-group.update', $productGroup->product_group_id) : route('product-group.store') }}" method="POST" id="productGroupForm" class="space-y-4">
            @csrf
            @if(isset($productGroup))
                @method('PUT')
            @endif

            <div class="relative">
                <label for="product_group_id" class="text-sm font-medium text-gray-700 block mb-2">Product Group ID</label>
                <div class="relative flex items-center">
                    <span class="absolute left-0 inset-y-0 flex items-center pl-3 text-gray-500">
                        <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                        </svg>
                    </span>
                    <input type="text" name="product_group_id" id="product_group_id" 
                        class="pl-10 pr-12 py-2 w-full border border-gray-300 rounded-lg focus:ring-teal-500 focus:border-teal-500 transition-all duration-300"
                        value="{{ isset($productGroup) ? $productGroup->product_group_id : old('product_group_id') }}"
                        {{ isset($productGroup) ? 'readonly' : '' }}
                        placeholder="Enter Group ID"
                        maxlength="10" required>
                    <button type="button" id="selectBtn" class="absolute right-2 inset-y-0 px-3 flex items-center bg-teal-500 text-white rounded-lg hover:bg-teal-600 transition-colors">
                        <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </button>
                </div>
                @error('product_group_id')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="product_group_name" class="text-sm font-medium text-gray-700 block mb-2">Product Group Name</label>
                <div class="relative">
                    <span class="absolute left-0 inset-y-0 flex items-center pl-3 text-gray-500">
                        <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                        </svg>
                    </span>
                    <input type="text" name="product_group_name" id="product_group_name" 
                        class="pl-10 py-2 w-full border border-gray-300 rounded-lg focus:ring-teal-500 focus:border-teal-500 transition-all duration-300"
                        value="{{ isset($productGroup) ? $productGroup->product_group_name : old('product_group_name') }}"
                        placeholder="Enter Group Name"
                        maxlength="100" required>
                </div>
                @error('product_group_name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end space-x-3 pt-4">
                <a href="{{ route('product-group.index') }}" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition-colors flex items-center">
                    <svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    Cancel
                </a>
                <button type="submit" class="px-4 py-2 bg-teal-500 text-white rounded-lg hover:bg-teal-600 transition-colors flex items-center">
                    <svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    {{ isset($productGroup) ? 'Update' : 'Save' }}
                </button>
            </div>
        </form>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white p-6 rounded-lg shadow-lg border-t-4 border-indigo-500 transform transition duration-300 hover:scale-[1.01]">
        <div class="flex items-center mb-4 pb-2 border-b border-gray-200">
            <div class="p-2 bg-indigo-500 rounded-lg mr-3">
                <svg class="w-6 h-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
            </div>
            <h3 class="text-xl font-semibold text-gray-800">Quick Actions</h3>
        </div>

        <div class="space-y-3">
            <button id="recordSelectBtn" class="w-full bg-indigo-50 p-4 rounded-lg flex items-center justify-between hover:bg-indigo-100 transition-colors">
                <div class="flex items-center">
                    <div class="p-2 bg-indigo-500 rounded-lg mr-3">
                        <svg class="w-5 h-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                    <div>
                        <h4 class="font-medium text-gray-700">Browse Records</h4>
                        <p class="text-sm text-gray-500">View and select from existing product groups</p>
                    </div>
                </div>
                <svg class="w-5 h-5 text-indigo-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </button>

            <a href="{{ route('product-group.index') }}" class="w-full bg-teal-50 p-4 rounded-lg flex items-center justify-between hover:bg-teal-100 transition-colors">
                <div class="flex items-center">
                    <div class="p-2 bg-teal-500 rounded-lg mr-3">
                        <svg class="w-5 h-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                        </svg>
                    </div>
                    <div>
                        <h4 class="font-medium text-gray-700">View All Groups</h4>
                        <p class="text-sm text-gray-500">See a complete list of product groups</p>
                    </div>
                </div>
                <svg class="w-5 h-5 text-teal-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </a>

            <div class="w-full bg-yellow-50 p-4 rounded-lg">
                <div class="flex items-center mb-3">
                    <div class="p-2 bg-yellow-500 rounded-lg mr-3">
                        <svg class="w-5 h-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h4 class="font-medium text-gray-700">Need to Know</h4>
                </div>
                <ul class="text-sm text-gray-600 space-y-2 pl-10 list-disc">
                    <li>Product Group ID harus unik</li>
                    <li>Grup produk digunakan untuk kategorisasi</li>
                    <li>Product Group Name maksimal 100 karakter</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Product List -->
<div class="bg-white p-6 rounded-lg shadow-lg border-t-4 border-blue-500 mb-6 transform transition duration-300 hover:scale-[1.01]">
    <div class="flex items-center justify-between mb-6 pb-2 border-b border-gray-200">
        <div class="flex items-center">
            <div class="p-2 bg-blue-500 rounded-lg mr-3">
                <svg class="w-6 h-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
            </div>
            <h3 class="text-xl font-semibold text-gray-800">Product Group List</h3>
        </div>
        <div class="bg-gray-100 px-3 py-1 rounded-lg flex items-center">
            <svg class="w-5 h-5 text-gray-500 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            <span class="text-sm text-gray-600">Total: {{ count($productGroups) }} groups</span>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @forelse($productGroups as $pg)
        <div class="bg-gray-50 rounded-lg p-4 border border-gray-200 hover:shadow-md transition-shadow">
            <div class="flex justify-between items-start">
                <div class="flex items-center">
                    <div class="bg-blue-500 text-white rounded-full w-10 h-10 flex items-center justify-center font-bold text-lg mr-3">
                        {{ substr($pg->product_group_id, 0, 1) }}
                    </div>
                    <div>
                        <h4 class="font-bold text-gray-800">{{ $pg->product_group_id }}</h4>
                        <p class="text-gray-600">{{ $pg->product_group_name }}</p>
                    </div>
                </div>
                <div class="flex space-x-1">
                    <a href="{{ route('product-group.edit', $pg->product_group_id) }}" class="p-1.5 bg-indigo-100 text-indigo-600 rounded hover:bg-indigo-200">
                        <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                    </a>
                    <form action="{{ route('product-group.destroy', $pg->product_group_id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus grup produk ini?');" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="p-1.5 bg-red-100 text-red-600 rounded hover:bg-red-200">
                            <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-3 bg-gray-50 rounded-lg p-8 border border-gray-200 text-center">
            <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
            </svg>
            <p class="text-gray-500 mb-2">Tidak ada data grup produk</p>
            <a href="#" class="text-teal-500 hover:text-teal-600 font-medium">Tambahkan grup produk baru</a>
        </div>
        @endforelse
    </div>
</div>

<!-- Product Group Table Modal -->
<div id="productGroupTableModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 transition-opacity">
    <div class="bg-white rounded-lg shadow-xl w-11/12 md:w-3/4 max-w-3xl transform transition-transform duration-300 scale-95 opacity-0" id="modalContent">
        <div class="flex items-center justify-between p-4 border-b border-gray-200">
            <div class="flex items-center">
                <div class="p-2 bg-blue-500 rounded-lg mr-3">
                    <svg class="w-6 h-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-800">Product Group Table</h3>
            </div>
            <button type="button" id="modalCloseBtn" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <div class="p-6">
            <div class="mb-4">
                <div class="relative">
                    <input type="text" id="searchInput" placeholder="Search product groups..." 
                        class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="w-5 h-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                </div>
            </div>
            <div class="max-h-96 overflow-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50 sticky top-0">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200" id="productTableBody">
                        @foreach($productGroups as $pg)
                        <tr class="hover:bg-blue-50 cursor-pointer product-row" data-id="{{ $pg->product_group_id }}" data-name="{{ $pg->product_group_name }}">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $pg->product_group_id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $pg->product_group_name }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="p-4 border-t border-gray-200 flex justify-end space-x-3">
            <button type="button" id="exitProductGroupBtn" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition-colors flex items-center">
                <svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
                Cancel
            </button>
            <button type="button" id="selectProductGroupBtn" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors flex items-center">
                <svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                Select
            </button>
        </div>
    </div>
</div>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('productGroupTableModal');
        const modalContent = document.getElementById('modalContent');
        const selectBtn = document.getElementById('selectBtn');
        const recordSelectBtn = document.getElementById('recordSelectBtn');
        const exitBtn = document.getElementById('exitProductGroupBtn');
        const modalCloseBtn = document.getElementById('modalCloseBtn');
        const selectProductGroupBtn = document.getElementById('selectProductGroupBtn');
        const productRows = document.querySelectorAll('.product-row');
        const productGroupIdInput = document.getElementById('product_group_id');
        const productGroupNameInput = document.getElementById('product_group_name');
        const searchInput = document.getElementById('searchInput');
        
        let selectedRow = null;

        // Tampilkan modal dengan animasi
        function showModal() {
            modal.classList.remove('hidden');
            setTimeout(() => {
                modalContent.classList.remove('scale-95', 'opacity-0');
                modalContent.classList.add('scale-100', 'opacity-100');
            }, 50);
        }

        // Sembunyikan modal dengan animasi
        function hideModal() {
            modalContent.classList.remove('scale-100', 'opacity-100');
            modalContent.classList.add('scale-95', 'opacity-0');
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 300);
        }

        // Tampilkan modal saat tombol select diklik
        selectBtn.addEventListener('click', showModal);
        recordSelectBtn.addEventListener('click', showModal);

        // Tutup modal saat tombol exit/close diklik
        exitBtn.addEventListener('click', hideModal);
        modalCloseBtn.addEventListener('click', hideModal);

        // Filter tabel saat pencarian
        searchInput.addEventListener('keyup', function() {
            const searchTerm = this.value.toLowerCase();
            const rows = document.querySelectorAll('#productTableBody tr');
            
            rows.forEach(row => {
                const id = row.querySelector('td:first-child').textContent.toLowerCase();
                const name = row.querySelector('td:last-child').textContent.toLowerCase();
                
                if (id.includes(searchTerm) || name.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });

        // Pilih baris saat diklik
        productRows.forEach(row => {
            row.addEventListener('click', function() {
                // Hapus highlight dari baris lain
                productRows.forEach(r => r.classList.remove('bg-blue-100'));
                
                // Highlight baris yang dipilih
                this.classList.add('bg-blue-100');
                selectedRow = this;
            });
            
            // Double klik untuk memilih dan menutup modal
            row.addEventListener('dblclick', function() {
                const id = this.getAttribute('data-id');
                const name = this.getAttribute('data-name');
                
                productGroupIdInput.value = id;
                productGroupNameInput.value = name;
                
                hideModal();
            });
        });

        // Tombol Select untuk konfirmasi pilihan
        selectProductGroupBtn.addEventListener('click', function() {
            if (selectedRow) {
                const id = selectedRow.getAttribute('data-id');
                const name = selectedRow.getAttribute('data-name');
                
                productGroupIdInput.value = id;
                productGroupNameInput.value = name;
                
                hideModal();
            } else {
                alert('Silakan pilih grup produk terlebih dahulu.');
            }
        });

        // Menutup modal jika mengklik di luar modal content
        modal.addEventListener('click', function(event) {
            if (event.target === modal) {
                hideModal();
            }
        });
        
        // Submit form dengan konfirmasi
        const form = document.getElementById('productGroupForm');
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            if (confirm('Apakah Anda yakin ingin menyimpan data ini?')) {
                this.submit();
            }
        });
    });
</script>
@endsection
