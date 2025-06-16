<template>
    <AppLayout :header="'Customer Account Credit'">
        <Head title="Customer Account Credit" />

        <!-- Header Section -->
        <div class="bg-gradient-to-r from-cyan-700 to-blue-600 p-6 rounded-t-lg shadow-lg">
            <h2 class="text-2xl font-bold text-white mb-2 flex items-center">
                <i class="fas fa-credit-card mr-3"></i> Customer Account Credit
            </h2>
            <p class="text-cyan-100">Manage customer account credit status</p>
        </div>

        <div class="bg-white rounded-b-lg shadow-lg p-6 mb-6">
            <div class="grid grid-cols-1 gap-8">
                <!-- Main Content Area -->
                <div class="bg-white p-6 rounded-lg shadow-md border-t-4 border-blue-500">
                    <div class="flex items-center mb-6 pb-2 border-b border-gray-200">
                        <div class="p-2 bg-blue-500 rounded-lg mr-3">
                            <i class="fas fa-search text-white"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-800">Search Customer Account</h3>
                    </div>
                    
                    <div class="space-y-6">
                        <!-- Customer Code Input -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Customer Code:</label>
                            <div class="flex items-center space-x-2">
                                <div class="relative flex-1 flex items-center rounded-md shadow-sm">
                                    <input type="text" v-model="customerCodeFrom" class="flex-1 block w-full px-3 py-2 border border-gray-300 rounded-l-md focus:ring-blue-500 focus:border-blue-500">
                                    <button type="button" @click="openCustomerAccountSearchModal('customerCodeFrom')" class="inline-flex items-center px-3 py-2 border border-l-0 border-gray-300 bg-gray-200 text-gray-700 rounded-r-md hover:bg-gray-300">
                                        <i class="fas fa-table"></i>
                                    </button>
                                </div>
                                <span class="text-gray-700">to</span>
                                <div class="relative flex-1 flex items-center rounded-md shadow-sm">
                                    <input type="text" v-model="customerCodeTo" class="flex-1 block w-full px-3 py-2 border border-gray-300 rounded-l-md focus:ring-blue-500 focus:border-blue-500">
                                    <button type="button" @click="openCustomerAccountSearchModal('customerCodeTo')" class="inline-flex items-center px-3 py-2 border border-l-0 border-gray-300 bg-gray-200 text-gray-700 rounded-r-md hover:bg-gray-300">
                                        <i class="fas fa-table"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- AC Status Checkboxes -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">AC Status:</label>
                            <div class="flex items-center space-x-4">
                                <label class="flex items-center">
                                    <input type="checkbox" v-model="acStatus.active" class="form-checkbox h-4 w-4 text-blue-600">
                                    <span class="ml-2 text-gray-700">Active</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" v-model="acStatus.obsolete" class="form-checkbox h-4 w-4 text-blue-600">
                                    <span class="ml-2 text-gray-700">Obsolete</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Proceed Button -->
                    <div class="mt-8 flex justify-end">
                        <button @click="handleProceed" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors shadow">
                            Proceed
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>

    <!-- Customer Account Search Modal -->
    <CustomerServiceModals ref="modalsRef" />

    <!-- Notification Toast -->
    <div v-if="notification.show" class="fixed bottom-4 right-4 z-50 shadow-xl rounded-lg transition-all duration-300"
         :class="{
             'bg-green-100 border-l-4 border-green-500': notification.type === 'success',
             'bg-red-100 border-l-4 border-red-500': notification.type === 'error',
             'bg-yellow-100 border-l-4 border-yellow-500': notification.type === 'warning'
         }">
        <div class="p-4 flex items-center">
            <div class="mr-3">
                <i v-if="notification.type === 'success'" class="fas fa-check-circle text-green-500 text-xl"></i>
                <i v-else-if="notification.type === 'error'" class="fas fa-exclamation-circle text-red-500 text-xl"></i>
                <i v-else class="fas fa-exclamation-triangle text-yellow-500 text-xl"></i>
            </div>
            <div>
                <p :class="{
                    'text-green-800': notification.type === 'success',
                    'text-red-800': notification.type === 'error',
                    'text-yellow-800': notification.type === 'warning'
                }">{{ notification.message }}</p>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import CustomerServiceModals from '@/Components/CustomerServiceModals.vue';

const customerCodeFrom = ref('');
const customerCodeTo = ref('');
const acStatus = ref({
    active: true,
    obsolete: false,
});

const modalsRef = ref(null); // Ref to access methods from the child component

const notification = ref({
    show: false,
    message: '',
    type: 'success'
});

const openCustomerAccountSearchModal = (targetInput) => {
    modalsRef.value.openCustomerAccountSearchModal(targetInput);
};

const handleProceed = () => {
    // Implement your logic for proceeding with the search/report here
    // You can access customerCodeFrom.value, customerCodeTo.value, acStatus.value.active, acStatus.value.obsolete
    console.log('Proceeding with search:', {
        from: customerCodeFrom.value,
        to: customerCodeTo.value,
        active: acStatus.value.active,
        obsolete: acStatus.value.obsolete,
    });
    showNotification('Search initiated! (Dummy action)', 'success');
};

const showNotification = (message, type) => {
    notification.value.show = true;
    notification.value.message = message;
    notification.value.type = type;

    setTimeout(() => {
        notification.value.show = false;
    }, 3000);
};
</script>

<style scoped>
/* Add any specific styles for this page here */
.form-checkbox {
    @apply rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500;
}
.form-input {
    @apply px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500;
}
</style> 