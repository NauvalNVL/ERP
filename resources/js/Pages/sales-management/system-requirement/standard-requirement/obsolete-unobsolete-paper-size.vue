<template>
    <AppLayout :header="'Manage Paper Size Status'">
    <Head title="Manage Paper Size Status" />

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
                            <h1 class="text-lg sm:text-xl font-semibold leading-tight">Manage Paper Size Status</h1>
                            <p class="text-xs sm:text-sm text-emerald-100">Activate or deactivate paper sizes quickly and easily.</p>
                        </div>
                    </div>

                    <!-- Search and Filter Controls -->
                    <div class="relative w-full sm:w-72">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-emerald-100">
                            <i class="fas fa-search text-xs"></i>
                        </span>
                        <input
                            v-model="searchQuery"
                            type="text"
                            placeholder="Cari paper size (mm, inches)..."
                            class="block w-full rounded-md border border-gray-200 bg-white py-1.5 pl-9 pr-3 text-sm text-gray-900 placeholder-gray-400 focus:border-emerald-500 focus:outline-none focus:ring-1 focus:ring-emerald-500"
                        />
                    </div>
                </div>
            </div>

            <!-- Success/Error Messages -->
            <div
                v-if="notification.show"
                :class="[
                    notification.type === 'success'
                        ? 'bg-emerald-50 border-emerald-200 text-emerald-800'
                        : 'bg-red-50 border-red-200 text-red-800',
                    'px-4 py-3 mb-4 rounded-lg border text-sm shadow-sm'
                ]"
            >
                <span class="block">{{ notification.message }}</span>
            </div>

            <div class="bg-white shadow-sm rounded-xl border border-gray-200 overflow-hidden">
                <div class="px-4 py-2 sm:px-6 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 bg-white">
                    <div>
                        <h2 class="text-sm font-semibold text-gray-800">Paper Size List</h2>
                        <p class="text-xs text-gray-500">{{ filteredSizes.length }} of {{ paperSizes.length }} paper sizes</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <label class="text-xs font-medium text-gray-600">Status:</label>
                        <select
                            v-model="statusFilter"
                            class="py-1.5 px-2.5 text-xs border border-gray-300 rounded-md bg-white text-gray-700 focus:outline-none focus:ring-1 focus:ring-emerald-500 focus:border-emerald-500"
                        >
                            <option value="all">All</option>
                            <option value="active">Active Only</option>
                            <option value="obsolete">Obsolete Only</option>
                        </select>
                    </div>
                </div>

                <div class="relative">
                    <!-- Loading Indicator -->
                    <div v-if="loading" class="py-10 flex justify-center items-center">
                        <div class="w-8 h-8 border-4 border-emerald-500 border-t-transparent rounded-full animate-spin"></div>
                    </div>

                    <!-- Paper Sizes Table -->
                    <div v-else class="overflow-x-auto">
                        <table class="min-w-full table-auto divide-y divide-gray-200 text-sm">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Millimeter</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Inches</th>
                                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr v-for="size in filteredSizes" :key="size.id" class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ size.millimeter }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ size.inches }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                                        <span v-if="size.status === 'Act'" class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-emerald-100 text-emerald-800">
                                            <i class="fas fa-check-circle mr-1"></i> Active
                                        </span>
                                        <span v-else class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                            <i class="fas fa-times-circle mr-1"></i> Obsolete
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex justify-center">
                                            <button @click="toggleSizeStatus(size)" :disabled="isToggling"
                                                :class="[
                                                    size.status === 'Act'
                                                        ? 'text-red-600 hover:text-red-900 bg-red-100 hover:bg-red-200'
                                                        : 'text-emerald-600 hover:text-emerald-900 bg-emerald-100 hover:bg-emerald-200',
                                                    'transition-colors duration-150 ease-in-out focus:outline-none focus:ring-2 focus:ring-offset-2 px-3 py-1 rounded text-xs font-semibold inline-flex items-center justify-center'
                                                ]"
                                                :style="{ minWidth: '120px' }">
                                                <i :class="[size.status === 'Act' ? 'fas fa-toggle-off' : 'fas fa-toggle-on', 'mr-1']"></i>
                                                {{ size.status === 'Act' ? 'Mark Obsolete' : 'Mark Active' }}
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr v-if="filteredSizes.length === 0">
                                    <td colspan="4" class="px-6 py-4 text-center text-gray-500">No paper sizes found.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Loading Overlay -->
    <div v-if="isToggling" class="fixed inset-0 z-50 bg-black bg-opacity-50 flex justify-center items-center">
        <div class="bg-white p-4 rounded-lg shadow-lg text-center">
            <div class="w-12 h-12 border-4 border-solid border-emerald-500 border-t-transparent rounded-full animate-spin mx-auto mb-3"></div>
            <p>Updating status...</p>
        </div>
    </div>
    </AppLayout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

// Props from controller
const props = defineProps({
    paperSizes: {
        type: Array,
        default: () => []
    },
    pagination: {
        type: Object,
        default: () => ({
            currentPage: 1,
            perPage: 15,
            total: 0
        })
    },
    header: {
        type: String,
        default: 'Manage Paper Size Status'
    }
});

// Data
const paperSizes = ref(props.paperSizes || []);
const loading = ref(false);
const isToggling = ref(false);
const searchQuery = ref('');
const statusFilter = ref('all');
const notification = ref({
    show: false,
    message: '',
    type: 'success'
});

// Fetch paper sizes
const fetchPaperSizes = async () => {
    loading.value = true;
    
    try {
        const response = await fetch('/api/paper-sizes?all_status=1', {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        
        if (!response.ok) {
            throw new Error('Failed to fetch paper sizes');
        }
        
        const data = await response.json();
        paperSizes.value = data;
    } catch (error) {
        console.error('Error fetching paper sizes:', error);
        showNotification('Error loading paper sizes: ' + error.message, 'error');
    } finally {
        loading.value = false;
    }
};

// Filter paper sizes based on search query and status filter
const filteredSizes = computed(() => {
    let filtered = [...paperSizes.value];
    
    // Apply search filter
    if (searchQuery.value) {
        const query = searchQuery.value.toLowerCase();
        filtered = filtered.filter(size => 
            String(size.millimeter).includes(query) || 
            String(size.inches).includes(query)
        );
    }
    
    // Apply status filter
    if (statusFilter.value !== 'all') {
        const isAct = statusFilter.value === 'active';
        filtered = filtered.filter(size => 
            isAct ? size.status === 'Act' : size.status === 'Obs'
        );
    }
    
    return filtered;
});

// Toggle paper size status
const toggleSizeStatus = async (size) => {
    if (isToggling.value) return;
    
    const confirmMessage = `Are you sure you want to change the status for "${size.millimeter} mm"?`;
    if (!confirm(confirmMessage)) return;
    
    isToggling.value = true;
    
    try {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
        
        if (!csrfToken) {
            throw new Error('CSRF token not found');
        }
        
        const response = await fetch(`/api/paper-sizes/${size.id}/status`, {
            method: 'PUT',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        });
        
        if (!response.ok) {
            const errorData = await response.json().catch(() => ({}));
            throw new Error(errorData.message || 'Failed to toggle paper size status');
        }
        
        const result = await response.json();
        
        // Update the local state
        if (result.data) {
            const index = paperSizes.value.findIndex(s => s.id === size.id);
            if (index !== -1) {
                paperSizes.value[index] = result.data;
            }
        } else {
             // Fallback if no data returned
            size.status = (size.status === 'Act') ? 'Obs' : 'Act';
        }
        
        // Show success message
        const statusText = (size.status === 'Act') ? 'activated' : 'deactivated';
        showNotification(`Paper Size "${size.millimeter} mm" successfully ${statusText}`, 'success');
    } catch (error) {
        console.error('Error toggling paper size status:', error);
        showNotification('Error updating status: ' + error.message, 'error');
    } finally {
        isToggling.value = false;
    }
};

// Show notification
const showNotification = (message, type = 'success') => {
    notification.value = {
        show: true,
        message,
        type
    };
    
    // Hide notification after 3 seconds
    setTimeout(() => {
        notification.value.show = false;
    }, 3000);
};

// Load data on component mount
onMounted(() => {
    if (paperSizes.value.length === 0) {
        fetchPaperSizes();
    }
});
</script>
