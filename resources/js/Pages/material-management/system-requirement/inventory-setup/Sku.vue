<template>
  <AppLayout :header="'Define SKU'">
    <Head title="Define SKU" />

    <!-- Toast Container -->
    <ToastContainer />

    <!-- Header Section -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-800 p-6 rounded-t-lg shadow-md">
      <h2 class="text-2xl font-bold text-white mb-2 flex items-center">
        <i class="fas fa-boxes mr-3"></i> Define SKU
      </h2>
      <p class="text-blue-100">Manage Stock Keeping Units (SKUs) for inventory</p>
    </div>

    <div class="bg-white rounded-b-lg shadow-md p-6 mb-6">
      <div class="flex flex-col md:flex-row gap-6">
        <!-- Main Content Area -->
        <div class="flex-1">
          <!-- Action Bar -->
          <div class="mb-6 flex flex-col md:flex-row gap-4 justify-between items-start md:items-center">
            <div class="flex-1 w-full">
              <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
                  <i class="fas fa-search"></i>
                </span>
                <input type="text" v-model="searchQuery" placeholder="Search by SKU, name, or category..."
                  class="pl-10 pr-4 py-2 w-full border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
              </div>
            </div>
            <div class="flex gap-2 w-full md:w-auto">
              <button @click="newSku" class="btn-primary flex-1 md:flex-none">
                <i class="fas fa-plus-circle mr-2"></i> New SKU
              </button>
              <button @click="refreshData" class="btn-secondary flex-1 md:flex-none">
                <i class="fas fa-sync-alt mr-2"></i> Refresh
              </button>
              <button @click="printSkus" class="btn-info flex-1 md:flex-none">
                <i class="fas fa-print mr-2"></i> Print
              </button>
            </div>
          </div>

          <!-- Table Section -->
          <div class="bg-white rounded-lg overflow-hidden border border-gray-200">
            <div class="overflow-x-auto">
              <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                  <tr>
                    <th @click="sortBy('sku')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                      <div class="flex items-center">
                        SKU <i class="fas fa-sort ml-1"></i>
                      </div>
                    </th>
                    <th @click="sortBy('sku_name')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                      <div class="flex items-center">
                        Name <i class="fas fa-sort ml-1"></i>
                      </div>
                    </th>
                    <th @click="sortBy('category_code')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                      <div class="flex items-center">
                        Category <i class="fas fa-sort ml-1"></i>
                      </div>
                    </th>
                    <th @click="sortBy('type')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                      <div class="flex items-center">
                        Type <i class="fas fa-sort ml-1"></i>
                      </div>
                    </th>
                    <th @click="sortBy('uom')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                      <div class="flex items-center">
                        UOM <i class="fas fa-sort ml-1"></i>
                      </div>
                    </th>
                  </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                  <tr v-if="loading" class="animate-pulse">
                    <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">
                      <div class="flex justify-center items-center space-x-2">
                        <i class="fas fa-spinner fa-spin"></i>
                        <span>Loading SKUs...</span>
                      </div>
                    </td>
                  </tr>
                  <tr v-else-if="paginatedSkus.length === 0" class="hover:bg-gray-50">
                    <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">
                      No SKUs found. Try adjusting your search.
                    </td>
                  </tr>
                  <tr v-for="sku in paginatedSkus" :key="sku.sku" 
                      @click="selectSku(sku)" 
                      :class="{'bg-blue-50': selectedSku && selectedSku.sku === sku.sku}"
                      class="hover:bg-gray-50 cursor-pointer">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ sku.sku }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ sku.sku_name }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ sku.category_code }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ sku.type }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ sku.uom }}</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>

          <!-- Pagination Controls -->
          <div class="mt-4 flex justify-between items-center text-sm text-gray-600">
            <div>
              <span>Showing {{ paginatedSkus.length }} of {{ filteredSkus.length }} SKUs</span>
            </div>
            <div class="flex items-center space-x-2">
              <select v-model="itemsPerPage" class="border border-gray-300 rounded px-2 py-1 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option :value="10">10 per page</option>
                <option :value="20">20 per page</option>
                <option :value="50">50 per page</option>
                <option :value="100">100 per page</option>
              </select>
              <button :disabled="currentPage === 1" @click="currentPage--" class="px-2 py-1 border rounded hover:bg-gray-200 disabled:opacity-50">
                <i class="fas fa-chevron-left"></i>
              </button>
              <span class="px-4">{{ currentPage }} / {{ totalPages }}</span>
              <button :disabled="currentPage === totalPages || totalPages === 0" @click="currentPage++" class="px-2 py-1 border rounded hover:bg-gray-200 disabled:opacity-50">
                <i class="fas fa-chevron-right"></i>
              </button>
            </div>
          </div>
        </div>

        <!-- Side Panel with Details -->
        <div class="w-full md:w-96 bg-gray-50 rounded-lg p-4 border border-gray-200">
          <div v-if="selectedSku" class="mb-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-3 flex items-center">
              <i class="fas fa-info-circle mr-2 text-blue-500"></i> SKU Details
            </h3>
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
              <div class="flex justify-between border-b border-gray-200 pb-2">
                <span class="text-gray-600">BOH:</span>
                <span class="font-medium text-gray-900">{{ selectedSku.boh }}</span>
              </div>
              <div class="flex justify-between border-b border-gray-200 pb-2">
                <span class="text-gray-600">FPO:</span>
                <span class="font-medium text-gray-900">{{ selectedSku.fpo }}</span>
              </div>
              <div class="flex justify-between border-b border-gray-200 pb-2">
                <span class="text-gray-600">ROL:</span>
                <span class="font-medium text-gray-900">{{ selectedSku.rol }}</span>
              </div>
              <div class="flex justify-between border-b border-gray-200 pb-2">
                <span class="text-gray-600">Status:</span>
                <span class="font-medium" :class="selectedSku.is_active ? 'text-green-600' : 'text-red-600'">
                  {{ selectedSku.is_active ? 'Active' : 'Inactive' }}
                </span>
              </div>
            </div>
            <div class="mt-6 flex space-x-2">
              <button @click="editSku(selectedSku)" class="flex-1 btn-blue">
                <i class="fas fa-edit mr-1"></i> Edit
              </button>
              <button @click="confirmDelete(selectedSku)" class="flex-1 btn-danger">
                <i class="fas fa-trash-alt mr-1"></i> Delete
              </button>
            </div>
          </div>
          <div v-else class="flex flex-col items-center justify-center h-64 text-center">
            <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mb-4">
              <i class="fas fa-boxes text-blue-500 text-2xl"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-1">No SKU Selected</h3>
            <p class="text-gray-500 mb-4">Select a SKU from the table to view details</p>
            <button @click="newSku" class="btn-primary">
              <i class="fas fa-plus-circle mr-1"></i> Create New SKU
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Form Modal -->
    <div v-if="showFormModal" class="modal-container">
      <div class="modal-overlay" @click="showFormModal = false"></div>
      <div class="modal-content">
        <!-- Modal Header -->
        <div class="modal-header">
          <div class="flex items-center">
            <div class="bg-white/20 p-2 rounded-lg mr-3">
              <i class="fas fa-boxes text-white text-xl"></i>
            </div>
            <h3 class="text-2xl font-bold text-white">{{ isEditing ? 'Edit' : 'New' }} SKU</h3>
          </div>
          <button @click="showFormModal = false" class="bg-white/20 hover:bg-white/30 text-white p-2 rounded-lg transition-all duration-200">
            <i class="fas fa-times"></i>
          </button>
        </div>
        
        <!-- Form Content -->
        <div class="modal-body">
          <form @submit.prevent="saveSku">
            <div class="space-y-6">
              <!-- SKU Code -->
              <div class="form-field">
                <label class="form-label">
                  <i class="fas fa-hashtag text-blue-500 mr-2"></i>
                  SKU Code<span class="text-red-500">*</span>
                </label>
                <input type="text" v-model="formSku.sku" required :disabled="isEditing"
                  maxlength="20"
                  @input="formSku.sku = formSku.sku.toUpperCase()"
                  class="form-input"
                  placeholder="Enter SKU code">
                <p class="text-xs text-gray-500 mt-2 italic">Code must be unique (max 20 chars) and cannot be changed later.</p>
                <p v-if="errors.sku" class="form-error">{{ errors.sku }}</p>
              </div>
              
              <!-- SKU Name -->
              <div class="form-field">
                <label class="form-label">
                  <i class="fas fa-tag text-blue-500 mr-2"></i>
                  SKU Name<span class="text-red-500">*</span>
                </label>
                <input type="text" v-model="formSku.sku_name" required
                  maxlength="150"
                  class="form-input"
                  placeholder="Enter SKU name">
                <p v-if="errors.sku_name" class="form-error">{{ errors.sku_name }}</p>
              </div>

              <!-- Category -->
              <div class="form-field">
                <label class="form-label">
                  <i class="fas fa-folder text-blue-500 mr-2"></i>
                  Category<span class="text-red-500">*</span>
                </label>
                <select v-model="formSku.category_code" required
                  class="form-input"
                >
                  <option value="">Select a category</option>
                  <option v-for="category in categories" :key="category.code" :value="category.code">
                    {{ category.name }}
                  </option>
                </select>
                <p v-if="errors.category_code" class="form-error">{{ errors.category_code }}</p>
              </div>

              <!-- Type -->
              <div class="form-field">
                <label class="form-label">
                  <i class="fas fa-cube text-blue-500 mr-2"></i>
                  Type
                </label>
                <select v-model="formSku.type"
                  class="form-input"
                >
                  <option value="">Select a type</option>
                  <option v-for="type in types" :key="type" :value="type">
                    {{ type }}
                  </option>
                </select>
                <p v-if="errors.type" class="form-error">{{ errors.type }}</p>
              </div>

              <!-- UOM -->
              <div class="form-field">
                <label class="form-label">
                  <i class="fas fa-ruler text-blue-500 mr-2"></i>
                  Unit of Measure
                </label>
                <select v-model="formSku.uom"
                  class="form-input"
                >
                  <option value="">Select a unit</option>
                  <option v-for="unit in units" :key="unit.code" :value="unit.code">
                    {{ unit.name }}
                  </option>
                </select>
                <p v-if="errors.uom" class="form-error">{{ errors.uom }}</p>
              </div>

              <!-- Quantities -->
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="form-field">
                  <label class="form-label">
                    <i class="fas fa-boxes text-blue-500 mr-2"></i>
                    BOH
                  </label>
                  <input type="number" v-model="formSku.boh"
                    step="0.001"
                    class="form-input"
                    placeholder="0.000">
                  <p v-if="errors.boh" class="form-error">{{ errors.boh }}</p>
                </div>

                <div class="form-field">
                  <label class="form-label">
                    <i class="fas fa-dolly text-blue-500 mr-2"></i>
                    FPO
                  </label>
                  <input type="number" v-model="formSku.fpo"
                    step="0.001"
                    class="form-input"
                    placeholder="0.000">
                  <p v-if="errors.fpo" class="form-error">{{ errors.fpo }}</p>
                </div>

                <div class="form-field">
                  <label class="form-label">
                    <i class="fas fa-level-down-alt text-blue-500 mr-2"></i>
                    ROL
                  </label>
                  <input type="number" v-model="formSku.rol"
                    step="0.001"
                    class="form-input"
                    placeholder="0.000">
                  <p v-if="errors.rol" class="form-error">{{ errors.rol }}</p>
                </div>

                <div class="form-field">
                  <label class="form-label">
                    <i class="fas fa-puzzle-piece text-blue-500 mr-2"></i>
                    Total Parts
                  </label>
                  <input type="number" v-model="formSku.total_part"
                    step="1"
                    class="form-input"
                    placeholder="0">
                  <p v-if="errors.total_part" class="form-error">{{ errors.total_part }}</p>
                </div>
              </div>

              <!-- Min/Max Quantities -->
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="form-field">
                  <label class="form-label">
                    <i class="fas fa-arrow-down text-blue-500 mr-2"></i>
                    Min Quantity
                  </label>
                  <input type="number" v-model="formSku.min_qty"
                    step="0.01"
                    class="form-input"
                    placeholder="0.00">
                  <p v-if="errors.min_qty" class="form-error">{{ errors.min_qty }}</p>
                </div>

                <div class="form-field">
                  <label class="form-label">
                    <i class="fas fa-arrow-up text-blue-500 mr-2"></i>
                    Max Quantity
                  </label>
                  <input type="number" v-model="formSku.max_qty"
                    step="0.01"
                    class="form-input"
                    placeholder="0.00">
                  <p v-if="errors.max_qty" class="form-error">{{ errors.max_qty }}</p>
                </div>
              </div>

              <!-- Additional Information -->
              <div class="form-field">
                <label class="form-label">
                  <i class="fas fa-info-circle text-blue-500 mr-2"></i>
                  Additional Name
                </label>
                <input type="text" v-model="formSku.additional_name"
                  maxlength="200"
                  class="form-input"
                  placeholder="Enter additional name">
                <p v-if="errors.additional_name" class="form-error">{{ errors.additional_name }}</p>
              </div>

              <!-- Part Numbers -->
              <div class="space-y-4">
                <div class="form-field">
                  <label class="form-label">
                    <i class="fas fa-barcode text-blue-500 mr-2"></i>
                    Part Number 1
                  </label>
                  <input type="text" v-model="formSku.part_number1"
                    maxlength="100"
                    class="form-input"
                    placeholder="Enter part number 1">
                  <p v-if="errors.part_number1" class="form-error">{{ errors.part_number1 }}</p>
                </div>

                <div class="form-field">
                  <label class="form-label">
                    <i class="fas fa-barcode text-blue-500 mr-2"></i>
                    Part Number 2
                  </label>
                  <input type="text" v-model="formSku.part_number2"
                    maxlength="100"
                    class="form-input"
                    placeholder="Enter part number 2">
                  <p v-if="errors.part_number2" class="form-error">{{ errors.part_number2 }}</p>
                </div>

                <div class="form-field">
                  <label class="form-label">
                    <i class="fas fa-barcode text-blue-500 mr-2"></i>
                    Part Number 3
                  </label>
                  <input type="text" v-model="formSku.part_number3"
                    maxlength="100"
                    class="form-input"
                    placeholder="Enter part number 3">
                  <p v-if="errors.part_number3" class="form-error">{{ errors.part_number3 }}</p>
                </div>
              </div>

              <!-- Status -->
              <div class="form-field">
                <label class="flex items-center space-x-2 cursor-pointer">
                  <input type="checkbox" v-model="formSku.is_active" class="form-checkbox h-5 w-5 text-blue-600">
                  <span class="text-gray-700">Active</span>
                </label>
              </div>
            </div>
            
            <!-- Form Footer with Buttons -->
            <div class="modal-footer">
              <button type="button" @click="showFormModal = false" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg shadow transition-all duration-200 flex items-center">
                <i class="fas fa-times mr-2"></i> Cancel
              </button>
              <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg shadow transition-all duration-200 flex items-center" :disabled="loading">
                <i class="fas fa-save mr-2"></i> {{ isEditing ? 'Update' : 'Create' }}
                <span v-if="loading" class="ml-2 animate-spin"><i class="fas fa-spinner"></i></span>
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- Confirmation Modal -->
    <div v-if="showConfirmation" class="fixed inset-0 flex items-center justify-center z-50">
      <div class="absolute inset-0 bg-black opacity-50" @click="showConfirmation = false"></div>
      <div class="bg-white rounded-lg shadow-lg max-w-md z-10 w-full">
        <div class="p-6">
          <div class="flex items-center mb-4">
            <div class="bg-red-100 rounded-full p-2 mr-3">
              <i class="fas fa-exclamation-triangle text-red-600"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900">Confirm Delete</h3>
          </div>
          <p class="mb-4 text-gray-600">Are you sure you want to delete SKU <span class="font-semibold">{{ skuToDelete?.sku }}</span>? This action cannot be undone.</p>
          <div class="flex justify-end space-x-3">
            <button @click="showConfirmation = false" class="px-4 py-2 text-gray-700 border border-gray-300 rounded hover:bg-gray-50">
              <i class="fas fa-times mr-1"></i> Cancel
            </button>
            <button @click="deleteSku" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700" :disabled="loading">
              <i class="fas fa-trash-alt mr-1"></i> Delete
            </button>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue';
import axios from 'axios';
import { Head, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import { useToast } from '@/Composables/useToast';
import ToastContainer from '@/Components/ToastContainer.vue';

// State variables
const skus = ref([]);
const categories = ref([]);
const units = ref([]);
const types = ref([]);
const selectedSku = ref(null);
const loading = ref(false);
const searchQuery = ref('');
const showFormModal = ref(false);
const showConfirmation = ref(false);
const skuToDelete = ref(null);
const isEditing = ref(false);
const sortOrder = ref({
  field: 'sku',
  direction: 'asc'
});

// Initialize toast
const toast = useToast();

const currentPage = ref(1);
const itemsPerPage = ref(10);
const errors = ref({});

// Form for creating/editing SKU
const formSku = ref({
  sku: '',
  sts: '',
  sku_name: '',
  category_code: '',
  type: '',
  uom: '',
  boh: 0,
  fpo: 0,
  rol: 0,
  total_part: 0,
  min_qty: 0,
  max_qty: 0,
  additional_name: '',
  part_number1: '',
  part_number2: '',
  part_number3: '',
  is_active: true,
});

// Reset form to default values
const resetForm = () => {
  formSku.value = {
    sku: '',
    sts: '',
    sku_name: '',
    category_code: '',
    type: '',
    uom: '',
    boh: 0,
    fpo: 0,
    rol: 0,
    total_part: 0,
    min_qty: 0,
    max_qty: 0,
    additional_name: '',
    part_number1: '',
    part_number2: '',
    part_number3: '',
    is_active: true,
  };
  isEditing.value = false;
  errors.value = {};
};

// Computed properties
const filteredSkus = computed(() => {
  let result = skus.value;
  
  if (searchQuery.value) {
    const query = searchQuery.value.toLowerCase();
    result = result.filter(sku =>
      (sku.sku && sku.sku.toLowerCase().includes(query)) ||
      (sku.sku_name && sku.sku_name.toLowerCase().includes(query)) ||
      (sku.category_code && sku.category_code.toLowerCase().includes(query))
    );
  }
  
  result = [...result].sort((a, b) => {
    const field = sortOrder.value.field;
    const direction = sortOrder.value.direction === 'asc' ? 1 : -1;
    
    const valA = a[field];
    const valB = b[field];

    if ((valA || '') < (valB || '')) return -1 * direction;
    if ((valA || '') > (valB || '')) return 1 * direction;
    return 0;
  });

  return result;
});

const paginatedSkus = computed(() => {
  const start = (currentPage.value - 1) * itemsPerPage.value;
  const end = start + itemsPerPage.value;
  return filteredSkus.value.slice(start, end);
});

const totalPages = computed(() => {
  const total = filteredSkus.value.length;
  if (total === 0) return 1;
  return Math.ceil(total / itemsPerPage.value);
});

watch(filteredSkus, () => {
  if (currentPage.value > totalPages.value) {
    currentPage.value = totalPages.value || 1;
  }
});

watch(itemsPerPage, () => {
  currentPage.value = 1;
});

const fetchSkus = async () => {
  loading.value = true;
  try {
    const response = await axios.get('/api/material-management/skus');
    skus.value = response.data;
  } catch (error) {
    console.error('Error fetching SKUs:', error);
    toast.error('Failed to load SKUs');
  } finally {
    loading.value = false;
  }
};

const fetchCategories = async () => {
  try {
    const response = await axios.get('/api/material-management/skus/categories');
    categories.value = response.data;
  } catch (error) {
    console.error('Error fetching categories:', error);
    toast.error('Failed to load categories');
  }
};

const fetchUnits = async () => {
  try {
    const response = await axios.get('/api/material-management/skus/units');
    units.value = response.data;
  } catch (error) {
    console.error('Error fetching units:', error);
    toast.error('Failed to load units');
  }
};

const fetchTypes = async () => {
  try {
    const response = await axios.get('/api/material-management/skus/types');
    types.value = response.data;
  } catch (error) {
    console.error('Error fetching types:', error);
    toast.error('Failed to load types');
  }
};

const refreshData = () => {
  selectedSku.value = null;
  searchQuery.value = '';
  fetchSkus();
};

const selectSku = (sku) => {
  selectedSku.value = sku;
};

const newSku = () => {
  resetForm();
  showFormModal.value = true;
};

const editSku = (sku) => {
  isEditing.value = true;
  formSku.value = { ...sku };
  showFormModal.value = true;
  errors.value = {};
};

const confirmDelete = (sku) => {
  skuToDelete.value = sku;
  showConfirmation.value = true;
};

const saveSku = async () => {
  loading.value = true;
  errors.value = {};
  try {
    let response;
    
    if (isEditing.value) {
      response = await axios.put(`/api/material-management/skus/${formSku.value.sku}`, formSku.value);
      toast.success('SKU updated successfully');
      
      const index = skus.value.findIndex(d => d.sku === formSku.value.sku);
      if (index !== -1) {
        skus.value[index] = response.data;
        selectedSku.value = response.data;
      }
    } else {
      response = await axios.post('/api/material-management/skus', formSku.value);
      toast.success('SKU created successfully');
      skus.value.push(response.data);
      selectedSku.value = response.data;
    }
    
    showFormModal.value = false;
  } catch (error) {
    console.error('Error saving SKU:', error);
    if (error.response?.data?.errors) {
      errors.value = error.response.data.errors;
    }
    toast.error(error.response?.data?.message || 'Failed to save SKU');
  } finally {
    loading.value = false;
  }
};

const deleteSku = async () => {
  if (!skuToDelete.value) return;
  
  loading.value = true;
  try {
    await axios.delete(`/api/material-management/skus/${skuToDelete.value.sku}`);
    toast.success('SKU deleted successfully');
    
    skus.value = skus.value.filter(d => d.sku !== skuToDelete.value.sku);
    
    if (selectedSku.value?.sku === skuToDelete.value.sku) {
      selectedSku.value = null;
    }
    
    showConfirmation.value = false;
    skuToDelete.value = null;
  } catch (error) {
    console.error('Error deleting SKU:', error);
    toast.error(error.response?.data?.message || 'Failed to delete SKU');
  } finally {
    loading.value = false;
  }
};

const sortBy = (field) => {
  if (sortOrder.value.field === field) {
    sortOrder.value.direction = sortOrder.value.direction === 'asc' ? 'desc' : 'asc';
  } else {
    sortOrder.value.field = field;
    sortOrder.value.direction = 'asc';
  }
};

const printSkus = () => {
  router.get('/material-management/system-requirement/inventory-setup/sku/view-print');
};

onMounted(async () => {
  await Promise.all([
    fetchSkus(),
    fetchCategories(),
    fetchUnits(),
    fetchTypes()
  ]);
});
</script>

<style scoped>
/* Button styles */
.btn-primary {
  @apply bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg shadow transition
    flex items-center justify-center whitespace-nowrap;
}

.btn-secondary {
  @apply bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg shadow transition
    flex items-center justify-center whitespace-nowrap;
}

.btn-info {
  @apply bg-cyan-600 hover:bg-cyan-700 text-white px-4 py-2 rounded-lg shadow transition
    flex items-center justify-center whitespace-nowrap;
}

.btn-danger {
  @apply bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg shadow transition
    flex items-center justify-center whitespace-nowrap;
}

.btn-blue {
  @apply bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg shadow transition
    flex items-center justify-center whitespace-nowrap;
}

/* Table hover animation */
tbody tr {
  transition: all 0.2s;
}

tbody tr:hover {
  transform: translateY(-2px);
  box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

/* Scrollbar styling */
.overflow-x-auto, .overflow-y-auto {
  scrollbar-width: thin;
  scrollbar-color: rgba(156, 163, 175, 0.5) transparent;
}

.overflow-x-auto::-webkit-scrollbar, .overflow-y-auto::-webkit-scrollbar {
  height: 8px;
  width: 8px;
}

.overflow-x-auto::-webkit-scrollbar-track, .overflow-y-auto::-webkit-scrollbar-track {
  background: transparent;
}

.overflow-x-auto::-webkit-scrollbar-thumb, .overflow-y-auto::-webkit-scrollbar-thumb {
  background-color: rgba(156, 163, 175, 0.5);
  border-radius: 20px;
}

/* Form field styles */
.form-field {
  @apply bg-gray-50 p-4 rounded-lg shadow-sm;
}

.form-label {
  @apply text-sm font-semibold text-gray-700 mb-2 flex items-center;
}

.form-input {
  @apply w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200;
}

.form-error {
  @apply mt-1 text-sm text-red-600;
}

/* Modal styles */
.modal-overlay {
  @apply fixed inset-0 bg-black bg-opacity-50;
}

.modal-container {
  @apply fixed inset-0 flex items-center justify-center z-50;
}

.modal-content {
  @apply bg-white rounded-xl shadow-2xl w-full max-w-lg z-10 relative transform transition-all duration-300 ease-in-out;
}

.modal-header {
  @apply flex items-center justify-between p-6 border-b border-gray-200 bg-gradient-to-r from-blue-600 to-indigo-800 rounded-t-xl;
}

.modal-body {
  @apply p-6 max-h-[70vh] overflow-y-auto;
}

.modal-footer {
  @apply flex justify-end space-x-3 border-t border-gray-200 pt-5 mt-4;
}
</style>
