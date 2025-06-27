<template>
  <AppLayout :header="'Define Location'">
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
                  placeholder="Search locations..."
                  class="w-64 pl-10 pr-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                />
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                  <SearchIcon class="w-5 h-5 text-gray-400" />
                </div>
              </div>
            </div>
            
            <!-- Form and Table Container (side by side in large screens) -->
            <div class="flex flex-col md:flex-row space-y-4 md:space-y-0 md:space-x-4">
              <!-- Location Form Panel -->
              <Transition name="slide-fade">
                <div v-if="showFormPanel" 
                    class="bg-gray-50 rounded-lg border border-gray-200 p-4 shadow-inner w-full md:w-2/5 lg:w-1/3 transition-all">
                  <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-800">
                      {{ isEditMode ? 'Edit Location' : 'Add New Location' }}
                    </h3>
                    <button @click="closeFormPanel" class="text-gray-500 hover:text-gray-700">
                      <XIcon class="w-5 h-5" />
                    </button>
                  </div>
                  
                  <form @submit.prevent="submitForm" class="space-y-4">
                    <div>
                      <label for="code" class="block text-sm font-medium text-gray-700 mb-1">
                        Location Code
                      </label>
                      <input 
                        id="code" 
                        v-model="form.code"
                        type="text"
                        :disabled="isEditMode"
                        :class="{'bg-gray-200': isEditMode}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                        placeholder="Enter location code"
                        required
                        maxlength="20"
                        autocomplete="off"
                      />
                      <p v-if="errors.code" class="mt-1 text-sm text-red-600">{{ errors.code }}</p>
                    </div>
                    
                    <div>
                      <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                        Location Name
                      </label>
                      <input 
                        id="name" 
                        v-model="form.name"
                        type="text"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                        placeholder="Enter location name"
                        required
                        maxlength="100"
                        autocomplete="off"
                      />
                      <p v-if="errors.name" class="mt-1 text-sm text-red-600">{{ errors.name }}</p>
                    </div>
                    
                    <div>
                      <label for="description" class="block text-sm font-medium text-gray-700 mb-1">
                        Description
                      </label>
                      <textarea 
                        id="description" 
                        v-model="form.description"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                        placeholder="Enter description"
                        rows="3"
                      ></textarea>
                      <p v-if="errors.description" class="mt-1 text-sm text-red-600">{{ errors.description }}</p>
                    </div>
                    
                    <div class="flex items-center">
                      <Switch
                        v-model="form.is_active"
                        :class="form.is_active ? 'bg-blue-600' : 'bg-gray-200'"
                        class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                      >
                        <span
                          :class="form.is_active ? 'translate-x-6' : 'translate-x-1'"
                          class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform"
                        />
                      </Switch>
                      <span class="ml-3 text-sm font-medium text-gray-700">Active</span>
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
              
              <!-- Locations Table -->
              <div class="w-full" :class="{ 'md:w-3/5 lg:w-2/3': showFormPanel, 'md:w-full': !showFormPanel }">
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
                          <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Description
                          </th>
                          <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status
                          </th>
                          <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions
                          </th>
                        </tr>
                      </thead>
                      <tbody class="bg-white divide-y divide-gray-200">
                        <tr v-if="loading">
                          <td colspan="5" class="px-6 py-4 whitespace-nowrap text-center">
                            <div class="flex justify-center">
                              <svg class="animate-spin h-5 w-5 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                              </svg>
                            </div>
                          </td>
                        </tr>
                        <tr v-else-if="filteredLocations.length === 0">
                          <td colspan="5" class="px-6 py-4 whitespace-nowrap text-center text-gray-500">
                            No locations found
                          </td>
                        </tr>
                        <tr v-for="location in filteredLocations" 
                            :key="location.code" 
                            class="hover:bg-gray-50 transition-colors"
                            :class="{'bg-blue-50': selectedLocation && selectedLocation.code === location.code}">
                          <td class="px-6 py-4 whitespace-nowrap">
                            <div class="font-medium text-gray-900">{{ location.code }}</div>
                          </td>
                          <td class="px-6 py-4 whitespace-nowrap text-gray-700">
                            {{ location.name }}
                          </td>
                          <td class="px-6 py-4 whitespace-nowrap text-gray-700 max-w-[200px] truncate">
                            {{ location.description || '—' }}
                          </td>
                          <td class="px-6 py-4 whitespace-nowrap">
                            <span 
                              class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                              :class="location.is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'"
                            >
                              {{ location.is_active ? 'Active' : 'Inactive' }}
                            </span>
                          </td>
                          <td class="px-6 py-4 whitespace-nowrap text-right">
                            <div class="flex justify-end space-x-1">
                              <button 
                                @click="showDetails(location)"
                                class="inline-flex items-center p-1.5 text-gray-600 hover:bg-gray-100 rounded-full transition-colors"
                                title="View Details"
                              >
                                <EyeIcon class="h-4 w-4" />
                              </button>
                              <button 
                                @click="toggleStatus(location)"
                                class="inline-flex items-center p-1.5 hover:bg-gray-100 rounded-full transition-colors"
                                :class="location.is_active ? 'text-green-600 hover:bg-green-100' : 'text-red-600 hover:bg-red-100'"
                                :title="location.is_active ? 'Deactivate' : 'Activate'"
                              >
                                <SwitchHorizontalIcon class="h-4 w-4" />
                              </button>
                              <button 
                                @click="editLocation(location)"
                                class="inline-flex items-center p-1.5 text-blue-600 hover:bg-blue-100 rounded-full transition-colors"
                                title="Edit"
                              >
                                <PencilIcon class="h-4 w-4" />
                              </button>
                              <button 
                                @click="confirmDelete(location)"
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
                        Showing {{ filteredLocations.length > 0 ? ((currentPage - 1) * itemsPerPage) + 1 : 0 }} 
                        to {{ Math.min(totalItems, currentPage * itemsPerPage) }} 
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
                    Are you sure you want to delete location <span class="font-bold">{{ selectedLocation?.code }}</span> - {{ selectedLocation?.name }}?
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
                    @click="deleteLocation"
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
    
    <!-- Detail Viewing Dialog -->
    <TransitionRoot appear :show="isDetailDialogOpen" as="template">
      <Dialog as="div" @close="closeDetailDialog" class="relative z-10">
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
                <DialogTitle as="h3" class="text-lg font-medium leading-6 text-gray-900 flex justify-between items-center">
                  <span>Location Details</span>
                  <button @click="closeDetailDialog" class="text-gray-500 hover:text-gray-700">
                    <XIcon class="w-5 h-5" />
                  </button>
                </DialogTitle>
                
                <div class="mt-4">
                  <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 rounded-t-lg">
                    <dt class="text-sm font-medium text-gray-500">Code</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">{{ selectedLocation?.code }}</dd>
                  </div>
                  <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Name</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">{{ selectedLocation?.name }}</dd>
                  </div>
                  <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Description</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">{{ selectedLocation?.description || '—' }}</dd>
                  </div>
                  <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 rounded-b-lg">
                    <dt class="text-sm font-medium text-gray-500">Status</dt>
                    <dd class="mt-1 text-sm sm:col-span-2 sm:mt-0">
                      <span 
                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                        :class="selectedLocation?.is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'"
                      >
                        {{ selectedLocation?.is_active ? 'Active' : 'Inactive' }}
                      </span>
                    </dd>
                  </div>
                </div>

                <div class="mt-6 flex justify-end space-x-2">
                  <button
                    type="button"
                    class="inline-flex justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2"
                    @click="closeDetailDialog"
                  >
                    Close
                  </button>
                  <button
                    type="button"
                    class="inline-flex justify-center rounded-md border border-transparent bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2"
                    @click="editFromDetails"
                  >
                    Edit
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
  TransitionRoot,
  Switch
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
  ExclamationCircleIcon,
  EyeIcon,
  LocationMarkerIcon,
  SwitchHorizontalIcon
} from '@heroicons/vue/solid';
import AppLayout from '@/Layouts/AppLayout.vue';
import axios from 'axios';

// Reactive data
const locations = ref([]);
const loading = ref(true);
const showFormPanel = ref(false);
const isEditMode = ref(false);
const selectedLocation = ref(null);
const isDeleteDialogOpen = ref(false);
const isDetailDialogOpen = ref(false);
const searchQuery = ref('');
const currentPage = ref(1);
const itemsPerPage = ref(10);
const errors = ref({});
const form = ref({ 
  code: '', 
  name: '',
  description: '',
  is_active: true
});
const notification = ref({
  show: false,
  type: 'success',
  message: ''
});

// Computed properties
const filteredLocations = computed(() => {
  let filtered = [...locations.value];
  
  if (searchQuery.value) {
    const query = searchQuery.value.toLowerCase();
    filtered = filtered.filter(loc => 
      loc.code.toLowerCase().includes(query) || 
      loc.name.toLowerCase().includes(query) ||
      (loc.description && loc.description.toLowerCase().includes(query))
    );
  }
  
  const startIndex = (currentPage.value - 1) * itemsPerPage.value;
  return filtered.slice(startIndex, startIndex + itemsPerPage.value);
});

const totalItems = computed(() => {
  if (!searchQuery.value) {
    return locations.value.length;
  }
  
  const query = searchQuery.value.toLowerCase();
  return locations.value.filter(loc => 
    loc.code.toLowerCase().includes(query) || 
    loc.name.toLowerCase().includes(query) ||
    (loc.description && loc.description.toLowerCase().includes(query))
  ).length;
});

const totalPages = computed(() => Math.ceil(totalItems.value / itemsPerPage.value));

// Methods
const fetchLocations = async () => {
  loading.value = true;
  try {
    const response = await axios.get('/api/material-management/locations');
    locations.value = response.data;
  } catch (error) {
    showNotification('Error loading locations', 'error');
    console.error('Failed to load locations:', error);
  } finally {
    loading.value = false;
  }
};

const openFormPanel = () => {
  showFormPanel.value = true;
  if (!isEditMode.value) {
    form.value = { 
      code: '', 
      name: '',
      description: '',
      is_active: true
    };
  }
  errors.value = {};
};

const closeFormPanel = () => {
  showFormPanel.value = false;
  setTimeout(() => {
    isEditMode.value = false;
    selectedLocation.value = null;
    form.value = { 
      code: '', 
      name: '',
      description: '',
      is_active: true
    };
    errors.value = {};
  }, 300);
};

const editLocation = (location) => {
  isEditMode.value = true;
  selectedLocation.value = location;
  form.value = {
    code: location.code,
    name: location.name,
    description: location.description || '',
    is_active: location.is_active
  };
  openFormPanel();
  closeDetailDialog();
};

const editFromDetails = () => {
  editLocation(selectedLocation.value);
  closeDetailDialog();
};

const submitForm = async () => {
  errors.value = {};
  
  try {
    if (isEditMode.value) {
      await axios.put(`/api/material-management/locations/${form.value.code}`, form.value);
      showNotification('Location updated successfully', 'success');
    } else {
      // Convert code to uppercase
      form.value.code = form.value.code.toUpperCase();
      await axios.post('/api/material-management/locations', form.value);
      showNotification('Location created successfully', 'success');
    }
    
    await fetchLocations();
    closeFormPanel();
  } catch (error) {
    if (error.response && error.response.data && error.response.data.errors) {
      errors.value = error.response.data.errors;
    } else if (error.response && error.response.data && error.response.data.message) {
      showNotification(error.response.data.message, 'error');
    } else {
      showNotification('An error occurred while saving', 'error');
    }
    console.error('Submit error:', error);
  }
};

const toggleStatus = async (location) => {
  try {
    await axios.patch(`/api/material-management/locations/${location.code}/toggle-active`);
    showNotification(`Location ${location.is_active ? 'deactivated' : 'activated'} successfully`, 'success');
    await fetchLocations();
  } catch (error) {
    showNotification('Error updating location status', 'error');
    console.error('Toggle status error:', error);
  }
};

const showDetails = (location) => {
  selectedLocation.value = location;
  isDetailDialogOpen.value = true;
};

const closeDetailDialog = () => {
  isDetailDialogOpen.value = false;
};

const confirmDelete = (location) => {
  selectedLocation.value = location;
  isDeleteDialogOpen.value = true;
};

const closeDeleteDialog = () => {
  isDeleteDialogOpen.value = false;
  // Clear selection after dialog animation completes
  setTimeout(() => {
    selectedLocation.value = null;
  }, 300);
};

const deleteLocation = async () => {
  if (!selectedLocation.value) return;
  
  try {
    await axios.delete(`/api/material-management/locations/${selectedLocation.value.code}`);
    showNotification('Location deleted successfully', 'success');
    await fetchLocations();
  } catch (error) {
    if (error.response && error.response.data && error.response.data.message) {
      showNotification(error.response.data.message, 'error');
    } else {
      showNotification('Error deleting location', 'error');
    }
    console.error('Delete error:', error);
  } finally {
    closeDeleteDialog();
  }
};

const refreshData = () => {
  fetchLocations();
  searchQuery.value = '';
  currentPage.value = 1;
};

const exportData = () => {
  const csvRows = [];
  // Add CSV header
  csvRows.push(['Code', 'Name', 'Description', 'Status'].join(','));
  
  // Add data rows
  locations.value.forEach(loc => {
    csvRows.push([
      // Wrap fields in quotes to handle commas in text
      `"${loc.code}"`,
      `"${loc.name}"`,
      `"${loc.description || ''}"`,
      `"${loc.is_active ? 'Active' : 'Inactive'}"`,
    ].join(','));
  });
  
  const csvContent = csvRows.join('\n');
  const blob = new Blob([csvContent], { type: 'text/csv' });
  const url = window.URL.createObjectURL(blob);
  
  const a = document.createElement('a');
  a.setAttribute('hidden', '');
  a.setAttribute('href', url);
  a.setAttribute('download', 'locations.csv');
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

// Seed data function (for development)
const seedData = async () => {
  try {
    await axios.post('/api/material-management/locations/seed');
    showNotification('Sample data seeded successfully', 'success');
    fetchLocations();
  } catch (error) {
    showNotification('Error seeding data', 'error');
    console.error('Seed error:', error);
  }
};

// Initialize data
onMounted(() => {
  fetchLocations();
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
