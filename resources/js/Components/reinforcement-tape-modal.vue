<template>
	<div v-if="show" class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto bg-black bg-opacity-50 px-4 sm:px-0">
		<div class="bg-white rounded-lg shadow-lg w-full max-w-4xl max-h-[90vh] flex flex-col">
			<!-- Modal Header -->
			<div class="flex items-center justify-between p-4 border-b border-gray-200 bg-gradient-to-r from-green-600 to-green-700 text-white rounded-t-lg">
				<div class="flex items-center">
					<div class="p-2 bg-white bg-opacity-30 rounded-lg mr-3">
						<i class="fas fa-tape"></i>
					</div>
					<h3 class="text-xl font-semibold">Reinforcement Tape Table</h3>
				</div>
				<button @click="$emit('close')" class="text-white hover:text-gray-200 focus:outline-none transform active:translate-y-px">
					<i class="fas fa-times text-xl"></i>
				</button>
			</div>

			<!-- Modal Content -->
			<div class="p-5 flex-1 flex flex-col min-h-0">
				<div class="relative mb-3">
					<span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
						<i class="fas fa-search"></i>
					</span>
					<input type="text" v-model="searchQuery" placeholder="Search reinforcement tapes..."
						class="pl-10 pr-4 py-2 w-full border border-gray-300 rounded-lg focus:ring-emerald-500 focus:border-emerald-500 bg-gray-50">
				</div>
				<div class="overflow-x-auto rounded-lg border border-gray-200 max-h-96 flex-1 min-h-0">
					<table class="w-full divide-y divide-gray-200 table-fixed min-w-[560px] md:min-w-0">
						<thead class="bg-gray-50 sticky top-0">
							<tr>
								<th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/6 cursor-pointer" @click="sortTable('code')">Code</th>
								<th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-2/5 cursor-pointer" @click="sortTable('name')">Name</th>
								<th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-1/5">Status</th>
								<th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/6 cursor-pointer" @click="sortTable('dry_end_code')">Dry-End Code</th>
							</tr>
						</thead>
						<tbody class="bg-white divide-y divide-gray-200 text-xs">
							<tr v-for="item in filteredRows" :key="item.id || item.code"
								:class="['hover:bg-emerald-50 cursor-pointer', selectedRow && (selectedRow.id || selectedRow.code) === (item.id || item.code) ? 'bg-emerald-100 border-l-4 border-emerald-500' : '']"
								@click="selectRow(item)"
								@dblclick="selectAndClose(item)">
								<td class="px-4 py-3 whitespace-nowrap font-medium text-gray-900">{{ item.code }}</td>
								<td class="px-4 py-3 whitespace-nowrap text-gray-700 truncate max-w-[220px] md:max-w-none">{{ item.name }}</td>
								<td class="px-4 py-3 whitespace-nowrap text-center">
									<span
										:class="[
											item.status === 'Obs'
												? 'bg-red-100 text-red-800'
												: 'bg-emerald-100 text-emerald-800',
											'px-2 py-1 text-[10px] font-semibold rounded-full inline-flex items-center justify-center'
										]"
									>
										{{ item.status === 'Obs' ? 'Obsolete' : 'Active' }}
									</span>
								</td>
								<td class="px-4 py-3 whitespace-nowrap text-gray-700">{{ item.dry_end_code || '-' }}</td>
							</tr>
							<tr v-if="filteredRows.length === 0">
								<td colspan="4" class="px-4 py-4 text-center text-gray-500">No reinforcement tape data available.</td>
							</tr>
						</tbody>
					</table>
				</div>
				<div class="mt-4 flex items-center justify-end space-x-3">
					<button type="button" @click="$emit('close')" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm rounded-lg border border-gray-300 transform active:translate-y-px">
						<i class="fas fa-times mr-1"></i>Exit
					</button>
					<button type="button" @click="selectAndClose(selectedRow)" class="px-4 py-2 bg-gradient-to-r from-emerald-500 to-green-600 hover:from-emerald-600 hover:to-green-700 text-white text-sm rounded-lg transform active:translate-y-px">
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
	rows: {
		type: Array,
		default: () => []
	}
});

const emit = defineEmits(['close', 'select']);

const searchQuery = ref('');
const selectedRow = ref(null);
const sortKey = ref('code');
const sortAsc = ref(true);

const filteredRows = computed(() => {
	let rows = props.rows || [];
	if (searchQuery.value) {
		const q = searchQuery.value.toLowerCase();
		rows = rows.filter(r =>
			(r.code || '').toString().toLowerCase().includes(q) ||
			(r.name || '').toLowerCase().includes(q) ||
			(r.dry_end_code || '').toLowerCase().includes(q)
		);
	}
	return [...rows].sort((a, b) => {
		const aVal = (a[sortKey.value] || '').toString();
		const bVal = (b[sortKey.value] || '').toString();
		return sortAsc.value ? aVal.localeCompare(bVal) : bVal.localeCompare(aVal);
	});
});

function selectRow(row) {
	selectedRow.value = row;
}

function selectAndClose(row) {
	if (row) {
		emit('select', row);
		emit('close');
	}
}

function sortTable(key) {
	if (sortKey.value === key) sortAsc.value = !sortAsc.value; else { sortKey.value = key; sortAsc.value = true; }
}

watch(() => props.show, (val) => {
	if (val) {
		selectedRow.value = null;
		searchQuery.value = '';
	}
});
</script>

<style scoped>
.fixed.inset-0 {
	backdrop-filter: blur(1px);
}
</style>


