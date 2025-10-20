-- ========================================
-- SALESPERSON DEBUG QUERIES
-- ========================================

-- Query 1: Check DO table for salesperson (SLM field)
-- This should be the PRIMARY source
SELECT 
    DO_Num as delivery_order,
    AC_Num as customer_code,
    AC_Name as customer_name,
    SLM as salesperson_from_DO,
    Area1 as area,
    DO_Remark1,
    DO_Remark2
FROM DO
WHERE AC_Num = '000004'
  AND DO_Num = '2025-10-00006'
ORDER BY DO_DMY DESC;

-- Expected: SLM should have value like 'S118'
-- If NULL or empty, this is the problem!


-- Query 2: Check CUSTOMER table for salesperson (SLM field)
-- This is the FALLBACK source
SELECT 
    CODE as customer_code,
    NAME as customer_name,
    SLM as salesperson_from_CUSTOMER,
    AREA as area,
    CURRENCY
FROM CUSTOMER
WHERE CODE = '000004';

-- Expected: SLM should have value like 'S118'


-- Query 3: Check salesperson_teams table for full name
SELECT 
    s_person_code,
    salesperson_name,
    status
FROM salesperson_teams
WHERE s_person_code = 'S118';

-- Expected: Should return 'ROBERT PURBA' or similar


-- Query 4: Check ALL delivery orders for customer 000004
SELECT 
    DO_Num,
    DO_DMY,
    AC_Num,
    AC_Name,
    SLM,
    Status
FROM DO
WHERE AC_Num = '000004'
ORDER BY DO_DMY DESC
LIMIT 10;

-- This shows if ANY DO has SLM populated


-- Query 5: Fix missing salesperson in DO table
-- Run this ONLY if Query 1 shows SLM is NULL
UPDATE DO 
SET SLM = 'S118'  -- Replace with actual salesperson code
WHERE DO_Num = '2025-10-00006'
  AND AC_Num = '000004';

-- Verify update
SELECT DO_Num, AC_Num, SLM 
FROM DO 
WHERE DO_Num = '2025-10-00006';


-- Query 6: Fix missing salesperson in CUSTOMER table
-- Run this ONLY if Query 2 shows SLM is NULL
UPDATE CUSTOMER 
SET SLM = 'S118'  -- Replace with actual salesperson code
WHERE CODE = '000004';

-- Verify update
SELECT CODE, NAME, SLM 
FROM CUSTOMER 
WHERE CODE = '000004';


-- Query 7: Check if multiple rows exist for same DO_Num
SELECT 
    DO_Num,
    COUNT(*) as row_count,
    COUNT(DISTINCT SLM) as distinct_salesperson_count,
    STRING_AGG(DISTINCT SLM, ', ') as salesperson_values
FROM DO
WHERE DO_Num = '2025-10-00006'
GROUP BY DO_Num;

-- If row_count > 1, DO has multiple line items
-- All should have same SLM value
