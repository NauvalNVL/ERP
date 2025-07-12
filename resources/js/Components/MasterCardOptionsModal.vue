<template>
  <div v-if="show" class="fixed inset-0 z-50 bg-black bg-opacity-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-md mx-auto">
      <!-- Modal Header -->
      <div class="flex items-center justify-between p-4 border-b border-gray-200 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-t-lg">
        <h3 class="text-xl font-semibold flex items-center text-white">
          <i class="fas fa-cog mr-3"></i>
          MC Search Options
        </h3>
        <button type="button" @click="closeModal" class="text-white hover:text-gray-200 focus:outline-none">
          <i class="fas fa-times text-xl"></i>
        </button>
      </div>

      <!-- Modal Body -->
      <div class="p-6">
        <div class="mb-4">
          <label class="block text-sm font-medium text-gray-700 mb-1">Sort By:</label>
          <div class="flex flex-wrap gap-4">
            <label class="inline-flex items-center">
              <input
                type="radio"
                v-model="selectedSortColumn"
                value="mc_seq"
                name="sort-by"
                class="form-radio h-5 w-5 text-blue-600"
              />
              <span class="ml-2 text-gray-700">MC Sequence</span>
            </label>
            <label class="inline-flex items-center">
              <input
                type="radio"
                v-model="selectedSortColumn"
                value="mc_model"
                name="sort-by"
                class="form-radio h-5 w-5 text-blue-600"
              />
              <span class="ml-2 text-gray-700">MC Model</span>
            </label>
            <label class="inline-flex items-center">
              <input
                type="radio"
                v-model="selectedSortColumn"
                value="part"
                name="sort-by"
                class="form-radio h-5 w-5 text-blue-600"
              />
              <span class="ml-2 text-gray-700">Part</span>
            </label>
            <label class="inline-flex items-center">
              <input
                type="radio"
                v-model="selectedSortColumn"
                value="p_design"
                name="sort-by"
                class="form-radio h-5 w-5 text-blue-600"
              />
              <span class="ml-2 text-gray-700">Product Design</span>
            </label>
            <label class="inline-flex items-center">
              <input
                type="radio"
                v-model="selectedSortColumn"
                value="status"
                name="sort-by"
                class="form-radio h-5 w-5 text-blue-600"
              />
              <span class="ml-2 text-gray-700">Status</span>
            </label>
          </div>
        </div>

        <div class="mb-6">
          <label class="block text-sm font-medium text-gray-700 mb-1">Status Filter:</label>
          <div class="flex flex-wrap gap-4">
            <label class="inline-flex items-center">
              <input
                type="checkbox"
                v-model="filterStatus.active"
                class="form-checkbox h-5 w-5 text-blue-600"
              />
              <span class="ml-2 text-gray-700">Active</span>
            </label>
            <label class="inline-flex items-center">
              <input
                type="checkbox"
                v-model="filterStatus.obsolete"
                class="form-checkbox h-5 w-5 text-blue-600"
              />
              <span class="ml-2 text-gray-700">Obsolete</span>
            </label>
            <label class="inline-flex items-center">
              <input
                type="checkbox"
                v-model="filterStatus.pending"
                class="form-checkbox h-5 w-5 text-blue-600"
              />
              <span class="ml-2 text-gray-700">Pending</span>
            </label>
          </div>
        </div>

        <!-- Modal Footer -->
        <div class="flex justify-end space-x-3">
          <button
            type="button"
            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400"
            @click="closeModal"
          >
            Cancel
          </button>
          <button
            type="button"
            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700"
            @click="confirmOptions"
          >
            Continue
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, watch } from 'vue';

const props = defineProps({
  show: Boolean,
  targetField: String, // 'mcsFrom' or 'mcsTo'
  initialSortColumn: { type: String, default: 'mc_seq' },
  initialSortDirection: { type: String, default: 'asc' },
  initialFilterStatus: { 
    type: Object, 
    default: () => ({ active: true, obsolete: false, pending: false })
  },
});

const emit = defineEmits(['close', 'confirm']);

const selectedSortColumn = ref(props.initialSortColumn);
const selectedSortDirection = ref(props.initialSortDirection); 
const filterStatus = ref({ ...props.initialFilterStatus });

// Watch for prop changes to reset modal state if needed
watch(() => props.show, (newVal) => {
  if (newVal) {
    selectedSortColumn.value = props.initialSortColumn;
    selectedSortDirection.value = props.initialSortDirection;
    filterStatus.value = { ...props.initialFilterStatus };
  }
});

const confirmOptions = () => {
  emit('confirm', {
    sortColumn: selectedSortColumn.value,
    sortDirection: selectedSortDirection.value,
    filterStatus: filterStatus.value,
    targetField: props.targetField,
  });
};

const closeModal = () => {
  emit('close');
};
</script>

<style scoped>
/* Add any specific styles for this modal here */
</style> 