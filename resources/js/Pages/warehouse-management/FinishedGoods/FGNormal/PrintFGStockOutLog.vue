<template>
    <AppLayout :header="'Print FG Stock-Out Log'">
        <!-- Header Section -->
        <div class="bg-gradient-to-r from-red-600 via-rose-600 to-pink-600 p-6 rounded-t-lg shadow-lg overflow-hidden relative">
            <!-- Decorative Elements -->
            <div class="absolute top-0 right-0 w-40 h-40 bg-white opacity-5 rounded-full -translate-y-20 translate-x-20"></div>
            <div class="absolute bottom-0 left-0 w-20 h-20 bg-white opacity-5 rounded-full translate-y-10 -translate-x-10"></div>
            
            <div class="flex items-center">
                <div class="bg-gradient-to-br from-red-500 to-rose-600 p-3 rounded-lg shadow-inner flex items-center justify-center relative overflow-hidden mr-4">
                    <div class="absolute -top-1 -right-1 w-6 h-6 bg-yellow-300 opacity-30 rounded-full animate-ping-slow"></div>
                    <div class="absolute -bottom-1 -left-1 w-4 h-4 bg-blue-300 opacity-30 rounded-full animate-ping-slow animation-delay-500"></div>
                    <i class="fas fa-file-export text-white text-2xl z-10"></i>
                </div>
                <div>
                    <h2 class="text-2xl md:text-3xl font-bold text-white mb-1 text-shadow">Print FG Stock-Out Log</h2>
                    <p class="text-red-100 max-w-2xl">View and print finished goods stock-out transaction logs</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-b-lg shadow-lg p-6 mb-6">
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
                <!-- Left Column - Search Filters -->
                <div class="lg:col-span-1">
                    <div class="bg-white p-6 rounded-lg shadow-md border-t-4 border-red-500 mb-6">
                        <div class="flex items-center mb-6 pb-2 border-b border-gray-200">
                            <div class="p-2 bg-gradient-to-r from-red-500 to-rose-600 rounded-lg mr-3 shadow-md">
                                <i class="fas fa-search text-white"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-800">Search Filters</h3>
                        </div>

                        <form @submit.prevent="searchTransactions" class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Date Range:</label>
                                <div class="grid grid-cols-2 gap-2">
                                    <div>
                                        <label for="start_date" class="block text-xs text-gray-500 mb-1">From:</label>
                                        <input
                                            type="date"
                                            id="start_date"
                                            v-model="filters.start_date"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500"
                                        />
                                    </div>
                                    <div>
                                        <label for="end_date" class="block text-xs text-gray-500 mb-1">To:</label>
                                        <input
                                            type="date"
                                            id="end_date"
                                            v-model="filters.end_date"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500"
                                        />
                                    </div>
                                </div>
                            </div>

                            <div>
                                <label for="warehouse" class="block text-sm font-medium text-gray-700 mb-1">Warehouse:</label>
                                <select
                                    id="warehouse"
                                    v-model="filters.warehouse"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500"
                                >
                                    <option value="">All Warehouses</option>
                                    <option value="FG01">FG01 - Main Finished Goods</option>
                                    <option value="FG02">FG02 - Secondary Finished Goods</option>
                                    <option value="FG03">FG03 - Transit Warehouse</option>
                                </select>
                            </div>

                            <div>
                                <label for="product_code" class="block text-sm font-medium text-gray-700 mb-1">Product Code:</label>
                                <input
                                    type="text"
                                    id="product_code"
                                    v-model="filters.product_code"
                                    placeholder="Enter product code"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500"
                                />
                            </div>

                            <div>
                                <label for="customer" class="block text-sm font-medium text-gray-700 mb-1">Customer:</label>
                                <div class="flex">
                                    <input
                                        type="text"
                                        id="customer"
                                        v-model="filters.customer_code"
                                        placeholder="Customer code"
                                        class="w-32 px-3 py-2 border border-gray-300 rounded-l-md focus:outline-none focus:ring-2 focus:ring-red-500"
                                    />
                                    <button
                                        type="button"
                                        @click="openCustomerModal"
                                        class="px-3 py-2 bg-gray-100 border border-l-0 border-gray-300 rounded-r-md hover:bg-gray-200"
                                    >
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>

                            <div>
                                <label for="reference" class="block text-sm font-medium text-gray-700 mb-1">Reference/DO Number:</label>
                                <input
                                    type="text"
                                    id="reference"
                                    v-model="filters.reference"
                                    placeholder="Enter reference or DO number"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500"
                                />
                            </div>

                            <div>
                                <label for="batch" class="block text-sm font-medium text-gray-700 mb-1">Batch/Lot Number:</label>
                                <input
                                    type="text"
                                    id="batch"
                                    v-model="filters.batch"
                                    placeholder="Enter batch or lot number"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500"
                                />
                            </div>

                            <div class="flex justify-end pt-4">
                                <button
                                    type="button"
                                    @click="resetFilters"
                                    class="px-4 py-2 bg-gray-100 hover:bg-gray-200 border border-gray-300 rounded-md text-sm mr-2"
                                >
                                    Reset
                                </button>
                                <button
                                    type="submit"
                                    class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-md text-sm shadow-md"
                                >
                                    Search
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Print Controls -->
                    <div class="bg-white p-6 rounded-lg shadow-md border-t-4 border-rose-500">
                        <div class="flex items-center mb-6 pb-2 border-b border-gray-200">
                            <div class="p-2 bg-gradient-to-r from-rose-500 to-red-600 rounded-lg mr-3 shadow-md">
                                <i class="fas fa-print text-white"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-800">Print Options</h3>
                        </div>

                        <div class="space-y-4">
                            <button
                                @click="printReport"
                                class="w-full flex items-center justify-center px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-md shadow-md"
                                :disabled="transactions.length === 0"
                            >
                                <i class="fas fa-print mr-2"></i>
                                Print Report
                            </button>

                            <button
                                @click="exportToExcel"
                                class="w-full flex items-center justify-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-md shadow-md"
                                :disabled="transactions.length === 0"
                            >
                                <i class="fas fa-file-excel mr-2"></i>
                                Export to Excel
                            </button>

                            <button
                                @click="exportToPDF"
                                class="w-full flex items-center justify-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-md shadow-md"
                                :disabled="transactions.length === 0"
                            >
                                <i class="fas fa-file-pdf mr-2"></i>
                                Export to PDF
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Right Column - Transaction Log -->
                <div class="lg:col-span-3">
                    <div class="bg-white p-6 rounded-lg shadow-md border-t-4 border-rose-500 mb-6">
                        <div class="flex items-center justify-between mb-6 pb-2 border-b border-gray-200">
                            <div class="flex items-center">
                                <div class="p-2 bg-gradient-to-r from-rose-500 to-red-600 rounded-lg mr-3 shadow-md">
                                    <i class="fas fa-clipboard-list text-white"></i>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-800">Stock-Out Transaction Log</h3>
                            </div>
                            <div class="text-sm">
                                Total Records: <span class="font-medium">{{ transactions.length }}</span>
                            </div>
                        </div>

                        <!-- Print View -->
                        <div id="printable-area" class="print-section">
                            <div class="print-header mb-6">
                                <h2 class="text-xl font-bold text-center">Finished Goods Stock-Out Log</h2>
                                <div class="text-sm text-center text-gray-600 mt-2">
                                    <p v-if="filters.start_date && filters.end_date">
                                        Period: {{ formatDate(filters.start_date) }} to {{ formatDate(filters.end_date) }}
                                    </p>
                                    <p v-if="filters.warehouse">Warehouse: {{ filters.warehouse }}</p>
                                </div>
                            </div>

                            <!-- Transactions Table -->
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Date
                                            </th>
                                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                DO/Ref
                                            </th>
                                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Product
                                            </th>
                                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Description
                                            </th>
                                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Batch/Lot
                                            </th>
                                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Customer
                                            </th>
                                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Warehouse
                                            </th>
                                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Quantity
                                            </th>
                                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hide-on-print">
                                                Actions
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        <tr v-for="(item, index) in transactions" :key="index" class="hover:bg-gray-50">
                                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-700">
                                                {{ formatDate(item.date) }}
                                            </td>
                                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-700">
                                                {{ item.reference }}
                                            </td>
                                            <td class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                {{ item.product_code }}
                                            </td>
                                            <td class="px-4 py-4 text-sm text-gray-700">
                                                {{ item.description }}
                                            </td>
                                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-700">
                                                {{ item.batch }}
                                            </td>
                                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-700">
                                                {{ item.customer_name }}
                                            </td>
                                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-700">
                                                {{ item.warehouse }}
                                            </td>
                                            <td class="px-4 py-4 whitespace-nowrap text-sm font-medium text-red-600">
                                                {{ item.quantity.toLocaleString() }}
                                            </td>
                                            <td class="px-4 py-4 whitespace-nowrap text-right text-sm font-medium hide-on-print">
                                                <button 
                                                    @click="viewTransactionDetails(item)"
                                                    class="text-indigo-600 hover:text-indigo-900"
                                                >
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        <tr v-if="transactions.length === 0">
                                            <td colspan="9" class="px-6 py-4 text-center text-sm text-gray-500">
                                                <div class="flex flex-col items-center py-6">
                                                    <i class="fas fa-search text-gray-300 text-5xl mb-4"></i>
                                                    <p class="text-gray-600 font-medium">No transactions found</p>
                                                    <p class="text-gray-400 mt-1">Try adjusting your search filters</p>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="print-footer mt-6 text-sm text-gray-600">
                                <p>Generated on {{ getCurrentDate() }}</p>
                            </div>
                        </div>

                        <!-- Pagination -->
                        <div class="flex items-center justify-between mt-6 px-4 hide-on-print">
                            <div class="text-sm text-gray-700">
                                Showing <span class="font-medium">{{ pagination.from }}</span> to <span class="font-medium">{{ pagination.to }}</span> of <span class="font-medium">{{ pagination.total }}</span> results
                            </div>
                            <div>
                                <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                                    <button
                                        @click="changePage(pagination.current_page - 1)"
                                        :disabled="pagination.current_page === 1"
                                        :class="pagination.current_page === 1 ? 'opacity-50 cursor-not-allowed' : ''"
                                        class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50"
                                    >
                                        <span class="sr-only">Previous</span>
                                        <i class="fas fa-chevron-left"></i>
                                    </button>
                                    <button
                                        v-for="page in pagination.last_page"
                                        :key="page"
                                        @click="changePage(page)"
                                        :class="page === pagination.current_page ? 'z-10 bg-red-50 border-red-500 text-red-600' : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50'"
                                        class="relative inline-flex items-center px-4 py-2 border text-sm font-medium"
                                    >
                                        {{ page }}
                                    </button>
                                    <button
                                        @click="changePage(pagination.current_page + 1)"
                                        :disabled="pagination.current_page === pagination.last_page"
                                        :class="pagination.current_page === pagination.last_page ? 'opacity-50 cursor-not-allowed' : ''"
                                        class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50"
                                    >
                                        <span class="sr-only">Next</span>
                                        <i class="fas fa-chevron-right"></i>
                                    </button>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import axios from 'axios';

// State
const filters = reactive({
    start_date: '',
    end_date: '',
    warehouse: '',
    product_code: '',
    customer_code: '',
    reference: '',
    batch: ''
});

const transactions = ref([]);
const loading = ref(false);
const error = ref(null);

// Pagination
const pagination = reactive({
    current_page: 1,
    last_page: 1,
    from: 0,
    to: 0,
    total: 0,
    per_page: 10
});

// Methods
const openCustomerModal = () => {
    // Demo customer data for simplicity
    filters.customer_code = 'CUST001';
    
    /* For actual implementation:
    // Open a customer search modal component
    // When customer is selected, update filters.customer_code
    */
};

const searchTransactions = async () => {
    try {
        loading.value = true;
        error.value = null;

        // Set default dates if not provided
        if (!filters.start_date) {
            const thirtyDaysAgo = new Date();
            thirtyDaysAgo.setDate(thirtyDaysAgo.getDate() - 30);
            filters.start_date = thirtyDaysAgo.toISOString().slice(0, 10);
        }
        
        if (!filters.end_date) {
            filters.end_date = new Date().toISOString().slice(0, 10);
        }

        // Demo data for display purposes
        transactions.value = [
            { 
                date: '2023-05-10', 
                reference: 'DO-2023-0001',
                product_code: 'FG10001', 
                description: 'Product A - Red',
                batch: 'LOT-2023-001',
                customer_name: 'ABC Electronics Inc.',
                warehouse: 'FG01',
                quantity: 30 
            },
            { 
                date: '2023-05-12', 
                reference: 'DO-2023-0002',
                product_code: 'FG10002', 
                description: 'Product B - Blue',
                batch: 'LOT-2023-002',
                customer_name: 'XYZ Manufacturing Ltd.',
                warehouse: 'FG01',
                quantity: 50 
            },
            { 
                date: '2023-05-15', 
                reference: 'DO-2023-0003',
                product_code: 'FG10001', 
                description: 'Product A - Red',
                batch: 'LOT-2023-003',
                customer_name: 'ABC Electronics Inc.',
                warehouse: 'FG02',
                quantity: 25
            },
        ];

        // Update pagination
        pagination.current_page = 1;
        pagination.last_page = 1;
        pagination.from = 1;
        pagination.to = transactions.value.length;
        pagination.total = transactions.value.length;

        loading.value = false;

        /* For actual API implementation:
        const response = await axios.get('/api/reports/fg-stock-out-log', {
            params: {
                start_date: filters.start_date,
                end_date: filters.end_date,
                warehouse: filters.warehouse,
                product_code: filters.product_code,
                customer_code: filters.customer_code,
                reference: filters.reference,
                batch: filters.batch,
                page: pagination.current_page
            }
        });
        
        transactions.value = response.data.data;
        pagination.current_page = response.data.current_page;
        pagination.last_page = response.data.last_page;
        pagination.from = response.data.from;
        pagination.to = response.data.to;
        pagination.total = response.data.total;
        */
        
    } catch (err) {
        console.error('Error fetching transactions:', err);
        error.value = 'Failed to load transaction data';
        loading.value = false;
    }
};

const resetFilters = () => {
    filters.start_date = '';
    filters.end_date = '';
    filters.warehouse = '';
    filters.product_code = '';
    filters.customer_code = '';
    filters.reference = '';
    filters.batch = '';
};

const changePage = (page) => {
    if (page < 1 || page > pagination.last_page) return;
    pagination.current_page = page;
    searchTransactions();
};

const formatDate = (dateString) => {
    if (!dateString) return '';
    const date = new Date(dateString);
    return date.toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric'
    });
};

const getCurrentDate = () => {
    return new Date().toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
};

const viewTransactionDetails = (transaction) => {
    // Show transaction details in a modal or navigate to details page
    alert(`View details for transaction: ${transaction.reference}`);
};

const printReport = () => {
    window.print();
};

const exportToExcel = () => {
    alert('Export to Excel functionality will be implemented here');
    // Actual implementation would use a library like SheetJS or server-side export
};

const exportToPDF = () => {
    alert('Export to PDF functionality will be implemented here');
    // Actual implementation would use a library like jsPDF or server-side export
};

// Lifecycle hooks
onMounted(() => {
    // Set default dates for last 30 days
    const today = new Date();
    const thirtyDaysAgo = new Date();
    thirtyDaysAgo.setDate(today.getDate() - 30);
    
    filters.end_date = today.toISOString().slice(0, 10);
    filters.start_date = thirtyDaysAgo.toISOString().slice(0, 10);
    
    searchTransactions();
});
</script>

<style scoped>
.text-shadow {
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

@media print {
    .hide-on-print {
        display: none !important;
    }
    
    body {
        font-size: 12pt;
        color: #000;
    }
    
    .print-section {
        width: 100%;
    }
    
    table {
        width: 100%;
        border-collapse: collapse;
    }
    
    th, td {
        border: 1px solid #ddd;
        padding: 8px;
        text-align: left;
    }
    
    .print-header {
        margin-bottom: 20px;
    }
    
    .print-footer {
        margin-top: 20px;
        font-size: 10pt;
        color: #666;
    }
}
</style> 