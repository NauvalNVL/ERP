<template>
    <AppLayout :header="'Update FG Stock-In by SO'">
        <!-- Header Section -->
        <div class="bg-gradient-to-r from-green-600 via-teal-600 to-emerald-600 p-6 rounded-t-lg shadow-lg overflow-hidden relative">
            <div class="absolute top-0 right-0 w-40 h-40 bg-white opacity-5 rounded-full -translate-y-20 translate-x-20"></div>
            <div class="absolute bottom-0 left-0 w-20 h-20 bg-white opacity-5 rounded-full translate-y-10 -translate-x-10"></div>
            <div class="flex items-center">
                <div class="bg-gradient-to-br from-green-500 to-emerald-600 p-3 rounded-lg shadow-inner flex items-center justify-center relative overflow-hidden mr-4">
                    <div class="absolute -top-1 -right-1 w-6 h-6 bg-yellow-300 opacity-30 rounded-full animate-ping-slow"></div>
                    <div class="absolute -bottom-1 -left-1 w-4 h-4 bg-blue-300 opacity-30 rounded-full animate-ping-slow animation-delay-500"></div>
                    <i class="fas fa-boxes text-white text-2xl z-10"></i>
                </div>
                <div>
                    <h2 class="text-2xl md:text-3xl font-bold text-white mb-1 text-shadow">Update FG Stock-In by SO</h2>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-b-lg shadow-lg p-6 mb-6 bg-gradient-to-br from-white to-green-50">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Main Form -->
                <div class="lg:col-span-2">
                    <div class="bg-white p-6 rounded-lg shadow-md border-t-4 border-green-500 transition-all duration-300 hover:shadow-lg transform hover:-translate-y-1 relative overflow-hidden">
                        <div class="absolute -top-20 -right-20 w-40 h-40 bg-green-50 rounded-full opacity-20"></div>
                        <div class="absolute -bottom-8 -left-8 w-24 h-24 bg-teal-50 rounded-full opacity-20"></div>
                        <div class="flex items-center mb-6 pb-2 border-b border-gray-200 relative z-10">
                            <div class="p-2 bg-gradient-to-r from-green-500 to-emerald-600 rounded-lg mr-3 shadow-md">
                                <i class="fas fa-file-invoice text-white"></i>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-800">Sales Order Management</h3>
                        </div>
                        <form class="space-y-6">
                            <div>
                                <label for="current_period" class="block text-sm font-medium text-gray-700 mb-1 flex items-center">
                                    <span class="inline-flex items-center justify-center w-5 h-5 bg-gradient-to-r from-green-500 to-emerald-600 rounded-full mr-2 shadow-sm">
                                        <i class="fas fa-calendar text-white text-xs"></i>
                                    </span>
                                    Current Period:
                                </label>
                                <input 
                                    type="text" 
                                    id="current_period" 
                                    v-model="form.current_period"
                                    class="flex-1 min-w-0 block w-full px-3 py-2 rounded-md border border-gray-300 focus:ring-green-500 focus:border-green-500 transition-all"
                                />
                            </div>
                            <div>
                                <label for="so_number" class="block text-sm font-medium text-gray-700 mb-1 flex items-center">
                                    <span class="inline-flex items-center justify-center w-5 h-5 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-full mr-2 shadow-sm">
                                        <i class="fas fa-hashtag text-white text-xs"></i>
                                    </span>
                                    Sales Order#:
                                </label>
                                <div class="relative flex group">
                                    <input
                                        type="text"
                                        id="so_number"
                                        v-model="form.so_number"
                                        class="flex-1 min-w-0 block w-full px-3 py-2 rounded-l-md border border-gray-300 focus:ring-blue-500 focus:border-blue-500 transition-all group-hover:border-blue-300"
                                    />
                                    <button
                                        type="button"
                                        @click="openSalesOrderModal"
                                        class="inline-flex items-center px-3 py-2 border border-l-0 border-gray-300 bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 text-white rounded-r-md transition-all transform active:translate-y-px relative overflow-hidden shadow-sm"
                                    >
                                        <span class="absolute inset-0 bg-white opacity-0 hover:opacity-20 transition-opacity"></span>
                                        <i class="fas fa-table relative z-10"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="flex justify-end pt-2">
                                <button type="button"
                                    :disabled="!form.so_number"
                                    class="px-6 py-2 rounded-lg shadow-md flex items-center space-x-2 transition-all duration-300 transform active:scale-95 text-white bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 disabled:opacity-50 disabled:cursor-not-allowed"
                                    @click="proceed"
                                >
                                    <i class="fas fa-arrow-right"></i>
                                    <span>Proceed</span>
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Detail Form Section -->
                    <div v-if="showDetailForm" class="mt-8">
                        <div class="bg-gray-50 border border-gray-300 rounded-lg p-6 shadow-inner">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-2">
                                <div class="flex items-center mb-2">
                                    <label class="w-40 text-sm font-semibold text-gray-700">Current Period:</label>
                                    <input type="text" class="form-input bg-gray-100 border border-gray-300 rounded px-2 py-1 text-sm w-full" :value="detail.current_period" readonly />
                                </div>
                                <div class="flex items-center mb-2">
                                    <label class="w-40 text-sm font-semibold text-gray-700">Sales Order#:</label>
                                    <input type="text" class="form-input bg-gray-100 border border-gray-300 rounded px-2 py-1 text-sm w-full" :value="detail.so_number" readonly />
                                </div>
                                <div class="flex items-center mb-2">
                                    <label class="w-40 text-sm font-semibold text-gray-700">Customer:</label>
                                    <input type="text" class="form-input bg-gray-100 border border-gray-300 rounded px-2 py-1 text-sm w-full" :value="detail.customer" readonly />
                                </div>
                                <div class="flex items-center mb-2">
                                    <label class="w-40 text-sm font-semibold text-gray-700">M/Card Seq#:</label>
                                    <input type="text" class="form-input bg-gray-100 border border-gray-300 rounded px-2 py-1 text-sm w-full" :value="detail.mcard_seq" readonly />
                            </div>
                                <div class="flex items-center mb-2">
                                    <label class="w-40 text-sm font-semibold text-gray-700">Product:</label>
                                    <input type="text" class="form-input bg-gray-100 border border-gray-300 rounded px-2 py-1 text-sm w-20" :value="detail.product" readonly />
                                    <input type="text" class="form-input bg-gray-100 border border-gray-300 rounded px-2 py-1 text-sm ml-2 w-full" :value="detail.product_desc" readonly />
                        </div>
                                <div class="flex items-center mb-2">
                                    <label class="w-40 text-sm font-semibold text-gray-700">Model:</label>
                                    <input type="text" class="form-input bg-gray-100 border border-gray-300 rounded px-2 py-1 text-sm w-full" :value="detail.model" readonly />
                            </div>
                                <div class="flex items-center mb-2">
                                    <label class="w-40 text-sm font-semibold text-gray-700">Order Status:</label>
                                    <input type="text" class="form-input bg-gray-100 border border-gray-300 rounded px-2 py-1 text-sm w-full" :value="detail.order_status" readonly />
                            </div>
                                <div class="flex items-center mb-2">
                                    <label class="w-40 text-sm font-semibold text-gray-700">Analysis Code:</label>
                                    <input type="text" class="form-input border border-gray-300 rounded px-2 py-1 text-sm w-24" v-model="detail.analysis_code" />
                                    <button class="ml-2 text-blue-600"><i class="fas fa-table"></i></button>
                            </div>
                                <div class="flex items-center mb-2">
                                    <label class="w-40 text-sm font-semibold text-gray-700">Entry Date:</label>
                                    <input type="date" class="form-input border border-gray-300 rounded px-2 py-1 text-sm w-40" v-model="detail.entry_date" />
                                    <button class="ml-2 text-blue-600"><i class="fas fa-calendar"></i></button>
                                    <span class="ml-2 text-xs text-gray-500">Mon</span>
                            </div>
                                <div class="flex items-center mb-2">
                                    <label class="w-40 text-sm font-semibold text-gray-700">Entry Reference:</label>
                                    <input type="text" class="form-input border border-gray-300 rounded px-2 py-1 text-sm w-full" v-model="detail.entry_ref" />
                            </div>
                                <div class="flex items-center mb-2">
                                    <label class="w-40 text-sm font-semibold text-gray-700">Set Quantity:</label>
                                    <input type="text" class="form-input border border-gray-300 rounded px-2 py-1 text-sm w-32" v-model="detail.set_qty" />
                                    <span class="ml-2 text-xs text-gray-500">Leave blank for Loose Item Stocking</span>
                    </div>
                                <div class="flex items-center mb-2">
                                    <label class="w-40 text-sm font-semibold text-gray-700">Whse Location:</label>
                                    <input type="text" class="form-input border border-gray-300 rounded px-2 py-1 text-sm w-32" v-model="detail.whse_location" readonly />
                                    <button class="ml-2 text-blue-600" @click="openWarehouseLocationModal"><i class="fas fa-table"></i></button>
                </div>
                                <div class="flex items-center mb-2 md:col-span-2">
                                    <label class="w-40 text-sm font-semibold text-gray-700">Remark:</label>
                                    <input type="text" class="form-input border border-gray-300 rounded px-2 py-1 text-sm flex-1" v-model="detail.remark" />
                                </div>
                            </div>
                        </div>
                                            </div>

                                            </div>
                <!-- Quick Tips -->
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
                        <div class="space-y-4 relative z-10">
                            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 p-4 rounded-lg border-l-4 border-blue-400">
                                <h4 class="font-semibold text-blue-800 mb-2 flex items-center">
                                    <i class="fas fa-info-circle mr-2"></i>
                                    How to Use
                                </h4>
                                <ul class="space-y-2 text-sm text-blue-700">
                                    <li class="flex items-start">
                                        <span class="inline-flex items-center justify-center w-5 h-5 bg-gradient-to-r from-blue-400 to-indigo-400 rounded-full mr-2 shadow-sm mt-0.5">
                                            <i class="fas fa-search text-white text-xs"></i>
                                        </span>
                                        <span>Click "Search Sales Order" to find existing orders.</span>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="inline-flex items-center justify-center w-5 h-5 bg-gradient-to-r from-green-400 to-emerald-400 rounded-full mr-2 shadow-sm mt-0.5">
                                            <i class="fas fa-check text-white text-xs"></i>
                                        </span>
                                        <span>Select a sales order to load its details.</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <WarehouseLocationModal
            :show="isWarehouseLocationModalOpen"
            :warehouseLocations="warehouseLocations"
            @close="closeWarehouseLocationModal"
            @select-location="handleWarehouseLocationSelect"
            @sort-by-code="sortWarehouseLocations('code')"
            @sort-by-name="sortWarehouseLocations('description')"
        />
        <SalesOrderModal 
            :show="isModalOpen" 
            :salesOrders="salesOrders"
            @close="closeSalesOrderModal"
            @select-sales-order="handleSalesOrderSelect"
            @sort-by-so="sortSalesOrders('so_number')"
            @sort-by-customer="sortSalesOrders('customer_name')"
        />
    </AppLayout>
</template>

<script setup>
import { ref, reactive } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import SalesOrderModal from '@/Components/SalesOrderModal.vue';
import WarehouseLocationModal from '@/Components/WarehouseLocationModal.vue';
import axios from 'axios';

const form = reactive({
    current_period: '06/2025',
    so_number: ''
});

const isModalOpen = ref(false);
const salesOrders = ref([]);
const showDetailForm = ref(false);

const detail = reactive({
    current_period: '',
    so_number: '',
    customer: '',
    mcard_seq: '',
    product: '',
    product_desc: '',
    model: '',
    order_status: '',
    analysis_code: '',
    entry_date: '',
    entry_ref: '',
    set_qty: '',
    whse_location: '',
    remark: ''
});

// Warehouse Location Modal State
const isWarehouseLocationModalOpen = ref(false);
const warehouseLocations = ref([]);

const openWarehouseLocationModal = async () => {
    await fetchWarehouseLocations();
    isWarehouseLocationModalOpen.value = true;
};
const closeWarehouseLocationModal = () => {
    isWarehouseLocationModalOpen.value = false;
};
const fetchWarehouseLocations = async () => {
    try {
        const response = await axios.get('/api/warehouse-locations');
        warehouseLocations.value = response.data;
    } catch (error) {
        console.error('Error fetching warehouse locations:', error);
    }
};
const handleWarehouseLocationSelect = (location) => {
    detail.whse_location = location.code;
    closeWarehouseLocationModal();
};
const sortWarehouseLocations = (key) => {
    warehouseLocations.value.sort((a, b) => {
        if (a[key] < b[key]) return -1;
        if (a[key] > b[key]) return 1;
        return 0;
    });
};

const openSalesOrderModal = () => {
    fetchSalesOrders();
    isModalOpen.value = true;
};

const closeSalesOrderModal = () => {
    isModalOpen.value = false;
};

const fetchSalesOrders = async () => {
    try {
        salesOrders.value = [
            {
                so_number: '06-2025-02271',
                customer_po: 'KOS2250600806',
                ac_number: '000506-10',
                mcs_number: 'OL-0000172-4',
                status: 'Completed',
                d_location: 'Same',
                customer_name: 'PT. SELALU CINTA INDONESIA',
                model: '183156-19SBP14',
                order_mode: '0-Order by Customer + Deliver & Invoice to Customer',
                salesperson_code: 'S129',
                salesperson_name: 'MULTI NATIONAL COMPANY DIA',
                order_group: 'Sales',
                order_type: 'S1',
                product: '017',
                product_desc: 'OFFSET',
                order_status: 'Completed'
            },
            {
                so_number: '06-2025-02270',
                customer_po: '250010920P',
                ac_number: '000541-02',
                mcs_number: 'DP-000009',
                status: 'Outs',
                d_location: 'Same',
                customer_name: 'ABC Electronics Inc.',
                model: '183156-20SBP15',
                order_mode: '0-Order by Customer + Deliver & Invoice to Customer',
                salesperson_code: 'S130',
                salesperson_name: 'ANOTHER SALES COMPANY',
                order_group: 'Sales',
                order_type: 'S1'
            },
            {
                so_number: '06-2025-02268',
                customer_po: 'SO PRODUKSI NIKE',
                ac_number: '000506',
                mcs_number: '0063923',
                status: 'Outs',
                d_location: 'Same',
                customer_name: 'NIKE PRODUCTION',
                model: '183156-21SBP16',
                order_mode: '0-Order by Customer + Deliver & Invoice to Customer',
                salesperson_code: 'S131',
                salesperson_name: 'NIKE SALES TEAM',
                order_group: 'Sales',
                order_type: 'S1'
            }
        ];
    } catch (err) {
        // handle error
    }
};

const handleSalesOrderSelect = (salesOrder) => {
    form.so_number = salesOrder.so_number;
    // Pre-fill detail form fields from selected SO
    detail.current_period = form.current_period;
    detail.so_number = salesOrder.so_number;
    detail.customer = salesOrder.customer_name;
    detail.mcard_seq = salesOrder.ac_number;
    detail.product = salesOrder.product || '';
    detail.product_desc = salesOrder.product_desc || '';
    detail.model = salesOrder.model;
    detail.order_status = salesOrder.order_status || salesOrder.status || '';
    detail.analysis_code = '';
    detail.entry_date = '';
    detail.entry_ref = '';
    detail.set_qty = '';
    detail.whse_location = '';
    detail.remark = '';
    showDetailForm.value = false;
    closeSalesOrderModal();
};

const sortSalesOrders = (key) => {
    salesOrders.value.sort((a, b) => {
        if (a[key] < b[key]) return -1;
        if (a[key] > b[key]) return 1;
        return 0;
    });
};

const proceed = () => {
    // Show the detail form below
    showDetailForm.value = true;
};
</script>

<style scoped>
.text-shadow {
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}
</style> 