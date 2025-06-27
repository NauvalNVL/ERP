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

                        <!-- Form content -->
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
                                            <button type="button" class="inline-flex items-center px-3 py-2 border border-l-0 border-gray-300 bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 text-white rounded-r-md transition-all transform active:translate-y-px relative overflow-hidden shadow-sm">
                                                <span class="absolute inset-0 bg-white opacity-0 hover:opacity-20 transition-opacity"></span>
                                                <i class="fas fa-search relative z-10"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div>
                                        <label for="whse_location_excess" class="block text-sm font-medium text-gray-700 mb-1">Whse Location [Excess]:</label>
                                        <div class="relative flex group">
                                            <input type="text" id="whse_location_excess" v-model="form.whseLocationExcess" class="flex-1 min-w-0 block w-full px-3 py-2 rounded-l-md border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 transition-all group-hover:border-indigo-300" />
                                            <button type="button" class="inline-flex items-center px-3 py-2 border border-l-0 border-gray-300 bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 text-white rounded-r-md transition-all transform active:translate-y-px relative overflow-hidden shadow-sm">
                                                <span class="absolute inset-0 bg-white opacity-0 hover:opacity-20 transition-opacity"></span>
                                                <i class="fas fa-search relative z-10"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div>
                                        <label for="whse_location_transit" class="block text-sm font-medium text-gray-700 mb-1">Whse Location [Transit]:</label>
                                        <div class="relative flex group">
                                            <input type="text" id="whse_location_transit" v-model="form.whseLocationTransit" class="flex-1 min-w-0 block w-full px-3 py-2 rounded-l-md border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 transition-all group-hover:border-indigo-300" />
                                            <button type="button" class="inline-flex items-center px-3 py-2 border border-l-0 border-gray-300 bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 text-white rounded-r-md transition-all transform active:translate-y-px relative overflow-hidden shadow-sm">
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
                                            <input type="text" id="stock_in_normal" v-model="form.stockInNormal" class="flex-1 min-w-0 block w-full px-3 py-2 rounded-l-md border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 transition-all group-hover:border-indigo-300" value="SI01" />
                                            <button type="button" class="inline-flex items-center px-3 py-2 border border-l-0 border-gray-300 bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 text-white rounded-r-md transition-all transform active:translate-y-px relative overflow-hidden shadow-sm">
                                                <span class="absolute inset-0 bg-white opacity-0 hover:opacity-20 transition-opacity"></span>
                                                <i class="fas fa-search relative z-10"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div>
                                        <label for="stock_in_booking" class="block text-sm font-medium text-gray-700 mb-1">Stock-In for Booking:</label>
                                        <div class="relative flex group">
                                            <input type="text" id="stock_in_booking" v-model="form.stockInBooking" class="flex-1 min-w-0 block w-full px-3 py-2 rounded-l-md border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 transition-all group-hover:border-indigo-300" value="SI01" />
                                            <button type="button" class="inline-flex items-center px-3 py-2 border border-l-0 border-gray-300 bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 text-white rounded-r-md transition-all transform active:translate-y-px relative overflow-hidden shadow-sm">
                                                <span class="absolute inset-0 bg-white opacity-0 hover:opacity-20 transition-opacity"></span>
                                                <i class="fas fa-search relative z-10"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div>
                                        <label for="stock_out_normal" class="block text-sm font-medium text-gray-700 mb-1">Stock-Out - Normal:</label>
                                        <div class="relative flex group">
                                            <input type="text" id="stock_out_normal" v-model="form.stockOutNormal" class="flex-1 min-w-0 block w-full px-3 py-2 rounded-l-md border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 transition-all group-hover:border-indigo-300" value="SO01" />
                                            <button type="button" class="inline-flex items-center px-3 py-2 border border-l-0 border-gray-300 bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 text-white rounded-r-md transition-all transform active:translate-y-px relative overflow-hidden shadow-sm">
                                                <span class="absolute inset-0 bg-white opacity-0 hover:opacity-20 transition-opacity"></span>
                                                <i class="fas fa-search relative z-10"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div>
                                        <label for="stock_out_booking" class="block text-sm font-medium text-gray-700 mb-1">Stock-Out for Booking:</label>
                                        <div class="relative flex group">
                                            <input type="text" id="stock_out_booking" v-model="form.stockOutBooking" class="flex-1 min-w-0 block w-full px-3 py-2 rounded-l-md border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 transition-all group-hover:border-indigo-300" value="SO01" />
                                            <button type="button" class="inline-flex items-center px-3 py-2 border border-l-0 border-gray-300 bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 text-white rounded-r-md transition-all transform active:translate-y-px relative overflow-hidden shadow-sm">
                                                <span class="absolute inset-0 bg-white opacity-0 hover:opacity-20 transition-opacity"></span>
                                                <i class="fas fa-search relative z-10"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div>
                                        <label for="warehouse_transfer" class="block text-sm font-medium text-gray-700 mb-1">Warehouse Transfer:</label>
                                        <div class="relative flex group">
                                            <input type="text" id="warehouse_transfer" v-model="form.warehouseTransfer" class="flex-1 min-w-0 block w-full px-3 py-2 rounded-l-md border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 transition-all group-hover:border-indigo-300" value="WT01" />
                                            <button type="button" class="inline-flex items-center px-3 py-2 border border-l-0 border-gray-300 bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 text-white rounded-r-md transition-all transform active:translate-y-px relative overflow-hidden shadow-sm">
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
                                <button type="button" @click="openModal" class="ml-4 bg-gradient-to-r from-blue-500 to-cyan-600 hover:from-blue-600 hover:to-cyan-700 text-white px-6 py-2 rounded-lg shadow-md flex items-center space-x-2 transition-all duration-300 transform active:scale-95">
                                    <i class="fas fa-plus"></i>
                                    <span>Open FG/DO Modal</span>
                                </button>
                            </div>
                        </form>
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
                                        <span>Fill in all required fields accurately.</span>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="inline-flex items-center justify-center w-5 h-5 bg-gradient-to-r from-purple-400 to-pink-400 rounded-full mr-2 shadow-sm mt-0.5">
                                            <i class="fas fa-check text-white text-xs"></i>
                                        </span>
                                        <span>Click 'Save Configuration' to apply changes.</span>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="inline-flex items-center justify-center w-5 h-5 bg-gradient-to-r from-amber-400 to-orange-400 rounded-full mr-2 shadow-sm mt-0.5">
                                            <i class="fas fa-check text-white text-xs"></i>
                                        </span>
                                        <span>Review settings after saving.</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref, reactive } from 'vue'; // Import reactive
import FgDoConfigModal from '@/Components/FgDoConfigModal.vue'; // Import the new modal component

const isModalOpen = ref(false);

// Reactive form data to hold the values for the input fields
const form = reactive({
    lockCustomerLocation: 'N-No',
    whseLocationNormal: '',
    whseLocationExcess: '',
    whseLocationTransit: '',
    stockInNormal: 'SI01',
    stockInBooking: 'SI01',
    stockOutNormal: 'SO01',
    stockOutBooking: 'SO01',
    warehouseTransfer: 'WT01',
    printStockIn: 'N-No',
    printStockOut: 'N-No',
    printWTransfer: 'N-No',
    keepFgRecordsFor: '1',
});

const openModal = () => {
    isModalOpen.value = true;
};

const closeModal = () => {
    isModalOpen.value = false;
};

const handleModalSave = () => {
    // Logic when save button in modal is clicked
    alert('Modal Save clicked (placeholder)!');
    closeModal();
};

const saveConfiguration = () => {
    // Logic to save configuration using form.value
    console.log('Saving configuration:', form);
    alert('Configuration saved (placeholder)!');
};
</script>

<style scoped>
/* Component-specific styles if any, for now using Tailwind utility classes */
</style>

<FgDoConfigModal :show="isModalOpen" @close="closeModal" @save="handleModalSave" /> 