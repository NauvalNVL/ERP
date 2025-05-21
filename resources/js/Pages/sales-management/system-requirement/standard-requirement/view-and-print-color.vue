<template>
    <Head title="View & Print Colors" />
    
    <div class="container mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-6 print:hidden">
            <h1 class="text-2xl font-semibold text-gray-700">View & Print Colors</h1>
            <button 
                @click="printTable" 
                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition duration-150 ease-in-out flex items-center">
                <i class="fas fa-print mr-2"></i> Print
            </button>
        </div>

        <div v-if="loading" class="flex justify-center items-center py-12">
            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-500"></div>
        </div>

        <div v-else class="bg-white shadow-md rounded-lg overflow-hidden">
            <table class="min-w-full leading-normal">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-5 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Color ID</th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Color Name</th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Origin</th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Color Group</th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">CG Type</th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Created At</th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Updated At</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="color in colors" :key="color.color_id" class="hover:bg-gray-50">
                        <td class="px-5 py-4 border-b border-gray-200 bg-white text-sm">{{ color.color_id || 'N/A' }}</td>
                        <td class="px-5 py-4 border-b border-gray-200 bg-white text-sm">{{ color.color_name || 'N/A' }}</td>
                        <td class="px-5 py-4 border-b border-gray-200 bg-white text-sm">{{ color.origin || 'N/A' }}</td>
                        <td class="px-5 py-4 border-b border-gray-200 bg-white text-sm">{{ getCGName(color.color_group_id) || 'N/A' }}</td>
                        <td class="px-5 py-4 border-b border-gray-200 bg-white text-sm">{{ color.cg_type || 'N/A' }}</td>
                        <td class="px-5 py-4 border-b border-gray-200 bg-white text-sm">{{ formatDate(color.created_at) }}</td>
                        <td class="px-5 py-4 border-b border-gray-200 bg-white text-sm">{{ formatDate(color.updated_at) }}</td>
                    </tr>
                    <tr v-if="colors.length === 0">
                        <td colspan="7" class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-center text-gray-500">
                            No colors found.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { Head } from '@inertiajs/vue3';

const colors = ref([]);
const colorGroups = ref([]);
const loading = ref(true);

onMounted(async () => {
    await Promise.all([fetchColors(), fetchColorGroups()]);
    loading.value = false;
});

const fetchColors = async () => {
    try {
        const response = await fetch('/api/colors', {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        
        if (!response.ok) {
            throw new Error('Failed to fetch colors');
        }
        
        const data = await response.json();
        colors.value = data;
    } catch (error) {
        console.error('Error fetching colors:', error);
    }
};

const fetchColorGroups = async () => {
    try {
        const response = await fetch('/api/color-groups', {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        
        if (!response.ok) {
            throw new Error('Failed to fetch color groups');
        }
        
        const data = await response.json();
        colorGroups.value = data;
    } catch (error) {
        console.error('Error fetching color groups:', error);
    }
};

const getCGName = (cgId) => {
    if (!cgId) return 'N/A';
    const group = colorGroups.value.find(cg => cg.cg === cgId);
    return group ? group.cg_name : cgId;
};

const formatDate = (dateString) => {
    if (!dateString) return 'N/A';
    const date = new Date(dateString);
    return date.toISOString().replace('T', ' ').substr(0, 19);
};

const printTable = () => {
    window.print();
};
</script>

<style>
@media print {
    body * {
        visibility: hidden;
    }
    .container, .container * {
        visibility: visible;
    }
    .container {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        margin: 0;
        padding: 0;
    }
    table {
        width: 100%;
        border-collapse: collapse;
    }
    th, td {
        border: 1px solid #ddd;
        padding: 8px;
        font-size: 10pt;
        color: #000;
    }
    thead {
        background-color: #eee !important;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }
    h1 {
        font-size: 14pt;
        margin-bottom: 1rem;
    }
    .print\:hidden {
        display: none !important;
    }
}
</style>
