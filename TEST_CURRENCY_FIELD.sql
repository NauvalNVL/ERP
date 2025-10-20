-- CURRENCY FIELD VERIFICATION TEST
-- Run this after saving a Master Card to verify currency is populated correctly

-- ========================================
-- Test 1: Basic Currency Check
-- ========================================
-- This should show that MC.CURRENCY matches CUSTOMER.CURRENCY
SELECT 
    MC.MCS_Num,
    MC.AC_NUM,
    MC.AC_NAME,
    MC.CURRENCY as MC_CURRENCY,
    C.CURRENCY as CUSTOMER_CURRENCY,
    CASE 
        WHEN MC.CURRENCY = C.CURRENCY THEN '✓ CORRECT' 
        WHEN MC.CURRENCY IS NULL THEN '✗ NULL - MISSING'
        ELSE '✗ MISMATCH' 
    END as STATUS
FROM MC
INNER JOIN CUSTOMER C ON MC.AC_NUM = C.CODE
ORDER BY MC.MCS_Num DESC
LIMIT 10;

-- ========================================
-- Test 2: Find MCs with Missing Currency
-- ========================================
-- This should return empty result if all MCs have currency populated
SELECT 
    MC.MCS_Num,
    MC.AC_NUM,
    MC.AC_NAME,
    MC.CURRENCY as MC_CURRENCY,
    C.NAME as CUSTOMER_NAME,
    C.CURRENCY as CUSTOMER_CURRENCY
FROM MC
INNER JOIN CUSTOMER C ON MC.AC_NUM = C.CODE
WHERE MC.CURRENCY IS NULL AND C.CURRENCY IS NOT NULL
ORDER BY MC.MCS_Num DESC;

-- ========================================
-- Test 3: Currency Mismatch Detection
-- ========================================
-- This should return empty result if currency is correctly populated from customer
SELECT 
    MC.MCS_Num,
    MC.AC_NUM,
    MC.AC_NAME,
    MC.CURRENCY as MC_CURRENCY,
    C.CURRENCY as CUSTOMER_CURRENCY,
    'MISMATCH' as ISSUE
FROM MC
INNER JOIN CUSTOMER C ON MC.AC_NUM = C.CODE
WHERE MC.CURRENCY IS NOT NULL 
  AND C.CURRENCY IS NOT NULL 
  AND MC.CURRENCY != C.CURRENCY
ORDER BY MC.MCS_Num DESC;

-- ========================================
-- Test 4: Verify Specific MC (Replace MCS_NUM)
-- ========================================
-- Use this to check a specific Master Card you just created/updated
-- Replace 'YOUR_MCS_NUMBER' with actual MCS number
SELECT 
    MC.MCS_Num,
    MC.AC_NUM,
    MC.AC_NAME,
    MC.MODEL,
    MC.CURRENCY as MC_CURRENCY,
    C.NAME as CUSTOMER_NAME,
    C.CURRENCY as CUSTOMER_CURRENCY,
    CASE 
        WHEN MC.CURRENCY = C.CURRENCY THEN '✓ PASS - Currency matches customer' 
        WHEN MC.CURRENCY IS NULL AND C.CURRENCY IS NOT NULL THEN '✗ FAIL - Currency is NULL but customer has currency'
        WHEN MC.CURRENCY IS NULL AND C.CURRENCY IS NULL THEN '⚠ WARNING - Both currencies are NULL'
        ELSE '✗ FAIL - Currency mismatch' 
    END as TEST_RESULT
FROM MC
INNER JOIN CUSTOMER C ON MC.AC_NUM = C.CODE
WHERE MC.MCS_Num = 'YOUR_MCS_NUMBER';

-- Example:
-- WHERE MC.MCS_Num = 'TEST001';

-- ========================================
-- Test 5: Check All Currency Fields
-- ========================================
-- Comprehensive check for a specific MC
SELECT 
    'MCS Number' as Field, MC.MCS_Num as Value
FROM MC WHERE MC.MCS_Num = 'YOUR_MCS_NUMBER'
UNION ALL
SELECT 'Customer Code', MC.AC_NUM FROM MC WHERE MC.MCS_Num = 'YOUR_MCS_NUMBER'
UNION ALL
SELECT 'Customer Name (AC_NAME)', MC.AC_NAME FROM MC WHERE MC.MCS_Num = 'YOUR_MCS_NUMBER'
UNION ALL
SELECT 'MC Currency', COALESCE(MC.CURRENCY, '(NULL)') FROM MC WHERE MC.MCS_Num = 'YOUR_MCS_NUMBER'
UNION ALL
SELECT 'Customer Currency', COALESCE(C.CURRENCY, '(NULL)') 
FROM MC 
INNER JOIN CUSTOMER C ON MC.AC_NUM = C.CODE 
WHERE MC.MCS_Num = 'YOUR_MCS_NUMBER'
UNION ALL
SELECT 'Match Status', 
    CASE 
        WHEN MC.CURRENCY = C.CURRENCY THEN '✓ MATCH'
        ELSE '✗ NO MATCH'
    END
FROM MC 
INNER JOIN CUSTOMER C ON MC.AC_NUM = C.CODE 
WHERE MC.MCS_Num = 'YOUR_MCS_NUMBER';

-- ========================================
-- Test 6: Sample Customers with Currency
-- ========================================
-- View customers that have currency set for testing
SELECT 
    CODE,
    NAME,
    CURRENCY,
    CASE 
        WHEN CURRENCY IS NOT NULL THEN '✓ Has Currency'
        ELSE '✗ No Currency'
    END as STATUS
FROM CUSTOMER
WHERE CURRENCY IS NOT NULL
ORDER BY CODE
LIMIT 20;

-- ========================================
-- Expected Results Guide
-- ========================================
/*
Test 1: Should show all recent MCs with STATUS = '✓ CORRECT'
Test 2: Should return 0 rows (no missing currencies)
Test 3: Should return 0 rows (no mismatches)
Test 4: Should show '✓ PASS - Currency matches customer'
Test 5: Should show matching currency values
Test 6: Should show customers available for testing

If any test fails:
1. Check if customer has currency set in CUSTOMER table
2. Re-save the Master Card
3. Clear browser cache and try again
4. Check application logs for errors
*/
