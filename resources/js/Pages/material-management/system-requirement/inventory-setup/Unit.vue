<template>
  <AppLayout :header="'Define Unit'">
    <Head title="Define Unit" />

    <!-- Header Section -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-800 p-6 rounded-t-lg shadow-md">
      <h2 class="text-2xl font-bold text-white mb-2 flex items-center">
        <i class="fas fa-ruler mr-3"></i> Define Unit
      </h2>
      <p class="text-blue-100">Manage measurement units for inventory items</p>
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
                <input type="text" v-model="searchTerm" placeholder="Search by code or name..."
                  class="pl-10 pr-4 py-2 w-full border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
              </div>
            </div>
            <div class="flex gap-2 w-full md:w-auto">
              <button @click="openCreateModal" class="btn-primary flex-1 md:flex-none">
                <i class="fas fa-plus-circle mr-2"></i> New Unit
              </button>
              <button @click="refreshData" class="btn-secondary flex-1 md:flex-none">
                <i class="fas fa-sync-alt mr-2"></i> Refresh
              </button>
              <Link href="/material-management/system-requirement/inventory-setup/unit/view-print" class="btn-info flex-1 md:flex-none">
                <i class="fas fa-print mr-2"></i> Print
              </Link>
            </div>
          </div>
              
          <!-- Table Section -->
          <div class="bg-white rounded-lg overflow-hidden border border-gray-200">
            <div class="overflow-x-auto">
              <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                  <tr>
                    <th @click="sortBy('code')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                      <div class="flex items-center">
                        Code <i class="fas fa-sort ml-1"></i>
                      </div>
                    </th>
                    <th @click="sortBy('short_name')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                      <div class="flex items-center">
                        Short Name <i class="fas fa-sort ml-1"></i>
                      </div>
                    </th>
                    <th @click="sortBy('full_name')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                      <div class="flex items-center">
                        Full Name <i class="fas fa-sort ml-1"></i>
                      </div>
                    </th>
                  </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                  <tr v-if="loading" class="animate-pulse">
                    <td colspan="3" class="px-6 py-4 text-center text-sm text-gray-500">
                      <div class="flex justify-center items-center space-x-2">
                        <i class="fas fa-spinner fa-spin"></i>
                        <span>Loading units...</span>
                      </div>
                    </td>
                  </tr>
                  <tr v-else-if="filteredUnits.length === 0" class="hover:bg-gray-50">
                    <td colspan="3" class="px-6 py-4 text-center text-sm text-gray-500">
                      No units found. Click "New Unit" to create one or use "Seed Data" to generate sample data.
                    </td>
                  </tr>
                  <tr v-for="unit in paginatedUnits" :key="unit.code" 
                      @click="selectUnit(unit)" 
                      :class="{'bg-blue-50': selectedUnit && selectedUnit.code === unit.code}"
                      class="hover:bg-gray-50 cursor-pointer">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ unit.code }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ unit.short_name }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ unit.full_name }}</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
                  
          <!-- Pagination Controls -->
          <div class="mt-4 flex justify-between items-center text-sm text-gray-600">
            <div>
              <span>Showing {{ paginatedUnits.length }} of {{ filteredUnits.length }} units</span>
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
          <div v-if="selectedUnit" class="mb-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-3 flex items-center">
              <i class="fas fa-info-circle mr-2 text-blue-500"></i> Unit Details
            </h3>
            <div class="space-y-3">
              <div class="flex justify-between border-b border-gray-200 pb-2">
                <span class="text-gray-600">Code:</span>
                <span class="font-medium text-gray-900">{{ selectedUnit.code }}</span>
              </div>
              <div class="flex justify-between border-b border-gray-200 pb-2">
                <span class="text-gray-600">Short Name:</span>
                <span class="font-medium text-gray-900 text-right">{{ selectedUnit.short_name }}</span>
              </div>
              <div class="flex justify-between border-b border-gray-200 pb-2">
                <span class="text-gray-600">Full Name:</span>
                <span class="font-medium text-gray-900 text-right">{{ selectedUnit.full_name }}</span>
              </div>
            </div>
            <div class="mt-6">
              <button @click="editUnit(selectedUnit)" class="w-full btn-blue">
                <i class="fas fa-edit mr-1"></i> Edit
              </button>
            </div>
          </div>
          <div v-else class="flex flex-col items-center justify-center h-64 text-center">
            <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mb-4">
              <i class="fas fa-ruler text-blue-500 text-2xl"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-1">No Unit Selected</h3>
            <p class="text-gray-500 mb-4">Select a unit from the table to view details</p>
            <button @click="openCreateModal" class="btn-primary">
              <i class="fas fa-plus-circle mr-1"></i> Create New Unit
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Form Modal -->
    <TransitionRoot appear :show="isModalOpen" as="template">
      <Dialog as="div" @close="closeModal" class="relative z-10">
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
              <DialogPanel class="w-full max-w-md transform overflow-hidden rounded-xl bg-white p-6 text-left align-middle shadow-xl transition-all">
                <DialogTitle as="h3" class="text-lg font-medium leading-6 text-gray-900 flex items-center">
                  <i class="fas fa-ruler mr-2 text-blue-600"></i>
                  {{ isEditing ? 'Edit Unit' : 'New Unit' }}
                </DialogTitle>

                <div class="mt-4 space-y-4">
                  <div v-if="formErrors" class="bg-red-50 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <strong class="font-bold">Error!</strong>
                    <ul class="mt-1 list-disc list-inside">
                      <li v-for="(error, field) in formErrors" :key="field">{{ error[0] }}</li>
                    </ul>
                  </div>

                  <div>
                    <label for="code" class="block text-sm font-medium text-gray-700">Unit Code:</label>
                    <input 
                      type="text"
                      id="code" 
                      v-model="form.code" 
                      :disabled="isEditing"
                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                      :class="{ 'bg-gray-100': isEditing }"
                      placeholder="e.g., KG, PCS"
                      maxlength="20"
                      @input="form.code = form.code.toUpperCase()"
                    />
                    <p v-if="formErrors?.code" class="mt-1 text-sm text-red-600">{{ formErrors.code[0] }}</p>
                  </div>

                  <div>
                    <label for="short_name" class="block text-sm font-medium text-gray-700">Short Name:</label>
                    <input 
                      type="text"
                      id="short_name" 
                      v-model="form.short_name" 
                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                      placeholder="e.g., KG"
                      maxlength="50"
                    />
                    <p v-if="formErrors?.short_name" class="mt-1 text-sm text-red-600">{{ formErrors.short_name[0] }}</p>
                  </div>

                  <div>
                    <label for="full_name" class="block text-sm font-medium text-gray-700">Full Name:</label>
                    <input 
                      type="text"
                      id="full_name" 
                      v-model="form.full_name" 
                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                      placeholder="e.g., KILOGRAM"
                      maxlength="150"
                    />
                    <p v-if="formErrors?.full_name" class="mt-1 text-sm text-red-600">{{ formErrors.full_name[0] }}</p>
                  </div>
                </div>

                <div class="mt-6 flex justify-end space-x-3">
                  <button 
                    type="button" 
                    @click="closeModal"
                    class="inline-flex justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                  >
                    Cancel
                  </button>
                  <button 
                    type="button"
                    @click="saveUnit"
                    class="inline-flex justify-center rounded-md border border-transparent bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                  >
                    {{ isEditing ? 'Update' : 'Save' }}
                  </button>
                </div>
              </DialogPanel>
            </TransitionChild>
          </div>
        </div>
      </Dialog>
    </TransitionRoot>

    <!-- Toast Notification -->
    <div
      v-if="toast.show"
      class="fixed bottom-4 right-4 z-50 flex items-center p-4 mb-4 w-full max-w-xs rounded-lg shadow"
      :class="toast.type === 'success' ? 'text-green-800 bg-green-50' : 'text-red-800 bg-red-50'"
      role="alert"
    >
      <div class="inline-flex flex-shrink-0 justify-center items-center w-8 h-8 rounded-lg" 
        :class="toast.type === 'success' ? 'text-green-500 bg-green-100' : 'text-red-500 bg-red-100'">
        <i :class="toast.type === 'success' ? 'fas fa-check' : 'fas fa-exclamation'"></i>
      </div>
      <div class="ml-3 text-sm font-normal">{{ toast.message }}</div>
      <button
        @click="toast.show = false"
        type="button"
        class="ml-auto -mx-1.5 -my-1.5 rounded-lg focus:ring-2 p-1.5 inline-flex h-8 w-8"
        :class="toast.type === 'success' ? 'text-green-500 hover:bg-green-200 focus:ring-green-400' : 'text-red-500 hover:bg-red-200 focus:ring-red-400'"
      >
        <span class="sr-only">Close</span>
        <i class="fas fa-times"></i>
      </button>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue';
import { Link, Head } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import { Dialog, DialogPanel, DialogTitle, TransitionChild, TransitionRoot } from '@headlessui/vue';
import axios from 'axios';

// State variables
const units = ref([]);
const selectedUnit = ref(null);
const loading = ref(false);
const searchTerm = ref('');
const isModalOpen = ref(false);
const isEditing = ref(false);
const formErrors = ref(null);
const currentPage = ref(1);
const itemsPerPage = ref(10);
const sortOrder = ref({
  field: 'code',
  direction: 'asc'
});

// Toast notification state
const toast = ref({
  show: false,
  message: '',
  type: 'success',
  timeout: null
});

// Form state
const form = ref({
  code: '',
  short_name: '',
  full_name: '',
  is_active: true // Keep this for the backend, but don't show in UI
});

// Computed properties
const filteredUnits = computed(() => {
  if (!searchTerm.value) return units.value;
  
  const term = searchTerm.value.toLowerCase();
  return units.value.filter(unit => 
    unit.code.toLowerCase().includes(term) || 
    unit.short_name.toLowerCase().includes(term) ||
    unit.full_name.toLowerCase().includes(term)
  );
});

const paginatedUnits = computed(() => {
  const start = (currentPage.value - 1) * itemsPerPage.value;
  const end = start + itemsPerPage.value;
  return filteredUnits.value.slice(start, end);
});

const totalPages = computed(() => {
  const total = filteredUnits.value.length;
  return total === 0 ? 1 : Math.ceil(total / itemsPerPage.value);
});

// Methods
const fetchUnits = async () => {
  loading.value = true;
  try {
    const response = await axios.get('/api/material-management/units');
    units.value = response.data;
    if (selectedUnit.value) {
      const updated = units.value.find(u => u.code === selectedUnit.value.code);
      if (updated) {
        selectedUnit.value = updated;
      } else {
        selectedUnit.value = null;
      }
    }
  } catch (error) {
    showToast('Failed to load units', 'error');
    console.error('Error fetching units:', error);
  } finally {
    loading.value = false;
  }
};

const selectUnit = (unit) => {
  selectedUnit.value = unit;
};

const sortBy = (field) => {
  if (sortOrder.value.field === field) {
    sortOrder.value.direction = sortOrder.value.direction === 'asc' ? 'desc' : 'asc';
  } else {
    sortOrder.value.field = field;
    sortOrder.value.direction = 'asc';
  }
  
  units.value.sort((a, b) => {
    const direction = sortOrder.value.direction === 'asc' ? 1 : -1;
    if (a[field] < b[field]) return -1 * direction;
    if (a[field] > b[field]) return 1 * direction;
    return 0;
  });
};

const openCreateModal = () => {
  isEditing.value = false;
  form.value = {
    code: '',
    short_name: '',
    full_name: '',
    is_active: true
  };
  formErrors.value = null;
  isModalOpen.value = true;
};

const editUnit = (unit) => {
  isEditing.value = true;
  form.value = {
    code: unit.code,
    short_name: unit.short_name,
    full_name: unit.full_name,
    is_active: unit.is_active
  };
  formErrors.value = null;
  isModalOpen.value = true;
};

const closeModal = () => {
  isModalOpen.value = false;
};

const saveUnit = async () => {
  formErrors.value = null;
  
  try {
    if (isEditing.value) {
      await axios.put(`/api/material-management/units/${form.value.code}`, form.value);
      showToast('Unit updated successfully', 'success');
    } else {
      await axios.post('/api/material-management/units', form.value);
      showToast('Unit created successfully', 'success');
    }

    closeModal();
    fetchUnits();
  } catch (error) {
    if (error.response && error.response.data && error.response.data.errors) {
      formErrors.value = error.response.data.errors;
    } else {
      showToast(isEditing.value ? 'Failed to update unit' : 'Failed to create unit', 'error');
    }
    console.error('Error saving unit:', error);
  }
};

const refreshData = () => {
  fetchUnits();
};

const showToast = (message, type = 'success') => {
  if (toast.value.timeout) {
    clearTimeout(toast.value.timeout);
  }
  
  toast.value = {
    show: true,
    message,
    type,
    timeout: setTimeout(() => {
      toast.value.show = false;
    }, 3000)
  };
};

// Watch for changes in filtered units
watch(filteredUnits, () => {
  if (currentPage.value > totalPages.value) {
    currentPage.value = totalPages.value;
  }
});

watch(itemsPerPage, () => {
  currentPage.value = 1;
});

// Lifecycle hooks
onMounted(() => {
  fetchUnits();
});
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
  width: 8px;
}

.overflow-x-auto::-webkit-scrollbar-track {
  background: transparent;
}

.overflow-x-auto::-webkit-scrollbar-thumb {
  background-color: rgba(156, 163, 175, 0.5);
  border-radius: 20px;
}
</style>
