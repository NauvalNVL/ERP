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
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Code</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Name</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Group</th>
                                    <th scope="col" class="relative px-6 py-3"><span class="sr-only">Select</span></th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr v-for="code in filteredAnalysisCodes" :key="code.code" @click="selectAnalysisCode(code)" class="hover:bg-blue-50 cursor-pointer transition-colors duration-150">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ code.code }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ code.name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ code.group }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <button @click.stop="selectAnalysisCode(code)" class="text-blue-600 hover:text-blue-900 font-semibold transition-colors duration-150">Select</button>
                                    </td>
                                </tr>
                                <tr v-if="filteredAnalysisCodes.length === 0">
                                    <td colspan="4" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">No analysis codes found.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- Modal footer -->
                <div class="flex items-center justify-end p-6 bg-gray-50 border-t border-solid border-gray-200 rounded-b-xl">
                    <button 
                        class="text-gray-700 bg-gray-200 hover:bg-gray-300 font-bold uppercase px-6 py-2 text-sm rounded-lg shadow hover:shadow-md outline-none focus:outline-none mr-3 transition-all duration-150"
                        type="button"
                        @click="$emit('close')"
                    >
                        Exit
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div v-if="show" class="fixed inset-0 z-40 bg-black opacity-50 transition-opacity duration-300"></div>
</template>

<script setup>
import { ref, watch, computed, onMounted } from 'vue';
import debounce from 'lodash/debounce';
import axios from 'axios';

const props = defineProps({
    show: {
        type: Boolean,
        default: false,
    },
    group: {
        type: String,
        default: null,
    },
});

const emits = defineEmits(['close', 'select-analysis-code']);

const searchQuery = ref('');
const analysisCodes = ref([]); // Data yang fetched dari API
const rawAnalysisCodes = ref([]); // Menyimpan data asli yang belum difilter

const fetchAnalysisCodes = async (group = null) => {
    try {
        console.log('Fetching analysis codes with group:', group);
        let url = '/api/material-management/analysis-codes';
        if (group) {
            url += `?group=${encodeURIComponent(group)}`;
        }
        console.log('API URL:', url);
        const response = await axios.get(url);
        console.log('API Response:', response.data);
        // Ensure we're handling the response correctly
        const data = Array.isArray(response.data) ? response.data : [];
        rawAnalysisCodes.value = data; // Simpan data asli
        analysisCodes.value = data; // Awalnya, data yang ditampilkan sama dengan data asli
        console.log('Analysis codes loaded:', data.length);
    } catch (error) {
        console.error('Error fetching analysis codes:', error);
        rawAnalysisCodes.value = [];
        analysisCodes.value = [];
    }
};

const filteredAnalysisCodes = computed(() => {
    if (!searchQuery.value) {
        return analysisCodes.value;
    }
    const query = searchQuery.value.toLowerCase();
    return analysisCodes.value.filter(code => 
        (code.code && code.code.toLowerCase().includes(query)) || 
        (code.name && code.name.toLowerCase().includes(query))
    );
});

const selectAnalysisCode = (code) => {
    // Ensure we're passing the correct data structure
    const analysisCodeData = {
        code: code.code,
        name: code.name,
        group: code.group
    };
    console.log('Selecting analysis code:', analysisCodeData);
    emits('select-analysis-code', analysisCodeData);
    emits('close');
};

const applySearchFilter = () => {
    if (!searchQuery.value) {
        analysisCodes.value = rawAnalysisCodes.value; // Reset ke data asli jika search kosong
    } else {
        const query = searchQuery.value.toLowerCase();
        analysisCodes.value = rawAnalysisCodes.value.filter(code => 
            (code.code && code.code.toLowerCase().includes(query)) || 
            (code.name && code.name.toLowerCase().includes(query))
        );
    }
};

const debouncedSearch = debounce(applySearchFilter, 300); // Debounce search by 300ms

// Fetch data when component is mounted and shown
onMounted(() => {
    if (props.show) {
        fetchAnalysisCodes(props.group);
    }
});

watch(() => props.show, (newValue) => {
    if (newValue) {
        fetchAnalysisCodes(props.group);
        searchQuery.value = ''; // Reset search query when modal opens
    }
});

watch(() => props.group, (newGroup) => {
    if (props.show) {
        fetchAnalysisCodes(newGroup);
    }
});

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