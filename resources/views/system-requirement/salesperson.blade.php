@extends('layouts.app')

@section('title', 'Define Salesperson')

@section('header', 'Define Salesperson')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 p-6">
    <div class="container mx-auto max-w-7xl">
        <!-- Loading Overlay -->
        <div id="loadingOverlay" class="loading-overlay hidden">
            <div class="loading-spinner"></div>
        </div>
        
        <div class="bg-white rounded-lg shadow-lg p-6">
            <!-- Header with navigation buttons -->
            <div class="flex items-center space-x-2 mb-6">
                <button type="button" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded flex items-center space-x-2">
                    <i class="fas fa-power-off"></i>
                </button>
                <button type="button" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded flex items-center space-x-2">
                    <i class="fas fa-arrow-right"></i>
                </button>
                <button type="button" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded flex items-center space-x-2">
                    <i class="fas fa-arrow-left"></i>
                </button>
                <button type="button" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded flex items-center space-x-2">
                    <i class="fas fa-search"></i>
                </button>
                <button type="button" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded flex items-center space-x-2">
                    <i class="fas fa-save"></i>
                </button>
            </div>

            <!-- Search Section -->
            <div class="flex justify-between items-center mb-6">
                <div class="flex items-center space-x-2">
                    <label class="text-gray-600">Salesperson Code:</label>
                    <input type="text" class="border border-gray-300 rounded px-3 py-2 w-48">
                    <button type="button" id="showSalespersonTableBtn" class="bg-gray-200 hover:bg-gray-300 px-2 py-2 border border-gray-400" onclick="openSalespersonModal()">
                        <i class="fas fa-table text-gray-600"></i>
                    </button>
                </div>
                <div class="flex items-center space-x-2">
                    <span class="text-gray-600">Record:</span>
                    <button type="button" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 border border-gray-300">
                        Select
                    </button>
                </div>
            </div>

            <!-- Debug Information -->
            <div class="debug-info">
                @if(empty($salespersons) || (isset($salespersons) && count($salespersons) == 0))
                    <div class="alert alert-danger mt-4">
                        <h4>Warning: No Salesperson found</h4>
                        <p>Please ensure database tables are set up correctly and run the salesperson seeder.</p>
                        <a href="{{ route('run.salesperson.seeder') }}" class="btn btn-warning">Run Salesperson Seeder</a>
                        <button class="btn btn-info" id="loadDataJsBtn">Load via JavaScript</button>
                    </div>
                @else
                    <div class="alert alert-success mt-4">
                        <h4>Found {{ count($salespersons) }} salespersons available</h4>
                    </div>
                @endif
            </div>

            <div class="flex justify-end mt-4">
                <button type="button" id="runSeederBtn" class="bg-orange-500 hover:bg-orange-600 text-white text-xs px-4 py-1 rounded mr-2">Run Salesperson Seeder</button>
            </div>
        </div>
    </div>
</div>

<!-- Salesperson Table Window -->
<div id="salespersonTableWindow" class="hidden fixed inset-0 z-50">
    <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white border-2 border-gray-500 shadow-lg" style="width: 700px;">
        <!-- Modal Header - Title Bar -->
        <div class="bg-blue-800 px-1 py-0.5 text-white flex justify-between items-center">
            <h3 class="text-xs font-normal">Salesperson Table</h3>
            <button type="button" onclick="closeModalX()" class="text-white hover:text-gray-200 focus:outline-none">
                <span class="text-lg">×</span>
            </button>
        </div>

        <!-- Table Content -->
        <div class="p-1">
            <div class="border border-gray-300 overflow-hidden">
                <table class="w-full" id="salespersonDataTable">
                    <thead>
                        <tr>
                            <th class="px-2 py-0.5 bg-gray-100 border-b border-r border-gray-300 text-left text-xs font-semibold">Code</th>
                            <th class="px-2 py-0.5 bg-gray-100 border-b border-r border-gray-300 text-left text-xs font-semibold">Name</th>
                            <th class="px-2 py-0.5 bg-gray-100 border-b border-r border-gray-300 text-left text-xs font-semibold">Sales Team</th>
                            <th class="px-2 py-0.5 bg-gray-100 border-b border-gray-300 text-left text-xs font-semibold">Position</th>
                        </tr>
                    </thead>
                    <tbody class="text-xs">
                        <!-- Data dari database akan ditampilkan di sini jika tersedia -->
                        <!-- Jika tidak ada data dari database, JavaScript akan mengisi dengan data dari SalespersonSeeder -->
                        @if(isset($salespersons) && count($salespersons) > 0)
                        @foreach($salespersons as $person)
                            <tr class="cursor-pointer" 
                                data-person-code="{{ $person->code }}"
                                data-person-name="{{ $person->name }}"
                                data-person-team="{{ $person->sales_team_id }}"
                                data-person-position="{{ $person->position }}"
                                data-person-user-id="{{ $person->user_id }}"
                                data-person-is-active="{{ $person->is_active }}"
                                onclick="selectRow(this); event.stopPropagation();"
                                ondblclick="openEditSalespersonModal(this)">
                                <td class="px-2 py-0.5 border-b border-r border-gray-300">{{ $person->code }}</td>
                                <td class="px-2 py-0.5 border-b border-r border-gray-300">{{ $person->name }}</td>
                                <td class="px-2 py-0.5 border-b border-r border-gray-300">
                                    @if($person->sales_team_id == 1)
                                        MBI
                                    @elseif($person->sales_team_id == 2)
                                        MANAGEMENT LOCAL
                                    @elseif($person->sales_team_id == 3)
                                        MANAGEMENT MNC
                                    @endif
                                </td>
                                <td class="px-2 py-0.5 border-b border-gray-300">{{ $person->position }}</td>
                            </tr>
                        @endforeach
                        @else
                            <tr>
                                <td colspan="4" class="px-2 py-4 text-center border-b border-gray-300">Tidak ada data salesperson yang tersedia.</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>

            <!-- Bottom Buttons with equal spacing -->
            <div class="flex justify-between mt-2">
                <button type="button" onclick="sortTableDirectly(0)" class="py-0.5 px-2 bg-gray-100 border border-gray-400 hover:bg-gray-200 text-xs w-24">By Code</button>
                <button type="button" onclick="sortTableDirectly(1)" class="py-0.5 px-2 bg-gray-100 border border-gray-400 hover:bg-gray-200 text-xs w-24">By Name</button>
                <button type="button" onclick="sortTableDirectly(2)" class="py-0.5 px-2 bg-gray-100 border border-gray-400 hover:bg-gray-200 text-xs w-24">By Team</button>
                <button type="button" onclick="editSelectedRow()" class="py-0.5 px-2 bg-gray-100 border border-gray-400 hover:bg-gray-200 text-xs w-24">Select</button>
                <button type="button" onclick="closeSalespersonModal()" class="py-0.5 px-2 bg-gray-100 border border-gray-400 hover:bg-gray-200 text-xs w-24">Exit</button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Salesperson Modal -->
<div id="editSalespersonModal" class="hidden fixed inset-0 z-50 bg-black bg-opacity-30">
    <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white border-2 border-gray-500 shadow-lg" style="width: 400px;">
        <!-- Modal Header - Title Bar -->
        <div class="bg-blue-800 px-1 py-0.5 text-white flex justify-between items-center">
            <h3 class="text-xs font-normal">Define Salesperson</h3>
            <button type="button" onclick="closeEditSalespersonModal()" class="text-white hover:text-gray-200 focus:outline-none">
                <span class="text-lg">×</span>
            </button>
        </div>

        <!-- Form Content -->
        <div class="p-4">
            <form id="editSalespersonForm" onsubmit="saveSalespersonChanges(); return false;">
                <div class="space-y-3">
                    <div class="flex items-center">
                        <label class="w-24 text-xs text-gray-600">Code:</label>
                        <input id="edit_person_code" type="text" class="border border-gray-300 rounded px-2 py-1 text-xs w-24" required>
                    </div>
                    <div class="flex items-center">
                        <label class="w-24 text-xs text-gray-600">Name:</label>
                        <input id="edit_person_name" type="text" class="border border-gray-300 rounded px-2 py-1 text-xs w-full" required>
                    </div>
                    <div class="flex items-center">
                        <label class="w-24 text-xs text-gray-600">Sales Team:</label>
                        <select id="edit_person_team" class="border border-gray-300 rounded px-2 py-1 text-xs w-48" required>
                            <option value="1">MBI</option>
                            <option value="2">MANAGEMENT LOCAL</option>
                            <option value="3">MANAGEMENT MNC</option>
                        </select>
                    </div>
                    <div class="flex items-center">
                        <label class="w-24 text-xs text-gray-600">Position:</label>
                        <select id="edit_person_position" class="border border-gray-300 rounded px-2 py-1 text-xs w-48" required>
                            <option value="E - Executive">E - Executive</option>
                            <option value="M - Manager">M - Manager</option>
                            <option value="S - Supervisor">S - Supervisor</option>
                        </select>
                    </div>
                    <div class="flex items-center">
                        <label class="w-24 text-xs text-gray-600">User ID:</label>
                        <input id="edit_person_user_id" type="text" class="border border-gray-300 rounded px-2 py-1 text-xs w-48">
                    </div>
                    <div class="flex items-center">
                        <label class="w-24 text-xs text-gray-600">Status:</label>
                        <select id="edit_person_is_active" class="border border-gray-300 rounded px-2 py-1 text-xs w-48" required>
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="flex justify-end mt-4">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white text-xs px-4 py-1 rounded mr-2">Save</button>
                    <button type="button" onclick="closeEditSalespersonModal()" class="bg-gray-300 hover:bg-gray-400 text-gray-800 text-xs px-4 py-1 rounded">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Sales Person Table Modal -->
<div class="modal fade" id="salespersonTableModal" tabindex="-1" role="dialog" aria-labelledby="salespersonTableModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="salespersonTableModalLabel">Sales Person Table</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table id="salespersonTable" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Code</th>
                            <th>Name</th>
                            <th>Sales Team</th>
                            <th>Position</th>
                            <th>User ID</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- This will be populated dynamically -->
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="editSelectedRowBtn">Edit Selected</button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Sales Person Modal -->
<div class="modal fade" id="editSalespersonModalBootstrap" tabindex="-1" role="dialog" aria-labelledby="editSalespersonModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editSalespersonModalLabel">Edit Sales Person</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editSalespersonFormBootstrap">
                    <input type="hidden" id="edit_id">
                    <div class="form-group">
                        <label for="edit_code">Sales Person Code</label>
                        <input type="text" class="form-control" id="edit_code">
                    </div>
                    <div class="form-group">
                        <label for="edit_name">Sales Person Name</label>
                        <input type="text" class="form-control" id="edit_name">
                    </div>
                    <div class="form-group">
                        <label for="edit_sales_team_id">Sales Team</label>
                        <select class="form-control" id="edit_sales_team_id">
                            <!-- This will be populated dynamically -->
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="edit_position">Position</label>
                        <input type="text" class="form-control" id="edit_position">
                    </div>
                    <div class="form-group">
                        <label for="edit_user_id">User ID</label>
                        <input type="text" class="form-control" id="edit_user_id">
                    </div>
                    <div class="form-group">
                        <label for="edit_is_active">Status</label>
                        <select class="form-control" id="edit_is_active">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="saveSalesperson">Save</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        console.log('DOM content loaded');
        
        // Test if the edit modal exists
        const editModal = document.getElementById('editSalespersonModal');
        if (editModal) {
            console.log('Edit modal found in DOM');
        } else {
            console.error('Edit modal not found in DOM');
        }
        
        // Add event listener for the Run Seeder button
        var runSeederBtn = document.getElementById('runSeederBtn');
        if (runSeederBtn) {
            runSeederBtn.addEventListener('click', function() {
                runSalespersonSeeder();
            });
        }
        
        // Function to run the salesperson seeder
        function runSalespersonSeeder() {
            showLoadingOverlay();
            
            // Use POST with CSRF token and handle JSON response
            $.ajax({
                url: '/run-salesperson-seeder',
                type: 'POST',
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    console.log('Salesperson seeder response:', response);
                    hideLoadingOverlay();
                    
                    if (response.success) {
                        // Reload the page to show the newly seeded data
                        location.reload();
                    } else {
                        // Display error message
                        alert('Error: ' + (response.message || 'Failed to run salesperson seeder'));
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error running salesperson seeder:', error);
                    hideLoadingOverlay();
                    alert('Error: Failed to run salesperson seeder. Please check the console for details.');
                }
            });
        }
        
        // Function to show loading overlay
        function showLoadingOverlay() {
            var overlay = document.getElementById('loadingOverlay');
            if (overlay) {
                overlay.classList.remove('hidden');
            } else {
                overlay = document.createElement('div');
                overlay.className = 'loading-overlay';
                overlay.id = 'loadingOverlay';
                
                var spinner = document.createElement('div');
                spinner.className = 'loading-spinner';
                
                overlay.appendChild(spinner);
                document.body.appendChild(overlay);
            }
        }
        
        // Function to hide loading overlay
        function hideLoadingOverlay() {
            var overlay = document.getElementById('loadingOverlay');
            if (overlay) {
                overlay.classList.add('hidden');
            }
        }
        
        // Dapatkan tombol dan modal
        var showBtn = document.getElementById('showSalespersonTableBtn');
        var salespersonModal = document.getElementById('salespersonTableWindow');
        
        // Tambahkan event listener ke tombol untuk menampilkan modal
        if (showBtn) {
            showBtn.onclick = function() {
                if (salespersonModal) {
                    salespersonModal.style.display = 'block';
                    salespersonModal.classList.remove('hidden');
                    
                    // Populasi tabel dengan data dari SalespersonSeeder jika perlu
                    populateSalespersonTable();
                    
                    // Urutkan berdasarkan kolom pertama secara default
                    sortTableDirectly(0);
                }
            };
        }
        
        // Setup row events initially
        setupTableRowEvents();
        
        // Override the sortTableDirectly function to setup events after sort
        const originalSortFunction = window.sortTableDirectly;
        if (typeof originalSortFunction === 'function') {
            window.sortTableDirectly = function(columnIndex) {
                originalSortFunction(columnIndex);
                setupTableRowEvents(); // Re-attach events after sorting
            };
        }
        
        // Tutup modal saat mengklik area di luar tabel
        if (salespersonModal) {
            salespersonModal.onclick = function(e) {
                if (e.target === salespersonModal) {
                    closeSalespersonModal();
                }
            };
        }
        
        // Event untuk menutup modal saat mengklik di luar
        window.onclick = function(event) {
            const editModal = document.getElementById('editSalespersonModal');
            if (event.target === editModal) {
                closeEditSalespersonModal();
            }
        };

        function populateSalespersonTable() {
            $.ajax({
                url: '/get-salesperson-list',
                type: 'GET',
                success: function(response) {
                    if (response.salespersons.length === 0) {
                        // If no data from server, use seed data
                        const seedData = [
                            // Sample seed data for testing
                            { id: 1, code: 'S101', name: 'ABENG', sales_team_id: 1, position: 'E - Executive', user_id: 'root', is_active: true },
                            { id: 2, code: 'S102', name: 'AGUNG', sales_team_id: 1, position: 'E - Executive', user_id: 'SLS', is_active: true },
                            { id: 3, code: 'S103', name: 'EKO', sales_team_id: 1, position: 'E - Executive', user_id: 'SLS', is_active: true },
                            { id: 4, code: 'S104', name: 'ELIAS', sales_team_id: 1, position: 'E - Executive', user_id: 'SLS', is_active: true },
                            { id: 5, code: 'S105', name: 'FEBBY', sales_team_id: 1, position: 'E - Executive', user_id: 'SLS', is_active: true }
                        ];
                        
                        $('#salespersonTable tbody').empty();
                        
                        seedData.forEach(function(salesperson) {
                            const statusBadge = salesperson.is_active ? 
                                '<span class="badge badge-success">Active</span>' : 
                                '<span class="badge badge-danger">Inactive</span>';
                                
                            $('#salespersonTable tbody').append(`
                                <tr data-id="${salesperson.id}">
                                    <td>${salesperson.id}</td>
                                    <td>${salesperson.code}</td>
                                    <td>${salesperson.name}</td>
                                    <td>${salesperson.sales_team_id}</td>
                                    <td>${salesperson.position}</td>
                                    <td>${salesperson.user_id}</td>
                                    <td>${statusBadge}</td>
                                    <td>
                                        <button class="btn btn-sm btn-info edit-btn" data-id="${salesperson.id}">Edit</button>
                                    </td>
                                </tr>
                            `);
                        });
                    } else {
                        // If data from server, use that data
                        $('#salespersonTable tbody').empty();
                        
                        response.salespersons.forEach(function(salesperson) {
                            const statusBadge = salesperson.is_active ? 
                                '<span class="badge badge-success">Active</span>' : 
                                '<span class="badge badge-danger">Inactive</span>';
                                
                            $('#salespersonTable tbody').append(`
                                <tr data-id="${salesperson.id}">
                                    <td>${salesperson.id}</td>
                                    <td>${salesperson.code}</td>
                                    <td>${salesperson.name}</td>
                                    <td>${salesperson.sales_team_id}</td>
                                    <td>${salesperson.position}</td>
                                    <td>${salesperson.user_id}</td>
                                    <td>${statusBadge}</td>
                                    <td>
                                        <button class="btn btn-sm btn-info edit-btn" data-id="${salesperson.id}">Edit</button>
                                    </td>
                                </tr>
                            `);
                        });
                    }

                    // Add event listeners to the edit buttons
                    $('.edit-btn').on('click', function() {
                        const id = $(this).data('id');
                        editSalesperson(id);
                    });
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching salesperson data:', error);
                    // If error, use seed data as fallback
                    const seedData = [
                        { id: 1, code: 'S101', name: 'ABENG', sales_team_id: 1, position: 'E - Executive', user_id: 'root', is_active: true },
                        { id: 2, code: 'S102', name: 'AGUNG', sales_team_id: 1, position: 'E - Executive', user_id: 'SLS', is_active: true },
                        { id: 3, code: 'S103', name: 'EKO', sales_team_id: 1, position: 'E - Executive', user_id: 'SLS', is_active: true },
                        { id: 4, code: 'S104', name: 'ELIAS', sales_team_id: 1, position: 'E - Executive', user_id: 'SLS', is_active: true },
                        { id: 5, code: 'S105', name: 'FEBBY', sales_team_id: 1, position: 'E - Executive', user_id: 'SLS', is_active: true }
                    ];
                    
                    $('#salespersonTable tbody').empty();
                    
                    seedData.forEach(function(salesperson) {
                        const statusBadge = salesperson.is_active ? 
                            '<span class="badge badge-success">Active</span>' : 
                            '<span class="badge badge-danger">Inactive</span>';
                            
                        $('#salespersonTable tbody').append(`
                            <tr data-id="${salesperson.id}">
                                <td>${salesperson.id}</td>
                                <td>${salesperson.code}</td>
                                <td>${salesperson.name}</td>
                                <td>${salesperson.sales_team_id}</td>
                                <td>${salesperson.position}</td>
                                <td>${salesperson.user_id}</td>
                                <td>${statusBadge}</td>
                                <td>
                                    <button class="btn btn-sm btn-info edit-btn" data-id="${salesperson.id}">Edit</button>
                                </td>
                            </tr>
                        `);
                    });

                    // Add event listeners to the edit buttons
                    $('.edit-btn').on('click', function() {
                        const id = $(this).data('id');
                        editSalesperson(id);
                    });
                }
            });
        }

        // Function to edit a salesperson
        function editSalesperson(id) {
            // Find the selected row in the table
            const selectedRow = $(`#salespersonTable tr[data-id="${id}"]`);
            
            if (selectedRow.length) {
                const cells = selectedRow.find('td');
                
                // Populate the form with the data from the selected row
                $('#edit_id').val(id);
                $('#edit_code').val(cells.eq(1).text());
                $('#edit_name').val(cells.eq(2).text());
                $('#edit_sales_team_id').val(cells.eq(3).text());
                $('#edit_position').val(cells.eq(4).text());
                $('#edit_user_id').val(cells.eq(5).text());
                $('#edit_is_active').val(cells.eq(6).text().trim() === 'Active' ? 1 : 0);
                
                // Show the edit modal
                $('#editSalespersonModal').modal('show');
            }
        }

        // Save the edited salesperson
        $('#saveSalesperson').on('click', function() {
            const id = $('#edit_id').val();
            const code = $('#edit_code').val();
            const name = $('#edit_name').val();
            const sales_team_id = $('#edit_sales_team_id').val();
            const position = $('#edit_position').val();
            const user_id = $('#edit_user_id').val();
            const is_active = $('#edit_is_active').val();
            
            $.ajax({
                url: `/update-salesperson/${id}`,
                type: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    code: code,
                    name: name,
                    sales_team_id: sales_team_id,
                    position: position,
                    user_id: user_id,
                    is_active: is_active
                },
                success: function(response) {
                    $('#editSalespersonModal').modal('hide');
                    
                    // Update the table row
                    const selectedRow = $(`#salespersonTable tr[data-id="${id}"]`);
                    const cells = selectedRow.find('td');
                    
                    cells.eq(1).text(code);
                    cells.eq(2).text(name);
                    cells.eq(3).text(sales_team_id);
                    cells.eq(4).text(position);
                    cells.eq(5).text(user_id);
                    
                    const statusBadge = is_active == 1 ? 
                        '<span class="badge badge-success">Active</span>' : 
                        '<span class="badge badge-danger">Inactive</span>';
                    cells.eq(6).html(statusBadge);
                    
                    alert('Salesperson updated successfully!');
                },
                error: function(xhr, status, error) {
                    console.error('Error updating salesperson:', error);
                    alert('Error updating salesperson. Please try again.');
                }
            });
        });

        function closeEditSalespersonModal() {
            const editModal = document.getElementById('editSalespersonModal');
            if (editModal) {
                editModal.classList.add('hidden');
            }
        }

        function saveSalespersonChanges() {
            // Get the values from the form
            const personCode = document.getElementById('edit_person_code').value;
            const personName = document.getElementById('edit_person_name').value;
            const personTeam = document.getElementById('edit_person_team').value;
            const personPosition = document.getElementById('edit_person_position').value;
            const personUserId = document.getElementById('edit_person_user_id').value;
            const personIsActive = document.getElementById('edit_person_is_active').value;

            // Here you would normally submit this data to your server
            console.log('Saving salesperson changes:', { 
                code: personCode, 
                name: personName, 
                team: personTeam, 
                position: personPosition,
                user_id: personUserId,
                is_active: personIsActive
            });

            // Mock update to table (in a real app, you'd update after server confirmation)
            const selectedRow = document.querySelector('#salespersonTable tr.selected');
            if (selectedRow) {
                const cells = selectedRow.getElementsByTagName('td');
                cells[1].textContent = personCode;
                cells[2].textContent = personName;
                cells[3].textContent = personTeam;
                cells[4].textContent = personPosition;
                cells[5].textContent = personUserId;
                cells[6].textContent = personIsActive == 1 ? 'Active' : 'Inactive';
                
                // Update data attributes
                selectedRow.setAttribute('data-person-code', personCode);
                selectedRow.setAttribute('data-person-name', personName);
                selectedRow.setAttribute('data-person-team', personTeam);
                selectedRow.setAttribute('data-person-position', personPosition);
                selectedRow.setAttribute('data-person-user-id', personUserId);
                selectedRow.setAttribute('data-person-is-active', personIsActive);
            }

            // Close the modal
            closeEditSalespersonModal();
        }

        // For the Bootstrap modal
        $(document).ready(function() {
            // Save changes when clicking the Save button in the Bootstrap modal
            $('#saveSalesperson').on('click', function() {
                const id = $('#edit_id').val();
                const code = $('#edit_code').val();
                const name = $('#edit_name').val();
                const salesTeamId = $('#edit_sales_team_id').val();
                const position = $('#edit_position').val();
                const userId = $('#edit_user_id').val();
                const isActive = $('#edit_is_active').val();
                
                // Here you would normally save the data via AJAX
                console.log('Saving salesperson with Bootstrap modal:', {
                    id, code, name, salesTeamId, position, userId, isActive
                });
                
                // Update the table row (mock update)
                updateTableRow(id, code, name, salesTeamId, position, userId, isActive);
                
                // Close the modal
                $('#editSalespersonModalBootstrap').modal('hide');
            });
            
            // Function to update the table row
            function updateTableRow(id, code, name, salesTeamId, position, userId, isActive) {
                const row = $(`#salespersonTable tr[data-id="${id}"]`);
                if (row.length) {
                    const cells = row.find('td');
                    cells.eq(1).text(code);
                    cells.eq(2).text(name);
                    cells.eq(3).text(salesTeamId);
                    cells.eq(4).text(position);
                    cells.eq(5).text(userId);
                    cells.eq(6).text(isActive == 1 ? 'Active' : 'Inactive');
                    
                    // Update data attributes
                    row.attr('data-person-code', code);
                    row.attr('data-person-name', name);
                    row.attr('data-person-team', salesTeamId);
                    row.attr('data-person-position', position);
                    row.attr('data-person-user-id', userId);
                    row.attr('data-person-is-active', isActive);
                }
            }
        });
    });
</script>
@endsection

@push('styles')
<style>
    /* Modal window styles */
    #salespersonTableWindow, #editSalespersonModal {
        background-color: rgba(0, 0, 0, 0.2);
        z-index: 9999;
    }
    
    #salespersonTableWindow:not(.hidden), #editSalespersonModal:not(.hidden) {
        display: block;
    }
    
    #salespersonTableWindow.hidden, #editSalespersonModal.hidden {
        display: none;
    }
    
    /* Loading overlay */
    .loading-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 10000;
    }
    
    .loading-spinner {
        border: 5px solid #f3f3f3;
        border-top: 5px solid #3498db;
        border-radius: 50%;
        width: 50px;
        height: 50px;
        animation: spin 2s linear infinite;
    }
    
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    
    /* Table styles */
    #salespersonDataTable {
        border-collapse: collapse;
        width: 100%;
    }
    
    #salespersonDataTable tbody {
        display: block;
        max-height: 250px;
        overflow-y: auto;
        overflow-x: hidden;
    }
    
    #salespersonDataTable thead {
        display: table;
        width: calc(100% - 17px); /* Adjust for scrollbar width */
        table-layout: fixed;
    }
    
    #salespersonDataTable tbody tr {
        display: table;
        width: 100%;
        table-layout: fixed;
    }
    
    /* Selected row */
    #salespersonDataTable tbody tr.selected {
        background-color: #0078d7;
        color: white;
    }
    
    #salespersonDataTable tbody tr:hover {
        background-color: #0078d7;
        color: white;
    }
    
    /* Classic Windows button styles */
    button {
        cursor: pointer;
    }
    
    button:active {
        transform: translateY(1px);
    }
    
    /* Sort button active style */
    .sort-btn.active-sort {
        background-color: #4299e1;
        color: white;
        font-weight: bold;
    }
    
    /* Classic Windows scrollbar style */
    #salespersonDataTable tbody::-webkit-scrollbar {
        width: 16px;
    }
    
    #salespersonDataTable tbody::-webkit-scrollbar-track {
        background: #f1f1f1;
    }
    
    #salespersonDataTable tbody::-webkit-scrollbar-thumb {
        background: #c1c1c1;
        border: 1px solid #a1a1a1;
    }
    
    #salespersonDataTable tbody::-webkit-scrollbar-thumb:hover {
        background: #a1a1a1;
    }
</style>
@endpush
