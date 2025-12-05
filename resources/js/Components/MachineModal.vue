<template>
  <div v-if="show" class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto bg-black bg-opacity-50 px-4 sm:px-0">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-4xl max-h-[90vh] flex flex-col">
      <!-- Modal Header -->
      <div class="flex items-center justify-between p-4 border-b border-gray-200 bg-gradient-to-r from-green-600 to-green-700 text-white rounded-t-lg">
        <div class="flex items-center">
          <div class="p-2 bg-white bg-opacity-30 rounded-lg mr-3">
            <i class="fas fa-cogs"></i>
          </div>
          <h3 class="text-xl font-semibold">Machine Table</h3>
        </div>
        <button @click="closeModal" class="text-white hover:text-gray-200 focus:outline-none transform active:translate-y-px">
          <i class="fas fa-times text-xl"></i>
        </button>
      </div>
      <!-- Modal Content -->
      <div class="p-5 flex-1 flex flex-col min-h-0">
        <div class="mb-4">
          <div class="relative">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
              <i class="fas fa-search"></i>
            </span>
            <input type="text" v-model="searchTerm" placeholder="Search machines..."
              class="pl-10 pr-4 py-2 w-full border border-gray-300 rounded-lg focus:ring-emerald-500 focus:border-emerald-500 bg-gray-50">
          </div>
        </div>
        <div class="overflow-x-auto rounded-lg border border-gray-200 max-h-96 flex-1 min-h-0">
          <table class="w-full divide-y divide-gray-200 table-auto min-w-max">
            <thead class="bg-gray-50 sticky top-0">
              <tr>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer" @click="sortTable('machine_code')">Code</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer" @click="sortTable('machine_name')">Name</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer" @click="sortTable('process')">Process</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer" @click="sortTable('sub_process')">Sub-Process</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer" @click="sortTable('resource_type')">Resource Type</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer" @click="sortTable('finisher_type')">Finisher Type</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200 text-xs">
              <tr v-for="machine in filteredMachines" :key="machine.machine_code || machine.id"
                :class="['hover:bg-emerald-50 cursor-pointer', selectedMachine && selectedMachine.machine_code === machine.machine_code ? 'bg-emerald-100 border-l-4 border-emerald-500' : '']"
                @click="selectMachine(machine)"
                @dblclick="selectAndClose(machine)">
                <td class="px-4 py-3 whitespace-nowrap font-medium text-gray-900">{{ machine.machine_code }}</td>
                <td class="px-4 py-3 whitespace-nowrap text-gray-700">{{ machine.machine_name }}</td>
                <td class="px-4 py-3 whitespace-nowrap text-gray-700">{{ machine.process || '-' }}</td>
                <td class="px-4 py-3 whitespace-nowrap text-gray-700">{{ machine.sub_process || '-' }}</td>
                <td class="px-4 py-3 whitespace-nowrap text-gray-700">{{ machine.resource_type || '-' }}</td>
                <td class="px-4 py-3 whitespace-nowrap text-gray-700">{{ machine.finisher_type || '-' }}</td>
                <td class="px-4 py-3 whitespace-nowrap text-gray-700">
                  <span v-if="(machine.status || 'Act') === 'Act'" class="px-2 py-1 inline-flex text-[10px] leading-4 font-semibold rounded-full bg-green-100 text-green-800">
                    Active
                  </span>
                  <span v-else class="px-2 py-1 inline-flex text-[10px] leading-4 font-semibold rounded-full bg-red-100 text-red-800">
                    Obsolete
                  </span>
                </td>
              </tr>
              <tr v-if="filteredMachines.length === 0">
                <td colspan="7" class="px-6 py-4 text-center text-gray-500">No machine data available.</td>
              </tr>
            </tbody>
          </table>
        </div>
        <div class="mt-2 text-xs text-gray-500 italic">
          <p>Click on a row to select and edit its details.</p>
        </div>
        <div class="mt-4 grid grid-cols-4 md:grid-cols-6 lg:grid-cols-8 gap-2">
          <button type="button" @click="sortTable('machine_code')" class="py-2 px-3 bg-gray-100 border border-gray-400 hover:bg-gray-200 text-xs rounded-lg transform active:translate-y-px">
            <i class="fas fa-sort mr-1"></i>By Code
          </button>
          <button type="button" @click="sortTable('machine_name')" class="py-2 px-3 bg-gray-100 border border-gray-400 hover:bg-gray-200 text-xs rounded-lg transform active:translate-y-px">
            <i class="fas fa-sort mr-1"></i>By Name
          </button>
          <button type="button" @click="sortTable('process')" class="py-2 px-3 bg-gray-100 border border-gray-400 hover:bg-gray-200 text-xs rounded-lg transform active:translate-y-px">
            <i class="fas fa-sort mr-1"></i>By Process
          </button>
          <button type="button" @click="sortTable('sub_process')" class="py-2 px-3 bg-gray-100 border border-gray-400 hover:bg-gray-200 text-xs rounded-lg transform active:translate-y-px">
            <i class="fas fa-sort mr-1"></i>By Sub-Process
          </button>
          <button type="button" @click="sortTable('resource_type')" class="py-2 px-3 bg-gray-100 border border-gray-400 hover:bg-gray-200 text-xs rounded-lg transform active:translate-y-px">
            <i class="fas fa-sort mr-1"></i>By Resource
          </button>
          <button type="button" @click="sortTable('finisher_type')" class="py-2 px-3 bg-gray-100 border border-gray-400 hover:bg-gray-200 text-xs rounded-lg transform active:translate-y-px">
            <i class="fas fa-sort mr-1"></i>By Finisher
          </button>
          <button type="button" @click="selectAndClose(selectedMachine)" :disabled="!selectedMachine" class="py-2 px-3 bg-gradient-to-r from-emerald-500 to-green-600 hover:from-emerald-600 hover:to-green-700 text-white text-xs rounded-lg transform active:translate-y-px disabled:opacity-50 disabled:cursor-not-allowed">
            <i class="fas fa-edit mr-1"></i>Select
          </button>
          <button type="button" @click="closeModal" class="py-2 px-3 bg-gray-300 hover:bg-gray-400 text-gray-800 text-xs rounded-lg transform active:translate-y-px">
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
  show: {
    type: Boolean,
    default: false,
  },
  machines: {
    type: Array,
    default: () => []
  }
});

const emit = defineEmits(['close', 'select', 'refresh']);

const searchTerm = ref('');
const selectedMachine = ref(null);
const sortKey = ref('machine_code');
const sortAsc = ref(true);


// Compute filtered machines based on search query
const filteredMachines = computed(() => {
  let machines = props.machines || [];
  if (searchTerm.value) {
    const q = searchTerm.value.toLowerCase();
    machines = machines.filter(machine =>
      machine.machine_code.toLowerCase().includes(q) ||
      machine.machine_name.toLowerCase().includes(q) ||
      (machine.process && machine.process.toLowerCase().includes(q)) ||
      (machine.sub_process && machine.sub_process.toLowerCase().includes(q)) ||
      (machine.resource_type && machine.resource_type.toLowerCase().includes(q)) ||
      (machine.finisher_type && machine.finisher_type.toLowerCase().includes(q))
    );
  }
  
  // Apply sorting
  return [...machines].sort((a, b) => {
    if (sortKey.value === 'machine_code') {
      if (a.machine_code < b.machine_code) return sortAsc.value ? -1 : 1;
      if (a.machine_code > b.machine_code) return sortAsc.value ? 1 : -1;
      return 0;
    }
    
    if (a[sortKey.value] < b[sortKey.value]) return sortAsc.value ? -1 : 1;
    if (a[sortKey.value] > b[sortKey.value]) return sortAsc.value ? 1 : -1;
    return 0;
  });
});

// Select a row
function selectMachine(machine) {
  selectedMachine.value = machine;
}

// Select and close modal
function selectAndClose(machine) {
  if (machine) {
    emit('select', machine);
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

const closeModal = () => {
  searchTerm.value = '';
  selectedMachine.value = null;
  emit('close');
};

// Reset selection when modal is opened
watch(() => props.show, (val) => {
  if (val) {
    selectedMachine.value = null;
    searchTerm.value = '';
    
    // Emit event to parent component to refresh machines data
    emit('refresh');
  }
});
</script>

<style scoped>
/* Add any specific styling for the modal here */
</style> 