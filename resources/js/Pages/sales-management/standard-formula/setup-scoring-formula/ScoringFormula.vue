<template>
  <AppLayout :header="'Define Scoring Formula'">
    <Head title="Define Scoring Formula" />

    <!-- Hidden form with CSRF token -->
    <form ref="csrfForm" class="hidden">
        @csrf
    </form>

    <!-- Header Section -->
    <div class="bg-gradient-to-r from-cyan-700 to-blue-600 p-6 rounded-t-lg shadow-lg">
        <h2 class="text-2xl font-bold text-white mb-2 flex items-center">
            <i class="fas fa-calculator mr-3"></i> Define Scoring Formula
        </h2>
        <p class="text-cyan-100">Configure scoring formulas for product manufacturing</p>
    </div>

    <div class="bg-white rounded-b-lg shadow-lg p-6 mb-6">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column -->
            <div class="lg:col-span-2">
                <div class="bg-white p-6 rounded-lg shadow-md border-t-4 border-blue-500">
                    <div class="flex items-center mb-6 pb-2 border-b border-gray-200">
                        <div class="p-2 bg-blue-500 rounded-lg mr-3">
                            <i class="fas fa-edit text-white"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-800">Scoring Formula Management</h3>
                    </div>
                    
                    <!-- Form Fields Section -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-6">
                        <div class="col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Product Design:</label>
                            <div class="relative flex">
                                <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500">
                                    <i class="fas fa-drafting-compass"></i>
                                </span>
                                <input 
                                  type="text" 
                                  v-model="form.productDesign" 
                                  class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none border border-gray-300 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                  placeholder="B1-1CR"
                                >
                                <button 
                                  @click="openProductDesignModal"
                                  class="inline-flex items-center px-3 py-2 border border-l-0 border-gray-300 bg-blue-500 hover:bg-blue-600 text-white rounded-r-md"
                                >
                                  <i class="fas fa-search"></i>
                                </button>
                            </div>
                            
                            <label class="block text-sm font-medium text-gray-700 mb-1 mt-4">Paper Flute:</label>
                            <div class="relative flex">
                                <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500">
                                    <i class="fas fa-layer-group"></i>
                                </span>
                                <input 
                                  type="text" 
                                  v-model="form.paperFlute" 
                                  class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none border border-gray-300 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                  placeholder="Select flute"
                                >
                                <button 
                                  @click="showPaperFluteModal = true"
                                  class="inline-flex items-center px-3 py-2 border border-l-0 border-gray-300 bg-blue-500 hover:bg-blue-600 text-white rounded-r-md"
                                >
                                  <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                        
                        <div class="col-span-1">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Action:</label>
                            <button 
                              @click="openRecordModal"
                              class="w-full flex items-center justify-center px-4 py-2 bg-indigo-500 hover:bg-indigo-600 text-white rounded"
                            >
                                <i class="fas fa-search mr-2"></i> Find Records
                            </button>
                            
                            <button 
                              @click="saveFormula"
                              class="w-full mt-4 flex items-center justify-center px-4 py-2 bg-green-500 hover:bg-green-600 text-white rounded"
                            >
                                <i class="fas fa-save mr-2"></i> Save Formula
                            </button>
                        </div>
                    </div>
                    
                    <!-- Data Status Information -->
                    <div v-if="loading" class="mt-4 bg-yellow-100 p-3 rounded">
                        <div class="flex items-center">
                            <div class="mr-3">
                                <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-yellow-700"></div>
                            </div>
                            <p class="text-sm font-medium text-yellow-800">Loading paper flute data...</p>
                        </div>
                    </div>
                    <div v-else-if="paperFlutes.length === 0" class="mt-4 bg-yellow-100 p-3 rounded">
                        <p class="text-sm font-medium text-yellow-800">No paper flute data available.</p>
                        <p class="text-xs text-yellow-700 mt-1">Make sure the database is properly configured.</p>
                    </div>
                    <div v-else class="mt-4 bg-green-100 p-3 rounded">
                        <p class="text-sm font-medium text-green-800">Paper flute data available: {{ paperFlutes.length }} flutes found.</p>
                        <p v-if="form.paperFlute" class="text-xs text-green-700 mt-1">
                            Selected Paper Flute: <span class="font-semibold">{{ form.paperFlute }}</span>
                        </p>
                        <p v-if="form.productDesign" class="text-xs text-green-700 mt-1">
                            Selected Product Design: <span class="font-semibold">{{ form.productDesign }}</span>
                        </p>
                    </div>
                </div>

                <!-- Formula Preview Section -->
                <div class="bg-white p-6 rounded-lg shadow-md border-t-4 border-green-500 mt-6">
                    <div class="flex items-center mb-6 pb-2 border-b border-gray-200">
                        <div class="p-2 bg-green-500 rounded-lg mr-3">
                            <i class="fas fa-eye text-white"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-800">Formula Preview</h3>
                    </div>
                    
                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                        <div v-if="form.productDesign && form.paperFlute" class="space-y-3">
                            <div class="grid grid-cols-3 gap-4">
                                <div class="col-span-1 text-right font-medium text-gray-600">Product Design:</div>
                                <div class="col-span-2 font-semibold text-gray-800">{{ form.productDesign }}</div>
                            </div>
                            <div class="grid grid-cols-3 gap-4">
                                <div class="col-span-1 text-right font-medium text-gray-600">Paper Flute:</div>
                                <div class="col-span-2 font-semibold text-gray-800">{{ form.paperFlute }}</div>
                            </div>
                            <!-- Add more formula details here as needed -->
                        </div>
                        <div v-else class="text-center text-gray-500 py-6">
                            <i class="fas fa-info-circle text-blue-500 text-xl mb-2"></i>
                            <p>Select Product Design and Paper Flute to see formula details</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Right Column - Quick Info -->
            <div class="lg:col-span-1">
                <!-- Formula Info Card -->
                <div class="bg-white p-6 rounded-lg shadow-md border-t-4 border-teal-500 mb-6">
                    <div class="flex items-center mb-4 pb-2 border-b border-gray-200">
                        <div class="p-2 bg-teal-500 rounded-lg mr-3">
                            <i class="fas fa-info-circle text-white"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-800">Scoring Formula Info</h3>
                    </div>

                    <div class="space-y-4">
                        <div class="p-4 bg-teal-50 rounded-lg">
                            <h4 class="text-sm font-semibold text-teal-800 uppercase tracking-wider mb-2">Instructions</h4>
                            <ul class="list-disc pl-5 text-sm text-gray-600 space-y-1">
                                <li>Select a Product Design for the scoring formula</li>
                                <li>Choose a Paper Flute from the available options</li>
                                <li>Use the search buttons to find existing items</li>
                                <li>Save the formula when you're finished</li>
                            </ul>
                        </div>

                        <div class="p-4 bg-blue-50 rounded-lg">
                            <h4 class="text-sm font-semibold text-blue-800 uppercase tracking-wider mb-2">Common Formulas</h4>
                            <div class="grid grid-cols-1 gap-2 text-sm">
                                <div class="flex items-center">
                                    <span class="w-6 h-6 flex items-center justify-center bg-green-600 text-white rounded-full font-bold mr-2">01</span>
                                    <span>B1-1CR / BC-Flute</span>
                                </div>
                                <div class="flex items-center">
                                    <span class="w-6 h-6 flex items-center justify-center bg-blue-600 text-white rounded-full font-bold mr-2">02</span>
                                    <span>B2-2CR / E-Flute</span>
                                </div>
                                <div class="flex items-center">
                                    <span class="w-6 h-6 flex items-center justify-center bg-purple-600 text-white rounded-full font-bold mr-2">03</span>
                                    <span>C1-1CR / C-Flute</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Links -->
                <div class="bg-white p-6 rounded-lg shadow-md border-t-4 border-purple-500">
                    <div class="flex items-center mb-4 pb-2 border-b border-gray-200">
                        <div class="p-2 bg-purple-500 rounded-lg mr-3">
                            <i class="fas fa-link text-white"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-800">Quick Links</h3>
                    </div>

                    <div class="grid grid-cols-1 gap-3">
                        <Link href="/product-design" class="flex items-center p-3 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                            <div class="p-2 bg-blue-500 rounded-full mr-3">
                                <i class="fas fa-drafting-compass text-white text-sm"></i>
                            </div>
                            <div>
                                <p class="font-medium text-blue-900">Product Design</p>
                                <p class="text-xs text-blue-700">Manage product designs</p>
                            </div>
                        </Link>

                        <Link href="/paper-flute" class="flex items-center p-3 bg-purple-50 rounded-lg hover:bg-purple-100 transition-colors">
                            <div class="p-2 bg-purple-500 rounded-full mr-3">
                                <i class="fas fa-layer-group text-white text-sm"></i>
                            </div>
                            <div>
                                <p class="font-medium text-purple-900">Paper Flutes</p>
                                <p class="text-xs text-purple-700">Manage flute options</p>
                            </div>
                        </Link>

                        <Link href="/standard-formula-configuration" class="flex items-center p-3 bg-green-50 rounded-lg hover:bg-green-100 transition-colors">
                            <div class="p-2 bg-green-500 rounded-full mr-3">
                                <i class="fas fa-cogs text-white text-sm"></i>
                            </div>
                            <div>
                                <p class="font-medium text-green-900">Standard Formula</p>
                                <p class="text-xs text-green-700">Configure standards</p>
                            </div>
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Paper Flute Modal -->
    <PapeFluteSelector
      :show="showPaperFluteModal"
      :flutes="paperFlutes"
      @select="onFluteSelected"
      @close="showPaperFluteModal = false"
    />

    <!-- Product Design Modal -->
    <ProductDesignModal
      :show="showProductDesignModal"
      :designs="productDesigns"
      :products="[]"
      @select="onProductDesignSelected"
      @close="showProductDesignModal = false"
    />

    <!-- Paper Flute Table Modal -->
    <div v-if="showPaperFluteModal" class="fixed inset-0 z-50 overflow-y-auto">
      <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
          <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>

        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
          <div class="bg-gradient-to-r from-blue-600 to-indigo-700 px-4 py-3 sm:px-6 flex justify-between items-center">
            <h3 class="text-lg leading-6 font-medium text-white flex items-center">
              <i class="fas fa-layer-group mr-2"></i>
              Paper Flute Table
            </h3>
            <button @click="showPaperFluteModal = false" class="text-white hover:text-gray-200 focus:outline-none">
              <i class="fas fa-times"></i>
            </button>
          </div>
          
          <div class="bg-gray-50 px-4 py-3">
            <div class="relative rounded-md shadow-sm">
              <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i class="fas fa-search text-gray-400"></i>
              </div>
              <input type="text" v-model="fluteSearch" class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="Search flutes...">
            </div>
          </div>

          <div class="max-h-96 overflow-y-auto">
            <table class="min-w-full divide-y divide-gray-200">
              <thead class="bg-gray-100 sticky top-0">
                <tr>
                  <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider bg-blue-50 border-b-2 border-blue-200">
                    Paper Flute
                  </th>
                  <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider bg-blue-50 border-b-2 border-blue-200">
                    Description
                  </th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                <tr v-for="flute in filteredFlutes" :key="flute.code" 
                    @click="onFluteSelected(flute)" 
                    class="hover:bg-blue-50 cursor-pointer transition-colors duration-150">
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                      <div class="flex-shrink-0 h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center">
                        <span class="text-blue-700 font-semibold">{{ flute.code.charAt(0) }}</span>
                      </div>
                      <div class="ml-4">
                        <div class="text-sm font-medium text-gray-900">{{ flute.code }}</div>
                      </div>
                    </div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm text-gray-900">{{ flute.description || 'No description' }}</div>
                  </td>
                </tr>
                <tr v-if="filteredFlutes.length === 0">
                  <td colspan="2" class="px-6 py-4 text-center text-sm text-gray-500">
                    <div class="flex flex-col items-center py-6">
                      <i class="fas fa-search text-gray-400 text-3xl mb-2"></i>
                      <p>No flutes found matching your search</p>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

          <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
            <button @click="showPaperFluteModal = false" type="button" class="w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
              Cancel
            </button>
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
             'bg-yellow-100 border-l-4 border-yellow-500': notification.type === 'warning',
             'bg-blue-100 border-l-4 border-blue-500': notification.type === 'info'
         }">
      <div class="p-4 flex items-center">
        <div class="mr-3">
          <i v-if="notification.type === 'success'" class="fas fa-check-circle text-green-500 text-xl"></i>
          <i v-else-if="notification.type === 'error'" class="fas fa-exclamation-circle text-red-500 text-xl"></i>
          <i v-else-if="notification.type === 'warning'" class="fas fa-exclamation-triangle text-yellow-500 text-xl"></i>
          <i v-else class="fas fa-info-circle text-blue-500 text-xl"></i>
        </div>
        <div>
          <p :class="{
              'text-green-800': notification.type === 'success',
              'text-red-800': notification.type === 'error',
              'text-yellow-800': notification.type === 'warning',
              'text-blue-800': notification.type === 'info'
          }">{{ notification.message }}</p>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import PapeFluteSelector from '@/Components/paper-flute-selector-modal.vue';
import ProductDesignModal from '@/Components/product-design-modal.vue';

// Form data
const form = ref({
  product_design_id: '',
  productDesign: '',
  paper_flute_id: '',
  paperFlute: '',
  scoring_length_formula: [],
  scoring_width_formula: [],
  length_conversion: 7.00,
  width_conversion: 7.00,
  height_conversion: 12.00,
  is_active: true,
  notes: ''
});

// Paper flute data
const paperFlutes = ref([]);
const showPaperFluteModal = ref(false);
const loading = ref(false);
const notification = ref({ show: false, message: '', type: 'success' });
const fluteSearch = ref('');

// Computed property for filtered flutes
const filteredFlutes = computed(() => {
  if (!fluteSearch.value) return paperFlutes.value;
  const searchTerm = fluteSearch.value.toLowerCase();
  return paperFlutes.value.filter(flute => 
    flute.code.toLowerCase().includes(searchTerm) || 
    (flute.description && flute.description.toLowerCase().includes(searchTerm))
  );
});

// Reference to the CSRF form
const csrfForm = ref(null);

// Add product designs data
const productDesigns = ref([]);
const showProductDesignModal = ref(false);

// Function to get fresh CSRF token from the form
const getCsrfToken = () => {
    // Try to get token from meta tag first
    let token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    
    // If token from meta tag is not available or we want a fresh token, get from the form
    if (csrfForm.value) {
        const tokenInput = csrfForm.value.querySelector('input[name="_token"]');
        if (tokenInput) {
            token = tokenInput.value;
        }
    }
    
    return token || '';
};

// Fetch paper flutes from API
const fetchPaperFlutes = async () => {
  loading.value = true;
  try {
    const res = await fetch('/api/paper-flutes', { 
      headers: { 
        'Accept': 'application/json',
        'X-Requested-With': 'XMLHttpRequest'
      } 
    });
    
    if (!res.ok) {
      throw new Error('Network response was not ok');
    }
    
    const data = await res.json();
    
    if (Array.isArray(data)) {
      paperFlutes.value = data;
    } else {
      paperFlutes.value = [];
      console.error('Unexpected data format:', data);
      showNotification('Error loading paper flutes: Invalid data format', 'error');
    }
  } catch (e) {
    console.error('Error fetching paper flutes:', e);
    paperFlutes.value = [];
    showNotification('Error loading paper flutes: ' + e.message, 'error');
  } finally {
    loading.value = false;
  }
};

// Handle flute selection from modal
const onFluteSelected = (flute) => {
  form.value.paperFlute = flute.code;
  form.value.paper_flute_id = flute.id;
  showPaperFluteModal.value = false;
  showNotification(`Selected paper flute: ${flute.code}`, 'success');
};

// Add function to fetch product designs
const fetchProductDesigns = async () => {
  loading.value = true;
  try {
    const res = await fetch('/api/product-designs', { 
      headers: { 
        'Accept': 'application/json',
        'X-Requested-With': 'XMLHttpRequest'
      } 
    });
    
    if (!res.ok) {
      throw new Error('Network response was not ok');
    }
    
    const data = await res.json();
    
    if (Array.isArray(data)) {
      productDesigns.value = data;
    } else {
      productDesigns.value = [];
      console.error('Unexpected data format:', data);
      showNotification('Error loading product designs: Invalid data format', 'error');
    }
  } catch (e) {
    console.error('Error fetching product designs:', e);
    productDesigns.value = [];
    showNotification('Error loading product designs: ' + e.message, 'error');
  } finally {
    loading.value = false;
  }
};

// Add function to handle product design selection
const onProductDesignSelected = (design) => {
  form.value.productDesign = design.pd_code;
  form.value.product_design_id = design.id;
  showProductDesignModal.value = false;
  showNotification(`Selected product design: ${design.pd_code}`, 'success');
};

// Open product design modal
const openProductDesignModal = () => {
  showProductDesignModal.value = true;
};

// Open record modal
const openRecordModal = () => {
  // This would be implemented to show a record selection modal
  showNotification('Record selection not implemented in this demo', 'info');
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

// Add function to save the formula
const saveFormula = async () => {
  if (!form.value.product_design_id || !form.value.paper_flute_id) {
    showNotification('Please select both Product Design and Paper Flute', 'warning');
    return;
  }
  
  loading.value = true;
  
  try {
    // Get fresh CSRF token
    const csrfToken = getCsrfToken();
    
    const response = await fetch('/api/scoring-formulas', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': csrfToken,
        'Accept': 'application/json',
        'X-Requested-With': 'XMLHttpRequest'
      },
      body: JSON.stringify({
        product_design_id: form.value.product_design_id,
        paper_flute_id: form.value.paper_flute_id,
        scoring_length_formula: form.value.scoring_length_formula,
        scoring_width_formula: form.value.scoring_width_formula,
        length_conversion: form.value.length_conversion,
        width_conversion: form.value.width_conversion,
        height_conversion: form.value.height_conversion,
        is_active: form.value.is_active,
        notes: form.value.notes
      }),
      credentials: 'same-origin'
    });
    
    const result = await response.json();
    
    if (response.ok && result.success) {
      showNotification('Scoring formula saved successfully', 'success');
      // Optionally reset form or redirect
    } else {
      showNotification(result.message || 'Error saving formula', 'error');
    }
  } catch (e) {
    console.error('Error saving formula:', e);
    showNotification('Error saving formula: ' + e.message, 'error');
  } finally {
    loading.value = false;
  }
};

// Load data when component mounts
onMounted(() => {
  fetchPaperFlutes();
  fetchProductDesigns();
  
  // Initialize empty scoring formula arrays
  form.value.scoring_length_formula = [
    { index: 1, value: '' },
    { index: 2, value: '' },
    { index: 3, value: '' }
  ];
  
  form.value.scoring_width_formula = [
    { index: 1, value: '' },
    { index: 2, value: '' }
  ];
});
</script>

<style scoped>
/* Add any additional styling here */
</style>
