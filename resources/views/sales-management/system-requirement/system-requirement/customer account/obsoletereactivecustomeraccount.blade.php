@extends('layouts.app')

@section('title', 'Obsolete/Reactive Customer Account')

@section('content')
<!-- Header Section -->
<div class="bg-gradient-to-r from-cyan-700 to-blue-600 p-6 rounded-t-lg shadow-lg mb-0">
    <h2 class="text-2xl font-bold text-white mb-2 flex items-center">
        <i class="fas fa-user-slash mr-3"></i> Obsolete/Reactive Customer Account
    </h2>
    <p class="text-cyan-100">Kelola status obsolete atau reactive untuk akun customer</p>
</div>

<div class="bg-white rounded-b-lg shadow-lg p-6 mb-6">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Left Column - Main Content -->
        <div class="lg:col-span-2">
            <div class="bg-white p-6 rounded-lg shadow-md border-t-4 border-blue-500">
                <div class="flex items-center mb-6 pb-2 border-b border-gray-200">
                    <div class="p-2 bg-blue-500 rounded-lg mr-3">
                        <i class="fas fa-exchange-alt text-white"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800">Customer Account Status Management</h3>
                </div>

                <!-- Header with navigation buttons -->
                <div class="flex items-center space-x-2 mb-6">
                    <button type="button" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded flex items-center space-x-2 transform active:translate-y-px" onclick="window.location.href='{{ route('dashboard') }}'">
                        <i class="fas fa-power-off"></i>
                    </button>
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

                <!-- Form Section -->
                <form method="POST" class="space-y-6">
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
                                <button type="button" class="inline-flex items-center px-3 py-2 border border-l-0 border-gray-300 bg-blue-500 hover:bg-blue-600 text-white rounded-r-md transition-colors transform active:translate-y-px">
                                    <i class="fas fa-table"></i> {{-- Assuming this opens a customer selection modal --}}
                                </button>
                            </div>
                        </div>
                        <div>
                            <label for="customer_name" class="block text-sm font-medium text-gray-700 mb-1">Customer Name</label>
                            <input type="text" name="customer_name" id="customer_name" maxlength="100" disabled
                                class="block w-full px-3 py-2 rounded-md border border-gray-300 bg-gray-100 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="current_status" class="block text-sm font-medium text-gray-700 mb-1">Current Status</label>
                            <input type="text" name="current_status" id="current_status" disabled
                                class="block w-full px-3 py-2 rounded-md border border-gray-300 bg-gray-100 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        </div>
                        <div class="flex items-center space-x-4">
                            <label class="block text-sm font-medium text-gray-700">New Status:</label>
                            <div class="flex items-center">
                                <input type="radio" name="new_status" id="status_obsolete" value="Obsolete" class="focus:ring-red-500 h-4 w-4 text-red-600 border-gray-300">
                                <label for="status_obsolete" class="ml-2 block text-sm text-gray-900">Obsolete</label>
                            </div>
                            <div class="flex items-center">
                                <input type="radio" name="new_status" id="status_reactive" value="Reactive" class="focus:ring-green-500 h-4 w-4 text-green-600 border-gray-300">
                                <label for="status_reactive" class="ml-2 block text-sm text-gray-900">Reactive</label>
                            </div>
                        </div>
                    </div>

                     <div>
                         <label for="reason" class="block text-sm font-medium text-gray-700 mb-1">Reason</label>
                         <textarea name="reason" id="reason" rows="3"
                             class="block w-full px-3 py-2 rounded-md border border-gray-300 focus:ring-blue-500 focus:border-blue-500 transition-colors"></textarea>
                     </div>

                    <div class="flex justify-end">
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                            Update Status
                        </button>
                    </div>
                </form>

                <!-- Table Section (for listing obsolete/reactive customers) -->
                <div class="mt-8">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-200">Obsolete/Reactive Customers List</h3>
                    <div class="overflow-x-auto rounded-lg border border-gray-200">
                        <table class="min-w-full divide-y divide-gray-200 text-xs" id="obsoleteReactiveCustomerTable">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer Code</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer Name</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Last Status Change</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reason</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                {{-- Customer data will be loaded here --}}
                                @if(isset($customers) && count($customers) > 0)
                                    @foreach($customers as $customer)
                                        <tr>
                                            <td class="px-4 py-3 whitespace-nowrap font-medium text-gray-900">{{ $customer->customer_code }}</td>
                                            <td class="px-4 py-3 whitespace-nowrap text-gray-700">{{ $customer->customer_name }}</td>
                                            <td class="px-4 py-3 whitespace-nowrap text-gray-700">{{ $customer->status }}</td>
                                            <td class="px-4 py-3 whitespace-nowrap text-gray-700">{{ $customer->last_status_change ?? 'N/A' }}</td>
                                            <td class="px-4 py-3 whitespace-nowrap text-gray-700">{{ $customer->reason ?? 'N/A' }}</td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="5" class="px-4 py-4 text-center text-gray-500">No obsolete or reactive customer accounts found.</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>

        <!-- Right Column - Info/Help -->
        <div class="lg:col-span-1">
            <div class="bg-white p-6 rounded-lg shadow-md border-t-4 border-teal-500 mb-6">
                <div class="flex items-center mb-4 pb-2 border-b border-gray-200">
                    <div class="p-2 bg-teal-500 rounded-lg mr-3">
                        <i class="fas fa-info-circle text-white"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800">Info Obsolete/Reactive</h3>
                </div>
                <div class="p-4 bg-teal-50 rounded-lg">
                    <h4 class="text-sm font-semibold text-teal-800 uppercase tracking-wider mb-2">Petunjuk</h4>
                    <ul class="list-disc pl-5 text-sm text-gray-600 space-y-1">
                        <li>Pilih customer account menggunakan tombol <span class="font-medium">search</span>.</li>
                        <li>Lihat status customer saat ini.</li>
                        <li>Pilih status baru (Obsolete atau Reactive).</li>
                        <li>Masukkan alasan perubahan status (opsional).</li>
                        <li>Klik <span class="font-medium">Update Status</span> untuk menyimpan perubahan.</li>
                        <li>Tabel di bawah menampilkan customer accounts dengan status Obsolete atau Reactive.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 