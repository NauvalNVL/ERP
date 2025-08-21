<template>
  <div v-if="show" class="fixed inset-0 z-50 overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
      <!-- Background overlay -->
      <div class="fixed inset-0 bg-gray-600 bg-opacity-75 transition-opacity" @click="closeModal"></div>

      <!-- Modal panel -->
      <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
        <!-- Header -->
        <div class="bg-gray-50 px-4 py-3 sm:px-6 flex justify-between items-center">
          <h3 class="text-lg leading-6 font-medium text-gray-900">
            User Table
          </h3>
          <button
            @click="closeModal"
            class="text-gray-400 hover:text-gray-600 transition-colors"
          >
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
          </button>
        </div>

        <!-- Search -->
        <div class="px-4 py-3 border-b border-gray-200">
          <div class="flex gap-2">
            <input
              v-model="searchQuery"
              @input="searchUsers"
              type="text"
              placeholder="Search by User ID or Name..."
              class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            />
            <button
              @click="searchUsers"
              class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
            >
              Search
            </button>
          </div>
        </div>

        <!-- Table -->
        <div class="max-h-96 overflow-y-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50 sticky top-0">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Code
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Name
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Email
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Signon
                </th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr
                v-for="user in filteredUsers"
                :key="user.user_id"
                @click="selectUser(user)"
                :class="[
                  'cursor-pointer hover:bg-blue-50 transition-colors',
                  selectedUserId === user.user_id ? 'bg-blue-100' : ''
                ]"
              >
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                  {{ user.user_id }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                  {{ user.name }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                  {{ user.email }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                  <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full"
                        :class="user.is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'">
                    {{ user.is_active ? 'Yes' : 'No' }}
                  </span>
                </td>
              </tr>
              <tr v-if="filteredUsers.length === 0">
                <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">
                  {{ loading ? 'Loading users...' : 'No users found' }}
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Footer -->
        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
          <button
            @click="confirmSelection"
            :disabled="!selectedUserId"
            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm disabled:opacity-50 disabled:cursor-not-allowed"
          >
            Select
          </button>
          <button
            @click="closeModal"
            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
          >
            Exit
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, watch, onMounted } from 'vue'
import axios from 'axios'

const props = defineProps({
  show: {
    type: Boolean,
    default: false
  },
  currentUserId: {
    type: String,
    default: ''
  }
})

const emit = defineEmits(['close', 'select'])

const users = ref([])
const filteredUsers = ref([])
const searchQuery = ref('')
const selectedUserId = ref('')
const loading = ref(false)

// Sample users data (in real app, this would come from API)
const sampleUsers = [
  { user_id: 'MKT', name: 'MARKETING', email: 'marketing@ptmbi.com', is_active: true },
  { user_id: 'SLS', name: 'SALES', email: 'sales@ptmbi.com', is_active: true },
  { user_id: 'acc01', name: 'Jenita', email: 'jenita@ptmbi.com', is_active: true },
  { user_id: 'acc02', name: 'Ricky', email: 'ricky@ptmbi.com', is_active: true },
  { user_id: 'acc03', name: 'Sari', email: 'sari@ptmbi.com', is_active: true },
  { user_id: 'acc04', name: 'Budi', email: 'budi@ptmbi.com', is_active: true },
  { user_id: 'acc05', name: 'Dewi', email: 'dewi@ptmbi.com', is_active: true },
  { user_id: 'acc06', name: 'Eko', email: 'eko@ptmbi.com', is_active: true },
  { user_id: 'acc07', name: 'Fani', email: 'fani@ptmbi.com', is_active: true },
  { user_id: 'acc08', name: 'Gita', email: 'gita@ptmbi.com', is_active: true },
  { user_id: 'acc09', name: 'Nuri', email: 'nuriati@ptmbil.com', is_active: true },
  { user_id: 'acc10', name: 'Indra', email: 'indra@ptmbi.com', is_active: true },
  { user_id: 'mat01', name: 'Ahmad', email: 'ahmad@ptmbi.com', is_active: true },
  { user_id: 'mat02', name: 'Bella', email: 'bella@ptmbi.com', is_active: true },
  { user_id: 'mat03', name: 'Marsih', email: 'marsih@ptmbil.com', is_active: true },
  { user_id: 'mat04', name: 'Christine', email: 'christine@ptmbil.com', is_active: true },
  { user_id: 'mat05', name: 'Diana', email: 'diana@ptmbi.com', is_active: true },
  { user_id: 'mat06', name: 'Erik', email: 'erik@ptmbi.com', is_active: true },
  { user_id: 'mat07', name: 'Nikita', email: 'admin@ptmbil.com', is_active: true },
  { user_id: 'mat08', name: 'Fiona', email: 'fiona@ptmbi.com', is_active: true },
  { user_id: 'mat09', name: 'Garry', email: 'garry@ptmbi.com', is_active: true },
  { user_id: 'mat10', name: 'Helen', email: 'helen@ptmbi.com', is_active: true },
  { user_id: 'mat11', name: 'Ivan', email: 'ivan@ptmbi.com', is_active: true },
  { user_id: 'mat12', name: 'Yufa', email: 'purchasing@ptmbi1.com', is_active: true },
  { user_id: 'mat13', name: 'Julia', email: 'julia@ptmbi.com', is_active: true },
  { user_id: 'mat14', name: 'Kevin', email: 'kevin@ptmbi.com', is_active: true },
  { user_id: 'mat15', name: 'Linda', email: 'linda@ptmbi.com', is_active: true },
  { user_id: 'mat16', name: 'Yuda', email: 'mbi.purchasing@ptmbi.com', is_active: true },
  { user_id: 'mis01', name: 'Maya', email: 'maya@ptmbi.com', is_active: true },
  { user_id: 'mis02', name: 'Nina', email: 'nina@ptmbi.com', is_active: true },
  { user_id: 'mis03', name: 'Oscar', email: 'oscar@ptmbi.com', is_active: true },
  { user_id: 'mis04', name: 'Nyoman', email: 'nwedasuharta@ptmbi1.com', is_active: true },
  { user_id: 'mis05', name: 'Pamela', email: 'pamela@ptmbi.com', is_active: true },
  { user_id: 'mis06', name: 'Quinn', email: 'quinn@ptmbi.com', is_active: true },
  { user_id: 'mis07', name: 'Sakka', email: 'sagung@multiindustry1.com', is_active: true },
  { user_id: 'mkt01', name: 'Rita', email: 'rita@ptmbi.com', is_active: true },
  { user_id: 'mkt02', name: 'Sam', email: 'sam@ptmbi.com', is_active: true },
  { user_id: 'mkt03', name: 'Tina', email: 'tina@ptmbi.com', is_active: true },
  { user_id: 'mkt04', name: 'Umar', email: 'umar@ptmbi.com', is_active: true },
  { user_id: 'mkt05', name: 'Endang', email: 'endang@ptmbil.com', is_active: true },
  { user_id: 'corpm1', name: 'Dudi', email: 'dudi@ptmbi.com', is_active: true },
]

const searchUsers = async () => {
  loading.value = true
  try {
    // In a real application, you would call the API
    // const response = await axios.get('/api/users', {
    //   params: { search: searchQuery.value }
    // })
    // users.value = response.data

    // For now, use sample data with filtering
    const filtered = sampleUsers.filter(user => {
      const query = searchQuery.value.toLowerCase()
      return user.user_id.toLowerCase().includes(query) ||
             user.name.toLowerCase().includes(query) ||
             user.email.toLowerCase().includes(query)
    })
    
    filteredUsers.value = filtered
  } catch (error) {
    console.error('Error fetching users:', error)
  } finally {
    loading.value = false
  }
}

const selectUser = (user) => {
  selectedUserId.value = user.user_id
}

const confirmSelection = () => {
  if (selectedUserId.value) {
    const selectedUser = filteredUsers.value.find(user => user.user_id === selectedUserId.value)
    emit('select', selectedUser)
    closeModal()
  }
}

const closeModal = () => {
  emit('close')
  selectedUserId.value = ''
  searchQuery.value = ''
}

// Watch for show prop changes
watch(() => props.show, (newVal) => {
  if (newVal) {
    searchUsers()
    selectedUserId.value = props.currentUserId || ''
  }
})

// onMounted is not needed since watch handles the show prop
</script>

<style scoped>
/* Add any specific styles for this modal here */
</style> 