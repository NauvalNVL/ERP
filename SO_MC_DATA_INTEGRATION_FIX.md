# Sales Order Master Card Data Integration Fix

## Date: 2025-10-20

---

## Problem

When creating a Sales Order in PrepareMCSO.vue, the following fields in the SO table were not being populated from the selected Master Card:

- `INT_L`, `INT_W`, `INT_H` (Internal dimensions)
- `EXT_L`, `EXT_W`, `EXT_H` (External dimensions)  
- `FLUTE` (Paper flute type)
- `PQ1`, `PQ2`, `PQ3`, `PQ4`, `PQ5` (Paper quality 1-5)

These fields were being saved as 0 or empty strings instead of getting the values from the MC table.

---

## Solution

Modified `SalesOrderController.php` to fetch Master Card data from the MC table and populate the SO table fields accordingly.

### Data Mapping

| SO Table Field | MC Table Field | Description |
|----------------|----------------|-------------|
| `INT_L` | `INT_LENGTH` | Internal Length |
| `INT_W` | `INT_WIDTH` | Internal Width |
| `INT_H` | `INT_HEIGHT` | Internal Height |
| `EXT_L` | `EXT_LENGTH` | External Length |
| `EXT_W` | `EXT_WIDTH` | External Width |
| `EXT_H` | `EXT_HEIGHT` | External Height |
| `FLUTE` | `FLUTE` | Paper Flute Type |
| `PQ1` | `SO_PQ1` | Paper Quality 1 |
| `PQ2` | `SO_PQ2` | Paper Quality 2 |
| `PQ3` | `SO_PQ3` | Paper Quality 3 |
| `PQ4` | `SO_PQ4` | Paper Quality 4 |
| `PQ5` | `SO_PQ5` | Paper Quality 5 |

---

## Files Modified

### `app/Http/Controllers/SalesOrderController.php`

#### Changes in `store()` method:

**Before:**
```php
// Fetch master card data from database
$masterCardData = DB::table('MC')
    ->where('MCS_Num', $validated['master_card_seq'])
    ->where('AC_NUM', $validated['customer_code'])
    ->first();
$mcModel = $masterCardData ? $masterCardData->MODEL : '';
$mcPDesign = $masterCardData ? $masterCardData->P_DESIGN : '';
$mcPartNumber = $masterCardData ? $masterCardData->PART_NO : '';

// ... later in code ...
'INT_L' => 0,
'INT_W' => 0,
'INT_H' => 0,
'EXT_L' => 0,
'EXT_W' => 0,
'EXT_H' => 0,
'FLUTE' => '',
'PQ1' => '',
'PQ2' => '',
'PQ3' => '',
'PQ4' => '',
'PQ5' => '',
```

**After:**
```php
// Fetch master card data from database
$masterCardData = DB::table('MC')
    ->where('MCS_Num', $validated['master_card_seq'])
    ->where('AC_NUM', $validated['customer_code'])
    ->first();
$mcModel = $masterCardData ? $masterCardData->MODEL : '';
$mcPDesign = $masterCardData ? $masterCardData->P_DESIGN : '';
$mcPartNumber = $masterCardData ? $masterCardData->PART_NO : '';

// Extract dimensions and flute data from MC table
$intL = $masterCardData ? (float)($masterCardData->INT_LENGTH ?? 0) : 0;
$intW = $masterCardData ? (float)($masterCardData->INT_WIDTH ?? 0) : 0;
$intH = $masterCardData ? (float)($masterCardData->INT_HEIGHT ?? 0) : 0;
$extL = $masterCardData ? (float)($masterCardData->EXT_LENGTH ?? 0) : 0;
$extW = $masterCardData ? (float)($masterCardData->EXT_WIDTH ?? 0) : 0;
$extH = $masterCardData ? (float)($masterCardData->EXT_HEIGHT ?? 0) : 0;
$flute = $masterCardData ? ($masterCardData->FLUTE ?? '') : '';

// Extract paper quality (PQ1-PQ5) from MC table (SO_PQ1-SO_PQ5)
$pq1 = $masterCardData ? ($masterCardData->SO_PQ1 ?? '') : '';
$pq2 = $masterCardData ? ($masterCardData->SO_PQ2 ?? '') : '';
$pq3 = $masterCardData ? ($masterCardData->SO_PQ3 ?? '') : '';
$pq4 = $masterCardData ? ($masterCardData->SO_PQ4 ?? '') : '';
$pq5 = $masterCardData ? ($masterCardData->SO_PQ5 ?? '') : '';

// ... later in code ...
// Populate dimensions from MC table
'INT_L' => $intL,
'INT_W' => $intW,
'INT_H' => $intH,
'EXT_L' => $extL,
'EXT_W' => $extW,
'EXT_H' => $extH,
// Populate flute and paper quality from MC table
'FLUTE' => $flute,
'PQ1' => $pq1,
'PQ2' => $pq2,
'PQ3' => $pq3,
'PQ4' => $pq4,
'PQ5' => $pq5,
```

#### Changes in `apiStoreToSo()` method:

The same logic was applied to the `apiStoreToSo()` method to ensure consistency across both API endpoints.

---

## Testing Guide

### 1. Prerequisites
- Master Card with populated dimensions and paper quality in MC table
- Customer account with valid master card

### 2. Test Steps

#### Test 1: Create New Sales Order
1. Navigate to **Prepare MC SO** page
2. Select a customer account (e.g., customer code: `000283`)
3. Select a master card with known dimensions (e.g., MCS# `TEST001`)
4. Fill in required SO fields
5. Click **Create Sales Order**
6. Verify the SO is created

#### Test 2: Verify Data Population in Database
Run this SQL query to verify the data:

```sql
-- Check if SO fields are populated from MC
SELECT 
    SO.SO_Num,
    SO.MCS_Num,
    SO.INT_L, SO.INT_W, SO.INT_H,
    SO.EXT_L, SO.EXT_W, SO.EXT_H,
    SO.FLUTE,
    SO.PQ1, SO.PQ2, SO.PQ3, SO.PQ4, SO.PQ5,
    MC.INT_LENGTH, MC.INT_WIDTH, MC.INT_HEIGHT,
    MC.EXT_LENGTH, MC.EXT_WIDTH, MC.EXT_HEIGHT,
    MC.FLUTE as MC_FLUTE,
    MC.SO_PQ1, MC.SO_PQ2, MC.SO_PQ3, MC.SO_PQ4, MC.SO_PQ5
FROM so SO
INNER JOIN MC ON SO.MCS_Num = MC.MCS_Num AND SO.AC_Num = MC.AC_NUM
WHERE SO.SO_Num = 'YOUR_SO_NUMBER'
ORDER BY SO.SO_Num DESC
LIMIT 1;
```

**Expected Result:**
- `SO.INT_L` = `MC.INT_LENGTH`
- `SO.INT_W` = `MC.INT_WIDTH`
- `SO.INT_H` = `MC.INT_HEIGHT`
- `SO.EXT_L` = `MC.EXT_LENGTH`
- `SO.EXT_W` = `MC.EXT_WIDTH`
- `SO.EXT_H` = `MC.EXT_HEIGHT`
- `SO.FLUTE` = `MC.FLUTE`
- `SO.PQ1` = `MC.SO_PQ1`
- `SO.PQ2` = `MC.SO_PQ2`
- `SO.PQ3` = `MC.SO_PQ3`
- `SO.PQ4` = `MC.SO_PQ4`
- `SO.PQ5` = `MC.SO_PQ5`

#### Test 3: Verify in Print SO
1. Navigate to **Print SO** page
2. Select the SO number created in Test 1
3. Verify that dimensions and paper quality are displayed correctly
4. Print/Preview the SO document

---

## Verification SQL Queries

### Check Master Card Data
```sql
-- Get MC data for a specific master card
SELECT 
    MCS_Num,
    AC_NUM,
    MODEL,
    INT_LENGTH, INT_WIDTH, INT_HEIGHT,
    EXT_LENGTH, EXT_WIDTH, EXT_HEIGHT,
    FLUTE,
    SO_PQ1, SO_PQ2, SO_PQ3, SO_PQ4, SO_PQ5
FROM MC
WHERE MCS_Num = 'YOUR_MCS_NUMBER'
  AND AC_NUM = 'YOUR_CUSTOMER_CODE';
```

### Compare SO and MC Data
```sql
-- Compare SO and MC data side by side
SELECT 
    'SO Number' as Field,
    SO.SO_Num as SO_Value,
    '' as MC_Value,
    CASE WHEN SO.SO_Num IS NOT NULL THEN '✓' ELSE '✗' END as Status
FROM so SO
WHERE SO.SO_Num = 'YOUR_SO_NUMBER'

UNION ALL

SELECT 'INT_L', 
    CAST(SO.INT_L AS VARCHAR), 
    CAST(MC.INT_LENGTH AS VARCHAR),
    CASE WHEN SO.INT_L = MC.INT_LENGTH THEN '✓ Match' ELSE '✗ Mismatch' END
FROM so SO
INNER JOIN MC ON SO.MCS_Num = MC.MCS_Num AND SO.AC_Num = MC.AC_NUM
WHERE SO.SO_Num = 'YOUR_SO_NUMBER'

UNION ALL

SELECT 'INT_W', 
    CAST(SO.INT_W AS VARCHAR), 
    CAST(MC.INT_WIDTH AS VARCHAR),
    CASE WHEN SO.INT_W = MC.INT_WIDTH THEN '✓ Match' ELSE '✗ Mismatch' END
FROM so SO
INNER JOIN MC ON SO.MCS_Num = MC.MCS_Num AND SO.AC_Num = MC.AC_NUM
WHERE SO.SO_Num = 'YOUR_SO_NUMBER'

UNION ALL

SELECT 'INT_H', 
    CAST(SO.INT_H AS VARCHAR), 
    CAST(MC.INT_HEIGHT AS VARCHAR),
    CASE WHEN SO.INT_H = MC.INT_HEIGHT THEN '✓ Match' ELSE '✗ Mismatch' END
FROM so SO
INNER JOIN MC ON SO.MCS_Num = MC.MCS_Num AND SO.AC_Num = MC.AC_NUM
WHERE SO.SO_Num = 'YOUR_SO_NUMBER'

UNION ALL

SELECT 'EXT_L', 
    CAST(SO.EXT_L AS VARCHAR), 
    CAST(MC.EXT_LENGTH AS VARCHAR),
    CASE WHEN SO.EXT_L = MC.EXT_LENGTH THEN '✓ Match' ELSE '✗ Mismatch' END
FROM so SO
INNER JOIN MC ON SO.MCS_Num = MC.MCS_Num AND SO.AC_Num = MC.AC_NUM
WHERE SO.SO_Num = 'YOUR_SO_NUMBER'

UNION ALL

SELECT 'EXT_W', 
    CAST(SO.EXT_W AS VARCHAR), 
    CAST(MC.EXT_WIDTH AS VARCHAR),
    CASE WHEN SO.EXT_W = MC.EXT_WIDTH THEN '✓ Match' ELSE '✗ Mismatch' END
FROM so SO
INNER JOIN MC ON SO.MCS_Num = MC.MCS_Num AND SO.AC_Num = MC.AC_NUM
WHERE SO.SO_Num = 'YOUR_SO_NUMBER'

UNION ALL

SELECT 'EXT_H', 
    CAST(SO.EXT_H AS VARCHAR), 
    CAST(MC.EXT_HEIGHT AS VARCHAR),
    CASE WHEN SO.EXT_H = MC.EXT_HEIGHT THEN '✓ Match' ELSE '✗ Mismatch' END
FROM so SO
INNER JOIN MC ON SO.MCS_Num = MC.MCS_Num AND SO.AC_Num = MC.AC_NUM
WHERE SO.SO_Num = 'YOUR_SO_NUMBER'

UNION ALL

SELECT 'FLUTE', 
    SO.FLUTE, 
    MC.FLUTE,
    CASE WHEN SO.FLUTE = MC.FLUTE THEN '✓ Match' ELSE '✗ Mismatch' END
FROM so SO
INNER JOIN MC ON SO.MCS_Num = MC.MCS_Num AND SO.AC_Num = MC.AC_NUM
WHERE SO.SO_Num = 'YOUR_SO_NUMBER'

UNION ALL

SELECT 'PQ1', SO.PQ1, MC.SO_PQ1,
    CASE WHEN SO.PQ1 = MC.SO_PQ1 THEN '✓ Match' ELSE '✗ Mismatch' END
FROM so SO
INNER JOIN MC ON SO.MCS_Num = MC.MCS_Num AND SO.AC_Num = MC.AC_NUM
WHERE SO.SO_Num = 'YOUR_SO_NUMBER'

UNION ALL

SELECT 'PQ2', SO.PQ2, MC.SO_PQ2,
    CASE WHEN SO.PQ2 = MC.SO_PQ2 THEN '✓ Match' ELSE '✗ Mismatch' END
FROM so SO
INNER JOIN MC ON SO.MCS_Num = MC.MCS_Num AND SO.AC_Num = MC.AC_NUM
WHERE SO.SO_Num = 'YOUR_SO_NUMBER'

UNION ALL

SELECT 'PQ3', SO.PQ3, MC.SO_PQ3,
    CASE WHEN SO.PQ3 = MC.SO_PQ3 THEN '✓ Match' ELSE '✗ Mismatch' END
FROM so SO
INNER JOIN MC ON SO.MCS_Num = MC.MCS_Num AND SO.AC_Num = MC.AC_NUM
WHERE SO.SO_Num = 'YOUR_SO_NUMBER'

UNION ALL

SELECT 'PQ4', SO.PQ4, MC.SO_PQ4,
    CASE WHEN SO.PQ4 = MC.SO_PQ4 THEN '✓ Match' ELSE '✗ Mismatch' END
FROM so SO
INNER JOIN MC ON SO.MCS_Num = MC.MCS_Num AND SO.AC_Num = MC.AC_NUM
WHERE SO.SO_Num = 'YOUR_SO_NUMBER'

UNION ALL

SELECT 'PQ5', SO.PQ5, MC.SO_PQ5,
    CASE WHEN SO.PQ5 = MC.SO_PQ5 THEN '✓ Match' ELSE '✗ Mismatch' END
FROM so SO
INNER JOIN MC ON SO.MCS_Num = MC.MCS_Num AND SO.AC_Num = MC.AC_NUM
WHERE SO.SO_Num = 'YOUR_SO_NUMBER';
```

### Find SOs with Missing Data
```sql
-- Find SOs that have missing dimension or paper quality data
SELECT 
    SO.SO_Num,
    SO.MCS_Num,
    SO.AC_Num,
    CASE 
        WHEN SO.INT_L = 0 AND SO.INT_W = 0 AND SO.INT_H = 0 THEN '✗ Missing Internal Dims'
        ELSE '✓ Has Internal Dims'
    END as Internal_Status,
    CASE 
        WHEN SO.EXT_L = 0 AND SO.EXT_W = 0 AND SO.EXT_H = 0 THEN '✗ Missing External Dims'
        ELSE '✓ Has External Dims'
    END as External_Status,
    CASE 
        WHEN SO.FLUTE = '' THEN '✗ Missing Flute'
        ELSE '✓ Has Flute'
    END as Flute_Status,
    CASE 
        WHEN SO.PQ1 = '' AND SO.PQ2 = '' AND SO.PQ3 = '' THEN '✗ Missing Paper Quality'
        ELSE '✓ Has Paper Quality'
    END as PQ_Status
FROM so SO
WHERE SO.SO_DMY >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
ORDER BY SO.SO_Num DESC;
```

---

## Notes

1. **Data Source**: All dimension and paper quality data now comes from the MC table
2. **Null Handling**: If MC data is not found, fields default to 0 or empty string
3. **Data Types**: Dimensions are cast to `float`, paper quality to `string`
4. **Backward Compatibility**: Existing SOs are not affected, only new SOs created after this fix
5. **Both Endpoints**: The fix is applied to both `store()` and `apiStoreToSo()` methods for consistency

---

## Impact

- **Positive**: SO records now have complete master card information
- **Positive**: Print SO will display correct dimensions and paper quality
- **Positive**: Reports and analytics based on SO data will be more accurate
- **No Breaking Changes**: Existing functionality remains unchanged

---

## Related Files

- **Controller**: `app/Http/Controllers/SalesOrderController.php`
- **Frontend**: `resources/js/Pages/sales-management/sales-order/transaction/PrepareMCSO.vue`
- **Models**: 
  - `app/Models/Mc.php` (Master Card model)
  - SO table (database)

---

## Status

✅ **COMPLETED** - Ready for testing

All changes have been implemented and verified for syntax errors. Manual testing is required to confirm data population.
