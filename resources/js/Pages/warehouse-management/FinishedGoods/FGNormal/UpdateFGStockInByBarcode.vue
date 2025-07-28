<template>
    <AppLayout :header="'Update FG Stock-In by Barcode'">
        <!-- Header Section -->
        <div class="bg-gradient-to-r from-amber-600 via-orange-600 to-red-600 p-6 rounded-t-lg shadow-lg overflow-hidden relative">
            <!-- Decorative Elements -->
            <div class="absolute top-0 right-0 w-40 h-40 bg-white opacity-5 rounded-full -translate-y-20 translate-x-20"></div>
            <div class="absolute bottom-0 left-0 w-20 h-20 bg-white opacity-5 rounded-full translate-y-10 -translate-x-10"></div>
            
            <div class="flex items-center">
                <div class="bg-gradient-to-br from-amber-500 to-orange-600 p-3 rounded-lg shadow-inner flex items-center justify-center relative overflow-hidden mr-4">
                    <div class="absolute -top-1 -right-1 w-6 h-6 bg-yellow-300 opacity-30 rounded-full animate-ping-slow"></div>
                    <div class="absolute -bottom-1 -left-1 w-4 h-4 bg-orange-300 opacity-30 rounded-full animate-ping-slow animation-delay-500"></div>
                    <i class="fas fa-barcode text-white text-2xl z-10"></i>
                </div>
                <div>
                    <h2 class="text-2xl md:text-3xl font-bold text-white mb-1 text-shadow">Update FG Stock-In by Barcode</h2>
                    <p class="text-amber-100 max-w-2xl">Process finished goods stock-in using barcode scanning</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-b-lg shadow-lg p-6 mb-6">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left Column - Scan Form -->
                <div class="lg:col-span-1">
                    <div class="bg-white p-6 rounded-lg shadow-md border-t-4 border-amber-500 mb-6">
                        <div class="flex items-center mb-6 pb-2 border-b border-gray-200">
                            <div class="p-2 bg-gradient-to-r from-amber-500 to-orange-600 rounded-lg mr-3 shadow-md">
                                <i class="fas fa-qrcode text-white"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-800">Scan Barcode</h3>
                        </div>

                        <form @submit.prevent="processBarcode" class="space-y-4">
                            <div>
                                <label for="barcode" class="block text-sm font-medium text-gray-700 mb-1">Barcode:</label>
                                <div class="flex">
                                    <input
                                        type="text"
                                        id="barcode"
                                        ref="barcodeInput"
                                        v-model="barcode"
                                        placeholder="Scan or enter barcode"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-l-md focus:outline-none focus:ring-2 focus:ring-amber-500"
                                        autofocus
                                    />
                                    <button
                                        type="submit"
                                        class="px-3 py-2 bg-amber-600 text-white rounded-r-md hover:bg-amber-700"
                                    >
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                                <p class="mt-2 text-sm text-gray-600">Press Enter or click Search after scanning</p>
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

                <!-- Right Column - Product Info and Scan Items -->
                <div class="lg:col-span-2">
                    <!-- Product Information -->
                    <div v-if="currentProduct.product_code" class="bg-white p-6 rounded-lg shadow-md border-t-4 border-indigo-500 mb-6">
                        <div class="flex items-center justify-between mb-6 pb-2 border-b border-gray-200">
                            <div class="flex items-center">
                                <div class="p-2 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-lg mr-3 shadow-md">
                                    <i class="fas fa-box text-white"></i>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-800">Product Information</h3>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Product Code:</label>
                                    <div class="px-3 py-2 border border-gray-300 rounded-md bg-gray-50 font-medium">
                                        {{ currentProduct.product_code }}
                                    </div>
                                </div>
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Description:</label>
                                    <div class="px-3 py-2 border border-gray-300 rounded-md bg-gray-50">
                                        {{ currentProduct.description }}
                                    </div>
                                </div>
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Category:</label>
                                    <div class="px-3 py-2 border border-gray-300 rounded-md bg-gray-50">
                                        {{ currentProduct.category }}
                                    </div>
                                </div>
                            </div>
                            <div>
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">UoM:</label>
                                    <div class="px-3 py-2 border border-gray-300 rounded-md bg-gray-50">
                                        {{ currentProduct.uom }}
                                    </div>
                                </div>
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Quantity to Add:</label>
                                    <input
                                        type="number"
                                        v-model.number="currentProduct.quantity"
                                        min="1"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                    />
                                </div>
                                <div class="flex justify-end mt-6">
                                    <button
                                        type="button"
                                        @click="addToScanList"
                                        class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-md text-sm shadow-md"
                                    >
                                        <i class="fas fa-plus mr-1"></i> Add to List
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- No product scanned placeholder -->
                    <div v-else class="bg-white p-6 rounded-lg shadow-md border-t-4 border-gray-400 mb-6">
                        <div class="flex flex-col items-center py-8">
                            <i class="fas fa-barcode text-gray-300 text-6xl mb-4"></i>
                            <p class="text-gray-600 font-medium">No product scanned</p>
                            <p class="text-gray-400 mt-1">Scan a barcode to view product information</p>
                        </div>
                    </div>

                    <!-- Scanned Items List -->
                    <div class="bg-white p-6 rounded-lg shadow-md border-t-4 border-green-500">
                        <div class="flex items-center justify-between mb-6 pb-2 border-b border-gray-200">
                            <div class="flex items-center">
                                <div class="p-2 bg-gradient-to-r from-green-500 to-emerald-600 rounded-lg mr-3 shadow-md">
                                    <i class="fas fa-list-ul text-white"></i>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-800">Scanned Items</h3>
                            </div>
                            <div class="text-sm">
                                Total Items: <span class="font-medium">{{ scannedItems.length }}</span>
                            </div>
                        </div>

                        <!-- Scanned Items Table -->
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Product
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Description
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Quantity
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            UoM
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Actions
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr v-for="(item, index) in scannedItems" :key="index" class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ item.product_code }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-700">
                                            {{ item.description }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-green-600">
                                            {{ item.quantity.toLocaleString() }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                            {{ item.uom }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <button
                                                @click="editScannedItem(index)"
                                                class="text-indigo-600 hover:text-indigo-900 mr-3"
                                            >
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button
                                                @click="removeScannedItem(index)"
                                                class="text-red-600 hover:text-red-900"
                                            >
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr v-if="scannedItems.length === 0">
                                        <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">
                                            <div class="flex flex-col items-center py-6">
                                                <i class="fas fa-list-alt text-gray-300 text-5xl mb-4"></i>
                                                <p class="text-gray-600 font-medium">No items added yet</p>
                                                <p class="text-gray-400 mt-1">Scan products to add them to the list</p>
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
import { ref, reactive, computed, onMounted, nextTick } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import axios from 'axios';

// State
const barcode = ref('');
const barcodeInput = ref(null);
const scannedItems = ref([]);
const editingIndex = ref(-1);

const currentProduct = reactive({
    product_code: '',
    description: '',
    category: '',
    uom: '',
    quantity: 1
});

const stockIn = reactive({
    warehouse: '',
    batch_number: '',
    date: new Date().toISOString().slice(0, 10), // Today's date in YYYY-MM-DD format
    reference: '',
    remarks: ''
});

const loading = ref(false);
const error = ref(null);

// Computed
const canSave = computed(() => {
    return stockIn.warehouse && 
           stockIn.date &&
           stockIn.batch_number &&
           scannedItems.value.length > 0;
});

// Methods
const processBarcode = async () => {
    if (!barcode.value) return;

    try {
        loading.value = true;
        error.value = null;

        // Demo data for display purposes
        const demoProducts = {
            'BC12345': {
                product_code: 'FG10001',
                description: 'Product A - Red',
                category: 'Finished Goods',
                uom: 'PCS'
            },
            'BC67890': {
                product_code: 'FG10002',
                description: 'Product B - Blue',
                category: 'Finished Goods',
                uom: 'KG'
            }
        };

        const product = demoProducts[barcode.value] || {
            product_code: barcode.value,
            description: `Product for ${barcode.value}`,
            category: 'Finished Goods',
            uom: 'PCS'
        };

        // Set current product
        currentProduct.product_code = product.product_code;
        currentProduct.description = product.description;
        currentProduct.category = product.category;
        currentProduct.uom = product.uom;
        currentProduct.quantity = 1;

        loading.value = false;

        /* For actual API implementation:
        const response = await axios.get(`/api/products/barcode/${barcode.value}`);
        
        if (response.data) {
            currentProduct.product_code = response.data.product_code;
            currentProduct.description = response.data.description;
            currentProduct.category = response.data.category;
            currentProduct.uom = response.data.uom;
            currentProduct.quantity = 1;
        } else {
            error.value = 'Barcode not found';
        }
        */
        
        barcode.value = '';
        // Focus back on barcode input for next scan
        nextTick(() => {
            barcodeInput.value.focus();
        });
        
    } catch (err) {
        console.error('Error processing barcode:', err);
        error.value = 'Failed to process barcode';
        loading.value = false;
    }
};

const addToScanList = () => {
    if (!currentProduct.product_code || currentProduct.quantity <= 0) return;

    if (editingIndex.value >= 0) {
        // Update existing item
        scannedItems.value[editingIndex.value] = { ...currentProduct };
        editingIndex.value = -1;
    } else {
        // Check if product already in list
        const existingIndex = scannedItems.value.findIndex(
            item => item.product_code === currentProduct.product_code
        );

        if (existingIndex >= 0) {
            // Add to existing quantity
            scannedItems.value[existingIndex].quantity += currentProduct.quantity;
        } else {
            // Add as new item
            scannedItems.value.push({ ...currentProduct });
        }
    }

    // Reset current product
    currentProduct.product_code = '';
    currentProduct.description = '';
    currentProduct.category = '';
    currentProduct.uom = '';
    currentProduct.quantity = 1;

    // Focus back on barcode input
    nextTick(() => {
        barcodeInput.value.focus();
    });
};

const editScannedItem = (index) => {
    const item = scannedItems.value[index];
    
    currentProduct.product_code = item.product_code;
    currentProduct.description = item.description;
    currentProduct.category = item.category;
    currentProduct.uom = item.uom;
    currentProduct.quantity = item.quantity;
    
    editingIndex.value = index;
};

const removeScannedItem = (index) => {
    if (confirm('Are you sure you want to remove this item?')) {
        scannedItems.value.splice(index, 1);
    }
};

const resetForm = () => {
    stockIn.warehouse = '';
    stockIn.batch_number = '';
    stockIn.reference = '';
    stockIn.remarks = '';
    stockIn.date = new Date().toISOString().slice(0, 10);
    
    currentProduct.product_code = '';
    currentProduct.description = '';
    currentProduct.category = '';
    currentProduct.uom = '';
    currentProduct.quantity = 1;
    
    scannedItems.value = [];
    editingIndex.value = -1;
    barcode.value = '';
    
    nextTick(() => {
        barcodeInput.value.focus();
    });
};

const saveStockIn = async () => {
    try {
        loading.value = true;
        
        const stockInData = {
            warehouse: stockIn.warehouse,
            batch_number: stockIn.batch_number,
            date: stockIn.date,
            reference: stockIn.reference,
            remarks: stockIn.remarks,
            items: scannedItems.value.map(item => ({
                product_code: item.product_code,
                quantity: item.quantity
            }))
        };
        
        // For demo purposes
        console.log('Stock-in data to save:', stockInData);
        alert('Stock-in processed successfully!');
        resetForm();
        
        /* For actual API implementation:
        const response = await axios.post('/api/fg-stock-in/barcode', stockInData);
        
        if (response.data.success) {
            alert('Stock-in processed successfully!');
            resetForm();
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

// Keyboard shortcut for barcode scanning
const handleKeyDown = (event) => {
    if (event.key === 'Enter' && document.activeElement === barcodeInput.value) {
        processBarcode();
    }
};

// Lifecycle hooks
onMounted(() => {
    document.addEventListener('keydown', handleKeyDown);
    nextTick(() => {
        barcodeInput.value.focus();
    });
});
</script>

<style scoped>
.text-shadow {
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}
</style> 