<template>
    <AppLayout :header="'Define Color'">
    <Head title="Define Color" />

    <!-- Hidden form with CSRF token -->
    <form ref="csrfForm" class="hidden">
        @csrf
    </form>

    <!-- Header Section -->
    <div class="bg-gradient-to-r from-green-600 to-green-700 p-6 sm:p-7 md:p-8 rounded-t-2xl shadow-lg">
        <h2 class="text-2xl md:text-3xl font-bold text-white mb-2 flex items-center">
            <span class="inline-flex items-center justify-center w-10 h-10 md:w-11 md:h-11 rounded-xl bg-white/15 mr-3">
                <i class="fas fa-palette text-lg md:text-xl"></i>
            </span>
            Define Color
        </h2>
        <p class="text-emerald-100 text-sm md:text-base">Define colors for specific product categories</p>
    </div>

    <div class="bg-gradient-to-br from-slate-50 via-white to-emerald-50 rounded-b-2xl shadow-lg p-4 sm:p-6 mb-6">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8">
            <!-- Left Column -->
            <div class="lg:col-span-2">
                <div class="bg-white/90 backdrop-blur-sm p-5 sm:p-6 rounded-2xl shadow-md border border-emerald-100">
                    <div class="flex items-center mb-5 sm:mb-6 pb-3 border-b border-gray-100">
                        <div class="p-2.5 bg-emerald-500 rounded-xl mr-3 text-white">
                            <i class="fas fa-edit"></i>
                        </div>
                        <div>
                            <h3 class="text-lg sm:text-xl font-semibold text-slate-800">Define Color</h3>
                            <p class="text-xs sm:text-sm text-slate-500">Search, create, and maintain your colors</p>
                        </div>
                    </div>

                    <!-- Search Section -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-6">
                        <div class="col-span-2">
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Color Code</label>
                            <div class="relative flex">
                                <span class="inline-flex items-center px-3 rounded-l-xl border border-r-0 border-gray-200 bg-slate-50 text-slate-500">
                                    <i class="fas fa-palette"></i>
                                </span>
                                <input type="text" v-model="searchQuery" class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none border border-gray-200 focus:ring-emerald-500 focus:border-emerald-500 text-slate-800 placeholder-slate-400 text-sm sm:text-base transition-colors" placeholder="Search or type color code">
                                <button type="button" @click="showModal = true" class="inline-flex items-center px-3 py-2 border border-l-0 border-emerald-500 bg-emerald-500 hover:bg-emerald-600 text-white rounded-r-xl text-sm transition-colors transform active:translate-y-px">
                                    <i class="fas fa-table"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-span-1">
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Action</label>
                            <button type="button" @click="createNewColor" class="w-full flex items-center justify-center px-4 py-2 rounded-xl bg-gradient-to-r from-emerald-500 to-green-600 hover:from-emerald-600 hover:to-green-700 text-white text-sm font-semibold shadow-md transform active:translate-y-px">
                                <i class="fas fa-plus-circle mr-2"></i>
                                Add New
                            </button>
                        </div>
                    </div>
                    <!-- Data Status Information -->
                    <div v-if="loading" class="mt-4 bg-amber-50 border border-amber-200 p-3 rounded-xl flex items-center space-x-3 text-sm">
                        <div class="flex items-center">
                            <div class="mr-3 animate-spin rounded-full h-6 w-6 border-2 border-amber-300 border-t-amber-600"></div>
                            <p class="font-medium text-amber-800">Loading color data...</p>
                        </div>
                    </div>
                    <div v-else-if="colors.length === 0" class="mt-4 bg-amber-50 border border-amber-200 p-3 rounded-xl">
                        <p class="text-sm font-semibold text-amber-800">No color data available.</p>
                        <p class="text-xs text-amber-700 mt-1">Data will be automatically loaded when available.</p>
                    </div>
                    <div v-else class="mt-4 bg-emerald-50 border border-emerald-200 p-3 rounded-xl">
                        <p class="text-sm font-semibold text-emerald-800">Data available: {{ colors.length }} colors found.</p>
                        <p v-if="selectedRow" class="text-xs text-emerald-700 mt-1">
                            Selected: <span class="font-semibold">{{ selectedRow.color_id }}</span> - {{ selectedRow.color_name }} ({{ selectedRow.cg_type }})
                        </p>
                    </div>
                </div>
            </div>
            <!-- Right Column - Quick Info -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Color Info Card -->
                <div class="bg-emerald-50/80 backdrop-blur-sm p-5 rounded-2xl shadow-sm border border-emerald-100">
                    <div class="flex items-center mb-4 pb-2 border-b border-emerald-100">
                        <div class="p-2.5 bg-emerald-500 rounded-xl mr-3">
                            <i class="fas fa-info-circle text-white"></i>
                        </div>
                        <h3 class="text-base sm:text-lg font-semibold text-emerald-900">Color Information</h3>
                    </div>

                    <div class="space-y-4">
                        <div class="p-4 bg-white/80 rounded-xl border border-emerald-100">
                            <h4 class="text-xs font-semibold text-emerald-700 uppercase tracking-wider mb-2">Instructions</h4>
                            <ul class="list-disc pl-5 text-xs sm:text-sm text-slate-600 space-y-1">
                                <li>Color code must be unique and cannot be changed</li>
                                <li>Use the <span class="font-medium">search</span> button to select a color</li>
                                <li>CG type determines the color characteristics</li>
                                <li>Any changes must be saved</li>
                            </ul>
                        </div>

                        <div class="p-4 bg-sky-50 rounded-xl border border-sky-100">
                            <h4 class="text-xs font-semibold text-sky-800 uppercase tracking-wider mb-2">Common Color Groups</h4>
                            <div class="grid grid-cols-2 gap-2 text-xs sm:text-sm">
                                <div class="flex items-center">
                                    <span class="w-7 h-7 flex items-center justify-center bg-black text-white rounded-full font-bold mr-2">01</span>
                                    <span>Black</span>
                                </div>
                                <div class="flex items-center">
                                    <span class="w-7 h-7 flex items-center justify-center bg-gray-200 text-gray-800 rounded-full font-bold mr-2">02</span>
                                    <span>White</span>
                                </div>
                                <div class="flex items-center">
                                    <span class="w-7 h-7 flex items-center justify-center bg-red-500 text-white rounded-full font-bold mr-2">03</span>
                                    <span>Red</span>
                                </div>
                                <div class="flex items-center">
                                    <span class="w-7 h-7 flex items-center justify-center bg-blue-500 text-white rounded-full font-bold mr-2">04</span>
                                    <span>Blue</span>
                                </div>
                                <div class="flex items-center">
                                    <span class="w-7 h-7 flex items-center justify-center bg-green-500 text-white rounded-full font-bold mr-2">05</span>
                                    <span>Green</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Links -->
                <div class="bg-white/90 backdrop-blur-sm p-5 rounded-2xl shadow-sm border border-violet-100">
                    <div class="flex items-center mb-4 pb-2 border-b border-violet-100">
                        <div class="p-2.5 bg-violet-500 rounded-xl mr-3">
                            <i class="fas fa-link text-white"></i>
                        </div>
                        <h3 class="text-base sm:text-lg font-semibold text-slate-800">Quick Links</h3>
                    </div>

                    <div class="grid grid-cols-1 gap-3">
                        <Link href="/color-group" class="flex items-center p-3 bg-purple-50 rounded-xl hover:bg-purple-100 transition-colors border border-purple-100">
                            <div class="p-2.5 bg-purple-500 rounded-full mr-3">
                                <i class="fas fa-layer-group text-white text-sm"></i>
                            </div>
                            <div>
                                <p class="font-medium text-purple-900 text-sm">Color Groups</p>
                                <p class="text-xs text-purple-700">Manage color groups</p>
                            </div>
                        </Link>

                        <Link href="/finishing" class="flex items-center p-3 bg-sky-50 rounded-xl hover:bg-sky-100 transition-colors border border-sky-100">
                            <div class="p-2.5 bg-sky-500 rounded-full mr-3">
                                <i class="fas fa-th-list text-white text-sm"></i>
                            </div>
                            <div>
                                <p class="font-medium text-sky-900 text-sm">Finishings</p>
                                <p class="text-xs text-sky-700">Manage finishings</p>
                            </div>
                        </Link>

                        <Link href="/color/view-print" class="flex items-center p-3 bg-emerald-50 rounded-xl hover:bg-emerald-100 transition-colors border border-emerald-100">
                            <div class="p-2.5 bg-emerald-500 rounded-full mr-3">
                                <i class="fas fa-print text-white text-sm"></i>
                            </div>
                            <div>
                                <p class="font-medium text-emerald-900 text-sm">Print List</p>
                                <p class="text-xs text-emerald-700">Print color list</p>
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
            <div class="flex items-center justify-between p-4 border-b border-gray-200 bg-gradient-to-r from-green-600 to-green-700 text-white rounded-t-lg">
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
                                <input v-model="editForm.color_name" type="text" class="pl-10 block w-full rounded-md border-gray-300 shadow-sm focus:ring-emerald-500 focus:border-emerald-500 text-sm" required>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Color Group:</label>
                            <div class="relative flex">
                                <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500">
                                    <i class="fas fa-layer-group"></i>
                                </span>
                                <input v-model="editForm.color_group_id" type="text" class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none border border-gray-300 focus:ring-emerald-500 focus:border-emerald-500 text-sm" required>
                                <button type="button" @click="openColorGroupSelector" class="inline-flex items-center px-3 py-2 border border-l-0 border-emerald-500 bg-emerald-500 hover:bg-emerald-600 text-white rounded-r-md transition-colors transform active:translate-y-px">
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
                                <input
                                    v-model="editForm.cg_type"
                                    type="text"
                                    class="pl-10 block w-full rounded-md border-gray-300 shadow-sm focus:ring-emerald-500 focus:border-emerald-500 text-sm"
                                >
                            </div>
                        </div>
                    </div>
                    <div class="flex justify-between mt-6 pt-4 border-t border-gray-200">
                        <button type="button" v-if="!isCreating" @click="obsoleteColor(editForm.color_id)" class="px-4 py-2 bg-orange-500 text-white rounded-lg hover:bg-orange-600 transition-colors text-sm transform active:translate-y-px">
                            <i class="fas fa-ban mr-2"></i>Obsolete
                        </button>
                        <div v-else class="w-24"></div>
                        <div class="flex space-x-3">
                            <button type="button" @click="closeEditModal" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition-colors text-sm transform active:translate-y-px">
                                <i class="fas fa-times mr-2"></i>Cancel
                            </button>
                            <button type="submit" class="px-4 py-2 bg-gradient-to-r from-emerald-500 to-green-600 text-white rounded-lg hover:from-emerald-600 hover:to-green-700 transition-colors text-sm transform active:translate-y-px">
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
            <div class="flex items-center justify-between p-4 border-b border-gray-200 bg-gradient-to-r from-green-600 to-green-700 text-white rounded-t-lg">
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
                            class="pl-10 pr-4 py-2 w-full border border-gray-300 rounded-lg focus:ring-emerald-500 focus:border-emerald-500 bg-gray-50">
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
                                :class="['hover:bg-emerald-50 cursor-pointer', selectedColorGroup && selectedColorGroup.cg === group.cg ? 'bg-emerald-100' : '']"
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

    // Debug log
    console.log('CSRF Token acquired:', token ? 'Yes' : 'No');

    if (!token) {
        console.error('Failed to acquire CSRF token from any source');
    }

    return token || '';
};

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
const sortKey = ref('color_id');
const sortAsc = ref(true);
const editForm = ref({
    color_id: '',
    color_name: '',
    color_group_id: '',
    cg_type: 'X-Flexo'
});
const isCreating = ref(false);
const notification = ref({ show: false, message: '', type: 'success' });

// Fetch colors from API
const fetchColors = async () => {
    loading.value = true;
    try {
        const response = await fetch('/api/colors', {
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

        // Handle different response formats
        if (Array.isArray(data)) {
            // Direct array response
            colors.value = data;
        } else if (data.colors && Array.isArray(data.colors)) {
            // Object with colors array
            colors.value = data.colors;
        } else if (data.data && Array.isArray(data.data)) {
            // Nested data array
            colors.value = data.data;
        } else {
            colors.value = [];
            console.error('Unexpected data format:', data);
        }
    } catch (error) {
        console.error('Error fetching colors:', error);
        showNotification('Failed to load colors data', 'error');
        colors.value = [];
    } finally {
        loading.value = false;
    }
};

// Manually refresh colors - can be called after operations
const refreshColors = async () => {
    await fetchColors();
};

// Fetch color groups from API
const fetchColorGroups = async () => {
    try {
        const response = await fetch('/api/color-groups', {
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

        // Handle different response formats
        let groupsArray = [];
        if (Array.isArray(data)) {
            // Direct array response
            groupsArray = data;
        } else if (data.color_groups && Array.isArray(data.color_groups)) {
            // Object with color_groups array
            groupsArray = data.color_groups;
        } else if (data.data && Array.isArray(data.data)) {
            // Nested data array
            groupsArray = data.data;
        } else {
            colorGroups.value = [];
            console.error('Unexpected data format:', data);
            return;
        }

        // Transform the data
        colorGroups.value = groupsArray.map(group => ({
            cg: group.cg_id || group.cg,
            cg_name: group.cg_name,
            cg_type: group.cg_type
        }));
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

const sortTable = (key) => {
    if (sortKey.value === key) {
        sortAsc.value = !sortAsc.value;
    } else {
        sortKey.value = key;
        sortAsc.value = true;
    }
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
        color_group_id: '',
        cg_type: 'X-Flexo'
    };
    showEditModal.value = true;
};

const closeEditModal = () => {
    showEditModal.value = false;
    editForm.value = {
        color_id: '',
        color_name: '',
        color_group_id: '',
        cg_type: 'X-Flexo'
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
    // Validate form
    if (!editForm.value.color_id) {
        showNotification('Color code is required', 'error');
        return;
    }

    if (!editForm.value.color_name) {
        showNotification('Color name is required', 'error');
        return;
    }

    if (!editForm.value.color_group_id) {
        showNotification('Color group is required', 'error');
        return;
    }

    saving.value = true;

    try {
        const csrfToken = getCsrfToken();

        // Different API call for create vs update
        let url = isCreating.value ? '/api/colors' : `/api/colors/${editForm.value.color_id}`;
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
            credentials: 'same-origin' // Include cookies in the request
        });

        if (!response.ok) {
            const errorData = await response.json();
            throw new Error(errorData.message || 'Error saving color');
        }

        const result = await response.json();

        // Update the local data
        await fetchColors();

        // Show success notification
        if (isCreating.value) {
            showNotification('Color created successfully', 'success');
            // Find and select the newly created color
            const newColor = colors.value.find(c => c.color_id === editForm.value.color_id);
            if (newColor) {
                selectedRow.value = newColor;
                searchQuery.value = newColor.color_id;
            }
        } else {
            showNotification('Color updated successfully', 'success');
        }

        // Close the edit modal
        closeEditModal();
    } catch (error) {
        console.error('Error saving color:', error);
        showNotification('Error: ' + error.message, 'error');
    } finally {
        saving.value = false;
    }
};

const obsoleteColor = async (colorId) => {
    if (!colorId) {
        showNotification('No color selected', 'error');
        return;
    }

    if (!confirm(`Are you sure you want to obsolete color "${colorId}"? This will hide it from selection and tables.`)) {
        return;
    }

    saving.value = true;

    try {
        const csrfToken = getCsrfToken();

        const response = await fetch(`/api/colors/${colorId}/status`, {
            method: 'PUT',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            credentials: 'same-origin' // Include cookies in the request
        });

        const result = await response.json();

        if (!response.ok || !result.success) {
            throw new Error(result.message || 'Error obsoleting color');
        }

        // Refresh colors so obsolete color disappears from Define Color and modal
        await fetchColors();

        if (selectedRow.value && selectedRow.value.color_id === colorId) {
            selectedRow.value = null;
            searchQuery.value = '';
        }

        closeEditModal();

        showNotification(`Color "${colorId}" has been marked as obsolete successfully.`, 'success');
    } catch (error) {
        console.error('Error obsoleting color:', error);
        showNotification(`Error obsoleting color: ${error.message}`, 'error');
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
        (color.cg_type && color.cg_type.toLowerCase().includes(query))
    );
});

const selectColor = (color) => {
    selectedRow.value = color;
    searchQuery.value = color.color_id;
};

</script>
