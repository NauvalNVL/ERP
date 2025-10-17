# 🧪 Testing Guide - Complete CPS Flow

## ✅ Implementation Complete!

Semua komponen dan flow sudah diimplementasikan sesuai CPS Enterprise 2020.

## 📋 Quick Testing Steps

### 1. Persiapan Data

```bash
# Pastikan tax data sudah ada
php artisan db:seed --class=TaxRateSeeder

# Cek DO yang tersedia
SELECT DO_Num, AC_Num, AC_Name, DO_Tran_Amt, Status 
FROM DO 
WHERE DOYYYY = '2025' AND DOMM = '10' 
AND (Status IS NULL OR Status != 'Invoiced')
LIMIT 10;
```

### 2. Buka Menu

```
URL: http://127.0.0.1:8000/warehouse-management/invoice/iv-processing/prepare-by-do-current-period
```

### 3. Complete Flow Test (11 Steps)

#### Step 1: Form Awal
- ✅ Current Period: 10/2025 (auto-filled)
- ✅ Update Period: 10/2025 (auto-filled)
- ✅ Click button di Customer Code

#### Step 2: Customer Lookup Modal
- ✅ Modal muncul dengan list customer
- ✅ Sort by: Customer Code / Customer Name
- ✅ Filter: Active/Obsolete
- ✅ Select customer (e.g., ABDULLAH_BPK - 000211)
- ✅ Modal close, form auto-populate:
  - Customer Name: ABDULLAH_BPK
  - Currency: IDR - INDONESIA

#### Step 3: Tax Lookup (Optional)
- ✅ Click button di Tax Index No
- ✅ Modal muncul dengan tax list
- ✅ Select tax (e.g., PPN11)
- ✅ Form auto-populate Tax Index No

#### Step 4: Set Invoice Date
- ✅ Click date field
- ✅ Calendar picker muncul
- ✅ Select date (e.g., 14/10/2025)
- ✅ Day of week muncul (e.g., Tue)

#### Step 5: Continue to Prepare
- ✅ Click "Continue to Prepare" button
- ✅ Button disabled saat processing
- ✅ Loading spinner muncul

#### Step 6: Check Sales Tax Screen
- ✅ Modal muncul otomatis
- ✅ Table menampilkan tax codes:
  - No Tax Code | Name | Apply | Tax% | Include
  - PPN11 | PPN 11% | Yes | 11.00% | No
- ✅ First tax auto-selected
- ✅ Click row untuk select tax lain
- ✅ Click OK button

#### Step 7: Delivery Order Screen
- ✅ Modal muncul otomatis
- ✅ Toolbar buttons visible:
  - 👁 View Details
  - ❌ Close
  - 🔄 Refresh
  - 🖨 Print
- ✅ Table menampilkan DO list:
  - No | D/Order# | D/O Date | Select
  - 01 | DO-001 | 14/10/2025 | ☐
  - 02 | DO-002 | 14/10/2025 | ☐
- ✅ Check 1 atau lebih DOs
- ✅ Selected count update (e.g., "2 delivery order(s) selected")
- ✅ Select button enabled
- ✅ Click "Select (n)" button

#### Step 8: View DO Details (Optional)
- ✅ Click 👁 button di toolbar
- ✅ Delivery Order Table modal muncul
- ✅ Full table dengan kolom:
  - D/Order# | D/O Date | Customer | Vehicle# | Status
- ✅ Additional info fields:
  - Customer Name
  - Salesperson
  - Order Mode (radio buttons)
  - Agent Cost
  - Sales Type
  - D/O Instructions
  - Prepared by / Date
  - Printed by / Date
- ✅ Click Exit untuk kembali

#### Step 9: Final Tax Calculation
- ✅ Modal muncul otomatis setelah select DOs
- ✅ Display:
  - Total Amount: 4,410,000.00 (sum of selected DOs)
  - Tax Group: [PPN11 ▼] (dropdown)
  - Tax Amount: 485,100.00 (auto-calculated: 11%)
  - Net Amount: 4,895,100.00 (Total + Tax)
- ✅ Change tax group → tax amount recalculated
- ✅ Click OK button

#### Step 10: Invoice Number Option
- ✅ Modal muncul otomatis
- ✅ 2 radio options:
  - ○ Auto Generated Number (default)
  - ○ Manual Selection Number
- ✅ Select "Auto Generated Number"
- ✅ Click OK button

#### Step 11: Invoice Created
- ✅ Success alert muncul:
  ```
  Success! 2 invoice(s) prepared:
  IV-202510-0001
  IV-202510-0002
  ```
- ✅ Form reset otomatis
- ✅ Ready untuk entry berikutnya

## 🔍 Verification Queries

### Check Created Invoices
```sql
SELECT IV_NUM, AC_NUM, AC_NAME, IV_TAX_CODE, IV_TRAN_AMT, IV_STS
FROM INV
WHERE YYYY = '2025' AND MM = '10'
ORDER BY IV_NUM DESC
LIMIT 5;
```

### Check DO Status Updated
```sql
SELECT DO_Num, AC_Num, Status
FROM DO
WHERE DO_Num IN ('DO-001', 'DO-002');
-- Status should be 'Invoiced'
```

### Check Tax Calculation
```sql
SELECT 
  IV_NUM,
  IV_TRAN_AMT as Subtotal,
  IV_TAX_CODE as TaxCode,
  IV_TAX_PERCENT as TaxRate,
  (IV_TRAN_AMT * IV_TAX_PERCENT / 100) as TaxAmount,
  (IV_TRAN_AMT + (IV_TRAN_AMT * IV_TAX_PERCENT / 100)) as NetAmount
FROM INV
WHERE IV_NUM = 'IV-202510-0001';
```

## 🧪 Edge Cases Testing

### Test 1: No Customer Selected
- ✅ "Continue to Prepare" button disabled
- ✅ Customer fields hidden

### Test 2: No DOs Found
- ✅ Delivery Order Screen shows empty state
- ✅ Message: "No delivery orders found for current period"

### Test 3: No DOs Selected
- ✅ Select button disabled
- ✅ Selected count: "0 delivery order(s) selected"

### Test 4: Manual Invoice Number
- Step 10: Select "Manual Selection Number"
- ✅ Input field muncul
- ✅ Enter: "IV-TEST-001"
- ✅ Click OK
- ✅ Invoice created dengan number manual

### Test 5: Duplicate Manual Number
- Try to create invoice dengan number yang sudah ada
- ✅ Error: "Invoice number IV-TEST-001 already exists"

### Test 6: Change Tax in Final Screen
- Step 9: Change tax group dari PPN11 ke PPN11+PPh
- ✅ Tax amount recalculated
- ✅ Net amount updated

### Test 7: Multiple DOs
- Step 7: Select 3 DOs
- ✅ Total amount = sum of 3 DOs
- ✅ 3 invoices created (one per DO)

## 📊 Expected Results

### Form Behavior
- ✅ Period fields readonly (auto-filled)
- ✅ Customer lookup button functional
- ✅ Currency auto-populated from customer
- ✅ Tax lookup button functional
- ✅ Date picker with day of week
- ✅ Continue button disabled until customer selected
- ✅ Loading state during processing

### Modal Sequence
1. ✅ Check Sales Tax Screen
2. ✅ Delivery Order Screen
3. ✅ (Optional) Delivery Order Table
4. ✅ Final Tax Calculation
5. ✅ Invoice Number Option
6. ✅ Success Message

### Data Integrity
- ✅ Invoice records created in INV table
- ✅ DO status updated to 'Invoiced'
- ✅ Tax calculated correctly
- ✅ All DO fields copied to invoice
- ✅ Audit fields populated (NW_UID, NW_DATE, NW_TIME)

### User Experience
- ✅ Smooth modal transitions
- ✅ Clear loading indicators
- ✅ Helpful error messages
- ✅ Form reset after success
- ✅ Responsive design

## 🚨 Common Issues & Solutions

### Issue 1: Modal tidak muncul
**Check:**
- Browser console for errors
- Vue DevTools for component state
- Network tab for API calls

**Solution:**
```bash
# Clear cache
npm run build
php artisan optimize:clear
```

### Issue 2: Tax list kosong
**Check:**
```sql
SELECT * FROM taxrate WHERE RATEPPN > 0;
```

**Solution:**
```bash
php artisan db:seed --class=TaxRateSeeder
```

### Issue 3: DO list kosong
**Check:**
```sql
SELECT * FROM DO 
WHERE DOYYYY = '2025' AND DOMM = '10'
AND (Status IS NULL OR Status != 'Invoiced');
```

**Solution:**
- Pastikan ada DO untuk period yang dipilih
- Pastikan DO belum di-invoice

### Issue 4: API error 500
**Check:**
```bash
tail -f storage/logs/laravel.log
```

**Solution:**
- Check database connection
- Check table structure
- Check controller logic

## 📝 Test Checklist

### Components
- [x] CheckSalesTaxModal
- [x] DeliveryOrderSelectionModal
- [x] DeliveryOrderDetailModal
- [x] FinalTaxCalculationModal
- [x] InvoiceNumberOptionModal

### API Endpoints
- [x] GET /api/invoices/current-period-do
- [x] GET /api/invoices/customer-details
- [x] GET /api/invoices/sales-tax-options
- [x] POST /api/invoices/calculate-total
- [x] POST /api/invoices/prepare

### Flow Steps
- [x] Form awal
- [x] Customer lookup
- [x] Tax lookup
- [x] Continue to prepare
- [x] Check sales tax
- [x] DO selection
- [x] DO detail view (optional)
- [x] Final tax calculation
- [x] Invoice number option
- [x] Invoice created

### Data Operations
- [x] Fetch customer details
- [x] Fetch tax options
- [x] Fetch DOs
- [x] Calculate total
- [x] Create invoices
- [x] Update DO status

## ✅ Success Criteria

1. ✅ All 11 steps complete without errors
2. ✅ Invoices created in database
3. ✅ DO status updated correctly
4. ✅ Tax calculated accurately
5. ✅ Form resets after success
6. ✅ User can repeat process immediately

---
**Status**: ✅ Ready for Testing
**Last Updated**: October 16, 2025
**Version**: 3.0 - Complete CPS Flow
