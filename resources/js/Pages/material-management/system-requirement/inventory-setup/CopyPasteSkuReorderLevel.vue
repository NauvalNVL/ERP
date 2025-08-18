<template>
  <AppLayout :header="'Copy & Paste SKU Reorder Level'">
    <Head title="Copy & Paste SKU Reorder Level" />
    <ToastContainer />
    
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-800 p-6 rounded-t-lg shadow-md">
      <h2 class="text-2xl font-bold text-white mb-2 flex items-center">
        <i class="fas fa-copy mr-3"></i> Copy & Paste SKU Reorder Level
      </h2>
      <p class="text-blue-100">Copy reorder levels between SKUs, periods, and locations</p>
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
          
          <!-- Process Button -->
          <button @click="executeCopy" :disabled="!canExecute || processing" class="btn-success">
            <i v-if="processing" class="fas fa-spinner fa-spin mr-2"></i>
            <i v-else class="fas fa-arrow-right mr-2"></i>
            {{ processing ? 'Processing...' : 'Process' }}
          </button>
        </div>
        
        <div class="flex items-center space-x-4">
          <!-- Refresh Button -->
          <button @click="refreshData" class="btn-secondary">
            <i class="fas fa-sync-alt mr-2"></i>
            Refresh
          </button>
          
          <!-- Help Button -->
          <button @click="showHelp = true" class="btn-info">
            <i class="fas fa-question-circle mr-2"></i>
            Help
          </button>
        </div>
      </div>
    </div>

    <!-- Main Content -->
    <div class="bg-white rounded-b-lg shadow-md p-6 mb-6">
      <div class="max-w-4xl mx-auto">
        <!-- Input Fields Section -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
          
          <!-- Copy Period Section -->
          <div class="bg-blue-50 p-6 rounded-lg border border-blue-200">
            <h3 class="text-lg font-semibold text-blue-800 mb-4 flex items-center">
              <i class="fas fa-copy mr-2"></i>
              Copy Period
            </h3>
            
            <div class="space-y-4">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Copy Period:</label>
                <div class="flex items-center space-x-3">
                  <input 
                    type="number" 
                    v-model.number="copyPeriod" 
                    min="0" 
                    max="12"
                    class="form-input w-24 text-center"
                    placeholder="0"
                  >
                  <span class="text-sm text-gray-600">to next 11 months [mm/yyyy]</span>
                </div>
                <p class="text-xs text-gray-500 mt-1">Enter number of months to copy (0-12)</p>
              </div>
              
              <div v-if="copyPeriod > 0" class="bg-blue-100 p-4 rounded">
                <h4 class="font-medium text-blue-800 mb-2">Periods to Copy:</h4>
                <div class="grid grid-cols-3 gap-2 text-sm">
                  <div v-for="period in calculatedCopyPeriods" :key="period" 
                       class="bg-white px-3 py-2 rounded text-center text-blue-700 font-medium">
                    {{ period }}
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Paste Period Section -->
          <div class="bg-green-50 p-6 rounded-lg border border-green-200">
            <h3 class="text-lg font-semibold text-green-800 mb-4 flex items-center">
              <i class="fas fa-paste mr-2"></i>
              Paste Period
            </h3>
            
            <div class="space-y-4">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Paste Period:</label>
                <div class="flex items-center space-x-3">
                  <input 
                    type="number" 
                    v-model.number="pastePeriod" 
                    min="0" 
                    max="12"
                    class="form-input w-24 text-center"
                    placeholder="0"
                  >
                  <span class="text-sm text-gray-600">[mm/yyyy]</span>
                </div>
                <p class="text-xs text-gray-500 mt-1">Enter number of months to paste (0-12)</p>
              </div>
              
              <div v-if="pastePeriod > 0" class="bg-green-100 p-4 rounded">
                <h4 class="font-medium text-green-800 mb-2">Periods to Paste:</h4>
                <div class="grid grid-cols-3 gap-2 text-sm">
                  <div v-for="period in calculatedPastePeriods" :key="period" 
                       class="bg-white px-3 py-2 rounded text-center text-green-700 font-medium">
                    {{ period }}
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- SKU Selection Section -->
        <div class="bg-gray-50 p-6 rounded-lg border border-gray-200 mb-8">
          <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
            <i class="fas fa-boxes mr-2"></i>
            SKU Selection
          </h3>
          
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Source SKU -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Source SKU</label>
              <div class="relative">
                <input 
                  v-model="sourceSkuSearch" 
                  @input="searchSourceSkus"
                  @focus="showSourceSkuDropdown = true"
                  placeholder="Search source SKU..."
                  class="form-input"
                >
                <div v-if="showSourceSkuDropdown && sourceSkuSuggestions.length" 
                     class="absolute z-10 bg-white border w-full mt-1 rounded shadow max-h-60 overflow-y-auto">
                  <div v-for="sku in sourceSkuSuggestions" :key="sku.sku" 
                       @click="selectSourceSku(sku)"
                       class="px-4 py-2 hover:bg-blue-100 cursor-pointer">
                    <div class="font-medium">{{ sku.sku }}</div>
                    <div class="text-sm text-gray-600">{{ sku.sku_name }}</div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Target SKU -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Target SKU</label>
              <div class="relative">
                <input 
                  v-model="targetSkuSearch" 
                  @input="searchTargetSkus"
                  @focus="showTargetSkuDropdown = true"
                  placeholder="Search target SKU..."
                  class="form-input"
                >
                <div v-if="showTargetSkuDropdown && targetSkuSuggestions.length" 
                     class="absolute z-10 bg-white border w-full mt-1 rounded shadow max-h-60 overflow-y-auto">
                  <div v-for="sku in targetSkuSuggestions" :key="sku.sku" 
                       @click="selectTargetSku(sku)"
                       class="px-4 py-2 hover:bg-blue-100 cursor-pointer">
                    <div class="font-medium">{{ sku.sku }}</div>
                    <div class="text-sm text-gray-600">{{ sku.sku_name }}</div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Preview Section -->
        <div v-if="canPreview" class="bg-yellow-50 p-6 rounded-lg border border-yellow-200 mb-8">
          <h3 class="text-lg font-semibold text-yellow-800 mb-4 flex items-center">
            <i class="fas fa-eye mr-2"></i>
            Preview
          </h3>
          
          <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white p-4 rounded border">
              <h4 class="font-medium text-gray-800 mb-3">Source Data</h4>
              <div class="space-y-2 text-sm">
                <div class="flex justify-between">
                  <span class="text-gray-600">SKU:</span>
                  <span class="font-medium">{{ selectedSourceSku?.sku }}</span>
                </div>
                <div class="flex justify-between">
                  <span class="text-gray-600">Periods:</span>
                  <span class="font-medium">{{ calculatedCopyPeriods.length }}</span>
                </div>
                <div class="flex justify-between">
                  <span class="text-gray-600">Min Level:</span>
                  <span class="font-medium">{{ previewData.min_level || 0 }}</span>
                </div>
                <div class="flex justify-between">
                  <span class="text-gray-600">Max Level:</span>
                  <span class="font-medium">{{ previewData.max_level || 0 }}</span>
                </div>
                <div class="flex justify-between">
                  <span class="text-gray-600">Reorder Level:</span>
                  <span class="font-medium">{{ previewData.reorder_level || 0 }}</span>
                </div>
              </div>
            </div>
            
            <div class="bg-white p-4 rounded border">
              <h4 class="font-medium text-gray-800 mb-3">Target Data</h4>
              <div class="space-y-2 text-sm">
                <div class="flex justify-between">
                  <span class="text-gray-600">SKU:</span>
                  <span class="font-medium">{{ selectedTargetSku?.sku }}</span>
                </div>
                <div class="flex justify-between">
                  <span class="text-gray-600">Periods:</span>
                  <span class="font-medium">{{ calculatedPastePeriods.length }}</span>
                </div>
                <div class="flex justify-between">
                  <span class="text-gray-600">Operation:</span>
                  <span class="font-medium text-blue-600">Copy & Paste</span>
                </div>
              </div>
            </div>
            
            <div class="bg-white p-4 rounded border">
              <h4 class="font-medium text-gray-800 mb-3">Summary</h4>
              <div class="space-y-2 text-sm">
                <div class="flex justify-between">
                  <span class="text-gray-600">Total Operations:</span>
                  <span class="font-medium">{{ totalOperations }}</span>
                </div>
                <div class="flex justify-between">
                  <span class="text-gray-600">Status:</span>
                  <span class="font-medium text-green-600">Ready</span>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Note Section -->
        <div class="p-6 bg-blue-50 rounded-lg border border-blue-200">
          <div class="flex items-start">
            <i class="fas fa-info-circle text-blue-500 mt-1 mr-3 text-xl"></i>
            <div>
              <h4 class="font-medium text-blue-800 mb-2">Note:</h4>
              <p class="text-sm text-blue-700">
                All SKU# will be copied over to next period. This operation will copy reorder levels, 
                minimum levels, and maximum levels from the source SKU to the target SKU for the specified periods.
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Help Modal -->
    <div v-if="showHelp" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
      <div class="bg-white rounded-lg shadow-xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
        <div class="bg-blue-600 p-6 rounded-t-lg">
          <div class="flex justify-between items-center">
            <h3 class="text-xl font-bold text-white">Help - Copy & Paste SKU Reorder Level</h3>
            <button @click="showHelp = false" class="text-white hover:text-gray-200">
              <i class="fas fa-times text-xl"></i>
            </button>
          </div>
        </div>
        
        <div class="p-6 space-y-4">
          <div>
            <h4 class="font-semibold text-gray-800 mb-2">How to Use:</h4>
            <ol class="list-decimal list-inside space-y-2 text-sm text-gray-600">
              <li>Enter the number of months to copy in the "Copy Period" field (0-12)</li>
              <li>Enter the number of months to paste in the "Paste Period" field (0-12)</li>
              <li>Select the source SKU from which to copy reorder levels</li>
              <li>Select the target SKU to which reorder levels will be copied</li>
              <li>Review the preview to ensure correct data</li>
              <li>Click "Process" to execute the copy operation</li>
            </ol>
          </div>
          
          <div>
            <h4 class="font-semibold text-gray-800 mb-2">Features:</h4>
            <ul class="list-disc list-inside space-y-1 text-sm text-gray-600">
              <li>Copy reorder levels between different SKUs</li>
              <li>Copy to multiple periods at once</li>
              <li>Preview data before processing</li>
              <li>Automatic period calculation</li>
              <li>Real-time validation</li>
            </ul>
          </div>
          
          <div>
            <h4 class="font-semibold text-gray-800 mb-2">Important Notes:</h4>
            <ul class="list-disc list-inside space-y-1 text-sm text-gray-600">
              <li>All SKU reorder levels will be copied to the next period</li>
              <li>Existing data in target periods will be overwritten</li>
              <li>Operation cannot be undone once processed</li>
              <li>Ensure you have proper permissions before processing</li>
            </ul>
          </div>
        </div>
        
        <div class="bg-gray-50 px-6 py-4 rounded-b-lg flex justify-end">
          <button @click="showHelp = false" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
            Close
          </button>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue';
import axios from 'axios';
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import { useToast } from '@/Composables/useToast';
import ToastContainer from '@/Components/ToastContainer.vue';

const toast = useToast();

// Reactive data
const copyPeriod = ref(0);
const pastePeriod = ref(0);
const sourceSkuSearch = ref('');
const targetSkuSearch = ref('');
const selectedSourceSku = ref(null);
const selectedTargetSku = ref(null);
const sourceSkuSuggestions = ref([]);
const targetSkuSuggestions = ref([]);
const showSourceSkuDropdown = ref(false);
const showTargetSkuDropdown = ref(false);
const processing = ref(false);
const showHelp = ref(false);
const previewData = ref({});

// Computed properties
const calculatedCopyPeriods = computed(() => {
  if (copyPeriod.value <= 0) return [];
  const periods = [];
  const now = new Date();
  for (let i = 0; i < copyPeriod.value; i++) {
    const date = new Date(now.getFullYear(), now.getMonth() + i, 1);
    const month = (date.getMonth() + 1).toString().padStart(2, '0');
    const year = date.getFullYear();
    periods.push(`${month}/${year}`);
  }
  return periods;
});

const calculatedPastePeriods = computed(() => {
  if (pastePeriod.value <= 0) return [];
  const periods = [];
  const now = new Date();
  for (let i = 0; i < pastePeriod.value; i++) {
    const date = new Date(now.getFullYear(), now.getMonth() + i, 1);
    const month = (date.getMonth() + 1).toString().padStart(2, '0');
    const year = date.getFullYear();
    periods.push(`${month}/${year}`);
  }
  return periods;
});

const canPreview = computed(() => {
  return selectedSourceSku.value && selectedTargetSku.value && 
         copyPeriod.value > 0 && pastePeriod.value > 0;
});

const canExecute = computed(() => {
  return selectedSourceSku.value && selectedTargetSku.value && 
         copyPeriod.value > 0 && pastePeriod.value > 0;
});

const totalOperations = computed(() => {
  return calculatedCopyPeriods.value.length * calculatedPastePeriods.value.length;
});

// Methods
const searchSourceSkus = async () => {
  if (!sourceSkuSearch.value) {
    sourceSkuSuggestions.value = [];
    return;
  }
  try {
    const response = await axios.get('/api/sku-suggestions-fallback', {
      params: { search: sourceSkuSearch.value }
    });
    sourceSkuSuggestions.value = response.data;
  } catch (error) {
    sourceSkuSuggestions.value = [];
  }
};

const searchTargetSkus = async () => {
  if (!targetSkuSearch.value) {
    targetSkuSuggestions.value = [];
    return;
  }
  try {
    const response = await axios.get('/api/sku-suggestions-fallback', {
      params: { search: targetSkuSearch.value }
    });
    targetSkuSuggestions.value = response.data;
  } catch (error) {
    targetSkuSuggestions.value = [];
  }
};

const selectSourceSku = (sku) => {
  selectedSourceSku.value = sku;
  sourceSkuSearch.value = `${sku.sku} - ${sku.sku_name}`;
  showSourceSkuDropdown.value = false;
  sourceSkuSuggestions.value = [];
  loadSourcePreviewData();
};

const selectTargetSku = (sku) => {
  selectedTargetSku.value = sku;
  targetSkuSearch.value = `${sku.sku} - ${sku.sku_name}`;
  showTargetSkuDropdown.value = false;
  targetSkuSuggestions.value = [];
};

const loadSourcePreviewData = async () => {
  if (!selectedSourceSku.value || calculatedCopyPeriods.value.length === 0) return;
  
  try {
    const sourceSkuId = selectedSourceSku.value.sku;
    const response = await axios.get(`/api/material-management/system-requirement/inventory-setup/sku-reorder-levels/by-sku/${sourceSkuId}`);
    const sourceData = response.data.find(item => item.period === calculatedCopyPeriods.value[0]);
    
    if (sourceData) {
      previewData.value = {
        min_level: sourceData.min_level,
        max_level: sourceData.max_level,
        reorder_level: sourceData.reorder_level,
      };
    } else {
      previewData.value = {
        min_level: 0,
        max_level: 0,
        reorder_level: 0,
      };
    }
  } catch (error) {
    previewData.value = {
      min_level: 0,
      max_level: 0,
      reorder_level: 0,
    };
  }
};

const executeCopy = async () => {
  if (!canExecute.value) {
    toast.error('Please fill in all required fields');
    return;
  }

  processing.value = true;
  try {
    const sourceSkuId = selectedSourceSku.value.sku;
    const targetSkuId = selectedTargetSku.value.sku;
    
    // Execute copy operations for each period combination
    let successCount = 0;
    let totalCount = 0;
    
    for (const copyPeriod of calculatedCopyPeriods.value) {
      for (const pastePeriod of calculatedPastePeriods.value) {
        try {
          await axios.post('/api/copy-reorder-levels-fallback', {
            source_sku_id: sourceSkuId,
            target_sku_id: targetSkuId,
            source_period: copyPeriod,
            target_period: pastePeriod,
          });
          successCount++;
        } catch (error) {
          console.error(`Failed to copy ${copyPeriod} to ${pastePeriod}:`, error);
        }
        totalCount++;
      }
    }
    
    if (successCount === totalCount) {
      toast.success(`Successfully copied reorder levels for ${successCount} period combinations`);
    } else {
      toast.warning(`Copied ${successCount} out of ${totalCount} period combinations`);
    }
    
    // Reset form
    resetForm();
    
  } catch (error) {
    toast.error(error.response?.data?.error || 'Failed to execute copy operation');
  } finally {
    processing.value = false;
  }
};

const resetForm = () => {
  copyPeriod.value = 0;
  pastePeriod.value = 0;
  sourceSkuSearch.value = '';
  targetSkuSearch.value = '';
  selectedSourceSku.value = null;
  selectedTargetSku.value = null;
  previewData.value = {};
};

const refreshData = () => {
  resetForm();
  toast.info('Form refreshed');
};

// Watch for changes to update preview
watch([copyPeriod, selectedSourceSku], () => {
  if (selectedSourceSku.value && copyPeriod.value > 0) {
    loadSourcePreviewData();
  }
});

onMounted(() => {
  // Initialize any required data
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
.btn-success {
  @apply bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg shadow transition flex items-center justify-center whitespace-nowrap;
}
.form-input {
  @apply w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200;
}
</style>
