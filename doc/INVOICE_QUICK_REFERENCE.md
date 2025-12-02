# Invoice Module - Quick Reference Guide

## File Changes Summary

### ✅ New Files Created
1. `app/Models/Invoice.php` - Main invoice model
2. `app/Http/Controllers/WarehouseManagement/Invoice/InvoiceController.php` - Invoice controller
3. `INVOICE_IMPLEMENTATION_SUMMARY.md` - Detailed documentation
4. `INVOICE_QUICK_REFERENCE.md` - This file

### 📝 Files Modified
1. `routes/api.php` - Updated invoice routes
2. `resources/js/Pages/warehouse-management/Invoice/IVProcessing/PrepareInvoicebyDOCurrentPeriod.vue` - Enhanced main page
3. `resources/js/Components/PrepareInvoiceFlowModal.vue` - Added invoice number options
4. `resources/js/Components/CurrentPeriodDoTable.vue` - Added customer filtering
5. `resources/js/Components/PrepareInvoiceConfirmModal.vue` - Enhanced with full parameters

### ⚠️ Files to Remove (After Testing)
1. `app/Models/Inv.php` - Replaced by Invoice.php
2. `app/Http/Controllers/WarehouseManagement/Invoice/IVProcessingController.php` - Replaced by InvoiceController.php

## Key Features Comparison

| CPS Feature | Status | Implementation |
|------------|--------|----------------|
| Current Period Display | ✅ | Auto-populated from system date |
| Customer Selection | ✅ | Modal with sorting options |
| Customer Details Auto-fill | ✅ | API call on customer select |
| Currency Display | ✅ | Shows code and name |
| Tax Index Selection | ✅ | Modal with tax options |
| Invoice Date Picker | ✅ | Date input with day display |
| 2nd Reference Field | ✅ | Text input |
| Remark Field | ✅ | Text input |
| DO Selection Screen | ✅ | Multi-select table with checkboxes |
| DO Filtering by Customer | ✅ | Automatic filtering |
| Invoice Number Options | ✅ | Auto-generated or manual |
| Tax Calculation | ✅ | Automatic based on tax code |
| Invoice Preparation | ✅ | Creates INV records |
| DO Status Update | ✅ | Marks as "Invoiced" |
| Cancel Invoice | 🔄 | Button ready, needs modal |
| Invoice Log/Print | 🔄 | Button ready, needs modal |

Legend: ✅ Complete | 🔄 Partial | ❌ Not implemented

## API Endpoints Quick Reference

```
GET    /api/invoices/current-period-do?customer_code={code}
GET    /api/invoices/customer-details?customer_code={code}
GET    /api/invoices/sales-tax-options
POST   /api/invoices/prepare
POST   /api/invoices/cancel
GET    /api/invoices/log?year={year}&month={month}&customer_code={code}
```

## Common Use Cases

### 1. Prepare Invoice for Single Customer
```
1. Open menu: Warehouse Management > Invoice > IV Processing > Prepare Invoice by D/Order
2. Click customer lookup button
3. Select customer (e.g., ABDULLAH_BPK)
4. Verify auto-filled: Name, Currency, Tax Index
5. Set invoice date if needed
6. Click "Continue to Prepare"
7. Select DOs from the list
8. Choose "Auto Generated Number"
9. Click "Prepare"
10. Confirm
```

### 2. Prepare Invoice with Manual Number
```
1-7. Same as above
8. Choose "Manual Selection Number"
9. Enter invoice number (e.g., IV-CUSTOM-001)
10. Click "Prepare"
11. Confirm
```

### 3. Prepare Multiple Invoices
```
1-7. Same as use case 1
8. Select multiple DOs using checkboxes
9. System will create one invoice per DO
10. Click "Prepare"
11. Confirm
```

## Database Tables Used

### Primary Table: `INV`
- Stores all invoice records
- Key fields: `IV_NUM`, `IV_STS`, `AC_NUM`, `IV_TRAN_AMT`, `IV_TAX_CODE`

### Related Tables:
- `DO` - Delivery Orders (source data)
- `Customer_Account` - Customer information
- `Sales_Tax` - Tax codes and rates

## Troubleshooting

### Issue: Customer details not loading
**Solution**: Check `/api/invoices/customer-details` endpoint and Customer_Account table

### Issue: DOs not showing
**Solution**: 
- Verify DO records exist for current period
- Check DOYYYY and DOMM fields match current date
- Ensure Status is not 'Invoiced'

### Issue: Invoice preparation fails
**Solution**:
- Check Laravel logs
- Verify DO exists and is not already invoiced
- Ensure all required fields are populated
- Check database permissions

### Issue: Tax not calculating
**Solution**:
- Verify Sales_Tax table has the tax code
- Check tax_rate field is numeric
- Ensure tax code is passed in request

## Testing Commands

### Check Invoice Creation
```sql
SELECT IV_NUM, AC_NUM, AC_NAME, IV_TRAN_AMT, IV_TAX_CODE, IV_STS, NW_DATE
FROM INV 
WHERE YYYY = YEAR(CURDATE()) AND MM = LPAD(MONTH(CURDATE()), 2, '0')
ORDER BY IV_NUM DESC
LIMIT 10;
```

### Check DO Status
```sql
SELECT DO_Num, AC_Num, AC_Name, Status, Invoice_Num, DO_Tran_Amt
FROM DO
WHERE Status = 'Invoiced'
ORDER BY DO_Num DESC
LIMIT 10;
```

### Check Available DOs for Current Period
```sql
SELECT DO_Num, AC_Num, AC_Name, DO_DMY, DO_Tran_Amt, Status
FROM DO
WHERE DOYYYY = YEAR(CURDATE()) 
  AND DOMM = LPAD(MONTH(CURDATE()), 2, '0')
  AND (Status IS NULL OR Status != 'Invoiced')
ORDER BY DO_Num;
```

## Configuration

### Invoice Number Format
Default: `IV-YYYYMM-NNNN`
- Example: `IV-202510-0001`
- Sequential numbering per period

### Tax Codes
Stored in `Sales_Tax` table
- Common codes: PPN11 (11% VAT), PPN10 (10% VAT)
- Can be extended with new tax types

## Browser Console Commands (for debugging)

```javascript
// Test customer details API
fetch('/api/invoices/customer-details?customer_code=000211-08')
  .then(r => r.json())
  .then(console.log)

// Test current period DOs
fetch('/api/invoices/current-period-do')
  .then(r => r.json())
  .then(console.log)

// Test tax options
fetch('/api/invoices/sales-tax-options')
  .then(r => r.json())
  .then(console.log)
```

## Performance Tips

1. **Large DO Lists**: Use customer filtering to reduce data
2. **Batch Processing**: Select multiple DOs at once
3. **Database Indexes**: Ensure indexes on YYYY, MM, AC_NUM fields
4. **Caching**: Customer details can be cached for session

## Security Notes

- CSRF token required for POST requests
- User authentication checked via Auth facade
- Audit trail maintained (NW_UID, NW_DATE, NW_TIME)
- Cancellation reasons logged

## Future Enhancements Roadmap

### Phase 1 (Immediate)
- [ ] Cancel Invoice Modal UI
- [ ] Invoice Log Viewer Modal
- [ ] Print Invoice functionality

### Phase 2 (Short-term)
- [ ] Tax amount preview in confirmation
- [ ] Email invoice to customer
- [ ] PDF invoice generation
- [ ] Invoice amendment feature

### Phase 3 (Long-term)
- [ ] Credit note functionality
- [ ] Recurring invoice templates
- [ ] Invoice approval workflow
- [ ] Integration with accounting system

---
**Last Updated**: October 16, 2025
**Module Version**: 1.0
**Compatibility**: Laravel 10+, Vue 3, Inertia.js
