@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 p-6">
    <div class="container mx-auto max-w-7xl">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-indigo-600">
                Define Color Group
            </h1>
            <div class="flex space-x-3">
                <button id="addColorGroupBtn" class="btn-action bg-blue-500 hover:bg-blue-600">
                    <i class="fas fa-plus"></i>
                </button>
            </div>
        </div>

        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <table class="w-full">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">CG</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">CG Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">CG Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($colorGroups as $group)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">{{ $group->cg }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $group->cg_name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $group->cg_type }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex space-x-2">
                                <button class="edit-color-group-btn text-blue-500 hover:text-blue-700" data-id="{{ $group->id }}">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="delete-color-group-btn text-red-500 hover:text-red-700" data-id="{{ $group->id }}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Logika untuk tambah, edit, hapus color group
});
</script>
@endpush
@endsection 