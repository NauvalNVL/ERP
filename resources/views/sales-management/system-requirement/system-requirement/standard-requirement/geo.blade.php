@extends('layouts.app')

@section('title', 'Define Geo')

@section('content')
<div class="container mx-auto px-4 py-6">
    <script src="{{ asset('js/geo.js') }}"></script>

    <!-- Header Section -->
    <div class="bg-gradient-to-r from-cyan-700 to-blue-600 p-6 rounded-t-lg shadow-lg">
        <h2 class="text-2xl font-bold text-white mb-2 flex items-center">
            <i class="fas fa-globe-americas mr-3"></i> Define Geo
        </h2>
        <p class="text-cyan-100">Definisikan data geografis untuk lokasi dan area yang spesifik</p>
    </div>

    <div class="bg-white rounded-b-lg shadow-lg p-6 mb-6">
        <!-- Main Content -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column - Main Content -->
            <div class="lg:col-span-2">
                <div class="bg-white p-6 rounded-lg shadow-md border-t-4 border-blue-500">
                    <div class="flex items-center mb-6 pb-2 border-b border-gray-200">
                        <div class="p-2 bg-blue-500 rounded-lg mr-3">
                            <i class="fas fa-map-marker-alt text-white"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-800">Geo Management</h3>
                    </div>

                    <!-- Header with navigation buttons -->
                    <div class="flex items-center space-x-2 mb-6">
                        <button type="button"
                            class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded flex items-center space-x-2 transform active:translate-y-px">
                            <i class="fas fa-power-off"></i>
                        </button>
                        <button type="button"
                            class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded flex items-center space-x-2 transform active:translate-y-px">
                            <i class="fas fa-arrow-right"></i>
                        </button>
                        <button type="button"
                            class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded flex items-center space-x-2 transform active:translate-y-px">
                            <i class="fas fa-arrow-left"></i>
                        </button>
                        <button type="button"
                            class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded flex items-center space-x-2 transform active:translate-y-px">
                            <i class="fas fa-search"></i>
                        </button>
                        <button type="button"
                            class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded flex items-center space-x-2 transform active:translate-y-px">
                            <i class="fas fa-save"></i>
                        </button>
                    </div>

                    <!-- Search Section -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-6">
                        <div class="col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Geo Code:</label>
                            <div class="relative flex">
                                <span
                                    class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500">
                                    <i class="fas fa-globe"></i>
                                </span>
                                <input type="text" id="geoCode"
                                    class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none border border-gray-300 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                    placeholder="Masukkan Kode Geo" onkeyup="searchGeoCode(this.value)">
                                <button type="button" id="showGeoTableBtn"
                                    class="inline-flex items-center px-3 py-2 border border-l-0 border-gray-300 bg-blue-500 hover:bg-blue-600 text-white rounded-r-md transition-colors transform active:translate-y-px"
                                    onclick="toggleModal('geoModal')">
                                    <i class="fas fa-table"></i>
                                </button>
                            </div>
                        </div>

                        <div class="col-span-1">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Record:</label>
                            <button type="button"
                                class="w-full flex items-center justify-center px-4 py-2 bg-indigo-500 hover:bg-indigo-600 text-white rounded transition-colors transform active:translate-y-px">
                                <i class="fas fa-list-ul mr-2"></i> Select Record
                            </button>
                        </div>
                    </div>

                    <!-- Debug Information -->
                    <div id="geoCodeResult" class="mt-4"></div>
                    @if (empty($geoData) || count($geoData) === 0)
                        <div class="mt-4 bg-yellow-100 p-3 rounded">
                            <p class="text-sm font-medium text-yellow-800">Tidak ada data geo yang tersedia.</p>
                            <p class="text-xs text-yellow-700 mt-1">Pastikan database telah diatur dengan benar dan data
                                seeder telah dijalankan.</p>
                            <div class="mt-2 flex items-center space-x-3">
                                <a href="{{ route('geo.index') }}"
                                    class="bg-blue-500 hover:bg-blue-600 text-white text-xs px-3 py-1 rounded transform active:translate-y-px">
                                    Run Geo Seeder (DB)
                                </a>
                                <button onclick="loadSeedData()"
                                    class="bg-green-500 hover:bg-green-600 text-white text-xs px-3 py-1 rounded transform active:translate-y-px">
                                    Load Geo Seeder Data (JS)
                                </button>
                            </div>
                        </div>
                    @elseif(count($geoData) > 0)
                        <div class="mt-4 bg-green-100 p-3 rounded">
                            <p class="text-sm font-medium text-green-800">Data tersedia: {{ count($geoData) }} geo
                                ditemukan.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Right Column - Quick Info -->
            <div class="lg:col-span-1">
                <!-- Geo Info Card -->
                <div class="bg-white p-6 rounded-lg shadow-md border-t-4 border-teal-500 mb-6">
                    <div class="flex items-center mb-4 pb-2 border-b border-gray-200">
                        <div class="p-2 bg-teal-500 rounded-lg mr-3">
                            <i class="fas fa-info-circle text-white"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-800">Info Geo</h3>
                    </div>

                    <div class="space-y-4">
                        <div class="p-4 bg-teal-50 rounded-lg">
                            <h4 class="text-sm font-semibold text-teal-800 uppercase tracking-wider mb-2">Petunjuk</h4>
                            <ul class="list-disc pl-5 text-sm text-gray-600 space-y-1">
                                <li>Kode geo harus unik dan tidak berubah</li>
                                <li>Gunakan tombol <span class="font-medium">search</span> untuk memilih geo</li>
                                <li>Area menentukan karakteristik geografis</li>
                                <li>Perubahan apa pun harus disimpan</li>
                            </ul>
                        </div>

                        <div class="p-4 bg-blue-50 rounded-lg">
                            <h4 class="text-sm font-semibold text-blue-800 uppercase tracking-wider mb-2">Geo Areas</h4>
                            <div class="grid grid-cols-2 gap-2 text-sm">
                                <div class="flex items-center">
                                    <span
                                        class="w-6 h-6 flex items-center justify-center bg-blue-600 text-white rounded-full font-bold mr-2">ID</span>
                                    <span>Indonesia</span>
                                </div>
                                <div class="flex items-center">
                                    <span
                                        class="w-6 h-6 flex items-center justify-center bg-red-500 text-white rounded-full font-bold mr-2">MY</span>
                                    <span>Malaysia</span>
                                </div>
                                <div class="flex items-center">
                                    <span
                                        class="w-6 h-6 flex items-center justify-center bg-green-500 text-white rounded-full font-bold mr-2">SG</span>
                                    <span>Singapore</span>
                                </div>
                                <div class="flex items-center">
                                    <span
                                        class="w-6 h-6 flex items-center justify-center bg-yellow-500 text-white rounded-full font-bold mr-2">TH</span>
                                    <span>Thailand</span>
                                </div>
                                <div class="flex items-center">
                                    <span
                                        class="w-6 h-6 flex items-center justify-center bg-purple-500 text-white rounded-full font-bold mr-2">VN</span>
                                    <span>Vietnam</span>
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
                        <a href="#"
                            class="flex items-center p-3 bg-purple-50 rounded-lg hover:bg-purple-100 transition-colors">
                            <div class="p-2 bg-purple-500 rounded-full mr-3">
                                <i class="fas fa-layer-group text-white text-sm"></i>
                            </div>
                            <div>
                                <p class="font-medium text-purple-900">Geo Areas</p>
                                <p class="text-xs text-purple-700">Kelola area geografis</p>
                            </div>
                        </a>

                        <a href="#"
                            class="flex items-center p-3 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                            <div class="p-2 bg-blue-500 rounded-full mr-3">
                                <i class="fas fa-th-list text-white text-sm"></i>
                            </div>
                            <div>
                                <p class="font-medium text-blue-900">Region Types</p>
                                <p class="text-xs text-blue-700">Lihat tipe region</p>
                            </div>
                        </a>

                        <a href="#"
                            class="flex items-center p-3 bg-green-50 rounded-lg hover:bg-green-100 transition-colors">
                            <div class="p-2 bg-green-500 rounded-full mr-3">
                                <i class="fas fa-print text-white text-sm"></i>
                            </div>
                            <div>
                                <p class="font-medium text-green-900">Cetak Daftar</p>
                                <p class="text-xs text-green-700">Cetak daftar geo</p>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Geo Table Window -->
    <div id="geoModal" class="hidden fixed inset-0 z-50 bg-black bg-opacity-50 items-center justify-center">
        <div class="bg-white rounded-lg shadow-xl w-11/12 md:w-3/4 lg:w-2/3 max-w-5xl mx-auto transform transition-transform duration-300"
            style="max-height: 80vh;">
            <!-- Modal Header - Title Bar -->
            <div
                class="flex items-center justify-between p-4 border-b border-gray-200 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-t-lg">
                <div class="flex items-center">
                    <div class="p-2 bg-white bg-opacity-30 rounded-lg mr-3">
                        <i class="fas fa-globe-americas"></i>
                    </div>
                    <h3 class="text-xl font-semibold">Geo Table</h3>
                </div>
                <button type="button" onclick="toggleModal('geoModal')"
                    class="text-white hover:text-gray-200 focus:outline-none transform active:translate-y-px">
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
                        <input type="text" id="searchInput" placeholder="Search geo data..."
                            class="pl-10 pr-4 py-2 w-full border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 bg-gray-50">
                    </div>
                </div>

                <div class="overflow-x-auto rounded-lg border border-gray-200">
                    <table class="min-w-full divide-y divide-gray-200" id="geoDataTable">
                        <thead class="bg-gray-50 sticky top-0">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    No</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Code</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Country</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    State</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Town</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Town Section</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Area</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200 text-xs" id="geoTableBody">
                            @if (isset($geoData) && count($geoData) > 0)
                                @foreach ($geoData as $index => $geo)
                                    <tr class="hover:bg-blue-50 cursor-pointer" data-code="{{ $geo->code }}"
                                        data-area="{{ $geo->area }}"
                                        onclick="selectGeoRow(this); event.stopPropagation();"
                                        ondblclick="editGeoData('{{ $geo->code }}')">
                                        <td class="px-4 py-3 whitespace-nowrap">{{ $index + 1 }}</td>
                                        <td class="px-4 py-3 whitespace-nowrap font-medium text-gray-900">
                                            {{ $geo->code }}</td>
                                        <td class="px-4 py-3 whitespace-nowrap text-gray-700">{{ $geo->country }}</td>
                                        <td class="px-4 py-3 whitespace-nowrap text-gray-700">{{ $geo->state }}</td>
                                        <td class="px-4 py-3 whitespace-nowrap text-gray-700">{{ $geo->town }}</td>
                                        <td class="px-4 py-3 whitespace-nowrap text-gray-700">{{ $geo->town_section }}
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            <span
                                                class="px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">{{ $geo->area }}</span>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="7" class="px-4 py-4 text-center text-gray-500">Tidak ada data geo yang
                                        tersedia.</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>

                <!-- Bottom Buttons -->
                <div class="mt-4 grid grid-cols-3 gap-2">
                    <button type="button" onclick="sortTableByColumn(1)"
                        class="py-2 px-3 bg-gray-100 border border-gray-400 hover:bg-gray-200 text-xs rounded-lg transform active:translate-y-px">
                        <i class="fas fa-sort mr-1"></i>By Code
                    </button>
                    <button type="button" onclick="sortTableByColumn(2)"
                        class="py-2 px-3 bg-gray-100 border border-gray-400 hover:bg-gray-200 text-xs rounded-lg transform active:translate-y-px">
                        <i class="fas fa-sort mr-1"></i>By Country
                    </button>
                    <button type="button" onclick="selectGeo()"
                        class="py-2 px-3 bg-blue-500 hover:bg-blue-600 text-white text-xs rounded-lg transform active:translate-y-px">
                        <i class="fas fa-check mr-1"></i>Select
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Geo Modal -->
    <div id="editGeoModal" class="hidden">
        <div class="fixed inset-0 z-50 bg-black bg-opacity-50 flex items-center justify-center">
            <div class="bg-white rounded-lg shadow-xl w-11/12 md:w-2/5 max-w-md mx-auto transform transition-transform duration-300">
                <!-- Modal Header - Title Bar -->
                <div
                    class="flex items-center justify-between p-4 border-b border-gray-200 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-t-lg">
                    <div class="flex items-center">
                        <div class="p-2 bg-white bg-opacity-30 rounded-lg mr-3">
                            <i class="fas fa-globe-americas"></i>
                        </div>
                        <h3 class="text-xl font-semibold">Define Geo</h3>
                    </div>
                    <button type="button" onclick="toggleModal('editGeoModal')"
                        class="text-white hover:text-gray-200 focus:outline-none transform active:translate-y-px">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                <!-- Form Content -->
                <div class="p-6">
                    <form id="editGeoForm" class="space-y-4">
                        <div class="grid grid-cols-1 gap-4">
                            <div>
                                <label for="editGeoCode" class="block text-sm font-medium text-gray-700 mb-1">Geo
                                    Code:</label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
                                        <i class="fas fa-hashtag"></i>
                                    </span>
                                    <input id="editGeoCode" type="text"
                                        class="pl-10 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm">
                                </div>
                            </div>

                            <div>
                                <label for="editCountry" class="block text-sm font-medium text-gray-700 mb-1">Country:</label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
                                        <i class="fas fa-flag"></i>
                                    </span>
                                    <input id="editCountry" type="text"
                                        class="pl-10 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm"
                                        required>
                                </div>
                            </div>

                            <div>
                                <label for="editState" class="block text-sm font-medium text-gray-700 mb-1">State:</label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
                                        <i class="fas fa-map"></i>
                                    </span>
                                    <input id="editState" type="text"
                                        class="pl-10 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm">
                                </div>
                            </div>

                            <div>
                                <label for="editTown" class="block text-sm font-medium text-gray-700 mb-1">Town:</label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
                                        <i class="fas fa-city"></i>
                                    </span>
                                    <input id="editTown" type="text"
                                        class="pl-10 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm">
                                </div>
                            </div>

                            <div>
                                <label for="editTownSection" class="block text-sm font-medium text-gray-700 mb-1">Town
                                    Section:</label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
                                        <i class="fas fa-map-marker-alt"></i>
                                    </span>
                                    <input id="editTownSection" type="text"
                                        class="pl-10 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm">
                                </div>
                            </div>

                            <div>
                                <label for="editGeoArea" class="block text-sm font-medium text-gray-700 mb-1">Geo
                                    Area:</label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
                                        <i class="fas fa-globe-asia"></i>
                                    </span>
                                    <input id="editGeoArea" type="text"
                                        class="pl-10 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm">
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Note:</label>
                                <div class="border border-gray-300 rounded-md p-3 bg-gray-50 h-16 overflow-auto text-sm">
                                    <p>Geo Area harus 2-4 karakter dan spesifik untuk negara/wilayah</p>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end space-x-3 mt-6 pt-4 border-t border-gray-200">
                            <button type="button" onclick="toggleModal('editGeoModal')"
                                class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition-colors text-sm transform active:translate-y-px">
                                <i class="fas fa-times mr-2"></i>Cancel
                            </button>
                            <button id="btnReview" type="button"
                                class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors text-sm transform active:translate-y-px">
                                <i class="fas fa-eye mr-2"></i>Review
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Review Geo Modal -->
    <div id="reviewGeoModal" class="hidden">
        <div class="fixed inset-0 z-50 bg-black bg-opacity-50 flex items-center justify-center">
            <div class="bg-white rounded-lg shadow-xl w-11/12 md:w-2/5 max-w-md mx-auto transform transition-transform duration-300">
                <!-- Modal Header - Title Bar -->
                <div
                    class="flex items-center justify-between p-4 border-b border-gray-200 bg-gradient-to-r from-green-600 to-green-700 text-white rounded-t-lg">
                    <div class="flex items-center">
                        <div class="p-2 bg-white bg-opacity-30 rounded-lg mr-3">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <h3 class="text-xl font-semibold">Review Geo Data</h3>
                    </div>
                    <button type="button" onclick="toggleModal('reviewGeoModal')"
                        class="text-white hover:text-gray-200 focus:outline-none transform active:translate-y-px">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                <!-- Form Content -->
                <div class="p-6">
                    <div class="mb-4 p-4 bg-gray-50 rounded-lg">
                        <div class="grid grid-cols-2 gap-y-3">
                            <div class="text-sm font-medium text-gray-500">Geo Code:</div>
                            <div id="reviewGeoCode" class="text-sm font-semibold text-gray-900"></div>

                            <div class="text-sm font-medium text-gray-500">Country:</div>
                            <div id="reviewCountry" class="text-sm font-semibold text-gray-900"></div>

                            <div class="text-sm font-medium text-gray-500">State:</div>
                            <div id="reviewState" class="text-sm font-semibold text-gray-900"></div>

                            <div class="text-sm font-medium text-gray-500">Town:</div>
                            <div id="reviewTown" class="text-sm font-semibold text-gray-900"></div>

                            <div class="text-sm font-medium text-gray-500">Town Section:</div>
                            <div id="reviewTownSection" class="text-sm font-semibold text-gray-900"></div>

                            <div class="text-sm font-medium text-gray-500">Geo Area:</div>
                            <div id="reviewGeoArea" class="text-sm font-semibold text-gray-900"></div>
                        </div>
                    </div>

                    <p class="text-sm text-gray-600 mb-4">Konfirmasi data di atas untuk menyimpan perubahan.</p>

                    <div class="flex justify-end space-x-3 mt-6 pt-4 border-t border-gray-200">
                        <button type="button" onclick="toggleModal('reviewGeoModal')"
                            class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition-colors text-sm transform active:translate-y-px">
                            <i class="fas fa-arrow-left mr-2"></i>Kembali
                        </button>
                        <button type="button" onclick="confirmAndSave()"
                            class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-colors text-sm transform active:translate-y-px">
                            <i class="fas fa-save mr-2"></i>Konfirmasi
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Loading Overlay -->
    <div id="loadingOverlay" class="hidden">
        <div class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50">
            <div class="w-12 h-12 border-4 border-solid border-blue-500 border-t-transparent rounded-full animate-spin"></div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/geo.js') }}"></script>
@endpush
