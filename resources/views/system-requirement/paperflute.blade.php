@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Toolbar minimalis modern -->
    <div class="bg-white p-4 rounded-lg shadow-sm mb-6 border border-gray-100">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-2">
                <button id="newButton" type="button" class="p-2 bg-white border border-gray-200 rounded-md hover:bg-gray-50 transition-all duration-200" title="Baru">
                    <i class="fas fa-file text-gray-600"></i>
                </button>
                <button id="editButton" type="button" class="p-2 bg-white border border-gray-200 rounded-md hover:bg-gray-50 transition-all duration-200" title="Edit">
                    <i class="fas fa-edit text-gray-600"></i>
                </button>
                <button id="deleteButton" type="button" class="p-2 bg-white border border-gray-200 rounded-md hover:bg-gray-50 transition-all duration-200" title="Hapus">
                    <i class="fas fa-trash-alt text-gray-600"></i>
                </button>
                <div class="h-6 mx-2 border-r border-gray-200"></div>
                <button id="printButton" type="button" class="p-2 bg-white border border-gray-200 rounded-md hover:bg-gray-50 transition-all duration-200" title="Cetak">
                    <i class="fas fa-print text-gray-600"></i>
                </button>
                <button id="searchButton" type="button" class="p-2 bg-white border border-gray-200 rounded-md hover:bg-gray-50 transition-all duration-200" title="Cari">
                    <i class="fas fa-search text-gray-600"></i>
                </button>
            </div>
            <button id="refreshButton" type="button" class="p-2 bg-white border border-gray-200 rounded-md hover:bg-gray-50 transition-all duration-200" title="Refresh">
                <i class="fas fa-sync-alt text-gray-600"></i>
            </button>
        </div>
    </div>

    <!-- Header sederhana -->
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-gray-800">Define Paper Flute</h1>
        <div class="h-1 w-20 bg-blue-500 mt-2 rounded-full"></div>
    </div>

    <!-- Card utama minimalis -->
    <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100">
        <!-- Form pencarian minimalis -->
        <div class="mb-6 flex items-center">
            <label for="paperFluteSearch" class="mr-4 w-32 font-medium text-gray-700">Paper Flute:</label>
            <div class="relative flex-1">
                <input type="text" id="paperFluteSearch" class="border border-gray-300 p-2 rounded-md w-full focus:ring-2 focus:ring-blue-400/30 focus:border-blue-400 transition-all duration-200" placeholder="Pilih paper flute..." readonly>
                <button id="openModalButton" type="button" class="absolute right-1 top-1 p-1.5 bg-gray-100 text-gray-700 border-0 rounded-md hover:bg-gray-200 transition-all duration-200">
                    <i class="fas fa-ellipsis-h"></i>
                </button>
            </div>
        </div>

        <!-- Tabel minimalis -->
        <div id="flutesTable" class="mt-6 hidden animate__animated animate__fadeIn">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-semibold text-gray-800">
                    Daftar Paper Flute
                </h2>
                <div class="bg-blue-500 text-white text-xs font-medium px-3 py-1 rounded-md">
                    Data Terpilih
                </div>
            </div>
            <div class="overflow-hidden rounded-md border border-gray-200">
                <table class="min-w-full bg-white">
                    <thead>
                        <tr class="bg-gray-50 text-gray-600">
                            <th class="py-3 px-4 border-b border-gray-200 text-left font-medium text-xs uppercase">Kode</th>
                            <th class="py-3 px-4 border-b border-gray-200 text-left font-medium text-xs uppercase">Nama</th>
                            <th class="py-3 px-4 border-b border-gray-200 text-left font-medium text-xs uppercase">Deskripsi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($paperFlutes as $flute)
                        <tr class="hover:bg-gray-50 border-b border-gray-100 transition-all duration-200 cursor-pointer">
                            <td class="py-3 px-4 font-medium text-blue-600">{{ $flute->code }}</td>
                            <td class="py-3 px-4">{{ $flute->name }}</td>
                            <td class="py-3 px-4 text-gray-600">{{ $flute->description }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Box untuk Paper Flute minimalis -->
<div id="paperFluteModal" class="fixed inset-0 bg-black/50 flex items-center justify-center hidden z-50 transition-all duration-200 animate__animated animate__fadeIn p-4">
    <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-2xl transform transition-all duration-200 animate__animated animate__zoomIn">
        <div class="flex justify-between items-center mb-4 pb-3 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-800">
                Pilih Paper Flute
            </h3>
            <button id="closeModalButton" class="text-gray-500 hover:text-gray-700 p-1">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <div class="mb-4 relative">
            <input type="text" id="searchModalInput" placeholder="Cari paper flute..." class="w-full p-2 pl-8 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-400/30 focus:border-blue-400">
            <i class="fas fa-search absolute left-3 top-2.5 text-gray-400"></i>
        </div>
        
        <div class="max-h-80 overflow-y-auto rounded-md border border-gray-200">
            <table class="min-w-full bg-white">
                <thead class="sticky top-0 bg-gray-50 z-10">
                    <tr>
                        <th class="py-2 px-4 border-b text-left font-medium text-xs uppercase text-gray-600">Kode</th>
                        <th class="py-2 px-4 border-b text-left font-medium text-xs uppercase text-gray-600">Nama</th>
                        <th class="py-2 px-4 border-b text-left font-medium text-xs uppercase text-gray-600 hidden sm:table-cell">Deskripsi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($paperFlutes as $flute)
                    <tr class="hover:bg-gray-50 cursor-pointer flute-row border-b border-gray-100" data-code="{{ $flute->code }}" data-name="{{ $flute->name }}">
                        <td class="py-2 px-4 font-medium text-blue-600">{{ $flute->code }}</td>
                        <td class="py-2 px-4">{{ $flute->name }}</td>
                        <td class="py-2 px-4 text-gray-600 hidden sm:table-cell">{{ $flute->description }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <div class="flex justify-end mt-4 space-x-3">
            <button id="cancelModalButton" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 transition-all duration-200">
                Batal
            </button>
            <button id="addNewFluteButton" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 transition-all duration-200">
                Tambah Baru
            </button>
        </div>
    </div>
</div>

<!-- Modal untuk Tambah/Edit Paper Flute minimalis -->
<div id="editFluteModal" class="fixed inset-0 bg-black/50 flex items-center justify-center hidden z-50 transition-all duration-200 animate__animated animate__fadeIn p-4">
    <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md transform transition-all duration-200 animate__animated animate__zoomIn">
        <div class="flex justify-between items-center mb-4 pb-3 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-800">
                <span id="modalTitle">Tambah Paper Flute Baru</span>
            </h3>
            <button id="closeEditModalButton" class="text-gray-500 hover:text-gray-700 p-1">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <form id="fluteForm" method="POST" action="{{ route('paper-flute.store') }}" class="overflow-y-auto max-h-[70vh]">
            @csrf
            <input type="hidden" id="editFluteId" name="id">
            <input type="hidden" id="methodField" name="_method" value="POST">
            
            <div class="mb-4">
                <label for="fluteCode" class="block text-sm font-medium text-gray-700 mb-1">Kode Flute</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <i class="fas fa-barcode text-gray-400"></i>
                    </div>
                    <input type="text" id="fluteCode" name="code" class="w-full pl-9 p-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-400/30 focus:border-blue-400" required>
                </div>
            </div>
            
            <div class="mb-4">
                <label for="fluteName" class="block text-sm font-medium text-gray-700 mb-1">Nama Flute</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <i class="fas fa-signature text-gray-400"></i>
                    </div>
                    <input type="text" id="fluteName" name="name" class="w-full pl-9 p-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-400/30 focus:border-blue-400" required>
                </div>
            </div>
            
            <div class="mb-5">
                <label for="fluteDescription" class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                <div class="relative">
                    <div class="absolute top-3 left-3 pointer-events-none">
                        <i class="fas fa-align-left text-gray-400"></i>
                    </div>
                    <textarea id="fluteDescription" name="description" rows="3" class="w-full pl-9 p-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-400/30 focus:border-blue-400"></textarea>
                </div>
            </div>
            
            <div class="flex justify-end space-x-3">
                <button type="button" id="cancelEditButton" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 transition-all duration-200">
                    Batal
                </button>
                <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 transition-all duration-200">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Konfirmasi Hapus minimalis -->
<div id="deleteConfirmModal" class="fixed inset-0 bg-black/50 flex items-center justify-center hidden z-50 transition-all duration-200 animate__animated animate__fadeIn p-4">
    <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md transform transition-all duration-200 animate__animated animate__zoomIn">
        <div class="mb-4 pb-3 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-800 mb-2">
                Konfirmasi Hapus
            </h3>
            <p class="text-gray-600 text-sm">Anda yakin ingin menghapus paper flute ini? Tindakan ini tidak dapat dibatalkan.</p>
        </div>
        
        <p id="deleteFluteName" class="font-medium mb-4 p-3 bg-gray-50 rounded-md text-gray-700 border border-gray-200"></p>
        
        <form id="deleteForm" method="POST">
            @csrf
            @method('DELETE')
            
            <div class="flex justify-end space-x-3">
                <button type="button" id="cancelDeleteButton" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 transition-all duration-200">
                    Batal
                </button>
                <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-600 transition-all duration-200">
                    Hapus
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Detail Paper Flute minimalis -->
<div id="fluteDetailModal" class="fixed inset-0 bg-black/50 flex items-center justify-center hidden z-50 transition-all duration-200 animate__animated animate__fadeIn p-4">
    <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-2xl transform transition-all duration-200 animate__animated animate__zoomIn">
        <div class="flex justify-between items-center mb-4 pb-3 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-800">
                Detail Paper Flute
            </h3>
            <button id="closeDetailModalButton" class="text-gray-500 hover:text-gray-700 p-1">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <form id="fluteDetailForm" class="overflow-y-auto max-h-[70vh]">
            <div class="grid grid-cols-1 gap-4 mb-5 bg-gray-50 p-4 rounded-md">
                <div class="flex flex-col sm:flex-row sm:items-center">
                    <label for="detailPaperFlute" class="w-full sm:w-32 font-medium text-gray-700 mb-1 sm:mb-0">Paper Flute:</label>
                    <select id="detailPaperFlute" class="border border-gray-300 p-2 rounded-md w-full focus:ring-2 focus:ring-blue-400/30 focus:border-blue-400">
                        <option value="All">All</option>
                        @foreach($paperFlutes as $flute)
                            <option value="{{ $flute->code }}">{{ $flute->code }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex flex-col sm:flex-row sm:items-center">
                    <label for="detailDescription" class="w-full sm:w-32 font-medium text-gray-700 mb-1 sm:mb-0">Description:</label>
                    <input type="text" id="detailDescription" class="border border-gray-300 p-2 rounded-md w-full focus:ring-2 focus:ring-blue-400/30 focus:border-blue-400" value="All">
                </div>
            </div>
            
            <div class="mb-5">
                <h4 class="font-medium text-gray-800 mb-2">Take Up Ratio</h4>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3 mb-4">
                    <div class="flex items-center bg-gray-50 p-3 rounded-md border border-gray-200">
                        <label for="layer1" class="w-16 text-sm font-medium text-gray-700">Layer 1:</label>
                        <input type="text" id="layer1" class="border border-gray-300 p-2 rounded-md w-full text-right focus:ring-2 focus:ring-blue-400/30 focus:border-blue-400" value="1.00">
                        <span class="ml-2 text-gray-700 bg-gray-100 py-1 px-2 rounded text-xs">KL</span>
                    </div>
                    <div class="flex items-center bg-gray-50 p-3 rounded-md border border-gray-200">
                        <label for="layer2" class="w-16 text-sm font-medium text-gray-700">Layer 2:</label>
                        <input type="text" id="layer2" class="border border-gray-300 p-2 rounded-md w-full text-right focus:ring-2 focus:ring-blue-400/30 focus:border-blue-400" value="1.40">
                        <span class="ml-2 text-gray-700 bg-gray-100 py-1 px-2 rounded text-xs">B</span>
                    </div>
                    <div class="flex items-center bg-gray-50 p-3 rounded-md border border-gray-200">
                        <label for="layer3" class="w-16 text-sm font-medium text-gray-700">Layer 3:</label>
                        <input type="text" id="layer3" class="border border-gray-300 p-2 rounded-md w-full text-right focus:ring-2 focus:ring-blue-400/30 focus:border-blue-400" value="1.00">
                        <span class="ml-2 text-gray-700 bg-gray-100 py-1 px-2 rounded text-xs">L</span>
                    </div>
                    <div class="flex items-center bg-gray-50 p-3 rounded-md border border-gray-200">
                        <label for="layer4" class="w-16 text-sm font-medium text-gray-700">Layer 4:</label>
                        <input type="text" id="layer4" class="border border-gray-300 p-2 rounded-md w-full text-right focus:ring-2 focus:ring-blue-400/30 focus:border-blue-400" value="1.60">
                        <span class="ml-2 text-gray-700 bg-gray-100 py-1 px-2 rounded text-xs">A/C/E</span>
                    </div>
                    <div class="flex items-center bg-gray-50 p-3 rounded-md border border-gray-200">
                        <label for="layer5" class="w-16 text-sm font-medium text-gray-700">Layer 5:</label>
                        <input type="text" id="layer5" class="border border-gray-300 p-2 rounded-md w-full text-right focus:ring-2 focus:ring-blue-400/30 focus:border-blue-400" value="1.00">
                        <span class="ml-2 text-gray-700 bg-gray-100 py-1 px-2 rounded text-xs">2L</span>
                    </div>
                </div>
            </div>
            
            <div class="mb-5">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div class="flex flex-col sm:flex-row sm:items-center bg-gray-50 p-3 rounded-md border border-gray-200">
                        <label for="fluteHeight" class="w-full sm:w-28 text-sm font-medium text-gray-700 mb-1 sm:mb-0">Flute Height:</label>
                        <div class="flex items-center w-full">
                            <input type="text" id="fluteHeight" class="border border-gray-300 p-2 rounded-md w-full text-right focus:ring-2 focus:ring-blue-400/30 focus:border-blue-400" value="0.00">
                            <span class="ml-2 text-gray-600 bg-gray-100 py-1 px-2 rounded text-xs whitespace-nowrap">mm</span>
                        </div>
                    </div>
                    <div class="flex flex-col sm:flex-row sm:items-center bg-gray-50 p-3 rounded-md border border-gray-200">
                        <label for="starchConsumption" class="w-full sm:w-28 text-sm font-medium text-gray-700 mb-1 sm:mb-0">Starch Cons:</label>
                        <div class="flex items-center w-full">
                            <input type="text" id="starchConsumption" class="border border-gray-300 p-2 rounded-md w-full text-right focus:ring-2 focus:ring-blue-400/30 focus:border-blue-400" value="0.00">
                            <span class="ml-2 text-gray-600 bg-gray-100 py-1 px-2 rounded text-xs whitespace-nowrap">Factor</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="mb-5">
                <div class="bg-yellow-50 p-3 rounded-md border border-yellow-100">
                    <p class="text-xs text-gray-600">
                        <span class="font-medium text-gray-700">Catatan:</span>
                        Starch Consumption (G/M²) = Area(M²) × Factor
                    </p>
                </div>
            </div>
            
            <div class="flex justify-end space-x-3">
                <button type="button" id="cancelDetailButton" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 transition-all duration-200">
                    Tutup
                </button>
                <button type="button" id="saveDetailButton" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 transition-all duration-200">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Tambahkan CSS untuk animasi -->
<style>
    .animate__animated {
        animation-duration: 0.3s;
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
        alert('Data detail flute telah disimpan');
        closeDetailModal();
    });
});
</script>
@endsection 