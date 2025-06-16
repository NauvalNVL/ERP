<template>
    <AppLayout :header="'Customer Finished Goods'">
        <Head title="Customer Finished Goods" />

        <!-- Header Section -->
        <div class="bg-gradient-to-r from-cyan-700 to-blue-600 p-6 rounded-t-lg shadow-lg">
            <h2 class="text-2xl font-bold text-white mb-2 flex items-center">
                <i class="fas fa-box-open mr-3"></i> Customer Finished Goods
            </h2>
            <p class="text-cyan-100">View and manage customer finished goods</p>
        </div>

        <div class="bg-white rounded-b-lg shadow-lg p-6 mb-6">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Main Content Area -->
                <div class="lg:col-span-3">
                    <div class="bg-white p-6 rounded-lg shadow-md border-t-4 border-blue-500">
                        <div class="flex items-center mb-6 pb-2 border-b border-gray-200">
                            <div class="p-2 bg-blue-500 rounded-lg mr-3">
                                <i class="fas fa-cubes text-white"></i>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-800">Finished Goods Details</h3>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            <!-- F/Goods Period -->
                            <div class="col-span-full flex items-center space-x-2">
                                <label class="text-sm font-medium text-gray-700">F/Goods Period:</label>
                                <input type="text" v-model="fgPeriodMonth" class="form-input w-16 text-center" placeholder="MM">
                                <input type="text" v-model="fgPeriodYear" class="form-input w-24 text-center" placeholder="YYYY">
                                <button type="button" @click="openDatePickerModal('fgPeriodMonth')" class="px-3 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300">
                                    <i class="fas fa-calendar-alt"></i>
                                </button>
                            </div>

                            <!-- Salesperson -->
                            <div class="col-span-full sm:col-span-1">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Salesperson:</label>
                                <div class="flex items-center space-x-2">
                                    <div class="relative flex-1 flex items-center rounded-md shadow-sm">
                                        <input type="text" v-model="salespersonFrom" class="flex-1 block w-full px-3 py-2 border border-gray-300 rounded-l-md focus:ring-blue-500 focus:border-blue-500">
                                        <button type="button" @click="openSalespersonModal('salespersonFrom')" class="inline-flex items-center px-3 py-2 border border-l-0 border-gray-300 bg-gray-200 text-gray-700 rounded-r-md hover:bg-gray-300">
                                            <i class="fas fa-table"></i>
                                        </button>
                                    </div>
                                    <span class="text-gray-700">to</span>
                                    <div class="relative flex-1 flex items-center rounded-md shadow-sm">
                                        <input type="text" v-model="salespersonTo" class="flex-1 block w-full px-3 py-2 border border-gray-300 rounded-l-md focus:ring-blue-500 focus:border-blue-500">
                                        <button type="button" @click="openSalespersonModal('salespersonTo')" class="inline-flex items-center px-3 py-2 border border-l-0 border-gray-300 bg-gray-200 text-gray-700 rounded-r-md hover:bg-gray-300">
                                            <i class="fas fa-table"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Customer -->
                            <div class="col-span-full sm:col-span-1">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Customer:</label>
                                <div class="flex items-center space-x-2">
                                    <div class="relative flex-1 flex items-center rounded-md shadow-sm">
                                        <input type="text" v-model="customerCodeFrom" class="flex-1 block w-full px-3 py-2 border border-gray-300 rounded-l-md focus:ring-blue-500 focus:border-blue-500">
                                        <button type="button" @click="openCustomerModal('customerCodeFrom')" class="inline-flex items-center px-3 py-2 border border-l-0 border-gray-300 bg-gray-200 text-gray-700 rounded-r-md hover:bg-gray-300">
                                            <i class="fas fa-table"></i>
                                        </button>
                                    </div>
                                    <span class="text-gray-700">to</span>
                                    <div class="relative flex-1 flex items-center rounded-md shadow-sm">
                                        <input type="text" v-model="customerCodeTo" class="flex-1 block w-full px-3 py-2 border border-gray-300 rounded-l-md focus:ring-blue-500 focus:border-blue-500">
                                        <button type="button" @click="openCustomerModal('customerCodeTo')" class="inline-flex items-center px-3 py-2 border border-l-0 border-gray-300 bg-gray-200 text-gray-700 rounded-r-md hover:bg-gray-300">
                                            <i class="fas fa-table"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- M/Card Seq# -->
                            <div class="col-span-full sm:col-span-1">
                                <label class="block text-sm font-medium text-gray-700 mb-1">M/Card Seq#:</label>
                                <div class="flex items-center space-x-2">
                                    <div class="relative flex-1 flex items-center rounded-md shadow-sm">
                                        <input type="text" v-model="mcSeqFrom" class="flex-1 block w-full px-3 py-2 border border-gray-300 rounded-l-md focus:ring-blue-500 focus:border-blue-500">
                                        <button type="button" @click="openMCMasterModal('mcSeqFrom')" class="inline-flex items-center px-3 py-2 border border-l-0 border-gray-300 bg-gray-200 text-gray-700 rounded-r-md hover:bg-gray-300">
                                            <i class="fas fa-table"></i>
                                        </button>
                                    </div>
                                    <span class="text-gray-700">to</span>
                                    <div class="relative flex-1 flex items-center rounded-md shadow-sm">
                                        <input type="text" v-model="mcSeqTo" class="flex-1 block w-full px-3 py-2 border border-gray-300 rounded-l-md focus:ring-blue-500 focus:border-blue-500">
                                        <button type="button" @click="openMCMasterModal('mcSeqTo')" class="inline-flex items-center px-3 py-2 border border-l-0 border-gray-300 bg-gray-200 text-gray-700 rounded-r-md hover:bg-gray-300">
                                            <i class="fas fa-table"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- M/Card Status Checkboxes -->
                            <div class="col-span-full">
                                <label class="block text-sm font-medium text-gray-700 mb-2">M/Card Status:</label>
                                <div class="flex flex-wrap items-center space-x-4">
                                    <label class="flex items-center">
                                        <input type="checkbox" v-model="mcStatus.active" class="form-checkbox h-4 w-4 text-blue-600">
                                        <span class="ml-2 text-gray-700">Active</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="checkbox" v-model="mcStatus.obsolete" class="form-checkbox h-4 w-4 text-blue-600">
                                        <span class="ml-2 text-gray-700">Obsolete</span>
                                    </label>
                                </div>
                            </div>

                            <!-- Proceed Button -->
                            <div class="col-span-full flex justify-end mt-6">
                                <button @click="handleProceed" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors shadow">
                                    Proceed
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>

    <!-- Modals for this page -->
    <CustomerFinishedGoodsModals ref="modalsRef" 
        @date-selected="updateDateInput"
        @salesperson-selected="updateSalespersonInput"
        @customer-selected="updateCustomerInput"
        @mc-selected="updateMCInput"
    />

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
import CustomerFinishedGoodsModals from '@/Components/CustomerFinishedGoodsModals.vue';

const fgPeriodMonth = ref('6');
const fgPeriodYear = ref('2025');

const salespersonFrom = ref('');
const salespersonTo = ref('');
const customerCodeFrom = ref('');
const customerCodeTo = ref('');
const mcSeqFrom = ref('');
const mcSeqTo = ref('');

const mcStatus = ref({
    active: true,
    obsolete: false,
});

const modalsRef = ref(null);
const notification = ref({
    show: false,
    message: '',
    type: 'success'
});

const openDatePickerModal = (targetInput) => {
    showNotification(`Opening date picker for: ${targetInput}`, 'info');
    modalsRef.value.openDatePickerModal(targetInput);
};

const updateDateInput = ({ target, date }) => {
    if (target === 'fgPeriodMonth') fgPeriodMonth.value = date.split('-')[1]; // Extract month
    if (target === 'fgPeriodYear') fgPeriodYear.value = date.split('-')[0]; // Extract year
};

const openSalespersonModal = (targetInput) => {
    showNotification(`Opening Salesperson modal for: ${targetInput}`, 'info');
    modalsRef.value.openSalespersonModal(targetInput);
};

const updateSalespersonInput = ({ target, salespersonCode }) => {
    if (target === 'salespersonFrom') salespersonFrom.value = salespersonCode;
    else if (target === 'salespersonTo') salespersonTo.value = salespersonCode;
};

const openCustomerModal = (targetInput) => {
    showNotification(`Opening Customer modal for: ${targetInput}`, 'info');
    modalsRef.value.openCustomerAccountSearchModal(targetInput);
};

const updateCustomerInput = ({ target, customerCode }) => {
    if (target === 'customerCodeFrom') customerCodeFrom.value = customerCode;
    else if (target === 'customerCodeTo') customerCodeTo.value = customerCode;
};

const openMCMasterModal = (targetInput) => {
    showNotification(`Opening MC Master modal for: ${targetInput}`, 'info');
    modalsRef.value.openMCMasterModal(targetInput);
};

const updateMCInput = ({ target, mcSeq }) => {
    if (target === 'mcSeqFrom') mcSeqFrom.value = mcSeq;
    else if (target === 'mcSeqTo') mcSeqTo.value = mcSeq;
};

const handleProceed = () => {
    console.log('Proceeding with Customer Finished Goods:', {
        fgPeriodMonth: fgPeriodMonth.value,
        fgPeriodYear: fgPeriodYear.value,
        salespersonFrom: salespersonFrom.value,
        salespersonTo: salespersonTo.value,
        customerCodeFrom: customerCodeFrom.value,
        customerCodeTo: customerCodeTo.value,
        mcSeqFrom: mcSeqFrom.value,
        mcSeqTo: mcSeqTo.value,
        mcStatus: mcStatus.value,
    });
    showNotification('Proceeding with Customer Finished Goods! (Dummy action)', 'success');
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
.form-input {
    @apply px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500;
}
.form-checkbox {
    @apply rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500;
}
</style>
