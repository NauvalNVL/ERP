<template>
  <AppLayout title="View & Print SKU Price">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        View & Print SKU Price
      </h2>
    </template>

    <div class="py-6">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg">
          <!-- Header with buttons -->
          <div class="bg-gradient-to-r from-blue-600 to-blue-800 p-4 flex items-center justify-between">
            <div class="flex items-center space-x-4">
              <h2 class="text-lg font-bold text-white">View & Print SKU Price</h2>
              <div class="flex space-x-2">
                <button 
                  @click="proceed"
                  :disabled="loading"
                  class="bg-green-500 hover:bg-green-400 text-white p-2 rounded-full disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
                  title="Proceed"
                >
                  <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                  </svg>
                </button>
                <button 
                  @click="refreshData"
                  class="bg-green-500 hover:bg-green-400 text-white p-2 rounded-full transition-colors"
                  title="Refresh"
                >
                  <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                  </svg>
                </button>
              </div>
            </div>
            <div class="flex space-x-2">
              <div class="relative" ref="printDropdownContainer">
                <button @click="printDropdownOpen = !printDropdownOpen" class="bg-blue-500 hover:bg-blue-400 text-white px-3 py-1 rounded text-sm flex items-center transition-colors">
                  <i class="fas fa-print mr-2"></i>
                  <span>Print/Export</span>
                  <i class="fas fa-chevron-down ml-2 transition-transform" :class="{'rotate-180': printDropdownOpen}"></i>
                </button>
                <transition
                  enter-active-class="transition ease-out duration-200"
                  enter-from-class="transform opacity-0 scale-95"
                  enter-to-class="transform opacity-100 scale-100"
                  leave-active-class="transition ease-in duration-75"
                  leave-from-class="transform opacity-100 scale-100"
                  leave-to-class="transform opacity-0 scale-95"
                >
                  <div v-if="printDropdownOpen" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg z-20 border border-gray-200">
                    <a @click.prevent="printAsPdf" href="#" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors">
                      <i class="fas fa-file-pdf mr-2 text-red-500"></i> Export as PDF
                    </a>
                    <a @click.prevent="exportAsCsv" href="#" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors">
                      <i class="fas fa-file-csv mr-2 text-green-500"></i> Export as CSV
                    </a>
                  </div>
                </transition>
              </div>
              <Link :href="defineRoute" class="bg-gray-600 hover:bg-gray-500 text-white px-3 py-1 rounded text-sm flex items-center transition-colors">
                <i class="fas fa-arrow-left mr-1"></i>
                Back
              </Link>
            </div>
          </div>

          <!-- Main content -->
          <div class="p-6">
            <!-- Loading Spinner -->
            <div v-if="loading" class="flex justify-center items-center py-8">
              <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-blue-500"></div>
              <span class="ml-3 text-gray-600">Loading...</span>
            </div>
            
            <div v-else>
              <!-- Search and Filters -->
              <div class="mb-6 bg-gray-50 p-6 rounded-lg border border-gray-200 print:hidden">
                <div class="space-y-6">
                  <!-- Row 1: SKU# and Category -->
                  <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- SKU# Range -->
                    <div class="flex items-center space-x-3">
                      <label class="text-sm font-medium text-gray-700 w-20">SKU#:</label>
                      <div class="flex-1 flex space-x-2">
                        <div class="flex-1 flex space-x-1">
                          <input 
                            v-model="filters.skuFrom" 
                            type="text" 
                            placeholder="From"
                            class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm bg-white"
                          />
                          <button 
                            @click="showSkuLookupModal = true"
                            type="button"
                            class="px-3 py-2 border border-gray-300 rounded-md bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors"
                            title="Lookup SKU"
                          >
                            <svg class="h-4 w-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                          </button>
                        </div>
                        <span class="text-gray-500 flex items-center">to</span>
                        <div class="flex-1 flex space-x-1">
                          <input 
                            v-model="filters.skuTo" 
                            type="text" 
                            placeholder="To"
                            class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm"
                          />
                          <button 
                            @click="showSkuLookupModal = true"
                            type="button"
                            class="px-3 py-2 border border-gray-300 rounded-md bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            title="Lookup SKU"
                          >
                            <svg class="h-4 w-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                          </button>
                        </div>
                      </div>
                    </div>

                    <!-- Category Range -->
                    <div class="flex items-center space-x-3">
                      <label class="text-sm font-medium text-gray-700 w-24">to Category:</label>
                      <div class="flex-1 flex space-x-2">
                        <div class="flex-1 flex space-x-1">
                          <input 
                            v-model="filters.categoryFrom" 
                            type="text" 
                            placeholder="From"
                            class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm"
                          />
                          <button 
                            @click="showCategoryLookupModal = true"
                            type="button"
                            class="px-3 py-2 border border-gray-300 rounded-md bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            title="Lookup Category"
                          >
                            <svg class="h-4 w-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                          </button>
                        </div>
                        <span class="text-gray-500 flex items-center">to</span>
                        <div class="flex-1 flex space-x-1">
                          <input 
                            v-model="filters.categoryTo" 
                            type="text" 
                            placeholder="To"
                            class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm"
                          />
                          <button 
                            @click="showCategoryLookupModal = true"
                            type="button"
                            class="px-3 py-2 border border-gray-300 rounded-md bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            title="Lookup Category"
                          >
                            <svg class="h-4 w-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                          </button>
                        </div>
                      </div>
                    </div>
                  </div>

                  <!-- Row 2: Currency and Price Date -->
                  <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Currency -->
                    <div class="flex items-center space-x-3">
                      <label class="text-sm font-medium text-gray-700 w-20">Currency:</label>
                      <div class="flex-1 flex space-x-1">
                        <input 
                          v-model="filters.currency" 
                          type="text" 
                          placeholder="Select Currency"
                          @click="showCurrencyModal = true"
                          readonly
                          class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm cursor-pointer bg-white"
                        />
                        <button 
                          @click="showCurrencyModal = true"
                          type="button"
                          class="px-3 py-2 border border-gray-300 rounded-md bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500"
                          title="Lookup Currency"
                        >
                          <svg class="h-4 w-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                          </svg>
                        </button>
                      </div>
                    </div>

                    <!-- Price Date Range -->
                    <div class="flex items-center space-x-3">
                      <label class="text-sm font-medium text-gray-700 w-24">Price Date:</label>
                      <div class="flex-1 flex space-x-2">
                        <div class="flex-1 flex space-x-1">
                          <input 
                            v-model="filters.priceDateFrom" 
                            type="date" 
                            class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm bg-white"
                          />
                          <button 
                            @click="showDatePickerModal = true"
                            type="button"
                            class="px-3 py-2 border border-gray-300 rounded-md bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            title="Date Picker"
                          >
                            <svg class="h-4 w-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                          </button>
                        </div>
                        <span class="text-gray-500 flex items-center">to</span>
                        <div class="flex-1 flex space-x-1">
                          <input 
                            v-model="filters.priceDateTo" 
                            type="date" 
                            class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm"
                          />
                          <button 
                            @click="showDatePickerModal = true"
                            type="button"
                            class="px-3 py-2 border border-gray-300 rounded-md bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            title="Date Picker"
                          >
                            <svg class="h-4 w-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                          </button>
                        </div>
                      </div>
                    </div>
                  </div>

                  <!-- Row 3: Checkboxes -->
                  <div class="space-y-4">
                    <!-- SKU Type -->
                    <div class="flex items-center space-x-6">
                      <label class="text-sm font-medium text-gray-700 w-20">SKU Type:</label>
                      <div class="flex space-x-6">
                        <label class="flex items-center">
                          <input 
                            v-model="filters.skuTypes" 
                            type="checkbox" 
                            value="S"
                            class="mr-2 rounded border-gray-300 text-blue-600 focus:ring-blue-500 focus:ring-2"
                          />
                          <span class="text-sm text-gray-700">1-Stock Item</span>
                        </label>
                        <label class="flex items-center">
                          <input 
                            v-model="filters.skuTypes" 
                            type="checkbox" 
                            value="NS"
                            class="mr-2 rounded border-gray-300 text-blue-600 focus:ring-blue-500 focus:ring-2"
                          />
                          <span class="text-sm text-gray-700">2-Non Stock Item</span>
                        </label>
                      </div>
                    </div>

                    <!-- P/O Item Status -->
                    <div class="flex items-center space-x-6">
                      <label class="text-sm font-medium text-gray-700 w-32">P/O Item Status:</label>
                      <div class="flex space-x-4">
                        <label class="flex items-center">
                          <input 
                            v-model="filters.poStatus" 
                            type="checkbox" 
                            value="0"
                            class="mr-2 rounded border-gray-300 text-blue-600 focus:ring-blue-500 focus:ring-2"
                          />
                          <span class="text-sm text-gray-700">0-Outstanding</span>
                        </label>
                        <label class="flex items-center">
                          <input 
                            v-model="filters.poStatus" 
                            type="checkbox" 
                            value="P"
                            class="mr-2 rounded border-gray-300 text-blue-600 focus:ring-blue-500 focus:ring-2"
                          />
                          <span class="text-sm text-gray-700">P-Partial</span>
                        </label>
                        <label class="flex items-center">
                          <input 
                            v-model="filters.poStatus" 
                            type="checkbox" 
                            value="C"
                            class="mr-2 rounded border-gray-300 text-blue-600 focus:ring-blue-500 focus:ring-2"
                          />
                          <span class="text-sm text-gray-700">C-Completed</span>
                        </label>
                        <label class="flex items-center">
                          <input 
                            v-model="filters.poStatus" 
                            type="checkbox" 
                            value="M"
                            class="mr-2 rounded border-gray-300 text-blue-600 focus:ring-blue-500 focus:ring-2"
                          />
                          <span class="text-sm text-gray-700">M-Closed</span>
                        </label>
                        <label class="flex items-center">
                          <input 
                            v-model="filters.poStatus" 
                            type="checkbox" 
                            value="X"
                            class="mr-2 rounded border-gray-300 text-blue-600 focus:ring-blue-500 focus:ring-2"
                          />
                          <span class="text-sm text-gray-700">X-Cancelled</span>
                        </label>
                      </div>
                    </div>

                    <!-- SKU Status -->
                    <div class="flex items-center space-x-6">
                      <label class="text-sm font-medium text-gray-700 w-20">SKU Status:</label>
                      <div class="flex space-x-6">
                        <label class="flex items-center">
                          <input 
                            v-model="filters.skuStatus" 
                            type="checkbox" 
                            value="A"
                            class="mr-2 rounded border-gray-300 text-blue-600 focus:ring-blue-500 focus:ring-2"
                          />
                          <span class="text-sm text-gray-700">A-Active</span>
                        </label>
                        <label class="flex items-center">
                          <input 
                            v-model="filters.skuStatus" 
                            type="checkbox" 
                            value="O"
                            class="mr-2 rounded border-gray-300 text-blue-600 focus:ring-blue-500 focus:ring-2"
                          />
                          <span class="text-sm text-gray-700">0-Obsolete</span>
                        </label>
                      </div>
                    </div>

                    <!-- Price History -->
                    <div class="flex items-center space-x-6">
                      <label class="text-sm font-medium text-gray-700 w-24">Price History:</label>
                      <div class="flex space-x-6">
                        <label class="flex items-center">
                          <input 
                            v-model="filters.priceHistory" 
                            type="checkbox" 
                            value="S"
                            class="mr-2 rounded border-gray-300 text-blue-600 focus:ring-blue-500 focus:ring-2"
                          />
                          <span class="text-sm text-gray-700">S-SKU</span>
                        </label>
                        <label class="flex items-center">
                          <input 
                            v-model="filters.priceHistory" 
                            type="checkbox" 
                            value="P"
                            class="mr-2 rounded border-gray-300 text-blue-600 focus:ring-blue-500 focus:ring-2"
                          />
                          <span class="text-sm text-gray-700">P-P/Order</span>
                        </label>
                      </div>
                    </div>

                    <!-- Sorting Method -->
                    <div class="flex items-center space-x-6">
                      <label class="text-sm font-medium text-gray-700 w-28">Sorting Method:</label>
                      <div class="flex space-x-6">
                        <label class="flex items-center">
                          <input 
                            v-model="filters.sortBy" 
                            type="radio" 
                            value="category_sku_currency"
                            class="mr-2 border-gray-300 text-blue-600 focus:ring-blue-500 focus:ring-2"
                          />
                          <span class="text-sm text-gray-700">1-Category + SKU# + Currency</span>
                        </label>
                        <label class="flex items-center">
                          <input 
                            v-model="filters.sortBy" 
                            type="radio" 
                            value="sku_currency"
                            class="mr-2 border-gray-300 text-blue-600 focus:ring-blue-500 focus:ring-2"
                          />
                          <span class="text-sm text-gray-700">2-SKU# + Currency</span>
                        </label>
                      </div>
                    </div>
                  </div>

                  <!-- Proceed Button -->
                  <div class="flex justify-start pt-4">
                    <button 
                      @click="proceed"
                      :disabled="loading"
                      class="bg-gray-600 hover:bg-gray-500 text-white px-6 py-3 rounded text-sm font-medium disabled:opacity-50 disabled:cursor-not-allowed transition-colors shadow-sm"
                    >
                      <svg v-if="loading" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                      </svg>
                      {{ loading ? 'Loading...' : 'Proceed' }}
                    </button>
                  </div>
                </div>
              </div>
              
              <!-- Report header for print -->
              <div class="hidden print:block mb-6">
                <div class="text-center">
                  <h1 class="text-2xl font-bold text-center print:text-3xl">SKU Price Report</h1>
                  <p class="text-center text-gray-600 print:text-lg">Generated on {{ formattedDate }}</p>
                </div>
              </div>
              
              <!-- Table section -->
              <div v-if="showResults" class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 border">
                  <thead class="bg-gray-100">
                    <tr>
                      <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-r">SKU#</th>
                      <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-r">STS</th>
                      <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-r">SKU Name</th>
                      <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-r">Category</th>
                      <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-r">Type</th>
                      <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-r">UOM</th>
                      <th scope="col" class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider border-r">BOH</th>
                      <th scope="col" class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider border-r">FPO</th>
                      <th scope="col" class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider border-r">ROL</th>
                      <th scope="col" class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider border-r">Currency</th>
                      <th scope="col" class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider border-r">Price</th>
                      <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-r">Effective Date</th>
                      <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    </tr>
                  </thead>
                  <tbody class="bg-white divide-y divide-gray-200">
                    <tr v-if="filteredData.length === 0" class="hover:bg-gray-50">
                      <td colspan="13" class="px-4 py-4 text-center text-sm text-gray-500">No data found.</td>
                    </tr>
                    <tr v-for="item in filteredData" :key="item.id" class="hover:bg-gray-50">
                      <td class="px-4 py-2 text-sm font-medium text-gray-900 border-r">{{ item.sku }}</td>
                      <td class="px-4 py-2 text-sm text-gray-900 border-r">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full" :class="item.is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'">
                          {{ item.is_active ? 'ACT' : 'OBS' }}
                        </span>
                      </td>
                      <td class="px-4 py-2 text-sm text-gray-900 border-r">{{ item.sku_name }}</td>
                      <td class="px-4 py-2 text-sm text-gray-900 border-r">{{ item.category_code }}</td>
                      <td class="px-4 py-2 text-sm text-gray-900 border-r">{{ item.type }}</td>
                      <td class="px-4 py-2 text-sm text-gray-900 border-r">{{ item.uom }}</td>
                      <td class="px-4 py-2 text-sm text-gray-900 border-r text-right">{{ formatNumber(item.boh) }}</td>
                      <td class="px-4 py-2 text-sm text-gray-900 border-r text-right">{{ formatNumber(item.fpo) }}</td>
                      <td class="px-4 py-2 text-sm text-gray-900 border-r text-right">{{ formatNumber(item.rol) }}</td>
                      <td class="px-4 py-2 text-sm text-gray-900 border-r text-right">{{ item.currency_code || 'IDR' }}</td>
                      <td class="px-4 py-2 text-sm text-gray-900 border-r text-right">{{ formatCurrency(item.price) }}</td>
                      <td class="px-4 py-2 text-sm text-gray-900 border-r text-left">{{ formatDate(item.effective_date) }}</td>
                      <td class="px-4 py-2 text-sm text-gray-900">
                        <span :class="item.is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'" class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full">
                          {{ item.is_active ? 'Active' : 'Inactive' }}
                        </span>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>

              <!-- Summary -->
              <div v-if="showResults" class="mt-4 px-4 py-3 border-t border-gray-200 bg-gray-50">
                <div class="flex justify-between items-center">
                  <div class="text-sm text-gray-700">
                    <span class="font-medium">Total SKUs:</span> {{ filteredData.length }}
                  </div>
                  <div class="text-sm text-gray-700">
                    <span class="font-medium">Generated:</span> {{ new Date().toLocaleString() }}
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Currency Selection Modal -->
    <div v-if="showCurrencyModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 modal-overlay">
      <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white modal-content">
        <div class="mt-3">
          <h3 class="text-lg font-medium text-gray-900 mb-4">Foreign Currency Table</h3>
          <div class="max-h-60 overflow-y-auto">
            <div 
              v-for="currency in currencies" 
              :key="currency.currency_code"
              @click="selectCurrency(currency)"
              class="p-3 border-b border-gray-200 hover:bg-gray-50 cursor-pointer"
            >
              <div class="font-medium">{{ currency.currency_code }}</div>
              <div class="text-sm text-gray-500">{{ currency.country }}</div>
              <div class="text-sm text-gray-500">Exchange Rate: {{ formatNumber(currency.exchange_rate) }}</div>
            </div>
          </div>
          <div class="mt-4 flex justify-end space-x-2">
            <button 
              @click="selectCurrency(null)"
              class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700"
            >
              Select
            </button>
            <button 
              @click="showCurrencyModal = false"
              class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400"
            >
              Exit
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- SKU Lookup Modal -->
    <div v-if="showSkuLookupModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 modal-overlay">
      <div class="relative top-20 mx-auto p-5 border w-4/5 max-w-4xl shadow-lg rounded-md bg-white modal-content">
        <div class="mt-3">
          <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-medium text-gray-900">SKU Lookup</h3>
            <button @click="closeSkuModal" class="text-gray-400 hover:text-gray-600">
              <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>
          </div>
          
          <!-- Search Bar -->
          <div class="mb-4">
            <input 
              v-model="skuSearchTerm" 
              type="text" 
              placeholder="Search SKU..."
              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
            />
          </div>
          
          <!-- SKU Table -->
          <div class="max-h-96 overflow-y-auto">
            <table class="min-w-full divide-y divide-gray-200">
              <thead class="bg-gray-50">
                <tr>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">SKU#</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">SKU Name</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                <tr v-for="sku in filteredSkuList" :key="sku.sku" class="hover:bg-gray-50">
                  <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ sku.sku }}</td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ sku.sku_name }}</td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ sku.category_code }}</td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ sku.type }}</td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <span :class="sku.is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'" class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full">
                      {{ sku.is_active ? 'Active' : 'Inactive' }}
                    </span>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    <button 
                      @click="selectSku(sku)"
                      class="text-blue-600 hover:text-blue-900"
                    >
                      Select
                    </button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
          
          <div class="mt-4 flex justify-end">
            <button 
              @click="closeSkuModal"
              class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400"
            >
              Close
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Category Lookup Modal -->
    <div v-if="showCategoryLookupModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 modal-overlay">
      <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white modal-content">
        <div class="mt-3">
          <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-medium text-gray-900">Category Lookup</h3>
            <button @click="closeCategoryModal" class="text-gray-400 hover:text-gray-600">
              <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>
          </div>
          
          <!-- Search Bar -->
          <div class="mb-4">
            <input 
              v-model="categorySearchTerm" 
              type="text" 
              placeholder="Search Category..."
              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
            />
          </div>
          
          <!-- Category List -->
          <div class="max-h-60 overflow-y-auto">
            <div 
              v-for="category in filteredCategoryList" 
              :key="category.code"
              @click="selectCategory(category)"
              class="p-3 border-b border-gray-200 hover:bg-gray-50 cursor-pointer"
            >
              <div class="font-medium">{{ category.code }}</div>
              <div class="text-sm text-gray-500">{{ category.name }}</div>
            </div>
          </div>
          
          <div class="mt-4 flex justify-end">
            <button 
              @click="closeCategoryModal"
              class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400"
            >
              Close
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Date Picker Modal -->
    <div v-if="showDatePickerModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 modal-overlay">
      <div class="relative top-20 mx-auto p-5 border w-80 shadow-lg rounded-md bg-white modal-content">
        <div class="mt-3">
          <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-medium text-gray-900">Date Table</h3>
            <button @click="closeDatePickerModal" class="text-gray-400 hover:text-gray-600">
              <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>
          </div>
          
          <!-- Calendar Navigation -->
          <div class="flex justify-between items-center mb-4">
            <button @click="previousYear" class="p-1 hover:bg-gray-100 rounded">
              <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7" />
              </svg>
            </button>
            <button @click="previousMonth" class="p-1 hover:bg-gray-100 rounded">
              <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
              </svg>
            </button>
            <span class="text-lg font-medium">{{ currentMonthYear }}</span>
            <button @click="nextMonth" class="p-1 hover:bg-gray-100 rounded">
              <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
              </svg>
            </button>
            <button @click="nextYear" class="p-1 hover:bg-gray-100 rounded">
              <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7m-8 0l7-7-7-7" />
              </svg>
            </button>
          </div>
          
          <!-- Calendar Grid -->
          <div class="grid grid-cols-7 gap-1">
            <div class="text-center text-xs font-medium text-gray-500 p-2">Mon</div>
            <div class="text-center text-xs font-medium text-gray-500 p-2">Tue</div>
            <div class="text-center text-xs font-medium text-gray-500 p-2">Wed</div>
            <div class="text-center text-xs font-medium text-gray-500 p-2">Thu</div>
            <div class="text-center text-xs font-medium text-gray-500 p-2">Fri</div>
            <div class="text-center text-xs font-medium text-red-500 p-2">Sat</div>
            <div class="text-center text-xs font-medium text-red-500 p-2">Sun</div>
            
            <template v-for="day in calendarDays" :key="day.date">
              <div 
                v-if="day.isCurrentMonth"
                @click="selectDate(day.date)"
                :class="[
                  'text-center p-2 cursor-pointer hover:bg-blue-100 rounded',
                  day.isSelected ? 'bg-blue-500 text-white' : 'text-gray-700',
                  day.isToday ? 'font-bold' : ''
                ]"
              >
                {{ day.day }}
              </div>
              <div v-else class="text-center p-2 text-gray-300">
                {{ day.day }}
              </div>
            </template>
          </div>
          
          <div class="mt-4 flex justify-end space-x-2">
            <button 
              @click="selectDate(new Date())"
              class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700"
            >
              Today
            </button>
            <button 
              @click="closeDatePickerModal"
              class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400"
            >
              Close
            </button>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue';
import { Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import axios from 'axios';
import jsPDF from 'jspdf';
import autoTable from 'jspdf-autotable';
import 'jspdf-autotable';
import { useToast } from '@/Composables/useToast';

const rows = ref([]);
const currencies = ref([]);
const skuList = ref([]);
const categoryList = ref([]);
const loading = ref(false);
const showResults = ref(false);
const showCurrencyModal = ref(false);
const showSkuLookupModal = ref(false);
const showCategoryLookupModal = ref(false);
const showDatePickerModal = ref(false);
const printDropdownOpen = ref(false);
const printDropdownContainer = ref(null);
const toast = useToast();

// Search terms for modals
const skuSearchTerm = ref('');
const categorySearchTerm = ref('');

// Date picker variables
const currentDate = ref(new Date());
const selectedDate = ref(null);

const defineRoute = '/material-management/system-requirement/inventory-setup/sku-price';

// Filters matching desktop version
const filters = ref({
  skuFrom: '',
  skuTo: '',
  categoryFrom: '',
  categoryTo: '',
  currency: '',
  priceDateFrom: '',
  priceDateTo: '',
  skuTypes: ['S', 'NS'], // Default both checked
  skuStatus: ['A', 'O'], // Default both checked
  priceHistory: ['S', 'P'], // Default both checked
  poStatus: ['0', 'P', 'C', 'M', 'X'], // Default all checked
  sortBy: 'category_sku_currency' // Default sort
});

const filteredData = computed(() => {
  let filtered = [...rows.value];

  // Apply SKU range filter
  if (filters.value.skuFrom || filters.value.skuTo) {
    filtered = filtered.filter(item => {
      const sku = item.sku || '';
      const from = filters.value.skuFrom || '';
      const to = filters.value.skuTo || '';
      
      if (from && to) {
        return sku >= from && sku <= to;
      } else if (from) {
        return sku >= from;
      } else if (to) {
        return sku <= to;
      }
      return true;
    });
  }
  
  // Apply category range filter
  if (filters.value.categoryFrom || filters.value.categoryTo) {
    filtered = filtered.filter(item => {
      const category = item.category_code || '';
      const from = filters.value.categoryFrom || '';
      const to = filters.value.categoryTo || '';
      
      if (from && to) {
        return category >= from && category <= to;
      } else if (from) {
        return category >= from;
      } else if (to) {
        return category <= to;
      }
      return true;
    });
  }
  
  // Apply currency filter
  if (filters.value.currency) {
    filtered = filtered.filter(item => item.currency_code === filters.value.currency);
  }
  
  // Apply price date range filter
  if (filters.value.priceDateFrom || filters.value.priceDateTo) {
    filtered = filtered.filter(item => {
      const date = item.effective_date;
      const from = filters.value.priceDateFrom;
      const to = filters.value.priceDateTo;
      
      if (from && to) {
        return date >= from && date <= to;
      } else if (from) {
        return date >= from;
      } else if (to) {
        return date <= to;
      }
      return true;
    });
  }
  
  // Apply SKU type filter
  if (filters.value.skuTypes.length > 0) {
    filtered = filtered.filter(item => filters.value.skuTypes.includes(item.type));
  }
  
  // Apply SKU status filter
  if (filters.value.skuStatus.length > 0) {
    filtered = filtered.filter(item => {
      const isActive = item.is_active;
      return filters.value.skuStatus.includes(isActive ? 'A' : 'O');
    });
  }
  
  // Apply price history filter
  if (filters.value.priceHistory.length > 0) {
    filtered = filtered.filter(item => filters.value.priceHistory.includes(item.price_type));
  }
  
  // Apply P/O status filter
  if (filters.value.poStatus.length > 0) {
    filtered = filtered.filter(item => filters.value.poStatus.includes(item.po_status));
  }
  
  // Apply sorting
  filtered.sort((a, b) => {
    if (filters.value.sortBy === 'category_sku_currency') {
      if (a.category_code !== b.category_code) {
        return a.category_code.localeCompare(b.category_code);
      }
      if (a.sku !== b.sku) {
        return a.sku.localeCompare(b.sku);
      }
      return (a.currency_code || 'IDR').localeCompare(b.currency_code || 'IDR');
    } else {
      if (a.sku !== b.sku) {
        return a.sku.localeCompare(b.sku);
      }
      return (a.currency_code || 'IDR').localeCompare(b.currency_code || 'IDR');
    }
  });
  
  return filtered;
});

const formattedDate = computed(() => {
  const now = new Date();
  return new Intl.DateTimeFormat('en-US', {
    year: 'numeric', month: 'long', day: 'numeric',
    hour: '2-digit', minute: '2-digit'
  }).format(now);
});

// Computed properties for modal filters
const filteredSkuList = computed(() => {
  if (!skuSearchTerm.value) return skuList.value;
  return skuList.value.filter(sku => 
    sku.sku.toLowerCase().includes(skuSearchTerm.value.toLowerCase()) ||
    sku.sku_name.toLowerCase().includes(skuSearchTerm.value.toLowerCase()) ||
    sku.category_code.toLowerCase().includes(skuSearchTerm.value.toLowerCase())
  );
});

const filteredCategoryList = computed(() => {
  if (!categorySearchTerm.value) return categoryList.value;
  return categoryList.value.filter(category => 
    category.code.toLowerCase().includes(categorySearchTerm.value.toLowerCase()) ||
    category.name.toLowerCase().includes(categorySearchTerm.value.toLowerCase())
  );
});

// Date picker computed properties
const currentMonthYear = computed(() => {
  return new Intl.DateTimeFormat('en-US', { 
    year: 'numeric', 
    month: 'long' 
  }).format(currentDate.value);
});

const calendarDays = computed(() => {
  const year = currentDate.value.getFullYear();
  const month = currentDate.value.getMonth();
  const firstDay = new Date(year, month, 1);
  const lastDay = new Date(year, month + 1, 0);
  const startDate = new Date(firstDay);
  startDate.setDate(startDate.getDate() - firstDay.getDay() + 1); // Start from Monday
  
  const days = [];
  const today = new Date();
  
  for (let i = 0; i < 42; i++) {
    const date = new Date(startDate);
    date.setDate(startDate.getDate() + i);
    
    days.push({
      date: date,
      day: date.getDate(),
      isCurrentMonth: date.getMonth() === month,
      isToday: date.toDateString() === today.toDateString(),
      isSelected: selectedDate.value && date.toDateString() === selectedDate.value.toDateString()
    });
  }
  
  return days;
});

const loadData = async () => {
  loading.value = true;
  try {
    // Build query parameters from filters
    const params = new URLSearchParams();
    
    if (filters.value.skuFrom) params.append('sku_from', filters.value.skuFrom);
    if (filters.value.skuTo) params.append('sku_to', filters.value.skuTo);
    if (filters.value.categoryFrom) params.append('category_from', filters.value.categoryFrom);
    if (filters.value.categoryTo) params.append('category_to', filters.value.categoryTo);
    if (filters.value.currency) params.append('currency', filters.value.currency);
    if (filters.value.priceDateFrom) params.append('price_date_from', filters.value.priceDateFrom);
    if (filters.value.priceDateTo) params.append('price_date_to', filters.value.priceDateTo);
    if (filters.value.skuTypes.length > 0) {
      filters.value.skuTypes.forEach(type => params.append('sku_types[]', type));
    }
    if (filters.value.skuStatus.length > 0) {
      filters.value.skuStatus.forEach(status => params.append('sku_status[]', status));
    }
    if (filters.value.priceHistory.length > 0) {
      filters.value.priceHistory.forEach(history => params.append('price_history[]', history));
    }
    if (filters.value.poStatus.length > 0) {
      filters.value.poStatus.forEach(status => params.append('po_status[]', status));
    }
    params.append('sort_by', filters.value.sortBy);

    const [skuPricesResponse, currencyResponse, skuResponse, categoryResponse] = await Promise.all([
      axios.get(`/api/sku-prices/for-print?${params.toString()}`),
      axios.get('/api/foreign-currencies'),
      axios.get('/api/material-management/skus'),
      axios.get('/api/material-management/categories')
    ]);
    
    rows.value = skuPricesResponse.data;
    currencies.value = currencyResponse.data;
    skuList.value = skuResponse.data;
    categoryList.value = categoryResponse.data;
  } catch (error) {
    console.error('Error fetching data:', error);
    toast.error('Failed to load data. Please try again.');
  } finally {
    loading.value = false;
  }
};

const formatNumber = (value) => {
  if (value === null || value === undefined) return '0.000';
  return parseFloat(value).toFixed(3);
};

const formatCurrency = (value) => {
  return new Intl.NumberFormat('id-ID', {
    style: 'currency',
    currency: 'IDR',
    minimumFractionDigits: 2
  }).format(value || 0);
};

const formatDate = (date) => {
  if (!date) return '';
  return new Date(date).toLocaleDateString('id-ID');
};

const proceed = async () => {
  loading.value = true;
  try {
    await loadData();
    showResults.value = true;
    toast.success('Data loaded successfully');
  } catch (error) {
    console.error('Error processing request:', error);
    toast.error('Failed to process request');
  } finally {
    loading.value = false;
  }
};

const refreshData = async () => {
  await loadData();
  toast.success('Data refreshed successfully');
};

const selectCurrency = (currency) => {
  if (currency) {
    filters.value.currency = currency.currency_code;
  } else {
    filters.value.currency = '';
  }
  showCurrencyModal.value = false;
};

const exportAsCsv = () => {
  const headers = [
    'SKU#', 'STS', 'SKU Name', 'Category', 'Type', 'UOM', 
    'BOH', 'FPO', 'ROL', 'Currency', 'Price', 'Effective Date', 'Status'
  ];
  
  const csvContent = [
    headers.join(','),
    ...filteredData.value.map(item => [
      item.sku,
      item.is_active ? 'ACT' : 'OBS',
      `"${item.sku_name}"`,
      item.category_code,
      item.type,
      item.uom,
      formatNumber(item.boh),
      formatNumber(item.fpo),
      formatNumber(item.rol),
      item.currency_code || 'IDR',
      formatCurrency(item.price),
      formatDate(item.effective_date),
      item.is_active ? 'Active' : 'Inactive'
    ].join(','))
  ].join('\n');

  const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
  const link = document.createElement('a');
  const url = URL.createObjectURL(blob);
  link.setAttribute('href', url);
  link.setAttribute('download', `sku_price_report_${new Date().toISOString().slice(0, 10)}.csv`);
  link.style.visibility = 'hidden';
  document.body.appendChild(link);
  link.click();
  document.body.removeChild(link);
  printDropdownOpen.value = false;
};

const printAsPdf = () => {
  try {
    // Create new PDF document
    const doc = new jsPDF('l', 'mm', 'a4'); // Landscape orientation
    
    // Set document properties
    doc.setProperties({
      title: 'SKU Price Report',
      subject: 'SKU Price Listing',
      author: 'PT. Multibox Indah - CPS ENTERPRISE 2020',
      creator: 'ERP System'
    });

    // Add company header
    doc.setFontSize(20);
    doc.setFont('helvetica', 'bold');
    doc.text('PT. Multibox Indah - CPS ENTERPRISE 2020', 148, 20, { align: 'center' });
    
    doc.setFontSize(14);
    doc.setFont('helvetica', 'normal');
    doc.text('SKU Price Report', 148, 30, { align: 'center' });
    
    // Add date and time
    const currentDate = new Date().toLocaleDateString('id-ID');
    const currentTime = new Date().toLocaleTimeString('id-ID');
    doc.setFontSize(10);
    doc.text(`Generated on ${currentDate} at ${currentTime}`, 148, 40, { align: 'center' });
    
    // Add summary information
    doc.setFontSize(11);
    doc.setFont('helvetica', 'bold');
    doc.text('Summary:', 20, 55);
    doc.setFont('helvetica', 'normal');
    doc.text(`Total SKUs: ${rows.value.length}`, 20, 62);
    doc.text(`Showing: ${filteredData.value.length} of ${rows.value.length}`, 20, 69);
    
    // Prepare table data
    const tableData = filteredData.value.map((item, index) => [
      (index + 1).toString(),
      item.sku || '',
      item.is_active ? 'ACT' : 'OBS',
      item.sku_name || '',
      item.category_code || '',
      item.type || '',
      item.uom || '',
      formatNumber(item.boh),
      formatNumber(item.fpo),
      formatNumber(item.rol),
      item.currency_code || 'IDR',
      formatCurrency(item.price),
      formatDate(item.effective_date),
      item.is_active ? 'Active' : 'Inactive'
    ]);
    
    // Add table using autoTable
    autoTable(doc, {
      startY: 80,
      head: [['No.', 'SKU#', 'STS', 'SKU Name', 'Category', 'Type', 'UOM', 'BOH', 'FPO', 'ROL', 'Currency', 'Price', 'Effective Date', 'Status']],
      body: tableData,
      theme: 'grid',
      styles: {
        fontSize: 7,
        cellPadding: 2,
        lineColor: [0, 0, 0],
        lineWidth: 0.1,
        textColor: [0, 0, 0],
      },
      headStyles: {
        fillColor: [79, 70, 229], // Indigo color
        textColor: [255, 255, 255],
        fontStyle: 'bold',
        fontSize: 8,
        halign: 'center',
      },
      columnStyles: {
        0: { cellWidth: 12, halign: 'center', fontStyle: 'bold' }, // No.
        1: { cellWidth: 25, halign: 'center' }, // SKU#
        2: { cellWidth: 15, halign: 'center' }, // STS
        3: { cellWidth: 40, halign: 'left' }, // SKU Name
        4: { cellWidth: 20, halign: 'center' }, // Category
        5: { cellWidth: 15, halign: 'center' }, // Type
        6: { cellWidth: 15, halign: 'center' }, // UOM
        7: { cellWidth: 18, halign: 'right' }, // BOH
        8: { cellWidth: 18, halign: 'right' }, // FPO
        9: { cellWidth: 18, halign: 'right' }, // ROL
        10: { cellWidth: 20, halign: 'center' }, // Currency
        11: { cellWidth: 25, halign: 'right' }, // Price
        12: { cellWidth: 25, halign: 'center' }, // Effective Date
        13: { cellWidth: 20, halign: 'center' }, // Status
      },
      alternateRowStyles: {
        fillColor: [248, 250, 252], // Light gray
      },
      didDrawPage: function (data) {
        // Add page number
        const pageCount = doc.internal.getNumberOfPages();
        doc.setFontSize(8);
        doc.text(`Page ${data.pageNumber} of ${pageCount}`, 148, doc.internal.pageSize.height - 10, { align: 'center' });
      },
      margin: { top: 80, right: 15, bottom: 20, left: 15 },
      tableWidth: 'auto',
      showFoot: 'lastPage',
      footStyles: {
        fillColor: [240, 240, 240],
        textColor: [0, 0, 0],
        fontStyle: 'bold',
        fontSize: 7,
      },
      foot: [[
        { content: `Total: ${filteredData.value.length} records`, colSpan: 14, styles: { halign: 'center' } }
      ]],
    });
    
    // Generate filename with timestamp
    const timestamp = new Date().toISOString().slice(0, 19).replace(/:/g, '-');
    const filename = `sku_price_report_${timestamp}.pdf`;
    
    // Save the PDF
    doc.save(filename);
    
    toast.success('PDF generated successfully');
    printDropdownOpen.value = false;
  } catch (err) {
    console.error('Error generating PDF:', err);
    toast.error('Failed to generate PDF');
  }
};

const handleClickOutside = (event) => {
  if (printDropdownContainer.value && !printDropdownContainer.value.contains(event.target)) {
    printDropdownOpen.value = false;
  }
  
  // Handle modal click outside
  const modals = document.querySelectorAll('.modal-overlay');
  modals.forEach(modal => {
    if (modal.contains(event.target) && !modal.querySelector('.modal-content').contains(event.target)) {
      if (showSkuLookupModal.value) closeSkuModal();
      if (showCategoryLookupModal.value) closeCategoryModal();
      if (showDatePickerModal.value) closeDatePickerModal();
      if (showCurrencyModal.value) showCurrencyModal.value = false;
    }
  });
};

// Modal interaction functions
const selectSku = (sku) => {
  // Determine which field to update based on context
  if (filters.value.skuFrom === '' || filters.value.skuFrom === sku.sku) {
    filters.value.skuFrom = sku.sku;
  } else {
    filters.value.skuTo = sku.sku;
  }
  showSkuLookupModal.value = false;
  skuSearchTerm.value = '';
};

const selectCategory = (category) => {
  // Determine which field to update based on context
  if (filters.value.categoryFrom === '' || filters.value.categoryFrom === category.code) {
    filters.value.categoryFrom = category.code;
  } else {
    filters.value.categoryTo = category.code;
  }
  showCategoryLookupModal.value = false;
  categorySearchTerm.value = '';
};

const selectDate = (date) => {
  selectedDate.value = date;
  const formattedDate = date.toISOString().split('T')[0];
  
  // Determine which field to update based on context
  if (filters.value.priceDateFrom === '' || filters.value.priceDateFrom === formattedDate) {
    filters.value.priceDateFrom = formattedDate;
  } else {
    filters.value.priceDateTo = formattedDate;
  }
  showDatePickerModal.value = false;
};

// Functions to clear search terms when modals are closed
const closeSkuModal = () => {
  showSkuLookupModal.value = false;
  skuSearchTerm.value = '';
};

const closeCategoryModal = () => {
  showCategoryLookupModal.value = false;
  categorySearchTerm.value = '';
};

const closeDatePickerModal = () => {
  showDatePickerModal.value = false;
};

// Calendar navigation functions
const previousMonth = () => {
  currentDate.value = new Date(currentDate.value.getFullYear(), currentDate.value.getMonth() - 1, 1);
};

const nextMonth = () => {
  currentDate.value = new Date(currentDate.value.getFullYear(), currentDate.value.getMonth() + 1, 1);
};

const previousYear = () => {
  currentDate.value = new Date(currentDate.value.getFullYear() - 1, currentDate.value.getMonth(), 1);
};

const nextYear = () => {
  currentDate.value = new Date(currentDate.value.getFullYear() + 1, currentDate.value.getMonth(), 1);
};

onMounted(() => {
  loadData();
  document.addEventListener('mousedown', handleClickOutside);
});

onUnmounted(() => {
  document.removeEventListener('mousedown', handleClickOutside);
});

</script>

<style>
@media print {
  body * {
    visibility: hidden;
  }
  .print-container, .print-container * {
    visibility: visible;
  }
  .print-container {
    position: absolute;
    left: 0;
    top: 0;
    width: 100%;
  }
  .print\:hidden {
    display: none;
  }
  .print\:block {
    display: block;
  }
}

/* Custom styles for desktop ERP look */
.bg-gradient-to-r {
  background: linear-gradient(to right, #1e40af, #1d4ed8);
}

/* Input field styling */
input[type="text"], input[type="date"] {
  background-color: #ffffff;
  border: 1px solid #d1d5db;
  transition: all 0.2s ease-in-out;
}

input[type="text"]:focus, input[type="date"]:focus {
  border-color: #3b82f6;
  box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

/* Button styling */
button {
  transition: all 0.2s ease-in-out;
}

/* Checkbox and radio styling */
input[type="checkbox"], input[type="radio"] {
  accent-color: #3b82f6;
}

/* Table styling */
table {
  border-collapse: collapse;
  width: 100%;
}

th, td {
  border: 1px solid #e5e7eb;
  padding: 8px 12px;
}

th {
  background-color: #f9fafb;
  font-weight: 600;
  text-transform: uppercase;
  font-size: 0.75rem;
  letter-spacing: 0.05em;
}

/* Modal styling */
.modal-overlay {
  backdrop-filter: blur(2px);
}

.modal-content {
  box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
}

/* Status badge styling */
.bg-green-100 {
  background-color: #dcfce7;
}

.bg-red-100 {
  background-color: #fee2e2;
}

.text-green-800 {
  color: #166534;
}

.text-red-800 {
  color: #991b1b;
}

/* Hover effects */
.hover\:bg-gray-50:hover {
  background-color: #f9fafb;
}

.hover\:bg-blue-100:hover {
  background-color: #dbeafe;
}

/* Focus states */
.focus\:ring-2:focus {
  box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.focus\:ring-blue-500:focus {
  --tw-ring-color: #3b82f6;
}

/* Transition effects */
.transition-colors {
  transition-property: color, background-color, border-color, text-decoration-color, fill, stroke;
  transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
  transition-duration: 150ms;
}

.transition-transform {
  transition-property: transform;
  transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
  transition-duration: 150ms;
}
</style> 