<template>
  <div v-if="show" class="fixed inset-0 z-50 bg-black bg-opacity-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-sm mx-auto">
      <!-- Modal Header -->
      <div class="flex items-center justify-between p-3 border-b border-gray-200 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-t-lg">
        <h3 class="text-xl font-semibold flex items-center">
          <i class="fas fa-cogs mr-2"></i>Options
        </h3>
        <button type="button" @click="handleExit" class="text-white hover:text-gray-200 focus:outline-none">
          <i class="fas fa-times text-xl"></i>
        </button>
      </div>

      <!-- Modal Body -->
      <div class="p-4">
        <div class="border border-gray-300 rounded-md p-3 mb-4">
          <label class="block text-sm font-medium text-gray-700 mb-2">Sort by:</label>
          <div class="flex flex-col space-y-2">
            <label class="inline-flex items-center">
              <input type="radio" class="form-radio text-blue-600 h-4 w-4" value="customer_code" v-model="sortBy">
              <span class="ml-2 text-gray-700">Customer Code</span>
            </label>
            <label class="inline-flex items-center">
              <input type="radio" class="form-radio text-blue-600 h-4 w-4" value="customer_name" v-model="sortBy">
              <span class="ml-2 text-gray-700">Customer Name</span>
            </label>
          </div>
        </div>

        <div class="border border-gray-300 rounded-md p-3">
          <label class="block text-sm font-medium text-gray-700 mb-2">Record Status:</label>
          <div class="flex space-x-4">
            <label class="inline-flex items-center">
              <input type="checkbox" class="form-checkbox h-4 w-4 text-blue-600" value="active" v-model="recordStatus.active">
              <span class="ml-2 text-gray-700">Active</span>
            </label>
            <label class="inline-flex items-center">
              <input type="checkbox" class="form-checkbox h-4 w-4 text-blue-600" value="obsolete" v-model="recordStatus.obsolete">
              <span class="ml-2 text-gray-700">Obsolete</span>
            </label>
          </div>
        </div>
      </div>

      <!-- Modal Footer -->
      <div class="flex items-center justify-end p-3 border-t border-gray-200 bg-gray-100 rounded-b-lg space-x-2">
        <button @click="handleOk" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none">OK</button>
        <button @click="handleExit" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 focus:outline-none">Exit</button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, watch } from 'vue';

const props = defineProps({
  show: Boolean,
});

const emit = defineEmits(['confirm', 'close']);

const sortBy = ref('customer_code'); // Default sort by customer code
const recordStatus = ref({
  active: true,
  obsolete: false,
});

// Watch for changes in the show prop to reset defaults when opened
watch(() => props.show, (newVal) => {
  if (newVal) {
    sortBy.value = 'customer_code';
    recordStatus.value = {
      active: true,
      obsolete: false,
    };
  }
});

const handleOk = () => {
  const selectedStatus = [];
  if (recordStatus.value.active) {
    selectedStatus.push('Active');
  }
  if (recordStatus.value.obsolete) {
    selectedStatus.push('Obsolete');
  }
  emit('confirm', {
    sortBy: sortBy.value,
    statusFilter: selectedStatus,
  });
  emit('close');
};

const handleExit = () => {
  emit('close');
};
</script>

<style scoped>
/* Add any specific styles for this modal here */
</style> 