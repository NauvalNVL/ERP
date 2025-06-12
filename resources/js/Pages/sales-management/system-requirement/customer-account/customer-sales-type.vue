<template>
    <AppLayout :header="'Define Customer Sales Type'">
        <div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 p-6">
            <div class="max-w-7xl mx-auto">
                <!-- Header -->
                <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-gradient-to-r from-purple-500 to-indigo-600 rounded-lg flex items-center justify-center">
                                <i class="fas fa-tags text-white text-xl"></i>
                            </div>
                            <div>
                                <h1 class="text-2xl font-bold text-gray-800">Define Customer Sales Type</h1>
                                <p class="text-gray-600">Manage customer sales type settings</p>
                            </div>
                        </div>
                        <div class="flex space-x-3">
                            <button 
                                @click="refreshData"
                                class="flex items-center space-x-2 px-4 py-2 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-lg hover:from-green-600 hover:to-green-700 transition-all duration-200 shadow-md hover:shadow-lg"
                            >
                                <i class="fas fa-sync-alt"></i>
                                <span>Refresh</span>
                            </button>
                        </div>
                    </div>

                    <!-- Search Bar -->
                    <div class="relative">
                        <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        <input
                            type="text"
                            placeholder="Search customers..."
                            v-model="searchTerm"
                            class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                        />
                    </div>
                </div>

                <!-- Main Content -->
                <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                    <div class="p-6 border-b border-gray-200 flex justify-between items-center">
                        <h2 class="text-lg font-semibold text-gray-800">Customer Sales Type</h2>
                    </div>
                    
                    <!-- Customer Table -->
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">Customer Code & Name</th>
                                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">Sales Type</th>
                                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                <tr 
                                    v-for="customer in filteredCustomers" 
                                    :key="customer.customer_code"
                                    class="hover:bg-gray-50"
                                >
                                    <td class="px-6 py-4">
                                        <div class="font-medium text-gray-900">{{ customer.customer_code }}</div>
                                        <div class="text-gray-500">{{ customer.customer_name }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <input 
                                                type="text" 
                                                v-model="customer.sales_type" 
                                                class="border border-gray-300 rounded-md px-3 py-1 w-20 text-center"
                                                maxlength="2"
                                                @change="updateSalesType(customer)"
                                            />
                                            <button class="ml-2 bg-gray-200 hover:bg-gray-300 rounded-md p-1" @click="openSalesTypeSelector(customer)">
                                                <i class="fas fa-ellipsis-h text-gray-600"></i>
                                            </button>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <button 
                                            class="text-blue-600 hover:text-blue-800 mr-3"
                                            @click="saveCustomerSalesType(customer)"
                                        >
                                            <i class="fas fa-save"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr v-if="filteredCustomers.length === 0">
                                    <td colspan="3" class="px-6 py-8 text-center text-gray-500">
                                        No customers found. Please adjust your search criteria.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sales Type Selector Modal -->
        <div v-if="showSalesTypeModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg shadow-xl w-80">
                <div class="bg-gradient-to-r from-blue-600 to-blue-800 text-white px-4 py-3 rounded-t-lg flex justify-between items-center">
                    <h3 class="font-medium">Select Sales Type</h3>
                    <button @click="showSalesTypeModal = false" class="text-white hover:text-gray-200">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="p-4 space-y-2">
                    <button 
                        class="w-full text-left px-4 py-2 hover:bg-blue-50 rounded-md flex items-center"
                        @click="selectSalesType('LC')"
                    >
                        <span class="w-8">LC</span>
                        <span class="ml-2">Local</span>
                    </button>
                    <button 
                        class="w-full text-left px-4 py-2 hover:bg-blue-50 rounded-md flex items-center"
                        @click="selectSalesType('EX')"
                    >
                        <span class="w-8">EX</span>
                        <span class="ml-2">Export</span>
                    </button>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import { useToast } from '@/Composables/useToast';
import axios from 'axios';

// Props
const props = defineProps({
    customers: Array,
    salesTypes: Array
});

// State
const searchTerm = ref('');
const customersList = ref(props.customers || []);
const salesTypesList = ref(props.salesTypes || []);
const showSalesTypeModal = ref(false);
const selectedCustomer = ref(null);

// Toast
const { showToast } = useToast();

// Computed
const filteredCustomers = computed(() => {
    if (!searchTerm.value) {
        return customersList.value;
    }
    
    const searchLower = searchTerm.value.toLowerCase();
    return customersList.value.filter(customer => 
        customer.customer_code.toLowerCase().includes(searchLower) ||
        customer.customer_name.toLowerCase().includes(searchLower)
    );
});

// Methods
const refreshData = async () => {
    try {
        const response = await axios.get('/api/customer-accounts');
        customersList.value = response.data;
        showToast('Data refreshed successfully', 'success');
    } catch (error) {
        console.error('Error refreshing data:', error);
        showToast('Error refreshing data', 'error');
    }
};

const openSalesTypeSelector = (customer) => {
    selectedCustomer.value = customer;
    showSalesTypeModal.value = true;
};

const selectSalesType = (type) => {
    if (selectedCustomer.value) {
        selectedCustomer.value.sales_type = type;
        showSalesTypeModal.value = false;
    }
};

const saveCustomerSalesType = async (customer) => {
    try {
        // Check if this customer already has a sales type record
        const existingSalesType = salesTypesList.value.find(st => st.customer_code === customer.customer_code);
        
        if (existingSalesType) {
            // Update existing record
            await axios.put(`/api/customer-sales-types/${existingSalesType.id}`, {
                customer_code: customer.customer_code,
                customer_name: customer.customer_name,
                sales_type: customer.sales_type || 'LC'
            });
        } else {
            // Create new record
            await axios.post('/api/customer-sales-types', {
                customer_code: customer.customer_code,
                customer_name: customer.customer_name,
                sales_type: customer.sales_type || 'LC'
            });
        }
        
        showToast('Sales type saved successfully', 'success');
    } catch (error) {
        console.error('Error saving sales type:', error);
        showToast('Error saving sales type', 'error');
    }
};

const updateSalesType = (customer) => {
    // Ensure sales type is always uppercase
    if (customer.sales_type) {
        customer.sales_type = customer.sales_type.toUpperCase();
    }
};

// Load data on mount
onMounted(async () => {
    if (!customersList.value.length) {
        await refreshData();
    }
    
    try {
        const response = await axios.get('/api/customer-sales-types');
        salesTypesList.value = response.data;
        
        // Apply existing sales types to customers
        salesTypesList.value.forEach(salesType => {
            const customer = customersList.value.find(c => c.customer_code === salesType.customer_code);
            if (customer) {
                customer.sales_type = salesType.sales_type;
            }
        });
    } catch (error) {
        console.error('Error loading sales types:', error);
    }
});
</script>

<style scoped>
/* Add any component-specific styles here */
</style>