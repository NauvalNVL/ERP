<template>
    <AppLayout :header="'View & Print Paper Qualities'">
    <Head title="View & Print Paper Qualities" />

    <!-- Header Section -->
    <div class="bg-gradient-to-r from-cyan-700 to-blue-600 p-6 rounded-t-lg shadow-lg">
        <h2 class="text-2xl font-bold text-white mb-2 flex items-center">
            <i class="fas fa-print mr-3"></i> View & Print Paper Qualities
        </h2>
        <p class="text-cyan-100">Preview and print paper quality data</p>
    </div>

    <div class="bg-white rounded-b-lg shadow-lg p-6 mb-6">
        <!-- Actions Bar -->
        <div class="flex flex-wrap items-center justify-between mb-6">
            <div class="flex items-center space-x-2 mb-3 sm:mb-0">
                <button @click="printTable" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded flex items-center space-x-2">
                    <i class="fas fa-print mr-2"></i> Print List
                </button>
                <Link href="/paper-quality" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded flex items-center space-x-2">
                    <i class="fas fa-arrow-left mr-2"></i> Back to Paper Qualities
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
                    placeholder="Search paper qualities..."
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
                            <i class="fas fa-file-alt text-3xl"></i>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold">PAPER QUALITY LIST</h2>
                            <p class="text-sm opacity-80">View and print paper quality data</p>
                        </div>
                    </div>
                </div>

                <!-- Table Content -->
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th @click="sortTable('id')" class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                                ID <i class="fas fa-sort ml-1"></i>
                            </th>
                            <th @click="sortTable('paper_quality')" class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                                Quality Code <i class="fas fa-sort ml-1"></i>
                            </th>
                            <th @click="sortTable('paper_name')" class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                                Paper Name <i class="fas fa-sort ml-1"></i>
                            </th>
                            <th @click="sortTable('weight_kg_m')" class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                                Weight (kg/m) <i class="fas fa-sort ml-1"></i>
                            </th>
                            <th @click="sortTable('commercial_code')" class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                                Comm. Code <i class="fas fa-sort ml-1"></i>
                            </th>
                            <th @click="sortTable('wet_end_code')" class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                                Wet-End Code <i class="fas fa-sort ml-1"></i>
                            </th>
                            <th @click="sortTable('decc_code')" class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                                DECC Code <i class="fas fa-sort ml-1"></i>
                            </th>
                            <th @click="sortTable('status')" class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                                Status <i class="fas fa-sort ml-1"></i>
                            </th>
                            <th @click="sortTable('flute')" class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                                Flute <i class="fas fa-sort ml-1"></i>
                            </th>
                            <th @click="sortTable('db')" class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                                DB <i class="fas fa-sort ml-1"></i>
                            </th>
                            <th @click="sortTable('b')" class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                                B <i class="fas fa-sort ml-1"></i>
                            </th>
                            <th @click="sortTable('il')" class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                                IL <i class="fas fa-sort ml-1"></i>
                            </th>
                            <th @click="sortTable('a_c_e')" class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                                A/C/E <i class="fas fa-sort ml-1"></i>
                            </th>
                            <th @click="sortTable('2l')" class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                                2L <i class="fas fa-sort ml-1"></i>
                            </th>
                            <th @click="sortTable('is_active')" class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                                Active <i class="fas fa-sort ml-1"></i>
                            </th>
                            <th @click="sortTable('created_at')" class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                                Created At <i class="fas fa-sort ml-1"></i>
                            </th>
                            <th @click="sortTable('updated_at')" class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                                Updated At <i class="fas fa-sort ml-1"></i>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr v-if="loading" class="hover:bg-gray-50">
                            <td colspan="17" class="px-2 py-4 text-center text-gray-500">
                                <div class="flex justify-center">
                                    <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-blue-500"></div>
                                </div>
                                <p class="mt-2">Loading paper quality data...</p>
                            </td>
                        </tr>
                        <tr v-else-if="filteredPaperQualities.length === 0" class="hover:bg-gray-50">
                            <td colspan="17" class="px-2 py-4 text-center text-gray-500">
                                No paper qualities found. 
                                <template v-if="searchQuery">
                                    <p class="mt-2">No results match your search query: "{{ searchQuery }}"</p>
                                    <button @click="searchQuery = ''" class="mt-2 text-blue-500 hover:underline">Clear search</button>
                                </template>
                            </td>
                        </tr>
                        <tr v-for="(quality, index) in filteredPaperQualities" :key="quality.id" 
                            :class="{'bg-blue-50': index % 2 === 0}" 
                            class="hover:bg-blue-100">
                            <td class="px-2 py-4 whitespace-nowrap text-sm">{{ quality.id }}</td>
                            <td class="px-2 py-4 whitespace-nowrap text-sm">{{ quality.paper_quality || 'N/A' }}</td>
                            <td class="px-2 py-4 whitespace-nowrap text-sm">{{ quality.paper_name || 'N/A' }}</td>
                            <td class="px-2 py-4 whitespace-nowrap text-sm text-right">{{ formatNumber(quality.weight_kg_m, 4) }}</td>
                            <td class="px-2 py-4 whitespace-nowrap text-sm">{{ quality.commercial_code || 'N/A' }}</td>
                            <td class="px-2 py-4 whitespace-nowrap text-sm">{{ quality.wet_end_code || 'N/A' }}</td>
                            <td class="px-2 py-4 whitespace-nowrap text-sm">{{ quality.decc_code || 'N/A' }}</td>
                            <td class="px-2 py-4 whitespace-nowrap text-sm">{{ quality.status || 'N/A' }}</td>
                            <td class="px-2 py-4 whitespace-nowrap text-sm">{{ quality.flute || 'N/A' }}</td>
                            <td class="px-2 py-4 whitespace-nowrap text-sm">{{ quality.db || 'N/A' }}</td>
                            <td class="px-2 py-4 whitespace-nowrap text-sm">{{ quality.b || 'N/A' }}</td>
                            <td class="px-2 py-4 whitespace-nowrap text-sm">{{ quality.il || 'N/A' }}</td>
                            <td class="px-2 py-4 whitespace-nowrap text-sm">{{ quality.a_c_e || 'N/A' }}</td>
                            <td class="px-2 py-4 whitespace-nowrap text-sm">{{ quality['2l'] || 'N/A' }}</td>
                            <td class="px-2 py-4 whitespace-nowrap text-sm">
                                <span :class="quality.is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'" class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full">
                                    {{ quality.is_active ? 'Yes' : 'No' }}
                                </span>
                            </td>
                            <td class="px-2 py-4 whitespace-nowrap text-sm">{{ formatDate(quality.created_at) }}</td>
                            <td class="px-2 py-4 whitespace-nowrap text-sm">{{ formatDate(quality.updated_at) }}</td>
                        </tr>
                    </tbody>
                </table>

                <!-- Table Footer -->
                <div class="bg-gray-50 px-6 py-3 border-t border-gray-200 text-sm text-gray-500">
                    <div class="flex items-center justify-between">
                        <div>Total Paper Qualities: {{ filteredPaperQualities.length }}</div>
                        <div v-if="searchQuery">Filtered from {{ paperQualities.length }} total records</div>
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
                <li>Click the "Print List" button above to print this paper quality list</li>
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
const paperQualities = ref([]);
const loading = ref(true);
const searchQuery = ref('');
const sortColumn = ref('paper_quality');
const sortDirection = ref('asc');
const currentDate = new Date().toLocaleString();

// Fetch paper qualities from API
const fetchPaperQualities = async () => {
    loading.value = true;
    try {
        const response = await fetch('/api/paper-qualities', {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        
        if (!response.ok) {
            throw new Error('Failed to fetch paper qualities');
        }
        
        const data = await response.json();
        paperQualities.value = data;
    } catch (error) {
        console.error('Error fetching paper qualities:', error);
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

// Format number
const formatNumber = (value, decimals = 2) => {
    if (value === null || value === undefined) return '0.' + '0'.repeat(decimals);
    return Number(value).toFixed(decimals);
};

// Format date
const formatDate = (dateString) => {
    if (!dateString) return 'N/A';
    return new Date(dateString).toLocaleString();
};

// Filtered and sorted paper qualities
const filteredPaperQualities = computed(() => {
    let filtered = [...paperQualities.value];
    
    // Apply search filter
    if (searchQuery.value) {
        const query = searchQuery.value.toLowerCase();
        filtered = filtered.filter(quality => 
            (quality.id && quality.id.toString().includes(query)) ||
            (quality.paper_quality && quality.paper_quality.toLowerCase().includes(query)) ||
            (quality.paper_name && quality.paper_name.toLowerCase().includes(query)) ||
            (quality.commercial_code && quality.commercial_code.toLowerCase().includes(query)) ||
            (quality.status && quality.status.toLowerCase().includes(query))
        );
    }
    
    // Apply sorting
    filtered.sort((a, b) => {
        let valueA = a[sortColumn.value];
        let valueB = b[sortColumn.value];
        
        // Handle null values
        if (valueA === null || valueA === undefined) valueA = '';
        if (valueB === null || valueB === undefined) valueB = '';
        
        // Handle numeric columns
        if (['id', 'weight_kg_m'].includes(sortColumn.value)) {
            valueA = parseFloat(valueA) || 0;
            valueB = parseFloat(valueB) || 0;
            
            if (sortDirection.value === 'asc') {
                return valueA - valueB;
            } else {
                return valueB - valueA;
            }
        }
        
        // Handle boolean column
        if (sortColumn.value === 'is_active') {
            if (sortDirection.value === 'asc') {
                return valueA === valueB ? 0 : valueA ? -1 : 1;
            } else {
                return valueA === valueB ? 0 : valueA ? 1 : -1;
            }
        }
        
        // Handle date columns
        if (['created_at', 'updated_at'].includes(sortColumn.value)) {
            const dateA = valueA ? new Date(valueA).getTime() : 0;
            const dateB = valueB ? new Date(valueB).getTime() : 0;
            
            if (sortDirection.value === 'asc') {
                return dateA - dateB;
            } else {
                return dateB - dateA;
            }
        }
        
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

    newWin.document.write('<html><head><title>Print Paper Qualities</title>');
    newWin.document.write('<style>');
    newWin.document.write('body { font-family: Arial, sans-serif; }');
    newWin.document.write('@page { size: landscape; }');
    newWin.document.write('table { width: 100%; border-collapse: collapse; table-layout: fixed; }');
    newWin.document.write('th, td { border: 1px solid #ddd; padding: 3px; text-align: left; font-size: 7pt; color: #000; word-wrap: break-word; }');
    newWin.document.write('th { background-color: #f2f2f2; font-weight: bold; }');
    newWin.document.write('tr:nth-child(even) { background-color: #f9f9f9; }');
    newWin.document.write('.text-right { text-align: right; }');
    newWin.document.write('.header { background-color: #1e40af; color: white; padding: 10px; display: flex; align-items: center; }');
    newWin.document.write('.header-text { margin-left: 15px; }');
    newWin.document.write('.footer { background-color: #f2f2f2; padding: 8px; border-top: 1px solid #ddd; display: flex; justify-content: space-between; }');
    newWin.document.write('</style></head><body>');
    newWin.document.write(printContent.outerHTML);
    newWin.document.write('<script>window.onload = function() { window.print(); window.close(); }<\/script>');
    newWin.document.write('</body></html>');
    
    newWin.document.close();
};

// Lifecycle hooks
onMounted(() => {
    fetchPaperQualities();
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

.text-right {
    text-align: right;
}
</style>
