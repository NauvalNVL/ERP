<template>
    <AppLayout :header="'Paper Size'">
    <Head title="Paper Size Management" />

    <!-- Header Section -->
    <div class="bg-gradient-to-r from-cyan-700 to-blue-600 p-6 rounded-t-lg shadow-lg">
        <h2 class="text-2xl font-bold text-white mb-2 flex items-center">
            <i class="fas fa-ruler-combined mr-3"></i> Define Paper Size
        </h2>
        <p class="text-cyan-100">Define standard paper sizes for production and document purposes</p>
    </div>

    <div class="bg-white rounded-b-lg shadow-lg p-6 mb-6">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column - Main Content -->
            <div class="lg:col-span-2">
                <div class="bg-white p-6 rounded-lg shadow-md border-t-4 border-blue-500">
                    <div class="flex items-center mb-6 pb-2 border-b border-gray-200">
                        <div class="p-2 bg-blue-500 rounded-lg mr-3">
                            <i class="fas fa-edit text-white"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-800">Paper Size Management</h3>
                    </div>
                    
                    <!-- Header with navigation buttons -->
                    <div class="flex items-center space-x-2 mb-6">
                        <button type="button" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded flex items-center space-x-2">
                            <i class="fas fa-power-off"></i>
                        </button>
                        <button type="button" @click="fetchPaperSizes" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded flex items-center space-x-2">
                            <i class="fas fa-sync"></i>
                        </button>
                        <button type="button" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded flex items-center space-x-2">
                            <i class="fas fa-arrow-left"></i>
                        </button>
                        <button type="button" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded flex items-center space-x-2">
                            <i class="fas fa-arrow-right"></i>
                        </button>
                        <button type="button" @click="showModal = true" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded flex items-center space-x-2">
                            <i class="fas fa-search"></i>
                        </button>
                        <button type="button" @click="savePaperSize" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded flex items-center space-x-2">
                            <i class="fas fa-save"></i>
                        </button>
                        <button type="button" v-if="!isCreating" @click="deletePaperSize" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded flex items-center space-x-2">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </div>

                    <!-- Paper Size Form -->
                    <form @submit.prevent="savePaperSize" class="grid grid-cols-1 gap-6">
                        <div class="grid grid-cols-1 md:grid-cols-5 gap-5">
                            <!-- Size ID Field -->
                            <div class="col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Size ID:</label>
                                <div class="relative flex">
                                    <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500">
                                        <i class="fas fa-hashtag"></i>
                                    </span>
                                    <input 
                                        type="text" 
                                        v-model="sizeDisplay" 
                                        class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none border border-gray-300 focus:ring-blue-500 focus:border-blue-500 bg-gray-100" 
                                        readonly>
                                    <button type="button" @click="showModal = true" class="inline-flex items-center px-3 py-2 border border-l-0 border-gray-300 bg-blue-500 hover:bg-blue-600 text-white rounded-r-md transition-colors">
                                        <i class="fas fa-table"></i>
                                    </button>
                                </div>
                                <span class="text-xs text-gray-700 mt-1 block">Unique identifier for paper size</span>
                            </div>

                            <!-- Size in MM Field -->
                            <div class="col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Paper Size (MM):</label>
                                <div class="relative flex">
                                    <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500">
                                        <i class="fas fa-ruler-combined"></i>
                                    </span>
                                    <input 
                                        type="number" 
                                        v-model="form.size" 
                                        step="0.01" 
                                        min="0.01"
                                        @input="updateInches"
                                        class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none rounded-r-md border border-gray-300 focus:ring-blue-500 focus:border-blue-500 text-right">
                                </div>
                                <span class="text-xs text-gray-700 mt-1 block">Size in Millimeters</span>
                            </div>
                            
                            <!-- Action Button -->
                            <div class="col-span-1">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Action:</label>
                                <button type="button" @click="createNewPaperSize" class="w-full flex items-center justify-center px-4 py-2 bg-indigo-500 hover:bg-indigo-600 text-white rounded transition-colors">
                                    <i class="fas fa-plus mr-2"></i> New
                                </button>
                            </div>
                        </div>

                        <!-- Size in Inches -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Size in Inches:</label>
                            <div class="relative flex">
                                <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500">
                                    <i class="fas fa-ruler"></i>
                                </span>
                                <input 
                                    type="number" 
                                    v-model="form.inches" 
                                    step="0.01" 
                                    min="0.01"
                                    @input="updateMillimeters"
                                    class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none rounded-r-md border border-gray-300 focus:ring-blue-500 focus:border-blue-500 text-right">
                            </div>
                            <span class="text-xs text-gray-700 mt-1 block">Equivalent size in Inches</span>
                        </div>

                        <!-- Description -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Description:</label>
                            <div class="relative flex">
                                <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500">
                                    <i class="fas fa-font"></i>
                                </span>
                                <input 
                                    type="text" 
                                    v-model="form.description" 
                                    placeholder="e.g., A4 Width, Letter Height" 
                                    class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none rounded-r-md border border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <span class="text-xs text-gray-700 mt-1 block">Optional description of this paper size</span>
                        </div>

                        <!-- Conversion Information -->
                        <div class="p-4 mt-2 bg-blue-50 rounded-lg">
                            <div class="flex items-center mb-2">
                                <div class="p-2 bg-blue-500 rounded-lg mr-3">
                                    <i class="fas fa-calculator text-white text-sm"></i>
                                </div>
                                <h4 class="text-md font-semibold text-blue-900">Automatic Conversion</h4>
                            </div>
                            <p class="text-xs text-blue-800 ml-10">
                                1 inch = 25.4 millimeters <br>
                                {{ form.size ? form.size : '0.00' }} mm = {{ form.inches ? form.inches : '0.00' }} inches
                            </p>
                        </div>

                        <!-- Data Status Information -->
                        <div v-if="loading" class="mt-4 bg-yellow-100 p-3 rounded">
                            <div class="flex items-center">
                                <div class="mr-3">
                                    <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-yellow-700"></div>
                                </div>
                                <p class="text-sm font-medium text-yellow-800">Loading paper size data...</p>
                            </div>
                        </div>
                        <div v-else-if="paperSizes.length === 0" class="mt-4 bg-yellow-100 p-3 rounded">
                            <p class="text-sm font-medium text-yellow-800">No paper size data available.</p>
                            <p class="text-xs text-yellow-700 mt-1">Make sure the database is properly configured and seeders have been run.</p>
                            <div class="mt-2 flex items-center space-x-3">
                                <button @click="fetchPaperSizes" class="bg-blue-500 hover:bg-blue-600 text-white text-xs px-3 py-1 rounded">Reload Data</button>
                            </div>
                        </div>
                        <div v-else class="mt-4 bg-green-100 p-3 rounded">
                            <p class="text-sm font-medium text-green-800">Data available: {{ paperSizes.length }} paper sizes found.</p>
                            <p v-if="selectedSize" class="text-xs text-green-700 mt-1">
                                Selected: <span class="font-semibold">{{ sizeDisplay }}</span> - {{ selectedSize.description || 'No description' }}
                            </p>

                            <div class="mt-2 flex flex-wrap items-center gap-2">
                                <span 
                                    v-for="(size, index) in paperSizes.slice(0, 8)" 
                                    :key="index" 
                                    @click="selectSize(size)" 
                                    class="px-2 py-1 text-xs cursor-pointer rounded-full"
                                    :class="selectedSize && selectedSize.id === size.id ? 'bg-blue-600 text-white' : 'bg-blue-100 text-blue-700 hover:bg-blue-200'">
                                    {{ size.size }}mm
                                </span>
                                <span v-if="paperSizes.length > 8" class="px-2 py-1 text-xs bg-gray-100 text-gray-700 rounded-full">
                                    +{{ paperSizes.length - 8 }} more
                                </span>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Right Column - Quick Info -->
            <div class="lg:col-span-1">
                <!-- Paper Size Info Card -->
                <div class="bg-white p-6 rounded-lg shadow-md border-t-4 border-teal-500 mb-6">
                    <div class="flex items-center mb-4 pb-2 border-b border-gray-200">
                        <div class="p-2 bg-teal-500 rounded-lg mr-3">
                            <i class="fas fa-info-circle text-white"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-800">Paper Size Info</h3>
                    </div>

                    <div class="space-y-4">
                        <div class="p-4 bg-teal-50 rounded-lg">
                            <h4 class="text-sm font-semibold text-teal-800 uppercase tracking-wider mb-2">Instructions</h4>
                            <ul class="list-disc pl-5 text-sm text-gray-600 space-y-1">
                                <li>All paper sizes are stored in millimeters</li>
                                <li>Automatic conversion between mm and inches</li>
                                <li>Use the search button to browse all sizes</li>
                                <li>Click New to create a new paper size</li>
                                <li>Adding descriptions helps identify sizes</li>
                            </ul>
                        </div>

                        <div class="p-4 bg-blue-50 rounded-lg">
                            <h4 class="text-sm font-semibold text-blue-800 uppercase tracking-wider mb-2">Standard Sizes</h4>
                            <div class="grid grid-cols-2 gap-2 text-sm">
                                <div class="flex items-center">
                                    <span class="w-6 h-6 flex items-center justify-center bg-blue-500 text-white rounded-full font-bold mr-2">A4</span>
                                    <span>210 x 297 mm</span>
                                </div>
                                <div class="flex items-center">
                                    <span class="w-6 h-6 flex items-center justify-center bg-blue-500 text-white rounded-full font-bold mr-2">A3</span>
                                    <span>297 x 420 mm</span>
                                </div>
                                <div class="flex items-center">
                                    <span class="w-6 h-6 flex items-center justify-center bg-blue-500 text-white rounded-full font-bold mr-2">A5</span>
                                    <span>148 x 210 mm</span>
                                </div>
                                <div class="flex items-center">
                                    <span class="w-6 h-6 flex items-center justify-center bg-blue-500 text-white rounded-full font-bold mr-2">LTR</span>
                                    <span>216 x 279 mm</span>
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
                        <Link href="/paper-size/view-print" class="flex items-center p-3 bg-purple-50 rounded-lg hover:bg-purple-100 transition-colors">
                            <div class="p-2 bg-purple-500 rounded-full mr-3">
                                <i class="fas fa-print text-white text-sm"></i>
                            </div>
                            <div>
                                <p class="font-medium text-purple-900">Print Paper Sizes</p>
                                <p class="text-xs text-purple-700">Print paper size list</p>
                            </div>
                        </Link>

                        <Link href="/vue/paper-quality" class="flex items-center p-3 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                            <div class="p-2 bg-blue-500 rounded-full mr-3">
                                <i class="fas fa-scroll text-white text-sm"></i>
                            </div>
                            <div>
                                <p class="font-medium text-blue-900">Paper Quality</p>
                                <p class="text-xs text-blue-700">Manage paper qualities</p>
                            </div>
                        </Link>

                        <Link href="/vue/paper-flute" class="flex items-center p-3 bg-green-50 rounded-lg hover:bg-green-100 transition-colors">
                            <div class="p-2 bg-green-500 rounded-full mr-3">
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
        :paperSizes="paperSizes"
        @close="showModal = false"
        @select="onPaperSizeSelected"
    />

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
    </AppLayout>
</template>

<script setup>
import { ref, onMounted, watch, computed } from 'vue';
import { Head, Link, usePage, router } from '@inertiajs/vue3';
import PaperSizeModal from '@/Components/paper-size-modal.vue';

// Get the header from props
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
const selectedSize = ref(null);
const isCreating = ref(true);
const notification = ref({ show: false, message: '', type: 'success' });

// Form state
const form = ref({
    id: null,
    size: '',
    inches: '',
    description: '',
    unit: 'Millimeter'
});

// Computed property for Size ID display
const sizeDisplay = computed(() => {
    if (selectedSize.value && selectedSize.value.id) {
        return 'PS' + String(selectedSize.value.id).padStart(3, '0');
    }
    return 'New';
});

// Select a paper size directly from the chips
const selectSize = (size) => {
    selectedSize.value = size;
    isCreating.value = false;
    
    form.value = {
        id: size.id,
        size: size.size,
        inches: size.inches || (size.size / 25.4).toFixed(2),
        description: size.description || '',
        unit: size.unit || 'Millimeter'
    };
};

// Fetch paper sizes from API
const fetchPaperSizes = async () => {
    loading.value = true;
    try {
        const res = await fetch('/paper-size', { 
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
            paperSizes.value = data.map(size => ({
                id: size.id,
                size: size.size,
                inches: size.inches || (size.size / 25.4).toFixed(2),
                description: size.description || '',
                unit: size.unit || 'Millimeter'
            }));
            
            console.log('Loaded paper sizes:', paperSizes.value);
            
            if (paperSizes.value.length > 0 && !selectedSize.value) {
                // Auto-select the first paper size if none is selected
                selectedSize.value = paperSizes.value[0];
                form.value = {
                    id: selectedSize.value.id,
                    size: selectedSize.value.size,
                    inches: selectedSize.value.inches,
                    description: selectedSize.value.description || '',
                    unit: selectedSize.value.unit || 'Millimeter'
                };
                isCreating.value = false;
            }
        } else {
            paperSizes.value = [];
            console.error('Unexpected data format:', data);
        }
    } catch (e) {
        console.error('Error fetching paper sizes:', e);
        paperSizes.value = [];
        showNotification('Failed to load paper sizes: ' + e.message, 'error');
    } finally {
        loading.value = false;
    }
};

onMounted(() => {
    // Jika data sudah ada dari props, gunakan itu
    if (props.initialPaperSizes && props.initialPaperSizes.length > 0) {
        console.log('Using initial paper sizes from props:', props.initialPaperSizes);
        
        // Jika belum ada yang dipilih, pilih yang pertama secara otomatis
        if (!selectedSize.value && paperSizes.value.length > 0) {
            selectedSize.value = paperSizes.value[0];
            form.value = {
                id: selectedSize.value.id,
                size: selectedSize.value.size,
                inches: selectedSize.value.inches,
                description: selectedSize.value.description || '',
                unit: selectedSize.value.unit || 'Millimeter'
            };
            isCreating.value = false;
        }
    } else {
        // Jika tidak ada data dari props, fetch dari API
        fetchPaperSizes();
    }
});

// Update inches when mm changes
const updateInches = () => {
    if (form.value.size && !isNaN(form.value.size)) {
        form.value.inches = (parseFloat(form.value.size) / 25.4).toFixed(2);
    } else {
        form.value.inches = '';
    }
};

// Update mm when inches changes
const updateMillimeters = () => {
    if (form.value.inches && !isNaN(form.value.inches)) {
        form.value.size = (parseFloat(form.value.inches) * 25.4).toFixed(2);
    } else {
        form.value.size = '';
    }
};

// Handle paper size selection from modal
const onPaperSizeSelected = (size) => {
    selectedSize.value = size;
    isCreating.value = false;
    
    // Fill the form with the selected paper size data
    form.value = {
        id: size.id,
        size: size.size,
        inches: size.inches,
        description: size.description || '',
        unit: 'Millimeter'
    };
};

// Create new paper size
const createNewPaperSize = () => {
    selectedSize.value = null;
    isCreating.value = true;
    
    // Reset form to default values
    form.value = {
        id: null,
        size: '',
        inches: '',
        description: '',
        unit: 'Millimeter'
    };
};

// Save paper size changes
const savePaperSize = async () => {
    // Validate form
    if (!form.value.size || parseFloat(form.value.size) <= 0) {
        showNotification('Please enter a valid size', 'error');
        return;
    }

    saving.value = true;
    try {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

        // Different API call for create vs update
        let url = isCreating.value ? '/paper-size' : `/paper-size/${form.value.id}`;
        let method = isCreating.value ? 'POST' : 'PUT';
        
        const response = await fetch(url, {
            method: method,
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                size: form.value.size,
                description: form.value.description
            })
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
            const newPaperSize = paperSizes.value.find(s => parseFloat(s.size) === parseFloat(form.value.size));
            if (newPaperSize) {
                selectedSize.value = newPaperSize;
                isCreating.value = false;
                form.value.id = newPaperSize.id;
            }
        } else {
            showNotification('Paper size updated successfully', 'success');
        }
    } catch (e) {
        console.error('Error saving paper size:', e);
        showNotification('Error: ' + e.message, 'error');
    } finally {
        saving.value = false;
    }
};

// Delete paper size
const deletePaperSize = async () => {
    if (!selectedSize.value) {
        showNotification('No paper size selected', 'error');
        return;
    }
    
    if (!confirm(`Are you sure you want to delete paper size "${selectedSize.value.size}mm"?`)) {
        return;
    }
    
    saving.value = true;
    try {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
        
        const response = await fetch(`/paper-size/${selectedSize.value.id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        });
        
        if (!response.ok) {
            const errorData = await response.json();
            throw new Error(errorData.message || 'Error deleting paper size');
        }
        
        // Remove from local array
        paperSizes.value = paperSizes.value.filter(s => s.id !== selectedSize.value.id);
        
        // Reset selection and form
        selectedSize.value = null;
        createNewPaperSize();
        
        showNotification('Paper size deleted successfully', 'success');
    } catch (e) {
        console.error('Error deleting paper size:', e);
        showNotification('Error: ' + e.message, 'error');
    } finally {
        saving.value = false;
    }
};

// Show notification
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
