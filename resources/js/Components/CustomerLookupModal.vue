<template>
  <Transition name="modal">
    <div v-if="show" class="modal-mask">
      <div class="modal-wrapper">
        <div class="modal-container max-w-2xl">
          <div class="modal-header">
            <slot name="header">
              <h3 class="text-lg font-bold text-gray-800">Customer Lookup</h3>
              <button @click="$emit('close')" class="modal-default-button text-gray-500 hover:text-gray-700">
                <i class="fas fa-times"></i>
              </button>
            </slot>
          </div>

          <div class="modal-body">
            <slot name="body">
              <input
                type="text"
                v-model="searchQuery"
                @input="filterCustomers"
                placeholder="Search by Customer Code or Name..."
                class="w-full p-2 border border-gray-300 rounded-md mb-4 focus:ring-blue-500 focus:border-blue-500"
              />

              <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                  <thead class="bg-gray-50">
                    <tr>
                      <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Code</th>
                      <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                      <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                      <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                    </tr>
                  </thead>
                  <tbody class="bg-white divide-y divide-gray-200">
                    <tr
                      v-for="customer in filteredCustomers"
                      :key="customer.customer_code"
                      @click="selectCustomer(customer)"
                      class="cursor-pointer hover:bg-blue-50 transition-colors"
                    >
                      <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ customer.customer_code }}</td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ customer.customer_name }}</td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ customer.status }}</td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm">
                        <button @click.stop="selectCustomer(customer)" class="text-blue-600 hover:text-blue-900">
                          Select
                        </button>
                      </td>
                    </tr>
                    <tr v-if="filteredCustomers.length === 0">
                        <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">No customers found.</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </slot>
          </div>

          <div class="modal-footer">
            <slot name="footer">
              <button
                class="modal-default-button bg-gray-300 hover:bg-gray-400 text-gray-800"
                @click="$emit('close')"
              >
                Close
              </button>
            </slot>
          </div>
        </div>
      </div>
    </div>
  </Transition>
</template>

<script>
import axios from 'axios';

export default {
  props: {
    show: Boolean,
  },
  emits: ['close', 'customerSelected'],
  data() {
    return {
      customers: [],
      searchQuery: '',
    };
  },
  computed: {
    filteredCustomers() {
      if (!this.searchQuery) {
        return this.customers;
      }
      const query = this.searchQuery.toLowerCase();
      return this.customers.filter(
        (customer) =>
          customer.customer_code.toLowerCase().includes(query) ||
          customer.customer_name.toLowerCase().includes(query)
      );
    },
  },
  watch: {
    show(newVal) {
      if (newVal) {
        this.fetchCustomers();
      }
    },
  },
  methods: {
    async fetchCustomers() {
      try {
        const response = await axios.get('/api/customers-with-status');
        this.customers = response.data;
      } catch (error) {
        console.error('Error fetching customers in modal:', error);
        // Optionally, emit an error or show a message to the user
      }
    },
    selectCustomer(customer) {
      this.$emit('customerSelected', customer);
    },
  },
};
</script>

<style scoped>
.modal-mask {
  position: fixed;
  z-index: 9998;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.5);
  display: flex;
  transition: opacity 0.3s ease;
}

.modal-wrapper {
  margin: auto;
}

.modal-container {
  background-color: #fff;
  border-radius: 8px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.33);
  transition: all 0.3s ease;
  display: flex;
  flex-direction: column;
}

.modal-header h3 {
  margin-top: 0;
  color: #333;
}

.modal-body {
  margin: 20px 0;
  flex-grow: 1;
  overflow-y: auto;
}

.modal-default-button {
  padding: 8px 16px;
  border-radius: 4px;
  cursor: pointer;
  transition: background-color 0.2s;
}

.modal-enter-from,
.modal-leave-to {
  opacity: 0;
}

.modal-enter-active .modal-container,
.modal-leave-active .modal-container {
  -webkit-transform: scale(1.1);
  transform: scale(1.1);
}

.modal-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 16px 24px;
  border-bottom: 1px solid #eee;
}

.modal-footer {
  padding: 16px 24px;
  border-top: 1px solid #eee;
  display: flex;
  justify-content: flex-end;
}
</style> 