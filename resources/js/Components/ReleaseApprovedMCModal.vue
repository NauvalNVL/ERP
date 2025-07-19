<template>
  <transition name="fade">
    <div v-if="show" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-30 py-8">
      <div class="bg-white rounded-lg shadow-2xl w-full max-w-2xl mx-auto relative animate-fade-in-up max-h-[90vh] flex flex-col">
        <!-- Modal Header -->
        <div class="bg-gradient-to-r from-teal-500 to-green-600 p-4 rounded-t-lg shadow-md flex items-center justify-between flex-shrink-0">
          <div class="flex items-center gap-2">
            <i class="fas fa-hand-holding-usd text-white text-2xl"></i>
            <h2 class="text-xl font-bold text-white">Release Approved MC</h2>
          </div>
          <button @click="$emit('close')" class="text-white hover:text-gray-100 text-2xl transition-transform transform hover:rotate-90">&times;</button>
        </div>

        <!-- Modal Body -->
        <div class="p-6 flex-grow overflow-y-auto">
          <!-- Customer Info -->
          <div class="bg-blue-50 p-4 rounded-lg shadow-inner mb-4 border border-blue-100">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 items-center">
              <div class="flex items-center">
                <i class="fas fa-user-circle text-blue-600 mr-2"></i>
                <span class="font-semibold text-gray-700">Customer:</span>
                <input type="text" :value="customerCode" readonly class="ml-2 px-2 py-1 border border-blue-200 rounded-md bg-blue-100 text-blue-800 font-medium w-32" />
                <input type="text" :value="customerName" readonly class="ml-2 px-2 py-1 border border-blue-200 rounded-md bg-blue-100 text-blue-800 font-medium flex-grow" />
              </div>
            </div>
          </div>

          <!-- Table Section -->
          <div class="overflow-x-auto border border-gray-200 rounded-lg shadow-lg mb-4 max-h-80 custom-scrollbar">
            <table class="min-w-full divide-y divide-gray-200">
              <thead class="bg-gradient-to-r from-blue-100 to-indigo-100">
                <tr>
                  <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                    <div class="flex items-center"><i class="fas fa-hashtag mr-2 text-indigo-500"></i>MCS#</div>
                  </th>
                  <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                    <div class="flex items-center"><i class="fas fa-box-open mr-2 text-indigo-500"></i>MODEL</div>
                  </th>
                  <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                    <div class="flex items-center"><i class="fas fa-info-circle mr-2 text-indigo-500"></i>STS</div>
                  </th>
                  <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                    <div class="flex items-center"><i class="fas fa-check-double mr-2 text-indigo-500"></i>RELEASE</div>
                  </th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                <tr v-if="filteredMasterCards.length === 0">
                  <td colspan="4" class="px-6 py-10 text-center text-gray-500">
                    <div class="flex flex-col items-center justify-center">
                        <i class="fas fa-clipboard-list text-5xl text-gray-300 mb-3"></i>
                        <p class="text-lg font-semibold">No matching master cards found.</p> 
                        <p class="text-sm mt-1">Please ensure the MCS range and filters are correct.</p>
                    </div>
                  </td>
                </tr>
                <tr v-for="mc in filteredMasterCards" :key="mc.id">
                  <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ mc.mc_seq }}</td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ mc.mc_model }}</td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ mc.status }}</td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ mc.released_date ? 'Yes' : 'No' }}</td>
                </tr>
              </tbody>
            </table>
          </div>

          <!-- Restructured Search & Logs Section -->
          <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
            <!-- Search Section (takes one column) -->
            <div class="bg-gradient-to-br from-gray-50 to-gray-100 p-4 rounded-lg shadow-inner border border-gray-200">
              <label for="search" class="block text-sm font-medium text-gray-700 flex items-center mb-2">
                <i class="fas fa-search text-gray-500 mr-2"></i>Search:
              </label>
              <div class="flex w-full">
                <input type="text" id="search" v-model="searchQuery" placeholder="Search MCS#" class="flex-grow px-3 py-2 border border-gray-300 rounded-l-md shadow-sm focus:ring-blue-500 focus:border-blue-500 min-w-0" />
                <button @click="performSearch" class="px-4 py-2 bg-blue-600 text-white font-medium rounded-r-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 shadow-sm flex-shrink-0">
                  Search
                </button>
              </div>
            </div>

            <!-- Logs Section (takes the other column, contains two stacked log entries) -->
            <div class="grid grid-cols-1 gap-4">
            <!-- Last Maintenance Log -->
            <div class="bg-gradient-to-br from-yellow-50 to-orange-50 p-4 rounded-lg shadow-inner border border-yellow-100">
              <h3 class="text-lg font-semibold text-yellow-800 mb-3 flex items-center">
                <i class="fas fa-history text-yellow-600 mr-2"></i>Last Maintenance Log
              </h3>
              <div class="space-y-2">
                <div class="flex items-center">
                  <label class="w-24 text-sm font-medium text-gray-700">User ID:</label>
                  <input type="text" value="mc02" readonly class="flex-grow px-2 py-1 border border-yellow-200 rounded-md bg-yellow-100 text-yellow-800" />
                </div>
                <div class="flex items-center">
                  <label class="w-24 text-sm font-medium text-gray-700">Date & Time:</label>
                  <input type="text" value="21/03/2020 08:30" readonly class="flex-grow px-2 py-1 border border-yellow-200 rounded-md bg-yellow-100 text-yellow-800" />
                </div>
                <div class="flex items-center">
                  <label class="w-24 text-sm font-medium text-gray-700">Process:</label>
                  <input type="text" value="Amendment" readonly class="flex-grow px-2 py-1 border border-yellow-200 rounded-md bg-yellow-100 text-yellow-800" />
                </div>
              </div>
            </div>
            <!-- Last Approval Log -->
            <div class="bg-gradient-to-br from-purple-50 to-indigo-50 p-4 rounded-lg shadow-inner border border-purple-100">
              <h3 class="text-lg font-semibold text-purple-800 mb-3 flex items-center">
                <i class="fas fa-clipboard-check text-purple-600 mr-2"></i>Last Approval Log
              </h3>
              <div class="space-y-2">
                <div class="flex items-center">
                  <label class="w-24 text-sm font-medium text-gray-700">User ID:</label>
                  <input type="text" value="mc02" readonly class="flex-grow px-2 py-1 border border-purple-200 rounded-md bg-purple-100 text-purple-800" />
                </div>
                <div class="flex items-center">
                  <label class="w-24 text-sm font-medium text-gray-700">Date & Time:</label>
                  <input type="text" value="21/03/2020 08:30" readonly class="flex-grow px-2 py-1 border border-purple-200 rounded-md bg-purple-100 text-purple-800" />
                </div>
                <div class="flex items-center">
                  <label class="w-24 text-sm font-medium text-gray-700">Active W/O:</label>
                  <input type="text" value="34" readonly class="flex-grow px-2 py-1 border border-purple-200 rounded-md bg-purple-100 text-purple-800" />
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Modal Footer -->
        <div class="bg-gray-100 px-4 py-3 sm:px-6 flex justify-end rounded-b-lg flex-shrink-0">
          <button @click="$emit('close')" class="inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-gradient-to-r from-red-500 to-pink-500 text-base font-medium text-white hover:from-red-600 hover:to-pink-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm items-center">
            <i class="fas fa-times mr-2"></i>
            Close
          </button>
        </div>
      </div>
    </div>
  </transition>
</template>

<script setup>
import { defineProps, defineEmits, computed, ref } from 'vue';

const props = defineProps({
  show: Boolean,
  customerCode: String,
  customerName: String,
  mcsFrom: String,
  mcsTo: String,
  masterCards: {
    type: Array,
    default: () => [],
  },
});

const emit = defineEmits(['close']);

const searchQuery = ref('');

// Dummy master card data for demonstration, filtered by the MCS range
// In a real application, this data would be fetched from an API based on customerCode, mcsFrom, mcsTo
const dummyMasterCards = computed(() => {
  // Filter masterCards based on mcsFrom and mcsTo props
  // For demonstration, we'll use a simple filter.
  // In a real app, you might need to convert MCS# to numbers for proper range comparison.
  return props.masterCards.filter(mc => {
    const mcSeq = parseInt(mc.mc_seq);
    const from = parseInt(props.mcsFrom);
    const to = parseInt(props.mcsTo);

    return mcSeq >= from && mcSeq <= to;
  });
});

const filteredMasterCards = computed(() => {
  if (!searchQuery.value) {
    return dummyMasterCards.value;
  }
  const searchLower = searchQuery.value.toLowerCase();
  return dummyMasterCards.value.filter(mc =>
    mc.mc_seq.toLowerCase().includes(searchLower) ||
    mc.mc_model.toLowerCase().includes(searchLower)
  );
});

const performSearch = () => {
  // Filtering is already handled by filteredMasterCards computed property
  console.log('Performing search for:', searchQuery.value);
};
</script>

<style scoped>
/* Add common styles or override if necessary */
.animate-fade-in-up {
  animation: fade-in-up 0.5s ease-out forwards;
}

@keyframes fade-in-up {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.fade-enter-active, .fade-leave-active {
  transition: opacity 0.3s ease;
}
.fade-enter-from, .fade-leave-to {
  opacity: 0;
}

/* Custom Scrollbar for the modal's table */
.custom-scrollbar {
    scrollbar-width: thin; /* For Firefox */
    scrollbar-color: #a78bfa #e0e7ff; /* thumb and track color for Firefox */
}

.custom-scrollbar::-webkit-scrollbar {
    width: 8px; /* width of the scrollbar */
}

.custom-scrollbar::-webkit-scrollbar-track {
    background: #e0e7ff; /* color of the track */
    border-radius: 10px;
}

.custom-scrollbar::-webkit-scrollbar-thumb {
    background-color: #a78bfa; /* color of the scroll thumb */
    border-radius: 10px; /* roundness of the scroll thumb */
    border: 2px solid #e0e7ff; /* creates padding around scroll thumb */
}
</style> 