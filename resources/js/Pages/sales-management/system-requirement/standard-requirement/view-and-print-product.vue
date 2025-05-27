<template>
    <AppLayout :header="'View & Print Products'">
    <Head title="View & Print Products" />

    <!-- Header Section -->
    <div class="bg-gradient-to-r from-cyan-700 to-blue-600 p-6 rounded-t-lg shadow-lg">
        <h2 class="text-2xl font-bold text-white mb-2 flex items-center">
            <i class="fas fa-print mr-3"></i> View & Print Products
        </h2>
        <p class="text-cyan-100">Preview and print product data</p>
    </div>

    <div class="bg-white rounded-b-lg shadow-lg p-6 mb-6">
        <!-- Actions Bar -->
        <div class="flex flex-wrap items-center justify-between mb-6">
            <div class="flex items-center space-x-2 mb-3 sm:mb-0">
                <button @click="printTable" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded flex items-center space-x-2">
                    <i class="fas fa-print mr-2"></i> Print List
                </button>
                <Link href="/vue/product" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded flex items-center space-x-2">
                    <i class="fas fa-arrow-left mr-2"></i> Back to Products
                </Link>
            </div>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <i class="fas fa-search text-gray-400"></i>
                </div>
                <input 
                    type="text" 
                    v-model="searchQuery" 
                    class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                    placeholder="Search products..."
                >
            </div>
        </div>

        <!-- Table Section -->
        <div class="overflow-x-auto">
            <div id="printableTable" class="min-w-full bg-white border border-gray-200 rounded-lg overflow-hidden">
                <!-- Table Header -->
                <div class="bg-gradient-to-r from-blue-600 to-blue-700 text-white py-4 px-6 flex items-center">
                    <div class="flex items-center">
                        <div class="mr-4">
                            <i class="fas fa-box-open text-3xl"></i>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold">PRODUCT LIST</h2>
                            <p class="text-sm opacity-80">View and print product data</p>
                        </div>
                    </div>
                </div>

                <!-- Table Content -->
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th @click="sortTable('product_code')" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                                Product Code <i class="fas fa-sort ml-1"></i>
                            </th>
                            <th @click="sortTable('description')" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                                Description <i class="fas fa-sort ml-1"></i>
                            </th>
                            <th @click="sortTable('product_group_id')" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                                Product Group <i class="fas fa-sort ml-1"></i>
                            </th>
                            <th @click="sortTable('category')" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                                Category <i class="fas fa-sort ml-1"></i>
                            </th>
                            <th @click="sortTable('is_active')" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                                Active <i class="fas fa-sort ml-1"></i>
                            </th>
                            <th @click="sortTable('created_at')" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                                Created At <i class="fas fa-sort ml-1"></i>
                            </th>
                            <th @click="sortTable('updated_at')" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                                Updated At <i class="fas fa-sort ml-1"></i>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr v-if="loading" class="hover:bg-gray-50">
                            <td colspan="7" class="px-3 py-4 text-center text-gray-500">
                                <div class="flex justify-center">
                                    <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-blue-500"></div>
                                </div>
                                <p class="mt-2">Loading product data...</p>
                            </td>
                        </tr>
                        <tr v-else-if="filteredProducts.length === 0" class="hover:bg-gray-50">
                            <td colspan="7" class="px-3 py-4 text-center text-gray-500">
                                No products found. 
                                <template v-if="searchQuery">
                                    <p class="mt-2">No results match your search query: "{{ searchQuery }}"</p>
                                    <button @click="searchQuery = ''" class="mt-2 text-blue-500 hover:underline">Clear search</button>
                                </template>
                            </td>
                        </tr>
                        <tr v-for="(product, index) in filteredProducts" :key="product.id" 
                            :class="{'bg-blue-50': index % 2 === 0}" 
                            class="hover:bg-blue-100">
                            <td class="px-3 py-4 whitespace-nowrap text-sm">{{ product.product_code || 'N/A' }}</td>
                            <td class="px-3 py-4 whitespace-nowrap text-sm">{{ product.description || 'N/A' }}</td>
                            <td class="px-3 py-4 whitespace-nowrap text-sm">{{ getProductGroupName(product) }}</td>
                            <td class="px-3 py-4 whitespace-nowrap text-sm">{{ product.category || 'N/A' }}</td>
                            <td class="px-3 py-4 whitespace-nowrap text-sm">
                                <span :class="product.is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'" class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full">
                                    {{ product.is_active ? 'Yes' : 'No' }}
                                </span>
                            </td>
                            <td class="px-3 py-4 whitespace-nowrap text-sm">{{ formatDate(product.created_at) }}</td>
                            <td class="px-3 py-4 whitespace-nowrap text-sm">{{ formatDate(product.updated_at) }}</td>
                        </tr>
                    </tbody>
                </table>

                <!-- Table Footer -->
                <div class="bg-gray-50 px-6 py-3 border-t border-gray-200 text-sm text-gray-500">
                    <div class="flex items-center justify-between">
                        <div>Total Products: {{ filteredProducts.length }}</div>
                        <div v-if="searchQuery">Filtered from {{ products.length }} total records</div>
                        <div class="text-xs text-gray-400">Generated: {{ currentDate }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Print Instructions -->
        <div class="mt-6 bg-blue-50 p-4 rounded-lg border border-blue-100">
            <h3 class="font-semibold text-blue-800 mb-2 flex items-center">
                <i class="fas fa-info-circle mr-2"></i> Print Instructions
            </h3>
            <ul class="list-disc pl-5 text-sm text-gray-600 space-y-1">
                <li>Click the "Print List" button above to print this product list</li>
                <li>Use landscape orientation for better results</li>
                <li>You can search or sort data before printing</li>
                <li>Only the table will be included in the print output</li>
            </ul>
        </div>
    </div>
    </AppLayout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { Head, Link } from '@inertiajs/vue3';

// Data
const products = ref([]);
const productGroups = ref([]);
const loading = ref(true);
const searchQuery = ref('');
const sortColumn = ref('product_code');
const sortDirection = ref('asc');
const currentDate = new Date().toLocaleString();

// Fetch products from API
const fetchProducts = async () => {
    loading.value = true;
    try {
        // Fetch products
        const response = await fetch('/api/products', {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        
        if (!response.ok) {
            throw new Error('Failed to fetch products');
        }
        
        const data = await response.json();
        products.value = data;
        
        // Fetch product groups for better display
        try {
            const groupsResponse = await fetch('/api/product-groups', {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            if (groupsResponse.ok) {
                const groupsData = await groupsResponse.json();
                productGroups.value = groupsData;
            }
        } catch (error) {
            console.error('Error fetching product groups:', error);
        }
    } catch (error) {
        console.error('Error fetching products:', error);
    } finally {
        loading.value = false;
    }
};

// Get product group name helper
const getProductGroupName = (product) => {
    if (!product.product_group_id) return 'N/A';
    
    // Try to find the product group from the fetched list
    const group = productGroups.value.find(g => g.id === product.product_group_id || g.product_group_id === product.product_group_id);
    
    if (group && group.product_group_name) {
        return group.product_group_name;
    }
    
    // If we have the product_group object embedded, use that
    if (product.product_group && product.product_group.product_group_name) {
        return product.product_group.product_group_name;
    }
    
    // Fallback to just showing the ID
    return product.product_group_id;
};

// Sort function
const sortTable = (column) => {
    if (sortColumn.value === column) {
        sortDirection.value = sortDirection.value === 'asc' ? 'desc' : 'asc';
    } else {
        sortColumn.value = column;
        sortDirection.value = 'asc';
    }
};

// Format date
const formatDate = (dateString) => {
    if (!dateString) return 'N/A';
    return new Date(dateString).toLocaleString();
};

// Filtered and sorted products
const filteredProducts = computed(() => {
    let filtered = [...products.value];
    
    // Apply search filter
    if (searchQuery.value) {
        const query = searchQuery.value.toLowerCase();
        filtered = filtered.filter(product => 
            (product.product_code && product.product_code.toLowerCase().includes(query)) ||
            (product.description && product.description.toLowerCase().includes(query)) ||
            (product.category && product.category.toLowerCase().includes(query)) ||
            (getProductGroupName(product) && getProductGroupName(product).toLowerCase().includes(query))
        );
    }
    
    // Apply sorting
    filtered.sort((a, b) => {
        let valueA = a[sortColumn.value];
        let valueB = b[sortColumn.value];
        
        // Handle null values
        if (valueA === null || valueA === undefined) valueA = '';
        if (valueB === null || valueB === undefined) valueB = '';
        
        // Special case for product group column
        if (sortColumn.value === 'product_group_id') {
            valueA = getProductGroupName(a);
            valueB = getProductGroupName(b);
        }
        
        // Handle boolean column
        if (sortColumn.value === 'is_active') {
            if (sortDirection.value === 'asc') {
                return valueA === valueB ? 0 : valueA ? -1 : 1;
            } else {
                return valueA === valueB ? 0 : valueA ? 1 : -1;
            }
        }
        
        // Handle date columns
        if (['created_at', 'updated_at'].includes(sortColumn.value)) {
            const dateA = valueA ? new Date(valueA).getTime() : 0;
            const dateB = valueB ? new Date(valueB).getTime() : 0;
            
            if (sortDirection.value === 'asc') {
                return dateA - dateB;
            } else {
                return dateB - dateA;
            }
        }
        
        // Convert to string for comparison if not already
        if (typeof valueA !== 'string') valueA = String(valueA || '');
        if (typeof valueB !== 'string') valueB = String(valueB || '');
        
        // Case insensitive comparison
        valueA = valueA.toLowerCase();
        valueB = valueB.toLowerCase();
        
        // Sort direction
        if (sortDirection.value === 'asc') {
            return valueA.localeCompare(valueB);
        } else {
            return valueB.localeCompare(valueA);
        }
    });
    
    return filtered;
});

// Print function
const printTable = () => {
    const printContent = document.getElementById('printableTable');
    const newWin = window.open('', '_blank');

    newWin.document.write('<html><head><title>Print Products</title>');
    newWin.document.write('<style>');
    newWin.document.write('body { font-family: Arial, sans-serif; }');
    newWin.document.write('@page { size: landscape; }');
    newWin.document.write('table { width: 100%; border-collapse: collapse; }');
    newWin.document.write('th, td { border: 1px solid #ddd; padding: 4px; text-align: left; font-size: 10pt; }');
    newWin.document.write('th { background-color: #f2f2f2; font-weight: bold; }');
    newWin.document.write('tr:nth-child(even) { background-color: #f9f9f9; }');
    newWin.document.write('.header { background-color: #1e40af; color: white; padding: 10px; display: flex; align-items: center; }');
    newWin.document.write('.header-text { margin-left: 15px; }');
    newWin.document.write('.footer { background-color: #f2f2f2; padding: 8px; border-top: 1px solid #ddd; display: flex; justify-content: space-between; }');
    newWin.document.write('</style></head><body>');
    newWin.document.write(printContent.outerHTML);
    newWin.document.write('<script>window.onload = function() { window.print(); window.close(); }<\/script>');
    newWin.document.write('</body></html>');
    
    newWin.document.close();
};

// Lifecycle hooks
onMounted(() => {
    fetchProducts();
});
</script>

<style scoped>
@media print {
    body * {
        visibility: hidden;
    }
    #printableTable, #printableTable * {
        visibility: visible;
    }
    #printableTable {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
    }
}
</style>
