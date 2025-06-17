<template>
  <AppLayout :header="'Initialize MC Maintenance Log'">
    <Head title="Initialize MC Maintenance Log" />

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
                    <i class="fas fa-history text-white text-2xl z-10 animate-pulse"></i>
                  </div>
                  <div>
                    <h1 class="text-2xl md:text-3xl font-bold text-white text-shadow">Initialize MC Maintenance Log</h1>
                    <p class="text-blue-100 max-w-2xl">Initialize and manage master card maintenance log records</p>
                  </div>
                </div>
                <div class="flex flex-wrap gap-3">
                  <button @click="initializeAction" class="bg-gradient-to-r from-green-500 to-teal-400 text-white hover:from-green-600 hover:to-teal-500 px-4 py-2 rounded-lg flex items-center transition-all duration-300 transform hover:scale-105 shadow-md relative overflow-hidden group">
                    <span class="absolute inset-0 bg-white opacity-20 transform scale-x-0 origin-left transition-transform group-hover:scale-x-100"></span>
                    <div class="relative z-10 flex items-center">
                      <div class="bg-white bg-opacity-30 rounded-full p-1 mr-2">
                        <i class="fas fa-sync-alt text-white"></i>
                      </div>
                      <span>Initialize</span>
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

        <!-- Log Information Form -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-6">
          <div class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white py-3 px-6">
            <div class="flex items-center">
              <div class="mr-3 bg-white bg-opacity-20 p-2 rounded-full shadow-inner relative overflow-hidden">
                <div class="absolute -top-1 -right-1 w-4 h-4 bg-blue-300 opacity-30 rounded-full"></div>
                <div class="absolute -bottom-1 -left-1 w-3 h-3 bg-yellow-300 opacity-30 rounded-full"></div>
                <i class="fas fa-database text-xl relative z-10"></i>
              </div>
              <div>
                <h2 class="text-xl font-bold">Log Records</h2>
                <p class="text-xs text-blue-100 opacity-80">View and manage maintenance log records</p>
              </div>
            </div>
          </div>
          <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <!-- M/C Audit Log Records -->
              <div class="space-y-4 bg-gradient-to-br from-indigo-50 to-white p-4 rounded-lg border border-indigo-100">
                <h3 class="font-semibold text-lg text-indigo-700 border-b border-indigo-100 pb-2 flex items-center">
                  <div class="w-8 h-8 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-full mr-2 flex items-center justify-center shadow-sm">
                    <i class="fas fa-clipboard-check text-white text-sm"></i>
                  </div>
                  M/C Audit Log Records
                </h3>
                
                <div class="grid grid-cols-[150px_1fr] items-center gap-4">
                  <label class="font-medium text-gray-700 flex items-center">
                    <i class="fas fa-hourglass-start text-indigo-500 mr-2"></i>
                    First Record:
                  </label>
                  <div class="flex space-x-2">
                    <div class="relative flex-1">
                      <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-calendar-alt text-gray-400"></i>
                      </div>
                      <input 
                        type="text" 
                        v-model="auditFirstDate" 
                        class="w-full pl-10 px-3 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500"
                        readonly 
                      />
                    </div>
                    <div class="relative flex-1">
                      <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-clock text-gray-400"></i>
                      </div>
                      <input 
                        type="text" 
                        v-model="auditFirstTime" 
                        class="w-full pl-10 px-3 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500"
                        readonly 
                      />
                    </div>
                  </div>
                </div>
                
                <div class="grid grid-cols-[150px_1fr] items-center gap-4">
                  <label class="font-medium text-gray-700 flex items-center">
                    <i class="fas fa-hourglass-end text-indigo-500 mr-2"></i>
                    Last Record:
                  </label>
                  <div class="flex space-x-2">
                    <div class="relative flex-1">
                      <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-calendar-alt text-gray-400"></i>
                      </div>
                      <input 
                        type="text" 
                        v-model="auditLastDate" 
                        class="w-full pl-10 px-3 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500"
                        readonly 
                      />
                    </div>
                    <div class="relative flex-1">
                      <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-clock text-gray-400"></i>
                      </div>
                      <input 
                        type="text" 
                        v-model="auditLastTime" 
                        class="w-full pl-10 px-3 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500"
                        readonly 
                      />
                    </div>
                  </div>
                </div>
              </div>
              
              <!-- M/C Verify Log Records -->
              <div class="space-y-4 bg-gradient-to-br from-purple-50 to-white p-4 rounded-lg border border-purple-100">
                <h3 class="font-semibold text-lg text-indigo-700 border-b border-indigo-100 pb-2 flex items-center">
                  <div class="w-8 h-8 bg-gradient-to-r from-purple-500 to-pink-600 rounded-full mr-2 flex items-center justify-center shadow-sm">
                    <i class="fas fa-clipboard-list text-white text-sm"></i>
                  </div>
                  M/C Verify Log Records
                </h3>
                
                <div class="grid grid-cols-[150px_1fr] items-center gap-4">
                  <label class="font-medium text-gray-700 flex items-center">
                    <i class="fas fa-hourglass-start text-purple-500 mr-2"></i>
                    First Record:
                  </label>
                  <div class="flex space-x-2">
                    <div class="relative flex-1">
                      <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-calendar-alt text-gray-400"></i>
                      </div>
                      <input 
                        type="text" 
                        v-model="verifyFirstDate" 
                        class="w-full pl-10 px-3 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500"
                        readonly 
                      />
                    </div>
                    <div class="relative flex-1">
                      <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-clock text-gray-400"></i>
                      </div>
                      <input 
                        type="text" 
                        v-model="verifyFirstTime" 
                        class="w-full pl-10 px-3 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500"
                        readonly 
                      />
                    </div>
                  </div>
                </div>
                
                <div class="grid grid-cols-[150px_1fr] items-center gap-4">
                  <label class="font-medium text-gray-700 flex items-center">
                    <i class="fas fa-hourglass-end text-purple-500 mr-2"></i>
                    Last Record:
                  </label>
                  <div class="flex space-x-2">
                    <div class="relative flex-1">
                      <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-calendar-alt text-gray-400"></i>
                      </div>
                      <input 
                        type="text" 
                        v-model="verifyLastDate" 
                        class="w-full pl-10 px-3 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500"
                        readonly 
                      />
                    </div>
                    <div class="relative flex-1">
                      <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-clock text-gray-400"></i>
                      </div>
                      <input 
                        type="text" 
                        v-model="verifyLastTime" 
                        class="w-full pl-10 px-3 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500"
                        readonly 
                      />
                    </div>
                  </div>
                </div>
              </div>
            </div>
            
            <!-- Initialize from first record to -->
            <div class="mt-8 p-4 bg-gradient-to-r from-indigo-50 via-purple-50 to-pink-50 rounded-lg border border-indigo-100">
              <div class="grid grid-cols-1 md:grid-cols-[200px_1fr] items-center gap-4">
                <label class="font-medium text-gray-700 flex items-center">
                  <div class="w-8 h-8 bg-gradient-to-r from-green-500 to-teal-500 rounded-full mr-2 flex items-center justify-center shadow-sm">
                    <i class="fas fa-calendar-day text-white text-sm"></i>
                  </div>
                  Initialize from first record to:
                </label>
                <div class="flex items-center space-x-2">
                  <div class="relative flex-grow-0">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                      <i class="fas fa-calendar-alt text-gray-400"></i>
                    </div>
                    <input 
                      type="text" 
                      v-model="initializeFrom" 
                      class="w-48 pl-10 px-3 py-3 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 shadow-sm" 
                    />
                    <button class="absolute inset-y-0 right-0 px-3 text-indigo-600 hover:text-indigo-800">
                      <i class="fas fa-search"></i>
                    </button>
                  </div>
                  <button class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-6 py-3 rounded-lg shadow-md hover:from-indigo-700 hover:to-purple-700 transition-colors">
                    <i class="fas fa-calendar-check mr-2"></i>
                    Set Date
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- System Note -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-6">
          <div class="bg-gradient-to-r from-yellow-500 to-amber-500 text-white py-3 px-6">
            <div class="flex items-center">
              <div class="mr-3 bg-white bg-opacity-20 p-2 rounded-full shadow-inner">
                <i class="fas fa-exclamation-triangle text-xl"></i>
              </div>
              <h2 class="text-xl font-bold">Important Note</h2>
            </div>
          </div>
          <div class="p-6">
            <div class="p-4 bg-gradient-to-r from-yellow-50 to-amber-50 border border-yellow-200 rounded-md flex items-start">
              <i class="fas fa-info-circle text-amber-500 text-xl mr-3 mt-1"></i>
              <p class="text-gray-700 font-medium">System will retain at least 1 year of audit log records!</p>
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
                <span class="text-gray-700">The audit log records track all changes made to master cards</span>
              </li>
              <li class="flex items-start">
                <div class="w-6 h-6 bg-gradient-to-r from-indigo-500 to-purple-500 rounded-full flex items-center justify-center mr-3 mt-0.5 flex-shrink-0">
                  <i class="fas fa-check text-white text-xs"></i>
                </div>
                <span class="text-gray-700">The verification log records track when master cards were verified</span>
              </li>
              <li class="flex items-start">
                <div class="w-6 h-6 bg-gradient-to-r from-indigo-500 to-purple-500 rounded-full flex items-center justify-center mr-3 mt-0.5 flex-shrink-0">
                  <i class="fas fa-check text-white text-xs"></i>
                </div>
                <span class="text-gray-700">Enter a record ID in the initialize field to remove logs before that record</span>
              </li>
              <li class="flex items-start">
                <div class="w-6 h-6 bg-gradient-to-r from-indigo-500 to-purple-500 rounded-full flex items-center justify-center mr-3 mt-0.5 flex-shrink-0">
                  <i class="fas fa-check text-white text-xs"></i>
                </div>
                <span class="text-gray-700">Click "Initialize" to perform the maintenance log cleanup</span>
              </li>
              <li class="flex items-start">
                <div class="w-6 h-6 bg-gradient-to-r from-indigo-500 to-purple-500 rounded-full flex items-center justify-center mr-3 mt-0.5 flex-shrink-0">
                  <i class="fas fa-check text-white text-xs"></i>
                </div>
                <span class="text-gray-700">The system will always retain at least 1 year of log history</span>
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
const auditFirstDate = ref('03/05/2014');
const auditFirstTime = ref('09:52:04');
const auditLastDate = ref('14/06/2025');
const auditLastTime = ref('16:27:33');

const verifyFirstDate = ref('03/05/2014');
const verifyFirstTime = ref('09:52:22');
const verifyLastDate = ref('25/11/2015');
const verifyLastTime = ref('09:54:22');

const initializeFrom = ref('00/00/0000');

// Action handlers
const initializeAction = () => {
  // In a real implementation, this would send a request to initialize logs
  if (confirm('Are you sure you want to initialize the maintenance log records?')) {
    alert('Maintenance log initialization started');
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
