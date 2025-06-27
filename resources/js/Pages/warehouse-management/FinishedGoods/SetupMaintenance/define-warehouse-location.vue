<template>
    <AppLayout :header="'Define Warehouse Location'">
        <!-- Header Section with animated elements, adapted from Update MC -->
        <div class="bg-gradient-to-r from-purple-600 via-indigo-600 to-blue-600 p-6 rounded-t-lg shadow-lg overflow-hidden relative">
            <!-- Decorative Elements -->
            <div class="absolute top-0 right-0 w-40 h-40 bg-white opacity-5 rounded-full -translate-y-20 translate-x-20"></div>
            <div class="absolute bottom-0 left-0 w-20 h-20 bg-white opacity-5 rounded-full translate-y-10 -translate-x-10"></div>
            <div class="absolute bottom-0 right-0 w-32 h-32 bg-yellow-400 opacity-5 rounded-full translate-y-10 translate-x-10"></div>
            
            <div class="flex items-center">
                <div class="bg-gradient-to-br from-pink-500 to-purple-600 p-3 rounded-lg shadow-inner flex items-center justify-center relative overflow-hidden mr-4">
                    <div class="absolute -top-1 -right-1 w-6 h-6 bg-yellow-300 opacity-30 rounded-full animate-ping-slow"></div>
                    <div class="absolute -bottom-1 -left-1 w-4 h-4 bg-blue-300 opacity-30 rounded-full animate-ping-slow animation-delay-500"></div>
                    <i class="fas fa-warehouse text-white text-2xl z-10"></i>
                </div>
                <div>
                    <h2 class="text-2xl md:text-3xl font-bold text-white mb-1 text-shadow">Define Warehouse Location</h2>
                    <p class="text-blue-100 max-w-2xl">Manage and define warehouse locations</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-b-lg shadow-lg p-6 mb-6 bg-gradient-to-br from-white to-indigo-50">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left Column - Main Content -->
                <div class="lg:col-span-2">
                    <div class="bg-white p-6 rounded-lg shadow-md border-t-4 border-indigo-500 transition-all duration-300 hover:shadow-lg transform hover:-translate-y-1 relative overflow-hidden">
                        <div class="absolute -top-20 -right-20 w-40 h-40 bg-indigo-50 rounded-full opacity-20"></div>
                        <div class="absolute -bottom-8 -left-8 w-24 h-24 bg-purple-50 rounded-full opacity-20"></div>
                        
                        <div class="flex items-center mb-6 pb-2 border-b border-gray-200 relative z-10">
                            <div class="p-2 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-lg mr-3 shadow-md">
                                <i class="fas fa-map-marker-alt text-white"></i>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-800">Warehouse Location Management</h3>
                        </div>

                        <!-- Form content -->
                        <form @submit.prevent="saveLocation" class="space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="code" class="block text-sm font-medium text-gray-700 mb-1 flex items-center">
                                        <span class="inline-flex items-center justify-center w-5 h-5 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-full mr-2 shadow-sm">
                                            <i class="fas fa-hashtag text-white text-xs"></i>
                                        </span>
                                        Code:
                                    </label>
                                    <div class="relative flex group">
                                        <input 
                                            type="text" 
                                            id="code" 
                                            v-model="form.code"
                                            @input="debouncedCheckCode"
                                            :class="{'border-red-500': codeExists && !isEditMode}"
                                            class="flex-1 min-w-0 block w-full px-3 py-2 rounded-l-md border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 transition-all group-hover:border-indigo-300"
                                        />
                                        <button 
                                            type="button"
                                            @click="openWarehouseLocationModal"
                                            class="inline-flex items-center px-3 py-2 border border-l-0 border-gray-300 bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 text-white rounded-r-md transition-all transform active:translate-y-px relative overflow-hidden shadow-sm"
                                        >
                                            <span class="absolute inset-0 bg-white opacity-0 hover:opacity-20 transition-opacity"></span>
                                            <i class="fas fa-table relative z-10"></i>
                                        </button>
                                    </div>
                                    <p v-if="codeExists && !isEditMode" class="mt-1 text-sm text-red-600">Code already exists. Use 'Record: Select' to load it.</p>
                                </div>

                                <div class="flex items-end">
                                    <button 
                                        type="button"
                                        @click="handleRecordAction"
                                        :disabled="!form.code || (codeExists && !isEditMode) && !form.id"
                                        :class="{
                                            'bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700': !isEditMode,
                                            'bg-gradient-to-r from-blue-500 to-cyan-600 hover:from-blue-600 hover:to-cyan-700': isEditMode,
                                            'opacity-50 cursor-not-allowed': !form.code || (codeExists && !isEditMode) && !form.id
                                        }"
                                        class="text-white px-4 py-2 rounded-lg flex items-center space-x-2 transform active:translate-y-px transition-all duration-300 shadow-md relative overflow-hidden group"
                                    >
                                        <span class="absolute inset-0 bg-white opacity-0 group-hover:opacity-20 transition-opacity"></span>
                                        <div class="bg-white bg-opacity-30 rounded-full p-1.5 mr-2 flex items-center justify-center">
                                            <i :class="isEditMode ? 'fas fa-edit' : 'fas fa-plus'" class="text-white text-xs"></i>
                                        </div>
                                        <span>Record: {{ isEditMode ? 'Edit' : 'Add' }}</span>
                                    </button>
                                </div>
                            </div>
                            
                            <div v-if="isEditMode || (form.code && !codeExists)" class="space-y-6">
                                <div>
                                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1 flex items-center">
                                        <span class="inline-flex items-center justify-center w-5 h-5 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-full mr-2 shadow-sm">
                                            <i class="fas fa-align-left text-white text-xs"></i>
                                        </span>
                                        Description:
                                    </label>
                                    <input 
                                        type="text" 
                                        id="description" 
                                        v-model="form.description" 
                                        class="block w-full px-3 py-2 rounded-md border border-gray-300 focus:ring-blue-500 focus:border-blue-500"
                                    />
                                </div>

                                <div>
                                    <label for="type" class="block text-sm font-medium text-gray-700 mb-1 flex items-center">
                                        <span class="inline-flex items-center justify-center w-5 h-5 bg-gradient-to-r from-amber-500 to-orange-500 rounded-full mr-2 shadow-sm">
                                            <i class="fas fa-tags text-white text-xs"></i>
                                        </span>
                                        Type:
                                    </label>
                                    <select 
                                        id="type" 
                                        v-model="form.type" 
                                        class="block w-full px-3 py-2 rounded-md border border-gray-300 focus:ring-amber-500 focus:border-amber-500"
                                    >
                                        <option value="1-Normal Stock">1-Normal Stock</option>
                                        <option value="2-Excess Stock">2-Excess Stock</option>
                                        <option value="3-Excess Stock in Transit">3-Excess Stock in Transit</option>
                                    </select>
                                </div>

                                <!-- Lock Control -->
                                <fieldset class="border border-gray-300 p-4 rounded-md shadow-sm">
                                    <legend class="text-md font-medium text-gray-700 px-2 -ml-2">Lock Control</legend>
                                    <div class="mt-2 space-y-3">
                                        <div class="flex items-center space-x-4">
                                            <label class="text-sm font-medium text-gray-700">To Lock at Delivery Order:</label>
                                            <label class="inline-flex items-center">
                                                <input type="radio" class="form-radio" name="lock_do" :value="true" v-model="form.to_lock_delivery_order">
                                                <span class="ml-2 text-gray-700">Y-Yes</span>
                                            </label>
                                            <label class="inline-flex items-center">
                                                <input type="radio" class="form-radio" name="lock_do" :value="false" v-model="form.to_lock_delivery_order">
                                                <span class="ml-2 text-gray-700">N-No</span>
                                            </label>
                                        </div>
                                        <div class="flex items-center space-x-4">
                                            <label class="text-sm font-medium text-gray-700">To Lock at Stock-Out/Adjustment:</label>
                                            <label class="inline-flex items-center">
                                                <input type="radio" class="form-radio" name="lock_so_adj" :value="true" v-model="form.to_lock_stock_out_adjustment">
                                                <span class="ml-2 text-gray-700">Y-Yes</span>
                                            </label>
                                            <label class="inline-flex items-center">
                                                <input type="radio" class="form-radio" name="lock_so_adj" :value="false" v-model="form.to_lock_stock_out_adjustment">
                                                <span class="ml-2 text-gray-700">N-No</span>
                                            </label>
                                        </div>
                                        <div class="flex items-center space-x-4">
                                            <label class="text-sm font-medium text-gray-700">To Lock at Warehouse Transfer:</label>
                                            <label class="inline-flex items-center">
                                                <input type="radio" class="form-radio" name="lock_wt" :value="true" v-model="form.to_lock_warehouse_transfer">
                                                <span class="ml-2 text-gray-700">Y-Yes</span>
                                            </label>
                                            <label class="inline-flex items-center">
                                                <input type="radio" class="form-radio" name="lock_wt" :value="false" v-model="form.to_lock_warehouse_transfer">
                                                <span class="ml-2 text-gray-700">N-No</span>
                                            </label>
                                        </div>
                                    </div>
                                </fieldset>

                                <div class="flex justify-end pt-4 border-t border-gray-200">
                                    <button type="submit" 
                                        :class="{
                                            'bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700': isEditMode,
                                            'bg-gradient-to-r from-blue-500 to-cyan-600 hover:from-blue-600 hover:to-cyan-700': !isEditMode
                                        }"
                                        class="text-white px-6 py-2 rounded-lg shadow-md flex items-center space-x-2 transition-all duration-300 transform active:scale-95"
                                    >
                                        <i class="fas fa-save"></i>
                                        <span>{{ isEditMode ? 'Update Location' : 'Create Location' }}</span>
                                    </button>
                                    <button v-if="isEditMode" type="button" @click="deleteLocation" class="ml-4 bg-gradient-to-r from-red-500 to-rose-600 hover:from-red-600 hover:to-rose-700 text-white px-6 py-2 rounded-lg shadow-md flex items-center space-x-2 transition-all duration-300 transform active:scale-95">
                                        <i class="fas fa-trash-alt"></i>
                                        <span>Delete Location</span>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Right Column - Quick Info (adapted from Update MC) -->
                <div class="lg:col-span-1">
                    <div class="bg-white p-6 rounded-lg shadow-md border-t-4 border-teal-500 mb-6 transition-all duration-300 hover:shadow-lg transform hover:-translate-y-1 relative overflow-hidden">
                        <div class="absolute -top-16 -right-16 w-32 h-32 bg-teal-50 rounded-full opacity-20"></div>
                        <div class="absolute -bottom-6 -left-6 w-20 h-20 bg-green-50 rounded-full opacity-20"></div>
                        
                        <div class="flex items-center mb-4 pb-2 border-b border-gray-200 relative z-10">
                            <div class="p-2 bg-gradient-to-r from-teal-500 to-green-500 rounded-lg mr-3 shadow-md">
                                <i class="fas fa-lightbulb text-white"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-800">Quick Tips</h3>
                        </div>

                        <div class="space-y-4">
                            <div class="p-4 bg-teal-50 rounded-lg">
                                <h4 class="text-sm font-semibold text-teal-800 uppercase tracking-wider mb-2 flex items-center">
                                    <span class="inline-flex items-center justify-center w-5 h-5 bg-gradient-to-r from-blue-400 to-indigo-400 rounded-full mr-2 shadow-sm mt-0.5">
                                        <i class="fas fa-question text-white text-xs"></i>
                                    </span>
                                    How to Use
                                </h4>
                                <ul class="text-sm text-gray-600 space-y-2">
                                    <li class="flex items-start">
                                        <span class="inline-flex items-center justify-center w-5 h-5 bg-gradient-to-r from-blue-400 to-indigo-400 rounded-full mr-2 shadow-sm mt-0.5">
                                            <i class="fas fa-check text-white text-xs"></i>
                                        </span>
                                        <span>Enter a code and press <i class="fas fa-table"></i> to search.</span>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="inline-flex items-center justify-center w-5 h-5 bg-gradient-to-r from-purple-400 to-pink-400 rounded-full mr-2 shadow-sm mt-0.5">
                                            <i class="fas fa-check text-white text-xs"></i>
                                        </span>
                                        <span>If code exists, click 'Record: Edit' to update.</span>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="inline-flex items-center justify-center w-5 h-5 bg-gradient-to-r from-amber-400 to-orange-400 rounded-full mr-2 shadow-sm mt-0.5">
                                            <i class="fas fa-check text-white text-xs"></i>
                                        </span>
                                        <span>If code is new, fill details and click 'Record: Add'.</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>

    <WarehouseLocationModal 
        :show="isModalOpen" 
        :warehouseLocations="warehouseLocations"
        @close="closeWarehouseLocationModal"
        @select-location="handleLocationSelect"
        @sort-by-code="sortLocations('code')"
        @sort-by-name="sortLocations('description')"
    />
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref, reactive, watch } from 'vue';
import WarehouseLocationModal from '@/Components/WarehouseLocationModal.vue';
import debounce from 'lodash/debounce';
import axios from 'axios';

const isModalOpen = ref(false);
const warehouseLocations = ref([]);
const codeExists = ref(false);
const isEditMode = ref(false);

const form = reactive({
    id: null,
    code: '',
    description: '',
    type: '1-Normal Stock',
    to_lock_delivery_order: false,
    to_lock_stock_out_adjustment: false,
    to_lock_warehouse_transfer: false,
});

const resetForm = () => {
    form.id = null;
    form.code = '';
    form.description = '';
    form.type = '1-Normal Stock';
    form.to_lock_delivery_order = false;
    form.to_lock_stock_out_adjustment = false;
    form.to_lock_warehouse_transfer = false;
    isEditMode.value = false;
    codeExists.value = false;
};

const fetchWarehouseLocations = async () => {
    try {
        const response = await axios.get('/api/warehouse-locations');
        warehouseLocations.value = response.data;
    } catch (error) {
        console.error('Error fetching warehouse locations:', error);
    }
};

const openWarehouseLocationModal = async () => {
    await fetchWarehouseLocations();
    isModalOpen.value = true;
};

const closeWarehouseLocationModal = () => {
    isModalOpen.value = false;
};

const handleLocationSelect = (location) => {
    form.id = location.id;
    form.code = location.code;
    form.description = location.description;
    form.type = location.type;
    form.to_lock_delivery_order = location.to_lock_delivery_order;
    form.to_lock_stock_out_adjustment = location.to_lock_stock_out_adjustment;
    form.to_lock_warehouse_transfer = location.to_lock_warehouse_transfer;
    isEditMode.value = true;
    codeExists.value = true; // Mark as exists since it's selected
};

const checkCodeExistence = async () => {
    if (form.code) {
        try {
            const response = await axios.get(`/api/warehouse-locations/${form.code}`);
            if (response.data) {
                codeExists.value = true;
                // If in add mode and code exists, load the data
                if (!isEditMode.value) {
                    handleLocationSelect(response.data);
                }
            } else {
                codeExists.value = false;
                if (isEditMode.value) {
                    resetForm(); // Clear form if code no longer exists in edit mode
                }
            }
        } catch (error) {
            if (error.response && error.response.status === 404) {
                codeExists.value = false;
                isEditMode.value = false; // Not in edit mode if code not found
                // Only clear description and type if we are trying to add a new one
                if (!form.id) {
                    form.description = '';
                    form.type = '1-Normal Stock';
                }
            } else {
                console.error('Error checking code existence:', error);
            }
        }
    } else {
        codeExists.value = false;
        resetForm();
    }
};

const debouncedCheckCode = debounce(checkCodeExistence, 500);

const handleRecordAction = async () => {
    if (isEditMode.value) {
        // If in edit mode, it means a record is loaded, so we do nothing on this button click for now
        // The actual update happens on form submit
        console.log('Already in edit mode. Submit form to update.');
    } else {
        // If not in edit mode and code doesn't exist, it's a new record
        // The form fields (description, type, lock controls) will appear
        if (!codeExists.value && form.code) {
            isEditMode.value = false; // Stay in add new mode
        } else if (codeExists.value) {
            // If code exists, switch to edit mode and load data
            await checkCodeExistence(); // This will load the data if not already loaded
        } else {
            alert('Please enter a code to add a new record or select an existing one.');
        }
    }
};

const saveLocation = async () => {
    try {
        if (form.id) {
            // Update existing
            await axios.put(`/api/warehouse-locations/${form.code}`, form);
            alert('Warehouse location updated successfully!');
        } else {
            // Create new
            await axios.post('/api/warehouse-locations', form);
            alert('Warehouse location created successfully!');
        }
        resetForm();
        fetchWarehouseLocations(); // Refresh the list in the modal
    } catch (error) {
        console.error('Error saving warehouse location:', error);
        alert('Error saving warehouse location: ' + (error.response.data.message || error.message));
    }
};

const deleteLocation = async () => {
    if (confirm('Are you sure you want to delete this warehouse location?')) {
        try {
            await axios.delete(`/api/warehouse-locations/${form.code}`);
            alert('Warehouse location deleted successfully!');
            resetForm();
            fetchWarehouseLocations(); // Refresh the list in the modal
        } catch (error) {
            console.error('Error deleting warehouse location:', error);
            alert('Error deleting warehouse location: ' + (error.response.data.message || error.message));
        }
    }
};

const sortLocations = (key) => {
    warehouseLocations.value.sort((a, b) => {
        if (a[key] < b[key]) return -1;
        if (a[key] > b[key]) return 1;
        return 0;
    });
};

// Initial fetch when component is mounted
fetchWarehouseLocations();

// Watch for changes in form.code to update UI elements like Record: Add/Select button
watch(() => form.code, (newCode) => {
    if (!newCode) {
        resetForm();
    }
});
</script>

<style scoped>
/* Component-specific styles if any, for now using Tailwind utility classes */
.form-radio {
  -webkit-appearance: none;
  -moz-appearance: none;
  appearance: none;
  display: inline-block;
  vertical-align: middle;
  background-origin: border-box;
  user-select: none;
  flex-shrink: 0;
  border-radius: 100%;
  height: 1.25em;
  width: 1.25em;
  color: #4f46e5; /* indigo-600 */
  background-color: #ffffff;
  border-color: #d1d5db; /* gray-300 */
  border-width: 1px;
}

.form-radio:checked {
  background-image: url("data:image/svg+xml,%3csvg viewBox='0 0 16 16' fill='white' xmlns='http://www.w3.org/2000/svg'%3e%3ccircle cx='8' cy='8' r='3'/%3e%3c/svg%3e");
  background-size: 100% 100%;
  background-position: center;
  background-repeat: no-repeat;
  background-color: currentColor;
}

.form-radio:focus {
  outline: none;
  box-shadow: 0 0 0 3px rgba(100, 116, 139, 0.45); /* slate-400 with opacity */
}
</style> 