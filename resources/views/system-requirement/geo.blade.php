@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-2xl font-bold mb-4">Define Geo</h1>

    <div class="flex items-center mb-4">
        <label for="geoCode" class="mr-2 font-medium">Geo Code:</label>
        <input type="text" id="geoCode" class="border border-gray-300 rounded-md p-2 mr-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Masukkan Kode Geo" onkeyup="searchGeoCode(this.value)">
        <button type="button" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 transition duration-200 flex items-center" onclick="toggleModal('geoModal')">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
            </svg>
        </button>
    </div>
    
    <div id="geoCodeResult" class="mt-2"></div>

    <!-- Modal -->
    <div id="geoModal" class="fixed inset-0 flex items-center justify-center z-50 hidden">
        <div class="modal-overlay absolute inset-0 bg-black bg-opacity-70 backdrop-blur-sm transition-opacity duration-300 ease-in-out"></div>
        <div class="modal-content bg-white bg-opacity-95 rounded-2xl shadow-2xl z-10 w-4/5 max-h-[90vh] flex flex-col transform transition-all duration-300 ease-in-out scale-95 opacity-0 overflow-hidden">
            <div class="modal-header p-5 border-b flex justify-between items-center bg-gradient-to-r from-blue-600 to-indigo-700 text-white rounded-t-2xl">
                <h5 class="modal-title text-xl font-bold flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 mr-2 text-blue-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                    </svg>
                    Geo Area Table
                </h5>
                <button type="button" class="text-white hover:text-red-200 focus:outline-none transition-colors duration-200 p-1 rounded-full hover:bg-white hover:bg-opacity-20" onclick="toggleModal('geoModal')">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div class="modal-body p-6 overflow-auto bg-gradient-to-b from-gray-50 to-white">
                <div class="mb-5 relative">
                    <input type="text" id="searchInput" class="w-full p-4 pl-12 border border-gray-300 rounded-xl shadow-sm focus:ring-3 focus:ring-blue-400 focus:border-blue-500 transition-all" placeholder="Cari berdasarkan kode, negara, provinsi, kota...">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-500" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>
                <div class="overflow-x-auto shadow-xl rounded-xl">
                    <table class="min-w-full bg-white rounded-xl overflow-hidden">
                        <thead>
                            <tr class="bg-gradient-to-r from-blue-50 to-indigo-100 text-indigo-900 uppercase text-sm leading-normal font-semibold">
                                <th class="py-4 px-6 text-left">No</th>
                                <th class="py-4 px-6 text-left">Kode</th>
                                <th class="py-4 px-6 text-left">Negara</th>
                                <th class="py-4 px-6 text-left">Provinsi</th>
                                <th class="py-4 px-6 text-left">Kota</th>
                                <th class="py-4 px-6 text-left">Bagian Kota</th>
                                <th class="py-4 px-6 text-left">Area</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-700 text-sm" id="geoTableBody">
                            @forelse($geoData as $index => $geo)
                            <tr class="border-b border-gray-200 hover:bg-blue-50 cursor-pointer transition-colors duration-150" data-code="{{ $geo->code }}" data-area="{{ $geo->area }}" onclick="selectGeoRow(this)">
                                <td class="py-3.5 px-6">{{ $index + 1 }}</td>
                                <td class="py-3.5 px-6 font-medium text-blue-800">{{ $geo->code }}</td>
                                <td class="py-3.5 px-6">{{ $geo->country }}</td>
                                <td class="py-3.5 px-6">{{ $geo->state }}</td>
                                <td class="py-3.5 px-6">{{ $geo->town }}</td>
                                <td class="py-3.5 px-6">{{ $geo->town_section }}</td>
                                <td class="py-3.5 px-6">{{ $geo->area }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="py-8 px-6 text-center text-gray-500">
                                    <div class="flex flex-col items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        <span>Tidak ada data yang tersedia</span>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer p-5 border-t flex justify-between bg-white rounded-b-2xl">
                <button type="button" class="bg-gradient-to-r from-blue-600 to-indigo-700 text-white px-6 py-3 rounded-xl shadow-lg hover:shadow-blue-200 transition duration-300 flex items-center transform hover:-translate-y-1" onclick="selectGeo()">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                    Pilih
                </button>
                <button type="button" class="bg-gray-200 text-gray-800 px-6 py-3 rounded-xl shadow hover:bg-gray-300 transition duration-300 flex items-center transform hover:-translate-y-1" onclick="toggleModal('geoModal')">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                    Keluar
                </button>
            </div>
        </div>
    </div>

    <!-- Modal untuk mengubah data geo -->
    <div id="editGeoModal" class="fixed inset-0 flex items-center justify-center z-50 hidden">
        <div class="modal-overlay absolute inset-0 bg-black bg-opacity-70 backdrop-blur-sm transition-opacity duration-300 ease-in-out"></div>
        <div class="modal-content bg-white bg-opacity-95 rounded-2xl shadow-2xl w-full max-w-lg transform transition-all duration-300 ease-in-out scale-95 opacity-0 overflow-hidden">
            <div class="flex justify-between items-center border-b p-5 bg-gradient-to-r from-purple-600 to-indigo-700 text-white rounded-t-2xl">
                <h3 class="text-xl font-bold flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 mr-2 text-purple-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                    </svg>
                    Edit Data Geo
                </h3>
                <button type="button" class="text-white hover:text-red-200 focus:outline-none transition-colors duration-200 p-1 rounded-full hover:bg-white hover:bg-opacity-20" onclick="toggleModal('editGeoModal')">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div class="p-6 bg-gradient-to-b from-gray-50 to-white">
                <form id="editGeoForm" class="space-y-5">
                    <div class="grid grid-cols-3 gap-4 items-center">
                        <label for="editGeoCode" class="font-medium text-gray-700 text-right">Geo Code:</label>
                        <div class="col-span-2">
                            <input type="text" id="editGeoCode" class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-3 focus:ring-purple-400 focus:border-purple-500 transition-all shadow-sm">
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-3 gap-4 items-center">
                        <label for="editCountry" class="font-medium text-gray-700 text-right">Country:</label>
                        <div class="col-span-2">
                            <input type="text" id="editCountry" class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-3 focus:ring-purple-400 focus:border-purple-500 transition-all shadow-sm">
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-3 gap-4 items-center">
                        <label for="editState" class="font-medium text-gray-700 text-right">State:</label>
                        <div class="col-span-2">
                            <input type="text" id="editState" class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-3 focus:ring-purple-400 focus:border-purple-500 transition-all shadow-sm">
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-3 gap-4 items-center">
                        <label for="editTown" class="font-medium text-gray-700 text-right">Town:</label>
                        <div class="col-span-2">
                            <input type="text" id="editTown" class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-3 focus:ring-purple-400 focus:border-purple-500 transition-all shadow-sm">
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-3 gap-4 items-center">
                        <label for="editTownSection" class="font-medium text-gray-700 text-right">Town Section:</label>
                        <div class="col-span-2">
                            <input type="text" id="editTownSection" class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-3 focus:ring-purple-400 focus:border-purple-500 transition-all shadow-sm">
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-3 gap-4 items-center">
                        <label for="editGeoArea" class="font-medium text-gray-700 text-right">Geo Area:</label>
                        <div class="col-span-2">
                            <input type="text" id="editGeoArea" class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-3 focus:ring-purple-400 focus:border-purple-500 transition-all shadow-sm">
                        </div>
                    </div>
                </form>
            </div>
            <div class="flex justify-end border-t p-5 space-x-3 bg-white rounded-b-2xl">
                <button type="button" class="px-5 py-3 bg-gray-200 text-gray-800 rounded-xl hover:bg-gray-300 transition duration-300 transform hover:-translate-y-1 shadow" onclick="toggleModal('editGeoModal')">
                    Batal
                </button>
                <button type="button" id="btnReview" class="px-5 py-3 bg-gradient-to-r from-amber-500 to-amber-600 text-white rounded-xl hover:shadow-amber-200 hover:shadow-lg transition duration-300 transform hover:-translate-y-1">
                    Review
                </button>
                <button type="button" id="btnRecord" class="px-5 py-3 bg-gradient-to-r from-purple-600 to-indigo-700 text-white rounded-xl hover:shadow-indigo-200 hover:shadow-lg transition duration-300 transform hover:-translate-y-1" onclick="saveGeoData()">
                    Record
                </button>
            </div>
        </div>
    </div>

    <!-- Modal untuk review data -->
    <div id="reviewGeoModal" class="fixed inset-0 flex items-center justify-center z-50 hidden">
        <div class="modal-overlay absolute inset-0 bg-black bg-opacity-70 backdrop-blur-sm transition-opacity duration-300 ease-in-out"></div>
        <div class="modal-content bg-white bg-opacity-95 rounded-2xl shadow-2xl w-full max-w-lg transform transition-all duration-300 ease-in-out scale-95 opacity-0 overflow-hidden">
            <div class="flex justify-between items-center border-b p-5 bg-gradient-to-r from-green-600 to-teal-700 text-white rounded-t-2xl">
                <h3 class="text-xl font-bold flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 mr-2 text-green-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Review Data Geo
                </h3>
                <button type="button" class="text-white hover:text-red-200 focus:outline-none transition-colors duration-200 p-1 rounded-full hover:bg-white hover:bg-opacity-20" onclick="toggleModal('reviewGeoModal')">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div class="p-6 bg-gradient-to-b from-gray-50 to-white">
                <div class="bg-white rounded-xl p-5 shadow-md space-y-5 text-sm border border-gray-100">
                    <div class="grid grid-cols-3 gap-3 items-center border-b pb-3">
                        <div class="font-semibold text-gray-600">Geo Code:</div>
                        <div class="col-span-2 font-bold text-green-700 text-base" id="reviewGeoCode"></div>
                    </div>
                    <div class="grid grid-cols-3 gap-3 items-center border-b pb-3">
                        <div class="font-semibold text-gray-600">Country:</div>
                        <div class="col-span-2 font-bold text-gray-800" id="reviewCountry"></div>
                    </div>
                    <div class="grid grid-cols-3 gap-3 items-center border-b pb-3">
                        <div class="font-semibold text-gray-600">State:</div>
                        <div class="col-span-2 font-bold text-gray-800" id="reviewState"></div>
                    </div>
                    <div class="grid grid-cols-3 gap-3 items-center border-b pb-3">
                        <div class="font-semibold text-gray-600">Town:</div>
                        <div class="col-span-2 font-bold text-gray-800" id="reviewTown"></div>
                    </div>
                    <div class="grid grid-cols-3 gap-3 items-center border-b pb-3">
                        <div class="font-semibold text-gray-600">Town Section:</div>
                        <div class="col-span-2 font-bold text-gray-800" id="reviewTownSection"></div>
                    </div>
                    <div class="grid grid-cols-3 gap-3 items-center">
                        <div class="font-semibold text-gray-600">Geo Area:</div>
                        <div class="col-span-2 font-bold text-green-700 text-base" id="reviewGeoArea"></div>
                    </div>
                </div>
            </div>
            <div class="flex justify-end border-t p-5 space-x-3 bg-white rounded-b-2xl">
                <button type="button" class="px-5 py-3 bg-gray-200 text-gray-800 rounded-xl hover:bg-gray-300 transition duration-300 transform hover:-translate-y-1 shadow" onclick="toggleModal('reviewGeoModal')">
                    Kembali
                </button>
                <button type="button" class="px-6 py-3 bg-gradient-to-r from-green-600 to-teal-700 text-white rounded-xl hover:shadow-green-200 hover:shadow-lg transition duration-300 transform hover:-translate-y-1" onclick="confirmAndSave()">
                    Konfirmasi
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    let selectedRow = null;
    let geoData = [];
    
    // Ambil semua data geo dari tabel untuk pencarian lokal
    document.addEventListener('DOMContentLoaded', function() {
        const rows = document.querySelectorAll('#geoTableBody tr:not(.empty-message)');
        rows.forEach(row => {
            if (row.getAttribute('data-code')) {
                geoData.push({
                    code: row.getAttribute('data-code'),
                    area: row.getAttribute('data-area'),
                    country: row.querySelector('td:nth-child(3)').textContent.trim(),
                    state: row.querySelector('td:nth-child(4)').textContent.trim(),
                    town: row.querySelector('td:nth-child(5)').textContent.trim(),
                    town_section: row.querySelector('td:nth-child(6)').textContent.trim()
                });
            }
        });
    });

    function searchGeoCode(code) {
        if (code.trim() === '') {
            document.getElementById('geoCodeResult').innerHTML = '';
            return;
        }
        
        // Pencarian data geo berdasarkan kode
        const matches = geoData.filter(item => 
            item.code.toLowerCase().includes(code.toLowerCase())
        );
        
        const resultDiv = document.getElementById('geoCodeResult');
        
        if (matches.length > 0) {
            if (matches.length === 1 && matches[0].code.toLowerCase() === code.toLowerCase()) {
                // Jika kode cocok persis, tampilkan detail dan tambahkan tombol edit
                resultDiv.innerHTML = `
                    <div class="p-3 bg-green-50 border border-green-200 rounded-md">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-sm text-green-800">Data ditemukan: <span class="font-semibold">${matches[0].code}</span> - ${matches[0].area}</p>
                                <p class="text-xs text-green-700">${matches[0].country}, ${matches[0].state}, ${matches[0].town}</p>
                            </div>
                            <button class="px-2 py-1 bg-blue-500 text-white text-xs rounded hover:bg-blue-600" 
                                onclick="editGeoData('${matches[0].code}')">
                                Edit
                            </button>
                        </div>
                    </div>
                `;
            } else if (matches.length <= 5) {
                // Jika ada beberapa kecocokan (hingga 5), tampilkan pilihan
                let html = `<div class="p-3 bg-blue-50 border border-blue-200 rounded-md">
                    <p class="text-sm text-blue-800 mb-2">Beberapa kode ditemukan:</p>
                    <ul class="space-y-1">`;
                
                matches.forEach(match => {
                    html += `<li class="flex justify-between items-center">
                        <button class="text-sm text-blue-700 hover:text-blue-900 hover:underline focus:outline-none" 
                            onclick="selectGeoByCode('${match.code}')">
                            ${match.code} - ${match.area}
                        </button>
                        <button class="px-2 py-1 bg-blue-500 text-white text-xs rounded hover:bg-blue-600" 
                            onclick="editGeoData('${match.code}')">
                            Edit
                        </button>
                    </li>`;
                });
                
                html += `</ul></div>`;
                resultDiv.innerHTML = html;
            } else {
                // Jika terlalu banyak kecocokan
                resultDiv.innerHTML = `
                    <div class="p-3 bg-yellow-50 border border-yellow-200 rounded-md">
                        <p class="text-sm text-yellow-800">Ditemukan ${matches.length} kode yang cocok. Silakan lebih spesifik atau gunakan tombol Cari Data.</p>
                    </div>
                `;
            }
        } else {
            // Jika tidak ada kecocokan, tampilkan pesan dan tombol untuk membuat baru
            resultDiv.innerHTML = `
                <div class="p-3 bg-red-50 border border-red-200 rounded-md">
                    <div class="flex justify-between items-start">
                        <p class="text-sm text-red-800">Tidak ditemukan kode geo "${code}". Periksa kembali kode atau buat data baru.</p>
                        <button class="px-2 py-1 bg-green-500 text-white text-xs rounded hover:bg-green-600" 
                            onclick="createNewGeo('${code}')">
                            Buat Baru
                        </button>
                    </div>
                </div>
            `;
        }
    }
    
    function selectGeoByCode(code) {
        document.getElementById('geoCode').value = code;
        
        const match = geoData.find(item => item.code === code);
        if (match) {
            document.getElementById('geoCodeResult').innerHTML = `
                <div class="p-3 bg-green-50 border border-green-200 rounded-md">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-sm text-green-800">Data dipilih: <span class="font-semibold">${match.code}</span> - ${match.area}</p>
                            <p class="text-xs text-green-700">${match.country}, ${match.state}, ${match.town}</p>
                        </div>
                        <button class="px-2 py-1 bg-blue-500 text-white text-xs rounded hover:bg-blue-600" 
                            onclick="editGeoData('${match.code}')">
                            Edit
                        </button>
                    </div>
                </div>
            `;
        }
    }
    
    function editGeoData(code) {
        // Cari data geo berdasarkan kode
        const geoItem = geoData.find(item => item.code === code);
        
        if (geoItem) {
            // Isi form dengan data geo yang ditemukan
            document.getElementById('editGeoCode').value = geoItem.code;
            document.getElementById('editCountry').value = geoItem.country;
            document.getElementById('editState').value = geoItem.state;
            document.getElementById('editTown').value = geoItem.town;
            document.getElementById('editTownSection').value = geoItem.town_section;
            document.getElementById('editGeoArea').value = geoItem.area;
            
            // Tampilkan modal edit
            toggleModal('editGeoModal');
        }
    }
    
    function createNewGeo(code) {
        // Reset form
        document.getElementById('editGeoForm').reset();
        
        // Isi kode geo dengan nilai yang dimasukkan user
        document.getElementById('editGeoCode').value = code;
        
        // Tampilkan modal edit untuk data baru
        toggleModal('editGeoModal');
    }
    
    // Tombol Review
    document.getElementById('btnReview').addEventListener('click', function() {
        // Ambil data dari form
        const geoCode = document.getElementById('editGeoCode').value;
        const country = document.getElementById('editCountry').value;
        const state = document.getElementById('editState').value;
        const town = document.getElementById('editTown').value;
        const townSection = document.getElementById('editTownSection').value;
        const geoArea = document.getElementById('editGeoArea').value;
        
        // Validasi data
        if (!geoCode || !country) {
            alert('Kode Geo dan Negara harus diisi!');
            return;
        }
        
        // Isi data ke modal review
        document.getElementById('reviewGeoCode').textContent = geoCode;
        document.getElementById('reviewCountry').textContent = country;
        document.getElementById('reviewState').textContent = state;
        document.getElementById('reviewTown').textContent = town;
        document.getElementById('reviewTownSection').textContent = townSection;
        document.getElementById('reviewGeoArea').textContent = geoArea;
        
        // Sembunyikan modal edit dan tampilkan modal review
        toggleModal('editGeoModal');
        toggleModal('reviewGeoModal');
    });
    
    function confirmAndSave() {
        // Sembunyikan modal review
        toggleModal('reviewGeoModal');
        
        // Simpan data
        saveGeoData();
    }
    
    function saveGeoData() {
        // Ambil data dari form
        const geoCode = document.getElementById('editGeoCode').value;
        const country = document.getElementById('editCountry').value;
        const state = document.getElementById('editState').value;
        const town = document.getElementById('editTown').value;
        const townSection = document.getElementById('editTownSection').value;
        const geoArea = document.getElementById('editGeoArea').value;
        
        // Validasi data
        if (!geoCode || !country) {
            alert('Kode Geo dan Negara harus diisi!');
            return;
        }
        
        // Simpan kode ke input utama
        document.getElementById('geoCode').value = geoCode;
        
        // Sembunyikan modal
        toggleModal('editGeoModal');
        
        // Cek apakah ini data baru atau update
        const existingIndex = geoData.findIndex(item => item.code === geoCode);
        
        if (existingIndex !== -1) {
            // Update data yang ada
            geoData[existingIndex] = {
                code: geoCode,
                country: country,
                state: state,
                town: town,
                town_section: townSection,
                area: geoArea
            };
            
            // Tampilkan pesan sukses update
            document.getElementById('geoCodeResult').innerHTML = `
                <div class="p-3 bg-green-50 border border-green-200 rounded-md">
                    <p class="text-sm text-green-800">Data berhasil diperbarui: <span class="font-semibold">${geoCode}</span> - ${geoArea}</p>
                </div>
            `;
        } else {
            // Tambahkan data baru
            const newGeoData = {
                code: geoCode,
                country: country,
                state: state,
                town: town,
                town_section: townSection,
                area: geoArea
            };
            
            geoData.push(newGeoData);
            
            // Tampilkan pesan sukses
            document.getElementById('geoCodeResult').innerHTML = `
                <div class="p-3 bg-green-50 border border-green-200 rounded-md">
                    <p class="text-sm text-green-800">Data baru berhasil ditambahkan: <span class="font-semibold">${geoCode}</span> - ${geoArea}</p>
                </div>
            `;
        }
        
        // Update tabel dengan data yang sudah diperbarui
        updateGeoTable();
        
        // Dalam aplikasi nyata, kita akan mengirim data ke server di sini
        // Misalnya dengan AJAX POST ke endpoint API yang sesuai
        
        // Untuk demo ini, kita bisa menambahkan alert sebagai indikasi
        alert('Data berhasil disimpan!');
    }
    
    // Fungsi untuk memperbarui tampilan tabel berdasarkan data terbaru
    function updateGeoTable() {
        const tableBody = document.getElementById('geoTableBody');
        
        // Kosongkan tabel terlebih dahulu
        tableBody.innerHTML = '';
        
        // Jika tidak ada data, tampilkan pesan
        if (geoData.length === 0) {
            tableBody.innerHTML = '<tr><td colspan="7" class="py-6 px-6 text-center text-gray-500">Tidak ada data yang tersedia</td></tr>';
            return;
        }
        
        // Isi tabel dengan data terbaru
        geoData.forEach((geo, index) => {
            const row = document.createElement('tr');
            row.className = 'border-b border-gray-200 hover:bg-gray-100 cursor-pointer transition-colors duration-150';
            row.setAttribute('data-code', geo.code);
            row.setAttribute('data-area', geo.area);
            row.onclick = function() { selectGeoRow(this); };
            
            row.innerHTML = `
                <td class="py-3 px-6">${index + 1}</td>
                <td class="py-3 px-6 font-medium text-blue-800">${geo.code}</td>
                <td class="py-3 px-6">${geo.country}</td>
                <td class="py-3 px-6">${geo.state || ''}</td>
                <td class="py-3 px-6">${geo.town || ''}</td>
                <td class="py-3 px-6">${geo.town_section || ''}</td>
                <td class="py-3 px-6">${geo.area || ''}</td>
            `;
            
            tableBody.appendChild(row);
        });
        
        // Tambahkan kembali event handler untuk baris tabel
        modifyTableRowHandlers();
    }
    
    function toggleModal(modalId) {
        const modal = document.getElementById(modalId);
        const modalContent = modal.querySelector('.modal-content');
        const modalOverlay = modal.querySelector('.modal-overlay');
        
        if (modal.classList.contains('hidden')) {
            // Tampilkan modal dengan animasi
            modal.classList.remove('hidden');
            setTimeout(() => {
                modalOverlay.classList.add('opacity-100');
                modalContent.classList.add('opacity-100', 'scale-100');
                modalContent.classList.remove('opacity-0', 'scale-95');
            }, 10);
            
            if (modalId === 'geoModal' && document.getElementById('searchInput')) {
                document.getElementById('searchInput').focus();
            }
        } else {
            // Sembunyikan modal dengan animasi
            modalOverlay.classList.remove('opacity-100');
            modalContent.classList.remove('opacity-100', 'scale-100');
            modalContent.classList.add('opacity-0', 'scale-95');
            
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 300);
        }
    }
    
    function selectGeoRow(row) {
        // Hapus highlight dari baris yang dipilih sebelumnya
        if (selectedRow) {
            selectedRow.classList.remove('bg-blue-100');
        }
        
        // Highlight baris yang dipilih
        selectedRow = row;
        selectedRow.classList.add('bg-blue-100');
    }
    
    function selectGeo() {
        if (selectedRow) {
            const code = selectedRow.getAttribute('data-code');
            const area = selectedRow.getAttribute('data-area');
            
            // Isi input dengan data yang dipilih
            document.getElementById('geoCode').value = code;
            
            // Tutup modal
            toggleModal('geoModal');
            
            // Reset selectedRow
            selectedRow = null;
            
            // Tampilkan info geo yang dipilih
            searchGeoCode(code);
        } else {
            alert('Silakan pilih data terlebih dahulu!');
        }
    }

    // Pencarian pada tabel
    document.getElementById('searchInput').addEventListener('keyup', function(e) {
        const searchValue = this.value.toLowerCase();
        const rows = document.getElementById('geoTableBody').getElementsByTagName('tr');
        let visibleCount = 0;
        
        for (let i = 0; i < rows.length; i++) {
            const cols = rows[i].getElementsByTagName('td');
            let matchFound = false;
            
            // Skip row jika itu adalah pesan "tidak ada data"
            if (cols.length === 1 && cols[0].colSpan === 7) continue;
            
            for (let j = 0; j < cols.length; j++) {
                if (cols[j].textContent.toLowerCase().includes(searchValue)) {
                    matchFound = true;
                    break;
                }
            }
            
            if (matchFound) {
                rows[i].style.display = '';
                visibleCount++;
            } else {
                rows[i].style.display = 'none';
            }
        }
        
        // Jika tidak ada hasil pencarian, tampilkan pesan
        const emptyRow = document.querySelector('#geoTableBody tr.empty-message');
        if (visibleCount === 0) {
            if (!emptyRow) {
                const tbody = document.getElementById('geoTableBody');
                const tr = document.createElement('tr');
                tr.className = 'empty-message';
                tr.innerHTML = '<td colspan="7" class="py-6 px-6 text-center text-gray-500">Tidak ditemukan hasil untuk "' + this.value + '"</td>';
                tbody.appendChild(tr);
            } else {
                emptyRow.style.display = '';
                emptyRow.querySelector('td').textContent = 'Tidak ditemukan hasil untuk "' + this.value + '"';
            }
        } else if (emptyRow) {
            emptyRow.style.display = 'none';
        }
        
        // Jika tekan tombol Enter dan hanya ada satu hasil, pilih baris tersebut
        if (e.key === 'Enter' && visibleCount === 1) {
            for (let i = 0; i < rows.length; i++) {
                if (rows[i].style.display !== 'none' && !rows[i].classList.contains('empty-message')) {
                    selectGeoRow(rows[i]);
                    selectGeo();
                    break;
                }
            }
        }
    });

    // Double click pada baris untuk memilih
    document.querySelectorAll('#geoTableBody tr').forEach(row => {
        row.addEventListener('dblclick', function() {
            if (this.querySelector('td').colSpan !== 7) { // Skip empty message row
                const code = this.getAttribute('data-code');
                document.getElementById('geoCode').value = code;
                toggleModal('geoModal');
            }
        });
    });
    
    // Tambahkan navigasi keyboard pada tabel
    document.addEventListener('keydown', function(e) {
        const modal = document.getElementById('geoModal');
        if (modal && !modal.classList.contains('hidden')) {
            const rows = Array.from(document.querySelectorAll('#geoTableBody tr')).filter(row => 
                row.style.display !== 'none' && !row.classList.contains('empty-message')
            );
            
            if (rows.length === 0) return;
            
            const currentIndex = selectedRow ? rows.indexOf(selectedRow) : -1;
            
            // Arah panah
            if (e.key === 'ArrowDown' && currentIndex < rows.length - 1) {
                e.preventDefault();
                const nextRow = rows[currentIndex + 1];
                selectGeoRow(nextRow);
                nextRow.scrollIntoView({block: 'nearest'});
            } else if (e.key === 'ArrowUp' && currentIndex > 0) {
                e.preventDefault();
                const prevRow = rows[currentIndex - 1];
                selectGeoRow(prevRow);
                prevRow.scrollIntoView({block: 'nearest'});
            } else if (e.key === 'Enter' && selectedRow) {
                e.preventDefault();
                selectGeo();
            } else if (e.key === 'Escape') {
                toggleModal('geoModal');
            }
        }
    });

    // Event handler untuk baris tabel
    function modifyTableRowHandlers() {
        document.querySelectorAll('#geoTableBody tr').forEach(row => {
            // Hapus semua event listener lama (jika ada)
            const newRow = row.cloneNode(true);
            row.parentNode.replaceChild(newRow, row);
            
            // Tambahkan event listener baru
            if (!newRow.classList.contains('empty-message')) {
                newRow.addEventListener('click', function() {
                    selectGeoRow(this);
                });
                
                newRow.addEventListener('dblclick', function() {
                    const code = this.getAttribute('data-code');
                    if (code) {
                        document.getElementById('geoCode').value = code;
                        toggleModal('geoModal');
                        editGeoData(code);
                    }
                });
            }
        });
    }
    
    // Panggil fungsi untuk menambahkan event handler ke baris tabel
    document.addEventListener('DOMContentLoaded', modifyTableRowHandlers);
</script>
@endsection