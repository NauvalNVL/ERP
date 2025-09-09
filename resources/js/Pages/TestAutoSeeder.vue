<template>
    <AppLayout header="Test Auto-Seeder">
        <Head title="Test Auto-Seeder" />
        
        <div class="max-w-4xl mx-auto p-6">
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h1 class="text-2xl font-bold text-gray-800 mb-6">Auto-Seeder Test Page</h1>
                
                <div class="mb-6">
                    <button 
                        @click="runAutoSeed" 
                        :disabled="isSeeding"
                        class="px-6 py-3 bg-blue-500 text-white rounded-lg hover:bg-blue-600 disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        <i v-if="isSeeding" class="fas fa-spinner fa-spin mr-2"></i>
                        <i v-else class="fas fa-seedling mr-2"></i>
                        {{ isSeeding ? 'Running Auto-Seed...' : 'Run Auto-Seed' }}
                    </button>
                </div>
                
                <div v-if="results.length > 0" class="mt-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Seeding Results:</h2>
                    <div class="space-y-2">
                        <div 
                            v-for="result in results" 
                            :key="result.seeder"
                            class="p-3 rounded-lg border"
                            :class="{
                                'bg-green-50 border-green-200': result.status === 'success',
                                'bg-red-50 border-red-200': result.status === 'error'
                            }"
                        >
                            <div class="flex items-center justify-between">
                                <span class="font-medium">{{ result.seeder }}</span>
                                <span 
                                    class="px-2 py-1 rounded text-xs font-medium"
                                    :class="{
                                        'bg-green-100 text-green-800': result.status === 'success',
                                        'bg-red-100 text-red-800': result.status === 'error'
                                    }"
                                >
                                    {{ result.status }}
                                </span>
                            </div>
                            <p class="text-sm text-gray-600 mt-1">{{ result.message }}</p>
                        </div>
                    </div>
                </div>
                
                <div v-if="message" class="mt-4 p-4 rounded-lg" :class="messageType === 'success' ? 'bg-green-50 text-green-800' : 'bg-red-50 text-red-800'">
                    {{ message }}
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref } from 'vue'
import { Head } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import { useAutoSeeder } from '@/Composables/useAutoSeeder'

const { isSeeding, runAutoSeed: runSeeder } = useAutoSeeder()
const results = ref([])
const message = ref('')
const messageType = ref('')

const runAutoSeed = async () => {
    results.value = []
    message.value = ''
    
    try {
        const response = await fetch('/api/auto-seed-standard-requirements', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            credentials: 'same-origin'
        })
        
        const data = await response.json()
        
        if (data.success) {
            results.value = data.results || []
            message.value = data.message
            messageType.value = 'success'
        } else {
            message.value = data.message
            messageType.value = 'error'
        }
    } catch (error) {
        message.value = 'Error: ' + error.message
        messageType.value = 'error'
    }
}
</script>
