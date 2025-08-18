<template>
  <AppLayout :header="'Define SKU Reorder Level'">
    <Head title="Define SKU Reorder Level" />
    <ToastContainer />
    
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-800 p-6 rounded-t-lg shadow-md">
      <h2 class="text-2xl font-bold text-white mb-2 flex items-center">
        <i class="fas fa-level-down-alt mr-3"></i> Define SKU Reorder Level
      </h2>
      <p class="text-blue-100">Set reorder, min, and max level for each SKU and period</p>
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
            <button @click="previousSku" :disabled="!canNavigatePrevious" class="btn-secondary">
              <i class="fas fa-chevron-left"></i>
            </button>
            <button @click="nextSku" :disabled="!canNavigateNext" class="btn-secondary">
              <i class="fas fa-chevron-right"></i>
            </button>
          </div>
          
          <!-- Save Button -->
          <button @click="saveAllChanges" :disabled="!hasChanges" class="btn-success">
            <i class="fas fa-save mr-2"></i>
            Save
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
        <!-- SKU Input Section -->
        <div class="flex-1">
          <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">SKU#:</label>
            <div class="flex items-center space-x-2">
              <div class="relative flex-1">
                <input 
                  type="text" 
                  v-model="skuSearch" 
                  @input="filterSkus" 
                  @focus="showSkuDropdown = true"
                  placeholder="Enter SKU code or search..."
                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                >
                <div v-if="showSkuDropdown && filteredSkus.length" 
                     class="absolute z-10 bg-white border w-full mt-1 rounded shadow max-h-60 overflow-y-auto">
                  <div v-for="sku in filteredSkus" :key="sku.sku" 
                       @click="selectSku(sku)"
                       class="px-4 py-2 hover:bg-blue-100 cursor-pointer">
                    <div class="font-medium">{{ sku.sku }}</div>
                    <div class="text-sm text-gray-600">{{ sku.sku_name }}</div>
                  </div>
                </div>
              </div>
              
              <!-- Lookup Button -->
              <button @click="showSkuLookup = true" class="btn-info px-3 py-2">
                <i class="fas fa-search"></i>
              </button>
              
              <!-- Clear Button -->
              <button @click="clearSku" class="btn-secondary px-3 py-2">
                <i class="fas fa-times"></i>
              </button>
            </div>
          </div>

          <!-- SKU Details Section -->
          <div v-if="selectedSku" class="mb-6 bg-gray-50 p-4 rounded-lg">
            <h3 class="text-lg font-semibold text-gray-800 mb-3">SKU Details</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
              <div>
                <span class="text-gray-600">Category:</span>
                <span class="font-medium ml-2">{{ selectedSku.category_code }}</span>
              </div>
              <div>
                <span class="text-gray-600">Type:</span>
                <span class="font-medium ml-2">{{ selectedSku.type }}</span>
              </div>
              <div>
                <span class="text-gray-600">UOM:</span>
                <span class="font-medium ml-2">{{ selectedSku.uom }}</span>
              </div>
              <div>
                <span class="text-gray-600">BOH:</span>
                <span class="font-medium ml-2">{{ selectedSku.boh || 0 }}</span>
              </div>
            </div>
          </div>

          <!-- Period Table Section -->
          <div class="bg-white rounded-lg overflow-hidden border border-gray-200">
            <div class="overflow-x-auto">
              <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                  <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Period</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Min Level</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Max Level</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Reorder Level</th>
                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                  </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                  <tr v-if="loading">
                    <td colspan="6" class="px-4 py-4 text-center text-sm text-gray-500">
                      <i class="fas fa-spinner fa-spin"></i> Loading reorder levels...
                    </td>
                  </tr>
                  <tr v-else-if="!selectedSku">
                    <td colspan="6" class="px-4 py-4 text-center text-sm text-gray-500">
                      Please select a SKU to view reorder levels.
                    </td>
                  </tr>
                  <tr v-else-if="periodRows.length === 0">
                    <td colspan="6" class="px-4 py-4 text-center text-sm text-gray-500">
                      No period data. Try refresh or select another SKU.
                    </td>
                  </tr>
                  <tr v-for="(row, idx) in periodRows" :key="row.period" 
                      :class="{'bg-blue-50': row.changed}">
                    <td class="px-4 py-2 text-sm text-gray-700">{{ idx+1 }}</td>
                    <td class="px-4 py-2 text-sm text-gray-700">{{ row.period }}</td>
                    <td class="px-4 py-2 text-sm">
                      <input type="number" v-model.number="row.min_level" step="0.01" 
                             @input="markAsChanged(row)"
                             class="form-input text-right" />
                    </td>
                    <td class="px-4 py-2 text-sm">
                      <input type="number" v-model.number="row.max_level" step="0.01" 
                             @input="markAsChanged(row)"
                             class="form-input text-right" />
                    </td>
                    <td class="px-4 py-2 text-sm">
                      <input type="number" v-model.number="row.reorder_level" step="0.01" 
                             @input="markAsChanged(row)"
                             class="form-input text-right" />
                    </td>
                    <td class="px-4 py-2 text-center">
                      <button @click="saveRow(row, idx)" class="btn-blue px-3 py-1 text-xs" :disabled="row.saving">
                        <i v-if="row.saving" class="fas fa-spinner fa-spin"></i>
                        <span v-else>Save</span>
                      </button>
                      <button v-if="row.id" @click="deleteRow(row, idx)" class="btn-danger px-3 py-1 text-xs ml-2" :disabled="row.saving">
                        <i class="fas fa-trash-alt"></i>
                      </button>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
        
        <!-- Side Panel with Additional Details -->
        <div class="w-full lg:w-80 bg-gray-50 rounded-lg p-4 border border-gray-200">
          <div v-if="selectedSku" class="space-y-4">
            <div>
              <h3 class="text-lg font-semibold text-gray-800 mb-3">Additional Details</h3>
              <div class="space-y-3">
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Min Qty:</label>
                  <input type="number" v-model="additionalDetails.min_qty" step="0.01" class="form-input" />
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Max Qty:</label>
                  <input type="number" v-model="additionalDetails.max_qty" step="0.01" class="form-input" />
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Part Number 1:</label>
                  <input type="text" v-model="additionalDetails.part_number_1" class="form-input" />
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Part Number 2:</label>
                  <input type="text" v-model="additionalDetails.part_number_2" class="form-input" />
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Part Number 3:</label>
                  <input type="text" v-model="additionalDetails.part_number_3" class="form-input" />
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">SKU Additional Name:</label>
                  <textarea v-model="additionalDetails.sku_additional_name" rows="3" class="form-input"></textarea>
                </div>
              </div>
            </div>
            <div class="flex justify-end mt-4">
              <button @click="saveAdditionalDetails" :disabled="savingAdditional" class="btn-success w-full">
                <i v-if="savingAdditional" class="fas fa-spinner fa-spin mr-2"></i>
                <span v-else>Save Additional Details</span>
              </button>
            </div>
          </div>
          <div v-else class="flex flex-col items-center justify-center h-64 text-center">
            <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mb-4">
              <i class="fas fa-boxes text-blue-500 text-2xl"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-1">No SKU Selected</h3>
            <p class="text-gray-500 mb-4">Select a SKU to view additional details</p>
          </div>
        </div>
      </div>
    </div>

    <!-- SKU Lookup Modal -->
    <SkuLookupModal 
      :show="showSkuLookup"
      @close="showSkuLookup = false"
      @skuSelected="selectSkuFromLookup"
    />
  </AppLayout>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue';
import axios from 'axios';
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import { useToast } from '@/Composables/useToast';
import ToastContainer from '@/Components/ToastContainer.vue';
import SkuLookupModal from '@/Components/SkuLookupModal.vue';

const toast = useToast();
const loading = ref(false);
const skus = ref([]);
const skuSearch = ref('');
const showSkuDropdown = ref(false);
const filteredSkus = ref([]);
const selectedSku = ref(null);
const periodRows = ref([]);
const errors = ref({});
const showSkuLookup = ref(false);

// Additional details for the side panel
const additionalDetails = ref({
  min_qty: 0,
  max_qty: 0,
  part_number_1: '',
  part_number_2: '',
  part_number_3: '',
  sku_additional_name: ''
});

// Navigation state
const currentSkuIndex = ref(-1);

// Computed properties
const canNavigatePrevious = computed(() => {
  return currentSkuIndex.value > 0;
});

const canNavigateNext = computed(() => {
  return currentSkuIndex.value < skus.value.length - 1;
});

const hasChanges = computed(() => {
  return periodRows.value.some(row => row.changed);
});

// Fetch all SKUs for dropdown
const fetchSkus = async () => {
  try {
    const res = await axios.get('/api/material-management/skus');
    skus.value = res.data;
    filteredSkus.value = res.data;
  } catch (e) {
    toast.error('Failed to load SKUs');
  }
};

const filterSkus = () => {
  if (!skuSearch.value) {
    filteredSkus.value = skus.value;
    showSkuDropdown.value = false;
    return;
  }
  const q = skuSearch.value.toLowerCase();
  filteredSkus.value = skus.value.filter(sku =>
    (sku.sku && sku.sku.toLowerCase().includes(q)) ||
    (sku.sku_name && sku.sku_name.toLowerCase().includes(q))
  );
  showSkuDropdown.value = true;
};

const selectSku = (sku) => {
  selectedSku.value = sku;
  skuSearch.value = sku.sku + ' - ' + sku.sku_name;
  showSkuDropdown.value = false;
  currentSkuIndex.value = skus.value.findIndex(s => s.sku === sku.sku);
  fetchReorderLevels(sku.sku);
  loadAdditionalDetails(sku.sku);
};

const selectSkuFromLookup = (sku) => {
  selectSku(sku);
  showSkuLookup.value = false;
};

const clearSku = () => {
  selectedSku.value = null;
  skuSearch.value = '';
  periodRows.value = [];
  additionalDetails.value = {
    min_qty: 0,
    max_qty: 0,
    part_number_1: '',
    part_number_2: '',
    part_number_3: '',
    sku_additional_name: ''
  };
  currentSkuIndex.value = -1;
};

const refreshData = () => {
  if (selectedSku.value) {
    fetchReorderLevels(selectedSku.value.sku);
    loadAdditionalDetails(selectedSku.value.sku);
  }
};

const fetchReorderLevels = async (skuId) => {
  loading.value = true;
  try {
    const res = await axios.get(`/api/material-management/system-requirement/inventory-setup/sku-reorder-levels/by-sku/${skuId}`);
    let data = res.data;
    if (!data.length) {
      // Generate 12 months ahead if no data
      const now = new Date();
      data = Array.from({ length: 12 }, (_, i) => {
        const d = new Date(now.getFullYear(), now.getMonth() + i, 1);
        return {
          id: null,
          sku_id: skuId,
          period: (d.getMonth() + 1).toString().padStart(2, '0') + '/' + d.getFullYear(),
          min_level: 0,
          max_level: 0,
          reorder_level: 0,
          saving: false,
          changed: false,
        };
      });
    } else {
      data = data.map(row => ({ ...row, saving: false, changed: false }));
    }
    periodRows.value = data;
  } catch (e) {
    toast.error('Failed to load reorder levels');
    periodRows.value = [];
  } finally {
    loading.value = false;
  }
};

const loadAdditionalDetails = async (skuId) => {
  try {
    const res = await axios.get(`/api/material-management/skus/${skuId}`);
    const sku = res.data;
    additionalDetails.value = {
      min_qty: sku.min_qty || 0,
      max_qty: sku.max_qty || 0,
      part_number_1: sku.part_number1 || '',
      part_number_2: sku.part_number2 || '',
      part_number_3: sku.part_number3 || '',
      sku_additional_name: sku.additional_name || ''
    };
  } catch (e) {
    toast.error('Failed to load additional details');
    additionalDetails.value = {
      min_qty: 0,
      max_qty: 0,
      part_number_1: '',
      part_number_2: '',
      part_number_3: '',
      sku_additional_name: ''
    };
  }
};

const markAsChanged = (row) => {
  row.changed = true;
};

const saveRow = async (row, idx) => {
  const validationError = validateRow(row);
  if (validationError) {
    toast.error(validationError);
    return;
  }
  row.saving = true;
  try {
    let res;
    if (row.id) {
      res = await axios.put(`/api/material-management/system-requirement/inventory-setup/sku-reorder-levels/${row.id}`, {
        min_level: row.min_level,
        max_level: row.max_level,
        reorder_level: row.reorder_level,
      });
    } else {
      res = await axios.post(`/api/material-management/system-requirement/inventory-setup/sku-reorder-levels`, {
        sku_id: row.sku_id,
        period: row.period,
        min_level: row.min_level,
        max_level: row.max_level,
        reorder_level: row.reorder_level,
      });
    }
    periodRows.value[idx] = { ...res.data, saving: false, changed: false };
    toast.success('Reorder level saved');
  } catch (e) {
    toast.error('Failed to save reorder level');
    row.saving = false;
  }
};

const deleteRow = async (row, idx) => {
  if (!row.id) return;
  row.saving = true;
  try {
    await axios.delete(`/api/material-management/system-requirement/inventory-setup/sku-reorder-levels/${row.id}`);
    periodRows.value.splice(idx, 1);
    toast.success('Reorder level deleted');
  } catch (e) {
    toast.error('Failed to delete reorder level');
    row.saving = false;
  }
};

const saveAllChanges = async () => {
  const changedRows = periodRows.value.filter(row => row.changed);
  if (changedRows.length === 0) {
    toast.info('No changes to save');
    return;
  }
  // Validate all rows first
  for (const row of changedRows) {
    const validationError = validateRow(row);
    if (validationError) {
      toast.error(`Row ${row.period}: ${validationError}`);
      return;
    }
  }
  loading.value = true;
  try {
    for (const row of changedRows) {
      await saveRow(row, periodRows.value.indexOf(row));
    }
    toast.success(`Saved ${changedRows.length} changes`);
  } catch (e) {
    toast.error('Failed to save some changes');
  } finally {
    loading.value = false;
  }
};

const savingAdditional = ref(false);
const saveAdditionalDetails = async () => {
  if (!selectedSku.value) return;
  savingAdditional.value = true;
  try {
    await axios.put(`/api/material-management/skus/${selectedSku.value.sku}`, {
      min_qty: additionalDetails.value.min_qty,
      max_qty: additionalDetails.value.max_qty,
      part_number1: additionalDetails.value.part_number_1,
      part_number2: additionalDetails.value.part_number_2,
      part_number3: additionalDetails.value.part_number_3,
      additional_name: additionalDetails.value.sku_additional_name
    });
    toast.success('Additional details saved');
  } catch (e) {
    toast.error('Failed to save additional details');
  } finally {
    savingAdditional.value = false;
  }
};

const validateRow = (row) => {
  if (row.min_level > row.reorder_level) return 'Min Level must be <= Reorder Level';
  if (row.reorder_level > row.max_level) return 'Reorder Level must be <= Max Level';
  return '';
};

// Navigation methods
const previousSku = () => {
  if (canNavigatePrevious.value) {
    const prevSku = skus.value[currentSkuIndex.value - 1];
    selectSku(prevSku);
  }
};

const nextSku = () => {
  if (canNavigateNext.value) {
    const nextSku = skus.value[currentSkuIndex.value + 1];
    selectSku(nextSku);
  }
};

// Action methods
const printReport = () => {
  if (!selectedSku.value) {
    toast.error('Please select a SKU first');
    return;
  }
  
  // Open print window with reorder level data
  const printWindow = window.open('', '_blank');
  printWindow.document.write(`
    <html>
      <head>
        <title>SKU Reorder Level Report - ${selectedSku.value.sku}</title>
        <style>
          body { font-family: Arial, sans-serif; margin: 20px; }
          table { width: 100%; border-collapse: collapse; margin-top: 20px; }
          th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
          th { background-color: #f2f2f2; }
          .header { text-align: center; margin-bottom: 20px; }
        </style>
      </head>
      <body>
        <div class="header">
          <h1>SKU Reorder Level Report</h1>
          <h2>${selectedSku.value.sku} - ${selectedSku.value.sku_name}</h2>
          <p>Generated on: ${new Date().toLocaleDateString()}</p>
        </div>
        <table>
          <thead>
            <tr>
              <th>Period</th>
              <th>Min Level</th>
              <th>Max Level</th>
              <th>Reorder Level</th>
            </tr>
          </thead>
          <tbody>
            ${periodRows.value.map(row => `
              <tr>
                <td>${row.period}</td>
                <td>${row.min_level}</td>
                <td>${row.max_level}</td>
                <td>${row.reorder_level}</td>
              </tr>
            `).join('')}
          </tbody>
        </table>
      </body>
    </html>
  `);
  printWindow.document.close();
  printWindow.print();
};

onMounted(fetchSkus);
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
tbody tr { transition: all 0.2s; }
tbody tr:hover { transform: translateY(-2px); box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
.overflow-x-auto, .overflow-y-auto { scrollbar-width: thin; scrollbar-color: rgba(156,163,175,0.5) transparent; }
.overflow-x-auto::-webkit-scrollbar, .overflow-y-auto::-webkit-scrollbar { height: 8px; width: 8px; }
.overflow-x-auto::-webkit-scrollbar-track, .overflow-y-auto::-webkit-scrollbar-track { background: transparent; }
.overflow-x-auto::-webkit-scrollbar-thumb, .overflow-y-auto::-webkit-scrollbar-thumb { background-color: rgba(156,163,175,0.5); border-radius: 20px; }
.form-input { @apply w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200; }
</style>
