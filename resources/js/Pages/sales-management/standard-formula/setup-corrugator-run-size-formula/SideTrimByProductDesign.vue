<template>
  <AppLayout :header="'Define Side Trim By Product Design'">
    <Head title="Define Side Trim By Product Design" />

    <!-- Header Section -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-800 p-6 rounded-t-lg shadow-md">
      <h2 class="text-2xl font-bold text-white mb-2 flex items-center">
        <i class="fas fa-object-group mr-3"></i> Define Side Trim By Product Design
      </h2>
      <p class="text-blue-100">Manage side trim specifications for each product design combination</p>
    </div>

    <div class="bg-white rounded-b-lg shadow-md p-6 mb-6">
      <div class="flex flex-col md:flex-row gap-6">
        <!-- Main Content Area -->
        <div class="flex-1">
          <!-- Action Bar -->
          <div class="mb-6 flex flex-col md:flex-row gap-4 justify-between items-start md:items-center">
            <div class="flex-1 w-full">
              <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
                  <i class="fas fa-search"></i>
                </span>
                <input type="text" v-model="searchQuery" placeholder="Search by P/Design, Product, or Flute..."
                  class="pl-10 pr-4 py-2 w-full border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
              </div>
            </div>
            <div class="flex gap-2 w-full md:w-auto">
              <button @click="refreshData" class="btn-secondary flex-1 md:flex-none">
                <i class="fas fa-sync-alt mr-2"></i> Refresh
              </button>
              <button @click="printData" class="btn-info flex-1 md:flex-none">
                <i class="fas fa-print mr-2"></i> Print
              </button>
            </div>
          </div>

          <!-- Table Section -->
          <div class="bg-white rounded-lg overflow-hidden border border-gray-200">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                  <thead class="bg-gray-50">
                    <tr>
                    <th @click="sortBy('product_design_code')" class="th-sortable">P/Design</th>
                    <th @click="sortBy('product_code')" class="th-sortable">Product</th>
                    <th @click="sortBy('flute_code')" class="th-sortable">Flute</th>
                    <th @click="sortBy('compute')" class="th-sortable">Compute</th>
                    <th @click="sortBy('length_less')" class="th-sortable">Length Less (mm)</th>
                    <th @click="sortBy('length_add')" class="th-sortable">Length Add (mm)</th>
                    </tr>
                  </thead>
                  <tbody class="bg-white divide-y divide-gray-200">
                  <tr v-if="loading" class="animate-pulse">
                    <td colspan="6" class="table-cell-info">
                      <div class="flex justify-center items-center space-x-2">
                        <i class="fas fa-spinner fa-spin"></i>
                        <span>Loading side trims...</span>
                        </div>
                      </td>
                    </tr>
                  <tr v-else-if="paginatedItems.length === 0">
                    <td colspan="6" class="table-cell-info">
                      No side trims found. Try adjusting your search.
                    </td>
                  </tr>
                  <tr v-for="item in paginatedItems" :key="item.id" @click="selectItem(item)" 
                      :class="{'bg-blue-50': selectedItem && selectedItem.id === item.id}" class="hover:bg-gray-50 cursor-pointer">
                    <td class="table-cell-main">{{ item.product_design_code }}</td>
                    <td class="table-cell-default">{{ item.product_code }}</td>
                    <td class="table-cell-default">{{ item.flute_code }}</td>
                      <td class="px-6 py-4 whitespace-nowrap text-center">
                      <button @click.stop="toggleCompute(item)" :class="[
                        'px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2',
                        item.compute ? 'bg-green-100 text-green-800 hover:bg-green-200 focus:ring-green-500' : 'bg-red-100 text-red-800 hover:bg-red-200 focus:ring-red-500',
                        {'opacity-50 cursor-not-allowed': savingStatus[item.id]}
                      ]">
                        <i v-if="savingStatus[item.id + '-compute']" class="fas fa-spinner fa-spin mr-1"></i>
                        {{ item.compute ? 'Yes' : 'No' }}
                      </button>
                      </td>
                    <td class="px-2 py-1 whitespace-nowrap">
                      <input type="number" 
                             :value="item.length_less"
                             @input="handleInput(item, 'length_less', $event)"
                             @blur="updateTrim(item, 'length_less')"
                             class="table-input"
                             placeholder="Less"
                             :disabled="savingStatus[item.id]">
                      </td>
                    <td class="px-2 py-1 whitespace-nowrap">
                      <input type="number" 
                             :value="item.length_add"
                             @input="handleInput(item, 'length_add', $event)"
                             @blur="updateTrim(item, 'length_add')"
                             class="table-input"
                             placeholder="Add"
                             :disabled="savingStatus[item.id]">
                      </td>
                    </tr>
                  </tbody>
                </table>
            </div>
              </div>

          <!-- Pagination Controls -->
          <div class="mt-4 flex justify-between items-center text-sm text-gray-600">
            <div>
              <span>Showing {{ paginatedItems.length }} of {{ filteredItems.length }} results</span>
            </div>
            <div class="flex items-center space-x-2">
              <select v-model="itemsPerPage" class="border border-gray-300 rounded px-2 py-1 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option :value="10">10 per page</option>
                <option :value="20">20 per page</option>
                <option :value="50">50 per page</option>
                <option :value="100">100 per page</option>
              </select>
              <button :disabled="currentPage === 1" @click="currentPage--" class="pagination-btn">
                <i class="fas fa-chevron-left"></i>
                  </button>
              <span class="px-4">{{ currentPage }} / {{ totalPages }}</span>
              <button :disabled="currentPage === totalPages || totalPages === 0" @click="currentPage++" class="pagination-btn">
                <i class="fas fa-chevron-right"></i>
                  </button>
                </div>
              </div>
        </div>

        <!-- Side Panel with Details -->
        <div class="w-full md:w-96 bg-gray-50 rounded-lg p-4 border border-gray-200 space-y-6">
          <div>
            <h3 class="text-lg font-semibold text-gray-800 mb-3 flex items-center">
              <i class="fas fa-info-circle mr-2 text-blue-500"></i> Trim Details
            </h3>
            <div v-if="selectedItem" class="space-y-3">
              <div class="detail-item"><span>P/Design:</span><strong>{{ selectedItem.product_design_code }}</strong></div>
              <div class="detail-item"><span>Product:</span><strong>{{ selectedItem.product_code }}</strong></div>
              <div class="detail-item"><span>Flute:</span><strong>{{ selectedItem.flute_code }}</strong></div>
              <div class="detail-item">
                <span>Compute:</span>
                <strong :class="selectedItem.compute ? 'text-green-600' : 'text-red-600'">
                  {{ selectedItem.compute ? 'Yes' : 'No' }}
                </strong>
              </div>
              <div class="detail-item"><span>Length Less (mm):</span><strong>{{ selectedItem.length_less }}</strong></div>
              <div class="detail-item"><span>Length Add (mm):</span><strong>{{ selectedItem.length_add }}</strong></div>
            </div>
            <div v-else class="flex flex-col items-center justify-center h-40 text-center">
              <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mb-4">
                <i class="fas fa-object-group text-blue-500 text-2xl"></i>
                </div>
              <h3 class="text-lg font-medium text-gray-900 mb-1">No Item Selected</h3>
              <p class="text-gray-500 text-sm">Select an item from the table to view its details</p>
              </div>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue';
import axios from 'axios';
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import { useToast } from '@/Composables/useToast';

// --- State ---
const items = ref([]);
const selectedItem = ref(null);
const loading = ref(false);
    const searchQuery = ref('');
const sortOrder = ref({ field: 'product_design_code', direction: 'asc' });
const toast = useToast();
    const currentPage = ref(1);
    const itemsPerPage = ref(10);
const savingStatus = ref({});

// --- Computed Properties ---
const filteredItems = computed(() => {
  let result = items.value;
  if (searchQuery.value) {
    const query = searchQuery.value.toLowerCase();
    result = result.filter(item =>
      (item.product_design_code && item.product_design_code.toLowerCase().includes(query)) ||
      (item.product_code && item.product_code.toLowerCase().includes(query)) ||
      (item.flute_code && item.flute_code.toLowerCase().includes(query))
    );
  }

  return [...result].sort((a, b) => {
    const field = sortOrder.value.field;
    const direction = sortOrder.value.direction === 'asc' ? 1 : -1;
    const valA = a[field] ?? (typeof a[field] === 'number' ? -Infinity : '');
    const valB = b[field] ?? (typeof b[field] === 'number' ? -Infinity : '');

    if (valA < valB) return -1 * direction;
    if (valA > valB) return 1 * direction;
    
    // Secondary sort keys for consistent ordering
    if (a.product_design_code !== b.product_design_code) return a.product_design_code.localeCompare(b.product_design_code);
    if (a.product_code !== b.product_code) return a.product_code.localeCompare(b.product_code);
    return a.flute_code.localeCompare(b.flute_code);
  });
    });

    const totalPages = computed(() => Math.ceil(filteredItems.value.length / itemsPerPage.value));

    const paginatedItems = computed(() => {
  if (totalPages.value === 0) return [];
  if (currentPage.value > totalPages.value) {
    currentPage.value = totalPages.value;
  }
      const start = (currentPage.value - 1) * itemsPerPage.value;
      const end = start + itemsPerPage.value;
      return filteredItems.value.slice(start, end);
    });

// --- Watchers ---
watch(searchQuery, () => { currentPage.value = 1; });
watch(itemsPerPage, () => { currentPage.value = 1; });

// --- Methods ---
const handleInput = (item, field, event) => {
  const originalItem = items.value.find(i => i.id === item.id);
  if (originalItem) {
    originalItem[field] = Number(event.target.value);
  }
    };

    const loadData = async () => {
  loading.value = true;
  try {
    const response = await axios.get('/api/side-trims-by-product-design');

    if (response.data?.status === 'success') {
      items.value = response.data.data;
      if (items.value.length > 0 && !selectedItem.value) {
        selectItem(items.value[0]);
      }
        } else {
      toast.error(response.data?.message || 'Failed to load data.');
      items.value = [];
    }
      } catch (error) {
    console.error('Error loading data:', error);
    toast.error(error.response?.data?.message || 'An error occurred while loading data.');
    items.value = [];
      } finally {
        loading.value = false;
      }
    };

const refreshData = () => {
  searchQuery.value = '';
  loadData();
};

const selectItem = (item) => {
  selectedItem.value = item;
};

async function _saveTrimData(item) {
  const statusKey = item.id;
  savingStatus.value[statusKey] = true;

  try {
    const payload = [{
      product_design_id: item.product_design_id,
      product_id: item.product_id,
      flute_id: item.flute_id,
      compute: item.compute,
      length_less: item.length_less === '' || item.length_less === null ? 0 : Number(item.length_less),
      length_add: item.length_add === '' || item.length_add === null ? 0 : Number(item.length_add),
    }];
    
    if(typeof item.id !== 'string' || !item.id.startsWith('new-')) {
        payload[0].id = item.id;
    }

    const response = await axios.post('/api/side-trims-by-product-design/batch', payload);

    if (response.data.status === 'success') {
      const updatedResult = response.data.results?.[0];
      if (updatedResult && typeof item.id === 'string' && item.id.startsWith('new-')) {
        const index = items.value.findIndex(i => i.id === item.id);
        if (index !== -1) items.value[index].id = updatedResult.id;
        if (selectedItem.value?.id === item.id) selectedItem.value.id = updatedResult.id;
      }
      return true;
    } else {
      toast.error(response.data.message || 'An unknown error occurred during save.');
      return false;
    }
  } catch (error) {
    console.error(`Error saving data for item ${item.id}:`, error);
    toast.error(error.response?.data?.message || 'Failed to save changes.');
    return false;
  } finally {
    savingStatus.value[statusKey] = false;
  }
}

const updateTrim = async (item, field) => {
  const success = await _saveTrimData(item);
  if (success) {
    toast.success(`${item.product_design_code} / ${item.product_code} / ${item.flute_code} updated.`);
  } else {
    await loadData(); // Revert changes on failure
  }
};

const toggleCompute = async (item) => {
  const statusKey = item.id + '-compute';
  savingStatus.value[statusKey] = true;
  
  const originalValue = item.compute;
  item.compute = !originalValue;

  const success = await _saveTrimData(item);

  if (success) {
    toast.success(`Compute for ${item.product_design_code} updated to ${item.compute ? 'Yes' : 'No'}.`);
  } else {
    item.compute = originalValue; // Revert optimistic update
    toast.error(`Failed to update compute status.`);
  }
  savingStatus.value[statusKey] = false;
};

const sortBy = (field) => {
  if (sortOrder.value.field === field) {
    sortOrder.value.direction = sortOrder.value.direction === 'asc' ? 'desc' : 'asc';
  } else {
    sortOrder.value.field = field;
    sortOrder.value.direction = 'asc';
  }
};

const printData = () => {
    window.print();
};

onMounted(loadData);
</script>

<style>
.th-sortable {
  @apply px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer;
}
.th-sortable:hover {
  @apply bg-gray-100;
}
.table-cell-info {
  @apply px-6 py-4 text-center text-sm text-gray-500;
}
.table-cell-main {
  @apply px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900;
}
.table-cell-default {
  @apply px-6 py-4 whitespace-nowrap text-sm text-gray-600;
}
.table-input {
  @apply w-full px-2 py-1 text-center border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500;
}
.pagination-btn {
  @apply px-3 py-1 border rounded hover:bg-gray-200 disabled:opacity-50 disabled:cursor-not-allowed;
}
.detail-item {
  @apply flex justify-between border-b border-gray-200 pb-2;
}
.detail-item span {
  @apply text-gray-600;
}
.detail-item strong {
  @apply font-medium text-gray-900;
}
.btn-secondary {
  @apply bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg shadow-sm transition flex items-center justify-center whitespace-nowrap;
}
.btn-info {
  @apply bg-cyan-600 hover:bg-cyan-700 text-white px-4 py-2 rounded-lg shadow-sm transition flex items-center justify-center whitespace-nowrap;
}

@media print {
  body * {
    visibility: hidden;
  }
  .max-w-7xl, .max-w-7xl * {
    visibility: visible;
  }
  .max-w-7xl {
    position: absolute;
    left: 0;
    top: 0;
    width: 100%;
  }
  .bg-gradient-to-r, .md\:w-96, .flex-col.md\:flex-row.gap-4, .mt-4.flex.justify-between, .btn-secondary, .btn-info {
    display: none !important;
  }
  .table-input {
    border: none;
    background-color: transparent;
  }
}
</style>
