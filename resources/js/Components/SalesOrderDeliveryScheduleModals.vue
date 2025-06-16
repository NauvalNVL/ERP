<template>
    <!-- Date Picker Modal (for Due Date and Schedule Date) -->
    <div v-if="showDatePickerModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black bg-opacity-70 backdrop-blur-sm transition-opacity duration-300 ease-in-out">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-sm overflow-hidden transform transition-all duration-300 ease-in-out scale-95 opacity-0" :class="{'scale-100 opacity-100': showDatePickerModal}">
            <div class="flex items-center justify-between p-4 border-b border-gray-200 bg-gradient-to-r from-blue-600 to-blue-700 text-white">
                <h3 class="text-xl font-semibold flex items-center">
                    <i class="fas fa-calendar-alt mr-2"></i> Select Date
                </h3>
                <button type="button" @click="closeDatePickerModal" class="text-white hover:text-gray-200">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div class="p-6 space-y-6">
                <div class="datepicker-container">
                    <!-- Placeholder for a simple date picker or input -->
                    <input type="date" v-model="selectedDate" class="form-input w-full">
                </div>
            </div>
            <div class="p-4 bg-gray-50 border-t border-gray-200 flex justify-end space-x-3">
                <button @click="confirmDateSelection" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors shadow">
                    Select
                </button>
                <button @click="closeDatePickerModal" class="px-6 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition-colors shadow">
                    Cancel
                </button>
            </div>
        </div>
    </div>

    <!-- Customer Account Search Modal (Reusable, simplified for this page) -->
    <div v-if="showCustomerAccountSearchModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black bg-opacity-70 backdrop-blur-sm transition-opacity duration-300 ease-in-out">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-4xl overflow-hidden transform transition-all duration-300 ease-in-out scale-95 opacity-0" :class="{'scale-100 opacity-100': showCustomerAccountSearchModal}">
            <div class="flex items-center justify-between p-4 border-b border-gray-200 bg-gradient-to-r from-blue-600 to-blue-700 text-white">
                <h3 class="text-xl font-semibold flex items-center">
                    <i class="fas fa-users mr-2"></i> Customer Account Search
                </h3>
                <button type="button" @click="closeCustomerAccountSearchModal" class="text-white hover:text-gray-200">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div class="p-6 space-y-6 overflow-y-auto max-h-[70vh]">
                <input type="text" v-model="customerSearchQuery" @input="filterCustomers" placeholder="Search Customer Name or Code..." class="form-input w-full mb-4">
                <div class="border border-gray-300 rounded-lg overflow-hidden mb-6">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer Name</th>
                                <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer Code</th>
                                <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="customer in filteredCustomers" :key="customer.customer_code" 
                                @click="selectCustomer(customer)" @dblclick="confirmCustomerSelection(customer.customer_code)"
                                :class="{'bg-blue-100': selectedCustomer && selectedCustomer.customer_code === customer.customer_code, 'hover:bg-blue-50': true, 'cursor-pointer': true}">
                                <td class="px-3 py-2 whitespace-nowrap text-sm font-medium text-gray-900">{{ customer.customer_name }}</td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900">{{ customer.customer_code }}</td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900">{{ customer.status }}</td>
                            </tr>
                            <tr v-if="filteredCustomers.length === 0">
                                <td colspan="3" class="px-3 py-2 text-sm text-gray-500 text-center">No customers found.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="p-4 bg-gray-50 border-t border-gray-200 flex justify-end space-x-3">
                <button @click="handleSelectCustomer" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors shadow">
                    Select
                </button>
                <button @click="closeCustomerAccountSearchModal" class="px-6 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition-colors shadow">
                    Exit
                </button>
            </div>
        </div>
    </div>

    <!-- MC Master Search Modal -->
    <div v-if="showMCMasterModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black bg-opacity-70 backdrop-blur-sm transition-opacity duration-300 ease-in-out">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-4xl overflow-hidden transform transition-all duration-300 ease-in-out scale-95 opacity-0" :class="{'scale-100 opacity-100': showMCMasterModal}">
            <div class="flex items-center justify-between p-4 border-b border-gray-200 bg-gradient-to-r from-blue-600 to-blue-700 text-white">
                <h3 class="text-xl font-semibold flex items-center">
                    <i class="fas fa-credit-card mr-2"></i> MC Master Search
                </h3>
                <button type="button" @click="closeMCMasterModal" class="text-white hover:text-gray-200">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div class="p-6 space-y-6 overflow-y-auto max-h-[70vh]">
                <input type="text" v-model="mcSearchQuery" @input="filterMCs" placeholder="Search MC Seq#..." class="form-input w-full mb-4">
                <div class="border border-gray-300 rounded-lg overflow-hidden mb-6">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">MC Seq#</th>
                                <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer Code</th>
                                <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product Name</th>
                                <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="mc in filteredMCs" :key="mc.mc_seq" 
                                @click="selectMC(mc)" @dblclick="confirmMCSelection(mc.mc_seq)"
                                :class="{'bg-blue-100': selectedMC && selectedMC.mc_seq === mc.mc_seq, 'hover:bg-blue-50': true, 'cursor-pointer': true}">
                                <td class="px-3 py-2 whitespace-nowrap text-sm font-medium text-gray-900">{{ mc.mc_seq }}</td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900">{{ mc.customer_code }}</td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900">{{ mc.product_name }}</td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900">{{ mc.status }}</td>
                            </tr>
                            <tr v-if="filteredMCs.length === 0">
                                <td colspan="4" class="px-3 py-2 text-sm text-gray-500 text-center">No MCs found.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="p-4 bg-gray-50 border-t border-gray-200 flex justify-end space-x-3">
                <button @click="handleSelectMC" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors shadow">
                    Select
                </button>
                <button @click="closeMCMasterModal" class="px-6 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition-colors shadow">
                    Exit
                </button>
            </div>
        </div>
    </div>

    <!-- PO Ref Search Modal -->
    <div v-if="showPORefModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black bg-opacity-70 backdrop-blur-sm transition-opacity duration-300 ease-in-out">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-4xl overflow-hidden transform transition-all duration-300 ease-in-out scale-95 opacity-0" :class="{'scale-100 opacity-100': showPORefModal}">
            <div class="flex items-center justify-between p-4 border-b border-gray-200 bg-gradient-to-r from-blue-600 to-blue-700 text-white">
                <h3 class="text-xl font-semibold flex items-center">
                    <i class="fas fa-file-invoice mr-2"></i> PO Ref# Search
                </h3>
                <button type="button" @click="closePORefModal" class="text-white hover:text-gray-200">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div class="p-6 space-y-6 overflow-y-auto max-h-[70vh]">
                <input type="text" v-model="poRefSearchQuery" @input="filterPORefs" placeholder="Search PO Ref#..." class="form-input w-full mb-4">
                <div class="border border-gray-300 rounded-lg overflow-hidden mb-6">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">PO Ref#</th>
                                <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer Code</th>
                                <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">SO#</th>
                                <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="po in filteredPORefs" :key="po.po_ref" 
                                @click="selectPORef(po)" @dblclick="confirmPORefSelection(po.po_ref)"
                                :class="{'bg-blue-100': selectedPORef && selectedPORef.po_ref === po.po_ref, 'hover:bg-blue-50': true, 'cursor-pointer': true}">
                                <td class="px-3 py-2 whitespace-nowrap text-sm font-medium text-gray-900">{{ po.po_ref }}</td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900">{{ po.customer_code }}</td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900">{{ po.so_number }}</td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900">{{ po.status }}</td>
                            </tr>
                            <tr v-if="filteredPORefs.length === 0">
                                <td colspan="4" class="px-3 py-2 text-sm text-gray-500 text-center">No PO Refs found.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="p-4 bg-gray-50 border-t border-gray-200 flex justify-end space-x-3">
                <button @click="handleSelectPORef" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors shadow">
                    Select
                </button>
                <button @click="closePORefModal" class="px-6 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition-colors shadow">
                    Exit
                </button>
            </div>
        </div>
    </div>

    <!-- Salesperson Search Modal -->
    <div v-if="showSalespersonModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black bg-opacity-70 backdrop-blur-sm transition-opacity duration-300 ease-in-out">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-2xl overflow-hidden transform transition-all duration-300 ease-in-out scale-95 opacity-0" :class="{'scale-100 opacity-100': showSalespersonModal}">
            <div class="flex items-center justify-between p-4 border-b border-gray-200 bg-gradient-to-r from-blue-600 to-blue-700 text-white">
                <h3 class="text-xl font-semibold flex items-center">
                    <i class="fas fa-user-tie mr-2"></i> Salesperson Search
                </h3>
                <button type="button" @click="closeSalespersonModal" class="text-white hover:text-gray-200">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div class="p-6 space-y-6 overflow-y-auto max-h-[70vh]">
                <input type="text" v-model="salespersonSearchQuery" @input="filterSalespersons" placeholder="Search Salesperson Code or Name..." class="form-input w-full mb-4">
                <div class="border border-gray-300 rounded-lg overflow-hidden mb-6">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Code</th>
                                <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="sp in filteredSalespersons" :key="sp.code" 
                                @click="selectSalesperson(sp)" @dblclick="confirmSalespersonSelection(sp.code)"
                                :class="{'bg-blue-100': selectedSalesperson && selectedSalesperson.code === sp.code, 'hover:bg-blue-50': true, 'cursor-pointer': true}">
                                <td class="px-3 py-2 whitespace-nowrap text-sm font-medium text-gray-900">{{ sp.code }}</td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900">{{ sp.name }}</td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900">{{ sp.status }}</td>
                            </tr>
                            <tr v-if="filteredSalespersons.length === 0">
                                <td colspan="3" class="px-3 py-2 text-sm text-gray-500 text-center">No salespersons found.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="p-4 bg-gray-50 border-t border-gray-200 flex justify-end space-x-3">
                <button @click="handleSelectSalesperson" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors shadow">
                    Select
                </button>
                <button @click="closeSalespersonModal" class="px-6 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition-colors shadow">
                    Exit
                </button>
            </div>
        </div>
    </div>

    <!-- User ID Search Modal -->
    <div v-if="showUserModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black bg-opacity-70 backdrop-blur-sm transition-opacity duration-300 ease-in-out">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-2xl overflow-hidden transform transition-all duration-300 ease-in-out scale-95 opacity-0" :class="{'scale-100 opacity-100': showUserModal}">
            <div class="flex items-center justify-between p-4 border-b border-gray-200 bg-gradient-to-r from-blue-600 to-blue-700 text-white">
                <h3 class="text-xl font-semibold flex items-center">
                    <i class="fas fa-user mr-2"></i> User Search
                </h3>
                <button type="button" @click="closeUserModal" class="text-white hover:text-gray-200">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div class="p-6 space-y-6 overflow-y-auto max-h-[70vh]">
                <input type="text" v-model="userSearchQuery" @input="filterUsers" placeholder="Search User ID or Name..." class="form-input w-full mb-4">
                <div class="border border-gray-300 rounded-lg overflow-hidden mb-6">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User ID</th>
                                <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Username</th>
                                <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="user in filteredUsers" :key="user.user_id" 
                                @click="selectUser(user)" @dblclick="confirmUserSelection(user.user_id)"
                                :class="{'bg-blue-100': selectedUser && selectedUser.user_id === user.user_id, 'hover:bg-blue-50': true, 'cursor-pointer': true}">
                                <td class="px-3 py-2 whitespace-nowrap text-sm font-medium text-gray-900">{{ user.user_id }}</td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900">{{ user.username }}</td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900">{{ user.status }}</td>
                            </tr>
                            <tr v-if="filteredUsers.length === 0">
                                <td colspan="3" class="px-3 py-2 text-sm text-gray-500 text-center">No users found.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="p-4 bg-gray-50 border-t border-gray-200 flex justify-end space-x-3">
                <button @click="handleSelectUser" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors shadow">
                    Select
                </button>
                <button @click="closeUserModal" class="px-6 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition-colors shadow">
                    Exit
                </button>
            </div>
        </div>
    </div>

    <!-- Area Group Search Modal -->
    <div v-if="showAreaGroupModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black bg-opacity-70 backdrop-blur-sm transition-opacity duration-300 ease-in-out">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-2xl overflow-hidden transform transition-all duration-300 ease-in-out scale-95 opacity-0" :class="{'scale-100 opacity-100': showAreaGroupModal}">
            <div class="flex items-center justify-between p-4 border-b border-gray-200 bg-gradient-to-r from-blue-600 to-blue-700 text-white">
                <h3 class="text-xl font-semibold flex items-center">
                    <i class="fas fa-globe mr-2"></i> Area Group Search
                </h3>
                <button type="button" @click="closeAreaGroupModal" class="text-white hover:text-gray-200">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div class="p-6 space-y-6 overflow-y-auto max-h-[70vh]">
                <input type="text" v-model="areaGroupSearchQuery" @input="filterAreaGroups" placeholder="Search Area Group Code or Name..." class="form-input w-full mb-4">
                <div class="border border-gray-300 rounded-lg overflow-hidden mb-6">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Code</th>
                                <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="area in filteredAreaGroups" :key="area.code" 
                                @click="selectAreaGroup(area)" @dblclick="confirmAreaGroupSelection(area.code)"
                                :class="{'bg-blue-100': selectedAreaGroup && selectedAreaGroup.code === area.code, 'hover:bg-blue-50': true, 'cursor-pointer': true}">
                                <td class="px-3 py-2 whitespace-nowrap text-sm font-medium text-gray-900">{{ area.code }}</td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900">{{ area.name }}</td>
                            </tr>
                            <tr v-if="filteredAreaGroups.length === 0">
                                <td colspan="2" class="px-3 py-2 text-sm text-gray-500 text-center">No area groups found.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="p-4 bg-gray-50 border-t border-gray-200 flex justify-end space-x-3">
                <button @click="handleSelectAreaGroup" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors shadow">
                    Select
                </button>
                <button @click="closeAreaGroupModal" class="px-6 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition-colors shadow">
                    Exit
                </button>
            </div>
        </div>
    </div>

</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';

const showDatePickerModal = ref(false);
const targetDateInput = ref(null);
const selectedDate = ref(null);

const showCustomerAccountSearchModal = ref(false);
const customers = ref([]);
const filteredCustomers = ref([]);
const selectedCustomer = ref(null);
const targetCustomerInput = ref(null);
const customerSearchQuery = ref('');

const showMCMasterModal = ref(false);
const mcs = ref([]);
const filteredMCs = ref([]);
const selectedMC = ref(null);
const targetMCInput = ref(null);
const mcSearchQuery = ref('');

const showPORefModal = ref(false);
const poRefs = ref([]);
const filteredPORefs = ref([]);
const selectedPORef = ref(null);
const targetPORefInput = ref(null);
const poRefSearchQuery = ref('');

const showSalespersonModal = ref(false);
const salespersons = ref([]);
const filteredSalespersons = ref([]);
const selectedSalesperson = ref(null);
const targetSalespersonInput = ref(null);
const salespersonSearchQuery = ref('');

const showUserModal = ref(false);
const users = ref([]);
const filteredUsers = ref([]);
const selectedUser = ref(null);
const targetUserInput = ref(null);
const userSearchQuery = ref('');

const showAreaGroupModal = ref(false);
const areaGroups = ref([]);
const filteredAreaGroups = ref([]);
const selectedAreaGroup = ref(null);
const targetAreaGroupInput = ref(null);
const areaGroupSearchQuery = ref('');

const emit = defineEmits(['date-selected', 'customer-selected', 'mc-selected', 'po-ref-selected', 'salesperson-selected', 'user-selected', 'area-group-selected']);

// Date Picker Modal Functions
const openDatePickerModal = (targetInput) => {
    targetDateInput.value = targetInput;
    selectedDate.value = null; // Reset selection
    showDatePickerModal.value = true;
};

const closeDatePickerModal = () => {
    showDatePickerModal.value = false;
};

const confirmDateSelection = () => {
    if (selectedDate.value) {
        emit('date-selected', { target: targetDateInput.value, date: selectedDate.value });
        closeDatePickerModal();
    }
};

// Customer Account Search Modal Functions
const openCustomerAccountSearchModal = async (targetInput) => {
    targetCustomerInput.value = targetInput;
    try {
        const response = await axios.get('/api/customer-accounts');
        customers.value = response.data;
        filteredCustomers.value = response.data; // Initially, all customers are filtered
        showCustomerAccountSearchModal.value = true;
    } catch (error) {
        console.error('Error fetching customer accounts:', error);
        // Implement a notification or error message for the user
    }
};

const closeCustomerAccountSearchModal = () => {
    showCustomerAccountSearchModal.value = false;
    selectedCustomer.value = null;
    customerSearchQuery.value = '';
};

const selectCustomer = (customer) => {
    selectedCustomer.value = customer;
};

const handleSelectCustomer = () => {
    if (selectedCustomer.value) {
        emit('customer-selected', { target: targetCustomerInput.value, customerCode: selectedCustomer.value.customer_code });
        closeCustomerAccountSearchModal();
    }
};

const confirmCustomerSelection = (customerCode) => {
    emit('customer-selected', { target: targetCustomerInput.value, customerCode: customerCode });
    closeCustomerAccountSearchModal();
};

const filterCustomers = () => {
    const query = customerSearchQuery.value.toLowerCase();
    filteredCustomers.value = customers.value.filter(customer => 
        customer.customer_name.toLowerCase().includes(query) || 
        customer.customer_code.toLowerCase().includes(query)
    );
};

// MC Master Search Modal Functions
const openMCMasterModal = async (targetInput) => {
    targetMCInput.value = targetInput;
    try {
        // Replace with actual API call to fetch MC data
        const response = await axios.get('/api/approve-mc'); // Adjust this API endpoint
        mcs.value = response.data;
        filteredMCs.value = response.data; 
        showMCMasterModal.value = true;
    } catch (error) {
        console.error('Error fetching MC Master data:', error);
    }
};

const closeMCMasterModal = () => {
    showMCMasterModal.value = false;
    selectedMC.value = null;
    mcSearchQuery.value = '';
};

const selectMC = (mc) => {
    selectedMC.value = mc;
};

const handleSelectMC = () => {
    if (selectedMC.value) {
        emit('mc-selected', { target: targetMCInput.value, mcSeq: selectedMC.value.mc_seq });
        closeMCMasterModal();
    }
};

const confirmMCSelection = (mcSeq) => {
    emit('mc-selected', { target: targetMCInput.value, mcSeq: mcSeq });
    closeMCMasterModal();
};

const filterMCs = () => {
    const query = mcSearchQuery.value.toLowerCase();
    filteredMCs.value = mcs.value.filter(mc => 
        mc.mc_seq.toLowerCase().includes(query) || 
        mc.customer_code.toLowerCase().includes(query) ||
        mc.product_name.toLowerCase().includes(query)
    );
};

// PO Ref Search Modal Functions
const openPORefModal = async (targetInput) => {
    targetPORefInput.value = targetInput;
    try {
        // Replace with actual API call to fetch PO Ref data
        // const response = await axios.get('/api/po-refs'); 
        poRefs.value = [
            { po_ref: 'PO-001', customer_code: 'CUST001', so_number: 'SO-001', status: 'Open' },
            { po_ref: 'PO-002', customer_code: 'CUST002', so_number: 'SO-002', status: 'Closed' },
        ];
        filteredPORefs.value = poRefs.value;
        showPORefModal.value = true;
    } catch (error) {
        console.error('Error fetching PO Ref data:', error);
    }
};

const closePORefModal = () => {
    showPORefModal.value = false;
    selectedPORef.value = null;
    poRefSearchQuery.value = '';
};

const selectPORef = (po) => {
    selectedPORef.value = po;
};

const handleSelectPORef = () => {
    if (selectedPORef.value) {
        emit('po-ref-selected', { target: targetPORefInput.value, poRef: selectedPORef.value.po_ref });
        closePORefModal();
    }
};

const confirmPORefSelection = (poRef) => {
    emit('po-ref-selected', { target: targetPORefInput.value, poRef: poRef });
    closePORefModal();
};

const filterPORefs = () => {
    const query = poRefSearchQuery.value.toLowerCase();
    filteredPORefs.value = poRefs.value.filter(po => 
        po.po_ref.toLowerCase().includes(query) || 
        po.customer_code.toLowerCase().includes(query)
    );
};

// Salesperson Search Modal Functions
const openSalespersonModal = async (targetInput) => {
    targetSalespersonInput.value = targetInput;
    try {
        const response = await axios.get('/api/salesperson'); // Adjust this API endpoint
        salespersons.value = response.data;
        filteredSalespersons.value = response.data;
        showSalespersonModal.value = true;
    } catch (error) {
        console.error('Error fetching Salesperson data:', error);
    }
};

const closeSalespersonModal = () => {
    showSalespersonModal.value = false;
    selectedSalesperson.value = null;
    salespersonSearchQuery.value = '';
};

const selectSalesperson = (sp) => {
    selectedSalesperson.value = sp;
};

const handleSelectSalesperson = () => {
    if (selectedSalesperson.value) {
        emit('salesperson-selected', { target: targetSalespersonInput.value, salespersonCode: selectedSalesperson.value.code });
        closeSalespersonModal();
    }
};

const confirmSalespersonSelection = (salespersonCode) => {
    emit('salesperson-selected', { target: targetSalespersonInput.value, salespersonCode: salespersonCode });
    closeSalespersonModal();
};

const filterSalespersons = () => {
    const query = salespersonSearchQuery.value.toLowerCase();
    filteredSalespersons.value = salespersons.value.filter(sp => 
        sp.code.toLowerCase().includes(query) || 
        sp.name.toLowerCase().includes(query)
    );
};

// User ID Search Modal Functions
const openUserModal = async (targetInput) => {
    targetUserInput.value = targetInput;
    try {
        const response = await axios.get('/api/users'); // Adjust this API endpoint
        users.value = response.data.users; // Assuming API returns { users: [...] }
        filteredUsers.value = users.value;
        showUserModal.value = true;
    } catch (error) {
        console.error('Error fetching User data:', error);
    }
};

const closeUserModal = () => {
    showUserModal.value = false;
    selectedUser.value = null;
    userSearchQuery.value = '';
};

const selectUser = (user) => {
    selectedUser.value = user;
};

const handleSelectUser = () => {
    if (selectedUser.value) {
        emit('user-selected', { target: targetUserInput.value, userID: selectedUser.value.user_id });
        closeUserModal();
    }
};

const confirmUserSelection = (userID) => {
    emit('user-selected', { target: targetUserInput.value, userID: userID });
    closeUserModal();
};

const filterUsers = () => {
    const query = userSearchQuery.value.toLowerCase();
    filteredUsers.value = users.value.filter(user => 
        user.user_id.toLowerCase().includes(query) || 
        user.username.toLowerCase().includes(query)
    );
};

// Area Group Search Modal Functions
const openAreaGroupModal = async (targetInput) => {
    targetAreaGroupInput.value = targetInput;
    try {
        // Replace with actual API call to fetch Area Group data
        // const response = await axios.get('/api/area-groups'); 
        areaGroups.value = [
            { code: 'A01', name: 'North Area' },
            { code: 'A02', name: 'South Area' },
            { code: 'A03', name: 'East Area' },
            { code: 'A04', name: 'West Area' },
        ];
        filteredAreaGroups.value = areaGroups.value;
        showAreaGroupModal.value = true;
    } catch (error) {
        console.error('Error fetching Area Group data:', error);
    }
};

const closeAreaGroupModal = () => {
    showAreaGroupModal.value = false;
    selectedAreaGroup.value = null;
    areaGroupSearchQuery.value = '';
};

const selectAreaGroup = (area) => {
    selectedAreaGroup.value = area;
};

const handleSelectAreaGroup = () => {
    if (selectedAreaGroup.value) {
        emit('area-group-selected', { target: targetAreaGroupInput.value, areaGroupCode: selectedAreaGroup.value.code });
        closeAreaGroupModal();
    }
};

const confirmAreaGroupSelection = (areaGroupCode) => {
    emit('area-group-selected', { target: targetAreaGroupInput.value, areaGroupCode: areaGroupCode });
    closeAreaGroupModal();
};

const filterAreaGroups = () => {
    const query = areaGroupSearchQuery.value.toLowerCase();
    filteredAreaGroups.value = areaGroups.value.filter(area => 
        area.code.toLowerCase().includes(query) || 
        area.name.toLowerCase().includes(query)
    );
};

// Expose functions to the parent component
defineExpose({
    openDatePickerModal,
    openCustomerAccountSearchModal,
    openMCMasterModal,
    openPORefModal,
    openSalespersonModal,
    openUserModal,
    openAreaGroupModal,
});

</script>

<style scoped>
.form-input {
    @apply px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500;
}
.form-radio {
    @apply rounded-full border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500;
}
.form-checkbox {
    @apply rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500;
}
</style> 