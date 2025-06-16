<template>
    <AppLayout :header="'Production Monitoring Board'">
        <Head title="Production Monitoring Board" />

        <!-- Header Section -->
        <div class="bg-gradient-to-r from-cyan-700 to-blue-600 p-6 rounded-t-lg shadow-lg">
            <h2 class="text-2xl font-bold text-white mb-2 flex items-center">
                <i class="fas fa-chart-line mr-3"></i> Production Monitoring Board
            </h2>
            <p class="text-cyan-100">Monitor production progress and status</p>
        </div>

        <div class="bg-white rounded-b-lg shadow-lg p-6 mb-6">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Main Content Area -->
                <div class="lg:col-span-3">
                    <div class="bg-white p-6 rounded-lg shadow-md border-t-4 border-blue-500">
                        <div class="flex items-center mb-6 pb-2 border-b border-gray-200">
                            <div class="p-2 bg-blue-500 rounded-lg mr-3">
                                <i class="fas fa-industry text-white"></i>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-800">Production Details</h3>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            <!-- WO Period -->
                            <div class="col-span-full flex items-center space-x-2">
                                <label class="text-sm font-medium text-gray-700">WO Period:</label>
                                <input type="text" v-model="woPeriodMonth" class="form-input w-16 text-center" placeholder="MM">
                                <input type="text" v-model="woPeriodYear" class="form-input w-24 text-center" placeholder="YYYY">
                            </div>

                            <!-- WO Due Date -->
                            <div class="col-span-full">
                                <label class="block text-sm font-medium text-gray-700 mb-1">WO Due Date:</label>
                                <div class="flex items-center space-x-2">
                                    <div class="relative flex-1 flex items-center rounded-md shadow-sm">
                                        <input type="text" v-model="woDueDateFrom" class="flex-1 block w-full px-3 py-2 border border-gray-300 rounded-l-md focus:ring-blue-500 focus:border-blue-500">
                                        <button type="button" @click="openDatePickerModal('woDueDateFrom')" class="inline-flex items-center px-3 py-2 border border-l-0 border-gray-300 bg-gray-200 text-gray-700 rounded-r-md hover:bg-gray-300">
                                            <i class="fas fa-calendar-alt"></i>
                                        </button>
                                    </div>
                                    <span class="text-gray-700">to</span>
                                    <div class="relative flex-1 flex items-center rounded-md shadow-sm">
                                        <input type="text" v-model="woDueDateTo" class="flex-1 block w-full px-3 py-2 border border-gray-300 rounded-l-md focus:ring-blue-500 focus:border-blue-500">
                                        <button type="button" @click="openDatePickerModal('woDueDateTo')" class="inline-flex items-center px-3 py-2 border border-l-0 border-gray-300 bg-gray-200 text-gray-700 rounded-r-md hover:bg-gray-300">
                                            <i class="fas fa-calendar-alt"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- AC# -->
                            <div class="col-span-full sm:col-span-1">
                                <label class="block text-sm font-medium text-gray-700 mb-1">AC#:</label>
                                <div class="flex items-center space-x-2">
                                    <div class="relative flex-1 flex items-center rounded-md shadow-sm">
                                        <input type="text" v-model="acFrom" class="flex-1 block w-full px-3 py-2 border border-gray-300 rounded-l-md focus:ring-blue-500 focus:border-blue-500">
                                        <button type="button" @click="openCustomerModal('acFrom')" class="inline-flex items-center px-3 py-2 border border-l-0 border-gray-300 bg-gray-200 text-gray-700 rounded-r-md hover:bg-gray-300">
                                            <i class="fas fa-table"></i>
                                        </button>
                                    </div>
                                    <span class="text-gray-700">to</span>
                                    <div class="relative flex-1 flex items-center rounded-md shadow-sm">
                                        <input type="text" v-model="acTo" class="flex-1 block w-full px-3 py-2 border border-gray-300 rounded-l-md focus:ring-blue-500 focus:border-blue-500">
                                        <button type="button" @click="openCustomerModal('acTo')" class="inline-flex items-center px-3 py-2 border border-l-0 border-gray-300 bg-gray-200 text-gray-700 rounded-r-md hover:bg-gray-300">
                                            <i class="fas fa-table"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- MCS# -->
                            <div class="col-span-full sm:col-span-1">
                                <label class="block text-sm font-medium text-gray-700 mb-1">MCS#:</label>
                                <div class="flex items-center space-x-2">
                                    <div class="relative flex-1 flex items-center rounded-md shadow-sm">
                                        <input type="text" v-model="mcsFrom" class="flex-1 block w-full px-3 py-2 border border-gray-300 rounded-l-md focus:ring-blue-500 focus:border-blue-500">
                                        <button type="button" @click="openMCMasterModal('mcsFrom')" class="inline-flex items-center px-3 py-2 border border-l-0 border-gray-300 bg-gray-200 text-gray-700 rounded-r-md hover:bg-gray-300">
                                            <i class="fas fa-table"></i>
                                        </button>
                                    </div>
                                    <span class="text-gray-700">to</span>
                                    <div class="relative flex-1 flex items-center rounded-md shadow-sm">
                                        <input type="text" v-model="mcsTo" class="flex-1 block w-full px-3 py-2 border border-gray-300 rounded-l-md focus:ring-blue-500 focus:border-blue-500">
                                        <button type="button" @click="openMCMasterModal('mcsTo')" class="inline-flex items-center px-3 py-2 border border-l-0 border-gray-300 bg-gray-200 text-gray-700 rounded-r-md hover:bg-gray-300">
                                            <i class="fas fa-table"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- PO# -->
                            <div class="col-span-full sm:col-span-1">
                                <label class="block text-sm font-medium text-gray-700 mb-1">PO#:</label>
                                <div class="flex items-center space-x-2">
                                    <div class="relative flex-1 flex items-center rounded-md shadow-sm">
                                        <input type="text" v-model="poFrom" class="flex-1 block w-full px-3 py-2 border border-gray-300 rounded-l-md focus:ring-blue-500 focus:border-blue-500">
                                        <button type="button" @click="openPORefModal('poFrom')" class="inline-flex items-center px-3 py-2 border border-l-0 border-gray-300 bg-gray-200 text-gray-700 rounded-r-md hover:bg-gray-300">
                                            <i class="fas fa-table"></i>
                                        </button>
                                    </div>
                                    <span class="text-gray-700">to</span>
                                    <div class="relative flex-1 flex items-center rounded-md shadow-sm">
                                        <input type="text" v-model="poTo" class="flex-1 block w-full px-3 py-2 border border-gray-300 rounded-l-md focus:ring-blue-500 focus:border-blue-500">
                                        <button type="button" @click="openPORefModal('poTo')" class="inline-flex items-center px-3 py-2 border border-l-0 border-gray-300 bg-gray-200 text-gray-700 rounded-r-md hover:bg-gray-300">
                                            <i class="fas fa-table"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- SLM -->
                            <div class="col-span-full sm:col-span-1">
                                <label class="block text-sm font-medium text-gray-700 mb-1">SLM:</label>
                                <div class="flex items-center space-x-2">
                                    <div class="relative flex-1 flex items-center rounded-md shadow-sm">
                                        <input type="text" v-model="slmFrom" class="flex-1 block w-full px-3 py-2 border border-gray-300 rounded-l-md focus:ring-blue-500 focus:border-blue-500">
                                        <button type="button" @click="openSalespersonModal('slmFrom')" class="inline-flex items-center px-3 py-2 border border-l-0 border-gray-300 bg-gray-200 text-gray-700 rounded-r-md hover:bg-gray-300">
                                            <i class="fas fa-table"></i>
                                        </button>
                                    </div>
                                    <span class="text-gray-700">to</span>
                                    <div class="relative flex-1 flex items-center rounded-md shadow-sm">
                                        <input type="text" v-model="slmTo" class="flex-1 block w-full px-3 py-2 border border-gray-300 rounded-l-md focus:ring-blue-500 focus:border-blue-500">
                                        <button type="button" @click="openSalespersonModal('slmTo')" class="inline-flex items-center px-3 py-2 border border-l-0 border-gray-300 bg-gray-200 text-gray-700 rounded-r-md hover:bg-gray-300">
                                            <i class="fas fa-table"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Order Type Checkboxes -->
                            <div class="col-span-full">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Order Type:</label>
                                <div class="flex flex-wrap items-center space-x-4">
                                    <label class="flex items-center">
                                        <input type="checkbox" v-model="orderType.sales" class="form-checkbox h-4 w-4 text-blue-600">
                                        <span class="ml-2 text-gray-700">Sales</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="checkbox" v-model="orderType.nonSales" class="form-checkbox h-4 w-4 text-blue-600">
                                        <span class="ml-2 text-gray-700">Non-Sales</span>
                                    </label>
                                </div>
                            </div>

                            <!-- WO Status Checkboxes -->
                            <div class="col-span-full">
                                <label class="block text-sm font-medium text-gray-700 mb-2">WO Status:</label>
                                <div class="flex flex-wrap items-center space-x-4">
                                    <label class="flex items-center">
                                        <input type="checkbox" v-model="woStatus.active" class="form-checkbox h-4 w-4 text-blue-600">
                                        <span class="ml-2 text-gray-700">A-Active</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="checkbox" v-model="woStatus.completed" class="form-checkbox h-4 w-4 text-blue-600">
                                        <span class="ml-2 text-gray-700">C-Completed</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="checkbox" v-model="woStatus.closed" class="form-checkbox h-4 w-4 text-blue-600">
                                        <span class="ml-2 text-gray-700">M-Closed</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="checkbox" v-model="woStatus.cancelled" class="form-checkbox h-4 w-4 text-blue-600">
                                        <span class="ml-2 text-gray-700">X-Cancelled</span>
                                    </label>
                                </div>
                            </div>

                            <!-- Refresh Result Radio Buttons -->
                            <div class="col-span-full">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Refresh Result:</label>
                                <div class="flex items-center space-x-4">
                                    <label class="flex items-center">
                                        <input type="radio" v-model="refreshResult" value="1" class="form-radio h-4 w-4 text-blue-600">
                                        <span class="ml-2 text-gray-700">1 Min</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="radio" v-model="refreshResult" value="2" class="form-radio h-4 w-4 text-blue-600">
                                        <span class="ml-2 text-gray-700">2 Min</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="radio" v-model="refreshResult" value="3" class="form-radio h-4 w-4 text-blue-600">
                                        <span class="ml-2 text-gray-700">3 Min</span>
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
    <ProductionMonitoringBoardModals ref="modalsRef"
        @date-selected="updateDateInput"
        @customer-selected="updateACInput"
        @mc-selected="updateMCSInput"
        @po-ref-selected="updatePOInput"
        @salesperson-selected="updateSLMInput"
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
import ProductionMonitoringBoardModals from '@/Components/ProductionMonitoringBoardModals.vue';

const woPeriodMonth = ref('6');
const woPeriodYear = ref('2025');
const woDueDateFrom = ref('00/00/0000');
const woDueDateTo = ref('00/00/0000');
const acFrom = ref('');
const acTo = ref('');
const mcsFrom = ref('');
const mcsTo = ref('');
const poFrom = ref('');
const poTo = ref('');
const slmFrom = ref('');
const slmTo = ref('');

const orderType = ref({
    sales: true,
    nonSales: true,
});

const woStatus = ref({
    active: true,
    completed: true,
    closed: true,
    cancelled: false,
});

const refreshResult = ref('1');

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
    if (target === 'woDueDateFrom') woDueDateFrom.value = date;
    else if (target === 'woDueDateTo') woDueDateTo.value = date;
};

const openCustomerModal = (targetInput) => {
    showNotification(`Opening Customer Account modal for: ${targetInput}`, 'info');
    modalsRef.value.openCustomerAccountSearchModal(targetInput);
};

const updateACInput = ({ target, customerCode }) => {
    if (target === 'acFrom') acFrom.value = customerCode;
    else if (target === 'acTo') acTo.value = customerCode;
};

const openMCMasterModal = (targetInput) => {
    showNotification(`Opening MC Master modal for: ${targetInput}`, 'info');
    modalsRef.value.openMCMasterModal(targetInput);
};

const updateMCSInput = ({ target, mcSeq }) => {
    if (target === 'mcsFrom') mcsFrom.value = mcSeq;
    else if (target === 'mcsTo') mcsTo.value = mcSeq;
};

const openPORefModal = (targetInput) => {
    showNotification(`Opening PO Ref modal for: ${targetInput}`, 'info');
    modalsRef.value.openPORefModal(targetInput);
};

const updatePOInput = ({ target, poRef }) => {
    if (target === 'poFrom') poFrom.value = poRef;
    else if (target === 'poTo') poTo.value = poRef;
};

const openSalespersonModal = (targetInput) => {
    showNotification(`Opening SLM modal for: ${targetInput}`, 'info');
    modalsRef.value.openSalespersonModal(targetInput);
};

const updateSLMInput = ({ target, salespersonCode }) => {
    if (target === 'slmFrom') slmFrom.value = salespersonCode;
    else if (target === 'slmTo') slmTo.value = salespersonCode;
};

const handleProceed = () => {
    console.log('Proceeding with Production Monitoring Board:', {
        woPeriodMonth: woPeriodMonth.value,
        woPeriodYear: woPeriodYear.value,
        woDueDateFrom: woDueDateFrom.value,
        woDueDateTo: woDueDateTo.value,
        acFrom: acFrom.value,
        acTo: acTo.value,
        mcsFrom: mcsFrom.value,
        mcsTo: mcsTo.value,
        poFrom: poFrom.value,
        poTo: poTo.value,
        slmFrom: slmFrom.value,
        slmTo: slmTo.value,
        orderType: orderType.value,
        woStatus: woStatus.value,
        refreshResult: refreshResult.value,
    });
    showNotification('Proceeding with Production Monitoring Board! (Dummy action)', 'success');
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
.form-radio {
    @apply rounded-full border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500;
}
</style>