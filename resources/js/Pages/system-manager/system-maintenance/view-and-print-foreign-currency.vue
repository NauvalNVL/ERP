<template>
    <AppLayout header="View & Print Foreign Currencies">
        <Head title="View & Print Foreign Currencies" />

        <div class="container mx-auto px-4 py-6">
            <!-- Header Section -->
            <div class="bg-gradient-to-r from-cyan-700 to-blue-600 p-6 rounded-t-lg shadow-lg">
                <h2 class="text-2xl font-bold text-white mb-2 flex items-center">
                    <i class="fas fa-money-bill-wave mr-3"></i> View & Print Foreign Currencies
                </h2>
                <p class="text-cyan-100">Review and print a comprehensive list of all foreign currencies</p>
            </div>

            <!-- Actions Bar -->
            <div class="bg-gray-100 border-b border-gray-300 p-4 flex flex-wrap justify-between items-center gap-4">
                <div class="flex items-center space-x-2">
                    <button 
                        @click="printCurrencies" 
                        class="bg-green-600 hover:bg-green-700 text-white py-2 px-4 rounded flex items-center transition-colors duration-150"
                        :disabled="loading"
                    >
                        <i class="fas fa-print mr-2"></i> Print Currency List
                    </button>
                    <Link 
                        href="/vue/foreign-currency" 
                        class="bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded flex items-center transition-colors duration-150"
                    >
                        <i class="fas fa-arrow-left mr-2"></i> Back to Currencies
                    </Link>
                </div>

                <!-- Search Box -->
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                    <input 
                        type="text" 
                        v-model="search" 
                        class="pl-10 pr-10 py-2 border border-gray-300 rounded-lg w-full md:w-72 focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Search currencies..."
                    >
                    <button 
                        v-if="search" 
                        @click="search = ''" 
                        class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-500 hover:text-gray-700"
                    >
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>

            <!-- Table Container -->
            <div class="bg-white rounded-b-lg shadow-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 table-fixed" id="currenciesTable">
                        <thead class="bg-gray-50">
                            <tr>
                                <th @click="sortBy('currency_code')" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer w-1/12">
                                    Code <i :class="getSortIcon('currency_code')"></i>
                                </th>
                                <th @click="sortBy('currency_name')" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer w-1/6">
                                    Currency Name <i :class="getSortIcon('currency_name')"></i>
                                </th>
                                <th @click="sortBy('country')" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer w-1/6">
                                    Country <i :class="getSortIcon('country')"></i>
                                </th>
                                <th @click="sortBy('exchange_rate')" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer w-1/8">
                                    Exchange Rate <i :class="getSortIcon('exchange_rate')"></i>
                                </th>
                                <th @click="sortBy('exchange_method')" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer w-1/8">
                                    Method <i :class="getSortIcon('exchange_method')"></i>
                                </th>
                                <th @click="sortBy('variance_control')" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer w-1/8">
                                    Variance Control <i :class="getSortIcon('variance_control')"></i>
                                </th>
                                <th @click="sortBy('max_tax_adj')" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer w-1/8">
                                    Max Tax Adj. <i :class="getSortIcon('max_tax_adj')"></i>
                                </th>
                                <th @click="sortBy('created_at')" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer w-1/6">
                                    Created At <i :class="getSortIcon('created_at')"></i>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-if="loading">
                                <td colspan="8" class="px-4 py-8 text-center">
                                    <div class="flex justify-center items-center">
                                        <div class="animate-spin rounded-full h-10 w-10 border-b-2 border-blue-500"></div>
                                        <span class="ml-4 text-gray-500">Loading currencies...</span>
                                    </div>
                                </td>
                            </tr>
                            <tr v-else-if="filteredCurrencies.length === 0">
                                <td colspan="8" class="px-4 py-8 text-center text-gray-500">
                                    <div class="flex flex-col items-center">
                                        <i class="fas fa-search text-gray-400 text-4xl mb-4"></i>
                                        <p class="text-lg mb-2">No currencies found</p>
                                        <p v-if="search" class="text-sm">
                                            No results matching "{{ search }}". 
                                            <button @click="search = ''" class="text-blue-500 hover:text-blue-700">
                                                Clear search
                                            </button>
                                        </p>
                                    </div>
                                </td>
                            </tr>
                            <tr v-for="(currency, index) in filteredCurrencies" :key="currency.id" 
                                :class="{'bg-blue-50': index % 2 === 0}"
                                class="hover:bg-blue-100 transition-colors duration-150">
                                <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ currency.currency_code }}</td>
                                <td class="px-4 py-3 text-sm text-gray-700">{{ currency.currency_name || 'N/A' }}</td>
                                <td class="px-4 py-3 text-sm text-gray-700">{{ currency.country || 'N/A' }}</td>
                                <td class="px-4 py-3 text-sm text-gray-700">{{ formatNumber(currency.exchange_rate) }}</td>
                                <td class="px-4 py-3 text-sm text-gray-700">
                                    {{ currency.exchange_method == 1 ? 'Method 1' : 'Method 2' }}
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-700">{{ formatNumber(currency.variance_control) }}%</td>
                                <td class="px-4 py-3 text-sm text-gray-700">{{ formatNumber(currency.max_tax_adj) }}</td>
                                <td class="px-4 py-3 text-sm text-gray-700">{{ formatDate(currency.created_at) }}</td>
                            </tr>
                        </tbody>
                        <tfoot class="bg-gray-50 border-t border-gray-200">
                            <tr>
                                <td colspan="8" class="px-4 py-3">
                                    <div class="flex justify-between items-center">
                                        <div class="text-sm text-gray-700">
                                            Showing <span class="font-semibold">{{ filteredCurrencies.length }}</span> currencies
                                            <span v-if="search">(filtered from {{ currencies.length }} total)</span>
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            Report generated at {{ currentDateTime }}
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <!-- Print Instructions -->
            <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4 print:hidden">
                <h3 class="text-lg font-semibold text-blue-800 mb-2 flex items-center">
                    <i class="fas fa-info-circle mr-2"></i> Print Instructions
                </h3>
                <ul class="list-disc list-inside text-sm text-blue-700 ml-4 space-y-1">
                    <li>Click the "Print Currency List" button to generate a PDF of the currency list.</li>
                    <li>You can use the search box to filter results before printing.</li>
                    <li>The generated PDF will respect the current sorting of the table.</li>
                </ul>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref, computed, onMounted, onBeforeUnmount } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import jsPDF from 'jspdf';
import autoTable from 'jspdf-autotable';

// Data
const currencies = ref([]);
const loading = ref(true);
const search = ref('');
const sortField = ref('currency_code');
const sortDirection = ref('asc');
const currentDateTime = ref(new Date().toLocaleString());

// Format number
const formatNumber = (value) => {
    if (value === null || value === undefined) return 'N/A';
    return Number(value).toLocaleString(undefined, {
        minimumFractionDigits: 2,
        maximumFractionDigits: 6
    });
};

// Format date
const formatDate = (dateString) => {
    if (!dateString) return 'N/A';
    return new Date(dateString).toLocaleString();
};

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

// Filtered and sorted currencies
const filteredCurrencies = computed(() => {
    let filtered = [...currencies.value];
    
    // Apply search filter
    if (search.value) {
        const query = search.value.toLowerCase();
        filtered = filtered.filter(currency => 
            (currency.currency_code && currency.currency_code.toLowerCase().includes(query)) ||
            (currency.currency_name && currency.currency_name.toLowerCase().includes(query)) ||
            (currency.country && currency.country.toLowerCase().includes(query))
        );
    }
    
    // Apply sorting
    filtered.sort((a, b) => {
        let valueA = a[sortField.value];
        let valueB = b[sortField.value];
        
        // Handle numeric fields
        if (['exchange_rate', 'variance_control', 'max_tax_adj'].includes(sortField.value)) {
            valueA = parseFloat(valueA) || 0;
            valueB = parseFloat(valueB) || 0;
            
            if (sortDirection.value === 'asc') {
                return valueA - valueB;
            } else {
                return valueB - valueA;
            }
        }
        
        // Handle date fields
        if (['created_at', 'updated_at'].includes(sortField.value)) {
            valueA = valueA ? new Date(valueA).getTime() : 0;
            valueB = valueB ? new Date(valueB).getTime() : 0;
            
            if (sortDirection.value === 'asc') {
                return valueA - valueB;
            } else {
                return valueB - valueA;
            }
        }
        
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

// Fetch currencies
const fetchCurrencies = async () => {
    loading.value = true;
    try {
        const response = await fetch('/api/foreign-currencies', {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        
        if (!response.ok) {
            throw new Error('Failed to fetch currencies');
        }
        
        const data = await response.json();
        currencies.value = data;
    } catch (error) {
        console.error('Error fetching currencies:', error);
    } finally {
        loading.value = false;
    }
};

// Print function using jsPDF
const printCurrencies = () => {
    const doc = new jsPDF({
        orientation: 'landscape',
        unit: 'px',
        format: 'a4'
    });

    doc.setFontSize(18);
    doc.text("Foreign Currencies Report", doc.internal.pageSize.getWidth() / 2, 30, { align: 'center' });
    doc.setFontSize(10);
    doc.text(`Generated on: ${currentDateTime.value}`, doc.internal.pageSize.getWidth() - 40, 45, { align: 'right' });

    const head = [['Code', 'Currency Name', 'Country', 'Exchange Rate', 'Method', 'Variance Control', 'Max Tax Adj', 'Created At']];
    const body = filteredCurrencies.value.map(c => [
        c.currency_code,
        c.currency_name,
        c.country,
        formatNumber(c.exchange_rate),
        c.exchange_method == 1 ? 'Multiply' : 'Divide',
        `${formatNumber(c.variance_control)}%`,
        formatNumber(c.max_tax_adj),
        formatDate(c.created_at)
    ]);

    autoTable(doc, {
        head: head,
        body: body,
        startY: 55,
        theme: 'grid',
        headStyles: {
            fillColor: [23, 79, 153], // Dark blue
            textColor: 255,
            fontSize: 8,
        },
        styles: {
            fontSize: 8,
            cellPadding: 2,
        },
    });

    doc.save('foreign-currencies.pdf');
};

onMounted(() => {
    fetchCurrencies();
    
    // Update time every minute
    const interval = setInterval(() => {
        currentDateTime.value = new Date().toLocaleString();
    }, 60000);
    
    // Clean up on unmount
    onBeforeUnmount(() => {
        clearInterval(interval);
    });
});
</script>

<style>
/* No longer needed with jsPDF */
</style> 