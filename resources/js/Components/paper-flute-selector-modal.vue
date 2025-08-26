<template>
  <div v-if="show" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-4xl mx-auto flex flex-col max-h-[90vh]">
      <!-- Modal Header -->
      <div class="flex items-center justify-between p-4 border-b border-gray-200 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-t-lg">
        <div class="flex items-center">
          <div class="p-2 bg-white bg-opacity-30 rounded-lg mr-3 shadow-inner">
            <i class="fas fa-layer-group"></i>
          </div>
          <div>
            <h3 class="text-xl font-semibold">Paper Flute Table</h3>
            <p class="text-xs text-blue-100">Select a paper flute type.</p>
          </div>
        </div>
        <button @click="$emit('close')" class="text-white hover:text-gray-200 focus:outline-none transform active:translate-y-px bg-red-500 hover:bg-red-600 h-8 w-8 rounded-full flex items-center justify-center">
          <i class="fas fa-times"></i>
        </button>
      </div>
      
      <!-- Modal Content -->
      <div class="p-5 overflow-y-auto flex-grow">
        <div class="mb-4 bg-gray-50 p-3 rounded-lg border border-gray-200 shadow-sm">
          <div class="flex flex-wrap items-center gap-3">
            <div class="relative flex-grow">
              <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
                <i class="fas fa-search"></i>
              </span>
              <input type="text" v-model="searchQuery" placeholder="Search by flute code or description..."
                class="pl-10 pr-4 py-2 w-full border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 bg-white shadow-sm">
            </div>
            <div class="flex space-x-2">
              <button type="button" @click="searchQuery = ''" class="py-2 px-3 bg-gray-200 hover:bg-gray-300 text-gray-700 text-sm rounded-lg flex items-center">
                <i class="fas fa-eraser mr-1"></i> Clear
              </button>
            </div>
          </div>
        </div>
        
        <div class="overflow-x-auto rounded-lg border border-gray-200 max-h-96">
          <table class="w-full divide-y divide-gray-200">
            <thead class="bg-gray-50 sticky top-0">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer" @click="sortTable('code')">
                  Paper Flute
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer" @click="sortTable('description')">
                  Description
                </th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200 text-xs">
              <tr v-for="flute in filteredFlutes" :key="flute.code"
                :class="['hover:bg-blue-50 cursor-pointer group', selectedFlute && selectedFlute.code === flute.code ? 'bg-blue-100 border-l-4 border-blue-500' : '']"
                @click="selectFlute(flute)"
                @dblclick="selectAndClose(flute)"
                title="Double-click to select this flute">
                <td class="px-6 py-3 whitespace-nowrap font-medium text-gray-900 relative">
                  {{ flute.code }}
                  <span class="absolute top-1 right-1 opacity-0 group-hover:opacity-100 transition-opacity text-blue-500">
                    <i class="fas fa-mouse-pointer text-xs"></i>
                  </span>
                </td>
                <td class="px-6 py-3 whitespace-nowrap text-gray-700">{{ flute.description || flute.name || flute.code }}</td>
              </tr>
              <tr v-if="filteredFlutes.length === 0">
                <td colspan="2" class="px-6 py-4 text-center text-gray-500">No paper flute data available.</td>
              </tr>
            </tbody>
          </table>
        </div>
        
        <div class="mt-4 flex justify-between items-center">
          <div class="flex items-center text-sm text-gray-500">
            <i class="fas fa-info-circle mr-2 text-blue-500"></i>
            <p>{{ filteredFlutes.length }} flutes found. Double-click a row to select.</p>
            <span v-if="selectedFlute" class="ml-2 px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs">
              Selected: {{ selectedFlute.code }}
            </span>
          </div>
          <div class="flex space-x-2">
            <button type="button" @click="selectAndClose(selectedFlute)" class="py-2 px-4 bg-blue-500 hover:bg-blue-600 text-white text-sm rounded-lg flex items-center shadow-sm" :disabled="!selectedFlute" :class="{'opacity-50 cursor-not-allowed': !selectedFlute}">
              <i class="fas fa-check-circle mr-2"></i>Select
            </button>
            <button type="button" @click="$emit('close')" class="py-2 px-4 bg-gray-300 hover:bg-gray-400 text-gray-800 text-sm rounded-lg flex items-center">
              <i class="fas fa-times mr-2"></i>Exit
            </button>
          </div>
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
    default: false
  },
  flutes: {
    type: Array,
    default: () => []
  }
});

const emit = defineEmits(['select', 'close']);

const searchQuery = ref('');
const selectedFlute = ref(null);
const sortKey = ref('code');
const sortAsc = ref(true);

// Compute filtered flutes based on search query
const filteredFlutes = computed(() => {
  let flutes = props.flutes;
  if (searchQuery.value) {
    const q = searchQuery.value.toLowerCase();
    flutes = flutes.filter(flute => {
      const searchText = [
        flute.code,
        flute.description,
        flute.name
      ].filter(Boolean).join(' ').toLowerCase();
      return searchText.includes(q);
    });
  }

  // Apply sorting
  return [...flutes].sort((a, b) => {
    // Handle null values for sorting
    const aVal = a[sortKey.value] || '';
    const bVal = b[sortKey.value] || '';

    if (aVal < bVal) return sortAsc.value ? -1 : 1;
    if (aVal > bVal) return sortAsc.value ? 1 : -1;
    return 0;
  });
});

// Select a flute when clicked
const selectFlute = (flute) => {
  selectedFlute.value = flute;
};

// Select and close modal
const selectAndClose = (flute) => {
  if (flute) {
    emit('select', flute);
    emit('close');
  }
};

// Sort table by the given key
const sortTable = (key) => {
  if (sortKey.value === key) {
    sortAsc.value = !sortAsc.value;
  } else {
    sortKey.value = key;
    sortAsc.value = true;
  }
};

// Reset selection when modal is opened
watch(() => props.show, (val) => {
  if (val) {
    selectedFlute.value = null;
    searchQuery.value = '';
  }
});
</script>

<style scoped>
/* Add custom styles for better table display */
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

/* Enhanced table styling */
table {
  border-collapse: separate;
  border-spacing: 0;
}

thead th {
  background: linear-gradient(to bottom, #f9fafb, #f3f4f6);
  position: sticky;
  top: 0;
  z-index: 10;
  box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
  transition: background-color 0.2s;
}

thead th:hover {
  background: linear-gradient(to bottom, #f3f4f6, #e5e7eb);
}

tbody tr {
  transition: all 0.2s;
}

tbody tr:hover {
  transform: translateY(-1px);
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
  z-index: 1;
  position: relative;
}

tbody tr.bg-blue-100 {
  box-shadow: 0 0 0 1px rgba(59, 130, 246, 0.5);
}

/* Search input styling */
input[type="text"] {
  transition: all 0.3s;
}

input[type="text"]:focus {
  box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.25);
  border-color: #3b82f6;
}

/* Button styling */
button {
  transition: all 0.2s;
}

button:hover {
  transform: translateY(-1px);
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

button:active {
  transform: translateY(0);
}

.bg-blue-500:hover {
  background-color: #3b82f6;
  box-shadow: 0 4px 6px -1px rgba(59, 130, 246, 0.4);
}

/* Modal styling */
.fixed.inset-0 {
  backdrop-filter: blur(2px);
}

.bg-white.rounded-lg {
  box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
  animation: modal-appear 0.3s ease-out forwards;
}

@keyframes modal-appear {
  from {
    opacity: 0;
    transform: translateY(-20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

/* Badge styling */
.rounded-full.bg-blue-100 {
  box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
  border: 1px solid rgba(59, 130, 246, 0.2);
}
</style> 