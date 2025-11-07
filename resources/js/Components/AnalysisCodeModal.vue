<template>
    <div v-if="show" class="fixed inset-0 z-50 flex items-center justify-center overflow-x-hidden overflow-y-auto outline-none focus:outline-none backdrop-blur-sm bg-black bg-opacity-50 transition-opacity duration-300">
        <div class="relative w-full max-w-3xl mx-auto my-6 transform transition-all duration-300 scale-100 opacity-100" :class="{'scale-95 opacity-0': !show}">
            <!-- Modal content -->
            <div class="relative flex flex-col w-full bg-white border-0 rounded-xl shadow-2xl outline-none focus:outline-none overflow-hidden">
                <!-- Modal header -->
                <div class="flex items-center justify-between p-6 bg-gradient-to-r from-blue-600 to-indigo-700 rounded-t-xl shadow-md">
                    <h3 class="text-2xl font-bold text-white">
                        Analysis Code Table
                    </h3>
                    <button 
                        class="p-2 ml-auto bg-transparent border-0 text-white hover:text-gray-200 transition-colors duration-200 text-3xl leading-none font-semibold outline-none focus:outline-none"
                        @click="$emit('close')"
                    >
                        <span class="block outline-none focus:outline-none transform hover:rotate-90 transition-transform duration-300">
                            ×
                        </span>
                    </button>
                </div>
                <!-- Modal body -->
                <div class="relative p-6 flex-auto max-h-[70vh] overflow-y-auto custom-scrollbar">
                    <div class="mb-6">
                        <input 
                            type="text" 
                            v-model="searchQuery"
                            @input="debouncedSearch"
                            placeholder="Search by Code or Name..."
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                        />
                    </div>
                    <div class="overflow-x-auto rounded-lg shadow-md border border-gray-200">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-blue-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider cursor-pointer" @click="sortTable('analysis_code')">Analysis Code</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider cursor-pointer" @click="sortTable('analysis_name')">Analysis Name</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Group</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Group2</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr v-for="code in filteredAnalysisCodes" :key="code.analysis_code"
                                    :class="['hover:bg-blue-50 cursor-pointer transition-colors duration-150', selectedCode && selectedCode.analysis_code === code.analysis_code ? 'bg-blue-100 border-l-4 border-blue-500' : '']"
                                    @click="selectRow(code)"
                                    @dblclick="selectAndClose(code)">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ code.analysis_code }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ code.analysis_name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">{{ code.analysis_group }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ code.analysis_group2 }}</td>
                                </tr>
                                <tr v-if="filteredAnalysisCodes.length === 0">
                                    <td colspan="4" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">No analysis codes found.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- Modal footer -->
                <div class="p-6 bg-gray-50 border-t border-solid border-gray-200 rounded-b-xl">
                    <div class="grid grid-cols-3 gap-2">
                        <button type="button" @click="sortTable('analysis_code')" class="py-2 px-3 bg-gray-100 border border-gray-400 hover:bg-gray-200 text-xs rounded-lg transform active:translate-y-px">
                            <i class="fas fa-sort mr-1"></i>By Code
                        </button>
                        <button type="button" @click="selectAndClose(selectedCode)" class="py-2 px-3 bg-blue-500 hover:bg-blue-600 text-white text-xs rounded-lg transform active:translate-y-px">
                            <i class="fas fa-edit mr-1"></i>Select
                        </button>
                        <button type="button" @click="$emit('close')" class="py-2 px-3 bg-gray-300 hover:bg-gray-400 text-gray-800 text-xs rounded-lg transform active:translate-y-px">
                            <i class="fas fa-times mr-1"></i>Exit
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div v-if="show" class="fixed inset-0 z-40 bg-black opacity-50 transition-opacity duration-300"></div>
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

<style scoped>
/* Custom Scrollbar */
.custom-scrollbar::-webkit-scrollbar {
  width: 8px;
}

.custom-scrollbar::-webkit-scrollbar-track {
  background: #f1f1f1;
  border-radius: 10px;
}

.custom-scrollbar::-webkit-scrollbar-thumb {
  background: #888;
  border-radius: 10px;
}

.custom-scrollbar::-webkit-scrollbar-thumb:hover {
  background: #555;
}
</style>