<template>
    <AppLayout :header="'Paper Quality'">
    <Head title="Paper Quality Management" />

    <!-- Header Section -->
    <div class="min-h-screen bg-gray-50 py-6 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <div class="bg-emerald-600 text-white shadow-sm rounded-xl border border-emerald-700 mb-4">
                <div class="px-4 py-3 sm:px-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div class="flex items-center gap-3">
                        <div class="h-9 w-9 rounded-full bg-emerald-500 flex items-center justify-center">
                            <i class="fas fa-scroll text-white text-sm"></i>
                        </div>
                        <div>
                            <h2 class="text-lg sm:text-xl font-semibold leading-tight">
                                Define Paper Quality
                            </h2>
                            <p class="text-xs sm:text-sm text-emerald-100">
                                Define paper quality types for various production needs.
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 text-xs text-emerald-100">
                        <i class="fas fa-info-circle text-sm"></i>
                        <span>Use paper quality codes to manage GSM, type, and mapping.</span>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8">
                <!-- Left Column - Main Content -->
                <div class="lg:col-span-2 space-y-4">
                    <div class="bg-white shadow-sm rounded-xl border border-emerald-100">
                        <div class="px-4 py-3 sm:px-6 border-b border-gray-100 flex items-center">
                            <div class="p-2 bg-emerald-500 rounded-lg mr-3 text-white">
                                <i class="fas fa-scroll"></i>
                            </div>
                            <div>
                                <h3 class="text-sm sm:text-base font-semibold text-slate-800">Paper Quality Management</h3>
                                <p class="text-xs text-slate-500">Search, create, and maintain your paper qualities.</p>
                            </div>
                        </div>
                        <div class="px-4 py-4 sm:px-6">
                            <!-- Search Section -->
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                                <div class="col-span-2">
                                    <label class="block text-sm font-semibold text-slate-700 mb-1">Paper Quality Code</label>
                                    <div class="relative flex">
                                        <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-200 bg-slate-50 text-slate-500">
                                            <i class="fas fa-hashtag"></i>
                                        </span>
                                        <input
                                            type="text"
                                            v-model="searchQuery"
                                            class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none border border-gray-200 focus:ring-emerald-500 focus:border-emerald-500 text-slate-800 placeholder-slate-400 text-sm transition-colors"
                                            placeholder="Search or type paper quality code"
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
                                        @click="createNewPaperQuality"
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
                                    <p class="font-medium text-amber-800">Loading paper quality data...</p>
                                </div>
                            </div>
                            <div v-else-if="paperQualities.length === 0" class="mt-3 bg-amber-50 border border-amber-200 p-3 rounded-lg">
                                <p class="text-sm font-semibold text-amber-800">No paper quality data available.</p>
                                <p class="text-xs text-amber-700 mt-1">Make sure the database is properly configured and seeders have been run.</p>
                                <div class="mt-2 flex items-center space-x-3">
                                    <button @click="fetchPaperQualities" class="bg-emerald-500 hover:bg-emerald-600 text-white text-xs px-3 py-1 rounded-lg">Reload Data</button>
                                </div>
                            </div>
                            <div v-else class="mt-3 bg-emerald-50 border border-emerald-200 p-3 rounded-lg">
                                <p class="text-sm font-semibold text-emerald-800">Data available: {{ paperQualities.length }} paper qualities found.</p>
                                <p v-if="selectedQuality" class="text-xs text-emerald-700 mt-1">
                                    Selected: <span class="font-semibold">{{ selectedQuality.paper_quality }}</span> - {{ selectedQuality.paper_name }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column - Quick Info -->
                <div class="lg:col-span-1 space-y-4">
                    <!-- Paper Quality Info Card -->
                    <div class="bg-white shadow-sm rounded-xl border border-emerald-100">
                        <div class="px-4 py-3 sm:px-5 border-b border-emerald-100 flex items-center">
                            <div class="p-2 bg-emerald-500 rounded-lg mr-3">
                                <i class="fas fa-info-circle text-white"></i>
                            </div>
                            <h3 class="text-sm sm:text-base font-semibold text-emerald-900">Paper Quality Info</h3>
                        </div>

                        <div class="px-4 py-4 sm:px-5">
                            <div class="space-y-4">
                                <div class="p-4 bg-emerald-50 rounded-lg border border-emerald-100">
                                    <h4 class="text-xs font-semibold text-emerald-700 uppercase tracking-wider mb-2">Instructions</h4>
                                    <ul class="list-disc pl-5 text-xs sm:text-sm text-slate-600 space-y-1">
                                        <li>Paper quality code must be unique</li>
                                        <li>GSM is the gramature measurement (g/m²)</li>
                                        <li>Use inactive status for paper types no longer in use</li>
                                        <li>Any changes must be saved</li>
                                    </ul>
                                </div>

                                <div class="p-4 bg-sky-50 rounded-lg border border-sky-100">
                                    <h4 class="text-xs font-semibold text-sky-800 uppercase tracking-wider mb-2">Paper Types</h4>
                                    <div class="grid grid-cols-2 gap-2 text-xs sm:text-sm">
                                        <div class="flex items-center">
                                            <span class="w-7 h-7 flex items-center justify-center bg-blue-500 text-white rounded-full font-bold mr-2">L</span>
                                            <span>Liner</span>
                                        </div>
                                        <div class="flex items-center">
                                            <span class="w-7 h-7 flex items-center justify-center bg-blue-500 text-white rounded-full font-bold mr-2">M</span>
                                            <span>Medium</span>
                                        </div>
                                        <div class="flex items-center">
                                            <span class="w-7 h-7 flex items-center justify-center bg-blue-500 text-white rounded-full font-bold mr-2">K</span>
                                            <span>Kraft</span>
                                        </div>
                                        <div class="flex items-center">
                                            <span class="w-7 h-7 flex items-center justify-center bg-blue-500 text-white rounded-full font-bold mr-2">C</span>
                                            <span>Chipboard</span>
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

    <!-- Modal for Paper Quality Table -->
    <PaperQualityModal
        :show="showModal"
        :qualities="paperQualities"
        @close="showModal = false"
        @select="onPaperQualitySelected"
        @create="createNewPaperQuality"
    />

    <!-- Edit Modal -->
    <div v-if="showEditModal" class="fixed inset-0 z-50 bg-black bg-opacity-30 flex items-center justify-center">
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 w-11/12 md:w-2/5 max-w-md mx-auto">
            <div class="flex items-center justify-between px-4 py-3 border-b border-gray-200 bg-emerald-600 text-white rounded-t-xl">
                <div class="flex items-center">
                    <div class="p-2 bg-white bg-opacity-20 rounded-lg mr-3">
                        <i class="fas fa-scroll"></i>
                    </div>
                    <h3 class="text-sm font-semibold">{{ isCreating ? 'Create Paper Quality' : 'Edit Paper Quality' }}</h3>
                </div>
                <button type="button" @click="closeEditModal" class="text-white hover:text-gray-200">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>
            <div class="p-5">
                <form @submit.prevent="saveChanges" class="space-y-4">
                    <div class="grid grid-cols-1 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Paper Quality:</label>
                            <input v-model="form.paper_quality" type="text" maxlength="10" class="block w-full rounded-md border-gray-300 shadow-sm text-sm"
                                :class="{ 'bg-gray-100': !isCreating }" :readonly="!isCreating" required>
                        </div>
                        <div v-if="!isCreating">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Record Status:</label>
                            <input v-model="form.status" type="text" class="block w-full rounded-md border-gray-300 shadow-sm bg-gray-100 text-sm" readonly>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Paper Name:</label>
                            <input v-model="form.paper_name" type="text" maxlength="50" class="block w-full rounded-md border-gray-300 shadow-sm text-sm" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Weight:</label>
                            <div class="flex items-center space-x-2">
                                <input v-model="form.weight_kg_m" type="number" step="0.0001" min="0" max="9.9999"
                                    class="block w-full rounded-md border-gray-300 shadow-sm text-sm">
                                <span class="text-sm text-gray-600 whitespace-nowrap">Kg/m2 for Weight Calculation</span>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Commercial Code:</label>
                            <div class="flex items-center space-x-2">
                                <input v-model="form.commercial_code" type="text" maxlength="10" class="block w-full rounded-md border-gray-300 shadow-sm text-sm">
                                <span class="text-sm text-gray-600 whitespace-nowrap">For Forms like QT, DO, IV, etc</span>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">CORR Wet-End Code:</label>
                            <div class="flex items-center space-x-2">
                                <input v-model="form.wet_end_code" type="text" maxlength="10" class="block w-full rounded-md border-gray-300 shadow-sm text-sm">
                                <span class="text-sm text-gray-600 whitespace-nowrap">For Roll FIFO Line-Up</span>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">CORR DECC Code:</label>
                            <div class="flex items-center space-x-2">
                                <input v-model="form.decc_code" type="text" maxlength="10" class="block w-full rounded-md border-gray-300 shadow-sm text-sm">
                                <span class="text-sm text-gray-600 whitespace-nowrap">For Linking to CPMS</span>
                            </div>
                        </div>
                    </div>
                    <div class="flex justify-between mt-5 pt-4 border-t border-gray-200">
                        <button type="button" v-if="!isCreating" @click="obsoletePaperQuality" class="px-4 py-2 bg-orange-500 text-white rounded-lg hover:bg-orange-600 text-sm font-medium">
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
import AppLayout from '@/Layouts/AppLayout.vue';
import PaperQualityModal from '@/Components/paper-quality-modal.vue';

// Get the header from props
const props = defineProps({
    header: {
        type: String,
        default: 'Paper Quality Management'
    }
});

const paperQualities = ref([]);
const loading = ref(false);
const saving = ref(false);
const showModal = ref(false);
const showEditModal = ref(false);
const selectedQuality = ref(null);
const searchQuery = ref('');
const isCreating = ref(false);
const notification = ref({ show: false, message: '', type: 'success' });

// Form state
const form = ref({
    paper_quality: '',
    paper_name: '',
    weight_kg_m: '',
    commercial_code: '',
    wet_end_code: '',
    decc_code: '',
    status: 'Active',
    is_active: 1
});

const updateIsActive = () => {
    form.value.is_active = form.value.status === 'Act' ? 1 : 0;
};

// Watch for changes in search query to filter the data
watch(searchQuery, (newQuery) => {
    if (newQuery && paperQualities.value.length > 0) {
        const foundQuality = paperQualities.value.find(quality =>
            quality.paper_quality.toLowerCase().includes(newQuery.toLowerCase()) ||
            quality.paper_name.toLowerCase().includes(newQuery.toLowerCase())
        );

        if (foundQuality) {
            selectedQuality.value = foundQuality;
        }
    }
});

const fetchPaperQualities = async () => {
    loading.value = true;
    try {
        const response = await fetch('/api/paper-qualities');

        if (!response.ok) {
            throw new Error('Network response was not ok');
        }

        const data = await response.json();

        if (Array.isArray(data)) {
            paperQualities.value = data;
        } else {
            paperQualities.value = [];
            console.error('Unexpected data format:', data);
        }
    } catch (e) {
        console.error('Error fetching paper qualities:', e);
        paperQualities.value = [];
    } finally {
        loading.value = false;
    }
};

onMounted(fetchPaperQualities);

const onPaperQualitySelected = (quality) => {
    selectedQuality.value = quality;
    searchQuery.value = quality.paper_quality;
    showModal.value = false;

    // Automatically open the edit modal for the selected row
    isCreating.value = false;
    form.value = { ...quality };
    showEditModal.value = true;
};

const editSelectedQuality = () => {
    if (selectedQuality.value) {
        isCreating.value = false;
        form.value = { ...selectedQuality.value };
        showEditModal.value = true;
    } else {
        showNotification('Please select a paper quality first', 'error');
    }
};

const createNewPaperQuality = () => {
    isCreating.value = true;
    form.value = {
        paper_quality: '',
        paper_name: '',
        weight_kg_m: '',
        commercial_code: '',
        wet_end_code: '',
        decc_code: '',
        status: 'Active',
        is_active: 1
    };
    showEditModal.value = true;
};

const closeEditModal = () => {
    showEditModal.value = false;
};

const saveChanges = async () => {
    // Validate form
    if (!form.value.paper_quality) {
        showNotification('Paper quality code is required', 'error');
        return;
    }

    if (!form.value.paper_name) {
        showNotification('Paper quality name is required', 'error');
        return;
    }

    saving.value = true;
    try {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

        // For new records, set status to Active and is_active to 1
        if (isCreating.value) {
            form.value.status = 'Act';
            form.value.is_active = 1;
        }

        // Different API call for create vs update
        let url = isCreating.value ? '/api/paper-qualities' : `/api/paper-qualities/${selectedQuality.value.id}`;
        let method = isCreating.value ? 'POST' : 'PUT';

        const response = await fetch(url, {
            method: method,
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify(form.value)
        });

        if (!response.ok) {
            const errorData = await response.json();
            throw new Error(errorData.message || 'Error saving paper quality');
        }

        const result = await response.json();

        // Update the local data
        await fetchPaperQualities();

        // Show success notification
        if (isCreating.value) {
            showNotification('Paper quality created successfully', 'success');
            // Find and select the newly created quality
            const newQuality = paperQualities.value.find(q => q.paper_quality === form.value.paper_quality);
            if (newQuality) {
                selectedQuality.value = newQuality;
                searchQuery.value = newQuality.paper_quality;
            }
        } else {
            showNotification('Paper quality updated successfully', 'success');
        }

        // Close the edit modal
        closeEditModal();
    } catch (e) {
        console.error('Error saving paper quality:', e);
        showNotification('Error: ' + e.message, 'error');
    } finally {
        saving.value = false;
    }
};

const obsoletePaperQuality = async () => {
    if (!selectedQuality.value) {
        showNotification('No paper quality selected', 'error');
        return;
    }

    if (!confirm(`Are you sure you want to obsolete paper quality "${selectedQuality.value.paper_quality}"? This will hide it from paper quality selection.`)) {
        return;
    }

    saving.value = true;
    try {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

        const response = await fetch(`/api/paper-qualities/${selectedQuality.value.id}/status`, {
            method: 'PUT',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        });

        const result = await response.json();

        if (!response.ok || !result.success) {
            throw new Error(result.message || 'Error obsoleting paper quality');
        }

        // Remove from local array (since it's now obsolete)
        paperQualities.value = paperQualities.value.filter(q => q.id !== selectedQuality.value.id);

        // Reset selection and form
        const qualityCode = selectedQuality.value.paper_quality;
        selectedQuality.value = null;
        searchQuery.value = '';
        closeEditModal();

        showNotification(`Paper quality "${qualityCode}" has been obsoleted successfully.`, 'success');
    } catch (e) {
        console.error('Error obsoleting paper quality:', e);
        showNotification('Error: ' + e.message, 'error');
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
