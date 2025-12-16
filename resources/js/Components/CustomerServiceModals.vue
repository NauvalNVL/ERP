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
                            <tr
                                v-for="order in filteredSalesOrders"
                                :key="order.so_number"
                                @click="selectSalesOrder(order)"
                                class="hover:bg-blue-50 cursor-pointer"
                                :class="{'bg-blue-100': selectedSalesOrder && selectedSalesOrder.so_number === order.so_number}"
                            >
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
                                <input
                                    type="text"
                                    class="form-input mt-1 block w-20 bg-gray-100"
                                    :value="selectedSalesOrder ? selectedSalesOrder.salesperson_code : ''"
                                    readonly
                                >
                                <input
                                    type="text"
                                    class="form-input mt-1 ml-2 block w-full bg-gray-100"
                                    :value="selectedSalesOrder ? selectedSalesOrder.salesperson_name : ''"
                                    readonly
                                >
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
                <button @click="confirmSalesOrderSelection" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors shadow">
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

    <!-- Customer Account Search Modal (reuse shared CustomerAccountModal component) -->
    <CustomerAccountModal
        v-if="showCustomerAccountSearchModal"
        :show="showCustomerAccountSearchModal"
        @close="showCustomerAccountSearchModal = false"
        @select="handleCustomerAccountSelected"
    />

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
                            <tr
                                v-for="doItem in filteredDeliveryOrders"
                                :key="doItem.do_no"
                                @click="selectDeliveryOrder(doItem)"
                                @dblclick="confirmDeliveryOrderFromRow(doItem)"
                                class="hover:bg-blue-50 cursor-pointer"
                                :class="{'bg-blue-100': selectedDeliveryOrder && selectedDeliveryOrder.do_no === doItem.do_no}"
                            >
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
                                <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr
                                v-for="invoice in filteredInvoices"
                                :key="invoice.invoice_no"
                                @click="selectInvoice(invoice)"
                                @dblclick="confirmInvoiceFromRow(invoice)"
                                class="hover:bg-blue-50 cursor-pointer"
                                :class="{'bg-blue-100': selectedInvoice && selectedInvoice.invoice_no === invoice.invoice_no}"
                            >
                                <td class="px-3 py-2 whitespace-nowrap text-sm font-medium text-gray-900">{{ invoice.invoice_no }}</td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900">{{ invoice.inv_date }}</td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900">{{ invoice.customer_code }}</td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900">{{ invoice.tax }}</td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900">{{ invoice.mode }}</td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900">{{ invoice.status }}</td>
                            </tr>
                            <tr v-if="filteredInvoices.length === 0">
                                <td colspan="6" class="px-3 py-2 text-center text-sm text-gray-500">No invoices found.</td>
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

    <!-- Master Card List Modal using UpdateMcModal (MCS Table) -->
    <UpdateMcModal
        v-if="showMasterCardListModal"
        :showErrorModal="false"
        :showSetupMcModal="false"
        :showSetupPdModal="false"
        :showMcsTableModal="showMasterCardListModal"
        :formData="{ ac: customerCodeMasterCard }"
        :mcComponents="[]"
        :mcLoaded="null"
        :zoomOption="'normal'"
        :mcsSortOption="mcsSortOption"
        :mcsSortOrder="mcsSortOrder"
        :mcsStatusFilter="mcsStatusFilter"
        :mcsSearchTerm="mcsSearchTerm"
        :mcsLoading="mcsLoading"
        :mcsError="mcsError"
        :mcsMasterCards="mcsMasterCards"
        :selectedMcs="selectedMcs"
        :mcsCurrentPage="mcsCurrentPage"
        :mcsLastPage="mcsLastPage"
        :productDesigns="[]"
        :paperFlutes="[]"
        :soValues="[]"
        :woValues="[]"
        @closeErrorModal="() => {}"
        @closeSetupMcModal="() => {}"
        @closeSetupPdModal="() => {}"
        @closeMcsTableModal="showMasterCardListModal = false"
        @selectComponent="() => {}"
        @setupPD="() => {}"
        @setupOthers="() => {}"
        @handleZoomChange="() => {}"
        @fetchMcsData="fetchMcsData"
        @selectMcsItem="selectedMcs = $event"
        @selectMcs="handleMasterCardSelected"
        @goToMcsPage="goToMcsPage"
        @updateSearchTerm="updateMcsSearchTerm"
        @updateSortOption="updateMcsSortOption"
        @update:mcsSortOrder="mcsSortOrder = $event"
        @update:mcsStatusFilter="mcsStatusFilter = $event"
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
import UpdateMcModal from '@/Components/UpdateMcModal.vue'
import CustomerAccountModal from '@/Components/customer-account-modal.vue'

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

const mcsSortOption = ref('mc_seq');
const mcsSortOrder = ref('asc');
const mcsStatusFilter = ref('Act');
const mcsSearchTerm = ref('');
const mcsLoading = ref(false);
const mcsError = ref('');
const mcsMasterCards = ref([]);
const selectedMcs = ref(null);
const mcsCurrentPage = ref(1);
const mcsLastPage = ref(1);

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
    const modalRefs = [
        showInitialSalesOrderModal,
        showOptionsModal,
        showSalesOrderSearchModal,
        showMasterCardSearchModal,
        showPurchaseOrderRefSearchModal,
        showCustomerAccountSearchModal,
        showInitialDeliveryOrderModal,
        showDeliveryOrderTableModal,
        showInitialInvoiceModal,
        showInvoiceTableModal,
        showMasterCardListModal,
    ];

    modalRefs.forEach(modal => {
        try {
            if (modal && typeof modal.value !== 'undefined') {
                modal.value = false;
            }
        } catch (e) {
            console.warn('Error closing modal:', e);
        }
    });

    const resetStates = [
        { ref: selectedSalesOrder, value: null },
        { ref: selectedCustomerAccount, value: null },
        { ref: currentCustomerCodeTarget, value: null },
        { ref: customerSearchQuery, value: '' },
        { ref: filteredCustomerAccounts, value: [] },
        { ref: masterCardSearch, value: '' },
        { ref: purchaseOrderRefSearch, value: '' },
        { ref: salesOrderSearch, value: { month: '', year: '', sequence: '' } }
    ];

    resetStates.forEach(({ ref, value }) => {
        try {
            if (ref && typeof ref.value !== 'undefined') {
                if (Array.isArray(ref.value)) {
                    ref.value = [];
                } else if (typeof value === 'object' && value !== null) {
                    ref.value = value;
                } else {
                    ref.value = value;
                }
            }
        } catch (e) {
            console.warn('Error resetting state:', e);
        }
    });
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
        const rawData = Array.isArray(payload) ? payload : (payload.data || []);

        // Normalisasi field supaya punya salesperson_name jika tersedia di backend
        const mapped = rawData.map(order => ({
            ...order,
            salesperson_name: order.salesperson_name || order.Salesperson_Name || order.SALESPERSON_NAME || ''
        }));

        salesOrders.value = mapped;
        filteredSalesOrders.value = mapped;
    } catch (error) {
        console.error('Error fetching sales orders:', error);
        salesOrders.value = [];
        filteredSalesOrders.value = [];
    }
};

const applyMasterCardSortToSalesOrders = () => {
    const data = Array.isArray(filteredSalesOrders.value) ? [...filteredSalesOrders.value] : [];
    const by = masterCardSortOption.value;

    const getValue = (obj, key) => {
        if (!obj || obj[key] === undefined || obj[key] === null) return '';
        return obj[key];
    };

    const compareByKeys = (a, b, keys) => {
        for (const key of keys) {
            const av = getValue(a, key).toString();
            const bv = getValue(b, key).toString();
            if (av < bv) return -1;
            if (av > bv) return 1;
        }
        return 0;
    };

    let sorted = data;

    if (by === '1-M/C Seq# + S/Order#') {
        sorted = data.sort((a, b) => compareByKeys(a, b, ['master_card_seq', 'so_number']));
    } else if (by === '2-M/C Seq# + P/Order#' || by === '2-M/C Seq# + P/O Ref#') {
        sorted = data.sort((a, b) => compareByKeys(a, b, ['master_card_seq', 'customer_po_number', 'so_number']));
    } else if (by === '3-Ext.Size + B/Q + Flute') {
        sorted = data.sort((a, b) => compareByKeys(a, b, ['master_card_model', 'order_quantity', 'p_design', 'so_number']));
    } else if (by === '4-Flute + B/Q + Ext.Size') {
        sorted = data.sort((a, b) => compareByKeys(a, b, ['p_design', 'order_quantity', 'master_card_model', 'so_number']));
    }

    filteredSalesOrders.value = sorted;
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
                salesperson_name: order_info.salesperson_name ?? selectedSalesOrder.value?.salesperson_name ?? '',
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

// Khusus Customer Service Sales Order Table: konfirmasi pilihan dan langsung
// memanggil API detail Customer Service, lalu emit ke parent agar membuka
// tampilan CPS (SODetailView).
const confirmSalesOrderSelection = async () => {
    if (!selectedSalesOrder.value || !selectedSalesOrder.value.so_number) {
        alert('Please select a Sales Order first');
        return;
    }

    const soNumber = selectedSalesOrder.value.so_number;

    try {
        emit('loading', true);

        const response = await axios.get(`/api/customer-service/sales-order/${encodeURIComponent(soNumber)}/detail`);

        if (response.data?.success && response.data.data) {
            emit('so-selected', response.data.data);
            closeAllModals();
        } else {
            alert('Failed to load Sales Order details');
        }
    } catch (error) {
        console.error('Error in confirmSalesOrderSelection:', error);
        alert('Error loading Sales Order details');
    } finally {
        emit('loading', false);
    }
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
    if (!customerCodeMasterCard.value) {
        console.warn('Customer code is required before browsing Master Cards');
        return;
    }

    if (masterCardStatus.value.active && !masterCardStatus.value.obsolete) {
        mcsStatusFilter.value = 'Act';
    } else if (!masterCardStatus.value.active && masterCardStatus.value.obsolete) {
        mcsStatusFilter.value = 'Obsolete';
    } else {
        mcsStatusFilter.value = 'all';
    }

    mcsCurrentPage.value = 1;
    showMasterCardListModal.value = true;
    fetchMcsData();
};

const handleMasterCardSelected = (mc) => {
    if (!mc) return;
    const seq = mc.seq || mc.mc_seq || mc.mc_sequence || '';
    masterCardSearch.value = String(seq);
    showMasterCardListModal.value = false;
};

const mapMcsRows = (rows) => {
    if (!Array.isArray(rows)) return [];
    return rows.map(r => ({
        seq: r.seq ?? r.mc_seq ?? r.mc_sequence ?? '',
        model: r.model ?? r.mc_model ?? '',
        part: r.part ?? r.part_no ?? r.part_num ?? '',
        comp: r.comp ?? r.comp_no ?? r.comp_num ?? r.component ?? '',
        p_design: r.p_design ?? r.pd ?? '',
        ext_dim_1: r.ext_dim_1 ?? r.ed_l ?? r.ext_l ?? '',
        ext_dim_2: r.ext_dim_2 ?? r.ed_w ?? r.ext_w ?? '',
        ext_dim_3: r.ext_dim_3 ?? r.ed_h ?? r.ext_h ?? '',
        int_dim_1: r.int_dim_1 ?? r.id_l ?? '',
        int_dim_2: r.int_dim_2 ?? r.id_w ?? '',
        int_dim_3: r.int_dim_3 ?? r.id_h ?? '',
        status: r.status ?? r.sts ?? '',
        mc_approval: r.mc_approval ?? r.approval ?? 'No',
    }));
};

const fetchMcsData = async (page = 1) => {
    mcsLoading.value = true;
    mcsError.value = '';

    if (!customerCodeMasterCard.value) {
        mcsError.value = 'Please select Customer Code first.';
        mcsMasterCards.value = [];
        mcsLoading.value = false;
        return;
    }

    try {
        const params = {
            customer_code: customerCodeMasterCard.value,
            sortBy: mcsSortOption.value || 'mc_seq',
            sortOrder: mcsSortOrder.value || 'asc',
            query: mcsSearchTerm.value || '',
            page: page || 1,
            per_page: 10,
        };

        if (mcsStatusFilter.value === 'Act') {
            params.status = ['Act'];
        } else if (mcsStatusFilter.value === 'Obsolete') {
            params.status = ['Obsolete'];
        }

        const response = await axios.get('/api/update-mc/master-cards', { params });
        const data = response.data;

        if (Array.isArray(data?.data)) {
            mcsMasterCards.value = mapMcsRows(data.data);
            mcsCurrentPage.value = data.current_page || 1;
            mcsLastPage.value = data.last_page || 1;
        } else if (Array.isArray(data)) {
            mcsMasterCards.value = mapMcsRows(data);
            mcsCurrentPage.value = 1;
            mcsLastPage.value = 1;
        } else {
            mcsMasterCards.value = [];
            mcsCurrentPage.value = 1;
            mcsLastPage.value = 1;
        }
    } catch (e) {
        console.error('Error fetching MC data:', e);
        mcsError.value = e?.message || 'Failed to load Master Cards';
        mcsMasterCards.value = [];
    } finally {
        mcsLoading.value = false;
    }
};

const goToMcsPage = (page) => {
    if (page < 1 || page > mcsLastPage.value) return;
    mcsCurrentPage.value = page;
    fetchMcsData(page);
};

const updateMcsSearchTerm = (val) => {
    mcsSearchTerm.value = val || '';
};

const updateMcsSortOption = (val) => {
    mcsSortOption.value = val || 'mc_seq';
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

const fetchDeliveryOrders = async (extraParams = {}) => {
    try {
        const response = await axios.get('/api/invoices/delivery-orders', { params: extraParams });
        const data = response.data;

        const rawOrders = Array.isArray(data) ? data : (data.data || []);

        deliveryOrders.value = rawOrders.map(order => ({
            do_no: order.do_number || order.DO_Num || '',
            do_date: order.do_date || order.DO_DMY || '',
            customer: order.customer_code || order.AC_Num || '',
            vehicle_no: order.vehicle_no || order.DO_VHC_Num || '',
            vehicle_class: order.vehicle_class || order.VHC_Class || '',
            item_no: typeof order.item_count !== 'undefined' ? order.item_count : (order.item_no || 1),
            pc_mode: order.mode || order.pc_mode || '',
            status: order.status || order.Status || '',
            so_number: order.so_number || order.SO_Num || '',
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

const confirmDeliveryOrderFromRow = async (doItem) => {
    selectDeliveryOrder(doItem);
    await performSearch();
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

const fetchInvoices = async (extraParams = {}) => {
    try {
        const response = await axios.get('/api/invoices', { params: extraParams });
        const payload = response.data;
        const data = Array.isArray(payload) ? payload : (payload.data || []);

        invoices.value = data.map(inv => ({
            // Core fields from backend
            invoice_no: inv.invoice_no || inv.IV_NUM || '',
            inv_date: inv.invoice_date || inv.inv_date || '',
            customer_code: inv.customer_code || inv.AC_NUM || '',
            tax: inv.tax_code || inv.tax || '',
            mode: inv.mode || 'Manual',
            status: inv.status ?? '',


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

const confirmInvoiceFromRow = async (invoice) => {
    selectInvoice(invoice);
    await performSearch();
};

// New: Customer Account Search Modal functions
const openCustomerAccountSearchModal = async (targetInput) => {
    closeAllModals(); // Close all other modals first
    currentCustomerCodeTarget.value = targetInput; // Set the target input field
    showCustomerAccountSearchModal.value = true;
    selectedCustomerAccount.value = null; // Clear any previous selection
    customerSearchQuery.value = ''; // Clear search query
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

const performSearch = async () => {
    // Handle different modals
    try {
        // 1. Handle initial "Search by Sales Order" dialog (OK button)
        if (showInitialSalesOrderModal.value) {
            // Validate inputs
            if (!salesOrderParts.value.part1 || !salesOrderParts.value.part2 || !salesOrderParts.value.part3) {
                alert('Please fill in all required fields');
                return;
            }

            // Format SO number (MM-YYYY-SSSSS)
            const soNumber = [
                String(salesOrderParts.value.part1).padStart(2, '0'),
                String(salesOrderParts.value.part2).padStart(4, '0'),
                String(salesOrderParts.value.part3).padStart(5, '0')
            ].join('-');

            // Close all modals first
            closeAllModals();

            try {
                // Show loading state if needed
                emit('loading', true);

                const response = await axios.get(`/api/customer-service/sales-order/${encodeURIComponent(soNumber)}/detail`);

                if (response.data?.success && response.data.data) {
                    // Emit event to parent to show SO details
                    emit('so-selected', response.data.data);
                } else {
                    // Show error message and reopen search dialog
                    alert('Sales Order not found');
                    showInitialSalesOrderModal.value = true;
                }
            } catch (error) {
                console.error('Error fetching SO details:', error);
                let errorMessage = 'Failed to load Sales Order details';

                if (error.response) {
                    // Server responded with error status
                    errorMessage = error.response.data?.message || errorMessage;
                } else if (error.request) {
                    // No response received
                    errorMessage = 'No response from server. Please check your connection.';
                }

                alert(errorMessage);
                showInitialSalesOrderModal.value = true;
            } finally {
                emit('loading', false);
            }
            return;
        }

        // 6. Handle "Select" in Invoice Table modal
        if (showInvoiceTableModal.value) {
            const selected = selectedInvoice.value;
            if (selected?.invoice_no) {
                const invoiceNo = selected.invoice_no;

                try {
                    emit('loading', true);

                    // Get invoice header (including linked SO number) from CPS-compatible API
                    const invoiceResponse = await axios.get(`/api/invoices/${encodeURIComponent(invoiceNo)}`);
                    const invoiceData = invoiceResponse.data || {};

                    const soNum = invoiceData.so_number || invoiceData.soNum || selected.so_number;

                    if (!soNum) {
                        alert('Sales Order number not found for selected Invoice');
                        showInvoiceTableModal.value = true;
                        return;
                    }

                    // Load Customer Service Sales Order detail using SO number from invoice
                    const soResponse = await axios.get(`/api/customer-service/sales-order/${encodeURIComponent(soNum)}/detail`);

                    if (soResponse.data?.success && soResponse.data.data) {
                        const payload = soResponse.data.data;

                        // Attach selected Invoice header info so dashboard can show invoice-related context if needed
                        payload.selected_invoice = {
                            invoice_no: invoiceData.invoice_no || invoiceNo,
                            invoice_date: invoiceData.invoice_date || selected.inv_date,
                            customer_code: invoiceData.customer_code || selected.customer_code,
                            customer_name: invoiceData.customer_name || selected.customer_name_detail,
                            tax_code: invoiceData.tax_code || selected.tax,
                            order_mode: invoiceData.order_mode || selected.order_mode,
                            status: invoiceData.status || selected.status,
                            so_number: soNum,
                            do_number: invoiceData.do_number || '',
                            issued_by: invoiceData.issued_by || selected.issued_by,
                            issued_date: invoiceData.issued_date || selected.issued_date,
                            amended_by: invoiceData.amended_by || selected.amended_by,
                            amended_date: invoiceData.amended_date || selected.amended_date,
                            cancelled_by: invoiceData.cancelled_by || selected.cancelled_by,
                            cancelled_date: invoiceData.cancelled_date || selected.cancelled_date,
                            printed_by: invoiceData.printed_by || selected.printed_by,
                            printed_date: invoiceData.printed_date || selected.printed_date,
                            posted_by: invoiceData.posted_by || selected.posted_by,
                            posted_date: invoiceData.posted_date || selected.posted_date,
                            second_ref: invoiceData.second_ref || invoiceData.ref2 || selected.second_invoice_ref,
                            reason: invoiceData.cancelled_reason_1 || invoiceData.cancelled_reason_2 || selected.reason,
                        };

                        closeAllModals();
                        emit('so-selected', payload);
                    } else {
                        alert('Sales Order not found for selected Invoice');
                        showInvoiceTableModal.value = true;
                    }
                } catch (error) {
                    console.error('Error fetching SO details from Invoice selection:', error);
                    alert('Failed to load Sales Order details for selected Invoice');
                    showInvoiceTableModal.value = true;
                } finally {
                    emit('loading', false);
                }
            } else {
                alert('Please select an Invoice');
            }
            return;
        }

        // 2. Handle initial "Search by Delivery Order" dialog (OK button)
        if (showInitialDeliveryOrderModal.value) {
            if (!deliveryOrderParts.value.part1 || !deliveryOrderParts.value.part2 || !deliveryOrderParts.value.part3) {
                alert('Please fill in all required fields');
                return;
            }

            // Format DO number (MM-YYYY-SSSSS)
            const doNumber = [
                String(deliveryOrderParts.value.part1).padStart(2, '0'),
                String(deliveryOrderParts.value.part2).padStart(4, '0'),
                String(deliveryOrderParts.value.part3).padStart(5, '0')
            ].join('-');

            await fetchDeliveryOrders({ do_number: doNumber });

            if (!Array.isArray(deliveryOrders.value) || deliveryOrders.value.length === 0) {
                alert('Delivery Order not found');
                showInitialDeliveryOrderModal.value = true;
                return;
            }

            // Open Delivery Order Table with filtered result
            closeAllModals();
            showDeliveryOrderTableModal.value = true;
            return;
        }

        // 2b. Handle initial "Search by Invoice" dialog (OK button)
        if (showInitialInvoiceModal.value) {
            if (!invoiceParts.value.part1 || !invoiceParts.value.part2 || !invoiceParts.value.part3) {
                alert('Please fill in all required fields');
                return;
            }

            // Format invoice parts to match CPS style (MM-YYYY-SSSSS)
            const mm = String(invoiceParts.value.part1).padStart(2, '0');
            const yyyy = String(invoiceParts.value.part2).padStart(4, '0');
            const seq = String(invoiceParts.value.part3).padStart(5, '0');

            await fetchInvoices({ mm, yyyy, seq });

            if (!Array.isArray(invoices.value) || invoices.value.length === 0) {
                alert('Invoice not found');
                showInitialInvoiceModal.value = true;
                return;
            }

            // Open Invoice Table with filtered result
            closeAllModals();
            showInvoiceTableModal.value = true;
            return;
        }

        // 3. Handle OK in Master Card Search modal (Search by Master Card flow)
        if (showMasterCardSearchModal.value) {
            if (!customerCodeMasterCard.value && !masterCardSearch.value) {
                alert('Please input Customer Code or M/Card Seq#');
                return;
            }

            const statusList = [];
            if (salesOrderStatus.value.outstanding) statusList.push('outstanding');
            if (salesOrderStatus.value.partialCompleted) statusList.push('partial');
            if (salesOrderStatus.value.closedManually) statusList.push('closed');
            if (salesOrderStatus.value.completed) statusList.push('completed');
            if (salesOrderStatus.value.cancelled) statusList.push('cancelled');

            const extraParams = {};
            if (customerCodeMasterCard.value) extraParams.customer_code = customerCodeMasterCard.value;
            if (masterCardSearch.value) extraParams.master_card_seq = masterCardSearch.value;
            if (statusList.length > 0) extraParams.status = statusList;

            closeAllModals();
            showSalesOrderSearchModal.value = true;

            await fetchSalesOrders(extraParams);
            applyMasterCardSortToSalesOrders();

            return;
        }

        // 4. Handle "Select" in Sales Order Table modal
        if (showSalesOrderSearchModal.value) {
            if (selectedSalesOrder.value?.so_number) {
                const soNum = selectedSalesOrder.value.so_number;
                const segments = soNum.split('-');

                if (segments.length >= 3) {
                    // Update the search fields with selected SO
                    salesOrderParts.value = {
                        part1: segments[0] || '0',
                        part2: segments[1] || '0',
                        part3: String(parseInt(segments[2] || '0') || 0).padStart(5, '0'),
                        part4: 'mm-yyyy-ccccc',
                    };

                    // Close table and show initial search with pre-filled values
                    closeAllModals();
                    showInitialSalesOrderModal.value = true;
                } else {
                    alert('Invalid Sales Order number format');
                }
            } else {
                alert('Please select a Sales Order');
            }
            return;
        }

        // 5. Handle "Select" in Delivery Order Table modal
        if (showDeliveryOrderTableModal.value) {
            const selected = selectedDeliveryOrder.value;
            if (selected?.so_number) {
                const soNum = selected.so_number;

                try {
                    emit('loading', true);

                    const response = await axios.get(`/api/customer-service/sales-order/${encodeURIComponent(soNum)}/detail`);

                    if (response.data?.success && response.data.data) {
                        const payload = response.data.data;

                        // Attach selected Delivery Order header info so dashboard can show vehicle, salesperson, etc.
                        payload.selected_delivery_order = {
                            do_number: selected.do_no,
                            do_date: selected.do_date,
                            customer_code: selected.customer,
                            customer_name: selected.customer_name_detail,
                            vehicle_no: selected.vehicle_no,
                            vehicle_class: selected.vehicle_class,
                            item_count: selected.item_no,
                            pc_mode: selected.pc_mode,
                            status: selected.status,
                            salesperson_code: selected.salesperson_code,
                            cticket_no: selected.cticket_no,
                            on_hold: selected.on_hold,
                            order_mode: selected.order_mode,
                            agent_cust: selected.agent_cust,
                            sales_type: selected.sales_type,
                            do_inst1: selected.do_inst1,
                            do_inst2: selected.do_inst2,
                            prepared_by: selected.prepared_by,
                            prepared_date: selected.prepared_date,
                            cancelled_by: selected.cancelled_by,
                            cancelled_date: selected.cancelled_date,
                            amended_by: selected.amended_by,
                            amended_date: selected.amended_date,
                            printed_by: selected.printed_by,
                            printed_date: selected.printed_date,
                        };

                        closeAllModals();
                        emit('so-selected', payload);
                    } else {
                        alert('Sales Order not found for selected Delivery Order');
                        showDeliveryOrderTableModal.value = true;
                    }
                } catch (error) {
                    console.error('Error fetching SO details from Delivery Order selection:', error);
                    alert('Failed to load Sales Order details for selected Delivery Order');
                    showDeliveryOrderTableModal.value = true;
                } finally {
                    emit('loading', false);
                }
            } else {
                alert('Please select a Delivery Order');
            }
            return;
        }

        // Default: close all modals for other cases
        closeAllModals();
    } catch (error) {
        console.error('Error in performSearch:', error);
        closeAllModals();
    }
};

// New function to open Sales Order Table from table icon
const openSalesOrderTable = async () => {
    try {
        closeAllModals();
        showSalesOrderSearchModal.value = true;
        await fetchSalesOrders({
            month: salesOrderParts.value.part1,
            year: salesOrderParts.value.part2,
            sequence: salesOrderParts.value.part3
        });
    } catch (error) {
        console.error('Error opening sales order table:', error);
    }
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

// Declare emits so Vue recognizes listeners passed from parent (e.g. @show-notification, @so-selected)
const emit = defineEmits(['customerSelected', 'showNotification', 'so-selected', 'loading']);

const handleCustomerAccountSelected = (customer) => {
    if (!customer) {
        return;
    }

    // Keep last selected account for potential reuse
    selectedCustomerAccount.value = customer;

    const code = customer.customer_code || customer.code || customer.AC_Num || '';

    if (currentCustomerCodeTarget.value === 'masterCard') {
        // Fill customer code in Master Card# modal
        customerCodeMasterCard.value = code;
        showCustomerAccountSearchModal.value = false;
        // Return user back to Master Card# search like CPS
        showMasterCardSearchModal.value = true;
    } else if (currentCustomerCodeTarget.value === 'purchaseOrderRef') {
        // Fill customer code in P/Order Ref# modal
        customerCodePurchaseOrderRef.value = code;
        showCustomerAccountSearchModal.value = false;
        showPurchaseOrderRefSearchModal.value = true;
    } else if (
        currentCustomerCodeTarget.value === 'customerCodeFrom' ||
        currentCustomerCodeTarget.value === 'customerCodeTo'
    ) {
        // For other flows, let parent decide how to use the selected customer
        emit('customerSelected', { customer, target: currentCustomerCodeTarget.value });
        showCustomerAccountSearchModal.value = false;
    } else {
        // Default: just close the modal
        showCustomerAccountSearchModal.value = false;
    }
};

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
