<template>
    <Head title="View & Print Salespersons" />
    
    <!-- Main Content -->
    <div class="container mx-auto px-4 py-8">
        <!-- Header Section -->
        <div class="flex justify-between items-center mb-6 print:hidden">
            <h1 class="text-2xl font-semibold text-gray-700">View & Print Salespersons</h1>
            <div class="flex space-x-4">
                <div class="relative">
                    <input 
                        v-model="searchQuery" 
                        type="text" 
                        placeholder="Search salespersons..." 
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
                Loading salespersons...
            </div>
        </div>

        <!-- Table -->
        <div v-else class="bg-white shadow-md rounded-lg overflow-x-auto">
            <table class="min-w-full leading-normal">
                <thead class="bg-gray-100">
                    <tr>
                        <th @click="sortTable('id')" class="px-3 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider cursor-pointer">
                            ID <i :class="getSortIcon('id')"></i>
                        </th>
                        <th @click="sortTable('code')" class="px-3 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider cursor-pointer">
                            Code <i :class="getSortIcon('code')"></i>
                        </th>
                        <th @click="sortTable('name')" class="px-3 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider cursor-pointer">
                            Name <i :class="getSortIcon('name')"></i>
                        </th>
                        <th @click="sortTable('sales_team_id')" class="px-3 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider cursor-pointer">
                            Sales Team <i :class="getSortIcon('sales_team_id')"></i>
                        </th>
                        <th @click="sortTable('position')" class="px-3 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider cursor-pointer">
                            Position <i :class="getSortIcon('position')"></i>
                        </th>
                        <th @click="sortTable('user_id')" class="px-3 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider cursor-pointer">
                            User ID <i :class="getSortIcon('user_id')"></i>
                        </th>
                        <th @click="sortTable('is_active')" class="px-3 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider cursor-pointer">
                            Active <i :class="getSortIcon('is_active')"></i>
                        </th>
                        <th @click="sortTable('created_at')" class="px-3 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider cursor-pointer">
                            Created At <i :class="getSortIcon('created_at')"></i>
                        </th>
                        <th @click="sortTable('updated_at')" class="px-3 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider cursor-pointer">
                            Updated At <i :class="getSortIcon('updated_at')"></i>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="person in filteredSalespersons" :key="person.id" class="hover:bg-gray-50">
                        <td class="px-3 py-4 border-b border-gray-200 bg-white text-sm">{{ person.id }}</td>
                        <td class="px-3 py-4 border-b border-gray-200 bg-white text-sm">{{ person.code || 'N/A' }}</td>
                        <td class="px-3 py-4 border-b border-gray-200 bg-white text-sm">{{ person.name || 'N/A' }}</td>
                        <td class="px-3 py-4 border-b border-gray-200 bg-white text-sm">{{ getSalesTeamName(person) }}</td>
                        <td class="px-3 py-4 border-b border-gray-200 bg-white text-sm">{{ person.position || 'N/A' }}</td>
                        <td class="px-3 py-4 border-b border-gray-200 bg-white text-sm">{{ person.user_id || 'N/A' }}</td>
                        <td class="px-3 py-4 border-b border-gray-200 bg-white text-sm">
                            <span 
                                :class="`px-2 py-1 rounded-full text-xs font-medium ${person.is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}`"
                            >
                                {{ person.is_active ? 'Yes' : 'No' }}
                            </span>
                        </td>
                        <td class="px-3 py-4 border-b border-gray-200 bg-white text-sm">{{ formatDate(person.created_at) }}</td>
                        <td class="px-3 py-4 border-b border-gray-200 bg-white text-sm">{{ formatDate(person.updated_at) }}</td>
                    </tr>
                    <tr v-if="filteredSalespersons.length === 0">
                        <td colspan="9" class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-center text-gray-500">
                            No salespersons found.
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
const salespersons = ref([]);
const salesTeams = ref({});
const loading = ref(true);
const searchQuery = ref('');
const sortColumn = ref('name');
const sortDirection = ref('asc');

// Fetch salespersons from the API
const fetchSalespersons = async () => {
    try {
        const response = await fetch('/api/salespersons');
        if (!response.ok) {
            throw new Error('Failed to fetch salespersons');
        }
        
        const data = await response.json();
        
        // Handle different API response formats
        if (data.data) {
            salespersons.value = data.data;
        } else if (Array.isArray(data)) {
            salespersons.value = data;
        } else {
            console.error('Unexpected API response format:', data);
            salespersons.value = [];
        }
        
        // Fetch sales teams for name lookup
        await fetchSalesTeams();
        
        loading.value = false;
    } catch (error) {
        console.error('Error fetching salespersons:', error);
        salespersons.value = [];
        loading.value = false;
    }
};

// Fetch sales teams for lookups
const fetchSalesTeams = async () => {
    try {
        const response = await fetch('/api/sales-teams');
        if (!response.ok) {
            throw new Error('Failed to fetch sales teams');
        }
        
        const data = await response.json();
        
        // Create a lookup object for sales team names
        const teamsMap = {};
        if (Array.isArray(data)) {
            data.forEach(team => {
                teamsMap[team.id] = team.name;
            });
        } else if (data.data && Array.isArray(data.data)) {
            data.data.forEach(team => {
                teamsMap[team.id] = team.name;
            });
        }
        
        salesTeams.value = teamsMap;
    } catch (error) {
        console.error('Error fetching sales teams:', error);
    }
};

// Helper to get sales team name
const getSalesTeamName = (person) => {
    if (person.sales_team && person.sales_team.name) {
        return person.sales_team.name;
    }
    
    if (salesTeams.value[person.sales_team_id]) {
        return salesTeams.value[person.sales_team_id];
    }
    
    return person.sales_team_id || 'N/A';
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

// Filtered and sorted salespersons
const filteredSalespersons = computed(() => {
    let filtered = [...salespersons.value];
    
    // Apply search filter
    if (searchQuery.value) {
        const query = searchQuery.value.toLowerCase();
        filtered = filtered.filter(person => 
            (person.id && String(person.id).includes(query)) ||
            (person.code && person.code.toLowerCase().includes(query)) ||
            (person.name && person.name.toLowerCase().includes(query)) ||
            (person.position && person.position.toLowerCase().includes(query)) ||
            (getSalesTeamName(person).toLowerCase().includes(query))
        );
    }
    
    // Apply sorting
    filtered.sort((a, b) => {
        let valueA, valueB;
        
        // Special handling for sales team column
        if (sortColumn.value === 'sales_team_id') {
            valueA = getSalesTeamName(a);
            valueB = getSalesTeamName(b);
        } else {
            valueA = a[sortColumn.value];
            valueB = b[sortColumn.value];
        }
        
        // Handle null values
        if (valueA === null || valueA === undefined) valueA = '';
        if (valueB === null || valueB === undefined) valueB = '';
        
        // Convert to strings for comparison (except numbers and booleans)
        if (typeof valueA !== 'number' && typeof valueA !== 'boolean') {
            valueA = String(valueA).toLowerCase();
        }
        if (typeof valueB !== 'number' && typeof valueB !== 'boolean') {
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
    fetchSalespersons();
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
        table-layout: fixed;
    }
    th, td {
        border: 1px solid #ccc;
        padding: 4px;
        font-size: 8pt;
        color: #000;
        word-wrap: break-word;
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