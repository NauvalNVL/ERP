<template>
  <AppLayout :header="'View & Print MC by Color'">
    <Head title="View & Print MC by Color" />

    <!-- Main Container with vibrant gradient background -->
    <div class="min-h-screen bg-gradient-to-br from-indigo-50 via-purple-50 to-pink-50 p-4 md:p-6">
      <div class="max-w-7xl mx-auto">
        <!-- Header Section with animated elements -->
        <div class="bg-gradient-to-r from-purple-600 via-indigo-600 to-blue-600 rounded-xl shadow-lg overflow-hidden mb-6 transform transition-all duration-500 hover:shadow-xl">
          <div class="relative overflow-hidden">
            <!-- Decorative Elements -->
            <div class="absolute top-0 right-0 w-40 h-40 bg-white opacity-5 rounded-full -translate-y-20 translate-x-20"></div>
            <div class="absolute bottom-0 left-0 w-20 h-20 bg-white opacity-5 rounded-full translate-y-10 -translate-x-10"></div>
            <div class="absolute bottom-0 right-0 w-32 h-32 bg-yellow-400 opacity-5 rounded-full translate-y-10 translate-x-10"></div>

            <div class="p-6 md:p-8 relative z-10">
              <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                <div class="flex items-start md:items-center space-x-4 mb-4 md:mb-0">
                  <div class="bg-gradient-to-br from-pink-500 to-purple-600 p-3 rounded-lg shadow-inner flex items-center justify-center relative overflow-hidden">
                    <div class="absolute -top-1 -right-1 w-6 h-6 bg-yellow-300 opacity-30 rounded-full animate-ping-slow"></div>
                    <div class="absolute -bottom-1 -left-1 w-4 h-4 bg-blue-300 opacity-30 rounded-full animate-ping-slow animation-delay-500"></div>
                    <i class="fas fa-palette text-white text-2xl z-10 animate-pulse"></i>
                  </div>
                  <div>
                    <h1 class="text-2xl md:text-3xl font-bold text-white text-shadow">View & Print MC by Color</h1>
                    <p class="text-blue-100 max-w-2xl">Search and print master cards filtered by color</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Search Form Panel -->
        <div class="bg-white rounded-xl shadow-lg mb-6 overflow-hidden transition-all duration-500 transform hover:shadow-xl">
          <!-- Panel Header with Gradient -->
          <div class="bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 px-6 py-4">
            <h2 class="text-lg font-semibold text-white flex items-center">
              <i class="fas fa-search mr-3 animate-pulse-light"></i>
              Search Criteria
            </h2>
          </div>

          <!-- Panel Body with improved layout -->
          <div class="p-6 md:p-8">
            <form @submit.prevent="searchRecords">
              <!-- Grid layout for search parameters -->
              <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- ACN Range -->
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                    <div class="mr-2 w-7 h-7 bg-gradient-to-r from-purple-500 to-indigo-600 rounded-full shadow-md flex items-center justify-center">
                      <i class="fas fa-id-card text-white text-sm animate-pulse-light"></i>
                    </div>
                    ACN Range:
                  </label>
                  <div class="flex flex-wrap space-y-2 md:space-y-0 md:space-x-2 ml-9">
                    <div class="flex w-full md:w-auto">
                      <input
                        v-model="searchParams.acnFrom"
                        type="text"
                        placeholder="From"
                        class="rounded-l-md w-full px-3 py-2 border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 text-sm"
                      />
                      <button 
                        @click="showCustomerAccountModal = true" 
                        type="button"
                        class="inline-flex items-center px-2 py-2 border border-l-0 border-gray-300 bg-gradient-to-r from-indigo-500 to-purple-600 text-white rounded-r-md hover:from-indigo-600 hover:to-purple-700"
                      >
                        <i class="fas fa-search text-sm"></i>
                      </button>
                    </div>
                    <div class="flex items-center w-full md:w-auto">
                      <div class="flex-shrink-0 w-8 text-center text-gray-500 hidden md:block">to</div>
                      <div class="flex w-full md:w-auto">
                        <input
                          v-model="searchParams.acnTo"
                          type="text"
                          placeholder="To"
                          class="rounded-l-md w-full px-3 py-2 border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 text-sm"
                        />
                        <button 
                          @click="showCustomerAccountModal = true" 
                          type="button"
                          class="inline-flex items-center px-2 py-2 border border-l-0 border-gray-300 bg-gradient-to-r from-indigo-500 to-purple-600 text-white rounded-r-md hover:from-indigo-600 hover:to-purple-700"
                        >
                          <i class="fas fa-search text-sm"></i>
                        </button>
                      </div>
                    </div>
                  </div>
                </div>
                
                <!-- MCS Range -->
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                    <div class="mr-2 w-7 h-7 bg-gradient-to-r from-purple-500 to-indigo-600 rounded-full shadow-md flex items-center justify-center">
                      <i class="fas fa-hashtag text-white text-sm animate-pulse-light"></i>
                    </div>
                    MCS# Range:
                  </label>
                  <div class="flex flex-wrap space-y-2 md:space-y-0 md:space-x-2 ml-9">
                    <div class="flex w-full md:w-auto">
                      <input
                        v-model="searchParams.mcsFrom"
                        type="text"
                        placeholder="From"
                        class="rounded-l-md w-full px-3 py-2 border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 text-sm"
                      />
                      <button 
                        @click="showMcsSearch('mcsFrom')" 
                        type="button"
                        class="inline-flex items-center px-2 py-2 border border-l-0 border-gray-300 bg-gradient-to-r from-indigo-500 to-purple-600 text-white rounded-r-md hover:from-indigo-600 hover:to-purple-700"
                      >
                        <i class="fas fa-search text-sm"></i>
                      </button>
                    </div>
                    <div class="flex items-center w-full md:w-auto">
                      <div class="flex-shrink-0 w-8 text-center text-gray-500 hidden md:block">to</div>
                      <div class="flex w-full md:w-auto">
                        <input
                          v-model="searchParams.mcsTo"
                          type="text"
                          placeholder="To"
                          class="rounded-l-md w-full px-3 py-2 border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 text-sm"
                        />
                        <button 
                          @click="showMcsSearch('mcsTo')" 
                          type="button"
                          class="inline-flex items-center px-2 py-2 border border-l-0 border-gray-300 bg-gradient-to-r from-indigo-500 to-purple-600 text-white rounded-r-md hover:from-indigo-600 hover:to-purple-700"
                        >
                          <i class="fas fa-search text-sm"></i>
                        </button>
                      </div>
                    </div>
                  </div>
                </div>
                
                <!-- SO Period -->
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                    <div class="mr-2 w-7 h-7 bg-gradient-to-r from-purple-500 to-indigo-600 rounded-full shadow-md flex items-center justify-center">
                      <i class="fas fa-calendar-alt text-white text-sm animate-pulse-light"></i>
                    </div>
                    SO Period:
                  </label>
                  <div class="ml-9 bg-gradient-to-r from-indigo-50 to-purple-50 p-3 rounded-md shadow-sm">
                    <div class="grid grid-cols-2 gap-4">
                      <div>
                        <div class="flex items-center space-x-2">
                          <div>
                            <label class="block text-xs text-gray-600 mb-1">From Year:</label>
                            <input
                              v-model="searchParams.soPeriodFromYear"
                              type="text"
                              maxlength="2"
                              placeholder="YY"
                              class="w-full px-2 py-1 border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 text-sm text-center rounded-md"
                            />
                          </div>
                          <div>
                            <label class="block text-xs text-gray-600 mb-1">Month:</label>
                            <input
                              v-model="searchParams.soPeriodFromMonth"
                              type="text"
                              maxlength="2"
                              placeholder="MM"
                              class="w-full px-2 py-1 border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 text-sm text-center rounded-md"
                            />
                          </div>
                        </div>
                      </div>
                      <div>
                        <div class="flex items-center space-x-2">
                          <div>
                            <label class="block text-xs text-gray-600 mb-1">To Year:</label>
                            <input
                              v-model="searchParams.soPeriodToYear"
                              type="text"
                              maxlength="2"
                              placeholder="YY"
                              class="w-full px-2 py-1 border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 text-sm text-center rounded-md"
                            />
                          </div>
                          <div>
                            <label class="block text-xs text-gray-600 mb-1">Month:</label>
                            <input
                              v-model="searchParams.soPeriodToMonth"
                              type="text"
                              maxlength="2"
                              placeholder="MM"
                              class="w-full px-2 py-1 border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 text-sm text-center rounded-md"
                            />
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                
                <!-- MC Status -->
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                    <div class="mr-2 w-7 h-7 bg-gradient-to-r from-purple-500 to-indigo-600 rounded-full shadow-md flex items-center justify-center">
                      <i class="fas fa-tag text-white text-sm animate-pulse-light"></i>
                    </div>
                    MC Status:
                  </label>
                  <div class="flex flex-wrap gap-4 ml-9">
                    <div class="flex items-center">
                      <input
                        id="status-active"
                        type="checkbox"
                        v-model="searchParams.active"
                        class="h-4 w-4 text-indigo-600 focus:ring-indigo-500"
                      />
                      <label for="status-active" class="ml-2 text-sm text-gray-700">A-Active</label>
                    </div>
                    <div class="flex items-center">
                      <input
                        id="status-obsolete"
                        type="checkbox"
                        v-model="searchParams.obsolete"
                        class="h-4 w-4 text-indigo-600 focus:ring-indigo-500"
                      />
                      <label for="status-obsolete" class="ml-2 text-sm text-gray-700">O-Obsolete</label>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Action buttons -->
              <div class="mt-8 flex flex-col sm:flex-row justify-center gap-4">
                <button
                  type="button"
                  @click="showColorSelector"
                  class="px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-medium rounded-md shadow-md transition-all duration-300 transform hover:scale-105 flex items-center justify-center"
                >
                  <i class="fas fa-palette mr-2"></i>
                  <span>Select Colors</span>
                </button>
              </div>
            </form>
          </div>
        </div>

        <!-- Info Panel -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
          <div class="bg-gradient-to-r from-blue-500 via-blue-600 to-indigo-500 px-4 py-3">
            <h2 class="text-lg font-semibold text-white flex items-center">
              <i class="fas fa-info-circle mr-3"></i>
              Information
            </h2>
          </div>
          <div class="p-6 md:p-8">
            <p class="text-gray-700">Detailed information and results will appear here after search.</p>
            <!-- Placeholder for results table or message -->
          </div>
        </div>
      </div>
    </div>

    <!-- Customer Account Modal -->
    <CustomerAccountModal
        :show="showCustomerAccountModal"
        @close="showCustomerAccountModal = false"
    />

    <!-- Master Card Options Modal -->
    <MasterCardOptionsModal
      :show="showMcsOptionsModal"
      :target-field="selectedMcsTargetField"
      :initial-sort-column="mcsOptions.sortColumn"
      :initial-sort-direction="mcsOptions.sortDirection"
      :initial-filter-status="mcsOptions.filterStatus"
      @close="showMcsOptionsModal = false"
      @confirm="handleMcsOptionsConfirm"
    />

    <!-- Master Card Search and Select Modal -->
    <MasterCardSearchSelectModal
      :show="showMcsSearchModal"
      :target-field="selectedMcsTargetField"
      :initial-sort-column="mcsOptions.sortColumn"
      :initial-sort-direction="mcsOptions.sortDirection"
      :initial-filter-status="mcsOptions.filterStatus"
      @close="showMcsSearchModal = false"
      @select-mc="handleSelectedMc"
      @reopen-options="handleReopenOptions"
      @zoom-mc="handleZoomMc"
    />

    <!-- Master Card Zoom Modal -->
    <MasterCardZoomModal
      :show="showMcsZoomModal"
      :mc-data="zoomMcData"
      @close="showMcsZoomModal = false"
    />
  </AppLayout>
</template>

<script setup>
import { Head } from '@inertiajs/vue3';
import { ref, reactive } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import CustomerAccountModal from '@/Components/customer-account-modal.vue';
import MasterCardSearchSelectModal from '@/Components/MasterCardSearchSelectModal.vue'; 
import MasterCardOptionsModal from '@/Components/MasterCardOptionsModal.vue'; 
import MasterCardZoomModal from '@/Components/MasterCardZoomModal.vue';

const searchParams = reactive({
  acnFrom: '',
  acnTo: '',
  mcsFrom: '',
  mcsTo: '',
  soPeriodFromYear: '',
  soPeriodFromMonth: '',
  soPeriodToYear: '',
  soPeriodToMonth: '',
  active: true,
  obsolete: false,
});

const showCustomerAccountModal = ref(false);
const showMcsSearchModal = ref(false); 
const showMcsOptionsModal = ref(false); 
const showMcsZoomModal = ref(false); // New reactive state for zoom modal
const selectedMcsTargetField = ref(''); 
const mcsOptions = reactive({
  sortColumn: 'mc_seq',
  sortDirection: 'asc',
  filterStatus: { active: true, obsolete: false, pending: false },
});
const zoomMcData = ref(null); // New reactive state to hold data for zoom modal

const searchRecords = () => {
  console.log('Searching with params:', searchParams);
  // Implement your search logic here
};

const showColorSelector = () => {
  // Logic to show color selector
  alert('Color selector will be implemented here.');
};

// Function to open the MC options modal
const showMcsSearch = (targetField) => {
  selectedMcsTargetField.value = targetField;
  showMcsOptionsModal.value = true;
};

// Function to handle confirmed options from the MC options modal
const handleMcsOptionsConfirm = (options) => {
  mcsOptions.sortColumn = options.sortColumn;
  mcsOptions.sortDirection = options.sortDirection;
  mcsOptions.filterStatus = { ...options.filterStatus };
  showMcsOptionsModal.value = false; // Close options modal
  showMcsSearchModal.value = true;    // Open search and select modal
};

// Function to handle selected MC from the search and select modal
const handleSelectedMc = ({ mc, targetField }) => {
  if (targetField === 'mcsFrom') {
    searchParams.mcsFrom = mc.mc_seq;
  } else if (targetField === 'mcsTo') {
    searchParams.mcsTo = mc.mc_seq;
  }
  showMcsSearchModal.value = false; // Close search and select modal after selection
};

// Function to handle zoom MC event from the search and select modal
const handleZoomMc = (mc) => {
  zoomMcData.value = mc; // Set the MC data for the zoom modal
  showMcsZoomModal.value = true; // Show the zoom modal
};

// Function to handle reopening the options modal from the search table modal
const handleReopenOptions = ({ sortColumn, sortDirection, filterStatus, targetField }) => {
  // Update mcsOptions with the current state from the search modal before reopening options modal
  mcsOptions.sortColumn = sortColumn;
  mcsOptions.sortDirection = sortDirection;
  mcsOptions.filterStatus = { ...filterStatus };
  selectedMcsTargetField.value = targetField;

  showMcsSearchModal.value = false; // Close the search table modal
  showMcsOptionsModal.value = true; // Open the options modal
};
</script>

<style scoped>
/* Add any specific styles for this page here */

/* Animations for header elements */
@keyframes ping-slow {
  0%, 100% {
    transform: scale(0.8);
    opacity: 0.3;
  }
  50% {
    transform: scale(1.2);
    opacity: 0.7;
  }
}

@keyframes pulse-light {
  0%, 100% {
    opacity: 1;
  }
  50% {
    opacity: 0.7;
  }
}

.animate-ping-slow {
  animation: ping-slow 3s cubic-bezier(0, 0, 0.2, 1) infinite;
}

.animate-pulse-light {
  animation: pulse-light 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}

.animation-delay-500 {
  animation-delay: 0.5s;
}

.text-shadow {
  text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
}
</style>
