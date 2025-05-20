<template>
    <Head title="Finishing Management" />

    <!-- Header Section -->
    <div class="bg-gradient-to-r from-cyan-700 to-blue-600 p-6 rounded-t-lg shadow-lg">
        <h2 class="text-2xl font-bold text-white mb-2 flex items-center">
            <i class="fas fa-tools mr-3"></i> Define Finishing
        </h2>
        <p class="text-cyan-100">Define finishing methods for product manufacturing</p>
    </div>

    <div class="bg-white rounded-b-lg shadow-lg p-6 mb-6">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column -->
            <div class="lg:col-span-2">
                <div class="bg-white p-6 rounded-lg shadow-md border-t-4 border-blue-500">
                    <div class="flex items-center mb-6 pb-2 border-b border-gray-200">
                        <div class="p-2 bg-blue-500 rounded-lg mr-3">
                            <i class="fas fa-edit text-white"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-800">Finishing Management</h3>
                    </div>
                    <!-- Header with navigation buttons -->
                    <div class="flex items-center space-x-2 mb-6">
                        <button type="button" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded flex items-center space-x-2">
                            <i class="fas fa-power-off"></i>
                        </button>
                        <button type="button" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded flex items-center space-x-2">
                            <i class="fas fa-arrow-right"></i>
                        </button>
                        <button type="button" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded flex items-center space-x-2">
                            <i class="fas fa-arrow-left"></i>
                        </button>
                        <button type="button" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded flex items-center space-x-2" @click="showModal = true">
                            <i class="fas fa-search"></i>
                        </button>
                        <button type="button" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded flex items-center space-x-2" @click="editSelectedRow">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button type="button" class="bg-purple-500 hover:bg-purple-600 text-white px-4 py-2 rounded flex items-center space-x-2" @click="createNewFinishing">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                    <!-- Search Section -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-6">
                        <div class="col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Finishing Code:</label>
                            <div class="relative flex">
                                <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500">
                                    <i class="fas fa-tools"></i>
                                </span>
                                <input type="text" v-model="finishingCode" @keyup.enter="handleSearchAndCreate" class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none border border-gray-300 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                <button type="button" @click="showModal = true" class="inline-flex items-center px-3 py-2 border border-l-0 border-gray-300 bg-blue-500 hover:bg-blue-600 text-white rounded-r-md">
                                    <i class="fas fa-table"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-span-1">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Record:</label>
                            <button type="button" @click="handleSearchAndCreate" class="w-full flex items-center justify-center px-4 py-2 bg-indigo-500 hover:bg-indigo-600 text-white rounded">
                                <i class="fas fa-edit mr-2"></i> Search & Edit
                            </button>
                        </div>
                    </div>
                    <!-- Data Status Information -->
                    <div v-if="loading" class="mt-4 bg-yellow-100 p-3 rounded">
                        <div class="flex items-center">
                            <div class="mr-3">
                                <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-yellow-700"></div>
                            </div>
                            <p class="text-sm font-medium text-yellow-800">Loading finishing data...</p>
                        </div>
                    </div>
                    <div v-else-if="finishings.length === 0" class="mt-4 bg-yellow-100 p-3 rounded">
                        <p class="text-sm font-medium text-yellow-800">No finishing data available.</p>
                        <p class="text-xs text-yellow-700 mt-1">Make sure the database is properly configured and seeders have been run.</p>
                        <div class="mt-2 flex items-center space-x-3">
                            <button @click="fetchFinishings" class="bg-blue-500 hover:bg-blue-600 text-white text-xs px-3 py-1 rounded">Reload Data</button>
                        </div>
                    </div>
                    <div v-else class="mt-4 bg-green-100 p-3 rounded">
                        <p class="text-sm font-medium text-green-800">Data available: {{ finishings.length }} finishing methods found.</p>
                        <p v-if="selectedRow" class="text-xs text-green-700 mt-1">
                            Selected: <span class="font-semibold">{{ selectedRow.code }}</span> - {{ selectedRow.description }}
                        </p>
                    </div>
                </div>
            </div>
            <!-- Right Column - Quick Info -->
            <div class="lg:col-span-1">
                <!-- Finishing Info Card -->
                <div class="bg-white p-6 rounded-lg shadow-md border-t-4 border-teal-500 mb-6">
                    <div class="flex items-center mb-4 pb-2 border-b border-gray-200">
                        <div class="p-2 bg-teal-500 rounded-lg mr-3">
                            <i class="fas fa-info-circle text-white"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-800">Finishing Information</h3>
                    </div>

                    <div class="space-y-4">
                        <div class="p-4 bg-teal-50 rounded-lg">
                            <h4 class="text-sm font-semibold text-teal-800 uppercase tracking-wider mb-2">Instructions</h4>
                            <ul class="list-disc pl-5 text-sm text-gray-600 space-y-1">
                                <li>Finishing code must be unique</li>
                                <li>Use the <span class="font-medium">search</span> button to select a finishing method</li>
                                <li>Enter a new code and press Enter to create a new finishing</li>
                                <li>Any changes must be saved</li>
                            </ul>
                        </div>

                        <div class="p-4 bg-blue-50 rounded-lg">
                            <h4 class="text-sm font-semibold text-blue-800 uppercase tracking-wider mb-2">Common Finishing Methods</h4>
                            <div class="grid grid-cols-1 gap-2 text-sm">
                                <div class="flex items-center">
                                    <span class="w-6 h-6 flex items-center justify-center bg-blue-500 text-white rounded-full font-bold mr-2">G</span>
                                    <span>Glue Application</span>
                                </div>
                                <div class="flex items-center">
                                    <span class="w-6 h-6 flex items-center justify-center bg-green-500 text-white rounded-full font-bold mr-2">S</span>
                                    <span>Stitching</span>
                                </div>
                                <div class="flex items-center">
                                    <span class="w-6 h-6 flex items-center justify-center bg-purple-500 text-white rounded-full font-bold mr-2">A</span>
                                    <span>Assembly</span>
                                </div>
                                <div class="flex items-center">
                                    <span class="w-6 h-6 flex items-center justify-center bg-red-500 text-white rounded-full font-bold mr-2">H</span>
                                    <span>Heat Treatment</span>
                                </div>
                                <div class="flex items-center">
                                    <span class="w-6 h-6 flex items-center justify-center bg-yellow-500 text-white rounded-full font-bold mr-2">W</span>
                                    <span>Wrapping</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Links -->
                <div class="bg-white p-6 rounded-lg shadow-md border-t-4 border-purple-500">
                    <div class="flex items-center mb-4 pb-2 border-b border-gray-200">
                        <div class="p-2 bg-purple-500 rounded-lg mr-3">
                            <i class="fas fa-link text-white"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-800">Quick Links</h3>
                    </div>

                    <div class="grid grid-cols-1 gap-3">
                        <Link href="/color" class="flex items-center p-3 bg-purple-50 rounded-lg hover:bg-purple-100 transition-colors">
                            <div class="p-2 bg-purple-500 rounded-full mr-3">
                                <i class="fas fa-palette text-white text-sm"></i>
                            </div>
                            <div>
                                <p class="font-medium text-purple-900">Colors</p>
                                <p class="text-xs text-purple-700">Manage colors</p>
                            </div>
                        </Link>

                        <Link href="/color-group" class="flex items-center p-3 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                            <div class="p-2 bg-blue-500 rounded-full mr-3">
                                <i class="fas fa-layer-group text-white text-sm"></i>
                            </div>
                            <div>
                                <p class="font-medium text-blue-900">Color Groups</p>
                                <p class="text-xs text-blue-700">View color groups</p>
                            </div>
                        </Link>

                        <Link href="/finishing/view-print" class="flex items-center p-3 bg-green-50 rounded-lg hover:bg-green-100 transition-colors">
                            <div class="p-2 bg-green-500 rounded-full mr-3">
                                <i class="fas fa-print text-white text-sm"></i>
                            </div>
                            <div>
                                <p class="font-medium text-green-900">Print List</p>
                                <p class="text-xs text-green-700">Print finishing list</p>
                            </div>
                        </Link>
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
    <div v-if="showEditModal" class="fixed inset-0 z-50 bg-black bg-opacity-50 flex items-center justify-center">
        <div class="bg-white rounded-lg shadow-xl w-11/12 md:w-2/5 max-w-md mx-auto">
            <div class="flex items-center justify-between p-4 border-b border-gray-200 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-t-lg">
                <div class="flex items-center">
                    <div class="p-2 bg-white bg-opacity-30 rounded-lg mr-3">
                        <i class="fas fa-tools"></i>
                    </div>
                    <h3 class="text-xl font-semibold">{{ isCreating ? 'Create Finishing' : 'Edit Finishing' }}</h3>
                </div>
                <button type="button" @click="closeEditModal" class="text-white hover:text-gray-200">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div class="p-6">
                <form @submit.prevent="saveFinishingChanges" class="space-y-4">
                    <div class="grid grid-cols-1 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Finishing Code:</label>
                            <input v-model="editForm.code" type="text" class="block w-full rounded-md border-gray-300 shadow-sm" :class="{ 'bg-gray-100': !isCreating }" :readonly="!isCreating" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Description:</label>
                            <input v-model="editForm.description" type="text" class="block w-full rounded-md border-gray-300 shadow-sm" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Note:</label>
                            <div class="border border-gray-300 rounded-md p-3 bg-gray-50 h-16 overflow-auto text-sm">
                                <p>Finishing methods are applied in the production process to finalize the product.</p>
                            </div>
                        </div>
                    </div>
                    <div class="flex justify-between mt-6 pt-4 border-t border-gray-200">
                        <button type="button" v-if="!isCreating" @click="deleteFinishing(editForm.code)" class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600">
                            <i class="fas fa-trash-alt mr-2"></i>Delete
                        </button>
                        <div v-else class="w-24"></div>
                        <div class="flex space-x-3">
                            <button type="button" @click="closeEditModal" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300">Cancel</button>
                            <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">Save</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Loading Overlay -->
    <div v-if="saving" class="fixed inset-0 z-50 bg-black bg-opacity-50 flex justify-center items-center">
        <div class="w-12 h-12 border-4 border-solid border-blue-500 border-t-transparent rounded-full animate-spin"></div>
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
</template>

<script setup>
import { ref, onMounted, watch } from 'vue';
import { Head, Link, usePage, router } from '@inertiajs/vue3';
import FinishingModal from '@/Components/finishing-modal.vue';

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
const finishingCode = ref('');
const editForm = ref({ 
    code: '', 
    description: ''
});
const isCreating = ref(false);
const notification = ref({ show: false, message: '', type: 'success' });

// Fetch finishings from API
const fetchFinishings = async () => {
    loading.value = true;
    try {
        const res = await fetch('/finishing/json/all', { 
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
            finishings.value = data;
        } else {
            finishings.value = [];
            console.error('Unexpected data format:', data);
        }
    } catch (e) {
        console.error('Error fetching finishings:', e);
        finishings.value = [];
    } finally {
        loading.value = false;
    }
};

onMounted(() => {
    if (finishings.value.length === 0) {
        fetchFinishings();
    }
});

// Watch for changes in finishingCode to filter the data
watch(finishingCode, (newQuery) => {
    if (newQuery && finishings.value.length > 0) {
        const foundFinishing = finishings.value.find(finish => 
            finish.code.toLowerCase().includes(newQuery.toLowerCase()) ||
            finish.description.toLowerCase().includes(newQuery.toLowerCase())
        );
        
        if (foundFinishing) {
            selectedRow.value = foundFinishing;
        }
    }
});

const handleSearchAndCreate = async () => {
    if (!finishingCode.value) return;
    
    saving.value = true;
    try {
        const code = finishingCode.value.trim().toUpperCase();
        
        // Search for the finishing code
        const response = await fetch(`/finishing/search/${code}`, {
            headers: { 
                'Accept': 'application/json'
            }
        });
        
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        
        const data = await response.json();
        
        if (data.exists) {
            // Find the finishing in our list
            const existingFinishing = finishings.value.find(f => f.code === code);
            if (existingFinishing) {
                selectedRow.value = existingFinishing;
                editSelectedRow();
            } else {
                // Reload data to find this finishing
                await fetchFinishings();
                const reloadedFinishing = finishings.value.find(f => f.code === code);
                if (reloadedFinishing) {
                    selectedRow.value = reloadedFinishing;
                    editSelectedRow();
                }
            }
        } else {
            // Create new finishing
            createNewFinishing(code);
        }
    } catch (e) {
        console.error('Error in search and create:', e);
        showNotification('Error searching for finishing: ' + e.message, 'error');
    } finally {
        saving.value = false;
    }
};

const onFinishingSelected = (finishing) => {
    selectedRow.value = finishing;
    finishingCode.value = finishing.code;
    showModal.value = false;
    
    // Automatically open the edit modal for the selected row
    isCreating.value = false;
    editForm.value = { ...finishing };
    showEditModal.value = true;
};

const editSelectedRow = () => {
    if (selectedRow.value) {
        isCreating.value = false;
        editForm.value = { ...selectedRow.value };
        showEditModal.value = true;
    } else {
        showNotification('Please select a finishing first', 'error');
    }
};

const createNewFinishing = (code = '') => {
    isCreating.value = true;
    editForm.value = { 
        code: code || '',
        description: ''
    };
    showEditModal.value = true;
};

const closeEditModal = () => {
    showEditModal.value = false;
    editForm.value = { 
        code: '', 
        description: ''
    };
    isCreating.value = false;
};

const saveFinishingChanges = async () => {
    saving.value = true;
    try {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
        
        // Different API call for create vs update
        let url = isCreating.value ? '/finishing' : `/finishing/${editForm.value.code}`;
        let method = isCreating.value ? 'POST' : 'PUT';
        
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
            })
        });
        
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
            
            // Set the code in the input field
            finishingCode.value = editForm.value.code;
            
            // Refresh the full data list to ensure we're in sync with the database
            await fetchFinishings();
            closeEditModal();
        } else {
            showNotification('Error: ' + (result.message || 'Unknown error'), 'error');
        }
    } catch (e) {
        console.error('Error saving finishing changes:', e);
        showNotification('Error saving finishing. Please try again.', 'error');
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
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
        
        const response = await fetch(`/finishing/${code}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        });
        
        if (response.ok) {
            // Remove the item from the local array
            finishings.value = finishings.value.filter(finishing => finishing.code !== code);
            
            if (selectedRow.value && selectedRow.value.code === code) {
                selectedRow.value = null;
                finishingCode.value = '';
            }
            
            closeEditModal();
            showNotification('Finishing deleted successfully', 'success');
        } else {
            const result = await response.json();
            showNotification('Error deleting finishing: ' + (result.message || 'Unknown error'), 'error');
        }
    } catch (e) {
        console.error('Error deleting finishing:', e);
        showNotification('Error deleting finishing. Please try again.', 'error');
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
