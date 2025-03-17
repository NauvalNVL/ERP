@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Toolbar dengan tampilan ultra-modern -->
    <div class="bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-700 p-5 rounded-2xl shadow-2xl mb-8 border-b-4 border-indigo-800 transition-all duration-300 hover:shadow-blue-200/50 transform hover:-translate-y-1">
        <div class="flex items-center justify-between space-x-4">
            <div class="flex items-center space-x-3">
                <button id="newButton" type="button" class="p-3 bg-white/90 border-0 rounded-xl hover:bg-blue-50 transition-all duration-300 shadow hover:shadow-lg transform hover:-translate-y-1 hover:scale-105" title="Baru">
                    <i class="fas fa-file text-blue-600 text-xl"></i>
                </button>
                <button id="editButton" type="button" class="p-3 bg-white/90 border-0 rounded-xl hover:bg-blue-50 transition-all duration-300 shadow hover:shadow-lg transform hover:-translate-y-1 hover:scale-105" title="Edit">
                    <i class="fas fa-edit text-blue-600 text-xl"></i>
                </button>
                <button id="deleteButton" type="button" class="p-3 bg-white/90 border-0 rounded-xl hover:bg-red-50 transition-all duration-300 shadow hover:shadow-lg transform hover:-translate-y-1 hover:scale-105" title="Hapus">
                    <i class="fas fa-trash-alt text-red-600 text-xl"></i>
                </button>
                <div class="h-10 mx-4 border-r-2 border-white/50"></div>
                <button id="printButton" type="button" class="p-3 bg-white/90 border-0 rounded-xl hover:bg-blue-50 transition-all duration-300 shadow hover:shadow-lg transform hover:-translate-y-1 hover:scale-105" title="Cetak">
                    <i class="fas fa-print text-blue-600 text-xl"></i>
                </button>
                <button id="searchButton" type="button" class="p-3 bg-white/90 border-0 rounded-xl hover:bg-blue-50 transition-all duration-300 shadow hover:shadow-lg transform hover:-translate-y-1 hover:scale-105" title="Cari">
                    <i class="fas fa-search text-blue-600 text-xl"></i>
                </button>
            </div>
            <button id="refreshButton" type="button" class="p-3 bg-white/90 border-0 rounded-xl hover:bg-green-50 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-1 hover:scale-105" title="Refresh">
                <i class="fas fa-sync-alt text-green-600 text-xl animate-spin-slow"></i>
            </button>
        </div>
    </div>

    <!-- Header yang lebih menarik dan dinamis -->
    <div class="flex items-center mb-10">
        <div class="relative">
            <h1 class="text-4xl font-black text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-indigo-800">
                Define Paper Flute
            </h1>
            <span class="absolute -bottom-3 left-0 h-2 w-48 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-full shadow-md"></span>
            <span class="absolute -bottom-3 left-0 h-2 w-24 bg-gradient-to-r from-indigo-600 to-purple-600 rounded-full animate-pulse"></span>
        </div>
    </div>

    <!-- Card utama dengan desain ultra-modern -->
    <div class="bg-white/95 p-8 rounded-2xl shadow-2xl border-0 transition-all duration-300 hover:shadow-blue-100 backdrop-blur-sm">
        <!-- Form pencarian dengan tampilan yang lebih modern dan efek glassmorphism -->
        <div class="mb-8 flex items-center bg-gradient-to-r from-blue-50/90 to-indigo-50/90 p-6 rounded-2xl border-l-4 border-blue-500 shadow-lg hover:shadow-xl transition-all duration-300 backdrop-blur-md">
            <label for="paperFluteSearch" class="mr-4 w-32 font-bold text-gray-700">Paper Flute:</label>
            <input type="text" id="paperFluteSearch" class="border border-gray-300 p-3 rounded-xl w-96 focus:ring-3 focus:ring-blue-400/40 focus:border-blue-500 transition-all duration-300 shadow-inner" placeholder="Pilih paper flute..." readonly>
            <button id="openModalButton" type="button" class="ml-3 p-3 bg-gradient-to-r from-blue-600 to-indigo-700 text-white border-0 rounded-xl hover:from-blue-700 hover:to-indigo-800 transition-all duration-300 shadow-md hover:shadow-lg transform hover:-translate-y-1">
                <i class="fas fa-ellipsis-h"></i>
            </button>
        </div>

        <!-- Tabel dengan desain yang lebih modern dan animasi -->
        <div id="flutesTable" class="mt-10 hidden animate__animated animate__fadeIn">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-extrabold text-gray-800 flex items-center">
                    <i class="fas fa-table text-indigo-600 mr-3"></i>
                    Daftar Paper Flute
                </h2>
                <div class="bg-gradient-to-r from-blue-500 via-indigo-600 to-purple-600 text-white text-sm font-semibold px-5 py-2 rounded-full shadow-lg transform hover:scale-105 transition-all duration-300">
                    Data Terpilih
                </div>
            </div>
            <div class="overflow-hidden rounded-2xl border border-gray-200 shadow-2xl transition-all duration-300 hover:shadow-indigo-100/50">
                <table class="min-w-full bg-white">
                    <thead>
                        <tr class="bg-gradient-to-r from-gray-100 to-blue-50 text-gray-700">
                            <th class="py-4 px-6 border-b border-gray-200 text-left font-extrabold">Kode</th>
                            <th class="py-4 px-6 border-b border-gray-200 text-left font-extrabold">Nama</th>
                            <th class="py-4 px-6 border-b border-gray-200 text-left font-extrabold">Deskripsi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($paperFlutes as $flute)
                        <tr class="hover:bg-blue-50 border-b border-gray-100 transition-all duration-300 cursor-pointer">
                            <td class="py-4 px-6 font-medium text-blue-700">{{ $flute->code }}</td>
                            <td class="py-4 px-6">{{ $flute->name }}</td>
                            <td class="py-4 px-6 text-gray-600">{{ $flute->description }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Box untuk Paper Flute dengan desain yang lebih responsif -->
<div id="paperFluteModal" class="fixed inset-0 bg-gray-900/80 backdrop-blur-sm flex items-center justify-center hidden z-50 transition-all duration-300 animate__animated animate__fadeIn p-4">
    <div class="bg-white/95 rounded-2xl shadow-2xl p-4 sm:p-6 md:p-8 w-full max-w-3xl transform transition-all duration-300 animate__animated animate__zoomIn border-t-4 border-blue-600">
        <div class="flex justify-between items-center mb-4 sm:mb-6 border-b border-gray-200 pb-4">
            <h3 class="text-lg sm:text-xl font-bold text-gray-800 flex items-center">
                <i class="fas fa-layer-group text-blue-600 mr-2 sm:mr-3"></i>
                Pilih Paper Flute
            </h3>
            <button id="closeModalButton" class="text-gray-500 hover:text-red-500 hover:bg-red-50 rounded-full p-2 sm:p-3 transition-colors">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <div class="mb-4 sm:mb-6 relative">
            <input type="text" id="searchModalInput" placeholder="Cari paper flute..." class="w-full p-3 sm:p-4 pl-10 sm:pl-12 border border-gray-300 rounded-xl focus:ring-3 focus:ring-blue-500/40 focus:border-blue-500 transition-all duration-300 shadow-sm">
            <i class="fas fa-search absolute left-3 sm:left-4 top-3 sm:top-4 text-gray-400"></i>
        </div>
        
        <div class="max-h-60 sm:max-h-80 overflow-y-auto rounded-xl border border-gray-200 shadow-xl hover:shadow-blue-100/50 transition-all duration-300">
            <table class="min-w-full bg-white">
                <thead class="sticky top-0 bg-gradient-to-r from-blue-50 to-indigo-50 z-10 shadow-sm">
                    <tr>
                        <th class="py-3 sm:py-4 px-3 sm:px-6 border-b text-left font-bold text-gray-700 text-xs sm:text-sm">Kode</th>
                        <th class="py-3 sm:py-4 px-3 sm:px-6 border-b text-left font-bold text-gray-700 text-xs sm:text-sm">Nama</th>
                        <th class="py-3 sm:py-4 px-3 sm:px-6 border-b text-left font-bold text-gray-700 text-xs sm:text-sm hidden sm:table-cell">Deskripsi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($paperFlutes as $flute)
                    <tr class="hover:bg-blue-50 cursor-pointer flute-row border-b border-gray-100 transition-all duration-200" data-code="{{ $flute->code }}" data-name="{{ $flute->name }}">
                        <td class="py-2 sm:py-4 px-3 sm:px-6 font-medium text-blue-700 text-xs sm:text-sm">{{ $flute->code }}</td>
                        <td class="py-2 sm:py-4 px-3 sm:px-6 text-xs sm:text-sm">{{ $flute->name }}</td>
                        <td class="py-2 sm:py-4 px-3 sm:px-6 text-gray-600 text-xs sm:text-sm hidden sm:table-cell">{{ $flute->description }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <div class="flex flex-col sm:flex-row justify-end mt-4 sm:mt-6 space-y-3 sm:space-y-0 sm:space-x-4">
            <button id="cancelModalButton" class="w-full sm:w-auto px-4 sm:px-6 py-3 bg-gray-200 text-gray-800 rounded-xl hover:bg-gray-300 transition-all duration-300 font-medium shadow-sm hover:shadow transform hover:-translate-y-1 order-2 sm:order-1">
                <i class="fas fa-times-circle mr-2"></i>Batal
            </button>
            <button id="addNewFluteButton" class="w-full sm:w-auto px-4 sm:px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-700 text-white rounded-xl hover:from-blue-700 hover:to-indigo-800 transition-all duration-300 font-medium shadow-md hover:shadow-lg transform hover:-translate-y-1 order-1 sm:order-2">
                <i class="fas fa-plus-circle mr-2"></i>Tambah Baru
            </button>
        </div>
    </div>
</div>

<!-- Modal untuk Tambah/Edit Paper Flute dengan desain yang lebih responsif -->
<div id="editFluteModal" class="fixed inset-0 bg-gray-900/80 backdrop-blur-sm flex items-center justify-center hidden z-50 transition-all duration-300 animate__animated animate__fadeIn p-4">
    <div class="bg-white/95 rounded-2xl shadow-2xl p-4 sm:p-6 md:p-8 w-full max-w-xl transform transition-all duration-300 animate__animated animate__zoomIn border-t-4 border-blue-600">
        <div class="flex justify-between items-center mb-4 sm:mb-6 border-b border-gray-200 pb-4">
            <h3 class="text-lg sm:text-xl font-bold text-gray-800 flex items-center">
                <i class="fas fa-edit text-blue-600 mr-2 sm:mr-3"></i>
                <span id="modalTitle">Tambah Paper Flute Baru</span>
            </h3>
            <button id="closeEditModalButton" class="text-gray-500 hover:text-red-500 hover:bg-red-50 rounded-full p-2 sm:p-3 transition-colors">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <form id="fluteForm" method="POST" action="{{ route('paper-flute.store') }}" class="overflow-y-auto max-h-[70vh]">
            @csrf
            <input type="hidden" id="editFluteId" name="id">
            <input type="hidden" id="methodField" name="_method" value="POST">
            
            <div class="mb-4 sm:mb-6">
                <label for="fluteCode" class="block text-sm font-medium text-gray-700 mb-2">Kode Flute</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 sm:pl-4 pointer-events-none">
                        <i class="fas fa-barcode text-gray-400 text-sm sm:text-base"></i>
                    </div>
                    <input type="text" id="fluteCode" name="code" class="w-full pl-10 sm:pl-12 px-3 sm:px-4 py-3 sm:py-3.5 border border-gray-300 rounded-xl focus:outline-none focus:ring-3 focus:ring-blue-500/40 focus:border-blue-500 transition-all duration-300 shadow-sm text-sm sm:text-base" required>
                </div>
            </div>
            
            <div class="mb-4 sm:mb-6">
                <label for="fluteName" class="block text-sm font-medium text-gray-700 mb-2">Nama Flute</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 sm:pl-4 pointer-events-none">
                        <i class="fas fa-signature text-gray-400 text-sm sm:text-base"></i>
                    </div>
                    <input type="text" id="fluteName" name="name" class="w-full pl-10 sm:pl-12 px-3 sm:px-4 py-3 sm:py-3.5 border border-gray-300 rounded-xl focus:outline-none focus:ring-3 focus:ring-blue-500/40 focus:border-blue-500 transition-all duration-300 shadow-sm text-sm sm:text-base" required>
                </div>
            </div>
            
            <div class="mb-6 sm:mb-8">
                <label for="fluteDescription" class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                <div class="relative">
                    <div class="absolute top-3 sm:top-3.5 left-3 sm:left-4 pointer-events-none">
                        <i class="fas fa-align-left text-gray-400 text-sm sm:text-base"></i>
                    </div>
                    <textarea id="fluteDescription" name="description" rows="4" class="w-full pl-10 sm:pl-12 px-3 sm:px-4 py-3 sm:py-3.5 border border-gray-300 rounded-xl focus:outline-none focus:ring-3 focus:ring-blue-500/40 focus:border-blue-500 transition-all duration-300 shadow-sm text-sm sm:text-base"></textarea>
                </div>
            </div>
            
            <div class="flex flex-col sm:flex-row justify-end space-y-3 sm:space-y-0 sm:space-x-4">
                <button type="button" id="cancelEditButton" class="w-full sm:w-auto px-4 sm:px-6 py-3 bg-gray-200 text-gray-800 rounded-xl hover:bg-gray-300 transition-all duration-300 font-medium shadow-sm hover:shadow transform hover:-translate-y-1 order-2 sm:order-1">
                    <i class="fas fa-times-circle mr-2"></i>Batal
                </button>
                <button type="submit" class="w-full sm:w-auto px-4 sm:px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-700 text-white rounded-xl hover:from-blue-700 hover:to-indigo-800 transition-all duration-300 font-medium shadow-md hover:shadow-lg transform hover:-translate-y-1 order-1 sm:order-2">
                    <i class="fas fa-save mr-2"></i>Simpan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Konfirmasi Hapus dengan desain yang lebih responsif -->
<div id="deleteConfirmModal" class="fixed inset-0 bg-gray-900/80 backdrop-blur-sm flex items-center justify-center hidden z-50 transition-all duration-300 animate__animated animate__fadeIn p-4">
    <div class="bg-white/95 rounded-2xl shadow-2xl p-4 sm:p-6 md:p-8 w-full max-w-lg transform transition-all duration-300 animate__animated animate__zoomIn border-t-4 border-red-600">
        <div class="mb-4 sm:mb-6 pb-4">
            <div class="bg-red-50 rounded-xl p-4 sm:p-6 mb-4 sm:mb-6 border-l-4 border-red-500 shadow-md">
                <h3 class="text-lg sm:text-xl font-bold text-red-600 flex items-center">
                    <i class="fas fa-exclamation-triangle mr-2 sm:mr-3"></i>
                    Konfirmasi Hapus
                </h3>
                <p class="mt-3 text-gray-600 text-sm sm:text-base">Anda yakin ingin menghapus paper flute ini? Tindakan ini tidak dapat dibatalkan.</p>
            </div>
            <p id="deleteFluteName" class="font-semibold mt-4 p-3 sm:p-4 bg-gray-50 rounded-xl text-gray-800 border border-gray-200 shadow-sm text-sm sm:text-base"></p>
        </div>
        
        <form id="deleteForm" method="POST">
            @csrf
            @method('DELETE')
            
            <div class="flex flex-col sm:flex-row justify-end space-y-3 sm:space-y-0 sm:space-x-4">
                <button type="button" id="cancelDeleteButton" class="w-full sm:w-auto px-4 sm:px-6 py-3 bg-gray-200 text-gray-800 rounded-xl hover:bg-gray-300 transition-all duration-300 font-medium shadow-sm hover:shadow transform hover:-translate-y-1 order-2 sm:order-1">
                    <i class="fas fa-times-circle mr-2"></i>Batal
                </button>
                <button type="submit" class="w-full sm:w-auto px-4 sm:px-6 py-3 bg-gradient-to-r from-red-600 to-red-700 text-white rounded-xl hover:from-red-700 hover:to-red-800 transition-all duration-300 font-medium shadow-md hover:shadow-lg transform hover:-translate-y-1 order-1 sm:order-2">
                    <i class="fas fa-trash-alt mr-2"></i>Hapus Permanen
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Detail Paper Flute dengan desain yang lebih responsif -->
<div id="fluteDetailModal" class="fixed inset-0 bg-gray-900/80 backdrop-blur-sm flex items-center justify-center hidden z-50 transition-all duration-300 animate__animated animate__fadeIn p-4">
    <div class="bg-white/95 rounded-2xl shadow-2xl p-4 sm:p-6 md:p-8 w-full max-w-3xl transform transition-all duration-300 animate__animated animate__zoomIn border-t-4 border-blue-600">
        <div class="flex justify-between items-center mb-4 sm:mb-6 border-b border-gray-200 pb-4">
            <h3 class="text-lg sm:text-xl font-bold text-gray-800 flex items-center">
                <i class="fas fa-info-circle text-blue-600 mr-2 sm:mr-3"></i>
                Detail Paper Flute
            </h3>
            <button id="closeDetailModalButton" class="text-gray-500 hover:text-red-500 hover:bg-red-50 rounded-full p-2 sm:p-3 transition-colors">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <form id="fluteDetailForm" class="overflow-y-auto max-h-[70vh]">
            <div class="grid grid-cols-1 gap-4 mb-6 sm:mb-8 bg-gradient-to-r from-blue-50 to-indigo-50 p-4 sm:p-6 rounded-xl border border-blue-100 shadow-md">
                <div class="flex flex-col sm:flex-row sm:items-center">
                    <label for="detailPaperFlute" class="w-full sm:w-32 font-medium text-gray-700 mb-2 sm:mb-0">Paper Flute:</label>
                    <select id="detailPaperFlute" class="border border-gray-300 p-3 rounded-xl w-full focus:ring-3 focus:ring-blue-500/40 focus:border-blue-500 transition-all duration-300 shadow-sm text-sm sm:text-base">
                        <option value="All">All</option>
                        @foreach($paperFlutes as $flute)
                            <option value="{{ $flute->code }}">{{ $flute->code }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex flex-col sm:flex-row sm:items-center">
                    <label for="detailDescription" class="w-full sm:w-32 font-medium text-gray-700 mb-2 sm:mb-0">Description:</label>
                    <input type="text" id="detailDescription" class="border border-gray-300 p-3 rounded-xl w-full focus:ring-3 focus:ring-blue-500/40 focus:border-blue-500 transition-all duration-300 shadow-sm text-sm sm:text-base" value="All">
                </div>
            </div>
            
            <div class="border-t border-gray-200 pt-4 sm:pt-6 mb-6 sm:mb-8">
                <div class="mb-4 sm:mb-5 bg-gradient-to-r from-blue-50 to-indigo-50 p-3 sm:p-4 rounded-xl border-l-4 border-blue-500 shadow-md">
                    <label class="font-semibold text-gray-800 flex items-center text-sm sm:text-base">
                        <i class="fas fa-layer-group text-blue-600 mr-2"></i>
                        Take Up Ratio
                    </label>
                </div>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 sm:gap-5 mb-6 sm:mb-8">
                    <div class="flex items-center bg-white p-3 sm:p-5 rounded-xl border border-gray-200 shadow-md hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
                        <label for="layer1" class="w-16 sm:w-24 text-xs sm:text-sm font-medium">Layer 1:</label>
                        <input type="text" id="layer1" class="border border-gray-300 p-2 sm:p-3 rounded-xl w-full text-right focus:ring-3 focus:ring-blue-500/40 focus:border-blue-500 transition-all duration-300 shadow-sm text-xs sm:text-sm" value="1.00">
                        <span class="ml-2 sm:ml-3 text-blue-700 font-semibold bg-blue-50 py-1 px-2 sm:px-3 rounded-lg text-xs sm:text-sm">KL</span>
                    </div>
                    <div class="flex items-center bg-white p-3 sm:p-5 rounded-xl border border-gray-200 shadow-md hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
                        <label for="layer2" class="w-16 sm:w-24 text-xs sm:text-sm font-medium">Layer 2:</label>
                        <input type="text" id="layer2" class="border border-gray-300 p-2 sm:p-3 rounded-xl w-full text-right focus:ring-3 focus:ring-blue-500/40 focus:border-blue-500 transition-all duration-300 shadow-sm text-xs sm:text-sm" value="1.40">
                        <span class="ml-2 sm:ml-3 text-blue-700 font-semibold bg-blue-50 py-1 px-2 sm:px-3 rounded-lg text-xs sm:text-sm">B</span>
                    </div>
                    <div class="flex items-center bg-white p-3 sm:p-5 rounded-xl border border-gray-200 shadow-md hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
                        <label for="layer3" class="w-16 sm:w-24 text-xs sm:text-sm font-medium">Layer 3:</label>
                        <input type="text" id="layer3" class="border border-gray-300 p-2 sm:p-3 rounded-xl w-full text-right focus:ring-3 focus:ring-blue-500/40 focus:border-blue-500 transition-all duration-300 shadow-sm text-xs sm:text-sm" value="1.00">
                        <span class="ml-2 sm:ml-3 text-blue-700 font-semibold bg-blue-50 py-1 px-2 sm:px-3 rounded-lg text-xs sm:text-sm">L</span>
                    </div>
                    <div class="flex items-center bg-white p-3 sm:p-5 rounded-xl border border-gray-200 shadow-md hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
                        <label for="layer4" class="w-16 sm:w-24 text-xs sm:text-sm font-medium">Layer 4:</label>
                        <input type="text" id="layer4" class="border border-gray-300 p-2 sm:p-3 rounded-xl w-full text-right focus:ring-3 focus:ring-blue-500/40 focus:border-blue-500 transition-all duration-300 shadow-sm text-xs sm:text-sm" value="1.60">
                        <span class="ml-2 sm:ml-3 text-blue-700 font-semibold bg-blue-50 py-1 px-2 sm:px-3 rounded-lg text-xs sm:text-sm">A/C/E</span>
                    </div>
                    <div class="flex items-center bg-white p-3 sm:p-5 rounded-xl border border-gray-200 shadow-md hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
                        <label for="layer5" class="w-16 sm:w-24 text-xs sm:text-sm font-medium">Layer 5:</label>
                        <input type="text" id="layer5" class="border border-gray-300 p-2 sm:p-3 rounded-xl w-full text-right focus:ring-3 focus:ring-blue-500/40 focus:border-blue-500 transition-all duration-300 shadow-sm text-xs sm:text-sm" value="1.00">
                        <span class="ml-2 sm:ml-3 text-blue-700 font-semibold bg-blue-50 py-1 px-2 sm:px-3 rounded-lg text-xs sm:text-sm">2L</span>
                    </div>
                </div>
            </div>
            
            <div class="border-t border-gray-200 pt-4 sm:pt-6 mb-6 sm:mb-8">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-5 mb-6">
                    <div class="flex flex-col sm:flex-row sm:items-center bg-white p-4 sm:p-5 rounded-xl border border-gray-200 shadow-md hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
                        <label for="fluteHeight" class="w-full sm:w-32 text-xs sm:text-sm font-medium text-gray-700 mb-2 sm:mb-0">Flute Height:</label>
                        <div class="flex items-center w-full">
                            <input type="text" id="fluteHeight" class="border border-gray-300 p-2 sm:p-3 rounded-xl w-full text-right focus:ring-3 focus:ring-blue-500/40 focus:border-blue-500 transition-all duration-300 shadow-sm text-xs sm:text-sm" value="0.00">
                            <span class="ml-2 sm:ml-3 text-gray-600 bg-gray-50 py-1 px-2 sm:px-3 rounded-lg text-xs sm:text-sm whitespace-nowrap">Millimeter</span>
                        </div>
                    </div>
                    <div class="flex flex-col sm:flex-row sm:items-center bg-white p-4 sm:p-5 rounded-xl border border-gray-200 shadow-md hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
                        <label for="starchConsumption" class="w-full sm:w-32 text-xs sm:text-sm font-medium text-gray-700 mb-2 sm:mb-0">Starch Consumption:</label>
                        <div class="flex items-center w-full">
                            <input type="text" id="starchConsumption" class="border border-gray-300 p-2 sm:p-3 rounded-xl w-full text-right focus:ring-3 focus:ring-blue-500/40 focus:border-blue-500 transition-all duration-300 shadow-sm text-xs sm:text-sm" value="0.00">
                            <span class="ml-2 sm:ml-3 text-gray-600 bg-gray-50 py-1 px-2 sm:px-3 rounded-lg text-xs sm:text-sm whitespace-nowrap">Factor</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="border-t border-gray-200 pt-4 sm:pt-6 mb-6 sm:mb-8">
                <div class="bg-gradient-to-r from-yellow-50 to-amber-50 p-4 sm:p-5 rounded-xl border-l-4 border-yellow-400 shadow-md">
                    <label class="font-semibold text-gray-800 flex items-center text-sm sm:text-base">
                        <i class="fas fa-sticky-note text-yellow-600 mr-2"></i>
                        Catatan:
                    </label>
                    <p class="text-xs sm:text-sm text-gray-600 mt-2 sm:mt-3">Starch Consumption (G/M²) = Area(M²) × Factor</p>
                </div>
            </div>
            
            <div class="flex flex-col sm:flex-row justify-end space-y-3 sm:space-y-0 sm:space-x-4 mt-6 sm:mt-8">
                <button type="button" id="cancelDetailButton" class="w-full sm:w-auto px-4 sm:px-6 py-3 bg-gray-200 text-gray-800 rounded-xl hover:bg-gray-300 transition-all duration-300 font-medium shadow-sm hover:shadow transform hover:-translate-y-1 order-2 sm:order-1">
                    <i class="fas fa-times-circle mr-2"></i>Tutup
                </button>
                <button type="button" id="saveDetailButton" class="w-full sm:w-auto px-4 sm:px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-700 text-white rounded-xl hover:from-blue-700 hover:to-indigo-800 transition-all duration-300 font-medium shadow-md hover:shadow-lg transform hover:-translate-y-1 order-1 sm:order-2">
                    <i class="fas fa-save mr-2"></i>Simpan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Tambahkan CSS untuk animasi dan efek-efek modern -->
<style>
    .animate-spin-slow {
        animation: spin 3s linear infinite;
    }
    
    @keyframes spin {
        from {
            transform: rotate(0deg);
        }
        to {
            transform: rotate(360deg);
        }
    }
    
    /* Animasi untuk elemen-elemen UI */
    .animate__animated {
        animation-duration: 0.5s;
    }
    
    .animate__fadeIn {
        animation-name: fadeIn;
    }
    
    .animate__zoomIn {
        animation-name: zoomIn;
    }
    
    @keyframes fadeIn {
        from {
            opacity: 0;
        }
        to {
            opacity: 1;
        }
    }
    
    @keyframes zoomIn {
        from {
            opacity: 0;
            transform: scale3d(0.3, 0.3, 0.3);
        }
        50% {
            opacity: 1;
        }
    }
    
    /* Efek glassmorphism untuk modal dan card */
    .modal-glass {
        backdrop-filter: blur(12px);
        background: rgba(255, 255, 255, 0.7);
    }
    
    /* Perubahan warna gradien saat hover */
    .gradient-hover {
        background-size: 200% 200%;
        transition: background-position 0.5s;
    }
    
    .gradient-hover:hover {
        background-position: right center;
    }
    
    /* Efek glassmorphism yang lebih kuat untuk modal */
    .bg-white\/95 {
        backdrop-filter: blur(12px);
    }
    
    /* Animasi hover yang lebih halus untuk tombol */
    button {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    /* Efek bayangan yang lebih baik untuk kartu */
    .shadow-modal {
        box-shadow: 0 10px 25px -5px rgba(59, 130, 246, 0.1), 0 10px 10px -5px rgba(59, 130, 246, 0.04);
    }
    
    /* Tambahan CSS untuk responsivitas */
    @media (max-width: 640px) {
        .modal-content {
            width: 95%;
        }
        
        .modal-header h3 {
            font-size: 1.1rem;
        }
        
        .table-responsive {
            display: block;
            width: 100%;
            overflow-x: auto;
        }
    }
    
    /* Memastikan modal tidak terlalu tinggi pada layar kecil */
    .max-h-modal {
        max-height: 90vh;
    }
    
    /* Styling untuk form lebih responsif */
    .form-group-responsive {
        display: flex;
        flex-direction: column;
    }
    
    @media (min-width: 640px) {
        .form-group-responsive {
            flex-direction: row;
            align-items: center;
        }
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Variabel untuk menyimpan data flute yang dipilih
    let selectedFlute = null;
    
    // Tombol dan elemen untuk modal utama
    const openModalButton = document.getElementById('openModalButton');
    const paperFluteModal = document.getElementById('paperFluteModal');
    const closeModalButton = document.getElementById('closeModalButton');
    const cancelModalButton = document.getElementById('cancelModalButton');
    const paperFluteSearch = document.getElementById('paperFluteSearch');
    const searchModalInput = document.getElementById('searchModalInput');
    const fluteRows = document.querySelectorAll('.flute-row');
    
    // Tombol toolbar
    const newButton = document.getElementById('newButton');
    const editButton = document.getElementById('editButton');
    const deleteButton = document.getElementById('deleteButton');
    const printButton = document.getElementById('printButton');
    const searchButton = document.getElementById('searchButton');
    const refreshButton = document.getElementById('refreshButton');
    
    // Elemen untuk modal edit
    const addNewFluteButton = document.getElementById('addNewFluteButton');
    const editFluteModal = document.getElementById('editFluteModal');
    const closeEditModalButton = document.getElementById('closeEditModalButton');
    const cancelEditButton = document.getElementById('cancelEditButton');
    const fluteForm = document.getElementById('fluteForm');
    const modalTitle = document.getElementById('modalTitle');
    const editFluteId = document.getElementById('editFluteId');
    const methodField = document.getElementById('methodField');
    const fluteCode = document.getElementById('fluteCode');
    const fluteName = document.getElementById('fluteName');
    const fluteDescription = document.getElementById('fluteDescription');
    
    // Elemen untuk modal hapus
    const deleteConfirmModal = document.getElementById('deleteConfirmModal');
    const cancelDeleteButton = document.getElementById('cancelDeleteButton');
    const deleteForm = document.getElementById('deleteForm');
    const deleteFluteName = document.getElementById('deleteFluteName');
    
    // Tabel flutes
    const flutesTable = document.getElementById('flutesTable');
    
    // Tambahan untuk modal detail flute
    const fluteDetailModal = document.getElementById('fluteDetailModal');
    const closeDetailModalButton = document.getElementById('closeDetailModalButton');
    const cancelDetailButton = document.getElementById('cancelDetailButton');
    const saveDetailButton = document.getElementById('saveDetailButton');
    const detailPaperFlute = document.getElementById('detailPaperFlute');
    const detailDescription = document.getElementById('detailDescription');
    
    // Buka modal utama
    openModalButton.addEventListener('click', function() {
        paperFluteModal.classList.remove('hidden');
        searchModalInput.focus();
    });
    
    // Tutup modal utama
    function closeMainModal() {
        paperFluteModal.classList.add('hidden');
        searchModalInput.value = '';
        resetModalSearch();
    }
    
    closeModalButton.addEventListener('click', closeMainModal);
    cancelModalButton.addEventListener('click', closeMainModal);
    
    // Pencarian dalam modal
    searchModalInput.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        fluteRows.forEach(row => {
            const code = row.querySelector('td:first-child').textContent.toLowerCase();
            const name = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
            const description = row.querySelector('td:last-child').textContent.toLowerCase();
            
            if (code.includes(searchTerm) || name.includes(searchTerm) || description.includes(searchTerm)) {
                row.classList.remove('hidden');
            } else {
                row.classList.add('hidden');
            }
        });
    });
    
    // Reset pencarian modal
    function resetModalSearch() {
        fluteRows.forEach(row => {
            row.classList.remove('hidden');
        });
    }
    
    // Pilih flute dari modal
    fluteRows.forEach(row => {
        row.addEventListener('click', function() {
            const code = this.getAttribute('data-code');
            const name = this.getAttribute('data-name');
            
            paperFluteSearch.value = `${code} - ${name}`;
            selectedFlute = {
                id: this.getAttribute('data-id'),
                code: code,
                name: name
            };
            
            closeMainModal();
            flutesTable.classList.remove('hidden');
            
            // Tampilkan modal detail setelah memilih flute
            showFluteDetail(code, name);
        });
    });
    
    // Tambah flute baru
    function openNewFluteModal() {
        modalTitle.textContent = 'Tambah Paper Flute Baru';
        fluteForm.action = "{{ route('paper-flute.store') }}";
        methodField.value = 'POST';
        editFluteId.value = '';
        fluteCode.value = '';
        fluteName.value = '';
        fluteDescription.value = '';
        fluteCode.readOnly = false;
        
        editFluteModal.classList.remove('hidden');
        fluteCode.focus();
    }
    
    newButton.addEventListener('click', openNewFluteModal);
    addNewFluteButton.addEventListener('click', function() {
        closeMainModal();
        openNewFluteModal();
    });
    
    // Edit flute
    editButton.addEventListener('click', function() {
        if (!selectedFlute) {
            alert('Pilih paper flute terlebih dahulu.');
            return;
        }
        
        modalTitle.textContent = 'Edit Paper Flute';
        fluteForm.action = `/paper-flute/${selectedFlute.id}`;
        methodField.value = 'PUT';
        editFluteId.value = selectedFlute.id;
        fluteCode.value = selectedFlute.code;
        fluteName.value = selectedFlute.name;
        // Deskripsi perlu diambil dari data baris yang dipilih
        const selectedRow = document.querySelector(`.flute-row[data-code="${selectedFlute.code}"]`);
        fluteDescription.value = selectedRow.querySelector('td:last-child').textContent;
        fluteCode.readOnly = true;
        
        editFluteModal.classList.remove('hidden');
        fluteName.focus();
    });
    
    // Tutup modal edit
    function closeEditModal() {
        editFluteModal.classList.add('hidden');
    }
    
    closeEditModalButton.addEventListener('click', closeEditModal);
    cancelEditButton.addEventListener('click', closeEditModal);
    
    // Hapus flute
    deleteButton.addEventListener('click', function() {
        if (!selectedFlute) {
            alert('Pilih paper flute terlebih dahulu.');
            return;
        }
        
        deleteForm.action = `/paper-flute/${selectedFlute.id}`;
        deleteFluteName.textContent = `${selectedFlute.code} - ${selectedFlute.name}`;
        
        deleteConfirmModal.classList.remove('hidden');
    });
    
    // Tutup modal hapus
    function closeDeleteModal() {
        deleteConfirmModal.classList.add('hidden');
    }
    
    cancelDeleteButton.addEventListener('click', closeDeleteModal);
    
    // Tombol Refresh
    refreshButton.addEventListener('click', function() {
        location.reload();
    });
    
    // Tombol Search
    searchButton.addEventListener('click', function() {
        openModalButton.click();
    });
    
    // Tombol Print
    printButton.addEventListener('click', function() {
        if (flutesTable.classList.contains('hidden')) {
            alert('Tampilkan data paper flute terlebih dahulu.');
            return;
        }
        window.print();
    });

    // Pilih flute dari modal dan tampilkan detail
    function showFluteDetail(code, name) {
        // Set nilai pada form detail
        detailPaperFlute.value = code;
        detailDescription.value = name;
        
        // Tampilkan modal detail
        fluteDetailModal.classList.remove('hidden');
    }

    // Tutup modal detail
    function closeDetailModal() {
        fluteDetailModal.classList.add('hidden');
    }

    closeDetailModalButton.addEventListener('click', closeDetailModal);
    cancelDetailButton.addEventListener('click', closeDetailModal);

    // Simpan detail flute
    saveDetailButton.addEventListener('click', function() {
        // Implementasi penyimpanan data detail flute
        // Bisa menggunakan AJAX untuk menyimpan ke server
        alert('Data detail flute telah disimpan');
        closeDetailModal();
    });
});
</script>
@endsection 