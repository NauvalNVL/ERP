@extends('layouts.app')

@section('title', 'Define Sales Team')

@section('header', 'Define Sales Team')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="bg-white rounded-lg shadow-md p-6">
        <h1 class="text-2xl font-bold mb-6">Define Sales Team</h1>
        
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('sales-team.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 gap-6 mb-6">
                <div class="flex items-center">
                    <label for="code" class="w-1/4 text-gray-700">Sales Team Code:</label>
                    <input type="text" id="code" name="code" value="{{ old('code') }}" 
                           class="w-1/4 border border-gray-300 rounded px-3 py-2 focus:outline-none focus:border-blue-500 @error('code') border-red-500 @enderror">
                    
                    <button type="button" id="openModalBtn" class="ml-2 bg-blue-500 hover:bg-blue-600 text-white px-2 py-1 rounded">
                        <i class="fas fa-search"></i>
                    </button>
                    
                    @error('code')
                        <span class="text-red-500 ml-2 text-sm">{{ $message }}</span>
                    @enderror
                </div>
                
                <div class="flex items-center">
                    <label for="name" class="w-1/4 text-gray-700">Sales Team Name:</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" 
                           class="w-3/4 border border-gray-300 rounded px-3 py-2 focus:outline-none focus:border-blue-500 @error('name') border-red-500 @enderror">
                    
                    @error('name')
                        <span class="text-red-500 ml-2 text-sm">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            
            <div class="flex justify-end space-x-4">
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
                    Record
                </button>
                <button type="button" id="reviewBtn" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">
                    Review
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Box -->
<div id="salesTeamModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden flex items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-lg p-6 w-2/3 max-w-2xl">
        <div class="mb-4">
            <h2 class="text-xl font-semibold">Sales Team Table</h2>
        </div>
        
        <div class="overflow-auto max-h-96">
            <table class="min-w-full border border-gray-300">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="py-2 px-4 border-b border-r border-gray-300 text-left w-1/4">Code</th>
                        <th class="py-2 px-4 border-b border-gray-300 text-left w-3/4">Name</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($salesTeams as $team)
                    <tr class="hover:bg-blue-100 cursor-pointer sales-team-row" data-code="{{ $team->code }}" data-name="{{ $team->name }}">
                        <td class="py-2 px-4 border-b border-r border-gray-300">{{ $team->code }}</td>
                        <td class="py-2 px-4 border-b border-gray-300">{{ $team->name }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <div class="mt-6 flex justify-center space-x-4">
            <button id="selectBtn" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
                Select
            </button>
            <button id="exitBtn" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">
                Exit
            </button>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('salesTeamModal');
        const openModalBtn = document.getElementById('openModalBtn');
        const reviewBtn = document.getElementById('reviewBtn');
        const exitBtn = document.getElementById('exitBtn');
        const selectBtn = document.getElementById('selectBtn');
        const salesTeamRows = document.querySelectorAll('.sales-team-row');
        
        let selectedRow = null;
        
        // Open modal function
        function openModal() {
            modal.classList.remove('hidden');
        }
        
        // Close modal function
        function closeModal() {
            modal.classList.add('hidden');
            selectedRow = null;
            // Remove selected class from all rows
            salesTeamRows.forEach(row => {
                row.classList.remove('bg-blue-200');
            });
        }
        
        // Open modal when search button is clicked
        if (openModalBtn) {
            openModalBtn.addEventListener('click', openModal);
        }
        
        // Open modal when review button is clicked
        if (reviewBtn) {
            reviewBtn.addEventListener('click', openModal);
        }
        
        // Close modal when exit button is clicked
        if (exitBtn) {
            exitBtn.addEventListener('click', closeModal);
        }
        
        // Handle row selection
        salesTeamRows.forEach(row => {
            row.addEventListener('click', function() {
                // Remove selected class from all rows
                salesTeamRows.forEach(r => {
                    r.classList.remove('bg-blue-200');
                });
                
                // Add selected class to clicked row
                this.classList.add('bg-blue-200');
                selectedRow = this;
            });
            
            // Double click to select and close
            row.addEventListener('dblclick', function() {
                const code = this.getAttribute('data-code');
                const name = this.getAttribute('data-name');
                
                document.getElementById('code').value = code;
                document.getElementById('name').value = name;
                
                closeModal();
            });
        });
        
        // Handle select button click
        if (selectBtn) {
            selectBtn.addEventListener('click', function() {
                if (selectedRow) {
                    const code = selectedRow.getAttribute('data-code');
                    const name = selectedRow.getAttribute('data-name');
                    
                    document.getElementById('code').value = code;
                    document.getElementById('name').value = name;
                    
                    closeModal();
                }
            });
        }
        
        // Close modal when clicking outside
        window.addEventListener('click', function(event) {
            if (event.target === modal) {
                closeModal();
            }
        });
    });
</script>
@endsection
