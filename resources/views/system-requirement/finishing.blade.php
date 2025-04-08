@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="bg-gradient-to-r from-blue-500 to-indigo-600 px-6 py-4">
            <h5 class="text-xl font-semibold text-white">Define Finishing</h5>
        </div>
        <div class="p-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
                <div class="flex flex-col sm:flex-row sm:items-center space-y-2 sm:space-y-0">
                    <label for="finishingCode" class="text-gray-700 font-medium sm:w-32 sm:mr-4">Finishing Code:</label>
                    <div class="flex w-full sm:w-64">
                        <input type="text" id="finishingCode" class="w-full rounded-l-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" readonly>
                        <button type="button" id="searchFinishingBtn" class="flex items-center justify-center px-4 border border-l-0 border-gray-300 bg-gray-50 rounded-r-md hover:bg-gray-100 transition-colors duration-200">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div>
                </div>
                <div class="flex space-x-3">
                    <button id="recentBtn" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md shadow-sm hover:bg-gray-300 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">Recent</button>
                    <button id="selectBtn" class="px-4 py-2 bg-indigo-600 text-white rounded-md shadow-sm hover:bg-indigo-700 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Select</button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Modal Finishing -->
    <div id="finishingModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5); z-index: 1000;">
        <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 100%; max-width: 500px; background: white; border-radius: 0.5rem; overflow: hidden; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);">
            <!-- Header Modal -->
            <div style="background: linear-gradient(to right, #3b82f6, #4f46e5); padding: 1rem; display: flex; justify-content: space-between; align-items: center;">
                <h3 style="color: white; font-size: 1.25rem; font-weight: 500; margin: 0;">Finishing Table</h3>
                <button onclick="closeFinishingModal()" style="background: transparent; border: none; color: white; cursor: pointer;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
            </div>
            
            <!-- Body Modal -->
            <div style="max-height: 300px; overflow-y: auto;">
                <table style="width: 100%; border-collapse: collapse;">
                    <thead style="background-color: #f9fafb; position: sticky; top: 0;">
                        <tr>
                            <th style="width: 30%; padding: 0.75rem 1.5rem; text-align: left; font-size: 0.75rem; font-weight: 500; color: #6b7280; text-transform: uppercase; border-bottom: 1px solid #e5e7eb;">Code</th>
                            <th style="width: 70%; padding: 0.75rem 1.5rem; text-align: left; font-size: 0.75rem; font-weight: 500; color: #6b7280; text-transform: uppercase; border-bottom: 1px solid #e5e7eb;">Description</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($finishings as $finishing)
                        <tr onclick="selectFinishingItem('{{ $finishing->code }}')" style="cursor: pointer; transition: background-color 0.2s;" onmouseover="this.style.backgroundColor='#f3f4f6'" onmouseout="this.style.backgroundColor='white'">
                            <td style="padding: 0.5rem 1.5rem; border-bottom: 1px solid #e5e7eb; font-weight: 500;">{{ $finishing->code }}</td>
                            <td style="padding: 0.5rem 1.5rem; border-bottom: 1px solid #e5e7eb; color: #6b7280;">{{ $finishing->description }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Footer Modal -->
            <div style="background-color: #f9fafb; padding: 0.75rem 1rem; display: flex; justify-content: center; gap: 0.75rem; border-top: 1px solid #e5e7eb;">
                <button onclick="closeFinishingModal()" style="background-color: #4f46e5; color: white; padding: 0.5rem 1rem; border-radius: 0.375rem; border: none; font-weight: 500; cursor: pointer;">Select</button>
                <button onclick="closeFinishingModal()" style="background-color: #e5e7eb; color: #374151; padding: 0.5rem 1rem; border-radius: 0.375rem; border: none; font-weight: 500; cursor: pointer;">Exit</button>
            </div>
        </div>
    </div>
</div>

<script>
    // Memastikan script berjalan setelah dokumen dimuat
    document.addEventListener('DOMContentLoaded', function() {
        console.log('DOM fully loaded');
        
        // Inisialisasi elemen-elemen yang dibutuhkan
        var searchBtn = document.getElementById('searchFinishingBtn');
        var modal = document.getElementById('finishingModal');
        var finishingCode = document.getElementById('finishingCode');
        var selectBtn = document.getElementById('selectBtn');
        var recentBtn = document.getElementById('recentBtn');
        
        // Menambahkan event listener untuk tombol search
        if (searchBtn) {
            searchBtn.addEventListener('click', function() {
                console.log('Search button clicked');
                openFinishingModal();
            });
        }
        
        // Menambahkan event listener untuk tombol Select
        if (selectBtn) {
            selectBtn.addEventListener('click', function() {
                if (finishingCode.value) {
                    alert('Finishing dengan kode ' + finishingCode.value + ' telah dipilih');
                } else {
                    alert('Silahkan pilih finishing terlebih dahulu');
                    openFinishingModal();
                }
            });
        }
        
        // Menambahkan event listener untuk tombol Recent
        if (recentBtn) {
            recentBtn.addEventListener('click', function() {
                alert('Menampilkan finishing yang terakhir digunakan');
            });
        }
        
        // Menambahkan event listener untuk tombol Escape
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && modal.style.display === 'block') {
                closeFinishingModal();
            }
        });
    });
    
    // Fungsi untuk membuka modal
    function openFinishingModal() {
        var modal = document.getElementById('finishingModal');
        if (modal) {
            console.log('Opening modal');
            modal.style.display = 'block';
        } else {
            console.error('Modal element not found');
        }
    }
    
    // Fungsi untuk menutup modal
    function closeFinishingModal() {
        var modal = document.getElementById('finishingModal');
        if (modal) {
            console.log('Closing modal');
            modal.style.display = 'none';
        } else {
            console.error('Modal element not found');
        }
    }
    
    // Fungsi untuk memilih item finishing
    function selectFinishingItem(code) {
        var finishingCode = document.getElementById('finishingCode');
        if (finishingCode) {
            finishingCode.value = code;
            closeFinishingModal();
        } else {
            console.error('Finishing code input not found');
        }
    }
</script>

<style>
    /* Style minimal yang diperlukan */
    .finishing-row {
        cursor: pointer;
    }
    .finishing-row:hover {
        background-color: #f0f9ff;
    }
    .finishing-row.selected {
        background-color: #3b82f6;
        color: white;
    }
</style>
@endsection
