<template>
    <AppLayout :header="'Define Stitch Wire'">
    <Head title="Define Stitch Wire" />

    <!-- Hidden form with CSRF token -->
    <form ref="csrfForm" class="hidden">
        @csrf
    </form>

    <!-- Header Section -->
    <div class="bg-gradient-to-r from-green-600 to-green-700 p-6 sm:p-7 md:p-8 rounded-t-2xl shadow-lg">
        <h2 class="text-2xl md:text-3xl font-bold text-white mb-2 flex items-center">
            <span class="inline-flex items-center justify-center w-10 h-10 md:w-11 md:h-11 rounded-xl bg-white/15 mr-3">
                <i class="fas fa-link text-lg md:text-xl"></i>
            </span>
            Define Stitch Wire
        </h2>
        <p class="text-emerald-100 text-sm md:text-base">Define stitch wire types used in production</p>
    </div>

    <div class="bg-gradient-to-br from-slate-50 via-white to-emerald-50 rounded-b-2xl shadow-lg p-4 sm:p-6 mb-6">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8">
            <!-- Left Column -->
            <div class="lg:col-span-2">
                <div class="bg-white/90 backdrop-blur-sm p-5 sm:p-6 rounded-2xl shadow-md border border-emerald-100">
                    <div class="flex items-center mb-5 sm:mb-6 pb-3 border-b border-gray-100">
                        <div class="p-2.5 bg-emerald-500 rounded-xl mr-3 text-white">
                            <i class="fas fa-link"></i>
                        </div>
                        <div>
                            <h3 class="text-lg sm:text-xl font-semibold text-slate-800">Stitch Wire Management</h3>
                            <p class="text-xs sm:text-sm text-slate-500">Search, create, and maintain your stitch wire types</p>
                        </div>
                    </div>

                    <!-- Search Section -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-6">
                        <div class="col-span-2">
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Stitch Wire Code</label>
                            <div class="relative flex">
                                <span class="inline-flex items-center px-3 rounded-l-xl border border-r-0 border-gray-200 bg-slate-50 text-slate-500">
                                    <i class="fas fa-hashtag"></i>
                                </span>
                                <input type="text" v-model="searchQuery" class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none border border-gray-200 focus:ring-emerald-500 focus:border-emerald-500 text-slate-800 placeholder-slate-400 text-sm sm:text-base transition-colors" placeholder="Search or type stitch wire code">
                                <button type="button" @click="showModal = true" class="inline-flex items-center px-3 py-2 border border-l-0 border-emerald-500 bg-emerald-500 hover:bg-emerald-600 text-white rounded-r-xl text-sm">
                                    <i class="fas fa-table"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-span-1">
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Action</label>
                            <button type="button" @click="createNewStitchWire" class="w-full flex items-center justify-center px-4 py-2 rounded-xl bg-gradient-to-r from-emerald-500 to-green-600 hover:from-emerald-600 hover:to-green-700 text-white text-sm font-semibold shadow-md">
                                <i class="fas fa-plus-circle mr-2"></i>
                                Add New
                            </button>
                        </div>
                    </div>
                    <!-- Data Status Information -->
                    <div v-if="loading" class="mt-4 bg-amber-50 border border-amber-200 p-3 rounded-xl flex items-center space-x-3 text-sm">
                        <div class="flex items-center">
                            <div class="mr-3">
                                <div class="animate-spin rounded-full h-6 w-6 border-2 border-amber-300 border-t-amber-600"></div>
                            </div>
                            <p class="font-medium text-amber-800">Loading stitch wire data...</p>
                        </div>
                    </div>
                    <div v-else-if="stitchWires.length === 0" class="mt-4 bg-amber-50 border border-amber-200 p-3 rounded-xl">
                        <p class="text-sm font-semibold text-amber-800">No stitch wire data available.</p>
                        <p class="text-xs text-amber-700 mt-1">Make sure the database is properly configured and seeders have been run.</p>
                        <div class="mt-2 flex items-center space-x-3">
                            <button @click="fetchStitchWires" class="bg-emerald-500 hover:bg-emerald-600 text-white text-xs px-3 py-1 rounded-lg">Reload Data</button>
                        </div>
                    </div>
                    <div v-else class="mt-4 bg-emerald-50 border border-emerald-200 p-3 rounded-xl">
                        <p class="text-sm font-semibold text-emerald-800">Data available: {{ stitchWires.length }} stitch wires found.</p>
                        <p v-if="selectedRow" class="text-xs text-emerald-700 mt-1">
                            Selected: <span class="font-semibold">{{ selectedRow.code }}</span> - {{ selectedRow.description }}</p>
                    </div>
                </div>
            </div>
            <!-- Right Column - Quick Info -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Stitch Wire Info Card -->
                <div class="bg-emerald-50/80 backdrop-blur-sm p-5 rounded-2xl shadow-sm border border-emerald-100">
                    <div class="flex items-center mb-4 pb-2 border-b border-emerald-100">
                        <div class="p-2.5 bg-emerald-500 rounded-xl mr-3">
                            <i class="fas fa-info-circle text-white"></i>
                        </div>
                        <h3 class="text-base sm:text-lg font-semibold text-emerald-900">Stitch Wire Info</h3>
                    </div>

                    <div class="space-y-4">
                        <div class="p-4 bg-white/80 rounded-xl border border-emerald-100">
                            <h4 class="text-xs font-semibold text-emerald-700 uppercase tracking-wider mb-2">Instructions</h4>
                            <ul class="list-disc pl-5 text-xs sm:text-sm text-slate-600 space-y-1">
                                <li>Stitch wire code must be unique</li>
                                <li>Use appropriate wire type for each product</li>
                                <li>Inactive wire types will not appear in selection lists</li>
                                <li>Any changes must be saved</li>
                            </ul>
                        </div>

                        <div class="p-4 bg-sky-50 rounded-xl border border-sky-100">
                            <h4 class="text-xs font-semibold text-sky-800 uppercase tracking-wider mb-2">Common Wire Types</h4>
                            <div class="grid grid-cols-1 gap-2 text-xs sm:text-sm">
                                <div class="flex items-center">
                                    <span class="w-7 h-7 flex items-center justify-center bg-blue-500 text-white rounded-full font-bold mr-2">GS</span>
                                    <span>Galvanized Steel</span>
                                </div>
                                <div class="flex items-center">
                                    <span class="w-7 h-7 flex items-center justify-center bg-green-500 text-white rounded-full font-bold mr-2">SS</span>
                                    <span>Stainless Steel</span>
                                </div>
                                <div class="flex items-center">
                                    <span class="w-7 h-7 flex items-center justify-center bg-purple-500 text-white rounded-full font-bold mr-2">AL</span>
                                    <span>Aluminum</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Links -->
                <div class="bg-white/90 backdrop-blur-sm p-5 rounded-2xl shadow-sm border border-violet-100">
                    <div class="flex items-center mb-4 pb-2 border-b border-violet-100">
                        <div class="p-2.5 bg-violet-500 rounded-xl mr-3">
                            <i class="fas fa-link text-white"></i>
                        </div>
                        <h3 class="text-base sm:text-lg font-semibold text-slate-800">Quick Links</h3>
                    </div>

                    <div class="grid grid-cols-1 gap-3">
                        <Link href="/bundling-string" class="flex items-center p-3 bg-purple-50 rounded-xl hover:bg-purple-100 transition-colors border border-purple-100">
                            <div class="p-2.5 bg-purple-500 rounded-full mr-3">
                                <i class="fas fa-ribbon text-white text-sm"></i>
                            </div>
                            <div>
                                <p class="font-medium text-purple-900 text-sm">Bundling String</p>
                                <p class="text-xs text-purple-700">Manage bundling strings</p>
                            </div>
                        </Link>

                        <Link href="/wrapping-material" class="flex items-center p-3 bg-sky-50 rounded-xl hover:bg-sky-100 transition-colors border border-sky-100">
                            <div class="p-2.5 bg-sky-500 rounded-full mr-3">
                                <i class="fas fa-box-open text-white text-sm"></i>
                            </div>
                            <div>
                                <p class="font-medium text-sky-900 text-sm">Wrapping Material</p>
                                <p class="text-xs text-sky-700">Manage wrapping materials</p>
                            </div>
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Use StitchWireModal component -->
    <StitchWireModal
      v-if="showModal"
      :show="showModal"
      @close="showModal = false"
      @select="onStitchWireSelected"
    />

    <!-- Edit Modal -->
    <div v-if="showEditModal" class="fixed inset-0 z-50 bg-black bg-opacity-50 flex items-center justify-center">
        <div class="bg-white rounded-lg shadow-xl w-11/12 md:w-2/5 max-w-md mx-auto transform transition-transform duration-300">
            <div class="flex items-center justify-between p-4 border-b border-gray-200 bg-gradient-to-r from-green-600 to-green-700 text-white rounded-t-lg">
                <div class="flex items-center">
                    <div class="p-2 bg-white bg-opacity-30 rounded-lg mr-3">
                        <i class="fas fa-paperclip"></i>
                    </div>
                    <h3 class="text-xl font-semibold">{{ isCreating ? 'Create Stitch Wire' : 'Edit Stitch Wire' }}</h3>
                </div>
                <button type="button" @click="closeEditModal" class="text-white hover:text-gray-200 transform active:translate-y-px">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div class="p-6">
                <form @submit.prevent="saveStitchWireChanges" class="space-y-4">
                    <div class="grid grid-cols-1 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Stitch Wire Code:</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
                                    <i class="fas fa-hashtag"></i>
                                </span>
                                <input v-model="editForm.code" type="text" class="pl-10 block w-full rounded-md border-gray-300 shadow-sm" :class="{ 'bg-gray-100': !isCreating }" :readonly="!isCreating" required>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Stitch Wire Name:</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
                                    <i class="fas fa-font"></i>
                                </span>
                                <input v-model="editForm.name" type="text" class="pl-10 block w-full rounded-md border-gray-300 shadow-sm focus:ring-emerald-500 focus:border-emerald-500 text-sm" required>
                            </div>
                        </div>
                    </div>
                    <div class="flex justify-between mt-6 pt-4 border-t border-gray-200">
                        <button type="button" v-if="!isCreating" @click="deleteStitchWire(editForm.code)" class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors text-sm transform active:translate-y-px">
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
import StitchWireModal from '@/Components/stitch-wire-modal.vue';

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

const stitchWires = ref([]);
const loading = ref(false);
const saving = ref(false);
const showModal = ref(false);
const showEditModal = ref(false);
const selectedRow = ref(null);
const searchQuery = ref('');
const editForm = ref({
    code: '',
    name: ''
});
const isCreating = ref(false);
const notification = ref({ show: false, message: '', type: 'success' });

// Fetch stitch wires from API
const fetchStitchWires = async () => {
    loading.value = true;
    try {
        const response = await fetch('/api/stitch-wires', {
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

        // Handle different response formats
        if (Array.isArray(data)) {
            stitchWires.value = data;
        } else if (data.data && Array.isArray(data.data)) {
            stitchWires.value = data.data;
        } else {
            stitchWires.value = [];
            console.error('Unexpected data format:', data);
        }
    } catch (error) {
        console.error('Error fetching stitch wires:', error);
        showNotification('Failed to load stitch wires data', 'error');
        stitchWires.value = [];
    } finally {
        loading.value = false;
    }
};

onMounted(() => {
    fetchStitchWires();
});

// Watch for changes in search query to filter the data
watch(searchQuery, (newQuery) => {
    if (newQuery && stitchWires.value.length > 0) {
        const foundStitchWire = stitchWires.value.find(sw =>
            sw.code.toLowerCase().includes(newQuery.toLowerCase()) ||
            sw.name.toLowerCase().includes(newQuery.toLowerCase())
        );

        if (foundStitchWire) {
            selectedRow.value = foundStitchWire;
        }
    }
});

// Watch for modal opening to refresh data
watch(showModal, (isOpen) => {
    if (isOpen) {
        fetchStitchWires();
    }
});

const onStitchWireSelected = (stitchWire) => {
    selectedRow.value = stitchWire;
    searchQuery.value = stitchWire.code;
    showModal.value = false;

    // Automatically open the edit modal for the selected row
    isCreating.value = false;
    editForm.value = { ...stitchWire };
    showEditModal.value = true;
};

const createNewStitchWire = () => {
    isCreating.value = true;
    editForm.value = {
        code: '',
        name: ''
    };
    showEditModal.value = true;
};

const closeEditModal = () => {
    showEditModal.value = false;
    editForm.value = {
        code: '',
        name: ''
    };
    isCreating.value = false;
};

const saveStitchWireChanges = async () => {
    // Validate form
    if (!editForm.value.code) {
        showNotification('Stitch wire code is required', 'error');
        return;
    }

    if (!editForm.value.name) {
        showNotification('Stitch wire name is required', 'error');
        return;
    }

    saving.value = true;

    try {
        const csrfToken = getCsrfToken();

        // Different API call for create vs update
        let url = isCreating.value ? '/api/stitch-wires' : `/api/stitch-wires/${editForm.value.code}`;
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
            throw new Error(errorData.message || 'Error saving stitch wire');
        }

        const result = await response.json();

        // Update the local data
        await fetchStitchWires();

        // Show success notification
        if (isCreating.value) {
            showNotification('Stitch wire created successfully', 'success');
            // Find and select the newly created stitch wire
            const newStitchWire = stitchWires.value.find(sw => sw.code === editForm.value.code);
            if (newStitchWire) {
                selectedRow.value = newStitchWire;
                searchQuery.value = newStitchWire.code;
            }
        } else {
            showNotification('Stitch wire updated successfully', 'success');
        }

        // Close the edit modal
        closeEditModal();
    } catch (error) {
        console.error('Error saving stitch wire:', error);
        showNotification('Error: ' + error.message, 'error');
    } finally {
        saving.value = false;
    }
};

const deleteStitchWire = async (code) => {
    if (!confirm(`Are you sure you want to delete stitch wire "${code}"?`)) return;

    saving.value = true;

    try {
        const csrfToken = getCsrfToken();

        const response = await fetch(`/api/stitch-wires/${code}`, {
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
            throw new Error(errorData.message || 'Error deleting stitch wire');
        }

        showNotification('Stitch wire deleted successfully!', 'success');
        await fetchStitchWires();

        if (selectedRow.value && selectedRow.value.code === code) {
            selectedRow.value = null;
            searchQuery.value = '';
        }

        closeEditModal();
    } catch (error) {
        console.error('Error deleting stitch wire:', error);
        showNotification(`Error deleting stitch wire: ${error.message}`, 'error');
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
