# Amend Invoice Implementation - CPS Compatible

## 📋 Overview
Implementasi lengkap menu **Amend Invoice** yang 100% sesuai dengan CPS Enterprise 2020, memungkinkan user untuk mengedit invoice yang sudah dibuat sebelum di-print atau di-post.

---

## 🎯 Features Implemented

### **1. Main Screen (Image 1)**
- ✅ **Current Period Input** - MM/YYYY format
- ✅ **Invoice# Input** - Split into 3 parts (MM-YYYY-NNNN)
- ✅ **Search Button** - Open invoice table modal
- ✅ Modern gradient header dengan icon

### **2. Invoice Table Modal (Image 2)**
- ✅ **Invoice List Table** dengan kolom:
  - INVOICE# | INV DATE | AC# | TAX | MODE | PC STATUS | POST STATUS
- ✅ **Detail Panel** di bawah table dengan fields:
  - Invoice# (split 3 fields dengan Search button)
  - Customer Name (readonly)
  - Order Mode (readonly)
  - Issued by + Date (audit trail)
  - Amended by + Date (audit trail)  
  - Printed by + Date (audit trail)
  - Posted by + Date (audit trail)
  - Reason (textarea)
- ✅ **Action Buttons**: Zoom, Select, Exit
- ✅ **Row Selection**: Click row untuk select
- ✅ **Search Functionality**: Filter by MM, YYYY, Sequence

### **3. Edit Screen (Image 3)**
- ✅ **Editable Fields**:
  - Current Period (MM/YYYY) - readonly display
  - Invoice# (readonly)
  - Customer (readonly - sudah fixed dari prepare)
  - Order Mode (editable)
  - Salesperson (editable)
  - A/C Currency (editable)
  - Exchange Rate (editable)
  - Exchange Method (1-Multiply / 2-Divide radio)
  - Tax Index No. (editable dengan search button)
  - Invoice Date (editable dengan date picker button)
  - 2nd Reference# (editable)
  - Remark (editable)
  - Invoice Status (display only)
- ✅ **Action Buttons**: Calculate, Save, Cancel

### **4. Customer Sales Tax Modal (Image 4)**
- ✅ **Tax Table** dengan kolom:
  - Index | S/Tax Code | S/Tax Name | S/Tax Exemption Reference# | Status
- ✅ **Row Selection**: Single selection mode
- ✅ **Action Buttons**: More Options, Zoom, Select, Exit
- ✅ **Integration**: Update Tax Index No. dari selected row

### **5. Date Picker Modal (Image 5)**
- ✅ **Calendar Display**: 
  - Month/Year navigation (prev/next buttons)
  - 7-column grid (Mon-Sun)
  - Highlight weekends (Sat/Sun) dengan warna
  - Highlight selected date dengan blue background
- ✅ **CPS Style**: 
  - Monday start (bukan Sunday)
  - Weekend highlighting (yellow header, red text)
  - Current selection indicator
- ✅ **Action Buttons**: OK, Cancel

### **6. Final Screen Modal (Image 6)**
- ✅ **Amount Display**:
  - Total Amount (readonly, formatted dengan separator)
  - Tax Group (dropdown)
  - Tax List (scrollable list dengan highlight)
  - Net Amount (readonly, calculated)
- ✅ **Tax Calculation**: 
  - Net Amount = Total Amount + Tax Amount
  - Tax Amount = Total Amount × Tax Rate
- ✅ **Action Buttons**: OK, Cancel
- ✅ **Integration**: Update invoice amounts setelah confirm

---

## 📁 Files Created/Modified

### **Frontend (Vue.js)**
1. **`resources/js/Pages/warehouse-management/Invoice/IVProcessing/AmendInvoice.vue`**
   - Main page dengan complete workflow
   - Integrated with existing components
   - Modern TailwindCSS styling

### **Backend (PHP)**
2. **`app/Http/Controllers/WarehouseManagement/Invoice/InvoiceController.php`**
   - ✅ **`index()`** - Get list of invoices (table modal)
   - ✅ **`show()`** - Get single invoice details (edit screen)
   - ✅ **`update()`** - Amend invoice dengan validation & audit trail

### **Routes**
3. **Routes Already Configured**:
   ```php
   Route::prefix('invoices')->group(function () {
       Route::get('/', [InvoiceController::class, 'index']);          // List invoices
       Route::get('/{invoiceNo}', [InvoiceController::class, 'show']); // Get invoice details
       Route::put('/{invoiceNo}', [InvoiceController::class, 'update']); // Amend invoice
   });
   ```

---

## 🔧 Technical Implementation

### **Backend Controller Methods**

#### **1. index() - Get Invoice List**
```php
GET /api/invoices?mm=10&yyyy=2025&seq=97042

Query: INV table
Filters:
  - MM (month)
  - YYYY (year)
  - IV_NUM (invoice number sequence)

Returns:
  - invoice_no (IV_NUM)
  - invoice_date (IV_DMY)
  - customer_code (AC_NUM)
  - customer_name (AC_NAME)
  - tax_code (IV_TAX_CODE)
  - mode (Auto/Manual from IV_NUM_TYPE)
  - pc_status (0/1 from PT_UID)
  - post_status (Posted/UnPost from IV_STS)
  - Audit trail: issued_by, amended_by, printed_by, posted_by
```

#### **2. show() - Get Invoice Details**
```php
GET /api/invoices/10-2025-97042

Query: INV table
WHERE: IV_NUM = '10-2025-97042'

Returns: All editable fields + audit trail
  - Date format conversion: DD/MM/YYYY → YYYY-MM-DD for HTML input
```

#### **3. update() - Amend Invoice**
```php
PUT /api/invoices/10-2025-97042
Content-Type: application/json

Body: {
  "order_mode": "...",
  "salesperson": "...",
  "tax_index_no": "...",
  "invoice_date": "...",
  // ... other fields
}

Business Rules Validation:
  ✓ Cannot amend if PT_UID (printed)
  ✓ Cannot amend if IV_STS = 'Cancelled'
  ✓ Cannot amend if IV_STS = 'Posted'

Audit Trail:
  AM_UID = current user
  AM_DATE = current date (d/m/Y)
  AM_TIME = current time (H:i)

Returns: Success message with audit info
```

---

## 📊 Database Schema (INV Table)

### **Columns Used for Amend**

```sql
-- Primary Keys
YYYY            VARCHAR(50)     -- Year
MM              VARCHAR(50)     -- Month
IV_NUM          VARCHAR(50)     -- Invoice Number (MM-YYYY-NNNN)

-- Status & Control
IV_STS          VARCHAR(50)     -- Status: Prepared/Posted/Cancelled
IV_NUM_TYPE     VARCHAR(50)     -- Type: A=Auto, M=Manual

-- Customer Info (readonly in amend)
AC_NUM          VARCHAR(50)     -- Customer Code
AC_NAME         VARCHAR(250)    -- Customer Name

-- Editable Fields
ORDER_MODE      VARCHAR(250)    -- Order Mode
SLM             VARCHAR(50)     -- Salesperson
CURR            VARCHAR(50)     -- Currency
EX_RATE         DECIMAL(15,2)   -- Exchange Rate
EXCHANGE_METHOD VARCHAR(50)     -- 1=Multiply, 2=Divide
TAX_INDEX_NO    VARCHAR(50)     -- Tax Index Number
IV_TAX_CODE     VARCHAR(50)     -- Tax Code
IV_TAX_PERCENT  DECIMAL(15,2)   -- Tax Percentage
IV_DMY          VARCHAR(50)     -- Invoice Date (DD/MM/YYYY)
REF2            VARCHAR(50)     -- 2nd Reference
IV_REMARK       VARCHAR(250)    -- Remark

-- Amounts
IV_TRAN_AMT     DECIMAL(18,4)   -- Total Amount
IV_TAX_AMT      DECIMAL(18,4)   -- Tax Amount
IV_NET_AMT      DECIMAL(18,4)   -- Net Amount

-- Audit Trail - New (Created)
NW_UID          VARCHAR(50)     -- Created by user ID
NW_DATE         VARCHAR(50)     -- Created date (d/m/Y)
NW_TIME         VARCHAR(50)     -- Created time (H:i)

-- Audit Trail - Amend (Modified)
AM_UID          VARCHAR(50)     -- Amended by user ID
AM_DATE         VARCHAR(50)     -- Amended date (d/m/Y)
AM_TIME         VARCHAR(50)     -- Amended time (H:i)

-- Audit Trail - Print
PT_UID          VARCHAR(50)     -- Printed by user ID
PT_DATE         VARCHAR(50)     -- Printed date (d/m/Y)
PT_TIME         VARCHAR(50)     -- Printed time (H:i)

-- Audit Trail - Post
PO_UID          VARCHAR(50)     -- Posted by user ID
PO_DATE         VARCHAR(50)     -- Posted date (d/m/Y)
PO_TIME         VARCHAR(50)     -- Posted time (H:i)
```

---

## 🔒 Business Rules (CPS Compatible)

### **Amend Validation Rules**

| Rule | Condition | Action |
|------|-----------|--------|
| **Printed Invoice** | `PT_UID IS NOT NULL` | ❌ Error: "Cannot amend invoice that has been printed" |
| **Cancelled Invoice** | `IV_STS = 'Cancelled'` | ❌ Error: "Cannot amend cancelled invoice" |
| **Posted Invoice** | `IV_STS = 'Posted'` | ❌ Error: "Cannot amend invoice that has been posted to GL" |
| **Prepared Invoice** | `IV_STS = 'Prepared' AND PT_UID IS NULL` | ✅ Allow amend |

### **Field Edit Rules (Updated - CPS Compatible)**

| Field | Editable? | Reason | CPS Behavior |
|-------|-----------|--------|--------------|
| **Current Period** | ❌ No | Primary key (YYYY, MM) | Display only, extracted from Invoice# |
| **Invoice Number** | ❌ No | Primary key, immutable | Display only |
| **Customer** | ❌ No | Fixed from prepare invoice | Display only |
| **Order Mode** | ❌ No | Fixed from prepare invoice | Display only |
| **Salesperson** | ❌ No | Fixed from prepare invoice | Display only |
| **A/C Currency** | ❌ No | Fixed from prepare invoice | Display only |
| **Exchange Rate** | ❌ No | Fixed from prepare invoice | Display only (0.000000) |
| **Invoice Status** | ❌ No | System controlled | Display only (Amd/Posted/etc) |
| **Exchange Method** | ✅ Yes | Can change calculation method | Radio: 1-Multiply / 2-Divide |
| **Tax Index No** | ✅ Yes | Tax may change | Editable with browse button |
| **Invoice Date** | ✅ Yes | Date correction | Editable with calendar button |
| **2nd Reference#** | ✅ Yes | Additional reference | Free text input |
| **Remark** | ✅ Yes | Additional notes | Textarea |
| **Amounts** | ✅ Yes (via Calculate) | Recalculated from Final Screen | Updated via Calculate button |

---

## 🎨 UI/UX Features

### **Modern Design Elements**
- ✅ Gradient headers (teal/cyan/blue)
- ✅ Smooth animations (fadeIn, scaleIn)
- ✅ Hover effects on rows
- ✅ Selected row highlighting
- ✅ Icon buttons with tooltips
- ✅ Responsive grid layout
- ✅ Professional color scheme

### **CPS Compatibility**
- ✅ Classic table layout
- ✅ Detail panel below table
- ✅ Split invoice number input (3 parts)
- ✅ Calendar dengan Monday start
- ✅ Weekend highlighting
- ✅ Date format: DD/MM/YYYY
- ✅ Audit trail display

### **User Experience**
- ✅ Loading states
- ✅ Error handling dengan toast notifications
- ✅ Confirmation prompts
- ✅ Readonly vs editable field distinction
- ✅ Clear action buttons
- ✅ Modal close on backdrop click
- ✅ Keyboard navigation ready

---

## 🔄 Workflow Diagram

```
┌─────────────────────────────────────────────────────────────┐
│                   AMEND INVOICE WORKFLOW                     │
└─────────────────────────────────────────────────────────────┘

1. User buka menu "Amend Invoice"
   ↓
2. Input Current Period (10 / 2025)
   ↓
3. Input Invoice# (optional): 10 / 2025 / 97042
   ↓
4. Click Search button
   ↓
5. System fetch invoices via API:
   GET /api/invoices?mm=10&yyyy=2025&seq=97042
   ↓
6. Invoice Table Modal opens
   - Display list of invoices
   - Show audit trail (issued, amended, printed, posted)
   ↓
7. User click row untuk select invoice
   - Row highlight blue
   - Detail panel populated
   ↓
8. User click "Select" button
   ↓
9. System fetch invoice details via API:
   GET /api/invoices/10-2025-97042
   ↓
10. Edit Screen appears dengan all fields populated
    ↓
11. User edit fields yang perlu diubah:
    - Order Mode
    - Salesperson
    - Currency & Exchange Rate
    - Tax Index (via Tax Modal)
    - Invoice Date (via Date Picker)
    - Remark
    ↓
12. User click "Calculate" button (optional)
    ↓
13. Final Screen Modal opens
    - Display Total Amount
    - Select Tax Group
    - Calculate Tax Amount
    - Show Net Amount
    ↓
14. User click "OK" di Final Screen
    - Amounts updated in Edit Screen
    ↓
15. User click "Save" button
    ↓
16. System validate business rules:
    ✓ Check if invoice was printed (PT_UID)
    ✓ Check if invoice was cancelled (IV_STS)
    ✓ Check if invoice was posted (IV_STS)
    ↓
17. System update INV table via API:
    PUT /api/invoices/10-2025-97042
    {
      order_mode: "...",
      salesperson: "...",
      // ... updated fields
    }
    ↓
18. System update audit trail:
    AM_UID = "current_user"
    AM_DATE = "05/11/2025"
    AM_TIME = "19:30"
    ↓
19. Success message displayed
    ↓
20. Edit Screen closes, back to main screen
```

---

## 📝 API Endpoints Summary

| Method | Endpoint | Description | CPS Compatible |
|--------|----------|-------------|----------------|
| **GET** | `/api/invoices` | List invoices dengan filter MM, YYYY, Seq | ✅ |
| **GET** | `/api/invoices/{invoiceNo}` | Get invoice details for editing | ✅ |
| **PUT** | `/api/invoices/{invoiceNo}` | Amend invoice dengan validation | ✅ |
| **GET** | `/api/invoices/sales-tax-options` | Get tax options for Final Screen | ✅ |
| **GET** | `/api/invoices/customer-tax-indices/{customerCode}` | Get customer tax indices | ✅ |

---

## 🧪 Testing Scenarios

### **Scenario 1: Normal Amend (Success)**
```
1. Open Amend Invoice
2. Search invoice: 10-2025-97042
3. Select invoice from table
4. Edit Remark: "Updated remark"
5. Click Save
   → Success: Invoice amended
   → Audit trail: AM_UID, AM_DATE, AM_TIME updated
```

### **Scenario 2: Amend Printed Invoice (Error)**
```
1. Open Amend Invoice
2. Search invoice: 10-2025-97001 (already printed)
3. Select invoice
4. Try to edit
5. Click Save
   → Error: "Cannot amend invoice that has been printed"
   → Invoice not updated
```

### **Scenario 3: Amend with Tax Calculation**
```
1. Open Amend Invoice
2. Select invoice
3. Edit Tax Index: 01 → 02 (via Tax Modal)
4. Click Calculate → Final Screen opens
5. Select Tax: PPN 11%
6. System calculate:
   - Total: 20,700,000
   - Tax: 2,277,000 (11%)
   - Net: 22,977,000
7. Click OK → amounts updated
8. Click Save → Success
```

### **Scenario 4: Date Change with Date Picker**
```
1. Open Amend Invoice
2. Select invoice
3. Click calendar icon on Invoice Date
4. Date Picker Modal opens
5. Navigate to November 2025
6. Select date: 03 (Monday)
7. Click OK
   → Invoice Date: 03/11/2025
8. Click Save → Success
```

---

## ⚠️ Important Notes

### **1. Audit Trail**
- Setiap amend **WAJIB** update `AM_UID`, `AM_DATE`, `AM_TIME`
- Original creator (`NW_UID`) tidak berubah
- History amend tercatat untuk audit purpose

### **2. Date Format**
- **Database**: DD/MM/YYYY (CPS format)
- **HTML Input**: YYYY-MM-DD (ISO format)
- **Conversion**: Automatic di backend & frontend

### **3. Validation**
- Frontend: Basic validation (required fields)
- Backend: Business rules validation (print/post/cancel status)
- Database: Constraint validation

### **4. Transaction Safety**
- All updates wrapped dalam `DB::beginTransaction()`
- Rollback on error
- Log semua operations

---

## 🚀 Future Enhancements

### **Phase 2 (Optional)**
1. ✨ **Print Invoice** - Generate PDF invoice
2. ✨ **Post to GL** - Integration dengan General Ledger
3. ✨ **Cancel Invoice** - Cancel dengan reason & audit
4. ✨ **Invoice History** - View all amendments timeline
5. ✨ **Bulk Amend** - Amend multiple invoices sekaligus
6. ✨ **Email Notification** - Notify customer saat amend
7. ✨ **Approval Workflow** - Require approval untuk certain changes

---

## ✅ Checklist Completion

- [x] Main Screen UI (Period, Invoice# input, Search)
- [x] Invoice Table Modal (List, Detail Panel, Search, Select)
- [x] Edit Screen (All fields, Save/Cancel)
- [x] Customer Sales Tax Modal (Table, Select)
- [x] Date Picker Modal (Calendar, Navigation)
- [x] Final Screen Modal (Tax calculation, OK/Cancel)
- [x] Backend API - index() (List invoices)
- [x] Backend API - show() (Get invoice details)
- [x] Backend API - update() (Amend with validation)
- [x] Business Rules Validation (Print/Post/Cancel check)
- [x] Audit Trail Implementation (AM_UID, AM_DATE, AM_TIME)
- [x] Date Format Conversion (DD/MM/YYYY ↔ YYYY-MM-DD)
- [x] Error Handling & Logging
- [x] CPS Compatible Design & Layout
- [x] Responsive UI dengan TailwindCSS
- [x] Documentation

---

## 📞 Support

Jika ada pertanyaan atau issue:
1. Check log di `storage/logs/laravel.log`
2. Verify database schema di `2025_10_14_000000_create_inv_table.php`
3. Test API endpoints via Postman/Thunder Client
4. Review business rules di dokumentasi ini

---

**Implementation Date**: November 5, 2025  
**CPS Version**: Enterprise 2020  
**Status**: ✅ **COMPLETE & READY FOR TESTING**  
**100% CPS Compatible** 🎉
