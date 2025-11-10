# Reset SO, WO, and MSP Data on New MasterCard Creation

## Problem
Ketika membuat MasterCard baru setelah save MasterCard sebelumnya, data SO, WO, dan MSP di modal Setup MC PD masih terload dengan data dari pembuatan MC sebelumnya. Data tidak di-reset sehingga user harus menghapus manual.

## Solution
Menambahkan logic untuk me-reset data SO, WO, dan MSP ketika user membuat MasterCard baru.

## Changes Made

### 1. Frontend - update-mc.vue (`handleMcsInput` function)

**File:** `resources/js/Pages/sales-management/system-requirement/master-card/update-mc.vue`

**Added reset logic in `handleMcsInput` function (when user types new MCS#):**

```javascript
const handleMcsInput = () => {
    if (isProgrammaticUpdate.value) return;

    if (form.value.mcs && !selectedMcs.value) {
        recordMode.value = "new";
        form.value.mc_approval = "No";
        
        // ✅ Reset SO, WO, and MSP data for new MC
        soValues.value = ["", "", "", "", ""];
        woValues.value = ["", "", "", "", ""];
        selectedMcsFull.value = null;
        
        // ... rest of code
    } else if (!form.value.mcs) {
        // ✅ Reset when MCS is cleared
        soValues.value = ["", "", "", "", ""];
        woValues.value = ["", "", "", "", ""];
        selectedMcsFull.value = null;
    }
};
```

### 2. Frontend - update-mc.vue (`handleNextSetup` function)

**Added reset logic in `handleNextSetup` function (when user clicks "Next Setup"):**

```javascript
const handleNextSetup = () => {
    if (recordMode.value === "new" && !form.value.mc_model.trim()) {
        showErrorModal.value = true;
        return;
    }

    // ✅ Reset SO, WO, and MSP data for new MC
    if (recordMode.value === "new") {
        soValues.value = ["", "", "", "", ""];
        woValues.value = ["", "", "", "", ""];
        selectedMcsFull.value = null;
    }

    showSetupMcModal.value = true;
};
```

### 3. Frontend - update-mc.vue (`addNewRecord` function)

**Added reset logic in `addNewRecord` function:**

```javascript
const addNewRecord = () => {
    // Guard: AC must be selected first
    if (!form.value.ac) {
        toast.error("Please select Customer Account (AC#) first before creating a new Master Card");
        openCustomerAccountModal();
        return;
    }
    recordMode.value = "new";
    form.value.mc_approval = "No";
    recordSelected.value = true;
    showDetailedMcInfo.value = true;
    
    // ✅ Reset SO, WO, and MSP data for new MC
    soValues.value = ["", "", "", "", ""];
    woValues.value = ["", "", "", "", ""];
    
    // ✅ Clear selectedMcsFull to ensure fresh data
    selectedMcsFull.value = null;
};
```

**What this does:**
- Resets `soValues` to empty array `["", "", "", "", ""]`
- Resets `woValues` to empty array `["", "", "", "", ""]`
- Sets `selectedMcsFull` to `null` so `mcLoaded` prop becomes `null`

### 4. Frontend - UpdateMcModal.vue (watcher for mcLoaded)

**File:** `resources/js/Components/UpdateMcModal.vue`

**Added MSP reset logic in `mcLoaded` watcher:**

```javascript
watch(() => props.mcLoaded, (newValue) => {
    if (newValue) {
        hydratePdFromLoaded();
    } else {
        // ✅ Reset MSP data when creating new MC (mcLoaded is null)
        mspData.value = {};
    }
}, { immediate: true });
```

**What this does:**
- When `mcLoaded` is `null` (new MC), reset `mspData` to empty object `{}`
- This ensures MSP modal opens with empty data for new MC
- When `mcLoaded` has value (existing MC), load data normally

## Data Flow

### Creating New MasterCard:

1. **User clicks "New" or enters new MCS#**
   - `addNewRecord()` is called

2. **Reset SO/WO values:**
   ```javascript
   soValues.value = ["", "", "", "", ""]
   woValues.value = ["", "", "", "", ""]
   ```

3. **Clear loaded MC data:**
   ```javascript
   selectedMcsFull.value = null
   ```

4. **UpdateMcModal receives `mcLoaded = null`:**
   ```vue
   :mcLoaded="recordMode === 'existing' ? selectedMcsFull : null"
   ```

5. **Watcher detects `mcLoaded = null`:**
   ```javascript
   watch(() => props.mcLoaded, (newValue) => {
       if (!newValue) {
           mspData.value = {}  // ✅ Reset MSP data
       }
   })
   ```

6. **Result:**
   - SO fields: Empty ✅
   - WO fields: Empty ✅
   - MSP modal: Empty ✅

### Loading Existing MasterCard:

1. **User selects existing MC**
   - `selectedMcsFull.value` is populated with MC data

2. **UpdateMcModal receives `mcLoaded = selectedMcsFull`:**
   ```vue
   :mcLoaded="recordMode === 'existing' ? selectedMcsFull : null"
   ```

3. **Watcher detects `mcLoaded` has value:**
   ```javascript
   watch(() => props.mcLoaded, (newValue) => {
       if (newValue) {
           hydratePdFromLoaded()  // ✅ Load existing data
       }
   })
   ```

4. **MSP modal loads existing data:**
   ```javascript
   const openMspModal = () => {
       const loaded = props.mcLoaded || {};
       // Load MSP1-MSP12 from loaded data
   }
   ```

5. **Result:**
   - SO fields: Loaded from database ✅
   - WO fields: Loaded from database ✅
   - MSP modal: Loaded from database ✅

## Testing Steps

### Test 1: Create New MC After Saving Previous MC

1. **Create first MasterCard:**
   - Select customer AC
   - Enter MCS#
   - Click "Next Setup"
   - Fill SO values (e.g., "100", "200", "300", "400", "500")
   - Fill WO values (e.g., "10", "20", "30", "40", "50")
   - Click "MSP" button
   - Select machines and fill No/Up values
   - Save MSP modal
   - Save MasterCard

2. **Create second MasterCard (NEW):**
   - Clear MCS# field or enter new MCS#
   - Click "Next Setup"
   - **Expected:** SO fields should be empty
   - **Expected:** WO fields should be empty
   - Click "MSP" button
   - **Expected:** MSP modal should be empty (no machines selected)

### Test 2: Load Existing MC After Creating New MC

1. **Create new MasterCard:**
   - Enter new MCS#
   - Click "Next Setup"
   - Verify SO, WO, MSP are empty

2. **Load existing MasterCard:**
   - Click MCS# search button
   - Select an existing MC with data
   - Click "Next Setup"
   - **Expected:** SO fields should show saved values
   - **Expected:** WO fields should show saved values
   - Click "MSP" button
   - **Expected:** MSP modal should show saved machines

### Test 3: Switch Between New and Existing MC

1. **Load existing MC with data**
2. **Create new MC** (clear MCS# or enter new)
3. **Verify data is reset**
4. **Load existing MC again**
5. **Verify data is loaded correctly**

## Files Modified

1. ✅ `resources/js/Pages/sales-management/system-requirement/master-card/update-mc.vue`
   - Modified `addNewRecord()` function
   - Added SO/WO reset logic
   - Clear `selectedMcsFull` for new MC

2. ✅ `resources/js/Components/UpdateMcModal.vue`
   - Modified `mcLoaded` watcher
   - Added MSP reset logic when `mcLoaded` is null

## Benefits

✅ **Clean slate for new MC:** Users start with empty fields
✅ **No manual clearing needed:** Automatic reset saves time
✅ **Prevents data confusion:** Old data won't carry over to new MC
✅ **Maintains existing MC data:** Loading existing MC still works correctly
✅ **Better UX:** Intuitive behavior matches user expectations

## Status

- [x] Reset SO values on new MC
- [x] Reset WO values on new MC
- [x] Reset MSP data on new MC
- [x] Clear selectedMcsFull on new MC
- [x] Watcher for mcLoaded prop
- [x] Tested with new MC creation
- [x] Tested with existing MC loading
- [x] Documentation complete
