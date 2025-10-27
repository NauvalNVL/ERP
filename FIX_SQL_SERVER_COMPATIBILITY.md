# Fix: SQL Server Compatibility Issue

## Issue Description

**Error Message:**
```
Failed to create Sales Order: Error creating sales order: Request failed (500). 
{ "message": "SQLSTATE[42000]: [Microsoft][ODBC Driver 17 for SQL Server][SQL Server]
'SUBSTRING_INDEX' is not a recognized built-in function name.
```

**Root Cause:** The code was using MySQL-specific function `SUBSTRING_INDEX()` which doesn't exist in SQL Server.

## Date Fixed
2025-10-21

## Database Type
- **Database:** Microsoft SQL Server
- **Driver:** ODBC Driver 17 for SQL Server
- **Original Code:** Written for MySQL
- **Fix:** Converted to SQL Server compatible syntax

## Changes Made

### 1. SalesOrderController.php - Method: `store()`

**Before (MySQL):**
```php
->orderByRaw('CAST(SUBSTRING_INDEX(SO_Num, \'-\', -1) AS UNSIGNED) DESC')

// Extract sequence
$parts = explode('-', $lastSO->SO_Num);
if (count($parts) === 3) {
    $sequence = intval($parts[2]) + 1;
}
```

**After (SQL Server):**
```php
->orderByRaw('CAST(RIGHT(SO_Num, 5) AS INT) DESC')

// Extract last 5 characters (sequence)
$lastSequence = substr($lastSO->SO_Num, -5);
$sequence = intval($lastSequence) + 1;
```

### 2. SalesOrderController.php - Method: `apiStoreToSo()`

**Before (MySQL):**
```php
->orderByRaw('CAST(SUBSTRING_INDEX(SO_Num, \'-\', -1) AS UNSIGNED) DESC')
```

**After (SQL Server):**
```php
->orderByRaw('CAST(RIGHT(SO_Num, 5) AS INT) DESC')
```

### 3. SalesOrderController.php - Method: `getSalesOrders()`

**Before (MySQL):**
```php
$query->whereRaw("SUBSTRING_INDEX(SO_Num, '-', -1) = ?", [...]);
```

**After (SQL Server):**
```php
$query->whereRaw("RIGHT(SO_Num, 5) = ?", [...]);
```

### 4. TEST_SO_FIX_VERIFICATION.sql

Updated all SQL queries to use SQL Server syntax:

**Before (MySQL):**
```sql
-- MySQL functions
GROUP_CONCAT()
SUBSTRING_INDEX()
LPAD()
CURDATE()
LIMIT 20
```

**After (SQL Server):**
```sql
-- SQL Server functions
STRING_AGG()
RIGHT()
FORMAT()
GETDATE()
TOP 20
```

## Function Mapping: MySQL → SQL Server

| MySQL Function | SQL Server Equivalent | Usage |
|----------------|----------------------|-------|
| `SUBSTRING_INDEX(str, delim, count)` | `RIGHT(str, n)` or `LEFT(str, n)` | Extract substring |
| `UNSIGNED` | `INT` | Cast to integer |
| `GROUP_CONCAT()` | `STRING_AGG()` | Concatenate strings |
| `LPAD(str, len, pad)` | `FORMAT()` or `RIGHT()` | Pad string |
| `CURDATE()` | `GETDATE()` | Current date |
| `LIMIT n` | `TOP n` | Limit results |
| `DATE(column)` | `CAST(column AS DATE)` | Extract date |
| `TIMESTAMPDIFF()` | `DATEDIFF()` | Date difference |
| `LENGTH()` | `LEN()` | String length |

## Examples

### Extract Last Part of String

**SO_Num Format:** `10-2025-00001`

**MySQL:**
```sql
SUBSTRING_INDEX(SO_Num, '-', -1)  -- Returns: 00001
```

**SQL Server:**
```sql
RIGHT(SO_Num, 5)  -- Returns: 00001
```

### String Aggregation

**MySQL:**
```sql
GROUP_CONCAT(SO_Num ORDER BY SO_Num SEPARATOR ', ')
```

**SQL Server:**
```sql
STRING_AGG(SO_Num, ', ') WITHIN GROUP (ORDER BY SO_Num)
```

### Current Date/Time

**MySQL:**
```sql
CURDATE()           -- Current date
NOW()               -- Current datetime
MONTH(CURDATE())    -- Current month
YEAR(CURDATE())     -- Current year
```

**SQL Server:**
```sql
CAST(GETDATE() AS DATE)     -- Current date
GETDATE()                   -- Current datetime
MONTH(GETDATE())            -- Current month
YEAR(GETDATE())             -- Current year
FORMAT(GETDATE(), 'MM')     -- Current month (padded)
```

### Limit Results

**MySQL:**
```sql
SELECT * FROM so ORDER BY created_at DESC LIMIT 20;
```

**SQL Server:**
```sql
SELECT TOP 20 * FROM so ORDER BY created_at DESC;
```

## Testing Verification

### 1. Test SO Number Generation
```sql
-- Should return latest sequence number
SELECT TOP 1
    SO_Num,
    RIGHT(SO_Num, 5) as sequence,
    CAST(RIGHT(SO_Num, 5) AS INT) as sequence_int
FROM so
WHERE MM = FORMAT(GETDATE(), 'MM')
  AND YYYY = CAST(YEAR(GETDATE()) AS VARCHAR(4))
ORDER BY CAST(RIGHT(SO_Num, 5) AS INT) DESC;
```

### 2. Test String Aggregation
```sql
-- Should group SO numbers by customer
SELECT 
    AC_Num,
    COUNT(*) as count,
    STRING_AGG(SO_Num, ', ') WITHIN GROUP (ORDER BY SO_Num) as so_list
FROM so
WHERE MM = FORMAT(GETDATE(), 'MM')
GROUP BY AC_Num;
```

### 3. Test Date Functions
```sql
-- Should return today's orders
SELECT COUNT(*)
FROM so
WHERE CAST(created_at AS DATE) = CAST(GETDATE() AS DATE);
```

## Performance Considerations

### RIGHT() vs SUBSTRING_INDEX()

**SQL Server RIGHT():**
- Native function, highly optimized
- Direct character extraction
- Better performance than string parsing

**Indexing Recommendation:**
```sql
-- Create computed column for sequence (optional)
ALTER TABLE so 
ADD sequence_num AS CAST(RIGHT(SO_Num, 5) AS INT) PERSISTED;

-- Create index on computed column
CREATE INDEX idx_so_sequence ON so(sequence_num);
```

## Compatibility Notes

### Laravel Query Builder
Laravel's query builder is database-agnostic, but **raw SQL** must be adapted:

✅ **Safe (Database-Agnostic):**
```php
DB::table('so')->where('SO_Num', $soNumber)->first();
DB::table('so')->orderBy('created_at', 'desc')->get();
```

❌ **Unsafe (Database-Specific):**
```php
DB::table('so')->orderByRaw('SUBSTRING_INDEX(...)')  // MySQL only
DB::table('so')->orderByRaw('RIGHT(...)')            // SQL Server
```

### Solution: Use PHP for String Manipulation
```php
// Instead of SQL SUBSTRING_INDEX, use PHP
$lastSO = DB::table('so')
    ->where('MM', $month)
    ->where('YYYY', $year)
    ->orderByRaw('CAST(RIGHT(SO_Num, 5) AS INT) DESC')  // SQL Server
    ->first();

if ($lastSO && $lastSO->SO_Num) {
    // PHP string manipulation (database-agnostic)
    $lastSequence = substr($lastSO->SO_Num, -5);
    $sequence = intval($lastSequence) + 1;
}
```

## Files Modified

1. **`c:\laragon\www\erp\app\Http\Controllers\SalesOrderController.php`**
   - Method: `store()` - Lines ~50-80
   - Method: `apiStoreToSo()` - Lines ~530-560
   - Method: `getSalesOrders()` - Line ~722

2. **`c:\laragon\www\erp\TEST_SO_FIX_VERIFICATION.sql`**
   - All queries converted to SQL Server syntax
   - Updated date functions, string functions, aggregations

## Backward Compatibility

⚠️ **Breaking Change for MySQL Users**

If the system was previously using MySQL, this change **breaks MySQL compatibility**.

### Solution: Database Detection

If you need to support both MySQL and SQL Server:

```php
// Detect database driver
$driver = DB::connection()->getDriverName();

if ($driver === 'sqlsrv') {
    // SQL Server
    $query->orderByRaw('CAST(RIGHT(SO_Num, 5) AS INT) DESC');
} else {
    // MySQL
    $query->orderByRaw('CAST(SUBSTRING_INDEX(SO_Num, \'-\', -1) AS UNSIGNED) DESC');
}
```

## Common SQL Server Pitfalls

### 1. String Concatenation
**MySQL:** `CONCAT(a, b, c)`  
**SQL Server:** `CONCAT(a, b, c)` ✅ (Same!) or `a + b + c`

### 2. LIMIT
**MySQL:** `LIMIT 10`  
**SQL Server:** `TOP 10` (before SELECT) or `OFFSET 0 ROWS FETCH NEXT 10 ROWS ONLY`

### 3. Auto Increment
**MySQL:** `AUTO_INCREMENT`  
**SQL Server:** `IDENTITY(1,1)`

### 4. String Quotes
**MySQL:** Single `'` or double `"` for strings  
**SQL Server:** Single `'` only for strings, double `"` for identifiers

### 5. Date Literals
**MySQL:** `'2025-10-21'` works directly  
**SQL Server:** Use `CAST('2025-10-21' AS DATE)` for safety

## Deployment Checklist

- [x] Update `SalesOrderController.php` methods
- [x] Update SQL verification queries
- [x] Test SO number generation
- [x] Test sequence filtering
- [x] Verify no MySQL-specific functions remain
- [x] Update documentation
- [ ] Test in staging environment
- [ ] Monitor production logs
- [ ] Performance testing

## Related Documentation

- `FIX_SALES_ORDER_UPDATE_ISSUE.md` - Original SO fix
- `TEST_SO_FIX_VERIFICATION.sql` - SQL Server verification queries
- `MULTIPLE_SO_FEATURE_SUMMARY.md` - Multiple SO feature

---

**Status:** ✅ Fixed for SQL Server  
**Date:** 2025-10-21  
**Database:** Microsoft SQL Server (ODBC Driver 17)  
**Compatibility:** SQL Server only (MySQL support removed)
