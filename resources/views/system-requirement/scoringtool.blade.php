@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-2">
        <h1 class="text-xl font-bold text-gray-800">Define Scoring Tool</h1>
        <div class="flex space-x-2">
            <a href="{{ route('dashboard') }}" class="bg-gray-500 hover:bg-gray-600 text-white py-1 px-3 rounded text-sm">
                <i class="fas fa-arrow-left mr-1"></i>Kembali
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-2 mb-4 text-sm" role="alert">
        <p>{{ session('success') }}</p>
    </div>
    @endif

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <!-- Header with icons -->
        <div class="flex items-center px-4 py-2 bg-gray-200 border-b border-gray-300">
            <div class="flex space-x-2">
                <button class="text-red-600" title="Delete">
                    <i class="fas fa-times-circle"></i>
                </button>
                <button class="text-blue-600" title="New">
                    <i class="fas fa-file"></i>
                </button>
                <button class="text-blue-600" title="Save">
                    <i class="fas fa-save"></i>
                </button>
                <button class="text-green-600" title="Print">
                    <i class="fas fa-print"></i>
                </button>
            </div>
        </div>

        <div class="p-4 border-b">
            <form action="{{ isset($scoringTool) ? route('scoring-tool.update', $scoringTool->id) : route('scoring-tool.store') }}" method="POST" class="space-y-3">
                @csrf
                @if(isset($scoringTool))
                    @method('PUT')
                @endif

                <div class="flex items-center mb-3">
                    <label for="code" class="w-32 text-sm font-medium text-gray-700">Scoring Tool Code:</label>
                    <div class="flex">
                        <input type="text" name="code" id="code" value="{{ isset($scoringTool) ? $scoringTool->code : old('code') }}" 
                            class="block w-48 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('code') border-red-500 @enderror">
                        <button type="button" onclick="showModal()" class="ml-1 px-2 py-1 bg-gray-200 border border-gray-400 text-gray-700 rounded-sm hover:bg-gray-300">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                    @error('code')
                    <p class="text-red-500 text-xs mt-1 ml-32">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center mb-3">
                    <label for="name" class="w-32 text-sm font-medium text-gray-700">Name:</label>
                    <input type="text" name="name" id="name" value="{{ isset($scoringTool) ? $scoringTool->name : old('name') }}" 
                        class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('name') border-red-500 @enderror">
                    @error('name')
                    <p class="text-red-500 text-xs mt-1 ml-32">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center mb-3">
                    <label for="scores" class="w-32 text-sm font-medium text-gray-700">Scores:</label>
                    <input type="number" step="0.1" name="scores" id="scores" value="{{ isset($scoringTool) ? $scoringTool->scores : old('scores', 0.0) }}" 
                        class="block w-48 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                </div>

                <div class="flex items-center mb-3">
                    <label for="gap" class="w-32 text-sm font-medium text-gray-700">Gap:</label>
                    <input type="number" step="0.1" name="gap" id="gap" value="{{ isset($scoringTool) ? $scoringTool->gap : old('gap', 0.0) }}" 
                        class="block w-48 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                </div>

                <div class="flex items-center mb-3">
                    <label for="specification" class="w-32 text-sm font-medium text-gray-700">Specification:</label>
                    <input type="text" name="specification" id="specification" value="{{ isset($scoringTool) ? $scoringTool->specification : old('specification') }}" 
                        class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                </div>

                <div class="flex items-start mb-3">
                    <label for="description" class="w-32 text-sm font-medium text-gray-700">Description:</label>
                    <textarea name="description" id="description" rows="3" 
                        class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">{{ isset($scoringTool) ? $scoringTool->description : old('description') }}</textarea>
                </div>

                <div class="flex items-center mb-3">
                    <label for="is_active" class="w-32 text-sm font-medium text-gray-700">Status:</label>
                    <select name="is_active" id="is_active" class="block w-48 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        <option value="1" {{ isset($scoringTool) && $scoringTool->is_active ? 'selected' : '' }}>Aktif</option>
                        <option value="0" {{ isset($scoringTool) && !$scoringTool->is_active ? 'selected' : '' }}>Tidak Aktif</option>
                    </select>
                </div>

                <div class="flex justify-end space-x-3 mt-4 pt-3 border-t">
                    <button type="reset" class="bg-gray-300 hover:bg-gray-400 text-gray-800 py-1 px-3 rounded text-sm">
                        Reset
                    </button>
                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white py-1 px-3 rounded text-sm">
                        {{ isset($scoringTool) ? 'Update' : 'Simpan' }}
                    </button>
                </div>
            </form>
        </div>

        <div class="p-4">
            <div class="mb-3 flex justify-between items-center">
                <h2 class="text-md font-semibold text-gray-800">Daftar Scoring Tool</h2>
                <form action="{{ route('scoring-tool.index') }}" method="GET" class="flex">
                    <input type="text" name="search" placeholder="Cari kode atau nama..." value="{{ request('search') }}" 
                        class="text-sm border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    <button type="submit" class="ml-2 bg-indigo-500 hover:bg-indigo-600 text-white py-1 px-2 rounded text-sm">
                        <i class="fas fa-search"></i>
                    </button>
                </form>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kode</th>
                            <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                            <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Scores</th>
                            <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Gap</th>
                            <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($scoringTools as $item)
                        <tr class="scoring-tool-row hover:bg-gray-50" data-id="{{ $item->id }}">
                            <td class="px-3 py-2 whitespace-nowrap text-sm font-medium text-gray-900">{{ $item->code }}</td>
                            <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-500">{{ $item->name }}</td>
                            <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-500">{{ $item->scores }}</td>
                            <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-500">{{ $item->gap }}</td>
                            <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-500">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $item->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $item->is_active ? 'Aktif' : 'Tidak Aktif' }}
                                </span>
                            </td>
                            <td class="px-3 py-2 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <a href="{{ route('scoring-tool.edit', $item->id) }}" class="text-indigo-600 hover:text-indigo-900">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('scoring-tool.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus item ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-3 py-2 whitespace-nowrap text-sm text-gray-500 text-center">
                                Tidak ada data scoring tool.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $scoringTools->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Modal untuk Scoring Tool -->
<div id="scoringToolModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5); z-index: 1000;">
    <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background-color: #f0f0f0; border: 1px solid #999; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2); width: 500px;">
        <div style="padding: 5px 10px; background-color: #e0e0e0; border-bottom: 1px solid #999; display: flex; justify-content: space-between; align-items: center;">
            <h3 style="font-size: 14px; font-weight: 600; margin: 0;">Scoring Tool Table</h3>
            <button onclick="hideModal()" style="background: none; border: none; font-size: 16px; font-weight: bold; cursor: pointer; color: #555;">&times;</button>
        </div>
        
        <div style="padding: 10px;">
            <div style="overflow: auto; height: 250px; border: 1px solid #999; background-color: white;">
                <table style="width: 100%; border-collapse: collapse;">
                    <thead style="background-color: #e0e0e0; position: sticky; top: 0;">
                        <tr>
                            <th style="padding: 2px 5px; text-align: left; font-size: 12px; font-weight: bold; border-right: 1px solid #999; border-bottom: 1px solid #999; width: 40px;">Code</th>
                            <th style="padding: 2px 5px; text-align: left; font-size: 12px; font-weight: bold; border-right: 1px solid #999; border-bottom: 1px solid #999;">Name</th>
                            <th style="padding: 2px 5px; text-align: right; font-size: 12px; font-weight: bold; border-right: 1px solid #999; border-bottom: 1px solid #999; width: 70px;">Score</th>
                            <th style="padding: 2px 5px; text-align: right; font-size: 12px; font-weight: bold; border-bottom: 1px solid #999; width: 40px;">Gap</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr onclick="selectRow(this)" ondblclick="selectTool(this)" class="scoring-row" style="background-color: #2070d0; color: white; cursor: pointer;" data-id="0" data-code="0" data-name="N/A" data-scores="0.0" data-gap="0.0">
                            <td style="padding: 2px 5px; font-size: 12px; border-right: 1px solid #999;">0</td>
                            <td style="padding: 2px 5px; font-size: 12px; border-right: 1px solid #999;">N/A</td>
                            <td style="padding: 2px 5px; font-size: 12px; text-align: right; border-right: 1px solid #999;">0.0</td>
                            <td style="padding: 2px 5px; font-size: 12px; text-align: right;">0.0</td>
                        </tr>
                        <tr onclick="selectRow(this)" ondblclick="selectTool(this)" class="scoring-row" style="cursor: pointer;" data-id="1" data-code="1" data-name="MALE MALE" data-scores="0.0" data-gap="0.0">
                            <td style="padding: 2px 5px; font-size: 12px; border-right: 1px solid #999;">1</td>
                            <td style="padding: 2px 5px; font-size: 12px; border-right: 1px solid #999;">MALE MALE</td>
                            <td style="padding: 2px 5px; font-size: 12px; text-align: right; border-right: 1px solid #999;">0.0</td>
                            <td style="padding: 2px 5px; font-size: 12px; text-align: right;">0.0</td>
                        </tr>
                        <tr onclick="selectRow(this)" ondblclick="selectTool(this)" class="scoring-row" style="cursor: pointer;" data-id="2" data-code="2" data-name="MALE FEMALE 10MM" data-scores="0.0" data-gap="0.0">
                            <td style="padding: 2px 5px; font-size: 12px; border-right: 1px solid #999;">2</td>
                            <td style="padding: 2px 5px; font-size: 12px; border-right: 1px solid #999;">MALE FEMALE 10MM</td>
                            <td style="padding: 2px 5px; font-size: 12px; text-align: right; border-right: 1px solid #999;">0.0</td>
                            <td style="padding: 2px 5px; font-size: 12px; text-align: right;">0.0</td>
                        </tr>
                        <tr onclick="selectRow(this)" ondblclick="selectTool(this)" class="scoring-row" style="cursor: pointer;" data-id="3" data-code="3" data-name="MALE FLAT" data-scores="0.0" data-gap="0.0">
                            <td style="padding: 2px 5px; font-size: 12px; border-right: 1px solid #999;">3</td>
                            <td style="padding: 2px 5px; font-size: 12px; border-right: 1px solid #999;">MALE FLAT</td>
                            <td style="padding: 2px 5px; font-size: 12px; text-align: right; border-right: 1px solid #999;">0.0</td>
                            <td style="padding: 2px 5px; font-size: 12px; text-align: right;">0.0</td>
                        </tr>
                        <tr onclick="selectRow(this)" ondblclick="selectTool(this)" class="scoring-row" style="cursor: pointer;" data-id="4" data-code="4" data-name="MALE FEMALE 8MM" data-scores="0.0" data-gap="0.0">
                            <td style="padding: 2px 5px; font-size: 12px; border-right: 1px solid #999;">4</td>
                            <td style="padding: 2px 5px; font-size: 12px; border-right: 1px solid #999;">MALE FEMALE 8MM</td>
                            <td style="padding: 2px 5px; font-size: 12px; text-align: right; border-right: 1px solid #999;">0.0</td>
                            <td style="padding: 2px 5px; font-size: 12px; text-align: right;">0.0</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        
        <div style="padding: 8px 10px; background-color: #e0e0e0; display: flex; justify-content: center; gap: 10px; border-top: 1px solid #999;">
            <button onclick="selectCurrentTool()" style="width: 80px; background-color: #e8e8e8; border: 1px solid #999; padding: 3px 0; font-size: 12px;">
                Select
            </button>
            <button onclick="hideModal()" style="width: 80px; background-color: #e8e8e8; border: 1px solid #999; padding: 3px 0; font-size: 12px;">
                Exit
            </button>
        </div>
    </div>
</div>

<!-- Modal untuk Edit Scoring Tool -->
<div id="editScoringToolModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5); z-index: 1000;">
    <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background-color: #f0f0f0; border: 1px solid #999; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2); width: 500px;">
        <div style="padding: 5px 10px; background-color: #e0e0e0; border-bottom: 1px solid #999; display: flex; justify-content: space-between; align-items: center;">
            <h3 style="font-size: 14px; font-weight: 600; margin: 0;">Define Scoring Tool</h3>
            <div style="display: flex; align-items: center;">
                <button onclick="saveEditedTool()" style="font-size: 12px; padding: 2px 8px; background-color: #e8e8e8; border: 1px solid #999; margin-right: 10px;">Record: Revise</button>
                <button onclick="closeEditModal()" style="background: none; border: none; font-size: 16px; font-weight: bold; cursor: pointer; color: #555;">&times;</button>
            </div>
        </div>
        
        <div style="padding: 20px 10px;">
            <div style="margin-bottom: 12px; display: flex; align-items: center;">
                <label style="width: 120px; font-size: 12px;">Scoring Tool Code:</label>
                <input type="text" id="editCode" style="width: 60px; border: 1px solid #999; padding: 2px;" readonly>
            </div>
            
            <div style="margin-bottom: 12px; display: flex; align-items: center;">
                <label style="width: 120px; font-size: 12px;">Scoring Tool Name:</label>
                <input type="text" id="editName" style="width: 300px; border: 1px solid #999; padding: 2px;">
            </div>
            
            <div style="margin-bottom: 12px; display: flex; align-items: center;">
                <label style="width: 120px; font-size: 12px;">Scorer Gap:</label>
                <input type="text" id="editGap" style="width: 60px; border: 1px solid #999; padding: 2px;" value="0.0">
            </div>
        </div>
    </div>
</div>

<!-- Tambahkan script JavaScript berikut -->
<script>
    // Variabel untuk menyimpan baris yang dipilih
    let selectedRow = null;
    
    // Fungsi untuk menampilkan modal
    function showModal() {
        document.getElementById('scoringToolModal').style.display = 'block';
    }
    
    // Fungsi untuk menyembunyikan modal
    function hideModal() {
        document.getElementById('scoringToolModal').style.display = 'none';
    }
    
    // Fungsi untuk memilih baris
    function selectRow(row) {
        // Hapus highlight dari baris sebelumnya jika ada
        if (selectedRow) {
            selectedRow.style.backgroundColor = '';
            selectedRow.style.color = '';
        }
        
        // Highlight baris yang dipilih
        row.style.backgroundColor = '#2070d0';
        row.style.color = 'white';
        selectedRow = row;
    }
    
    // Fungsi untuk memilih tool saat double-click
    function selectTool(row) {
        selectRow(row);
        showEditModal();
    }
    
    // Fungsi untuk menampilkan modal edit
    function showEditModal() {
        if (selectedRow) {
            const code = selectedRow.getAttribute('data-code');
            const name = selectedRow.getAttribute('data-name');
            const gap = selectedRow.getAttribute('data-gap') || '0.0';
            
            // Isi nilai field form edit
            document.getElementById('editCode').value = code;
            document.getElementById('editName').value = name;
            document.getElementById('editGap').value = gap;
            
            // Tutup modal pemilihan
            hideModal();
            
            // Tampilkan modal edit
            document.getElementById('editScoringToolModal').style.display = 'block';
        } else {
            alert('Silakan pilih scoring tool terlebih dahulu.');
        }
    }
    
    // Fungsi untuk menyimpan tool yang diedit
    function saveEditedTool() {
        const code = document.getElementById('editCode').value;
        const name = document.getElementById('editName').value;
        const gap = document.getElementById('editGap').value;
        
        // Isi nilai field form utama
        document.getElementById('code').value = code;
        document.getElementById('name').value = name;
        document.getElementById('gap').value = gap;
        
        // Tutup modal edit
        document.getElementById('editScoringToolModal').style.display = 'none';
    }
    
    // Fungsi untuk memilih tool yang saat ini dipilih
    function selectCurrentTool() {
        if (selectedRow) {
            showEditModal();
        } else {
            alert('Silakan pilih scoring tool terlebih dahulu.');
        }
    }
    
    // Tutup modal jika user mengklik di luar modal
    window.onclick = function(event) {
        const modal = document.getElementById('scoringToolModal');
        const editModal = document.getElementById('editScoringToolModal');
        if (event.target == modal) {
            hideModal();
        }
        if (event.target == editModal) {
            editModal.style.display = 'none';
        }
    }
    
    // Inisialisasi baris pertama sebagai dipilih (jika ada)
    document.addEventListener('DOMContentLoaded', function() {
        const firstRow = document.querySelector('.scoring-row');
        if (firstRow) {
            selectRow(firstRow);
        }
    });
    
    // Fungsi untuk menutup modal edit
    function closeEditModal() {
        document.getElementById('editScoringToolModal').style.display = 'none';
    }
</script>

@endsection