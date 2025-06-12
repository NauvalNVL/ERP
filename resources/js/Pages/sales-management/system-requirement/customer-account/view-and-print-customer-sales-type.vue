<template>
    <AppLayout :header="'View & Print Customer Sales Type'">
        <div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 p-6">
            <div class="max-w-7xl mx-auto">
                <!-- Header -->
                <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-gradient-to-r from-purple-500 to-indigo-600 rounded-lg flex items-center justify-center">
                                <i class="fas fa-print text-white text-xl"></i>
                            </div>
                            <div>
                                <h1 class="text-2xl font-bold text-gray-800">View & Print Customer Sales Type</h1>
                                <p class="text-gray-600">View and print customer sales type information</p>
                            </div>
                        </div>
                        <div class="flex space-x-3">
                            <button 
                                @click="printReport"
                                class="flex items-center space-x-2 px-4 py-2 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg hover:from-blue-600 hover:to-blue-700 transition-all duration-200 shadow-md hover:shadow-lg"
                            >
                                <i class="fas fa-print"></i>
                                <span>Print</span>
                            </button>
                            <button 
                                @click="refreshData"
                                class="flex items-center space-x-2 px-4 py-2 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-lg hover:from-green-600 hover:to-green-700 transition-all duration-200 shadow-md hover:shadow-lg"
                            >
                                <i class="fas fa-sync-alt"></i>
                                <span>Refresh</span>
                            </button>
                        </div>
                    </div>

                    <!-- Search Bar -->
                    <div class="relative">
                        <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        <input
                            type="text"
                            placeholder="Search customers..."
                            v-model="searchTerm"
                            class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                        />
                    </div>
                </div>

                <!-- Main Content -->
                <div id="printable-content" class="bg-white rounded-xl shadow-lg overflow-hidden">
                    <div class="p-6 border-b border-gray-200 flex justify-between items-center">
                        <h2 class="text-lg font-semibold text-gray-800">Customer Sales Type List</h2>
                    </div>
                    
                    <!-- Customer Table -->
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">Customer Code</th>
                                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">Customer Name</th>
                                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">Sales Type</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                <tr 
                                    v-for="salesType in filteredSalesTypes" 
                                    :key="salesType.id"
                                    class="hover:bg-gray-50"
                                >
                                    <td class="px-6 py-4 text-sm text-gray-900">{{ salesType.customer_code }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-900">{{ salesType.customer_name }}</td>
                                    <td class="px-6 py-4">
                                        <span 
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                                            :class="{
                                                'bg-blue-100 text-blue-800': salesType.sales_type === 'LC',
                                                'bg-green-100 text-green-800': salesType.sales_type === 'EX'
                                            }"
                                        >
                                            {{ salesType.sales_type }}
                                        </span>
                                    </td>
                                </tr>
                                <tr v-if="filteredSalesTypes.length === 0">
                                    <td colspan="3" class="px-6 py-8 text-center text-gray-500">
                                        No customer sales types found. Please adjust your search criteria.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import { useToast } from '@/Composables/useToast';
import axios from 'axios';

// Props
const props = defineProps({
    salesTypes: Array
});

// State
const searchTerm = ref('');
const salesTypesList = ref(props.salesTypes || []);

// Toast
const { showToast } = useToast();

// Computed
const filteredSalesTypes = computed(() => {
    if (!searchTerm.value) {
        return salesTypesList.value;
    }
    
    const searchLower = searchTerm.value.toLowerCase();
    return salesTypesList.value.filter(salesType => 
        salesType.customer_code.toLowerCase().includes(searchLower) ||
        salesType.customer_name.toLowerCase().includes(searchLower) ||
        salesType.sales_type.toLowerCase().includes(searchLower)
    );
});

// Methods
const refreshData = async () => {
    try {
        const response = await axios.get('/api/customer-sales-types');
        salesTypesList.value = response.data;
        showToast('Data refreshed successfully', 'success');
    } catch (error) {
        console.error('Error refreshing data:', error);
        showToast('Error refreshing data', 'error');
    }
};

const printReport = () => {
    const printContent = document.getElementById('printable-content').innerHTML;
    const originalContent = document.body.innerHTML;
    
    document.body.innerHTML = `
        <div class="print-header">
            <h1 style="text-align: center; font-size: 18px; margin-bottom: 20px;">Customer Sales Type Report</h1>
            <p style="text-align: right; font-size: 12px;">Printed on: ${new Date().toLocaleString()}</p>
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