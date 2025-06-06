<template>
    <AppLayout :header="'Define Color'">
    <Head title="Define Color" />

    <!-- Header Section -->
    <div class="bg-gradient-to-r from-cyan-700 to-blue-600 p-6 rounded-t-lg shadow-lg">
        <h2 class="text-2xl font-bold text-white mb-2 flex items-center">
            <i class="fas fa-palette mr-3"></i> Define Color
        </h2>
        <p class="text-cyan-100">Define colors for specific product categories</p>
    </div>

    <div class="bg-white rounded-b-lg shadow-lg p-6 mb-6">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column -->
            <div class="lg:col-span-2">
                <div class="bg-white p-6 rounded-lg shadow-md border-t-4 border-blue-500">
                    <div class="flex items-center mb-6 pb-2 border-b border-gray-200">
                        <div class="p-2 bg-blue-500 rounded-lg mr-3">
                            <i class="fas fa-edit text-white"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-800">Define Color</h3>
                    </div>
                    
                    <!-- Search Section -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-6">
                        <div class="col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Color Code:</label>
                            <div class="relative flex">
                                <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500">
                                    <i class="fas fa-palette"></i>
                                </span>
                                <input type="text" v-model="searchQuery" class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none border border-gray-300 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                <button type="button" @click="showModal = true" class="inline-flex items-center px-3 py-2 border border-l-0 border-gray-300 bg-blue-500 hover:bg-blue-600 text-white rounded-r-md transition-colors transform active:translate-y-px">
                                    <i class="fas fa-table"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-span-1">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Action:</label>
                            <button type="button" @click="createNewColor" class="w-full flex items-center justify-center px-4 py-2 bg-indigo-500 hover:bg-indigo-600 text-white rounded transition-colors transform active:translate-y-px">
                                <i class="fas fa-plus-circle mr-2"></i> Add New
                            </button>
                        </div>
                    </div>
                    <!-- Data Status Information -->
                    <div v-if="loading" class="mt-4 bg-yellow-100 p-3 rounded">
                        <div class="flex items-center">
                            <div class="mr-3 animate-spin rounded-full h-6 w-6 border-b-2 border-yellow-700"></div>
                            <p class="text-sm font-medium text-yellow-800">Loading color data...</p>
                        </div>
                    </div>
                    <div v-else-if="colors.length === 0" class="mt-4 bg-yellow-100 p-3 rounded">
                        <p class="text-sm font-medium text-yellow-800">No color data available.</p>
                        <p class="text-xs text-yellow-700 mt-1">Make sure the database is properly configured and seeders have been run.</p>
                        <div class="mt-2 flex items-center space-x-3">
                            <button @click="fetchColors" class="bg-blue-500 hover:bg-blue-600 text-white text-xs px-3 py-1 rounded">Reload Data</button>
                        </div>
                    </div>
                    <div v-else class="mt-4 bg-green-100 p-3 rounded">
                        <p class="text-sm font-medium text-green-800">Data available: {{ colors.length }} colors found.</p>
                        <p v-if="selectedRow" class="text-xs text-green-700 mt-1">
                            Selected: <span class="font-semibold">{{ selectedRow.color_id }}</span> - {{ selectedRow.color_name }} ({{ selectedRow.cg_type }})
                        </p>
                    </div>
                </div>
            </div>
            <!-- Right Column - Quick Info -->
            <div class="lg:col-span-1">
                <!-- Color Info Card -->
                <div class="bg-white p-6 rounded-lg shadow-md border-t-4 border-teal-500 mb-6">
                    <div class="flex items-center mb-4 pb-2 border-b border-gray-200">
                        <div class="p-2 bg-teal-500 rounded-lg mr-3">
                            <i class="fas fa-info-circle text-white"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-800">Color Information</h3>
                    </div>

                    <div class="space-y-4">
                        <div class="p-4 bg-teal-50 rounded-lg">
                            <h4 class="text-sm font-semibold text-teal-800 uppercase tracking-wider mb-2">Instructions</h4>
                            <ul class="list-disc pl-5 text-sm text-gray-600 space-y-1">
                                <li>Color code must be unique and cannot be changed</li>
                                <li>Use the <span class="font-medium">search</span> button to select a color</li>
                                <li>CG type determines the color characteristics</li>
                                <li>Any changes must be saved</li>
                            </ul>
                        </div>

                        <div class="p-4 bg-blue-50 rounded-lg">
                            <h4 class="text-sm font-semibold text-blue-800 uppercase tracking-wider mb-2">Common Color Groups</h4>
                            <div class="grid grid-cols-2 gap-2 text-sm">
                                <div class="flex items-center">
                                    <span class="w-6 h-6 flex items-center justify-center bg-black text-white rounded-full font-bold mr-2">01</span>
                                    <span>Black</span>
                                </div>
                                <div class="flex items-center">
                                    <span class="w-6 h-6 flex items-center justify-center bg-gray-200 text-gray-800 rounded-full font-bold mr-2">02</span>
                                    <span>White</span>
                                </div>
                                <div class="flex items-center">
                                    <span class="w-6 h-6 flex items-center justify-center bg-red-500 text-white rounded-full font-bold mr-2">03</span>
                                    <span>Red</span>
                                </div>
                                <div class="flex items-center">
                                    <span class="w-6 h-6 flex items-center justify-center bg-blue-500 text-white rounded-full font-bold mr-2">04</span>
                                    <span>Blue</span>
                                </div>
                                <div class="flex items-center">
                                    <span class="w-6 h-6 flex items-center justify-center bg-green-500 text-white rounded-full font-bold mr-2">05</span>
                                    <span>Green</span>
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
                        <Link href="/color-group" class="flex items-center p-3 bg-purple-50 rounded-lg hover:bg-purple-100 transition-colors">
                            <div class="p-2 bg-purple-500 rounded-full mr-3">
                                <i class="fas fa-layer-group text-white text-sm"></i>
                            </div>
                            <div>
                                <p class="font-medium text-purple-900">Color Groups</p>
                                <p class="text-xs text-purple-700">Manage color groups</p>
                            </div>
                        </Link>

                        <a href="/finishing" class="flex items-center p-3 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                            <div class="p-2 bg-blue-500 rounded-full mr-3">
                                <i class="fas fa-th-list text-white text-sm"></i>
                            </div>
                            <div>
                                <p class="font-medium text-blue-900">Finishings</p>
                                <p class="text-xs text-blue-700">Manage finishings</p>
                            </div>
                        </a>

                        <Link href="/color/view-print" class="flex items-center p-3 bg-green-50 rounded-lg hover:bg-green-100 transition-colors">
                            <div class="p-2 bg-green-500 rounded-full mr-3">
                                <i class="fas fa-print text-white text-sm"></i>
                            </div>
                            <div>
                                <p class="font-medium text-green-900">Print List</p>
                                <p class="text-xs text-green-700">Print color list</p>
                            </div>
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Gunakan komponen ColorModal yang telah diimpor -->
    <ColorModal 
      v-if="showModal"
      :show="showModal"
      :colors="colors"
      :colorGroups="colorGroups"
      @close="showModal = false"
      @select="onColorSelected"
      @refresh="refreshColors"
    />

    <!-- Edit Modal -->
    <div v-if="showEditModal" class="fixed inset-0 z-50 bg-black bg-opacity-50 flex items-center justify-center">
        <div class="bg-white rounded-lg shadow-xl w-11/12 md:w-2/5 max-w-md mx-auto transform transition-transform duration-300">
            <div class="flex items-center justify-between p-4 border-b border-gray-200 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-t-lg">
                <div class="flex items-center">
                    <div class="p-2 bg-white bg-opacity-30 rounded-lg mr-3">
                        <i class="fas fa-palette"></i>
                    </div>
                    <h3 class="text-xl font-semibold">{{ isCreating ? 'Create Color' : 'Edit Color' }}</h3>
                </div>
                <button type="button" @click="closeEditModal" class="text-white hover:text-gray-200 transform active:translate-y-px">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div class="p-6">
                <form @submit.prevent="saveColorChanges" class="space-y-4">
                    <div class="grid grid-cols-1 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Color#:</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
                                    <i class="fas fa-hashtag"></i>
                                </span>
                                <input v-model="editForm.color_id" type="text" class="pl-10 block w-full rounded-md border-gray-300 shadow-sm" :class="{ 'bg-gray-100': !isCreating }" :readonly="!isCreating" required>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Color Name:</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
                                    <i class="fas fa-font"></i>
                                </span>
                                <input v-model="editForm.color_name" type="text" class="pl-10 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm" required>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Origin:</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
                                    <i class="fas fa-map-marker-alt"></i>
                                </span>
                                <input v-model="editForm.origin" type="text" class="pl-10 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm" required>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Color Group:</label>
                            <div class="relative flex">
                                <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500">
                                    <i class="fas fa-layer-group"></i>
                                </span>
                                <input v-model="editForm.color_group_id" type="text" class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none border border-gray-300 focus:ring-blue-500 focus:border-blue-500 text-sm" required>
                                <button type="button" @click="openColorGroupSelector" class="inline-flex items-center px-3 py-2 border border-l-0 border-gray-300 bg-blue-500 hover:bg-blue-600 text-white rounded-r-md transition-colors transform active:translate-y-px">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">CG Type:</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
                                    <i class="fas fa-tag"></i>
                                </span>
                                <input v-model="editForm.cg_type" type="text" class="pl-10 block w-full rounded-md border-gray-300 shadow-sm bg-gray-100 focus:ring-blue-500 focus:border-blue-500 text-sm" readonly>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">KG per M2:</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
                                    <i class="fas fa-weight"></i>
                                </span>
                                <input v-model="editForm.kg_per_m2" type="text" class="pl-10 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm">
                                <span class="text-xs text-gray-500 mt-1 block">Estimate KG per M2</span>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Note:</label>
                            <div class="border border-gray-300 rounded-md p-3 bg-gray-50 h-16 overflow-auto text-sm">
                                <p>Flexo Ink is estimated around 0.008 KG per M2</p>
                            </div>
                        </div>
                    </div>
                    <div class="flex justify-between mt-6 pt-4 border-t border-gray-200">
                        <button type="button" v-if="!isCreating" @click="deleteColor(editForm.color_id)" class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors text-sm transform active:translate-y-px">
                            <i class="fas fa-trash-alt mr-2"></i>Delete
                        </button>
                        <div v-else class="w-24"></div>
                        <div class="flex space-x-3">
                            <button type="button" @click="closeEditModal" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition-colors text-sm transform active:translate-y-px">
                                <i class="fas fa-times mr-2"></i>Cancel
                            </button>
                            <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors text-sm transform active:translate-y-px">
                                <i class="fas fa-save mr-2"></i>Save
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Color Group Selector Modal -->
    <div v-if="showColorGroupModal" class="fixed inset-0 z-[70] bg-black bg-opacity-50 flex items-center justify-center">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-2xl mx-auto transform transition-transform duration-300">
            <div class="flex items-center justify-between p-4 border-b border-gray-200 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-t-lg">
                <div class="flex items-center">
                    <div class="p-2 bg-white bg-opacity-30 rounded-lg mr-3">
                        <i class="fas fa-layer-group"></i>
                    </div>
                    <h3 class="text-xl font-semibold">Color Group Table</h3>
                </div>
                <button @click="showColorGroupModal = false" class="text-white hover:text-gray-200 transform active:translate-y-px">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div class="p-5 overflow-auto" style="max-height: calc(80vh - 130px);">
                <div class="mb-4">
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
                            <i class="fas fa-search"></i>
                        </span>
                        <input type="text" v-model="cgSearchQuery" placeholder="Search color groups..."
                            class="pl-10 pr-4 py-2 w-full border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 bg-gray-50">
                    </div>
                </div>
                
                <div class="overflow-x-auto rounded-lg border border-gray-200">
                    <table class="min-w-full divide-y divide-gray-200 table-fixed">
                        <thead class="bg-gray-50 sticky top-0">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/4">CG#</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-2/4">CG Name</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/4">CG Type</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200 text-xs">
                            <tr v-for="group in filteredColorGroups" :key="group.cg"
                                :class="['hover:bg-blue-50 cursor-pointer', selectedColorGroup && selectedColorGroup.cg === group.cg ? 'bg-blue-100' : '']"
                                @click="selectColorGroupRow(group)"
                                @dblclick="selectColorGroup(group)">
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
                
                <div class="mt-4 flex justify-center space-x-4">
                    <button @click="confirmColorGroupSelection" class="py-2 px-6 bg-blue-500 hover:bg-blue-600 text-white text-sm rounded-lg transform active:translate-y-px">
                        <i class="fas fa-check mr-2"></i>Select
                    </button>
                    <button @click="showColorGroupModal = false" class="py-2 px-6 bg-gray-300 hover:bg-gray-400 text-gray-800 text-sm rounded-lg transform active:translate-y-px">
                        <i class="fas fa-times mr-2"></i>Exit
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Loading Overlay -->
    <div v-if="saving" class="fixed inset-0 z-[80] bg-black bg-opacity-50 flex justify-center items-center">
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
import AppLayout from '@/Layouts/AppLayout.vue';
import axios from 'axios';
import ColorModal from '@/Components/color-modal.vue';

// Get the header from props
const props = defineProps({
    header: {
        type: String,
        default: 'Color Management'
    },
    colors: {
        type: Array,
        default: () => []
    },
    colorGroups: {
        type: Array,
        default: () => []
    }
});

const colors = ref(props.colors || []);
const colorGroups = ref(props.colorGroups || []);
const loading = ref(false);
const saving = ref(false);
const showModal = ref(false);
const showEditModal = ref(false);
const showColorGroupModal = ref(false);
const selectedRow = ref(null);
const selectedColorGroup = ref(null);
const searchQuery = ref('');
const cgSearchQuery = ref('');
const modalSearchQuery = ref('');
const sortOption = ref('colorId');
const editForm = ref({ 
    color_id: '', 
    color_name: '', 
    origin: '',
    color_group_id: '',
    cg_type: 'X-Flexo',
    kg_per_m2: '1.0000'
});
const isCreating = ref(false);
const notification = ref({ show: false, message: '', type: 'success' });

// Fetch colors from API
const fetchColors = async () => {
    loading.value = true;
    try {
        console.log('Fetching colors from API...');
        const response = await axios.get('/api/colors');
        console.log('API Response:', response);
        colors.value = response.data || [];
        console.log("Fetched colors:", colors.value.length, "items");
        console.log("First 3 colors:", colors.value.slice(0, 3));
    } catch (error) {
        console.error('Error fetching colors:', error);
        showNotification('Failed to load colors data', 'error');
    } finally {
        loading.value = false;
    }
};

// Manually refresh colors - can be called after operations
const refreshColors = async () => {
    console.log('Manual refresh of colors data triggered');
    await fetchColors();
    console.log('Colors data has been refreshed, count:', colors.value.length);
};

// Fetch color groups from API
const fetchColorGroups = async () => {
    try {
        const result = await axios.get('/api/color-groups');
        if (Array.isArray(result.data)) {
            colorGroups.value = result.data.map(group => ({
                cg: group.cg_id || group.cg,
                cg_name: group.cg_name,
                cg_type: group.cg_type
            }));
        } else {
            colorGroups.value = [];
            console.error('Unexpected data format:', result.data);
        }
    } catch (e) {
        console.error('Error fetching color groups:', e);
        colorGroups.value = [];
        showNotification('Failed to load color groups data', 'error');
    }
};

onMounted(() => {
    if (colors.value.length === 0) {
        fetchColors();
    }
    if (colorGroups.value.length === 0) {
        fetchColorGroups();
    }
});

// Watch for changes in search query to filter the data
watch(searchQuery, (newQuery) => {
    if (newQuery && colors.value.length > 0) {
        const foundColor = colors.value.find(color => 
            color.color_id.toLowerCase().includes(newQuery.toLowerCase()) ||
            color.color_name.toLowerCase().includes(newQuery.toLowerCase())
        );
        
        if (foundColor) {
            selectedRow.value = foundColor;
        }
    }
});

// Watch for modal opening to refresh data
watch(showModal, (isOpen) => {
    if (isOpen) {
        // Refresh colors data when modal opens
        fetchColors();
    }
});

// Filtered color groups based on search query
const filteredColorGroups = computed(() => {
    if (!cgSearchQuery.value) return colorGroups.value;
    
    const query = cgSearchQuery.value.toLowerCase();
    return colorGroups.value.filter(group => 
        group.cg.toLowerCase().includes(query) ||
        group.cg_name.toLowerCase().includes(query) ||
        group.cg_type.toLowerCase().includes(query)
    );
});

// Get color group name by ID
const getColorGroupName = (cgId) => {
    const group = colorGroups.value.find(g => g.cg === cgId);
    return group ? group.cg_name : '';
};

// Sort by Color ID
const sortByColorId = () => {
    sortOption.value = 'colorId';
    colors.value.sort((a, b) => {
        return a.color_id.localeCompare(b.color_id, undefined, {numeric: true});
    });
};

// Sort by Color Group + Color ID
const sortByCGAndColor = () => {
    sortOption.value = 'cgAndColor';
    colors.value.sort((a, b) => {
        if (a.color_group_id !== b.color_group_id) {
            return a.color_group_id.localeCompare(b.color_group_id, undefined, {numeric: true});
        }
        return a.color_id.localeCompare(b.color_id, undefined, {numeric: true});
    });
};

// Sort by CG Type + Color ID
const sortByCGTypeAndColor = () => {
    sortOption.value = 'cgTypeAndColor';
    colors.value.sort((a, b) => {
        if (a.cg_type !== b.cg_type) {
            return a.cg_type.localeCompare(b.cg_type);
        }
        return a.color_id.localeCompare(b.color_id, undefined, {numeric: true});
    });
};

const onColorSelected = (color) => {
    selectedRow.value = color;
    searchQuery.value = color.color_id;
    showModal.value = false;
    
    // Automatically open the edit modal for the selected row
    isCreating.value = false;
    editForm.value = { ...color };
    showEditModal.value = true;
};

const editSelectedRow = () => {
    if (selectedRow.value) {
        isCreating.value = false;
        editForm.value = { ...selectedRow.value };
        showEditModal.value = true;
    } else {
        showNotification('Please select a color first', 'error');
    }
};

const createNewColor = () => {
    isCreating.value = true;
    editForm.value = { 
        color_id: '', 
        color_name: '', 
        origin: '',
        color_group_id: '',
        cg_type: 'X-Flexo',
        kg_per_m2: '1.0000'
    };
    showEditModal.value = true;
};

const closeEditModal = () => {
    showEditModal.value = false;
    editForm.value = { 
        color_id: '', 
        color_name: '', 
        origin: '',
        color_group_id: '',
        cg_type: 'X-Flexo',
        kg_per_m2: '1.0000'
    };
    isCreating.value = false;
};

const openColorGroupSelector = () => {
    showColorGroupModal.value = true;
    selectedColorGroup.value = null;
    cgSearchQuery.value = '';
};

const selectColorGroupRow = (group) => {
    selectedColorGroup.value = group;
};

const confirmColorGroupSelection = () => {
    if (!selectedColorGroup.value) {
        showNotification('Please select a color group first', 'warning');
        return;
    }
    selectColorGroup(selectedColorGroup.value);
};

const selectColorGroup = (group) => {
    editForm.value.color_group_id = group.cg;
    editForm.value.cg_type = group.cg_type;
    showColorGroupModal.value = false;
};

const saveColorChanges = async () => {
    saving.value = true;
    
    try {
        let response;
        if (isCreating.value) {
            console.log('Sending POST request to create new color:', editForm.value);
            response = await axios.post('/api/colors', editForm.value);
            console.log('Response from create:', response.data);
            showNotification('Color added successfully!', 'success');
        } else {
            console.log('Sending PUT request to update color:', editForm.value);
            response = await axios.put(`/api/colors/${editForm.value.color_id}`, editForm.value);
            console.log('Response from update:', response.data);
            showNotification('Color updated successfully!', 'success');
        }
        
        // Refresh the colors data after save
        console.log('Refreshing colors data after save');
        await fetchColors();
        console.log('Colors refreshed, current count:', colors.value.length);
        
        showEditModal.value = false;
    } catch (error) {
        console.error('Error saving color:', error);
        console.error('Error response:', error.response?.data);
        const errorMessage = error.response?.data?.message || 'An unexpected error occurred';
        showNotification(`Error saving color: ${errorMessage}`, 'error');
    } finally {
        saving.value = false;
    }
};

const deleteColor = async (colorId) => {
    if (!confirm(`Are you sure you want to delete color "${colorId}"?`)) return;
    
    saving.value = true;
    
    try {
        await axios.delete(`/api/colors/${colorId}`);
        showNotification('Color deleted successfully!', 'success');
        await fetchColors();
        
        if (selectedRow.value && selectedRow.value.color_id === colorId) {
            selectedRow.value = null;
            searchQuery.value = '';
        }
        
        closeEditModal();
    } catch (error) {
        console.error('Error deleting color:', error);
        const errorMessage = error.response?.data?.message || 'An unexpected error occurred';
        showNotification(`Error deleting color: ${errorMessage}`, 'error');
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

// Filtered colors based on search query
const filteredColors = computed(() => {
    if (!modalSearchQuery.value) return colors.value;
    
    const query = modalSearchQuery.value.toLowerCase();
    return colors.value.filter(color => 
        color.color_id.toLowerCase().includes(query) ||
        color.color_name.toLowerCase().includes(query) ||
        color.color_group_id.toLowerCase().includes(query) ||
        (color.cg_type && color.cg_type.toLowerCase().includes(query)) ||
        (color.origin && color.origin.toLowerCase().includes(query))
    );
});

const selectColor = (color) => {
    selectedRow.value = color;
    searchQuery.value = color.color_id;
};

// Watch modal data changes
watch(() => colors.value.length, (newCount, oldCount) => {
    console.log(`Colors count changed from ${oldCount} to ${newCount}`);
});
</script>
