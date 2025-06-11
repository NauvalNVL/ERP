<template>
  <Head title="Define SO Period" />
  <AppLayout header="Define SO Period">
    <div class="p-5">
      <div class="bg-white shadow-lg rounded-lg overflow-hidden">
        <!-- Header Section -->
        <div class="bg-gradient-to-r from-blue-600 to-blue-800 px-6 py-4">
          <div class="flex flex-col md:flex-row justify-between items-center">
            <div>
              <h2 class="text-xl font-bold text-white flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                Define SO Period
              </h2>
              <p class="text-blue-100 mt-1">Configure Sales Order Period settings</p>
            </div>
          </div>
        </div>
        
        <form @submit.prevent="saveConfiguration" class="p-6">
          <!-- Main Form Section -->
          <div class="bg-gray-50 p-5 rounded-lg shadow-sm border border-gray-100 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 items-center mb-4">
              <label for="current_period_month" class="text-gray-700">Current Period:</label>
              <div class="flex items-center space-x-2">
                <input type="number" id="current_period_month" v-model="form.currentPeriodMonth" class="w-20 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                <input type="number" id="current_period_year" v-model="form.currentPeriodYear" class="w-20 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                <span class="text-gray-500">mm/yyyy</span>
              </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 items-center mb-4">
              <label for="forward_period" class="text-gray-700">Forward Period:</label>
              <div class="flex items-center space-x-2">
                <input type="number" id="forward_period" v-model="form.forwardPeriod" class="w-20 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                <span class="text-gray-500">0-6 Months</span>
              </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 items-center mb-6">
              <label for="backward_period" class="text-gray-700">Backward Period:</label>
              <div class="flex items-center space-x-2">
                <input type="number" id="backward_period" v-model="form.backwardPeriod" class="w-20 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                <span class="text-gray-500">0-1 Months</span>
              </div>
            </div>

            <!-- Validate Entry Date Section -->
            <fieldset class="border p-4 rounded-lg shadow-sm">
              <legend class="text-sm font-semibold text-gray-800 px-2">Validate Entry Date</legend>
              <div class="flex items-center justify-between mt-2">
                <label for="sales_order_entry_date" class="text-gray-700 w-1/3">Sales Order Entry Date:</label>
                <div class="w-2/3">
                  <select id="sales_order_entry_date" v-model="form.salesOrderEntryDate" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                    <option value="0-Open Date">0-Open Date</option>
                    <option value="U-Date Under Current Period">U-Date Under Current Period</option>
                    <option value="F-Date Under Current/Forward Current Period">F-Date Under Current/Forward Current Period</option>
                    <option value="B-Date Under Current/Backward Current Period">B-Date Under Current/Backward Current Period</option>
                  </select>
                </div>
              </div>
            </fieldset>
          </div>
          
          <!-- Buttons -->
          <div class="flex justify-end gap-3 mt-8 border-t pt-6">
            <button 
              type="button" 
              @click="resetForm"
              class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 transition-colors duration-200 flex items-center"
            >
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
              </svg>
              Reset
            </button>
            <button 
              type="submit" 
              class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors duration-200 flex items-center"
              :disabled="processing"
            >
              <svg v-if="!processing" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
              </svg>
              <svg v-else class="animate-spin h-4 w-4 mr-2 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
              {{ processing ? 'Saving...' : 'Save Changes' }}
            </button>
          </div>
        </form>
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
      processing: false,
      successMessage: '',
      errorMessage: '',
      form: {
        currentPeriodMonth: 6,
        currentPeriodYear: 2025,
        forwardPeriod: 1,
        backwardPeriod: 1,
        salesOrderEntryDate: '0-Open Date',
      },
    };
  },
  methods: {
    async saveConfiguration() {
      this.processing = true;
      this.successMessage = '';
      this.errorMessage = '';

      try {
        // Here you would send the data to your backend API
        // For demonstration, we'll just simulate a successful save
        await new Promise(resolve => setTimeout(resolve, 1000)); // Simulate API call
        this.successMessage = 'SO Period configuration saved successfully.';
      } catch (error) {
        this.errorMessage = 'An unexpected error occurred while saving.';
        console.error('Error saving configuration:', error);
      } finally {
        this.processing = false;
        setTimeout(() => {
          this.successMessage = '';
          this.errorMessage = '';
        }, 3000);
      }
    },
    resetForm() {
      this.form = {
        currentPeriodMonth: 6,
        currentPeriodYear: 2025,
        forwardPeriod: 1,
        backwardPeriod: 1,
        salesOrderEntryDate: '0-Open Date',
      };
    },
  },
};
</script>

<style scoped>
/* Add any specific styles for this component here */
</style> 