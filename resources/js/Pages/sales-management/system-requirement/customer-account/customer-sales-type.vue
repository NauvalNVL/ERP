<template>
    <AppLayout :header="'Define Customer Sales Type'">
                <!-- Header -->
        <div class="bg-gradient-to-r from-blue-600 via-cyan-600 to-teal-500 p-6 rounded-t-lg shadow-lg overflow-hidden relative mb-6">
            <div class="absolute top-0 right-0 w-40 h-40 bg-white opacity-5 rounded-full -translate-y-20 translate-x-20 animate-pulse-slow"></div>
            <div class="absolute bottom-0 left-0 w-20 h-20 bg-white opacity-5 rounded-full translate-y-10 -translate-x-10 animate-pulse-slow animation-delay-500"></div>
            <div class="flex items-center">
                <div class="bg-gradient-to-br from-purple-500 to-indigo-600 p-3 rounded-lg shadow-inner flex items-center justify-center mr-4">
                    <i class="fas fa-tags text-white text-2xl z-10"></i>
                            </div>
    <div>
                    <h2 class="text-2xl md:text-3xl font-bold text-white mb-1 text-shadow">Define Customer Sales Type</h2>
                    <p class="text-indigo-100">Kelola tipe penjualan customer dengan mudah</p>
                </div>
                            </div>
                        </div>
        <div class="max-w-7xl mx-auto">
            <!-- Card Container -->
            <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
                <!-- Action Buttons -->
                <div class="flex items-center space-x-3 mb-4">
                    <button @click="refreshData" class="action-btn bg-gradient-to-r from-green-500 to-green-600">
                                <i class="fas fa-sync-alt"></i>
                    </button>
                    <button @click="showSalesTypeModal = true" class="action-btn bg-gradient-to-r from-blue-500 to-cyan-500">
                        <i class="fas fa-search"></i>
                            </button>
                </div>
                <!-- Table -->
                <div class="overflow-x-auto rounded-lg border border-gray-100">
                    <table class="min-w-full text-sm">
                            <thead class="bg-gray-50">
                                <tr>
                                <th class="px-4 py-3 text-left font-semibold text-gray-600 w-1/2">
                                    <span class="inline-flex items-center">
                                        <i class="fas fa-id-card text-cyan-500 mr-2"></i>
                                        Customer Code & Name
                                    </span>
                                </th>
                                <th class="px-4 py-3 text-left font-semibold text-gray-600 w-1/4">
                                    <span class="inline-flex items-center">
                                        <i class="fas fa-tags text-purple-500 mr-2"></i>
                                        Sales Type
                                    </span>
                                </th>
                                <th class="px-4 py-3 text-left font-semibold text-gray-600 w-1/4">
                                    <span class="inline-flex items-center">
                                        <i class="fas fa-cogs text-green-500 mr-2"></i>
                                        Actions
                                    </span>
                                </th>
                                </tr>
                            </thead>
                        <tbody class="divide-y divide-gray-100">
                            <tr v-for="customer in filteredCustomers" :key="customer.customer_code" :class="{'bg-blue-50': selectedCustomer && selectedCustomer.customer_code === customer.customer_code, 'hover:bg-cyan-50': true}">
                                <td class="px-4 py-2 font-mono">
                                    <span class="font-bold text-blue-700">{{ customer.customer_code }}</span>
                                    <span class="ml-2 text-gray-700">{{ customer.customer_name }}</span>
                                    </td>
                                <td class="px-4 py-2">
                                    <input type="text" v-model="customer.sales_type" class="border border-gray-300 rounded-md px-3 py-1 w-20 text-center font-semibold" maxlength="2" @change="updateSalesType(customer)" />
                                    </td>
                                <td class="px-4 py-2">
                                    <button class="text-blue-600 hover:text-blue-800 mr-3" @click="saveCustomerSalesType(customer)">
                                            <i class="fas fa-save"></i>
                                        </button>
                                    <button class="text-gray-500 hover:text-gray-700" @click="openSalesTypeSelector(customer)">
                                        <i class="fas fa-table"></i>
                                    </button>
                                    </td>
                                </tr>
                                <tr v-if="filteredCustomers.length === 0">
                                <td colspan="3" class="px-4 py-8 text-center text-gray-400">No customers found. Please adjust your search criteria.</td>
                                </tr>
                            </tbody>
                        </table>
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
                        <button class="w-full text-left px-4 py-2 hover:bg-blue-50 rounded-md flex items-center" @click="selectSalesType('LC')">
                        <span class="w-8">LC</span>
                        <span class="ml-2">Local</span>
                    </button>
                        <button class="w-full text-left px-4 py-2 hover:bg-blue-50 rounded-md flex items-center" @click="selectSalesType('EX')">
                        <span class="w-8">EX</span>
                        <span class="ml-2">Export</span>
                    </button>
                    </div>
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
        const payload = response.data?.data ?? response.data ?? [];
        customersList.value = Array.isArray(payload) ? payload : [];
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
.text-shadow {
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}
.action-btn {
    @apply text-white px-4 py-2 rounded-lg shadow-md text-lg flex items-center justify-center transition-all duration-200 hover:scale-105 hover:shadow-xl;
}
.animate-pulse-slow {
    animation: fadeInUp 0.7s cubic-bezier(0.23, 1, 0.32, 1);
}
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(40px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>
