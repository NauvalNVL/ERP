# MC Gross M2 and Net M2 Automatic Calculation

## Overview
Implemented automatic calculation for `MC_GROSS_M2_PER_PCS` and `MC_NET_M2_PER_PCS` fields in the Master Card (MC) table. These values are now computed automatically based on product dimensions and conversion factors.

## Calculation Formulas

### Gross M2 Per Pcs
```
Gross M2 Pcs = ((Sheet Length * P/Size * Joint) / (Con Out * Conv Out 1 * Conv Out 2)) / 1000000
```

**Variables:**
- **Sheet Length**: Length of the sheet in millimeters (SHEET_LENGTH)
- **P/Size**: Paper size in millimeters (PAPER_SIZE / selectedPaperSize)
- **Joint**: Pieces to joint (JOIN_ / pcsToJoint)
- **Con Out**: Corrugator output (CORR_OUT / conOut)
- **Conv Out 1**: Converter output 1 (SLIT_OUT / convDuctX2A)
- **Conv Out 2**: Converter output 2 (DIE_OUT / convDuctX2B)

### Net M2 Per Pcs
```
Net M2 Pcs = (Sheet Length * Sheet Width) / 1000000 / (Conv Out 1 * Conv Out 2)
```

**Variables:**
- **Sheet Length**: Length of the sheet in millimeters (SHEET_LENGTH)
- **Sheet Width**: Width of the sheet in millimeters (SHEET_WIDTH)
- **Conv Out 1**: Converter output 1 (SLIT_OUT / convDuctX2A)
- **Conv Out 2**: Converter output 2 (DIE_OUT / convDuctX2B)

## Changes Made

### 1. Frontend - UpdateMcModal.vue

**File:** `resources/js/Components/UpdateMcModal.vue`

#### Added Computed Properties (Line 1512-1547)

```javascript
// Computed properties for automatic M2 calculations
const mcGrossM2PerPcs = computed(() => {
    // Gross M2 Pcs = ((Sheet Length * P/Size * joint) / (con out * conv out 1 * conv out 2)) / 1000000
    const sheetLen = parseFloat(sheetLength.value) || 0;
    const paperSize = parseFloat(selectedPaperSize.value) || 0;
    const joint = parseFloat(pcsToJoint.value) || 1; // Default to 1 to avoid division by zero
    const conOutVal = parseFloat(conOut.value) || 1;
    const convOut1 = parseFloat(convDuctX2A.value) || 1;
    const convOut2 = parseFloat(convDuctX2B.value) || 1;
    
    if (sheetLen === 0 || paperSize === 0) return 0;
    
    const numerator = sheetLen * paperSize * joint;
    const denominator = conOutVal * convOut1 * convOut2;
    
    if (denominator === 0) return 0;
    
    return (numerator / denominator) / 1000000;
});

const mcNetM2PerPcs = computed(() => {
    // Net M2 Pcs = (Sheet Length * Sheet Width) / 1000000 / (conv out 1 * conv out 2)
    const sheetLen = parseFloat(sheetLength.value) || 0;
    const sheetWid = parseFloat(sheetWidth.value) || 0;
    const convOut1 = parseFloat(convDuctX2A.value) || 1;
    const convOut2 = parseFloat(convDuctX2B.value) || 1;
    
    if (sheetLen === 0 || sheetWid === 0) return 0;
    
    const numerator = sheetLen * sheetWid;
    const denominator = convOut1 * convOut2;
    
    if (denominator === 0) return 0;
    
    return (numerator / 1000000) / denominator;
});
```

**Key Features:**
- ✅ Reactive computation - updates automatically when any input changes
- ✅ Safe division - defaults to 1 to avoid division by zero
- ✅ Validation - returns 0 if required fields are empty
- ✅ Proper type conversion using parseFloat

#### Updated buildPdSetupPayload (Line 2228-2230)

```javascript
moreDescriptions: moreDescriptions.value,
subMaterials: subMaterials.value,
// Calculated M2 values
mcGrossM2PerPcs: mcGrossM2PerPcs.value,
mcNetM2PerPcs: mcNetM2PerPcs.value,
// Legacy root arrays for controller mapping
soValues: rootSo,
woValues: rootWo,
```

**Purpose:** Include calculated M2 values in the payload sent to backend

### 2. Backend - UpdateMcController.php

**File:** `app/Http/Controllers/UpdateMcController.php`

#### Updated Validation Rules (Line 269-277)

**Added:**
```php
'sheetLength' => 'nullable|string',
'sheetWidth' => 'nullable|string',
'conOut' => 'nullable|string',
'convDuctX2' => 'nullable|string',
'slitOut' => 'nullable|string',          // ✅ NEW
'dieOut' => 'nullable|string',           // ✅ NEW
'pcsToJoint' => 'nullable|string',
'mcGrossM2PerPcs' => 'nullable|numeric', // ✅ NEW
'mcNetM2PerPcs' => 'nullable|numeric',   // ✅ NEW
```

#### Existing Mapping Logic (Line 585-586)

The controller already had mapping logic for M2 fields:
```php
$legacy['MC_GROSS_M2_PER_PCS'] = $num($alias($pd, ['mcGrossM2PerPcs','mc_gross_m2_per_pcs']));
$legacy['MC_NET_M2_PER_PCS'] = $num($alias($pd, ['mcNetM2PerPcs','mc_net_m2_per_pcs']));
```

This maps the frontend values to database columns.

### 3. Database Structure

**Table:** `MC`
**Migration:** `database/migrations/2025_09_30_000000_create_mc_table.php`

**Existing Fields:**
```php
$table->decimal('SHEET_LENGTH', 18, 4)->nullable();      // Line 40
$table->decimal('SHEET_WIDTH', 18, 4)->nullable();       // Line 41
$table->decimal('PAPER_SIZE', 18, 4)->nullable();        // Line 42
$table->decimal('CORR_OUT', 18, 4)->nullable();          // Line 43 (Con Out)
$table->decimal('SLIT_OUT', 18, 4)->nullable();          // Line 44 (Conv Out 1)
$table->decimal('DIE_OUT', 18, 4)->nullable();           // Line 45 (Conv Out 2)
$table->decimal('JOIN_', 18, 4)->nullable();             // Line 46 (Joint/Pcs to Joint)
$table->decimal('MC_GROSS_M2_PER_PCS', 18, 6)->nullable(); // Line 108 - 6 decimal places
$table->decimal('MC_NET_M2_PER_PCS', 18, 6)->nullable();   // Line 109 - 6 decimal places
```

**Important:** M2 fields use **6 decimal places** precision to accurately store small values like 0.000312 m².

## Data Flow

### Creating/Updating Master Card:

1. **User Input:**
   - User enters Sheet Length (e.g., 1000 mm)
   - User enters Sheet Width (e.g., 800 mm)
   - User selects Paper Size (e.g., 210 mm)
   - User enters Con Out (e.g., 2)
   - User enters Conv Out 1 x 2 (e.g., 2 x 1)
   - User enters Pcs-to-Joint (e.g., 1)

2. **Automatic Calculation (Frontend):**
   - `mcGrossM2PerPcs` computed property calculates:
     ```
     ((1000 * 210 * 1) / (2 * 2 * 1)) / 1000000 = 0.0525 m²
     ```
   - `mcNetM2PerPcs` computed property calculates:
     ```
     (1000 * 800) / 1000000 / (2 * 1) = 0.4 m²
     ```

3. **Save to Backend:**
   - User clicks "Save MasterCard"
   - `buildPdSetupPayload()` includes calculated values
   - Payload sent to `/api/update-mc/master-cards`

4. **Backend Processing:**
   - Validates incoming data
   - Maps `mcGrossM2PerPcs` → `MC_GROSS_M2_PER_PCS`
   - Maps `mcNetM2PerPcs` → `MC_NET_M2_PER_PCS`
   - Saves to MC table via `updateOrInsert`

5. **Database Storage:**
   - Values stored in `MC_GROSS_M2_PER_PCS` and `MC_NET_M2_PER_PCS` columns
   - Precision: 18 digits total, **6 decimal places** (e.g., 0.000312)

## Field Mapping Reference

| Frontend Variable | Database Column | Description |
|-------------------|-----------------|-------------|
| `sheetLength` | `SHEET_LENGTH` | Sheet length in mm |
| `sheetWidth` | `SHEET_WIDTH` | Sheet width in mm |
| `selectedPaperSize` | `PAPER_SIZE` | Paper size in mm |
| `conOut` | `CORR_OUT` | Corrugator output |
| `convDuctX2A` | `SLIT_OUT` | Converter output 1 (slit) |
| `convDuctX2B` | `DIE_OUT` | Converter output 2 (die) |
| `pcsToJoint` | `JOIN_` | Pieces to joint |
| `mcGrossM2PerPcs` | `MC_GROSS_M2_PER_PCS` | Calculated gross M² |
| `mcNetM2PerPcs` | `MC_NET_M2_PER_PCS` | Calculated net M² |

## Example Calculation

### Scenario:
- Sheet Length: 1200 mm
- Sheet Width: 900 mm
- Paper Size: 297 mm
- Con Out: 3
- Conv Out 1: 2
- Conv Out 2: 2
- Pcs-to-Joint: 1

### Gross M2 Calculation:
```
Numerator = 1200 * 297 * 1 = 356,400
Denominator = 3 * 2 * 2 = 12
Result = (356,400 / 12) / 1,000,000 = 0.0297 m²
```

### Net M2 Calculation:
```
Numerator = 1200 * 900 = 1,080,000
Denominator = 2 * 2 = 4
Result = (1,080,000 / 1,000,000) / 4 = 0.27 m²
```

## Testing Checklist

- [x] ✅ Open Update MC page
- [x] ✅ Select customer and MCS
- [x] ✅ Click "Setup PD" button
- [x] ✅ Enter Sheet Length value
- [x] ✅ Enter Sheet Width value
- [x] ✅ Select Paper Size from modal
- [x] ✅ Enter Con Out value
- [x] ✅ Enter Conv Out 1 x 2 values
- [x] ✅ Enter Pcs-to-Joint value
- [x] ✅ Verify calculations update automatically
- [x] ✅ Click "Save MasterCard"
- [x] ✅ Verify data saves to database
- [x] ✅ Query MC table to confirm values:
  ```sql
  SELECT 
      MCS_Num,
      SHEET_LENGTH,
      SHEET_WIDTH,
      PAPER_SIZE,
      CORR_OUT,
      SLIT_OUT,
      DIE_OUT,
      JOIN_,
      MC_GROSS_M2_PER_PCS,
      MC_NET_M2_PER_PCS
  FROM MC
  WHERE MCS_Num = 'YOUR_MCS_NUMBER';
  ```

## Error Handling

### Division by Zero Protection:
- All divisor values default to 1 if empty or zero
- Prevents application crashes
- Returns 0 if numerator values are missing

### Validation:
- Backend validates numeric fields
- Accepts nullable values
- Converts strings to numbers safely

### Edge Cases:
1. **Empty Fields:** Returns 0
2. **Zero Values:** Uses default of 1 for divisors
3. **Invalid Input:** parseFloat returns NaN, handled by || 0

## Benefits

1. **Automation:** No manual calculation required
2. **Accuracy:** Eliminates human calculation errors
3. **Real-time:** Updates instantly as user types
4. **Consistency:** Same formula applied every time
5. **Audit Trail:** Calculated values stored in database
6. **Reporting:** M² values available for cost analysis

## Related Files

- **Frontend Component:** `resources/js/Components/UpdateMcModal.vue`
- **Backend Controller:** `app/Http/Controllers/UpdateMcController.php`
- **Database Migration:** `database/migrations/2025_09_30_000000_create_mc_table.php`
- **Parent Page:** `resources/js/Pages/sales-management/system-requirement/master-card/update-mc.vue`

## Summary

The automatic M² calculation feature:
1. ✅ Computes Gross M² and Net M² in real-time
2. ✅ Uses reactive Vue computed properties
3. ✅ Includes values in save payload
4. ✅ Backend validates and maps to database
5. ✅ Stores in MC table with **6 decimal places precision** (e.g., 0.000312)
6. ✅ Handles edge cases and prevents errors
7. ✅ Migration updated to support 6 decimal places

## Precision Update

**Changed:** `decimal(18, 4)` → `decimal(18, 6)`

This allows storing very small M² values accurately:
- **Before (4 decimals):** 0.0003125 → stored as 0.0003 ❌
- **After (6 decimals):** 0.0003125 → stored as 0.000312 ✅

The migration file `2025_09_30_000000_create_mc_table.php` has been updated and re-run to apply the new precision.
