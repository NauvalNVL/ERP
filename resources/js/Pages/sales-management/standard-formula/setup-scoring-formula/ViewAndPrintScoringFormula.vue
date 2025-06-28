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
        <button class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded shadow flex items-center" @click="fetchFormulaData">
          <i class="fas fa-sync-alt mr-2"></i> Refresh
        </button>
        <button class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded shadow flex items-center" @click="seedData">
          <i class="fas fa-database mr-2"></i> Seed Data
        </button>
      </div>

      <!-- Loading indicator -->
      <div v-if="loading" class="flex justify-center items-center py-8">
        <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-blue-500"></div>
        <span class="ml-3 text-gray-600">Loading formula data...</span>
      </div>

      <!-- No Data Message -->
      <div v-else-if="!formulas.length" class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 text-center">
        <i class="fas fa-exclamation-triangle text-yellow-500 text-3xl mb-3"></i>
        <h3 class="text-lg font-medium text-yellow-800 mb-2">No Scoring Formula Data Found</h3>
        <p class="text-yellow-700">Please create scoring formulas in the Define Scoring Formula section first.</p>
      </div>

      <!-- Main Content -->
      <div v-else id="printable-content" class="border border-gray-300 rounded-lg bg-gray-100 p-4">
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
                    <tr v-if="formulas.length === 0">
                      <td colspan="2" class="py-4 text-center text-gray-500">No formulas found</td>
                    </tr>
                    <tr v-for="formula in formulas" :key="formula.id" 
                        class="hover:bg-blue-100 cursor-pointer"
                        :class="{ 'bg-blue-100': selectedFormula && selectedFormula.id === formula.id }"
                        @click="selectFormula(formula)">
                      <td class="py-1 px-2 border-b">{{ formula.product_design?.pd_code || 'N/A' }}</td>
                      <td class="py-1 px-2 border-b">{{ formula.paper_flute?.code || 'N/A' }}</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>

            <!-- Right Column: Scoring Length/Width -->
            <div v-if="selectedFormula">
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
                    <tr v-if="!selectedFormula.scoring_length_formula || selectedFormula.scoring_length_formula.length === 0">
                      <td colspan="2" class="py-4 text-center text-gray-500">No length formula data</td>
                    </tr>
                    <tr v-for="(item, index) in selectedFormula.scoring_length_formula" :key="index" class="hover:bg-blue-100">
                      <td class="py-1 px-2 border-b text-center">{{ item.index }}</td>
                      <td class="py-1 px-2 border-b">{{ item.value || '' }}</td>
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
                    <tr v-if="!selectedFormula.scoring_width_formula || selectedFormula.scoring_width_formula.length === 0">
                      <td colspan="2" class="py-4 text-center text-gray-500">No width formula data</td>
                    </tr>
                    <tr v-for="(item, index) in selectedFormula.scoring_width_formula" :key="index" class="hover:bg-blue-100">
                      <td class="py-1 px-2 border-b text-center">{{ item.index }}</td>
                      <td class="py-1 px-2 border-b">{{ item.value || '' }}</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
            <div v-else class="flex items-center justify-center h-full">
              <p class="text-gray-500">Select a formula to view details</p>
            </div>
          </div>

          <!-- Dimension Conversion Formula -->
          <div v-if="selectedFormula" class="mt-6">
            <div class="mb-2 font-bold text-gray-700">— Dimension Conversion Formula —</div>
            <div class="bg-gray-50 border border-gray-300 rounded p-3 text-sm">
              <p class="mb-2">To convert from ordered to internal dimensions or vice-versa</p>
              <div class="flex items-center space-x-2 mb-1 text-gray-800">
                <span class="font-bold">Length:</span>
                <span class="px-2 py-1 bg-white border border-gray-300 rounded">{{ selectedFormula.length_conversion }}</span>
                <span>mm</span>
              </div>
              <div class="flex items-center space-x-2 mb-1 text-gray-800">
                <span class="font-bold">Width:</span>
                <span class="px-2 py-1 bg-white border border-gray-300 rounded">{{ selectedFormula.width_conversion }}</span>
                <span>mm</span>
              </div>
              <div class="flex items-center space-x-2 text-gray-800">
                <span class="font-bold">Height:</span>
                <span class="px-2 py-1 bg-white border border-gray-300 rounded">{{ selectedFormula.height_conversion }}</span>
                <span>mm</span>
              </div>
            </div>
          </div>

          <!-- Scoring Length & Width Formula -->
          <div v-if="selectedFormula" class="mt-6">
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
              <div class="mt-2 text-gray-600" v-if="selectedFormula.notes">
                <p class="font-bold">Notes:</p>
                <p>{{ selectedFormula.notes }}</p>
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
import axios from 'axios';

// State variables
const loading = ref(false);
const notification = ref({ show: false, message: '', type: 'success' });
const formulas = ref([]);
const selectedFormula = ref(null);

// Fetch formula data from API
const fetchFormulaData = async () => {
  loading.value = true;
  try {
    console.log('Fetching scoring formula data...');
    const response = await axios.get('/api/scoring-formulas');
    
    console.log('API Response:', response.data);
    
    if (Array.isArray(response.data)) {
      formulas.value = response.data;
      
      // If we have formulas, select the first one by default
      if (formulas.value.length > 0) {
        selectFormula(formulas.value[0]);
      } else {
        selectedFormula.value = null;
      }
      
      showNotification('Formula data loaded successfully', 'success');
    } else {
      console.error('Unexpected data format:', response.data);
      formulas.value = [];
      selectedFormula.value = null;
      showNotification('Error loading formula data: Invalid data format', 'error');
    }
  } catch (error) {
    console.error('Error fetching formula data:', error);
    formulas.value = [];
    selectedFormula.value = null;
    
    let errorMessage = 'Error loading formula data';
    if (error.response) {
      errorMessage += `: ${error.response.status} ${error.response.statusText}`;
    } else if (error.request) {
      errorMessage += ': No response from server';
    } else {
      errorMessage += `: ${error.message}`;
    }
    
    showNotification(errorMessage, 'error');
  } finally {
    loading.value = false;
  }
};

// Select a formula to display details
const selectFormula = (formula) => {
  console.log('Selected formula:', formula);
  selectedFormula.value = formula;
  
  // Ensure scoring_length_formula and scoring_width_formula are arrays
  if (typeof selectedFormula.value.scoring_length_formula === 'string') {
    try {
      selectedFormula.value.scoring_length_formula = JSON.parse(selectedFormula.value.scoring_length_formula);
    } catch (e) {
      console.error('Error parsing scoring_length_formula:', e);
      selectedFormula.value.scoring_length_formula = [];
    }
  }
  
  if (typeof selectedFormula.value.scoring_width_formula === 'string') {
    try {
      selectedFormula.value.scoring_width_formula = JSON.parse(selectedFormula.value.scoring_width_formula);
    } catch (e) {
      console.error('Error parsing scoring_width_formula:', e);
      selectedFormula.value.scoring_width_formula = [];
    }
  }
};

// Print formula handler
const printFormula = () => {
  if (!selectedFormula.value) {
    showNotification('Please select a formula to print', 'warning');
    return;
  }
  
  const printContent = document.getElementById('printable-content');
  const originalContent = document.body.innerHTML;
  
  document.body.innerHTML = `
    <div style="padding: 20px;">
      <h1 style="text-align: center; margin-bottom: 20px;">Scoring Formula: ${selectedFormula.value.product_design?.pd_code || 'N/A'} - ${selectedFormula.value.paper_flute?.code || 'N/A'}</h1>
      ${printContent.innerHTML}
    </div>
  `;
  
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

// Add this new function
const seedData = async () => {
  if (!confirm('Are you sure you want to seed the database with scoring formula data? This may create duplicate entries if data already exists.')) {
    return;
  }
  
  loading.value = true;
  try {
    const response = await axios.post('/api/scoring-formulas/seed');
    if (response.data.success) {
      showNotification('Data seeded successfully. Refreshing list...', 'success');
      fetchFormulaData(); // Refresh the data after seeding
    } else {
      showNotification(response.data.message || 'Error seeding data.', 'error');
    }
  } catch (error) {
    console.error('Error seeding data:', error);
    showNotification('An error occurred while seeding data: ' + (error.response?.data?.message || error.message), 'error');
  } finally {
    loading.value = false;
  }
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
