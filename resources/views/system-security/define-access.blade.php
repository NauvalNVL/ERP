@extends('layouts.app')

@section('title', 'Define User Access Permission')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-xl shadow-md p-6 mb-8">
        <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
            <i class="fas fa-user-lock text-blue-500 mr-3"></i>Define User Access Permission
        </h2>

        <!-- Search Form -->
        <form method="POST" action="{{ route('users.define-access') }}" class="mb-8">
            @csrf
            <div class="flex gap-4 items-end">
                <div class="flex-1">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Cari User ID
                        <span class="text-xs text-gray-500">(Contoh: ADMIN001)</span>
                    </label>
                    <input type="text" name="user_id" 
                           class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="Masukkan User ID" required>
                </div>
                <button type="submit" 
                        class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg transition-all flex items-center">
                    <i class="fas fa-search mr-2"></i>Cari User
                </button>
            </div>
        </form>

        @if(isset($user))
        <!-- Permissions Form -->
        <form method="POST" action="{{ route('users.save-permissions') }}">
            @csrf
            <input type="hidden" name="user_id" value="{{ $user->user_id }}">
            
            <div class="bg-gray-50 p-6 rounded-xl">
                <div class="flex items-center mb-6">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($user->official_name) }}&background=random" 
                         class="w-12 h-12 rounded-full mr-4" alt="Avatar">
                    <div>
                        <h3 class="text-lg font-semibold">{{ $user->official_name }}</h3>
                        <p class="text-sm text-gray-600">{{ $user->user_id }}</p>
                    </div>
                </div>

                <!-- Permission Groups -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- System Manager -->
                    <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                        <h4 class="font-semibold text-lg mb-4 text-blue-600">
                            <i class="fas fa-cogs mr-2"></i>System Manager
                        </h4>
                        <div class="space-y-2">
                            @include('partials.permission-checkbox', [
                                'name' => 'permissions[]',
                                'value' => 'system_manager',
                                'label' => 'Akses Penuh System Manager',
                                'checked' => in_array('system_manager', $permissions)
                            ])
                        </div>
                    </div>

                    <!-- Sales Management -->
                    <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                        <h4 class="font-semibold text-lg mb-4 text-green-600">
                            <i class="fas fa-chart-line mr-2"></i>Sales Management
                        </h4>
                        <div class="space-y-2">
                            @include('partials.permission-checkbox', [
                                'name' => 'permissions[]',
                                'value' => 'sales_dashboard',
                                'label' => 'Dashboard Sales',
                                'checked' => in_array('sales_dashboard', $permissions)
                            ])
                            @include('partials.permission-checkbox', [
                                'name' => 'permissions[]',
                                'value' => 'sales_report',
                                'label' => 'Laporan Penjualan',
                                'checked' => in_array('sales_report', $permissions)
                            ])
                        </div>
                    </div>

                    <!-- Material Management -->
                    <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                        <h4 class="font-semibold text-lg mb-4 text-purple-600">
                            <i class="fas fa-pallet mr-2"></i>Material Management
                        </h4>
                        <div class="space-y-2">
                            @include('partials.permission-checkbox', [
                                'name' => 'permissions[]',
                                'value' => 'material_inventory',
                                'label' => 'Manajemen Inventory',
                                'checked' => in_array('material_inventory', $permissions)
                            ])
                            @include('partials.permission-checkbox', [
                                'name' => 'permissions[]',
                                'value' => 'material_orders',
                                'label' => 'Manajemen Purchase Order',
                                'checked' => in_array('material_orders', $permissions)
                            ])
                        </div>
                    </div>

                    <!-- Production Management -->
                    <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                        <h4 class="font-semibold text-lg mb-4 text-red-600">
                            <i class="fas fa-industry mr-2"></i>Production Management
                        </h4>
                        <div class="space-y-2">
                            @include('partials.permission-checkbox', [
                                'name' => 'permissions[]',
                                'value' => 'production_schedule',
                                'label' => 'Jadwal Produksi',
                                'checked' => in_array('production_schedule', $permissions)
                            ])
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="mt-8 flex justify-end">
                    <button type="submit" 
                            class="bg-green-500 hover:bg-green-600 text-white px-6 py-3 rounded-lg font-semibold transition-all flex items-center">
                        <i class="fas fa-save mr-2"></i>Simpan Permissions
                    </button>
                </div>
            </div>
        </form>
        @endif

        @if(session('success'))
        <div class="mt-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg">
            <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
        </div>
        @endif

        @if(session('error'))
        <div class="mt-6 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-lg">
            <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
        </div>
        @endif
    </div>
</div>
@endsection 