<template>
  <div v-if="show" class="fixed inset-0 flex items-center justify-center z-50">
    <div class="absolute inset-0 bg-black opacity-50" @click="$emit('close')"></div>
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-4xl z-10 relative transform transition-all duration-300 ease-in-out">
      <!-- Modal Header -->
      <div class="flex items-center justify-between p-6 border-b border-gray-200 bg-gradient-to-r from-blue-600 to-indigo-800 rounded-t-xl">
        <div class="flex items-center">
          <div class="bg-white/20 p-2 rounded-lg mr-3">
            <i class="fas fa-calculator text-white text-xl"></i>
          </div>
          <h3 class="text-2xl font-bold text-white">{{ method && method.id ? 'Edit' : 'New' }} Computation Method</h3>
        </div>
        <button @click="$emit('close')" class="bg-white/20 hover:bg-white/30 text-white p-2 rounded-lg transition-all duration-200">
          <i class="fas fa-times"></i>
        </button>
      </div>
      
      <!-- Form Content -->
      <div class="p-6 max-h-[70vh] overflow-y-auto">
        <form @submit.prevent="submitForm">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Product Name -->
            <div class="bg-gray-50 p-4 rounded-lg shadow-sm">
              <label class="text-sm font-semibold text-gray-700 mb-2 flex items-center">
                <i class="fas fa-tag text-blue-500 mr-2"></i>
                Product Name
              </label>
              <input type="text" v-model="form.product_name"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-gray-100"
                placeholder="Select a product to see its name" readonly>
                </div>

                <!-- Product Design Dropdown -->
            <div class="bg-gray-50 p-4 rounded-lg shadow-sm">
              <label class="text-sm font-semibold text-gray-700 mb-2 flex items-center">
                <i class="fas fa-drafting-compass text-blue-500 mr-2"></i>
                Product Design
              </label>
              <select v-model="form.product_design"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 appearance-none bg-white bg-no-repeat bg-right pr-10"
                style="background-image: url('data:image/svg+xml;charset=US-ASCII,<svg xmlns=%22http://www.w3.org/2000/svg%22 width=%2224%22 height=%2224%22 viewBox=%220 0 24 24%22 fill=%22none%22 stroke=%22%23666%22 stroke-width=%222%22 stroke-linecap=%22round%22 stroke-linejoin=%22round%22><polyline points=%226 9 12 15 18 9%22></polyline></svg>'); background-size: 16px;">
                    <option value="">Select Product Design</option>
                    <option v-for="design in productDesigns" :key="design.pd_code" :value="design.pd_code">
                      {{ design.pd_code }} - {{ design.pd_name }}
                    </option>
                  </select>
                  <div v-if="errors.product_design" class="text-red-500 text-xs mt-1">{{ errors.product_design }}</div>
                </div>

                <!-- Product Dropdown -->
            <div class="bg-gray-50 p-4 rounded-lg shadow-sm">
              <label class="text-sm font-semibold text-gray-700 mb-2 flex items-center">
                <i class="fas fa-box text-blue-500 mr-2"></i>
                Product
              </label>
              <select v-model="form.product"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 appearance-none bg-white bg-no-repeat bg-right pr-10"
                style="background-image: url('data:image/svg+xml;charset=US-ASCII,<svg xmlns=%22http://www.w3.org/2000/svg%22 width=%2224%22 height=%2224%22 viewBox=%220 0 24 24%22 fill=%22none%22 stroke=%22%23666%22 stroke-width=%222%22 stroke-linecap=%22round%22 stroke-linejoin=%22round%22><polyline points=%226 9 12 15 18 9%22></polyline></svg>'); background-size: 16px;">
                    <option value="">Select Product</option>
                    <option v-for="product in products" :key="product.product_code" :value="product.product_code">
                      {{ product.product_code }} - {{ product.description }}
                    </option>
                  </select>
                  <div v-if="errors.product" class="text-red-500 text-xs mt-1">{{ errors.product }}</div>
                </div>

                <!-- Flute Dropdown -->
            <div class="bg-gray-50 p-4 rounded-lg shadow-sm">
              <label class="text-sm font-semibold text-gray-700 mb-2 flex items-center">
                <i class="fas fa-layer-group text-blue-500 mr-2"></i>
                Flute
              </label>
              <select v-model="form.flute"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 appearance-none bg-white bg-no-repeat bg-right pr-10"
                style="background-image: url('data:image/svg+xml;charset=US-ASCII,<svg xmlns=%22http://www.w3.org/2000/svg%22 width=%2224%22 height=%2224%22 viewBox=%220 0 24 24%22 fill=%22none%22 stroke=%22%23666%22 stroke-width=%222%22 stroke-linecap=%22round%22 stroke-linejoin=%22round%22><polyline points=%226 9 12 15 18 9%22></polyline></svg>'); background-size: 16px;">
                    <option value="">Select Flute</option>
                    <option v-for="flute in flutes" :key="flute.code" :value="flute.code">
                      {{ flute.code }} - {{ flute.description }}
                    </option>
                  </select>
                  <div v-if="errors.flute" class="text-red-500 text-xs mt-1">{{ errors.flute }}</div>
                </div>

                <!-- Pieces Per Bundle -->
            <div class="bg-gray-50 p-4 rounded-lg shadow-sm">
              <label class="text-sm font-semibold text-gray-700 mb-2 flex items-center">
                <i class="fas fa-boxes text-blue-500 mr-2"></i>
                Pieces Per Bundle<span class="text-red-500">*</span>
              </label>
              <input type="number" v-model.number="form.formula_pieces" min="0" required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                placeholder="Enter number of pieces">
                  <div v-if="errors.formula_pieces" class="text-red-500 text-xs mt-1">{{ errors.formula_pieces }}</div>
                </div>
            
            <!-- Formula Divisor -->
            <div class="bg-gray-50 p-4 rounded-lg shadow-sm">
              <label class="text-sm font-semibold text-gray-700 mb-2 flex items-center">
                <i class="fas fa-divide text-blue-500 mr-2"></i>
                Formula Divisor
              </label>
              <input type="number" v-model.number="form.formula_divisor" min="1"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                placeholder="Enter divisor">
                </div>

                <!-- Height Type -->
            <div class="bg-gray-50 p-4 rounded-lg shadow-sm">
              <label class="text-sm font-semibold text-gray-700 mb-2 flex items-center">
                <i class="fas fa-arrows-alt-v text-blue-500 mr-2"></i>
                Height Type
              </label>
              <select v-model="form.height_type"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 appearance-none bg-white bg-no-repeat bg-right pr-10"
                style="background-image: url('data:image/svg+xml;charset=US-ASCII,<svg xmlns=%22http://www.w3.org/2000/svg%22 width=%2224%22 height=%2224%22 viewBox=%220 0 24 24%22 fill=%22none%22 stroke=%22%23666%22 stroke-width=%222%22 stroke-linecap=%22round%22 stroke-linejoin=%22round%22><polyline points=%226 9 12 15 18 9%22></polyline></svg>'); background-size: 16px;">
                    <option value="internal">Internal</option>
                    <option value="extended">Extended</option>
                  </select>
                </div>

                <!-- Rounding Type -->
            <div class="bg-gray-50 p-4 rounded-lg shadow-sm">
              <label class="text-sm font-semibold text-gray-700 mb-2 flex items-center">
                <i class="fas fa-circle-notch text-blue-500 mr-2"></i>
                Rounding Type
              </label>
              <select v-model="form.rounding_type"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 appearance-none bg-white bg-no-repeat bg-right pr-10"
                style="background-image: url('data:image/svg+xml;charset=US-ASCII,<svg xmlns=%22http://www.w3.org/2000/svg%22 width=%2224%22 height=%2224%22 viewBox=%220 0 24 24%22 fill=%22none%22 stroke=%22%23666%22 stroke-width=%222%22 stroke-linecap=%22round%22 stroke-linejoin=%22round%22><polyline points=%226 9 12 15 18 9%22></polyline></svg>'); background-size: 16px;">
                    <option value="up">Round Up</option>
                    <option value="down">Round Down</option>
                  </select>
                </div>

                <!-- To Compute -->
            <div class="bg-gray-50 p-4 rounded-lg shadow-sm">
              <label class="text-sm font-semibold text-gray-700 mb-2 flex items-center">
                <i class="fas fa-calculator text-blue-500 mr-2"></i>
                To Compute
              </label>
              <div class="flex gap-4">
                <label class="flex items-center">
                  <input type="radio" v-model="form.is_compute" :value="true" class="w-4 h-4 text-blue-600 focus:ring-blue-500">
                      <span class="ml-2 text-sm text-gray-700">Yes</span>
                    </label>
                <label class="flex items-center">
                  <input type="radio" v-model="form.is_compute" :value="false" class="w-4 h-4 text-blue-600 focus:ring-blue-500">
                      <span class="ml-2 text-sm text-gray-700">No</span>
                    </label>
                  </div>
            </div>
          </div>
          
          <!-- Form Footer with Buttons -->
          <div class="flex justify-end space-x-3 border-t border-gray-200 pt-5 mt-4">
            <button type="button" @click="$emit('close')" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg shadow transition-all duration-200 flex items-center">
              <i class="fas fa-times mr-2"></i> Cancel
            </button>
            <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg shadow transition-all duration-200 flex items-center" :disabled="loading">
              <i class="fas fa-save mr-2"></i> {{ method && method.id ? 'Update' : 'Create' }}
              <span v-if="loading" class="ml-2 animate-spin"><i class="fas fa-spinner"></i></span>
            </button>
        </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { reactive, watch } from 'vue';

const props = defineProps({
  show: Boolean,
  method: Object,
  productDesigns: Array,
  products: Array,
  flutes: Array,
  loading: Boolean,
  errors: Object,
});

const emit = defineEmits(['close', 'save']);

const form = reactive({
  product_name: '',
  product_design: '',
  product: '',
  flute: '',
  formula_divisor: 1,
  formula_pieces: 0,
  height_type: 'internal',
  rounding_type: 'down',
  is_compute: false
});

watch(() => props.method, (newMethod) => {
  if (newMethod) {
    Object.assign(form, {
      product_name: newMethod.product_name || '',
      product_design: newMethod.product_design || '',
      product: newMethod.product || '',
      flute: newMethod.flute || '',
      formula_divisor: newMethod.formula_divisor || 1,
      formula_pieces: newMethod.formula_pieces || 0,
      height_type: newMethod.height_type || 'internal',
      rounding_type: newMethod.rounding_type || 'down',
      is_compute: newMethod.is_compute || false
    });
  } else {
    Object.assign(form, {
      product_name: '',
      product_design: '',
      product: '',
      flute: '',
      formula_divisor: 1,
      formula_pieces: 0,
      height_type: 'internal',
      rounding_type: 'down',
      is_compute: false
    });
  }
});

watch(() => form.product, (newProductCode) => {
  if (newProductCode && props.products) {
    const selectedProduct = props.products.find(p => p.product_code === newProductCode);
    if (selectedProduct) {
      form.product_name = selectedProduct.description;
    } else {
      form.product_name = '';
    }
  } else {
    form.product_name = '';
  }
});

const submitForm = () => {
  emit('save', { ...form });
}
</script> 