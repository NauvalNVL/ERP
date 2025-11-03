<template>
    <!-- Tax Group Table Modal (CPS-style) -->
    <div v-if="show" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-2xl w-3/5 max-w-3xl max-h-[70vh] flex flex-col">
            <!-- Modal Header -->
            <div class="bg-gradient-to-r from-blue-600 to-cyan-600 text-white px-4 py-3 rounded-t-lg flex justify-between items-center">
                <div class="flex items-center gap-2">
                    <i class="fas fa-table"></i>
                    <h3 class="text-sm font-semibold">Tax Group Table</h3>
                </div>
                <button @click="closeModal" class="text-white/90 hover:text-white">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <!-- Table Content -->
            <div class="flex-1 overflow-auto p-4">
                <table class="w-full text-sm">
                    <thead class="bg-gray-100 border-b-2 border-gray-300">
                        <tr>
                            <th class="px-4 py-2 text-left font-semibold w-32">Code</th>
                            <th class="px-4 py-2 text-left font-semibold">Name</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="group in taxGroups"
                            :key="group.code"
                            @dblclick="selectGroup(group)"
                            :class="selectedRow === group.code ? 'bg-blue-500 text-white' : 'hover:bg-gray-100'"
                            @click="selectedRow = group.code"
                            class="cursor-pointer border-b"
                        >
                            <td class="px-4 py-2">{{ group.code }}</td>
                            <td class="px-4 py-2">{{ group.name }}</td>
                        </tr>
                        <tr v-if="taxGroups.length === 0">
                            <td colspan="2" class="px-4 py-6 text-center text-gray-500">No tax groups found</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Modal Footer -->
            <div class="px-4 py-3 bg-gray-50 border-t flex justify-center gap-3">
                <button @click="selectGroup(getSelectedGroup())" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded text-sm">
                    Select
                </button>
                <button @click="closeModal" class="px-6 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 rounded text-sm">
                    Exit
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, watch, onMounted } from 'vue';
import axios from 'axios';

const props = defineProps({
    show: {
        type: Boolean,
        default: false
    }
});

const emit = defineEmits(['close', 'select']);

const taxGroups = ref([]);
const selectedRow = ref(null);

const loadTaxGroups = async () => {
    try {
        const res = await axios.get('/api/invoices/tax-groups');
        console.log('TaxGroupModal API Response:', res.data);
        
        // Handle different response formats
        if (res.data) {
            if (res.data.success && Array.isArray(res.data.data)) {
                taxGroups.value = res.data.data;
                console.log('✅ Loaded tax groups:', taxGroups.value.length);
            } else if (Array.isArray(res.data)) {
                taxGroups.value = res.data;
                console.log('✅ Loaded tax groups (direct array):', taxGroups.value.length);
            } else {
                console.warn('⚠️ Unexpected response format:', res.data);
                taxGroups.value = [];
            }
        }
    } catch (e) {
        console.error('❌ Error loading tax groups:', e);
        console.error('Error details:', e.response?.data);
        taxGroups.value = [];
    }
};

const getSelectedGroup = () => {
    return taxGroups.value.find(g => g.code === selectedRow.value);
};

const selectGroup = (group) => {
    if (!group) return;
    emit('select', group);
};

const closeModal = () => {
    emit('close');
};

watch(() => props.show, (newVal) => {
    if (newVal) {
        loadTaxGroups();
        selectedRow.value = null;
    }
});

// Also load on mount for safety
onMounted(() => {
    if (props.show) {
        loadTaxGroups();
    }
});
</script>

<style scoped>
tr.cursor-pointer:hover {
    background-color: #f3f4f6;
}
</style>
