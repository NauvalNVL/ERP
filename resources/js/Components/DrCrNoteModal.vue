<template>
  <div v-if="show" class="fixed inset-0 z-50 overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
      <!-- Background overlay -->
      <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="closeModal"></div>

      <!-- Modal panel -->
      <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
        <!-- Header -->
        <div class="bg-gradient-to-r from-blue-600 to-blue-800 px-6 py-4">
          <div class="flex items-center justify-between">
            <h3 class="text-lg font-semibold text-white">
              {{ isEditing ? 'Edit DR/CR Note' : 'Create New DR/CR Note' }}
            </h3>
            <button @click="closeModal" class="text-white hover:text-gray-200">
              <i class="fas fa-times"></i>
            </button>
          </div>
        </div>

        <!-- Body -->
        <div class="px-6 py-4">
          <form @submit.prevent="saveNote" class="space-y-6">
            <!-- Note Type Selection -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Note Type *</label>
                <div class="flex space-x-4">
                  <label class="flex items-center">
                    <input 
                      type="radio" 
                      v-model="form.note_type" 
                      value="DR" 
                      class="mr-2 text-blue-600 focus:ring-blue-500"
                      :disabled="isEditing"
                    >
                    <span class="text-sm font-medium text-red-600">DR - Debit Note</span>
                  </label>
                  <label class="flex items-center">
                    <input 
                      type="radio" 
                      v-model="form.note_type" 
                      value="CR" 
                      class="mr-2 text-blue-600 focus:ring-blue-500"
                      :disabled="isEditing"
                    >
                    <span class="text-sm font-medium text-green-600">CR - Credit Note</span>
                  </label>
                </div>
                <p class="text-xs text-gray-500 mt-1">
                  {{ form.note_type === 'DR' ? 'Untuk menambah tagihan' : 'Untuk mengurangi tagihan' }}
                </p>
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Note Number</label>
                <input 
                  type="text" 
                  v-model="form.note_number" 
                  class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-50"
                  readonly
                >
                <p class="text-xs text-gray-500 mt-1">Auto-generated</p>
              </div>
            </div>

            <!-- Reference Information -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Reference Document</label>
                <input 
                  type="text" 
                  v-model="form.reference_document" 
                  placeholder="e.g., INV-2025-001"
                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                >
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Reference Type</label>
                <select 
                  v-model="form.reference_type" 
                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                >
                  <option value="">Select type...</option>
                  <option value="Invoice">Invoice</option>
                  <option value="Purchase Order">Purchase Order</option>
                  <option value="Sales Order">Sales Order</option>
                  <option value="Delivery Order">Delivery Order</option>
                  <option value="Other">Other</option>
                </select>
              </div>
            </div>

            <!-- Counterparty Information -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Customer/Vendor Code</label>
                <div class="relative">
                  <input 
                    type="text" 
                    v-model="form.customer_code" 
                    placeholder="Search customer/vendor..."
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                    @input="searchCounterparty"
                  >
                  <button 
                    type="button"
                    @click="showCounterpartyModal = true"
                    class="absolute right-2 top-2 text-gray-400 hover:text-gray-600"
                  >
                    <i class="fas fa-search"></i>
                  </button>
                </div>
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Customer/Vendor Name</label>
                <input 
                  type="text" 
                  v-model="form.customer_name" 
                  placeholder="Customer or vendor name"
                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                >
              </div>
            </div>

            <!-- Amount and Currency -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Amount *</label>
                <input 
                  type="number" 
                  v-model="form.amount" 
                  step="0.01"
                  min="0"
                  placeholder="0.00"
                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                  :class="{ 'border-red-500': errors.amount }"
                >
                <p v-if="errors.amount" class="text-xs text-red-500 mt-1">{{ errors.amount[0] }}</p>
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Currency</label>
                <select 
                  v-model="form.currency" 
                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                >
                  <option value="IDR">IDR - Indonesian Rupiah</option>
                  <option value="USD">USD - US Dollar</option>
                  <option value="EUR">EUR - Euro</option>
                  <option value="SGD">SGD - Singapore Dollar</option>
                </select>
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Exchange Rate</label>
                <input 
                  type="number" 
                  v-model="form.exchange_rate" 
                  step="0.0001"
                  min="0"
                  placeholder="1.0000"
                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                >
              </div>
            </div>

            <!-- Dates -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Note Date *</label>
                <input 
                  type="date" 
                  v-model="form.note_date" 
                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                  :class="{ 'border-red-500': errors.note_date }"
                >
                <p v-if="errors.note_date" class="text-xs text-red-500 mt-1">{{ errors.note_date[0] }}</p>
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Due Date</label>
                <input 
                  type="date" 
                  v-model="form.due_date" 
                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                >
              </div>
            </div>

            <!-- Description and Reason -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Description *</label>
                <textarea 
                  v-model="form.description" 
                  rows="3"
                  placeholder="Enter description of the note..."
                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                  :class="{ 'border-red-500': errors.description }"
                ></textarea>
                <p v-if="errors.description" class="text-xs text-red-500 mt-1">{{ errors.description[0] }}</p>
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Reason</label>
                <textarea 
                  v-model="form.reason" 
                  rows="3"
                  placeholder="Enter reason for the adjustment..."
                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                ></textarea>
              </div>
            </div>

            <!-- Status Information (for editing) -->
            <div v-if="isEditing" class="bg-gray-50 p-4 rounded-md">
              <h4 class="text-sm font-medium text-gray-700 mb-3">Status Information</h4>
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                  <label class="block text-xs text-gray-500">Current Status</label>
                  <span :class="getStatusBadgeClass(note.status)" class="px-2 py-1 text-xs font-medium rounded-full">
                    {{ note.status }}
                  </span>
                </div>
                <div v-if="note.approved_by">
                  <label class="block text-xs text-gray-500">Approved By</label>
                  <span class="text-sm text-gray-700">{{ note.approved_by }}</span>
                </div>
              </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-end space-x-3 pt-4 border-t">
              <button 
                type="button" 
                @click="closeModal"
                class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
              >
                Cancel
              </button>
              <button 
                type="submit" 
                :disabled="loading"
                class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50"
              >
                <i v-if="loading" class="fas fa-spinner fa-spin mr-2"></i>
                {{ isEditing ? 'Update Note' : 'Create Note' }}
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, computed, watch, onMounted } from 'vue';
import { useToast } from '@/Composables/useToast';

const { showToast } = useToast();

const props = defineProps({
  show: {
    type: Boolean,
    default: false
  },
  note: {
    type: Object,
    default: null
  }
});

const emit = defineEmits(['close', 'saved']);

// Reactive data
const loading = ref(false);
const errors = ref({});
const showCounterpartyModal = ref(false);

const form = reactive({
  note_type: 'DR',
  note_number: '',
  reference_document: '',
  reference_type: '',
  customer_code: '',
  vendor_code: '',
  customer_name: '',
  vendor_name: '',
  amount: '',
  description: '',
  reason: '',
  note_date: new Date().toISOString().split('T')[0],
  due_date: '',
  currency: 'IDR',
  exchange_rate: '1.0000'
});

// Computed
const isEditing = computed(() => props.note !== null);

// Methods
const closeModal = () => {
  emit('close');
  resetForm();
};

const resetForm = () => {
  Object.assign(form, {
    note_type: 'DR',
    note_number: '',
    reference_document: '',
    reference_type: '',
    customer_code: '',
    vendor_code: '',
    customer_name: '',
    vendor_name: '',
    amount: '',
    description: '',
    reason: '',
    note_date: new Date().toISOString().split('T')[0],
    due_date: '',
    currency: 'IDR',
    exchange_rate: '1.0000'
  });
  errors.value = {};
};

const loadNoteData = () => {
  if (props.note) {
    Object.assign(form, {
      note_type: props.note.note_type,
      note_number: props.note.note_number,
      reference_document: props.note.reference_document || '',
      reference_type: props.note.reference_type || '',
      customer_code: props.note.customer_code || '',
      vendor_code: props.note.vendor_code || '',
      customer_name: props.note.customer_name || '',
      vendor_name: props.note.vendor_name || '',
      amount: props.note.amount,
      description: props.note.description,
      reason: props.note.reason || '',
      note_date: props.note.note_date,
      due_date: props.note.due_date || '',
      currency: props.note.currency,
      exchange_rate: props.note.exchange_rate
    });
  }
};

const saveNote = async () => {
  loading.value = true;
  errors.value = {};

  try {
    const url = isEditing.value 
      ? `/api/dr-cr-notes/${props.note.id}`
      : '/api/dr-cr-notes';
    
    const method = isEditing.value ? 'PUT' : 'POST';
    
    const response = await fetch(url, {
      method,
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      },
      body: JSON.stringify(form)
    });

    const data = await response.json();

    if (data.success) {
      showToast(data.message, 'success');
      emit('saved', data.data);
      closeModal();
    } else {
      if (data.errors) {
        errors.value = data.errors;
      } else {
        showToast(data.message, 'error');
      }
    }
  } catch (error) {
    showToast('Error saving note', 'error');
  } finally {
    loading.value = false;
  }
};

const searchCounterparty = () => {
  // Implement counterparty search functionality
};

const getStatusBadgeClass = (status) => {
  const classes = {
    'Draft': 'bg-gray-100 text-gray-800',
    'Pending': 'bg-yellow-100 text-yellow-800',
    'Approved': 'bg-green-100 text-green-800',
    'Rejected': 'bg-red-100 text-red-800',
    'Posted': 'bg-blue-100 text-blue-800'
  };
  return classes[status] || 'bg-gray-100 text-gray-800';
};

// Watchers
watch(() => props.show, (newVal) => {
  if (newVal && props.note) {
    loadNoteData();
  }
});

watch(() => props.note, (newVal) => {
  if (newVal) {
    loadNoteData();
  }
});

// Lifecycle
onMounted(() => {
  if (props.note) {
    loadNoteData();
  }
});
</script> 