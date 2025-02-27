@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6" x-data="{ showHelpTable: false }">
    <div class="bg-white rounded-lg shadow-lg p-6">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Define Customised Program</h1>
            <div class="flex space-x-2">
                <button type="submit" form="customProgramForm" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-sm transition duration-200">
                    <i class="fas fa-save mr-2"></i>Save
                </button>
                <button type="reset" form="customProgramForm" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg text-sm transition duration-200">
                    <i class="fas fa-times mr-2"></i>Cancel
                </button>
            </div>
        </div>

        <!-- Form -->
        <form id="customProgramForm" action="{{ route('customised-program.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 gap-6">
                <!-- Program ID -->
                <div class="space-y-2">
                    <label for="program_id" class="block text-sm font-medium text-gray-700">Program ID#</label>
                    <div class="flex space-x-2">
                        <input type="text" id="program_id" name="program_id"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('program_id') border-red-500 @enderror"
                            placeholder="Enter Program ID"
                            value="{{ old('program_id') }}">
                        <button type="button" class="bg-gray-200 hover:bg-gray-300 p-2 rounded-lg" id="helpButton">
                            <i class="fas fa-question-circle"></i>
                        </button>
                    </div>
                    @error('program_id')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Button to Show Help Table -->
                <div class="flex justify-end mt-4">
                    <button
                        @click.prevent="showHelpTable = true"
                        class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition duration-200"
                        id="showHelpTableButton">
                        Show Help Table
                    </button>
                </div>

                <!-- Notes -->
                <div class="bg-gray-100 p-4 rounded-lg">
                    <p class="text-sm text-gray-600">
                        <i class="fas fa-info-circle mr-2"></i>
                        User can setup program scripts using Program ID# from 500 501 1100 1150 1200 to 999 998 999 9999 9999
                    </p>
                </div>
            </div>
        </form>

        <!-- Data Table -->
        <div class="mt-8">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Program ID
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Description
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                500501
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                Sample Program
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    Active
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <button type="button" class="text-blue-600 hover:text-blue-900 mr-3">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button type="button" class="text-red-600 hover:text-red-900">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Modal for Help Table -->
        <template x-teleport="body">
            <div 
                x-show="showHelpTable"
                class="fixed inset-0 z-[999] bg-black bg-opacity-50 flex items-center justify-center"
                style="display: none"
            >
                <div 
                    class="bg-white rounded-lg p-6 w-11/12 md:w-3/4 lg:w-1/2 max-h-[80vh] overflow-y-auto"
                    @click.stop
                >
                    <h2 class="text-xl font-bold mb-4">Custom Made Help Table</h2>
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2 text-left">LEVEL</th>
                                <th class="px-4 py-2 text-left">ID #</th>
                                <th class="px-4 py-2 text-left">NAME</th>
                                <th class="px-4 py-2 text-left">TYPE</th>
                                <th class="px-4 py-2 text-left">STS</th>
                                <th class="px-4 py-2 text-left">WINDOW'S PATH</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <!-- Baris 1 -->
                            <tr class="cursor-pointer hover:bg-gray-50" 
                                @click="document.getElementById('program_id').value = 500; showHelpTable = false">
                                <td class="px-4 py-2">1</td>
                                <td class="px-4 py-2">500</td>
                                <td class="px-4 py-2">Inventory Management</td>
                                <td class="px-4 py-2">System</td>
                                <td class="px-4 py-2 text-green-500">Active</td>
                                <td class="px-4 py-2">/system/inventory</td>
                            </tr>
                            
                            <!-- Baris 2 -->
                            <tr class="cursor-pointer hover:bg-gray-50" 
                                @click="document.getElementById('program_id').value = 501; showHelpTable = false">
                                <td class="px-4 py-2">2</td>
                                <td class="px-4 py-2">501</td>
                                <td class="px-4 py-2">Sales Reporting</td>
                                <td class="px-4 py-2">Report</td>
                                <td class="px-4 py-2 text-yellow-500">Inactive</td>
                                <td class="px-4 py-2">/reports/sales</td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="mt-4 flex justify-end space-x-2">
                        <button 
                            @click="showHelpTable = false; document.getElementById('program_id').value = 500"
                            class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition duration-200"
                        >
                            Select
                        </button>
                        <button 
                            @click="showHelpTable = false" 
                            class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg transition duration-200"
                        >
                            Close
                        </button>
                    </div>
                </div>
            </div>
        </template>
    </div>
</div>

<!-- Success Message Toast -->
@if(session('success'))
<div id="successToast" class="fixed bottom-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg">
    <div class="flex items-center space-x-2">
        <i class="fas fa-check-circle"></i>
        <span>{{ session('success') }}</span>
    </div>
</div>
@endif

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
@endpush
@endsection