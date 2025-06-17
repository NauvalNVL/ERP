<template>
  <AppLayout :header="'View & Print MC Print/DC Block Listing'">
    <Head title="View & Print MC Print/DC Block Listing" />

    <!-- Main Container with vibrant gradient background -->
    <div class="min-h-screen bg-gradient-to-br from-indigo-50 via-purple-50 to-pink-50 p-4 md:p-6">
      <div class="max-w-7xl mx-auto">
        <!-- Header Section with animated elements -->
        <div class="bg-gradient-to-r from-purple-600 via-indigo-600 to-blue-600 rounded-xl shadow-lg overflow-hidden mb-6 transform transition-all duration-500 hover:shadow-xl">
          <div class="relative overflow-hidden">
            <!-- Decorative Elements -->
            <div class="absolute top-0 right-0 w-40 h-40 bg-white opacity-5 rounded-full -translate-y-20 translate-x-20"></div>
            <div class="absolute bottom-0 left-0 w-20 h-20 bg-white opacity-5 rounded-full translate-y-10 -translate-x-10"></div>
            <div class="absolute bottom-0 right-0 w-32 h-32 bg-yellow-400 opacity-5 rounded-full translate-y-10 translate-x-10"></div>

            <div class="p-6 md:p-8 relative z-10">
              <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                <div class="flex items-start md:items-center space-x-4 mb-4 md:mb-0">
                  <div class="bg-gradient-to-br from-pink-500 to-purple-600 p-3 rounded-lg shadow-inner flex items-center justify-center relative overflow-hidden">
                    <div class="absolute -top-1 -right-1 w-6 h-6 bg-yellow-300 opacity-30 rounded-full animate-ping-slow"></div>
                    <div class="absolute -bottom-1 -left-1 w-4 h-4 bg-blue-300 opacity-30 rounded-full animate-ping-slow animation-delay-500"></div>
                    <i class="fas fa-cubes text-white text-2xl z-10 animate-pulse"></i>
                  </div>
                  <div>
                    <h1 class="text-2xl md:text-3xl font-bold text-white text-shadow">MC Print/DC Block Listing</h1>
                    <p class="text-blue-100 max-w-2xl">View and print master card print/diecut block listing</p>
                  </div>
                </div>
                <div class="flex flex-wrap gap-3">
                  <button @click="proceedSearch" class="bg-gradient-to-r from-green-500 to-teal-400 text-white hover:from-green-600 hover:to-teal-500 px-4 py-2 rounded-lg flex items-center transition-all duration-300 transform hover:scale-105 shadow-md relative overflow-hidden">
                    <span class="absolute inset-0 bg-white opacity-20 transform scale-x-0 origin-left transition-transform group-hover:scale-x-100"></span>
                    <div class="relative z-10 flex items-center">
                      <div class="bg-white bg-opacity-30 rounded-full p-1 mr-2">
                        <i class="fas fa-search text-white"></i>
                      </div>
                      <span>Search</span>
                    </div>
                  </button>
                  <button @click="printPage" class="bg-gradient-to-r from-blue-500 to-indigo-400 text-white hover:from-blue-600 hover:to-indigo-500 px-4 py-2 rounded-lg flex items-center transition-all duration-300 transform hover:scale-105 shadow-md relative overflow-hidden">
                    <span class="absolute inset-0 bg-white opacity-20 transform scale-x-0 origin-left transition-transform group-hover:scale-x-100"></span>
                    <div class="relative z-10 flex items-center">
                      <div class="bg-white bg-opacity-30 rounded-full p-1 mr-2">
                        <i class="fas fa-print text-white"></i>
                      </div>
                      <span>Print Report</span>
                    </div>
                  </button>
                                      <Link href="/sales-management/system-requirement/master-card/view-and-print-MC" class="bg-white bg-opacity-20 text-white border border-white border-opacity-30 hover:bg-opacity-30 px-4 py-2 rounded-lg flex items-center transition-all duration-300 relative overflow-hidden">
                      <span class="absolute inset-0 bg-white opacity-10 transform scale-x-0 origin-left transition-transform hover:scale-x-100"></span>
                      <div class="relative z-10 flex items-center">
                        <div class="bg-white bg-opacity-30 rounded-full p-1.5 mr-2 transition-transform transform group-hover:rotate-12 shadow-inner">
                          <i class="fas fa-arrow-left text-white"></i>
                        </div>
                        <span>Back</span>
                      </div>
                    </Link>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Search Form Card -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-6 border border-gray-100">
          <div class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white py-3 px-6">
            <div class="flex items-center">
              <div class="mr-3 bg-white bg-opacity-20 p-2 rounded-full shadow-inner relative overflow-hidden">
                <div class="absolute -top-1 -right-1 w-4 h-4 bg-blue-300 opacity-30 rounded-full"></div>
                <div class="absolute -bottom-1 -left-1 w-3 h-3 bg-yellow-300 opacity-30 rounded-full"></div>
                <i class="fas fa-filter text-xl relative z-10"></i>
              </div>
              <div>
                <h2 class="text-xl font-bold">Search Parameters</h2>
                <p class="text-xs text-blue-100 opacity-80">Filter master cards by various criteria</p>
              </div>
            </div>
          </div>
          <div class="p-6">
          
          <div class="grid grid-cols-1 gap-y-6 gap-x-4 md:grid-cols-2 lg:grid-cols-3">
            <!-- ACN Field -->
            <div class="flex flex-col">
              <label class="block text-sm font-medium text-gray-700 mb-1">ACN:</label>
              <div class="relative flex ml-8">
                <div class="absolute -left-8 top-1/2 transform -translate-y-1/2 flex items-center justify-center w-7 h-7 bg-gradient-to-r from-purple-500 to-indigo-600 rounded-full shadow-md z-10">
                  <i class="fas fa-id-card-alt text-white text-sm animate-pulse-light"></i>
                </div>
                <input
                  v-model="searchParams.acnFrom"
                  type="text"
                  class="rounded-l-md flex-1 min-w-0 block w-full px-3 py-2 border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                  placeholder="From"
                />
                <button @click="showAcnSearch" class="inline-flex items-center px-3 py-2 border border-l-0 border-gray-300 bg-gradient-to-r from-indigo-500 to-purple-600 text-white rounded-r-md hover:from-indigo-600 hover:to-purple-700 transition-colors">
                  <i class="fas fa-search text-white text-sm"></i>
                </button>
              </div>
            </div>
            
            <div class="flex flex-col">
              <label class="block text-sm font-medium text-gray-700 mb-1">to</label>
              <div class="relative flex ml-8">
                <div class="absolute -left-8 top-1/2 transform -translate-y-1/2 flex items-center justify-center w-7 h-7 bg-gradient-to-r from-purple-500 to-indigo-600 rounded-full shadow-md z-10">
                  <i class="fas fa-id-card-alt text-white text-sm animate-pulse-light"></i>
                </div>
                <input
                  v-model="searchParams.acnTo"
                  type="text"
                  class="rounded-l-md flex-1 min-w-0 block w-full px-3 py-2 border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                  placeholder="To"
                />
                <button @click="showAcnSearch" class="inline-flex items-center px-3 py-2 border border-l-0 border-gray-300 bg-gradient-to-r from-indigo-500 to-purple-600 text-white rounded-r-md hover:from-indigo-600 hover:to-purple-700 transition-colors">
                  <i class="fas fa-search text-white text-sm"></i>
                </button>
              </div>
            </div>
            
            <!-- MCS Field -->
            <div class="flex flex-col">
              <label class="block text-sm font-medium text-gray-700 mb-1">MCS#:</label>
              <div class="relative flex ml-8">
                <div class="absolute -left-8 top-1/2 transform -translate-y-1/2 flex items-center justify-center w-7 h-7 bg-gradient-to-r from-purple-500 to-indigo-600 rounded-full shadow-md z-10">
                  <i class="fas fa-clipboard-list text-white text-sm animate-pulse-light"></i>
                </div>
                <input
                  v-model="searchParams.mcsFrom"
                  type="text"
                  class="rounded-l-md flex-1 min-w-0 block w-full px-3 py-2 border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                  placeholder="From"
                />
                <button @click="showMcsSearch" class="inline-flex items-center px-3 py-2 border border-l-0 border-gray-300 bg-gradient-to-r from-indigo-500 to-purple-600 text-white rounded-r-md hover:from-indigo-600 hover:to-purple-700 transition-colors">
                  <i class="fas fa-search text-white text-sm"></i>
                </button>
              </div>
            </div>
            
            <div class="flex flex-col">
              <label class="block text-sm font-medium text-gray-700 mb-1">to</label>
              <div class="relative flex ml-8">
                <div class="absolute -left-8 top-1/2 transform -translate-y-1/2 flex items-center justify-center w-7 h-7 bg-gradient-to-r from-purple-500 to-indigo-600 rounded-full shadow-md z-10">
                  <i class="fas fa-clipboard-list text-white text-sm animate-pulse-light"></i>
                </div>
                <input
                  v-model="searchParams.mcsTo"
                  type="text"
                  class="rounded-l-md flex-1 min-w-0 block w-full px-3 py-2 border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                  placeholder="To"
                />
                <button @click="showMcsSearch" class="inline-flex items-center px-3 py-2 border border-l-0 border-gray-300 bg-gradient-to-r from-indigo-500 to-purple-600 text-white rounded-r-md hover:from-indigo-600 hover:to-purple-700 transition-colors">
                  <i class="fas fa-search text-white text-sm"></i>
                </button>
              </div>
            </div>
            
            <!-- MC Option -->
            <div class="md:col-span-2 lg:col-span-3">
              <label class="block text-sm font-medium text-gray-700 mb-2">MC Option:</label>
              <div class="flex flex-wrap gap-4">
                <div class="flex items-center">
                  <input
                    id="mc-option-1"
                    type="radio"
                    v-model="searchParams.mcOption"
                    value="1"
                    class="h-4 w-4 text-indigo-600 focus:ring-indigo-500"
                  />
                  <label for="mc-option-1" class="ml-2 text-sm text-gray-700">1-M/Card with/without last S/Order#</label>
                </div>
                <div class="flex items-center">
                  <input
                    id="mc-option-2"
                    type="radio"
                    v-model="searchParams.mcOption"
                    value="2"
                    class="h-4 w-4 text-indigo-600 focus:ring-indigo-500"
                  />
                  <label for="mc-option-2" class="ml-2 text-sm text-gray-700">2-M/Card without last S/Order#</label>
                </div>
              </div>
            </div>
          
            <!-- SO Period -->
            <div class="flex flex-col">
              <label class="block text-sm font-medium text-gray-700 mb-1">SO Period:</label>
              <div class="flex">
                <div class="flex-1 flex">
                  <input
                    v-model="searchParams.soPeriodFromMonth"
                    type="text"
                    maxlength="2"
                    class="w-12 text-center border-l border-t border-b border-gray-300 py-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                    placeholder="MM"
                  />
                  <input
                    v-model="searchParams.soPeriodFromYear"
                    type="text"
                    maxlength="2"
                    class="w-12 text-center border border-gray-300 py-2 rounded-r-md focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                    placeholder="YY"
                  />
                </div>
                <span class="mx-2 flex items-center">to</span>
                <div class="flex-1 flex">
                  <input
                    v-model="searchParams.soPeriodToMonth"
                    type="text"
                    maxlength="2"
                    class="w-12 text-center border-l border-t border-b border-gray-300 py-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                    placeholder="MM"
                  />
                  <input
                    v-model="searchParams.soPeriodToYear"
                    type="text"
                    maxlength="2"
                    class="w-12 text-center border border-gray-300 py-2 rounded-r-md focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                    placeholder="YY"
                  />
                </div>
                <span class="ml-2 text-sm text-gray-500">mm/yyyy</span>
              </div>
            </div>
            
            <!-- Salesperson -->
            <div class="flex flex-col">
              <label class="block text-sm font-medium text-gray-700 mb-1">Salesperson:</label>
              <div class="relative flex ml-8">
                <div class="absolute -left-8 top-1/2 transform -translate-y-1/2 flex items-center justify-center w-7 h-7 bg-gradient-to-r from-purple-500 to-indigo-600 rounded-full shadow-md z-10">
                  <i class="fas fa-user-tie text-white text-sm animate-pulse-light"></i>
                </div>
                <input
                  v-model="searchParams.salesperson"
                  type="text"
                  class="rounded-l-md flex-1 min-w-0 block w-full px-3 py-2 border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                />
                <button @click="showSalespersonSearch" class="inline-flex items-center px-3 py-2 border border-l-0 border-gray-300 bg-gradient-to-r from-indigo-500 to-purple-600 text-white rounded-r-md hover:from-indigo-600 hover:to-purple-700 transition-colors">
                  <i class="fas fa-search text-white text-sm"></i>
                </button>
              </div>
            </div>
            
            <!-- MC Status -->
            <div class="flex flex-col lg:col-span-3">
              <label class="block text-sm font-medium text-gray-700 mb-2">MC Status:</label>
              <div class="flex items-center space-x-4">
                <div class="flex items-center">
                  <input
                    id="status-active"
                    type="checkbox"
                    v-model="searchParams.statusActive"
                    class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
                  />
                  <label for="status-active" class="ml-2 text-sm text-gray-700">A-Active</label>
                </div>
                <div class="flex items-center">
                  <input
                    id="status-obsolete"
                    type="checkbox"
                    v-model="searchParams.statusObsolete"
                    class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
                  />
                  <label for="status-obsolete" class="ml-2 text-sm text-gray-700">O-Obsolete</label>
                </div>
              </div>
            </div>
            
            <!-- Sort Option -->
            <div class="md:col-span-2 lg:col-span-3">
              <label class="block text-sm font-medium text-gray-700 mb-2">Sort Option:</label>
              <div class="flex flex-wrap gap-4">
                <div class="flex items-center">
                  <input
                    id="sort-option-1"
                    type="radio"
                    v-model="searchParams.sortOption"
                    value="1"
                    class="h-4 w-4 text-indigo-600 focus:ring-indigo-500"
                    checked
                  />
                  <label for="sort-option-1" class="ml-2 text-sm text-gray-700">1-Customer+M/Card</label>
                </div>
                <div class="flex items-center">
                  <input
                    id="sort-option-2"
                    type="radio"
                    v-model="searchParams.sortOption"
                    value="2"
                    class="h-4 w-4 text-indigo-600 focus:ring-indigo-500"
                  />
                  <label for="sort-option-2" class="ml-2 text-sm text-gray-700">2-Block#</label>
                </div>
                <div class="flex items-center">
                  <input
                    id="sort-option-3"
                    type="radio"
                    v-model="searchParams.sortOption"
                    value="3"
                    class="h-4 w-4 text-indigo-600 focus:ring-indigo-500"
                  />
                  <label for="sort-option-3" class="ml-2 text-sm text-gray-700">3-Last S/Order#</label>
                </div>
                <div class="flex items-center">
                  <input
                    id="sort-option-4"
                    type="radio"
                    v-model="searchParams.sortOption"
                    value="4"
                    class="h-4 w-4 text-indigo-600 focus:ring-indigo-500"
                  />
                  <label for="sort-option-4" class="ml-2 text-sm text-gray-700">4-Customer+S/Order#(Descending)+M/Card Seq#</label>
                </div>
              </div>
            </div>
            
            <!-- Block Type -->
            <div class="md:col-span-2 lg:col-span-3">
              <label class="block text-sm font-medium text-gray-700 mb-2">Block Type:</label>
              <div class="flex flex-wrap gap-4">
                <div class="flex items-center">
                  <input
                    id="block-type-1"
                    type="radio"
                    v-model="searchParams.blockType"
                    value="1"
                    class="h-4 w-4 text-indigo-600 focus:ring-indigo-500"
                    checked
                  />
                  <label for="block-type-1" class="ml-2 text-sm text-gray-700">1-Printing Block</label>
                </div>
                <div class="flex items-center">
                  <input
                    id="block-type-2"
                    type="radio"
                    v-model="searchParams.blockType"
                    value="2"
                    class="h-4 w-4 text-indigo-600 focus:ring-indigo-500"
                  />
                  <label for="block-type-2" class="ml-2 text-sm text-gray-700">2-Diecut Block</label>
                </div>
              </div>
            </div>
          </div>
          </div>
        </div>
        
        <!-- Results preview will appear here when search is performed -->
        <div v-if="loading" class="flex flex-col items-center justify-center my-12">
          <div class="relative">
            <div class="absolute inset-0 bg-gradient-to-r from-indigo-500 to-purple-500 rounded-full opacity-20 animate-ping"></div>
            <div class="animate-spin rounded-full h-16 w-16 border-t-2 border-b-2 border-indigo-500"></div>
          </div>
          <p class="mt-4 text-indigo-600 font-medium">Loading results...</p>
        </div>

        <div v-if="showResults && !loading" class="bg-white rounded-xl shadow-lg overflow-hidden">
          <div class="bg-gradient-to-r from-purple-600 to-indigo-600 text-white py-4 px-6 flex items-center justify-between">
            <div class="flex items-center">
              <div class="mr-4 relative">
                <div class="absolute -top-1 -right-1 w-3 h-3 bg-yellow-300 animate-pulse rounded-full"></div>
                <div class="relative bg-white bg-opacity-20 p-2 rounded-lg transform rotate-3 shadow-md">
                  <i class="fas fa-layer-group text-2xl"></i>
                </div>
              </div>
              <div>
                <h2 class="text-xl font-bold">MASTER CARDS PRINT/DC BLOCK LISTING</h2>
                <p class="text-sm opacity-80">Results based on your search criteria</p>
              </div>
            </div>
            <div class="flex items-center space-x-3">
              <div class="relative">
                <input 
                  type="text" 
                  v-model="tableSearch" 
                  placeholder="Search results..."
                  class="bg-white bg-opacity-20 border border-white border-opacity-30 rounded px-4 py-1 text-white placeholder-blue-100 focus:outline-none focus:ring-2 focus:ring-white"
                />
                <div class="absolute right-2 top-1/2 transform -translate-y-1/2">
                  <i class="fas fa-search text-blue-100"></i>
                </div>
              </div>
            </div>
          </div>
          
          <div class="p-6">
            <div v-if="showResults && !blockListings.length" class="bg-gradient-to-br from-white to-purple-50 rounded-xl p-10 shadow-lg text-center">
              <div class="flex flex-col items-center justify-center space-y-4">
                <div class="w-20 h-20 bg-gradient-to-br from-purple-100 to-pink-100 rounded-full flex items-center justify-center text-purple-600 text-xl shadow-inner">
                  <i class="fas fa-search"></i>
                </div>
                <h3 class="text-xl font-medium text-indigo-800">No Results Found</h3>
                <p class="text-indigo-600 max-w-md">We couldn't find any master card listings matching your search criteria. Try adjusting your filters or search terms.</p>
                <button 
                  @click="resetAllFilters" 
                  class="mt-4 px-6 py-2 bg-gradient-to-r from-purple-600 to-pink-500 text-white rounded-lg hover:from-purple-700 hover:to-pink-600 transition-colors shadow-md"
                >
                  Reset All Filters
                </button>
              </div>
            </div>
            
            <!-- Results table -->
            <div v-if="showResults && blockListings.length" class="overflow-x-auto" id="printableTable">
              <div class="inline-block min-w-full align-middle">
                <table class="min-w-full divide-y divide-gray-200">
                  <thead>
                    <tr class="bg-gradient-to-r from-gray-50 to-indigo-50">
                      <th @click="sortTable('mc_seq')" 
                        class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider cursor-pointer hover:bg-indigo-100 transition-colors">
                        MC# 
                        <span v-if="sortColumn === 'mc_seq'" class="ml-1 inline-block">
                          <i :class="['fas', sortDirection === 'asc' ? 'fa-sort-up text-indigo-600' : 'fa-sort-down text-indigo-600']"></i>
                        </span>
                      </th>
                      <th @click="sortTable('customer_name')" 
                        class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider cursor-pointer hover:bg-indigo-100 transition-colors">
                        Customer
                        <span v-if="sortColumn === 'customer_name'" class="ml-1 inline-block">
                          <i :class="['fas', sortDirection === 'asc' ? 'fa-sort-up text-indigo-600' : 'fa-sort-down text-indigo-600']"></i>
                        </span>
                      </th>
                      <th @click="sortTable('description')" 
                        class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider cursor-pointer hover:bg-indigo-100 transition-colors">
                        Description
                        <span v-if="sortColumn === 'description'" class="ml-1 inline-block">
                          <i :class="['fas', sortDirection === 'asc' ? 'fa-sort-up text-indigo-600' : 'fa-sort-down text-indigo-600']"></i>
                        </span>
                      </th>
                      <th @click="sortTable('block_number')" 
                        class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider cursor-pointer hover:bg-indigo-100 transition-colors">
                        Block#
                        <span v-if="sortColumn === 'block_number'" class="ml-1 inline-block">
                          <i :class="['fas', sortDirection === 'asc' ? 'fa-sort-up text-indigo-600' : 'fa-sort-down text-indigo-600']"></i>
                        </span>
                      </th>
                      <th @click="sortTable('status')" 
                        class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider cursor-pointer hover:bg-indigo-100 transition-colors">
                        Status
                        <span v-if="sortColumn === 'status'" class="ml-1 inline-block">
                          <i :class="['fas', sortDirection === 'asc' ? 'fa-sort-up text-indigo-600' : 'fa-sort-down text-indigo-600']"></i>
                        </span>
                      </th>
                    </tr>
                  </thead>
                  <tbody class="bg-white divide-y divide-gray-200">
                    <tr v-for="(listing, index) in filteredBlockListings" :key="index" 
                      :class="{'bg-purple-50': index % 2 === 0}" 
                      class="hover:bg-indigo-50 transition-all duration-150">
                      <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ listing.mc_seq }}</td>
                      <td class="px-6 py-4 text-sm text-gray-900">{{ listing.customer_name }}</td>
                      <td class="px-6 py-4 text-sm text-gray-900">{{ listing.description }}</td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ listing.block_number }}</td>
                      <td class="px-6 py-4 whitespace-nowrap">
                        <span 
                          class="px-3 py-1 inline-flex items-center text-xs leading-5 font-semibold rounded-full shadow-sm"
                          :class="{
                            'bg-gradient-to-r from-green-400 to-green-500 text-white': listing.status === 'active',
                            'bg-gradient-to-r from-red-400 to-red-500 text-white': listing.status === 'obsolete'
                          }">
                          <i :class="['fas mr-1.5', listing.status === 'active' ? 'fa-check-circle' : 'fa-times-circle']"></i>
                          {{ capitalize(listing.status) }}
                        </span>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
            
            <!-- Pagination (if needed) -->
            <div v-if="showResults && blockListings.length > 10" class="flex justify-between items-center px-4 py-3 bg-white border-t border-gray-200 sm:px-6">
              <div class="flex items-center text-sm text-gray-700">
                Showing <span class="font-medium mx-1">1</span> to <span class="font-medium mx-1">{{ Math.min(10, filteredBlockListings.length) }}</span> of <span class="font-medium mx-1">{{ filteredBlockListings.length }}</span> results
              </div>
              <div class="flex space-x-2">
                <button class="px-3 py-1 border border-gray-300 rounded-md bg-gradient-to-r from-indigo-50 to-purple-50 text-indigo-700 hover:from-indigo-100 hover:to-purple-100 disabled:opacity-50 disabled:cursor-not-allowed transition-colors flex items-center">
                  <i class="fas fa-chevron-left mr-1 text-xs"></i> Previous
                </button>
                <button class="px-3 py-1 border border-gray-300 rounded-md bg-gradient-to-r from-indigo-50 to-purple-50 text-indigo-700 hover:from-indigo-100 hover:to-purple-100 disabled:opacity-50 disabled:cursor-not-allowed transition-colors flex items-center">
                  Next <i class="fas fa-chevron-right ml-1 text-xs"></i>
                </button>
              </div>
            </div>
          </div>
        </div>
        
        <!-- Help Section -->
        <div class="mt-6 bg-gradient-to-r from-purple-50 to-pink-50 p-4 rounded-lg border border-purple-100 shadow-sm">
          <h3 class="font-semibold text-indigo-800 mb-2 flex items-center">
            <div class="bg-gradient-to-br from-purple-400 to-indigo-500 text-white p-1.5 rounded-lg shadow-sm mr-2">
              <i class="fas fa-lightbulb"></i>
            </div>
            <span class="bg-gradient-to-r from-purple-600 to-indigo-600 text-transparent bg-clip-text">Instructions</span>
          </h3>
          <ul class="list-disc pl-5 text-sm text-gray-600 space-y-1">
            <li>Enter search criteria in the fields above</li>
            <li>You can search by ACN, MCS#, SO Period, Salesperson and other options</li>
            <li>Select MC Status options (Active, Obsolete or both)</li>
            <li>Choose a sort option and block type</li>
            <li>Click "Search" to find matching records</li>
            <li>Use the table filters to further refine your results</li>
            <li>Click "Print Report" to generate a printable report</li>
          </ul>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

// Search parameters
const searchParams = ref({
  acnFrom: '',
  acnTo: '',
  mcsFrom: '',
  mcsTo: '',
  mcOption: '1',
  soPeriodFromMonth: '',
  soPeriodFromYear: '',
  soPeriodToMonth: '',
  soPeriodToYear: '',
  salesperson: '',
  statusActive: true,
  statusObsolete: false,
  sortOption: '1',
  blockType: '1'
});

// UI States
const loading = ref(false);
const showResults = ref(false);
const tableSearch = ref('');
const sortColumn = ref('mc_seq');
const sortDirection = ref('asc');

// Sample data - this would be fetched from API in real implementation
const blockListings = ref([
  {
    mc_seq: 'MC001',
    customer_name: 'Acme Corporation',
    description: 'Box 300x200x150',
    block_number: 'BL001',
    status: 'active'
  },
  {
    mc_seq: 'MC002',
    customer_name: 'Globex Industries',
    description: 'Package 450x300x200',
    block_number: 'BL002',
    status: 'active'
  },
  {
    mc_seq: 'MC003',
    customer_name: 'Initech Systems',
    description: 'Container 600x400x300',
    block_number: 'BL003',
    status: 'obsolete'
  }
]);

// Filtered results based on search criteria and table search
const filteredBlockListings = computed(() => {
  if (!blockListings.value) return [];
  
  let filtered = [...blockListings.value];
  
  // Apply table search if provided
  if (tableSearch.value) {
    const searchTerm = tableSearch.value.toLowerCase();
    filtered = filtered.filter(item => 
      (item.mc_seq && item.mc_seq.toLowerCase().includes(searchTerm)) ||
      (item.customer_name && item.customer_name.toLowerCase().includes(searchTerm)) ||
      (item.description && item.description.toLowerCase().includes(searchTerm)) ||
      (item.block_number && item.block_number.toLowerCase().includes(searchTerm))
    );
  }
  
  // Apply sorting
  filtered.sort((a, b) => {
    let valA = a[sortColumn.value];
    let valB = b[sortColumn.value];
    
    if (typeof valA === 'string') valA = valA.toLowerCase();
    if (typeof valB === 'string') valB = valB.toLowerCase();
    
    if (valA < valB) return sortDirection.value === 'asc' ? -1 : 1;
    if (valA > valB) return sortDirection.value === 'asc' ? 1 : -1;
    return 0;
  });
  
  return filtered;
});

// Functions for search modal popups
const showAcnSearch = () => {
  // Implement customer search modal
  console.log('Open ACN search modal');
};

const showMcsSearch = () => {
  // Implement master card search modal
  console.log('Open MCS search modal');
};

const showSalespersonSearch = () => {
  // Implement salesperson search modal
  console.log('Open salesperson search modal');
};

// Utility function to capitalize strings
const capitalize = (str) => {
  if (!str) return '';
  return str.charAt(0).toUpperCase() + str.slice(1);
};

// Reset all filters
const resetAllFilters = () => {
  searchParams.value = {
    acnFrom: '',
    acnTo: '',
    mcsFrom: '',
    mcsTo: '',
    mcOption: '1',
    soPeriodFromMonth: '',
    soPeriodFromYear: '',
    soPeriodToMonth: '',
    soPeriodToYear: '',
    salesperson: '',
    statusActive: true,
    statusObsolete: false,
    sortOption: '1',
    blockType: '1'
  };
  tableSearch.value = '';
  showResults.value = false;
};

// Sort table function
const sortTable = (column) => {
  if (sortColumn.value === column) {
    sortDirection.value = sortDirection.value === 'asc' ? 'desc' : 'asc';
  } else {
    sortColumn.value = column;
    sortDirection.value = 'asc';
  }
};

// Print page function
const printPage = () => {
  window.print();
};

// Search function
const proceedSearch = () => {
  // Show loading spinner
  loading.value = true;
  
  // Implement search functionality
  console.log('Searching with parameters:', searchParams.value);
  
  // Simulate API call delay
  setTimeout(() => {
    // For this demo, we're just showing the sample results
    showResults.value = true;
    loading.value = false;
    
    // In a real implementation, you'd make an API call here
    // and populate the blockListings with the response data
  }, 1000);
};
</script>

<style scoped>
.text-shadow {
  text-shadow: 0 1px 3px rgba(0,0,0,0.2);
}

/* Animation classes */
@keyframes ping-slow {
  0% {
    transform: scale(1);
    opacity: 0.3;
  }
  50% {
    transform: scale(1.1);
    opacity: 0.2;
  }
  100% {
    transform: scale(1);
    opacity: 0.3;
  }
}

.animate-ping-slow {
  animation: ping-slow 3s infinite ease-in-out;
}

.animation-delay-500 {
  animation-delay: 0.5s;
}

.pulse-light {
  animation: pulse 2s infinite ease-in-out;
}

.search-button-icon {
  position: relative;
  transition: all 0.3s;
}

.search-button-icon:hover {
  transform: scale(1.2);
}

.search-button {
  position: relative;
  overflow: hidden;
}

.search-button::after {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(255, 255, 255, 0.1);
  opacity: 0;
  transform: scale(0.5);
  transition: all 0.3s;
}

.search-button:hover::after {
  opacity: 1;
  transform: scale(1);
}

.icon-animated {
  animation: icon-pulse 2s infinite;
}

@keyframes icon-pulse {
  0% { transform: scale(1); }
  50% { transform: scale(1.15); }
  100% { transform: scale(1); }
}

/* Print-specific styles */
@media print {
  .no-print {
    display: none !important;
  }
  
  body {
    font-size: 12pt;
    color: #000;
    background-color: #fff;
  }
  
  #printableTable {
    width: 100%;
    border-collapse: collapse;
  }
  
  /* More print styles as needed */
}
</style>
