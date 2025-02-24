@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="bg-white rounded-lg shadow-lg p-6">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <div class="flex items-center">
                <h1 class="text-2xl font-bold text-gray-800">Define Customised Program</h1>
            </div>
            <div class="flex space-x-2">
                <button type="submit" form="customProgramForm" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-sm">
                    <i class="fas fa-save mr-2"></i>Save
                </button>
                <button type="reset" form="customProgramForm" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg text-sm">
                    <i class="fas fa-times mr-2"></i>Cancel
                </button>
            </div>
        </div>

        <!-- Form -->
        <form id="customProgramForm" action="{{ route('customised-program.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 gap-6">
                <!-- Program ID -->
                <div class="space-y-2">
                    <label for="program_id" class="block text-sm font-medium text-gray-700">Program ID#</label>
                    <div class="flex space-x-2">
                        <input type="text" id="program_id" name="program_id" 
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('program_id') border-red-500 @enderror"
                               placeholder="Enter Program ID"
                               value="{{ old('program_id') }}">
                        <button type="button" class="bg-gray-200 hover:bg-gray-300 p-2 rounded-lg">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                    @error('program_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Notes -->
                <div class="bg-gray-100 p-4 rounded-lg">
                    <p class="text-sm text-gray-600">
                        <i class="fas fa-info-circle mr-2"></i>
                        User can setup program scripts using Program ID# from 500 501 1100 1150 1200 to 999 998 999 9999 9999
                    </p>
                </div>
            </div>
        </form>

        <!-- Data Table -->
        <div class="mt-8">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Program ID
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Description
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <!-- Table rows will be dynamically populated -->
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                500501
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                Sample Program
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    Active
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <button type="button" class="text-blue-600 hover:text-blue-900 mr-3">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button type="button" class="text-red-600 hover:text-red-900">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Success Message Toast -->
@if(session('success'))
<div id="successToast" class="fixed bottom-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg">
    <div class="flex items-center space-x-2">
        <i class="fas fa-check-circle"></i>
        <span>{{ session('success') }}</span>
    </div>
</div>
@endif

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Success Toast Auto Hide
    const successToast = document.getElementById('successToast');
    if (successToast) {
        setTimeout(() => {
            successToast.style.display = 'none';
        }, 3000);
    }

    // Reset Form
    const resetButton = document.querySelector('button[type="reset"]');
    if (resetButton) {
        resetButton.addEventListener('click', function(e) {
            e.preventDefault();
            document.getElementById('customProgramForm').reset();
        });
    }
});
</script>
@endpush
@endsection