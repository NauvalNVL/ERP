import { reactive } from 'vue';

const toastState = reactive({
  toasts: [],
  toastId: 0
});

/**
 * Simple toast notification composable
 */
export function useToast() {
    const addToast = (message, type = 'success', duration = 3000) => {
        const id = toastState.toastId++;
        const toast = { id, message, type, duration };
        toastState.toasts.push(toast);
        
        setTimeout(() => {
            removeToast(id);
        }, duration);
        
        return id;
    };
    
    const removeToast = (id) => {
        const index = toastState.toasts.findIndex(toast => toast.id === id);
        if (index !== -1) {
            toastState.toasts.splice(index, 1);
        }
    };

    const success = (message) => {
        addToast(message, 'success');
    };

    const error = (message) => {
        addToast(message, 'error');
    };

    const warning = (message) => {
        addToast(message, 'warning');
    };

    const info = (message) => {
        addToast(message, 'info');
    };

    /**
     * Show a toast notification
     * @param {string} message - The message to display
     * @param {string} type - The type of toast: 'success', 'error', 'warning', 'info'
     */
    const showToast = (message, type) => {
        // Create toast element
        const toast = document.createElement('div');
        toast.className = `fixed z-50 top-4 right-4 px-4 py-2 rounded-lg shadow-lg transform transition-all duration-500 ease-in-out max-w-xs`;
        
        // Set style based on type
        switch (type) {
            case 'success':
                toast.className += ' bg-green-500 text-white';
                toast.innerHTML = `<div class="flex items-center"><i class="fas fa-check-circle mr-2"></i>${message}</div>`;
                break;
            case 'error':
                toast.className += ' bg-red-500 text-white';
                toast.innerHTML = `<div class="flex items-center"><i class="fas fa-exclamation-circle mr-2"></i>${message}</div>`;
                break;
            case 'warning':
                toast.className += ' bg-yellow-500 text-white';
                toast.innerHTML = `<div class="flex items-center"><i class="fas fa-exclamation-triangle mr-2"></i>${message}</div>`;
                break;
            default:
                toast.className += ' bg-blue-500 text-white';
                toast.innerHTML = `<div class="flex items-center"><i class="fas fa-info-circle mr-2"></i>${message}</div>`;
        }
        
        // Add toast to DOM
        document.body.appendChild(toast);
        
        // Animate in
        setTimeout(() => {
            toast.style.transform = 'translateY(10px)';
        }, 10);
        
        // Auto remove after 3 seconds
        setTimeout(() => {
            toast.style.transform = 'translateY(-10px)';
            toast.style.opacity = '0';
            setTimeout(() => {
                document.body.removeChild(toast);
            }, 300);
        }, 3000);
    };

    return {
        toasts: toastState.toasts,
        addToast,
        removeToast,
        success,
        error,
        warning,
        info
    };
} 