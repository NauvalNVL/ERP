<template>
  <AppLayout title="Define SKU">
    <div class="py-6">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <h1 class="text-2xl font-semibold text-gray-800">Define SKU</h1>
        <p class="mt-1 text-sm text-gray-600">Manage Stock Keeping Units (SKU) for inventory management</p>
        
        <div class="mt-4 flex justify-end">
          <button 
            @click="showAddModal = true" 
            class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150"
          >
            <i class="fas fa-plus mr-2"></i> Add New SKU
          </button>
        </div>
        
        <!-- Search and filter section -->
        <div class="mt-4 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-4 border border-gray-200 dark:border-gray-700">
          <div class="flex flex-wrap gap-4">
            <div class="w-full sm:w-64">
              <label class="block text-sm font-medium text-gray-700 mb-1">Search</label>
              <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                  <i class="fas fa-search text-gray-400"></i>
                </div>
                <input 
                  v-model="filters.search" 
                  @input="debouncedFetch"
                  type="text" 
                  class="form-input block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" 
                  placeholder="Search SKU or name"
                />
              </div>
            </div>
            
            <div class="w-full sm:w-64">
              <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
              <select 
                v-model="filters.category_code" 
                @change="fetchSkus"
                class="form-select mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
              >
                <option value="">All Categories</option>
                <option v-for="category in categories" :key="category.code" :value="category.code">
                  {{ category.name }}
                </option>
              </select>
            </div>
            
            <div class="w-full sm:w-64">
              <label class="block text-sm font-medium text-gray-700 mb-1">Type</label>
              <select 
                v-model="filters.type" 
                @change="fetchSkus"
                class="form-select mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
              >
                <option value="">All Types</option>
                <option v-for="type in types" :key="type" :value="type">
                  {{ type }}
                </option>
              </select>
            </div>
            
            <div class="w-full sm:w-auto flex items-end">
              <button 
                @click="resetFilters"
                class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
              >
                <i class="fas fa-redo-alt mr-2"></i> Reset Filters
              </button>
            </div>
          </div>
        </div>
        
        <!-- Main content section -->
        <div class="mt-4">
          <!-- Loading indicator -->
          <div v-if="loading" class="flex justify-center items-center py-8">
            <div class="animate-spin rounded-full h-10 w-10 border-t-2 border-b-2 border-blue-500"></div>
          </div>
          
          <!-- Error message -->
          <div v-else-if="error" class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
            <p class="font-bold">Error</p>
            <p>{{ error }}</p>
          </div>
          
          <!-- SKU Table -->
          <div v-else-if="skus.data && skus.data.length > 0" class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
            <div class="overflow-x-auto">
              <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                  <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                      SKU#
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                      Status
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                      SKU Name
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                      Category
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                      Type
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                      UOM
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                      BOH
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                      Actions
                    </th>
                  </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                  <tr v-for="sku in skus.data" :key="sku.sku" class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                      {{ sku.sku }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                      <span 
                        :class="{
                          'px-2 inline-flex text-xs leading-5 font-semibold rounded-full': true,
                          'bg-green-100 text-green-800': sku.is_active,
                          'bg-red-100 text-red-800': !sku.is_active
                        }"
                      >
                        {{ sku.is_active ? 'Active' : 'Inactive' }}
                      </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                      {{ sku.sku_name }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                      {{ sku.category ? sku.category.name : 'N/A' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                      {{ sku.type || 'N/A' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                      {{ sku.uom || 'N/A' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                      {{ formatNumber(sku.boh) }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                      <button 
                        @click="editSku(sku)" 
                        class="text-indigo-600 hover:text-indigo-900 mr-4"
                      >
                        <i class="fas fa-edit"></i> Edit
                      </button>
                      <button 
                        @click="confirmDelete(sku)" 
                        class="text-red-600 hover:text-red-900 mr-4"
                      >
                        <i class="fas fa-trash"></i> Delete
                      </button>
                      <button 
                        @click="toggleActive(sku)" 
                        :class="{
                          'hover:text-indigo-900 mr-4': true,
                          'text-red-600': sku.is_active,
                          'text-green-600': !sku.is_active
                        }"
                      >
                        <i :class="sku.is_active ? 'fas fa-ban' : 'fas fa-check'"></i>
                        {{ sku.is_active ? 'Disable' : 'Enable' }}
                      </button>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
            
            <!-- Pagination -->
            <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
              <div class="flex justify-between">
                <div class="text-sm text-gray-700">
                  Showing {{ skus.from }} to {{ skus.to }} of {{ skus.total }} results
                </div>
                <div>
                  <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                    <button
                      @click="changePage(skus.current_page - 1)"
                      :disabled="skus.current_page === 1"
                      :class="{
                        'relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium': true,
                        'text-gray-300 cursor-not-allowed': skus.current_page === 1,
                        'text-gray-500 hover:bg-gray-50': skus.current_page !== 1
                      }"
                    >
                      <span class="sr-only">Previous</span>
                      <i class="fas fa-chevron-left"></i>
                    </button>
                    
                    <div v-for="page in pageRange" :key="page">
                      <button
                        v-if="page !== '...'"
                        @click="changePage(page)"
                        :class="{
                          'relative inline-flex items-center px-4 py-2 border text-sm font-medium': true,
                          'bg-blue-50 border-blue-500 text-blue-600 z-10': page === skus.current_page,
                          'bg-white border-gray-300 text-gray-500 hover:bg-gray-50': page !== skus.current_page
                        }"
                      >
                        {{ page }}
                      </button>
                      <span
                        v-else
                        class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700"
                      >
                        ...
                      </span>
                    </div>
                    
                    <button
                      @click="changePage(skus.current_page + 1)"
                      :disabled="skus.current_page === skus.last_page"
                      :class="{
                        'relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium': true,
                        'text-gray-300 cursor-not-allowed': skus.current_page === skus.last_page,
                        'text-gray-500 hover:bg-gray-50': skus.current_page !== skus.last_page
                      }"
                    >
                      <span class="sr-only">Next</span>
                      <i class="fas fa-chevron-right"></i>
                    </button>
                  </nav>
                </div>
              </div>
            </div>
          </div>
          
          <!-- Empty state -->
          <div v-else class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 p-6">
            <div class="text-center">
              <i class="fas fa-box-open text-4xl text-gray-400 mb-3"></i>
              <h3 class="text-lg leading-6 font-medium text-gray-900">No SKUs found</h3>
              <p class="mt-1 text-sm text-gray-500">
                Get started by creating a new SKU or try a different search.
              </p>
              <div class="mt-6">
                <button 
                  @click="showAddModal = true" 
                  class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                >
                  <i class="fas fa-plus mr-2"></i> Add New SKU
                </button>
                <button 
                  @click="seedSampleData"
                  class="ml-3 inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                >
                  <i class="fas fa-seedling mr-2"></i> Seed Sample Data
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Add/Edit SKU Modal -->
    <TransitionRoot appear :show="showAddModal || showEditModal" as="template">
      <Dialog as="div" @close="showAddModal ? (showAddModal = false) : (showEditModal = false)" class="relative z-10">
        <TransitionChild
          as="template"
          enter="duration-300 ease-out"
          enter-from="opacity-0"
          enter-to="opacity-100"
          leave="duration-200 ease-in"
          leave-from="opacity-100"
          leave-to="opacity-0"
        >
          <div class="fixed inset-0 bg-black bg-opacity-25" />
        </TransitionChild>

        <div class="fixed inset-0 overflow-y-auto">
          <div class="flex min-h-full items-center justify-center p-4 text-center">
            <TransitionChild
              as="template"
              enter="duration-300 ease-out"
              enter-from="opacity-0 scale-95"
              enter-to="opacity-100 scale-100"
              leave="duration-200 ease-in"
              leave-from="opacity-100 scale-100"
              leave-to="opacity-0 scale-95"
            >
              <DialogPanel class="w-full max-w-4xl transform overflow-hidden rounded-2xl bg-white p-6 text-left align-middle shadow-xl transition-all">
                <DialogTitle as="h3" class="text-lg font-medium leading-6 text-gray-900">
                  {{ showAddModal ? 'Add New SKU' : 'Edit SKU' }}
                </DialogTitle>
                
                <!-- Form -->
                <div class="mt-4 grid grid-cols-1 md:grid-cols-3 gap-4">
                  <!-- Left Column -->
                  <div class="space-y-4">
                    <div>
                      <label class="block text-sm font-medium text-gray-700">SKU #</label>
                      <input 
                        type="text" 
                        v-model="form.sku" 
                        :disabled="showEditModal"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" 
                        :class="{ 'border-red-500': formErrors.sku }"
                      />
                      <p v-if="formErrors.sku" class="mt-1 text-sm text-red-600">{{ formErrors.sku[0] }}</p>
                    </div>
                    
                    <div>
                      <label class="block text-sm font-medium text-gray-700">Status</label>
                      <select 
                        v-model="form.sts"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                        :class="{ 'border-red-500': formErrors.sts }"
                      >
                        <option value="ACT">Active</option>
                        <option value="OBS">Obsolete</option>
                      </select>
                      <p v-if="formErrors.sts" class="mt-1 text-sm text-red-600">{{ formErrors.sts[0] }}</p>
                    </div>
                    
                    <div>
                      <label class="block text-sm font-medium text-gray-700">Category</label>
                      <select 
                        v-model="form.category_code"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                        :class="{ 'border-red-500': formErrors.category_code }"
                      >
                        <option v-for="category in categories" :key="category.code" :value="category.code">
                          {{ category.name }}
                        </option>
                      </select>
                      <p v-if="formErrors.category_code" class="mt-1 text-sm text-red-600">{{ formErrors.category_code[0] }}</p>
                    </div>
                    
                    <div>
                      <label class="block text-sm font-medium text-gray-700">Type</label>
                      <input 
                        type="text" 
                        v-model="form.type" 
                        list="sku-types"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" 
                        :class="{ 'border-red-500': formErrors.type }"
                      />
                      <datalist id="sku-types">
                        <option v-for="type in types" :key="type" :value="type">{{ type }}</option>
                      </datalist>
                      <p v-if="formErrors.type" class="mt-1 text-sm text-red-600">{{ formErrors.type[0] }}</p>
                    </div>
                    
                    <div>
                      <label class="block text-sm font-medium text-gray-700">UOM (Unit of Measure)</label>
                      <input 
                        type="text" 
                        v-model="form.uom" 
                        list="sku-uoms"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" 
                        :class="{ 'border-red-500': formErrors.uom }"
                      />
                      <datalist id="sku-uoms">
                        <option v-for="uom in uoms" :key="uom" :value="uom">{{ uom }}</option>
                      </datalist>
                      <p v-if="formErrors.uom" class="mt-1 text-sm text-red-600">{{ formErrors.uom[0] }}</p>
                    </div>
                  </div>
                  
                  <!-- Middle Column -->
                  <div class="space-y-4">
                    <div>
                      <label class="block text-sm font-medium text-gray-700">SKU Name</label>
                      <input 
                        type="text" 
                        v-model="form.sku_name" 
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" 
                        :class="{ 'border-red-500': formErrors.sku_name }"
                      />
                      <p v-if="formErrors.sku_name" class="mt-1 text-sm text-red-600">{{ formErrors.sku_name[0] }}</p>
                    </div>
                    
                    <div>
                      <label class="block text-sm font-medium text-gray-700">Additional Name</label>
                      <textarea 
                        v-model="form.additional_name" 
                        rows="3"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" 
                        :class="{ 'border-red-500': formErrors.additional_name }"
                      ></textarea>
                      <p v-if="formErrors.additional_name" class="mt-1 text-sm text-red-600">{{ formErrors.additional_name[0] }}</p>
                    </div>
                    
                    <div>
                      <label class="block text-sm font-medium text-gray-700">BOH (Balance on Hand)</label>
                      <input 
                        type="number" 
                        step="0.001"
                        v-model="form.boh" 
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" 
                        :class="{ 'border-red-500': formErrors.boh }"
                      />
                      <p v-if="formErrors.boh" class="mt-1 text-sm text-red-600">{{ formErrors.boh[0] }}</p>
                    </div>
                    
                    <div>
                      <label class="block text-sm font-medium text-gray-700">FPO (Future Purchase Orders)</label>
                      <input 
                        type="number" 
                        step="0.001"
                        v-model="form.fpo" 
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" 
                        :class="{ 'border-red-500': formErrors.fpo }"
                      />
                      <p v-if="formErrors.fpo" class="mt-1 text-sm text-red-600">{{ formErrors.fpo[0] }}</p>
                    </div>
                    
                    <div>
                      <label class="block text-sm font-medium text-gray-700">ROL (Reorder Level)</label>
                      <input 
                        type="number" 
                        step="0.001"
                        v-model="form.rol" 
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" 
                        :class="{ 'border-red-500': formErrors.rol }"
                      />
                      <p v-if="formErrors.rol" class="mt-1 text-sm text-red-600">{{ formErrors.rol[0] }}</p>
                    </div>
                  </div>
                  
                  <!-- Right Column -->
                  <div class="space-y-4">
                    <div>
                      <label class="block text-sm font-medium text-gray-700">Total Part</label>
                      <input 
                        type="number"
                        v-model="form.total_part" 
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" 
                        :class="{ 'border-red-500': formErrors.total_part }"
                      />
                      <p v-if="formErrors.total_part" class="mt-1 text-sm text-red-600">{{ formErrors.total_part[0] }}</p>
                    </div>
                    
                    <div>
                      <label class="block text-sm font-medium text-gray-700">Min Qty</label>
                      <input 
                        type="number" 
                        step="0.01"
                        v-model="form.min_qty" 
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" 
                        :class="{ 'border-red-500': formErrors.min_qty }"
                      />
                      <p v-if="formErrors.min_qty" class="mt-1 text-sm text-red-600">{{ formErrors.min_qty[0] }}</p>
                    </div>
                    
                    <div>
                      <label class="block text-sm font-medium text-gray-700">Max Qty</label>
                      <input 
                        type="number" 
                        step="0.01"
                        v-model="form.max_qty" 
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" 
                        :class="{ 'border-red-500': formErrors.max_qty }"
                      />
                      <p v-if="formErrors.max_qty" class="mt-1 text-sm text-red-600">{{ formErrors.max_qty[0] }}</p>
                    </div>
                    
                    <div>
                      <label class="block text-sm font-medium text-gray-700">Part Number 1</label>
                      <input 
                        type="text" 
                        v-model="form.part_number1" 
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" 
                        :class="{ 'border-red-500': formErrors.part_number1 }"
                      />
                      <p v-if="formErrors.part_number1" class="mt-1 text-sm text-red-600">{{ formErrors.part_number1[0] }}</p>
                    </div>
                    
                    <div>
                      <label class="block text-sm font-medium text-gray-700">Part Number 2</label>
                      <input 
                        type="text" 
                        v-model="form.part_number2" 
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" 
                        :class="{ 'border-red-500': formErrors.part_number2 }"
                      />
                      <p v-if="formErrors.part_number2" class="mt-1 text-sm text-red-600">{{ formErrors.part_number2[0] }}</p>
                    </div>
                    
                    <div>
                      <label class="block text-sm font-medium text-gray-700">Part Number 3</label>
                      <input 
                        type="text" 
                        v-model="form.part_number3" 
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" 
                        :class="{ 'border-red-500': formErrors.part_number3 }"
                      />
                      <p v-if="formErrors.part_number3" class="mt-1 text-sm text-red-600">{{ formErrors.part_number3[0] }}</p>
                    </div>
                  </div>
                </div>

                <!-- Active Status Toggle -->
                <div class="mt-4">
                  <div class="flex items-center">
                    <input 
                      id="is_active" 
                      type="checkbox" 
                      v-model="form.is_active" 
                      class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                    />
                    <label for="is_active" class="ml-2 block text-sm text-gray-900">Active</label>
                  </div>
                </div>

                <!-- Action Buttons -->
                <div class="mt-6 flex justify-end space-x-3">
                  <button 
                    type="button" 
                    class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
                    @click="showAddModal ? (showAddModal = false) : (showEditModal = false)"
                  >
                    Cancel
                  </button>
                  <button 
                    type="button" 
                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700"
                    @click="showAddModal ? createSku() : updateSku()"
                  >
                    {{ showAddModal ? 'Create' : 'Update' }}
                  </button>
                </div>
              </DialogPanel>
            </TransitionChild>
          </div>
        </div>
      </Dialog>
    </TransitionRoot>

    <!-- Delete Confirmation Modal -->
    <TransitionRoot appear :show="showDeleteModal" as="template">
      <Dialog as="div" @close="showDeleteModal = false" class="relative z-10">
        <TransitionChild
          as="template"
          enter="duration-300 ease-out"
          enter-from="opacity-0"
          enter-to="opacity-100"
          leave="duration-200 ease-in"
          leave-from="opacity-100"
          leave-to="opacity-0"
        >
          <div class="fixed inset-0 bg-black bg-opacity-25" />
        </TransitionChild>

        <div class="fixed inset-0 overflow-y-auto">
          <div class="flex min-h-full items-center justify-center p-4 text-center">
            <TransitionChild
              as="template"
              enter="duration-300 ease-out"
              enter-from="opacity-0 scale-95"
              enter-to="opacity-100 scale-100"
              leave="duration-200 ease-in"
              leave-from="opacity-100 scale-100"
              leave-to="opacity-0 scale-95"
            >
              <DialogPanel class="w-full max-w-md transform overflow-hidden rounded-2xl bg-white p-6 text-left align-middle shadow-xl transition-all">
                <DialogTitle as="h3" class="text-lg font-medium leading-6 text-gray-900">
                  Confirm Deletion
                </DialogTitle>
                
                <div class="mt-2">
                  <p class="text-sm text-gray-500">
                    Are you sure you want to delete the SKU "{{ currentSku?.sku_name }}" ({{ currentSku?.sku }})?
                    This action cannot be undone.
                  </p>
                </div>
                
                <div class="mt-6 flex justify-end space-x-3">
                  <button 
                    type="button" 
                    class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
                    @click="showDeleteModal = false"
                  >
                    Cancel
                  </button>
                  <button 
                    type="button" 
                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700"
                    @click="deleteSku"
                  >
                    Delete
                  </button>
                </div>
              </DialogPanel>
            </TransitionChild>
          </div>
        </div>
      </Dialog>
    </TransitionRoot>
  </AppLayout>
</template>

<script setup>
import { ref, computed, onMounted, nextTick } from 'vue';
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import axios from 'axios';
import debounce from 'lodash/debounce';
import { useToast } from '@/Composables/useToast';
import { Dialog, DialogPanel, DialogTitle, TransitionChild, TransitionRoot } from '@headlessui/vue';

// Toast notification
const { showToast } = useToast();

// Data
const skus = ref({ data: [] });
const categories = ref([]);
const types = ref([]);
const uoms = ref([]);

// UI state
const loading = ref(false);
const error = ref(null);
const showAddModal = ref(false);
const showEditModal = ref(false);
const showDeleteModal = ref(false);
const currentSku = ref(null);

// Form data
const form = ref({
  sku: '',
  sku_name: '',
  category_code: '',
  type: '',
  uom: '',
  sts: 'ACT',
  boh: 0,
  fpo: 0,
  rol: 0,
  total_part: 0,
  min_qty: 0,
  max_qty: 0,
  additional_name: '',
  part_number1: '',
  part_number2: '',
  part_number3: '',
  is_active: true
});

// Form errors
const formErrors = ref({});

// Filters
const filters = ref({
  search: '',
  category_code: '',
  type: ''
});

// Create debounced function for search input
const debouncedFetch = debounce(() => {
  fetchSkus();
}, 500);

// Pagination helpers
const pageRange = computed(() => {
  if (!skus.value || !skus.value.last_page) return [];
  
  const totalPages = skus.value.last_page;
  const currentPage = skus.value.current_page;
  
  let range = [];
  
  if (totalPages <= 7) {
    // Show all pages if 7 or fewer
    range = Array.from({ length: totalPages }, (_, i) => i + 1);
  } else {
    // Always include first page, last page, and pages around current
    range.push(1);
    
    if (currentPage > 3) {
      range.push('...');
    }
    
    // Pages around current
    const start = Math.max(2, currentPage - 1);
    const end = Math.min(totalPages - 1, currentPage + 1);
    
    for (let i = start; i <= end; i++) {
      range.push(i);
    }
    
    if (currentPage < totalPages - 2) {
      range.push('...');
    }
    
    range.push(totalPages);
  }
  
  return range;
});

// Methods
const fetchSkus = async () => {
  try {
    loading.value = true;
    error.value = null;
    
    const params = {
      page: skus.value.current_page || 1,
      ...filters.value
    };
    
    const response = await axios.get('/api/material-management/system-requirement/inventory-setup/sku', { params });
    skus.value = response.data;
  } catch (err) {
    console.error('Error fetching SKUs:', err);
    error.value = 'Failed to load SKUs. Please try again.';
  } finally {
    loading.value = false;
  }
};

const fetchCategories = async () => {
  try {
    const response = await axios.get('/api/material-management/system-requirement/inventory-setup/category');
    categories.value = response.data;
  } catch (err) {
    console.error('Error fetching categories:', err);
    showToast('Failed to load categories', 'error');
  }
};

const fetchTypes = async () => {
  try {
    const response = await axios.get('/api/material-management/system-requirement/inventory-setup/sku-types');
    types.value = response.data;
  } catch (err) {
    console.error('Error fetching SKU types:', err);
  }
};

const fetchUoms = async () => {
  try {
    const response = await axios.get('/api/material-management/system-requirement/inventory-setup/units');
    uoms.value = response.data;
  } catch (err) {
    console.error('Error fetching UOMs:', err);
  }
};

const resetFilters = () => {
  filters.value = {
    search: '',
    category_code: '',
    type: ''
  };
  fetchSkus();
};

const formatNumber = (value) => {
  if (value === null || value === undefined) return '0.000';
  return parseFloat(value).toFixed(3);
};

const createSku = async () => {
  try {
    formErrors.value = {};
    
    const response = await axios.post('/api/material-management/system-requirement/inventory-setup/sku', form.value);
    
    showToast('SKU created successfully', 'success');
    resetForm();
    showAddModal.value = false;
    fetchSkus();
  } catch (err) {
    console.error('Error creating SKU:', err);
    
    if (err.response && err.response.data && err.response.data.errors) {
      formErrors.value = err.response.data.errors;
    } else {
      showToast('Failed to create SKU', 'error');
    }
  }
};

const editSku = (sku) => {
  currentSku.value = sku;
  form.value = { ...sku };
  showEditModal.value = true;
};

const updateSku = async () => {
  try {
    formErrors.value = {};
    
    const response = await axios.put(`/api/material-management/system-requirement/inventory-setup/sku/${currentSku.value.sku}`, form.value);
    
    showToast('SKU updated successfully', 'success');
    showEditModal.value = false;
    fetchSkus();
  } catch (err) {
    console.error('Error updating SKU:', err);
    
    if (err.response && err.response.data && err.response.data.errors) {
      formErrors.value = err.response.data.errors;
    } else {
      showToast('Failed to update SKU', 'error');
    }
  }
};

const confirmDelete = (sku) => {
  currentSku.value = sku;
  showDeleteModal.value = true;
};

const deleteSku = async () => {
  try {
    await axios.delete(`/api/material-management/system-requirement/inventory-setup/sku/${currentSku.value.sku}`);
    
    showToast('SKU deleted successfully', 'success');
    showDeleteModal.value = false;
    fetchSkus();
  } catch (err) {
    console.error('Error deleting SKU:', err);
    showToast('Failed to delete SKU', 'error');
  }
};

const toggleActive = async (sku) => {
  try {
    await axios.patch(`/api/material-management/system-requirement/inventory-setup/sku/${sku.sku}/toggle-active`);
    
    const status = sku.is_active ? 'disabled' : 'enabled';
    showToast(`SKU ${status} successfully`, 'success');
    
    // Update the SKU in the data
    sku.is_active = !sku.is_active;
  } catch (err) {
    console.error('Error toggling SKU status:', err);
    showToast('Failed to update SKU status', 'error');
  }
};

const seedSampleData = async () => {
  try {
    loading.value = true;
    
    await axios.post('/api/material-management/system-requirement/inventory-setup/sku/seed');
    showToast('Sample SKUs seeded successfully', 'success');
    
    fetchSkus();
  } catch (err) {
    console.error('Error seeding sample data:', err);
    showToast('Failed to seed sample data', 'error');
  } finally {
    loading.value = false;
  }
};

const resetForm = () => {
  form.value = {
    sku: '',
    sku_name: '',
    category_code: '',
    type: '',
    uom: '',
    sts: 'ACT',
    boh: 0,
    fpo: 0,
    rol: 0,
    total_part: 0,
    min_qty: 0,
    max_qty: 0,
    additional_name: '',
    part_number1: '',
    part_number2: '',
    part_number3: '',
    is_active: true
  };
  formErrors.value = {};
};

const changePage = (page) => {
  if (page < 1 || page > skus.value.last_page) return;
  
  // If it's a valid page number, update the current page and fetch SKUs
  skus.value.current_page = page;
  fetchSkus();
};

// Lifecycle hooks
onMounted(async () => {
  try {
    await Promise.all([
      fetchSkus(),
      fetchCategories(),
      fetchTypes(),
      fetchUoms()
    ]);
  } catch (err) {
    console.error('Error during initial data loading:', err);
  }
});
</script>

<style scoped>
/* Optional: Add any specific styling you need */
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>
