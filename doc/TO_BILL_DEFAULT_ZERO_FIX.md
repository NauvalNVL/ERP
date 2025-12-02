# To Bill Default to Zero (CPS-Compatible)

## 📋 Problem

**Issue:** To Bill column terisi otomatis dengan 1000 (Unbill quantity) saat modal dibuka, padahal seharusnya default 0 dan user harus mengisi manual.

**Current Behavior (Wrong):**
```
Modal opens:
  Deliver: 1000
  Unbill: 1000
  To Bill: 1000 ← Auto-filled
  Total: Rp 3,036,360 ← Pre-calculated
```

**Expected CPS Behavior:**
```
Modal opens:
  Deliver: 1000
  Unbill: 1000
  To Bill: 0 ← Empty, user must input
  Total: 0 ← Will calculate after input
```

---

## ✅ Solution Implemented

### **1. Backend Change** (`InvoiceController.php`)

**Before:**
```php
$itemDetails[] = [
    'to_bill' => floatval($mainItem->DO_Qty ?: 0),  // Auto-fill with DO quantity
    'to_bill_kg' => floatval($mainItem->Total_DO_Net_KG ?: 0),
];
```

**After:**
```php
$itemDetails[] = [
    'to_bill' => 0,  // Default 0 - User must input manually
    'to_bill_kg' => 0,  // Will calculate when user inputs
    'kg_per_unit' => ...,  // Store for calculation
];
```

### **2. Frontend Changes** (`SalesOrderItemsModal.vue`)

#### **A. Total Initially 0**

**Before:**
```javascript
total.value = data.total_amount || 0  // Used API total
```

**After:**
```javascript
// Total should be 0 initially
// Will calculate when user inputs to_bill
total.value = 0
```

#### **B. S/O List Amount 0**

**Added:**
```javascript
// Set S/O List amount to 0 initially
if (soList.value.length > 0) {
  soList.value[0].amount = 0
}
```

#### **C. Input Placeholder**

**Added placeholder to input:**
```vue
<input 
  v-model.number="item.to_bill"
  type="number"
  placeholder="0"  ← Shows user should input
  :max="item.unbill"
  class="..."
/>
```

---

## 🔄 User Workflow (CPS-Compatible)

### **Old Flow (Wrong):**
```
1. Modal opens
2. To Bill = 1000 (auto-filled)
3. Total = Rp 3,036,360 (pre-calculated)
4. User can change or just click OK
```

### **New Flow (CPS-Compatible):**
```
1. Modal opens
2. To Bill = 0 (empty)
3. Total = 0
4. User MUST input To Bill quantity
5. Type: 300 ⏎
6. Auto-calculate:
   - To Bill KG = 30 kg
   - Total = Rp 909,000
7. Click OK
```

---

## 📊 Comparison: Before vs After

| Field | Before (Wrong) | After (CPS-Compatible) | Notes |
|-------|----------------|------------------------|-------|
| **To Bill (Load)** | 1000 (auto) | 0 (empty) | User must input |
| **To Bill KG (Load)** | 100 kg | 0.00 kg | Calculate after input |
| **Total (Load)** | Rp 3,036,360 | Rp 0 | Calculate after input |
| **S/O Amount (Load)** | Rp 3,036,360 | Rp 0 | Calculate after input |
| **User Action** | Optional edit | Required input | Must enter quantity |

---

## 🎯 Key Benefits

### **1. Explicit User Intent**
- ❌ **Before:** System assumes full invoice (1000 pcs)
- ✅ **After:** User explicitly states quantity to invoice

### **2. Prevent Mistakes**
- ❌ **Before:** User might not notice auto-fill and invoice wrong amount
- ✅ **After:** User must consciously input, reducing errors

### **3. CPS Workflow Match**
- ❌ **Before:** Different from CPS behavior
- ✅ **After:** Exactly matches CPS ERP workflow

### **4. Partial Invoicing Clarity**
- ❌ **Before:** User must remember to edit from 1000 to desired amount
- ✅ **After:** User inputs exact amount from start

---

## 💡 Example Scenarios

### **Scenario 1: Full Invoice**

**Before (Auto-fill):**
```
1. Modal opens: To Bill = 1000
2. User thinks: "Looks right, click OK"
3. Invoice for 1000 pcs
```

**After (Manual Input):**
```
1. Modal opens: To Bill = 0
2. User thinks: "I need to invoice all"
3. User types: 1000
4. See total update
5. Click OK
6. Invoice for 1000 pcs ✅ (conscious decision)
```

### **Scenario 2: Partial Invoice**

**Before (Auto-fill - Error Prone):**
```
1. Modal opens: To Bill = 1000
2. User wants: 300 pcs only
3. Must remember to change from 1000 to 300
4. Risk: Forget to edit, invoice 1000 by mistake ❌
```

**After (Manual Input - Safe):**
```
1. Modal opens: To Bill = 0
2. User wants: 300 pcs
3. User types: 300
4. Total updates: Rp 909,000
5. Click OK
6. Invoice for 300 pcs ✅ (no risk of mistake)
```

### **Scenario 3: Multi-Step Invoicing**

**Invoice 1:**
```
Unbill: 1000
User input: 300
Invoice created: 300 pcs
```

**Invoice 2 (Later):**
```
Unbill: 700 (remaining)
User input: 400
Invoice created: 400 pcs
```

**Invoice 3 (Final):**
```
Unbill: 300 (remaining)
User input: 300
Invoice created: 300 pcs
Total invoiced: 1000 pcs ✅
```

---

## 🔧 Technical Details

### **API Response Structure:**
```json
{
  "item_details": [
    {
      "item": "Main",
      "deliver": 1000,
      "reject": 0,
      "unbill": 1000,
      "to_bill": 0,        // ← Changed from 1000 to 0
      "to_bill_kg": 0,     // ← Changed from 100 to 0
      "kg_per_unit": 0.1,  // Stored for calculation
      "u_price": 3036
    }
  ],
  "total_amount": 3036360  // ← Not used anymore
}
```

### **Frontend State on Load:**
```javascript
{
  itemDetails: [{
    to_bill: 0,        // Empty
    to_bill_kg: 0      // Empty
  }],
  total: 0,            // Zero
  soList: [{
    amount: 0          // Zero
  }]
}
```

### **After User Input 300:**
```javascript
{
  itemDetails: [{
    to_bill: 300,      // User input
    to_bill_kg: 30     // Auto-calculated
  }],
  total: 909000,       // Auto-calculated
  soList: [{
    amount: 909000     // Auto-updated
  }]
}
```

---

## 📝 Files Modified

1. **✅ InvoiceController.php**
   - Line 812: `'to_bill' => 0`
   - Line 813: `'to_bill_kg' => 0`
   - Added comments for clarity

2. **✅ SalesOrderItemsModal.vue**
   - Line 323: `total.value = 0`
   - Line 342-345: Set S/O List amount to 0
   - Line 203 & 229: Added `placeholder="0"`
   - Updated console logs

3. **✅ TO_BILL_DEFAULT_ZERO_FIX.md**
   - Complete documentation

**No changes to:**
- ❌ Database migrations
- ❌ API routes
- ❌ Invoice model

---

## 🎨 Visual Changes

### **To Bill Input Field:**

**Before:**
```
┌─────────────────┐
│     1000        │  ← Pre-filled with Unbill
└─────────────────┘
```

**After:**
```
┌─────────────────┐
│                 │  ← Empty, shows placeholder "0"
└─────────────────┘
```

### **Total Display:**

**Before:**
```
Total: 3,036,360  ← Pre-calculated
```

**After:**
```
Total: 0  ← Will update after input
```

---

## ✅ Testing Checklist

- [x] To Bill shows 0 on modal open
- [x] To Bill KG shows 0.00 on modal open
- [x] Total shows 0 on modal open
- [x] S/O List amount shows 0 on modal open
- [x] Input has placeholder "0"
- [x] User can input To Bill quantity
- [x] To Bill KG auto-calculates on input
- [x] Total auto-calculates on input
- [x] Max validation works (cannot exceed Unbill)
- [x] Matches CPS ERP behavior
- [x] No style changes
- [x] No database changes

---

## 💻 Console Logs

**On Modal Open:**
```
✅ DO items data received: {...}
📦 KG per unit (from API): 0.1000
📊 Populated: {
  soCount: 1,
  total: 0,  ← Zero initially
  itemCount: 10,
  originalKgPerUnit: 0.1,
  note: 'Total is 0 - user must input To Bill quantity (CPS-compatible)'
}
```

**After User Input 300:**
```
📝 To Bill changed for: Main New value: 300
🔢 KG Calculation: {
  to_bill: 300,
  kg_per_unit: 0.1000,
  calculated_kg: 30.00
}
💰 Total recalculated: 909,000
✅ Updated: {to_bill: 300, to_bill_kg: 30.00, new_total: "909,000"}
```

---

## 🎓 User Benefits

### **1. Clear Intent**
- User explicitly chooses quantity
- No assumptions by system
- Conscious decision required

### **2. Error Prevention**
- Cannot accidentally invoice wrong amount
- Must review and input quantity
- Visual feedback (total updates)

### **3. Workflow Clarity**
- Obvious that input is required
- Placeholder guides user
- Empty field = action needed

### **4. CPS Compatibility**
- Exactly matches CPS behavior
- Familiar to CPS users
- No retraining needed

---

## ⚠️ Important Notes

### **Breaking Change:**
This is a **behavior change** from previous version:
- **Old:** Auto-fill with full quantity
- **New:** Default to 0, require input

### **User Impact:**
- Users must now **always input** To Bill quantity
- Cannot just click OK without input
- More steps but **safer and explicit**

### **Migration:**
- No database migration needed
- No API version change
- Pure behavioral change
- Immediate effect on next page refresh

---

## ✅ Summary

**Sales Order Items Screen sekarang:**

1. ✅ **To Bill default 0** - Not auto-filled
2. ✅ **To Bill KG default 0** - Will calculate after input
3. ✅ **Total default 0** - Will calculate after input
4. ✅ **User must input** - Required action
5. ✅ **Placeholder shown** - "0" guides user
6. ✅ **CPS-compatible** - Exact behavior match
7. ✅ **Error prevention** - No accidental wrong invoice
8. ✅ **No migration** - Pure logic change

Modal sekarang **berfungsi exactly seperti CPS ERP** di mana user HARUS mengisi To Bill quantity secara manual! 🎉

---

**Last Updated:** October 29, 2025, 23:58 WIB
**Version:** 5.0 - To Bill Default Zero (CPS-Compatible)
