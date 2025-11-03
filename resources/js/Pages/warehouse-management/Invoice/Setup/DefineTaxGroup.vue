<template>
    <AppLayout :header="'Define Tax Group'">
        <!-- Header Section -->
        <div class="bg-gradient-to-r from-blue-600 via-cyan-600 to-teal-500 p-6 rounded-t-lg shadow-lg overflow-hidden relative">
            <div class="absolute top-0 right-0 w-40 h-40 bg-white opacity-5 rounded-full -translate-y-20 translate-x-20 animate-pulse-slow"></div>
            <div class="absolute bottom-0 left-0 w-20 h-20 bg-white opacity-5 rounded-full translate-y-10 -translate-x-10 animate-pulse-slow animation-delay-500"></div>
            <div class="flex items-center">
                <div class="bg-gradient-to-br from-cyan-500 to-teal-600 p-3 rounded-lg shadow-inner mr-4">
                    <i class="fas fa-layer-group text-white text-2xl"></i>
                </div>
                <div>
                    <h2 class="text-2xl md:text-3xl font-bold text-white text-shadow">Define Tax Group</h2>
                    <p class="text-teal-100">Organize tax types into groups for invoicing.</p>
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
                                <h3 class="text-xl font-semibold text-gray-800">Tax Group Management</h3>
                            </div>
                            <!-- Search and Actions -->
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6 relative z-10">
                                <div class="md:col-span-2">
                                    <label for="taxGroupCode" class="block text-sm font-medium text-gray-700 mb-2">
                                        Tax Group Code:
                                    </label>
                                    <div class="relative flex group">
                                        <input
                                            id="taxGroupCode"
                                            v-model.trim="form.code"
                                            @input="handleCodeInput"
                                            @keypress.enter="handleEnterKey"
                                            type="text"
                                            placeholder="Enter tax group code..."
                                            class="input-field"
                                            :readonly="recordMode === 'review'"
                                            :class="{ 'bg-gray-100 cursor-not-allowed': recordMode === 'review', 'bg-white': recordMode !== 'review' }"
                                        />
                                        <button
                                            type="button"
                                            @click="openTableModal"
                                            class="lookup-button from-blue-500 to-cyan-500 hover:from-blue-600 hover:to-cyan-600"
                                            title="Tax Group Table"
                                        >
                                            <i class="fas fa-table"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="md:col-span-1 flex flex-col justify-end">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">&nbsp;</label>
                                    <button
                                        type="button"
                                        @click="handleNew"
                                        class="primary-button group w-full h-[42px]"
                                    >
                                        <span class="shimmer-effect"></span>
                                        <i class="fas fa-plus-circle mr-2 group-hover:rotate-90 transition-transform duration-300"></i>
                                        New Tax Group
                                    </button>
                                </div>
                            </div>

                            <!-- Form Fields (shown only when tax group selected or creating new) -->
                            <div v-if="recordMode !== 'select'" class="relative z-10 space-y-6">
                                <!-- Tax Group Name -->
                                <div>
                                    <label for="taxGroupName" class="block text-sm font-medium text-gray-700 mb-2">
                                        Tax Group Name:
                                    </label>
                                    <input
                                        id="taxGroupName"
                                        type="text"
                                        v-model="form.name"
                                        placeholder="Enter tax group name..."
                                        class="modal-input"
                                        required
                                    />
                                </div>

                                <!-- Sales Tax Applied -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Sales Tax Applied:
                                    </label>
                                    <div class="flex gap-8">
                                        <label class="flex items-center gap-2 cursor-pointer">
                                            <input 
                                                type="radio" 
                                                v-model="form.sales_tax_applied" 
                                                value="Y" 
                                                class="w-4 h-4 text-blue-600" 
                                            />
                                            <span class="text-sm font-medium">Y-Yes</span>
                                        </label>
                                        <label class="flex items-center gap-2 cursor-pointer">
                                            <input 
                                                type="radio" 
                                                v-model="form.sales_tax_applied" 
                                                value="N" 
                                                class="w-4 h-4 text-blue-600" 
                                            />
                                            <span class="text-sm font-medium">N-No</span>
                                        </label>
                                    </div>
                                </div>

                                <!-- Tax Item Screen Button (only in review mode) -->
                                <div v-if="recordMode === 'review'" class="pt-4 border-t border-gray-200">
                                    <button
                                        type="button"
                                        @click="openTaxItemScreen"
                                        class="w-full px-4 py-3 bg-gradient-to-r from-teal-500 to-cyan-500 hover:from-teal-600 hover:to-cyan-600 text-white rounded-lg flex items-center justify-center gap-2 font-medium shadow-lg hover:shadow-xl transition-all"
                                    >
                                        <i class="fas fa-list"></i>
                                        Tax Item Screen
                                    </button>
                                </div>

                                <!-- Action Buttons -->
                                <div class="flex justify-between items-center pt-6 border-t border-gray-200">
                                    <button
                                        v-if="recordMode === 'review'"
                                        type="button"
                                        @click="handleDelete"
                                        class="secondary-button group text-red-600 border-red-300 hover:bg-red-50"
                                    >
                                        <i class="fas fa-trash-alt mr-2"></i>
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
                            <div class="text-gray-700 mb-4">
                                Use this form to define tax groups for invoicing. Tax groups categorize similar tax types.
                            </div>
                            <div class="bg-blue-50 rounded-lg p-4">
                                <div class="font-bold text-blue-700 mb-2">Instructions:</div>
                                <ul class="list-disc pl-5 text-blue-700 space-y-1 text-sm">
                                    <li>Enter tax group code to search</li>
                                    <li>Click table button to view all groups</li>
                                    <li>Fill all required fields</li>
                                    <li>View tax types per group</li>
                                    <li>Click Save to apply changes</li>
                                </ul>
                            </div>
                        </div>

                        <!-- Quick Links Card -->
                        <div class="bg-white rounded-xl shadow-md border-t-4 border-cyan-400 p-6">
                            <div class="flex items-center mb-2">
                                <div class="inline-flex items-center justify-center w-10 h-10 bg-gradient-to-br from-cyan-400 to-blue-400 rounded-lg mr-3">
                                    <i class="fas fa-link text-white text-2xl"></i>
                                </div>
                                <h3 class="text-xl font-bold text-gray-800">Quick Links</h3>
                            </div>
                            <hr class="my-2 border-cyan-100">
                            <div class="space-y-3 mt-4">
                                <a href="#" class="flex items-center p-3 rounded-lg bg-green-50 hover:bg-green-100 transition">
                                    <span class="inline-flex items-center justify-center w-9 h-9 bg-green-400 rounded-lg mr-3">
                                        <i class="fas fa-print text-white text-xl"></i>
                                    </span>
                                    <div>
                                        <div class="font-bold text-green-800">View & Print</div>
                                        <div class="text-xs text-green-700">Print tax group list</div>
                                    </div>
                                </a>
                                <a href="#" class="flex items-center p-3 rounded-lg bg-blue-50 hover:bg-blue-100 transition">
                                    <span class="inline-flex items-center justify-center w-9 h-9 bg-blue-400 rounded-lg mr-3">
                                        <i class="fas fa-list text-white text-xl"></i>
                                    </span>
                                    <div>
                                        <div class="font-bold text-blue-800">Tax Types</div>
                                        <div class="text-xs text-blue-700">Manage tax types per group</div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tax Group Modal Component -->
        <TaxGroupModal
            v-if="showTableModal"
            :show="showTableModal"
            @close="showTableModal = false"
            @select="onTaxGroupSelected"
        />

        <!-- Tax Item Screen Modal -->
        <TaxItemScreenModal
            v-if="showTaxItemScreen"
            :show="showTaxItemScreen"
            :taxGroupCode="form.code"
            @close="showTaxItemScreen = false"
        />

        <!-- Loading Overlay -->
        <div v-if="saving" class="fixed inset-0 z-50 bg-black bg-opacity-50 flex justify-center items-center">
            <div class="w-12 h-12 border-4 border-solid border-purple-500 border-t-transparent rounded-full animate-spin"></div>
        </div>

        <!-- Notification Toast -->
        <div v-if="notification.show" class="fixed bottom-5 right-5 z-50 shadow-xl rounded-lg transition-all duration-300 transform"
             :class="{
                 'bg-green-100 border-l-4 border-green-500': notification.type === 'success',
                 'bg-red-100 border-l-4 border-red-500': notification.type === 'error',
                 'bg-yellow-100 border-l-4 border-yellow-500': notification.type === 'warning',
                 'translate-x-0 opacity-100': notification.show,
                 'translate-x-full opacity-0': !notification.show
             }">
            <div class="p-4 flex items-center">
                <div class="mr-3">
                    <i v-if="notification.type === 'success'" class="fas fa-check-circle text-green-500 text-xl"></i>
                    <i v-else-if="notification.type === 'error'" class="fas fa-exclamation-circle text-red-500 text-xl"></i>
                    <i v-else class="fas fa-exclamation-triangle text-yellow-500 text-xl"></i>
                </div>
                <div>
                    <p class="font-medium" :class="{
                        'text-green-800': notification.type === 'success',
                        'text-red-800': notification.type === 'error',
                        'text-yellow-800': notification.type === 'warning'
                    }">{{ notification.message }}</p>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import TaxGroupModal from '@/Components/TaxGroupModal.vue';
import TaxItemScreenModal from '@/Components/TaxItemScreenModal.vue';
import axios from 'axios';

// UI State
const showTableModal = ref(false);
const showTaxItemScreen = ref(false);
const loading = ref(false);
const saving = ref(false);
const recordMode = ref('select'); // 'select', 'add', 'review'

// Data
const taxGroups = ref([]);
const form = ref({
    code: '',
    name: '',
    sales_tax_applied: 'Y'
});
const originalCode = ref(''); // Track original code for updates

// Notification
const notification = ref({
    show: false,
    message: '',
    type: 'success'
});

const showNotification = (message, type = 'success') => {
    notification.value = {
        show: true,
        message,
        type
    };
    setTimeout(() => {
        notification.value.show = false;
    }, 3000);
};

// Methods
const fetchTaxGroups = async () => {
    loading.value = true;
    try {
        const response = await axios.get('/api/invoices/tax-groups');
        if (response.data && response.data.success) {
            taxGroups.value = response.data.data || [];
        }
    } catch (e) {
        console.error('Error fetching tax groups:', e);
        taxGroups.value = [];
        showNotification('Failed to load tax groups.', 'error');
    } finally {
        loading.value = false;
    }
};

const openTableModal = () => {
    showTableModal.value = true;
};

const handleNew = () => {
    // Create new tax group mode
    form.value = {
        code: '',
        name: '',
        sales_tax_applied: 'Y'
    };
    originalCode.value = '';
    recordMode.value = 'add';
    showNotification('Create new tax group - Enter code and details', 'success');
    
    // Focus on code input
    setTimeout(() => {
        const codeInput = document.getElementById('taxGroupCode');
        if (codeInput) {
            codeInput.removeAttribute('readonly');
            codeInput.classList.remove('bg-gray-100', 'cursor-not-allowed');
            codeInput.focus();
        }
    }, 100);
};

const handleCodeInput = () => {
    // User is typing a code
    const code = form.value.code?.trim();
    if (code && recordMode.value === 'select') {
        // Prepare to check if this is new or existing when Enter is pressed
    }
};

const handleEnterKey = async () => {
    const code = form.value.code?.trim().toUpperCase();
    if (!code) return;
    
    // Try to find existing tax group
    try {
        const response = await axios.get(`/api/invoices/tax-groups/${code}`);
        if (response.data && response.data.success && response.data.data) {
            // Found existing tax group
            onTaxGroupSelected(response.data.data);
        }
    } catch (e) {
        // Not found, treat as new
        if (e.response?.status === 404) {
            form.value.code = code;
            form.value.name = '';
            form.value.sales_tax_applied = 'Y';
            originalCode.value = '';
            recordMode.value = 'add';
            showNotification('Creating new tax group: ' + code, 'success');
        } else {
            console.error('Error checking tax group:', e);
            showNotification('Error checking tax group', 'error');
        }
    }
};

const onTaxGroupSelected = (group) => {
    showTableModal.value = false;
    
    // Load the selected tax group into form
    form.value = {
        code: group.code,
        name: group.name || '',
        sales_tax_applied: group.sales_tax_applied || 'Y'
    };
    originalCode.value = group.code;
    recordMode.value = 'review';
    
    showNotification('Tax group loaded: ' + group.code, 'success');
};

const handleCancel = () => {
    form.value = {
        code: '',
        name: '',
        sales_tax_applied: 'Y'
    };
    originalCode.value = '';
    recordMode.value = 'select';
};

const handleSave = async () => {
    if (!form.value.code || !form.value.name) {
        showNotification('Tax Group Code and Name are required.', 'error');
        return;
    }
    
    // Show confirmation dialog
    if (!confirm('Confirm Saving / Updating ?')) {
        return;
    }
    
    saving.value = true;
    try {
        let response;
        const payload = {
            code: form.value.code.toUpperCase(),
            name: form.value.name,
            sales_tax_applied: form.value.sales_tax_applied || 'Y'
        };
        
        if (recordMode.value === 'add' || !originalCode.value) {
            // Create new tax group
            response = await axios.post('/api/invoices/tax-groups', payload);
        } else {
            // Update existing tax group
            response = await axios.put(`/api/invoices/tax-groups/${originalCode.value}`, payload);
        }
        
        const result = response.data;
        if (result.success) {
            showNotification(
                recordMode.value === 'add' ? 'Tax group created successfully!' : 'Tax group updated successfully!',
                'success'
            );
            await fetchTaxGroups();
            
            // Keep in review mode after save
            originalCode.value = form.value.code.toUpperCase();
            recordMode.value = 'review';
        } else {
            showNotification('Error: ' + (result.message || 'Unknown error'), 'error');
        }
    } catch (e) {
        const errorMessage = e.response?.data?.message || e.message || 'An error occurred';
        console.error('Error saving tax group:', e);
        showNotification(`Error saving tax group: ${errorMessage}`, 'error');
    } finally {
        saving.value = false;
    }
};

const handleDelete = async () => {
    if (!form.value.code) {
        return;
    }
    
    if (!confirm(`Are you sure you want to delete tax group "${form.value.code}"? This action cannot be undone.`)) {
        return;
    }
    
    saving.value = true;
    try {
        const response = await axios.delete(`/api/invoices/tax-groups/${form.value.code}`);
        const result = response.data;
        if (result.success) {
            showNotification('Tax group deleted successfully.', 'success');
            await fetchTaxGroups();
            handleCancel(); // Reset form
        } else {
            showNotification('Error deleting tax group: ' + (result.message || 'Unknown error'), 'error');
        }
    } catch (e) {
        const errorMessage = e.response?.data?.message || e.message || 'An error occurred';
        console.error('Error deleting tax group:', e);
        showNotification(`Error deleting tax group: ${errorMessage}`, 'error');
    } finally {
        saving.value = false;
    }
};

const openTaxItemScreen = () => {
    if (!form.value.code) {
        showNotification('Please select a tax group first.', 'warning');
        return;
    }
    showTaxItemScreen.value = true;
};

onMounted(() => {
    fetchTaxGroups();
});
</script>

<style scoped>
.text-shadow {
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.input-field {
    @apply flex-1 min-w-0 block w-full px-3 py-2 rounded-l-md border-gray-300 focus:ring-cyan-500 focus:border-cyan-500 transition-all duration-300 group-hover:border-cyan-400 shadow-sm focus:shadow-md;
}

.lookup-button {
    @apply inline-flex items-center px-4 py-2 border border-l-0 border-gray-300 rounded-r-md transition-all duration-300 bg-gradient-to-r text-white shadow-sm hover:shadow-md transform hover:-translate-y-px;
}

.primary-button {
    @apply items-center justify-center bg-gradient-to-r from-blue-500 to-cyan-500 text-white font-bold py-2 px-6 rounded-md shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300 flex relative overflow-hidden;
}

.secondary-button {
    @apply items-center justify-center bg-gradient-to-r from-gray-100 to-gray-200 text-gray-700 font-bold py-2 px-6 rounded-md shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300 flex relative overflow-hidden border border-gray-300;
}

.modal-input {
    @apply block w-full rounded-md border-gray-300 shadow-sm focus:border-cyan-500 focus:ring-cyan-200 focus:ring-opacity-50 transition-shadow;
}

@keyframes shake {
    0%, 100% { transform: translateX(0); }
    25% { transform: translateX(-3px) rotate(-2deg); }
    75% { transform: translateX(3px) rotate(2deg); }
}
.group-hover\:animate-shake:hover {
    animation: shake 0.3s ease-in-out;
}

.shimmer-effect {
    @apply absolute top-0 -left-[150%] h-full w-[50%] skew-x-[-25deg] bg-white/20;
    animation: shimmer 2.5s infinite;
}

@keyframes shimmer {
    100% {
        left: 150%;
    }
}

@keyframes pulse-slow {
    0%, 100% { transform: scale(1); opacity: 0.05; }
    50% { transform: scale(1.1); opacity: 0.08; }
}

.animate-pulse-slow {
    animation: pulse-slow 5s infinite;
}

.animation-delay-500 {
    animation-delay: 0.5s;
}

@keyframes fade-in-up {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fade-in-up {
    animation: fade-in-up 0.6s ease-out;
}
</style>
