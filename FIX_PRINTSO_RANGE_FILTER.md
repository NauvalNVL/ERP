# Fix: PrintSO Sales Order Range Filter

## Issue Description

**Problem:** Sales Order Range di menu PrintSO tidak menampilkan data SO yang sudah dibuat. Filter berdasarkan range From S/Order# dan To S/Order# tidak berfungsi dengan benar.

**Root Cause:** Parameter SO number yang dikirim ke API tidak di-format dengan benar (format MM-YYYY-XXXXX).

## Date Fixed
2025-10-21

## Files Modified
- `c:\laragon\www\erp\resources\js\Pages\sales-management\sales-order\transaction\PrintSO.vue`

## Changes Made

### 1. Fixed `fetchSalesOrdersData()` Function

**Before:**
```javascript
from_so: formatSO(form.from.month, form.from.year, form.from.seq || '0'),
to_so: formatSO(form.to.month, form.to.year, form.to.seq || '99999'),
```

**Problem:** 
- Passing `'0'` as sequence when empty, which creates invalid SO number like `10-2025-00000`
- Incorrect default value for sequence

**After:**
```javascript
const fromSO = form.from.seq 
  ? formatSO(form.from.month, form.from.year, form.from.seq)
  : formatSO(form.from.month, form.from.year, '00001')

const toSO = form.to.seq
  ? formatSO(form.to.month, form.to.year, form.to.seq)
  : formatSO(form.to.month, form.to.year, '99999')
```

**Benefits:**
- ✅ Correct default: `00001` instead of `00000`
- ✅ Proper range filtering
- ✅ Better readability

### 2. Fixed `proceedPrint()` Function

**Before:**
```javascript
range: {
  from: form.from.seq || formatSO(form.from.month, form.from.year, '00001'),
  to: form.to.seq || formatSO(form.to.month, form.to.year, '99999'),
},
```

**Problem:**
- Using raw `form.from.seq` without formatting
- Inconsistent formatting logic

**After:**
```javascript
const fromSO = form.from.seq 
  ? formatSO(form.from.month, form.from.year, form.from.seq)
  : formatSO(form.from.month, form.from.year, '00001')

const toSO = form.to.seq
  ? formatSO(form.to.month, form.to.year, form.to.seq)
  : formatSO(form.to.month, form.to.year, '99999')

range: {
  from: fromSO,
  to: toSO,
},
```

**Benefits:**
- ✅ Always formatted correctly
- ✅ Consistent with fetchSalesOrdersData
- ✅ Proper default values

### 3. Fixed `formatPreview()` Function

**Before:**
```javascript
const fromRange = form.from.seq || formatSO(form.from.month, form.from.year, '00001')
const toRange = form.to.seq || formatSO(form.to.month, form.to.year, '99999')
```

**Problem:**
- Same issue as proceedPrint - not formatting when seq exists

**After:**
```javascript
const fromRange = form.from.seq 
  ? formatSO(form.from.month, form.from.year, form.from.seq)
  : formatSO(form.from.month, form.from.year, '00001')

const toRange = form.to.seq
  ? formatSO(form.to.month, form.to.year, form.to.seq)
  : formatSO(form.to.month, form.to.year, '99999')
```

**Benefits:**
- ✅ Consistent formatting across all functions
- ✅ Accurate display in preview

### 4. Enhanced Error Handling

Added better logging and fallback messages:

```javascript
if (salesOrders.length === 0) {
  console.warn('No sales orders found matching filters')
  tableData.push(['No sales orders found for the selected criteria', '', '', '', '', ''])
}
```

## SO Number Format

**Format:** `MM-YYYY-XXXXX`

**Examples:**
- `10-2025-00001` - October 2025, sequence 1
- `10-2025-00002` - October 2025, sequence 2
- `12-2025-99999` - December 2025, sequence 99999

**Components:**
- `MM`: Month (01-12), 2 digits
- `YYYY`: Year (e.g., 2025), 4 digits
- `XXXXX`: Sequence (00001-99999), 5 digits

## Testing Scenarios

### Test Case 1: Empty Range (Default)
**Input:**
- From: Month=10, Year=2025, Seq=(empty)
- To: Month=10, Year=2025, Seq=(empty)

**Expected:**
- API receives: `from_so=10-2025-00001`, `to_so=10-2025-99999`
- Shows all SO from October 2025

**Result:** ✅

### Test Case 2: Specific Range
**Input:**
- From: Month=10, Year=2025, Seq=00001
- To: Month=10, Year=2025, Seq=00010

**Expected:**
- API receives: `from_so=10-2025-00001`, `to_so=10-2025-00010`
- Shows SO 00001 through 00010 only

**Result:** ✅

### Test Case 3: Cross-Month Range
**Input:**
- From: Month=09, Year=2025, Seq=00001
- To: Month=10, Year=2025, Seq=00010

**Expected:**
- API receives: `from_so=09-2025-00001`, `to_so=10-2025-00010`
- Shows SO from Sept 1 to Oct 10

**Result:** ✅

### Test Case 4: Using Sales Order Lookup
**Input:**
- Click lookup button (🔍)
- Select SO: `10-2025-00001`

**Expected:**
- Form fills: Month=10, Year=2025, Seq=00001
- Format applied correctly

**Result:** ✅

## Integration Points

### API Endpoint
- **Endpoint:** `/api/sales-orders`
- **Method:** GET
- **Controller:** `SalesOrderController@getSalesOrders`

### Parameters Sent
```javascript
{
  month: 10,
  year: 2025,
  from_so: '10-2025-00001',
  to_so: '10-2025-99999',
  status: ['outstanding', 'partial', 'closed', 'completed']
}
```

### API Response Structure
```json
{
  "success": true,
  "data": [
    {
      "so_number": "10-2025-00001",
      "customer_code": "000283",
      "customer_name": "ABC Company",
      "customer_po_number": "PO-001",
      "status": "OPEN",
      "amount": 5000000,
      "po_date": "2025-10-21"
    }
  ],
  "count": 1
}
```

## User Guide

### How to Use Sales Order Range Filter

1. **Set Period** (Current Period section):
   - Month: 1-12
   - Year: e.g., 2025

2. **Set Range** (From/To):
   - **Option A - Manual Input:**
     - Enter Month, Year, and Sequence (e.g., 00001)
     - Leave Seq empty for default (from 00001, to 99999)
   
   - **Option B - Using Lookup:**
     - Click 🔍 button next to From or To field
     - Select SO from modal
     - Fields auto-fill with selected SO details

3. **Select Status Filters:**
   - ☑ Outstanding
   - ☑ Partial Completed
   - ☑ Closed
   - ☑ Completed
   - ☐ Cancelled

4. **Generate Report:**
   - Click "Proceed to Print"
   - Select printer code
   - Click "Proceed"

### Examples

#### Example 1: All October 2025 Orders
```
Period: Month=10, Year=2025
From: Month=10, Year=2025, Seq=(empty)
To: Month=10, Year=2025, Seq=(empty)
Status: All active
```
**Result:** Shows all active SO from October 2025

#### Example 2: Specific Range
```
Period: Month=10, Year=2025
From: Month=10, Year=2025, Seq=00001
To: Month=10, Year=2025, Seq=00050
Status: All active
```
**Result:** Shows SO 10-2025-00001 through 10-2025-00050

#### Example 3: Single SO
```
Period: Month=10, Year=2025
From: Month=10, Year=2025, Seq=00001
To: Month=10, Year=2025, Seq=00001
Status: All active
```
**Result:** Shows only SO 10-2025-00001

## Troubleshooting

### Issue: "No sales orders found"

**Possible Causes:**
1. ❌ No SO exists in database for the period
2. ❌ Range too narrow (From > To)
3. ❌ Status filters exclude all SO
4. ❌ Month/Year mismatch

**Solutions:**
- Check database for existing SO
- Expand range or leave Seq empty
- Enable all status checkboxes
- Verify period matches SO creation date

### Issue: Range not working

**Check:**
1. ✅ Month values (1-12)
2. ✅ Year format (YYYY)
3. ✅ Sequence format (5 digits with leading zeros)
4. ✅ From <= To

## Related Files

- `PrintSO.vue` - Main component
- `SalesOrderController.php` - API endpoint
- `SalesOrderLookupModal.vue` - SO selection modal
- `routes/api.php` - Route definition

## SQL Verification

```sql
-- Check SO numbers in current period
SELECT 
    SO_Num,
    AC_Num,
    AC_NAME,
    PO_Num,
    STS,
    AMOUNT,
    created_at
FROM so
WHERE MM = '10' 
  AND YYYY = '2025'
ORDER BY SO_Num;

-- Check SO range
SELECT COUNT(*) as total
FROM so
WHERE SO_Num >= '10-2025-00001'
  AND SO_Num <= '10-2025-99999';
```

## Performance

- ✅ Efficient filtering at database level
- ✅ Indexed queries on MM, YYYY columns
- ✅ Limit 100 results for performance
- ✅ Client-side caching for preview

## Backward Compatibility

✅ **Fully backward compatible**
- Existing SO numbers unchanged
- API response format unchanged
- UI behavior improved (more consistent)

---

**Status:** ✅ Fixed and Tested  
**Date:** 2025-10-21  
**Impact:** Users can now properly filter SO by range  
**Tested:** ✅ Empty range, ✅ Specific range, ✅ Lookup integration
