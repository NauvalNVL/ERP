-- ============================================================================
-- Test Data for Customer Sales Tax Index Flow
-- Matching CPS ERP Behavior
-- ============================================================================

-- Step 1: Insert Tax Groups
-- ============================================================================
INSERT INTO tax_groups (code, name, sales_tax_applied, created_at, updated_at) VALUES
('PPN', 'PPN 10%', 'Y', NOW(), NOW()),
('PPN11', 'PPN 11%', 'Y', NOW(), NOW()),
('EXEMPT', 'Tax Exempt', 'N', NOW(), NOW())
ON DUPLICATE KEY UPDATE 
    name = VALUES(name),
    sales_tax_applied = VALUES(sales_tax_applied),
    updated_at = NOW();

-- Step 2: Insert Tax Types
-- ============================================================================
INSERT INTO tax_types (code, name, rate, apply, tax_group_code, created_at, updated_at) VALUES
('PPN', 'PPN 10%', 10.00, 'Y', 'PPN', NOW(), NOW()),
('PPN11', 'PPN 11%', 11.00, 'Y', 'PPN11', NOW(), NOW()),
('PPNBM', 'PPNBM 20%', 20.00, 'Y', 'PPN', NOW(), NOW()),
('EXEMPT', 'Tax Exempt', 0.00, 'Y', 'EXEMPT', NOW(), NOW())
ON DUPLICATE KEY UPDATE 
    name = VALUES(name),
    rate = VALUES(rate),
    apply = VALUES(apply),
    tax_group_code = VALUES(tax_group_code),
    updated_at = NOW();

-- Step 3: Insert Customer Sales Tax Indices
-- ============================================================================
-- Example for customer 000211-08
INSERT INTO customer_sales_tax_indices (customer_code, index_number, tax_group_code, status, created_at, updated_at) VALUES
('000211-08', 1, 'PPN', 'A', NOW(), NOW()),
('000211-08', 2, 'PPN11', 'A', NOW(), NOW()),
('000211-08', 3, 'EXEMPT', 'O', NOW(), NOW())
ON DUPLICATE KEY UPDATE 
    tax_group_code = VALUES(tax_group_code),
    status = VALUES(status),
    updated_at = NOW();

-- ============================================================================
-- Verification Queries
-- ============================================================================

-- 1. Check Tax Groups
SELECT 
    code,
    name,
    sales_tax_applied,
    (SELECT COUNT(*) FROM tax_types WHERE tax_group_code = tax_groups.code) as tax_types_count
FROM tax_groups
ORDER BY code;

-- Expected output:
-- code    | name        | sales_tax_applied | tax_types_count
-- --------|-------------|-------------------|----------------
-- EXEMPT  | Tax Exempt  | N                 | 1
-- PPN     | PPN 10%     | Y                 | 2
-- PPN11   | PPN 11%     | Y                 | 1

-- 2. Check Tax Types with Tax Groups
SELECT 
    tt.code,
    tt.name,
    tt.rate,
    tt.apply,
    tt.tax_group_code,
    tg.name as tax_group_name
FROM tax_types tt
LEFT JOIN tax_groups tg ON tt.tax_group_code = tg.code
ORDER BY tt.tax_group_code, tt.code;

-- Expected output:
-- code   | name        | rate  | apply | tax_group_code | tax_group_name
-- -------|-------------|-------|-------|----------------|---------------
-- EXEMPT | Tax Exempt  | 0.00  | Y     | EXEMPT         | Tax Exempt
-- PPN    | PPN 10%     | 10.00 | Y     | PPN            | PPN 10%
-- PPNBM  | PPNBM 20%   | 20.00 | Y     | PPN            | PPN 10%
-- PPN11  | PPN 11%     | 11.00 | Y     | PPN11          | PPN 11%

-- 3. Check Customer Tax Indices
SELECT 
    csti.customer_code,
    csti.index_number,
    csti.tax_group_code,
    tg.name as tax_group_name,
    csti.status,
    CASE WHEN csti.status = 'A' THEN 'Active' ELSE 'Obsolete' END as status_label
FROM customer_sales_tax_indices csti
JOIN tax_groups tg ON csti.tax_group_code = tg.code
WHERE csti.customer_code = '000211-08'
ORDER BY csti.index_number;

-- Expected output:
-- customer_code | index_number | tax_group_code | tax_group_name | status | status_label
-- --------------|--------------|----------------|----------------|--------|-------------
-- 000211-08     | 1            | PPN            | PPN 10%        | A      | Active
-- 000211-08     | 2            | PPN11          | PPN 11%        | A      | Active
-- 000211-08     | 3            | EXEMPT         | Tax Exempt     | O      | Obsolete

-- 4. Full Flow Verification (what user will see in Check Sales Tax)
SELECT 
    csti.customer_code,
    csti.index_number,
    csti.tax_group_code,
    tg.name as tax_group_name,
    tt.code as tax_code,
    tt.name as tax_name,
    tt.rate as tax_rate,
    tt.apply
FROM customer_sales_tax_indices csti
JOIN tax_groups tg ON csti.tax_group_code = tg.code
JOIN tax_types tt ON tg.code = tt.tax_group_code
WHERE csti.customer_code = '000211-08'
  AND csti.index_number = 1
  AND csti.status = 'A'
ORDER BY tt.code;

-- Expected output for Index 01 (PPN):
-- customer_code | index_number | tax_group_code | tax_group_name | tax_code | tax_name    | tax_rate | apply
-- --------------|--------------|----------------|----------------|----------|-------------|----------|------
-- 000211-08     | 1            | PPN            | PPN 10%        | PPN      | PPN 10%     | 10.00    | Y
-- 000211-08     | 1            | PPN            | PPN 10%        | PPNBM    | PPNBM 20%   | 20.00    | Y

-- ============================================================================
-- Cleanup (if needed)
-- ============================================================================

-- Remove test data
-- DELETE FROM customer_sales_tax_indices WHERE customer_code = '000211-08';
-- DELETE FROM tax_types WHERE tax_group_code IN ('PPN', 'PPN11', 'EXEMPT');
-- DELETE FROM tax_groups WHERE code IN ('PPN', 'PPN11', 'EXEMPT');

-- ============================================================================
-- API Testing URLs
-- ============================================================================

-- Test these URLs in browser or Postman:

-- 1. Get Customer Tax Indices:
--    GET http://127.0.0.1:8000/api/invoices/customer-tax-indices/000211-08

-- 2. Get Tax Items for Tax Group PPN:
--    GET http://127.0.0.1:8000/api/invoices/tax-groups/PPN/tax-items

-- 3. Get Tax Items for Tax Group PPN11:
--    GET http://127.0.0.1:8000/api/invoices/tax-groups/PPN11/tax-items

-- ============================================================================
-- Expected API Responses
-- ============================================================================

-- GET /api/invoices/customer-tax-indices/000211-08
/*
{
  "success": true,
  "data": [
    {
      "id": 1,
      "customer_code": "000211-08",
      "index_number": 1,
      "tax_group_code": "PPN",
      "status": "A",
      "tax_group": {
        "code": "PPN",
        "name": "PPN 10%",
        "sales_tax_applied": "Y"
      }
    },
    {
      "id": 2,
      "customer_code": "000211-08",
      "index_number": 2,
      "tax_group_code": "PPN11",
      "status": "A",
      "tax_group": {
        "code": "PPN11",
        "name": "PPN 11%",
        "sales_tax_applied": "Y"
      }
    }
  ]
}
*/

-- GET /api/invoices/tax-groups/PPN/tax-items
/*
[
  {
    "tax_code": "PPN",
    "tax_name": "PPN 10%",
    "rate": 10.0,
    "include": false,
    "status": "A",
    "apply": true
  },
  {
    "tax_code": "PPNBM",
    "tax_name": "PPNBM 20%",
    "rate": 20.0,
    "include": false,
    "status": "A",
    "apply": true
  }
]
*/

-- ============================================================================
-- Common Issues and Solutions
-- ============================================================================

/*
Issue 1: "No active tax codes found" in Check Sales Tax Screen
-----------------------------------------------------------------
Problem: Tax Group doesn't have tax types assigned
Solution: Ensure tax_types.tax_group_code is set

Check:
SELECT code, name, tax_group_code FROM tax_types WHERE code IN ('PPN', 'PPNBM');

Fix:
UPDATE tax_types SET tax_group_code = 'PPN' WHERE code = 'PPN';
UPDATE tax_types SET tax_group_code = 'PPN' WHERE code = 'PPNBM';


Issue 2: Tax Index Table is empty
-----------------------------------------------------------------
Problem: No customer_sales_tax_indices records
Solution: Create indices using Define Customer Sales Tax Index menu or SQL

Fix:
INSERT INTO customer_sales_tax_indices 
(customer_code, index_number, tax_group_code, status, created_at, updated_at) 
VALUES ('000211-08', 1, 'PPN', 'A', NOW(), NOW());


Issue 3: selectedIndexData is null
-----------------------------------------------------------------
Problem: Tax Index not selected from Customer Sales Tax Index Table
Solution: Ensure user clicks tax index from the modal before continuing

Check browser console for:
✅ Selected Tax Index Data: {index_number: 1, tax_group_code: "PPN", ...}
*/

-- ============================================================================
-- End of Test Data Script
-- ============================================================================
