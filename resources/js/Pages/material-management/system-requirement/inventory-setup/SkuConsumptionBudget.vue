<template>
  <AppLayout :header="'Define SKU Consumption Budget'">
    <Head title="Define SKU Consumption Budget" />
    <ToastContainer />
    
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-800 p-6 rounded-t-lg shadow-md">
      <h2 class="text-2xl font-bold text-white mb-2 flex items-center">
        <i class="fas fa-chart-line mr-3"></i> Define SKU Consumption Budget
      </h2>
      <p class="text-blue-100">Manage and plan SKU consumption budgets for different periods</p>
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
        
        <div class="flex items-center space-x-4">
          <!-- Options Button -->
          <button @click="showOptionsModal = true" class="btn-info">
            <i class="fas fa-cog mr-2"></i>
            Options
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

          <!-- Effective Month Section -->
          <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Effective Month:</label>
            <div class="flex items-center space-x-3">
              <input 
                type="number" 
                v-model.number="effectiveMonth.month" 
                min="1" 
                max="12"
                class="form-input w-20 text-center"
                placeholder="MM"
              >
              <span class="text-gray-500">/</span>
              <input 
                type="number" 
                v-model.number="effectiveMonth.year" 
                min="2020" 
                max="2030"
                class="form-input w-24 text-center"
                placeholder="YYYY"
              >
              <span class="text-sm text-gray-600">[mm/yyyy]</span>
            </div>
          </div>

          <!-- Record Select Button -->
          <div class="mb-6">
            <button @click="loadBudgetData" :disabled="!canLoadData" class="btn-primary">
              <i class="fas fa-database mr-2"></i>
              Record: Select
            </button>
          </div>

          <!-- SKU Budget Table -->
          <div class="bg-white rounded-lg overflow-hidden border border-gray-200">
            <div class="overflow-x-auto">
              <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                  <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">SKU#</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Effective Month</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Budget Quantity</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actual Quantity</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Variance</th>
                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                  </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                  <tr v-if="loading">
                    <td colspan="7" class="px-4 py-4 text-center text-sm text-gray-500">
                      <i class="fas fa-spinner fa-spin"></i> Loading budget data...
                    </td>
                  </tr>
                  <tr v-else-if="!selectedSku">
                    <td colspan="7" class="px-4 py-4 text-center text-sm text-gray-500">
                      Please select a SKU to view consumption budget.
                    </td>
                  </tr>
                  <tr v-else-if="budgetRows.length === 0">
                    <td colspan="7" class="px-4 py-4 text-center text-sm text-gray-500">
                      No budget data. Click "Record: Select" to load data.
                    </td>
                  </tr>
                  <tr v-for="(row, idx) in budgetRows" :key="row.effective_month" 
                      :class="{'bg-blue-50': row.changed}">
                    <td class="px-4 py-2 text-sm text-gray-700">{{ idx+1 }}</td>
                    <td class="px-4 py-2 text-sm text-gray-700">{{ row.sku_id }}</td>
                    <td class="px-4 py-2 text-sm text-gray-700">{{ row.effective_month }}</td>
                    <td class="px-4 py-2 text-sm">
                      <input type="number" v-model.number="row.budget_quantity" step="0.01" 
                             @input="markAsChanged(row)"
                             class="form-input text-right" />
                    </td>
                    <td class="px-4 py-2 text-sm">
                      <input type="number" v-model.number="row.actual_quantity" step="0.01" 
                             @input="markAsChanged(row)"
                             class="form-input text-right" />
                    </td>
                    <td class="px-4 py-2 text-sm">
                      <span :class="getVarianceClass(row.variance)">
                        {{ formatNumber(row.variance) }}
                      </span>
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
              <h3 class="text-lg font-semibold text-gray-800 mb-3">SKU Details</h3>
              <div class="space-y-3">
                <div class="flex justify-between border-b border-gray-200 pb-2">
                  <span class="text-gray-600">SKU:</span>
                  <span class="font-medium text-gray-900">{{ selectedSku.sku }}</span>
                </div>
                <div class="flex justify-between border-b border-gray-200 pb-2">
                  <span class="text-gray-600">Name:</span>
                  <span class="font-medium text-gray-900 text-right">{{ selectedSku.sku_name }}</span>
                </div>
                <div class="flex justify-between border-b border-gray-200 pb-2">
                  <span class="text-gray-600">Category:</span>
                  <span class="font-medium text-gray-900">{{ selectedSku.category_code }}</span>
                </div>
                <div class="flex justify-between border-b border-gray-200 pb-2">
                  <span class="text-gray-600">Type:</span>
                  <span class="font-medium text-gray-900">{{ selectedSku.type }}</span>
                </div>
                <div class="flex justify-between border-b border-gray-200 pb-2">
                  <span class="text-gray-600">UOM:</span>
                  <span class="font-medium text-gray-900">{{ selectedSku.uom }}</span>
                </div>
              </div>
            </div>

            <!-- Budget Summary -->
            <div v-if="budgetSummary" class="mt-6">
              <h3 class="text-lg font-semibold text-gray-800 mb-3">Budget Summary</h3>
              <div class="space-y-3">
                <div class="flex justify-between border-b border-gray-200 pb-2">
                  <span class="text-gray-600">Total Budget:</span>
                  <span class="font-medium text-gray-900">{{ formatNumber(budgetSummary.total_budget) }}</span>
                </div>
                <div class="flex justify-between border-b border-gray-200 pb-2">
                  <span class="text-gray-600">Total Actual:</span>
                  <span class="font-medium text-gray-900">{{ formatNumber(budgetSummary.total_actual) }}</span>
                </div>
                <div class="flex justify-between border-b border-gray-200 pb-2">
                  <span class="text-gray-600">Total Variance:</span>
                  <span :class="getVarianceClass(budgetSummary.total_variance)" class="font-medium">
                    {{ formatNumber(budgetSummary.total_variance) }}
                  </span>
                </div>
                <div class="flex justify-between border-b border-gray-200 pb-2">
                  <span class="text-gray-600">Budget Count:</span>
                  <span class="font-medium text-gray-900">{{ budgetSummary.budget_count }}</span>
                </div>
              </div>
            </div>
          </div>
          <div v-else class="flex flex-col items-center justify-center h-64 text-center">
            <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mb-4">
              <i class="fas fa-chart-line text-blue-500 text-2xl"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-1">No SKU Selected</h3>
            <p class="text-gray-500 mb-4">Select a SKU to view consumption budget details</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Options Modal -->
    <SkuOptionsModal 
      :show="showOptionsModal"
      @close="showOptionsModal = false"
      @options-applied="handleOptionsApplied"
    />

    <!-- SKU Lookup Modal -->
    <SkuLookupModal 
      :show="showSkuLookup"
      @close="showSkuLookup = false"
      @selected="selectSkuFromLookup"
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
import SkuOptionsModal from '@/Components/SkuOptionsModal.vue';
import SkuLookupModal from '@/Components/SkuLookupModal.vue';

const toast = useToast();
const loading = ref(false);
const skus = ref([]);
const skuSearch = ref('');
const showSkuDropdown = ref(false);
const filteredSkus = ref([]);
const selectedSku = ref(null);
const budgetRows = ref([]);
const budgetSummary = ref(null);
const errors = ref({});
const showOptionsModal = ref(false);
const showSkuLookup = ref(false);

// Effective month input
const effectiveMonth = ref({
  month: new Date().getMonth() + 1,
  year: new Date().getFullYear()
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

const canLoadData = computed(() => {
  return selectedSku.value && effectiveMonth.value.month && effectiveMonth.value.year;
});

const hasChanges = computed(() => {
  return budgetRows.value.some(row => row.changed);
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
  loadBudgetData();
};

const selectSkuFromLookup = (sku) => {
  selectSku(sku);
  showSkuLookup.value = false;
};

const clearSku = () => {
  selectedSku.value = null;
  skuSearch.value = '';
  budgetRows.value = [];
  budgetSummary.value = null;
  currentSkuIndex.value = -1;
};

const refreshData = () => {
  if (selectedSku.value) {
    loadBudgetData();
  }
};

const loadBudgetData = async () => {
  if (!canLoadData.value) {
    toast.error('Please select a SKU and enter effective month');
    return;
  }

  loading.value = true;
  try {
    const effectiveMonthStr = `${effectiveMonth.value.month.toString().padStart(2, '0')}/${effectiveMonth.value.year}`;
    const res = await axios.get(`/api/material-management/system-requirement/inventory-setup/sku-consumption-budgets/by-sku/${selectedSku.value.sku}`);
    
    let data = res.data;
    if (!data.length) {
      // Generate 12 months ahead if no data
      const now = new Date();
      data = Array.from({ length: 12 }, (_, i) => {
        const d = new Date(now.getFullYear(), now.getMonth() + i, 1);
        return {
          id: null,
          sku_id: selectedSku.value.sku,
          effective_month: (d.getMonth() + 1).toString().padStart(2, '0') + '/' + d.getFullYear(),
          budget_quantity: 0,
          actual_quantity: 0,
          variance: 0,
          notes: '',
          saving: false,
          changed: false,
        };
      });
    } else {
      data = data.map(row => ({ ...row, saving: false, changed: false }));
    }
    budgetRows.value = data;
    
    // Load budget summary
    await loadBudgetSummary();
    
  } catch (e) {
    toast.error('Failed to load budget data');
    budgetRows.value = [];
  } finally {
    loading.value = false;
  }
};

const loadBudgetSummary = async () => {
  if (!selectedSku.value) return;
  
  try {
    const res = await axios.get('/api/material-management/system-requirement/inventory-setup/sku-consumption-budgets/summary', {
      params: { sku_id: selectedSku.value.sku }
    });
    budgetSummary.value = res.data;
  } catch (e) {
    console.error('Failed to load budget summary:', e);
  }
};

const markAsChanged = (row) => {
  row.changed = true;
  row.variance = row.budget_quantity - row.actual_quantity;
};

const saveRow = async (row, idx) => {
  row.saving = true;
  try {
    let res;
    if (row.id) {
      res = await axios.put(`/api/material-management/system-requirement/inventory-setup/sku-consumption-budgets/${row.id}`, {
        budget_quantity: row.budget_quantity,
        actual_quantity: row.actual_quantity,
        notes: row.notes,
      });
    } else {
      res = await axios.post(`/api/material-management/system-requirement/inventory-setup/sku-consumption-budgets`, {
        sku_id: row.sku_id,
        effective_month: row.effective_month,
        budget_quantity: row.budget_quantity,
        actual_quantity: row.actual_quantity,
        notes: row.notes,
      });
    }
    budgetRows.value[idx] = { ...res.data, saving: false, changed: false };
    toast.success('Budget saved');
    await loadBudgetSummary();
  } catch (e) {
    toast.error('Failed to save budget');
    row.saving = false;
  }
};

const deleteRow = async (row, idx) => {
  if (!row.id) return;
  row.saving = true;
  try {
    await axios.delete(`/api/material-management/system-requirement/inventory-setup/sku-consumption-budgets/${row.id}`);
    budgetRows.value.splice(idx, 1);
    toast.success('Budget deleted');
    await loadBudgetSummary();
  } catch (e) {
    toast.error('Failed to delete budget');
    row.saving = false;
  }
};

const saveAllChanges = async () => {
  const changedRows = budgetRows.value.filter(row => row.changed);
  if (changedRows.length === 0) {
    toast.info('No changes to save');
    return;
  }

  loading.value = true;
  try {
    for (const row of changedRows) {
      await saveRow(row, budgetRows.value.indexOf(row));
    }
    toast.success(`Saved ${changedRows.length} changes`);
  } catch (e) {
    toast.error('Failed to save some changes');
  } finally {
    loading.value = false;
  }
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
  // Navigate to the View & Print page
  window.location.href = '/material-management/system-requirement/inventory-setup/sku-consumption-budget/view-print';
};

const handleOptionsApplied = (options) => {
  // Handle options applied from modal
  console.log('Options applied:', options);
  toast.info('Options applied successfully');
};

// Utility methods
const formatNumber = (num) => {
  return new Intl.NumberFormat('en-US', { minimumFractionDigits: 2 }).format(num);
};

const getVarianceClass = (variance) => {
  if (variance > 0) return 'text-green-600 font-medium';
  if (variance < 0) return 'text-red-600 font-medium';
  return 'text-gray-600';
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
