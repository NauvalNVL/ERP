<template>
  <AppLayout :header="'Define Tax Group'">
    <div class="py-6">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Main Content Card -->
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
          <div class="p-6">
            <!-- Action buttons -->
            <div class="mb-6 flex justify-between items-center">
              <div class="flex space-x-2">
                <button 
                  @click="isEditMode = false; openFormPanel()"
                  class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition-colors inline-flex items-center"
                >
                  <PlusIcon class="w-5 h-5 mr-1" />
                  Add New
                </button>
                <button 
                  @click="refreshData"
                  class="bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700 transition-colors inline-flex items-center"
                >
                  <RefreshIcon class="w-5 h-5 mr-1" />
                  Refresh
                </button>
                <button 
                  @click="exportData"
                  class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 transition-colors inline-flex items-center"
                >
                  <DocumentDownloadIcon class="w-5 h-5 mr-1" />
                  Export
                </button>
              </div>
              <div class="relative">
                <input 
                  v-model="searchQuery"
                  type="text" 
                  placeholder="Search tax groups..."
                  class="w-64 pl-10 pr-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                />
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                  <SearchIcon class="w-5 h-5 text-gray-400" />
                </div>
              </div>
            </div>
            
            <!-- Form and Table Container (side by side in large screens) -->
            <div class="flex flex-col md:flex-row space-y-4 md:space-y-0 md:space-x-4">
              <!-- Tax Group Form Panel -->
              <Transition name="slide-fade">
                <div v-if="showFormPanel" 
                    class="bg-gray-50 rounded-lg border border-gray-200 p-4 shadow-inner w-full md:w-1/3 transition-all">
                  <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-800">
                      {{ isEditMode ? 'Edit Tax Group' : 'Add New Tax Group' }}
                    </h3>
                    <button @click="closeFormPanel" class="text-gray-500 hover:text-gray-700">
                      <XIcon class="w-5 h-5" />
                    </button>
                  </div>
                  
                  <form @submit.prevent="submitForm" class="space-y-4">
                    <div>
                      <label for="code" class="block text-sm font-medium text-gray-700 mb-1">
                        Tax Group Code
                      </label>
                      <input 
                        id="code" 
                        v-model="form.code"
                        type="text"
                        :disabled="isEditMode"
                        :class="{'bg-gray-200': isEditMode}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                        placeholder="Enter code"
                        required
                        maxlength="10"
                        autocomplete="off"
                      />
                      <p v-if="errors.code" class="mt-1 text-sm text-red-600">{{ errors.code }}</p>
                    </div>
                    
                    <div>
                      <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                        Tax Group Name
                      </label>
                      <input 
                        id="name" 
                        v-model="form.name"
                        type="text"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                        placeholder="Enter name"
                        required
                        maxlength="100"
                        autocomplete="off"
                      />
                      <p v-if="errors.name" class="mt-1 text-sm text-red-600">{{ errors.name }}</p>
                    </div>
                    
                    <div class="flex justify-end space-x-2 pt-2">
                      <button 
                        type="button" 
                        @click="closeFormPanel"
                        class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                      >
                        Cancel
                      </button>
                      <button 
                        type="submit"
                        class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                      >
                        {{ isEditMode ? 'Update' : 'Save' }}
                      </button>
                    </div>
                  </form>
                </div>
              </Transition>
              
              <!-- Tax Group Table -->
              <div class="w-full" :class="{ 'md:w-2/3': showFormPanel, 'md:w-full': !showFormPanel }">
                <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
                  <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                      <thead class="bg-gray-50">
                        <tr>
                          <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Code
                          </th>
                          <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Name
                          </th>
                          <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions
                          </th>
                        </tr>
                      </thead>
                      <tbody class="bg-white divide-y divide-gray-200">
                        <tr v-if="loading">
                          <td colspan="3" class="px-6 py-4 whitespace-nowrap text-center">
                            <div class="flex justify-center">
                              <svg class="animate-spin h-5 w-5 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                              </svg>
                            </div>
                          </td>
                        </tr>
                        <tr v-else-if="filteredTaxGroups.length === 0">
                          <td colspan="3" class="px-6 py-4 whitespace-nowrap text-center text-gray-500">
                            No tax groups found
                          </td>
                        </tr>
                        <tr v-for="taxGroup in filteredTaxGroups" 
                            :key="taxGroup.code" 
                            class="hover:bg-gray-50 transition-colors"
                            :class="{'bg-blue-50': selectedTaxGroup && selectedTaxGroup.code === taxGroup.code}">
                          <td class="px-6 py-4 whitespace-nowrap">
                            <div class="font-medium text-gray-900">{{ taxGroup.code }}</div>
                          </td>
                          <td class="px-6 py-4 whitespace-nowrap text-gray-700">
                            {{ taxGroup.name }}
                          </td>
                          <td class="px-6 py-4 whitespace-nowrap text-right">
                            <div class="flex justify-end space-x-1">
                              <button 
                                @click="editTaxGroup(taxGroup)"
                                class="inline-flex items-center p-1.5 text-blue-600 hover:bg-blue-100 rounded-full transition-colors"
                                title="Edit"
                              >
                                <PencilIcon class="h-4 w-4" />
                              </button>
                              <button 
                                @click="confirmDelete(taxGroup)"
                                class="inline-flex items-center p-1.5 text-red-600 hover:bg-red-100 rounded-full transition-colors"
                                title="Delete"
                              >
                                <TrashIcon class="h-4 w-4" />
                              </button>
                            </div>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                  
                  <!-- Table Footer with Pagination -->
                  <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                    <div class="flex items-center justify-between">
                      <div class="text-sm text-gray-700">
                        Showing {{ filteredTaxGroups.length > 0 ? ((currentPage - 1) * itemsPerPage) + 1 : 0 }} 
                        to {{ Math.min(filteredTaxGroups.length, currentPage * itemsPerPage) }} 
                        of {{ totalItems }} results
                      </div>
                      <div class="flex space-x-2">
                        <button
                          @click="currentPage > 1 && currentPage--"
                          :disabled="currentPage === 1"
                          :class="[
                            'relative inline-flex items-center px-4 py-2 text-sm font-medium rounded-md',
                            currentPage === 1 
                              ? 'bg-gray-100 text-gray-400 cursor-not-allowed' 
                              : 'bg-white text-gray-700 hover:bg-gray-50 border border-gray-300'
                          ]"
                        >
                          Previous
                        </button>
                        <button
                          @click="currentPage < totalPages && currentPage++"
                          :disabled="currentPage >= totalPages"
                          :class="[
                            'relative inline-flex items-center px-4 py-2 text-sm font-medium rounded-md',
                            currentPage >= totalPages 
                              ? 'bg-gray-100 text-gray-400 cursor-not-allowed' 
                              : 'bg-white text-gray-700 hover:bg-gray-50 border border-gray-300'
                          ]"
                        >
                          Next
                        </button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Delete Confirmation Dialog -->
    <TransitionRoot appear :show="isDeleteDialogOpen" as="template">
      <Dialog as="div" @close="closeDeleteDialog" class="relative z-10">
        <TransitionChild
          as="template"
          enter="duration-300 ease-out"
          enter-from="opacity-0"
          enter-to="opacity-100"
          leave="duration-200 ease-in"
          leave-from="opacity-100"
          leave-to="opacity-0"
        >
          <div class="fixed inset-0 bg-black/25" />
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
              <DialogPanel class="w-full max-w-md transform overflow-hidden rounded-lg bg-white p-6 text-left align-middle shadow-xl transition-all">
                <DialogTitle as="h3" class="text-lg font-medium leading-6 text-gray-900">
                  Confirm Delete
                </DialogTitle>
                
                <div class="mt-3">
                  <p class="text-sm text-gray-600">
                    Are you sure you want to delete tax group <span class="font-bold">{{ selectedTaxGroup?.code }}</span> - {{ selectedTaxGroup?.name }}?
                    <br>
                    This action cannot be undone.
                  </p>
                </div>

                <div class="mt-6 flex justify-end space-x-2">
                  <button
                    type="button"
                    class="inline-flex justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2"
                    @click="closeDeleteDialog"
                  >
                    Cancel
                  </button>
                  <button
                    type="button"
                    class="inline-flex justify-center rounded-md border border-transparent bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-red-500 focus-visible:ring-offset-2"
                    @click="deleteTaxGroup"
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

    <!-- Success/Error Toast Notification -->
    <div 
      v-if="notification.show" 
      class="fixed bottom-4 right-4 p-4 rounded-lg shadow-lg w-80 z-50 transition-all transform duration-500 ease-in-out"
      :class="{
        'bg-green-50 border border-green-200': notification.type === 'success',
        'bg-red-50 border border-red-200': notification.type === 'error',
        'translate-x-0': notification.show,
        'translate-x-full': !notification.show
      }">
      <div class="flex items-center">
        <div class="flex-shrink-0">
          <CheckCircleIcon v-if="notification.type === 'success'" class="h-6 w-6 text-green-500" />
          <ExclamationCircleIcon v-else class="h-6 w-6 text-red-500" />
        </div>
        <div class="ml-3">
          <p class="text-sm font-medium" 
            :class="{
              'text-green-800': notification.type === 'success',
              'text-red-800': notification.type === 'error'
            }">
            {{ notification.message }}
          </p>
        </div>
        <div class="ml-auto pl-3">
          <div class="-mx-1.5 -my-1.5">
            <button
              @click="notification.show = false"
              class="inline-flex rounded-md p-1.5 focus:outline-none focus:ring-2 focus:ring-offset-2"
              :class="{
                'bg-green-100 text-green-500 hover:bg-green-200 focus:ring-green-600': notification.type === 'success',
                'bg-red-100 text-red-500 hover:bg-red-200 focus:ring-red-600': notification.type === 'error'
              }">
              <XIcon class="h-4 w-4" />
            </button>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue';
import { 
  Dialog, 
  DialogPanel, 
  DialogTitle,
  TransitionChild,
  TransitionRoot 
} from '@headlessui/vue';
import { 
  PlusIcon, 
  PencilIcon, 
  TrashIcon, 
  RefreshIcon, 
  DocumentDownloadIcon, 
  SearchIcon,
  XIcon,
  CheckCircleIcon,
  ExclamationCircleIcon
} from '@heroicons/vue/solid';
import AppLayout from '@/Layouts/AppLayout.vue';
import axios from 'axios';

// Reactive data
const taxGroups = ref([]);
const loading = ref(true);
const showFormPanel = ref(false);
const isEditMode = ref(false);
const selectedTaxGroup = ref(null);
const isDeleteDialogOpen = ref(false);
const searchQuery = ref('');
const currentPage = ref(1);
const itemsPerPage = ref(10);
const errors = ref({});
const form = ref({ code: '', name: '' });
const notification = ref({
  show: false,
  type: 'success',
  message: ''
});

// Computed properties
const filteredTaxGroups = computed(() => {
  let filtered = [...taxGroups.value];
  
  if (searchQuery.value) {
    const query = searchQuery.value.toLowerCase();
    filtered = filtered.filter(group => 
      group.code.toLowerCase().includes(query) || 
      group.name.toLowerCase().includes(query)
    );
  }
  
  const startIndex = (currentPage.value - 1) * itemsPerPage.value;
  return filtered.slice(startIndex, startIndex + itemsPerPage.value);
});

const totalItems = computed(() => {
  if (!searchQuery.value) {
    return taxGroups.value.length;
  }
  
  const query = searchQuery.value.toLowerCase();
  return taxGroups.value.filter(group => 
    group.code.toLowerCase().includes(query) || 
    group.name.toLowerCase().includes(query)
  ).length;
});

const totalPages = computed(() => Math.ceil(totalItems.value / itemsPerPage.value));

// Methods
const fetchTaxGroups = async () => {
  loading.value = true;
  try {
    const response = await axios.get('/api/material-management/tax-groups');
    taxGroups.value = response.data;
  } catch (error) {
    showNotification('Error loading tax groups', 'error');
    console.error('Failed to load tax groups:', error);
  } finally {
    loading.value = false;
  }
};

const openFormPanel = () => {
  showFormPanel.value = true;
  if (!isEditMode.value) {
    form.value = { code: '', name: '' };
  }
  errors.value = {};
};

const closeFormPanel = () => {
  showFormPanel.value = false;
  setTimeout(() => {
    isEditMode.value = false;
    selectedTaxGroup.value = null;
    form.value = { code: '', name: '' };
    errors.value = {};
  }, 300);
};

const editTaxGroup = (taxGroup) => {
  isEditMode.value = true;
  selectedTaxGroup.value = taxGroup;
  form.value = {
    code: taxGroup.code,
    name: taxGroup.name
  };
  openFormPanel();
};

const submitForm = async () => {
  errors.value = {};
  
  try {
    if (isEditMode.value) {
      await axios.put(`/api/material-management/tax-groups/${form.value.code}`, form.value);
      showNotification('Tax group updated successfully', 'success');
    } else {
      // Convert code to uppercase
      form.value.code = form.value.code.toUpperCase();
      await axios.post('/api/material-management/tax-groups', form.value);
      showNotification('Tax group created successfully', 'success');
    }
    
    await fetchTaxGroups();
    closeFormPanel();
  } catch (error) {
    if (error.response && error.response.data && error.response.data.errors) {
      errors.value = error.response.data.errors;
    } else if (error.response && error.response.data && error.response.data.message) {
      showNotification(error.response.data.message, 'error');
    } else {
      showNotification('An error occurred while saving', 'error');
    }
  }
};

const confirmDelete = (taxGroup) => {
  selectedTaxGroup.value = taxGroup;
  isDeleteDialogOpen.value = true;
};

const closeDeleteDialog = () => {
  isDeleteDialogOpen.value = false;
  // Clear selection after dialog animation completes
  setTimeout(() => {
    selectedTaxGroup.value = null;
  }, 300);
};

const deleteTaxGroup = async () => {
  if (!selectedTaxGroup.value) return;
  
  try {
    await axios.delete(`/api/material-management/tax-groups/${selectedTaxGroup.value.code}`);
    showNotification('Tax group deleted successfully', 'success');
    await fetchTaxGroups();
  } catch (error) {
    if (error.response && error.response.data && error.response.data.message) {
      showNotification(error.response.data.message, 'error');
    } else {
      showNotification('Error deleting tax group', 'error');
    }
    console.error('Delete error:', error);
  } finally {
    closeDeleteDialog();
  }
};

const refreshData = () => {
  fetchTaxGroups();
  searchQuery.value = '';
  currentPage.value = 1;
};

const exportData = () => {
  const csvRows = [];
  // Add CSV header
  csvRows.push(['Code', 'Name'].join(','));
  
  // Add data rows
  taxGroups.value.forEach(group => {
    csvRows.push([
      // Wrap fields in quotes to handle commas in text
      `"${group.code}"`,
      `"${group.name}"`
    ].join(','));
  });
  
  const csvContent = csvRows.join('\n');
  const blob = new Blob([csvContent], { type: 'text/csv' });
  const url = window.URL.createObjectURL(blob);
  
  const a = document.createElement('a');
  a.setAttribute('hidden', '');
  a.setAttribute('href', url);
  a.setAttribute('download', 'tax-groups.csv');
  document.body.appendChild(a);
  a.click();
  document.body.removeChild(a);
};

const showNotification = (message, type = 'success') => {
  notification.value = {
    show: true,
    type,
    message
  };
  
  // Auto-hide notification after 3 seconds
  setTimeout(() => {
    notification.value.show = false;
  }, 3000);
};

// Reset to first page when search query changes
watch(searchQuery, () => {
  currentPage.value = 1;
});

// Initialize data
onMounted(() => {
  fetchTaxGroups();
});
</script>

<style scoped>
.slide-fade-enter-active,
.slide-fade-leave-active {
  transition: all 0.3s ease;
}

.slide-fade-enter-from,
.slide-fade-leave-to {
  transform: translateX(-20px);
  opacity: 0;
}

@media (min-width: 768px) {
  .table-transition {
    transition: width 0.3s ease;
  }
}
</style>
