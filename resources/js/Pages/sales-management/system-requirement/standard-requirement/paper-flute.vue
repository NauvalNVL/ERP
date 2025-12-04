<template>
    <AppLayout :header="'Paper Flute'">
    <Head title="Paper Flute Management" />


    <!-- Header Section -->
    <div class="min-h-screen bg-gray-50 py-6 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <div class="bg-emerald-600 text-white shadow-sm rounded-xl border border-emerald-700 mb-4">
                <div class="px-4 py-3 sm:px-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div class="flex items-center gap-3">
                        <div class="h-9 w-9 rounded-full bg-emerald-500 flex items-center justify-center">
                            <i class="fas fa-layer-group text-white text-sm"></i>
                        </div>
                        <div>
                            <h2 class="text-lg sm:text-xl font-semibold leading-tight">
                                Define Paper Flute
                            </h2>
                            <p class="text-xs sm:text-sm text-emerald-100">
                                Define flute thickness for corrugated cardboard.
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 text-xs text-emerald-100">
                        <i class="fas fa-info-circle text-sm"></i>
                        <span>Use flute codes to manage corrugator flute setup.</span>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8">
                <!-- Left Column -->
                <div class="lg:col-span-2 space-y-4">
                    <div class="bg-white shadow-sm rounded-xl border border-gray-200">
                        <div class="px-4 py-3 sm:px-6 border-b border-gray-100 flex items-center">
                            <div class="p-2 bg-emerald-500 rounded-lg mr-3 text-white">
                                <i class="fas fa-edit"></i>
                            </div>
                            <div>
                                <h3 class="text-sm sm:text-base font-semibold text-slate-800">Paper Flute Management</h3>
                                <p class="text-xs text-slate-500">Search, create, and maintain your paper flutes.</p>
                            </div>
                        </div>
                        <div class="px-4 py-4 sm:px-6">
                            <!-- Search Section -->
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                                <div class="col-span-2">
                                    <label class="block text-sm font-semibold text-slate-700 mb-1">Flute#</label>
                                    <div class="relative flex">
                                        <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-200 bg-slate-50 text-slate-500">
                                            <i class="fas fa-layer-group"></i>
                                        </span>
                                        <input
                                            type="text"
                                            v-model="searchQuery"
                                            class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none border border-gray-200 focus:ring-emerald-500 focus:border-emerald-500 text-slate-800 placeholder-slate-400 text-sm transition-colors"
                                            placeholder="Search or type flute code"
                                        >
                                        <button
                                            type="button"
                                            @click="showModal = true"
                                            class="inline-flex items-center px-3 py-2 border border-l-0 border-emerald-500 bg-emerald-600 hover:bg-emerald-700 text-white rounded-r-md text-sm"
                                        >
                                            <i class="fas fa-table"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="col-span-1">
                                    <label class="block text-sm font-semibold text-slate-700 mb-1">Action</label>
                                    <button
                                        type="button"
                                        @click="createNewFlute"
                                        class="w-full flex items-center justify-center px-4 py-2 rounded-md bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold shadow-sm"
                                    >
                                        <i class="fas fa-plus-circle mr-2"></i>
                                        Add New
                                    </button>
                                </div>
                            </div>
                            <!-- Data Status Information -->
                            <div v-if="loading" class="mt-3 bg-amber-50 border border-amber-200 p-3 rounded-lg flex items-center space-x-3 text-sm">
                                <div class="flex items-center">
                                    <div class="mr-3">
                                        <div class="animate-spin rounded-full h-6 w-6 border-2 border-amber-300 border-t-amber-600"></div>
                                    </div>
                                    <p class="font-medium text-amber-800">Loading paper flute data...</p>
                                </div>
                            </div>
                            <div v-else-if="flutes.length === 0" class="mt-3 bg-amber-50 border border-amber-200 p-3 rounded-lg">
                                <p class="text-sm font-semibold text-amber-800">No paper flute data available.</p>
                                <p class="text-xs text-amber-700 mt-1">Make sure the database is properly configured and seeders have been run.</p>
                                <div class="mt-2 flex items-center space-x-3">
                                    <button @click="fetchFlutes" class="bg-emerald-500 hover:bg-emerald-600 text-white text-xs px-3 py-1 rounded-lg">Reload Data</button>
                                </div>
                            </div>
                            <div v-else class="mt-3 bg-emerald-50 border border-emerald-200 p-3 rounded-lg">
                                <p class="text-sm font-semibold text-emerald-800">Data available: {{ flutes.length }} paper flutes found.</p>
                                <p v-if="selectedRow" class="text-xs text-emerald-700 mt-1">
                                    Selected: <span class="font-semibold">{{ selectedRow.Flute }}</span> - {{ selectedRow.Descr }} ({{ selectedRow.Height }} mm)
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Right Column - Quick Info -->
                <div class="lg:col-span-1 space-y-4">
                    <!-- Flute Info Card -->
                    <div class="bg-white shadow-sm rounded-xl border border-emerald-100 mb-2">
                        <div class="px-4 py-3 sm:px-5 border-b border-emerald-100 flex items-center">
                            <div class="p-2 bg-emerald-500 rounded-lg mr-3">
                                <i class="fas fa-info-circle text-white"></i>
                            </div>
                            <h3 class="text-sm sm:text-base font-semibold text-emerald-900">Flute Information</h3>
                        </div>

                        <div class="px-4 py-4 sm:px-5">
                            <div class="space-y-4">
                                <div class="p-4 bg-emerald-50 rounded-lg border border-emerald-100">
                                    <h4 class="text-xs font-semibold text-emerald-700 uppercase tracking-wider mb-2">Instructions</h4>
                                    <ul class="list-disc pl-5 text-xs sm:text-sm text-slate-600 space-y-1">
                                        <li>Flute code must be unique and cannot be changed</li>
                                        <li>Use the <span class="font-medium">search</span> button to select a flute</li>
                                        <li>Flute height is measured in millimeters</li>
                                        <li>Any changes must be saved</li>
                                    </ul>
                                </div>

                                <div class="p-4 bg-sky-50 rounded-lg border border-sky-100">
                                    <h4 class="text-xs font-semibold text-sky-800 uppercase tracking-wider mb-2">Common Flutes</h4>
                                    <div class="grid grid-cols-2 gap-2 text-xs sm:text-sm">
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

                    <!-- Quick Links -->
                    <div class="bg-white shadow-sm rounded-xl border border-violet-100">
                        <div class="px-4 py-3 sm:px-5 border-b border-violet-100 flex items-center">
                            <div class="p-2 bg-violet-500 rounded-lg mr-3">
                                <i class="fas fa-link text-white"></i>
                            </div>
                            <h3 class="text-sm sm:text-base font-semibold text-slate-800">Quick Links</h3>
                        </div>

                        <div class="px-4 py-4 sm:px-5">
                            <div class="grid grid-cols-1 gap-3">
                                <Link href="/paper-size" class="flex items-center p-3 bg-emerald-50 rounded-lg hover:bg-emerald-100 transition-colors border border-emerald-100">
                                    <div class="p-2 bg-emerald-500 rounded-full mr-3">
                                        <i class="fas fa-box text-white text-sm"></i>
                                    </div>
                                    <div>
                                        <p class="font-medium text-emerald-900 text-sm">Paper Size</p>
                                        <p class="text-xs text-emerald-700">Manage paper sizes</p>
                                    </div>
                                </Link>

                                <a href="/paper-quality" class="flex items-center p-3 bg-sky-50 rounded-lg hover:bg-sky-100 transition-colors border border-sky-100">
                                    <div class="p-2 bg-sky-500 rounded-full mr-3">
                                        <i class="fas fa-th-list text-white text-sm"></i>
                                    </div>
                                    <div>
                                        <p class="font-medium text-sky-900 text-sm">Paper Quality</p>
                                        <p class="text-xs text-sky-700">Manage paper quality</p>
                                    </div>
                                </a>

                                <Link href="/paper-flute/view-print" class="flex items-center p-3 bg-emerald-50 rounded-lg hover:bg-emerald-100 transition-colors border border-emerald-100">
                                    <div class="p-2 bg-emerald-500 rounded-full mr-3">
                                        <i class="fas fa-print text-white text-sm"></i>
                                    </div>
                                    <div>
                                        <p class="font-medium text-emerald-900 text-sm">Print List</p>
                                        <p class="text-xs text-emerald-700">Print flute list</p>
                                    </div>
                                </Link>
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
    <div v-if="showEditModal" class="fixed inset-0 z-50 bg-black bg-opacity-30 flex items-center justify-center p-4 overflow-y-auto">
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 w-full max-w-2xl mx-auto my-8">
            <div class="flex items-center justify-between px-4 py-3 border-b border-gray-200 bg-emerald-600 text-white rounded-t-xl">
                <div class="flex items-center">
                    <div class="p-2 bg-white bg-opacity-20 rounded-lg mr-3">
                        <i class="fas fa-layer-group"></i>
                    </div>
                    <h3 class="text-sm md:text-base font-semibold">{{ isCreating ? 'Create Paper Flute' : 'Edit Paper Flute' }}</h3>
                </div>
                <button type="button" @click="closeEditModal" class="text-white hover:text-gray-200">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>
            <div class="p-6">
                <form @submit.prevent="saveFluteChanges" class="space-y-4">
                    <div class="grid grid-cols-1 gap-4">
                        <!-- Paper Flute -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Paper Flute:</label>
                            <input v-model="editForm.Flute" type="text" class="block w-full rounded-md border-gray-300 shadow-sm text-sm" :class="{ 'bg-gray-100': !isCreating }" :readonly="!isCreating" required maxlength="25">
                        </div>
                        
                        <!-- Description -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Description:</label>
                            <input v-model="editForm.Descr" type="text" class="block w-full rounded-md border-gray-300 shadow-sm text-sm" required maxlength="100">
                        </div>
                        
                        <!-- Take-Up Ratio Section -->
                        <div class="border border-gray-300 rounded-md p-3 md:p-4 bg-gray-50">
                            <h4 class="text-sm font-semibold text-gray-800 mb-2">Take-Up Ratio:</h4>
                            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-2">
                                <div>
                                    <label class="block text-xs font-medium text-gray-600 mb-1">L1: DB</label>
                                    <input v-model.number="editForm.DB" type="number" step="0.01" min="0" class="block w-full rounded-md border-gray-300 shadow-sm text-sm px-2 py-1.5">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-600 mb-1">L2: B</label>
                                    <input v-model.number="editForm.B" type="number" step="0.01" min="0" class="block w-full rounded-md border-gray-300 shadow-sm text-sm px-2 py-1.5">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-600 mb-1">L3: 1L</label>
                                    <input v-model.number="editForm._1L" type="number" step="0.01" min="0" class="block w-full rounded-md border-gray-300 shadow-sm text-sm px-2 py-1.5">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-600 mb-1">L4: A/C/E</label>
                                    <input v-model.number="editForm.A_C_E" type="number" step="0.01" min="0" class="block w-full rounded-md border-gray-300 shadow-sm text-sm px-2 py-1.5">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-600 mb-1">L5: 2L</label>
                                    <input v-model.number="editForm._2L" type="number" step="0.01" min="0" class="block w-full rounded-md border-gray-300 shadow-sm text-sm px-2 py-1.5">
                                </div>
                            </div>
                        </div>
                        
                        <!-- Flute Height & Starch Consumption -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Flute Height:</label>
                                <div class="flex items-center space-x-2">
                                    <input v-model.number="editForm.Height" type="number" step="0.01" min="0" class="block flex-1 rounded-md border-gray-300 shadow-sm text-sm">
                                    <span class="text-xs text-gray-600 whitespace-nowrap">mm</span>
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Starch Consumption:</label>
                                <div class="flex items-center space-x-2">
                                    <input v-model.number="editForm.Starch" type="number" step="0.01" min="0" class="block flex-1 rounded-md border-gray-300 shadow-sm text-sm">
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
                            <button type="button" @click="closeEditModal" class="w-full sm:w-auto px-4 py-2 bg-gray-100 text-gray-800 rounded-lg hover:bg-gray-200 text-sm font-medium">Cancel</button>
                            <button type="submit" class="w-full sm:w-auto px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 text-sm font-medium">Save</button>
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
            flutes.value = data;
        } else {
            flutes.value = [];
            console.error('Unexpected data format:', data);
            showNotification('Error loading paper flutes: Invalid data format', 'error');
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

// Watch for changes in search query to filter the data
watch(searchQuery, (newQuery) => {
    if (newQuery && flutes.value.length > 0) {
        const foundFlute = flutes.value.find(flute => 
            flute.Flute?.toLowerCase().includes(newQuery.toLowerCase()) ||
            flute.Descr?.toLowerCase().includes(newQuery.toLowerCase())
        );
        
        if (foundFlute) {
            selectedRow.value = foundFlute;
        }
    }
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
    if (!confirm(`Are you sure you want to obsolete paper flute "${editForm.value.Flute}"? This will hide it from paper flute selection.`)) {
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
