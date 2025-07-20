<template>
  <div v-if="show" class="fixed inset-0 z-50 overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
      <!-- Background overlay -->
      <div class="fixed inset-0 transition-opacity" @click="closeModal">
        <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
      </div>

      <!-- Modal panel -->
      <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
        <!-- Modal header -->
        <div class="bg-gradient-to-r from-blue-600 to-blue-800 px-4 py-3 sm:px-6 flex justify-between items-center">
          <h3 class="text-lg leading-6 font-medium text-white flex items-center">
            <i class="fas fa-dollar-sign mr-2"></i> {{ title }}
          </h3>
          <button @click="closeModal" class="text-white hover:text-gray-200 focus:outline-none">
            <i class="fas fa-times"></i>
          </button>
        </div>

        <!-- Modal body -->
        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
          <form @submit.prevent="submitForm">
            <div class="space-y-4">
              <!-- SKU Information -->
              <div v-if="sku" class="bg-gray-50 p-3 rounded-lg">
                <div class="flex justify-between mb-2">
                  <span class="font-medium text-gray-700">SKU:</span>
                  <span class="text-gray-900">{{ sku.sku }}</span>
                </div>
                <div class="flex justify-between mb-2">
                  <span class="font-medium text-gray-700">Name:</span>
                  <span class="text-gray-900">{{ sku.sku_name }}</span>
                </div>
                <div class="flex justify-between">
                  <span class="font-medium text-gray-700">Category:</span>
                  <span class="text-gray-900">{{ sku.category_code }}</span>
                </div>
              </div>
              <div v-else class="bg-gray-50 p-3 rounded-lg text-center text-gray-500">
                No SKU selected
              </div>
              
              <!-- Price Field -->
              <div class="form-group">
                <label for="price" class="block text-sm font-medium text-gray-700 mb-1">
                  <i class="fas fa-dollar-sign text-green-500 mr-1"></i> Price
                </label>
                <div class="mt-1 relative rounded-md shadow-sm">
                  <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <span class="text-gray-500 sm:text-sm">Rp</span>
                  </div>
                  <input
                    type="number"
                    id="price"
                    v-model="form.price"
                    class="focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 pr-12 sm:text-sm border-gray-300 rounded-md"
                    placeholder="0.00"
                    step="0.01"
                    min="0"
                  />
                </div>
                <p v-if="errors.price" class="mt-1 text-sm text-red-600">{{ errors.price }}</p>
              </div>
              
              <!-- Min/Max Quantity Fields -->
              <div class="grid grid-cols-2 gap-4">
                <div class="form-group">
                  <label for="min_qty" class="block text-sm font-medium text-gray-700 mb-1">
                    <i class="fas fa-arrow-down text-blue-500 mr-1"></i> Min Quantity
                  </label>
                  <input
                    type="number"
                    id="min_qty"
                    v-model="form.min_qty"
                    class="focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md"
                    placeholder="0.00"
                    step="0.01"
                    min="0"
                  />
                  <p v-if="errors.min_qty" class="mt-1 text-sm text-red-600">{{ errors.min_qty }}</p>
                </div>
                
                <div class="form-group">
                  <label for="max_qty" class="block text-sm font-medium text-gray-700 mb-1">
                    <i class="fas fa-arrow-up text-blue-500 mr-1"></i> Max Quantity
                  </label>
                  <input
                    type="number"
                    id="max_qty"
                    v-model="form.max_qty"
                    class="focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md"
                    placeholder="0.00"
                    step="0.01"
                    min="0"
                  />
                  <p v-if="errors.max_qty" class="mt-1 text-sm text-red-600">{{ errors.max_qty }}</p>
                </div>
              </div>
              
              <!-- Additional Name Field -->
              <div class="form-group">
                <label for="additional_name" class="block text-sm font-medium text-gray-700 mb-1">
                  <i class="fas fa-info-circle text-blue-500 mr-1"></i> Additional Name
                </label>
                <input
                  type="text"
                  id="additional_name"
                  v-model="form.additional_name"
                  class="focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md"
                  placeholder="Enter additional name"
                  maxlength="200"
                />
                <p v-if="errors.additional_name" class="mt-1 text-sm text-red-600">{{ errors.additional_name }}</p>
              </div>
              
              <!-- Part Number Fields -->
              <div class="space-y-3">
                <div class="form-group">
                  <label for="part_number1" class="block text-sm font-medium text-gray-700 mb-1">
                    <i class="fas fa-barcode text-blue-500 mr-1"></i> Part Number 1
                  </label>
                  <input
                    type="text"
                    id="part_number1"
                    v-model="form.part_number1"
                    class="focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md"
                    placeholder="Enter part number 1"
                    maxlength="100"
                  />
                  <p v-if="errors.part_number1" class="mt-1 text-sm text-red-600">{{ errors.part_number1 }}</p>
                </div>
                
                <div class="form-group">
                  <label for="part_number2" class="block text-sm font-medium text-gray-700 mb-1">
                    <i class="fas fa-barcode text-blue-500 mr-1"></i> Part Number 2
                  </label>
                  <input
                    type="text"
                    id="part_number2"
                    v-model="form.part_number2"
                    class="focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md"
                    placeholder="Enter part number 2"
                    maxlength="100"
                  />
                  <p v-if="errors.part_number2" class="mt-1 text-sm text-red-600">{{ errors.part_number2 }}</p>
                </div>
                
                <div class="form-group">
                  <label for="part_number3" class="block text-sm font-medium text-gray-700 mb-1">
                    <i class="fas fa-barcode text-blue-500 mr-1"></i> Part Number 3
                  </label>
                  <input
                    type="text"
                    id="part_number3"
                    v-model="form.part_number3"
                    class="focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md"
                    placeholder="Enter part number 3"
                    maxlength="100"
                  />
                  <p v-if="errors.part_number3" class="mt-1 text-sm text-red-600">{{ errors.part_number3 }}</p>
                </div>
              </div>
            </div>
          </form>
        </div>

        <!-- Modal footer -->
        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
          <button 
            @click="submitForm" 
            type="button" 
            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm"
            :disabled="loading"
          >
            <i class="fas fa-save mr-2"></i> 
            <span v-if="loading">Saving...</span>
            <span v-else>Save Changes</span>
          </button>
          <button 
            @click="closeModal" 
            type="button" 
            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
          >
            Cancel
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    show: {
      type: Boolean,
      default: false
    },
    sku: {
      type: [Object, null],
      default: null
    },
    title: {
      type: String,
      default: 'Edit SKU Price'
    }
  },
  
  data() {
    return {
      loading: false,
      form: {
        price: 0,
        min_qty: 0,
        max_qty: 0,
        additional_name: '',
        part_number1: '',
        part_number2: '',
        part_number3: ''
      },
      errors: {}
    }
  },
  
  watch: {
    show(val) {
      if (val && this.sku) {
        this.resetForm();
      }
    },
    sku: {
      handler(newSku) {
        if (newSku) {
          this.resetForm();
        }
      },
      deep: true
    }
  },
  
  methods: {
    resetForm() {
      if (!this.sku) return;
      
      this.form = {
        price: this.sku.price || 0,
        min_qty: this.sku.min_qty || 0,
        max_qty: this.sku.max_qty || 0,
        additional_name: this.sku.additional_name || '',
        part_number1: this.sku.part_number1 || '',
        part_number2: this.sku.part_number2 || '',
        part_number3: this.sku.part_number3 || ''
      };
      this.errors = {};
    },
    
    closeModal() {
      this.$emit('close');
    },
    
    submitForm() {
      this.loading = true;
      this.errors = {};
      
      // Validate form
      if (!this.form.price && this.form.price !== 0) {
        this.errors.price = 'Price is required';
        this.loading = false;
        return;
      }
      
      if (this.form.price < 0) {
        this.errors.price = 'Price cannot be negative';
        this.loading = false;
        return;
      }
      
      // Emit update event with form data
      this.$emit('update', {
        sku: this.sku.sku,
        ...this.form
      });
      
      // Reset loading state after a short delay
      setTimeout(() => {
        this.loading = false;
      }, 500);
    },
    
    formatCurrency(value) {
      return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 2
      }).format(value || 0);
    }
  }
}
</script>

<style scoped>
.form-group {
  @apply mb-4;
}

input[type="number"]::-webkit-outer-spin-button,
input[type="number"]::-webkit-inner-spin-button {
  -webkit-appearance: none;
  margin: 0;
}

input[type="number"] {
  -moz-appearance: textfield;
}
</style> 