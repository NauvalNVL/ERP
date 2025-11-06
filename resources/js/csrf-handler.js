import { router } from '@inertiajs/vue3'

// Global CSRF token handler
export function setupCsrfHandler() {
    let isRefreshing = false;

    // Update CSRF token from response headers
    router.on('success', (event) => {
        const response = event.detail?.response;
        if (response && response.headers) {
            const newToken = response.headers.get('X-CSRF-Token');
            if (newToken) {
                updateCsrfToken(newToken);
            }
        }
    });

    // Intercept all requests to add fresh CSRF token
    router.on('before', (event) => {
        const { visit } = event.detail;

        // ✅ SECURITY FIX: Only add CSRF token to POST/PUT/PATCH/DELETE requests
        // GET requests should NOT have CSRF token to prevent token exposure in URL
        const isStateMutatingMethod = ['post', 'put', 'patch', 'delete'].includes(
            (visit.method || 'get').toLowerCase()
        );

        if (isStateMutatingMethod && visit.data && typeof visit.data === 'object') {
            const csrfToken = getFreshCsrfToken();
            if (csrfToken) {
                visit.data._token = csrfToken;
            }
        }
    });

    // Handle CSRF token mismatch errors
    router.on('error', (event) => {
        const response = event.detail.response;

        // Check for 419 CSRF token mismatch
        if (response && response.status === 419 && !isRefreshing) {
            isRefreshing = true;
            console.log('CSRF token expired (419), refreshing...');

            // Reload page to get fresh token
            router.reload({
                preserveState: false,
                preserveScroll: true,
                onSuccess: () => {
                    console.log('Page refreshed with new CSRF token');
                    isRefreshing = false;
                },
                onError: () => {
                    console.log('Failed to refresh, redirecting to login');
                    window.location.href = '/login';
                }
            });
        }
    });
}

// Function to get fresh CSRF token
export function getFreshCsrfToken() {
    return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ||
           window.Laravel?.csrfToken ||
           document.querySelector('input[name="_token"]')?.value
}

// Function to update CSRF token in meta tag
export function updateCsrfToken(newToken) {
    const metaTag = document.querySelector('meta[name="csrf-token"]');
    if (metaTag) {
        metaTag.setAttribute('content', newToken);
    }

    // Update any hidden token inputs
    const tokenInputs = document.querySelectorAll('input[name="_token"]');
    tokenInputs.forEach(input => {
        input.value = newToken;
    });
}

// Function to refresh CSRF token
export function refreshCsrfToken() {
    return router.reload({
        only: [],
        preserveState: true,
        preserveScroll: true
    });
}
