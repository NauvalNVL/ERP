@extends('layouts.app')

@section('title', 'Amend Password - ERP System')

@section('content')
<div class="container mx-auto px-4 sm:px-6">
    <div class="bg-white shadow-md rounded-lg p-4 sm:p-6">
        <h1 class="text-2xl font-semibold text-gray-900 mb-6">Amend Password</h1>
        
        <form action="{{ route('users.update-password') }}" method="POST">
            @csrf
            <div class="max-w-md space-y-4">
                <!-- User ID Input -->
                <div>
                    <label for="user_id" class="block text-sm font-medium text-gray-700">User ID</label>
                    <input type="text" name="user_id" id="user_id" 
                        class="form-input"
                        required
                        value="{{ old('user_id', $user->user_id ?? '') }}"
                        {{ isset($user) ? 'readonly' : '' }}>
                </div>

                @if(isset($user))
                <!-- Password Fields -->
                <div>
                    <label for="new_password" class="block text-sm font-medium text-gray-700">New Password</label>
                    <input type="password" name="new_password" id="new_password" 
                        class="form-input"
                        required>
                </div>

                <div>
                    <label for="confirm_password" class="block text-sm font-medium text-gray-700">Confirm Password</label>
                    <input type="password" name="confirm_password" id="confirm_password" 
                        class="form-input"
                        required>
                </div>
                @endif

                <button type="submit" 
                    class="w-full sm:w-auto inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                    {{ isset($user) ? 'Update Password' : 'Find User' }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
