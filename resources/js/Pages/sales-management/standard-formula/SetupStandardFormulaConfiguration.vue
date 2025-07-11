<template>
  <Head title="Setup Standard Formula Configuration" />
  <AppLayout header="Setup Standard Formula Configuration">
    <div class="p-5">
      <div class="bg-white shadow-lg rounded-lg overflow-hidden">
        <!-- Header Section -->
        <div class="bg-gradient-to-r from-blue-600 to-blue-800 px-6 py-4">
          <div class="flex flex-col md:flex-row justify-between items-center">
            <div>
              <h2 class="text-xl font-bold text-white flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                Standard Formula Configuration
              </h2>
              <p class="text-blue-100 mt-1">Configure formula settings for business operations</p>
            </div>
            <button 
              @click="seedConfiguration" 
              class="mt-3 md:mt-0 px-4 py-2 bg-white text-blue-700 rounded-md hover:bg-blue-50 transition-colors duration-200 font-medium shadow-sm flex items-center text-sm"
              :disabled="seedProcessing"
            >
              <svg v-if="!seedProcessing" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
              </svg>
              <svg v-else class="animate-spin h-4 w-4 mr-2 text-blue-700" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
              {{ seedProcessing ? 'Seeding...' : 'Seed Default Data' }}
            </button>
          </div>
        </div>
        
        <form @submit.prevent="saveConfiguration" class="p-6">
          <!-- Notification Messages -->
          <transition name="fade">
            <div v-if="successMessage" class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-md shadow-sm">
              <div class="flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                {{ successMessage }}
              </div>
            </div>
          </transition>
          
          <transition name="fade">
            <div v-if="errorMessage" class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-md shadow-sm">
              <div class="flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                {{ errorMessage }}
              </div>
            </div>
          </transition>
          
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Left Column -->
            <div class="space-y-6">
              <!-- Activate Standard Formula -->
              <div class="bg-gray-50 p-5 rounded-lg shadow-sm border border-gray-100">
                <h3 class="font-semibold text-gray-800 mb-3 flex items-center">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                  </svg>
                  Activate Standard Formula
                </h3>
                <div class="flex items-center space-x-6">
                  <label class="inline-flex items-center cursor-pointer">
                    <input type="radio" id="yes" v-model="form.activateStandardFormula" value="yes" class="form-radio h-5 w-5 text-blue-600">
                    <span class="ml-2 text-gray-700">Yes</span>
                  </label>
                  <label class="inline-flex items-center cursor-pointer">
                    <input type="radio" id="no" v-model="form.activateStandardFormula" value="no" class="form-radio h-5 w-5 text-blue-600">
                    <span class="ml-2 text-gray-700">No</span>
                  </label>
                </div>
                <div v-if="formErrors.activateStandardFormula" class="text-red-500 text-sm mt-1">
                  {{ formErrors.activateStandardFormula }}
                </div>
              </div>
              
              <!-- Compute Corrugate Run Size Result -->
              <div class="bg-gray-50 p-5 rounded-lg shadow-sm border border-gray-100">
                <h3 class="font-semibold text-gray-800 mb-3 flex items-center">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                  </svg>
                  Compute Corrugate Run Size Result
                </h3>
                
                <div class="ml-2 space-y-4">
                  <!-- Economic Run Size -->
                  <div>
                    <h4 class="font-medium text-gray-700 mb-2">Economic Run Size:</h4>
                    <div class="flex flex-col space-y-2">
                      <label class="inline-flex items-center cursor-pointer">
                        <input type="radio" id="average" v-model="form.economicRunSize" value="average" class="form-radio h-5 w-5 text-blue-600">
                        <span class="ml-2 text-gray-700">By Average Time</span>
                      </label>
                      <label class="inline-flex items-center cursor-pointer">
                        <input type="radio" id="highest" v-model="form.economicRunSize" value="highest" class="form-radio h-5 w-5 text-blue-600">
                        <span class="ml-2 text-gray-700">By Highest Corrugate Out</span>
                      </label>
                    </div>
                    <div v-if="formErrors.economicRunSize" class="text-red-500 text-sm mt-1">
                      {{ formErrors.economicRunSize }}
                    </div>
                  </div>
                  
                  <!-- Checkbox for Run Size Result -->
                  <div>
                    <label class="inline-flex items-center cursor-pointer">
                      <input type="checkbox" id="check_run_size" v-model="form.checkRunSizeResult" class="form-checkbox h-5 w-5 text-blue-600 rounded">
                      <span class="ml-2 text-gray-700">Checking on Corrugate Run Size Result</span>
                    </label>
                    <div v-if="formErrors.checkRunSizeResult" class="text-red-500 text-sm mt-1">
                      {{ formErrors.checkRunSizeResult }}
                    </div>
                  </div>
                </div>
              </div>
            </div>
            
            <!-- Right Column -->
            <div class="space-y-6">
              <!-- Master Card -->
              <div class="bg-gray-50 p-5 rounded-lg shadow-sm border border-gray-100">
                <h3 class="font-semibold text-gray-800 mb-3 flex items-center">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                  </svg>
                  Master Card
                </h3>
                <div class="flex flex-col space-y-2">
                  <label class="inline-flex items-center cursor-pointer">
                    <input type="radio" id="mc_free" v-model="form.masterCard" value="free" class="form-radio h-5 w-5 text-blue-600">
                    <span class="ml-2 text-gray-700">Free to Choose</span>
                  </label>
                  <label class="inline-flex items-center cursor-pointer">
                    <input type="radio" id="mc_accept" v-model="form.masterCard" value="accept" class="form-radio h-5 w-5 text-blue-600">
                    <span class="ml-2 text-gray-700">Accept the best only</span>
                  </label>
                </div>
                <div v-if="formErrors.masterCard" class="text-red-500 text-sm mt-1">
                  {{ formErrors.masterCard }}
                </div>
              </div>
              
              <!-- SARoad Sales Order -->
              <div class="bg-gray-50 p-5 rounded-lg shadow-sm border border-gray-100">
                <h3 class="font-semibold text-gray-800 mb-3 flex items-center">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                  </svg>
                  S/Road Sales Order
                </h3>
                <div class="flex flex-col space-y-2">
                  <label class="inline-flex items-center cursor-pointer">
                    <input type="radio" id="so_free" v-model="form.salesOrder" value="free" class="form-radio h-5 w-5 text-blue-600">
                    <span class="ml-2 text-gray-700">Free to Choose</span>
                  </label>
                  <label class="inline-flex items-center cursor-pointer">
                    <input type="radio" id="so_accept" v-model="form.salesOrder" value="accept" class="form-radio h-5 w-5 text-blue-600">
                    <span class="ml-2 text-gray-700">Accept the best only</span>
                  </label>
                </div>
                <div v-if="formErrors.salesOrder" class="text-red-500 text-sm mt-1">
                  {{ formErrors.salesOrder }}
                </div>
              </div>
              
              <!-- Work Order -->
              <div class="bg-gray-50 p-5 rounded-lg shadow-sm border border-gray-100">
                <h3 class="font-semibold text-gray-800 mb-3 flex items-center">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                  </svg>
                  Work Order
                </h3>
                <div class="flex flex-col space-y-2">
                  <label class="inline-flex items-center cursor-pointer">
                    <input type="radio" id="wo_free" v-model="form.workOrder" value="free" class="form-radio h-5 w-5 text-blue-600">
                    <span class="ml-2 text-gray-700">Free to Choose</span>
                  </label>
                  <label class="inline-flex items-center cursor-pointer">
                    <input type="radio" id="wo_accept" v-model="form.workOrder" value="accept" class="form-radio h-5 w-5 text-blue-600">
                    <span class="ml-2 text-gray-700">Accept the best only</span>
                  </label>
                </div>
                <div v-if="formErrors.workOrder" class="text-red-500 text-sm mt-1">
                  {{ formErrors.workOrder }}
                </div>
              </div>
            </div>
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
    AppLayout
  },
  props: {
    configuration: Object,
  },
  data() {
    return {
      processing: false,
      seedProcessing: false,
      successMessage: '',
      errorMessage: '',
      form: {
        activateStandardFormula: this.configuration?.activateStandardFormula || 'yes',
        economicRunSize: this.configuration?.economicRunSize || 'average',
        checkRunSizeResult: this.configuration?.checkRunSizeResult || true,
        masterCard: this.configuration?.masterCard || 'free',
        salesOrder: this.configuration?.salesOrder || 'free',
        workOrder: this.configuration?.workOrder || 'free'
      },
      formErrors: {}
    };
  },
  mounted() {
    this.loadConfiguration();
  },
  methods: {
    loadConfiguration() {
      axios.get('/api/standard-formula')
        .then(response => {
          this.form = response.data;
        })
        .catch(error => {
          console.error('Error loading configuration:', error);
          this.errorMessage = 'Failed to load configuration. Please try again.';
        });
    },
    saveConfiguration() {
      this.processing = true;
      this.formErrors = {};
      this.successMessage = '';
      this.errorMessage = '';
      
      axios.post('/api/standard-formula', this.form)
        .then(response => {
          this.processing = false;
          this.successMessage = response.data.message;
          
          // Auto-hide success message after 5 seconds
          setTimeout(() => {
            this.successMessage = '';
          }, 5000);
        })
        .catch(error => {
          this.processing = false;
          if (error.response && error.response.data && error.response.data.errors) {
            this.formErrors = error.response.data.errors;
          } else {
            console.error('Error saving configuration:', error);
            this.errorMessage = 'Failed to save configuration. Please try again.';
          }
        });
    },
    resetForm() {
      this.loadConfiguration();
      this.formErrors = {};
      this.successMessage = '';
      this.errorMessage = '';
    },
    seedConfiguration() {
      if (confirm('This will reset the configuration to default values. Are you sure?')) {
        this.seedProcessing = true;
        this.successMessage = '';
        this.errorMessage = '';
        
        axios.post('/api/standard-formula/seed')
          .then(response => {
            this.seedProcessing = false;
            this.successMessage = response.data.message;
            this.loadConfiguration(); // Reload configuration after seeding
            
            // Auto-hide success message after 5 seconds
            setTimeout(() => {
              this.successMessage = '';
            }, 5000);
          })
          .catch(error => {
            this.seedProcessing = false;
            console.error('Error seeding configuration:', error);
            this.errorMessage = 'Failed to seed configuration. Please try again.';
          });
      }
    }
  }
};
</script>

<style scoped>
/* Transitions for alerts */
.fade-enter-active, .fade-leave-active {
  transition: opacity 0.5s ease, transform 0.5s ease;
}
.fade-enter-from, .fade-leave-to {
  opacity: 0;
  transform: translateY(-10px);
}

/* Custom form styles */
.form-radio, .form-checkbox {
  @apply cursor-pointer focus:ring-2 focus:ring-offset-2 focus:ring-blue-500;
}
</style>
