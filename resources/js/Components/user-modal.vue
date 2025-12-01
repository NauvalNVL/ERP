<template>
  <div v-if="show" class="fixed inset-0 z-100 flex items-center justify-center overflow-y-auto">
    <!-- Background overlay -->
    <div class="fixed inset-0 bg-black bg-opacity-50" @click="$emit('close')"></div>
    
    <!-- Modal content -->
    <div class="bg-white rounded-lg shadow-lg w-full max-w-2xl z-110 relative">
      <!-- Modal Header -->
      <div class="flex items-center justify-between p-4 border-b border-gray-200 bg-gradient-to-r from-green-600 to-green-700 text-white rounded-t-lg">
        <div class="flex items-center">
          <div class="p-2 bg-white bg-opacity-30 rounded-lg mr-3">
            <i class="fas fa-users"></i>
          </div>
          <h3 class="text-xl font-semibold">User Table</h3>
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
            <input type="text" v-model="searchQuery" placeholder="Search users..."
              class="pl-10 pr-4 py-2 w-full border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500 bg-gray-50">
          </div>
        </div>
        
        <div class="overflow-x-auto rounded-lg border border-gray-200 max-h-96">
          <table class="w-full divide-y divide-gray-200 table-fixed">
            <thead class="bg-gray-50 sticky top-0">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/4 cursor-pointer" @click="sortTable('code')">Code</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/2 cursor-pointer" @click="sortTable('name')">Name</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200 text-xs">
              <tr v-for="user in filteredUsers" :key="user.code"
                :class="['hover:bg-green-50 cursor-pointer', selectedUser && selectedUser.code === user.code ? 'bg-green-100 border-l-4 border-green-500' : '']"
                @click="selectRow(user)"
                @dblclick="selectAndClose(user)">
                <td class="px-6 py-3 whitespace-nowrap font-medium text-gray-900">{{ user.code }}</td>
                <td class="px-6 py-3 whitespace-nowrap text-gray-700">{{ user.name }}</td>
              </tr>
              <tr v-if="filteredUsers.length === 0">
                <td colspan="2" class="px-6 py-4 text-center text-gray-500">No user data available.</td>
              </tr>
            </tbody>
          </table>
        </div>
        
        <div class="mt-2 text-xs text-gray-500 italic">
          <p>Click on a row to select and edit its details.</p>
        </div>
        
        <div class="mt-4 grid grid-cols-4 gap-2">
          <button type="button" @click="sortTable('code')" class="py-2 px-3 bg-gray-100 border border-gray-400 hover:bg-gray-200 text-xs rounded-lg transform active:translate-y-px">
            <i class="fas fa-sort mr-1"></i>By Code
          </button>
          <button type="button" @click="sortTable('name')" class="py-2 px-3 bg-gray-100 border border-gray-400 hover:bg-gray-200 text-xs rounded-lg transform active:translate-y-px">
            <i class="fas fa-sort mr-1"></i>By Name
          </button>
          <button type="button" @click="selectAndClose(selectedUser)" class="py-2 px-3 bg-green-500 hover:bg-green-600 text-white text-xs rounded-lg transform active:translate-y-px">
            <i class="fas fa-check mr-1"></i>Select
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
  users: {
    type: Array,
    default: () => []
  }
});

const emit = defineEmits(['close', 'select']);

const selectedUser = ref(null);
const searchQuery = ref('');
const sortKey = ref('code');
const sortAsc = ref(true);

// Compute filtered users based on search query
const filteredUsers = computed(() => {
  let people = props.users;
  if (searchQuery.value) {
    const q = searchQuery.value.toLowerCase();
    people = people.filter(user =>
      user.code.toLowerCase().includes(q) ||
      user.name.toLowerCase().includes(q)
    );
  }
  
  // Apply sorting
  return [...people].sort((a, b) => {
    const valueA = a[sortKey.value];
    const valueB = b[sortKey.value];
    
    if (valueA < valueB) return sortAsc.value ? -1 : 1;
    if (valueA > valueB) return sortAsc.value ? 1 : -1;
    return 0;
  });
});

// Select a row
function selectRow(user) {
  selectedUser.value = user;
}

// Select and close modal
function selectAndClose(user) {
  if (user) {
    emit('select', user);
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
    selectedUser.value = null;
    searchQuery.value = '';
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
