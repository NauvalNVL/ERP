<div id="customerAccountModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50 flex items-center justify-center">
    <!-- Modal content -->
    <div class="relative p-5 border w-11/12 max-w-2xl shadow-lg rounded-md bg-white flex flex-col max-h-[90vh]">

        <!-- Modal header -->
        <div class="flex justify-between items-center pb-3 border-b flex-shrink-0">
            <h3 class="text-lg font-semibold text-gray-900">Customer Account Table</h3>
            <button id="closeCustomerAccountModalBtn" class="text-gray-400 hover:text-gray-600 focus:outline-none">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <!-- Modal body (Scrollable) -->
        <div class="mt-2 overflow-y-auto flex-grow">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50 sticky top-0">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer Code</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">S/person</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">AC Type</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Currency</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody id="customerAccountTableBody" class="bg-white divide-y divide-gray-200">
                        {{-- Data akan dimuat di sini oleh JavaScript --}}
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Modal footer -->
        <div class="flex items-center justify-end p-3 border-t flex-shrink-0">
            <button id="moreOptionsBtn" type="button" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded mr-2">More Options</button>
            <button id="zoomBtn" type="button" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded mr-2">Zoom</button>
            <button id="selectCustomerBtn" type="button" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded mr-2" disabled>Select</button>
            <button id="exitCustomerModalBtn" type="button" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded">Exit</button>
        </div>

    </div>
</div> 