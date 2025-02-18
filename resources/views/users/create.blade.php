@extends('layouts.app')

@section('title', 'Add New User - ERP System')

@section('content')
<div class="container mx-auto px-4 sm:px-6">
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-gray-900">Add New User</h1>
    </div>

    <div class="bg-white shadow-md rounded-lg p-4 sm:p-6">
        <form action="{{ route('users.store') }}" method="POST" class="space-y-4">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- User ID -->
                <div>
                    <label for="user_id" class="block text-sm font-medium text-gray-700">User ID</label>
                    <input type="text" name="user_id" id="user_id" 
                        class="form-input @error('user_id') border-red-500 @enderror"
                        required
                        value="{{ old('user_id') }}">
                    @error('user_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Username -->
                <div>
                    <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
                    <input type="text" name="username" id="username" 
                        class="form-input @error('username') border-red-500 @enderror"
                        required
                        value="{{ old('username') }}">
                    @error('username')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Official Name -->
                <div>
                    <label for="official_name" class="block text-sm font-medium text-gray-700">Official Name</label>
                    <input type="text" name="official_name" id="official_name" 
                        class="form-input @error('official_name') border-red-500 @enderror"
                        required
                        value="{{ old('official_name') }}">
                    @error('official_name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Official Title -->
                <div>
                    <label for="official_title" class="block text-sm font-medium text-gray-700">Official Title</label>
                    <input type="text" name="official_title" id="official_title" 
                        class="form-input @error('official_title') border-red-500 @enderror">
                    @error('official_title')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Mobile Number -->
                <div>
                    <label for="mobile_number" class="block text-sm font-medium text-gray-700">Mobile Number</label>
                    <input type="text" name="mobile_number" id="mobile_number" 
                        class="form-input @error('mobile_number') border-red-500 @enderror">
                    @error('mobile_number')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Official Tel -->
                <div>
                    <label for="official_tel" class="block text-sm font-medium text-gray-700">Official Telephone</label>
                    <input type="text" name="official_tel" id="official_tel" 
                        class="form-input @error('official_tel') border-red-500 @enderror">
                    @error('official_tel')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password Expiry Date -->
                <div>
                    <label for="password_expiry_date" class="block text-sm font-medium text-gray-700">Password Expiry</label>
                    <div class="flex items-center">
                        <input type="number" name="password_expiry_date" id="password_expiry_date" 
                            class="form-input w-32 @error('password_expiry_date') border-red-500 @enderror"
                            min="0"
                            value="0">
                        <span class="ml-2 text-sm text-gray-500">days (zero for none)</span>
                    </div>
                    @error('password_expiry_date')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Amend Expired Password -->
                <div>
                    <label for="amend_expired_password" class="block text-sm font-medium text-gray-700">Amend Expired Password</label>
                    <select name="amend_expired_password" id="amend_expired_password" 
                        class="form-input @error('amend_expired_password') border-red-500 @enderror">
                        <option value="Yes">Yes</option>
                        <option value="No">No</option>
                    </select>
                    @error('amend_expired_password')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status -->
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                    <select name="status" id="status" 
                        class="form-input @error('status') border-red-500 @enderror"
                        required>
                        <option value="A">A - Active</option>
                        <option value="O">O - Obsolate</option>
                    </select>
                    @error('status')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Buttons -->
            <div class="flex flex-col sm:flex-row justify-end gap-3 mt-6">
                <a href="{{ route('users.index') }}" 
                    class="w-full sm:w-auto text-center inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                    Cancel
                </a>
                <button type="submit" 
                    class="w-full sm:w-auto inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                    Create User
                </button>
            </div>
        </form>
    </div>
</div>
@endsection 