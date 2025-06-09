<template>
  <AppLayout :header="'Customer Group'">
    <Head title="Customer Group Management" />

    <!-- Hidden form with CSRF token -->
    <form ref="csrfForm" class="hidden">
      @csrf
    </form>

    <!-- Header Section -->
    <div class="bg-gradient-to-r from-cyan-700 to-blue-600 p-6 rounded-t-lg shadow-lg">
      <h2 class="text-2xl font-bold text-white mb-2 flex items-center">
        <i class="fas fa-users mr-3"></i> Define Customer Group
      </h2>
      <p class="text-cyan-100">Define customer groups for categorizing customers in the system</p>
    </div>

    <div class="bg-white rounded-b-lg shadow-lg p-6 mb-6">
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Left Column - Main Content -->
        <div class="lg:col-span-2">
          <div class="bg-white p-6 rounded-lg shadow-md border-t-4 border-blue-500">
            <div class="flex items-center mb-6 pb-2 border-b border-gray-200">
              <div class="p-2 bg-blue-500 rounded-lg mr-3">
                <i class="fas fa-edit text-white"></i>
              </div>
              <h3 class="text-xl font-semibold text-gray-800">Customer Group Management</h3>
            </div>

            <!-- Search Section -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-6">
              <div class="col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Customer Group:</label>
                <div class="relative flex">
                  <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500">
                    <i class="fas fa-users"></i>
                  </span>
                  <input type="text" v-model="searchQuery" class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none border border-gray-300 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                  <button type="button" @click="showModal = true" class="inline-flex items-center px-3 py-2 border border-l-0 border-gray-300 bg-blue-500 hover:bg-blue-600 text-white rounded-r-md">
                    <i class="fas fa-table"></i>
                  </button>
                </div>
              </div>
              <div class="col-span-1">
                <label class="block text-sm font-medium text-gray-700 mb-1">Action:</label>
                <button type="button" @click="createNewCustomerGroup" class="w-full flex items-center justify-center px-4 py-2 bg-indigo-500 hover:bg-indigo-600 text-white rounded">
                  <i class="fas fa-plus-circle mr-2"></i> Add New
                </button>
              </div>
            </div>

            <!-- Data Status Information -->
            <div v-if="loading" class="mt-4 bg-yellow-100 p-3 rounded">
              <div class="flex items-center">
                <div class="mr-3">
                  <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-yellow-700"></div>
                </div>
                <p class="text-sm font-medium text-yellow-800">Loading customer group data...</p>
              </div>
            </div>
            <div v-else-if="customerGroups.length === 0" class="mt-4 bg-yellow-100 p-3 rounded">
              <p class="text-sm font-medium text-yellow-800">No customer group data available.</p>
              <p class="text-xs text-yellow-700 mt-1">Make sure the database is properly configured and seeders have been run.</p>
              <div class="mt-2 flex items-center space-x-3">
                <button @click="loadSeedData" class="bg-blue-500 hover:bg-blue-600 text-white text-xs px-3 py-1 rounded">Run Customer Group Seeder</button>
              </div>
            </div>
            <div v-else class="mt-4 bg-green-100 p-3 rounded">
              <p class="text-sm font-medium text-green-800">Data available: {{ customerGroups.length }} customer groups found.</p>
              <p v-if="selectedRow" class="text-xs text-green-700 mt-1">
                Selected: <span class="font-semibold">{{ selectedRow.group_code }}</span> - {{ selectedRow.description }}
              </p>
            </div>
          </div>
        </div>

        <!-- Right Column - Info/Help -->
        <div class="lg:col-span-1">
          <div class="bg-white p-6 rounded-lg shadow-md border-t-4 border-teal-500 mb-6">
            <div class="flex items-center mb-4 pb-2 border-b border-gray-200">
              <div class="p-2 bg-teal-500 rounded-lg mr-3">
                <i class="fas fa-info-circle text-white"></i>
              </div>
              <h3 class="text-lg font-semibold text-gray-800">Info Customer Group</h3>
            </div>
            <div class="space-y-4">
              <div class="p-4 bg-teal-50 rounded-lg">
                <h4 class="text-sm font-semibold text-teal-800 uppercase tracking-wider mb-2">Instructions</h4>
                <ul class="list-disc pl-5 text-sm text-gray-600 space-y-1">
                  <li>Customer group code must be unique and cannot be changed</li>
                  <li>Use the <span class="font-medium">search</span> button to select a customer group</li>
                  <li>Any changes must be saved</li>
                </ul>
              </div>

              <div class="p-4 bg-blue-50 rounded-lg">
                <h4 class="text-sm font-semibold text-blue-800 uppercase tracking-wider mb-2">Common Groups</h4>
                <div class="grid grid-cols-1 gap-2 text-sm">
                  <div class="flex items-center">
                    <span class="w-6 h-6 flex items-center justify-center bg-green-600 text-white rounded-full font-bold mr-2">01</span>
                    <span>RETAIL</span>
                  </div>
                  <div class="flex items-center">
                    <span class="w-6 h-6 flex items-center justify-center bg-blue-600 text-white rounded-full font-bold mr-2">02</span>
                    <span>WHOLESALE</span>
                  </div>
                  <div class="flex items-center">
                    <span class="w-6 h-6 flex items-center justify-center bg-purple-600 text-white rounded-full font-bold mr-2">03</span>
                    <span>CORPORATE</span>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Quick Links -->
          <div class="bg-white p-6 rounded-lg shadow-md border-t-4 border-purple-500">
            <div class="flex items-center mb-4 pb-2 border-b border-gray-200">
              <div class="p-2 bg-purple-500 rounded-lg mr-3">
                <i class="fas fa-link text-white"></i>
              </div>
              <h3 class="text-lg font-semibold text-gray-800">Quick Links</h3>
            </div>

            <div class="grid grid-cols-1 gap-3">
              <Link href="/customer-account" class="flex items-center p-3 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                <div class="p-2 bg-blue-500 rounded-full mr-3">
                  <i class="fas fa-user-tie text-white text-sm"></i>
                </div>
                <div>
                  <p class="font-medium text-blue-900">Customer Accounts</p>
                  <p class="text-xs text-blue-700">Manage customer accounts</p>
                </div>
              </Link>

              <Link href="/customer-group/view-print" class="flex items-center p-3 bg-green-50 rounded-lg hover:bg-green-100 transition-colors">
                <div class="p-2 bg-green-500 rounded-full mr-3">
                  <i class="fas fa-print text-white text-sm"></i>
                </div>
                <div>
                  <p class="font-medium text-green-900">Print List</p>
                  <p class="text-xs text-green-700">Print group list</p>
                </div>
              </Link>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Customer Group Modal Component -->
    <CustomerGroupModal
      v-if="showModal"
      :show="showModal"
      @close="showModal = false"
      @select="onCustomerGroupSelected"
    />

    <!-- Edit Modal -->
    <div v-if="showEditModal" class="fixed inset-0 z-50 bg-black bg-opacity-50 flex items-center justify-center">
      <div class="bg-white rounded-lg shadow-xl w-11/12 md:w-2/5 max-w-md mx-auto">
        <div class="flex items-center justify-between p-4 border-b border-gray-200 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-t-lg">
          <div class="flex items-center">
            <div class="p-2 bg-white bg-opacity-30 rounded-lg mr-3">
              <i class="fas fa-users"></i>
            </div>
            <h3 class="text-xl font-semibold">{{ isCreating ? 'Create Customer Group' : 'Edit Customer Group' }}</h3>
          </div>
          <button type="button" @click="closeEditModal" class="text-white hover:text-gray-200">
            <i class="fas fa-times text-xl"></i>
          </button>
        </div>
        <div class="p-6">
          <form @submit.prevent="saveCustomerGroupChanges" class="space-y-4">
            <div class="grid grid-cols-1 gap-4">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Group Code:</label>
                <input v-model="editForm.group_code" type="text" class="block w-full rounded-md border-gray-300 shadow-sm" :class="{ 'bg-gray-100': !isCreating }" :readonly="!isCreating" required>
                <span class="text-xs text-gray-500">Group code must be unique</span>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Description:</label>
                <input v-model="editForm.description" type="text" class="block w-full rounded-md border-gray-300 shadow-sm" required>
              </div>
            </div>
            <div class="flex justify-between mt-6 pt-4 border-t border-gray-200">
              <button type="button" v-if="!isCreating" @click="deleteCustomerGroup(editForm.group_code)" class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600">
                <i class="fas fa-trash-alt mr-2"></i>Delete
              </button>
              <div v-else class="w-24"></div>
              <div class="flex space-x-3">
                <button type="button" @click="closeEditModal" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">Save</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- Loading Overlay -->
    <div v-if="saving" class="fixed inset-0 z-50 bg-black bg-opacity-50 flex justify-center items-center">
      <div class="w-12 h-12 border-4 border-solid border-blue-500 border-t-transparent rounded-full animate-spin"></div>
    </div>
    
    <!-- Notification Toast -->
    <div v-if="notification.show" class="fixed bottom-4 right-4 z-50 shadow-xl rounded-lg transition-all duration-300"
         :class="{
             'bg-green-100 border-l-4 border-green-500': notification.type === 'success',
             'bg-red-100 border-l-4 border-red-500': notification.type === 'error',
             'bg-yellow-100 border-l-4 border-yellow-500': notification.type === 'warning'
         }">
      <div class="p-4 flex items-center">
        <div class="mr-3">
          <i v-if="notification.type === 'success'" class="fas fa-check-circle text-green-500 text-xl"></i>
          <i v-else-if="notification.type === 'error'" class="fas fa-exclamation-circle text-red-500 text-xl"></i>
          <i v-else class="fas fa-exclamation-triangle text-yellow-500 text-xl"></i>
        </div>
        <div>
          <p :class="{
            'text-green-800': notification.type === 'success',
            'text-red-800': notification.type === 'error',
            'text-yellow-800': notification.type === 'warning'
          }">{{ notification.message }}</p>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue';
import { Head, Link, usePage, router } from '@inertiajs/vue3';
import CustomerGroupModal from '@/Components/customer-group-modal.vue';
import AppLayout from '@/Layouts/AppLayout.vue';

// Get the header from props
const props = defineProps({
  header: {
    type: String,
    default: 'Customer Group Management'
  }
});

const customerGroups = ref([]);
const loading = ref(false);
const saving = ref(false);
const showModal = ref(false);
const showEditModal = ref(false);
const selectedRow = ref(null);
const searchQuery = ref('');
const editForm = ref({ group_code: '', description: '' });
const isCreating = ref(false);
const notification = ref({ show: false, message: '', type: 'success' });

// Reference to the CSRF form
const csrfForm = ref(null);

// Function to get fresh CSRF token from the form
const getCsrfToken = () => {
  // Try to get token from meta tag first
  let token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
  
  // If token from meta tag is not available or we want a fresh token, get from the form
  if (csrfForm.value) {
    const tokenInput = csrfForm.value.querySelector('input[name="_token"]');
    if (tokenInput) {
      token = tokenInput.value;
    }
  }
  
  return token || '';
};

const fetchCustomerGroups = async () => {
  loading.value = true;
  try {
    const res = await fetch('/api/customer-groups', { 
      headers: { 
        'Accept': 'application/json',
        'X-Requested-With': 'XMLHttpRequest'
      },
      credentials: 'same-origin'
    });
    
    if (!res.ok) {
      throw new Error('Network response was not ok');
    }
    
    const data = await res.json();
    console.log('Fetched customer groups:', data);
    
    if (Array.isArray(data)) {
      customerGroups.value = data;
    } else if (data.data && Array.isArray(data.data)) {
      customerGroups.value = data.data;
    } else {
      customerGroups.value = [];
      console.error('Unexpected data format:', data);
    }
  } catch (e) {
    console.error('Error fetching customer groups:', e);
    customerGroups.value = [];
  } finally {
    loading.value = false;
  }
};

onMounted(() => {
  fetchCustomerGroups();
});

// Watch for changes in search query to filter the data
watch(searchQuery, (newQuery) => {
  if (newQuery && customerGroups.value.length > 0) {
    const foundGroup = customerGroups.value.find(group => 
      group.group_code.toLowerCase().includes(newQuery.toLowerCase()) ||
      group.description.toLowerCase().includes(newQuery.toLowerCase())
    );
    
    if (foundGroup) {
      selectedRow.value = foundGroup;
    }
  }
});

const onCustomerGroupSelected = (group) => {
  selectedRow.value = group;
  searchQuery.value = group.group_code;
  showModal.value = false;
  
  // Automatically open the edit modal for the selected row
  isCreating.value = false;
  editForm.value = { 
    group_code: group.group_code, 
    description: group.description || ''
  };
  showEditModal.value = true;
};

const createNewCustomerGroup = () => {
  isCreating.value = true;
  editForm.value = { group_code: '', description: '' };
  showEditModal.value = true;
};

const closeEditModal = () => {
  showEditModal.value = false;
  editForm.value = { group_code: '', description: '' };
  isCreating.value = false;
};

const saveCustomerGroupChanges = async () => {
  saving.value = true;
  try {
    // Get fresh CSRF token
    const csrfToken = getCsrfToken();
    
    // Different API call for create vs update
    let url = isCreating.value ? '/api/customer-groups' : `/api/customer-groups/${editForm.value.group_code}`;
    let method = isCreating.value ? 'POST' : 'PUT';
    
    console.log(`Sending ${method} request to ${url} with data:`, editForm.value);
    
    const response = await fetch(url, {
      method: method,
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': csrfToken,
        'Accept': 'application/json',
        'X-Requested-With': 'XMLHttpRequest'
      },
      body: JSON.stringify({
        group_code: editForm.value.group_code,
        description: editForm.value.description || ''
      }),
      credentials: 'same-origin' // Include cookies in the request
    });
    
    const result = await response.json();
    console.log('API response:', result);
    
    if (!response.ok) {
      throw new Error(result.message || `Error ${method === 'POST' ? 'creating' : 'updating'} customer group`);
    }
    
    if (result.success) {
      // Update the local data with the changes or add new item
      if (isCreating.value) {
        showNotification('Customer group created successfully', 'success');
      } else {
        if (selectedRow.value) {
          selectedRow.value.description = editForm.value.description;
        }
        showNotification('Customer group updated successfully', 'success');
      }
      
      // Refresh the full data list to ensure we're in sync with the database
      await fetchCustomerGroups();
      closeEditModal();
    } else {
      showNotification('Error: ' + (result.message || 'Unknown error'), 'error');
    }
  } catch (e) {
    console.error('Error saving customer group changes:', e);
    showNotification('Error saving customer group: ' + e.message, 'error');
  } finally {
    saving.value = false;
  }
};

const deleteCustomerGroup = async (groupCode) => {
  if (!confirm(`Are you sure you want to delete customer group "${groupCode}"?`)) {
    return;
  }
  
  saving.value = true;
  try {
    // Get fresh CSRF token
    const csrfToken = getCsrfToken();
    
    console.log('Deleting customer group with code:', groupCode);
    
    const response = await fetch(`/api/customer-groups/${groupCode}`, {
      method: 'DELETE',
      headers: {
        'X-CSRF-TOKEN': csrfToken,
        'Accept': 'application/json',
        'X-Requested-With': 'XMLHttpRequest'
      },
      credentials: 'same-origin' // Include cookies in the request
    });
    
    const result = await response.json();
    
    if (!response.ok) {
      throw new Error(result.message || 'Error deleting customer group');
    }
    
    if (result.success) {
      // Remove the item from the local array
      customerGroups.value = customerGroups.value.filter(group => group.group_code !== groupCode);
      
      if (selectedRow.value && selectedRow.value.group_code === groupCode) {
        selectedRow.value = null;
        searchQuery.value = '';
      }
      
      closeEditModal();
      showNotification('Customer group deleted successfully', 'success');
    } else {
      showNotification('Error deleting customer group: ' + (result.message || 'Unknown error'), 'error');
    }
  } catch (e) {
    console.error('Error deleting customer group:', e);
    showNotification('Error deleting customer group: ' + e.message, 'error');
  } finally {
    saving.value = false;
  }
};

const loadSeedData = async () => {
  saving.value = true;
  try {
    // Get fresh CSRF token
    const csrfToken = getCsrfToken();
    
    console.log('Running customer group seeder...');
    
    const response = await fetch('/api/customer-groups/seed', {
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': csrfToken,
        'Accept': 'application/json',
        'X-Requested-With': 'XMLHttpRequest'
      },
      credentials: 'same-origin' // Include cookies in the request
    });
    
    const result = await response.json();
    
    if (!response.ok) {
      throw new Error(result.message || 'Error seeding data');
    }
    
    if (result.success) {
      showNotification('Customer group data seeded successfully', 'success');
      await fetchCustomerGroups();
    } else {
      showNotification('Error seeding data: ' + (result.message || 'Unknown error'), 'error');
    }
  } catch (e) {
    console.error('Error seeding data:', e);
    showNotification('Error seeding data: ' + e.message, 'error');
  } finally {
    saving.value = false;
  }
};

const showNotification = (message, type = 'success') => {
  notification.value = {
    show: true,
    message,
    type
  };
  
  setTimeout(() => {
    notification.value.show = false;
  }, 3000);
};

const goToDashboard = () => {
  router.visit(route('dashboard'));
};

const deleteSelected = () => {
  if (selectedRow.value) {
    deleteCustomerGroup(selectedRow.value.group_code);
  } else {
    showNotification('Please select a customer group first', 'warning');
  }
};
</script>