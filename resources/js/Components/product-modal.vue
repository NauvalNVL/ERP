<template>
  <div v-if="show" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-4xl">
      <!-- Modal Header -->
      <div class="flex items-center justify-between p-4 border-b border-gray-200 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-t-lg">
        <div class="flex items-center">
          <div class="p-2 bg-white bg-opacity-30 rounded-lg mr-3">
            <i class="fas fa-box"></i>
          </div>
          <h3 class="text-xl font-semibold">Product Table</h3>
        </div>
        <button @click="$emit('close')" class="text-white hover:text-gray-200 focus:outline-none transform active:translate-y-px">
          <i class="fas fa-times text-xl"></i>
        </button>
      </div>
      <!-- Modal Content -->
      <div class="p-5">
        <div class="mb-4">
          <div class="relative">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
              <i class="fas fa-search"></i>
            </span>
            <input type="text" v-model="searchQuery" placeholder="Search products..."
              class="pl-10 pr-4 py-2 w-full border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 bg-gray-50">
          </div>
        </div>
        <div class="overflow-x-auto rounded-lg border border-gray-200 max-h-96">
          <table class="w-full divide-y divide-gray-200">
            <thead class="bg-gray-50 sticky top-0">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-20 cursor-pointer" @click="sortTable('product_code')">Code</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/4 cursor-pointer" @click="sortTable('name')">Name</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-24 cursor-pointer" @click="sortTable('category_id')">Category</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer" @click="sortTable('description')">Description</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200 text-xs">
              <tr v-for="product in filteredProducts" :key="product.id"
                :class="['hover:bg-blue-50 cursor-pointer', selectedProduct && selectedProduct.id === product.id ? 'bg-blue-100 border-l-4 border-blue-500' : '']"
                @click="selectRow(product)"
                @dblclick="selectAndClose(product)">
                <td class="px-6 py-3 whitespace-nowrap font-medium text-gray-900">{{ product.product_code }}</td>
                <td class="px-6 py-3 whitespace-nowrap text-gray-700">{{ product.name }}</td>
                <td class="px-6 py-3 whitespace-nowrap">
                  <div class="inline-flex px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800 max-w-full overflow-hidden">
                    {{ getCategoryName(product.category_id) }}
                  </div>
                </td>
                <td class="px-6 py-3 text-gray-700 truncate max-w-xs">{{ product.description }}</td>
              </tr>
              <tr v-if="filteredProducts.length === 0">
                <td colspan="4" class="px-6 py-4 text-center text-gray-500">No product data available.</td>
              </tr>
            </tbody>
          </table>
        </div>
        <div class="mt-2 text-xs text-gray-500 italic">
          <p>Click on a row to select and edit its details.</p>
        </div>
        <div class="mt-4 grid grid-cols-5 gap-2">
          <button type="button" @click="sortTable('product_code')" class="py-2 px-3 bg-gray-100 border border-gray-400 hover:bg-gray-200 text-xs rounded-lg transform active:translate-y-px">
            <i class="fas fa-sort mr-1"></i>By Code
          </button>
          <button type="button" @click="sortTable('name')" class="py-2 px-3 bg-gray-100 border border-gray-400 hover:bg-gray-200 text-xs rounded-lg transform active:translate-y-px">
            <i class="fas fa-sort mr-1"></i>By Name
          </button>
          <button type="button" @click="sortByCategoryAndName()" class="py-2 px-3 bg-gray-100 border border-gray-400 hover:bg-gray-200 text-xs rounded-lg transform active:translate-y-px">
            <i class="fas fa-sort mr-1"></i>By Category
          </button>
          <button type="button" @click="selectAndClose(selectedProduct)" class="py-2 px-3 bg-blue-500 hover:bg-blue-600 text-white text-xs rounded-lg transform active:translate-y-px">
            <i class="fas fa-edit mr-1"></i>Select
          </button>
          <button type="button" @click="$emit('close')" class="py-2 px-3 bg-gray-300 hover:bg-gray-400 text-gray-800 text-xs rounded-lg transform active:translate-y-px">
            <i class="fas fa-times mr-1"></i>Exit
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, watch } from 'vue';

const props = defineProps({
  show: Boolean,
  products: {
    type: Array,
    default: () => []
  },
  categories: {
    type: Array,
    default: () => []
  }
});

const emit = defineEmits(['close', 'select']);

const searchQuery = ref('');
const selectedProduct = ref(null);
const sortKey = ref('product_code');
const sortAsc = ref(true);

// Compute filtered products based on search query
const filteredProducts = computed(() => {
  let products = props.products;
  if (searchQuery.value) {
    const q = searchQuery.value.toLowerCase();
    products = products.filter(p =>
      (p.product_code && p.product_code.toLowerCase().includes(q)) ||
      (p.name && p.name.toLowerCase().includes(q)) ||
      (p.description && p.description.toLowerCase().includes(q)) ||
      (getCategoryName(p.category_id) && getCategoryName(p.category_id).toLowerCase().includes(q))
    );
  }
  
  // Apply sorting
  return [...products].sort((a, b) => {
    let valA, valB;
    
    if (sortKey.value === 'product_code') {
      valA = a.product_code || '';
      valB = b.product_code || '';
    } else if (sortKey.value === 'category_id') {
      valA = getCategoryName(a.category_id) || '';
      valB = getCategoryName(b.category_id) || '';
    } else {
      valA = a[sortKey.value] || '';
      valB = b[sortKey.value] || '';
    }
    
    if (valA.toLowerCase && valB.toLowerCase) {
      valA = valA.toLowerCase();
      valB = valB.toLowerCase();
    }
    
    if (valA < valB) return sortAsc.value ? -1 : 1;
    if (valA > valB) return sortAsc.value ? 1 : -1;
    return 0;
  });
});

// Get category name by ID
function getCategoryName(categoryId) {
  if (!categoryId) return 'Uncategorized';
  
  // Try to find category in the categories prop
  if (props.categories && props.categories.length > 0) {
    const category = props.categories.find(c => c.id === categoryId);
    if (category) return category.name;
  }
  
  return categoryId;
}

// Select a row
function selectRow(product) {
  selectedProduct.value = product;
}

// Select and close modal
function selectAndClose(product) {
  if (product) {
    emit('select', product);
    emit('close');
  }
}

// Sort table by the given key
function sortTable(key) {
  if (sortKey.value === key) {
    sortAsc.value = !sortAsc.value;
  } else {
    sortKey.value = key;
    sortAsc.value = true;
  }
}

// Sort by category and name
function sortByCategoryAndName() {
  sortKey.value = 'category_id';
  sortAsc.value = true;
}

// Reset selection when modal is opened
watch(() => props.show, (val) => {
  if (val) {
    selectedProduct.value = null;
    searchQuery.value = '';
  }
});
</script>

<style scoped>
/* Add some custom styles to prevent text overflow */
.truncate {
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

/* Ensure proper spacing in table cells */
td {
  vertical-align: middle;
}

/* Improve category pill display */
.inline-flex {
  text-overflow: ellipsis;
  overflow: hidden;
}
</style> 