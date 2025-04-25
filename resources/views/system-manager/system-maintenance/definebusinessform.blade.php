@extends('layouts.app')

@section('title', 'Define Business Form')

@section('content')
{{-- Header Section --}}
<div class="bg-gradient-to-r from-blue-700 to-indigo-600 p-6 rounded-t-lg shadow-lg mb-6">
    <h2 class="text-2xl font-bold text-white mb-2 flex items-center">
        <i class="fas fa-file-alt mr-3"></i> Define Business Form
    </h2>
    <p class="text-indigo-100">Create or update business form definitions.</p>
</div>

<div class="bg-white rounded-b-lg shadow-lg p-6">
    {{-- Display Success/Error Messages --}}
    @include('partials.alert-messages')

    {{-- Business Form --}}
    {{-- Jika $businessForm ada, kita dalam mode edit --}}
    @if(isset($businessForm))
        <form id="businessForm" action="{{ route('business-form.update', $businessForm->id) }}" method="POST">
        @method('PUT')
    @else
        <form id="businessForm" action="{{ route('business-form.store') }}" method="POST">
    @endif
        @csrf
        <input type="hidden" name="id" id="bf_id" value="{{ $businessForm->id ?? '' }}">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Kiri --}}
            <div>
                {{-- BF Code --}}
                <div class="mb-4">
                    <label for="bf_code" class="block text-sm font-medium text-gray-700 mb-1">BF Code <span class="text-red-500">*</span></label>
                    <div class="flex">
                        <input type="text" id="bf_code" name="bf_code" value="{{ old('bf_code', $businessForm->bf_code ?? '') }}" required
                               class="flex-grow mt-1 block w-full rounded-l-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 {{ $errors->has('bf_code') ? 'border-red-500' : '' }}">
                        <button type="button" id="searchButton" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded-r-md flex items-center justify-center focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500" title="Search Business Form">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                     @error('bf_code') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- BF Name --}}
                <div class="mb-4">
                    <label for="bf_name" class="block text-sm font-medium text-gray-700 mb-1">BF Name <span class="text-red-500">*</span></label>
                    <input type="text" id="bf_name" name="bf_name" value="{{ old('bf_name', $businessForm->bf_name ?? '') }}" required
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 {{ $errors->has('bf_name') ? 'border-red-500' : '' }}">
                     @error('bf_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- BF Group --}}
                <div class="mb-4">
                    <label for="bf_group" class="block text-sm font-medium text-gray-700 mb-1">BF Group <span class="text-red-500">*</span></label>
                    <input type="text" id="bf_group" name="bf_group" value="{{ old('bf_group', $businessForm->bf_group ?? '') }}" required
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 {{ $errors->has('bf_group') ? 'border-red-500' : '' }}">
                    @error('bf_group') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                 {{-- BF ISO# --}}
                <div class="mb-4">
                    <label for="bf_iso" class="block text-sm font-medium text-gray-700 mb-1">BF ISO#</label>
                    <input type="text" id="bf_iso" name="bf_iso" value="{{ old('bf_iso', $businessForm->bf_iso ?? '') }}"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 {{ $errors->has('bf_iso') ? 'border-red-500' : '' }}">
                    @error('bf_iso') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- Kanan --}}
            <div>
                 {{-- BF CHECK BY --}}
                <fieldset class="border border-gray-300 p-4 rounded-md mb-6">
                    <legend class="text-sm font-medium text-gray-700 px-2">BF CHECK BY</legend>
                    <div class="mb-4">
                        <label for="check_by_name" class="block text-sm font-medium text-gray-500 mb-1">NAME:</label>
                        <input type="text" id="check_by_name" name="check_by_name" value="{{ old('check_by_name', $businessForm->check_by_name ?? '') }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    </div>
                    <div>
                        <label for="check_by_title" class="block text-sm font-medium text-gray-500 mb-1">TITLE:</label>
                        <input type="text" id="check_by_title" name="check_by_title" value="{{ old('check_by_title', $businessForm->check_by_title ?? '') }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    </div>
                </fieldset>

                {{-- BF APPROVE BY --}}
                <fieldset class="border border-gray-300 p-4 rounded-md">
                    <legend class="text-sm font-medium text-gray-700 px-2">BF APPROVE BY</legend>
                     <div class="mb-4">
                        <label for="approve_by_name" class="block text-sm font-medium text-gray-500 mb-1">NAME:</label>
                        <input type="text" id="approve_by_name" name="approve_by_name" value="{{ old('approve_by_name', $businessForm->approve_by_name ?? '') }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    </div>
                    <div>
                        <label for="approve_by_title" class="block text-sm font-medium text-gray-500 mb-1">TITLE:</label>
                        <input type="text" id="approve_by_title" name="approve_by_title" value="{{ old('approve_by_title', $businessForm->approve_by_title ?? '') }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    </div>
                </fieldset>
            </div>
        </div>

        {{-- Action Buttons --}}
        <div class="mt-8 flex justify-end space-x-3 border-t pt-4">
             <button type="button" onclick="window.location='{{ route('business-form.index') }}'"
                    class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 active:bg-gray-500 focus:outline-none focus:border-gray-500 focus:ring focus:ring-gray-200 disabled:opacity-25 transition">
                <i class="fas fa-times mr-2"></i>Cancel / New
            </button>
            @if(isset($businessForm))
                <button type="submit" formaction="{{ route('business-form.destroy', $businessForm->id) }}" formmethod="POST" id="deleteButton"
                        class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-800 focus:outline-none focus:border-red-800 focus:ring focus:ring-red-300 disabled:opacity-25 transition"
                        onclick="event.preventDefault(); if(confirm('Are you sure you want to delete this Business Form?')) { document.getElementById('deleteForm').submit(); }">
                     <i class="fas fa-trash-alt mr-2"></i>Delete
                </button>
                <button type="submit" id="saveButton"
                    class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-800 focus:outline-none focus:border-green-800 focus:ring focus:ring-green-300 disabled:opacity-25 transition">
                    <i class="fas fa-save mr-2"></i>Update
                </button>
            @else
                <button type="submit" id="saveButton"
                        class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-800 focus:outline-none focus:border-green-800 focus:ring focus:ring-green-300 disabled:opacity-25 transition">
                     <i class="fas fa-save mr-2"></i>Save
                </button>
            @endif
        </div>
    </form>

    {{-- Hidden Delete Form --}}
    @if(isset($businessForm))
    <form id="deleteForm" action="{{ route('business-form.destroy', $businessForm->id) }}" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>
    @endif

</div>

{{-- Search Modal --}}
<div id="businessFormSearchModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full flex items-center justify-center" style="display: none; z-index: 50;">
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
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
            </div>

            {{-- Results Table --}}
            <div class="overflow-x-auto rounded-lg border border-gray-200 shadow-sm max-h-60 overflow-y-auto">
                 <table class="min-w-full divide-y divide-gray-200 bg-white">
                    <thead class="bg-gray-100 sticky top-0">
                        <tr>
                            <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Code</th>
                            <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                            <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Group</th>
                            <th scope="col" class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                        </tr>
                    </thead>
                    <tbody id="modalResultsBody" class="bg-white divide-y divide-gray-200">
                        {{-- Results will be loaded here via JS --}}
                        <tr><td colspan="4" class="text-center p-4 text-gray-500">Loading...</td></tr>
                    </tbody>
                </table>
            </div>

            {{-- Pagination (jika diperlukan, tambahkan logika JS) --}}
            <div id="modalPagination" class="mt-4"></div>

        </div>
        <div class="mt-4 text-right border-t pt-3">
             <button type="button" class="px-4 py-2 bg-gray-500 text-white text-base font-medium rounded-md shadow-sm hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-300" onclick="closeModal()">
                Close
            </button>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    const searchButton = document.getElementById('searchButton');
    const modal = document.getElementById('businessFormSearchModal');
    const modalSearchInput = document.getElementById('modalSearchInput');
    const resultsBody = document.getElementById('modalResultsBody');
    const paginationContainer = document.getElementById('modalPagination');
    const businessForm = document.getElementById('businessForm');

    // --- Form Elements ---
    const bfIdInput = document.getElementById('bf_id');
    const bfCodeInput = document.getElementById('bf_code');
    const bfNameInput = document.getElementById('bf_name');
    const bfGroupInput = document.getElementById('bf_group');
    const bfIsoInput = document.getElementById('bf_iso');
    const checkNameInput = document.getElementById('check_by_name');
    const checkTitleInput = document.getElementById('check_by_title');
    const approveNameInput = document.getElementById('approve_by_name');
    const approveTitleInput = document.getElementById('approve_by_title');
    const saveButton = document.getElementById('saveButton');
    const deleteButtonContainer = document.querySelector('#deleteButton')?.parentElement; // Container for delete button logic

    let searchAbortController = null; // To handle rapid search requests
    let searchTimeout = null;

    function openModal() {
        modal.style.display = 'flex';
        fetchResults(); // Load initial results
    }

    function closeModal() {
        modal.style.display = 'none';
        modalSearchInput.value = ''; // Clear search on close
    }

    async function fetchResults(url = '{{ route("business-form.search") }}', query = '') {
        // Abort previous request if any
        if (searchAbortController) {
            searchAbortController.abort();
        }
        searchAbortController = new AbortController();
        const signal = searchAbortController.signal;

        resultsBody.innerHTML = '<tr><td colspan="4" class="text-center p-4 text-gray-500">Loading...</td></tr>';
        paginationContainer.innerHTML = ''; // Clear pagination

        const fetchUrl = new URL(url);
        if (query) {
             fetchUrl.searchParams.append('search', query);
        }
        fetchUrl.searchParams.append('page', new URL(url).searchParams.get('page') || 1); // Ensure page param


        try {
            const response = await fetch(fetchUrl.toString(), {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest' // Important for Laravel ajax() check
                },
                signal // Pass the signal to the fetch request
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const data = await response.json();
            renderResults(data.data);
            renderPagination(data); // data contains pagination info (links, current_page, etc.)

        } catch (error) {
            if (error.name === 'AbortError') {
                console.log('Search fetch aborted');
            } else {
                console.error("Error fetching search results:", error);
                resultsBody.innerHTML = '<tr><td colspan="4" class="text-center p-4 text-red-500">Error loading results.</td></tr>';
            }
        } finally {
             searchAbortController = null; // Reset controller
        }
    }

    function renderResults(forms) {
        resultsBody.innerHTML = ''; // Clear previous results
        if (forms && forms.length > 0) {
            forms.forEach(form => {
                const row = document.createElement('tr');
                row.classList.add('hover:bg-gray-50', 'cursor-pointer');
                row.innerHTML = `
                    <td class="px-4 py-2 whitespace-nowrap text-sm font-medium text-gray-900">${form.bf_code}</td>
                    <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-700">${form.bf_name}</td>
                    <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-700">${form.bf_group}</td>
                    <td class="px-4 py-2 whitespace-nowrap text-sm font-medium text-center">
                        <button type="button" class="text-indigo-600 hover:text-indigo-900 text-xs" onclick='selectForm(${JSON.stringify(form)})'>Select</button>
                    </td>
                `;
                // Add data attributes if needed, e.g., row.dataset.id = form.id;
                resultsBody.appendChild(row);
            });
        } else {
            resultsBody.innerHTML = '<tr><td colspan="4" class="text-center p-4 text-gray-500">No business forms found.</td></tr>';
        }
    }

    function renderPagination(paginationData) {
        paginationContainer.innerHTML = ''; // Clear existing pagination

        if (!paginationData || !paginationData.links || paginationData.links.length <= 3) { // <=3 because prev, next, current only means one page
            return; // No need for pagination if only one page or less
        }

        const paginationNav = document.createElement('nav');
        paginationNav.setAttribute('role', 'navigation');
        paginationNav.setAttribute('aria-label', 'Pagination Navigation');
        paginationNav.classList.add('flex', 'items-center', 'justify-between');

        const linksContainer = document.createElement('div');
        linksContainer.classList.add('flex', 'justify-between', 'flex-1', 'sm:hidden');
        // Mobile pagination (Prev/Next buttons) can be added here if needed

        const desktopLinksContainer = document.createElement('div');
        desktopLinksContainer.classList.add('hidden', 'sm:flex-1', 'sm:flex', 'sm:items-center', 'sm:justify-between');

        // Info Text (e.g., "Showing 1 to 10 of 57 results")
        const infoDiv = document.createElement('div');
        infoDiv.innerHTML = `<p class="text-sm text-gray-700 leading-5">Showing <span class="font-medium">${paginationData.from || 0}</span> to <span class="font-medium">${paginationData.to || 0}</span> of <span class="font-medium">${paginationData.total}</span> results</p>`;


        // Link Buttons
        const linksDiv = document.createElement('div');
        linksDiv.innerHTML = `<span class="relative z-0 inline-flex shadow-sm rounded-md"> ${paginationData.links.map(link => {
            let classes = "relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium border border-gray-300 leading-5 focus:z-10 focus:outline-none focus:ring ring-gray-300 focus:border-blue-300 transition ease-in-out duration-150";
            if (link.active) {
                classes += " bg-blue-50 text-blue-600"; // Active page
            } else if (!link.url) {
                 classes += " bg-white text-gray-500 cursor-not-allowed"; // Disabled link (e.g., ...)
             } else {
                 classes += " bg-white text-gray-700 hover:text-gray-500"; // Regular link
             }
             // Add rounded corners for first/last visible links
            if (link.label.includes('Previous')) classes += ' rounded-l-md';
            if (link.label.includes('Next')) classes += ' rounded-r-md';

            const disabled = !link.url ? ' disabled' : '';
            const clickHandler = link.url ? `onclick="event.preventDefault(); fetchResults('${link.url}', modalSearchInput.value)"` : '';
            const ariaCurrent = link.active ? ' aria-current="page"' : '';

             return `<button type="button" class="${classes}" ${clickHandler} ${disabled} ${ariaCurrent}>${link.label.replace('&laquo;', '«').replace('&raquo;', '»')}</button>`;
        }).join('')} </span>`;


        desktopLinksContainer.appendChild(infoDiv);
        desktopLinksContainer.appendChild(linksDiv);
        paginationNav.appendChild(linksContainer); // Add mobile container (even if empty for now)
        paginationNav.appendChild(desktopLinksContainer);
        paginationContainer.appendChild(paginationNav);
    }


    function selectForm(form) {
        // Populate the main form
        bfIdInput.value = form.id;
        bfCodeInput.value = form.bf_code;
        bfNameInput.value = form.bf_name;
        bfGroupInput.value = form.bf_group;
        bfIsoInput.value = form.bf_iso || '';
        checkNameInput.value = form.check_by_name || '';
        checkTitleInput.value = form.check_by_title || '';
        approveNameInput.value = form.approve_by_name || '';
        approveTitleInput.value = form.approve_by_title || '';

        // Change form action to update
        businessForm.action = '{{ url("system-manager/system-maintenance/business-form") }}/' + form.id;
        // Add PUT method spoofing
        let methodInput = businessForm.querySelector('input[name="_method"]');
        if (!methodInput) {
            methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            businessForm.appendChild(methodInput);
        }
        methodInput.value = 'PUT';

        // Change Save button text to Update
        saveButton.innerHTML = '<i class="fas fa-save mr-2"></i>Update';

        // Show Delete button if it exists and was hidden
        if (deleteButtonContainer) {
            deleteButtonContainer.style.display = 'inline-flex'; // Or your default display type
             // Update delete form action as well
            const deleteForm = document.getElementById('deleteForm');
            if(deleteForm) {
                deleteForm.action = '{{ url("system-manager/system-maintenance/business-form") }}/' + form.id;
            }
        }


        closeModal();
    }

    // Event Listeners
    searchButton.addEventListener('click', openModal);

    modalSearchInput.addEventListener('keyup', () => {
        clearTimeout(searchTimeout); // Clear previous timeout
        searchTimeout = setTimeout(() => {
            fetchResults('{{ route("business-form.search") }}', modalSearchInput.value);
        }, 300); // Debounce search input
    });

    // Close modal if clicked outside the content
    modal.addEventListener('click', (event) => {
        if (event.target === modal) {
            closeModal();
        }
    });

    // Close modal on Escape key
    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && modal.style.display !== 'none') {
            closeModal();
        }
    });

     // Initial setup: Hide delete button container if we are not in edit mode
    document.addEventListener('DOMContentLoaded', () => {
        if (!bfIdInput.value && deleteButtonContainer) {
            deleteButtonContainer.style.display = 'none';
        }
    });


</script>
@endpush

@push('styles')
{{-- Add any specific styles if needed --}}
@endpush


</rewritten_file>
