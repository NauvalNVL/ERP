<template>
    <AppLayout :header="'Paper Flute'">
    <Head title="Paper Flute Management" />


    <!-- Header Section -->
    <div class="min-h-screen bg-gray-50 py-6 px-4 sm:px-6 lg:px-8">
        <div class="max-w-6xl mx-auto">
            <div class="bg-gradient-to-r from-green-600 to-green-700 text-white shadow-sm rounded-xl border border-green-700 mb-4">
                <div class="px-4 py-3 sm:px-6 flex items-center gap-3">
                    <div class="h-9 w-9 rounded-full bg-green-500 flex items-center justify-center">
                        <i class="fas fa-layer-group text-white text-lg"></i>
                    </div>
                    <div>
                        <h2 class="text-lg sm:text-xl font-semibold leading-tight">Define Paper Flute</h2>
                        <p class="text-xs sm:text-sm text-green-100">Define flute thickness for corrugated cardboard. Use flute codes to manage corrugator flute setup.</p>
                    </div>
                </div>
            </div>

            <div class="bg-white shadow-sm rounded-xl border border-gray-200 p-4 sm:p-6 mb-6">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Left Column -->
                    <div class="lg:col-span-2">
                        <div class="bg-white p-4 sm:p-6 rounded-lg shadow-sm border-t-4 border-emerald-500">
                            <div class="flex items-center mb-6 pb-2 border-b border-gray-200">
                                <div class="p-2 bg-emerald-500 rounded-lg mr-3">
                                    <i class="fas fa-edit text-white"></i>
                                </div>
                                <h3 class="text-xl font-semibold text-gray-800">Paper Flute Management</h3>
                            </div>

                            <!-- Search Section -->
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-6">
                                <div class="col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Flute#:</label>
                                    <div class="relative flex">
                                        <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500">
                                            <i class="fas fa-layer-group"></i>
                                        </span>
                                        <input
                                            type="text"
                                            v-model="searchQuery"
                                            class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none border border-gray-300 focus:ring-emerald-500 focus:border-emerald-500 transition-colors"
                                            placeholder="Search or type flute code"
                                        >
                                        <button
                                            type="button"
                                            @click="showModal = true"
                                            class="inline-flex items-center px-3 py-2 border border-l-0 border-emerald-500 bg-emerald-500 hover:bg-emerald-600 text-white rounded-r-md transition-colors transform active:translate-y-px"
                                        >
                                            <i class="fas fa-table"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="col-span-1">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Action:</label>
                                    <button
                                        type="button"
                                        @click="createNewFlute"
                                        class="w-full flex items-center justify-center px-4 py-2 bg-gradient-to-r from-emerald-500 to-green-600 hover:from-emerald-600 hover:to-green-700 text-white rounded transition-colors transform active:translate-y-px"
                                    >
                                        <i class="fas fa-plus-circle mr-2"></i> Add New
                                    </button>
                                </div>
                            </div>

                            <!-- Data Status Information -->
                            <div v-if="loading" class="mt-4 bg-yellow-100 p-3 rounded">
                                <div class="flex items-center">
                                    <div class="mr-3">
                                        <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-yellow-700"></div>
                                    </div>
                                    <p class="text-sm font-medium text-yellow-800">Loading paper flute data...</p>
                                </div>
                            </div>
                            <div v-else-if="flutes.length === 0" class="mt-4 bg-yellow-100 p-3 rounded">
                                <p class="text-sm font-medium text-yellow-800">No paper flute data available.</p>
                                <p class="text-xs text-yellow-700 mt-1">Make sure the database is properly configured and seeders have been run.</p>
                            </div>
                            <div v-else class="mt-4 bg-green-100 p-3 rounded">
                                <p class="text-sm font-medium text-green-800">Data available: {{ flutes.length }} paper flutes found.</p>
                                <p v-if="selectedRow" class="text-xs text-green-700 mt-1">
                                    Selected: <span class="font-semibold">{{ selectedRow.Flute }}</span> - {{ selectedRow.Descr }} ({{ selectedRow.Height }} mm)
                                </p>
                            </div>
                        </div>
                    </div>
                    <!-- Right Column - Quick Info -->
                    <div class="lg:col-span-1">
                        <!-- Flute Info Card -->
                        <div class="bg-white p-4 sm:p-6 rounded-lg shadow-sm border-t-4 border-teal-500 mb-6">
                            <div class="flex items-center mb-4 pb-2 border-b border-gray-200">
                                <div class="p-2 bg-teal-500 rounded-lg mr-3">
                                    <i class="fas fa-info-circle text-white"></i>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-800">Paper Flute Info</h3>
                            </div>

                            <div class="space-y-4">
                                <div class="p-4 bg-teal-50 rounded-lg">
                                    <h4 class="text-sm font-semibold text-teal-800 uppercase tracking-wider mb-2">Instructions</h4>
                                    <ul class="list-disc pl-5 text-sm text-gray-600 space-y-1">
                                        <li>Flute code must be unique and cannot be changed</li>
                                        <li>Use the <span class="font-medium">search</span> button to select a flute</li>
                                        <li>Flute height is measured in millimeters</li>
                                        <li>Any changes must be saved</li>
                                    </ul>
                                </div>

                                <div class="p-4 bg-blue-50 rounded-lg">
                                    <h4 class="text-sm font-semibold text-blue-800 uppercase tracking-wider mb-2">Common Flutes</h4>
                                    <div class="grid grid-cols-2 gap-2 text-sm">
                                        <div class="flex items-center">
                                            <span class="w-6 h-6 flex items-center justify-center bg-emerald-600 text-white rounded-full font-bold mr-2">A</span>
                                            <span>A Flute</span>
                                        </div>
                                        <div class="flex items-center">
                                            <span class="w-6 h-6 flex items-center justify-center bg-emerald-600 text-white rounded-full font-bold mr-2">B</span>
                                            <span>B Flute</span>
                                        </div>
                                        <div class="flex items-center">
                                            <span class="w-6 h-6 flex items-center justify-center bg-emerald-600 text-white rounded-full font-bold mr-2">C</span>
                                            <span>C Flute</span>
                                        </div>
                                        <div class="flex items-center">
                                            <span class="w-6 h-6 flex items-center justify-center bg-emerald-600 text-white rounded-full font-bold mr-2">E</span>
                                            <span>E Flute</span>
                                        </div>
                                        <div class="flex items-center">
                                            <span class="w-6 h-6 flex items-center justify-center bg-emerald-600 text-white rounded-full font-bold mr-2">F</span>
                                            <span>F Flute</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Table -->
    <PaperFluteModal
        :show="showModal"
        :flutes="flutes"
        @close="showModal = false"
        @select="onFluteSelected"
    />

    <!-- Edit Modal -->
    <div v-if="showEditModal" class="fixed inset-0 z-50 bg-black bg-opacity-50 flex items-center justify-center p-4 overflow-y-auto">
        <div class="bg-white rounded-xl shadow-lg w-full max-w-2xl mx-auto my-8">
            <div class="flex items-center justify-between px-4 py-3 border-b border-emerald-100 bg-gradient-to-r from-green-600 to-green-700 text-white rounded-t-xl">
                <div class="flex items-center">
                    <div class="p-2 bg-white/20 rounded-lg mr-3">
                        <i class="fas fa-layer-group"></i>
                    </div>
                    <h3 class="text-sm md:text-base font-semibold">{{ isCreating ? 'Create Paper Flute' : 'Edit Paper Flute' }}</h3>
                </div>
                <button type="button" @click="closeEditModal" class="text-white hover:text-emerald-100">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>
            <div class="p-6">
                <form @submit.prevent="saveFluteChanges" class="space-y-4">
                    <div class="grid grid-cols-1 gap-4">
                        <!-- Paper Flute -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Paper Flute:</label>
                            <input v-model="editForm.Flute" type="text" class="block w-full rounded-md border-gray-300 shadow-sm focus:ring-emerald-500 focus:border-emerald-500 text-sm" :class="{ 'bg-gray-100': !isCreating }" :readonly="!isCreating" required maxlength="25">
                        </div>
                        
                        <!-- Description -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Description:</label>
                            <input v-model="editForm.Descr" type="text" class="block w-full rounded-md border-gray-300 shadow-sm focus:ring-emerald-500 focus:border-emerald-500 text-sm" required maxlength="100">
                        </div>
                        
                        <!-- Take-Up Ratio Section -->
                        <div class="border border-gray-300 rounded-md p-3 md:p-4 bg-gray-50">
                            <h4 class="text-sm font-semibold text-gray-800 mb-2">Take-Up Ratio:</h4>
                            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-2">
                                <div>
                                    <label class="block text-xs font-medium text-gray-600 mb-1">L1: DB</label>
                                    <input v-model.number="editForm.DB" type="number" step="0.01" min="0" class="block w-full rounded-md border-gray-300 shadow-sm focus:ring-emerald-500 focus:border-emerald-500 text-sm px-2 py-1.5">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-600 mb-1">L2: B</label>
                                    <input v-model.number="editForm.B" type="number" step="0.01" min="0" class="block w-full rounded-md border-gray-300 shadow-sm focus:ring-emerald-500 focus:border-emerald-500 text-sm px-2 py-1.5">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-600 mb-1">L3: 1L</label>
                                    <input v-model.number="editForm._1L" type="number" step="0.01" min="0" class="block w-full rounded-md border-gray-300 shadow-sm focus:ring-emerald-500 focus:border-emerald-500 text-sm px-2 py-1.5">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-600 mb-1">L4: A/C/E</label>
                                    <input v-model.number="editForm.A_C_E" type="number" step="0.01" min="0" class="block w-full rounded-md border-gray-300 shadow-sm focus:ring-emerald-500 focus:border-emerald-500 text-sm px-2 py-1.5">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-600 mb-1">L5: 2L</label>
                                    <input v-model.number="editForm._2L" type="number" step="0.01" min="0" class="block w-full rounded-md border-gray-300 shadow-sm focus:ring-emerald-500 focus:border-emerald-500 text-sm px-2 py-1.5">
                                </div>
                            </div>
                        </div>
                        
                        <!-- Flute Height & Starch Consumption -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Flute Height:</label>
                                <div class="flex items-center space-x-2">
                                    <input v-model.number="editForm.Height" type="number" step="0.01" min="0" class="block flex-1 rounded-md border-gray-300 shadow-sm focus:ring-emerald-500 focus:border-emerald-500 text-sm">
                                    <span class="text-xs text-gray-600 whitespace-nowrap">mm</span>
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Starch Consumption:</label>
                                <div class="flex items-center space-x-2">
                                    <input v-model.number="editForm.Starch" type="number" step="0.01" min="0" class="block flex-1 rounded-md border-gray-300 shadow-sm focus:ring-emerald-500 focus:border-emerald-500 text-sm">
                                    <span class="text-xs text-gray-600 whitespace-nowrap">Factor</span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Note -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Note:</label>
                            <div class="border border-gray-300 rounded-md p-3 bg-blue-50 text-sm">
                                <p class="text-gray-700">Starch Consumption [G/M2] = (Area[M2] x Factor)</p>
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-col sm:flex-row sm:justify-between gap-3 mt-6 pt-4 border-t border-gray-200">
                        <button type="button" v-if="!isCreating" @click="obsoleteFlute(editForm.No)" class="w-full sm:w-auto px-4 py-2 bg-orange-500 text-white rounded-lg hover:bg-orange-600 text-sm font-medium">
                            <i class="fas fa-archive mr-2"></i>Mark as Obsolete
                        </button>
                        <div v-else></div>
                        <div class="flex flex-col sm:flex-row gap-2 sm:gap-3">
                            <button type="button" @click="closeEditModal" class="w-full sm:w-auto px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 text-sm font-medium">Cancel</button>
                            <button type="submit" class="w-full sm:w-auto px-4 py-2 bg-gradient-to-r from-emerald-500 to-green-600 hover:from-emerald-600 hover:to-green-700 text-white rounded-lg text-sm font-medium">Save</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Loading Overlay -->
    <div v-if="saving" class="fixed inset-0 z-50 bg-black bg-opacity-50 flex justify-center items-center">
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
import { ref, onMounted, watch, computed } from 'vue';
import { Head, Link, usePage, router } from '@inertiajs/vue3';
import PaperFluteModal from '@/Components/paper-flute-modal.vue';
import Swal from 'sweetalert2';

// Get the header from props
const props = defineProps({
    header: {
        type: String,
        default: 'Paper Flute Management'
    }
});

const flutes = ref([]);
const loading = ref(false);
const saving = ref(false);
const showModal = ref(false);
const showEditModal = ref(false);
const selectedRow = ref(null);
const searchQuery = ref('');
const editForm = ref({
    Flute: '',
    Descr: '',
    DB: 1.00,
    B: 1.00,
    _1L: 1.00,
    A_C_E: 1.00,
    _2L: 1.00,
    Height: 0.00,
    Starch: 0.00
});
const isCreating = ref(false);
const notification = ref({ show: false, message: '', type: 'success' });

const fetchFlutes = async () => {
    loading.value = true;
    try {
        const res = await fetch('/api/paper-flutes', { 
            headers: { 
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            } 
        });
        
        if (!res.ok) {
            throw new Error('Network response was not ok');
        }
        
        const data = await res.json();
        
        if (Array.isArray(data)) {
            flutes.value = data.filter(isActiveFlute);
        } else {
            flutes.value = [];
            console.error('Unexpected data format:', data);
            showNotification('Error loading paper flutes: Invalid data format', 'error');
        }

        if (flutes.value.length === 0) {
            selectedRow.value = null;
            searchQuery.value = '';
        } else if (selectedRow.value) {
            const stillExists = flutes.value.some(f => String(f.Flute || '').toLowerCase() === String(selectedRow.value?.Flute || '').toLowerCase());
            if (!stillExists) {
                selectedRow.value = null;
                searchQuery.value = '';
            }
        }
    } catch (e) {
        console.error('Error fetching paper flutes:', e);
        flutes.value = [];
        showNotification('Error loading paper flutes: ' + e.message, 'error');
    } finally {
        loading.value = false;
    }
};

onMounted(fetchFlutes);

const isActiveFlute = (flute) => {
    const status = flute?.status ?? flute?.STATUS;
    if (status === undefined || status === null || String(status).trim() === '') return true;

    const s = String(status).trim().toLowerCase();
    if (s === 'act' || s === 'active' || s === 'a' || s === 'y' || s === '1' || s === 'true') return true;
    if (s === 'obs' || s === 'obsolete' || s === 'inactive' || s === 'i' || s === 'n' || s === '0' || s === 'false') return false;

    return true;
};

const normalizeFluteCode = (value) => {
    return (value ?? '').toString().trim().toLowerCase();
};

const tryAutoDetectFluteByCode = (query) => {
    const q = normalizeFluteCode(query);

    if (!q) {
        selectedRow.value = null;
        return;
    }

    const match = flutes.value.find((flute) => {
        return isActiveFlute(flute) && normalizeFluteCode(flute.Flute) === q;
    });

    selectedRow.value = match ?? null;
};

// Watch for changes in search query to filter the data
watch([searchQuery, flutes], ([newQuery]) => {
    tryAutoDetectFluteByCode(newQuery);
});

const onFluteSelected = (flute) => {
    selectedRow.value = flute;
    searchQuery.value = flute.Flute;
    showModal.value = false;
    
    // Automatically open the edit modal for the selected row
    isCreating.value = false;
    editForm.value = { ...flute };
    showEditModal.value = true;
};

const editSelectedRow = () => {
    if (selectedRow.value) {
        isCreating.value = false;
        editForm.value = { ...selectedRow.value };
        showEditModal.value = true;
    } else {
        showNotification('Please select a paper flute first', 'error');
    }
};

const createNewFlute = () => {
    isCreating.value = true;
    editForm.value = {
        Flute: '',
        Descr: '',
        DB: 1.00,
        B: 1.00,
        _1L: 1.00,
        A_C_E: 1.00,
        _2L: 1.00,
        Height: 0.00,
        Starch: 0.00
    };
    showEditModal.value = true;
};

const closeEditModal = () => {
    showEditModal.value = false;
    editForm.value = {
        Flute: '',
        Descr: '',
        DB: 1.00,
        B: 1.00,
        _1L: 1.00,
        A_C_E: 1.00,
        _2L: 1.00,
        Height: 0.00,
        Starch: 0.00
    };
    isCreating.value = false;
};

const saveFluteChanges = async () => {
    saving.value = true;
    try {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
        
        // Different API call for create vs update
        let url = isCreating.value ? '/api/paper-flutes' : `/api/paper-flutes/${editForm.value.Flute}`;
        let method = isCreating.value ? 'POST' : 'PUT';
        
        const response = await fetch(url, {
            method: method,
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify(editForm.value)
        });
        
        const result = await response.json();
        
        if (response.ok) {
            // Update the local data with the changes or add new item
            if (isCreating.value) {
                showNotification('Paper flute created successfully', 'success');
            } else {
                if (selectedRow.value) {
                    Object.assign(selectedRow.value, editForm.value);
                }
                showNotification('Paper flute updated successfully', 'success');
            }
            
            // Refresh the full data list to ensure we're in sync with the database
            await fetchFlutes();
            closeEditModal();
        } else {
            showNotification('Error: ' + (result.message || 'Unknown error'), 'error');
        }
    } catch (e) {
        console.error('Error saving paper flute changes:', e);
        showNotification('Error saving paper flute. Please try again.', 'error');
    } finally {
        saving.value = false;
    }
};

const obsoleteFlute = async (id) => {
    const confirmRes = await Swal.fire({
        title: 'Obsolete Paper Flute?',
        text: `Are you sure you want to obsolete paper flute "${editForm.value.Flute}"? This will hide it from paper flute selection.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'OK',
        cancelButtonText: 'Cancel',
        reverseButtons: true,
        allowOutsideClick: false,
    });

    if (!confirmRes.isConfirmed) {
        return;
    }
    
    saving.value = true;
    try {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
        
        const response = await fetch(`/api/paper-flutes/${id}/status`, {
            method: 'PUT',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        });
        
        const result = await response.json();
        
        if (response.ok && result.success) {
            // Remove from the local array (since it's now obsolete)
            const fluteCode = editForm.value.Flute;
            flutes.value = flutes.value.filter(f => f.No !== id);
            
            if (selectedRow.value && selectedRow.value.No === id) {
                selectedRow.value = null;
                searchQuery.value = '';
            }
            
            closeEditModal();
            showNotification(`Paper flute "${fluteCode}" has been obsoleted successfully.`, 'success');
        } else {
            showNotification('Error obsoleting paper flute: ' + (result.message || 'Unknown error'), 'error');
        }
    } catch (e) {
        console.error('Error obsoleting paper flute:', e);
        showNotification('Error obsoleting paper flute. Please try again.', 'error');
    } finally {
        saving.value = false;
    }
};

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
</script>
