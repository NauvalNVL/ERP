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
                            <i class="fas fa-briefcase text-blue-500 mr-1"></i>Jabatan
                        </label>
                        <input type="text" name="official_title" id="official_title" 
                            class="form-input w-full rounded-lg p-3 border-2 border-gray-200 focus:border-blue-500 transition-all"
                            placeholder="Contoh: Manager Produksi"
                            value="{{ old('official_title') }}">
                    </div>

                    <!-- Mobile Number -->
                    <div>
                        <label for="mobile_number" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-mobile-alt text-blue-500 mr-1"></i>Nomor HP
                        </label>
                        <input type="text" name="mobile_number" id="mobile_number" 
                            class="form-input w-full rounded-lg p-3 border-2 border-gray-200 focus:border-blue-500 transition-all"
                            placeholder="Contoh: 08123456789"
                            value="{{ old('mobile_number') }}">
                    </div>

                    <!-- Official Tel -->
                    <div>
                        <label for="official_tel" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-phone-alt text-blue-500 mr-1"></i>Telepon Kantor
                        </label>
                        <input type="text" name="official_tel" id="official_tel" 
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
@endsection 