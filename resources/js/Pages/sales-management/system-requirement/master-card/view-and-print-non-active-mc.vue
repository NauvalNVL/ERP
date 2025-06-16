<template>
  <AppLayout title="View & Print Non-Active MC">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        View & Print Non-Active MC
      </h2>
    </template>

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
                  <div class="bg-gradient-to-br from-pink-500 to-purple-600 p-3 rounded-lg shadow-inner flex items-center justify-center">
                    <i class="fas fa-print text-white text-2xl"></i>
                  </div>
                  <div>
                    <h1 class="text-2xl md:text-3xl font-bold text-white text-shadow">View & Print Non-Active MC</h1>
                    <p class="text-blue-100 max-w-2xl">Preview and print master cards that are no longer active</p>
                  </div>
                </div>
                <div class="flex flex-wrap gap-3">
                  <button @click="proceedAction" class="bg-gradient-to-r from-green-500 to-teal-400 text-white hover:from-green-600 hover:to-teal-500 px-4 py-2 rounded-lg flex items-center transition-all duration-300 transform hover:scale-105 shadow-md">
                    <i class="fas fa-check mr-2"></i> Proceed
                  </button>
                  <button @click="$router.go(-1)" class="bg-white bg-opacity-20 text-white border border-white border-opacity-30 hover:bg-opacity-30 px-4 py-2 rounded-lg flex items-center transition-all duration-300">
                    <i class="fas fa-arrow-left mr-2"></i> Back
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Search Form -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-6">
          <div class="p-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
              <!-- Left Column - Filter Fields -->
              <div class="space-y-6">
                <!-- Current Sales Period -->
                <div>
                  <label class="block text-sm font-medium text-indigo-700 mb-2">Current Sales Period:</label>
                  <div class="flex space-x-2">
                    <div class="relative w-20">
                      <input type="text" value="6" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500" readonly />
                    </div>
                    <div class="relative w-24">
                      <input type="text" value="2025" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500" readonly />
                    </div>
                    <div class="flex items-center text-gray-500 text-sm">
                      mm/yyyy
                    </div>
                  </div>
                </div>

                <!-- Salesperson Code -->
                <div>
                  <label class="block text-sm font-medium text-indigo-700 mb-2">Salesperson Code:</label>
                  <div class="flex space-x-2">
                    <div class="relative flex-grow">
                      <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500" />
                      <button class="absolute inset-y-0 right-0 px-3 text-indigo-600 hover:text-indigo-800">
                        <i class="fas fa-search"></i>
                      </button>
                    </div>
                    <div class="flex items-center text-gray-700">
                      to
                    </div>
                    <div class="relative flex-grow">
                      <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500" />
                      <button class="absolute inset-y-0 right-0 px-3 text-indigo-600 hover:text-indigo-800">
                        <i class="fas fa-search"></i>
                      </button>
                    </div>
                  </div>
                </div>

                <!-- AC# -->
                <div>
                  <label class="block text-sm font-medium text-indigo-700 mb-2">AC#:</label>
                  <div class="flex space-x-2">
                    <div class="relative flex-grow">
                      <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500" />
                      <button class="absolute inset-y-0 right-0 px-3 text-indigo-600 hover:text-indigo-800">
                        <i class="fas fa-search"></i>
                      </button>
                    </div>
                    <div class="flex items-center text-gray-700">
                      to
                    </div>
                    <div class="relative flex-grow">
                      <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500" />
                      <button class="absolute inset-y-0 right-0 px-3 text-indigo-600 hover:text-indigo-800">
                        <i class="fas fa-search"></i>
                      </button>
                    </div>
                  </div>
                </div>

                <!-- MCS# -->
                <div>
                  <label class="block text-sm font-medium text-indigo-700 mb-2">MCS#:</label>
                  <div class="flex space-x-2">
                    <div class="relative flex-grow">
                      <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500" />
                      <button class="absolute inset-y-0 right-0 px-3 text-indigo-600 hover:text-indigo-800">
                        <i class="fas fa-search"></i>
                      </button>
                    </div>
                    <div class="flex items-center text-gray-700">
                      to
                    </div>
                    <div class="relative flex-grow">
                      <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500" />
                      <button class="absolute inset-y-0 right-0 px-3 text-indigo-600 hover:text-indigo-800">
                        <i class="fas fa-search"></i>
                      </button>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Right Column - Additional Options -->
              <div class="space-y-6">
                <!-- Master Card Status -->
                <div>
                  <label class="block text-sm font-medium text-indigo-700 mb-2">Master Card Status:</label>
                  <div class="flex space-x-4">
                    <div class="flex items-center">
                      <input type="checkbox" id="active" checked class="form-checkbox h-5 w-5 text-indigo-600 rounded border-gray-300 focus:ring-indigo-500" />
                      <label for="active" class="ml-2 text-gray-700">A-Active</label>
                    </div>
                    <div class="flex items-center">
                      <input type="checkbox" id="obsolete" checked class="form-checkbox h-5 w-5 text-indigo-600 rounded border-gray-300 focus:ring-indigo-500" />
                      <label for="obsolete" class="ml-2 text-gray-700">O-Obsolete</label>
                    </div>
                  </div>
                </div>

                <!-- M/Card with no S/Order -->
                <div>
                  <label class="block text-sm font-medium text-indigo-700 mb-2">M/Card with no S/Order:</label>
                  <div class="flex space-x-4">
                    <div class="flex items-center">
                      <input type="radio" id="yes" name="so_status" class="form-radio h-5 w-5 text-indigo-600 border-gray-300 focus:ring-indigo-500" />
                      <label for="yes" class="ml-2 text-gray-700">Y-Yes</label>
                    </div>
                    <div class="flex items-center">
                      <input type="radio" id="no" name="so_status" checked class="form-radio h-5 w-5 text-indigo-600 border-gray-300 focus:ring-indigo-500" />
                      <label for="no" class="ml-2 text-gray-700">N-No</label>
                    </div>
                  </div>
                </div>

                <!-- Non-Active for -->
                <div>
                  <label class="block text-sm font-medium text-indigo-700 mb-2">Non-Active for:</label>
                  <div class="flex items-center">
                    <input type="text" value="0" class="w-20 px-3 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 text-center" />
                    <span class="ml-2 text-gray-700">Months and above without any new orders</span>
                  </div>
                </div>

                <!-- Product# -->
                <div>
                  <label class="block text-sm font-medium text-indigo-700 mb-2">Product#:</label>
                  <div class="flex space-x-2">
                    <div class="relative flex-grow">
                      <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500" />
                      <button class="absolute inset-y-0 right-0 px-3 text-indigo-600 hover:text-indigo-800">
                        <i class="fas fa-search"></i>
                      </button>
                    </div>
                    <div class="flex items-center text-gray-700">
                      to
                    </div>
                    <div class="relative flex-grow">
                      <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500" />
                      <button class="absolute inset-y-0 right-0 px-3 text-indigo-600 hover:text-indigo-800">
                        <i class="fas fa-search"></i>
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Instructions and Tips -->
        <div class="mt-6 bg-gradient-to-r from-purple-50 to-pink-50 p-4 rounded-lg border border-purple-100 shadow-sm">
          <h3 class="font-semibold text-indigo-800 mb-2 flex items-center">
            <i class="fas fa-info-circle mr-2 text-purple-500"></i> Usage Instructions
          </h3>
          <ul class="list-disc pl-5 text-sm text-gray-600 space-y-1">
            <li>Set your filter criteria above to narrow down the list of non-active master cards</li>
            <li>Check "A-Active" and/or "O-Obsolete" to include those statuses in your results</li>
            <li>Set the number of months to filter by age of inactive master cards</li>
            <li>Click "Proceed" to view the filtered list of non-active master cards</li>
            <li>All fields are optional - leave blank to include all values</li>
          </ul>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script>
import { ref } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

export default {
  components: {
    AppLayout,
    Head,
    Link
  },
  setup() {
    // Form state variables
    const salesPeriodMonth = ref(6);
    const salesPeriodYear = ref(2025);
    const salespersonFrom = ref('');
    const salespersonTo = ref('');
    const acFrom = ref('');
    const acTo = ref('');
    const mcsFrom = ref('');
    const mcsTo = ref('');
    const showActive = ref(true);
    const showObsolete = ref(true);
    const withNoSalesOrder = ref(false);
    const inactiveMonths = ref(0);
    const productFrom = ref('');
    const productTo = ref('');

    // Action handlers
    const proceedAction = () => {
      // In a real implementation, this would navigate to a results page
      // or perform a search with the specified criteria
      alert('Searching for non-active master cards with the specified criteria');
    };

    return {
      salesPeriodMonth,
      salesPeriodYear,
      salespersonFrom,
      salespersonTo,
      acFrom,
      acTo,
      mcsFrom,
      mcsTo,
      showActive,
      showObsolete,
      withNoSalesOrder,
      inactiveMonths,
      productFrom,
      productTo,
      proceedAction
    };
  }
};
</script>

<style scoped>
/* Animation for transition effects */
.transition-all {
  transition: all 0.3s ease;
}

/* Text shadow for headings */
.text-shadow {
  text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

/* Enhance form styling */
input[type="text"],
input[type="number"] {
  transition: all 0.2s ease;
}

input[type="text"]:focus,
input[type="number"]:focus {
  border-color: #6366f1;
  box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.2);
}

input[type="checkbox"],
input[type="radio"] {
  cursor: pointer;
}

/* Button hover effects */
button {
  transition: all 0.2s ease;
}

button:hover {
  transform: translateY(-1px);
}

button:active {
  transform: translateY(0);
}

/* Print styling */
@media print {
  .bg-gradient-to-br,
  .bg-gradient-to-r {
    background: white !important;
  }
  
  .shadow-lg,
  .shadow-md,
  .shadow-sm {
    box-shadow: none !important;
  }
  
  .text-white,
  .text-blue-100 {
    color: black !important;
  }
  
  button {
    display: none !important;
  }
  
  input, select {
    border-color: #ddd !important;
  }
}
</style>
