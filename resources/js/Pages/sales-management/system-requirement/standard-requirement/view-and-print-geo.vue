<template>
    <Head title="View & Print Geo Data" />

    <!-- Header Section -->
    <div class="bg-gradient-to-r from-cyan-700 to-blue-600 p-6 rounded-t-lg shadow-lg">
        <h2 class="text-2xl font-bold text-white mb-2 flex items-center">
            <i class="fas fa-globe-americas mr-3"></i> View & Print Geo Data
        </h2>
        <p class="text-cyan-100">Preview and print geographical data</p>
    </div>

    <div class="bg-white rounded-b-lg shadow-lg p-6 mb-6">
        <!-- Actions Bar -->
        <div class="flex flex-wrap items-center justify-between mb-6">
            <div class="flex items-center space-x-2 mb-3 sm:mb-0">
                <button @click="printTable" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded flex items-center space-x-2">
                    <i class="fas fa-print mr-2"></i> Print List
                </button>
                <Link href="/vue/geo" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded flex items-center space-x-2">
                    <i class="fas fa-arrow-left mr-2"></i> Back to Geo Management
                </Link>
            </div>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <i class="fas fa-search text-gray-400"></i>
                </div>
                <input 
                    type="text" 
                    v-model="searchQuery" 
                    class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                    placeholder="Search geo data..."
                >
            </div>
        </div>

        <!-- Table Section -->
        <div class="overflow-x-auto">
            <div id="printableTable" class="min-w-full bg-white border border-gray-200 rounded-lg overflow-hidden">
                <!-- Table Header -->
                <div class="bg-gradient-to-r from-blue-600 to-blue-700 text-white py-4 px-6 flex items-center">
                    <div class="flex items-center">
                        <div class="mr-4">
                            <i class="fas fa-globe-americas text-3xl"></i>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold">GEO DATA LIST</h2>
                            <p class="text-sm opacity-80">View and print geographical data</p>
                        </div>
                    </div>
                </div>

                <!-- Table Content -->
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th @click="sortTable('index')" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                                No <i class="fas fa-sort ml-1"></i>
                            </th>
                            <th @click="sortTable('code')" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                                Code <i class="fas fa-sort ml-1"></i>
                            </th>
                            <th @click="sortTable('country')" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                                Country <i class="fas fa-sort ml-1"></i>
                            </th>
                            <th @click="sortTable('state')" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                                State <i class="fas fa-sort ml-1"></i>
                            </th>
                            <th @click="sortTable('town')" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                                Town <i class="fas fa-sort ml-1"></i>
                            </th>
                            <th @click="sortTable('town_section')" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                                Town Section <i class="fas fa-sort ml-1"></i>
                            </th>
                            <th @click="sortTable('area')" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                                Area <i class="fas fa-sort ml-1"></i>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr v-if="loading" class="hover:bg-gray-50">
                            <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                                <div class="flex justify-center">
                                    <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-blue-500"></div>
                                </div>
                                <p class="mt-2">Loading geo data...</p>
                            </td>
                        </tr>
                        <tr v-else-if="filteredGeos.length === 0" class="hover:bg-gray-50">
                            <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                                No geo data available.
                                <template v-if="searchQuery">
                                    <p class="mt-2">No results match your search query: "{{ searchQuery }}"</p>
                                    <button @click="searchQuery = ''" class="mt-2 text-blue-500 hover:underline">Clear search</button>
                                </template>
                            </td>
                        </tr>
                        <tr v-for="(geo, index) in filteredGeos" :key="geo.code" 
                            :class="{'bg-blue-50': index % 2 === 0}" 
                            class="hover:bg-blue-100">
                            <td class="px-4 py-3 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ index + 1 }}</div>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ geo.code }}</div>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ geo.country || 'N/A' }}</div>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ geo.state || 'N/A' }}</div>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ geo.town || 'N/A' }}</div>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ geo.town_section || 'N/A' }}</div>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">
                                    {{ geo.area || 'N/A' }}
                                </span>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <!-- Table Footer -->
                <div class="bg-gray-50 px-6 py-3 border-t border-gray-200 text-sm text-gray-500">
                    <div class="flex items-center justify-between">
                        <div>Total Geo Records: {{ filteredGeos.length }}</div>
                        <div v-if="searchQuery">Filtered from {{ geos.length }} total records</div>
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
                <li>Click the "Print List" button above to print this geo data list</li>
                <li>Use landscape orientation for better results</li>
                <li>You can search or sort data before printing</li>
                <li>Only the table will be included in the print output</li>
            </ul>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { Head, Link } from '@inertiajs/vue3';

// Data
const geos = ref([]);
const loading = ref(true);
const searchQuery = ref('');
const sortColumn = ref('code');
const sortDirection = ref('asc');
const currentDate = new Date().toLocaleString();

// Fetch geos from API
const fetchGeos = async () => {
    loading.value = true;
    try {
        const response = await fetch('/geo', {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        
        if (!response.ok) {
            throw new Error('Failed to fetch geo data');
        }
        
        const data = await response.json();
        geos.value = data;
    } catch (error) {
        console.error('Error fetching geo data:', error);
    } finally {
        loading.value = false;
    }
};

// Sort function
const sortTable = (column) => {
    if (sortColumn.value === column) {
        sortDirection.value = sortDirection.value === 'asc' ? 'desc' : 'asc';
    } else {
        sortColumn.value = column;
        sortDirection.value = 'asc';
    }
};

// Filtered and sorted geos
const filteredGeos = computed(() => {
    let filtered = [...geos.value];
    
    // Apply search filter
    if (searchQuery.value) {
        const query = searchQuery.value.toLowerCase();
        filtered = filtered.filter(geo => 
            (geo.code && geo.code.toLowerCase().includes(query)) ||
            (geo.country && geo.country.toLowerCase().includes(query)) ||
            (geo.state && geo.state.toLowerCase().includes(query)) ||
            (geo.town && geo.town.toLowerCase().includes(query)) ||
            (geo.town_section && geo.town_section.toLowerCase().includes(query)) ||
            (geo.area && geo.area.toLowerCase().includes(query))
        );
    }
    
    // Apply sorting
    filtered.sort((a, b) => {
        // Special case for index (always sort by index number)
        if (sortColumn.value === 'index') {
            return sortDirection.value === 'asc' ? 
                geos.value.indexOf(a) - geos.value.indexOf(b) :
                geos.value.indexOf(b) - geos.value.indexOf(a);
        }
        
        let valueA = a[sortColumn.value];
        let valueB = b[sortColumn.value];
        
        // Handle null values
        if (valueA === null || valueA === undefined) valueA = '';
        if (valueB === null || valueB === undefined) valueB = '';
        
        // Convert to string for comparison if not already
        if (typeof valueA !== 'string') valueA = valueA.toString();
        if (typeof valueB !== 'string') valueB = valueB.toString();
        
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

// Print function
const printTable = () => {
    const printContent = document.getElementById('printableTable');
    const newWin = window.open('', '_blank');

    newWin.document.write('<html><head><title>Print Geo Data</title>');
    newWin.document.write('<style>');
    newWin.document.write('body { font-family: Arial, sans-serif; }');
    newWin.document.write('table { width: 100%; border-collapse: collapse; }');
    newWin.document.write('th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }');
    newWin.document.write('th { background-color: #f2f2f2; }');
    newWin.document.write('tr:nth-child(even) { background-color: #f9f9f9; }');
    newWin.document.write('.header { background-color: #1e40af; color: white; padding: 10px; display: flex; align-items: center; }');
    newWin.document.write('.header-text { margin-left: 15px; }');
    newWin.document.write('.footer { background-color: #f2f2f2; padding: 8px; border-top: 1px solid #ddd; display: flex; justify-content: space-between; }');
    newWin.document.write('@media print { @page { size: landscape; } }');
    newWin.document.write('</style></head><body>');
    newWin.document.write(printContent.outerHTML);
    newWin.document.write('<script>window.onload = function() { window.print(); window.close(); }<\/script>');
    newWin.document.write('</body></html>');
    
    newWin.document.close();
};

// Lifecycle hooks
onMounted(() => {
    fetchGeos();
});
</script>

<style scoped>
@media print {
    body * {
        visibility: hidden;
    }
    #printableTable, #printableTable * {
        visibility: visible;
    }
    #printableTable {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
    }
}
</style>
