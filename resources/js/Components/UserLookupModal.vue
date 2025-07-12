<template>
  <div v-if="show" class="fixed inset-0 z-50 bg-black bg-opacity-50 flex items-center justify-center">
    <div class="bg-white rounded-lg shadow-xl w-11/12 md:w-2/3 lg:w-3/4 max-w-3xl mx-auto">
      <!-- Modal Header -->
      <div class="flex items-center justify-between p-4 border-b border-gray-200 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-t-lg">
        <h3 class="text-xl font-semibold flex items-center">
          <i class="fas fa-user-circle mr-3"></i>Select User ID
        </h3>
        <button type="button" @click="$emit('close')" class="text-white hover:text-gray-200 focus:outline-none">
          <i class="fas fa-times text-xl"></i>
        </button>
      </div>

      <!-- Modal Body -->
      <div class="p-4 overflow-y-auto" style="max-height: 60vh;">
        <div class="mb-4">
          <input
            type="text"
            v-model="searchQuery"
            placeholder="Search by User ID or Name..."
            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
          />
        </div>

        <div v-if="loading" class="flex justify-center items-center p-4">
          <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-500"></div>
          <span class="ml-2 text-gray-600">Loading users...</span>
        </div>
        <div v-else-if="error" class="p-4 text-red-500 bg-red-50 rounded border border-red-200">
          <div class="font-bold mb-1">Error:</div>
          <div>{{ error }}</div>
          <button @click="fetchUsers" class="mt-2 px-3 py-1 bg-blue-500 text-white text-xs rounded hover:bg-blue-600">
            Try Again
          </button>
        </div>
        <div v-else-if="filteredUsers.length === 0" class="p-4 text-amber-700 bg-amber-50 rounded border border-amber-200">
          No users found. Adjust your search or try again.
        </div>
        <table v-else class="min-w-full text-xs border border-gray-300">
          <thead class="bg-gray-200 sticky top-0">
            <tr>
              <th class="px-2 py-1 border border-gray-300 text-left">User ID</th>
              <th class="px-2 py-1 border border-gray-300 text-left">User Name</th>
              <th class="px-2 py-1 border border-gray-300 text-left">Email</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr
              v-for="user in filteredUsers"
              :key="user.id"
              class="hover:bg-blue-100 cursor-pointer"
              :class="{ 'bg-blue-200': selectedUser?.id === user.id }"
              @click="selectUser(user)"
            >
              <td class="px-2 py-1 border border-gray-300">{{ user.user_id }}</td>
              <td class="px-2 py-1 border border-gray-300">{{ user.name }}</td>
              <td class="px-2 py-1 border border-gray-300">{{ user.email }}</td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Modal Footer -->
      <div class="flex items-center justify-end gap-2 p-2 border-t border-gray-200 bg-gray-100 rounded-b-lg">
        <div class="text-xs text-gray-500 mr-auto" v-if="filteredUsers.length > 0">
          {{ filteredUsers.length }} users found
        </div>
        <button
          @click="handleSelect"
          :disabled="!selectedUser"
          class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-3 rounded text-xs disabled:opacity-50 disabled:cursor-not-allowed"
        >
          Select
        </button>
        <button type="button" @click="$emit('close')" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-1 px-3 rounded text-xs">Exit</button>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, onMounted, computed, watch } from 'vue';

export default {
  props: {
    show: {
      type: Boolean,
      required: true,
    },
  },
  emits: ['close', 'select'],
  setup(props, { emit }) {
    const allUsers = ref([]);
    const selectedUser = ref(null);
    const loading = ref(false);
    const error = ref(null);
    const searchQuery = ref('');

    const fetchUsers = async () => {
      loading.value = true;
      error.value = null;
      try {
        const response = await fetch('/api/users', {
          headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.head.querySelector('meta[name="csrf-token"]')?.getAttribute('content'),
          },
          credentials: 'same-origin',
        });

        if (!response.ok) {
          throw new Error(`Server returned ${response.status}: ${response.statusText}`);
        }

        const rawData = await response.json();
        if (rawData.error) {
          throw new Error(rawData.error);
        }

        if (Array.isArray(rawData)) {
          allUsers.value = rawData;
        } else if (rawData.data && Array.isArray(rawData.data)) {
          allUsers.value = rawData.data;
        } else {
          throw new Error('Invalid data format returned from server');
        }
        
        if (allUsers.value.length > 0 && !selectedUser.value) {
          selectedUser.value = allUsers.value[0];
        }
      } catch (err) {
        console.error('Error fetching users:', err);
        error.value = `${err.message || 'Unknown error'}`;
      } finally {
        loading.value = false;
      }
    };

    const filteredUsers = computed(() => {
      const query = searchQuery.value.toLowerCase();
      return allUsers.value.filter(user =>
        (user.user_id && user.user_id.toLowerCase().includes(query)) ||
        (user.name && user.name.toLowerCase().includes(query))
      );
    });

    const selectUser = (user) => {
      selectedUser.value = user;
    };

    const handleSelect = () => {
      if (selectedUser.value) {
        emit('select', selectedUser.value);
        emit('close');
      } else {
        console.warn('No user selected');
      }
    };

    watch(() => props.show, (newValue) => {
      if (newValue === true) {
        fetchUsers();
      }
    }, { immediate: true });

    return {
      allUsers,
      filteredUsers,
      selectedUser,
      loading,
      error,
      searchQuery,
      fetchUsers,
      selectUser,
      handleSelect,
    };
  },
};
</script>

<style scoped>
/* Add any specific styles for this modal here */
</style> 