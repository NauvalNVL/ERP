 <template>
  <AppLayout>
    <Head title="Define DR/CR Note" />
    <ToastContainer />

    <!-- Enhanced Header Section -->
    <div class="bg-gradient-to-r from-blue-600 via-blue-700 to-blue-800 p-8 rounded-lg shadow-lg mb-6">
      <div class="flex items-center justify-between">
        <div>
          <h1 class="text-3xl font-bold text-white mb-2 flex items-center">
            <i class="fas fa-file-invoice-dollar mr-4 text-blue-200"></i>
            Define DR/CR Note
          </h1>
          <p class="text-blue-100 text-lg">Manage Debit and Credit Notes for financial adjustments</p>
        </div>
        <div class="hidden md:block">
          <div class="bg-blue-500 bg-opacity-20 rounded-full p-4">
            <i class="fas fa-chart-line text-3xl text-blue-200"></i>
          </div>
        </div>
      </div>
    </div>

    <!-- Enhanced Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
      <div class="bg-gradient-to-br from-blue-50 to-blue-100 p-6 rounded-xl border border-blue-200 shadow-md hover:shadow-lg transition-all duration-300">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm font-semibold text-blue-700 uppercase tracking-wide">Total Notes</p>
            <p class="text-3xl font-bold text-blue-900 mt-2">{{ summary.total_notes || 0 }}</p>
          </div>
          <div class="bg-blue-500 p-3 rounded-full">
            <i class="fas fa-file-invoice text-white text-xl"></i>
          </div>
        </div>
      </div>
      
      <div class="bg-gradient-to-br from-red-50 to-red-100 p-6 rounded-xl border border-red-200 shadow-md hover:shadow-lg transition-all duration-300">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm font-semibold text-red-700 uppercase tracking-wide">DR Notes</p>
            <p class="text-3xl font-bold text-red-900 mt-2">{{ summary.dr_notes || 0 }}</p>
          </div>
          <div class="bg-red-500 p-3 rounded-full">
            <i class="fas fa-plus-circle text-white text-xl"></i>
          </div>
        </div>
      </div>
      
      <div class="bg-gradient-to-br from-green-50 to-green-100 p-6 rounded-xl border border-green-200 shadow-md hover:shadow-lg transition-all duration-300">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm font-semibold text-green-700 uppercase tracking-wide">CR Notes</p>
            <p class="text-3xl font-bold text-green-900 mt-2">{{ summary.cr_notes || 0 }}</p>
          </div>
          <div class="bg-green-500 p-3 rounded-full">
            <i class="fas fa-minus-circle text-white text-xl"></i>
          </div>
        </div>
      </div>
      
      <div class="bg-gradient-to-br from-purple-50 to-purple-100 p-6 rounded-xl border border-purple-200 shadow-md hover:shadow-lg transition-all duration-300">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm font-semibold text-purple-700 uppercase tracking-wide">Total Amount</p>
            <p class="text-2xl font-bold text-purple-900 mt-2">{{ formatCurrency(summary.total_amount || 0) }}</p>
          </div>
          <div class="bg-purple-500 p-3 rounded-full">
            <i class="fas fa-money-bill-wave text-white text-xl"></i>
          </div>
        </div>
      </div>
    </div>

    <!-- Enhanced Filters Section -->
    <div class="bg-white rounded-xl shadow-md p-6 mb-6 border border-gray-100">
      <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
        <i class="fas fa-filter mr-2 text-blue-600"></i>
        Search & Filters
      </h3>
      
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-2">Search</label>
          <div class="relative">
            <input 
              type="text" 
              v-model="filters.search" 
              placeholder="Search by note number, reference, or counterparty..."
              class="w-full px-4 py-3 pl-10 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
            >
            <i class="fas fa-search absolute left-3 top-3.5 text-gray-400"></i>
          </div>
        </div>
        
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-2">Type</label>
          <select 
            v-model="filters.type" 
            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
          >
            <option value="">All Types</option>
            <option value="DR">DR - Debit Note</option>
            <option value="CR">CR - Credit Note</option>
          </select>
        </div>
        
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-2">Status</label>
          <select 
            v-model="filters.status" 
            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
          >
            <option value="">All Status</option>
            <option value="Draft">Draft</option>
            <option value="Pending">Pending</option>
            <option value="Approved">Approved</option>
            <option value="Rejected">Rejected</option>
            <option value="Posted">Posted</option>
          </select>
        </div>
        
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-2">Date Range</label>
          <div class="flex space-x-2">
            <input 
              type="date" 
              v-model="filters.date_from" 
              class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
            >
            <input 
              type="date" 
              v-model="filters.date_to" 
              class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
            >
          </div>
        </div>
      </div>
      
      <div class="flex flex-col sm:flex-row justify-between items-center space-y-3 sm:space-y-0 sm:space-x-4">
        <div class="flex space-x-3">
          <button @click="applyFilters" class="btn-primary-enhanced">
            <i class="fas fa-filter mr-2"></i> Apply Filters
          </button>
          <button @click="clearFilters" class="btn-secondary-enhanced">
            <i class="fas fa-times mr-2"></i> Clear
          </button>
        </div>
        
        <div class="flex space-x-3">
          <button @click="showCreateModal" class="btn-success-enhanced">
            <i class="fas fa-plus-circle mr-2"></i> New Note
          </button>
          <button @click="refreshData" class="btn-info-enhanced">
            <i class="fas fa-sync-alt mr-2"></i> Refresh
          </button>
        </div>
      </div>
    </div>

    <!-- Enhanced Table Section -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-100">
      <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
        <h3 class="text-lg font-semibold text-gray-800 flex items-center">
          <i class="fas fa-table mr-2 text-blue-600"></i>
          DR/CR Notes List
        </h3>
      </div>
      
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider cursor-pointer hover:bg-gray-100 transition-colors" @click="handleSort('note_number')">
                <div class="flex items-center">
                  <span>Note Number</span>
                  <i v-if="sortBy === 'note_number'" :class="sortOrder === 'asc' ? 'fas fa-sort-up' : 'fas fa-sort-down'" class="ml-2 text-blue-600"></i>
                </div>
              </th>
              <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Type</th>
              <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider cursor-pointer hover:bg-gray-100 transition-colors" @click="handleSort('note_date')">
                <div class="flex items-center">
                  <span>Date</span>
                  <i v-if="sortBy === 'note_date'" :class="sortOrder === 'asc' ? 'fas fa-sort-up' : 'fas fa-sort-down'" class="ml-2 text-blue-600"></i>
                </div>
              </th>
              <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Counterparty</th>
              <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider cursor-pointer hover:bg-gray-100 transition-colors" @click="handleSort('amount')">
                <div class="flex items-center">
                  <span>Amount</span>
                  <i v-if="sortBy === 'amount'" :class="sortOrder === 'asc' ? 'fas fa-sort-up' : 'fas fa-sort-down'" class="ml-2 text-blue-600"></i>
                </div>
              </th>
              <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
              <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr v-if="loading" class="animate-pulse">
              <td colspan="7" class="px-6 py-8 text-center">
                <div class="flex justify-center items-center space-x-3">
                  <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                  <span class="text-gray-600 font-medium">Loading notes...</span>
                </div>
              </td>
            </tr>
            <tr v-else-if="notes.length === 0" class="hover:bg-gray-50">
              <td colspan="7" class="px-6 py-8 text-center">
                <div class="flex flex-col items-center space-y-3">
                  <i class="fas fa-inbox text-4xl text-gray-300"></i>
                  <p class="text-gray-500 font-medium">No notes found</p>
                  <p class="text-sm text-gray-400">Try adjusting your search or filters</p>
                </div>
              </td>
            </tr>
            <tr v-for="note in notes" :key="note.id" class="hover:bg-blue-50 transition-colors duration-200">
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm font-semibold text-gray-900">{{ note.note_number }}</div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span :class="note.note_type === 'DR' ? 'bg-red-100 text-red-800 border border-red-200' : 'bg-green-100 text-green-800 border border-green-200'" 
                      class="px-3 py-1 text-xs font-semibold rounded-full">
                  {{ note.note_type }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ formatDate(note.note_date) }}</td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ note.customer_name || note.vendor_name || 'N/A' }}</td>
              <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">{{ formatCurrency(note.amount) }}</td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span :class="getStatusBadgeClass(note.status)" class="px-3 py-1 text-xs font-semibold rounded-full border">
                  {{ note.status }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                <div class="flex space-x-2">
                  <button @click="viewNote(note)" class="action-btn-view" title="View">
                    <i class="fas fa-eye"></i>
                  </button>
                  <button v-if="note.can_edit" @click="editNote(note)" class="action-btn-edit" title="Edit">
                    <i class="fas fa-edit"></i>
                  </button>
                  <button v-if="note.can_delete" @click="deleteNote(note)" class="action-btn-delete" title="Delete">
                    <i class="fas fa-trash"></i>
                  </button>
                  <button v-if="note.can_approve" @click="approveNote(note)" class="action-btn-approve" title="Approve">
                    <i class="fas fa-check"></i>
                  </button>
                  <button v-if="note.can_approve" @click="rejectNote(note)" class="action-btn-reject" title="Reject">
                    <i class="fas fa-times"></i>
                  </button>
                  <button v-if="note.can_post" @click="postNote(note)" class="action-btn-post" title="Post">
                    <i class="fas fa-upload"></i>
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Enhanced Pagination -->
    <div v-if="pagination.total > 0" class="mt-6 bg-white rounded-lg shadow-md p-4 border border-gray-100">
      <div class="flex flex-col sm:flex-row justify-between items-center space-y-3 sm:space-y-0">
        <div class="text-sm text-gray-700">
          Showing <span class="font-semibold">{{ (pagination.current_page - 1) * pagination.per_page + 1 }}</span> to 
          <span class="font-semibold">{{ Math.min(pagination.current_page * pagination.per_page, pagination.total) }}</span> of 
          <span class="font-semibold">{{ pagination.total }}</span> results
        </div>
        
        <div class="flex items-center space-x-2">
          <button 
            @click="changePage(pagination.current_page - 1)" 
            :disabled="pagination.current_page === 1"
            class="pagination-btn"
            :class="{ 'pagination-btn-disabled': pagination.current_page === 1 }"
          >
            <i class="fas fa-chevron-left mr-1"></i> Previous
          </button>
          
          <span class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg">
            Page {{ pagination.current_page }} of {{ pagination.last_page }}
          </span>
          
          <button 
            @click="changePage(pagination.current_page + 1)" 
            :disabled="pagination.current_page === pagination.last_page"
            class="pagination-btn"
            :class="{ 'pagination-btn-disabled': pagination.current_page === pagination.last_page }"
          >
            Next <i class="fas fa-chevron-right ml-1"></i>
          </button>
        </div>
      </div>
    </div>

    <!-- Create/Edit Modal -->
    <DrCrNoteModal 
      :show="showModal" 
      :note="selectedNote" 
      @close="closeModal" 
      @saved="onNoteSaved"
    />

    <!-- Enhanced View Modal -->
    <div v-if="showViewModal" class="fixed inset-0 z-50 overflow-y-auto">
      <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="closeViewModal"></div>
        
        <div class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
          <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4">
            <div class="flex items-center justify-between">
              <h3 class="text-xl font-semibold text-white flex items-center">
                <i class="fas fa-file-invoice-dollar mr-3"></i>
                View DR/CR Note
              </h3>
              <button @click="closeViewModal" class="text-white hover:text-gray-200 transition-colors">
                <i class="fas fa-times text-xl"></i>
              </button>
            </div>
          </div>
          
          <div class="px-6 py-6">
            <div v-if="viewingNote" class="space-y-6">
              <div class="grid grid-cols-2 gap-6">
                <div class="bg-gray-50 p-4 rounded-lg">
                  <label class="block text-sm font-semibold text-gray-700 mb-2">Note Number</label>
                  <p class="text-lg font-bold text-gray-900">{{ viewingNote.note_number }}</p>
                </div>
                <div class="bg-gray-50 p-4 rounded-lg">
                  <label class="block text-sm font-semibold text-gray-700 mb-2">Type</label>
                  <span :class="viewingNote.note_type === 'DR' ? 'bg-red-100 text-red-800 border border-red-200' : 'bg-green-100 text-green-800 border border-green-200'" 
                        class="px-3 py-1 text-sm font-semibold rounded-full">
                    {{ viewingNote.note_type }}
                  </span>
                </div>
                <div class="bg-gray-50 p-4 rounded-lg">
                  <label class="block text-sm font-semibold text-gray-700 mb-2">Amount</label>
                  <p class="text-lg font-bold text-gray-900">{{ formatCurrency(viewingNote.amount) }}</p>
                </div>
                <div class="bg-gray-50 p-4 rounded-lg">
                  <label class="block text-sm font-semibold text-gray-700 mb-2">Status</label>
                  <span :class="getStatusBadgeClass(viewingNote.status)" class="px-3 py-1 text-sm font-semibold rounded-full border">
                    {{ viewingNote.status }}
                  </span>
                </div>
                <div class="bg-gray-50 p-4 rounded-lg">
                  <label class="block text-sm font-semibold text-gray-700 mb-2">Date</label>
                  <p class="text-lg font-bold text-gray-900">{{ formatDate(viewingNote.note_date) }}</p>
                </div>
                <div class="bg-gray-50 p-4 rounded-lg">
                  <label class="block text-sm font-semibold text-gray-700 mb-2">Due Date</label>
                  <p class="text-lg font-bold text-gray-900">{{ viewingNote.due_date ? formatDate(viewingNote.due_date) : 'N/A' }}</p>
                </div>
              </div>
              
              <div class="bg-gray-50 p-4 rounded-lg">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Description</label>
                <p class="text-gray-900">{{ viewingNote.description }}</p>
              </div>
              
              <div v-if="viewingNote.reason" class="bg-gray-50 p-4 rounded-lg">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Reason</label>
                <p class="text-gray-900">{{ viewingNote.reason }}</p>
              </div>
              
              <div class="grid grid-cols-2 gap-6">
                <div class="bg-gray-50 p-4 rounded-lg">
                  <label class="block text-sm font-semibold text-gray-700 mb-2">Reference Document</label>
                  <p class="text-gray-900">{{ viewingNote.reference_document || 'N/A' }}</p>
                </div>
                <div class="bg-gray-50 p-4 rounded-lg">
                  <label class="block text-sm font-semibold text-gray-700 mb-2">Reference Type</label>
                  <p class="text-gray-900">{{ viewingNote.reference_type || 'N/A' }}</p>
                </div>
              </div>
              
              <div class="grid grid-cols-2 gap-6">
                <div class="bg-gray-50 p-4 rounded-lg">
                  <label class="block text-sm font-semibold text-gray-700 mb-2">Counterparty</label>
                  <p class="text-gray-900">{{ viewingNote.customer_name || viewingNote.vendor_name || 'N/A' }}</p>
                </div>
                <div class="bg-gray-50 p-4 rounded-lg">
                  <label class="block text-sm font-semibold text-gray-700 mb-2">Currency</label>
                  <p class="text-gray-900">{{ viewingNote.currency }}</p>
                </div>
              </div>
            </div>
          </div>
          
          <div class="bg-gray-50 px-6 py-4 flex justify-end">
            <button 
              @click="closeViewModal"
              class="btn-primary-enhanced"
            >
              <i class="fas fa-times mr-2"></i> Close
            </button>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, reactive, onMounted, watch } from 'vue';
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import ToastContainer from '@/Components/ToastContainer.vue';
import DrCrNoteModal from '@/Components/DrCrNoteModal.vue';
import { useToast } from '@/Composables/useToast';

const { showToast } = useToast();

// Reactive data
const loading = ref(false);
const notes = ref([]);
const summary = ref({});
const pagination = ref({
  current_page: 1,
  last_page: 1,
  per_page: 15,
  total: 0
});

const filters = reactive({
  search: '',
  type: '',
  status: '',
  date_from: '',
  date_to: ''
});

const sortBy = ref('created_at');
const sortOrder = ref('desc');

// Modal states
const showModal = ref(false);
const selectedNote = ref(null);
const showViewModal = ref(false);
const viewingNote = ref(null);

// Methods
const loadNotes = async () => {
  loading.value = true;
  try {
    const params = new URLSearchParams({
      page: pagination.value.current_page,
      per_page: pagination.value.per_page,
      sort_by: sortBy.value,
      sort_order: sortOrder.value,
      ...filters
    });

    const response = await fetch(`/api/dr-cr-notes?${params}`);
    const data = await response.json();
    
    if (data.success) {
      notes.value = data.data;
      pagination.value = data.pagination;
    } else {
      showToast('Error loading notes', 'error');
    }
  } catch (error) {
    showToast('Error loading notes', 'error');
  } finally {
    loading.value = false;
  }
};

const loadSummary = async () => {
  try {
    const params = new URLSearchParams(filters);
    const response = await fetch(`/api/dr-cr-notes/summary?${params}`);
    const data = await response.json();
    
    if (data.success) {
      summary.value = data.data;
    }
  } catch (error) {
    console.error('Error loading summary:', error);
  }
};

const applyFilters = () => {
  pagination.value.current_page = 1;
  loadNotes();
  loadSummary();
};

const clearFilters = () => {
  Object.assign(filters, {
    search: '',
    type: '',
    status: '',
    date_from: '',
    date_to: ''
  });
  applyFilters();
};

const changePage = (page) => {
  pagination.value.current_page = page;
  loadNotes();
};

const handleSort = (field) => {
  if (sortBy.value === field) {
    sortOrder.value = sortOrder.value === 'asc' ? 'desc' : 'asc';
  } else {
    sortBy.value = field;
    sortOrder.value = 'asc';
  }
  loadNotes();
};

const showCreateModal = () => {
  selectedNote.value = null;
  showModal.value = true;
};

const editNote = (note) => {
  selectedNote.value = note;
  showModal.value = true;
};

const viewNote = async (note) => {
  try {
    const response = await fetch(`/api/dr-cr-notes/${note.id}`);
    const data = await response.json();
    
    if (data.success) {
      viewingNote.value = data.data;
      showViewModal.value = true;
    } else {
      showToast('Error loading note details', 'error');
    }
  } catch (error) {
    showToast('Error loading note details', 'error');
  }
};

const deleteNote = async (note) => {
  if (!confirm('Are you sure you want to delete this note?')) return;
  
  try {
    const response = await fetch(`/api/dr-cr-notes/${note.id}`, {
      method: 'DELETE',
      headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      }
    });
    
    const data = await response.json();
    
    if (data.success) {
      showToast('Note deleted successfully', 'success');
      loadNotes();
      loadSummary();
    } else {
      showToast(data.message, 'error');
    }
  } catch (error) {
    showToast('Error deleting note', 'error');
  }
};

const approveNote = async (note) => {
  if (!confirm('Are you sure you want to approve this note?')) return;
  
  try {
    const response = await fetch(`/api/dr-cr-notes/${note.id}/approve`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      },
      body: JSON.stringify({ approval_notes: 'Approved via web interface' })
    });
    
    const data = await response.json();
    
    if (data.success) {
      showToast('Note approved successfully', 'success');
      loadNotes();
      loadSummary();
    } else {
      showToast(data.message, 'error');
    }
  } catch (error) {
    showToast('Error approving note', 'error');
  }
};

const rejectNote = async (note) => {
  const reason = prompt('Please enter rejection reason:');
  if (!reason) return;
  
  try {
    const response = await fetch(`/api/dr-cr-notes/${note.id}/reject`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      },
      body: JSON.stringify({ approval_notes: reason })
    });
    
    const data = await response.json();
    
    if (data.success) {
      showToast('Note rejected successfully', 'success');
      loadNotes();
      loadSummary();
    } else {
      showToast(data.message, 'error');
    }
  } catch (error) {
    showToast('Error rejecting note', 'error');
  }
};

const postNote = async (note) => {
  if (!confirm('Are you sure you want to post this note?')) return;
  
  try {
    const response = await fetch(`/api/dr-cr-notes/${note.id}/post`, {
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      }
    });
    
    const data = await response.json();
    
    if (data.success) {
      showToast('Note posted successfully', 'success');
      loadNotes();
      loadSummary();
    } else {
      showToast(data.message, 'error');
    }
  } catch (error) {
    showToast('Error posting note', 'error');
  }
};

const closeModal = () => {
  showModal.value = false;
  selectedNote.value = null;
};

const closeViewModal = () => {
  showViewModal.value = false;
  viewingNote.value = null;
};

const onNoteSaved = (note) => {
  loadNotes();
  loadSummary();
};

const refreshData = () => {
  loadNotes();
  loadSummary();
};

const formatDate = (date) => {
  return new Date(date).toLocaleDateString('id-ID');
};

const formatCurrency = (amount) => {
  return new Intl.NumberFormat('id-ID', {
    style: 'currency',
    currency: 'IDR'
  }).format(amount);
};

const getStatusBadgeClass = (status) => {
  const classes = {
    'Draft': 'bg-gray-100 text-gray-800 border-gray-200',
    'Pending': 'bg-yellow-100 text-yellow-800 border-yellow-200',
    'Approved': 'bg-green-100 text-green-800 border-green-200',
    'Rejected': 'bg-red-100 text-red-800 border-red-200',
    'Posted': 'bg-blue-100 text-blue-800 border-blue-200'
  };
  return classes[status] || 'bg-gray-100 text-gray-800 border-gray-200';
};

// Lifecycle
onMounted(() => {
  loadNotes();
  loadSummary();
});
</script>

<style scoped>
.btn-primary-enhanced {
  @apply bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-200 font-semibold shadow-md hover:shadow-lg;
}

.btn-secondary-enhanced {
  @apply bg-gray-600 text-white px-6 py-3 rounded-lg hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-all duration-200 font-semibold shadow-md hover:shadow-lg;
}

.btn-success-enhanced {
  @apply bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-all duration-200 font-semibold shadow-md hover:shadow-lg;
}

.btn-info-enhanced {
  @apply bg-blue-500 text-white px-6 py-3 rounded-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-200 font-semibold shadow-md hover:shadow-lg;
}

.action-btn-view {
  @apply p-2 text-blue-600 hover:text-blue-900 hover:bg-blue-100 rounded-lg transition-all duration-200;
}

.action-btn-edit {
  @apply p-2 text-green-600 hover:text-green-900 hover:bg-green-100 rounded-lg transition-all duration-200;
}

.action-btn-delete {
  @apply p-2 text-red-600 hover:text-red-900 hover:bg-red-100 rounded-lg transition-all duration-200;
}

.action-btn-approve {
  @apply p-2 text-green-600 hover:text-green-900 hover:bg-green-100 rounded-lg transition-all duration-200;
}

.action-btn-reject {
  @apply p-2 text-red-600 hover:text-red-900 hover:bg-red-100 rounded-lg transition-all duration-200;
}

.action-btn-post {
  @apply p-2 text-blue-600 hover:text-blue-900 hover:bg-blue-100 rounded-lg transition-all duration-200;
}

.pagination-btn {
  @apply px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-200;
}

.pagination-btn-disabled {
  @apply opacity-50 cursor-not-allowed hover:bg-white;
}
</style>