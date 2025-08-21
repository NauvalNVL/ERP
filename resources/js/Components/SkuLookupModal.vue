<template>
  <Transition name="modal">
    <div v-if="show" class="modal-mask">
      <div class="modal-wrapper">
        <div class="modal-container max-w-4xl">
          <div class="modal-header">
            <slot name="header">
              <h3 class="text-lg font-bold text-gray-800">SKU Lookup</h3>
              <button @click="$emit('close')" class="modal-default-button text-gray-500 hover:text-gray-700">
                <i class="fas fa-times"></i>
              </button>
            </slot>
          </div>

          <div class="modal-body">
            <slot name="body">
              <div class="flex gap-2 mb-4">
                <input
                  type="text"
                  v-model="searchQuery"
                  @input="filterSkus"
                  placeholder="Search by SKU Code or Name..."
                  class="w-full p-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                />
                <select
                  v-model="categoryFilter"
                  @change="filterSkus"
                  class="p-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                >
                  <option value="">All Categories</option>
                  <option v-for="category in categories" :key="category.code" :value="category.code">
                    {{ category.name }}
                  </option>
                </select>
              </div>

              <div class="overflow-x-auto max-h-96">
                <table class="min-w-full divide-y divide-gray-200">
                  <thead class="bg-gray-50 sticky top-0">
                    <tr>
                      <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">SKU</th>
                      <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                      <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                      <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                      <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">UOM</th>
                      <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                    </tr>
                  </thead>
                  <tbody class="bg-white divide-y divide-gray-200">
                    <tr
                      v-for="sku in filteredSkus"
                      :key="sku.sku"
                      @click="selectSku(sku)"
                      class="cursor-pointer hover:bg-blue-50 transition-colors"
                    >
                      <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ sku.sku }}</td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ sku.sku_name }}</td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ sku.category_code }}</td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ sku.type }}</td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ sku.uom }}</td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm">
                        <button @click.stop="selectSku(sku)" class="text-blue-600 hover:text-blue-900">
                          Select
                        </button>
                      </td>
                    </tr>
                    <tr v-if="filteredSkus.length === 0">
                      <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">
                        <div v-if="loading" class="flex justify-center items-center">
                          <i class="fas fa-spinner fa-spin mr-2"></i> Loading...
                        </div>
                        <div v-else>
                          No SKUs found.
                        </div>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>

              <div class="mt-3 flex justify-between text-sm text-gray-500">
                <div>Total: {{ filteredSkus.length }} SKUs</div>
                <div v-if="loading" class="text-blue-600">
                  <i class="fas fa-spinner fa-spin mr-1"></i> Loading...
                </div>
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
  emits: ['close', 'select'],
  data() {
    return {
      skus: [],
      categories: [],
      searchQuery: '',
      categoryFilter: '',
      loading: false,
    };
  },
  computed: {
    filteredSkus() {
      // Ensure skus is an array before filtering
      let filtered = Array.isArray(this.skus) ? this.skus : [];
      
      // Apply search query filter
      if (this.searchQuery && this.searchQuery.trim()) {
        const query = this.searchQuery.toLowerCase().trim();
        filtered = filtered.filter(
          (sku) =>
            (sku.sku && sku.sku.toLowerCase().includes(query)) ||
            (sku.sku_name && sku.sku_name.toLowerCase().includes(query))
        );
      }
      
      // Apply category filter
      if (this.categoryFilter) {
        filtered = filtered.filter(sku => sku.category_code === this.categoryFilter);
      }
      
      return filtered;
    },
  },
  watch: {
    show(newVal) {
      if (newVal) {
        this.fetchData();
      }
    },
  },
  methods: {
    async fetchData() {
      this.loading = true;
      try {
        // Load categories first - fixed API endpoint with material-management prefix
        const categoryResponse = await axios.get('/api/material-management/skus/categories', {
          headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
          }
        });
        this.categories = categoryResponse.data;
        
        // Then load SKUs - fixed API endpoint with material-management prefix
        const skuResponse = await axios.get('/api/material-management/skus', {
          headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
          }
        });
        this.skus = skuResponse.data;
      } catch (error) {
        console.error('Error fetching data for SKU lookup:', error);
        console.error('Error details:', {
          message: error.message,
          status: error.response?.status,
          data: error.response?.data
        });
      } finally {
        this.loading = false;
      }
    },
    filterSkus() {
      // Debounce could be added here if needed
    },
    selectSku(sku) {
      this.$emit('select', sku);
      this.$emit('close');
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
  align-items: center;
  justify-content: center;
  transition: opacity 0.3s ease;
}

.modal-wrapper {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 100%;
  height: 100%;
}

.modal-container {
  background-color: white;
  border-radius: 8px;
  box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
  max-height: 90vh;
  overflow-y: auto;
  margin: 2rem;
  width: 100%;
  max-width: 768px;
}

.modal-header {
  padding: 1.5rem;
  border-bottom: 1px solid #e5e7eb;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.modal-body {
  padding: 1.5rem;
}

.modal-footer {
  padding: 1.5rem;
  border-top: 1px solid #e5e7eb;
  display: flex;
  justify-content: flex-end;
}

.modal-default-button {
  padding: 0.5rem 1rem;
  border-radius: 0.375rem;
  border: none;
  cursor: pointer;
  font-weight: 500;
  transition: all 0.2s ease-in-out;
}

.modal-enter-active, .modal-leave-active {
  transition: opacity 0.3s ease;
}

.modal-enter-from, .modal-leave-to {
  opacity: 0;
}

.modal-container {
  width: 100%;
  background-color: #fff;
  border-radius: 0.5rem;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1), 0 1px 3px rgba(0, 0, 0, 0.08);
  transition: all 0.3s ease;
  overflow: hidden;
}

.modal-header {
  background-color: #f9fafb;
  padding: 1rem 1.5rem;
  display: flex;
  align-items: center;
  justify-content: space-between;
  border-bottom: 1px solid #e5e7eb;
}

.modal-body {
  padding: 1.5rem;
  max-height: 70vh;
  overflow-y: auto;
}

.modal-footer {
  background-color: #f9fafb;
  padding: 1rem 1.5rem;
  display: flex;
  align-items: center;
  justify-content: flex-end;
  border-top: 1px solid #e5e7eb;
}

.modal-default-button {
  padding: 0.5rem 1rem;
  border-radius: 0.375rem;
  font-size: 0.875rem;
  line-height: 1.25rem;
  transition: all 0.3s ease;
}

/* Animation */
.modal-enter-active,
.modal-leave-active {
  transition: all 0.3s ease;
}

.modal-enter-from,
.modal-leave-to {
  opacity: 0;
}

.modal-enter-from .modal-container,
.modal-leave-to .modal-container {
  transform: scale(0.9);
}
</style> 