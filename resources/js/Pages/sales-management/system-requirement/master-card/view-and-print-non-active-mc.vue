<template>
  <AppLayout :header="'View & Print Non-Active MC'">
    <Head title="View & Print Non-Active MC" />

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
                    <i class="fas fa-print text-white text-2xl z-10"></i>
                  </div>
                  <div>
                    <h1 class="text-2xl md:text-3xl font-bold text-white text-shadow">View & Print Non-Active MC</h1>
                    <p class="text-blue-100 max-w-2xl">Preview and print master cards that are no longer active</p>
                  </div>
                </div>
                <div class="flex flex-wrap gap-3">
                  <button @click="proceedAction" class="bg-gradient-to-r from-green-500 to-teal-400 text-white hover:from-green-600 hover:to-teal-500 px-4 py-2 rounded-lg flex items-center transition-all duration-300 transform hover:scale-105 shadow-md relative overflow-hidden group">
                    <span class="absolute inset-0 bg-white opacity-20 transform scale-x-0 origin-left transition-transform group-hover:scale-x-100"></span>
                    <div class="relative z-10 flex items-center">
                      <div class="bg-white bg-opacity-30 rounded-full p-1 mr-2">
                        <i class="fas fa-check text-white"></i>
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

        <!-- Search Form -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-6">
          <div class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white py-3 px-6">
            <div class="flex items-center">
              <div class="mr-3 bg-white bg-opacity-20 p-2 rounded-full shadow-inner relative overflow-hidden">
                <div class="absolute -top-1 -right-1 w-4 h-4 bg-blue-300 opacity-30 rounded-full"></div>
                <div class="absolute -bottom-1 -left-1 w-3 h-3 bg-yellow-300 opacity-30 rounded-full"></div>
                <i class="fas fa-filter text-xl relative z-10"></i>
              </div>
              <div>
                <h2 class="text-xl font-bold">Search Parameters</h2>
                <p class="text-xs text-blue-100 opacity-80">Filter non-active master cards by various criteria</p>
              </div>
            </div>
          </div>
          <div class="p-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
              <!-- Left Column - Filter Fields -->
              <div class="space-y-6">
                <!-- Current Sales Period -->
                <div>
                  <label class="block text-sm font-medium text-indigo-700 mb-2 flex items-center">
                    <div class="w-8 h-8 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-full mr-2 flex items-center justify-center shadow-sm">
                      <i class="fas fa-calendar-alt text-white text-sm"></i>
                    </div>
                    Current Sales Period:
                  </label>
                  <div class="flex space-x-2 ml-10">
                    <div class="relative w-20">
                      <input type="text" value="6" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 shadow-sm" readonly />
                    </div>
                    <div class="relative w-24">
                      <input type="text" value="2025" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 shadow-sm" readonly />
                    </div>
                    <div class="flex items-center text-gray-500 text-sm">
                      mm/yyyy
                    </div>
                  </div>
                </div>

                <!-- Salesperson Code -->
                <div>
                  <label class="block text-sm font-medium text-indigo-700 mb-2 flex items-center">
                    <div class="w-8 h-8 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-full mr-2 flex items-center justify-center shadow-sm">
                      <i class="fas fa-user-tie text-white text-sm"></i>
                    </div>
                    Salesperson Code:
                  </label>
                  <div class="flex space-x-2 ml-10">
                    <div class="relative flex-grow">
                      <input type="text" class="w-full pl-3 pr-10 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 shadow-sm" />
                      <button class="absolute inset-y-0 right-0 px-3 text-indigo-600 hover:text-indigo-800 transition-colors">
                        <i class="fas fa-search"></i>
                      </button>
                    </div>
                    <div class="flex items-center text-gray-700">
                      to
                    </div>
                    <div class="relative flex-grow">
                      <input type="text" class="w-full pl-3 pr-10 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 shadow-sm" />
                      <button class="absolute inset-y-0 right-0 px-3 text-indigo-600 hover:text-indigo-800 transition-colors">
                        <i class="fas fa-search"></i>
                      </button>
                    </div>
                  </div>
                </div>

                <!-- AC# -->
                <div>
                  <label class="block text-sm font-medium text-indigo-700 mb-2 flex items-center">
                    <div class="w-8 h-8 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-full mr-2 flex items-center justify-center shadow-sm">
                      <i class="fas fa-building text-white text-sm"></i>
                    </div>
                    AC#:
                  </label>
                  <div class="flex space-x-2 ml-10">
                    <div class="relative flex-grow">
                      <input type="text" class="w-full pl-3 pr-10 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 shadow-sm" />
                      <button class="absolute inset-y-0 right-0 px-3 text-indigo-600 hover:text-indigo-800 transition-colors">
                        <i class="fas fa-search"></i>
                      </button>
                    </div>
                    <div class="flex items-center text-gray-700">
                      to
                    </div>
                    <div class="relative flex-grow">
                      <input type="text" class="w-full pl-3 pr-10 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 shadow-sm" />
                      <button class="absolute inset-y-0 right-0 px-3 text-indigo-600 hover:text-indigo-800 transition-colors">
                        <i class="fas fa-search"></i>
                      </button>
                    </div>
                  </div>
                </div>

                <!-- MCS# -->
                <div>
                  <label class="block text-sm font-medium text-indigo-700 mb-2 flex items-center">
                    <div class="w-8 h-8 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-full mr-2 flex items-center justify-center shadow-sm">
                      <i class="fas fa-file-alt text-white text-sm"></i>
                    </div>
                    MCS#:
                  </label>
                  <div class="flex space-x-2 ml-10">
                    <div class="relative flex-grow">
                      <input type="text" class="w-full pl-3 pr-10 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 shadow-sm" />
                      <button class="absolute inset-y-0 right-0 px-3 text-indigo-600 hover:text-indigo-800 transition-colors">
                        <i class="fas fa-search"></i>
                      </button>
                    </div>
                    <div class="flex items-center text-gray-700">
                      to
                    </div>
                    <div class="relative flex-grow">
                      <input type="text" class="w-full pl-3 pr-10 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 shadow-sm" />
                      <button class="absolute inset-y-0 right-0 px-3 text-indigo-600 hover:text-indigo-800 transition-colors">
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
                  <label class="block text-sm font-medium text-indigo-700 mb-2 flex items-center">
                    <div class="w-8 h-8 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-full mr-2 flex items-center justify-center shadow-sm">
                      <i class="fas fa-tag text-white text-sm"></i>
                    </div>
                    Master Card Status:
                  </label>
                  <div class="flex space-x-6 ml-10 mt-1">
                    <div class="flex items-center">
                      <input type="checkbox" id="active" checked class="form-checkbox h-5 w-5 text-indigo-600 rounded border-gray-300 focus:ring-indigo-500" />
                      <label for="active" class="ml-2 text-gray-700 flex items-center">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                          <i class="fas fa-check-circle mr-1"></i> Active
                        </span>
                      </label>
                    </div>
                    <div class="flex items-center">
                      <input type="checkbox" id="obsolete" checked class="form-checkbox h-5 w-5 text-indigo-600 rounded border-gray-300 focus:ring-indigo-500" />
                      <label for="obsolete" class="ml-2 text-gray-700 flex items-center">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                          <i class="fas fa-ban mr-1"></i> Obsolete
                        </span>
                      </label>
                    </div>
                  </div>
                </div>

                <!-- M/Card with no S/Order -->
                <div>
                  <label class="block text-sm font-medium text-indigo-700 mb-2 flex items-center">
                    <div class="w-8 h-8 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-full mr-2 flex items-center justify-center shadow-sm">
                      <i class="fas fa-file-invoice text-white text-sm"></i>
                    </div>
                    M/Card with no S/Order:
                  </label>
                  <div class="flex space-x-6 ml-10 mt-1">
                    <div class="flex items-center">
                      <input type="radio" id="yes" name="so_status" class="form-radio h-5 w-5 text-indigo-600 border-gray-300 focus:ring-indigo-500" />
                      <label for="yes" class="ml-2 text-gray-700 flex items-center">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                          <i class="fas fa-check mr-1"></i> Yes
                        </span>
                      </label>
                    </div>
                    <div class="flex items-center">
                      <input type="radio" id="no" name="so_status" checked class="form-radio h-5 w-5 text-indigo-600 border-gray-300 focus:ring-indigo-500" />
                      <label for="no" class="ml-2 text-gray-700 flex items-center">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                          <i class="fas fa-times mr-1"></i> No
                        </span>
                      </label>
                    </div>
                  </div>
                </div>

                <!-- Non-Active for -->
                <div>
                  <label class="block text-sm font-medium text-indigo-700 mb-2 flex items-center">
                    <div class="w-8 h-8 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-full mr-2 flex items-center justify-center shadow-sm">
                      <i class="fas fa-hourglass-half text-white text-sm"></i>
                    </div>
                    Non-Active for:
                  </label>
                  <div class="flex items-center ml-10">
                    <div class="relative">
                      <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-clock text-gray-400"></i>
                      </div>
                      <input type="text" value="0" class="w-20 pl-10 px-3 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 text-center shadow-sm" />
                    </div>
                    <span class="ml-3 text-gray-700">Months and above without any new orders</span>
                  </div>
                </div>

                <!-- Product# -->
                <div>
                  <label class="block text-sm font-medium text-indigo-700 mb-2 flex items-center">
                    <div class="w-8 h-8 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-full mr-2 flex items-center justify-center shadow-sm">
                      <i class="fas fa-box text-white text-sm"></i>
                    </div>
                    Product#:
                  </label>
                  <div class="flex space-x-2 ml-10">
                    <div class="relative flex-grow">
                      <input type="text" class="w-full pl-3 pr-10 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 shadow-sm" />
                      <button class="absolute inset-y-0 right-0 px-3 text-indigo-600 hover:text-indigo-800 transition-colors">
                        <i class="fas fa-search"></i>
                      </button>
                    </div>
                    <div class="flex items-center text-gray-700">
                      to
                    </div>
                    <div class="relative flex-grow">
                      <input type="text" class="w-full pl-3 pr-10 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 shadow-sm" />
                      <button class="absolute inset-y-0 right-0 px-3 text-indigo-600 hover:text-indigo-800 transition-colors">
                        <i class="fas fa-search"></i>
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Action Buttons -->
            <div class="mt-8 flex justify-end space-x-4">
              <button class="px-6 py-2.5 border border-gray-300 rounded-lg bg-white hover:bg-gray-50 text-gray-700 transition-all duration-300 shadow-sm flex items-center">
                <i class="fas fa-undo mr-2 text-gray-500"></i>
                Reset Filters
              </button>
              <button @click="proceedAction" class="px-6 py-2.5 bg-gradient-to-r from-purple-600 to-pink-500 hover:from-purple-700 hover:to-pink-600 text-white rounded-lg transition-all duration-300 shadow-md flex items-center">
                <i class="fas fa-check mr-2"></i>
                Proceed
              </button>
            </div>
          </div>
        </div>

        <!-- Instructions and Tips -->
        <div class="bg-gradient-to-br from-white to-purple-50 rounded-xl shadow-lg overflow-hidden mb-6">
          <div class="bg-gradient-to-r from-blue-500 to-indigo-600 text-white py-3 px-6">
            <div class="flex items-center">
              <div class="mr-3 bg-white bg-opacity-20 p-2 rounded-full shadow-inner">
                <i class="fas fa-lightbulb text-xl"></i>
              </div>
              <h2 class="text-xl font-bold">Usage Instructions</h2>
            </div>
          </div>
          <div class="p-6">
            <ul class="space-y-3">
              <li class="flex items-start">
                <div class="w-6 h-6 bg-gradient-to-r from-indigo-500 to-purple-500 rounded-full flex items-center justify-center mr-3 mt-0.5 flex-shrink-0">
                  <i class="fas fa-check text-white text-xs"></i>
                </div>
                <span class="text-gray-700">Set your filter criteria above to narrow down the list of non-active master cards</span>
              </li>
              <li class="flex items-start">
                <div class="w-6 h-6 bg-gradient-to-r from-indigo-500 to-purple-500 rounded-full flex items-center justify-center mr-3 mt-0.5 flex-shrink-0">
                  <i class="fas fa-check text-white text-xs"></i>
                </div>
                <span class="text-gray-700">Check "A-Active" and/or "O-Obsolete" to include those statuses in your results</span>
              </li>
              <li class="flex items-start">
                <div class="w-6 h-6 bg-gradient-to-r from-indigo-500 to-purple-500 rounded-full flex items-center justify-center mr-3 mt-0.5 flex-shrink-0">
                  <i class="fas fa-check text-white text-xs"></i>
                </div>
                <span class="text-gray-700">Set the number of months to filter by age of inactive master cards</span>
              </li>
              <li class="flex items-start">
                <div class="w-6 h-6 bg-gradient-to-r from-indigo-500 to-purple-500 rounded-full flex items-center justify-center mr-3 mt-0.5 flex-shrink-0">
                  <i class="fas fa-check text-white text-xs"></i>
                </div>
                <span class="text-gray-700">Click "Proceed" to view the filtered list of non-active master cards</span>
              </li>
              <li class="flex items-start">
                <div class="w-6 h-6 bg-gradient-to-r from-indigo-500 to-purple-500 rounded-full flex items-center justify-center mr-3 mt-0.5 flex-shrink-0">
                  <i class="fas fa-check text-white text-xs"></i>
                </div>
                <span class="text-gray-700">All fields are optional - leave blank to include all values</span>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

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

/* Custom animation for slow ping effect */
@keyframes ping-slow {
  0% {
    transform: scale(1);
    opacity: 0.5;
  }
  50% {
    transform: scale(1.8);
    opacity: 0.15;
  }
  100% {
    transform: scale(1);
    opacity: 0.5;
  }
}

.animate-ping-slow {
  animation: ping-slow 3s ease-in-out infinite;
}

.animation-delay-500 {
  animation-delay: 1.5s;
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
