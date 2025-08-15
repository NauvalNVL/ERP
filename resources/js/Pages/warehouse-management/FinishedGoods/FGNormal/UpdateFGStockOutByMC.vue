<template>
    <AppLayout :header="'Update FG Stock-Out by MC'">
        <!-- Header Section -->
        <div class="bg-gradient-to-r from-pink-600 via-red-600 to-rose-600 p-6 rounded-t-lg shadow-lg overflow-hidden relative">
            <!-- Decorative Elements -->
            <div class="absolute top-0 right-0 w-40 h-40 bg-white opacity-5 rounded-full -translate-y-20 translate-x-20"></div>
            <div class="absolute bottom-0 left-0 w-20 h-20 bg-white opacity-5 rounded-full translate-y-10 -translate-x-10"></div>
            
            <div class="flex items-center">
                <div class="bg-gradient-to-br from-pink-500 to-red-600 p-3 rounded-lg shadow-inner flex items-center justify-center relative overflow-hidden mr-4">
                    <div class="absolute -top-1 -right-1 w-6 h-6 bg-yellow-300 opacity-30 rounded-full animate-ping-slow"></div>
                    <div class="absolute -bottom-1 -left-1 w-4 h-4 bg-blue-300 opacity-30 rounded-full animate-ping-slow animation-delay-500"></div>
                    <i class="fas fa-dolly-flatbed text-white text-2xl z-10"></i>
                </div>
                <div>
                    <h2 class="text-2xl md:text-3xl font-bold text-white mb-1 text-shadow">Update FG Stock-Out by MC</h2>
                    <p class="text-pink-100 max-w-2xl">Process finished goods stock-out for material consumption</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-b-lg shadow-lg p-6 mb-6">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left Column - MC Search and Details -->
                <div class="lg:col-span-1">
                    <div class="bg-white p-6 rounded-lg shadow-md border-t-4 border-red-500 mb-6">
                        <div class="flex items-center mb-6 pb-2 border-b border-gray-200">
                            <div class="p-2 bg-gradient-to-r from-red-500 to-rose-600 rounded-lg mr-3 shadow-md">
                                <i class="fas fa-search text-white"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-800">Search MC</h3>
                        </div>

                        <form @submit.prevent="searchMC" class="space-y-4">
                            <div>
                                <label for="mc_number" class="block text-sm font-medium text-gray-700 mb-1">MC Number:</label>
                                <div class="flex">
                                    <input
                                        type="text"
                                        id="mc_number"
                                        v-model="mc.mc_number"
                                        placeholder="Enter MC number"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-l-md focus:outline-none focus:ring-2 focus:ring-red-500"
                                    />
                                    <button
                                        type="submit"
                                        class="px-3 py-2 bg-red-600 text-white rounded-r-md hover:bg-red-700"
                                    >
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>

                            <div v-if="mc.wo_number">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Work Order:</label>
                                <div class="px-3 py-2 border border-gray-300 rounded-md bg-gray-50">
                                    {{ mc.wo_number }}
                                </div>
                            </div>

                            <div v-if="mc.product_code" class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Product:</label>
                                    <div class="px-3 py-2 border border-gray-300 rounded-md bg-gray-50 font-medium">
                                        {{ mc.product_code }}
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Quantity:</label>
                                    <div class="px-3 py-2 border border-gray-300 rounded-md bg-gray-50 font-medium">
                                        {{ mc.quantity.toLocaleString() }}
                                    </div>
                                </div>
                            </div>

                            <div v-if="mc.request_date">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Request Date:</label>
                                <div class="px-3 py-2 border border-gray-300 rounded-md bg-gray-50">
                                    {{ mc.request_date }}
                                </div>
                            </div>

                            <div v-if="mc.status">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Status:</label>
                                <div 
                                    class="px-3 py-2 border rounded-md font-medium"
                                    :class="getStatusClass(mc.status)"
                                >
                                    {{ mc.status }}
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="bg-white p-6 rounded-lg shadow-md border-t-4 border-blue-500">
                        <div class="flex items-center mb-6 pb-2 border-b border-gray-200">
                            <div class="p-2 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-lg mr-3 shadow-md">
                                <i class="fas fa-sign-out-alt text-white"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-800">Stock-Out Details</h3>
                        </div>

                        <form @submit.prevent="saveStockOut" class="space-y-4">
                            <div>
                                <label for="warehouse" class="block text-sm font-medium text-gray-700 mb-1">Warehouse:</label>
                                <select
                                    id="warehouse"
                                    v-model="stockOut.warehouse"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    required
                                >
                                    <option value="">Select Warehouse</option>
                                    <option value="FG01">FG01 - Main Finished Goods</option>
                                    <option value="FG02">FG02 - Secondary Finished Goods</option>
                                </select>
                            </div>

                            <div>
                                <label for="stock_out_date" class="block text-sm font-medium text-gray-700 mb-1">Stock-Out Date:</label>
                                <input
                                    type="date"
                                    id="stock_out_date"
                                    v-model="stockOut.date"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    required
                                />
                            </div>

                            <div>
                                <label for="reference" class="block text-sm font-medium text-gray-700 mb-1">Reference Number:</label>
                                <input
                                    type="text"
                                    id="reference"
                                    v-model="stockOut.reference"
                                    placeholder="Enter reference number"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                />
                            </div>

                            <div>
                                <label for="remarks" class="block text-sm font-medium text-gray-700 mb-1">Remarks:</label>
                                <textarea
                                    id="remarks"
                                    v-model="stockOut.remarks"
                                    rows="3"
                                    placeholder="Enter remarks or notes"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                ></textarea>
                            </div>

                            <div class="flex justify-end pt-4">
                                <button
                                    type="button"
                                    @click="resetForm"
                                    class="px-4 py-2 bg-gray-100 hover:bg-gray-200 border border-gray-300 rounded-md text-sm mr-2"
                                >
                                    Reset
                                </button>
                                <button
                                    type="submit"
                                    class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md text-sm shadow-md"
                                    :disabled="!canSave"
                                >
                                    Process Stock-Out
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Right Column - Material Items and Stock Info -->
                <div class="lg:col-span-2">
                    <!-- Material Items -->
                    <div class="bg-white p-6 rounded-lg shadow-md border-t-4 border-indigo-500 mb-6">
                        <div class="flex items-center justify-between mb-6 pb-2 border-b border-gray-200">
                            <div class="flex items-center">
                                <div class="p-2 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-lg mr-3 shadow-md">
                                    <i class="fas fa-list-alt text-white"></i>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-800">Material Items</h3>
                            </div>
                        </div>

                        <!-- Materials Table -->
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Material
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Description
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Required Qty
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            UoM
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Status
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr v-for="(item, index) in mcItems" :key="index" class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ item.material_code }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-700">
                                            {{ item.description }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-indigo-600">
                                            {{ item.required_qty.toLocaleString() }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                            {{ item.uom }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span :class="getMaterialStatusClass(item.status)">
                                                {{ item.status }}
                                            </span>
                                        </td>
                                    </tr>
                                    <tr v-if="mcItems.length === 0">
                                        <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">
                                            <div class="flex flex-col items-center py-6">
                                                <i class="fas fa-boxes text-gray-300 text-5xl mb-4"></i>
                                                <p class="text-gray-600 font-medium">No materials found</p>
                                                <p class="text-gray-400 mt-1">Search for an MC to view material items</p>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Stock Information -->
                    <div class="bg-white p-6 rounded-lg shadow-md border-t-4 border-green-500">
                        <div class="flex items-center justify-between mb-6 pb-2 border-b border-gray-200">
                            <div class="flex items-center">
                                <div class="p-2 bg-gradient-to-r from-green-500 to-emerald-600 rounded-lg mr-3 shadow-md">
                                    <i class="fas fa-boxes text-white"></i>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-800">Available Stock</h3>
                            </div>
                        </div>

                        <!-- Stock Table -->
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Warehouse
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Product
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Batch/Lot
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Available Qty
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Select
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr v-for="(item, index) in availableStock" :key="index" class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                            {{ item.warehouse }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ item.product_code }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                            {{ item.batch }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-green-600">
                                            {{ item.available_qty.toLocaleString() }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <input
                                                type="radio"
                                                name="selected_stock"
                                                :value="index"
                                                v-model="selectedStockIndex"
                                                class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300"
                                            />
                                        </td>
                                    </tr>
                                    <tr v-if="availableStock.length === 0">
                                        <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">
                                            <div class="flex flex-col items-center py-6">
                                                <i class="fas fa-exclamation-circle text-gray-300 text-5xl mb-4"></i>
                                                <p class="text-gray-600 font-medium">No available stock found</p>
                                                <p class="text-gray-400 mt-1">Select a warehouse to view available stock</p>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Stock Selection Summary -->
                        <div v-if="selectedStockIndex !== null" class="mt-6 p-4 bg-blue-50 rounded-md border border-blue-200">
                            <div class="text-sm text-blue-800 font-medium mb-2">Selected Stock</div>
                            <div class="grid grid-cols-2 gap-4 text-sm">
                                <div>
                                    <span class="text-gray-600">Product:</span> 
                                    <span class="font-medium">{{ availableStock[selectedStockIndex].product_code }}</span>
                                </div>
                                <div>
                                    <span class="text-gray-600">Batch/Lot:</span> 
                                    <span class="font-medium">{{ availableStock[selectedStockIndex].batch }}</span>
                                </div>
                                <div>
                                    <span class="text-gray-600">Warehouse:</span> 
                                    <span class="font-medium">{{ availableStock[selectedStockIndex].warehouse }}</span>
                                </div>
                                <div>
                                    <span class="text-gray-600">Available Qty:</span> 
                                    <span class="font-medium text-green-600">{{ availableStock[selectedStockIndex].available_qty.toLocaleString() }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref, reactive, computed, onMounted } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import axios from 'axios';

// State
const mc = reactive({
    mc_number: '',
    wo_number: '',
    product_code: '',
    product_description: '',
    quantity: 0,
    request_date: '',
    status: ''
});

const stockOut = reactive({
    warehouse: '',
    date: new Date().toISOString().slice(0, 10), // Today's date in YYYY-MM-DD format
    reference: '',
    remarks: ''
});

const mcItems = ref([]);
const availableStock = ref([]);
const selectedStockIndex = ref(null);
const loading = ref(false);
const error = ref(null);

// Computed
const canSave = computed(() => {
    return mc.mc_number && 
           stockOut.warehouse && 
           stockOut.date &&
           selectedStockIndex.value !== null &&
           mc.status === 'Pending';
});

// Methods
const searchMC = async () => {
    if (!mc.mc_number) return;

    try {
        loading.value = true;
        error.value = null;

        // Demo data for display purposes
        mc.wo_number = 'WO-2023-0156';
        mc.product_code = 'FG10001';
        mc.product_description = 'Product A - Red';
        mc.quantity = 50;
        mc.request_date = '2023-05-15';
        mc.status = 'Pending';
        
        mcItems.value = [
            { 
                material_code: 'RM001', 
                description: 'Raw Material A', 
                required_qty: 25, 
                uom: 'KG',
                status: 'Available'
            },
            { 
                material_code: 'RM002', 
                description: 'Raw Material B', 
                required_qty: 15, 
                uom: 'LTR',
                status: 'Available'
            },
            { 
                material_code: 'RM003', 
                description: 'Raw Material C', 
                required_qty: 10, 
                uom: 'PCS',
                status: 'Partial'
            }
        ];

        loading.value = false;

        /* For actual API implementation:
        const response = await axios.get(`/api/material-consumption/${mc.mc_number}`);
        
        if (response.data) {
            mc.wo_number = response.data.wo_number;
            mc.product_code = response.data.product_code;
            mc.product_description = response.data.product_description;
            mc.quantity = response.data.quantity;
            mc.request_date = response.data.request_date;
            mc.status = response.data.status;
            
            mcItems.value = response.data.items;
        } else {
            error.value = 'Material consumption not found';
        }
        */
        
    } catch (err) {
        console.error('Error fetching material consumption:', err);
        error.value = 'Failed to load material consumption data';
        loading.value = false;
    }
};

const fetchAvailableStock = async () => {
    if (!stockOut.warehouse || !mc.product_code) return;

    try {
        loading.value = true;
        selectedStockIndex.value = null;

        // Demo data for display purposes
        availableStock.value = [
            { 
                warehouse: stockOut.warehouse, 
                product_code: mc.product_code, 
                batch: 'BATCH-2023-001', 
                available_qty: 100 
            },
            { 
                warehouse: stockOut.warehouse, 
                product_code: mc.product_code, 
                batch: 'BATCH-2023-002', 
                available_qty: 75 
            }
        ];

        loading.value = false;

        /* For actual API implementation:
        const response = await axios.get('/api/inventory/available-stock', {
            params: {
                warehouse: stockOut.warehouse,
                product_code: mc.product_code
            }
        });
        
        availableStock.value = response.data;
        */
        
    } catch (err) {
        console.error('Error fetching available stock:', err);
        error.value = 'Failed to load available stock data';
        loading.value = false;
    }
};

const resetForm = () => {
    stockOut.warehouse = '';
    stockOut.reference = '';
    stockOut.remarks = '';
    stockOut.date = new Date().toISOString().slice(0, 10);
    availableStock.value = [];
    selectedStockIndex.value = null;
};

const getStatusClass = (status) => {
    switch (status.toLowerCase()) {
        case 'completed':
            return 'border-green-300 bg-green-50 text-green-800';
        case 'pending':
            return 'border-blue-300 bg-blue-50 text-blue-800';
        case 'processing':
            return 'border-indigo-300 bg-indigo-50 text-indigo-800';
        case 'cancelled':
            return 'border-red-300 bg-red-50 text-red-800';
        default:
            return 'border-gray-300 bg-gray-50 text-gray-800';
    }
};

const getMaterialStatusClass = (status) => {
    const classes = 'px-2 py-1 text-xs font-semibold rounded-full';
    switch (status.toLowerCase()) {
        case 'available':
            return `${classes} bg-green-100 text-green-800`;
        case 'partial':
            return `${classes} bg-yellow-100 text-yellow-800`;
        case 'unavailable':
            return `${classes} bg-red-100 text-red-800`;
        default:
            return `${classes} bg-gray-100 text-gray-800`;
    }
};

const saveStockOut = async () => {
    if (selectedStockIndex.value === null) {
        alert('Please select a stock item');
        return;
    }

    try {
        loading.value = true;
        
        const selectedStock = availableStock.value[selectedStockIndex.value];
        
        const stockOutData = {
            mc_number: mc.mc_number,
            warehouse: stockOut.warehouse,
            product_code: mc.product_code,
            batch: selectedStock.batch,
            quantity: mc.quantity,
            date: stockOut.date,
            reference: stockOut.reference,
            remarks: stockOut.remarks
        };
        
        // For demo purposes
        console.log('Stock-out data to save:', stockOutData);
        
        // Update status
        mc.status = 'Completed';
        
        alert('Stock-out processed successfully!');
        
        /* For actual API implementation:
        const response = await axios.post('/api/fg-stock-out/mc', stockOutData);
        
        if (response.data.success) {
            alert('Stock-out processed successfully!');
            mc.status = 'Completed';
        }
        */
        
        loading.value = false;
    } catch (err) {
        console.error('Error processing stock-out:', err);
        error.value = 'Failed to process stock-out';
        loading.value = false;
        alert('Error: ' + (err.response?.data?.message || err.message));
    }
};

// Watch for warehouse changes to fetch available stock
const watchWarehouse = () => {
    if (stockOut.warehouse && mc.product_code) {
        fetchAvailableStock();
    } else {
        availableStock.value = [];
        selectedStockIndex.value = null;
    }
};

// Lifecycle hooks
onMounted(() => {
    // Set up watchers
    watch(() => stockOut.warehouse, watchWarehouse);
});
</script>

<script>
// Need to add the watch import in the options API part
import { watch } from 'vue';

export default {
    // Options API part just for the watch setup
}
</script>

<style scoped>
.text-shadow {
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}
</style> 