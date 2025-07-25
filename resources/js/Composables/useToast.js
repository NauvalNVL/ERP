import { reactive, toRefs } from 'vue';

const state = reactive({
  toasts: [],
  nextId: 0,
});

export function useToast() {
  const addToast = (message, type = 'success', duration = 3000) => {
    const id = state.nextId++;
    state.toasts.push({ id, message, type });

    setTimeout(() => {
      removeToast(id);
    }, duration);
  };

  const removeToast = (id) => {
    const index = state.toasts.findIndex((toast) => toast.id === id);
    if (index !== -1) {
      state.toasts.splice(index, 1);
    }
  };

  const success = (message) => addToast(message, 'success');
  const error = (message) => addToast(message, 'error');
  const warning = (message) => addToast(message, 'warning');
  const info = (message) => addToast(message, 'info');

  return {
    ...toRefs(state),
    addToast,
    removeToast,
    success,
    error,
    warning,
    info,
  };
} 