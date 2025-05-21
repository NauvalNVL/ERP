<template>
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-xl max-w-4xl w-full p-6 max-h-[90vh] flex flex-col">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Select Business Form</h3>
                <button @click="$emit('close')" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <!-- Search and Filter -->
            <div class="mb-4 flex">
                <div class="relative flex-1">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                    <input 
                        type="text" 
                        v-model="search" 
                        class="pl-10 pr-4 py-2 w-full border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Search by code, name or group..."
                        @keyup.enter="searchForms"
                    >
                </div>
                <button 
                    @click="searchForms" 
                    class="ml-2 px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                >
                    Search
                </button>
                <button 
                    @click="clearSearch" 
                    class="ml-2 px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2"
                >
                    Clear
                </button>
            </div>
            
            <!-- Loading State -->
            <div v-if="loading" class="flex-1 flex justify-center items-center py-12">
                <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-500"></div>
            </div>
            
            <!-- Results Table -->
            <div v-else class="overflow-auto flex-1">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Form Code
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Form Name
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Group
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                ISO Reference
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Action
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr v-if="businessForms.length === 0">
                            <td colspan="5" class="px-4 py-4 text-center text-gray-500">
                                No business forms found matching your search criteria.
                            </td>
                        </tr>
                        <tr v-for="(bform, index) in businessForms" :key="bform.id"
                            :class="{'bg-blue-50': index % 2 === 0}"
                            class="hover:bg-blue-100 cursor-pointer"
                            @click="selectForm(bform)">
                            <td class="px-4 py-3 whitespace-nowrap text-sm">{{ bform.bf_code }}</td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm">{{ bform.bf_name }}</td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm">{{ bform.bf_group }}</td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm">{{ bform.bf_iso || 'N/A' }}</td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm">
                                <button 
                                    @click.stop="selectForm(bform)" 
                                    class="text-blue-600 hover:text-blue-900 font-medium"
                                >
                                    Select
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div v-if="pagination && pagination.last_page > 1" class="mt-4 flex justify-between items-center">
                <div class="text-sm text-gray-500">
                    Showing {{ pagination.from }} to {{ pagination.to }} of {{ pagination.total }} results
                </div>
                <div class="flex space-x-1">
                    <button 
                        @click="goToPage(1)" 
                        :disabled="pagination.current_page === 1"
                        :class="{'bg-gray-200 cursor-not-allowed': pagination.current_page === 1, 'bg-blue-500 text-white hover:bg-blue-600': pagination.current_page !== 1}"
                        class="px-3 py-1 rounded"
                    >
                        <i class="fas fa-angle-double-left"></i>
                    </button>
                    <button 
                        @click="goToPage(pagination.current_page - 1)" 
                        :disabled="pagination.current_page === 1"
                        :class="{'bg-gray-200 cursor-not-allowed': pagination.current_page === 1, 'bg-blue-500 text-white hover:bg-blue-600': pagination.current_page !== 1}"
                        class="px-3 py-1 rounded"
                    >
                        <i class="fas fa-angle-left"></i>
                    </button>
                    
                    <template v-for="page in displayPages" :key="page">
                        <button 
                            @click="goToPage(page)" 
                            :class="{'bg-blue-700 text-white': pagination.current_page === page, 'bg-blue-500 text-white hover:bg-blue-600': pagination.current_page !== page}"
                            class="px-3 py-1 rounded"
                        >
                            {{ page }}
                        </button>
                    </template>
                    
                    <button 
                        @click="goToPage(pagination.current_page + 1)" 
                        :disabled="pagination.current_page === pagination.last_page"
                        :class="{'bg-gray-200 cursor-not-allowed': pagination.current_page === pagination.last_page, 'bg-blue-500 text-white hover:bg-blue-600': pagination.current_page !== pagination.last_page}"
                        class="px-3 py-1 rounded"
                    >
                        <i class="fas fa-angle-right"></i>
                    </button>
                    <button 
                        @click="goToPage(pagination.last_page)" 
                        :disabled="pagination.current_page === pagination.last_page"
                        :class="{'bg-gray-200 cursor-not-allowed': pagination.current_page === pagination.last_page, 'bg-blue-500 text-white hover:bg-blue-600': pagination.current_page !== pagination.last_page}"
                        class="px-3 py-1 rounded"
                    >
                        <i class="fas fa-angle-double-right"></i>
                    </button>
                </div>
            </div>
            
            <!-- Modal Footer -->
            <div class="flex justify-end mt-4 pt-4 border-t border-gray-200">
                <button 
                    @click="$emit('close')" 
                    class="px-4 py-2 bg-gray-300 text-gray-800 rounded-md hover:bg-gray-400 focus:outline-none mr-2"
                >
                    Cancel
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';

const props = defineProps({
    title: {
        type: String,
        default: 'Select Business Form'
    }
});

const emit = defineEmits(['close', 'select']);

const businessForms = ref([]);
const loading = ref(false);
const search = ref('');
const pagination = ref(null);

// Compute display pages for pagination
const displayPages = computed(() => {
    if (!pagination.value) return [];
    
    const current = pagination.value.current_page;
    const last = pagination.value.last_page;
    const delta = 2; // Number of pages to show before and after current page
    
    let pages = [];
    
    // Always include first page
    pages.push(1);
    
    // Calculate range of pages to show around current page
    const rangeStart = Math.max(2, current - delta);
    const rangeEnd = Math.min(last - 1, current + delta);
    
    // Add ellipsis after first page if needed
    if (rangeStart > 2) {
        pages.push('...');
    }
    
    // Add pages in range
    for (let i = rangeStart; i <= rangeEnd; i++) {
        pages.push(i);
    }
    
    // Add ellipsis before last page if needed
    if (rangeEnd < last - 1) {
        pages.push('...');
    }
    
    // Always include last page if it's not the first page
    if (last > 1) {
        pages.push(last);
    }
    
    return pages;
});

// Fetch business forms with optional search
const fetchBusinessForms = async (page = 1) => {
    loading.value = true;
    
    try {
        const query = search.value ? `?search=${encodeURIComponent(search.value)}&page=${page}` : `?page=${page}`;
        const response = await fetch(`/business-form-search${query}`, {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        
        if (!response.ok) {
            throw new Error('Failed to fetch business forms');
        }
        
        const data = await response.json();
        
        businessForms.value = data.data;
        pagination.value = {
            current_page: data.current_page,
            last_page: data.last_page,
            per_page: data.per_page,
            total: data.total,
            from: data.from,
            to: data.to
        };
    } catch (error) {
        console.error('Error fetching business forms:', error);
        businessForms.value = [];
    } finally {
        loading.value = false;
    }
};

// Search forms
const searchForms = () => {
    fetchBusinessForms(1);
};

// Clear search
const clearSearch = () => {
    search.value = '';
    fetchBusinessForms(1);
};

// Go to specific page
const goToPage = (page) => {
    if (page === '...') return;
    fetchBusinessForms(page);
};

// Select a form
const selectForm = (form) => {
    emit('select', form);
};

// Initialize component
onMounted(() => {
    fetchBusinessForms();
});
</script>

<style scoped>
.fade-enter-active, .fade-leave-active {
    transition: opacity 0.3s;
}
.fade-enter, .fade-leave-to {
    opacity: 0;
}
</style> 