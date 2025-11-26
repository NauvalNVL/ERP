<template>
    <AppLayout :header="'Manage Color Status'">
    <Head title="Manage Color Status" />

    <div class="bg-gradient-to-r from-green-600 to-green-700 p-6 rounded-t-lg shadow-lg mb-6">
        <h2 class="text-2xl font-bold text-white mb-2 flex items-center">
            <i class="fas fa-sync-alt mr-3"></i> Manage Color Status (Obsolete/Unobsolete)
        </h2>
        <p class="text-emerald-100">Toggle the active status of colors.</p>
    </div>

    <div class="bg-white rounded-b-lg shadow-lg p-6">
        <div v-if="notification.show" 
             :class="{
                'bg-green-100 border border-green-400 text-green-700': notification.type === 'success',
                'bg-red-100 border border-red-400 text-red-700': notification.type === 'error',
                'px-4 py-3 rounded relative mb-4': true
             }">
            <span class="block sm:inline">{{ notification.message }}</span>
        </div>

        <div class="mb-6 flex flex-wrap items-center gap-4">
            <div class="flex-1 min-w-[300px]">
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
                        <i class="fas fa-search"></i>
                    </span>
                    <input type="text" v-model="searchQuery" placeholder="Search colors..."
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

        <div v-if="loading" class="my-8 flex justify-center">
            <div class="w-12 h-12 border-4 border-solid border-emerald-500 border-t-transparent rounded-full animate-spin"></div>
        </div>

        <div v-else class="overflow-x-auto rounded-lg border border-gray-200 shadow-sm">
            <table class="min-w-full divide-y divide-gray-200 bg-white">
                <thead class="bg-gray-100">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Color Code</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Color Name</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Group</th>
                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr v-for="color in filteredColors" :key="color.color_code" class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ color.color_code }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ color.color_name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ color.group_name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                            <span v-if="color.is_active" class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                <i class="fas fa-check-circle mr-1"></i> Active
                            </span>
                            <span v-else class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                <i class="fas fa-times-circle mr-1"></i> Obsolete
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-center">
                            <button @click="toggleColorStatus(color)" :disabled="isToggling"
                                :class="[
                                    color.is_active
                                        ? 'text-red-600 hover:text-red-900 bg-red-100 hover:bg-red-200'
                                        : 'text-green-600 hover:text-green-900 bg-green-100 hover:bg-green-200',
                                    'transition-colors duration-150 ease-in-out focus:outline-none focus:ring-2 focus:ring-offset-2 px-3 py-1 rounded text-xs font-semibold flex items-center justify-center'
                                ]"
                                :style="{ minWidth: '120px' }">
                                <i :class="[color.is_active ? 'fas fa-toggle-off' : 'fas fa-toggle-on', 'mr-1']"></i>
                                {{ color.is_active ? 'Mark Obsolete' : 'Mark Active' }}
                            </button>
                        </td>
                    </tr>
                    <tr v-if="filteredColors.length === 0">
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">No colors found.</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

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

const props = defineProps({
    colors: { type: Array, default: () => [] },
    pagination: { type: Object, default: () => ({ currentPage: 1, perPage: 15, total: 0 }) },
    header: { type: String, default: 'Manage Color Status' }
});

const colors = ref(props.colors || []);
const loading = ref(false);
const isToggling = ref(false);
const searchQuery = ref('');
const statusFilter = ref('all');
const notification = ref({ show: false, message: '', type: 'success' });

const fetchColors = async () => {
    loading.value = true;
    console.log('Fetching colors from API...');
    try {
        const response = await fetch('/api/colors', {
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
        });
        console.log('Response status:', response.status);
        if (!response.ok) {
            const errorText = await response.text();
            console.error('API error:', errorText);
            throw new Error('Failed to fetch colors');
        }
        const data = await response.json();
        console.log('Received data:', data);
        colors.value = Array.isArray(data) ? data : [];
        console.log('Colors loaded:', colors.value.length);
    } catch (error) {
        console.error('Fetch error:', error);
        showNotification('Error loading colors: ' + error.message, 'error');
        colors.value = [];
    } finally {
        loading.value = false;
        console.log('Loading complete, loading state:', loading.value);
    }
};

const filteredColors = computed(() => {
    let filtered = [...colors.value];
    if (searchQuery.value) {
        const query = searchQuery.value.toLowerCase();
        filtered = filtered.filter(color => 
            (color.color_code && color.color_code.toLowerCase().includes(query)) || 
            (color.color_name && color.color_name.toLowerCase().includes(query)) ||
            (color.group_name && color.group_name.toLowerCase().includes(query))
        );
    }
    if (statusFilter.value !== 'all') {
        const isActive = statusFilter.value === 'active';
        filtered = filtered.filter(color => color.is_active === isActive);
    }
    return filtered;
});

const toggleColorStatus = async (color) => {
    if (isToggling.value) return;
    if (!confirm(`Are you sure you want to change the status for "${color.color_name}"?`)) return;
    
    isToggling.value = true;
    try {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
        if (!csrfToken) throw new Error('CSRF token not found');
        
        const response = await fetch(`/api/colors/${color.color_code}`, {
            method: 'PUT',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ is_active: !color.is_active })
        });
        
        if (!response.ok) throw new Error('Failed to toggle color status');
        
        color.is_active = !color.is_active;
        const statusText = color.is_active ? 'activated' : 'deactivated';
        showNotification(`Color "${color.color_name}" successfully ${statusText}`, 'success');
    } catch (error) {
        showNotification('Error updating status: ' + error.message, 'error');
    } finally {
        isToggling.value = false;
    }
};

const showNotification = (message, type = 'success') => {
    notification.value = { show: true, message, type };
    setTimeout(() => { notification.value.show = false; }, 3000);
};

// Data is loaded from props, no need to fetch on mount
// onMounted(() => { fetchColors(); });
</script>
