<template>
    <AppLayout :header="'Define Tax Type'">
        <!-- Header Section -->
        <div class="min-h-screen bg-gray-50 py-6 px-4 sm:px-6 lg:px-8">
            <div class="max-w-7xl mx-auto">
                <!-- Header Section -->
                <div class="bg-blue-600 text-white shadow-sm rounded-xl border border-blue-700 mb-4">
                    <div class="px-4 py-3 sm:px-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                        <div class="flex items-center gap-3">
                            <div class="h-9 w-9 rounded-full bg-blue-500 flex items-center justify-center">
                                <i class="fas fa-percent text-white text-sm"></i>
                            </div>
                            <div>
                                <h2 class="text-lg sm:text-xl font-semibold leading-tight">
                                    Define Tax Type
                                </h2>
                                <p class="text-xs sm:text-sm text-blue-100">
                                    Manage sales tax types and rates for invoicing.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8">
                    <!-- Left Column -->
                    <div class="lg:col-span-2 space-y-4">
                        <div class="bg-white shadow-sm rounded-xl border border-gray-200">
                            <div class="px-4 py-3 sm:px-6 border-b border-gray-100 flex items-center">
                                <div class="p-2 bg-blue-500 rounded-lg mr-3 text-white">
                                    <i class="fas fa-edit"></i>
                                </div>
                                <div>
                                    <h3 class="text-sm sm:text-base font-semibold text-slate-800">Tax Type Management</h3>
                                    <p class="text-xs text-slate-500">Search, create, and maintain tax types.</p>
                                </div>
                            </div>
                            <div class="px-4 py-4 sm:px-6">
                                <!-- Search and Actions -->
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                                    <div class="col-span-2">
                                        <label class="block text-sm font-semibold text-slate-700 mb-1">Tax Code</label>
                                        <div class="relative flex">
                                            <input
                                                id="taxCode"
                                                v-model.trim="form.code"
                                                @input="handleCodeInput"
                                                @keypress.enter="openTableModal"
                                                type="text"
                                                placeholder="Search or type tax code"
                                                class="flex-1 min-w-0 block w-full px-3 py-2 rounded-l-md border border-gray-200 focus:ring-blue-500 focus:border-blue-500 text-slate-800 placeholder-slate-400 text-sm"
                                            />
                                            <button
                                                type="button"
                                                @click="openTableModal"
                                                class="inline-flex items-center px-3 py-2 border border-l-0 border-blue-500 bg-blue-600 hover:bg-blue-700 text-white rounded-r-md text-sm"
                                            >
                                                <i class="fas fa-table"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col-span-1">
                                        <label class="block text-sm font-semibold text-slate-700 mb-1">Action</label>
                                        <button
                                            type="button"
                                            @click="handleNew"
                                            class="w-full flex items-center justify-center px-4 py-2 rounded-md bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold shadow-sm"
                                        >
                                            <i class="fas fa-plus-circle mr-2"></i>
                                            Add New
                                        </button>
                                    </div>
                                </div>

                                <!-- Data Status -->
                                <div v-if="!showForm" class="mt-3 bg-amber-50 border border-amber-200 p-3 rounded-lg">
                                    <p class="text-sm font-semibold text-amber-800">No tax type selected.</p>
                                    <p class="text-xs text-amber-700 mt-1">Search or click table button to select.</p>
                                </div>
                                <div v-else class="mt-3 bg-blue-50 border border-blue-200 p-3 rounded-lg">
                                    <p class="text-sm font-semibold text-blue-800">Tax Type: {{ form.code }}</p>
                                    <p class="text-xs text-blue-700 mt-1">Mode: {{ recordMode === 'review' ? 'Review/Edit' : 'New Entry' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column - Quick Info -->
                    <div class="lg:col-span-1 space-y-4">
                        <!-- Info Card -->
                        <div class="bg-white shadow-sm rounded-xl border border-blue-100">
                            <div class="px-4 py-3 sm:px-5 border-b border-blue-100 flex items-center">
                                <div class="p-2 bg-blue-500 rounded-lg mr-3">
                                    <i class="fas fa-info-circle text-white"></i>
                                </div>
                                <h3 class="text-sm sm:text-base font-semibold text-blue-900">Information</h3>
                            </div>
                            <div class="px-4 py-4 sm:px-5">
                                <div class="p-4 bg-blue-50 rounded-lg border border-blue-100">
                                    <h4 class="text-xs font-semibold text-blue-700 uppercase tracking-wider mb-2">Instructions</h4>
                                    <ul class="list-disc pl-5 text-xs sm:text-sm text-slate-600 space-y-1">
                                        <li>Enter tax code to search</li>
                                        <li>Use table button to view all types</li>
                                        <li>Fill all required fields</li>
                                        <li>Save changes to apply</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Modal -->
        <div v-if="showForm" class="fixed inset-0 z-50 bg-black bg-opacity-60 flex items-center justify-center transition-opacity" @click.self="closeForm">
            <div class="bg-white rounded-lg shadow-xl w-11/12 md:w-3/5 max-w-2xl mx-auto transform transition-all scale-95" :class="{'scale-100': showForm}">
                <div class="flex items-center justify-between p-4 border-b border-gray-200 bg-blue-600 text-white rounded-t-lg">
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
                            <label for="code" class="text-sm font-medium text-gray-700 mb-1 flex items-center">
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
                            <label for="name" class="text-sm font-medium text-gray-700 mb-1 flex items-center">
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
                            <label class="text-sm font-medium text-gray-700 mb-1 flex items-center">
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
                            <label for="rate" class="text-sm font-medium text-gray-700 mb-1 flex items-center">
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
                            <label for="custom_type" class="text-sm font-medium text-gray-700 mb-1 flex items-center">
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
                        class="px-4 py-2 bg-orange-500 text-white rounded-lg hover:bg-orange-600 text-sm font-medium flex items-center"
                    >
                        <i class="fas fa-ban mr-2"></i>
                        Obsolete
                    </button>
                    <div v-else class="w-24"></div>
                    <div class="flex space-x-3">
                        <button type="button" @click="closeForm" class="px-4 py-2 bg-gray-100 text-gray-800 rounded-lg hover:bg-gray-200 text-sm font-medium border border-gray-300">Cancel</button>
                        <button
                            @click="handleSave"
                            :disabled="saving"
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm font-medium disabled:opacity-50 disabled:cursor-not-allowed"
                        >
                            <i v-if="saving" class="fas fa-spinner fa-spin mr-2"></i>
                            <i v-else class="fas fa-save mr-2"></i>
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
                <div class="bg-blue-600 text-white px-4 py-3 rounded-t-lg flex justify-between items-center">
                    <div class="flex items-center gap-2">
                        <i class="fas fa-table"></i>
                        <h3 class="text-sm font-semibold">Sales Tax Type Table</h3>
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
                                <th class="px-4 py-2 text-left font-semibold">Status</th>
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
                                <td class="px-4 py-2">
                                    {{ tx.status === 'O' ? 'Obsolete' : 'Active' }}
                                </td>
                            </tr>
                            <tr v-if="taxTypes.length === 0">
                                <td colspan="6" class="px-4 py-6 text-center text-gray-500">No tax types found</td>
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

        <!-- Loading Overlay -->
        <div v-if="saving" class="fixed inset-0 z-50 bg-black bg-opacity-50 flex justify-center items-center">
            <div class="w-12 h-12 border-4 border-solid border-blue-500 border-t-transparent rounded-full animate-spin"></div>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import axios from 'axios';
import Swal from 'sweetalert2';

const showSuccessAlert = (message) =>
    Swal.fire({
        icon: 'success',
        title: 'Berhasil',
        text: message,
        confirmButtonColor: '#4f46e5',
    });

const showErrorAlert = (message) =>
    Swal.fire({
        icon: 'error',
        title: 'Gagal',
        text: message,
        confirmButtonColor: '#ef4444',
    });

const showConfirmDialog = async ({ title, text, confirmButtonText = 'Ya, simpan' }) => {
    const result = await Swal.fire({
        title,
        text,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#2563eb',
        cancelButtonColor: '#6b7280',
        confirmButtonText,
        cancelButtonText: 'Batal',
    });
    return result.isConfirmed;
};

// UI State
const recordMode = ref('select'); // 'select' | 'review'
const showTable = ref(false);
const showForm = ref(false);
const selectedRow = ref(null);

// Data
const form = ref({
    code: '',
    name: '',
    apply: 'Y',
    rate: 0.00,
    custom_type: 'N-NIL',
    status: 'A',
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
const saving = ref(false);

const handleObsoleteSelection = (code, message) => {
    showErrorAlert(
        message || `Tax type ${code || ''} is obsolete and cannot be used.`
    );
    resetForm();
};

// Methods
const resetForm = () => {
    form.value = {
        code: '',
        name: '',
        apply: 'Y',
        rate: 0.00,
        custom_type: 'N-NIL',
        status: 'A',
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
    if ((tx.status || '').toUpperCase() === 'O') {
        handleObsoleteSelection(
            tx.code,
            `Tax type ${tx.code} is obsolete and cannot be selected.`
        );
        showTable.value = false;
        return;
    }
    form.value.code = tx.code;
    form.value.name = tx.name;
    form.value.apply = tx.apply;
    form.value.rate = Number(tx.rate);
    form.value.custom_type = tx.custom_type;
    form.value.status = tx.status || 'A';
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
            form.value.status = data.status || 'A';
            originalData.value = { ...data };
            recordMode.value = 'review';
            showForm.value = true;
        }
    } catch (e) {
        const status = e.response?.status;
        if (status === 422) {
            handleObsoleteSelection(
                form.value.code,
                e.response?.data?.message
            );
            return;
        }
        if (status === 404) {
            // Not found - can create new
            recordMode.value = 'select';
            showForm.value = false;
            return;
        }
        console.error('Error fetching tax type:', e);
        showErrorAlert('Failed to fetch tax type data.');
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
        status: 'A',
    };
    recordMode.value = 'select';
    originalData.value = null;
    showForm.value = true;
};

const closeForm = () => {
    showForm.value = false;
};

const handleSave = async () => {
    if (!form.value.code || !form.value.name) {
        showErrorAlert('Please fill Tax Code and Tax Name');
        return;
    }
    const confirmed = await showConfirmDialog({
        title: 'Simpan Tax Type?',
        text: 'Apakah Anda yakin ingin menyimpan perubahan ini?',
        confirmButtonText: 'Ya, simpan',
    });
    if (!confirmed) return;
    await saveTaxType();
};

const handleDelete = async () => {
    if (!form.value.code) {
        showErrorAlert('Please select a tax type to obsolete');
        return;
    }
    const confirmed = await showConfirmDialog({
        title: 'Obsolete Tax Type?',
        text: `Tax type ${form.value.code} akan diubah menjadi Obsolete. Lanjutkan?`,
        confirmButtonText: 'Ya, obsolete',
    });
    if (!confirmed) return;
    await deleteTaxType();
};

const handleRefresh = async () => {
    await loadTaxTypes();
    resetForm();
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
            status: form.value.status,
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
            showSuccessAlert(res.data.message || 'Tax type saved successfully!');
            await loadTaxTypes();
            resetForm();
            showForm.value = false;
        }
    } catch (e) {
        showErrorAlert(e.response?.data?.message || 'Error saving tax type');
    } finally {
        saving.value = false;
    }
};

const deleteTaxType = async () => {
    saving.value = true;
    try {
        const res = await axios.put(`/api/invoices/tax-types/${form.value.code}/status`, {
            status: 'O',
        });
        if (res.data && res.data.success) {
            showSuccessAlert(res.data.message || 'Tax type marked obsolete successfully');
            await loadTaxTypes();
            resetForm();
            showForm.value = false;
        }
    } catch (e) {
        showErrorAlert(e.response?.data?.message || 'Error obsoleting tax type');
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


