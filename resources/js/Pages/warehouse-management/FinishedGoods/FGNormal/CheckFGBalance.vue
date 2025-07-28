<template>
    <AppLayout :header="'Check FG Balance'">
        <!-- Header Section -->
        <div class="bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 p-6 rounded-t-lg shadow-lg overflow-hidden relative">
            <!-- Decorative Elements -->
            <div class="absolute top-0 right-0 w-40 h-40 bg-white opacity-5 rounded-full -translate-y-20 translate-x-20"></div>
            <div class="absolute bottom-0 left-0 w-20 h-20 bg-white opacity-5 rounded-full translate-y-10 -translate-x-10"></div>
            
            <div class="flex items-center">
                <div class="bg-gradient-to-br from-blue-500 to-indigo-600 p-3 rounded-lg shadow-inner flex items-center justify-center relative overflow-hidden mr-4">
                    <div class="absolute -top-1 -right-1 w-6 h-6 bg-yellow-300 opacity-30 rounded-full animate-ping-slow"></div>
                    <div class="absolute -bottom-1 -left-1 w-4 h-4 bg-blue-300 opacity-30 rounded-full animate-ping-slow animation-delay-500"></div>
                    <i class="fas fa-balance-scale text-white text-2xl z-10"></i>
                </div>
                <div>
                    <h2 class="text-2xl md:text-3xl font-bold text-white mb-1 text-shadow">Check FG Balance</h2>
                    <p class="text-blue-100 max-w-2xl">View and check finished goods inventory balance</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-b-lg shadow-lg p-6 mb-6">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left Column - Search and Filters -->
                <div class="lg:col-span-1">
                    <div class="bg-white p-6 rounded-lg shadow-md border-t-4 border-blue-500 mb-6">
                        <div class="flex items-center mb-6 pb-2 border-b border-gray-200">
                            <div class="p-2 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-lg mr-3 shadow-md">
                                <i class="fas fa-search text-white"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-800">Search & Filters</h3>
                        </div>

                        <form @submit.prevent="searchFGBalance" class="space-y-4">
                            <div>
                                <label for="product_code" class="block text-sm font-medium text-gray-700 mb-1">Product Code:</label>
                                <div class="flex">
                                    <input
                                        type="text"
                                        id="product_code"
                                        v-model="filters.product_code"
                                        placeholder="Enter product code"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-l-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    />
                                    <button
                                        type="button"
                                        class="px-3 py-2 bg-gray-100 border border-l-0 border-gray-300 rounded-r-md hover:bg-gray-200"
                                    >
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>

                            <div>
                                <label for="warehouse" class="block text-sm font-medium text-gray-700 mb-1">Warehouse:</label>
                                <select
                                    id="warehouse"
                                    v-model="filters.warehouse"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                >
                                    <option value="">All Warehouses</option>
                                    <option value="FG01">FG01 - Main Finished Goods</option>
                                    <option value="FG02">FG02 - Secondary Finished Goods</option>
                                    <option value="FG03">FG03 - Transit Warehouse</option>
                                </select>
                            </div>

                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status:</label>
                                <select
                                    id="status"
                                    v-model="filters.status"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                >
                                    <option value="">All Status</option>
                                    <option value="available">Available</option>
                                    <option value="reserved">Reserved</option>
                                    <option value="transit">In Transit</option>
                                </select>
                            </div>

                            <div>
                                <label for="batch" class="block text-sm font-medium text-gray-700 mb-1">Batch/Lot Number:</label>
                                <input
                                    type="text"
                                    id="batch"
                                    v-model="filters.batch"
                                    placeholder="Enter batch or lot number"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
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
                                    class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md text-sm shadow-md"
                                >
                                    Search
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Statistics Card -->
                    <div class="bg-white p-6 rounded-lg shadow-md border-t-4 border-green-500">
                        <div class="flex items-center mb-6 pb-2 border-b border-gray-200">
                            <div class="p-2 bg-gradient-to-r from-green-500 to-teal-500 rounded-lg mr-3 shadow-md">
                                <i class="fas fa-chart-pie text-white"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-800">Summary</h3>
                        </div>

                        <div class="space-y-4">
                            <div class="bg-green-50 p-4 rounded-lg border border-green-100">
                                <div class="text-sm text-green-800 font-medium">Total Products</div>
                                <div class="text-2xl font-bold text-green-700">{{ summary.total_products }}</div>
                            </div>
                            <div class="bg-blue-50 p-4 rounded-lg border border-blue-100">
                                <div class="text-sm text-blue-800 font-medium">Total Quantity</div>
                                <div class="text-2xl font-bold text-blue-700">{{ summary.total_quantity.toLocaleString() }}</div>
                            </div>
                            <div class="bg-indigo-50 p-4 rounded-lg border border-indigo-100">
                                <div class="text-sm text-indigo-800 font-medium">Warehouses</div>
                                <div class="text-2xl font-bold text-indigo-700">{{ summary.warehouse_count }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column - Results Table -->
                <div class="lg:col-span-2">
                    <div class="bg-white p-6 rounded-lg shadow-md border-t-4 border-indigo-500">
                        <div class="flex items-center justify-between mb-6 pb-2 border-b border-gray-200">
                            <div class="flex items-center">
                                <div class="p-2 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-lg mr-3 shadow-md">
                                    <i class="fas fa-boxes text-white"></i>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-800">FG Balance</h3>
                            </div>
                            <div class="flex items-center space-x-2">
                                <button
                                    class="px-3 py-1.5 bg-gray-100 hover:bg-gray-200 border border-gray-300 rounded-md text-sm"
                                    @click="exportToExcel"
                                >
                                    <i class="fas fa-file-excel text-green-600 mr-1"></i> Export
                                </button>
                                <button
                                    class="px-3 py-1.5 bg-gray-100 hover:bg-gray-200 border border-gray-300 rounded-md text-sm"
                                    @click="printReport"
                                >
                                    <i class="fas fa-print text-gray-600 mr-1"></i> Print
                                </button>
                            </div>
                        </div>

                        <!-- Results Table -->
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
                                            Warehouse
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Batch/Lot
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Quantity
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Status
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Actions
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr v-for="(item, index) in fgBalanceItems" :key="index" class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ item.product_code }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                            {{ item.description }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                            {{ item.warehouse }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                            {{ item.batch }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium" :class="getQuantityColorClass(item)">
                                            {{ item.quantity.toLocaleString() }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span :class="getStatusBadgeClass(item.status)">
                                                {{ item.status }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                            <button
                                                class="text-blue-600 hover:text-blue-800 mr-3"
                                                @click="viewDetails(item)"
                                            >
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button
                                                class="text-green-600 hover:text-green-800"
                                                @click="showMovementHistory(item)"
                                            >
                                                <i class="fas fa-history"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr v-if="fgBalanceItems.length === 0">
                                        <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500">
                                            <div class="flex flex-col items-center py-6">
                                                <i class="fas fa-search text-gray-400 text-5xl mb-4"></i>
                                                <p class="text-gray-600 font-medium">No items found</p>
                                                <p class="text-gray-400 mt-1">Try adjusting your search criteria</p>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="flex items-center justify-between mt-6 px-4">
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
                                        :class="page === pagination.current_page ? 'z-10 bg-indigo-50 border-indigo-500 text-indigo-600' : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50'"
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
    product_code: '',
    warehouse: '',
    status: '',
    batch: '',
});

const summary = reactive({
    total_products: 0,
    total_quantity: 0,
    warehouse_count: 0
});

const fgBalanceItems = ref([]);
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
const searchFGBalance = async () => {
    try {
        loading.value = true;
        error.value = null;

        // Demo data for display purposes
        fgBalanceItems.value = [
            { 
                product_code: 'FG10001', 
                description: 'Product A - Red', 
                warehouse: 'FG01', 
                batch: 'LOT-2023-001', 
                quantity: 1250, 
                status: 'available' 
            },
            { 
                product_code: 'FG10002', 
                description: 'Product B - Blue', 
                warehouse: 'FG01', 
                batch: 'LOT-2023-002', 
                quantity: 750, 
                status: 'available' 
            },
            { 
                product_code: 'FG10003', 
                description: 'Product C - Green', 
                warehouse: 'FG02', 
                batch: 'LOT-2023-003', 
                quantity: 500, 
                status: 'reserved' 
            },
            { 
                product_code: 'FG10004', 
                description: 'Product D - Yellow', 
                warehouse: 'FG03', 
                batch: 'LOT-2023-004', 
                quantity: 120, 
                status: 'transit' 
            },
        ];

        // Update summary
        summary.total_products = 4;
        summary.total_quantity = 2620;
        summary.warehouse_count = 3;

        // Update pagination
        pagination.current_page = 1;
        pagination.last_page = 1;
        pagination.from = 1;
        pagination.to = fgBalanceItems.value.length;
        pagination.total = fgBalanceItems.value.length;

        loading.value = false;

        /* For actual API implementation:
        const response = await axios.get('/api/fg-balance', {
            params: {
                product_code: filters.product_code,
                warehouse: filters.warehouse,
                status: filters.status,
                batch: filters.batch,
                page: pagination.current_page
            }
        });
        
        fgBalanceItems.value = response.data.data;
        pagination.current_page = response.data.current_page;
        pagination.last_page = response.data.last_page;
        pagination.from = response.data.from;
        pagination.to = response.data.to;
        pagination.total = response.data.total;
        
        // Update summary from response
        summary.total_products = response.data.summary.total_products;
        summary.total_quantity = response.data.summary.total_quantity;
        summary.warehouse_count = response.data.summary.warehouse_count;
        */
        
    } catch (err) {
        console.error('Error fetching FG balance:', err);
        error.value = 'Failed to load FG balance data';
        loading.value = false;
    }
};

const resetFilters = () => {
    filters.product_code = '';
    filters.warehouse = '';
    filters.status = '';
    filters.batch = '';
};

const changePage = (page) => {
    if (page < 1 || page > pagination.last_page) return;
    pagination.current_page = page;
    searchFGBalance();
};

const getStatusBadgeClass = (status) => {
    const classes = 'px-2 py-1 text-xs font-semibold rounded-full';
    switch (status.toLowerCase()) {
        case 'available':
            return `${classes} bg-green-100 text-green-800`;
        case 'reserved':
            return `${classes} bg-yellow-100 text-yellow-800`;
        case 'transit':
            return `${classes} bg-blue-100 text-blue-800`;
        default:
            return `${classes} bg-gray-100 text-gray-800`;
    }
};

const getQuantityColorClass = (item) => {
    return item.quantity > 500 ? 'text-green-600' : 'text-yellow-600';
};

const viewDetails = (item) => {
    alert(`View details for ${item.product_code}`);
};

const showMovementHistory = (item) => {
    alert(`Show movement history for ${item.product_code}`);
};

const exportToExcel = () => {
    alert('Export to Excel functionality will be implemented here');
};

const printReport = () => {
    window.print();
};

// Lifecycle hooks
onMounted(() => {
    searchFGBalance();
});
</script>

<style scoped>
.text-shadow {
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

@media print {
    button {
        display: none !important;
    }
}
</style> 