import { reactive, watch } from 'vue';

const STATE_KEY = 'sidebarState';

// Load state from localStorage, handling migration from old format
const loadState = () => {
  const defaultState = { openMenus: {}, mobileOpen: false };
  try {
    const savedStateJSON = localStorage.getItem(STATE_KEY);
    if (savedStateJSON) {
      const savedState = JSON.parse(savedStateJSON);
      
      // Check for new structure: { openMenus: {}, mobileOpen: boolean }
      if (savedState && typeof savedState.openMenus === 'object' && savedState.openMenus !== null) {
        return {
          openMenus: savedState.openMenus,
          mobileOpen: savedState.mobileOpen || false,
        };
      }
      
      // Check for old structure (flat object) and migrate it
      if (savedState && typeof savedState === 'object' && savedState.openMenus === undefined) {
        console.warn('Migrating old sidebar state to new format.');
        return {
          openMenus: savedState,
          mobileOpen: false, // mobileOpen was not persisted in old format
        };
      }
    }
  } catch (e) {
    console.error("Failed to parse or migrate sidebar state from localStorage", e);
    // If migration fails, clear the invalid state
    localStorage.removeItem(STATE_KEY);
  }
  
  // Return default state if nothing is saved, parsing fails, or migration fails
  return defaultState;
};

const store = reactive({
  ...loadState(),

  isOpen(menuId) {
    return !!this.openMenus[menuId];
  },
  
  setOpen(menuId, isOpen) {
    this.openMenus[menuId] = isOpen;
  },

  toggle(menuId) {
    this.openMenus[menuId] = !this.openMenus[menuId];
  },
  
  toggleMobile() {
    this.mobileOpen = !this.mobileOpen;
  },

  closeMobile() {
    this.mobileOpen = false;
  },

  resetState() {
    this.openMenus = {};
    this.mobileOpen = false;
  }
});

// Watch for changes to openMenus and save to localStorage
watch(() => store.openMenus, (newOpenMenus) => {
  try {
    const stateToSave = {
      openMenus: newOpenMenus,
      mobileOpen: store.mobileOpen
    };
    localStorage.setItem(STATE_KEY, JSON.stringify(stateToSave));
  } catch (e) {
    console.error("Failed to save sidebar state to localStorage", e);
  }
}, { deep: true });

// Watch for changes to mobileOpen and save to localStorage
watch(() => store.mobileOpen, (newMobileOpen) => {
  try {
    const stateToSave = {
      openMenus: store.openMenus,
      mobileOpen: newMobileOpen
    };
    localStorage.setItem(STATE_KEY, JSON.stringify(stateToSave));
  } catch (e) {
    console.error("Failed to save sidebar mobile state to localStorage", e);
  }
});

export default store; 