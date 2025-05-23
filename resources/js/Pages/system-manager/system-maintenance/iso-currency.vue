<template>
    <AppLayout header="ISO Currency Management">
    <Head title="Define ISO Currency" />

        <!-- Content -->
        <div class="container mx-auto px-4 py-6">
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-800 rounded-lg shadow-lg p-6 mb-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <i class="fas fa-globe text-white text-3xl mr-4"></i>
                <div>
                    <h1 class="text-2xl font-bold text-white">ISO Currency Management</h1>
                    <p class="text-blue-100">Manage international standard currency codes</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Form Section -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold mb-4 flex items-center">
                    <i class="fas fa-edit text-blue-600 mr-2"></i>
                    {{ isEditing ? 'Edit ISO Currency' : 'Add New ISO Currency' }}
                </h2>
                
                <form @submit.prevent="submitForm">
                    <!-- ISO Code -->
                    <div class="mb-4">
                        <label for="iso_code" class="block text-sm font-medium text-gray-700 mb-1">ISO Code <span class="text-red-500">*</span></label>
                        <input
                            type="text"
                            id="iso_code"
                            v-model="form.iso_code"
                            maxlength="3"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            :class="{ 'border-red-500': errors.iso_code }"
                            :disabled="isEditing"
                        />
                        <p v-if="errors.iso_code" class="mt-1 text-sm text-red-500">{{ errors.iso_code }}</p>
                        <p class="mt-1 text-xs text-gray-500">3-letter ISO 4217 currency code</p>
                    </div>

                    <!-- Currency Name -->
                    <div class="mb-4">
                        <label for="currency_name" class="block text-sm font-medium text-gray-700 mb-1">Currency Name <span class="text-red-500">*</span></label>
                        <input
                            type="text"
                            id="currency_name"
                            v-model="form.currency_name"
                            maxlength="100"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            :class="{ 'border-red-500': errors.currency_name }"
                        />
                        <p v-if="errors.currency_name" class="mt-1 text-sm text-red-500">{{ errors.currency_name }}</p>
                    </div>

                    <!-- Country -->
                    <div class="mb-4">
                        <label for="country" class="block text-sm font-medium text-gray-700 mb-1">Country <span class="text-red-500">*</span></label>
                        <input
                            type="text"
                            id="country"
                            v-model="form.country"
                            maxlength="100"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            :class="{ 'border-red-500': errors.country }"
                        />
                        <p v-if="errors.country" class="mt-1 text-sm text-red-500">{{ errors.country }}</p>
                    </div>

                    <!-- Numeric Code -->
                    <div class="mb-4">
                        <label for="numeric_code" class="block text-sm font-medium text-gray-700 mb-1">Numeric Code <span class="text-red-500">*</span></label>
                        <input
                            type="text"
                            id="numeric_code"
                            v-model="form.numeric_code"
                            maxlength="3"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            :class="{ 'border-red-500': errors.numeric_code }"
                        />
                        <p v-if="errors.numeric_code" class="mt-1 text-sm text-red-500">{{ errors.numeric_code }}</p>
                        <p class="mt-1 text-xs text-gray-500">3-digit numeric code</p>
                    </div>

                    <!-- Minor Unit -->
                    <div class="mb-4">
                        <label for="minor_unit" class="block text-sm font-medium text-gray-700 mb-1">Minor Unit <span class="text-red-500">*</span></label>
                        <input
                            type="number"
                            id="minor_unit"
                            v-model="form.minor_unit"
                            min="0"
                            max="4"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            :class="{ 'border-red-500': errors.minor_unit }"
                        />
                        <p v-if="errors.minor_unit" class="mt-1 text-sm text-red-500">{{ errors.minor_unit }}</p>
                        <p class="mt-1 text-xs text-gray-500">Number of decimal places (0-4)</p>
                    </div>

                    <!-- Currency Symbol -->
                    <div class="mb-4">
                        <label for="symbol" class="block text-sm font-medium text-gray-700 mb-1">Currency Symbol</label>
                        <input
                            type="text"
                            id="symbol"
                            v-model="form.symbol"
                            maxlength="10"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            :class="{ 'border-red-500': errors.symbol }"
                        />
                        <p v-if="errors.symbol" class="mt-1 text-sm text-red-500">{{ errors.symbol }}</p>
                    </div>

                    <!-- Is Active -->
                    <div class="mb-6">
                        <div class="flex items-center">
                            <input
                                type="checkbox"
                                id="is_active"
                                v-model="form.is_active"
                                class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                            />
                            <label for="is_active" class="ml-2 block text-sm text-gray-700">Active Currency</label>
                        </div>
                    </div>

                    <!-- Form Buttons -->
                    <div class="flex space-x-3">
                        <button
                            type="submit"
                            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            :disabled="loading"
                        >
                            <i class="fas fa-save mr-2"></i>
                            {{ isEditing ? 'Update' : 'Save' }}
                        </button>
                        
                        <button
                            v-if="isEditing"
                            type="button"
                            @click="cancelEdit"
                            class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500"
                        >
                            <i class="fas fa-times mr-2"></i>
                            Cancel
                        </button>
                        
                        <button
                            v-if="isEditing"
                            type="button"
                            @click="confirmDelete"
                            class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500"
                        >
                            <i class="fas fa-trash mr-2"></i>
                            Delete
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Table Section -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-semibold flex items-center">
                        <i class="fas fa-list text-blue-600 mr-2"></i>
                        ISO Currency List
                    </h2>
                    
                    <div class="flex items-center">
                        <div class="relative">
                            <input
                                type="text"
                                v-model="search"
                                placeholder="Search currencies..."
                                class="px-3 py-2 pr-10 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            />
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                <i class="fas fa-search text-gray-400"></i>
                            </div>
                        </div>
                        
                        <button
                            @click="openCurrencyModal"
                            class="ml-3 px-3 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        >
                            <i class="fas fa-search mr-1"></i>
                            Lookup
                        </button>
                    </div>
                </div>

                <!-- Loading State -->
                <div v-if="loading" class="py-8 flex justify-center">
                    <div class="w-12 h-12 border-4 border-blue-500 border-t-transparent rounded-full animate-spin"></div>
                </div>

                <!-- Table -->
                <div v-else-if="currencies.length > 0" class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
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
                                <th @click="sortBy('symbol')" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                                    Symbol
                                    <i :class="getSortIcon('symbol')"></i>
                                </th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr 
                                v-for="currency in filteredCurrencies" 
                                :key="currency.id" 
                                @click="editCurrency(currency)"
                                class="hover:bg-blue-50 cursor-pointer"
                                :class="{ 'bg-blue-100': selectedCurrency && selectedCurrency.id === currency.id }"
                            >
                                <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900">{{ currency.iso_code }}</td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-700">{{ currency.currency_name }}</td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-700">{{ currency.country }}</td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-700">{{ currency.symbol }}</td>
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
                        </tbody>
                    </table>
                </div>

                <!-- Empty State -->
                <div v-else class="py-8 text-center text-gray-500">
                    <i class="fas fa-coins text-gray-300 text-5xl mb-4"></i>
                    <p>No ISO currencies found. Add your first currency using the form.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Currency Details -->
    <div v-if="selectedCurrency" class="mt-6">
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold mb-4 flex items-center">
                <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                Currency Details: {{ selectedCurrency.currency_name }}
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <h3 class="text-sm font-medium text-gray-500">ISO Code</h3>
                    <p class="mt-1 text-lg">{{ selectedCurrency.iso_code }}</p>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-500">Currency Name</h3>
                    <p class="mt-1 text-lg">{{ selectedCurrency.currency_name }}</p>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-500">Country</h3>
                    <p class="mt-1 text-lg">{{ selectedCurrency.country }}</p>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-500">Numeric Code</h3>
                    <p class="mt-1 text-lg">{{ selectedCurrency.numeric_code }}</p>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-500">Minor Unit</h3>
                    <p class="mt-1 text-lg">{{ selectedCurrency.minor_unit }}</p>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-500">Symbol</h3>
                    <p class="mt-1 text-lg">{{ selectedCurrency.symbol || '-' }}</p>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-500">Status</h3>
                    <p class="mt-1">
                        <span 
                            :class="[
                                'px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full',
                                selectedCurrency.is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'
                            ]"
                        >
                            {{ selectedCurrency.is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div v-if="showDeleteModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white rounded-lg shadow-xl p-6 max-w-md mx-auto">
            <h3 class="text-xl font-bold text-gray-900 mb-4">Confirm Delete</h3>
            <p class="mb-6 text-gray-700">
                Are you sure you want to delete the ISO currency <span class="font-semibold">{{ selectedCurrency.iso_code }}</span>?
                This action cannot be undone.
            </p>
            <div class="flex justify-end space-x-3">
                <button 
                    @click="showDeleteModal = false"
                    class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500"
                >
                    Cancel
                </button>
                <button 
                    @click="deleteCurrency"
                    class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500"
                >
                    Delete
                </button>
            </div>
        </div>
    </div>

    <!-- ISO Currency Modal -->
    <ISOCurrencyModal 
        v-if="showCurrencyModal"
        @close="showCurrencyModal = false"
        @select="selectCurrencyFromModal"
    />

    <!-- Success Notification -->
    <div 
        v-if="notification.show" 
        class="fixed bottom-4 right-4 px-6 py-4 bg-green-500 text-white rounded-lg shadow-lg z-50 flex items-center"
    >
        <i class="fas fa-check-circle mr-3 text-xl"></i>
        <span>{{ notification.message }}</span>
        <button @click="notification.show = false" class="ml-4 text-white hover:text-green-200">
            <i class="fas fa-times"></i>
        </button>
    </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import axios from 'axios';
import ISOCurrencyModal from '@/Components/ISOCurrencyModal.vue';
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

// State
const currencies = ref([]);
const selectedCurrency = ref(null);
const loading = ref(true);
const showDeleteModal = ref(false);
const showCurrencyModal = ref(false);
const search = ref('');
const sortField = ref('iso_code');
const sortDirection = ref('asc');
const notification = ref({
    show: false,
    message: ''
});

// Form state
const form = ref({
    iso_code: '',
    currency_name: '',
    country: '',
    numeric_code: '',
    minor_unit: 2,
    symbol: '',
    is_active: true
});
const errors = ref({});
const isEditing = ref(false);

// Fetch all currencies
const fetchCurrencies = async () => {
    loading.value = true;
    try {
        const response = await axios.get('/api/iso-currencies');
        currencies.value = response.data;
    } catch (error) {
        console.error('Error fetching ISO currencies:', error);
        showNotification('Failed to load ISO currencies');
    } finally {
        loading.value = false;
    }
};

// Filter currencies based on search
const filteredCurrencies = computed(() => {
    if (!search.value) return currencies.value;
    
    const searchTerm = search.value.toLowerCase();
    return currencies.value.filter(currency => 
        currency.iso_code.toLowerCase().includes(searchTerm) ||
        currency.currency_name.toLowerCase().includes(searchTerm) ||
        currency.country.toLowerCase().includes(searchTerm) ||
        (currency.symbol && currency.symbol.toLowerCase().includes(searchTerm))
    );
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

// Edit currency
const editCurrency = (currency) => {
    selectedCurrency.value = currency;
    form.value = { ...currency };
    isEditing.value = true;
};

// Cancel edit
const cancelEdit = () => {
    form.value = {
        iso_code: '',
        currency_name: '',
        country: '',
        numeric_code: '',
        minor_unit: 2,
        symbol: '',
        is_active: true
    };
    isEditing.value = false;
    errors.value = {};
};

// Submit form
const submitForm = async () => {
    errors.value = {};
    
    try {
        if (isEditing.value) {
            // Update existing currency
            await axios.put(`/api/iso-currencies/${selectedCurrency.value.id}`, form.value);
            showNotification('ISO Currency updated successfully');
        } else {
            // Create new currency
            await axios.post('/api/iso-currencies', form.value);
            showNotification('ISO Currency created successfully');
        }
        
        // Reset form and fetch updated currencies
        cancelEdit();
        fetchCurrencies();
    } catch (error) {
        if (error.response && error.response.data && error.response.data.errors) {
            errors.value = error.response.data.errors;
        } else {
            showNotification('An error occurred while saving the ISO currency', 'error');
            console.error('Error submitting form:', error);
        }
    }
};

// Confirm delete
const confirmDelete = () => {
    if (selectedCurrency.value) {
        showDeleteModal.value = true;
    }
};

// Delete currency
const deleteCurrency = async () => {
    try {
        await axios.delete(`/api/iso-currencies/${selectedCurrency.value.id}`);
        showNotification('ISO Currency deleted successfully');
        showDeleteModal.value = false;
        cancelEdit();
        selectedCurrency.value = null;
        fetchCurrencies();
    } catch (error) {
        showNotification('Failed to delete ISO currency', 'error');
        console.error('Error deleting currency:', error);
    }
};

// Open currency modal
const openCurrencyModal = () => {
    showCurrencyModal.value = true;
};

// Select currency from modal
const selectCurrencyFromModal = (currency) => {
    editCurrency(currency);
    showCurrencyModal.value = false;
};

// Show notification
const showNotification = (message, type = 'success') => {
    notification.value = {
        show: true,
        message,
        type
    };
    
    // Auto-hide after 5 seconds
    setTimeout(() => {
        notification.value.show = false;
    }, 5000);
};

// Fetch currencies on component mount
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