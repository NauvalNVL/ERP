<template>
  <div v-if="show" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-4xl">
      <!-- Modal Header -->
      <div class="flex items-center justify-between p-4 border-b border-gray-200 bg-gradient-to-r from-green-600 to-green-700 text-white rounded-t-lg">
        <div class="flex items-center">
          <div class="p-2 bg-white bg-opacity-30 rounded-lg mr-3">
            <i class="fas fa-users-cog"></i>
          </div>
          <h3 class="text-xl font-semibold">Sales Team Table</h3>
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
            <input type="text" v-model="searchQuery" placeholder="Search sales teams..."
              class="pl-10 pr-4 py-2 w-full border border-gray-300 rounded-lg focus:ring-emerald-500 focus:border-emerald-500 bg-gray-50">
          </div>
        </div>
        <div class="overflow-x-auto rounded-lg border border-gray-200 max-h-96">
          <table class="w-full divide-y divide-gray-200 table-fixed" id="salesTeamDataTable">
            <thead class="bg-gray-50 sticky top-0">
              <tr>
                <th @click="sortTable('code')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/3 cursor-pointer">
                  Code <i class="fas fa-sort ml-1"></i>
                </th>
                <th @click="sortTable('name')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-2/3 cursor-pointer">
                  Name <i class="fas fa-sort ml-1"></i>
                </th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200 text-xs">
              <tr v-for="team in filteredTeams" :key="team.id || team.code"
                :class="['hover:bg-emerald-50 cursor-pointer', selectedTeam && selectedTeam.code === team.code ? 'bg-emerald-100 border-l-4 border-emerald-500' : '']"
                @click="selectRow(team)"
                @dblclick="selectAndClose(team)">
                <td class="px-6 py-3 whitespace-nowrap font-medium text-gray-900">{{ team.code }}</td>
                <td class="px-6 py-3 whitespace-nowrap text-gray-700">{{ team.name }}</td>
              </tr>
              <tr v-if="loading">
                <td colspan="2" class="px-6 py-4 text-center">
                  <div class="flex items-center justify-center">
                    <div class="animate-spin rounded-full h-5 w-5 border-b-2 border-emerald-500"></div>
                    <span class="ml-2 text-gray-500">Loading data...</span>
                  </div>
                </td>
              </tr>
              <tr v-else-if="filteredTeams.length === 0">
                <td colspan="2" class="px-6 py-4 text-center text-gray-500">No sales team data available.</td>
              </tr>
            </tbody>
          </table>
        </div>
        <div class="mt-2 text-xs text-gray-500 italic">
          <p>Click on a row to select, double-click to select and close the modal.</p>
        </div>
        <div class="mt-4 grid grid-cols-4 gap-2">
          <button type="button" @click="sortTable('code')" class="py-2 px-3 bg-gray-100 border border-gray-400 hover:bg-gray-200 text-xs rounded-lg transform active:translate-y-px">
            <i class="fas fa-sort mr-1"></i>Sort by Code
          </button>
          <button type="button" @click="sortTable('name')" class="py-2 px-3 bg-gray-100 border border-gray-400 hover:bg-gray-200 text-xs rounded-lg transform active:translate-y-px">
            <i class="fas fa-sort mr-1"></i>Sort by Name
          </button>
          <button type="button" @click="selectAndClose(selectedTeam)" class="py-2 px-3 bg-emerald-500 hover:bg-emerald-600 text-white text-xs rounded-lg transform active:translate-y-px">
            <i class="fas fa-edit mr-1"></i>Select Team
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
import { ref, computed, watch } from 'vue';

const props = defineProps({
  show: Boolean,
  salesTeams: {
    type: Array,
    default: () => []
  }
});

const emit = defineEmits(['close', 'select']);

const searchQuery = ref('');
const selectedTeam = ref(null);
const sortKey = ref('code');
const sortAsc = ref(true);
const loading = ref(false);

// Compute filtered teams based on search query
const filteredTeams = computed(() => {
  let teams = props.salesTeams || [];
  
  if (searchQuery.value) {
    const query = searchQuery.value.toLowerCase();
    teams = teams.filter(team =>
      (team.code && team.code.toLowerCase().includes(query)) ||
      (team.name && team.name.toLowerCase().includes(query))
    );
  }
  
  // Apply sorting
  return [...teams].sort((a, b) => {
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
function selectRow(team) {
  selectedTeam.value = team;
}

// Select and close modal
function selectAndClose(team) {
  if (team) {
    emit('select', team);
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

// Reset selection when modal is opened
watch(() => props.show, (val) => {
  if (val) {
    selectedTeam.value = null;
    searchQuery.value = '';
  }
});
</script>
