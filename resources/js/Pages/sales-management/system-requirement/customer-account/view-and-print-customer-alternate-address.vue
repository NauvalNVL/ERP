<template>
    <AppLayout :header="'View & Print Customer Alternate Addresses'">
    <Head title="View & Print Customer Alternate Addresses" />

    <!-- Header Section -->
    <div class="bg-gradient-to-r from-cyan-700 to-blue-600 p-6 rounded-t-lg shadow-lg">
        <h2 class="text-2xl font-bold text-white mb-2 flex items-center">
            <i class="fas fa-print mr-3"></i> View & Print Customer Alternate Addresses
        </h2>
        <p class="text-cyan-100">Preview and print customer alternate address data</p>
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
                    v-model="recordStatus" 
                    class="border border-gray-300 rounded-lg px-4 py-2 focus:ring-blue-500 focus:border-blue-500 bg-gray-50"
                >
                    <option value="both">All Records</option>
                    <option value="Active">Active Only</option>
                    <option value="Obsolete">Obsolete Only</option>
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
                        <li>Use the search box to filter by customer name, code, or address</li>
                        <li>Click the column headers to sort the data</li>
                        <li>Use the status filter to show active or obsolete records</li>
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
                        <th @click="sortBy('customer_name')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                            Customer Name
                            <i class="fas fa-sort ml-1"></i>
                        </th>
                        <th @click="sortBy('customer_code')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                            Customer Code
                            <i class="fas fa-sort ml-1"></i>
                        </th>
                        <th @click="sortBy('address')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                            Address
                            <i class="fas fa-sort ml-1"></i>
                        </th>
                        <th @click="sortBy('city')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                            City
                            <i class="fas fa-sort ml-1"></i>
                        </th>
                        <th @click="sortBy('phone')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                            Phone
                            <i class="fas fa-sort ml-1"></i>
                        </th>
                        <th @click="sortBy('status')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                            Status
                            <i class="fas fa-sort ml-1"></i>
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr v-if="loading">
                        <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">
                            <div class="flex justify-center items-center">
                                <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-blue-500"></div>
                                <span class="ml-3">Loading data...</span>
                            </div>
                        </td>
                    </tr>
                    <tr v-else-if="filteredAddresses.length === 0">
                        <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">
                            No customer alternate addresses found
                        </td>
                    </tr>
                    <tr v-for="address in filteredAddresses" :key="address.id" class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ address.customer_name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ address.customer_code }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ address.address }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ address.city }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ address.phone }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <span :class="{
                                'px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full': true,
                                'bg-green-100 text-green-800': address.status === 'Active',
                                'bg-red-100 text-red-800': address.status === 'Obsolete'
                            }">
                                {{ address.status }}
                            </span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="flex items-center justify-between mt-4">
            <div class="text-sm text-gray-500">
                Showing {{ filteredAddresses.length }} of {{ addresses.length }} entries
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

// Data
const addresses = ref([]);
const loading = ref(true);
const searchTerm = ref('');
const recordStatus = ref('both');
const sortColumn = ref('customer_name');
const sortDirection = ref('asc');

// Fetch data
const fetchData = async () => {
    loading.value = true;
    try {
        const response = await axios.get('/api/customer-alternate-addresses');
        addresses.value = response.data;
    } catch (error) {
        console.error('Error fetching customer alternate addresses:', error);
    } finally {
        loading.value = false;
    }
};

// Computed properties
const filteredAddresses = computed(() => {
    let filtered = addresses.value;
    
    // Filter by search term
    if (searchTerm.value) {
        const term = searchTerm.value.toLowerCase();
        filtered = filtered.filter(address => 
            address.customer_name.toLowerCase().includes(term) ||
            address.customer_code.toLowerCase().includes(term) ||
            address.address.toLowerCase().includes(term) ||
            address.city.toLowerCase().includes(term)
        );
    }
    
    // Filter by status
    if (recordStatus.value !== 'both') {
        filtered = filtered.filter(address => address.status === recordStatus.value);
    }
    
    // Sort
    filtered.sort((a, b) => {
        let valA = a[sortColumn.value];
        let valB = b[sortColumn.value];
        
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

const printTable = () => {
    const printContents = document.getElementById('printable-table').innerHTML;
    const originalContents = document.body.innerHTML;
    
    document.body.innerHTML = `
        <div style="padding: 20px;">
            <h1 style="text-align: center; margin-bottom: 20px;">Customer Alternate Addresses</h1>
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
    fetchData();
});
</script>

<style scoped>
@media print {
    .no-print {
        display: none !important;
    }
    
    body {
        font-size: 12pt;
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
    
    th {
        background-color: #f2f2f2;
    }
}
</style>