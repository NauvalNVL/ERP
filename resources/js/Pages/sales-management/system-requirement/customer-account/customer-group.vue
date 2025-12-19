<!--
  Define Customer Group Component
  New UI inspired by the View & Print Non-Active Customer page.
-->
<template>
  <AppLayout :header="'Customer Group'">
    <Head title="Customer Group Management" />

    <div class="min-h-screen bg-gray-50 py-6 px-4 sm:px-6 lg:px-8">
      <div class="max-w-7xl mx-auto">

        <!-- Header Section -->
        <div class="bg-blue-600 text-white shadow-sm rounded-xl border border-blue-700 mb-4">
          <div class="px-4 py-4 sm:px-6 flex items-center justify-between">
            <div class="flex items-center gap-3">
              <div class="h-10 w-10 rounded-full bg-blue-500 flex items-center justify-center">
                <i class="fas fa-users text-white text-lg"></i>
              </div>
              <div>
                <h2 class="text-lg sm:text-xl font-semibold leading-tight">Define Customer Group</h2>
                <p class="text-xs sm:text-sm text-blue-100">Categorize customers for better management and reporting.</p>
              </div>
            </div>
          </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 sm:p-6 mb-6">
          <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left: Main Form (col-span-2) -->
            <div class="lg:col-span-2">
              <div class="bg-white p-4 sm:p-5 rounded-lg shadow-sm border border-gray-100 mb-6">
                <div class="flex items-center mb-4 pb-3 border-b border-gray-200">
                    <div class="p-2 bg-blue-500 rounded-lg mr-4">
                        <i class="fas fa-edit text-white"></i>
                    </div>
                    <h3 class="text-base sm:text-lg font-semibold text-gray-800">Customer Group Management</h3>
                </div>
                <!-- Search and Actions -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6 relative z-10">
                    <div class="md:col-span-2">
                      <label for="searchQuery" class="flex items-center text-sm font-medium text-gray-700 mb-2">
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
                      <button type="button" @click="createNewCustomerGroup" class="primary-button group w-full whitespace-nowrap">
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
                      <p class="text-xs text-yellow-700 mt-1">Data will be automatically loaded when available.</p>
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
              <div class="bg-white rounded-xl shadow-sm border border-blue-100 p-4 sm:p-5">
                <div class="flex items-center mb-3 pb-2 border-b border-gray-100">
                  <div class="inline-flex items-center justify-center w-9 h-9 bg-blue-500 rounded-lg mr-3">
                    <i class="fas fa-info text-white text-lg"></i>
                  </div>
                  <h3 class="text-sm sm:text-base font-semibold text-gray-800">Information</h3>
                </div>
                <div class="text-gray-700 mb-4 text-sm">
                  Use this form to update customer group data. Make sure all information entered is correct and complete.
                </div>
                <div class="bg-blue-50 rounded-lg p-4">
                  <div class="font-bold text-blue-700 mb-2 text-sm">Instructions:</div>
                  <ul class="list-disc pl-5 text-blue-700 space-y-1 text-xs sm:text-sm">
                    <li>Enter the group code to search for data</li>
                    <li>Click the table button to view the list of groups</li>
                    <li>Fill in all required fields</li>
                    <li>Click Save to store your changes</li>
                  </ul>
                </div>
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
              <label for="group_code" class="flex items-center text-sm font-medium text-gray-700 mb-1">
                <i class="fas fa-barcode text-gray-400 w-5 mr-2"></i> Group Code:
              </label>
              <input id="group_code" v-model="editForm.group_code" type="text" class="modal-input" :class="{ 'bg-gray-200 cursor-not-allowed': !isCreating }" :readonly="!isCreating" required>
              <span class="text-xs text-gray-500 mt-1">Group code must be unique and cannot be changed.</span>
            </div>
            <div>
              <label for="description" class="flex items-center text-sm font-medium text-gray-700 mb-1">
                 <i class="fas fa-pen-alt text-gray-400 w-5 mr-2"></i> Description:
              </label>
              <input id="description" v-model="editForm.description" type="text" class="modal-input" required>
            </div>
          </form>
        </div>
        <div class="flex justify-between items-center p-4 bg-gray-100 border-t border-gray-200 rounded-b-lg">
          <button type="button" v-if="!isCreating" @click="obsoleteCustomerGroup(editForm.group_code)" class="secondary-button group text-red-600 border-red-200 hover:bg-red-50">
            <i class="fas fa-ban mr-2 group-hover:animate-shake"></i>Obsolete
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
import Swal from 'sweetalert2';
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

const isActiveGroup = (group) => {
  const status = (group?.status ?? group?.STATUS);
  if (status !== undefined && status !== null && String(status).trim() !== '') {
    const s = String(status).trim().toLowerCase();
    if (s === 'act' || s === 'active' || s === 'a' || s === 'y' || s === '1' || s === 'true') return true;
    if (s === 'obs' || s === 'obsolete' || s === 'inactive' || s === 'i' || s === 'n' || s === '0' || s === 'false') return false;
  }

  const active = (group?.active ?? group?.ACTIVE);
  if (active !== undefined && active !== null && String(active).trim() !== '') {
    const a = String(active).trim().toLowerCase();
    if (a === 'y' || a === 'a' || a === '1' || a === 'true') return true;
    if (a === 'n' || a === 'i' || a === '0' || a === 'false') return false;
  }

  if (group?.is_active !== undefined && group?.is_active !== null) {
    if (group.is_active === true || group.is_active === 1 || group.is_active === '1') return true;
    if (group.is_active === false || group.is_active === 0 || group.is_active === '0') return false;
  }

  const ac = (group?.AC ?? group?.ac);
  if (ac !== undefined && ac !== null && String(ac).trim() !== '') {
    const v = String(ac).trim().toUpperCase();
    if (v === 'Y' || v === 'A') return true;
    if (v === 'N' || v === 'I') return false;
  }

  return true;
};

const fetchCustomerGroups = async () => {
  loading.value = true;
  try {
    const response = await axios.get('/api/customer-groups');
    const data = response.data;
    if (data && Array.isArray(data.data)) {
      customerGroups.value = data.data.filter(isActiveGroup);
    } else if (Array.isArray(data)) { // Fallback for direct array response
      customerGroups.value = data.filter(isActiveGroup);
    } else {
      customerGroups.value = [];
      console.error('Unexpected data format:', data);
    }

    if (customerGroups.value.length === 0) {
      selectedRow.value = null;
      searchQuery.value = '';
      return;
    }

    if (selectedRow.value) {
      const stillExists = customerGroups.value.some(g => g.group_code === selectedRow.value?.group_code);
      if (!stillExists) {
        const selectedCode = selectedRow.value?.group_code;
        selectedRow.value = null;
        if (selectedCode && searchQuery.value === selectedCode) {
          searchQuery.value = '';
        }
      }
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

const obsoleteCustomerGroup = async (groupCode) => {
  const confirmRes = await Swal.fire({
    title: 'Obsolete Customer Group?',
    text: `Are you sure you want to obsolete customer group "${groupCode}"?`,
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'OK',
    cancelButtonText: 'Cancel',
    reverseButtons: true,
    allowOutsideClick: false,
  });

  if (!confirmRes.isConfirmed) {
    return;
  }

  saving.value = true;
  try {
    const response = await axios.put(`/api/customer-groups/${groupCode}/status`, {});
    const result = response.data;
    if (result.success) {
      await fetchCustomerGroups();
      closeEditModal();
      showNotification('Customer group status updated successfully.', 'success');
    } else {
      showNotification('Error updating customer group status: ' + (result.message || 'Unknown error'), 'error');
    }
  } catch (e) {
    const errorMessage = e.response?.data?.message || e.message || 'An error occurred';
    console.error('Error updating customer group status:', e);
    showNotification(`Error updating customer group status: ${errorMessage}`, 'error');
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
