@extends('layouts.app')

@section('title', 'Define Foreign Currency')

@section('content')
<!-- Header Section -->
<div class="bg-gradient-to-r from-blue-700 to-indigo-600 p-6 rounded-t-lg shadow-lg">
    <h2 class="text-2xl font-bold text-white mb-2 flex items-center">
        <i class="fas fa-money-bill-wave mr-3"></i> Define Foreign Currency
    </h2>
    <p class="text-indigo-100">Define and manage foreign currencies and their exchange rates.</p>
</div>

<div class="bg-white rounded-b-lg shadow-lg p-6 mb-6">
    {{-- Display Success/Error Messages --}}
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif
    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif
    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <strong class="font-bold">Validation Error!</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Main Content -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Left Column - Main Content -->
        <div class="lg:col-span-2">
            <div class="bg-white p-6 rounded-lg shadow-md border-t-4 border-blue-500">
                <div class="flex items-center mb-6 pb-2 border-b border-gray-200">
                    <div class="p-2 {{ isset($editCurrency) ? 'bg-orange-500' : 'bg-blue-500' }} rounded-lg mr-3">
                        <i class="fas {{ isset($editCurrency) ? 'fa-edit' : 'fa-plus' }} text-white"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800">
                        {{ isset($editCurrency) ? 'Edit Currency' : 'Foreign Currency Management' }}
                    </h3>
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
                    <button type="button" onclick="openCurrencyModal()" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded flex items-center space-x-2 transform active:translate-y-px">
                        <i class="fas fa-search"></i>
                    </button>
                    <button type="submit" form="currency-form" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded flex items-center space-x-2 transform active:translate-y-px">
                        <i class="fas fa-save"></i>
                    </button>
                </div>

                <!-- Currency Form -->
                <form 
                    id="currency-form"
                    action="{{ isset($editCurrency) ? route('foreign-currency.update', $editCurrency->id) : route('foreign-currency.store') }}" 
                    method="POST"
                >
                    @csrf
                    @if(isset($editCurrency))
                        @method('PUT')
                    @endif

                    <div class="grid grid-cols-1 gap-6">
                        <!-- First Row - Code and Record Selection -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                            <div class="col-span-2">
                                <label for="currency_code" class="block text-sm font-medium text-gray-700 mb-1">Currency Code:</label>
                                <div class="relative flex">
                                    <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500">
                                        <i class="fas fa-hashtag"></i>
                                    </span>
                                    <input type="text" id="currency_code" name="currency_code" 
                                           value="{{ old('currency_code', $editCurrency->currency_code ?? '') }}" 
                                           class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none border border-gray-300 focus:ring-blue-500 focus:border-blue-500 @error('currency_code') border-red-500 @enderror" 
                                           maxlength="3" required {{ isset($editCurrency) ? 'readonly bg-gray-100' : '' }} placeholder="e.g., USD">
                                    <button type="button" onclick="openCurrencyModal()" class="inline-flex items-center px-3 py-2 border border-l-0 border-gray-300 bg-blue-500 hover:bg-blue-600 text-white rounded-r-md transition-colors transform active:translate-y-px">
                                        <i class="fas fa-table"></i>
                                    </button>
                                </div>
                                @error('currency_code')
                                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div class="col-span-1">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Record:</label>
                                <button type="button" onclick="openCurrencyModal()" class="w-full flex items-center justify-center px-4 py-2 bg-indigo-500 hover:bg-indigo-600 text-white rounded transition-colors transform active:translate-y-px">
                                    <i class="fas fa-list-ul mr-2"></i> Select Record
                                </button>
                            </div>
                        </div>

                        <!-- Country -->
                        <div>
                            <label for="country" class="block text-sm font-medium text-gray-700 mb-1">Country:</label>
                            <div class="relative flex">
                                <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500">
                                    <i class="fas fa-globe"></i>
                                </span>
                                <input type="text" id="country" name="country" 
                                       value="{{ old('country', $editCurrency->country ?? '') }}" 
                                       class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none rounded-r-md border border-gray-300 focus:ring-blue-500 focus:border-blue-500 @error('country') border-red-500 @enderror" 
                                       maxlength="100" required placeholder="e.g., USA">
                            </div>
                            @error('country')
                                <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Currency Name -->
                        <div>
                            <label for="currency_name" class="block text-sm font-medium text-gray-700 mb-1">Currency Name:</label>
                            <div class="relative flex">
                                <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500">
                                    <i class="fas fa-font"></i>
                                </span>
                                <input type="text" id="currency_name" name="currency_name" 
                                       value="{{ old('currency_name', $editCurrency->currency_name ?? '') }}" 
                                       class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none rounded-r-md border border-gray-300 focus:ring-blue-500 focus:border-blue-500 @error('currency_name') border-red-500 @enderror" 
                                       maxlength="100" required placeholder="e.g., US Dollar">
                            </div>
                            @error('currency_name')
                                <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Exchange Rate -->
                        <div>
                            <label for="exchange_rate" class="block text-sm font-medium text-gray-700 mb-1">Exchange Rate:</label>
                            <div class="relative flex">
                                <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500">
                                    <i class="fas fa-exchange-alt"></i>
                                </span>
                                <input type="number" id="exchange_rate" name="exchange_rate" step="0.000001" 
                                       value="{{ old('exchange_rate', isset($editCurrency) ? number_format($editCurrency->exchange_rate, 6, '.', '') : '') }}" 
                                       class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none rounded-r-md border border-gray-300 focus:ring-blue-500 focus:border-blue-500 @error('exchange_rate') border-red-500 @enderror" 
                                       required placeholder="e.g., 14500.123456">
                            </div>
                            @error('exchange_rate')
                                <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Exchange Method -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Exchange Method:</label>
                            <div class="grid grid-cols-2 gap-4">
                                <label class="relative flex items-center p-3 rounded-lg border border-gray-200 hover:border-blue-500 cursor-pointer transition-colors">
                                    <input type="radio" id="method_multiply" name="exchange_method" value="1" 
                                           class="h-4 w-4 text-blue-600 focus:ring-blue-500" {{ old('exchange_method', $editCurrency->exchange_method ?? 1) == 1 ? 'checked' : '' }}>
                                    <span class="ml-3 text-gray-900">Multiply</span>
                                </label>
                                <label class="relative flex items-center p-3 rounded-lg border border-gray-200 hover:border-blue-500 cursor-pointer transition-colors">
                                    <input type="radio" id="method_divide" name="exchange_method" value="2" 
                                           class="h-4 w-4 text-blue-600 focus:ring-blue-500" {{ old('exchange_method', $editCurrency->exchange_method ?? 1) == 2 ? 'checked' : '' }}>
                                    <span class="ml-3 text-gray-900">Divide</span>
                                </label>
                            </div>
                            @error('exchange_method')
                                <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Variance Control -->
                        <div>
                            <label for="variance_control" class="block text-sm font-medium text-gray-700 mb-1">Variance Control (%):</label>
                            <div class="relative flex">
                                <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500">
                                    <i class="fas fa-percentage"></i>
                                </span>
                                <input type="number" id="variance_control" name="variance_control" step="0.01" min="0" max="100" 
                                       value="{{ old('variance_control', isset($editCurrency) ? number_format($editCurrency->variance_control, 2, '.', '') : '100.00') }}" 
                                       class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none rounded-r-md border border-gray-300 focus:ring-blue-500 focus:border-blue-500 @error('variance_control') border-red-500 @enderror" 
                                       required>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">Control exchange rate variance (%)</p>
                            @error('variance_control')
                                <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Max Tax Adj -->
                        <div>
                            <label for="max_tax_adj" class="block text-sm font-medium text-gray-700 mb-1">Max +/- Tax ADJ:</label>
                            <div class="relative flex">
                                <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500">
                                    <i class="fas fa-calculator"></i>
                                </span>
                                <input type="number" id="max_tax_adj" name="max_tax_adj" step="0.01" 
                                       value="{{ old('max_tax_adj', isset($editCurrency) ? number_format($editCurrency->max_tax_adj, 2, '.', '') : '0.00') }}" 
                                       class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none rounded-r-md border border-gray-300 focus:ring-blue-500 focus:border-blue-500 @error('max_tax_adj') border-red-500 @enderror" 
                                       required>
                            </div>
                            @error('max_tax_adj')
                                <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Right Column - Quick Links & Info -->
        <div class="lg:col-span-1">
            <!-- Currency Info Card -->
            <div class="bg-white p-6 rounded-lg shadow-md border-t-4 border-indigo-500 mb-6">
                <div class="flex items-center mb-4 pb-2 border-b border-gray-200">
                    <div class="p-2 bg-indigo-500 rounded-lg mr-3">
                        <i class="fas fa-info-circle text-white"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800">Currency Information</h3>
                </div>

                <div class="space-y-4">
                    <div class="p-4 bg-indigo-50 rounded-lg">
                        <h4 class="text-sm font-semibold text-indigo-800 uppercase tracking-wider mb-2">Instructions</h4>
                        <ul class="list-disc pl-5 text-sm text-gray-600 space-y-1">
                            <li>Currency code must be 3 letters (ISO format)</li>
                            <li>Exchange rate should be up to 6 decimal places</li>
                            <li>Choose multiply or divide for conversion method</li>
                            <li>Set variance control to limit allowed rate changes</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Quick Links Card -->
            <div class="bg-white p-6 rounded-lg shadow-md border-t-4 border-purple-500">
                <div class="flex items-center mb-4 pb-2 border-b border-gray-200">
                    <div class="p-2 bg-purple-500 rounded-lg mr-3">
                        <i class="fas fa-link text-white"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800">Quick Links</h3>
                </div>

                <div class="grid grid-cols-1 gap-3">
                    <a href="{{ route('foreign-currency.view-print') }}" class="flex items-center p-3 bg-purple-50 rounded-lg hover:bg-purple-100 transition-colors">
                        <div class="p-2 bg-purple-500 rounded-full mr-3">
                            <i class="fas fa-print text-white text-sm"></i>
                        </div>
                        <div>
                            <p class="font-medium text-purple-900">Print Currencies</p>
                            <p class="text-xs text-purple-700">Print currency list</p>
                        </div>
                    </a>

                    <a href="#" class="flex items-center p-3 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                        <div class="p-2 bg-blue-500 rounded-full mr-3">
                            <i class="fas fa-sync-alt text-white text-sm"></i>
                        </div>
                        <div>
                            <p class="font-medium text-blue-900">Update Rates</p>
                            <p class="text-xs text-blue-700">Refresh currency rates</p>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Currency Selection Modal --}}
<div id="currencyModal" class="fixed inset-0 z-50 bg-black bg-opacity-50 flex items-center justify-center hidden">
    <div class="bg-white rounded-lg shadow-xl w-11/12 md:w-2/3 lg:w-1/2 max-w-2xl mx-auto transform transition-transform duration-300 scale-95">
        {{-- Modal Header --}}
        <div class="flex items-center justify-between p-4 border-b border-gray-200 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-t-lg">
            <h3 class="text-xl font-semibold flex items-center"><i class="fas fa-list mr-3"></i>Select Currency</h3>
            <button type="button" onclick="closeCurrencyModal()" class="text-white hover:text-gray-200 focus:outline-none">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        
        {{-- Modal Body --}}
        <div class="p-5 overflow-y-auto" style="max-height: 60vh;">
            {{-- Search Input --}}
             <div class="mb-4">
                <input type="text" id="currencySearchInput" placeholder="Search by Code or Name..."
                       class="shadow-sm appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            
            {{-- Table --}}
            <div class="overflow-x-auto rounded-lg border border-gray-200">
                <table class="min-w-full divide-y divide-gray-200" id="currencyModalTable">
                    <thead class="bg-gray-100 sticky top-0">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Code</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Country</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($currencies as $currency)
                            <tr class="hover:bg-blue-50 cursor-pointer modal-row"
                                data-currency-id="{{ $currency->id }}" 
                                ondblclick="selectCurrencyAndEdit('{{ route('foreign-currency.edit', $currency->id) }}')">
                                <td class="px-4 py-3 whitespace-nowrap font-medium text-gray-900">{{ $currency->currency_code }}</td>
                                <td class="px-4 py-3 whitespace-nowrap text-gray-700">{{ $currency->currency_name }}</td>
                                <td class="px-4 py-3 whitespace-nowrap text-gray-500">{{ $currency->country }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-4 py-4 text-center text-gray-500">No currencies found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
         {{-- Modal Footer --}}
        <div class="flex items-center justify-end p-4 border-t border-gray-200 rounded-b-lg bg-gray-50">
            <button type="button" onclick="editSelectedCurrency()" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline mr-2">Select & Edit</button>
            <button type="button" onclick="closeCurrencyModal()" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Cancel</button>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const modal = document.getElementById('currencyModal');
    const searchInput = document.getElementById('currencySearchInput');
    const tableBody = document.getElementById('currencyModalTable').querySelector('tbody');
    const rows = tableBody.querySelectorAll('tr.modal-row');
    let selectedRow = null;

    function openCurrencyModal() {
        modal.classList.remove('hidden');
        setTimeout(() => { modal.querySelector('.scale-95').classList.remove('scale-95'); }, 10); // Animation
        searchInput.focus();
        clearSelection();
    }

    function closeCurrencyModal() {
        const modalContent = modal.querySelector('div');
        if (modalContent) modalContent.classList.add('scale-95');
        setTimeout(() => { modal.classList.add('hidden'); }, 150); // Animation
    }

    function selectCurrencyAndEdit(url) {
        if (url) {
            window.location.href = url;
        }
    }
    
    function clearSelection() {
         rows.forEach(r => r.classList.remove('bg-blue-200'));
         selectedRow = null;
    }

    // Row selection visual feedback
    rows.forEach(row => {
        row.addEventListener('click', function() {
             rows.forEach(r => r.classList.remove('bg-blue-200')); // Deselect others
             this.classList.add('bg-blue-200'); // Select this
             selectedRow = this;
        });
    });
    
    function editSelectedCurrency(){
        if(selectedRow){
            const editUrl = "{{ url('system-manager/system-maintenance/foreign-currency') }}/" + selectedRow.dataset.currencyId + "/edit";
            selectCurrencyAndEdit(editUrl);
        }
    }

    // Search functionality
    searchInput.addEventListener('keyup', function() {
        const searchTerm = this.value.toLowerCase();
        rows.forEach(row => {
            const code = row.cells[0].textContent.toLowerCase();
            const name = row.cells[1].textContent.toLowerCase();
            if (code.includes(searchTerm) || name.includes(searchTerm)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });

    // Close modal on escape key
    document.addEventListener('keydown', function(event) {
        if (event.key === "Escape" && !modal.classList.contains('hidden')) {
            closeCurrencyModal();
        }
    });
</script>
@endpush

@endsection
