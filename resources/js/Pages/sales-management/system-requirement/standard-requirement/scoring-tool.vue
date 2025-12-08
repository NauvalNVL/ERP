<template>
    <AppLayout header="Scoring Tool">
        <Head title="Scoring Tool Management" />

        <!-- Header & Main Layout -->
        <div class="min-h-screen bg-gray-50 py-6 px-4 sm:px-6 lg:px-8">
            <div class="max-w-7xl mx-auto">
                <!-- Header Section -->
                <div class="bg-emerald-600 text-white shadow-sm rounded-xl border border-emerald-700 mb-4">
                    <div class="px-4 py-3 sm:px-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                        <div class="flex items-center gap-3">
                            <div class="h-9 w-9 rounded-full bg-emerald-500 flex items-center justify-center">
                                <i class="fas fa-tools text-white text-sm"></i>
                            </div>
                            <div>
                                <h2 class="text-lg sm:text-xl font-semibold leading-tight">
                                    Define Scoring Tool
                                </h2>
                                <p class="text-xs sm:text-sm text-emerald-100">
                                    Define scoring tools for production process.
                                </p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2 text-xs text-emerald-100">
                            <i class="fas fa-info-circle text-sm"></i>
                            <span>Search, create, and maintain scoring tools.</span>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Left Column -->
                    <div class="lg:col-span-2 space-y-4">
                        <div class="bg-white shadow-sm rounded-xl border border-gray-200">
                            <div class="px-4 py-3 sm:px-6 border-b border-gray-100 flex items-center">
                                <div class="p-2 bg-emerald-500 rounded-lg mr-3">
                                    <i class="fas fa-edit text-white"></i>
                                </div>
                                <div>
                                    <h3 class="text-sm sm:text-base font-semibold text-gray-800">Scoring Tool Management</h3>
                                    <p class="text-xs text-gray-500">Search, create, and update scoring tools.</p>
                                </div>
                            </div>
                            <div class="px-4 py-4 sm:px-6">
                                <!-- Search Section -->
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                                    <div class="col-span-2">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Scoring Tool Code</label>
                                        <div class="relative flex">
                                            <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500">
                                                <i class="fas fa-tools"></i>
                                            </span>
                                            <input
                                                type="text"
                                                v-model="searchQuery"
                                                class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none border border-gray-300 focus:ring-emerald-500 focus:border-emerald-500 text-sm transition-colors"
                                            />
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
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Action</label>
                                        <button
                                            type="button"
                                            @click="createNewScoringTool"
                                            class="w-full flex items-center justify-center px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-md text-sm font-semibold shadow-sm"
                                        >
                                            <i class="fas fa-plus-circle mr-2"></i>
                                            Add New
                                        </button>
                                    </div>
                                </div>

                                <!-- Data Status Information -->
                                <div v-if="loading" class="mt-3 bg-yellow-50 border border-yellow-100 p-3 rounded-lg">
                                    <div class="flex items-center">
                                        <div class="mr-3">
                                            <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-yellow-700"></div>
                                        </div>
                                        <p class="text-sm font-medium text-yellow-800">Loading scoring tool data...</p>
                                    </div>
                                </div>
                                <div v-else-if="scoringTools.length === 0" class="mt-3 bg-yellow-50 border border-yellow-100 p-3 rounded-lg">
                                    <p class="text-sm font-medium text-yellow-800">No scoring tool data available.</p>
                                    <p class="text-xs text-yellow-700 mt-1">Data will be automatically loaded when available.</p>
                                </div>
                                <div v-else class="mt-3 bg-emerald-50 border border-emerald-100 p-3 rounded-lg">
                                    <p class="text-sm font-medium text-emerald-800">Data available: {{ scoringTools.length }} scoring tools found.</p>
                                    <p v-if="selectedRow" class="text-xs text-emerald-700 mt-1">
                                        Selected:
                                        <span class="font-semibold">{{ selectedRow.code }}</span>
                                        -
                                        {{ selectedRow.name }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column - Quick Info -->
                    <div class="lg:col-span-1 space-y-4">
                        <!-- Scoring Tool Info Card -->
                        <div class="bg-white shadow-sm rounded-xl border border-emerald-100">
                            <div class="px-4 py-3 sm:px-5 border-b border-emerald-100 flex items-center">
                                <div class="p-2 bg-emerald-500 rounded-lg mr-3">
                                    <i class="fas fa-info-circle text-white"></i>
                                </div>
                                <h3 class="text-sm sm:text-base font-semibold text-gray-800">Info Scoring Tool</h3>
                            </div>
                            <div class="px-4 py-4 sm:px-5">
                                <div class="space-y-4">
                                    <div class="p-4 bg-emerald-50 rounded-lg">
                                        <h4 class="text-sm font-semibold text-emerald-800 uppercase tracking-wider mb-2">Instructions</h4>
                                        <ul class="list-disc pl-5 text-sm text-gray-600 space-y-1">
                                            <li>Scoring tool code must be unique</li>
                                            <li>Use the <span class="font-medium">search</span> button to select a tool</li>
                                            <li>Changes must be saved</li>
                                            <li>Gap determines the distance between scores</li>
                                        </ul>
                                    </div>

                                    <div class="p-4 bg-emerald-50 rounded-lg">
                                        <h4 class="text-sm font-semibold text-emerald-800 uppercase tracking-wider mb-2">Preset Tools</h4>
                                        <div class="grid grid-cols-1 gap-2 text-sm">
                                            <div class="flex items-center">
                                                <span class="w-6 h-6 flex items-center justify-center bg-emerald-600 text-white rounded-full font-bold mr-2">1</span>
                                                <span>MALE MALE</span>
                                            </div>
                                            <div class="flex items-center">
                                                <span class="w-6 h-6 flex items-center justify-center bg-green-600 text-white rounded-full font-bold mr-2">2</span>
                                                <span>MALE FEMALE 10MM</span>
                                            </div>
                                            <div class="flex items-center">
                                                <span class="w-6 h-6 flex items-center justify-center bg-purple-600 text-white rounded-full font-bold mr-2">3</span>
                                                <span>MALE FLAT</span>
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
        <ScoringToolModal
            :show="showModal"
            :scoring-tools="scoringTools"
            @close="showModal = false"
            @select="onScoringToolSelected"
        />

        <!-- Edit Modal -->
        <div v-if="showEditModal" class="fixed inset-0 z-40 bg-black bg-opacity-30 flex items-start sm:items-center justify-center">
            <div class="bg-white rounded-xl shadow-xl w-full max-w-md mx-4">
                <div class="flex items-center justify-between px-4 py-3 border-b border-gray-200 bg-emerald-600 rounded-t-xl text-white">
                    <div class="flex items-center">
                        <div class="p-2 bg-white bg-opacity-20 rounded-lg mr-3">
                            <i class="fas fa-tools"></i>
                        </div>
                        <h3 class="text-sm sm:text-base font-semibold">{{ isCreating ? 'Create Scoring Tool' : 'Edit Scoring Tool' }}</h3>
                    </div>
                    <button type="button" @click="closeEditModal" class="text-white hover:text-gray-100">
                        <i class="fas fa-times text-lg"></i>
                    </button>
                </div>
                <div class="px-4 py-5 sm:px-6">
                    <form @submit.prevent="saveScoringToolChanges" class="space-y-4">
                        <div class="grid grid-cols-1 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Scoring Tool Code</label>
                                <input
                                    v-model="editForm.code"
                                    type="text"
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:ring-emerald-500 focus:border-emerald-500 text-sm"
                                    :class="{ 'bg-gray-100': !isCreating }"
                                    :readonly="!isCreating"
                                    required
                                />
                                <span class="text-xs text-gray-500">Code must be unique</span>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Scoring Tool Name</label>
                                <input
                                    v-model="editForm.name"
                                    type="text"
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:ring-emerald-500 focus:border-emerald-500 text-sm"
                                    required
                                />
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Scorer Gap</label>
                                <input
                                    v-model="editForm.scorer_gap"
                                    type="number"
                                    step="0.1"
                                    min="0"
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:ring-emerald-500 focus:border-emerald-500 text-sm"
                                    required
                                />
                                <span class="text-xs text-gray-500">Value must be 0 or greater</span>
                            </div>
                        </div>
                        <div class="flex justify-between mt-6 pt-4 border-t border-gray-200">
                            <button
                                type="button"
                                v-if="!isCreating"
                                @click="obsoleteScoringTool(editForm.id)"
                                class="px-3 py-2 bg-orange-500 text-white rounded-md hover:bg-orange-600 text-xs sm:text-sm flex items-center shadow-sm"
                            >
                                <i class="fas fa-ban mr-2"></i>
                                Obsolete
                            </button>
                            <div v-else class="w-24"></div>
                            <div class="flex space-x-3">
                                <button
                                    type="button"
                                    @click="closeEditModal"
                                    class="px-3 py-2 bg-gray-100 text-gray-800 rounded-md hover:bg-gray-200 text-xs sm:text-sm"
                                >
                                    Cancel
                                </button>
                                <button
                                    type="submit"
                                    class="px-3 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-md text-xs sm:text-sm font-semibold shadow-sm"
                                >
                                    Save
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Loading Overlay -->
        <div v-if="saving" class="fixed inset-0 z-50 bg-black bg-opacity-30 flex justify-center items-start pt-32">
            <div class="bg-white px-5 py-4 rounded-lg shadow-lg text-center border border-gray-200">
                <div class="w-8 h-8 border-4 border-solid border-emerald-500 border-t-transparent rounded-full animate-spin mx-auto mb-2"></div>
                <p class="text-sm text-gray-700">Saving changes...</p>
            </div>
        </div>

        <!-- Notification Toast -->
        <div
            v-if="notification.show"
            class="fixed bottom-4 right-4 z-50 max-w-sm shadow-lg rounded-lg border transition-all duration-300"
            :class="{
                'bg-emerald-50 border-emerald-200': notification.type === 'success',
                'bg-red-50 border-red-200': notification.type === 'error',
                'bg-yellow-50 border-yellow-200': notification.type === 'warning'
            }"
        >
            <div class="p-3 flex items-start">
                <div class="mr-3 mt-0.5">
                    <i v-if="notification.type === 'success'" class="fas fa-check-circle text-emerald-500 text-lg"></i>
                    <i v-else-if="notification.type === 'error'" class="fas fa-exclamation-circle text-red-500 text-lg"></i>
                    <i v-else class="fas fa-exclamation-triangle text-yellow-500 text-lg"></i>
                </div>
                <p
                    class="text-sm"
                    :class="{
                        'text-emerald-800': notification.type === 'success',
                        'text-red-800': notification.type === 'error',
                        'text-yellow-800': notification.type === 'warning'
                    }"
                >
                    {{ notification.message }}
                </p>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue';
import { Head, Link, usePage, router } from '@inertiajs/vue3';
import ScoringToolModal from '@/Components/scoring-tool-modal.vue';
import AppLayout from '@/Layouts/AppLayout.vue';

// Get the header from props
const props = defineProps({
    header: {
        type: String,
        default: 'Scoring Tool Management'
    }
});

const scoringTools = ref([]);
const loading = ref(false);
const saving = ref(false);
const showModal = ref(false);
const showEditModal = ref(false);
const selectedRow = ref(null);
const searchQuery = ref('');
const editForm = ref({ 
    id: '',
    code: '', 
    name: '', 
    scorer_gap: 0.0
});
const isCreating = ref(false);
const notification = ref({ show: false, message: '', type: 'success' });

const fetchScoringTools = async () => {
    loading.value = true;
    try {
        const res = await fetch('/api/scoring-tools', { 
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
            scoringTools.value = data;
        } else if (data.data && Array.isArray(data.data)) {
            scoringTools.value = data.data;
        } else {
            scoringTools.value = [];
            console.error('Unexpected data format:', data);
        }
    } catch (e) {
        console.error('Error fetching scoring tools:', e);
        scoringTools.value = [];
    } finally {
        loading.value = false;
    }
};

onMounted(() => {
    fetchScoringTools();
});

// Watch for changes in search query to filter the data
watch(searchQuery, (newQuery) => {
    if (newQuery && scoringTools.value.length > 0) {
        const foundTool = scoringTools.value.find(tool => 
            tool.code.toLowerCase().includes(newQuery.toLowerCase()) ||
            tool.name.toLowerCase().includes(newQuery.toLowerCase())
        );
        
        if (foundTool) {
            selectedRow.value = foundTool;
        }
    }
});

const onScoringToolSelected = (tool) => {
    selectedRow.value = tool;
    searchQuery.value = tool.code;
    showModal.value = false;
    
    // Automatically open the edit modal for the selected row
    isCreating.value = false;
    editForm.value = { ...tool };
    showEditModal.value = true;
};

const editSelectedRow = () => {
    if (selectedRow.value) {
        isCreating.value = false;
        editForm.value = { ...selectedRow.value };
        showEditModal.value = true;
    } else {
        showNotification('Please select a scoring tool first', 'error');
    }
};

const createNewScoringTool = () => {
    isCreating.value = true;
    editForm.value = { 
        id: '',
        code: '', 
        name: '', 
        scorer_gap: 0.0
    };
    showEditModal.value = true;
};

const closeEditModal = () => {
    showEditModal.value = false;
    editForm.value = { 
        id: '',
        code: '', 
        name: '', 
        scorer_gap: 0.0
    };
    isCreating.value = false;
};

const saveScoringToolChanges = async () => {
    // Validate scorer_gap is not negative
    if (parseFloat(editForm.value.scorer_gap) < 0) {
        showNotification('Scorer Gap cannot be negative. Please enter a value of 0 or greater.', 'error');
        return;
    }
    
    saving.value = true;
    try {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
        
        // Different API call for create vs update
        let url = isCreating.value ? '/api/scoring-tools' : `/api/scoring-tools/${editForm.value.id}`;
        let method = isCreating.value ? 'POST' : 'PUT';
        
        const response = await fetch(url, {
            method: method,
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                code: editForm.value.code,
                name: editForm.value.name,
                scorer_gap: parseFloat(editForm.value.scorer_gap)
            })
        });
        
        const result = await response.json();
        
        if (result.success) {
            // Update the local data with the changes or add new item
            if (isCreating.value) {
                showNotification('Scoring tool created successfully', 'success');
            } else {
                if (selectedRow.value) {
                    selectedRow.value.name = editForm.value.name;
                    selectedRow.value.scores = editForm.value.scores;
                    selectedRow.value.gap = editForm.value.gap;
                    selectedRow.value.specification = editForm.value.specification;
                    selectedRow.value.description = editForm.value.description;
                }
                showNotification('Scoring tool updated successfully', 'success');
            }
            
            // Refresh the full data list to ensure we're in sync with the database
            await fetchScoringTools();
            closeEditModal();
        } else {
            showNotification('Error: ' + (result.message || 'Unknown error'), 'error');
        }
    } catch (e) {
        console.error('Error saving scoring tool changes:', e);
        showNotification('Error saving scoring tool. Please try again.', 'error');
    } finally {
        saving.value = false;
    }
};

const obsoleteScoringTool = async (id) => {
    if (!confirm(`Are you sure you want to obsolete this scoring tool? This will hide it from scoring tool selection.`)) {
        return;
    }
    
    if (!id) {
        showNotification('Invalid scoring tool ID', 'error');
        return;
    }
    
    saving.value = true;
    try {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
        
        const response = await fetch(`/api/scoring-tools/${id}/status`, {
            method: 'PUT',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        });
        
        const result = await response.json();
        
        if (result.success) {
            // Remove from the local array (since it's now obsolete)
            scoringTools.value = scoringTools.value.filter(tool => tool.id !== id);
            
            if (selectedRow.value && selectedRow.value.id === id) {
                selectedRow.value = null;
                searchQuery.value = '';
            }
            
            closeEditModal();
            showNotification(`Scoring tool "${editForm.value.code}" has been obsoleted successfully.`, 'success');
        } else {
            showNotification('Error obsoleting scoring tool: ' + (result.message || 'Unknown error'), 'error');
        }
    } catch (e) {
        console.error('Error obsoleting scoring tool:', e);
        showNotification('Error obsoleting scoring tool. Please try again.', 'error');
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
