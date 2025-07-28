<template>
    <AppLayout :header="'Update FG Stock-In by WO'">
        <!-- Header Section -->
        <div class="bg-gradient-to-r from-purple-600 via-indigo-600 to-blue-600 p-6 rounded-t-lg shadow-lg overflow-hidden relative">
            <!-- Decorative Elements -->
            <div class="absolute top-0 right-0 w-40 h-40 bg-white opacity-5 rounded-full -translate-y-20 translate-x-20"></div>
            <div class="absolute bottom-0 left-0 w-20 h-20 bg-white opacity-5 rounded-full translate-y-10 -translate-x-10"></div>
            
            <div class="flex items-center">
                <div class="bg-gradient-to-br from-indigo-500 to-purple-600 p-3 rounded-lg shadow-inner flex items-center justify-center relative overflow-hidden mr-4">
                    <div class="absolute -top-1 -right-1 w-6 h-6 bg-yellow-300 opacity-30 rounded-full animate-ping-slow"></div>
                    <div class="absolute -bottom-1 -left-1 w-4 h-4 bg-blue-300 opacity-30 rounded-full animate-ping-slow animation-delay-500"></div>
                    <i class="fas fa-industry text-white text-2xl z-10"></i>
                </div>
                <div>
                    <h2 class="text-2xl md:text-3xl font-bold text-white mb-1 text-shadow">Update FG Stock-In by WO</h2>
                    <p class="text-blue-100 max-w-2xl">Process finished goods stock-in based on work order</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-b-lg shadow-lg p-6 mb-6">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left Column - Form -->
                <div class="lg:col-span-1">
                    <div class="bg-white p-6 rounded-lg shadow-md border-t-4 border-indigo-500 mb-6">
                        <div class="flex items-center mb-6 pb-2 border-b border-gray-200">
                            <div class="p-2 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-lg mr-3 shadow-md">
                                <i class="fas fa-clipboard-list text-white"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-800">Work Order Information</h3>
                        </div>

                        <form @submit.prevent="searchWorkOrder" class="space-y-4">
                            <div>
                                <label for="wo_number" class="block text-sm font-medium text-gray-700 mb-1">Work Order Number:</label>
                                <div class="flex">
                                    <input
                                        type="text"
                                        id="wo_number"
                                        v-model="workOrder.wo_number"
                                        placeholder="Enter WO number"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-l-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                    />
                                    <button
                                        type="submit"
                                        class="px-3 py-2 bg-indigo-600 text-white rounded-r-md hover:bg-indigo-700"
                                    >
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>

                            <div v-if="workOrder.product_code">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Product:</label>
                                <div class="flex flex-col px-3 py-2 border border-gray-300 rounded-md bg-gray-50">
                                    <span class="font-medium">{{ workOrder.product_code }}</span>
                                    <span class="text-sm text-gray-600">{{ workOrder.product_description }}</span>
                                </div>
                            </div>

                            <div v-if="workOrder.start_date" class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Start Date:</label>
                                    <div class="px-3 py-2 border border-gray-300 rounded-md bg-gray-50">
                                        {{ workOrder.start_date }}
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Due Date:</label>
                                    <div class="px-3 py-2 border border-gray-300 rounded-md bg-gray-50">
                                        {{ workOrder.due_date }}
                                    </div>
                                </div>
                            </div>

                            <div v-if="workOrder.status">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Status:</label>
                                <div 
                                    class="px-3 py-2 border rounded-md font-medium"
                                    :class="getStatusClass(workOrder.status)"
                                >
                                    {{ workOrder.status }}
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="bg-white p-6 rounded-lg shadow-md border-t-4 border-blue-500">
                        <div class="flex items-center mb-6 pb-2 border-b border-gray-200">
                            <div class="p-2 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-lg mr-3 shadow-md">
                                <i class="fas fa-warehouse text-white"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-800">Stock-In Details</h3>
                        </div>

                        <form @submit.prevent="saveStockIn" class="space-y-4">
                            <div>
                                <label for="warehouse" class="block text-sm font-medium text-gray-700 mb-1">Warehouse:</label>
                                <select
                                    id="warehouse"
                                    v-model="stockIn.warehouse"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    required
                                >
                                    <option value="">Select Warehouse</option>
                                    <option value="FG01">FG01 - Main Finished Goods</option>
                                    <option value="FG02">FG02 - Secondary Finished Goods</option>
                                    <option value="FG03">FG03 - Transit Warehouse</option>
                                </select>
                            </div>

                            <div>
                                <label for="quantity" class="block text-sm font-medium text-gray-700 mb-1">Quantity:</label>
                                <input
                                    type="number"
                                    id="quantity"
                                    v-model.number="stockIn.quantity"
                                    min="1"
                                    :max="workOrder.planned_quantity || 9999"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    required
                                />
                                <div class="text-xs text-gray-500 mt-1" v-if="workOrder.planned_quantity">
                                    Planned: {{ workOrder.planned_quantity.toLocaleString() }} | 
                                    Received: {{ workOrder.completed_quantity.toLocaleString() }} | 
                                    Balance: {{ (workOrder.planned_quantity - workOrder.completed_quantity).toLocaleString() }}
                                </div>
                            </div>

                            <div>
                                <label for="batch_number" class="block text-sm font-medium text-gray-700 mb-1">Batch/Lot Number:</label>
                                <input
                                    type="text"
                                    id="batch_number"
                                    v-model="stockIn.batch_number"
                                    placeholder="Enter batch or lot number"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    required
                                />
                            </div>

                            <div>
                                <label for="stock_in_date" class="block text-sm font-medium text-gray-700 mb-1">Stock-In Date:</label>
                                <input
                                    type="date"
                                    id="stock_in_date"
                                    v-model="stockIn.date"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    required
                                />
                            </div>

                            <div>
                                <label for="reference" class="block text-sm font-medium text-gray-700 mb-1">Reference Number:</label>
                                <input
                                    type="text"
                                    id="reference"
                                    v-model="stockIn.reference"
                                    placeholder="Enter reference number"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                />
                            </div>

                            <div>
                                <label for="remarks" class="block text-sm font-medium text-gray-700 mb-1">Remarks:</label>
                                <textarea
                                    id="remarks"
                                    v-model="stockIn.remarks"
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
                                    Save Stock-In
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Right Column - Production History -->
                <div class="lg:col-span-2">
                    <div class="bg-white p-6 rounded-lg shadow-md border-t-4 border-purple-500 mb-6">
                        <div class="flex items-center justify-between mb-6 pb-2 border-b border-gray-200">
                            <div class="flex items-center">
                                <div class="p-2 bg-gradient-to-r from-purple-500 to-indigo-600 rounded-lg mr-3 shadow-md">
                                    <i class="fas fa-chart-line text-white"></i>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-800">Production Progress</h3>
                            </div>
                        </div>

                        <div class="mb-8" v-if="workOrder.planned_quantity">
                            <!-- Progress Bar -->
                            <div class="mb-2 flex justify-between text-sm">
                                <span class="font-medium">
                                    {{ calculateProgressPercentage() }}% Complete
                                </span>
                                <span>
                                    {{ workOrder.completed_quantity.toLocaleString() }} / {{ workOrder.planned_quantity.toLocaleString() }}
                                </span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2.5">
                                <div 
                                    class="h-2.5 rounded-full bg-gradient-to-r from-purple-500 to-indigo-500" 
                                    :style="{ width: `${calculateProgressPercentage()}%` }"
                                ></div>
                            </div>
                        </div>

                        <!-- Production Stats -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                            <div class="bg-indigo-50 p-4 rounded-lg border border-indigo-100">
                                <div class="text-sm text-indigo-800 font-medium">Planned Quantity</div>
                                <div class="text-2xl font-bold text-indigo-700">{{ workOrder.planned_quantity?.toLocaleString() || 0 }}</div>
                            </div>
                            <div class="bg-green-50 p-4 rounded-lg border border-green-100">
                                <div class="text-sm text-green-800 font-medium">Completed</div>
                                <div class="text-2xl font-bold text-green-700">{{ workOrder.completed_quantity?.toLocaleString() || 0 }}</div>
                            </div>
                            <div class="bg-blue-50 p-4 rounded-lg border border-blue-100">
                                <div class="text-sm text-blue-800 font-medium">Remaining</div>
                                <div class="text-2xl font-bold text-blue-700">{{ ((workOrder.planned_quantity || 0) - (workOrder.completed_quantity || 0)).toLocaleString() }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Stock-In History -->
                    <div class="bg-white p-6 rounded-lg shadow-md border-t-4 border-green-500">
                        <div class="flex items-center justify-between mb-6 pb-2 border-b border-gray-200">
                            <div class="flex items-center">
                                <div class="p-2 bg-gradient-to-r from-green-500 to-emerald-600 rounded-lg mr-3 shadow-md">
                                    <i class="fas fa-history text-white"></i>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-800">Stock-In History</h3>
                            </div>
                        </div>

                        <!-- History Table -->
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Date
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Warehouse
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Batch/Lot
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Quantity
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Reference
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Created By
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr v-for="(item, index) in stockInHistory" :key="index" class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                            {{ item.date }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                            {{ item.warehouse }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                            {{ item.batch_number }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-green-600">
                                            {{ item.quantity.toLocaleString() }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                            {{ item.reference }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                            {{ item.created_by }}
                                        </td>
                                    </tr>
                                    <tr v-if="stockInHistory.length === 0">
                                        <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">
                                            <div class="flex flex-col items-center py-6">
                                                <i class="fas fa-inbox text-gray-400 text-5xl mb-4"></i>
                                                <p class="text-gray-600 font-medium">No stock-in history found</p>
                                                <p class="text-gray-400 mt-1">This work order has no stock-in transactions yet</p>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
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
const workOrder = reactive({
    wo_number: '',
    product_code: '',
    product_description: '',
    planned_quantity: 0,
    completed_quantity: 0,
    start_date: '',
    due_date: '',
    status: ''
});

const stockIn = reactive({
    warehouse: '',
    quantity: 0,
    batch_number: '',
    date: new Date().toISOString().slice(0, 10), // Today's date in YYYY-MM-DD format
    reference: '',
    remarks: ''
});

const stockInHistory = ref([]);
const loading = ref(false);
const error = ref(null);

// Computed
const canSave = computed(() => {
    return workOrder.wo_number && 
           stockIn.warehouse && 
           stockIn.date &&
           stockIn.batch_number &&
           stockIn.quantity > 0 &&
           stockIn.quantity <= (workOrder.planned_quantity - workOrder.completed_quantity);
});

// Methods
const searchWorkOrder = async () => {
    if (!workOrder.wo_number) return;

    try {
        loading.value = true;
        error.value = null;

        // Demo data for display purposes
        workOrder.product_code = 'FG10001';
        workOrder.product_description = 'Product A - Red';
        workOrder.planned_quantity = 1000;
        workOrder.completed_quantity = 600;
        workOrder.start_date = '2023-05-10';
        workOrder.due_date = '2023-05-20';
        workOrder.status = 'In Progress';
        
        // Demo stock-in history
        stockInHistory.value = [
            { 
                date: '2023-05-12', 
                warehouse: 'FG01', 
                batch_number: 'BATCH-2023-001', 
                quantity: 250,
                reference: 'REF-001',
                created_by: 'John Doe'
            },
            { 
                date: '2023-05-15', 
                warehouse: 'FG01', 
                batch_number: 'BATCH-2023-002', 
                quantity: 350,
                reference: 'REF-002',
                created_by: 'Jane Smith'
            },
        ];

        loading.value = false;

        /* For actual API implementation:
        const response = await axios.get(`/api/work-orders/${workOrder.wo_number}`);
        
        workOrder.product_code = response.data.product_code;
        workOrder.product_description = response.data.product_description;
        workOrder.planned_quantity = response.data.planned_quantity;
        workOrder.completed_quantity = response.data.completed_quantity;
        workOrder.start_date = response.data.start_date;
        workOrder.due_date = response.data.due_date;
        workOrder.status = response.data.status;
        
        const historyResponse = await axios.get(`/api/work-orders/${workOrder.wo_number}/stock-in-history`);
        stockInHistory.value = historyResponse.data;
        */
        
    } catch (err) {
        console.error('Error fetching work order:', err);
        error.value = 'Failed to load work order data';
        loading.value = false;
    }
};

const calculateProgressPercentage = () => {
    if (!workOrder.planned_quantity) return 0;
    
    const percentage = (workOrder.completed_quantity / workOrder.planned_quantity) * 100;
    return Math.min(100, Math.round(percentage));
};

const resetForm = () => {
    stockIn.warehouse = '';
    stockIn.quantity = 0;
    stockIn.batch_number = '';
    stockIn.reference = '';
    stockIn.remarks = '';
    stockIn.date = new Date().toISOString().slice(0, 10);
};

const getStatusClass = (status) => {
    switch (status.toLowerCase()) {
        case 'completed':
            return 'border-green-300 bg-green-50 text-green-800';
        case 'in progress':
            return 'border-blue-300 bg-blue-50 text-blue-800';
        case 'planned':
            return 'border-indigo-300 bg-indigo-50 text-indigo-800';
        case 'delayed':
            return 'border-yellow-300 bg-yellow-50 text-yellow-800';
        case 'cancelled':
            return 'border-red-300 bg-red-50 text-red-800';
        default:
            return 'border-gray-300 bg-gray-50 text-gray-800';
    }
};

const saveStockIn = async () => {
    try {
        loading.value = true;
        
        const stockInData = {
            wo_number: workOrder.wo_number,
            product_code: workOrder.product_code,
            warehouse: stockIn.warehouse,
            quantity: stockIn.quantity,
            batch_number: stockIn.batch_number,
            date: stockIn.date,
            reference: stockIn.reference,
            remarks: stockIn.remarks
        };
        
        // For demo purposes
        console.log('Stock-in data to save:', stockInData);
        
        // Update the work order completion quantity
        workOrder.completed_quantity += stockIn.quantity;
        
        // Add to history
        stockInHistory.value.unshift({
            date: stockIn.date,
            warehouse: stockIn.warehouse,
            batch_number: stockIn.batch_number,
            quantity: stockIn.quantity,
            reference: stockIn.reference,
            created_by: 'Current User'
        });
        
        alert('Stock-in processed successfully!');
        resetForm();
        
        /* For actual API implementation:
        const response = await axios.post('/api/fg-stock-in/work-order', stockInData);
        
        if (response.data.success) {
            alert('Stock-in processed successfully!');
            resetForm();
            searchWorkOrder(); // Refresh WO data and history
        }
        */
        
        loading.value = false;
    } catch (err) {
        console.error('Error processing stock-in:', err);
        error.value = 'Failed to process stock-in';
        loading.value = false;
        alert('Error: ' + (err.response?.data?.message || err.message));
    }
};

// Lifecycle hooks
onMounted(() => {
    // Any initialization code
});
</script>

<style scoped>
.text-shadow {
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}
</style> 