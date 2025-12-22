<template>
	<AppLayout header="Product Group">
		<Head title="Product Group Management" />

		<!-- Header Section -->
		<div class="min-h-screen bg-gray-50 py-6 px-4 sm:px-6 lg:px-8">
			<div class="max-w-6xl mx-auto">
				<!-- Header Section -->
				<div class="bg-gradient-to-r from-green-600 to-green-700 text-white shadow-sm rounded-xl border border-green-700 mb-4">
					<div class="px-4 py-3 sm:px-6 flex items-center gap-3">
						<div class="h-9 w-9 rounded-full bg-green-500 flex items-center justify-center">
							<i class="fas fa-object-group text-white text-lg"></i>
						</div>
						<div>
							<h2 class="text-lg sm:text-xl font-semibold leading-tight">Define Product Group</h2>
							<p class="text-xs sm:text-sm text-green-100">Define product groups for product categorization</p>
						</div>
					</div>
				</div>

				<div class="bg-white shadow-sm rounded-xl border border-gray-200 p-4 sm:p-6 mb-6">
					<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
						<!-- Left Column -->
						<div class="lg:col-span-2">
							<div class="bg-white p-4 sm:p-6 rounded-lg shadow-sm border-t-4 border-emerald-500">
								<div class="flex items-center mb-6 pb-2 border-b border-gray-200">
									<div class="p-2 bg-emerald-500 rounded-lg mr-3">
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
											<input
												type="text"
												v-model="searchQuery"
												class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none border border-gray-300 focus:ring-emerald-500 focus:border-emerald-500 transition-colors"
											/>
											<button
												type="button"
												@click="openProductGroupModal"
												class="inline-flex items-center px-3 py-2 border border-l-0 border-emerald-500 bg-emerald-500 hover:bg-emerald-600 text-white rounded-r-md transition-colors transform active:translate-y-px"
											>
												<i class="fas fa-table"></i>
											</button>
										</div>
									</div>
									<div class="col-span-1">
										<label class="block text-sm font-medium text-gray-700 mb-1">Action:</label>
										<button
											type="button"
											@click="createNewGroup"
											class="w-full flex items-center justify-center px-4 py-2 bg-gradient-to-r from-emerald-500 to-green-600 hover:from-emerald-600 hover:to-green-700 text-white rounded transition-colors transform active:translate-y-px"
										>
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
									<p class="text-xs text-yellow-700 mt-1">Data will be automatically loaded when available.</p>
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
							<div class="bg-white p-4 sm:p-6 rounded-lg shadow-sm border-t-4 border-teal-500 mb-6">
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
										<h4 class="text-sm font-semibold text-blue-800 uppercase tracking-wider mb-2">Common Group</h4>
										<div class="grid grid-cols-2 gap-2 text-sm">
											<div class="flex items-center">
												<span class="w-6 h-6 flex items-center justify-center bg-emerald-600 text-white rounded-full font-bold mr-2 shrink-0">B</span>
												<span>Box</span>
											</div>
											<div class="flex items-center">
												<span class="w-6 h-6 flex items-center justify-center bg-blue-600 text-white rounded-full font-bold mr-2 shrink-0">P</span>
												<span>Paper</span>
											</div>
											<div class="flex items-center">
												<span class="w-6 h-6 flex items-center justify-center bg-purple-600 text-white rounded-full font-bold mr-2 shrink-0">L</span>
												<span>Label</span>
											</div>
											<div class="flex items-center">
												<span class="w-6 h-6 flex items-center justify-center bg-yellow-500 text-white rounded-full font-bold mr-2 shrink-0">A</span>
												<span>Accessory</span>
											</div>
											<div class="flex items-center">
												<span class="w-6 h-6 flex items-center justify-center bg-green-600 text-white rounded-full font-bold mr-2 shrink-0">S</span>
												<span>Sticker</span>
											</div>
											<div class="flex items-center">
												<span class="w-6 h-6 flex items-center justify-center bg-red-500 text-white rounded-full font-bold mr-2 shrink-0">F</span>
												<span>Foam</span>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<!-- Modal Table -->
		<ProductGroupModal
			:show="showModal"
			:product-groups="productGroups"
			:loading="modalLoading"
			@close="showModal = false"
			@select="onGroupSelected"
		/>

		<!-- Edit Modal -->
		<div v-if="showEditModal" class="fixed inset-0 z-50 bg-black bg-opacity-50 flex items-center justify-center">
			<div class="bg-white rounded-xl shadow-lg w-11/12 md:w-2/5 max-w-md mx-auto">
				<div class="flex items-center justify-between px-4 py-3 border-b border-emerald-100 bg-gradient-to-r from-green-600 to-green-700 text-white rounded-t-xl">
					<div class="flex items-center">
						<div class="p-2 bg-white/20 rounded-lg mr-3">
							<i class="fas fa-object-group"></i>
						</div>
						<h3 class="text-sm font-semibold">{{ isCreating ? 'Create Product Group' : 'Edit Product Group' }}</h3>
					</div>
					<button type="button" @click="closeEditModal" class="text-white hover:text-emerald-100">
						<i class="fas fa-times text-lg"></i>
					</button>
				</div>
				<div class="p-5">
					<form @submit.prevent="saveGroupChanges" class="space-y-4">
						<div class="grid grid-cols-1 gap-4">
							<div>
								<label class="block text-sm font-medium text-gray-700 mb-1">Group Code:</label>
								<input v-model="editForm.code" type="text" class="block w-full rounded-md border-gray-300 shadow-sm text-sm focus:ring-emerald-500 focus:border-emerald-500" :class="{ 'bg-gray-100': !isCreating }" :readonly="!isCreating" required>
								<p class="mt-1 text-xs text-gray-500">Short code like "B" for Box or "P" for Paper</p>
							</div>
							<div>
								<label class="block text-sm font-medium text-gray-700 mb-1">Group Name:</label>
								<input v-model="editForm.name" type="text" class="block w-full rounded-md border-gray-300 shadow-sm text-sm focus:ring-emerald-500 focus:border-emerald-500" required>
							</div>
							<div>
								<label class="flex items-center">
									<input type="checkbox" v-model="editForm.is_active" class="rounded border-gray-300 text-emerald-600 shadow-sm focus:border-emerald-300 focus:ring focus:ring-emerald-200 focus:ring-opacity-50">
									<span class="ml-2 text-sm text-gray-700">Active</span>
								</label>
							</div>
						</div>
						<div class="flex justify-between mt-5 pt-4 border-t border-gray-200">
							<button type="button" v-if="!isCreating" @click="obsoleteGroup(editForm.id)" class="px-4 py-2 bg-orange-500 text-white rounded-lg hover:bg-orange-600 text-sm font-medium">
								<i class="fas fa-ban mr-2"></i>Obsolete
							</button>
							<div v-else class="w-24"></div>
							<div class="flex space-x-3">
								<button type="button" @click="closeEditModal" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 text-sm font-medium">Cancel</button>
								<button type="submit" class="px-4 py-2 bg-gradient-to-r from-emerald-500 to-green-600 text-white rounded-lg hover:from-emerald-600 hover:to-green-700 text-sm font-medium">Save</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>

		<!-- Loading Overlay -->
		<div v-if="saving" class="fixed inset-0 z-50 bg-black bg-opacity-30 flex justify-center items-center">
			<div class="w-10 h-10 border-4 border-solid border-emerald-500 border-t-transparent rounded-full animate-spin"></div>
		</div>
		
		<!-- Notification Toast -->
		<div v-if="notification.show" class="fixed bottom-4 right-4 z-50 shadow-lg rounded-lg transition-all duration-300"
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
import Swal from 'sweetalert2';

// Get the header from props
const props = defineProps({
    header: {
        type: String,
        default: 'Product Group Management'
    }
});

const productGroups = ref([]);
const loading = ref(false);
const modalLoading = ref(false);
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

const fetchProductGroups = async (options = {}) => {
    const { showGlobal = true } = options;
    if (showGlobal) {
        loading.value = true;
    } else {
        modalLoading.value = true;
    }
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
        if (showGlobal) {
            loading.value = false;
        } else {
            modalLoading.value = false;
        }
    }
};

const openProductGroupModal = async () => {
    showModal.value = true;
    await fetchProductGroups({ showGlobal: false });
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

const obsoleteGroup = async (id) => {
    const confirmRes = await Swal.fire({
        title: 'Obsolete Product Group?',
        text: 'Are you sure you want to obsolete this product group? This will mark it as inactive and it will no longer appear in selection lists.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'OK',
        cancelButtonText: 'Cancel',
        reverseButtons: true,
        allowOutsideClick: false,
    });

    if (!confirmRes.isConfirmed) {
        return;
    }
    
    saving.value = true;
    try {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
        
        // Update status to 'Obs' (Obsolete) instead of deleting
        const response = await fetch(`/api/product-groups/${id}/status`, {
            method: 'PUT',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        });
        
        const result = await response.json();
        
        if (result.success) {
            // Remove the item from the local array (since obsoleted items shouldn't appear)
            productGroups.value = productGroups.value.filter(group => group.id !== id);
            
            if (selectedRow.value && selectedRow.value.id === id) {
                selectedRow.value = null;
                searchQuery.value = '';
            }
            
            closeEditModal();
            showNotification(`Product group "${editForm.value.code}" has been obsoleted successfully. Use Obsolete/Unobsolete Product Group menu to reactivate.`, 'success');
        } else {
            showNotification('Error obsoleting product group: ' + (result.message || 'Unknown error'), 'error');
        }
    } catch (e) {
        console.error('Error obsoleting product group:', e);
        showNotification('Error obsoleting product group. Please try again.', 'error');
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
