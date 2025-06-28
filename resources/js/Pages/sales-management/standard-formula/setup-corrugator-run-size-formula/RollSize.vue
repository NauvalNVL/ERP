<template>
  <AppLayout :header="'Define Roll Size'">
    <Head title="Define Roll Size" />

    <!-- Header Section -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-800 p-6 rounded-t-lg shadow-md">
      <h2 class="text-2xl font-bold text-white mb-2 flex items-center">
        <i class="fas fa-ruler-combined mr-3"></i> Define Roll Size
      </h2>
      <p class="text-blue-100">Manage roll size specifications for each flute.</p>
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
                <input type="text" v-model="searchQuery" placeholder="Search by flute code..."
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
              <table class="min-w-full divide-y divide-gray-200" id="rollSizeTable">
                  <thead class="bg-gray-50">
                    <tr>
                    <th @click="sortBy('roll_length')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                      <div class="flex items-center">
                        Roll (mm) <i class="fas fa-sort ml-1"></i>
                      </div>
                      </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                      Roll (inches)
                      </th>
                    <th @click="sortBy('compute')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                      <div class="flex items-center">
                        Compute <i class="fas fa-sort ml-1"></i>
                      </div>
                      </th>
                    </tr>
                  </thead>
                  <tbody class="bg-white divide-y divide-gray-200">
                  <tr v-if="loading" class="animate-pulse">
                    <td colspan="3" class="px-6 py-4 text-center text-sm text-gray-500">
                      <div class="flex justify-center items-center space-x-2">
                        <i class="fas fa-spinner fa-spin"></i>
                        <span>Loading roll sizes...</span>
                        </div>
                      </td>
                    </tr>
                  <tr v-else-if="paginatedItems.length === 0" class="hover:bg-gray-50">
                    <td colspan="3" class="px-6 py-4 text-center text-sm text-gray-500">
                      No roll sizes found. Try adjusting your search or add a new one.
                    </td>
                  </tr>
                  <tr v-for="item in paginatedItems" :key="item.id" @click="selectItem(item)"
                    :class="{'bg-blue-50': selectedItem && selectedItem.id === item.id}"
                    class="hover:bg-gray-50 cursor-pointer">
                    <td class="px-2 py-1 whitespace-nowrap">
                      <input type="number"
                        :value="item.roll_length"
                        @input="handleInput(item, 'roll_length', $event)"
                        @blur="updateRollSize(item, 'roll_length')"
                        class="w-full px-2 py-1 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="mm">
                      </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                      {{ (item.roll_length / 25.4).toFixed(2) }}
                      </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 cursor-pointer hover:bg-blue-100 active:bg-blue-200 transition-all duration-150 transform hover:scale-105 border border-transparent hover:border-blue-300 rounded-md"
                      @click.stop="toggleCompute(item)"
                      title="Click to toggle Compute status"
                      :class="{'opacity-75': savingStatus[item.id]}">
                      <div class="flex items-center justify-between">
                        <span v-if="savingStatus[item.id]" class="text-blue-500 font-medium flex items-center">
                          <i class="fas fa-spinner fa-spin mr-1"></i> Updating...
                        </span>
                        <span v-else-if="item.compute" class="text-green-600 font-medium flex items-center">
                          <i class="fas fa-check-circle mr-1"></i> Yes
                        </span>
                        <span v-else class="text-red-600 font-medium flex items-center">
                          <i class="fas fa-times-circle mr-1"></i> No
                        </span>
                        <i v-if="!savingStatus[item.id]" class="fas fa-exchange-alt text-blue-500 ml-2"></i>
                          </div>
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
              <button :disabled="currentPage === 1" @click="currentPage--" class="px-2 py-1 border rounded hover:bg-gray-200 disabled:opacity-50">
                <i class="fas fa-chevron-left"></i>
                  </button>
              <span class="px-4">{{ currentPage }} / {{ totalPages }}</span>
              <button :disabled="currentPage === totalPages" @click="currentPage++" class="px-2 py-1 border rounded hover:bg-gray-200 disabled:opacity-50">
                <i class="fas fa-chevron-right"></i>
                  </button>
                </div>
          </div>
        </div>

        <!-- Side Panel -->
        <div class="w-full md:w-96 bg-gray-50 rounded-lg p-4 border border-gray-200 space-y-6">
          <!-- Details Section -->
          <div>
            <h3 class="text-lg font-semibold text-gray-800 mb-3 flex items-center">
              <i class="fas fa-info-circle mr-2 text-blue-500"></i> Details
            </h3>
            <div v-if="selectedItem" class="space-y-3">
              <div class="flex justify-between border-b border-gray-200 pb-2">
                <span class="text-gray-600">Flute:</span>
                <span class="font-medium text-gray-900">{{ selectedItem.flute_code }}</span>
              </div>
              <div class="flex justify-between border-b border-gray-200 pb-2">
                <span class="text-gray-600">Roll (mm):</span>
                <span class="font-medium text-gray-900">{{ selectedItem.roll_length }}</span>
              </div>
              <div class="flex justify-between border-b border-gray-200 pb-2">
                <span class="text-gray-600">Roll (inches):</span>
                <span class="font-medium text-gray-900">{{ (selectedItem.roll_length / 25.4).toFixed(2) }}</span>
              </div>
              <div class="flex justify-between border-b border-gray-200 pb-2">
                <span class="text-gray-600">Compute:</span>
                <span class="font-medium" :class="{'text-green-600': selectedItem.compute, 'text-red-600': !selectedItem.compute}">
                  {{ selectedItem.compute ? 'Yes' : 'No' }}
                </span>
              </div>
                <div class="pt-2">
                    <button @click="deleteRollSize(selectedItem)" class="btn-danger w-full">
                        <i class="fas fa-trash-alt mr-2"></i> Delete
                    </button>
                </div>
            </div>
            <div v-else class="flex flex-col items-center justify-center h-40 text-center">
              <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mb-4">
                <i class="fas fa-ruler-combined text-blue-500 text-2xl"></i>
              </div>
              <h3 class="text-lg font-medium text-gray-900 mb-1">No Item Selected</h3>
              <p class="text-gray-500 text-sm">Select an item from the table to view its details</p>
              </div>
            </div>

          <!-- Add New Roll Size Form -->
          <div>
            <h3 class="text-lg font-semibold text-gray-800 mb-3 flex items-center">
              <i class="fas fa-plus-circle mr-2 text-green-500"></i> Add New Roll Size
            </h3>
            <div class="space-y-4">
                <div>
                  <label for="new-flute" class="block text-sm font-medium text-gray-700">Flute</label>
                  <select id="new-flute" v-model="newRollSize.flute_id"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                    <option disabled value="">Select a flute</option>
                    <option v-for="flute in flutes" :key="flute.id" :value="flute.id">{{ flute.code }}</option>
                  </select>
                </div>
                <div>
                  <label for="new-roll-length" class="block text-sm font-medium text-gray-700">Roll Length (mm)</label>
                  <input type="number" id="new-roll-length" v-model.number="newRollSize.roll_length"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                    placeholder="e.g., 1200">
                </div>
                <div class="flex items-center justify-between">
                    <label class="flex items-center text-sm font-medium text-gray-700">
                        <input type="checkbox" v-model="newRollSize.compute"
                          class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        <span class="ml-2">Compute</span>
                    </label>
                  <button @click="addRollSize" :disabled="!canAddRollSize" class="btn-primary">
                    <i class="fas fa-plus mr-2"></i> Add
                  </button>
                </div>
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
import Swal from 'sweetalert2';

// --- State ---
const items = ref([]);
    const flutes = ref([]);
const selectedItem = ref(null);
const loading = ref(false);
    const searchQuery = ref('');
const sortOrder = ref({ field: 'flute_code', direction: 'asc' });
    const currentPage = ref(1);
    const itemsPerPage = ref(10);
const savingStatus = ref({});
const toast = useToast();
    const newRollSize = ref({
      flute_id: '',
      roll_length: '',
      compute: false,
});

// --- Computed Properties ---
const filteredItems = computed(() => {
  let result = items.value;
  if (searchQuery.value) {
    const query = searchQuery.value.toLowerCase();
    result = result.filter(item =>
      (item.flute_code && item.flute_code.toLowerCase().includes(query))
    );
  }

  return [...result].sort((a, b) => {
    const field = sortOrder.value.field;
    const direction = sortOrder.value.direction === 'asc' ? 1 : -1;
    const valA = a[field];
    const valB = b[field];

    if (valA < valB) return -1 * direction;
    if (valA > valB) return 1 * direction;

    // Secondary sort by roll_length if flutes are the same
    if (field === 'flute_code') {
        return a.roll_length - b.roll_length;
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

    const canAddRollSize = computed(() => {
  return newRollSize.value.flute_id && newRollSize.value.roll_length > 0;
});

// --- Watchers ---
watch(filteredItems, () => { currentPage.value = 1; });
watch(itemsPerPage, () => { currentPage.value = 1; });

// --- Methods ---
    const loadData = async () => {
  loading.value = true;
      try {
    const [sizesResponse, flutesResponse] = await Promise.all([
      axios.get('/api/roll-sizes'),
      axios.get('/api/paper-flutes'),
    ]);
        
        flutes.value = flutesResponse.data;
        
    if (sizesResponse.data && sizesResponse.data.status === 'success' && Array.isArray(sizesResponse.data.data)) {
      items.value = sizesResponse.data.data.map(size => ({
        ...size,
              compute: size.compute === 1 || size.compute === true
      }));
    }
      } catch (error) {
        console.error('Error loading data:', error);
    toast.error(error.response?.data?.message || 'Failed to load roll size data.');
    items.value = [];
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

const handleInput = (item, field, event) => {
  const originalItem = items.value.find(i => i.id === item.id);
  if (originalItem) {
    originalItem[field] = Number(event.target.value);
  }
};

const updateRollSize = async (item, field) => {
  const statusKey = `${item.id}-${field}`;
  if (savingStatus.value[statusKey]) return;

  savingStatus.value[statusKey] = true;

  const payload = {
      flute_id: item.flute_id,
      roll_length: item.roll_length,
      compute: item.compute,
  };

  try {
    const response = await axios.put(`/api/roll-sizes/${item.id}`, payload);
    toast.success(`Roll size for ${item.flute_code} updated.`);
    const index = items.value.findIndex(i => i.id === item.id);
    if (index !== -1) {
        items.value[index] = response.data.data;
    }
      } catch (error) {
    console.error(`Error updating ${field}:`, error);
    toast.error(error.response?.data?.message || `Failed to update ${field}.`);
    loadData(); // Revert changes on error
      } finally {
    savingStatus.value[statusKey] = false;
  }
};

const toggleCompute = async (item) => {
  if (savingStatus.value[item.id]) return;
  savingStatus.value[item.id] = true;

  const originalValue = item.compute;
  item.compute = !item.compute;

  const payload = {
      flute_id: item.flute_id,
      roll_length: item.roll_length,
      compute: item.compute,
  };

  try {
    await axios.put(`/api/roll-sizes/${item.id}`, payload);
    toast.success(`Compute for ${item.flute_code} (${item.roll_length}mm) updated.`);
      } catch (error) {
    item.compute = originalValue; // Revert on error
    console.error('Error toggling compute:', error);
    toast.error(error.response?.data?.message || 'Failed to update compute status.');
      } finally {
    savingStatus.value[item.id] = false;
      }
    };

    const addRollSize = async () => {
  if (!canAddRollSize.value) return;

  const exists = items.value.some(size => 
          size.flute_id === newRollSize.value.flute_id && 
    size.roll_length === newRollSize.value.roll_length
        );
        
        if (exists) {
    toast.error('This flute and roll length combination already exists.');
          return;
        }
        
  try {
    const response = await axios.post('/api/roll-sizes', newRollSize.value);
        if (response.data && response.data.status === 'success') {
            const newItem = response.data.data;
      toast.success('Roll size added successfully.');
            items.value.push(newItem);
      newRollSize.value = { flute_id: '', roll_length: '', compute: false };
        } else {
      toast.error('Failed to add roll size.');
        }
      } catch (error) {
        console.error('Error adding roll size:', error);
    toast.error(error.response?.data?.message || 'Failed to add roll size.');
  }
};

const deleteRollSize = async (itemToDelete) => {
    Swal.fire({
        title: 'Are you sure?',
        text: `You are about to delete the roll size ${itemToDelete.roll_length}mm for flute ${itemToDelete.flute_code}. You won't be able to revert this!`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!'
    }).then(async (result) => {
        if (result.isConfirmed) {
            try {
                await axios.delete(`/api/roll-sizes/${itemToDelete.id}`);
                toast.success('Roll size deleted successfully.');
                
                const index = items.value.findIndex(i => i.id === itemToDelete.id);
                if (index !== -1) {
                    items.value.splice(index, 1);
                }

                if (selectedItem.value && selectedItem.value.id === itemToDelete.id) {
                    selectedItem.value = null;
                }
            } catch (error) {
                console.error('Error deleting roll size:', error);
                toast.error(error.response?.data?.message || 'Failed to delete roll size.');
            }
        }
    });
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
    const printContents = document.getElementById('rollSizeTable').outerHTML;
    const originalContents = document.body.innerHTML;
    document.body.innerHTML = printContents;
      window.print();
    document.body.innerHTML = originalContents;
    location.reload();
};


// --- Lifecycle ---
onMounted(loadData);
</script>

<style scoped>
/* Button styles */
.btn-primary {
  @apply bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg shadow-md transition-all duration-200 ease-in-out disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center whitespace-nowrap;
}
.btn-secondary {
  @apply bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg shadow transition flex items-center justify-center whitespace-nowrap;
}
.btn-info {
  @apply bg-cyan-600 hover:bg-cyan-700 text-white px-4 py-2 rounded-lg shadow transition flex items-center justify-center whitespace-nowrap;
}
.btn-danger {
    @apply bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg shadow-md transition-all duration-200 ease-in-out disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center whitespace-nowrap;
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
