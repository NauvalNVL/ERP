<template>
  <AppLayout :header="'Define Category'">
    <div class="py-6">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <!-- Top Action Bar -->
          <div class="p-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
            <div class="flex items-center space-x-2">
          <button
                @click="openCreateModal"
                class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
          >
                <i class="fas fa-plus mr-2"></i>
                New
          </button>
          <button
                @click="refreshData"
                class="inline-flex items-center px-3 py-2 border border-gray-300 text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
          >
                <i class="fas fa-sync-alt mr-2"></i>
                Refresh
          </button>
        </div>
            <div class="flex items-center space-x-2">
              <div class="relative">
          <input
            type="text"
                  v-model="searchTerm"
                  placeholder="Search..."
                  class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
          />
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                  <i class="fas fa-search text-gray-400"></i>
        </div>
        </div>
              <button
                @click="seedSampleData"
                class="inline-flex items-center px-3 py-2 border border-gray-300 text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
          >
                <i class="fas fa-database mr-2"></i>
                Seed Data
              </button>
              <Link
                href="/material-management/system-requirement/inventory-setup/category/view-print"
                class="inline-flex items-center px-3 py-2 border border-gray-300 text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
          >
                <i class="fas fa-print mr-2"></i>
                View & Print
              </Link>
        </div>
      </div>
      
          <!-- Category Table -->
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
                <tr v-if="loading" class="animate-pulse">
                  <td colspan="5" class="px-6 py-4 whitespace-nowrap text-center text-gray-500">
                    Loading categories...
                  </td>
                </tr>
                <tr v-else-if="filteredCategories.length === 0" class="hover:bg-gray-50">
                  <td colspan="5" class="px-6 py-4 whitespace-nowrap text-center text-gray-500">
                    No categories found. Click "New" to create one or "Seed Data" to generate sample data.
                  </td>
                </tr>
                <tr v-for="category in filteredCategories" :key="category.code" class="hover:bg-gray-50">
                  <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                {{ category.code }}
              </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                {{ category.name }}
              </td>
                  <td class="px-6 py-4 text-sm text-gray-500 max-w-xs truncate">
                    {{ category.description || 'No description' }}
              </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm">
                    <span
                      :class="category.is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'"
                      class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                    >
                  {{ category.is_active ? 'Active' : 'Inactive' }}
                </span>
              </td>
                  <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                <button 
                      @click="editCategory(category)"
                      class="text-indigo-600 hover:text-indigo-900 mr-3"
                >
                      <i class="fas fa-edit"></i>
                </button>
                <button 
                      @click="toggleCategoryStatus(category)"
                      :class="category.is_active ? 'text-red-600 hover:text-red-900' : 'text-green-600 hover:text-green-900'"
                >
                      <i :class="category.is_active ? 'fas fa-ban' : 'fas fa-check-circle'"></i>
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
        </div>
      </div>
    </div>

    <!-- Create/Edit Modal -->
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
              <DialogPanel class="w-full max-w-md transform overflow-hidden rounded-2xl bg-white p-6 text-left align-middle shadow-xl transition-all">
                <DialogTitle as="h3" class="text-lg font-medium leading-6 text-gray-900">
                  {{ isEditing ? 'Edit Category' : 'Define New Category' }}
                </DialogTitle>

                <div class="mt-4 space-y-4">
                  <div v-if="formErrors" class="bg-red-50 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <strong class="font-bold">Error!</strong>
                    <ul class="mt-1 list-disc list-inside">
                      <li v-for="(error, field) in formErrors" :key="field">{{ error[0] }}</li>
                    </ul>
                  </div>

                  <div>
                    <label for="code" class="block text-sm font-medium text-gray-700">Category Code:</label>
                    <input 
                      type="text"
                      id="code" 
                      v-model="form.code" 
                      :disabled="isEditing"
                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                      :class="{ 'bg-gray-100': isEditing }"
                      placeholder="e.g., RAW, FINISH"
                      maxlength="20"
                    />
                  </div>

                  <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Category Name:</label>
                    <input 
                      type="text"
                      id="name" 
                      v-model="form.name" 
                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                      placeholder="e.g., RAW MATERIALS"
                      maxlength="100"
                    />
                  </div>

                  <div>
                    <label for="description" class="block text-sm font-medium text-gray-700">Description:</label>
                    <textarea 
                      id="description" 
                      v-model="form.description" 
                      rows="3" 
                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                      placeholder="Optional description"
                    ></textarea>
                  </div>

                  <div class="flex items-center">
                    <input
                      id="is_active"
                      type="checkbox"
                      v-model="form.is_active"
                      class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
                    />
                    <label for="is_active" class="ml-2 block text-sm text-gray-900">Active</label>
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
                    @click="saveCategory"
                    class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
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
import { ref, computed, onMounted } from 'vue';
import { Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import { Dialog, DialogPanel, DialogTitle, TransitionChild, TransitionRoot } from '@headlessui/vue';
import axios from 'axios';

// State
const categories = ref([]);
const loading = ref(true);
const searchTerm = ref('');
const isModalOpen = ref(false);
const isEditing = ref(false);
const formErrors = ref(null);
const toast = ref({
  show: false,
  message: '',
  type: 'success',
  timeout: null
});

// Form state
const form = ref({
  code: '',
  name: '',
  description: '',
  is_active: true
});

// Computed properties
const filteredCategories = computed(() => {
  if (!searchTerm.value) return categories.value;
  
  const term = searchTerm.value.toLowerCase();
  return categories.value.filter(category => 
    category.code.toLowerCase().includes(term) || 
    category.name.toLowerCase().includes(term) || 
    (category.description && category.description.toLowerCase().includes(term))
  );
});

// Methods
const fetchCategories = async () => {
  loading.value = true;
  try {
    const response = await axios.get('/api/material-management/categories');
    categories.value = response.data;
  } catch (error) {
    showToast('Failed to load categories', 'error');
    console.error('Error fetching categories:', error);
  } finally {
    loading.value = false;
  }
};

const refreshData = () => {
  fetchCategories();
};

const openCreateModal = () => {
  isEditing.value = false;
  form.value = {
    code: '',
    name: '',
    description: '',
    is_active: true
  };
  formErrors.value = null;
  isModalOpen.value = true;
};

const editCategory = (category) => {
    isEditing.value = true;
    form.value = {
      code: category.code,
      name: category.name,
      description: category.description || '',
      is_active: category.is_active
    };
  formErrors.value = null;
  isModalOpen.value = true;
};

const closeModal = () => {
  isModalOpen.value = false;
};

const saveCategory = async () => {
  formErrors.value = null;
  
  try {
    if (isEditing.value) {
      await axios.put(`/api/material-management/categories/${form.value.code}`, form.value);
      showToast('Category updated successfully', 'success');
    } else {
      await axios.post('/api/material-management/categories', form.value);
      showToast('Category created successfully', 'success');
    }

    closeModal();
    fetchCategories();
  } catch (error) {
    if (error.response && error.response.data && error.response.data.errors) {
      formErrors.value = error.response.data.errors;
    } else {
      showToast(isEditing.value ? 'Failed to update category' : 'Failed to create category', 'error');
    console.error('Error saving category:', error);
    }
  }
};

const toggleCategoryStatus = async (category) => {
  try {
    await axios.patch(`/api/material-management/categories/${category.code}/toggle-active`);
    showToast(`Category ${category.is_active ? 'deactivated' : 'activated'} successfully`, 'success');
    fetchCategories();
  } catch (error) {
    showToast('Failed to update category status', 'error');
    console.error('Error toggling category status:', error);
  }
};

const seedSampleData = async () => {
  try {
    await axios.post('/api/material-management/categories/seed');
    showToast('Sample categories seeded successfully', 'success');
    fetchCategories();
  } catch (error) {
    showToast('Failed to seed sample data', 'error');
    console.error('Error seeding data:', error);
      }
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

// Lifecycle hooks
onMounted(() => {
  fetchCategories();
});
</script>

<style scoped>
.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

/* Add smooth transition for hover effects */
button {
  transition: all 0.2s ease;
}
</style>
