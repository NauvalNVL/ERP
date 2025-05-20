<template>
  <div v-if="show" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-2xl">
      <!-- Modal Header -->
      <div class="flex items-center justify-between p-4 border-b border-gray-200 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-t-lg">
        <div class="flex items-center">
          <div class="p-2 bg-white bg-opacity-30 rounded-lg mr-3">
            <i class="fas fa-layer-group"></i>
          </div>
          <h3 class="text-xl font-semibold">Color Group Table</h3>
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
            <input type="text" v-model="searchQuery" placeholder="Search color groups..."
              class="pl-10 pr-4 py-2 w-full border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 bg-gray-50">
          </div>
        </div>
        <div class="overflow-x-auto rounded-lg border border-gray-200">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50 sticky top-0">
              <tr>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer" @click="sortTable('cg')">CG#</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer" @click="sortTable('cg_name')">CG Name</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer" @click="sortTable('cg_type')">CG Type</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200 text-xs">
              <tr v-for="group in filteredColorGroups" :key="group.cg"
                :class="['hover:bg-blue-50 cursor-pointer', selectedGroup && selectedGroup.cg === group.cg ? 'bg-blue-600 text-white' : '']"
                @click="selectRow(group)"
                @dblclick="selectAndClose(group)">
                <td class="px-4 py-3 whitespace-nowrap font-medium text-gray-900">{{ group.cg }}</td>
                <td class="px-4 py-3 whitespace-nowrap text-gray-700">{{ group.cg_name }}</td>
                <td class="px-4 py-3 whitespace-nowrap text-gray-700">{{ group.cg_type }}</td>
              </tr>
              <tr v-if="filteredColorGroups.length === 0">
                <td colspan="3" class="px-4 py-4 text-center text-gray-500">Tidak ada data color group yang tersedia.</td>
              </tr>
            </tbody>
          </table>
        </div>
        <div class="mt-4 grid grid-cols-5 gap-2">
          <button type="button" @click="sortTable('cg')" class="py-2 px-3 bg-gray-100 border border-gray-400 hover:bg-gray-200 text-xs rounded-lg transform active:translate-y-px">
            <i class="fas fa-sort mr-1"></i>By CG#
          </button>
          <button type="button" @click="sortTable('cg_name')" class="py-2 px-3 bg-gray-100 border border-gray-400 hover:bg-gray-200 text-xs rounded-lg transform active:translate-y-px">
            <i class="fas fa-sort mr-1"></i>By CG Name
          </button>
          <button type="button" @click="sortTable('cg_type')" class="py-2 px-3 bg-gray-100 border border-gray-400 hover:bg-gray-200 text-xs rounded-lg transform active:translate-y-px">
            <i class="fas fa-sort mr-1"></i>By CG Type
          </button>
          <button type="button" @click="selectAndClose(selectedGroup)" class="py-2 px-3 bg-blue-500 hover:bg-blue-600 text-white text-xs rounded-lg transform active:translate-y-px">
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
  colorGroups: {
    type: Array,
    default: () => []
  }
});

const emit = defineEmits(['close', 'select']);

const searchQuery = ref('');
const selectedGroup = ref(null);
const sortKey = ref('cg');
const sortAsc = ref(true);

const filteredColorGroups = computed(() => {
  let groups = props.colorGroups;
  if (searchQuery.value) {
    const q = searchQuery.value.toLowerCase();
    groups = groups.filter(g =>
      g.cg.toLowerCase().includes(q) ||
      g.cg_name.toLowerCase().includes(q) ||
      g.cg_type.toLowerCase().includes(q)
    );
  }
  // Sort
  return [...groups].sort((a, b) => {
    if (a[sortKey.value] < b[sortKey.value]) return sortAsc.value ? -1 : 1;
    if (a[sortKey.value] > b[sortKey.value]) return sortAsc.value ? 1 : -1;
    return 0;
  });
});

function selectRow(group) {
  selectedGroup.value = group;
}

function selectAndClose(group) {
  if (group) {
    emit('select', group);
    emit('close');
  }
}

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
    selectedGroup.value = null;
    searchQuery.value = '';
  }
});
</script>
