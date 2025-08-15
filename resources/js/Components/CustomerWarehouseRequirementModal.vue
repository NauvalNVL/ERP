<template>
  <div v-if="show" class="fixed inset-0 z-50 bg-black bg-opacity-50 flex items-center justify-center">
    <div class="bg-white rounded-lg shadow-xl w-11/12 md:w-2/3 lg:w-3/4 max-w-4xl mx-auto">
      <!-- Modal Header -->
      <div class="flex items-center justify-between p-4 border-b border-gray-200 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-t-lg">
        <h3 class="text-xl font-semibold flex items-center">
          <i class="fas fa-warehouse mr-3"></i>Warehouse Locations
        </h3>
        <div class="flex space-x-3 items-center">
          <button type="button" @click="$emit('close')" class="text-white hover:text-gray-200 focus:outline-none">
            <i class="fas fa-times text-xl"></i>
          </button>
        </div>
      </div>

      <!-- Modal Body -->
      <div class="p-2 overflow-y-auto" style="max-height: 60vh;">
        <div v-if="loading" class="flex justify-center items-center p-4">
          <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-500"></div>
          <span class="ml-2 text-gray-600">Loading data...</span>
        </div>
        <div v-else-if="error" class="p-4 text-red-500 bg-red-50 rounded border border-red-200">
          <div class="font-bold mb-1">Error:</div>
          <div>{{ error }}</div>
          <button @click="fetchLocations" class="mt-2 px-3 py-1 bg-blue-500 text-white text-xs rounded hover:bg-blue-600">
            Try Again
          </button>
        </div>
        <div v-else-if="filteredLocations.length === 0" class="p-4 text-amber-700 bg-amber-50 rounded border border-amber-200">
          No warehouse locations found. Please add new locations first.
        </div>
        <table v-else class="min-w-full text-xs border border-gray-300">
          <thead class="bg-gray-200 sticky top-0">
            <tr>
              <th class="px-2 py-1 border border-gray-300 text-left">
                <button @click="$emit('sort-by-code')" class="flex items-center">
                  Code <i class="fas fa-sort ml-1"></i>
                </button>
              </th>
              <th class="px-2 py-1 border border-gray-300 text-left">
                <button @click="$emit('sort-by-name')" class="flex items-center">
                  Description <i class="fas fa-sort ml-1"></i>
                </button>
              </th>
              <th class="px-2 py-1 border border-gray-300 text-left">Type</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr v-for="location in filteredLocations" 
                :key="location.code"
                class="hover:bg-blue-100 cursor-pointer"
                :class="{ 'bg-blue-200': selectedLocation?.code === location.code }"
                @click="selectLocation(location)">
              <td class="px-2 py-1 border border-gray-300">{{ location.code }}</td>
              <td class="px-2 py-1 border border-gray-300">{{ location.description }}</td>
              <td class="px-2 py-1 border border-gray-300">{{ location.type }}</td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Modal Footer -->
      <div class="flex items-center justify-end gap-2 p-2 border-t border-gray-200 bg-gray-100 rounded-b-lg">
        <div class="text-xs text-gray-500 mr-auto" v-if="filteredLocations.length > 0">
          {{ filteredLocations.length }} locations found
        </div>
        <button 
          @click="handleSelect" 
          :disabled="!selectedLocation"
          class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-3 rounded text-xs disabled:opacity-50 disabled:cursor-not-allowed">
          Select
        </button>
        <button type="button" @click="$emit('close')" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-1 px-3 rounded text-xs">Exit</button>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, computed, watch } from 'vue'

export default {
  props: {
    show: {
      type: Boolean,
      required: true
    },
    warehouseLocations: {
      type: Array,
      default: () => []
    }
  },
  emits: ['close', 'select-location', 'sort-by-code', 'sort-by-name'],
  setup(props, { emit }) {
    const selectedLocation = ref(null);
    const loading = ref(false);
    const error = ref(null);

    const filteredLocations = computed(() => {
      return props.warehouseLocations;
    });

    const selectLocation = (location) => {
      selectedLocation.value = location;
    };

    const handleSelect = () => {
      if (selectedLocation.value) {
        emit('select-location', selectedLocation.value);
        emit('close');
      } else {
        error.value = 'Please select a warehouse location';
      }
    };

    // Reset selected location when modal is closed
    watch(() => props.show, (newValue) => {
      if (!newValue) {
        selectedLocation.value = null;
      }
    });

    return {
      filteredLocations,
      selectedLocation,
      loading,
      error,
      selectLocation,
      handleSelect
    };
  }
}
</script> 