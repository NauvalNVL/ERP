@extends('layouts.app')

@section('title', 'Define Program Printer')

@section('content')
<div class="min-h-screen bg-gray-100">
    <!-- Header/Toolbar dengan desain modern -->
    <div class="bg-white shadow-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between py-4">
                <h1 class="text-2xl font-bold text-gray-900">Define Program Printer</h1>
                <div class="flex items-center space-x-3">
                    <button class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md transition-colors duration-200" title="New [F6]">
                        <i class="fas fa-plus text-sm mr-2"></i>
                        New
                    </button>
                    <button class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-md transition-colors duration-200" title="Edit [F7]">
                        <i class="fas fa-edit text-sm mr-2"></i>
                        Edit
                    </button>
                    <button class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-md transition-colors duration-200" title="Delete [F8]">
                        <i class="fas fa-trash text-sm mr-2"></i>
                        Delete
                    </button>
                    <button class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-md transition-colors duration-200" title="Print [F10]">
                        <i class="fas fa-print text-sm mr-2"></i>
                        Print
                    </button>
                    <button class="inline-flex items-center px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-md transition-colors duration-200" title="Exit">
                        <i class="fas fa-times text-sm mr-2"></i>
                        Exit
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content dengan layout yang lebih modern -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Description Column -->
            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-900">Description</h2>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div class="menu-item hover:bg-gray-50 p-2 rounded-lg transition-colors duration-200">
                            <div class="font-semibold text-gray-900">System Manager</div>
                        </div>
                        <div class="space-y-2 ml-6">
                            @php
                                $menuItems = [
                                    'Sales Management',
                                    'Cost Management',
                                    'Material Management',
                                    'Production Management',
                                    'Plant Maintenance Management',
                                    'Warehouse Management',
                                    'Financial Management',
                                    'Plug-in Management',
                                    'Executive Information',
                                    'Data Mining',
                                    'Customised Module'
                                ];
                            @endphp

                            @foreach($menuItems as $item)
                            <div class="menu-item hover:bg-gray-50 p-2 rounded-lg transition-colors duration-200">
                                <div class="text-gray-700 hover:text-blue-600 cursor-pointer">{{ $item }}</div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Printer Code Column -->
            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-900">Printer Code</h2>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div class="menu-item">
                            <input type="text" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200" placeholder="Enter printer code..." />
                        </div>
                        <div class="space-y-3">
                            @for ($i = 0; $i < 11; $i++)
                            <div class="menu-item">
                                <input type="text" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200" placeholder="Enter printer code..." />
                            </div>
                            @endfor
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('keydown', function(e) {
        if (e.key === 'F6') {
            e.preventDefault();
            // Handle New
        } else if (e.key === 'F7') {
            e.preventDefault();
            // Handle Edit
        } else if (e.key === 'F8') {
            e.preventDefault();
            // Handle Delete
        } else if (e.key === 'F10') {
            e.preventDefault();
            // Handle Print
        }
    });
</script>
@endpush
@endsection