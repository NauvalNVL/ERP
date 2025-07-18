<template>
    <AppLayout :header="'Paper Quality'">
    <Head title="Paper Quality Management" />

    <!-- Header Section -->
    <div class="bg-gradient-to-r from-cyan-700 to-blue-600 p-6 rounded-t-lg shadow-lg">
        <h2 class="text-2xl font-bold text-white mb-2 flex items-center">
            <i class="fas fa-scroll mr-3"></i> Define Paper Quality
        </h2>
        <p class="text-cyan-100">Define paper quality types for various production needs</p>
    </div>

    <div class="bg-white rounded-b-lg shadow-lg p-6 mb-6">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column - Main Content -->
            <div class="lg:col-span-2">
                <div class="bg-white p-6 rounded-lg shadow-md border-t-4 border-blue-500">
                    <div class="flex items-center mb-6 pb-2 border-b border-gray-200">
                        <div class="p-2 bg-blue-500 rounded-lg mr-3">
                            <i class="fas fa-scroll text-white"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-800">Paper Quality Management</h3>
                    </div>

                    <!-- Search Section -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-6">
                        <div class="col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Paper Quality Code:</label>
                            <div class="relative flex">
                                <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500">
                                    <i class="fas fa-hashtag"></i>
                                </span>
                                <input type="text" v-model="searchQuery" 
                                    class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none border border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                                <button type="button" @click="showModal = true" class="inline-flex items-center px-3 py-2 border border-l-0 border-gray-300 bg-blue-500 hover:bg-blue-600 text-white rounded-r-md transition-colors transform active:translate-y-px">
                                    <i class="fas fa-table"></i>
                                </button>
                            </div>
                        </div>
                        
                        <div class="col-span-1">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Action:</label>
                            <button type="button" @click="createNewPaperQuality" class="w-full flex items-center justify-center px-4 py-2 bg-indigo-500 hover:bg-indigo-600 text-white rounded transition-colors transform active:translate-y-px">
                                <i class="fas fa-plus-circle mr-2"></i> Add New
                            </button>
                        </div>
                    </div>

                    <!-- Data Status Information -->
                    <div v-if="loading" class="mt-6 bg-yellow-100 p-3 rounded">
                        <div class="flex items-center">
                            <div class="mr-3 animate-spin rounded-full h-6 w-6 border-b-2 border-yellow-700"></div>
                            <p class="text-sm font-medium text-yellow-800">Loading paper quality data...</p>
                        </div>
                    </div>
                    <div v-else-if="paperQualities.length === 0" class="mt-6 bg-yellow-100 p-3 rounded">
                        <p class="text-sm font-medium text-yellow-800">No paper quality data available.</p>
                        <p class="text-xs text-yellow-700 mt-1">Make sure the database is properly configured and seeders have been run.</p>
                        <div class="mt-2 flex items-center space-x-3">
                            <button @click="fetchPaperQualities" class="bg-blue-500 hover:bg-blue-600 text-white text-xs px-3 py-1 rounded">Reload Data</button>
                        </div>
                    </div>
                    <div v-else class="mt-6 bg-green-100 p-3 rounded">
                        <p class="text-sm font-medium text-green-800">Data available: {{ paperQualities.length }} paper qualities found.</p>
                        <p v-if="selectedQuality" class="text-xs text-green-700 mt-1">
                            Selected: <span class="font-semibold">{{ selectedQuality.paper_quality }}</span> - {{ selectedQuality.paper_name }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Right Column - Quick Info -->
            <div class="lg:col-span-1">
                <!-- Paper Quality Info Card -->
                <div class="bg-white p-6 rounded-lg shadow-md border-t-4 border-teal-500 mb-6">
                    <div class="flex items-center mb-4 pb-2 border-b border-gray-200">
                        <div class="p-2 bg-teal-500 rounded-lg mr-3">
                            <i class="fas fa-info-circle text-white"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-800">Paper Quality Info</h3>
                    </div>

                    <div class="space-y-4">
                        <div class="p-4 bg-teal-50 rounded-lg">
                            <h4 class="text-sm font-semibold text-teal-800 uppercase tracking-wider mb-2">Instructions</h4>
                            <ul class="list-disc pl-5 text-sm text-gray-600 space-y-1">
                                <li>Paper quality code must be unique</li>
                                <li>GSM is the gramature measurement (g/m²)</li>
                                <li>Use inactive status for paper types no longer in use</li>
                                <li>Any changes must be saved</li>
                            </ul>
                        </div>

                        <div class="p-4 bg-blue-50 rounded-lg">
                            <h4 class="text-sm font-semibold text-blue-800 uppercase tracking-wider mb-2">Paper Types</h4>
                            <div class="grid grid-cols-2 gap-2 text-sm">
                                <div class="flex items-center">
                                    <span class="w-6 h-6 flex items-center justify-center bg-blue-500 text-white rounded-full font-bold mr-2">L</span>
                                    <span>Liner</span>
                                </div>
                                <div class="flex items-center">
                                    <span class="w-6 h-6 flex items-center justify-center bg-blue-500 text-white rounded-full font-bold mr-2">M</span>
                                    <span>Medium</span>
                                </div>
                                <div class="flex items-center">
                                    <span class="w-6 h-6 flex items-center justify-center bg-blue-500 text-white rounded-full font-bold mr-2">K</span>
                                    <span>Kraft</span>
                                </div>
                                <div class="flex items-center">
                                    <span class="w-6 h-6 flex items-center justify-center bg-blue-500 text-white rounded-full font-bold mr-2">C</span>
                                    <span>Chipboard</span>
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
                        <Link href="/paper-quality/view-print" class="flex items-center p-3 bg-purple-50 rounded-lg hover:bg-purple-100 transition-colors">
                            <div class="p-2 bg-purple-500 rounded-full mr-3">
                                <i class="fas fa-print text-white text-sm"></i>
                            </div>
                            <div>
                                <p class="font-medium text-purple-900">Print Paper Quality</p>
                                <p class="text-xs text-purple-700">Print paper quality list</p>
                            </div>
                        </Link>

                        <Link href="/paper-flute" class="flex items-center p-3 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                            <div class="p-2 bg-blue-500 rounded-full mr-3">
                                <i class="fas fa-layer-group text-white text-sm"></i>
                            </div>
                            <div>
                                <p class="font-medium text-blue-900">Paper Flute</p>
                                <p class="text-xs text-blue-700">Manage paper flutes</p>
                            </div>
                        </Link>

                        <Link href="/paper-size" class="flex items-center p-3 bg-green-50 rounded-lg hover:bg-green-100 transition-colors">
                            <div class="p-2 bg-green-500 rounded-full mr-3">
                                <i class="fas fa-ruler-combined text-white text-sm"></i>
                            </div>
                            <div>
                                <p class="font-medium text-green-900">Paper Size</p>
                                <p class="text-xs text-green-700">Manage paper sizes</p>
                            </div>
                        </Link>
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
    <div v-if="showEditModal" class="fixed inset-0 z-50 bg-black bg-opacity-50 flex items-center justify-center">
        <div class="bg-white rounded-lg shadow-xl w-11/12 md:w-2/5 max-w-md mx-auto">
            <div class="flex items-center justify-between p-4 border-b border-gray-200 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-t-lg">
                <div class="flex items-center">
                    <div class="p-2 bg-white bg-opacity-30 rounded-lg mr-3">
                        <i class="fas fa-scroll"></i>
                    </div>
                    <h3 class="text-xl font-semibold">{{ isCreating ? 'Create Paper Quality' : 'Edit Paper Quality' }}</h3>
                </div>
                <button type="button" @click="closeEditModal" class="text-white hover:text-gray-200">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div class="p-6">
                <form @submit.prevent="saveChanges" class="space-y-4">
                    <div class="grid grid-cols-1 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Paper Quality Code:</label>
                            <input v-model="form.paper_quality" type="text" class="block w-full rounded-md border-gray-300 shadow-sm" 
                                :class="{ 'bg-gray-100': !isCreating }" :readonly="!isCreating" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Paper Quality Name:</label>
                            <input v-model="form.paper_name" type="text" class="block w-full rounded-md border-gray-300 shadow-sm" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Weight (KG/M):</label>
                            <input v-model="form.weight_kg_m" type="number" step="0.0001" min="0" max="9.9999" 
                                class="block w-full rounded-md border-gray-300 shadow-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Commercial Code:</label>
                            <input v-model="form.commercial_code" type="text" class="block w-full rounded-md border-gray-300 shadow-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Wet-End Code:</label>
                            <input v-model="form.wet_end_code" type="text" class="block w-full rounded-md border-gray-300 shadow-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">DECC Code:</label>
                            <input v-model="form.decc_code" type="text" class="block w-full rounded-md border-gray-300 shadow-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Status:</label>
                            <div class="grid grid-cols-2 gap-4">
                                <label class="relative flex items-center p-3 rounded-lg border border-gray-200 hover:border-blue-500 cursor-pointer transition-colors">
                                    <input type="radio" v-model="form.status" value="Act" class="h-4 w-4 text-blue-600 focus:ring-blue-500" @change="updateIsActive">
                                    <span class="ml-3 text-gray-900">Active (Act)</span>
                                </label>
                                <label class="relative flex items-center p-3 rounded-lg border border-gray-200 hover:border-blue-500 cursor-pointer transition-colors">
                                    <input type="radio" v-model="form.status" value="Obs" class="h-4 w-4 text-blue-600 focus:ring-blue-500" @change="updateIsActive">
                                    <span class="ml-3 text-gray-900">Obsolete (Obs)</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="flex justify-between mt-6 pt-4 border-t border-gray-200">
                        <button type="button" v-if="!isCreating" @click="deletePaperQuality" class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600">
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
    status: 'Act',
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
        status: 'Act',
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

const deletePaperQuality = async () => {
    if (!selectedQuality.value) {
        showNotification('No paper quality selected', 'error');
        return;
    }
    
    if (!confirm(`Are you sure you want to delete paper quality "${selectedQuality.value.paper_quality}"?`)) {
        return;
    }
    
    saving.value = true;
    try {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
        
        const response = await fetch(`/api/paper-qualities/${selectedQuality.value.id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        });
        
        if (!response.ok) {
            const errorData = await response.json();
            throw new Error(errorData.message || 'Error deleting paper quality');
        }
        
        // Remove from local array
        paperQualities.value = paperQualities.value.filter(q => q.id !== selectedQuality.value.id);
        
        // Reset selection and form
        selectedQuality.value = null;
        searchQuery.value = '';
        closeEditModal();
        
        showNotification('Paper quality deleted successfully', 'success');
    } catch (e) {
        console.error('Error deleting paper quality:', e);
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
