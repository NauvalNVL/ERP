<template>
  <div v-if="show" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
      <div class="fixed inset-0 bg-black bg-opacity-60 transition-opacity" aria-hidden="true" @click="closeModal"></div>
      <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
      <div class="inline-block align-bottom bg-white rounded-lg text-left shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full animate-modalScaleIn">
        <!-- Header -->
        <div class="bg-gradient-to-r from-gray-700 to-gray-800 px-6 py-4 relative rounded-t-lg">
          <div class="flex items-center">
            <div class="bg-white bg-opacity-20 p-2 rounded-full mr-4">
              <i class="fas fa-cog text-white text-xl"></i>
            </div>
            <h3 class="text-xl font-bold text-white" id="modal-title">SKU Options</h3>
          </div>
          <button @click="closeModal" class="absolute top-4 right-4 text-white opacity-70 hover:opacity-100 transition-opacity">
            <i class="fas fa-times text-2xl"></i>
          </button>
        </div>

        <!-- Content -->
        <div class="bg-white px-6 py-4">
          <div class="space-y-6">
            <!-- Sorting Options -->
            <div>
              <h4 class="text-lg font-semibold mb-3">Sort by:</h4>
              <div class="bg-gray-50 p-4 rounded border border-gray-200 space-y-2">
                <label class="flex items-center">
                  <input type="radio" v-model="sortOptions.field" value="sku" class="mr-3 text-blue-600">
                  <span class="text-gray-700">SKU#</span>
                </label>
                <label class="flex items-center">
                  <input type="radio" v-model="sortOptions.field" value="cat_sku" class="mr-3 text-blue-600">
                  <span class="text-gray-700">CAT + SKU#</span>
                </label>
                <label class="flex items-center">
                  <input type="radio" v-model="sortOptions.field" value="part" class="mr-3 text-blue-600">
                  <span class="text-gray-700">SKU Part#</span>
                </label>
                <label class="flex items-center">
                  <input type="radio" v-model="sortOptions.field" value="name" class="mr-3 text-blue-600">
                  <span class="text-gray-700">SKU Name</span>
                </label>
              </div>
            </div>

            <!-- Record Status -->
            <div>
              <h4 class="text-lg font-semibold mb-3">Record Status:</h4>
              <div class="bg-gray-50 p-4 rounded border border-gray-200 flex flex-wrap gap-6">
                <label class="flex items-center">
                  <input type="checkbox" v-model="recordFilters.active" class="mr-3 text-blue-600 rounded">
                  <span class="text-gray-700">Active</span>
                </label>
                <label class="flex items-center">
                  <input type="checkbox" v-model="recordFilters.obsolete" class="mr-3 text-blue-600 rounded">
                  <span class="text-gray-700">Obsolete</span>
                </label>
              </div>
            </div>

            <!-- Record Type -->
            <div>
              <h4 class="text-lg font-semibold mb-3">Record Type:</h4>
              <div class="bg-gray-50 p-4 rounded border border-gray-200 flex flex-wrap gap-6">
                <label class="flex items-center">
                  <input type="checkbox" v-model="recordFilters.stockItem" class="mr-3 text-blue-600 rounded">
                  <span class="text-gray-700">Stock Item</span>
                </label>
                <label class="flex items-center">
                  <input type="checkbox" v-model="recordFilters.nonStock" class="mr-3 text-blue-600 rounded">
                  <span class="text-gray-700">Non Stock</span>
                </label>
              </div>
            </div>
            
            <!-- Display Type -->
            <div>
              <h4 class="text-lg font-semibold mb-3">Display Mode:</h4>
              <div class="bg-gray-50 p-4 rounded border border-gray-200">
                <label class="flex items-center">
                  <input type="checkbox" v-model="displayOptions.showDetailedView" class="mr-3 text-blue-600 rounded">
                  <span class="text-gray-700">Show detailed view</span>
                </label>
              </div>
            </div>
          </div>
        </div>

        <!-- Footer -->
        <div class="bg-gray-100 px-6 py-4 flex justify-end space-x-3 rounded-b-lg">
          <button @click="applyOptions" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded transition-colors">
            OK
          </button>
          <button @click="closeModal" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2 px-6 rounded transition-colors">
            Exit
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';

const props = defineProps({
  show: {
    type: Boolean,
    required: true,
  },
  initialSortOptions: {
    type: Object,
    default: () => ({
      field: 'sku',
      ascending: true
    })
  },
  initialRecordFilters: {
    type: Object,
    default: () => ({
      active: true,
      obsolete: true,
      stockItem: true,
      nonStock: true
    })
  },
  initialDisplayOptions: {
    type: Object,
    default: () => ({
      showDetailedView: false
    })
  }
});

const emit = defineEmits(['close', 'apply']);

// State
const sortOptions = ref({...props.initialSortOptions});
const recordFilters = ref({...props.initialRecordFilters});
const displayOptions = ref({...props.initialDisplayOptions});

// Initialize options when modal opens
onMounted(() => {
  sortOptions.value = {...props.initialSortOptions};
  recordFilters.value = {...props.initialRecordFilters};
  displayOptions.value = {...props.initialDisplayOptions};
});

const applyOptions = () => {
  // At least one status filter must be selected
  if (!recordFilters.value.active && !recordFilters.value.obsolete) {
    recordFilters.value.active = true;
  }
  
  // At least one type filter must be selected
  if (!recordFilters.value.stockItem && !recordFilters.value.nonStock) {
    recordFilters.value.stockItem = true;
  }
  
  emit('apply', {
    sortOptions: sortOptions.value,
    recordFilters: recordFilters.value,
    displayOptions: displayOptions.value
  });
  
  closeModal();
};

const closeModal = () => {
  emit('close');
};
</script>

<style scoped>
@keyframes modalScaleIn {
  from { transform: scale(0.95); opacity: 0; }
  to { transform: scale(1); opacity: 1; }
}

.animate-modalScaleIn {
  animation: modalScaleIn 0.3s ease-out forwards;
}
</style> 