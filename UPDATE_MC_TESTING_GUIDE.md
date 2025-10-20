# Quick Testing Guide for Update MC Fixes

## Prerequisites
- Access to the ERP system
- Access to the database (for verification)
- Test customer account with known data

---

## Test Case 1: Customer Name in AC_NAME Field

### Steps:
1. Navigate to **Update MC** menu
2. Click on the **AC#** search button
3. Select a customer (e.g., customer with code "000283")
4. Note the customer name displayed (e.g., "PT. EXAMPLE COMPANY")
5. Enter a new MCS# (e.g., "TEST001")
6. Click **Proceed**
7. Fill in the MC Model field
8. Click **Next Setup** → **Setup PD** → Save

### Verification:
```sql
-- Check AC_NAME field contains customer name, not code
SELECT AC_NUM, AC_NAME FROM MC WHERE MCS_Num = 'TEST001';

-- Expected Result:
-- AC_NUM  | AC_NAME
-- 000283  | PT. EXAMPLE COMPANY  (NOT 000283)
```

**Expected:** AC_NAME should contain "PT. EXAMPLE COMPANY", not "000283"

**Status:** [ ] PASS [ ] FAIL

---

## Test Case 2: TOTAL_SL Calculation

### Steps:
1. Open an existing MC or create a new one
2. Click **Next Setup** → **Setup PD**
3. In the Setup PD modal, find the Score Length (SL) fields
4. Enter test values:
   - SL1: 10
   - SL2: 20
   - SL3: 15
   - SL4: 5
   - SL5: 0 (leave empty)
   - SL6: 0 (leave empty)
   - SL7: 0 (leave empty)
   - SL8: 0 (leave empty)
5. Save the Master Card

### Verification:
```sql
-- Check TOTAL_SL calculation
SELECT 
    MCS_Num,
    SL1, SL2, SL3, SL4, SL5, SL6, SL7, SL8,
    TOTAL_SL,
    (COALESCE(SL1,0) + COALESCE(SL2,0) + COALESCE(SL3,0) + COALESCE(SL4,0)) as EXPECTED_TOTAL
FROM MC 
WHERE MCS_Num = 'YOUR_TEST_MCS';

-- Expected Result:
-- SL1 | SL2 | SL3 | SL4 | TOTAL_SL | EXPECTED_TOTAL
-- 10  | 20  | 15  | 5   | 50       | 50
```

**Expected:** TOTAL_SL should be 50 (10+20+15+5)

**Status:** [ ] PASS [ ] FAIL

---

## Test Case 3: TOTAL_SW Calculation

### Steps:
1. Same MC as Test Case 2
2. In the Setup PD modal, find the Score Width (SW) fields
3. Enter test values:
   - SW1: 12
   - SW2: 18
   - SW3: 7
   - SW4: 3
   - SW5-SW8: leave empty
4. Save the Master Card

### Verification:
```sql
-- Check TOTAL_SW calculation
SELECT 
    MCS_Num,
    SW1, SW2, SW3, SW4, SW5, SW6, SW7, SW8,
    TOTAL_SW,
    (COALESCE(SW1,0) + COALESCE(SW2,0) + COALESCE(SW3,0) + COALESCE(SW4,0)) as EXPECTED_TOTAL
FROM MC 
WHERE MCS_Num = 'YOUR_TEST_MCS';

-- Expected Result:
-- SW1 | SW2 | SW3 | SW4 | TOTAL_SW | EXPECTED_TOTAL
-- 12  | 18  | 7   | 3   | 40       | 40
```

**Expected:** TOTAL_SW should be 40 (12+18+7+3)

**Status:** [ ] PASS [ ] FAIL

---

## Test Case 4: Currency Field Population

### Steps:
1. First, verify customer has a currency set:
   ```sql
   SELECT CODE, NAME, CURRENCY FROM CUSTOMER WHERE CODE = '000283';
   -- Should return: CODE='000283', NAME='PT. EXAMPLE COMPANY', CURRENCY='IDR'
   ```
2. Create a new MC for this customer
3. Save the Master Card (no need to fill currency manually)

### Verification:
```sql
-- Check CURRENCY field is populated from customer
SELECT 
    MC.MCS_Num,
    MC.AC_NUM,
    MC.CURRENCY as MC_CURRENCY,
    C.CURRENCY as CUSTOMER_CURRENCY
FROM MC
INNER JOIN CUSTOMER C ON MC.AC_NUM = C.CODE
WHERE MC.MCS_Num = 'YOUR_TEST_MCS';

-- Expected Result:
-- MCS_Num  | AC_NUM | MC_CURRENCY | CUSTOMER_CURRENCY
-- TEST001  | 000283 | IDR         | IDR
```

**Expected:** MC.CURRENCY should match CUSTOMER.CURRENCY (e.g., "IDR")

**Status:** [ ] PASS [ ] FAIL

---

## Test Case 5: PCS_PER_BLD Field (Bundling String Qty)

### Steps:
1. Open a Master Card
2. Click **Next Setup** → **Setup PD**
3. In the "Finishing & Others" section, find **Bundling String**
4. Enter quantity value: **100**
5. Save the Master Card

### Verification:
```sql
-- Check PCS_PER_BLD field stores bundling string qty
SELECT 
    MCS_Num,
    PCS_PER_BLD,
    STRING_TYPE
FROM MC 
WHERE MCS_Num = 'YOUR_TEST_MCS';

-- Expected Result:
-- MCS_Num  | PCS_PER_BLD | STRING_TYPE
-- TEST001  | 100         | [string_type_code]
```

**Expected:** PCS_PER_BLD should be 100

**Status:** [ ] PASS [ ] FAIL

---

## Full Integration Test

### Scenario: Create a Complete Master Card

1. **Select Customer:**
   - Customer Code: 000283
   - Customer Name: PT. EXAMPLE COMPANY
   - Currency: IDR

2. **Create MC:**
   - MCS#: FULLTEST001
   - MC Model: Box Type A
   - MC Short Model: BTA

3. **Setup PD with all fields:**
   - SL1: 10, SL2: 15, SL3: 20, SL4: 5
   - SW1: 12, SW2: 8, SW3: 10, SW4: 5
   - Bundling String Qty: 50

4. **Save and Verify:**

```sql
-- Complete verification query
SELECT 
    MC.MCS_Num,
    MC.AC_NUM,
    MC.AC_NAME,
    MC.CURRENCY,
    MC.MODEL,
    MC.SL1, MC.SL2, MC.SL3, MC.SL4, MC.TOTAL_SL,
    MC.SW1, MC.SW2, MC.SW3, MC.SW4, MC.TOTAL_SW,
    MC.PCS_PER_BLD,
    C.NAME as ACTUAL_CUSTOMER_NAME,
    C.CURRENCY as ACTUAL_CURRENCY
FROM MC
INNER JOIN CUSTOMER C ON MC.AC_NUM = C.CODE
WHERE MC.MCS_Num = 'FULLTEST001';
```

### Expected Results:
| Field | Expected Value |
|-------|----------------|
| AC_NUM | 000283 |
| AC_NAME | PT. EXAMPLE COMPANY |
| CURRENCY | IDR |
| TOTAL_SL | 50 |
| TOTAL_SW | 35 |
| PCS_PER_BLD | 50 |

**Overall Status:** [ ] PASS [ ] FAIL

---

## Common Issues & Troubleshooting

### Issue 1: AC_NAME still shows customer code
**Possible Causes:**
- Old cached data in browser
- Database not updated
- Customer record doesn't exist in CUSTOMER table

**Solution:**
- Clear browser cache and refresh
- Check if customer exists: `SELECT CODE, NAME FROM CUSTOMER WHERE CODE = 'customer_code'`
- Re-save the Master Card

### Issue 2: TOTAL_SL/TOTAL_SW not calculating
**Possible Causes:**
- Frontend not sending SL/SW values
- Database column type issue
- Values are strings instead of numbers

**Solution:**
- Check browser console for errors
- Verify SL/SW values in the request payload
- Check database column types: `DESCRIBE MC;`

### Issue 3: CURRENCY field is NULL
**Possible Causes:**
- Customer doesn't have currency set
- CUSTOMER table relationship issue

**Solution:**
- Set currency for customer: `UPDATE CUSTOMER SET CURRENCY = 'IDR' WHERE CODE = 'customer_code'`
- Verify customer data: `SELECT CODE, CURRENCY FROM CUSTOMER WHERE CODE = 'customer_code'`

### Issue 4: PCS_PER_BLD not saving
**Possible Causes:**
- Frontend modal not sending the value
- Incorrect field mapping in backend

**Solution:**
- Check the PD Setup modal form data
- Verify the request payload includes `bundlingStringQty`
- Check browser console for errors

---

## Test Results Summary

| Test Case | Status | Notes |
|-----------|--------|-------|
| AC_NAME Field | [ ] | |
| TOTAL_SL Calculation | [ ] | |
| TOTAL_SW Calculation | [ ] | |
| CURRENCY Field | [ ] | |
| PCS_PER_BLD Field | [ ] | |
| Full Integration | [ ] | |

**Tested By:** ___________________

**Date:** ___________________

**Sign-off:** ___________________

---

## Notes
- Document any issues found during testing
- Take screenshots of any errors
- Record SQL query results for verification
- Test with multiple customers to ensure consistency
