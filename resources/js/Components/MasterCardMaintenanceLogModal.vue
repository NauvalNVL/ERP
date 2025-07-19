<template>
    <div v-if="show" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-30 py-8">
        <div class="bg-white rounded-lg shadow-2xl w-full max-w-2xl mx-auto relative animate-fade-in-up max-h-[90vh] flex flex-col">
            <!-- Modal Header -->
            <div class="bg-gradient-to-r from-blue-600 to-blue-700 p-4 rounded-t-lg shadow-md flex items-center justify-between flex-shrink-0">
                <h2 class="text-xl font-bold text-white">Zoom Master Card Maintenance Log</h2>
                <button @click="$emit('close')" class="text-white hover:text-gray-200 text-2xl transition-transform transform hover:rotate-90">&times;</button>
            </div>

            <!-- Modal Body -->
            <div class="p-4 flex-grow overflow-y-auto">
                <div class="mb-4 flex items-center space-x-2">
                    <label for="log-date" class="text-sm font-medium text-gray-700">Date:</label>
                    <input type="text" id="log-date" v-model="searchDate" placeholder="dd/mm/yyyy"
                           class="w-32 px-2 py-1 border border-gray-300 rounded-md text-sm focus:ring-blue-500 focus:border-blue-500" />
                    <button @click="performSearch" class="px-4 py-2 bg-blue-500 text-white rounded-md text-sm hover:bg-blue-600">Search</button>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full text-xs border border-gray-300">
                        <thead class="bg-gray-200">
                            <tr>
                                <th class="px-2 py-1 border border-gray-300 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                <th class="px-2 py-1 border border-gray-300 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time</th>
                                <th class="px-2 py-1 border border-gray-300 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User ID</th>
                                <th class="px-2 py-1 border border-gray-300 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User ID 2</th>
                                <th class="px-2 py-1 border border-gray-300 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="(log, index) in filteredLogs" :key="index">
                                <td class="px-2 py-1 border border-gray-300">{{ log.date }}</td>
                                <td class="px-2 py-1 border border-gray-300">{{ log.time }}</td>
                                <td class="px-2 py-1 border border-gray-300">{{ log.userId }}</td>
                                <td class="px-2 py-1 border border-gray-300">{{ log.userId2 }}</td>
                                <td class="px-2 py-1 border border-gray-300">{{ log.action }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="flex justify-end p-4 border-t border-gray-200 bg-gray-100 rounded-b-lg flex-shrink-0">
                <button @click="$emit('close')" class="px-4 py-2 bg-gray-300 text-gray-800 rounded-md hover:bg-gray-400">Exit</button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed } from 'vue';

const props = defineProps({
    show: Boolean,
});

const emit = defineEmits(['close']);

const searchDate = ref('');

const dummyLogs = ref([
    { date: '18/09/2024', time: '13:57:22', userId: 'mc01', userId2: 'mcs', action: 'Amend M/Card' },
    { date: '17/09/2024', time: '14:13:07', userId: 'mc02', userId2: '', action: 'New M/Card' },
]);

const filteredLogs = computed(() => {
    if (!searchDate.value) {
        return dummyLogs.value;
    }
    const searchLower = searchDate.value.toLowerCase();
    return dummyLogs.value.filter(log => log.date.toLowerCase().includes(searchLower));
});

const performSearch = () => {
    // Filter is already handled by computed property `filteredLogs`
    console.log('Searching logs for date:', searchDate.value);
};
</script>

<style scoped>
/* Add modal animations */
@keyframes fade-in-up {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fade-in-up {
    animation: fade-in-up 0.3s ease-out forwards;
}

/* Basic table styling for consistency */
table {
    width: 100%;
    border-collapse: collapse;
}

th, td {
    border: 1px solid #e2e8f0; /* gray-300 */
    padding: 8px;
    text-align: left;
}

thead th {
    background-color: #edf2f7; /* gray-200 */
}
</style> 