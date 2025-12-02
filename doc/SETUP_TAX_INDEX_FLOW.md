# Setup Customer Sales Tax Index Flow (CPS Compatible)

## Overview
This guide explains how to setup and use the Customer Sales Tax Index functionality, matching CPS ERP behavior.

## Prerequisites

### 1. Define Tax Types
**Menu:** `Define Tax Type`

Create tax types that will be used in tax groups:
```
Code: PPN
Name: PPN 10%
Rate: 10.00
Apply: Y
```

```
Code: PPN11
Name: PPN 11%  
Rate: 11.00
Apply: Y
```

### 2. Define Tax Group
**Menu:** `Define Tax Group`

1. Create Tax Group:
   ```
   Code: PPN
   Name: PPN 10%
   Sales Tax Applied: Y
   ```

2. **IMPORTANT:** Assign Tax Types to Tax Group:
   - Select Tax Group: PPN
   - Click "Tax Item Screen" button
   - Select tax types: PPN, PPN11
   - Save

This creates the relationship:
```
Tax Group: PPN
├─ Tax Type: PPN (10%)
└─ Tax Type: PPN11 (11%)
```

### 3. Define Customer Sales Tax Index
**Menu:** `Define Customer Sales Tax Index`

1. Select Customer: 000211-08
2. Add new index:
   ```
   Index Number: 01
   Tax Group: PPN
   Status: Active
   ```
3. Save

This creates:
```
customer_sales_tax_indices:
- customer_code: 000211-08
- index_number: 1
- tax_group_code: PPN
- status: A
```

## Usage Flow (Prepare Invoice by D/Order)

### Step 1: Select Customer
```
Customer Code: 000211-08
```

### Step 2: Select Tax Index No
Click 🔍 next to Tax Index No → Opens "Customer's Sales Tax Index Table"

```
Customer's Sales Tax Index Table:
┌────────┬───────────┬──────────────────┬────────┐
│ Index# │ Tax Group │ Tax Group Name   │ Status │
├────────┼───────────┼──────────────────┼────────┤
│ 01     │ PPN       │ PPN 10%          │ Active │
└────────┴───────────┴──────────────────┴────────┘
```

Select Index 01 → Tax Index No = "01"

### Step 3: Continue to Prepare
Click "Continue to Prepare" → Opens "Check Sales Tax Screen"

```
Check Sales Tax Screen:
Tax Index No: 01
Tax Group: PPN - PPN 10%

┌─────────────┬───────────┬───────┬───────┬─────────┐
│ No Tax Code │ Name      │ Apply │ Tax%  │ Include │
├─────────────┼───────────┼───────┼───────┼─────────┤
│ PPN         │ PPN 10%   │ Yes   │ 10.00%│ No      │
│ PPN11       │ PPN 11%   │ Yes   │ 11.00%│ No      │
└─────────────┴───────────┴───────┴───────┴─────────┘
```

✅ This shows all tax types from Tax Group PPN

### Step 4: Select Tax & Continue
Select tax (e.g., PPN 10%) → Click OK → Continue with invoice preparation

## Database Relationships

```
customer_sales_tax_indices
├─ customer_code → customers.code
├─ tax_group_code → tax_groups.code
└─ tax_groups.code → tax_types.tax_group_code (1:many)
```

## API Endpoints

### 1. Get Customer Tax Indices
```
GET /api/invoices/customer-tax-indices/{customerCode}

Response:
{
  "success": true,
  "data": [
    {
      "customer_code": "000211-08",
      "index_number": 1,
      "tax_group_code": "PPN",
      "status": "A",
      "tax_group": {
        "code": "PPN",
        "name": "PPN 10%"
      }
    }
  ]
}
```

### 2. Get Tax Items from Tax Group
```
GET /api/invoices/tax-groups/{code}/tax-items

Example: GET /api/invoices/tax-groups/PPN/tax-items

Response:
[
  {
    "tax_code": "PPN",
    "tax_name": "PPN 10%",
    "rate": 10.00,
    "include": false,
    "status": "A",
    "apply": true
  },
  {
    "tax_code": "PPN11",
    "tax_name": "PPN 11%",
    "rate": 11.00,
    "include": false,
    "status": "A",
    "apply": true
  }
]
```

## Troubleshooting

### Problem: "No active tax codes found" in Check Sales Tax Screen

**Cause:** Tax Group doesn't have any Tax Types assigned.

**Solution:**
1. Go to "Define Tax Group" menu
2. Select Tax Group (e.g., PPN)
3. Click "Tax Item Screen" button
4. Select tax types to include in this group
5. Save
6. Verify in database:
   ```sql
   SELECT * FROM tax_types WHERE tax_group_code = 'PPN';
   ```

### Problem: Tax Index Table is empty

**Cause:** No indices defined for customer.

**Solution:**
1. Go to "Define Customer Sales Tax Index" menu
2. Select customer
3. Add new index with tax group
4. Save

### Problem: Selected tax doesn't appear

**Cause:** `selectedIndexData` not passed to CheckSalesTaxModal.

**Solution:** Check browser console for:
```
✅ Selected Tax Index Data: {index_number: 1, tax_group_code: "PPN", ...}
📋 Fetching tax items for Tax Group: PPN
✅ Loaded tax items from Tax Group: [{...}]
```

## Testing Checklist

- [ ] Tax Types exist (Define Tax Type)
- [ ] Tax Group exists (Define Tax Group)
- [ ] Tax Types assigned to Tax Group (Tax Item Screen)
- [ ] Customer Tax Index created (Define Customer Sales Tax Index)
- [ ] Tax Index appears in Customer's Sales Tax Index Table
- [ ] Check Sales Tax shows correct tax items from Tax Group
- [ ] Can select tax and continue with invoice

## SQL Verification Queries

```sql
-- 1. Check Tax Group
SELECT * FROM tax_groups WHERE code = 'PPN';

-- 2. Check Tax Types in Group
SELECT code, name, rate, tax_group_code 
FROM tax_types 
WHERE tax_group_code = 'PPN';

-- 3. Check Customer Tax Indices
SELECT * FROM customer_sales_tax_indices 
WHERE customer_code = '000211-08';

-- 4. Full Join Verification
SELECT 
    csti.customer_code,
    csti.index_number,
    csti.tax_group_code,
    tg.name as tax_group_name,
    tt.code as tax_code,
    tt.name as tax_name,
    tt.rate
FROM customer_sales_tax_indices csti
JOIN tax_groups tg ON csti.tax_group_code = tg.code
JOIN tax_types tt ON tg.code = tt.tax_group_code
WHERE csti.customer_code = '000211-08'
ORDER BY csti.index_number, tt.code;
```

Expected result:
```
customer_code | index_number | tax_group_code | tax_group_name | tax_code | tax_name  | rate
------------- | ------------ | -------------- | -------------- | -------- | --------- | -----
000211-08     | 1            | PPN            | PPN 10%        | PPN      | PPN 10%   | 10.00
000211-08     | 1            | PPN            | PPN 10%        | PPN11    | PPN 11%   | 11.00
```

## CPS Compatibility Notes

This implementation matches CPS behavior:
1. ✅ Customer Sales Tax Index Table shows indices per customer
2. ✅ Each index links to a Tax Group
3. ✅ Check Sales Tax Screen shows tax items from selected Tax Group
4. ✅ Tax selection flows through to invoice preparation
5. ✅ Supports multiple tax indices per customer with different tax groups

---

**Last Updated:** Nov 3, 2025
**Version:** 1.0
