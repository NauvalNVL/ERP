<template>
    <AppLayout :header="'Define Analysis Code'">
    <Head title="Define Analysis Code" />

    <!-- Hidden form with CSRF token -->
    <form ref="csrfForm" class="hidden">
        @csrf
    </form>

    <!-- Header Section -->
    <div class="bg-gradient-to-r from-green-600 to-green-700 p-6 sm:p-7 md:p-8 rounded-t-2xl shadow-lg">
        <h2 class="text-2xl md:text-3xl font-bold text-white mb-2 flex items-center">
            <span class="inline-flex items-center justify-center w-10 h-10 md:w-11 md:h-11 rounded-xl bg-white/15 mr-3">
                <i class="fas fa-code-branch text-lg md:text-xl"></i>
            </span>
            Define Analysis Code
        </h2>
        <p class="text-emerald-100 text-sm md:text-base">Define analysis codes for transaction categorization and reporting</p>
    </div>

    <div class="bg-gradient-to-br from-slate-50 via-white to-emerald-50 rounded-b-2xl shadow-lg p-6 mb-6">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column -->
            <div class="lg:col-span-2">
                <div class="bg-white p-6 rounded-lg shadow-md border-t-4 border-emerald-500">
                    <div class="flex items-center mb-6 pb-2 border-b border-gray-200">
                        <div class="p-2 bg-emerald-500 rounded-lg mr-3">
                            <i class="fas fa-edit text-white"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-800">Define Analysis Code</h3>
                    </div>

                    <!-- Search Section -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-6">
                        <div class="col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Analysis Code:</label>
                            <div class="relative flex">
                                <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500">
                                    <i class="fas fa-code-branch"></i>
                                </span>
                                <input type="text" v-model="searchQuery" class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none border border-gray-300 focus:ring-emerald-500 focus:border-emerald-500 transition-colors">
                                <button type="button" @click="showModal = true" class="inline-flex items-center px-3 py-2 border border-l-0 border-emerald-500 bg-emerald-500 hover:bg-emerald-600 text-white rounded-r-md transition-colors transform active:translate-y-px">
                                    <i class="fas fa-table"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-span-1">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Action:</label>
                            <button type="button" @click="createNewCode" class="w-full flex items-center justify-center px-4 py-2 bg-gradient-to-r from-emerald-500 to-green-600 hover:from-emerald-600 hover:to-green-700 text-white rounded transition-colors transform active:translate-y-px">
                                <i class="fas fa-plus-circle mr-2"></i> Add New
                            </button>
                        </div>
                    </div>

                    <!-- Data Status Information -->
                    <div v-if="loading" class="mt-4 bg-yellow-100 p-3 rounded">
                        <div class="flex items-center">
                            <div class="mr-3 animate-spin rounded-full h-6 w-6 border-b-2 border-yellow-700"></div>
                            <p class="text-sm font-medium text-yellow-800">Loading analysis code data...</p>
                        </div>
                    </div>
                    <div v-else-if="analysisCodes.length === 0" class="mt-4 bg-yellow-100 p-3 rounded">
                        <p class="text-sm font-medium text-yellow-800">No analysis code data available.</p>
                        <p class="text-xs text-yellow-700 mt-1">Data will be automatically loaded when available.</p>
                    </div>
                    <div v-else class="mt-4 bg-green-100 p-3 rounded">
                        <p class="text-sm font-medium text-green-800">Data available: {{ analysisCodes.length }} analysis codes found.</p>
                        <p v-if="selectedRow" class="text-xs text-green-700 mt-1">
                            Selected: <span class="font-semibold">{{ selectedRow.analysis_code }}</span> - {{ selectedRow.analysis_name }} ({{ selectedRow.analysis_group }})
                        </p>
                    </div>
                </div>
            </div>

            <!-- Right Column - Quick Info -->
            <div class="lg:col-span-1">
                <!-- Analysis Code Info Card -->
                <div class="bg-white p-6 rounded-lg shadow-md border-t-4 border-emerald-500 mb-6">
                    <div class="flex items-center mb-4 pb-2 border-b border-gray-200">
                        <div class="p-2 bg-emerald-500 rounded-lg mr-3">
                            <i class="fas fa-info-circle text-white"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-800">Analysis Code Information</h3>
                    </div>

                    <div class="space-y-4">
                        <div class="p-4 bg-emerald-50 rounded-lg">
                            <h4 class="text-sm font-semibold text-emerald-800 uppercase tracking-wider mb-2">Instructions</h4>
                            <ul class="list-disc pl-5 text-sm text-gray-600 space-y-1">
                                <li>Analysis code must be unique and cannot be changed</li>
                                <li>Use the <span class="font-medium">search</span> button to select a code</li>
                                <li>Analysis Group determines which module to track</li>
                                <li>Analysis Group2 defines the specific action type</li>
                            </ul>
                        </div>

                        <div class="p-4 bg-emerald-50 rounded-lg">
                            <h4 class="text-sm font-semibold text-emerald-800 uppercase tracking-wider mb-2">Analysis Groups</h4>
                            <div class="space-y-2 text-sm">
                                <div class="flex items-center">
                                    <span class="w-8 h-8 flex items-center justify-center bg-emerald-500 text-white rounded-full font-bold mr-2">MC</span>
                                    <span>Master Card</span>
                                </div>
                                <div class="flex items-center">
                                    <span class="w-8 h-8 flex items-center justify-center bg-green-500 text-white rounded-full font-bold mr-2">SO</span>
                                    <span>Sales Order</span>
                                </div>
                            </div>
                        </div>

                        <div class="p-4 bg-yellow-50 rounded-lg">
                            <h4 class="text-sm font-semibold text-yellow-800 uppercase tracking-wider mb-2">Important Note</h4>
                            <p class="text-xs text-gray-600">
                                <i class="fas fa-exclamation-triangle text-yellow-600 mr-1"></i>
                                AM, CM, CL & UN of Analysis Group 2 are only applied to Analysis Group = 'SO'
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Quick Links -->
                <div class="bg-white p-6 rounded-lg shadow-md border-t-4 border-emerald-500">
                    <div class="flex items-center mb-4 pb-2 border-b border-gray-200">
                        <div class="p-2 bg-emerald-500 rounded-lg mr-3">
                            <i class="fas fa-link text-white"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-800">Related Reports</h3>
                    </div>

                    <div class="grid grid-cols-1 gap-3">
                        <Link href="/analysis-code/view-print" class="flex items-center p-3 bg-emerald-50 rounded-lg hover:bg-emerald-100 transition-colors">
                            <div class="p-2 bg-emerald-500 rounded-full mr-3">
                                <i class="fas fa-print text-white text-sm"></i>
                            </div>
                            <div>
                                <p class="font-medium text-emerald-900">View & Print</p>
                                <p class="text-xs text-emerald-700">Print analysis code list</p>
                            </div>
                        </Link>

                        <div class="flex items-center p-3 bg-emerald-50 rounded-lg">
                            <div class="p-2 bg-emerald-500 rounded-full mr-3">
                                <i class="fas fa-chart-bar text-white text-sm"></i>
                            </div>
                            <div>
                                <p class="font-medium text-emerald-900">SO Analysis</p>
                                <p class="text-xs text-emerald-700">By Analysis Code</p>
                            </div>
                        </div>

                        <div class="flex items-center p-3 bg-emerald-50 rounded-lg">
                            <div class="p-2 bg-emerald-500 rounded-full mr-3">
                                <i class="fas fa-list text-white text-sm"></i>
                            </div>
                            <div>
                                <p class="font-medium text-emerald-900">MC Reports</p>
                                <p class="text-xs text-emerald-700">Run Size Analysis</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Analysis Code Modal -->
    <AnalysisCodeModal
      v-if="showModal"
      :show="showModal"
      :analysisCodes="analysisCodes"
      @close="showModal = false"
      @select="onCodeSelected"
      @refresh="refreshCodes"
    />

    <!-- Edit Modal -->
    <div v-if="showEditModal" class="fixed inset-0 z-50 bg-black bg-opacity-50 flex items-center justify-center">
        <div class="bg-white rounded-lg shadow-xl w-11/12 md:w-2/5 max-w-md mx-auto transform transition-transform duration-300">
            <div class="flex items-center justify-between p-4 border-b border-gray-200 bg-gradient-to-r from-green-600 to-green-700 text-white rounded-t-lg">
                <div class="flex items-center">
                    <div class="p-2 bg-white bg-opacity-30 rounded-lg mr-3">
                        <i class="fas fa-code-branch"></i>
                    </div>
                    <h3 class="text-xl font-semibold">{{ isCreating ? 'Create Analysis Code' : 'Edit Analysis Code' }}</h3>
                </div>
                <button type="button" @click="closeEditModal" class="text-white hover:text-gray-200 transform active:translate-y-px">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div class="p-6">
                <form @submit.prevent="saveCodeChanges" class="space-y-4">
                    <div class="grid grid-cols-1 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Analysis Code:</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
                                    <i class="fas fa-hashtag"></i>
                                </span>
                                <input v-model="editForm.analysis_code" type="text" class="pl-10 block w-full rounded-md border-gray-300 shadow-sm" :class="{ 'bg-gray-100': !isCreating }" :readonly="!isCreating" required>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Analysis Name:</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
                                    <i class="fas fa-font"></i>
                                </span>
                                <input v-model="editForm.analysis_name" type="text" class="pl-10 block w-full rounded-md border-gray-300 shadow-sm focus:ring-emerald-500 focus:border-emerald-500 text-sm" required>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Analysis Group:</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
                                    <i class="fas fa-layer-group"></i>
                                </span>
                                <select v-model="editForm.analysis_group" @change="onAnalysisGroupChange" class="pl-10 block w-full rounded-md border-gray-300 shadow-sm focus:ring-emerald-500 focus:border-emerald-500 text-sm" required>
                                    <option value="">Select Analysis Group</option>
                                    <option value="MC">MC-Master Card</option>
                                    <option value="SO">SO-Sales Order</option>
                                </select>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Analysis Group2:</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
                                    <i class="fas fa-tag"></i>
                                </span>
                                <select v-model="editForm.analysis_group2" class="pl-10 block w-full rounded-md border-gray-300 shadow-sm focus:ring-emerald-500 focus:border-emerald-500 text-sm" required :disabled="!editForm.analysis_group">
                                    <option value="">Select Analysis Group2</option>
                                    <optgroup label="Master Card Options" v-if="editForm.analysis_group === 'MC'">
                                        <option value="CS">CS-Corrugator Run Size</option>
                                        <option value="RS">RS-Sheet Board Run Size</option>
                                    </optgroup>
                                    <optgroup label="Sales Order Options" v-if="editForm.analysis_group === 'SO'">
                                        <option value="AM">AM-Amend Sales Order</option>
                                        <option value="CM">CM-Close Sales Order</option>
                                        <option value="CL">CL-Cancel Sales Order</option>
                                        <option value="UN">UN-Unclose Sales Order</option>
                                    </optgroup>
                                </select>
                            </div>
                            <p v-if="editForm.analysis_group === 'SO'" class="mt-1 text-xs text-yellow-600">
                                <i class="fas fa-info-circle mr-1"></i>AM, CM, CL & UN are only for Sales Order
                            </p>
                        </div>
                    </div>
                    <div class="flex justify-between mt-6 pt-4 border-t border-gray-200">
                        <button type="button" v-if="!isCreating" @click="deleteCode(editForm.analysis_code)" class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors text-sm transform active:translate-y-px">
                            <i class="fas fa-trash-alt mr-2"></i>Delete
                        </button>
                        <div v-else class="w-24"></div>
                        <div class="flex space-x-3">
                            <button type="button" @click="closeEditModal" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition-colors text-sm transform active:translate-y-px">
                                <i class="fas fa-times mr-2"></i>Cancel
                            </button>
                            <button type="submit" class="px-4 py-2 bg-gradient-to-r from-emerald-500 to-green-600 text-white rounded-lg hover:from-emerald-600 hover:to-green-700 transition-colors text-sm transform active:translate-y-px">
                                <i class="fas fa-save mr-2"></i>Save
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Loading Overlay -->
    <div v-if="saving" class="fixed inset-0 z-[80] bg-black bg-opacity-50 flex justify-center items-center">
        <div class="w-12 h-12 border-4 border-solid border-emerald-500 border-t-transparent rounded-full animate-spin"></div>
    </div>

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
    </AppLayout>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue';
import { Head, Link, usePage } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import AnalysisCodeModal from '@/Components/AnalysisCodeModal.vue';

// Get the header from props
const props = defineProps({
    header: {
        type: String,
        default: 'Analysis Code Management'
    },
    analysisCodes: {
        type: Array,
        default: () => []
    }
});

// Reference to the CSRF form
const csrfForm = ref(null);

// Function to get fresh CSRF token from the form
const getCsrfToken = () => {
    // Try to get token from meta tag first (prefer this method)
    let token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

    // Option 2: Try to get from Inertia usePage if available
    if (!token && usePage().props.value?.csrf) {
        token = usePage().props.value.csrf;
    }

    // Option 3: Try to get from hidden form with @csrf directive
    if (!token && csrfForm.value) {
        const tokenInput = csrfForm.value.querySelector('input[name="_token"]');
        if (tokenInput) {
            token = tokenInput.value;
        }
    }

    if (!token) {
        console.error('Failed to acquire CSRF token from any source');
    }

    return token || '';
};

const analysisCodes = ref((props.analysisCodes || []).filter(code => !code.status || code.status === 'Act'));
const loading = ref(false);
const saving = ref(false);
const showModal = ref(false);
const showEditModal = ref(false);
const selectedRow = ref(null);
const searchQuery = ref('');
const editForm = ref({
    analysis_code: '',
    analysis_name: '',
    analysis_group: '',
    analysis_group2: ''
});
const isCreating = ref(false);
const notification = ref({ show: false, message: '', type: 'success' });

// Fetch analysis codes from API
const fetchCodes = async () => {
    loading.value = true;
    try {
        const response = await fetch('/api/analysis-codes', {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            credentials: 'same-origin'
        });

        if (!response.ok) {
            throw new Error('Network response was not ok');
        }

        const data = await response.json();

        if (Array.isArray(data)) {
            analysisCodes.value = data.filter(code => !code.status || code.status === 'Act');
        } else if (data.data && Array.isArray(data.data)) {
            analysisCodes.value = data.data.filter(code => !code.status || code.status === 'Act');
        } else {
            analysisCodes.value = [];
            console.error('Unexpected data format:', data);
        }
    } catch (error) {
        console.error('Error fetching analysis codes:', error);
        showNotification('Failed to load analysis codes data', 'error');
        analysisCodes.value = [];
    } finally {
        loading.value = false;
    }
};

// Manually refresh codes - can be called after operations
const refreshCodes = async () => {
    await fetchCodes();
};

onMounted(() => {
    if (analysisCodes.value.length === 0) {
        fetchCodes();
    }
});

// Watch for changes in search query to filter the data
watch(searchQuery, (newQuery) => {
    if (newQuery && analysisCodes.value.length > 0) {
        const foundCode = analysisCodes.value.find(code =>
            code.analysis_code.toLowerCase().includes(newQuery.toLowerCase()) ||
            code.analysis_name.toLowerCase().includes(newQuery.toLowerCase())
        );

        if (foundCode) {
            selectedRow.value = foundCode;
        }
    }
});

// Watch for modal opening to refresh data
watch(showModal, (isOpen) => {
    if (isOpen) {
        fetchCodes();
    }
});

const onCodeSelected = (code) => {
    selectedRow.value = code;
    searchQuery.value = code.analysis_code;
    showModal.value = false;

    // Automatically open the edit modal for the selected row
    isCreating.value = false;
    editForm.value = { ...code };
    showEditModal.value = true;
};

const createNewCode = () => {
    isCreating.value = true;
    editForm.value = {
        analysis_code: '',
        analysis_name: '',
        analysis_group: '',
        analysis_group2: ''
    };
    showEditModal.value = true;
};

const closeEditModal = () => {
    showEditModal.value = false;
    editForm.value = {
        analysis_code: '',
        analysis_name: '',
        analysis_group: '',
        analysis_group2: ''
    };
    isCreating.value = false;
};

const onAnalysisGroupChange = () => {
    // Reset analysis_group2 when analysis_group changes
    editForm.value.analysis_group2 = '';
};

const saveCodeChanges = async () => {
    // Validate form
    if (!editForm.value.analysis_code) {
        showNotification('Analysis code is required', 'error');
        return;
    }

    if (!editForm.value.analysis_name) {
        showNotification('Analysis name is required', 'error');
        return;
    }

    if (!editForm.value.analysis_group) {
        showNotification('Analysis group is required', 'error');
        return;
    }

    if (!editForm.value.analysis_group2) {
        showNotification('Analysis group2 is required', 'error');
        return;
    }

    saving.value = true;

    try {
        const csrfToken = getCsrfToken();

        // Different API call for create vs update
        let url = isCreating.value ? '/api/analysis-codes' : `/api/analysis-codes/${editForm.value.analysis_code}`;
        let method = isCreating.value ? 'POST' : 'PUT';

        const response = await fetch(url, {
            method: method,
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify(editForm.value),
            credentials: 'same-origin'
        });

        if (!response.ok) {
            const errorData = await response.json();
            throw new Error(errorData.message || 'Error saving analysis code');
        }

        const result = await response.json();

        // Update the local data
        await fetchCodes();

        // Show success notification
        if (isCreating.value) {
            showNotification('Analysis code created successfully', 'success');
            const newCode = analysisCodes.value.find(c => c.analysis_code === editForm.value.analysis_code);
            if (newCode) {
                selectedRow.value = newCode;
                searchQuery.value = newCode.analysis_code;
            }
        } else {
            showNotification('Analysis code updated successfully', 'success');
        }

        // Close the edit modal
        closeEditModal();
    } catch (error) {
        console.error('Error saving analysis code:', error);
        showNotification('Error: ' + error.message, 'error');
    } finally {
        saving.value = false;
    }
};

const deleteCode = async (codeId) => {
    if (!confirm(`Are you sure you want to delete analysis code "${codeId}"?`)) return;

    saving.value = true;

    try {
        const csrfToken = getCsrfToken();

        const response = await fetch(`/api/analysis-codes/${codeId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            credentials: 'same-origin'
        });

        if (!response.ok) {
            const errorData = await response.json();
            throw new Error(errorData.message || 'Error deleting analysis code');
        }

        // Update the local data
        await fetchCodes();

        showNotification('Analysis code deleted successfully', 'success');

        // Clear selection
        selectedRow.value = null;
        searchQuery.value = '';

        // Close the edit modal
        closeEditModal();
    } catch (error) {
        console.error('Error deleting analysis code:', error);
        showNotification('Error: ' + error.message, 'error');
    } finally {
        saving.value = false;
    }
};

const showNotification = (message, type = 'success') => {
    notification.value = { show: true, message, type };
    setTimeout(() => {
        notification.value = { show: false, message: '', type: 'success' };
    }, 3000);
};
</script>
