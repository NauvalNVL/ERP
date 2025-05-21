<template>
    <Head title="View & Print Scoring Tools" />
    
    <!-- Main Content -->
    <div class="container mx-auto px-4 py-8">
        <!-- Header Section -->
        <div class="flex justify-between items-center mb-6 print:hidden">
            <h1 class="text-2xl font-semibold text-gray-700">View & Print Scoring Tools</h1>
            <div class="flex space-x-4">
                <div class="relative">
                    <input 
                        v-model="searchQuery" 
                        type="text" 
                        placeholder="Search scoring tools..." 
                        class="px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    />
                    <span class="absolute right-3 top-2 text-gray-400">
                        <i class="fas fa-search"></i>
                    </span>
                </div>
                <button 
                    @click="printTable" 
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition duration-150 ease-in-out flex items-center"
                >
                    <i class="fas fa-print mr-2"></i> Print
                </button>
            </div>
        </div>

        <!-- Loading State -->
        <div v-if="loading" class="bg-white p-8 rounded-lg shadow-md text-center">
            <div class="inline-flex items-center px-4 py-2 font-semibold leading-6 text-sm transition ease-in-out duration-150">
                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Loading scoring tools...
            </div>
        </div>

        <!-- Table -->
        <div v-else class="bg-white shadow-md rounded-lg overflow-x-auto">
            <table class="min-w-full leading-normal">
                <thead class="bg-gray-100">
                    <tr>
                        <th @click="sortTable('id')" class="px-5 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider cursor-pointer">
                            ID <i :class="getSortIcon('id')"></i>
                        </th>
                        <th @click="sortTable('code')" class="px-5 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider cursor-pointer">
                            Code <i :class="getSortIcon('code')"></i>
                        </th>
                        <th @click="sortTable('name')" class="px-5 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider cursor-pointer">
                            Name <i :class="getSortIcon('name')"></i>
                        </th>
                        <th @click="sortTable('scores')" class="px-5 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider cursor-pointer">
                            Scores <i :class="getSortIcon('scores')"></i>
                        </th>
                        <th @click="sortTable('gap')" class="px-5 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider cursor-pointer">
                            Gap <i :class="getSortIcon('gap')"></i>
                        </th>
                        <th @click="sortTable('created_at')" class="px-5 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider cursor-pointer">
                            Created At <i :class="getSortIcon('created_at')"></i>
                        </th>
                        <th @click="sortTable('updated_at')" class="px-5 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider cursor-pointer">
                            Updated At <i :class="getSortIcon('updated_at')"></i>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="tool in filteredScoringTools" :key="tool.id" class="hover:bg-gray-50">
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">{{ tool.id }}</td>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm font-medium">{{ tool.code }}</td>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">{{ tool.name }}</td>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">{{ tool.scores }}</td>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">{{ tool.gap }}</td>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">{{ formatDate(tool.created_at) }}</td>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">{{ formatDate(tool.updated_at) }}</td>
                    </tr>
                    <tr v-if="filteredScoringTools.length === 0">
                        <td colspan="7" class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-center text-gray-500">
                            No scoring tools found.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { Head } from '@inertiajs/vue3';

// Data
const scoringTools = ref([]);
const loading = ref(true);
const searchQuery = ref('');
const sortColumn = ref('code');
const sortDirection = ref('asc');

// Fetch scoring tools from the API
const fetchScoringTools = async () => {
    try {
        const response = await fetch('/api/scoring-tools');
        if (!response.ok) {
            throw new Error('Failed to fetch scoring tools');
        }
        
        const data = await response.json();
        
        // Handle different API response formats
        if (data.data) {
            scoringTools.value = data.data;
        } else if (Array.isArray(data)) {
            scoringTools.value = data;
        } else {
            console.error('Unexpected API response format:', data);
            scoringTools.value = [];
        }
        
        loading.value = false;
    } catch (error) {
        console.error('Error fetching scoring tools:', error);
        scoringTools.value = [];
        loading.value = false;
    }
};

// Format date
const formatDate = (dateString) => {
    if (!dateString) return 'N/A';
    
    const date = new Date(dateString);
    return date.toLocaleString('en-US', {
        year: 'numeric',
        month: '2-digit',
        day: '2-digit',
        hour: '2-digit',
        minute: '2-digit',
        second: '2-digit'
    });
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

// Filtered and sorted scoring tools
const filteredScoringTools = computed(() => {
    let filtered = [...scoringTools.value];
    
    // Apply search filter
    if (searchQuery.value) {
        const query = searchQuery.value.toLowerCase();
        filtered = filtered.filter(tool => 
            (tool.id && String(tool.id).includes(query)) ||
            (tool.code && tool.code.toLowerCase().includes(query)) ||
            (tool.name && tool.name.toLowerCase().includes(query)) ||
            (tool.scores && String(tool.scores).toLowerCase().includes(query)) ||
            (tool.gap && String(tool.gap).toLowerCase().includes(query))
        );
    }
    
    // Apply sorting
    filtered.sort((a, b) => {
        let valueA = a[sortColumn.value];
        let valueB = b[sortColumn.value];
        
        // Handle null values
        if (valueA === null || valueA === undefined) valueA = '';
        if (valueB === null || valueB === undefined) valueB = '';
        
        // Convert to strings for comparison (except numbers)
        if (typeof valueA !== 'number') {
            valueA = String(valueA).toLowerCase();
        }
        if (typeof valueB !== 'number') {
            valueB = String(valueB).toLowerCase();
        }
        
        // Compare and apply sort direction
        if (valueA < valueB) return sortDirection.value === 'asc' ? -1 : 1;
        if (valueA > valueB) return sortDirection.value === 'asc' ? 1 : -1;
        return 0;
    });
    
    return filtered;
});

// Print table
const printTable = () => {
    window.print();
};

// Fetch data on component mount
onMounted(() => {
    fetchScoringTools();
});
</script>

<style>
@media print {
    body * {
        visibility: hidden;
    }
    .container, .container * {
        visibility: visible;
    }
    .container {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        margin: 0;
        padding: 5px;
    }
    table {
        width: 100%;
        border-collapse: collapse;
    }
    th, td {
        border: 1px solid #ccc;
        padding: 4px;
        font-size: 10pt;
        color: #000;
    }
    thead {
        background-color: #eee !important;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
        display: table-header-group;
    }
    h1 {
        font-size: 12pt;
        margin-bottom: 0.5rem;
        text-align: center;
    }
    .print\:hidden {
        display: none !important;
    }
}
</style> 