<template>
    <AppLayout :header="'View & Print Sales Teams'">
    <Head title="View & Print Sales Teams" />
    
        <!-- Header Section -->
    <div class="bg-gradient-to-r from-cyan-700 to-blue-600 p-6 rounded-t-lg shadow-lg">
        <h2 class="text-2xl font-bold text-white mb-2 flex items-center">
            <i class="fas fa-print mr-3"></i> View & Print Sales Teams
        </h2>
        <p class="text-cyan-100">Preview and print sales team data</p>
    </div>

    <div class="bg-white rounded-b-lg shadow-lg p-6 mb-6">
        <!-- Actions Bar -->
        <div class="flex flex-wrap items-center justify-between mb-6">
            <div class="flex items-center space-x-2 mb-3 sm:mb-0">
                <button @click="printTable" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded flex items-center space-x-2">
                    <i class="fas fa-print mr-2"></i> Print List
                </button>
                <button @click="refreshData" class="bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded flex items-center space-x-2" :disabled="loading">
                    <i class="fas fa-sync-alt mr-2" :class="{ 'animate-spin': loading }"></i> Refresh Data
                </button>
                <Link href="/sales-team" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded flex items-center space-x-2">
                    <i class="fas fa-arrow-left mr-2"></i> Back to Sales Teams
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
                    placeholder="Search sales teams..."
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
                            <i class="fas fa-users-cog text-3xl"></i>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold">SALES TEAM LIST</h2>
                            <p class="text-sm opacity-80">View and print sales team data</p>
                        </div>
            </div>
        </div>

                <!-- Table Content -->
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                    <tr>
                            <th @click="sortTable('id')" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                            ID <i :class="getSortIcon('id')"></i>
                        </th>
                            <th @click="sortTable('code')" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                            Code <i :class="getSortIcon('code')"></i>
                        </th>
                            <th @click="sortTable('name')" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                            Name <i :class="getSortIcon('name')"></i>
                        </th>
                            <th @click="sortTable('created_at')" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                            Created At <i :class="getSortIcon('created_at')"></i>
                        </th>
                            <th @click="sortTable('updated_at')" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                            Updated At <i :class="getSortIcon('updated_at')"></i>
                        </th>
                    </tr>
                </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr v-if="loading" class="hover:bg-gray-50">
                            <td colspan="5" class="px-3 py-4 text-center text-gray-500">
                                <div class="flex justify-center">
                                    <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-blue-500"></div>
                                </div>
                                <p class="mt-2">Loading sales team data...</p>
                            </td>
                    </tr>
                        <tr v-else-if="filteredSalesTeams.length === 0" class="hover:bg-gray-50">
                            <td colspan="5" class="px-3 py-4 text-center text-gray-500">
                            No sales teams found.
                                <template v-if="searchQuery">
                                    <p class="mt-2">No results match your search query: "{{ searchQuery }}"</p>
                                    <button @click="searchQuery = ''" class="mt-2 text-blue-500 hover:underline">Clear search</button>
                                </template>
                        </td>
                    </tr>
                        <tr v-for="(team, index) in filteredSalesTeams" :key="team.id" 
                            :class="{'bg-blue-50': index % 2 === 0}" 
                            class="hover:bg-blue-100">
                            <td class="px-3 py-4 whitespace-nowrap text-sm">{{ team.id || 'N/A' }}</td>
                            <td class="px-3 py-4 whitespace-nowrap text-sm font-medium">{{ team.code || 'N/A' }}</td>
                            <td class="px-3 py-4 whitespace-nowrap text-sm">{{ team.name || 'N/A' }}</td>
                            <td class="px-3 py-4 whitespace-nowrap text-sm">{{ formatDate(team.created_at) }}</td>
                            <td class="px-3 py-4 whitespace-nowrap text-sm">{{ formatDate(team.updated_at) }}</td>
                    </tr>
                </tbody>
            </table>

                <!-- Table Footer -->
                <div class="bg-gray-50 px-6 py-3 border-t border-gray-200 text-sm text-gray-500">
                    <div class="flex items-center justify-between">
                        <div>Total Sales Teams: {{ filteredSalesTeams.length }}</div>
                        <div v-if="searchQuery">Filtered from {{ salesTeams.length }} total records</div>
                        <div class="text-xs text-gray-400">Generated: {{ currentDate }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Debug Information Panel -->
        <div class="mt-6 bg-yellow-50 p-4 rounded-lg border border-yellow-200">
            <h3 class="font-semibold text-yellow-800 mb-2 flex items-center">
                <i class="fas fa-bug mr-2"></i> Debug Information
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                <div>
                    <strong>Data Source:</strong> /api/sales-teams<br>
                    <strong>Records Loaded:</strong> {{ salesTeams.length }}<br>
                    <strong>Loading Status:</strong> {{ loading ? 'Loading...' : 'Complete' }}
                </div>
                <div>
                    <strong>Last Fetch:</strong> {{ new Date().toLocaleTimeString() }}<br>
                    <strong>Filtered Records:</strong> {{ filteredSalesTeams.length }}<br>
                    <strong>Search Query:</strong> {{ searchQuery || 'None' }}
                </div>
                <div>
                    <strong>Sort Column:</strong> {{ sortColumn }}<br>
                    <strong>Sort Direction:</strong> {{ sortDirection }}<br>
                    <strong>Cache Busting:</strong> Enabled
                </div>
            </div>
            <div class="mt-2 text-xs text-yellow-700">
                If data doesn't match Define Sales Team, click "Refresh Data" button or check browser console for errors.
            </div>
        </div>

        <!-- Print Instructions -->
        <div class="mt-6 bg-blue-50 p-4 rounded-lg border border-blue-100">
            <h3 class="font-semibold text-blue-800 mb-2 flex items-center">
                <i class="fas fa-info-circle mr-2"></i> Print Instructions
            </h3>
            <ul class="list-disc pl-5 text-sm text-gray-600 space-y-1">
                <li>Click the "Print List" button above to print this sales team list</li>
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
const salesTeams = ref([]);
const loading = ref(true);
const searchQuery = ref('');
const sortColumn = ref('code');
const sortDirection = ref('asc');
const currentDate = new Date().toLocaleString();

// Fetch sales teams from the API
const fetchSalesTeams = async () => {
    loading.value = true;
    console.log('🔄 Fetching sales teams from /api/sales-teams...');
    
    try {
        const response = await fetch('/api/sales-teams?' + new URLSearchParams({
            '_t': Date.now(), // Cache busting
            '_refresh': 'true'
        }), {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'Cache-Control': 'no-cache, no-store, must-revalidate',
                'Pragma': 'no-cache',
                'Expires': '0'
            }
        });
        
        console.log('📡 API Response status:', response.status);
        console.log('📡 API Response headers:', Object.fromEntries(response.headers.entries()));
        
        if (!response.ok) {
            const errorText = await response.text();
            console.error('❌ API Error Response:', errorText);
            throw new Error(`HTTP ${response.status}: ${errorText}`);
        }
        
        const data = await response.json();
        console.log('📊 Raw API Response:', data);
        console.log('📊 Data type:', typeof data);
        console.log('📊 Is array:', Array.isArray(data));
        
        // Handle different API response formats
        if (data.data) {
            salesTeams.value = data.data;
            console.log('✅ Using data.data format, records:', data.data.length);
        } else if (Array.isArray(data)) {
            salesTeams.value = data;
            console.log('✅ Using direct array format, records:', data.length);
        } else {
            console.error('❌ Unexpected API response format:', data);
            salesTeams.value = [];
        }
        
        console.log('📋 Final salesTeams.value:', salesTeams.value);
        
    } catch (error) {
        console.error('❌ Error fetching sales teams:', error);
        console.error('❌ Error details:', {
            message: error.message,
            stack: error.stack
        });
        salesTeams.value = [];
    } finally {
        loading.value = false;
        console.log('✅ Loading finished, final count:', salesTeams.value.length);
    }
};

// Format date
const formatDate = (dateString) => {
    if (!dateString) return 'N/A';
    return new Date(dateString).toLocaleString();
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

// Filtered and sorted sales teams
const filteredSalesTeams = computed(() => {
    let filtered = [...salesTeams.value];
    
    // Apply search filter
    if (searchQuery.value) {
        const query = searchQuery.value.toLowerCase();
        filtered = filtered.filter(team => 
            (team.id && String(team.id).includes(query)) ||
            (team.code && team.code.toLowerCase().includes(query)) ||
            (team.name && team.name.toLowerCase().includes(query))
        );
    }
    
    // Apply sorting
    filtered.sort((a, b) => {
        let valueA = a[sortColumn.value];
        let valueB = b[sortColumn.value];
        
        // Handle null values
        if (valueA === null || valueA === undefined) valueA = '';
        if (valueB === null || valueB === undefined) valueB = '';
        
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

// Print function
const printTable = () => {
    const printContent = document.getElementById('printableTable');
    const newWin = window.open('', '_blank');

    newWin.document.write('<html><head><title>Print Sales Teams</title>');
    newWin.document.write('<style>');
    newWin.document.write('body { font-family: Arial, sans-serif; }');
    newWin.document.write('@page { size: landscape; }');
    newWin.document.write('table { width: 100%; border-collapse: collapse; }');
    newWin.document.write('th, td { border: 1px solid #ddd; padding: 4px; text-align: left; font-size: 10pt; }');
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

// Refresh data function
const refreshData = async () => {
    console.log('🔄 Manual refresh triggered...');
    await fetchSalesTeams();
};

// Fetch data on component mount
onMounted(() => {
    fetchSalesTeams();
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