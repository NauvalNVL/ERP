<template>
  <AppLayout :header="'View & Print Scoring Formula'">
    <Head title="View & Print Scoring Formula" />

    <!-- Header Section -->
    <div class="bg-gradient-to-r from-cyan-700 to-blue-600 p-6 rounded-t-lg shadow-lg">
      <h2 class="text-2xl font-bold text-white mb-2 flex items-center">
        <i class="fas fa-print mr-3"></i> View & Print Scoring Formula
      </h2>
      <p class="text-cyan-100">View and print scoring formula configurations for manufacturing</p>
    </div>

    <div class="bg-white rounded-b-lg shadow-lg p-6 mb-6">
      <!-- Action Buttons -->
      <div class="flex mb-6 space-x-2">
        <button class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded shadow flex items-center" @click="printFormula">
          <i class="fas fa-print mr-2"></i> Print
        </button>
        <button class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded shadow flex items-center">
          <i class="fas fa-sync-alt mr-2"></i> Refresh
        </button>
      </div>

      <!-- Main Content -->
      <div id="printable-content" class="border border-gray-300 rounded-lg bg-gray-100 p-4">
        <!-- Formula Header -->
        <div class="bg-white p-3 rounded-t-lg border border-gray-300 mb-1 flex justify-between items-center">
          <h3 class="text-lg font-bold text-gray-800">View & Print Scoring Formula</h3>
          <div class="flex items-center">
            <div class="w-6 h-6 rounded-full bg-red-500 mr-2"></div>
            <div class="w-6 h-6 rounded-full bg-gray-300"></div>
          </div>
        </div>

        <!-- Formula Content -->
        <div class="bg-white border border-gray-300 rounded-b-lg p-4">
          <!-- Tables Layout (2 columns) -->
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Left Column: Product Design & Paper Flute -->
            <div>
              <div class="mb-2 font-bold text-gray-700">Product Design</div>
              <div class="relative max-h-64 overflow-auto border border-gray-300 rounded mb-4">
                <table class="w-full text-sm">
                  <thead class="bg-gray-200 sticky top-0">
                    <tr>
                      <th class="text-left py-1 px-2">Product Design</th>
                      <th class="text-left py-1 px-2">Paper Flute</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr class="hover:bg-blue-100 cursor-pointer">
                      <td class="py-1 px-2 border-b">B1</td>
                      <td class="py-1 px-2 border-b">BF</td>
                    </tr>
                    <tr class="hover:bg-blue-100 cursor-pointer">
                      <td class="py-1 px-2 border-b">B1 RJ</td>
                      <td class="py-1 px-2 border-b">CF</td>
                    </tr>
                    <tr class="hover:bg-blue-100 cursor-pointer">
                      <td class="py-1 px-2 border-b">B1/B1 DJ</td>
                      <td class="py-1 px-2 border-b">AF</td>
                    </tr>
                    <tr class="hover:bg-blue-100 cursor-pointer">
                      <td class="py-1 px-2 border-b">B1/B1</td>
                      <td class="py-1 px-2 border-b">AB</td>
                    </tr>
                    <tr class="hover:bg-blue-100 cursor-pointer">
                      <td class="py-1 px-2 border-b">B1</td>
                      <td class="py-1 px-2 border-b">BC</td>
                    </tr>
                    <tr class="hover:bg-blue-100 cursor-pointer">
                      <td class="py-1 px-2 border-b">B1</td>
                      <td class="py-1 px-2 border-b">BEF</td>
                    </tr>
                    <tr class="hover:bg-blue-100 cursor-pointer">
                      <td class="py-1 px-2 border-b">B1</td>
                      <td class="py-1 px-2 border-b">EF</td>
                    </tr>
                    <tr class="hover:bg-blue-100 cursor-pointer">
                      <td class="py-1 px-2 border-b">B1</td>
                      <td class="py-1 px-2 border-b">CF</td>
                    </tr>
                    <tr class="hover:bg-blue-100 cursor-pointer">
                      <td class="py-1 px-2 border-b">B1 DJ</td>
                      <td class="py-1 px-2 border-b">CF</td>
                    </tr>
                    <tr class="hover:bg-blue-100 cursor-pointer">
                      <td class="py-1 px-2 border-b">B1 DJ</td>
                      <td class="py-1 px-2 border-b">BC</td>
                    </tr>
                    <tr class="hover:bg-blue-100 cursor-pointer">
                      <td class="py-1 px-2 border-b">B1/B0</td>
                      <td class="py-1 px-2 border-b">EF</td>
                    </tr>
                    <tr class="hover:bg-blue-100 cursor-pointer">
                      <td class="py-1 px-2 border-b">B1/B0 DJ</td>
                      <td class="py-1 px-2 border-b">EF</td>
                    </tr>
                    <tr class="hover:bg-blue-100 cursor-pointer">
                      <td class="py-1 px-2 border-b">B1/B0 NJ</td>
                      <td class="py-1 px-2 border-b">CF</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>

            <!-- Right Column: Scoring Length/Width -->
            <div>
              <!-- Scoring Length -->
              <div class="mb-2 font-bold text-gray-700">Scoring Length/Base</div>
              <div class="relative max-h-28 overflow-auto border border-gray-300 rounded mb-4">
                <table class="w-full text-sm">
                  <thead class="bg-gray-200 sticky top-0">
                    <tr>
                      <th class="text-center py-1 px-2 w-10">#</th>
                      <th class="text-left py-1 px-2">Value</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr class="hover:bg-blue-100 cursor-pointer">
                      <td class="py-1 px-2 border-b text-center">1</td>
                      <td class="py-1 px-2 border-b">40</td>
                    </tr>
                    <tr class="hover:bg-blue-100 cursor-pointer">
                      <td class="py-1 px-2 border-b text-center">2</td>
                      <td class="py-1 px-2 border-b">L+7</td>
                    </tr>
                    <tr class="hover:bg-blue-100 cursor-pointer">
                      <td class="py-1 px-2 border-b text-center">3</td>
                      <td class="py-1 px-2 border-b">W+7</td>
                    </tr>
                    <tr class="hover:bg-blue-100 cursor-pointer">
                      <td class="py-1 px-2 border-b text-center">4</td>
                      <td class="py-1 px-2 border-b">L+5</td>
                    </tr>
                    <tr class="hover:bg-blue-100 cursor-pointer">
                      <td class="py-1 px-2 border-b text-center">5</td>
                      <td class="py-1 px-2 border-b"></td>
                    </tr>
                    <tr class="hover:bg-blue-100 cursor-pointer">
                      <td class="py-1 px-2 border-b text-center">6</td>
                      <td class="py-1 px-2 border-b"></td>
                    </tr>
                    <tr class="hover:bg-blue-100 cursor-pointer">
                      <td class="py-1 px-2 border-b text-center">7</td>
                      <td class="py-1 px-2 border-b"></td>
                    </tr>
                    <tr class="hover:bg-blue-100 cursor-pointer">
                      <td class="py-1 px-2 border-b text-center">8</td>
                      <td class="py-1 px-2 border-b"></td>
                    </tr>
                    <tr class="hover:bg-blue-100 cursor-pointer">
                      <td class="py-1 px-2 border-b text-center">9</td>
                      <td class="py-1 px-2 border-b"></td>
                    </tr>
                    <tr class="hover:bg-blue-100 cursor-pointer">
                      <td class="py-1 px-2 border-b text-center">10</td>
                      <td class="py-1 px-2 border-b"></td>
                    </tr>
                  </tbody>
                </table>
              </div>

              <!-- Scoring Width -->
              <div class="mb-2 font-bold text-gray-700">Scoring Width/Base</div>
              <div class="relative max-h-28 overflow-auto border border-gray-300 rounded mb-4">
                <table class="w-full text-sm">
                  <thead class="bg-gray-200 sticky top-0">
                    <tr>
                      <th class="text-center py-1 px-2 w-10">#</th>
                      <th class="text-left py-1 px-2">Value</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr class="hover:bg-blue-100 cursor-pointer">
                      <td class="py-1 px-2 border-b text-center">1</td>
                      <td class="py-1 px-2 border-b">H-12</td>
                    </tr>
                    <tr class="hover:bg-blue-100 cursor-pointer">
                      <td class="py-1 px-2 border-b text-center">2</td>
                      <td class="py-1 px-2 border-b">H/2-5</td>
                    </tr>
                    <tr class="hover:bg-blue-100 cursor-pointer">
                      <td class="py-1 px-2 border-b text-center">3</td>
                      <td class="py-1 px-2 border-b"></td>
                    </tr>
                    <tr class="hover:bg-blue-100 cursor-pointer">
                      <td class="py-1 px-2 border-b text-center">4</td>
                      <td class="py-1 px-2 border-b"></td>
                    </tr>
                    <tr class="hover:bg-blue-100 cursor-pointer">
                      <td class="py-1 px-2 border-b text-center">5</td>
                      <td class="py-1 px-2 border-b"></td>
                    </tr>
                    <tr class="hover:bg-blue-100 cursor-pointer">
                      <td class="py-1 px-2 border-b text-center">6</td>
                      <td class="py-1 px-2 border-b"></td>
                    </tr>
                    <tr class="hover:bg-blue-100 cursor-pointer">
                      <td class="py-1 px-2 border-b text-center">7</td>
                      <td class="py-1 px-2 border-b"></td>
                    </tr>
                    <tr class="hover:bg-blue-100 cursor-pointer">
                      <td class="py-1 px-2 border-b text-center">8</td>
                      <td class="py-1 px-2 border-b"></td>
                    </tr>
                    <tr class="hover:bg-blue-100 cursor-pointer">
                      <td class="py-1 px-2 border-b text-center">9</td>
                      <td class="py-1 px-2 border-b"></td>
                    </tr>
                    <tr class="hover:bg-blue-100 cursor-pointer">
                      <td class="py-1 px-2 border-b text-center">10</td>
                      <td class="py-1 px-2 border-b"></td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>

          <!-- Dimension Conversion Formula -->
          <div class="mt-6">
            <div class="mb-2 font-bold text-gray-700">— Dimension Conversion Formula —</div>
            <div class="bg-gray-50 border border-gray-300 rounded p-3 text-sm">
              <p class="mb-2">To convert from ordered to internal dimensions or vice-versa</p>
              <div class="flex items-center space-x-2 mb-1 text-gray-800">
                <span class="font-bold">Length:</span>
                <span class="px-2 py-1 bg-white border border-gray-300 rounded">7</span>
                <span>mm</span>
              </div>
              <div class="flex items-center space-x-2 mb-1 text-gray-800">
                <span class="font-bold">Width:</span>
                <span class="px-2 py-1 bg-white border border-gray-300 rounded">7</span>
                <span>mm</span>
              </div>
              <div class="flex items-center space-x-2 text-gray-800">
                <span class="font-bold">Height:</span>
                <span class="px-2 py-1 bg-white border border-gray-300 rounded">12</span>
                <span>mm</span>
              </div>
            </div>
          </div>

          <!-- Scoring Length & Width Formula -->
          <div class="mt-6">
            <div class="mb-2 font-bold text-gray-700">Scoring Length & Width Formula</div>
            <div class="bg-gray-50 border border-gray-300 rounded p-3 text-sm">
              <div class="flex items-center">
                <span class="mr-3">Calculate using:</span>
                <div class="flex items-center space-x-4">
                  <label class="flex items-center">
                    <input type="radio" name="calculation" class="mr-1" checked>
                    <span>Internal Measurement</span>
                  </label>
                  <label class="flex items-center">
                    <input type="radio" name="calculation" class="mr-1">
                    <span>External Measurement</span>
                  </label>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Loading Overlay -->
    <div v-if="loading" class="fixed inset-0 z-50 bg-black bg-opacity-50 flex justify-center items-center">
      <div class="bg-white p-5 rounded-lg shadow-lg flex items-center">
        <div class="w-10 h-10 border-4 border-blue-500 border-t-transparent rounded-full animate-spin mr-3"></div>
        <p class="text-gray-700 font-medium">Loading data...</p>
      </div>
    </div>
    
    <!-- Notification Toast -->
    <div v-if="notification.show" class="fixed bottom-4 right-4 z-50 shadow-xl rounded-lg transition-all duration-300"
        :class="{
            'bg-green-100 border-l-4 border-green-500': notification.type === 'success',
            'bg-red-100 border-l-4 border-red-500': notification.type === 'error',
            'bg-yellow-100 border-l-4 border-yellow-500': notification.type === 'warning'
        }">
      <div class="p-4 flex items-center">
        <div class="mr-3">
          <i v-if="notification.type === 'success'" class="fas fa-check-circle text-green-500 text-xl"></i>
          <i v-else-if="notification.type === 'error'" class="fas fa-exclamation-circle text-red-500 text-xl"></i>
          <i v-else class="fas fa-exclamation-triangle text-yellow-500 text-xl"></i>
        </div>
        <div>
          <p :class="{
              'text-green-800': notification.type === 'success',
              'text-red-800': notification.type === 'error',
              'text-yellow-800': notification.type === 'warning'
          }">{{ notification.message }}</p>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

// State variables
const loading = ref(false);
const notification = ref({ show: false, message: '', type: 'success' });
const formulaData = ref({
  productDesigns: [],
  scoringLengths: [],
  scoringWidths: [],
  dimensions: {
    length: 7,
    width: 7,
    height: 12
  }
});

// Fetch formula data
const fetchFormulaData = async () => {
  loading.value = true;
  try {
    // This would be replaced with an actual API call
    // const response = await fetch('/api/scoring-formulas');
    // const data = await response.json();
    // formulaData.value = data;
    
    // For demo purposes, we're just setting a timeout
    await new Promise(resolve => setTimeout(resolve, 500));
    
    showNotification('Formula data loaded successfully', 'success');
  } catch (error) {
    console.error('Error fetching formula data:', error);
    showNotification('Error loading formula data', 'error');
  } finally {
    loading.value = false;
  }
};

// Print formula handler
const printFormula = () => {
  const printContent = document.getElementById('printable-content');
  const originalContent = document.body.innerHTML;
  
  document.body.innerHTML = printContent.innerHTML;
  window.print();
  document.body.innerHTML = originalContent;
  
  // Re-initialize the Vue app after printing
  location.reload();
};

// Show notification toast
const showNotification = (message, type = 'success') => {
  notification.value = {
    show: true,
    message,
    type
  };
  
  setTimeout(() => {
    notification.value.show = false;
  }, 3000);
};

// Load data on component mount
onMounted(() => {
  fetchFormulaData();
});
</script>

<style scoped>
@media print {
  body {
    margin: 0;
    padding: 0;
    background: white;
  }
  
  button, .no-print {
    display: none !important;
  }
  
  #printable-content {
    width: 100%;
    padding: 0;
    margin: 0;
    background: white;
    border: none;
  }
}
</style>
