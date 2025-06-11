<template>
  <Head title="Define MC Auto WO" />
  <AppLayout header="Define MC Auto WO">
    <div class="p-5">
      <div class="bg-white shadow-lg rounded-lg overflow-hidden">
        <!-- Header Section -->
        <div class="bg-gradient-to-r from-blue-600 to-blue-800 px-6 py-4">
          <div class="flex flex-col md:flex-row justify-between items-center">
            <div>
              <h2 class="text-xl font-bold text-white flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
                Define MC Auto WO
              </h2>
              <p class="text-blue-100 mt-1">Configure Auto Work Order settings for Master Card</p>
            </div>
          </div>
        </div>
        
        <div class="p-6">
          <!-- Top Controls -->
          <div class="flex items-center justify-start space-x-2 mb-6">
            <button type="button" class="px-3 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors duration-200">
              <i class="fas fa-power-off"></i>
            </button>
            <button type="button" class="px-3 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors duration-200">
              <i class="fas fa-redo-alt"></i>
            </button>
            <button type="button" class="px-3 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors duration-200">
              <i class="fas fa-save"></i>
            </button>
            <button type="button" class="px-3 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors duration-200">
              <i class="fas fa-file-alt"></i>
            </button>
          </div>

          <!-- Customer Code Input -->
          <div class="bg-gray-50 p-5 rounded-lg shadow-sm border border-gray-100 flex items-center space-x-4">
            <label for="customer_code" class="text-gray-700 font-semibold">Customer Code:</label>
            <input type="text" id="customer_code" v-model="form.customerCode" class="w-48 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
            <button @click="showCustomerLookup = true" type="button" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
              <i class="fas fa-search"></i>
            </button>
          </div>
        </div>
      </div>
    </div>

    <CustomerLookupModal :show="showCustomerLookup" @close="showCustomerLookup = false" @customerSelected="handleCustomerSelected" />

  </AppLayout>
</template>

<script>
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import CustomerLookupModal from '@/Components/CustomerLookupModal.vue';

export default {
  components: {
    Head,
    AppLayout,
    CustomerLookupModal,
  },
  data() {
    return {
      form: {
        customerCode: '',
      },
      showCustomerLookup: false,
    };
  },
  methods: {
    handleCustomerSelected(customer) {
      this.form.customerCode = customer.customer_code;
      this.showCustomerLookup = false;
    },
  },
};
</script>

<style scoped>
/* Add any specific styles for this component here */
</style> 