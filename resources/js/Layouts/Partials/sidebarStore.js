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
  // Initialize with saved state or empty object
  const state = reactive({
    ...loadSavedState(),
    // Ensure that important menus are always opened by default
    'sales-management': true,
    'system-requirement': true,
    'master-card': true
  });
  
  // Mobile sidebar state should always start as false
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

  // Find menu ID based on current path
  const getMenuId = (path, structure) => {
    if (!path) return null;
    
    for (const item of structure) {
      if (item.route === path) {
        return item.id;
      } 
      if (item.children) {
        const childId = getMenuId(path, item.children);
        if (childId) return childId;
      }
    }
    return null;
  };

  // Set parent menus to open state
  const openParentMenus = (currentPath, structure, parentIds = []) => {
    let found = false;
    
    for (const item of structure) {
      const currentIds = [...parentIds];
      
      if (item.id) {
        currentIds.push(item.id);
      }
      
      if (item.route === currentPath) {
        found = true;
        break;
      }
      
      if (item.children) {
        const childFound = openParentMenus(currentPath, item.children, currentIds);
        if (childFound) {
          found = true;
          // Open all parent menus
          currentIds.forEach(id => {
            state[id] = true;
          });
          break;
        }
      }
    }
    
    return found;
  };

  return {
    state,
    mobileOpen,
    isOpen,
    toggle,
    toggleMobile,
    getMenuId,
    openParentMenus
  };
};

// Create a singleton instance
const sidebarStore = useSidebarStore();
export default sidebarStore; 