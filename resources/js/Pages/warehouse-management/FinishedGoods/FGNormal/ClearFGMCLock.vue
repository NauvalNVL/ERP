<template>
    <AppLayout :header="'Clear FGMC Lock'">
        <div class="bg-gradient-to-r from-purple-600 via-violet-600 to-indigo-600 p-6 rounded-t-lg shadow-lg overflow-hidden relative">
            <div class="flex items-center">
                <div class="bg-gradient-to-br from-purple-500 to-violet-600 p-3 rounded-lg shadow-inner flex items-center justify-center relative overflow-hidden mr-4">
                    <i class="fas fa-unlock-alt text-white text-2xl z-10"></i>
                </div>
                <div>
                    <h2 class="text-2xl md:text-3xl font-bold text-white mb-1 text-shadow">Clear FGMC Lock</h2>
                    <p class="text-purple-100 max-w-2xl">Manage and clear locks on finished goods movement control</p>
                </div>
            </div>
        </div>

        <div class="bg-white shadow-md rounded-b-lg">
            <div class="p-6">
                <!-- Search and Filter Section -->
                <div class="bg-gray-50 p-4 rounded-lg border border-gray-200 mb-6">
                    <h3 class="text-lg font-medium text-gray-700 mb-3">Search Locked Records</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Product Code/Name</label>
                            <input 
                                type="text" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                placeholder="Search product..."
                                v-model="filters.product"
                            />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Document Reference</label>
                            <input 
                                type="text" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                placeholder="Document #"
                                v-model="filters.reference"
                            />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Lock Type</label>
                            <select 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                v-model="filters.lockType"
                            >
                                <option value="">All Lock Types</option>
                                <option value="SO">Sales Order</option>
                                <option value="WO">Work Order</option>
                                <option value="MO">Movement Order</option>
                                <option value="TO">Transfer Order</option>
                            </select>
                        </div>
                    </div>
                    <div class="flex justify-end mt-4">
                        <button
                            type="button"
                            class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                            @click="searchLocks"
                        >
                            <i class="fas fa-search mr-2"></i> Search
                        </button>
                    </div>
                </div>
                
                <!-- Locked Records Table -->
                <div class="bg-white rounded-lg border border-gray-200">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <input type="checkbox" class="rounded text-indigo-600" v-model="selectAll" @change="toggleSelectAll" />
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Lock ID
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Product
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Warehouse
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Quantity
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Lock Type
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Reference
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Locked By
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Lock Date
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr v-if="locks.length === 0">
                                    <td colspan="9" class="px-6 py-4 text-center text-sm text-gray-500">
                                        No locked records found
                                    </td>
                                </tr>
                                <tr v-for="lock in locks" :key="lock.id" class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <input type="checkbox" class="rounded text-indigo-600" v-model="lock.selected" />
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ lock.id }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <div>{{ lock.product_code }}</div>
                                        <div class="text-xs text-gray-400">{{ lock.product_name }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ lock.warehouse }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ lock.quantity }} {{ lock.uom }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <span :class="{
                                            'px-2 py-1 text-xs rounded-full': true,
                                            'bg-blue-100 text-blue-800': lock.lock_type === 'SO',
                                            'bg-purple-100 text-purple-800': lock.lock_type === 'WO',
                                            'bg-amber-100 text-amber-800': lock.lock_type === 'MO',
                                            'bg-green-100 text-green-800': lock.lock_type === 'TO',
                                        }">
                                            {{ lock.lock_type }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ lock.reference }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ lock.locked_by }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ lock.lock_date }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                        <div class="flex items-center justify-between">
                            <div class="flex-1 flex justify-between sm:hidden">
                                <button @click="prevPage" :disabled="page === 1" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                    Previous
                                </button>
                                <button @click="nextPage" :disabled="page >= totalPages" class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                    Next
                                </button>
                            </div>
                            <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                                <div>
                                    <p class="text-sm text-gray-700">
                                        Showing <span class="font-medium">{{ startRecord }}</span> to <span class="font-medium">{{ endRecord }}</span> of <span class="font-medium">{{ totalRecords }}</span> results
                                    </p>
                                </div>
                                <div>
                                    <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px">
                                        <button @click="goToPage(1)" :disabled="page === 1" class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                            <span class="sr-only">First</span>
                                            <i class="fas fa-angle-double-left"></i>
                                        </button>
                                        <button @click="prevPage" :disabled="page === 1" class="relative inline-flex items-center px-2 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                            <span class="sr-only">Previous</span>
                                            <i class="fas fa-angle-left"></i>
                                        </button>
                                        <span v-for="p in paginationRange" :key="p">
                                            <button 
                                                @click="goToPage(p)" 
                                                :class="{ 'z-10 bg-indigo-50 border-indigo-500 text-indigo-600': page === p, 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50': page !== p }"
                                                class="relative inline-flex items-center px-4 py-2 border text-sm font-medium"
                                            >
                                                {{ p }}
                                            </button>
                                        </span>
                                        <button @click="nextPage" :disabled="page >= totalPages" class="relative inline-flex items-center px-2 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                            <span class="sr-only">Next</span>
                                            <i class="fas fa-angle-right"></i>
                                        </button>
                                        <button @click="goToPage(totalPages)" :disabled="page >= totalPages" class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                            <span class="sr-only">Last</span>
                                            <i class="fas fa-angle-double-right"></i>
                                        </button>
                                    </nav>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="mt-6 flex justify-end space-x-3">
                    <button 
                        type="button" 
                        class="px-4 py-2 bg-white border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                        @click="resetFilters"
                    >
                        <i class="fas fa-redo mr-2"></i> Reset
                    </button>
                    <button 
                        type="button" 
                        class="px-4 py-2 bg-red-600 border border-transparent rounded-md shadow-sm text-sm font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                        @click="confirmClearLocks"
                        :disabled="!hasSelectedLocks"
                    >
                        <i class="fas fa-unlock mr-2"></i> Clear Selected Locks
                    </button>
                </div>
            </div>
        </div>

        <!-- Confirmation Modal -->
        <Modal :show="showConfirmModal" @close="closeConfirmModal">
            <div class="p-6">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                    <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
                </div>
                <div class="mt-3 text-center sm:mt-5">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Confirm Clear Locks</h3>
                    <div class="mt-2">
                        <p class="text-sm text-gray-500">
                            You are about to clear {{ selectedLocksCount }} lock(s). This action cannot be undone.
                            Are you sure you want to continue?
                        </p>
                    </div>
                </div>
                <div class="mt-5 sm:mt-6 sm:grid sm:grid-cols-2 sm:gap-3 sm:grid-flow-row-dense">
                    <button 
                        type="button" 
                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:col-start-2 sm:text-sm"
                        @click="clearSelectedLocks"
                    >
                        Clear Locks
                    </button>
                    <button 
                        type="button" 
                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:col-start-1 sm:text-sm"
                        @click="closeConfirmModal"
                    >
                        Cancel
                    </button>
                </div>
            </div>
        </Modal>
    </AppLayout>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout.vue';
import Modal from '@/Components/Modal.vue';
import { ref, computed, onMounted } from 'vue';

export default {
    components: {
        AppLayout,
        Modal
    },
    setup() {
        const filters = ref({
            product: '',
            reference: '',
            lockType: ''
        });

        const page = ref(1);
        const perPage = ref(10);
        const totalRecords = ref(25); // Demo data
        const selectAll = ref(false);
        const showConfirmModal = ref(false);

        // Demo data for locks
        const locks = ref([
            { 
                id: 'LCK001', 
                product_code: 'P-1001', 
                product_name: 'Widget A',
                warehouse: 'FG-WH-01',
                quantity: 100,
                uom: 'PCS',
                lock_type: 'SO',
                reference: 'SO-2023-001',
                locked_by: 'John Doe',
                lock_date: '2023-08-15 09:30',
                selected: false
            },
            { 
                id: 'LCK002', 
                product_code: 'P-1002', 
                product_name: 'Widget B',
                warehouse: 'FG-WH-02',
                quantity: 50,
                uom: 'BOX',
                lock_type: 'WO',
                reference: 'WO-2023-015',
                locked_by: 'Jane Smith',
                lock_date: '2023-08-16 11:45',
                selected: false
            },
            { 
                id: 'LCK003', 
                product_code: 'P-1003', 
                product_name: 'Widget C',
                warehouse: 'FG-WH-01',
                quantity: 200,
                uom: 'PCS',
                lock_type: 'MO',
                reference: 'MO-2023-008',
                locked_by: 'Robert Johnson',
                lock_date: '2023-08-17 14:20',
                selected: false
            },
            { 
                id: 'LCK004', 
                product_code: 'P-1004', 
                product_name: 'Widget D',
                warehouse: 'FG-WH-03',
                quantity: 75,
                uom: 'BOX',
                lock_type: 'TO',
                reference: 'TO-2023-022',
                locked_by: 'Lisa Brown',
                lock_date: '2023-08-18 16:10',
                selected: false
            },
        ]);

        // Computed properties
        const totalPages = computed(() => Math.ceil(totalRecords.value / perPage.value));
        
        const startRecord = computed(() => ((page.value - 1) * perPage.value) + 1);
        
        const endRecord = computed(() => {
            const end = page.value * perPage.value;
            return end > totalRecords.value ? totalRecords.value : end;
        });
        
        const paginationRange = computed(() => {
            const range = [];
            const maxButtons = 5;
            let start = Math.max(1, page.value - Math.floor(maxButtons / 2));
            let end = Math.min(totalPages.value, start + maxButtons - 1);
            
            if (end - start + 1 < maxButtons) {
                start = Math.max(1, end - maxButtons + 1);
            }
            
            for (let i = start; i <= end; i++) {
                range.push(i);
            }
            
            return range;
        });
        
        const hasSelectedLocks = computed(() => {
            return locks.value.some(lock => lock.selected);
        });
        
        const selectedLocksCount = computed(() => {
            return locks.value.filter(lock => lock.selected).length;
        });

        // Methods
        const searchLocks = () => {
            // In a real application, this would make an API call with the filters
            console.log('Searching with filters:', filters.value);
            page.value = 1; // Reset to first page when searching
        };

        const resetFilters = () => {
            filters.value.product = '';
            filters.value.reference = '';
            filters.value.lockType = '';
            searchLocks();
        };

        const toggleSelectAll = () => {
            locks.value.forEach(lock => {
                lock.selected = selectAll.value;
            });
        };

        const prevPage = () => {
            if (page.value > 1) {
                page.value--;
            }
        };

        const nextPage = () => {
            if (page.value < totalPages.value) {
                page.value++;
            }
        };

        const goToPage = (pageNum) => {
            page.value = pageNum;
        };

        const confirmClearLocks = () => {
            if (hasSelectedLocks.value) {
                showConfirmModal.value = true;
            }
        };

        const closeConfirmModal = () => {
            showConfirmModal.value = false;
        };

        const clearSelectedLocks = () => {
            // In a real application, this would make an API call to clear the selected locks
            console.log('Clearing locks:', locks.value.filter(lock => lock.selected));
            
            // Remove the selected locks from the display list
            locks.value = locks.value.filter(lock => !lock.selected);
            
            // Close the confirmation modal
            showConfirmModal.value = false;
            
            // Reset selectAll
            selectAll.value = false;
        };

        onMounted(() => {
            // In a real application, this would fetch initial data
            searchLocks();
        });

        return {
            filters,
            locks,
            page,
            perPage,
            totalRecords,
            totalPages,
            selectAll,
            startRecord,
            endRecord,
            paginationRange,
            hasSelectedLocks,
            selectedLocksCount,
            showConfirmModal,
            searchLocks,
            resetFilters,
            toggleSelectAll,
            prevPage,
            nextPage,
            goToPage,
            confirmClearLocks,
            closeConfirmModal,
            clearSelectedLocks
        };
    }
};
</script>

<style scoped>
/* Utility classes for text shadows */
.text-shadow {
    text-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}
</style> 