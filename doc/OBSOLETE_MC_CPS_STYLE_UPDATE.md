# Obsolete & Reactive MC - CPS Style Update

## 📋 Overview
Updated menu **Obsolete & Reactive MC** to match CPS Enterprise 2020 style dengan layout 2 kolom sederhana.

---

## 🎨 UI Changes - Matching CPS Enterprise

### **Before (Complex Multi-Column Layout):**
```
┌─────────────────────────────────────────────┐
│  3 Columns Layout                           │
├─────────────────────────────────────────────┤
│  Left (Form):                               │
│  - AC# with lookup                          │
│  - MCS From/To range                        │
│  - Product Code                             │
│  - Action radio buttons                     │
│  - Reason textarea                          │
│  - Process button                           │
│                                             │
│  Right (Info Panel):                        │
│  - Description                              │
│  - Instructions                             │
└─────────────────────────────────────────────┘
```

### **After (CPS Style - 2 Columns):**
```
┌─────────────────────────────────────────────┐
│  2 Columns Layout                           │
├─────────────────┬───────────────────────────┤
│  Left Panel:    │  Right Panel:             │
│  ┌───────────┐  │  ┌───────────────────┐   │
│  │ AC#       │  │  │ Description       │   │
│  │ [Input] 📁│  │  │ Panel             │   │
│  └───────────┘  │  │                   │   │
│                 │  │ • Tentukan        │   │
│  ┌───────────┐  │  │   Kriteria        │   │
│  │ MCS#      │  │  │ • Pilih Aksi      │   │
│  │ [Input] 📁│  │  │ • Tambahkan       │   │
│  └───────────┘  │  │   Alasan          │   │
│                 │  └───────────────────┘   │
│  ┌─────────────┐│                          │
│  │ [Obsolete] ││                          │
│  │ [Reactive] ││                          │
│  └─────────────┘│                          │
└─────────────────┴───────────────────────────┘
```

---

## 🔄 Key Changes Made

### 1. **Layout Simplification**
**Changed from:**
```vue
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
  <!-- Left Column (2/3 width) -->
  <!-- Right Column (1/3 width) -->
</div>
```

**Changed to:**
```vue
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
  <!-- Left Column (1/2 width) -->
  <!-- Right Column (1/2 width) -->
</div>
```

### 2. **Form Fields Simplification**

**Removed:**
- ❌ Product Code field
- ❌ MCS "To" range field (only kept "From")
- ❌ Action radio buttons (Obsolete/Reactive)
- ❌ Reason textarea in main form
- ❌ Process button in main form

**Kept:**
- ✅ AC# with browse button
- ✅ MCS# with browse button
- ✅ Two action buttons: **Obsolete** and **Reactive**

### 3. **CPS-Style Header**
```vue
<h3>Selected Master Card</h3>  <!-- CPS terminology -->
```

### 4. **Input Field Styling (CPS Style)**
```vue
<!-- Clean, simple input with browse button -->
<div class="flex items-stretch border border-gray-300 rounded-md">
    <input 
        type="text" 
        placeholder="Enter Customer Account"
        class="flex-1 px-4 py-2.5 text-sm"
    >
    <button class="px-4 py-2.5 bg-indigo-500 text-white">
        <i class="fas fa-folder-open"></i>  <!-- CPS uses folder icon -->
    </button>
</div>
```

### 5. **Action Buttons (Bottom Toolbar)**
```vue
<div class="flex gap-3">
    <button @click="openObsoleteModal" class="flex-1 bg-red-500">
        <i class="fas fa-ban"></i> Obsolete
    </button>
    <button @click="openReactiveModal" class="flex-1 bg-green-500">
        <i class="fas fa-check-circle"></i> Reactive
    </button>
</div>
```

---

## 🔔 Modal Workflow (CPS Style)

### **New Flow:**
```
1. User fills AC# or MCS#
2. User clicks "Obsolete" or "Reactive" button
3. Modal pops up asking for reason
4. Modal shows criteria summary
5. User enters reason (mandatory)
6. User clicks action button to confirm
7. System processes and shows result
```

### **Reason Modal Features:**
```vue
<!-- Dynamic header based on action -->
<div :class="currentAction === 'obsolete' ? 'bg-red-50' : 'bg-green-50'">
    <h3>{{ currentAction === 'obsolete' ? 'Obsolete' : 'Reactive' }} Master Card</h3>
</div>

<!-- Criteria Display -->
<div class="bg-gray-50 p-3">
    <p>Criteria:</p>
    <div v-if="form.ac">AC#: {{ form.ac }}</div>
    <div v-if="form.mcs_from">MCS#: {{ form.mcs_from }}</div>
</div>

<!-- Reason Input -->
<textarea 
    v-model="reasonInput" 
    placeholder="Enter the reason for this action (mandatory)"
    rows="4"
></textarea>

<!-- Action Buttons -->
<button @click="processAction">
    {{ currentAction === 'obsolete' ? 'Obsolete' : 'Reactive' }}
</button>
```

---

## 📊 JavaScript Functions Added

### 1. **onAcInput()**
```javascript
const onAcInput = () => {
    // Auto-fetch MCs when AC# changes
    if (form.value.ac && form.value.ac.length >= 3) {
        fetchMasterCards();
    }
};
```

### 2. **openObsoleteModal()**
```javascript
const openObsoleteModal = () => {
    // Validate at least AC# or MCS# is filled
    if (!form.value.ac && !form.value.mcs_from) {
        showToast('Validation Error', 'Please fill AC# or MCS# field first.', 'error');
        return;
    }
    
    currentAction.value = 'obsolete';
    reasonInput.value = '';
    showReasonModal.value = true;
};
```

### 3. **openReactiveModal()**
```javascript
const openReactiveModal = () => {
    // Validate at least AC# or MCS# is filled
    if (!form.value.ac && !form.value.mcs_from) {
        showToast('Validation Error', 'Please fill AC# or MCS# field first.', 'error');
        return;
    }
    
    currentAction.value = 'reactivate';
    reasonInput.value = '';
    showReasonModal.value = true;
};
```

### 4. **processAction()**
```javascript
const processAction = async () => {
    if (!reasonInput.value || reasonInput.value.trim() === '') {
        showToast('Validation Error', 'Reason is mandatory.', 'error');
        return;
    }

    const payload = {
        ac: form.value.ac || null,
        mcs_from: form.value.mcs_from || null,
        reason: reasonInput.value.trim(),
    };

    const endpoint = currentAction.value === 'obsolete' 
        ? '/api/mc/bulk-obsolete' 
        : '/api/mc/bulk-reactive';

    const response = await axios.post(endpoint, payload);
    
    if (response.data.success) {
        showToast('Success', response.data.message, 'success');
        showReasonModal.value = false;
        // Clear form
        form.value.ac = '';
        form.value.mcs_from = '';
        reasonInput.value = '';
    }
};
```

---

## 🎯 User Experience Flow

### **Obsolete Workflow:**
```
1. User: Enter AC# "C001"
2. System: Auto-fetch MCs (optional preview)
3. User: Click "Obsolete" button
4. System: Show modal with criteria summary
5. User: Enter reason "Phase out old design"
6. User: Click "Obsolete" in modal
7. System: Process → Update MC.STS = 'Obsolete'
8. System: Show success message with count
9. System: Clear form
```

### **Reactive Workflow:**
```
1. User: Enter MCS# "MC001"
2. User: Click "Reactive" button
3. System: Show modal with criteria summary
4. User: Enter reason "Reactivate for new order"
5. User: Click "Reactive" in modal
6. System: Process → Update MC.STS = 'Active'
7. System: Show success message with count
8. System: Clear form
```

---

## ✅ CPS Enterprise 2020 Compliance

### **Matching Features:**
- ✅ **2-column layout** (form left, info right)
- ✅ **Simple field structure** (AC# and MCS# only)
- ✅ **Browse buttons** with folder icons
- ✅ **Bottom action buttons** (not inline)
- ✅ **Modal for reason input** (popup workflow)
- ✅ **Criteria summary** before action
- ✅ **Mandatory reason** validation
- ✅ **Clear visual feedback** (red for obsolete, green for reactive)
- ✅ **Simple, clean UI** (no clutter)

### **Visual Consistency:**
- Clean borders without excessive shadows
- Consistent spacing and padding
- Professional color scheme (indigo/purple theme)
- Proper button hierarchy
- Clear action indicators

---

## 🔄 API Integration

### **Endpoints Used:**
```javascript
// Fetch MCs by customer
GET /api/mc/by-customer/{customerCode}

// Obsolete action
POST /api/mc/bulk-obsolete
{
    "ac": "C001",
    "mcs_from": "MC001",
    "reason": "Phase out old design"
}

// Reactive action
POST /api/mc/bulk-reactive
{
    "ac": "C001",
    "mcs_from": "MC001",
    "reason": "Reactivate for new order"
}
```

### **Response Format:**
```json
{
    "success": true,
    "message": "Successfully obsoleted 5 master card(s).",
    "count": 5
}
```

---

## 📝 Validation Rules

### **Before Opening Modal:**
- ✅ At least AC# OR MCS# must be filled
- ✅ Cannot proceed with empty criteria

### **In Modal:**
- ✅ Reason is mandatory (cannot be empty)
- ✅ Reason must be trimmed (no whitespace-only)

### **API Level:**
- ✅ Validates filter criteria
- ✅ Only updates Active → Obsolete (or vice versa)
- ✅ Returns count of affected records
- ✅ Logs action with user, timestamp, reason

---

## 🎨 Color Scheme

### **Obsolete (Red Theme):**
- Header background: `bg-red-50`
- Icon background: `bg-red-500`
- Button gradient: `from-red-500 to-red-600`
- Hover: `from-red-600 to-red-700`

### **Reactive (Green Theme):**
- Header background: `bg-green-50`
- Icon background: `bg-green-500`
- Button gradient: `from-green-500 to-green-600`
- Hover: `from-green-600 to-green-700`

### **Form Elements:**
- Primary color: Indigo (`indigo-500`, `indigo-600`)
- Focus ring: `ring-indigo-500`
- Borders: `border-gray-300`
- Background: `bg-white`, `bg-gray-50`

---

## 📦 Component Structure

```
obsolete-reactive-mc.vue
├── Template
│   ├── Header Section (gradient banner)
│   ├── Main Content (2 columns)
│   │   ├── Left Column (Form)
│   │   │   ├── AC# input with browse
│   │   │   ├── MCS# input with browse
│   │   │   └── Action buttons
│   │   └── Right Column (Info Panel)
│   ├── Modals
│   │   ├── CustomerAccountModal
│   │   ├── MasterCardSearchSelectModal
│   │   ├── MasterCardZoomModal
│   │   └── Reason Input Modal (NEW)
│   └── AppLayout wrapper
├── Script
│   ├── Reactive refs
│   ├── Modal handlers
│   ├── Action handlers (NEW)
│   └── API calls
└── Styles
    └── Custom classes
```

---

## ✨ Benefits of New Design

### **User Experience:**
1. **Simpler Interface** - Less fields, clearer purpose
2. **Clear Actions** - Two big buttons, obvious choices
3. **Guided Workflow** - Modal ensures reason is provided
4. **Visual Feedback** - Color coding (red/green) for actions
5. **Quick Entry** - Minimal required fields

### **Technical:**
1. **Cleaner Code** - Less conditional logic in template
2. **Better Validation** - Modal-based validation flow
3. **Modular** - Reason modal can be reused
4. **Maintainable** - Simpler component structure

### **Business:**
1. **Matches CPS** - Familiar to existing users
2. **Audit Trail** - Reason is always captured
3. **Error Prevention** - Validation before action
4. **Flexibility** - Can obsolete by AC# or specific MCS#

---

## 🧪 Testing Scenarios

### **Test 1: Obsolete by AC#**
```
1. Enter AC#: "C001"
2. Click "Obsolete"
3. Modal appears with criteria
4. Enter reason: "Customer closed"
5. Click "Obsolete" in modal
6. Verify: All MCs for C001 are obsoleted
7. Verify: Success message shows count
```

### **Test 2: Reactive by MCS#**
```
1. Enter MCS#: "MC001"
2. Click "Reactive"
3. Modal appears with criteria
4. Enter reason: "Customer request"
5. Click "Reactive" in modal
6. Verify: MC001 is reactivated
7. Verify: Success message shows count
```

### **Test 3: Validation**
```
1. Click "Obsolete" without any input
2. Verify: Error message appears
3. Enter AC#: "C001"
4. Click "Obsolete"
5. Modal appears
6. Click "Obsolete" without reason
7. Verify: Error message in modal
```

---

## 📄 Files Modified

### **Vue Component:**
- `resources/js/Pages/sales-management/system-requirement/master-card/obsolete-reactive-mc.vue`

### **Changes Summary:**
- ✅ Changed layout from 3 columns to 2 columns
- ✅ Simplified form to AC# and MCS# only
- ✅ Removed inline action selection
- ✅ Added modal workflow for reason input
- ✅ Added `onAcInput`, `openObsoleteModal`, `openReactiveModal`, `processAction` functions
- ✅ Updated styling to match CPS design
- ✅ Improved user experience and validation

---

**Date:** 2025-01-03  
**Version:** 3.0 (CPS Style)  
**Status:** ✅ Complete - Matches CPS Enterprise 2020
