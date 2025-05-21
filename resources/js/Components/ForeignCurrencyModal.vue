<template>
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-4xl p-6 max-h-[90vh] flex flex-col">
            <!-- Modal Header -->
            <div class="flex justify-between items-center border-b pb-3 mb-4">
                <h3 class="text-xl font-semibold text-gray-900 flex items-center">
                    <i class="fas fa-money-bill-wave mr-2 text-blue-600"></i> 
                    Select Foreign Currency
                </h3>
                <button @click="$emit('close')" class="text-gray-400 hover:text-gray-500 focus:outline-none">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <!-- Search Bar -->
            <div class="mb-4">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                    <input 
                        type="text" 
                        v-model="search" 
                        class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Search by code, name, or country..."
                        @keyup.esc="$emit('close')"
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

            <!-- Loading State -->
            <div v-if="loading" class="flex-grow flex items-center justify-center p-8">
                <div class="flex flex-col items-center">
                    <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-500 mb-4"></div>
                    <p class="text-gray-600">Loading currencies...</p>
                </div>
            </div>

            <!-- Results Table -->
            <div v-else class="flex-grow overflow-y-auto">
                <div v-if="filteredCurrencies.length === 0" class="flex flex-col items-center justify-center p-8">
                    <i class="fas fa-search text-gray-400 text-4xl mb-4"></i>
                    <p class="text-gray-600 mb-2">No currencies found matching "{{ search }}"</p>
                    <button 
                        v-if="search" 
                        @click="search = ''" 
                        class="text-blue-500 hover:text-blue-700 font-medium"
                    >
                        Clear search
                    </button>
                </div>
                <table v-else class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50 sticky top-0 z-10">
                        <tr>
                            <th @click="sortBy('currency_code')" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                                Code <i :class="getSortIcon('currency_code')"></i>
                            </th>
                            <th @click="sortBy('currency_name')" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                                Currency Name <i :class="getSortIcon('currency_name')"></i>
                            </th>
                            <th @click="sortBy('country')" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                                Country <i :class="getSortIcon('country')"></i>
                            </th>
                            <th @click="sortBy('exchange_rate')" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                                Rate <i :class="getSortIcon('exchange_rate')"></i>
                            </th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Action
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr v-for="(currency, index) in paginatedCurrencies" :key="currency.id" 
                            :class="{ 'bg-blue-50': index % 2 === 0 }"
                            class="hover:bg-blue-100 transition-colors duration-150">
                            <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ currency.currency_code }}
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-700">
                                {{ currency.currency_name }}
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-700">
                                {{ currency.country }}
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-700">
                                {{ formatNumber(currency.exchange_rate) }}
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-center">
                                <button 
                                    @click="selectCurrency(currency)" 
                                    class="px-3 py-1 bg-blue-500 text-white rounded-md hover:bg-blue-600 text-sm"
                                >
                                    Select
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pagination Controls -->
            <div v-if="!loading && filteredCurrencies.length > 0" class="pt-4 border-t border-gray-200 mt-4">
                <div class="flex items-center justify-between">
                    <div class="text-sm text-gray-600">
                        Showing {{ startIndex + 1 }}-{{ Math.min(endIndex, filteredCurrencies.length) }} of {{ filteredCurrencies.length }} currencies
                    </div>
                    <div class="flex space-x-2">
                        <button 
                            @click="prevPage" 
                            :disabled="currentPage === 1"
                            :class="[
                                'px-3 py-1 rounded text-sm', 
                                currentPage === 1 ? 'bg-gray-200 text-gray-500 cursor-not-allowed' : 'bg-blue-500 text-white hover:bg-blue-600'
                            ]"
                        >
                            <i class="fas fa-chevron-left mr-1"></i> Previous
                        </button>
                        <button 
                            @click="nextPage" 
                            :disabled="currentPage >= totalPages"
                            :class="[
                                'px-3 py-1 rounded text-sm', 
                                currentPage >= totalPages ? 'bg-gray-200 text-gray-500 cursor-not-allowed' : 'bg-blue-500 text-white hover:bg-blue-600'
                            ]"
                        >
                            Next <i class="fas fa-chevron-right ml-1"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted, onBeforeUnmount } from 'vue';

// Props and emits
defineEmits(['close', 'select']);

// States
const currencies = ref([]);
const loading = ref(true);
const search = ref('');
const sortField = ref('currency_code');
const sortDirection = ref('asc');
const currentPage = ref(1);
const itemsPerPage = ref(10);

// Format number helper
const formatNumber = (value) => {
    if (value === null || value === undefined) return 'N/A';
    return Number(value).toLocaleString(undefined, {
        minimumFractionDigits: 2,
        maximumFractionDigits: 6
    });
};

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

// Sort currencies
const sortBy = (field) => {
    if (sortField.value === field) {
        sortDirection.value = sortDirection.value === 'asc' ? 'desc' : 'asc';
    } else {
        sortField.value = field;
        sortDirection.value = 'asc';
    }
    // Reset to first page when sorting changes
    currentPage.value = 1;
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

// Pagination computeds
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

// Pagination methods
const nextPage = () => {
    if (currentPage.value < totalPages.value) {
        currentPage.value++;
    }
};

const prevPage = () => {
    if (currentPage.value > 1) {
        currentPage.value--;
    }
};

// Emit selected currency
const selectCurrency = (currency) => {
    $emit('select', currency);
};

// Watch for search changes to reset pagination
const watchSearch = (newValue) => {
    currentPage.value = 1; // Reset to first page on search
};

// Setup key listener for escape key
const handleKeyDown = (e) => {
    if (e.key === 'Escape') {
        $emit('close');
    }
};

// Lifecycle hooks
onMounted(() => {
    fetchCurrencies();
    window.addEventListener('keydown', handleKeyDown);
});

onBeforeUnmount(() => {
    window.removeEventListener('keydown', handleKeyDown);
});
</script>

<style scoped>
.modal-enter-active,
.modal-leave-active {
    transition: opacity 0.3s ease;
}

.modal-enter-from,
.modal-leave-to {
    opacity: 0;
}
</style> 