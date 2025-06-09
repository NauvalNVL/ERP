<template>
  <div v-if="show" class="fixed inset-0 z-50 bg-black bg-opacity-50 flex items-center justify-center">
    <div class="bg-white rounded-lg shadow-xl w-11/12 md:w-2/3 lg:w-1/2 max-w-lg mx-auto">
      <!-- Modal Header -->
      <div class="flex items-center justify-between p-4 border-b border-gray-200" 
           :class="[
             mode === 'add' ? 'bg-gradient-to-r from-green-600 to-green-700' : 'bg-gradient-to-r from-blue-600 to-blue-700',
             'text-white rounded-t-lg'
           ]">
        <h3 class="text-xl font-semibold flex items-center text-white">
          <i :class="[mode === 'add' ? 'fas fa-plus-circle' : 'fas fa-edit', 'mr-3']"></i>
          {{ mode === 'add' ? 'Add New Master Card' : 'Edit Master Card' }}
        </h3>
        <button type="button" @click="$emit('close')" class="text-white hover:text-gray-200 focus:outline-none">
          <i class="fas fa-times text-xl"></i>
        </button>
      </div>

      <!-- Modal Body -->
      <div class="p-6">
        <div v-if="loading" class="flex justify-center items-center p-4">
          <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-500"></div>
        </div>
        <div v-else>
          <form @submit.prevent="handleSubmit">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <!-- MC Sequence -->
              <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1" for="mc_seq">MC Sequence:</label>
                <input 
                  type="text" 
                  id="mc_seq" 
                  v-model="formData.mc_seq" 
                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                  required
                >
              </div>
              
              <!-- MC Model -->
              <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1" for="mc_model">MC Model:</label>
                <input 
                  type="text" 
                  id="mc_model" 
                  v-model="formData.mc_model" 
                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                  required
                >
              </div>
              
              <!-- Customer Code -->
              <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1" for="customer_code">Customer Code:</label>
                <div class="flex">
                  <input 
                    type="text" 
                    id="customer_code" 
                    v-model="formData.customer_code" 
                    class="w-full px-3 py-2 border border-gray-300 rounded-l-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                    readonly
                  >
                  <button 
                    type="button" 
                    class="bg-gray-200 px-3 py-2 border border-l-0 border-gray-300 rounded-r-md hover:bg-gray-300"
                    @click="openCustomerModal"
                  >
                    <i class="fas fa-search"></i>
                  </button>
                </div>
              </div>
              
              <!-- Customer Name -->
              <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1" for="customer_name">Customer Name:</label>
                <input 
                  type="text" 
                  id="customer_name" 
                  v-model="formData.customer_name" 
                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                  readonly
                >
              </div>
              
              <!-- Status -->
              <div class="mb-4 col-span-1 md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Status:</label>
                <div class="flex space-x-4">
                  <label class="inline-flex items-center">
                    <input 
                      type="radio" 
                      name="status" 
                      value="pending" 
                      v-model="formData.status"
                      class="form-radio h-4 w-4 text-yellow-600"
                    >
                    <span class="ml-2 text-sm text-gray-700">Pending</span>
                  </label>
                  <label class="inline-flex items-center">
                    <input 
                      type="radio" 
                      name="status" 
                      value="active" 
                      v-model="formData.status"
                      class="form-radio h-4 w-4 text-green-600"
                    >
                    <span class="ml-2 text-sm text-gray-700">Active</span>
                  </label>
                  <label class="inline-flex items-center">
                    <input 
                      type="radio" 
                      name="status" 
                      value="obsolete" 
                      v-model="formData.status"
                      class="form-radio h-4 w-4 text-red-600"
                    >
                    <span class="ml-2 text-sm text-gray-700">Obsolete</span>
                  </label>
                </div>
              </div>
            </div>
            
            <!-- Error Message -->
            <div v-if="error" class="mt-4 p-2 bg-red-100 text-red-700 rounded-md">
              {{ error }}
            </div>

            <!-- Modal Footer -->
            <div class="mt-6 flex justify-end space-x-3">
              <button 
                type="button" 
                class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400"
                @click="$emit('close')"
              >
                Cancel
              </button>
              <button 
                type="submit" 
                class="px-4 py-2 text-white rounded-md"
                :class="mode === 'add' ? 'bg-green-600 hover:bg-green-700' : 'bg-blue-600 hover:bg-blue-700'"
                :disabled="loading"
              >
                <span v-if="loading">Saving...</span>
                <span v-else>{{ mode === 'add' ? 'Add Master Card' : 'Save Changes' }}</span>
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- Customer Account Modal -->
    <customer-account-modal
      v-if="showCustomerModal"
      :show="showCustomerModal"
      @close="showCustomerModal = false"
      @select="selectCustomer"
    />
  </div>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue';
import axios from 'axios';
import CustomerAccountModal from './customer-account-modal.vue';

const props = defineProps({
  show: {
    type: Boolean,
    required: true
  },
  masterCard: {
    type: Object,
    default: null
  },
  mode: {
    type: String,
    default: 'edit',
    validator: (value) => ['add', 'edit'].includes(value)
  }
});

const emit = defineEmits(['close', 'update']);

const loading = ref(false);
const error = ref(null);
const showCustomerModal = ref(false);
const formData = ref({
  mc_seq: '',
  mc_model: '',
  customer_code: '',
  customer_name: '',
  status: 'pending'
});

// Watch for changes in the masterCard prop
watch(() => props.masterCard, (newVal) => {
  if (newVal) {
    formData.value = { ...newVal };
  }
}, { immediate: true });

const openCustomerModal = () => {
  showCustomerModal.value = true;
};

const selectCustomer = (customer) => {
  formData.value.customer_code = customer.customer_code;
  formData.value.customer_name = customer.customer_name;
  showCustomerModal.value = false;
};

const handleSubmit = async () => {
  loading.value = true;
  error.value = null;
  
  try {
    let response;
    if (props.mode === 'edit' && props.masterCard?.id) {
      // Update existing master card
      response = await axios.put(`/api/approve-mc/${props.masterCard.id}`, formData.value);
    } else {
      // Create new master card
      response = await axios.post('/api/approve-mc', formData.value);
    }
    
    emit('update', response.data);
    emit('close');
  } catch (err) {
    console.error('Error saving master card:', err);
    error.value = err.response?.data?.message || 'Failed to save master card data.';
  } finally {
    loading.value = false;
  }
};
</script> 