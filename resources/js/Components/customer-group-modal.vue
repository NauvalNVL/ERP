<template>
  <div v-if="show" class="fixed inset-0 z-100 flex items-center justify-center overflow-y-auto">
    <!-- Background overlay -->
    <div class="fixed inset-0 bg-black bg-opacity-50" @click="$emit('close')"></div>
    
    <!-- Modal content -->
    <div class="bg-white rounded-lg shadow-lg w-full max-w-4xl z-110 relative">
      <!-- Modal Header -->
      <div class="flex items-center justify-between p-4 border-b border-gray-200 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-t-lg">
        <div class="flex items-center">
          <div class="p-2 bg-white bg-opacity-30 rounded-lg mr-3">
            <i class="fas fa-users"></i>
          </div>
          <h3 class="text-xl font-semibold">Customer Group Table</h3>
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
            <input type="text" v-model="searchQuery" placeholder="Search customer groups..."
              class="pl-10 pr-4 py-2 w-full border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 bg-gray-50">
        </div>
        </div>
        <div class="overflow-x-auto rounded-lg border border-gray-200 max-h-96">
          <table class="w-full divide-y divide-gray-200 table-fixed" id="customerGroupDataTable">
            <thead class="bg-gray-50 sticky top-0">
            <tr>
                <th @click="sortTable('group_code')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/3 cursor-pointer">
                  Group Code <i class="fas fa-sort ml-1"></i>
                </th>
                <th @click="sortTable('description')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-2/3 cursor-pointer">
                  Description <i class="fas fa-sort ml-1"></i>
                </th>
            </tr>
          </thead>
            <tbody class="bg-white divide-y divide-gray-200 text-xs">
              <tr v-for="group in filteredGroups" :key="group.group_code"
                :class="['hover:bg-blue-50 cursor-pointer', selectedGroup && selectedGroup.group_code === group.group_code ? 'bg-blue-100 border-l-4 border-blue-500' : '']"
                @click="selectRow(group)"
                @dblclick="selectAndClose(group)">
                <td class="px-6 py-3 whitespace-nowrap font-medium text-gray-900">{{ group.group_code }}</td>
                <td class="px-6 py-3 whitespace-nowrap text-gray-700">{{ group.description || '-' }}</td>
              </tr>
              <tr v-if="loading">
                <td colspan="2" class="px-6 py-4 text-center">
                  <div class="flex items-center justify-center">
                    <div class="animate-spin rounded-full h-5 w-5 border-b-2 border-blue-500"></div>
                    <span class="ml-2 text-gray-500">Loading data...</span>
                  </div>
                </td>
              </tr>
              <tr v-else-if="error">
                <td colspan="2" class="px-6 py-4 text-center">
                  <div class="text-red-600">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    {{ error }}
                  </div>
                </td>
              </tr>
              <tr v-else-if="filteredGroups.length === 0">
                <td colspan="2" class="px-6 py-4 text-center text-gray-500">
                  No customer group data available.
                  <div class="mt-2 text-xs">
                    Total groups loaded: {{ customerGroups.length }}
                  </div>
                </td>
            </tr>
          </tbody>
        </table>
      </div>
        <div class="mt-2 text-xs text-gray-500 italic">
          <p>Click on a row to select, double-click to select and close the modal.</p>
        </div>
        <div class="mt-4 grid grid-cols-3 gap-2">
          <button type="button" @click="sortTable('group_code')" class="py-2 px-3 bg-gray-100 border border-gray-400 hover:bg-gray-200 text-xs rounded-lg transform active:translate-y-px">
            <i class="fas fa-sort mr-1"></i>Sort by Code
          </button>
          <button type="button" @click="selectAndClose(selectedGroup)" class="py-2 px-3 bg-blue-500 hover:bg-blue-600 text-white text-xs rounded-lg transform active:translate-y-px">
            <i class="fas fa-edit mr-1"></i>Select Group
          </button>
          <button type="button" @click="$emit('close')" class="py-2 px-3 bg-gray-300 hover:bg-gray-400 text-gray-800 text-xs rounded-lg transform active:translate-y-px">
            <i class="fas fa-times mr-1"></i>Close
        </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, watch, onMounted } from 'vue';
import axios from 'axios';

const props = defineProps({
  show: Boolean
});

const emit = defineEmits(['close', 'select']);

const customerGroups = ref([]);
const searchQuery = ref('');
const selectedGroup = ref(null);
const sortKey = ref('group_code');
const sortAsc = ref(true);
const loading = ref(false);
const error = ref(null);

const isActiveGroup = (group) => {
  const status = (group?.status ?? group?.STATUS);
  if (status !== undefined && status !== null && String(status).trim() !== '') {
    const s = String(status).trim().toLowerCase();
    if (s === 'act' || s === 'active' || s === 'a' || s === 'y' || s === '1' || s === 'true') return true;
    if (s === 'obs' || s === 'obsolete' || s === 'inactive' || s === 'i' || s === 'n' || s === '0' || s === 'false') return false;
  }

  const active = (group?.active ?? group?.ACTIVE);
  if (active !== undefined && active !== null && String(active).trim() !== '') {
    const a = String(active).trim().toLowerCase();
    if (a === 'y' || a === 'a' || a === '1' || a === 'true') return true;
    if (a === 'n' || a === 'i' || a === '0' || a === 'false') return false;
  }

  if (group?.is_active !== undefined && group?.is_active !== null) {
    if (group.is_active === true || group.is_active === 1 || group.is_active === '1') return true;
    if (group.is_active === false || group.is_active === 0 || group.is_active === '0') return false;
  }

  const ac = (group?.AC ?? group?.ac);
  if (ac !== undefined && ac !== null && String(ac).trim() !== '') {
    const v = String(ac).trim().toUpperCase();
    if (v === 'Y' || v === 'A') return true;
    if (v === 'N' || v === 'I') return false;
  }

  // Default: treat as active when no status flag is present
  return true;
};

// Fetch customer groups when the component is mounted or when show changes
const fetchCustomerGroups = async () => {
  loading.value = true;
  error.value = null;
  try {
    console.log('Fetching customer groups from API...');
    const response = await axios.get('/api/customer-groups');
    console.log('API Response:', response.data);
    console.log('Total groups fetched:', response.data.length);
    
    // Log first item to see structure
    if (response.data.length > 0) {
      console.log('First group structure:', response.data[0]);
      console.log('Has group_code?', response.data[0].group_code);
      console.log('Has description?', response.data[0].description);
      console.log('Has Group_ID?', response.data[0].Group_ID);
      console.log('Has Group_Name?', response.data[0].Group_Name);
    }

    customerGroups.value = (response.data || []).filter(isActiveGroup);
  } catch (err) {
    console.error('Error fetching customer groups:', err);
    console.error('Error details:', err.response?.data);
    error.value = 'Failed to load customer groups. Please try again.';
  } finally {
    loading.value = false;
  }
};

// Compute filtered groups based on search query
const filteredGroups = computed(() => {
  let groups = customerGroups.value || [];

  // Hide obsolete groups from the lookup modal
  groups = groups.filter(isActiveGroup);
  
  if (searchQuery.value) {
    const query = searchQuery.value.toLowerCase();
    groups = groups.filter(group =>
      (group.group_code && group.group_code.toLowerCase().includes(query)) ||
      (group.description && group.description.toLowerCase().includes(query))
    );
  }
  
  // Apply sorting
  return [...groups].sort((a, b) => {
    let valueA = a[sortKey.value] || '';
    let valueB = b[sortKey.value] || '';
    
    if (typeof valueA === 'string') valueA = valueA.toLowerCase();
    if (typeof valueB === 'string') valueB = valueB.toLowerCase();
    
    if (valueA < valueB) return sortAsc.value ? -1 : 1;
    if (valueA > valueB) return sortAsc.value ? 1 : -1;
    return 0;
  });
});

// Select a row
function selectRow(group) {
  selectedGroup.value = group;
}

// Select and close modal
function selectAndClose(group) {
  if (group) {
    emit('select', group);
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

// Reset selection when modal is opened and fetch data
watch(() => props.show, (val) => {
  if (val) {
    selectedGroup.value = null;
    searchQuery.value = '';
    fetchCustomerGroups();
      }
});

    onMounted(() => {
      if (props.show) {
    fetchCustomerGroups();
      }
});
</script> 

<style scoped>
/* Add custom z-index classes */
.z-100 {
  z-index: 100;
}

.z-110 {
  z-index: 110;
}
</style> 