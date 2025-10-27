<template>
  <AppLayout :header="'Define Scoring Formula'">
    <Head title="Define Scoring Formula" />

    <!-- Hidden form with CSRF token -->
    <form ref="csrfForm" class="hidden">
        @csrf
    </form>

    <!-- Header Section with animated elements -->
    <div class="bg-gradient-to-r from-purple-600 via-indigo-600 to-blue-600 p-6 rounded-t-lg shadow-lg overflow-hidden relative">
        <!-- Decorative Elements -->
        <div class="absolute top-0 right-0 w-40 h-40 bg-white opacity-5 rounded-full -translate-y-20 translate-x-20"></div>
        <div class="absolute bottom-0 left-0 w-20 h-20 bg-white opacity-5 rounded-full translate-y-10 -translate-x-10"></div>
        <div class="absolute bottom-0 right-0 w-32 h-32 bg-yellow-400 opacity-5 rounded-full translate-y-10 translate-x-10"></div>
        
        <div class="flex items-center">
            <div class="bg-gradient-to-br from-pink-500 to-purple-600 p-3 rounded-lg shadow-inner flex items-center justify-center relative overflow-hidden mr-4">
                <div class="absolute -top-1 -right-1 w-6 h-6 bg-yellow-300 opacity-30 rounded-full animate-ping-slow"></div>
                <div class="absolute -bottom-1 -left-1 w-4 h-4 bg-blue-300 opacity-30 rounded-full animate-ping-slow animation-delay-500"></div>
                <i class="fas fa-calculator text-white text-2xl z-10"></i>
            </div>
            <div>
                <h2 class="text-2xl md:text-3xl font-bold text-white mb-1 text-shadow">Define Scoring Formula</h2>
                <p class="text-blue-100 max-w-2xl">Configure scoring formulas for product manufacturing</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-b-lg shadow-lg p-6 mb-6 bg-gradient-to-br from-white to-indigo-50">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column -->
            <div class="lg:col-span-2">
                <div class="bg-white p-6 rounded-lg shadow-md border-t-4 border-indigo-500 transition-all duration-300 hover:shadow-lg transform hover:-translate-y-1 relative overflow-hidden">
                    <div class="absolute -top-20 -right-20 w-40 h-40 bg-indigo-50 rounded-full opacity-20"></div>
                    <div class="absolute -bottom-8 -left-8 w-24 h-24 bg-purple-50 rounded-full opacity-20"></div>
                    
                    <div class="flex items-center mb-6 pb-2 border-b border-gray-200 relative z-10">
                        <div class="p-2 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-lg mr-3 shadow-md">
                            <i class="fas fa-edit text-white"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-800">Scoring Formula Management</h3>
                    </div>
                    
                    <!-- Form Fields Section -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label for="productDesign" class="block text-sm font-medium text-gray-700 mb-1 flex items-center">
                                <span class="inline-flex items-center justify-center w-5 h-5 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-full mr-2 shadow-sm">
                                    <i class="fas fa-drafting-compass text-white text-xs"></i>
                                </span>
                                Product Design:
                            </label>
                            <div class="relative flex group">
                                <input 
                                  type="text" 
                                  id="productDesign"
                                  v-model="form.productDesign" 
                                  class="flex-1 min-w-0 block w-full px-3 py-2 rounded-l-md border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 transition-all group-hover:border-indigo-300"
                                  placeholder="Enter or select product design"
                                >
                                <button 
                                  type="button"
                                  @click="openProductDesignModal"
                                  class="inline-flex items-center px-3 py-2 border border-l-0 border-gray-300 bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 text-white rounded-r-md transition-all transform active:translate-y-px relative overflow-hidden shadow-sm"
                                >
                                  <span class="absolute inset-0 bg-white opacity-0 hover:opacity-20 transition-opacity"></span>
                                  <i class="fas fa-search relative z-10"></i>
                                </button>
                            </div>
                        </div>
                        
                        <div>
                             <label for="paperFlute" class="block text-sm font-medium text-gray-700 mb-1 flex items-center">
                                <span class="inline-flex items-center justify-center w-5 h-5 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-full mr-2 shadow-sm">
                                    <i class="fas fa-layer-group text-white text-xs"></i>
                                </span>
                                Paper Flute:
                            </label>
                            <div class="relative flex group">
                                <input 
                                  type="text" 
                                  id="paperFlute"
                                  v-model="form.paperFlute" 
                                  class="flex-1 min-w-0 block w-full px-3 py-2 rounded-l-md border border-gray-300 focus:ring-blue-500 focus:border-blue-500 transition-all group-hover:border-blue-300"
                                  placeholder="Enter or select paper flute"
                                >
                                <button
                                  type="button"
                                  @click="showPaperFluteModal = true"
                                  class="inline-flex items-center px-3 py-2 border border-l-0 border-gray-300 bg-gradient-to-r from-blue-500 to-cyan-600 hover:from-blue-600 hover:to-cyan-700 text-white rounded-r-md transition-all transform active:translate-y-px relative overflow-hidden shadow-sm"
                                >
                                  <span class="absolute inset-0 bg-white opacity-0 hover:opacity-20 transition-opacity"></span>
                                  <i class="fas fa-search relative z-10"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                         <!-- Action Buttons -->
                        <div class="flex items-end space-x-4">
                            <button 
                              @click="openRecordModal"
                              class="text-white px-4 py-2 rounded-lg flex items-center space-x-2 transform active:translate-y-px transition-all duration-300 shadow-md relative overflow-hidden group bg-gradient-to-r from-blue-500 to-cyan-600 hover:from-blue-600 hover:to-cyan-700"
                            >
                                <span class="absolute inset-0 bg-white opacity-0 group-hover:opacity-20 transition-opacity"></span>
                                <div class="bg-white bg-opacity-30 rounded-full p-1.5 mr-2 flex items-center justify-center">
                                    <i class="fas fa-search text-white text-xs"></i>
                                </div>
                                <span>Find Records</span>
                            </button>
                            
                            <button 
                              @click="saveFormula"
                              class="text-white px-4 py-2 rounded-lg flex items-center space-x-2 transform active:translate-y-px transition-all duration-300 shadow-md relative overflow-hidden group bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700"
                            >
                                <span class="absolute inset-0 bg-white opacity-0 group-hover:opacity-20 transition-opacity"></span>
                                <div class="bg-white bg-opacity-30 rounded-full p-1.5 mr-2 flex items-center justify-center">
                                    <i class="fas fa-save text-white text-xs"></i>
                                </div>
                                <span>Save Formula</span>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Data Status Information -->
                    <div v-if="loading" class="mt-6 bg-yellow-100 p-3 rounded-lg border border-yellow-200">
                        <div class="flex items-center">
                            <div class="mr-3">
                                <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-yellow-700"></div>
                            </div>
                            <p class="text-sm font-medium text-yellow-800">Loading data...</p>
                        </div>
                    </div>
                    <div v-else-if="paperFlutes.length === 0 && productDesigns.length === 0" class="mt-6 bg-red-100 p-3 rounded-lg border border-red-200">
                        <p class="text-sm font-medium text-red-800">No data available.</p>
                        <p class="text-xs text-red-700 mt-1">Make sure the database is properly configured and APIs are running.</p>
                    </div>
                    <div v-else class="mt-6 bg-green-100 p-3 rounded-lg border border-green-200">
                        <p class="text-sm font-medium text-green-800">Data loaded successfully. Ready to configure formulas.</p>
                        <p v-if="form.paperFlute" class="text-xs text-green-700 mt-1">
                            Selected Paper Flute: <span class="font-semibold">{{ form.paperFlute }}</span>
                        </p>
                        <p v-if="form.productDesign" class="text-xs text-green-700 mt-1">
                            Selected Product Design: <span class="font-semibold">{{ form.productDesign }}</span>
                        </p>
                    </div>
                    
                    <!-- Formula Preview Section -->
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <div class="flex items-center mb-6">
                            <div class="p-2 bg-gradient-to-r from-green-500 to-emerald-600 rounded-lg mr-3 shadow-md">
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
            </div>
            
            <!-- Right Column - Quick Info -->
            <div class="lg:col-span-1">
                <!-- Formula Info Card -->
                <div class="bg-white p-6 rounded-lg shadow-md border-t-4 border-teal-500 mb-6 transition-all duration-300 hover:shadow-lg transform hover:-translate-y-1 relative overflow-hidden">
                    <div class="absolute -top-16 -right-16 w-32 h-32 bg-teal-50 rounded-full opacity-20"></div>
                    <div class="absolute -bottom-6 -left-6 w-20 h-20 bg-green-50 rounded-full opacity-20"></div>

                    <div class="flex items-center mb-4 pb-2 border-b border-gray-200 relative z-10">
                        <div class="p-2 bg-gradient-to-r from-teal-500 to-green-500 rounded-lg mr-3 shadow-md">
                            <i class="fas fa-info-circle text-white"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-800">Scoring Formula Info</h3>
                    </div>

                    <div class="space-y-4">
                        <div class="p-4 bg-teal-50 rounded-lg">
                             <h4 class="text-sm font-semibold text-teal-800 uppercase tracking-wider mb-2 flex items-center">
                                <span class="inline-flex items-center justify-center w-5 h-5 bg-gradient-to-r from-blue-400 to-indigo-400 rounded-full mr-2 shadow-sm mt-0.5">
                                    <i class="fas fa-question text-white text-xs"></i>
                                </span>
                                How to Use
                            </h4>
                            <ul class="text-sm text-gray-600 space-y-2">
                                <li class="flex items-start">
                                    <span class="inline-flex items-center justify-center w-5 h-5 bg-gradient-to-r from-blue-400 to-indigo-400 rounded-full mr-2 shadow-sm mt-0.5">
                                        <i class="fas fa-check text-white text-xs"></i>
                                    </span>
                                    <span>Select a Product Design for the scoring formula.</span>
                                </li>
                                <li class="flex items-start">
                                    <span class="inline-flex items-center justify-center w-5 h-5 bg-gradient-to-r from-purple-400 to-pink-400 rounded-full mr-2 shadow-sm mt-0.5">
                                        <i class="fas fa-check text-white text-xs"></i>
                                    </span>
                                    <span>Choose a Paper Flute from the available options.</span>
                                </li>
                                <li class="flex items-start">
                                    <span class="inline-flex items-center justify-center w-5 h-5 bg-gradient-to-r from-amber-400 to-orange-400 rounded-full mr-2 shadow-sm mt-0.5">
                                        <i class="fas fa-check text-white text-xs"></i>
                                    </span>
                                    <span>Use the search buttons to find existing items.</span>
                                </li>
                                <li class="flex items-start">
                                    <span class="inline-flex items-center justify-center w-5 h-5 bg-gradient-to-r from-green-400 to-emerald-400 rounded-full mr-2 shadow-sm mt-0.5">
                                        <i class="fas fa-check text-white text-xs"></i>
                                    </span>
                                    <span>Save the formula when you're finished.</span>
                                </li>
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
                <div class="bg-white p-6 rounded-lg shadow-md border-t-4 border-purple-500 transition-all duration-300 hover:shadow-lg transform hover:-translate-y-1">
                    <div class="flex items-center mb-4 pb-2 border-b border-gray-200">
                        <div class="p-2 bg-gradient-to-r from-purple-500 to-pink-500 rounded-lg mr-3 shadow-md">
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

    <!-- Product Design Modal -->
    <ProductDesignModal
      :show="showProductDesignModal"
      :designs="productDesigns"
      :products="products"
      @select="onProductDesignSelected"
      @close="showProductDesignModal = false"
      double-click-action="select"
    />

    <!-- Paper Flute Modal -->
    <PaperFluteModal
        :show="showPaperFluteModal"
        :flutes="paperFlutes"
        @select="onFluteSelected"
        @close="showPaperFluteModal = false"
    />


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
import ProductDesignModal from '@/Components/product-design-modal.vue';
import PaperFluteModal from '@/Components/paper-flute-modal.vue'; // Import the new modal
import axios from 'axios';

// Form data
const form = ref({
  product_design_id: '',
  productDesign: '',
  paper_flute_code: '',
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

// Reference to the CSRF form
const csrfForm = ref(null);

// Add product designs data
const productDesigns = ref([]);
const showProductDesignModal = ref(false);
const products = ref([]);

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

const fetchProducts = async () => {
  loading.value = true;
  try {
    const res = await fetch('/api/products', {
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
      products.value = data;
    } else {
      products.value = [];
      console.error('Unexpected data format for products:', data);
      showNotification('Error loading products: Invalid data format', 'error');
    }
  } catch (e) {
    console.error('Error fetching products:', e);
    products.value = [];
    showNotification('Error loading products: ' + e.message, 'error');
  } finally {
    loading.value = false;
  }
};

// Handle flute selection from modal
const onFluteSelected = (flute) => {
  console.log("Selected flute:", flute);
  if (!flute || !(flute.code || flute.Flute)) {
    console.error("Invalid flute object:", flute);
    showNotification("Error selecting flute: Invalid data", 'error');
    return;
  }

  // Set the display value
  const code = flute.code || flute.Flute;
  form.value.paperFlute = code;
  // Store soft reference code to be sent to backend
  form.value.paper_flute_code = code;
  
  showPaperFluteModal.value = false;
  showNotification(`Selected paper flute: ${code}`, 'success');
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
  console.log("Selected product design:", design);
  if (!design || !design.pd_code) {
    console.error("Invalid product design object:", design);
    showNotification("Error selecting product design: Invalid data", 'error');
    return;
  }

  // Set the display value
  form.value.productDesign = design.pd_code;
  
  // IMPORTANT FIX: For product designs with codes like "APP" or "LAYER", we need to use the actual ID
  // First, log the full design object to see what's available
  console.log("Full design object:", design);
  
  // Try to get a valid numeric ID
  let validId = null;
  
  // First try from the selected design
  if (design.id !== undefined && design.id !== null) {
    const parsedId = parseInt(design.id, 10);
    if (!isNaN(parsedId)) {
      validId = parsedId;
      console.log("Using ID directly from selected design:", validId);
    }
  }
  
  // If that didn't work, try to find the design in our cached list
  if (validId === null) {
    // Log the product designs list to debug
    console.log("Available product designs:", productDesigns.value);
    
    const matchedDesign = productDesigns.value.find(d => d.pd_code === design.pd_code);
    console.log("Matched design from cache:", matchedDesign);
    
    if (matchedDesign && matchedDesign.id !== undefined && matchedDesign.id !== null) {
      const parsedId = parseInt(matchedDesign.id, 10);
      if (!isNaN(parsedId)) {
        validId = parsedId;
        console.log("Found ID from cached designs list:", validId);
      }
    }
  }
  
  // FALLBACK: If we still don't have an ID, make a direct API call to get the design by code
  if (validId === null) {
    console.log("Attempting to fetch product design by code:", design.pd_code);
    fetchProductDesignByCode(design.pd_code);
  } else {
    // Set the ID if we found it
    form.value.product_design_id = validId;
    console.log("Set product_design_id to:", validId);
  }
  
  showProductDesignModal.value = false;
  showNotification(`Selected product design: ${design.pd_code}`, 'success');
};

// Add new function to fetch product design by code
const fetchProductDesignByCode = async (code) => {
  loading.value = true;
  try {
    console.log("Fetching product design by code:", code);
    
    const response = await fetch(`/api/product-designs/by-code/${encodeURIComponent(code)}`, {
      headers: {
        'Accept': 'application/json',
        'X-Requested-With': 'XMLHttpRequest'
      }
    });
    
    if (!response.ok) {
      throw new Error(`Failed to fetch product design by code: ${response.status}`);
    }
    
    const data = await response.json();
    console.log("API response for product design by code:", data);
    
    if (data && data.id) {
      form.value.product_design_id = parseInt(data.id, 10);
      console.log("Set product_design_id from API lookup:", form.value.product_design_id);
    } else {
      console.error("API returned data but no valid ID for code:", code);
      form.value.product_design_id = null;
      showNotification(`Could not find a valid ID for product design: ${code}. Please try selecting again or contact support.`, 'warning');
    }
  } catch (error) {
    console.error("Error fetching product design by code:", error);
    form.value.product_design_id = null;
    showNotification(`Error looking up product design: ${error.message}`, 'error');
  } finally {
    loading.value = false;
  }
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
  // Add more detailed debugging
  console.log("=== SAVE FORMULA DEBUG INFO ===");
  console.log("Form object:", form.value);
  console.log("product_design_id (raw):", form.value.product_design_id);
  console.log("product_design_id (type):", typeof form.value.product_design_id);
  console.log("paper_flute_code (raw):", form.value.paper_flute_code);
  console.log("paper_flute_code (type):", typeof form.value.paper_flute_code);
  
  // Check if both values are present
  if (!form.value.product_design_id || !form.value.paper_flute_code) {
    console.log("VALIDATION FAILED: Missing IDs");
    console.log("product_design_id exists:", !!form.value.product_design_id);
    console.log("paper_flute_code exists:", !!form.value.paper_flute_code);
    showNotification('Please select both Product Design and Paper Flute', 'warning');
    return;
  }
  const productDesignId = Number(form.value.product_design_id);
  const paperFluteCode = String(form.value.paper_flute_code);
  console.log("productDesignId:", productDesignId, "isNaN:", isNaN(productDesignId));
  console.log("paperFluteId:", paperFluteId, "isNaN:", isNaN(paperFluteId));
  
  if (isNaN(productDesignId) || isNaN(paperFluteId)) {
    console.log("VALIDATION FAILED: Invalid numeric IDs");
    showNotification('Invalid ID values. Please reselect Product Design and Paper Flute', 'warning');
    return;
  }
  
  loading.value = true;
  
  try {
    // Get CSRF token from meta tag
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    console.log("CSRF token from meta tag:", csrfToken ? "Found" : "Not found");
    
    if (!csrfToken) {
      console.error("CSRF token not found in meta tag");
      showNotification('CSRF token not found. Please refresh the page and try again.', 'error');
      loading.value = false;
      return;
    }
    
    const payload = {
      product_design_id: productDesignId,
      paper_flute_code: paperFluteCode,
      scoring_length_formula: form.value.scoring_length_formula,
      scoring_width_formula: form.value.scoring_width_formula,
      length_conversion: parseFloat(form.value.length_conversion),
      width_conversion: parseFloat(form.value.width_conversion),
      height_conversion: parseFloat(form.value.height_conversion),
      is_active: form.value.is_active,
      notes: form.value.notes
    };
    
    console.log("Request body:", payload);
    
    // Use axios instead of fetch for better handling of CSRF
    const response = await axios.post('/api/scoring-formulas', payload, {
      headers: {
        'X-CSRF-TOKEN': csrfToken,
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-Requested-With': 'XMLHttpRequest'
      }
    });
    
    console.log("API response:", response.data);
    
    if (response.data && response.data.success) {
      showNotification('Scoring formula saved successfully', 'success');
      // Optionally reset form or redirect
    } else {
      showNotification(response.data?.message || 'Error saving formula', 'error');
    }
  } catch (e) {
    console.error('Error saving formula:', e);
    if (e.response && e.response.status === 419) {
      showNotification('CSRF token mismatch. Please refresh the page and try again.', 'error');
    } else {
      showNotification('Error saving formula: ' + (e.response?.data?.message || e.message), 'error');
    }
  } finally {
    loading.value = false;
  }
};

// Load data when component mounts
onMounted(() => {
  fetchPaperFlutes();
  fetchProductDesigns();
  fetchProducts();
  
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

<style>
.text-shadow {
  text-shadow: 1px 1px 2px rgba(0,0,0,0.3);
}

.animate-ping-slow {
  animation: ping 2s cubic-bezier(0, 0, 0.2, 1) infinite;
}

.animation-delay-500 {
  animation-delay: 0.5s;
}
</style>

<style scoped>
/* Add any additional styling here */
</style>

