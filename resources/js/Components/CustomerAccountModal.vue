<template>
    <Modal :show="show" :max-width="'2xl'" @close="closeModal">
        <!-- Modal Header Section -->
        <div class="bg-gradient-to-r from-purple-600 via-indigo-600 to-blue-600 p-4 sm:p-6 rounded-t-lg shadow-lg overflow-hidden relative">
            <!-- Decorative Elements (simplified) -->
            <div class="absolute top-0 right-0 w-20 h-20 bg-white opacity-5 rounded-full -translate-y-10 translate-x-10"></div>
            <div class="absolute bottom-0 left-0 w-12 h-12 bg-white opacity-5 rounded-full translate-y-5 -translate-x-5"></div>
            
            <div class="flex items-center">
                <div class="bg-gradient-to-br from-pink-500 to-purple-600 p-2 rounded-lg shadow-inner flex items-center justify-center relative overflow-hidden mr-3">
                    <i class="fas fa-user-friends text-white text-xl z-10"></i>
                </div>
                <div>
                    <h2 class="text-xl sm:text-2xl font-bold text-white mb-1 text-shadow">Select Customer Account</h2>
                    <p class="text-blue-100 text-sm">Choose a customer from the list below</p>
                </div>
            </div>
        </div>

        <div class="p-6 bg-white rounded-b-lg">
            <!-- Search and Filter Section -->
            <div class="mb-4 flex flex-col sm:flex-row items-center space-y-3 sm:space-y-0 sm:space-x-4">
                <input
                    type="text"
                    v-model="searchQuery"
                    @input="debouncedSearch"
                    placeholder="Search by code or name..."
                    class="flex-1 w-full sm:w-auto rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                />
                
                <!-- Sort By Options -->
                <div class="flex items-center space-x-2">
                    <span class="text-sm font-medium text-gray-700">Sort by:</span>
                    <label class="inline-flex items-center">
                        <input type="radio" class="form-radio text-indigo-600" value="customer_code" v-model="sortBy" @change="fetchCustomers">
                        <span class="ml-2 text-sm text-gray-700">Customer Code</span>
                    </label>
                    <label class="inline-flex items-center">
                        <input type="radio" class="form-radio text-indigo-600" value="customer_name" v-model="sortBy" @change="fetchCustomers">
                        <span class="ml-2 text-sm text-gray-700">Customer Name</span>
                    </label>
                </div>

                <!-- Record Status Options -->
                <div class="flex items-center space-x-2">
                    <span class="text-sm font-medium text-gray-700">Status:</span>
                    <label class="inline-flex items-center">
                        <input type="checkbox" class="form-checkbox text-indigo-600 rounded" v-model="filterStatus.active" @change="fetchCustomers">
                        <span class="ml-2 text-sm text-gray-700">Active</span>
                    </label>
                    <label class="inline-flex items-center">
                        <input type="checkbox" class="form-checkbox text-indigo-600 rounded" v-model="filterStatus.obsolete" @change="fetchCustomers">
                        <span class="ml-2 text-sm text-gray-700">Obsolete</span>
                    </label>
                </div>
            </div>

            <!-- Customer Account Table -->
            <div class="overflow-x-auto shadow-sm ring-1 ring-black ring-opacity-5 md:rounded-lg mb-4">
                <table class="min-w-full divide-y divide-gray-300">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">Customer Code</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Customer Name</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">S/person</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">AC Type</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Currency</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        <tr v-if="customers.length === 0">
                            <td colspan="6" class="text-center py-4 text-sm text-gray-500">No customer accounts found.</td>
                        </tr>
                        <tr 
                            v-for="customer in customers" 
                            :key="customer.id" 
                            @click="selectCustomer(customer)"
                            class="cursor-pointer hover:bg-indigo-50"
                        >
                            <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6">{{ customer.customer_code }}</td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ customer.customer_name }}</td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ customer.salesperson_code }}</td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ customer.account_type }}</td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ customer.currency_code }}</td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ customer.status }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="flex justify-end mt-4">
                <button 
                    @click="closeModal" 
                    class="ml-3 bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-md shadow-sm transition duration-150 ease-in-out"
                >
                    Exit
                </button>
            </div>
        </div>
    </Modal>
</template>

<script setup>
import { ref, watch, onMounted, reactive } from 'vue';
import Modal from '@/Components/Modal.vue';
import axios from 'axios';
import debounce from 'lodash/debounce';

const props = defineProps({
    show: Boolean,
});

const emit = defineEmits(['close', 'customer-selected']);

const customers = ref([]);
const searchQuery = ref('');
const sortBy = ref('customer_code'); // Default sort
const filterStatus = reactive({ active: true, obsolete: false });

const fetchCustomers = async () => {
    try {
        const response = await axios.get(route('customer-accounts.index'), {
            params: {
                search: searchQuery.value,
                sort_by: sortBy.value,
                filter_active: filterStatus.active,
                filter_obsolete: filterStatus.obsolete,
            }
        });
        customers.value = response.data.data; // Assuming API returns { data: [...] }
    } catch (error) {
        console.error('Error fetching customer accounts:', error);
        customers.value = [];
    }
};

const debouncedSearch = debounce(() => {
    fetchCustomers();
}, 300);

const selectCustomer = (customer) => {
    emit('customer-selected', customer);
};

const closeModal = () => {
    emit('close');
};

watch(() => props.show, (newValue) => {
    if (newValue) {
        fetchCustomers();
    }
});

onMounted(() => {
    fetchCustomers();
});
</script> 