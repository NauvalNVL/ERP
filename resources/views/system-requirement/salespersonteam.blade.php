@extends('layouts.app')

@section('title', 'Define Salesperson Team')

@section('header', 'Define Salesperson Team')

@section('content')
@if(isset($error))
<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
    <strong class="font-bold">Error!</strong>
    <span class="block sm:inline">{{ $error }}</span>
    <p class="mt-2">
        Silakan jalankan perintah berikut di terminal untuk mengatur database:
        <pre class="bg-gray-800 text-white p-2 rounded mt-1 overflow-x-auto"></pre>
    </p>
</div>
@endif

<div class="bg-white rounded-lg shadow-md overflow-hidden">
    <!-- Toolbar -->
    <div class="p-3 flex space-x-2 border-b border-gray-200 bg-gray-100">
        <button type="button" onclick="showAddModal()" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-sm flex items-center">
            <i class="fas fa-plus mr-1"></i> Add
        </button>
        <button type="button" onclick="showDeleteModal()" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm flex items-center" id="deleteButton" disabled>
            <i class="fas fa-trash-alt mr-1"></i> Delete
        </button>
        <button type="button" onclick="showEditModal()" class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded text-sm flex items-center" id="editButton" disabled>
            <i class="fas fa-edit mr-1"></i> Edit
        </button>
    </div>

    <!-- Search and Filter -->
    <div class="p-3 border-b border-gray-200 bg-gray-50">
        <div class="flex items-center space-x-3">
            <div class="flex-1">
                <input type="text" id="searchInput" placeholder="Cari salesperson..." class="w-full px-3 py-1 border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-blue-500">
            </div>
            <div>
                <button type="button" onclick="searchData()" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-sm">
                    <i class="fas fa-search mr-1"></i> Cari
                </button>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-100">
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
            <tbody class="bg-white divide-y divide-gray-200" id="salespersonTable">
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
            </tbody>
        </table>
    </div>

    <!-- Position Options Footer -->
    <div class="bg-gray-100 p-3 border-t border-gray-200">
        <div class="text-sm text-gray-600 font-medium">Sales Team Position</div>
        <div class="flex flex-wrap gap-2 mt-1">
            <button class="px-2 py-1 bg-gray-200 hover:bg-gray-300 rounded text-sm">M-Manager</button>
            <button class="px-2 py-1 bg-gray-200 hover:bg-gray-300 rounded text-sm">T-Team Leader</button>
            <button class="px-2 py-1 bg-gray-200 hover:bg-gray-300 rounded text-sm">E-Executive</button>
        </div>
    </div>
</div>

<!-- Add Modal -->
<div id="addModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded shadow-lg w-full max-w-md">
        <div class="p-3 border-b border-gray-200 flex justify-between items-center bg-gray-50">
            <h3 class="text-lg font-semibold">Tambah Salesperson Team</h3>
            <button type="button" onclick="hideAddModal()" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form action="{{ route('salesperson-team.store') }}" method="POST">
            @csrf
            <div class="p-4">
                <div class="mb-3">
                    <label for="salesperson_code" class="block text-sm font-medium text-gray-700 mb-1">Salesperson Code</label>
                    <input type="text" name="salesperson_code" id="salesperson_code" required maxlength="4" class="w-full border border-gray-300 rounded px-3 py-1 focus:outline-none focus:ring-1 focus:ring-blue-500">
                </div>
                <div class="mb-3">
                    <label for="salesperson_name" class="block text-sm font-medium text-gray-700 mb-1">Salesperson Name</label>
                    <input type="text" name="salesperson_name" id="salesperson_name" required class="w-full border border-gray-300 rounded px-3 py-1 focus:outline-none focus:ring-1 focus:ring-blue-500">
                </div>
                <div class="mb-3">
                    <label for="sales_team_id" class="block text-sm font-medium text-gray-700 mb-1">Sales Team</label>
                    <select name="sales_team_id" id="sales_team_id" required class="w-full border border-gray-300 rounded px-3 py-1 focus:outline-none focus:ring-1 focus:ring-blue-500">
                        <option value="">Pilih Sales Team</option>
                        @foreach ($salesTeams as $team)
                        <option value="{{ $team->id }}">{{ $team->code }} - {{ $team->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label for="position" class="block text-sm font-medium text-gray-700 mb-1">Position</label>
                    <select name="position" id="position" required class="w-full border border-gray-300 rounded px-3 py-1 focus:outline-none focus:ring-1 focus:ring-blue-500">
                        <option value="">Pilih Position</option>
                        <option value="E - Executive">E - Executive</option>
                        <option value="M - Manager">M - Manager</option>
                        <option value="T - Team Leader">T - Team Leader</option>
                    </select>
                </div>
            </div>
            <div class="p-3 border-t border-gray-200 flex justify-end space-x-2 bg-gray-50">
                <button type="button" onclick="hideAddModal()" class="px-3 py-1 bg-gray-200 hover:bg-gray-300 rounded text-sm">Batal</button>
                <button type="submit" class="px-3 py-1 bg-blue-500 hover:bg-blue-600 text-white rounded text-sm">Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Modal -->
<div id="editModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded shadow-lg w-full max-w-md">
        <div class="p-3 border-b border-gray-200 flex justify-between items-center bg-gray-50">
            <h3 class="text-lg font-semibold">Edit Salesperson Team</h3>
            <button type="button" onclick="hideEditModal()" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form id="editForm" method="POST">
            @csrf
            @method('PUT')
            <div class="p-4">
                <div class="mb-3">
                    <label for="edit_salesperson_code" class="block text-sm font-medium text-gray-700 mb-1">Salesperson Code</label>
                    <input type="text" id="edit_salesperson_code" class="w-full border border-gray-300 rounded px-3 py-1 bg-gray-100 focus:outline-none" readonly>
                </div>
                <div class="mb-3">
                    <label for="edit_salesperson_name" class="block text-sm font-medium text-gray-700 mb-1">Salesperson Name</label>
                    <input type="text" name="salesperson_name" id="edit_salesperson_name" required class="w-full border border-gray-300 rounded px-3 py-1 focus:outline-none focus:ring-1 focus:ring-blue-500">
                </div>
                <div class="mb-3">
                    <label for="edit_sales_team_id" class="block text-sm font-medium text-gray-700 mb-1">Sales Team</label>
                    <select name="sales_team_id" id="edit_sales_team_id" required class="w-full border border-gray-300 rounded px-3 py-1 focus:outline-none focus:ring-1 focus:ring-blue-500">
                        <option value="">Pilih Sales Team</option>
                        @foreach ($salesTeams as $team)
                        <option value="{{ $team->id }}">{{ $team->code }} - {{ $team->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label for="edit_position" class="block text-sm font-medium text-gray-700 mb-1">Position</label>
                    <select name="position" id="edit_position" required class="w-full border border-gray-300 rounded px-3 py-1 focus:outline-none focus:ring-1 focus:ring-blue-500">
                        <option value="">Pilih Position</option>
                        <option value="E - Executive">E - Executive</option>
                        <option value="M - Manager">M - Manager</option>
                        <option value="T - Team Leader">T - Team Leader</option>
                    </select>
                </div>
            </div>
            <div class="p-3 border-t border-gray-200 flex justify-end space-x-2 bg-gray-50">
                <button type="button" onclick="hideEditModal()" class="px-3 py-1 bg-gray-200 hover:bg-gray-300 rounded text-sm">Batal</button>
                <button type="submit" class="px-3 py-1 bg-blue-500 hover:bg-blue-600 text-white rounded text-sm">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded shadow-lg w-full max-w-md">
        <div class="p-3 border-b border-gray-200 bg-gray-50">
            <h3 class="text-lg font-semibold">Konfirmasi Hapus</h3>
        </div>
        <div class="p-4">
            <p class="text-gray-700">Apakah Anda yakin ingin menghapus data salesperson yang dipilih?</p>
        </div>
        <div class="p-3 border-t border-gray-200 flex justify-end space-x-2 bg-gray-50">
            <button type="button" onclick="hideDeleteModal()" class="px-3 py-1 bg-gray-200 hover:bg-gray-300 rounded text-sm">Batal</button>
            <form id="deleteForm" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-3 py-1 bg-red-500 hover:bg-red-600 text-white rounded text-sm">Hapus</button>
            </form>
        </div>
    </div>
</div>

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

@section('scripts')
<script>
    // Variabel untuk menyimpan data yang dipilih
    let selectedItems = [];

    // Handler untuk checkbox "Select All"
    document.getElementById('selectAll')?.addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('.selectItem');
        checkboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
        updateButtonState();
    });

    // Handler untuk checkbox item individual
    document.querySelectorAll('.selectItem').forEach(checkbox => {
        checkbox.addEventListener('change', updateButtonState);
    });

    // Fungsi untuk memperbarui status tombol edit dan delete
    function updateButtonState() {
        selectedItems = Array.from(document.querySelectorAll('.selectItem:checked')).map(cb => cb.value);
        
        const deleteButton = document.getElementById('deleteButton');
        const editButton = document.getElementById('editButton');
        
        if (!deleteButton || !editButton) return;
        
        deleteButton.disabled = selectedItems.length === 0;
        editButton.disabled = selectedItems.length !== 1;
        
        if (selectedItems.length === 0) {
            deleteButton.classList.add('opacity-50', 'cursor-not-allowed');
            editButton.classList.add('opacity-50', 'cursor-not-allowed');
        } else {
            deleteButton.classList.remove('opacity-50', 'cursor-not-allowed');
            
            if (selectedItems.length === 1) {
                editButton.classList.remove('opacity-50', 'cursor-not-allowed');
            } else {
                editButton.classList.add('opacity-50', 'cursor-not-allowed');
            }
        }
    }

    // Fungsi untuk menampilkan/menyembunyikan modal
    function showAddModal() {
        document.getElementById('addModal').classList.remove('hidden');
    }

    function hideAddModal() {
        document.getElementById('addModal').classList.add('hidden');
        document.getElementById('salesperson_code').value = '';
        document.getElementById('salesperson_name').value = '';
        document.getElementById('sales_team_id').selectedIndex = 0;
        document.getElementById('position').selectedIndex = 0;
    }

    function showEditModal() {
        if (selectedItems.length !== 1) return;
        
        const id = selectedItems[0];
        
        // Di sini kita perlu mengambil data dari baris yang dipilih
        const selectedRow = document.querySelector(`.selectItem[value="${id}"]`).closest('tr');
        const cells = selectedRow.querySelectorAll('td');
        
        document.getElementById('edit_salesperson_code').value = cells[1].textContent.trim();
        document.getElementById('edit_salesperson_name').value = cells[2].textContent.trim();
        
        // Set sales team and position values based on displayed data
        const teamCode = cells[3].textContent.trim();
        const position = cells[5].textContent.trim();
        
        // Find the correct option in the select dropdown
        const teamOptions = document.getElementById('edit_sales_team_id').options;
        for (let i = 0; i < teamOptions.length; i++) {
            if (teamOptions[i].text.startsWith(teamCode)) {
                document.getElementById('edit_sales_team_id').selectedIndex = i;
                break;
            }
        }
        
        // Set position
        const positionOptions = document.getElementById('edit_position').options;
        for (let i = 0; i < positionOptions.length; i++) {
            if (positionOptions[i].value.includes(position.split(' ')[0])) {
                document.getElementById('edit_position').selectedIndex = i;
                break;
            }
        }
        
        // Set the form action URL
        document.getElementById('editForm').action = `/salesperson-team/${id}`;
        
        document.getElementById('editModal').classList.remove('hidden');
    }

    function hideEditModal() {
        document.getElementById('editModal').classList.add('hidden');
    }

    function showDeleteModal() {
        if (selectedItems.length === 0) return;
        
        // Set the form action URL for the first selected item
        // In a real application, you might want to handle multiple deletes
        document.getElementById('deleteForm').action = `/salesperson-team/${selectedItems[0]}`;
        
        document.getElementById('deleteModal').classList.remove('hidden');
    }

    function hideDeleteModal() {
        document.getElementById('deleteModal').classList.add('hidden');
    }

    // Function for searching
    function searchData() {
        const searchValue = document.getElementById('searchInput').value.toLowerCase();
        const rows = document.querySelectorAll('#salespersonTable tr');
        
        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            if (text.includes(searchValue)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }
    
    // Enter key pada input search akan memicu fungsi search
    document.getElementById('searchInput')?.addEventListener('keyup', function(e) {
        if (e.key === 'Enter') {
            searchData();
        }
    });

    // Auto-hide alerts after 5 seconds
    setTimeout(() => {
        const successAlert = document.getElementById('successAlert');
        const errorAlert = document.getElementById('errorAlert');
        
        if (successAlert) successAlert.style.display = 'none';
        if (errorAlert) errorAlert.style.display = 'none';
    }, 5000);

    // Initialize button states
    updateButtonState();
</script>
@endsection
