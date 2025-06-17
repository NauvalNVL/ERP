<template>
  <AppLayout :header="'View & Print MC/DC Block Matching'">
    <Head title="View & Print MC/DC Block Matching" />

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
                    <i class="fas fa-project-diagram text-white text-2xl z-10 animate-pulse"></i>
                  </div>
                  <div>
                    <h1 class="text-2xl md:text-3xl font-bold text-white text-shadow">MC DC Block Matching</h1>
                    <p class="text-blue-100 max-w-2xl">View and print master card diecut block matching</p>
                  </div>
                </div>
                <div class="flex flex-wrap gap-3">
                  <button @click="proceedSearch" class="bg-gradient-to-r from-green-500 to-teal-400 text-white hover:from-green-600 hover:to-teal-500 px-4 py-2 rounded-lg flex items-center transition-all duration-300 transform hover:scale-105 shadow-md relative overflow-hidden">
                    <span class="absolute inset-0 bg-white opacity-20 transform scale-x-0 origin-left transition-transform group-hover:scale-x-100"></span>
                    <div class="relative z-10 flex items-center">
                      <div class="bg-white bg-opacity-30 rounded-full p-1 mr-2">
                        <i class="fas fa-search text-white"></i>
                      </div>
                      <span>Proceed</span>
                    </div>
                  </button>
                  <Link href="/sales-management/system-requirement/master-card/view-and-print-MC" class="bg-white bg-opacity-20 text-white border border-white border-opacity-30 hover:bg-opacity-30 px-4 py-2 rounded-lg flex items-center transition-all duration-300 relative overflow-hidden">
                    <span class="absolute inset-0 bg-white opacity-10 transform scale-x-0 origin-left transition-transform hover:scale-x-100"></span>
                    <div class="relative z-10 flex items-center">
                      <div class="bg-white bg-opacity-30 rounded-full p-1.5 mr-2 transition-transform transform group-hover:rotate-12 shadow-inner">
                        <i class="fas fa-arrow-left text-white"></i>
                      </div>
                      <span>Back</span>
                    </div>
                  </Link>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Search Form Card -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-6 border border-gray-100">
          <div class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white py-3 px-6">
            <div class="flex items-center">
              <div class="mr-3 bg-white bg-opacity-20 p-2 rounded-full shadow-inner relative overflow-hidden">
                <div class="absolute -top-1 -right-1 w-4 h-4 bg-blue-300 opacity-30 rounded-full"></div>
                <div class="absolute -bottom-1 -left-1 w-3 h-3 bg-yellow-300 opacity-30 rounded-full"></div>
                <i class="fas fa-filter text-xl relative z-10"></i>
              </div>
              <div>
                <h2 class="text-xl font-bold">Search Parameters</h2>
                <p class="text-xs text-blue-100 opacity-80">Filter master cards by various criteria</p>
              </div>
            </div>
          </div>
          <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <!-- ACN Fields -->
              <div>
                <div class="flex items-center mb-4">
                  <label class="block text-sm font-medium text-gray-700 mb-1 flex items-center">
                    <div class="mr-2 w-7 h-7 bg-gradient-to-r from-purple-500 to-indigo-600 rounded-full shadow-md flex items-center justify-center">
                      <i class="fas fa-id-card-alt text-white text-sm animate-pulse-light"></i>
                    </div>
                    ACN:
                  </label>
                </div>
                <div class="flex space-x-4">
                  <div class="relative flex-grow">
                    <input
                      type="text"
                      v-model="searchParams.acnFrom"
                      class="rounded-l-md flex-1 min-w-0 block w-full px-3 py-2 border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                    />
                    <button @click="showAcnSearch" class="absolute inset-y-0 right-0 inline-flex items-center px-3 py-2 border border-l-0 border-gray-300 bg-gradient-to-r from-indigo-500 to-purple-600 text-white rounded-r-md hover:from-indigo-600 hover:to-purple-700 transition-colors">
                      <i class="fas fa-search text-white text-sm"></i>
                    </button>
                  </div>
                  <span class="flex items-center text-sm text-gray-600">to</span>
                  <div class="relative flex-grow">
                    <input
                      type="text"
                      v-model="searchParams.acnTo"
                      class="rounded-l-md flex-1 min-w-0 block w-full px-3 py-2 border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                    />
                    <button @click="showAcnSearch" class="absolute inset-y-0 right-0 inline-flex items-center px-3 py-2 border border-l-0 border-gray-300 bg-gradient-to-r from-indigo-500 to-purple-600 text-white rounded-r-md hover:from-indigo-600 hover:to-purple-700 transition-colors">
                      <i class="fas fa-search text-white text-sm"></i>
                    </button>
                  </div>
                </div>
              </div>
            </div>
            
            <!-- Print Option -->
            <div class="mt-6">
              <label class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                <div class="mr-2 w-7 h-7 bg-gradient-to-r from-purple-500 to-indigo-600 rounded-full shadow-md flex items-center justify-center">
                  <i class="fas fa-print text-white text-sm animate-pulse-light"></i>
                </div>
                Print Option:
              </label>
              <div class="flex flex-wrap gap-4 ml-9">
                <div class="flex items-center">
                  <input
                    id="print-option-1"
                    type="radio"
                    v-model="searchParams.printOption"
                    value="1"
                    class="h-4 w-4 text-indigo-600 focus:ring-indigo-500"
                    checked
                  />
                  <label for="print-option-1" class="ml-2 text-sm text-gray-700">1-With Same D/Cut Block#</label>
                </div>
                <div class="flex items-center">
                  <input
                    id="print-option-2"
                    type="radio"
                    v-model="searchParams.printOption"
                    value="2"
                    class="h-4 w-4 text-indigo-600 focus:ring-indigo-500"
                  />
                  <label for="print-option-2" class="ml-2 text-sm text-gray-700">2-All</label>
                </div>
              </div>
            </div>

            <!-- Share Status -->
            <div class="mt-6">
              <label class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                <div class="mr-2 w-7 h-7 bg-gradient-to-r from-purple-500 to-indigo-600 rounded-full shadow-md flex items-center justify-center">
                  <i class="fas fa-share-alt text-white text-sm animate-pulse-light"></i>
                </div>
                Share Status:
              </label>
              <div class="flex flex-wrap gap-x-6 gap-y-2 ml-9">
                <div class="flex items-center">
                  <input
                    id="status-shared"
                    type="checkbox"
                    v-model="searchParams.statusShared"
                    class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
                    checked
                  />
                  <label for="status-shared" class="ml-2 text-sm text-gray-700">1-Shared</label>
                </div>
                <div class="flex items-center">
                  <input
                    id="status-not-shared"
                    type="checkbox"
                    v-model="searchParams.statusNotShared"
                    class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
                    checked
                  />
                  <label for="status-not-shared" class="ml-2 text-sm text-gray-700">2-Not Shared</label>
                </div>
                <div class="flex items-center">
                  <input
                    id="status-error"
                    type="checkbox"
                    v-model="searchParams.statusError"
                    class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
                    checked
                  />
                  <label for="status-error" class="ml-2 text-sm text-gray-700">3-Error</label>
                </div>
                <div class="flex items-center">
                  <input
                    id="status-no-dcut"
                    type="checkbox"
                    v-model="searchParams.statusNoDCut"
                    class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
                    checked
                  />
                  <label for="status-no-dcut" class="ml-2 text-sm text-gray-700">4-No D/Cut</label>
                </div>
              </div>
              <div class="ml-9 mt-3 text-sm text-gray-600">
                For Print Option '2' only
              </div>
            </div>
          </div>
        </div>

        <!-- Results preview will appear here when search is performed -->
        <div v-if="loading" class="flex flex-col items-center justify-center my-12">
          <div class="relative">
            <div class="absolute inset-0 bg-gradient-to-r from-indigo-500 to-purple-500 rounded-full opacity-20 animate-ping"></div>
            <div class="animate-spin rounded-full h-16 w-16 border-t-2 border-b-2 border-indigo-500"></div>
          </div>
          <p class="mt-4 text-indigo-600 font-medium">Loading results...</p>
        </div>

        <!-- Help Section -->
        <div class="mt-6 bg-gradient-to-r from-purple-50 to-pink-50 p-4 rounded-lg border border-purple-100 shadow-sm">
          <h3 class="font-semibold text-indigo-800 mb-2 flex items-center">
            <div class="bg-gradient-to-br from-purple-400 to-indigo-500 text-white p-1.5 rounded-lg shadow-sm mr-2">
              <i class="fas fa-lightbulb"></i>
            </div>
            <span class="bg-gradient-to-r from-purple-600 to-indigo-600 text-transparent bg-clip-text">Instructions</span>
          </h3>
          <ul class="list-disc pl-5 text-sm text-gray-600 space-y-1">
            <li>Enter an ACN range to filter master cards</li>
            <li>Select the Print Option to filter by matching or show all</li>
            <li>Check the desired Share Status options</li>
            <li>Click "Proceed" to generate the matching report</li>
            <li>Note: Share Status options are only applicable when using Print Option 2 (All)</li>
          </ul>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

// Search parameters
const searchParams = ref({
  acnFrom: '',
  acnTo: '',
  printOption: '1',
  statusShared: true,
  statusNotShared: true,
  statusError: true,
  statusNoDCut: true
});

// UI States
const loading = ref(false);

// Function for search modal popup
const showAcnSearch = () => {
  // Implement customer search modal
  console.log('Open ACN search modal');
};

// Search function
const proceedSearch = () => {
  // Show loading spinner
  loading.value = true;
  
  // Implement search functionality
  console.log('Processing with parameters:', searchParams.value);
  
  // Simulate API call delay
  setTimeout(() => {
    loading.value = false;
    // In real implementation, this would redirect to a report view or generate
    // the report directly
  }, 1000);
};
</script>

<style scoped>
.text-shadow {
  text-shadow: 0 1px 3px rgba(0,0,0,0.2);
}

/* Animation classes */
@keyframes ping-slow {
  0% {
    transform: scale(1);
    opacity: 0.3;
  }
  50% {
    transform: scale(1.1);
    opacity: 0.2;
  }
  100% {
    transform: scale(1);
    opacity: 0.3;
  }
}

.animate-ping-slow {
  animation: ping-slow 3s infinite ease-in-out;
}

.animation-delay-500 {
  animation-delay: 0.5s;
}

.pulse-light {
  animation: pulse 2s infinite ease-in-out;
}

@keyframes icon-pulse {
  0% { transform: scale(1); }
  50% { transform: scale(1.15); }
  100% { transform: scale(1); }
}

/* Print-specific styles */
@media print {
  .no-print {
    display: none !important;
  }
  
  body {
    font-size: 12pt;
    color: #000;
    background-color: #fff;
  }
}
</style>
