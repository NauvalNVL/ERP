<template>
  <AppLayout :header="header">
    <Head title="Customer Alternate Address" />

    <!-- Hidden form with CSRF token -->
    <form ref="csrfForm" class="hidden">
        @csrf
    </form>

    <!-- Header Section -->
    <div class="bg-gradient-to-r from-cyan-700 to-blue-600 p-6 rounded-t-lg shadow-lg">
        <h2 class="text-2xl font-bold text-white mb-2 flex items-center">
            <i class="fas fa-map-marker-alt mr-3"></i> Define Customer Alternate Address
        </h2>
        <p class="text-cyan-100">Manage alternate addresses for customer accounts</p>
    </div>

    <!-- Error Notification -->
    <div v-if="errorMessage" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg my-3 flex items-center justify-between">
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
    <div v-if="successMessage" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg my-3 flex items-center justify-between">
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

    <div class="bg-white rounded-b-lg shadow-lg p-6 mb-6">
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Left Column -->
        <div class="lg:col-span-2">
          <div class="bg-white p-6 rounded-lg shadow-md border-t-4 border-blue-500">
            <div class="flex items-center mb-6 pb-2 border-b border-gray-200">
              <div class="p-2 bg-blue-500 rounded-lg mr-3">
                <i class="fas fa-users text-white"></i>
              </div>
              <h3 class="text-xl font-semibold text-gray-800">Customer Management</h3>
            </div>
            
            <!-- Search Section -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-6">
              <div class="col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Customer Code:</label>
                <div class="relative flex">
                  <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500">
                    <i class="fas fa-user"></i>
                  </span>
                  <input type="text" v-model="searchTerm" class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none border border-gray-300 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                  <button type="button" @click="showCustomerModal = true" class="inline-flex items-center px-3 py-2 border border-l-0 border-gray-300 bg-blue-500 hover:bg-blue-600 text-white rounded-r-md transition-colors transform active:translate-y-px">
                    <i class="fas fa-table"></i>
                  </button>
                </div>
              </div>
              <div class="col-span-1">
                <label class="block text-sm font-medium text-gray-700 mb-1">Options:</label>
                <button 
                  @click="showOptions = !showOptions" 
                  class="w-full flex items-center justify-center px-4 py-2 bg-indigo-500 hover:bg-indigo-600 text-white rounded transition-colors transform active:translate-y-px"
                >
                  <i class="fas fa-cog mr-2"></i> Settings
                </button>
              </div>
            </div>

            <!-- Data Status Information -->
            <div v-if="loadingCustomers" class="mt-4 bg-yellow-100 p-3 rounded">
              <div class="flex items-center">
                <div class="mr-3">
                  <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-yellow-700"></div>
                </div>
                <p class="text-sm font-medium text-yellow-800">Loading customer data...</p>
              </div>
            </div>
            <div v-else-if="customers.length === 0" class="mt-4 bg-yellow-100 p-3 rounded">
              <p class="text-sm font-medium text-yellow-800">No customer data available.</p>
              <p class="text-xs text-yellow-700 mt-1">Make sure the database is properly configured.</p>
            </div>
            <div v-else class="mt-4 bg-green-100 p-3 rounded">
              <p class="text-sm font-medium text-green-800">Data available: {{ customers.length }} customers found.</p>
              <p v-if="selectedCustomer" class="text-xs text-green-700 mt-1">
                Selected: <span class="font-semibold">{{ selectedCustomer.customer_code }}</span> - {{ selectedCustomer.customer_name }}
              </p>
            </div>
          </div>

          <!-- Customer Table -->
          <div class="mt-6 bg-white p-6 rounded-lg shadow-md border-t-4 border-blue-500">
            <div class="flex items-center mb-4 pb-2 border-b border-gray-200">
              <div class="p-2 bg-blue-500 rounded-lg mr-3">
                <i class="fas fa-list text-white"></i>
              </div>
              <h3 class="text-xl font-semibold text-gray-800">Customers</h3>
            </div>
            
            <div class="overflow-x-auto">
              <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                  <tr>
                    <th @click="sortCustomers('customer_name')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100">
                      Customer Name
                      <i class="fas fa-sort ml-1" :class="{
                        'fa-sort-up': sortBy === 'customer_name' && sortAsc,
                        'fa-sort-down': sortBy === 'customer_name' && !sortAsc
                      }"></i>
                    </th>
                    <th @click="sortCustomers('customer_code')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100">
                      Customer Code
                      <i class="fas fa-sort ml-1" :class="{
                        'fa-sort-up': sortBy === 'customer_code' && sortAsc,
                        'fa-sort-down': sortBy === 'customer_code' && !sortAsc
                      }"></i>
                    </th>
                    <th @click="sortCustomers('status')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100">
                      Status
                      <i class="fas fa-sort ml-1" :class="{
                        'fa-sort-up': sortBy === 'status' && sortAsc,
                        'fa-sort-down': sortBy === 'status' && !sortAsc
                      }"></i>
                    </th>
                  </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                  <tr v-if="filteredCustomers.length === 0">
                    <td colspan="3" class="px-6 py-4 text-center text-sm text-gray-500">
                      No customers found matching your search criteria.
                    </td>
                  </tr>
                  <tr 
                    v-for="customer in filteredCustomers" 
                    :key="customer.id"
                    @click="selectCustomer(customer)"
                    class="hover:bg-blue-50 cursor-pointer transition-colors"
                    :class="{ 'bg-blue-50': selectedCustomer && selectedCustomer.id === customer.id }"
                  >
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                      {{ customer.customer_name }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                      {{ customer.customer_code }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <span 
                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                        :class="{
                          'bg-green-100 text-green-800': customer.status === 'Active',
                          'bg-red-100 text-red-800': customer.status === 'Obsolete' || customer.status === 'Inactive'
                        }"
                      >
                        {{ customer.status }}
                      </span>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
        
        <!-- Right Column -->
        <div class="lg:col-span-1">
          <!-- Addresses Panel -->
          <div class="bg-white p-6 rounded-lg shadow-md border-t-4 border-teal-500 mb-6">
            <div class="flex items-center mb-4 pb-2 border-b border-gray-200">
              <div class="p-2 bg-teal-500 rounded-lg mr-3">
                <i class="fas fa-map-marker-alt text-white"></i>
              </div>
              <h3 class="text-lg font-semibold text-gray-800">Alternate Addresses</h3>
            </div>

            <div v-if="!selectedCustomer" class="p-4 bg-gray-50 rounded-lg text-center">
              <p class="text-gray-500">Please select a customer to view addresses</p>
            </div>
            
            <div v-else-if="loadingAddresses" class="p-4 bg-gray-50 rounded-lg text-center">
              <div class="flex justify-center">
                <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-teal-700"></div>
              </div>
              <p class="mt-2 text-gray-500">Loading addresses...</p>
            </div>
            
            <div v-else-if="addresses.length === 0" class="p-4 bg-gray-50 rounded-lg">
              <p class="text-gray-500 text-center">No alternate addresses found</p>
              <div class="mt-4 flex justify-center">
                <button 
                  @click="openAddModal" 
                  class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600"
                >
                  <i class="fas fa-plus mr-1"></i> Add Address
                </button>
              </div>
            </div>
            
            <div v-else class="space-y-3">
              <div v-for="address in addresses" :key="address.id" class="p-3 bg-gray-50 rounded-lg border border-gray-200 hover:border-teal-300 hover:shadow-sm transition-all">
                <div class="flex justify-between items-start">
                  <div>
                    <p class="font-medium text-gray-900">{{ address.address }}</p>
                    <p class="text-sm text-gray-600">{{ address.city }}, {{ address.postal_code }}</p>
                    <p class="text-xs text-gray-500">{{ address.phone }}</p>
                    <p class="text-xs text-teal-600">{{ address.email }}</p>
                  </div>
                  <div class="flex space-x-1">
                    <button @click.stop="openEditModal(address)" class="p-1 text-blue-600 hover:bg-blue-50 rounded">
                      <i class="fas fa-edit"></i>
                    </button>
                    <button @click.stop="confirmDelete(address)" class="p-1 text-red-600 hover:bg-red-50 rounded">
                      <i class="fas fa-trash-alt"></i>
                    </button>
                  </div>
                </div>
              </div>
              
              <div class="mt-4 flex justify-center">
                <button 
                  @click="openAddModal" 
                  class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600"
                >
                  <i class="fas fa-plus mr-1"></i> Add Address
                </button>
              </div>
            </div>
          </div>
          
          <!-- Options Panel (when showOptions is true) -->
          <div v-if="showOptions" class="bg-white p-6 rounded-lg shadow-md border-t-4 border-purple-500">
            <div class="flex items-center justify-between mb-4 pb-2 border-b border-gray-200">
              <div class="flex items-center">
                <div class="p-2 bg-purple-500 rounded-lg mr-3">
                  <i class="fas fa-cog text-white"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-800">Options</h3>
              </div>
              <button @click="showOptions = false" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times"></i>
              </button>
            </div>

            <div class="space-y-4">
              <!-- Sort Options -->
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Sort by:</label>
                <div class="space-y-2">
                  <label class="flex items-center">
                    <input type="radio" v-model="sortBy" value="customer_name" class="mr-2">
                    <span>Customer Name</span>
                  </label>
                  <label class="flex items-center">
                    <input type="radio" v-model="sortBy" value="customer_code" class="mr-2">
                    <span>Customer Code</span>
                  </label>
                  <label class="flex items-center">
                    <input type="radio" v-model="sortBy" value="status" class="mr-2">
                    <span>Status</span>
                  </label>
                </div>
              </div>
              
              <!-- Sort Direction -->
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Direction:</label>
                <label class="flex items-center">
                  <input type="checkbox" v-model="sortAsc" class="mr-2">
                  <span>Ascending Order</span>
                </label>
              </div>
              
              <!-- Status Filter -->
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status Filter:</label>
                <div class="space-y-2">
                  <label class="flex items-center">
                    <input type="radio" v-model="recordStatus" value="All" class="mr-2">
                    <span>All</span>
                  </label>
                  <label class="flex items-center">
                    <input type="radio" v-model="recordStatus" value="Active" class="mr-2">
                    <span>Active Only</span>
                  </label>
                  <label class="flex items-center">
                    <input type="radio" v-model="recordStatus" value="Obsolete" class="mr-2">
                    <span>Obsolete Only</span>
                  </label>
                </div>
              </div>
            </div>
          </div>

          <!-- Info Panel -->
          <div v-else class="bg-white p-6 rounded-lg shadow-md border-t-4 border-purple-500">
            <div class="flex items-center mb-4 pb-2 border-b border-gray-200">
              <div class="p-2 bg-purple-500 rounded-lg mr-3">
                <i class="fas fa-info-circle text-white"></i>
              </div>
              <h3 class="text-lg font-semibold text-gray-800">Information</h3>
            </div>

            <div class="space-y-4">
              <div class="p-4 bg-purple-50 rounded-lg">
                <h4 class="text-sm font-semibold text-purple-800 uppercase tracking-wider mb-2">Instructions</h4>
                <ul class="list-disc pl-5 text-sm text-gray-600 space-y-1">
                  <li>Select a customer first</li>
                  <li>View and manage alternate addresses</li>
                  <li>You can add multiple addresses for each customer</li>
                  <li>All addresses must have valid email format</li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Customer Selection Modal -->
    <div v-if="showCustomerModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
      <div class="bg-white rounded-lg shadow-xl w-11/12 max-w-4xl mx-auto">
        <div class="flex items-center justify-between p-4 border-b border-gray-200 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-t-lg">
          <div class="flex items-center">
            <div class="p-2 bg-white bg-opacity-30 rounded-lg mr-3">
              <i class="fas fa-users"></i>
            </div>
            <h3 class="text-xl font-semibold">Select Customer</h3>
          </div>
          <button type="button" @click="showCustomerModal = false" class="text-white hover:text-gray-200">
            <i class="fas fa-times text-xl"></i>
          </button>
        </div>
        
        <div class="p-6">
          <div class="mb-4">
            <div class="relative">
              <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
                <i class="fas fa-search"></i>
              </span>
              <input 
                type="text" 
                v-model="modalSearchTerm" 
                placeholder="Search customers..." 
                class="pl-10 pr-4 py-2 w-full border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 bg-gray-50"
              >
            </div>
          </div>
          
          <div class="overflow-x-auto max-h-96 rounded-lg border border-gray-200">
            <table class="w-full divide-y divide-gray-200">
              <thead class="bg-gray-50 sticky top-0">
                <tr>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer Name</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer Code</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                <tr v-if="filteredModalCustomers.length === 0">
                  <td colspan="3" class="px-6 py-4 text-center text-sm text-gray-500">
                    No customers found matching your search criteria.
                  </td>
                </tr>
                <tr 
                  v-for="customer in filteredModalCustomers" 
                  :key="customer.id"
                  @click="selectCustomerFromModal(customer)"
                  class="hover:bg-blue-50 cursor-pointer transition-colors"
                >
                  <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                    {{ customer.customer_name }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    {{ customer.customer_code }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <span 
                      class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                      :class="{
                        'bg-green-100 text-green-800': customer.status === 'Active',
                        'bg-red-100 text-red-800': customer.status === 'Obsolete' || customer.status === 'Inactive'
                      }"
                    >
                      {{ customer.status }}
                    </span>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
          
          <div class="mt-4 flex justify-end">
            <button 
              type="button" 
              @click="showCustomerModal = false" 
              class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300"
            >
              Cancel
            </button>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Address Form Modal -->
    <div v-if="showModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
      <div class="bg-white rounded-lg shadow-xl w-11/12 max-w-lg mx-auto">
        <div class="flex items-center justify-between p-4 border-b border-gray-200 bg-gradient-to-r from-teal-600 to-teal-700 text-white rounded-t-lg">
          <div class="flex items-center">
            <div class="p-2 bg-white bg-opacity-30 rounded-lg mr-3">
              <i class="fas fa-map-marker-alt"></i>
            </div>
            <h3 class="text-xl font-semibold">{{ isEditing ? 'Edit Address' : 'Add New Address' }}</h3>
          </div>
          <button @click="closeModal" class="text-white hover:text-gray-200">
            <i class="fas fa-times text-xl"></i>
          </button>
        </div>
        
        <div class="p-6">
          <form @submit.prevent="saveAddress">
            <div class="space-y-4">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                <input 
                  type="text" 
                  v-model="addressForm.address" 
                  class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-teal-500"
                  required
                />
              </div>
              
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">City</label>
                <input 
                  type="text" 
                  v-model="addressForm.city" 
                  class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-teal-500"
                  required
                />
              </div>
              
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Postal Code</label>
                <input 
                  type="text" 
                  v-model="addressForm.postal_code" 
                  class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-teal-500"
                  required
                />
              </div>
              
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                <input 
                  type="text" 
                  v-model="addressForm.phone" 
                  class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-teal-500"
                  required
                />
              </div>
              
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input 
                  type="email" 
                  v-model="addressForm.email" 
                  class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-teal-500"
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
                class="px-4 py-2 bg-teal-600 text-white rounded-md hover:bg-teal-700"
                :disabled="saving"
              >
                <span v-if="saving">Saving...</span>
                <span v-else>Save</span>
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
    
    <!-- Delete Confirmation Modal -->
    <div v-if="showDeleteModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
      <div class="bg-white rounded-lg shadow-xl w-11/12 max-w-md mx-auto">
        <div class="flex items-center justify-between p-4 border-b border-gray-200 bg-gradient-to-r from-red-600 to-red-700 text-white rounded-t-lg">
          <div class="flex items-center">
            <div class="p-2 bg-white bg-opacity-30 rounded-lg mr-3">
              <i class="fas fa-exclamation-triangle"></i>
            </div>
            <h3 class="text-xl font-semibold">Confirm Deletion</h3>
          </div>
          <button @click="showDeleteModal = false" class="text-white hover:text-gray-200">
            <i class="fas fa-times text-xl"></i>
          </button>
        </div>
        
        <div class="p-6">
          <p class="mb-6 text-gray-700">Are you sure you want to delete this address? This action cannot be undone.</p>
          
          <div class="flex justify-end space-x-3">
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
    </div>
    
    <!-- Loading Overlay -->
    <div v-if="saving || deleting" class="fixed inset-0 z-50 bg-black bg-opacity-50 flex justify-center items-center">
      <div class="w-12 h-12 border-4 border-solid border-blue-500 border-t-transparent rounded-full animate-spin"></div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue';
import { Head, Link, usePage } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import axios from 'axios';

// Access props from Inertia
const props = defineProps({
  initialData: Object,
  header: {
    type: String,
    default: 'Customer Alternate Address'
  }
});

// Access page data
const page = usePage();

// Reference to the CSRF form
const csrfForm = ref(null);

// Function to get fresh CSRF token from the form
const getCsrfToken = () => {
  // Try to get token from meta tag first
  let token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
  
  // If token from meta tag is not available or we want a fresh token, get from the form
  if (csrfForm.value) {
    const tokenInput = csrfForm.value.querySelector('input[name="_token"]');
    if (tokenInput) {
      token = tokenInput.value;
    }
  }
  
  return token || '';
};

// Define reactive state variables
const customers = ref([]);
const addresses = ref([]);
const selectedCustomer = ref(null);
const searchTerm = ref('');
const modalSearchTerm = ref('');
const loadingCustomers = ref(true);
const loadingAddresses = ref(false);
const errorMessage = ref('');
const successMessage = ref('');

// Modal state
const showModal = ref(false);
const showCustomerModal = ref(false);
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

// Watch for changes in search query to filter the data
watch(searchTerm, (newQuery) => {
  if (newQuery && customers.value.length > 0) {
    const foundCustomer = customers.value.find(customer => 
      customer.customer_code.toLowerCase().includes(newQuery.toLowerCase()) ||
      customer.customer_name.toLowerCase().includes(newQuery.toLowerCase())
    );
    
    if (foundCustomer) {
      selectCustomer(foundCustomer);
    }
  }
});

// Computed property for filtered customers in main view
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

// Computed property for filtered customers in modal
const filteredModalCustomers = computed(() => {
  if (!customers.value.length) return [];
  
  // Filter by search term only for the modal
  if (!modalSearchTerm.value) return customers.value;
  
  return customers.value.filter(customer => 
    (customer.customer_name && customer.customer_name.toLowerCase().includes(modalSearchTerm.value.toLowerCase())) ||
    (customer.customer_code && customer.customer_code.toLowerCase().includes(modalSearchTerm.value.toLowerCase()))
  );
});

// Function to select a customer and fetch their addresses
const selectCustomer = async (customer) => {
  selectedCustomer.value = customer;
  errorMessage.value = ''; // Clear any error messages
  successMessage.value = ''; // Clear any success messages
  fetchAddresses(customer.customer_code);
};

// Function to select a customer from the modal
const selectCustomerFromModal = (customer) => {
  selectCustomer(customer);
  showCustomerModal.value = false;
  searchTerm.value = customer.customer_code;
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
    const csrfToken = getCsrfToken();
    
    if (isEditing.value) {
      // Update existing address
      const response = await axios.put(`/api/customer-alternate-addresses/${editingAddressId.value}`, addressForm.value, {
        headers: {
          'X-CSRF-TOKEN': csrfToken
        }
      });
      
      if (response.data.success) {
        successMessage.value = 'Address updated successfully';
      } else {
        throw new Error(response.data.message || 'Failed to update address');
      }
    } else {
      // Create new address
      const response = await axios.post('/api/customer-alternate-addresses', {
        ...addressForm.value,
        customer_code: selectedCustomer.value.customer_code,
      }, {
        headers: {
          'X-CSRF-TOKEN': csrfToken
        }
      });
      
      if (response.data.success) {
        successMessage.value = 'New address added successfully';
      } else {
        throw new Error(response.data.message || 'Failed to add address');
      }
    }
    
    // Refresh addresses
    await fetchAddresses(selectedCustomer.value.customer_code);
    closeModal();
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
    const csrfToken = getCsrfToken();
    
    const response = await axios.delete(`/api/customer-alternate-addresses/${addressToDelete.value.id}`, {
      headers: {
        'X-CSRF-TOKEN': csrfToken
      }
    });
    
    if (response.data.success) {
      // Refresh addresses
      await fetchAddresses(selectedCustomer.value.customer_code);
      showDeleteModal.value = false;
      addressToDelete.value = null;
      successMessage.value = 'Address deleted successfully';
    } else {
      throw new Error(response.data.message || 'Failed to delete address');
    }
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