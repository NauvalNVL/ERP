import { ref, reactive } from 'vue';

// Load saved state from localStorage if available
const loadSavedState = () => {
  try {
    const savedState = localStorage.getItem('sidebarState');
    return savedState ? JSON.parse(savedState) : {};
  } catch (e) {
    console.error('Error loading sidebar state from localStorage', e);
    return {};
  }
};

export const useSidebarStore = () => {
  const state = reactive(loadSavedState());
  const mobileOpen = ref(false);

  const isOpen = (id) => {
    return state[id] === true;
  };

  const toggle = (id) => {
    state[id] = !isOpen(id);
    // Save state to localStorage
    try {
      localStorage.setItem('sidebarState', JSON.stringify(state));
    } catch (e) {
      console.error('Error saving sidebar state to localStorage', e);
    }
  };

  const toggleMobile = () => {
    mobileOpen.value = !mobileOpen.value;
  };

  return {
    state,
    mobileOpen,
    isOpen,
    toggle,
    toggleMobile
  };
};

// Create a singleton instance
const sidebarStore = useSidebarStore();
export default sidebarStore; 