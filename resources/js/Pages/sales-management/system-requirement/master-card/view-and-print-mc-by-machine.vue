<template>
  <AppLayout :header="'View & Print MC by Machine'">
    <Head title="View & Print MC by Machine" />

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
                    <i class="fas fa-cogs text-white text-2xl z-10 animate-pulse"></i>
                  </div>
                  <div>
                    <h1 class="text-2xl md:text-3xl font-bold text-white text-shadow">View & Print MC by Machine</h1>
                    <p class="text-blue-100 max-w-2xl">Search and print master cards filtered by machine</p>
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
                <!-- Machine Code -->
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                    <div class="mr-2 w-7 h-7 bg-gradient-to-r from-purple-500 to-indigo-600 rounded-full shadow-md flex items-center justify-center">
                      <i class="fas fa-industry text-white text-sm animate-pulse-light"></i>
                    </div>
                    Machine Code:
                  </label>
                  <div class="flex ml-9">
                    <input
                      v-model="searchParams.machineCode"
                      type="text"
                      placeholder="Enter machine code"
                      class="rounded-l-md w-full px-3 py-2 border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 text-sm"
                    />
                    <button 
                      @click="openMachineLookup" 
                      type="button"
                      class="inline-flex items-center px-2 py-2 border border-l-0 border-gray-300 bg-gradient-to-r from-indigo-500 to-purple-600 text-white rounded-r-md hover:from-indigo-600 hover:to-purple-700"
                    >
                      <i class="fas fa-search text-sm"></i>
                    </button>
                  </div>
                </div>
                
                <!-- ACN Range -->
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                    <div class="mr-2 w-7 h-7 bg-gradient-to-r from-purple-500 to-indigo-600 rounded-full shadow-md flex items-center justify-center">
                      <i class="fas fa-id-card text-white text-sm animate-pulse-light"></i>
                    </div>
                    ACN:
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
                        @click="openCustomerAccountLookup('from')" 
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
                          @click="openCustomerAccountLookup('to')" 
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
                    MCS#:
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
                        @click="openMcsLookup('from')" 
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
                          @click="openMcsLookup('to')" 
                          type="button"
                          class="inline-flex items-center px-2 py-2 border border-l-0 border-gray-300 bg-gradient-to-r from-indigo-500 to-purple-600 text-white rounded-r-md hover:from-indigo-600 hover:to-purple-700"
                        >
                          <i class="fas fa-search text-sm"></i>
                        </button>
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
              </div>

              <!-- Action buttons -->
              <div class="mt-8 flex flex-col sm:flex-row justify-center">
                <button
                  type="button"
                  @click="proceedSearch"
                  class="px-6 py-3 bg-gradient-to-r from-green-600 to-teal-600 hover:from-green-700 hover:to-teal-700 text-white font-medium rounded-md shadow-md transition-all duration-300 transform hover:scale-105 flex items-center justify-center"
                >
                  <i class="fas fa-check-circle mr-2"></i>
                  <span>Proceed</span>
                </button>
              </div>
            </form>
          </div>
        </div>

        <!-- Info Panel -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
          <div class="bg-gradient-to-r from-blue-500 via-blue-600 to-indigo-500 px-4 py-3">
            <h2 class="text-lg font-semibold text-white flex items-center">
              <i class="fas fa-info-circle mr-2"></i>
              Instructions
            </h2>
          </div>
          <div class="p-6 bg-gradient-to-r from-blue-50 to-indigo-50">
            <div class="flex items-start">
              <div class="flex-shrink-0 pt-0.5">
                <div class="bg-gradient-to-br from-yellow-400 to-amber-500 text-white p-2 rounded-lg shadow-sm">
                  <i class="fas fa-lightbulb"></i>
                </div>
              </div>
              <div class="ml-4">
                <h3 class="font-semibold text-indigo-800 mb-2 bg-gradient-to-r from-purple-600 to-indigo-600 text-transparent bg-clip-text">Using this form</h3>
                <div class="text-sm text-gray-600">
                  <p class="mb-2">Use this form to search and print master cards by machine:</p>
                  <ul class="list-disc pl-5 space-y-1">
                    <li>Enter a specific Machine Code, or click the search icon to browse available machines</li>
                    <li>Optionally filter by ACN or MCS# ranges to narrow your search</li>
                    <li>Select active, obsolete, or both status types</li>
                    <li>Specify SO period if needed for further filtering</li>
                    <li>Click "Proceed" to view and print the results</li>
                  </ul>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <MachineModal
      :show="showMachineModal"
      @close="showMachineModal = false"
      @select="handleSelectedMachine"
    />
    <CustomerAccountModal
      :show="showCustomerAccountModal"
      @close="showCustomerAccountModal = false"
      @select="handleSelectedCustomerAccount"
    />
    <MasterCardOptionsModal
      :show="showMcsOptionsModal"
      @close="showMcsOptionsModal = false"
      @confirm="handleMcsOptionsConfirm"
    />
    <MasterCardSearchSelectModal
      :show="showMcsSearchModal"
      @close="showMcsSearchModal = false"
      @select="handleSelectedMc"
    />
  </AppLayout>
</template>

<script setup>
import { ref } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import MachineModal from '@/Components/MachineModal.vue';
import CustomerAccountModal from '@/Components/customer-account-modal.vue';
import MasterCardOptionsModal from '@/Components/MasterCardOptionsModal.vue';
import MasterCardSearchSelectModal from '@/Components/MasterCardSearchSelectModal.vue';

// Search parameters
const searchParams = ref({
  machineCode: '',
  acnFrom: '',
  acnTo: '',
  mcsFrom: '',
  mcsTo: '',
  active: true,
  obsolete: true,
  soPeriodFromYear: '',
  soPeriodFromMonth: '',
  soPeriodToYear: '',
  soPeriodToMonth: ''
});

// UI States for Modals
const showMachineModal = ref(false);
const showCustomerAccountModal = ref(false);
const selectedCustomerAccountTargetField = ref(null);
const showMcsOptionsModal = ref(false);
const showMcsSearchModal = ref(false);
const selectedMcsTargetField = ref(null);

// Search functionality
const searchRecords = () => {
  console.log('Searching with parameters:', searchParams.value);
  // Implement actual search logic here
};

// Action functions
const openMachineLookup = () => {
  showMachineModal.value = true;
};

const handleSelectedMachine = (machine) => {
  searchParams.value.machineCode = machine.code;
  showMachineModal.value = false;
};

const openCustomerAccountLookup = (targetField) => {
  selectedCustomerAccountTargetField.value = targetField;
  showCustomerAccountModal.value = true;
};

const handleSelectedCustomerAccount = (customer) => {
  if (selectedCustomerAccountTargetField.value === 'from') {
    searchParams.value.acnFrom = customer.customer_code;
  } else if (selectedCustomerAccountTargetField.value === 'to') {
    searchParams.value.acnTo = customer.customer_code;
  }
  showCustomerAccountModal.value = false;
};

const openMcsLookup = (targetField) => {
  selectedMcsTargetField.value = targetField;
  showMcsOptionsModal.value = true;
};

const handleMcsOptionsConfirm = (options) => {
  console.log('Master Card options confirmed:', options);
  showMcsOptionsModal.value = false;
  showMcsSearchModal.value = true;
};

const handleSelectedMc = (mc) => {
  if (selectedMcsTargetField.value === 'from') {
    searchParams.value.mcsFrom = mc.mc_seq;
  } else if (selectedMcsTargetField.value === 'to') {
    searchParams.value.mcsTo = mc.mc_seq;
  }
  showMcsSearchModal.value = false;
};

const proceedSearch = () => {
  console.log('Proceeding with search:', searchParams.value);
  // Implement actual proceed logic here
};
</script>

<style scoped>
/* Custom animation for subtle pulse effect on icons */
@keyframes pulse-light {
  0% { opacity: 1; }
  50% { opacity: 0.7; }
  100% { opacity: 1; }
}

.animate-pulse-light {
  animation: pulse-light 2s infinite ease-in-out;
}

/* Custom animation for ping effect */
@keyframes ping-slow {
  0% { transform: scale(1); opacity: 1; }
  50% { transform: scale(1.5); opacity: 0.5; }
  100% { transform: scale(1); opacity: 1; }
}

.animate-ping-slow {
  animation: ping-slow 3s infinite cubic-bezier(0, 0, 0.2, 1);
}

/* Animation delay utility */
.animation-delay-500 {
  animation-delay: 500ms;
}

/* Custom animation for icon rotation */
@keyframes spin-slow {
  from { transform: rotate(0deg); }
  to { transform: rotate(360deg); }
}

.spin-slow {
  animation: spin-slow 10s linear infinite;
}

/* Text shadow for better contrast */
.text-shadow {
  text-shadow: 0px 2px 4px rgba(0, 0, 0, 0.3);
}

/* Button hover transition */
button {
  transition: all 0.3s ease;
}
</style>
