

<!-- Add direct script for opening modal -->
<script>
// Data dari ColorSeeder langsung (sebagai fallback jika tidak ada data dari database)
const seedColors = [
    {
        color_id: '00001',
        color_name: 'Black',
        origin: '01',
        color_group_id: '01',
        cg_type: 'X-Flexo'
    },
    {
        color_id: '00002',
        color_name: 'White',
        origin: '02',
        color_group_id: '02',
        cg_type: 'X-Flexo'
    },
    {
        color_id: '00003',
        color_name: 'Red',
        origin: '03',
        color_group_id: '03',
        cg_type: 'X-Flexo'
    },
    {
        color_id: '00004',
        color_name: 'Dark Red',
        origin: '03',
        color_group_id: '03',
        cg_type: 'X-Flexo'
    },
    {
        color_id: '00005',
        color_name: 'Light Red',
        origin: '03',
        color_group_id: '03',
        cg_type: 'X-Flexo'
    },
    {
        color_id: '00006',
        color_name: 'Blue',
        origin: '04',
        color_group_id: '04',
        cg_type: 'X-Flexo'
    },
    {
        color_id: '00007',
        color_name: 'Dark Blue',
        origin: '04',
        color_group_id: '04',
        cg_type: 'X-Flexo'
    },
    {
        color_id: '00008',
        color_name: 'Light Blue',
        origin: '04',
        color_group_id: '04',
        cg_type: 'X-Flexo'
    },
    {
        color_id: '00009',
        color_name: 'Green',
        origin: '05',
        color_group_id: '05',
        cg_type: 'X-Flexo'
    },
    {
        color_id: '00010',
        color_name: 'Dark Green',
        origin: '05',
        color_group_id: '05',
        cg_type: 'X-Flexo'
    },
    {
        color_id: '00011',
        color_name: 'Light Green',
        origin: '05',
        color_group_id: '05',
        cg_type: 'X-Flexo'
    },
    {
        color_id: '00012',
        color_name: 'Cyan',
        origin: '06',
        color_group_id: '06',
        cg_type: 'X-Flexo'
    },
    {
        color_id: '00013',
        color_name: 'Dark Cyan',
        origin: '06',
        color_group_id: '06',
        cg_type: 'X-Flexo'
    },
    {
        color_id: '00014',
        color_name: 'Light Cyan',
        origin: '06',
        color_group_id: '06',
        cg_type: 'X-Flexo'
    },
    {
        color_id: '00015',
        color_name: 'Magenta',
        origin: '07',
        color_group_id: '07',
        cg_type: 'X-Flexo'
    },
    {
        color_id: '00016',
        color_name: 'Dark Magenta',
        origin: '07',
        color_group_id: '07',
        cg_type: 'X-Flexo'
    },
    {
        color_id: '00017',
        color_name: 'Light Magenta',
        origin: '07',
        color_group_id: '07',
        cg_type: 'X-Flexo'
    },
    {
        color_id: '00018',
        color_name: 'Pink',
        origin: '08',
        color_group_id: '08',
        cg_type: 'X-Flexo'
    },
    {
        color_id: '00019',
        color_name: 'Dark Pink',
        origin: '08',
        color_group_id: '08',
        cg_type: 'X-Flexo'
    },
    {
        color_id: '00020',
        color_name: 'Light Pink',
        origin: '08',
        color_group_id: '08',
        cg_type: 'X-Flexo'
    }
];

// Fungsi untuk mengisi tabel dengan data dari seedColors jika tidak ada data dari database
function populateColorTable() {
    console.log('Populating color table');
    
    // Cek apakah tabel sudah memiliki data
    const tbody = document.querySelector('#colorDataTable tbody');
    const rows = tbody.querySelectorAll('tr');
    const isEmpty = rows.length === 0 || (rows.length === 1 && rows[0].querySelector('td[colspan]'));
    
    if (isEmpty) {
        console.log('Table is empty, populating with seed data');
        
        // Hapus pesan "tidak ada data" jika ada
        tbody.innerHTML = '';
        
        // Isi tabel dengan data dari seedColors
        seedColors.forEach(color => {
            const row = document.createElement('tr');
            row.className = 'cursor-pointer';
            row.setAttribute('data-color-id', color.color_id);
            row.setAttribute('data-color-name', color.color_name);
            row.setAttribute('data-origin', color.origin);
            row.setAttribute('data-cg-id', color.color_group_id);
            row.setAttribute('data-cg-type', color.cg_type);
            row.onclick = function(e) { selectRow(this); e.stopPropagation(); };
            row.ondblclick = function() { openEditColorModal(this); };
            
            // Buat kolom untuk setiap sel
            const idCell = document.createElement('td');
            idCell.className = 'px-2 py-0.5 border-b border-r border-gray-300';
            idCell.textContent = color.color_id;
            
            const nameCell = document.createElement('td');
            nameCell.className = 'px-2 py-0.5 border-b border-r border-gray-300';
            nameCell.textContent = color.color_name;
            
            const originCell = document.createElement('td');
            originCell.className = 'px-2 py-0.5 border-b border-r border-gray-300';
            originCell.textContent = color.origin;
            
            const cgIdCell = document.createElement('td');
            cgIdCell.className = 'px-2 py-0.5 border-b border-r border-gray-300';
            cgIdCell.textContent = color.color_group_id;
            
            const cgNameCell = document.createElement('td');
            cgNameCell.className = 'px-2 py-0.5 border-b border-r border-gray-300';
            cgNameCell.textContent = getCGName(color.color_group_id);
            
            const cgTypeCell = document.createElement('td');
            cgTypeCell.className = 'px-2 py-0.5 border-b border-gray-300';
            cgTypeCell.textContent = color.cg_type;
            
            // Tambahkan semua sel ke baris
            row.appendChild(idCell);
            row.appendChild(nameCell);
            row.appendChild(originCell);
            row.appendChild(cgIdCell);
            row.appendChild(cgNameCell);
            row.appendChild(cgTypeCell);
            
            // Tambahkan baris ke tabel
            tbody.appendChild(row);
        });
        
        console.log('Table populated with seed data');
        
        // Setup event handlers for the table rows
        if (typeof setupTableRowEvents === 'function') {
            setupTableRowEvents();
        }
        
        // Apply blue highlighting to rows
        highlightBlueRows();
        
        // Urutkan tabel berdasarkan Color#
        sortTableDirectly(0);
    } else {
        console.log('Table already has data, no need to populate');
    }
}

// Fungsi mengurutkan tabel
function sortTableDirectly(columnIndex) {
    console.log("Sorting by column: " + columnIndex);
    
    var table = document.getElementById('colorDataTable');
    if (!table) {
        console.error("Table not found");
        return;
    }
    
    var tbody = table.querySelector('tbody');
    if (!tbody) {
        console.error("Table body not found");
        return;
    }
    
    var rows = Array.from(tbody.querySelectorAll('tr'));
    
    // Jangan urutkan jika tidak ada data atau hanya ada pesan "tidak ada data"
    if (rows.length === 0 || (rows.length === 1 && rows[0].querySelector('td[colspan]'))) {
        console.log("No data to sort");
        return;
    }
    
    // Urutkan baris berdasarkan isi kolom
    rows.sort(function(a, b) {
        // Periksa apakah kolom tersedia
        if (!a.cells[columnIndex] || !b.cells[columnIndex]) {
            return 0;
        }
        
        var textA = a.cells[columnIndex].textContent.trim();
        var textB = b.cells[columnIndex].textContent.trim();
        
        // Pengurutan berbasis numerik untuk kolom ID
        if (columnIndex === 0 || columnIndex === 3) {
            return textA.localeCompare(textB, undefined, { numeric: true });
        }
        
        // Pengurutan teks biasa untuk kolom lainnya
        return textA.localeCompare(textB);
    });
    
    // Hapus semua baris dari tabel
    while (tbody.firstChild) {
        tbody.removeChild(tbody.firstChild);
    }
    
    // Tambahkan baris yang sudah diurutkan ke tabel
    rows.forEach(function(row) {
        tbody.appendChild(row);
    });
    
    // Highlight baris pertama setelah pengurutan
    if (rows.length > 0) {
        rows.forEach(function(row) {
            row.classList.remove('selected');
        });
        rows[0].classList.add('selected');
    }
    
    // Highlight baris warna berdasarkan color group
    highlightBlueRows();
    
    console.log("Sorting complete");
}

// Fungsi untuk mengedit baris terpilih (Edit button)
function editSelectedRow() {
    var selectedRow = document.querySelector('#colorDataTable tbody tr.selected');
    if (!selectedRow) {
        console.log("No row selected");
        alert("Silahkan pilih warna terlebih dahulu");
        return;
    }
    
    console.log("Selected row for editing:", selectedRow);
    
    // Buka modal edit untuk baris terpilih
    openEditColorModal(selectedRow);
}

// Fungsi untuk debug - update langsung table
function debugUpdateTable() {
    console.log('Debug: direct table update test');
    
    // Get the first row of the table
    const firstRow = document.querySelector('#colorDataTable tbody tr');
    if (!firstRow) {
        console.error('No rows found in table');
        alert('No rows found in table');
        return;
    }
    
    console.log('First row:', firstRow);
    
    // Get the color ID from the row
    const colorId = firstRow.getAttribute('data-color-id');
    console.log('Color ID:', colorId);
    
    // Generate random values for testing
    const randomSuffix = Math.floor(Math.random() * 1000);
    const newName = `Test Color ${randomSuffix}`;
    const newOrigin = `${randomSuffix % 10}`;
    const newCgId = `0${(randomSuffix % 8) + 1}`;
    const newCgType = ['X-Flexo', 'Roto', 'Flexo'][randomSuffix % 3];
    
    console.log('New values:', { newName, newOrigin, newCgId, newCgType });
    
    try {
        // Update row cells directly
        const cells = firstRow.cells;
        console.log('Row has cells:', cells.length);
        
        if (cells.length >= 6) {
            // Display original values
            console.log('Original values:');
            console.log('- Cell 1 (Color Name):', cells[1].textContent);
            console.log('- Cell 2 (Origin):', cells[2].textContent);
            console.log('- Cell 3 (CG#):', cells[3].textContent);
            console.log('- Cell 5 (CG Type):', cells[5].textContent);
            
            // Update cell text directly
            cells[1].innerHTML = newName;
            cells[2].innerHTML = newOrigin;
            cells[3].innerHTML = newCgId;
            cells[5].innerHTML = newCgType;
            
            // Update data attributes
            firstRow.setAttribute('data-color-name', newName);
            firstRow.setAttribute('data-origin', newOrigin);
            firstRow.setAttribute('data-cg-id', newCgId);
            firstRow.setAttribute('data-cg-type', newCgType);
            
            // Force a refresh of the table display
            const table = document.getElementById('colorDataTable');
            table.style.display = 'none';
            setTimeout(() => {
                table.style.display = 'table';
                console.log('Table redisplayed to force refresh');
                
                // Highlight updated row
                selectRow(firstRow);
                highlightBlueRows();
            }, 50);
            
            alert('Row updated successfully with debug values');
        } else {
            console.error('Row does not have enough cells:', cells.length);
            alert('Error: Row does not have enough cells');
        }
    } catch (error) {
        console.error('Error updating row:', error);
        alert('Error updating row: ' + error.message);
    }
}

// Fungsi untuk menggunakan warna yang terpilih (Select button)
function useSelectedColor() {
    var selectedRow = document.querySelector('#colorDataTable tbody tr.selected');
    if (!selectedRow) {
        console.log("No row selected");
        alert("Silahkan pilih warna terlebih dahulu");
        return;
    }
    
    var colorId = selectedRow.cells[0].textContent.trim();
    var colorName = selectedRow.cells[1].textContent.trim();
    console.log("Selected color ID: " + colorId);
    
    // Pilih warna dan tutup modal
    selectColor(colorId, colorName);
}

// Fungsi memilih baris saat ini dan menutup modal
function selectCurrentRow() {
    var selectedRow = document.querySelector('#colorDataTable tbody tr.selected');
    if (!selectedRow) {
        console.log("No row selected");
        alert("Silahkan pilih warna terlebih dahulu");
        return;
    }
    
    var colorId = selectedRow.cells[0].textContent.trim();
    var colorName = selectedRow.cells[1].textContent.trim();
    console.log("Selected color ID: " + colorId);
    
    // Pilih warna dan tutup modal
    selectColor(colorId, colorName);
}

// Fungsi memilih warna
function selectColor(colorId, colorName) {
    console.log('Selecting color: ' + colorId + ' - ' + colorName);
    
    // Isi input form dengan ID warna yang dipilih
    var colorInput = document.querySelector('input[type="text"]');
    if (colorInput) {
        colorInput.value = colorId;
    }
    
    // Tutup modal
    closeColorModal();
}

// Fungsi menutup modal
function closeColorModal() {
    var modal = document.getElementById('colorTableWindow');
    if (modal) {
        modal.style.display = 'none';
        // modal.classList.add('hidden');
    }
}

// Fungsi memilih baris
function selectRow(row) {
    // Hapus kelas 'selected' dari semua baris
    var allRows = document.querySelectorAll('#colorDataTable tbody tr');
    allRows.forEach(function(r) {
        r.classList.remove('selected');
    });
    
    // Tambahkan kelas 'selected' ke baris yang dipilih
    row.classList.add('selected');
}

// Fungsi untuk membuka modal edit
function openEditColorModal(row) {
    console.log('Opening edit color modal for row:', row);
    
    const colorId = row.getAttribute('data-color-id');
    const colorName = row.getAttribute('data-color-name');
    const origin = row.getAttribute('data-origin');
    const cgId = row.getAttribute('data-cg-id');
    
    console.log('Color data:', { colorId, colorName, origin, cgId });
    
    document.getElementById('edit_color_id').value = colorId;
    document.getElementById('edit_color_name').value = colorName;
    document.getElementById('edit_color_origin').value = origin;
    document.getElementById('edit_color_group').value = cgId;
    document.getElementById('edit_cg_type').value = "X-Flexo"; // Always set to X-Flexo
    
    const editModal = document.getElementById('editColorModal');
    // editModal.classList.remove('hidden');
    editModal.style.display = 'block';
    
    console.log('Edit modal opened');
}

// Fungsi untuk menutup modal edit
function closeEditColorModal() {
    console.log('Closing edit color modal');
    const editModal = document.getElementById('editColorModal');
    editModal.classList.add('hidden');
    editModal.style.display = 'none';
}

// Fungsi untuk menampilkan modal
function openColorModal() {
    console.log('Opening color modal');
    var modal = document.getElementById('colorTableWindow');
    if (!modal) {
        console.error('Modal element not found');
        return;
    }
    
    // Tampilkan modal
    modal.style.display = 'block';
    modal.classList.remove('hidden');
    
    // Populasi tabel dengan data dari ColorSeeder jika perlu
    populateColorTable();
    
    // Urutkan berdasarkan Color# secara default
    sortTableDirectly(0);
    
    console.log('Modal opened successfully');
}

// Fungsi untuk memberikan warna biru pada baris tertentu
function highlightBlueRows() {
    // Color rows based on the pattern shown in the image
    var rows = document.querySelectorAll('#colorDataTable tbody tr');
    rows.forEach(function(row) {
        // Skip the "no data" row if present
        if (row.querySelector('td[colspan="6"]')) return;
        
        // Clear any existing color classes
        row.classList.remove('blue-row');
        
        // Get the color group ID from the row
        var cgId = row.getAttribute('data-cg-id');
        
        // Apply blue-row class based on the colorGroup patterns
        // Using the pattern from the image (blue for groups 01, 04, 06, 08)
        if (cgId === '01' || cgId === '04' || cgId === '06' || cgId === '08') {
            row.classList.add('blue-row');
        }
    });
}

// Mapping nama Color Group berdasarkan ID
function getCGName(cgId) {
    // Map of color group IDs to names based on ColorGroupSeeder
    const cgNames = {
        '01': 'BLACK',
        '02': 'WHITE',
        '03': 'RED',
        '04': 'BLUE',
        '05': 'GREEN',
        '06': 'CYAN',
        '07': 'MAGENTA',
        '08': 'PINK'
    };
    
    return cgNames[cgId] || '';
}

// Fungsi untuk mengatur event pada baris tabel
function setupTableRowEvents() {
    console.log('Setting up table row events');
    var tableRows = document.querySelectorAll('#colorDataTable tbody tr');
    console.log('Table rows found:', tableRows.length);
    
    tableRows.forEach(function(row) {
        // Lewati baris "tidak ada data"
        if (row.querySelector('td[colspan]')) return;
        
        // Event klik untuk memilih baris
        row.onclick = function(e) {
            e.stopPropagation();
            selectRow(this);
        };
        
        // Event klik ganda untuk membuka modal edit
        row.ondblclick = function() {
            console.log('Double-click detected on row:', this);
            openEditColorModal(this);
        };
    });
}

function closeModalX() {
    closeColorModal();
}

// Fungsi untuk membuat data tersedia tanpa database
function loadSeedData() {
    // Periksa apakah data sudah dimuat
    const tbody = document.querySelector('#colorDataTable tbody');
    if (!tbody) return;
    
    // Tampilkan loading overlay
    const loadingOverlay = document.getElementById('loadingOverlay');
    if (loadingOverlay) {
        loadingOverlay.classList.remove('hidden');
    }
    
    // Kosongkan tabel
    tbody.innerHTML = '';
    
    // Simulasi loading
    setTimeout(() => {
        // Isi tabel dengan data dari seedColors
        populateColorTable();
        
        // Tampilkan notifikasi
        alert('Data warna berhasil dimuat dari ColorSeeder');
        
        // Sembunyikan loading overlay
        if (loadingOverlay) {
            loadingOverlay.classList.add('hidden');
        }
        
        // Update notification di halaman utama
        const dbStatusElement = document.querySelector('.bg-yellow-100');
        if (dbStatusElement) {
            dbStatusElement.classList.remove('bg-yellow-100');
            dbStatusElement.classList.add('bg-green-100');
            dbStatusElement.innerHTML = `
                <p class="text-sm font-medium text-green-800">Data tersedia: ${seedColors.length} warna ditemukan (dari JavaScript).</p>
            `;
        }
        
        // Buka modal untuk menampilkan data
        openColorModal();
    }, 1000);
}

// Fungsi untuk menyimpan perubahan warna
function saveColorChanges() {
    console.log('Saving color changes');
    
    const colorId = document.getElementById('edit_color_id').value;
    const colorName = document.getElementById('edit_color_name').value;
    const origin = document.getElementById('edit_color_origin').value;
    const colorGroup = document.getElementById('edit_color_group').value;
    const cgType = "X-Flexo"; // Always use X-Flexo regardless of what's in the field
    const kgPerM2 = document.getElementById('edit_kg_per_m2').value || '1.0000';
    
    console.log('Form data to save:', { colorId, colorName, origin, colorGroup, cgType, kgPerM2 });
    
    // Validasi input sudah ditangani oleh atribut required di form
    
    // Tampilkan indikator loading pada tombol dan overlay
    const saveButton = document.querySelector('#editColorForm button[type="submit"]');
    const originalText = saveButton.innerText;
    saveButton.innerText = 'Menyimpan...';
    saveButton.disabled = true;
    
    // Tampilkan loading overlay
    document.getElementById('loadingOverlay').classList.remove('hidden');
    
    // Update row in table immediately to provide visual feedback
    const row = document.querySelector(`#colorDataTable tbody tr[data-color-id="${colorId}"]`);
    if (row) {
        console.log('Found row to update:', row);
        
        try {
            // Direct DOM manipulation for cell updates
            const cells = row.cells;
            console.log('Row has cells:', cells.length);
            
            if (cells.length >= 6) {
                console.log('Original cell values:');
                console.log('- Cell 1 (Color Name):', cells[1].textContent);
                console.log('- Cell 2 (Origin):', cells[2].textContent);
                console.log('- Cell 3 (CG#):', cells[3].textContent);
                console.log('- Cell 5 (CG Type):', cells[5].textContent);
                
                // Update cell text directly
                cells[1].textContent = colorName;
                cells[2].textContent = origin;
                cells[3].textContent = colorGroup;
                cells[5].textContent = cgType;
                
                // Get the color group name from JS function
                const cgName = getCGName(colorGroup);
                cells[4].textContent = cgName;
                
                console.log('After update:');
                console.log('- Cell 1 (Color Name):', cells[1].textContent);
                console.log('- Cell 2 (Origin):', cells[2].textContent);
                console.log('- Cell 3 (CG#):', cells[3].textContent);
                console.log('- Cell 4 (CG Name):', cells[4].textContent);
                console.log('- Cell 5 (CG Type):', cells[5].textContent);
                
                // Update data attributes
                row.setAttribute('data-color-name', colorName);
                row.setAttribute('data-origin', origin);
                row.setAttribute('data-cg-id', colorGroup);
                row.setAttribute('data-cg-type', cgType);
                
                // Re-apply selected class to ensure visibility
                row.classList.add('selected');
                
                // Reapply blue row highlighting
                highlightBlueRows();
                
                // Also update seedColors array to keep data in sync
                updateSeedColorData(colorId, colorName, origin, colorGroup, cgType, kgPerM2);
            } else {
                console.error('Row does not have enough cells:', cells.length);
            }
        } catch (error) {
            console.error('Error updating row cells:', error);
        }
        
        console.log('Row updated successfully in the table');
    } else {
        console.error('Row not found in table for color ID:', colorId);
    }
    
    // Show success message and close modal
    alert('Data warna berhasil diperbarui');
    closeEditColorModal();
    
    // Reset button state and hide loading overlay after a short delay
    setTimeout(() => {
        saveButton.innerText = originalText;
        saveButton.disabled = false;
        document.getElementById('loadingOverlay').classList.add('hidden');
    }, 500);
    
    // If you need to actually send the data to the server, uncomment this code:
    /*
    // Ambil token CSRF
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    // Kirim data ke server menggunakan fetch API
    fetch(`/color/${colorId}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': token
        },
        body: JSON.stringify({
            color_name: colorName,
            origin: origin,
            color_group_id: colorGroup,
            cg_type: cgType,
            kg_per_m2: kgPerM2
        })
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Server responded with status: ' + response.status);
        }
        return response.json();
    })
    .then(data => {
        console.log('Response:', data);
        
        // Update row in table
        const row = document.querySelector(`tr[data-color-id="${colorId}"]`);
        if (row) {
            // Update row cells
            row.cells[1].textContent = colorName;
            row.cells[2].textContent = origin;
            row.cells[3].textContent = colorGroup;
            row.cells[5].textContent = cgType;
            
            // Update data attributes for future editing
            row.setAttribute('data-color-name', colorName);
            row.setAttribute('data-origin', origin);
            row.setAttribute('data-cg-id', colorGroup);
            row.setAttribute('data-cg-type', cgType);
            
            // Reapply blue row highlighting if needed
            highlightBlueRows();
        }
        
        // Show success message and close modal
        alert('Data warna berhasil diperbarui');
        closeEditColorModal();
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat memperbarui data warna: ' + error.message);
    })
    .finally(() => {
        // Reset button state and hide loading overlay
        saveButton.innerText = originalText;
        saveButton.disabled = false;
        document.getElementById('loadingOverlay').classList.add('hidden');
    });
    */
}

// Fungsi untuk memperbarui data di seedColors array
function updateSeedColorData(colorId, colorName, origin, colorGroup, cgType, kgPerM2) {
    // Cari color dengan ID yang sesuai di seedColors array
    const colorIndex = seedColors.findIndex(color => color.color_id === colorId);
    
    // Always use X-Flexo for cg_type
    const finalCgType = "X-Flexo";
    
    if (colorIndex !== -1) {
        console.log(`Updating seedColors[${colorIndex}] with new data`);
        
        // Update data di array
        seedColors[colorIndex].color_name = colorName;
        seedColors[colorIndex].origin = origin;
        seedColors[colorIndex].color_group_id = colorGroup;
        seedColors[colorIndex].cg_type = finalCgType;
        
        console.log('Updated seedColors:', seedColors[colorIndex]);
    } else {
        console.log(`Color with ID ${colorId} not found in seedColors array`);
        
        // Jika tidak ditemukan, tambahkan sebagai item baru
        seedColors.push({
            color_id: colorId,
            color_name: colorName,
            origin: origin,
            color_group_id: colorGroup,
            cg_type: finalCgType
        });
        
        console.log('Added new color to seedColors array');
    }
}
</script>

@extends('layouts.app')

@section('title', 'Define Color')

@section('content')
<div class="container mx-auto px-4 py-6">
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
                <label class="text-gray-600">Color#:</label>
                <input type="text" class="border border-gray-300 rounded px-3 py-2 w-48">
                <button type="button" id="showColorTableBtn" class="bg-gray-200 hover:bg-gray-300 px-2 py-2 border border-gray-400" onclick="openColorModal()">
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
        @if(empty($colors) || count($colors) === 0)
        <div class="mt-4 bg-yellow-100 p-3 rounded">
            <p class="text-sm font-medium text-yellow-800">Tidak ada data warna yang tersedia.</p>
            <p class="text-xs text-yellow-700 mt-1">Pastikan database telah diatur dengan benar dan data seeder telah dijalankan.</p>
            <div class="mt-2 flex items-center space-x-3">
                <a href="{{ route('run.color.seeder') }}" class="bg-blue-500 hover:bg-blue-600 text-white text-xs px-3 py-1 rounded">
                    Run Color Seeder (DB)
                </a>
                <button onclick="loadSeedData()" class="bg-green-500 hover:bg-green-600 text-white text-xs px-3 py-1 rounded">
                    Load Color Seeder Data (JS)
                </button>
                <span class="text-xs text-yellow-700">atau jalankan: <code class="bg-yellow-200 px-1 py-0.5 rounded"></code></span>
            </div>
        </div>
        @elseif(count($colors) > 0)
        <div class="mt-4 bg-green-100 p-3 rounded">
            <p class="text-sm font-medium text-green-800">Data tersedia: {{ count($colors) }} warna ditemukan.</p>
                    </div>
        @endif
    </div>
</div>

<!-- Color Table Window -->
<div id="colorTableWindow" class="hidden fixed inset-0 z-50">
    <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white border-2 border-gray-500 shadow-lg" style="width: 560px;">
        <!-- Modal Header - Title Bar -->
        <div class="bg-blue-800 px-1 py-0.5 text-white flex justify-between items-center">
            <h3 class="text-xs font-normal">Color Table</h3>
            <button type="button" onclick="closeModalX()" class="text-white hover:text-gray-200 focus:outline-none">
                <span class="text-lg">×</span>
            </button>
        </div>

        <!-- Table Content -->
        <div class="p-1">
            <div class="border border-gray-300 overflow-hidden">
                <table class="w-full" id="colorDataTable">
                    <thead>
                        <tr>
                            <th class="px-2 py-0.5 bg-gray-100 border-b border-r border-gray-300 text-left text-xs font-semibold">Color#</th>
                            <th class="px-2 py-0.5 bg-gray-100 border-b border-r border-gray-300 text-left text-xs font-semibold">Color Name</th>
                            <th class="px-2 py-0.5 bg-gray-100 border-b border-r border-gray-300 text-left text-xs font-semibold">Origin</th>
                            <th class="px-2 py-0.5 bg-gray-100 border-b border-r border-gray-300 text-left text-xs font-semibold">CG#</th>
                            <th class="px-2 py-0.5 bg-gray-100 border-b border-r border-gray-300 text-left text-xs font-semibold">CG Name</th>
                            <th class="px-2 py-0.5 bg-gray-100 border-b border-gray-300 text-left text-xs font-semibold">CG Type</th>
                        </tr>
                    </thead>
                    <tbody class="text-xs">
                        <!-- Data dari database akan ditampilkan di sini jika tersedia -->
                        <!-- Jika tidak ada data dari database, JavaScript akan mengisi dengan data dari ColorSeeder -->
                        @if(isset($colors) && count($colors) > 0)
                        @foreach($colors as $color)
                            <tr class="cursor-pointer" 
                            data-color-id="{{ $color->color_id }}"
                            data-color-name="{{ $color->color_name }}"
                            data-origin="{{ $color->origin }}"
                                data-cg-id="{{ $color->color_group_id }}"
                                data-cg-type="{{ $color->cg_type ?? '' }}"
                                onclick="selectRow(this); event.stopPropagation();"
                                ondblclick="openEditColorModal(this)">
                                <td class="px-2 py-0.5 border-b border-r border-gray-300">{{ $color->color_id }}</td>
                                <td class="px-2 py-0.5 border-b border-r border-gray-300">{{ $color->color_name }}</td>
                                <td class="px-2 py-0.5 border-b border-r border-gray-300">{{ $color->origin }}</td>
                                <td class="px-2 py-0.5 border-b border-r border-gray-300">{{ $color->color_group_id }}</td>
                                <td class="px-2 py-0.5 border-b border-r border-gray-300">
                                    @if(isset($colorGroups) && count($colorGroups) > 0)
                                        @php $cgName = ''; @endphp
                                        @foreach($colorGroups as $cg)
                                            @if(isset($cg->cg) && $cg->cg == $color->color_group_id)
                                                @php $cgName = $cg->cg_name; @endphp
                                            @endif
                                        @endforeach
                                        {{ $cgName }}
                                    @else
                                        <script>document.write(getCGName('{{ $color->color_group_id }}'));</script>
                                    @endif
                                </td>
                                <td class="px-2 py-0.5 border-b border-gray-300">{{ $color->cg_type ?? '' }}</td>
                            </tr>
                        @endforeach
                        @else
                            <tr>
                                <td colspan="6" class="px-2 py-4 text-center border-b border-gray-300">Tidak ada data warna yang tersedia.</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>

            <!-- Bottom Buttons with equal spacing -->
            <div class="flex justify-between mt-2">
                <button type="button" onclick="sortTableDirectly(0)" class="py-0.5 px-2 bg-gray-100 border border-gray-400 hover:bg-gray-200 text-xs w-20">By Color#</button>
                <button type="button" onclick="sortTableDirectly(3)" class="py-0.5 px-2 bg-gray-100 border border-gray-400 hover:bg-gray-200 text-xs w-24">By CG# + Color#</button>
                <button type="button" onclick="sortTableDirectly(5)" class="py-0.5 px-2 bg-gray-100 border border-gray-400 hover:bg-gray-200 text-xs w-28">By CG Type + Color#</button>
                <button type="button" onclick="editSelectedRow()" class="py-0.5 px-2 bg-gray-100 border border-gray-400 hover:bg-gray-200 text-xs w-16">Select</button>
                <button type="button" onclick="closeColorModal()" class="py-0.5 px-2 bg-gray-100 border border-gray-400 hover:bg-gray-200 text-xs w-16">Exit</button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Color Modal -->
<div id="editColorModal" class="hidden fixed inset-0 z-50 bg-black bg-opacity-30">
    <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white border-2 border-gray-500 shadow-lg" style="width: 350px;">
        <!-- Modal Header - Title Bar -->
        <div class="bg-blue-800 px-1 py-0.5 text-white flex justify-between items-center">
            <h3 class="text-xs font-normal">Define Color</h3>
            <button type="button" onclick="closeEditColorModal()" class="text-white hover:text-gray-200 focus:outline-none">
                <span class="text-lg">×</span>
            </button>
        </div>

        <!-- Form Content -->
        <div class="p-4">
            <form id="editColorForm" onsubmit="saveColorChanges(); return false;">
                <div class="space-y-3">
                    <div class="flex items-center">
                        <label class="w-24 text-xs text-gray-600">Color#:</label>
                        <input id="edit_color_id" type="text" class="border border-gray-300 rounded px-2 py-1 text-xs w-24 bg-gray-100" readonly>
                    </div>
                    <div class="flex items-center">
                        <label class="w-24 text-xs text-gray-600">Color Name:</label>
                        <input id="edit_color_name" type="text" class="border border-gray-300 rounded px-2 py-1 text-xs w-full" required>
                    </div>
                    <div class="flex items-center">
                        <label class="w-24 text-xs text-gray-600">Color Origin:</label>
                        <input id="edit_color_origin" type="text" class="border border-gray-300 rounded px-2 py-1 text-xs w-20" required>
                    </div>
                    <div class="flex items-center">
                        <label class="w-24 text-xs text-gray-600">Color Group:</label>
                        <input id="edit_color_group" type="text" class="border border-gray-300 rounded px-2 py-1 text-xs w-20" required>
                        <button class="bg-blue-500 text-white ml-1 px-2 rounded text-xs">...</button>
                    </div>
                    <div class="flex items-center">
                        <label class="w-24 text-xs text-gray-600">CG Type:</label>
                        <input id="edit_cg_type" type="text" value="X-Flexo" class="border border-gray-300 rounded px-2 py-1 text-xs w-32 bg-gray-100" readonly>
                    </div>
                    <div class="flex items-center">
                        <label class="w-24 text-xs text-gray-600">KG per M2:</label>
                        <input id="edit_kg_per_m2" type="text" class="border border-gray-300 rounded px-2 py-1 text-xs w-24" value="1.0000">
                        <span class="text-xs ml-2">Estimate KG per M2</span>
                    </div>
                    <div class="mt-4">
                        <label class="text-xs text-gray-600">Note:</label>
                        <div class="border border-gray-300 p-2 mt-1 h-16 overflow-auto text-xs">
                            <p>Flexo Ink is estimated around 0.008 KG per M2</p>
                        </div>
                    </div>
                </div>
                <div class="flex justify-end mt-4">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white text-xs px-4 py-1 rounded mr-2">Save</button>
                    <button type="button" onclick="closeEditColorModal()" class="bg-gray-300 hover:bg-gray-400 text-gray-800 text-xs px-4 py-1 rounded">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Inisialisasi event handler saat dokumen dimuat
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM content loaded');
    
    // Test if the edit modal exists
    const editModal = document.getElementById('editColorModal');
    if (editModal) {
        console.log('Edit modal found in DOM');
    } else {
        console.error('Edit modal not found in DOM');
    }
    
    // Dapatkan tombol dan modal
    var showBtn = document.getElementById('showColorTableBtn');
    var colorModal = document.getElementById('colorTableWindow');
    
    // Tambahkan event listener ke tombol untuk menampilkan modal
    if (showBtn) {
        showBtn.onclick = function() {
            if (colorModal) {
                colorModal.style.display = 'block';
                colorModal.classList.remove('hidden');
                
                // Populasi tabel dengan data dari ColorSeeder jika perlu
                populateColorTable();
                
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
    if (colorModal) {
        colorModal.onclick = function(e) {
            if (e.target === colorModal) {
                closeColorModal();
            }
        };
    }
    
    // Event untuk menutup modal saat mengklik di luar
    window.onclick = function(event) {
        const editModal = document.getElementById('editColorModal');
        if (event.target === editModal) {
            closeEditColorModal();
        }
    };
    
    // Add a direct test button if needed
    var testBtn = document.createElement('button');
    testBtn.innerText = 'Test Edit Modal';
    testBtn.style.position = 'fixed';
    testBtn.style.bottom = '10px';
    testBtn.style.right = '10px';
    testBtn.style.zIndex = '9999';
    testBtn.style.padding = '5px 10px';
    testBtn.style.backgroundColor = '#4299e1';
    testBtn.style.color = 'white';
    testBtn.style.border = 'none';
    testBtn.style.borderRadius = '4px';
    testBtn.onclick = function() {
        // Get the first row from the table
        var firstRow = document.querySelector('#colorDataTable tbody tr');
        if (firstRow) {
            openEditColorModal(firstRow);
        } else {
            console.error('No rows found to test with');
        }
    };
    document.body.appendChild(testBtn);
    
    // Add debug update button
    var debugBtn = document.createElement('button');
    debugBtn.innerText = 'Debug Table Update';
    debugBtn.style.position = 'fixed';
    debugBtn.style.bottom = '50px';
    debugBtn.style.right = '10px';
    debugBtn.style.zIndex = '9999';
    debugBtn.style.padding = '5px 10px';
    debugBtn.style.backgroundColor = '#f56565';
    debugBtn.style.color = 'white';
    debugBtn.style.border = 'none';
    debugBtn.style.borderRadius = '4px';
    debugBtn.onclick = function() {
        debugUpdateTable();
    };
    document.body.appendChild(debugBtn);
});
</script>
@endpush

@push('styles')
<style>
    /* Modal window styles */
    #colorTableWindow, #editColorModal {
        background-color: rgba(0, 0, 0, 0.2);
        z-index: 9999;
    }
    
    #colorTableWindow:not(.hidden), #editColorModal:not(.hidden) {
        display: block;
    }
    
    #colorTableWindow.hidden, #editColorModal.hidden {
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
    #colorDataTable {
        border-collapse: collapse;
        width: 100%;
    }
    
    #colorDataTable tbody {
        display: block;
        max-height: 250px;
        overflow-y: auto;
        overflow-x: hidden;
    }
    
    #colorDataTable thead {
        display: table;
        width: calc(100% - 17px); /* Adjust for scrollbar width */
        table-layout: fixed;
    }
    
    #colorDataTable tbody tr {
        display: table;
        width: 100%;
        table-layout: fixed;
    }
    
    /* Selected row */
    #colorDataTable tbody tr.selected {
        background-color: #0078d7;
        color: white;
    }
    
    #colorDataTable tbody tr:hover {
        background-color: #0078d7;
        color: white;
    }
    
    /* Blue rows (as in the image) */
    #colorDataTable tbody tr.blue-row {
        background-color: #d0e7ff;
    }
    
    #colorDataTable tbody tr.blue-row:hover {
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
    #colorDataTable tbody::-webkit-scrollbar {
        width: 16px;
    }
    
    #colorDataTable tbody::-webkit-scrollbar-track {
        background: #f1f1f1;
    }
    
    #colorDataTable tbody::-webkit-scrollbar-thumb {
        background: #c1c1c1;
        border: 1px solid #a1a1a1;
    }
    
    #colorDataTable tbody::-webkit-scrollbar-thumb:hover {
        background: #a1a1a1;
    }
</style>
@endpush
@endsection