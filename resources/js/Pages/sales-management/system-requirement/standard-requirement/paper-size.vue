<template>
    <AppLayout :header="'Paper Size'">
    <Head title="Paper Size Management" />

    <!-- Header Section -->
    <div class="bg-gradient-to-r from-green-600 to-green-700 p-6 rounded-t-lg shadow-lg">
        <h2 class="text-2xl font-bold text-white mb-2 flex items-center">
            <i class="fas fa-ruler-combined mr-3"></i> Define Paper Size
        </h2>
        <p class="text-emerald-100">Define standard paper sizes for production and document purposes</p>
    </div>

    <div class="bg-white rounded-b-lg shadow-lg p-6 mb-6">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column - Main Content -->
            <div class="lg:col-span-2">
                <div class="bg-white p-6 rounded-lg shadow-md border-t-4 border-emerald-500">
                    <div class="flex items-center mb-6 pb-2 border-b border-gray-200">
                        <div class="p-2 bg-emerald-500 rounded-lg mr-3">
                            <i class="fas fa-edit text-white"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-800">Paper Size Management</h3>
                    </div>

                    <!-- Search Section -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-6">
                            <div class="col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Paper Size Code:</label>
                                <div class="relative flex">
                                    <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500">
                                        <i class="fas fa-ruler-combined"></i>
                                    </span>
                                <input type="text" v-model="searchQuery"
                                    class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none border border-gray-300 focus:ring-emerald-500 focus:border-emerald-500">
                                <button type="button" @click="showModal = true" class="inline-flex items-center px-3 py-2 border border-l-0 border-emerald-500 bg-gradient-to-r from-emerald-500 to-green-600 hover:from-emerald-600 hover:to-green-700 text-white rounded-r-md transition-colors transform active:translate-y-px">
                                    <i class="fas fa-table"></i>
                                </button>
                            </div>
                        </div>

                        <div class="col-span-1">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Action:</label>
                            <button type="button" @click="createNewPaperSize" class="w-full flex items-center justify-center px-4 py-2 bg-gradient-to-r from-emerald-500 to-green-600 hover:from-emerald-600 hover:to-green-700 text-white rounded transition-colors transform active:translate-y-px">
                                <i class="fas fa-plus-circle mr-2"></i> Add New
                            </button>
                        </div>
                        </div>

                        <!-- Data Status Information -->
                    <div v-if="loading" class="mt-4 bg-yellow-100 p-3 rounded">
                            <div class="flex items-center">
                            <div class="mr-3 animate-spin rounded-full h-6 w-6 border-b-2 border-yellow-700"></div>
                                <p class="text-sm font-medium text-yellow-800">Loading paper size data...</p>
                            </div>
                        </div>
                    <div v-else-if="paperSizes.length === 0" class="mt-4 bg-yellow-100 p-3 rounded">
                            <p class="text-sm font-medium text-yellow-800">No paper size data available.</p>
                            <p class="text-xs text-yellow-700 mt-1">Make sure the database is properly configured and seeders have been run.</p>
                            <div class="mt-2 flex items-center space-x-3">
                                <button @click="fetchPaperSizes" class="bg-emerald-500 hover:bg-emerald-600 text-white text-xs px-3 py-1 rounded">Reload Data</button>
                            </div>
                        </div>
                    <div v-else class="mt-4 bg-green-100 p-3 rounded">
                            <p class="text-sm font-medium text-green-800">Data available: {{ paperSizes.length }} paper sizes found.</p>
                            <p v-if="selectedSize" class="text-xs text-green-700 mt-1">
                                Selected: <span class="font-semibold">{{ selectedSize.millimeter }} mm</span> = {{ selectedSize.inches }} inches
                            </p>
                        </div>
                </div>
            </div>

            <!-- Right Column - Quick Info -->
            <div class="lg:col-span-1">
                <!-- Paper Size Info Card -->
                <div class="bg-white p-6 rounded-lg shadow-md border-t-4 border-emerald-500 mb-6">
                    <div class="flex items-center mb-4 pb-2 border-b border-gray-200">
                        <div class="p-2 bg-emerald-500 rounded-lg mr-3">
                            <i class="fas fa-info-circle text-white"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-800">Paper Size Info</h3>
                    </div>

                    <div class="space-y-4">
                        <div class="p-4 bg-emerald-50 rounded-lg">
                            <h4 class="text-sm font-semibold text-emerald-800 uppercase tracking-wider mb-2">Instructions</h4>
                            <ul class="list-disc pl-5 text-sm text-gray-600 space-y-1">
                                <li>Paper size values must be unique</li>
                                <li>Size can be entered in MM or inches</li>
                                <li>Values are automatically converted</li>
                                <li>Add clear descriptions for commonly used sizes</li>
                            </ul>
                        </div>

                        <div class="p-4 bg-emerald-50 rounded-lg">
                            <h4 class="text-sm font-semibold text-emerald-800 uppercase tracking-wider mb-2">Common Sizes</h4>
                            <div class="grid grid-cols-2 gap-2 text-sm">
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

                <!-- Quick Links -->
                <div class="bg-white p-6 rounded-lg shadow-md border-t-4 border-emerald-500">
                    <div class="flex items-center mb-4 pb-2 border-b border-gray-200">
                        <div class="p-2 bg-emerald-500 rounded-lg mr-3">
                            <i class="fas fa-link text-white"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-800">Quick Links</h3>
                    </div>

                    <div class="grid grid-cols-1 gap-3">
                        <Link href="/paper-size/view-print" class="flex items-center p-3 bg-emerald-50 rounded-lg hover:bg-emerald-100 transition-colors">
                            <div class="p-2 bg-emerald-500 rounded-full mr-3">
                                <i class="fas fa-print text-white text-sm"></i>
                            </div>
                            <div>
                                <p class="font-medium text-emerald-900">Print Paper Sizes</p>
                                <p class="text-xs text-emerald-700">Print paper size list</p>
                            </div>
                        </Link>

                        <Link href="/paper-quality" class="flex items-center p-3 bg-emerald-50 rounded-lg hover:bg-emerald-100 transition-colors">
                            <div class="p-2 bg-emerald-500 rounded-full mr-3">
                                <i class="fas fa-scroll text-white text-sm"></i>
                            </div>
                            <div>
                                <p class="font-medium text-emerald-900">Paper Quality</p>
                                <p class="text-xs text-emerald-700">Manage paper qualities</p>
                            </div>
                        </Link>

                        <Link href="/paper-flute" class="flex items-center p-3 bg-green-50 rounded-lg hover:bg-green-100 transition-colors">
                            <div class="p-2 bg-green-600 rounded-full mr-3">
                                <i class="fas fa-layer-group text-white text-sm"></i>
                            </div>
                            <div>
                                <p class="font-medium text-green-900">Paper Flute</p>
                                <p class="text-xs text-green-700">Manage paper flutes</p>
                            </div>
                        </Link>
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
    <div v-if="showEditModal" class="fixed inset-0 z-50 bg-black bg-opacity-50 flex items-center justify-center">
        <div class="bg-white rounded-lg shadow-xl w-11/12 md:w-2/5 max-w-md mx-auto">
            <div class="flex items-center justify-between p-4 border-b border-gray-200 bg-gradient-to-r from-green-600 to-green-700 text-white rounded-t-lg">
                <div class="flex items-center">
                    <div class="p-2 bg-white bg-opacity-30 rounded-lg mr-3">
                        <i class="fas fa-ruler-combined"></i>
                    </div>
                    <h3 class="text-xl font-semibold">{{ isCreating ? 'Create Paper Size' : 'Edit Paper Size' }}</h3>
                </div>
                <button type="button" @click="closeEditModal" class="text-white hover:text-gray-200">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div class="p-6">
                <form @submit.prevent="savePaperSize" class="space-y-4">
                    <div class="grid grid-cols-1 gap-4">
                        <div v-if="!isCreating">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Size ID:</label>
                            <input type="text" v-model="sizeDisplay" class="block w-full rounded-md border-gray-300 shadow-sm bg-gray-100" readonly>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Size in Millimeters (MM):</label>
                            <input
                                type="number"
                                v-model="form.millimeter"
                                step="0.01"
                                min="0.01"
                                @input="updateInches"
                                class="block w-full rounded-md border-gray-300 shadow-sm"
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
                                class="block w-full rounded-md border-gray-300 shadow-sm"
                                required>
                        </div>
                        <div class="p-4 mt-2 bg-emerald-50 rounded-lg">
                            <div class="flex items-center mb-2">
                                <div class="p-2 bg-emerald-500 rounded-lg mr-3">
                                    <i class="fas fa-calculator text-white text-sm"></i>
                                </div>
                                <h4 class="text-md font-semibold text-emerald-900">Automatic Conversion</h4>
                            </div>
                            <p class="text-xs text-emerald-800 ml-10">
                                1 inch = 25.4 millimeters <br>
                                {{ form.millimeter ? form.millimeter : '0.00' }} mm = {{ form.inches ? form.inches : '0.00' }} inches
                            </p>
                        </div>
                    </div>
                    <div class="flex justify-between mt-6 pt-4 border-t border-gray-200">
                        <button type="button" v-if="!isCreating" @click="deletePaperSize" class="px-4 py-2 bg-orange-500 text-white rounded-lg hover:bg-orange-600">
                            <i class="fas fa-archive mr-2"></i>Mark as Obsolete
                        </button>
                        <div v-else class="w-24"></div>
                        <div class="flex space-x-3">
                            <button type="button" @click="closeEditModal" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300">Cancel</button>
                            <button type="submit" class="px-4 py-2 bg-gradient-to-r from-emerald-500 to-green-600 hover:from-emerald-600 hover:to-green-700 text-white rounded-lg">Save</button>
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

// Watch for changes in search query to filter the data
watch(searchQuery, (newQuery) => {
    if (newQuery && paperSizes.value.length > 0) {
        const foundSize = paperSizes.value.find(size =>
            String(size.id).includes(newQuery) ||
            String(size.millimeter).includes(newQuery)
        );

        if (foundSize) {
            selectSize(foundSize);
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
