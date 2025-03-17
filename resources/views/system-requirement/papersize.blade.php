@extends('layouts.app')

@section('title', 'Define Paper Size')

@section('content')
<style>
    [x-cloak] { display: none !important; }
</style>

<div class="container mx-auto px-4 py-5" x-data="paperSizeEditor()" x-cloak>
    <div class="bg-white rounded-lg shadow-md">
        <div class="border-b border-gray-200 px-6 py-4">
            <h1 class="text-lg font-medium text-gray-900">Define Paper Size</h1>
        </div>

        <div class="p-6">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    {{ session('error') }}
                </div>
            @endif

            @if($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ isset($paperSize) ? route('paper-size.update', $paperSize->id) : route('paper-size.store') }}" 
                  method="POST" class="mb-6">
                @csrf
                @if(isset($paperSize))
                    @method('PUT')
                @endif

                <div class="flex items-center mb-4">
                    <label for="size" class="block text-sm font-medium text-gray-700 w-24">Paper Size:</label>
                    <div class="flex items-center">
                        <div class="flex border border-gray-300 rounded-md">
                            <input type="number" step="0.01" id="size" name="size" 
                                x-model="mmValue"
                                min="0.01"
                                class="w-20 px-2 py-1 border-0 focus:ring-indigo-500 focus:border-indigo-500 text-right">
                            <button type="button" @click="openSizeTable()" class="bg-blue-500 hover:bg-blue-600 text-white px-2 py-1 border-l border-gray-300">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </button>
                        </div>
                        <span class="ml-2 text-gray-700">Millimeter</span>
                    </div>
                </div>

                <input type="hidden" id="inches" name="inches" x-model="inchValue">

                <!-- Table Modal -->
                <div x-show="showSizeTable" 
                     x-cloak
                     class="fixed inset-0 z-50 overflow-y-auto"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0">
                    
                    <!-- Backdrop -->
                    <div class="fixed inset-0 bg-black bg-opacity-50" @click="showSizeTable = false"></div>
                    
                    <!-- Modal Container -->
                    <div class="flex items-center justify-center min-h-screen px-4 py-8 text-center sm:p-0">
                        <div class="relative bg-white rounded-lg max-w-lg w-full mx-auto shadow-xl"
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                             x-transition:leave="transition ease-in duration-200"
                             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                            
                            <!-- Header -->
                            <div class="flex items-center justify-between p-4 border-b border-gray-200">
                                <h3 class="text-lg font-medium text-gray-900" id="modal-title">
                                    Pilih Ukuran Kertas
                                </h3>
                                <button type="button" @click="showSizeTable = false" class="text-gray-400 hover:text-gray-500">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                            
                            <!-- Content -->
                            <div class="py-4 px-6">
                                <div class="overflow-y-auto max-h-80">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50 sticky top-0">
                                            <tr>
                                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    No
                                                </th>
                                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Millimeters
                                                </th>
                                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Inches
                                                </th>
                                                <th class="px-3 py-2 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Pilih
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            @foreach($paperSizes as $index => $size)
                                                <tr class="hover:bg-gray-50">
                                                    <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-500">
                                                        {{ $index + 1 }}
                                                    </td>
                                                    <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900">
                                                        {{ number_format($size->size, 2) }}
                                                    </td>
                                                    <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-500">
                                                        {{ number_format($size->inches, 2) }}
                                                    </td>
                                                    <td class="px-3 py-2 whitespace-nowrap text-sm text-center">
                                                        <button type="button" 
                                                                @click="selectAndEditSize({{ $size->size }}, {{ $size->inches }})" 
                                                                class="inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs font-medium rounded text-blue-700 bg-blue-100 hover:bg-blue-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                                            Pilih
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            
                            <!-- Footer -->
                            <div class="px-4 py-3 bg-gray-50 sm:px-6 sm:flex sm:flex-row-reverse">
                                <button type="button" @click="showSizeTable = false" 
                                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                    Tutup
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End Table Modal -->

                <!-- Edit Modal - Dua input (Millimeter & Inches) -->
                <div x-show="showEditModal" 
                     x-cloak
                     class="fixed inset-0 z-50 overflow-y-auto">
                    
                    <!-- Backdrop -->
                    <div class="fixed inset-0 bg-black bg-opacity-50" @click="cancelEdit()"></div>
                    
                    <!-- Modal Container - Kotak dialog -->
                    <div class="flex items-center justify-center min-h-screen">
                        <div class="relative bg-gray-100 rounded-lg shadow-md border border-gray-300 w-80"
                             @click.away="cancelEdit()">
                            
                            <!-- Header dengan judul dan tombol close (X) -->
                            <div class="bg-gray-200 border-b border-gray-300 py-1 px-2 flex items-center justify-between">
                                <h3 class="text-xs font-medium text-gray-700">Define Paper Size</h3>
                                <button type="button" @click="cancelEdit()" class="text-gray-500 hover:text-gray-700">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                            
                            <!-- Form Fields - 2 baris input (mm dan inches) -->
                            <div class="p-3 space-y-2">
                                <!-- Input Millimeter -->
                                <div class="flex items-center">
                                    <label class="block text-xs font-medium text-gray-700 w-20">Paper Size:</label>
                                    <input type="number" step="0.01" x-model="tempMmValue" @input="updateInchesFromMm()" 
                                           class="px-2 py-1 border border-gray-300 rounded w-20 text-right bg-white text-xs">
                                    <span class="ml-2 text-xs text-gray-700">Millimeter</span>
                                </div>
                                
                                <!-- Input Inches -->
                                <div class="flex items-center">
                                    <label class="block text-xs font-medium text-gray-700 w-20">Paper Size:</label>
                                    <input type="number" step="0.01" x-model="tempInchValue" @input="updateMmFromInches()"
                                           class="px-2 py-1 border border-gray-300 rounded w-20 text-right bg-white text-xs">
                                    <span class="ml-2 text-xs text-gray-700">Inches</span>
                                </div>
                            </div>
                            
                            <!-- Footer dengan tombol OK dan Batal -->
                            <div class="flex justify-end px-3 py-2 space-x-2 border-t border-gray-300">
                                <button type="button" @click="confirmEdit()" 
                                        class="px-3 py-1 bg-gray-200 border border-gray-400 text-xs text-gray-700 font-medium rounded shadow-sm hover:bg-gray-300">
                                    OK
                                </button>
                                <button type="button" @click="cancelEdit()" 
                                        class="px-3 py-1 bg-gray-200 border border-gray-400 text-xs text-gray-700 font-medium rounded shadow-sm hover:bg-gray-300">
                                    Batal
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End Edit Modal -->

                <div class="flex space-x-2 mt-6">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                        {{ isset($paperSize) ? 'Update' : 'Save' }}
                    </button>
                    
                    @if(isset($paperSize))
                        <a href="{{ route('paper-size.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">
                            Cancel
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function paperSizeEditor() {
        return {
            showSizeTable: false,
            showEditModal: false,
            mmValue: {{ isset($paperSize) ? $paperSize->size : old('size', 0) }},
            inchValue: {{ isset($paperSize) ? $paperSize->inches : old('inches', 0) }},
            tempMmValue: 0,
            tempInchValue: 0,
            
            // Membuka tabel ukuran
            openSizeTable() {
                this.showSizeTable = true;
            },
            
            // Memilih ukuran dari tabel dan langsung menampilkan modal edit
            selectAndEditSize(mm, inch) {
                this.tempMmValue = parseFloat(mm).toFixed(2);
                this.tempInchValue = parseFloat(inch).toFixed(2);
                this.showSizeTable = false;
                
                // Menggunakan timeout untuk memastikan modal table ditutup dulu
                // sebelum modal edit ditampilkan
                setTimeout(() => {
                    this.showEditModal = true;
                }, 100);
            },
            
            // Update nilai inches saat milimeter diubah
            updateInchesFromMm() {
                if (this.tempMmValue && !isNaN(this.tempMmValue) && this.tempMmValue > 0) {
                    this.tempInchValue = (parseFloat(this.tempMmValue) / 25.4).toFixed(2);
                } else {
                    this.tempInchValue = "0.00";
                }
            },
            
            // Update nilai milimeter saat inches diubah
            updateMmFromInches() {
                if (this.tempInchValue && !isNaN(this.tempInchValue) && this.tempInchValue > 0) {
                    this.tempMmValue = (parseFloat(this.tempInchValue) * 25.4).toFixed(2);
                } else {
                    this.tempMmValue = "0.00";
                }
            },
            
            // Konfirmasi edit dan terapkan nilai baru
            confirmEdit() {
                if (this.tempMmValue && this.tempInchValue) {
                    this.mmValue = parseFloat(this.tempMmValue);
                    this.inchValue = parseFloat(this.tempInchValue);
                    this.showEditModal = false;
                } else {
                    alert('Silakan masukkan nilai yang valid');
                }
            },
            
            // Batalkan edit
            cancelEdit() {
                this.showEditModal = false;
            }
        }
    }
</script>
@endsection