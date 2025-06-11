/**
 * Simple toast notification composable
 */
export function useToast() {
    /**
     * Show a toast notification
     * @param {string} message - The message to display
     * @param {string} type - The type of toast: 'success', 'error', 'warning', 'info'
     * @param {number} duration - Duration in milliseconds
     */
    const showToast = (message, type = 'info', duration = 3000) => {
        // Create toast container if it doesn't exist
        let toastContainer = document.getElementById('toast-container');
        
        if (!toastContainer) {
            toastContainer = document.createElement('div');
            toastContainer.id = 'toast-container';
            toastContainer.className = 'fixed top-4 right-4 z-50 flex flex-col gap-2';
            document.body.appendChild(toastContainer);
        }
        
        // Create toast element
        const toast = document.createElement('div');
        toast.className = 'px-4 py-2 rounded-md shadow-lg transform transition-all duration-300 ease-in-out';
        
        // Add appropriate styling based on type
        switch(type) {
            case 'success':
                toast.classList.add('bg-green-500', 'text-white');
                break;
            case 'error':
                toast.classList.add('bg-red-500', 'text-white');
                break;
            case 'warning':
                toast.classList.add('bg-yellow-500', 'text-white');
                break;
            default:
                toast.classList.add('bg-blue-500', 'text-white');
        }
        
        // Add message content
        toast.textContent = message;
        
        // Add to container
        toastContainer.appendChild(toast);
        
        // Animation: slide in from right
        setTimeout(() => {
            toast.classList.add('translate-x-0');
            toast.classList.remove('translate-x-full');
        }, 10);
        
        // Remove after duration
        setTimeout(() => {
            toast.classList.add('opacity-0');
            setTimeout(() => {
                toastContainer.removeChild(toast);
                
                // Remove container if empty
                if (toastContainer.childNodes.length === 0) {
                    document.body.removeChild(toastContainer);
                }
            }, 300);
        }, duration);
    };
    
    return {
        showToast
    };
} 