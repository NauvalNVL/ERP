@extends('layouts.app')

@section('title', 'Update Customer Account')

@section('content')
<!-- Header Section -->
<div class="bg-gradient-to-r from-cyan-700 to-blue-600 p-6 rounded-t-lg shadow-lg mb-0">
    <h2 class="text-2xl font-bold text-white mb-2 flex items-center">
        <i class="fas fa-user-edit mr-3"></i> Update Customer Account
    </h2>
    <p class="text-cyan-100">Perbarui data akun customer di sistem</p>
</div>

<div class="bg-white rounded-b-lg shadow-lg p-6 mb-6">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Left Column - Main Content -->
        <div class="lg:col-span-2">
            <div class="bg-white p-6 rounded-lg shadow-md border-t-4 border-blue-500">
                <div class="flex items-center mb-6 pb-2 border-b border-gray-200">
                    <div class="p-2 bg-blue-500 rounded-lg mr-3">
                        <i class="fas fa-edit text-white"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800">Customer Account Management</h3>
                </div>

                <!-- Header with navigation buttons -->
                <div class="flex items-center space-x-2 mb-6">
                    <button type="button" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded flex items-center space-x-2 transform active:translate-y-px" onclick="window.location.href='{{ route('dashboard') }}'">
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

                <!-- Form Section -->
                <form method="POST" class="space-y-6">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="customer_code" class="block text-sm font-medium text-gray-700 mb-1">Customer Code</label>
                            <div class="relative flex">
                                <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500">
                                    <i class="fas fa-id-card"></i>
                                </span>
                                <input type="text" name="customer_code" id="customer_code" required maxlength="20"
                                    class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none border border-gray-300 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                <button type="button" class="inline-flex items-center px-3 py-2 border border-l-0 border-gray-300 bg-blue-500 hover:bg-blue-600 text-white rounded-r-md transition-colors transform active:translate-y-px">
                                    <i class="fas fa-table"></i>
                                </button>
                            </div>
                        </div>
                        <div>
                            <label for="customer_name" class="block text-sm font-medium text-gray-700 mb-1">Customer Name</label>
                            <input type="text" name="customer_name" id="customer_name" maxlength="100"
                                class="block w-full px-3 py-2 rounded-md border border-gray-300 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        </div>
                    </div>

                    {{-- Container for additional fields, initially hidden --}}
                    <div id="additional-fields-container" class="space-y-6 hidden">
                        {{-- Add new fields based on the image --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="short_name" class="block text-sm font-medium text-gray-700 mb-1">Short Name</label>
                                <input type="text" name="short_name" id="short_name" maxlength="50"
                                    class="block w-full px-3 py-2 rounded-md border border-gray-300 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            </div>
                            <div>
                                <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                                <textarea name="address" id="address" rows="3"
                                    class="block w-full px-3 py-2 rounded-md border border-gray-300 focus:ring-blue-500 focus:border-blue-500 transition-colors"></textarea>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="contact_person" class="block text-sm font-medium text-gray-700 mb-1">Contact Person</label>
                                <input type="text" name="contact_person" id="contact_person" maxlength="100"
                                    class="block w-full px-3 py-2 rounded-md border border-gray-300 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            </div>
                            <div>
                                <label for="telephone_no" class="block text-sm font-medium text-gray-700 mb-1">Telephone No</label>
                                <input type="text" name="telephone_no" id="telephone_no" maxlength="30"
                                    class="block w-full px-3 py-2 rounded-md border border-gray-300 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="fax_no" class="block text-sm font-medium text-gray-700 mb-1">Fax No</label>
                                <input type="text" name="fax_no" id="fax_no" maxlength="30"
                                    class="block w-full px-3 py-2 rounded-md border border-gray-300 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            </div>
                            <div>
                                <label for="co_email" class="block text-sm font-medium text-gray-700 mb-1">Co. Email</label>
                                <input type="email" name="co_email" id="co_email" maxlength="100"
                                    class="block w-full px-3 py-2 rounded-md border border-gray-300 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-center">
                            <div>
                                <label for="credit_limit" class="block text-sm font-medium text-gray-700 mb-1">Credit Limit</label>
                                <input type="number" name="credit_limit" id="credit_limit" step="0.01"
                                    class="block w-full px-3 py-2 rounded-md border border-gray-300 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            </div>
                            <div class="flex items-center">
                                <label for="credit_terms" class="block text-sm font-medium text-gray-700 mr-2">Credit Terms:</label>
                                <input type="number" name="credit_terms" id="credit_terms" class="w-20 px-3 py-2 rounded-md border border-gray-300 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                <span class="ml-2 text-sm text-gray-700">Days</span>
                            </div>
                            <div class="flex items-center space-x-4">
                                <label class="block text-sm font-medium text-gray-700">Account Type:</label>
                                <div class="flex items-center">
                                    <input type="radio" name="ac_type" id="ac_type_foreign" value="Y-Foreign" class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300">
                                    <label for="ac_type_foreign" class="ml-2 block text-sm text-gray-900">Y-Foreign</label>
                                </div>
                                <div class="flex items-center">
                                    <input type="radio" name="ac_type" id="ac_type_local" value="N-Local" class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300">
                                    <label for="ac_type_local" class="ml-2 block text-sm text-gray-900">N-Local</label>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-center">
                            <div class="flex items-center">
                                <label for="currency_code" class="block text-sm font-medium text-gray-700 mr-2">Currency Code:</label>
                                <input type="text" name="currency_code" id="currency_code" maxlength="10"
                                    class="w-24 px-3 py-2 rounded-md border border-gray-300 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                <span class="ml-4 text-sm text-gray-700">Leave Blank if Local Account</span>
                            </div>
                            <div>
                                <label for="salesperson_code" class="block text-sm font-medium text-gray-700 mb-1">Salesperson Code:</label>
                                <div class="relative flex">
                                    <input type="text" name="salesperson_code" id="salesperson_code" maxlength="20"
                                        class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none border border-gray-300 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                    <button type="button" class="inline-flex items-center px-3 py-2 border border-l-0 border-gray-300 bg-blue-500 hover:bg-blue-600 text-white rounded-r-md transition-colors transform active:translate-y-px">
                                        <i class="fas fa-table"></i> {{-- Assuming this opens a salesperson modal --}}
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-center">
                            <div>
                                <label for="industrial_code" class="block text-sm font-medium text-gray-700 mb-1">Industrial Code:</label>
                                <div class="relative flex">
                                    <input type="text" name="industrial_code" id="industrial_code" maxlength="20"
                                        class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none border border-gray-300 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                    <button type="button" id="openIndustryModalBtn" class="inline-flex items-center px-3 py-2 border border-l-0 border-gray-300 bg-blue-500 hover:bg-blue-600 text-white rounded-r-md transition-colors transform active:translate-y-px">
                                        <i class="fas fa-table"></i> {{-- Assuming this opens an industrial modal --}}
                                    </button>
                                </div>
                            </div>
                            <div>
                                <label for="geographical" class="block text-sm font-medium text-gray-700 mb-1">Geographical:</label>
                                <div class="relative flex">
                                    <input type="text" name="geographical" id="geographical" maxlength="20"
                                        class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none border border-gray-300 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                    <button type="button" class="inline-flex items-center px-3 py-2 border border-l-0 border-gray-300 bg-blue-500 hover:bg-blue-600 text-white rounded-r-md transition-colors transform active:translate-y-px">
                                        <i class="fas fa-table"></i> {{-- Assuming this opens a geographical modal --}}
                                    </button>
                                </div>
                            </div>
                            <div>
                                <label for="grouping_code" class="block text-sm font-medium text-gray-700 mb-1">Grouping Code:</label>
                                <div class="relative flex">
                                    <input type="text" name="grouping_code" id="grouping_code" maxlength="20"
                                        class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none border border-gray-300 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                    <button type="button" class="inline-flex items-center px-3 py-2 border border-l-0 border-gray-300 bg-blue-500 hover:bg-blue-600 text-white rounded-r-md transition-colors transform active:translate-y-px">
                                        <i class="fas fa-table"></i> {{-- Assuming this opens a grouping code modal --}}
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center space-x-4">
                            <label class="block text-sm font-medium text-gray-700">Print AR Aging:</label>
                            <div class="flex items-center">
                                <input type="radio" name="print_ar_aging" id="print_ar_aging_yes" value="Y-Yes" class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300">
                                <label for="print_ar_aging_yes" class="ml-2 block text-sm text-gray-900">Y-Yes</label>
                            </div>
                            <div class="flex items-center">
                                <input type="radio" name="print_ar_aging" id="print_ar_aging_no" value="N-No" class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300">
                                <label for="print_ar_aging_no" class="ml-2 block text-sm text-gray-900">N-No</label>
                            </div>
                            <span class="ml-4 text-sm text-gray-700">[For Sales Order]</span>
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                            Save
                        </button>
                    </div>
                </form>

                <!-- Modal Customer Account Table -->
                <div id="customerAccountModal" class="fixed inset-0 z-50 bg-black bg-opacity-50 flex items-center justify-center hidden">
                    <div class="bg-white rounded-lg shadow-xl w-11/12 md:w-2/3 lg:w-3/4 max-w-4xl mx-auto">
                        <!-- Modal Header -->
                        <div class="flex items-center justify-between p-4 border-b border-gray-200 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-t-lg">
                            <h3 class="text-xl font-semibold flex items-center"><i class="fas fa-list mr-3"></i>Customer Account Table</h3>
                            <button type="button" onclick="closeCustomerAccountModal()" class="text-white hover:text-gray-200 focus:outline-none">
                                <i class="fas fa-times text-xl"></i>
                            </button>
                        </div>
                        <!-- Modal Body -->
                        <div class="p-2 overflow-y-auto" style="max-height: 60vh;">
                            <table class="min-w-full text-xs border border-gray-300" id="customerAccountTable">
                                <thead class="bg-gray-200 sticky top-0">
                                    <tr>
                                        <th class="px-2 py-1 border border-gray-300 text-left">Customer Name</th>
                                        <th class="px-2 py-1 border border-gray-300 text-left">Customer Code</th>
                                        <th class="px-2 py-1 border border-gray-300 text-left">S/person</th>
                                        <th class="px-2 py-1 border border-gray-300 text-left">AC Type</th>
                                        <th class="px-2 py-1 border border-gray-300 text-left">Currency</th>
                                        <th class="px-2 py-1 border border-gray-300 text-left">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @if(isset($accounts))
                                        @foreach($accounts as $acc)
                                        <tr class="hover:bg-blue-100 cursor-pointer modal-row" onclick="selectCustomerAccountRow(this)"
                                            data-code="{{ $acc->customer_code }}"
                                            data-name="{{ $acc->customer_name }}"
                                            data-salesperson="{{ $acc->salesperson ?? '' }}"
                                            data-actype="{{ $acc->ac_type ?? '' }}"
                                            data-currency="{{ $acc->currency ?? '' }}"
                                            data-status="{{ $acc->status ?? '' }}"
                                            {{-- Add data attributes for new fields --}}
                                            data-shortname="{{ $acc->short_name ?? '' }}"
                                            data-address="{{ $acc->address ?? '' }}"
                                            data-contactperson="{{ $acc->contact_person ?? '' }}"
                                            data-telephoneno="{{ $acc->telephone_no ?? '' }}"
                                            data-faxno="{{ $acc->fax_no ?? '' }}"
                                            data-coemail="{{ $acc->co_email ?? '' }}"
                                            data-creditlimit="{{ $acc->credit_limit ?? '0.00' }}"
                                            data-creditterms="{{ $acc->credit_terms ?? '0' }}"
                                            data-industrialcode="{{ $acc->industrial_code ?? '' }}"
                                            data-geographical="{{ $acc->geographical ?? '' }}"
                                            data-groupingcode="{{ $acc->grouping_code ?? '' }}"
                                            data-printaraging="{{ $acc->print_ar_aging ?? '' }}"
                                            >
                                            <td class="px-2 py-1 border border-gray-300 font-medium text-gray-900">{{ $acc->customer_name }}</td>
                                            <td class="px-2 py-1 border border-gray-300">{{ $acc->customer_code }}</td>
                                            <td class="px-2 py-1 border border-gray-300">{{ $acc->salesperson ?? '' }}</td>
                                            <td class="px-2 py-1 border border-gray-300">{{ $acc->ac_type ?? '' }}</td>
                                            <td class="px-2 py-1 border border-gray-300">{{ $acc->currency ?? '' }}</td>
                                            <td class="px-2 py-1 border border-gray-300">{{ $acc->status ?? '' }}</td>
                                        </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                        <!-- Modal Footer -->
                        <div class="flex items-center justify-end gap-2 p-2 border-t border-gray-200 bg-gray-100 rounded-b-lg">
                            <button type="button" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-1 px-3 rounded text-xs">More Options</button>
                            <button type="button" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-1 px-3 rounded text-xs">Zoom</button>
                            <button id="selectCustomerAccountBtn" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-3 rounded text-xs" disabled>Select</button>
                            <button type="button" onclick="closeCustomerAccountModal()" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-1 px-3 rounded text-xs">Exit</button>
                        </div>
                    </div>
                </div>

                {{-- Salesperson Table Modal --}}
                <div id="salespersonTableModal" class="hidden fixed inset-0 z-50 bg-black bg-opacity-50 items-center justify-center">
                    <div class="bg-white rounded-lg shadow-xl w-11/12 md:w-2/3 lg:w-1/2 max-w-2xl mx-auto">
                        <!-- Modal Header - Title Bar -->
                        <div class="flex items-center justify-between p-4 border-b border-gray-200 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-t-lg">
                            <div class="flex items-center">
                                <div class="p-2 bg-white bg-opacity-30 rounded-lg mr-3">
                                    <i class="fas fa-user-tie"></i>
                                </div>
                                <h3 class="text-xl font-semibold">Salesperson Table</h3>
                            </div>
                            <button type="button" onclick="closeSalespersonModal()" class="text-white hover:text-gray-200 focus:outline-none transform active:translate-y-px">
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
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Team</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Position</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        {{-- Data will be loaded here via JavaScript/Ajax --}}
                                        {{-- Example row structure (replace with actual data loop if rendering server-side) --}}
                                        {{--
                                        <tr class="hover:bg-blue-50 cursor-pointer modal-row" data-person-code="S111" data-person-name="ABDULLAH, BPK">
                                            <td class="px-4 py-3 whitespace-nowrap font-medium text-gray-900">S111</td>
                                            <td class="px-4 py-3 whitespace-nowrap text-gray-700">ABDULLAH, BPK</td>
                                            <td class="px-4 py-3 whitespace-nowrap text-gray-700">Team A</td>
                                            <td class="px-4 py-3 whitespace-nowrap text-gray-700">Salesman</td>
                                        </tr>
                                        --}}
                                    </tbody>
                                </table>
                            </div>

                            <!-- Bottom Buttons -->
                            <div class="mt-4 grid grid-cols-4 gap-2">
                                {{-- Sort buttons will be handled by DataTables or custom JS if needed --}}
                                <button type="button" class="py-2 px-3 bg-blue-500 hover:bg-blue-600 text-white text-xs rounded-lg transform active:translate-y-px" id="selectSalespersonBtn">
                                    <i class="fas fa-edit mr-1"></i>Select
                                </button>
                                <button type="button" onclick="closeSalespersonModal()" class="py-2 px-3 bg-gray-300 hover:bg-gray-400 text-gray-800 text-xs rounded-lg transform active:translate-y-px">
                                    <i class="fas fa-times mr-1"></i>Exit
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Industry Table Modal --}}
                <div id="industryTableModal" class="hidden fixed inset-0 z-50 bg-black bg-opacity-50 flex items-center justify-center">
                    <div class="bg-white rounded-lg shadow-xl w-11/12 md:w-3/4 lg:w-2/3 max-w-5xl mx-auto" style="max-height: 80vh;">
                        <!-- Modal Header - Title Bar -->
                        <div class="flex items-center justify-between p-4 border-b border-gray-200 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-t-lg">
                            <div class="flex items-center">
                                <div class="p-2 bg-white bg-opacity-30 rounded-lg mr-3">
                                    <i class="fas fa-industry"></i>
                                </div>
                                <h3 class="text-xl font-semibold">Industry Table</h3>
                            </div>
                            <button type="button" onclick="closeIndustryModal()" class="text-white hover:text-gray-200 focus:outline-none transform active:translate-y-px">
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
                                    <input type="text" id="searchIndustryInput" placeholder="Search industries..."
                                        class="pl-10 pr-4 py-2 w-full border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 bg-gray-50">
                                </div>
                            </div>
                            
                            <div class="overflow-x-auto rounded-lg border border-gray-200">
                                <table class="min-w-full divide-y divide-gray-200" id="industryDataTable">
                                    <thead class="bg-gray-50 sticky top-0">
                                        <tr>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Code</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                        </tr>
                                    </thead>
                                    <tbody id="industryTableBody" class="bg-white divide-y divide-gray-200 text-xs">
                                        {{-- Data will be loaded here via JavaScript/Ajax or passed from controller --}}
                                        {{-- Example row structure (replace with actual data loop if rendering server-side) --}}
                                        {{--
                                        <tr class="hover:bg-blue-50 cursor-pointer modal-row" data-industry-code="IND1" data-industry-name="Manufacturing">
                                            <td class="px-4 py-3 whitespace-nowrap font-medium text-gray-900">IND1</td>
                                            <td class="px-4 py-3 whitespace-nowrap text-gray-700">Manufacturing</td>
                                        </tr>
                                        --}}
                                    </tbody>
                                </table>
                            </div>

                            <!-- Bottom Buttons -->
                            <div class="mt-4 flex justify-end space-x-3">
                                <button type="button" id="selectIndustryBtn" class="py-2 px-4 bg-blue-500 hover:bg-blue-600 text-white text-sm rounded-lg transform active:translate-y-px">
                                    <i class="fas fa-check mr-1"></i>Select
                                </button>
                                <button type="button" onclick="closeIndustryModal()" id="cancelSelectIndustryBtn" class="py-2 px-4 bg-gray-300 hover:bg-gray-400 text-gray-800 text-sm rounded-lg transform active:translate-y-px">
                                    <i class="fas fa-times mr-1"></i>Exit
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Geo Table Modal --}}
                <div id="geoTableModal" class="hidden fixed inset-0 z-50 bg-black bg-opacity-50 flex items-center justify-center">
                    <div class="bg-white rounded-lg shadow-xl w-11/12 md:w-3/4 lg:w-2/3 max-w-5xl mx-auto" style="max-height: 80vh;">
                        <!-- Modal Header - Title Bar -->
                        <div class="flex items-center justify-between p-4 border-b border-gray-200 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-t-lg">
                            <div class="flex items-center">
                                <div class="p-2 bg-white bg-opacity-30 rounded-lg mr-3">
                                    <i class="fas fa-globe-americas"></i>
                                </div>
                                <h3 class="text-xl font-semibold">Geo Table</h3>
                            </div>
                            <button type="button" onclick="closeGeoModal()" class="text-white hover:text-gray-200 focus:outline-none transform active:translate-y-px">
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
                                    <input type="text" id="searchGeoInput" placeholder="Search geo data..."
                                        class="pl-10 pr-4 py-2 w-full border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 bg-gray-50">
                                </div>
                            </div>
                            
                            <div class="overflow-x-auto rounded-lg border border-gray-200">
                                <table class="min-w-full divide-y divide-gray-200" id="geoDataTable">
                                    <thead class="bg-gray-50 sticky top-0">
                                        <tr>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Code</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Country</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">State</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Town</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Town Section</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Area</th>
                                        </tr>
                                    </thead>
                                    <tbody id="geoTableBody" class="bg-white divide-y divide-gray-200 text-xs">
                                        {{-- Data will be loaded here via JavaScript/Ajax or passed from controller --}}
                                        {{-- Example row structure (replace with actual data loop) --}}
                                        {{--
                                        <tr class="hover:bg-blue-50 cursor-pointer modal-row" data-geo-code="B103">
                                            <td class="px-4 py-3 whitespace-nowrap font-medium text-gray-900">B103</td>
                                            <td class="px-4 py-3 whitespace-nowrap text-gray-700">Indonesia</td>
                                            <td class="px-4 py-3 whitespace-nowrap text-gray-700">Jawa Barat</td>
                                            <td class="px-4 py-3 whitespace-nowrap text-gray-700">Bekasi</td>
                                            <td class="px-4 py-3 whitespace-nowrap text-gray-700">Cikarang</td>
                                            <td class="px-4 py-3 whitespace-nowrap text-gray-700">WEST</td>
                                        </tr>
                                        --}}
                                    </tbody>
                                </table>
                            </div>

                            <!-- Bottom Buttons -->
                            <div class="mt-4 flex justify-end space-x-3">
                                <button type="button" id="selectGeoBtn" class="py-2 px-4 bg-blue-500 hover:bg-blue-600 text-white text-sm rounded-lg transform active:translate-y-px">
                                    <i class="fas fa-check mr-1"></i>Select
                                </button>
                                <button type="button" onclick="closeGeoModal()" id="cancelSelectGeoBtn" class="py-2 px-4 bg-gray-300 hover:bg-gray-400 text-gray-800 text-sm rounded-lg transform active:translate-y-px">
                                    <i class="fas fa-times mr-1"></i>Exit
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <script>
                let selectedCustomerAccountRow = null;
                let selectedSalespersonRow = null;
                let selectedIndustryRow = null;
                let selectedGeoRow = null; // Added for geo modal

                function openCustomerAccountModal() {
                    console.log('Opening modal...');
                    const modal = document.getElementById('customerAccountModal');
                    if (!modal) {
                        console.error('Customer account modal not found');
                        return;
                    }
                    modal.classList.remove('hidden');
                    if(selectedCustomerAccountRow) selectedCustomerAccountRow.classList.remove('bg-blue-200');
                    selectedCustomerAccountRow = null;
                    const selectBtn = document.getElementById('selectCustomerAccountBtn');
                    if (selectBtn) selectBtn.disabled = true;
                     // Optionally hide additional fields when opening modal
                    const additionalFields = document.getElementById('additional-fields-container');
                     if (additionalFields) additionalFields.classList.add('hidden');
                }

                function closeCustomerAccountModal() {
                    console.log('Closing modal...');
                    const modal = document.getElementById('customerAccountModal');
                    if (modal) modal.classList.add('hidden');
                }

                function selectCustomerAccountRow(row) {
                    console.log('Selecting row:', row);
                    if (!row) return;
                    document.querySelectorAll('#customerAccountTable .modal-row').forEach(r => r.classList.remove('bg-blue-200'));
                    selectedCustomerAccountRow = row;
                    row.classList.add('bg-blue-200');
                    const selectBtn = document.getElementById('selectCustomerAccountBtn');
                    if (selectBtn) selectBtn.disabled = false;
                }

                // Salesperson Modal Logic
                function openSalespersonModal() {
                    console.log('Opening salesperson modal...');
                    const modal = document.getElementById('salespersonTableModal');
                    if (!modal) {
                        console.error('Salesperson modal not found');
                        return;
                    }
                    modal.classList.remove('hidden');
                    // Reset selected row and disable select button
                    if(selectedSalespersonRow) selectedSalespersonRow.classList.remove('bg-blue-200');
                    selectedSalespersonRow = null;
                    const selectBtn = document.getElementById('selectSalespersonBtn');
                    if (selectBtn) selectBtn.disabled = true;

                    // Load data when modal opens (assuming an endpoint /salesperson/json/all or similar exists)
                    // For now, we'll just show the modal structure. Data loading needs a separate step or existing data.
                    // You might need an AJAX call here to fetch salesperson data if not already available in the view.
                    loadSalespersonData(); // Call function to load data
                }

                function closeSalespersonModal() {
                    console.log('Closing salesperson modal...');
                    const modal = document.getElementById('salespersonTableModal');
                    if (modal) modal.classList.add('hidden');
                }

                function selectSalespersonRow(row) {
                     console.log('Selecting salesperson row:', row);
                    if (!row) return;
                     // Remove highlight from previously selected row
                    document.querySelectorAll('#salespersonDataTable tr').forEach(r => r.classList.remove('bg-blue-200'));
                    // Highlight the newly selected row
                    selectedSalespersonRow = row;
                    row.classList.add('bg-blue-200');
                     // Enable the select button
                    const selectBtn = document.getElementById('selectSalespersonBtn');
                    if (selectBtn) selectBtn.disabled = false;
                }

                // Function to load salesperson data (placeholder)
                function loadSalespersonData() {
                    console.log('Loading salesperson data...');
                    // This is a placeholder. In a real application, you would make an AJAX request here.
                    // For example, using Fetch API:
                    /*
                    fetch('/salesperson/json/all') // Replace with your actual endpoint
                        .then(response => response.json())
                        .then(data => {
                            console.log('Salesperson data fetched:', data);
                            const tbody = document.querySelector('#salespersonDataTable tbody');
                            tbody.innerHTML = ''; // Clear existing rows
                            if (data && data.length > 0) {
                                data.forEach(person => {
                                    const row = `
                                        <tr class="hover:bg-blue-50 cursor-pointer" 
                                            data-person-code="${person.code}"
                                            data-person-name="${person.name}"
                                            data-person-team="${person.sales_team_id ?? ''}"
                                            data-person-position="${person.position ?? ''}"
                                            data-person-user-id="${person.user_id ?? ''}"
                                            data-person-is-active="${person.is_active ? '1' : '0'}"
                                            onclick="selectSalespersonRow(this)">
                                            <td class="px-4 py-3 whitespace-nowrap font-medium text-gray-900">${person.code}</td>
                                            <td class="px-4 py-3 whitespace-nowrap text-gray-700">${person.name}</td>
                                            <td class="px-4 py-3 whitespace-nowrap text-gray-700">${person.sales_team ? person.sales_team.name : ''}</td>
                                            <td class="px-4 py-3 whitespace-nowrap text-gray-700">${person.position ?? ''}</td>
                                        </tr>
                                    `;
                                    tbody.innerHTML += row;
                                });
                            } else {
                                tbody.innerHTML = `<tr><td colspan="4" class="px-4 py-4 text-center text-gray-500">Tidak ada data salesperson yang tersedia.</td></tr>`;
                            }
                        })
                        .catch(error => console.error('Error loading salesperson data:', error));
                    */

                    // --- Temporary: Populate with sample data if not using AJAX ---
                    const sampleSalespersons = [
                         { code: 'S101', name: 'Salesperson A', sales_team: { name: 'Team X' }, position: 'Salesman' },
                         { code: 'S102', name: 'Salesperson B', sales_team: { name: 'Team Y' }, position: 'Manager' },
                         { code: 'S103', name: 'Salesperson C', sales_team: null, position: 'Staff' },
                    ];

                    const tbody = document.querySelector('#salespersonDataTable tbody');
                     if (tbody) {
                        tbody.innerHTML = ''; // Clear existing rows
                        if (sampleSalespersons.length > 0) {
                            sampleSalespersons.forEach(person => {
                                const row = `
                                    <tr class="hover:bg-blue-50 cursor-pointer modal-row" 
                                        data-person-code="${person.code}"
                                        data-person-name="${person.name}"
                                        data-person-team="${person.sales_team ? person.sales_team.name : ''}"
                                        data-person-position="${person.position ?? ''}"
                                        onclick="selectSalespersonRow(this)">
                                        <td class="px-4 py-3 whitespace-nowrap font-medium text-gray-900">${person.code}</td>
                                        <td class="px-4 py-3 whitespace-nowrap text-gray-700">${person.name}</td>
                                        <td class="px-4 py-3 whitespace-nowrap text-gray-700">${person.sales_team ? person.sales_team.name : ''}</td>
                                        <td class="px-4 py-3 whitespace-nowrap text-gray-700">${person.position ?? ''}</td>
                                    </tr>
                                `;
                                tbody.innerHTML += row;
                            });
                         } else {
                             tbody.innerHTML = `<tr><td colspan="4" class="px-4 py-4 text-center text-gray-500">Tidak ada data salesperson yang tersedia.</td></tr>`;
                         }
                     }
                    // --- End Temporary ---
                }

                // --- Industry Modal Logic ---
                function openIndustryModal() {
                    console.log('Opening industry modal...');
                    const modal = document.getElementById('industryTableModal');
                    if (!modal) {
                        console.error('Industry modal not found');
                        return;
                    }
                    modal.classList.remove('hidden');
                    // Reset selected row and disable select button
                    if(selectedIndustryRow) selectedIndustryRow.classList.remove('bg-blue-200');
                    selectedIndustryRow = null;
                    const selectBtn = document.getElementById('selectIndustryBtn');
                    if (selectBtn) selectBtn.disabled = true;

                    // Load data when modal opens (assuming an endpoint /industry/json/all or data is available in view)
                    // For now, we'll use sample data or data passed from the controller if available.
                    loadIndustryData(); // Call function to load data
                }

                function closeIndustryModal() {
                    console.log('Closing industry modal...');
                    const modal = document.getElementById('industryTableModal');
                    if (modal) modal.classList.add('hidden');
                }

                function selectIndustryRow(row) {
                     console.log('Selecting industry row:', row);
                    if (!row) return;
                     // Remove highlight from previously selected row
                    document.querySelectorAll('#industryDataTable tr').forEach(r => r.classList.remove('bg-blue-200'));
                    // Highlight the newly selected row
                    selectedIndustryRow = row;
                    row.classList.add('bg-blue-200');
                     // Enable the select button
                    const selectBtn = document.getElementById('selectIndustryBtn');
                    if (selectBtn) selectBtn.disabled = false;
                }

                // Function to load industry data
                function loadIndustryData() {
                     console.log('Loading industry data...');
                     const tbody = document.querySelector('#industryDataTable tbody');
                     if (!tbody) {
                        console.error('Industry table body not found');
                        return;
                     }
                     tbody.innerHTML = ''; // Clear existing rows

                     // Check if industries data is passed from the controller
                     @if(isset($industries) && count($industries) > 0)
                         console.log('Using server-side industries data.');
                         const industries = @json($industries);
                         industries.forEach(industry => {
                             const row = `
                                 <tr class="hover:bg-blue-50 cursor-pointer modal-row" 
                                     data-industry-code="${industry.code}"
                                     data-industry-name="${industry.name}"
                                     onclick="selectIndustryRow(this)">
                                     <td class="px-4 py-3 whitespace-nowrap font-medium text-gray-900">${industry.code}</td>
                                     <td class="px-4 py-3 whitespace-nowrap text-gray-700">${industry.name}</td>
                                 </tr>
                             `;
                             tbody.innerHTML += row;
                         });
                     @else
                         console.log('No server-side industries data found. Using sample data.');
                         // --- Temporary: Populate with sample data if no server-side data ---
                         const sampleIndustries = [
                              { code: 'IND1', name: 'Manufacturing' },
                              { code: 'IND2', name: 'Retail' },
                              { code: 'IND3', name: 'Service' },
                         ];

                         if (sampleIndustries.length > 0) {
                             sampleIndustries.forEach(industry => {
                                 const row = `
                                     <tr class="hover:bg-blue-50 cursor-pointer modal-row" 
                                         data-industry-code="${industry.code}"
                                         data-industry-name="${industry.name}"
                                         onclick="selectIndustryRow(this)">
                                         <td class="px-4 py-3 whitespace-nowrap font-medium text-gray-900">${industry.code}</td>
                                         <td class="px-4 py-3 whitespace-nowrap text-gray-700">${industry.name}</td>
                                     </tr>
                                 `;
                                 tbody.innerHTML += row;
                             });
                          } else {
                              tbody.innerHTML = `<tr><td colspan="2" class="px-4 py-4 text-center text-gray-500">Tidak ada data industri yang tersedia.</td></tr>`;
                          }
                         // --- End Temporary ---
                     @endif
                }

                // --- Geo Modal Logic ---
                function openGeoModal() {
                    console.log('Opening geo modal...');
                    const modal = document.getElementById('geoTableModal');
                    if (!modal) {
                        console.error('Geo modal not found');
                        return;
                    }
                    modal.classList.remove('hidden');
                    // Reset selected row and disable select button
                    if(selectedGeoRow) selectedGeoRow.classList.remove('bg-blue-200');
                    selectedGeoRow = null;
                    const selectBtn = document.getElementById('selectGeoBtn');
                    if (selectBtn) selectBtn.disabled = true;

                    // Load data when modal opens (assuming an endpoint /geo/json/all or data is available in view)
                    // For now, we'll use sample data or data passed from the controller if available.
                    loadGeoData(); // Call function to load data
                }

                function closeGeoModal() {
                    console.log('Closing geo modal...');
                    const modal = document.getElementById('geoTableModal');
                    if (modal) modal.classList.add('hidden');
                }

                function selectGeoRow(row) {
                     console.log('Selecting geo row:', row);
                    if (!row) return;
                     // Remove highlight from previously selected row
                    document.querySelectorAll('#geoDataTable tr').forEach(r => r.classList.remove('bg-blue-200'));
                    // Highlight the newly selected row
                    selectedGeoRow = row;
                    row.classList.add('bg-blue-200');
                     // Enable the select button
                    const selectBtn = document.getElementById('selectGeoBtn');
                    if (selectBtn) selectBtn.disabled = false;
                }

                // Function to load geo data
                function loadGeoData() {
                     console.log('Loading geo data...');
                     const tbody = document.querySelector('#geoDataTable tbody');
                     if (!tbody) {
                        console.error('Geo table body not found');
                        return;
                     }
                     tbody.innerHTML = ''; // Clear existing rows

                     // Check if geo data is passed from the controller
                     @if(isset($geoData) && count($geoData) > 0)
                         console.log('Using server-side geo data.');
                         const geoData = @json($geoData);
                         geoData.forEach(geo => {
                             const row = `
                                 <tr class="hover:bg-blue-50 cursor-pointer modal-row" 
                                     data-geo-code="${geo.code}"
                                     {{-- Add other data attributes if needed --}}
                                     onclick="selectGeoRow(this)">
                                     {{-- Adjust columns based on what you want to show in the modal --}}
                                     <td class="px-4 py-3 whitespace-nowrap font-medium text-gray-900">${geo.code}</td>
                                     <td class="px-4 py-3 whitespace-nowrap text-gray-700">${geo.country ?? ''}</td>
                                     <td class="px-4 py-3 whitespace-nowrap text-gray-700">${geo.state ?? ''}</td>
                                     <td class="px-4 py-3 whitespace-nowrap text-gray-700">${geo.town ?? ''}</td>
                                     <td class="px-4 py-3 whitespace-nowrap text-gray-700">${geo.town_section ?? ''}</td>
                                     <td class="px-4 py-3 whitespace-nowrap text-gray-700">${geo.area ?? ''}</td>
                                 </tr>
                             `;
                             tbody.innerHTML += row;
                         });
                     @else
                         console.log('No server-side geo data found. Using sample data.');
                         // --- Temporary: Populate with sample data if no server-side data ---
                         const sampleGeoData = [
                              { code: 'B103', country: 'Indonesia', state: 'Jawa Barat', town: 'Bekasi', town_section: 'Cikarang', area: 'WEST' },
                              { code: 'JKT', country: 'Indonesia', state: 'DKI Jakarta', town: 'Jakarta', town_section: 'Pusat', area: 'WEST' },
                         ];

                         if (sampleGeoData.length > 0) {
                             sampleGeoData.forEach(geo => {
                                 const row = `
                                     <tr class="hover:bg-blue-50 cursor-pointer modal-row" 
                                         data-geo-code="${geo.code}"
                                         data-geo-country="${geo.country ?? ''}"
                                         data-geo-state="${geo.state ?? ''}"
                                         data-geo-town="${geo.town ?? ''}"
                                         data-geo-town-section="${geo.town_section ?? ''}"
                                         data-geo-area="${geo.area ?? ''}"
                                         onclick="selectGeoRow(this)">
                                         <td class="px-4 py-3 whitespace-nowrap font-medium text-gray-900">${geo.code}</td>
                                         <td class="px-4 py-3 whitespace-nowrap text-gray-700">${geo.country ?? ''}</td>
                                         <td class="px-4 py-3 whitespace-nowrap text-gray-700">${geo.state ?? ''}</td>
                                         <td class="px-4 py-3 whitespace-nowrap text-gray-700">${geo.town ?? ''}</td>
                                         <td class="px-4 py-3 whitespace-nowrap text-gray-700">${geo.town_section ?? ''}</td>
                                         <td class="px-4 py-3 whitespace-nowrap text-gray-700">${geo.area ?? ''}</td>
                                     </tr>
                                 `;
                                 tbody.innerHTML += row;
                             });
                          } else {
                              tbody.innerHTML = `<tr><td colspan="6" class="px-4 py-4 text-center text-gray-500">Tidak ada data geo yang tersedia.</td></tr>`;
                          }
                         // --- End Temporary ---
                     @endif
                }

                document.addEventListener('DOMContentLoaded', function() {
                    console.log('DOM loaded, initializing...');
                    
                    // Find the customer account table button
                    const customerAccountTableBtn = document.querySelector('#customer_code').nextElementSibling;
                    console.log('Customer Account Table button found:', customerAccountTableBtn);
                    
                    if (customerAccountTableBtn) {
                        customerAccountTableBtn.addEventListener('click', function(e) {
                            console.log('Customer Account Table button clicked');
                            e.preventDefault();
                            openCustomerAccountModal();
                        });
                    } else {
                        console.error('Customer Account Table button not found');
                    }

                    // Set up select button for Customer Account Modal
                    const selectCustomerAccountBtn = document.getElementById('selectCustomerAccountBtn');
                    console.log('Select Customer Account button found:', selectCustomerAccountBtn);
                    
                    if (selectCustomerAccountBtn) {
                         selectCustomerAccountBtn.addEventListener('click', function() {
                            console.log('Select Customer Account button clicked');
                        if(selectedCustomerAccountRow) {
                            const code = selectedCustomerAccountRow.getAttribute('data-code');
                            const name = selectedCustomerAccountRow.getAttribute('data-name');
                                // Get data for new fields
                                const shortName = selectedCustomerAccountRow.getAttribute('data-shortname');
                                const address = selectedCustomerAccountRow.getAttribute('data-address');
                                const contactPerson = selectedCustomerAccountRow.getAttribute('data-contactperson');
                                const telephoneNo = selectedCustomerAccountRow.getAttribute('data-telephoneno');
                                const faxNo = selectedCustomerAccountRow.getAttribute('data-faxno');
                                const coEmail = selectedCustomerAccountRow.getAttribute('data-coemail');
                                const creditLimit = selectedCustomerAccountRow.getAttribute('data-creditlimit');
                                const creditTerms = selectedCustomerAccountRow.getAttribute('data-creditterms');
                                const acType = selectedCustomerAccountRow.getAttribute('data-actype');
                                const currencyCode = selectedCustomerAccountRow.getAttribute('data-currency'); // Reusing data-currency for currency_code
                                const salespersonCode = selectedCustomerAccountRow.getAttribute('data-salesperson'); // Reusing data-salesperson for salesperson_code
                                const geographical = selectedCustomerAccountRow.getAttribute('data-geographical');
                                const groupingCode = selectedCustomerAccountRow.getAttribute('data-groupingcode');
                                const printArAging = selectedCustomerAccountRow.getAttribute('data-printaraging');
                                const industrialCode = selectedCustomerAccountRow.getAttribute('data-industrialcode');

                                console.log('Selected customer:', { code, name, shortName, address, contactPerson, telephoneNo, faxNo, coEmail, creditLimit, creditTerms, acType, currencyCode, salespersonCode, geographical, groupingCode, printArAging, industrialCode });
                                
                                // Populate the form fields
                                const codeInput = document.getElementById('customer_code');
                                const nameInput = document.getElementById('customer_name');
                                const shortNameInput = document.getElementById('short_name');
                                const addressInput = document.getElementById('address');
                                const contactPersonInput = document.getElementById('contact_person');
                                const telephoneNoInput = document.getElementById('telephone_no');
                                const faxNoInput = document.getElementById('fax_no');
                                const coEmailInput = document.getElementById('co_email');
                                const creditLimitInput = document.getElementById('credit_limit');
                                const creditTermsInput = document.getElementById('credit_terms');
                                const currencyCodeInput = document.getElementById('currency_code');
                                const salespersonCodeInput = document.getElementById('salesperson_code');
                                const industrialCodeInput = document.getElementById('industrial_code');
                                const geographicalInput = document.getElementById('geographical');
                                const groupingCodeInput = document.getElementById('grouping_code');
                                const additionalFieldsContainer = document.getElementById('additional-fields-container');

                                if (codeInput) codeInput.value = code || '';
                                if (nameInput) nameInput.value = name || '';
                                if (shortNameInput) shortNameInput.value = shortName || '';
                                if (addressInput) addressInput.value = address || '';
                                if (contactPersonInput) contactPersonInput.value = contactPerson || '';
                                if (telephoneNoInput) telephoneNoInput.value = telephoneNo || '';
                                if (faxNoInput) faxNoInput.value = faxNo || '';
                                if (coEmailInput) coEmailInput.value = coEmail || '';
                                if (creditLimitInput) creditLimitInput.value = parseFloat(creditLimit || '0.00');
                                if (creditTermsInput) creditTermsInput.value = parseInt(creditTerms || '0');
                                if (currencyCodeInput) currencyCodeInput.value = currencyCode || '';
                                if (salespersonCodeInput) salespersonCodeInput.value = salespersonCode || '';
                                if (industrialCodeInput) industrialCodeInput.value = industrialCode || '';
                                if (geographicalInput) geographicalInput.value = geographical || '';
                                if (groupingCodeInput) groupingCodeInput.value = groupingCode || '';

                                // Set radio buttons for Account Type and Print AR Aging
                                if (acType) {
                                    const acTypeRadio = document.querySelector(`input[name="ac_type"][value="${acType}"]`);
                                    if (acTypeRadio) acTypeRadio.checked = true;
                                }
                                if (printArAging) {
                                     const printArAgingRadio = document.querySelector(`input[name="print_ar_aging"][value="${printArAging}"]`);
                                    if (printArAgingRadio) printArAgingRadio.checked = true;
                                }
                                
                                // Show additional fields
                                if (additionalFieldsContainer) additionalFieldsContainer.classList.remove('hidden');

                            closeCustomerAccountModal();
                        }
                        });
                    } else {
                        console.error('Select button for Customer Account Modal not found');
                    }

                    // --- Salesperson Modal Setup ---

                    // Find the salesperson table button
                    const salespersonTableBtn = document.querySelector('#salesperson_code').nextElementSibling;
                     console.log('Salesperson Table button found:', salespersonTableBtn);

                    if (salespersonTableBtn) {
                         salespersonTableBtn.addEventListener('click', function(e) {
                             console.log('Salesperson Table button clicked');
                             e.preventDefault();
                             openSalespersonModal();
                         });
                     } else {
                         console.error('Salesperson Table button not found');
                     }

                    // Set up select button for Salesperson Modal
                    const selectSalespersonBtn = document.getElementById('selectSalespersonBtn');
                     console.log('Select Salesperson button found:', selectSalespersonBtn);

                    if (selectSalespersonBtn) {
                         selectSalespersonBtn.addEventListener('click', function() {
                             console.log('Select Salesperson button clicked');
                             if(selectedSalespersonRow) {
                                 const personCode = selectedSalespersonRow.getAttribute('data-person-code');
                                  console.log('Selected salesperson code:', personCode);

                                 // Populate the Salesperson Code input in the main form
                                 const salespersonCodeInput = document.getElementById('salesperson_code');
                                 if (salespersonCodeInput) {
                                     salespersonCodeInput.value = personCode || '';
                                 }

                                 closeSalespersonModal();
                             }
                         });
                     } else {
                         console.error('Select button for Salesperson Modal not found');
                     }
                     
                     // Initial data load for salesperson modal (optional, depends on your setup)
                     // loadSalespersonData(); 

                    // --- End Salesperson Modal Setup ---

                    // --- Industry Modal Setup ---

                    // Find the industry table button
                    const industryTableBtn = document.getElementById('openIndustryModalBtn');
                     console.log('Industry Table button found:', industryTableBtn);

                    if (industryTableBtn) {
                         industryTableBtn.addEventListener('click', function(e) {
                             console.log('Industry Table button clicked');
                             e.preventDefault();
                             openIndustryModal();
                         });
                     } else {
                         console.error('Industry Table button not found');
                     }

                    // Set up select button for Industry Modal
                    const selectIndustryBtn = document.getElementById('selectIndustryBtn');
                     console.log('Select Industry button found:', selectIndustryBtn);

                    if (selectIndustryBtn) {
                         selectIndustryBtn.addEventListener('click', function() {
                             console.log('Select Industry button clicked');
                             if(selectedIndustryRow) {
                                 const industryCode = selectedIndustryRow.getAttribute('data-industry-code');
                                  console.log('Selected industry code:', industryCode);

                                 // Populate the Industrial Code input in the main form
                                 const industrialCodeInput = document.getElementById('industrial_code');
                                 if (industrialCodeInput) {
                                     industrialCodeInput.value = industryCode || '';
                                 }

                                 closeIndustryModal();
                             }
                         });
                     } else {
                         console.error('Select button for Industry Modal not found');
                     }
                     
                     // Initial data load for industry modal (optional, depends on your setup)
                     // loadIndustryData(); // You might want to load this when the page loads if data is always needed

                    // --- End Industry Modal Setup ---

                    // --- Geo Modal Setup ---

                    // Find the geo table button
                    const geoTableBtn = document.querySelector('#geographical').nextElementSibling;
                     console.log('Geo Table button found:', geoTableBtn);

                    if (geoTableBtn) {
                         geoTableBtn.addEventListener('click', function(e) {
                             console.log('Geo Table button clicked');
                             e.preventDefault();
                             openGeoModal();
                         });
                     } else {
                         console.error('Geo Table button not found');
                     }

                    // Set up select button for Geo Modal
                    const selectGeoBtn = document.getElementById('selectGeoBtn');
                     console.log('Select Geo button found:', selectGeoBtn);

                    if (selectGeoBtn) {
                         selectGeoBtn.addEventListener('click', function() {
                             console.log('Select Geo button clicked');
                             if(selectedGeoRow) {
                                 const geoCode = selectedGeoRow.getAttribute('data-geo-code');
                                 const geoCountry = selectedGeoRow.getAttribute('data-geo-country');
                                 const geoState = selectedGeoRow.getAttribute('data-geo-state');
                                 const geoTown = selectedGeoRow.getAttribute('data-geo-town');
                                 const geoTownSection = selectedGeoRow.getAttribute('data-geo-town-section');
                                 const geoArea = selectedGeoRow.getAttribute('data-geo-area');

                                  console.log('Selected geo code:', geoCode);

                                 // Populate the Geographical input in the main form with a display value
                                 const geographicalInput = document.getElementById('geographical');
                                 if (geographicalInput) {
                                     geographicalInput.value = `${geoCode || ''} - ${geoTown || ''}, ${geoState || ''}, ${geoCountry || ''}`;
                                 }

                                 // Populate the hidden Geographical Code input
                                 const geographicalCodeInput = document.getElementById('geographical_code');
                                 if (geographicalCodeInput) {
                                      geographicalCodeInput.value = geoCode || '';
                                 }

                                 closeGeoModal();
                             }
                         });
                     } else {
                         console.error('Select button for Geo Modal not found');
                     }
                     
                     // Initial data load for geo modal (optional, depends on your setup)
                     // loadGeoData(); // You might want to load this when the page loads if data is always needed

                    // --- End Geo Modal Setup ---

                });
                </script>
            </div>
        </div>

        <!-- Right Column - Info/Help -->
        <div class="lg:col-span-1">
            <div class="bg-white p-6 rounded-lg shadow-md border-t-4 border-teal-500 mb-6">
                <div class="flex items-center mb-4 pb-2 border-b border-gray-200">
                    <div class="p-2 bg-teal-500 rounded-lg mr-3">
                        <i class="fas fa-info-circle text-white"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800">Info Customer Account</h3>
                </div>
                <div class="p-4 bg-teal-50 rounded-lg">
                    <h4 class="text-sm font-semibold text-teal-800 uppercase tracking-wider mb-2">Petunjuk</h4>
                    <ul class="list-disc pl-5 text-sm text-gray-600 space-y-1">
                        <li>Kode customer harus unik</li>
                        <li>Gunakan tombol <span class="font-medium">search</span> untuk memilih customer</li>
                        <li>Perubahan apa pun harus disimpan</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
