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
            Cari Data
        </button>
    </div>
    
    <div id="geoCodeResult" class="mt-2"></div>

    <!-- Modal -->
    <div id="geoModal" class="fixed inset-0 flex items-center justify-center z-50 hidden">
        <div class="modal-overlay absolute inset-0 bg-gray-800 opacity-75"></div>
        <div class="modal-content bg-white rounded-lg shadow-xl z-10 w-4/5 max-h-screen flex flex-col">
            <div class="modal-header p-4 border-b flex justify-between items-center bg-gray-50 rounded-t-lg">
                <h5 class="modal-title text-lg font-bold text-gray-800">Geo Area Table</h5>
                <button type="button" class="text-gray-500 hover:text-gray-700 focus:outline-none" onclick="toggleModal('geoModal')">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div class="modal-body p-4 overflow-auto">
                <div class="mb-4 relative">
                    <input type="text" id="searchInput" class="w-full p-2 pl-10 border border-gray-300 rounded-md" placeholder="Cari berdasarkan kode, negara, provinsi, kota...">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>
                <div class="overflow-x-auto shadow-md rounded-lg">
                    <table class="min-w-full bg-white border border-gray-300 rounded-lg">
                        <thead>
                            <tr class="bg-gray-100 text-gray-700 uppercase text-sm leading-normal">
                                <th class="py-3 px-6 text-left">No</th>
                                <th class="py-3 px-6 text-left">Kode</th>
                                <th class="py-3 px-6 text-left">Negara</th>
                                <th class="py-3 px-6 text-left">Provinsi</th>
                                <th class="py-3 px-6 text-left">Kota</th>
                                <th class="py-3 px-6 text-left">Bagian Kota</th>
                                <th class="py-3 px-6 text-left">Area</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-600 text-sm" id="geoTableBody">
                            @forelse($geoData as $index => $geo)
                            <tr class="border-b border-gray-200 hover:bg-gray-100 cursor-pointer transition-colors duration-150" data-code="{{ $geo->code }}" data-area="{{ $geo->area }}" onclick="selectGeoRow(this)">
                                <td class="py-3 px-6">{{ $index + 1 }}</td>
                                <td class="py-3 px-6 font-medium">{{ $geo->code }}</td>
                                <td class="py-3 px-6">{{ $geo->country }}</td>
                                <td class="py-3 px-6">{{ $geo->state }}</td>
                                <td class="py-3 px-6">{{ $geo->town }}</td>
                                <td class="py-3 px-6">{{ $geo->town_section }}</td>
                                <td class="py-3 px-6">{{ $geo->area }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="py-6 px-6 text-center text-gray-500">Tidak ada data yang tersedia</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer p-4 border-t flex justify-between bg-gray-50 rounded-b-lg">
                <button type="button" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 transition duration-200 flex items-center" onclick="selectGeo()">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                    Select
                </button>
                <button type="button" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400 transition duration-200 flex items-center" onclick="toggleModal('geoModal')">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                    Exit
                </button>
            </div>
        </div>
    </div>

    <!-- Modal untuk mengubah data geo -->
    <div id="editGeoModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-md shadow-lg w-full max-w-md">
            <div class="flex justify-between items-center border-b p-4">
                <h3 class="text-lg font-medium">Edit Data Geo</h3>
                <button type="button" class="text-gray-400 hover:text-gray-500" onclick="toggleModal('editGeoModal')">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div class="p-4">
                <form id="editGeoForm" class="space-y-4">
                    <div class="grid grid-cols-3 gap-4 items-center">
                        <label for="editGeoCode" class="font-medium text-gray-700">Geo Code:</label>
                        <div class="col-span-2">
                            <input type="text" id="editGeoCode" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-3 gap-4 items-center">
                        <label for="editCountry" class="font-medium text-gray-700">Country:</label>
                        <div class="col-span-2">
                            <input type="text" id="editCountry" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-3 gap-4 items-center">
                        <label for="editState" class="font-medium text-gray-700">State:</label>
                        <div class="col-span-2">
                            <input type="text" id="editState" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-3 gap-4 items-center">
                        <label for="editTown" class="font-medium text-gray-700">Town:</label>
                        <div class="col-span-2">
                            <input type="text" id="editTown" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-3 gap-4 items-center">
                        <label for="editTownSection" class="font-medium text-gray-700">Town Section:</label>
                        <div class="col-span-2">
                            <input type="text" id="editTownSection" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-3 gap-4 items-center">
                        <label for="editGeoArea" class="font-medium text-gray-700">Geo Area:</label>
                        <div class="col-span-2">
                            <input type="text" id="editGeoArea" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>
                </form>
            </div>
            <div class="flex justify-end border-t p-4 space-x-3">
                <button type="button" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition duration-200" onclick="toggleModal('editGeoModal')">
                    Batal
                </button>
                <button type="button" id="btnReview" class="px-4 py-2 bg-yellow-500 text-white rounded-md hover:bg-yellow-600 transition duration-200">
                    Review
                </button>
                <button type="button" id="btnRecord" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 transition duration-200" onclick="saveGeoData()">
                    Record
                </button>
            </div>
        </div>
    </div>

    <!-- Modal untuk review data -->
    <div id="reviewGeoModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-md shadow-lg w-full max-w-md">
            <div class="flex justify-between items-center border-b p-4">
                <h3 class="text-lg font-medium">Review Data Geo</h3>
                <button type="button" class="text-gray-400 hover:text-gray-500" onclick="toggleModal('reviewGeoModal')">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div class="p-4">
                <div class="space-y-3 text-sm">
                    <div class="grid grid-cols-3 gap-2">
                        <div class="font-medium">Geo Code:</div>
                        <div class="col-span-2" id="reviewGeoCode"></div>
                    </div>
                    <div class="grid grid-cols-3 gap-2">
                        <div class="font-medium">Country:</div>
                        <div class="col-span-2" id="reviewCountry"></div>
                    </div>
                    <div class="grid grid-cols-3 gap-2">
                        <div class="font-medium">State:</div>
                        <div class="col-span-2" id="reviewState"></div>
                    </div>
                    <div class="grid grid-cols-3 gap-2">
                        <div class="font-medium">Town:</div>
                        <div class="col-span-2" id="reviewTown"></div>
                    </div>
                    <div class="grid grid-cols-3 gap-2">
                        <div class="font-medium">Town Section:</div>
                        <div class="col-span-2" id="reviewTownSection"></div>
                    </div>
                    <div class="grid grid-cols-3 gap-2">
                        <div class="font-medium">Geo Area:</div>
                        <div class="col-span-2" id="reviewGeoArea"></div>
                    </div>
                </div>
            </div>
            <div class="flex justify-end border-t p-4 space-x-3">
                <button type="button" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition duration-200" onclick="toggleModal('reviewGeoModal')">
                    Kembali
                </button>
                <button type="button" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 transition duration-200" onclick="confirmAndSave()">
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
        
        // Dalam aplikasi nyata, kita akan mengirim data ke server di sini
        // Misalnya dengan AJAX POST ke endpoint API yang sesuai
        
        // Untuk demo ini, kita bisa menambahkan alert sebagai indikasi
        alert('Data berhasil disimpan!');
    }
    
    function toggleModal(modalId) {
        const modal = document.getElementById(modalId);
        modal.classList.toggle('hidden');
        if (!modal.classList.contains('hidden')) {
            document.getElementById('searchInput').focus();
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