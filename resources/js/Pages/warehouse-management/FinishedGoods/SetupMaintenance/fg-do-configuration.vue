<template>
    <AppLayout :header="'Setup F/Goods & D/Order Configuration'">
        <!-- Header Section with animated elements, adapted from Update MC -->
        <div class="bg-gradient-to-r from-purple-600 via-indigo-600 to-blue-600 p-6 rounded-t-lg shadow-lg overflow-hidden relative">
            <!-- Decorative Elements -->
            <div class="absolute top-0 right-0 w-40 h-40 bg-white opacity-5 rounded-full -translate-y-20 translate-x-20"></div>
            <div class="absolute bottom-0 left-0 w-20 h-20 bg-white opacity-5 rounded-full translate-y-10 -translate-x-10"></div>
            <div class="absolute bottom-0 right-0 w-32 h-32 bg-yellow-400 opacity-5 rounded-full translate-y-10 translate-x-10"></div>
            
            <div class="flex items-center">
                <div class="bg-gradient-to-br from-pink-500 to-purple-600 p-3 rounded-lg shadow-inner flex items-center justify-center relative overflow-hidden mr-4">
                    <div class="absolute -top-1 -right-1 w-6 h-6 bg-yellow-300 opacity-30 rounded-full animate-ping-slow"></div>
                    <div class="absolute -bottom-1 -left-1 w-4 h-4 bg-blue-300 opacity-30 rounded-full animate-ping-slow animation-delay-500"></div>
                    <i class="fas fa-cog text-white text-2xl z-10"></i> <!-- Changed icon to a cog for settings -->
                </div>
                <div>
                    <h2 class="text-2xl md:text-3xl font-bold text-white mb-1 text-shadow">Setup F/Goods & D/Order Configuration</h2>
                    <p class="text-blue-100 max-w-2xl">Configure finished goods and delivery order settings</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-b-lg shadow-lg p-6 mb-6 bg-gradient-to-br from-white to-indigo-50">
            <!-- Tab Navigation -->
            <div class="flex border-b border-gray-200 mb-6">
                <button 
                    v-for="tab in tabs" :key="tab.id"
                    @click="activeTab = tab.id"
                    :class="{
                        'py-2 px-4 text-sm font-medium border-b-2': true,
                        'border-indigo-500 text-indigo-600': activeTab === tab.id,
                        'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== tab.id
                    }"
                    class="transition-colors duration-200"
                >
                    {{ tab.name }}
                </button>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left Column - Main Content (adapted from Update MC) -->
                <div class="lg:col-span-2">
                    <div class="bg-white p-6 rounded-lg shadow-md border-t-4 border-indigo-500 transition-all duration-300 hover:shadow-lg transform hover:-translate-y-1 relative overflow-hidden">
                        <div class="absolute -top-20 -right-20 w-40 h-40 bg-indigo-50 rounded-full opacity-20"></div>
                        <div class="absolute -bottom-8 -left-8 w-24 h-24 bg-purple-50 rounded-full opacity-20"></div>
                        
                        <div class="flex items-center mb-6 pb-2 border-b border-gray-200 relative z-10">
                            <div class="p-2 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-lg mr-3 shadow-md">
                                <i class="fas fa-tasks text-white"></i>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-800">Configuration Settings</h3>
                        </div>

                        <!-- Tab Content -->
                        <div v-if="activeTab === 'fg'">
                            <form @submit.prevent="saveConfiguration" class="space-y-6">
                                <!-- Lock Customer Warehouse Location -->
                                <fieldset class="border border-gray-300 p-4 rounded-md shadow-sm">
                                    <legend class="text-md font-medium text-gray-700 px-2 -ml-2">Lock Customer Warehouse Location</legend>
                                    <div class="mt-2 space-y-2">
                                        <div class="flex items-center space-x-4">
                                            <label class="inline-flex items-center">
                                                <input type="radio" class="form-radio" name="lock_customer_location" value="Y-Yes" v-model="form.lockCustomerLocation">
                                                <span class="ml-2 text-gray-700">Y-Yes</span>
                                            </label>
                                            <label class="inline-flex items-center">
                                                <input type="radio" class="form-radio" name="lock_customer_location" value="N-No" v-model="form.lockCustomerLocation">
                                                <span class="ml-2 text-gray-700">N-No</span>
                                            </label>
                                            <label class="inline-flex items-center">
                                                <input type="radio" class="form-radio" name="lock_customer_location" value="C-Lock specific Customer" v-model="form.lockCustomerLocation">
                                                <span class="ml-2 text-gray-700">C-Lock specific Customer</span>
                                            </label>
                                        </div>
                                    </div>
                                </fieldset>

                                <!-- Default Warehouse -->
                                <fieldset class="border border-gray-300 p-4 rounded-md shadow-sm">
                                    <legend class="text-md font-medium text-gray-700 px-2 -ml-2">Default Warehouse</legend>
                                    <div class="space-y-4 mt-2">
                                        <div>
                                            <label for="whse_location_normal" class="block text-sm font-medium text-gray-700 mb-1">Whse Location [Normal]:</label>
                                            <div class="relative flex group">
                                                <input type="text" id="whse_location_normal" v-model="form.whseLocationNormal" class="flex-1 min-w-0 block w-full px-3 py-2 rounded-l-md border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 transition-all group-hover:border-indigo-300" />
                                                <button type="button" @click="openWarehouseLocationModal('normal')" class="inline-flex items-center px-3 py-2 border border-l-0 border-gray-300 bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 text-white rounded-r-md transition-all transform active:translate-y-px relative overflow-hidden shadow-sm">
                                                    <span class="absolute inset-0 bg-white opacity-0 hover:opacity-20 transition-opacity"></span>
                                                    <i class="fas fa-search relative z-10"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div>
                                            <label for="whse_location_excess" class="block text-sm font-medium text-gray-700 mb-1">Whse Location [Excess]:</label>
                                            <div class="relative flex group">
                                                <input type="text" id="whse_location_excess" v-model="form.whseLocationExcess" class="flex-1 min-w-0 block w-full px-3 py-2 rounded-l-md border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 transition-all group-hover:border-indigo-300" />
                                                <button type="button" @click="openWarehouseLocationModal('excess')" class="inline-flex items-center px-3 py-2 border border-l-0 border-gray-300 bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 text-white rounded-r-md transition-all transform active:translate-y-px relative overflow-hidden shadow-sm">
                                                    <span class="absolute inset-0 bg-white opacity-0 hover:opacity-20 transition-opacity"></span>
                                                    <i class="fas fa-search relative z-10"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div>
                                            <label for="whse_location_transit" class="block text-sm font-medium text-gray-700 mb-1">Whse Location [Transit]:</label>
                                            <div class="relative flex group">
                                                <input type="text" id="whse_location_transit" v-model="form.whseLocationTransit" class="flex-1 min-w-0 block w-full px-3 py-2 rounded-l-md border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 transition-all group-hover:border-indigo-300" />
                                                <button type="button" @click="openWarehouseLocationModal('transit')" class="inline-flex items-center px-3 py-2 border border-l-0 border-gray-300 bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 text-white rounded-r-md transition-all transform active:translate-y-px relative overflow-hidden shadow-sm">
                                                    <span class="absolute inset-0 bg-white opacity-0 hover:opacity-20 transition-opacity"></span>
                                                    <i class="fas fa-search relative z-10"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>

                                <!-- Default Analysis Code -->
                                <fieldset class="border border-gray-300 p-4 rounded-md shadow-sm">
                                    <legend class="text-md font-medium text-gray-700 px-2 -ml-2">Default Analysis Code</legend>
                                    <div class="space-y-4 mt-2">
                                        <div>
                                            <label for="stock_in_normal" class="block text-sm font-medium text-gray-700 mb-1">Stock-In - Normal:</label>
                                            <div class="relative flex group">
                                                <input type="text" id="stock_in_normal" v-model="form.stockInNormal" class="flex-1 min-w-0 block w-full px-3 py-2 rounded-l-md border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 transition-all group-hover:border-indigo-300" readonly />
                                                <button type="button" @click="openAnalysisCodeModal('stock_in_normal', 'FGSI')" class="inline-flex items-center px-3 py-2 border border-l-0 border-gray-300 bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 text-white rounded-r-md transition-all transform active:translate-y-px relative overflow-hidden shadow-sm">
                                                    <span class="absolute inset-0 bg-white opacity-0 hover:opacity-20 transition-opacity"></span>
                                                    <i class="fas fa-search relative z-10"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div>
                                            <label for="stock_in_booking" class="block text-sm font-medium text-gray-700 mb-1">Stock-In for Booking:</label>
                                            <div class="relative flex group">
                                                <input type="text" id="stock_in_booking" v-model="form.stockInBooking" class="flex-1 min-w-0 block w-full px-3 py-2 rounded-l-md border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 transition-all group-hover:border-indigo-300" readonly />
                                                <button type="button" @click="openAnalysisCodeModal('stock_in_booking', 'FGSI')" class="inline-flex items-center px-3 py-2 border border-l-0 border-gray-300 bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 text-white rounded-r-md transition-all transform active:translate-y-px relative overflow-hidden shadow-sm">
                                                    <span class="absolute inset-0 bg-white opacity-0 hover:opacity-20 transition-opacity"></span>
                                                    <i class="fas fa-search relative z-10"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div>
                                            <label for="stock_out_normal" class="block text-sm font-medium text-gray-700 mb-1">Stock-Out - Normal:</label>
                                            <div class="relative flex group">
                                                <input type="text" id="stock_out_normal" v-model="form.stockOutNormal" class="flex-1 min-w-0 block w-full px-3 py-2 rounded-l-md border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 transition-all group-hover:border-indigo-300" readonly />
                                                <button type="button" @click="openAnalysisCodeModal('stock_out_normal', 'FGSO')" class="inline-flex items-center px-3 py-2 border border-l-0 border-gray-300 bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 text-white rounded-r-md transition-all transform active:translate-y-px relative overflow-hidden shadow-sm">
                                                    <span class="absolute inset-0 bg-white opacity-0 hover:opacity-20 transition-opacity"></span>
                                                    <i class="fas fa-search relative z-10"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div>
                                            <label for="stock_out_booking" class="block text-sm font-medium text-gray-700 mb-1">Stock-Out for Booking:</label>
                                            <div class="relative flex group">
                                                <input type="text" id="stock_out_booking" v-model="form.stockOutBooking" class="flex-1 min-w-0 block w-full px-3 py-2 rounded-l-md border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 transition-all group-hover:border-indigo-300" readonly />
                                                <button type="button" @click="openAnalysisCodeModal('stock_out_booking', 'FGSO')" class="inline-flex items-center px-3 py-2 border border-l-0 border-gray-300 bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 text-white rounded-r-md transition-all transform active:translate-y-px relative overflow-hidden shadow-sm">
                                                    <span class="absolute inset-0 bg-white opacity-0 hover:opacity-20 transition-opacity"></span>
                                                    <i class="fas fa-search relative z-10"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div>
                                            <label for="warehouse_transfer" class="block text-sm font-medium text-gray-700 mb-1">Warehouse Transfer:</label>
                                            <div class="relative flex group">
                                                <input type="text" id="warehouse_transfer" v-model="form.warehouseTransfer" class="flex-1 min-w-0 block w-full px-3 py-2 rounded-l-md border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 transition-all group-hover:border-indigo-300" readonly />
                                                <button type="button" @click="openAnalysisCodeModal('warehouse_transfer', 'FGTR')" class="inline-flex items-center px-3 py-2 border border-l-0 border-gray-300 bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 text-white rounded-r-md transition-all transform active:translate-y-px relative overflow-hidden shadow-sm">
                                                    <span class="absolute inset-0 bg-white opacity-0 hover:opacity-20 transition-opacity"></span>
                                                    <i class="fas fa-search relative z-10"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>

                                <!-- Print Directly after F/Goods Transaction -->
                                <fieldset class="border border-gray-300 p-4 rounded-md shadow-sm">
                                    <legend class="text-md font-medium text-gray-700 px-2 -ml-2">Print Directly after F/Goods Transaction</legend>
                                    <div class="space-y-4 mt-2">
                                        <div class="flex items-center space-x-4">
                                            <label class="text-sm font-medium text-gray-700">Stock-In:</label>
                                            <label class="inline-flex items-center">
                                                <input type="radio" class="form-radio" name="print_stock_in" value="Y-Yes" v-model="form.printStockIn">
                                                <span class="ml-2 text-gray-700">Y-Yes</span>
                                            </label>
                                            <label class="inline-flex items-center">
                                                <input type="radio" class="form-radio" name="print_stock_in" value="N-No" v-model="form.printStockIn">
                                                <span class="ml-2 text-gray-700">N-No</span>
                                            </label>
                                        </div>
                                        <div class="flex items-center space-x-4">
                                            <label class="text-sm font-medium text-gray-700">Stock-Out:</label>
                                            <label class="inline-flex items-center">
                                                <input type="radio" class="form-radio" name="print_stock_out" value="Y-Yes" v-model="form.printStockOut">
                                                <span class="ml-2 text-gray-700">Y-Yes</span>
                                            </label>
                                            <label class="inline-flex items-center">
                                                <input type="radio" class="form-radio" name="print_stock_out" value="N-No" v-model="form.printStockOut">
                                                <span class="ml-2 text-gray-700">N-No</span>
                                            </label>
                                        </div>
                                        <div class="flex items-center space-x-4">
                                            <label class="text-sm font-medium text-gray-700">W/Transfer Transaction:</label>
                                            <label class="inline-flex items-center">
                                                <input type="radio" class="form-radio" name="print_w_transfer" value="Y-Yes" v-model="form.printWTransfer">
                                                <span class="ml-2 text-gray-700">Y-Yes</span>
                                            </label>
                                            <label class="inline-flex items-center">
                                                <input type="radio" class="form-radio" name="print_w_transfer" value="N-No" v-model="form.printWTransfer">
                                                <span class="ml-2 text-gray-700">N-No</span>
                                            </label>
                                        </div>
                                    </div>
                                </fieldset>

                                <!-- Period-End Closing -->
                                <fieldset class="border border-gray-300 p-4 rounded-md shadow-sm">
                                    <legend class="text-md font-medium text-gray-700 px-2 -ml-2">Period-End Closing</legend>
                                    <div class="mt-2 flex items-center">
                                        <label for="keep_fg_records_for" class="block text-sm font-medium text-gray-700 mr-2">Keep FG Records For:</label>
                                        <select id="keep_fg_records_for" v-model="form.keepFgRecordsFor" class="block w-24 px-3 py-2 rounded-md border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                            <option value="4">4</option>
                                            <option value="5">5</option>
                                        </select>
                                        <span class="ml-2 text-gray-700">Years</span>
                                    </div>
                                </fieldset>

                                <div class="flex justify-end pt-4 border-t border-gray-200">
                                    <button type="submit" class="bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white px-6 py-2 rounded-lg shadow-md flex items-center space-x-2 transition-all duration-300 transform active:scale-95">
                                        <i class="fas fa-save"></i>
                                        <span>Save Configuration</span>
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- Tab Content for FG MORE -->
                        <div v-else-if="activeTab === 'fg_more'">
                             <form @submit.prevent="saveConfiguration" class="space-y-6">
                                <!-- Stock-In by SO -->
                                <fieldset class="border border-gray-300 p-4 rounded-md shadow-sm">
                                    <legend class="text-md font-medium text-gray-700 px-2 -ml-2">Stock-In by SO</legend>
                                    <div class="mt-2 space-y-4">
                                        <div class="flex items-center space-x-4">
                                            <label class="text-sm font-medium text-gray-700">Check SO Mode:</label>
                                            <label class="inline-flex items-center">
                                                <input type="radio" class="form-radio" name="check_so_mode" value="B-Prompt + Block" v-model="form.checkSoMode">
                                                <span class="ml-2 text-gray-700">B-Prompt + Block</span>
                                            </label>
                                            <label class="inline-flex items-center">
                                                <input type="radio" class="form-radio" name="check_so_mode" value="P-Prompt" v-model="form.checkSoMode">
                                                <span class="ml-2 text-gray-700">P-Prompt</span>
                                            </label>
                                            <label class="inline-flex items-center">
                                                <input type="radio" class="form-radio" name="check_so_mode" value="N-No Check" v-model="form.checkSoMode">
                                                <span class="ml-2 text-gray-700">N-No Check</span>
                                            </label>
                                        </div>
                                        <div>
                                            <label for="allow_qty_so" class="block text-sm font-medium text-gray-700 mb-1">Allow Qty:</label>
                                            <input type="number" id="allow_qty_so" v-model="form.allowQtySo" class="block w-full px-3 py-2 rounded-md border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500" />
                                        </div>
                                        <div class="flex items-center space-x-4">
                                            <label class="text-sm font-medium text-gray-700">Allow Type:</label>
                                            <label class="inline-flex items-center">
                                                <input type="radio" class="form-radio" name="allow_type_so" value="1-Absolute Qty" v-model="form.allowTypeSo">
                                                <span class="ml-2 text-gray-700">1-Absolute Qty</span>
                                            </label>
                                            <label class="inline-flex items-center">
                                                <input type="radio" class="form-radio" name="allow_type_so" value="2-Percentage of SO Qty" v-model="form.allowTypeSo">
                                                <span class="ml-2 text-gray-700">2-Percentage of SO Qty</span>
                                            </label>
                                        </div>
                                    </div>
                                </fieldset>

                                <!-- Stock-In by WO -->
                                <fieldset class="border border-gray-300 p-4 rounded-md shadow-sm">
                                    <legend class="text-md font-medium text-gray-700 px-2 -ml-2">Stock-In by WO</legend>
                                    <div class="mt-2 space-y-4">
                                        <div class="flex items-center space-x-4">
                                            <label class="text-sm font-medium text-gray-700">Check WO Mode:</label>
                                            <label class="inline-flex items-center">
                                                <input type="radio" class="form-radio" name="check_wo_mode" value="B-Prompt + Block" v-model="form.checkWoMode">
                                                <span class="ml-2 text-gray-700">B-Prompt + Block</span>
                                            </label>
                                            <label class="inline-flex items-center">
                                                <input type="radio" class="form-radio" name="check_wo_mode" value="P-Prompt" v-model="form.checkWoMode">
                                                <span class="ml-2 text-gray-700">P-Prompt</span>
                                            </label>
                                            <label class="inline-flex items-center">
                                                <input type="radio" class="form-radio" name="check_wo_mode" value="N-No Check" v-model="form.checkWoMode">
                                                <span class="ml-2 text-gray-700">N-No Check</span>
                                            </label>
                                        </div>
                                        <div>
                                            <label for="allow_qty_wo" class="block text-sm font-medium text-gray-700 mb-1">Allow Qty:</label>
                                            <input type="number" id="allow_qty_wo" v-model="form.allowQtyWo" class="block w-full px-3 py-2 rounded-md border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500" />
                                        </div>
                                        <div class="flex items-center space-x-4">
                                            <label class="text-sm font-medium text-gray-700">Allow Type:</label>
                                            <label class="inline-flex items-center">
                                                <input type="radio" class="form-radio" name="allow_type_wo" value="1-Absolute Qty" v-model="form.allowTypeWo">
                                                <span class="ml-2 text-gray-700">1-Absolute Qty</span>
                                            </label>
                                            <label class="inline-flex items-center">
                                                <input type="radio" class="form-radio" name="allow_type_wo" value="2-Percentage of WO Qty" v-model="form.allowTypeWo">
                                                <span class="ml-2 text-gray-700">2-Percentage of WO Qty</span>
                                            </label>
                                        </div>
                                    </div>
                                </fieldset>

                                <!-- Stock-In by FG Non-GRX Barcode -->
                                <fieldset class="border border-gray-300 p-4 rounded-md shadow-sm">
                                    <legend class="text-md font-medium text-gray-700 px-2 -ml-2">Stock-In by FG Non-GRX Barcode</legend>
                                    <div class="mt-2 space-y-4">
                                        <div class="flex items-center space-x-4">
                                            <label class="text-sm font-medium text-gray-700">Check Mode:</label>
                                            <label class="inline-flex items-center">
                                                <input type="radio" class="form-radio" name="check_mode_fg_barcode" value="B-Prompt + Block" v-model="form.checkModeFgBarcode">
                                                <span class="ml-2 text-gray-700">B-Prompt + Block</span>
                                            </label>
                                            <label class="inline-flex items-center">
                                                <input type="radio" class="form-radio" name="check_mode_fg_barcode" value="P-Prompt" v-model="form.checkModeFgBarcode">
                                                <span class="ml-2 text-gray-700">P-Prompt</span>
                                            </label>
                                            <label class="inline-flex items-center">
                                                <input type="radio" class="form-radio" name="check_mode_fg_barcode" value="N-No Check" v-model="form.checkModeFgBarcode">
                                                <span class="ml-2 text-gray-700">N-No Check</span>
                                            </label>
                                        </div>
                                        <div>
                                            <label for="allow_qty_fg_barcode" class="block text-sm font-medium text-gray-700 mb-1">Allow Qty:</label>
                                            <input type="number" id="allow_qty_fg_barcode" v-model="form.allowQtyFgBarcode" class="block w-full px-3 py-2 rounded-md border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500" />
                                            <p class="mt-1 text-sm text-gray-500">Pcs/Roll/Kg (+/- of qty printed on FG Pallet Label) * Recommend 20 Qty</p>
                                        </div>
                                        <div class="flex items-center space-x-4">
                                            <label class="text-sm font-medium text-gray-700">Allow Type:</label>
                                            <label class="inline-flex items-center">
                                                <input type="radio" class="form-radio" name="allow_type_fg_barcode" value="1-Absolute Qty" v-model="form.allowTypeFgBarcode">
                                                <span class="ml-2 text-gray-700">1-Absolute Qty</span>
                                            </label>
                                            <label class="inline-flex items-center">
                                                <input type="radio" class="form-radio" name="allow_type_fg_barcode" value="2-Percentage of SO Qty" v-model="form.allowTypeFgBarcode">
                                                <span class="ml-2 text-gray-700">2-Percentage of SO Qty</span>
                                            </label>
                                        </div>
                                    </div>
                                </fieldset>

                                <div class="flex justify-end pt-4 border-t border-gray-200">
                                    <button type="submit" class="bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white px-6 py-2 rounded-lg shadow-md flex items-center space-x-2 transition-all duration-300 transform active:scale-95">
                                        <i class="fas fa-save"></i>
                                        <span>Save Configuration</span>
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- Placeholder for other tabs -->
                        <div v-else-if="activeTab === 'do'">
                            <form @submit.prevent="saveConfiguration" class="space-y-6">
                                <!-- Delivery Order Format -->
                                <fieldset class="border border-gray-300 p-4 rounded-md shadow-sm">
                                    <legend class="text-md font-medium text-gray-700 px-2 -ml-2">Delivery Order Format</legend>
                                    <div class="mt-2 flex items-center space-x-4">
                                        <div class="relative flex group">
                                            <input type="text" id="delivery_order_format" v-model="form.deliveryOrderFormat" class="flex-1 min-w-0 block w-24 px-3 py-2 rounded-l-md border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 transition-all group-hover:border-indigo-300" />
                                            <button type="button" class="inline-flex items-center px-3 py-2 border border-l-0 border-gray-300 bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 text-white rounded-r-md transition-all transform active:translate-y-px relative overflow-hidden shadow-sm">
                                                <span class="absolute inset-0 bg-white opacity-0 hover:opacity-20 transition-opacity"></span>
                                                <i class="fas fa-a relative z-10"></i>
                                            </button>
                                            <button type="button" class="inline-flex items-center px-3 py-2 border border-l-0 border-gray-300 bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 text-white rounded-r-md transition-all transform active:translate-y-px relative overflow-hidden shadow-sm ml-2">
                                                <span class="absolute inset-0 bg-white opacity-0 hover:opacity-20 transition-opacity"></span>
                                                <i class="fas fa-boxes relative z-10"></i>
                                            </button>
                                        </div>
                                    </div>
                                </fieldset>

                                <!-- Bundle Format -->
                                <fieldset class="border border-gray-300 p-4 rounded-md shadow-sm">
                                    <legend class="text-md font-medium text-gray-700 px-2 -ml-2">Bundle Format</legend>
                                    <div class="mt-2 space-y-2">
                                        <div class="flex items-center space-x-4">
                                            <label class="inline-flex items-center">
                                                <input type="radio" class="form-radio" name="bundle_format" value="0-Not Applicable" v-model="form.bundleFormat">
                                                <span class="ml-2 text-gray-700">0-Not Applicable</span>
                                            </label>
                                        </div>
                                        <div class="flex items-center space-x-4">
                                            <label class="inline-flex items-center">
                                                <input type="radio" class="form-radio" name="bundle_format" value="1-{BDLSxQTY}+{QTY}" v-model="form.bundleFormat">
                                                <span class="ml-2 text-gray-700">1-{BDLSxQTY}+{QTY}</span>
                                            </label>
                                        </div>
                                        <div class="flex items-center space-x-4">
                                            <label class="inline-flex items-center">
                                                <input type="radio" class="form-radio" name="bundle_format" value="2-{BDLSxQTY}+{1BDLxQTY}" v-model="form.bundleFormat">
                                                <span class="ml-2 text-gray-700">2-{BDLSxQTY}+{1BDLxQTY}</span>
                                            </label>
                                        </div>
                                    </div>
                                </fieldset>

                                <!-- Destination Change -->
                                <fieldset class="border border-gray-300 p-4 rounded-md shadow-sm">
                                    <legend class="text-md font-medium text-gray-700 px-2 -ml-2">Destination Change</legend>
                                    <div class="mt-2 space-y-2">
                                        <div class="flex items-center space-x-4">
                                            <label class="inline-flex items-center">
                                                <input type="radio" class="form-radio" name="destination_change" value="Y-Yes" v-model="form.destinationChange">
                                                <span class="ml-2 text-gray-700">Y-Yes</span>
                                            </label>
                                            <label class="inline-flex items-center">
                                                <input type="radio" class="form-radio" name="destination_change" value="N-No" v-model="form.destinationChange">
                                                <span class="ml-2 text-gray-700">N-No</span>
                                            </label>
                                        </div>
                                    </div>
                                </fieldset>

                                <!-- Product Group Tie-Up -->
                                <fieldset class="border border-gray-300 p-4 rounded-md shadow-sm">
                                    <legend class="text-md font-medium text-gray-700 px-2 -ml-2">Product Group Tie-Up</legend>
                                    <div class="mt-2 space-y-2">
                                        <div class="flex items-center space-x-4">
                                            <label class="inline-flex items-center">
                                                <input type="radio" class="form-radio" name="product_group_tie_up" value="Y-Yes" v-model="form.productGroupTieUp">
                                                <span class="ml-2 text-gray-700">Y-Yes</span>
                                            </label>
                                            <label class="inline-flex items-center">
                                                <input type="radio" class="form-radio" name="product_group_tie_up" value="N-No" v-model="form.productGroupTieUp">
                                                <span class="ml-2 text-gray-700">N-No *Customer Sales Index Profile</span>
                                            </label>
                                        </div>
                                    </div>
                                </fieldset>

                                <!-- Unapplied F/Goods -->
                                <fieldset class="border border-gray-300 p-4 rounded-md shadow-sm">
                                    <legend class="text-md font-medium text-gray-700 px-2 -ml-2">Unapplied F/Goods</legend>
                                    <div class="mt-2 space-y-2">
                                        <div class="flex items-center space-x-4">
                                            <label class="inline-flex items-center">
                                                <input type="radio" class="form-radio" name="unapplied_fgoods" value="Y-Yes" v-model="form.unappliedFGoods">
                                                <span class="ml-2 text-gray-700">Y-Yes</span>
                                            </label>
                                            <label class="inline-flex items-center">
                                                <input type="radio" class="form-radio" name="unapplied_fgoods" value="N-No" v-model="form.unappliedFGoods">
                                                <span class="ml-2 text-gray-700">N-No *For D/Order with multiple items</span>
                                            </label>
                                        </div>
                                    </div>
                                </fieldset>

                                <!-- Amend D/O Un.Qty -->
                                <fieldset class="border border-gray-300 p-4 rounded-md shadow-sm">
                                    <legend class="text-md font-medium text-gray-700 px-2 -ml-2">Amend D/O Un.Qty</legend>
                                    <div class="mt-2 space-y-2">
                                        <div class="flex items-center space-x-4">
                                            <label class="inline-flex items-center">
                                                <input type="radio" class="form-radio" name="amend_do_un_qty" value="Y-Yes" v-model="form.amendDoUnQty">
                                                <span class="ml-2 text-gray-700">Y-Yes</span>
                                            </label>
                                            <label class="inline-flex items-center">
                                                <input type="radio" class="form-radio" name="amend_do_un_qty" value="N-No" v-model="form.amendDoUnQty">
                                                <span class="ml-2 text-gray-700">N-No *Amend unapplied Qty before reconcile</span>
                                            </label>
                                        </div>
                                    </div>
                                </fieldset>

                                <!-- Input Pallet Qty -->
                                <fieldset class="border border-gray-300 p-4 rounded-md shadow-sm">
                                    <legend class="text-md font-medium text-gray-700 px-2 -ml-2">Input Pallet Qty</legend>
                                    <div class="mt-2 space-y-2">
                                        <div class="flex items-center space-x-4">
                                            <label class="inline-flex items-center">
                                                <input type="radio" class="form-radio" name="input_pallet_qty" value="Y-Yes" v-model="form.inputPalletQty">
                                                <span class="ml-2 text-gray-700">Y-Yes</span>
                                            </label>
                                            <label class="inline-flex items-center">
                                                <input type="radio" class="form-radio" name="input_pallet_qty" value="N-No" v-model="form.inputPalletQty">
                                                <span class="ml-2 text-gray-700">N-No *For CPS Plugin Weight Bridge</span>
                                            </label>
                                        </div>
                                    </div>
                                </fieldset>

                                <div class="flex justify-end pt-4 border-t border-gray-200">
                                    <button type="submit" class="bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white px-6 py-2 rounded-lg shadow-md flex items-center space-x-2 transition-all duration-300 transform active:scale-95">
                                        <i class="fas fa-save"></i>
                                        <span>Save Configuration</span>
                                    </button>
                                </div>
                            </form>
                        </div>
                        <div v-else-if="activeTab === 'do_blank'">
                            <p class="text-gray-600">DO Blank Configuration content will go here.</p>
                        </div>
                        <div v-else-if="activeTab === 'ctrl_do'">
                            <p class="text-gray-600">Control DO Configuration content will go here.</p>
                        </div>
                        <div v-else-if="activeTab === 'do_reject'">
                            <p class="text-gray-600">DO Reject Configuration content will go here.</p>
                        </div>
                        <div v-else-if="activeTab === 'vmc_link'">
                            <p class="text-gray-600">VMC Link Configuration content will go here.</p>
                        </div>
                    </div>
                </div>

                <!-- Right Column - Quick Info (adapted from Update MC) -->
                <div class="lg:col-span-1">
                    <div class="bg-white p-6 rounded-lg shadow-md border-t-4 border-teal-500 mb-6 transition-all duration-300 hover:shadow-lg transform hover:-translate-y-1 relative overflow-hidden">
                        <div class="absolute -top-16 -right-16 w-32 h-32 bg-teal-50 rounded-full opacity-20"></div>
                        <div class="absolute -bottom-6 -left-6 w-20 h-20 bg-green-50 rounded-full opacity-20"></div>
                        
                        <div class="flex items-center mb-4 pb-2 border-b border-gray-200 relative z-10">
                            <div class="p-2 bg-gradient-to-r from-teal-500 to-green-500 rounded-lg mr-3 shadow-md">
                                <i class="fas fa-lightbulb text-white"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-800">Quick Tips</h3>
                        </div>

                        <div class="space-y-4">
                            <div class="p-4 bg-teal-50 rounded-lg">
                                <h4 class="text-sm font-semibold text-teal-800 uppercase tracking-wider mb-2 flex items-center">
                                    <span class="inline-flex items-center justify-center w-5 h-5 bg-gradient-to-r from-blue-400 to-indigo-400 rounded-full mr-2 shadow-sm mt-0.5">
                                        <i class="fas fa-question text-white text-xs"></i>
                                    </span>
                                    How to Use
                                </h4>
                                <ul class="text-sm text-gray-600 space-y-2">
                                    <li class="flex items-start">
                                        <span class="inline-flex items-center justify-center w-5 h-5 bg-gradient-to-r from-blue-400 to-indigo-400 rounded-full mr-2 shadow-sm mt-0.5">
                                            <i class="fas fa-check text-white text-xs"></i>
                                        </span>
                                        Define default warehouse locations and analysis codes for various finished goods transactions.
                                    </li>
                                    <li class="flex items-start">
                                        <span class="inline-flex items-center justify-center w-5 h-5 bg-gradient-to-r from-purple-400 to-pink-400 rounded-full mr-2 shadow-sm mt-0.5">
                                            <i class="fas fa-check text-white text-xs"></i>
                                        </span>
                                        Use the tabs above to navigate through different configuration sections.
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <FgDoConfigModal
            :show="isWarehouseLocationModalOpen"
            @close="closeWarehouseLocationModal"
            @location-selected="handleWarehouseLocationSelect"
        />

        <AnalysisCodeModal
            :show="isAnalysisCodeModalOpen"
            :grouping="currentAnalysisCodeGrouping"
            @close="closeAnalysisCodeModal"
            @analysis-code-selected="handleAnalysisCodeSelect"
        />
    </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { ref, reactive, onMounted } from 'vue';
import FgDoConfigModal from '@/Components/FgDoConfigModal.vue';
import AnalysisCodeModal from '@/Components/AnalysisCodeModal.vue';
import axios from 'axios';
import debounce from 'lodash/debounce';

const form = reactive({
    lockCustomerLocation: 'N-No',
    whseLocationNormal: '',
    whseLocationExcess: '',
    whseLocationTransit: '',
    stockInNormal: '',
    stockInBooking: '',
    stockOutNormal: '',
    stockOutBooking: '',
    warehouseTransfer: '',
    printStockIn: 'N-No',
    printStockOut: 'N-No',
    printWTransfer: 'N-No',
    keepFgRecordsFor: 1,

    // FG MORE fields
    checkSoMode: 'N-No Check',
    allowQtySo: 0,
    allowTypeSo: '1-Absolute Qty',
    checkWoMode: 'N-No Check',
    allowQtyWo: 0,
    allowTypeWo: '1-Absolute Qty',
    checkModeFgBarcode: 'N-No Check',
    allowQtyFgBarcode: 0,
    allowTypeFgBarcode: '1-Absolute Qty',

    // DO fields
    deliveryOrderFormat: '',
    bundleFormat: '0-Not Applicable',
    destinationChange: 'N-No',
    productGroupTieUp: 'N-No',
    unappliedFGoods: 'N-No',
    amendDoUnQty: 'N-No',
    inputPalletQty: 'N-No',
});

const isWarehouseLocationModalOpen = ref(false);
const currentWarehouseLocationField = ref(null);

const isAnalysisCodeModalOpen = ref(false);
const currentAnalysisCodeField = ref(null);
const currentAnalysisCodeGrouping = ref('');

// New reactive variable for tabs
const activeTab = ref('fg'); // Default active tab
const tabs = ref([
    { id: 'fg', name: 'FG' },
    { id: 'fg_more', name: 'FG MORE' },
    { id: 'do', name: 'DO' },
    { id: 'do_blank', name: 'DO BLANK' },
    { id: 'ctrl_do', name: 'CTRL DO' },
    { id: 'do_reject', name: 'DO REJECT' },
    { id: 'vmc_link', name: 'VMC LINK' },
]);

const fetchConfiguration = async () => {
    try {
        const response = await axios.get(route('fg-do-config'));
        const config = response.data.data;
        if (config) {
            form.lockCustomerLocation = config.lock_customer_location;
            form.whseLocationNormal = config.whse_location_normal;
            form.whseLocationExcess = config.whse_location_excess;
            form.whseLocationTransit = config.whse_location_transit;
            form.stockInNormal = config.stock_in_normal;
            form.stockInBooking = config.stock_in_booking;
            form.stockOutNormal = config.stock_out_normal;
            form.stockOutBooking = config.stock_out_booking;
            form.warehouseTransfer = config.warehouse_transfer;
            form.printStockIn = config.print_stock_in;
            form.printStockOut = config.print_stock_out;
            form.printWTransfer = config.print_w_transfer;
            form.keepFgRecordsFor = config.keep_fg_records_for;

            // FG MORE fields
            form.checkSoMode = config.check_so_mode;
            form.allowQtySo = config.allow_qty_so;
            form.allowTypeSo = config.allow_type_so;
            form.checkWoMode = config.check_wo_mode;
            form.allowQtyWo = config.allow_qty_wo;
            form.allowTypeWo = config.allow_type_wo;
            form.checkModeFgBarcode = config.check_mode_fg_barcode;
            form.allowQtyFgBarcode = config.allow_qty_fg_barcode;
            form.allowTypeFgBarcode = config.allow_type_fg_barcode;

            // DO fields
            form.deliveryOrderFormat = config.delivery_order_format;
            form.bundleFormat = config.bundle_format;
            form.destinationChange = config.destination_change;
            form.productGroupTieUp = config.product_group_tie_up;
            form.unappliedFGoods = config.unapplied_f_goods;
            form.amendDoUnQty = config.amend_do_un_qty;
            form.inputPalletQty = config.input_pallet_qty;
        }
    } catch (error) {
        console.error('Failed to fetch configuration:', error);
    }
};

const saveConfiguration = async () => {
    try {
        await axios.post(route('fg-do-config'), form);
        alert('Configuration saved successfully!');
        fetchConfiguration();
    } catch (error) {
        console.error('Failed to save configuration:', error);
        alert('Failed to save configuration.');
    }
};

const openWarehouseLocationModal = (field) => {
    currentWarehouseLocationField.value = field;
    isWarehouseLocationModalOpen.value = true;
};

const closeWarehouseLocationModal = () => {
    isWarehouseLocationModalOpen.value = false;
};

const handleWarehouseLocationSelect = (location) => {
    if (currentWarehouseLocationField.value === 'normal') {
        form.whseLocationNormal = location.code;
    } else if (currentWarehouseLocationField.value === 'excess') {
        form.whseLocationExcess = location.code;
    } else if (currentWarehouseLocationField.value === 'transit') {
        form.whseLocationTransit = location.code;
    }
    closeWarehouseLocationModal();
};

const openAnalysisCodeModal = (field, grouping) => {
    currentAnalysisCodeField.value = field;
    currentAnalysisCodeGrouping.value = grouping;
    isAnalysisCodeModalOpen.value = true;
};

const closeAnalysisCodeModal = () => {
    isAnalysisCodeModalOpen.value = false;
};

const handleAnalysisCodeSelect = (analysisCode) => {
    if (currentAnalysisCodeField.value === 'stock_in_normal') {
        form.stockInNormal = analysisCode.code;
    } else if (currentAnalysisCodeField.value === 'stock_in_booking') {
        form.stockInBooking = analysisCode.code;
    } else if (currentAnalysisCodeField.value === 'stock_out_normal') {
        form.stockOutNormal = analysisCode.code;
    } else if (currentAnalysisCodeField.value === 'stock_out_booking') {
        form.stockOutBooking = analysisCode.code;
    } else if (currentAnalysisCodeField.value === 'warehouse_transfer') {
        form.warehouseTransfer = analysisCode.code;
    }
    closeAnalysisCodeModal();
};

onMounted(() => {
    fetchConfiguration();
});
</script>

<style scoped>
/* Add any specific styles for this page here if necessary */
</style> 