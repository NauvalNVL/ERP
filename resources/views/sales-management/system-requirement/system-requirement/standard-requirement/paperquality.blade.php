@extends('layouts.app')

@section('title', 'Define Paper Quality')

@section('content')
<script src="{{ asset('js/paperquality.js') }}"></script>

<!-- Header Section -->
<div class="bg-gradient-to-r from-cyan-700 to-blue-600 p-6 rounded-t-lg shadow-lg">
    <h2 class="text-2xl font-bold text-white mb-2 flex items-center">
        <i class="fas fa-scroll mr-3"></i> Define Paper Quality
    </h2>
    <p class="text-cyan-100">Definisikan jenis kualitas kertas untuk berbagai kebutuhan produksi</p>
</div>

<div class="bg-white rounded-b-lg shadow-lg p-6 mb-6">
    <!-- Main Content -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Left Column - Main Content -->
        <div class="lg:col-span-2">
            <div class="bg-white p-6 rounded-lg shadow-md border-t-4 border-blue-500">
                <div class="flex items-center mb-6 pb-2 border-b border-gray-200">
                    <div class="p-2 bg-blue-500 rounded-lg mr-3">
                        <i class="fas fa-scroll text-white"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800">Paper Quality Management</h3>
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

                <!-- Paper Quality Form -->
                <form id="paperQualityForm" method="POST" action="{{ route('paper-quality.store') }}">
                    @csrf
                    <div class="grid grid-cols-1 gap-6">
                        <!-- First Row - Code and Record Selection -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                            <div class="col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Paper Quality Code:</label>
                                <div class="relative flex">
                                    <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500">
                                        <i class="fas fa-hashtag"></i>
                                    </span>
                                    <input type="text" id="paper_quality" name="paper_quality" 
                                        class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none border border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                                    <button type="button" id="showPaperQualityTableBtn" class="inline-flex items-center px-3 py-2 border border-l-0 border-gray-300 bg-blue-500 hover:bg-blue-600 text-white rounded-r-md transition-colors transform active:translate-y-px" onclick="openPaperQualityModal()">
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

                        <!-- Paper Quality Name -->
                        <div>
                            <label for="paper_name" class="block text-sm font-medium text-gray-700 mb-1">Paper Quality Name:</label>
                            <div class="relative flex">
                                <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500">
                                    <i class="fas fa-font"></i>
                                </span>
                                <input type="text" id="paper_name" name="paper_name" placeholder="Enter paper quality name" 
                                    class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none rounded-r-md border border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                        </div>

                        <!-- Weight KG/M -->
                        <div>
                            <label for="weight_kg_m" class="block text-sm font-medium text-gray-700 mb-1">Weight (KG/M):</label>
                            <div class="relative flex">
                                <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500">
                                    <i class="fas fa-weight"></i>
                                </span>
                                <input type="number" id="weight_kg_m" name="weight_kg_m" placeholder="Enter paper weight" 
                                    min="0" step="0.0001" max="9.9999"
                                    class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none rounded-r-md border border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                        </div>

                        <!-- Commercial Code -->
                        <div>
                            <label for="commercial_code" class="block text-sm font-medium text-gray-700 mb-1">Commercial Code:</label>
                            <div class="relative flex">
                                <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500">
                                    <i class="fas fa-barcode"></i>
                                </span>
                                <input type="text" id="commercial_code" name="commercial_code" placeholder="Enter commercial code" 
                                    class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none rounded-r-md border border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                        </div>

                        <!-- Wet End Code -->
                        <div>
                            <label for="wet_end_code" class="block text-sm font-medium text-gray-700 mb-1">Wet-End Code:</label>
                            <div class="relative flex">
                                <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500">
                                    <i class="fas fa-code"></i>
                                </span>
                                <input type="text" id="wet_end_code" name="wet_end_code" placeholder="Enter wet-end code" 
                                    class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none rounded-r-md border border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                        </div>

                        <!-- DECC Code -->
                        <div>
                            <label for="decc_code" class="block text-sm font-medium text-gray-700 mb-1">DECC Code:</label>
                            <div class="relative flex">
                                <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500">
                                    <i class="fas fa-tag"></i>
                                </span>
                                <input type="text" id="decc_code" name="decc_code" placeholder="Enter DECC code" 
                                    class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none rounded-r-md border border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                        </div>

                        <!-- Status -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Status:</label>
                            <div class="grid grid-cols-2 gap-4">
                                <label class="relative flex items-center p-3 rounded-lg border border-gray-200 hover:border-blue-500 cursor-pointer transition-colors">
                                    <input type="radio" name="status" value="Act" class="h-4 w-4 text-blue-600 focus:ring-blue-500" checked>
                                    <span class="ml-3 text-gray-900">Active (Act)</span>
                                </label>
                                <label class="relative flex items-center p-3 rounded-lg border border-gray-200 hover:border-blue-500 cursor-pointer transition-colors">
                                    <input type="radio" name="status" value="Obs" class="h-4 w-4 text-blue-600 focus:ring-blue-500">
                                    <span class="ml-3 text-gray-900">Obsolete (Obs)</span>
                                </label>
                                <input type="hidden" name="is_active" id="is_active" value="1">
                            </div>
                        </div>
                    </div>
                </form>

                <!-- Debug Information -->
                <div class="mt-6">
                    @if(empty($paperQualities) || count($paperQualities) === 0)
                    <div class="mt-4 bg-yellow-100 p-3 rounded">
                        <p class="text-sm font-medium text-yellow-800">Tidak ada data kualitas kertas yang tersedia.</p>
                        <p class="text-xs text-yellow-700 mt-1">Pastikan database telah diatur dengan benar dan data seeder telah dijalankan.</p>
                        <div class="mt-2 flex items-center space-x-3">
                            <button onclick="loadSeedData()" class="bg-green-500 hover:bg-green-600 text-white text-xs px-3 py-1 rounded transform active:translate-y-px">
                                Load Paper Quality Data
                            </button>
                        </div>
                    </div>
                    @elseif(isset($paperQualities) && count($paperQualities) > 0)
                    <div class="mt-4 bg-green-100 p-3 rounded">
                        <p class="text-sm font-medium text-green-800">Data tersedia: {{ count($paperQualities) }} kualitas kertas ditemukan.</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Right Column - Quick Info -->
        <div class="lg:col-span-1">
            <!-- Paper Quality Info Card -->
            <div class="bg-white p-6 rounded-lg shadow-md border-t-4 border-teal-500 mb-6">
                <div class="flex items-center mb-4 pb-2 border-b border-gray-200">
                    <div class="p-2 bg-teal-500 rounded-lg mr-3">
                        <i class="fas fa-info-circle text-white"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800">Info Kualitas Kertas</h3>
                </div>

                <div class="space-y-4">
                    <div class="p-4 bg-teal-50 rounded-lg">
                        <h4 class="text-sm font-semibold text-teal-800 uppercase tracking-wider mb-2">Petunjuk</h4>
                        <ul class="list-disc pl-5 text-sm text-gray-600 space-y-1">
                            <li>Kode kualitas kertas harus unik</li>
                            <li>GSM adalah ukuran gramature (g/m²)</li>
                            <li>Gunakan status inactive untuk jenis kertas yang tidak digunakan lagi</li>
                            <li>Perubahan apa pun harus disimpan</li>
                        </ul>
                    </div>

                    <div class="p-4 bg-blue-50 rounded-lg">
                        <h4 class="text-sm font-semibold text-blue-800 uppercase tracking-wider mb-2">Jenis Kertas</h4>
                        <div class="grid grid-cols-2 gap-2 text-sm">
                            <div class="flex items-center">
                                <span class="w-6 h-6 flex items-center justify-center bg-blue-500 text-white rounded-full font-bold mr-2">L</span>
                                <span>Liner</span>
                            </div>
                            <div class="flex items-center">
                                <span class="w-6 h-6 flex items-center justify-center bg-blue-500 text-white rounded-full font-bold mr-2">M</span>
                                <span>Medium</span>
                            </div>
                            <div class="flex items-center">
                                <span class="w-6 h-6 flex items-center justify-center bg-blue-500 text-white rounded-full font-bold mr-2">K</span>
                                <span>Kraft</span>
                            </div>
                            <div class="flex items-center">
                                <span class="w-6 h-6 flex items-center justify-center bg-blue-500 text-white rounded-full font-bold mr-2">C</span>
                                <span>Chipboard</span>
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
                    <a href="{{ route('paper-quality.view-print') }}" class="flex items-center p-3 bg-purple-50 rounded-lg hover:bg-purple-100 transition-colors">
                        <div class="p-2 bg-purple-500 rounded-full mr-3">
                            <i class="fas fa-print text-white text-sm"></i>
                        </div>
                        <div>
                            <p class="font-medium text-purple-900">Print Paper Quality</p>
                            <p class="text-xs text-purple-700">Cetak daftar kualitas kertas</p>
                        </div>
                    </a>

                    <a href="{{ route('paper-flute.index') }}" class="flex items-center p-3 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                        <div class="p-2 bg-blue-500 rounded-full mr-3">
                            <i class="fas fa-layer-group text-white text-sm"></i>
                        </div>
                        <div>
                            <p class="font-medium text-blue-900">Paper Flute</p>
                            <p class="text-xs text-blue-700">Kelola flute kertas</p>
                        </div>
                    </a>

                    <a href="{{ route('paper-size.index') }}" class="flex items-center p-3 bg-green-50 rounded-lg hover:bg-green-100 transition-colors">
                        <div class="p-2 bg-green-500 rounded-full mr-3">
                            <i class="fas fa-ruler-combined text-white text-sm"></i>
                        </div>
                        <div>
                            <p class="font-medium text-green-900">Paper Size</p>
                            <p class="text-xs text-green-700">Kelola ukuran kertas</p>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Paper Quality Table Modal -->
<div id="paperQualityTableWindow" class="hidden fixed inset-0 z-50 bg-black bg-opacity-50 flex items-center justify-center">
    <div class="relative bg-white rounded-lg max-w-5xl w-full mx-auto shadow-xl">
        <!-- Header -->
        <div class="flex items-center justify-between p-4 border-b border-gray-200 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-t-lg">
            <div class="flex items-center">
                <div class="p-2 bg-white bg-opacity-30 rounded-lg mr-3">
                    <i class="fas fa-scroll"></i>
                </div>
                <h3 class="text-xl font-semibold">Paper Quality Table</h3>
            </div>
            <button type="button" onclick="closeModalX()" class="text-white hover:text-gray-200 focus:outline-none transform active:translate-y-px">
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
                    <input type="text" id="searchPaperQualityInput" placeholder="Search paper quality..."
                        class="pl-10 pr-4 py-2 w-full border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 bg-gray-50">
                </div>
            </div>
            
            <div class="overflow-y-auto max-h-80">
                <table class="min-w-full divide-y divide-gray-200" id="paperQualityDataTable">
                    <thead class="bg-gray-50 sticky top-0">
                        <tr>
                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Paper Quality
                            </th>
                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Paper Name
                            </th>
                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Weight KG/M
                            </th>
                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Commercial
                            </th>
                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Wet-End
                            </th>
                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                DECC
                            </th>
                            <th class="px-3 py-2 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status
                            </th>
                            <th class="px-3 py-2 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Action
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200" id="paperQualityTableBody">
                        @if(isset($paperQualities) && count($paperQualities) > 0)
                            @foreach($paperQualities as $quality)
                                <tr class="hover:bg-blue-50 cursor-pointer" 
                                    data-quality-id="{{ $quality->id }}"
                                    data-paper-quality="{{ $quality->paper_quality }}"
                                    data-paper-name="{{ $quality->paper_name }}"
                                    data-weight-kg-m="{{ $quality->weight_kg_m }}"
                                    data-commercial-code="{{ $quality->commercial_code }}"
                                    data-wet-end-code="{{ $quality->wet_end_code }}"
                                    data-decc-code="{{ $quality->decc_code }}"
                                    data-status="{{ $quality->status }}"
                                    data-is-active="{{ $quality->is_active ? 'true' : 'false' }}"
                                    onclick="selectRow(this)"
                                    ondblclick="selectAndClosePaperQualityModal(this)">
                                    <td class="px-3 py-2 whitespace-nowrap text-sm font-medium text-gray-900">{{ $quality->paper_quality }}</td>
                                    <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-700">{{ $quality->paper_name }}</td>
                                    <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-700">{{ $quality->weight_kg_m }}</td>
                                    <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-700">{{ $quality->commercial_code }}</td>
                                    <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-700">{{ $quality->wet_end_code }}</td>
                                    <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-700">{{ $quality->decc_code }}</td>
                                    <td class="px-3 py-2 whitespace-nowrap text-sm text-center">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $quality->status == 'Act' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $quality->status }}
                                        </span>
                                    </td>
                                    <td class="px-3 py-2 whitespace-nowrap text-sm text-center">
                                        <button class="text-blue-600 hover:text-blue-900" onclick="editPaperQuality('{{ $quality->id }}')">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="8" class="px-3 py-6 text-center text-sm text-gray-500">
                                    Tidak ada data kualitas kertas yang tersedia.
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
            
            <div class="mt-4 flex items-center justify-end space-x-3">
                <button type="button" onclick="closeModal()" class="px-4 py-2 bg-gray-300 text-gray-800 rounded hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-opacity-50 transition-colors">
                    Cancel
                </button>
                <button type="button" onclick="createNewPaperQuality()" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 transition-colors">
                    <i class="fas fa-plus mr-2"></i> Create New
                </button>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script>
    // Add any additional scripts that are not in the JS file
</script>
@endsection

@endsection
