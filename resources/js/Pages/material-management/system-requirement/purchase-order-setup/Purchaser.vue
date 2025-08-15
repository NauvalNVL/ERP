<template>
  <AppLayout :header="'Define Purchaser'">
    <Head title="Define Purchaser" />

    <!-- Toast Container -->
    <ToastContainer />

    <!-- Header Section -->
    <div class="bg-gradient-to-r from-green-600 to-green-800 p-6 rounded-t-lg shadow-md">
      <h2 class="text-2xl font-bold text-white mb-2 flex items-center">
        <i class="fas fa-users mr-3"></i> Define Purchaser
      </h2>
      <p class="text-green-100">Manage purchasers and requestors with approval workflows</p>
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
                <input type="text" v-model="searchQuery" placeholder="Search by ID, name, or email..."
                  class="pl-10 pr-4 py-2 w-full border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500">
              </div>
            </div>
            <div class="flex gap-2 w-full md:w-auto">
              <button @click="newPurchaser" class="btn-primary flex-1 md:flex-none">
                <i class="fas fa-plus-circle mr-2"></i> New Purchaser
              </button>
              <button @click="refreshData" class="btn-secondary flex-1 md:flex-none">
                <i class="fas fa-sync-alt mr-2"></i> Refresh
              </button>
            </div>
          </div>

          <!-- Filter Bar -->
          <div class="mb-4 flex flex-wrap gap-4">
            <select v-model="typeFilter" class="border border-gray-300 rounded px-3 py-1 focus:outline-none focus:ring-2 focus:ring-green-500">
              <option value="">All Types</option>
              <option value="PU">Purchaser</option>
              <option value="RQ">Requestor</option>
            </select>
            <select v-model="statusFilter" class="border border-gray-300 rounded px-3 py-1 focus:outline-none focus:ring-2 focus:ring-green-500">
              <option value="">All Status</option>
              <option value="true">Active</option>
              <option value="false">Inactive</option>
            </select>
          </div>

          <!-- Table Section -->
          <div class="bg-white rounded-lg overflow-hidden border border-gray-200">
            <div class="overflow-x-auto">
              <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                  <tr>
                    <th @click="sortBy('purchaser_id')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                      <div class="flex items-center">
                        Purchaser ID <i class="fas fa-sort ml-1"></i>
                      </div>
                    </th>
                    <th @click="sortBy('purchaser_name')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                      <div class="flex items-center">
                        Name <i class="fas fa-sort ml-1"></i>
                      </div>
                    </th>
                    <th @click="sortBy('type')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                      <div class="flex items-center">
                        Type <i class="fas fa-sort ml-1"></i>
                      </div>
                    </th>
                    <th @click="sortBy('email')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                      <div class="flex items-center">
                        Email <i class="fas fa-sort ml-1"></i>
                      </div>
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                      CC to Self
                    </th>
                  </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                  <tr v-if="loading" class="animate-pulse">
                    <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">
                      <div class="flex justify-center items-center space-x-2">
                        <i class="fas fa-spinner fa-spin"></i>
                        <span>Loading purchasers...</span>
                      </div>
                    </td>
                  </tr>
                  <tr v-else-if="paginatedPurchasers.length === 0" class="hover:bg-gray-50">
                    <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">
                      No purchasers found. Try adjusting your search.
                    </td>
                  </tr>
                  <tr v-for="purchaser in paginatedPurchasers" :key="purchaser.purchaser_id" 
                      @click="selectPurchaser(purchaser)" 
                      :class="{'bg-green-50': selectedPurchaser && selectedPurchaser.purchaser_id === purchaser.purchaser_id}"
                      class="hover:bg-gray-50 cursor-pointer">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ purchaser.purchaser_id }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ purchaser.purchaser_name }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                      <span :class="purchaser.type === 'PU' ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800'" 
                            class="px-2 py-1 text-xs font-semibold rounded-full">
                        {{ purchaser.type === 'PU' ? 'Purchaser' : 'Requestor' }}
                      </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ purchaser.email }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                      <span :class="purchaser.cc_to_self ? 'text-green-600' : 'text-red-600'">
                        {{ purchaser.cc_to_self ? 'Yes' : 'No' }}
                      </span>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>

          <!-- Pagination Controls -->
          <div class="mt-4 flex justify-between items-center text-sm text-gray-600">
            <div>
              <span>Showing {{ paginatedPurchasers.length }} of {{ filteredPurchasers.length }} purchasers</span>
            </div>
            <div class="flex items-center space-x-2">
              <select v-model="itemsPerPage" class="border border-gray-300 rounded px-2 py-1 focus:outline-none focus:ring-2 focus:ring-green-500">
                <option :value="10">10 per page</option>
                <option :value="20">20 per page</option>
                <option :value="50">50 per page</option>
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
        <div class="w-full lg:w-96 bg-gray-50 rounded-lg p-4 border border-gray-200">
          <div v-if="selectedPurchaser" class="mb-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-3 flex items-center">
              <i class="fas fa-user-circle mr-2 text-green-500"></i> Purchaser Details
            </h3>
            <div class="space-y-3">
              <div class="flex justify-between border-b border-gray-200 pb-2">
                <span class="text-gray-600">ID:</span>
                <span class="font-medium text-gray-900">{{ selectedPurchaser.purchaser_id }}</span>
              </div>
              <div class="flex justify-between border-b border-gray-200 pb-2">
                <span class="text-gray-600">Name:</span>
                <span class="font-medium text-gray-900 text-right">{{ selectedPurchaser.purchaser_name }}</span>
              </div>
              <div class="flex justify-between border-b border-gray-200 pb-2">
                <span class="text-gray-600">Type:</span>
                <span class="font-medium text-gray-900">{{ selectedPurchaser.type_text }}</span>
              </div>
              <div class="flex justify-between border-b border-gray-200 pb-2">
                <span class="text-gray-600">Email:</span>
                <span class="font-medium text-gray-900 text-right break-all">{{ selectedPurchaser.email }}</span>
              </div>
              <div class="flex justify-between border-b border-gray-200 pb-2">
                <span class="text-gray-600">Department:</span>
                <span class="font-medium text-gray-900">{{ selectedPurchaser.department || 'N/A' }}</span>
              </div>
              <div class="flex justify-between border-b border-gray-200 pb-2">
                <span class="text-gray-600">Position:</span>
                <span class="font-medium text-gray-900">{{ selectedPurchaser.position || 'N/A' }}</span>
              </div>
              <div class="flex justify-between border-b border-gray-200 pb-2">
                <span class="text-gray-600">Status:</span>
                <span class="font-medium" :class="selectedPurchaser.is_active ? 'text-green-600' : 'text-red-600'">
                  {{ selectedPurchaser.is_active ? 'Active' : 'Inactive' }}
                </span>
              </div>
            </div>
            <div class="mt-6 space-y-2">
              <button @click="editPurchaser(selectedPurchaser)" class="w-full btn-green">
                <i class="fas fa-edit mr-1"></i> Edit
              </button>
              <button @click="setupApprovalFlow(selectedPurchaser)" class="w-full btn-blue">
                <i class="fas fa-sitemap mr-1"></i> Setup Approval Flow
              </button>
              <button @click="deletePurchaser(selectedPurchaser)" class="w-full btn-danger">
                <i class="fas fa-trash-alt mr-1"></i> Delete
              </button>
            </div>
          </div>
          <div v-else class="flex flex-col items-center justify-center h-64 text-center">
            <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mb-4">
              <i class="fas fa-users text-green-500 text-2xl"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-1">No Purchaser Selected</h3>
            <p class="text-gray-500 mb-4">Select a purchaser from the table to view details</p>
            <button @click="newPurchaser" class="btn-primary">
              <i class="fas fa-plus-circle mr-1"></i> Create New Purchaser
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Form Modal -->
    <div v-if="showFormModal" class="modal-container">
      <div class="modal-overlay" @click="showFormModal = false"></div>
      <div class="modal-content max-w-2xl">
        <!-- Modal Header -->
        <div class="modal-header">
          <div class="flex items-center">
            <div class="bg-white/20 p-2 rounded-lg mr-3">
              <i class="fas fa-user-plus text-white text-xl"></i>
            </div>
            <h3 class="text-2xl font-bold text-white">{{ isEditing ? 'Edit' : 'New' }} Purchaser</h3>
          </div>
          <button @click="showFormModal = false" class="bg-white/20 hover:bg-white/30 text-white p-2 rounded-lg transition-all duration-200">
            <i class="fas fa-times"></i>
          </button>
        </div>
        
        <!-- Form Content -->
        <div class="modal-body">
          <form @submit.prevent="savePurchaser">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <!-- Left Column -->
              <div class="space-y-4">
                <!-- Purchaser ID -->
                <div class="form-field">
                  <label class="form-label">
                    <i class="fas fa-hashtag text-green-500 mr-2"></i>
                    Purchaser ID<span class="text-red-500">*</span>
                  </label>
                  <input type="text" v-model="formPurchaser.purchaser_id" required :disabled="isEditing"
                    maxlength="20"
                    @input="formPurchaser.purchaser_id = formPurchaser.purchaser_id.toUpperCase()"
                    class="form-input"
                    placeholder="Enter purchaser ID">
                  <p v-if="errors.purchaser_id" class="form-error">{{ errors.purchaser_id }}</p>
                </div>
                
                <!-- Name -->
                <div class="form-field">
                  <label class="form-label">
                    <i class="fas fa-user text-green-500 mr-2"></i>
                    Purchaser Name<span class="text-red-500">*</span>
                  </label>
                  <input type="text" v-model="formPurchaser.purchaser_name" required
                    maxlength="100"
                    class="form-input"
                    placeholder="Enter full name">
                  <p v-if="errors.purchaser_name" class="form-error">{{ errors.purchaser_name }}</p>
                </div>

                <!-- Type -->
                <div class="form-field">
                  <label class="form-label">
                    <i class="fas fa-user-tag text-green-500 mr-2"></i>
                    Type<span class="text-red-500">*</span>
                  </label>
                  <div class="space-y-2">
                    <label class="flex items-center">
                      <input type="radio" v-model="formPurchaser.type" value="PU" class="form-radio h-4 w-4 text-green-600">
                      <span class="ml-2">Purchaser</span>
                    </label>
                    <label class="flex items-center">
                      <input type="radio" v-model="formPurchaser.type" value="RQ" class="form-radio h-4 w-4 text-green-600">
                      <span class="ml-2">Requestor</span>
                    </label>
                  </div>
                  <p v-if="errors.type" class="form-error">{{ errors.type }}</p>
                </div>

                <!-- Email -->
                <div class="form-field">
                  <label class="form-label">
                    <i class="fas fa-envelope text-green-500 mr-2"></i>
                    Email<span class="text-red-500">*</span>
                  </label>
                  <input type="email" v-model="formPurchaser.email" required
                    maxlength="150"
                    class="form-input"
                    placeholder="Enter email address">
                  <p v-if="errors.email" class="form-error">{{ errors.email }}</p>
                </div>

                <!-- Password -->
                <div class="form-field">
                  <label class="form-label">
                    <i class="fas fa-lock text-green-500 mr-2"></i>
                    Password {{ isEditing ? '(leave blank to keep current)' : '*' }}
                  </label>
                  <input type="password" v-model="formPurchaser.password"
                    :required="!isEditing"
                    minlength="6"
                    class="form-input"
                    placeholder="Enter password">
                  <p v-if="errors.password" class="form-error">{{ errors.password }}</p>
                </div>
              </div>

              <!-- Right Column -->
              <div class="space-y-4">
                <!-- Department -->
                <div class="form-field">
                  <label class="form-label">
                    <i class="fas fa-building text-green-500 mr-2"></i>
                    Department
                  </label>
                  <input type="text" v-model="formPurchaser.department"
                    maxlength="50"
                    class="form-input"
                    placeholder="Enter department">
                  <p v-if="errors.department" class="form-error">{{ errors.department }}</p>
                </div>

                <!-- Position -->
                <div class="form-field">
                  <label class="form-label">
                    <i class="fas fa-user-tie text-green-500 mr-2"></i>
                    Position
                  </label>
                  <input type="text" v-model="formPurchaser.position"
                    maxlength="50"
                    class="form-input"
                    placeholder="Enter position">
                  <p v-if="errors.position" class="form-error">{{ errors.position }}</p>
                </div>

                <!-- Employee ID -->
                <div class="form-field">
                  <label class="form-label">
                    <i class="fas fa-id-badge text-green-500 mr-2"></i>
                    Employee ID
                  </label>
                  <input type="text" v-model="formPurchaser.employee_id"
                    maxlength="20"
                    class="form-input"
                    placeholder="Enter employee ID">
                  <p v-if="errors.employee_id" class="form-error">{{ errors.employee_id }}</p>
                </div>

                <!-- Phone -->
                <div class="form-field">
                  <label class="form-label">
                    <i class="fas fa-phone text-green-500 mr-2"></i>
                    Phone
                  </label>
                  <input type="text" v-model="formPurchaser.phone"
                    maxlength="20"
                    class="form-input"
                    placeholder="Enter phone number">
                  <p v-if="errors.phone" class="form-error">{{ errors.phone }}</p>
                </div>

                <!-- CC to Self -->
                <div class="form-field">
                  <label class="flex items-center space-x-2 cursor-pointer">
                    <input type="checkbox" v-model="formPurchaser.cc_to_self" class="form-checkbox h-5 w-5 text-green-600">
                    <span class="text-gray-700">CC to Self</span>
                  </label>
                </div>

                <!-- Active -->
                <div class="form-field">
                  <label class="flex items-center space-x-2 cursor-pointer">
                    <input type="checkbox" v-model="formPurchaser.is_active" class="form-checkbox h-5 w-5 text-green-600">
                    <span class="text-gray-700">Active</span>
                  </label>
                </div>
              </div>
            </div>

            <!-- Notes -->
            <div class="form-field mt-6">
              <label class="form-label">
                <i class="fas fa-sticky-note text-green-500 mr-2"></i>
                Notes
              </label>
              <textarea v-model="formPurchaser.notes"
                rows="3"
                class="form-input"
                placeholder="Enter additional notes">
              </textarea>
              <p v-if="errors.notes" class="form-error">{{ errors.notes }}</p>
            </div>
            
            <!-- Form Footer with Buttons -->
            <div class="modal-footer">
              <button type="button" @click="showFormModal = false" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg shadow transition-all duration-200 flex items-center">
                <i class="fas fa-times mr-2"></i> Cancel
              </button>
              <button type="submit" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg shadow transition-all duration-200 flex items-center" :disabled="loading">
                <i class="fas fa-save mr-2"></i> {{ isEditing ? 'Update' : 'Create' }}
                <span v-if="loading" class="ml-2 animate-spin"><i class="fas fa-spinner"></i></span>
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- Approval Flow Modal -->
    <div v-if="showApprovalFlowModal" class="modal-container">
      <div class="modal-overlay" @click="showApprovalFlowModal = false"></div>
      <div class="modal-content max-w-4xl">
        <!-- Modal Header -->
        <div class="modal-header">
          <div class="flex items-center">
            <div class="bg-white/20 p-2 rounded-lg mr-3">
              <i class="fas fa-sitemap text-white text-xl"></i>
            </div>
            <h3 class="text-2xl font-bold text-white">Setup Approval Flow - {{ selectedPurchaser?.purchaser_name }}</h3>
          </div>
          <button @click="showApprovalFlowModal = false" class="bg-white/20 hover:bg-white/30 text-white p-2 rounded-lg transition-all duration-200">
            <i class="fas fa-times"></i>
          </button>
        </div>
        
        <!-- Form Content -->
        <div class="modal-body">
          <div class="mb-4">
            <p class="text-gray-600">Configure approval hierarchy for <strong>{{ selectedPurchaser?.purchaser_id }}</strong></p>
          </div>

          <!-- Approvers Table -->
          <div class="overflow-x-auto mb-4">
            <table class="min-w-full divide-y divide-gray-200 border">
              <thead class="bg-gray-50">
                <tr>
                  <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Approver ID</th>
                  <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Approver Name</th>
                  <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Level 1</th>
                  <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Level 2</th>
                  <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Level 3</th>
                  <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Email</th>
                  <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                <tr v-for="(approver, index) in approvalFlowForm" :key="index">
                  <td class="px-4 py-3">
                    <input type="text" v-model="approver.approver_id" 
                      class="w-full px-2 py-1 border border-gray-300 rounded text-sm"
                      placeholder="Approver ID">
                  </td>
                  <td class="px-4 py-3">
                    <input type="text" v-model="approver.approver_name" 
                      class="w-full px-2 py-1 border border-gray-300 rounded text-sm"
                      placeholder="Approver Name">
                  </td>
                  <td class="px-4 py-3 text-center">
                    <input type="checkbox" v-model="approver.level_1" 
                      class="form-checkbox h-4 w-4 text-green-600">
                  </td>
                  <td class="px-4 py-3 text-center">
                    <input type="checkbox" v-model="approver.level_2" 
                      class="form-checkbox h-4 w-4 text-green-600">
                  </td>
                  <td class="px-4 py-3 text-center">
                    <input type="checkbox" v-model="approver.level_3" 
                      class="form-checkbox h-4 w-4 text-green-600">
                  </td>
                  <td class="px-4 py-3 text-center">
                    <input type="checkbox" v-model="approver.email_notification" 
                      class="form-checkbox h-4 w-4 text-green-600">
                  </td>
                  <td class="px-4 py-3 text-center">
                    <button @click="removeApprover(index)" 
                      class="text-red-600 hover:text-red-800 px-2 py-1">
                      <i class="fas fa-trash"></i>
                    </button>
                  </td>
                </tr>
                <tr v-if="approvalFlowForm.length === 0">
                  <td colspan="7" class="px-4 py-8 text-center text-gray-500">
                    No approvers configured. Click "Add Approver" to get started.
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

          <div class="flex justify-between mb-4">
            <button @click="addApprover" class="btn-secondary">
              <i class="fas fa-plus mr-2"></i> Add Approver
            </button>
            <button @click="loadExistingApprovalFlow" class="btn-info" v-if="selectedPurchaser?.approval_flows?.length > 0">
              <i class="fas fa-download mr-2"></i> Load Existing
            </button>
          </div>
          
          <!-- Form Footer with Buttons -->
          <div class="modal-footer">
            <button type="button" @click="showApprovalFlowModal = false" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg shadow transition-all duration-200 flex items-center">
              <i class="fas fa-times mr-2"></i> Cancel
            </button>
            <button @click="saveApprovalFlow" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg shadow transition-all duration-200 flex items-center" :disabled="loading">
              <i class="fas fa-save mr-2"></i> Save Approval Flow
              <span v-if="loading" class="ml-2 animate-spin"><i class="fas fa-spinner"></i></span>
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Confirmation Modal -->
    <div v-if="showConfirmation" class="fixed inset-0 flex items-center justify-center z-50">
      <div class="absolute inset-0 bg-black opacity-50" @click="showConfirmation = false"></div>
      <div class="bg-white rounded-lg shadow-lg max-w-md z-10 w-full">
        <div class="p-6">
          <div class="flex items-center mb-4">
            <div class="bg-red-100 rounded-full p-2 mr-3">
              <i class="fas fa-exclamation-triangle text-red-600"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900">Confirm Delete</h3>
          </div>
          <p class="mb-4 text-gray-600">Are you sure you want to delete purchaser <span class="font-semibold">{{ purchaserToDelete?.purchaser_name }}</span>? This action cannot be undone.</p>
          <div class="flex justify-end space-x-3">
            <button @click="showConfirmation = false" class="px-4 py-2 text-gray-700 border border-gray-300 rounded hover:bg-gray-50">
              <i class="fas fa-times mr-1"></i> Cancel
            </button>
            <button @click="confirmDelete" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700" :disabled="loading">
              <i class="fas fa-trash-alt mr-1"></i> Delete
            </button>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue';
import axios from 'axios';
import { Head, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import { useToast } from '@/Composables/useToast';
import ToastContainer from '@/Components/ToastContainer.vue';

// State variables
const purchasers = ref([]);
const selectedPurchaser = ref(null);
const loading = ref(false);
const searchQuery = ref('');
const typeFilter = ref('');
const statusFilter = ref('');
const showFormModal = ref(false);
const showApprovalFlowModal = ref(false);
const showConfirmation = ref(false);
const purchaserToDelete = ref(null);
const isEditing = ref(false);
const sortOrder = ref({
  field: 'purchaser_id',
  direction: 'asc'
});

// Initialize toast
const toast = useToast();

const currentPage = ref(1);
const itemsPerPage = ref(10);
const errors = ref({});

// Form for creating/editing purchaser
const formPurchaser = ref({
  purchaser_id: '',
  purchaser_name: '',
  type: 'PU',
  email: '',
  password: '',
  cc_to_self: true,
  is_active: true,
  department: '',
  position: '',
  employee_id: '',
  phone: '',
  notes: ''
});

// Approval flow form
const approvalFlowForm = ref([]);

// Reset form to default values
const resetForm = () => {
  formPurchaser.value = {
    purchaser_id: '',
    purchaser_name: '',
    type: 'PU',
    email: '',
    password: '',
    cc_to_self: true,
    is_active: true,
    department: '',
    position: '',
    employee_id: '',
    phone: '',
    notes: ''
  };
  isEditing.value = false;
  errors.value = {};
};

// Reset approval flow form
const resetApprovalFlowForm = () => {
  approvalFlowForm.value = [];
};

// Computed properties
const filteredPurchasers = computed(() => {
  let result = purchasers.value;
  
  if (searchQuery.value) {
    const query = searchQuery.value.toLowerCase();
    result = result.filter(purchaser =>
      (purchaser.purchaser_id && purchaser.purchaser_id.toLowerCase().includes(query)) ||
      (purchaser.purchaser_name && purchaser.purchaser_name.toLowerCase().includes(query)) ||
      (purchaser.email && purchaser.email.toLowerCase().includes(query))
    );
  }
  
  if (typeFilter.value) {
    result = result.filter(purchaser => purchaser.type === typeFilter.value);
  }
  
  if (statusFilter.value !== '') {
    const isActive = statusFilter.value === 'true';
    result = result.filter(purchaser => purchaser.is_active === isActive);
  }
  
  result = [...result].sort((a, b) => {
    const field = sortOrder.value.field;
    const direction = sortOrder.value.direction === 'asc' ? 1 : -1;
    
    const valA = a[field];
    const valB = b[field];

    if ((valA || '') < (valB || '')) return -1 * direction;
    if ((valA || '') > (valB || '')) return 1 * direction;
    return 0;
  });

  return result;
});

const paginatedPurchasers = computed(() => {
  const start = (currentPage.value - 1) * itemsPerPage.value;
  const end = start + itemsPerPage.value;
  return filteredPurchasers.value.slice(start, end);
});

const totalPages = computed(() => {
  const total = filteredPurchasers.value.length;
  if (total === 0) return 1;
  return Math.ceil(total / itemsPerPage.value);
});

watch(filteredPurchasers, () => {
  if (currentPage.value > totalPages.value) {
    currentPage.value = totalPages.value || 1;
  }
});

watch(itemsPerPage, () => {
  currentPage.value = 1;
});

const fetchPurchasers = async () => {
  loading.value = true;
  try {
    const response = await axios.get('/api/purchasers');
    purchasers.value = response.data.map(purchaser => ({
      ...purchaser,
      type_text: purchaser.type === 'PU' ? 'Purchaser' : 'Requestor'
    }));
  } catch (error) {
    console.error('Error fetching purchasers:', error);
    toast.error('Failed to load purchasers');
  } finally {
    loading.value = false;
  }
};

const refreshData = () => {
  selectedPurchaser.value = null;
  searchQuery.value = '';
  typeFilter.value = '';
  statusFilter.value = '';
  fetchPurchasers();
};

const selectPurchaser = (purchaser) => {
  selectedPurchaser.value = purchaser;
};

const newPurchaser = () => {
  resetForm();
  showFormModal.value = true;
};

const editPurchaser = (purchaser) => {
  isEditing.value = true;
  formPurchaser.value = { ...purchaser };
  showFormModal.value = true;
  errors.value = {};
};

const deletePurchaser = (purchaser) => {
  purchaserToDelete.value = purchaser;
  showConfirmation.value = true;
};

const setupApprovalFlow = (purchaser) => {
  selectedPurchaser.value = purchaser;
  resetApprovalFlowForm();
  showApprovalFlowModal.value = true;
};

const savePurchaser = async () => {
  loading.value = true;
  errors.value = {};
  try {
    let response;
    
    if (isEditing.value) {
      response = await axios.put(`/api/purchasers/${formPurchaser.value.purchaser_id}`, formPurchaser.value);
      toast.success('Purchaser updated successfully');
      
      const index = purchasers.value.findIndex(p => p.purchaser_id === formPurchaser.value.purchaser_id);
      if (index !== -1) {
        purchasers.value[index] = {
          ...response.data,
          type_text: response.data.type === 'PU' ? 'Purchaser' : 'Requestor'
        };
        selectedPurchaser.value = purchasers.value[index];
      }
    } else {
      response = await axios.post('/api/purchasers', formPurchaser.value);
      toast.success('Purchaser created successfully');
      const newPurchaser = {
        ...response.data,
        type_text: response.data.type === 'PU' ? 'Purchaser' : 'Requestor'
      };
      purchasers.value.push(newPurchaser);
      selectedPurchaser.value = newPurchaser;
    }
    
    showFormModal.value = false;
  } catch (error) {
    console.error('Error saving purchaser:', error);
    if (error.response?.data?.errors) {
      errors.value = error.response.data.errors;
    }
    toast.error(error.response?.data?.message || 'Failed to save purchaser');
  } finally {
    loading.value = false;
  }
};

const confirmDelete = async () => {
  if (!purchaserToDelete.value) return;
  
  loading.value = true;
  try {
    await axios.delete(`/api/purchasers/${purchaserToDelete.value.purchaser_id}`);
    toast.success('Purchaser deleted successfully');
    
    purchasers.value = purchasers.value.filter(p => p.purchaser_id !== purchaserToDelete.value.purchaser_id);
    
    if (selectedPurchaser.value?.purchaser_id === purchaserToDelete.value.purchaser_id) {
      selectedPurchaser.value = null;
    }
    
    showConfirmation.value = false;
    purchaserToDelete.value = null;
  } catch (error) {
    console.error('Error deleting purchaser:', error);
    toast.error(error.response?.data?.message || 'Failed to delete purchaser');
  } finally {
    loading.value = false;
  }
};

const addApprover = () => {
  approvalFlowForm.value.push({
    approver_id: '',
    approver_name: '',
    level_1: false,
    level_2: false,
    level_3: false,
    email_notification: false
  });
};

const removeApprover = (index) => {
  approvalFlowForm.value.splice(index, 1);
};

const loadExistingApprovalFlow = async () => {
  if (!selectedPurchaser.value) return;
  
  try {
    const response = await axios.get(`/api/purchasers/${selectedPurchaser.value.purchaser_id}/approval-flow`);
    approvalFlowForm.value = response.data;
  } catch (error) {
    console.error('Error loading approval flow:', error);
    toast.error('Failed to load existing approval flow');
  }
};

const saveApprovalFlow = async () => {
  if (!selectedPurchaser.value) return;
  
  const validApprovers = approvalFlowForm.value.filter(
    approver => approver.approver_id.trim() && approver.approver_name.trim()
  );
  
  if (validApprovers.length === 0) {
    toast.error('Please add at least one valid approver');
    return;
  }
  
  loading.value = true;
  try {
    await axios.post(`/api/purchasers/${selectedPurchaser.value.purchaser_id}/setup-approval-flow`, {
      approvers: validApprovers
    });
    
    toast.success('Approval flow setup successfully');
    showApprovalFlowModal.value = false;
    
    // Refresh the selected purchaser data
    const response = await axios.get(`/api/purchasers/${selectedPurchaser.value.purchaser_id}`);
    selectedPurchaser.value = {
      ...response.data,
      type_text: response.data.type === 'PU' ? 'Purchaser' : 'Requestor'
    };
  } catch (error) {
    console.error('Error saving approval flow:', error);
    toast.error(error.response?.data?.message || 'Failed to save approval flow');
  } finally {
    loading.value = false;
  }
};

const sortBy = (field) => {
  if (sortOrder.value.field === field) {
    sortOrder.value.direction = sortOrder.value.direction === 'asc' ? 'desc' : 'asc';
  } else {
    sortOrder.value.field = field;
    sortOrder.value.direction = 'asc';
  }
};

onMounted(() => {
  fetchPurchasers();
});
</script>

<style scoped>
/* Button styles */
.btn-primary {
  @apply bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg shadow transition
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

.btn-green {
  @apply bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg shadow transition
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

/* Form field styles */
.form-field {
  @apply bg-gray-50 p-4 rounded-lg shadow-sm;
}

.form-label {
  @apply text-sm font-semibold text-gray-700 mb-2 flex items-center;
}

.form-input {
  @apply w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200;
}

.form-error {
  @apply mt-1 text-sm text-red-600;
}

/* Modal styles */
.modal-overlay {
  @apply fixed inset-0 bg-black bg-opacity-50;
}

.modal-container {
  @apply fixed inset-0 flex items-center justify-center z-50;
}

.modal-content {
  @apply bg-white rounded-xl shadow-2xl w-full max-w-lg z-10 relative transform transition-all duration-300 ease-in-out;
}

.modal-header {
  @apply flex items-center justify-between p-6 border-b border-gray-200 bg-gradient-to-r from-green-600 to-blue-800 rounded-t-xl;
}

.modal-body {
  @apply p-6 max-h-[70vh] overflow-y-auto;
}

.modal-footer {
  @apply flex justify-end space-x-3 border-t border-gray-200 pt-5 mt-4;
}
</style>
