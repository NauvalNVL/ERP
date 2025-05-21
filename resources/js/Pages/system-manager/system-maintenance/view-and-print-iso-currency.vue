<template>
    <Head title="ISO Currency - View & Print" />
    <div class="container mx-auto px-4 py-6">
        <!-- Header -->
        <div class="bg-gradient-to-r from-blue-600 to-blue-800 rounded-lg shadow-lg p-6 mb-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <i class="fas fa-print text-white text-3xl mr-4"></i>
                    <div>
                        <h1 class="text-2xl font-bold text-white">ISO Currency Report</h1>
                        <p class="text-blue-100">View and print ISO currency information</p>
                    </div>
                </div>
                <div>
                    <button @click="printReport" class="bg-white text-blue-800 px-4 py-2 rounded-lg hover:bg-blue-50 flex items-center">
                        <i class="fas fa-print mr-2"></i>
                        Print Report
                    </button>
                </div>
            </div>
        </div>

        <!-- Actions Bar -->
        <div class="bg-white rounded-lg shadow-md p-4 mb-6 flex flex-wrap items-center justify-between">
            <!-- Search Input -->
            <div class="relative w-full md:w-auto mb-4 md:mb-0">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <i class="fas fa-search text-gray-400"></i>
                </div>
                <input
                    type="text"
                    v-model="search"
                    placeholder="Search currencies..."
                    class="pl-10 pr-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 w-full md:w-80"
                />
            </div>

            <!-- Filter Bar -->
            <div class="flex flex-wrap items-center space-x-2">
                <div class="mb-2 sm:mb-0">
                    <select 
                        v-model="filter.status" 
                        class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                    >
                        <option value="all">All Statuses</option>
                        <option value="active">Active Only</option>
                        <option value="inactive">Inactive Only</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Loading State -->
        <div v-if="loading" class="bg-white rounded-lg shadow-md p-8 flex justify-center">
            <div class="w-12 h-12 border-4 border-blue-500 border-t-transparent rounded-full animate-spin"></div>
        </div>

        <!-- Table View -->
        <div v-else class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200" id="printable-table">
                    <thead class="bg-gray-50">
                        <tr>
                            <th @click="sortBy('iso_code')" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                                ISO Code
                                <i :class="getSortIcon('iso_code')"></i>
                            </th>
                            <th @click="sortBy('currency_name')" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                                Currency Name
                                <i :class="getSortIcon('currency_name')"></i>
                            </th>
                            <th @click="sortBy('country')" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                                Country
                                <i :class="getSortIcon('country')"></i>
                            </th>
                            <th @click="sortBy('numeric_code')" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                                Numeric Code
                                <i :class="getSortIcon('numeric_code')"></i>
                            </th>
                            <th @click="sortBy('minor_unit')" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                                Minor Unit
                                <i :class="getSortIcon('minor_unit')"></i>
                            </th>
                            <th @click="sortBy('symbol')" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                                Symbol
                                <i :class="getSortIcon('symbol')"></i>
                            </th>
                            <th @click="sortBy('is_active')" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                                Status
                                <i :class="getSortIcon('is_active')"></i>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr 
                            v-for="(currency, index) in paginatedCurrencies" 
                            :key="currency.id" 
                            :class="{'bg-gray-50': index % 2 === 0}"
                        >
                            <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900">{{ currency.iso_code }}</td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-700">{{ currency.currency_name }}</td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-700">{{ currency.country }}</td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-700">{{ currency.numeric_code }}</td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-700">{{ currency.minor_unit }}</td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-700">{{ currency.symbol || '-' }}</td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm">
                                <span 
                                    :class="[
                                        'px-2 inline-flex text-xs leading-5 font-semibold rounded-full',
                                        currency.is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'
                                    ]"
                                >
                                    {{ currency.is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                        </tr>
                        <tr v-if="paginatedCurrencies.length === 0">
                            <td colspan="7" class="px-4 py-8 text-center text-gray-500">
                                No ISO currencies found matching your criteria
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="bg-gray-50 px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
                <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                    <div>
                        <p class="text-sm text-gray-700">
                            Showing <span class="font-medium">{{ startIndex + 1 }}</span> to <span class="font-medium">{{ Math.min(endIndex, filteredCurrencies.length) }}</span> of
                            <span class="font-medium">{{ filteredCurrencies.length }}</span> results
                        </p>
                    </div>
                    <div>
                        <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                            <button
                                @click="currentPage = Math.max(1, currentPage - 1)"
                                :disabled="currentPage === 1"
                                class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50"
                                :class="{ 'opacity-50 cursor-not-allowed': currentPage === 1 }"
                            >
                                <i class="fas fa-chevron-left text-xs"></i>
                            </button>
                            
                            <button
                                v-for="page in visiblePages"
                                :key="page"
                                @click="currentPage = page"
                                :class="[
                                    'relative inline-flex items-center px-4 py-2 border text-sm font-medium',
                                    currentPage === page
                                        ? 'z-10 bg-blue-50 border-blue-500 text-blue-600'
                                        : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50'
                                ]"
                            >
                                {{ page }}
                            </button>
                            
                            <button
                                @click="currentPage = Math.min(totalPages, currentPage + 1)"
                                :disabled="currentPage === totalPages"
                                class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50"
                                :class="{ 'opacity-50 cursor-not-allowed': currentPage === totalPages }"
                            >
                                <i class="fas fa-chevron-right text-xs"></i>
                            </button>
                        </nav>
                    </div>
                </div>
            </div>

            <!-- Print Footer (hidden in normal view) -->
            <div id="print-footer" class="hidden">
                <div class="flex justify-between text-sm text-gray-500 mt-4 pt-2 border-t">
                    <div>Report generated on: {{ new Date().toLocaleString() }}</div>
                    <div>Page 1 of 1</div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import axios from 'axios';
import { Head } from '@inertiajs/vue3';

// State
const currencies = ref([]);
const loading = ref(true);
const search = ref('');
const currentPage = ref(1);
const itemsPerPage = ref(15);
const sortField = ref('iso_code');
const sortDirection = ref('asc');
const filter = ref({
    status: 'all'
});

// Fetch all currencies
const fetchCurrencies = async () => {
    loading.value = true;
    try {
        const response = await axios.get('/api/iso-currencies');
        currencies.value = response.data;
    } catch (error) {
        console.error('Error fetching ISO currencies:', error);
    } finally {
        loading.value = false;
    }
};

// Filter currencies based on search and filters
const filteredCurrencies = computed(() => {
    let filtered = [...currencies.value];
    
    // Apply status filter
    if (filter.value.status !== 'all') {
        const isActive = filter.value.status === 'active';
        filtered = filtered.filter(currency => currency.is_active === isActive);
    }
    
    // Apply search filter
    if (search.value) {
        const searchTerm = search.value.toLowerCase();
        filtered = filtered.filter(currency => 
            (currency.iso_code && currency.iso_code.toLowerCase().includes(searchTerm)) ||
            (currency.currency_name && currency.currency_name.toLowerCase().includes(searchTerm)) ||
            (currency.country && currency.country.toLowerCase().includes(searchTerm)) ||
            (currency.numeric_code && currency.numeric_code.includes(searchTerm)) ||
            (currency.symbol && currency.symbol.toLowerCase().includes(searchTerm))
        );
    }
    
    // Apply sorting
    filtered.sort((a, b) => {
        let valueA = a[sortField.value];
        let valueB = b[sortField.value];
        
        // Handle null or undefined values
        if (valueA === null || valueA === undefined) valueA = '';
        if (valueB === null || valueB === undefined) valueB = '';
        
        // For boolean values like is_active
        if (typeof valueA === 'boolean') {
            if (sortDirection.value === 'asc') {
                return valueA === valueB ? 0 : valueA ? -1 : 1;
            } else {
                return valueA === valueB ? 0 : valueA ? 1 : -1;
            }
        }
        
        // For strings and numbers
        if (sortDirection.value === 'asc') {
            return String(valueA).localeCompare(String(valueB));
        } else {
            return String(valueB).localeCompare(String(valueA));
        }
    });
    
    return filtered;
});

// Pagination
const totalPages = computed(() => {
    return Math.ceil(filteredCurrencies.value.length / itemsPerPage.value);
});

const startIndex = computed(() => {
    return (currentPage.value - 1) * itemsPerPage.value;
});

const endIndex = computed(() => {
    return startIndex.value + itemsPerPage.value;
});

const paginatedCurrencies = computed(() => {
    return filteredCurrencies.value.slice(startIndex.value, endIndex.value);
});

// Generate array of page numbers for pagination
const visiblePages = computed(() => {
    const totalVisible = 5;
    const pages = [];
    
    if (totalPages.value <= totalVisible) {
        // If we have fewer pages than we want to display, show all pages
        for (let i = 1; i <= totalPages.value; i++) {
            pages.push(i);
        }
    } else {
        // Calculate which pages to show
        const halfVisible = Math.floor(totalVisible / 2);
        
        let startPage = Math.max(1, currentPage.value - halfVisible);
        let endPage = Math.min(totalPages.value, startPage + totalVisible - 1);
        
        // Adjust if we're near the end
        if (endPage - startPage < totalVisible - 1) {
            startPage = Math.max(1, endPage - totalVisible + 1);
        }
        
        for (let i = startPage; i <= endPage; i++) {
            pages.push(i);
        }
    }
    
    return pages;
});

// Sort currencies
const sortBy = (field) => {
    if (sortField.value === field) {
        sortDirection.value = sortDirection.value === 'asc' ? 'desc' : 'asc';
    } else {
        sortField.value = field;
        sortDirection.value = 'asc';
    }
};

// Get sort icon
const getSortIcon = (field) => {
    if (sortField.value !== field) {
        return 'fas fa-sort text-gray-300';
    }
    
    return sortDirection.value === 'asc' 
        ? 'fas fa-sort-up text-blue-500' 
        : 'fas fa-sort-down text-blue-500';
};

// Print report
const printReport = () => {
    // Show the print dialog
    window.print();
};

// Fetch currencies on component mount
onMounted(() => {
    fetchCurrencies();
});
</script>

<style scoped>
@media print {
    @page {
        size: landscape;
    }
    
    body {
        padding: 20px;
        font-size: 12pt;
    }
    
    .container {
        max-width: 100%;
        padding: 0;
    }
    
    button, .actions-bar, .pagination {
        display: none !important;
    }
    
    #printable-table {
        width: 100%;
        border-collapse: collapse;
        border: 1px solid #e5e7eb;
    }
    
    #printable-table th, #printable-table td {
        border: 1px solid #e5e7eb;
        padding: 8px;
        text-align: left;
    }
    
    #printable-table th {
        background-color: #f9fafb;
    }
    
    #print-footer {
        display: block !important;
        margin-top: 20px;
        border-top: 1px solid #e5e7eb;
        padding-top: 10px;
        font-size: 10pt;
        color: #6b7280;
    }
}
</style> 