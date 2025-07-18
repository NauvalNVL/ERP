<template>
  <AppLayout :header="'Define Product Design'">
    <Head title="Define Product Design" />
    
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-800 p-6 rounded-t-lg shadow-md">
      <h2 class="text-2xl font-bold text-white mb-2 flex items-center">
        <i class="fas fa-drafting-compass mr-3"></i> Define Product Design
      </h2>
      <p class="text-blue-100">Manage product designs for manufacturing processes</p>
    </div>

    <div class="bg-white rounded-b-lg shadow-md p-6 mb-6">
      <div class="flex flex-col md:flex-row gap-6">
        <!-- Main Content -->
        <div class="flex-1">
          <!-- Action Bar -->
          <div class="mb-6 flex flex-col md:flex-row gap-4 justify-between items-start md:items-center">
            <div class="flex-1 w-full">
              <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
                  <i class="fas fa-search"></i>
                </span>
                <input type="text" v-model="searchQuery" placeholder="Search by design code, name or product..."
                  class="pl-10 pr-4 py-2 w-full border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
              </div>
            </div>
            <div class="flex gap-2 w-full md:w-auto">
              <button @click="addNew" class="btn-primary flex-1 md:flex-none">
                <i class="fas fa-plus-circle mr-2"></i> New Design
              </button>
              <button @click="refreshData" class="btn-secondary flex-1 md:flex-none">
                <i class="fas fa-sync-alt mr-2"></i> Refresh
              </button>
              <button @click="seedData" class="btn-info flex-1 md:flex-none" :disabled="loading">
                <i class="fas fa-database mr-2"></i> Seed Data
              </button>
            </div>
            </div>

          <!-- Loading Overlay -->
          <div v-if="loading" class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white p-4 rounded-lg shadow-lg flex items-center space-x-3">
              <svg class="animate-spin h-8 w-8 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
              <span class="text-gray-700 font-medium">Processing...</span>
            </div>
            </div>

          <!-- Table Section -->
          <div class="bg-white rounded-lg overflow-hidden border border-gray-200">
            <div class="overflow-x-auto">
              <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                  <tr>
                    <th @click="sortBy('pd_code')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                      <div class="flex items-center">
                        Product Design <i class="fas fa-sort ml-1"></i>
                      </div>
                      </th>
                    <th @click="sortBy('pd_name')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                      <div class="flex items-center">
                        Name <i class="fas fa-sort ml-1"></i>
                      </div>
                      </th>
                    <th @click="sortBy('product')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                      <div class="flex items-center">
                        Product <i class="fas fa-sort ml-1"></i>
                      </div>
                      </th>
                    <th @click="sortBy('score')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                      <div class="flex items-center">
                        Compute <i class="fas fa-sort ml-1"></i>
                      </div>
                      </th>
                    </tr>
                  </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                  <tr v-if="loading" class="animate-pulse">
                    <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">
                      <div class="flex justify-center items-center space-x-2">
                        <i class="fas fa-spinner fa-spin"></i>
                        <span>Loading product designs...</span>
                      </div>
                      </td>
                  </tr>
                  <tr v-else-if="paginatedDesigns.length === 0" class="hover:bg-gray-50">
                    <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">
                      No product designs found. Try adjusting your search or create a new design.
                      </td>
                    </tr>
                  <tr v-for="design in paginatedDesigns" :key="design.pd_code" 
                      @click="selectDesign(design)" 
                      :class="{'bg-blue-50': selectedDesign && selectedDesign.pd_code === design.pd_code}"
                      class="hover:bg-gray-50 cursor-pointer">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ design.pd_code || '-' }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ design.pd_name || '-' }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ design.product || '-' }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 cursor-pointer hover:bg-blue-100 active:bg-blue-200 transition-all duration-150 transform hover:scale-105 border border-transparent hover:border-blue-300 rounded-md" 
                      @click.stop="toggleCompute(design)"
                      title="Click to toggle Compute status"
                      :class="{'opacity-75': toggleLoading[design.pd_code]}">
                      <div class="flex items-center justify-between">
                        <span v-if="toggleLoading[design.pd_code]" class="text-blue-500 font-medium flex items-center">
                          <i class="fas fa-spinner fa-spin mr-1"></i> Updating...
                        </span>
                        <span v-else-if="design.score === 'Yes'" class="text-green-600 font-medium flex items-center">
                          <i class="fas fa-check-circle mr-1"></i> Yes
                        </span>
                        <span v-else class="text-red-600 font-medium flex items-center">
                          <i class="fas fa-times-circle mr-1"></i> No
                        </span>
                        <i v-if="!toggleLoading[design.pd_code]" class="fas fa-exchange-alt text-blue-500 ml-2"></i>
                      </div>
                      </td>
                    </tr>
                  </tbody>
                </table>
            </div>
          </div>

          <!-- Pagination Controls -->
          <div class="mt-4 flex justify-between items-center text-sm text-gray-600">
            <div>
              <span>Showing {{ paginatedDesigns.length }} of {{ filteredDesigns.length }} designs</span>
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
              <button :disabled="currentPage === totalPages" @click="currentPage++" class="px-2 py-1 border rounded hover:bg-gray-200 disabled:opacity-50">
                <i class="fas fa-chevron-right"></i>
              </button>
            </div>
          </div>
              </div>

        <!-- Side Panel with Details -->
        <div class="w-full md:w-96 bg-gray-50 rounded-lg p-4 border border-gray-200">
          <div v-if="selectedDesign" class="mb-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-3 flex items-center">
              <i class="fas fa-info-circle mr-2 text-blue-500"></i> Design Details
            </h3>
            <div class="space-y-3">
              <div class="flex justify-between border-b border-gray-200 pb-2">
                <span class="text-gray-600">Design Code:</span>
                <span class="font-medium text-gray-900">{{ selectedDesign.pd_code || '-' }}</span>
              </div>
              <div class="flex justify-between border-b border-gray-200 pb-2">
                <span class="text-gray-600">Design Name:</span>
                <span class="font-medium text-gray-900">{{ selectedDesign.pd_name || '-' }}</span>
              </div>
              <div class="flex justify-between border-b border-gray-200 pb-2">
                <span class="text-gray-600">Product:</span>
                <span class="font-medium bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-xs">{{ selectedDesign.product || '-' }}</span>
              </div>
              <div class="flex justify-between border-b border-gray-200 pb-2">
                <span class="text-gray-600">Design Type:</span>
                <span class="font-medium text-gray-900">{{ selectedDesign.pd_design_type || '-' }}</span>
              </div>
              <div class="flex justify-between border-b border-gray-200 pb-2">
                <span class="text-gray-600">IDC:</span>
                <span class="font-medium text-gray-900">{{ selectedDesign.idc || '-' }}</span>
              </div>
              <div class="flex justify-between border-b border-gray-200 pb-2">
                <span class="text-gray-600">Compute:</span>
                <span class="font-medium" :class="{'text-green-600': selectedDesign.score === 'Yes', 'text-red-600': selectedDesign.score !== 'Yes'}">
                  {{ selectedDesign.score === 'Yes' ? 'Yes' : 'No' }}
                </span>
              </div>
              <div class="flex justify-between border-b border-gray-200 pb-2">
                <span class="text-gray-600">Joint:</span>
                <span class="font-medium" :class="{'text-green-600': selectedDesign.joint === 'Yes', 'text-red-600': selectedDesign.joint !== 'Yes'}">
                  {{ selectedDesign.joint || 'No' }}
                </span>
              </div>
              <div class="flex justify-between border-b border-gray-200 pb-2">
                <span class="text-gray-600">Joint to Print:</span>
                <span class="font-medium" :class="{'text-green-600': selectedDesign.joint_to_print === 'Yes', 'text-red-600': selectedDesign.joint_to_print !== 'Yes'}">
                  {{ selectedDesign.joint_to_print || 'No' }}
                </span>
              </div>
              <div class="flex justify-between border-b border-gray-200 pb-2">
                <span class="text-gray-600">PCS to Joint:</span>
                <span class="font-medium text-gray-900">{{ selectedDesign.pcs_to_joint || '-' }}</span>
              </div>
              <div class="flex justify-between border-b border-gray-200 pb-2">
                <span class="text-gray-600">Score:</span>
                <span class="font-medium" :class="{'text-green-600': selectedDesign.score === 'Yes', 'text-red-600': selectedDesign.score !== 'Yes'}">
                  {{ selectedDesign.score || 'No' }}
                </span>
              </div>
              <div class="flex justify-between border-b border-gray-200 pb-2">
                <span class="text-gray-600">Slot:</span>
                <span class="font-medium" :class="{'text-green-600': selectedDesign.slot === 'Yes', 'text-red-600': selectedDesign.slot !== 'Yes'}">
                  {{ selectedDesign.slot || 'No' }}
                </span>
              </div>
              <div class="flex justify-between border-b border-gray-200 pb-2">
                <span class="text-gray-600">Flute Style:</span>
                <span class="font-medium text-gray-900">{{ selectedDesign.flute_style || '-' }}</span>
              </div>
              <div class="flex justify-between border-b border-gray-200 pb-2">
                <span class="text-gray-600">Print Flute:</span>
                <span class="font-medium" :class="{'text-green-600': selectedDesign.print_flute === 'Yes', 'text-red-600': selectedDesign.print_flute !== 'Yes'}">
                  {{ selectedDesign.print_flute || 'No' }}
                </span>
              </div>
              <div class="flex justify-between border-b border-gray-200 pb-2">
                <span class="text-gray-600">Input Weight:</span>
                <span class="font-medium" :class="{'text-green-600': selectedDesign.input_weight === 'Yes', 'text-red-600': selectedDesign.input_weight !== 'Yes'}">
                  {{ selectedDesign.input_weight || 'No' }}
                </span>
              </div>
            </div>
          </div>
          <div v-else class="flex flex-col items-center justify-center h-64 text-center">
            <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mb-4">
              <i class="fas fa-drafting-compass text-blue-500 text-2xl"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-1">No Design Selected</h3>
            <p class="text-gray-500 mb-4">Select a design from the table to view details</p>
            <button @click="addNew" class="btn-primary">
              <i class="fas fa-plus-circle mr-1"></i> Create New Design
            </button>
          </div>
        </div>
      </div>
              </div>

    <!-- Design Form Modal -->
    <div v-if="showFormModal" class="fixed inset-0 flex items-center justify-center z-50">
      <div class="absolute inset-0 bg-black opacity-50" @click="showFormModal = false"></div>
      <div class="bg-white rounded-xl shadow-2xl w-full max-w-4xl z-10 relative transform transition-all duration-300 ease-in-out">
        <!-- Modal Header -->
        <div class="flex items-center justify-between p-6 border-b border-gray-200 bg-gradient-to-r from-blue-600 to-indigo-800 rounded-t-xl">
          <div class="flex items-center">
            <div class="bg-white/20 p-2 rounded-lg mr-3">
              <i class="fas fa-drafting-compass text-white text-xl"></i>
            </div>
            <h3 class="text-2xl font-bold text-white">{{ editMode ? 'Edit' : 'New' }} Product Design</h3>
          </div>
          <button @click="showFormModal = false" class="bg-white/20 hover:bg-white/30 text-white p-2 rounded-lg transition-all duration-200">
            <i class="fas fa-times"></i>
          </button>
                </div>

        <!-- Form Content -->
        <div class="p-6 max-h-[70vh] overflow-y-auto">
          <form @submit.prevent="saveDesign">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                  <!-- Product Design Code -->
              <div class="bg-gray-50 p-4 rounded-lg shadow-sm">
                <label class="text-sm font-semibold text-gray-700 mb-2 flex items-center">
                  <i class="fas fa-barcode text-blue-500 mr-2"></i>
                  Product Design<span class="text-red-500">*</span>
                </label>
                <input type="text" v-model="form.pd_code" :disabled="editMode"
                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                      :class="{'bg-gray-100': editMode}"
                  placeholder="Enter product design">
                <div v-if="errors.pd_code" class="text-red-500 text-xs mt-1">{{ errors.pd_code }}</div>
                  </div>

                  <!-- Name -->
              <div class="bg-gray-50 p-4 rounded-lg shadow-sm">
                <label class="text-sm font-semibold text-gray-700 mb-2 flex items-center">
                  <i class="fas fa-tag text-blue-500 mr-2"></i>
                  Name<span class="text-red-500">*</span>
                </label>
                <input type="text" v-model="form.pd_name"
                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                  placeholder="Enter design name">
                <div v-if="errors.pd_name" class="text-red-500 text-xs mt-1">{{ errors.pd_name }}</div>
              </div>

              <!-- Product -->
              <div class="bg-gray-50 p-4 rounded-lg shadow-sm">
                <label class="text-sm font-semibold text-gray-700 mb-2 flex items-center">
                  <i class="fas fa-box text-blue-500 mr-2"></i>
                  Product<span class="text-red-500">*</span>
                </label>
                <input type="text" v-model="form.product"
                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                  placeholder="Enter product code">
                <div v-if="errors.product" class="text-red-500 text-xs mt-1">{{ errors.product }}</div>
              </div>

              <!-- Design Type -->
              <div class="bg-gray-50 p-4 rounded-lg shadow-sm">
                <label class="text-sm font-semibold text-gray-700 mb-2 flex items-center">
                  <i class="fas fa-shapes text-blue-500 mr-2"></i>
                  Design Type
                </label>
                <input type="text" v-model="form.pd_design_type"
                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                  placeholder="Enter design type">
                  </div>

              <!-- Flute Style -->
              <div class="bg-gray-50 p-4 rounded-lg shadow-sm">
                <label class="text-sm font-semibold text-gray-700 mb-2 flex items-center">
                  <i class="fas fa-layer-group text-blue-500 mr-2"></i>
                  Flute Style
                </label>
                <input type="text" v-model="form.flute_style"
                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                  placeholder="Enter flute style">
                  </div>

                  <!-- Compute Flag -->
              <div class="bg-gray-50 p-4 rounded-lg shadow-sm">
                <label class="text-sm font-semibold text-gray-700 mb-2 flex items-center">
                  <i class="fas fa-calculator text-blue-500 mr-2"></i>
                  Compute
                </label>
                <select v-model="form.score"
                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 appearance-none bg-white bg-no-repeat bg-right pr-10"
                  style="background-image: url('data:image/svg+xml;charset=US-ASCII,<svg xmlns=%22http://www.w3.org/2000/svg%22 width=%2224%22 height=%2224%22 viewBox=%220 0 24 24%22 fill=%22none%22 stroke=%22%23666%22 stroke-width=%222%22 stroke-linecap=%22round%22 stroke-linejoin=%22round%22><polyline points=%226 9 12 15 18 9%22></polyline></svg>'); background-size: 16px;">
                    <option value="Yes">Yes</option>
                    <option value="No">No</option>
                </select>
              </div>

              <!-- Joint -->
              <div class="bg-gray-50 p-4 rounded-lg shadow-sm">
                <label class="text-sm font-semibold text-gray-700 mb-2 flex items-center">
                  <i class="fas fa-link text-blue-500 mr-2"></i>
                  Joint
                </label>
                <select v-model="form.joint"
                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 appearance-none bg-white bg-no-repeat bg-right pr-10"
                  style="background-image: url('data:image/svg+xml;charset=US-ASCII,<svg xmlns=%22http://www.w3.org/2000/svg%22 width=%2224%22 height=%2224%22 viewBox=%220 0 24 24%22 fill=%22none%22 stroke=%22%23666%22 stroke-width=%222%22 stroke-linecap=%22round%22 stroke-linejoin=%22round%22><polyline points=%226 9 12 15 18 9%22></polyline></svg>'); background-size: 16px;">
                    <option value="Yes">Yes</option>
                    <option value="No">No</option>
                </select>
              </div>

              <!-- Joint to Print -->
              <div class="bg-gray-50 p-4 rounded-lg shadow-sm">
                <label class="text-sm font-semibold text-gray-700 mb-2 flex items-center">
                  <i class="fas fa-print text-blue-500 mr-2"></i>
                  Joint to Print
                </label>
                <select v-model="form.joint_to_print"
                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 appearance-none bg-white bg-no-repeat bg-right pr-10"
                  style="background-image: url('data:image/svg+xml;charset=US-ASCII,<svg xmlns=%22http://www.w3.org/2000/svg%22 width=%2224%22 height=%2224%22 viewBox=%220 0 24 24%22 fill=%22none%22 stroke=%22%23666%22 stroke-width=%222%22 stroke-linecap=%22round%22 stroke-linejoin=%22round%22><polyline points=%226 9 12 15 18 9%22></polyline></svg>'); background-size: 16px;">
                      <option value="Yes">Yes</option>
                      <option value="No">No</option>
                    </select>
                  </div>
                </div>

            <!-- Form Footer with Buttons -->
            <div class="flex justify-end space-x-3 border-t border-gray-200 pt-5 mt-4">
              <button type="button" @click="showFormModal = false" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg shadow transition-all duration-200 flex items-center">
                <i class="fas fa-times mr-2"></i> Cancel
                  </button>
              <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg shadow transition-all duration-200 flex items-center" :disabled="loading">
                <i class="fas fa-save mr-2"></i> {{ editMode ? 'Update' : 'Create' }}
                <span v-if="loading" class="ml-2 animate-spin"><i class="fas fa-spinner"></i></span>
                  </button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div v-if="showDeleteModal" class="fixed z-10 inset-0 overflow-y-auto">
      <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
          <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
          <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
            <div class="sm:flex sm:items-start">
              <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                <i class="fas fa-exclamation-triangle text-red-600"></i>
              </div>
              <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Delete Product Design</h3>
                <div class="mt-2">
                  <p class="text-sm text-gray-500">
                    Are you sure you want to delete this product design? This action cannot be undone.
                  </p>
                </div>
              </div>
            </div>
          </div>
          <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
            <button @click="deleteDesign" type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
              Delete
            </button>
            <button @click="showDeleteModal = false" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
              Cancel
            </button>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Toast Messages -->
    <div v-if="toast.show" class="fixed bottom-4 right-4 px-4 py-2 rounded-lg shadow-lg z-50"
      :class="{
        'bg-green-500': toast.type === 'success',
        'bg-red-500': toast.type === 'error',
        'bg-blue-500': toast.type === 'info',
      }"
    >
      <div class="flex items-center text-white">
        <span class="mr-2">
          <i :class="{
            'fas fa-check-circle': toast.type === 'success',
            'fas fa-exclamation-circle': toast.type === 'error',
            'fas fa-info-circle': toast.type === 'info',
          }"></i>
        </span>
        <span>{{ toast.message }}</span>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, onMounted, reactive, computed, watch } from 'vue';
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import axios from 'axios';
import { useToast } from '@/Composables/useToast';

// State
const productDesigns = ref([]);
const products = ref([]);
const selectedDesign = ref(null);
const editMode = ref(false);
const loading = ref(false);
const isLoading = ref(true);
const showDeleteModal = ref(false);
const showFormModal = ref(false);
const designToDelete = ref(null);
const searchQuery = ref('');
const errors = ref({});
const toggleLoading = ref({});
const currentPage = ref(1);
const itemsPerPage = ref(10);
const sortOrder = ref({
  field: 'pd_code',
  direction: 'asc'
});

// Use toast composable
const toast = useToast();

// Form data
const form = reactive({
  pd_code: '',
  pd_name: '',
  pd_design_type: '',
  idc: '',
  product: '',
  joint: 'No',
  joint_to_print: 'No',
  pcs_to_joint: 'No',
  score: 'No',
  slot: 'No',
  flute_style: '',
  print_flute: '',
  input_weight: ''
});

// Filtered designs based on search query and sorting
const filteredDesigns = computed(() => {
  let result = productDesigns.value;
  
  // Apply search filter
  if (searchQuery.value) {
    const term = searchQuery.value.toLowerCase();
    result = result.filter(design => 
      (design.pd_code && design.pd_code.toLowerCase().includes(term)) ||
      (design.pd_name && design.pd_name.toLowerCase().includes(term)) ||
      (design.product && design.product.toLowerCase().includes(term))
    );
  }
  
  // Apply sorting
  result = [...result].sort((a, b) => {
    const field = sortOrder.value.field;
    const direction = sortOrder.value.direction === 'asc' ? 1 : -1;
    
    if ((a[field] || '') < (b[field] || '')) return -1 * direction;
    if ((a[field] || '') > (b[field] || '')) return 1 * direction;
    return 0;
  });
  
  return result;
});

// Pagination computed properties
const paginatedDesigns = computed(() => {
  const start = (currentPage.value - 1) * itemsPerPage.value;
  const end = start + itemsPerPage.value;
  return filteredDesigns.value.slice(start, end);
});

const totalPages = computed(() => {
  return Math.max(1, Math.ceil(filteredDesigns.value.length / itemsPerPage.value));
});

// Watch for changes in filtered designs to update pagination
watch(filteredDesigns, () => {
  currentPage.value = 1;
});

// Watch for changes in items per page
watch(itemsPerPage, () => {
  currentPage.value = 1;
});

// Fetch data on component mount
onMounted(async () => {
  await Promise.all([
    fetchProductDesigns(),
    fetchProducts()
  ]);
});

// Fetch product designs from API
const fetchProductDesigns = async () => {
  try {
    isLoading.value = true;
    const response = await axios.get('/api/product-designs');
    productDesigns.value = response.data;
    toast.success('Product designs loaded successfully');
  } catch (error) {
    console.error('Error fetching product designs:', error);
    toast.error('Error loading product designs');
  } finally {
    isLoading.value = false;
  }
};

// Fetch products
const fetchProducts = async () => {
  try {
    const response = await axios.get('/api/products');
    products.value = response.data;
  } catch (error) {
    console.error('Error fetching products:', error);
    toast.error('Error loading products');
  }
};

// Validate form
const validateForm = () => {
  errors.value = {};
  let isValid = true;
  
  if (!form.pd_code) {
    errors.value.pd_code = 'Product design is required';
    isValid = false;
  }
  
  if (!form.pd_name) {
    errors.value.pd_name = 'Design name is required';
    isValid = false;
  }
  
  if (!form.product) {
    errors.value.product = 'Product is required';
    isValid = false;
  }
  
  return isValid;
};

// Function to refresh data
const refreshData = () => {
  selectedDesign.value = null;
  fetchProductDesigns();
};

// Function to select a design
const selectDesign = (design) => {
  selectedDesign.value = design;
};

// Function to open the new design form modal
const addNew = () => {
  resetForm();
  showFormModal.value = true;
};

// Function to sort table by a given field
const sortBy = (field) => {
  if (sortOrder.value.field === field) {
    // Toggle direction if same field
    sortOrder.value.direction = sortOrder.value.direction === 'asc' ? 'desc' : 'asc';
  } else {
    // Change field and reset direction
    sortOrder.value.field = field;
    sortOrder.value.direction = 'asc';
  }
};

// Reset form to default values
const resetForm = () => {
  Object.assign(form, {
    pd_code: '',
    pd_name: '',
    pd_design_type: '',
    idc: '',
    product: '',
    joint: 'No',
    joint_to_print: 'No',
    pcs_to_joint: 'No',
    score: 'No',
    slot: 'No',
    flute_style: '',
    print_flute: '',
    input_weight: ''
  });
  editMode.value = false;
  errors.value = {};
};

// Function to toggle compute status directly from the table
const toggleCompute = async (design) => {
  // Prevent multiple clicks
  if (toggleLoading.value[design.pd_code]) return;
  
  // Set loading state for this specific design
  toggleLoading.value[design.pd_code] = true;
  const newComputeValue = design.score === 'Yes' ? 'No' : 'Yes';
  
  try {
    // Create a copy of the design object with all required fields for the update
    const designUpdate = {
    pd_code: design.pd_code,
    pd_name: design.pd_name,
    pd_design_type: design.pd_design_type || '',
      product: design.product,
    idc: design.idc || '',
    joint: design.joint || 'No',
    joint_to_print: design.joint_to_print || 'No',
    pcs_to_joint: design.pcs_to_joint || 'No',
      score: newComputeValue,
    slot: design.slot || 'No',
    flute_style: design.flute_style || '',
    print_flute: design.print_flute || '',
    input_weight: design.input_weight || ''
    };
    
    const response = await axios.put(`/api/product-designs/${design.pd_code}`, designUpdate);
    
    if (response.status === 200) {
      // Update the design in the local array
      const index = productDesigns.value.findIndex(d => d.pd_code === design.pd_code);
      if (index !== -1) {
        productDesigns.value[index].score = newComputeValue;
        
        // Also update selected design if it's the same one
        if (selectedDesign.value?.pd_code === design.pd_code) {
          selectedDesign.value.score = newComputeValue;
        }
      }
      
      toast.success(`Compute value updated to ${newComputeValue}`);
    } else {
      throw new Error(response.data.message || 'Unknown error');
    }
  } catch (error) {
    console.error('Error updating compute status:', error);
    toast.error(error.response?.data?.message || 'Failed to update compute status');
  } finally {
    // Clear loading state
    toggleLoading.value[design.pd_code] = false;
  }
};

// Edit a product design
const editSelected = () => {
  if (!selectedDesign.value) return;
  
  editMode.value = true;
  
  Object.assign(form, {
    pd_code: selectedDesign.value.pd_code,
    pd_name: selectedDesign.value.pd_name,
    pd_design_type: selectedDesign.value.pd_design_type || '',
    idc: selectedDesign.value.idc || '',
    product: selectedDesign.value.product,
    joint: selectedDesign.value.joint || 'No',
    joint_to_print: selectedDesign.value.joint_to_print || 'No',
    pcs_to_joint: selectedDesign.value.pcs_to_joint || 'No',
    score: selectedDesign.value.score || 'No',
    slot: selectedDesign.value.slot || 'No',
    flute_style: selectedDesign.value.flute_style || '',
    print_flute: selectedDesign.value.print_flute || '',
    input_weight: selectedDesign.value.input_weight || ''
  });
  
  errors.value = {};
  showFormModal.value = true;
};

// Save or update a product design
const saveDesign = async () => {
  if (!validateForm()) {
    return;
  }
  
  try {
    loading.value = true;
    
    const designData = {
      pd_code: form.pd_code,
      pd_name: form.pd_name,
      pd_design_type: form.pd_design_type,
      idc: form.idc,
      product: form.product,
      joint: form.joint,
      joint_to_print: form.joint_to_print,
      pcs_to_joint: form.pcs_to_joint,
      score: form.score,
      slot: form.slot,
      flute_style: form.flute_style,
      print_flute: form.print_flute,
      input_weight: form.input_weight
    };
    
    if (editMode.value) {
      // Update existing design
      const response = await axios.put(`/api/product-designs/${form.pd_code}`, designData);
      if (response.status === 200) {
        toast.success('Product design updated successfully');
      } else {
        toast.error('Failed to update product design');
      }
    } else {
      // Create new design
      const response = await axios.post('/api/product-designs', designData);
      if (response.status === 201) {
        toast.success('Product design created successfully');
      } else {
        toast.error('Failed to create product design');
      }
    }
    
    // Refresh the data
    await fetchProductDesigns();
    
    // Reset the form and close modal
    resetForm();
    showFormModal.value = false;
  } catch (error) {
    console.error('Error saving product design:', error);
    
    // Handle validation errors
    if (error.response && error.response.data && error.response.data.errors) {
      errors.value = error.response.data.errors;
      toast.error('Please correct the errors in the form');
    } else {
      toast.error('An error occurred while saving');
    }
  } finally {
    loading.value = false;
  }
};

// Confirm deletion of a design
const confirmDelete = () => {
  if (!selectedDesign.value) return;
  designToDelete.value = selectedDesign.value;
  showDeleteModal.value = true;
};

// Delete a design
const deleteDesign = async () => {
  if (!designToDelete.value) return;
  
  try {
    loading.value = true;
    const response = await axios.delete(`/api/product-designs/${designToDelete.value.pd_code}`);
    
    if (response.status === 200) {
      toast.success('Product design deleted successfully');
    } else {
      toast.error('Failed to delete product design');
    }
    
    // Refresh the data
    await fetchProductDesigns();
    
    // Clear the selected design if it was the one deleted
    if (selectedDesign.value && selectedDesign.value.pd_code === designToDelete.value.pd_code) {
      selectedDesign.value = null;
    }
    
    // Close the modal
    showDeleteModal.value = false;
    designToDelete.value = null;
  } catch (error) {
    console.error('Error deleting product design:', error);
    toast.error('Error deleting product design');
  } finally {
    loading.value = false;
  }
};

// Seed sample data
const seedData = async () => {
  if (!confirm('Are you sure you want to seed sample data? This will add example product designs to the database.')) {
    return;
  }
  
  try {
    loading.value = true;
    const response = await axios.post('/api/product-designs/seed');
    
    if (response.status === 200) {
      toast.success('Sample data seeded successfully');
      await fetchProductDesigns();
    } else {
      toast.error('Failed to seed sample data');
    }
  } catch (error) {
    console.error('Error seeding data:', error);
    toast.error('Error seeding sample data');
  } finally {
    loading.value = false;
  }
};
</script>

<style scoped>
/* Button styles */
.btn-primary {
  @apply bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg shadow transition
    flex items-center justify-center whitespace-nowrap;
}

.btn-secondary {
  @apply bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg shadow transition
    flex items-center justify-center whitespace-nowrap;
}

.btn-info {
  @apply bg-cyan-600 hover:bg-cyan-700 text-white px-4 py-2 rounded-lg shadow transition
    flex items-center justify-center whitespace-nowrap;
}

.btn-danger {
  @apply bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg shadow transition
    flex items-center justify-center whitespace-nowrap;
}

.btn-blue {
  @apply bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg shadow transition
    flex items-center justify-center whitespace-nowrap;
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
.overflow-x-auto {
  scrollbar-width: thin;
  scrollbar-color: rgba(156, 163, 175, 0.5) transparent;
}

.overflow-x-auto::-webkit-scrollbar {
  height: 8px;
}

.overflow-x-auto::-webkit-scrollbar-track {
  background: transparent;
}

.overflow-x-auto::-webkit-scrollbar-thumb {
  background-color: rgba(156, 163, 175, 0.5);
  border-radius: 20px;
}
</style>
