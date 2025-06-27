import { ref, reactive } from 'vue';

// Load saved state from localStorage if available
const loadSavedState = () => {
  if (typeof window === 'undefined') return {};
  
  try {
    const savedState = localStorage.getItem('sidebarState');
    return savedState ? JSON.parse(savedState) : {};
  } catch (e) {
    console.error('Error loading sidebar state from localStorage', e);
    return {};
  }
};

// Initialize with saved state
const state = reactive(loadSavedState());

// Mobile sidebar state should always start as false (don't persist this)
const mobileOpen = ref(false);

const isOpen = (id) => {
  return state[id] === true;
};

const toggle = (id) => {
  state[id] = !isOpen(id);
  // Save state to localStorage
  saveState();
};

const saveState = () => {
  if (typeof window === 'undefined') return;
  
  try {
    localStorage.setItem('sidebarState', JSON.stringify(state));
  } catch (e) {
    console.error('Error saving sidebar state to localStorage', e);
  }
};

const toggleMobile = () => {
  mobileOpen.value = !mobileOpen.value;
};

// Set open state for a specific menu
const setOpen = (id, isOpen) => {
  state[id] = isOpen;
  saveState();
};

// Reset all menus to closed state
const resetState = () => {
  Object.keys(state).forEach(key => {
    state[key] = false;
  });
  saveState();
};

// Create singleton instance of the store
const sidebarStore = {
  state,
  mobileOpen,
  isOpen,
  toggle,
  toggleMobile,
  setOpen,
  resetState,
  saveState
};

export default sidebarStore; 