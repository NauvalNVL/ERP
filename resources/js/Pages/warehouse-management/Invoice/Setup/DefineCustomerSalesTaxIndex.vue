<template>
    <AppLayout :header="'Define Customer Sales Tax Index'">
        <!-- Header Section -->
        <div class="bg-gradient-to-r from-blue-600 via-cyan-600 to-teal-500 p-6 rounded-t-lg shadow-lg overflow-hidden relative">
            <div class="absolute top-0 right-0 w-40 h-40 bg-white opacity-5 rounded-full -translate-y-20 translate-x-20 animate-pulse-slow"></div>
            <div class="absolute bottom-0 left-0 w-20 h-20 bg-white opacity-5 rounded-full translate-y-10 -translate-x-10 animate-pulse-slow animation-delay-500"></div>
            <div class="flex items-center">
                <div class="bg-gradient-to-br from-cyan-500 to-teal-600 p-3 rounded-lg shadow-inner mr-4">
                    <i class="fas fa-user-tag text-white text-2xl"></i>
                </div>
                <div>
                    <h2 class="text-2xl md:text-3xl font-bold text-white text-shadow">Define Customer Sales Tax Index</h2>
                    <p class="text-teal-100">Configure customer-specific sales tax settings and product group mappings.</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-b-lg shadow-lg p-6 mb-6 bg-gradient-to-br from-white to-cyan-50">
            <div class="max-w-7xl mx-auto">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Left: Main Form (col-span-2) -->
                    <div class="lg:col-span-2">
                        <div class="relative bg-gradient-to-br from-blue-50 via-cyan-50 to-teal-100 p-8 rounded-2xl shadow-2xl border-t-4 border-cyan-500 overflow-hidden mb-8 animate-fade-in-up">
                            <div class="absolute -top-16 -right-16 w-40 h-40 bg-cyan-200 rounded-full opacity-30"></div>
                            <div class="absolute -bottom-12 -left-12 w-32 h-32 bg-teal-200 rounded-full opacity-30"></div>

                            <div class="flex items-center mb-6 pb-3 border-b border-gray-200 relative z-10">
                                <div class="p-2 bg-gradient-to-r from-cyan-500 to-teal-600 rounded-lg mr-4 shadow-md">
                                    <i class="fas fa-edit text-white"></i>
                                </div>
                                <h3 class="text-xl font-semibold text-gray-800">Customer Tax Index Management</h3>
                            </div>

                            <!-- Customer Code Input -->
                            <div class="relative z-10 mb-6">
                                <label for="customerCode" class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                                    <span class="flex items-center justify-center h-6 w-6 rounded-full bg-gradient-to-br from-cyan-500 to-teal-500 text-white mr-3 shadow-md">
                                        <i class="fas fa-user text-xs"></i>
                                    </span>
                                    Customer Code
                                </label>
                                <div class="relative flex group">
                                    <input
                                        id="customerCode"
                                        v-model="form.customer_code"
                                        @input="handleCustomerCodeInput"
                                        type="text"
                                        placeholder="Search customer code..."
                                        class="input-field"
                                        :readonly="recordMode !== 'select'"
                                        :class="{ 'bg-gray-100 cursor-not-allowed': recordMode !== 'select' }"
                                    />
                                    <button
                                        type="button"
                                        @click="openCustomerModal"
                                        class="lookup-button from-blue-500 to-cyan-500 hover:from-blue-600 hover:to-cyan-600"
                                        title="Select Customer"
                                    >
                                        <i class="fas fa-table"></i>
                                    </button>
                                </div>
                                <p v-if="selectedCustomer" class="text-sm text-gray-600 mt-1 ml-1">
                                    {{ selectedCustomer.name }}
                                </p>
                            </div>

                            <!-- Sales Tax Index# Input -->
                            <div class="relative z-10 mb-6">
                                <label for="salesTaxIndex" class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                                    <span class="flex items-center justify-center h-6 w-6 rounded-full bg-gradient-to-br from-cyan-500 to-teal-500 text-white mr-3 shadow-md">
                                        <i class="fas fa-hashtag text-xs"></i>
                                    </span>
                                    Sales Tax Index#
                                </label>
                                <div class="relative flex group">
                                    <input
                                        id="salesTaxIndex"
                                        v-model.number="form.index_number"
                                        type="number"
                                        min="1"
                                        placeholder="Enter index number..."
                                        class="input-field"
                                        :readonly="!form.customer_code"
                                        :class="{ 'bg-gray-100 cursor-not-allowed': !form.customer_code }"
                                    />
                                    <button
                                        type="button"
                                        @click="openIndexTableModal"
                                        :disabled="!form.customer_code"
                                        class="lookup-button from-blue-500 to-cyan-500 hover:from-blue-600 hover:to-cyan-600"
                                        :class="{ 'opacity-50 cursor-not-allowed': !form.customer_code }"
                                        title="View Customer's Tax Indices"
                                    >
                                        <i class="fas fa-table"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Data Status Information -->
                            <div class="relative z-10 mt-4 p-4 rounded-lg shadow-inner border" :class="{
                                'bg-yellow-50 border-yellow-200 text-yellow-800': recordMode === 'select',
                                'bg-green-50 border-green-200 text-green-800': recordMode !== 'select'
                            }">
                                <div v-if="recordMode === 'select'">
                                    <p class="text-sm font-medium">No customer selected.</p>
                                    <p class="text-xs text-yellow-700 mt-1">Select a customer and index number to begin.</p>
                                </div>
                                <div v-else>
                                    <p class="text-sm font-medium">
                                        Customer: {{ form.customer_code }} | Index#: {{ form.index_number }}
                                    </p>
                                    <p class="text-xs text-green-700 mt-1">
                                        Mode: {{ recordMode === 'review' ? 'Review/Edit' : 'New Entry' }}
                                    </p>
                                </div>
                            </div>

                            <!-- Form Fields (shown only when customer and index selected) -->
                            <div v-if="recordMode !== 'select'" class="relative z-10 space-y-6 mt-6">
                                <!-- Index Status -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Index Status:
                                    </label>
                                    <div class="flex gap-4">
                                        <label class="flex items-center cursor-pointer">
                                            <input
                                                type="radio"
                                                v-model="form.status"
                                                value="A"
                                                class="mr-2"
                                            />
                                            <span class="text-sm">A-Active</span>
                                        </label>
                                        <label class="flex items-center cursor-pointer">
                                            <input
                                                type="radio"
                                                v-model="form.status"
                                                value="O"
                                                class="mr-2"
                                            />
                                            <span class="text-sm">O-Obsolete</span>
                                        </label>
                                    </div>
                                </div>

                                <!-- Tax Group -->
                                <div>
                                    <label for="taxGroup" class="block text-sm font-medium text-gray-700 mb-2">
                                        Tax Group:
                                    </label>
                                    <div class="relative flex group">
                                        <input
                                            id="taxGroup"
                                            v-model="form.tax_group_code"
                                            type="text"
                                            placeholder="Select tax group..."
                                            class="input-field bg-gray-100 cursor-pointer"
                                            readonly
                                            @click="openTaxGroupModal"
                                        />
                                        <button
                                            type="button"
                                            @click="openTaxGroupModal"
                                            class="lookup-button from-blue-500 to-cyan-500 hover:from-blue-600 hover:to-cyan-600"
                                        >
                                            <i class="fas fa-table"></i>
                                        </button>
                                    </div>
                                    <p v-if="selectedTaxGroup" class="text-sm text-gray-600 mt-1 ml-1">
                                        {{ selectedTaxGroup.name }}
                                    </p>
                                </div>

                                <!-- Action Buttons -->
                                <div class="flex gap-4 pt-4">
                                    <button
                                        type="button"
                                        @click="openProductTieUpModal"
                                        class="secondary-button flex-1"
                                    >
                                        <i class="fas fa-link mr-2"></i>
                                        Define Product Group Tie-Up
                                    </button>
                                </div>

                                <!-- Save/Cancel Buttons -->
                                <div class="flex justify-between items-center pt-6 border-t border-gray-200">
                                    <button
                                        v-if="recordMode === 'review'"
                                        type="button"
                                        @click="handleDelete"
                                        class="delete-button"
                                    >
                                        <i class="fas fa-trash mr-2"></i>
                                        Delete
                                    </button>
                                    <div v-else></div>

                                    <div class="flex gap-3">
                                        <button
                                            type="button"
                                            @click="handleCancel"
                                            class="secondary-button"
                                        >
                                            <i class="fas fa-times mr-2"></i>
                                            Cancel
                                        </button>
                                        <button
                                            type="button"
                                            @click="handleSave"
                                            class="primary-button"
                                        >
                                            <span class="shimmer-effect"></span>
                                            <i class="fas fa-save mr-2"></i>
                                            Save
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right: Information & Quick Links (col-span-1) -->
                    <div class="flex flex-col space-y-6">
                        <!-- Information Card -->
                        <div class="bg-white rounded-xl shadow-md border-t-4 border-blue-400 p-6">
                            <div class="flex items-center mb-2">
                                <div class="inline-flex items-center justify-center w-10 h-10 bg-gradient-to-br from-green-400 to-teal-400 rounded-lg mr-3">
                                    <i class="fas fa-info text-white text-2xl"></i>
                                </div>
                                <h3 class="text-xl font-bold text-gray-800">Information</h3>
                            </div>
                            <hr class="my-2 border-blue-100">
                            <p class="text-sm text-gray-600 mb-3">
                                Configure customer-specific tax settings including tax groups and product group mappings for precise invoicing.
                            </p>
                            <div class="space-y-2 text-sm text-gray-700">
                                <div class="flex items-start">
                                    <i class="fas fa-check-circle text-green-500 mt-1 mr-2"></i>
                                    <span>Select customer and index number</span>
                                </div>
                                <div class="flex items-start">
                                    <i class="fas fa-check-circle text-green-500 mt-1 mr-2"></i>
                                    <span>Choose applicable tax group</span>
                                </div>
                                <div class="flex items-start">
                                    <i class="fas fa-check-circle text-green-500 mt-1 mr-2"></i>
                                    <span>Define product group tie-ups</span>
                                </div>
                                <div class="flex items-start">
                                    <i class="fas fa-check-circle text-green-500 mt-1 mr-2"></i>
                                    <span>Manage multiple tax indices per customer</span>
                                </div>
                            </div>
                        </div>

                        <!-- Quick Actions Card -->
                        <div class="bg-white rounded-xl shadow-md border-t-4 border-purple-400 p-6">
                            <div class="flex items-center mb-2">
                                <div class="inline-flex items-center justify-center w-10 h-10 bg-gradient-to-br from-purple-400 to-pink-400 rounded-lg mr-3">
                                    <i class="fas fa-bolt text-white text-2xl"></i>
                                </div>
                                <h3 class="text-xl font-bold text-gray-800">Quick Actions</h3>
                            </div>
                            <hr class="my-2 border-purple-100">
                            <div class="space-y-3">
                                <button
                                    @click="openCustomerModal"
                                    class="w-full text-left px-4 py-2 bg-gradient-to-r from-blue-500 to-cyan-500 text-white rounded-lg hover:from-blue-600 hover:to-cyan-600 transition-all shadow-md hover:shadow-lg"
                                >
                                    <i class="fas fa-users mr-2"></i>
                                    Select Customer
                                </button>
                                <button
                                    @click="openIndexTableModal"
                                    :disabled="!form.customer_code"
                                    class="w-full text-left px-4 py-2 bg-gradient-to-r from-purple-500 to-pink-500 text-white rounded-lg hover:from-purple-600 hover:to-pink-600 transition-all shadow-md hover:shadow-lg disabled:opacity-50 disabled:cursor-not-allowed"
                                >
                                    <i class="fas fa-list mr-2"></i>
                                    View Tax Indices
                                </button>
                            </div>
                        </div>

                        <div class="bg-white rounded-xl shadow-md border-t-4 border-green-400 p-6">
                            <div class="flex items-center mb-2">
                                <div class="inline-flex items-center justify-center w-10 h-10 bg-gradient-to-br from-green-400 to-teal-400 rounded-lg mr-3">
                                    <i class="fas fa-print text-white text-2xl"></i>
                                </div>
                                <h3 class="text-xl font-bold text-gray-800">Quick Links</h3>
                            </div>
                            <hr class="my-2 border-green-100">
                            <div class="space-y-3">
                                <a
                                    href="/warehouse-management/invoice/setup/print-customer-sales-tax-index"
                                    class="flex items-center p-3 rounded-lg bg-green-50 hover:bg-green-100 transition"
                                >
                                    <span class="inline-flex items-center justify-center w-9 h-9 bg-green-400 rounded-lg mr-3">
                                        <i class="fas fa-file-alt text-white text-xl"></i>
                                    </span>
                                    <div>
                                        <div class="font-bold text-green-800">View & Print Tax Index</div>
                                        <div class="text-xs text-green-700">Print customer's sales tax indices</div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Customer Account Modal (Reuse Existing) -->
        <CustomerAccountModal
            v-if="showCustomerModal"
            :show="showCustomerModal"
            :customerAccounts="customers"
            :initial-search="searchQuery"
            @close="showCustomerModal = false"
            @select="selectCustomer"
        />

        <!-- Tax Group Modal (Reuse Existing) -->
        <TaxGroupModal
            :show="showTaxGroupModal"
            @close="showTaxGroupModal = false"
            @select="selectTaxGroup"
        />

        <!-- Customer Sales Tax Index Table Modal -->
        <CustomerSalesTaxorSalesTaxExemptionTable
            :open="showIndexTableModal"
            :customerCode="form.customer_code"
            @close="showIndexTableModal = false"
            @select="selectIndex"
        />

        <!-- Product Group Tie-Up Modal -->
        <ProductGroupTieUpModal
            :show="showProductTieUpModal"
            :customer-code="form.customer_code"
            :index-number="form.index_number"
            @close="showProductTieUpModal = false"
            @saved="handleProductTieUpSaved"
        />
    </AppLayout>
</template>

<script setup>
import { ref, watch } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import CustomerAccountModal from '@/Components/customer-account-modal.vue';
import TaxGroupModal from '@/Components/TaxGroupModal.vue';
import CustomerSalesTaxorSalesTaxExemptionTable from '@/Components/CustomerSalesTaxorSalesTaxExemptionTable.vue';
import ProductGroupTieUpModal from '@/Components/ProductGroupTieUpModal.vue';
import axios from 'axios';

// Record modes
const recordMode = ref('select'); // 'select', 'add', 'review'

// Form data
const form = ref({
    customer_code: '',
    index_number: null,
    tax_group_code: '',
    status: 'A'
});

// Selected data objects
const selectedCustomer = ref(null);
const selectedTaxGroup = ref(null);

// Modal states
const showCustomerModal = ref(false);
const showTaxGroupModal = ref(false);
const showIndexTableModal = ref(false);
const showProductTieUpModal = ref(false);

// Customer data
const customers = ref([]);
const searchQuery = ref('');

// Handle customer code input
const handleCustomerCodeInput = () => {
    if (recordMode.value !== 'select') return;
    // Real-time validation or search can be added here
};

// Open customer modal
const openCustomerModal = async () => {
    // Load customer accounts if not already loaded
    if (customers.value.length === 0) {
        await loadCustomerAccounts();
    }
    showCustomerModal.value = true;
};

// Load customer accounts from API
const loadCustomerAccounts = async () => {
    try {
        const response = await axios.get('/api/customers-with-status');
        const data = response.data;

        if (Array.isArray(data)) {
            customers.value = data;
        } else if (data.data && Array.isArray(data.data)) {
            customers.value = data.data;
        }
    } catch (error) {
        console.error('Error loading customer accounts:', error);
        alert('Failed to load customer accounts: ' + (error.response?.data?.message || error.message));
    }
};

// Select customer from modal
const selectCustomer = async (account) => {
    // Set customer code and name from selected account
    form.value.customer_code = account.customer_code;
    selectedCustomer.value = {
        code: account.customer_code,
        name: account.customer_name
    };
    showCustomerModal.value = false;

    // Check if customer has existing indices
    await checkCustomerIndices();
};

// Check if customer has existing tax indices
const checkCustomerIndices = async () => {
    if (!form.value.customer_code) return;

    try {
        const response = await axios.get(`/api/invoices/customer-tax-indices/${form.value.customer_code}`);
        if (response.data.success && response.data.data.length > 0) {
            // Customer has indices, suggest opening index table
            console.log('Customer has', response.data.data.length, 'tax indices');
        }
    } catch (error) {
        console.error('Error checking customer indices:', error);
    }
};

// Open index table modal
const openIndexTableModal = () => {
    if (!form.value.customer_code) {
        alert('Please select a customer first.');
        return;
    }
    showIndexTableModal.value = true;
};

// Select index from modal
const selectIndex = (indexData) => {
    // indexData contains: index_number, tax_group_code, tax_group_name, status
    form.value.index_number = indexData.index_number;
    form.value.tax_group_code = indexData.tax_group_code;
    selectedTaxGroup.value = {
        code: indexData.tax_group_code,
        name: indexData.tax_group_name || ''
    };
    form.value.status = indexData.status;
    showIndexTableModal.value = false;
    recordMode.value = 'review';
};

// Open tax group modal
const openTaxGroupModal = () => {
    showTaxGroupModal.value = true;
};

// Select tax group
const selectTaxGroup = (taxGroup) => {
    form.value.tax_group_code = taxGroup.code;
    selectedTaxGroup.value = taxGroup;
    showTaxGroupModal.value = false;
};

// Open product tie-up modal
const openProductTieUpModal = () => {
    if (!form.value.customer_code || !form.value.index_number) {
        alert('Please save the tax index first before defining product tie-ups.');
        return;
    }
    showProductTieUpModal.value = true;
};

// Handle product tie-up saved
const handleProductTieUpSaved = () => {
    console.log('Product tie-ups saved successfully');
};

// Handle save
const handleSave = async () => {
    // Validation
    if (!form.value.customer_code) {
        alert('Please select a customer.');
        return;
    }

    if (!form.value.index_number || form.value.index_number < 1) {
        alert('Please enter a valid index number.');
        return;
    }

    if (!form.value.tax_group_code) {
        alert('Please select a tax group.');
        return;
    }

    if (!confirm('Confirm Saving / Updating ?')) {
        return;
    }

    try {
        const response = await axios.post('/api/invoices/customer-tax-indices', form.value);

        if (response.data.success) {
            alert('Customer sales tax index saved successfully!');
            recordMode.value = 'review';
        }
    } catch (error) {
        console.error('Error saving:', error);
        alert('Failed to save: ' + (error.response?.data?.message || error.message));
    }
};

// Handle delete
const handleDelete = async () => {
    if (!confirm('Are you sure you want to delete this tax index?')) {
        return;
    }

    try {
        const response = await axios.delete(
            `/api/invoices/customer-tax-indices/${form.value.customer_code}/${form.value.index_number}`
        );

        if (response.data.success) {
            alert('Tax index deleted successfully!');
            handleCancel();
        }
    } catch (error) {
        console.error('Error deleting:', error);
        alert('Failed to delete: ' + (error.response?.data?.message || error.message));
    }
};

// Handle cancel
const handleCancel = () => {
    form.value = {
        customer_code: '',
        index_number: null,
        tax_group_code: '',
        status: 'A'
    };
    selectedCustomer.value = null;
    selectedTaxGroup.value = null;
    recordMode.value = 'select';
};

// Watch for customer and index changes to set add mode
watch([() => form.value.customer_code, () => form.value.index_number], ([customer, index]) => {
    if (customer && index && recordMode.value === 'select') {
        recordMode.value = 'add';
    }
});
</script>
