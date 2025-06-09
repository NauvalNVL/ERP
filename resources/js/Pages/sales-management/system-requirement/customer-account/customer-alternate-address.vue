<template>
  <AppLayout :header="'Customer Alternate Address'">
    <div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 p-6">
      <div class="max-w-7xl mx-auto">
        <!-- Error Notification -->
        <div v-if="errorMessage" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6 flex items-center justify-between">
          <div class="flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
            </svg>
            <span>{{ errorMessage }}</span>
          </div>
          <button @click="errorMessage = ''" class="text-red-700">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
            </svg>
          </button>
        </div>
        
        <!-- Success Notification -->
        <div v-if="successMessage" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6 flex items-center justify-between">
          <div class="flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
            </svg>
            <span>{{ successMessage }}</span>
          </div>
          <button @click="successMessage = ''" class="text-green-700">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
            </svg>
          </button>
        </div>
        
        <!-- Header -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
          <div class="flex items-center justify-between mb-4">
            <div class="flex items-center space-x-3">
              <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center">
                <!-- Map Pin Icon -->
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5 text-white"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
              </div>
              <div>
                <h1 class="text-2xl font-bold text-gray-800">Customer Alternate Address</h1>
                <p class="text-gray-600">Manage customer alternate addresses</p>
              </div>
            </div>
            <div class="flex space-x-2">
              <button 
                @click="openAddModal"
                class="flex items-center space-x-2 px-4 py-2 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-lg hover:from-green-600 hover:to-green-700 transition-all duration-200 shadow-md hover:shadow-lg"
              >
                <!-- Plus Icon -->
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                <span>Add Address</span>
              </button>
              <button 
                @click="showOptions = !showOptions"
                class="flex items-center space-x-2 px-4 py-2 bg-gradient-to-r from-blue-500 to-indigo-600 text-white rounded-lg hover:from-blue-600 hover:to-indigo-700 transition-all duration-200 shadow-md hover:shadow-lg"
              >
                <!-- Filter Icon -->
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon></svg>
                <span>Options</span>
              </button>
            </div>
          </div>

          <!-- Search Bar -->
          <div class="relative">
            <!-- Search Icon -->
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 w-4 h-4"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
            <input
              type="text"
              placeholder="Search customers or addresses..."
              v-model="searchTerm"
              class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
            />
          </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
          <!-- Customer List -->
          <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
              <div class="p-6 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-800">Customers</h2>
                <p class="text-sm text-gray-600 mt-1">Select a customer to view alternate addresses</p>
              </div>
              
              <div v-if="loadingCustomers" class="p-8 flex justify-center">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-500"></div>
              </div>
              
              <div v-else class="overflow-x-auto max-h-96 overflow-y-auto">
                <table class="w-full">
                  <thead class="bg-blue-600 text-white sticky top-0">
                    <tr>
                      <th @click="sortCustomers('customer_name')" class="px-4 py-2 text-left text-xs font-semibold cursor-pointer hover:bg-blue-700">
                        Customer Name
                        <i class="fas fa-sort ml-1" :class="{
                          'fa-sort-up': sortBy === 'customer_name' && sortAsc,
                          'fa-sort-down': sortBy === 'customer_name' && !sortAsc
                        }"></i>
                      </th>
                      <th @click="sortCustomers('customer_code')" class="px-4 py-2 text-left text-xs font-semibold cursor-pointer hover:bg-blue-700">
                        Customer Code
                        <i class="fas fa-sort ml-1" :class="{
                          'fa-sort-up': sortBy === 'customer_code' && sortAsc,
                          'fa-sort-down': sortBy === 'customer_code' && !sortAsc
                        }"></i>
                      </th>
                      <th @click="sortCustomers('status')" class="px-4 py-2 text-left text-xs font-semibold cursor-pointer hover:bg-blue-700">
                        Status
                        <i class="fas fa-sort ml-1" :class="{
                          'fa-sort-up': sortBy === 'status' && sortAsc,
                          'fa-sort-down': sortBy === 'status' && !sortAsc
                        }"></i>
                      </th>
                    </tr>
                  </thead>
                  <tbody class="divide-y divide-gray-200">
                    <tr v-if="filteredCustomers.length === 0" class="text-center">
                      <td colspan="3" class="px-4 py-4 text-gray-500 text-sm">No customers found</td>
                    </tr>
                    <tr 
                      v-for="customer in filteredCustomers"
                      :key="customer.id"
                      class="hover:bg-blue-50 transition-colors duration-150 cursor-pointer"
                      :class="{ 'bg-blue-50 border-l-4 border-blue-500': selectedCustomer && selectedCustomer.customer_code === customer.customer_code }"
                      @click="selectCustomer(customer)"
                    >
                      <td class="px-4 py-2 text-xs font-medium text-gray-900">{{ customer.customer_name }}</td>
                      <td class="px-4 py-2 text-xs text-gray-700">{{ customer.customer_code }}</td>
                      <td class="px-4 py-2">
                        <span 
                          class="inline-flex items-center px-2 py-0.5 text-xs font-medium rounded-full"
                          :class="{
                            'bg-green-100 text-green-800': customer.status === 'Active', 
                            'bg-red-100 text-red-800': customer.status === 'Obsolete'
                          }">
                          {{ customer.status }}
                        </span>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>

          <!-- Alternate Addresses -->
          <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
              <div class="p-6 border-b border-gray-200 flex justify-between items-center">
                <div>
                  <h2 class="text-lg font-semibold text-gray-800">Alternate Addresses</h2>
                  <p class="text-sm text-gray-600 mt-1" v-if="selectedCustomer">
                    Showing addresses for {{ selectedCustomer.customer_name }}
                  </p>
                  <p class="text-sm text-gray-600 mt-1" v-else>
                    Select a customer to view addresses
                  </p>
                </div>
                <button 
                  v-if="selectedCustomer"
                  @click="openAddModal"
                  class="px-3 py-1 bg-green-500 text-white rounded hover:bg-green-600 text-xs flex items-center"
                >
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-3 h-3 mr-1"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                  Add
                </button>
              </div>
              
              <div v-if="loadingAddresses" class="p-8 flex justify-center">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-500"></div>
              </div>
              
              <div v-else-if="!selectedCustomer" class="p-8 text-center text-gray-500">
                Please select a customer to view their alternate addresses
              </div>
              
              <div v-else-if="addresses.length === 0" class="p-8 text-center text-gray-500">
                No alternate addresses found for this customer
              </div>
              
              <div v-else class="overflow-x-auto max-h-96 overflow-y-auto">
                <table class="w-full">
                  <thead class="bg-blue-600 text-white sticky top-0">
                    <tr>
                      <th class="px-4 py-2 text-left text-xs font-semibold">Address</th>
                      <th class="px-4 py-2 text-left text-xs font-semibold">City</th>
                      <th class="px-4 py-2 text-left text-xs font-semibold">Actions</th>
                    </tr>
                  </thead>
                  <tbody class="divide-y divide-gray-200">
                    <tr 
                      v-for="address in addresses"
                      :key="address.id"
                      class="hover:bg-blue-50 transition-colors duration-150"
                    >
                      <td class="px-4 py-2 text-xs font-medium text-gray-900">{{ address.address }}</td>
                      <td class="px-4 py-2 text-xs text-gray-700">{{ address.city }}</td>
                      <td class="px-4 py-2">
                        <div class="flex space-x-2">
                          <button class="p-1 text-blue-600 hover:bg-blue-50 rounded transition-colors duration-150" @click="openEditModal(address)">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-3 h-3"><path d="M12 20h9"></path><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path></svg>
                          </button>
                          <button class="p-1 text-red-600 hover:bg-red-50 rounded transition-colors duration-150" @click="confirmDelete(address)">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-3 h-3"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
                          </button>
                        </div>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>

          <!-- Options Panel -->
          <div v-if="showOptions" class="lg:col-span-4 mt-4">
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
              <div class="p-6 border-b border-gray-200 flex justify-between items-center">
                <div>
                  <h2 class="text-lg font-semibold text-gray-800">Options</h2>
                  <p class="text-sm text-gray-600 mt-1">Sorting and filtering options</p>
                </div>
                <button 
                  @click="showOptions = false"
                  class="text-gray-400 hover:text-gray-600"
                >
                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                </button>
              </div>
              
              <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Sort By Section -->
                <div>
                  <h3 class="text-sm font-medium text-gray-700 mb-3">Sort by:</h3>
                  <div class="space-y-3">
                    <label class="flex items-center">
                      <input
                        type="radio"
                        name="sortBy"
                        value="customer_code"
                        v-model="sortBy"
                        class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500"
                      />
                      <span class="ml-3 text-sm text-gray-700">Customer Code</span>
                    </label>
                    <label class="flex items-center">
                      <input
                        type="radio"
                        name="sortBy"
                        value="customer_name"
                        v-model="sortBy"
                        class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500"
                      />
                      <span class="ml-3 text-sm text-gray-700">Customer Name</span>
                    </label>
                    <label class="flex items-center">
                      <input
                        type="radio"
                        name="sortBy"
                        value="status"
                        v-model="sortBy"
                        class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500"
                      />
                      <span class="ml-3 text-sm text-gray-700">Status</span>
                    </label>
                    
                    <div class="mt-4">
                      <label class="flex items-center">
                        <input
                          type="checkbox"
                          v-model="sortAsc"
                          class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500"
                        />
                        <span class="ml-3 text-sm text-gray-700">Ascending Order</span>
                      </label>
                    </div>
                  </div>
                </div>
                
                <!-- Record Status Section -->
                <div>
                  <h3 class="text-sm font-medium text-gray-700 mb-3">Record Status:</h3>
                  <div class="space-y-3">
                    <label class="flex items-center">
                      <input
                        type="radio"
                        name="recordStatus"
                        value="All"
                        v-model="recordStatus"
                        class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500"
                      />
                      <span class="ml-3 text-sm text-gray-700">All</span>
                    </label>
                    <label class="flex items-center">
                      <input
                        type="radio"
                        name="recordStatus"
                        value="Active"
                        v-model="recordStatus"
                        class="w-4 h-4 text-green-600 border-gray-300 focus:ring-green-500"
                      />
                      <span class="ml-3 text-sm text-gray-700">Active</span>
                    </label>
                    <label class="flex items-center">
                      <input
                        type="radio"
                        name="recordStatus"
                        value="Obsolete"
                        v-model="recordStatus"
                        class="w-4 h-4 text-red-600 border-gray-300 focus:ring-red-500"
                      />
                      <span class="ml-3 text-sm text-gray-700">Obsolete</span>
                    </label>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Address Form Modal -->
    <div v-if="showModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
      <div class="bg-white rounded-lg shadow-lg w-full max-w-lg p-6">
        <div class="flex justify-between items-center mb-4">
          <h3 class="text-lg font-semibold text-gray-800">
            {{ isEditing ? 'Edit Address' : 'Add New Address' }}
          </h3>
          <button @click="closeModal" class="text-gray-400 hover:text-gray-600">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
          </button>
        </div>
        
        <form @submit.prevent="saveAddress">
          <div class="space-y-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Address</label>
              <input 
                type="text" 
                v-model="addressForm.address" 
                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                required
              />
            </div>
            
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">City</label>
              <input 
                type="text" 
                v-model="addressForm.city" 
                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                required
              />
            </div>
            
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Postal Code</label>
              <input 
                type="text" 
                v-model="addressForm.postal_code" 
                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                required
              />
            </div>
            
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
              <input 
                type="text" 
                v-model="addressForm.phone" 
                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                required
              />
            </div>
            
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
              <input 
                type="email" 
                v-model="addressForm.email" 
                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                required
              />
            </div>
          </div>
          
          <div class="mt-6 flex justify-end space-x-3">
            <button 
              type="button"
              @click="closeModal"
              class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300"
            >
              Cancel
            </button>
            
            <button 
              type="submit"
              class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700"
              :disabled="saving"
            >
              <span v-if="saving">Saving...</span>
              <span v-else>Save</span>
            </button>
          </div>
        </form>
      </div>
    </div>
    
    <!-- Delete Confirmation Modal -->
    <div v-if="showDeleteModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
      <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6">
        <div class="mb-4">
          <h3 class="text-lg font-semibold text-gray-800 mb-2">Confirm Deletion</h3>
          <p class="text-sm text-gray-600">Are you sure you want to delete this address? This action cannot be undone.</p>
        </div>
        
        <div class="mt-6 flex justify-end space-x-3">
          <button 
            @click="showDeleteModal = false"
            class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300"
          >
            Cancel
          </button>
          
          <button 
            @click="deleteAddress"
            class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700"
            :disabled="deleting"
          >
            <span v-if="deleting">Deleting...</span>
            <span v-else>Delete</span>
          </button>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { Head, usePage } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import axios from 'axios';

// Access props from Inertia
const props = defineProps({
  initialData: Object
});

// Access page data
const page = usePage();

// Define reactive state variables
const customers = ref([]);
const addresses = ref([]);
const selectedCustomer = ref(null);
const searchTerm = ref('');
const loadingCustomers = ref(true);
const loadingAddresses = ref(false);
const errorMessage = ref('');
const successMessage = ref('');

// Modal state
const showModal = ref(false);
const isEditing = ref(false);
const addressForm = ref({
  address: '',
  city: '',
  postal_code: '',
  phone: '',
  email: '',
});
const editingAddressId = ref(null);
const saving = ref(false);

// Delete confirmation modal
const showDeleteModal = ref(false);
const addressToDelete = ref(null);
const deleting = ref(false);

// Options state
const showOptions = ref(false);
const sortBy = ref('customer_name');
const sortAsc = ref(true);
const recordStatus = ref('All');

// Fetch customer data
onMounted(async () => {
  try {
    loadingCustomers.value = true;
    const response = await axios.get('/api/customer-alternate-addresses');
    customers.value = response.data;
    
    // If we received initial data from server-side props, use it
    if (props.initialData?.customers?.length > 0) {
      customers.value = props.initialData.customers;
    }
    
  } catch (error) {
    console.error('Error fetching customers:', error);
    errorMessage.value = 'Failed to load customers: ' + (error.response?.data?.message || error.message);
  } finally {
    loadingCustomers.value = false;
  }
});

// Computed property to filter customers based on search
const filteredCustomers = computed(() => {
  if (!customers.value.length) return [];
  
  // Filter by search term and status
  let filtered = customers.value.filter(customer => {
    const matchesSearch = !searchTerm.value || 
      (customer.customer_name && customer.customer_name.toLowerCase().includes(searchTerm.value.toLowerCase())) ||
      (customer.customer_code && customer.customer_code.toLowerCase().includes(searchTerm.value.toLowerCase()));
      
    const matchesStatus = recordStatus.value === 'All' ? true : 
                          customer.status === recordStatus.value;
                          
    return matchesSearch && matchesStatus;
  });
  
  // Apply sorting
  filtered.sort((a, b) => {
    let valueA = a[sortBy.value] || '';
    let valueB = b[sortBy.value] || '';
    
    if (typeof valueA === 'string') valueA = valueA.toLowerCase();
    if (typeof valueB === 'string') valueB = valueB.toLowerCase();
    
    if (valueA < valueB) return sortAsc.value ? -1 : 1;
    if (valueA > valueB) return sortAsc.value ? 1 : -1;
    return 0;
  });
  
  return filtered;
});

// Function to select a customer and fetch their addresses
const selectCustomer = async (customer) => {
  selectedCustomer.value = customer;
  errorMessage.value = ''; // Clear any error messages
  successMessage.value = ''; // Clear any success messages
  fetchAddresses(customer.customer_code);
};

// Function to sort customers when clicking on table headers
const sortCustomers = (key) => {
  if (sortBy.value === key) {
    sortAsc.value = !sortAsc.value;
  } else {
    sortBy.value = key;
    sortAsc.value = true;
  }
};

// Function to fetch addresses for a customer
const fetchAddresses = async (customerCode) => {
  try {
    loadingAddresses.value = true;
    addresses.value = [];
    const response = await axios.get(`/api/customer-alternate-addresses/${customerCode}`);
    addresses.value = response.data;
  } catch (error) {
    console.error('Error fetching addresses:', error);
    errorMessage.value = 'Failed to load addresses: ' + (error.response?.data?.message || error.message);
  } finally {
    loadingAddresses.value = false;
  }
};

// Modal functions
const openAddModal = () => {
  if (!selectedCustomer.value) {
    // Show warning that a customer must be selected
    errorMessage.value = 'Please select a customer first before adding an address';
    return;
  }
  
  isEditing.value = false;
  addressForm.value = {
    address: '',
    city: '',
    postal_code: '',
    phone: '',
    email: '',
  };
  showModal.value = true;
};

const openEditModal = (address) => {
  isEditing.value = true;
  editingAddressId.value = address.id;
  addressForm.value = {
    address: address.address,
    city: address.city,
    postal_code: address.postal_code,
    phone: address.phone,
    email: address.email,
  };
  showModal.value = true;
};

const closeModal = () => {
  showModal.value = false;
  addressForm.value = {
    address: '',
    city: '',
    postal_code: '',
    phone: '',
    email: '',
  };
};

// Save address function
const saveAddress = async () => {
  if (!selectedCustomer.value) return;
  
  saving.value = true;
  try {
    if (isEditing.value) {
      // Update existing address
      await axios.put(`/api/customer-alternate-addresses/${editingAddressId.value}`, addressForm.value);
    } else {
      // Create new address
      await axios.post('/api/customer-alternate-addresses', {
        ...addressForm.value,
        customer_code: selectedCustomer.value.customer_code,
      });
    }
    
    // Refresh addresses
    await fetchAddresses(selectedCustomer.value.customer_code);
    closeModal();
    successMessage.value = isEditing.value ? 'Address updated successfully' : 'New address added successfully';
  } catch (error) {
    console.error('Error saving address:', error);
    errorMessage.value = 'Failed to save address: ' + (error.response?.data?.message || error.message);
  } finally {
    saving.value = false;
  }
};

// Delete address functions
const confirmDelete = (address) => {
  addressToDelete.value = address;
  showDeleteModal.value = true;
};

const deleteAddress = async () => {
  if (!addressToDelete.value) return;
  
  deleting.value = true;
  try {
    await axios.delete(`/api/customer-alternate-addresses/${addressToDelete.value.id}`);
    
    // Refresh addresses
    await fetchAddresses(selectedCustomer.value.customer_code);
    showDeleteModal.value = false;
    addressToDelete.value = null;
    successMessage.value = 'Address deleted successfully';
  } catch (error) {
    console.error('Error deleting address:', error);
    errorMessage.value = 'Failed to delete address: ' + (error.response?.data?.message || error.message);
  } finally {
    deleting.value = false;
  }
};
</script>

<style scoped>
/* Add any component-specific styles here */
</style>