<template>
    <div class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto bg-black bg-opacity-50">
        <div class="relative w-full max-w-4xl p-6 mx-auto bg-white rounded-lg shadow-xl">
            <!-- Modal Header -->
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-xl font-bold text-gray-900">Select ISO Currency</h3>
                <button @click="$emit('close')" class="text-gray-500 hover:text-gray-700 focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Search Box -->
            <div class="mb-4">
                <div class="relative">
                    <input
                        type="text"
                        v-model="searchTerm"
                        placeholder="Search ISO currencies..."
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                        @input="searchCurrencies"
                    />
                    <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Loading State -->
            <div v-if="loading" class="flex justify-center py-8">
                <div class="w-12 h-12 border-4 border-blue-500 rounded-full border-t-transparent animate-spin"></div>
            </div>

            <!-- Results Table -->
            <div v-else class="overflow-x-auto">
                <table class="min-w-full bg-white border border-gray-200">
                    <thead class="bg-gray-100">
                        <tr>
                            <th @click="sortBy('iso_code')" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                                ISO Code
                                <span v-if="sortColumn === 'iso_code'" class="ml-1">{{ sortDirection === 'asc' ? '↑' : '↓' }}</span>
                            </th>
                            <th @click="sortBy('currency_name')" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                                Currency Name
                                <span v-if="sortColumn === 'currency_name'" class="ml-1">{{ sortDirection === 'asc' ? '↑' : '↓' }}</span>
                            </th>
                            <th @click="sortBy('country')" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                                Country
                                <span v-if="sortColumn === 'country'" class="ml-1">{{ sortDirection === 'asc' ? '↑' : '↓' }}</span>
                            </th>
                            <th @click="sortBy('numeric_code')" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                                Numeric Code
                                <span v-if="sortColumn === 'numeric_code'" class="ml-1">{{ sortDirection === 'asc' ? '↑' : '↓' }}</span>
                            </th>
                            <th @click="sortBy('symbol')" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                                Symbol
                                <span v-if="sortColumn === 'symbol'" class="ml-1">{{ sortDirection === 'asc' ? '↑' : '↓' }}</span>
                            </th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Action
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <tr v-for="currency in paginatedCurrencies" :key="currency.id" :class="{ 'bg-gray-50': currency.id % 2 === 0 }">
                            <td class="px-4 py-2 whitespace-nowrap">{{ currency.iso_code }}</td>
                            <td class="px-4 py-2 whitespace-nowrap">{{ currency.currency_name }}</td>
                            <td class="px-4 py-2 whitespace-nowrap">{{ currency.country }}</td>
                            <td class="px-4 py-2 whitespace-nowrap">{{ currency.numeric_code }}</td>
                            <td class="px-4 py-2 whitespace-nowrap">{{ currency.symbol }}</td>
                            <td class="px-4 py-2 whitespace-nowrap">
                                <button
                                    @click="selectCurrency(currency)"
                                    class="px-3 py-1 text-xs text-white bg-blue-600 rounded hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                >
                                    Select
                                </button>
                            </td>
                        </tr>
                        <tr v-if="paginatedCurrencies.length === 0">
                            <td colspan="6" class="px-4 py-4 text-center text-gray-500">No ISO currencies found</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pagination Controls -->
            <div class="flex items-center justify-between mt-4">
                <div class="text-sm text-gray-700">
                    Showing {{ startIndex + 1 }} to {{ Math.min(endIndex, filteredCurrencies.length) }} of {{ filteredCurrencies.length }} entries
                </div>
                <div class="flex space-x-2">
                    <button
                        @click="currentPage = Math.max(1, currentPage - 1)"
                        :disabled="currentPage === 1"
                        class="px-3 py-1 text-sm bg-white border border-gray-300 rounded-md hover:bg-gray-100 disabled:opacity-50"
                    >
                        Previous
                    </button>
                    <button
                        @click="currentPage = Math.min(totalPages, currentPage + 1)"
                        :disabled="currentPage === totalPages || totalPages === 0"
                        class="px-3 py-1 text-sm bg-white border border-gray-300 rounded-md hover:bg-gray-100 disabled:opacity-50"
                    >
                        Next
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import axios from 'axios';

const props = defineProps({
    selectedCurrency: {
        type: Object,
        default: null
    }
});

const emit = defineEmits(['close', 'select']);

const currencies = ref([]);
const loading = ref(true);
const searchTerm = ref('');
const currentPage = ref(1);
const itemsPerPage = ref(10);
const sortColumn = ref('iso_code');
const sortDirection = ref('asc');

// Fetch currencies from API
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

// Filter currencies based on search term
const filteredCurrencies = computed(() => {
    if (!searchTerm.value) return currencies.value;
    
    const term = searchTerm.value.toLowerCase();
    return currencies.value.filter(currency => 
        currency.iso_code.toLowerCase().includes(term) ||
        currency.currency_name.toLowerCase().includes(term) ||
        currency.country.toLowerCase().includes(term) ||
        currency.numeric_code.toLowerCase().includes(term) ||
        (currency.symbol && currency.symbol.toLowerCase().includes(term))
    );
});

// Sort currencies
const sortedCurrencies = computed(() => {
    return [...filteredCurrencies.value].sort((a, b) => {
        const aValue = a[sortColumn.value];
        const bValue = b[sortColumn.value];
        
        if (sortDirection.value === 'asc') {
            return aValue < bValue ? -1 : aValue > bValue ? 1 : 0;
        } else {
            return aValue > bValue ? -1 : aValue < bValue ? 1 : 0;
        }
    });
});

// Paginate currencies
const totalPages = computed(() => {
    return Math.ceil(filteredCurrencies.value.length / itemsPerPage.value) || 1;
});

const startIndex = computed(() => {
    return (currentPage.value - 1) * itemsPerPage.value;
});

const endIndex = computed(() => {
    return startIndex.value + itemsPerPage.value;
});

const paginatedCurrencies = computed(() => {
    return sortedCurrencies.value.slice(startIndex.value, endIndex.value);
});

// Sort by column
const sortBy = (column) => {
    if (sortColumn.value === column) {
        sortDirection.value = sortDirection.value === 'asc' ? 'desc' : 'asc';
    } else {
        sortColumn.value = column;
        sortDirection.value = 'asc';
    }
};

// Search currencies
const searchCurrencies = () => {
    currentPage.value = 1;
};

// Select a currency
const selectCurrency = (currency) => {
    emit('select', currency);
};

// Fetch currencies on component mount
onMounted(() => {
    fetchCurrencies();
});
</script>

<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.3s;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}
</style> 