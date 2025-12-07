<template>
    <div v-if="show" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white rounded-xl shadow-2xl w-11/12 md:w-3/4 lg:w-2/3 max-w-4xl max-h-[90vh] flex flex-col">
            <!-- Header -->
            <div class="flex items-center justify-between px-6 py-4 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-t-xl">
                <div class="flex items-center">
                    <div class="p-2 bg-white bg-opacity-20 rounded-lg mr-3">
                        <i class="fas fa-history"></i>
                    </div>
                    <h3 class="text-lg font-semibold">{{ title }}</h3>
                </div>
                <button
                    type="button"
                    @click="$emit('close')"
                    class="text-white hover:text-gray-200 transition-colors"
                >
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <!-- Search Bar -->
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                        <i class="fas fa-search text-gray-400"></i>
                    </span>
                    <input
                        v-model="searchQuery"
                        type="text"
                        placeholder="Search by date..."
                        class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    />
                </div>
            </div>

            <!-- Table -->
            <div class="flex-1 overflow-auto px-6 py-4">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50 sticky top-0">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Date
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Time
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                User ID
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                User ID 2
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Action
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr
                            v-for="(log, index) in filteredLogs"
                            :key="index"
                            class="hover:bg-blue-50 transition-colors"
                        >
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                                {{ log.date }}
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                                {{ log.time }}
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                                {{ log.user_id }}
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">
                                {{ log.user_id_2 || '-' }}
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <span
                                    class="px-2 py-1 text-xs font-semibold rounded-full"
                                    :class="{
                                        'bg-green-100 text-green-800': log.action === 'REACTIVE' || log.action === 'CREATE',
                                        'bg-red-100 text-red-800': log.action === 'OBSOLETE',
                                        'bg-blue-100 text-blue-800': log.action === 'UPDATE' || log.action === 'MAINTENANCE',
                                    }"
                                >
                                    {{ log.action }}
                                </span>
                            </td>
                        </tr>
                        <tr v-if="filteredLogs.length === 0">
                            <td colspan="5" class="px-4 py-8 text-center text-gray-500">
                                <i class="fas fa-inbox text-3xl mb-2"></i>
                                <p>No log entries found</p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Footer -->
            <div class="px-6 py-4 bg-gray-50 rounded-b-xl border-t border-gray-200 flex justify-end">
                <button
                    type="button"
                    @click="$emit('close')"
                    class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors text-sm font-medium"
                >
                    Exit
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed } from 'vue';

const props = defineProps({
    show: {
        type: Boolean,
        default: false
    },
    title: {
        type: String,
        default: 'Master Card Log'
    },
    logs: {
        type: Array,
        default: () => []
    }
});

defineEmits(['close']);

const searchQuery = ref('');

const filteredLogs = computed(() => {
    if (!searchQuery.value) {
        return props.logs;
    }

    const query = searchQuery.value.toLowerCase();
    return props.logs.filter(log =>
        log.date?.toLowerCase().includes(query) ||
        log.time?.toLowerCase().includes(query) ||
        log.user_id?.toLowerCase().includes(query) ||
        log.action?.toLowerCase().includes(query)
    );
});
</script>
