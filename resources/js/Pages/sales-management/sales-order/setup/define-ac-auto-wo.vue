<template>
  <Head title="Define AC# Auto WO" />
  <AppLayout header="Define AC# Auto WO">
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
                Define AC# Auto WO
              </h2>
              <p class="text-blue-100 mt-1">Configure Auto Work Order settings for Account Codes</p>
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

          <!-- Customer List Table -->
          <div class="bg-gray-50 p-5 rounded-lg shadow-sm border border-gray-100">
            <div class="overflow-x-auto">
              <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                  <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer Code</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer Name</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Activate (Tick)</th>
                  </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                  <tr v-for="customer in customers" :key="customer.code">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ customer.code }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ customer.name }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ customer.status }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                      <input type="checkbox" v-model="customer.activate" class="form-checkbox h-5 w-5 text-blue-600 rounded">
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script>
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import axios from 'axios';

export default {
  components: {
    Head,
    AppLayout,
  },
  data() {
    return {
      customers: [],
    };
  },
  mounted() {
    this.fetchCustomers();
  },
  methods: {
    async fetchCustomers() {
      try {
        const response = await axios.get('/api/customers-with-status'); // New API endpoint
        this.customers = response.data.map(customer => ({
          code: customer.customer_code,
          name: customer.customer_name,
          status: customer.status,
          activate: false, // Default to false, will be loaded from backend later
        }));
      } catch (error) {
        console.error('Error fetching customers:', error);
      }
    },
  },
};
</script>

<style scoped>
/* Add any specific styles for this component here */
</style> 