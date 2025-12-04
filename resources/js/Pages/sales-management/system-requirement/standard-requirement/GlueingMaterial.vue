<template>
    <AppLayout :header="'Define Glueing Material'">
    <Head title="Define Glueing Material" />

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
                            <i class="fas fa-vial text-white text-sm"></i>
                        </div>
                        <div>
                            <h2 class="text-lg sm:text-xl font-semibold leading-tight">
                                Define Glueing Material
                            </h2>
                            <p class="text-xs sm:text-sm text-emerald-100">
                                Manage Glueing Material definitions for production.
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 text-xs text-emerald-100">
                        <i class="fas fa-info-circle text-sm"></i>
                        <span>Use glueing material codes to manage glue processes.</span>
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
                                <h3 class="text-sm sm:text-base font-semibold text-slate-800">Define Glueing Material</h3>
                                <p class="text-xs text-slate-500">Search, create, and maintain your Glueing Materials.</p>
                            </div>
                        </div>
                        <div class="px-4 py-4 sm:px-6">
                            <!-- Search Section -->
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                                <div class="col-span-2">
                                    <label class="block text-sm font-semibold text-slate-700 mb-1">Glueing Material Code</label>
                                    <div class="relative flex">
                                        <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-200 bg-slate-50 text-slate-500">
                                            <i class="fas fa-vial"></i>
                                        </span>
                                        <input
                                            type="text"
                                            v-model="searchQuery"
                                            class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none border border-gray-200 focus:ring-emerald-500 focus:border-emerald-500 text-slate-800 placeholder-slate-400 text-sm transition-colors"
                                            placeholder="Search or type glueing material code"
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
                                        @click="createNewglueingMaterial"
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
                                    <div class="mr-3 animate-spin rounded-full h-6 w-6 border-2 border-amber-300 border-t-amber-600"></div>
                                    <p class="font-medium text-amber-800">Loading Glueing Material data...</p>
                                </div>
                            </div>
                            <div v-else-if="glueingMaterials.length === 0" class="mt-3 bg-amber-50 border border-amber-200 p-3 rounded-lg">
                                <p class="text-sm font-semibold text-amber-800">No Glueing Material data available.</p>
                                <p class="text-xs text-amber-700 mt-1">Data will be automatically loaded when available.</p>
                            </div>
                            <div v-else class="mt-3 bg-emerald-50 border border-emerald-200 p-3 rounded-lg">
                                <p class="text-sm font-semibold text-emerald-800">Data available: {{ glueingMaterials.length }} Glueing Materials found.</p>
                                <p v-if="selectedRow" class="text-xs text-emerald-700 mt-1">
                                    Selected: <span class="font-semibold">{{ selectedRow.code }}</span> - {{ selectedRow.name }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column - Quick Info -->
                <div class="lg:col-span-1 space-y-4">
                    <!-- Glueing Material Info Card -->
                    <div class="bg-white shadow-sm rounded-xl border border-emerald-100">
                        <div class="px-4 py-3 sm:px-5 border-b border-emerald-100 flex items-center">
                            <div class="p-2 bg-emerald-500 rounded-lg mr-3">
                                <i class="fas fa-info-circle text-white"></i>
                            </div>
                            <h3 class="text-sm sm:text-base font-semibold text-emerald-900">Glueing Material Info</h3>
                        </div>

                        <div class="px-4 py-4 sm:px-5">
                            <div class="space-y-4">
                                <div class="p-4 bg-emerald-50 rounded-lg border border-emerald-100">
                                    <h4 class="text-xs font-semibold text-emerald-700 uppercase tracking-wider mb-2">Instructions</h4>
                                    <ul class="list-disc pl-5 text-xs sm:text-sm text-slate-600 space-y-1">
                                        <li>Glueing Material code must be unique</li>
                                        <li>Use the search button to select a string</li>
                                        <li>Specify string size and type</li>
                                        <li>Any changes must be saved</li>
                                    </ul>
                                </div>

                                <div class="p-4 bg-sky-50 rounded-lg border border-sky-100">
                                    <h4 class="text-xs font-semibold text-sky-800 uppercase tracking-wider mb-2">Common Sizes</h4>
                                    <div class="space-y-2 text-xs sm:text-sm">
                                        <div class="flex items-center">
                                            <span class="w-7 h-7 flex items-center justify-center bg-orange-500 text-white rounded-full font-bold mr-2">5</span>
                                            <span>5 MM</span>
                                        </div>
                                        <div class="flex items-center">
                                            <span class="w-7 h-7 flex items-center justify-center bg-emerald-500 text-white rounded-full font-bold mr-2">7</span>
                                            <span>7 MM</span>
                                        </div>
                                        <div class="flex items-center">
                                            <span class="w-7 h-7 flex items-center justify-center bg-green-500 text-white rounded-full font-bold mr-2">10</span>
                                            <span>10 MM</span>
                                        </div>
                                        <div class="flex items-center">
                                            <span class="w-7 h-7 flex items-center justify-center bg-emerald-600 text-white rounded-full font-bold mr-2">12</span>
                                            <span>12 MM</span>
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
                                <i class="fas fa-vial text-white"></i>
                            </div>
                            <h3 class="text-sm sm:text-base font-semibold text-slate-800">Quick Links</h3>
                        </div>

                        <div class="px-4 py-4 sm:px-5">
                            <div class="grid grid-cols-1 gap-3">
                                <Link href="/reinforcement-tape" class="flex items-center p-3 bg-purple-50 rounded-lg hover:bg-purple-100 transition-colors border border-purple-100">
                                    <div class="p-2 bg-purple-500 rounded-full mr-3">
                                        <i class="fas fa-tape text-white text-sm"></i>
                                    </div>
                                    <div>
                                        <p class="font-medium text-purple-900 text-sm">Reinforcement Tape</p>
                                        <p class="text-xs text-purple-700">Manage reinforcement tape</p>
                                    </div>
                                </Link>

                                <Link href="/chemical-coat" class="flex items-center p-3 bg-sky-50 rounded-lg hover:bg-sky-100 transition-colors border border-sky-100">
                                    <div class="p-2 bg-sky-500 rounded-full mr-3">
                                        <i class="fas fa-vial text-white text-sm"></i>
                                    </div>
                                    <div>
                                        <p class="font-medium text-sky-900 text-sm">Chemical Coat</p>
                                        <p class="text-xs text-sky-700">Manage chemical coatings</p>
                                    </div>
                                </Link>

                                <Link href="/glueing-material/view-print" class="flex items-center p-3 bg-emerald-50 rounded-lg hover:bg-emerald-100 transition-colors border border-emerald-100">
                                    <div class="p-2 bg-emerald-500 rounded-full mr-3">
                                        <i class="fas fa-print text-white text-sm"></i>
                                    </div>
                                    <div>
                                        <p class="font-medium text-emerald-900 text-sm">Print List</p>
                                        <p class="text-xs text-emerald-700">Print Glueing Material list</p>
                                    </div>
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Use the existing glueingMaterialModal component -->
    <glueingMaterialModal
        v-if="showModal"
        :show="showModal"
        :items="glueingMaterials"
        @close="showModal = false"
        @select="onglueingMaterialSelected"
    />

    <!-- Edit Modal -->
    <div v-if="showEditModal" class="fixed inset-0 z-50 bg-black bg-opacity-30 flex items-center justify-center">
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 w-11/12 md:w-2/5 max-w-md mx-auto">
            <div class="flex items-center justify-between px-4 py-3 border-b border-gray-200 bg-emerald-600 text-white rounded-t-xl">
                <div class="flex items-center">
                    <div class="p-2 bg-white bg-opacity-20 rounded-lg mr-3">
                        <i class="fas fa-vial"></i>
                    </div>
                    <h3 class="text-sm font-semibold">{{ isCreating ? 'Create Glueing Material' : 'Edit Glueing Material' }}</h3>
                </div>
                <button type="button" @click="closeEditModal" class="text-white hover:text-gray-200">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>
            <div class="p-5">
                <form @submit.prevent="saveglueingMaterialChanges" class="space-y-4">
                    <div class="grid grid-cols-1 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Glueing Material Code:</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
                                    <i class="fas fa-hashtag"></i>
                                </span>
                                <input v-model="editForm.code" type="text" class="pl-10 block w-full rounded-md border-gray-300 shadow-sm text-sm" :class="{ 'bg-gray-100': !isCreating }" :readonly="!isCreating" required>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Glueing Material Name:</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
                                    <i class="fas fa-font"></i>
                                </span>
                                <input v-model="editForm.name" type="text" class="pl-10 block w-full rounded-md border-gray-300 shadow-sm focus:ring-emerald-500 focus:border-emerald-500 text-sm" required>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Description:</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
                                    <i class="fas fa-align-left"></i>
                                </span>
                                <input v-model="editForm.description" type="text" class="pl-10 block w-full rounded-md border-gray-300 shadow-sm focus:ring-emerald-500 focus:border-emerald-500 text-sm">
                            </div>
                        </div>
                    </div>
                    <div class="flex justify-between mt-5 pt-4 border-t border-gray-200">
                        <button type="button" v-if="!isCreating" @click="deleteglueingMaterial(editForm.id)" class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 text-sm font-medium">
                            <i class="fas fa-trash-alt mr-2"></i>Delete
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
import glueingMaterialModal from '@/Components/glueing-material-modal.vue';

// Get the header from props
const props = defineProps({
    header: {
        type: String,
        default: 'Glueing Material Management'
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

const glueingMaterials = ref([]);
const loading = ref(false);
const saving = ref(false);
const showModal = ref(false);
const showEditModal = ref(false);
const selectedRow = ref(null);
const searchQuery = ref('');
const editForm = ref({
    id: null,
    code: '',
    name: '',
    description: '',
    status: 'Act',
    is_active: true
});
const isCreating = ref(false);
const notification = ref({ show: false, message: '', type: 'success' });

// Fetch Glueing Materials from API
const fetchglueingMaterials = async () => {
    loading.value = true;
    try {
        const response = await fetch('/api/glueing-materials', {
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

        glueingMaterials.value = (list || []).filter(item => !item.status || item.status === 'Act');
    } catch (error) {
        console.error('Error fetching Glueing Materials:', error);
        glueingMaterials.value = [];
    } finally {
        loading.value = false;
    }
};

onMounted(() => {
    fetchglueingMaterials();
});

// Watch for changes in search query to filter the data
watch(searchQuery, (newQuery) => {
    if (newQuery && glueingMaterials.value.length > 0) {
        const foundString = glueingMaterials.value.find(string =>
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
        fetchglueingMaterials();
    }
});

const onglueingMaterialSelected = (string) => {
    selectedRow.value = string;
    searchQuery.value = string.code;
    showModal.value = false;

    // Automatically open the edit modal for the selected row
    isCreating.value = false;
    editForm.value = { ...string };
    showEditModal.value = true;
};

const createNewglueingMaterial = () => {
    isCreating.value = true;
    editForm.value = {
        id: null,
        code: '',
        name: '',
        description: '',
        status: 'Act',
        is_active: true
    };
    showEditModal.value = true;
};

const closeEditModal = () => {
    showEditModal.value = false;
    editForm.value = {
        id: null,
        code: '',
        name: '',
        description: '',
        status: 'Act',
        is_active: true
    };
    isCreating.value = false;
};

const saveglueingMaterialChanges = async () => {
    // Validate form
    if (!editForm.value.code) {
        showNotification('Glueing Material code is required', 'error');
        return;
    }

    if (!editForm.value.name) {
        showNotification('Glueing Material name is required', 'error');
        return;
    }

    saving.value = true;

    try {
        const csrfToken = getCsrfToken();

        let url = isCreating.value ? '/api/glueing-materials' : `/api/glueing-materials/${editForm.value.id}`;
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
            throw new Error(errorData.message || 'Error saving Glueing Material');
        }

        await fetchglueingMaterials();

        if (isCreating.value) {
            showNotification('Glueing Material created successfully', 'success');
            const newString = glueingMaterials.value.find(s => s.code === editForm.value.code);
            if (newString) {
                selectedRow.value = newString;
                searchQuery.value = newString.code;
            }
        } else {
            showNotification('Glueing Material updated successfully', 'success');
        }

        closeEditModal();
    } catch (error) {
        console.error('Error saving Glueing Material:', error);
        showNotification('Error: ' + error.message, 'error');
    } finally {
        saving.value = false;
    }
};

const deleteglueingMaterial = async (id) => {
    if (!confirm(`Are you sure you want to delete this Glueing Material?`)) return;

    saving.value = true;

    try {
        const csrfToken = getCsrfToken();

        const response = await fetch(`/api/glueing-materials/${id}`, {
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
            throw new Error(errorData.message || 'Error deleting Glueing Material');
        }

        showNotification('Glueing Material deleted successfully!', 'success');
        await fetchglueingMaterials();

        if (selectedRow.value && selectedRow.value.id === id) {
            selectedRow.value = null;
            searchQuery.value = '';
        }

        closeEditModal();
    } catch (error) {
        console.error('Error deleting Glueing Material:', error);
        showNotification(`Error deleting Glueing Material: ${error.message}`, 'error');
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
