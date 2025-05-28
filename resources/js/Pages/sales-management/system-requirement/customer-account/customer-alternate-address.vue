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
                <h1 class="text-2xl font-bold text-gray-800">Customer Alternate Address</h1>
                <p class="text-gray-600">Define Customer Alternate Address</p>
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
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
          <!-- Customer List -->
          <div class="lg:col-span-3">
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
              <div class="p-6 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-800">Customer Account Table</h2>
              </div>
              
              <div class="overflow-x-auto">
                <table class="w-full">
                  <thead class="bg-gray-50">
                    <tr>
                      <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">Customer Code</th>
                      <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">Customer Name</th>
                      <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">Status</th>
                      <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">Actions</th>
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
                      <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ customer.code }}</td>
                      <td class="px-6 py-4 text-sm text-gray-700">{{ customer.name }}</td>
                      <td class="px-6 py-4">
                        <span 
                          class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                          :class="{
                            'bg-green-100 text-green-800': customer.status === 'active', 
                            'bg-red-100 text-red-800': customer.status === 'obsolete'
                          }">
                          {{ customer.status === 'active' ? 'Active' : 'Obsolete' }}
                        </span>
                      </td>
                      <td class="px-6 py-4">
                        <div class="flex space-x-2">
                          <button class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors duration-150" @click="handleEdit(customer)">
                            <!-- Edit Icon -->
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4"><path d="M12 20h9"></path><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path></svg>
                          </button>
                          <button class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors duration-150" @click="handleDelete(customer)">
                            <!-- Trash2 Icon -->
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
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
                        value="code"
                        v-model="sortBy"
                        class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500"
                      />
                      <span class="ml-3 text-sm text-gray-700">Customer Code</span>
                    </label>
                    <label class="flex items-center">
                      <input
                        type="radio"
                        name="sortBy"
                        value="name"
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
                        value="active"
                        v-model="recordStatus"
                        class="w-4 h-4 text-green-600 border-gray-300 focus:ring-green-500"
                      />
                      <span class="ml-3 text-sm text-gray-700">Active</span>
                    </label>
                    <label class="flex items-center">
                      <input
                        type="radio"
                        name="recordStatus"
                        value="obsolete"
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
                <p class="text-sm"><span class="font-medium text-gray-600">Code:</span> {{ selectedCustomer.code }}</p>
                <p class="text-sm"><span class="font-medium text-gray-600">Name:</span> {{ selectedCustomer.name }}</p>
                <p class="text-sm">
                  <span class="font-medium text-gray-600">Status:</span> 
                  <span 
                    class="ml-2 px-2 py-1 rounded text-xs"
                    :class="{
                      'bg-green-100 text-green-800': selectedCustomer.status === 'active', 
                      'bg-red-100 text-red-800': selectedCustomer.status === 'obsolete'
                    }">
                    {{ selectedCustomer.status === 'active' ? 'Active' : 'Obsolete' }}
                  </span>
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, computed } from 'vue';

// Define reactive state variables
const sortBy = ref('name');
const recordStatus = ref('active');
const showOptions = ref(false);
// Initial customer data - replace with data fetched from your backend
const customers = ref([
  { id: 1, code: 'C001', name: 'PT. Indah Karya', status: 'active' },
  { id: 2, code: 'C002', name: 'PT. Maju Bersama', status: 'active' },
  { id: 3, code: 'C003', name: 'CV. Berkah Jaya', status: 'obsolete' },
  { id: 4, code: 'C004', name: 'UD. Sukses Mandiri', status: 'active' },
]);

const selectedCustomer = ref(null);
const searchTerm = ref('');

// Computed property to filter and sort customers based on state
const filteredCustomers = computed(() => {
  let filtered = customers.value.filter(customer => {
    const matchesSearch = customer.name.toLowerCase().includes(searchTerm.value.toLowerCase()) ||
                         customer.code.toLowerCase().includes(searchTerm.value.toLowerCase());
    const matchesStatus = recordStatus.value === 'both' ? true : customer.status === recordStatus.value;
    return matchesSearch && matchesStatus;
  });

  // Basic sorting logic by name or code
  filtered.sort((a, b) => {
    if (sortBy.value === 'name') {
      return a.name.localeCompare(b.name);
    } else if (sortBy.value === 'code') {
      return a.code.localeCompare(b.code);
    }
    return 0; // No specific sorting applied if sortBy is neither 'name' nor 'code'
  });

  return filtered;
});

// handleSortChange and handleStatusChange are handled directly by v-model on radio inputs

// --- Action Methods (Implement these) ---

// Method to handle editing a customer
const handleEdit = (customer) => {
  console.log('Edit customer:', customer);
  // TODO: Implement edit logic, e.g., open a modal or navigate to an edit page
};

// Method to handle deleting a customer
const handleDelete = (customer) => {
  console.log('Delete customer:', customer);
  // TODO: Implement delete logic, e.g., show a confirmation modal and call an API endpoint
};

// Method for the OK button in options panel
const handleOK = () => {
    console.log('OK button clicked');
    // TODO: Implement logic for applying selected options (if any additional actions are needed)
    // For sorting and filtering, the computed property updates automatically.
    // This button might be used to close the options panel or trigger other actions.
    showOptions.value = false; // Example: close options panel on OK
};

// Method for the Exit button in options panel
const handleExit = () => {
    console.log('Exit button clicked');
    // TODO: Implement logic for exiting or cancelling options
    showOptions.value = false; // Example: close options panel on Exit
};

// TODO: Add methods for adding new customers if needed
// const handleAddCustomer = () => { /* ... */ };

// TODO: Implement data fetching when the component is mounted
// import { onMounted } from 'vue';
// onMounted(() => {
//   // Fetch customer data from your API here
// });

// TODO: Consider using Inertia.js methods for data persistence and navigation if applicable
// import { usePage, useForm } from '@inertiajs/vue3';

</script>

<style scoped>
/* Add any component-specific styles here */
</style>