<template>
  <AppLayout :header="'Define Custom Tariff Code'">
    <Head title="Define Custom Tariff Code" />
    <ToastContainer />
    
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-800 p-6 rounded-t-lg shadow-md">
      <h2 class="text-2xl font-bold text-white mb-2 flex items-center">
        <i class="fas fa-file-invoice-dollar mr-3"></i> Define Custom Tariff Code
      </h2>
      <p class="text-blue-100">Manage HS Codes for import/export customs documentation and duty calculations</p>
    </div>

    <!-- Toolbar Section -->
    <div class="bg-white border-b border-gray-200 px-6 py-3">
      <div class="flex items-center justify-between">
        <div class="flex items-center space-x-4">
          <!-- Exit Button -->
          <button @click="$router.back()" class="btn-danger">
            <i class="fas fa-power-off mr-2"></i>
            Exit
          </button>
          
          <!-- Navigation Buttons -->
          <div class="flex items-center space-x-2">
            <button @click="previousRecord" :disabled="!canNavigatePrevious" class="btn-secondary">
              <i class="fas fa-chevron-left"></i>
            </button>
            <button @click="nextRecord" :disabled="!canNavigateNext" class="btn-secondary">
              <i class="fas fa-chevron-right"></i>
            </button>
          </div>
          
          <!-- Save Button -->
          <button @click="saveRecord" :disabled="!hasChanges" class="btn-success">
            <i class="fas fa-save mr-2"></i>
            Save
          </button>
          
          <!-- New Record Button -->
          <button @click="newRecord" class="btn-info">
            <i class="fas fa-plus mr-2"></i>
            New
          </button>
          
          <!-- Print Button -->
          <button @click="printReport" class="btn-secondary">
            <i class="fas fa-print mr-2"></i>
            Print
          </button>
          
          <!-- Refresh Button -->
          <button @click="refreshData" class="btn-secondary">
            <i class="fas fa-sync-alt mr-2"></i>
            Refresh
          </button>
        </div>
      </div>
    </div>

    <!-- Main Content Area -->
    <div class="bg-white rounded-b-lg shadow-md p-6 mb-6">
      <div class="flex flex-col lg:flex-row gap-6">
        <!-- Tariff Code Input Section -->
        <div class="flex-1">
          <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Tariff Code:</label>
            <div class="flex items-center space-x-2">
              <div class="relative flex-1">
                <input 
                  type="text" 
                  v-model="tariffCode.code" 
                  placeholder="Enter HS Code (e.g., 12345678)"
                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                  :class="{ 'border-red-500': errors.code }"
                >
                <div v-if="errors.code" class="text-red-500 text-sm mt-1">{{ errors.code }}</div>
              </div>
              
              <!-- Record Select Button -->
              <button @click="loadRecord" class="btn-primary">
                <i class="fas fa-database mr-2"></i>
                Record: Select
              </button>
            </div>
          </div>

          <!-- Tariff Code Details Form -->
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Name -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Name:</label>
              <input 
                type="text" 
                v-model="tariffCode.name" 
                placeholder="Enter tariff code name"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                :class="{ 'border-red-500': errors.name }"
              >
              <div v-if="errors.name" class="text-red-500 text-sm mt-1">{{ errors.name }}</div>
            </div>

            <!-- Category -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Category:</label>
              <input 
                type="text" 
                v-model="tariffCode.category" 
                placeholder="Enter category"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
              >
            </div>

            <!-- Duty Rate -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Duty Rate (%):</label>
              <input 
                type="number" 
                v-model.number="tariffCode.duty_rate" 
                step="0.01"
                min="0"
                max="100"
                placeholder="0.00"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                :class="{ 'border-red-500': errors.duty_rate }"
              >
              <div v-if="errors.duty_rate" class="text-red-500 text-sm mt-1">{{ errors.duty_rate }}</div>
            </div>

            <!-- Tax Rate -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Tax Rate (%):</label>
              <input 
                type="number" 
                v-model.number="tariffCode.tax_rate" 
                step="0.01"
                min="0"
                max="100"
                placeholder="0.00"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                :class="{ 'border-red-500': errors.tax_rate }"
              >
              <div v-if="errors.tax_rate" class="text-red-500 text-sm mt-1">{{ errors.tax_rate }}</div>
            </div>

            <!-- Country Origin -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Country Origin:</label>
              <input 
                type="text" 
                v-model="tariffCode.country_origin" 
                placeholder="Enter country of origin"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
              >
            </div>

            <!-- Active Status -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Status:</label>
              <div class="flex items-center">
                <input 
                  type="checkbox" 
                  v-model="tariffCode.is_active"
                  class="mr-2 h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                >
                <span class="text-sm text-gray-700">Active</span>
              </div>
            </div>
          </div>

          <!-- Description -->
          <div class="mt-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Description:</label>
            <textarea 
              v-model="tariffCode.description" 
              rows="3"
              placeholder="Enter detailed description"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
            ></textarea>
          </div>

          <!-- Notes -->
          <div class="mt-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Notes:</label>
            <textarea 
              v-model="tariffCode.notes" 
              rows="2"
              placeholder="Enter additional notes"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
            ></textarea>
          </div>

          <!-- Action Buttons -->
          <div class="mt-6 flex space-x-4">
            <button @click="saveRecord" :disabled="!hasChanges || saving" class="btn-success">
              <i v-if="saving" class="fas fa-spinner fa-spin mr-2"></i>
              <i v-else class="fas fa-save mr-2"></i>
              {{ saving ? 'Saving...' : 'Save' }}
            </button>
            <button @click="newRecord" class="btn-info">
              <i class="fas fa-plus mr-2"></i>
              New Record
            </button>
            <button v-if="tariffCode.id" @click="deleteRecord" class="btn-danger">
              <i class="fas fa-trash mr-2"></i>
              Delete
            </button>
          </div>
        </div>
        
        <!-- Side Panel with Additional Details -->
        <div class="w-full lg:w-80 bg-gray-50 rounded-lg p-4 border border-gray-200">
          <div class="space-y-4">
            <!-- Current Record Info -->
            <div>
              <h3 class="text-lg font-semibold text-gray-800 mb-3">Current Record</h3>
              <div class="space-y-3">
                <div class="flex justify-between border-b border-gray-200 pb-2">
                  <span class="text-gray-600">Code:</span>
                  <span class="font-medium text-gray-900">{{ tariffCode.code || 'Not set' }}</span>
                </div>
                <div class="flex justify-between border-b border-gray-200 pb-2">
                  <span class="text-gray-600">Name:</span>
                  <span class="font-medium text-gray-900 text-right">{{ tariffCode.name || 'Not set' }}</span>
                </div>
                <div class="flex justify-between border-b border-gray-200 pb-2">
                  <span class="text-gray-600">Total Rate:</span>
                  <span class="font-medium text-gray-900">{{ formatNumber(getTotalRate()) }}%</span>
                </div>
                <div class="flex justify-between border-b border-gray-200 pb-2">
                  <span class="text-gray-600">Status:</span>
                  <span :class="tariffCode.is_active ? 'text-green-600' : 'text-red-600'" class="font-medium">
                    {{ tariffCode.is_active ? 'Active' : 'Inactive' }}
                  </span>
                </div>
              </div>
            </div>

            <!-- Summary -->
            <div v-if="summary" class="mt-6">
              <h3 class="text-lg font-semibold text-gray-800 mb-3">Summary</h3>
              <div class="space-y-3">
                <div class="flex justify-between border-b border-gray-200 pb-2">
                  <span class="text-gray-600">Total Codes:</span>
                  <span class="font-medium text-gray-900">{{ summary.total_codes }}</span>
                </div>
                <div class="flex justify-between border-b border-gray-200 pb-2">
                  <span class="text-gray-600">Active Codes:</span>
                  <span class="font-medium text-green-600">{{ summary.active_codes }}</span>
                </div>
                <div class="flex justify-between border-b border-gray-200 pb-2">
                  <span class="text-gray-600">Categories:</span>
                  <span class="font-medium text-gray-900">{{ summary.categories }}</span>
                </div>
                <div class="flex justify-between border-b border-gray-200 pb-2">
                  <span class="text-gray-600">Avg Duty Rate:</span>
                  <span class="font-medium text-gray-900">{{ formatNumber(summary.avg_duty_rate) }}%</span>
                </div>
                <div class="flex justify-between border-b border-gray-200 pb-2">
                  <span class="text-gray-600">Avg Tax Rate:</span>
                  <span class="font-medium text-gray-900">{{ formatNumber(summary.avg_tax_rate) }}%</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import axios from 'axios';
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import { useToast } from '@/Composables/useToast';
import ToastContainer from '@/Components/ToastContainer.vue';

const toast = useToast();
const saving = ref(false);
const summary = ref(null);
const errors = ref({});

// Current record state
const tariffCode = ref({
  id: null,
  code: '',
  name: '',
  description: '',
  duty_rate: 0,
  tax_rate: 0,
  category: '',
  country_origin: '',
  is_active: true,
  notes: '',
  created_by: '',
  updated_by: ''
});

// Navigation state
const currentIndex = ref(-1);

// Computed properties
const canNavigatePrevious = computed(() => {
  return currentIndex.value > 0;
});

const canNavigateNext = computed(() => {
  return currentIndex.value < 0; // Simplified for now
});

const hasChanges = computed(() => {
  return tariffCode.value.id === null || 
         tariffCode.value.code !== '' ||
         tariffCode.value.name !== '' ||
         tariffCode.value.description !== '' ||
         tariffCode.value.duty_rate !== 0 ||
         tariffCode.value.tax_rate !== 0 ||
         tariffCode.value.category !== '' ||
         tariffCode.value.country_origin !== '' ||
         tariffCode.value.notes !== '';
});

const loadRecord = async () => {
  if (!tariffCode.value.code) {
    toast.error('Please enter a tariff code');
    return;
  }

  try {
    const res = await axios.get(`/api/material-management/system-requirement/inventory-setup/custom-tariff-codes/suggestions`, {
      params: { search: tariffCode.value.code }
    });
    
    if (res.data.length > 0) {
      const code = res.data[0];
      tariffCode.value = { ...code };
      toast.success('Record loaded successfully');
    } else {
      toast.info('No record found. You can create a new one.');
    }
  } catch (e) {
    toast.error('Failed to load record');
  }
};

const newRecord = () => {
  tariffCode.value = {
    id: null,
    code: '',
    name: '',
    description: '',
    duty_rate: 0,
    tax_rate: 0,
    category: '',
    country_origin: '',
    is_active: true,
    notes: '',
    created_by: '',
    updated_by: ''
  };
  currentIndex.value = -1;
  errors.value = {};
};

const saveRecord = async () => {
  saving.value = true;
  errors.value = {};

  try {
    let res;
    if (tariffCode.value.id) {
      res = await axios.put(`/api/material-management/system-requirement/inventory-setup/custom-tariff-codes/${tariffCode.value.id}`, tariffCode.value);
    } else {
      res = await axios.post('/api/material-management/system-requirement/inventory-setup/custom-tariff-codes', tariffCode.value);
    }
    
    tariffCode.value = { ...res.data };
    toast.success('Tariff code saved successfully');
    loadSummary();
  } catch (e) {
    if (e.response?.data?.errors) {
      errors.value = e.response.data.errors;
    } else {
      toast.error('Failed to save tariff code');
    }
  } finally {
    saving.value = false;
  }
};

const deleteRecord = async () => {
  if (!tariffCode.value.id) return;
  
  if (!confirm('Are you sure you want to delete this tariff code?')) return;
  
  try {
    await axios.delete(`/api/material-management/system-requirement/inventory-setup/custom-tariff-codes/${tariffCode.value.id}`);
    toast.success('Tariff code deleted successfully');
    newRecord();
    loadSummary();
  } catch (e) {
    toast.error('Failed to delete tariff code');
  }
};

// Navigation methods
const previousRecord = () => {
  // Simplified navigation
  toast.info('Navigation not implemented yet');
};

const nextRecord = () => {
  // Simplified navigation
  toast.info('Navigation not implemented yet');
};

const loadSummary = async () => {
  try {
    const res = await axios.get('/api/material-management/system-requirement/inventory-setup/custom-tariff-codes/summary');
    summary.value = res.data;
  } catch (e) {
    console.error('Failed to load summary:', e);
  }
};

// Action methods
const refreshData = () => {
  loadSummary();
};

const printReport = () => {
  // Navigate to the View & Print page
  window.location.href = '/material-management/system-requirement/inventory-setup/custom-tariff-code/view-print';
};

// Utility methods
const formatNumber = (num) => {
  return new Intl.NumberFormat('en-US', { minimumFractionDigits: 2 }).format(num);
};

const getTotalRate = () => {
  return (tariffCode.value.duty_rate || 0) + (tariffCode.value.tax_rate || 0);
};

onMounted(() => {
  loadSummary();
});
</script>

<style scoped>
.btn-primary {
  @apply bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg shadow transition flex items-center justify-center whitespace-nowrap;
}
.btn-secondary {
  @apply bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg shadow transition flex items-center justify-center whitespace-nowrap;
}
.btn-info {
  @apply bg-cyan-600 hover:bg-cyan-700 text-white px-4 py-2 rounded-lg shadow transition flex items-center justify-center whitespace-nowrap;
}
.btn-danger {
  @apply bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg shadow transition flex items-center justify-center whitespace-nowrap;
}
.btn-blue {
  @apply bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg shadow transition flex items-center justify-center whitespace-nowrap;
}
.btn-success {
  @apply bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg shadow transition flex items-center justify-center whitespace-nowrap;
}
</style>
