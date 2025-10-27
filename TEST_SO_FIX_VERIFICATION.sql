-- SQL Verification Queries for Sales Order Fix
-- Date: 2025-10-21
-- Purpose: Verify that multiple sales orders can be created for same customer/MC
-- Database: SQL Server

-- ==================================================
-- 1. Check for Duplicate SO Numbers
-- Expected Result: 0 rows (no duplicates)
-- ==================================================
SELECT 
    SO_Num,
    COUNT(*) as duplicate_count,
    STRING_AGG(AC_Num, ', ') as customers,
    STRING_AGG(MCS_Num, ', ') as master_cards
FROM so 
GROUP BY SO_Num 
HAVING COUNT(*) > 1;

-- If any results appear, there are duplicates (BAD!)
-- Empty result = All SO numbers are unique (GOOD!)


-- ==================================================
-- 2. View Recent Sales Orders (Last 20)
-- Expected Result: Sequential SO numbers without gaps
-- ==================================================
SELECT TOP 20
    SO_Num,
    AC_Num,
    AC_NAME,
    MCS_Num,
    PO_Num,
    SO_QTY,
    UNIT_PRICE,
    AMOUNT,
    STS,
    created_at
FROM so
WHERE MM = FORMAT(GETDATE(), 'MM')
  AND YYYY = CAST(YEAR(GETDATE()) AS VARCHAR(4))
ORDER BY CAST(RIGHT(SO_Num, 5) AS INT) DESC;

-- Check if sequence numbers are incrementing properly
-- Format: MM-YYYY-00001, MM-YYYY-00002, etc.


-- ==================================================
-- 3. Count Orders by Customer and Master Card
-- Expected Result: Multiple orders per customer/MC combination
-- ==================================================
SELECT 
    AC_Num as customer_code,
    AC_NAME as customer_name,
    MCS_Num as master_card_seq,
    COUNT(*) as total_orders,
    STRING_AGG(SO_Num, ', ') WITHIN GROUP (ORDER BY SO_Num) as so_numbers,
    MIN(created_at) as first_order,
    MAX(created_at) as last_order
FROM so
WHERE MM = FORMAT(GETDATE(), 'MM')
  AND YYYY = CAST(YEAR(GETDATE()) AS VARCHAR(4))
GROUP BY AC_Num, AC_NAME, MCS_Num
HAVING COUNT(*) > 1
ORDER BY total_orders DESC, customer_code;

-- This shows customers with multiple orders using same master card
-- After fix, you should see multiple orders per customer/MC


-- ==================================================
-- 4. Verify Sequence Numbering is Correct
-- Expected Result: No gaps in sequence (or gaps are intentional)
-- ==================================================
SELECT 
    CAST(RIGHT(SO_Num, 5) AS INT) as sequence_num,
    SO_Num,
    AC_Num,
    MCS_Num,
    PO_Num,
    created_at
FROM so
WHERE MM = FORMAT(GETDATE(), 'MM')
  AND YYYY = CAST(YEAR(GETDATE()) AS VARCHAR(4))
ORDER BY sequence_num;

-- Check if sequences are: 1, 2, 3, 4, 5... (consecutive)


-- ==================================================
-- 5. Find Test Data (Same Customer & MC, Multiple Orders)
-- Expected Result: Multiple rows with same AC_Num and MCS_Num
-- ==================================================
SELECT 
    SO_Num,
    AC_Num,
    AC_NAME,
    MCS_Num,
    PO_Num,
    SO_QTY,
    created_at,
    DATEDIFF(SECOND, 
        LAG(created_at) OVER (PARTITION BY AC_Num, MCS_Num ORDER BY created_at), 
        created_at
    ) as seconds_since_prev_order
FROM so
WHERE MM = FORMAT(GETDATE(), 'MM')
  AND YYYY = CAST(YEAR(GETDATE()) AS VARCHAR(4))
ORDER BY AC_Num, MCS_Num, created_at;

-- This shows time difference between orders for same customer/MC
-- Rapid creation (< 60 seconds) indicates batch testing


-- ==================================================
-- 6. Check Latest SO Number Format
-- Expected Result: MM-YYYY-XXXXX format
-- ==================================================
SELECT TOP 10
    SO_Num,
    LEFT(SO_Num, CHARINDEX('-', SO_Num) - 1) as month_part,
    SUBSTRING(SO_Num, CHARINDEX('-', SO_Num) + 1, 4) as year_part,
    RIGHT(SO_Num, 5) as sequence_part,
    LEN(RIGHT(SO_Num, 5)) as sequence_length,
    created_at
FROM so
ORDER BY created_at DESC;

-- Verify format:
-- - month_part: 2 digits (01-12)
-- - year_part: 4 digits (2025)
-- - sequence_part: 5 digits (00001, 00002, etc.)
-- - sequence_length: should be 5


-- ==================================================
-- 7. Audit Trail - Orders Created Today
-- Expected Result: All new orders from today's testing
-- ==================================================
SELECT 
    SO_Num,
    AC_Num,
    AC_NAME,
    MCS_Num,
    PO_Num,
    SO_QTY,
    AMOUNT,
    NW_UID as created_by,
    NW_DATE as created_date,
    NW_TIME as created_time,
    created_at
FROM so
WHERE CAST(created_at AS DATE) = CAST(GETDATE() AS DATE)
ORDER BY created_at DESC;

-- Shows all orders created today with full audit info


-- ==================================================
-- 8. Compare Old vs New Format SO Numbers
-- Expected Result: New format (MM-YYYY-XXXXX) and old format (if any)
-- ==================================================
SELECT 
    CASE 
        WHEN SO_Num LIKE '__-____-_____' THEN 'New Format (MM-YYYY-XXXXX)'
        WHEN SO_Num LIKE 'SO-________-____' THEN 'Old Format (SO-YYYYMMDD-XXXX)'
        ELSE 'Unknown Format'
    END as so_format,
    COUNT(*) as count,
    MIN(SO_Num) as first_so,
    MAX(SO_Num) as last_so,
    MIN(created_at) as oldest_order,
    MAX(created_at) as newest_order
FROM so
GROUP BY so_format
ORDER BY newest_order DESC;


-- ==================================================
-- 9. Monthly Statistics
-- Expected Result: Order count per month showing growth
-- ==================================================
SELECT 
    CONCAT(MM, '-', YYYY) as period,
    COUNT(*) as total_orders,
    COUNT(DISTINCT AC_Num) as unique_customers,
    COUNT(DISTINCT MCS_Num) as unique_master_cards,
    SUM(SO_QTY) as total_quantity,
    SUM(AMOUNT) as total_amount,
    MIN(SO_Num) as first_so,
    MAX(SO_Num) as last_so
FROM so
GROUP BY YYYY, MM
ORDER BY YYYY DESC, MM DESC
LIMIT 12;


-- ==================================================
-- 10. BEFORE/AFTER Test Comparison
-- Run this BEFORE testing, then AFTER testing
-- ==================================================

-- BEFORE: Record current state
-- Copy these results for comparison
DECLARE @total_orders_before INT;
DECLARE @unique_customers_before INT;
DECLARE @max_sequence_before INT;

SELECT 
    @total_orders_before = COUNT(*),
    @unique_customers_before = COUNT(DISTINCT AC_Num),
    @max_sequence_before = MAX(CAST(RIGHT(SO_Num, 5) AS INT))
FROM so
WHERE MM = FORMAT(GETDATE(), 'MM')
  AND YYYY = CAST(YEAR(GETDATE()) AS VARCHAR(4));

SELECT 
    @total_orders_before as total_orders_before,
    @unique_customers_before as unique_customers_before,
    @max_sequence_before as max_sequence_before;

-- Create 3 test orders for same customer/MC using PrepareMCSO.vue
-- Then run this query:

-- AFTER: Compare with before
SELECT 
    COUNT(*) as total_orders_after,
    COUNT(DISTINCT AC_Num) as unique_customers_after,
    MAX(CAST(RIGHT(SO_Num, 5) AS INT)) as max_sequence_after,
    -- Expected increase:
    COUNT(*) - @total_orders_before as orders_created,
    MAX(CAST(RIGHT(SO_Num, 5) AS INT)) - @max_sequence_before as sequence_increase
FROM so
WHERE MM = FORMAT(GETDATE(), 'MM')
  AND YYYY = CAST(YEAR(GETDATE()) AS VARCHAR(4));

-- Expected: 
-- orders_created = 3 (if you created 3 test orders)
-- sequence_increase = 3


-- ==================================================
-- CLEANUP: Delete Test Orders (USE WITH CAUTION!)
-- ==================================================

-- DO NOT RUN IN PRODUCTION WITHOUT BACKUP!
-- Only use for cleaning up test data

/*
DELETE FROM so 
WHERE SO_Num IN (
    '10-2025-00001',
    '10-2025-00002',
    '10-2025-00003'
);
*/

-- Or delete by customer PO number:
/*
DELETE FROM so 
WHERE PO_Num IN ('TEST-001', 'TEST-002', 'TEST-003');
*/

-- Or delete today's test data:
/*
DELETE FROM so 
WHERE DATE(created_at) = CURDATE()
  AND PO_Num LIKE 'TEST-%';
*/


-- ==================================================
-- VERIFICATION CHECKLIST
-- ==================================================

/*
✅ Run Query #1: Should return 0 rows (no duplicates)
✅ Run Query #2: Should show sequential SO numbers
✅ Run Query #3: Should show multiple orders per customer/MC
✅ Run Query #4: Should show no gaps in sequences
✅ Run Query #5: Should show time gaps between orders
✅ Run Query #6: Should confirm MM-YYYY-XXXXX format
✅ Run Query #7: Should show today's orders
✅ Run Query #10: BEFORE count vs AFTER count should match test orders created

If all checks pass: ✅ Fix is working correctly!
If any check fails: ❌ Review SalesOrderController.php implementation
*/
