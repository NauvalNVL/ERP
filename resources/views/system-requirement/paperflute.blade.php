@extends('layouts.app')

@section('title', 'Define Paper Flute')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Page Header -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold text-gray-900">Define Paper Flute</h1>
        <button onclick="openAddModal()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition-colors">
            <i class="fas fa-plus"></i>
            <span>Add New Flute</span>
        </button>
    </div>

    <!-- Main Content Card -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <!-- Toolbar -->
        <div class="p-4 border-b border-gray-200 flex flex-wrap gap-3 items-center">
            <div class="flex items-center gap-2">
                <button class="text-gray-600 hover:text-blue-600 p-2 rounded-lg hover:bg-blue-50 transition-colors">
                    <i class="fas fa-sync-alt"></i>
                </button>
                <div class="relative">
                    <input type="text" placeholder="Search flutes..." class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 w-64">
                    <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                </div>
            </div>
            <div class="flex-grow"></div>
            <div class="flex items-center gap-2">
                <button class="text-gray-600 hover:text-blue-600 p-2 rounded-lg hover:bg-blue-50 transition-colors" title="Export to Excel">
                    <i class="fas fa-file-excel"></i>
                </button>
                <button class="text-gray-600 hover:text-blue-600 p-2 rounded-lg hover:bg-blue-50 transition-colors" title="Print">
                    <i class="fas fa-print"></i>
                </button>
            </div>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Code</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Flute Height</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($paperFlutes as $flute)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $flute->code }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $flute->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $flute->description }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $flute->flute_height }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $flute->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $flute->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex items-center gap-2">
                                <button onclick="openEditModal('{{ $flute->id }}')" class="text-blue-600 hover:text-blue-900">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button onclick="confirmDelete('{{ $flute->id }}')" class="text-red-600 hover:text-red-900">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-200">
            <div class="flex items-center justify-between">
                <div class="text-sm text-gray-500">
                    Showing 1 to 10 of {{ count($paperFlutes) }} entries
                </div>
                <div class="flex items-center gap-2">
                    <button class="px-3 py-1 border border-gray-300 rounded-md text-sm hover:bg-gray-50">Previous</button>
                    <button class="px-3 py-1 bg-blue-600 text-white rounded-md text-sm">1</button>
                    <button class="px-3 py-1 border border-gray-300 rounded-md text-sm hover:bg-gray-50">Next</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add/Edit Modal -->
<div id="fluteModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-2xl mx-4">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900" id="modalTitle">Add New Flute</h3>
        </div>
        <form id="fluteForm" class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Code</label>
                    <input type="text" name="code" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Name</label>
                    <input type="text" name="name" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                    <textarea name="description" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Flute Height</label>
                    <input type="number" step="0.01" name="flute_height" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select name="is_active" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>
                </div>
            </div>
        </form>
        <div class="p-6 border-t border-gray-200 flex justify-end gap-3">
            <button onclick="closeModal()" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">Cancel</button>
            <button onclick="saveFlute()" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Save Changes</button>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-md mx-4">
        <div class="p-6">
            <div class="flex items-center justify-center w-12 h-12 mx-auto mb-4 rounded-full bg-red-100">
                <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 text-center mb-2">Confirm Deletion</h3>
            <p class="text-gray-500 text-center">Are you sure you want to delete this flute? This action cannot be undone.</p>
        </div>
        <div class="p-6 border-t border-gray-200 flex justify-center gap-3">
            <button onclick="closeDeleteModal()" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">Cancel</button>
            <button onclick="deleteFlute()" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">Delete</button>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    // Modal functionality
    function openAddModal() {
        document.getElementById('modalTitle').textContent = 'Add New Flute';
        document.getElementById('fluteForm').reset();
        document.getElementById('fluteModal').style.display = 'flex';
    }

    function openEditModal(id) {
        document.getElementById('modalTitle').textContent = 'Edit Flute';
        // Fetch and populate flute data
        document.getElementById('fluteModal').style.display = 'flex';
    }

    function closeModal() {
        document.getElementById('fluteModal').style.display = 'none';
    }

    function saveFlute() {
        // Implement save functionality
        closeModal();
    }

    function confirmDelete(id) {
        document.getElementById('deleteModal').style.display = 'flex';
    }

    function closeDeleteModal() {
        document.getElementById('deleteModal').style.display = 'none';
    }

    function deleteFlute() {
        // Implement delete functionality
        closeDeleteModal();
    }

    // Close modals when clicking outside
    window.onclick = function(event) {
        const fluteModal = document.getElementById('fluteModal');
        const deleteModal = document.getElementById('deleteModal');
        if (event.target === fluteModal) {
            closeModal();
        }
        if (event.target === deleteModal) {
            closeDeleteModal();
        }
    }
</script>
@endsection