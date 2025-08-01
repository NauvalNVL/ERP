<template>
    <AppLayout :header="'View & Print Color Groups'">
    <Head title="View & Print Color Groups" />

    <!-- Header Section -->
    <div class="bg-gradient-to-r from-cyan-700 to-blue-600 p-6 rounded-t-lg shadow-lg">
        <h2 class="text-2xl font-bold text-white mb-2 flex items-center">
            <i class="fas fa-print mr-3"></i> View & Print Color Groups
        </h2>
        <p class="text-cyan-100">Preview and print color group data</p>
    </div>

    <div class="bg-white rounded-b-lg shadow-lg p-6 mb-6">
        <!-- Actions Bar -->
        <div class="flex flex-wrap items-center justify-between mb-6">
            <div class="flex items-center space-x-2 mb-3 sm:mb-0">
                <button @click="printTable" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded flex items-center space-x-2">
                    <i class="fas fa-print mr-2"></i> Print List
                </button>
                <Link href="/color-group" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded flex items-center space-x-2">
                    <i class="fas fa-arrow-left mr-2"></i> Back to Color Groups
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
                    placeholder="Search color groups..."
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
                            <i class="fas fa-layer-group text-3xl"></i>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold">COLOR GROUP LIST</h2>
                            <p class="text-sm opacity-80">View and print color group data</p>
                        </div>
                    </div>
                </div>

                <!-- Table Content -->
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th @click="sortTable('cg')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                                CG# <i class="fas fa-sort ml-1"></i>
                            </th>
                            <th @click="sortTable('cg_name')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                                CG Name <i class="fas fa-sort ml-1"></i>
                            </th>
                            <th @click="sortTable('cg_type')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                                CG Type <i class="fas fa-sort ml-1"></i>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr v-if="loading" class="hover:bg-gray-50">
                            <td colspan="3" class="px-6 py-4 text-center text-gray-500">
                                <div class="flex justify-center">
                                    <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-blue-500"></div>
                                </div>
                                <p class="mt-2">Loading color group data...</p>
                            </td>
                        </tr>
                        <tr v-else-if="filteredColorGroups.length === 0" class="hover:bg-gray-50">
                            <td colspan="3" class="px-6 py-4 text-center text-gray-500">
                                No color group data found. 
                                <template v-if="searchQuery">
                                    <p class="mt-2">No results match your search query: "{{ searchQuery }}"</p>
                                    <button @click="searchQuery = ''" class="mt-2 text-blue-500 hover:underline">Clear search</button>
                                </template>
                            </td>
                        </tr>
                        <tr v-for="(group, index) in filteredColorGroups" :key="group.cg" 
                            :class="{'bg-blue-50': index % 2 === 0}" 
                            class="hover:bg-blue-100">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ group.cg }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ group.cg_name }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full" 
                                    :class="getCGTypeClass(group.cg_type)">
                                    {{ group.cg_type }}
                                </span>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <!-- Table Footer -->
                <div class="bg-gray-50 px-6 py-3 border-t border-gray-200 text-sm text-gray-500">
                    <div class="flex items-center justify-between">
                        <div>Total Color Groups: {{ filteredColorGroups.length }}</div>
                        <div v-if="searchQuery">Filtered from {{ colorGroups.length }} total records</div>
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
                <li>Click the "Print List" button above to print this color group list</li>
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

// Data
const colorGroups = ref([]);
const loading = ref(true);
const searchQuery = ref('');
const sortColumn = ref('cg');
const sortDirection = ref('asc');
const currentDate = new Date().toLocaleString();

// Fetch color groups from API
const fetchColorGroups = async () => {
    loading.value = true;
    try {
        const response = await fetch('/api/color-groups', {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        
        if (!response.ok) {
            throw new Error('Failed to fetch color groups');
        }
        
        const data = await response.json();
        
        if (Array.isArray(data)) {
            colorGroups.value = data;
        } else if (data.data && Array.isArray(data.data)) {
            colorGroups.value = data.data;
        } else {
            console.error('Unexpected API response format:', data);
            colorGroups.value = [];
        }
    } catch (error) {
        console.error('Error fetching color groups:', error);
        colorGroups.value = [];
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

// Filtered and sorted color groups
const filteredColorGroups = computed(() => {
    let filtered = [...colorGroups.value];
    
    // Apply search filter
    if (searchQuery.value) {
        const query = searchQuery.value.toLowerCase();
        filtered = filtered.filter(group => 
            group.cg.toLowerCase().includes(query) ||
            group.cg_name.toLowerCase().includes(query) ||
            group.cg_type.toLowerCase().includes(query)
        );
    }
    
    // Apply sorting
    filtered.sort((a, b) => {
        const valueA = a[sortColumn.value].toLowerCase();
        const valueB = b[sortColumn.value].toLowerCase();
        
        if (sortDirection.value === 'asc') {
            return valueA.localeCompare(valueB);
        } else {
            return valueB.localeCompare(valueA);
        }
    });
    
    return filtered;
});

// Get styling class based on CG type
const getCGTypeClass = (type) => {
    const classes = {
        'X-Flex': 'bg-blue-100 text-blue-800',
        'C-Coating': 'bg-green-100 text-green-800',
        'S-Offset': 'bg-purple-100 text-purple-800',
        'D-Digital': 'bg-red-100 text-red-800',
        'P-Pantone': 'bg-yellow-100 text-yellow-800'
    };
    
    return classes[type] || 'bg-gray-100 text-gray-800';
};

// Print function
const printTable = () => {
    const printContent = document.getElementById('printableTable');
    const newWin = window.open('', '_blank');

    newWin.document.write('<html><head><title>Print Color Groups</title>');
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
    fetchColorGroups();
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
