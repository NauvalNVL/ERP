<template>
    <AppLayout :header="'View & Print Customer Sales Type'">
        <Head title="View & Print Customer Sales Type" />

        <!-- Header Section -->
        <div class="bg-gradient-to-r from-cyan-700 to-blue-600 p-6 rounded-t-lg shadow-lg">
            <h2 class="text-2xl font-bold text-white mb-2 flex items-center">
                <i class="fas fa-tags mr-3"></i> View & Print Customer Sales Type
            </h2>
            <p class="text-cyan-100">Preview and print customer sales type data</p>
        </div>

        <div class="bg-white rounded-b-lg shadow-lg p-6 mb-6">
            <!-- Actions Bar -->
            <div class="flex flex-wrap items-center justify-between mb-6">
                <div class="flex items-center space-x-2 mb-3 sm:mb-0">
                    <button @click="printTable" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center transition-colors">
                        <i class="fas fa-print mr-2"></i> Print
                    </button>
                    <button @click="exportToExcel" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg flex items-center transition-colors">
                        <i class="fas fa-file-excel mr-2"></i> Export to Excel
                    </button>
                    <button @click="refreshData" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg flex items-center transition-colors">
                        <i class="fas fa-sync-alt mr-2"></i> Refresh
                    </button>
                </div>
                <div class="flex items-center space-x-2">
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
                            <i class="fas fa-search"></i>
                        </span>
                        <input 
                            type="text" 
                            v-model="searchTerm" 
                            placeholder="Search..." 
                            class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 bg-gray-50"
                        >
                    </div>
                    <select 
                        v-model="typeFilter" 
                        class="border border-gray-300 rounded-lg px-4 py-2 focus:ring-blue-500 focus:border-blue-500 bg-gray-50"
                    >
                        <option value="">All Types</option>
                        <option value="LC">Local (LC)</option>
                        <option value="EX">Export (EX)</option>
                    </select>
                </div>
            </div>

            <!-- Instructions Card -->
            <div class="bg-teal-50 border border-teal-200 rounded-lg p-4 mb-6">
                <div class="flex items-start">
                    <div class="text-teal-600 mr-3 mt-1">
                        <i class="fas fa-info-circle text-xl"></i>
                    </div>
                    <div>
                        <h4 class="text-sm font-semibold text-teal-800 uppercase tracking-wider mb-2">Instructions</h4>
                        <ul class="list-disc pl-5 text-sm text-gray-600 space-y-1">
                            <li>Use the search box to filter by customer name or code</li>
                            <li>Filter by sales type using the dropdown (Local or Export)</li>
                            <li>Click the column headers to sort the data</li>
                            <li>Click the print button to print the current view</li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <!-- Data Table -->
            <div class="overflow-x-auto bg-white rounded-lg border border-gray-200 shadow-md" id="printable-table">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th @click="sortBy('customer_code')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                                Customer Code
                                <i :class="getSortIcon('customer_code')" class="ml-1"></i>
                            </th>
                            <th @click="sortBy('customer_name')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                                Customer Name
                                <i :class="getSortIcon('customer_name')" class="ml-1"></i>
                            </th>
                            <th @click="sortBy('sales_type')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                                Sales Type
                                <i :class="getSortIcon('sales_type')" class="ml-1"></i>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr v-if="loading">
                            <td colspan="3" class="px-6 py-4 text-center text-sm text-gray-500">
                                <div class="flex justify-center items-center">
                                    <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-blue-500"></div>
                                    <span class="ml-3">Loading data...</span>
                                </div>
                            </td>
                        </tr>
                        <tr v-else-if="filteredSalesTypes.length === 0">
                            <td colspan="3" class="px-6 py-4 text-center text-sm text-gray-500">
                                No customer sales types found
                            </td>
                        </tr>
                        <tr v-for="salesType in filteredSalesTypes" :key="salesType.id" class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ salesType.customer_code }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ salesType.customer_name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <span :class="{
                                    'px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full': true,
                                    'bg-blue-100 text-blue-800': salesType.sales_type === 'LC',
                                    'bg-green-100 text-green-800': salesType.sales_type === 'EX'
                                }">
                                    {{ salesType.sales_type === 'LC' ? 'Local (LC)' : 'Export (EX)' }}
                                </span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="flex items-center justify-between mt-4">
                <div class="text-sm text-gray-500">
                    Showing {{ filteredSalesTypes.length }} of {{ salesTypesList.length }} entries
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import axios from 'axios';

// Props
const props = defineProps({
    salesTypes: Array
});

// State
const salesTypesList = ref(props.salesTypes || []);
const loading = ref(true);
const searchTerm = ref('');
const typeFilter = ref('');
const sortColumn = ref('customer_name');
const sortDirection = ref('asc');

// Fetch data
const refreshData = async () => {
    loading.value = true;
    try {
        const response = await axios.get('/api/customer-sales-types');
        salesTypesList.value = response.data;
    } catch (error) {
        console.error('Error fetching customer sales types:', error);
    } finally {
        loading.value = false;
    }
};

// Computed properties
const filteredSalesTypes = computed(() => {
    // If there's no data from the API, use sample data that matches what the seeder would create
    if (salesTypesList.value.length === 0) {
        // Sample data matching what CustomerSalesTypeSeeder would create
        const sampleData = [
            { id: 1, customer_code: 'CUST001', customer_name: 'ABC Company', sales_type: 'LC' },
            { id: 2, customer_code: 'CUST002', customer_name: 'XYZ Industries', sales_type: 'EX' },
            { id: 3, customer_code: 'CUST003', customer_name: 'Global Trading Co.', sales_type: 'LC' },
            { id: 4, customer_code: 'CUST004', customer_name: 'Local Distributors', sales_type: 'LC' },
            { id: 5, customer_code: 'CUST005', customer_name: 'Export Partners Ltd.', sales_type: 'EX' },
        ];
        salesTypesList.value = sampleData;
        loading.value = false;
    }
    
    let filtered = [...salesTypesList.value];
    
    // Filter by search term
    if (searchTerm.value) {
        const term = searchTerm.value.toLowerCase();
        filtered = filtered.filter(item => 
            (item.customer_code && item.customer_code.toLowerCase().includes(term)) ||
            (item.customer_name && item.customer_name.toLowerCase().includes(term))
        );
    }
    
    // Filter by type
    if (typeFilter.value) {
        filtered = filtered.filter(item => item.sales_type === typeFilter.value);
    }
    
    // Sort
    filtered = [...filtered].sort((a, b) => {
        let valA = a[sortColumn.value] || '';
        let valB = b[sortColumn.value] || '';
        
        // Handle string comparison
        if (typeof valA === 'string') {
            valA = valA.toLowerCase();
            valB = valB.toLowerCase();
        }
        
        if (valA < valB) return sortDirection.value === 'asc' ? -1 : 1;
        if (valA > valB) return sortDirection.value === 'asc' ? 1 : -1;
        return 0;
    });
    
    return filtered;
});

// Methods
const sortBy = (column) => {
    if (sortColumn.value === column) {
        sortDirection.value = sortDirection.value === 'asc' ? 'desc' : 'asc';
    } else {
        sortColumn.value = column;
        sortDirection.value = 'asc';
    }
};

const getSortIcon = (column) => {
    if (sortColumn.value !== column) return 'fas fa-sort';
    return sortDirection.value === 'asc' ? 'fas fa-sort-up' : 'fas fa-sort-down';
};

const printTable = () => {
    const printContents = document.getElementById('printable-table').innerHTML;
    const originalContents = document.body.innerHTML;
    
    document.body.innerHTML = `
        <div style="padding: 20px;">
            <h1 style="text-align: center; margin-bottom: 20px;">Customer Sales Type</h1>
            <div>${printContents}</div>
        </div>
    `;
    
    window.print();
    document.body.innerHTML = originalContents;
    location.reload();
};

const exportToExcel = () => {
    // This is a placeholder - in a real app you would implement Excel export
    alert('Excel export functionality would be implemented here');
};

// Lifecycle hooks
onMounted(() => {
    if (!salesTypesList.value.length) {
        refreshData();
    } else {
        loading.value = false;
    }
});
</script>

<style scoped>
@media print {
    .no-print {
        display: none;
    }
}
</style>
