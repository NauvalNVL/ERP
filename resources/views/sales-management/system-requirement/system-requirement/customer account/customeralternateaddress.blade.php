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

        <!-- Header with navigation buttons -->
        <div class="flex items-center space-x-2 mb-6">
            <button type="button" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded flex items-center space-x-2 transform active:translate-y-px" onclick="window.location.href='{{ route('dashboard') }}'">
                <i class="fas fa-power-off"></i>
            </button>
            <button type="button" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded flex items-center space-x-2 transform active:translate-y-px" title="Next">
                <i class="fas fa-arrow-right"></i>
            </button>
            <button type="button" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded flex items-center space-x-2 transform active:translate-y-px" title="Previous">
                <i class="fas fa-arrow-left"></i>
            </button>
            <button type="button" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded flex items-center space-x-2 transform active:translate-y-px" title="Search">
                <i class="fas fa-search"></i>
            </button>
            <button type="button" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded flex items-center space-x-2 transform active:translate-y-px" title="Save">
                <i class="fas fa-save"></i>
            </button>
            <button type="button" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded flex items-center space-x-2 transform active:translate-y-px" title="Refresh">
                <i class="fas fa-sync-alt"></i>
            </button>
             <button type="button" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded ml-auto">Record: Review</button>
        </div>

        <form method="POST" action="{{-- Add your store route here --}}" class="space-y-6">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="customer_code" class="block text-sm font-medium text-gray-700 mb-1">Customer Code</label>
                     <div class="relative flex">
                         <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500">
                             <i class="fas fa-id-card"></i>
                         </span>
                         <input type="text" name="customer_code" id="customer_code" required maxlength="20"
                             class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none border border-gray-300 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                         <button type="button" id="openCustomerAccountModalBtn" class="inline-flex items-center px-3 py-2 border border-l-0 border-gray-300 bg-blue-500 hover:bg-blue-600 text-white rounded-r-md transition-colors transform active:translate-y-px">
                             <i class="fas fa-table"></i>
                         </button>
                     </div>
                </div>
                 <div>
                     <label for="delivery_code" class="block text-sm font-medium text-gray-700 mb-1">Delivery Code</label>
                     <input type="text" name="delivery_code" id="delivery_code" maxlength="20"
                         class="block w-full px-3 py-2 rounded-md border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                 </div>
                <div>
                    <label for="country" class="block text-sm font-medium text-gray-700 mb-1">Country</label>
                    <input type="text" name="country" id="country" maxlength="50"
                        class="block w-full px-3 py-2 rounded-md border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                </div>
                 <div>
                     <label for="town" class="block text-sm font-medium text-gray-700 mb-1">Town</label>
                     <input type="text" name="town" id="town" maxlength="50"
                         class="block w-full px-3 py-2 rounded-md border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                 </div>
                <div>
                    <label for="state" class="block text-sm font-medium text-gray-700 mb-1">State</label>
                    <input type="text" name="state" id="state" maxlength="50"
                        class="block w-full px-3 py-2 rounded-md border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                </div>
                 <div>
                     <label for="town_section" class="block text-sm font-medium text-gray-700 mb-1">Town Section</label>
                     <input type="text" name="town_section" id="town_section" maxlength="50"
                         class="block w-full px-3 py-2 rounded-md border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                 </div>
            </div>

            <!-- Bill To Section -->
             <fieldset class="border rounded-md p-4 mt-4">
                 <legend class="text-base font-semibold text-gray-900">Bill To</legend>
                 <div class="grid grid-cols-1 gap-6 mt-4">
                     <div>
                         <label for="bill_to_name" class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                         <input type="text" name="bill_to_name" id="bill_to_name" maxlength="100"
                             class="block w-full px-3 py-2 rounded-md border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                     </div>
                     <div>
                         <label for="bill_to_address" class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                         <textarea name="bill_to_address" id="bill_to_address" rows="3"
                             class="block w-full px-3 py-2 rounded-md border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"></textarea>
                     </div>
                 </div>
             </fieldset>

            <!-- Ship To Section -->
             <fieldset class="border rounded-md p-4 mt-6">
                 <legend class="text-base font-semibold text-gray-900">Ship To</legend>
                 <div class="grid grid-cols-1 gap-6 mt-4">
                     <div>
                         <label for="ship_to_name" class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                         <input type="text" name="ship_to_name" id="ship_to_name" maxlength="100"
                             class="block w-full px-3 py-2 rounded-md border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                     </div>
                     <div>
                         <label for="ship_to_address" class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                         <textarea name="ship_to_address" id="ship_to_address" rows="3"
                             class="block w-full px-3 py-2 rounded-md border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"></textarea>
                     </div>
                     <div>
                         <label for="contact_person" class="block text-sm font-medium text-gray-700 mb-1">Contact Person</label>
                         <input type="text" name="contact_person" id="contact_person" maxlength="100"
                             class="block w-full px-3 py-2 rounded-md border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                     </div>
                      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                          <div>
                              <label for="tel_no" class="block text-sm font-medium text-gray-700 mb-1">Tel No</label>
                              <input type="text" name="tel_no" id="tel_no" maxlength="50"
                                  class="block w-full px-3 py-2 rounded-md border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                          </div>
                          <div>
                              <label for="fax_no" class="block text-sm font-medium text-gray-700 mb-1">Fax No</label>
                              <input type="text" name="fax_no" id="fax_no" maxlength="50"
                                  class="block w-full px-3 py-2 rounded-md border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                          </div>
                      </div>
                     <div>
                         <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                         <input type="email" name="email" id="email" maxlength="100"
                             class="block w-full px-3 py-2 rounded-md border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                     </div>
                 </div>
             </fieldset>

            <div class="flex justify-end mt-6">
                <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                    Save Alternate Address
                </button>
            </div>
        </form>
    </div>

    {{-- Customer Account Modal --}}
    @include('sales-management.system-requirement.system-requirement.customer account.partials.customer-account-modal')

</div>
@endsection

@push('scripts')
<script src="{{ asset('js/customer-alternate-address.js') }}"></script>
@endpush
