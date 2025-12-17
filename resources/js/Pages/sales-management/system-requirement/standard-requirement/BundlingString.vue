<template>
    <AppLayout :header="'Define Bundling String'">
    <Head title="Define Bundling String" />

    <!-- Hidden form with CSRF token -->
    <form ref="csrfForm" class="hidden">
        @csrf
    </form>

    <div class="min-h-screen bg-gray-50 py-6 px-4 sm:px-6 lg:px-8">
        <div class="max-w-6xl mx-auto">
            <!-- Header Section -->
            <div class="bg-emerald-600 text-white shadow-sm rounded-xl border border-emerald-700 mb-4">
                <div class="px-4 py-3 sm:px-6 flex items-center gap-3">
                    <div class="h-9 w-9 rounded-full bg-emerald-500 flex items-center justify-center">
                        <i class="fas fa-ribbon text-white text-lg"></i>
                    </div>
                    <div>
                        <h2 class="text-lg sm:text-xl font-semibold leading-tight">Define Bundling String</h2>
                        <p class="text-xs sm:text-sm text-emerald-100">Define bundling string types used in production and packaging.</p>
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
                                <h3 class="text-xl font-semibold text-gray-800">Bundling String Management</h3>
                            </div>

                            <!-- Search Section -->
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-6">
                                <div class="col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Bundling String Code:</label>
                                    <div class="relative flex">
                                        <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500">
                                            <i class="fas fa-hashtag"></i>
                                        </span>
                                        <input type="text" v-model="searchQuery" class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none border border-gray-300 focus:ring-emerald-500 focus:border-emerald-500 transition-colors" placeholder="Search or type bundling string code">
                                        <button type="button" @click="showModal = true" class="inline-flex items-center px-3 py-2 border border-l-0 border-emerald-500 bg-emerald-500 hover:bg-emerald-600 text-white rounded-r-md transition-colors transform active:translate-y-px">
                                            <i class="fas fa-table"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="col-span-1">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Action:</label>
                                    <button type="button" @click="createNewBundlingString" class="w-full flex items-center justify-center px-4 py-2 bg-gradient-to-r from-emerald-500 to-green-600 hover:from-emerald-600 hover:to-green-700 text-white rounded transition-colors transform active:translate-y-px">
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
                                    <p class="text-sm font-medium text-yellow-800">Loading bundling string data...</p>
                                </div>
                            </div>
                            <div v-else-if="bundlingStrings.length === 0" class="mt-4 bg-yellow-100 p-3 rounded">
                                <p class="text-sm font-medium text-yellow-800">No bundling string data available.</p>
                                <p class="text-xs text-yellow-700 mt-1">Make sure the database is properly configured and seeders have been run.</p>
                            </div>
                            <div v-else class="mt-4 bg-green-100 p-3 rounded">
                                <p class="text-sm font-medium text-green-800">Data available: {{ bundlingStrings.length }} bundling strings found.</p>
                                <p v-if="selectedRow" class="text-xs text-green-700 mt-1">
                                    Selected: <span class="font-semibold">{{ selectedRow.code }}</span> - {{ selectedRow.name }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column - Quick Info -->
                    <div class="lg:col-span-1">
                        <div class="bg-white p-4 sm:p-6 rounded-lg shadow-sm border-t-4 border-teal-500 mb-6">
                            <div class="flex items-center mb-4 pb-2 border-b border-gray-200">
                                <div class="p-2 bg-teal-500 rounded-lg mr-3">
                                    <i class="fas fa-info-circle text-white"></i>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-800">Bundling String Info</h3>
                            </div>

                            <div class="space-y-4">
                                <div class="p-4 bg-teal-50 rounded-lg">
                                    <h4 class="text-sm font-semibold text-teal-800 uppercase tracking-wider mb-2">Instructions</h4>
                                    <ul class="list-disc pl-5 text-sm text-gray-600 space-y-1">
                                        <li>Bundling string code must be unique</li>
                                        <li>Use appropriate bundling type for each product</li>
                                        <li>Inactive types will not appear in selection lists</li>
                                        <li>Any changes must be saved</li>
                                    </ul>
                                </div>

                                <div class="p-4 bg-blue-50 rounded-lg">
                                    <h4 class="text-sm font-semibold text-blue-800 uppercase tracking-wider mb-2">Common Types</h4>
                                    <div class="grid grid-cols-1 gap-2 text-sm">
                                        <div class="flex items-center">
                                            <span class="w-6 h-6 flex items-center justify-center bg-blue-600 text-white rounded-full font-bold mr-2">PP</span>
                                            <span>Plastic Strapping</span>
                                        </div>
                                        <div class="flex items-center">
                                            <span class="w-6 h-6 flex items-center justify-center bg-green-600 text-white rounded-full font-bold mr-2">PE</span>
                                            <span>Polyethylene String</span>
                                        </div>
                                        <div class="flex items-center">
                                            <span class="w-6 h-6 flex items-center justify-center bg-purple-600 text-white rounded-full font-bold mr-2">NT</span>
                                            <span>Natural Fiber</span>
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

    <!-- Use the existing BundlingStringModal component -->
    <BundlingStringModal
        v-if="showModal"
        :show="showModal"
        :items="bundlingStrings"
        @close="showModal = false"
        @select="onBundlingStringSelected"
    />

    <!-- Edit Modal -->
    <div v-if="showEditModal" class="fixed inset-0 z-50 bg-black bg-opacity-30 flex items-center justify-center">
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 w-11/12 md:w-2/5 max-w-md mx-auto">
            <div class="flex items-center justify-between px-4 py-3 border-b border-gray-200 bg-emerald-600 text-white rounded-t-xl">
                <div class="flex items-center">
                    <div class="p-2 bg-white bg-opacity-20 rounded-lg mr-3">
                        <i class="fas fa-link"></i>
                    </div>
                    <h3 class="text-sm font-semibold">{{ isCreating ? 'Create Bundling String' : 'Edit Bundling String' }}</h3>
                </div>
                <button type="button" @click="closeEditModal" class="text-white hover:text-gray-200">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>
            <div class="p-5">
                <form @submit.prevent="saveBundlingStringChanges" class="space-y-4">
                    <div class="grid grid-cols-1 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Bundling String Code:</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
                                    <i class="fas fa-hashtag"></i>
                                </span>
                                <input v-model="editForm.code" type="text" class="pl-10 block w-full rounded-md border-gray-300 shadow-sm text-sm" :class="{ 'bg-gray-100': !isCreating }" :readonly="!isCreating" required>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Bundling String Name:</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
                                    <i class="fas fa-font"></i>
                                </span>
                                <input v-model="editForm.name" type="text" class="pl-10 block w-full rounded-md border-gray-300 shadow-sm focus:ring-emerald-500 focus:border-emerald-500 text-sm" required>
                            </div>
                        </div>
                    </div>
                    <div class="flex justify-between mt-6 pt-4 border-t border-gray-200">
                        <button type="button" v-if="!isCreating" @click="deleteBundlingString(editForm.id)" class="px-4 py-2 bg-orange-500 text-white rounded-lg hover:bg-orange-600 transition-colors text-sm transform active:translate-y-px">
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
import BundlingStringModal from '@/Components/bundling-string-modal.vue';

// Get the header from props
const props = defineProps({
    header: {
        type: String,
        default: 'Bundling String Management'
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

const bundlingStrings = ref([]);
const loading = ref(false);
const saving = ref(false);
const showModal = ref(false);
const showEditModal = ref(false);
const selectedRow = ref(null);
const searchQuery = ref('');
const editForm = ref({
    id: null,
    code: '',
    name: ''
});
const isCreating = ref(false);
const notification = ref({ show: false, message: '', type: 'success' });

// Fetch bundling strings from API
const fetchBundlingStrings = async () => {
    loading.value = true;
    try {
        const response = await fetch('/api/bundling-strings', {
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

        let list = [];
        if (Array.isArray(data)) {
            list = data;
        } else if (data.data && Array.isArray(data.data)) {
            list = data.data;
        }

        bundlingStrings.value = (list || []).filter(item => !item.status || item.status === 'Act');
    } catch (error) {
        console.error('Error fetching bundling strings:', error);
        bundlingStrings.value = [];
    } finally {
        loading.value = false;
    }
};

onMounted(() => {
    fetchBundlingStrings();
});

// Watch for changes in search query to filter the data
watch(searchQuery, (newQuery) => {
    if (newQuery && bundlingStrings.value.length > 0) {
        const foundString = bundlingStrings.value.find(string =>
            string.code.toLowerCase().includes(newQuery.toLowerCase()) ||
            string.name.toLowerCase().includes(newQuery.toLowerCase())
        );

        if (foundString) {
            selectedRow.value = foundString;
        }
    }
});

// Watch for modal opening to refresh data
watch(showModal, (isOpen) => {
    if (isOpen) {
        fetchBundlingStrings();
    }
});

const onBundlingStringSelected = (string) => {
    selectedRow.value = string;
    searchQuery.value = string.code;
    showModal.value = false;

    // Automatically open the edit modal for the selected row
    isCreating.value = false;
    editForm.value = { ...string };
    showEditModal.value = true;
};

const createNewBundlingString = () => {
    isCreating.value = true;
    editForm.value = {
        id: null,
        code: '',
        name: ''
    };
    showEditModal.value = true;
};

const closeEditModal = () => {
    showEditModal.value = false;
    editForm.value = {
        id: null,
        code: '',
        name: ''
    };
    isCreating.value = false;
};

const saveBundlingStringChanges = async () => {
    // Validate form
    if (!editForm.value.code) {
        showNotification('Bundling string code is required', 'error');
        return;
    }

    if (!editForm.value.name) {
        showNotification('Bundling string name is required', 'error');
        return;
    }

    saving.value = true;

    try {
        const csrfToken = getCsrfToken();

        let url = isCreating.value ? '/api/bundling-strings' : `/api/bundling-strings/${editForm.value.id}`;
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
            throw new Error(errorData.message || 'Error saving bundling string');
        }

        await fetchBundlingStrings();

        if (isCreating.value) {
            showNotification('Bundling string created successfully', 'success');
            const newString = bundlingStrings.value.find(s => s.code === editForm.value.code);
            if (newString) {
                selectedRow.value = newString;
                searchQuery.value = newString.code;
            }
        } else {
            showNotification('Bundling string updated successfully', 'success');
        }

        closeEditModal();
    } catch (error) {
        console.error('Error saving bundling string:', error);
        showNotification('Error: ' + error.message, 'error');
    } finally {
        saving.value = false;
    }
};

const deleteBundlingString = async (id) => {
    if (!confirm(`Are you sure you want to obsolete this bundling string?`)) return;

    saving.value = true;

    try {
        const csrfToken = getCsrfToken();

        const response = await fetch(`/api/bundling-strings/${id}`, {
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
            throw new Error(errorData.message || 'Error obsoleting bundling string');
        }

        showNotification('Bundling string obsoleted successfully!', 'success');
        await fetchBundlingStrings();

        if (selectedRow.value && selectedRow.value.id === id) {
            selectedRow.value = null;
            searchQuery.value = '';
        }

        closeEditModal();
    } catch (error) {
        console.error('Error obsoleting bundling string:', error);
        showNotification(`Error obsoleting bundling string: ${error.message}`, 'error');
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
