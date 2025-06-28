<template>
  <AppLayout :header="'Define Side Trim By Flute'">
    <Head title="Define Side Trim By Flute" />

    <!-- Header Section -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-800 p-6 rounded-t-lg shadow-md">
      <h2 class="text-2xl font-bold text-white mb-2 flex items-center">
        <i class="fas fa-cut mr-3"></i> Define Side Trim By Flute
      </h2>
      <p class="text-blue-100">Manage side trim specifications for each flute type and compute status.</p>
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
                <input type="text" v-model="searchQuery" placeholder="Search by flute code or name..."
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
              <table class="min-w-full divide-y divide-gray-200" id="sideTrimsTable">
                  <thead class="bg-gray-50">
                    <tr>
                    <th @click="sortBy('flute_code')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                      <div class="flex items-center">Flute <i class="fas fa-sort ml-1"></i></div>
                      </th>
                    <th @click="sortBy('flute_name')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                      <div class="flex items-center">Flute Name <i class="fas fa-sort ml-1"></i></div>
                      </th>
                    <th @click="sortBy('compute')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                      <div class="flex items-center">Compute <i class="fas fa-sort ml-1"></i></div>
                      </th>
                    <th @click="sortBy('length_less')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                      <div class="flex items-center">Length Less [mm] <i class="fas fa-sort ml-1"></i></div>
                      </th>
                    <th @click="sortBy('length_add')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                      <div class="flex items-center">Length Add [mm] <i class="fas fa-sort ml-1"></i></div>
                      </th>
                    </tr>
                  </thead>
                  <tbody class="bg-white divide-y divide-gray-200">
                  <tr v-if="loading" class="animate-pulse">
                    <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">
                      <div class="flex justify-center items-center space-x-2"><i class="fas fa-spinner fa-spin"></i><span>Loading side trims...</span></div>
                      </td>
                    </tr>
                  <tr v-else-if="paginatedItems.length === 0" class="hover:bg-gray-50">
                    <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">No side trims found. Try adjusting your search.</td>
                  </tr>
                  <tr v-for="item in paginatedItems" :key="item.id" @click="selectItem(item)" :class="{'bg-blue-50': selectedItem && selectedItem.id === item.id}" class="hover:bg-gray-50 cursor-pointer">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ item.flute_code }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ item.flute_name }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                      <button @click.stop="toggleCompute(item)" :class="[
                        'px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2',
                        item.compute ? 'bg-green-100 text-green-800 hover:bg-green-200 focus:ring-green-500' : 'bg-red-100 text-red-800 hover:bg-red-200 focus:ring-red-500'
                      ]">
                        {{ item.compute ? 'Yes' : 'No' }}
                      </button>
                      </td>
                    <td class="px-2 py-1 whitespace-nowrap">
                      <input type="number" :value="item.length_less" @input="handleInput(item, 'length_less', $event)" @blur="updateTrim(item)" class="w-full px-2 py-1 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Length Less">
                      </td>
                    <td class="px-2 py-1 whitespace-nowrap">
                      <input type="number" :value="item.length_add" @input="handleInput(item, 'length_add', $event)" @blur="updateTrim(item)" class="w-full px-2 py-1 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Length Add">
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
              <button :disabled="currentPage === 1" @click="currentPage--" class="px-2 py-1 border rounded hover:bg-gray-200 disabled:opacity-50"><i class="fas fa-chevron-left"></i></button>
              <span class="px-4">{{ currentPage }} / {{ totalPages }}</span>
              <button :disabled="currentPage === totalPages" @click="currentPage++" class="px-2 py-1 border rounded hover:bg-gray-200 disabled:opacity-50"><i class="fas fa-chevron-right"></i></button>
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
              <div class="flex justify-between border-b border-gray-200 pb-2">
                <span class="text-gray-600">Flute Code:</span>
                <span class="font-medium text-gray-900">{{ selectedItem.flute_code }}</span>
                </div>
              <div class="flex justify-between border-b border-gray-200 pb-2">
                <span class="text-gray-600">Flute Name:</span>
                <span class="font-medium text-gray-900">{{ selectedItem.flute_name }}</span>
                </div>
              <div class="flex justify-between border-b border-gray-200 pb-2">
                <span class="text-gray-600">Compute:</span>
                <span class="font-medium" :class="{'text-green-600': selectedItem.compute, 'text-red-600': !selectedItem.compute}">
                  {{ selectedItem.compute ? 'Yes' : 'No' }}
                </span>
                </div>
              <div class="flex justify-between border-b border-gray-200 pb-2">
                <span class="text-gray-600">Length Less (mm):</span>
                <span class="font-medium text-gray-900">{{ selectedItem.length_less }}</span>
                </div>
              <div class="flex justify-between">
                <span class="text-gray-600">Length Add (mm):</span>
                <span class="font-medium text-gray-900">{{ selectedItem.length_add }}</span>
              </div>
            </div>
            <div v-else class="flex flex-col items-center justify-center h-40 text-center">
              <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mb-4">
                <i class="fas fa-cut text-blue-500 text-2xl"></i>
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
const sortOrder = ref({ field: 'flute_code', direction: 'asc' });
const toast = useToast();
    const currentPage = ref(1);
    const itemsPerPage = ref(10);
const savingStatus = ref({});

// --- Computed Properties ---
const filteredItems = computed(() => {
  let result = [...items.value];
  if (searchQuery.value) {
    const query = searchQuery.value.toLowerCase();
    result = result.filter(item =>
      (item.flute_code && item.flute_code.toLowerCase().includes(query)) ||
      (item.flute_name && item.flute_name.toLowerCase().includes(query))
    );
  }

  return result.sort((a, b) => {
    const field = sortOrder.value.field;
    const direction = sortOrder.value.direction === 'asc' ? 1 : -1;
    let valA = a[field];
    let valB = b[field];

    // Handle sorting for boolean 'compute' field
    if (field === 'compute') {
        valA = valA ? 1 : 0;
        valB = valB ? 1 : 0;
    }

    if (valA < valB) return -1 * direction;
    if (valA > valB) return 1 * direction;
    
    // Secondary sort by compute status if primary sort is equal
    if (a.flute_code === b.flute_code) {
        return (a.compute === b.compute) ? 0 : a.compute ? -1 : 1;
    }

    return 0;
  });
});

const totalPages = computed(() => Math.ceil(filteredItems.value.length / itemsPerPage.value));

const paginatedItems = computed(() => {
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
    let response = await axios.get('/api/side-trims-by-flute');
    
    // If no data, seed and fetch again.
    if (response.data?.status !== 'success' || response.data.data.length === 0) {
        try {
            toast.info('No side trim data found. Seeding initial data...');
            await axios.post('/api/side-trims-by-flute/seed');
            toast.success('Initial data seeded. Reloading...');
            response = await axios.get('/api/side-trims-by-flute');
          } catch (seedError) {
            console.error('Error seeding data:', seedError);
            toast.error('Failed to seed initial side trim data.');
            items.value = [];
            loading.value = false;
            return;
        }
    }

    if (response.data?.status === 'success') {
        items.value = response.data.data.map(trim => ({
            id: trim.id,
            flute_id: trim.flute_id,
            flute_code: trim.paper_flute?.code || 'N/A',
            flute_name: trim.paper_flute?.name || 'N/A',
            compute: trim.compute === 1 || trim.compute === true,
            length_less: trim.length_less,
            length_add: trim.length_add,
        }));
        } else {
        items.value = [];
        toast.error('Failed to parse side trim data from server.');
    }

  } catch (error) {
    console.error('Error loading data:', error);
    toast.error(error.response?.data?.message || 'Failed to load side trim data.');
      } finally {
        loading.value = false;
      }
    };

const refreshData = () => {
  selectedItem.value = null;
  searchQuery.value = '';
  loadData();
};

const selectItem = (item) => {
  selectedItem.value = item;
};

// --- API Methods ---
const updateTrim = async (item) => {
  const statusKey = `${item.id}`;
  if (savingStatus.value[statusKey]) return;
  savingStatus.value[statusKey] = true;

  const payload = {
    length_less: item.length_less === '' || item.length_less === null ? 0 : Number(item.length_less),
    length_add: item.length_add === '' || item.length_add === null ? 0 : Number(item.length_add),
  };

  try {
    const response = await axios.put(`/api/side-trims-by-flute/${item.id}`, payload);
        
        if (response.data.status === 'success') {
      toast.success(`${item.flute_code} (${item.compute ? 'Yes' : 'No'}) trim updated.`);
      
      // Update local data with server response to ensure consistency
      const index = items.value.findIndex(i => i.id === item.id);
      if (index !== -1) {
        const updatedItem = {
          ...items.value[index],
          ...response.data.data,
          compute: response.data.data.compute === 1 || response.data.data.compute === true,
          flute_code: response.data.data.paper_flute?.code || items.value[index].flute_code,
          flute_name: response.data.data.paper_flute?.name || items.value[index].flute_name
        };
        items.value[index] = updatedItem;

        if (selectedItem.value && selectedItem.value.id === item.id) {
          selectItem(updatedItem);
        }
      }
        } else {
      toast.error(response.data.message || `Failed to update trim.`);
      loadData(); // Revert changes on failure
        }
      } catch (error) {
    console.error(`Error updating trim:`, error);
    toast.error(error.response?.data?.message || `Failed to update trim.`);
    loadData(); // Revert changes on failure
      } finally {
    savingStatus.value[statusKey] = false;
  }
};

const toggleCompute = async (itemToToggle) => {
  const originalValue = itemToToggle.compute;
  // Optimistically update the UI
  itemToToggle.compute = !originalValue;

  // If toggling to 'Yes', set the other record for the same flute to 'No'
  if (itemToToggle.compute) {
    const otherItem = items.value.find(i => i.flute_id === itemToToggle.flute_id && i.id !== itemToToggle.id);
    if (otherItem) {
      otherItem.compute = false;
    }
  }

  try {
    const response = await axios.put(`/api/side-trims-by-flute/${itemToToggle.id}`, {
      compute: itemToToggle.compute
    });
        
        if (response.data.status === 'success') {
      // Update the toggled item with data from the server
      const updatedItemFromServer = response.data.data;
      const index = items.value.findIndex(i => i.id === updatedItemFromServer.id);
      if (index !== -1) {
        items.value[index] = {
          ...items.value[index],
          ...updatedItemFromServer,
          flute_code: items.value[index].flute_code, // Preserve properties not returned by API
          flute_name: items.value[index].flute_name
        };
      }
      
      // The controller now handles updating the other record, so we just need to refresh our data
      // to reflect the true state from the database.
          await loadData();
          
      toast.success(`'${itemToToggle.flute_code}' compute status updated.`);
      
      // Update the selected item if it was the one toggled
      if (selectedItem.value && selectedItem.value.id === itemToToggle.id) {
        selectItem(items.value.find(i => i.id === itemToToggle.id));
      }
        } else {
      // Revert on failure
      itemToToggle.compute = originalValue;
      toast.error(response.data.message || 'Failed to update compute status.');
        }
      } catch (error) {
    // Revert on error
    itemToToggle.compute = originalValue;
     // Also revert the other item if it was changed optimistically
    if (itemToToggle.compute) {
        const otherItem = items.value.find(i => i.flute_id === itemToToggle.flute_id && i.id !== itemToToggle.id);
        if (otherItem) {
            otherItem.compute = true;
        }
    }

    const errorMessage = error.response?.data?.message || 'An error occurred.';
    console.error('Error toggling compute status:', error.response?.data || error);
    toast.error(`Error: ${errorMessage}`);
  }
};

// --- UI Methods ---
const sortBy = (field) => {
  if (sortOrder.value.field === field) {
    sortOrder.value.direction = sortOrder.value.direction === 'asc' ? 'desc' : 'asc';
  } else {
    sortOrder.value.field = field;
    sortOrder.value.direction = 'asc';
  }
};

const printData = () => {
  const printContents = document.getElementById('sideTrimsTable').outerHTML;
  const originalContents = document.body.innerHTML;
  const styles = Array.from(document.styleSheets)
    .map(styleSheet => {
        try {
            return Array.from(styleSheet.cssRules)
                .map(rule => rule.cssText)
                .join('');
        } catch (e) {
            console.log('Access to stylesheet %s is denied. Skipping.', styleSheet.href);
            return '';
        }
    })
    .join('');
  
  const printWindow = window.open('', '', 'height=600,width=800');
  printWindow.document.write('<html><head><title>Print Side Trims</title>');
  printWindow.document.write(`<style>${styles}</style>`);
  printWindow.document.write('</head><body>');
  printWindow.document.write('<h1>Side Trim By Flute</h1>');
  printWindow.document.write(printContents);
  printWindow.document.write('</body></html>');
  printWindow.document.close();
  printWindow.focus();
  setTimeout(() => { printWindow.print(); }, 500);
};

// --- Lifecycle ---
onMounted(loadData);
</script>

<style scoped>
/* Button styles */
.btn-secondary {
  @apply bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg shadow transition
    flex items-center justify-center whitespace-nowrap;
}
.btn-info {
  @apply bg-cyan-600 hover:bg-cyan-700 text-white px-4 py-2 rounded-lg shadow transition
    flex items-center justify-center whitespace-nowrap;
}

/* Table hover animation */
tbody tr {
  transition: all 0.2s;
}
tbody tr:hover {
  transform: translateY(-2px);
  box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

/* Scrollbar styling */
.overflow-x-auto {
  scrollbar-width: thin;
  scrollbar-color: rgba(156, 163, 175, 0.5) transparent;
}
.overflow-x-auto::-webkit-scrollbar {
  height: 8px;
}
.overflow-x-auto::-webkit-scrollbar-track {
  background: transparent;
}
.overflow-x-auto::-webkit-scrollbar-thumb {
  background-color: rgba(156, 163, 175, 0.5);
  border-radius: 20px;
}
</style>
