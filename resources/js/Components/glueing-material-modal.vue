<template>
    <div v-if="show" class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto bg-black bg-opacity-50 px-4 sm:px-0">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-4xl max-h-[90vh] flex flex-col">
            <div class="flex items-center justify-between p-4 border-b border-gray-200 bg-gradient-to-r from-green-600 to-green-700 text-white rounded-t-lg">
                <div class="flex items-center">
                    <div class="p-2 bg-white bg-opacity-30 rounded-lg mr-3">
                        <i class="fas fa-vial"></i>
                    </div>
                    <h3 class="text-xl font-semibold">Glueing Material Table</h3>
                </div>
                <button @click="$emit('close')" class="text-white hover:text-gray-200"><i class="fas fa-times text-xl"></i></button>
            </div>

            <div class="p-5 flex-1 flex flex-col min-h-0">
                <div class="mb-4">
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
                            <i class="fas fa-search"></i>
                        </span>
                        <input v-model="searchQuery" type="text" placeholder="Search by code or name..." class="pl-10 pr-4 py-2 w-full border border-gray-300 rounded-lg focus:ring-emerald-500 focus:border-emerald-500 bg-gray-50" />
                    </div>
                </div>

                <div class="overflow-x-auto rounded-lg border border-gray-200 max-h-96 flex-1 min-h-0">
                    <table class="w-full divide-y divide-gray-200 table-fixed min-w-[480px] md:min-w-0">
                        <thead class="bg-gray-50 sticky top-0">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-[18%] cursor-pointer" @click="toggleSort('code')">Code</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-[57%] cursor-pointer" @click="toggleSort('name')">Name</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-[25%]">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200 text-xs">
                            <tr v-for="item in filteredItems" :key="item.code"
                                :class="['hover:bg-emerald-50 cursor-pointer', selected && selected.code === item.code ? 'bg-emerald-100 border-l-4 border-emerald-500' : '']"
                                @click="selectRow(item)"
                                @dblclick="selectAndClose(item)">
                                <td class="px-4 py-3 whitespace-nowrap font-medium text-gray-900">{{ item.code }}</td>
                                <td class="px-4 py-3 whitespace-nowrap text-gray-700 truncate max-w-[220px] md:max-w-none">{{ item.name }}</td>
                                <td class="px-4 py-3 whitespace-nowrap text-left">
                                    <span
                                        :class="[
                                            item.status === 'Obs'
                                                ? 'bg-red-100 text-red-800'
                                                : 'bg-emerald-100 text-emerald-800',
                                            'px-2 py-1 text-[10px] font-semibold rounded-full inline-flex items-center justify-center'
                                        ]"
                                    >
                                        {{ item.status === 'Obs' ? 'Obsolete' : 'Active' }}
                                    </span>
                                </td>
                            </tr>
                            <tr v-if="filteredItems.length === 0">
                                <td colspan="3" class="px-6 py-4 text-center text-gray-500">No data available.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="mt-4 grid grid-cols-3 gap-2">
                    <button type="button" @click="toggleSort('code')" class="py-2 px-3 bg-gray-100 border border-gray-400 hover:bg-gray-200 text-xs rounded-lg"><i class="fas fa-sort mr-1"></i>By Code</button>
                    <button type="button" @click="toggleSort('name')" class="py-2 px-3 bg-gray-100 border border-gray-400 hover:bg-gray-200 text-xs rounded-lg"><i class="fas fa-sort mr-1"></i>By Name</button>
                    <div class="flex gap-2">
                        <button type="button" @click="selectAndClose(selected)" class="flex-1 py-2 px-3 bg-gradient-to-r from-emerald-500 to-green-600 hover:from-emerald-600 hover:to-green-700 text-white text-xs rounded-lg"><i class="fas fa-edit mr-1"></i>Select</button>
                        <button type="button" @click="$emit('close')" class="flex-1 py-2 px-3 bg-gray-300 hover:bg-gray-400 text-gray-800 text-xs rounded-lg"><i class="fas fa-times mr-1"></i>Exit</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, watch } from 'vue';

const props = defineProps({
    show: Boolean,
    items: {
        type: Array,
        default: () => []
    }
});

const emit = defineEmits(['close', 'select']);

const searchQuery = ref('');
const selected = ref(null);
const sortKey = ref('code');
const sortAsc = ref(true);

const filteredItems = computed(() => {
    const q = searchQuery.value.toLowerCase();
    let list = props.items || [];
    if (q) {
        list = list.filter(it => (it.code || '').toLowerCase().includes(q) || (it.name || '').toLowerCase().includes(q));
    }
    return [...list].sort((a,b) => {
        const va = (a[sortKey.value] || '').toString().toLowerCase();
        const vb = (b[sortKey.value] || '').toString().toLowerCase();
        return sortAsc.value ? va.localeCompare(vb) : vb.localeCompare(va);
    });
});

function selectRow(item) { selected.value = item; }
function selectAndClose(item) { if (item) { emit('select', item); emit('close'); } }
function toggleSort(key) { if (sortKey.value === key) sortAsc.value = !sortAsc.value; else { sortKey.value = key; sortAsc.value = true; } }

watch(() => props.show, (val) => { if (val) { selected.value = null; searchQuery.value = ''; } });
</script>


