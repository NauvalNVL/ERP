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
          class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400"
          @click="reopenOptionsModal"
        >
          More Options
        </button>
        <button
          type="button"
          class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400"
          @click="zoomSelectedMc"
          :disabled="!selectedRowMc"
        >
          Zoom
        </button>
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

const props = defineProps({
  show: Boolean,
  targetField: String, // 'mcsFrom' or 'mcsTo'
  initialSortColumn: { type: String, default: 'mc_seq' },
  initialSortDirection: { type: String, default: 'asc' },
  initialFilterStatus: { 
    type: Object, 
    default: () => ({ active: true, obsolete: false, pending: false })
  },
});

const emit = defineEmits(['close', 'select-mc', 'reopen-options', 'zoom-mc']);

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

// Mock API call to fetch master cards
const fetchMasterCards = async () => {
  try {
    masterCards.value = [
      { mc_seq: '1609138', mc_model: 'BOX BASO 4,5 KG', part: 'BOX', comp: 'Main', p_design: 'B1', status: 'Active', ed: 'ED-01', id: 'ID-01', ext_dim_1: '396', ext_dim_2: '243', ext_dim_3: '297', int_dim_1: '393', int_dim_2: '240', int_dim_3: '292' },
      { mc_seq: '1609144', mc_model: 'BOX IKAN HARIMAU 4.5 KG', part: 'BOX', comp: 'Main', p_design: 'B1', status: 'Active', ed: 'ED-02', id: 'ID-02', ext_dim_1: '396', ext_dim_2: '243', ext_dim_3: '297', int_dim_1: '393', int_dim_2: '240', int_dim_3: '292' },
      { mc_seq: '1609145', mc_model: 'BOX SRIKAYA 4.5 KG', part: 'BOX', comp: 'Main', p_design: 'B1', status: 'Active', ed: 'ED-03', id: 'ID-03', ext_dim_1: '396', ext_dim_2: '243', ext_dim_3: '297', int_dim_1: '393', int_dim_2: '240', int_dim_3: '292' },
      { mc_seq: '1609162', mc_model: 'BIHUN FANIA 5 KG', part: 'BOX', comp: 'Main', p_design: 'B1', status: 'Obsolete', ed: 'ED-04', id: 'ID-04', ext_dim_1: '396', ext_dim_2: '243', ext_dim_3: '297', int_dim_1: '393', int_dim_2: '240', int_dim_3: '292' },
      { mc_seq: '1609163', mc_model: 'BIHUN IKAN TUNA 4.5 KG BARU', part: 'BOX', comp: 'Main', p_design: 'B1', status: 'Active', ed: 'ED-05', id: 'ID-05', ext_dim_1: '396', ext_dim_2: '243', ext_dim_3: '297', int_dim_1: '393', int_dim_2: '240', int_dim_3: '292' },
      { mc_seq: '1609164', mc_model: 'BIHUN IKAN TUNA 5 KG BARU', part: 'BOX', comp: 'Main', p_design: 'B1', status: 'Pending', ed: 'ED-06', id: 'ID-06', ext_dim_1: '396', ext_dim_2: '243', ext_dim_3: '297', int_dim_1: '393', int_dim_2: '240', int_dim_3: '292' },
      { mc_seq: '1609166', mc_model: 'BIHUN PIRING MAS 5 KG', part: 'BOX', comp: 'Main', p_design: 'B1', status: 'Active', ed: 'ED-07', id: 'ID-07', ext_dim_1: '396', ext_dim_2: '243', ext_dim_3: '297', int_dim_1: '393', int_dim_2: '240', int_dim_3: '292' },
      { mc_seq: '1609173', mc_model: 'BOX JAGUNG SRIKAYA 5 KG', part: 'BOX', comp: 'Main', p_design: 'B1', status: 'Obsolete', ed: 'ED-08', id: 'ID-08', ext_dim_1: '396', ext_dim_2: '243', ext_dim_3: '297', int_dim_1: '393', int_dim_2: '240', int_dim_3: '292' },
      { mc_seq: '1609181', mc_model: 'BIHUN POHON KOPI 5 KG', part: 'BOX', comp: 'Main', p_design: 'B1', status: 'Active', ed: 'ED-09', id: 'ID-09', ext_dim_1: '396', ext_dim_2: '243', ext_dim_3: '297', int_dim_1: '393', int_dim_2: '240', int_dim_3: '292' },
      { mc_seq: '1609182', mc_model: 'BIHUN DELLIS 5 KG', part: 'BOX', comp: 'Main', p_design: 'B1', status: 'Pending', ed: 'ED-10', id: 'ID-10', ext_dim_1: '396', ext_dim_2: '243', ext_dim_3: '297', int_dim_1: '393', int_dim_2: '240', int_dim_3: '292' },
    ];
  } catch (error) {
    console.error('Error fetching master cards:', error);
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

const reopenOptionsModal = () => {
  emit('reopen-options');
  closeModal();
};

const zoomSelectedMc = () => {
  if (selectedRowMc.value) {
    emit('zoom-mc', selectedRowMc.value);
  }
};

const closeModal = () => {
  emit('close');
};
</script>

<style scoped>
/* Add any specific styles for this modal here */
</style> 