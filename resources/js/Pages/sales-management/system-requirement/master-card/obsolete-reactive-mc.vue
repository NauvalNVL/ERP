<!-- TEST EDIT -->
<!-- 
  Obsolete and Reactive Master Card Component
  This component handles bulk obsoleting and reactivating master cards in the system.
  Final polished version with enhanced UI, colors, responsiveness, and animations.
-->
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
                    <p class="text-blue-100">Manage master card status in bulk based on specific criteria.</p>
                </div>
            </div>
        </div>

        <div class="rounded-b-lg shadow-lg p-6 mb-6 bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <!-- Left Column - Main Content (The Form) -->
                <div class="lg:col-span-2 animate-fade-in-up">
                    <div class="bg-white p-6 rounded-lg shadow-lg border-t-4 border-indigo-500 transition-all duration-300 hover:shadow-xl relative overflow-hidden">
                        <div class="absolute -top-10 -right-10 w-32 h-32 bg-indigo-50 rounded-full opacity-50"></div>
                        <div class="absolute -bottom-12 -left-12 w-36 h-36 bg-purple-50 rounded-full opacity-50"></div>
                        
                        <div class="flex items-center mb-6 pb-3 border-b border-gray-200 relative z-10">
                            <div class="p-2 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-lg mr-4 shadow-md">
                                <i class="fas fa-edit text-white"></i>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-800">Process Bulk Action</h3>
                        </div>
                            
                        <!-- Form for Bulk Action -->
                        <div class="space-y-6 relative z-10">
                            <!-- AC# -->
                            <div>
                                <label for="ac" class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                                    <span class="flex items-center justify-center h-6 w-6 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 text-white mr-3 shadow-md transition-transform group-hover:scale-110">
                                        <i class="fas fa-user-circle text-xs"></i>
                                    </span>
                                    AC#:
                                </label>
                                <div class="relative flex group">
                                    <input type="text" id="ac" v-model="form.ac" placeholder="Customer Account" class="input-field">
                                    <button @click="openCustomerLookup" type="button" class="inline-flex items-center px-4 py-2 border border-l-0 border-gray-300 rounded-r-md transition-all duration-300 bg-gradient-to-r from-indigo-500 to-purple-500 text-white hover:from-indigo-600 hover:to-purple-600 shadow-sm hover:shadow-md transform hover:-translate-y-px">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                            <!-- Product Code -->
                            <div>
                                <label for="product_code" class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                                     <span class="flex items-center justify-center h-6 w-6 rounded-full bg-gradient-to-br from-blue-500 to-cyan-500 text-white mr-3 shadow-md transition-transform group-hover:scale-110">
                                        <i class="fas fa-box text-xs"></i>
                                    </span>
                                    Product Code:
                                </label>
                                <div class="relative flex group">
                                    <input type="text" id="product_code" v-model="form.product_code" placeholder="Product Code" class="input-field">
                                    <button @click="openProductLookup" type="button" class="inline-flex items-center px-4 py-2 border border-l-0 border-gray-300 rounded-r-md transition-all duration-300 bg-gradient-to-r from-blue-500 to-cyan-500 text-white hover:from-blue-600 hover:to-cyan-600 shadow-sm hover:shadow-md transform hover:-translate-y-px">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                            <!-- MCS# Range -->
                            <div>
                                <label for="mcs_from" class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                                     <span class="flex items-center justify-center h-6 w-6 rounded-full bg-gradient-to-br from-pink-500 to-orange-500 text-white mr-3 shadow-md transition-transform group-hover:scale-110">
                                        <i class="fas fa-barcode text-xs"></i>
                                    </span>
                                    MCS# Range:
                                </label>
                                <div class="flex items-center gap-4">
                                    <div class="relative flex group flex-1">
                                        <input type="text" id="mcs_from" v-model="form.mcs_from" placeholder="From Sequence" class="input-field">
                                        <button @click="openMcsLookup('from')" type="button" class="inline-flex items-center px-4 py-2 border border-l-0 border-gray-300 rounded-r-md transition-all duration-300 bg-gradient-to-r from-pink-500 to-orange-500 text-white hover:from-pink-600 hover:to-orange-600 shadow-sm hover:shadow-md transform hover:-translate-y-px">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                    <span class="text-gray-500 font-medium">TO</span>
                                    <div class="relative flex group flex-1">
                                        <input type="text" id="mcs_to" v-model="form.mcs_to" placeholder="To Sequence" class="input-field">
                                        <button @click="openMcsLookup('to')" type="button" class="inline-flex items-center px-4 py-2 border border-l-0 border-gray-300 rounded-r-md transition-all duration-300 bg-gradient-to-r from-pink-500 to-orange-500 text-white hover:from-pink-600 hover:to-orange-600 shadow-sm hover:shadow-md transform hover:-translate-y-px">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Divider -->
                            <div class="border-t border-gray-200 !my-6"></div>

                            <!-- Action To Perform -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                                    <span class="flex items-center justify-center h-6 w-6 rounded-full bg-gradient-to-br from-amber-400 to-orange-500 text-white mr-3 shadow-md transition-transform group-hover:scale-110">
                                        <i class="fas fa-bolt text-xs"></i>
                                    </span>
                                    Action To Perform
                                </label>
                                <div class="flex flex-col sm:flex-row gap-4 mt-2">
                                    <label class="action-radio" :class="{ 'action-radio-obsolete-active': form.action === 'obsolete' }">
                                        <input type="radio" v-model="form.action" value="obsolete" name="action-radio" class="hidden">
                                        <i class="fas fa-ban w-6 text-xl" :class="form.action === 'obsolete' ? 'text-red-500' : 'text-gray-400'"></i>
                                        <span class="font-bold" :class="form.action === 'obsolete' ? 'text-red-800' : 'text-gray-700'">Obsolete Active</span>
                                    </label>
                                    <label class="action-radio" :class="{ 'action-radio-reactivate-active': form.action === 'reactivate' }">
                                        <input type="radio" v-model="form.action" value="reactivate" name="action-radio" class="hidden">
                                        <i class="fas fa-check-circle w-6 text-xl" :class="form.action === 'reactivate' ? 'text-green-500' : 'text-gray-400'"></i>
                                        <span class="font-bold" :class="form.action === 'reactivate' ? 'text-green-800' : 'text-gray-700'">Reactivate Obsolete</span>
                                    </label>
                                </div>
                            </div>
                            
                            <!-- Reason -->
                            <div>
                                <label for="reason" class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                                    <span class="flex items-center justify-center h-6 w-6 rounded-full bg-gradient-to-br from-green-400 to-emerald-500 text-white mr-3 shadow-md transition-transform group-hover:scale-110">
                                        <i class="fas fa-comment-dots text-xs"></i>
                                    </span>
                                    Provide Reason
                                </label>
                                <textarea id="reason" v-model="form.reason" rows="3" placeholder="A reason must be provided..." class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 shadow-sm focus:shadow-lg transition-shadow"></textarea>
                            </div>

                            <!-- Process Button -->
                            <div class="pt-6 text-center border-t border-gray-200">
                                <button @click="processSelections" class="process-button group">
                                    <span class="shimmer-effect"></span>
                                    <i class="fas fa-cogs mr-3 text-xl group-hover:animate-spin"></i>
                                    Process Selections
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column - Quick Info Panel -->
                <div class="lg:col-span-1 animate-fade-in-up animation-delay-300">
                    <div class="sticky top-24">
                        <div class="bg-white p-6 rounded-lg shadow-lg border-t-4 border-teal-500">
                            <div class="flex items-center mb-4 pb-3 border-b border-gray-200">
                                 <div class="p-2 bg-gradient-to-r from-teal-500 to-green-500 rounded-lg mr-4 shadow-md">
                                    <i class="fas fa-info-circle text-white"></i>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-800">Instructions</h3>
                            </div>
                            <ul class="text-sm text-gray-600 space-y-4">
                                <li class="flex items-start">
                                    <i class="fas fa-filter text-teal-500 w-4 mt-1 mr-3 flex-shrink-0"></i>
                                    <span>Fill in one or more criteria to filter the master cards you want to update.</span>
                                </li>
                                <li class="flex items-start">
                                    <i class="fas fa-bolt text-teal-500 w-4 mt-1 mr-3 flex-shrink-0"></i>
                                    <span>Choose whether to <span class="font-semibold text-red-600">Obsolete</span> or <span class="font-semibold text-green-600">Reactivate</span>.</span>
                                </li>
                                 <li class="flex items-start">
                                    <i class="fas fa-comment-dots text-teal-500 w-4 mt-1 mr-3 flex-shrink-0"></i>
                                    <span>A reason for the bulk action is mandatory.</span>
                                </li>
                                <li class="flex items-start">
                                    <i class="fas fa-cogs text-teal-500 w-4 mt-1 mr-3 flex-shrink-0"></i>
                                    <span>Click 'Process Selections' to apply the changes. This action is irreversible.</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <LookupModal 
            :show="lookup.show"
            :title="lookup.title"
            :items="lookup.items"
            :headers="lookup.headers"
            :headerClass="lookup.headerClass"
            :headerIconClass="lookup.headerIconClass"
            :headerIconBgClass="lookup.headerIconBgClass"
            :filterTag="lookup.filterTag"
            :showMoreOptionsButton="lookup.showMoreOptionsButton"
            :showZoomButton="lookup.showZoomButton"
            @close="lookup.show = false"
            @select="handleLookupSelection"
            @moreOptions="handleCustomerMoreOptions"
            @zoom="handleCustomerZoom"
        />

        <!-- Customer Account Options Modal -->
        <CustomerAccountOptionsModal
            :show="showCustomerAccountOptionsModal"
            @close="showCustomerAccountOptionsModal = false"
            @confirm="applyCustomerOptions"
            :initialSortBy="customerOptionSortBy"
            :initialStatusFilter="customerOptionRecordStatus"
        />

        <!-- MCS Options Modal -->
        <MasterCardOptionsModal
            :show="showMcsOptionsModal"
            @close="showMcsOptionsModal = false"
            @confirm="applyMcsOptions"
            :initialSortBy="mcsOptionSortBy"
            :initialSortOrder="mcsOptionSortOrder"
            :initialStatusFilter="mcsOptionStatus"
        />
        
        <!-- MCS Search/Select Modal -->
        <MasterCardSearchSelectModal
            :show="showMcsSearchSelectModal"
            @close="showMcsSearchSelectModal = false"
            @select="handleSelectedMc"
            @zoom-mc="handleZoomMc"
            :initialMcsList="masterCards"
            :initialSortBy="mcsOptionSortBy"
            :initialSortOrder="mcsOptionSortOrder"
            :initialStatusFilter="mcsOptionStatus"
        />

        <!-- MCS Zoom Modal -->
        <MasterCardZoomModal
            :show="showMcsZoomModal"
            :mc="zoomedMasterCard"
            @close="closeMcsZoomModal"
        />

    </AppLayout>
</template>

<script setup>
import { ref } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import { useToast } from '@/Composables/useToast';
import LookupModal from '@/Components/obsolete-reactive-modal.vue';
import CustomerAccountOptionsModal from '@/Components/CustomerAccountOptionsModal.vue';
import MasterCardOptionsModal from '@/Components/MasterCardOptionsModal.vue';
import MasterCardSearchSelectModal from '@/Components/MasterCardSearchSelectModal.vue';
import MasterCardZoomModal from '@/Components/MasterCardZoomModal.vue';
import axios from 'axios';

const { showToast } = useToast();

const form = ref({
    ac: '',
    mcs_from: '',
    mcs_to: '',
    product_code: '',
    action: 'obsolete', // Default action
    reason: ''
});

// New state variables for Customer Account Options Modal
const showCustomerAccountOptionsModal = ref(false);
const customerOptionSortBy = ref('customer_code'); // Default sort by Customer Code
const customerOptionRecordStatus = ref({
    active: true,
    obsolete: true
});

// --- Lookup Modal State and Data ---
const lookup = ref({
    show: false,
    title: '',
    items: [],
    headers: [],
    type: '' // 'customer', 'product', 'mcs_from', 'mcs_to'
});

// Dummy data for lookups
const customers = ref([
    { id: 1, customer_code: '000211-08', customer_name: 'ABDULLAH, BPK', salesperson: 'S111', acType: 'Local', currency: 'IDR', status: 'Active' },
    { id: 2, customer_code: '000680-06', customer_name: 'ACEP SUNANDAR, BPK', salesperson: 'S140', acType: 'Local', currency: 'IDR', status: 'Active' },
    { id: 3, customer_code: '000585-01', customer_name: 'ACHMAD JAMAL', salesperson: 'S102', acType: 'Local', currency: 'IDR', status: 'Active' },
    { id: 4, customer_code: '000283', customer_name: 'ACOSTA SUPER FOOD, PT', salesperson: 'S143', acType: 'Local', currency: 'IDR', status: 'Active' },
    { id: 5, customer_code: '000903', customer_name: 'ADHITYA SERAYAKORITA, PT', salesperson: 'S103', acType: 'Local', currency: 'IDR', status: 'Active' },
    { id: 6, customer_code: '000212-08', customer_name: 'BAMBANG, BPK', salesperson: 'S112', acType: 'Export', currency: 'USD', status: 'Obsolete' },
    { id: 7, customer_code: '000681-06', customer_name: 'BUDI, BPK', salesperson: 'S141', acType: 'Local', currency: 'IDR', status: 'Active' },
    { id: 8, customer_code: '000586-01', customer_name: 'CITRA, IBU', salesperson: 'S103', acType: 'Local', currency: 'IDR', status: 'Active' },
    { id: 9, customer_code: '000284', customer_name: 'DEDI, BPK', salesperson: 'S144', acType: 'Export', currency: 'USD', status: 'Active' },
    { id: 10, customer_code: '000904', customer_name: 'EKA, IBU', salesperson: 'S104', acType: 'Local', currency: 'IDR', status: 'Obsolete' },
]);
const products = ref([
    { id: 1, product_code: '001', description: 'BOX', group: 'B', category: '1' },
    { id: 2, product_code: '002', description: 'SHEET BOARD', group: 'S', category: '5' },
    { id: 3, product_code: '003', description: 'BUTT ROLL', group: 'R', category: '3' },
    { id: 4, product_code: '004', description: 'PENJUALAN WASTE', group: 'OT', category: 'X' },
    { id: 5, product_code: '005', description: 'PENJUALAN LAIN LAIN PCS', group: 'OT', category: 'X' },
    { id: 6, product_code: '006', description: 'CONEST', group: 'R', category: '3' },
    { id: 7, product_code: '007', description: 'ROLL', group: 'R', category: '3' },
    { id: 8, product_code: '008', description: 'PENDAPATAN LAIN LAIN', group: 'OT', category: 'X' },
    { id: 9, product_code: '009', description: 'PENJUALAN LAIN LAIN KG', group: 'OT', category: '3' },
    { id: 10, product_code: '010', description: 'SINGLE FACER ROLL', group: 'S', category: '2' },
    { id: 11, product_code: '011', description: 'SINGLE FACER KG', group: 'S', category: '3' },
    { id: 12, product_code: '012', description: 'SINGLE FACER SHEET', group: 'S', category: '4' },
    { id: 13, product_code: '013', description: 'PENDAPATAN LAIN - LAIN', group: 'OT', category: 'X' },
    { id: 14, product_code: '014', description: 'SEWA TRUCK', group: 'OT', category: 'X' },
    { id: 15, product_code: '015', description: 'CORE PLUG', group: 'OT', category: 'X' },
    { id: 16, product_code: '016', description: 'PAPER TUBE', group: 'OT', category: 'X' },
    { id: 17, product_code: '017', description: 'OFFSET', group: 'OF', category: '1' },
    { id: 18, product_code: '018', description: 'CETAK OFFSET', group: 'OF', category: '1' },
    { id: 19, product_code: '019', description: 'DIGITAL PRINT', group: 'OF', category: '1' },
    { id: 20, product_code: '500', description: 'SEWA TRUCK TRAILER', group: 'OT', category: 'X' },
]);
const masterCards = ref([
    { id: 1, mc_seq: 'MC-2023-001', mc_model: 'Model-A', pd_part_no: 'PD-P001', pd_ed: 'PD-E001', pd_id: 'PD-I001', status: 'Active' },
    { id: 2, mc_seq: 'MC-2023-002', mc_model: 'Model-B', pd_part_no: 'PD-P002', pd_ed: 'PD-E002', pd_id: 'PD-I002', status: 'Active' },
    { id: 3, mc_seq: 'MC-2023-003', mc_model: 'Model-C', pd_part_no: 'PD-P003', pd_ed: 'PD-E003', pd_id: 'PD-I003', status: 'Obsolete' },
    { id: 4, mc_seq: 'MC-2023-004', mc_model: 'Model-D', pd_part_no: 'PD-P004', pd_ed: 'PD-E004', pd_id: 'PD-I004', status: 'Active' },
]);

// New state variables for MCS Options Modal
const showMcsOptionsModal = ref(false);
const mcsOptionSortBy = ref('mc_seq');
const mcsOptionSortOrder = ref('Ascending');
const mcsOptionStatus = ref({
    active: true,
    obsolete: true
});
const currentMcsLookupType = ref(''); // 'mcs_from' or 'mcs_to'
const showMcsSearchSelectModal = ref(false);
const showMcsZoomModal = ref(false);
const zoomedMasterCard = ref(null);

// Functions to open different lookups
const openCustomerLookup = () => {
    showCustomerAccountOptionsModal.value = true; // Open options modal first
};

const applyCustomerOptions = () => {
    let filteredCustomers = customers.value.filter(customer => {
        const isActive = customerOptionRecordStatus.value.active && customer.status === 'Active';
        const isObsolete = customerOptionRecordStatus.value.obsolete && customer.status === 'Obsolete';
        return isActive || isObsolete;
    });

    filteredCustomers.sort((a, b) => {
        if (customerOptionSortBy.value === 'customer_code') {
            return a.customer_code.localeCompare(b.customer_code);
        } else {
            return a.customer_name.localeCompare(b.customer_name);
        }
    });

    showCustomerAccountOptionsModal.value = false; // Close options modal

    lookup.value = {
        show: true,
        title: 'Customer Account Table',
        items: filteredCustomers,
        headers: [
            { key: 'customer_code', label: 'Customer Code' },
            { key: 'customer_name', label: 'Customer Name' },
            { key: 'salesperson', label: 'S/Person' },
            { key: 'acType', label: 'AC Type' },
            { key: 'currency', label: 'Currency' },
        ],
        headerClass: 'from-blue-600 to-blue-700',
        headerIconClass: 'fas fa-user-circle',
        headerIconBgClass: 'bg-white bg-opacity-30',
        filterTag: 'customer',
        showMoreOptionsButton: true,
        showZoomButton: true
    };
};

const openProductLookup = () => {
    lookup.value = {
        show: true,
        title: 'Product Table',
        items: products.value,
        headers: [
            { key: 'product_code', label: 'Product Code' },
            { key: 'description', label: 'Description' },
            { key: 'group', label: 'Group' },
            { key: 'category', label: 'Category' },
        ],
        headerClass: 'from-blue-500 to-cyan-500',
        headerIconClass: 'fas fa-box',
        headerIconBgClass: 'bg-white bg-opacity-30',
        filterTag: 'product',
        showMoreOptionsButton: false,
        showZoomButton: false
    };
};

const openMcsLookup = (type) => {
    currentMcsLookupType.value = type; // Store whether it's 'from' or 'to'
    showMcsOptionsModal.value = true; // Open options modal first
};

const applyMcsOptions = () => {
    showMcsOptionsModal.value = false; // Close options modal
    showMcsSearchSelectModal.value = true; // Open the search/select modal
};

const handleSelectedMc = (mc) => {
    if (currentMcsLookupType.value === 'from') {
        form.value.mcs_from = mc.mc_seq;
    } else if (currentMcsLookupType.value === 'to') {
        form.value.mcs_to = mc.mc_seq;
    }
    showMcsSearchSelectModal.value = false;
};

const handleZoomMc = (mc) => {
    zoomedMasterCard.value = mc;
    showMcsZoomModal.value = true;
};

const closeMcsZoomModal = () => {
    showMcsZoomModal.value = false;
    zoomedMasterCard.value = null;
};

const handleLookupSelection = (item) => {
    if (lookup.value.type === 'customer') {
        form.value.ac = item.customer_code;
    } else if (lookup.value.type === 'product') {
        form.value.product_code = item.product_code;
    }
    lookup.value.show = false;
};

const handleCustomerMoreOptions = (customer) => {
    console.log('More options for customer:', customer);
    showToast('info', `More options for ${customer.customer_name}`);
    // Implement navigation or another modal for more options
};

const handleCustomerZoom = (customer) => {
    console.log('Zooming into customer:', customer);
    showToast('info', `Zooming into ${customer.customer_name}`);
    // Implement navigation or another modal for zooming
};

const processSelections = async () => {
    if (!form.value.reason) {
        showToast('error', 'Reason is mandatory for processing.');
        return;
    }

    const endpoint = form.value.action === 'obsolete' 
        ? '/api/obsolate-reactive-mc/obsolate/' 
        : '/api/obsolate-reactive-mc/reactive/';

    try {
        const response = await axios.post(endpoint, {
            ac: form.value.ac,
            mcs_from: form.value.mcs_from,
            mcs_to: form.value.mcs_to,
            product_code: form.value.product_code,
            reason: form.value.reason,
        });

        if (response.data.success) {
            showToast('success', response.data.message);
            // Optionally, clear form or update UI after success
        form.value.ac = '';
        form.value.mcs_from = '';
        form.value.mcs_to = '';
        form.value.product_code = '';
        form.value.reason = '';
        } else {
            showToast('error', response.data.message);
        }
    } catch (error) {
        console.error('Error processing selection:', error);
        showToast('error', 'Failed to process selections.');
    }
};
</script>

<style scoped>
/* Base input field styling */
.input-field {
    @apply w-full px-4 py-2 border border-gray-300 rounded-l-md shadow-sm
           focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500
           transition-all duration-200 text-sm;
}

/* Styling for action radio buttons */
.action-radio {
    @apply flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer
           transition-all duration-300 shadow-sm;
}

.action-radio:hover {
    @apply shadow-md transform -translate-y-0.5;
}

.action-radio-obsolete-active {
    @apply bg-red-50 border-red-400 ring-2 ring-red-500;
}

.action-radio-reactivate-active {
    @apply bg-green-50 border-green-400 ring-2 ring-green-500;
}

/* Process button styling */
.process-button {
    @apply relative overflow-hidden inline-flex items-center justify-center
           px-8 py-3 bg-gradient-to-r from-teal-500 to-green-500 text-white font-semibold
           rounded-lg shadow-lg transform transition-all duration-300 hover:scale-105
           hover:from-teal-600 hover:to-green-600 focus:outline-none focus:ring-2
           focus:ring-teal-500 focus:ring-offset-2 w-full sm:w-auto;
}

/* Shimmer effect for buttons */
.shimmer-effect {
    @apply absolute inset-0 bg-gradient-to-r from-transparent via-white to-transparent
           opacity-20 transition-transform duration-1000 transform -skew-x-12 translate-x-[-100%];
}

.process-button:hover .shimmer-effect {
    @apply translate-x-[100%];
}

/* Text shadow for headings */
.text-shadow {
    text-shadow: 0px 2px 4px rgba(0, 0, 0, 0.3);
}

/* Ping animation for header icons */
@keyframes pulse-slow {
    0% { transform: scale(1); opacity: 0.5; }
    50% { transform: scale(1.05); opacity: 0.8; }
    100% { transform: scale(1); opacity: 0.5; }
}

.animate-pulse-slow {
    animation: pulse-slow 3s ease-in-out infinite;
}

.animation-delay-500 {
    animation-delay: 0.5s;
}

</style>
