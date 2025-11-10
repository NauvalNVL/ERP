# Fix: DO_M3 Calculation Implementation

## 📐 DO_M3 Formula

### **Formula yang BENAR:**
```
DO_M3 = (EXT_L × EXT_W × EXT_H × DO_Qty) / 1,000,000,000
```

**Penjelasan:**
- `EXT_L` = External Length (mm)
- `EXT_W` = External Width (mm)
- `EXT_H` = External Height (mm)
- `DO_Qty` = Delivery Order Quantity (pcs)
- `1,000,000,000` = Conversion factor from mm³ to m³

**Unit Conversion:**
- Input dimensions: **millimeters (mm)**
- Volume calculation: **mm³**
- Final result: **cubic meters (m³)**

---

## ❌ Problem SEBELUM Fix

### **DeliveryOrderController.php - Line 261:**
```php
'DO_M3' => 0.0,  // ❌ HARDCODED! Tidak dihitung sama sekali
```

**Impact:**
- ❌ Semua DO records punya `DO_M3 = 0.0`
- ❌ Tidak ada data volume untuk logistics planning
- ❌ Report DO tidak akurat

---

## ✅ Solution SETELAH Fix

### **DeliveryOrderController.php - store() Method (Line 217-241):**

```php
// Get EXT dimensions for DO_M3 calculation
$extL = (float) (($so->EXT_L ?? null) !== null ? $so->EXT_L : ($mc->EXT_LENGTH ?? 0));
$extW = (float) (($so->EXT_W ?? null) !== null ? $so->EXT_W : ($mc->EXT_WIDTH ?? 0));
$extH = (float) (($so->EXT_H ?? null) !== null ? $so->EXT_H : ($mc->EXT_HEIGHT ?? 0));

// Calculate DO_M3 (volume) = (L × W × H × Quantity) / 1,000,000,000 (convert mm³ to m³)
// Use EXT dimensions for outer carton volume
$doM3 = 0.0;
if ($extL > 0 && $extW > 0 && $extH > 0 && $doQty > 0) {
    $doM3 = ($extL * $extW * $extH * $doQty) / 1000000000;
    Log::info('DO_M3 calculated from EXT dimensions', [
        'do_number' => $doNumber,
        'ext_l' => $extL,
        'ext_w' => $extW,
        'ext_h' => $extH,
        'do_qty' => $doQty,
        'do_m3' => $doM3
    ]);
} else {
    Log::warning('DO_M3 calculation failed - no valid dimensions', [
        'do_number' => $doNumber,
        'ext_dimensions' => [$extL, $extW, $extH],
        'do_qty' => $doQty
    ]);
}

// In $doData array:
'DO_M3' => round($doM3, 2), // ✅ FIXED: Calculated from (EXT_L × EXT_W × EXT_H × DO_Qty) / 1,000,000,000
```

---

## 📊 Calculation Example

### **Example 1: Standard Box**

**Input Data:**
- EXT_L = 500 mm
- EXT_W = 400 mm
- EXT_H = 300 mm
- DO_Qty = 100 pcs

**Calculation:**
```
DO_M3 = (500 × 400 × 300 × 100) / 1,000,000,000
DO_M3 = 6,000,000,000 / 1,000,000,000
DO_M3 = 6.00 m³
```

---

### **Example 2: Large Carton**

**Input Data:**
- EXT_L = 1200 mm
- EXT_W = 800 mm
- EXT_H = 600 mm
- DO_Qty = 50 pcs

**Calculation:**
```
DO_M3 = (1200 × 800 × 600 × 50) / 1,000,000,000
DO_M3 = 28,800,000,000 / 1,000,000,000
DO_M3 = 28.80 m³
```

---

### **Example 3: Small Package**

**Input Data:**
- EXT_L = 250 mm
- EXT_W = 200 mm
- EXT_H = 150 mm
- DO_Qty = 500 pcs

**Calculation:**
```
DO_M3 = (250 × 200 × 150 × 500) / 1,000,000,000
DO_M3 = 3,750,000,000 / 1,000,000,000
DO_M3 = 3.75 m³
```

---

## 🔄 Data Source Priority

### **EXT Dimensions Source:**

| Priority | Source | Condition |
|----------|--------|-----------|
| 1 | `SO.EXT_L`, `SO.EXT_W`, `SO.EXT_H` | If SO exists and has dimensions |
| 2 | `MC.EXT_LENGTH`, `MC.EXT_WIDTH`, `MC.EXT_HEIGHT` | Fallback from Master Card |
| 3 | `0` | If no dimensions available (DO_M3 = 0.0) |

**Code:**
```php
$extL = (float) (($so->EXT_L ?? null) !== null ? $so->EXT_L : ($mc->EXT_LENGTH ?? 0));
$extW = (float) (($so->EXT_W ?? null) !== null ? $so->EXT_W : ($mc->EXT_WIDTH ?? 0));
$extH = (float) (($so->EXT_H ?? null) !== null ? $so->EXT_H : ($mc->EXT_HEIGHT ?? 0));
```

---

## 🧪 Testing Scenarios

### **Test 1: Create DO with Valid Dimensions**

**Steps:**
1. Create DO with:
   - EXT_L = 500 mm
   - EXT_W = 400 mm
   - EXT_H = 300 mm
   - DO_Qty = 100 pcs

**Expected Result:**
- `DO_M3 = 6.00` ✅
- Log message: "DO_M3 calculated from EXT dimensions"

---

### **Test 2: Create DO with Missing Dimensions**

**Steps:**
1. Create DO with:
   - EXT_L = 0 mm (missing)
   - EXT_W = 0 mm (missing)
   - EXT_H = 0 mm (missing)
   - DO_Qty = 100 pcs

**Expected Result:**
- `DO_M3 = 0.00` ✅
- Log warning: "DO_M3 calculation failed - no valid dimensions"

---

### **Test 3: Create DO with Zero Quantity**

**Steps:**
1. Create DO with:
   - EXT_L = 500 mm
   - EXT_W = 400 mm
   - EXT_H = 300 mm
   - DO_Qty = 0 pcs

**Expected Result:**
- `DO_M3 = 0.00` ✅
- Log warning: "DO_M3 calculation failed - no valid dimensions"

---

## 📋 Database Schema

### **DO Table - DO_M3 Column:**
```php
$table->decimal('DO_M3', 15, 2)->nullable();
```

**Data Type:**
- Type: `DECIMAL(15, 2)`
- Precision: 15 digits total
- Scale: 2 decimal places
- Nullable: Yes

**Value Range:**
- Min: `0.00`
- Max: `9,999,999,999,999.99`
- Typical: `0.01` - `1,000.00` m³

---

## 🎯 Benefits

### **For System:**
1. ✅ **Accurate Volume Data** - DO_M3 calculated correctly
2. ✅ **Consistent Logic** - sama dengan SO.TOTAL_M3 calculation
3. ✅ **Better Logging** - detailed calculation logs for debugging
4. ✅ **Data Validation** - checks for valid dimensions before calculation

### **For Users:**
1. ✅ **Logistics Planning** - accurate volume for truck capacity planning
2. ✅ **Warehouse Management** - space allocation based on actual volume
3. ✅ **Cost Calculation** - shipping cost based on correct volume
4. ✅ **Reporting** - accurate volume reports for management

---

## 🔗 Related Calculations

### **SO.TOTAL_M3 (Sales Order):**

**File:** `SalesOrderController.php` - Line 265-288

```php
// Calculate total M3 (volume) = (L × W × H × Quantity) / 1,000,000,000 (convert mm³ to m³)
// Use EXT dimensions for outer carton volume
$totalM3 = 0;

// Try EXT dimensions first, fallback to INT dimensions
if ($extL > 0 && $extW > 0 && $extH > 0 && $qty > 0) {
    $totalM3 = ($extL * $extW * $extH * $qty) / 1000000000;
    Log::info('TOTAL_M3 calculated from EXT dimensions', ['totalM3' => $totalM3]);
} elseif ($intL > 0 && $intW > 0 && $intH > 0 && $qty > 0) {
    $totalM3 = ($intL * $intW * $intH * $qty) / 1000000000;
    Log::info('TOTAL_M3 calculated from INT dimensions', ['totalM3' => $totalM3]);
}

// In SO data:
'TOTAL_M3' => (int) round($totalM3),  // Note: SO stores as integer, DO stores as decimal
```

**Key Differences:**
- **SO.TOTAL_M3**: Stored as `INTEGER` (rounded)
- **DO.DO_M3**: Stored as `DECIMAL(15,2)` (with 2 decimal places)

---

## 📝 Verification Checklist

### **After Creating DO:**

- [ ] Check `DO_M3` is not zero (if dimensions exist)
- [ ] Verify `DO_M3` matches manual calculation:
  ```
  DO_M3 = (EXT_L × EXT_W × EXT_H × DO_Qty) / 1,000,000,000
  ```
- [ ] Check log file for calculation messages
- [ ] Compare with `SO.TOTAL_M3` if DO qty = SO qty

### **Validation Query:**
```sql
SELECT 
    DO_Num,
    EXT_L,
    EXT_W,
    EXT_H,
    DO_Qty,
    DO_M3,
    (EXT_L * EXT_W * EXT_H * DO_Qty) / 1000000000 as Calculated_M3,
    ABS(DO_M3 - ((EXT_L * EXT_W * EXT_H * DO_Qty) / 1000000000)) as Difference
FROM DO
WHERE DO_M3 > 0
ORDER BY DO_Num DESC
LIMIT 10;
```

---

## 🚨 Edge Cases Handled

### **1. Missing Dimensions:**
```php
if ($extL > 0 && $extW > 0 && $extH > 0 && $doQty > 0) {
    // Calculate DO_M3
} else {
    // DO_M3 = 0.0, log warning
}
```

### **2. Zero Quantity:**
```php
if ($doQty > 0) {
    // Proceed with calculation
} else {
    // DO_M3 = 0.0
}
```

### **3. Null Values:**
```php
$extL = (float) (($so->EXT_L ?? null) !== null ? $so->EXT_L : ($mc->EXT_LENGTH ?? 0));
// Handles: null, undefined, missing fields
```

### **4. Rounding:**
```php
'DO_M3' => round($doM3, 2),
// Always 2 decimal places: 6.123456 → 6.12
```

---

## ✅ Summary

### **What Was Wrong:**
- `DO_M3` = 0.0 (hardcoded) ❌

### **What Is Fixed:**
- `DO_M3` = **(EXT_L × EXT_W × EXT_H × DO_Qty) / 1,000,000,000** ✅

### **Formula Confirmed:**
**YES, your formula is 100% CORRECT!** ✅

```
DO_M3 = (EXT_L × EXT_W × EXT_H × DO_Qty) / 1,000,000,000
```

---

**Status**: ✅ COMPLETED
**Date Fixed**: 10 November 2025
**File Modified**: 
- `app/Http/Controllers/DeliveryOrderController.php` (Line 217-287)
