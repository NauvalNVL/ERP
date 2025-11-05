<template>
  <div v-if="show" class="fixed inset-0 z-50 bg-black bg-opacity-50 flex items-center justify-center">
    <div class="bg-white rounded-lg shadow-xl w-11/12 md:w-4/5 lg:w-3/5 max-h-[90vh] max-w-4xl mx-auto overflow-hidden">
      <div class="flex items-center justify-between p-4 border-b border-gray-200 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-t-lg">
        <div class="flex items-center">
          <div class="p-2 bg-white bg-opacity-30 rounded-lg mr-3">
            <i class="fas fa-tools"></i>
          </div>
          <h3 class="text-xl font-semibold">Scoring Tool Board</h3>
        </div>
        <button type="button" @click="$emit('close')" class="text-white hover:text-gray-200">
          <i class="fas fa-times text-xl"></i>
        </button>
      </div>
      
      <!-- Search Filter -->
      <div class="p-4 bg-gray-50 border-b border-gray-200">
        <div class="flex flex-wrap items-center gap-3">
          <div class="flex-grow">
            <div class="relative">
              <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i class="fas fa-search text-gray-400"></i>
              </div>
              <input type="text" v-model="searchFilter" placeholder="Search by code or name..." 
                     class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white focus:outline-none focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out sm:text-sm">
            </div>
          </div>
        </div>
      </div>
      
      <!-- Table Content -->
      <div class="overflow-auto max-h-[50vh]">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50 sticky top-0">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Code</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Scorer Gap</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr v-if="filteredScoringTools.length === 0" class="hover:bg-gray-50">
              <td colspan="3" class="px-6 py-4 text-center text-sm text-gray-500">
                No scoring tools found.
              </td>
            </tr>
            <tr v-for="tool in filteredScoringTools" :key="tool.id" 
                :class="['hover:bg-gray-50 cursor-pointer', selectedTool && selectedTool.id === tool.id ? 'bg-blue-100 border-l-4 border-blue-500' : '']"
                @click="selectedTool = tool"
                @dblclick="selectTool(tool)">
              <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ tool.code }}</td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ tool.name }}</td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ tool.scorer_gap }}</td>
            </tr>
          </tbody>
        </table>
      </div>
      
      <!-- Footer -->
      <div class="px-4 py-3 bg-gray-50 border-t border-gray-200 sm:px-6 flex justify-end space-x-3">
        <button type="button" 
                @click="selectTool(selectedTool)" 
                :disabled="!selectedTool"
                :class="[
                  'inline-flex justify-center px-4 py-2 text-sm font-medium rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transform active:translate-y-px',
                  selectedTool 
                    ? 'text-white bg-blue-600 hover:bg-blue-700 border border-blue-600' 
                    : 'text-gray-400 bg-gray-200 border border-gray-300 cursor-not-allowed'
                ]">
          <i class="fas fa-check mr-1"></i>
          Select
        </button>
        <button type="button" @click="$emit('close')" class="inline-flex justify-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transform active:translate-y-px">
          <i class="fas fa-times mr-1"></i>
          Close
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, watch } from 'vue';

const props = defineProps({
  show: {
    type: Boolean,
    required: true
  },
  scoringTools: {
    type: Array,
    required: true
  }
});

const emit = defineEmits(['close', 'select']);

const searchFilter = ref('');
const selectedTool = ref(null);

const filteredScoringTools = computed(() => {
  if (!searchFilter.value) {
    return props.scoringTools;
  }
  
  const query = searchFilter.value.toLowerCase();
  return props.scoringTools.filter(tool => 
    tool.code.toLowerCase().includes(query) || 
    tool.name.toLowerCase().includes(query)
  );
});

const selectTool = (tool) => {
  emit('select', tool);
};

// Reset search filter and selection when modal is opened/closed
watch(() => props.show, (newValue) => {
  if (newValue) {
    // Reset when modal opens
    selectedTool.value = null;
    searchFilter.value = '';
  } else {
    // Reset when modal closes
    selectedTool.value = null;
    searchFilter.value = '';
  }
});
</script>
