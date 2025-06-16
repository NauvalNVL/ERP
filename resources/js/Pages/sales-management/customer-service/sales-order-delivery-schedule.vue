<template>
    <AppLayout :header="'Sales Order Delivery Schedule'">
        <Head title="Sales Order Delivery Schedule" />

        <!-- Header Section -->
        <div class="bg-gradient-to-r from-cyan-700 to-blue-600 p-6 rounded-t-lg shadow-lg">
            <h2 class="text-2xl font-bold text-white mb-2 flex items-center">
                <i class="fas fa-truck-loading mr-3"></i> Sales Order Delivery Schedule
            </h2>
            <p class="text-cyan-100">Manage sales order delivery schedules</p>
        </div>

        <div class="bg-white rounded-b-lg shadow-lg p-6 mb-6">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Main Content Area -->
                <div class="lg:col-span-3">
                    <div class="bg-white p-6 rounded-lg shadow-md border-t-4 border-blue-500">
                        <div class="flex items-center mb-6 pb-2 border-b border-gray-200">
                            <div class="p-2 bg-blue-500 rounded-lg mr-3">
                                <i class="fas fa-calendar-alt text-white"></i>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-800">Delivery Schedule Details</h3>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            <!-- Current SO Period & FG Period -->
                            <div class="col-span-full flex space-x-6">
                                <div class="flex items-center space-x-2">
                                    <label class="text-sm font-medium text-gray-700">Current SO Period:</label>
                                    <input type="text" v-model="currentSOPeriod" class="form-input w-24 bg-gray-100" readonly>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <label class="text-sm font-medium text-gray-700">Current FG Period:</label>
                                    <input type="text" v-model="currentFGPeriod" class="form-input w-24 bg-gray-100" readonly>
                                </div>
                            </div>

                            <!-- Delivery Schedule Inputs -->
                            <div class="col-span-full">
                                <fieldset class="border border-gray-300 p-4 rounded-lg space-y-4">
                                    <legend class="text-md font-semibold text-gray-800 px-2">Delivery Schedule</legend>
                                    
                                    <!-- Due Date -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Due Date:</label>
                                        <div class="flex items-center space-x-2">
                                            <div class="relative flex-1 flex items-center rounded-md shadow-sm">
                                                <input type="text" v-model="dueDateFrom" class="flex-1 block w-full px-3 py-2 border border-gray-300 rounded-l-md focus:ring-blue-500 focus:border-blue-500">
                                                <button type="button" @click="openDateModal('dueDateFrom')" class="inline-flex items-center px-3 py-2 border border-l-0 border-gray-300 bg-gray-200 text-gray-700 rounded-r-md hover:bg-gray-300">
                                                    <i class="fas fa-calendar-alt"></i>
                                                </button>
                                            </div>
                                            <span class="text-gray-700">to</span>
                                            <div class="relative flex-1 flex items-center rounded-md shadow-sm">
                                                <input type="text" v-model="dueDateTo" class="flex-1 block w-full px-3 py-2 border border-gray-300 rounded-l-md focus:ring-blue-500 focus:border-blue-500">
                                                <button type="button" @click="openDateModal('dueDateTo')" class="inline-flex items-center px-3 py-2 border border-l-0 border-gray-300 bg-gray-200 text-gray-700 rounded-r-md hover:bg-gray-300">
                                                    <i class="fas fa-calendar-alt"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Due Time -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Due Time:</label>
                                        <div class="flex items-center space-x-2">
                                            <input type="text" v-model="dueTime.hourFrom" class="form-input w-16 text-center" placeholder="HH">
                                            <span class="text-gray-700">:</span>
                                            <input type="text" v-model="dueTime.minuteFrom" class="form-input w-16 text-center" placeholder="MM">
                                            <span class="text-gray-700">to</span>
                                            <input type="text" v-model="dueTime.hourTo" class="form-input w-16 text-center" placeholder="HH">
                                            <span class="text-gray-700">:</span>
                                            <input type="text" v-model="dueTime.minuteTo" class="form-input w-16 text-center" placeholder="MM">
                                            <span class="text-gray-700">hh:mm</span>
                                        </div>
                                    </div>

                                    <!-- Due Flag -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Due Flag:</label>
                                        <div class="flex items-center space-x-4">
                                            <label class="flex items-center">
                                                <input type="checkbox" v-model="dueFlag.etdDeliveryDate" class="form-checkbox h-4 w-4 text-blue-600">
                                                <span class="ml-2 text-gray-700">ETD-Delivery Date</span>
                                            </label>
                                            <label class="flex items-center">
                                                <input type="checkbox" v-model="dueFlag.etaArrivalDate" class="form-checkbox h-4 w-4 text-blue-600">
                                                <span class="ml-2 text-gray-700">ETA-Arrival Date</span>
                                            </label>
                                            <label class="flex items-center">
                                                <input type="checkbox" v-model="dueFlag.tbaToBeAdvised" class="form-checkbox h-4 w-4 text-blue-600">
                                                <span class="ml-2 text-gray-700">TBA-To Be Advised</span>
                                            </label>
                                        </div>
                                    </div>

                                    <!-- Schedule Date -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Schedule Date:</label>
                                        <div class="flex items-center space-x-2">
                                            <input type="text" v-model="scheduleDate" class="form-input w-36 bg-gray-100" readonly>
                                            <button type="button" @click="openDateModal('scheduleDate')" class="px-3 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300">
                                                <i class="fas fa-calendar-alt"></i>
                                            </button>
                                            <span class="text-gray-700">{{ dayOfWeek }}</span>
                                        </div>
                                    </div>
                                </fieldset>
                            </div>
                            
                            <!-- Customer Code -->
                            <div class="col-span-full sm:col-span-1">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Customer Code:</label>
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

                            <!-- MC Seq# -->
                            <div class="col-span-full sm:col-span-1">
                                <label class="block text-sm font-medium text-gray-700 mb-1">MC Seq#:</label>
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

                            <!-- PO Ref# -->
                            <div class="col-span-full sm:col-span-1">
                                <label class="block text-sm font-medium text-gray-700 mb-1">PO Ref#:</label>
                                <div class="flex items-center space-x-2">
                                    <div class="relative flex-1 flex items-center rounded-md shadow-sm">
                                        <input type="text" v-model="poRefFrom" class="flex-1 block w-full px-3 py-2 border border-gray-300 rounded-l-md focus:ring-blue-500 focus:border-blue-500">
                                        <button type="button" @click="openPORefModal('poRefFrom')" class="inline-flex items-center px-3 py-2 border border-l-0 border-gray-300 bg-gray-200 text-gray-700 rounded-r-md hover:bg-gray-300">
                                            <i class="fas fa-table"></i>
                                        </button>
                                    </div>
                                    <span class="text-gray-700">to</span>
                                    <div class="relative flex-1 flex items-center rounded-md shadow-sm">
                                        <input type="text" v-model="poRefTo" class="flex-1 block w-full px-3 py-2 border border-gray-300 rounded-l-md focus:ring-blue-500 focus:border-blue-500">
                                        <button type="button" @click="openPORefModal('poRefTo')" class="inline-flex items-center px-3 py-2 border border-l-0 border-gray-300 bg-gray-200 text-gray-700 rounded-r-md hover:bg-gray-300">
                                            <i class="fas fa-table"></i>
                                        </button>
                                    </div>
                                </div>
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

                            <!-- User ID -->
                            <div class="col-span-full sm:col-span-1">
                                <label class="block text-sm font-medium text-gray-700 mb-1">User ID:</label>
                                <div class="flex items-center space-x-2">
                                    <div class="relative flex-1 flex items-center rounded-md shadow-sm">
                                        <input type="text" v-model="userIDFrom" class="flex-1 block w-full px-3 py-2 border border-gray-300 rounded-l-md focus:ring-blue-500 focus:border-blue-500">
                                        <button type="button" @click="openUserModal('userIDFrom')" class="inline-flex items-center px-3 py-2 border border-l-0 border-gray-300 bg-gray-200 text-gray-700 rounded-r-md hover:bg-gray-300">
                                            <i class="fas fa-table"></i>
                                        </button>
                                    </div>
                                    <span class="text-gray-700">to</span>
                                    <div class="relative flex-1 flex items-center rounded-md shadow-sm">
                                        <input type="text" v-model="userIDTo" class="flex-1 block w-full px-3 py-2 border border-gray-300 rounded-l-md focus:ring-blue-500 focus:border-blue-500">
                                        <button type="button" @click="openUserModal('userIDTo')" class="inline-flex items-center px-3 py-2 border border-l-0 border-gray-300 bg-gray-200 text-gray-700 rounded-r-md hover:bg-gray-300">
                                            <i class="fas fa-table"></i>
                                        </button>
                                    </div>
                                    <span class="text-gray-700">User Issue SO</span>
                                </div>
                            </div>

                            <!-- Area Group -->
                            <div class="col-span-full sm:col-span-1">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Area Group:</label>
                                <div class="flex items-center space-x-2">
                                    <div class="relative flex-1 flex items-center rounded-md shadow-sm">
                                        <input type="text" v-model="areaGroupFrom" class="flex-1 block w-full px-3 py-2 border border-gray-300 rounded-l-md focus:ring-blue-500 focus:border-blue-500">
                                        <button type="button" @click="openAreaGroupModal('areaGroupFrom')" class="inline-flex items-center px-3 py-2 border border-l-0 border-gray-300 bg-gray-200 text-gray-700 rounded-r-md hover:bg-gray-300">
                                            <i class="fas fa-table"></i>
                                        </button>
                                    </div>
                                    <span class="text-gray-700">to</span>
                                    <div class="relative flex-1 flex items-center rounded-md shadow-sm">
                                        <input type="text" v-model="areaGroupTo" class="flex-1 block w-full px-3 py-2 border border-gray-300 rounded-l-md focus:ring-blue-500 focus:border-blue-500">
                                        <button type="button" @click="openAreaGroupModal('areaGroupTo')" class="inline-flex items-center px-3 py-2 border border-l-0 border-gray-300 bg-gray-200 text-gray-700 rounded-r-md hover:bg-gray-300">
                                            <i class="fas fa-table"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- SO Status Checkboxes -->
                            <div class="col-span-full">
                                <label class="block text-sm font-medium text-gray-700 mb-2">SO Status:</label>
                                <div class="flex flex-wrap items-center space-x-4">
                                    <label class="flex items-center">
                                        <input type="checkbox" v-model="soStatus.outstanding" class="form-checkbox h-4 w-4 text-blue-600">
                                        <span class="ml-2 text-gray-700">O-Outstanding</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="checkbox" v-model="soStatus.partial" class="form-checkbox h-4 w-4 text-blue-600">
                                        <span class="ml-2 text-gray-700">P-Partial</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="checkbox" v-model="soStatus.completed" class="form-checkbox h-4 w-4 text-blue-600">
                                        <span class="ml-2 text-gray-700">C-Completed</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="checkbox" v-model="soStatus.closed" class="form-checkbox h-4 w-4 text-blue-600">
                                        <span class="ml-2 text-gray-700">M-Closed</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="checkbox" v-model="soStatus.cancelled" class="form-checkbox h-4 w-4 text-blue-600">
                                        <span class="ml-2 text-gray-700">X-Cancelled</span>
                                    </label>
                                </div>
                            </div>

                            <!-- Order Group Checkboxes -->
                            <div class="col-span-full">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Order Group:</label>
                                <div class="flex flex-wrap items-center space-x-4">
                                    <label class="flex items-center">
                                        <input type="checkbox" v-model="orderGroup.sales" class="form-checkbox h-4 w-4 text-blue-600">
                                        <span class="ml-2 text-gray-700">S-Sales</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="checkbox" v-model="orderGroup.nonSales" class="form-checkbox h-4 w-4 text-blue-600">
                                        <span class="ml-2 text-gray-700">N-Non-Sales</span>
                                    </label>
                                </div>
                            </div>

                             <!-- Product Button -->
                            <div class="col-span-full flex justify-start">
                                <button @click="showNotification('Product button clicked!', 'info')" class="px-6 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors shadow">
                                    Product
                                </button>
                            </div>

                            <!-- Process Table -->
                            <div class="col-span-full mt-6">
                                <div class="border border-gray-300 rounded-lg overflow-hidden">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                                <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Process</th>
                                                <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Compute</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            <tr v-for="(item, index) in processTableData" :key="index">
                                                <td class="px-3 py-2 whitespace-nowrap text-sm font-medium text-gray-900">{{ item.type }}</td>
                                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900">{{ item.process }}</td>
                                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900">{{ item.compute }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
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
    <SalesOrderDeliveryScheduleModals ref="modalsRef" @date-selected="updateDateInput" @customer-selected="updateCustomerInput" />

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
import { ref, computed } from 'vue';
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import SalesOrderDeliveryScheduleModals from '@/Components/SalesOrderDeliveryScheduleModals.vue';

const currentSOPeriod = ref('6 2025');
const currentFGPeriod = ref('6 2025');

const dueDateFrom = ref('00/00/0000');
const dueDateTo = ref('00/00/0000');
const dueTime = ref({
    hourFrom: '0', minuteFrom: '0',
    hourTo: '0', minuteTo: '0',
});
const dueFlag = ref({
    etdDeliveryDate: true,
    etaArrivalDate: true,
    tbaToBeAdvised: true,
});
const scheduleDate = ref('09/06/2025');

const customerCodeFrom = ref('');
const customerCodeTo = ref('');
const mcSeqFrom = ref('');
const mcSeqTo = ref('');
const poRefFrom = ref('');
const poRefTo = ref('');
const salespersonFrom = ref('');
const salespersonTo = ref('');
const userIDFrom = ref('');
const userIDTo = ref('');
const areaGroupFrom = ref('');
const areaGroupTo = ref('');

const soStatus = ref({
    outstanding: true,
    partial: true,
    completed: false,
    closed: false,
    cancelled: false,
});

const orderGroup = ref({
    sales: true,
    nonSales: true,
});

const processTableData = ref([
    { type: 'S1 - Sales', process: 'SO > CORR > CONV > FG > DO > IV', compute: 'Yes' },
    { type: 'S2 - Sales', process: 'SO > DO > IV (KANBAN)', compute: 'Yes' },
    { type: 'S3 - Sales', process: 'SO > CONV > FG > DO > IV', compute: 'Yes' },
    { type: 'N1 - Non Sales', process: 'SO > CORR > CONV > FG > DO', compute: 'Yes' },
    { type: 'N2 - Non Sales', process: 'SO > DO', compute: 'Yes' },
    { type: 'N3 - Non Sales', process: 'SO > CORR > CONV > FG', compute: 'No' },
    { type: 'N4 - Non Sales', process: 'SO > CORR', compute: 'No' },
]);

const modalsRef = ref(null);
const notification = ref({
    show: false,
    message: '',
    type: 'success'
});

const dayOfWeek = computed(() => {
    const date = new Date(scheduleDate.value);
    return isNaN(date) ? '' : date.toLocaleString('en-US', { weekday: 'short' });
});

const openDateModal = (targetInput) => {
    showNotification(`Opening date modal for: ${targetInput}`, 'info');
    // In a real application, you would call a function on modalsRef to open the date picker modal.
    // modalsRef.value.openDatePickerModal(targetInput);
};

const updateDateInput = ({ target, date }) => {
    if (target === 'dueDateFrom') dueDateFrom.value = date;
    else if (target === 'dueDateTo') dueDateTo.value = date;
    else if (target === 'scheduleDate') scheduleDate.value = date;
};

const openCustomerModal = (targetInput) => {
    showNotification(`Opening customer modal for: ${targetInput}`, 'info');
    modalsRef.value.openCustomerAccountSearchModal(targetInput);
};

const openMCMasterModal = (targetInput) => {
    showNotification(`Opening MC Master modal for: ${targetInput}`, 'info');
    // In a real application, you would call a function on modalsRef to open the MC Master modal.
};

const openPORefModal = (targetInput) => {
    showNotification(`Opening PO Ref modal for: ${targetInput}`, 'info');
    // In a real application, you would call a function on modalsRef to open the PO Ref modal.
};

const openSalespersonModal = (targetInput) => {
    showNotification(`Opening Salesperson modal for: ${targetInput}`, 'info');
    // In a real application, you would call a function on modalsRef to open the Salesperson modal.
};

const openUserModal = (targetInput) => {
    showNotification(`Opening User modal for: ${targetInput}`, 'info');
    // In a real application, you would call a function on modalsRef to open the User modal.
};

const openAreaGroupModal = (targetInput) => {
    showNotification(`Opening Area Group modal for: ${targetInput}`, 'info');
    // In a real application, you would call a function on modalsRef to open the Area Group modal.
};

const updateCustomerInput = ({ target, customerCode }) => {
    if (target === 'customerCodeFrom') customerCodeFrom.value = customerCode;
    else if (target === 'customerCodeTo') customerCodeTo.value = customerCode;
};

const handleProceed = () => {
    console.log('Proceeding with Delivery Schedule:', {
        currentSOPeriod: currentSOPeriod.value,
        currentFGPeriod: currentFGPeriod.value,
        dueDateFrom: dueDateFrom.value,
        dueDateTo: dueDateTo.value,
        dueTime: dueTime.value,
        dueFlag: dueFlag.value,
        scheduleDate: scheduleDate.value,
        customerCodeFrom: customerCodeFrom.value,
        customerCodeTo: customerCodeTo.value,
        mcSeqFrom: mcSeqFrom.value,
        mcSeqTo: mcSeqTo.value,
        poRefFrom: poRefFrom.value,
        poRefTo: poRefTo.value,
        salespersonFrom: salespersonFrom.value,
        salespersonTo: salespersonTo.value,
        userIDFrom: userIDFrom.value,
        userIDTo: userIDTo.value,
        areaGroupFrom: areaGroupFrom.value,
        areaGroupTo: areaGroupTo.value,
        soStatus: soStatus.value,
        orderGroup: orderGroup.value,
    });
    showNotification('Proceeding with Sales Order Delivery Schedule! (Dummy action)', 'success');
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
fieldset {
    border: 1px solid #e2e8f0; /* border-gray-300 */
    padding: 1rem;
    border-radius: 0.5rem;
}
legend {
    padding: 0 0.5rem;
    font-weight: 600;
    color: #4a5568; /* text-gray-800 */
}
</style> 