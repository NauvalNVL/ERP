@extends('layouts.app')

@section('title', 'Edit Color')

@section('header', 'Edit Color')

@section('content')
<div class="bg-white p-6 rounded-lg shadow-md">
    <div class="flex items-center space-x-2 mb-6">
        <i class="fas fa-fill-drip text-blue-500"></i>
        <h2 class="text-xl font-semibold">Edit Warna: {{ $color->color_id }} - {{ $color->color_name }}</h2>
    </div>

    <form action="{{ route('color.update', $color->color_id) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label for="color_id" class="block mb-2 text-sm font-medium text-gray-900">Color#</label>
                <input type="text" id="color_id" value="{{ $color->color_id }}" class="bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5" readonly>
            </div>
            
            <div>
                <label for="color_name" class="block mb-2 text-sm font-medium text-gray-900">Nama Warna <span class="text-red-500">*</span></label>
                <input type="text" name="color_name" id="color_name" value="{{ $color->color_name }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
            </div>
            
            <div>
                <label for="origin" class="block mb-2 text-sm font-medium text-gray-900">Origin <span class="text-red-500">*</span></label>
                <input type="text" name="origin" id="origin" value="{{ $color->origin }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
            </div>
            
            <div>
                <label for="color_group_id" class="block mb-2 text-sm font-medium text-gray-900">Color Group <span class="text-red-500">*</span></label>
                <select name="color_group_id" id="color_group_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                    @foreach($colorGroups as $cg)
                    <option value="{{ $cg->color_group_id }}" {{ $color->color_group_id == $cg->color_group_id ? 'selected' : '' }}>
                        {{ $cg->color_group_id }} - {{ $cg->color_group_name }}
                    </option>
                    @endforeach
                </select>
            </div>
            
            <div>
                <label for="cg_type" class="block mb-2 text-sm font-medium text-gray-900">CG Type</label>
                <input type="text" name="cg_type" id="cg_type" value="{{ $color->cg_type }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
            </div>
        </div>
        
        <div class="flex items-center justify-between">
            <a href="{{ route('color.index') }}" class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
            </a>
            
            <div class="flex space-x-2">
                <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                    <i class="fas fa-save mr-2"></i>Simpan Perubahan
                </button>
                
                <form action="{{ route('color.destroy', $color->color_id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus warna ini?');" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                        <i class="fas fa-trash mr-2"></i>Hapus
                    </button>
                </form>
            </div>
        </div>
    </form>
</div>
@endsection 