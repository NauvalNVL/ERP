<template>
    <AppLayout :header="'Manage Salesperson Status'">
    <Head title="Manage Salesperson Status" />

    <!-- Header Section -->
    <div class="bg-gradient-to-r from-green-600 to-green-700 p-6 rounded-t-lg shadow-lg mb-6">
        <h2 class="text-2xl font-bold text-white mb-2 flex items-center">
            <i class="fas fa-sync-alt mr-3"></i> Manage Salesperson Status (Obsolete/Unobsolete)
        </h2>
        <p class="text-emerald-100">Toggle the active status of salespersons.</p>
    </div>

    <div class="bg-white rounded-b-lg shadow-lg p-6">
        <!-- Success/Error Messages -->
        <div v-if="notification.show" 
             :class="{
                'bg-green-100 border border-green-400 text-green-700': notification.type === 'success',
                'bg-red-100 border border-red-400 text-red-700': notification.type === 'error',
                'px-4 py-3 rounded relative mb-4': true
             }">
            <span class="block sm:inline">{{ notification.message }}</span>
        </div>

        <!-- Search and Filter Controls -->
        <div class="mb-6 flex flex-wrap items-center gap-4">
            <div class="flex-1 min-w-[300px]">
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
                        <i class="fas fa-search"></i>
                    </span>
                    <input type="text" v-model="searchQuery" placeholder="Search salespersons..."
                        class="pl-10 pr-4 py-2 w-full border border-gray-300 rounded-lg focus:ring-emerald-500 focus:border-emerald-500 bg-gray-50">
                </div>
            </div>
            <div>
                <select v-model="statusFilter" class="py-2 px-3 border border-gray-300 rounded-lg focus:ring-emerald-500 focus:border-emerald-500">
                    <option value="all">All Statuses</option>
                    <option value="active">Active Only</option>
                    <option value="obsolete">Obsolete Only</option>
                </select>
            </div>
        </div>

        <!-- Loading Indicator -->
        <div v-if="loading" class="my-8 flex justify-center">
            <div class="w-12 h-12 border-4 border-solid border-emerald-500 border-t-transparent rounded-full animate-spin"></div>
        </div>

        <!-- Salespersons Table -->
        <div v-else class="overflow-x-auto rounded-lg border border-gray-200 shadow-sm">
            <table class="min-w-full divide-y divide-gray-200 bg-white">
                <thead class="bg-gray-100">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Code</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sales Team</th>
                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr v-for="salesperson in filteredSalespersons" :key="salesperson.Code || salesperson.code" class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ salesperson.Code || salesperson.code }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ salesperson.Name || salesperson.name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ salesperson.Grup || salesperson.grup || 'N/A' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                            <span v-if="salesperson.is_active" class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                <i class="fas fa-check-circle mr-1"></i> Active
                            </span>
                            <span v-else class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                <i class="fas fa-times-circle mr-1"></i> Obsolete
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-center">
                            <button @click="toggleSalespersonStatus(salesperson)" :disabled="isToggling"
                                :class="[
                                    salesperson.is_active
                                        ? 'text-red-600 hover:text-red-900 bg-red-100 hover:bg-red-200'
                                        : 'text-green-600 hover:text-green-900 bg-green-100 hover:bg-green-200',
                                    'transition-colors duration-150 ease-in-out focus:outline-none focus:ring-2 focus:ring-offset-2 px-3 py-1 rounded text-xs font-semibold flex items-center justify-center'
                                ]"
                                :style="{ minWidth: '120px' }">
                                <i :class="[salesperson.is_active ? 'fas fa-toggle-off' : 'fas fa-toggle-on', 'mr-1']"></i>
                                {{ salesperson.is_active ? 'Mark Obsolete' : 'Mark Active' }}
                            </button>
                        </td>
                    </tr>
                    <tr v-if="filteredSalespersons.length === 0">
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">No salespersons found.</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Pagination Controls -->
        <div v-if="pagination.total > pagination.perPage" class="flex items-center justify-between mt-6">
            <div class="flex-1 flex justify-between items-center">
                <button 
                    @click="changePage(pagination.currentPage - 1)" 
                    :disabled="pagination.currentPage === 1"
                    :class="[
                        pagination.currentPage === 1 ? 'bg-gray-200 text-gray-500 cursor-not-allowed' : 'bg-green-600 text-white hover:bg-green-700',
                        'py-2 px-4 border border-transparent rounded-md text-sm font-medium focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500'
                    ]">
                    Previous
                </button>
                
                <span class="text-sm text-gray-700">
                    Page {{ pagination.currentPage }} of {{ Math.ceil(pagination.total / pagination.perPage) }}
                </span>
                
                <button 
                    @click="changePage(pagination.currentPage + 1)" 
                    :disabled="pagination.currentPage >= Math.ceil(pagination.total / pagination.perPage)"
                    :class="[
                        pagination.currentPage >= Math.ceil(pagination.total / pagination.perPage) ? 'bg-gray-200 text-gray-500 cursor-not-allowed' : 'bg-green-600 text-white hover:bg-green-700',
                        'py-2 px-4 border border-transparent rounded-md text-sm font-medium focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500'
                    ]">
                    Next
                </button>
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
    salespersons: {
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
        default: 'Manage Salesperson Status'
    }
});

// Data
const salespersons = ref(props.salespersons || []);
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

// Fetch salespersons with pagination
const fetchSalespersons = async (page = 1) => {
    loading.value = true;
    
    try {
        const response = await fetch(`/api/salesperson?page=${page}`, {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        
        if (!response.ok) {
            throw new Error('Failed to fetch salespersons');
        }
        
        const data = await response.json();
        
        if (data) {
            salespersons.value = data;
            pagination.value = {
                currentPage: page,
                perPage: 15,
                total: data.length
            };
        }
    } catch (error) {
        console.error('Error fetching salespersons:', error);
        showNotification('Error loading salespersons: ' + error.message, 'error');
    } finally {
        loading.value = false;
    }
};

// Filter salespersons based on search query and status filter
const filteredSalespersons = computed(() => {
    let filtered = [...salespersons.value];
    
    // Apply search filter
    if (searchQuery.value) {
        const query = searchQuery.value.toLowerCase();
        filtered = filtered.filter(salesperson => {
            const code = (salesperson.Code || salesperson.code || '').toLowerCase();
            const name = (salesperson.Name || salesperson.name || '').toLowerCase();
            const grup = (salesperson.Grup || salesperson.grup || '').toLowerCase();
            
            return code.includes(query) || name.includes(query) || grup.includes(query);
        });
    }
    
    // Apply status filter
    if (statusFilter.value !== 'all') {
        const isActive = statusFilter.value === 'active';
        filtered = filtered.filter(salesperson => salesperson.is_active === isActive);
    }
    
    return filtered;
});

// Toggle salesperson status
const toggleSalespersonStatus = async (salesperson) => {
    if (isToggling.value) return;
    
    const name = salesperson.Name || salesperson.name;
    const confirmMessage = `Are you sure you want to change the status for "${name}"?`;
    if (!confirm(confirmMessage)) return;
    
    isToggling.value = true;
    
    try {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
        
        if (!csrfToken) {
            throw new Error('CSRF token not found');
        }
        
        // Use Code as identifier since it's the primary key
        const code = salesperson.Code || salesperson.code;
        
        if (!code) {
            throw new Error('Salesperson code not found');
        }
        
        // Toggle the is_active property
        const updatedData = {
            is_active: !salesperson.is_active,
            status: !salesperson.is_active ? 'Act' : 'Obs'
        };
        
        const response = await fetch(`/api/salesperson/update/${code}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(updatedData)
        });
        
        if (!response.ok) {
            const errorData = await response.json().catch(() => ({ message: 'Unknown error' }));
            throw new Error(errorData.message || 'Failed to toggle salesperson status');
        }
        
        const result = await response.json();
        
        if (result.success) {
            // Update the local state
            salesperson.is_active = !salesperson.is_active;
            salesperson.status = salesperson.is_active ? 'Act' : 'Obs';
            
            // Show success message
            const statusText = salesperson.is_active ? 'activated' : 'deactivated';
            showNotification(`Salesperson "${name}" successfully ${statusText}`, 'success');
        } else {
            throw new Error(result.message || 'Failed to update status');
        }
    } catch (error) {
        console.error('Error toggling salesperson status:', error);
        showNotification('Error updating status: ' + error.message, 'error');
    } finally {
        isToggling.value = false;
    }
};

// Change page
const changePage = (page) => {
    if (page < 1 || page > Math.ceil(pagination.value.total / pagination.value.perPage)) {
        return;
    }
    
    fetchSalespersons(page);
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
    fetchSalespersons();
});
</script>
