<template>
    <AppLayout header="Define Foreign Currency">
    <Head title="Define Foreign Currency" />

        <div class="container mx-auto px-4 py-6">
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-cyan-700 to-blue-600 p-6 rounded-t-lg shadow-lg">
        <h2 class="text-2xl font-bold text-white mb-2 flex items-center">
            <i class="fas fa-money-bill-wave mr-3"></i> Define Foreign Currency
        </h2>
        <p class="text-cyan-100">Create and manage foreign currencies for your organization</p>
    </div>

    <div class="bg-white rounded-b-lg shadow-lg p-6 mb-6">
        <!-- Notifications -->
        <div v-if="$page.props.flash.success" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ $page.props.flash.success }}
        </div>
        <div v-if="$page.props.flash.error" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            {{ $page.props.flash.error }}
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Form Section -->
            <div class="bg-white p-6 rounded-lg border border-gray-200 shadow">
                <h3 class="text-lg font-semibold text-gray-700 mb-4 flex items-center">
                    <i class="fas fa-pen mr-2"></i>
                    {{ isEditing ? 'Edit Foreign Currency' : 'Create New Foreign Currency' }}
                </h3>

                <form @submit.prevent="submitForm">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label for="currency_code" class="block text-sm font-medium text-gray-700 mb-1">Currency Code <span class="text-red-500">*</span></label>
                            <input
                                id="currency_code"
                                v-model="form.currency_code"
                                type="text"
                                maxlength="3"
                                class="w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500 uppercase"
                                :class="{'border-red-500': errors.currency_code}"
                                :disabled="isEditing"
                                required
                            >
                            <div v-if="errors.currency_code" class="text-red-500 text-xs mt-1">{{ errors.currency_code }}</div>
                            <div class="text-xs text-gray-500 mt-1">3 characters (e.g. USD, EUR, GBP)</div>
                        </div>
                        <div>
                            <label for="currency_name" class="block text-sm font-medium text-gray-700 mb-1">Currency Name <span class="text-red-500">*</span></label>
                            <input
                                id="currency_name"
                                v-model="form.currency_name"
                                type="text"
                                class="w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                :class="{'border-red-500': errors.currency_name}"
                                required
                            >
                            <div v-if="errors.currency_name" class="text-red-500 text-xs mt-1">{{ errors.currency_name }}</div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label for="country" class="block text-sm font-medium text-gray-700 mb-1">Country <span class="text-red-500">*</span></label>
                            <input
                                id="country"
                                v-model="form.country"
                                type="text"
                                class="w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                :class="{'border-red-500': errors.country}"
                                required
                            >
                            <div v-if="errors.country" class="text-red-500 text-xs mt-1">{{ errors.country }}</div>
                        </div>
                        <div>
                            <label for="exchange_rate" class="block text-sm font-medium text-gray-700 mb-1">Exchange Rate <span class="text-red-500">*</span></label>
                            <input
                                id="exchange_rate"
                                v-model="form.exchange_rate"
                                type="number"
                                step="0.0001"
                                min="0"
                                class="w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                :class="{'border-red-500': errors.exchange_rate}"
                                required
                            >
                            <div v-if="errors.exchange_rate" class="text-red-500 text-xs mt-1">{{ errors.exchange_rate }}</div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label for="exchange_method" class="block text-sm font-medium text-gray-700 mb-1">Exchange Method <span class="text-red-500">*</span></label>
                            <select
                                id="exchange_method"
                                v-model="form.exchange_method"
                                class="w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                :class="{'border-red-500': errors.exchange_method}"
                                required
                            >
                                <option value="1">Method 1 - Standard</option>
                                <option value="2">Method 2 - Special</option>
                            </select>
                            <div v-if="errors.exchange_method" class="text-red-500 text-xs mt-1">{{ errors.exchange_method }}</div>
                        </div>
                        <div>
                            <label for="variance_control" class="block text-sm font-medium text-gray-700 mb-1">Variance Control % <span class="text-red-500">*</span></label>
                            <input
                                id="variance_control"
                                v-model="form.variance_control"
                                type="number"
                                step="0.01"
                                min="0"
                                max="100"
                                class="w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                :class="{'border-red-500': errors.variance_control}"
                                required
                            >
                            <div v-if="errors.variance_control" class="text-red-500 text-xs mt-1">{{ errors.variance_control }}</div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="max_tax_adj" class="block text-sm font-medium text-gray-700 mb-1">Max Tax Adjustment <span class="text-red-500">*</span></label>
                        <input
                            id="max_tax_adj"
                            v-model="form.max_tax_adj"
                            type="number"
                            step="0.01"
                            min="0"
                            class="w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                            :class="{'border-red-500': errors.max_tax_adj}"
                            required
                        >
                        <div v-if="errors.max_tax_adj" class="text-red-500 text-xs mt-1">{{ errors.max_tax_adj }}</div>
                    </div>

                    <div class="flex items-center justify-between pt-3">
                        <div>
                            <button
                                type="button"
                                @click="resetForm"
                                class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2"
                            >
                                <i class="fas fa-times mr-2"></i> Cancel
                            </button>
                        </div>
                        <div class="flex space-x-2">
                            <button
                                v-if="isEditing"
                                type="button"
                                @click="confirmDelete"
                                class="px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2"
                            >
                                <i class="fas fa-trash mr-2"></i> Delete
                            </button>
                            <button
                                type="submit"
                                class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                            >
                                <i class="fas fa-save mr-2"></i> {{ isEditing ? 'Update' : 'Save' }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Table Section -->
            <div>
                <div class="bg-white p-4 rounded-lg border border-gray-200 shadow">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-700 flex items-center">
                            <i class="fas fa-list mr-2"></i> Foreign Currencies List
                        </h3>

                        <div class="flex items-center space-x-2">
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    <i class="fas fa-search text-gray-400"></i>
                                </div>
                                <input 
                                    type="text" 
                                    v-model="search" 
                                    class="pl-10 pr-4 py-2 text-sm border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="Search currencies..."
                                >
                            </div>
                            <Link 
                                href="/vue/foreign-currency/view-print" 
                                class="px-3 py-2 bg-green-500 text-white rounded hover:bg-green-600 flex items-center text-sm"
                            >
                                <i class="fas fa-print mr-1"></i> View & Print
                            </Link>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th @click="sortBy('currency_code')" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                                        Code <i :class="getSortIcon('currency_code')"></i>
                                    </th>
                                    <th @click="sortBy('currency_name')" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                                        Name <i :class="getSortIcon('currency_name')"></i>
                                    </th>
                                    <th @click="sortBy('country')" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                                        Country <i :class="getSortIcon('country')"></i>
                                    </th>
                                    <th @click="sortBy('exchange_rate')" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                                        Exchange Rate <i :class="getSortIcon('exchange_rate')"></i>
                                    </th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Action
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr v-if="loading">
                                    <td colspan="5" class="px-4 py-4 text-center">
                                        <div class="flex justify-center">
                                            <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-blue-500"></div>
                                        </div>
                                    </td>
                                </tr>
                                <tr v-else-if="filteredCurrencies.length === 0">
                                    <td colspan="5" class="px-4 py-4 text-center text-gray-500">
                                        No currencies found. Add your first currency using the form on the left.
                                    </td>
                                </tr>
                                <tr v-for="(currency, index) in filteredCurrencies" :key="currency.id" 
                                    :class="{'bg-blue-50': index % 2 === 0}"
                                    class="hover:bg-blue-100">
                                    <td class="px-4 py-2 whitespace-nowrap text-sm font-medium">{{ currency.currency_code }}</td>
                                    <td class="px-4 py-2 whitespace-nowrap text-sm">{{ currency.currency_name }}</td>
                                    <td class="px-4 py-2 whitespace-nowrap text-sm">{{ currency.country }}</td>
                                    <td class="px-4 py-2 whitespace-nowrap text-sm">{{ formatNumber(currency.exchange_rate) }}</td>
                                    <td class="px-4 py-2 whitespace-nowrap text-sm">
                                        <button 
                                            @click="editCurrency(currency)" 
                                            class="text-blue-600 hover:text-blue-900 mr-2"
                                            title="Edit"
                                        >
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button 
                                            @click="showCurrencyDetails(currency)" 
                                            class="text-green-600 hover:text-green-900"
                                            title="View Details"
                                        >
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Details Panel -->
                <div v-if="selectedCurrency" class="mt-4 bg-white p-4 rounded-lg border border-gray-200 shadow">
                    <div class="flex justify-between items-center mb-3">
                        <h3 class="text-lg font-semibold text-gray-700">Currency Details: {{ selectedCurrency.currency_code }}</h3>
                        <button @click="selectedCurrency = null" class="text-gray-500 hover:text-gray-700">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Currency Code</p>
                            <p class="text-sm">{{ selectedCurrency.currency_code }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Currency Name</p>
                            <p class="text-sm">{{ selectedCurrency.currency_name }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Country</p>
                            <p class="text-sm">{{ selectedCurrency.country }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Exchange Rate</p>
                            <p class="text-sm">{{ formatNumber(selectedCurrency.exchange_rate) }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Exchange Method</p>
                            <p class="text-sm">{{ selectedCurrency.exchange_method == 1 ? 'Method 1 - Standard' : 'Method 2 - Special' }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Variance Control</p>
                            <p class="text-sm">{{ formatNumber(selectedCurrency.variance_control) }}%</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Max Tax Adjustment</p>
                            <p class="text-sm">{{ formatNumber(selectedCurrency.max_tax_adj) }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Last Updated</p>
                            <p class="text-sm">{{ formatDate(selectedCurrency.updated_at) }}</p>
                        </div>
                    </div>
                    <div class="mt-4 flex justify-end">
                        <button 
                            @click="editCurrency(selectedCurrency)" 
                            class="px-3 py-1 bg-blue-500 text-white rounded-md hover:bg-blue-600 text-sm"
                        >
                            <i class="fas fa-edit mr-1"></i> Edit
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
        </div>
    </AppLayout>

    <!-- Delete Confirmation Modal -->
    <div v-if="showDeleteModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Confirm Deletion</h3>
            <p class="text-gray-700 mb-6">
                Are you sure you want to delete the currency "{{ form.currency_name }}" ({{ form.currency_code }})? This action cannot be undone.
            </p>
            <div class="flex justify-end space-x-3">
                <button 
                    @click="showDeleteModal = false" 
                    class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300"
                >
                    Cancel
                </button>
                <button 
                    @click="deleteCurrency" 
                    class="px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-600"
                >
                    Delete
                </button>
            </div>
        </div>
    </div>

    <!-- Currency Modal (for lookup) -->
    <ForeignCurrencyModal 
        v-if="showModal"
        @close="showModal = false"
        @select="selectCurrency"
    />
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import ForeignCurrencyModal from '@/Components/ForeignCurrencyModal.vue';
import AppLayout from '@/Layouts/AppLayout.vue';

const currencies = ref([]);
const loading = ref(true);
const errors = ref({});
const isEditing = ref(false);
const selectedCurrency = ref(null);
const search = ref('');
const sortField = ref('currency_code');
const sortDirection = ref('asc');
const showDeleteModal = ref(false);
const showModal = ref(false);

// Initialize form with empty values
const form = useForm({
    id: null,
    currency_code: '',
    currency_name: '',
    country: '',
    exchange_rate: '',
    exchange_method: '1',
    variance_control: '',
    max_tax_adj: ''
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

// Reset form to initial state
const resetForm = () => {
    form.reset();
    isEditing.value = false;
    errors.value = {};
};

// Show currency details
const showCurrencyDetails = (currency) => {
    selectedCurrency.value = { ...currency };
};

// Edit currency
const editCurrency = (currency) => {
    isEditing.value = true;
    form.id = currency.id;
    form.currency_code = currency.currency_code;
    form.currency_name = currency.currency_name;
    form.country = currency.country;
    form.exchange_rate = currency.exchange_rate;
    form.exchange_method = currency.exchange_method.toString();
    form.variance_control = currency.variance_control;
    form.max_tax_adj = currency.max_tax_adj;
    
    // Close details panel if open
    selectedCurrency.value = null;
    
    // Scroll to form
    document.querySelector('form').scrollIntoView({ behavior: 'smooth' });
};

// Confirm delete
const confirmDelete = () => {
    showDeleteModal.value = true;
};

// Delete currency
const deleteCurrency = () => {
    form.delete(route('foreign-currency.destroy', form.id), {
        onSuccess: () => {
            showDeleteModal.value = false;
            fetchCurrencies();
            resetForm();
        },
        onError: (errors) => {
            errors.value = errors;
        }
    });
};

// Submit form
const submitForm = () => {
    if (isEditing.value) {
        form.put(route('foreign-currency.update', form.id), {
            onSuccess: () => {
                fetchCurrencies();
                resetForm();
            },
            onError: (formErrors) => {
                errors.value = formErrors;
            }
        });
    } else {
        form.post(route('foreign-currency.store'), {
            onSuccess: () => {
                fetchCurrencies();
                resetForm();
            },
            onError: (formErrors) => {
                errors.value = formErrors;
            }
        });
    }
};

// Select currency from modal
const selectCurrency = (selectedCurrencyItem) => {
    // Use the selected currency from the modal
    console.log('Selected currency:', selectedCurrencyItem);
    showModal.value = false;
};

onMounted(() => {
    fetchCurrencies();
});
</script>

<style scoped>
.fade-enter-active, .fade-leave-active {
    transition: opacity 0.3s;
}
.fade-enter, .fade-leave-to {
    opacity: 0;
}
</style> 