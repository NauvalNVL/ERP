<template>
  <AppLayout :header="'Customer Alternate Address'">
    <div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 p-6">
      <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
          <div class="flex items-center justify-between mb-4">
            <div class="flex items-center space-x-3">
              <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center">
                <!-- Plus Icon -->
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5 text-white"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
              </div>
              <div>
                <h1 class="text-2xl font-bold text-gray-800">Customer Account Table</h1>
                <p class="text-gray-600">View and manage customer accounts</p>
              </div>
            </div>
            <button 
              @click="showOptions = !showOptions"
              class="flex items-center space-x-2 px-4 py-2 bg-gradient-to-r from-blue-500 to-indigo-600 text-white rounded-lg hover:from-blue-600 hover:to-indigo-700 transition-all duration-200 shadow-md hover:shadow-lg"
            >
              <!-- Filter Icon -->
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon></svg>
              <span>Options</span>
            </button>
          </div>

          <!-- Search Bar -->
          <div class="relative">
            <!-- Search Icon -->
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 w-4 h-4"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
            <input
              type="text"
              placeholder="Search customers..."
              v-model="searchTerm"
              class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
            />
          </div>
          
          <!-- Data Status Information -->
          <div v-if="loading" class="mt-4 bg-yellow-100 p-3 rounded">
            <div class="flex items-center">
              <div class="mr-3">
                <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-yellow-700"></div>
              </div>
              <p class="text-sm font-medium text-yellow-800">Loading customer alternate address data...</p>
            </div>
          </div>
          <div v-else-if="customers.length === 0" class="mt-4 bg-yellow-100 p-3 rounded">
            <p class="text-sm font-medium text-yellow-800">No customer alternate address data available.</p>
            <p class="text-xs text-yellow-700 mt-1">Make sure the database is properly configured and seeders have been run.</p>
            <div class="mt-2 flex items-center space-x-3">
              <button @click="loadSeedData" class="bg-blue-500 hover:bg-blue-600 text-white text-xs px-3 py-1 rounded">Run Customer Alternate Address Seeder</button>
            </div>
          </div>
          <div v-else class="mt-4 bg-green-100 p-3 rounded">
            <p class="text-sm font-medium text-green-800">Data available: {{ customers.length }} addresses found.</p>
            <p v-if="selectedCustomer" class="text-xs text-green-700 mt-1">
              Selected: <span class="font-semibold">{{ selectedCustomer.customer_code }}</span> - {{ selectedCustomer.customer_name }}
            </p>
          </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
          <!-- Customer List -->
          <div class="lg:col-span-3">
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
              <div class="p-6 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-800">Customer Account Table</h2>
                <p class="text-sm text-gray-600 mt-1">Showing all customer accounts in the system</p>
              </div>
              
              <div class="overflow-x-auto">
                <table class="w-full">
                  <thead class="bg-blue-600 text-white">
                    <tr>
                      <th class="px-4 py-2 text-left text-xs font-semibold">Customer Name</th>
                      <th class="px-4 py-2 text-left text-xs font-semibold">Customer Code</th>
                      <th class="px-4 py-2 text-left text-xs font-semibold">Salesperson Code</th>
                      <th class="px-4 py-2 text-left text-xs font-semibold">Currency</th>
                      <th class="px-4 py-2 text-left text-xs font-semibold">Status</th>
                      <th class="px-4 py-2 text-left text-xs font-semibold">Actions</th>
                    </tr>
                  </thead>
                  <tbody class="divide-y divide-gray-200">
                    <tr 
                      v-for="customer in filteredCustomers"
                      :key="customer.id"
                      class="hover:bg-blue-50 transition-colors duration-150 cursor-pointer"
                      :class="{ 'bg-blue-50 border-l-4 border-blue-500': selectedCustomer?.id === customer.id }"
                      @click="selectedCustomer = customer"
                    >
                      <td class="px-4 py-2 text-xs font-medium text-gray-900">{{ customer.customer_name }}</td>
                      <td class="px-4 py-2 text-xs text-gray-700">{{ customer.customer_code }}</td>
                      <td class="px-4 py-2 text-xs text-gray-700">{{ customer.salesperson_type }}</td>
                      <td class="px-4 py-2 text-xs text-gray-700">{{ customer.currency }}</td>
                      <td class="px-4 py-2">
                        <span 
                          class="inline-flex items-center px-2 py-0.5 text-xs font-medium"
                          :class="{
                            'text-green-800': customer.status === 'Active', 
                            'text-red-800': customer.status === 'Obsolete'
                          }">
                          {{ customer.status }}
                        </span>
                      </td>
                      <td class="px-4 py-2">
                        <div class="flex space-x-2">
                          <button class="p-1 text-blue-600 hover:bg-blue-50 rounded transition-colors duration-150" @click.stop="handleEdit(customer)">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-3 h-3"><path d="M12 20h9"></path><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path></svg>
                          </button>
                          <button class="p-1 text-red-600 hover:bg-red-50 rounded transition-colors duration-150" @click.stop="handleDelete(customer)">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-3 h-3"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
                          </button>
                        </div>
                      </td>
                    </tr>
                    <tr v-if="loading">
                      <td colspan="6" class="px-6 py-4 text-center">
                        <div class="flex items-center justify-center">
                          <div class="animate-spin rounded-full h-5 w-5 border-b-2 border-blue-500"></div>
                          <span class="ml-2 text-gray-500">Loading data...</span>
                        </div>
                      </td>
                    </tr>
                    <tr v-else-if="filteredCustomers.length === 0">
                      <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                        No customer alternate address data found matching your criteria.
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>

          <!-- Options Panel -->
          <div class="lg:col-span-1">
            <div 
              :class="[
                'bg-white rounded-xl shadow-lg transition-all duration-300',
                showOptions ? 'opacity-100 transform translate-y-0' : 'opacity-50 transform translate-y-2 pointer-events-none'
              ]"
            >
              <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                  <h3 class="text-lg font-semibold text-gray-800">Options</h3>
                  <!-- X Icon -->
                  <svg 
                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" 
                    class="w-5 h-5 text-gray-400 cursor-pointer hover:text-gray-600" 
                    @click="showOptions = false">
                    <line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line>
                  </svg>
                </div>

                <!-- Sort By Section -->
                <div class="mb-6">
                  <label class="block text-sm font-medium text-gray-700 mb-3">Sort by:</label>
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
                  </div>
                </div>

                <!-- Record Status Section -->
                <div class="mb-6">
                  <label class="block text-sm font-medium text-gray-700 mb-3">Record Status:</label>
                  <div class="space-y-3">
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
                    <label class="flex items-center">
                      <input
                        type="radio"
                        name="recordStatus"
                        value="both"
                        v-model="recordStatus"
                        class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500"
                      />
                      <span class="ml-3 text-sm text-gray-700">Both</span>
                    </label>
                  </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col space-y-3">
                  <button class="w-full px-4 py-2 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-lg hover:from-green-600 hover:to-green-700 transition-all duration-200 shadow-md hover:shadow-lg flex items-center justify-center space-x-2" @click="handleOK">
                    <!-- Save Icon -->
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><polyline points="17 21 17 13 7 13 7 21"></polyline><polyline points="7 3 7 8 15 8"></polyline></svg>
                    <span>OK</span>
                  </button>
                  <button class="w-full px-4 py-2 bg-gradient-to-r from-gray-500 to-gray-600 text-white rounded-lg hover:from-gray-600 hover:to-gray-700 transition-all duration-200 shadow-md hover:shadow-lg flex items-center justify-center space-x-2" @click="handleExit">
                    <!-- X Icon -->
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                    <span>Exit</span>
                  </button>
                </div>
              </div>
            </div>

            <!-- Selected Customer Detail -->
            <div v-if="selectedCustomer" class="mt-6 bg-white rounded-xl shadow-lg p-6">
              <h3 class="text-lg font-semibold text-gray-800 mb-4">Selected Customer</h3>
              <div class="space-y-2">
                <p class="text-sm"><span class="font-medium text-gray-600">Name:</span> {{ selectedCustomer.customer_name }}</p>
                <p class="text-sm"><span class="font-medium text-gray-600">Code:</span> {{ selectedCustomer.customer_code }}</p>
                <p class="text-sm"><span class="font-medium text-gray-600">Salesperson Code:</span> {{ selectedCustomer.salesperson_type }}</p>
                <p class="text-sm"><span class="font-medium text-gray-600">Currency:</span> {{ selectedCustomer.currency }} ({{ selectedCustomer.currency_code }})</p>
                <p class="text-sm"><span class="font-medium text-gray-600">Address:</span> {{ selectedCustomer.address }}</p>
                <p class="text-sm"><span class="font-medium text-gray-600">City:</span> {{ selectedCustomer.city }}</p>
                <p class="text-sm"><span class="font-medium text-gray-600">Postal Code:</span> {{ selectedCustomer.postal_code }}</p>
                <p class="text-sm"><span class="font-medium text-gray-600">Phone:</span> {{ selectedCustomer.phone }}</p>
                <p class="text-sm"><span class="font-medium text-gray-600">Email:</span> {{ selectedCustomer.email }}</p>
                <p class="text-sm">
                  <span class="font-medium text-gray-600">Status:</span> 
                  <span 
                    class="ml-2 px-2 py-1 rounded text-xs"
                    :class="{
                      'bg-green-100 text-green-800': selectedCustomer.status === 'Active', 
                      'bg-red-100 text-red-800': selectedCustomer.status === 'Obsolete'
                    }">
                    {{ selectedCustomer.status }}
                  </span>
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Notification Toast -->
    <div v-if="notification.show" 
         :class="[
           'notification',
           notification.type === 'success' ? 'notification-success' : 
           notification.type === 'error' ? 'notification-error' : 'notification-warning'
         ]">
      <div class="flex items-center">
        <div class="mr-3">
          <i v-if="notification.type === 'success'" class="fas fa-check-circle"></i>
          <i v-else-if="notification.type === 'error'" class="fas fa-exclamation-circle"></i>
          <i v-else class="fas fa-exclamation-triangle"></i>
        </div>
        <span>{{ notification.message }}</span>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue';
import { Head, Link, usePage, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import axios from 'axios';

// Define reactive state variables
const sortBy = ref('customer_name');
const recordStatus = ref('Active');
const showOptions = ref(false);
const customers = ref([]);
const selectedCustomer = ref(null);
const searchTerm = ref('');
const loading = ref(true);

// Form for editing/creating
const editForm = ref({
  id: '',
  customer_code: '',
  customer_name: '',
  salesperson_type: '',
  currency: '',
  currency_code: '',
  status: 'Active',
  address: '',
  city: '',
  postal_code: '',
  phone: '',
  email: ''
});

// Notification system
const notification = ref({ 
  show: false, 
  message: '', 
  type: 'success' 
});

// API call to load data
const fetchCustomers = async () => {
  try {
    loading.value = true;
    const response = await axios.get('/api/customer-alternate-addresses');
    
    console.log('Fetched customer alternate addresses:', response.data);
    
    if (Array.isArray(response.data)) {
      customers.value = response.data;
    } else {
      console.error('Unexpected data format:', response.data);
      customers.value = [];
    }
  } catch (error) {
    console.error('Error fetching customers:', error);
    showNotification('Failed to load customer data', 'error');
  } finally {
    loading.value = false;
  }
};

// Initialize on component mount
onMounted(() => {
  fetchCustomers();
});

// Watch for changes in search term to filter data
watch(searchTerm, (newTerm) => {
  if (newTerm && customers.value.length > 0) {
    const foundCustomer = customers.value.find(customer => 
      (customer.customer_name && customer.customer_name.toLowerCase().includes(newTerm.toLowerCase())) ||
      (customer.customer_code && customer.customer_code.toLowerCase().includes(newTerm.toLowerCase()))
    );
    
    if (foundCustomer) {
      selectedCustomer.value = foundCustomer;
    }
  }
});

// Computed property to filter and sort customers based on state
const filteredCustomers = computed(() => {
  let filtered = customers.value.filter(customer => {
    const matchesSearch = (customer.customer_name && customer.customer_name.toLowerCase().includes(searchTerm.value.toLowerCase())) ||
                       (customer.customer_code && customer.customer_code.toLowerCase().includes(searchTerm.value.toLowerCase())) ||
                       (customer.salesperson_type && customer.salesperson_type.toLowerCase().includes(searchTerm.value.toLowerCase()));
    const matchesStatus = recordStatus.value === 'both' ? true : 
                       customer.status === recordStatus.value;
    return matchesSearch && matchesStatus;
  });

  // Sorting logic by name or code
  filtered.sort((a, b) => {
    if (sortBy.value === 'customer_name') {
      return (a.customer_name || '').localeCompare(b.customer_name || '');
    } else if (sortBy.value === 'customer_code') {
      return (a.customer_code || '').localeCompare(b.customer_code || '');
    }
    return 0;
  });

  return filtered;
});

// Method to show notifications
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

// Run data seed if needed
const loadSeedData = async () => {
  try {
    loading.value = true;
    const response = await axios.post('/api/seed-customer-alternate-addresses');
    
    if (response.data.success) {
      showNotification('Customer alternate address data seeded successfully', 'success');
      await fetchCustomers();
    } else {
      showNotification('Error seeding data: ' + (response.data.message || 'Unknown error'), 'error');
    }
  } catch (error) {
    console.error('Error seeding data:', error);
    showNotification('Error seeding data: ' + error.message, 'error');
  } finally {
    loading.value = false;
  }
};

// --- Action Methods ---

// Method to handle editing a customer
const handleEdit = (customer) => {
  console.log('Edit customer:', customer);
  // Initialize edit form with customer data
  editForm.value = { ...customer };
  // Here you would open a modal or navigate to edit page
};

// Method to handle deleting a customer
const handleDelete = async (customer) => {
  if (!confirm(`Are you sure you want to delete this alternate address for ${customer.customer_name}?`)) {
    return;
  }
  
  try {
    loading.value = true;
    const response = await axios.delete(`/api/customer-alternate-addresses/${customer.id}`);
    
    if (response.data.success) {
      showNotification('Address deleted successfully', 'success');
      
      // Remove from local array
      customers.value = customers.value.filter(c => c.id !== customer.id);
      
      if (selectedCustomer.value && selectedCustomer.value.id === customer.id) {
        selectedCustomer.value = null;
      }
    } else {
      showNotification('Error deleting address: ' + (response.data.message || 'Unknown error'), 'error');
    }
  } catch (error) {
    console.error('Error deleting address:', error);
    showNotification('Error deleting address: ' + error.message, 'error');
  } finally {
    loading.value = false;
  }
};

// Method for the OK button in options panel
const handleOK = () => {
  console.log('OK button clicked');
  showOptions.value = false;
};

// Method for the Exit button in options panel
const handleExit = () => {
  console.log('Exit button clicked');
  showOptions.value = false;
};
</script>

<style scoped>
/* Add any component-specific styles here */
.notification {
  position: fixed;
  bottom: 20px;
  right: 20px;
  z-index: 1000;
  padding: 15px;
  border-radius: 5px;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  transition: all 0.3s ease;
}

.notification-success {
  background-color: #d4edda;
  color: #155724;
  border-left: 4px solid #28a745;
}

.notification-error {
  background-color: #f8d7da;
  color: #721c24;
  border-left: 4px solid #dc3545;
}

.notification-warning {
  background-color: #fff3cd;
  color: #856404;
  border-left: 4px solid #ffc107;
}
</style>