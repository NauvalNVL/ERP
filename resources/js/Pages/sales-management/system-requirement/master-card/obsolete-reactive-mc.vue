<template>
    <AppLayout :header="'Obsolete & Reactive MC'">
        <!-- Header Section -->
        <div class="bg-gradient-to-r from-purple-600 via-indigo-600 to-blue-600 p-6 rounded-t-lg shadow-lg overflow-hidden relative">
            <div class="absolute top-0 right-0 w-40 h-40 bg-white opacity-5 rounded-full -translate-y-20 translate-x-20 animate-pulse-slow"></div>
            <div class="absolute bottom-0 left-0 w-20 h-20 bg-white opacity-5 rounded-full translate-y-10 -translate-x-10 animate-pulse-slow animation-delay-500"></div>
            <div class="flex items-center">
                <div class="bg-gradient-to-br from-pink-500 to-purple-600 p-3 rounded-lg shadow-inner mr-4">
                    <i class="fas fa-sync-alt text-white text-2xl"></i>
                </div>
                <div>
                    <h2 class="text-2xl md:text-3xl font-bold text-white text-shadow">Obsolete & Reactive Master Card</h2>
                    <p class="text-blue-100">Manage individual master card status by changing it to obsolete or reactivating it based on specific requirements.</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-b-lg shadow-lg p-6 mb-6 bg-gradient-to-br from-white to-indigo-50">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                
                <!-- Left Column - Main Content (The Form) -->
                <div class="animate-fade-in-up">
                    <div class="bg-white p-6 rounded-lg shadow-lg border-t-4 border-indigo-500 transition-all duration-300 hover:shadow-xl relative overflow-hidden">
                        <div class="absolute -top-10 -right-10 w-32 h-32 bg-indigo-50 rounded-full opacity-50"></div>
                        <div class="absolute -bottom-12 -left-12 w-36 h-36 bg-purple-50 rounded-full opacity-50"></div>
                        
                        <div class="flex items-center mb-6 pb-3 border-b border-gray-200 relative z-10">
                            <div class="p-2 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-lg mr-4 shadow-md">
                                <i class="fas fa-list-alt text-white"></i>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-800">Selected Master Card</h3>
                        </div>
                            
                        <!-- Form for Selection (CPS Style - Simple) -->
                        <div class="space-y-4 relative z-10">
                            <!-- Selected Master Card Section -->
                            <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                                <h4 class="text-sm font-semibold text-gray-700 mb-3">Selected Master Card</h4>
                                
                                <!-- Initial View: Only AC# and MCS# (CPS Style) -->
                                <div v-if="!form.mcs" class="space-y-3">
                                    <!-- AC# Field -->
                                    <div>
                                        <label class="block text-xs font-medium text-gray-600 mb-1">AC#:</label>
                                        <div class="flex items-stretch border border-gray-300 rounded overflow-hidden bg-white">
                                            <input 
                                                type="text" 
                                                v-model="form.ac" 
                                                @input="onAcInput"
                                                placeholder=""
                                                class="flex-1 px-2 py-1.5 text-sm text-gray-900 focus:outline-none focus:ring-1 focus:ring-indigo-400"
                                            >
                                            <button 
                                                @click="openCustomerLookup" 
                                                type="button" 
                                                class="px-2 py-1.5 bg-indigo-500 text-white hover:bg-indigo-600 transition-colors"
                                                title="Browse"
                                            >
                                                <i class="fas fa-folder-open text-xs"></i>
                                            </button>
                                        </div>
                                    </div>
                                    
                                    <!-- MCS# Field -->
                                    <div>
                                        <label class="block text-xs font-medium text-gray-600 mb-1">MCS#:</label>
                                        <div class="flex items-stretch border border-gray-300 rounded overflow-hidden bg-white">
                                            <input 
                                                type="text" 
                                                v-model="form.mcs" 
                                                readonly
                                                placeholder=""
                                                class="flex-1 px-2 py-1.5 text-sm text-gray-900 bg-gray-100"
                                            >
                                            <button 
                                                @click="openMcsLookup" 
                                                type="button" 
                                                class="px-2 py-1.5 bg-indigo-500 text-white hover:bg-indigo-600 transition-colors"
                                                title="Browse"
                                            >
                                                <i class="fas fa-folder-open text-xs"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Detail View: Show all fields after MC selected (CPS Style) -->
                                <div v-else class="space-y-3">
                                    <!-- Row 1: AC# and Customer Name -->
                                    <div class="grid grid-cols-2 gap-3">
                                        <div>
                                            <label class="block text-xs font-medium text-gray-600 mb-1">AC#:</label>
                                            <div class="flex items-stretch border border-gray-300 rounded overflow-hidden bg-white">
                                                <input 
                                                    type="text" 
                                                    v-model="form.ac" 
                                                    readonly
                                                    class="flex-1 px-2 py-1.5 text-sm text-gray-900 bg-gray-100"
                                                >
                                                <button 
                                                    @click="openCustomerLookup" 
                                                    type="button" 
                                                    class="px-2 py-1.5 bg-indigo-500 text-white hover:bg-indigo-600 transition-colors"
                                                    title="Browse"
                                                >
                                                    <i class="fas fa-folder-open text-xs"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-gray-600 mb-1">&nbsp;</label>
                                            <input 
                                                type="text" 
                                                v-model="mcDetails.customer_name" 
                                                readonly
                                                class="w-full px-2 py-1.5 text-sm border border-gray-300 rounded bg-gray-100"
                                            >
                                        </div>
                                    </div>

                                    <!-- Row 2: MCS# and Model -->
                                    <div class="grid grid-cols-2 gap-3">
                                        <div>
                                            <label class="block text-xs font-medium text-gray-600 mb-1">MCS#:</label>
                                            <div class="flex items-stretch border border-gray-300 rounded overflow-hidden bg-white">
                                                <input 
                                                    type="text" 
                                                    v-model="form.mcs" 
                                                    readonly
                                                    class="flex-1 px-2 py-1.5 text-sm text-gray-900 bg-gray-100"
                                                >
                                                <button 
                                                    @click="openMcsLookup" 
                                                    type="button" 
                                                    class="px-2 py-1.5 bg-indigo-500 text-white hover:bg-indigo-600 transition-colors"
                                                    title="Browse"
                                                >
                                                    <i class="fas fa-folder-open text-xs"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-gray-600 mb-1">Model:</label>
                                            <input 
                                                type="text" 
                                                v-model="mcDetails.model" 
                                                readonly
                                                class="w-full px-2 py-1.5 text-sm border border-gray-300 rounded bg-gray-100"
                                            >
                                        </div>
                                    </div>

                                    <!-- Row 3: Salesperson and Action -->
                                    <div class="grid grid-cols-2 gap-3">
                                        <div>
                                            <label class="block text-xs font-medium text-gray-600 mb-1">Salesperson:</label>
                                            <div class="flex gap-2">
                                                <input 
                                                    type="text" 
                                                    v-model="mcDetails.salesperson_code" 
                                                    readonly
                                                    class="w-20 px-2 py-1.5 text-sm border border-gray-300 rounded bg-gray-100"
                                                >
                                                <input 
                                                    type="text" 
                                                    v-model="mcDetails.salesperson_name" 
                                                    readonly
                                                    class="flex-1 px-2 py-1.5 text-sm border border-gray-300 rounded bg-gray-100"
                                                >
                                            </div>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-gray-600 mb-1">Action:</label>
                                            <input 
                                                type="text" 
                                                v-model="detectedAction" 
                                                readonly
                                                :class="[
                                                    'w-full px-2 py-1.5 text-sm border rounded font-medium',
                                                    detectedAction === 'To Obsolete' ? 'border-red-300 bg-red-50 text-red-700' : 'border-green-300 bg-green-50 text-green-700'
                                                ]"
                                            >
                                        </div>
                                    </div>

                                    <!-- Row 4: Reason -->
                                    <div>
                                        <label class="block text-xs font-medium text-gray-600 mb-1">Reason:</label>
                                        <textarea 
                                            v-model="form.reason" 
                                            rows="3"
                                            placeholder="Enter reason for this action (mandatory)"
                                            class="w-full px-2 py-1.5 text-sm border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-indigo-400"
                                        ></textarea>
                                    </div>
                                </div>
                            </div>

                            <!-- Last Update Log Section - Only show when MC selected -->
                            <div v-if="form.mcs" class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                                <h4 class="text-sm font-semibold text-gray-700 mb-3">Last Update Log</h4>
                                
                                <div class="grid grid-cols-2 gap-3 text-sm">
                                    <div>
                                        <label class="block text-xs font-medium text-gray-600 mb-1">Status:</label>
                                        <input 
                                            type="text" 
                                            v-model="lastUpdate.status" 
                                            readonly
                                            class="w-full px-2 py-1.5 text-sm border border-gray-300 rounded bg-white"
                                        >
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-600 mb-1">User ID:</label>
                                        <input 
                                            type="text" 
                                            v-model="lastUpdate.user_id" 
                                            readonly
                                            class="w-full px-2 py-1.5 text-sm border border-gray-300 rounded bg-white"
                                        >
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-600 mb-1">Date:</label>
                                        <input 
                                            type="text" 
                                            v-model="lastUpdate.date" 
                                            readonly
                                            class="w-full px-2 py-1.5 text-sm border border-gray-300 rounded bg-white"
                                        >
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-600 mb-1">Time:</label>
                                        <input 
                                            type="text" 
                                            v-model="lastUpdate.time" 
                                            readonly
                                            class="w-full px-2 py-1.5 text-sm border border-gray-300 rounded bg-white"
                                        >
                                    </div>
                                    <div class="col-span-2">
                                        <label class="block text-xs font-medium text-gray-600 mb-1">Reason:</label>
                                        <input 
                                            type="text" 
                                            v-model="lastUpdate.reason" 
                                            readonly
                                            class="w-full px-2 py-1.5 text-sm border border-gray-300 rounded bg-white"
                                        >
                                    </div>
                                    <div class="col-span-2">
                                        <label class="flex items-center text-xs font-medium text-gray-600">
                                            <input type="checkbox" :checked="lastUpdate.total_update > 0" disabled class="mr-2">
                                            Number of Obsolete and Reactivation done: {{ lastUpdate.total_update }}
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Save Button - Only show when MC selected -->
                            <div v-if="form.mcs" class="pt-2">
                                <button 
                                    @click="saveMcUpdate" 
                                    type="button"
                                    :disabled="!canSave"
                                    :class="[
                                        'w-full px-4 py-3 font-semibold rounded-lg shadow-md transition-all duration-200 flex items-center justify-center space-x-2',
                                        canSave 
                                            ? 'bg-gradient-to-r from-blue-500 to-blue-600 text-white hover:from-blue-600 hover:to-blue-700 hover:shadow-lg transform hover:-translate-y-0.5' 
                                            : 'bg-gray-300 text-gray-500 cursor-not-allowed'
                                    ]"
                                >
                                    <i class="fas fa-save"></i>
                                    <span>Save</span>
                                </button>
                            </div>
                            
                        </div>
                    </div>
                </div>

                <!-- Right Column - Description Info Panel -->
                <div class="lg:col-span-1 animate-fade-in-up animation-delay-300">
                    <div class="sticky top-24">
                        <div class="bg-white p-6 rounded-lg shadow-lg border-t-4 border-teal-500">
                            <div class="flex items-center mb-4 pb-3 border-b border-gray-200">
                                <div class="p-2 bg-gradient-to-r from-teal-500 to-green-500 rounded-lg mr-4 shadow-md">
                                    <i class="fas fa-info-circle text-white"></i>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-800">Obsolete & Reactive MC Menu Description</h3>
                            </div>
                            <div class="text-sm text-gray-700 space-y-4">
                                <p>
                                    This menu is used to change the status of individual Master Cards to
                                    <span class="font-semibold text-red-600">Obsolete</span> or
                                    <span class="font-semibold text-green-600">Reactivate</span> based on defined criteria.
                                </p>
                                <div class="space-y-3">
                                    <div class="flex items-start bg-white border-l-4 border-indigo-500 rounded-md p-3 shadow-sm">
                                        <span class="text-indigo-600 mr-3"><i class="fas fa-filter"></i></span>
                                        <div>
                                            <p class="font-semibold text-gray-900 text-sm">Define Criteria</p>
                                            <p class="text-gray-700 text-sm">Select AC# and MCS# to identify the Master Card.</p>
                                        </div>
                                    </div>
                                    <div class="flex items-start bg-white border-l-4 border-blue-500 rounded-md p-3 shadow-sm">
                                        <span class="text-blue-600 mr-3"><i class="fas fa-exchange-alt"></i></span>
                                        <div>
                                            <p class="font-semibold text-gray-900 text-sm">Select Action</p>
                                            <p class="text-gray-700 text-sm">Obsolete to deactivate, Reactive to reactivate.</p>
                                        </div>
                                    </div>
                                    <div class="flex items-start bg-white border-l-4 border-emerald-500 rounded-md p-3 shadow-sm">
                                        <span class="text-emerald-600 mr-3"><i class="fas fa-comment-dots"></i></span>
                                        <div>
                                            <p class="font-semibold text-gray-900 text-sm">Add Reason</p>
                                            <p class="text-gray-700 text-sm">Mandatory to fill in the reason for tracking changes.</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-3 bg-yellow-50 border border-yellow-200 rounded-lg p-3 flex items-start">
                                    <span class="text-yellow-600 mr-3"><i class="fas fa-info-circle"></i></span>
                                    <p class="text-sm text-yellow-800">Status changes will affect MC availability in subsequent processes.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- Customer Account Options Modal -->
        <CustomerAccountOptionsModal
            :show="showCustomerAccountOptionsModal"
            @close="showCustomerAccountOptionsModal = false"
            @confirm="applyCustomerOptions"
            :initialSortBy="customerOptionSortBy"
            :initialStatusFilter="customerOptionRecordStatus"
        />

        <!-- Customer Account Modal -->
        <CustomerAccountModal
            :show="showCustomerAccountModal"
            @close="showCustomerAccountModal = false"
            @select="handleCustomerSelect"
        />

        <!-- Update MC Modal - MCS Table Modal Only -->
        <UpdateMcModal
            :showErrorModal="false"
            :showSetupMcModal="false"
            :showSetupPdModal="false"
            :showMcsTableModal="showMcsTableModal"
            :formData="form"
            :mcComponents="[]"
            :mcLoaded="null"
            :zoomOption="'normal'"
            :mcsSortOption="mcsSortOption"
            :mcsSortOrder="mcsSortOrder"
            :mcsStatusFilter="mcsStatusFilter"
            :mcsSearchTerm="mcsSearchTerm"
            :mcsLoading="mcsLoading"
            :mcsError="mcsError"
            :mcsMasterCards="mcsMasterCards"
            :selectedMcs="selectedMcs"
            :mcsCurrentPage="mcsCurrentPage"
            :mcsLastPage="mcsLastPage"
            :productDesigns="[]"
            :paperFlutes="[]"
            :soValues="[]"
            :woValues="[]"
            @closeErrorModal="() => {}"
            @closeSetupMcModal="() => {}"
            @closeSetupPdModal="() => {}"
            @closeMcsTableModal="showMcsTableModal = false"
            @selectComponent="() => {}"
            @setupPD="() => {}"
            @setupOthers="() => {}"
            @handleZoomChange="() => {}"
            @fetchMcsData="fetchMcsData"
            @selectMcsItem="selectedMcs = $event"
            @selectMcs="selectMcs"
            @goToMcsPage="goToMcsPage"
            @updateSearchTerm="mcsSearchTerm = $event"
            @updateSortOption="handleSortOptionChange"
            @update:mcsSortOrder="mcsSortOrder = $event"
            @update:mcsStatusFilter="mcsStatusFilter = $event"
            @productDesignSelected="() => {}"
            @paperFluteSelected="() => {}"
            @soValueSelected="() => {}"
            @woValueSelected="() => {}"
        />

        <!-- Confirmation Modal (CPS Style) -->
        <div v-if="showConfirmModal" class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <!-- Background overlay -->
                <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" @click="cancelSave"></div>

                <!-- Modal panel -->
                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md sm:w-full">
                    <!-- Header -->
                    <div class="px-6 py-4 bg-gradient-to-r from-blue-600 to-blue-700">
                        <h3 class="text-lg font-semibold text-white flex items-center">
                            <i class="fas fa-question-circle mr-3 text-yellow-300"></i>
                            Confirmation
                        </h3>
                    </div>

                    <!-- Body -->
                    <div class="px-6 py-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 w-12 h-12 mx-auto bg-blue-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-question text-blue-600 text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-base font-medium text-gray-900">
                                    Confirm Saving / Updating?
                                </p>
                                <p class="text-sm text-gray-600 mt-1">
                                    {{ detectedAction }}: {{ form.mcs }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="px-6 py-4 bg-gray-50 flex justify-end gap-3">
                        <button 
                            @click="cancelSave" 
                            type="button"
                            class="px-6 py-2 bg-white border border-gray-300 text-gray-700 font-medium rounded hover:bg-gray-50 transition-colors"
                        >
                            Cancel
                        </button>
                        <button 
                            @click="confirmSave" 
                            type="button"
                            class="px-6 py-2 bg-blue-600 text-white font-medium rounded hover:bg-blue-700 transition-colors"
                        >
                            OK
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </AppLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import { useToast } from '@/Composables/useToast';
import CustomerAccountOptionsModal from '@/Components/CustomerAccountOptionsModal.vue';
import UpdateMcModal from '@/Components/UpdateMcModal.vue';
import CustomerAccountModal from '@/Components/customer-account-modal.vue';
import axios from 'axios';

const toast = useToast();

// Toast wrapper function
const showToast = (title, message, type = 'success') => {
    const fullMessage = title ? `${title}: ${message}` : message;
    switch(type) {
        case 'success':
            toast.success(fullMessage);
            break;
        case 'error':
            toast.error(fullMessage);
            break;
        case 'warning':
            toast.warning(fullMessage);
            break;
        case 'info':
            toast.info(fullMessage);
            break;
        default:
            toast.success(fullMessage);
    }
};

const form = ref({
    ac: '',
    mcs: '',
    reason: '',
});

// MC Details (loaded after selection)
const mcDetails = ref({
    customer_name: '',
    model: '',
    salesperson_code: '',
    salesperson_name: '',
    current_status: '', // 'Active' or 'Obsolete'
});

// Last Update Log
const lastUpdate = ref({
    status: '',
    user_id: '',
    date: '',
    time: '',
    reason: '',
    total_update: 0,
});

// Confirmation modal
const showConfirmModal = ref(false);

const showCustomerAccountOptionsModal = ref(false);
const customerOptionSortBy = ref('customer_code');
const customerOptionRecordStatus = ref(['Active']);

// UpdateMcModal props - MCS Table Modal only
const showMcsTableModal = ref(false);
const mcsSortOption = ref('mc_seq');
const mcsSortOrder = ref('asc');
const mcsStatusFilter = ref('Act');
const mcsSearchTerm = ref('');
const mcsLoading = ref(false);
const mcsError = ref(null);
const mcsMasterCards = ref([]);
const selectedMcs = ref(null);
const mcsCurrentPage = ref(1);
const mcsLastPage = ref(1);

// New refs for CustomerAccountModal
const showCustomerAccountModal = ref(false);
const selectedCustomerAccount = ref(null);

const openCustomerLookup = () => {
    showCustomerAccountModal.value = true;
};

const handleCustomerSelect = (customer) => {
    console.log('Customer selected:', customer);
    selectedCustomerAccount.value = customer;
    form.value.ac = customer.customer_code;
    
    // Auto-fill customer name
    mcDetails.value.customer_name = customer.customer_name || customer.name || '';
    console.log('Customer name filled:', mcDetails.value.customer_name);
    
    showCustomerAccountModal.value = false;
};

// Open MCS Table Modal (using UpdateMcModal)
const openMcsLookup = () => {
    if (!form.value.ac) {
        showToast('Validation Error', 'Please fill AC# field first to browse Master Cards.', 'error');
        return;
    }
    showMcsTableModal.value = true;
    fetchMcsData();
};

// Fetch MCS data for modal
const fetchMcsData = async () => {
    if (!form.value.ac) {
        mcsMasterCards.value = [];
        return;
    }

    mcsLoading.value = true;
    mcsError.value = null;

    try {
        const params = {
            customer_code: form.value.ac,
            sortBy: mcsSortOption.value || 'mc_seq',
            sortOrder: mcsSortOrder.value || 'asc',
            status: [mcsStatusFilter.value || 'Act'],
            query: mcsSearchTerm.value || '',
            page: mcsCurrentPage.value || 1,
            per_page: 10,
        };

        console.log('Fetching MCS data with params:', params);
        const response = await axios.get('/api/update-mc/master-cards', { params });
        
        console.log('MCS API Response:', response.data);
        
        if (response.data) {
            mcsMasterCards.value = response.data.data || response.data;
            mcsCurrentPage.value = response.data.current_page || 1;
            mcsLastPage.value = response.data.last_page || 1;
        }
    } catch (error) {
        console.error('Error fetching MCS data:', error);
        mcsError.value = error.response?.data?.message || 'Failed to load master cards';
        mcsMasterCards.value = [];
    } finally {
        mcsLoading.value = false;
    }
};

// Select MCS from modal and load full MC details
const selectMcs = async (mcs) => {
    console.log('MCS selected:', mcs);
    form.value.mcs = mcs.seq;
    console.log('MCS# set to:', form.value.mcs);
    
    showMcsTableModal.value = false;
    selectedMcs.value = null;

    // Load full MC details
    console.log('Calling loadMcDetails...');
    await loadMcDetails(mcs.seq);
    console.log('loadMcDetails completed');
};

// Load MC details after selection
const loadMcDetails = async (mcsNum) => {
    try {
        console.log('Loading MC details for MCS#:', mcsNum);
        const response = await axios.get(`/api/mc/details/${mcsNum}`);
        
        console.log('MC Details API Response:', response.data);
        
        if (response.data) {
            const mc = response.data;
            
            // Preserve existing customer_name if already filled
            const existingCustomerName = mcDetails.value.customer_name;
            
            // Populate MC details - using MC table structure with salesperson
            mcDetails.value = {
                customer_name: mc.AC_NAME || existingCustomerName || '',
                model: mc.MODEL || '',
                salesperson_code: mc.salesperson_code || '',
                salesperson_name: mc.salesperson_name || '',
                current_status: mc.STS || 'ACTIVE',
            };
            
            console.log('MC Details populated:', mcDetails.value);

            // Get last update log from MC_UPDATE_LOG table
            try {
                const logResponse = await axios.get(`/api/mc/update-log/${mcsNum}`);
                console.log('Update Log API Response:', logResponse.data);
                
                if (logResponse.data && logResponse.data.length > 0) {
                    const lastLog = logResponse.data[0];
                    
                    // Format date and time
                    const createdDate = lastLog.created_at ? new Date(lastLog.created_at) : null;
                    
                    lastUpdate.value = {
                        status: lastLog.status || '',
                        user_id: lastLog.user_id || '',
                        date: createdDate ? createdDate.toLocaleDateString('id-ID') : '',
                        time: createdDate ? createdDate.toLocaleTimeString('id-ID') : '',
                        reason: lastLog.reason || '',
                        total_update: logResponse.data.length || 0,
                    };
                    console.log('Last Update Log populated:', lastUpdate.value);
                } else {
                    // Initialize empty last update if no data
                    lastUpdate.value = {
                        status: '',
                        user_id: '',
                        date: '',
                        time: '',
                        reason: '',
                        total_update: 0,
                    };
                    console.log('No last update data available');
                }
            } catch (logError) {
                console.log('No update log found or error fetching log:', logError);
                lastUpdate.value = {
                    status: '',
                    user_id: '',
                    date: '',
                    time: '',
                    reason: '',
                    total_update: 0,
                };
            }
        }
    } catch (error) {
        console.error('Error loading MC details:', error);
        console.error('Error details:', error.response?.data);
        showToast('Error', 'Failed to load master card details.', 'error');
    }
};

// Handle MCS page change
const goToMcsPage = (page) => {
    mcsCurrentPage.value = page;
    fetchMcsData();
};

// Handle sort option change
const handleSortOptionChange = (event) => {
    mcsSortOption.value = event.target.value;
    fetchMcsData();
};

// Computed: Detected Action based on MC status
const detectedAction = computed(() => {
    const status = mcDetails.value.current_status;
    console.log('Detecting action for status:', status);
    
    if (!status) return '';
    
    // Check for ACTIVE status (case insensitive)
    if (status.toUpperCase() === 'ACTIVE' || status.toUpperCase() === 'ACT' || status.toUpperCase() === 'APPROVED') {
        console.log('Action detected: To Obsolete');
        return 'To Obsolete';
    } else if (status.toUpperCase() === 'OBSOLETE') {
        console.log('Action detected: To Reactivate');
        return 'To Reactivate';
    }
    console.log('No action detected for status:', status);
    return '';
});

// Computed: Can Save (validation)
const canSave = computed(() => {
    return form.value.ac &&
           form.value.mcs &&
           form.value.reason.trim() !== '' &&
           detectedAction.value !== '';
});

// Handle AC# input change
const onAcInput = () => {
    // Clear MCS selection when AC# changes
    form.value.mcs = '';
    mcDetails.value = {
        customer_name: '',
        model: '',
        salesperson_code: '',
        salesperson_name: '',
        current_status: '',
    };
    selectedMcs.value = null;
};

// Save MC Update (with confirmation)
const saveMcUpdate = () => {
    if (!canSave.value) {
        showToast('Validation Error', 'Please complete all required fields.', 'error');
        return;
    }
    
    // Show confirmation modal
    showConfirmModal.value = true;
};

// Reset form to initial state
const resetFormToInitial = () => {
    // Clear form inputs
    form.value.ac = '';
    form.value.mcs = '';
    form.value.reason = '';
    
    // Reset MC details
    mcDetails.value = {
        customer_name: '',
        model: '',
        salesperson_code: '',
        salesperson_name: '',
        current_status: '',
    };
    
    // Reset last update log
    lastUpdate.value = {
        status: '',
        user_id: '',
        date: '',
        time: '',
        reason: '',
        total_update: 0,
    };
    
    // Clear selections
    selectedCustomerAccount.value = null;
    selectedMcs.value = null;
    
    console.log('Form reset to initial state');
};

// Confirm and execute save
const confirmSave = async () => {
    console.log('confirmSave called');
    showConfirmModal.value = false;

    try {
        const action = detectedAction.value;
        const mcsNum = form.value.mcs;
        const reason = form.value.reason.trim();
        
        let endpoint = '';
        
        // Determine endpoint based on action
        if (action === 'To Obsolete') {
            endpoint = `/api/obsolete-reactive-mc/${mcsNum}/obsolete`;
        } else if (action === 'To Reactivate') {
            endpoint = `/api/obsolete-reactive-mc/${mcsNum}/reactive`;
        } else {
            showToast('Error', 'Invalid action detected.', 'error');
            return;
        }

        const payload = {
            reason: reason,
        };

        console.log('Sending request to:', endpoint);
        console.log('Payload:', payload);
        
        const response = await axios.post(endpoint, payload);
        console.log('Response:', response.data);
        
        console.log('Update successful, showing toast...');
        
        // Show success message
        showToast('Success', response.data.message || 'Master Card updated successfully.', 'success');
        
        console.log('Waiting 1.5 seconds...');
        // Wait a moment for user to see the toast
        await new Promise(resolve => setTimeout(resolve, 1500));
        
        console.log('Resetting form...');
        // Reset form back to initial state
        resetFormToInitial();
        
        console.log('Showing info toast...');
        // Show additional success message
        showToast('Info', 'Form has been reset. You can now update another Master Card.', 'info');
        
        console.log('confirmSave complete');
    } catch (error) {
        console.error('Save error:', error);
        console.error('Error response:', error.response?.data);
        showToast('Error', error.response?.data?.message || error.response?.data?.error || 'An unexpected error occurred.', 'error');
    }
};

// Cancel confirmation
const cancelSave = () => {
    showConfirmModal.value = false;
};

// Customer Account Options Modal logic
const applyCustomerOptions = (options) => {
    customerOptionSortBy.value = options.sortBy;
    customerOptionRecordStatus.value = options.status;
    showCustomerAccountOptionsModal.value = false;
    // You might want to re-fetch or re-filter data for the customer account modal if it was open
};

// Master Card Options Modal logic
const applyMcsOptions = (options) => {
    mcsOptionSortBy.value = options.sortBy;
    mcsOptionSortOrder.value = options.sortOrder;
    mcsOptionStatus.value = options.status;
    showMcsOptionsModal.value = false;
    // Re-fetch master cards with new options if necessary
    fetchMasterCards();
};

</script>

<style scoped>
/* Utility classes for form styling */
.input-field {
    @apply flex-1 block w-full px-4 py-2 text-sm text-gray-900 bg-gray-50 border border-gray-300 rounded-l-md focus:ring-blue-500 focus:border-blue-500 shadow-sm transition-shadow duration-200;
}

.action-radio {
    @apply flex items-center justify-center p-4 border border-gray-300 rounded-lg cursor-pointer transition-all duration-300 ease-in-out flex-1 text-center;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
    background-color: #f9fafb;
}

.action-radio:hover {
    box-shadow: 0 6px 10px rgba(0, 0, 0, 0.1);
    transform: translateY(-2px);
}

.action-radio-obsolete-active {
    @apply border-red-500 bg-red-50;
    box-shadow: 0 4px 6px rgba(239, 68, 68, 0.2);
}

.action-radio-reactivate-active {
    @apply border-green-500 bg-green-50;
    box-shadow: 0 4px 6px rgba(34, 197, 94, 0.2);
}

.process-button {
    @apply relative overflow-hidden px-8 py-3 bg-gradient-to-r from-blue-600 to-indigo-700 text-white font-bold rounded-lg shadow-lg text-lg tracking-wide transition-all duration-300 ease-out transform hover:scale-105 hover:from-blue-700 hover:to-indigo-800 focus:outline-none focus:ring-4 focus:ring-blue-300;
}

.process-button .shimmer-effect {
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: rgba(255, 255, 255, 0.3);
    transform: skewX(-20deg);
    animation: shimmer 2s infinite;
}

@keyframes shimmer {
    0% {
        left: -100%;
    }
    100% {
        left: 100%;
    }
}
</style>
