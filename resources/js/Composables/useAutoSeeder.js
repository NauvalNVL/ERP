import { ref, onMounted } from 'vue'
import { useToast } from './useToast'

export function useAutoSeeder() {
    const { success, error, loading } = useToast()
    const isSeeding = ref(false)
    const hasSeeded = ref(false)

    const getCsrfToken = () => {
        return document.querySelector('meta[name="csrf-token"]')?.content || ''
    }

    const runAutoSeed = async () => {
        if (isSeeding.value || hasSeeded.value) return

        isSeeding.value = true
        loading('Auto-seeding standard requirements...')

        try {
            const csrfToken = getCsrfToken()
            
            const response = await fetch('/api/auto-seed-standard-requirements', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                credentials: 'same-origin'
            })

            if (!response.ok) {
                const errorData = await response.json()
                throw new Error(errorData.message || 'Error running auto-seed')
            }

            const result = await response.json()
            
            if (result.success) {
                hasSeeded.value = true
                success('Standard requirements auto-seeded successfully!')
                
                // Log individual seeder results
                if (result.results) {
                    const failedSeeders = result.results.filter(r => r.status === 'error')
                    if (failedSeeders.length > 0) {
                        console.warn('Some seeders failed:', failedSeeders)
                    }
                }
            } else {
                throw new Error(result.message || 'Auto-seed failed')
            }
        } catch (e) {
            console.error('Auto-seed error:', e)
            error('Auto-seed failed: ' + e.message)
        } finally {
            isSeeding.value = false
        }
    }

    const checkAndRunAutoSeed = async () => {
        // Check if we should run auto-seed (only once per session)
        const sessionKey = 'standard_requirements_auto_seeded'
        const hasRunInSession = sessionStorage.getItem(sessionKey)
        
        if (!hasRunInSession) {
            await runAutoSeed()
            sessionStorage.setItem(sessionKey, 'true')
        }
    }

    return {
        isSeeding,
        hasSeeded,
        runAutoSeed,
        checkAndRunAutoSeed
    }
}
