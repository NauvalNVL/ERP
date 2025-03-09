@extends('layouts.app')

@section('title', 'Amend Password')

@section('header', 'Amend Password')

@section('content')
<div class="max-w-2xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <div class="bg-white shadow-xl rounded-lg p-8">
        <div class="flex items-center mb-8">
            <div class="bg-blue-100 p-3 rounded-full mr-4">
                <i class="fas fa-key text-blue-600 text-2xl"></i>
            </div>
            <h1 class="text-2xl font-bold text-gray-800">Manajemen Password Pengguna</h1>
        </div>

        <!-- Form Pencarian User -->
        <div class="bg-blue-50 rounded-xl p-6 mb-8">
            <form action="{{ route('users.amend-password') }}" method="GET" class="space-y-4">
                <div>
                    <label for="search_user_id" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-search text-blue-500 mr-1"></i>Cari User ID
                    </label>
                    <div class="flex gap-3">
                        <input type="text" name="search_user_id" id="search_user_id" 
                            class="form-input flex-1 rounded-lg p-3 border-2 border-gray-200 focus:border-blue-500 transition-all"
                            placeholder="Masukkan User ID..."
                            value="{{ old('search_user_id', $user->user_id ?? '') }}">
                        <button type="submit" 
                            class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg transition-all duration-300 transform hover:scale-105 flex items-center">
                            <i class="fas fa-search mr-2"></i>Cari
                        </button>
                    </div>
                </div>
            </form>
        </div>

        @if(isset($user))
        <!-- Form Ubah Password -->
        <div class="bg-green-50 rounded-xl p-6 border-2 border-green-200">
            <form action="{{ route('users.update-password') }}" method="POST" class="space-y-6">
                @csrf
                <div class="flex items-center mb-4">
                    <div class="bg-green-100 p-2 rounded-full mr-3">
                        <i class="fas fa-user-check text-green-600"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800">User Ditemukan: 
                        <span class="text-blue-600">{{ $user->user_id }}</span>
                    </h3>
                </div>

                <input type="hidden" name="user_id" value="{{ $user->user_id }}">

                <div>
                    <label for="new_password" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-lock text-blue-500 mr-1"></i>Password Baru
                    </label>
                    <input type="password" name="new_password" id="new_password" 
                        class="form-input w-full rounded-lg p-3 border-2 border-gray-200 focus:border-blue-500 transition-all"
                        placeholder="Masukkan password minimal 8 karakter"
                        required>
                </div>

                <div>
                    <label for="new_password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-lock text-blue-500 mr-1"></i>Konfirmasi Password
                    </label>
                    <input type="password" name="new_password_confirmation" id="new_password_confirmation" 
                        class="form-input w-full rounded-lg p-3 border-2 border-gray-200 focus:border-blue-500 transition-all"
                        placeholder="Ketik ulang password"
                        required>
                </div>

                <button type="submit" 
                    class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-6 rounded-lg transition-all duration-300 transform hover:scale-105 flex items-center justify-center">
                    <i class="fas fa-sync-alt mr-2"></i>Perbarui Password
                </button>
            </form>
        </div>
        @endif

        <!-- Feedback Messages -->
        @if(session('success'))
        <div class="mt-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg animate-fade-in">
            <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
        </div>
        @endif

        @if(session('error'))
        <div class="mt-6 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-lg animate-fade-in">
            <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
        </div>
        @endif
    </div>
</div>

<style>
.animate-fade-in {
    animation: fadeIn 0.5s ease-in;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
}
</style>
@endsection
