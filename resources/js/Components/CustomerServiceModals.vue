<template>
    <!-- Initial Sales Order Search Modal (Image 1) -->
    <div v-if="showInitialSalesOrderModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black bg-opacity-70 backdrop-blur-sm transition-opacity duration-300 ease-in-out">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-lg overflow-hidden transform transition-all duration-300 ease-in-out scale-95 opacity-0" :class="{'scale-100 opacity-100': showInitialSalesOrderModal}">
            <div class="flex items-center justify-between p-4 border-b border-gray-200 bg-gradient-to-r from-blue-600 to-blue-700 text-white">
                <h3 class="text-xl font-semibold flex items-center">
                    <i class="fas fa-search mr-2"></i> Search by Sales Order
                </h3>
                <button type="button" @click="closeAllModals" class="text-white hover:text-gray-200">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div class="p-6 space-y-6">
                <label class="block text-sm font-medium text-gray-700 mb-1">Sales Order#:</label>
                <div class="relative flex items-center rounded-md shadow-sm">
                    <input type="text" v-model="salesOrderParts.part1" class="flex-1 block w-full px-3 py-2 border border-gray-300 rounded-l-md focus:ring-blue-500 focus:border-blue-500">
                    <span class="inline-flex items-center px-2 py-2 border-t border-b border-gray-300 bg-gray-50 text-gray-500">
                        -
                    </span>
                    <input type="text" v-model="salesOrderParts.part2" class="flex-1 block w-full px-3 py-2 border-t border-b border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                    <span class="inline-flex items-center px-2 py-2 border-t border-b border-gray-300 bg-gray-50 text-gray-500">
                        -
                    </span>
                    <input type="text" v-model="salesOrderParts.part3" class="flex-1 block w-full px-3 py-2 border-t border-b border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                    <span class="inline-flex items-center px-2 py-2 border-t border-b border-gray-300 bg-gray-50 text-gray-500">
                        -
                    </span>
                    <input type="text" v-model="salesOrderParts.part4" class="flex-1 block w-full px-3 py-2 border border-gray-300 focus:ring-blue-500 focus:border-blue-500 rounded-r-md">
                    <button type="button" @click="openOptionsModal" class="ml-2 px-3 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <i class="fas fa-table"></i>
                    </button>
                </div>
            </div>
            <div class="p-4 bg-gray-50 border-t border-gray-200 flex justify-end space-x-3">
                <button @click="performSearch" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors shadow">
                    OK
                </button>
                <button @click="retrySearch" class="px-6 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition-colors shadow">
                    Retry
                </button>
                <button @click="closeAllModals" class="px-6 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition-colors shadow">
                    Exit
                </button>
            </div>
        </div>
    </div>

    <!-- Options Modal (appears after clicking table icon in initial modal) -->
    <div v-if="showOptionsModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black bg-opacity-70 backdrop-blur-sm transition-opacity duration-300 ease-in-out">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-lg overflow-hidden transform transition-all duration-300 ease-in-out scale-95 opacity-0" :class="{'scale-100 opacity-100': showOptionsModal}">
            <div class="flex items-center justify-between p-4 border-b border-gray-200 bg-gradient-to-r from-blue-600 to-blue-700 text-white">
                <h3 class="text-xl font-semibold flex items-center">
                    <i class="fas fa-list-alt mr-2"></i> Select Search Option
                </h3>
                <button type="button" @click="closeAllModals" class="text-white hover:text-gray-200">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div class="p-6 space-y-6">
                <label class="block text-gray-700 font-medium mb-3">Options:</label>
                <div class="space-y-2">
                    <label class="flex items-center text-gray-800 cursor-pointer">
                        <input type="radio" v-model="selectedSearchOption" value="salesOrder" class="form-radio h-5 w-5 text-blue-600 focus:ring-blue-500">
                        <span class="ml-3">Sales Order#</span>
                    </label>
                    <label class="flex items-center text-gray-800 cursor-pointer">
                        <input type="radio" v-model="selectedSearchOption" value="masterCard" class="form-radio h-5 w-5 text-blue-600 focus:ring-blue-500">
                        <span class="ml-3">Master Card#</span>
                    </label>
                    <label class="flex items-center text-gray-800 cursor-pointer">
                        <input type="radio" v-model="selectedSearchOption" value="purchaseOrderRef" class="form-radio h-5 w-5 text-blue-600 focus:ring-blue-500">
                        <span class="ml-3">P/Order Ref#</span>
                    </label>
                </div>
            </div>
            <div class="p-4 bg-gray-50 border-t border-gray-200 flex justify-end space-x-3">
                <button @click="handleOptionSelection" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors shadow">
                    OK
                </button>
                <button @click="closeAllModals" class="px-6 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition-colors shadow">
                    Exit
                </button>
            </div>
        </div>
    </div>

    <!-- Sales Order Search Modal (Image 2 - Table with details) -->
    <div v-if="showSalesOrderSearchModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black bg-opacity-70 backdrop-blur-sm transition-opacity duration-300 ease-in-out">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-4xl overflow-hidden transform transition-all duration-300 ease-in-out scale-95 opacity-0" :class="{'scale-100 opacity-100': showSalesOrderSearchModal}">
            <div class="flex items-center justify-between p-4 border-b border-gray-200 bg-gradient-to-r from-blue-600 to-blue-700 text-white">
                <h3 class="text-xl font-semibold flex items-center">
                    <i class="fas fa-search mr-2"></i> Sales Order Table (Sorted by S/Order#)
                </h3>
                <button type="button" @click="closeAllModals" class="text-white hover:text-gray-200">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div class="p-6 space-y-6 overflow-y-auto max-h-[70vh]">
                <!-- Sales Order Table -->
                <div class="border border-gray-300 rounded-lg overflow-hidden mb-6">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">SO#</th>
                                <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">CUSTOMER PO#</th>
                                <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">AC#</th>
                                <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">MCS#</th>
                                <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">STATUS</th>
                                <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">D/LOCATION</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <!-- Sample Data Rows -->
                            <tr class="hover:bg-blue-50 cursor-pointer">
                                <td class="px-3 py-2 whitespace-nowrap text-sm font-medium text-gray-900">06-2025-00595</td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900">03-2025-00568</td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900">000117.05</td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900">0069828</td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900">Outs</td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900">B105</td>
                            </tr>
                            <tr class="hover:bg-blue-50 cursor-pointer">
                                <td class="px-3 py-2 whitespace-nowrap text-sm font-medium text-gray-900">06-2025-00584</td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900">PO.2025.06.00004</td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900">000576</td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900">263207</td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900">Outs</td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900">B102</td>
                            </tr>
                            <tr class="hover:bg-blue-50 cursor-pointer">
                                <td class="px-3 py-2 whitespace-nowrap text-sm font-medium text-gray-900">06-2025-00583</td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900">PO 271-0625 05 JUNI 2025</td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900">000603-01</td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900">2657103</td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900">J001</td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900">J001</td>
                            </tr>
                            <tr class="hover:bg-blue-50 cursor-pointer">
                                <td class="px-3 py-2 whitespace-nowrap text-sm font-medium text-gray-900">06-2025-00582</td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900">PO 270-0625 05 JUNI 2025</td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900">000603-01</td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900">265744</td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900">Outs</td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900">J001</td>
                            </tr>
                            <tr class="hover:bg-blue-50 cursor-pointer">
                                <td class="px-3 py-2 whitespace-nowrap text-sm font-medium text-gray-900">06-2025-00581</td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900">PO 269-0625 04 JUNI 2025</td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900">000603-01</td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900">2657106</td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900">Outs</td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900">J001</td>
                            </tr>
                            <tr class="hover:bg-blue-50 cursor-pointer">
                                <td class="px-3 py-2 whitespace-nowrap text-sm font-medium text-gray-900">06-2025-00580</td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900">ZAENAL ROHMAN</td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900">000581-02</td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900">2470180</td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900">Outs</td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900">B103</td>
                            </tr>
                            <tr class="hover:bg-blue-50 cursor-pointer">
                                <td class="px-3 py-2 whitespace-nowrap text-sm font-medium text-gray-900">06-2025-00579</td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900">P005-SMCP2506</td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900">000947</td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900">297412</td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900">Outs</td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900">B103</td>
                            </tr>
                            <tr class="hover:bg-blue-50 cursor-pointer">
                                <td class="px-3 py-2 whitespace-nowrap text-sm font-medium text-gray-900">06-2025-00578</td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900">P005-SMCP2506</td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900">000947</td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900">297426</td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900">Outs</td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900">B103</td>
                            </tr>
                            <tr class="hover:bg-blue-50 cursor-pointer">
                                <td class="px-3 py-2 whitespace-nowrap text-sm font-medium text-gray-900">06-2025-00577</td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900">4280000972</td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900">000235</td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900">2064353</td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900">J204</td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900">J204</td>
                            </tr>
                            <tr class="hover:bg-blue-50 cursor-pointer">
                                <td class="px-3 py-2 whitespace-nowrap text-sm font-medium text-gray-900">06-2025-00576</td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900">4000054614</td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900">000176</td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900">OL-0000267</td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900">Outs</td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900">B105</td>
                            </tr>
                            <!-- Add more rows as needed -->
                        </tbody>
                    </table>
                </div>

                <!-- Search and Order details -->
                <div class="mb-6 border p-4 rounded-lg bg-gray-50">
                    <div class="flex items-center space-x-2 mb-4">
                        <label class="block text-sm font-medium text-gray-700">Search:</label>
                        <input type="text" class="form-input w-16 px-2 py-1 border border-gray-300 rounded-md text-sm">
                        <input type="text" class="form-input w-16 px-2 py-1 border border-gray-300 rounded-md text-sm">
                        <input type="text" class="form-input w-16 px-2 py-1 border border-gray-300 rounded-md text-sm">
                        <button class="px-4 py-1 bg-blue-500 text-white rounded-md text-sm hover:bg-blue-600">Search</button>
                        <span class="text-sm font-medium text-gray-700">S/Order#</span>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Customer Name:</label>
                            <input type="text" class="form-input mt-1 block w-full bg-gray-100" value="KARYA INDAH MULTIGUNA, PT" readonly>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Model:</label>
                            <input type="text" class="form-input mt-1 block w-full bg-gray-100" value="PEA.PIAYZ.020_POP ICE UNGU" readonly>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Order Mode:</label>
                            <input type="text" class="form-input mt-1 block w-full bg-gray-100" value="0-Order by Customer + Deliver & Invoice to Customer" readonly>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Salesperson:</label>
                            <div class="flex items-center">
                                <input type="text" class="form-input mt-1 block w-20 bg-gray-100" value="S100" readonly>
                                <input type="text" class="form-input mt-1 ml-2 block w-full bg-gray-100" value="IN HOUSE" readonly>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Order Group:</label>
                            <div class="flex items-center">
                                <input type="text" class="form-input mt-1 block w-20 bg-gray-100" value="Sales" readonly>
                                <label class="ml-4 text-sm font-medium text-gray-700">Order Type:</label>
                                <input type="text" class="form-input mt-1 ml-2 block w-20 bg-gray-100" value="S1" readonly>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Item Details Table -->
                <div class="border border-gray-300 rounded-lg overflow-hidden">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ITEM</th>
                                <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">PD</th>
                                <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">MAIN</th>
                                <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">F1</th>
                                <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">F2</th>
                                <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">F3</th>
                                <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">F4</th>
                                <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">F5</th>
                                <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">F6</th>
                                <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">F7</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr>
                                <td class="px-3 py-2 whitespace-nowrap text-sm font-medium text-gray-900">PD</td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900"></td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900">B1</td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900"></td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900"></td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900"></td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900"></td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900"></td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900"></td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900"></td>
                            </tr>
                            <tr>
                                <td class="px-3 py-2 whitespace-nowrap text-sm font-medium text-gray-900">PCS</td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900"></td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900">1</td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900"></td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900"></td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900"></td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900"></td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900"></td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900"></td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900"></td>
                            </tr>
                            <tr>
                                <td class="px-3 py-2 whitespace-nowrap text-sm font-medium text-gray-900">UNIT</td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900"></td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900">Pcs</td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900"></td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900"></td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900"></td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900"></td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900"></td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900"></td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900"></td>
                            </tr>
                            <tr>
                                <td class="px-3 py-2 whitespace-nowrap text-sm font-medium text-gray-900">ORDER</td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900"></td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900">2,000</td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900"></td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900"></td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900"></td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900"></td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900"></td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900"></td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900"></td>
                            </tr>
                            <tr>
                                <td class="px-3 py-2 whitespace-nowrap text-sm font-medium text-gray-900">NET DELIVERY</td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900"></td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900">2,000</td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900"></td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900"></td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900"></td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900"></td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900"></td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900"></td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900"></td>
                            </tr>
                            <tr>
                                <td class="px-3 py-2 whitespace-nowrap text-sm font-medium text-gray-900">BALANCE</td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900"></td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900">2,000</td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900"></td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900"></td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900"></td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900"></td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900"></td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900"></td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="p-4 bg-gray-50 border-t border-gray-200 flex justify-end space-x-3">
                <button @click="performSearch" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors shadow">
                    Zoom
                </button>
                <button @click="performSearch" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors shadow">
                    Select
                </button>
                <button @click="closeAllModals" class="px-6 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition-colors shadow">
                    Exit
                </button>
            </div>
        </div>
    </div>

    <!-- Master Card Search Modal (Image 3) -->
    <div v-if="showMasterCardSearchModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black bg-opacity-70 backdrop-blur-sm transition-opacity duration-300 ease-in-out">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-lg overflow-hidden transform transition-all duration-300 ease-in-out scale-95 opacity-0" :class="{'scale-100 opacity-100': showMasterCardSearchModal}">
            <div class="flex items-center justify-between p-4 border-b border-gray-200 bg-gradient-to-r from-blue-600 to-blue-700 text-white">
                <h3 class="text-xl font-semibold flex items-center">
                    <i class="fas fa-id-card mr-2"></i> Master Card#
                </h3>
                <button type="button" @click="closeAllModals" class="text-white hover:text-gray-200">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div class="p-6 space-y-4">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Customer Code:</label>
                    <div class="flex items-center">
                        <input type="text" v-model="customerCodeMasterCard" class="form-input w-full px-3 py-2 border border-gray-300 rounded-l-md focus:ring-blue-500 focus:border-blue-500">
                        <button @click="openCustomerAccountSearchModal('masterCard')" class="ml-2 px-3 py-2 bg-gray-200 text-gray-700 rounded-r-md hover:bg-gray-300">
                            <i class="fas fa-table"></i>
                        </button>
                    </div>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">M/Card Seq#:</label>
                    <div class="flex items-center">
                        <input type="text" v-model="masterCardSearch" class="form-input w-full px-3 py-2 border border-gray-300 rounded-l-md focus:ring-blue-500 focus:border-blue-500">
                        <button class="ml-2 px-3 py-2 bg-gray-200 text-gray-700 rounded-r-md hover:bg-gray-300">
                            <i class="fas fa-table"></i>
                        </button>
                    </div>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">M/C Status:</label>
                    <div class="flex items-center space-x-4">
                        <label class="flex items-center">
                            <input type="checkbox" v-model="masterCardStatus.active" class="form-checkbox h-4 w-4 text-blue-600">
                            <span class="ml-2 text-gray-700">Active</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" v-model="masterCardStatus.obsolete" class="form-checkbox h-4 w-4 text-blue-600">
                            <span class="ml-2 text-gray-700">Obsolete</span>
                        </label>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Sort Option:</label>
                    <div class="grid grid-cols-2 gap-2">
                        <label class="flex items-center">
                            <input type="radio" name="masterCardSort" value="1-M/C Seq# + S/Order#" v-model="masterCardSortOption" class="form-radio h-4 w-4 text-blue-600">
                            <span class="ml-2 text-gray-700">1-M/C Seq# + S/Order#</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="masterCardSort" value="2-M/C Seq# + P/O Ref#" v-model="masterCardSortOption" class="form-radio h-4 w-4 text-blue-600">
                            <span class="ml-2 text-gray-700">2-M/C Seq# + P/O Ref#</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="masterCardSort" value="3-Ext.Size + B/Q + Flute" v-model="masterCardSortOption" class="form-radio h-4 w-4 text-blue-600">
                            <span class="ml-2 text-gray-700">3-Ext.Size + B/Q + Flute</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="masterCardSort" value="4-Flute + B/Q + Ext.Size" v-model="masterCardSortOption" class="form-radio h-4 w-4 text-blue-600">
                            <span class="ml-2 text-gray-700">4-Flute + B/Q + Ext.Size</span>
                        </label>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">S/Order Status:</label>
                    <div class="grid grid-cols-2 gap-2">
                        <label class="flex items-center">
                            <input type="checkbox" v-model="salesOrderStatus.outstanding" class="form-checkbox h-4 w-4 text-blue-600">
                            <span class="ml-2 text-gray-700">Outstanding</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" v-model="salesOrderStatus.partialCompleted" class="form-checkbox h-4 w-4 text-blue-600">
                            <span class="ml-2 text-gray-700">Partial Completed</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" v-model="salesOrderStatus.closedManually" class="form-checkbox h-4 w-4 text-blue-600">
                            <span class="ml-2 text-gray-700">Closed Manually</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" v-model="salesOrderStatus.completed" class="form-checkbox h-4 w-4 text-blue-600">
                            <span class="ml-2 text-gray-700">Completed</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" v-model="salesOrderStatus.cancelled" class="form-checkbox h-4 w-4 text-blue-600">
                            <span class="ml-2 text-gray-700">Cancelled</span>
                        </label>
                    </div>
                </div>
            </div>
            <div class="p-4 bg-gray-50 border-t border-gray-200 flex justify-end space-x-3">
                <button @click="performSearch" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors shadow">
                    OK
                </button>
                <button @click="retrySearch" class="px-6 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition-colors shadow">
                    Retry
                </button>
                <button @click="closeAllModals" class="px-6 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition-colors shadow">
                    Exit
                </button>
            </div>
        </div>
    </div>

    <!-- P/Order Ref Search Modal (Image 4) -->
    <div v-if="showPurchaseOrderRefSearchModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black bg-opacity-70 backdrop-blur-sm transition-opacity duration-300 ease-in-out">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-lg overflow-hidden transform transition-all duration-300 ease-in-out scale-95 opacity-0" :class="{'scale-100 opacity-100': showPurchaseOrderRefSearchModal}">
            <div class="flex items-center justify-between p-4 border-b border-gray-200 bg-gradient-to-r from-blue-600 to-blue-700 text-white">
                <h3 class="text-xl font-semibold flex items-center">
                    <i class="fas fa-file-invoice mr-2"></i> P/Order Ref#
                </h3>
                <button type="button" @click="closeAllModals" class="text-white hover:text-gray-200">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div class="p-6 space-y-4 overflow-y-auto max-h-[70vh]">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Customer Code:</label>
                    <div class="flex items-center">
                        <input type="text" v-model="customerCodePurchaseOrderRef" class="form-input w-full px-3 py-2 border border-gray-300 rounded-l-md focus:ring-blue-500 focus:border-blue-500">
                        <button @click="openCustomerAccountSearchModal('purchaseOrderRef')" class="ml-2 px-3 py-2 bg-gray-200 text-gray-700 rounded-r-md hover:bg-gray-300">
                            <i class="fas fa-table"></i>
                        </button>
                    </div>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">P/Order Ref#:</label>
                    <div class="flex items-center">
                        <input type="text" v-model="purchaseOrderRefSearch" class="form-input w-full px-3 py-2 border border-gray-300 rounded-l-md focus:ring-blue-500 focus:border-blue-500">
                        <button class="ml-2 px-3 py-2 bg-gray-200 text-gray-700 rounded-r-md hover:bg-gray-300">
                            <i class="fas fa-table"></i>
                        </button>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Sort Option:</label>
                    <div class="flex items-center space-x-4">
                        <label class="flex items-center">
                            <input type="radio" name="purchaseOrderSort" value="1-P/O Ref# + S/Order#" v-model="purchaseOrderSortOption" class="form-radio h-4 w-4 text-blue-600">
                            <span class="ml-2 text-gray-700">1-P/O Ref# + S/Order#</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="purchaseOrderSort" value="2-P/O Ref# + M/C Seq#" v-model="purchaseOrderSortOption" class="form-radio h-4 w-4 text-blue-600">
                            <span class="ml-2 text-gray-700">2-P/O Ref# + M/C Seq#</span>
                        </label>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">S/Order Status:</label>
                    <div class="grid grid-cols-2 gap-2">
                        <label class="flex items-center">
                            <input type="checkbox" v-model="purchaseOrderStatus.outstanding" class="form-checkbox h-4 w-4 text-blue-600">
                            <span class="ml-2 text-gray-700">Outstanding</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" v-model="purchaseOrderStatus.partialCompleted" class="form-checkbox h-4 w-4 text-blue-600">
                            <span class="ml-2 text-gray-700">Partial Completed</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" v-model="purchaseOrderStatus.closedManually" class="form-checkbox h-4 w-4 text-blue-600">
                            <span class="ml-2 text-gray-700">Closed Manually</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" v-model="purchaseOrderStatus.completed" class="form-checkbox h-4 w-4 text-blue-600">
                            <span class="ml-2 text-gray-700">Completed</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" v-model="purchaseOrderStatus.cancelled" class="form-checkbox h-4 w-4 text-blue-600">
                            <span class="ml-2 text-gray-700">Cancelled</span>
                        </label>
                    </div>
                </div>
            </div>
            <div class="p-4 bg-gray-50 border-t border-gray-200 flex justify-end space-x-3">
                <button @click="performSearch" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors shadow">
                    OK
                </button>
                <button @click="closeAllModals" class="px-6 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition-colors shadow">
                    Exit
                </button>
            </div>
        </div>
    </div>

    <!-- New: Direct P/Order Ref Search Modal (from dashboard) -->
    <div v-if="showDirectPurchaseOrderSearchModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black bg-opacity-70 backdrop-blur-sm transition-opacity duration-300 ease-in-out">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-lg overflow-hidden transform transition-all duration-300 ease-in-out scale-95 opacity-0" :class="{'scale-100 opacity-100': showDirectPurchaseOrderSearchModal}">
            <div class="flex items-center justify-between p-4 border-b border-gray-200 bg-gradient-to-r from-blue-600 to-blue-700 text-white">
                <h3 class="text-xl font-semibold flex items-center">
                    <i class="fas fa-file-invoice mr-2"></i> Search by Purchase Order
                </h3>
                <button type="button" @click="closeAllModals" class="text-white hover:text-gray-200">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div class="p-6 space-y-4 overflow-y-auto max-h-[70vh]">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Customer Code:</label>
                    <div class="flex items-center">
                        <input type="text" v-model="customerCodePurchaseOrderRef" class="form-input w-full px-3 py-2 border border-gray-300 rounded-l-md focus:ring-blue-500 focus:border-blue-500">
                        <button @click="openCustomerAccountSearchModal('purchaseOrderRef')" class="ml-2 px-3 py-2 bg-gray-200 text-gray-700 rounded-r-md hover:bg-gray-300">
                            <i class="fas fa-table"></i>
                        </button>
                    </div>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">P/Order Ref#:</label>
                    <div class="flex items-center">
                        <input type="text" v-model="purchaseOrderRefSearch" class="form-input w-full px-3 py-2 border border-gray-300 rounded-l-md focus:ring-blue-500 focus:border-blue-500">
                        <button class="ml-2 px-3 py-2 bg-gray-200 text-gray-700 rounded-r-md hover:bg-gray-300">
                            <i class="fas fa-table"></i>
                        </button>
                    </div>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Start S/O Period:</label>
                    <div class="flex items-center space-x-2">
                        <input type="text" v-model="purchaseOrderStartSOPeriod.month" class="form-input w-16 px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                        <input type="text" v-model="purchaseOrderStartSOPeriod.year" class="form-input w-24 px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                        <span class="text-gray-600 text-sm">mm/yyyy [If leave blank for P/Order Ref#]</span>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Sort Option:</label>
                    <div class="flex items-center space-x-4">
                        <label class="flex items-center">
                            <input type="radio" name="directPurchaseOrderSort" value="1-P/O Ref# + S/Order#" v-model="purchaseOrderSortOption" class="form-radio h-4 w-4 text-blue-600">
                            <span class="ml-2 text-gray-700">1-P/O Ref# + S/Order#</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="directPurchaseOrderSort" value="2-P/O Ref# + M/C Seq#" v-model="purchaseOrderSortOption" class="form-radio h-4 w-4 text-blue-600">
                            <span class="ml-2 text-gray-700">2-P/O Ref# + M/C Seq#</span>
                        </label>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">S/Order Status:</label>
                    <div class="grid grid-cols-2 gap-2">
                        <label class="flex items-center">
                            <input type="checkbox" v-model="purchaseOrderStatus.outstanding" class="form-checkbox h-4 w-4 text-blue-600">
                            <span class="ml-2 text-gray-700">Outstanding</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" v-model="purchaseOrderStatus.partialCompleted" class="form-checkbox h-4 w-4 text-blue-600">
                            <span class="ml-2 text-gray-700">Partial Completed</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" v-model="purchaseOrderStatus.closedManually" class="form-checkbox h-4 w-4 text-blue-600">
                            <span class="ml-2 text-gray-700">Closed Manually</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" v-model="purchaseOrderStatus.completed" class="form-checkbox h-4 w-4 text-blue-600">
                            <span class="ml-2 text-gray-700">Completed</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" v-model="purchaseOrderStatus.cancelled" class="form-checkbox h-4 w-4 text-blue-600">
                            <span class="ml-2 text-gray-700">Cancelled</span>
                        </label>
                    </div>
                </div>
            </div>
            <div class="p-4 bg-gray-50 border-t border-gray-200 flex justify-end space-x-3">
                <button @click="performSearch" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors shadow">
                    OK
                </button>
                <button @click="retrySearch" class="px-6 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition-colors shadow">
                    Retry
                </button>
                <button @click="closeAllModals" class="px-6 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition-colors shadow">
                    Exit
                </button>
            </div>
        </div>
    </div>

    <!-- Customer Account Search Modal -->
    <div v-if="showCustomerAccountSearchModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black bg-opacity-70 backdrop-blur-sm transition-opacity duration-300 ease-in-out">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-4xl overflow-hidden transform transition-all duration-300 ease-in-out scale-95 opacity-0" :class="{'scale-100 opacity-100': showCustomerAccountSearchModal}">
            <div class="flex items-center justify-between p-4 border-b border-gray-200 bg-gradient-to-r from-blue-600 to-blue-700 text-white">
                <h3 class="text-xl font-semibold flex items-center">
                    <i class="fas fa-users mr-2"></i> Customer Account Search
                </h3>
                <button type="button" @click="closeAllModals" class="text-white hover:text-gray-200">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div class="p-6 space-y-6 overflow-y-auto max-h-[70vh]">
                <div class="mb-4">
                    <label for="customerSearch" class="block text-sm font-medium text-gray-700 mb-1">Search Customer:</label>
                    <input type="text" id="customerSearch" v-model="customerSearchQuery" @input="filterCustomerAccounts" placeholder="Search by Customer Code or Name" class="form-input w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div class="border border-gray-300 rounded-lg overflow-hidden">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer Code</th>
                                <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer Name</th>
                                <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="customer in filteredCustomerAccounts" :key="customer.customer_code" @click="selectCustomer(customer)" class="hover:bg-blue-50 cursor-pointer" :class="{'bg-blue-100': selectedCustomerAccount && selectedCustomerAccount.customer_code === customer.customer_code}">
                                <td class="px-3 py-2 whitespace-nowrap text-sm font-medium text-gray-900">{{ customer.customer_code }}</td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900">{{ customer.customer_name }}</td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900">{{ customer.status }}</td>
                            </tr>
                            <tr v-if="filteredCustomerAccounts.length === 0">
                                <td colspan="3" class="px-3 py-2 text-center text-sm text-gray-500">No customers found.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="p-4 bg-gray-50 border-t border-gray-200 flex justify-end space-x-3">
                <button @click="confirmCustomerSelection" :disabled="!selectedCustomerAccount" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors shadow disabled:opacity-50 disabled:cursor-not-allowed">
                    Select
                </button>
                <button @click="closeAllModals" class="px-6 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition-colors shadow">
                    Exit
                </button>
            </div>
        </div>
    </div>

    <!-- New: Search by Board Purchase Modal -->
    <div v-if="showBoardPurchaseSearchModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black bg-opacity-70 backdrop-blur-sm transition-opacity duration-300 ease-in-out">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-lg overflow-hidden transform transition-all duration-300 ease-in-out scale-95 opacity-0" :class="{'scale-100 opacity-100': showBoardPurchaseSearchModal}">
            <div class="flex items-center justify-between p-4 border-b border-gray-200 bg-gradient-to-r from-blue-600 to-blue-700 text-white">
                <h3 class="text-xl font-semibold flex items-center">
                    <i class="fas fa-pallet mr-2"></i> Search by Board Purchase
                </h3>
                <button type="button" @click="closeAllModals" class="text-white hover:text-gray-200">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div class="p-6 space-y-4 overflow-y-auto max-h-[70vh]">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Customer Code:</label>
                    <div class="flex items-center">
                        <input type="text" v-model="customerCodeBoardPurchase" class="form-input w-full px-3 py-2 border border-gray-300 rounded-l-md focus:ring-blue-500 focus:border-blue-500">
                        <button @click="openCustomerAccountSearchModal('boardPurchase')" class="ml-2 px-3 py-2 bg-gray-200 text-gray-700 rounded-r-md hover:bg-gray-300">
                            <i class="fas fa-table"></i>
                        </button>
                    </div>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">P/Order Ref#:</label>
                    <div class="flex items-center">
                        <input type="text" v-model="boardPurchaseRefSearch" class="form-input w-full px-3 py-2 border border-gray-300 rounded-l-md focus:ring-blue-500 focus:border-blue-500">
                        <button class="ml-2 px-3 py-2 bg-gray-200 text-gray-700 rounded-r-md hover:bg-gray-300">
                            <i class="fas fa-table"></i>
                        </button>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Sort Option:</label>
                    <div class="flex items-center space-x-4">
                        <label class="flex items-center">
                            <input type="radio" name="boardPurchaseSort" value="1-M/C Seq# + S/Order#" v-model="boardPurchaseSortOption" class="form-radio h-4 w-4 text-blue-600">
                            <span class="ml-2 text-gray-700">1-M/C Seq# + S/Order#</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="boardPurchaseSort" value="2-M/C Seq# + P/Order#" v-model="boardPurchaseSortOption" class="form-radio h-4 w-4 text-blue-600">
                            <span class="ml-2 text-gray-700">2-M/C Seq# + P/Order#</span>
                        </label>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">S/Order Status:</label>
                    <div class="grid grid-cols-2 gap-2">
                        <label class="flex items-center">
                            <input type="checkbox" v-model="boardPurchaseOrderStatus.outstanding" class="form-checkbox h-4 w-4 text-blue-600">
                            <span class="ml-2 text-gray-700">Outstanding</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" v-model="boardPurchaseOrderStatus.partialCompleted" class="form-checkbox h-4 w-4 text-blue-600">
                            <span class="ml-2 text-gray-700">Partial Completed</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" v-model="boardPurchaseOrderStatus.closedManually" class="form-checkbox h-4 w-4 text-blue-600">
                            <span class="ml-2 text-gray-700">Closed Manually</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" v-model="boardPurchaseOrderStatus.completed" class="form-checkbox h-4 w-4 text-blue-600">
                            <span class="ml-2 text-gray-700">Completed</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" v-model="boardPurchaseOrderStatus.cancelled" class="form-checkbox h-4 w-4 text-blue-600">
                            <span class="ml-2 text-gray-700">Cancelled</span>
                        </label>
                    </div>
                </div>
            </div>
            <div class="p-4 bg-gray-50 border-t border-gray-200 flex justify-end space-x-3">
                <button @click="performSearch" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors shadow">
                    OK
                </button>
                <button @click="retrySearch" class="px-6 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition-colors shadow">
                    Retry
                </button>
                <button @click="closeAllModals" class="px-6 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition-colors shadow">
                    Exit
                </button>
            </div>
        </div>
    </div>

    <!-- New: Search by Work Order Modal (Image 1) -->
    <div v-if="showInitialWorkOrderModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black bg-opacity-70 backdrop-blur-sm transition-opacity duration-300 ease-in-out">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-lg overflow-hidden transform transition-all duration-300 ease-in-out scale-95 opacity-0" :class="{'scale-100 opacity-100': showInitialWorkOrderModal}">
            <div class="flex items-center justify-between p-4 border-b border-gray-200 bg-gradient-to-r from-blue-600 to-blue-700 text-white">
                <h3 class="text-xl font-semibold flex items-center">
                    <i class="fas fa-clipboard-list mr-2"></i> Search by Work Order
                </h3>
                <button type="button" @click="closeAllModals" class="text-white hover:text-gray-200">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div class="p-6 space-y-6">
                <label class="block text-sm font-medium text-gray-700 mb-1">Work Order#:</label>
                <div class="relative flex items-center rounded-md shadow-sm">
                    <input type="text" v-model="workOrderParts.part1" class="flex-1 block w-full px-3 py-2 border border-gray-300 rounded-l-md focus:ring-blue-500 focus:border-blue-500">
                    <span class="inline-flex items-center px-2 py-2 border-t border-b border-gray-300 bg-gray-50 text-gray-500">
                        -
                    </span>
                    <input type="text" v-model="workOrderParts.part2" class="flex-1 block w-full px-3 py-2 border-t border-b border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                    <span class="inline-flex items-center px-2 py-2 border-t border-b border-gray-300 bg-gray-50 text-gray-500">
                        -
                    </span>
                    <input type="text" v-model="workOrderParts.part3" class="flex-1 block w-full px-3 py-2 border-t border-b border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                    <span class="inline-flex items-center px-2 py-2 border-t border-b border-gray-300 bg-gray-50 text-gray-500">
                        -
                    </span>
                    <input type="text" v-model="workOrderParts.part4" class="flex-1 block w-full px-3 py-2 border border-gray-300 focus:ring-blue-500 focus:border-blue-500 rounded-r-md">
                    <button type="button" @click="openWorkOrderTableModal" class="ml-2 px-3 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <i class="fas fa-table"></i>
                    </button>
                </div>
            </div>
            <div class="p-4 bg-gray-50 border-t border-gray-200 flex justify-end space-x-3">
                <button @click="performSearch" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors shadow">
                    OK
                </button>
                <button @click="retrySearch" class="px-6 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition-colors shadow">
                    Retry
                </button>
                <button @click="closeAllModals" class="px-6 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition-colors shadow">
                    Exit
                </button>
            </div>
        </div>
    </div>

    <!-- New: Work Order Table Modal (Image 2) -->
    <div v-if="showWorkOrderTableModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black bg-opacity-70 backdrop-blur-sm transition-opacity duration-300 ease-in-out">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-6xl overflow-hidden transform transition-all duration-300 ease-in-out scale-95 opacity-0" :class="{'scale-100 opacity-100': showWorkOrderTableModal}">
            <div class="flex items-center justify-between p-4 border-b border-gray-200 bg-gradient-to-r from-blue-600 to-blue-700 text-white">
                <h3 class="text-xl font-semibold flex items-center">
                    <i class="fas fa-table mr-2"></i> Work Order Table
                </h3>
                <button type="button" @click="closeAllModals" class="text-white hover:text-gray-200">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div class="p-6 space-y-6 overflow-y-auto max-h-[70vh]">
                <!-- Search and Input Section -->
                <div class="flex items-center space-x-4 mb-4">
                    <label class="block text-sm font-medium text-gray-700">Work Order#:</label>
                    <input type="text" v-model="workOrderSearchQueryParts.part1" class="form-input w-20 px-2 py-1 border border-gray-300 rounded-md text-sm">
                    <input type="text" v-model="workOrderSearchQueryParts.part2" class="form-input w-20 px-2 py-1 border border-gray-300 rounded-md text-sm">
                    <input type="text" v-model="workOrderSearchQueryParts.part3" class="form-input w-20 px-2 py-1 border border-gray-300 rounded-md text-sm">
                    <button @click="filterWorkOrders" class="px-4 py-1 bg-blue-500 text-white rounded-md text-sm hover:bg-blue-600">Search</button>
                </div>

                <!-- Work Order Table -->
                <div class="border border-gray-300 rounded-lg overflow-hidden mb-6">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">WORK ORDER#</th>
                                <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">SO#</th>
                                <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">CUSTOMER NAME</th>
                                <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">PLAN QTY</th>
                                <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">DUE DATE</th>
                                <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">STATUS</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="wo in filteredWorkOrders" :key="wo.work_order_no" @dblclick="selectWorkOrder(wo)" class="hover:bg-blue-50 cursor-pointer" :class="{'bg-blue-100': selectedWorkOrder && selectedWorkOrder.work_order_no === wo.work_order_no}">
                                <td class="px-3 py-2 whitespace-nowrap text-sm font-medium text-gray-900">{{ wo.work_order_no }}</td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900">{{ wo.so_no }}</td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900">{{ wo.customer_name }}</td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900">{{ wo.plan_qty }}</td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900">{{ wo.due_date }}</td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900">{{ wo.status }}</td>
                            </tr>
                            <tr v-if="filteredWorkOrders.length === 0">
                                <td colspan="6" class="px-3 py-2 text-center text-sm text-gray-500">No work orders found.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Work Order Details Section (visible on double-click) -->
                <div v-if="selectedWorkOrder" class="mb-6 border p-4 rounded-lg bg-gray-50">
                    <h4 class="text-lg font-semibold text-gray-800 mb-4">Work Order Details</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Master Card#:</label>
                            <input type="text" class="form-input mt-1 block w-full bg-gray-100" :value="selectedWorkOrder.master_card_no" readonly>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Model:</label>
                            <input type="text" class="form-input mt-1 block w-full bg-gray-100" :value="selectedWorkOrder.model" readonly>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Product:</label>
                            <input type="text" class="form-input mt-1 block w-full bg-gray-100" :value="selectedWorkOrder.product" readonly>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">P/Design:</label>
                            <input type="text" class="form-input mt-1 block w-full bg-gray-100" :value="selectedWorkOrder.p_design" readonly>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Priority:</label>
                            <input type="text" class="form-input mt-1 block w-full bg-gray-100" :value="selectedWorkOrder.priority" readonly>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Release Type:</label>
                            <input type="text" class="form-input mt-1 block w-full bg-gray-100" :value="selectedWorkOrder.release_type" readonly>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Release:</label>
                            <input type="text" class="form-input mt-1 block w-full bg-gray-100" :value="selectedWorkOrder.release" readonly>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Allowance:</label>
                            <input type="text" class="form-input mt-1 block w-full bg-gray-100" :value="selectedWorkOrder.allowance" readonly>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Book Excess:</label>
                            <input type="text" class="form-input mt-1 block w-full bg-gray-100" :value="selectedWorkOrder.book_excess" readonly>
                        </div>
                    </div>
                </div>
            </div>
            <div class="p-4 bg-gray-50 border-t border-gray-200 flex justify-end space-x-3">
                <button @click="performSearch" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors shadow">
                    Zoom
                </button>
                <button @click="performSearch" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors shadow">
                    Select
                </button>
                <button @click="closeAllModals" class="px-6 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition-colors shadow">
                    Exit
                </button>
            </div>
        </div>
    </div>

    <!-- New: Search by Delivery Order Modal (Image 1) -->
    <div v-if="showInitialDeliveryOrderModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black bg-opacity-70 backdrop-blur-sm transition-opacity duration-300 ease-in-out">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-lg overflow-hidden transform transition-all duration-300 ease-in-out scale-95 opacity-0" :class="{'scale-100 opacity-100': showInitialDeliveryOrderModal}">
            <div class="flex items-center justify-between p-4 border-b border-gray-200 bg-gradient-to-r from-blue-600 to-blue-700 text-white">
                <h3 class="text-xl font-semibold flex items-center">
                    <i class="fas fa-truck mr-2"></i> Search by Delivery Order
                </h3>
                <button type="button" @click="closeAllModals" class="text-white hover:text-gray-200">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div class="p-6 space-y-6">
                <label class="block text-sm font-medium text-gray-700 mb-1">Delivery Order#:</label>
                <div class="relative flex items-center rounded-md shadow-sm">
                    <input type="text" v-model="deliveryOrderParts.part1" class="flex-1 block w-full px-3 py-2 border border-gray-300 rounded-l-md focus:ring-blue-500 focus:border-blue-500">
                    <span class="inline-flex items-center px-2 py-2 border-t border-b border-gray-300 bg-gray-50 text-gray-500">
                        -
                    </span>
                    <input type="text" v-model="deliveryOrderParts.part2" class="flex-1 block w-full px-3 py-2 border-t border-b border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                    <span class="inline-flex items-center px-2 py-2 border-t border-b border-gray-300 bg-gray-50 text-gray-500">
                        -
                    </span>
                    <input type="text" v-model="deliveryOrderParts.part3" class="flex-1 block w-full px-3 py-2 border-t border-b border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                    <span class="inline-flex items-center px-2 py-2 border-t border-b border-gray-300 bg-gray-50 text-gray-500">
                        -
                    </span>
                    <input type="text" v-model="deliveryOrderParts.part4" class="flex-1 block w-full px-3 py-2 border border-gray-300 focus:ring-blue-500 focus:border-blue-500 rounded-r-md">
                    <button type="button" @click="openDeliveryOrderTableModal" class="ml-2 px-3 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <i class="fas fa-table"></i>
                    </button>
                </div>
            </div>
            <div class="p-4 bg-gray-50 border-t border-gray-200 flex justify-end space-x-3">
                <button @click="performSearch" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors shadow">
                    OK
                </button>
                <button @click="retrySearch" class="px-6 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition-colors shadow">
                    Retry
                </button>
                <button @click="closeAllModals" class="px-6 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition-colors shadow">
                    Exit
                </button>
            </div>
        </div>
    </div>

    <!-- New: Delivery Order Table Modal (Image 2) -->
    <div v-if="showDeliveryOrderTableModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black bg-opacity-70 backdrop-blur-sm transition-opacity duration-300 ease-in-out">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-6xl overflow-hidden transform transition-all duration-300 ease-in-out scale-95 opacity-0" :class="{'scale-100 opacity-100': showDeliveryOrderTableModal}">
            <div class="flex items-center justify-between p-4 border-b border-gray-200 bg-gradient-to-r from-blue-600 to-blue-700 text-white">
                <h3 class="text-xl font-semibold flex items-center">
                    <i class="fas fa-table mr-2"></i> Delivery Order Table
                </h3>
                <button type="button" @click="closeAllModals" class="text-white hover:text-gray-200">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div class="p-6 space-y-6 overflow-y-auto max-h-[70vh]">
                <!-- Search and Input Section -->
                <div class="flex items-center space-x-4 mb-4">
                    <label class="block text-sm font-medium text-gray-700">D/Order#:</label>
                    <input type="text" v-model="deliveryOrderSearchQueryParts.part1" class="form-input w-20 px-2 py-1 border border-gray-300 rounded-md text-sm">
                    <input type="text" v-model="deliveryOrderSearchQueryParts.part2" class="form-input w-20 px-2 py-1 border border-gray-300 rounded-md text-sm">
                    <input type="text" v-model="deliveryOrderSearchQueryParts.part3" class="form-input w-20 px-2 py-1 border border-gray-300 rounded-md text-sm">
                    <button @click="filterDeliveryOrders" class="px-4 py-1 bg-blue-500 text-white rounded-md text-sm hover:bg-blue-600">Search</button>
                </div>

                <!-- Delivery Order Table -->
                <div class="border border-gray-300 rounded-lg overflow-hidden mb-6">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">D/Order#</th>
                                <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">D/O Date</th>
                                <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                                <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Vehicle#</th>
                                <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Item#</th>
                                <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">P/C Mode</th>
                                <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="doItem in filteredDeliveryOrders" :key="doItem.do_no" @dblclick="selectDeliveryOrder(doItem)" class="hover:bg-blue-50 cursor-pointer" :class="{'bg-blue-100': selectedDeliveryOrder && selectedDeliveryOrder.do_no === doItem.do_no}">
                                <td class="px-3 py-2 whitespace-nowrap text-sm font-medium text-gray-900">{{ doItem.do_no }}</td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900">{{ doItem.do_date }}</td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900">{{ doItem.customer }}</td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900">{{ doItem.vehicle_no }}</td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900">{{ doItem.item_no }}</td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900">{{ doItem.pc_mode }}</td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900">{{ doItem.status }}</td>
                            </tr>
                            <tr v-if="filteredDeliveryOrders.length === 0">
                                <td colspan="7" class="px-3 py-2 text-center text-sm text-gray-500">No delivery orders found.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Delivery Order Details Section (visible on double-click) -->
                <div v-if="selectedDeliveryOrder" class="mb-6 border p-4 rounded-lg bg-gray-50">
                    <h4 class="text-lg font-semibold text-gray-800 mb-4">Delivery Order Details</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Cust. Name:</label>
                            <input type="text" class="form-input mt-1 block w-full bg-gray-100" :value="selectedDeliveryOrder.customer_name_detail" readonly>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Salesperson:</label>
                            <div class="flex items-center">
                                <input type="text" class="form-input mt-1 block w-20 bg-gray-100" :value="selectedDeliveryOrder.salesperson_code" readonly>
                                <label class="ml-4 text-sm font-medium text-gray-700">C/Ticket#:</label>
                                <input type="text" class="form-input mt-1 ml-2 block w-full bg-gray-100" :value="selectedDeliveryOrder.cticket_no" readonly>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">On-Hold:</label>
                            <input type="text" class="form-input mt-1 block w-full bg-gray-100" :value="selectedDeliveryOrder.on_hold" readonly>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Order Mode:</label>
                            <input type="text" class="form-input mt-1 block w-full bg-gray-100" :value="selectedDeliveryOrder.order_mode" readonly>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Agent Cust.:</label>
                            <input type="text" class="form-input mt-1 block w-full bg-gray-100" :value="selectedDeliveryOrder.agent_cust" readonly>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Sales Type:</label>
                            <input type="text" class="form-input mt-1 block w-full bg-gray-100" :value="selectedDeliveryOrder.sales_type" readonly>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">D/O Inst1:</label>
                            <input type="text" class="form-input mt-1 block w-full bg-gray-100" :value="selectedDeliveryOrder.do_inst1" readonly>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">D/O Inst2:</label>
                            <input type="text" class="form-input mt-1 block w-full bg-gray-100" :value="selectedDeliveryOrder.do_inst2" readonly>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Prepared by:</label>
                            <div class="flex items-center">
                                <input type="text" class="form-input mt-1 block w-20 bg-gray-100" :value="selectedDeliveryOrder.prepared_by" readonly>
                                <label class="ml-4 text-sm font-medium text-gray-700">Date:</label>
                                <input type="text" class="form-input mt-1 ml-2 block w-full bg-gray-100" :value="selectedDeliveryOrder.prepared_date" readonly>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Cancelled by:</label>
                            <div class="flex items-center">
                                <input type="text" class="form-input mt-1 block w-20 bg-gray-100" :value="selectedDeliveryOrder.cancelled_by" readonly>
                                <label class="ml-4 text-sm font-medium text-gray-700">Date:</label>
                                <input type="text" class="form-input mt-1 ml-2 block w-full bg-gray-100" :value="selectedDeliveryOrder.cancelled_date" readonly>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Amended by:</label>
                            <div class="flex items-center">
                                <input type="text" class="form-input mt-1 block w-20 bg-gray-100" :value="selectedDeliveryOrder.amended_by" readonly>
                                <label class="ml-4 text-sm font-medium text-gray-700">Date:</label>
                                <input type="text" class="form-input mt-1 ml-2 block w-full bg-gray-100" :value="selectedDeliveryOrder.amended_date" readonly>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Printed by:</label>
                            <div class="flex items-center">
                                <input type="text" class="form-input mt-1 block w-20 bg-gray-100" :value="selectedDeliveryOrder.printed_by" readonly>
                                <label class="ml-4 text-sm font-medium text-gray-700">Date:</label>
                                <input type="text" class="form-input mt-1 ml-2 block w-full bg-gray-100" :value="selectedDeliveryOrder.printed_date" readonly>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="p-4 bg-gray-50 border-t border-gray-200 flex justify-end space-x-3">
                <button @click="performSearch" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors shadow">
                    Zoom
                </button>
                <button @click="performSearch" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors shadow">
                    Select
                </button>
                <button @click="closeAllModals" class="px-6 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition-colors shadow">
                    Exit
                </button>
            </div>
        </div>
    </div>

    <!-- New: Search by Invoice Modal (Image 1) -->
    <div v-if="showInitialInvoiceModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black bg-opacity-70 backdrop-blur-sm transition-opacity duration-300 ease-in-out">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-lg overflow-hidden transform transition-all duration-300 ease-in-out scale-95 opacity-0" :class="{'scale-100 opacity-100': showInitialInvoiceModal}">
            <div class="flex items-center justify-between p-4 border-b border-gray-200 bg-gradient-to-r from-blue-600 to-blue-700 text-white">
                <h3 class="text-xl font-semibold flex items-center">
                    <i class="fas fa-file-invoice-dollar mr-2"></i> Search by Invoice
                </h3>
                <button type="button" @click="closeAllModals" class="text-white hover:text-gray-200">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div class="p-6 space-y-6">
                <label class="block text-sm font-medium text-gray-700 mb-1">Invoice#:</label>
                <div class="relative flex items-center rounded-md shadow-sm">
                    <input type="text" v-model="invoiceParts.part1" class="flex-1 block w-full px-3 py-2 border border-gray-300 rounded-l-md focus:ring-blue-500 focus:border-blue-500">
                    <span class="inline-flex items-center px-2 py-2 border-t border-b border-gray-300 bg-gray-50 text-gray-500">
                        -
                    </span>
                    <input type="text" v-model="invoiceParts.part2" class="flex-1 block w-full px-3 py-2 border-t border-b border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                    <span class="inline-flex items-center px-2 py-2 border-t border-b border-gray-300 bg-gray-50 text-gray-500">
                        -
                    </span>
                    <input type="text" v-model="invoiceParts.part3" class="flex-1 block w-full px-3 py-2 border-t border-b border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                    <span class="inline-flex items-center px-2 py-2 border-t border-b border-gray-300 bg-gray-50 text-gray-500">
                        -
                    </span>
                    <input type="text" v-model="invoiceParts.part4" class="flex-1 block w-full px-3 py-2 border border-gray-300 focus:ring-blue-500 focus:border-blue-500 rounded-r-md">
                    <button type="button" @click="openInvoiceTableModal" class="ml-2 px-3 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <i class="fas fa-table"></i>
                    </button>
                </div>
            </div>
            <div class="p-4 bg-gray-50 border-t border-gray-200 flex justify-end space-x-3">
                <button @click="performSearch" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors shadow">
                    OK
                </button>
                <button @click="retrySearch" class="px-6 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition-colors shadow">
                    Retry
                </button>
                <button @click="closeAllModals" class="px-6 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition-colors shadow">
                    Exit
                </button>
            </div>
        </div>
    </div>

    <!-- New: Invoice Table Modal (Image 2) -->
    <div v-if="showInvoiceTableModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black bg-opacity-70 backdrop-blur-sm transition-opacity duration-300 ease-in-out">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-6xl overflow-hidden transform transition-all duration-300 ease-in-out scale-95 opacity-0" :class="{'scale-100 opacity-100': showInvoiceTableModal}">
            <div class="flex items-center justify-between p-4 border-b border-gray-200 bg-gradient-to-r from-blue-600 to-blue-700 text-white">
                <h3 class="text-xl font-semibold flex items-center">
                    <i class="fas fa-table mr-2"></i> Invoice Table
                </h3>
                <button type="button" @click="closeAllModals" class="text-white hover:text-gray-200">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div class="p-6 space-y-6 overflow-y-auto max-h-[70vh]">
                <!-- Search and Input Section -->
                <div class="flex items-center space-x-4 mb-4">
                    <label class="block text-sm font-medium text-gray-700">Invoice#:</label>
                    <input type="text" v-model="invoiceSearchQueryParts.part1" class="form-input w-20 px-2 py-1 border border-gray-300 rounded-md text-sm">
                    <input type="text" v-model="invoiceSearchQueryParts.part2" class="form-input w-20 px-2 py-1 border border-gray-300 rounded-md text-sm">
                    <input type="text" v-model="invoiceSearchQueryParts.part3" class="form-input w-20 px-2 py-1 border border-gray-300 rounded-md text-sm">
                    <button @click="filterInvoices" class="px-4 py-1 bg-blue-500 text-white rounded-md text-sm hover:bg-blue-600">Search</button>
                </div>

                <!-- Invoice Table -->
                <div class="border border-gray-300 rounded-lg overflow-hidden mb-6">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Invoice#</th>
                                <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Inv Date</th>
                                <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                                <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tax</th>
                                <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mode</th>
                                <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">P/C Status</th>
                                <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Post Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="invoice in filteredInvoices" :key="invoice.invoice_no" @dblclick="selectInvoice(invoice)" class="hover:bg-blue-50 cursor-pointer" :class="{'bg-blue-100': selectedInvoice && selectedInvoice.invoice_no === invoice.invoice_no}">
                                <td class="px-3 py-2 whitespace-nowrap text-sm font-medium text-gray-900">{{ invoice.invoice_no }}</td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900">{{ invoice.inv_date }}</td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900">{{ invoice.customer_code }}</td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900">{{ invoice.tax }}</td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900">{{ invoice.mode }}</td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900">{{ invoice.pc_status }}</td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900">{{ invoice.post_status }}</td>
                            </tr>
                            <tr v-if="filteredInvoices.length === 0">
                                <td colspan="7" class="px-3 py-2 text-center text-sm text-gray-500">No invoices found.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Invoice Details Section (visible on double-click) -->
                <div v-if="selectedInvoice" class="mb-6 border p-4 rounded-lg bg-gray-50">
                    <h4 class="text-lg font-semibold text-gray-800 mb-4">Invoice Details</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Customer Name:</label>
                            <input type="text" class="form-input mt-1 block w-full bg-gray-100" :value="selectedInvoice.customer_name_detail" readonly>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Order Mode:</label>
                            <input type="text" class="form-input mt-1 block w-full bg-gray-100" :value="selectedInvoice.order_mode" readonly>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Customer's Agent:</label>
                            <input type="text" class="form-input mt-1 block w-full bg-gray-100" :value="selectedInvoice.customer_agent" readonly>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">2nd Invoice Ref#:</label>
                            <input type="text" class="form-input mt-1 block w-full bg-gray-100" :value="selectedInvoice.second_invoice_ref" readonly>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Issued by:</label>
                            <div class="flex items-center">
                                <input type="text" class="form-input mt-1 block w-20 bg-gray-100" :value="selectedInvoice.issued_by" readonly>
                                <label class="ml-4 text-sm font-medium text-gray-700">Date:</label>
                                <input type="text" class="form-input mt-1 ml-2 block w-full bg-gray-100" :value="selectedInvoice.issued_date" readonly>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Amended by:</label>
                            <div class="flex items-center">
                                <input type="text" class="form-input mt-1 block w-20 bg-gray-100" :value="selectedInvoice.amended_by" readonly>
                                <label class="ml-4 text-sm font-medium text-gray-700">Date:</label>
                                <input type="text" class="form-input mt-1 ml-2 block w-full bg-gray-100" :value="selectedInvoice.amended_date" readonly>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Cancelled by:</label>
                            <div class="flex items-center">
                                <input type="text" class="form-input mt-1 block w-20 bg-gray-100" :value="selectedInvoice.cancelled_by" readonly>
                                <label class="ml-4 text-sm font-medium text-gray-700">Date:</label>
                                <input type="text" class="form-input mt-1 ml-2 block w-full bg-gray-100" :value="selectedInvoice.cancelled_date" readonly>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Printed by:</label>
                            <div class="flex items-center">
                                <input type="text" class="form-input mt-1 block w-20 bg-gray-100" :value="selectedInvoice.printed_by" readonly>
                                <label class="ml-4 text-sm font-medium text-gray-700">Date:</label>
                                <input type="text" class="form-input mt-1 ml-2 block w-full bg-gray-100" :value="selectedInvoice.printed_date" readonly>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Posted by:</label>
                            <div class="flex items-center">
                                <input type="text" class="form-input mt-1 block w-20 bg-gray-100" :value="selectedInvoice.posted_by" readonly>
                                <label class="ml-4 text-sm font-medium text-gray-700">Date:</label>
                                <input type="text" class="form-input mt-1 ml-2 block w-full bg-gray-100" :value="selectedInvoice.posted_date" readonly>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Reason:</label>
                            <input type="text" class="form-input mt-1 block w-full bg-gray-100" :value="selectedInvoice.reason" readonly>
                        </div>
                    </div>
                </div>
            </div>
            <div class="p-4 bg-gray-50 border-t border-gray-200 flex justify-end space-x-3">
                <button @click="performSearch" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors shadow">
                    Select
                </button>
                <button @click="closeAllModals" class="px-6 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition-colors shadow">
                    Exit
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import {
    Dialog,
    DialogPanel,
    DialogTitle,
    TransitionChild,
    TransitionRoot,
} from '@headlessui/vue'
import axios from 'axios'; // Import axios

const showInitialSalesOrderModal = ref(false); // For the first "Search by Sales Order" modal
const showOptionsModal = ref(false);         // For the modal with radio button options
const showSalesOrderSearchModal = ref(false); // For the actual Sales Order# input modal (after choosing from options)
const showMasterCardSearchModal = ref(false); // For the Master Card search modal
const showPurchaseOrderRefSearchModal = ref(false); // For the P/Order Ref search modal
const showCustomerAccountSearchModal = ref(false); // New: For customer account search
const showDirectPurchaseOrderSearchModal = ref(false); // New: For direct Purchase Order search from dashboard
const showBoardPurchaseSearchModal = ref(false); // New: For direct Board Purchase search from dashboard

// New: For Work Order Search
const showInitialWorkOrderModal = ref(false);
const showWorkOrderTableModal = ref(false);
const workOrderParts = ref({
    part1: '0',
    part2: '0',
    part3: '0',
    part4: 'mm-yyyy-ccccc',
});
const workOrders = ref([]);
const filteredWorkOrders = ref([]);
const workOrderSearchQueryParts = ref({
    part1: '',
    part2: '',
    part3: '',
});
const selectedWorkOrder = ref(null);

// New: For Delivery Order Search
const showInitialDeliveryOrderModal = ref(false);
const showDeliveryOrderTableModal = ref(false);
const deliveryOrderParts = ref({
    part1: '0',
    part2: '0',
    part3: '0',
    part4: 'mm-yyyy-ccccc',
});
const deliveryOrders = ref([]);
const filteredDeliveryOrders = ref([]);
const deliveryOrderSearchQueryParts = ref({
    part1: '',
    part2: '',
    part3: '',
});
const selectedDeliveryOrder = ref(null);

// New: For Invoice Search
const showInitialInvoiceModal = ref(false);
const showInvoiceTableModal = ref(false);
const invoiceParts = ref({
    part1: '0',
    part2: '0',
    part3: '0',
    part4: 'mm-yyyy-ccccc',
});
const invoices = ref([]);
const filteredInvoices = ref([]);
const invoiceSearchQueryParts = ref({
    part1: '',
    part2: '',
    part3: '',
});
const selectedInvoice = ref(null);

// Data for Sales Order search parts
const salesOrderParts = ref({
    part1: '0',
    part2: '0',
    part3: '0',
    part4: 'mm-yyyy-ccccc',
});

const masterCardSearch = ref('');
const purchaseOrderRefSearch = ref('');

const customerCodeMasterCard = ref(''); // New: For customer code in Master Card modal
const customerCodePurchaseOrderRef = ref(''); // New: For customer code in P/Order Ref modal
const customerCodeBoardPurchase = ref(''); // New: For customer code in Board Purchase modal

// New: For Start S/O Period in Direct Purchase Order modal
const purchaseOrderStartSOPeriod = ref({
    month: '0',
    year: '0',
});

const masterCardStatus = ref({
    active: true,
    obsolete: false,
});

const masterCardSortOption = ref('1-M/C Seq# + S/Order#');

const salesOrderStatus = ref({
    outstanding: true,
    partialCompleted: true,
    closedManually: true,
    completed: false,
    cancelled: false,
});

const purchaseOrderSortOption = ref('1-P/O Ref# + S/Order#');
const boardPurchaseSortOption = ref('1-M/C Seq# + S/Order#'); // New: For Board Purchase modal

const purchaseOrderStatus = ref({
    outstanding: true,
    partialCompleted: true,
    closedManually: true,
    completed: false,
    cancelled: false,
});

const boardPurchaseOrderStatus = ref({
    outstanding: true,
    partialCompleted: true,
    closedManually: true,
    completed: false,
    cancelled: false,
});

const selectedSearchOption = ref('salesOrder'); // Initialize here as it's used by the radio buttons

// New: Data for Customer Account search
const customerAccounts = ref([]);
const filteredCustomerAccounts = ref([]);
const customerSearchQuery = ref('');
const selectedCustomerAccount = ref(null);
const currentCustomerCodeTarget = ref(null); // To know which input to populate

const closeAllModals = () => {
    showInitialSalesOrderModal.value = false;
    showOptionsModal.value = false;
    showSalesOrderSearchModal.value = false;
    showMasterCardSearchModal.value = false;
    showPurchaseOrderRefSearchModal.value = false;
    showCustomerAccountSearchModal.value = false; // Close new modal
    showDirectPurchaseOrderSearchModal.value = false; // New: Close direct PO modal
    showBoardPurchaseSearchModal.value = false; // New: Close Board Purchase modal
    showInitialWorkOrderModal.value = false; // New: Close initial Work Order modal
    showWorkOrderTableModal.value = false; // New: Close Work Order Table modal
    showInitialDeliveryOrderModal.value = false; // New: Close initial Delivery Order modal
    showDeliveryOrderTableModal.value = false; // New: Close Delivery Order Table modal
    showInitialInvoiceModal.value = false; // New: Close initial Invoice modal
    showInvoiceTableModal.value = false; // New: Close Invoice Table modal
    selectedCustomerAccount.value = null; // Clear selected customer
    customerSearchQuery.value = ''; // Clear search query
    filteredCustomerAccounts.value = []; // Clear filtered results
};

const openInitialSalesOrderModal = () => {
    closeAllModals(); // Ensure all others are closed
    showInitialSalesOrderModal.value = true;
    // Reset sales order parts for the initial display
    salesOrderParts.value = {
        part1: '0',
        part2: '0',
        part3: '0',
        part4: 'mm-yyyy-ccccc',
    };
};

const openOptionsModal = () => {
    showInitialSalesOrderModal.value = false; // Close the initial modal
    showOptionsModal.value = true;
};

const openSalesOrderSearchModal = () => {
    closeAllModals();
    showSalesOrderSearchModal.value = true;
    // You might want to reset salesOrderParts here too if needed
};

const openMasterCardSearchModal = () => {
    closeAllModals();
    showMasterCardSearchModal.value = true;
    masterCardSearch.value = ''; // Reset master card search
    customerCodeMasterCard.value = ''; // Reset customer code for Master Card modal
    // Reset Master Card specific options when opened via the 'Options' modal
    masterCardStatus.value = {
        active: true,
        obsolete: false,
    };
    masterCardSortOption.value = '1-M/C Seq# + S/Order#';
    salesOrderStatus.value = {
        outstanding: true,
        partialCompleted: true,
        closedManually: true,
        completed: false,
        cancelled: false,
    };
};

const openMasterCardSearchDirectlyModal = () => {
    closeAllModals();
    showMasterCardSearchModal.value = true;
    masterCardSearch.value = '';
    customerCodeMasterCard.value = ''; // Reset customer code for Master Card modal
    masterCardStatus.value = {
        active: true,
        obsolete: false,
    };
    masterCardSortOption.value = '1-M/C Seq# + S/Order#';
    salesOrderStatus.value = {
        outstanding: true,
        partialCompleted: true,
        closedManually: true,
        completed: false,
        cancelled: false,
    };
};

const openPurchaseOrderRefSearchModal = () => {
    closeAllModals();
    showPurchaseOrderRefSearchModal.value = true;
    purchaseOrderRefSearch.value = ''; // Reset purchase order ref search
    customerCodePurchaseOrderRef.value = ''; // Reset customer code for P/Order Ref modal
    // Reset P/Order Ref specific options when opened via the 'Options' modal
    purchaseOrderSortOption.value = '1-P/O Ref# + S/Order#';
    purchaseOrderStatus.value = {
        outstanding: true,
        partialCompleted: true,
        closedManually: true,
        completed: false,
        cancelled: false,
    };
};

const openPurchaseOrderRefSearchDirectlyModal = () => {
    closeAllModals();
    showDirectPurchaseOrderSearchModal.value = true; // Open the new direct modal
    purchaseOrderRefSearch.value = '';
    customerCodePurchaseOrderRef.value = '';
    purchaseOrderStartSOPeriod.value = { month: '0', year: '0' }; // Reset new field
    purchaseOrderSortOption.value = '1-P/O Ref# + S/Order#';
    purchaseOrderStatus.value = {
        outstanding: true,
        partialCompleted: true,
        closedManually: true,
        completed: false,
        cancelled: false,
    };
};

// New: Function to open Board Purchase Search Modal
const openBoardPurchaseSearchModal = () => {
    closeAllModals();
    showBoardPurchaseSearchModal.value = true;
    customerCodeBoardPurchase.value = '';
    boardPurchaseRefSearch.value = '';
    boardPurchaseSortOption.value = '1-M/C Seq# + S/Order#';
    boardPurchaseOrderStatus.value = {
        outstanding: true,
        partialCompleted: true,
        closedManually: true,
        completed: false,
        cancelled: false,
    };
};

// New: Functions for Work Order Search Modals
const openInitialWorkOrderModal = () => {
    closeAllModals();
    showInitialWorkOrderModal.value = true;
    workOrderParts.value = {
        part1: '0',
        part2: '0',
        part3: '0',
        part4: 'mm-yyyy-ccccc',
    };
};

const openWorkOrderTableModal = () => {
    closeAllModals();
    showWorkOrderTableModal.value = true;
    selectedWorkOrder.value = null; // Clear previous selection
    workOrderSearchQueryParts.value = { part1: '', part2: '', part3: '' }; // Clear search query
    fetchWorkOrders();
};

const fetchWorkOrders = () => {
    // Dummy data for now. Replace with actual API call later.
    workOrders.value = [
        { work_order_no: '06-2025-00438', so_no: '06-2025-00583', customer_name: 'CHEVITA', plan_qty: 2100, due_date: '09/06/2025', status: 'Active', master_card_no: '000603-01', model: 'POLOS 353 X 243 X 168', product: '001', p_design: 'B1', priority: '10 NORMAL', release_type: 'Normal Run', release: '2,100', allowance: '', book_excess: '' },
        { work_order_no: '06-2025-00437', so_no: '06-2025-00582', customer_name: 'CHEVITA', plan_qty: 1300, due_date: '09/06/2025', status: 'Active', master_card_no: '000603-01', model: 'POLOS 353 X 243 X 168', product: '001', p_design: 'B1', priority: '10 NORMAL', release_type: 'Normal Run', release: '1,300', allowance: '', book_excess: '' },
        { work_order_no: '06-2025-00436', so_no: '06-2025-00579', customer_name: 'SOLID MULTI', plan_qty: 3200, due_date: '13/06/2025', status: 'Active', master_card_no: '000603-01', model: 'POLOS 353 X 243 X 168', product: '001', p_design: 'B1', priority: '10 NORMAL', release_type: 'Normal Run', release: '3,200', allowance: '', book_excess: '' },
        { work_order_no: '06-2025-00435', so_no: '06-2025-00579', customer_name: 'SOLID MULTI', plan_qty: 3152, due_date: '13/06/2025', status: 'Active', master_card_no: '000603-01', model: 'POLOS 353 X 243 X 168', product: '001', p_design: 'B1', priority: '10 NORMAL', release_type: 'Normal Run', release: '3,152', allowance: '', book_excess: '' },
        { work_order_no: '06-2025-00434', so_no: '06-2025-00578', customer_name: 'SOLID MULTI', plan_qty: 5150, due_date: '13/06/2025', status: 'Active', master_card_no: '000603-01', model: 'POLOS 353 X 243 X 168', product: '001', p_design: 'B1', priority: '10 NORMAL', release_type: 'Normal Run', release: '5,150', allowance: '', book_excess: '' },
        { work_order_no: '06-2025-00433', so_no: '06-2025-00580', customer_name: 'M.RIZWAN', plan_qty: 2004, due_date: '16/06/2025', status: 'Active', master_card_no: '000581-02', model: 'KARTON BOX POLOS', product: '001', p_design: 'K1', priority: '00 NORMAL', release_type: 'Normal Run', release: '2,004', allowance: '', book_excess: '' },
        { work_order_no: '06-2025-00432', so_no: '06-2025-00576', customer_name: 'PROPAN RAYA', plan_qty: 2000, due_date: '16/06/2025', status: 'Active', master_card_no: 'OL-0000267', model: 'POLYURETHANE WOOD FINISH', product: '001', p_design: 'P1', priority: '00 NORMAL', release_type: 'Normal Run', release: '2,000', allowance: '', book_excess: '' },
        { work_order_no: '06-2025-00431', so_no: '06-2025-00542', customer_name: 'PROPAN RAYA', plan_qty: 3000, due_date: '16/06/2025', status: 'Active', master_card_no: 'OL-0000267', model: 'POLYURETHANE WOOD FINISH', product: '001', p_design: 'P1', priority: '00 NORMAL', release_type: 'Normal Run', release: '3,000', allowance: '', book_excess: '' },
        { work_order_no: '06-2025-00430', so_no: '06-2025-00544', customer_name: 'PROPAN RAYA', plan_qty: 3000, due_date: '16/06/2025', status: 'Active', master_card_no: 'OL-0000267', model: 'POLYURETHANE WOOD FINISH', product: '001', p_design: 'P1', priority: '00 NORMAL', release_type: 'Normal Run', release: '3,000', allowance: '', book_excess: '' },
        { work_order_no: '06-2025-00429', so_no: '05-2025-02496', customer_name: 'HOLLYLAND F', plan_qty: 9200, due_date: '13/06/2025', status: 'Active', master_card_no: '2064353', model: 'PAPER CUP', product: '001', p_design: 'C1', priority: '00 NORMAL', release_type: 'Normal Run', release: '9,200', allowance: '', book_excess: '' },
    ];
    filteredWorkOrders.value = workOrders.value; // Initialize filtered with all data
};

const filterWorkOrders = () => {
    const queryPart1 = workOrderSearchQueryParts.value.part1;
    const queryPart2 = workOrderSearchQueryParts.value.part2;
    const queryPart3 = workOrderSearchQueryParts.value.part3;

    filteredWorkOrders.value = workOrders.value.filter(wo => {
        const woParts = wo.work_order_no.split('-');
        const matchesPart1 = queryPart1 === '' || woParts[0].includes(queryPart1);
        const matchesPart2 = queryPart2 === '' || woParts[1].includes(queryPart2);
        const matchesPart3 = queryPart3 === '' || woParts[2].includes(queryPart3);
        return matchesPart1 && matchesPart2 && matchesPart3;
    });
};

const selectWorkOrder = (wo) => {
    selectedWorkOrder.value = wo;
};

// New: Functions for Delivery Order Search Modals
const openInitialDeliveryOrderModal = () => {
    closeAllModals();
    showInitialDeliveryOrderModal.value = true;
    deliveryOrderParts.value = {
        part1: '0',
        part2: '0',
        part3: '0',
        part4: 'mm-yyyy-ccccc',
    };
};

const openDeliveryOrderTableModal = () => {
    closeAllModals();
    showDeliveryOrderTableModal.value = true;
    selectedDeliveryOrder.value = null; // Clear previous selection
    deliveryOrderSearchQueryParts.value = { part1: '', part2: '', part3: '' }; // Clear search query
    fetchDeliveryOrders();
};

const fetchDeliveryOrders = () => {
    // Dummy data for now. Replace with actual API call later.
    deliveryOrders.value = [
        { do_no: '06-2025-99999', do_date: '31/05/2025', customer: '000117-05', vehicle_no: 'A9489EX', item_no: '1', pc_mode: '2 Multiple', status: 'Cancel', customer_name_detail: 'KARYA INDAH MULTIGUNA, PT', salesperson_code: 'S100', cticket_no: '00-0000-00000', on_hold: 'No', order_mode: '0-Order by Customer + Deliver & Invoice to Customer', agent_cust: '', sales_type: 'Sales', do_inst1: 'NO LOT : 05-25', do_inst2: 'NO SEAL : 000003', prepared_by: 'whs21', prepared_date: '30/05/2025', cancelled_by: 'whs08', cancelled_date: '30/05/2025', amended_by: '', amended_date: '', printed_by: 'whs12', printed_date: '02/06/2025' },
        { do_no: '06-2025-96013', do_date: '05/06/2025', customer: '000541-04', vehicle_no: 'B980KCE', item_no: '1', pc_mode: '0 Multiple', status: 'Active', customer_name_detail: 'TEST CUSTOMER', salesperson_code: 'S200', cticket_no: '01-1111-11111', on_hold: 'No', order_mode: '1-Order by Customer + Deliver to Customer', agent_cust: 'AGENT A', sales_type: 'Online', do_inst1: 'LOT X', do_inst2: 'SEAL Y', prepared_by: 'user01', prepared_date: '01/06/2025', cancelled_by: '', cancelled_date: '', amended_by: '', amended_date: '', printed_by: 'user02', printed_date: '03/06/2025' },
        { do_no: '06-2025-96012', do_date: '04/06/2025', customer: '000541-04', vehicle_no: 'B970KCE', item_no: '1', pc_mode: '0 Multiple', status: 'Active', customer_name_detail: 'TEST CUSTOMER', salesperson_code: 'S200', cticket_no: '01-1111-11111', on_hold: 'No', order_mode: '1-Order by Customer + Deliver to Customer', agent_cust: 'AGENT A', sales_type: 'Online', do_inst1: 'LOT X', do_inst2: 'SEAL Y', prepared_by: 'user01', prepared_date: '01/06/2025', cancelled_by: '', cancelled_date: '', amended_by: '', amended_date: '', printed_by: 'user02', printed_date: '03/06/2025' },
        { do_no: '06-2025-96007', do_date: '03/06/2025', customer: '000541-04', vehicle_no: 'B9471KCE', item_no: '1', pc_mode: '0 Multiple', status: 'Active', customer_name_detail: 'TEST CUSTOMER', salesperson_code: 'S200', cticket_no: '01-1111-11111', on_hold: 'No', order_mode: '1-Order by Customer + Deliver to Customer', agent_cust: 'AGENT A', sales_type: 'Online', do_inst1: 'LOT X', do_inst2: 'SEAL Y', prepared_by: 'user01', prepared_date: '01/06/2025', cancelled_by: '', cancelled_date: '', amended_by: '', amended_date: '', printed_by: 'user02', printed_date: '03/06/2025' },
        { do_no: '06-2025-96006', do_date: '03/06/2025', customer: '000541-04', vehicle_no: 'B9753KXV', item_no: '1', pc_mode: '0 Multiple', status: 'Active', customer_name_detail: 'TEST CUSTOMER', salesperson_code: 'S200', cticket_no: '01-1111-11111', on_hold: 'No', order_mode: '1-Order by Customer + Deliver to Customer', agent_cust: 'AGENT A', sales_type: 'Online', do_inst1: 'LOT X', do_inst2: 'SEAL Y', prepared_by: 'user01', prepared_date: '01/06/2025', cancelled_by: '', cancelled_date: '', amended_by: '', amended_date: '', printed_by: 'user02', printed_date: '03/06/2025' },
        { do_no: '06-2025-96005', do_date: '03/06/2025', customer: '000541-04', vehicle_no: 'B9753KXV', item_no: '1', pc_mode: '0 Multiple', status: 'Active', customer_name_detail: 'TEST CUSTOMER', salesperson_code: 'S200', cticket_no: '01-1111-11111', on_hold: 'No', order_mode: '1-Order by Customer + Deliver to Customer', agent_cust: 'AGENT A', sales_type: 'Online', do_inst1: 'LOT X', do_inst2: 'SEAL Y', prepared_by: 'user01', prepared_date: '01/06/2025', cancelled_by: '', cancelled_date: '', amended_by: '', amended_date: '', printed_by: 'user02', printed_date: '03/06/2025' },
    ];
    filteredDeliveryOrders.value = deliveryOrders.value; // Initialize filtered with all data
};

const filterDeliveryOrders = () => {
    const queryPart1 = deliveryOrderSearchQueryParts.value.part1;
    const queryPart2 = deliveryOrderSearchQueryParts.value.part2;
    const queryPart3 = deliveryOrderSearchQueryParts.value.part3;

    filteredDeliveryOrders.value = deliveryOrders.value.filter(doItem => {
        const doParts = doItem.do_no.split('-');
        const matchesPart1 = queryPart1 === '' || doParts[0].includes(queryPart1);
        const matchesPart2 = queryPart2 === '' || doParts[1].includes(queryPart2);
        const matchesPart3 = queryPart3 === '' || doParts[2].includes(queryPart3);
        return matchesPart1 && matchesPart2 && matchesPart3;
    });
};

const selectDeliveryOrder = (doItem) => {
    selectedDeliveryOrder.value = doItem;
};

// New: Functions for Invoice Search Modals
const openInitialInvoiceModal = () => {
    closeAllModals();
    showInitialInvoiceModal.value = true;
    invoiceParts.value = {
        part1: '0',
        part2: '0',
        part3: '0',
        part4: 'mm-yyyy-ccccc',
    };
};

const openInvoiceTableModal = () => {
    closeAllModals();
    showInvoiceTableModal.value = true;
    selectedInvoice.value = null; // Clear previous selection
    invoiceSearchQueryParts.value = { part1: '', part2: '', part3: '' }; // Clear search query
    fetchInvoices();
};

const fetchInvoices = () => {
    // Dummy data for now. Replace with actual API call later.
    invoices.value = [
        { invoice_no: '06-2025-00408', inv_date: '05/06/2025', customer_code: '000541-04', tax: 'NIL', mode: 'Manual', pc_status: '0 Amd', post_status: 'UnPost', customer_name_detail: 'ZINUS DREAM INDONESIA, PT', order_mode: '0-Order by Customer + Deliver & Invoice to Customer', customer_agent: '', second_invoice_ref: '', issued_by: 'fin02', issued_date: '5/06/2025', amended_by: '', amended_date: '', cancelled_by: '', cancelled_date: '', printed_by: '', printed_date: '', posted_by: '', posted_date: '' },
        { invoice_no: '06-2025-00407', inv_date: '04/06/2025', customer_code: '000100', tax: 'PPN11', mode: 'Manual', pc_status: '1 New', post_status: 'UnPost', customer_name_detail: 'Customer B', order_mode: '1-Order by Customer', customer_agent: 'Agent B', second_invoice_ref: 'REF-001', issued_by: 'fin01', issued_date: '04/06/2025', amended_by: '', amended_date: '', cancelled_by: '', cancelled_date: '', printed_by: '', printed_date: '', posted_by: '', posted_date: '' },
        { invoice_no: '06-2025-00406', inv_date: '04/06/2025', customer_code: '000045', tax: 'PPN11', mode: 'Manual', pc_status: '0 New', post_status: 'UnPost', customer_name_detail: 'Customer C', order_mode: '0-Order by Customer + Deliver & Invoice to Customer', customer_agent: '', second_invoice_ref: '', issued_by: 'fin02', issued_date: '04/06/2025', amended_by: '', amended_date: '', cancelled_by: '', cancelled_date: '', printed_by: '', printed_date: '', posted_by: '', posted_date: '' },
        { invoice_no: '06-2025-00405', inv_date: '04/06/2025', customer_code: '000045', tax: 'PPN11', mode: 'Manual', pc_status: '0 New', post_status: 'UnPost', customer_name_detail: 'Customer C', order_mode: '0-Order by Customer + Deliver & Invoice to Customer', customer_agent: '', second_invoice_ref: '', issued_by: 'fin02', issued_date: '04/06/2025', amended_by: '', amended_date: '', cancelled_by: '', cancelled_date: '', printed_by: '', printed_date: '', posted_by: '', posted_date: '' },
        { invoice_no: '06-2025-00404', inv_date: '04/06/2025', customer_code: '000045', tax: 'PPN11', mode: 'Manual', pc_status: '0 New', post_status: 'UnPost', customer_name_detail: 'Customer C', order_mode: '0-Order by Customer + Deliver & Invoice to Customer', customer_agent: '', second_invoice_ref: '', issued_by: 'fin02', issued_date: '04/06/2025', amended_by: '', amended_date: '', cancelled_by: '', cancelled_date: '', printed_by: '', printed_date: '', posted_by: '', posted_date: '' },
        { invoice_no: '06-2025-00403', inv_date: '04/06/2025', customer_code: '000045', tax: 'PPN11', mode: 'Manual', pc_status: '0 New', post_status: 'UnPost', customer_name_detail: 'Customer C', order_mode: '0-Order by Customer + Deliver & Invoice to Customer', customer_agent: '', second_invoice_ref: '', issued_by: 'fin02', issued_date: '04/06/2025', amended_by: '', amended_date: '', cancelled_by: '', cancelled_date: '', printed_by: '', printed_date: '', posted_by: '', posted_date: '' },
        { invoice_no: '06-2025-00402', inv_date: '04/06/2025', customer_code: '000045', tax: 'PPN11', mode: 'Manual', pc_status: '0 New', post_status: 'UnPost', customer_name_detail: 'Customer C', order_mode: '0-Order by Customer + Deliver & Invoice to Customer', customer_agent: '', second_invoice_ref: '', issued_by: 'fin02', issued_date: '04/06/2025', amended_by: '', amended_date: '', cancelled_by: '', cancelled_date: '', printed_by: '', printed_date: '', posted_by: '', posted_date: '' },
    ];
    filteredInvoices.value = invoices.value; // Initialize filtered with all data
};

const filterInvoices = () => {
    const queryPart1 = invoiceSearchQueryParts.value.part1;
    const queryPart2 = invoiceSearchQueryParts.value.part2;
    const queryPart3 = invoiceSearchQueryParts.value.part3;

    filteredInvoices.value = invoices.value.filter(invoice => {
        const invoiceParts = invoice.invoice_no.split('-');
        const matchesPart1 = queryPart1 === '' || invoiceParts[0].includes(queryPart1);
        const matchesPart2 = queryPart2 === '' || invoiceParts[1].includes(queryPart2);
        const matchesPart3 = queryPart3 === '' || invoiceParts[2].includes(queryPart3);
        return matchesPart1 && matchesPart2 && matchesPart3;
    });
};

const selectInvoice = (invoice) => {
    selectedInvoice.value = invoice;
};

// New: Customer Account Search Modal functions
const openCustomerAccountSearchModal = async (targetInput) => {
    closeAllModals(); // Close all other modals first
    currentCustomerCodeTarget.value = targetInput; // Set the target input field
    showCustomerAccountSearchModal.value = true;
    selectedCustomerAccount.value = null; // Clear any previous selection
    customerSearchQuery.value = ''; // Clear search query
    await fetchCustomerAccounts(); // Fetch data when modal opens
};

const fetchCustomerAccounts = async () => {
    try {
        const response = await axios.get('/api/customer-accounts');
        customerAccounts.value = response.data;
        filteredCustomerAccounts.value = response.data; // Initially display all
    } catch (error) {
        console.error('Error fetching customer accounts:', error);
        // Optionally, show a notification to the user
    }
};

const filterCustomerAccounts = () => {
    const query = customerSearchQuery.value.toLowerCase();
    filteredCustomerAccounts.value = customerAccounts.value.filter(customer =>
        customer.customer_code.toLowerCase().includes(query) ||
        customer.customer_name.toLowerCase().includes(query)
    );
};

const selectCustomer = (customer) => {
    selectedCustomerAccount.value = customer;
};

const confirmCustomerSelection = () => {
    if (selectedCustomerAccount.value) {
        if (currentCustomerCodeTarget.value === 'masterCard') {
            customerCodeMasterCard.value = selectedCustomerAccount.value.customer_code;
        } else if (currentCustomerCodeTarget.value === 'purchaseOrderRef') {
            customerCodePurchaseOrderRef.value = selectedCustomerAccount.value.customer_code;
        } else if (currentCustomerCodeTarget.value === 'boardPurchase') {
            customerCodeBoardPurchase.value = selectedCustomerAccount.value.customer_code;
        } else if (currentCustomerCodeTarget.value === 'customerCodeFrom' || currentCustomerCodeTarget.value === 'customerCodeTo') {
            emit('customerSelected', { customer: selectedCustomerAccount.value, target: currentCustomerCodeTarget.value });
        }
        closeAllModals(); // Close the customer account modal
        // Re-open the parent modal based on the specific scenario, if needed
        if (currentCustomerCodeTarget.value === 'masterCard') {
            showMasterCardSearchModal.value = true;
        } else if (currentCustomerCodeTarget.value === 'purchaseOrderRef') {
            showPurchaseOrderRefSearchModal.value = true;
        } else if (currentCustomerCodeTarget.value === 'boardPurchase') {
            showBoardPurchaseSearchModal.value = true;
        }
    }
};

const performSearch = () => {
    // This will be generalized later. For now, it's just a placeholder.
    console.log('Performing search...');
    closeAllModals();
    // emit('searchPerformed'); // If the parent needs to know a search was done
};

const retrySearch = () => {
    // Implement retry logic or just keep the modal open and clear inputs
    console.log('Retrying search...');
    // For now, let's just reset parts for the current open modal
    if (showInitialSalesOrderModal.value || showSalesOrderSearchModal.value) {
        salesOrderParts.value = {
            part1: '0',
            part2: '0',
            part3: '0',
            part4: 'mm-yyyy-ccccc',
        };
    } else if (showMasterCardSearchModal.value) {
        masterCardSearch.value = '';
        customerCodeMasterCard.value = '';
        masterCardStatus.value = {
            active: true,
            obsolete: false,
        };
        masterCardSortOption.value = '1-M/C Seq# + S/Order#';
        salesOrderStatus.value = {
            outstanding: true,
            partialCompleted: true,
            closedManually: true,
            completed: false,
            cancelled: false,
        };
    } else if (showPurchaseOrderRefSearchModal.value) {
        purchaseOrderRefSearch.value = '';
        customerCodePurchaseOrderRef.value = '';
        purchaseOrderSortOption.value = '1-P/O Ref# + S/Order#';
        purchaseOrderStatus.value = {
            outstanding: true,
            partialCompleted: true,
            closedManually: true,
            completed: false,
            cancelled: false,
        };
    } else if (showDirectPurchaseOrderSearchModal.value) { // New: Handle retry for direct PO modal
        purchaseOrderRefSearch.value = '';
        customerCodePurchaseOrderRef.value = '';
        purchaseOrderStartSOPeriod.value = { month: '0', year: '0' };
        purchaseOrderSortOption.value = '1-P/O Ref# + S/Order#';
        purchaseOrderStatus.value = {
            outstanding: true,
            partialCompleted: true,
            closedManually: true,
            completed: false,
            cancelled: false,
        };
    } else if (showBoardPurchaseSearchModal.value) { // New: Handle retry for Board Purchase modal
        customerCodeBoardPurchase.value = '';
        boardPurchaseRefSearch.value = '';
        boardPurchaseSortOption.value = '1-M/C Seq# + S/Order#';
        boardPurchaseOrderStatus.value = {
            outstanding: true,
            partialCompleted: true,
            closedManually: true,
            completed: false,
            cancelled: false,
        };
    } else if (showInitialWorkOrderModal.value || showWorkOrderTableModal.value) { // New: Handle retry for Work Order modals
        workOrderParts.value = {
            part1: '0',
            part2: '0',
            part3: '0',
            part4: 'mm-yyyy-ccccc',
        };
        workOrderSearchQueryParts.value = { part1: '', part2: '', part3: '' };
        selectedWorkOrder.value = null;
        fetchWorkOrders(); // Re-fetch initial data for table
    } else if (showInitialDeliveryOrderModal.value || showDeliveryOrderTableModal.value) { // New: Handle retry for Delivery Order modals
        deliveryOrderParts.value = {
            part1: '0',
            part2: '0',
            part3: '0',
            part4: 'mm-yyyy-ccccc',
        };
        deliveryOrderSearchQueryParts.value = { part1: '', part2: '', part3: '' };
        selectedDeliveryOrder.value = null;
        fetchDeliveryOrders(); // Re-fetch initial data for table
    } else if (showInitialInvoiceModal.value || showInvoiceTableModal.value) { // New: Handle retry for Invoice modals
        invoiceParts.value = {
            part1: '0',
            part2: '0',
            part3: '0',
            part4: 'mm-yyyy-ccccc',
        };
        invoiceSearchQueryParts.value = { part1: '', part2: '', part3: '' };
        selectedInvoice.value = null;
        fetchInvoices(); // Re-fetch initial data for table
    }
    // emit('retrySearch');
};

const handleOptionSelection = () => {
    if (selectedSearchOption.value === 'salesOrder') {
        openSalesOrderSearchModal();
    } else if (selectedSearchOption.value === 'masterCard') {
        openMasterCardSearchModal();
    } else if (selectedSearchOption.value === 'purchaseOrderRef') {
        openPurchaseOrderRefSearchModal();
    }
};

const emit = defineEmits(['customerSelected']);

defineExpose({
    openInitialSalesOrderModal,
    closeAllModals,
    performSearch,
    retrySearch,
    openMasterCardSearchDirectlyModal,
    openPurchaseOrderRefSearchDirectlyModal,
    openBoardPurchaseSearchModal, // New: Expose the new function
    openInitialWorkOrderModal, // New: Expose Work Order functions
    openInitialDeliveryOrderModal, // New: Expose Delivery Order functions
    openInitialInvoiceModal, // New: Expose Invoice functions
    openCustomerAccountSearchModal, // Expose this for other components to use
});
</script>

<style scoped>
/* Basic Modal Transition Styles (These might be generic and could be moved to a global CSS if applicable) */
.modal-enter-active, .modal-leave-active {
    transition: opacity 0.3s ease-in-out;
}
.modal-enter-from, .modal-leave-to {
    opacity: 0;
}
.modal-enter-active .modal-container,
.modal-leave-active .modal-container {
    transition: all 0.3s ease-in-out;
}
.modal-enter-from .modal-container,
.modal-leave-to .modal-container {
    transform: scale(0.95);
    opacity: 0;
}
</style> 