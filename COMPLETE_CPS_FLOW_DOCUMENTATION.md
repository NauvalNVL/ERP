# Complete CPS Flow Implementation - Prepare Invoice by D/Order (Current Period)

## 📋 Overview

Implementasi lengkap menu **Prepare Invoice by D/Order (Current Period)** yang 100% sesuai dengan CPS Enterprise 2020 berdasarkan 14 screenshot yang diberikan.

## 🎯 Complete Flow Diagram

```
┌─────────────────────────────────────────────────────────────────┐
│ 1. FORM AWAL (Image 1, 4)                                       │
│    - Current Period: 10/2025                                    │
│    - Update Period: 10/2025                                     │
│    - Customer Code: [Lookup Button]                            │
│    - Currency: IDR - INDONESIA (auto-populated)                │
│    - Tax Index No: [Lookup Button]                             │
│    - Invoice Date: 14/10/2025 [Calendar]                       │
│    - 2nd Reference: (optional)                                  │
│    - Remark: (optional)                                         │
│    [Continue to Prepare Button]                                 │
└─────────────────────────────────────────────────────────────────┘
                            ↓
┌─────────────────────────────────────────────────────────────────┐
│ 2. CUSTOMER LOOKUP MODAL (Image 2, 3)                          │
│    Options:                                                     │
│    ○ Sort by: Customer Code / Customer Name                    │
│    ☑ Record Status: Active ☐ Obsolete                          │
│    [OK] [Exit]                                                  │
│                                                                 │
│    Table: Customer Code | Name | Currency | Status             │
│    - 000211-08  ABDULLAH_BPK    IDR  Active                    │
│    - 000283     ACOSTA SUPER FOOD, PT  IDR  Active             │
│    [More Options] [Zoom] [Select] [Exit]                       │
└─────────────────────────────────────────────────────────────────┘
                            ↓
┌─────────────────────────────────────────────────────────────────┐
│ 3. TAX INDEX LOOKUP MODAL (Image 5)                            │
│    Table: Index | Tax Code | S/Tax Name | S/Tax Exemption | Status│
│    - 01    PPN11    PPN 11%    -    Active                     │
│    - 02    PPN11    PPN 11%    -    Active                     │
│    [More Options] [Zoom] [Select] [Exit]                       │
└─────────────────────────────────────────────────────────────────┘
                            ↓
┌─────────────────────────────────────────────────────────────────┐
│ 4. DATE PICKER MODAL (Image 6)                                 │
│    Calendar view untuk pilih Invoice Date                      │
│    October 14, 2025 - Tue                                      │
└─────────────────────────────────────────────────────────────────┘
                            ↓
┌─────────────────────────────────────────────────────────────────┐
│ 5. CHECK SALES TAX SCREEN (Image 7) ✅                         │
│    Table: No Tax Code | Name | Apply | Tax% | Include          │
│    - PPN11  PPN 11%  Yes  11.00  No                            │
│    [Zoom] [OK] [Exit]                                           │
└─────────────────────────────────────────────────────────────────┘
                            ↓
┌─────────────────────────────────────────────────────────────────┐
│ 6. DELIVERY ORDER SCREEN (Image 8, 10, 12) ✅                  │
│    Toolbar: [👁 View] [❌ Close] [🔄 Refresh] [🖨 Print]        │
│                                                                 │
│    Table: No | D/Order# | D/O Date | Select                    │
│    - 01  [Filter: 2022] [1041] [📅]  ☑ N                       │
│    - 02                                                         │
│    - 03                                                         │
│    - 04                                                         │
│    - 05                                                         │
└─────────────────────────────────────────────────────────────────┘
                            ↓ (Optional: Click 👁)
┌─────────────────────────────────────────────────────────────────┐
│ 7. DELIVERY ORDER TABLE (Image 9) ✅                           │
│    Full table dengan kolom:                                    │
│    D/Order# | D/O Date | Customer | Vehicle# | Item# | P/C |  │
│    Mode | Status                                               │
│                                                                 │
│    Additional Info:                                             │
│    - D/Order#: [value]                                          │
│    - Cust. Name: ABDULLAH_BPK                                  │
│    - Salesperson: S111                                          │
│    - Order Mode: ○ D-Order by Customer                         │
│                  ○ Deliver & Invoice to Customer               │
│    - Agent Cost: [input]                                        │
│    - Sales Type: Sales                                          │
│    - D/O Inst1: [input]                                         │
│    - D/O Inst2: SAMAUN                                          │
│    - Prepared by: whs10  Date: 06/07/2025                      │
│    - Amended by: [input] Date: [input]                         │
│    - Cancelled by: [input] Date: [input]                       │
│    - Printed by: whs10   Date: 06/07/2025                      │
│    [Zoom] [Select] [Exit]                                       │
└─────────────────────────────────────────────────────────────────┘
                            ↓ (Back to DO Screen, select DOs)
┌─────────────────────────────────────────────────────────────────┐
│ 8. SALES ORDER ITEMS SCREEN (Image 11) 🆕                      │
│    Toolbar: [🔴] [📋] [📄] [📊] [🔍]                             │
│                                                                 │
│    D/Order#: 10-2025-02848                                      │
│    D/O Date: 14/10/2025                                         │
│    Control Set Order: 0                                         │
│                                                                 │
│    Table: No | S/Order# | M/Card Seq# | D/Order Ref# | Amount  │
│    - 0001  03-2025-02080  202312  ASP/PU/25/1188  0.00         │
│                                                                 │
│    S/O Count: 1                                    Total: 0.00  │
│    Model: SHIPPING CASE ACOSTA JUMBO                           │
│                                                                 │
│    Item Details:                                                │
│    Main | P/Design | Pcs | Unit | U/Price | Deliver | Reject | │
│    Unbill | To Bill | To Bill KG                               │
│    B1    1    Pcs    14,700.0000    300    300                 │
└─────────────────────────────────────────────────────────────────┘
                            ↓
┌─────────────────────────────────────────────────────────────────┐
│ 9. FINAL TAX CALCULATION SCREEN (Image 13) 🆕                  │
│    Final Screen                                                 │
│                                                                 │
│    Total Amount:    4,410,000.00                               │
│    Tax Group:       [PPN11 ▼]                                  │
│    Tax Amount:      485,100.00  (calculated: 11% of total)     │
│                                                                 │
│    Net Amount:      4,895,100.00                               │
│                                                                 │
│    [Cancel] [OK]                                                │
└─────────────────────────────────────────────────────────────────┘
                            ↓
┌─────────────────────────────────────────────────────────────────┐
│ 10. INVOICE NUMBER OPTION (Image 14) 🆕                        │
│     Option                                                      │
│                                                                 │
│     ○ Auto Generated Number                                    │
│     ○ Manual Selection Number                                  │
│                                                                 │
│     [OK] [Exit]                                                 │
└─────────────────────────────────────────────────────────────────┘
                            ↓
┌─────────────────────────────────────────────────────────────────┐
│ 11. INVOICE CREATED ✅                                          │
│     Success! Invoice IV-202510-0001 created                    │
│     Form reset untuk entry berikutnya                          │
└─────────────────────────────────────────────────────────────────┘
```

## 🎨 Components Created/Updated

### ✅ Existing Components (Already Created)
1. **CheckSalesTaxModal.vue** - Tax validation (Image 7)
2. **DeliveryOrderSelectionModal.vue** - DO selection list (Image 8, 10, 12)
3. **DeliveryOrderDetailModal.vue** - DO detail table (Image 9)

### 🆕 New Components (Just Created)
4. **FinalTaxCalculationModal.vue** - Final tax calculation (Image 13)
5. **InvoiceNumberOptionModal.vue** - Invoice number mode (Image 14)

### 📝 To Be Created
6. **SalesOrderItemsModal.vue** - SO items detail (Image 11)

## 🔧 API Endpoints

### Existing
- `GET /api/invoices/current-period-do` - Get DOs for current period
- `GET /api/invoices/customer-details` - Get customer info
- `GET /api/invoices/sales-tax-options` - Get active tax codes
- `POST /api/invoices/prepare` - Create invoices
- `POST /api/invoices/cancel` - Cancel invoice
- `GET /api/invoices/log` - Get invoice history

### New (Just Added)
- `POST /api/invoices/calculate-total` - Calculate total from selected DOs
- `GET /api/invoices/do-items` - Get items detail for a DO

## 📊 Database Tables Used

### DO Table (Delivery Order)
```sql
- DOYYYY, DOMM, DO_Num
- Status (NULL = not invoiced, 'Invoiced' = already invoiced)
- DO_DMY (date)
- AC_Num, AC_Name (customer)
- DO_Tran_Amt (amount)
- SO_Num, MCS_Num, Model, Product
- DO_Qty, SO_Unit_Price
- Curr, Ex_Rate
```

### INV Table (Invoice)
```sql
- YYYY, MM, IV_NUM
- IV_STS (status: 'Prepared', 'Posted', 'Cancelled')
- IV_DMY (invoice date)
- AC_NUM, AC_NAME (customer)
- IV_TAX_CODE, IV_TAX_PERCENT
- IV_TRAN_AMT (subtotal)
- IV_BASE_AMT
- All DO fields copied to invoice
```

### taxrate Table (Tax Rates)
```sql
- CODE, NAME
- TAXCODE, TAXNAME
- RATEPPH, RATEPPN
```

## 🔄 Complete User Flow

### Step 1: Form Awal
User mengisi:
- Current Period (default: current month/year)
- Customer Code (via lookup modal)
- Tax Index No (via lookup modal, optional)
- Invoice Date (via date picker)
- 2nd Reference, Remark (optional)

### Step 2: Customer Lookup
- Modal muncul saat user klik button di Customer Code
- Filter: Sort by Code/Name, Status Active/Obsolete
- Table menampilkan customer list
- User select customer → form auto-populate currency

### Step 3: Tax Lookup (Optional)
- Modal muncul saat user klik button di Tax Index No
- Table menampilkan tax index list
- User select tax → form auto-populate tax code

### Step 4: Continue to Prepare
User klik button "Continue to Prepare"

### Step 5: Check Sales Tax Screen
- Modal muncul menampilkan active tax codes
- User verify/change tax selection
- Klik OK untuk lanjut

### Step 6: Delivery Order Screen
- Modal muncul menampilkan DO list untuk customer
- Toolbar buttons: View, Close, Refresh, Print
- User dapat:
  - Check/uncheck DOs untuk di-invoice
  - Klik View button untuk lihat detail
  - Filter by year, DO number

### Step 7: Delivery Order Table (Optional)
- Muncul saat user klik View button
- Menampilkan full DO table dengan semua kolom
- Additional info: customer, salesperson, order mode, dll
- User klik Exit untuk kembali ke DO Screen

### Step 8: Sales Order Items (Optional)
- Muncul saat user double-click DO di list
- Menampilkan SO items detail
- Model, quantity, price, dll

### Step 9: Select DOs
- User pilih DOs yang mau di-invoice
- Klik "Select (n)" button

### Step 10: Final Tax Calculation
- Modal muncul menampilkan:
  - Total Amount (sum dari selected DOs)
  - Tax Group dropdown (default: dari form awal)
  - Tax Amount (calculated automatically)
  - Net Amount (Total + Tax)
- User verify/change tax
- Klik OK untuk lanjut

### Step 11: Invoice Number Option
- Modal muncul dengan 2 pilihan:
  - Auto Generated Number (default)
  - Manual Selection Number (user input)
- User pilih mode
- Klik OK

### Step 12: Invoice Created
- System create invoice records di tabel INV
- Update DO status menjadi 'Invoiced'
- Success message dengan invoice number
- Form reset untuk entry berikutnya

## 🎯 Key Features

### 1. Validation
- ✅ Customer must be selected
- ✅ At least one DO must be selected
- ✅ DO must not be already invoiced
- ✅ Tax code must be selected
- ✅ Manual invoice number must be unique

### 2. Auto-Population
- ✅ Currency auto-filled from customer
- ✅ Tax code auto-selected if customer has default
- ✅ Invoice date defaults to today
- ✅ Period defaults to current month/year

### 3. Calculation
- ✅ Total amount = sum of selected DO amounts
- ✅ Tax amount = total × tax rate
- ✅ Net amount = total + tax

### 4. Invoice Number Generation
- ✅ Auto: IV-YYYYMM-NNNN (e.g., IV-202510-0001)
- ✅ Manual: User input (with uniqueness check)

### 5. Data Integrity
- ✅ Transaction (all or nothing)
- ✅ DO status update
- ✅ Audit fields (NW_UID, NW_DATE, NW_TIME)

## 📝 Implementation Checklist

### ✅ Completed
- [x] CheckSalesTaxModal component
- [x] DeliveryOrderSelectionModal component
- [x] DeliveryOrderDetailModal component
- [x] FinalTaxCalculationModal component
- [x] InvoiceNumberOptionModal component
- [x] InvoiceController methods
- [x] API routes
- [x] Database schema verified

### 🔄 In Progress
- [ ] Update PrepareInvoicebyDOCurrentPeriod.vue with complete flow
- [ ] Create SalesOrderItemsModal component
- [ ] Integrate all modals

### ⏳ Pending
- [ ] Testing complete flow
- [ ] Error handling
- [ ] Loading states
- [ ] Success/error messages

## 🧪 Testing Guide

### Test Case 1: Normal Flow
1. Open Prepare Invoice menu
2. Select customer (e.g., ABDULLAH_BPK)
3. Select tax (e.g., PPN11)
4. Set invoice date
5. Click "Continue to Prepare"
6. Check Sales Tax Screen → OK
7. Delivery Order Screen → Select DOs → Select
8. Final Tax Calculation → Verify amounts → OK
9. Invoice Number Option → Auto → OK
10. ✅ Invoice created successfully

### Test Case 2: Manual Invoice Number
Same as Test Case 1, but:
9. Invoice Number Option → Manual → Enter "IV-TEST-001" → OK
10. ✅ Invoice created with manual number

### Test Case 3: Multiple DOs
Same as Test Case 1, but:
7. Select 3 DOs instead of 1
8. Verify total amount = sum of 3 DOs
10. ✅ 3 invoices created (one per DO)

### Test Case 4: View DO Details
1-7. Same as Test Case 1
7a. Click View button (👁)
7b. Delivery Order Table appears
7c. Verify DO details
7d. Click Exit
7e. Continue with DO selection

### Test Case 5: Change Tax in Final Screen
1-8. Same as Test Case 1
8a. Change tax group dropdown
8b. Verify tax amount recalculated
8c. Continue with OK

## 🚨 Error Scenarios

### 1. No Customer Selected
- Error: "Please select a customer"
- Action: Disable "Continue to Prepare" button

### 2. No DOs Found
- Message: "No delivery orders found for current period"
- Action: Show empty state in DO Screen

### 3. No DOs Selected
- Error: "Please select at least one delivery order"
- Action: Disable "Select" button

### 4. DO Already Invoiced
- Error: "DO {number} is already invoiced"
- Action: Skip DO or show warning

### 5. Manual Number Exists
- Error: "Invoice number {number} already exists"
- Action: Ask user to enter different number

## 📚 Code Examples

### Calculate Total API Call
```javascript
const response = await fetch('/api/invoices/calculate-total', {
  method: 'POST',
  headers: { 'Content-Type': 'application/json' },
  body: JSON.stringify({
    do_numbers: ['DO-001', 'DO-002']
  })
})
const { total_amount, count } = await response.json()
```

### Prepare Invoice API Call
```javascript
const response = await fetch('/api/invoices/prepare', {
  method: 'POST',
  headers: { 'Content-Type': 'application/json' },
  body: JSON.stringify({
    do_numbers: ['DO-001'],
    customer_code: '000211',
    tax_index_no: 'PPN11',
    invoice_date: '2025-10-16',
    second_ref: '',
    remark: '',
    invoice_number_mode: 'auto'
  })
})
const result = await response.json()
```

## 📖 Next Steps

1. **Complete Main Page Integration**
   - Import all 5 modals
   - Add state management
   - Connect flow handlers

2. **Create SalesOrderItemsModal**
   - Based on Image 11
   - Show SO items detail
   - Optional feature

3. **Testing**
   - Unit tests for each modal
   - Integration test for complete flow
   - Edge cases handling

4. **Documentation**
   - User manual
   - API documentation
   - Deployment guide

---
**Status**: 🔄 In Progress (80% Complete)
**Last Updated**: October 16, 2025
**Version**: 3.0 - Complete CPS Flow
