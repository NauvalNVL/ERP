<template>
    <Head title="View & Print Business Forms" />
    
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-cyan-700 to-blue-600 p-6 rounded-t-lg shadow-lg">
        <h2 class="text-2xl font-bold text-white mb-2 flex items-center">
            <i class="fas fa-print mr-3"></i> View & Print Business Forms
        </h2>
        <p class="text-cyan-100">Preview and print business form data</p>
    </div>

    <div class="bg-white rounded-b-lg shadow-lg p-6 mb-6">
        <!-- Actions Bar -->
        <div class="flex flex-wrap items-center justify-between mb-6">
            <div class="flex items-center space-x-2 mb-3 sm:mb-0">
                <button @click="printTable" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded flex items-center space-x-2">
                    <i class="fas fa-print mr-2"></i> Print List
                </button>
                <Link href="/vue/business-form" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded flex items-center space-x-2">
                    <i class="fas fa-arrow-left mr-2"></i> Back to Business Forms
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
                    placeholder="Search business forms..."
                >
            </div>
        </div>

        <!-- Filter Bar -->
        <div class="flex flex-wrap gap-4 mb-6 p-4 bg-gray-50 rounded-lg border border-gray-200">
            <div>
                <label for="group_filter" class="block text-sm font-medium text-gray-700 mb-1">Filter by Group</label>
                <select 
                    id="group_filter" 
                    v-model="groupFilter" 
                    class="w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                >
                    <option value="">All Groups</option>
                    <option v-for="group in uniqueGroups" :key="group" :value="group">{{ group }}</option>
                </select>
            </div>
            <div>
                <label for="iso_filter" class="block text-sm font-medium text-gray-700 mb-1">Filter by ISO</label>
                <input 
                    id="iso_filter" 
                    v-model="isoFilter" 
                    type="text" 
                    class="w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                    placeholder="ISO reference..."
                >
            </div>
            <div class="flex items-end">
                <button 
                    @click="clearFilters" 
                    class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2"
                >
                    <i class="fas fa-times mr-2"></i> Clear Filters
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
                            <i class="fas fa-file-alt text-3xl"></i>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold">BUSINESS FORM LIST</h2>
                            <p class="text-sm opacity-80">View and print business form data</p>
                        </div>
                    </div>
                </div>

                <!-- Table Content -->
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th @click="sortTable('bf_code')" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                                Form Code <i :class="getSortIcon('bf_code')"></i>
                            </th>
                            <th @click="sortTable('bf_name')" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                                Form Name <i :class="getSortIcon('bf_name')"></i>
                            </th>
                            <th @click="sortTable('bf_group')" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                                Group <i :class="getSortIcon('bf_group')"></i>
                            </th>
                            <th @click="sortTable('bf_iso')" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                                ISO Reference <i :class="getSortIcon('bf_iso')"></i>
                            </th>
                            <th @click="sortTable('check_by_name')" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                                Checked By <i :class="getSortIcon('check_by_name')"></i>
                            </th>
                            <th @click="sortTable('approve_by_name')" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                                Approved By <i :class="getSortIcon('approve_by_name')"></i>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr v-if="loading" class="hover:bg-gray-50">
                            <td colspan="6" class="px-3 py-4 text-center text-gray-500">
                                <div class="flex justify-center">
                                    <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-blue-500"></div>
                                </div>
                                <p class="mt-2">Loading business form data...</p>
                            </td>
                        </tr>
                        <tr v-else-if="filteredForms.length === 0" class="hover:bg-gray-50">
                            <td colspan="6" class="px-3 py-4 text-center text-gray-500">
                                No business forms found. 
                                <template v-if="hasActiveFilters">
                                    <p class="mt-2">No results match your filter criteria.</p>
                                    <button @click="clearFilters" class="mt-2 text-blue-500 hover:underline">Clear filters</button>
                                </template>
                            </td>
                        </tr>
                        <tr v-for="(form, index) in filteredForms" :key="form.id" 
                            :class="{'bg-blue-50': index % 2 === 0}" 
                            class="hover:bg-blue-100">
                            <td class="px-3 py-4 whitespace-nowrap text-sm font-medium">{{ form.bf_code }}</td>
                            <td class="px-3 py-4 whitespace-nowrap text-sm">{{ form.bf_name }}</td>
                            <td class="px-3 py-4 whitespace-nowrap text-sm">{{ form.bf_group }}</td>
                            <td class="px-3 py-4 whitespace-nowrap text-sm">{{ form.bf_iso || 'N/A' }}</td>
                            <td class="px-3 py-4 whitespace-nowrap text-sm">{{ form.check_by_name || 'N/A' }}</td>
                            <td class="px-3 py-4 whitespace-nowrap text-sm">{{ form.approve_by_name || 'N/A' }}</td>
                        </tr>
                    </tbody>
                </table>

                <!-- Table Footer -->
                <div class="bg-gray-50 px-6 py-3 border-t border-gray-200 text-sm text-gray-500">
                    <div class="flex items-center justify-between">
                        <div>Total Forms: {{ filteredForms.length }}</div>
                        <div v-if="hasActiveFilters">Filtered from {{ businessForms.length }} total records</div>
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
                <li>Click the "Print List" button above to print this business form list</li>
                <li>Use landscape orientation for better results</li>
                <li>You can search or filter data before printing to narrow down results</li>
                <li>Only the table will be included in the print output</li>
            </ul>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { Head, Link } from '@inertiajs/vue3';

const businessForms = ref([]);
const loading = ref(true);
const searchQuery = ref('');
const groupFilter = ref('');
const isoFilter = ref('');
const sortColumn = ref('bf_code');
const sortDirection = ref('asc');
const currentDate = new Date().toLocaleString();

// Check if any filters are active
const hasActiveFilters = computed(() => {
    return searchQuery.value || groupFilter.value || isoFilter.value;
});

// Get unique groups for the filter dropdown
const uniqueGroups = computed(() => {
    const groups = new Set();
    businessForms.value.forEach(form => {
        if (form.bf_group) {
            groups.add(form.bf_group);
        }
    });
    return Array.from(groups).sort();
});

// Fetch business forms
const fetchBusinessForms = async () => {
    loading.value = true;
    try {
        const response = await fetch('/api/business-forms', {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        
        if (!response.ok) {
            throw new Error('Failed to fetch business forms');
        }
        
        const data = await response.json();
        businessForms.value = data;
    } catch (error) {
        console.error('Error fetching business forms:', error);
        businessForms.value = [];
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

// Clear all filters
const clearFilters = () => {
    searchQuery.value = '';
    groupFilter.value = '';
    isoFilter.value = '';
};

// Filtered and sorted forms
const filteredForms = computed(() => {
    let filtered = [...businessForms.value];
    
    // Apply search filter
    if (searchQuery.value) {
        const query = searchQuery.value.toLowerCase();
        filtered = filtered.filter(form => 
            (form.bf_code && form.bf_code.toLowerCase().includes(query)) ||
            (form.bf_name && form.bf_name.toLowerCase().includes(query)) ||
            (form.bf_group && form.bf_group.toLowerCase().includes(query)) ||
            (form.bf_iso && form.bf_iso.toLowerCase().includes(query)) ||
            (form.check_by_name && form.check_by_name.toLowerCase().includes(query)) ||
            (form.approve_by_name && form.approve_by_name.toLowerCase().includes(query))
        );
    }
    
    // Apply group filter
    if (groupFilter.value) {
        filtered = filtered.filter(form => form.bf_group === groupFilter.value);
    }
    
    // Apply ISO filter
    if (isoFilter.value) {
        const isoQuery = isoFilter.value.toLowerCase();
        filtered = filtered.filter(form => 
            form.bf_iso && form.bf_iso.toLowerCase().includes(isoQuery)
        );
    }
    
    // Apply sorting
    filtered.sort((a, b) => {
        let valueA = a[sortColumn.value];
        let valueB = b[sortColumn.value];
        
        // Handle null values
        if (valueA === null || valueA === undefined) valueA = '';
        if (valueB === null || valueB === undefined) valueB = '';
        
        // Convert to string for comparison
        valueA = String(valueA).toLowerCase();
        valueB = String(valueB).toLowerCase();
        
        // Apply sort direction
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

    newWin.document.write('<html><head><title>Print Business Forms</title>');
    newWin.document.write('<style>');
    newWin.document.write('body { font-family: Arial, sans-serif; }');
    newWin.document.write('@page { size: landscape; }');
    newWin.document.write('table { width: 100%; border-collapse: collapse; }');
    newWin.document.write('th, td { border: 1px solid #ddd; padding: 4px; text-align: left; font-size: 9pt; }');
    newWin.document.write('th { background-color: #f2f2f2; font-weight: bold; }');
    newWin.document.write('tr:nth-child(even) { background-color: #f9f9f9; }');
    newWin.document.write('.header { background-color: #1e40af; color: white; padding: 10px; display: flex; align-items: center; }');
    newWin.document.write('.header-text { margin-left: 15px; }');
    newWin.document.write('.footer { background-color: #f2f2f2; padding: 8px; border-top: 1px solid #ddd; display: flex; justify-content: space-between; }');
    newWin.document.write('</style></head><body>');
    newWin.document.write(printContent.outerHTML);
    newWin.document.write('<script>window.onload = function() { window.print(); window.close(); }<\/script>');
    newWin.document.write('</body></html>');
    
    newWin.document.close();
};

// Fetch data on component mount
onMounted(() => {
    fetchBusinessForms();
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