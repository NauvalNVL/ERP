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
                    <i class="fas fa-file-pdf mr-2"></i> Print PDF
                </button>
                <Link href="/product" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded flex items-center space-x-2">
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
                <table class="min-w-full border-collapse">
                    <thead class="bg-blue-600" style="background-color: #2563eb;">
                        <tr>
                            <th @click="sortTable('product_code')" class="px-4 py-2 text-left font-semibold border border-gray-300 cursor-pointer" style="color: black;">
                                Product Code <i :class="getSortIcon('product_code')" class="text-xs"></i>
                            </th>
                            <th @click="sortTable('description')" class="px-4 py-2 text-left font-semibold border border-gray-300 cursor-pointer" style="color: black;">
                                Description <i :class="getSortIcon('description')" class="text-xs"></i>
                            </th>
                            <th @click="sortTable('product_group_id')" class="px-4 py-2 text-left font-semibold border border-gray-300 cursor-pointer" style="color: black;">
                                Product Group <i :class="getSortIcon('product_group_id')" class="text-xs"></i>
                            </th>
                            <th @click="sortTable('category')" class="px-4 py-2 text-left font-semibold border border-gray-300 cursor-pointer" style="color: black;">
                                Category <i :class="getSortIcon('category')" class="text-xs"></i>
                            </th>
                            <th @click="sortTable('is_active')" class="px-4 py-2 text-left font-semibold border border-gray-300 cursor-pointer" style="color: black;">
                                Active <i :class="getSortIcon('is_active')" class="text-xs"></i>
                            </th>
                            <th @click="sortTable('created_at')" class="px-4 py-2 text-left font-semibold border border-gray-300 cursor-pointer" style="color: black;">
                                Created At <i :class="getSortIcon('created_at')" class="text-xs"></i>
                            </th>
                            <th @click="sortTable('updated_at')" class="px-4 py-2 text-left font-semibold border border-gray-300 cursor-pointer" style="color: black;">
                                Updated At <i :class="getSortIcon('updated_at')" class="text-xs"></i>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white">
                        <tr v-if="loading">
                            <td colspan="7" class="px-4 py-3 text-center text-gray-500 border border-gray-300">
                                <div class="flex justify-center">
                                    <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-blue-500"></div>
                                </div>
                                <p class="mt-2">Loading product data...</p>
                            </td>
                        </tr>
                        <tr v-else-if="filteredProducts.length === 0">
                            <td colspan="7" class="px-4 py-3 text-center text-gray-500 border border-gray-300">
                                No products found. 
                                <template v-if="searchQuery">
                                    <p class="mt-2">No results match your search query: "{{ searchQuery }}"</p>
                                    <button @click="searchQuery = ''" class="mt-2 text-blue-500 hover:underline">Clear search</button>
                                </template>
                            </td>
                        </tr>
                        <tr v-for="(product, index) in filteredProducts" :key="product.id" 
                            :class="index % 2 === 0 ? 'bg-blue-100' : 'bg-white'"
                            class="hover:bg-blue-200">
                            <td class="px-4 py-2 border border-gray-300">
                                <div class="text-sm font-medium text-gray-900">{{ product.product_code || 'N/A' }}</div>
                            </td>
                            <td class="px-4 py-2 border border-gray-300">
                                <div class="text-sm text-gray-900">{{ product.description || 'N/A' }}</div>
                            </td>
                            <td class="px-4 py-2 border border-gray-300">
                                <div class="text-sm text-gray-900">{{ getProductGroupName(product) }}</div>
                            </td>
                            <td class="px-4 py-2 border border-gray-300">
                                <div class="text-sm text-gray-900">{{ product.category || 'N/A' }}</div>
                            </td>
                            <td class="px-4 py-2 border border-gray-300">
                                <div class="text-sm text-gray-900">{{ product.is_active ? 'Yes' : 'No' }}</div>
                            </td>
                            <td class="px-4 py-2 border border-gray-300">
                                <div class="text-sm text-gray-900">{{ formatDate(product.created_at) }}</div>
                            </td>
                            <td class="px-4 py-2 border border-gray-300">
                                <div class="text-sm text-gray-900">{{ formatDate(product.updated_at) }}</div>
                            </td>
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
                <i class="fas fa-info-circle mr-2"></i> PDF Export Instructions
            </h3>
            <ul class="list-disc pl-5 text-sm text-gray-600 space-y-1">
                <li>Click the "Print PDF" button above to generate and download PDF</li>
                <li>PDF will be automatically saved in landscape orientation</li>
                <li>You can search or sort data before exporting</li>
                <li>PDF includes formatted table with headers and page numbers</li>
            </ul>
        </div>
    </div>
    </AppLayout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import jsPDF from 'jspdf';
import autoTable from 'jspdf-autotable';

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
        console.log('Raw products API response:', data);
        products.value = Array.isArray(data) ? data : (data.data || []);
        console.log('Processed products:', products.value);
        
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
                console.log('Raw product groups API response:', groupsData);
                // Handle both array and object with data property
                productGroups.value = Array.isArray(groupsData) ? groupsData : (groupsData.data || []);
                console.log('Processed product groups:', productGroups.value);
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
    // Match by product_group_id (most common)
    const group = productGroups.value.find(g => {
        return g.product_group_id === product.product_group_id || 
               g.id === product.product_group_id ||
               String(g.product_group_id) === String(product.product_group_id);
    });
    
    if (group) {
        // Return the group name with fallback
        return group.product_group_name || group.name || group.product_group_id;
    }
    
    // If we have the product_group object embedded, use that
    if (product.product_group) {
        if (typeof product.product_group === 'object') {
            return product.product_group.product_group_name || product.product_group.name || product.product_group_id;
        }
        return product.product_group;
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

// Get sort icon
const getSortIcon = (column) => {
    if (sortColumn.value !== column) {
        return 'fas fa-sort text-gray-400';
    }
    
    return sortDirection.value === 'asc' 
        ? 'fas fa-sort-up text-blue-600' 
        : 'fas fa-sort-down text-blue-600';
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

// Print function using jsPDF
const printTable = () => {
    try {
        const doc = new jsPDF({
            orientation: 'landscape',
            unit: 'mm',
            format: 'a4'
        });

        // Add title
        doc.setFontSize(16);
        doc.setTextColor(37, 99, 235); // Blue color
        doc.text('PRODUCT LIST', 10, 15);

        // Add subtitle
        doc.setFontSize(10);
        doc.setTextColor(100);
        doc.text('View and print product data', 10, 22);

        // Prepare table data
        const tableData = filteredProducts.value.map(product => [
            product.product_code || 'N/A',
            product.description || 'N/A',
            getProductGroupName(product),
            product.category || 'N/A',
            product.is_active ? 'Yes' : 'No',
            formatDate(product.created_at),
            formatDate(product.updated_at)
        ]);

        // Add table using autoTable
        autoTable(doc, {
            startY: 28,
            head: [['Product Code', 'Description', 'Product Group', 'Category', 'Active', 'Created At', 'Updated At']],
            body: tableData,
            theme: 'grid',
            tableWidth: 'auto',
            headStyles: {
                fillColor: [37, 99, 235], // Blue background
                textColor: [255, 255, 255], // White text
                fontStyle: 'bold',
                halign: 'left',
                fontSize: 9
            },
            bodyStyles: {
                textColor: [50, 50, 50],
                halign: 'left',
                fontSize: 8
            },
            alternateRowStyles: {
                fillColor: [219, 234, 254] // Light blue for alternate rows
            },
            margin: { top: 28, left: 10, right: 10 },
            columnStyles: {
                0: { cellWidth: 25 },  // Product Code
                1: { cellWidth: 60 },  // Description
                2: { cellWidth: 35 },  // Product Group
                3: { cellWidth: 45 },  // Category
                4: { cellWidth: 20 },  // Active
                5: { cellWidth: 35 },  // Created At
                6: { cellWidth: 35 }   // Updated At
            }
        });

        // Add footer
        const pageCount = doc.internal.getNumberOfPages();
        const pageHeight = doc.internal.pageSize.height;
        
        for (let i = 1; i <= pageCount; i++) {
            doc.setPage(i);
            doc.setFontSize(8);
            doc.setTextColor(100);
            doc.text(
                `Total Products: ${filteredProducts.value.length} | Generated: ${currentDate}`,
                10,
                pageHeight - 10
            );
            doc.text(
                `Page ${i} of ${pageCount}`,
                doc.internal.pageSize.width - 35,
                pageHeight - 10
            );
        }

        // Save PDF
        doc.save(`product-list-${new Date().getTime()}.pdf`);
    } catch (error) {
        console.error('Error generating PDF:', error);
        alert('Error generating PDF. Please try again.');
    }
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
