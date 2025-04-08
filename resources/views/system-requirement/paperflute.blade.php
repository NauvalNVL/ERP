@extends('layouts.app')

@section('title', 'Define Color')

@section('styles')
<style>
    /* Variabel CSS untuk Tema Minimalis Modern */
    :root {
        --primary: #4361ee;
        --primary-light: #4895ef;
        --secondary: #3f37c9;
        --accent: #f72585;
        --success: #4cc9f0;
        --warning: #f8961e;
        --danger: #e63946;
        --light: #f8f9fa;
        --dark: #212529;
        --gray: #6c757d;
        --light-gray: #e9ecef;
        --white: #ffffff;
        --black: #000000;
        --shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        --radius: 8px;
        --transition: all 0.25s ease;
    }

    /* Layout & Base */
    body {
        font-family: 'Inter', 'Segoe UI', 'Helvetica Neue', sans-serif;
        background-color: #f5f7fb;
        color: var(--dark);
    }

    .container-fluid {
        padding: 20px;
        max-width: 1600px;
        margin: 0 auto;
    }

    /* Header Minimalis */
    .header-minimal {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 30px;
    }

    .page-title {
        font-size: 24px;
        font-weight: 600;
        color: var(--dark);
        margin: 0;
    }

    /* Toolbar Minimalis */
    .toolbar {
        display: flex;
        background-color: var(--white);
        border-radius: var(--radius);
        padding: 10px;
        margin-bottom: 20px;
        box-shadow: var(--shadow);
        align-items: center;
        flex-wrap: wrap;
        gap: 6px;
    }

    .toolbar-divider {
        width: 1px;
        height: 24px;
        background-color: var(--light-gray);
        margin: 0 5px;
    }

    .btn-toolbar {
        width: 36px;
        height: 36px;
        border-radius: 6px;
        background-color: transparent;
        border: none;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--gray);
        transition: var(--transition);
    }

    .btn-toolbar:hover {
        background-color: var(--light);
        color: var(--primary);
    }

    .btn-toolbar.active {
        background-color: var(--primary-light);
        color: var(--white);
    }

    /* Card Utama Minimalis */
    .card-minimal {
        background-color: var(--white);
        border-radius: var(--radius);
        box-shadow: var(--shadow);
        margin-bottom: 20px;
        overflow: hidden;
    }

    .card-header {
        padding: 15px 20px;
        background-color: var(--white);
        border-bottom: 1px solid var(--light-gray);
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .card-header-title {
        font-size: 16px;
        font-weight: 600;
        color: var(--dark);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .card-body {
        padding: 20px;
    }

    .card-footer {
        padding: 15px 20px;
        background-color: var(--light);
        border-top: 1px solid var(--light-gray);
        display: flex;
        justify-content: flex-end;
        gap: 10px;
    }

    /* Tabel Minimalis */
    .table-minimal {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }

    .table-minimal th {
        background-color: var(--light);
        font-weight: 600;
        color: var(--dark);
        text-align: left;
        padding: 12px 15px;
        font-size: 13px;
        border-bottom: 1px solid var(--light-gray);
    }

    .table-minimal td {
        padding: 12px 15px;
        border-bottom: 1px solid var(--light-gray);
        color: var(--gray);
        font-size: 13px;
        vertical-align: middle;
    }

    .table-minimal tr:last-child td {
        border-bottom: none;
    }

    .table-minimal tr:hover td {
        background-color: rgba(67, 97, 238, 0.05);
    }

    .table-minimal .empty-state {
        text-align: center;
        padding: 40px 20px;
        color: var(--gray);
    }

    /* Color Swatch pada tabel */
    .color-swatch {
        width: 24px;
        height: 24px;
        border-radius: 4px;
        display: inline-block;
        margin-right: 8px;
        vertical-align: middle;
        border: 1px solid var(--light-gray);
    }

    /* Action buttons pada tabel */
    .table-actions {
        display: flex;
        gap: 5px;
    }

    .btn-table-action {
        width: 28px;
        height: 28px;
        border-radius: 4px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: none;
        cursor: pointer;
        transition: var(--transition);
        font-size: 12px;
    }

    .btn-info {
        background-color: rgba(72, 149, 239, 0.1);
        color: var(--primary);
    }

    .btn-info:hover {
        background-color: var(--primary);
        color: var(--white);
    }

    .btn-edit {
        background-color: rgba(76, 201, 240, 0.1);
        color: var(--success);
    }

    .btn-edit:hover {
        background-color: var(--success);
        color: var(--white);
    }

    .btn-delete {
        background-color: rgba(230, 57, 70, 0.1);
        color: var(--danger);
    }

    .btn-delete:hover {
        background-color: var(--danger);
        color: var(--white);
    }

    /* Form Elements minimalis */
    .form-group {
        margin-bottom: 20px;
    }

    .form-label {
        display: block;
        margin-bottom: 6px;
        font-size: 14px;
        font-weight: 500;
        color: var(--dark);
    }

    .form-control {
        width: 100%;
        padding: 10px 12px;
        font-size: 14px;
        border: 1px solid var(--light-gray);
        border-radius: var(--radius);
        transition: var(--transition);
        background-color: var(--white);
    }

    .form-control:focus {
        border-color: var(--primary);
        outline: none;
        box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.15);
    }

    .form-row {
        display: flex;
        flex-wrap: wrap;
        margin: -10px;
    }

    .form-col {
        flex: 1;
        padding: 10px;
        min-width: 250px;
    }

    /* Buttons */
    .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        padding: 8px 16px;
        font-size: 14px;
        font-weight: 500;
        border-radius: var(--radius);
        border: none;
        cursor: pointer;
        transition: var(--transition);
    }

    .btn-primary {
        background-color: var(--primary);
        color: var(--white);
    }

    .btn-primary:hover {
        background-color: var(--secondary);
        box-shadow: 0 4px 10px rgba(67, 97, 238, 0.3);
    }

    .btn-secondary {
        background-color: var(--light);
        color: var(--gray);
    }

    .btn-secondary:hover {
        background-color: var(--light-gray);
    }

    .btn-success {
        background-color: var(--success);
        color: var(--white);
    }

    .btn-success:hover {
        background-color: #3ba3c6;
        box-shadow: 0 4px 10px rgba(76, 201, 240, 0.3);
    }

    .btn-danger {
        background-color: var(--danger);
        color: var(--white);
    }

    .btn-danger:hover {
        background-color: #c1121f;
        box-shadow: 0 4px 10px rgba(230, 57, 70, 0.3);
    }

    /* Modal Minimalis */
    .modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: rgba(0, 0, 0, 0.4);
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 1000;
        animation: fadeIn 0.2s ease-out;
    }

    .modal {
        background-color: var(--white);
        border-radius: var(--radius);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        width: 100%;
        max-width: 600px;
        max-height: 90vh;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        animation: slideUp 0.3s ease-out;
    }

    .modal-sm {
        max-width: 400px;
    }

    .modal-lg {
        max-width: 800px;
    }

    .modal-header {
        padding: 15px 20px;
        border-bottom: 1px solid var(--light-gray);
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .modal-title {
        font-size: 18px;
        font-weight: 600;
        color: var(--dark);
        margin: 0;
    }

    .modal-close {
        background: transparent;
        border: none;
        font-size: 20px;
        cursor: pointer;
        color: var(--gray);
        width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        transition: var(--transition);
    }

    .modal-close:hover {
        background-color: var(--light);
        color: var(--danger);
    }

    .modal-body {
        padding: 20px;
        overflow-y: auto;
    }

    .modal-search {
        margin-bottom: 20px;
        position: relative;
    }

    .modal-search-input {
        width: 100%;
        padding: 10px 15px 10px 40px;
        border: 1px solid var(--light-gray);
        border-radius: var(--radius);
        transition: var(--transition);
    }

    .modal-search-input:focus {
        border-color: var(--primary);
        outline: none;
        box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.15);
    }

    .modal-search-icon {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--gray);
    }

    .modal-search-clear {
        position: absolute;
        right: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--gray);
        background: transparent;
        border: none;
        cursor: pointer;
        display: none;
    }

    .modal-search-clear.visible {
        display: block;
    }

    .modal-footer {
        padding: 15px 20px;
        border-top: 1px solid var(--light-gray);
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        background-color: var(--light);
    }

    /* Confirmation Modal */
    .modal-confirm {
        text-align: center;
        padding: 20px;
    }

    .modal-confirm-icon {
        font-size: 48px;
        color: var(--danger);
        margin-bottom: 20px;
    }

    .modal-confirm-title {
        font-size: 18px;
        font-weight: 600;
        margin-bottom: 10px;
    }

    .modal-confirm-text {
        color: var(--gray);
        margin-bottom: 20px;
    }

    /* Detail View dalam Modal */
    .detail-view {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }

    .detail-item {
        display: flex;
        border-bottom: 1px solid var(--light-gray);
        padding-bottom: 10px;
    }

    .detail-label {
        width: 40%;
        font-weight: 500;
        color: var(--dark);
    }

    .detail-value {
        width: 60%;
        color: var(--gray);
    }

    .color-preview-lg {
        width: 100%;
        height: 100px;
        border-radius: var(--radius);
        margin-bottom: 15px;
        border: 1px solid var(--light-gray);
    }

    /* Animasi */
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    @keyframes slideUp {
        from { transform: translateY(20px); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }

    @keyframes pulse {
        0% { box-shadow: 0 0 0 0 rgba(67, 97, 238, 0.4); }
        70% { box-shadow: 0 0 0 10px rgba(67, 97, 238, 0); }
        100% { box-shadow: 0 0 0 0 rgba(67, 97, 238, 0); }
    }

    .fade-in {
        animation: fadeIn 0.3s ease-out;
    }

    .slide-up {
        animation: slideUp 0.3s ease-out;
    }

    .pulse {
        animation: pulse 1.5s infinite;
    }

    /* Responsif */
    @media (max-width: 768px) {
        .toolbar {
            justify-content: space-between;
        }
        
        .form-col {
            min-width: 100%;
        }
        
        .modal {
            width: 90%;
        }
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Header Minimalis -->
    <div class="header-minimal">
        <h1 class="page-title">Define Color</h1>
    </div>
    
    <!-- Toolbar Minimalis -->
    <div class="toolbar">
        <button class="btn-toolbar" id="btnNew" title="Tambah Baru">
            <i class="fas fa-plus"></i>
        </button>
        <button class="btn-toolbar" id="btnEdit" title="Edit">
            <i class="fas fa-edit"></i>
        </button>
        <button class="btn-toolbar" id="btnDelete" title="Hapus">
            <i class="fas fa-trash"></i>
        </button>
        
        <span class="toolbar-divider"></span>
        
        <button class="btn-toolbar" id="btnRefresh" title="Refresh">
            <i class="fas fa-sync-alt"></i>
        </button>
        <button class="btn-toolbar" id="btnSearch" title="Pencarian">
            <i class="fas fa-search"></i>
        </button>
        
        <span class="toolbar-divider"></span>
        
        <button class="btn-toolbar" id="btnPrint" title="Cetak">
            <i class="fas fa-print"></i>
        </button>
        <button class="btn-toolbar" id="btnExit" title="Keluar">
            <i class="fas fa-sign-out-alt"></i>
        </button>
    </div>
    
    <!-- Card Utama Minimalis -->
    <div class="card-minimal">
        <div class="card-header">
            <h2 class="card-header-title">
                <i class="fas fa-palette"></i> Informasi Warna
            </h2>
            <button class="btn-toolbar" id="btnToggleView" title="Toggle Detail/List View">
                <i class="fas fa-th-list"></i>
            </button>
        </div>
        
        <div class="card-body">
            <!-- Detail View -->
            <div id="detailView">
                <div class="form-row">
                    <div class="form-col">
                        <div class="form-group">
                            <label class="form-label">ID Warna</label>
                            <input type="text" class="form-control" id="inputColorId" readonly>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Nama Warna</label>
                            <input type="text" class="form-control" id="inputColorName" readonly>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Asal</label>
                            <input type="text" class="form-control" id="inputOrigin" readonly>
                        </div>
                    </div>
                    
                    <div class="form-col">
                        <div class="form-group">
                            <label class="form-label">ID Grup Warna</label>
                            <input type="text" class="form-control" id="inputColorGroupId" readonly>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Tipe CG</label>
                            <input type="text" class="form-control" id="inputCgType" readonly>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Status</label>
                            <input type="text" class="form-control" id="inputStatus" readonly>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- List View / Table -->
            <div id="listView" style="display:none;">
                <table class="table-minimal">
                    <thead>
                        <tr>
                            <th width="15%">ID Warna</th>
                            <th width="25%">Nama Warna</th>
                            <th width="15%">Asal</th>
                            <th width="15%">ID Grup</th>
                            <th width="15%">Tipe CG</th>
                            <th width="15%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="colorTableBody">
                        @if(isset($colors) && count($colors) > 0)
                            @foreach($colors as $color)
                                <tr data-id="{{ $color->color_id }}">
                                    <td>{{ $color->color_id }}</td>
                                    <td>
                                        <span class="color-swatch" style="background-color: #{{ substr($color->color_id, 0, 6) }};"></span>
                                        {{ $color->color_name }}
                                    </td>
                                    <td>{{ $color->origin }}</td>
                                    <td>{{ $color->color_group_id }}</td>
                                    <td>{{ $color->cg_type ?? 'N/A' }}</td>
                                    <td>
                                        <div class="table-actions">
                                            <button class="btn-table-action btn-info" onclick="showDetailModal('{{ $color->color_id }}')">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn-table-action btn-edit" onclick="showEditModal('{{ $color->color_id }}')">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn-table-action btn-delete" onclick="showDeleteModal('{{ $color->color_id }}')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="6" class="empty-state">
                                    <i class="fas fa-palette fa-3x mb-3"></i>
                                    <p>Tidak ada data warna ditemukan. Klik tombol tambah baru untuk menambahkan warna.</p>
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Pencarian -->
<div class="modal-overlay" id="searchModal">
    <div class="modal">
        <div class="modal-header">
            <h3 class="modal-title">Cari Warna</h3>
            <button class="modal-close" id="closeSearchModal">&times;</button>
        </div>
        <div class="modal-body">
            <div class="modal-search">
                <i class="fas fa-search modal-search-icon"></i>
                <input type="text" class="modal-search-input" id="searchInput" placeholder="Cari berdasarkan ID atau nama warna...">
                <button class="modal-search-clear" id="clearSearch">&times;</button>
            </div>
            
            <table class="table-minimal">
                <thead>
                    <tr>
                        <th width="20%">ID Warna</th>
                        <th width="40%">Nama Warna</th>
                        <th width="20%">Asal</th>
                        <th width="20%">ID Grup</th>
                    </tr>
                </thead>
                <tbody id="searchResultsBody">
                    <!-- Hasil pencarian akan ditampilkan di sini -->
                </tbody>
            </table>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" id="cancelSearch">Batal</button>
        </div>
    </div>
</div>

<!-- Modal Detail Warna -->
<div class="modal-overlay" id="detailModal">
    <div class="modal">
        <div class="modal-header">
            <h3 class="modal-title">Detail Warna</h3>
            <button class="modal-close" id="closeDetailModal">&times;</button>
        </div>
        <div class="modal-body">
            <div class="color-preview-lg" id="colorPreview"></div>
            
            <div class="detail-view">
                <div class="detail-item">
                    <div class="detail-label">ID Warna</div>
                    <div class="detail-value" id="detailColorId"></div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Nama Warna</div>
                    <div class="detail-value" id="detailColorName"></div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Asal</div>
                    <div class="detail-value" id="detailOrigin"></div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">ID Grup Warna</div>
                    <div class="detail-value" id="detailColorGroupId"></div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Tipe CG</div>
                    <div class="detail-value" id="detailCgType"></div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Status</div>
                    <div class="detail-value" id="detailStatus"></div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-primary" id="editFromDetail">Edit</button>
            <button class="btn btn-secondary" id="closeDetailBtn">Tutup</button>
        </div>
    </div>
</div>

<!-- Modal Tambah/Edit Warna -->
<div class="modal-overlay" id="editModal">
    <div class="modal">
        <div class="modal-header">
            <h3 class="modal-title" id="editModalTitle">Tambah Warna Baru</h3>
            <button class="modal-close" id="closeEditModal">&times;</button>
        </div>
        <div class="modal-body">
            <form id="colorForm">
                <div class="form-row">
                    <div class="form-col">
                        <div class="form-group">
                            <label class="form-label">ID Warna <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="editColorId" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Nama Warna <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="editColorName" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Asal</label>
                            <input type="text" class="form-control" id="editOrigin">
                        </div>
                    </div>
                    
                    <div class="form-col">
                        <div class="form-group">
                            <label class="form-label">ID Grup Warna</label>
                            <input type="text" class="form-control" id="editColorGroupId">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Tipe CG</label>
                            <input type="text" class="form-control" id="editCgType">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Status</label>
                            <select class="form-control" id="editStatus">
                                <option value="active">Aktif</option>
                                <option value="inactive">Tidak Aktif</option>
                            </select>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button class="btn btn-primary" id="saveColor">Simpan</button>
            <button class="btn btn-secondary" id="cancelEdit">Batal</button>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Hapus -->
<div class="modal-overlay" id="deleteModal">
    <div class="modal modal-sm">
        <div class="modal-header">
            <h3 class="modal-title">Konfirmasi Hapus</h3>
            <button class="modal-close" id="closeDeleteModal">&times;</button>
        </div>
        <div class="modal-body">
            <div class="modal-confirm">
                <div class="modal-confirm-icon">
                    <i class="fas fa-trash-alt"></i>
                </div>
                <div class="modal-confirm-title">Hapus Warna?</div>
                <div class="modal-confirm-text">
                    Anda yakin ingin menghapus warna "<span id="deleteColorName"></span>"? 
                    Tindakan ini tidak dapat dibatalkan.
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-danger" id="confirmDelete">Hapus</button>
            <button class="btn btn-secondary" id="cancelDelete">Batal</button>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Variabel untuk menyimpan data warna yang dipilih
    let selectedColor = null;
    let colors = @json(isset($colors) ? $colors : []);
    
    $(document).ready(function() {
        // Toggle antara tampilan detail dan list
        $('#btnToggleView').click(function() {
            $('#detailView').toggle();
            $('#listView').toggle();
            $(this).find('i').toggleClass('fa-th-list fa-id-card');
        });
        
        // ====== TOMBOL TOOLBAR ======
        
        // Tombol tambah baru
        $('#btnNew').click(function() {
            showEditModal();
        });
        
        // Tombol edit
        $('#btnEdit').click(function() {
            if (selectedColor) {
                showEditModal(selectedColor.color_id);
            } else {
                alert('Silakan pilih warna terlebih dahulu');
            }
        });
        
        // Tombol hapus
        $('#btnDelete').click(function() {
            if (selectedColor) {
                showDeleteModal(selectedColor.color_id);
            } else {
                alert('Silakan pilih warna terlebih dahulu');
            }
        });
        
        // Tombol refresh
        $('#btnRefresh').click(function() {
            location.reload();
        });
        
        // Tombol search
        $('#btnSearch').click(function() {
            showSearchModal();
        });
        
        // Tombol print
        $('#btnPrint').click(function() {
            printColorData();
        });
        
        // Tombol exit
        $('#btnExit').click(function() {
            window.location.href = '{{ route("dashboard") }}';
        });
        
        // ====== MODAL UTAMA ======
        
        // Tutup modal pencarian
        $('#closeSearchModal, #cancelSearch').click(function() {
            $('#searchModal').hide();
        });
        
        // Pencarian dalam modal
        $('#searchInput').on('input', function() {
            const searchTerm = $(this).val().toLowerCase();
            
            if (searchTerm.length > 0) {
                $('#clearSearch').addClass('visible');
                
                // Filter data berdasarkan kata kunci
                const filteredColors = colors.filter(color => 
                    color.color_id.toLowerCase().includes(searchTerm) || 
                    color.color_name.toLowerCase().includes(searchTerm)
                );
                
                renderSearchResults(filteredColors);
            } else {
                $('#clearSearch').removeClass('visible');
                $('#searchResultsBody').html('');
            }
        });
        
        // Reset pencarian
        $('#clearSearch').click(function() {
            $('#searchInput').val('');
            $('#searchResultsBody').html('');
            $(this).removeClass('visible');
        });
        
        // ====== MODAL DETAIL ======
        
        // Tutup modal detail
        $('#closeDetailModal, #closeDetailBtn').click(function() {
            $('#detailModal').hide();
        });
        
        // Edit dari modal detail
        $('#editFromDetail').click(function() {
            $('#detailModal').hide();
            showEditModal(selectedColor.color_id);
        });
        
        // ====== MODAL EDIT ======
        
        // Tutup modal edit
        $('#closeEditModal, #cancelEdit').click(function() {
            $('#editModal').hide();
        });
        
        // Simpan perubahan
        $('#saveColor').click(function() {
            saveColorData();
        });
        
        // ====== MODAL HAPUS ======
        
        // Tutup modal hapus
        $('#closeDeleteModal, #cancelDelete').click(function() {
            $('#deleteModal').hide();
        });
        
        // Konfirmasi hapus
        $('#confirmDelete').click(function() {
            deleteColorData();
        });
        
        // Jika ada data dari pencarian sebelumnya, tampilkan
        @if(isset($colors) && count($colors) > 0 && request('search'))
            const foundColor = @json($colors->first());
            setSelectedColor(foundColor);
        @endif
    });
    
    // ========== FUNCTIONS ==========
    
    // Menampilkan modal pencarian
    function showSearchModal() {
        $('#searchInput').val('');
        $('#searchResultsBody').html('');
        $('#clearSearch').removeClass('visible');
        $('#searchModal').show();
    }
    
    // Render hasil pencarian
    function renderSearchResults(results) {
        let html = '';
        
        if (results.length > 0) {
            results.forEach(color => {
                html += `
                <tr data-id="${color.color_id}" class="search-result-row">
                    <td>${color.color_id}</td>
                    <td>
                        <span class="color-swatch" style="background-color: #${color.color_id.substring(0, 6)};"></span>
                        ${color.color_name}
                    </td>
                    <td>${color.origin || '-'}</td>
                    <td>${color.color_group_id || '-'}</td>
                </tr>`;
            });
        } else {
            html = `<tr><td colspan="4" class="empty-state">Tidak ada hasil ditemukan</td></tr>`;
        }
        
        $('#searchResultsBody').html(html);
        
        // Tambahkan event listener untuk baris hasil pencarian
        $('.search-result-row').click(function() {
            const colorId = $(this).data('id');
            const color = colors.find(c => c.color_id === colorId);
            
            if (color) {
                setSelectedColor(color);
                $('#searchModal').hide();
            }
        });
    }
    
    // Set warna yang dipilih
    function setSelectedColor(color) {
        selectedColor = color;
        
        // Isi form detail
        $('#inputColorId').val(color.color_id);
        $('#inputColorName').val(color.color_name);
        $('#inputOrigin').val(color.origin || '');
        $('#inputColorGroupId').val(color.color_group_id || '');
        $('#inputCgType').val(color.cg_type || '');
        $('#inputStatus').val(color.status || 'active');
        
        // Highlight baris yang dipilih pada tabel
        $('#colorTableBody tr').removeClass('active');
        $(`#colorTableBody tr[data-id="${color.color_id}"]`).addClass('active');
    }
    
    // Tampilkan modal detail
    function showDetailModal(colorId) {
        const color = colors.find(c => c.color_id === colorId);
        
        if (color) {
            selectedColor = color;
            
            $('#colorPreview').css('background-color', `#${color.color_id.substring(0, 6)}`);
            $('#detailColorId').text(color.color_id);
            $('#detailColorName').text(color.color_name);
            $('#detailOrigin').text(color.origin || '-');
            $('#detailColorGroupId').text(color.color_group_id || '-');
            $('#detailCgType').text(color.cg_type || '-');
            $('#detailStatus').text(color.status || 'active');
            
            $('#detailModal').show();
        }
    }
    
    // Tampilkan modal edit
    function showEditModal(colorId = null) {
        if (colorId) {
            // Mode edit
            const color = colors.find(c => c.color_id === colorId);
            
            if (color) {
                $('#editModalTitle').text('Edit Warna');
                $('#editColorId').val(color.color_id);
                $('#editColorId').prop('readonly', true);
                $('#editColorName').val(color.color_name);
                $('#editOrigin').val(color.origin