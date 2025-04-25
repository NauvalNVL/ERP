@extends('layouts.app')

@section('title', 'Define Business Form')

@section('content')
{{-- Header Section --}}
<div class="bg-gradient-to-r from-blue-700 to-indigo-600 p-6 rounded-t-lg shadow-lg">
    <h2 class="text-2xl font-bold text-white mb-2 flex items-center">
        <i class="fas fa-file-alt mr-3"></i> Define Business Form
    </h2>
    <p class="text-indigo-100">Create and manage business form definitions.</p>
</div>

<div class="bg-white rounded-b-lg shadow-lg p-6">
    {{-- Display Success/Error Messages --}}
    @include('partials.alert-messages')

    <!-- Main Content -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Left Column - Main Content -->
        <div class="lg:col-span-2">
            <div class="bg-white p-6 rounded-lg shadow-md border-t-4 border-blue-500">
                <div class="flex items-center mb-6 pb-2 border-b border-gray-200">
                    <div class="p-2 {{ isset($businessForm) ? 'bg-orange-500' : 'bg-blue-500' }} rounded-lg mr-3">
                        <i class="fas {{ isset($businessForm) ? 'fa-edit' : 'fa-plus' }} text-white"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800">
                        {{ isset($businessForm) ? 'Edit Business Form' : 'Business Form Management' }}
                    </h3>
                </div>

                <!-- Header with navigation buttons -->
                <div class="flex items-center space-x-2 mb-6">
                    <button type="button" onclick="window.location='{{ route('business-form.index') }}'" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded flex items-center space-x-2 transform active:translate-y-px">
                        <i class="fas fa-power-off"></i>
                    </button>
                    <button type="button" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded flex items-center space-x-2 transform active:translate-y-px">
                        <i class="fas fa-arrow-right"></i>
                    </button>
                    <button type="button" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded flex items-center space-x-2 transform active:translate-y-px">
                        <i class="fas fa-arrow-left"></i>
                    </button>
                    <button type="button" id="searchButton" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded flex items-center space-x-2 transform active:translate-y-px">
                        <i class="fas fa-search"></i>
                    </button>
                    <button type="submit" form="businessForm" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded flex items-center space-x-2 transform active:translate-y-px">
                        <i class="fas fa-save"></i>
                    </button>
                </div>

                <!-- Business Form -->
                @if(isset($businessForm))
                    <form id="businessForm" action="{{ route('business-form.update', $businessForm->id) }}" method="POST">
                    @method('PUT')
                @else
                    <form id="businessForm" action="{{ route('business-form.store') }}" method="POST">
                @endif
                    @csrf
                    <input type="hidden" name="id" id="bf_id" value="{{ $businessForm->id ?? '' }}">

                    <div class="grid grid-cols-1 gap-6">
                        <!-- First Row - Code and Record Selection -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                            <div class="col-span-2">
                                <label for="bf_code" class="block text-sm font-medium text-gray-700 mb-1">BF Code <span class="text-red-500">*</span></label>
                                <div class="relative flex">
                                    <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500">
                                        <i class="fas fa-hashtag"></i>
                                    </span>
                                    <input type="text" id="bf_code" name="bf_code" 
                                           value="{{ old('bf_code', $businessForm->bf_code ?? '') }}" 
                                           class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none border border-gray-300 focus:ring-blue-500 focus:border-blue-500 @error('bf_code') border-red-500 @enderror" 
                                           required>
                                    <button type="button" id="searchButton" class="inline-flex items-center px-3 py-2 border border-l-0 border-gray-300 bg-blue-500 hover:bg-blue-600 text-white rounded-r-md transition-colors transform active:translate-y-px">
                                        <i class="fas fa-table"></i>
                                    </button>
                                </div>
                                @error('bf_code')
                                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div class="col-span-1">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Record:</label>
                                <button type="button" id="searchButton" class="w-full flex items-center justify-center px-4 py-2 bg-indigo-500 hover:bg-indigo-600 text-white rounded transition-colors transform active:translate-y-px">
                                    <i class="fas fa-list-ul mr-2"></i> Select Record
                                </button>
                            </div>
                        </div>

                        <!-- BF Name -->
                        <div>
                            <label for="bf_name" class="block text-sm font-medium text-gray-700 mb-1">BF Name <span class="text-red-500">*</span></label>
                            <div class="relative flex">
                                <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500">
                                    <i class="fas fa-font"></i>
                                </span>
                                <input type="text" id="bf_name" name="bf_name" 
                                       value="{{ old('bf_name', $businessForm->bf_name ?? '') }}" 
                                       class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none rounded-r-md border border-gray-300 focus:ring-blue-500 focus:border-blue-500 @error('bf_name') border-red-500 @enderror" 
                                       required>
                            </div>
                            @error('bf_name')
                                <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- BF Group -->
                        <div>
                            <label for="bf_group" class="block text-sm font-medium text-gray-700 mb-1">BF Group <span class="text-red-500">*</span></label>
                            <div class="relative flex">
                                <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500">
                                    <i class="fas fa-layer-group"></i>
                                </span>
                                <input type="text" id="bf_group" name="bf_group" 
                                       value="{{ old('bf_group', $businessForm->bf_group ?? '') }}" 
                                       class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none rounded-r-md border border-gray-300 focus:ring-blue-500 focus:border-blue-500 @error('bf_group') border-red-500 @enderror" 
                                       required>
                            </div>
                            @error('bf_group')
                                <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- BF ISO -->
                        <div>
                            <label for="bf_iso" class="block text-sm font-medium text-gray-700 mb-1">BF ISO#</label>
                            <div class="relative flex">
                                <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500">
                                    <i class="fas fa-certificate"></i>
                                </span>
                                <input type="text" id="bf_iso" name="bf_iso" 
                                       value="{{ old('bf_iso', $businessForm->bf_iso ?? '') }}" 
                                       class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none rounded-r-md border border-gray-300 focus:ring-blue-500 focus:border-blue-500 @error('bf_iso') border-red-500 @enderror">
                            </div>
                            @error('bf_iso')
                                <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Check By Section -->
                        <div class="border border-gray-200 rounded-lg p-4">
                            <h4 class="text-sm font-medium text-gray-700 mb-3">BF CHECK BY</h4>
                            <div class="grid grid-cols-1 gap-4">
                                <div>
                                    <label for="check_by_name" class="block text-sm font-medium text-gray-500 mb-1">NAME:</label>
                                    <div class="relative flex">
                                        <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500">
                                            <i class="fas fa-user"></i>
                                        </span>
                                        <input type="text" id="check_by_name" name="check_by_name" 
                                               value="{{ old('check_by_name', $businessForm->check_by_name ?? '') }}" 
                                               class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none rounded-r-md border border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                                    </div>
                                </div>
                                <div>
                                    <label for="check_by_title" class="block text-sm font-medium text-gray-500 mb-1">TITLE:</label>
                                    <div class="relative flex">
                                        <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500">
                                            <i class="fas fa-id-badge"></i>
                                        </span>
                                        <input type="text" id="check_by_title" name="check_by_title" 
                                               value="{{ old('check_by_title', $businessForm->check_by_title ?? '') }}" 
                                               class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none rounded-r-md border border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Approve By Section -->
                        <div class="border border-gray-200 rounded-lg p-4">
                            <h4 class="text-sm font-medium text-gray-700 mb-3">BF APPROVE BY</h4>
                            <div class="grid grid-cols-1 gap-4">
                                <div>
                                    <label for="approve_by_name" class="block text-sm font-medium text-gray-500 mb-1">NAME:</label>
                                    <div class="relative flex">
                                        <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500">
                                            <i class="fas fa-user"></i>
                                        </span>
                                        <input type="text" id="approve_by_name" name="approve_by_name" 
                                               value="{{ old('approve_by_name', $businessForm->approve_by_name ?? '') }}" 
                                               class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none rounded-r-md border border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                                    </div>
                                </div>
                                <div>
                                    <label for="approve_by_title" class="block text-sm font-medium text-gray-500 mb-1">TITLE:</label>
                                    <div class="relative flex">
                                        <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500">
                                            <i class="fas fa-id-badge"></i>
                                        </span>
                                        <input type="text" id="approve_by_title" name="approve_by_title" 
                                               value="{{ old('approve_by_title', $businessForm->approve_by_title ?? '') }}" 
                                               class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none rounded-r-md border border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Right Column - Quick Links & Info -->
        <div class="lg:col-span-1">
            <!-- Business Form Info Card -->
            <div class="bg-white p-6 rounded-lg shadow-md border-t-4 border-indigo-500 mb-6">
                <div class="flex items-center mb-4 pb-2 border-b border-gray-200">
                    <div class="p-2 bg-indigo-500 rounded-lg mr-3">
                        <i class="fas fa-info-circle text-white"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800">Form Information</h3>
                </div>

                <div class="space-y-4">
                    <div class="p-4 bg-indigo-50 rounded-lg">
                        <h4 class="text-sm font-semibold text-indigo-800 uppercase tracking-wider mb-2">Instructions</h4>
                        <ul class="list-disc pl-5 text-sm text-gray-600 space-y-1">
                            <li>Enter a unique business form code</li>
                            <li>Group forms by department or function</li>
                            <li>ISO number format: MBI-FM-XXX-000</li>
                            <li>Specify check and approval authorities</li>
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
                    <a href="#" class="flex items-center p-3 bg-purple-50 rounded-lg hover:bg-purple-100 transition-colors">
                        <div class="p-2 bg-purple-500 rounded-full mr-3">
                            <i class="fas fa-print text-white text-sm"></i>
                        </div>
                        <div>
                            <p class="font-medium text-purple-900">Print Forms</p>
                            <p class="text-xs text-purple-700">Print business form list</p>
                        </div>
                    </a>

                    <a href="#" class="flex items-center p-3 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                        <div class="p-2 bg-blue-500 rounded-full mr-3">
                            <i class="fas fa-file-export text-white text-sm"></i>
                        </div>
                        <div>
                            <p class="font-medium text-blue-900">Export Forms</p>
                            <p class="text-xs text-blue-700">Export to Excel/PDF</p>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Business Form Selection Modal --}}
<div id="businessFormSearchModal" class="fixed inset-0 z-50 bg-black bg-opacity-50 overflow-y-auto h-full w-full flex items-center justify-center hidden">
    <div class="relative mx-auto p-5 border w-full max-w-2xl shadow-lg rounded-md bg-white">
        <div class="flex justify-between items-center border-b pb-3 mb-3">
            <h3 class="text-lg font-medium text-gray-900">Search Business Form</h3>
            <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center" onclick="closeModal()">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
            </button>
        </div>
        <div class="mt-3">
            {{-- Search Input --}}
            <div class="mb-4">
                <input type="text" id="modalSearchInput" placeholder="Search by Code, Name, or Group..."
                       class="shadow-sm appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            
            {{-- Table --}}
            <div class="overflow-x-auto rounded-lg border border-gray-200">
                <table class="min-w-full divide-y divide-gray-200" id="businessFormModalTable">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Code</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Group</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        {{-- Results will be loaded here via JS --}}
                    </tbody>
                </table>
            </div>
        </div>
        <div class="mt-4 text-right border-t pt-3">
            <button type="button" onclick="closeModal()" class="px-4 py-2 bg-gray-500 text-white text-base font-medium rounded-md shadow-sm hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-300">
                Close
            </button>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    const modal = document.getElementById('businessFormSearchModal');
    const searchInput = document.getElementById('modalSearchInput');
    const tableBody = document.getElementById('businessFormModalTable').querySelector('tbody');
    let selectedRow = null;
    let searchTimeout = null;

    async function fetchResults(query = '') {
        try {
            tableBody.innerHTML = '<tr><td colspan="4" class="text-center p-4 text-gray-500">Loading...</td></tr>';
            
            const response = await fetch(`{{ route('business-form.search') }}?search=${query}`, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const data = await response.json();
            renderResults(data.data);

        } catch (error) {
            console.error("Error fetching search results:", error);
            tableBody.innerHTML = '<tr><td colspan="4" class="text-center p-4 text-red-500">Error loading results.</td></tr>';
        }
    }

    function renderResults(forms) {
        tableBody.innerHTML = '';
        
        if (forms && forms.length > 0) {
            forms.forEach(form => {
                const row = document.createElement('tr');
                row.classList.add('hover:bg-blue-50', 'cursor-pointer');
                row.innerHTML = `
                    <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900">${form.bf_code || ''}</td>
                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-700">${form.bf_name || ''}</td>
                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-700">${form.bf_group || ''}</td>
                    <td class="px-4 py-3 whitespace-nowrap text-sm text-center">
                        <button type="button" class="text-blue-600 hover:text-blue-900" 
                                onclick="selectForm(${JSON.stringify(form).replace(/"/g, '&quot;')})">
                            Select
                        </button>
                    </td>
                `;
                tableBody.appendChild(row);
            });
        } else {
            tableBody.innerHTML = '<tr><td colspan="4" class="text-center p-4 text-gray-500">No business forms found.</td></tr>';
        }
    }

    function selectForm(form) {
        // Populate form fields
        document.getElementById('bf_id').value = form.id || '';
        document.getElementById('bf_code').value = form.bf_code || '';
        document.getElementById('bf_name').value = form.bf_name || '';
        document.getElementById('bf_group').value = form.bf_group || '';
        document.getElementById('bf_iso').value = form.bf_iso || '';
        document.getElementById('check_by_name').value = form.check_by_name || '';
        document.getElementById('check_by_title').value = form.check_by_title || '';
        document.getElementById('approve_by_name').value = form.approve_by_name || '';
        document.getElementById('approve_by_title').value = form.approve_by_title || '';

        // Update form action for edit mode
        const businessForm = document.getElementById('businessForm');
        businessForm.action = `{{ url('system-manager/system-maintenance/business-form') }}/${form.id}`;
        
        // Add PUT method for edit mode
        let methodInput = businessForm.querySelector('input[name="_method"]');
        if (!methodInput) {
            methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            businessForm.appendChild(methodInput);
        }
        methodInput.value = 'PUT';

        closeModal();
    }

    function openModal() {
        modal.classList.remove('hidden');
        searchInput.focus();
        fetchResults(); // Load initial results when modal opens
    }

    function closeModal() {
        modal.classList.add('hidden');
        searchInput.value = '';
    }

    // Event Listeners
    document.querySelectorAll('[id^="searchButton"]').forEach(button => {
        button.addEventListener('click', openModal);
    });

    // Search input handler with debounce
    searchInput.addEventListener('keyup', () => {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            fetchResults(searchInput.value);
        }, 300);
    });

    // Close modal if clicked outside
    modal.addEventListener('click', (event) => {
        if (event.target === modal) {
            closeModal();
        }
    });

    // Close modal on Escape key
    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && !modal.classList.contains('hidden')) {
            closeModal();
        }
    });
</script>
@endpush

@push('styles')
{{-- Add any specific styles if needed --}}
@endpush


</rewritten_file>
