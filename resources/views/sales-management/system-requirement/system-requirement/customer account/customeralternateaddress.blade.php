@extends('layouts.app')

@section('title', 'Define Customer Alternate Address')

@section('content')
<!-- Header Section -->
<div class="bg-gradient-to-r from-purple-700 to-indigo-600 p-6 rounded-t-lg shadow-lg mb-0">
    <h2 class="text-2xl font-bold text-white mb-2 flex items-center">
        <i class="fas fa-address-book mr-3"></i> Define Customer Alternate Address
    </h2>
    <p class="text-purple-100">Kelola alamat alternatif untuk customer</p>
</div>

<div class="bg-white rounded-b-lg shadow-lg p-6 mb-6">
    <!-- Form Section -->
    <div class="bg-white p-6 rounded-lg shadow-md border-t-4 border-indigo-500 mb-6">
        <div class="flex items-center mb-6 pb-2 border-b border-gray-200">
            <div class="p-2 bg-indigo-500 rounded-lg mr-3">
                <i class="fas fa-edit text-white"></i>
            </div>
            <h3 class="text-xl font-semibold text-gray-800">Customer Alternate Address Form</h3>
        </div>

        <!-- Header with navigation buttons - Adjust icons/colors as needed -->
        <div class="flex items-center space-x-2 mb-6">
            <button type="button" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded flex items-center space-x-2 transform active:translate-y-px" onclick="window.location.href='{{ route('dashboard') }}'">
                <i class="fas fa-power-off"></i>
            </button>
            {{-- Example Navigation/Action Buttons --}}
            <button type="button" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded flex items-center space-x-2 transform active:translate-y-px">
                <i class="fas fa-arrow-right"></i>
            </button>
            <button type="button" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded flex items-center space-x-2 transform active:translate-y-px">
                <i class="fas fa-arrow-left"></i>
            </button>
            <button type="button" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded flex items-center space-x-2 transform active:translate-y-px">
                <i class="fas fa-search"></i>
            </button>
            <button type="button" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded flex items-center space-x-2 transform active:translate-y-px">
                <i class="fas fa-save"></i>
            </button>
        </div>

        <form method="POST" action="{{-- Add your store route here --}}" class="space-y-6">
            @csrf
            <!-- Add form fields here based on your Customer Alternate Address table structure -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="customer_code" class="block text-sm font-medium text-gray-700 mb-1">Customer Code</label>
                     <div class="relative flex">
                         <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500">
                             <i class="fas fa-id-card"></i>
                         </span>
                         <input type="text" name="customer_code" id="customer_code" required maxlength="20"
                             class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none border border-gray-300 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                         <button type="button" class="inline-flex items-center px-3 py-2 border border-l-0 border-gray-300 bg-blue-500 hover:bg-blue-600 text-white rounded-r-md transition-colors transform active:translate-y-px">
                             <i class="fas fa-table"></i> {{-- Assuming this opens a customer selection modal --}}
                         </button>
                     </div>
                </div>
                <div>
                    <label for="alternate_address_code" class="block text-sm font-medium text-gray-700 mb-1">Alternate Address Code</label>
                    <input type="text" name="alternate_address_code" id="alternate_address_code" required maxlength="20"
                        class="block w-full px-3 py-2 rounded-md border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                </div>
            </div>

             <div>
                 <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                 <textarea name="address" id="address" rows="3"
                     class="block w-full px-3 py-2 rounded-md border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"></textarea>
             </div>

            <!-- Add more fields like City, State, Country, Postal Code as needed -->

            <div class="flex justify-end">
                <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                    Save Alternate Address
                </button>
            </div>
        </form>
    </div>

    {{-- Removed Table Section --}}
</div>
@endsection
