<template>
    <AppLayout :header="'Product'">
    <Head title="Product Management" />

    <!-- Header Section -->
    <div class="bg-gradient-to-r from-cyan-700 to-blue-600 p-6 rounded-t-lg shadow-lg">
        <h2 class="text-2xl font-bold text-white mb-2 flex items-center">
            <i class="fas fa-box mr-3"></i> Define Product
        </h2>
        <p class="text-cyan-100">Define products and manage their categories</p>
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
                        <h3 class="text-xl font-semibold text-gray-800">Product Management</h3>
                    </div>
                    
                    <!-- Search Section -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-6">
                        <div class="col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Product Code:</label>
                            <div class="relative flex">
                                <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500">
                                    <i class="fas fa-box"></i>
                                </span>
                                <input type="text" v-model="searchQuery" class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none border border-gray-300 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                <button type="button" @click="showModal = true" class="inline-flex items-center px-3 py-2 border border-l-0 border-gray-300 bg-blue-500 hover:bg-blue-600 text-white rounded-r-md">
                                    <i class="fas fa-table"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-span-1">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Action:</label>
                            <button type="button" @click="createNewProduct" class="w-full flex items-center justify-center px-4 py-2 bg-indigo-500 hover:bg-indigo-600 text-white rounded">
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
                            <p class="text-sm font-medium text-yellow-800">Loading product data...</p>
                        </div>
                    </div>
                    <div v-else-if="products.length === 0" class="mt-4 bg-yellow-100 p-3 rounded">
                        <p class="text-sm font-medium text-yellow-800">No product data available.</p>
                        <p class="text-xs text-yellow-700 mt-1">Make sure the database is properly configured and seeders have been run.</p>
                        <div class="mt-2 flex items-center space-x-3">
                            <button @click="fetchProducts" class="bg-blue-500 hover:bg-blue-600 text-white text-xs px-3 py-1 rounded">Reload Data</button>
                        </div>
                    </div>
                    <div v-else class="mt-4 bg-green-100 p-3 rounded">
                        <p class="text-sm font-medium text-green-800">Data available: {{ products.length }} products found.</p>
                        <p v-if="selectedRow" class="text-xs text-green-700 mt-1">
                            Selected: <span class="font-semibold">{{ selectedRow.product_code }}</span> - {{ selectedRow.description }} ({{ selectedRow.category }})
                        </p>
                    </div>
                </div>
            </div>
            <!-- Right Column - Quick Info -->
            <div class="lg:col-span-1">
                <!-- Product Info Card -->
                <div class="bg-white p-6 rounded-lg shadow-md border-t-4 border-teal-500 mb-6">
                    <div class="flex items-center mb-4 pb-2 border-b border-gray-200">
                        <div class="p-2 bg-teal-500 rounded-lg mr-3">
                            <i class="fas fa-info-circle text-white"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-800">Product Information</h3>
                    </div>

                    <div class="space-y-4">
                        <div class="p-4 bg-teal-50 rounded-lg">
                            <h4 class="text-sm font-semibold text-teal-800 uppercase tracking-wider mb-2">Instructions</h4>
                            <ul class="list-disc pl-5 text-sm text-gray-600 space-y-1">
                                <li>Product code must be unique</li>
                                <li>Use the <span class="font-medium">search</span> button to select a product</li>
                                <li>Assign a category to each product</li>
                                <li>Any changes must be saved</li>
                            </ul>
                        </div>

                        <div class="p-4 bg-blue-50 rounded-lg">
                            <h4 class="text-sm font-semibold text-blue-800 uppercase tracking-wider mb-2">Common Categories</h4>
                            <div class="grid grid-cols-1 gap-2 text-sm">
                                <div class="flex items-center">
                                    <span class="w-6 h-6 flex items-center justify-center bg-blue-500 text-white rounded-full font-bold mr-2">1</span>
                                    <span>Corrugated Carton Box</span>
                                </div>
                                <div class="flex items-center">
                                    <span class="w-6 h-6 flex items-center justify-center bg-green-500 text-white rounded-full font-bold mr-2">2</span>
                                    <span>Single Facer Roll</span>
                                </div>
                                <div class="flex items-center">
                                    <span class="w-6 h-6 flex items-center justify-center bg-purple-500 text-white rounded-full font-bold mr-2">3</span>
                                    <span>Single Facer Roll/KG</span>
                                </div>
                                <div class="flex items-center">
                                    <span class="w-6 h-6 flex items-center justify-center bg-yellow-500 text-white rounded-full font-bold mr-2">7</span>
                                    <span>Other Packaging Products</span>
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
                        <Link href="/product-group" class="flex items-center p-3 bg-purple-50 rounded-lg hover:bg-purple-100 transition-colors">
                            <div class="p-2 bg-purple-500 rounded-full mr-3">
                                <i class="fas fa-layer-group text-white text-sm"></i>
                            </div>
                            <div>
                                <p class="font-medium text-purple-900">Product Groups</p>
                                <p class="text-xs text-purple-700">Manage product groups</p>
                            </div>
                        </Link>

                        <Link href="/product-design" class="flex items-center p-3 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                            <div class="p-2 bg-blue-500 rounded-full mr-3">
                                <i class="fas fa-th-list text-white text-sm"></i>
                            </div>
                            <div>
                                <p class="font-medium text-blue-900">Product Designs</p>
                                <p class="text-xs text-blue-700">Manage product designs</p>
                            </div>
                        </Link>

                        <Link href="/product/view-print" class="flex items-center p-3 bg-green-50 rounded-lg hover:bg-green-100 transition-colors">
                            <div class="p-2 bg-green-500 rounded-full mr-3">
                                <i class="fas fa-print text-white text-sm"></i>
                            </div>
                            <div>
                                <p class="font-medium text-green-900">Print List</p>
                                <p class="text-xs text-green-700">Print product list</p>
                            </div>
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Table -->
    <ProductModal
        :show="showModal"
        :products="products"
        :categories="categories"
        :loading="loading"
        @close="showModal = false"
        @select="onProductSelected"
    />

    <!-- Edit Modal -->
    <div v-if="showEditModal" class="fixed inset-0 z-50 bg-black bg-opacity-50 flex items-center justify-center">
        <div class="bg-white rounded-lg shadow-xl w-11/12 md:w-2/5 max-w-md mx-auto">
            <div class="flex items-center justify-between p-4 border-b border-gray-200 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-t-lg">
                <div class="flex items-center">
                    <div class="p-2 bg-white bg-opacity-30 rounded-lg mr-3">
                        <i class="fas fa-box"></i>
                    </div>
                    <h3 class="text-xl font-semibold">{{ isCreating ? 'Create Product' : 'Edit Product' }}</h3>
                </div>
                <button type="button" @click="closeEditModal" class="text-white hover:text-gray-200">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div class="p-6">
                <form @submit.prevent="saveProductChanges" class="space-y-4">
                    <div class="grid grid-cols-1 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Product Code:</label>
                            <input v-model="editForm.product_code" type="text" class="block w-full rounded-md border-gray-300 shadow-sm" :class="{ 'bg-gray-100': !isCreating }" :readonly="!isCreating" required>
                            <span class="text-xs text-gray-500">Product code must be unique</span>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Description:</label>
                            <input v-model="editForm.description" type="text" class="block w-full rounded-md border-gray-300 shadow-sm" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Category:</label>
                            <template v-if="isCreating">
                                <select v-model="editForm.category" class="block w-full rounded-md border-gray-300 shadow-sm" required>
                                    <option value="">Select a category</option>
                                    <option value="1-Corrugated Carton Box">1-Corrugated Carton Box</option>
                                    <option value="2-Single Facer Roll">2-Single Facer Roll</option>
                                    <option value="3-Single Facer Roll/KG">3-Single Facer Roll/KG</option>
                                    <option value="4-Single Facer Sheet">4-Single Facer Sheet</option>
                                    <option value="5-Corrugated Sheet Board/Piece">5-Corrugated Sheet Board/Piece</option>
                                    <option value="6-Corrugated Sheet Board/M2">6-Corrugated Sheet Board/M2</option>
                                    <option value="7-Other Packaging Products">7-Other Packaging Products</option>
                                </select>
                                <span class="text-xs text-gray-500">Select from one of the predefined product categories</span>
                            </template>
                            <template v-else>
                                <input type="text" :value="editForm.category" class="block w-full rounded-md border-gray-300 shadow-sm bg-gray-100" readonly>
                                <span class="text-xs text-gray-500">Category cannot be changed after creation</span>
                            </template>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Product Group ID:</label>
                            <select v-model="editForm.product_group_id" class="block w-full rounded-md border-gray-300 shadow-sm" required>
                                <option value="">Select a product group</option>
                                <option v-for="group in productGroups" :key="group.id || group.product_group_id" :value="group.product_group_id">
                                    {{ group.product_group_id }} - {{ group.product_group_name }}
                                </option>
                            </select>
                            <span class="text-xs text-gray-500 mt-1">Required: Select a valid product group (e.g., B for Box, R for Roll)</span>
                        </div>
                        <div>
                            <label class="flex items-center">
                                <input type="checkbox" v-model="editForm.is_active" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                <span class="ml-2 text-sm text-gray-700">Active</span>
                            </label>
                        </div>
                    </div>
                    <div class="flex justify-between mt-6 pt-4 border-t border-gray-200">
                        <button type="button" v-if="!isCreating" @click="deleteProduct(editForm.id)" class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600">
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
import ProductModal from '@/Components/product-modal.vue';

// Get the header from props
const props = defineProps({
    header: {
        type: String,
        default: 'Product Management'
    }
});

const products = ref([]);
const categories = ref([]);
const loading = ref(false);
const saving = ref(false);
const showModal = ref(false);
const showEditModal = ref(false);
const selectedRow = ref(null);
const searchQuery = ref('');
const editForm = ref({ 
    id: '', 
    product_code: '', 
    description: '',
    category: '',
    product_group_id: '',
    is_active: true
});
const isCreating = ref(false);
const notification = ref({ show: false, message: '', type: 'success' });
const productGroups = ref([]);

const fetchProducts = async () => {
    loading.value = true;
    try {
        console.log('Fetching products from API...');
        const res = await fetch('/api/products', { 
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
        
        if (Array.isArray(data)) {
            products.value = data.map(product => ({
                id: product.id,
                product_code: product.product_code,
                description: product.description,
                category: product.category || '1-Corrugated Carton Box', // Default to standard category if empty
                product_group_id: product.product_group_id,
                is_active: typeof product.is_active !== 'undefined' ? product.is_active : true
            }));
            console.log('Processed products data:', products.value);
        } else {
            products.value = [];
            console.error('Unexpected data format:', data);
        }
    } catch (e) {
        console.error('Error fetching products:', e);
        products.value = [];
    } finally {
        loading.value = false;
    }
};

const fetchCategories = async () => {
    try {
        // Use the correct endpoint for categories
        const res = await fetch('/categories', { 
            headers: { 
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            } 
        });
        
        if (!res.ok) {
            throw new Error('Network response was not ok');
        }
        
        const data = await res.json();
        console.log('Categories API response:', data);
        
        // Transform the data to match our component needs
        if (Array.isArray(data)) {
            categories.value = data.map(category => ({
                id: category.id || category.category_code || category,
                name: category.name || category.id || category
            }));
        } else {
            // Fallback to hardcoded categories matching ProductSeeder.php if API fails
            categories.value = [
                { id: '1-Corrugated Carton Box', name: '1-Corrugated Carton Box' },
                { id: '2-Single Facer Roll', name: '2-Single Facer Roll' },
                { id: '3-Single Facer Roll/KG', name: '3-Single Facer Roll/KG' },
                { id: '4-Single Facer Sheet', name: '4-Single Facer Sheet' },
                { id: '5-Corrugated Sheet Board/Piece', name: '5-Corrugated Sheet Board/Piece' },
                { id: '6-Corrugated Sheet Board/M2', name: '6-Corrugated Sheet Board/M2' },
                { id: '7-Other Packaging Products', name: '7-Other Packaging Products' }
            ];
        }
        console.log('Processed categories:', categories.value);
    } catch (e) {
        console.error('Error fetching categories:', e);
        // Fallback to hardcoded categories matching ProductSeeder.php
        categories.value = [
            { id: '1-Corrugated Carton Box', name: '1-Corrugated Carton Box' },
            { id: '2-Single Facer Roll', name: '2-Single Facer Roll' },
            { id: '3-Single Facer Roll/KG', name: '3-Single Facer Roll/KG' },
            { id: '4-Single Facer Sheet', name: '4-Single Facer Sheet' },
            { id: '5-Corrugated Sheet Board/Piece', name: '5-Corrugated Sheet Board/Piece' },
            { id: '6-Corrugated Sheet Board/M2', name: '6-Corrugated Sheet Board/M2' },
            { id: '7-Other Packaging Products', name: '7-Other Packaging Products' }
        ];
    }
};

const fetchProductGroups = async () => {
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
        console.log('Product Groups API response:', data);
        
        if (Array.isArray(data)) {
            productGroups.value = data;
            console.log('Processed product groups:', productGroups.value);
        } else {
            productGroups.value = [];
            console.error('Unexpected data format for product groups:', data);
        }
    } catch (e) {
        console.error('Error fetching product groups:', e);
        productGroups.value = [];
    }
};

onMounted(() => {
    fetchProducts();
    fetchCategories();
    fetchProductGroups();
});

// Watch for changes in search query to filter the data
watch(searchQuery, (newQuery) => {
    if (newQuery && products.value.length > 0) {
        const foundProduct = products.value.find(product => 
            product.product_code.toLowerCase().includes(newQuery.toLowerCase()) ||
            product.description.toLowerCase().includes(newQuery.toLowerCase())
        );
        
        if (foundProduct) {
            selectedRow.value = foundProduct;
        }
    }
});

const onProductSelected = (product) => {
    console.log('Product selected from modal:', product);
    selectedRow.value = product;
    searchQuery.value = product.product_code;
    showModal.value = false;
    
    // Automatically open the edit modal for the selected row
    isCreating.value = false;
    editForm.value = {
        id: product.id,
        product_code: product.product_code,
        description: product.description,
        category: product.category,
        product_group_id: product.product_group_id || '',
        is_active: product.is_active
    };
    console.log('Editing product with category:', editForm.value.category);
    showEditModal.value = true;
};

const editSelectedRow = () => {
    if (selectedRow.value) {
        isCreating.value = false;
        editForm.value = {
            id: selectedRow.value.id,
            product_code: selectedRow.value.product_code,
            description: selectedRow.value.description,
            category: selectedRow.value.category,
            product_group_id: selectedRow.value.product_group_id,
            is_active: selectedRow.value.is_active
        };
        console.log('Editing selected row:', editForm.value);
        showEditModal.value = true;
    } else {
        showNotification('Please select a product first', 'error');
    }
};

const createNewProduct = () => {
    isCreating.value = true;
    editForm.value = { 
        id: '', 
        product_code: '', 
        description: '',
        category: '1-Corrugated Carton Box', // Default to standard category
        product_group_id: '',
        is_active: true
    };
    console.log('Creating new product with categories:', categories.value);
    console.log('Available product groups:', productGroups.value);
    showEditModal.value = true;
};

const closeEditModal = () => {
    showEditModal.value = false;
    editForm.value = { 
        id: '', 
        product_code: '', 
        description: '',
        category: '1-Corrugated Carton Box', // Default to standard category
        product_group_id: '',
        is_active: true
    };
    isCreating.value = false;
};

const saveProductChanges = async () => {
    saving.value = true;
    try {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
        
        // Different API call for create vs update
        let url = isCreating.value ? '/api/products' : `/api/products/${editForm.value.id}`;
        let method = isCreating.value ? 'POST' : 'PUT';
        
        console.log('Saving product with URL:', url);
        console.log('Method:', method);
        console.log('Form data before save:', editForm.value);
        
        // Client-side validation
        if (!editForm.value.description.trim()) {
            showNotification('Description field is required', 'error');
            saving.value = false;
            return;
        }
        
        if (!editForm.value.product_group_id) {
            showNotification('Product Group ID is required', 'error');
            saving.value = false;
            return;
        }
        
        // Prepare data for API request - key point: the API expects 'name' field but our form uses 'description'
        const requestData = {
            product_code: editForm.value.product_code,
            name: editForm.value.description, // This mapping is critical - 'name' is required by the API
            description: editForm.value.description,
            category_id: editForm.value.category, // API expects category_id
            category: editForm.value.category,
            product_group_id: editForm.value.product_group_id,
            is_active: editForm.value.is_active
        };
        
        console.log('Sending data to API:', requestData);
        
        const response = await fetch(url, {
            method: method,
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify(requestData)
        });
        
        const result = await response.json();
        console.log('API response:', result);
        
        if (response.ok || result.success) {
            // Update the local data with the changes or add new item
            if (isCreating.value) {
                showNotification('Product created successfully', 'success');
            } else {
                if (selectedRow.value) {
                    selectedRow.value.description = editForm.value.description;
                    selectedRow.value.category = editForm.value.category;
                    selectedRow.value.product_group_id = editForm.value.product_group_id;
                    selectedRow.value.is_active = editForm.value.is_active;
                }
                showNotification('Product updated successfully', 'success');
            }
            
            // Refresh the full data list to ensure we're in sync with the database
            await fetchProducts();
            closeEditModal();
        } else {
            // Handle specific error messages from the API
            let errorMessage = 'Unknown error';
            
            if (result.message) {
                errorMessage = result.message;
            }
            
            showNotification('Error: ' + errorMessage, 'error');
            console.error('API error details:', result);
        }
    } catch (e) {
        console.error('Error saving product changes:', e);
        showNotification('Error saving product. Please try again.', 'error');
    } finally {
        saving.value = false;
    }
};

const deleteProduct = async (productId) => {
    if (!confirm(`Are you sure you want to delete this product?`)) {
        return;
    }
    
    saving.value = true;
    try {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
        
        // Find the product in our local array to get its product_code
        const productToDelete = products.value.find(p => p.id === productId);
        if (!productToDelete) {
            showNotification('Product not found in local data', 'error');
            saving.value = false;
            return;
        }
        
        const productCode = productToDelete.product_code;
        console.log('Deleting product with ID:', productId, 'and code:', productCode);
        
        // Use product_code in the API endpoint instead of id
        const response = await fetch(`/api/products/${productCode}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        });
        
        const result = await response.json();
        console.log('Delete API response:', result);
        
        if (result.success) {
            // Remove the item from the local array
            products.value = products.value.filter(product => product.id !== productId);
            
            if (selectedRow.value && selectedRow.value.id === productId) {
                selectedRow.value = null;
                searchQuery.value = '';
            }
            
            closeEditModal();
            showNotification('Product deleted successfully', 'success');
        } else {
            showNotification('Error deleting product: ' + (result.message || 'Unknown error'), 'error');
        }
    } catch (e) {
        console.error('Error deleting product:', e);
        showNotification('Error deleting product. Please try again.', 'error');
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

const getCategoryName = (categoryId) => {
    const category = categories.value.find(c => c.id === categoryId);
    return category ? category.name : 'Unknown';
};
</script>
