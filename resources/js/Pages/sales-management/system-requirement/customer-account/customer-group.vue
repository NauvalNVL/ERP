<!--
  Define Customer Group Component
  New UI inspired by the View & Print Non-Active Customer page.
-->
<template>
  <AppLayout :header="'Customer Group'">
    <Head title="Customer Group Management" />

    <!-- Header Section -->
    <div class="bg-gradient-to-r from-blue-600 via-cyan-600 to-teal-500 p-6 rounded-t-lg shadow-lg overflow-hidden relative">
        <div class="absolute top-0 right-0 w-40 h-40 bg-white opacity-5 rounded-full -translate-y-20 translate-x-20 animate-pulse-slow"></div>
        <div class="absolute bottom-0 left-0 w-20 h-20 bg-white opacity-5 rounded-full translate-y-10 -translate-x-10 animate-pulse-slow animation-delay-500"></div>
        <div class="flex items-center">
            <div class="bg-gradient-to-br from-cyan-500 to-teal-600 p-3 rounded-lg shadow-inner mr-4">
                <i class="fas fa-users text-white text-2xl"></i>
            </div>
            <div>
                <h2 class="text-2xl md:text-3xl font-bold text-white text-shadow">Define Customer Group</h2>
                <p class="text-teal-100">Categorize customers for better management and reporting.</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-b-lg shadow-lg p-6 mb-6 bg-gradient-to-br from-white to-cyan-50">
        <div class="max-w-7xl mx-auto">
          <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left: Main Form (col-span-2) -->
            <div class="lg:col-span-2">
              <div class="relative bg-gradient-to-br from-blue-50 via-cyan-50 to-teal-100 p-8 rounded-2xl shadow-2xl border-t-4 border-cyan-500 overflow-hidden mb-8 animate-fade-in-up">
                <div class="absolute -top-16 -right-16 w-40 h-40 bg-cyan-200 rounded-full opacity-30"></div>
                <div class="absolute -bottom-12 -left-12 w-32 h-32 bg-teal-200 rounded-full opacity-30"></div>
                <div class="flex items-center mb-6 pb-3 border-b border-gray-200 relative z-10">
                    <div class="p-2 bg-gradient-to-r from-cyan-500 to-teal-600 rounded-lg mr-4 shadow-md">
                        <i class="fas fa-edit text-white"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800">Customer Group Management</h3>
                </div>
                <!-- Search and Actions -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6 relative z-10">
                    <div class="md:col-span-2">
                      <label for="searchQuery" class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                        <span class="flex items-center justify-center h-6 w-6 rounded-full bg-gradient-to-br from-cyan-500 to-teal-500 text-white mr-3 shadow-md">
                          <i class="fas fa-search text-xs"></i>
                      </span>
                        Find Customer Group
                      </label>
                      <div class="relative flex group">
                        <input id="searchQuery" type="text" v-model="searchQuery" placeholder="Search by code or description..." class="input-field">
                        <button type="button" @click="showModal = true" class="lookup-button from-blue-500 to-cyan-500 hover:from-blue-600 hover:to-cyan-600">
                        <i class="fas fa-table"></i>
                      </button>
                    </div>
                  </div>
                    <div class="md:col-span-1 flex flex-col justify-end">
                      <label class="block text-sm font-medium text-gray-700 mb-2">&nbsp;</label>
                      <button type="button" @click="createNewCustomerGroup" class="primary-button group w-full">
                          <span class="shimmer-effect"></span>
                          <i class="fas fa-plus-circle mr-2 group-hover:rotate-90 transition-transform duration-300"></i>
                          Add New Group
                    </button>
                  </div>
                </div>
                <!-- Data Status Information -->
                <div class="relative z-10 mt-4 p-4 rounded-lg shadow-inner border" :class="{
                    'bg-yellow-50 border-yellow-200 text-yellow-800': loading || customerGroups.length === 0,
                    'bg-green-50 border-green-200 text-green-800': !loading && customerGroups.length > 0
                  }">
                    <div v-if="loading" class="flex items-center">
                      <div class="animate-spin rounded-full h-5 w-5 border-b-2 border-yellow-700 mr-3"></div>
                      <p class="text-sm font-medium">Loading customer group data...</p>
                    </div>
                    <div v-else-if="customerGroups.length === 0">
                      <p class="text-sm font-medium">No customer group data available.</p>
                      <p class="text-xs text-yellow-700 mt-1">Make sure the database is configured and seeders have been run.</p>
                      <button @click="loadSeedData" class="mt-2 text-xs px-3 py-1 rounded-md transition-colors bg-yellow-400 text-yellow-900 hover:bg-yellow-500">
                          Run Customer Group Seeder
                      </button>
                  </div>
                    <div v-else>
                      <p class="text-sm font-medium">Data ready: {{ customerGroups.length }} customer groups found.</p>
                  <p v-if="selectedRow" class="text-xs text-green-700 mt-1">
                    Selected: <span class="font-semibold">{{ selectedRow.group_code }}</span> - {{ selectedRow.description }}
                  </p>
                  </div>
                </div>
              </div>
            </div>
            <!-- Right: Information & Quick Links (col-span-1) -->
            <div class="flex flex-col space-y-6">
              <!-- Information Card -->
              <div class="bg-white rounded-xl shadow-md border-t-4 border-blue-400 p-6">
                <div class="flex items-center mb-2">
                  <div class="inline-flex items-center justify-center w-10 h-10 bg-gradient-to-br from-green-400 to-teal-400 rounded-lg mr-3">
                    <i class="fas fa-info text-white text-2xl"></i>
                  </div>
                  <h3 class="text-xl font-bold text-gray-800">Information</h3>
                </div>
                <hr class="my-2 border-blue-100">
                <div class="text-gray-700 mb-4">
                  Gunakan form ini untuk memperbarui data group customer. Pastikan semua informasi yang dimasukkan sudah benar dan lengkap.
                </div>
                <div class="bg-blue-50 rounded-lg p-4">
                  <div class="font-bold text-blue-700 mb-2">Petunjuk:</div>
                  <ul class="list-disc pl-5 text-blue-700 space-y-1 text-sm">
                    <li>Masukkan kode group untuk mencari data</li>
                    <li>Klik tombol tabel untuk melihat daftar group</li>
                    <li>Isi semua field yang diperlukan</li>
                    <li>Klik Save untuk menyimpan perubahan</li>
                  </ul>
                </div>
              </div>
              <!-- Quick Links Card -->
              <div class="bg-white rounded-xl shadow-md border-t-4 border-purple-400 p-6">
                <div class="flex items-center mb-2">
                  <div class="inline-flex items-center justify-center w-10 h-10 bg-gradient-to-br from-purple-400 to-pink-400 rounded-lg mr-3">
                    <i class="fas fa-link text-white text-2xl"></i>
                  </div>
                  <h3 class="text-xl font-bold text-gray-800">Quick Links</h3>
                </div>
                <hr class="my-2 border-purple-100">
                <div class="space-y-3 mt-4">
                  <a href="#" class="flex items-center p-3 rounded-lg bg-green-50 hover:bg-green-100 transition">
                    <span class="inline-flex items-center justify-center w-9 h-9 bg-green-400 rounded-lg mr-3">
                      <i class="fas fa-print text-white text-xl"></i>
                    </span>
                    <div>
                      <div class="font-bold text-green-800">View & Print</div>
                      <div class="text-xs text-green-700">Print group list</div>
                    </div>
                  </a>
                  <a href="#" class="flex items-center p-3 rounded-lg bg-blue-50 hover:bg-blue-100 transition">
                    <span class="inline-flex items-center justify-center w-9 h-9 bg-blue-400 rounded-lg mr-3">
                      <i class="fas fa-users text-white text-xl"></i>
                    </span>
                    <div>
                      <div class="font-bold text-blue-800">Customer List</div>
                      <div class="text-xs text-blue-700">Manage customers in group</div>
                    </div>
                  </a>
                </div>
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
    <div v-if="showEditModal" class="fixed inset-0 z-50 bg-black bg-opacity-60 flex items-center justify-center transition-opacity" @click.self="closeEditModal">
      <div class="bg-white rounded-lg shadow-xl w-11/12 md:w-2/5 max-w-lg mx-auto transform transition-all scale-95" :class="{'scale-100': showEditModal}">
        <div class="flex items-center justify-between p-4 border-b border-gray-200 bg-gradient-to-r from-blue-600 to-cyan-500 text-white rounded-t-lg">
          <div class="flex items-center">
            <div class="p-2 bg-white bg-opacity-20 rounded-lg mr-3">
              <i class="fas fa-users"></i>
            </div>
            <h3 class="text-xl font-semibold">{{ isCreating ? 'Create Customer Group' : 'Edit Customer Group' }}</h3>
          </div>
          <button type="button" @click="closeEditModal" class="text-white hover:text-gray-200 p-2 rounded-full hover:bg-white/20">
            <i class="fas fa-times"></i>
          </button>
        </div>
        <div class="p-6 bg-gray-50">
          <form @submit.prevent="saveCustomerGroupChanges" class="space-y-6">
              <div>
              <label for="group_code" class="block text-sm font-medium text-gray-700 mb-1 flex items-center">
                <i class="fas fa-barcode text-gray-400 w-5 mr-2"></i> Group Code:
              </label>
              <input id="group_code" v-model="editForm.group_code" type="text" class="modal-input" :class="{ 'bg-gray-200 cursor-not-allowed': !isCreating }" :readonly="!isCreating" required>
              <span class="text-xs text-gray-500 mt-1">Group code must be unique and cannot be changed.</span>
            </div>
            <div>
              <label for="description" class="block text-sm font-medium text-gray-700 mb-1 flex items-center">
                 <i class="fas fa-pen-alt text-gray-400 w-5 mr-2"></i> Description:
              </label>
              <input id="description" v-model="editForm.description" type="text" class="modal-input" required>
            </div>
          </form>
        </div>
        <div class="flex justify-between items-center p-4 bg-gray-100 border-t border-gray-200 rounded-b-lg">
          <button type="button" v-if="!isCreating" @click="deleteCustomerGroup(editForm.group_code)" class="secondary-button group text-red-600 border-red-200 hover:bg-red-50">
            <i class="fas fa-trash-alt mr-2 group-hover:animate-shake"></i>Delete
          </button>
          <div v-else class="w-24"></div>
          <div class="flex space-x-3">
            <button type="button" @click="closeEditModal" class="secondary-button group">Cancel</button>
            <button @click="saveCustomerGroupChanges" class="primary-button group">
              <span class="shimmer-effect"></span>
              <i class="fas fa-save mr-2"></i>
              Save
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Loading Overlay -->
    <div v-if="saving" class="fixed inset-0 z-50 bg-black bg-opacity-50 flex justify-center items-center">
      <div class="w-12 h-12 border-4 border-solid border-blue-500 border-t-transparent rounded-full animate-spin"></div>
    </div>
    
    <!-- Notification Toast -->
    <div v-if="notification.show" class="fixed bottom-5 right-5 z-50 shadow-xl rounded-lg transition-all duration-300 transform"
         :class="{
             'bg-green-100 border-l-4 border-green-500': notification.type === 'success',
             'bg-red-100 border-l-4 border-red-500': notification.type === 'error',
             'bg-yellow-100 border-l-4 border-yellow-500': notification.type === 'warning',
             'translate-x-0 opacity-100': notification.show,
             'translate-x-full opacity-0': !notification.show
         }">
      <div class="p-4 flex items-center">
        <div class="mr-3">
          <i v-if="notification.type === 'success'" class="fas fa-check-circle text-green-500 text-xl"></i>
          <i v-else-if="notification.type === 'error'" class="fas fa-exclamation-circle text-red-500 text-xl"></i>
          <i v-else class="fas fa-exclamation-triangle text-yellow-500 text-xl"></i>
        </div>
        <div>
          <p class="font-medium" :class="{
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
import { Head, Link } from '@inertiajs/vue3';
import axios from 'axios';
import { route } from 'ziggy-js';
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

const fetchCustomerGroups = async () => {
  loading.value = true;
  try {
    const response = await axios.get('/api/customer-groups');
    const data = response.data;
    if (data && Array.isArray(data.data)) {
      customerGroups.value = data.data;
    } else if (Array.isArray(data)) { // Fallback for direct array response
      customerGroups.value = data;
    } else {
      customerGroups.value = [];
      console.error('Unexpected data format:', data);
    }
  } catch (e) {
    console.error('Error fetching customer groups:', e);
    customerGroups.value = [];
    showNotification('Failed to load customer groups.', 'error');
  } finally {
    loading.value = false;
  }
};

onMounted(() => {
  fetchCustomerGroups();
});

watch(searchQuery, (newQuery) => {
  if (newQuery && customerGroups.value.length > 0) {
    const foundGroup = customerGroups.value.find(group => 
      group.group_code.toLowerCase().includes(newQuery.toLowerCase()) ||
      group.description.toLowerCase().includes(newQuery.toLowerCase())
    );
    selectedRow.value = foundGroup || null;
  } else {
    selectedRow.value = null;
  }
});

const onCustomerGroupSelected = (group) => {
  selectedRow.value = group;
  searchQuery.value = group.group_code;
  showModal.value = false;
  
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
};

const saveCustomerGroupChanges = async () => {
  if (!editForm.value.group_code || !editForm.value.description) {
      showNotification('Group Code and Description are required.', 'error');
      return;
  }
  saving.value = true;
  try {
    let response;
    const payload = {
        group_code: editForm.value.group_code,
        description: editForm.value.description || ''
    };

    if (isCreating.value) {
      response = await axios.post('/api/customer-groups', payload);
    } else {
      response = await axios.put(`/api/customer-groups/${editForm.value.group_code}`, payload);
    }
    
    const result = response.data;
    if (result.success) {
      showNotification(isCreating.value ? 'Customer group created successfully!' : 'Customer group updated successfully!', 'success');
      await fetchCustomerGroups();
      closeEditModal();
    } else {
      showNotification('Error: ' + (result.message || 'Unknown error'), 'error');
    }
  } catch (e) {
      const errorMessage = e.response?.data?.message || e.message || 'An error occurred';
    console.error('Error saving customer group changes:', e);
      showNotification(`Error saving customer group: ${errorMessage}`, 'error');
  } finally {
    saving.value = false;
  }
};

const deleteCustomerGroup = async (groupCode) => {
  if (!confirm(`Are you sure you want to delete customer group "${groupCode}"? This action cannot be undone.`)) {
    return;
  }
  
  saving.value = true;
  try {
    const response = await axios.delete(`/api/customer-groups/${groupCode}`);
    const result = response.data;
    if (result.success) {
      await fetchCustomerGroups();
      if (selectedRow.value && selectedRow.value.group_code === groupCode) {
        selectedRow.value = null;
        searchQuery.value = '';
      }
      closeEditModal();
      showNotification('Customer group deleted successfully.', 'success');
    } else {
      showNotification('Error deleting customer group: ' + (result.message || 'Unknown error'), 'error');
    }
  } catch (e) {
    const errorMessage = e.response?.data?.message || e.message || 'An error occurred';
    console.error('Error deleting customer group:', e);
    showNotification(`Error deleting customer group: ${errorMessage}`, 'error');
  } finally {
    saving.value = false;
  }
};

const loadSeedData = async () => {
  saving.value = true;
  try {
    const response = await axios.post('/api/customer-groups/seed');
    const result = response.data;
    if (result.success) {
      showNotification('Customer group data seeded successfully!', 'success');
      await fetchCustomerGroups();
    } else {
      showNotification('Error seeding data: ' + (result.message || 'Unknown error'), 'error');
    }
  } catch (e) {
    const errorMessage = e.response?.data?.message || e.message || 'An error occurred';
    console.error('Error seeding data:', e);
    showNotification(`Error seeding data: ${errorMessage}`, 'error');
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
</script>

<style scoped>
.text-shadow {
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.input-field {
    @apply flex-1 min-w-0 block w-full px-3 py-2 rounded-l-md border-gray-300 focus:ring-cyan-500 focus:border-cyan-500 transition-all duration-300 group-hover:border-cyan-400 shadow-sm focus:shadow-md;
}

.lookup-button {
    @apply inline-flex items-center px-4 py-2 border border-l-0 border-gray-300 rounded-r-md transition-all duration-300 bg-gradient-to-r text-white shadow-sm hover:shadow-md transform hover:-translate-y-px;
}

.primary-button {
    @apply h-full items-center justify-center bg-gradient-to-r from-cyan-500 to-blue-500 text-white font-bold py-2 px-6 rounded-md shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300 flex relative overflow-hidden;
}

.secondary-button {
     @apply items-center justify-center bg-gradient-to-r from-gray-100 to-gray-200 text-gray-700 font-bold py-2 px-6 rounded-md shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300 flex relative overflow-hidden border border-gray-300;
}

.modal-input {
    @apply block w-full rounded-md border-gray-300 shadow-sm focus:border-cyan-500 focus:ring-cyan-200 focus:ring-opacity-50 transition-shadow;
}

@keyframes shake {
  0%, 100% { transform: translateX(0); }
  25% { transform: translateX(-3px) rotate(-2deg); }
  75% { transform: translateX(3px) rotate(2deg); }
}
.group-hover\:animate-shake:hover {
    animation: shake 0.3s ease-in-out;
}

.shimmer-effect {
    @apply absolute top-0 -left-[150%] h-full w-[50%] skew-x-[-25deg] bg-white/20;
    animation: shimmer 2.5s infinite;
}

@keyframes shimmer {
    100% {
        left: 150%;
    }
}

@keyframes pulse-slow {
    0%, 100% { transform: scale(1); opacity: 0.05; }
    50% { transform: scale(1.1); opacity: 0.08; }
}

.animate-pulse-slow {
    animation: pulse-slow 5s infinite;
}

.animation-delay-500 { 
    animation-delay: 0.5s; 
}
</style>
