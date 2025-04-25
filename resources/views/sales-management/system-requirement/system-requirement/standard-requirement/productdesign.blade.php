@extends('layouts.app')

@section('title', 'Define Product Design')

@section('content')
<script src="{{ asset('js/productdesign.js') }}"></script>

<!-- Header Section -->
<div class="bg-gradient-to-r from-cyan-700 to-blue-600 p-6 rounded-t-lg shadow-lg">
    <h2 class="text-2xl font-bold text-white mb-2 flex items-center">
        <i class="fas fa-drafting-compass mr-3"></i> Define Product Design
    </h2>
    <p class="text-cyan-100">Definisikan desain produk yang akan digunakan dalam sistem</p>
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
                    <h3 class="text-xl font-semibold text-gray-800">Product Design Management</h3>
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

                <!-- Search Section -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-6">
                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Product Design#:</label>
                        <div class="relative flex">
                            <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500">
                                <i class="fas fa-drafting-compass"></i>
                            </span>
                            <input type="text" class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none border border-gray-300 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            <button type="button" id="showProductDesignTableBtn" class="inline-flex items-center px-3 py-2 border border-l-0 border-gray-300 bg-blue-500 hover:bg-blue-600 text-white rounded-r-md transition-colors transform active:translate-y-px">
                                <i class="fas fa-table"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="col-span-1">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Record:</label>
                        <button type="button" class="w-full flex items-center justify-center px-4 py-2 bg-indigo-500 hover:bg-indigo-600 text-white rounded transition-colors transform active:translate-y-px">
                            <i class="fas fa-list-ul mr-2"></i> Select Record
                        </button>
                    </div>
                </div>

                <!-- Additional Details -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Product Design Name:</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
                                <i class="fas fa-tag"></i>
                            </span>
                            <input type="text" class="pl-10 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm">
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Product Code:</label>
                        <div class="relative flex">
                            <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500">
                                <i class="fas fa-barcode"></i>
                            </span>
                            <input type="text" class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none border border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                            <button type="button" class="inline-flex items-center px-3 py-2 border border-l-0 border-gray-300 bg-blue-500 hover:bg-blue-600 text-white rounded-r-md transition-colors transform active:translate-y-px">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Dimension:</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
                                <i class="fas fa-ruler-combined"></i>
                            </span>
                            <input type="text" class="pl-10 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm">
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">IDC:</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
                                <i class="fas fa-hashtag"></i>
                            </span>
                            <input type="text" class="pl-10 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm">
                        </div>
                    </div>
                </div>

                <!-- Debug Information -->
                @if(empty($productDesigns) || count($productDesigns) === 0)
                <div class="mt-4 bg-yellow-100 p-3 rounded">
                    <p class="text-sm font-medium text-yellow-800">Tidak ada data desain produk yang tersedia.</p>
                    <p class="text-xs text-yellow-700 mt-1">Pastikan database telah diatur dengan benar dan data seeder telah dijalankan.</p>
                    <div class="mt-2 flex items-center space-x-3">
                        <button onclick="loadSeedData()" class="bg-green-500 hover:bg-green-600 text-white text-xs px-3 py-1 rounded transform active:translate-y-px">
                            Load Product Design Data
                        </button>
                    </div>
                </div>
                @elseif(count($productDesigns) > 0)
                <div class="mt-4 bg-green-100 p-3 rounded">
                    <p class="text-sm font-medium text-green-800">Data tersedia: {{ count($productDesigns) }} desain produk ditemukan.</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Right Column - Quick Info -->
        <div class="lg:col-span-1">
            <!-- Product Design Info Card -->
            <div class="bg-white p-6 rounded-lg shadow-md border-t-4 border-teal-500 mb-6">
                <div class="flex items-center mb-4 pb-2 border-b border-gray-200">
                    <div class="p-2 bg-teal-500 rounded-lg mr-3">
                        <i class="fas fa-info-circle text-white"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800">Info Desain Produk</h3>
                </div>

                <div class="space-y-4">
                    <div class="p-4 bg-teal-50 rounded-lg">
                        <h4 class="text-sm font-semibold text-teal-800 uppercase tracking-wider mb-2">Petunjuk</h4>
                        <ul class="list-disc pl-5 text-sm text-gray-600 space-y-1">
                            <li>Kode desain produk harus unik</li>
                            <li>Gunakan tombol <span class="font-medium">search</span> untuk memilih desain produk</li>
                            <li>Pastikan kode produk telah terdaftar</li>
                            <li>Perubahan apa pun harus disimpan</li>
                        </ul>
                    </div>

                    <div class="p-4 bg-blue-50 rounded-lg">
                        <h4 class="text-sm font-semibold text-blue-800 uppercase tracking-wider mb-2">Spesifikasi Desain</h4>
                        <div class="grid grid-cols-1 gap-2 text-sm">
                            <div class="flex items-center">
                                <span class="w-6 h-6 flex items-center justify-center bg-blue-500 text-white rounded-full font-bold mr-2">1</span>
                                <span>Dimensi dalam format PxLxT (mm)</span>
                            </div>
                            <div class="flex items-center">
                                <span class="w-6 h-6 flex items-center justify-center bg-blue-500 text-white rounded-full font-bold mr-2">2</span>
                                <span>Satu produk bisa memiliki beberapa desain</span>
                            </div>
                            <div class="flex items-center">
                                <span class="w-6 h-6 flex items-center justify-center bg-blue-500 text-white rounded-full font-bold mr-2">3</span>
                                <span>IDC adalah Internal Design Code</span>
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
                            <i class="fas fa-box text-white text-sm"></i>
                        </div>
                        <div>
                            <p class="font-medium text-purple-900">Products</p>
                            <p class="text-xs text-purple-700">Kelola produk</p>
                        </div>
                    </a>

                    <a href="#" class="flex items-center p-3 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                        <div class="p-2 bg-blue-500 rounded-full mr-3">
                            <i class="fas fa-layer-group text-white text-sm"></i>
                        </div>
                        <div>
                            <p class="font-medium text-blue-900">Product Groups</p>
                            <p class="text-xs text-blue-700">Lihat grup produk</p>
                        </div>
                    </a>

                    <a href="#" class="flex items-center p-3 bg-green-50 rounded-lg hover:bg-green-100 transition-colors">
                        <div class="p-2 bg-green-500 rounded-full mr-3">
                            <i class="fas fa-print text-white text-sm"></i>
                        </div>
                        <div>
                            <p class="font-medium text-green-900">Cetak Daftar</p>
                            <p class="text-xs text-green-700">Cetak daftar desain</p>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Product Design Table Window -->
<div id="productDesignTableWindow" class="hidden fixed inset-0 z-50 bg-black bg-opacity-50 flex items-center justify-center">
    <div class="bg-white rounded-lg shadow-xl w-11/12 md:w-3/4 lg:w-2/3 max-w-5xl mx-auto transform transition-transform duration-300" style="max-height: 80vh;">
        <!-- Modal Header - Title Bar -->
        <div class="flex items-center justify-between p-4 border-b border-gray-200 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-t-lg">
            <div class="flex items-center">
                <div class="p-2 bg-white bg-opacity-30 rounded-lg mr-3">
                    <i class="fas fa-drafting-compass"></i>
                </div>
                <h3 class="text-xl font-semibold">Product Design Table</h3>
            </div>
            <button type="button" onclick="closeModalX()" class="text-white hover:text-gray-200 focus:outline-none transform active:translate-y-px">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>

        <!-- Table Content -->
        <div class="p-5 overflow-auto" style="max-height: calc(80vh - 130px);">
            <div class="mb-4">
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
                        <i class="fas fa-search"></i>
                    </span>
                    <input type="text" id="searchProductDesignInput" placeholder="Search designs..."
                        class="pl-10 pr-4 py-2 w-full border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 bg-gray-50">
                </div>
            </div>
            
            <div class="overflow-x-auto rounded-lg border border-gray-200">
                <table class="min-w-full divide-y divide-gray-200" id="productDesignTable">
                    <thead class="bg-gray-50 sticky top-0">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">P/Design#</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">P/Design Name</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product Code</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dimension</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">IDC</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200 text-xs">
                        @if(isset($productDesigns) && count($productDesigns) > 0)
                        @foreach($productDesigns as $design)
                            <tr class="hover:bg-blue-50 cursor-pointer" 
                            data-pd-code="{{ $design->pd_code }}"
                            data-pd-name="{{ $design->pd_name }}"
                            data-product-code="{{ $design->product_code }}"
                            data-dimension="{{ $design->dimension ?? '' }}"
                            data-idc="{{ $design->idc }}"
                            onclick="selectRow(this); event.stopPropagation();"
                            ondblclick="selectDesign('{{ $design->pd_code }}', '{{ $design->pd_name }}')">
                                <td class="px-4 py-3 whitespace-nowrap font-medium text-gray-900">{{ $design->pd_code }}</td>
                                <td class="px-4 py-3 whitespace-nowrap text-gray-700">{{ $design->pd_name }}</td>
                                <td class="px-4 py-3 whitespace-nowrap text-gray-700">{{ $design->product_code }}</td>
                                <td class="px-4 py-3 whitespace-nowrap text-gray-700">{{ $design->dimension ?? '' }}</td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">{{ $design->idc }}</span>
                                </td>
                        </tr>
                        @endforeach
                        @else
                            <tr>
                                <td colspan="5" class="px-4 py-4 text-center text-gray-500">Tidak ada data desain produk yang tersedia.</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>

            <!-- Bottom Buttons -->
            <div class="mt-4 grid grid-cols-4 gap-2">
                <button type="button" onclick="sortTableDirectly(0)" class="py-2 px-3 bg-gray-100 border border-gray-400 hover:bg-gray-200 text-xs rounded-lg transform active:translate-y-px">
                    <i class="fas fa-sort mr-1"></i>By Design#
                </button>
                <button type="button" onclick="sortTableDirectly(2)" class="py-2 px-3 bg-gray-100 border border-gray-400 hover:bg-gray-200 text-xs rounded-lg transform active:translate-y-px">
                    <i class="fas fa-sort mr-1"></i>By Product
                </button>
                <button type="button" onclick="selectCurrentRow()" class="py-2 px-3 bg-blue-500 hover:bg-blue-600 text-white text-xs rounded-lg transform active:translate-y-px">
                    <i class="fas fa-edit mr-1"></i>Select
                </button>
                <button type="button" onclick="closeProductDesignModal()" class="py-2 px-3 bg-gray-300 hover:bg-gray-400 text-gray-800 text-xs rounded-lg transform active:translate-y-px">
                    <i class="fas fa-times mr-1"></i>Exit
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Product Design Modal -->
<div id="editProductDesignModal" class="hidden fixed inset-0 z-50 bg-black bg-opacity-50 flex items-center justify-center">
    <div class="bg-white rounded-lg shadow-xl w-11/12 md:w-2/5 max-w-md mx-auto transform transition-transform duration-300">
        <!-- Modal Header - Title Bar -->
        <div class="flex items-center justify-between p-4 border-b border-gray-200 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-t-lg">
            <div class="flex items-center">
                <div class="p-2 bg-white bg-opacity-30 rounded-lg mr-3">
                    <i class="fas fa-drafting-compass"></i>
                </div>
                <h3 class="text-xl font-semibold">Define Product Design</h3>
            </div>
            <button type="button" onclick="closeEditModal()" class="text-white hover:text-gray-200 focus:outline-none transform active:translate-y-px">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>

        <!-- Form Content -->
        <div class="p-6">
            <form id="editProductDesignForm" onsubmit="saveProductDesignChanges(); return false;" class="space-y-4">
                <div class="grid grid-cols-1 gap-4">
                    <div>
                        <label for="edit_pd_code" class="block text-sm font-medium text-gray-700 mb-1">Design Code:</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
                                <i class="fas fa-hashtag"></i>
                            </span>
                            <input id="edit_pd_code" type="text" class="pl-10 block w-full rounded-md border-gray-300 bg-gray-100 shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm" readonly>
                        </div>
                    </div>
                    
                    <div>
                        <label for="edit_pd_name" class="block text-sm font-medium text-gray-700 mb-1">Design Name:</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
                                <i class="fas fa-font"></i>
                            </span>
                            <input id="edit_pd_name" type="text" class="pl-10 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm" required>
                        </div>
                    </div>
                    
                    <div>
                        <label for="edit_product_code" class="block text-sm font-medium text-gray-700 mb-1">Product Code:</label>
                        <div class="relative flex">
                            <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500">
                                <i class="fas fa-barcode"></i>
                            </span>
                            <input id="edit_product_code" type="text" class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none border border-gray-300 focus:ring-blue-500 focus:border-blue-500 text-sm" required>
                            <button class="inline-flex items-center px-3 py-2 border border-l-0 border-gray-300 bg-blue-500 hover:bg-blue-600 text-white rounded-r-md transition-colors transform active:translate-y-px">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div>
                        <label for="edit_dimension" class="block text-sm font-medium text-gray-700 mb-1">Dimension:</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
                                <i class="fas fa-ruler-combined"></i>
                            </span>
                            <input id="edit_dimension" type="text" class="pl-10 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm">
                        </div>
                    </div>
                    
                    <div>
                        <label for="edit_idc" class="block text-sm font-medium text-gray-700 mb-1">IDC:</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
                                <i class="fas fa-hashtag"></i>
                            </span>
                            <input id="edit_idc" type="text" class="pl-10 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm">
                        </div>
                    </div>
                </div>
                
                <div class="flex justify-end space-x-3 mt-6 pt-4 border-t border-gray-200">
                    <button type="button" onclick="closeEditModal()" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition-colors text-sm transform active:translate-y-px">
                        <i class="fas fa-times mr-2"></i>Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors text-sm transform active:translate-y-px">
                        <i class="fas fa-save mr-2"></i>Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Loading Overlay -->
<div id="loadingOverlay" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50 hidden">
    <div class="w-12 h-12 border-4 border-solid border-blue-500 border-t-transparent rounded-full animate-spin"></div>
</div>

@endsection

@push('styles')
<style>
#productDesignTable tbody tr.selected {
    background-color: #2563eb;
    color: white;
}
</style>
@endpush

@push('scripts')
<script>
function loadSeedData() {
    // Show loading overlay
    document.getElementById('loadingOverlay').classList.remove('hidden');
    
    // Make AJAX request to load seed data
    fetch('/run-product-design-seeder', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            window.location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        alert('Error loading data: ' + error.message);
    })
    .finally(() => {
        // Hide loading overlay
        document.getElementById('loadingOverlay').classList.add('hidden');
    });
}
</script>
@endpush