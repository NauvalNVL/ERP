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

        <!-- Customer Account Options Modal -->
        <CustomerAccountOptionsModal
            :show="showCustomerAccountOptionsModal"
            @close="showCustomerAccountOptionsModal = false"
            @confirm="applyCustomerOptions"
            :initialSortBy="customerOptionSortBy"
            :initialStatusFilter="customerOptionRecordStatus"
        />

        <!-- New Customer Account Modal -->
        <CustomerAccountModal
            :show="showCustomerAccountModal"
            @close="showCustomerAccountModal = false"
            @select="handleCustomerSelect"
        />

        <!-- New Product Modal -->
        <ProductModal
            :show="showProductModal"
            @close="showProductModal = false"
            @select="handleProductSelect"
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
// import LookupModal from '@/Components/obsolete-reactive-modal.vue'; // Removed, replaced by specific modals
import CustomerAccountOptionsModal from '@/Components/CustomerAccountOptionsModal.vue';
import MasterCardOptionsModal from '@/Components/MasterCardOptionsModal.vue';
import MasterCardSearchSelectModal from '@/Components/MasterCardSearchSelectModal.vue';
import MasterCardZoomModal from '@/Components/MasterCardZoomModal.vue';
import CustomerAccountModal from '@/Components/customer-account-modal.vue'; // New Import
import ProductModal from '@/Components/product-modal.vue'; // New Import
import axios from 'axios';

const { showToast } = useToast();

const form = ref({
    ac: '',
    product_code: '',
    mcs_from: '',
    mcs_to: '',
    action: 'obsolete',
    reason: '',
});

// Removed the generic 'lookup' ref as it's no longer needed
// const lookup = ref({
//     show: false,
//     title: '',
//     items: [],
//     headers: [],
//     headerClass: '',
//     headerIconClass: '',
//     headerIconBgClass: '',
//     filterTag: '',
//     showMoreOptionsButton: false,
//     showZoomButton: false,
// });

const showCustomerAccountOptionsModal = ref(false);
const customerOptionSortBy = ref('customer_code');
const customerOptionRecordStatus = ref(['Active']);

const showMcsOptionsModal = ref(false);
const mcsOptionSortBy = ref('mc_sequence');
const mcsOptionSortOrder = ref('asc');
const mcsOptionStatus = ref(['Active']);

const showMcsSearchSelectModal = ref(false);
const masterCards = ref([]); // This will hold the list of master cards for the modal
const zoomedMasterCard = ref(null);
const showMcsZoomModal = ref(false);

const currentMcsField = ref(''); // To track which MCS field is being edited ('from' or 'to')

// New refs for CustomerAccountModal and ProductModal
const showCustomerAccountModal = ref(false);
const showProductModal = ref(false);
const selectedCustomerAccount = ref(null);
const selectedProduct = ref(null);

const openCustomerLookup = () => {
    showCustomerAccountModal.value = true;
};

const handleCustomerSelect = (customer) => {
    selectedCustomerAccount.value = customer;
    form.value.ac = customer.customer_code;
    showCustomerAccountModal.value = false;
};

const openProductLookup = () => {
    showProductModal.value = true;
};

const handleProductSelect = (product) => {
    selectedProduct.value = product;
    form.value.product_code = product.product_code;
    showProductModal.value = false;
};

const openMcsLookup = (field) => {
    currentMcsField.value = field;
    showMcsSearchSelectModal.value = true;
    // Potentially fetch master cards based on current AC# and Product Code
    fetchMasterCards(); 
};

const handleSelectedMc = (mc) => {
    if (currentMcsField.value === 'from') {
        form.value.mcs_from = mc.mc_sequence;
    } else if (currentMcsField.value === 'to') {
        form.value.mcs_to = mc.mc_sequence;
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

const fetchMasterCards = async () => {
    // In a real application, you might fetch based on form.value.ac and form.value.product_code
    try {
        const response = await axios.get('/api/approve-mc'); // Assuming this endpoint returns a list of master cards
        masterCards.value = response.data;
    } catch (error) {
        showToast('Error', 'Failed to fetch master cards.', 'error');
        console.error('Error fetching master cards:', error);
    }
};

const processSelections = async () => {
    if (!form.value.reason) {
        showToast('Validation Error', 'Reason is mandatory for processing.', 'error');
        return;
    }

    const payload = {
        ac: form.value.ac,
        product_code: form.value.product_code,
        mcs_from: form.value.mcs_from,
        mcs_to: form.value.mcs_to,
        action: form.value.action,
        reason: form.value.reason,
    };

    try {
        const response = await axios.post('/api/obsolate-reactive-mc', payload);
        if (response.data.success) {
            showToast('Success', response.data.message, 'success');
            // Optionally clear form or refresh data
            form.value.ac = '';
            form.value.product_code = '';
            form.value.mcs_from = '';
            form.value.mcs_to = '';
            form.value.reason = '';
            selectedCustomerAccount.value = null;
            selectedProduct.value = null;
        } else {
            showToast('Error', response.data.message, 'error');
        }
    } catch (error) {
        showToast('Error', error.response?.data?.message || 'An unexpected error occurred.', 'error');
        console.error('Processing error:', error);
    }
};

// If you had a generic handleLookupSelection function, it would be removed or refactored:
// const handleLookupSelection = (selectedItem) => {
//     // This function is no longer needed if we have specific handlers for each modal
// };

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
