<template>
    <AppLayout :header="'Define Tax Type'">
        <!-- Header Section -->
        <div class="bg-gradient-to-r from-blue-600 via-cyan-600 to-teal-500 p-6 rounded-t-lg shadow-lg overflow-hidden relative">
            <div class="absolute top-0 right-0 w-40 h-40 bg-white opacity-5 rounded-full -translate-y-20 translate-x-20 animate-pulse-slow"></div>
            <div class="absolute bottom-0 left-0 w-20 h-20 bg-white opacity-5 rounded-full translate-y-10 -translate-x-10 animate-pulse-slow animation-delay-500"></div>
            <div class="flex items-center">
                <div class="bg-gradient-to-br from-cyan-500 to-teal-600 p-3 rounded-lg shadow-inner mr-4">
                    <i class="fas fa-percent text-white text-2xl"></i>
                </div>
                <div>
                    <h2 class="text-2xl md:text-3xl font-bold text-white text-shadow">Define Tax Type</h2>
                    <p class="text-teal-100">Manage sales tax types and rates for invoicing.</p>
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
                                <h3 class="text-xl font-semibold text-gray-800">Tax Type Management</h3>
                            </div>
                            <!-- Search and Actions -->
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6 relative z-10">
                                <div class="md:col-span-2">
                                    <label for="taxCode" class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                                        <span class="flex items-center justify-center h-6 w-6 rounded-full bg-gradient-to-br from-cyan-500 to-teal-500 text-white mr-3 shadow-md">
                                            <i class="fas fa-search text-xs"></i>
                                        </span>
                                        Find Tax Type
                                    </label>
                                    <div class="relative flex group">
                                        <input
                                            id="taxCode"
                                            v-model.trim="form.code"
                                            @input="handleCodeInput"
                                            @keypress.enter="openTableModal"
                                            type="text"
                                            placeholder="Search by tax code..."
                                            class="input-field"
                                        />
                                        <button
                                            type="button"
                                            @click="openTableModal"
                                            class="lookup-button from-blue-500 to-cyan-500 hover:from-blue-600 hover:to-cyan-600"
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
                                        class="primary-button group w-full"
                                    >
                                        <span class="shimmer-effect"></span>
                                        <i class="fas fa-plus-circle mr-2 group-hover:rotate-90 transition-transform duration-300"></i>
                                        New Tax Type
                                    </button>
                                </div>
                            </div>
                            <!-- Data Status Information -->
                            <div class="relative z-10 mt-4 p-4 rounded-lg shadow-inner border" :class="{
                                'bg-yellow-50 border-yellow-200 text-yellow-800': !showForm,
                                'bg-green-50 border-green-200 text-green-800': showForm
                            }">
                                <div v-if="!showForm">
                                    <p class="text-sm font-medium">No tax type selected.</p>
                                    <p class="text-xs text-yellow-700 mt-1">Search for a tax code or click the table button to select.</p>
                                </div>
                                <div v-else>
                                    <p class="text-sm font-medium">Tax Type Loaded: {{ form.code }}</p>
                                    <p class="text-xs text-green-700 mt-1">Mode: {{ recordMode === 'review' ? 'Review/Edit' : 'New Entry' }}</p>
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
                                Use this form to define tax types for invoicing. Ensure all information entered is accurate.
                            </div>
                            <div class="bg-blue-50 rounded-lg p-4">
                                <div class="font-bold text-blue-700 mb-2">Instructions:</div>
                                <ul class="list-disc pl-5 text-blue-700 space-y-1 text-sm">
                                    <li>Enter tax code to search</li>
                                    <li>Click table button to view all tax types</li>
                                    <li>Fill all required fields</li>
                                    <li>Follow Financial Tax Reporter Code</li>
                                    <li>Click Save to apply changes</li>
                                </ul>
                            </div>
                        </div>

                        <!-- Quick Links Card -->
                        <div class="bg-white rounded-xl shadow-md border-t-4 border-purple-400 p-6">
                            <div class="flex items-center mb-2">
                                <div class="inline-flex items-center justify-center w-10 h-10 bg-gradient-to-br from-purple-400 to-pink-400 rounded-lg mr-3">
                                    <i class="fas fa-link text-white text-2xl"></i>
                                </div>
                                <h3 class="text-xl font-bold text-gray-800">Quick Links</h3>
                            </div>
                            <hr class="my-2 border-purple-100">
                            <div class="space-y-3 mt-4">
                                <a href="#" class="flex items-center p-3 rounded-lg bg-green-50 hover:bg-green-100 transition">
                                    <span class="inline-flex items-center justify-center w-9 h-9 bg-green-400 rounded-lg mr-3">
                                        <i class="fas fa-print text-white text-xl"></i>
                                    </span>
                                    <div>
                                        <div class="font-bold text-green-800">View & Print</div>
                                        <div class="text-xs text-green-700">Print tax type list</div>
                                    </div>
                                </a>
                                <a href="#" class="flex items-center p-3 rounded-lg bg-blue-50 hover:bg-blue-100 transition">
                                    <span class="inline-flex items-center justify-center w-9 h-9 bg-blue-400 rounded-lg mr-3">
                                        <i class="fas fa-calculator text-white text-xl"></i>
                                    </span>
                                    <div>
                                        <div class="font-bold text-blue-800">Tax Calculator</div>
                                        <div class="text-xs text-blue-700">Calculate tax amounts</div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Modal -->
        <div v-if="showForm" class="fixed inset-0 z-50 bg-black bg-opacity-60 flex items-center justify-center transition-opacity" @click.self="closeForm">
            <div class="bg-white rounded-lg shadow-xl w-11/12 md:w-3/5 max-w-2xl mx-auto transform transition-all scale-95" :class="{'scale-100': showForm}">
                <div class="flex items-center justify-between p-4 border-b border-gray-200 bg-gradient-to-r from-blue-600 to-cyan-500 text-white rounded-t-lg">
                    <div class="flex items-center">
                        <div class="p-2 bg-white bg-opacity-20 rounded-lg mr-3">
                            <i class="fas fa-percent"></i>
                        </div>
                        <h3 class="text-xl font-semibold">{{ recordMode === 'review' ? 'Edit Tax Type' : 'Create Tax Type' }}</h3>
                    </div>
                    <button type="button" @click="closeForm" class="text-white hover:text-gray-200 p-2 rounded-full hover:bg-white/20">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="p-6 bg-gray-50">
                    <form @submit.prevent="handleSave" class="space-y-6">
                        <div>
                            <label for="code" class="block text-sm font-medium text-gray-700 mb-1 flex items-center">
                                <i class="fas fa-barcode text-gray-400 w-5 mr-2"></i> Tax Code:
                            </label>
                            <input
                                id="code"
                                v-model="form.code"
                                type="text"
                                class="modal-input"
                                :class="{ 'bg-gray-200 cursor-not-allowed': recordMode === 'review' }"
                                :readonly="recordMode === 'review'"
                                required
                            />
                            <span class="text-xs text-gray-500 mt-1">Tax code must be unique and follow Financial Tax Reporter Code.</span>
                        </div>
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1 flex items-center">
                                <i class="fas fa-pen-alt text-gray-400 w-5 mr-2"></i> Tax Name:
                            </label>
                            <input
                                id="name"
                                v-model.trim="form.name"
                                type="text"
                                class="modal-input"
                                required
                            />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1 flex items-center">
                                <i class="fas fa-check-circle text-gray-400 w-5 mr-2"></i> Tax Apply:
                            </label>
                            <div class="flex gap-6">
                                <label class="flex items-center gap-2">
                                    <input type="radio" v-model="form.apply" value="Y" class="text-cyan-600" />
                                    <span class="text-sm">Y-Yes</span>
                                </label>
                                <label class="flex items-center gap-2">
                                    <input type="radio" v-model="form.apply" value="N" class="text-cyan-600" />
                                    <span class="text-sm">N-No</span>
                                </label>
                            </div>
                        </div>
                        <div>
                            <label for="rate" class="block text-sm font-medium text-gray-700 mb-1 flex items-center">
                                <i class="fas fa-percentage text-gray-400 w-5 mr-2"></i> Tax Rate %:
                            </label>
                            <input
                                id="rate"
                                v-model.number="form.rate"
                                type="number"
                                step="0.01"
                                min="0"
                                max="100"
                                class="modal-input"
                                required
                            />
                        </div>
                        <div>
                            <label for="custom_type" class="block text-sm font-medium text-gray-700 mb-1 flex items-center">
                                <i class="fas fa-tag text-gray-400 w-5 mr-2"></i> Custom Type:
                            </label>
                            <select
                                id="custom_type"
                                v-model="form.custom_type"
                                class="modal-input"
                            >
                                <option v-for="opt in customTypeOptions" :key="opt" :value="opt">{{ opt }}</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="flex justify-between items-center p-4 bg-gray-100 border-t border-gray-200 rounded-b-lg">
                    <button
                        v-if="recordMode === 'review'"
                        type="button"
                        @click="handleDelete"
                        class="secondary-button group text-red-600 border-red-200 hover:bg-red-50"
                    >
                        <i class="fas fa-trash-alt mr-2 group-hover:animate-shake"></i>Delete
                    </button>
                    <div v-else class="w-24"></div>
                    <div class="flex space-x-3">
                        <button type="button" @click="closeForm" class="secondary-button group">Cancel</button>
                        <button @click="handleSave" class="primary-button group">
                            <span class="shimmer-effect"></span>
                            <i class="fas fa-save mr-2"></i>
                            Save
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tax Sales Tax Type Table Modal (CPS-style) -->
        <div v-if="showTable" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg shadow-2xl w-4/5 max-w-4xl max-h-[70vh] flex flex-col">
                <!-- Modal Header -->
                <div class="bg-gradient-to-r from-teal-600 to-cyan-600 text-white px-4 py-3 rounded-t-lg flex justify-between items-center">
                    <div class="flex items-center gap-2">
                        <i class="fas fa-table"></i>
                        <h3 class="text-sm font-semibold">Tax Sales Tax Type Table</h3>
                    </div>
                    <button @click="showTable = false" class="text-white/90 hover:text-white">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <!-- Table Content -->
                <div class="flex-1 overflow-auto p-4">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-100 border-b-2 border-gray-300">
                            <tr>
                                <th class="px-4 py-2 text-left font-semibold">Tax</th>
                                <th class="px-4 py-2 text-left font-semibold">Tax Name</th>
                                <th class="px-4 py-2 text-left font-semibold">Apply</th>
                                <th class="px-4 py-2 text-left font-semibold">Tax Rate %</th>
                                <th class="px-4 py-2 text-left font-semibold">Custom Type</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                                v-for="tx in taxTypes"
                                :key="tx.code"
                                @dblclick="selectFromTable(tx)"
                                :class="selectedRow === tx.code ? 'bg-blue-500 text-white' : 'hover:bg-gray-100'"
                                @click="selectedRow = tx.code"
                                class="cursor-pointer border-b"
                            >
                                <td class="px-4 py-2">{{ tx.code }}</td>
                                <td class="px-4 py-2">{{ tx.name }}</td>
                                <td class="px-4 py-2">{{ tx.apply }}</td>
                                <td class="px-4 py-2">{{ Number(tx.rate).toFixed(2) }}</td>
                                <td class="px-4 py-2">{{ tx.custom_type }}</td>
                            </tr>
                            <tr v-if="taxTypes.length === 0">
                                <td colspan="5" class="px-4 py-6 text-center text-gray-500">No tax types found</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Modal Footer -->
                <div class="px-4 py-3 bg-gray-50 border-t flex justify-center gap-3">
                    <button @click="selectFromTable(getSelectedTaxType())" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded text-sm">
                        Select
                    </button>
                    <button @click="showTable = false" class="px-6 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 rounded text-sm">
                        Exit
                    </button>
                </div>
            </div>
        </div>

        <!-- Confirmation Dialog (CPS-style) -->
        <div v-if="showConfirmation" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg shadow-2xl w-96">
                <div class="bg-blue-600 text-white px-4 py-3 rounded-t-lg">
                    <h3 class="text-sm font-semibold">Confirmation</h3>
                </div>
                <div class="p-6 flex items-center gap-4">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center">
                            <i class="fas fa-question text-blue-600 text-2xl"></i>
                        </div>
                    </div>
                    <div class="flex-1">
                        <p class="text-gray-800 font-medium">{{ confirmationMessage }}</p>
                    </div>
                </div>
                <div class="px-6 pb-4 flex justify-center gap-3">
                    <button @click="confirmAction" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded text-sm">
                        OK
                    </button>
                    <button @click="cancelAction" class="px-6 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 rounded text-sm">
                        Cancel
                    </button>
                </div>
            </div>
        </div>

        <!-- Loading Overlay -->
        <div v-if="saving" class="fixed inset-0 z-50 bg-black bg-opacity-50 flex justify-center items-center">
            <div class="w-12 h-12 border-4 border-solid border-blue-500 border-t-transparent rounded-full animate-spin"></div>
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
import { ref, computed, onMounted } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import axios from 'axios';

// UI State
const recordMode = ref('select'); // 'select' | 'review'
const showTable = ref(false);
const showForm = ref(false);
const showConfirmation = ref(false);
const confirmationMessage = ref('');
const confirmationAction = ref(null);
const selectedRow = ref(null);

// Data
const form = ref({
    code: '',
    name: '',
    apply: 'Y',
    rate: 0.00,
    custom_type: 'N-NIL',
});

const customTypeOptions = [
    'N-NIL',
    '01-FTZ CUSTOM FORM 2',
    '02-LMW LAMPIRAN H',
    '03-LMW CUSTOM FORM 9',
    '04-LMW LAMPIRAN GPB-1',
    '05-LMW LAMPIRAN GPB-2',
    '06-LMW LAMPIRAN J',
    '07-CJP(2)',
    '08-LMW LAMPIRAN I',
    'S1-SST, FTZ',
    'S2-SST, LMW',
    'SA-SST, SCHEDULE A',
    'SB-SST, SCHEDULE B',
    'SC-SST, SCHEDULE C',
];

const taxTypes = ref([]);
const originalData = ref(null);
const notification = ref({ show: false, message: '', type: 'success' });
const saving = ref(false);

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
const resetForm = () => {
    form.value = {
        code: '',
        name: '',
        apply: 'Y',
        rate: 0.00,
        custom_type: 'N-NIL',
    };
    showForm.value = false;
    recordMode.value = 'select';
    originalData.value = null;
};

const openTableModal = async () => {
    await loadTaxTypes();
    showTable.value = true;
};

const getSelectedTaxType = () => {
    return taxTypes.value.find(t => t.code === selectedRow.value);
};

const selectFromTable = (tx) => {
    if (!tx) return;
    form.value.code = tx.code;
    form.value.name = tx.name;
    form.value.apply = tx.apply;
    form.value.rate = Number(tx.rate);
    form.value.custom_type = tx.custom_type;
    originalData.value = { ...tx };
    recordMode.value = 'review';
    showForm.value = true;
    showTable.value = false;
};

const handleCodeInput = async () => {
    if (!form.value.code) {
        resetForm();
        return;
    }
    try {
        const res = await axios.get(`/api/invoices/tax-types/${form.value.code}`);
        if (res.data && res.data.success) {
            const data = res.data.data;
            form.value.name = data.name;
            form.value.apply = data.apply;
            form.value.rate = Number(data.rate);
            form.value.custom_type = data.custom_type;
            originalData.value = { ...data };
            recordMode.value = 'review';
            showForm.value = true;
        }
    } catch (e) {
        // Not found - can create new
        recordMode.value = 'select';
        showForm.value = false;
    }
};

const loadTaxTypes = async () => {
    try {
        const res = await axios.get('/api/invoices/tax-types');
        if (res.data && res.data.success) {
            taxTypes.value = res.data.data || [];
        }
    } catch (e) {
        console.error('Error loading tax types:', e);
        taxTypes.value = [];
    }
};

const handleNew = () => {
    form.value = {
        code: '',
        name: '',
        apply: 'Y',
        rate: 0.00,
        custom_type: 'N-NIL',
    };
    recordMode.value = 'select';
    originalData.value = null;
    showForm.value = true;
};

const closeForm = () => {
    showForm.value = false;
};

const handleSave = () => {
    if (!form.value.code || !form.value.name) {
        alert('Please fill Tax Code and Tax Name');
        return;
    }
    confirmationMessage.value = 'Confirm Saving / Updating ?';
    confirmationAction.value = 'save';
    showConfirmation.value = true;
};

const handleDelete = () => {
    if (!form.value.code) {
        alert('Please select a tax type to delete');
        return;
    }
    confirmationMessage.value = 'Confirm Deleting ?';
    confirmationAction.value = 'delete';
    showConfirmation.value = true;
};

const handleRefresh = async () => {
    await loadTaxTypes();
    resetForm();
};

const confirmAction = async () => {
    if (confirmationAction.value === 'save') {
        await saveTaxType();
    } else if (confirmationAction.value === 'delete') {
        await deleteTaxType();
    }
    showConfirmation.value = false;
};

const cancelAction = () => {
    showConfirmation.value = false;
};

const saveTaxType = async () => {
    saving.value = true;
    try {
        const payload = {
            code: form.value.code.toUpperCase(),
            name: form.value.name,
            apply: form.value.apply,
            rate: Number(form.value.rate),
            custom_type: form.value.custom_type,
        };

        let res;
        if (originalData.value) {
            // Update
            res = await axios.put(`/api/invoices/tax-types/${form.value.code}`, payload);
        } else {
            // Create
            res = await axios.post('/api/invoices/tax-types', payload);
        }

        if (res.data && res.data.success) {
            showNotification(res.data.message || 'Tax type saved successfully!', 'success');
            await loadTaxTypes();
            resetForm();
            showForm.value = false;
        }
    } catch (e) {
        showNotification(e.response?.data?.message || 'Error saving tax type', 'error');
    } finally {
        saving.value = false;
    }
};

const deleteTaxType = async () => {
    if (!confirm(`Are you sure you want to delete tax type "${form.value.code}"? This action cannot be undone.`)) {
        return;
    }
    saving.value = true;
    try {
        const res = await axios.delete(`/api/invoices/tax-types/${form.value.code}`);
        if (res.data && res.data.success) {
            showNotification(res.data.message || 'Tax type deleted successfully', 'success');
            await loadTaxTypes();
            resetForm();
            showForm.value = false;
        }
    } catch (e) {
        showNotification(e.response?.data?.message || 'Error deleting tax type', 'error');
    } finally {
        saving.value = false;
    }
};

onMounted(() => {
    loadTaxTypes();
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
    @apply h-full items-center justify-center bg-gradient-to-r from-cyan-500 to-blue-500 text-white font-bold py-2 px-6 rounded-md shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300 flex relative overflow-hidden;
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


