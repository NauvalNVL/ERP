@extends('layouts.app')

@section('title', 'Define Product')

@section('header', 'Define Product')

@section('content')
<!-- Header Section -->
<div class="bg-gradient-to-r from-cyan-700 to-blue-600 p-6 rounded-t-lg shadow-lg">
    <h2 class="text-2xl font-bold text-white mb-2 flex items-center">
        <i class="fas fa-box-open mr-3"></i> Define Product
    </h2>
    <p class="text-cyan-100">Definisikan produk-produk untuk kategori dan kelompok yang spesifik</p>
</div>

<div class="bg-white rounded-b-lg shadow-lg p-6 mb-6">
    @if(session('success'))
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow-md animate__animated animate__fadeIn" role="alert">
        <div class="flex">
            <div class="py-1">
                <i class="fas fa-check-circle text-green-500 mr-3 text-xl"></i>
            </div>
            <div>
                <p class="font-bold">Berhasil!</p>
                <p>{{ session('success') }}</p>
            </div>
        </div>
    </div>
    @endif

    <!-- Main Content -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Left Column - Product Form -->
        <div class="lg:col-span-2">
            <div class="bg-white p-6 rounded-lg shadow-md border-t-4 border-blue-500">
                <div class="flex items-center mb-6 pb-2 border-b border-gray-200">
                    <div class="p-2 bg-blue-500 rounded-lg mr-3">
                        <i class="fas fa-edit text-white"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800">{{ isset($product) ? 'Edit Produk' : 'Tambah Produk Baru' }}</h3>
                </div>

                <form action="{{ isset($product) ? route('product.update', $product->product_code) : route('product.store') }}" method="POST" id="productForm" class="space-y-5">
                    @csrf
                    @if(isset($product))
                    @method('PUT')
                    @endif

                    <!-- Product Code Input Group -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                        <div class="col-span-2">
                            <label for="product_code" class="block text-sm font-medium text-gray-700 mb-1">Kode Produk</label>
                            <div class="relative flex">
                                <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500">
                                    <i class="fas fa-barcode"></i>
                                </span>
                                <input type="text" name="product_code" id="product_code"
                                    class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none border border-gray-300 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                    value="{{ isset($product) ? $product->product_code : old('product_code') }}"
                                    {{ isset($product) ? 'readonly' : '' }}
                                    maxlength="10" required placeholder="Masukkan kode produk">
                                <button type="button" id="selectProductBtn" class="inline-flex items-center px-3 py-2 border border-l-0 border-gray-300 bg-blue-500 hover:bg-blue-600 text-white rounded-r-md transition-colors">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                            @error('product_code')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="col-span-1">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Aksi</label>
                            <button type="button" id="recordSelectBtn" class="w-full flex items-center justify-center px-4 py-2 bg-indigo-500 hover:bg-indigo-600 text-white rounded transition-colors">
                                <i class="fas fa-list-ul mr-2"></i> Pilih Record
                            </button>
                        </div>
                    </div>

                    <!-- Description Input -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Deskripsi Produk</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
                                <i class="fas fa-align-left"></i>
                            </span>
                            <input type="text" name="description" id="description"
                                class="pl-10 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                value="{{ isset($product) ? $product->description : old('description') }}"
                                maxlength="255" required placeholder="Masukkan deskripsi produk">
                        </div>
                        @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Product Group Input -->
                    <div>
                        <label for="product_group_id" class="block text-sm font-medium text-gray-700 mb-1">Grup Produk</label>
                        <div class="relative flex">
                            <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500">
                                <i class="fas fa-layer-group"></i>
                            </span>
                            <input type="text" name="product_group_id" id="product_group_id"
                                class="flex-1 min-w-0 block px-3 py-2 rounded-none border border-gray-300 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                value="{{ isset($product) ? $product->product_group_id : old('product_group_id') }}"
                                readonly required placeholder="Pilih grup produk">
                            <button type="button" id="selectGroupBtn" class="inline-flex items-center px-3 py-2 border border-l-0 border-gray-300 bg-teal-500 hover:bg-teal-600 text-white rounded-r-md transition-colors">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                        @error('product_group_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Category Select -->
                    <div>
                        <label for="category" class="block text-sm font-medium text-gray-700 mb-1">Kategori Produk</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
                                <i class="fas fa-tags"></i>
                            </span>
                            <select name="category" id="category"
                                class="pl-10 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 transition-colors" required>
                                <option value="">Pilih Kategori</option>
                                @foreach($categories as $category)
                                <option value="{{ $category }}" {{ (isset($product) && $product->category == $category) || old('category') == $category ? 'selected' : '' }}>
                                    {{ $category }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        @error('category')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Form Actions -->
                    <div class="pt-5 border-t border-gray-200 flex justify-end space-x-3">
                        <a href="{{ route('product.index') }}" class="inline-flex justify-center px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 border border-gray-300 rounded-md hover:bg-gray-200 focus:outline-none transition-colors">
                            <i class="fas fa-times mr-2"></i>Batal
                        </a>
                        <button type="submit" class="inline-flex justify-center px-5 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700 focus:outline-none transition-colors">
                            <i class="fas fa-save mr-2"></i>{{ isset($product) ? 'Update' : 'Simpan' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Right Column - Quick Info -->
        <div class="lg:col-span-1">
            <!-- Group Info Card -->
            <div class="bg-white p-6 rounded-lg shadow-md border-t-4 border-teal-500 mb-6">
                <div class="flex items-center mb-4 pb-2 border-b border-gray-200">
                    <div class="p-2 bg-teal-500 rounded-lg mr-3">
                        <i class="fas fa-info-circle text-white"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800">Info Produk</h3>
                </div>

                <div class="space-y-4">
                    <div class="p-4 bg-teal-50 rounded-lg">
                        <h4 class="text-sm font-semibold text-teal-800 uppercase tracking-wider mb-2">Petunjuk</h4>
                        <ul class="list-disc pl-5 text-sm text-gray-600 space-y-1">
                            <li>Kode produk harus unik dan tidak berubah</li>
                            <li>Gunakan tombol <span class="font-medium">search</span> untuk memilih grup produk</li>
                            <li>Kategori menentukan UOM yang tersedia</li>
                            <li>Perubahan apa pun harus disimpan</li>
                        </ul>
                    </div>

                    <div class="p-4 bg-blue-50 rounded-lg">
                        <h4 class="text-sm font-semibold text-blue-800 uppercase tracking-wider mb-2">Kode Grup</h4>
                        <div class="grid grid-cols-2 gap-2 text-sm">
                            <div class="flex items-center">
                                <span class="w-6 h-6 flex items-center justify-center bg-blue-200 text-blue-800 rounded-full font-bold mr-2">B</span>
                                <span>Box</span>
                            </div>
                            <div class="flex items-center">
                                <span class="w-6 h-6 flex items-center justify-center bg-indigo-200 text-indigo-800 rounded-full font-bold mr-2">S</span>
                                <span>Sheet</span>
                            </div>
                            <div class="flex items-center">
                                <span class="w-6 h-6 flex items-center justify-center bg-teal-200 text-teal-800 rounded-full font-bold mr-2">R</span>
                                <span>Roll</span>
                            </div>
                            <div class="flex items-center">
                                <span class="w-6 h-6 flex items-center justify-center bg-purple-200 text-purple-800 rounded-full font-bold mr-2">OF</span>
                                <span>Offset</span>
                            </div>
                            <div class="flex items-center">
                                <span class="w-6 h-6 flex items-center justify-center bg-amber-200 text-amber-800 rounded-full font-bold mr-2">OT</span>
                                <span>Other</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Links -->
            <div class="bg-white p-6 rounded-lg shadow-md border-t-4 border-purple-500">
                <div class="flex items-center mb-4 pb-2 border-b border-gray-200">
                    <div class="p-2 bg-purple-500 rounded-lg mr-3">
                        <i class="fas fa-link text-white"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800">Tautan Cepat</h3>
                </div>

                <div class="grid grid-cols-1 gap-3">
                    <a href="{{ route('product-group.index') }}" class="flex items-center p-3 bg-purple-50 rounded-lg hover:bg-purple-100 transition-colors">
                        <div class="p-2 bg-purple-500 rounded-full mr-3">
                            <i class="fas fa-layer-group text-white text-sm"></i>
                        </div>
                        <div>
                            <p class="font-medium text-purple-900">Product Groups</p>
                            <p class="text-xs text-purple-700">Kelola grup produk</p>
                        </div>
                    </a>

                    <a href="#product-categories" class="flex items-center p-3 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                        <div class="p-2 bg-blue-500 rounded-full mr-3">
                            <i class="fas fa-th-list text-white text-sm"></i>
                        </div>
                        <div>
                            <p class="font-medium text-blue-900">Kategori UOM</p>
                            <p class="text-xs text-blue-700">Lihat UOM yang tersedia</p>
                        </div>
                    </a>

                    <a href="#" class="flex items-center p-3 bg-green-50 rounded-lg hover:bg-green-100 transition-colors">
                        <div class="p-2 bg-green-500 rounded-full mr-3">
                            <i class="fas fa-print text-white text-sm"></i>
                        </div>
                        <div>
                            <p class="font-medium text-green-900">Cetak Daftar</p>
                            <p class="text-xs text-green-700">Cetak daftar produk</p>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Product Categories Table -->
    <div id="product-categories" class="bg-white p-6 rounded-lg shadow-lg mt-8 border-t-4 border-amber-500">
        <div class="flex items-center mb-6 pb-2 border-b border-gray-200">
            <div class="p-2 bg-amber-500 rounded-lg mr-3">
                <i class="fas fa-table text-white"></i>
            </div>
            <h3 class="text-xl font-semibold text-gray-800">Kategori Produk dan UOM yang Diizinkan</h3>
        </div>

        <div class="overflow-x-auto rounded-lg shadow">
            <table class="min-w-full border-collapse">
                <thead>
                    <tr>
                        <th class="bg-gradient-to-r from-amber-600 to-amber-500 text-white px-4 py-3 text-left text-sm font-medium">Kategori</th>
                        <th class="bg-gradient-to-r from-amber-500 to-amber-400 text-white px-4 py-3 text-left text-sm font-medium">UOM</th>
                        <th class="bg-gradient-to-r from-amber-500 to-amber-400 text-white px-4 py-3 text-left text-sm font-medium">UOM</th>
                        <th class="bg-gradient-to-r from-amber-500 to-amber-400 text-white px-4 py-3 text-left text-sm font-medium">UOM</th>
                        <th class="bg-gradient-to-r from-amber-500 to-amber-400 text-white px-4 py-3 text-left text-sm font-medium">UOM</th>
                        <th class="bg-gradient-to-r from-amber-500 to-amber-400 text-white px-4 py-3 text-left text-sm font-medium">UOM</th>
                        <th class="bg-gradient-to-r from-amber-500 to-amber-400 text-white px-4 py-3 text-left text-sm font-medium">UOM</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="hover:bg-amber-50 transition-colors">
                        <td class="border border-gray-200 px-4 py-3 text-sm font-medium bg-amber-50">1-Corrugated Carton Box</td>
                        <td class="border border-gray-200 px-4 py-3 text-sm flex items-center"><span class="w-5 h-5 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center text-xs font-bold mr-2">P</span>Piece</td>
                        <td class="border border-gray-200 px-4 py-3 text-sm flex items-center"><span class="w-5 h-5 rounded-full bg-green-100 text-green-600 flex items-center justify-center text-xs font-bold mr-2">S</span>Set</td>
                        <td class="border border-gray-200 px-4 py-3 text-sm"></td>
                        <td class="border border-gray-200 px-4 py-3 text-sm"></td>
                        <td class="border border-gray-200 px-4 py-3 text-sm"></td>
                        <td class="border border-gray-200 px-4 py-3 text-sm"></td>
                    </tr>
                    <tr class="hover:bg-amber-50 transition-colors">
                        <td class="border border-gray-200 px-4 py-3 text-sm font-medium bg-amber-50">2-Single Facer Roll</td>
                        <td class="border border-gray-200 px-4 py-3 text-sm flex items-center"><span class="w-5 h-5 rounded-full bg-pink-100 text-pink-600 flex items-center justify-center text-xs font-bold mr-2">R</span>Roll</td>
                        <td class="border border-gray-200 px-4 py-3 text-sm"></td>
                        <td class="border border-gray-200 px-4 py-3 text-sm"></td>
                        <td class="border border-gray-200 px-4 py-3 text-sm"></td>
                        <td class="border border-gray-200 px-4 py-3 text-sm"></td>
                        <td class="border border-gray-200 px-4 py-3 text-sm"></td>
                    </tr>
                    <tr class="hover:bg-amber-50 transition-colors">
                        <td class="border border-gray-200 px-4 py-3 text-sm font-medium bg-amber-50">3-Single Facer Roll/KG</td>
                        <td class="border border-gray-200 px-4 py-3 text-sm flex items-center"><span class="w-5 h-5 rounded-full bg-purple-100 text-purple-600 flex items-center justify-center text-xs font-bold mr-2">K</span>KG</td>
                        <td class="border border-gray-200 px-4 py-3 text-sm"></td>
                        <td class="border border-gray-200 px-4 py-3 text-sm"></td>
                        <td class="border border-gray-200 px-4 py-3 text-sm"></td>
                        <td class="border border-gray-200 px-4 py-3 text-sm"></td>
                        <td class="border border-gray-200 px-4 py-3 text-sm"></td>
                    </tr>
                    <tr class="hover:bg-amber-50 transition-colors">
                        <td class="border border-gray-200 px-4 py-3 text-sm font-medium bg-amber-50">4-Single Facer Sheet</td>
                        <td class="border border-gray-200 px-4 py-3 text-sm flex items-center"><span class="w-5 h-5 rounded-full bg-teal-100 text-teal-600 flex items-center justify-center text-xs font-bold mr-2">T</span>Sheet</td>
                        <td class="border border-gray-200 px-4 py-3 text-sm"></td>
                        <td class="border border-gray-200 px-4 py-3 text-sm"></td>
                        <td class="border border-gray-200 px-4 py-3 text-sm"></td>
                        <td class="border border-gray-200 px-4 py-3 text-sm"></td>
                        <td class="border border-gray-200 px-4 py-3 text-sm"></td>
                    </tr>
                    <tr class="hover:bg-amber-50 transition-colors">
                        <td class="border border-gray-200 px-4 py-3 text-sm font-medium bg-amber-50">5-Corrugated Sheet Board/Piece</td>
                        <td class="border border-gray-200 px-4 py-3 text-sm flex items-center"><span class="w-5 h-5 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center text-xs font-bold mr-2">P</span>Piece(Gross M2)</td>
                        <td class="border border-gray-200 px-4 py-3 text-sm flex items-center"><span class="w-5 h-5 rounded-full bg-orange-100 text-orange-600 flex items-center justify-center text-xs font-bold mr-2">O</span>Piece(Tim M2)</td>
                        <td class="border border-gray-200 px-4 py-3 text-sm"></td>
                        <td class="border border-gray-200 px-4 py-3 text-sm"></td>
                        <td class="border border-gray-200 px-4 py-3 text-sm"></td>
                        <td class="border border-gray-200 px-4 py-3 text-sm"></td>
                    </tr>
                    <tr class="hover:bg-amber-50 transition-colors">
                        <td class="border border-gray-200 px-4 py-3 text-sm font-medium bg-amber-50">6-Corrugated Sheet Board/M2</td>
                        <td class="border border-gray-200 px-4 py-3 text-sm flex items-center"><span class="w-5 h-5 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center text-xs font-bold mr-2">M</span>Gross M2</td>
                        <td class="border border-gray-200 px-4 py-3 text-sm flex items-center"><span class="w-5 h-5 rounded-full bg-cyan-100 text-cyan-600 flex items-center justify-center text-xs font-bold mr-2">N</span>Timmed M2</td>
                        <td class="border border-gray-200 px-4 py-3 text-sm"></td>
                        <td class="border border-gray-200 px-4 py-3 text-sm"></td>
                        <td class="border border-gray-200 px-4 py-3 text-sm"></td>
                        <td class="border border-gray-200 px-4 py-3 text-sm"></td>
                    </tr>
                    <tr class="hover:bg-amber-50 transition-colors">
                        <td class="border border-gray-200 px-4 py-3 text-sm font-medium bg-amber-50">7-Other Packaging Products</td>
                        <td class="border border-gray-200 px-4 py-3 text-sm flex items-center"><span class="w-5 h-5 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center text-xs font-bold mr-2">P</span>Piece</td>
                        <td class="border border-gray-200 px-4 py-3 text-sm flex items-center"><span class="w-5 h-5 rounded-full bg-green-100 text-green-600 flex items-center justify-center text-xs font-bold mr-2">S</span>Set</td>
                        <td class="border border-gray-200 px-4 py-3 text-sm flex items-center"><span class="w-5 h-5 rounded-full bg-red-100 text-red-600 flex items-center justify-center text-xs font-bold mr-2">D</span>Bundle</td>
                        <td class="border border-gray-200 px-4 py-3 text-sm flex items-center"><span class="w-5 h-5 rounded-full bg-amber-100 text-amber-600 flex items-center justify-center text-xs font-bold mr-2">L</span>Pallet</td>
                        <td class="border border-gray-200 px-4 py-3 text-sm flex items-center"><span class="w-5 h-5 rounded-full bg-purple-100 text-purple-600 flex items-center justify-center text-xs font-bold mr-2">K</span>KG</td>
                        <td class="border border-gray-200 px-4 py-3 text-sm flex items-center"><span class="w-5 h-5 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center text-xs font-bold mr-2">B</span>Box</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="mt-3 flex items-center text-sm text-gray-600 bg-yellow-50 p-3 rounded-lg">
            <i class="fas fa-info-circle text-yellow-500 mr-2"></i>
            <span>Trimmed M2 = Gross M2 less Corrugating Trim Waste</span>
        </div>
    </div>
</div>

<!-- Product Group Table Modal -->
<div id="productGroupTableModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 transition-all duration-300">
    <div class="bg-white rounded-lg shadow-xl w-11/12 md:w-3/4 max-w-3xl transform transition-transform duration-300 scale-95" id="productGroupContent">
        <div class="flex items-center justify-between p-4 border-b border-gray-200 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-t-lg">
            <div class="flex items-center">
                <div class="p-2 bg-white bg-opacity-30 rounded-lg mr-3">
                    <i class="fas fa-layer-group"></i>
                </div>
                <h3 class="text-xl font-semibold">Pilih Grup Produk</h3>
            </div>
            <button type="button" class="text-white hover:text-gray-200 focus:outline-none" id="closeGroupModalBtn">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>

        <div class="p-5">
            <div class="mb-4">
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
                        <i class="fas fa-search"></i>
                    </span>
                    <input type="text" id="searchGroupInput" placeholder="Cari grup produk..."
                        class="pl-10 pr-4 py-2 w-full border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 bg-gray-50">
                </div>
            </div>

            <div class="max-h-96 overflow-auto rounded-lg border border-gray-200">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50 sticky top-0">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kode</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200" id="productGroupTableBody">
                        <tr class="hover:bg-blue-50 cursor-pointer product-group-row" data-id="B" data-name="BOX">
                            <td class="px-6 py-3 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-blue-500 text-white rounded-full flex items-center justify-center font-bold mr-3">B</div>
                                    <span class="font-medium text-gray-900">B</span>
                                </div>
                            </td>
                            <td class="px-6 py-3 whitespace-nowrap text-gray-700">BOX</td>
                        </tr>
                        <tr class="hover:bg-blue-50 cursor-pointer product-group-row" data-id="OF" data-name="OFFSET">
                            <td class="px-6 py-3 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-indigo-500 text-white rounded-full flex items-center justify-center font-bold mr-3">OF</div>
                                    <span class="font-medium text-gray-900">OF</span>
                                </div>
                            </td>
                            <td class="px-6 py-3 whitespace-nowrap text-gray-700">OFFSET</td>
                        </tr>
                        <tr class="hover:bg-blue-50 cursor-pointer product-group-row" data-id="OT" data-name="OTHER">
                            <td class="px-6 py-3 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-purple-500 text-white rounded-full flex items-center justify-center font-bold mr-3">OT</div>
                                    <span class="font-medium text-gray-900">OT</span>
                                </div>
                            </td>
                            <td class="px-6 py-3 whitespace-nowrap text-gray-700">OTHER</td>
                        </tr>
                        <tr class="hover:bg-blue-50 cursor-pointer product-group-row" data-id="R" data-name="PAPER ROLL">
                            <td class="px-6 py-3 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-teal-500 text-white rounded-full flex items-center justify-center font-bold mr-3">R</div>
                                    <span class="font-medium text-gray-900">R</span>
                                </div>
                            </td>
                            <td class="px-6 py-3 whitespace-nowrap text-gray-700">PAPER ROLL</td>
                        </tr>
                        <tr class="hover:bg-blue-50 cursor-pointer product-group-row" data-id="S" data-name="SHEET BOARD">
                            <td class="px-6 py-3 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-amber-500 text-white rounded-full flex items-center justify-center font-bold mr-3">S</div>
                                    <span class="font-medium text-gray-900">S</span>
                                </div>
                            </td>
                            <td class="px-6 py-3 whitespace-nowrap text-gray-700">SHEET BOARD</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="p-4 border-t border-gray-200 flex justify-end space-x-3 bg-gray-50 rounded-b-lg">
            <button type="button" id="exitProductGroupBtn" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition-colors">
                <i class="fas fa-times mr-2"></i>Tutup
            </button>
            <button type="button" id="selectProductGroupBtn" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors">
                <i class="fas fa-check mr-2"></i>Pilih
            </button>
        </div>
    </div>
</div>

<!-- Product Table Modal -->
<div id="productTableModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 transition-all duration-300">
    <div class="bg-white rounded-lg shadow-xl w-11/12 md:w-3/4 max-w-5xl transform transition-transform duration-300 scale-95" id="productModalContent">
        <div class="flex items-center justify-between p-4 border-b border-gray-200 bg-gradient-to-r from-cyan-600 to-cyan-700 text-white rounded-t-lg">
            <div class="flex items-center">
                <div class="p-2 bg-white bg-opacity-30 rounded-lg mr-3">
                    <i class="fas fa-box"></i>
                </div>
                <h3 class="text-xl font-semibold">Pilih Produk</h3>
            </div>
            <button type="button" class="text-white hover:text-gray-200 focus:outline-none" id="closeProductModalBtn">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>

        <div class="p-5">
            <div class="mb-4 grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="col-span-2">
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
                            <i class="fas fa-search"></i>
                        </span>
                        <input type="text" id="searchProductInput" placeholder="Cari produk..."
                            class="pl-10 pr-4 py-2 w-full border border-gray-300 rounded-lg focus:ring-cyan-500 focus:border-cyan-500 bg-gray-50">
                    </div>
                </div>
                <div>
                    <select id="filterProductGroup" class="w-full border border-gray-300 rounded-lg focus:ring-cyan-500 focus:border-cyan-500 bg-gray-50 py-2 px-3">
                        <option value="">Semua Grup</option>
                        <option value="B">BOX (B)</option>
                        <option value="OF">OFFSET (OF)</option>
                        <option value="OT">OTHER (OT)</option>
                        <option value="R">PAPER ROLL (R)</option>
                        <option value="S">SHEET BOARD (S)</option>
                    </select>
                </div>
            </div>

            <div class="max-h-96 overflow-auto rounded-lg border border-gray-200">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50 sticky top-0">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/5">Kode</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/5">Deskripsi</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/5">Grup#</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/5">Kategori</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-[10%]">Status</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-[10%]">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200" id="productTableBody">
                        @forelse ($products as $item)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $item->product_code }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $item->description }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $item->product_group_id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $item->category }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm">
                                @if($item->is_active)
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Aktif</span>
                                @else
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Nonaktif</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                <a href="{{ route('product.edit', $item->product_code) }}" class="text-indigo-600 hover:text-indigo-900 transition-colors mr-3" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('product.destroy', $item->product_code) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus produk ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900 transition-colors" title="Hapus">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">Tidak ada data produk yang ditemukan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="p-4 border-t border-gray-200 flex justify-end space-x-3 bg-gray-50 rounded-b-lg">
            <button type="button" id="exitProductBtn" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition-colors">
                <i class="fas fa-times mr-2"></i>Tutup
            </button>
            <button type="button" id="selectProductTableBtn" class="px-4 py-2 bg-cyan-500 text-white rounded-lg hover:bg-cyan-600 transition-colors">
                <i class="fas fa-check mr-2"></i>Pilih
            </button>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // ============= Product Group Modal =============
        const groupModal = document.getElementById('productGroupTableModal');
        const groupModalContent = document.getElementById('productGroupContent');
        const selectGroupBtn = document.getElementById('selectGroupBtn');
        const closeGroupModalBtn = document.getElementById('closeGroupModalBtn');
        const exitGroupBtn = document.getElementById('exitProductGroupBtn');
        const selectProductGroupBtn = document.getElementById('selectProductGroupBtn');
        const productGroupRows = document.querySelectorAll('.product-group-row');
        const productGroupIdInput = document.getElementById('product_group_id');
        const searchGroupInput = document.getElementById('searchGroupInput');

        // ============= Product Modal =============
        const productModal = document.getElementById('productTableModal');
        const productModalContent = document.getElementById('productModalContent');
        const selectProductBtn = document.getElementById('selectProductBtn');
        const recordSelectBtn = document.getElementById('recordSelectBtn');
        const closeProductModalBtn = document.getElementById('closeProductModalBtn');
        const exitProductBtn = document.getElementById('exitProductBtn');
        const selectProductTableBtn = document.getElementById('selectProductTableBtn');
        const productRows = document.querySelectorAll('.product-row');
        const productCodeInput = document.getElementById('product_code');
        const descriptionInput = document.getElementById('description');
        const categorySelect = document.getElementById('category');
        const searchProductInput = document.getElementById('searchProductInput');
        const filterProductGroup = document.getElementById('filterProductGroup');

        let selectedGroupRow = null;
        let selectedProductRow = null;

        // ============= Product Group Modal Functions =============
        function openGroupModal() {
            groupModal.classList.remove('hidden');
            setTimeout(() => {
                groupModalContent.classList.remove('scale-95', 'opacity-0');
                groupModalContent.classList.add('scale-100', 'opacity-100');
            }, 50);
            searchGroupInput.focus();
        }

        function closeGroupModal() {
            groupModalContent.classList.remove('scale-100', 'opacity-100');
            groupModalContent.classList.add('scale-95', 'opacity-0');
            setTimeout(() => {
                groupModal.classList.add('hidden');
            }, 300);
        }

        selectGroupBtn.addEventListener('click', function(e) {
            e.preventDefault();
            openGroupModal();
        });

        closeGroupModalBtn.addEventListener('click', closeGroupModal);
        exitGroupBtn.addEventListener('click', closeGroupModal);

        productGroupRows.forEach(row => {
            row.addEventListener('click', function() {
                productGroupRows.forEach(r => r.classList.remove('bg-blue-100'));
                this.classList.add('bg-blue-100');
                selectedGroupRow = this;
            });

            row.addEventListener('dblclick', function() {
                const id = this.getAttribute('data-id');
                productGroupIdInput.value = id;
                closeGroupModal();
            });
        });

        selectProductGroupBtn.addEventListener('click', function() {
            if (selectedGroupRow) {
                const id = selectedGroupRow.getAttribute('data-id');
                productGroupIdInput.value = id;
                closeGroupModal();
            } else {
                alert('Silakan pilih grup produk terlebih dahulu.');
            }
        });

        // Filter untuk mencari grup produk
        searchGroupInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            productGroupRows.forEach(row => {
                const id = row.getAttribute('data-id').toLowerCase();
                const name = row.getAttribute('data-name').toLowerCase();

                if (id.includes(searchTerm) || name.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });

        // ============= Product Modal Functions =============
        function openProductModal() {
            productModal.classList.remove('hidden');
            setTimeout(() => {
                productModalContent.classList.remove('scale-95', 'opacity-0');
                productModalContent.classList.add('scale-100', 'opacity-100');
            }, 50);
            searchProductInput.focus();
        }

        function closeProductModal() {
            productModalContent.classList.remove('scale-100', 'opacity-100');
            productModalContent.classList.add('scale-95', 'opacity-0');
            setTimeout(() => {
                productModal.classList.add('hidden');
            }, 300);
        }

        selectProductBtn.addEventListener('click', function(e) {
            e.preventDefault();
            openProductModal();
        });

        recordSelectBtn.addEventListener('click', function(e) {
            e.preventDefault();
            openProductModal();
        });

        closeProductModalBtn.addEventListener('click', closeProductModal);
        exitProductBtn.addEventListener('click', closeProductModal);

        productRows.forEach(row => {
            row.addEventListener('click', function() {
                productRows.forEach(r => r.classList.remove('bg-cyan-100'));
                this.classList.add('bg-cyan-100');
                selectedProductRow = this;
            });

            row.addEventListener('dblclick', function() {
                selectProductData(this);
                closeProductModal();
            });
        });

        selectProductTableBtn.addEventListener('click', function() {
            if (selectedProductRow) {
                selectProductData(selectedProductRow);
                closeProductModal();
            } else {
                alert('Silakan pilih produk terlebih dahulu.');
            }
        });

        function selectProductData(row) {
            const code = row.getAttribute('data-code');
            const description = row.getAttribute('data-description');
            const group = row.getAttribute('data-group');
            const category = row.getAttribute('data-category');

            productCodeInput.value = code;
            descriptionInput.value = description;
            productGroupIdInput.value = group;

            // Set kategori jika valid
            if (category && category !== 'X') {
                const categoryOptions = categorySelect.querySelectorAll('option');
                let categoryFound = false;

                categoryOptions.forEach(option => {
                    if (option.value.startsWith(category + '-')) {
                        categorySelect.value = option.value;
                        categoryFound = true;
                    }
                });

                if (!categoryFound && !isNaN(parseInt(category))) {
                    categoryOptions.forEach(option => {
                        if (option.value.startsWith(category)) {
                            categorySelect.value = option.value;
                        }
                    });
                }
            }
        }

        // Filter untuk mencari produk
        searchProductInput.addEventListener('input', function() {
            filterProducts();
        });

        filterProductGroup.addEventListener('change', function() {
            filterProducts();
        });

        function filterProducts() {
            const searchTerm = searchProductInput.value.toLowerCase();
            const groupFilter = filterProductGroup.value;

            productRows.forEach(row => {
                const code = row.getAttribute('data-code').toLowerCase();
                const description = row.getAttribute('data-description').toLowerCase();
                const group = row.getAttribute('data-group');

                const matchesSearch = code.includes(searchTerm) || description.includes(searchTerm);
                const matchesGroup = !groupFilter || group === groupFilter;

                if (matchesSearch && matchesGroup) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }

        // Tutup modal saat mengklik backdrop
        window.addEventListener('click', function(event) {
            if (event.target === groupModal) {
                closeGroupModal();
            }
            if (event.target === productModal) {
                closeProductModal();
            }
        });

        // Konfirmasi sebelum submit form
        const form = document.getElementById('productForm');
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            if (confirm('Apakah Anda yakin ingin menyimpan data ini?')) {
                this.submit();
            }
        });

        // Navigasi keyboard untuk modal grup produk
        searchGroupInput.addEventListener('keydown', function(e) {
            // Enter untuk memilih grup yang dipilih
            if (e.key === 'Enter' && selectedGroupRow) {
                const id = selectedGroupRow.getAttribute('data-id');
                productGroupIdInput.value = id;
                closeGroupModal();
            }
            // Escape untuk menutup modal
            else if (e.key === 'Escape') {
                closeGroupModal();
            }
        });

        // Navigasi keyboard untuk modal produk
        searchProductInput.addEventListener('keydown', function(e) {
            // Enter untuk memilih produk yang dipilih
            if (e.key === 'Enter' && selectedProductRow) {
                selectProductData(selectedProductRow);
                closeProductModal();
            }
            // Escape untuk menutup modal
            else if (e.key === 'Escape') {
                closeProductModal();
            }
        });
    });
</script>
@endsection