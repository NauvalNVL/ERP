@extends('layouts.app')

@section('title', 'Define Salesperson')

@section('content')
<script src="{{ asset('js/salesperson.js') }}"></script>

<!-- Header Section -->
<div class="bg-gradient-to-r from-cyan-700 to-blue-600 p-6 rounded-t-lg shadow-lg">
    <h2 class="text-2xl font-bold text-white mb-2 flex items-center">
        <i class="fas fa-user-tie mr-3"></i> Define Salesperson
    </h2>
    <p class="text-cyan-100">Definisikan data salesperson untuk manajemen pengguna dan tim penjualan</p>
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
                    <h3 class="text-xl font-semibold text-gray-800">Salesperson Management</h3>
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
                        <label class="block text-sm font-medium text-gray-700 mb-1">Salesperson Code:</label>
                        <div class="relative flex">
                            <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500">
                                <i class="fas fa-user-tie"></i>
                            </span>
                            <input type="text" class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none border border-gray-300 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            <button type="button" id="showSalespersonTableBtn" class="inline-flex items-center px-3 py-2 border border-l-0 border-gray-300 bg-blue-500 hover:bg-blue-600 text-white rounded-r-md transition-colors transform active:translate-y-px" onclick="openSalespersonModal()">
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

            <!-- Debug Information -->
                @if(empty($salespersons) || (isset($salespersons) && count($salespersons) == 0))
                <div class="mt-4 bg-yellow-100 p-3 rounded alert-danger">
                    <p class="text-sm font-medium text-yellow-800">Warning: No Salesperson found</p>
                    <p class="text-xs text-yellow-700 mt-1">Please ensure database tables are set up correctly and run the salesperson seeder.</p>
                    <div class="mt-2 flex items-center space-x-3">
                        <a href="{{ route('run.salesperson.seeder') }}" class="bg-blue-500 hover:bg-blue-600 text-white text-xs px-3 py-1 rounded transform active:translate-y-px">
                            Run Salesperson Seeder (DB)
                        </a>
                        <button id="loadDataJsBtn" onclick="loadSeedData()" class="bg-green-500 hover:bg-green-600 text-white text-xs px-3 py-1 rounded transform active:translate-y-px">
                            Load Salesperson Data (JS)
                        </button>
                    </div>
                </div>
                @elseif(isset($salespersons) && count($salespersons) > 0)
                <div class="mt-4 bg-green-100 p-3 rounded alert-success">
                    <p class="text-sm font-medium text-green-800">Found {{ count($salespersons) }} salespersons available</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Right Column - Quick Info -->
        <div class="lg:col-span-1">
            <!-- Salesperson Info Card -->
            <div class="bg-white p-6 rounded-lg shadow-md border-t-4 border-teal-500 mb-6">
                <div class="flex items-center mb-4 pb-2 border-b border-gray-200">
                    <div class="p-2 bg-teal-500 rounded-lg mr-3">
                        <i class="fas fa-info-circle text-white"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800">Info Salesperson</h3>
                </div>

                <div class="space-y-4">
                    <div class="p-4 bg-teal-50 rounded-lg">
                        <h4 class="text-sm font-semibold text-teal-800 uppercase tracking-wider mb-2">Petunjuk</h4>
                        <ul class="list-disc pl-5 text-sm text-gray-600 space-y-1">
                            <li>Kode salesperson harus unik</li>
                            <li>Setiap salesperson memiliki satu tim penjualan</li>
                            <li>Position menunjukkan level jabatan</li>
                            <li>User ID digunakan untuk login sistem</li>
                        </ul>
                    </div>

                    <div class="p-4 bg-blue-50 rounded-lg">
                        <h4 class="text-sm font-semibold text-blue-800 uppercase tracking-wider mb-2">Sales Teams</h4>
                        <div class="grid grid-cols-1 gap-2 text-sm">
                            <div class="flex items-center">
                                <span class="w-6 h-6 flex items-center justify-center bg-purple-600 text-white rounded-full font-bold mr-2">1</span>
                                <span>MBI</span>
                            </div>
                            <div class="flex items-center">
                                <span class="w-6 h-6 flex items-center justify-center bg-blue-600 text-white rounded-full font-bold mr-2">2</span>
                                <span>MANAGEMENT LOCAL</span>
                            </div>
                            <div class="flex items-center">
                                <span class="w-6 h-6 flex items-center justify-center bg-green-600 text-white rounded-full font-bold mr-2">3</span>
                                <span>MANAGEMENT MNC</span>
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
                            <i class="fas fa-users text-white text-sm"></i>
                        </div>
                        <div>
                            <p class="font-medium text-purple-900">Sales Teams</p>
                            <p class="text-xs text-purple-700">Kelola tim penjualan</p>
                        </div>
                    </a>

                    <a href="#" class="flex items-center p-3 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                        <div class="p-2 bg-blue-500 rounded-full mr-3">
                            <i class="fas fa-chart-line text-white text-sm"></i>
                        </div>
                        <div>
                            <p class="font-medium text-blue-900">Performance</p>
                            <p class="text-xs text-blue-700">Lihat kinerja salesperson</p>
                        </div>
                    </a>

                    <a href="#" class="flex items-center p-3 bg-green-50 rounded-lg hover:bg-green-100 transition-colors">
                        <div class="p-2 bg-green-500 rounded-full mr-3">
                            <i class="fas fa-print text-white text-sm"></i>
                        </div>
                        <div>
                            <p class="font-medium text-green-900">Cetak Daftar</p>
                            <p class="text-xs text-green-700">Cetak daftar salesperson</p>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Salesperson Table Window -->
<div id="salespersonTableWindow" class="hidden fixed inset-0 z-50 bg-black bg-opacity-50 flex items-center justify-center">
    <div class="bg-white rounded-lg shadow-xl w-11/12 md:w-3/4 lg:w-2/3 max-w-5xl mx-auto transform transition-transform duration-300" style="max-height: 80vh;">
        <!-- Modal Header - Title Bar -->
        <div class="flex items-center justify-between p-4 border-b border-gray-200 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-t-lg">
            <div class="flex items-center">
                <div class="p-2 bg-white bg-opacity-30 rounded-lg mr-3">
                    <i class="fas fa-user-tie"></i>
                </div>
                <h3 class="text-xl font-semibold">Salesperson Table</h3>
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
                    <input type="text" id="searchSalespersonInput" placeholder="Search salespersons..."
                        class="pl-10 pr-4 py-2 w-full border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 bg-gray-50">
                </div>
            </div>
            
            <div class="overflow-x-auto rounded-lg border border-gray-200">
                <table class="min-w-full divide-y divide-gray-200" id="salespersonDataTable">
                    <thead class="bg-gray-50 sticky top-0">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Code</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sales Team</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Position</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200 text-xs">
                        <!-- Data dari database akan ditampilkan di sini jika tersedia -->
                        <!-- Jika tidak ada data dari database, JavaScript akan mengisi dengan data dari SalespersonSeeder -->
                        @if(isset($salespersons) && count($salespersons) > 0)
                        @foreach($salespersons as $person)
                            <tr class="hover:bg-blue-50 cursor-pointer" 
                                data-person-code="{{ $person->code }}"
                                data-person-name="{{ $person->name }}"
                                data-person-team="{{ $person->sales_team_id }}"
                                data-person-position="{{ $person->position }}"
                                data-person-user-id="{{ $person->user_id }}"
                                data-person-is-active="{{ $person->is_active }}"
                                onclick="selectRow(this); event.stopPropagation();"
                                ondblclick="openEditSalespersonModal(this)">
                                <td class="px-4 py-3 whitespace-nowrap font-medium text-gray-900">{{ $person->code }}</td>
                                <td class="px-4 py-3 whitespace-nowrap text-gray-700">{{ $person->name }}</td>
                                <td class="px-4 py-3 whitespace-nowrap text-gray-700">
                                    @if($person->sales_team_id == 1)
                                        MBI
                                    @elseif($person->sales_team_id == 2)
                                        MANAGEMENT LOCAL
                                    @elseif($person->sales_team_id == 3)
                                        MANAGEMENT MNC
                                    @endif
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-gray-700">{{ $person->position }}</td>
                            </tr>
                        @endforeach
                        @else
                            <tr>
                                <td colspan="4" class="px-4 py-4 text-center text-gray-500">Tidak ada data salesperson yang tersedia.</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>

            <!-- Bottom Buttons -->
            <div class="mt-4 grid grid-cols-5 gap-2">
                <button type="button" onclick="sortTableDirectly(0)" class="py-2 px-3 bg-gray-100 border border-gray-400 hover:bg-gray-200 text-xs rounded-lg transform active:translate-y-px">
                    <i class="fas fa-sort mr-1"></i>By Code
                </button>
                <button type="button" onclick="sortTableDirectly(1)" class="py-2 px-3 bg-gray-100 border border-gray-400 hover:bg-gray-200 text-xs rounded-lg transform active:translate-y-px">
                    <i class="fas fa-sort mr-1"></i>By Name
                </button>
                <button type="button" onclick="sortTableDirectly(2)" class="py-2 px-3 bg-gray-100 border border-gray-400 hover:bg-gray-200 text-xs rounded-lg transform active:translate-y-px">
                    <i class="fas fa-sort mr-1"></i>By Team
                </button>
                <button type="button" onclick="editSelectedRow()" class="py-2 px-3 bg-blue-500 hover:bg-blue-600 text-white text-xs rounded-lg transform active:translate-y-px">
                    <i class="fas fa-edit mr-1"></i>Select
                </button>
                <button type="button" onclick="closeSalespersonModal()" class="py-2 px-3 bg-gray-300 hover:bg-gray-400 text-gray-800 text-xs rounded-lg transform active:translate-y-px">
                    <i class="fas fa-times mr-1"></i>Exit
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Salesperson Modal -->
<div id="editSalespersonModal" class="hidden fixed inset-0 z-50 bg-black bg-opacity-50 flex items-center justify-center">
    <div class="bg-white rounded-lg shadow-xl w-11/12 md:w-2/5 max-w-md mx-auto transform transition-transform duration-300">
        <!-- Modal Header - Title Bar -->
        <div class="flex items-center justify-between p-4 border-b border-gray-200 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-t-lg">
            <div class="flex items-center">
                <div class="p-2 bg-white bg-opacity-30 rounded-lg mr-3">
                    <i class="fas fa-user-tie"></i>
                </div>
                <h3 class="text-xl font-semibold">Define Salesperson</h3>
            </div>
            <button type="button" onclick="closeEditSalespersonModal()" class="text-white hover:text-gray-200 focus:outline-none transform active:translate-y-px">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>

        <!-- Form Content -->
        <div class="p-6">
            <form id="editSalespersonForm" onsubmit="saveSalespersonChanges(); return false;" class="space-y-4">
                <div class="grid grid-cols-1 gap-4">
                    <div>
                        <label for="edit_person_code" class="block text-sm font-medium text-gray-700 mb-1">Code:</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
                                <i class="fas fa-hashtag"></i>
                            </span>
                            <input id="edit_person_code" type="text" class="pl-10 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm" required>
                        </div>
                    </div>
                    
                    <div>
                        <label for="edit_person_name" class="block text-sm font-medium text-gray-700 mb-1">Name:</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
                                <i class="fas fa-user"></i>
                            </span>
                            <input id="edit_person_name" type="text" class="pl-10 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm" required>
                        </div>
                    </div>
                    
                    <div>
                        <label for="edit_person_team" class="block text-sm font-medium text-gray-700 mb-1">Sales Team:</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
                                <i class="fas fa-users"></i>
                            </span>
                            <select id="edit_person_team" class="pl-10 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm">
                            <option value="1">MBI</option>
                            <option value="2">MANAGEMENT LOCAL</option>
                            <option value="3">MANAGEMENT MNC</option>
                        </select>
                    </div>
                    </div>
                    
                    <div>
                        <label for="edit_person_position" class="block text-sm font-medium text-gray-700 mb-1">Position:</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
                                <i class="fas fa-briefcase"></i>
                            </span>
                            <select id="edit_person_position" class="pl-10 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm">
                            <option value="E - Executive">E - Executive</option>
                            <option value="M - Manager">M - Manager</option>
                            <option value="S - Supervisor">S - Supervisor</option>
                        </select>
                    </div>
                    </div>
                    
                    <div>
                        <label for="edit_person_user_id" class="block text-sm font-medium text-gray-700 mb-1">User ID:</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
                                <i class="fas fa-id-badge"></i>
                            </span>
                            <input id="edit_person_user_id" type="text" class="pl-10 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm">
                        </div>
                    </div>
                    
                    <div>
                        <label for="edit_person_is_active" class="block text-sm font-medium text-gray-700 mb-1">Status:</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
                                <i class="fas fa-toggle-on"></i>
                            </span>
                            <select id="edit_person_is_active" class="pl-10 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                        </div>
                    </div>
                </div>
                
                <div class="flex justify-end space-x-3 mt-6 pt-4 border-t border-gray-200">
                    <button type="button" onclick="closeEditSalespersonModal()" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition-colors text-sm transform active:translate-y-px">
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
