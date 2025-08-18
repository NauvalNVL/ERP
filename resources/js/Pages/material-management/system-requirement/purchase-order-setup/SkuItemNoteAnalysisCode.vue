<template>
  <AppLayout :header="'Define SKU Item Note Analysis Code'">
    <Head title="Define SKU Item Note Analysis Code" />

    <!-- Toast Container -->
    <ToastContainer />

    <!-- Header Section -->
    <div class="bg-gradient-to-r from-purple-600 to-purple-800 p-6 rounded-t-lg shadow-md">
      <h2 class="text-2xl font-bold text-white mb-2 flex items-center">
        <i class="fas fa-code mr-3"></i> Define SKU Item Note Analysis Code
      </h2>
      <p class="text-purple-100">Manage specific analysis codes within analysis groups for detailed SKU note categorization</p>
    </div>

    <div class="bg-white rounded-b-lg shadow-md p-6 mb-6">
      <div class="flex flex-col lg:flex-row gap-6">
        <!-- Main Content Area -->
        <div class="flex-1">
          <!-- Action Bar -->
          <div class="mb-6 flex flex-col md:flex-row gap-4 justify-between items-start md:items-center">
            <div class="flex-1 w-full">
              <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
                  <i class="fas fa-search"></i>
                </span>
                <input type="text" v-model="searchQuery" placeholder="Search by code, name, or description..."
                  class="pl-10 pr-4 py-2 w-full border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500">
              </div>
            </div>
            <div class="flex gap-2 w-full md:w-auto">
              <button @click="newAnalysisCode" class="btn-primary flex-1 md:flex-none">
                <i class="fas fa-plus-circle mr-2"></i> New Code
              </button>
              <button @click="refreshData" class="btn-secondary flex-1 md:flex-none">
                <i class="fas fa-sync-alt mr-2"></i> Refresh
              </button>
              <button @click="openPrintModal" class="btn-info flex-1 md:flex-none">
                <i class="fas fa-print mr-2"></i> View & Print
              </button>
            </div>
          </div>

          <!-- Filter Bar -->
          <div class="mb-6 bg-gray-50 p-4 rounded-lg">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Analysis Group</label>
                <select v-model="filters.analysisGroupId" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500">
                  <option value="">All Groups</option>
                  <option v-for="group in analysisGroups" :key="group.id" :value="group.id">
                    {{ group.group_code }} - {{ group.group_name }}
                  </option>
                </select>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select v-model="filters.status" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500">
                  <option value="">All Status</option>
                  <option value="active">Active Only</option>
                  <option value="inactive">Inactive Only</option>
                </select>
              </div>
            </div>
          </div>

          <!-- Statistics Cards -->
          <div class="mb-6 grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
              <div class="flex items-center">
                <div class="flex-shrink-0">
                  <i class="fas fa-code text-blue-500 text-2xl"></i>
                </div>
                <div class="ml-3">
                  <p class="text-sm font-medium text-blue-800">Total Codes</p>
                  <p class="text-2xl font-bold text-blue-600">{{ totalCodes }}</p>
                </div>
              </div>
            </div>
            <div class="bg-green-50 p-4 rounded-lg border border-green-200">
              <div class="flex items-center">
                <div class="flex-shrink-0">
                  <i class="fas fa-check-circle text-green-500 text-2xl"></i>
                </div>
                <div class="ml-3">
                  <p class="text-sm font-medium text-green-800">Active</p>
                  <p class="text-2xl font-bold text-green-600">{{ activeCodes }}</p>
                </div>
              </div>
            </div>
            <div class="bg-red-50 p-4 rounded-lg border border-red-200">
              <div class="flex items-center">
                <div class="flex-shrink-0">
                  <i class="fas fa-times-circle text-red-500 text-2xl"></i>
                </div>
                <div class="ml-3">
                  <p class="text-sm font-medium text-red-800">Inactive</p>
                  <p class="text-2xl font-bold text-red-600">{{ inactiveCodes }}</p>
                </div>
              </div>
            </div>
            <div class="bg-purple-50 p-4 rounded-lg border border-purple-200">
              <div class="flex items-center">
                <div class="flex-shrink-0">
                  <i class="fas fa-layer-group text-purple-500 text-2xl"></i>
                </div>
                <div class="ml-3">
                  <p class="text-sm font-medium text-purple-800">Groups Used</p>
                  <p class="text-2xl font-bold text-purple-600">{{ uniqueGroups }}</p>
                </div>
              </div>
            </div>
          </div>

          <!-- Table Section -->
          <div class="bg-white rounded-lg overflow-hidden border border-gray-200">
            <div class="overflow-x-auto">
              <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                  <tr>
                    <th @click="sortBy('analysis_code')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100">
                      <div class="flex items-center">
                        Analysis Code
                        <i class="fas fa-sort ml-1" :class="getSortIcon('analysis_code')"></i>
                      </div>
                    </th>
                    <th @click="sortBy('code_name')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100">
                      <div class="flex items-center">
                        Code Name
                        <i class="fas fa-sort ml-1" :class="getSortIcon('code_name')"></i>
                      </div>
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                      Analysis Group
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                      Description
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                      Status
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                      Actions
                    </th>
                  </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                  <tr v-if="loading" class="animate-pulse">
                    <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">
                      <div class="flex justify-center items-center space-x-2">
                        <i class="fas fa-spinner fa-spin"></i>
                        <span>Loading analysis codes...</span>
                      </div>
                    </td>
                  </tr>
                  <tr v-else-if="analysisCodes.length === 0" class="hover:bg-gray-50">
                    <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">
                      No analysis codes found. Try adjusting your search or filters.
                    </td>
                  </tr>
                  <tr v-for="code in analysisCodes" :key="code.id" 
                      @click="selectAnalysisCode(code)" 
                      :class="{'bg-purple-50': selectedCode && selectedCode.id === code.id}"
                      class="hover:bg-gray-50 cursor-pointer transition-colors duration-150">
                    <td class="px-6 py-4 whitespace-nowrap">
                      <span class="text-sm font-medium text-gray-900 font-mono">{{ code.analysis_code }}</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <span class="text-sm text-gray-900">{{ code.code_name }}</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <span class="text-sm text-gray-600">
                        {{ code.analysis_group ? `${code.analysis_group.group_code} - ${code.analysis_group.group_name}` : 'N/A' }}
                      </span>
                    </td>
                    <td class="px-6 py-4">
                      <span class="text-sm text-gray-600">{{ code.description || '-' }}</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <span :class="code.is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'" 
                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full">
                        {{ code.is_active ? 'Active' : 'Inactive' }}
                      </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                      <div class="flex space-x-2">
                        <button @click.stop="editAnalysisCode(code)" class="text-blue-600 hover:text-blue-900" title="Edit">
                          <i class="fas fa-edit"></i>
                        </button>
                        <button @click.stop="toggleActive(code)" 
                                :class="code.is_active ? 'text-red-600 hover:text-red-900' : 'text-green-600 hover:text-green-900'"
                                :title="code.is_active ? 'Deactivate' : 'Activate'">
                          <i :class="code.is_active ? 'fas fa-ban' : 'fas fa-check-circle'"></i>
                        </button>
                        <button @click.stop="deleteAnalysisCode(code)" class="text-red-600 hover:text-red-900" title="Delete">
                          <i class="fas fa-trash"></i>
                        </button>
                      </div>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>

          <!-- Pagination -->
          <div class="mt-6 flex items-center justify-between">
            <div class="flex-1 flex justify-between sm:hidden">
              <button @click="previousPage" :disabled="!pagination.prev_page_url" 
                      class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                Previous
              </button>
              <button @click="nextPage" :disabled="!pagination.next_page_url"
                      class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                Next
              </button>
            </div>
            <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
              <div>
                <p class="text-sm text-gray-700">
                  Showing {{ pagination.from || 0 }} to {{ pagination.to || 0 }} of {{ pagination.total || 0 }} results
                </p>
              </div>
              <div class="flex items-center space-x-2">
                <select v-model="perPage" @change="fetchAnalysisCodes" class="border border-gray-300 rounded px-2 py-1 text-sm">
                  <option :value="10">10 per page</option>
                  <option :value="15">15 per page</option>
                  <option :value="25">25 per page</option>
                  <option :value="50">50 per page</option>
                </select>
                <button @click="previousPage" :disabled="!pagination.prev_page_url" class="px-3 py-1 border rounded text-sm hover:bg-gray-100 disabled:opacity-50">
                  <i class="fas fa-chevron-left"></i>
                </button>
                <span class="px-3 py-1 text-sm">{{ pagination.current_page || 1 }} / {{ pagination.last_page || 1 }}</span>
                <button @click="nextPage" :disabled="!pagination.next_page_url" class="px-3 py-1 border rounded text-sm hover:bg-gray-100 disabled:opacity-50">
                  <i class="fas fa-chevron-right"></i>
                </button>
              </div>
            </div>
          </div>
        </div>

        <!-- Side Panel -->
        <div class="w-full lg:w-96 bg-gray-50 rounded-lg p-4 border border-gray-200">
          <div v-if="selectedCode" class="mb-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-3 flex items-center">
              <i class="fas fa-info-circle mr-2 text-purple-500"></i> Code Details
            </h3>
            <div class="space-y-3">
              <div class="flex justify-between border-b border-gray-200 pb-2">
                <span class="text-gray-600">Code:</span>
                <span class="font-medium text-gray-900 font-mono">{{ selectedCode.analysis_code }}</span>
              </div>
              <div class="flex justify-between border-b border-gray-200 pb-2">
                <span class="text-gray-600">Name:</span>
                <span class="font-medium text-gray-900 text-right">{{ selectedCode.code_name }}</span>
              </div>
              <div class="flex justify-between border-b border-gray-200 pb-2">
                <span class="text-gray-600">Group:</span>
                <span class="font-medium text-gray-900 text-right">
                  {{ selectedCode.analysis_group ? selectedCode.analysis_group.group_name : 'N/A' }}
                </span>
              </div>
              <div class="flex justify-between border-b border-gray-200 pb-2">
                <span class="text-gray-600">Category:</span>
                <span class="font-medium text-gray-900">
                  {{ selectedCode.analysis_group ? selectedCode.analysis_group.category || 'N/A' : 'N/A' }}
                </span>
              </div>
              <div class="border-b border-gray-200 pb-2">
                <span class="text-gray-600">Description:</span>
                <p class="font-medium text-gray-900 mt-1">{{ selectedCode.description || 'No description provided' }}</p>
              </div>
              <div class="flex justify-between border-b border-gray-200 pb-2">
                <span class="text-gray-600">Status:</span>
                <span class="font-medium" :class="selectedCode.is_active ? 'text-green-600' : 'text-red-600'">
                  {{ selectedCode.is_active ? 'Active' : 'Inactive' }}
                </span>
              </div>
              <div class="flex justify-between border-b border-gray-200 pb-2">
                <span class="text-gray-600">Created:</span>
                <span class="font-medium text-gray-900">{{ formatDate(selectedCode.created_at) }}</span>
              </div>
            </div>
            <div class="mt-6 flex space-x-2">
              <button @click="editAnalysisCode(selectedCode)" class="flex-1 btn-blue">
                <i class="fas fa-edit mr-1"></i> Edit
              </button>
              <button @click="toggleActive(selectedCode)" 
                      :class="selectedCode.is_active ? 'flex-1 btn-red' : 'flex-1 btn-green'">
                <i :class="selectedCode.is_active ? 'fas fa-ban mr-1' : 'fas fa-check-circle mr-1'"></i>
                {{ selectedCode.is_active ? 'Deactivate' : 'Activate' }}
              </button>
            </div>
          </div>
          <div v-else class="flex flex-col items-center justify-center h-64 text-center">
            <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mb-4">
              <i class="fas fa-code text-purple-500 text-2xl"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-1">No Code Selected</h3>
            <p class="text-gray-500 mb-4">Select an analysis code from the table to view details</p>
            <button @click="newAnalysisCode" class="btn-primary">
              <i class="fas fa-plus-circle mr-1"></i> Create New Code
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Form Modal -->
    <div v-if="showFormModal" class="modal-container">
      <div class="modal-overlay" @click="showFormModal = false"></div>
      <div class="modal-content">
        <!-- Modal Header -->
        <div class="modal-header">
          <div class="flex items-center">
            <div class="bg-white/20 p-2 rounded-lg mr-3">
              <i class="fas fa-code text-white text-xl"></i>
            </div>
            <h3 class="text-2xl font-bold text-white">{{ isEditing ? 'Edit' : 'New' }} Analysis Code</h3>
          </div>
          <button @click="showFormModal = false" class="bg-white/20 hover:bg-white/30 text-white p-2 rounded-lg transition-all duration-200">
            <i class="fas fa-times"></i>
          </button>
        </div>
        
        <!-- Form Content -->
        <div class="modal-body">
          <form @submit.prevent="saveAnalysisCode">
            <div class="space-y-6">
              <!-- Analysis Group -->
              <div class="form-field">
                <label class="form-label">
                  <i class="fas fa-layer-group text-purple-500 mr-2"></i>
                  Analysis Group<span class="text-red-500">*</span>
                </label>
                <select v-model="formCode.analysis_group_id" required
                  class="form-input"
                  @change="onGroupChange">
                  <option value="">Select an analysis group</option>
                  <option v-for="group in analysisGroups" :key="group.id" :value="group.id">
                    {{ group.group_code }} - {{ group.group_name }}
                  </option>
                </select>
                <p v-if="errors.analysis_group_id" class="form-error">{{ errors.analysis_group_id[0] }}</p>
              </div>

              <!-- Analysis Code -->
              <div class="form-field">
                <label class="form-label">
                  <i class="fas fa-hashtag text-purple-500 mr-2"></i>
                  Analysis Code<span class="text-red-500">*</span>
                </label>
                <input type="text" v-model="formCode.analysis_code" required
                  maxlength="20"
                  @input="formCode.analysis_code = formCode.analysis_code.toUpperCase().replace(/[^A-Z0-9-_]/g, '')"
                  class="form-input font-mono"
                  placeholder="Enter analysis code (e.g., DMG-PKG)">
                <p class="text-xs text-gray-500 mt-2 italic">Code must be unique within the selected group. Use uppercase letters, numbers, hyphens, and underscores only.</p>
                <p v-if="errors.analysis_code" class="form-error">{{ errors.analysis_code[0] }}</p>
              </div>
              
              <!-- Code Name -->
              <div class="form-field">
                <label class="form-label">
                  <i class="fas fa-tag text-purple-500 mr-2"></i>
                  Code Name<span class="text-red-500">*</span>
                </label>
                <input type="text" v-model="formCode.code_name" required
                  maxlength="100"
                  class="form-input"
                  placeholder="Enter descriptive name for the code">
                <p v-if="errors.code_name" class="form-error">{{ errors.code_name[0] }}</p>
              </div>

              <!-- Description -->
              <div class="form-field">
                <label class="form-label">
                  <i class="fas fa-align-left text-purple-500 mr-2"></i>
                  Description
                </label>
                <textarea v-model="formCode.description" 
                  rows="3"
                  maxlength="500"
                  class="form-input"
                  placeholder="Enter detailed description of the analysis code">
                </textarea>
                <p class="text-xs text-gray-500 mt-1">{{ (formCode.description || '').length }}/500 characters</p>
                <p v-if="errors.description" class="form-error">{{ errors.description[0] }}</p>
              </div>

              <!-- Status -->
              <div class="form-field">
                <label class="flex items-center space-x-2 cursor-pointer">
                  <input type="checkbox" v-model="formCode.is_active" class="form-checkbox h-5 w-5 text-purple-600">
                  <span class="text-gray-700 flex items-center">
                    <i class="fas fa-toggle-on text-purple-500 mr-2"></i>
                    Active
                  </span>
                </label>
                <p class="text-xs text-gray-500 mt-1">Inactive codes won't be available for selection in other modules</p>
              </div>
            </div>
            
            <!-- Form Footer with Buttons -->
            <div class="modal-footer">
              <button type="button" @click="showFormModal = false" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg shadow transition-all duration-200 flex items-center">
                <i class="fas fa-times mr-2"></i> Cancel
              </button>
              <button type="submit" class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg shadow transition-all duration-200 flex items-center" :disabled="formLoading">
                <i class="fas fa-save mr-2"></i> {{ isEditing ? 'Update' : 'Create' }}
                <span v-if="formLoading" class="ml-2 animate-spin"><i class="fas fa-spinner"></i></span>
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- Print Modal -->
    <div v-if="showPrintModal" class="modal-container">
      <div class="modal-overlay" @click="showPrintModal = false"></div>
      <div class="modal-content max-w-4xl">
        <div class="modal-header">
          <div class="flex items-center">
            <div class="bg-white/20 p-2 rounded-lg mr-3">
              <i class="fas fa-print text-white text-xl"></i>
            </div>
            <h3 class="text-2xl font-bold text-white">View & Print Analysis Codes</h3>
          </div>
          <button @click="showPrintModal = false" class="bg-white/20 hover:bg-white/30 text-white p-2 rounded-lg transition-all duration-200">
            <i class="fas fa-times"></i>
          </button>
        </div>
        
        <div class="modal-body">
          <!-- Print Filters -->
          <div class="mb-6 bg-gray-50 p-4 rounded-lg">
            <h4 class="text-lg font-semibold text-gray-800 mb-4">Report Filters</h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Analysis Group</label>
                <select v-model="printFilters.analysisGroupId" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500">
                  <option value="">All Groups</option>
                  <option v-for="group in analysisGroups" :key="group.id" :value="group.id">
                    {{ group.group_code }} - {{ group.group_name }}
                  </option>
                </select>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select v-model="printFilters.status" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500">
                  <option value="">All Status</option>
                  <option value="active">Active Only</option>
                  <option value="inactive">Inactive Only</option>
                </select>
              </div>
            </div>
            <div class="mt-4 flex gap-4">
              <button @click="loadPrintData" class="btn-primary">
                <i class="fas fa-filter mr-2"></i> Apply Filters
              </button>
              <button @click="printReport" class="btn-secondary">
                <i class="fas fa-print mr-2"></i> Print Report
              </button>
            </div>
          </div>

          <!-- Print Preview -->
          <div id="printArea" class="border border-gray-200 p-6 bg-white">
            <div class="text-center mb-6">
              <h1 class="text-2xl font-bold text-gray-900">SKU Item Note Analysis Codes Report</h1>
              <p class="text-sm text-gray-600 mt-2">Generated on {{ new Date().toLocaleDateString() }}</p>
            </div>

            <div class="overflow-x-auto">
              <table class="w-full text-sm border-collapse border border-gray-300">
                <thead>
                  <tr class="bg-gray-100">
                    <th class="border border-gray-300 px-3 py-2 text-left font-semibold">Analysis Code</th>
                    <th class="border border-gray-300 px-3 py-2 text-left font-semibold">Code Name</th>
                    <th class="border border-gray-300 px-3 py-2 text-left font-semibold">Analysis Group</th>
                    <th class="border border-gray-300 px-3 py-2 text-left font-semibold">Description</th>
                    <th class="border border-gray-300 px-3 py-2 text-left font-semibold">Status</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-if="printLoading">
                    <td colspan="5" class="border border-gray-300 px-3 py-4 text-center">
                      <div class="flex justify-center items-center">
                        <i class="fas fa-spinner fa-spin mr-2"></i>
                        Loading data...
                      </div>
                    </td>
                  </tr>
                  <tr v-else-if="printData.length === 0">
                    <td colspan="5" class="border border-gray-300 px-3 py-4 text-center text-gray-500">
                      No data found with current filters
                    </td>
                  </tr>
                  <tr v-for="code in printData" :key="code.id" class="hover:bg-gray-50">
                    <td class="border border-gray-300 px-3 py-2 font-mono font-medium">{{ code.analysis_code }}</td>
                    <td class="border border-gray-300 px-3 py-2">{{ code.code_name }}</td>
                    <td class="border border-gray-300 px-3 py-2">
                      {{ code.analysis_group ? `${code.analysis_group.group_code} - ${code.analysis_group.group_name}` : 'N/A' }}
                    </td>
                    <td class="border border-gray-300 px-3 py-2">{{ code.description || '-' }}</td>
                    <td class="border border-gray-300 px-3 py-2">
                      <span :class="code.is_active ? 'text-green-600' : 'text-red-600'" class="font-medium">
                        {{ code.is_active ? 'Active' : 'Inactive' }}
                      </span>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>

            <div class="mt-6 text-center text-xs text-gray-500">
              <p>Report generated by ERP System - Material Management Module</p>
              <p>Total records: {{ printData.length }}</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue'
import { Head } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import ToastContainer from '@/Components/ToastContainer.vue'
import { useToast } from '@/Composables/useToast'

const { success, error } = useToast()

// Reactive data
const analysisCodes = ref([])
const analysisGroups = ref([])
const selectedCode = ref(null)
const loading = ref(false)
const formLoading = ref(false)
const printLoading = ref(false)
const searchQuery = ref('')
const showFormModal = ref(false)
const showPrintModal = ref(false)
const isEditing = ref(false)
const perPage = ref(15)
const pagination = ref({})
const printData = ref([])

// Filters
const filters = ref({
  analysisGroupId: '',
  status: ''
})

const printFilters = ref({
  analysisGroupId: '',
  status: ''
})

// Sorting
const sortField = ref('analysis_code')
const sortDirection = ref('asc')

// Form data
const formCode = ref({
  analysis_group_id: '',
  analysis_code: '',
  code_name: '',
  description: '',
  is_active: true
})

const errors = ref({})

// Computed properties
const totalCodes = computed(() => pagination.value.total || 0)
const activeCodes = computed(() => analysisCodes.value.filter(code => code.is_active).length)
const inactiveCodes = computed(() => analysisCodes.value.filter(code => !code.is_active).length)
const uniqueGroups = computed(() => {
  const groups = new Set(analysisCodes.value.map(code => code.analysis_group_id).filter(id => id))
  return groups.size
})

// Methods
const fetchAnalysisCodes = async (page = 1) => {
  loading.value = true
  try {
    const params = new URLSearchParams({
      page: page,
      per_page: perPage.value,
      sort_field: sortField.value,
      sort_direction: sortDirection.value
    })

    if (searchQuery.value) params.append('search', searchQuery.value)
    if (filters.value.analysisGroupId) params.append('analysis_group_id', filters.value.analysisGroupId)
    if (filters.value.status) params.append('status', filters.value.status)

    const response = await fetch(`/api/sku-item-note-analysis-codes?${params}`)
    const data = await response.json()
    
    analysisCodes.value = data.data
    pagination.value = {
      current_page: data.current_page,
      last_page: data.last_page,
      per_page: data.per_page,
      total: data.total,
      from: data.from,
      to: data.to,
      prev_page_url: data.prev_page_url,
      next_page_url: data.next_page_url
    }
  } catch (err) {
    error('Failed to load analysis codes')
  } finally {
    loading.value = false
  }
}

const fetchAnalysisGroups = async () => {
  try {
    const response = await fetch('/api/sku-item-note-analysis-codes/groups')
    const data = await response.json()
    analysisGroups.value = data
  } catch (err) {
    error('Failed to load analysis groups')
  }
}

const refreshData = () => {
  selectedCode.value = null
  searchQuery.value = ''
  filters.value = { analysisGroupId: '', status: '' }
  fetchAnalysisCodes()
}

const selectAnalysisCode = (code) => {
  selectedCode.value = code
}

const newAnalysisCode = () => {
  resetForm()
  showFormModal.value = true
}

const editAnalysisCode = (code) => {
  isEditing.value = true
  formCode.value = { ...code }
  showFormModal.value = true
  errors.value = {}
}

const resetForm = () => {
  formCode.value = {
    analysis_group_id: '',
    analysis_code: '',
    code_name: '',
    description: '',
    is_active: true
  }
  isEditing.value = false
  errors.value = {}
}

const saveAnalysisCode = async () => {
  formLoading.value = true
  errors.value = {}
  try {
    let response
    
    if (isEditing.value) {
      response = await fetch(`/api/sku-item-note-analysis-codes/${formCode.value.id}`, {
        method: 'PUT',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify(formCode.value)
      })
    } else {
      response = await fetch('/api/sku-item-note-analysis-codes', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify(formCode.value)
      })
    }

    const data = await response.json()

    if (!response.ok) {
      if (data.errors) {
        errors.value = data.errors
      }
      throw new Error(data.message || 'Failed to save analysis code')
    }

    success(isEditing.value ? 'Analysis code updated successfully' : 'Analysis code created successfully')
    showFormModal.value = false
    await fetchAnalysisCodes()
    
    if (isEditing.value && selectedCode.value?.id === data.id) {
      selectedCode.value = data
    }
  } catch (err) {
    error(err.message || 'Failed to save analysis code')
  } finally {
    formLoading.value = false
  }
}

const toggleActive = async (code) => {
  try {
    const response = await fetch(`/api/sku-item-note-analysis-codes/${code.id}/toggle-active`, {
      method: 'PATCH',
      headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
      }
    })

    if (!response.ok) throw new Error('Failed to update status')

    const data = await response.json()
    success(data.message)
    
    // Update the code in the list
    const index = analysisCodes.value.findIndex(c => c.id === code.id)
    if (index !== -1) {
      analysisCodes.value[index] = data.analysis_code
    }
    
    // Update selected code if it's the same
    if (selectedCode.value?.id === code.id) {
      selectedCode.value = data.analysis_code
    }
  } catch (err) {
    error('Failed to update status')
  }
}

const deleteAnalysisCode = async (code) => {
  if (!confirm(`Are you sure you want to delete analysis code "${code.analysis_code}"?`)) return

  try {
    const response = await fetch(`/api/sku-item-note-analysis-codes/${code.id}`, {
      method: 'DELETE',
      headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
      }
    })

    if (!response.ok) throw new Error('Failed to delete analysis code')

    success('Analysis code deleted successfully')
    
    if (selectedCode.value?.id === code.id) {
      selectedCode.value = null
    }
    
    await fetchAnalysisCodes()
  } catch (err) {
    error('Failed to delete analysis code')
  }
}

const sortBy = (field) => {
  if (sortField.value === field) {
    sortDirection.value = sortDirection.value === 'asc' ? 'desc' : 'asc'
  } else {
    sortField.value = field
    sortDirection.value = 'asc'
  }
  fetchAnalysisCodes()
}

const getSortIcon = (field) => {
  if (sortField.value !== field) return 'text-gray-400'
  return sortDirection.value === 'asc' ? 'text-blue-500 fa-sort-up' : 'text-blue-500 fa-sort-down'
}

const previousPage = () => {
  if (pagination.value.prev_page_url) {
    fetchAnalysisCodes(pagination.value.current_page - 1)
  }
}

const nextPage = () => {
  if (pagination.value.next_page_url) {
    fetchAnalysisCodes(pagination.value.current_page + 1)
  }
}

const onGroupChange = () => {
  // Clear analysis code when group changes to avoid conflicts
  if (!isEditing.value) {
    formCode.value.analysis_code = ''
  }
}

const openPrintModal = () => {
  showPrintModal.value = true
  loadPrintData()
}

const loadPrintData = async () => {
  printLoading.value = true
  try {
    const params = new URLSearchParams()
    if (printFilters.value.analysisGroupId) params.append('analysis_group_id', printFilters.value.analysisGroupId)
    if (printFilters.value.status) params.append('status', printFilters.value.status)
    
    const response = await fetch(`/api/sku-item-note-analysis-codes/for-print?${params}`)
    const data = await response.json()
    printData.value = data
  } catch (err) {
    error('Failed to load print data')
  } finally {
    printLoading.value = false
  }
}

const printReport = () => {
  const printContent = document.getElementById('printArea').innerHTML
  const printWindow = window.open('', '_blank')
  printWindow.document.write(`
    <html>
      <head>
        <title>SKU Item Note Analysis Codes Report</title>
        <style>
          body { 
            font-family: Arial, sans-serif; 
            margin: 20px; 
            color: #333;
          }
          table { 
            width: 100%; 
            border-collapse: collapse; 
            margin: 20px 0;
          }
          th, td { 
            border: 1px solid #ddd; 
            padding: 8px; 
            text-align: left;
          }
          th { 
            background-color: #f2f2f2; 
            font-weight: bold;
          }
          .text-center { text-align: center; }
          .font-bold { font-weight: bold; }
          .text-gray-900 { color: #111827; }
          .text-gray-600 { color: #4b5563; }
          .text-green-600 { color: #16a34a; }
          .text-red-600 { color: #dc2626; }
          @media print {
            body { margin: 0; }
          }
        </style>
      </head>
      <body>
        ${printContent}
      </body>
    </html>
  `)
  printWindow.document.close()
  printWindow.print()
}

const formatDate = (dateString) => {
  if (!dateString) return '-'
  return new Date(dateString).toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'short',
    day: 'numeric'
  })
}

// Watchers
watch([searchQuery, () => filters.value.analysisGroupId, () => filters.value.status], () => {
  fetchAnalysisCodes()
}, { deep: true })

// Lifecycle
onMounted(() => {
  fetchAnalysisGroups()
  fetchAnalysisCodes()
})
</script>

<style scoped>
/* Button styles */
.btn-primary {
  @apply bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg shadow transition
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

.btn-blue {
  @apply bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg shadow transition
    flex items-center justify-center whitespace-nowrap;
}

.btn-green {
  @apply bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg shadow transition
    flex items-center justify-center whitespace-nowrap;
}

.btn-red {
  @apply bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg shadow transition
    flex items-center justify-center whitespace-nowrap;
}

/* Form field styles */
.form-field {
  @apply bg-gray-50 p-4 rounded-lg shadow-sm;
}

.form-label {
  @apply text-sm font-semibold text-gray-700 mb-2 flex items-center;
}

.form-input {
  @apply w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200;
}

.form-error {
  @apply mt-1 text-sm text-red-600;
}

/* Modal styles */
.modal-overlay {
  @apply fixed inset-0 bg-black bg-opacity-50 z-40;
}

.modal-container {
  @apply fixed inset-0 flex items-center justify-center z-50 p-4;
}

.modal-content {
  @apply bg-white rounded-xl shadow-2xl w-full max-w-2xl z-10 relative transform transition-all duration-300 ease-in-out max-h-screen overflow-hidden;
}

.modal-header {
  @apply flex items-center justify-between p-6 border-b border-gray-200 bg-gradient-to-r from-purple-600 to-purple-800 rounded-t-xl;
}

.modal-body {
  @apply p-6 max-h-[calc(90vh-120px)] overflow-y-auto;
}

.modal-footer {
  @apply flex justify-end space-x-3 border-t border-gray-200 pt-5 mt-4;
}

/* Table hover animation */
tbody tr {
  transition: all 0.2s;
}

tbody tr:hover {
  transform: translateY(-1px);
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
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

@media print {
  body * {
    visibility: hidden;
  }
  #printArea, #printArea * {
    visibility: visible;
  }
  #printArea {
    position: absolute;
    left: 0;
    top: 0;
  }
}
</style>
