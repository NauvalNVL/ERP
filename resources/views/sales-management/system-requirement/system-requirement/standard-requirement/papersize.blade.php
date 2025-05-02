@extends('layouts.app')

@section('title', 'Define Paper Size')

@section('content')
<script src="{{ asset('js/papersize.js') }}"></script>

<!-- Header Section -->
<div class="bg-gradient-to-r from-cyan-700 to-blue-600 p-6 rounded-t-lg shadow-lg">
    <h2 class="text-2xl font-bold text-white mb-2 flex items-center">
        <i class="fas fa-file-alt mr-3"></i> Define Paper Size
    </h2>
    <p class="text-cyan-100">Definisikan ukuran kertas standar untuk berbagai dokumen dan aplikasi</p>
        </div>

<div class="bg-white rounded-b-lg shadow-lg p-6 mb-6">
    <!-- Main Content -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Left Column - Main Content -->
        <div class="lg:col-span-2">
            <div class="bg-white p-6 rounded-lg shadow-md border-t-4 border-blue-500">
                <div class="flex items-center mb-6 pb-2 border-b border-gray-200">
                    <div class="p-2 bg-blue-500 rounded-lg mr-3">
                        <i class="fas fa-edit text-white"></i>
                </div>
                    <h3 class="text-xl font-semibold text-gray-800">Paper Size Management</h3>
                </div>
                
                <!-- Header with navigation buttons -->
                <div class="flex items-center space-x-2 mb-6">
                    <button type="button" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded flex items-center space-x-2 transform active:translate-y-px">
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
                        <i class="fas fa-save"></i>
                    </button>
                </div>

                <!-- Paper Size Form -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-6">
                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Paper Size:</label>
                        <div class="relative flex">
                            <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500">
                                <i class="fas fa-ruler-combined"></i>
                            </span>
                            <input type="number" step="0.01" id="size" name="size" 
                                min="0.01"
                                class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none border border-gray-300 focus:ring-blue-500 focus:border-blue-500 text-right">
                            <button type="button" id="showPaperSizeTableBtn" class="inline-flex items-center px-3 py-2 border border-l-0 border-gray-300 bg-blue-500 hover:bg-blue-600 text-white rounded-r-md transition-colors transform active:translate-y-px" onclick="openPaperSizeModal()">
                                <i class="fas fa-table"></i>
                            </button>
                        </div>
                        <span class="text-xs text-gray-700 mt-1 block">Millimeter</span>
                    </div>
                    
                    <div class="col-span-1">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Record:</label>
                        <button type="button" class="w-full flex items-center justify-center px-4 py-2 bg-indigo-500 hover:bg-indigo-600 text-white rounded transition-colors transform active:translate-y-px">
                            <i class="fas fa-list-ul mr-2"></i> Select Record
                        </button>
                    </div>
                </div>

                <!-- Inches Display -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Equivalent Size:</label>
                    <div class="flex items-center bg-gray-100 p-3 rounded-lg">
                        <span class="mr-2 text-gray-700"><i class="fas fa-ruler mr-2"></i></span>
                        <span class="text-lg font-medium" id="inchDisplay"></span>
                    </div>
                </div>

                <!-- Debug Information -->
                <div class="mt-6">
                    @if(empty($paperSizes) || count($paperSizes) === 0)
                    <div class="mt-4 bg-yellow-100 p-3 rounded">
                        <p class="text-sm font-medium text-yellow-800">Tidak ada data ukuran kertas yang tersedia.</p>
                        <p class="text-xs text-yellow-700 mt-1">Pastikan database telah diatur dengan benar dan data seeder telah dijalankan.</p>
                        <div class="mt-2 flex items-center space-x-3">
                            <button onclick="loadSeedData()" class="bg-green-500 hover:bg-green-600 text-white text-xs px-3 py-1 rounded transform active:translate-y-px">
                                Load Paper Size Data (JS)
                            </button>
                        </div>
                    </div>
                    @elseif(count($paperSizes) > 0)
                    <div class="mt-4 bg-green-100 p-3 rounded">
                        <p class="text-sm font-medium text-green-800">Data tersedia: {{ count($paperSizes) }} ukuran kertas ditemukan.</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Right Column - Quick Info -->
        <div class="lg:col-span-1">
            <!-- Paper Size Info Card -->
            <div class="bg-white p-6 rounded-lg shadow-md border-t-4 border-teal-500 mb-6">
                <div class="flex items-center mb-4 pb-2 border-b border-gray-200">
                    <div class="p-2 bg-teal-500 rounded-lg mr-3">
                        <i class="fas fa-info-circle text-white"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800">Info Ukuran Kertas</h3>
                </div>

                <div class="space-y-4">
                    <div class="p-4 bg-teal-50 rounded-lg">
                        <h4 class="text-sm font-semibold text-teal-800 uppercase tracking-wider mb-2">Petunjuk</h4>
                        <ul class="list-disc pl-5 text-sm text-gray-600 space-y-1">
                            <li>Ukuran kertas dapat dimasukkan dalam mm atau inch</li>
                            <li>Konversi antara mm dan inch otomatis</li>
                            <li>Gunakan table untuk memilih ukuran standar</li>
                            <li>Perubahan apa pun harus disimpan</li>
                        </ul>
                    </div>

                    <div class="p-4 bg-blue-50 rounded-lg">
                        <h4 class="text-sm font-semibold text-blue-800 uppercase tracking-wider mb-2">Standar Ukuran</h4>
                        <div class="grid grid-cols-2 gap-2 text-sm">
                            <div class="flex items-center">
                                <span class="w-6 h-6 flex items-center justify-center bg-blue-500 text-white rounded-full font-bold mr-2">A4</span>
                                <span>210 x 297 mm</span>
                            </div>
                            <div class="flex items-center">
                                <span class="w-6 h-6 flex items-center justify-center bg-blue-500 text-white rounded-full font-bold mr-2">A3</span>
                                <span>297 x 420 mm</span>
                            </div>
                            <div class="flex items-center">
                                <span class="w-6 h-6 flex items-center justify-center bg-blue-500 text-white rounded-full font-bold mr-2">A5</span>
                                <span>148 x 210 mm</span>
                            </div>
                            <div class="flex items-center">
                                <span class="w-6 h-6 flex items-center justify-center bg-blue-500 text-white rounded-full font-bold mr-2">LTR</span>
                                <span>216 x 279 mm</span>
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
                    <a href="#" class="flex items-center p-3 bg-purple-50 rounded-lg hover:bg-purple-100 transition-colors">
                        <div class="p-2 bg-purple-500 rounded-full mr-3">
                            <i class="fas fa-print text-white text-sm"></i>
                        </div>
                        <div>
                            <p class="font-medium text-purple-900">Print Settings</p>
                            <p class="text-xs text-purple-700">Konfigurasi printer</p>
                        </div>
                    </a>

                    <a href="#" class="flex items-center p-3 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                        <div class="p-2 bg-blue-500 rounded-full mr-3">
                            <i class="fas fa-th-list text-white text-sm"></i>
                        </div>
                        <div>
                            <p class="font-medium text-blue-900">Paper Types</p>
                            <p class="text-xs text-blue-700">Lihat tipe kertas</p>
                        </div>
                    </a>

                    <a href="#" class="flex items-center p-3 bg-green-50 rounded-lg hover:bg-green-100 transition-colors">
                        <div class="p-2 bg-green-500 rounded-full mr-3">
                            <i class="fas fa-file-export text-white text-sm"></i>
                        </div>
                        <div>
                            <p class="font-medium text-green-900">Export Daftar</p>
                            <p class="text-xs text-green-700">Export data ukuran kertas</p>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Paper Size Table Modal -->
<div id="paperSizeTableWindow" class="hidden fixed inset-0 z-50 bg-black bg-opacity-50 flex items-center justify-center">
    <div class="relative bg-white rounded-lg max-w-3xl w-full mx-auto shadow-xl">
                            <!-- Header -->
        <div class="flex items-center justify-between p-4 border-b border-gray-200 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-t-lg">
            <div class="flex items-center">
                <div class="p-2 bg-white bg-opacity-30 rounded-lg mr-3">
                    <i class="fas fa-ruler-combined"></i>
                </div>
                <h3 class="text-xl font-semibold">Paper Size Table</h3>
            </div>
            <button type="button" onclick="closePaperSizeModal()" class="text-white hover:text-gray-200 focus:outline-none transform active:translate-y-px">
                <i class="fas fa-times text-xl"></i>
                                </button>
                            </div>
                            
                            <!-- Content -->
                            <div class="py-4 px-6">
            <div class="mb-4">
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
                        <i class="fas fa-search"></i>
                    </span>
                    <input type="text" id="searchPaperSizeInput" placeholder="Search sizes..."
                        class="pl-10 pr-4 py-2 w-full border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 bg-gray-50">
                </div>
            </div>
            
                                <div class="overflow-y-auto max-h-80">
                <table class="min-w-full divide-y divide-gray-200" id="paperSizeDataTable">
                                        <thead class="bg-gray-50 sticky top-0">
                                            <tr>
                                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Size ID
                                                </th>
                                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Millimeters
                                                </th>
                                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Inches
                                                </th>
                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Description
                                                </th>
                                                <th class="px-3 py-2 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Action
                                                </th>
                                            </tr>
                                        </thead>
                    <tbody class="bg-white divide-y divide-gray-200" id="paperSizeTableBody">
                        @if(isset($paperSizes) && count($paperSizes) > 0)
                                            @foreach($paperSizes as $index => $size)
                                <tr class="hover:bg-blue-50 cursor-pointer" 
                                    data-size-id="{{ $size->id }}"
                                    data-size-mm="{{ $size->size }}"
                                    data-size-inch="{{ $size->inches }}"
                                    data-description="{{ $size->description ?? '' }}"
                                    onclick="selectRow(this); event.stopPropagation();"
                                    ondblclick="openEditPaperSizeModal(this)">
                                    <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900">
                                        PS{{ sprintf('%03d', $size->id) }}
                                                    </td>
                                                    <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900">
                                                        {{ number_format($size->size, 2) }}
                                                    </td>
                                                    <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-500">
                                                        {{ number_format($size->inches, 2) }}
                                                    </td>
                                    <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-700">
                                        {{ $size->description ?? '' }}
                                                    </td>
                                                    <td class="px-3 py-2 whitespace-nowrap text-sm text-center">
                                                        <button type="button" 
                                                onclick="openEditPaperSizeModal(this.closest('tr'))" 
                                                                class="inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs font-medium rounded text-blue-700 bg-blue-100 hover:bg-blue-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                            Select
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endforeach
                        @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            
                            <!-- Footer -->
                            <div class="px-4 py-3 bg-gray-50 sm:px-6 sm:flex sm:flex-row-reverse">
            <button type="button" onclick="sortTableDirectly(1)" class="py-2 px-3 bg-gray-100 border border-gray-400 hover:bg-gray-200 text-xs rounded-lg transform active:translate-y-px mr-2">
                <i class="fas fa-sort mr-1"></i>By Size
            </button>
            <button type="button" onclick="sortTableDirectly(3)" class="py-2 px-3 bg-gray-100 border border-gray-400 hover:bg-gray-200 text-xs rounded-lg transform active:translate-y-px mr-2">
                <i class="fas fa-sort mr-1"></i>By Description
            </button>
            <button type="button" onclick="closePaperSizeModal()" 
                    class="mt-3 inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 text-xs transform active:translate-y-px">
                <i class="fas fa-times mr-1"></i>Close
                                </button>
                            </div>
                        </div>
                    </div>

<!-- Edit Modal -->
<div id="editPaperSizeModal" class="hidden fixed inset-0 z-50 bg-black bg-opacity-50 flex items-center justify-center">
    <!-- Modal Container -->
    <div class="relative bg-white rounded-lg shadow-xl w-11/12 md:w-2/5 max-w-md mx-auto">
        <!-- Header -->
        <div class="flex items-center justify-between p-4 border-b border-gray-200 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-t-lg">
            <div class="flex items-center">
                <div class="p-2 bg-white bg-opacity-30 rounded-lg mr-3">
                    <i class="fas fa-ruler-combined"></i>
                </div>
                <h3 class="text-xl font-semibold">Define Paper Size</h3>
            </div>
            <button type="button" onclick="closeEditPaperSizeModal()" class="text-white hover:text-gray-200 focus:outline-none transform active:translate-y-px">
                <i class="fas fa-times text-xl"></i>
                                </button>
                            </div>
                            
        <!-- Form Fields -->
        <div class="p-6 space-y-4">
                                <!-- Input Millimeter -->
            <div>
                <label for="edit_size_mm" class="block text-sm font-medium text-gray-700 mb-1">Size (mm):</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
                        <i class="fas fa-ruler"></i>
                    </span>
                    <input type="number" step="0.01" id="edit_size_mm" 
                        class="pl-10 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm">
                </div>
                                </div>
                                
                                <!-- Input Inches -->
            <div>
                <label for="edit_size_inch" class="block text-sm font-medium text-gray-700 mb-1">Size (inches):</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
                        <i class="fas fa-ruler-horizontal"></i>
                    </span>
                    <input type="number" step="0.01" id="edit_size_inch"
                        class="pl-10 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm">
                                </div>
                            </div>
                            
            <!-- Description -->
            <div>
                <label for="edit_description" class="block text-sm font-medium text-gray-700 mb-1">Description:</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
                        <i class="fas fa-font"></i>
                    </span>
                    <input type="text" id="edit_description" class="pl-10 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm" placeholder="e.g., A4, Letter, Custom">
                </div>
            </div>

            <!-- ID Hidden Field -->
            <input type="hidden" id="edit_size_id">
        </div>
        
        <!-- Footer -->
        <div class="px-4 py-3 bg-gray-50 sm:px-6 sm:flex sm:flex-row-reverse rounded-b-lg">
            <button type="button" onclick="savePaperSizeChanges()" 
                    class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-500 text-white text-sm font-medium hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transform active:translate-y-px sm:ml-3 sm:w-auto">
                <i class="fas fa-save mr-2"></i>Save
            </button>
            <button type="button" onclick="closeEditPaperSizeModal()" 
                    class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-gray-200 text-gray-800 text-sm font-medium hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transform active:translate-y-px sm:mt-0 sm:ml-3 sm:w-auto">
                <i class="fas fa-times mr-2"></i>Cancel
                    </button>
        </div>
    </div>
</div>

<!-- Loading Overlay -->
<div id="loadingOverlay" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50 hidden">
    <div class="w-12 h-12 border-4 border-solid border-blue-500 border-t-transparent rounded-full animate-spin"></div>
</div>

@endsection