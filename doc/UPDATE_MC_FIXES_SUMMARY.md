# Update MC Fixes Summary

## Changes Made to Fix Master Card (MC) Data Storage Issues

### Date: 2025-10-20

---

## Issues Fixed

### 1. **AC_NAME Field - Customer Name Storage** ✅
**Problem:** The `AC_NAME` field in the MC table was storing the customer code instead of the customer name.

**Solution:** 
- Modified `UpdateMcController.php` to fetch customer data from the `CUSTOMER` table
- Changed the mapping to use `$customer->NAME` instead of customer code
- Updated frontend to pass `customer_name` in the payload

**Files Changed:**
- `app/Http/Controllers/UpdateMcController.php`
- `resources/js/Pages/sales-management/system-requirement/master-card/update-mc.vue`

**Code Changes:**
```php
// Before
'AC_NAME' => optional(\App\Models\UpdateCustomerAccount::where('customer_code', $validated['customer_code'])->first())
    ->customer_name ?? $validated['customer_code'],

// After
$customer = \App\Models\Customer::where('CODE', $validated['customer_code'])->first();
'AC_NAME' => $customer ? $customer->NAME : $validated['customer_code'],
```

---

### 2. **TOTAL_SL and TOTAL_SW Calculation** ✅
**Problem:** The `TOTAL_SL` and `TOTAL_SW` fields were not calculating the sum of SL1-SL8 and SW1-SW8 values.

**Solution:**
- Added automatic calculation logic in the controller
- Loop through SL1-SL8 and SW1-SW8 fields
- Calculate the sum and store in TOTAL_SL and TOTAL_SW fields
- Override any passed values to ensure accuracy

**Files Changed:**
- `app/Http/Controllers/UpdateMcController.php`

**Code Changes:**
```php
// Calculate totals automatically
$totalSL = 0;
$totalSW = 0;

for ($i = 1; $i <= 8; $i++) {
    // ... get SL and SW values ...
    
    if ($legacy['SL' . $i] !== null) {
        $totalSL += (float)$legacy['SL' . $i];
    }
    if ($legacy['SW' . $i] !== null) {
        $totalSW += (float)$legacy['SW' . $i];
    }
}

// Set calculated totals
$legacy['TOTAL_SL'] = $totalSL > 0 ? $totalSL : null;
$legacy['TOTAL_SW'] = $totalSW > 0 ? $totalSW : null;
```

---

### 3. **CURRENCY Field Population** ✅
**Problem:** The `CURRENCY` field in the MC table was not being populated from the customer's currency data, and was being overwritten by PD setup data.

**Solution:**
- Fetch customer data from the `CUSTOMER` table
- Extract the `CURRENCY` field from the customer record
- Map it to the MC table during save operation
- **Fixed overwrite issue**: Added conditional check to prevent PD setup from overwriting customer currency

**Files Changed:**
- `app/Http/Controllers/UpdateMcController.php`

**Code Changes:**
```php
// Fetch customer and get currency at the beginning
$customer = \App\Models\Customer::where('CODE', $validated['customer_code'])->first();

$legacy = [
    // ... other fields ...
    'CURRENCY' => $customer ? $customer->CURRENCY : null,
];

// Later in the code - prevent PD setup from overwriting customer currency
$legacy['UNIT'] = $alias($pd, ['unit','uom']);
// CURRENCY should always come from customer table, not from PD setup
// Only use PD currency if customer is not found
if (!$customer) {
    $legacy['CURRENCY'] = $alias($pd, ['currency']);
}
```

---

### 4. **PCS_PER_BLD Field - Bundling String Qty** ✅
**Problem:** The `PCS_PER_BLD` field was not storing the bundling string quantity value correctly.

**Solution:**
- Updated the field mapping to prioritize `bundlingStringQty` from the PD setup data
- Added proper fallback aliases for backward compatibility
- Ensured the value is saved when provided in the PD setup modal

**Files Changed:**
- `app/Http/Controllers/UpdateMcController.php`

**Code Changes:**
```php
// Before
$legacy['PCS_PER_BLD'] = $keep('PCS_PER_BLD', $num($alias($pd, ['pcsPerBld','pcs_per_bld'])));

// After
// PCS_PER_BLD should store bundling string qty (bundlingStringQty)
$legacy['PCS_PER_BLD'] = $keep('PCS_PER_BLD', $num($alias($pd, ['bundlingStringQty','pcsPerBld','pcs_per_bld'])));
```

---

## Database Fields Affected

### MC Table
| Field | Type | Description | Change |
|-------|------|-------------|--------|
| `AC_NAME` | String | Customer Account Name | Now stores actual customer NAME from CUSTOMER table |
| `TOTAL_SL` | Decimal | Total Score Length | Auto-calculated from SL1-SL8 |
| `TOTAL_SW` | Decimal | Total Score Width | Auto-calculated from SW1-SW8 |
| `CURRENCY` | String | Currency Code | Populated from CUSTOMER.CURRENCY |
| `PCS_PER_BLD` | Decimal | Pieces per Bundle | Now correctly stores bundlingStringQty |

---

## Testing Recommendations

### 1. Test Customer Name Storage
- [ ] Create a new Master Card with a customer
- [ ] Verify AC_NAME field contains customer name, not code
- [ ] Check the database directly: `SELECT AC_NUM, AC_NAME FROM MC WHERE MCS_Num = 'your_test_mc'`

### 2. Test TOTAL_SL and TOTAL_SW Calculation
- [ ] Enter values in SL1-SL8 fields in the PD Setup modal
- [ ] Save the Master Card
- [ ] Verify TOTAL_SL is the sum of SL1-SL8
- [ ] Repeat for SW fields
- [ ] Check: `SELECT SL1, SL2, SL3, SL4, SL5, SL6, SL7, SL8, TOTAL_SL FROM MC WHERE MCS_Num = 'your_test_mc'`

### 3. Test Currency Field
- [ ] Select a customer with a specific currency (e.g., USD, IDR)
- [ ] Create a Master Card for that customer
- [ ] Verify the CURRENCY field matches the customer's currency
- [ ] Check: `SELECT AC_NUM, CURRENCY FROM MC WHERE MCS_Num = 'your_test_mc'`
- [ ] Compare with: `SELECT CODE, CURRENCY FROM CUSTOMER WHERE CODE = 'customer_code'`

### 4. Test PCS_PER_BLD Field
- [ ] Open the PD Setup modal for a Master Card
- [ ] Enter a bundling string quantity value
- [ ] Save the Master Card
- [ ] Verify PCS_PER_BLD field contains the entered value
- [ ] Check: `SELECT PCS_PER_BLD FROM MC WHERE MCS_Num = 'your_test_mc'`

---

## SQL Verification Queries

```sql
-- Verify all fixes at once
SELECT 
    MCS_Num,
    AC_NUM,
    AC_NAME,
    CURRENCY,
    SL1, SL2, SL3, SL4, SL5, SL6, SL7, SL8, TOTAL_SL,
    SW1, SW2, SW3, SW4, SW5, SW6, SW7, SW8, TOTAL_SW,
    PCS_PER_BLD
FROM MC 
WHERE MCS_Num = 'YOUR_TEST_MCS_NUMBER';

-- Verify AC_NAME matches customer name
SELECT 
    MC.AC_NUM,
    MC.AC_NAME,
    CUSTOMER.NAME as ACTUAL_CUSTOMER_NAME,
    CASE 
        WHEN MC.AC_NAME = CUSTOMER.NAME THEN 'CORRECT' 
        ELSE 'INCORRECT' 
    END as STATUS
FROM MC
INNER JOIN CUSTOMER ON MC.AC_NUM = CUSTOMER.CODE
WHERE MC.MCS_Num = 'YOUR_TEST_MCS_NUMBER';

-- Verify CURRENCY matches
SELECT 
    MC.AC_NUM,
    MC.CURRENCY as MC_CURRENCY,
    CUSTOMER.CURRENCY as CUSTOMER_CURRENCY,
    CASE 
        WHEN MC.CURRENCY = CUSTOMER.CURRENCY THEN 'CORRECT' 
        ELSE 'INCORRECT' 
    END as STATUS
FROM MC
INNER JOIN CUSTOMER ON MC.AC_NUM = CUSTOMER.CODE
WHERE MC.MCS_Num = 'YOUR_TEST_MCS_NUMBER';

-- Verify TOTAL_SL calculation
SELECT 
    MCS_Num,
    SL1, SL2, SL3, SL4, SL5, SL6, SL7, SL8,
    (COALESCE(SL1,0) + COALESCE(SL2,0) + COALESCE(SL3,0) + COALESCE(SL4,0) + 
     COALESCE(SL5,0) + COALESCE(SL6,0) + COALESCE(SL7,0) + COALESCE(SL8,0)) as CALCULATED_TOTAL_SL,
    TOTAL_SL,
    CASE 
        WHEN TOTAL_SL = (COALESCE(SL1,0) + COALESCE(SL2,0) + COALESCE(SL3,0) + COALESCE(SL4,0) + 
                         COALESCE(SL5,0) + COALESCE(SL6,0) + COALESCE(SL7,0) + COALESCE(SL8,0)) 
        THEN 'CORRECT' 
        ELSE 'INCORRECT' 
    END as STATUS
FROM MC
WHERE MCS_Num = 'YOUR_TEST_MCS_NUMBER';
```

---

## Notes

1. **Backward Compatibility:** The changes maintain backward compatibility with existing data through the `$keep()` helper function that preserves existing values when new values are empty.

2. **Data Migration:** Existing MC records will need to be updated if you want to fix historical data. A migration script can be created if needed.

3. **Frontend Integration:** The frontend already sends the correct customer_name value, so no additional frontend changes are required beyond the ones made.

4. **Currency Handling:** The currency is now automatically populated from the customer's currency setting, eliminating the need for manual entry.

5. **Auto-calculation:** TOTAL_SL and TOTAL_SW are now automatically calculated, ensuring data consistency and accuracy.

---

## Related Files

- **Controller:** `app/Http/Controllers/UpdateMcController.php`
- **Frontend:** `resources/js/Pages/sales-management/system-requirement/master-card/update-mc.vue`
- **Models:** 
  - `app/Models/Mc.php`
  - `app/Models/Customer.php`
  - `app/Models/UpdateCustomerAccount.php`

---

## Next Steps

1. Test all changes thoroughly
2. Run the SQL verification queries
3. Update any related documentation
4. Consider creating a data migration script for existing records
5. Monitor the application for any issues after deployment
