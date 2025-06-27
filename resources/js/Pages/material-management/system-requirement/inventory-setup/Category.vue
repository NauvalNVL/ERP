<template>
  <AppLayout title="Define Category">
    <div class="bg-white shadow rounded-lg p-6">
      <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold text-gray-800">Define Category</h1>
        <div class="flex space-x-3">
          <button
            @click="seedData"
            class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition duration-200"
          >
            Seed Sample Data
          </button>
          <button
            @click="openModal()"
            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition duration-200"
          >
            <i class="fas fa-plus mr-2"></i>Add New
          </button>
        </div>
      </div>
      
      <!-- Search & Filter -->
      <div class="mb-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Search</label>
          <input
            v-model="search"
            type="text"
            placeholder="Search by code, name or description"
            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
          />
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
          <select
            v-model="statusFilter"
            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
          >
            <option value="all">All</option>
            <option value="active">Active</option>
            <option value="inactive">Inactive</option>
          </select>
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Sort By</label>
          <select
            v-model="sortBy"
            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
          >
            <option value="code">Code</option>
            <option value="name">Name</option>
            <option value="updated_at">Last Updated</option>
          </select>
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Sort Order</label>
          <select
            v-model="sortOrder"
            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
          >
            <option value="asc">Ascending</option>
            <option value="desc">Descending</option>
          </select>
        </div>
      </div>
      
      <!-- Categories Table -->
      <div class="shadow overflow-x-auto border-b border-gray-200 rounded-lg">
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
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Last Updated
              </th>
              <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                Actions
              </th>
            </tr>
          </thead>
          <tbody v-if="filteredCategories.length > 0" class="bg-white divide-y divide-gray-200">
            <tr v-for="category in paginatedCategories" :key="category.code">
              <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-800">
                {{ category.code }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-gray-700">
                {{ category.name }}
              </td>
              <td class="px-6 py-4 text-gray-700 line-clamp-2">
                {{ category.description || '-' }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span :class="[
                  'px-2 py-1 text-xs font-semibold rounded-full', 
                  category.is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'
                ]">
                  {{ category.is_active ? 'Active' : 'Inactive' }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-gray-700">
                {{ formatDate(category.updated_at) }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-right text-sm space-x-2">
                <button 
                  @click="toggleStatus(category)" 
                  :class="[
                    'px-2 py-1 rounded',
                    category.is_active ? 'bg-amber-100 text-amber-700 hover:bg-amber-200' : 'bg-green-100 text-green-700 hover:bg-green-200'
                  ]"
                >
                  <span v-if="category.is_active">Deactivate</span>
                  <span v-else>Activate</span>
                </button>
                <button 
                  @click="openModal(category)" 
                  class="px-2 py-1 bg-blue-100 text-blue-700 rounded hover:bg-blue-200"
                >
                  Edit
                </button>
                <button 
                  @click="confirmDelete(category)" 
                  class="px-2 py-1 bg-red-100 text-red-700 rounded hover:bg-red-200"
                >
                  Delete
                </button>
              </td>
            </tr>
          </tbody>
          <tbody v-else class="bg-white">
            <tr>
              <td colspan="6" class="px-6 py-4 text-center text-gray-500 italic">
                No categories found
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
      <div class="mt-6 flex justify-between items-center">
        <p class="text-sm text-gray-700">
          Showing {{ startIndex + 1 }} to {{ endIndex }} of {{ filteredCategories.length }} categories
        </p>
        <div class="flex space-x-1">
          <button 
            @click="currentPage = 1" 
            :disabled="currentPage === 1"
            :class="[
              'px-3 py-1 rounded',
              currentPage === 1 ? 'bg-gray-200 text-gray-500 cursor-not-allowed' : 'bg-gray-200 text-gray-700 hover:bg-gray-300'
            ]"
          >
            First
          </button>
          <button 
            @click="currentPage--" 
            :disabled="currentPage === 1"
            :class="[
              'px-3 py-1 rounded',
              currentPage === 1 ? 'bg-gray-200 text-gray-500 cursor-not-allowed' : 'bg-gray-200 text-gray-700 hover:bg-gray-300'
            ]"
          >
            Prev
          </button>
          <button 
            v-for="page in displayedPages" 
            :key="page"
            @click="currentPage = page"
            :class="[
              'px-3 py-1 rounded',
              currentPage === page ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300'
            ]"
          >
            {{ page }}
          </button>
          <button 
            @click="currentPage++" 
            :disabled="currentPage === totalPages"
            :class="[
              'px-3 py-1 rounded',
              currentPage === totalPages ? 'bg-gray-200 text-gray-500 cursor-not-allowed' : 'bg-gray-200 text-gray-700 hover:bg-gray-300'
            ]"
          >
            Next
          </button>
          <button 
            @click="currentPage = totalPages" 
            :disabled="currentPage === totalPages"
            :class="[
              'px-3 py-1 rounded',
              currentPage === totalPages ? 'bg-gray-200 text-gray-500 cursor-not-allowed' : 'bg-gray-200 text-gray-700 hover:bg-gray-300'
            ]"
          >
            Last
          </button>
        </div>
      </div>
    </div>

    <!-- Category Modal -->
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
                <DialogTitle as="h3" class="text-lg font-semibold leading-6 text-gray-900 pb-2 border-b">
                  {{ isEditing ? 'Edit Category' : 'Add New Category' }}
                </DialogTitle>

                <form @submit.prevent="saveCategory" class="mt-4 space-y-4">
                  <div>
                    <label for="code" class="block text-sm font-medium text-gray-700">Code <span class="text-red-600">*</span></label>
                    <input 
                      id="code" 
                      v-model="form.code" 
                      type="text" 
                      :readOnly="isEditing"
                      :class="[
                        'mt-1 block w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500',
                        isEditing ? 'bg-gray-100 cursor-not-allowed' : '',
                        errors.code ? 'border-red-300' : 'border-gray-300'
                      ]"
                      placeholder="Enter code"
                      maxlength="20"
                      required
                    />
                    <p v-if="errors.code" class="mt-1 text-sm text-red-600">{{ errors.code[0] }}</p>
                  </div>

                  <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Name <span class="text-red-600">*</span></label>
                    <input 
                      id="name" 
                      v-model="form.name" 
                      type="text" 
                      class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                      :class="errors.name ? 'border-red-300' : 'border-gray-300'"
                      placeholder="Enter name"
                      maxlength="100"
                      required
                    />
                    <p v-if="errors.name" class="mt-1 text-sm text-red-600">{{ errors.name[0] }}</p>
                  </div>

                  <div>
                    <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea 
                      id="description" 
                      v-model="form.description" 
                      rows="3" 
                      class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                      :class="errors.description ? 'border-red-300' : 'border-gray-300'"
                      placeholder="Enter description"
                    ></textarea>
                    <p v-if="errors.description" class="mt-1 text-sm text-red-600">{{ errors.description[0] }}</p>
                  </div>

                  <div class="flex">
                    <label class="inline-flex items-center">
                      <input type="checkbox" v-model="form.is_active" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500 h-5 w-5" />
                      <span class="ml-2 text-gray-700">Active</span>
                    </label>
                  </div>

                  <div class="mt-6 flex justify-end space-x-3">
                    <button 
                      type="button" 
                      @click="closeModal" 
                      class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200"
                    >
                      Cancel
                    </button>
                    <button 
                      type="submit" 
                      class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700"
                    >
                      {{ isEditing ? 'Save Changes' : 'Add Category' }}
                    </button>
                  </div>
                </form>
              </DialogPanel>
            </TransitionChild>
          </div>
        </div>
      </Dialog>
    </TransitionRoot>

    <!-- Delete Confirmation Modal -->
    <TransitionRoot appear :show="isDeleteModalOpen" as="template">
      <Dialog as="div" @close="isDeleteModalOpen = false" class="relative z-10">
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
                <DialogTitle as="h3" class="text-lg font-semibold leading-6 text-gray-900">
                  Confirm Delete
                </DialogTitle>
                <div class="mt-3">
                  <p class="text-gray-700">
                    Are you sure you want to delete the category <span class="font-semibold">{{ categoryToDelete?.code }} - {{ categoryToDelete?.name }}</span>? This action cannot be undone.
                  </p>
                </div>
                <div class="mt-6 flex justify-end space-x-3">
                  <button 
                    type="button" 
                    @click="isDeleteModalOpen = false" 
                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200"
                  >
                    Cancel
                  </button>
                  <button 
                    @click="deleteCategory" 
                    class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-md hover:bg-red-700"
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

    <ToastContainer />
  </AppLayout>
</template>

<script setup>
import { ref, computed, onMounted, nextTick, watch } from 'vue';
import { Dialog, DialogPanel, DialogTitle, TransitionChild, TransitionRoot } from '@headlessui/vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import ToastContainer from '@/Components/ToastContainer.vue';
import { useToast } from '@/Composables/useToast.js';

// Toast notifications
const { showToast } = useToast();

// Data
const categories = ref([]);
const search = ref('');
const statusFilter = ref('all');
const sortBy = ref('code');
const sortOrder = ref('asc');
const currentPage = ref(1);
const perPage = ref(10);
const isLoading = ref(true);
const isModalOpen = ref(false);
const isEditing = ref(false);
const isDeleteModalOpen = ref(false);
const categoryToDelete = ref(null);
const errors = ref({});

// Form
const form = ref({
  code: '',
  name: '',
  description: '',
  is_active: true
});

// Fetch categories
const fetchCategories = async () => {
  isLoading.value = true;
  try {
    const response = await fetch('/api/material-management/categories');
    const data = await response.json();
    categories.value = data;
  } catch (error) {
    console.error('Error fetching categories:', error);
    showToast('Failed to load categories', 'error');
  } finally {
    isLoading.value = false;
  }
};

// Filtered categories based on search and status
const filteredCategories = computed(() => {
  let filtered = [...categories.value];

  // Apply search filter
  if (search.value) {
    const searchLower = search.value.toLowerCase();
    filtered = filtered.filter(category => 
      (category.code && category.code.toLowerCase().includes(searchLower)) ||
      (category.name && category.name.toLowerCase().includes(searchLower)) ||
      (category.description && category.description.toLowerCase().includes(searchLower))
    );
  }

  // Apply status filter
  if (statusFilter.value !== 'all') {
    filtered = filtered.filter(category => 
      statusFilter.value === 'active' ? category.is_active : !category.is_active
    );
  }

  // Apply sorting
  filtered.sort((a, b) => {
    let comparison = 0;
    const fieldA = a[sortBy.value];
    const fieldB = b[sortBy.value];

    if (fieldA < fieldB) {
      comparison = -1;
    } else if (fieldA > fieldB) {
      comparison = 1;
    }

    return sortOrder.value === 'desc' ? comparison * -1 : comparison;
  });

  return filtered;
});

// Pagination
const totalPages = computed(() => 
  Math.max(1, Math.ceil(filteredCategories.value.length / perPage.value))
);

const startIndex = computed(() => (currentPage.value - 1) * perPage.value);

const endIndex = computed(() => {
  const end = startIndex.value + perPage.value;
  return end > filteredCategories.value.length ? filteredCategories.value.length : end;
});

const paginatedCategories = computed(() => 
  filteredCategories.value.slice(startIndex.value, endIndex.value)
);

const displayedPages = computed(() => {
  const total = totalPages.value;
  const current = currentPage.value;
  const pages = [];

  if (total <= 7) {
    // Less than 7 pages, show all
    for (let i = 1; i <= total; i++) {
      pages.push(i);
    }
  } else {
    // More than 7 pages, show current page, 2 before and 2 after if possible
    pages.push(1); // Always show first page
    
    if (current <= 3) {
      // Near start
      pages.push(2, 3, 4, '...', total);
    } else if (current >= total - 2) {
      // Near end
      pages.push('...', total - 3, total - 2, total - 1, total);
    } else {
      // Middle
      pages.push('...', current - 1, current, current + 1, '...', total);
    }
  }
  
  return pages;
});

// Format date
const formatDate = (dateString) => {
  if (!dateString) return '-';
  const date = new Date(dateString);
  return new Intl.DateTimeFormat('en-US', {
    year: 'numeric',
    month: 'short',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  }).format(date);
};

// Modal functions
const openModal = (category = null) => {
  resetForm();
  errors.value = {};
  
  if (category) {
    isEditing.value = true;
    form.value = {
      code: category.code,
      name: category.name,
      description: category.description || '',
      is_active: category.is_active
    };
  } else {
    isEditing.value = false;
  }
  
  isModalOpen.value = true;
};

const closeModal = () => {
  isModalOpen.value = false;
  nextTick(() => {
    resetForm();
    errors.value = {};
  });
};

const resetForm = () => {
  form.value = {
    code: '',
    name: '',
    description: '',
    is_active: true
  };
};

// Save category (create or update)
const saveCategory = async () => {
  const url = `/api/material-management/categories${isEditing.value ? `/${form.value.code}` : ''}`;
  const method = isEditing.value ? 'PUT' : 'POST';

  try {
    const response = await fetch(url, {
      method: method,
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      },
      body: JSON.stringify(form.value)
    });

    if (response.status === 422) {
      const data = await response.json();
      errors.value = data.errors;
      return;
    }

    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`);
    }

    await fetchCategories();
    closeModal();
    showToast(isEditing.value ? 'Category updated successfully' : 'Category created successfully', 'success');
  } catch (error) {
    console.error('Error saving category:', error);
    showToast(isEditing.value ? 'Failed to update category' : 'Failed to create category', 'error');
  }
};

// Toggle status
const toggleStatus = async (category) => {
  try {
    const response = await fetch(`/api/material-management/categories/${category.code}/toggle-active`, {
      method: 'PATCH',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      }
    });

    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`);
    }

    await fetchCategories();
    showToast(`Category ${category.is_active ? 'deactivated' : 'activated'} successfully`, 'success');
  } catch (error) {
    console.error('Error toggling status:', error);
    showToast('Failed to update category status', 'error');
  }
};

// Delete category
const confirmDelete = (category) => {
  categoryToDelete.value = category;
  isDeleteModalOpen.value = true;
};

const deleteCategory = async () => {
  if (!categoryToDelete.value) return;
  
  try {
    const response = await fetch(`/api/material-management/categories/${categoryToDelete.value.code}`, {
      method: 'DELETE',
      headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      }
    });

    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`);
    }

    await fetchCategories();
    isDeleteModalOpen.value = false;
    showToast('Category deleted successfully', 'success');
  } catch (error) {
    console.error('Error deleting category:', error);
    showToast('Failed to delete category', 'error');
  }
};

// Seed sample data
const seedData = async () => {
  try {
    const response = await fetch('/api/material-management/categories/seed', {
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      }
    });

    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`);
    }

    await fetchCategories();
    showToast('Sample categories loaded successfully', 'success');
  } catch (error) {
    console.error('Error seeding data:', error);
    showToast('Failed to load sample data', 'error');
  }
};

// Watch for changes that should reset pagination
watch([search, statusFilter, sortBy, sortOrder], () => {
  currentPage.value = 1;
});

// Initial fetch
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
