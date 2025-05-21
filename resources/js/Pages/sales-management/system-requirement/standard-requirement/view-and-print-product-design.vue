<template>
    <Head title="View & Print Product Designs" />
    
    <!-- Main Content -->
    <div class="container mx-auto px-4 py-8">
        <!-- Header Section -->
        <div class="flex justify-between items-center mb-6 print:hidden">
            <h1 class="text-2xl font-semibold text-gray-700">View & Print Product Designs</h1>
            <div class="flex space-x-4">
                <div class="relative">
                    <input 
                        v-model="searchQuery" 
                        type="text" 
                        placeholder="Search designs..." 
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
                Loading product designs...
            </div>
        </div>

        <!-- Table -->
        <div v-else class="bg-white shadow-md rounded-lg overflow-hidden">
            <table class="min-w-full leading-normal">
                <thead class="bg-gray-100">
                    <tr>
                        <th @click="sortTable('design_code')" class="px-5 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider cursor-pointer">
                            Design Code <i :class="getSortIcon('design_code')"></i>
                        </th>
                        <th @click="sortTable('design_name')" class="px-5 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider cursor-pointer">
                            Design Name <i :class="getSortIcon('design_name')"></i>
                        </th>
                        <th @click="sortTable('product_code')" class="px-5 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider cursor-pointer">
                            Product Code <i :class="getSortIcon('product_code')"></i>
                        </th>
                        <th @click="sortTable('description')" class="px-5 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider cursor-pointer">
                            Description <i :class="getSortIcon('description')"></i>
                        </th>
                        <th @click="sortTable('status')" class="px-5 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider cursor-pointer">
                            Status <i :class="getSortIcon('status')"></i>
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
                    <tr v-for="design in filteredProductDesigns" :key="design.design_code" class="hover:bg-gray-50">
                        <td class="px-5 py-4 border-b border-gray-200 bg-white text-sm">
                            {{ design.design_code || 'N/A' }}
                        </td>
                        <td class="px-5 py-4 border-b border-gray-200 bg-white text-sm">
                            {{ design.design_name || 'N/A' }}
                        </td>
                        <td class="px-5 py-4 border-b border-gray-200 bg-white text-sm">
                            {{ design.product_code || 'N/A' }}
                        </td>
                        <td class="px-5 py-4 border-b border-gray-200 bg-white text-sm">
                            {{ design.description || 'N/A' }}
                        </td>
                        <td class="px-5 py-4 border-b border-gray-200 bg-white text-sm">
                            <span 
                                :class="`px-2 py-1 rounded-full text-xs font-medium ${design.status ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}`"
                            >
                                {{ design.status ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="px-5 py-4 border-b border-gray-200 bg-white text-sm">
                            {{ formatDate(design.created_at) }}
                        </td>
                        <td class="px-5 py-4 border-b border-gray-200 bg-white text-sm">
                            {{ formatDate(design.updated_at) }}
                        </td>
                    </tr>
                    <tr v-if="filteredProductDesigns.length === 0">
                        <td colspan="7" class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-center text-gray-500">
                            No product designs found.
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
const productDesigns = ref([]);
const loading = ref(true);
const searchQuery = ref('');
const sortColumn = ref('design_code');
const sortDirection = ref('asc');

// Fetch product designs from the API
const fetchProductDesigns = async () => {
    try {
        const response = await fetch('/api/product-designs');
        if (!response.ok) {
            throw new Error('Failed to fetch product designs');
        }
        
        const data = await response.json();
        productDesigns.value = data;
        loading.value = false;
    } catch (error) {
        console.error('Error fetching product designs:', error);
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

// Filtered and sorted product designs
const filteredProductDesigns = computed(() => {
    let filtered = [...productDesigns.value];
    
    // Apply search filter
    if (searchQuery.value) {
        const query = searchQuery.value.toLowerCase();
        filtered = filtered.filter(design => 
            (design.design_code && design.design_code.toLowerCase().includes(query)) ||
            (design.design_name && design.design_name.toLowerCase().includes(query)) ||
            (design.product_code && design.product_code.toLowerCase().includes(query)) ||
            (design.description && design.description.toLowerCase().includes(query))
        );
    }
    
    // Apply sorting
    filtered.sort((a, b) => {
        let valueA = a[sortColumn.value];
        let valueB = b[sortColumn.value];
        
        // Handle null values
        if (valueA === null || valueA === undefined) valueA = '';
        if (valueB === null || valueB === undefined) valueB = '';
        
        // Convert to strings for comparison
        valueA = String(valueA).toLowerCase();
        valueB = String(valueB).toLowerCase();
        
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
    fetchProductDesigns();
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
        padding: 0;
    }
    table {
        width: 100%;
        border-collapse: collapse;
    }
    th, td {
        border: 1px solid #ddd;
        padding: 8px;
        font-size: 10pt;
        color: #000;
    }
    thead {
        background-color: #eee !important;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }
    h1 {
         font-size: 14pt;
         margin-bottom: 1rem;
    }
    .print\:hidden {
        display: none !important;
    }
}
</style> 