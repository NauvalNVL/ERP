<template>
    <AppLayout :header="'Paper Size'">
    <Head title="Paper Size Management" />

    <!-- Header Section -->
    <div class="min-h-screen bg-gray-50 py-6 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <div class="bg-emerald-600 text-white shadow-sm rounded-xl border border-emerald-700 mb-4">
                <div class="px-4 py-3 sm:px-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div class="flex items-center gap-3">
                        <div class="h-9 w-9 rounded-full bg-emerald-500 flex items-center justify-center">
                            <i class="fas fa-ruler-combined text-white text-sm"></i>
                        </div>
                        <div>
                            <h2 class="text-lg sm:text-xl font-semibold leading-tight">
                                Define Paper Size
                            </h2>
                            <p class="text-xs sm:text-sm text-emerald-100">
                                Define standard paper sizes for production and document purposes.
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 text-xs text-emerald-100">
                        <i class="fas fa-info-circle text-sm"></i>
                        <span>Use consistent paper sizes for production and printing documents.</span>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8">
                <!-- Left Column - Main Content -->
                <div class="lg:col-span-2 space-y-4">
                    <div class="bg-white shadow-sm rounded-xl border border-gray-200">
                        <div class="px-4 py-3 sm:px-6 border-b border-gray-100 flex items-center">
                            <div class="p-2 bg-emerald-500 rounded-lg mr-3 text-white">
                                <i class="fas fa-edit"></i>
                            </div>
                            <div>
                                <h3 class="text-sm sm:text-base font-semibold text-slate-800">Paper Size Management</h3>
                                <p class="text-xs text-slate-500">Search, create, and maintain your paper sizes.</p>
                            </div>
                        </div>
                        <div class="px-4 py-4 sm:px-6">
                            <!-- Search Section -->
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                                <div class="col-span-2">
                                    <label class="block text-sm font-semibold text-slate-700 mb-1">Paper Size Code</label>
                                    <div class="relative flex">
                                        <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-200 bg-slate-50 text-slate-500">
                                            <i class="fas fa-ruler-combined"></i>
                                        </span>
                                        <input
                                            type="text"
                                            v-model="searchQuery"
                                            class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none border border-gray-200 focus:ring-emerald-500 focus:border-emerald-500 text-slate-800 placeholder-slate-400 text-sm transition-colors"
                                            placeholder="Search or type paper size code"
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
                                        @click="createNewPaperSize"
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
                                    <p class="font-medium text-amber-800">Loading paper size data...</p>
                                </div>
                            </div>
                            <div v-else-if="paperSizes.length === 0" class="mt-3 bg-amber-50 border border-amber-200 p-3 rounded-lg">
                                <p class="text-sm font-semibold text-amber-800">No paper size data available.</p>
                                <p class="text-xs text-amber-700 mt-1">Make sure the database is properly configured and seeders have been run.</p>
                                <div class="mt-2 flex items-center space-x-3">
                                    <button @click="fetchPaperSizes" class="bg-emerald-500 hover:bg-emerald-600 text-white text-xs px-3 py-1 rounded-lg">Reload Data</button>
                                </div>
                            </div>
                            <div v-else class="mt-3 bg-emerald-50 border border-emerald-200 p-3 rounded-lg">
                                <p class="text-sm font-semibold text-emerald-800">Data available: {{ paperSizes.length }} paper sizes found.</p>
                                <p v-if="selectedSize" class="text-xs text-emerald-700 mt-1">
                                    Selected: <span class="font-semibold">{{ selectedSize.millimeter }} mm</span> = {{ selectedSize.inches }} inches
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column - Quick Info -->
                <div class="lg:col-span-1 space-y-4">
                    <!-- Paper Size Info Card -->
                    <div class="bg-white shadow-sm rounded-xl border border-emerald-100 mb-2">
                        <div class="px-4 py-3 sm:px-5 border-b border-emerald-100 flex items-center">
                            <div class="p-2 bg-emerald-500 rounded-lg mr-3">
                                <i class="fas fa-info-circle text-white"></i>
                            </div>
                            <h3 class="text-sm sm:text-base font-semibold text-emerald-900">Paper Size Info</h3>
                        </div>

                        <div class="px-4 py-4 sm:px-5">
                            <div class="space-y-4">
                                <div class="p-4 bg-emerald-50 rounded-lg border border-emerald-100">
                                    <h4 class="text-xs font-semibold text-emerald-700 uppercase tracking-wider mb-2">Instructions</h4>
                                    <ul class="list-disc pl-5 text-xs sm:text-sm text-slate-600 space-y-1">
                                        <li>Paper size values must be unique</li>
                                        <li>Size can be entered in MM or inches</li>
                                        <li>Values are automatically converted</li>
                                        <li>Add clear descriptions for commonly used sizes</li>
                                    </ul>
                                </div>

                                <div class="p-4 bg-sky-50 rounded-lg border border-sky-100">
                                    <h4 class="text-xs font-semibold text-sky-800 uppercase tracking-wider mb-2">Common Sizes</h4>
                                    <div class="grid grid-cols-2 gap-2 text-xs sm:text-sm">
                                        <div class="flex items-center">
                                            <span class="w-6 h-6 flex items-center justify-center bg-emerald-500 text-white rounded-full font-bold mr-2">A4</span>
                                            <span>210 × 297 mm</span>
                                        </div>
                                        <div class="flex items-center">
                                            <span class="w-6 h-6 flex items-center justify-center bg-emerald-500 text-white rounded-full font-bold mr-2">A3</span>
                                            <span>297 × 420 mm</span>
                                        </div>
                                        <div class="flex items-center">
                                            <span class="w-6 h-6 flex items-center justify-center bg-emerald-500 text-white rounded-full font-bold mr-2">LTR</span>
                                            <span>216 × 279 mm</span>
                                        </div>
                                        <div class="flex items-center">
                                            <span class="w-6 h-6 flex items-center justify-center bg-emerald-500 text-white rounded-full font-bold mr-2">B5</span>
                                            <span>176 × 250 mm</span>
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

    <!-- Modal for Paper Size Table -->
    <PaperSizeModal
        :show="showModal"
        :paper-sizes="paperSizes"
        @close="showModal = false"
        @select="onPaperSizeSelected"
    />

    <!-- Edit Modal -->
    <div v-if="showEditModal" class="fixed inset-0 z-50 bg-black bg-opacity-30 flex items-center justify-center">
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 w-11/12 md:w-2/5 max-w-md mx-auto">
            <div class="flex items-center justify-between px-4 py-3 border-b border-gray-200 bg-emerald-600 text-white rounded-t-xl">
                <div class="flex items-center">
                    <div class="p-2 bg-white bg-opacity-20 rounded-lg mr-3">
                        <i class="fas fa-ruler-combined"></i>
                    </div>
                    <h3 class="text-sm font-semibold">{{ isCreating ? 'Create Paper Size' : 'Edit Paper Size' }}</h3>
                </div>
                <button type="button" @click="closeEditModal" class="text-white hover:text-gray-200">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>
            <div class="p-5">
                <form @submit.prevent="savePaperSize" class="space-y-4">
                    <div class="grid grid-cols-1 gap-4">
                        <div v-if="!isCreating">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Size ID:</label>
                            <input type="text" v-model="sizeDisplay" class="block w-full rounded-md border-gray-300 shadow-sm bg-gray-100 text-sm" readonly>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Size in Millimeters (MM):</label>
                            <input
                                type="number"
                                v-model="form.millimeter"
                                step="0.01"
                                min="0.01"
                                @input="updateInches"
                                class="block w-full rounded-md border-gray-300 shadow-sm text-sm"
                                required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Size in Inches:</label>
                            <input
                                type="number"
                                v-model="form.inches"
                                step="0.01"
                                min="0.01"
                                @input="updateMillimeters"
                                class="block w-full rounded-md border-gray-300 shadow-sm text-sm"
                                required>
                        </div>
                        <div class="p-4 mt-2 bg-emerald-50 rounded-lg border border-emerald-100">
                            <div class="flex items-center mb-2">
                                <div class="p-2 bg-emerald-500 rounded-lg mr-3">
                                    <i class="fas fa-calculator text-white text-sm"></i>
                                </div>
                                <h4 class="text-sm font-semibold text-emerald-900">Automatic Conversion</h4>
                            </div>
                            <p class="text-xs text-emerald-800 ml-10">
                                1 inch = 25.4 millimeters <br>
                                {{ form.millimeter ? form.millimeter : '0.00' }} mm = {{ form.inches ? form.inches : '0.00' }} inches
                            </p>
                        </div>
                    </div>
                    <div class="flex justify-between mt-5 pt-4 border-t border-gray-200">
                        <button type="button" v-if="!isCreating" @click="deletePaperSize" class="px-4 py-2 bg-orange-500 text-white rounded-lg hover:bg-orange-600 text-sm font-medium">
                            <i class="fas fa-archive mr-2"></i>Mark as Obsolete
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
import { ref, onMounted, watch, computed } from 'vue';
import { Head, Link, usePage } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import PaperSizeModal from '@/Components/paper-size-modal.vue';

// Get any props passed from the controller
const props = defineProps({
    header: {
        type: String,
        default: 'Paper Size Management'
    },
    initialPaperSizes: {
        type: Array,
        default: () => []
    }
});

const paperSizes = ref(props.initialPaperSizes || []);
const loading = ref(false);
const saving = ref(false);
const showModal = ref(false);
const showEditModal = ref(false);
const selectedSize = ref(null);
const searchQuery = ref('');
const isCreating = ref(false);
const notification = ref({ show: false, message: '', type: 'success' });

// Form state
const form = ref({
    id: null,
    millimeter: '',
    inches: ''
});

// Display for size ID
const sizeDisplay = computed(() => {
    if (!selectedSize.value) return '';
    return `PS${String(selectedSize.value.id).padStart(3, '0')}`;
});

// Convert MM to Inches (1 inch = 25.4 mm)
const updateInches = () => {
    if (form.value.millimeter) {
        form.value.inches = (parseFloat(form.value.millimeter) / 25.4).toFixed(2);
    }
};

// Convert Inches to MM
const updateMillimeters = () => {
    if (form.value.inches) {
        form.value.millimeter = (parseFloat(form.value.inches) * 25.4).toFixed(2);
    }
};

// Watch for changes in search query to help user find a size
watch(searchQuery, (newQuery) => {
    // If user clears the field, also clear current selection
    if (!newQuery) {
        selectedSize.value = null;
        return;
    }

    if (paperSizes.value.length > 0) {
        const foundSize = paperSizes.value.find(size =>
            String(size.id).includes(newQuery) ||
            String(size.millimeter).includes(newQuery)
        );

        if (foundSize) {
            // Only update selectedSize so input value remains under user control
            selectedSize.value = foundSize;
        }
    }
});

const fetchPaperSizes = async () => {
    loading.value = true;
    try {
        const response = await fetch('/api/paper-sizes');

        if (!response.ok) {
            throw new Error('Network response was not ok');
        }

        const data = await response.json();

        if (Array.isArray(data)) {
            paperSizes.value = data;
        } else {
            paperSizes.value = [];
            console.error('Unexpected data format:', data);
        }
    } catch (e) {
        console.error('Error fetching paper sizes:', e);
        paperSizes.value = [];
    } finally {
        loading.value = false;
    }
};

onMounted(() => {
    if (paperSizes.value.length === 0) {
        fetchPaperSizes();
    }
});

const onPaperSizeSelected = (size) => {
    selectSize(size);
    showModal.value = false;

    // Automatically open the edit modal for the selected row
    editSelectedSize();
};

const selectSize = (size) => {
    selectedSize.value = size;
    searchQuery.value = size.millimeter;
};

const editSelectedSize = () => {
    if (selectedSize.value) {
    isCreating.value = false;
    form.value = {
            id: selectedSize.value.id,
            millimeter: selectedSize.value.millimeter,
            inches: selectedSize.value.inches
    };
        showEditModal.value = true;
    } else {
        showNotification('Please select a paper size first', 'error');
    }
};

const createNewPaperSize = () => {
    isCreating.value = true;
    form.value = {
        id: null,
        millimeter: '',
        inches: ''
    };
    showEditModal.value = true;
};

const closeEditModal = () => {
    showEditModal.value = false;
};

const savePaperSize = async () => {
    // Validate form
    if (!form.value.millimeter) {
        showNotification('Paper size in millimeters is required', 'error');
        return;
    }

    if (!form.value.inches) {
        showNotification('Paper size in inches is required', 'error');
        return;
    }

    saving.value = true;
    try {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

        // Different API call for create vs update
        let url = isCreating.value ? '/api/paper-sizes' : `/api/paper-sizes/${form.value.id}`;
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
            throw new Error(errorData.message || 'Error saving paper size');
        }

        const result = await response.json();

        // Update the local data
        await fetchPaperSizes();

        // Show success notification
        if (isCreating.value) {
            showNotification('Paper size created successfully', 'success');
            // Find and select the newly created size
            if (result.id) {
                const newSize = paperSizes.value.find(s => s.id === result.id);
                if (newSize) {
                    selectSize(newSize);
                }
            }
        } else {
            showNotification('Paper size updated successfully', 'success');
        }

        // Close the edit modal
        closeEditModal();
    } catch (e) {
        console.error('Error saving paper size:', e);
        showNotification('Error: ' + e.message, 'error');
    } finally {
        saving.value = false;
    }
};

const deletePaperSize = async () => {
    if (!selectedSize.value) {
        showNotification('No paper size selected', 'error');
        return;
    }

    if (!confirm(`Are you sure you want to delete paper size "${sizeDisplay.value}"?`)) {
        return;
    }

    saving.value = true;
    try {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

        const response = await fetch(`/api/paper-sizes/${selectedSize.value.id}/status`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
            },
            body: JSON.stringify({ status: 'Obs' }),
        });

        if (!response.ok) {
            const errorData = await response.json();
            throw new Error(errorData.message || 'Error updating paper size status');
        }

        // Refresh data so status is up to date
        await fetchPaperSizes();

        const updated = paperSizes.value.find(s => s.id === selectedSize.value.id);
        if (updated) {
            selectedSize.value = updated;
        } else {
            selectedSize.value = null;
            searchQuery.value = '';
        }

        closeEditModal();
        showNotification('Paper size marked as obsolete successfully', 'success');
    } catch (e) {
        console.error('Error updating paper size status:', e);
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
