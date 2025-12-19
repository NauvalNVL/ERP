<template>
    <AppLayout header="Manage Product Design Status">
        <Head title="Manage Product Design Status" />

        <div class="min-h-screen bg-gray-50 py-6 px-4 sm:px-6 lg:px-8">
            <div class="max-w-7xl mx-auto">
                <!-- Header Section -->
                <div class="bg-gradient-to-r from-green-600 to-green-700 text-white shadow-sm rounded-xl border border-green-700 mb-4">
                    <div class="px-4 py-3 sm:px-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                        <div class="flex items-center gap-3">
                            <div class="h-9 w-9 rounded-full bg-green-500 flex items-center justify-center">
                                <i class="fas fa-sync-alt text-white text-sm"></i>
                            </div>
                            <div>
                                <h2 class="text-lg sm:text-xl font-semibold leading-tight">
                                    Manage Product Design Status (Obsolete/Unobsolete)
                                </h2>
                                <p class="text-xs sm:text-sm text-green-100">
                                    Toggle the active status of product designs.
                                </p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2 text-xs text-green-100">
                            <i class="fas fa-info-circle text-sm"></i>
                            <span>Use search and status filter to find specific designs.</span>
                        </div>
                    </div>
                </div>

                <!-- Main Card -->
                <div class="bg-white shadow-sm rounded-xl border border-gray-200">
                    <div class="px-4 py-3 sm:px-6 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                        <div>
                            <h3 class="text-sm sm:text-base font-semibold text-gray-800">Product Design Status</h3>
                            <p class="text-xs text-gray-500">
                                Showing {{ filteredProductDesigns.length }} of {{ productDesigns.length }} product designs.
                            </p>
                        </div>
                    </div>

                    <div class="px-4 py-4 sm:px-6">
                        <!-- Success/Error Messages -->
                        <div v-if="notification.show" class="mb-3">
                            <div
                                :class="[
                                    'px-3 py-2 rounded-lg border text-xs sm:text-sm flex items-start gap-2',
                                    notification.type === 'success'
                                        ? 'bg-emerald-50 border-emerald-200 text-emerald-800'
                                        : 'bg-red-50 border-red-200 text-red-800'
                                ]"
                            >
                                <i
                                    class="fas"
                                    :class="notification.type === 'success' ? 'fa-check-circle mt-0.5' : 'fa-exclamation-circle mt-0.5'"
                                ></i>
                                <span>{{ notification.message }}</span>
                            </div>
                        </div>

                        <!-- Search and Filter Controls -->
                        <div class="mb-4 flex flex-col sm:flex-row sm:items-center gap-3">
                            <div class="flex-1 min-w-[220px]">
                                <label class="block text-xs font-medium text-gray-700 mb-1">Search</label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                                        <i class="fas fa-search text-xs"></i>
                                    </span>
                                    <input
                                        type="text"
                                        v-model="searchQuery"
                                        placeholder="Search product designs..."
                                        class="pl-9 pr-3 py-2 w-full border border-gray-300 rounded-md focus:ring-emerald-500 focus:border-emerald-500 bg-gray-50 text-sm"
                                    />
                                </div>
                            </div>
                            <div class="w-full sm:w-48">
                                <label class="block text-xs font-medium text-gray-700 mb-1">Status</label>
                                <select
                                    v-model="statusFilter"
                                    class="py-2 px-3 w-full border border-gray-300 rounded-md focus:ring-emerald-500 focus:border-emerald-500 text-sm bg-white"
                                >
                                    <option value="all">All statuses</option>
                                    <option value="active">Active only</option>
                                    <option value="obsolete">Obsolete only</option>
                                </select>
                            </div>
                        </div>

                        <!-- Loading Indicator -->
                        <div v-if="loading" class="my-8 flex justify-center">
                            <div class="w-10 h-10 border-4 border-solid border-emerald-500 border-t-transparent rounded-full animate-spin"></div>
                        </div>

                        <!-- Product Designs Table -->
                        <div v-else class="mt-2 overflow-hidden border border-gray-200 rounded-lg">
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200 bg-white">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col" class="px-4 py-2.5 text-left text-[11px] font-semibold text-gray-500 uppercase tracking-wider">Code</th>
                                            <th scope="col" class="px-4 py-2.5 text-left text-[11px] font-semibold text-gray-500 uppercase tracking-wider">Name</th>
                                            <th scope="col" class="px-4 py-2.5 text-left text-[11px] font-semibold text-gray-500 uppercase tracking-wider">Design Type</th>
                                            <th scope="col" class="px-4 py-2.5 text-center text-[11px] font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                                            <th scope="col" class="px-4 py-2.5 text-center text-[11px] font-semibold text-gray-500 uppercase tracking-wider">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-100">
                                        <tr
                                            v-for="design in filteredProductDesigns"
                                            :key="design.id"
                                            class="hover:bg-gray-50"
                                        >
                                            <td class="px-4 py-2.5 whitespace-nowrap text-xs font-mono text-gray-900">{{ design.pd_code }}</td>
                                            <td class="px-4 py-2.5 whitespace-nowrap text-xs sm:text-sm text-gray-700">{{ design.pd_name }}</td>
                                            <td class="px-4 py-2.5 whitespace-nowrap text-xs sm:text-sm text-gray-600">{{ design.pd_design_type }}</td>
                                            <td class="px-4 py-2.5 whitespace-nowrap text-xs text-center">
                                                <span
                                                    v-if="design.status === 'Act'"
                                                    class="px-2.5 py-1 inline-flex text-[11px] leading-4 font-semibold rounded-full bg-emerald-50 text-emerald-700 border border-emerald-100 items-center justify-center"
                                                >
                                                    <i class="fas fa-check-circle mr-1"></i>
                                                    Active
                                                </span>
                                                <span
                                                    v-else
                                                    class="px-2.5 py-1 inline-flex text-[11px] leading-4 font-semibold rounded-full bg-red-50 text-red-700 border border-red-100 items-center justify-center"
                                                >
                                                    <i class="fas fa-times-circle mr-1"></i>
                                                    Obsolete
                                                </span>
                                            </td>
                                            <td class="px-4 py-2.5 whitespace-nowrap text-xs sm:text-sm font-medium">
                                                <div class="flex justify-center">
                                                    <button
                                                        @click="toggleProductDesignStatus(design)"
                                                        :disabled="isToggling"
                                                        :class="[
                                                            design.status === 'Act'
                                                                ? 'text-red-600 hover:text-red-900 bg-red-50 hover:bg-red-100 border border-red-200'
                                                                : 'text-emerald-600 hover:text-emerald-900 bg-emerald-50 hover:bg-emerald-100 border border-emerald-200',
                                                            'transition-colors duration-150 ease-in-out focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-emerald-500 px-3 py-1 rounded-full text-[11px] sm:text-xs font-semibold inline-flex items-center justify-center disabled:opacity-60 disabled:cursor-not-allowed'
                                                        ]"
                                                        :style="{ minWidth: '120px' }"
                                                    >
                                                        <i :class="[design.status === 'Act' ? 'fas fa-toggle-off' : 'fas fa-toggle-on', 'mr-1']"></i>
                                                        {{ design.status === 'Act' ? 'Mark Obsolete' : 'Mark Active' }}
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr v-if="filteredProductDesigns.length === 0">
                                            <td colspan="5" class="px-4 py-3 text-center text-xs sm:text-sm text-gray-500">
                                                No product designs found.
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Loading Overlay -->
                <div v-if="isToggling" class="fixed inset-0 z-50 bg-black bg-opacity-30 flex justify-center items-start pt-32">
                    <div class="bg-white px-5 py-4 rounded-lg shadow-lg text-center border border-gray-200">
                        <div class="w-8 h-8 border-4 border-solid border-emerald-500 border-t-transparent rounded-full animate-spin mx-auto mb-2"></div>
                        <p class="text-sm text-gray-700">Updating status...</p>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { Head } from '@inertiajs/vue3';
import Swal from 'sweetalert2';

// Props from controller
const props = defineProps({
    productDesigns: {
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
        default: 'Manage Product Design Status'
    }
});

// Data
const productDesigns = ref(props.productDesigns || []);
const pagination = ref(props.pagination);
const loading = ref(false);
const isToggling = ref(false);
const searchQuery = ref('');
const statusFilter = ref('all');
const notification = ref({
    show: false,
    message: '',
    type: 'success'
});

// Fetch product designs with pagination
const fetchProductDesigns = async (page = 1) => {
    loading.value = true;
    
    try {
        const response = await fetch(`/api/product-designs?page=${page}&all_status=1`, {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        
        if (!response.ok) {
            throw new Error('Failed to fetch product designs');
        }
        
        const data = await response.json();
        
        if (data) {
            productDesigns.value = data;
            pagination.value = {
                currentPage: page,
                perPage: 15,
                total: data.length
            };
        }
    } catch (error) {
        console.error('Error fetching product designs:', error);
        showNotification('Error loading product designs: ' + error.message, 'error');
    } finally {
        loading.value = false;
    }
};

// Filter product designs based on search query and status filter
const filteredProductDesigns = computed(() => {
    let filtered = [...productDesigns.value];
    
    // Apply search filter
    if (searchQuery.value) {
        const query = searchQuery.value.toLowerCase();
        filtered = filtered.filter(design => 
            design.pd_code.toLowerCase().includes(query) || 
            design.pd_name.toLowerCase().includes(query) ||
            (design.pd_design_type && design.pd_design_type.toLowerCase().includes(query))
        );
    }
    
    // Apply status filter
    if (statusFilter.value !== 'all') {
        const targetStatus = statusFilter.value === 'active' ? 'Act' : 'Obs';
        filtered = filtered.filter(design => design.status === targetStatus);
    }
    
    return filtered;
});

// Toggle product design status
const toggleProductDesignStatus = async (design) => {
    if (isToggling.value) return;
    
    const confirmMessage = `Are you sure you want to change the status for "${design.pd_code} - ${design.pd_name}"?`;
    const confirmRes = await Swal.fire({
        title: 'Confirm Status Change?',
        text: confirmMessage,
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'OK',
        cancelButtonText: 'Cancel',
        reverseButtons: true,
        allowOutsideClick: false,
    });

    if (!confirmRes.isConfirmed) return;
    
    isToggling.value = true;
    
    try {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
        
        if (!csrfToken) {
            throw new Error('CSRF token not found');
        }
        
        const response = await fetch(`/api/product-designs/${design.id}/status`, {
            method: 'PUT',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        });
        
        if (!response.ok) {
            throw new Error('Failed to toggle product design status');
        }
        
        const result = await response.json();
        
        if (result.success) {
            // Update the local state
            design.status = (design.status === 'Act') ? 'Obs' : 'Act';
            
            // Show success message
            const statusText = (design.status === 'Act') ? 'activated' : 'deactivated';
            showNotification(`Product design "${design.pd_code}" successfully ${statusText}`, 'success');
        } else {
            throw new Error(result.message || 'Unknown error');
        }
    } catch (error) {
        console.error('Error toggling product design status:', error);
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
    fetchProductDesigns();
});
</script>
