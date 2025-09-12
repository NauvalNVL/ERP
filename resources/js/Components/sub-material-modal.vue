<template>
    <div v-if="show" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-5xl">
            <!-- Header -->
            <div class="flex items-center justify-between p-3 border-b border-gray-200 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-t-lg">
                <h3 class="text-lg font-semibold">Sub-Material Screen</h3>
                <button @click="$emit('close')" class="text-white hover:text-gray-200"><i class="fas fa-times text-xl"></i></button>
            </div>

            <!-- Toolbar (icons only visual) -->
            <div class="px-3 py-2 border-b border-gray-200 bg-gray-50 flex items-center space-x-2 text-gray-600">
                <i class="fas fa-power-off"></i>
                <i class="fas fa-times"></i>
                <i class="fas fa-save"></i>
                <i class="fas fa-print"></i>
                <i class="fas fa-sync-alt"></i>
            </div>

            <!-- Table -->
            <div class="p-3">
                <div class="border rounded">
                    <table class="w-full text-xs">
                        <thead class="bg-gray-100 border-b">
                            <tr>
                                <th class="px-3 py-2 text-left w-12">No</th>
                                <th class="px-3 py-2 text-left w-24">Sub-Mat#</th>
                                <th class="px-3 py-2 text-left">Name</th>
                                <th class="px-3 py-2 text-left w-20">Unit</th>
                                <th class="px-3 py-2 text-left w-24">Quantity</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(row, idx) in rows" :key="idx" class="border-b hover:bg-gray-50">
                                <td class="px-3 py-2">{{ String(idx + 1).padStart(2,'0') }}</td>
                                <td class="px-3 py-2">
                                    <div class="flex items-center">
                                        <input type="text" v-model="row.code" class="w-16 px-1 py-1 border border-gray-300">
                                        <button class="ml-1 px-2 py-1 bg-gray-200 border border-gray-300" title="Select Sub-Material">
                                            <i class="fas fa-table"></i>
                                        </button>
                                    </div>
                                </td>
                                <td class="px-3 py-2">
                                    <input type="text" v-model="row.name" class="w-full px-2 py-1 border border-gray-300">
                                </td>
                                <td class="px-3 py-2">
                                    <input type="text" v-model="row.unit" class="w-16 px-2 py-1 border border-gray-300">
                                </td>
                                <td class="px-3 py-2">
                                    <input type="text" v-model="row.qty" class="w-24 px-2 py-1 border border-gray-300 text-right" placeholder="0.0000">
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Footer actions -->
                <div class="mt-3 flex justify-end space-x-2">
                    <button class="px-3 py-1 bg-gray-300 hover:bg-gray-400 text-gray-800 text-xs rounded" @click="$emit('close')">Close</button>
                    <button class="px-3 py-1 bg-blue-600 hover:bg-blue-700 text-white text-xs rounded" @click="emitSave">Save</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, watch } from 'vue';

const props = defineProps({
    show: Boolean,
    value: {
        type: Array,
        default: () => []
    }
});

const emit = defineEmits(['close', 'update:value']);

const rows = ref(Array.from({ length: 10 }, (_, i) => ({ code: '', name: '', unit: '', qty: '' })));

watch(() => props.show, (val) => {
    if (val && Array.isArray(props.value) && props.value.length) {
        // shallow copy into rows up to 10
        rows.value = Array.from({ length: 10 }, (_, i) => ({ ...(props.value[i] || { code: '', name: '', unit: '', qty: '' }) }));
    }
});

const emitSave = () => {
    emit('update:value', rows.value);
    emit('close');
};
</script>


