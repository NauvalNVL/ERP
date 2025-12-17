<template>
	<AppLayout header="Manage Product Status">
		<Head title="Manage Product Status" />

		<!-- Header & Main Layout -->
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
									Manage Product Status (Obsolete/Unobsolete)
								</h2>
								<p class="text-xs sm:text-sm text-green-100">
									Toggle the active status of products.
								</p>
							</div>
						</div>
						<div class="relative w-full sm:w-72">
							<span class="absolute inset-y-0 left-0 flex items-center pl-3 text-green-100">
								<i class="fas fa-search text-xs"></i>
							</span>
							<input
								v-model="searchQuery"
								type="text"
								placeholder="Search products (code, description, category)..."
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

				<!-- Main Card -->
				<div class="bg-white shadow-sm rounded-xl border border-gray-200 overflow-hidden">
					<!-- Card Header -->
					<div class="px-4 py-2 sm:px-6 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 bg-white">
						<div>
							<h2 class="text-sm font-semibold text-gray-800">
								Product List
							</h2>
							<p class="text-xs text-gray-500">
								{{ filteredProducts.length }} of {{ products.length }} products
							</p>
						</div>
						<div class="flex items-center gap-2">
							<label class="text-xs font-medium text-gray-600">
								Status:
							</label>
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

					<!-- Table / Loading -->
					<div class="relative">
						<!-- Loading Indicator -->
						<div v-if="loading" class="py-10 flex justify-center items-center">
							<div class="w-8 h-8 border-4 border-emerald-500 border-t-transparent rounded-full animate-spin"></div>
						</div>

						<!-- Products Table -->
						<div v-else class="overflow-x-auto">
							<table class="min-w-full table-auto divide-y divide-gray-200 text-sm">
								<thead class="bg-gray-50">
									<tr>
										<th
											scope="col"
											class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide"
										>
											Code
										</th>
										<th
											scope="col"
											class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide"
										>
											Name
										</th>
										<th
											scope="col"
											class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide"
										>
											Category
										</th>
										<th
											scope="col"
											class="px-4 py-2 text-center text-xs font-semibold text-gray-500 uppercase tracking-wide"
										>
											Status
										</th>
										<th
											scope="col"
											class="px-4 py-2 text-center text-xs font-semibold text-gray-500 uppercase tracking-wide"
										>
											Action
										</th>
									</tr>
								</thead>
								<tbody class="divide-y divide-gray-100 bg-white">
									<tr v-for="product in filteredProducts" :key="product.id" class="hover:bg-gray-50">
										<td class="px-4 py-2 whitespace-nowrap text-xs font-mono text-gray-700">
											{{ product.product_code }}
										</td>
										<td class="px-4 py-2 whitespace-nowrap text-sm text-gray-800">
											{{ product.description }}
										</td>
										<td class="px-4 py-2 whitespace-nowrap text-sm text-gray-600">
											{{ product.category }}
										</td>
										<td class="px-4 py-2 whitespace-nowrap text-sm text-center">
											<span
												v-if="product.status === 'Act'"
												class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-100"
											>
												<i class="fas fa-check-circle mr-1"></i>
												Active
											</span>
											<span
												v-else
												class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-50 text-red-700 border border-red-100"
											>
												<i class="fas fa-times-circle mr-1"></i>
												Obsolete
											</span>
										</td>
										<td class="px-4 py-2 whitespace-nowrap text-sm">
											<div class="flex justify-center">
												<button
													@click="toggleProductStatus(product)"
													:disabled="isToggling"
													:class="[
														product.status === 'Act'
															? 'text-red-600 hover:text-red-700 bg-red-50 hover:bg-red-100 border border-red-100'
															: 'text-emerald-600 hover:text-emerald-700 bg-emerald-50 hover:bg-emerald-100 border border-emerald-100',
														'transition-colors duration-150 ease-in-out focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-emerald-500 px-3 py-1 rounded-md text-xs font-semibold inline-flex items-center justify-center'
													]"
													:style="{ minWidth: '120px' }"
												>
													<i :class="[product.status === 'Act' ? 'fas fa-toggle-off' : 'fas fa-toggle-on', 'mr-1']"></i>
													{{ product.status === 'Act' ? 'Mark Obsolete' : 'Mark Active' }}
												</button>
											</div>
										</td>
									</tr>
									<tr v-if="filteredProducts.length === 0">
										<td colspan="5" class="px-4 py-10 text-center text-sm text-gray-500">
											No products found.
										</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>

					<!-- Pagination Controls -->
					<div
						v-if="pagination.total > pagination.perPage"
						class="px-4 py-3 sm:px-6 bg-gray-50 border-t border-gray-100 flex items-center justify-between"
					>
						<div class="flex-1 flex justify-between items-center">
							<button
									@click="changePage(pagination.currentPage - 1)"
									:disabled="pagination.currentPage === 1"
									:class="[
										pagination.currentPage === 1
											? 'bg-gray-100 text-gray-400 cursor-not-allowed'
											: 'bg-white text-gray-700 hover:bg-gray-100',
										'py-1.5 px-3 border border-gray-300 rounded-md text-xs font-medium focus:outline-none focus:ring-1 focus:ring-emerald-500 focus:border-emerald-500'
									]"
							>
								Previous
							</button>

							<span class="text-xs text-gray-600">
								Page {{ pagination.currentPage }} of {{ Math.ceil(pagination.total / pagination.perPage) }}
							</span>

							<button
									@click="changePage(pagination.currentPage + 1)"
									:disabled="pagination.currentPage >= Math.ceil(pagination.total / pagination.perPage)"
									:class="[
										pagination.currentPage >= Math.ceil(pagination.total / pagination.perPage)
											? 'bg-gray-100 text-gray-400 cursor-not-allowed'
											: 'bg-white text-gray-700 hover:bg-gray-100',
										'py-1.5 px-3 border border-gray-300 rounded-md text-xs font-medium focus:outline-none focus:ring-1 focus:ring-emerald-500 focus:border-emerald-500'
									]"
							>
								Next
							</button>
						</div>
					</div>
				</div>
			</div>
		</div>

		<!-- Loading Overlay -->
		<div v-if="isToggling" class="fixed inset-0 z-50 bg-black bg-opacity-30 flex justify-center items-center">
			<div class="bg-white px-6 py-4 rounded-lg shadow-lg text-center">
				<div class="w-8 h-8 border-4 border-solid border-emerald-500 border-t-transparent rounded-full animate-spin mx-auto mb-3"></div>
				<p class="text-sm text-gray-700">Updating status...</p>
			</div>
		</div>
	</AppLayout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { Head } from '@inertiajs/vue3';

// Props from controller
const props = defineProps({
    products: {
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
        default: 'Manage Product Status'
    }
});

// Data
const products = ref(props.products || []);
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

// Fetch products with pagination (including all statuses for obsolete/unobsolete page)
const fetchProducts = async (page = 1) => {
    loading.value = true;
    
    try {
        const response = await fetch(`/api/products?page=${page}&all_status=1`, {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        
        if (!response.ok) {
            throw new Error('Failed to fetch products');
        }
        
        const data = await response.json();
        
        if (data) {
            products.value = data;
            pagination.value = {
                currentPage: page,
                perPage: 15,
                total: data.length
            };
        }
    } catch (error) {
        console.error('Error fetching products:', error);
        showNotification('Error loading products: ' + error.message, 'error');
    } finally {
        loading.value = false;
    }
};

// Filter products based on search query and status filter
const filteredProducts = computed(() => {
    let filtered = [...products.value];
    
    // Apply search filter
    if (searchQuery.value) {
        const query = searchQuery.value.toLowerCase();
        filtered = filtered.filter(product => 
            product.product_code.toLowerCase().includes(query) || 
            product.description.toLowerCase().includes(query) ||
            (product.category && product.category.toLowerCase().includes(query))
        );
    }
    
    // Apply status filter
    if (statusFilter.value !== 'all') {
        const targetStatus = statusFilter.value === 'active' ? 'Act' : 'Obs';
        filtered = filtered.filter(product => product.status === targetStatus);
    }
    
    return filtered;
});

// Toggle product status
const toggleProductStatus = async (product) => {
    if (isToggling.value) return;
    
    const confirmMessage = `Are you sure you want to change the status for "${product.product_code} - ${product.description}"?`;
    if (!confirm(confirmMessage)) return;
    
    isToggling.value = true;
    
    try {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
        
        if (!csrfToken) {
            throw new Error('CSRF token not found');
        }
        
        const response = await fetch(`/api/products/${product.id}/status`, {
            method: 'PUT',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        });
        
        if (!response.ok) {
            throw new Error('Failed to toggle product status');
        }
        
        const result = await response.json();
        
        if (result.success) {
            // Update the local state
            product.status = (product.status === 'Act') ? 'Obs' : 'Act';
            
            // Show success message
            const statusText = (product.status === 'Act') ? 'activated' : 'deactivated';
            showNotification(`Product "${product.product_code}" successfully ${statusText}`, 'success');
        } else {
            throw new Error(result.message || 'Unknown error');
        }
    } catch (error) {
        console.error('Error toggling product status:', error);
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
    fetchProducts();
});
</script>
