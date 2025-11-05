<template>
	<div v-if="show" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
		<div class="bg-white rounded-lg shadow-lg w-full max-w-4xl">
			<!-- Modal Header -->
			<div class="flex items-center justify-between p-4 border-b border-gray-200 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-t-lg">
				<div class="flex items-center">
					<div class="p-2 bg-white bg-opacity-30 rounded-lg mr-3">
						<i class="fas fa-vial"></i>
					</div>
					<h3 class="text-xl font-semibold">Chemical Coat Table</h3>
				</div>
				<button @click="$emit('close')" class="text-white hover:text-gray-200 focus:outline-none transform active:translate-y-px">
					<i class="fas fa-times text-xl"></i>
				</button>
			</div>

			<!-- Modal Content -->
			<div class="p-5">
				<div class="relative mb-3">
					<span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
						<i class="fas fa-search"></i>
					</span>
					<input type="text" v-model="searchQuery" placeholder="Search chemical coats..."
						class="pl-10 pr-4 py-2 w-full border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 bg-gray-50">
				</div>
				<div class="overflow-x-auto rounded-lg border border-gray-200 max-h-96">
					<table class="w-full divide-y divide-gray-200 table-fixed">
						<thead class="bg-gray-50 sticky top-0">
							<tr>
								<th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/6 cursor-pointer" @click="sortTable('code')">Code</th>
								<th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-2/3 cursor-pointer" @click="sortTable('name')">Name</th>
								<th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/6 cursor-pointer" @click="sortTable('dry_end_code')">Dry-End Code</th>
							</tr>
						</thead>
						<tbody class="bg-white divide-y divide-gray-200 text-xs">
							<tr v-if="isLoading">
								<td colspan="3" class="px-4 py-4 text-center text-gray-500">
									<div class="flex items-center justify-center">
										<div class="animate-spin rounded-full h-6 w-6 border-b-2 border-blue-500 mr-3"></div>
										<span>Loading chemical coat data...</span>
									</div>
								</td>
							</tr>
							<tr v-else-if="filteredCoats.length === 0">
								<td colspan="3" class="px-4 py-4 text-center text-gray-500">No chemical coat data available.</td>
							</tr>
							<tr v-else v-for="coat in filteredCoats" :key="coat.id || coat.code"
								:class="['hover:bg-blue-50 cursor-pointer', selectedCoat && (selectedCoat.id || selectedCoat.code) === (coat.id || coat.code) ? 'bg-blue-100 border-l-4 border-blue-500' : '']"
								@click="selectRow(coat)"
								@dblclick="selectAndClose(coat)">
								<td class="px-4 py-3 whitespace-nowrap font-medium text-gray-900">{{ coat.code }}</td>
								<td class="px-4 py-3 whitespace-nowrap text-gray-700">{{ coat.name }}</td>
								<td class="px-4 py-3 whitespace-nowrap text-gray-700">{{ coat.dry_end_code || '-' }}</td>
							</tr>
						</tbody>
					</table>
				</div>
				<div class="mt-4 flex items-center justify-end space-x-3">
					<button type="button" @click="$emit('close')" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm rounded-lg border border-gray-300 transform active:translate-y-px">
						<i class="fas fa-times mr-1"></i>Exit
					</button>
					<button type="button" @click="selectAndClose(selectedCoat)" class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white text-sm rounded-lg transform active:translate-y-px">
						<i class="fas fa-check mr-1"></i>Select
					</button>
				</div>
			</div>
		</div>
	</div>
</template>

<script setup>
import { ref, computed, watch } from 'vue';

const props = defineProps({
	show: Boolean,
	items: {
		type: Array,
		default: () => []
	},
	loading: {
		type: Boolean,
		default: false
	}
});

const emit = defineEmits(['close', 'select']);

const searchQuery = ref('');
const selectedCoat = ref(null);
const sortKey = ref('code');
const sortAsc = ref(true);

const filteredCoats = computed(() => {
	let rows = props.items || [];
	if (searchQuery.value) {
		const q = searchQuery.value.toLowerCase();
		rows = rows.filter(c =>
			(c.code || '').toString().toLowerCase().includes(q) ||
			(c.name || '').toLowerCase().includes(q) ||
			(c.dry_end_code || '').toLowerCase().includes(q)
		);
	}
	return [...rows].sort((a, b) => {
		const aVal = (a[sortKey.value] || '').toString();
		const bVal = (b[sortKey.value] || '').toString();
		return sortAsc.value ? aVal.localeCompare(bVal) : bVal.localeCompare(aVal);
	});
});

const isLoading = computed(() => {
	return props.loading || (props.items.length === 0 && !props.loading);
});

function selectRow(coat) {
	selectedCoat.value = coat;
}

function selectAndClose(coat) {
	if (coat) {
		emit('select', coat);
		emit('close');
	}
}

function sortTable(key) {
	if (sortKey.value === key) sortAsc.value = !sortAsc.value; else { sortKey.value = key; sortAsc.value = true; }
}

watch(() => props.show, (val) => {
	if (val) {
		selectedCoat.value = null;
		searchQuery.value = '';
	}
});
</script>

<style scoped>
.fixed.inset-0 {
	backdrop-filter: blur(1px);
}
</style>


