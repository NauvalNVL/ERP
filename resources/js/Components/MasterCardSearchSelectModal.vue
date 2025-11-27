<template>
  <div v-if="show" class="fixed inset-0 z-50 bg-black bg-opacity-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-4xl mx-auto max-h-[90vh] flex flex-col">
      <!-- Modal Header -->
      <div class="flex items-center justify-between p-4 border-b border-gray-200 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-t-lg">
        <h3 class="text-xl font-semibold flex items-center text-white">
          <i class="fas fa-search mr-3"></i>
          Search & Select Master Card
        </h3>
        <button type="button" @click="closeModal" class="text-white hover:text-gray-200 focus:outline-none">
          <i class="fas fa-times text-xl"></i>
        </button>
      </div>

      <!-- Modal Body -->
      <div class="p-6 flex-grow overflow-hidden flex flex-col">
        <!-- Search and Filter Section -->
        <div class="mb-4 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
          <div>
            <label for="modal-mcs-search" class="block text-sm font-medium text-gray-700 mb-1">Search MCS:</label>
            <input
              type="text"
              id="modal-mcs-search"
              v-model="searchTerm"
              placeholder="Search by Seq, Model, Part, etc."
              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
            />
          </div>
          <div class="md:col-span-1 lg:col-span-2">
            <label class="block text-sm font-medium text-gray-700 mb-1">Status:</label>
            <div class="flex flex-wrap gap-4">
              <label class="inline-flex items-center">
                <input
                  type="checkbox"
                  v-model="filterStatus.active"
                  class="form-checkbox h-5 w-5 text-blue-600"
                />
                <span class="ml-2 text-gray-700">Active</span>
              </label>
              <label class="inline-flex items-center">
                <input
                  type="checkbox"
                  v-model="filterStatus.obsolete"
                  class="form-checkbox h-5 w-5 text-blue-600"
                />
                <span class="ml-2 text-gray-700">Obsolete</span>
              </label>
              <label class="inline-flex items-center">
                <input
                  type="checkbox"
                  v-model="filterStatus.pending"
                  class="form-checkbox h-5 w-5 text-blue-600"
                />
                <span class="ml-2 text-gray-700">Pending</span>
              </label>
            </div>
          </div>
        </div>

        <!-- Master Card Table -->
        <div class="flex-grow overflow-auto border border-gray-200 rounded-md shadow-sm">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50 sticky top-0">
              <tr>
                <th @click="sort('mc_seq')" scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                  MC Seq
                  <span v-if="sortColumn === 'mc_seq'">{{ sortDirection === 'asc' ? ' ▲' : ' ▼' }}</span>
                </th>
                <th @click="sort('mc_model')" scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                  MC Model
                  <span v-if="sortColumn === 'mc_model'">{{ sortDirection === 'asc' ? ' ▲' : ' ▼' }}</span>
                </th>
                <th @click="sort('part')" scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                  Part
                  <span v-if="sortColumn === 'part'">{{ sortDirection === 'asc' ? ' ▲' : ' ▼' }}</span>
                </th>
                <th @click="sort('p_design')" scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                  P.Design
                  <span v-if="sortColumn === 'p_design'">{{ sortDirection === 'asc' ? ' ▲' : ' ▼' }}</span>
                </th>
                <th @click="sort('status')" scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                  Status
                  <span v-if="sortColumn === 'status'">{{ sortDirection === 'asc' ? ' ▲' : ' ▼' }}</span>
                </th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-if="filteredMasterCards.length === 0">
                <td colspan="5" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                  No Master Cards found.
                </td>
              </tr>
              <tr
                v-for="mc in filteredMasterCards"
                :key="mc.mc_seq"
                @click="selectRow(mc)"
                :class="{ 'bg-blue-100 cursor-pointer': selectedRowMc && selectedRowMc.mc_seq === mc.mc_seq, 'hover:bg-gray-50 cursor-pointer': !selectedRowMc || selectedRowMc.mc_seq !== mc.mc_seq }"
              >
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ mc.mc_seq }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ mc.mc_model }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ mc.part }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ mc.p_design }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm">
                  <span
                    :class="{
                      'px-2 inline-flex text-xs leading-5 font-semibold rounded-full': true,
                      'bg-green-100 text-green-800': mc.status === 'Active',
                      'bg-red-100 text-red-800': mc.status === 'Obsolete',
                      'bg-yellow-100 text-yellow-800': mc.status === 'Pending',
                    }"
                  >
                    {{ mc.status }}
                  </span>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Modal Footer -->
      <div class="p-4 bg-gray-50 border-t border-gray-200 flex justify-start space-x-3 rounded-b-lg">
        <button
          type="button"
          class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700"
          @click="selectMasterCard"
          :disabled="!selectedRowMc"
        >
          Select
        </button>
        <button
          type="button"
          class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700"
          @click="closeModal"
        >
          Exit
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, watch } from 'vue';
import { usePage } from '@inertiajs/vue3';
import axios from 'axios';

const props = defineProps({
  show: Boolean,
  targetField: String, // 'mcsFrom' or 'mcsTo'
  initialSortColumn: { type: String, default: 'mc_seq' },
  initialSortDirection: { type: String, default: 'asc' },
  initialFilterStatus: {
    type: Object,
    default: () => ({ active: true, obsolete: false, pending: false })
  },
  customerCode: { type: String, default: '' },
});

const emit = defineEmits(['close', 'select-mc']);

const masterCards = ref([]);
const searchTerm = ref('');
const sortColumn = ref(props.initialSortColumn);
const sortDirection = ref(props.initialSortDirection);
const filterStatus = ref({ ...props.initialFilterStatus });
const selectedRowMc = ref(null); // New reactive state for selected row

// Watch for changes in initial props to update local state
watch(() => props.initialSortColumn, (newVal) => { sortColumn.value = newVal; });
watch(() => props.initialSortDirection, (newVal) => { sortDirection.value = newVal; });
watch(() => props.initialFilterStatus, (newVal) => { filterStatus.value = { ...newVal }; }, { deep: true });

// Fetch data when the modal is shown or initial options change
watch(() => props.show, (newVal) => {
  if (newVal) {
    fetchMasterCards();
    selectedRowMc.value = null; // Reset selected row when modal opens
  }
}, { immediate: true });

// Fetch master cards for the given customer from backend API
const fetchMasterCards = async () => {
  try {
    if (!props.customerCode) {
      masterCards.value = [];
      return;
    }

    const params = {
      customer_code: props.customerCode,
      query: searchTerm.value || '',
      sortBy: sortColumn.value || 'mc_seq',
      sortOrder: sortDirection.value || 'asc',
      per_page: 50,
      // Let backend default status filter handle Active; frontend checkboxes further filter
    };

    const response = await axios.get('/api/update-mc/master-cards', { params });
    const payload = response.data;
    const raw = Array.isArray(payload) ? payload : (payload.data || []);

    masterCards.value = raw.map((item) => ({
      mc_seq: item.mc_seq || item.seq || '',
      mc_model: item.mc_model || item.model || '',
      part: item.part || item.part_no || '',
      comp: item.comp || item.comp_no || 'Main',
      p_design: item.p_design || item.pd || '',
      status: item.status === 'Act' ? 'Active' : (item.status || 'Active'),
      ext_dim_1: item.ext_dim_1,
      ext_dim_2: item.ext_dim_2,
      ext_dim_3: item.ext_dim_3,
      int_dim_1: item.int_dim_1,
      int_dim_2: item.int_dim_2,
      int_dim_3: item.int_dim_3,
    }));
  } catch (error) {
    console.error('Error fetching master cards:', error);
    masterCards.value = [];
  }
};

const filteredMasterCards = computed(() => {
  let filtered = masterCards.value.filter(mc => {
    const matchesSearch =
      mc.mc_seq.toLowerCase().includes(searchTerm.value.toLowerCase()) ||
      mc.mc_model.toLowerCase().includes(searchTerm.value.toLowerCase()) ||
      mc.part.toLowerCase().includes(searchTerm.value.toLowerCase()) ||
      (mc.comp && mc.comp.toLowerCase().includes(searchTerm.value.toLowerCase())) ||
      (mc.p_design && mc.p_design.toLowerCase().includes(searchTerm.value.toLowerCase()));

    const matchesStatus =
      (filterStatus.value.active && mc.status === 'Active') ||
      (filterStatus.value.obsolete && mc.status === 'Obsolete') ||
      (filterStatus.value.pending && mc.status === 'Pending');

    return matchesSearch && matchesStatus;
  });

  // Apply sorting
  return filtered.sort((a, b) => {
    const aValue = a[sortColumn.value];
    const bValue = b[sortColumn.value];

    if (aValue < bValue) return sortDirection.value === 'asc' ? -1 : 1;
    if (aValue > bValue) return sortDirection.value === 'asc' ? 1 : -1;
    return 0;
  });
});

const sort = (column) => {
  if (sortColumn.value === column) {
    sortDirection.value = sortDirection.value === 'asc' ? 'desc' : 'asc';
  }
  else {
    sortColumn.value = column;
    sortDirection.value = 'asc';
  }
};

const selectRow = (mc) => {
  selectedRowMc.value = mc;
};

const selectMasterCard = () => {
  if (selectedRowMc.value) {
    emit('select-mc', selectedRowMc.value);
    closeModal(); // Close modal after selection
  }
};

const closeModal = () => {
  emit('close');
};
</script>

<style scoped>
/* Add any specific styles for this modal here */
</style>
