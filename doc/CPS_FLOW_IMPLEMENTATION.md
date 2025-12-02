# CPS Flow Implementation - Complete

## ✅ Flow Sesuai CPS Enterprise 2020

### Alur Lengkap (3 Modal)

```
┌─────────────────────────────────────────────────────────────┐
│ 1. FORM AWAL                                                │
│    - Current Period / Update Period                         │
│    - Customer Code (lookup)                                 │
│    - Currency (auto-populated)                              │
│    - Tax Index No. (optional)                               │
│    - Invoice Date                                           │
│    - 2nd Reference                                          │
│    - Remark                                                 │
│                                                             │
│    [Continue to Prepare] ──────────────────────────────┐   │
└─────────────────────────────────────────────────────────┼───┘
                                                          │
                                                          ▼
┌─────────────────────────────────────────────────────────────┐
│ 2. CHECK SALES TAX SCREEN (Modal 1)                        │
│    ┌──────────┬────────────┬───────┬────────┬─────────┐   │
│    │ Tax Code │ Name       │ Apply │ Tax%   │ Include │   │
│    ├──────────┼────────────┼───────┼────────┼─────────┤   │
│    │ ◉ PPN11  │ PPN 11%    │ Yes   │ 11.00% │ No      │   │
│    └──────────┴────────────┴───────┴────────┴─────────┘   │
│                                                             │
│    [Zoom]                          [Exit]  [OK] ────────┐  │
└─────────────────────────────────────────────────────────┼──┘
                                                          │
                                                          ▼
┌─────────────────────────────────────────────────────────────┐
│ 3. DELIVERY ORDER SCREEN (Modal 2)                         │
│    Toolbar: [👁] [❌] [🔄] [🖨]                              │
│                                                             │
│    ┌────┬──────────┬────────────┬────────┐                │
│    │ No │ D/Order# │ D/O Date   │ Select │                │
│    ├────┼──────────┼────────────┼────────┤                │
│    │ 01 │ DO-001   │ 14/10/2025 │   ☑    │                │
│    │ 02 │ DO-002   │ 14/10/2025 │   ☑    │                │
│    │ 03 │ DO-003   │ 15/10/2025 │   ☐    │                │
│    └────┴──────────┴────────────┴────────┘                │
│                                                             │
│    [Exit]                    [Select (2)] ──────────────┐  │
└─────────────────────────────────────────────────────────┼──┘
                                                          │
                                                          ▼
┌─────────────────────────────────────────────────────────────┐
│ 4. DELIVERY ORDER TABLE (Modal 3) - Optional               │
│    Muncul ketika user klik button [👁] di toolbar          │
│                                                             │
│    ┌──────────┬────────────┬──────────┬─────────┬────────┐│
│    │ D/Order# │ D/O Date   │ Customer │ Vehicle │ Status ││
│    ├──────────┼────────────┼──────────┼─────────┼────────┤│
│    │ DO-001   │ 14/10/2025 │ 000211   │ AB864FS │ Active ││
│    │ DO-002   │ 14/10/2025 │ 000211   │ AB864FS │ Active ││
│    └──────────┴────────────┴──────────┴─────────┴────────┘│
│                                                             │
│    Detail fields: Customer, Salesperson, Order Mode, etc.  │
│                                                             │
│    [Zoom]                    [Select]  [Exit] ──────────┐  │
└─────────────────────────────────────────────────────────┼──┘
                                                          │
                                                          ▼
┌─────────────────────────────────────────────────────────────┐
│ 5. PREPARE INVOICE                                          │
│    - Create invoice records                                 │
│    - Update DO status to "Invoiced"                         │
│    - Show success message                                   │
│    - Reset form                                             │
└─────────────────────────────────────────────────────────────┘
```

## Components Created

### 1. CheckSalesTaxModal.vue ✅
**Location**: `resources/js/Components/CheckSalesTaxModal.vue`

**Purpose**: Validasi dan konfirmasi tax code sebelum prepare invoice

**Features**:
- Display active tax codes from `taxrate` table
- Radio button selection
- Auto-select preselected or first active tax
- Show tax rate percentage
- Apply and Include status badges

**Props**:
- `open` - Boolean
- `customerCode` - String
- `preselectedTaxCode` - String

**Events**:
- `@close` - Close modal
- `@confirm(selectedTax)` - Confirm tax selection

### 2. DeliveryOrderSelectionModal.vue ✅
**Location**: `resources/js/Components/DeliveryOrderSelectionModal.vue`

**Purpose**: Menampilkan list DO untuk dipilih user (sesuai CPS Image 1 & 3)

**Features**:
- List DO dengan nomor urut (01, 02, 03, ...)
- Checkbox untuk multi-select
- Toolbar buttons:
  - 👁 View Details (open DeliveryOrderDetailModal)
  - ❌ Close
  - 🔄 Refresh
  - 🖨 Print
- Selected count indicator
- Select button (disabled jika tidak ada yang dipilih)

**Props**:
- `open` - Boolean
- `customerCode` - String
- `customerName` - String

**Events**:
- `@close` - Close modal
- `@select(selectedDOs)` - Return selected DOs array

### 3. DeliveryOrderDetailModal.vue ✅
**Location**: `resources/js/Components/DeliveryOrderDetailModal.vue`

**Purpose**: Menampilkan detail lengkap DO (sesuai CPS Image 2)

**Features**:
- Full DO table dengan kolom:
  - D/Order#
  - D/O Date
  - Customer
  - Vehicle#
  - Item#
  - P/C
  - Mode
  - Status
- Additional info fields (CPS-style):
  - Customer Name
  - Salesperson
  - Order Mode (radio buttons)
  - Agent Cost
  - Sales Type
  - D/O Instructions
  - Prepared by / Date
  - Amended by / Date
  - Cancelled by / Date
  - Printed by / Date

**Props**:
- `open` - Boolean
- `deliveryOrders` - Array
- `customerCode` - String
- `customerName` - String

**Events**:
- `@close` - Close modal

## Integration

### PrepareInvoicebyDOCurrentPeriod.vue

**Imports**:
```vue
import CheckSalesTaxModal from '@/Components/CheckSalesTaxModal.vue'
import DeliveryOrderSelectionModal from '@/Components/DeliveryOrderSelectionModal.vue'
```

**State**:
```javascript
const checkTaxModalOpen = ref(false)
const doSelectionModalOpen = ref(false)
const preparing = ref(false)
```

**Flow Functions**:
```javascript
// 1. User clicks "Continue to Prepare"
function openFlow(){
  if (!hasCustomer.value) return
  checkTaxModalOpen.value = true
}

// 2. User confirms tax
function onTaxConfirmed(selectedTax){
  taxIndexNo.value = selectedTax.code
  checkTaxModalOpen.value = false
  doSelectionModalOpen.value = true  // Open DO selection
}

// 3. User selects DOs and clicks Select
async function onDOsSelected(selectedDOs){
  doSelectionModalOpen.value = false
  if (selectedDOs.length === 0) {
    alert('No delivery orders selected')
    return
  }
  await prepareInvoice(selectedDOs)
}

// 4. Prepare invoices
async function prepareInvoice(selectedDOs){
  preparing.value = true
  try {
    const doNumbers = selectedDOs.map(d => d.do_number)
    const res = await fetch('/api/invoices/prepare', {
      method: 'POST',
      body: JSON.stringify({
        do_numbers: doNumbers,
        customer_code: customerCode.value,
        tax_index_no: taxIndexNo.value,
        // ... other fields
      })
    })
    const result = await res.json()
    if (result.success) {
      alert(`Success! ${result.data.length} invoice(s) prepared`)
      // Reset form
    }
  } finally {
    preparing.value = false
  }
}
```

## User Experience

### Scenario 1: Normal Flow
1. User fills form (customer, period, etc.)
2. Click "Continue to Prepare"
3. **Check Sales Tax Screen** appears
4. Select tax (e.g., PPN11)
5. Click OK
6. **Delivery Order Screen** appears with list of DOs
7. User checks DO-001 and DO-002
8. Click "Select (2)"
9. System prepares invoices
10. Success message appears
11. Form resets

### Scenario 2: View DO Details
1-6. Same as Scenario 1
7. User clicks 👁 button in toolbar
8. **Delivery Order Table** modal appears
9. Shows detailed DO information
10. User clicks Exit to return to DO selection
11. Continue with DO selection

### Scenario 3: No DOs Found
1-5. Same as Scenario 1
6. **Delivery Order Screen** appears
7. Shows "No delivery orders found for current period"
8. User clicks Exit

## API Endpoints Used

### 1. GET /api/invoices/sales-tax-options
**Used by**: CheckSalesTaxModal

**Response**:
```json
[
  {
    "code": "PPN11",
    "name": "PPN 11%",
    "rate": 11,
    "apply": true,
    "include": false,
    "status": "Active"
  }
]
```

### 2. GET /api/invoices/current-period-do?customer_code={code}
**Used by**: DeliveryOrderSelectionModal

**Response**:
```json
[
  {
    "do_number": "DO-001",
    "do_date": "14/10/2025",
    "customer_code": "000211",
    "customer_name": "ABDULLAH_BPK",
    "vehicle": "AB864FS",
    "item": "1",
    "amount": 1000000,
    "status": null
  }
]
```

### 3. POST /api/invoices/prepare
**Used by**: prepareInvoice function

**Request**:
```json
{
  "do_numbers": ["DO-001", "DO-002"],
  "customer_code": "000211",
  "tax_index_no": "PPN11",
  "invoice_date": "2025-10-16",
  "second_ref": "",
  "remark": "",
  "invoice_number_mode": "auto"
}
```

**Response**:
```json
{
  "success": true,
  "message": "Invoices prepared successfully",
  "data": [
    {
      "invoice_number": "IV-202510-0001",
      "do_number": "DO-001",
      "amount": 1000000,
      "tax_amount": 110000
    }
  ]
}
```

## Testing Checklist

### ✅ Modal 1: Check Sales Tax Screen
- [ ] Modal appears after "Continue to Prepare"
- [ ] Tax list loaded from taxrate table
- [ ] First active tax auto-selected
- [ ] Row highlighting works
- [ ] OK button enabled after selection
- [ ] Tax code saved after confirm

### ✅ Modal 2: Delivery Order Screen
- [ ] Modal appears after tax confirmation
- [ ] DO list loaded for customer
- [ ] Toolbar buttons visible
- [ ] Checkbox selection works
- [ ] Selected count updates
- [ ] Select button disabled when no selection
- [ ] Select button enabled when DOs selected

### ✅ Modal 3: Delivery Order Table (Optional)
- [ ] Modal appears when clicking 👁 button
- [ ] Full DO table displayed
- [ ] Customer info shown
- [ ] Additional fields populated
- [ ] Exit button closes modal

### ✅ Invoice Preparation
- [ ] Invoices created for selected DOs
- [ ] DO status updated to "Invoiced"
- [ ] Success message shows invoice numbers
- [ ] Form resets after success

## Files Structure

```
resources/js/
├── Components/
│   ├── CheckSalesTaxModal.vue              ✅ Created
│   ├── DeliveryOrderSelectionModal.vue     ✅ Created
│   └── DeliveryOrderDetailModal.vue        ✅ Created
└── Pages/
    └── warehouse-management/
        └── Invoice/
            └── IVProcessing/
                └── PrepareInvoicebyDOCurrentPeriod.vue  ✅ Updated
```

## Comparison: Before vs After

### Before (Incorrect)
```
Form → Check Tax → Auto Prepare All DOs ❌
```

### After (CPS Compliant) ✅
```
Form → Check Tax → DO Selection → Prepare Selected DOs ✅
```

## Benefits

### ✅ User Control
- User can select which DOs to invoice
- Not all DOs automatically invoiced
- Can view DO details before selecting

### ✅ CPS Compliant
- Exact same flow as CPS Enterprise 2020
- Same modal sequence
- Same UI elements (toolbar, buttons)

### ✅ Flexible
- Can select all or some DOs
- Can view details before deciding
- Can refresh DO list

## Summary

✅ **3 Modals Implemented**:
1. Check Sales Tax Screen
2. Delivery Order Selection
3. Delivery Order Table (detail view)

✅ **Flow Sesuai CPS**:
- Form → Tax → DO Selection → Prepare

✅ **Features Lengkap**:
- Multi-select DOs
- View DO details
- Toolbar buttons
- Loading states
- Success/error handling

---
**Status**: ✅ Complete and CPS Compliant
**Date**: October 16, 2025
**Version**: 2.0
