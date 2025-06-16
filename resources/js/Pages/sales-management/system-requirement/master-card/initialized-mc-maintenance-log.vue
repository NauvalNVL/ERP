<template>
  <AppLayout title="Initialize MC Maintenance Log">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Initialize MC Maintenance Log
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
                    <i class="fas fa-file-alt text-white text-2xl"></i>
                  </div>
                  <div>
                    <h1 class="text-2xl md:text-3xl font-bold text-white text-shadow">Initialize MC Maintenance Log</h1>
                    <p class="text-blue-100 max-w-2xl">Initialize and manage master card maintenance log records</p>
                  </div>
                </div>
                <div class="flex flex-wrap gap-3">
                  <button @click="initializeAction" class="bg-gradient-to-r from-green-500 to-teal-400 text-white hover:from-green-600 hover:to-teal-500 px-4 py-2 rounded-lg flex items-center transition-all duration-300 transform hover:scale-105 shadow-md">
                    <i class="fas fa-sync-alt mr-2"></i> Initialize
                  </button>
                  <button @click="$router.go(-1)" class="bg-white bg-opacity-20 text-white border border-white border-opacity-30 hover:bg-opacity-30 px-4 py-2 rounded-lg flex items-center transition-all duration-300">
                    <i class="fas fa-arrow-left mr-2"></i> Back
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Log Information Form -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-6">
          <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <!-- M/C Audit Log Records -->
              <div class="space-y-4">
                <h3 class="font-semibold text-lg text-indigo-700 border-b border-indigo-100 pb-2">M/C Audit Log Records</h3>
                
                <div class="grid grid-cols-[150px_1fr] items-center gap-4">
                  <label class="font-medium text-gray-700">First Record:</label>
                  <div class="flex space-x-2">
                    <input 
                      type="text" 
                      v-model="auditFirstDate" 
                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500"
                      readonly 
                    />
                    <input 
                      type="text" 
                      v-model="auditFirstTime" 
                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500"
                      readonly 
                    />
                  </div>
                </div>
                
                <div class="grid grid-cols-[150px_1fr] items-center gap-4">
                  <label class="font-medium text-gray-700">Last Record:</label>
                  <div class="flex space-x-2">
                    <input 
                      type="text" 
                      v-model="auditLastDate" 
                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500"
                      readonly 
                    />
                    <input 
                      type="text" 
                      v-model="auditLastTime" 
                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500"
                      readonly 
                    />
                  </div>
                </div>
              </div>
              
              <!-- M/C Verify Log Records -->
              <div class="space-y-4">
                <h3 class="font-semibold text-lg text-indigo-700 border-b border-indigo-100 pb-2">M/C Verify Log Records</h3>
                
                <div class="grid grid-cols-[150px_1fr] items-center gap-4">
                  <label class="font-medium text-gray-700">First Record:</label>
                  <div class="flex space-x-2">
                    <input 
                      type="text" 
                      v-model="verifyFirstDate" 
                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500"
                      readonly 
                    />
                    <input 
                      type="text" 
                      v-model="verifyFirstTime" 
                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500"
                      readonly 
                    />
                  </div>
                </div>
                
                <div class="grid grid-cols-[150px_1fr] items-center gap-4">
                  <label class="font-medium text-gray-700">Last Record:</label>
                  <div class="flex space-x-2">
                    <input 
                      type="text" 
                      v-model="verifyLastDate" 
                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500"
                      readonly 
                    />
                    <input 
                      type="text" 
                      v-model="verifyLastTime" 
                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500"
                      readonly 
                    />
                  </div>
                </div>
              </div>
            </div>
            
            <!-- Initialize from first record to -->
            <div class="mt-6">
              <div class="grid grid-cols-[200px_1fr] items-center gap-4">
                <label class="font-medium text-gray-700">Initialize from first record to:</label>
                <div class="flex items-center space-x-2">
                  <div class="relative flex-grow-0">
                    <input 
                      type="text" 
                      v-model="initializeFrom" 
                      class="w-36 px-3 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500" 
                    />
                    <button class="absolute inset-y-0 right-0 px-3 text-indigo-600 hover:text-indigo-800">
                      <i class="fas fa-search"></i>
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- System Note -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-6">
          <div class="p-6">
            <h3 class="font-semibold text-lg text-indigo-700 mb-4">Note</h3>
            <div class="p-4 bg-gray-50 border border-gray-200 rounded-md">
              <p class="text-gray-700 font-medium">System will retain at least 1 year of audit log records!</p>
            </div>
          </div>
        </div>

        <!-- Instructions and Tips -->
        <div class="mt-6 bg-gradient-to-r from-purple-50 to-pink-50 p-4 rounded-lg border border-purple-100 shadow-sm">
          <h3 class="font-semibold text-indigo-800 mb-2 flex items-center">
            <i class="fas fa-info-circle mr-2 text-purple-500"></i> Usage Instructions
          </h3>
          <ul class="list-disc pl-5 text-sm text-gray-600 space-y-1">
            <li>The audit log records track all changes made to master cards</li>
            <li>The verification log records track when master cards were verified</li>
            <li>Enter a record ID in the initialize field to remove logs before that record</li>
            <li>Click "Initialize" to perform the maintenance log cleanup</li>
            <li>The system will always retain at least 1 year of log history</li>
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

    return {
      auditFirstDate,
      auditFirstTime,
      auditLastDate,
      auditLastTime,
      verifyFirstDate,
      verifyFirstTime,
      verifyLastDate,
      verifyLastTime,
      initializeFrom,
      initializeAction
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
