<template>
    <AppLayout :header="'Update FG Stock-Out by Batch'">
        <!-- Header Section -->
        <div class="bg-gradient-to-r from-orange-600 via-amber-600 to-yellow-600 p-6 rounded-t-lg shadow-lg overflow-hidden relative">
            <!-- Decorative Elements -->
            <div class="absolute top-0 right-0 w-40 h-40 bg-white opacity-5 rounded-full -translate-y-20 translate-x-20"></div>
            <div class="absolute bottom-0 left-0 w-20 h-20 bg-white opacity-5 rounded-full translate-y-10 -translate-x-10"></div>
            
            <div class="flex items-center">
                <div class="bg-gradient-to-br from-amber-500 to-orange-600 p-3 rounded-lg shadow-inner flex items-center justify-center relative overflow-hidden mr-4">
                    <div class="absolute -top-1 -right-1 w-6 h-6 bg-yellow-300 opacity-30 rounded-full animate-ping-slow"></div>
                    <div class="absolute -bottom-1 -left-1 w-4 h-4 bg-orange-300 opacity-30 rounded-full animate-ping-slow animation-delay-500"></div>
                    <i class="fas fa-pallet text-white text-2xl z-10"></i>
                </div>
                <div>
                    <h2 class="text-2xl md:text-3xl font-bold text-white mb-1 text-shadow">Update FG Stock-Out by Batch</h2>
                    <p class="text-amber-100 max-w-2xl">Process batch stock-out for finished goods inventory</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-b-lg shadow-lg p-6 mb-6">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left Column - Stock-Out Form -->
                <div class="lg:col-span-1">
                    <div class="bg-white p-6 rounded-lg shadow-md border-t-4 border-amber-500">
                        <div class="flex items-center mb-6 pb-2 border-b border-gray-200">
                            <div class="p-2 bg-gradient-to-r from-amber-500 to-orange-600 rounded-lg mr-3 shadow-md">
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
                                    @change="fetchAvailableStock"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-amber-500"
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
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-amber-500"
                                    required
                                />
                            </div>

                            <div>
                                <label for="customer" class="block text-sm font-medium text-gray-700 mb-1">Customer:</label>
                                <div class="flex">
                                    <input
                                        type="text"
                                        id="customer"
                                        v-model="stockOut.customer_code"
                                        placeholder="Customer code"
                                        class="w-32 px-3 py-2 border border-gray-300 rounded-l-md focus:outline-none focus:ring-2 focus:ring-amber-500"
                                    />
                                    <button
                                        type="button"
                                        @click="openCustomerModal"
                                        class="px-3 py-2 bg-gray-100 border border-l-0 border-gray-300 rounded-r-md hover:bg-gray-200"
                                    >
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                                <input
                                    type="text"
                                    v-model="stockOut.customer_name"
                                    placeholder="Customer name"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md mt-1 focus:outline-none focus:ring-2 focus:ring-amber-500"
                                    readonly
                                />
                            </div>

                            <div>
                                <label for="reference" class="block text-sm font-medium text-gray-700 mb-1">Reference/DO Number:</label>
                                <input
                                    type="text"
                                    id="reference"
                                    v-model="stockOut.reference"
                                    placeholder="Enter reference or DO number"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-amber-500"
                                />
                            </div>

                            <div>
                                <label for="remarks" class="block text-sm font-medium text-gray-700 mb-1">Remarks:</label>
                                <textarea
                                    id="remarks"
                                    v-model="stockOut.remarks"
                                    rows="3"
                                    placeholder="Enter remarks or notes"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-amber-500"
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
                                    class="px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white rounded-md text-sm shadow-md"
                                    :disabled="!canSave"
                                >
                                    Process Stock-Out
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Right Column - Available Stock and Selected Items -->
                <div class="lg:col-span-2">
                    <!-- Available Stock -->
                    <div class="bg-white p-6 rounded-lg shadow-md border-t-4 border-orange-500 mb-6">
                        <div class="flex items-center justify-between mb-6 pb-2 border-b border-gray-200">
                            <div class="flex items-center">
                                <div class="p-2 bg-gradient-to-r from-orange-500 to-amber-600 rounded-lg mr-3 shadow-md">
                                    <i class="fas fa-boxes text-white"></i>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-800">Available Stock</h3>
                            </div>
                            <div class="flex items-center">
                                <div class="relative">
                                    <input
                                        type="text"
                                        v-model="searchQuery"
                                        placeholder="Search products..."
                                        class="w-64 px-3 py-1.5 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-amber-500"
                                    />
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                        <i class="fas fa-search text-gray-400"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Available Stock Table -->
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Product Code
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Description
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Batch/Lot
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Available Qty
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Actions
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr v-for="(item, index) in filteredAvailableStock" :key="index" class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ item.product_code }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                            {{ item.description }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                            {{ item.batch }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-green-600">
                                            {{ item.available_qty.toLocaleString() }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <button 
                                                @click="openStockOutModal(item)"
                                                class="bg-orange-100 text-orange-700 hover:bg-orange-200 px-3 py-1 rounded-md text-sm font-medium"
                                            >
                                                Stock-Out
                                            </button>
                                        </td>
                                    </tr>
                                    <tr v-if="availableStock.length === 0">
                                        <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">
                                            <div class="flex flex-col items-center py-6">
                                                <i class="fas fa-boxes text-gray-300 text-5xl mb-4"></i>
                                                <p class="text-gray-600 font-medium">No available stock found</p>
                                                <p class="text-gray-400 mt-1">Select a warehouse to view available stock</p>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr v-if="availableStock.length > 0 && filteredAvailableStock.length === 0">
                                        <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">
                                            <div class="flex flex-col items-center py-6">
                                                <i class="fas fa-search text-gray-300 text-5xl mb-4"></i>
                                                <p class="text-gray-600 font-medium">No matching products found</p>
                                                <p class="text-gray-400 mt-1">Try adjusting your search query</p>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Selected Items -->
                    <div class="bg-white p-6 rounded-lg shadow-md border-t-4 border-red-500">
                        <div class="flex items-center justify-between mb-6 pb-2 border-b border-gray-200">
                            <div class="flex items-center">
                                <div class="p-2 bg-gradient-to-r from-red-500 to-orange-600 rounded-lg mr-3 shadow-md">
                                    <i class="fas fa-shipping-fast text-white"></i>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-800">Stock-Out Items</h3>
                            </div>
                            <div class="text-sm">
                                Total Items: <span class="font-medium">{{ stockOutItems.length }}</span>
                            </div>
                        </div>

                        <!-- Stock-Out Items Table -->
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Product Code
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Description
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Batch/Lot
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Stock-Out Qty
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Actions
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr v-for="(item, index) in stockOutItems" :key="index" class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ item.product_code }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                            {{ item.description }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                            {{ item.batch }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-red-600">
                                            {{ item.stockout_qty.toLocaleString() }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <button 
                                                @click="editStockOutItem(index)" 
                                                class="text-indigo-600 hover:text-indigo-900 mr-3"
                                            >
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button 
                                                @click="removeStockOutItem(index)"
                                                class="text-red-600 hover:text-red-900"
                                            >
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr v-if="stockOutItems.length === 0">
                                        <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">
                                            <div class="flex flex-col items-center py-6">
                                                <i class="fas fa-truck-loading text-gray-300 text-5xl mb-4"></i>
                                                <p class="text-gray-600 font-medium">No stock-out items added</p>
                                                <p class="text-gray-400 mt-1">Click 'Stock-Out' on available items to add them</p>
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

        <!-- Stock-Out Modal -->
        <div v-if="showModal" class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
                <div class="px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900">Stock-Out Item</h3>
                        <button @click="closeModal" class="text-gray-400 hover:text-gray-500">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
                <div class="p-6">
                    <div class="mb-4">
                        <p class="text-sm text-gray-700 mb-2">
                            <span class="font-semibold">Product:</span> {{ currentItem.product_code }} - {{ currentItem.description }}
                        </p>
                        <p class="text-sm text-gray-700 mb-2">
                            <span class="font-semibold">Batch/Lot:</span> {{ currentItem.batch }}
                        </p>
                        <p class="text-sm text-gray-700 mb-4">
                            <span class="font-semibold">Available Quantity:</span> {{ currentItem.available_qty?.toLocaleString() }}
                        </p>
                    </div>
                    <div class="mb-4">
                        <label for="stockout_qty" class="block text-sm font-medium text-gray-700 mb-1">Quantity to Stock-Out:</label>
                        <input
                            type="number"
                            id="stockout_qty"
                            v-model.number="currentItem.stockout_qty"
                            min="1"
                            :max="currentItem.available_qty"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-amber-500"
                            required
                        />
                        <p v-if="qtyError" class="mt-1 text-sm text-red-600">{{ qtyError }}</p>
                    </div>
                </div>
                <div class="px-6 py-3 bg-gray-50 flex justify-end rounded-b-lg">
                    <button 
                        @click="closeModal" 
                        class="px-4 py-2 bg-gray-100 hover:bg-gray-200 border border-gray-300 rounded-md text-sm mr-2"
                    >
                        Cancel
                    </button>
                    <button 
                        @click="addStockOutItem" 
                        class="px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white rounded-md text-sm"
                        :disabled="!isValidStockOutQty"
                    >
                        Add to Stock-Out
                    </button>
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
const stockOut = reactive({
    warehouse: '',
    date: new Date().toISOString().slice(0, 10), // Today's date in YYYY-MM-DD format
    customer_code: '',
    customer_name: '',
    reference: '',
    remarks: ''
});

const availableStock = ref([]);
const stockOutItems = ref([]);
const searchQuery = ref('');
const showModal = ref(false);
const currentItem = reactive({
    product_code: '',
    description: '',
    batch: '',
    available_qty: 0,
    stockout_qty: 0
});
const qtyError = ref('');
const loading = ref(false);
const error = ref(null);
const isEditingIndex = ref(-1);

// Computed
const filteredAvailableStock = computed(() => {
    if (!searchQuery.value) return availableStock.value;
    
    const query = searchQuery.value.toLowerCase();
    return availableStock.value.filter(item => 
        item.product_code.toLowerCase().includes(query) || 
        item.description.toLowerCase().includes(query) ||
        item.batch.toLowerCase().includes(query)
    );
});

const isValidStockOutQty = computed(() => {
    if (!currentItem.stockout_qty) return false;
    if (currentItem.stockout_qty <= 0) return false;
    if (currentItem.stockout_qty > currentItem.available_qty) return false;
    return true;
});

const canSave = computed(() => {
    return stockOut.warehouse && 
           stockOut.date &&
           stockOutItems.value.length > 0;
});

// Methods
const fetchAvailableStock = async () => {
    if (!stockOut.warehouse) {
        availableStock.value = [];
        return;
    }

    try {
        loading.value = true;
        error.value = null;

        // Demo data for display purposes
        availableStock.value = [
            { 
                product_code: 'FG10001', 
                description: 'Product A - Red', 
                batch: 'LOT-2023-001', 
                available_qty: 120 
            },
            { 
                product_code: 'FG10002', 
                description: 'Product B - Blue', 
                batch: 'LOT-2023-002', 
                available_qty: 85 
            },
            { 
                product_code: 'FG10001', 
                description: 'Product A - Red', 
                batch: 'LOT-2023-003', 
                available_qty: 75 
            },
            { 
                product_code: 'FG10003', 
                description: 'Product C - Green', 
                batch: 'LOT-2023-004', 
                available_qty: 50 
            }
        ];

        loading.value = false;

        /* For actual API implementation:
        const response = await axios.get('/api/inventory/available-stock', {
            params: { warehouse: stockOut.warehouse }
        });
        
        availableStock.value = response.data;
        */
        
    } catch (err) {
        console.error('Error fetching available stock:', err);
        error.value = 'Failed to load inventory data';
        loading.value = false;
    }
};

const openCustomerModal = () => {
    // Demo customer data for simplicity
    stockOut.customer_code = 'CUST001';
    stockOut.customer_name = 'ABC Electronics Inc.';
    
    /* For actual implementation:
    // Open a customer search modal component
    // When customer is selected, update stockOut.customer_code and stockOut.customer_name
    */
};

const openStockOutModal = (item) => {
    Object.assign(currentItem, {
        ...item,
        stockout_qty: 0
    });
    qtyError.value = '';
    showModal.value = true;
};

const closeModal = () => {
    showModal.value = false;
    qtyError.value = '';
};

const addStockOutItem = () => {
    if (!isValidStockOutQty.value) {
        qtyError.value = 'Please enter a valid quantity';
        return;
    }

    if (isEditingIndex.value >= 0) {
        // Update existing item
        stockOutItems.value[isEditingIndex.value] = { 
            product_code: currentItem.product_code,
            description: currentItem.description,
            batch: currentItem.batch,
            stockout_qty: currentItem.stockout_qty
        };
        isEditingIndex.value = -1;
    } else {
        // Add new item
        stockOutItems.value.push({
            product_code: currentItem.product_code,
            description: currentItem.description,
            batch: currentItem.batch,
            stockout_qty: currentItem.stockout_qty
        });
    }

    showModal.value = false;
};

const editStockOutItem = (index) => {
    const item = stockOutItems.value[index];
    
    // Find the matching available stock item to get available_qty
    const availItem = availableStock.value.find(
        ai => ai.product_code === item.product_code && ai.batch === item.batch
    );
    
    Object.assign(currentItem, {
        ...item,
        available_qty: (availItem ? availItem.available_qty : 0) + item.stockout_qty
    });
    
    isEditingIndex.value = index;
    qtyError.value = '';
    showModal.value = true;
};

const removeStockOutItem = (index) => {
    if (confirm('Are you sure you want to remove this item?')) {
        stockOutItems.value.splice(index, 1);
    }
};

const resetForm = () => {
    stockOut.warehouse = '';
    stockOut.customer_code = '';
    stockOut.customer_name = '';
    stockOut.reference = '';
    stockOut.remarks = '';
    stockOut.date = new Date().toISOString().slice(0, 10);
    
    stockOutItems.value = [];
    availableStock.value = [];
    searchQuery.value = '';
};

const saveStockOut = async () => {
    if (!canSave.value) {
        if (stockOutItems.value.length === 0) {
            alert('Please add at least one item to stock-out');
        }
        return;
    }

    try {
        loading.value = true;
        
        const stockOutData = {
            warehouse: stockOut.warehouse,
            date: stockOut.date,
            customer_code: stockOut.customer_code,
            customer_name: stockOut.customer_name,
            reference: stockOut.reference,
            remarks: stockOut.remarks,
            items: stockOutItems.value
        };
        
        // For demo purposes
        console.log('Stock-out data to save:', stockOutData);
        alert('Stock-out processed successfully!');
        resetForm();
        
        /* For actual API implementation:
        const response = await axios.post('/api/fg-stock-out/batch', stockOutData);
        
        if (response.data.success) {
            alert('Stock-out processed successfully!');
            resetForm();
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