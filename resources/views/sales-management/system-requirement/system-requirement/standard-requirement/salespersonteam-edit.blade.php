@extends('layouts.app')

@section('title', 'Edit Salesperson Team')

@section('header', 'Edit Salesperson Team')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="p-4 bg-gray-50 border-b border-gray-200">
            <h2 class="text-lg font-semibold">Edit Salesperson Team</h2>
        </div>
        
        <form action="{{ route('salesperson-team.update', $salesperson->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="p-4">
                <div class="mb-4">
                    <label for="code" class="block text-sm font-medium text-gray-700 mb-1">Salesperson Code</label>
                    <input type="text" id="code" value="{{ $salesperson->code }}" class="w-full border border-gray-300 rounded px-3 py-2 bg-gray-100 focus:outline-none" readonly>
                </div>
                
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Salesperson Name</label>
                    <input type="text" name="salesperson_name" id="name" value="{{ $salesperson->name }}" required class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <div class="mb-4">
                    <label for="sales_team_id" class="block text-sm font-medium text-gray-700 mb-1">Sales Team</label>
                    <select name="sales_team_id" id="sales_team_id" required class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Pilih Sales Team</option>
                        @foreach ($salesTeams as $team)
                            <option value="{{ $team->id }}" {{ $salesperson->sales_team_id == $team->id ? 'selected' : '' }}>
                                {{ $team->code }} - {{ $team->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="mb-4">
                    <label for="position" class="block text-sm font-medium text-gray-700 mb-1">Position</label>
                    <select name="position" id="position" required class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Pilih Position</option>
                        <option value="E - Executive" {{ $salesperson->position == 'E - Executive' ? 'selected' : '' }}>E - Executive</option>
                        <option value="M - Manager" {{ $salesperson->position == 'M - Manager' ? 'selected' : '' }}>M - Manager</option>
                        <option value="T - Team Leader" {{ $salesperson->position == 'T - Team Leader' ? 'selected' : '' }}>T - Team Leader</option>
                    </select>
                </div>
            </div>
            
            <div class="p-4 bg-gray-50 border-t border-gray-200 flex justify-end space-x-3">
                <a href="{{ route('salesperson-team.index') }}" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded-md text-sm">Batal</a>
                <button type="submit" class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-md text-sm">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
@endsection 
