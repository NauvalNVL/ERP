<template>
    <AppLayout :header="'Manage Finishing Status'">
    <Head title="Manage Finishing Status" />

    <!-- Header Section -->
    <div class="min-h-screen bg-gray-50 py-6 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <div class="bg-gradient-to-r from-green-600 to-green-700 text-white shadow-sm rounded-xl border border-green-700 mb-4">
                <div class="px-4 py-3 sm:px-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div class="flex items-center gap-3">
                        <div class="h-9 w-9 rounded-full bg-green-500 flex items-center justify-center">
                            <i class="fas fa-wand-magic-sparkles text-white text-sm"></i>
                        </div>
                        <div>
                            <h1 class="text-lg sm:text-xl font-semibold leading-tight">Manage Finishing Status</h1>
                            <p class="text-xs sm:text-sm text-green-100">Activate or deactivate finishings quickly and easily.</p>
                        </div>
                    </div>

                    <!-- Search and Filter Controls -->
                    <div class="relative w-full sm:w-72">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-green-100">
                            <i class="fas fa-search text-xs"></i>
                        </span>
                        <input
                            v-model="searchQuery"
                            type="text"
                            placeholder="Cari finishing (code, desc)..."
                            class="block w-full rounded-md border border-gray-200 bg-white py-1.5 pl-9 pr-3 text-sm text-gray-900 placeholder-gray-400 focus:border-green-500 focus:outline-none focus:ring-1 focus:ring-green-500"
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
                        <h2 class="text-sm font-semibold text-gray-800">Finishing List</h2>
                        <p class="text-xs text-gray-500">{{ filteredFinishings.length }} of {{ finishings.length }} finishings</p>
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

                    <!-- Finishings Table -->
                    <div v-else class="overflow-x-auto">
                        <table class="min-w-full table-auto divide-y divide-gray-200 text-sm">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Code</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>

                                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr v-for="finishing in filteredFinishings" :key="finishing.id" class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ finishing.code }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ finishing.description }}</td>

                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                                        <span v-if="finishing.status === 'Act'" class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-emerald-100 text-emerald-800">
                                            <i class="fas fa-check-circle mr-1"></i> Active
                                        </span>
                                        <span v-else class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                            <i class="fas fa-times-circle mr-1"></i> Obsolete
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex justify-center">
                                            <button @click="toggleFinishingStatus(finishing)" :disabled="isToggling"
                                                :class="[
                                                    finishing.status === 'Act'
                                                        ? 'text-red-600 hover:text-red-900 bg-red-100 hover:bg-red-200'
                                                        : 'text-emerald-600 hover:text-emerald-900 bg-emerald-100 hover:bg-emerald-200',
                                                    'transition-colors duration-150 ease-in-out focus:outline-none focus:ring-2 focus:ring-offset-2 px-3 py-1 rounded text-xs font-semibold inline-flex items-center justify-center'
                                                ]"
                                                :style="{ minWidth: '120px' }">
                                                <i :class="[finishing.status === 'Act' ? 'fas fa-toggle-off' : 'fas fa-toggle-on', 'mr-1']"></i>
                                                {{ finishing.status === 'Act' ? 'Mark Obsolete' : 'Mark Active' }}
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr v-if="filteredFinishings.length === 0">
                                    <td colspan="4" class="px-6 py-4 text-center text-gray-500">No finishings found.</td>
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
    finishings: {
        type: Array,
        default: () => []
    },
    header: {
        type: String,
        default: 'Manage Finishing Status'
    }
});

// Data
const finishings = ref(props.finishings || []);
const loading = ref(false);
const isToggling = ref(false);
const searchQuery = ref('');
const statusFilter = ref('all');
const notification = ref({
    show: false,
    message: '',
    type: 'success'
});

// Fetch finishings
const fetchFinishings = async () => {
    loading.value = true;
    
    try {
        const response = await fetch('/api/finishings?all_status=1', {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        
        if (!response.ok) {
            throw new Error('Failed to fetch finishings');
        }
        
        const data = await response.json();
        finishings.value = data;
    } catch (error) {
        console.error('Error fetching finishings:', error);
        showNotification('Error loading finishings: ' + error.message, 'error');
    } finally {
        loading.value = false;
    }
};

// Filter finishings based on search query and status filter
const filteredFinishings = computed(() => {
    let filtered = [...finishings.value];
    
    // Apply search filter
    if (searchQuery.value) {
        const query = searchQuery.value.toLowerCase();
        filtered = filtered.filter(finishing => 
            (finishing.code && finishing.code.toLowerCase().includes(query)) || 
            (finishing.description && finishing.description.toLowerCase().includes(query))
        );
    }
    
    // Apply status filter
    if (statusFilter.value !== 'all') {
        const isAct = statusFilter.value === 'active';
        filtered = filtered.filter(finishing => 
            isAct ? finishing.status === 'Act' : finishing.status === 'Obs'
        );
    }
    
    return filtered;
});

// Toggle finishing status
const toggleFinishingStatus = async (finishing) => {
    if (isToggling.value) return;
    
    const confirmMessage = `Are you sure you want to change the status for "${finishing.description}"?`;
    if (!confirm(confirmMessage)) return;
    
    isToggling.value = true;
    
    try {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
        
        if (!csrfToken) {
            throw new Error('CSRF token not found');
        }
        
        const response = await fetch(`/api/finishings/${finishing.code}/status`, {
            method: 'PUT',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        });
        
        if (!response.ok) {
            const errorData = await response.json().catch(() => ({}));
            throw new Error(errorData.message || 'Failed to toggle finishing status');
        }
        
        const result = await response.json();
        
        // Update the local state
        if (result.data) {
            const index = finishings.value.findIndex(f => f.id === finishing.id);
            if (index !== -1) {
                finishings.value[index] = result.data;
                // Update the finishing reference to show correct status in notification
                finishing.status = result.data.status;
            }
        } else {
            // Fallback if no data returned
            finishing.status = (finishing.status === 'Act') ? 'Obs' : 'Act';
        }
        
        // Show success message using the updated status
        const statusText = (finishing.status === 'Act') ? 'activated' : 'deactivated';
        showNotification(`Finishing "${finishing.description}" successfully ${statusText}`, 'success');
    } catch (error) {
        console.error('Error toggling finishing status:', error);
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
    if (finishings.value.length === 0) {
        fetchFinishings();
    }
});
</script>
