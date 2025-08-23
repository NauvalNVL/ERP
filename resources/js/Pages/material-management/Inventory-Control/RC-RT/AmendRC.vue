<template>
  <AppLayout :header="header">
    <div class="max-w-7xl mx-auto">
      <!-- Header Section -->
      <div class="bg-gradient-to-r from-orange-600 to-orange-800 rounded-lg shadow-lg p-6 mb-6 text-white">
        <div class="flex items-center justify-between">
          <div>
            <h1 class="text-3xl font-bold flex items-center">
              <i class="fas fa-edit mr-3"></i>
              Amend Receipt (RC)
            </h1>
            <p class="text-orange-100 mt-2 text-lg">Edit existing receipt transactions</p>
          </div>
          <div class="flex space-x-3">
            <button
              @click="updateTransaction"
              :disabled="!isFormValid || isSaving"
              class="bg-green-600 hover:bg-green-700 disabled:bg-gray-400 text-white font-bold py-3 px-6 rounded-lg transition-all duration-200 flex items-center shadow-lg hover:shadow-xl"
            >
              <i class="fas fa-save mr-2" v-if="!isSaving"></i>
              <i class="fas fa-spinner fa-spin mr-2" v-else></i>
              {{ isSaving ? 'Updating...' : 'Update RC' }}
            </button>
            <button
              @click="postTransaction"
              :disabled="!canPost || isPosting"
              class="bg-blue-600 hover:bg-blue-700 disabled:bg-gray-400 text-white font-bold py-3 px-6 rounded-lg transition-all duration-200 flex items-center shadow-lg hover:shadow-xl"
            >
              <i class="fas fa-check mr-2" v-if="!isPosting"></i>
              <i class="fas fa-spinner fa-spin mr-2" v-else></i>
              {{ isPosting ? 'Posting...' : 'Post RC' }}
            </button>
            <button
              @click="cancelTransaction"
              :disabled="!canCancel || isCancelling"
              class="bg-red-600 hover:bg-red-700 disabled:bg-gray-400 text-white font-bold py-3 px-6 rounded-lg transition-all duration-200 flex items-center shadow-lg hover:shadow-xl"
            >
              <i class="fas fa-times mr-2" v-if="!isCancelling"></i>
              <i class="fas fa-spinner fa-spin mr-2" v-else></i>
              {{ isCancelling ? 'Cancelling...' : 'Cancel RC' }}
            </button>
          </div>
        </div>
      </div>

      <!-- Status Bar -->
      <div class="bg-white rounded-lg shadow-lg p-4 mb-6 border-l-4 border-orange-500">
        <div class="flex items-center justify-between">
          <div class="flex items-center space-x-4">
            <div class="flex items-center">
              <i class="fas fa-calendar-alt text-orange-500 mr-2"></i>
              <span class="text-sm font-medium text-gray-700">Current Period: {{ currentPeriod }}</span>
            </div>
            <div class="flex items-center">
              <i class="fas fa-user text-orange-500 mr-2"></i>
              <span class="text-sm font-medium text-gray-700">User: {{ currentUser }}</span>
            </div>
            <div class="flex items-center">
              <i class="fas fa-clock text-orange-500 mr-2"></i>
              <span class="text-sm font-medium text-gray-700">{{ currentTime }}</span>
            </div>
          </div>
          <div class="flex items-center space-x-2">
            <div class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-medium">
              <i class="fas fa-search mr-1"></i>
              Search Mode
            </div>
            <div class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-medium">
              <i class="fas fa-check-circle mr-1"></i>
              Ready to Edit
            </div>
          </div>
        </div>
      </div>

      <!-- Search Section -->
      <div class="bg-white rounded-lg shadow-lg p-6 mb-6 border border-gray-200">
        <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center border-b border-gray-200 pb-2">
          <i class="fas fa-search text-blue-500 mr-2"></i>
          Search RC Transaction
        </h3>
        
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Transaction Number</label>
            <input
              type="text"
              v-model="searchForm.transaction_number"
              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
              placeholder="Enter transaction number"
            />
          </div>
          
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Date From</label>
            <input
              type="date"
              v-model="searchForm.date_from"
              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
            />
          </div>
          
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Date To</label>
            <input
              type="date"
              v-model="searchForm.date_to"
              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
            />
          </div>
          
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
            <select
              v-model="searchForm.status"
              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
            >
              <option value="">All Status</option>
              <option value="Draft">Draft</option>
              <option value="Posted">Posted</option>
              <option value="Cancelled">Cancelled</option>
            </select>
          </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Supplier</label>
            <input
              type="text"
              v-model="searchForm.supplier"
              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
              placeholder="Enter supplier name"
            />
          </div>
          
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">SKU</label>
            <input
              type="text"
              v-model="searchForm.sku"
              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
              placeholder="Enter SKU code"
            />
          </div>
          
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Location</label>
            <input
              type="text"
              v-model="searchForm.location"
              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
              placeholder="Enter location code"
            />
          </div>
        </div>
        
        <div class="mt-4 flex space-x-3">
          <button
            @click="searchTransactions"
            :disabled="isSearching"
            class="bg-blue-600 hover:bg-blue-700 disabled:bg-gray-400 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-200 flex items-center"
          >
            <i class="fas fa-search mr-2" v-if="!isSearching"></i>
            <i class="fas fa-spinner fa-spin mr-2" v-else></i>
            {{ isSearching ? 'Searching...' : 'Search' }}
          </button>
          <button
            @click="clearSearch"
            class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-200 flex items-center"
          >
            <i class="fas fa-eraser mr-2"></i>
            Clear Search
          </button>
          <button
            @click="exportSearchResults"
            :disabled="transactions.length === 0"
            class="bg-green-600 hover:bg-green-700 disabled:bg-gray-400 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-200 flex items-center"
          >
            <i class="fas fa-download mr-2"></i>
            Export Results
          </button>
        </div>
      </div>

      <!-- Statistics Cards -->
      <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow-lg p-6 border border-gray-200">
          <div class="flex items-center">
            <div class="p-3 rounded-full bg-blue-100 text-blue-600">
              <i class="fas fa-file-alt text-2xl"></i>
            </div>
            <div class="ml-4">
              <p class="text-sm font-medium text-gray-600">Total RC</p>
              <p class="text-2xl font-bold text-gray-900">{{ statistics.total }}</p>
            </div>
          </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-lg p-6 border border-gray-200">
          <div class="flex items-center">
            <div class="p-3 rounded-full bg-green-100 text-green-600">
              <i class="fas fa-check-circle text-2xl"></i>
            </div>
            <div class="ml-4">
              <p class="text-sm font-medium text-gray-600">Posted</p>
              <p class="text-2xl font-bold text-gray-900">{{ statistics.posted }}</p>
            </div>
          </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-lg p-6 border border-gray-200">
          <div class="flex items-center">
            <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
              <i class="fas fa-clock text-2xl"></i>
            </div>
            <div class="ml-4">
              <p class="text-sm font-medium text-gray-600">Draft</p>
              <p class="text-2xl font-bold text-gray-900">{{ statistics.draft }}</p>
            </div>
          </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-lg p-6 border border-gray-200">
          <div class="flex items-center">
            <div class="p-3 rounded-full bg-red-100 text-red-600">
              <i class="fas fa-times-circle text-2xl"></i>
            </div>
            <div class="ml-4">
              <p class="text-sm font-medium text-gray-600">Cancelled</p>
              <p class="text-2xl font-bold text-gray-900">{{ statistics.cancelled }}</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Transactions Table -->
      <div class="bg-white rounded-lg shadow-lg border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
          <h3 class="text-lg font-bold text-gray-800 flex items-center">
            <i class="fas fa-list text-blue-500 mr-2"></i>
            RC Transactions
            <span class="ml-2 text-sm font-normal text-gray-500">({{ transactions.length }} records)</span>
          </h3>
        </div>
        
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100" @click="sortBy('transaction_number')">
                  Transaction #
                  <i class="fas fa-sort ml-1"></i>
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100" @click="sortBy('transaction_date')">
                  Date
                  <i class="fas fa-sort ml-1"></i>
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Supplier</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">SKU</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100" @click="sortBy('status')">
                  Status
                  <i class="fas fa-sort ml-1"></i>
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-for="transaction in paginatedTransactions" :key="transaction.id" class="hover:bg-gray-50 transition-colors duration-200">
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                  {{ transaction.transaction_number }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                  {{ formatDate(transaction.transaction_date) }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                  <div>
                    <div class="font-medium">{{ transaction.supplier_code }}</div>
                    <div class="text-xs text-gray-400">{{ transaction.supplier_name }}</div>
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                  <div>
                    <div class="font-medium">{{ transaction.sku_code }}</div>
                    <div class="text-xs text-gray-400">{{ transaction.sku_description }}</div>
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                  {{ formatNumber(transaction.quantity) }} {{ transaction.uom }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                  {{ formatCurrency(transaction.total_amount) }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full" :class="getStatusBadgeClass(transaction.status)">
                    {{ transaction.status }}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                  <div class="flex space-x-2">
                    <button
                      @click="editTransaction(transaction)"
                      class="text-blue-600 hover:text-blue-900 transition-colors duration-200"
                      title="Edit"
                    >
                      <i class="fas fa-edit"></i>
                    </button>
                    <button
                      @click="viewTransaction(transaction)"
                      class="text-green-600 hover:text-green-900 transition-colors duration-200"
                      title="View"
                    >
                      <i class="fas fa-eye"></i>
                    </button>
                    <button
                      @click="printTransaction(transaction)"
                      class="text-purple-600 hover:text-purple-900 transition-colors duration-200"
                      title="Print"
                    >
                      <i class="fas fa-print"></i>
                    </button>
                    <button
                      v-if="transaction.status === 'Draft'"
                      @click="deleteTransaction(transaction)"
                      class="text-red-600 hover:text-red-900 transition-colors duration-200"
                      title="Delete"
                    >
                      <i class="fas fa-trash"></i>
                    </button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Pagination -->
        <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
          <div class="flex items-center justify-between">
            <div class="flex-1 flex justify-between sm:hidden">
              <button
                @click="previousPage"
                :disabled="currentPage === 1"
                class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 disabled:opacity-50"
              >
                Previous
              </button>
              <button
                @click="nextPage"
                :disabled="currentPage >= totalPages"
                class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 disabled:opacity-50"
              >
                Next
              </button>
            </div>
            <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
              <div>
                <p class="text-sm text-gray-700">
                  Showing
                  <span class="font-medium">{{ startIndex + 1 }}</span>
                  to
                  <span class="font-medium">{{ endIndex }}</span>
                  of
                  <span class="font-medium">{{ transactions.length }}</span>
                  results
                </p>
              </div>
              <div>
                <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                  <button
                    @click="previousPage"
                    :disabled="currentPage === 1"
                    class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 disabled:opacity-50"
                  >
                    <i class="fas fa-chevron-left"></i>
                  </button>
                  <button
                    v-for="page in visiblePages"
                    :key="page"
                    @click="goToPage(page)"
                    :class="[
                      'relative inline-flex items-center px-4 py-2 border text-sm font-medium',
                      page === currentPage
                        ? 'z-10 bg-blue-50 border-blue-500 text-blue-600'
                        : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50'
                    ]"
                  >
                    {{ page }}
                  </button>
                  <button
                    @click="nextPage"
                    :disabled="currentPage >= totalPages"
                    class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 disabled:opacity-50"
                  >
                    <i class="fas fa-chevron-right"></i>
                  </button>
                </nav>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Edit Modal -->
    <div v-if="showEditModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
      <div class="relative top-20 mx-auto p-5 border w-11/12 max-w-4xl shadow-lg rounded-md bg-white">
        <div class="mt-3">
          <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-bold text-gray-800">Edit RC Transaction</h3>
            <button @click="closeEditModal" class="text-gray-400 hover:text-gray-600">
              <i class="fas fa-times text-xl"></i>
            </button>
          </div>
          
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Transaction Number</label>
              <input
                type="text"
                v-model="editForm.transaction_number"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50"
                readonly
              />
            </div>
            
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Transaction Date</label>
              <input
                type="date"
                v-model="editForm.transaction_date"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
              />
            </div>
            
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Supplier</label>
              <div class="relative">
                <input
                  type="text"
                  v-model="editForm.supplier_code"
                  @click="showSupplierModal = true"
                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 cursor-pointer"
                  placeholder="Click to select supplier"
                  readonly
                />
                <button
                  @click="showSupplierModal = true"
                  class="absolute right-2 top-2 text-gray-400 hover:text-blue-500"
                >
                  <i class="fas fa-search"></i>
                </button>
              </div>
            </div>
            
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Supplier Name</label>
              <input
                type="text"
                v-model="editForm.supplier_name"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50"
                readonly
              />
            </div>
            
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">SKU</label>
              <div class="relative">
                <input
                  type="text"
                  v-model="editForm.sku_code"
                  @click="showSkuModal = true"
                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 cursor-pointer"
                  placeholder="Click to select SKU"
                  readonly
                />
                <button
                  @click="showSkuModal = true"
                  class="absolute right-2 top-2 text-gray-400 hover:text-blue-500"
                >
                  <i class="fas fa-search"></i>
                </button>
              </div>
            </div>
            
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">SKU Description</label>
              <input
                type="text"
                v-model="editForm.sku_description"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50"
                readonly
              />
            </div>
            
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Quantity</label>
              <input
                type="number"
                v-model="editForm.quantity"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                min="0"
                step="0.01"
              />
            </div>
            
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Unit Price</label>
              <input
                type="number"
                v-model="editForm.unit_price"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                min="0"
                step="0.01"
              />
            </div>
            
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Total Amount</label>
              <input
                type="text"
                :value="formatCurrency(editTotalAmount)"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50 font-medium"
                readonly
              />
            </div>
            
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Location</label>
              <div class="relative">
                <input
                  type="text"
                  v-model="editForm.location_code"
                  @click="showLocationModal = true"
                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 cursor-pointer"
                  placeholder="Click to select location"
                  readonly
                />
                <button
                  @click="showLocationModal = true"
                  class="absolute right-2 top-2 text-gray-400 hover:text-blue-500"
                >
                  <i class="fas fa-search"></i>
                </button>
              </div>
            </div>
            
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Location Name</label>
              <input
                type="text"
                v-model="editForm.location_name"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50"
                readonly
              />
            </div>
            
            <div class="md:col-span-2">
              <label class="block text-sm font-medium text-gray-700 mb-2">Remarks</label>
              <textarea
                v-model="editForm.remarks"
                rows="3"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                placeholder="Enter any additional remarks"
              ></textarea>
            </div>
          </div>
          
          <div class="flex justify-end space-x-3 mt-6">
            <button
              @click="closeEditModal"
              class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-200"
            >
              Cancel
            </button>
            <button
              @click="saveEdit"
              :disabled="!isEditFormValid || isSaving"
              class="bg-blue-600 hover:bg-blue-700 disabled:bg-gray-400 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-200 flex items-center"
            >
              <i class="fas fa-save mr-2" v-if="!isSaving"></i>
              <i class="fas fa-spinner fa-spin mr-2" v-else></i>
              {{ isSaving ? 'Saving...' : 'Save Changes' }}
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Modals -->
    <VendorProfileModal
      v-if="showSupplierModal"
      @close="showSupplierModal = false"
      @select="selectSupplier"
    />
    
    <SkuLookupModal
      v-if="showSkuModal"
      @close="showSkuModal = false"
      @select="selectSku"
    />
    
    <LocationTableModal
      v-if="showLocationModal"
      @close="showLocationModal = false"
      @select="selectLocation"
    />
  </AppLayout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import VendorProfileModal from '@/Components/VendorProfileModal.vue'
import SkuLookupModal from '@/Components/SkuLookupModal.vue'
import LocationTableModal from '@/Components/LocationTableModal.vue'
import { useToast } from '@/Composables/useToast'
import axios from 'axios'

const { success, error } = useToast()

const header = 'Amend Receipt (RC)'

// Search form
const searchForm = ref({
  transaction_number: '',
  date_from: '',
  date_to: '',
  status: '',
  supplier: '',
  sku: '',
  location: ''
})

// Edit form
const editForm = ref({
  id: '',
  transaction_number: '',
  transaction_date: '',
  supplier_code: '',
  supplier_name: '',
  sku_code: '',
  sku_description: '',
  quantity: '',
  unit_price: '',
  location_code: '',
  location_name: '',
  remarks: ''
})

// UI state
const isSaving = ref(false)
const isPosting = ref(false)
const isCancelling = ref(false)
const isSearching = ref(false)
const showEditModal = ref(false)
const showSupplierModal = ref(false)
const showSkuModal = ref(false)
const showLocationModal = ref(false)
const transactions = ref([])
const currentPage = ref(1)
const itemsPerPage = ref(10)
const sortField = ref('transaction_date')
const sortDirection = ref('desc')

// Statistics
const statistics = ref({
  total: 0,
  posted: 0,
  draft: 0,
  cancelled: 0
})

// Current period info
const currentPeriod = ref('01/2024')
const currentUser = ref('Admin User')
const currentTime = ref('')

// Computed properties
const editTotalAmount = computed(() => {
  const quantity = parseFloat(editForm.value.quantity) || 0
  const unitPrice = parseFloat(editForm.value.unit_price) || 0
  return quantity * unitPrice
})

const isFormValid = computed(() => {
  return editForm.value.transaction_date &&
         editForm.value.supplier_code &&
         editForm.value.sku_code &&
         editForm.value.quantity &&
         editForm.value.location_code
})

const isEditFormValid = computed(() => {
  return editForm.value.transaction_date &&
         editForm.value.supplier_code &&
         editForm.value.sku_code &&
         editForm.value.quantity &&
         editForm.value.location_code
})

const canPost = computed(() => {
  return editForm.value.id && editForm.value.status === 'Draft'
})

const canCancel = computed(() => {
  return editForm.value.id && editForm.value.status === 'Draft'
})

const sortedTransactions = computed(() => {
  return [...transactions.value].sort((a, b) => {
    let aVal = a[sortField.value]
    let bVal = b[sortField.value]
    
    if (sortField.value === 'transaction_date') {
      aVal = new Date(aVal)
      bVal = new Date(bVal)
    }
    
    if (sortDirection.value === 'asc') {
      return aVal > bVal ? 1 : -1
    } else {
      return aVal < bVal ? 1 : -1
    }
  })
})

const totalPages = computed(() => {
  return Math.ceil(sortedTransactions.value.length / itemsPerPage.value)
})

const startIndex = computed(() => {
  return (currentPage.value - 1) * itemsPerPage.value
})

const endIndex = computed(() => {
  return Math.min(startIndex.value + itemsPerPage.value, sortedTransactions.value.length)
})

const paginatedTransactions = computed(() => {
  return sortedTransactions.value.slice(startIndex.value, endIndex.value)
})

const visiblePages = computed(() => {
  const pages = []
  const maxVisible = 5
  let start = Math.max(1, currentPage.value - Math.floor(maxVisible / 2))
  let end = Math.min(totalPages.value, start + maxVisible - 1)
  
  if (end - start + 1 < maxVisible) {
    start = Math.max(1, end - maxVisible + 1)
  }
  
  for (let i = start; i <= end; i++) {
    pages.push(i)
  }
  
  return pages
})

// Methods
const selectSupplier = (supplier) => {
  editForm.value.supplier_code = supplier.ap_ac_number
  editForm.value.supplier_name = supplier.vendor_name
  showSupplierModal.value = false
}

const selectSku = (sku) => {
  editForm.value.sku_code = sku.sku
  editForm.value.sku_description = sku.sku_name
  showSkuModal.value = false
}

const selectLocation = (location) => {
  editForm.value.location_code = location.code
  editForm.value.location_name = location.name
  showLocationModal.value = false
}

const searchTransactions = async () => {
  isSearching.value = true
  try {
    const response = await axios.get('/api/rc-rt/rc-transactions', {
      params: searchForm.value
    })
    
    if (response.data.success) {
      transactions.value = response.data.data
      updateStatistics()
      currentPage.value = 1
      success(`Found ${transactions.value.length} transactions`)
    } else {
      error(response.data.message || 'Failed to search transactions')
    }
  } catch (err) {
    error('Error searching transactions: ' + err.message)
  } finally {
    isSearching.value = false
  }
}

const clearSearch = () => {
  searchForm.value = {
    transaction_number: '',
    date_from: '',
    date_to: '',
    status: '',
    supplier: '',
    sku: '',
    location: ''
  }
  loadTransactions()
}

const updateStatistics = () => {
  statistics.value = {
    total: transactions.value.length,
    posted: transactions.value.filter(t => t.status === 'Posted').length,
    draft: transactions.value.filter(t => t.status === 'Draft').length,
    cancelled: transactions.value.filter(t => t.status === 'Cancelled').length
  }
}

const editTransaction = (transaction) => {
  editForm.value = { ...transaction }
  showEditModal.value = true
}

const closeEditModal = () => {
  showEditModal.value = false
  editForm.value = {
    id: '',
    transaction_number: '',
    transaction_date: '',
    supplier_code: '',
    supplier_name: '',
    sku_code: '',
    sku_description: '',
    quantity: '',
    unit_price: '',
    location_code: '',
    location_name: '',
    remarks: ''
  }
}

const saveEdit = async () => {
  if (!isEditFormValid.value) {
    error('Please fill in all required fields')
    return
  }

  isSaving.value = true
  try {
    const response = await axios.put(`/api/rc-rt/transactions/${editForm.value.id}`, {
      ...editForm.value,
      total_amount: editTotalAmount.value
    })

    if (response.data.success) {
      success('Transaction updated successfully')
      closeEditModal()
      loadTransactions()
    } else {
      error(response.data.message || 'Failed to update transaction')
    }
  } catch (err) {
    error('Error updating transaction: ' + err.message)
  } finally {
    isSaving.value = false
  }
}

const updateTransaction = async () => {
  await saveEdit()
}

const postTransaction = async () => {
  if (!canPost.value) {
    error('Cannot post this transaction')
    return
  }

  isPosting.value = true
  try {
    const response = await axios.post(`/api/rc-rt/transactions/${editForm.value.id}/post`)

    if (response.data.success) {
      success('Transaction posted successfully')
      closeEditModal()
      loadTransactions()
    } else {
      error(response.data.message || 'Failed to post transaction')
    }
  } catch (err) {
    error('Error posting transaction: ' + err.message)
  } finally {
    isPosting.value = false
  }
}

const cancelTransaction = async () => {
  if (!canCancel.value) {
    error('Cannot cancel this transaction')
    return
  }

  isCancelling.value = true
  try {
    const response = await axios.post(`/api/rc-rt/transactions/${editForm.value.id}/cancel`)

    if (response.data.success) {
      success('Transaction cancelled successfully')
      closeEditModal()
      loadTransactions()
    } else {
      error(response.data.message || 'Failed to cancel transaction')
    }
  } catch (err) {
    error('Error cancelling transaction: ' + err.message)
  } finally {
    isCancelling.value = false
  }
}

const viewTransaction = (transaction) => {
  // Navigate to view page
  window.location.href = `/material-management/inventory-control/rc-rt/view-print-rc-log?id=${transaction.id}`
}

const printTransaction = (transaction) => {
  // Open print window
  const printWindow = window.open('', '_blank')
  printWindow.document.write(`
    <html>
      <head>
        <title>RC Transaction - ${transaction.transaction_number}</title>
        <style>
          body { font-family: Arial, sans-serif; margin: 20px; }
          .header { text-align: center; margin-bottom: 30px; }
          .section { margin-bottom: 20px; }
          .field { margin-bottom: 10px; }
          .label { font-weight: bold; }
          table { width: 100%; border-collapse: collapse; }
          th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
          th { background-color: #f2f2f2; }
        </style>
      </head>
      <body>
        <div class="header">
          <h1>Receipt Transaction (RC)</h1>
          <h2>${transaction.transaction_number}</h2>
        </div>
        
        <div class="section">
          <h3>Transaction Details</h3>
          <div class="field">
            <span class="label">Date:</span> ${transaction.transaction_date}
          </div>
          <div class="field">
            <span class="label">Status:</span> ${transaction.status}
          </div>
        </div>
        
        <div class="section">
          <h3>Supplier Information</h3>
          <div class="field">
            <span class="label">Code:</span> ${transaction.supplier_code}
          </div>
          <div class="field">
            <span class="label">Name:</span> ${transaction.supplier_name}
          </div>
        </div>
        
        <div class="section">
          <h3>Item Information</h3>
          <div class="field">
            <span class="label">SKU:</span> ${transaction.sku_code}
          </div>
          <div class="field">
            <span class="label">Description:</span> ${transaction.sku_description}
          </div>
          <div class="field">
            <span class="label">Quantity:</span> ${transaction.quantity} ${transaction.uom}
          </div>
          <div class="field">
            <span class="label">Unit Price:</span> ${formatCurrency(transaction.unit_price)}
          </div>
          <div class="field">
            <span class="label">Total Amount:</span> ${formatCurrency(transaction.total_amount)}
          </div>
        </div>
        
        <div class="section">
          <h3>Location Information</h3>
          <div class="field">
            <span class="label">Location:</span> ${transaction.location_code} - ${transaction.location_name}
          </div>
        </div>
      </body>
    </html>
  `)
  printWindow.document.close()
  printWindow.print()
}

const deleteTransaction = async (transaction) => {
  if (!confirm('Are you sure you want to delete this transaction?')) {
    return
  }

  try {
    const response = await axios.delete(`/api/rc-rt/transactions/${transaction.id}`)

    if (response.data.success) {
      success('Transaction deleted successfully')
      loadTransactions()
    } else {
      error(response.data.message || 'Failed to delete transaction')
    }
  } catch (err) {
    error('Error deleting transaction: ' + err.message)
  }
}

const exportSearchResults = () => {
  if (transactions.value.length === 0) {
    error('No transactions to export')
    return
  }
  
  // Create CSV export
  const headers = ['Transaction Number', 'Date', 'Supplier', 'SKU', 'Quantity', 'Amount', 'Status']
  const csvContent = [
    headers,
    ...transactions.value.map(t => [
      t.transaction_number,
      t.transaction_date,
      t.supplier_name,
      t.sku_code,
      t.quantity,
      t.total_amount,
      t.status
    ])
  ].map(row => row.join(',')).join('\n')
  
  const blob = new Blob([csvContent], { type: 'text/csv' })
  const url = window.URL.createObjectURL(blob)
  const a = document.createElement('a')
  a.href = url
  a.download = `RC_Transactions_${new Date().toISOString().split('T')[0]}.csv`
  a.click()
  window.URL.revokeObjectURL(url)
  
  success('Transactions exported successfully')
}

const loadTransactions = async () => {
  try {
    const response = await axios.get('/api/rc-rt/rc-transactions')
    if (response.data.success) {
      transactions.value = response.data.data
      updateStatistics()
    }
  } catch (err) {
    console.error('Error loading transactions:', err)
  }
}

const sortBy = (field) => {
  if (sortField.value === field) {
    sortDirection.value = sortDirection.value === 'asc' ? 'desc' : 'asc'
  } else {
    sortField.value = field
    sortDirection.value = 'asc'
  }
}

const previousPage = () => {
  if (currentPage.value > 1) {
    currentPage.value--
  }
}

const nextPage = () => {
  if (currentPage.value < totalPages.value) {
    currentPage.value++
  }
}

const goToPage = (page) => {
  currentPage.value = page
}

const formatCurrency = (amount) => {
  return new Intl.NumberFormat('id-ID', {
    style: 'currency',
    currency: 'IDR'
  }).format(amount || 0)
}

const formatNumber = (number) => {
  return new Intl.NumberFormat('id-ID').format(number)
}

const formatDate = (date) => {
  return new Date(date).toLocaleDateString('id-ID')
}

const getStatusBadgeClass = (status) => {
  switch (status) {
    case 'Draft': return 'bg-yellow-100 text-yellow-800'
    case 'Posted': return 'bg-green-100 text-green-800'
    case 'Cancelled': return 'bg-red-100 text-red-800'
    default: return 'bg-gray-100 text-gray-800'
  }
}

const updateCurrentTime = () => {
  currentTime.value = new Date().toLocaleTimeString('id-ID')
}

// Lifecycle
onMounted(() => {
  loadTransactions()
  updateCurrentTime()
  setInterval(updateCurrentTime, 1000)
})
</script>

<style scoped>
/* Custom scrollbar */
.overflow-x-auto::-webkit-scrollbar {
  height: 6px;
}

.overflow-x-auto::-webkit-scrollbar-track {
  background: #f1f1f1;
  border-radius: 3px;
}

.overflow-x-auto::-webkit-scrollbar-thumb {
  background: #c1c1c1;
  border-radius: 3px;
}

.overflow-x-auto::-webkit-scrollbar-thumb:hover {
  background: #a8a8a8;
}
</style>
