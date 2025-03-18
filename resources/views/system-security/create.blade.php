@extends('layouts.app')

@section('title', 'Buat User Baru - ERP System')

@section('content')
<div class="max-w-4xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <div class="bg-white shadow-xl rounded-lg p-8">
        @if ($errors->any())
        <div class="mb-6 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-lg">
            <i class="fas fa-exclamation-circle mr-2"></i> Terdapat kesalahan dalam input data:
            <ul class="mt-2 list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        @if(session('error'))
        <div class="mb-6 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-lg">
            <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
        </div>
        @endif

        <h2 class="text-2xl font-bold text-gray-800 mb-6 border-b-2 border-blue-200 pb-3">
            <i class="fas fa-user-plus text-blue-600 mr-2"></i>Form Pendaftaran User Baru
        </h2>

        <form action="{{ route('users.store') }}" method="POST" class="space-y-6">
            @csrf
            
            <!-- Section Informasi Utama -->
            <div class="space-y-6">
                <h3 class="text-lg font-semibold text-blue-700 mb-4 flex items-center">
                    <i class="fas fa-id-card mr-2"></i>Informasi Utama
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- User ID -->
                    <div>
                        <label for="user_id" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-user-tag text-blue-500 mr-1"></i>User ID
                        </label>
                        <input type="text" name="user_id" id="user_id" 
                            class="form-input @error('user_id') border-red-500 @enderror w-full rounded-lg p-3 border-2 border-gray-200 focus:border-blue-500 transition-all"
                            placeholder="Contoh: EMP001"
                            value="{{ old('user_id') }}">
                        @error('user_id')
                            <p class="text-red-500 text-sm mt-1 animate-pulse">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Username -->
                    <div>
                        <label for="username" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-user-circle text-blue-500 mr-1"></i>Username
                        </label>
                        <input type="text" name="username" id="username" 
                            class="form-input @error('username') border-red-500 @enderror w-full rounded-lg p-3 border-2 border-gray-200 focus:border-blue-500 transition-all"
                            placeholder="Contoh: johndoe"
                            value="{{ old('username') }}">
                        @error('username')
                            <p class="text-red-500 text-sm mt-1 animate-pulse">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Section Data Pribadi -->
            <div class="space-y-6">
                <h3 class="text-lg font-semibold text-blue-700 mb-4 flex items-center">
                    <i class="fas fa-address-book mr-2"></i>Data Pribadi
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Official Name -->
                    <div>
                        <label for="official_name" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-signature text-blue-500 mr-1"></i>Nama Resmi
                        </label>
                        <input type="text" name="official_name" id="official_name" 
                            class="form-input @error('official_name') border-red-500 @enderror w-full rounded-lg p-3 border-2 border-gray-200 focus:border-blue-500 transition-all"
                            placeholder="Contoh: John Doe"
                            value="{{ old('official_name') }}">
                        @error('official_name')
                            <p class="text-red-500 text-sm mt-1 animate-pulse">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Official Title -->
                    <div>
                        <label for="official_title" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-briefcase text-blue-500 mr-1"></i>Jabatan <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="official_title" id="official_title" required
                            class="form-input w-full rounded-lg p-3 border-2 border-gray-200 focus:border-blue-500 transition-all"
                            placeholder="Contoh: Manager Produksi"
                            value="{{ old('official_title') }}">
                    </div>

                    <!-- Mobile Number -->
                    <div>
                        <label for="mobile_number" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-mobile-alt text-blue-500 mr-1"></i>Nomor HP <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="mobile_number" id="mobile_number" required
                            class="form-input w-full rounded-lg p-3 border-2 border-gray-200 focus:border-blue-500 transition-all"
                            placeholder="Contoh: 08123456789"
                            value="{{ old('mobile_number') }}">
                    </div>

                    <!-- Official Tel -->
                    <div>
                        <label for="official_tel" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-phone-alt text-blue-500 mr-1"></i>Telepon Kantor <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="official_tel" id="official_tel" required
                            class="form-input w-full rounded-lg p-3 border-2 border-gray-200 focus:border-blue-500 transition-all"
                            placeholder="Contoh: 0211234567"
                            value="{{ old('official_tel') }}">
                    </div>
                </div>
            </div>

            <!-- Section Pengaturan Akun -->
            <div class="space-y-6">
                <h3 class="text-lg font-semibold text-blue-700 mb-4 flex items-center">
                    <i class="fas fa-cogs mr-2"></i>Pengaturan Akun
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Status -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-user-lock text-blue-500 mr-1"></i>Status Akun
                        </label>
                        <select name="status" id="status" 
                            class="form-select @error('status') border-red-500 @enderror w-full rounded-lg p-3 border-2 border-gray-200 focus:border-blue-500 transition-all">
                            <option value="A" class="text-green-600">🟢 Active</option>
                            <option value="O" class="text-red-600">🔴 Obsolate</option>
                        </select>
                        @error('status')
                            <p class="text-red-500 text-sm mt-1 animate-pulse">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password Expiry Date -->
                    <div>
                        <label for="password_expiry_date" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-calendar-times text-blue-500 mr-1"></i>Masa Berlaku Password
                        </label>
                        <div class="flex items-center space-x-3">
                            <input type="number" name="password_expiry_date" id="password_expiry_date" 
                                class="form-input w-32 rounded-lg p-3 border-2 border-gray-200 focus:border-blue-500 transition-all"
                                min="0"
                                value="{{ old('password_expiry_date', 0) }}">
                            <span class="text-sm text-gray-500">hari (0 = tidak kadaluarsa)</span>
                        </div>
                    </div>

                    <!-- Amend Expired Password -->
                    <div>
                        <label for="amend_expired_password" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-key text-blue-500 mr-1"></i>Perbarui Password Kadaluarsa
                        </label>
                        <select name="amend_expired_password" id="amend_expired_password" 
                            class="form-select w-full rounded-lg p-3 border-2 border-gray-200 focus:border-blue-500 transition-all">
                            <option value="Yes">Ya</option>
                            <option value="No">Tidak</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- User Special Access Section -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <!-- Sales Management Section -->
                <div class="mb-8 bg-white rounded-xl shadow-sm border border-gray-200">
                    <h3 class="text-lg font-semibold bg-blue-600 text-white p-4 rounded-t-xl">
                        <i class="fas fa-chart-line mr-2"></i>Sales Management
                    </h3>
                    
                    <div class="p-6 space-y-6">
                        <!-- Radio Group Grid -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Column 1 -->
                            <div class="space-y-4">
                                <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg">
                                    <label class="w-48 font-medium">Access Unit Price:</label>
                                    <div class="flex gap-4">
                                        <label class="flex items-center gap-2">
                                            <input type="radio" name="sales_access[unit_price]" value="Y" 
                                                   class="form-radio h-5 w-5 text-blue-600 border-2">
                                            <span class="text-gray-700">Yes</span>
                                        </label>
                                        <label class="flex items-center gap-2">
                                            <input type="radio" name="sales_access[unit_price]" value="N" 
                                                   class="form-radio h-5 w-5 text-blue-600 border-2">
                                            <span class="text-gray-700">No</span>
                                        </label>
                                    </div>
                                </div>
                                
                                <!-- Tambahkan radio group lainnya dengan struktur yang sama -->
                                <!-- Contoh untuk Access Customer A/C -->
                                <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg">
                                    <label class="w-48 font-medium">Access Customer A/C:</label>
                                    <div class="flex gap-4">
                                        <label class="flex items-center gap-2">
                                            <input type="radio" name="sales_access[customer_ac]" value="Y" 
                                                   class="form-radio h-5 w-5 text-blue-600 border-2">
                                            <span class="text-gray-700">Yes</span>
                                        </label>
                                        <label class="flex items-center gap-2">
                                            <input type="radio" name="sales_access[customer_ac]" value="N" 
                                                   class="form-radio h-5 w-5 text-blue-600 border-2">
                                            <span class="text-gray-700">No</span>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Column 2 -->
                            <div class="space-y-4">
                                <!-- Radio groups untuk kolom kedua -->
                                <!-- Contoh untuk Amend MC -->
                                <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg">
                                    <label class="w-48 font-medium">Amend MC:</label>
                                    <div class="flex gap-4">
                                        <label class="flex items-center gap-2">
                                            <input type="radio" name="sales_access[amend_mc]" value="Y" 
                                                   class="form-radio h-5 w-5 text-blue-600 border-2">
                                            <span class="text-gray-700">Yes</span>
                                        </label>
                                        <label class="flex items-center gap-2">
                                            <input type="radio" name="sales_access[amend_mc]" value="N" 
                                                   class="form-radio h-5 w-5 text-blue-600 border-2">
                                            <span class="text-gray-700">No</span>
                                        </label>
                                    </div>
                                </div>
                                
                                <!-- Tambahkan radio groups lainnya di sini -->
                            </div>
                        </div>

                        <!-- Lock to Salesperson Section -->
                        <div class="space-y-4 pt-4 border-t border-gray-200">
                            <label class="block text-sm font-medium text-gray-700">
                                <i class="fas fa-lock mr-2"></i>Lock to Salesperson
                            </label>
                            <div class="flex gap-2">
                                <input type="text" id="salesperson_search" 
                                       class="flex-1 rounded-lg border-2 border-gray-200 p-3 focus:border-blue-500 focus:ring-2 focus:ring-blue-200"
                                       placeholder="Cari salesperson...">
                                <button type="button" onclick="openSalespersonModal()" 
                                        class="bg-blue-500 hover:bg-blue-600 text-white px-6 rounded-lg transition-all flex items-center">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                            <div id="selected_salesperson" class="mt-2 space-y-2"></div>
                        </div>
                    </div>
                </div>

                <!-- Warehouse Management Section -->
                <div class="mb-8 bg-white rounded-xl shadow-sm border border-gray-200">
                    <h3 class="text-lg font-semibold bg-blue-600 text-white p-4 rounded-t-xl">
                        <i class="fas fa-warehouse mr-2"></i>Warehouse Management
                    </h3>
                    <div class="p-6">
                        <label class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg">
                            <input type="checkbox" name="warehouse_access[wbms]" 
                                   class="form-checkbox h-5 w-5 text-blue-600 rounded border-2">
                            <span class="text-gray-700 font-medium">WBMS Restricted Pass</span>
                            <span class="text-blue-600 ml-2">(Yes)</span>
                        </label>
                    </div>
                </div>

                <!-- Material Management Section -->
                <div class="mb-8">
                    <h3 class="text-lg font-semibold bg-blue-100 p-3 rounded-t-lg">Material Management (MM2 Only)</h3>
                    <div class="p-4 border border-t-0 rounded-b-lg space-y-2">
                        <div class="flex items-center gap-2">
                            <label class="w-48">RC + RT Price:</label>
                            <label class="flex items-center gap-1">
                                <input type="radio" name="material_access[rc_rt]" value="Y" class="form-radio text-blue-600">
                                <span class="text-sm">Y - Yes</span>
                            </label>
                            <label class="flex items-center gap-1">
                                <input type="radio" name="material_access[rc_rt]" value="N" class="form-radio text-blue-600">
                                <span class="text-sm">N - No</span>
                            </label>
                            <span class="text-sm text-gray-500 ml-2">Cannot See Price + Tax</span>
                        </div>
                    </div>
                </div>

                <!-- Board Purchase Section -->
                <div class="mb-8">
                    <h3 class="text-lg font-semibold bg-blue-100 p-3 rounded-t-lg">Board Purchase</h3>
                    <div class="p-4 border border-t-0 rounded-b-lg space-y-2">
                        <div class="flex items-center gap-2">
                            <label class="w-48">Board RC Cost:</label>
                            <label class="flex items-center gap-1">
                                <input type="radio" name="board_access[rc_cost]" value="Y" class="form-radio text-blue-600">
                                <span class="text-sm">Y - Yes</span>
                            </label>
                            <label class="flex items-center gap-1">
                                <input type="radio" name="board_access[rc_cost]" value="N" class="form-radio text-blue-600">
                                <span class="text-sm">N - No</span>
                            </label>
                            <span class="text-sm text-gray-500 ml-2">Cannot See Price + Tax</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="mt-8">
                <button type="submit" 
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg transition-all duration-300 transform hover:scale-105 flex items-center justify-center">
                    <i class="fas fa-save mr-2"></i>Simpan User Baru
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Salesperson Modal -->
<div id="salespersonModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50">
    <div class="bg-white rounded-xl p-6 max-w-2xl mx-auto mt-20 shadow-2xl">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-xl font-semibold">Pilih Salesperson</h3>
            <button onclick="closeSalespersonModal()" 
                    class="text-gray-500 hover:text-gray-700 text-2xl">
                &times;
            </button>
        </div>
        <div class="relative mb-4">
            <input type="text" id="modalSearch" placeholder="Cari salesperson..." 
                   class="w-full p-3 border-2 border-gray-200 rounded-lg"
                   onkeyup="searchSalesperson()">
            <i class="fas fa-search absolute right-3 top-4 text-gray-400"></i>
        </div>
        <div class="space-y-2 max-h-96 overflow-y-auto" id="salespersonList">
            <!-- Hasil pencarian akan muncul di sini -->
        </div>
    </div>
</div>

<script>
function searchSalesperson() {
    const input = document.getElementById('modalSearch').value.toLowerCase();
    const items = document.querySelectorAll('#salespersonList div');
    
    items.forEach(item => {
        const text = item.textContent.toLowerCase();
        item.style.display = text.includes(input) ? 'block' : 'none';
    });
}

function selectSalesperson(code, name) {
    const displayDiv = document.getElementById('selected_salesperson');
    displayDiv.innerHTML = `
        <div class="bg-blue-100 border border-blue-200 rounded-lg p-3 flex items-center justify-between">
            <div>
                <span class="font-medium text-blue-800">${code}</span>
                <span class="text-gray-600 ml-2">${name}</span>
                <input type="hidden" name="locked_salesperson" value="${code}">
            </div>
            <button type="button" onclick="this.parentElement.remove()" 
                    class="text-red-500 hover:text-red-700 ml-2">
                <i class="fas fa-times-circle"></i>
            </button>
        </div>
    `;
    closeSalespersonModal();
}

function closeSalespersonModal() {
    document.getElementById('salespersonModal').classList.add('hidden');
}
</script>
@endsection 