@extends('layouts.app')

@section('title', 'Define Salesperson Team')

@section('content')
<script src="{{ asset('js/salespersonteam.js') }}"></script>

<!-- Header Section -->
<div class="bg-gradient-to-r from-cyan-700 to-blue-600 p-6 rounded-t-lg shadow-lg">
    <h2 class="text-2xl font-bold text-white mb-2 flex items-center">
        <i class="fas fa-users mr-3"></i> Define Salesperson Team
    </h2>
    <p class="text-cyan-100">Definisikan tim penjualan dan atur anggota untuk setiap tim</p>
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
                    <h3 class="text-xl font-semibold text-gray-800">Salesperson Team Management</h3>
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
                        <label class="block text-sm font-medium text-gray-700 mb-1">Salesperson Team:</label>
                        <div class="relative flex">
                            <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500">
                                <i class="fas fa-users"></i>
                            </span>
                            <input type="text" id="searchInput" class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none border border-gray-300 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            <button type="button" class="inline-flex items-center px-3 py-2 border border-l-0 border-gray-300 bg-blue-500 hover:bg-blue-600 text-white rounded-r-md transition-colors transform active:translate-y-px" onclick="searchData()">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="col-span-1">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Action:</label>
                        <button type="button" onclick="showAddModal()" class="w-full flex items-center justify-center px-4 py-2 bg-indigo-500 hover:bg-indigo-600 text-white rounded transition-colors transform active:translate-y-px">
                            <i class="fas fa-plus-circle mr-2"></i> Add New
                        </button>
                    </div>
            </div>

                <!-- Debug Information -->
                @if(empty($salespersons) || (isset($salespersons) && count($salespersons) == 0))
                <div class="mt-4 bg-yellow-100 p-3 rounded alert-danger">
                    <p class="text-sm font-medium text-yellow-800">Tidak ada data tim salesperson yang tersedia.</p>
                    <p class="text-xs text-yellow-700 mt-1">Pastikan database telah diatur dengan benar dan data seeder telah dijalankan.</p>
                    <div class="mt-2 flex items-center space-x-3">
                        <a href="{{ route('salesperson-team.index') }}" class="bg-blue-500 hover:bg-blue-600 text-white text-xs px-3 py-1 rounded transform active:translate-y-px">
                            Run Team Seeder (DB)
                        </a>
                        <button id="loadDataJsBtn" class="bg-green-500 hover:bg-green-600 text-white text-xs px-3 py-1 rounded transform active:translate-y-px">
                            Load Team Data (JS)
                </button>
            </div>
        </div>
                @elseif(isset($salespersons) && count($salespersons) > 0)
                <div class="mt-4 bg-green-100 p-3 rounded alert-success">
                    <p class="text-sm font-medium text-green-800">Data tersedia: {{ count($salespersons) }} tim salesperson ditemukan.</p>
    </div>
                @endif
                
                <!-- Salesperson Team Table -->
                <div class="mt-6 overflow-x-auto rounded-lg border border-gray-200">
                    <table class="min-w-full divide-y divide-gray-200" id="salespersonTable">
                        <thead class="bg-gray-50 sticky top-0">
                <tr>
                    <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-10">
                        <input type="checkbox" id="selectAll" class="rounded text-blue-500 focus:ring-blue-500">
                    </th>
                    <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Salesperson<br>Code
                    </th>
                    <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Salesperson<br>Name
                    </th>
                    <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        S/Team<br>Code
                    </th>
                    <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Sales Team<br>Name
                    </th>
                    <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Sales Team<br>Position
                    </th>
                </tr>
            </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @if(isset($salespersons) && count($salespersons) > 0)
                @foreach ($salespersons as $salesperson)
                <tr class="hover:bg-gray-50">
                    <td class="px-3 py-2 whitespace-nowrap">
                        <input type="checkbox" name="selected[]" value="{{ $salesperson->id }}" class="selectItem rounded text-blue-500 focus:ring-blue-500">
                    </td>
                    <td class="px-3 py-2 whitespace-nowrap font-medium">
                        {{ $salesperson->salesperson_code }}
                    </td>
                    <td class="px-3 py-2 whitespace-nowrap">
                        {{ $salesperson->salesperson_name }}
                    </td>
                    <td class="px-3 py-2 whitespace-nowrap font-medium">
                        {{ $salesperson->team_code }}
                    </td>
                    <td class="px-3 py-2 whitespace-nowrap">
                        {{ $salesperson->team_name }}
                    </td>
                    <td class="px-3 py-2 whitespace-nowrap">
                        {{ $salesperson->position }}
                    </td>
                </tr>
                @endforeach
                            @else
                                <tr>
                                    <td colspan="6" class="px-4 py-4 text-center text-gray-500">Tidak ada data salesperson team yang tersedia.</td>
                                </tr>
                            @endif
            </tbody>
        </table>
    </div>

                <!-- Action Buttons -->
                <div class="mt-4 grid grid-cols-3 gap-2">
                    <button type="button" id="editButton" onclick="showEditModal()" disabled class="py-2 px-3 bg-yellow-500 hover:bg-yellow-600 text-white text-xs rounded-lg transform active:translate-y-px opacity-50 cursor-not-allowed">
                        <i class="fas fa-edit mr-1"></i> Edit Selected
                    </button>
                    <button type="button" id="deleteButton" onclick="showDeleteModal()" disabled class="py-2 px-3 bg-red-500 hover:bg-red-600 text-white text-xs rounded-lg transform active:translate-y-px opacity-50 cursor-not-allowed">
                        <i class="fas fa-trash-alt mr-1"></i> Delete Selected
                    </button>
                    <button type="button" class="py-2 px-3 bg-gray-300 hover:bg-gray-400 text-gray-800 text-xs rounded-lg transform active:translate-y-px">
                        <i class="fas fa-print mr-1"></i> Print List
                    </button>
                </div>
            </div>
        </div>

        <!-- Right Column - Quick Info -->
        <div class="lg:col-span-1">
            <!-- Salesperson Team Info Card -->
            <div class="bg-white p-6 rounded-lg shadow-md border-t-4 border-teal-500 mb-6">
                <div class="flex items-center mb-4 pb-2 border-b border-gray-200">
                    <div class="p-2 bg-teal-500 rounded-lg mr-3">
                        <i class="fas fa-info-circle text-white"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800">Info Team</h3>
                </div>

                <div class="space-y-4">
                    <div class="p-4 bg-teal-50 rounded-lg">
                        <h4 class="text-sm font-semibold text-teal-800 uppercase tracking-wider mb-2">Petunjuk</h4>
                        <ul class="list-disc pl-5 text-sm text-gray-600 space-y-1">
                            <li>Setiap salesperson harus memiliki tim</li>
                            <li>Gunakan tombol <span class="font-medium">search</span> untuk mencari</li>
                            <li>Posisi menentukan level dalam tim</li>
                            <li>Team code harus unik dan tidak berubah</li>
                        </ul>
                    </div>

                    <div class="p-4 bg-blue-50 rounded-lg">
                        <h4 class="text-sm font-semibold text-blue-800 uppercase tracking-wider mb-2">Team Positions</h4>
                        <div class="grid grid-cols-1 gap-2 text-sm">
                            <div class="flex items-center">
                                <span class="w-6 h-6 flex items-center justify-center bg-blue-600 text-white rounded-full font-bold mr-2">M</span>
                                <span>Manager</span>
                            </div>
                            <div class="flex items-center">
                                <span class="w-6 h-6 flex items-center justify-center bg-green-600 text-white rounded-full font-bold mr-2">T</span>
                                <span>Team Leader</span>
                            </div>
                            <div class="flex items-center">
                                <span class="w-6 h-6 flex items-center justify-center bg-purple-600 text-white rounded-full font-bold mr-2">E</span>
                                <span>Executive</span>
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
                            <p class="font-medium text-purple-900">Teams</p>
                            <p class="text-xs text-purple-700">Kelola tim penjualan</p>
                        </div>
                    </a>

                    <a href="#" class="flex items-center p-3 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                        <div class="p-2 bg-blue-500 rounded-full mr-3">
                            <i class="fas fa-user-tie text-white text-sm"></i>
                        </div>
                        <div>
                            <p class="font-medium text-blue-900">Salespersons</p>
                            <p class="text-xs text-blue-700">Kelola salesperson</p>
                        </div>
                    </a>

                    <a href="#" class="flex items-center p-3 bg-green-50 rounded-lg hover:bg-green-100 transition-colors">
                        <div class="p-2 bg-green-500 rounded-full mr-3">
                            <i class="fas fa-print text-white text-sm"></i>
                        </div>
                        <div>
                            <p class="font-medium text-green-900">Cetak Daftar</p>
                            <p class="text-xs text-green-700">Cetak daftar tim</p>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Modal -->
<div id="addModal" class="hidden fixed inset-0 z-50 bg-black bg-opacity-50 flex items-center justify-center">
    <div class="bg-white rounded-lg shadow-xl w-11/12 md:w-2/5 max-w-md mx-auto transform transition-transform duration-300">
        <div class="flex items-center justify-between p-4 border-b border-gray-200 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-t-lg">
            <div class="flex items-center">
                <div class="p-2 bg-white bg-opacity-30 rounded-lg mr-3">
                    <i class="fas fa-users"></i>
                </div>
                <h3 class="text-xl font-semibold">Add Salesperson Team</h3>
            </div>
            <button type="button" onclick="hideAddModal()" class="text-white hover:text-gray-200 focus:outline-none transform active:translate-y-px">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <form action="{{ route('salesperson-team.store') }}" method="POST">
            @csrf
            <div class="p-4">
                <div class="mb-3">
                    <label for="salesperson_code" class="block text-sm font-medium text-gray-700 mb-1">Salesperson Code</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
                            <i class="fas fa-hashtag"></i>
                        </span>
                        <input type="text" name="salesperson_code" id="salesperson_code" required maxlength="10" class="pl-10 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm">
                    </div>
                </div>
                <div class="mb-3">
                    <label for="salesperson_name" class="block text-sm font-medium text-gray-700 mb-1">Salesperson Name</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
                            <i class="fas fa-user-tie"></i>
                        </span>
                        <input type="text" name="salesperson_name" id="salesperson_name" required class="pl-10 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm">
                    </div>
                </div>
                <div class="mb-3">
                    <label for="sales_team_id" class="block text-sm font-medium text-gray-700 mb-1">Sales Team</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
                            <i class="fas fa-users"></i>
                        </span>
                        <select name="sales_team_id" id="sales_team_id" required class="pl-10 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm">
                        <option value="">Pilih Sales Team</option>
                        @foreach ($salesTeams as $team)
                        <option value="{{ $team->id }}">{{ $team->code }} - {{ $team->name }}</option>
                        @endforeach
                    </select>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="position" class="block text-sm font-medium text-gray-700 mb-1">Position</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
                            <i class="fas fa-briefcase"></i>
                        </span>
                        <select name="position" id="position" required class="pl-10 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm">
                        <option value="">Pilih Position</option>
                        <option value="E - Executive">E - Executive</option>
                        <option value="M - Manager">M - Manager</option>
                        <option value="T - Team Leader">T - Team Leader</option>
                    </select>
                    </div>
                </div>
            </div>
            <div class="p-3 border-t border-gray-200 flex justify-end space-x-2 bg-gray-50">
                <button type="button" onclick="hideAddModal()" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition-colors text-sm transform active:translate-y-px">
                    <i class="fas fa-times mr-2"></i>Cancel
                </button>
                <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors text-sm transform active:translate-y-px">
                    <i class="fas fa-save mr-2"></i>Save
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Modal -->
<div id="editModal" class="hidden fixed inset-0 z-50 bg-black bg-opacity-50 flex items-center justify-center">
    <div class="bg-white rounded-lg shadow-xl w-11/12 md:w-2/5 max-w-md mx-auto transform transition-transform duration-300">
        <div class="flex items-center justify-between p-4 border-b border-gray-200 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-t-lg">
            <div class="flex items-center">
                <div class="p-2 bg-white bg-opacity-30 rounded-lg mr-3">
                    <i class="fas fa-users"></i>
                </div>
                <h3 class="text-xl font-semibold">Edit Salesperson Team</h3>
            </div>
            <button type="button" onclick="hideEditModal()" class="text-white hover:text-gray-200 focus:outline-none transform active:translate-y-px">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <form id="editForm" method="POST">
            @csrf
            @method('PUT')
            <div class="p-4">
                <div class="mb-3">
                    <label for="edit_salesperson_code" class="block text-sm font-medium text-gray-700 mb-1">Salesperson Code</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
                            <i class="fas fa-hashtag"></i>
                        </span>
                        <input type="text" id="edit_salesperson_code" class="pl-10 block w-full rounded-md border-gray-300 bg-gray-100 shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm" readonly>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="edit_salesperson_name" class="block text-sm font-medium text-gray-700 mb-1">Salesperson Name</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
                            <i class="fas fa-user-tie"></i>
                        </span>
                        <input type="text" name="salesperson_name" id="edit_salesperson_name" required class="pl-10 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm">
                    </div>
                </div>
                <div class="mb-3">
                    <label for="edit_sales_team_id" class="block text-sm font-medium text-gray-700 mb-1">Sales Team</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
                            <i class="fas fa-users"></i>
                        </span>
                        <select name="sales_team_id" id="edit_sales_team_id" required class="pl-10 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm">
                        <option value="">Pilih Sales Team</option>
                        @foreach ($salesTeams as $team)
                        <option value="{{ $team->id }}">{{ $team->code }} - {{ $team->name }}</option>
                        @endforeach
                    </select>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="edit_position" class="block text-sm font-medium text-gray-700 mb-1">Position</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
                            <i class="fas fa-briefcase"></i>
                        </span>
                        <select name="position" id="edit_position" required class="pl-10 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm">
                        <option value="">Pilih Position</option>
                        <option value="E - Executive">E - Executive</option>
                        <option value="M - Manager">M - Manager</option>
                        <option value="T - Team Leader">T - Team Leader</option>
                    </select>
                    </div>
                </div>
            </div>
            <div class="p-3 border-t border-gray-200 flex justify-end space-x-2 bg-gray-50">
                <button type="button" onclick="hideEditModal()" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition-colors text-sm transform active:translate-y-px">
                    <i class="fas fa-times mr-2"></i>Cancel
                </button>
                <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors text-sm transform active:translate-y-px">
                    <i class="fas fa-save mr-2"></i>Save Changes
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="hidden fixed inset-0 z-50 bg-black bg-opacity-50 flex items-center justify-center">
    <div class="bg-white rounded-lg shadow-xl w-11/12 md:w-2/5 max-w-md mx-auto transform transition-transform duration-300">
        <div class="flex items-center justify-between p-4 border-b border-gray-200 bg-gradient-to-r from-red-600 to-red-700 text-white rounded-t-lg">
            <div class="flex items-center">
                <div class="p-2 bg-white bg-opacity-30 rounded-lg mr-3">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <h3 class="text-xl font-semibold">Confirm Delete</h3>
        </div>
            <button type="button" onclick="hideDeleteModal()" class="text-white hover:text-gray-200 focus:outline-none transform active:translate-y-px">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <div class="p-6">
            <p class="text-gray-700 mb-4">Are you sure you want to delete the selected salesperson team(s)? This action cannot be undone.</p>
            
            <div class="flex justify-end space-x-3">
                <button type="button" onclick="hideDeleteModal()" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition-colors text-sm transform active:translate-y-px">
                    <i class="fas fa-times mr-2"></i>Cancel
                </button>
            <form id="deleteForm" method="POST">
                @csrf
                @method('DELETE')
                    <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors text-sm transform active:translate-y-px">
                        <i class="fas fa-trash-alt mr-2"></i>Delete
                    </button>
            </form>
            </div>
        </div>
    </div>
</div>

<!-- Loading Overlay -->
<div id="loadingOverlay" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50 hidden">
    <div class="w-12 h-12 border-4 border-solid border-blue-500 border-t-transparent rounded-full animate-spin"></div>
</div>

<!-- Success/Error Alerts -->
@if(session('success'))
<div id="successAlert" class="fixed bottom-4 right-4 bg-green-500 text-white px-4 py-2 rounded shadow-lg">
    <div class="flex items-center">
        <i class="fas fa-check-circle mr-2"></i>
        <span>{{ session('success') }}</span>
    </div>
</div>
@endif

@if(session('error'))
<div id="errorAlert" class="fixed bottom-4 right-4 bg-red-500 text-white px-4 py-2 rounded shadow-lg">
    <div class="flex items-center">
        <i class="fas fa-exclamation-circle mr-2"></i>
        <span>{{ session('error') }}</span>
    </div>
</div>
@endif

@endsection
