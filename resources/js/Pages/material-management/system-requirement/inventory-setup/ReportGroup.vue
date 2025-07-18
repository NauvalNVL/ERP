<template>
  <AppLayout :header="'Define Report Group'">
    <Head title="Define Report Group" />

    <!-- Header Section -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-800 p-6 rounded-t-lg shadow-md">
      <h2 class="text-2xl font-bold text-white mb-2 flex items-center">
        <i class="fas fa-layer-group mr-3"></i> Define Report Group
      </h2>
      <p class="text-blue-100">Manage inventory report grouping</p>
    </div>

    <div class="bg-white rounded-b-lg shadow-md p-6 mb-6">
      <div class="flex flex-col md:flex-row gap-6">
        <!-- Main Content Area -->
        <div class="flex-1">
          <!-- Action Bar -->
          <div class="mb-6 flex flex-col md:flex-row gap-4 justify-between items-start md:items-center">
            <div class="flex-1 w-full">
              <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
                  <i class="fas fa-search"></i>
                </span>
                <input type="text" v-model="search" placeholder="Search by code or name..."
                  class="pl-10 pr-4 py-2 w-full border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
              </div>
            </div>
            <div class="flex gap-2 w-full md:w-auto">
              <button @click="showAddModal" class="btn-primary flex-1 md:flex-none">
                <i class="fas fa-plus-circle mr-2"></i> New Report Group
              </button>
              <button @click="refresh" class="btn-secondary flex-1 md:flex-none">
                <i class="fas fa-sync-alt mr-2"></i> Refresh
              </button>
              <button @click="viewPrint" class="btn-info flex-1 md:flex-none">
                <i class="fas fa-print mr-2"></i> Print
              </button>
            </div>
          </div>
          
          <!-- Table Section -->
          <div class="bg-white rounded-lg overflow-hidden border border-gray-200">
            <div class="overflow-x-auto">
              <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                  <tr>
                    <th @click="sortTable('code')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                      <div class="flex items-center">
                        Code <i class="fas fa-sort ml-1"></i>
                      </div>
                    </th>
                    <th @click="sortTable('name')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                      <div class="flex items-center">
                        Name <i class="fas fa-sort ml-1"></i>
                      </div>
                    </th>
                  </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                  <tr v-if="loading" class="animate-pulse">
                    <td colspan="2" class="px-6 py-4 text-center text-sm text-gray-500">
                      <div class="flex justify-center items-center space-x-2">
                        <i class="fas fa-spinner fa-spin"></i>
                        <span>Loading report groups...</span>
                      </div>
                    </td>
                  </tr>
                  <tr v-else-if="paginatedReportGroups.length === 0" class="hover:bg-gray-50">
                    <td colspan="2" class="px-6 py-4 text-center text-sm text-gray-500">
                      No report groups found. Try adjusting your search.
                    </td>
                  </tr>
                  <tr v-for="group in paginatedReportGroups" :key="group.id" 
                      @click="selectGroup(group)" 
                      :class="{'bg-blue-50': selectedGroup && selectedGroup.id === group.id}"
                      class="hover:bg-gray-50 cursor-pointer">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ group.code }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ group.name }}</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
          
          <!-- Pagination Controls -->
          <div class="mt-4 flex justify-between items-center text-sm text-gray-600">
            <div>
              <span>Showing {{ paginatedReportGroups.length }} of {{ filteredReportGroups.length }} report groups</span>
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
          <div v-if="selectedGroup" class="mb-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-3 flex items-center">
              <i class="fas fa-info-circle mr-2 text-blue-500"></i> Report Group Details
            </h3>
            <div class="space-y-3">
              <div class="flex justify-between border-b border-gray-200 pb-2">
                <span class="text-gray-600">Code:</span>
                <span class="font-medium text-gray-900">{{ selectedGroup.code }}</span>
              </div>
              <div class="flex justify-between border-b border-gray-200 pb-2">
                <span class="text-gray-600">Name:</span>
                <span class="font-medium text-gray-900 text-right">{{ selectedGroup.name }}</span>
              </div>
            </div>
            <div class="mt-6 flex space-x-2">
              <button @click="editReportGroup(selectedGroup)" class="flex-1 btn-blue">
                <i class="fas fa-edit mr-1"></i> Edit
              </button>
              <button @click="confirmDelete(selectedGroup)" class="flex-1 btn-danger">
                <i class="fas fa-trash-alt mr-1"></i> Delete
              </button>
            </div>
          </div>
          <div v-else class="flex flex-col items-center justify-center h-64 text-center">
            <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mb-4">
              <i class="fas fa-layer-group text-blue-500 text-2xl"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-1">No Report Group Selected</h3>
            <p class="text-gray-500 mb-4">Select a report group from the table to view details</p>
            <button @click="showAddModal" class="btn-primary">
              <i class="fas fa-plus-circle mr-1"></i> Create New Report Group
            </button>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Form Modal -->
    <div v-if="showModal" class="fixed inset-0 flex items-center justify-center z-50">
      <div class="absolute inset-0 bg-black opacity-50" @click="closeModal"></div>
      <div class="bg-white rounded-xl shadow-2xl w-full max-w-lg z-10 relative transform transition-all duration-300 ease-in-out">
        <!-- Modal Header -->
        <div class="flex items-center justify-between p-6 border-b border-gray-200 bg-gradient-to-r from-blue-600 to-indigo-800 rounded-t-xl">
          <div class="flex items-center">
            <div class="bg-white/20 p-2 rounded-lg mr-3">
              <i class="fas fa-layer-group text-white text-xl"></i>
            </div>
            <h3 class="text-2xl font-bold text-white">{{ isEditing ? 'Edit' : 'New' }} Report Group</h3>
          </div>
          <button @click="closeModal" class="bg-white/20 hover:bg-white/30 text-white p-2 rounded-lg transition-all duration-200">
            <i class="fas fa-times"></i>
          </button>
        </div>
        
        <!-- Form Content -->
        <div class="p-6 max-h-[70vh] overflow-y-auto">
          <form @submit.prevent="saveReportGroup">
            <div class="space-y-6">
              <!-- Code -->
              <div class="bg-gray-50 p-4 rounded-lg shadow-sm">
                <label class="text-sm font-semibold text-gray-700 mb-2 flex items-center">
                  <i class="fas fa-hashtag text-blue-500 mr-2"></i>
                  Report Group Code<span class="text-red-500">*</span>
                </label>
                <input type="text" v-model="form.code" required :disabled="isEditing"
                  maxlength="10"
                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                  placeholder="Enter report group code">
                <p class="text-xs text-gray-500 mt-2 italic">Code must be unique (max 10 chars) and cannot be changed later.</p>
                <p v-if="errors.code" class="mt-1 text-sm text-red-600">{{ errors.code }}</p>
              </div>
              
              <!-- Name -->
              <div class="bg-gray-50 p-4 rounded-lg shadow-sm">
                <label class="text-sm font-semibold text-gray-700 mb-2 flex items-center">
                  <i class="fas fa-tag text-blue-500 mr-2"></i>
                  Report Group Name<span class="text-red-500">*</span>
                </label>
                <input type="text" v-model="form.name" required
                  maxlength="100"
                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                  placeholder="Enter report group name">
                <p v-if="errors.name" class="mt-1 text-sm text-red-600">{{ errors.name }}</p>
              </div>
            </div>
            
            <!-- Form Footer with Buttons -->
            <div class="flex justify-end space-x-3 border-t border-gray-200 pt-5 mt-4">
              <button type="button" @click="closeModal" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg shadow transition-all duration-200 flex items-center">
                <i class="fas fa-times mr-2"></i> Cancel
              </button>
              <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg shadow transition-all duration-200 flex items-center" :disabled="loading">
                <i class="fas fa-save mr-2"></i> {{ isEditing ? 'Update' : 'Create' }}
                <span v-if="loading" class="ml-2 animate-spin"><i class="fas fa-spinner"></i></span>
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- Confirmation Modal -->
    <div v-if="showDeleteModal" class="fixed inset-0 flex items-center justify-center z-50">
      <div class="absolute inset-0 bg-black opacity-50" @click="closeDeleteModal"></div>
      <div class="bg-white rounded-lg shadow-lg max-w-md z-10 w-full">
        <div class="p-6">
          <div class="flex items-center mb-4">
            <div class="bg-red-100 rounded-full p-2 mr-3">
              <i class="fas fa-exclamation-triangle text-red-600"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900">Confirm Delete</h3>
          </div>
          <p class="mb-4 text-gray-600">Are you sure you want to delete report group <span class="font-semibold">{{ selectedGroup?.code }}</span>? This action cannot be undone.</p>
          <div class="flex justify-end space-x-3">
            <button @click="closeDeleteModal" class="px-4 py-2 text-gray-700 border border-gray-300 rounded hover:bg-gray-50">
              <i class="fas fa-times mr-1"></i> Cancel
            </button>
            <button @click="deleteReportGroup" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700" :disabled="loading">
              <i class="fas fa-trash-alt mr-1"></i> Delete
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Toast Notifications -->
    <div class="fixed right-0 bottom-0 p-4 w-full max-w-xs">
      <div v-if="notification.show" :class="`transform transition-all p-4 rounded-lg shadow-lg ${notification.type === 'success' ? 'bg-green-500' : 'bg-red-500'} text-white`">
        <div class="flex justify-between items-center">
          <div class="flex items-center">
            <i :class="`mr-2 ${notification.type === 'success' ? 'fas fa-check-circle' : 'fas fa-exclamation-circle'}`"></i>
            <p>{{ notification.message }}</p>
          </div>
          <button @click="closeNotification" class="text-white focus:outline-none">
            <i class="fas fa-times"></i>
          </button>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script>
import { ref, computed, onMounted, watch } from 'vue';
import AppLayout from '../../../../Layouts/AppLayout.vue';
import { Head, router } from '@inertiajs/vue3';

export default {
  name: 'ReportGroup',
  components: {
    AppLayout,
    Head
  },
  props: {
    reportGroups: {
      type: Array,
      default: () => []
    }
  },
  setup(props) {
    // State variables
    const loading = ref(false);
    const showModal = ref(false);
    const showDeleteModal = ref(false);
    const isEditing = ref(false);
    const search = ref('');
    const reportGroupsData = ref(props.reportGroups || []);
    const selectedGroup = ref(null);
    const currentPage = ref(1);
    const itemsPerPage = ref(10);
    const sortField = ref('code');
    const sortDirection = ref('asc');
    
    const form = ref({
      id: null,
      code: '',
      name: '',
      is_active: true // Kept internally but not displayed
    });
    
    const errors = ref({});
    const notification = ref({
      show: false,
      message: '',
      type: 'success'
    });

    // Computed properties
    const filteredReportGroups = computed(() => {
      let result = reportGroupsData.value;
      
      if (search.value) {
        const query = search.value.toLowerCase();
        result = result.filter(group => 
          (group.code && group.code.toLowerCase().includes(query)) || 
          (group.name && group.name.toLowerCase().includes(query))
        );
      }
      
      result = [...result].sort((a, b) => {
        const field = sortField.value;
        const direction = sortDirection.value === 'asc' ? 1 : -1;
        
        if (a[field] < b[field]) return -1 * direction;
        if (a[field] > b[field]) return 1 * direction;
        return 0;
      });
      
      return result;
    });
    
    const paginatedReportGroups = computed(() => {
      const start = (currentPage.value - 1) * itemsPerPage.value;
      const end = start + itemsPerPage.value;
      return filteredReportGroups.value.slice(start, end);
    });
    
    const totalPages = computed(() => {
      const total = filteredReportGroups.value.length;
      if (total === 0) return 1;
      return Math.ceil(total / itemsPerPage.value);
    });

    // Watch for changes
    watch(filteredReportGroups, () => {
      if (currentPage.value > totalPages.value) {
        currentPage.value = totalPages.value || 1;
      }
    });
    
    watch(itemsPerPage, () => {
      currentPage.value = 1;
    });

    // Methods
    const fetchReportGroups = async () => {
      loading.value = true;
      try {
        const response = await fetch('/material-management/system-requirement/report-groups/list');
        const result = await response.json();
        
        if (result.status === 'success') {
          reportGroupsData.value = result.data;
        } else {
          showNotification('Failed to load report groups', 'error');
        }
      } catch (error) {
        showNotification('An error occurred while loading report groups', 'error');
        console.error('Error fetching report groups:', error);
      } finally {
        loading.value = false;
      }
    };

    const showAddModal = () => {
      isEditing.value = false;
      resetForm();
      showModal.value = true;
    };

    const editReportGroup = (group) => {
      isEditing.value = true;
      selectedGroup.value = group;
      form.value = {
        id: group.id,
        code: group.code,
        name: group.name,
        is_active: group.is_active
      };
      showModal.value = true;
    };

    const confirmDelete = (group) => {
      selectedGroup.value = group;
      showDeleteModal.value = true;
    };

    const saveReportGroup = async () => {
      errors.value = {};

      if (!form.value.code) {
        errors.value.code = 'Code is required';
        return;
      }

      if (!form.value.name) {
        errors.value.name = 'Name is required';
        return;
      }

      loading.value = true;
      const url = isEditing.value 
        ? `/material-management/system-requirement/report-groups/${form.value.id}`
        : '/material-management/system-requirement/report-groups';
      
      const method = isEditing.value ? 'PUT' : 'POST';
      const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

      try {
        const response = await fetch(url, {
          method,
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json',
          },
          body: JSON.stringify(form.value)
        });

        const result = await response.json();

        if (response.ok) {
          showNotification(isEditing.value ? 'Report group updated successfully' : 'Report group created successfully', 'success');
          closeModal();
          fetchReportGroups();
        } else {
          if (result.errors) {
            errors.value = result.errors;
          } else {
            showNotification(result.message || 'Failed to save report group', 'error');
          }
        }
      } catch (error) {
        showNotification('An error occurred while saving', 'error');
        console.error('Error saving report group:', error);
      } finally {
        loading.value = false;
      }
    };

    const deleteReportGroup = async () => {
      if (!selectedGroup.value) return;

      loading.value = true;
      const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

      try {
        const response = await fetch(`/material-management/system-requirement/report-groups/${selectedGroup.value.id}`, {
          method: 'DELETE',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json',
          }
        });

        const result = await response.json();

        if (response.ok) {
          showNotification('Report group deleted successfully', 'success');
          closeDeleteModal();
          fetchReportGroups();
          selectedGroup.value = null;
        } else {
          showNotification(result.message || 'Failed to delete report group', 'error');
        }
      } catch (error) {
        showNotification('An error occurred while deleting', 'error');
        console.error('Error deleting report group:', error);
      } finally {
        loading.value = false;
      }
    };

    const resetForm = () => {
      form.value = {
        id: null,
        code: '',
        name: '',
        is_active: true
      };
      errors.value = {};
    };

    const closeModal = () => {
      showModal.value = false;
      resetForm();
    };

    const closeDeleteModal = () => {
      showDeleteModal.value = false;
    };

    const refresh = () => {
      selectedGroup.value = null;
      search.value = '';
      fetchReportGroups();
    };
    
    const selectGroup = (group) => {
      selectedGroup.value = group;
    };
    
    const sortTable = (field) => {
      if (sortField.value === field) {
        sortDirection.value = sortDirection.value === 'asc' ? 'desc' : 'asc';
      } else {
        sortField.value = field;
        sortDirection.value = 'asc';
      }
    };
    
    const viewPrint = () => {
      router.get('/material-management/system-requirement/inventory-setup/report-group/view-print');
    };

    const showNotification = (message, type = 'success') => {
      notification.value = {
        show: true,
        message,
        type
      };

      setTimeout(() => {
        closeNotification();
      }, 5000);
    };

    const closeNotification = () => {
      notification.value.show = false;
    };

    onMounted(() => {
      if (reportGroupsData.value.length === 0) {
        fetchReportGroups();
      }
    });

    return {
      showModal,
      showDeleteModal,
      loading,
      isEditing,
      search,
      reportGroupsData,
      selectedGroup,
      form,
      errors,
      notification,
      filteredReportGroups,
      paginatedReportGroups,
      currentPage,
      itemsPerPage,
      totalPages,
      showAddModal,
      editReportGroup,
      confirmDelete,
      saveReportGroup,
      deleteReportGroup,
      closeModal,
      closeDeleteModal,
      refresh,
      selectGroup,
      sortTable,
      viewPrint,
      closeNotification
    };
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
