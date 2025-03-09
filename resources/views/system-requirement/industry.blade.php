@extends('layouts.app')

@section('title', 'Define Industry')

@section('header', 'Define Industry')

@section('content')
<div class="bg-white rounded-lg shadow-md p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-semibold">Define Industry</h2>
        <div class="flex space-x-2">
            <button id="addIndustryBtn" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                <i class="fas fa-plus mr-2"></i>Add New
            </button>
        </div>
    </div>

    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
        {{ session('success') }}
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

    <!-- Search & Filter -->
    <div class="mb-6 flex items-center">
        <div class="relative flex-1">
            <input type="text" id="searchInput" placeholder="Industry Code" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            <button type="button" id="searchBtn" class="absolute right-2 top-2 text-gray-500">
                <i class="fas fa-search"></i>
            </button>
        </div>
        <div class="ml-4">
            <button type="button" id="selectBtn" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
                Select
            </button>
        </div>
    </div>

    <!-- Industry Dialog -->
    <div id="industryDialog" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-lg shadow-lg w-1/2 max-w-2xl">
            <div class="p-4 border-b border-gray-200 bg-gray-100 flex justify-between items-center">
                <h3 class="text-lg font-semibold">Industry Table</h3>
                <button type="button" id="closeDialog" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <div class="p-6">
                <div class="mb-6 max-h-80 overflow-y-auto">
                    <table class="w-full border-collapse">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="border border-gray-300 px-4 py-2 text-left">CODE</th>
                                <th class="border border-gray-300 px-4 py-2 text-left">NAME</th>
                            </tr>
                        </thead>
                        <tbody id="industryTableBody">
                            @foreach($industries as $industry)
                            <tr class="cursor-pointer hover:bg-blue-100" data-id="{{ $industry->id }}">
                                <td class="border border-gray-300 px-4 py-2">{{ $industry->code }}</td>
                                <td class="border border-gray-300 px-4 py-2">{{ $industry->name }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="flex justify-end space-x-2">
                    <button type="button" id="selectIndustryBtn" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Select</button>
                    <button type="button" id="cancelSelectBtn" class="bg-gray-300 text-gray-700 px-4 py-2 rounded hover:bg-gray-400">Exit</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Industry Form Modal -->
    <div id="industryFormModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-lg shadow-lg w-1/2 max-w-md">
            <div class="p-4 border-b border-gray-200 bg-gray-100 flex justify-between items-center">
                <h3 id="modalTitle" class="text-lg font-semibold">Add Industry</h3>
                <button type="button" id="closeFormModal" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <form id="industryForm" method="POST" action="{{ route('industry.store') }}">
                @csrf
                <div id="methodField"></div>
                <div class="p-6">
                    <div class="mb-4">
                        <label for="code" class="block text-sm font-medium text-gray-700 mb-1">Industry Code</label>
                        <input type="text" id="code" name="code" maxlength="4" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    </div>
                    <div class="mb-6">
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Industry Name</label>
                        <input type="text" id="name" name="name" maxlength="100" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    </div>
                    <div class="flex justify-end space-x-2">
                        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Save</button>
                        <button type="button" id="cancelForm" class="bg-gray-300 text-gray-700 px-4 py-2 rounded hover:bg-gray-400">Cancel</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Data Table -->
    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border border-gray-300">
            <thead class="bg-gray-100">
                <tr>
                    <th class="py-2 px-4 border-b text-left">Code</th>
                    <th class="py-2 px-4 border-b text-left">Name</th>
                    <th class="py-2 px-4 border-b text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($industries as $industry)
                <tr class="hover:bg-gray-50">
                    <td class="py-2 px-4 border-b">{{ $industry->code }}</td>
                    <td class="py-2 px-4 border-b">{{ $industry->name }}</td>
                    <td class="py-2 px-4 border-b text-center">
                        <button type="button" class="text-blue-500 hover:text-blue-700 edit-btn mr-2" data-id="{{ $industry->id }}">
                            <i class="fas fa-edit"></i>
                        </button>
                        <form class="inline-block" method="POST" action="{{ route('industry.destroy', $industry->id) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-700" onclick="return confirm('Are you sure you want to delete this industry?');">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="py-4 text-center text-gray-500">No industries found</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Industry Dialog
        const searchBtn = document.getElementById('searchBtn');
        const selectBtn = document.getElementById('selectBtn');
        const industryDialog = document.getElementById('industryDialog');
        const closeDialog = document.getElementById('closeDialog');
        const cancelSelectBtn = document.getElementById('cancelSelectBtn');
        const selectIndustryBtn = document.getElementById('selectIndustryBtn');
        const searchInput = document.getElementById('searchInput');
        const industryTableBody = document.getElementById('industryTableBody');

        // Industry Form Modal
        const addIndustryBtn = document.getElementById('addIndustryBtn');
        const industryFormModal = document.getElementById('industryFormModal');
        const closeFormModal = document.getElementById('closeFormModal');
        const cancelForm = document.getElementById('cancelForm');
        const industryForm = document.getElementById('industryForm');
        const modalTitle = document.getElementById('modalTitle');
        const methodField = document.getElementById('methodField');
        const codeInput = document.getElementById('code');
        const nameInput = document.getElementById('name');

        // Edit buttons
        const editButtons = document.querySelectorAll('.edit-btn');

        if (searchBtn && selectBtn) {
            // Show industry dialog
            [searchBtn, selectBtn].forEach(btn => {
                btn.addEventListener('click', () => {
                    if (industryDialog) industryDialog.classList.remove('hidden');
                });
            });
        }

        if (closeDialog && cancelSelectBtn) {
            // Close industry dialog
            [closeDialog, cancelSelectBtn].forEach(btn => {
                btn.addEventListener('click', () => {
                    if (industryDialog) industryDialog.classList.add('hidden');
                });
            });
        }

        if (addIndustryBtn) {
            // Show add industry modal
            addIndustryBtn.addEventListener('click', () => {
                if (modalTitle) modalTitle.textContent = 'Add Industry';
                if (methodField) methodField.innerHTML = '';
                if (industryForm) industryForm.action = "{{ route('industry.store') }}";
                if (codeInput) codeInput.value = '';
                if (nameInput) nameInput.value = '';
                if (industryFormModal) industryFormModal.classList.remove('hidden');
            });
        }

        if (closeFormModal && cancelForm) {
            // Close industry form modal
            [closeFormModal, cancelForm].forEach(btn => {
                btn.addEventListener('click', () => {
                    if (industryFormModal) industryFormModal.classList.add('hidden');
                });
            });
        }

        if (editButtons && editButtons.length > 0) {
            // Edit industry
            editButtons.forEach(btn => {
                btn.addEventListener('click', async () => {
                    const id = btn.dataset.id;
                    if (modalTitle) modalTitle.textContent = 'Edit Industry';
                    if (methodField) methodField.innerHTML = `@method('PUT')`;
                    if (industryForm) industryForm.action = `/industry/${id}`;

                    try {
                        const response = await fetch(`/industry/${id}/edit`);
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        const industry = await response.json();
                        
                        if (codeInput) codeInput.value = industry.code;
                        if (nameInput) nameInput.value = industry.name;
                        
                        if (industryFormModal) industryFormModal.classList.remove('hidden');
                    } catch (error) {
                        console.error('Error fetching industry data:', error);
                        alert('Error loading industry data. Please try again.');
                    }
                });
            });
        }

        if (searchInput && industryTableBody) {
            // Filter industry table
            searchInput.addEventListener('keyup', () => {
                const searchTerm = searchInput.value.toUpperCase();
                const rows = industryTableBody.querySelectorAll('tr');

                rows.forEach(row => {
                    const codeCell = row.cells[0];
                    const nameCell = row.cells[1];
                    
                    if (codeCell && nameCell) {
                        const codeText = codeCell.textContent || codeCell.innerText;
                        const nameText = nameCell.textContent || nameCell.innerText;
                        
                        if (codeText.toUpperCase().indexOf(searchTerm) > -1 || 
                            nameText.toUpperCase().indexOf(searchTerm) > -1) {
                            row.style.display = "";
                        } else {
                            row.style.display = "none";
                        }
                    }
                });
            });

            // Select industry row
            const tableRows = industryTableBody.querySelectorAll('tr');
            tableRows.forEach(row => {
                row.addEventListener('click', () => {
                    tableRows.forEach(r => r.classList.remove('bg-blue-200'));
                    row.classList.add('bg-blue-200');
                });

                row.addEventListener('dblclick', () => {
                    const codeCell = row.cells[0];
                    if (searchInput && codeCell) {
                        searchInput.value = codeCell.textContent.trim();
                        if (industryDialog) industryDialog.classList.add('hidden');
                    }
                });
            });
        }

        if (selectIndustryBtn) {
            // Select button in dialog
            selectIndustryBtn.addEventListener('click', () => {
                if (industryTableBody) {
                    const selectedRow = industryTableBody.querySelector('tr.bg-blue-200');
                    if (selectedRow) {
                        const codeCell = selectedRow.cells[0];
                        if (searchInput && codeCell) {
                            searchInput.value = codeCell.textContent.trim();
                            if (industryDialog) industryDialog.classList.add('hidden');
                        }
                    }
                }
            });
        }
    });
</script>
@endsection