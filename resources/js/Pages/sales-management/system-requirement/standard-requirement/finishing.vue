<template>
    <AppLayout :header="'Finishing'">
    <Head title="Finishing Management" />

    <!-- Hidden form with CSRF token -->
    <form ref="csrfForm" class="hidden">
        @csrf
    </form>
    <!-- Header Section -->
    <div class="min-h-screen bg-gray-50 py-6 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <div class="bg-emerald-600 text-white shadow-sm rounded-xl border border-emerald-700 mb-4">
                <div class="px-4 py-3 sm:px-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div class="flex items-center gap-3">
                        <div class="h-9 w-9 rounded-full bg-emerald-500 flex items-center justify-center">
                            <i class="fas fa-tools text-white text-sm"></i>
                        </div>
                        <div>
                            <h2 class="text-lg sm:text-xl font-semibold leading-tight">
                                Define Finishing
                            </h2>
                            <p class="text-xs sm:text-sm text-emerald-100">
                                Define finishing methods for product manufacturing.
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 text-xs text-emerald-100">
                        <i class="fas fa-info-circle text-sm"></i>
                        <span>Use finishing codes to manage production processes.</span>
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
                                <h3 class="text-sm sm:text-base font-semibold text-slate-800">Finishing Management</h3>
                                <p class="text-xs text-slate-500">Search, create, and maintain your finishing methods.</p>
                            </div>
                        </div>
                        <div class="px-4 py-4 sm:px-6">
                            <!-- Search Section -->
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                                <div class="col-span-2">
                                    <label class="block text-sm font-semibold text-slate-700 mb-1">Finishing Code</label>
                                    <div class="relative flex">
                                        <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-200 bg-slate-50 text-slate-500">
                                            <i class="fas fa-tools"></i>
                                        </span>
                                        <input
                                            type="text"
                                            v-model="searchQuery"
                                            class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none border border-gray-200 focus:ring-emerald-500 focus:border-emerald-500 text-slate-800 placeholder-slate-400 text-sm transition-colors"
                                            placeholder="Search or type finishing code"
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
                                        @click="createNewFinishing"
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
                                    <p class="font-medium text-amber-800">Loading finishing data...</p>
                                </div>
                            </div>
                            <div v-else-if="finishings.length === 0" class="mt-3 bg-amber-50 border border-amber-200 p-3 rounded-lg">
                                <p class="text-sm font-semibold text-amber-800">No finishing data available.</p>
                                <p class="text-xs text-amber-700 mt-1">Data will be automatically loaded when available.</p>
                            </div>
                            <div v-else class="mt-3 bg-emerald-50 border border-emerald-200 p-3 rounded-lg">
                                <p class="text-sm font-semibold text-emerald-800">Data available: {{ finishings.length }} finishing methods found.</p>
                                <p v-if="selectedRow" class="text-xs text-emerald-700 mt-1">
                                    Selected: <span class="font-semibold">{{ selectedRow.code }}</span> - {{ selectedRow.description }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Right Column - Quick Info -->
                <div class="lg:col-span-1 space-y-4">
                    <!-- Finishing Info Card -->
                    <div class="bg-white shadow-sm rounded-xl border border-emerald-100">
                        <div class="px-4 py-3 sm:px-5 border-b border-emerald-100 flex items-center">
                            <div class="p-2 bg-emerald-500 rounded-lg mr-3">
                                <i class="fas fa-info-circle text-white"></i>
                            </div>
                            <h3 class="text-sm sm:text-base font-semibold text-emerald-900">Finishing Information</h3>
                        </div>

                        <div class="px-4 py-4 sm:px-5">
                            <div class="space-y-4">
                                <div class="p-4 bg-emerald-50 rounded-lg border border-emerald-100">
                                    <h4 class="text-xs font-semibold text-emerald-700 uppercase tracking-wider mb-2">Instructions</h4>
                                    <ul class="list-disc pl-5 text-xs sm:text-sm text-slate-600 space-y-1">
                                        <li>Finishing code must be unique and cannot be changed</li>
                                        <li>Use the <span class="font-medium">search</span> button to find finishing methods</li>
                                        <li>Enter a new code and press Add New to create a new finishing</li>
                                        <li>Any changes must be saved</li>
                                    </ul>
                                </div>

                                <div class="p-4 bg-sky-50 rounded-lg border border-sky-100">
                                    <h4 class="text-xs font-semibold text-sky-800 uppercase tracking-wider mb-2">Common Finishing Methods</h4>
                                    <div class="grid grid-cols-1 gap-2 text-xs sm:text-sm">
                                        <div class="flex items-center">
                                            <span class="w-7 h-7 flex items-center justify-center bg-emerald-500 text-white rounded-full font-bold mr-2">G</span>
                                            <span>Glue Application</span>
                                        </div>
                                        <div class="flex items-center">
                                            <span class="w-7 h-7 flex items-center justify-center bg-green-500 text-white rounded-full font-bold mr-2">S</span>
                                            <span>Stitching</span>
                                        </div>
                                        <div class="flex items-center">
                                            <span class="w-7 h-7 flex items-center justify-center bg-purple-500 text-white rounded-full font-bold mr-2">A</span>
                                            <span>Assembly</span>
                                        </div>
                                        <div class="flex items-center">
                                            <span class="w-7 h-7 flex items-center justify-center bg-red-500 text-white rounded-full font-bold mr-2">H</span>
                                            <span>Heat Treatment</span>
                                        </div>
                                        <div class="flex items-center">
                                            <span class="w-7 h-7 flex items-center justify-center bg-yellow-500 text-white rounded-full font-bold mr-2">W</span>
                                            <span>Wrapping</span>
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
    <FinishingModal
        :show="showModal"
        :finishings="finishings"
        @close="showModal = false"
        @select="onFinishingSelected"
    />

    <!-- Edit Modal -->
    <div v-if="showEditModal" class="fixed inset-0 z-50 bg-black bg-opacity-30 flex items-center justify-center">
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 w-11/12 md:w-2/5 max-w-md mx-auto">
            <div class="flex items-center justify-between px-4 py-3 border-b border-gray-200 bg-emerald-600 text-white rounded-t-xl">
                <div class="flex items-center">
                    <div class="p-2 bg-white bg-opacity-20 rounded-lg mr-3">
                        <i class="fas fa-tools"></i>
                    </div>
                    <h3 class="text-sm font-semibold">{{ isCreating ? 'Create Finishing' : 'Edit Finishing' }}</h3>
                </div>
                <button type="button" @click="closeEditModal" class="text-white hover:text-gray-200">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>
            <div class="p-5">
                <form @submit.prevent="saveFinishingChanges" class="space-y-4">
                    <div class="grid grid-cols-1 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Code:</label>
                            <input v-model="editForm.code" type="text" class="block w-full rounded-md border-gray-300 shadow-sm text-sm" :class="{ 'bg-gray-100': !isCreating }" :readonly="!isCreating" required>
                            <span class="text-xs text-gray-500">Finishing code must be unique</span>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Description:</label>
                            <input v-model="editForm.description" type="text" class="block w-full rounded-md border-gray-300 shadow-sm text-sm" required>
                        </div>
                    </div>
                    <div class="flex justify-between mt-5 pt-4 border-t border-gray-200">
                        <button type="button" v-if="!isCreating" @click="obsoleteFinishing(editForm.code)" class="px-4 py-2 bg-orange-500 text-white rounded-lg hover:bg-orange-600 text-sm font-medium">
                            <i class="fas fa-ban mr-2"></i>Obsolete
                        </button>
                        <div v-else class="w-24"></div>
                        <div class="flex space-x-3">
                            <button type="button" @click="closeEditModal" class="px-4 py-2 bg-gray-100 text-gray-800 rounded-lg hover:bg-gray-200 text-sm font-medium">Cancel</button>
                            <button type="submit" class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 text-sm font-medium">Save</button>
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
import { ref, onMounted, watch } from 'vue';
import { Head, Link, usePage, router } from '@inertiajs/vue3';
import FinishingModal from '@/Components/finishing-modal.vue';
import AppLayout from '@/Layouts/AppLayout.vue';

// Get the header from props
const props = defineProps({
    header: {
        type: String,
        default: 'Finishing Management'
    },
    finishings: {
        type: Array,
        default: () => []
    }
});

const finishings = ref(props.finishings || []);
const loading = ref(false);
const saving = ref(false);
const showModal = ref(false);
const showEditModal = ref(false);
const selectedRow = ref(null);
const searchQuery = ref('');
const editForm = ref({ code: '', description: '' });
const isCreating = ref(false);
const notification = ref({ show: false, message: '', type: 'success' });

// Reference to the CSRF form
const csrfForm = ref(null);

// Function to get fresh CSRF token from the form
const getCsrfToken = () => {
    // Try to get token from meta tag first
    let token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    
    // If token from meta tag is not available or we want a fresh token, get from the form
    if (csrfForm.value) {
        const tokenInput = csrfForm.value.querySelector('input[name="_token"]');
        if (tokenInput) {
            token = tokenInput.value;
        }
    }
    
    return token || '';
};

const fetchFinishings = async () => {
    loading.value = true;
    try {
        const res = await fetch('/api/finishings', { 
            headers: { 
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            credentials: 'same-origin'
        });
        
        if (!res.ok) {
            throw new Error('Network response was not ok');
        }
        
        const data = await res.json();
        console.log('Fetched finishings:', data);
        
        if (Array.isArray(data)) {
            finishings.value = data;
        } else if (data.data && Array.isArray(data.data)) {
            finishings.value = data.data;
        } else {
            finishings.value = [];
            console.error('Unexpected data format:', data);
        }
        
        // Verify that each record has an ID
        if (finishings.value.length > 0) {
            if (finishings.value[0].id === undefined) {
                console.warn('Warning: Finishing records do not have ID property!');
                // Try to find another identifier to use as ID
                if (finishings.value[0].code) {
                    console.log('Using code as ID fallback');
                    finishings.value = finishings.value.map(finishing => ({
                        ...finishing,
                        id: finishing.id || finishing.code // Use existing ID or fallback to code
                    }));
                }
            }
        }
        
        console.log('Processed finishings:', finishings.value);
    } catch (e) {
        console.error('Error fetching finishings:', e);
        finishings.value = [];
    } finally {
        loading.value = false;
    }
};

onMounted(() => {
        fetchFinishings();
});

// Watch for changes in search query to filter the data
watch(searchQuery, (newQuery) => {
    if (newQuery && finishings.value.length > 0) {
        const foundFinishing = finishings.value.find(finishing => 
            finishing.code.toLowerCase().includes(newQuery.toLowerCase()) ||
            finishing.description.toLowerCase().includes(newQuery.toLowerCase())
        );
        
        if (foundFinishing) {
            selectedRow.value = foundFinishing;
        }
    }
});

const onFinishingSelected = (finishing) => {
    selectedRow.value = finishing;
    searchQuery.value = finishing.code;
    showModal.value = false;
    
    // Automatically open the edit modal for the selected row
    isCreating.value = false;
    editForm.value = { 
        id: finishing.id,
        code: finishing.code, 
        description: finishing.description
    };
    console.log('Selected finishing for editing:', editForm.value);
    showEditModal.value = true;
};

const createNewFinishing = () => {
    isCreating.value = true;
    editForm.value = { code: '', description: '' };
    showEditModal.value = true;
};

const closeEditModal = () => {
    showEditModal.value = false;
    editForm.value = { code: '', description: '' };
    isCreating.value = false;
};

const saveFinishingChanges = async () => {
    saving.value = true;
    try {
        // Get fresh CSRF token
        const csrfToken = getCsrfToken();
        
        // Different API call for create vs update
        // Use code as the identifier for updates instead of id
        let url = isCreating.value ? '/api/finishings' : `/api/finishings/${editForm.value.code}`;
        let method = isCreating.value ? 'POST' : 'PUT';
        
        // Log what we're trying to update to help with debugging
        console.log('Updating finishing with code:', editForm.value.code);
        console.log('Update data:', editForm.value);
        
        const response = await fetch(url, {
            method: method,
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                code: editForm.value.code,
                description: editForm.value.description
            }),
            credentials: 'same-origin' // Include cookies in the request
        });
        
        if (!response.ok) {
            const errorData = await response.json();
            throw new Error(errorData.message || 'Error updating finishing');
        }
        
        const result = await response.json();
        
        if (result.success) {
            // Update the local data with the changes or add new item
            if (isCreating.value) {
                showNotification('Finishing created successfully', 'success');
            } else {
                if (selectedRow.value) {
                    selectedRow.value.description = editForm.value.description;
                }
                showNotification('Finishing updated successfully', 'success');
            }
            
            // Refresh the full data list to ensure we're in sync with the database
            await fetchFinishings();
            closeEditModal();
        } else {
            showNotification('Error: ' + (result.message || 'Unknown error'), 'error');
        }
    } catch (e) {
        console.error('Error saving finishing changes:', e);
        showNotification('Error saving finishing: ' + e.message, 'error');
    } finally {
        saving.value = false;
    }
};

const deleteFinishing = async (code) => {
    if (!confirm(`Are you sure you want to delete finishing "${code}"?`)) {
        return;
    }
    
    saving.value = true;
    try {
        // Get fresh CSRF token
        const csrfToken = getCsrfToken();
        
        console.log('Deleting finishing with code:', code);
        
        // Use code as the identifier instead of id
        const response = await fetch(`/api/finishings/${code}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            credentials: 'same-origin' // Include cookies in the request
        });
        
        if (!response.ok) {
            const errorData = await response.json();
            throw new Error(errorData.message || 'Error deleting finishing');
        }
        
        const result = await response.json();
        
        if (result.success) {
            // Remove the item from the local array
            finishings.value = finishings.value.filter(finishing => finishing.code !== code);
            
            if (selectedRow.value && selectedRow.value.code === code) {
                selectedRow.value = null;
                searchQuery.value = '';
            }
            
            closeEditModal();
            showNotification('Finishing deleted successfully', 'success');
        } else {
            showNotification('Error deleting finishing: ' + (result.message || 'Unknown error'), 'error');
        }
    } catch (e) {
        console.error('Error deleting finishing:', e);
        showNotification('Error deleting finishing: ' + e.message, 'error');
    } finally {
        saving.value = false;
    }
};


const obsoleteFinishing = async (code) => {
    if (!code) {
        showNotification('No finishing selected', 'error');
        return;
    }

    if (!confirm(`Are you sure you want to obsolete finishing "${code}"? This will hide it from selection and tables.`)) {
        return;
    }

    saving.value = true;

    try {
        const csrfToken = getCsrfToken();

        const response = await fetch(`/api/finishings/${code}/status`, {
            method: 'PUT',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            credentials: 'same-origin'
        });

        const result = await response.json();

        if (!response.ok || !result.success) {
            throw new Error(result.message || 'Error obsoleting finishing');
        }

        await fetchFinishings();

        if (selectedRow.value && selectedRow.value.code === code) {
            selectedRow.value = null;
            searchQuery.value = '';
        }

        closeEditModal();

        showNotification(`Finishing "${code}" has been marked as obsolete successfully.`, 'success');
    } catch (e) {
        console.error('Error obsoleting finishing:', e);
        showNotification('Error obsoleting finishing: ' + e.message, 'error');
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
