<template>
  <div v-if="show" class="fixed inset-0 z-100 flex items-center justify-center overflow-y-auto px-4 sm:px-0">
    <!-- Background overlay -->
    <div class="fixed inset-0 bg-black bg-opacity-50" @click="$emit('close')"></div>

    <!-- Modal content -->
    <div class="bg-white rounded-lg shadow-lg w-full max-w-4xl z-110 relative max-h-[90vh] flex flex-col">
      <!-- Modal Header -->
      <div class="flex items-center justify-between p-4 border-b border-gray-200 bg-gradient-to-r from-green-600 to-green-700 text-white rounded-t-lg">
        <div class="flex items-center">
          <div class="p-2 bg-white bg-opacity-30 rounded-lg mr-3">
            <i class="fas fa-user-tie"></i>
          </div>
          <h3 class="text-xl font-semibold">Salesperson Table</h3>
        </div>
        <button @click="$emit('close')" class="text-white hover:text-gray-200 focus:outline-none transform active:translate-y-px">
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
            <input type="text" v-model="searchQuery" placeholder="Search salespersons..."
              class="pl-10 pr-4 py-2 w-full border border-gray-300 rounded-lg focus:ring-emerald-500 focus:border-emerald-500 bg-gray-50">
          </div>
        </div>
        <div class="overflow-x-auto rounded-lg border border-gray-200 max-h-96 flex-1 min-h-0">
          <table class="min-w-[720px] w-full divide-y divide-gray-200 table-fixed">
            <thead class="bg-gray-50 sticky top-0">
              <tr>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-[15%] cursor-pointer" @click="sortTable('code')">Code</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-[22%] cursor-pointer" @click="sortTable('name')">Name</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-[14%] cursor-pointer" @click="sortTable('grup')">Group</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-[12%]">Code Group</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-[17%]">Target Sales</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-[20%]">Email</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200 text-xs">
              <tr v-for="person in filteredSalespersons" :key="person.code"
                :class="['hover:bg-emerald-50 cursor-pointer', selectedPerson && selectedPerson.code === person.code ? 'bg-emerald-100 border-l-4 border-emerald-500' : '']"
                @click="selectRow(person)"
                @dblclick="selectAndClose(person)">
                <td class="px-4 py-3 whitespace-nowrap font-medium text-gray-900">{{ person.code }}</td>
                <td class="px-4 py-3 whitespace-nowrap text-gray-700 truncate max-w-[140px]">{{ person.name }}</td>
                <td class="px-4 py-3 whitespace-nowrap text-gray-700 truncate max-w-[110px]">{{ person.grup || '-' }}</td>
                <td class="px-4 py-3 whitespace-nowrap text-gray-700">{{ person.code_grup || '-' }}</td>
                <td class="px-4 py-3 whitespace-nowrap text-gray-700">{{ person.target_sales ? Number(person.target_sales).toFixed(2) : '0.00' }}</td>
                <td class="px-4 py-3 whitespace-nowrap text-gray-700 truncate max-w-[180px]" :title="person.email">{{ person.email || '-' }}</td>
              </tr>
              <tr v-if="filteredSalespersons.length === 0">
                <td colspan="6" class="px-6 py-4 text-center text-gray-500">No salesperson data available.</td>
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
          <button type="button" @click="selectAndClose(selectedPerson)" class="py-2 px-3 bg-emerald-500 hover:bg-emerald-600 text-white text-xs rounded-lg transform active:translate-y-px">
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
  salespersons: {
    type: Array,
    default: () => []
  }
});

const emit = defineEmits(['close', 'select']);

const searchQuery = ref('');
const selectedPerson = ref(null);
const sortKey = ref('code');
const sortAsc = ref(true);

// Compute filtered salespersons based on search query
const filteredSalespersons = computed(() => {
  let people = props.salespersons;
  if (searchQuery.value) {
    const q = searchQuery.value.toLowerCase();
    people = people.filter(person =>
      person.code.toLowerCase().includes(q) ||
      person.name.toLowerCase().includes(q) ||
      (person.grup && person.grup.toLowerCase().includes(q)) ||
      (person.email && person.email.toLowerCase().includes(q))
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
function selectRow(person) {
  selectedPerson.value = person;
}

// Select and close modal
function selectAndClose(person) {
  if (person) {
    emit('select', person);
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
    selectedPerson.value = null;
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
