<template>
    <AppLayout :header="'Print SO Report'">
        <Head title="Print SO Report" />

        <!-- Header Section -->
        <div class="bg-gradient-to-r from-cyan-700 to-blue-600 p-6 rounded-t-lg shadow-lg">
            <h2 class="text-2xl font-bold text-white mb-2 flex items-center">
                <i class="fas fa-print mr-3"></i> Print SO Report
            </h2>
            <p class="text-cyan-100">Generate and print Sales Order reports.</p>
        </div>

        <div class="bg-white rounded-b-lg shadow-lg p-6 mb-6">
            <!-- Form Section -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <div class="flex items-center">
                    <label for="currentSalesPeriod" class="w-36 text-gray-700 font-semibold">Current Sales Period:</label>
                    <input
                        type="text"
                        id="currentSalesPeriod"
                        v-model="form.currentSalesPeriod"
                        class="flex-1 rounded-md border-2 border-gray-400 py-2 px-3 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                        placeholder="mm yyyy"
                    />
                </div>

                <div></div> <!-- Placeholder for layout alignment -->

                <div class="flex items-center">
                    <label for="salesOrderFrom1" class="w-36 text-gray-700 font-semibold">Sales Order#:</label>
                    <input type="text" id="salesOrderFrom1" v-model="form.salesOrderFrom1" class="w-16 rounded-md border-2 border-gray-400 py-2 px-3 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm mr-1" />
                    <input type="text" id="salesOrderFrom2" v-model="form.salesOrderFrom2" class="w-16 rounded-md border-2 border-gray-400 py-2 px-3 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm mr-1" />
                    <button @click="openSearchOptionsModal" class="p-2 rounded-md border border-gray-300 bg-white hover:bg-gray-50 text-gray-500 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 mr-2">
                        <i class="fas fa-search"></i>
                    </button>
                    <span class="mx-1">to</span>
                    <input type="text" id="salesOrderTo1" v-model="form.salesOrderTo1" class="w-16 rounded-md border-2 border-gray-400 py-2 px-3 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm mr-1" />
                    <input type="text" id="salesOrderTo2" v-model="form.salesOrderTo2" class="w-16 rounded-md border-2 border-gray-400 py-2 px-3 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm mr-1" />
                    <button @click="openSearchOptionsModal" class="p-2 rounded-md border border-gray-300 bg-white hover:bg-gray-50 text-gray-500 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                        <i class="fas fa-search"></i>
                    </button>
                </div>

                <div></div> <!-- Placeholder for layout alignment -->

                <div class="flex items-center">
                    <label for="salespersonCodeFrom" class="w-36 text-gray-700 font-semibold">Salesperson Code:</label>
                    <input
                        type="text"
                        id="salespersonCodeFrom"
                        v-model="form.salespersonCodeFrom"
                        class="flex-1 rounded-md border-2 border-gray-400 py-2 px-3 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                    />
                    <button class="ml-2 p-2 rounded-md border border-gray-300 bg-white hover:bg-gray-50 text-gray-500 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 mr-2">
                        <i class="fas fa-search"></i>
                    </button>
                    <span class="mx-1">to</span>
                    <input
                        type="text"
                        id="salespersonCodeTo"
                        v-model="form.salespersonCodeTo"
                        class="flex-1 rounded-md border-2 border-gray-400 py-2 px-3 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                    />
                    <button class="ml-2 p-2 rounded-md border border-gray-300 bg-white hover:bg-gray-50 text-gray-500 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                        <i class="fas fa-search"></i>
                    </button>
                </div>

                <div></div> <!-- Placeholder for layout alignment -->

                <div class="flex items-center">
                    <label for="customerCodeFrom" class="w-36 text-gray-700 font-semibold">Customer Code:</label>
                    <input
                        type="text"
                        id="customerCodeFrom"
                        v-model="form.customerCodeFrom"
                        class="flex-1 rounded-md border-2 border-gray-400 py-2 px-3 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                    />
                    <button class="ml-2 p-2 rounded-md border border-gray-300 bg-white hover:bg-gray-50 text-gray-500 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 mr-2">
                        <i class="fas fa-search"></i>
                    </button>
                    <span class="mx-1">to</span>
                    <input
                        type="text"
                        id="customerCodeTo"
                        v-model="form.customerCodeTo"
                        class="flex-1 rounded-md border-2 border-gray-400 py-2 px-3 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                    />
                    <button class="ml-2 p-2 rounded-md border border-gray-300 bg-white hover:bg-gray-50 text-gray-500 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                        <i class="fas fa-search"></i>
                    </button>
                </div>

                <div></div> <!-- Placeholder for layout alignment -->

                <div class="flex items-center col-span-2">
                    <label class="w-36 text-gray-700 font-semibold">Sales Order Status:</label>
                    <div class="flex flex-wrap gap-x-4">
                        <label class="inline-flex items-center">
                            <input type="checkbox" v-model="form.status.outstanding" class="form-checkbox h-5 w-5 text-blue-600 rounded" />
                            <span class="ml-2 text-gray-700">Outstanding</span>
                        </label>
                        <label class="inline-flex items-center">
                            <input type="checkbox" v-model="form.status.partial" class="form-checkbox h-5 w-5 text-blue-600 rounded" />
                            <span class="ml-2 text-gray-700">Partial</span>
                        </label>
                        <label class="inline-flex items-center">
                            <input type="checkbox" v-model="form.status.closed" class="form-checkbox h-5 w-5 text-blue-600 rounded" />
                            <span class="ml-2 text-gray-700">Closed</span>
                        </label>
                        <label class="inline-flex items-center">
                            <input type="checkbox" v-model="form.status.completed" class="form-checkbox h-5 w-5 text-blue-600 rounded" />
                            <span class="ml-2 text-gray-700">Completed</span>
                        </label>
                        <label class="inline-flex items-center">
                            <input type="checkbox" v-model="form.status.cancelled" class="form-checkbox h-5 w-5 text-blue-600 rounded" />
                            <span class="ml-2 text-gray-700">Cancelled</span>
                        </label>
                    </div>
                </div>

            </div>

            <!-- Proceed Button at the bottom right -->
            <div class="flex justify-end mt-8">
                <button @click="proceed" class="btn-blue py-2 px-6 rounded-md text-white font-semibold flex items-center">
                    Proceed
                </button>
            </div>

        </div>

        <!-- Search Options Modal -->
        <div v-if="showSearchOptionsModal" class="fixed inset-0 bg-gray-800 bg-opacity-60 flex items-center justify-center z-50 transition-opacity duration-300 ease-out">
            <div class="bg-white p-8 rounded-xl shadow-2xl border border-gray-200 w-96 transform scale-95 opacity-0 transition-all duration-300 ease-out" :class="{'scale-100 opacity-100': showSearchOptionsModal}">
                <h3 class="text-2xl font-bold text-gray-800 mb-6 text-center">Options</h3>
                <div class="space-y-5 mb-8">
                    <label class="flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-blue-50 transition-colors duration-200">
                        <input type="radio" class="form-radio h-5 w-5 text-blue-600 focus:ring-blue-500" value="salesOrder" v-model="selectedSearchOption" />
                        <span class="ml-3 text-lg font-medium text-gray-800">Sales Order#</span>
                    </label>
                    <label class="flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-blue-50 transition-colors duration-200">
                        <input type="radio" class="form-radio h-5 w-5 text-blue-600 focus:ring-blue-500" value="masterCard" v-model="selectedSearchOption" />
                        <span class="ml-3 text-lg font-medium text-gray-800">Master Card#</span>
                    </label>
                    <label class="flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-blue-50 transition-colors duration-200">
                        <input type="radio" class="form-radio h-5 w-5 text-blue-600 focus:ring-blue-500" value="pOrderRef" v-model="selectedSearchOption" />
                        <span class="ml-3 text-lg font-medium text-gray-800">P/Order Ref#</span>
                    </label>
                </div>
                <div class="flex justify-end space-x-4">
                    <button @click="applySearchOption" class="btn-blue py-3 px-6 rounded-lg text-lg font-semibold shadow-md hover:shadow-lg transition-all duration-200">OK</button>
                    <button @click="closeSearchOptionsModal" class="btn-red py-3 px-6 rounded-lg text-lg font-semibold shadow-md hover:shadow-lg transition-all duration-200">Exit</button>
                </div>
            </div>
        </div>

        <!-- Dynamic Search Form Modals -->
        <!-- Sales Order Table Modal (Image 2) -->
        <div v-if="showSalesOrderTableModal" class="fixed inset-0 bg-gray-800 bg-opacity-60 flex items-center justify-center z-50 transition-opacity duration-300 ease-out">
            <div class="bg-white p-8 rounded-xl shadow-2xl border border-gray-200 w-full max-w-5xl h-[90vh] overflow-y-auto transform scale-95 opacity-0 transition-all duration-300 ease-out" :class="{'scale-100 opacity-100': showSalesOrderTableModal}">
                <h3 class="text-2xl font-bold text-gray-800 mb-6 text-center">Sales Order Table (Sorted by S/Order#)</h3>
                <div class="mb-6">
                    <!-- Table Header -->
                    <div class="grid grid-cols-7 gap-x-3 border-b-2 border-gray-400 pb-3 mb-3 font-extrabold text-gray-700 text-sm uppercase">
                        <div class="col-span-1">SO#</div>
                        <div class="col-span-1">CUSTOMER PO#</div>
                        <div class="col-span-1">AC#</div>
                        <div class="col-span-1">MC#</div>
                        <div class="col-span-1">STATUS</div>
                        <div class="col-span-1">D/LOCATION</div>
                        <div class="col-span-1"></div>
                    </div>
                    <!-- Sample Table Rows -->
                    <div v-for="so in salesOrderData" :key="so.so_number" class="grid grid-cols-7 gap-x-3 py-2 border-b border-gray-200 text-sm text-gray-700 hover:bg-gray-50 transition-colors duration-150 cursor-pointer">
                        <div class="col-span-1">{{ so.so_number }}</div>
                        <div class="col-span-1">{{ so.customer_po }}</div>
                        <div class="col-span-1">{{ so.ac_number }}</div>
                        <div class="col-span-1">{{ so.mc_number }}</div>
                        <div class="col-span-1">{{ so.status }}</div>
                        <div class="col-span-1">{{ so.d_location }}</div>
                        <div class="col-span-1"><i class="fas fa-external-link-alt text-blue-500 hover:text-blue-700"></i></div>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-x-6 gap-y-4 mb-6">
                    <div class="flex items-center">
                        <label for="searchSo" class="w-20 text-gray-700 font-semibold">Search:</label>
                        <input type="text" id="searchSo1" class="w-16 rounded-md border-2 border-gray-400 py-2 px-3 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm mr-1" />
                        <input type="text" id="searchSo2" class="w-16 rounded-md border-2 border-gray-400 py-2 px-3 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm mr-1" />
                        <input type="text" id="searchSo3" class="w-16 rounded-md border-2 border-gray-400 py-2 px-3 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm mr-2" />
                        <button class="p-2 rounded-md border border-gray-300 bg-white hover:bg-gray-100 text-gray-700 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 shadow-sm">
                            Search
                        </button>
                        <span class="ml-4 text-gray-700 font-semibold">S/Order#</span>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-x-6 gap-y-4 mb-6">
                    <div class="flex items-center col-span-2">
                        <label for="customerName" class="w-36 text-gray-700 font-semibold">Customer Name:</label>
                        <input type="text" id="customerName" class="flex-1 rounded-md border-2 border-gray-400 py-2 px-3 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" />
                    </div>
                    <div class="flex items-center col-span-2">
                        <label for="model" class="w-36 text-gray-700 font-semibold">Model:</label>
                        <input type="text" id="model" class="flex-1 rounded-md border-2 border-gray-400 py-2 px-3 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" />
                    </div>
                    <div class="flex items-center col-span-2">
                        <label for="orderMode" class="w-36 text-gray-700 font-semibold">Order Mode:</label>
                        <input type="text" id="orderMode" class="flex-1 rounded-md border-2 border-gray-400 py-2 px-3 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" />
                    </div>
                    <div class="flex items-center col-span-2">
                        <label for="salesperson" class="w-36 text-gray-700 font-semibold">Salesperson:</label>
                        <input type="text" id="salesperson" class="w-24 rounded-md border-2 border-gray-400 py-2 px-3 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm mr-2" />
                        <input type="text" id="salespersonName" class="flex-1 rounded-md border-2 border-gray-400 py-2 px-3 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" />
                    </div>
                    <div class="flex items-center col-span-2">
                        <label for="orderGroup" class="w-36 text-gray-700 font-semibold">Order Group:</label>
                        <input type="text" id="orderGroup" class="w-24 rounded-md border-2 border-gray-400 py-2 px-3 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm mr-2" />
                        <span class="text-gray-700 font-semibold mr-2">Order Type:</span>
                        <input type="text" id="orderType" class="w-24 rounded-md border-2 border-gray-400 py-2 px-3 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" />
                    </div>
                </div>

                <h4 class="text-xl font-bold text-gray-800 mb-4">ITEM</h4>
                <div class="mb-6">
                    <!-- Item Table Header -->
                    <div class="grid grid-cols-10 gap-x-3 border-b-2 border-gray-400 pb-3 mb-3 font-extrabold text-gray-700 text-sm uppercase">
                        <div class="col-span-1">PD</div>
                        <div class="col-span-1">MAIN</div>
                        <div class="col-span-1">F1</div>
                        <div class="col-span-1">F2</div>
                        <div class="col-span-1">F3</div>
                        <div class="col-span-1">F4</div>
                        <div class="col-span-1">F5</div>
                        <div class="col-span-1">F6</div>
                        <div class="col-span-1">F7</div>
                        <div class="col-span-1">F8</div>
                    </div>
                    <!-- Sample Item Rows -->
                    <div v-for="(item, index) in itemData" :key="index" class="grid grid-cols-10 gap-x-3 py-2 border-b border-gray-200 text-sm text-gray-700 hover:bg-gray-50 transition-colors duration-150 cursor-pointer">
                        <div class="col-span-1">{{ item.pd }}</div>
                        <div class="col-span-1">{{ item.main }}</div>
                        <div class="col-span-1">{{ item.f1 }}</div>
                        <div class="col-span-1">{{ item.f2 }}</div>
                        <div class="col-span-1">{{ item.f3 }}</div>
                        <div class="col-span-1">{{ item.f4 }}</div>
                        <div class="col-span-1">{{ item.f5 }}</div>
                        <div class="col-span-1">{{ item.f6 }}</div>
                        <div class="col-span-1">{{ item.f7 }}</div>
                        <div class="col-span-1">{{ item.f8 }}</div>
                    </div>
                </div>

                <div class="grid grid-cols-4 gap-x-6 gap-y-4 mb-6">
                    <div class="flex items-center">
                        <label for="pcs" class="w-20 text-gray-700 font-semibold">PCS:</label>
                        <input type="text" id="pcs" class="flex-1 rounded-md border-2 border-gray-400 py-2 px-3 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" />
                    </div>
                    <div class="flex items-center">
                        <label for="unit" class="w-20 text-gray-700 font-semibold">UNIT:</label>
                        <input type="text" id="unit" class="flex-1 rounded-md border-2 border-gray-400 py-2 px-3 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" />
                    </div>
                    <div class="flex items-center">
                        <label for="netDelivery" class="w-28 text-gray-700 font-semibold">NET DELIVERY:</label>
                        <input type="text" id="netDelivery" class="flex-1 rounded-md border-2 border-gray-400 py-2 px-3 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" />
                    </div>
                    <div class="flex items-center">
                        <label for="balance" class="w-20 text-gray-700 font-semibold">BALANCE:</label>
                        <input type="text" id="balance" class="flex-1 rounded-md border-2 border-gray-400 py-2 px-3 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" />
                    </div>
                </div>

                <div class="flex justify-end space-x-4 mt-8">
                    <button @click="zoomSo" class="btn-blue py-3 px-6 rounded-lg text-lg font-semibold shadow-md hover:shadow-lg transition-all duration-200">Zoom</button>
                    <button @click="selectSo" class="btn-blue py-3 px-6 rounded-lg text-lg font-semibold shadow-md hover:shadow-lg transition-all duration-200">Select</button>
                    <button @click="closeSalesOrderTableModal" class="btn-red py-3 px-6 rounded-lg text-lg font-semibold shadow-md hover:shadow-lg transition-all duration-200">Exit</button>
                </div>
            </div>
        </div>

        <!-- Master Card Search Modal (Image 3) -->
        <div v-if="showMasterCardSearchModal" class="fixed inset-0 bg-gray-800 bg-opacity-60 flex items-center justify-center z-50 transition-opacity duration-300 ease-out">
            <div class="bg-white p-8 rounded-xl shadow-2xl border border-gray-200 w-96 transform scale-95 opacity-0 transition-all duration-300 ease-out" :class="{'scale-100 opacity-100': showMasterCardSearchModal}">
                <h3 class="text-2xl font-bold text-gray-800 mb-6 text-center">Master Card#</h3>
                <div class="space-y-6 mb-8">
                    <div class="flex items-center">
                        <label for="customerCodeMc" class="w-36 text-gray-700 font-semibold">Customer Code:</label>
                        <input type="text" id="customerCodeMc" v-model="masterCardForm.customerCode" class="flex-1 rounded-md border-2 border-gray-400 py-2 px-3 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" />
                        <button class="ml-3 p-2 rounded-md border border-gray-300 bg-white hover:bg-gray-100 text-gray-700 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 shadow-sm">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                    <div class="flex items-center">
                        <label for="mcSeq" class="w-36 text-gray-700 font-semibold">M/Card Seq#:</label>
                        <input type="text" id="mcSeq" v-model="masterCardForm.mcSeq" class="flex-1 rounded-md border-2 border-gray-400 py-2 px-3 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" />
                        <button class="ml-3 p-2 rounded-md border border-gray-300 bg-white hover:bg-gray-100 text-gray-700 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 shadow-sm">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                    <div class="flex flex-col">
                        <label class="text-lg font-semibold text-gray-800 mb-3">Sort Option:</label>
                        <div class="flex flex-wrap gap-y-3 gap-x-6">
                            <label class="inline-flex items-center">
                                <input type="radio" class="form-radio h-5 w-5 text-blue-600 focus:ring-blue-500" value="1-MC Seq# + S/Order#" v-model="masterCardForm.sortOption" />
                                <span class="ml-2 text-gray-700">1-M/C Seq# + S/Order#</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="radio" class="form-radio h-5 w-5 text-blue-600 focus:ring-blue-500" value="2-M/C Seq# + P/O Ref#" v-model="masterCardForm.sortOption" />
                                <span class="ml-2 text-gray-700">2-M/C Seq# + P/O Ref#</span>
                            </label>
                        </div>
                    </div>
                    <div class="flex flex-col">
                        <label class="text-lg font-semibold text-gray-800 mb-3">S/Order Status:</label>
                        <div class="grid grid-cols-2 gap-y-3 gap-x-6">
                            <label class="inline-flex items-center">
                                <input type="checkbox" v-model="masterCardForm.status.outstanding" class="form-checkbox h-5 w-5 text-blue-600 rounded focus:ring-blue-500" />
                                <span class="ml-2 text-gray-700">Outstanding</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="checkbox" v-model="masterCardForm.status.partialCompleted" class="form-checkbox h-5 w-5 text-blue-600 rounded focus:ring-blue-500" />
                                <span class="ml-2 text-gray-700">Partial Completed</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="checkbox" v-model="masterCardForm.status.closedManually" class="form-checkbox h-5 w-5 text-blue-600 rounded focus:ring-blue-500" />
                                <span class="ml-2 text-gray-700">Closed Manually</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="checkbox" v-model="masterCardForm.status.completed" class="form-checkbox h-5 w-5 text-blue-600 rounded focus:ring-blue-500" />
                                <span class="ml-2 text-gray-700">Completed</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="checkbox" v-model="masterCardForm.status.cancelled" class="form-checkbox h-5 w-5 text-blue-600 rounded focus:ring-blue-500" />
                                <span class="ml-2 text-gray-700">Cancelled</span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="flex justify-end space-x-4">
                    <button @click="okMasterCard" class="btn-blue py-3 px-6 rounded-lg text-lg font-semibold shadow-md hover:shadow-lg transition-all duration-200">OK</button>
                    <button @click="closeMasterCardSearchModal" class="btn-red py-3 px-6 rounded-lg text-lg font-semibold shadow-md hover:shadow-lg transition-all duration-200">Exit</button>
                </div>
            </div>
        </div>

        <!-- P/Order Ref# Search Modal (Image 4) -->
        <div v-if="showPOrderRefSearchModal" class="fixed inset-0 bg-gray-800 bg-opacity-60 flex items-center justify-center z-50 transition-opacity duration-300 ease-out">
            <div class="bg-white p-8 rounded-xl shadow-2xl border border-gray-200 w-96 transform scale-95 opacity-0 transition-all duration-300 ease-out" :class="{'scale-100 opacity-100': showPOrderRefSearchModal}">
                <h3 class="text-2xl font-bold text-gray-800 mb-6 text-center">P/Order Ref#</h3>
                <div class="space-y-6 mb-8">
                    <div class="flex items-center">
                        <label for="customerCodePo" class="w-36 text-gray-700 font-semibold">Customer Code:</label>
                        <input type="text" id="customerCodePo" v-model="pOrderRefForm.customerCode" class="flex-1 rounded-md border-2 border-gray-400 py-2 px-3 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" />
                        <button class="ml-3 p-2 rounded-md border border-gray-300 bg-white hover:bg-gray-100 text-gray-700 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 shadow-sm">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                    <div class="flex items-center">
                        <label for="pOrderRef" class="w-36 text-gray-700 font-semibold">P/Order Ref#:</label>
                        <input type="text" id="pOrderRef" v-model="pOrderRefForm.pOrderRef" class="flex-1 rounded-md border-2 border-gray-400 py-2 px-3 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" />
                        <button class="ml-3 p-2 rounded-md border border-gray-300 bg-white hover:bg-gray-100 text-gray-700 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 shadow-sm">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                    <div class="flex flex-col">
                        <label class="text-lg font-semibold text-gray-800 mb-3">Sort Option:</label>
                        <div class="flex flex-wrap gap-y-3 gap-x-6">
                            <label class="inline-flex items-center">
                                <input type="radio" class="form-radio h-5 w-5 text-blue-600 focus:ring-blue-500" value="1-P/O Ref# + S/Order#" v-model="pOrderRefForm.sortOption" />
                                <span class="ml-2 text-gray-700">1-P/O Ref# + S/Order#</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="radio" class="form-radio h-5 w-5 text-blue-600 focus:ring-blue-500" value="2-P/O Ref# + M/C Seq#" v-model="pOrderRefForm.sortOption" />
                                <span class="ml-2 text-gray-700">2-P/O Ref# + M/C Seq#</span>
                            </label>
                        </div>
                    </div>
                    <div class="flex flex-col">
                        <label class="text-lg font-semibold text-gray-800 mb-3">S/Order Status:</label>
                        <div class="grid grid-cols-2 gap-y-3 gap-x-6">
                            <label class="inline-flex items-center">
                                <input type="checkbox" v-model="pOrderRefForm.status.outstanding" class="form-checkbox h-5 w-5 text-blue-600 rounded focus:ring-blue-500" />
                                <span class="ml-2 text-gray-700">Outstanding</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="checkbox" v-model="pOrderRefForm.status.partialCompleted" class="form-checkbox h-5 w-5 text-blue-600 rounded focus:ring-blue-500" />
                                <span class="ml-2 text-gray-700">Partial Completed</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="checkbox" v-model="pOrderRefForm.status.closedManually" class="form-checkbox h-5 w-5 text-blue-600 rounded focus:ring-blue-500" />
                                <span class="ml-2 text-gray-700">Closed Manually</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="checkbox" v-model="pOrderRefForm.status.completed" class="form-checkbox h-5 w-5 text-blue-600 rounded focus:ring-blue-500" />
                                <span class="ml-2 text-gray-700">Completed</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="checkbox" v-model="pOrderRefForm.status.cancelled" class="form-checkbox h-5 w-5 text-blue-600 rounded focus:ring-blue-500" />
                                <span class="ml-2 text-gray-700">Cancelled</span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="flex justify-end space-x-4">
                    <button @click="okPOrderRef" class="btn-blue py-3 px-6 rounded-lg text-lg font-semibold shadow-md hover:shadow-lg transition-all duration-200">OK</button>
                    <button @click="closePOrderRefSearchModal" class="btn-red py-3 px-6 rounded-lg text-lg font-semibold shadow-md hover:shadow-lg transition-all duration-200">Exit</button>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import axios from 'axios';
import { useToast } from '@/Composables/useToast';

const toast = useToast();

const form = ref({
    currentSalesPeriod: '',
    salesOrderFrom1: '', salesOrderFrom2: '',
    salesOrderTo1: '', salesOrderTo2: '',
    salespersonCodeFrom: '', salespersonCodeTo: '',
    customerCodeFrom: '', customerCodeTo: '',
    status: {
        outstanding: false,
        partial: false,
        closed: false,
        completed: false,
        cancelled: false,
    },
});

// Modal related states
const showSearchOptionsModal = ref(false);
const selectedSearchOption = ref('salesOrder'); // Default to Sales Order#

const showSalesOrderTableModal = ref(false);
const showMasterCardSearchModal = ref(false);
const showPOrderRefSearchModal = ref(false);

const masterCardForm = ref({
    customerCode: '',
    mcSeq: '',
    sortOption: '1-MC Seq# + S/Order#', // Default
    status: {
        outstanding: true,
        partialCompleted: true,
        closedManually: true,
        completed: true,
        cancelled: true,
    }
});

const pOrderRefForm = ref({
    customerCode: '',
    pOrderRef: '',
    sortOption: '1-P/O Ref# + S/Order#', // Default
    status: {
        outstanding: true,
        partialCompleted: true,
        closedManually: true,
        completed: true,
        cancelled: true,
    }
});

const salesOrderData = ref([
    { so_number: '06-2025-00585', customer_po: '03.2025.00568', ac_number: '000117.05', mc_number: '0069820', status: 'Outs', d_location: 'B105' },
    { so_number: '06-2025-00584', customer_po: 'PO 2025.06.00004', ac_number: '000576', mc_number: '263207', status: 'Outs', d_location: 'B102' },
    { so_number: '06-2025-00583', customer_po: 'PO 271-0625 05 JUNI 2025', ac_number: '000603-01', mc_number: '2657103', status: 'Outs', d_location: 'J001' },
    { so_number: '06-2025-00582', customer_po: 'PO 270-0625 05 JUNI 2025', ac_number: '000603-01', mc_number: '265744', status: 'Outs', d_location: 'J001' },
    { so_number: '06-2025-00581', customer_po: 'PO 269-0625 04 JUNI 2025', ac_number: '000603-01', mc_number: '2657106', status: 'Outs', d_location: 'J001' },
    { so_number: '06-2025-00580', customer_po: 'ZAENAL ROHMAN', ac_number: '000501-02', mc_number: '2470180', status: 'Outs', d_location: 'B103' },
    { so_number: '06-2025-00579', customer_po: 'PO05-SMCP2506', ac_number: '000947', mc_number: '297412', status: 'Outs', d_location: 'B103' },
    { so_number: '06-2025-00578', customer_po: 'PO05-SMCP2506', ac_number: '000947', mc_number: '297426', status: 'Outs', d_location: 'B103' },
    { so_number: '06-2025-00577', customer_po: '4280008972', ac_number: '000235', mc_number: '2064353', status: 'Outs', d_location: 'J204' },
    { so_number: '06-2025-00576', customer_po: '4000054614', ac_number: '000176', mc_number: 'OL-0000267', status: 'Outs', d_location: 'J001' },
]);

const itemData = ref([
    { pd: 'PD', main: 'B1', f1: '', f2: '', f3: '', f4: '', f5: '', f6: '', f7: '', f8: '' },
    { pd: 'UNIT', main: 'Pcs', f1: '', f2: '', f3: '', f4: '', f5: '', f6: '', f7: '', f8: '' },
    { pd: 'PCS', main: '1', f1: '', f2: '', f3: '', f4: '', f5: '', f6: '', f7: '', f8: '' },
    { pd: 'NET DELIVERY', main: '2,000', f1: '', f2: '', f3: '', f4: '', f5: '', f6: '', f7: '', f8: '' },
    { pd: 'BALANCE', main: '2,000', f1: '', f2: '', f3: '', f4: '', f5: '', f6: '', f7: '', f8: '' },
]);


const openSearchOptionsModal = () => {
    showSearchOptionsModal.value = true;
};

const closeSearchOptionsModal = () => {
    showSearchOptionsModal.value = false;
};

const applySearchOption = () => {
    closeSearchOptionsModal();
    // Reset all specific search modals
    showSalesOrderTableModal.value = false;
    showMasterCardSearchModal.value = false;
    showPOrderRefSearchModal.value = false;

    // Open the selected search modal
    if (selectedSearchOption.value === 'salesOrder') {
        showSalesOrderTableModal.value = true;
    } else if (selectedSearchOption.value === 'masterCard') {
        showMasterCardSearchModal.value = true;
    } else if (selectedSearchOption.value === 'pOrderRef') {
        showPOrderRefSearchModal.value = true;
    }
};

const closeSalesOrderTableModal = () => {
    showSalesOrderTableModal.value = false;
};

const closeMasterCardSearchModal = () => {
    showMasterCardSearchModal.value = false;
};

const closePOrderRefSearchModal = () => {
    showPOrderRefSearchModal.value = false;
};

// Dummy functions for the internal modal buttons
const zoomSo = () => toast.info('Zoom SO button clicked');
const selectSo = () => toast.info('Select SO button clicked');
const okMasterCard = () => toast.info('OK Master Card button clicked');
const okPOrderRef = () => toast.info('OK P/Order Ref button clicked');

const powerOff = () => {
    toast.info('Power Off button clicked');
    // Implement actual power off logic here, e.g., redirect or close application
};

const proceed = async () => {
    toast.info('Proceed button clicked. Implementing report generation logic.');
    console.log('Form data:', form.value);

    try {
        // Example API call to fetch report data
        const response = await axios.post('/api/so-report', form.value);
        toast.success('Report data fetched successfully!');
        // Handle the report data, e.g., display it or generate a PDF
        console.log('Report Data:', response.data);
    } catch (error) {
        console.error('Error fetching SO Report:', error);
        toast.error('Failed to fetch SO Report.');
    }
};

// Set initial current Sales Period to current month and year
onMounted(() => {
    const today = new Date();
    const month = String(today.getMonth() + 1).padStart(2, '0');
    const year = today.getFullYear();
    form.value.currentSalesPeriod = `${month} ${year}`;
    // Set all status checkboxes to true by default, matching the image
    form.value.status.outstanding = true;
    form.value.status.partial = true;
    form.value.status.closed = true;
    form.value.status.completed = true;
    form.value.status.cancelled = true;

    // Set default sort options and status checkboxes for the new forms
    masterCardForm.value.status.outstanding = true;
    masterCardForm.value.status.partialCompleted = true;
    masterCardForm.value.status.closedManually = true;
    masterCardForm.value.status.completed = true;
    masterCardForm.value.status.cancelled = true;

    pOrderRefForm.value.status.outstanding = true;
    pOrderRefForm.value.status.partialCompleted = true;
    pOrderRefForm.value.status.closedManually = true;
    pOrderRefForm.value.status.completed = true;
    pOrderRefForm.value.status.cancelled = true;
});
</script> 

<style scoped>
/* Add any specific styles for this component here */
.btn-red-icon {
    @apply bg-red-500 hover:bg-red-600 text-white p-2 rounded-full shadow-md transition-colors;
}
.btn-green-icon {
    @apply bg-green-500 hover:bg-green-600 text-white p-2 rounded-full shadow-md transition-colors;
}
.btn-blue {
    @apply bg-blue-500 hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2;
}
.btn-red {
    @apply bg-red-500 hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2;
}
</style> 