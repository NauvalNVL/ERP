<template>
    <AppLayout :header="'Product Group'">
    <Head title="Product Group Management" />

    <!-- Header Section -->
    <div class="bg-gradient-to-r from-cyan-700 to-blue-600 p-6 rounded-t-lg shadow-lg">
        <h2 class="text-2xl font-bold text-white mb-2 flex items-center">
            <i class="fas fa-object-group mr-3"></i> Define Product Group
        </h2>
        <p class="text-cyan-100">Define product groups for organizing products</p>
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
                        <h3 class="text-xl font-semibold text-gray-800">Product Group Management</h3>
                    </div>
                    
                    <!-- Search Section -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-6">
                        <div class="col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Group #:</label>
                            <div class="relative flex">
                                <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500">
                                    <i class="fas fa-object-group"></i>
                                </span>
                                <input type="text" v-model="searchQuery" class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none border border-gray-300 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                <button type="button" @click="showModal = true" class="inline-flex items-center px-3 py-2 border border-l-0 border-gray-300 bg-blue-500 hover:bg-blue-600 text-white rounded-r-md">
                                    <i class="fas fa-table"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-span-1">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Action:</label>
                            <button type="button" @click="createNewGroup" class="w-full flex items-center justify-center px-4 py-2 bg-indigo-500 hover:bg-indigo-600 text-white rounded">
                                <i class="fas fa-plus-circle mr-2"></i> Add New
                            </button>
                        </div>
                    </div>
                    <!-- Data Status Information -->
                    <div v-if="loading" class="mt-4 bg-yellow-100 p-3 rounded">
                        <div class="flex items-center">
                            <div class="mr-3">
                                <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-yellow-700"></div>
                            </div>
                            <p class="text-sm font-medium text-yellow-800">Loading product group data...</p>
                        </div>
                    </div>
                    <div v-else-if="productGroups.length === 0" class="mt-4 bg-yellow-100 p-3 rounded">
                        <p class="text-sm font-medium text-yellow-800">No product group data available.</p>
                        <p class="text-xs text-yellow-700 mt-1">Make sure the database is properly configured and seeders have been run.</p>
                        <div class="mt-2 flex items-center space-x-3">
                            <button @click="loadSeedData" class="bg-blue-500 hover:bg-blue-600 text-white text-xs px-3 py-1 rounded">Run Product Group Seeder</button>
                        </div>
                    </div>
                    <div v-else class="mt-4 bg-green-100 p-3 rounded">
                        <p class="text-sm font-medium text-green-800">Data available: {{ productGroups.length }} groups found.</p>
                        <p v-if="selectedRow" class="text-xs text-green-700 mt-1">
                            Selected: <span class="font-semibold">{{ selectedRow.code }}</span> - {{ selectedRow.name }}
                        </p>
                    </div>
                </div>
            </div>
            <!-- Right Column - Quick Info -->
            <div class="lg:col-span-1">
                <!-- Product Group Info Card -->
                <div class="bg-white p-6 rounded-lg shadow-md border-t-4 border-teal-500 mb-6">
                    <div class="flex items-center mb-4 pb-2 border-b border-gray-200">
                        <div class="p-2 bg-teal-500 rounded-lg mr-3">
                            <i class="fas fa-info-circle text-white"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-800">Product Group Info</h3>
                    </div>

                    <div class="space-y-4">
                        <div class="p-4 bg-teal-50 rounded-lg">
                            <h4 class="text-sm font-semibold text-teal-800 uppercase tracking-wider mb-2">Instructions</h4>
                            <ul class="list-disc pl-5 text-sm text-gray-600 space-y-1">
                                <li>Product group code must be unique</li>
                                <li>Use the <span class="font-medium">search</span> button to select a group</li>
                                <li>Groups can be enabled or disabled using the Active status</li>
                                <li>Group codes are typically short, like "B" for Box or "P" for Paper</li>
                            </ul>
                        </div>

                        <div class="p-4 bg-blue-50 rounded-lg">
                            <h4 class="text-sm font-semibold text-blue-800 uppercase tracking-wider mb-2">Group Information</h4>
                            <div class="space-y-2 text-sm">
                                <div v-if="selectedRow" class="grid grid-cols-2 gap-2">
                                    <div class="font-medium text-gray-700">Group ID:</div>
                                    <div>{{ selectedRow.code }}</div>
                                    <div class="font-medium text-gray-700">Group Name:</div>
                                    <div>{{ selectedRow.name }}</div>
                                    <div class="font-medium text-gray-700">Status:</div>
                                    <div>
                                        <span v-if="selectedRow.is_active" class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs">Active</span>
                                        <span v-else class="px-2 py-1 bg-red-100 text-red-800 rounded-full text-xs">Inactive</span>
                                    </div>
                                </div>
                                <div v-else class="text-gray-500 italic">
                                    Select a product group to view details
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
                        <Link href="/product" class="flex items-center p-3 bg-purple-50 rounded-lg hover:bg-purple-100 transition-colors">
                            <div class="p-2 bg-purple-500 rounded-full mr-3">
                                <i class="fas fa-box text-white text-sm"></i>
                            </div>
                            <div>
                                <p class="font-medium text-purple-900">Products</p>
                                <p class="text-xs text-purple-700">Manage products</p>
                            </div>
                        </Link>

                        <Link href="/product-design" class="flex items-center p-3 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                            <div class="p-2 bg-blue-500 rounded-full mr-3">
                                <i class="fas fa-drafting-compass text-white text-sm"></i>
                            </div>
                            <div>
                                <p class="font-medium text-blue-900">Product Designs</p>
                                <p class="text-xs text-blue-700">Manage product designs</p>
                            </div>
                        </Link>

                        <Link href="/product-group/view-print" class="flex items-center p-3 bg-green-50 rounded-lg hover:bg-green-100 transition-colors">
                            <div class="p-2 bg-green-500 rounded-full mr-3">
                                <i class="fas fa-print text-white text-sm"></i>
                            </div>
                            <div>
                                <p class="font-medium text-green-900">Print Groups</p>
                                <p class="text-xs text-green-700">Print product group list</p>
                            </div>
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Table -->
    <ProductGroupModal
        :show="showModal"
        :product-groups="productGroups"
        :loading="loading"
        @close="showModal = false"
        @select="onGroupSelected"
    />

    <!-- Edit Modal -->
    <div v-if="showEditModal" class="fixed inset-0 z-50 bg-black bg-opacity-50 flex items-center justify-center">
        <div class="bg-white rounded-lg shadow-xl w-11/12 md:w-2/5 max-w-md mx-auto">
            <div class="flex items-center justify-between p-4 border-b border-gray-200 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-t-lg">
                <div class="flex items-center">
                    <div class="p-2 bg-white bg-opacity-30 rounded-lg mr-3">
                        <i class="fas fa-object-group"></i>
                    </div>
                    <h3 class="text-xl font-semibold">{{ isCreating ? 'Create Product Group' : 'Edit Product Group' }}</h3>
                </div>
                <button type="button" @click="closeEditModal" class="text-white hover:text-gray-200">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div class="p-6">
                <form @submit.prevent="saveGroupChanges" class="space-y-4">
                    <div class="grid grid-cols-1 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Group Code:</label>
                            <input v-model="editForm.code" type="text" class="block w-full rounded-md border-gray-300 shadow-sm" :class="{ 'bg-gray-100': !isCreating }" :readonly="!isCreating" required>
                            <p class="mt-1 text-xs text-gray-500">Short code like "B" for Box or "P" for Paper</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Group Name:</label>
                            <input v-model="editForm.name" type="text" class="block w-full rounded-md border-gray-300 shadow-sm" required>
                        </div>
                        <div>
                            <label class="flex items-center">
                                <input type="checkbox" v-model="editForm.is_active" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                <span class="ml-2 text-sm text-gray-700">Active</span>
                            </label>
                        </div>
                    </div>
                    <div class="flex justify-between mt-6 pt-4 border-t border-gray-200">
                        <button type="button" v-if="!isCreating" @click="deleteGroup(editForm.id)" class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600">
                            <i class="fas fa-trash-alt mr-2"></i>Delete
                        </button>
                        <div v-else class="w-24"></div>
                        <div class="flex space-x-3">
                            <button type="button" @click="closeEditModal" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300">Cancel</button>
                            <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">Save</button>
                        </div>
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
import { ref, onMounted, watch, computed } from 'vue';
import { Head, Link, usePage, router } from '@inertiajs/vue3';
import ProductGroupModal from '@/Components/product-group-modal.vue';
import AppLayout from '@/Layouts/AppLayout.vue';

// Get the header from props
const props = defineProps({
    header: {
        type: String,
        default: 'Product Group Management'
    }
});

const productGroups = ref([]);
const loading = ref(false);
const saving = ref(false);
const showModal = ref(false);
const showEditModal = ref(false);
const selectedRow = ref(null);
const searchQuery = ref('');
const editForm = ref({ 
    id: '',
    code: '', 
    name: '', 
    is_active: true
});
const isCreating = ref(false);
const notification = ref({ show: false, message: '', type: 'success' });

const fetchProductGroups = async () => {
    loading.value = true;
    try {
        console.log('Fetching product groups from API...');
        const res = await fetch('/api/product-groups', { 
            headers: { 
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            } 
        });
        
        if (!res.ok) {
            throw new Error('Network response was not ok');
        }
        
        const data = await res.json();
        console.log('API response raw data:', data);
        
        // Process the data to ensure we have consistent object structure
        let processedData = [];
        
        if (Array.isArray(data)) {
            processedData = data.map(item => ({
                id: item.id,
                code: item.product_group_id || item.code,
                name: item.product_group_name || item.name,
                is_active: typeof item.is_active !== 'undefined' ? item.is_active : true
            }));
        } else if (data.data && Array.isArray(data.data)) {
            processedData = data.data.map(item => ({
                id: item.id,
                code: item.product_group_id || item.code,
                name: item.product_group_name || item.name,
                is_active: typeof item.is_active !== 'undefined' ? item.is_active : true
            }));
        } else {
            console.error('Unexpected data format:', data);
        }
        
        console.log('Processed product groups data:', processedData);
        productGroups.value = processedData;
    } catch (e) {
        console.error('Error fetching product groups:', e);
        productGroups.value = [];
    } finally {
        loading.value = false;
    }
};

onMounted(() => {
    console.log('Product group component mounted, fetching data...');
    // Immediately fetch product groups when the component is mounted
    fetchProductGroups();
});

// Watch for changes in search query to filter the data
watch(searchQuery, (newQuery) => {
    if (newQuery && productGroups.value.length > 0) {
        const foundGroup = productGroups.value.find(group => 
            group.code.toLowerCase().includes(newQuery.toLowerCase()) ||
            group.name.toLowerCase().includes(newQuery.toLowerCase())
        );
        
        if (foundGroup) {
            selectedRow.value = foundGroup;
        }
    }
});

const onGroupSelected = (group) => {
    selectedRow.value = group;
    searchQuery.value = group.code;
    showModal.value = false;
    
    // Only open the edit modal if we received a complete group object
    if (group && group.id) {
        isCreating.value = false;
        editForm.value = { 
            id: group.id,
            code: group.code, 
            name: group.name,
            is_active: group.is_active 
        };
        console.log('Editing group:', editForm.value);
        showEditModal.value = true;
    }
};

const editSelectedRow = () => {
    if (selectedRow.value) {
        isCreating.value = false;
        editForm.value = { 
            id: selectedRow.value.id,
            code: selectedRow.value.code,
            name: selectedRow.value.name,
            is_active: selectedRow.value.is_active
        };
        console.log('Editing selected row:', editForm.value);
        showEditModal.value = true;
    } else {
        showNotification('Please select a product group first', 'error');
    }
};

const createNewGroup = () => {
    isCreating.value = true;
    editForm.value = { 
        id: '',
        code: '', 
        name: '', 
        is_active: true
    };
    showEditModal.value = true;
};

const closeEditModal = () => {
    showEditModal.value = false;
    editForm.value = { 
        id: '',
        code: '', 
        name: '', 
        is_active: true
    };
    isCreating.value = false;
};

const saveGroupChanges = async () => {
    saving.value = true;
    try {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
        
        // Different API call for create vs update
        let url = isCreating.value ? '/api/product-groups' : `/api/product-groups/${editForm.value.id}`;
        let method = isCreating.value ? 'POST' : 'PUT';
        
        console.log('Saving product group with URL:', url);
        console.log('Method:', method);
        console.log('Data:', {
            code: editForm.value.code,
            name: editForm.value.name,
            is_active: editForm.value.is_active
        });
        
        const response = await fetch(url, {
            method: method,
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                code: editForm.value.code,
                name: editForm.value.name,
                is_active: editForm.value.is_active
            })
        });
        
        const result = await response.json();
        
        console.log('API response:', result);
        
        if (result.success) {
            // Update the local data with the changes or add new item
            if (isCreating.value) {
                showNotification('Product group created successfully', 'success');
            } else {
                if (selectedRow.value) {
                    selectedRow.value.name = editForm.value.name;
                    selectedRow.value.is_active = editForm.value.is_active;
                }
                showNotification('Product group updated successfully', 'success');
            }
            
            // Refresh the full data list to ensure we're in sync with the database
            await fetchProductGroups();
            closeEditModal();
        } else {
            showNotification('Error: ' + (result.message || 'Unknown error'), 'error');
        }
    } catch (e) {
        console.error('Error saving product group changes:', e);
        showNotification('Error saving product group. Please try again.', 'error');
    } finally {
        saving.value = false;
    }
};

const deleteGroup = async (id) => {
    if (!confirm(`Are you sure you want to delete this product group?`)) {
        return;
    }
    
    saving.value = true;
    try {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
        
        const response = await fetch(`/api/product-groups/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        });
        
        const result = await response.json();
        
        if (result.success) {
            // Remove the item from the local array
            productGroups.value = productGroups.value.filter(group => group.id !== id);
            
            if (selectedRow.value && selectedRow.value.id === id) {
                selectedRow.value = null;
                searchQuery.value = '';
            }
            
            closeEditModal();
            showNotification('Product group deleted successfully', 'success');
        } else {
            showNotification('Error deleting product group: ' + (result.message || 'Unknown error'), 'error');
        }
    } catch (e) {
        console.error('Error deleting product group:', e);
        showNotification('Error deleting product group. Please try again.', 'error');
    } finally {
        saving.value = false;
    }
};

const loadSeedData = async () => {
    saving.value = true;
    try {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
        
        const response = await fetch('/api/product-groups/seed', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        });
        
        const result = await response.json();
        
        if (result.success) {
            showNotification('Product group data seeded successfully', 'success');
            await fetchProductGroups();
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
</script>
