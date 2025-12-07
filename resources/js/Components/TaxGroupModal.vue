<template>
	<div v-if="show" class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto bg-black bg-opacity-50 px-4 sm:px-0">
		<div class="bg-white rounded-lg shadow-lg w-full max-w-4xl max-h-[90vh] flex flex-col">
			<!-- Modal Header (color-modal style) -->
			<div class="flex items-center justify-between p-4 border-b border-gray-200 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-t-lg">
				<div class="flex items-center">
					<div class="p-2 bg-white bg-opacity-30 rounded-lg mr-3">
						<i class="fas fa-layer-group"></i>
					</div>
					<h3 class="text-xl font-semibold">Tax Group Table</h3>
				</div>
				<button @click="closeModal" class="text-white hover:text-gray-200 focus:outline-none transform active:translate-y-px">
					<i class="fas fa-times text-xl"></i>
				</button>
			</div>

			<!-- Modal Content -->
			<div class="p-5 flex-1 flex flex-col min-h-0">
				<!-- Search Bar -->
				<div class="mb-4">
					<div class="relative">
						<span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
							<i class="fas fa-search"></i>
						</span>
						<input
							type="text"
							v-model="searchQuery"
							placeholder="Search tax groups..."
							class="pl-10 pr-4 py-2 w-full border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 bg-gray-50 text-sm"
						/>
					</div>
				</div>

				<!-- Table Content -->
				<div class="overflow-x-auto rounded-lg border border-gray-200 max-h-96 flex-1 min-h-0">
					<table class="w-full divide-y divide-gray-200 table-fixed min-w-[480px] md:min-w-0 text-sm">
						<thead class="bg-gray-50 sticky top-0 z-10">
							<tr>
								<th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-[25%]">Code</th>
								<th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
								<th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-[20%]">Status</th>
							</tr>
						</thead>
						<tbody class="bg-white divide-y divide-gray-200">
							<tr
								v-for="group in filteredTaxGroups"
								:key="group.code"
								:class="['cursor-pointer', selectedRow === group.code ? 'bg-blue-100 border-l-4 border-blue-500' : 'hover:bg-gray-50']"
								@click="selectedRow = group.code"
								@dblclick="selectGroup(group)"
							>
								<td class="px-4 py-3 whitespace-nowrap font-medium text-gray-900">{{ group.code }}</td>
								<td class="px-4 py-3 whitespace-nowrap text-gray-700 truncate max-w-[260px] md:max-w-none">{{ group.name }}</td>
								<td class="px-4 py-3 whitespace-nowrap">
									<span
										class="px-2 py-1 text-xs font-medium rounded-full"
										:class="group.status === 'O' ? 'bg-red-100 text-red-800' : 'bg-emerald-100 text-emerald-800'"
									>
										{{ group.status === 'O' ? 'Obsolete' : 'Active' }}
									</span>
								</td>
							</tr>
							<tr v-if="filteredTaxGroups.length === 0">
								<td colspan="3" class="px-6 py-4 text-center text-gray-500 text-sm">No tax groups found.</td>
							</tr>
						</tbody>
					</table>
				</div>

				<!-- Helper Text -->
				<div class="mt-2 text-xs text-gray-500 italic">
					<p>Click on a row to select a tax group. Double-click to confirm selection.</p>
				</div>

				<!-- Footer Buttons -->
				<div class="mt-4 flex justify-end gap-2">
					<button
						type="button"
						@click="selectGroup(getSelectedGroup())"
						class="py-2 px-4 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white text-xs rounded-lg transform active:translate-y-px disabled:opacity-50 disabled:cursor-not-allowed"
						:disabled="!getSelectedGroup()"
					>
						<i class="fas fa-check mr-1"></i>Select
					</button>
					<button
						type="button"
						@click="closeModal"
						class="py-2 px-4 bg-gray-300 hover:bg-gray-400 text-gray-800 text-xs rounded-lg transform active:translate-y-px"
					>
						<i class="fas fa-times mr-1"></i>Exit
					</button>
				</div>
			</div>
		</div>
	</div>
</template>

<script setup>
import { ref, computed, watch, onMounted } from 'vue';
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
const searchQuery = ref('');

const filteredTaxGroups = computed(() => {
	let groups = Array.isArray(taxGroups.value) ? [...taxGroups.value] : [];

	// Only show active groups by default (hide obsolete ones)
	groups = groups.filter(group => (group.status || 'A') !== 'O');

	if (searchQuery.value) {
		const q = searchQuery.value.toLowerCase();
		groups = groups.filter(group => {
			const code = (group.code || '').toString().toLowerCase();
			const name = (group.name || '').toString().toLowerCase();
			return code.includes(q) || name.includes(q);
		});
	}

	return groups;
});

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
