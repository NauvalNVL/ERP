<template>
  <div v-if="show" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-4xl mx-auto">
      <!-- Modal Header -->
      <div class="flex items-center justify-between p-4 border-b border-gray-200 bg-gradient-to-r from-green-600 to-green-700 text-white rounded-t-lg">
        <div class="flex items-center">
          <div class="p-2 bg-white bg-opacity-30 rounded-lg mr-3">
            <i class="fas fa-code-branch"></i>
          </div>
          <h3 class="text-xl font-semibold">Analysis Code Table</h3>
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
            <input type="text" v-model="searchQuery" placeholder="Search analysis codes..."
              class="pl-10 pr-4 py-2 w-full border border-gray-300 rounded-lg focus:ring-emerald-500 focus:border-emerald-500 bg-gray-50">
          </div>
        </div>
        <div class="overflow-x-auto rounded-lg border border-gray-200 max-h-96">
          <table class="w-full divide-y divide-gray-200 table-fixed">
            <thead class="bg-gray-50 sticky top-0">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/4 cursor-pointer" @click="sortTable('analysis_code')">Analysis Code</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/3 cursor-pointer" @click="sortTable('analysis_name')">Analysis Name</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/5">Group</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/5">Group2</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200 text-xs">
              <tr v-for="code in filteredAnalysisCodes" :key="code.analysis_code"
                :class="['hover:bg-emerald-50 cursor-pointer', selectedCode && selectedCode.analysis_code === code.analysis_code ? 'bg-emerald-100 border-l-4 border-emerald-500' : '']"
                @click="selectRow(code)"
                @dblclick="selectAndClose(code)">
                <td class="px-6 py-3 whitespace-nowrap font-medium text-gray-900">{{ code.analysis_code }}</td>
                <td class="px-6 py-3 whitespace-nowrap text-gray-700">{{ code.analysis_name }}</td>
                <td class="px-6 py-3 whitespace-nowrap">
                  <span class="px-2 py-1 text-xs font-medium rounded-full bg-emerald-100 text-emerald-800">{{ code.analysis_group }}</span>
                </td>
                <td class="px-6 py-3 whitespace-nowrap text-gray-700">{{ code.analysis_group2 }}</td>
              </tr>
              <tr v-if="filteredAnalysisCodes.length === 0">
                <td colspan="4" class="px-6 py-4 text-center text-gray-500">No analysis codes found.</td>
              </tr>
            </tbody>
          </table>
        </div>
        <div class="mt-2 text-xs text-gray-500 italic">
          <p>Click on a row to select and edit its details.</p>
        </div>
        <div class="mt-4 grid grid-cols-3 gap-2">
          <button type="button" @click="sortTable('analysis_code')" class="py-2 px-3 bg-gray-100 border border-gray-400 hover:bg-gray-200 text-xs rounded-lg transform active:translate-y-px">
            <i class="fas fa-sort mr-1"></i>By Code
          </button>
          <button type="button" @click="selectAndClose(selectedCode)" class="py-2 px-3 bg-gradient-to-r from-emerald-500 to-green-600 hover:from-emerald-600 hover:to-green-700 text-white text-xs rounded-lg transform active:translate-y-px">
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
import { ref, computed } from 'vue';

const props = defineProps({
    show: Boolean,
    analysisCodes: {
        type: Array,
        default: () => []
    }
});

const emit = defineEmits(['close', 'select', 'refresh']);

const searchQuery = ref('');
const selectedCode = ref(null);
const sortKey = ref('analysis_code');
const sortAsc = ref(true);

const filteredAnalysisCodes = computed(() => {
    let filtered = props.analysisCodes || [];
    
    // Apply search filter
    if (searchQuery.value) {
        const query = searchQuery.value.toLowerCase();
        filtered = filtered.filter(code => 
            code.analysis_code?.toLowerCase().includes(query) ||
            code.analysis_name?.toLowerCase().includes(query) ||
            code.analysis_group?.toLowerCase().includes(query) ||
            code.analysis_group2?.toLowerCase().includes(query)
        );
    }
    
    // Apply sorting
    const sorted = [...filtered].sort((a, b) => {
        const aVal = a[sortKey.value] || '';
        const bVal = b[sortKey.value] || '';
        
        if (sortAsc.value) {
            return aVal.toString().localeCompare(bVal.toString());
        } else {
            return bVal.toString().localeCompare(aVal.toString());
        }
    });
    
    return sorted;
});

const sortTable = (key) => {
    if (sortKey.value === key) {
        sortAsc.value = !sortAsc.value;
    } else {
        sortKey.value = key;
        sortAsc.value = true;
    }
};

const selectRow = (code) => {
    selectedCode.value = code;
};

const selectAndClose = (code) => {
    if (!code) {
        return;
    }
    emit('select', code);
    emit('close');
};
</script>