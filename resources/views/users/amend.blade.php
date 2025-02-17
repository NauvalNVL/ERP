@extends('layouts.app')

@section('title', 'Amend Password - ERP System')

@section('content')
<div class="container mx-auto px-4 sm:px-6">
    <div class="bg-white shadow-md rounded-lg p-4 sm:p-6">
        <h1 class="text-2xl font-semibold text-gray-900 mb-6">Amend Password</h1>
        
        @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
        @endif

        <form action="{{ route('users.amend-password') }}" method="GET" class="mb-8">
            <div class="max-w-md space-y-4">
                <div>
                    <label for="search_user_id" class="block text-sm font-medium text-gray-700">Cari User ID</label>
                    <input type="text" name="search_user_id" id="search_user_id" 
                        class="form-input mt-1 block w-full"
                        required
                        value="{{ old('search_user_id', $user->user_id ?? '') }}">
                </div>
                
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                    Cari User
                </button>
            </div>
        </form>

        @if(isset($user))
        <form action="{{ route('users.update-password') }}" method="POST">
            @csrf
            <div class="max-w-md space-y-4 border-t pt-8">
                <div>
                    <label class="block text-sm font-medium text-gray-700">User ID Terdeteksi</label>
                    <input type="text" value="{{ $user->user_id }}" 
                        class="form-input mt-1 block w-full bg-gray-100" readonly>
                    <input type="hidden" name="user_id" value="{{ $user->user_id }}">
                </div>

                <div>
                    <label for="new_password" class="block text-sm font-medium text-gray-700">Password Baru</label>
                    <input type="password" name="new_password" id="new_password" 
                        class="form-input mt-1 block w-full"
                        required>
                </div>

                <div>
                    <label for="new_password_confirmation" class="block text-sm font-medium text-gray-700">Konfirmasi Password</label>
                    <input type="password" name="new_password_confirmation" id="new_password_confirmation" 
                        class="form-input mt-1 block w-full"
                        required>
                </div>

                <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700">
                    Update Password
                </button>
            </div>
        </form>
        @endif

        @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mt-4">
            {{ session('error') }}
        </div>
        @endif
    </div>
</div>
@endsection
