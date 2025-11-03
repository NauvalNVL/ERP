<template>
    <div v-if="show" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-2xl w-4/5 max-w-3xl max-h-[85vh] flex flex-col">
            <!-- Modal Header -->
            <div class="bg-gradient-to-r from-purple-600 to-pink-600 text-white px-4 py-3 rounded-t-lg flex justify-between items-center">
                <div class="flex items-center gap-2">
                    <i class="fas fa-link"></i>
                    <h3 class="text-sm font-semibold">Define Product Group Tie-Up</h3>
                </div>
                <button @click="closeModal" class="text-white/90 hover:text-white">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <!-- Info Bar -->
            <div class="px-4 py-2 bg-blue-50 border-b">
                <div class="flex items-center text-sm text-blue-800">
                    <i class="fas fa-info-circle mr-2"></i>
                    <span>Customer: <strong>{{ customerCode }}</strong> | Index#: <strong>{{ indexNumber }}</strong></span>
                </div>
            </div>

            <!-- Toolbar -->
            <div class="px-4 py-2 bg-gray-100 border-b">
                <div class="flex gap-2">
                    <button
                        @click="checkAll"
                        class="p-1.5 hover:bg-gray-200 rounded"
                        title="Check All"
                    >
                        <i class="fas fa-check-double text-green-600"></i>
                    </button>
                    <button
                        @click="uncheckAll"
                        class="p-1.5 hover:bg-gray-200 rounded"
                        title="Uncheck All"
                    >
                        <i class="fas fa-times text-red-600"></i>
                    </button>
                    <button
                        @click="saveTieups"
                        class="p-1.5 hover:bg-gray-200 rounded"
                        title="Save"
                    >
                        <i class="fas fa-save text-blue-600"></i>
                    </button>
                </div>
            </div>

            <!-- Loading State -->
            <div v-if="loading" class="flex-1 flex items-center justify-center p-8">
                <div class="text-center">
                    <i class="fas fa-spinner fa-spin text-4xl text-purple-500 mb-3"></i>
                    <p class="text-gray-600">Loading product groups...</p>
                </div>
            </div>

            <!-- Table Content -->
            <div v-else class="flex-1 overflow-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-100 border-b-2 border-gray-300 sticky top-0">
                        <tr>
                            <th class="px-3 py-2 text-left font-semibold border-r w-32">P/group Code</th>
                            <th class="px-3 py-2 text-left font-semibold border-r">Product Group Name</th>
                            <th class="px-3 py-2 text-center font-semibold w-24">Tie-Up Yes</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="item in productGroups"
                            :key="item.code"
                            @click="toggleTieup(item.code)"
                            :class="item.tie_up_enabled ? 'bg-blue-50' : 'hover:bg-gray-50'"
                            class="cursor-pointer border-b"
                        >
                            <td class="px-3 py-2 border-r font-mono">{{ item.code }}</td>
                            <td class="px-3 py-2 border-r">{{ item.name }}</td>
                            <td class="px-3 py-2 text-center">
                                <input
                                    type="checkbox"
                                    v-model="item.tie_up_enabled"
                                    @click.stop
                                    class="w-4 h-4 text-blue-600 focus:ring-blue-500"
                                />
                            </td>
                        </tr>
                        <!-- Empty state -->
                        <tr v-if="productGroups.length === 0">
                            <td colspan="3" class="px-3 py-8 text-center text-gray-500">
                                <i class="fas fa-inbox text-3xl mb-2"></i>
                                <p>No product groups available.</p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Footer with Summary and Action Buttons -->
            <div class="px-4 py-3 bg-gray-50 border-t">
                <div class="flex justify-between items-center mb-3">
                    <div class="text-sm text-gray-600">
                        <i class="fas fa-check-circle text-green-500 mr-1"></i>
                        <strong>{{ tieupCount }}</strong> product group(s) selected
                    </div>
                </div>
                <div class="flex justify-end gap-2">
                    <button
                        @click="saveTieups"
                        class="px-4 py-2 bg-gradient-to-r from-green-500 to-teal-500 text-white rounded hover:from-green-600 hover:to-teal-600 transition-all shadow-md"
                    >
                        <i class="fas fa-save mr-2"></i>
                        Save
                    </button>
                    <button
                        @click="closeModal"
                        class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600 transition-all"
                    >
                        <i class="fas fa-times mr-2"></i>
                        Exit
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, watch, computed } from 'vue';
import axios from 'axios';

const props = defineProps({
    show: Boolean,
    customerCode: String,
    indexNumber: [Number, String]
});

const emit = defineEmits(['close', 'saved']);

const productGroups = ref([]);
const loading = ref(false);

// Computed tieup count
const tieupCount = computed(() => {
    return productGroups.value.filter(pg => pg.tie_up_enabled).length;
});

// Load data when modal is shown
watch(() => props.show, async (newVal) => {
    if (newVal && props.customerCode && props.indexNumber) {
        await loadData();
    }
});

// Load product groups and existing tieups
const loadData = async () => {
    loading.value = true;
    try {
        // Load all product groups
        const pgResponse = await axios.get('/api/product-groups');
        console.log('Product groups API response:', pgResponse.data);
        
        // ProductGroupController returns array directly, not wrapped in data object
        let rawProductGroups = Array.isArray(pgResponse.data) ? pgResponse.data : (pgResponse.data.data || []);
        
        // Process data to normalize field names
        const allProductGroups = rawProductGroups.map(item => ({
            id: item.id,
            code: item.product_group_id || item.code,
            name: item.product_group_name || item.name,
            is_active: typeof item.is_active !== 'undefined' ? item.is_active : true
        }));
        
        console.log('Processed product groups:', allProductGroups);

        // Load existing tieups
        let existingTieups = [];
        try {
            const tieupResponse = await axios.get(
                `/api/invoices/customer-tax-indices/${props.customerCode}/${props.indexNumber}/product-tieups`
            );
            if (tieupResponse.data.success) {
                existingTieups = tieupResponse.data.data || [];
            }
        } catch (error) {
            console.log('No existing tieups found');
        }

        // Merge data
        productGroups.value = allProductGroups.map(pg => {
            const existingTieup = existingTieups.find(t => t.product_group_code === pg.code);
            return {
                ...pg,
                tie_up_enabled: existingTieup ? existingTieup.tie_up_enabled : false
            };
        });
        
        console.log('Final product groups with tie-up status:', productGroups.value);
    } catch (error) {
        console.error('Error loading data:', error);
        alert('Failed to load product groups: ' + (error.response?.data?.message || error.message));
    } finally {
        loading.value = false;
    }
};

// Toggle tieup for a product group
const toggleTieup = (code) => {
    const pg = productGroups.value.find(p => p.code === code);
    if (pg) {
        pg.tie_up_enabled = !pg.tie_up_enabled;
    }
};

// Check all
const checkAll = () => {
    productGroups.value.forEach(pg => {
        pg.tie_up_enabled = true;
    });
};

// Uncheck all
const uncheckAll = () => {
    productGroups.value.forEach(pg => {
        pg.tie_up_enabled = false;
    });
};

// Save tieups
const saveTieups = async () => {
    if (!confirm('Confirm Saving / Updating ?')) {
        return;
    }

    try {
        // Prepare data - send ALL product groups with their tie_up_enabled status
        const tieups = productGroups.value.map(pg => ({
            product_group_code: pg.code,
            tie_up_enabled: pg.tie_up_enabled
        }));

        const response = await axios.post(
            `/api/invoices/customer-tax-indices/${props.customerCode}/${props.indexNumber}/product-tieups`,
            { tieups }
        );

        if (response.data.success) {
            alert('Product tie-ups saved successfully!');
            emit('saved');
            closeModal();
        }
    } catch (error) {
        console.error('Error saving tieups:', error);
        alert('Failed to save: ' + (error.response?.data?.message || error.message));
    }
};

// Close modal
const closeModal = () => {
    emit('close');
};
</script>
