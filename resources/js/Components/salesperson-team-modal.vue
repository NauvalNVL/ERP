<template>
  <div v-if="show" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-6xl min-w-4xl mx-4">
      <!-- Modal Header -->
      <div class="flex items-center justify-between p-4 border-b border-gray-200 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-t-lg">
        <div class="flex items-center">
          <div class="p-2 bg-white bg-opacity-30 rounded-lg mr-3">
            <i class="fas fa-users"></i>
          </div>
          <h3 class="text-xl font-semibold">Salesperson Team Table</h3>
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
            <input type="text" v-model="searchQuery" placeholder="Search salesperson teams..."
              class="pl-10 pr-4 py-2 w-full border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 bg-gray-50">
          </div>
        </div>
        <div class="overflow-x-auto rounded-lg border border-gray-200 max-h-96">
          <table class="w-full divide-y divide-gray-200 table-fixed">
            <thead class="bg-gray-50 sticky top-0">
              <tr>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-16 cursor-pointer" @click="sortTable('s_person_code')">Code</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-32 cursor-pointer" @click="sortTable('salesperson_name')">Name</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-40 cursor-pointer" @click="sortTable('st_code')">Team Code</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-48 cursor-pointer" @click="sortTable('sales_team_name')">Team Name</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-28 cursor-pointer" @click="sortTable('sales_team_position')">Position</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-24 cursor-pointer" @click="sortTable('status')">Status</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200 text-xs">
              <tr v-for="team in filteredTeams" :key="team.id || team.s_person_code"
                :class="['hover:bg-blue-50 cursor-pointer', selectedTeam && selectedTeam.s_person_code === team.s_person_code ? 'bg-blue-100 border-l-4 border-blue-500' : '']"
                @click="selectRow(team)"
                @dblclick="selectAndClose(team)">
                <td class="px-4 py-3 whitespace-nowrap font-medium text-gray-900">{{ getSalespersonCode(team.s_person_code) }}</td>
                <td class="px-4 py-3 whitespace-nowrap text-gray-700">{{ getSalespersonName(team.s_person_code) }}</td>
                <td class="px-4 py-3 whitespace-nowrap">
                  <div v-if="isEditing(team.id || team.s_person_code, 'st_code')" class="flex items-center space-x-1 w-full">
                    <select v-model="editingValue" class="flex-1 text-xs border border-gray-300 rounded px-2 py-1 focus:ring-blue-500 focus:border-blue-500 min-w-0">
                      <option value="">Select Team</option>
                      <option v-for="salesTeam in salesTeams" :key="salesTeam.id" :value="salesTeam.code">
                        {{ salesTeam.code }} - {{ salesTeam.name }}
                      </option>
                    </select>
                    <button @click="saveEdit(team, 'st_code')" class="flex-shrink-0 text-green-600 hover:text-green-800 p-1">
                      <i class="fas fa-check text-xs"></i>
                    </button>
                    <button @click="cancelEdit()" class="flex-shrink-0 text-red-600 hover:text-red-800 p-1">
                      <i class="fas fa-times text-xs"></i>
                    </button>
                  </div>
                  <div v-else class="flex items-center justify-between group">
                    <span class="px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">{{ team.st_code }}</span>
                    <button @click="startEdit(team.id || team.s_person_code, 'st_code', team.st_code)" class="opacity-0 group-hover:opacity-100 text-gray-400 hover:text-blue-600 ml-2">
                      <i class="fas fa-edit text-xs"></i>
                    </button>
                  </div>
                </td>
                <td class="px-4 py-3 whitespace-nowrap text-gray-700">{{ team.sales_team_name }}</td>
                <td class="px-4 py-3 whitespace-nowrap">
                  <div v-if="isEditing(team.id || team.s_person_code, 'sales_team_position')" class="flex items-center space-x-1 w-full">
                    <select v-model="editingValue" class="flex-1 text-xs border border-gray-300 rounded px-2 py-1 focus:ring-blue-500 focus:border-blue-500 min-w-0">
                      <option value="">Select Position</option>
                      <option v-for="position in positionOptions" :key="position.value" :value="position.value">
                        {{ position.label }}
                      </option>
                    </select>
                    <button @click="saveEdit(team, 'sales_team_position')" class="flex-shrink-0 text-green-600 hover:text-green-800 p-1">
                      <i class="fas fa-check text-xs"></i>
                    </button>
                    <button @click="cancelEdit()" class="flex-shrink-0 text-red-600 hover:text-red-800 p-1">
                      <i class="fas fa-times text-xs"></i>
                    </button>
                  </div>
                  <div v-else class="flex items-center justify-between group">
                    <span class="text-gray-700">{{ getPositionLabel(team.sales_team_position) }}</span>
                    <button @click="startEdit(team.id || team.s_person_code, 'sales_team_position', team.sales_team_position)" class="opacity-0 group-hover:opacity-100 text-gray-400 hover:text-blue-600 ml-2">
                      <i class="fas fa-edit text-xs"></i>
                    </button>
                  </div>
                </td>
                <td class="px-4 py-3 whitespace-nowrap">
                  <div v-if="isEditing(team.id || team.s_person_code, 'status')" class="flex items-center space-x-1 w-full">
                    <select v-model="editingValue" class="flex-1 text-xs border border-gray-300 rounded px-2 py-1 focus:ring-blue-500 focus:border-blue-500 min-w-0">
                      <option value="">Select Status</option>
                      <option value="Active">Active</option>
                      <option value="Inactive">Inactive</option>
                      <option value="Suspended">Suspended</option>
                    </select>
                    <button @click="saveEdit(team, 'status')" class="flex-shrink-0 text-green-600 hover:text-green-800 p-1">
                      <i class="fas fa-check text-xs"></i>
                    </button>
                    <button @click="cancelEdit()" class="flex-shrink-0 text-red-600 hover:text-red-800 p-1">
                      <i class="fas fa-times text-xs"></i>
                    </button>
                  </div>
                  <div v-else class="flex items-center justify-between group">
                    <span :class="[
                      'px-2 py-1 text-xs font-medium rounded-full',
                      team.status === 'Active' ? 'bg-green-100 text-green-800' : 
                      team.status === 'Inactive' ? 'bg-gray-100 text-gray-800' : 
                      team.status === 'Suspended' ? 'bg-red-100 text-red-800' : 
                      'bg-gray-100 text-gray-800'
                    ]">{{ team.status || 'Active' }}</span>
                    <button @click="startEdit(team.id || team.s_person_code, 'status', team.status || 'Active')" class="opacity-0 group-hover:opacity-100 text-gray-400 hover:text-blue-600 ml-2">
                      <i class="fas fa-edit text-xs"></i>
                    </button>
                  </div>
                </td>
              </tr>
              <tr v-if="filteredTeams.length === 0">
                <td colspan="6" class="px-4 py-4 text-center text-gray-500">No salesperson team data available.</td>
              </tr>
            </tbody>
          </table>
        </div>
        <div class="mt-2 text-xs text-gray-500 italic">
          <p>Click on a row to select and edit its details.</p>
        </div>
        <div class="mt-4 grid grid-cols-5 gap-2">
          <button type="button" @click="sortTable('s_person_code')" class="py-2 px-3 bg-gray-100 border border-gray-400 hover:bg-gray-200 text-xs rounded-lg transform active:translate-y-px">
            <i class="fas fa-sort mr-1"></i>By Code
          </button>
          <button type="button" @click="sortTable('salesperson_name')" class="py-2 px-3 bg-gray-100 border border-gray-400 hover:bg-gray-200 text-xs rounded-lg transform active:translate-y-px">
            <i class="fas fa-sort mr-1"></i>By Name
          </button>
          <button type="button" @click="sortTable('sales_team_name')" class="py-2 px-3 bg-gray-100 border border-gray-400 hover:bg-gray-200 text-xs rounded-lg transform active:translate-y-px">
            <i class="fas fa-sort mr-1"></i>By Team
          </button>
          <button type="button" @click="selectAndClose(selectedTeam)" class="py-2 px-3 bg-blue-500 hover:bg-blue-600 text-white text-xs rounded-lg transform active:translate-y-px">
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
  salespersonTeams: {
    type: Array,
    default: () => []
  },
  salesTeams: {
    type: Array,
    default: () => []
  },
  salespersons: {
    type: Array,
    default: () => []
  }
});

const emit = defineEmits(['close', 'select', 'update']);

const searchQuery = ref('');
const selectedTeam = ref(null);
const sortKey = ref('s_person_code');
const sortAsc = ref(true);

// Inline editing state
const editingCell = ref(null);
const editingValue = ref('');

// Position options
const positionOptions = [
  { value: 'E-Executive', label: 'Executive' },
  { value: 'M-Manager', label: 'Manager' },
  { value: 'S-Supervisor', label: 'Supervisor' }
];

// Compute filtered teams based on search query
const filteredTeams = computed(() => {
  let teams = props.salespersonTeams;
  if (searchQuery.value) {
    const q = searchQuery.value.toLowerCase();
    teams = teams.filter(team => {
      const actualCode = getSalespersonCode(team.s_person_code);
      const actualName = getSalespersonName(team.s_person_code);
      
      return (actualCode && actualCode.toLowerCase().includes(q)) ||
             (actualName && actualName.toLowerCase().includes(q)) ||
             (team.st_code && team.st_code.toLowerCase().includes(q)) ||
             (team.sales_team_name && team.sales_team_name.toLowerCase().includes(q)) ||
             (team.sales_team_position && team.sales_team_position.toLowerCase().includes(q)) ||
             (team.status && team.status.toLowerCase().includes(q));
    });
  }
  
  // Apply sorting
  return [...teams].sort((a, b) => {
    let valueA = a[sortKey.value] || '';
    let valueB = b[sortKey.value] || '';
    
    // Handle sorting for salesperson code and name from define salesperson
    if (sortKey.value === 's_person_code') {
      valueA = getSalespersonCode(a.s_person_code);
      valueB = getSalespersonCode(b.s_person_code);
    } else if (sortKey.value === 'salesperson_name') {
      valueA = getSalespersonName(a.s_person_code);
      valueB = getSalespersonName(b.s_person_code);
    } else if (sortKey.value === 'sales_team_position') {
      valueA = getPositionLabel(valueA);
      valueB = getPositionLabel(valueB);
    }
    
    if (valueA.toLowerCase() < valueB.toLowerCase()) return sortAsc.value ? -1 : 1;
    if (valueA.toLowerCase() > valueB.toLowerCase()) return sortAsc.value ? 1 : -1;
    return 0;
  });
});

// Helper function to get position label
function getPositionLabel(position) {
  if (position === 'E-Executive') return 'Executive';
  if (position === 'M-Manager') return 'Manager';
  if (position === 'S-Supervisor') return 'Supervisor';
  return position;
}

// Helper function to get salesperson data from define salesperson
function getSalespersonData(salespersonCode) {
  return props.salespersons.find(sp => sp.code === salespersonCode) || null;
}

// Helper function to get salesperson name from define salesperson
function getSalespersonName(salespersonCode) {
  const salesperson = getSalespersonData(salespersonCode);
  return salesperson ? salesperson.name : salespersonCode;
}

// Helper function to get actual salesperson code (in case it needs normalization)
function getSalespersonCode(salespersonCode) {
  const salesperson = getSalespersonData(salespersonCode);
  return salesperson ? salesperson.code : salespersonCode;
}

// Inline editing functions
function startEdit(teamId, field, currentValue) {
  editingCell.value = `${teamId}-${field}`;
  editingValue.value = currentValue;
}

function cancelEdit() {
  editingCell.value = null;
  editingValue.value = '';
}

function saveEdit(team, field) {
  if (field === 'st_code') {
    // Auto-fill team name when team code changes
    const selectedSalesTeam = props.salesTeams.find(st => st.code === editingValue.value);
    if (selectedSalesTeam) {
      team.st_code = editingValue.value;
      team.sales_team_name = selectedSalesTeam.name;
    }
  } else {
    team[field] = editingValue.value;
  }
  
  // Emit update event
  emit('update', team);
  cancelEdit();
}

function isEditing(teamId, field) {
  return editingCell.value === `${teamId}-${field}`;
}

// Select a row
function selectRow(team) {
  selectedTeam.value = team;
}

// Select and close modal
function selectAndClose(team) {
  if (team) {
    emit('select', team);
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

// Reset selection when modal is opened
watch(() => props.show, (val) => {
  if (val) {
    selectedTeam.value = null;
    searchQuery.value = '';
  }
});
</script>
