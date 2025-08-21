<template>
  <AppLayout :header="'Define SKU'">
    <Head title="Define SKU" />

    <!-- Toast Container -->
    <ToastContainer />

    <!-- Header Section -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-800 p-6 rounded-t-lg shadow-md">
      <h2 class="text-2xl font-bold text-white mb-2 flex items-center">
        <i class="fas fa-boxes mr-3"></i> Define SKU
      </h2>
      <p class="text-blue-100">Manage Stock Keeping Units (SKUs) for inventory management</p>
    </div>

    <div class="bg-white rounded-b-lg shadow-md p-6 mb-6">
      <div class="flex flex-col md:flex-row gap-6">
        <!-- Main Content Area -->
        <div class="flex-1">
          <!-- Filter and Action Bar -->
          <div class="mb-6 space-y-4">
            <!-- Search and Filters Row -->
            <div class="flex flex-col lg:flex-row gap-4">
              <!-- Search -->
              <div class="flex-1">
              <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
                  <i class="fas fa-search"></i>
                </span>
                  <input type="text" v-model="searchQuery" placeholder="Search by SKU code, name, or category..."
                  class="pl-10 pr-4 py-2 w-full border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
              </div>
            </div>
              
              <!-- Category Filter -->
              <div class="w-full lg:w-48">
                <select v-model="filterCategory" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                  <option value="">All Categories</option>
                  <option v-for="category in uniqueCategories" :key="category" :value="category">{{ category }}</option>
                </select>
              </div>
              
              <!-- Type Filter -->
              <div class="w-full lg:w-40">
                <select v-model="filterType" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                  <option value="">All Types</option>
                  <option value="S">Stock Item</option>
                  <option value="NS">Non-Stock</option>
                </select>
              </div>
              

            </div>
            
            <!-- Action Buttons Row -->
            <div class="flex flex-wrap gap-2 justify-between items-center">
              <div class="flex flex-wrap gap-2">
                <button @click="newSku" class="btn-primary">
                <i class="fas fa-plus-circle mr-2"></i> New SKU
              </button>
              </div>
              
              <div class="flex gap-2">
                <button @click="refreshData" class="btn-secondary">
                <i class="fas fa-sync-alt mr-2"></i> Refresh
              </button>
                <button @click="printSkus" class="btn-info">
                <i class="fas fa-print mr-2"></i> Print
              </button>
              </div>
            </div>
          </div>
          
          <!-- Table Section -->
          <div class="bg-white rounded-lg overflow-hidden border border-gray-200">
            <div class="overflow-x-auto">
              <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                  <tr>

                    <th @click="sortBy('sku')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100">
                      <div class="flex items-center">
                        SKU Code 
                        <i class="fas ml-1" :class="getSortIcon('sku')"></i>
                      </div>
                    </th>
                    <th @click="sortBy('sku_name')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100">
                      <div class="flex items-center">
                        SKU Name 
                        <i class="fas ml-1" :class="getSortIcon('sku_name')"></i>
                      </div>
                    </th>
                    <th @click="sortBy('category_code')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100">
                      <div class="flex items-center">
                        Category 
                        <i class="fas ml-1" :class="getSortIcon('category_code')"></i>
                      </div>
                    </th>
                    <th @click="sortBy('type')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100">
                      <div class="flex items-center">
                        Type 
                        <i class="fas ml-1" :class="getSortIcon('type')"></i>
                      </div>
                    </th>
                    <th @click="sortBy('uom')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100">
                      <div class="flex items-center">
                        Unit 
                        <i class="fas ml-1" :class="getSortIcon('uom')"></i>
                      </div>
                    </th>
                    <th @click="sortBy('boh')" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100">
                      <div class="flex items-center justify-end">
                        BOH 
                        <i class="fas ml-1" :class="getSortIcon('boh')"></i>
                      </div>
                    </th>
                    
                  </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                  <tr v-if="loading" class="animate-pulse">
                    <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">
                      <div class="flex justify-center items-center space-x-2">
                        <i class="fas fa-spinner fa-spin"></i>
                        <span>Loading SKUs...</span>
                      </div>
                    </td>
                  </tr>
                  <tr v-else-if="paginatedSkus.length === 0" class="hover:bg-gray-50">
                    <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">
                      {{ searchQuery || filterCategory || filterType ? 'No SKUs found matching current filters.' : 'No SKUs defined yet. Click "New SKU" to create one.' }}
                    </td>
                  </tr>
                  <tr v-for="sku in paginatedSkus" :key="sku.sku" 
                      @click="selectSku(sku, $event)" 
                      :class="{'bg-blue-50': selectedSku && selectedSku.sku === sku.sku}"
                      class="hover:bg-gray-50 cursor-pointer transition-colors duration-150">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                      <div class="flex items-center">
                        <span class="font-mono">{{ sku.sku }}</span>

                      </div>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">
                      <div class="max-w-xs truncate" :title="sku.sku_name">{{ sku.sku_name }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                      <span class="font-mono">{{ sku.category_code }}</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                      <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium"
                            :class="sku.type === 'S' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800'">
                        <i class="fas mr-1" :class="sku.type === 'S' ? 'fa-box' : 'fa-tools'"></i>
                        {{ sku.type === 'S' ? 'Stock' : 'Non-Stock' }}
                      </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                      <span class="font-mono">{{ sku.uom || 'N/A' }}</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 text-right">
                      <span class="font-mono">{{ formatNumber(sku.boh) }}</span>
                    </td>

                  </tr>
                </tbody>
              </table>
            </div>
          </div>
                  
          <!-- Pagination Controls -->
          <div class="mt-4 flex justify-between items-center text-sm text-gray-600">
            <div>
              <span>Showing {{ paginatedSkus.length }} of {{ filteredSkus.length }} SKUs</span>
            </div>
            <div class="flex items-center space-x-2">
              <select v-model="itemsPerPage" class="border border-gray-300 rounded px-2 py-1 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option :value="10">10 per page</option>
                <option :value="20">20 per page</option>
                <option :value="50">50 per page</option>
                <option :value="100">100 per page</option>
              </select>
              <button :disabled="currentPage === 1" @click="currentPage--" class="px-2 py-1 border rounded hover:bg-gray-200 disabled:opacity-50">
                <i class="fas fa-chevron-left"></i>
              </button>
              <span class="px-4">{{ currentPage }} / {{ totalPages }}</span>
              <button :disabled="currentPage === totalPages || totalPages === 0" @click="currentPage++" class="px-2 py-1 border rounded hover:bg-gray-200 disabled:opacity-50">
                <i class="fas fa-chevron-right"></i>
              </button>
            </div>
          </div>
        </div>

        <!-- Side Panel with Details -->
        <div class="w-full md:w-96 bg-gray-50 rounded-lg p-4 border border-gray-200">
          <div v-if="selectedSku" class="mb-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-3 flex items-center">
              <i class="fas fa-info-circle mr-2 text-blue-500"></i> SKU Details
            </h3>
            <div class="space-y-3">
              <div class="flex justify-between border-b border-gray-200 pb-2">
                <span class="text-gray-600">SKU Code:</span>
                <span class="font-medium text-gray-900">{{ selectedSku.sku }}</span>
              </div>
              <div class="flex justify-between border-b border-gray-200 pb-2">
                <span class="text-gray-600">Name:</span>
                <span class="font-medium text-gray-900 text-right">{{ selectedSku.sku_name }}</span>
              </div>
              <div class="flex justify-between border-b border-gray-200 pb-2">
                <span class="text-gray-600">Category:</span>
                <span class="font-medium text-gray-900">{{ selectedSku.category_code }}</span>
              </div>
              <div class="flex justify-between border-b border-gray-200 pb-2">
                <span class="text-gray-600">Type:</span>
                <span class="font-medium text-gray-900">{{ selectedSku.type === 'S' ? 'Stock Item' : 'Non-Stock' }}</span>
              </div>
              <div class="flex justify-between border-b border-gray-200 pb-2">
                <span class="text-gray-600">Unit:</span>
                <span class="font-medium text-gray-900">{{ selectedSku.uom }}</span>
              </div>

            </div>
            <div class="mt-6 space-y-2">
              <button @click="editSku(selectedSku)" class="w-full btn-blue">
                <i class="fas fa-edit mr-1"></i> Edit
              </button>
              <button @click="openSkuPartModal" class="w-full btn-action">
                <i class="fas fa-puzzle-piece mr-1"></i> SKU Part#
              </button>
              <button @click="openAlternateUnitModal" class="w-full btn-action">
                <i class="fas fa-exchange-alt mr-1"></i> Alt Unit
              </button>
              <button @click="openSkuBalanceModal" class="w-full btn-action">
                <i class="fas fa-chart-line mr-1"></i> SKU Balance
              </button>
              <button @click="openSkuPictureModal" class="w-full btn-action">
                <i class="fas fa-camera mr-1"></i> Setup Picture
              </button>
            </div>
          </div>
          <div v-else class="flex flex-col items-center justify-center h-64 text-center">
            <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mb-4">
              <i class="fas fa-boxes text-blue-500 text-2xl"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-1">No SKU Selected</h3>
            <p class="text-gray-500 mb-4">Select a SKU from the table to view details</p>
            <button @click="newSku" class="btn-primary">
              <i class="fas fa-plus-circle mr-1"></i> Create New SKU
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Modals -->
    <CategoryTableModal 
      :show="showCategoryModal"
      @close="showCategoryModal = false"
      @categorySelected="handleCategorySelection"
    />

    <UnitTableModal 
      :show="showUnitModal"
      @close="showUnitModal = false"
      @unitSelected="handleUnitSelection"
    />

    <!-- Valuation Unit Modal -->
    <UnitTableModal 
      :show="showValuationUnitModal"
      @close="showValuationUnitModal = false"
      @unitSelected="handleValuationUnitSelection"
    />

    <LocationTableModal 
      :show="showLocationModal"
      @close="showLocationModal = false"
      @locationSelected="handleLocationSelection"
    />

    <ReportGroupTableModal 
      :show="showReportGroupModal"
      @close="showReportGroupModal = false"
      @reportGroupSelected="handleReportGroupSelection"
    />

    <AlternateUnitModal 
      :show="showAlternateUnitModal"
      :sku="formSku"
      @close="showAlternateUnitModal = false"
    />

    <SkuPartModal 
      :show="showSkuPartModal"
      :sku="formSku"
      @close="showSkuPartModal = false"
    />

    <SkuPictureModal 
      :show="showSkuPictureModal"
      :sku="formSku"
      @close="showSkuPictureModal = false"
      @pictureUpdated="handlePictureUpdate"
    />

    <SkuBalanceModal 
      :show="showSkuBalanceModal"
      :sku="formSku"
      @close="showSkuBalanceModal = false"
    />










    <!-- Form Modal -->
    <div v-if="showFormModal" class="fixed inset-0 flex items-center justify-center z-40 p-4">
      <div class="absolute inset-0 bg-black bg-opacity-50 backdrop-blur-sm" @click="showFormModal = false"></div>
      <div class="bg-white rounded-2xl shadow-2xl w-full max-w-6xl z-10 relative transform transition-all duration-300 ease-in-out max-h-[95vh] overflow-hidden">
        <!-- Modal Header -->
        <div class="flex items-center justify-between p-6 border-b border-gray-200 bg-gradient-to-r from-blue-600 to-blue-800 rounded-t-2xl">
          <div class="flex items-center">
            <div class="bg-white/20 p-3 rounded-xl mr-4">
              <i class="fas fa-boxes text-white text-2xl"></i>
            </div>
            <div>
              <h3 class="text-2xl font-bold text-white">{{ isEditing ? 'Edit SKU' : 'Create New SKU' }}</h3>
              <p class="text-blue-100 text-sm mt-1">{{ isEditing ? 'Update SKU information' : 'Enter SKU details below' }}</p>
            </div>
          </div>
          <button @click="showFormModal = false" class="bg-white/20 hover:bg-white/30 text-white p-3 rounded-xl transition-all duration-200 hover:scale-105">
            <i class="fas fa-times text-lg"></i>
          </button>
        </div>
        
        <!-- Form Content -->
        <div class="p-6 overflow-y-auto max-h-[calc(95vh-140px)]">
          <form @submit.prevent="saveSku">
            <!-- Basic Information Section -->
            <div class="mb-8">
              <h4 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-info-circle text-blue-500 mr-2"></i>
                Basic Information
              </h4>
              <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- SKU Code -->
                <div class="col-span-1">
                  <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-hashtag text-blue-500 mr-1"></i>
                    SKU Code<span class="text-red-500 ml-1">*</span>
                  </label>
                  <input 
                    type="text" 
                    v-model="formSku.sku" 
                    :disabled="isEditing"
                    maxlength="20"
                    @input="formSku.sku = formSku.sku.toUpperCase()"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 disabled:bg-gray-100"
                    placeholder="Enter SKU code"
                    required
                  >
                  <p class="text-xs text-gray-500 mt-1">Max 20 characters, cannot be changed later</p>
                </div>

                <!-- SKU Name -->
                <div class="col-span-1 md:col-span-2">
                  <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-tag text-blue-500 mr-1"></i>
                    SKU Name<span class="text-red-500 ml-1">*</span>
                  </label>
                  <input 
                    type="text" 
                    v-model="formSku.sku_name" 
                    maxlength="150"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                    placeholder="Enter SKU name"
                    required
                  >
                </div>
              </div>
            </div>

            <!-- Classification Section -->
            <div class="mb-8">
              <h4 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-tags text-green-500 mr-2"></i>
                Classification & Status
              </h4>
              <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- SKU Status (Read-only) -->
                <div class="col-span-1">
                  <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-flag text-green-500 mr-1"></i>
                    SKU Status
                  </label>
                  <div class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-50 flex items-center">
                    <span class="flex items-center text-sm font-medium" :class="getStatusColorClass(formSku.sts || 'ACTIVE')">
                      <span class="mr-2">{{ getStatusIcon(formSku.sts || 'ACTIVE') }}</span>
                      {{ getStatusText(formSku.sts || 'ACTIVE') }}
                    </span>
                  </div>
                  <p class="text-xs text-gray-500 mt-1">Current status (managed by system)</p>
                </div>

                <!-- SKU Type -->
                <div class="col-span-1">
                  <label class="block text-sm font-medium text-gray-700 mb-3">
                    <i class="fas fa-cube text-green-500 mr-1"></i>
                    SKU Type
                  </label>
                  <div class="space-y-2">
                    <label class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer transition-colors">
                      <input 
                        type="radio" 
                        v-model="formSku.type" 
                        value="S"
                        class="form-radio text-blue-600 mr-3"
                      >
                      <div class="flex items-center">
                        <i class="fas fa-box text-green-600 mr-2"></i>
                        <span class="font-medium">Stock Item</span>
                      </div>
                    </label>
                    <label class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer transition-colors">
                      <input 
                        type="radio" 
                        v-model="formSku.type" 
                        value="NS"
                        class="form-radio text-blue-600 mr-3"
                      >
                      <div class="flex items-center">
                        <i class="fas fa-tools text-blue-600 mr-2"></i>
                        <span class="font-medium">Non-Stock/Service</span>
                      </div>
                    </label>
                  </div>
                </div>

                <!-- Category -->
                <div class="col-span-1">
                  <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-folder text-green-500 mr-1"></i>
                    Category
                  </label>
                  <div class="flex">
                    <input 
                      type="text" 
                      v-model="formSku.category_code"
                      readonly
                      class="flex-1 px-4 py-3 border border-gray-300 rounded-l-lg bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                      placeholder="Select category..."
                    >
                    <button 
                      type="button"
                      @click="showCategoryModal = true"
                      class="px-4 py-3 bg-blue-600 hover:bg-blue-700 text-white border border-blue-600 rounded-r-lg transition-colors hover:scale-105"
                    >
                      <i class="fas fa-search"></i>
                    </button>
                  </div>
                </div>

                <!-- Base Unit -->
                <div class="col-span-1">
                  <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-ruler text-green-500 mr-1"></i>
                    Base Unit
                  </label>
                  <div class="flex">
                    <input 
                      type="text" 
                      v-model="formSku.uom"
                      readonly
                      class="flex-1 px-4 py-3 border border-gray-300 rounded-l-lg bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                      placeholder="Select unit..."
                    >
                    <button 
                      type="button"
                      @click="showUnitModal = true"
                      class="px-4 py-3 bg-blue-600 hover:bg-blue-700 text-white border border-blue-600 rounded-r-lg transition-colors hover:scale-105"
                    >
                      <i class="fas fa-search"></i>
                    </button>
                  </div>
                </div>
              </div>
            </div>

            <!-- Location & Reporting Section -->
            <div class="mb-8">
              <h4 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-map-marker-alt text-purple-500 mr-2"></i>
                Location & Reporting
              </h4>
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Location -->
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-warehouse text-purple-500 mr-1"></i>
                    Location
                  </label>
                  <div class="flex">
                    <input 
                      type="text" 
                      v-model="formSku.location_code"
                      readonly
                      class="flex-1 px-4 py-3 border border-gray-300 rounded-l-lg bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                      placeholder="Select location..."
                    >
                    <button 
                      type="button"
                      @click="showLocationModal = true"
                      class="px-4 py-3 bg-blue-600 hover:bg-blue-700 text-white border border-blue-600 rounded-r-lg transition-colors hover:scale-105"
                    >
                      <i class="fas fa-search"></i>
                    </button>
                  </div>
                </div>

                <!-- Report Group -->
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-chart-bar text-purple-500 mr-1"></i>
                    Report Group
                  </label>
                  <div class="flex">
                    <input 
                      type="text" 
                      v-model="formSku.report_group_code"
                      readonly
                      class="flex-1 px-4 py-3 border border-gray-300 rounded-l-lg bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                      placeholder="Select report group..."
                    >
                    <button 
                      type="button"
                      @click="showReportGroupModal = true"
                      class="px-4 py-3 bg-blue-600 hover:bg-blue-700 text-white border border-blue-600 rounded-r-lg transition-colors hover:scale-105"
                    >
                      <i class="fas fa-search"></i>
                    </button>
                  </div>
                </div>
              </div>
            </div>

            <!-- Valuation & Shipping Section -->
            <div class="mb-8">
              <h4 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-calculator text-orange-500 mr-2"></i>
                Valuation & Shipping
              </h4>
              <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Valuation Unit -->
                <div class="lg:col-span-2">
                  <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-balance-scale text-orange-500 mr-1"></i>
                    Valuation Unit
                  </label>
                  <div class="grid grid-cols-2 gap-3">
                    <div class="flex">
                    <input 
                      type="text" 
                      v-model="formSku.valuation_unit"
                        readonly
                        class="flex-1 px-4 py-3 border border-gray-300 rounded-l-lg bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Select unit..."
                      >
                      <button 
                        type="button"
                        @click="showValuationUnitModal = true"
                        class="px-4 py-3 bg-orange-600 hover:bg-orange-700 text-white border border-orange-600 rounded-r-lg transition-colors hover:scale-105"
                      >
                        <i class="fas fa-search"></i>
                      </button>
                    </div>
                    <input 
                      type="number" 
                      v-model="formSku.valuation_per_base_unit"
                      step="0.001"
                      class="px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                      placeholder="1.000"
                    >
                  </div>
                  <p class="text-xs text-gray-500 mt-1">Valuation unit and conversion factor</p>
                </div>

                <!-- Days to Ship -->
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-clock text-orange-500 mr-1"></i>
                    Days to Ship
                  </label>
                  <input 
                    type="number" 
                    v-model="formSku.days_to_ship"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    placeholder="0"
                  >
                  <p class="text-xs text-gray-500 mt-1">Average days to ship</p>
                </div>

                <!-- Units Shipped -->
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-shipping-fast text-orange-500 mr-1"></i>
                    Units Shipped
                  </label>
                  <input 
                    type="number" 
                    v-model="formSku.units_shipped"
                    step="0.001"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    placeholder="0.000"
                  >
                  <p class="text-xs text-gray-500 mt-1">Average units shipped</p>
                </div>
              </div>
            </div>

            <!-- Inventory Control Section -->
            <div class="mb-8">
              <h4 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-layer-group text-red-500 mr-2"></i>
                Inventory Control & Levels
              </h4>
              
              <!-- Current Inventory Status -->
              <div class="mb-6 p-4 bg-gradient-to-r from-blue-50 to-blue-100 rounded-lg border border-blue-200">
                <h5 class="text-sm font-medium text-blue-800 mb-3 flex items-center">
                  <i class="fas fa-info-circle mr-2"></i>
                  Current Inventory Status
                </h5>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                  <div>
                    <label class="block text-sm font-medium text-blue-700 mb-2">
                      <i class="fas fa-cubes text-blue-600 mr-1"></i>
                      Balance on Hand (BOH)
                    </label>
                    <input 
                      type="number" 
                      v-model="formSku.boh"
                      step="0.001"
                      class="w-full px-4 py-3 border border-blue-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white"
                      placeholder="0.000"
                      :class="formSku.boh < formSku.min_level ? 'border-red-400 bg-red-50' : ''"
                    >
                    <p class="text-xs text-blue-600 mt-1">Current stock quantity</p>
                  </div>
                  <div>
                    <label class="block text-sm font-medium text-blue-700 mb-2">
                      <i class="fas fa-clock text-blue-600 mr-1"></i>
                      Future Purchase Orders (FPO)
                    </label>
                    <input 
                      type="number" 
                      v-model="formSku.fpo"
                      step="0.001"
                      class="w-full px-4 py-3 border border-blue-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white"
                      placeholder="0.000"
                    >
                    <p class="text-xs text-blue-600 mt-1">Incoming purchase orders</p>
                  </div>
                  <div>
                    <label class="block text-sm font-medium text-blue-700 mb-2">
                      <i class="fas fa-calendar-check text-blue-600 mr-1"></i>
                      Reserved on Orders (ROL)
                    </label>
                    <input 
                      type="number" 
                      v-model="formSku.rol"
                      step="0.001"
                      class="w-full px-4 py-3 border border-blue-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white"
                      placeholder="0.000"
                    >
                    <p class="text-xs text-blue-600 mt-1">Reserved for orders</p>
                  </div>
                </div>
                
                <!-- Available Stock Calculation -->
                <div class="mt-4 p-3 bg-white rounded-lg border border-blue-200">
                  <div class="flex items-center justify-between">
                    <span class="text-sm font-medium text-gray-700">Available Stock:</span>
                    <span class="text-lg font-bold" :class="availableStock >= 0 ? 'text-green-600' : 'text-red-600'">
                      {{ availableStock.toFixed(3) }} {{ formSku.uom }}
                    </span>
                  </div>
                  <p class="text-xs text-gray-500 mt-1">BOH + FPO - ROL = Available Stock</p>
                </div>
              </div>

              <!-- Inventory Level Settings -->
              <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-arrow-down text-red-500 mr-1"></i>
                    Minimum Level
                  </label>
                  <input 
                    type="number" 
                    v-model="formSku.min_level"
                    step="0.001"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    placeholder="0.000"
                    @input="validateInventoryLevels"
                  >
                  <p class="text-xs text-gray-500 mt-1">Minimum stock level alert</p>
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-arrow-up text-red-500 mr-1"></i>
                    Maximum Level
                  </label>
                  <input 
                    type="number" 
                    v-model="formSku.max_level"
                    step="0.001"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    placeholder="0.000"
                    @input="validateInventoryLevels"
                    :class="formSku.max_level < formSku.min_level ? 'border-red-400' : ''"
                  >
                  <p class="text-xs text-gray-500 mt-1">Maximum stock level</p>
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-redo text-red-500 mr-1"></i>
                    Reorder Level
                  </label>
                  <input 
                    type="number" 
                    v-model="formSku.reorder_level"
                    step="0.001"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    placeholder="0.000"
                    @input="validateInventoryLevels"
                    :class="formSku.reorder_level > formSku.max_level || formSku.reorder_level < formSku.min_level ? 'border-yellow-400' : ''"
                  >
                  <p class="text-xs text-gray-500 mt-1">Trigger for reorder</p>
                </div>
              </div>

              <!-- Inventory Level Status -->
              <div v-if="inventoryValidationMessage" class="mt-4 p-3 rounded-lg" :class="inventoryValidationClass">
                <div class="flex items-center">
                  <i class="fas mr-2" :class="inventoryValidationIcon"></i>
                  <span class="text-sm font-medium">{{ inventoryValidationMessage }}</span>
                </div>
              </div>
            </div>

            <!-- Part Numbers & References Section -->
            <div class="mb-8">
              <h4 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-cogs text-purple-500 mr-2"></i>
                Part Numbers & References
              </h4>
              <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-barcode text-purple-500 mr-1"></i>
                    Part Number 1
                  </label>
                  <input 
                    type="text" 
                    v-model="formSku.part_number1"
                    maxlength="50"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    placeholder="Primary part number"
                  >
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-barcode text-purple-500 mr-1"></i>
                    Part Number 2
                  </label>
                  <input 
                    type="text" 
                    v-model="formSku.part_number2"
                    maxlength="50"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    placeholder="Secondary part number"
                  >
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-barcode text-purple-500 mr-1"></i>
                    Part Number 3
                  </label>
                  <input 
                    type="text" 
                    v-model="formSku.part_number3"
                    maxlength="50"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    placeholder="Tertiary part number"
                  >
                </div>
              </div>
            </div>

            <!-- Additional Information Section -->
            <div class="mb-8">
              <h4 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-plus-circle text-indigo-500 mr-2"></i>
                Additional Information
              </h4>
              <div class="space-y-4">
                <div v-for="i in 5" :key="i" class="flex items-center space-x-4">
                  <div class="flex-shrink-0 w-8 h-8 bg-indigo-100 rounded-full flex items-center justify-center">
                    <span class="text-sm font-medium text-indigo-600">{{ i }}</span>
                  </div>
                  <input 
                    type="text" 
                    v-model="formSku[`additional_name_${i}`]"
                    maxlength="150"
                    class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    :placeholder="`Additional description ${i} (Optional)`"
                  >
                </div>
              </div>
            </div>

            <!-- Advanced Settings Section -->
            <div class="mb-8">
              <h4 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-sliders-h text-teal-500 mr-2"></i>
                Advanced Settings
              </h4>
              <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Total Parts -->
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-puzzle-piece text-teal-500 mr-1"></i>
                    Total Parts
                  </label>
                  <input 
                    type="number" 
                    v-model="formSku.total_part"
                    min="0"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    placeholder="0"
                  >
                  <p class="text-xs text-gray-500 mt-1">Number of component parts</p>
                </div>

                <!-- Min Quantity -->
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-arrow-down text-teal-500 mr-1"></i>
                    Min Quantity
                  </label>
                  <input 
                    type="number" 
                    v-model="formSku.min_qty"
                    step="0.001"
                    min="0"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    placeholder="0.000"
                  >
                  <p class="text-xs text-gray-500 mt-1">Minimum order quantity</p>
                </div>

                <!-- Max Quantity -->
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-arrow-up text-teal-500 mr-1"></i>
                    Max Quantity
                  </label>
                  <input 
                    type="number" 
                    v-model="formSku.max_qty"
                    step="0.001"
                    min="0"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    placeholder="0.000"
                  >
                  <p class="text-xs text-gray-500 mt-1">Maximum order quantity</p>
                </div>


              </div>
            </div>
            
            <!-- Form Footer with Buttons -->
            <div class="flex flex-col sm:flex-row gap-3 justify-end pt-6 border-t border-gray-200">
              <button 
                type="button" 
                @click="showFormModal = false" 
                class="px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg shadow-md transition-all duration-200 flex items-center justify-center font-medium"
              >
                <i class="fas fa-times mr-2"></i> Cancel
              </button>
              <button 
                type="submit" 
                class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg shadow-md transition-all duration-200 flex items-center justify-center font-medium hover:scale-105" 
                :disabled="loading"
              >
                <i class="fas fa-save mr-2"></i> 
                {{ isEditing ? 'Update SKU' : 'Create SKU' }}
                <span v-if="loading" class="ml-2 animate-spin"><i class="fas fa-spinner"></i></span>
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue'
import axios from 'axios'
import { Head, router } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import { useToast } from '@/Composables/useToast'
import ToastContainer from '@/Components/ToastContainer.vue'

// Modal Components
import CategoryTableModal from '@/Components/CategoryTableModal.vue'
import UnitTableModal from '@/Components/UnitTableModal.vue'
import LocationTableModal from '@/Components/LocationTableModal.vue'
import ReportGroupTableModal from '@/Components/ReportGroupTableModal.vue'
import AlternateUnitModal from '@/Components/AlternateUnitModal.vue'
import SkuPartModal from '@/Components/SkuPartModal.vue'
import SkuPictureModal from '@/Components/SkuPictureModal.vue'
import SkuBalanceModal from '@/Components/SkuBalanceModal.vue'

// State variables
const skus = ref([])
const selectedSku = ref(null)

const loading = ref(false)
const isEditing = ref(false)
const searchQuery = ref('')
const showFormModal = ref(false)
const sortOrder = ref({
  field: 'sku',
  direction: 'asc'
})
const currentPage = ref(1)
const itemsPerPage = ref(10)

// Filter states
const filterCategory = ref('')
const filterType = ref('')


// Modal states
const showCategoryModal = ref(false)
const showUnitModal = ref(false)
const showValuationUnitModal = ref(false)
const showLocationModal = ref(false)
const showReportGroupModal = ref(false)
const showAlternateUnitModal = ref(false)
const showSkuPartModal = ref(false)
const showSkuPictureModal = ref(false)
const showSkuBalanceModal = ref(false)

// Form states

// Initialize toast
const toast = useToast()

// Form for creating/editing SKU
const formSku = ref({
  sku: '',
  sts: 'ACTIVE',
  sku_name: '',
  category_code: '',
  type: 'S',
  uom: '',
  valuation_unit: '',
  valuation_per_base_unit: 1.000,
  boh: 0,
  fpo: 0,
  rol: 0,
  days_to_ship: 0,
  units_shipped: 0,
  location_code: '',
  report_group_code: '',
  min_level: 0,
  max_level: 0,
  reorder_level: 0,
  total_part: 0,
  min_qty: 0,
  max_qty: 0,
  additional_name: '',
  additional_name_1: '',
  additional_name_2: '',
  additional_name_3: '',
  additional_name_4: '',
  additional_name_5: '',
  part_number1: '',
  part_number2: '',
  part_number3: '',
  sku_picture_path: '',
})

// Reset form to default values
const resetForm = () => {
  formSku.value = {
    sku: '',
    sts: 'ACTIVE',
    sku_name: '',
    category_code: '',
    type: 'S',
    uom: '',
    valuation_unit: '',
    valuation_per_base_unit: 1.000,
    boh: 0,
    fpo: 0,
    rol: 0,
    days_to_ship: 0,
    units_shipped: 0,
    location_code: '',
    report_group_code: '',
    min_level: 0,
    max_level: 0,
    reorder_level: 0,
    total_part: 0,
    min_qty: 0,
    max_qty: 0,
    additional_name: '',
    additional_name_1: '',
    additional_name_2: '',
    additional_name_3: '',
    additional_name_4: '',
    additional_name_5: '',
    part_number1: '',
    part_number2: '',
    part_number3: '',
    sku_picture_path: '',
  }
  isEditing.value = false
}

// Computed properties
const filteredSkus = computed(() => {
  let result = skus.value
  
  // Apply search filter
  if (searchQuery.value) {
    const query = searchQuery.value.toLowerCase()
    result = result.filter(sku =>
      (sku.sku && sku.sku.toLowerCase().includes(query)) ||
      (sku.sku_name && sku.sku_name.toLowerCase().includes(query)) ||
      (sku.category_code && sku.category_code.toLowerCase().includes(query))
    )
  }
  
  // Apply category filter
  if (filterCategory.value) {
    result = result.filter(sku => sku.category_code === filterCategory.value)
  }
  
  // Apply type filter
  if (filterType.value) {
    result = result.filter(sku => sku.type === filterType.value)
  }
  

  
  // Apply sorting
  result = [...result].sort((a, b) => {
    const field = sortOrder.value.field
    const direction = sortOrder.value.direction === 'asc' ? 1 : -1
    
    let valA = a[field]
    let valB = b[field]
    
    // Handle numeric fields
    if (['boh', 'fpo', 'rol'].includes(field)) {
      valA = parseFloat(valA) || 0
      valB = parseFloat(valB) || 0
    }
    


    if (valA < valB) return -1 * direction
    if (valA > valB) return 1 * direction
    return 0
  })

  return result
})

const uniqueCategories = computed(() => {
  return [...new Set(skus.value.map(sku => sku.category_code).filter(Boolean))].sort()
})



// Inventory calculation computeds
const availableStock = computed(() => {
  const boh = parseFloat(formSku.value.boh) || 0
  const fpo = parseFloat(formSku.value.fpo) || 0
  const rol = parseFloat(formSku.value.rol) || 0
  return boh + fpo - rol
})

const inventoryValidationMessage = ref('')
const inventoryValidationClass = ref('')
const inventoryValidationIcon = ref('')

const paginatedSkus = computed(() => {
  const start = (currentPage.value - 1) * itemsPerPage.value
  const end = start + itemsPerPage.value
  return filteredSkus.value.slice(start, end)
})

const totalPages = computed(() => {
  const total = filteredSkus.value.length
  if (total === 0) return 1
  return Math.ceil(total / itemsPerPage.value)
})

// Watchers
watch(filteredSkus, () => {
  if (currentPage.value > totalPages.value) {
    currentPage.value = totalPages.value || 1
  }
})

watch(itemsPerPage, () => {
  currentPage.value = 1
})

// Methods
const fetchSkus = async () => {
  loading.value = true
  try {
    const response = await axios.get('/api/material-management/skus')
    skus.value = response.data
  } catch (error) {
    console.error('Error fetching SKUs:', error)
    toast.error('Failed to load SKUs')
  } finally {
    loading.value = false
  }
}

const selectSku = (sku, event) => {
  selectedSku.value = sku
  // Update formSku for modal operations
  formSku.value = { ...sku }
}



// Utility functions
const formatNumber = (value) => {
  if (value === null || value === undefined) return '0.000'
  return parseFloat(value).toFixed(3)
}

const getSortIcon = (field) => {
  if (sortOrder.value.field !== field) return 'fa-sort'
  return sortOrder.value.direction === 'asc' ? 'fa-sort-up' : 'fa-sort-down'
}

const newSku = () => {
  resetForm()
  showFormModal.value = true
}

const editSku = (sku) => {
  isEditing.value = true
  formSku.value = { ...sku }
  showFormModal.value = true
}

const saveSku = async () => {
  loading.value = true
  try {
    let response
    
    if (isEditing.value) {
      response = await axios.put(`/api/material-management/skus/${formSku.value.sku}`, formSku.value)
      toast.success('SKU updated successfully')
      
      // Update SKU in the list
      const index = skus.value.findIndex(s => s.sku === formSku.value.sku)
      if (index !== -1) {
        skus.value[index] = response.data
        selectedSku.value = response.data
      }
    } else {
      response = await axios.post('/api/material-management/skus', formSku.value)
      toast.success('SKU created successfully')
      skus.value.push(response.data)
      selectedSku.value = response.data
    }
    
    showFormModal.value = false
  } catch (error) {
    console.error('Error saving SKU:', error)
    toast.error(error.response?.data?.message || 'Failed to save SKU')
  } finally {
    loading.value = false
  }
}

const sortBy = (field) => {
  if (sortOrder.value.field === field) {
    sortOrder.value.direction = sortOrder.value.direction === 'asc' ? 'desc' : 'asc'
  } else {
    sortOrder.value.field = field
    sortOrder.value.direction = 'asc'
  }
}

const refreshData = () => {
  selectedSku.value = null
  searchQuery.value = ''
  filterCategory.value = ''
  filterType.value = ''
  fetchSkus()
}

const printSkus = () => {
  router.get('/material-management/system-requirement/inventory-setup/sku/view-print')
}













// Modal handlers
const handleCategorySelection = (category) => {
  formSku.value.category_code = category.code
}

const handleUnitSelection = (unit) => {
  formSku.value.uom = unit.code
}

const handleValuationUnitSelection = (unit) => {
  formSku.value.valuation_unit = unit.code
}

const handleLocationSelection = (location) => {
  formSku.value.location_code = location.code
}

const handleReportGroupSelection = (reportGroup) => {
  formSku.value.report_group_code = reportGroup.code
}

const handlePictureUpdate = (pictureData) => {
  formSku.value.sku_picture_path = pictureData.path
  toast.success('SKU picture updated')
}

// Inventory validation function
const validateInventoryLevels = () => {
  const minLevel = parseFloat(formSku.value.min_level) || 0
  const maxLevel = parseFloat(formSku.value.max_level) || 0
  const reorderLevel = parseFloat(formSku.value.reorder_level) || 0
  const boh = parseFloat(formSku.value.boh) || 0

  // Clear previous validation
  inventoryValidationMessage.value = ''
  inventoryValidationClass.value = ''
  inventoryValidationIcon.value = ''

  // Validate levels
  if (maxLevel > 0 && minLevel >= maxLevel) {
    inventoryValidationMessage.value = 'Warning: Minimum level should be less than maximum level'
    inventoryValidationClass.value = 'bg-yellow-50 border border-yellow-200 text-yellow-800'
    inventoryValidationIcon.value = 'fa-exclamation-triangle text-yellow-600'
    return
  }

  if (reorderLevel > maxLevel && maxLevel > 0) {
    inventoryValidationMessage.value = 'Warning: Reorder level should not exceed maximum level'
    inventoryValidationClass.value = 'bg-yellow-50 border border-yellow-200 text-yellow-800'
    inventoryValidationIcon.value = 'fa-exclamation-triangle text-yellow-600'
    return
  }

  if (reorderLevel < minLevel && minLevel > 0) {
    inventoryValidationMessage.value = 'Warning: Reorder level should not be below minimum level'
    inventoryValidationClass.value = 'bg-yellow-50 border border-yellow-200 text-yellow-800'
    inventoryValidationIcon.value = 'fa-exclamation-triangle text-yellow-600'
    return
  }

  // Check current stock status
  if (boh < minLevel && minLevel > 0) {
    inventoryValidationMessage.value = 'Alert: Current stock is below minimum level - consider restocking'
    inventoryValidationClass.value = 'bg-red-50 border border-red-200 text-red-800'
    inventoryValidationIcon.value = 'fa-exclamation-circle text-red-600'
    return
  }

  if (boh <= reorderLevel && reorderLevel > 0) {
    inventoryValidationMessage.value = 'Alert: Current stock has reached reorder level - order more stock'
    inventoryValidationClass.value = 'bg-orange-50 border border-orange-200 text-orange-800'
    inventoryValidationIcon.value = 'fa-bell text-orange-600'
    return
  }

  // All good
  if (minLevel > 0 || maxLevel > 0 || reorderLevel > 0) {
    inventoryValidationMessage.value = 'Inventory levels are properly configured'
    inventoryValidationClass.value = 'bg-green-50 border border-green-200 text-green-800'
    inventoryValidationIcon.value = 'fa-check-circle text-green-600'
  }
}

const handleImageError = () => {
  console.log('Error loading SKU image')
}

// Status display helper functions
const getStatusIcon = (status) => {
  const statusIcons = {
    'ACTIVE': '🟢',
    'INACTIVE': '🔴',
    'PENDING': '🟡',
    'OBSOLETE': '⚫',
    'DISCONTINUED': '❌',
    'HOLD': '⏸️'
  }
  return statusIcons[status] || '❓'
}

const getStatusText = (status) => {
  const statusTexts = {
    'ACTIVE': 'Active',
    'INACTIVE': 'Inactive', 
    'PENDING': 'Pending',
    'OBSOLETE': 'Obsolete',
    'DISCONTINUED': 'Discontinued',
    'HOLD': 'On Hold'
  }
  return statusTexts[status] || 'Unknown'
}

const getStatusColorClass = (status) => {
  const statusColors = {
    'ACTIVE': 'text-green-700',
    'INACTIVE': 'text-red-700',
    'PENDING': 'text-yellow-700',
    'OBSOLETE': 'text-gray-700',
    'DISCONTINUED': 'text-red-800',
    'HOLD': 'text-orange-700'
  }
  return statusColors[status] || 'text-gray-500'
}

// Action button handlers
const openAlternateUnitModal = () => {
  if (!formSku.value.sku) {
    toast.warning('Please select or create a SKU first')
    return
  }
  showAlternateUnitModal.value = true
}

const openSkuPartModal = () => {
  if (!formSku.value.sku) {
    toast.warning('Please select or create a SKU first')
    return
  }
  showSkuPartModal.value = true
}

const openSkuPictureModal = () => {
  if (!formSku.value.sku) {
    toast.warning('Please select or create a SKU first')
    return
  }
  showSkuPictureModal.value = true
}

const openSkuBalanceModal = () => {
  if (!formSku.value.sku) {
    toast.warning('Please select or create a SKU first')
    return
  }
  showSkuBalanceModal.value = true
}

const floatingPd = () => {
  toast.info('Floating PD feature')
}

const addNote = () => {
  toast.info('Add Note feature')
}

const processLog = () => {
  toast.info('Process Log feature')
}

onMounted(() => {
  fetchSkus()
})
</script>

<style scoped>
/* Button styles */
.btn-primary {
  @apply bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg shadow transition-all duration-200
    flex items-center justify-center whitespace-nowrap hover:scale-105;
}

.btn-secondary {
  @apply bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg shadow transition-all duration-200
    flex items-center justify-center whitespace-nowrap hover:scale-105;
}

.btn-info {
  @apply bg-cyan-600 hover:bg-cyan-700 text-white px-4 py-2 rounded-lg shadow transition-all duration-200
    flex items-center justify-center whitespace-nowrap hover:scale-105;
}





.btn-action {
  @apply bg-gray-100 hover:bg-gray-200 text-gray-700 px-3 py-1 text-sm rounded border border-gray-300 transition-colors
    flex items-center justify-center whitespace-nowrap;
}

.btn-blue {
  @apply bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg shadow transition-all duration-200
    flex items-center justify-center whitespace-nowrap hover:scale-105;
}

/* Form styles */
.form-radio {
  @apply h-4 w-4 border-gray-300 focus:ring-2 focus:ring-blue-500;
}



/* Table hover animation */
tbody tr {
  transition: all 0.2s;
}

tbody tr:hover {
  transform: translateY(-2px);
  box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

/* Scrollbar styling */
.overflow-x-auto, .overflow-y-auto {
  scrollbar-width: thin;
  scrollbar-color: rgba(156, 163, 175, 0.5) transparent;
}

.overflow-x-auto::-webkit-scrollbar, .overflow-y-auto::-webkit-scrollbar {
  height: 8px;
  width: 8px;
}

.overflow-x-auto::-webkit-scrollbar-track, .overflow-y-auto::-webkit-scrollbar-track {
  background: transparent;
}

.overflow-x-auto::-webkit-scrollbar-thumb, .overflow-y-auto::-webkit-scrollbar-thumb {
  background-color: rgba(156, 163, 175, 0.5);
  border-radius: 20px;
}
</style>