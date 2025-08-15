<template>
  <div v-if="show" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-2xl">
      <!-- Header -->
      <div class="bg-gradient-to-r from-blue-600 to-blue-800 p-6 rounded-t-lg">
        <div class="flex justify-between items-center">
          <h3 class="text-xl font-bold text-white flex items-center">
            <i class="fas fa-copy mr-3"></i>
            Copy & Paste SKU Reorder Level
          </h3>
          <button @click="$emit('close')" class="text-white hover:text-gray-200">
            <i class="fas fa-times text-xl"></i>
          </button>
        </div>
        <p class="text-blue-100 mt-2">Copy reorder levels between SKUs and periods</p>
      </div>

      <!-- Content -->
      <div class="p-6">
        <!-- Input Fields -->
        <div class="space-y-6">
          <!-- Copy Period -->
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

          <!-- Paste Period -->
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

          <!-- SKU Selection -->
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
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

          <!-- Preview -->
          <div v-if="canPreview" class="bg-gray-50 p-4 rounded-lg">
            <h4 class="font-medium text-gray-800 mb-3">Preview</h4>
            <div class="grid grid-cols-3 gap-4 text-sm">
              <div class="text-center p-3 bg-blue-100 rounded">
                <div class="font-medium text-blue-800">Min Level</div>
                <div class="text-blue-600">{{ previewData.min_level || 0 }}</div>
              </div>
              <div class="text-center p-3 bg-green-100 rounded">
                <div class="font-medium text-green-800">Max Level</div>
                <div class="text-green-600">{{ previewData.max_level || 0 }}</div>
              </div>
              <div class="text-center p-3 bg-purple-100 rounded">
                <div class="font-medium text-purple-800">Reorder Level</div>
                <div class="text-purple-600">{{ previewData.reorder_level || 0 }}</div>
              </div>
            </div>
          </div>

          <!-- Note -->
          <div class="p-4 bg-blue-50 rounded-lg border border-blue-200">
            <div class="flex items-start">
              <i class="fas fa-info-circle text-blue-500 mt-1 mr-3"></i>
              <div>
                <h4 class="font-medium text-blue-800 mb-1">Note:</h4>
                <p class="text-sm text-blue-700">
                  All SKU# will be copied over to next period.
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Footer -->
      <div class="bg-gray-50 px-6 py-4 rounded-b-lg flex justify-between items-center">
        <button @click="$emit('close')" class="px-4 py-2 text-gray-600 hover:text-gray-800">
          Cancel
        </button>
        <div class="flex gap-3">
          <button 
            @click="previewCopy"
            :disabled="!canPreview"
            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed"
          >
            <i class="fas fa-eye mr-2"></i>
            Preview
          </button>
          <button 
            @click="executeCopy"
            :disabled="!canExecute || copying"
            class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 disabled:opacity-50 disabled:cursor-not-allowed"
          >
            <i v-if="copying" class="fas fa-spinner fa-spin mr-2"></i>
            <i v-else class="fas fa-copy mr-2"></i>
            {{ copying ? 'Copying...' : 'Execute Copy' }}
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue';
import axios from 'axios';
import { useToast } from '@/Composables/useToast';

const props = defineProps({
  show: Boolean,
  currentSku: Object,
});

const emit = defineEmits(['close', 'copied']);

const toast = useToast();

// Reactive data
const copyPeriod = ref(0);
const pastePeriod = ref(0);
const sourceSkuSearch = ref('');
const targetSkuSearch = ref('');
const sourceSkuSuggestions = ref([]);
const targetSkuSuggestions = ref([]);
const showSourceSkuDropdown = ref(false);
const showTargetSkuDropdown = ref(false);
const copying = ref(false);
const previewData = ref({});

// Computed properties
const canPreview = computed(() => {
  return sourceSkuSearch.value && targetSkuSearch.value && copyPeriod.value > 0 && pastePeriod.value > 0;
});

const canExecute = computed(() => {
  return sourceSkuSearch.value && targetSkuSearch.value && copyPeriod.value > 0 && pastePeriod.value > 0;
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
  sourceSkuSearch.value = `${sku.sku} - ${sku.sku_name}`;
  showSourceSkuDropdown.value = false;
  sourceSkuSuggestions.value = [];
  loadPreviewData();
};

const selectTargetSku = (sku) => {
  targetSkuSearch.value = `${sku.sku} - ${sku.sku_name}`;
  showTargetSkuDropdown.value = false;
  targetSkuSuggestions.value = [];
};

const loadPreviewData = async () => {
  if (!sourceSkuSearch.value) return;
  
  try {
    const sourceSkuId = sourceSkuSearch.value.split(' - ')[0];
    const response = await axios.get(`/api/material-management/system-requirement/inventory-setup/sku-reorder-levels/by-sku/${sourceSkuId}`);
    const sourceData = response.data[0]; // Get first available data
    
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

const previewCopy = async () => {
  await loadPreviewData();
  toast.info('Preview data loaded');
};

const executeCopy = async () => {
  copying.value = true;
  try {
    const sourceSkuId = sourceSkuSearch.value.split(' - ')[0];
    const targetSkuId = targetSkuSearch.value.split(' - ')[0];
    
    // Execute copy operations for each period combination
    let successCount = 0;
    let totalCount = 0;
    
    // Generate periods based on copy and paste settings
    const now = new Date();
    const copyPeriods = [];
    const pastePeriods = [];
    
    for (let i = 0; i < copyPeriod.value; i++) {
      const date = new Date(now.getFullYear(), now.getMonth() + i, 1);
      const month = (date.getMonth() + 1).toString().padStart(2, '0');
      const year = date.getFullYear();
      copyPeriods.push(`${month}/${year}`);
    }
    
    for (let i = 0; i < pastePeriod.value; i++) {
      const date = new Date(now.getFullYear(), now.getMonth() + i, 1);
      const month = (date.getMonth() + 1).toString().padStart(2, '0');
      const year = date.getFullYear();
      pastePeriods.push(`${month}/${year}`);
    }
    
    for (const copyPeriod of copyPeriods) {
      for (const pastePeriod of pastePeriods) {
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
    
    emit('copied');
    emit('close');
    
  } catch (error) {
    toast.error(error.response?.data?.error || 'Failed to copy reorder levels');
  } finally {
    copying.value = false;
  }
};

// Initialize current SKU if provided
watch(() => props.currentSku, (newSku) => {
  if (newSku) {
    sourceSkuSearch.value = `${newSku.sku} - ${newSku.sku_name}`;
  }
}, { immediate: true });

// Watch for changes to update preview
watch([copyPeriod, sourceSkuSearch], () => {
  if (sourceSkuSearch.value && copyPeriod.value > 0) {
    loadPreviewData();
  }
});

onMounted(() => {
  // Initialize any required data
});
</script>

<style scoped>
.form-input {
  @apply w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200;
}
</style> 