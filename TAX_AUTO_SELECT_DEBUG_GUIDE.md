# Tax Auto-Select Debug Guide

## 🐛 Issue: Tax Group Tidak Terisi Otomatis

**Screenshot Evidence:**
- Current: Tax Group shows "Select Tax Group..." ❌
- Expected (CPS): Tax Group shows "PPN11" ✅

---

## 🔍 Enhanced Debugging System

### **New Watchers Implemented:**

```javascript
// 1. Watch taxCode prop (immediate)
watch(() => props.taxCode, (newVal, oldVal) => {
  console.log('👁️ Tax Code prop changed:', { old, new })
  // Validates and auto-selects if taxOptions available
})

// 2. Watch taxOptions (immediate, deep)
watch(() => props.taxOptions, (newOptions, oldOptions) => {
  console.log('📊 Tax Options changed:', { oldCount, newCount, options })
  // Auto-selects tax if taxCode set but selectedTaxGroup empty
  // Fixes race condition when taxOptions loads after taxCode
})

// 3. Watch modal open
watch(() => props.open, (isOpen) => {
  console.log('📋 Final Screen opened with:', {...})
  // Final fallback for auto-selection
})
```

---

## 📋 Debug Checklist

### **Step 1: Open Browser Console (F12)**

Press `F12` to open DevTools, go to Console tab.

### **Step 2: Follow the Flow**

#### **A. Check Sales Tax Screen Selection**

When user selects tax in Check Sales Tax Screen:

```javascript
// Expected console logs:
✅ Tax confirmed in Check Sales Tax Screen: {code: "PPN11", name: "PPN11", rate: 11}
💼 Tax Index No set to: PPN11
```

**If not shown:**
- ❌ User didn't select tax properly
- ❌ Check Sales Tax modal not emitting
- **Action:** Check `CheckSalesTaxModal.vue` emit logic

#### **B. Check Data Passed to Final Screen**

Before Final Screen opens:

```javascript
// Expected console logs:
📤 Opening Final Screen with: {
  totalAmount: 3036360,
  taxIndexNo: "PPN11",           ← CRITICAL: Must have value!
  taxOptionsCount: 2,
  taxOptions: [
    {code: "PPN11", rate: 11},
    {code: "PPN0", rate: 0}
  ]
}
```

**If taxIndexNo is empty:**
- ❌ Tax not selected in Check Sales Tax Screen
- ❌ `taxIndexNo.value` not set in parent
- **Action:** User must go back and select tax

**If taxOptionsCount is 0:**
- ❌ Tax options not fetched
- ❌ API call failed
- **Action:** Check `fetchTaxOptions()` in parent

#### **C. Check Final Screen Props Reception**

When Final Screen opens:

```javascript
// Expected console logs:
📋 Final Screen opened with: {
  doNumber: "2025-10-00001",
  totalAmount: 3036360,
  taxCode: "PPN11",              ← CRITICAL: Must match taxIndexNo!
  taxOptions: [{...}],
  formatted: "3,036,360.00"
}
```

**If taxCode is empty/undefined:**
- ❌ Props binding issue in parent template
- **Action:** Check `:taxCode="taxIndexNo"` in parent component

**If taxOptions is []:**
- ❌ Props not passed correctly
- **Action:** Check `:taxOptions="taxOptions"` binding

#### **D. Check Tax Code Prop Watcher**

After props received:

```javascript
// Expected console logs:
👁️ Tax Code prop changed: {old: "", new: "PPN11"}
🔍 Validating tax code: PPN11
```

**Scenario 1: Tax Options Available**
```javascript
✅ Tax auto-selected from prop watcher: PPN11
🧮 Calculating tax...
📊 Found tax option: {code: "PPN11", rate: 11}
✅ Tax calculated: {formula: "3036360 × 11% = 333999.6"}
```

**Scenario 2: Tax Options NOT Available Yet (Race Condition)**
```javascript
⚠️ Tax Options not available yet, will wait...
```
→ This is OK! The taxOptions watcher will handle it.

#### **E. Check Tax Options Watcher**

When taxOptions loads:

```javascript
// Expected console logs:
📊 Tax Options changed: {
  oldCount: 0,
  newCount: 2,
  options: [
    {code: "PPN11", rate: 11},
    {code: "PPN0", rate: 0}
  ]
}
```

**If taxCode set but not selected yet:**
```javascript
🎯 Tax Options now available, attempting auto-select: PPN11
✅ Tax auto-selected from taxOptions watcher: PPN11
🧮 Calculating tax...
✅ Tax calculated: {formula: "..."}
```

**If tax not found:**
```javascript
⚠️ Tax code not found after taxOptions loaded: {
  taxCode: "PPN11",
  availableOptions: ["PPN0", "VAT"]    ← PPN11 not in list!
}
```
→ **Problem:** Tax code mismatch or wrong tax in options

#### **F. Final Verification**

After all watchers:

```javascript
// Dropdown should show:
Tax Group: PPN11 (11%) ✅

// Tax amount should show:
PPN11: 333,999.60 ✅

// Net amount should show:
Net Amount: 3,370,359.60 ✅
```

---

## ⚠️ Common Issues & Solutions

### **Issue 1: Tax Options Empty**

**Console shows:**
```javascript
📊 Tax Options changed: {newCount: 0}
⚠️ Tax Options not available yet
```

**Cause:**
- `fetchTaxOptions()` failed
- API endpoint not responding
- Network error

**Solution:**
```javascript
// Check in parent component:
async function fetchTaxOptions(){
  try {
    const res = await fetch('/api/invoices/sales-tax-options')
    if (res.ok) {
      taxOptions.value = await res.json()
      console.log('✅ Tax options loaded:', taxOptions.value)
    } else {
      console.error('❌ Failed to fetch tax options:', res.status)
    }
  } catch (e) {
    console.error('❌ Error fetching tax options:', e)
  }
}
```

**Action:**
1. Check network tab in DevTools
2. Verify API endpoint exists
3. Check server logs

### **Issue 2: Tax Code Not Set**

**Console shows:**
```javascript
📤 Opening Final Screen with: {
  taxIndexNo: "",               ← Empty!
  ...
}
```

**Cause:**
- User didn't select tax in Check Sales Tax Screen
- Tax selection not confirmed
- `onTaxConfirmed()` not called

**Solution:**
User must:
1. Open Check Sales Tax Screen
2. Click on a tax row to select
3. Click "OK" button
4. Check console for confirmation

### **Issue 3: Props Not Bound**

**Console shows:**
```javascript
📋 Final Screen opened with: {
  taxCode: undefined,           ← Should be "PPN11"!
  ...
}
```

**Cause:**
Props binding issue in parent template.

**Solution:**
Check parent component template:
```vue
<FinalScreenModal
  :open="finalTaxModalOpen"
  :totalAmount="totalAmount"
  :taxCode="taxIndexNo"          ← CRITICAL: Must bind taxIndexNo!
  :taxOptions="taxOptions"       ← CRITICAL: Must bind taxOptions!
  ...
/>
```

### **Issue 4: Tax Code Mismatch**

**Console shows:**
```javascript
⚠️ Tax code not found in options: {
  taxCode: "PPN11",
  availableOptions: ["ppn11", "PPN0"]
}
```

**Cause:**
Case sensitivity or format mismatch.

**Solution:**
Ensure exact match:
- Database: `PPN11`
- Check Sales Tax: `PPN11`
- Tax Options API: `PPN11`

All must use same case and format!

### **Issue 5: Race Condition**

**Console shows:**
```javascript
👁️ Tax Code prop changed: {new: "PPN11"}
⚠️ Tax Options not available yet, will wait...

// 500ms later...
📊 Tax Options changed: {newCount: 2}
✅ Tax auto-selected from taxOptions watcher: PPN11
```

**Cause:**
Tax options loaded after taxCode prop set (normal behavior).

**Solution:**
Already handled! The `taxOptions` watcher will auto-select when options become available. This is expected and working correctly.

---

## ✅ Expected Console Output (Success)

```javascript
// 1. User selects tax in Check Sales Tax Screen
✅ Tax confirmed in Check Sales Tax Screen: {code: "PPN11", rate: 11}
💼 Tax Index No set to: PPN11

// 2. User completes Sales Order Items
💰 Total Amount set to: 3036360
📤 Opening Final Screen with: {
  totalAmount: 3036360,
  taxIndexNo: "PPN11",
  taxOptionsCount: 2
}

// 3. Final Screen opens
📋 Final Screen opened with: {taxCode: "PPN11", taxOptions: [...]}

// 4. Tax Code watcher triggers
👁️ Tax Code prop changed: {old: "", new: "PPN11"}
🔍 Validating tax code: PPN11

// 5a. If taxOptions already available:
✅ Tax auto-selected from prop watcher: PPN11

// 5b. If taxOptions not available yet:
⚠️ Tax Options not available yet, will wait...
📊 Tax Options changed: {newCount: 2}
🎯 Tax Options now available, attempting auto-select: PPN11
✅ Tax auto-selected from taxOptions watcher: PPN11

// 6. Calculate tax
🧮 Calculating tax... {selectedTaxGroup: "PPN11", totalAmount: 3036360}
📊 Found tax option: {code: "PPN11", rate: 11}
✅ Tax calculated: {
  taxCode: "PPN11",
  taxRate: 11,
  totalAmount: 3036360,
  taxAmount: 333999.6,
  formula: "3036360 × 11% = 333999.6"
}

// 7. Display updates
Tax Group: PPN11 (11%) ✅
PPN11: 333,999.60 ✅
Net Amount: 3,370,359.60 ✅
```

---

## 🔧 Quick Fix Guide

### **If Tax Still Not Auto-Selecting:**

1. **Open Console** - Press F12
2. **Clear Console** - Click trash icon
3. **Follow the flow:**
   - Select customer
   - Select tax in Check Sales Tax Screen
   - Look for: `✅ Tax confirmed` log
   - Select D/O and input To Bill
   - Look for: `📤 Opening Final Screen` log
   - Final Screen opens
   - Look for: `✅ Tax auto-selected` log

4. **Find the error:**
   - If you see `⚠️` warning, that's the issue
   - Read the warning message
   - Follow the solution above

5. **Common quick fixes:**
   ```javascript
   // Issue: taxIndexNo empty
   → Select tax in Check Sales Tax Screen first!
   
   // Issue: taxOptions empty
   → Check API endpoint /api/invoices/sales-tax-options
   
   // Issue: Tax not found in options
   → Check database tax codes match exactly
   ```

---

## 📝 Files Modified

### **1. FinalScreen.vue**

**Lines 137-162:** Enhanced taxCode watcher
- Added validation
- Better logging
- Handles missing taxOptions

**Lines 221-254:** Enhanced taxOptions watcher
- Auto-selects when options load
- Fixes race condition
- Comprehensive logging

### **2. TAX_AUTO_SELECT_DEBUG_GUIDE.md**

**New file** - Complete debugging guide

---

## ✅ Summary

**Enhanced Auto-Selection System:**

1. ✅ **Multiple watchers** - taxCode, taxOptions, open
2. ✅ **Race condition handled** - taxOptions watcher auto-selects
3. ✅ **Comprehensive logging** - Every step tracked
4. ✅ **Error messages** - Clear warnings when issues occur
5. ✅ **Validation** - Checks existence before selecting

**Debugging Made Easy:**

1. ✅ **Console logs** show exactly what's happening
2. ✅ **Warning messages** point to specific issues
3. ✅ **Step-by-step** guide for troubleshooting
4. ✅ **Common issues** with solutions provided

Tax auto-selection sekarang **robust dan fully debuggable**! 🎉

---

**Last Updated:** October 30, 2025, 13:14 WIB  
**Version:** 2.0 - Enhanced with Race Condition Fix & Debug System
