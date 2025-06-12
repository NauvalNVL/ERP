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
                <button @click="printReport" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded flex items-center space-x-2">
                    <i class="fas fa-print mr-2"></i> Print List
                </button>
                <Link href="/customer-sales-type" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded flex items-center space-x-2">
                    <i class="fas fa-arrow-left mr-2"></i> Back to Customer Sales Type
                </Link>
            </div>
            <div class="flex items-center space-x-2">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                    <input 
                        type="text" 
                        v-model="searchTerm" 
                        class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Search customer sales type..."
                    >
                </div>
                <div>
                    <select v-model="typeFilter" class="py-2 pl-3 pr-8 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                        <option value="all">All Types</option>
                        <option value="LC">Local (LC)</option>
                        <option value="EX">Export (EX)</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Table Section -->
        <div class="overflow-x-auto">
            <div id="printable-content" class="min-w-full bg-white border border-gray-200 rounded-lg overflow-hidden">
                <!-- Table Header -->
                <div class="bg-gradient-to-r from-blue-600 to-blue-700 text-white py-4 px-6 flex items-center">
                    <div class="flex items-center">
                        <div class="mr-4">
                            <i class="fas fa-tags text-3xl"></i>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold">CUSTOMER SALES TYPE LIST</h2>
                            <p class="text-sm opacity-80">View and print customer sales type data</p>
                        </div>
                    </div>
                </div>

                <!-- Table Content -->
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th @click="sortTable('customer_code')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                                Customer Code <i class="fas fa-sort ml-1"></i>
                            </th>
                            <th @click="sortTable('customer_name')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                                Customer Name <i class="fas fa-sort ml-1"></i>
                            </th>
                            <th @click="sortTable('sales_type')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                                Sales Type <i class="fas fa-sort ml-1"></i>
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Description
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr v-if="loading" class="hover:bg-gray-50">
                            <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                                <div class="flex justify-center">
                                    <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-blue-500"></div>
                                </div>
                                <p class="mt-2">Loading customer sales type data...</p>
                            </td>
                        </tr>
                        <tr v-else-if="filteredSalesTypes.length === 0" class="hover:bg-gray-50">
                            <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                                No customer sales types found. 
                                <template v-if="searchTerm">
                                    <p class="mt-2">No results match your search query: "{{ searchTerm }}"</p>
                                    <button @click="searchTerm = ''" class="mt-2 text-blue-500 hover:underline">Clear search</button>
                                </template>
                            </td>
                        </tr>
                        <tr v-for="(salesType, index) in filteredSalesTypes" :key="salesType.id" 
                            :class="{'bg-blue-50': index % 2 === 0}" 
                            class="hover:bg-blue-100">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ salesType.customer_code }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ salesType.customer_name }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span 
                                    class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium"
                                    :class="{
                                        'bg-blue-100 text-blue-800': salesType.sales_type === 'LC',
                                        'bg-green-100 text-green-800': salesType.sales_type === 'EX'
                                    }">
                                    {{ salesType.sales_type }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    {{ salesType.sales_type === 'LC' ? 'Local' : 'Export' }}
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <!-- Table Footer -->
                <div class="bg-gray-50 px-6 py-3 border-t border-gray-200 text-sm text-gray-500">
                    <div class="flex items-center justify-between">
                        <div>Total Customer Sales Types: {{ filteredSalesTypes.length }}</div>
                        <div v-if="searchTerm || typeFilter !== 'all'">Filtered from {{ salesTypesList.length }} total records</div>
                        <div class="text-xs text-gray-400">Generated: {{ currentDate }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Print Instructions -->
        <div class="mt-6 bg-blue-50 p-4 rounded-lg border border-blue-100">
            <h3 class="font-semibold text-blue-800 mb-2 flex items-center">
                <i class="fas fa-info-circle mr-2"></i> Print Instructions
            </h3>
            <ul class="list-disc pl-5 text-sm text-gray-600 space-y-1">
                <li>Click the "Print List" button above to print this customer sales type list</li>
                <li>Use landscape orientation for better results</li>
                <li>You can search, filter by sales type, or sort data before printing</li>
                <li>Only the table will be included in the print output</li>
            </ul>
        </div>
    </div>
    </AppLayout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import { useToast } from '@/Composables/useToast';
import axios from 'axios';

// Props
const props = defineProps({
    salesTypes: Array
});

// State
const searchTerm = ref('');
const typeFilter = ref('all'); // 'all', 'LC', 'EX'
const sortColumn = ref('customer_code');
const sortDirection = ref('asc');
const salesTypesList = ref(props.salesTypes || []);
const loading = ref(true);
const currentDate = new Date().toLocaleString();

// Toast
const { showToast } = useToast();

// Computed
const filteredSalesTypes = computed(() => {
    let result = salesTypesList.value;
    
    // Apply search filter
    if (searchTerm.value) {
        const searchLower = searchTerm.value.toLowerCase();
        result = result.filter(salesType => 
            salesType.customer_code.toLowerCase().includes(searchLower) ||
            salesType.customer_name.toLowerCase().includes(searchLower) ||
            salesType.sales_type.toLowerCase().includes(searchLower)
        );
    }
    
    // Apply type filter
    if (typeFilter.value !== 'all') {
        result = result.filter(salesType => salesType.sales_type === typeFilter.value);
    }
    
    // Apply sorting
    result = [...result].sort((a, b) => {
        let aValue = a[sortColumn.value];
        let bValue = b[sortColumn.value];
        
        if (typeof aValue === 'string') {
            aValue = aValue.toLowerCase();
            bValue = bValue.toLowerCase();
        }
        
        if (aValue < bValue) return sortDirection.value === 'asc' ? -1 : 1;
        if (aValue > bValue) return sortDirection.value === 'asc' ? 1 : -1;
        return 0;
    });
    
    return result;
});

// Methods
const refreshData = async () => {
    loading.value = true;
    try {
        const response = await axios.get('/api/customer-sales-types');
        salesTypesList.value = response.data;
        showToast('Data refreshed successfully', 'success');
    } catch (error) {
        console.error('Error refreshing data:', error);
        showToast('Error refreshing data', 'error');
    } finally {
        loading.value = false;
    }
};

const sortTable = (column) => {
    if (sortColumn.value === column) {
        sortDirection.value = sortDirection.value === 'asc' ? 'desc' : 'asc';
    } else {
        sortColumn.value = column;
        sortDirection.value = 'asc';
    }
};

const printReport = () => {
    const printContent = document.getElementById('printable-content').innerHTML;
    const originalContent = document.body.innerHTML;
    
    document.body.innerHTML = `
        <div class="print-header">
            <h1 style="text-align: center; font-size: 18px; margin-bottom: 20px;">Customer Sales Type Report</h1>
            <p style="text-align: right; font-size: 12px;">Printed on: ${currentDate}</p>
        </div>
        ${printContent}
    `;
    
    window.print();
    document.body.innerHTML = originalContent;
    
    // Reload the page to restore Vue functionality
    window.location.reload();
};

// Load data on mount
onMounted(async () => {
    if (!salesTypesList.value.length) {
        await refreshData();
    } else {
        loading.value = false;
    }
});
</script>

<style scoped>
@media print {
    body {
        padding: 20px;
        font-size: 12px;
    }
    
    button {
        display: none;
    }
    
    .print-header {
        margin-bottom: 20px;
    }
}
</style> 