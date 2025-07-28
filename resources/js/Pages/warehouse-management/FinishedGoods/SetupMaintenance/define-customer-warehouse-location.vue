<template>
    <AppLayout :header="'Define Customer Warehouse Location'">
        <!-- Header Section with animated elements, adapted from Update MC -->
        <div class="bg-gradient-to-r from-purple-600 via-indigo-600 to-blue-600 p-6 rounded-t-lg shadow-lg overflow-hidden relative">
            <!-- Decorative Elements -->
            <div class="absolute top-0 right-0 w-40 h-40 bg-white opacity-5 rounded-full -translate-y-20 translate-x-20"></div>
            <div class="absolute bottom-0 left-0 w-20 h-20 bg-white opacity-5 rounded-full translate-y-10 -translate-x-10"></div>
            <div class="absolute bottom-0 right-0 w-32 h-32 bg-yellow-400 opacity-5 rounded-full translate-y-10 translate-x-10"></div>
            
            <div class="flex items-center">
                <div class="bg-gradient-to-br from-pink-500 to-purple-600 p-3 rounded-lg shadow-inner flex items-center justify-center relative overflow-hidden mr-4">
                    <div class="absolute -top-1 -right-1 w-6 h-6 bg-yellow-300 opacity-30 rounded-full animate-ping-slow"></div>
                    <div class="absolute -bottom-1 -left-1 w-4 h-4 bg-blue-300 opacity-30 rounded-full animate-ping-slow animation-delay-500"></div>
                    <i class="fas fa-map-marked-alt text-white text-2xl z-10"></i>
                </div>
                <div>
                    <h2 class="text-2xl md:text-3xl font-bold text-white mb-1 text-shadow">Define Customer Warehouse Location</h2>
                    <p class="text-blue-100 max-w-2xl">Manage and define customer-specific warehouse locations and their lock controls</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-b-lg shadow-lg p-6 mb-6 bg-gradient-to-br from-white to-indigo-50">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left Column - Main Content -->
                <div class="lg:col-span-2">
                    <div class="bg-white p-6 rounded-lg shadow-md border-t-4 border-indigo-500 transition-all duration-300 hover:shadow-lg transform hover:-translate-y-1 relative overflow-hidden">
                        <div class="absolute -top-20 -right-20 w-40 h-40 bg-indigo-50 rounded-full opacity-20"></div>
                        <div class="absolute -bottom-8 -left-8 w-24 h-24 bg-purple-50 rounded-full opacity-20"></div>
                        
                        <div class="flex items-center mb-6 pb-2 border-b border-gray-200 relative z-10">
                            <div class="p-2 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-lg mr-3 shadow-md">
                                <i class="fas fa-users-cog text-white"></i>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-800">Customer Warehouse Location Management</h3>
                        </div>

                        <!-- Form content -->
                        <form @submit.prevent="saveCustomerWarehouseLocation" class="space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="customer_code" class="block text-sm font-medium text-gray-700 mb-1 flex items-center">
                                        <span class="inline-flex items-center justify-center w-5 h-5 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-full mr-2 shadow-sm">
                                            <i class="fas fa-hashtag text-white text-xs"></i>
                                        </span>
                                        Customer Code:
                                    </label>
                                    <div class="relative flex group">
                                        <input 
                                            type="text" 
                                            id="customer_code" 
                                            v-model="form.customer_code"
                                            @input="debouncedCheckCustomerCode"
                                            :class="{'border-red-500': customerCodeExists && !isEditMode}"
                                            class="flex-1 min-w-0 block w-full px-3 py-2 rounded-l-md border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 transition-all group-hover:border-indigo-300"
                                        />
                                        <button 
                                            type="button"
                                            @click="openCustomerAccountModal"
                                            class="inline-flex items-center px-3 py-2 border border-l-0 border-gray-300 bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 text-white rounded-r-md transition-all transform active:translate-y-px relative overflow-hidden shadow-sm"
                                        >
                                            <span class="absolute inset-0 bg-white opacity-0 hover:opacity-20 transition-opacity"></span>
                                            <i class="fas fa-table relative z-10"></i>
                                        </button>
                                    </div>
                                    <p v-if="customerCodeExists && !isEditMode" class="mt-1 text-sm text-red-600">Customer Code already exists. Use 'Record: Select' to load it.</p>
                                </div>

                                <div class="flex items-end">
                                    <button 
                                        type="button"
                                        @click="handleRecordAction"
                                        :disabled="!form.customer_code || (customerCodeExists && !isEditMode) && !form.id"
                                        :class="{
                                            'bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700': !isEditMode,
                                            'bg-gradient-to-r from-blue-500 to-cyan-600 hover:from-blue-600 hover:to-cyan-700': isEditMode,
                                            'opacity-50 cursor-not-allowed': !form.customer_code || (customerCodeExists && !isEditMode) && !form.id
                                        }"
                                        class="text-white px-4 py-2 rounded-lg flex items-center space-x-2 transform active:translate-y-px transition-all duration-300 shadow-md relative overflow-hidden group"
                                    >
                                        <span class="absolute inset-0 bg-white opacity-0 group-hover:opacity-20 transition-opacity"></span>
                                        <div class="bg-white bg-opacity-30 rounded-full p-1.5 mr-2 flex items-center justify-center">
                                            <i :class="isEditMode ? 'fas fa-edit' : 'fas fa-plus'" class="text-white text-xs"></i>
                                        </div>
                                        <span>Record: {{ isEditMode ? 'Edit' : 'Add' }}</span>
                                    </button>
                                </div>
                            </div>
                            
                            <div v-if="isEditMode || (form.customer_code && !customerCodeExists)" class="space-y-6">
                                <div>
                                    <label for="customer_name" class="block text-sm font-medium text-gray-700 mb-1 flex items-center">
                                        <span class="inline-flex items-center justify-center w-5 h-5 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-full mr-2 shadow-sm">
                                            <i class="fas fa-user text-white text-xs"></i>
                                        </span>
                                        Customer Name:
                                    </label>
                                    <input 
                                        type="text" 
                                        id="customer_name" 
                                        v-model="form.customer_name" 
                                        readonly
                                        class="block w-full px-3 py-2 rounded-md border border-gray-300 bg-gray-100"
                                    />
                                </div>

                                <!-- Lock Control -->
                                <fieldset class="border border-gray-300 p-4 rounded-md shadow-sm">
                                    <legend class="text-md font-medium text-gray-700 px-2 -ml-2">Lock Control</legend>
                                    <div class="mt-2 space-y-3">
                                        <div class="flex items-center space-x-4">
                                            <label class="text-sm font-medium text-gray-700">Lock Customer Location:</label>
                                            <label class="inline-flex items-center">
                                                <input type="radio" class="form-radio" name="lock_customer_location" :value="true" v-model="form.lock_customer_location">
                                                <span class="ml-2 text-gray-700">Y-Yes Lock</span>
                                            </label>
                                            <label class="inline-flex items-center">
                                                <input type="radio" class="form-radio" name="lock_customer_location" :value="false" v-model="form.lock_customer_location">
                                                <span class="ml-2 text-gray-700">N-No Lock</span>
                                            </label>
                                        </div>
                                    </div>
                                </fieldset>

                                <div class="flex justify-end pt-4 border-t border-gray-200">
                                    <button type="submit" 
                                        :class="{
                                            'bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700': isEditMode,
                                            'bg-gradient-to-r from-blue-500 to-cyan-600 hover:from-blue-600 hover:to-cyan-700': !isEditMode
                                        }"
                                        class="text-white px-6 py-2 rounded-lg shadow-md flex items-center space-x-2 transition-all duration-300 transform active:scale-95"
                                    >
                                        <i class="fas fa-save"></i>
                                        <span>{{ isEditMode ? 'Update Location' : 'Create Location' }}</span>
                                    </button>
                                    <button v-if="isEditMode" type="button" @click="deleteCustomerWarehouseLocation" class="ml-4 bg-gradient-to-r from-red-500 to-rose-600 hover:from-red-600 hover:to-rose-700 text-white px-6 py-2 rounded-lg shadow-md flex items-center space-x-2 transition-all duration-300 transform active:scale-95">
                                        <i class="fas fa-trash-alt"></i>
                                        <span>Delete Location</span>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Right Column - Quick Info (adapted from Update MC) -->
                <div class="lg:col-span-1">
                    <div class="bg-white p-6 rounded-lg shadow-md border-t-4 border-teal-500 mb-6 transition-all duration-300 hover:shadow-lg transform hover:-translate-y-1 relative overflow-hidden">
                        <div class="absolute -top-16 -right-16 w-32 h-32 bg-teal-50 rounded-full opacity-20"></div>
                        <div class="absolute -bottom-6 -left-6 w-20 h-20 bg-green-50 rounded-full opacity-20"></div>
                        
                        <div class="flex items-center mb-4 pb-2 border-b border-gray-200 relative z-10">
                            <div class="p-2 bg-gradient-to-r from-teal-500 to-green-500 rounded-lg mr-3 shadow-md">
                                <i class="fas fa-lightbulb text-white"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-800">Quick Tips</h3>
                        </div>

                        <div class="space-y-4">
                            <div class="p-4 bg-teal-50 rounded-lg">
                                <h4 class="text-sm font-semibold text-teal-800 uppercase tracking-wider mb-2 flex items-center">
                                    <span class="inline-flex items-center justify-center w-5 h-5 bg-gradient-to-r from-blue-400 to-indigo-400 rounded-full mr-2 shadow-sm mt-0.5">
                                        <i class="fas fa-question text-white text-xs"></i>
                                    </span>
                                    How to Use
                                </h4>
                                <ul class="text-sm text-gray-600 space-y-2">
                                    <li class="flex items-start">
                                        <span class="inline-flex items-center justify-center w-5 h-5 bg-gradient-to-r from-blue-400 to-indigo-400 rounded-full mr-2 shadow-sm mt-0.5">
                                            <i class="fas fa-check text-white text-xs"></i>
                                        </span>
                                        <span>Enter a customer code and press <i class="fas fa-table"></i> to search.</span>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="inline-flex items-center justify-center w-5 h-5 bg-gradient-to-r from-purple-400 to-pink-400 rounded-full mr-2 shadow-sm mt-0.5">
                                            <i class="fas fa-check text-white text-xs"></i>
                                        </span>
                                        <span>If customer code exists, click 'Record: Edit' to update.</span>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="inline-flex items-center justify-center w-5 h-5 bg-gradient-to-r from-amber-400 to-orange-400 rounded-full mr-2 shadow-sm mt-0.5">
                                            <i class="fas fa-check text-white text-xs"></i>
                                        </span>
                                        <span>If customer code is new, fill details and click 'Record: Add'.</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Customer Account Modal -->
        <CustomerAccountModal 
            :show="showCustomerAccountModal" 
            :customer-accounts="customerAccounts"
            @close="closeCustomerAccountModal" 
            @select="handleCustomerSelected" 
            @sort-by-code="fetchCustomers(true)"
            @sort-by-name="fetchCustomers(false)"
        />

    </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { ref, reactive, onMounted, watch } from 'vue';
import axios from 'axios';
import debounce from 'lodash/debounce';
import CustomerAccountModal from '@/Components/CustomerAccountModal.vue';

const form = reactive({
    id: null,
    customer_code: '',
    customer_name: '',
    lock_customer_location: false,
});

const isEditMode = ref(false);
const customerCodeExists = ref(false);
const showCustomerAccountModal = ref(false);
const customerAccounts = ref([]);
const customerAccountSortBy = ref('code'); // Default sort by code
const customerAccountRecordStatus = reactive({
    active: true,
    obsolete: false
});

const fetchCustomers = async (sortByCode = true) => {
    try {
        const response = await axios.get('/api/customers-with-status');
        if (response.data && Array.isArray(response.data)) {
            customerAccounts.value = response.data;
        } else if (response.data && Array.isArray(response.data.data)) {
            customerAccounts.value = response.data.data;
        }
        console.log(`Loaded ${customerAccounts.value.length} customers`);
    } catch (error) {
        console.error('Error fetching customers:', error);
    }
};

const openCustomerAccountModal = async () => {
    await fetchCustomers();
    showCustomerAccountModal.value = true;
};

const closeCustomerAccountModal = () => {
    showCustomerAccountModal.value = false;
};

const handleCustomerSelected = (customer) => {
    console.log('Selected customer in parent component:', customer);
    if (customer && customer.customer_code) {
        form.customer_code = customer.customer_code;
        form.customer_name = customer.customer_name || '';
        closeCustomerAccountModal();
        // Don't call checkCustomerCodeExists here as it might overwrite the customer name
        // Instead, directly check if it exists in warehouse locations
        checkExistenceWithoutChangingName();
    } else {
        console.error('Invalid customer data received:', customer);
    }
};

// New function to check existence without changing customer name
const checkExistenceWithoutChangingName = async () => {
    if (!form.customer_code) return;
    
    try {
        const response = await axios.get(route('customer-warehouse-locations.check', form.customer_code));
        customerCodeExists.value = response.data.exists;
        
        // Don't update customer_name here, preserve what was selected
        
        if (response.data.exists) {
            // If exists, fetch full data but preserve customer name
            fetchCustomerWarehouseLocationPreserveName();
        } else {
            isEditMode.value = false;
            form.id = null;
            form.lock_customer_location = false; // Default
        }
    } catch (error) {
        console.error('Error checking customer code existence:', error);
        customerCodeExists.value = false;
        isEditMode.value = false;
        form.id = null;
        form.lock_customer_location = false;
    }
};

// New function to fetch warehouse location but preserve customer name
const fetchCustomerWarehouseLocationPreserveName = async () => {
    if (!form.customer_code) return;
    
    // Store current customer name
    const currentCustomerName = form.customer_name;
    
    try {
        const response = await axios.get(route('customer-warehouse-locations.show', form.customer_code));
        const data = response.data.data;
        if (data) {
            form.id = data.id;
            // Keep current customer name instead of overwriting
            form.lock_customer_location = data.lock_customer_location;
            isEditMode.value = true;
            customerCodeExists.value = true;
        } else {
            form.id = null;
            form.lock_customer_location = false;
            isEditMode.value = false;
            customerCodeExists.value = false;
        }
    } catch (error) {
        console.error('Error fetching customer warehouse location:', error);
        form.id = null;
        form.lock_customer_location = false;
        isEditMode.value = false;
        customerCodeExists.value = false;
    }
    
    // Restore customer name after the operation
    form.customer_name = currentCustomerName;
};

const checkCustomerCodeExists = debounce(async () => {
    if (!form.customer_code) {
        customerCodeExists.value = false;
        isEditMode.value = false;
        form.id = null;
        form.customer_name = '';
        form.lock_customer_location = false;
        return;
    }
    
    try {
        const response = await axios.get(route('customer-warehouse-locations.check', form.customer_code));
        customerCodeExists.value = response.data.exists;
        
        // Update the customer name only if it's currently empty
        if (!form.customer_name && response.data.customer_name) {
            form.customer_name = response.data.customer_name;
        }
        
        if (response.data.exists) {
            // If exists, fetch full data
            fetchCustomerWarehouseLocation();
        } else {
            isEditMode.value = false;
            form.id = null;
            form.lock_customer_location = false;
        }
    } catch (error) {
        console.error('Error checking customer code existence:', error);
        customerCodeExists.value = false;
        isEditMode.value = false;
        form.id = null;
        form.lock_customer_location = false;
    }
}, 500);

const fetchCustomerWarehouseLocation = async () => {
    if (!form.customer_code) return;
    
    // Store current customer name
    const currentCustomerName = form.customer_name;
    
    try {
        const response = await axios.get(route('customer-warehouse-locations.show', form.customer_code));
        const data = response.data.data;
        if (data) {
            form.id = data.id;
            // Don't overwrite customer_name if we already have it
            if (!currentCustomerName && data.customer_name) {
                form.customer_name = data.customer_name;
            }
            form.lock_customer_location = data.lock_customer_location;
            isEditMode.value = true;
            customerCodeExists.value = true;
        } else {
            form.id = null;
            form.lock_customer_location = false;
            isEditMode.value = false;
            customerCodeExists.value = false;
        }
    } catch (error) {
        console.error('Error fetching customer warehouse location:', error);
        form.id = null;
        form.lock_customer_location = false;
        isEditMode.value = false;
        customerCodeExists.value = false;
    }
};

const handleRecordAction = async () => {
    if (isEditMode.value) {
        // Load existing record (already done by checkCustomerCodeExists/fetchCustomerWarehouseLocation)
    } else {
        // Add new record - will be handled by saveCustomerWarehouseLocation
        // No specific action needed here beyond setting isEditMode to false if it somehow got set
        isEditMode.value = false;
    }
};

const saveCustomerWarehouseLocation = async () => {
    try {
        // Store current customer name
        const currentCustomerName = form.customer_name;
        
        let response;
        if (form.id) {
            // Update existing
            response = await axios.put(route('customer-warehouse-locations.update', form.customer_code), form);
        } else {
            // Create new
            response = await axios.post(route('customer-warehouse-locations.store'), form);
        }
        alert(response.data.message);
        
        // After saving, re-fetch to ensure latest data and correct mode
        // But preserve customer name
        await checkExistenceWithoutChangingName();
        
        // Make sure customer name is preserved
        form.customer_name = currentCustomerName;
    } catch (error) {
        console.error('Error saving customer warehouse location:', error);
        alert('Failed to save customer warehouse location.');
    }
};

const deleteCustomerWarehouseLocation = async () => {
    if (confirm('Are you sure you want to delete this customer warehouse location?')) {
        try {
            const response = await axios.delete(route('customer-warehouse-locations.destroy', form.customer_code));
            alert(response.data.message);
            
            // Clear form and reset state
            form.id = null;
            form.customer_code = '';
            form.customer_name = ''; // Explicitly clear customer name
            form.lock_customer_location = false;
            isEditMode.value = false;
            customerCodeExists.value = false;
        } catch (error) {
            console.error('Error deleting customer warehouse location:', error);
            alert('Failed to delete customer warehouse location.');
        }
    }
};

watch(() => form.customer_code, (newCode) => {
    if (newCode) {
        // Only update customer name if it's empty
        if (!form.customer_name) {
            checkCustomerCodeExists();
        } else {
            // If we already have a customer name, use the method that preserves it
            checkExistenceWithoutChangingName();
        }
    }
});

onMounted(() => {
    // Optionally fetch initial data if there's a default customer code or param
    // checkCustomerCodeExists(); 
});

</script>

<style scoped>
/* Add any specific styles here if needed */
.text-shadow {
    text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
}
.animate-ping-slow {
    animation: ping-slow 3s cubic-bezier(0, 0, 0.2, 1) infinite;
}
@keyframes ping-slow {
    0%, 100% {
        transform: scale(1);
        opacity: 0.7;
    }
    50% {
        transform: scale(1.5);
        opacity: 0;
    }
}
.animation-delay-500 {
    animation-delay: 0.5s;
}
</style> 