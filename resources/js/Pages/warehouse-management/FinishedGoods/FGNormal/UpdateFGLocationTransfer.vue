<template>
    <AppLayout :header="'Update FG Location Transfer'">
        <!-- Header Section -->
        <div class="bg-gradient-to-r from-cyan-600 via-blue-600 to-indigo-600 p-6 rounded-t-lg shadow-lg overflow-hidden relative">
            <!-- Decorative Elements -->
            <div class="absolute top-0 right-0 w-40 h-40 bg-white opacity-5 rounded-full -translate-y-20 translate-x-20"></div>
            <div class="absolute bottom-0 left-0 w-20 h-20 bg-white opacity-5 rounded-full translate-y-10 -translate-x-10"></div>
            
            <div class="flex items-center">
                <div class="bg-gradient-to-br from-cyan-500 to-blue-600 p-3 rounded-lg shadow-inner flex items-center justify-center relative overflow-hidden mr-4">
                    <div class="absolute -top-1 -right-1 w-6 h-6 bg-yellow-300 opacity-30 rounded-full animate-ping-slow"></div>
                    <div class="absolute -bottom-1 -left-1 w-4 h-4 bg-blue-300 opacity-30 rounded-full animate-ping-slow animation-delay-500"></div>
                    <i class="fas fa-exchange-alt text-white text-2xl z-10"></i>
                </div>
                <div>
                    <h2 class="text-2xl md:text-3xl font-bold text-white mb-1 text-shadow">Update FG Location Transfer</h2>
                    <p class="text-blue-100 max-w-2xl">Transfer finished goods between warehouse locations</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-b-lg shadow-lg p-6 mb-6">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left Column - Transfer Form -->
                <div class="lg:col-span-1">
                    <div class="bg-white p-6 rounded-lg shadow-md border-t-4 border-blue-500">
                        <div class="flex items-center mb-6 pb-2 border-b border-gray-200">
                            <div class="p-2 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-lg mr-3 shadow-md">
                                <i class="fas fa-map-marker-alt text-white"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-800">Transfer Details</h3>
                        </div>

                        <form @submit.prevent="saveTransfer" class="space-y-4">
                            <div>
                                <label for="from_warehouse" class="block text-sm font-medium text-gray-700 mb-1">From Warehouse:</label>
                                <select
                                    id="from_warehouse"
                                    v-model="transfer.from_warehouse"
                                    @change="fetchSourceInventory"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    required
                                >
                                    <option value="">Select Source Warehouse</option>
                                    <option value="FG01">FG01 - Main Finished Goods</option>
                                    <option value="FG02">FG02 - Secondary Finished Goods</option>
                                    <option value="FG03">FG03 - Transit Warehouse</option>
                                </select>
                            </div>

                            <div>
                                <label for="to_warehouse" class="block text-sm font-medium text-gray-700 mb-1">To Warehouse:</label>
                                <select
                                    id="to_warehouse"
                                    v-model="transfer.to_warehouse"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    required
                                >
                                    <option value="">Select Destination Warehouse</option>
                                    <option value="FG01">FG01 - Main Finished Goods</option>
                                    <option value="FG02">FG02 - Secondary Finished Goods</option>
                                    <option value="FG03">FG03 - Transit Warehouse</option>
                                </select>
                            </div>

                            <div>
                                <label for="transfer_date" class="block text-sm font-medium text-gray-700 mb-1">Transfer Date:</label>
                                <input
                                    type="date"
                                    id="transfer_date"
                                    v-model="transfer.date"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    required
                                />
                            </div>

                            <div>
                                <label for="reference" class="block text-sm font-medium text-gray-700 mb-1">Reference Number:</label>
                                <input
                                    type="text"
                                    id="reference"
                                    v-model="transfer.reference"
                                    placeholder="Enter reference number"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                />
                            </div>

                            <div>
                                <label for="remarks" class="block text-sm font-medium text-gray-700 mb-1">Remarks:</label>
                                <textarea
                                    id="remarks"
                                    v-model="transfer.remarks"
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
                                    Process Transfer
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Right Column - Source Inventory and Transfer Items -->
                <div class="lg:col-span-2">
                    <!-- Source Inventory -->
                    <div class="bg-white p-6 rounded-lg shadow-md border-t-4 border-cyan-500 mb-6">
                        <div class="flex items-center justify-between mb-6 pb-2 border-b border-gray-200">
                            <div class="flex items-center">
                                <div class="p-2 bg-gradient-to-r from-cyan-500 to-blue-500 rounded-lg mr-3 shadow-md">
                                    <i class="fas fa-warehouse text-white"></i>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-800">Source Inventory</h3>
                            </div>
                            <div class="flex items-center">
                                <div class="relative">
                                    <input
                                        type="text"
                                        v-model="searchQuery"
                                        placeholder="Search products..."
                                        class="w-64 px-3 py-1.5 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    />
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                        <i class="fas fa-search text-gray-400"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Source Inventory Table -->
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
                                    <tr v-for="(item, index) in filteredSourceInventory" :key="index" class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ item.product_code }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                            {{ item.description }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                            {{ item.batch }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-blue-600">
                                            {{ item.available_qty.toLocaleString() }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <button 
                                                @click="openTransferModal(item)"
                                                class="bg-blue-100 text-blue-700 hover:bg-blue-200 px-3 py-1 rounded-md text-sm font-medium"
                                            >
                                                Transfer
                                            </button>
                                        </td>
                                    </tr>
                                    <tr v-if="sourceInventory.length === 0">
                                        <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">
                                            <div class="flex flex-col items-center py-6">
                                                <i class="fas fa-boxes text-gray-300 text-5xl mb-4"></i>
                                                <p class="text-gray-600 font-medium">No inventory found</p>
                                                <p class="text-gray-400 mt-1">Select a source warehouse to view inventory</p>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr v-if="sourceInventory.length > 0 && filteredSourceInventory.length === 0">
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

                    <!-- Transfer Items -->
                    <div class="bg-white p-6 rounded-lg shadow-md border-t-4 border-indigo-500">
                        <div class="flex items-center justify-between mb-6 pb-2 border-b border-gray-200">
                            <div class="flex items-center">
                                <div class="p-2 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-lg mr-3 shadow-md">
                                    <i class="fas fa-dolly text-white"></i>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-800">Transfer Items</h3>
                            </div>
                            <div class="text-sm">
                                Total Items: <span class="font-medium">{{ transferItems.length }}</span>
                            </div>
                        </div>

                        <!-- Transfer Items Table -->
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
                                            Transfer Qty
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Actions
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr v-for="(item, index) in transferItems" :key="index" class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ item.product_code }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                            {{ item.description }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                            {{ item.batch }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-indigo-600">
                                            {{ item.transfer_qty.toLocaleString() }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <button 
                                                @click="editTransferItem(index)" 
                                                class="text-indigo-600 hover:text-indigo-900 mr-3"
                                            >
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button 
                                                @click="removeTransferItem(index)"
                                                class="text-red-600 hover:text-red-900"
                                            >
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr v-if="transferItems.length === 0">
                                        <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">
                                            <div class="flex flex-col items-center py-6">
                                                <i class="fas fa-exchange-alt text-gray-300 text-5xl mb-4"></i>
                                                <p class="text-gray-600 font-medium">No transfer items added</p>
                                                <p class="text-gray-400 mt-1">Click 'Transfer' on source items to add them</p>
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

        <!-- Transfer Modal -->
        <div v-if="showModal" class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
                <div class="px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900">Transfer Item</h3>
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
                        <label for="transfer_qty" class="block text-sm font-medium text-gray-700 mb-1">Quantity to Transfer:</label>
                        <input
                            type="number"
                            id="transfer_qty"
                            v-model.number="currentItem.transfer_qty"
                            min="1"
                            :max="currentItem.available_qty"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
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
                        @click="addTransferItem" 
                        class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md text-sm"
                        :disabled="!isValidTransferQty"
                    >
                        Add to Transfer
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
const transfer = reactive({
    from_warehouse: '',
    to_warehouse: '',
    date: new Date().toISOString().slice(0, 10), // Today's date in YYYY-MM-DD format
    reference: '',
    remarks: ''
});

const sourceInventory = ref([]);
const transferItems = ref([]);
const searchQuery = ref('');
const showModal = ref(false);
const currentItem = reactive({
    product_code: '',
    description: '',
    batch: '',
    available_qty: 0,
    transfer_qty: 0
});
const qtyError = ref('');
const loading = ref(false);
const error = ref(null);
const isEditingIndex = ref(-1);

// Computed
const filteredSourceInventory = computed(() => {
    if (!searchQuery.value) return sourceInventory.value;
    
    const query = searchQuery.value.toLowerCase();
    return sourceInventory.value.filter(item => 
        item.product_code.toLowerCase().includes(query) || 
        item.description.toLowerCase().includes(query) ||
        item.batch.toLowerCase().includes(query)
    );
});

const isValidTransferQty = computed(() => {
    if (!currentItem.transfer_qty) return false;
    if (currentItem.transfer_qty <= 0) return false;
    if (currentItem.transfer_qty > currentItem.available_qty) return false;
    return true;
});

const canSave = computed(() => {
    return transfer.from_warehouse && 
           transfer.to_warehouse && 
           transfer.date &&
           transfer.from_warehouse !== transfer.to_warehouse &&
           transferItems.value.length > 0;
});

// Methods
const fetchSourceInventory = async () => {
    if (!transfer.from_warehouse) {
        sourceInventory.value = [];
        return;
    }

    try {
        loading.value = true;
        error.value = null;

        // Demo data for display purposes
        sourceInventory.value = [
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
            params: { warehouse: transfer.from_warehouse }
        });
        
        sourceInventory.value = response.data;
        */
        
    } catch (err) {
        console.error('Error fetching source inventory:', err);
        error.value = 'Failed to load inventory data';
        loading.value = false;
    }
};

const openTransferModal = (item) => {
    Object.assign(currentItem, {
        ...item,
        transfer_qty: 0
    });
    qtyError.value = '';
    showModal.value = true;
};

const closeModal = () => {
    showModal.value = false;
    qtyError.value = '';
};

const addTransferItem = () => {
    if (!isValidTransferQty.value) {
        qtyError.value = 'Please enter a valid quantity';
        return;
    }

    if (isEditingIndex.value >= 0) {
        // Update existing item
        transferItems.value[isEditingIndex.value] = { 
            product_code: currentItem.product_code,
            description: currentItem.description,
            batch: currentItem.batch,
            transfer_qty: currentItem.transfer_qty
        };
        isEditingIndex.value = -1;
    } else {
        // Add new item
        transferItems.value.push({
            product_code: currentItem.product_code,
            description: currentItem.description,
            batch: currentItem.batch,
            transfer_qty: currentItem.transfer_qty
        });
    }

    showModal.value = false;
};

const editTransferItem = (index) => {
    const item = transferItems.value[index];
    
    // Find the matching source item to get available_qty
    const sourceItem = sourceInventory.value.find(
        si => si.product_code === item.product_code && si.batch === item.batch
    );
    
    Object.assign(currentItem, {
        ...item,
        available_qty: sourceItem ? sourceItem.available_qty : 0
    });
    
    isEditingIndex.value = index;
    qtyError.value = '';
    showModal.value = true;
};

const removeTransferItem = (index) => {
    if (confirm('Are you sure you want to remove this item?')) {
        transferItems.value.splice(index, 1);
    }
};

const resetForm = () => {
    transfer.from_warehouse = '';
    transfer.to_warehouse = '';
    transfer.reference = '';
    transfer.remarks = '';
    transfer.date = new Date().toISOString().slice(0, 10);
    
    transferItems.value = [];
    sourceInventory.value = [];
    searchQuery.value = '';
};

const saveTransfer = async () => {
    if (!canSave.value) {
        if (transfer.from_warehouse === transfer.to_warehouse) {
            alert('Source and destination warehouses cannot be the same');
        } else if (transferItems.value.length === 0) {
            alert('Please add at least one item to transfer');
        }
        return;
    }

    try {
        loading.value = true;
        
        const transferData = {
            from_warehouse: transfer.from_warehouse,
            to_warehouse: transfer.to_warehouse,
            date: transfer.date,
            reference: transfer.reference,
            remarks: transfer.remarks,
            items: transferItems.value
        };
        
        // For demo purposes
        console.log('Transfer data to save:', transferData);
        alert('Location transfer processed successfully!');
        resetForm();
        
        /* For actual API implementation:
        const response = await axios.post('/api/fg-location-transfer', transferData);
        
        if (response.data.success) {
            alert('Location transfer processed successfully!');
            resetForm();
        }
        */
        
        loading.value = false;
    } catch (err) {
        console.error('Error processing location transfer:', err);
        error.value = 'Failed to process location transfer';
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