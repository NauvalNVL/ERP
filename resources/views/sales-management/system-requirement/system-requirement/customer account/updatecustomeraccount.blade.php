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
                                            data-status="{{ $acc->status ?? '' }}">
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

                <script>
                let selectedCustomerAccountRow = null;
                
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

                // Initialize when DOM is loaded
                document.addEventListener('DOMContentLoaded', function() {
                    console.log('DOM loaded, initializing...');
                    
                    // Find the table button
                    const tableBtn = document.querySelector('button[type="button"] i.fas.fa-table')?.closest('button');
                    console.log('Table button found:', tableBtn);
                    
                    if (tableBtn) {
                        tableBtn.addEventListener('click', function(e) {
                            console.log('Table button clicked');
                            e.preventDefault();
                            openCustomerAccountModal();
                        });
                    } else {
                        console.error('Table button not found');
                    }

                    // Set up select button
                    const selectBtn = document.getElementById('selectCustomerAccountBtn');
                    console.log('Select button found:', selectBtn);
                    
                    if (selectBtn) {
                        selectBtn.addEventListener('click', function() {
                            console.log('Select button clicked');
                            if(selectedCustomerAccountRow) {
                                const code = selectedCustomerAccountRow.getAttribute('data-code');
                                const name = selectedCustomerAccountRow.getAttribute('data-name');
                                console.log('Selected customer:', { code, name });
                                
                                const codeInput = document.getElementById('customer_code');
                                const nameInput = document.getElementById('customer_name');
                                
                                if (codeInput) codeInput.value = code || '';
                                if (nameInput) nameInput.value = name || '';
                                
                                closeCustomerAccountModal();
                            }
                        });
                    } else {
                        console.error('Select button not found');
                    }
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
