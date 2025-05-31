<template>
    <AppLayout :header="'Color Group'">
    <Head title="Color Group Management" />

    <!-- Hidden form with CSRF token -->
    <form ref="csrfForm" class="hidden">
        @csrf
    </form>

    <!-- Header Section -->
    <div class="bg-gradient-to-r from-cyan-700 to-blue-600 p-6 rounded-t-lg shadow-lg">
        <h2 class="text-2xl font-bold text-white mb-2 flex items-center">
            <i class="fas fa-layer-group mr-3"></i> Define Color Group
        </h2>
        <p class="text-cyan-100">Define color groups for production and inventory management</p>
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
                        <h3 class="text-xl font-semibold text-gray-800">Color Group Management</h3>
                    </div>
                    
                    <!-- Header with navigation buttons -->
                    <div class="flex items-center space-x-2 mb-6">
                        <button type="button" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded flex items-center space-x-2 transform active:translate-y-px">
                            <i class="fas fa-power-off"></i>
                        </button>
                        <button type="button" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded flex items-center space-x-2 transform active:translate-y-px">
                            <i class="fas fa-arrow-right"></i>
                        </button>
                        <button type="button" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded flex items-center space-x-2 transform active:translate-y-px">
                            <i class="fas fa-arrow-left"></i>
                        </button>
                        <button type="button" @click="showModal = true" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded flex items-center space-x-2 transform active:translate-y-px">
                            <i class="fas fa-search"></i>
                        </button>
                        <button type="button" @click="saveColorGroup" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded flex items-center space-x-2 transform active:translate-y-px">
                            <i class="fas fa-save"></i>
                        </button>
                    </div>

                    <!-- Search Section -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-6">
                        <div class="col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Color Group#:</label>
                            <div class="relative flex">
                                <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500">
                                    <i class="fas fa-layer-group"></i>
                                </span>
                                <input type="text" v-model="searchQuery" 
                                    class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none border border-gray-300 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                <button type="button" @click="showModal = true" class="inline-flex items-center px-3 py-2 border border-l-0 border-gray-300 bg-blue-500 hover:bg-blue-600 text-white rounded-r-md transition-colors transform active:translate-y-px">
                                    <i class="fas fa-table"></i>
                                </button>
                            </div>
                        </div>
                        
                        <div class="col-span-1">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Record:</label>
                            <button type="button" @click="editSelectedGroup" class="w-full flex items-center justify-center px-4 py-2 bg-indigo-500 hover:bg-indigo-600 text-white rounded transition-colors transform active:translate-y-px">
                                <i class="fas fa-list-ul mr-2"></i> Select Record
                            </button>
                        </div>
                    </div>

                    <!-- Data Status Information -->
                    <div v-if="loading" class="mt-4 bg-yellow-100 p-3 rounded">
                        <div class="flex items-center">
                            <div class="mr-3 animate-spin rounded-full h-6 w-6 border-b-2 border-yellow-700"></div>
                            <p class="text-sm font-medium text-yellow-800">Loading color group data...</p>
                        </div>
                    </div>
                    <div v-else-if="colorGroups.length === 0" class="mt-4 bg-yellow-100 p-3 rounded">
                        <p class="text-sm font-medium text-yellow-800">No color group data available.</p>
                        <p class="text-xs text-yellow-700 mt-1">Make sure the database is properly configured and seeders have been run.</p>
                        <div class="mt-2 flex items-center space-x-3">
                            <button @click="loadSeedData" class="bg-blue-500 hover:bg-blue-600 text-white text-xs px-3 py-1 rounded">Run Color Group Seeder</button>
                            <button @click="fetchColorGroups" class="bg-green-500 hover:bg-green-600 text-white text-xs px-3 py-1 rounded">Reload Data</button>
                        </div>
                    </div>
                    <div v-else class="mt-4 bg-green-100 p-3 rounded">
                        <p class="text-sm font-medium text-green-800">Data available: {{ colorGroups.length }} color groups found.</p>
                        <p v-if="selectedGroup" class="text-xs text-green-700 mt-1">
                            Selected: <span class="font-semibold">{{ selectedGroup.cg }}</span> - {{ selectedGroup.cg_name }} ({{ selectedGroup.cg_type }})
                        </p>
                    </div>
                </div>
            </div>

            <!-- Right Column - Quick Info -->
            <div class="lg:col-span-1">
                <!-- Color Group Info Card -->
                <div class="bg-white p-6 rounded-lg shadow-md border-t-4 border-teal-500 mb-6">
                    <div class="flex items-center mb-4 pb-2 border-b border-gray-200">
                        <div class="p-2 bg-teal-500 rounded-lg mr-3">
                            <i class="fas fa-info-circle text-white"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-800">Info Color Group</h3>
                    </div>

                    <div class="space-y-4">
                        <div class="p-4 bg-teal-50 rounded-lg">
                            <h4 class="text-sm font-semibold text-teal-800 uppercase tracking-wider mb-2">Instructions</h4>
                            <ul class="list-disc pl-5 text-sm text-gray-600 space-y-1">
                                <li>Color group code must be unique and cannot be changed</li>
                                <li>Use the <span class="font-medium">search</span> button to select a color group</li>
                                <li>CG type determines the group characteristics</li>
                                <li>Any changes must be saved</li>
                            </ul>
                        </div>

                        <div class="p-4 bg-blue-50 rounded-lg">
                            <h4 class="text-sm font-semibold text-blue-800 uppercase tracking-wider mb-2">Common CG Types</h4>
                            <div class="grid grid-cols-2 gap-2 text-sm">
                                <div class="flex items-center">
                                    <span class="w-6 h-6 flex items-center justify-center bg-blue-500 text-white rounded-full font-bold mr-2">F</span>
                                    <span>Flexo</span>
                                </div>
                                <div class="flex items-center">
                                    <span class="w-6 h-6 flex items-center justify-center bg-purple-500 text-white rounded-full font-bold mr-2">O</span>
                                    <span>Offset</span>
                                </div>
                                <div class="flex items-center">
                                    <span class="w-6 h-6 flex items-center justify-center bg-green-500 text-white rounded-full font-bold mr-2">C</span>
                                    <span>Coating</span>
                                </div>
                                <div class="flex items-center">
                                    <span class="w-6 h-6 flex items-center justify-center bg-red-500 text-white rounded-full font-bold mr-2">D</span>
                                    <span>Digital</span>
                                </div>
                                <div class="flex items-center">
                                    <span class="w-6 h-6 flex items-center justify-center bg-yellow-500 text-white rounded-full font-bold mr-2">S</span>
                                    <span>Special</span>
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
                                <p class="text-xs text-purple-700">Manage individual colors</p>
                            </div>
                        </Link>

                        <a href="#" class="flex items-center p-3 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                            <div class="p-2 bg-blue-500 rounded-full mr-3">
                                <i class="fas fa-th-list text-white text-sm"></i>
                            </div>
                            <div>
                                <p class="font-medium text-blue-900">CG Types</p>
                                <p class="text-xs text-blue-700">View color group types</p>
                            </div>
                        </a>

                        <Link href="/color-group/view-print" class="flex items-center p-3 bg-green-50 rounded-lg hover:bg-green-100 transition-colors">
                            <div class="p-2 bg-green-500 rounded-full mr-3">
                                <i class="fas fa-print text-white text-sm"></i>
                            </div>
                            <div>
                                <p class="font-medium text-green-900">Print List</p>
                                <p class="text-xs text-green-700">Print color group list</p>
                            </div>
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for Color Group Table -->
    <div v-if="showModal" class="fixed inset-0 z-50 bg-black bg-opacity-50 flex items-center justify-center">
        <div class="bg-white rounded-lg shadow-xl w-11/12 md:w-3/4 lg:w-2/3 max-w-5xl mx-auto transform transition-transform duration-300" style="max-height: 80vh;">
            <!-- Modal Header - Title Bar -->
            <div class="flex items-center justify-between p-4 border-b border-gray-200 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-t-lg">
                <div class="flex items-center">
                    <div class="p-2 bg-white bg-opacity-30 rounded-lg mr-3">
                        <i class="fas fa-layer-group"></i>
                    </div>
                    <h3 class="text-xl font-semibold">Color Group Table</h3>
                </div>
                <button type="button" @click="showModal = false" class="text-white hover:text-gray-200 focus:outline-none transform active:translate-y-px">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <!-- Table Content -->
            <div class="p-5 overflow-auto" style="max-height: calc(80vh - 130px);">
                <div class="mb-4">
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
                            <i class="fas fa-search"></i>
                        </span>
                        <input type="text" v-model="modalSearchQuery" placeholder="Search color groups..."
                            class="pl-10 pr-4 py-2 w-full border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 bg-gray-50">
                    </div>
                </div>
                
                <div class="overflow-x-auto rounded-lg border border-gray-200">
                    <table class="min-w-full divide-y divide-gray-200" id="colorGroupDataTable">
                        <thead class="bg-gray-50 sticky top-0">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer" @click="sortTable('cg')">CG#</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer" @click="sortTable('cg_name')">CG Name</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer" @click="sortTable('cg_type')">CG Type</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200 text-xs">
                            <tr v-for="group in filteredColorGroups" :key="group.cg" 
                                :class="['hover:bg-blue-50 cursor-pointer', selectedGroup && selectedGroup.cg === group.cg ? 'bg-blue-100' : '']"
                                @click="selectGroup(group)"
                                @dblclick="onColorGroupSelected(group)">
                                <td class="px-4 py-3 whitespace-nowrap font-medium text-gray-900">{{ group.cg }}</td>
                                <td class="px-4 py-3 whitespace-nowrap text-gray-700">{{ group.cg_name }}</td>
                                <td class="px-4 py-3 whitespace-nowrap text-gray-700">{{ group.cg_type }}</td>
                            </tr>
                            <tr v-if="filteredColorGroups.length === 0">
                                <td colspan="3" class="px-4 py-4 text-center text-gray-500">No color group data available.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Bottom Buttons -->
                <div class="mt-4 grid grid-cols-5 gap-2">
                    <button type="button" @click="sortTable('cg')" class="py-2 px-3 bg-gray-100 border border-gray-400 hover:bg-gray-200 text-xs rounded-lg transform active:translate-y-px">
                        <i class="fas fa-sort mr-1"></i>By CG#
                    </button>
                    <button type="button" @click="sortTable('cg_name')" class="py-2 px-3 bg-gray-100 border border-gray-400 hover:bg-gray-200 text-xs rounded-lg transform active:translate-y-px">
                        <i class="fas fa-sort mr-1"></i>By CG Name
                    </button>
                    <button type="button" @click="sortTable('cg_type')" class="py-2 px-3 bg-gray-100 border border-gray-400 hover:bg-gray-200 text-xs rounded-lg transform active:translate-y-px">
                        <i class="fas fa-sort mr-1"></i>By CG Type
                    </button>
                    <button type="button" @click="onColorGroupSelected(selectedGroup)" class="py-2 px-3 bg-blue-500 hover:bg-blue-600 text-white text-xs rounded-lg transform active:translate-y-px">
                        <i class="fas fa-edit mr-1"></i>Select
                    </button>
                    <button type="button" @click="showModal = false" class="py-2 px-3 bg-gray-300 hover:bg-gray-400 text-gray-800 text-xs rounded-lg transform active:translate-y-px">
                        <i class="fas fa-times mr-1"></i>Exit
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div v-if="showEditModal" class="fixed inset-0 z-50 bg-black bg-opacity-50 flex items-center justify-center">
        <div class="bg-white rounded-lg shadow-xl w-11/12 md:w-2/5 max-w-md mx-auto transform transition-transform duration-300">
            <!-- Modal Header - Title Bar -->
            <div class="flex items-center justify-between p-4 border-b border-gray-200 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-t-lg">
                <div class="flex items-center">
                    <div class="p-2 bg-white bg-opacity-30 rounded-lg mr-3">
                        <i class="fas fa-layer-group"></i>
                    </div>
                    <h3 class="text-xl font-semibold">Define Color Group</h3>
                </div>
                <button type="button" @click="closeEditModal" class="text-white hover:text-gray-200 focus:outline-none transform active:translate-y-px">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <!-- Form Content -->
            <div class="p-6">
                <form @submit.prevent="saveColorGroup" class="space-y-4">
                    <div class="grid grid-cols-1 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">CG#:</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
                                    <i class="fas fa-hashtag"></i>
                                </span>
                                <input type="text" v-model="form.cg" class="pl-10 block w-full rounded-md border-gray-300 shadow-sm" 
                                    :class="{ 'bg-gray-100': !isCreating }" 
                                    :readonly="!isCreating" 
                                    required>
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">CG Name:</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
                                    <i class="fas fa-font"></i>
                                </span>
                                <input type="text" v-model="form.cg_name" class="pl-10 block w-full rounded-md border-gray-300 shadow-sm" required>
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">CG Type:</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
                                    <i class="fas fa-tag"></i>
                                </span>
                                <select v-model="form.cg_type" class="pl-10 block w-full rounded-md border-gray-300 shadow-sm" required>
                                <option value="X-Flex">X-Flex</option>
                                <option value="C-Coating">C-Coating</option>
                                <option value="S-Offset">S-Offset</option>
                                <option value="D-Digital">D-Digital</option>
                                <option value="P-Pantone">P-Pantone</option>
                            </select>
                        </div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Note:</label>
                            <div class="border border-gray-300 rounded-md p-3 bg-gray-50 h-16 overflow-auto text-sm">
                                <p>Color Group determines color characteristics in the printing process</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex justify-end space-x-3 mt-6 pt-4 border-t border-gray-200">
                        <button type="button" @click="closeEditModal" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition-colors text-sm transform active:translate-y-px">
                            <i class="fas fa-times mr-2"></i>Cancel
                        </button>
                        <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors text-sm transform active:translate-y-px">
                            <i class="fas fa-save mr-2"></i>Save
                        </button>
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
import { ref, computed, onMounted, watch } from 'vue';
import { Head, Link, usePage } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

// Get any props passed from the controller
const props = defineProps({
    header: {
        type: String,
        default: 'Color Group Management'
    }
});

// Reference to the CSRF form
const csrfForm = ref(null);

// Function to get fresh CSRF token from the form
const getCsrfToken = () => {
    // Try to get token from meta tag first
    let token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    
    // If token from meta tag is not available or we want a fresh token, get from the form
    if (csrfForm.value) {
        const tokenInput = csrfForm.value.querySelector('input[name="_token"]');
        if (tokenInput) {
            token = tokenInput.value;
        }
    }
    
    return token || '';
};

const colorGroups = ref([]);
const loading = ref(false);
const saving = ref(false);
const showModal = ref(false);
const showEditModal = ref(false);
const selectedGroup = ref(null);
const searchQuery = ref('');
const modalSearchQuery = ref('');
const isCreating = ref(false);
const notification = ref({ show: false, message: '', type: 'success' });
const sortKey = ref('cg');
const sortAsc = ref(true);

// Form state
const form = ref({
    cg: '',
    cg_name: '',
    cg_type: 'X-Flex'
});

// Filtered and sorted color groups for the modal table
const filteredColorGroups = computed(() => {
    let groups = colorGroups.value;
    
    if (modalSearchQuery.value) {
        const q = modalSearchQuery.value.toLowerCase();
        groups = groups.filter(g =>
            g.cg.toLowerCase().includes(q) ||
            g.cg_name.toLowerCase().includes(q) ||
            g.cg_type.toLowerCase().includes(q)
        );
    }
    
    // Sort
    return [...groups].sort((a, b) => {
        if (a[sortKey.value] < b[sortKey.value]) return sortAsc.value ? -1 : 1;
        if (a[sortKey.value] > b[sortKey.value]) return sortAsc.value ? 1 : -1;
        return 0;
    });
});

// Watch for changes in search query to filter the data
watch(searchQuery, (newQuery) => {
    if (newQuery && colorGroups.value.length > 0) {
        const foundGroup = colorGroups.value.find(group => 
            group.cg.toLowerCase().includes(newQuery.toLowerCase()) ||
            group.cg_name.toLowerCase().includes(newQuery.toLowerCase()) ||
            group.cg_type.toLowerCase().includes(newQuery.toLowerCase())
        );
        
        if (foundGroup) {
            selectGroup(foundGroup);
        }
    }
});

const fetchColorGroups = async () => {
    loading.value = true;
    try {
        // Updated API path and headers to match how your backend expects the request
        const response = await fetch('/api/color-groups', { 
            headers: { 
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            credentials: 'same-origin' // Include cookies in the request
        });
        
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        
        const data = await response.json();
        
        if (Array.isArray(data)) {
            colorGroups.value = data;
        } else if (data.data && Array.isArray(data.data)) {
            colorGroups.value = data.data;
        } else {
            colorGroups.value = [];
            console.error('Unexpected data format:', data);
        }
    } catch (e) {
        console.error('Error fetching color groups:', e);
        colorGroups.value = [];
    } finally {
        loading.value = false;
    }
};

onMounted(() => {
    fetchColorGroups();
});

const onColorGroupSelected = (group) => {
    if (!group) {
        showNotification('Please select a color group first', 'warning');
        return;
    }
    
    selectGroup(group);
    showModal.value = false;
    
    // Automatically open the edit modal for the selected row
    editSelectedGroup();
};

const selectGroup = (group) => {
    selectedGroup.value = group;
    searchQuery.value = group.cg;
};

const editSelectedGroup = () => {
    if (selectedGroup.value) {
        isCreating.value = false;
        form.value = {
            cg: selectedGroup.value.cg,
            cg_name: selectedGroup.value.cg_name,
            cg_type: selectedGroup.value.cg_type
        };
        showEditModal.value = true;
    } else {
        showNotification('Please select a color group first', 'error');
    }
};

const createNewColorGroup = () => {
    isCreating.value = true;
    form.value = {
        cg: '',
        cg_name: '',
        cg_type: 'X-Flex'
    };
    showEditModal.value = true;
};

const closeEditModal = () => {
    showEditModal.value = false;
};

const sortTable = (key) => {
    if (sortKey.value === key) {
        sortAsc.value = !sortAsc.value;
    } else {
        sortKey.value = key;
        sortAsc.value = true;
    }
};

const saveColorGroup = async () => {
    // Validate form
    if (!form.value.cg) {
        showNotification('Color Group code is required', 'error');
        return;
    }

    if (!form.value.cg_name) {
        showNotification('Color Group name is required', 'error');
        return;
    }

    if (!form.value.cg_type) {
        showNotification('Color Group type is required', 'error');
        return;
    }

    saving.value = true;
    try {
        const csrfToken = getCsrfToken();
        
        // Different API call for create vs update
        let url = isCreating.value ? '/api/color-groups' : `/api/color-groups/${form.value.cg}`;
        let method = isCreating.value ? 'POST' : 'PUT';
        
        const response = await fetch(url, {
            method: method,
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify(form.value),
            credentials: 'same-origin' // Include cookies in the request
        });
        
        if (!response.ok) {
            const errorData = await response.json();
            throw new Error(errorData.message || 'Error saving color group');
        }
        
        const result = await response.json();
        
        // Update the local data
        await fetchColorGroups();
        
        // Show success notification
        if (isCreating.value) {
            showNotification('Color group created successfully', 'success');
            // Find and select the newly created group
            const newGroup = colorGroups.value.find(g => g.cg === form.value.cg);
            if (newGroup) {
                selectGroup(newGroup);
            }
        } else {
            showNotification('Color group updated successfully', 'success');
        }
        
        // Close the edit modal
        closeEditModal();
    } catch (e) {
        console.error('Error saving color group:', e);
        showNotification('Error: ' + e.message, 'error');
    } finally {
        saving.value = false;
    }
};

const deleteColorGroup = async () => {
    if (!selectedGroup.value) {
        showNotification('No color group selected', 'error');
        return;
    }
    
    if (!confirm(`Are you sure you want to delete color group "${selectedGroup.value.cg}"?`)) {
        return;
    }
    
    saving.value = true;
    try {
        const csrfToken = getCsrfToken();
        
        const response = await fetch(`/api/color-groups/${selectedGroup.value.cg}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            credentials: 'same-origin' // Include cookies in the request
        });
        
        if (!response.ok) {
            const errorData = await response.json();
            throw new Error(errorData.message || 'Error deleting color group');
        }
        
        // Remove from local array
        colorGroups.value = colorGroups.value.filter(g => g.cg !== selectedGroup.value.cg);
        
        // Reset selection and form
        selectedGroup.value = null;
        searchQuery.value = '';
        closeEditModal();
        
        showNotification('Color group deleted successfully', 'success');
    } catch (e) {
        console.error('Error deleting color group:', e);
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

const loadSeedData = async () => {
    saving.value = true;
    try {
        const csrfToken = getCsrfToken();
        
        const response = await fetch('/api/color-groups/seed', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            credentials: 'same-origin' // Include cookies in the request
        });
        
        const result = await response.json();
        
        if (result.success) {
            showNotification('Color group data seeded successfully', 'success');
            await fetchColorGroups();
        } else {
            showNotification('Error seeding data: ' + (result.message || 'Unknown error'), 'error');
        }
    } catch (e) {
        console.error('Error seeding data:', e);
        showNotification('Error seeding data. Please try again.', 'error');
    } finally {
        saving.value = false;
    }
};
</script>
