<template>
    <AppLayout :header="'View & Print Rough Cut Target Capacity'">
    <Head title="View & Print Rough Cut Target Capacity" />
    
        <!-- Header Section -->
    <div class="bg-gradient-to-r from-cyan-700 to-blue-600 p-6 rounded-t-lg shadow-lg">
        <h2 class="text-2xl font-bold text-white mb-2 flex items-center">
            <i class="fas fa-print mr-3"></i> View & Print Rough Cut Target Capacity
        </h2>
        <p class="text-cyan-100">Preview and print rough cut target capacity data</p>
    </div>

    <div class="bg-white rounded-b-lg shadow-lg p-6 mb-6">
        <!-- Actions Bar -->
        <div class="flex flex-wrap items-center justify-between mb-6">
            <div class="flex items-center space-x-2 mb-3 sm:mb-0">
                <button @click="printTable" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded flex items-center space-x-2">
                    <i class="fas fa-print mr-2"></i> Print List
                </button>
                <Link href="/sales-order/setup/define-so-rough-cut" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded flex items-center space-x-2">
                    <i class="fas fa-arrow-left mr-2"></i> Back to SO Rough Cut Definition
                </Link>
            </div>
                <div class="relative flex items-center">
                <label for="period-search" class="mr-2 text-gray-700">Period:</label>
                <input 
                    id="period-search"
                    type="text" 
                    v-model="periodSearchQuery" 
                    class="w-32 px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 mr-2"
                    placeholder="mm/yyyy"
                >
                <button @click="fetchRoughCutCapacity" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded flex items-center space-x-2">
                    Search
                </button>
            </div>
        </div>

        <!-- Table Section -->
        <div class="overflow-x-auto">
            <div id="printableTable" class="min-w-full bg-white border border-gray-200 rounded-lg overflow-hidden">
                <!-- Table Header -->
                <div class="bg-gradient-to-r from-blue-600 to-blue-700 text-white py-4 px-6 flex items-center">
                    <div class="flex items-center">
                        <div class="mr-4">
                            <i class="fas fa-chart-line text-3xl"></i>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold">ROUGH CUT TARGET CAPACITY LIST ({{ currentPeriodDisplay }})</h2>
                            <p class="text-sm opacity-80">View and print rough cut target capacity data</p>
                        </div>
            </div>
        </div>

                <!-- Table Content -->
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                    <tr>
                            <th @click="sortTable('day')" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                            Day <i :class="getSortIcon('day')"></i>
                        </th>
                            <th @click="sortTable('l_meter')" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                            L/Meter <i :class="getSortIcon('l_meter')"></i>
                        </th>
                            <th @click="sortTable('pieces')" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                            Pieces <i :class="getSortIcon('pieces')"></i>
                        </th>
                    </tr>
                </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr v-if="loading" class="hover:bg-gray-50">
                            <td colspan="3" class="px-3 py-4 text-center text-gray-500">
                                <div class="flex justify-center">
                                    <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-blue-500"></div>
                                </div>
                                <p class="mt-2">Loading rough cut target capacity data...</p>
                            </td>
                    </tr>
                        <tr v-else-if="filteredCapacities.length === 0" class="hover:bg-gray-50">
                            <td colspan="3" class="px-3 py-4 text-center text-gray-500">
                            No rough cut target capacity data found for {{ currentPeriodDisplay || 'selected period' }}.
                                <template v-if="periodSearchQuery">
                                    <p class="mt-2">No results match your search query: "{{ periodSearchQuery }}"</p>
                                    <button @click="periodSearchQuery = ''" class="mt-2 text-blue-500 hover:underline">Clear search</button>
                                </template>
                        </td>
                    </tr>
                        <tr v-for="(item, index) in filteredCapacities" :key="item.day" 
                            :class="{'bg-blue-50': index % 2 === 0}" 
                            class="hover:bg-blue-100">
                            <td class="px-3 py-4 whitespace-nowrap text-sm font-medium">{{ item.day || 'N/A' }}</td>
                            <td class="px-3 py-4 whitespace-nowrap text-sm">{{ item.l_meter || 'N/A' }}</td>
                            <td class="px-3 py-4 whitespace-nowrap text-sm">{{ item.pieces || 'N/A' }}</td>
                    </tr>
                </tbody>
            </table>

                <!-- Table Footer -->
                <div class="bg-gray-50 px-6 py-3 border-t border-gray-200 text-sm text-gray-500">
                    <div class="flex items-center justify-between">
                        <div>Total Entries: {{ filteredCapacities.length }}</div>
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
                <li>Click the "Print List" button above to print this rough cut target capacity list</li>
                <li>Use landscape orientation for better results</li>
                <li>You can search or sort data before printing</li>
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

// Data
const capacities = ref([]);
const loading = ref(true);
const periodSearchQuery = ref(''); // For the period input field
const currentPeriodDisplay = ref(''); // To display the period currently shown in the table
const sortColumn = ref('day');
const sortDirection = ref('asc');
const currentDate = new Date().toLocaleString();

// Fetch rough cut capacity data from the API
const fetchRoughCutCapacity = async () => {
    loading.value = true;
    try {
        // Construct the API URL with the period search query
        const apiUrl = periodSearchQuery.value 
            ? `/api/so-rough-cut-capacity?period=${encodeURIComponent(periodSearchQuery.value)}`
            : '/api/so-rough-cut-capacity'; // Fetch all if no period is specified initially

        const response = await fetch(apiUrl, {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        
        if (!response.ok) {
            throw new Error('Failed to fetch rough cut target capacity data');
        }
        
        const data = await response.json();
        
        // Handle different API response formats
        if (data.data) {
            capacities.value = data.data;
        } else if (Array.isArray(data)) {
            capacities.value = data;
        } else {
            console.error('Unexpected API response format:', data);
            capacities.value = [];
        }

        // Update the displayed period based on the successful fetch
        currentPeriodDisplay.value = periodSearchQuery.value;

    } catch (error) {
        console.error('Error fetching rough cut target capacity data:', error);
        capacities.value = [];
    } finally {
        loading.value = false;
    }
};

// Sort table
const sortTable = (column) => {
    if (sortColumn.value === column) {
        sortDirection.value = sortDirection.value === 'asc' ? 'desc' : 'asc';
    } else {
        sortColumn.value = column;
        sortDirection.value = 'asc';
    }
};

// Get sort icon
const getSortIcon = (column) => {
    if (sortColumn.value !== column) {
        return 'fas fa-sort text-gray-300';
    }
    
    return sortDirection.value === 'asc' 
        ? 'fas fa-sort-up text-blue-500' 
        : 'fas fa-sort-down text-blue-500';
};

// Filtered and sorted capacities (no text search, only period search) 
const filteredCapacities = computed(() => {
    let filtered = [...capacities.value];
    
    // Apply sorting
    filtered.sort((a, b) => {
        let valueA = a[sortColumn.value];
        let valueB = b[sortColumn.value];
        
        // Handle null values
        if (valueA === null || valueA === undefined) valueA = '';
        if (valueB === null || valueB === undefined) valueB = '';
        
        // Numeric sort for L/Meter and Pieces
        if (['l_meter', 'pieces', 'day'].includes(sortColumn.value)) {
            const numA = parseFloat(valueA);
            const numB = parseFloat(valueB);
            if (sortDirection.value === 'asc') {
                return numA - numB;
            } else {
                return numB - numA;
            }
        }
        
        // Default to string comparison
        if (typeof valueA !== 'string') valueA = String(valueA || '');
        if (typeof valueB !== 'string') valueB = String(valueB || '');
        
        // Case insensitive comparison
        valueA = valueA.toLowerCase();
        valueB = valueB.toLowerCase();
        
        // Sort direction
        if (sortDirection.value === 'asc') {
            return valueA.localeCompare(valueB);
        } else {
            return valueB.localeCompare(valueA);
        }
    });
    
    return filtered;
});

// Print table
const printTable = () => {
    const printableContent = document.getElementById('printableTable').outerHTML;
    const originalContent = document.body.innerHTML;
    document.body.innerHTML = printableContent;
    window.print();
    document.body.innerHTML = originalContent;
    location.reload(); // Reload to restore original page state and functionality
};

// Lifecycle hook - fetch data on initial load
onMounted(() => {
    // Optionally pre-fill with current month/year or fetch default data
    const today = new Date();
    const month = (today.getMonth() + 1).toString().padStart(2, '0');
    const year = today.getFullYear();
    periodSearchQuery.value = `${month}/${year}`;
    fetchRoughCutCapacity();
});
</script>

<style scoped>
/* Add any specific styles here if needed */
</style> 