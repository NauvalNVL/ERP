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
                        <button id="searchFinishingBtn" class="flex items-center justify-center px-4 border border-l-0 border-gray-300 bg-gray-50 rounded-r-md hover:bg-gray-100 transition-colors duration-200">
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

    <!-- Finishing Modal -->
    <div id="finishingModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="finishingModalLabel" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <!-- Background overlay -->
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity modal-backdrop"></div>
            
            <!-- Modal panel -->
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <!-- Modal header -->
                <div class="bg-gradient-to-r from-blue-500 to-indigo-600 px-4 py-3 sm:px-6 flex items-center justify-between">
                    <h3 class="text-lg leading-6 font-medium text-white" id="finishingModalLabel">Finishing Table</h3>
                    <button type="button" class="bg-transparent rounded-md text-white hover:text-gray-200 focus:outline-none" id="closeModalBtn">
                        <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                
                <!-- Modal body -->
                <div class="overflow-hidden">
                    <div class="max-h-60 overflow-y-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="w-1/3 px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Code</th>
                                    <th scope="col" class="w-2/3 px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($finishings as $finishing)
                                <tr class="finishing-row hover:bg-gray-50 transition-colors duration-150" data-code="{{ $finishing->code }}" data-description="{{ $finishing->description }}">
                                    <td class="px-6 py-2 whitespace-nowrap text-sm font-medium text-gray-900">{{ $finishing->code }}</td>
                                    <td class="px-6 py-2 whitespace-nowrap text-sm text-gray-500">{{ $finishing->description }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <!-- Modal footer -->
                <div class="bg-gray-50 px-4 py-3 sm:px-6 flex justify-center space-x-3">
                    <button type="button" class="px-4 py-2 bg-indigo-600 text-white rounded-md shadow-sm hover:bg-indigo-700 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" id="modalSelectBtn">Select</button>
                    <button type="button" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md shadow-sm hover:bg-gray-400 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500" id="modalExitBtn">Exit</button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        let selectedRow = null;
        const modal = document.getElementById('finishingModal');
        
        // Fungsi untuk membuka modal
        function openModal() {
            modal.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        }
        
        // Fungsi untuk menutup modal
        function closeModal() {
            modal.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }
        
        // Buka modal ketika tombol pencarian diklik
        $('#searchFinishingBtn').click(function() {
            openModal();
        });
        
        // Tutup modal dengan tombol close
        $('#closeModalBtn, #modalExitBtn').click(function() {
            closeModal();
        });
        
        // Tutup modal ketika backdrop diklik
        $('.modal-backdrop').click(function() {
            closeModal();
        });
        
        // Mencegah modal tertutup ketika mengklik pada modal content
        $('.modal-content').click(function(e) {
            e.stopPropagation();
        });

        // Ketika baris dipilih
        $('.finishing-row').click(function() {
            $('.finishing-row').removeClass('selected bg-blue-100');
            $(this).addClass('selected bg-blue-100');
            selectedRow = $(this);
        });

        // Double click pada baris untuk select dan langsung tutup modal
        $('.finishing-row').dblclick(function() {
            selectedRow = $(this);
            const code = selectedRow.data('code');
            $('#finishingCode').val(code);
            closeModal();
        });

        // Ketika tombol select pada modal ditekan
        $('#modalSelectBtn').click(function() {
            if (selectedRow) {
                const code = selectedRow.data('code');
                $('#finishingCode').val(code);
                closeModal();
            } else {
                alert('Silahkan pilih finishing terlebih dahulu');
            }
        });

        // Ketika tombol select di luar modal ditekan
        $('#selectBtn').click(function() {
            if ($('#finishingCode').val()) {
                // Proses pemilihan finishing
                alert('Finishing dengan kode ' + $('#finishingCode').val() + ' telah dipilih');
            } else {
                alert('Silahkan pilih finishing terlebih dahulu');
                openModal();
            }
        });

        // Ketika tombol recent ditekan
        $('#recentBtn').click(function() {
            // Fungsi untuk menampilkan finishing yang terakhir digunakan
            alert('Menampilkan finishing yang terakhir digunakan');
        });

        // Reset seleksi saat modal dibuka
        $(document).on('modal:open', function() {
            $('.finishing-row').removeClass('selected bg-blue-100');
            selectedRow = null;
        });
        
        // Shortcut keyboard (Escape untuk menutup modal)
        $(document).keydown(function(e) {
            if (e.key === "Escape" && !modal.classList.contains('hidden')) {
                closeModal();
            }
        });
    });
</script>
@endpush

<style>
    .finishing-row {
        cursor: pointer;
    }
    .finishing-row.selected {
        @apply bg-blue-600 text-white;
    }
    .modal-backdrop {
        backdrop-filter: blur(1px);
    }
</style>
@endsection
