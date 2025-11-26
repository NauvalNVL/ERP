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
                            <tr v-for="order in filteredSalesOrders" :key="order.so_number" @dblclick="selectSalesOrder(order)" class="hover:bg-blue-50 cursor-pointer" :class="{'bg-blue-100': selectedSalesOrder && selectedSalesOrder.so_number === order.so_number}">
                                <td class="px-3 py-2 whitespace-nowrap text-sm font-medium text-gray-900">{{ order.so_number }}</td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900">{{ order.customer_po_number }}</td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900">{{ order.customer_code }}</td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900">{{ order.master_card_seq }}</td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900">{{ order.status }}</td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900">{{ order.delivery_location }}</td>
                            </tr>
                            <tr v-if="filteredSalesOrders.length === 0">
                                <td colspan="6" class="px-3 py-2 text-center text-sm text-gray-500">No sales orders found.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Search and Order details -->
                <div class="mb-6 border p-4 rounded-lg bg-gray-50">
                    <div class="flex items-center space-x-2 mb-4">
                        <label class="block text-sm font-medium text-gray-700">Search:</label>
                        <input type="text" v-model="salesOrderSearch.month" class="form-input w-16 px-2 py-1 border border-gray-300 rounded-md text-sm">
                        <input type="text" v-model="salesOrderSearch.year" class="form-input w-16 px-2 py-1 border border-gray-300 rounded-md text-sm">
                        <input type="text" v-model="salesOrderSearch.sequence" class="form-input w-16 px-2 py-1 border border-gray-300 rounded-md text-sm">
                        <button @click="onSalesOrderSearch" class="px-4 py-1 bg-blue-500 text-white rounded-md text-sm hover:bg-blue-600">Search</button>
                        <span class="text-sm font-medium text-gray-700">S/Order#</span>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Customer Name:</label>
                            <input type="text" class="form-input mt-1 block w-full bg-gray-100" :value="selectedSalesOrder ? selectedSalesOrder.customer_name : ''" readonly>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Model:</label>
                            <input type="text" class="form-input mt-1 block w-full bg-gray-100" :value="selectedSalesOrder ? selectedSalesOrder.master_card_model : ''" readonly>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Order Mode:</label>
                            <input type="text" class="form-input mt-1 block w-full bg-gray-100" :value="selectedSalesOrder ? (selectedSalesOrder.order_group + ' - ' + selectedSalesOrder.order_type) : ''" readonly>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Salesperson:</label>
                            <div class="flex items-center">
                                <input type="text" class="form-input mt-1 block w-20 bg-gray-100" :value="selectedSalesOrder ? selectedSalesOrder.salesperson_code : ''" readonly>
                                <input type="text" class="form-input mt-1 ml-2 block w-full bg-gray-100" value="" readonly>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Order Group:</label>
                            <div class="flex items-center">
                                <input type="text" class="form-input mt-1 block w-20 bg-gray-100" :value="selectedSalesOrder ? selectedSalesOrder.order_group : ''" readonly>
                                <label class="ml-4 text-sm font-medium text-gray-700">Order Type:</label>
                                <input type="text" class="form-input mt-1 ml-2 block w-20 bg-gray-100" :value="selectedSalesOrder ? selectedSalesOrder.order_type : ''" readonly>
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
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900">{{ salesOrderItemDetails.pd }}</td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900">{{ getFittingField(0, 'design') }}</td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900">{{ getFittingField(1, 'design') }}</td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900">{{ getFittingField(2, 'design') }}</td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900">{{ getFittingField(3, 'design') }}</td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900">{{ getFittingField(4, 'design') }}</td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900">{{ getFittingField(5, 'design') }}</td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900">{{ getFittingField(6, 'design') }}</td>
                            </tr>
                            <tr>
                                <td class="px-3 py-2 whitespace-nowrap text-sm font-medium text-gray-900">PCS</td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900"></td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900">{{ salesOrderItemDetails.pcs }}</td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900">{{ getFittingField(0, 'pcs') }}</td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900">{{ getFittingField(1, 'pcs') }}</td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900">{{ getFittingField(2, 'pcs') }}</td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900">{{ getFittingField(3, 'pcs') }}</td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900">{{ getFittingField(4, 'pcs') }}</td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900">{{ getFittingField(5, 'pcs') }}</td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900">{{ getFittingField(6, 'pcs') }}</td>
                            </tr>
                            <tr>
                                <td class="px-3 py-2 whitespace-nowrap text-sm font-medium text-gray-900">UNIT</td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900"></td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900">{{ salesOrderItemDetails.unit }}</td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900">{{ getFittingField(0, 'unit') }}</td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900">{{ getFittingField(1, 'unit') }}</td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900">{{ getFittingField(2, 'unit') }}</td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900">{{ getFittingField(3, 'unit') }}</td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900">{{ getFittingField(4, 'unit') }}</td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900">{{ getFittingField(5, 'unit') }}</td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900">{{ getFittingField(6, 'unit') }}</td>
                            </tr>
                            <tr>
                                <td class="px-3 py-2 whitespace-nowrap text-sm font-medium text-gray-900">ORDER</td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900"></td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900">{{ salesOrderItemDetails.order_qty }}</td>
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
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900">{{ salesOrderItemDetails.net_delivery }}</td>
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
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900">{{ salesOrderItemDetails.balance }}</td>
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
                        <button type="button" @click="openMasterCardListModal" class="ml-2 px-3 py-2 bg-gray-200 text-gray-700 rounded-r-md hover:bg-gray-300">
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

    <!-- Master Card List Modal integration -->
    <MasterCardSearchSelectModal
        :show="showMasterCardListModal"
        :customer-code="customerCodeMasterCard"
        @close="showMasterCardListModal = false"
        @select-mc="handleMasterCardSelected"
        @reopen-options="showMasterCardSearchModal = true"
        @zoom-mc="handleMasterCardSelected"
    />
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
import MasterCardSearchSelectModal from '@/Components/MasterCardSearchSelectModal.vue'

const showInitialSalesOrderModal = ref(false); // For the first "Search by Sales Order" modal
const showOptionsModal = ref(false);         // For the modal with radio button options
const showSalesOrderSearchModal = ref(false); // For the actual Sales Order# input modal (after choosing from options)
const showMasterCardSearchModal = ref(false); // For the Master Card search modal
const showPurchaseOrderRefSearchModal = ref(false); // For the P/Order Ref search modal
const showCustomerAccountSearchModal = ref(false); // New: For customer account search

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

const salesOrders = ref([]);
const filteredSalesOrders = ref([]);
const selectedSalesOrder = ref(null);
const salesOrderSearch = ref({
    month: '',
    year: '',
    sequence: '',
});

const salesOrderItemDetails = ref({
    pd: '',
    pcs: '',
    unit: '',
    order_qty: '',
    net_delivery: '',
    balance: '',
});

const salesOrderFittings = ref([]);

const masterCardSearch = ref('');
const purchaseOrderRefSearch = ref('');

const customerCodeMasterCard = ref(''); // New: For customer code in Master Card modal
const customerCodePurchaseOrderRef = ref(''); // New: For customer code in P/Order Ref modal

const masterCardStatus = ref({
    active: true,
    obsolete: false,
});

const masterCardSortOption = ref('1-M/C Seq# + S/Order#');

const showMasterCardListModal = ref(false);

const salesOrderStatus = ref({
    outstanding: true,
    partialCompleted: true,
    closedManually: true,
    completed: false,
    cancelled: false,
});

const purchaseOrderSortOption = ref('1-P/O Ref# + S/Order#');

const purchaseOrderStatus = ref({
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
    showInitialDeliveryOrderModal.value = false; // New: Close initial Delivery Order modal
    showDeliveryOrderTableModal.value = false; // New: Close Delivery Order Table modal
    showInitialInvoiceModal.value = false; // New: Close initial Invoice modal
    showInvoiceTableModal.value = false; // New: Close Invoice Table modal
    selectedCustomerAccount.value = null; // Clear selected customer
    customerSearchQuery.value = ''; // Clear search query
    filteredCustomerAccounts.value = []; // Clear filtered results
    showMasterCardListModal.value = false;
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
    selectedSalesOrder.value = null;
    salesOrderSearch.value = {
        month: '',
        year: '',
        sequence: '',
    };
    fetchSalesOrders();
};

const fetchSalesOrders = async (extraParams = {}) => {
    try {
        const params = {};

        if (salesOrderSearch.value.month) params.month = salesOrderSearch.value.month;
        if (salesOrderSearch.value.year) params.year = salesOrderSearch.value.year;
        if (salesOrderSearch.value.sequence) params.sequence = salesOrderSearch.value.sequence;

        Object.assign(params, extraParams);

        const response = await axios.get('/api/sales-orders', { params });
        const payload = response.data;
        const data = Array.isArray(payload) ? payload : (payload.data || []);

        salesOrders.value = data;
        filteredSalesOrders.value = data;
    } catch (error) {
        console.error('Error fetching sales orders:', error);
        salesOrders.value = [];
        filteredSalesOrders.value = [];
    }
};

const onSalesOrderSearch = () => {
    fetchSalesOrders();
};

const selectSalesOrder = (order) => {
    selectedSalesOrder.value = order;
    if (order && order.so_number) {
        fetchSalesOrderDetail(order.so_number);
    }
};

const fetchSalesOrderDetail = async (soNumber) => {
    try {
        const response = await axios.get(`/api/sales-order/${encodeURIComponent(soNumber)}/detail`);
        const payload = response.data || {};

        if (!payload.success || !payload.data) {
            salesOrderItemDetails.value = {
                pd: '',
                pcs: '',
                unit: '',
                order_qty: '',
                net_delivery: '',
                balance: '',
            };
            salesOrderFittings.value = [];
            return;
        }

        const { order_info, item_details, fittings } = payload.data;

        // Update header info if available
        if (order_info) {
            selectedSalesOrder.value = {
                ...(selectedSalesOrder.value || {}),
                customer_name: order_info.customer_name ?? selectedSalesOrder.value?.customer_name ?? '',
                master_card_model: order_info.model ?? selectedSalesOrder.value?.master_card_model ?? '',
                order_group: order_info.order_group ?? selectedSalesOrder.value?.order_group ?? '',
                order_type: order_info.order_type ?? selectedSalesOrder.value?.order_type ?? '',
                salesperson_code: order_info.salesperson_code ?? selectedSalesOrder.value?.salesperson_code ?? '',
            };
        }

        // Update main item details row
        salesOrderItemDetails.value = {
            pd: item_details?.pd ?? '',
            pcs: item_details?.pcs ?? '',
            unit: item_details?.unit ?? '',
            order_qty: item_details?.order_qty ?? '',
            net_delivery: item_details?.net_delivery ?? '',
            balance: item_details?.balance ?? '',
        };

        // Update fittings (Fit1..Fit9)
        salesOrderFittings.value = Array.isArray(fittings) ? fittings : [];
    } catch (error) {
        console.error('Error fetching sales order detail:', error);
        salesOrderItemDetails.value = {
            pd: '',
            pcs: '',
            unit: '',
            order_qty: '',
            net_delivery: '',
            balance: '',
        };
        salesOrderFittings.value = [];
    }
};

const getFittingField = (index, field) => {
    const fit = salesOrderFittings.value?.[index];
    if (!fit) return '';

    if (field === 'design') return fit.design ?? '';
    if (field === 'pcs') return fit.pcs ?? '';
    if (field === 'unit') return fit.unit ?? '';
    return '';
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

const openMasterCardListModal = () => {
    // Require customer code first to match CPS flow
    if (!customerCodeMasterCard.value) {
        console.warn('Customer code is required before browsing Master Cards');
        return;
    }
    showMasterCardListModal.value = true;
};

const handleMasterCardSelected = (mc) => {
    if (!mc) return;
    // Fill M/Card Seq# with selected MC sequence
    masterCardSearch.value = mc.mc_seq || mc.seq || '';
    showMasterCardListModal.value = false;
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

const fetchDeliveryOrders = async () => {
    try {
        const response = await axios.get('/api/invoices/delivery-orders');
        const data = response.data;

        const rawOrders = Array.isArray(data) ? data : (data.data || []);

        deliveryOrders.value = rawOrders.map(order => ({
            do_no: order.do_number || order.DO_Num || '',
            do_date: order.do_date || order.DO_DMY || '',
            customer: order.customer_code || order.AC_Num || '',
            vehicle_no: order.vehicle_no || order.DO_VHC_Num || '',
            item_no: typeof order.item_count !== 'undefined' ? order.item_count : (order.item_no || 1),
            pc_mode: order.mode || order.pc_mode || '',
            status: order.status || order.Status || '',
            customer_name_detail: order.customer_name || order.AC_Name || '',
            salesperson_code: order.salesperson_code || order.salesperson || '',
            cticket_no: order.cticket_no || '',
            on_hold: order.on_hold || '',
            order_mode: order.order_mode || '',
            agent_cust: order.agent_cust || '',
            sales_type: order.sales_type || '',
            do_inst1: order.remark1 || order.DO_Remark1 || '',
            do_inst2: order.remark2 || order.DO_Remark2 || '',
            prepared_by: order.prepared_by || '',
            prepared_date: order.prepared_date || '',
            cancelled_by: order.cancelled_by || '',
            cancelled_date: order.cancelled_date || '',
            amended_by: order.amended_by || '',
            amended_date: order.amended_date || '',
            printed_by: order.printed_by || '',
            printed_date: order.printed_date || '',
        }));

        filteredDeliveryOrders.value = deliveryOrders.value;
    } catch (error) {
        console.error('Error fetching delivery orders:', error);
        deliveryOrders.value = [];
        filteredDeliveryOrders.value = [];
    }
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

const fetchInvoices = async () => {
    try {
        const response = await axios.get('/api/invoices');
        const payload = response.data;
        const data = Array.isArray(payload) ? payload : (payload.data || []);

        invoices.value = data.map(inv => ({
            // Core fields from backend
            invoice_no: inv.invoice_no || inv.IV_NUM || '',
            inv_date: inv.invoice_date || inv.inv_date || '',
            customer_code: inv.customer_code || inv.AC_NUM || '',
            tax: inv.tax_code || inv.tax || '',
            mode: inv.mode || 'Manual',
            pc_status: inv.pc_status ?? '',
            post_status: inv.post_status ?? '',

            // Detail fields used in the bottom panel
            customer_name_detail: inv.customer_name || inv.customer_name_detail || '',
            order_mode: inv.order_mode || '',
            customer_agent: inv.customer_agent || '',
            second_invoice_ref: inv.second_invoice_ref || inv.ref2 || '',
            issued_by: inv.issued_by || '',
            issued_date: inv.issued_date || '',
            amended_by: inv.amended_by || '',
            amended_date: inv.amended_date || '',
            cancelled_by: inv.cancelled_by || '',
            cancelled_date: inv.cancelled_date || '',
            printed_by: inv.printed_by || '',
            printed_date: inv.printed_date || '',
            posted_by: inv.posted_by || '',
            posted_date: inv.posted_date || '',
            reason: inv.cancelled_reason_1 || inv.cancelled_reason_2 || '',
        }));

        filteredInvoices.value = invoices.value;
    } catch (error) {
        console.error('Error fetching invoices:', error);
        invoices.value = [];
        filteredInvoices.value = [];
    }
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
        const payload = response.data;

        // Backend returns `{ data: [...] }`; also support direct array for flexibility
        const data = Array.isArray(payload) ? payload : (payload.data || []);

        customerAccounts.value = data;
        filteredCustomerAccounts.value = data; // Initially display all
    } catch (error) {
        console.error('Error fetching customer accounts:', error);
        // Optionally, show a notification to the user
    }
};

const filterCustomerAccounts = () => {
    const query = customerSearchQuery.value.toLowerCase();
    const source = Array.isArray(customerAccounts.value) ? customerAccounts.value : [];

    filteredCustomerAccounts.value = source.filter(customer => {
        const code = (customer.customer_code || '').toString().toLowerCase();
        const name = (customer.customer_name || '').toString().toLowerCase();
        return code.includes(query) || name.includes(query);
    });
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
        } else if (currentCustomerCodeTarget.value === 'customerCodeFrom' || currentCustomerCodeTarget.value === 'customerCodeTo') {
            emit('customerSelected', { customer: selectedCustomerAccount.value, target: currentCustomerCodeTarget.value });
        }
        closeAllModals(); // Close the customer account modal
        // Re-open the parent modal based on the specific scenario, if needed
        if (currentCustomerCodeTarget.value === 'masterCard') {
            showMasterCardSearchModal.value = true;
        } else if (currentCustomerCodeTarget.value === 'purchaseOrderRef') {
            showPurchaseOrderRefSearchModal.value = true;
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
