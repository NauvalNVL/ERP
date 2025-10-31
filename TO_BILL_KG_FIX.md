# To Bill KG Calculation Fix

## 📋 Problem

**Issue:** To Bill KG column menampilkan 0.00 padahal seharusnya ada nilai KG yang sesuai dengan quantity.

**Root Causes:**
1. Data `Total_DO_Net_KG` di database mungkin 0 atau null
2. Calculation formula tidak menggunakan data yang tepat
3. Tidak ada fallback mechanism jika data KG tidak tersedia

---

## ✅ Solutions Implemented

### **1. Backend Enhancement** (`InvoiceController.php`)

#### **A. Enhanced Query - Add KG Fields**

**Added fields to query:**
```php
$doRecords = DB::table('DO')
    ->select([
        // ... existing fields
        'Total_DO_Net_KG',      // Total net weight
        'Total_DO_Gross_KG',    // Total gross weight
        'MC_Net_Kg_Per_Pcs',    // KG per piece from MC
        'MC_Gross_Kg_Per_Pcs',  // Gross KG per piece
    ])
    ->get();
```

#### **B. Smart KG Calculation with Fallback**

**Priority 1: Sum Total_DO_Net_KG**
```php
$totalNetKg = floatval($doRecords->sum('Total_DO_Net_KG'));
```

**Priority 2: Calculate from MC data (if Total = 0)**
```php
if ($totalNetKg == 0) {
    $netKgPerPcs = floatval($mainItem->MC_Net_Kg_Per_Pcs ?: 0);
    $doQty = floatval($mainItem->DO_Qty ?: 0);
    
    if ($netKgPerPcs > 0 && $doQty > 0) {
        $totalNetKg = $netKgPerPcs × $doQty;
    }
}
```

**Example:**
```
Case 1: Total_DO_Net_KG available
Total_DO_Net_KG = 100 kg
→ Use 100 kg ✅

Case 2: Total_DO_Net_KG = 0, use MC data
MC_Net_Kg_Per_Pcs = 0.1 kg
DO_Qty = 1000 pcs
→ Calculate: 0.1 × 1000 = 100 kg ✅

Case 3: No data available
→ to_bill_kg = 0 kg ⚠️
```

#### **C. Return KG Per Unit**

**Added to response:**
```php
$itemDetails[] = [
    'to_bill_kg' => $totalNetKg,
    'kg_per_unit' => $doQty > 0 ? ($totalNetKg / $doQty) : 0,
];
```

**Example:**
```
Total KG: 100 kg
Quantity: 1000 pcs
KG per unit: 100 / 1000 = 0.1 kg/pcs
```

---

### **2. Frontend Enhancement** (`SalesOrderItemsModal.vue`)

#### **A. Store KG Per Unit**

**New state variable:**
```javascript
const originalKgPerUnit = ref(0)
```

#### **B. Calculate KG Per Unit on Data Load**

**Priority 1: Use from API**
```javascript
if (mainItem.kg_per_unit && mainItem.kg_per_unit > 0) {
  originalKgPerUnit.value = mainItem.kg_per_unit
}
```

**Priority 2: Calculate from total KG**
```javascript
else if (mainItem.deliver > 0 && mainItem.to_bill_kg > 0) {
  originalKgPerUnit.value = mainItem.to_bill_kg / mainItem.deliver
}
```

**Priority 3: Default to 0**
```javascript
else {
  originalKgPerUnit.value = 0
}
```

#### **C. New Calculation Formula**

**Old Formula (Problematic):**
```javascript
// Used ratio × total KG (could reference wrong data)
const ratio = item.to_bill / item.deliver
item.to_bill_kg = ratio × originalNetKg
```

**New Formula (Accurate):**
```javascript
// Direct calculation: Quantity × KG per unit
item.to_bill_kg = item.to_bill × originalKgPerUnit.value
```

**Example:**
```
To Bill: 300 pcs
KG per unit: 0.1 kg/pcs

To Bill KG = 300 × 0.1 = 30 kg ✅
```

---

## 🔄 Data Flow

### **Complete Flow:**

```
1. User opens Sales Order Items modal
         ↓
2. API Call: GET /api/invoices/do-items?do_number=xxx
         ↓
3. Controller queries DO table:
   - Get Total_DO_Net_KG
   - Get MC_Net_Kg_Per_Pcs (fallback)
         ↓
4. Calculate KG:
   Priority 1: Sum(Total_DO_Net_KG)
   Priority 2: MC_Net_Kg_Per_Pcs × DO_Qty
         ↓
5. Calculate KG per unit:
   kg_per_unit = total_kg / quantity
         ↓
6. Return to frontend:
   {
     to_bill_kg: 100,
     kg_per_unit: 0.1
   }
         ↓
7. Vue stores originalKgPerUnit = 0.1
         ↓
8. User edits To Bill: 300
         ↓
9. Auto-calculate:
   to_bill_kg = 300 × 0.1 = 30 kg
         ↓
10. Display updated: 30.00 kg ✅
```

---

## 📊 Calculation Examples

### **Example 1: Full Quantity**
```
Initial Data:
- Deliver: 1000 pcs
- Total Net KG: 100 kg
- KG per unit: 0.1 kg/pcs

User Input: To Bill = 1000 (no change)

Calculation:
to_bill_kg = 1000 × 0.1 = 100.00 kg ✅
```

### **Example 2: Partial Quantity**
```
Initial Data:
- Deliver: 1000 pcs
- Total Net KG: 100 kg
- KG per unit: 0.1 kg/pcs

User Input: To Bill = 300

Calculation:
to_bill_kg = 300 × 0.1 = 30.00 kg ✅
```

### **Example 3: With MC Fallback**
```
Database:
- Total_DO_Net_KG: 0 (empty)
- MC_Net_Kg_Per_Pcs: 0.15 kg
- DO_Qty: 1000 pcs

Backend Calculates:
total_kg = 0.15 × 1000 = 150 kg
kg_per_unit = 150 / 1000 = 0.15

User Input: To Bill = 500

Frontend Calculates:
to_bill_kg = 500 × 0.15 = 75.00 kg ✅
```

---

## 🎯 Key Improvements

### **Backend:**
1. ✅ **Query MC KG fields** untuk fallback data
2. ✅ **Smart fallback** - Sum Total_DO_Net_KG → MC calculation
3. ✅ **Return kg_per_unit** ke frontend
4. ✅ **Logging** untuk debugging
5. ✅ **Handle multiple DO lines** dengan sum

### **Frontend:**
1. ✅ **Store originalKgPerUnit** sebagai constant
2. ✅ **Simple formula** - qty × kg_per_unit
3. ✅ **Use API kg_per_unit** jika available
4. ✅ **Fallback calculation** dari total_kg / qty
5. ✅ **Warning log** jika no data

---

## 🔧 Technical Details

### **No Migration Required:**
- ✅ Uses existing DO table fields
- ✅ MC_Net_Kg_Per_Pcs already exists
- ✅ Total_DO_Net_KG already exists
- ✅ No schema changes

### **API Response Enhanced:**
```json
{
  "item_details": [
    {
      "item": "Main",
      "deliver": 1000,
      "to_bill": 1000,
      "to_bill_kg": 100,
      "kg_per_unit": 0.1  // ← NEW
    }
  ]
}
```

### **Calculation Accuracy:**
```
Precision: 4 decimal places for kg_per_unit
Display: 2 decimal places for to_bill_kg
Example: 0.1234 kg/unit → display as 30.00 kg
```

---

## 📝 Files Modified

1. **✅ InvoiceController.php**
   - Enhanced getDoItems() method
   - Added MC fields to query
   - Smart KG calculation with fallback
   - Return kg_per_unit

2. **✅ SalesOrderItemsModal.vue**
   - Added originalKgPerUnit state
   - Enhanced fetchDoItems()
   - Improved onToBillChange()
   - Better logging

3. **✅ TO_BILL_KG_FIX.md**
   - Complete documentation

**No changes to:**
- ❌ Database migrations
- ❌ API routes
- ❌ Invoice model

---

## 🧪 Testing Checklist

- [x] To Bill KG shows correct value on load
- [x] To Bill KG updates when user edits To Bill
- [x] Formula: qty × kg_per_unit works
- [x] Fallback to MC data works if Total_DO_Net_KG = 0
- [x] Display with 2 decimal places
- [x] Console logs show correct calculations
- [x] Works for full quantity (1000 pcs)
- [x] Works for partial quantity (300 pcs)
- [x] No errors if KG data missing (shows 0.00)

---

## 🎓 How to Verify

### **Check Console Logs:**

**On Modal Open:**
```
✅ DO items data received: {...}
📦 KG per unit (from API): 0.1000
📊 Populated: {...originalKgPerUnit: 0.1}
```

**On To Bill Change:**
```
📝 To Bill changed for: Main New value: 300
🔢 KG Calculation: {
  to_bill: 300,
  kg_per_unit: 0.1000,
  calculated_kg: 30.00
}
💰 Total recalculated: 3,036,360
✅ Updated: {to_bill: 300, to_bill_kg: 30.00}
```

### **Check Laravel Logs:**

```bash
tail -f storage/logs/laravel.log | grep "KG"
```

**Expected Output:**
```
Main item KG data: {
  "Total_DO_Net_KG": 100,
  "MC_Net_Kg_Per_Pcs": 0.1,
  "calculated_total_kg": 100,
  "do_qty": 1000
}
```

---

## ⚠️ Known Limitations

1. **No KG Data Available**
   - If both Total_DO_Net_KG and MC_Net_Kg_Per_Pcs are 0
   - Display: 0.00 kg
   - Warning logged in console

2. **F# Items**
   - Currently not implemented
   - All show as "-" (placeholders)
   - Only Main item has KG calculation

3. **Multiple Items per DO**
   - Currently sums all KG
   - Shows total for first item
   - Can be enhanced for item-level KG

---

## ✅ Summary

**To Bill KG sekarang:**

1. ✅ **Fetch KG data** dari Total_DO_Net_KG
2. ✅ **Fallback to MC** jika Total_DO_Net_KG = 0
3. ✅ **Calculate kg_per_unit** di backend
4. ✅ **Store originalKgPerUnit** di frontend
5. ✅ **Simple formula** - qty × kg_per_unit
6. ✅ **Auto-update** saat user edit To Bill
7. ✅ **Display 2 decimals** (e.g., 30.00 kg)
8. ✅ **No migration** required

To Bill KG sekarang **berfungsi dengan benar** dan menampilkan nilai yang akurat! 🎉

---

**Last Updated:** October 29, 2025, 23:50 WIB
**Version:** 4.0 - To Bill KG Fixed with Smart Fallback
