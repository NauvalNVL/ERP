<template>
    <div v-if="show" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-3xl">
            <!-- Header -->
            <div class="flex items-center justify-between p-4 border-b border-gray-200 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-t-lg">
                <div class="flex items-center">
                    <div class="p-2 bg-white bg-opacity-30 rounded-lg mr-3">
                        <i class="fas fa-paperclip"></i>
                    </div>
                    <h3 class="text-xl font-semibold">Stitch Wire Table</h3>
                </div>
                <button @click="$emit('close')" class="text-white hover:text-gray-200 focus:outline-none transform active:translate-y-px">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <!-- Body -->
            <div class="p-5">
                <!-- Search -->
                <div class="mb-4">
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
                            <i class="fas fa-search"></i>
                        </span>
                        <input type="text" v-model="searchQuery" placeholder="Search by code or name..."
                               class="pl-10 pr-4 py-2 w-full border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 bg-gray-50">
                    </div>
                </div>

                <!-- Table -->
                <div class="overflow-x-auto rounded-lg border border-gray-200 max-h-96">
                    <table class="w-full divide-y divide-gray-200 table-fixed">
                        <thead class="bg-gray-50 sticky top-0">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/6 cursor-pointer" @click="toggleSort('code')">Code</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer" @click="toggleSort('name')">Name</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200 text-xs">
                            <tr v-if="loading">
                                <td colspan="2" class="px-6 py-4 text-center text-gray-500">
                                    <div class="flex items-center justify-center">
                                        <div class="animate-spin rounded-full h-5 w-5 border-b-2 border-blue-500 mr-2"></div>
                                        Loading...
                                    </div>
                                </td>
                            </tr>
                            <tr v-else v-for="item in filteredItems" :key="item.code"
                                :class="['hover:bg-blue-50 cursor-pointer', selected && selected.code === item.code ? 'bg-blue-100 border-l-4 border-blue-500' : '']"
                                @click="selectRow(item)"
                                @dblclick="selectAndClose(item)">
                                <td class="px-6 py-3 whitespace-nowrap font-medium text-gray-900">{{ item.code }}</td>
                                <td class="px-6 py-3 whitespace-nowrap text-gray-700">{{ item.name }}</td>
                            </tr>
                            <tr v-if="!loading && filteredItems.length === 0">
                                <td colspan="2" class="px-6 py-4 text-center text-gray-500">No data available.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Footer -->
                <div class="mt-4 grid grid-cols-3 gap-2">
                    <button type="button" @click="toggleSort('code')" class="py-2 px-3 bg-gray-100 border border-gray-400 hover:bg-gray-200 text-xs rounded-lg transform active:translate-y-px">
                        <i class="fas fa-sort mr-1"></i>By Code
                    </button>
                    <button type="button" @click="toggleSort('name')" class="py-2 px-3 bg-gray-100 border border-gray-400 hover:bg-gray-200 text-xs rounded-lg transform active:translate-y-px">
                        <i class="fas fa-sort mr-1"></i>By Name
                    </button>
                    <div class="flex gap-2">
                        <button type="button" @click="selectAndClose(selected)" class="flex-1 py-2 px-3 bg-blue-500 hover:bg-blue-600 text-white text-xs rounded-lg transform active:translate-y-px">
                            <i class="fas fa-edit mr-1"></i>Select
                        </button>
                        <button type="button" @click="$emit('close')" class="flex-1 py-2 px-3 bg-gray-300 hover:bg-gray-400 text-gray-800 text-xs rounded-lg transform active:translate-y-px">
                            <i class="fas fa-times mr-1"></i>Exit
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, watch, onMounted } from 'vue';

const props = defineProps({
    show: Boolean
});

const emit = defineEmits(['close', 'select']);

const searchQuery = ref('');
const selected = ref(null);
const sortKey = ref('code');
const sortAsc = ref(true);
const stitchWires = ref([]);
const loading = ref(false);

// Fetch stitch wires from API
const fetchStitchWires = async () => {
    loading.value = true;
    try {
        const response = await fetch('/api/stitch-wires', {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            credentials: 'same-origin'
        });

        if (!response.ok) {
            throw new Error('Failed to fetch stitch wires');
        }

        const data = await response.json();

        // Handle different response formats
        if (Array.isArray(data)) {
            stitchWires.value = data;
        } else if (data.data && Array.isArray(data.data)) {
            stitchWires.value = data.data;
        } else {
            stitchWires.value = [];
        }
    } catch (error) {
        console.error('Error fetching stitch wires:', error);
        stitchWires.value = [];
    } finally {
        loading.value = false;
    }
};

const filteredItems = computed(() => {
    const query = searchQuery.value.toLowerCase();
    let list = stitchWires.value || [];
    if (query) {
        list = list.filter(it =>
            (it.code || '').toLowerCase().includes(query) ||
            (it.name || '').toLowerCase().includes(query)
        );
    }
    return [...list].sort((a, b) => {
        const va = (a[sortKey.value] || '').toString().toLowerCase();
        const vb = (b[sortKey.value] || '').toString().toLowerCase();
        return sortAsc.value ? va.localeCompare(vb) : vb.localeCompare(va);
    });
});

function selectRow(item) {
    selected.value = item;
}

function selectAndClose(item) {
    if (item) {
        emit('select', item);
        emit('close');
    }
}

function toggleSort(key) {
    if (sortKey.value === key) {
        sortAsc.value = !sortAsc.value;
    } else {
        sortKey.value = key;
        sortAsc.value = true;
    }
}

// Watch for modal opening and fetch data
watch(() => props.show, (val) => {
    if (val) {
        selected.value = null;
        searchQuery.value = '';
        fetchStitchWires(); // Fetch fresh data when modal opens
    }
});

// Initial fetch on mount
onMounted(() => {
    if (props.show) {
        fetchStitchWires();
    }
});
</script>


