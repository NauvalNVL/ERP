@extends('layouts.app')

@section('title', 'Define Color')

@section('styles')
<style>
    /* Modern Enterprise UI styling dengan peningkatan */
    :root {
        --primary-color: #4a6cf7;
        --primary-dark: #3955d8;
        --secondary-color: #ff9f43;
        --accent-color: #ee5253;
        --bg-light: #f8f9fa;
        --bg-dark: #2d3436;
        --text-dark: #2c3e50;
        --text-light: #f1f2f6;
        --border-color: #e2e8f0;
        --success-color: #10ac84;
        --card-shadow: 0 10px 25px rgba(0, 0, 0, 0.07);
        --hover-transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
    }
    
    body {
        font-family: 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
        color: var(--text-dark);
        background: linear-gradient(135deg, #f5f7fa 0%, #eef2f7 100%);
        min-height: 100vh;
    }
    
    /* Peningkatan container dan wrapper */
    .container-fluid {
        padding: 25px;
        max-width: 1600px;
        margin: 0 auto;
    }
    
    .content-wrapper {
        background-color: #ffffff;
        padding: 25px;
        border-radius: 12px;
        box-shadow: var(--card-shadow);
        transition: var(--hover-transition);
    }
    
    /* Title dengan efek gradient */
    .page-title {
        font-size: 30px;
        font-weight: 700;
        color: var(--text-dark);
        margin-bottom: 25px;
        border-bottom: 2px solid var(--primary-color);
        padding-bottom: 12px;
        position: relative;
        background: linear-gradient(90deg, var(--text-dark), #576574);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }
    
    .page-title:after {
        content: '';
        position: absolute;
        bottom: -2px;
        left: 0;
        width: 100px;
        height: 3px;
        background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
        border-radius: 3px;
    }
    
    /* Modern Glassmorphism Navigation buttons */
    .function-nav {
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        padding: 12px 18px;
        margin-bottom: 25px;
        display: flex;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        flex-wrap: wrap;
        gap: 10px;
        border: 1px solid rgba(255, 255, 255, 0.3);
    }
    
    .function-button {
        background: rgba(255, 255, 255, 0.7);
        border: 1px solid rgba(255, 255, 255, 0.4);
        padding: 0;
        margin: 0;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        width: 48px;
        height: 48px;
        transition: var(--hover-transition);
        position: relative;
        overflow: hidden;
    }
    
    .function-button::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.1), rgba(255, 255, 255, 0));
        z-index: 1;
    }
    
    .function-button:hover {
        transform: translateY(-3px);
        box-shadow: 0 7px 14px rgba(50, 50, 93, 0.1), 0 3px 6px rgba(0, 0, 0, 0.08);
        background-color: rgba(74, 108, 247, 0.1);
        border-color: var(--primary-color);
    }
    
    .function-button:active {
        transform: translateY(1px);
    }
    
    .function-button i {
        font-size: 18px;
        color: var(--text-dark);
        transition: var(--hover-transition);
        position: relative;
        z-index: 2;
    }
    
    .function-button:hover i {
        color: var(--primary-color);
    }
    
    /* Improved Content sections with subtle shadows */
    .content-section {
        background: #ffffff;
        border: none;
        border-radius: 12px;
        margin-bottom: 30px;
        box-shadow: var(--card-shadow);
        overflow: hidden;
        transition: var(--hover-transition);
    }
    
    .content-section:hover {
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
    }
    
    .section-header {
        background: linear-gradient(120deg, var(--primary-color), var(--primary-dark));
        padding: 18px 22px;
        border-bottom: none;
        font-weight: 600;
        color: var(--text-light);
        font-size: 17px;
        display: flex;
        align-items: center;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .section-header i {
        margin-right: 12px;
        font-size: 20px;
    }
    
    .section-body {
        padding: 25px;
    }
    
    /* Enhanced Search panel with neumorphic style */
    .search-panel {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 25px;
        background-color: #f8f9fc;
        padding: 20px;
        border-radius: 12px;
        box-shadow: inset 3px 3px 5px rgba(0, 0, 0, 0.05),
                    inset -3px -3px 5px rgba(255, 255, 255, 0.5);
    }
    
    .search-form-group {
        display: flex;
        align-items: center;
        position: relative;
        flex-grow: 1;
        max-width: 500px;
        background: white;
        border-radius: 10px;
        padding: 5px 15px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.04);
    }
    
    .search-label {
        font-weight: 600;
        margin-right: 12px;
        min-width: 70px;
        color: var(--text-dark);
    }
    
    .search-input {
        border: 1px solid rgba(226, 232, 240, 0.8);
        border-radius: 8px;
        padding: 12px 15px;
        flex-grow: 1;
        transition: var(--hover-transition);
        font-size: 14px;
        background-color: rgba(255, 255, 255, 0.9);
    }
    
    .search-input:focus {
        border-color: var(--primary-color);
        outline: 0;
        box-shadow: 0 0 0 3px rgba(74, 108, 247, 0.25);
    }
    
    .search-button {
        background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
        color: white;
        border: none;
        border-radius: 8px;
        padding: 12px 20px;
        margin-left: 12px;
        cursor: pointer;
        transition: var(--hover-transition);
        font-weight: 600;
        display: flex;
        align-items: center;
        box-shadow: 0 4px 10px rgba(74, 108, 247, 0.2);
    }
    
    /* Data Entry dengan style floating label */
    .data-entry {
        background-color: #ffffff;
        border: 1px solid var(--border-color);
        border-radius: 12px;
        padding: 25px;
        margin-bottom: 25px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.03);
    }
    
    .entry-group {
        margin: 0 10px 15px;
        flex: 1 0 250px;
        position: relative;
    }
    
    .entry-label {
        display: block;
        font-weight: 600;
        margin-bottom: 8px;
        color: var(--text-dark);
        font-size: 14px;
        letter-spacing: 0.3px;
    }
    
    .entry-input {
        width: 100%;
        padding: 12px 15px;
        border: 1px solid var(--border-color);
        border-radius: 8px;
        transition: var(--hover-transition);
        font-size: 14px;
        background-color: #f9fafc;
        box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.05);
    }
    
    .entry-input:focus {
        border-color: var(--primary-color);
        outline: 0;
        box-shadow: 0 0 0 3px rgba(74, 108, 247, 0.15);
        background-color: #ffffff;
        transform: translateY(-2px);
    }
    
    /* Data table dengan animasi hover */
    .data-table-container {
        overflow-x: auto;
        border-radius: 12px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
        background: white;
    }
    
    .data-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        border-radius: 12px;
        overflow: hidden;
    }
    
    .data-table th {
        background: linear-gradient(120deg, var(--primary-color), var(--primary-dark));
        color: var(--text-light);
        border: none;
        padding: 16px;
        text-align: left;
        font-weight: 600;
        font-size: 14px;
        white-space: nowrap;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .data-table td {
        border-bottom: 1px solid var(--border-color);
        padding: 16px;
        vertical-align: middle;
        font-size: 14px;
        background-color: #ffffff;
        transition: var(--hover-transition);
    }
    
    .data-table tr:hover td {
        background-color: rgba(74, 108, 247, 0.03);
        transform: translateY(-1px);
    }
    
    /* Enhanced Color visualization */
    .color-preview {
        display: flex;
        align-items: center;
    }
    
    .color-swatch {
        width: 28px;
        height: 28px;
        border-radius: 6px;
        display: inline-block;
        margin-right: 12px;
        border: 1px solid var(--border-color);
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        transition: var(--hover-transition);
    }
    
    .color-preview:hover .color-swatch {
        transform: scale(1.2);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.15);
    }
    
    /* Improved Button styling */
    .action-buttons {
        display: flex;
        gap: 10px;
    }
    
    .action-button {
        border-radius: 8px;
        border: none;
        padding: 10px 14px;
        cursor: pointer;
        transition: var(--hover-transition);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
    
    .action-button:hover {
        transform: translateY(-3px);
        box-shadow: 0 7px 14px rgba(0, 0, 0, 0.15);
    }
    
    .edit-button {
        background: linear-gradient(135deg, var(--secondary-color), #f39c12);
    }
    
    .delete-button {
        background: linear-gradient(135deg, var(--accent-color), #d63031);
    }
    
    /* Empty state animation */
    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #95a5a6;
        background-color: #fcfcfc;
        border-radius: 12px;
        animation: pulse 2s infinite ease-in-out;
    }
    
    @keyframes pulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.02); }
        100% { transform: scale(1); }
    }
    
    .empty-state i {
        margin-bottom: 20px;
        color: #bdc3c7;
        font-size: 4rem;
        animation: fadeColor 3s infinite alternate;
    }
    
    @keyframes fadeColor {
        0% { color: #bdc3c7; }
        100% { color: var(--primary-color); }
    }
    
    /* Animations */
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    @keyframes slideRight {
        from { opacity: 0; transform: translateX(-20px); }
        to { opacity: 1; transform: translateX(0); }
    }
    
    .section-body {
        animation: fadeIn 0.5s ease-out;
    }
    
    .data-table tr {
        animation: slideRight 0.3s ease-out forwards;
        opacity: 0;
    }
    
    .data-table tr:nth-child(1) { animation-delay: 0.1s; }
    .data-table tr:nth-child(2) { animation-delay: 0.2s; }
    .data-table tr:nth-child(3) { animation-delay: 0.3s; }
    .data-table tr:nth-child(4) { animation-delay: 0.4s; }
    .data-table tr:nth-child(5) { animation-delay: 0.5s; }
}
</style>
@endsection

@section('content')
<div class="container-fluid">
    <h1 class="page-title">Define Color</h1>
    
    <!-- Function Navigation Buttons -->
    <div class="function-nav">
        <button class="function-button" id="exitBtn" title="Exit">
            <i class="fas fa-times"></i>
        </button>
        <button class="function-button" title="Previous Record">
            <i class="fas fa-arrow-left"></i>
        </button>
        <button class="function-button" title="Next Record">
            <i class="fas fa-arrow-right"></i>
        </button>
        <button class="function-button" title="Save">
            <i class="fas fa-save"></i>
        </button>
        <button class="function-button" title="Print">
            <i class="fas fa-print"></i>
        </button>
        <button class="function-button" title="Help">
            <i class="fas fa-question-circle"></i>
        </button>
    </div>
    
    <!-- Main Content -->
    <div class="content-section">
        <div class="section-header">
            <i class="fas fa-palette"></i> Color Information
        </div>
        <div class="section-body">
            <!-- Search Panel -->
            <div class="search-panel">
                <div class="search-form">
                    <div class="search-form-group">
                        <label class="search-label">Color#:</label>
                        <input type="text" class="search-input" id="colorId" value="{{ request('search') }}" placeholder="Enter color ID...">
                        <button class="search-button" id="searchBtn"><i class="fas fa-search"></i> Search</button>
                    </div>
                </div>
                
                <div class="search-actions">
                    <div class="record-selector">
                        <span class="record-label">Record:</span>
                        <button class="record-select-button" id="selectBtn"><i class="fas fa-plus"></i> New Color</button>
                    </div>
                </div>
            </div>
            
            <!-- Data Entry Form -->
            <div class="data-entry">
                <div class="entry-row">
                    <div class="entry-group">
                        <label class="entry-label">Color ID:</label>
                        <input type="text" class="entry-input" id="colorIdEntry" placeholder="Enter color ID...">
                    </div>
                    <div class="entry-group">
                        <label class="entry-label">Color Name:</label>
                        <input type="text" class="entry-input" id="colorNameEntry" placeholder="Enter color name...">
                    </div>
                    <div class="entry-group">
                        <label class="entry-label">Color Origin:</label>
                        <input type="text" class="entry-input" id="colorOriginEntry" placeholder="Enter origin...">
                    </div>
                </div>
                
                <div class="entry-row">
                    <div class="entry-group">
                        <label class="entry-label">Color Group ID:</label>
                        <input type="text" class="entry-input" id="cgIdEntry" placeholder="Enter color group ID...">
                    </div>
                    <div class="entry-group">
                        <label class="entry-label">CG Type:</label>
                        <input type="text" class="entry-input" id="cgTypeEntry" placeholder="Enter CG type...">
                    </div>
                    <div class="entry-group">
                        <label class="entry-label">Status:</label>
                        <select class="entry-input" id="statusEntry">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                </div>
            </div>
            
            <!-- Data Table -->
            <div class="data-table-container">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Color#</th>
                            <th>Color Name</th>
                            <th>Origin</th>
                            <th>CG#</th>
                            <th>CG Type</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(isset($colors) && count($colors) > 0)
                            @foreach($colors as $color)
                                <tr>
                                    <td>{{ $color->color_id }}</td>
                                    <td>
                                        <div class="color-preview">
                                            <span class="color-swatch" style="background-color: #{{ substr($color->color_id, 0, 6) }};"></span>
                                            {{ $color->color_name }}
                                        </div>
                                    </td>
                                    <td>{{ $color->origin }}</td>
                                    <td>{{ $color->color_group_id }}</td>
                                    <td>{{ $color->cg_type ?? 'N/A' }}</td>
                                    <td>
                                        <div class="action-buttons">
                                            <button class="action-button edit-button" onclick="editColor('{{ $color->color_id }}')">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="action-button delete-button" onclick="deleteColor('{{ $color->color_id }}')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="6">
                                    <div class="empty-state">
                                        <i class="fas fa-palette fa-3x"></i>
                                        <p>Tidak ada data warna yang tersedia.<br>Klik 'New Color' untuk menambahkan warna baru.</p>
                                    </div>
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Handle search functionality
        $('#searchBtn').click(function() {
            performSearch();
        });
        
        $('#colorId').keypress(function(e) {
            if(e.which == 13) {
                performSearch();
            }
        });
        
        function performSearch() {
            const colorId = $('#colorId').val().trim();
            if (colorId) {
                window.location.href = '{{ route("color.index") }}?search=' + encodeURIComponent(colorId);
            } else {
                alert('Please enter a Color ID to search');
            }
        }
        
        // Exit button
        $('#exitBtn').click(function() {
            window.location.href = '{{ route("dashboard") }}';
        });
        
        // Select button
        $('#selectBtn').click(function() {
            window.location.href = '{{ route("color.create") }}';
        });
        
        // Fill form if record is found in search
        @if(isset($colors) && count($colors) > 0 && request('search'))
            const foundColor = @json($colors->first());
            $('#colorIdEntry').val(foundColor.color_id);
            $('#colorNameEntry').val(foundColor.color_name);
            $('#colorOriginEntry').val(foundColor.origin);
            $('#cgIdEntry').val(foundColor.color_group_id);
            $('#cgTypeEntry').val(foundColor.cg_type);
        @endif
    });
    
    // Edit color function
    function editColor(colorId) {
        window.location.href = '{{ route("color.edit", "") }}/' + colorId;
    }
    
    // Delete color function
    function deleteColor(colorId) {
        if (confirm('Are you sure you want to delete this color?')) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route("color.destroy", "") }}/' + colorId;
            form.style.display = 'none';
            
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';
            
            const method = document.createElement('input');
            method.type = 'hidden';
            method.name = '_method';
            method.value = 'DELETE';
            
            form.appendChild(csrfToken);
            form.appendChild(method);
            document.body.appendChild(form);
            form.submit();
        }
    }
</script>
@endsection
