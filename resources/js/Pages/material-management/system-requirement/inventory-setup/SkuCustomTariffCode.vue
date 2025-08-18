<template>
  <AppLayout :header="'Define SKU Custom Tariff Code'">
    <Head title="Define SKU Custom Tariff Code" />
    <ToastContainer />
    
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-800 p-6 rounded-t-lg shadow-md">
      <h2 class="text-2xl font-bold text-white mb-2 flex items-center">
        <i class="fas fa-link mr-3"></i> Define SKU Custom Tariff Code
      </h2>
      <p class="text-blue-100">Link SKUs with HS Codes for accurate import/export customs management</p>
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
      <!-- SKU Data Table -->
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">SKU#</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sts</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">SKU Name</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tariff Code</th>
              <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr v-if="loading" class="animate-pulse">
              <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">
                <i class="fas fa-spinner fa-spin"></i> Loading SKU data...
              </td>
            </tr>
            <tr v-else-if="skuTariffCodes.length === 0" class="hover:bg-gray-50">
              <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">
                No SKU tariff codes found. Try adjusting your search.
              </td>
            </tr>
            <tr v-for="record in skuTariffCodes" :key="record.id" 
                @click="selectRecord(record)" 
                :class="{'bg-blue-50': selectedRecord && selectedRecord.id === record.id}"
                class="hover:bg-gray-50 cursor-pointer">
              <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ record.sku_id }}</td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                <span :class="record.is_active ? 'text-green-600' : 'text-red-600'" class="font-medium">
                  {{ record.is_active ? 'Act' : 'Obs' }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ record.sku?.sku_name }}</td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                <div class="flex items-center space-x-2">
                  <input 
                    type="text" 
                    :value="record.custom_tariff_code?.code || ''"
                    @click.stop
                    @input="updateTariffCode(record, $event.target.value)"
                    placeholder="Enter tariff code"
                    class="px-2 py-1 border border-gray-300 rounded text-sm focus:ring-blue-500 focus:border-blue-500"
                  >
                  <button @click.stop="openTariffCodeTable(record)" class="text-blue-600 hover:text-blue-800">
                    <i class="fas fa-table"></i>
                  </button>
                </div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-center">
                <button @click.stop="selectRecord(record)" class="btn-blue px-3 py-1 text-xs">
                  Select
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
      <div v-if="pagination" class="mt-4 flex items-center justify-between">
        <div class="text-sm text-gray-700">
          Showing {{ pagination.from }} to {{ pagination.to }} of {{ pagination.total }} results
        </div>
        <div class="flex space-x-2">
          <button @click="loadData(pagination.current_page - 1)" :disabled="pagination.current_page <= 1" class="btn-secondary px-3 py-1">
            Previous
          </button>
          <button @click="loadData(pagination.current_page + 1)" :disabled="pagination.current_page >= pagination.last_page" class="btn-secondary px-3 py-1">
            Next
          </button>
        </div>
      </div>
    </div>

    <!-- Selected Record Details -->
    <div v-if="selectedRecord" class="bg-white rounded-lg shadow-md p-6 mb-6">
      <h3 class="text-lg font-semibold text-gray-800 mb-4">Selected Record Details</h3>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- SKU Information -->
        <div>
          <h4 class="text-md font-medium text-gray-700 mb-3">SKU Information</h4>
          <div class="space-y-3">
            <div class="flex justify-between border-b border-gray-200 pb-2">
              <span class="text-gray-600">SKU Code:</span>
              <span class="font-medium text-gray-900">{{ selectedRecord.sku_id }}</span>
            </div>
            <div class="flex justify-between border-b border-gray-200 pb-2">
              <span class="text-gray-600">SKU Name:</span>
              <span class="font-medium text-gray-900 text-right">{{ selectedRecord.sku?.sku_name }}</span>
            </div>
            <div class="flex justify-between border-b border-gray-200 pb-2">
              <span class="text-gray-600">Status:</span>
              <span :class="selectedRecord.is_active ? 'text-green-600' : 'text-red-600'" class="font-medium">
                {{ selectedRecord.is_active ? 'Active' : 'Inactive' }}
              </span>
            </div>
          </div>
        </div>

        <!-- Tariff Code Information -->
        <div>
          <h4 class="text-md font-medium text-gray-700 mb-3">Tariff Code Information</h4>
          <div class="space-y-3">
            <div class="flex justify-between border-b border-gray-200 pb-2">
              <span class="text-gray-600">Tariff Code:</span>
              <span class="font-medium text-gray-900">{{ selectedRecord.custom_tariff_code?.code || 'Not assigned' }}</span>
            </div>
            <div class="flex justify-between border-b border-gray-200 pb-2">
              <span class="text-gray-600">Description:</span>
              <span class="font-medium text-gray-900 text-right">{{ selectedRecord.custom_tariff_code?.name || 'No description' }}</span>
            </div>
            <div class="flex justify-between border-b border-gray-200 pb-2">
              <span class="text-gray-600">Total Rate:</span>
              <span class="font-medium text-gray-900">{{ formatNumber(getTotalRate()) }}%</span>
            </div>
          </div>
        </div>

        <!-- Rate Configuration -->
        <div class="md:col-span-2">
          <h4 class="text-md font-medium text-gray-700 mb-3">Rate Configuration</h4>
          <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Duty Rate (%):</label>
              <input 
                type="number" 
                v-model.number="selectedRecord.duty_rate" 
                step="0.01"
                min="0"
                max="100"
                placeholder="0.00"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
              >
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">VAT/PPN Rate (%):</label>
              <input 
                type="number" 
                v-model.number="selectedRecord.vat_rate" 
                step="0.01"
                min="0"
                max="100"
                placeholder="0.00"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
              >
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">PPh Import Rate (%):</label>
              <input 
                type="number" 
                v-model.number="selectedRecord.pph_import_rate" 
                step="0.01"
                min="0"
                max="100"
                placeholder="0.00"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
              >
            </div>
          </div>
        </div>

        <!-- Additional Information -->
        <div class="md:col-span-2">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Country Origin:</label>
              <input 
                type="text" 
                v-model="selectedRecord.country_origin" 
                placeholder="Enter country of origin"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
              >
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Status:</label>
              <div class="flex items-center mt-2">
                <input 
                  type="checkbox" 
                  v-model="selectedRecord.is_active"
                  class="mr-2 h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                >
                <span class="text-sm text-gray-700">Active</span>
              </div>
            </div>
          </div>
        </div>

        <!-- Notes -->
        <div class="md:col-span-2">
          <label class="block text-sm font-medium text-gray-700 mb-1">Notes:</label>
          <textarea 
            v-model="selectedRecord.notes" 
            rows="3"
            placeholder="Enter additional notes"
            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
          ></textarea>
        </div>

        <!-- Action Buttons -->
        <div class="md:col-span-2 flex space-x-4">
          <button @click="saveRecord" :disabled="!hasChanges || saving" class="btn-success">
            <i v-if="saving" class="fas fa-spinner fa-spin mr-2"></i>
            <i v-else class="fas fa-save mr-2"></i>
            {{ saving ? 'Saving...' : 'Save Changes' }}
          </button>
          <button @click="newRecord" class="btn-info">
            <i class="fas fa-plus mr-2"></i>
            New Record
          </button>
          <button v-if="selectedRecord.id" @click="deleteRecord" class="btn-danger">
            <i class="fas fa-trash mr-2"></i>
            Delete
          </button>
        </div>
      </div>
    </div>

    <!-- Tariff Code Table Modal -->
    <div v-if="showTariffCodeTable" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
      <div class="bg-white rounded-lg shadow-xl w-full max-w-4xl max-h-[80vh] overflow-hidden">
        <!-- Header -->
        <div class="bg-gradient-to-r from-blue-600 to-blue-800 p-6">
          <div class="flex justify-between items-center">
            <h3 class="text-xl font-bold text-white flex items-center">
              <i class="fas fa-table mr-3"></i>
              Tariff Code Table
            </h3>
            <button @click="showTariffCodeTable = false" class="text-white hover:text-gray-200">
              <i class="fas fa-times text-2xl"></i>
            </button>
          </div>
        </div>

        <!-- Search -->
        <div class="p-6 border-b">
          <div class="flex space-x-4">
            <div class="flex-1">
              <input 
                type="text" 
                v-model="tableSearch" 
                placeholder="Search by code or name..."
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
              >
            </div>
            <button @click="searchTariffCodes" class="btn-primary">
              <i class="fas fa-search mr-2"></i>
              Search
            </button>
          </div>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto max-h-96">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50 sticky top-0">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Code</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Duty Rate</th>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Tax Rate</th>
                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-if="loading" class="animate-pulse">
                <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">
                  <i class="fas fa-spinner fa-spin"></i> Loading tariff codes...
                </td>
              </tr>
              <tr v-else-if="tariffCodes.length === 0" class="hover:bg-gray-50">
                <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">
                  No tariff codes found. Try adjusting your search.
                </td>
              </tr>
              <tr v-for="code in tariffCodes" :key="code.id" 
                  @click="selectTariffCode(code)" 
                  class="hover:bg-gray-50 cursor-pointer">
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ code.code }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ code.name }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ code.category }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 text-right">{{ formatNumber(code.duty_rate) }}%</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 text-right">{{ formatNumber(code.tax_rate) }}%</td>
                <td class="px-6 py-4 whitespace-nowrap text-center">
                  <button @click.stop="selectTariffCode(code)" class="btn-blue px-3 py-1 text-xs">
                    Select
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Footer -->
        <div class="bg-gray-100 px-6 py-4 flex justify-end space-x-3">
          <button @click="showTariffCodeTable = false" class="btn-secondary">
            Exit
          </button>
        </div>
      </div>
    </div>

    <!-- Summary Panel -->
    <div v-if="summary" class="bg-white rounded-lg shadow-md p-6">
      <h3 class="text-lg font-semibold text-gray-800 mb-4">Summary</h3>
      <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="text-center">
          <div class="text-2xl font-bold text-blue-600">{{ summary.total_assignments }}</div>
          <div class="text-sm text-gray-600">Total Assignments</div>
        </div>
        <div class="text-center">
          <div class="text-2xl font-bold text-green-600">{{ summary.active_assignments }}</div>
          <div class="text-sm text-gray-600">Active Assignments</div>
        </div>
        <div class="text-center">
          <div class="text-2xl font-bold text-orange-600">{{ summary.skus_with_tariff_codes }}</div>
          <div class="text-sm text-gray-600">SKUs with Tariff Codes</div>
        </div>
        <div class="text-center">
          <div class="text-2xl font-bold text-red-600">{{ summary.skus_without_tariff_codes }}</div>
          <div class="text-sm text-gray-600">SKUs without Tariff Codes</div>
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
const loading = ref(false);
const saving = ref(false);
const skuTariffCodes = ref([]);
const selectedRecord = ref(null);
const showTariffCodeTable = ref(false);
const tableSearch = ref('');
const tariffCodes = ref([]);
const summary = ref(null);
const pagination = ref(null);
const currentIndex = ref(-1);

// Computed properties
const canNavigatePrevious = computed(() => {
  return currentIndex.value > 0;
});

const canNavigateNext = computed(() => {
  return currentIndex.value < skuTariffCodes.value.length - 1;
});

const hasChanges = computed(() => {
  if (!selectedRecord.value) return false;
  
  // Check if any fields have been modified
  return selectedRecord.value.duty_rate !== 0 ||
         selectedRecord.value.vat_rate !== 0 ||
         selectedRecord.value.pph_import_rate !== 0 ||
         selectedRecord.value.country_origin !== '' ||
         selectedRecord.value.notes !== '';
});

// Load SKU tariff codes data
const loadData = async (page = 1) => {
  loading.value = true;
  try {
    const res = await axios.get('/api/material-management/system-requirement/inventory-setup/sku-custom-tariff-codes', {
      params: { page }
    });
    skuTariffCodes.value = res.data.data || res.data;
    pagination.value = res.data;
  } catch (e) {
    toast.error('Failed to load SKU tariff codes');
  } finally {
    loading.value = false;
  }
};

// Load tariff codes for table
const loadTariffCodes = async () => {
  try {
    const res = await axios.get('/api/material-management/system-requirement/inventory-setup/custom-tariff-codes', {
      params: { search: tableSearch.value }
    });
    tariffCodes.value = res.data.data || res.data;
  } catch (e) {
    toast.error('Failed to load tariff codes');
  }
};

const searchTariffCodes = () => {
  loadTariffCodes();
};

const selectRecord = (record) => {
  selectedRecord.value = { ...record };
  currentIndex.value = skuTariffCodes.value.findIndex(r => r.id === record.id);
  loadSummary();
};

const selectTariffCode = (code) => {
  if (selectedRecord.value) {
    selectedRecord.value.custom_tariff_code_id = code.id;
    selectedRecord.value.custom_tariff_code = code;
    selectedRecord.value.duty_rate = code.duty_rate;
    selectedRecord.value.vat_rate = code.tax_rate;
  }
  showTariffCodeTable.value = false;
};

const updateTariffCode = (record, value) => {
  // This would typically trigger a search or lookup
  console.log('Update tariff code for record:', record.id, 'to:', value);
};

const openTariffCodeTable = (record) => {
  selectedRecord.value = record;
  showTariffCodeTable.value = true;
  loadTariffCodes();
};

const newRecord = () => {
  selectedRecord.value = {
    id: null,
    sku_id: '',
    custom_tariff_code_id: null,
    custom_tariff_code: null,
    tariff_description: '',
    duty_rate: 0,
    vat_rate: 0,
    pph_import_rate: 0,
    country_origin: '',
    is_active: true,
    notes: '',
    sku: null
  };
  currentIndex.value = -1;
};

const saveRecord = async () => {
  if (!selectedRecord.value) return;
  
  saving.value = true;
  try {
    let res;
    if (selectedRecord.value.id) {
      res = await axios.put(`/api/material-management/system-requirement/inventory-setup/sku-custom-tariff-codes/${selectedRecord.value.id}`, selectedRecord.value);
    } else {
      res = await axios.post('/api/material-management/system-requirement/inventory-setup/sku-custom-tariff-codes', selectedRecord.value);
    }
    
    selectedRecord.value = res.data;
    toast.success('SKU tariff code saved successfully');
    loadData();
    loadSummary();
  } catch (e) {
    if (e.response?.data?.error) {
      toast.error(e.response.data.error);
    } else {
      toast.error('Failed to save SKU tariff code');
    }
  } finally {
    saving.value = false;
  }
};

const deleteRecord = async () => {
  if (!selectedRecord.value?.id) return;
  
  if (!confirm('Are you sure you want to delete this SKU tariff code?')) return;
  
  try {
    await axios.delete(`/api/material-management/system-requirement/inventory-setup/sku-custom-tariff-codes/${selectedRecord.value.id}`);
    toast.success('SKU tariff code deleted successfully');
    newRecord();
    loadData();
    loadSummary();
  } catch (e) {
    toast.error('Failed to delete SKU tariff code');
  }
};

// Navigation methods
const previousRecord = () => {
  if (canNavigatePrevious.value) {
    const prevRecord = skuTariffCodes.value[currentIndex.value - 1];
    selectRecord(prevRecord);
  }
};

const nextRecord = () => {
  if (canNavigateNext.value) {
    const nextRecord = skuTariffCodes.value[currentIndex.value + 1];
    selectRecord(nextRecord);
  }
};

const loadSummary = async () => {
  try {
    const res = await axios.get('/api/material-management/system-requirement/inventory-setup/sku-custom-tariff-codes/summary');
    summary.value = res.data;
  } catch (e) {
    console.error('Failed to load summary:', e);
  }
};

// Action methods
const refreshData = () => {
  loadData();
  loadSummary();
};

const printReport = () => {
  if (!selectedRecord.value) {
    toast.error('Please select a record first');
    return;
  }
  
  // Open print window with SKU tariff code data
  const printWindow = window.open('', '_blank');
  printWindow.document.write(`
    <html>
      <head>
        <title>SKU Custom Tariff Code Report - ${selectedRecord.value.sku_id}</title>
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
          <h1>SKU Custom Tariff Code Report</h1>
          <h2>${selectedRecord.value.sku_id} - ${selectedRecord.value.sku?.sku_name}</h2>
          <p>Generated on: ${new Date().toLocaleDateString()}</p>
        </div>
        <table>
          <tr>
            <th>Field</th>
            <th>Value</th>
          </tr>
          <tr>
            <td>SKU Code</td>
            <td>${selectedRecord.value.sku_id}</td>
          </tr>
          <tr>
            <td>SKU Name</td>
            <td>${selectedRecord.value.sku?.sku_name || 'N/A'}</td>
          </tr>
          <tr>
            <td>Tariff Code</td>
            <td>${selectedRecord.value.custom_tariff_code?.code || 'Not assigned'}</td>
          </tr>
          <tr>
            <td>Tariff Description</td>
            <td>${selectedRecord.value.custom_tariff_code?.name || 'N/A'}</td>
          </tr>
          <tr>
            <td>Duty Rate</td>
            <td>${selectedRecord.value.duty_rate}%</td>
          </tr>
          <tr>
            <td>VAT/PPN Rate</td>
            <td>${selectedRecord.value.vat_rate}%</td>
          </tr>
          <tr>
            <td>PPh Import Rate</td>
            <td>${selectedRecord.value.pph_import_rate}%</td>
          </tr>
          <tr>
            <td>Total Rate</td>
            <td>${getTotalRate()}%</td>
          </tr>
          <tr>
            <td>Country Origin</td>
            <td>${selectedRecord.value.country_origin || 'N/A'}</td>
          </tr>
          <tr>
            <td>Status</td>
            <td>${selectedRecord.value.is_active ? 'Active' : 'Inactive'}</td>
          </tr>
        </table>
      </body>
    </html>
  `);
  printWindow.document.close();
  printWindow.print();
};

// Utility methods
const formatNumber = (num) => {
  return new Intl.NumberFormat('en-US', { minimumFractionDigits: 2 }).format(num);
};

const getTotalRate = () => {
  if (!selectedRecord.value) return 0;
  return (selectedRecord.value.duty_rate || 0) + (selectedRecord.value.vat_rate || 0) + (selectedRecord.value.pph_import_rate || 0);
};

onMounted(() => {
  loadData();
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
tbody tr { transition: all 0.2s; }
tbody tr:hover { transform: translateY(-2px); box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
.overflow-x-auto, .overflow-y-auto { scrollbar-width: thin; scrollbar-color: rgba(156,163,175,0.5) transparent; }
.overflow-x-auto::-webkit-scrollbar, .overflow-y-auto::-webkit-scrollbar { height: 8px; width: 8px; }
.overflow-x-auto::-webkit-scrollbar-track, .overflow-y-auto::-webkit-scrollbar-track { background: transparent; }
.overflow-x-auto::-webkit-scrollbar-thumb, .overflow-y-auto::-webkit-scrollbar-thumb { background-color: rgba(156,163,175,0.5); border-radius: 20px; }
</style>
