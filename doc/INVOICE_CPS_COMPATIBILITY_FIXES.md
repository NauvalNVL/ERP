# Invoice Menu - CPS Compatibility Fixes

## 📋 Overview
Perbaikan menu **Prepare Invoice by D/Order (Current Period)** agar 100% sesuai dengan CPS Enterprise 2020 **tanpa mengubah struktur tabel DO atau membuat tabel baru**.

## ✅ Masalah yang Diperbaiki

### 1. **Double Invoicing Prevention** ✅ FIXED
**Masalah:** D/O yang sudah fully invoiced masih bisa dipilih lagi untuk di-invoice.

**Solusi CPS-Compatible:**
- Calculate `invoiced_qty` dari tabel **INV** (bukan dari kolom tambahan di DO)
- Filter D/O dengan `remaining_qty > 0` di `currentPeriodDo()`
- Validasi di `prepare()` sebelum create invoice
- Update status DO otomatis: `Open` → `Partial` → `Invoiced`

**Formula:**
```php
$invoicedQty = DB::table('INV')
    ->where('SO_NUM', $do->SO_Num)
    ->where('IV_STS', '!=', 'Cancelled')
    ->sum('IV_QTY');

$remainingQty = $doQty - $invoicedQty;

if ($remainingQty <= 0) {
    $status = 'Invoiced'; // Fully invoiced
} elseif ($invoicedQty > 0) {
    $status = 'Partial'; // Partially invoiced
} else {
    $status = 'Open'; // Not invoiced yet
}
```

---

### 2. **Partial Invoice Support** ✅ FIXED
**Masalah:** Sistem belum support partial invoicing dengan baik.

**Solusi:**
- Track `invoiced_qty` dan `remaining_qty` secara real-time dari INV table
- Visual indicator di modal: **Open** / **Partial: 600/1000** / **✓ Invoiced**
- User bisa invoice D/O sebagian (misal: DO_Qty=1000, invoice 600 dulu, nanti 400 lagi)

**UI Display:**
```
Status Column:
┌─────────────────────┐
│ Draft               │ ← DO Status
│ ⚠ Partial: 600/1000 │ ← Invoice Status (CPS-compatible)
└─────────────────────┘
```

---

### 3. **Status Update Logic** ✅ FIXED
**Masalah:** Status DO tidak update otomatis setelah invoice dibuat.

**Solusi:**
- Setelah insert ke INV, re-calculate `invoiced_qty`
- Update DO.Status berdasarkan `remaining_qty`
- Log lengkap untuk audit trail

**Kode:**
```php
// After invoice created
$newInvoicedQty = DB::table('INV')
    ->where('SO_NUM', $do->SO_Num)
    ->where('IV_STS', '!=', 'Cancelled')
    ->sum('IV_QTY');

$newRemainingQty = $doQty - $newInvoicedQty;

$newStatus = 'Open';
if ($newRemainingQty <= 0) {
    $newStatus = 'Invoiced';
} elseif ($newInvoicedQty > 0) {
    $newStatus = 'Partial';
}

DB::table('DO')
    ->where('DO_Num', $doNumber)
    ->update(['Status' => $newStatus]);
```

---

## 📁 Files Modified

### Backend (PHP)
1. **`app/Http/Controllers/WarehouseManagement/Invoice/InvoiceController.php`**
   - ✅ Updated `currentPeriodDo()` - Calculate invoiced_qty & remaining_qty
   - ✅ Updated `prepare()` - Validasi double invoicing
   - ✅ Updated DO status update logic - CPS-compatible
   - ✅ Added `getDoStatus()` - New endpoint untuk check DO status

### Routes
2. **`routes/api.php`**
   - ✅ Added route: `GET /api/invoices/do-status`

### Frontend (Vue.js)
3. **`resources/js/Components/DeliveryOrderTableModal.vue`**
   - ✅ Added invoice status badge display
   - ✅ Show: Open / Partial: X/Y / ✓ Invoiced
   - ✅ Map data dari backend (do_qty, invoiced_qty, remaining_qty, invoice_status)

---

## 🔧 API Endpoints

### New Endpoint
```http
GET /api/invoices/do-status?do_number=DO-2025-001
```

**Response:**
```json
{
  "do_number": "DO-2025-001",
  "so_number": "SO-2025-001",
  "do_qty": 1000,
  "invoiced_qty": 600,
  "remaining_qty": 400,
  "invoice_status": "Partial",
  "do_status": "Partial",
  "can_invoice": true
}
```

### Updated Endpoint
```http
GET /api/invoices/current-period-do?year=2025&month=10&customer_code=000004
```

**Response (Enhanced):**
```json
[
  {
    "do_number": "DO-2025-001",
    "do_date": "2025-10-15",
    "customer_code": "000004",
    "customer_name": "AGILITY INTERNATIONAL, PT.",
    "so_number": "SO-2025-001",
    "currency": "USD",
    "amount": 14700000,
    "do_qty": 1000,
    "invoiced_qty": 600,
    "remaining_qty": 400,
    "invoice_status": "Partial",
    "status": "Partial",
    "vehicle": "B1234XYZ",
    "item": "001"
  }
]
```

---

## 🎯 CPS Behavior Compliance

| Feature | CPS Behavior | Implementation | Status |
|---------|--------------|----------------|--------|
| **Double Invoicing Prevention** | D/O yang sudah fully invoiced tidak bisa dipilih | Calculate dari INV table, filter remaining_qty > 0 | ✅ |
| **Partial Invoice** | Support invoice sebagian (600/1000) | Track invoiced_qty dari INV, show remaining | ✅ |
| **Status Auto-Update** | Status update otomatis: Open → Partial → Invoiced | Re-calculate setelah insert INV | ✅ |
| **Visual Indicator** | Badge menunjukkan status invoice | Badge: Open / Partial: X/Y / ✓ Invoiced | ✅ |
| **Validation** | Prevent invoice D/O yang cancelled | Check DO.Status !== 'Cancelled' | ✅ |
| **Audit Trail** | Log semua perubahan status | Log::info dengan detail lengkap | ✅ |

---

## 🚀 How It Works

### Flow Diagram
```
User Select D/O
    ↓
Frontend fetch /api/invoices/current-period-do
    ↓
Backend calculate:
  - invoiced_qty = SUM(INV.IV_QTY WHERE SO_NUM = DO.SO_Num)
  - remaining_qty = DO.DO_Qty - invoiced_qty
  - invoice_status = (remaining_qty <= 0 ? 'Completed' : invoiced_qty > 0 ? 'Partial' : 'Open')
    ↓
Filter: remaining_qty > 0 (exclude completed)
    ↓
Return to Frontend with invoice_status
    ↓
Frontend display badge:
  - Open: ○ Open (blue)
  - Partial: ⚠ Partial: 600/1000 (yellow)
  - Completed: ✓ Invoiced (gray) - tidak muncul karena sudah di-filter
    ↓
User submit invoice
    ↓
Backend validate:
  - Check remaining_qty > 0
  - Check DO.Status !== 'Cancelled'
    ↓
Insert to INV table
    ↓
Re-calculate invoiced_qty
    ↓
Update DO.Status:
  - remaining_qty <= 0 → 'Invoiced'
  - invoiced_qty > 0 → 'Partial'
  - else → 'Open'
```

---

## 📊 Database Schema (Unchanged)

### Tabel DO (Existing)
```sql
DO_Num          VARCHAR(50)     -- D/O Number
SO_Num          VARCHAR(50)     -- Sales Order Number
DO_Qty          DECIMAL(15,2)   -- D/O Quantity
Status          VARCHAR(50)     -- Status: Open/Partial/Invoiced/Cancelled
```

### Tabel INV (Existing)
```sql
IV_NUM          VARCHAR(50)     -- Invoice Number
SO_NUM          VARCHAR(50)     -- Sales Order Number (link to DO)
IV_QTY          DECIMAL(18,4)   -- Invoice Quantity
IV_STS          VARCHAR(50)     -- Invoice Status: Prepared/Cancelled
```

**Key Point:** 
- ✅ **Tidak ada kolom baru** di tabel DO
- ✅ **Tidak ada tabel baru**
- ✅ Calculate `invoiced_qty` secara **real-time** dari INV table
- ✅ 100% **CPS-compatible**

---

## 🧪 Testing Scenarios

### Scenario 1: First Invoice (Full)
```
DO_Qty: 1000
Invoiced_Qty: 0
Remaining_Qty: 1000
Status: Open

User invoice 1000 pcs
→ INV created with IV_QTY = 1000
→ DO.Status updated to 'Invoiced'
→ Badge: ✓ Invoiced
→ D/O tidak muncul di list lagi (filtered out)
```

### Scenario 2: Partial Invoice
```
DO_Qty: 1000
Invoiced_Qty: 0
Remaining_Qty: 1000
Status: Open

User invoice 600 pcs
→ INV created with IV_QTY = 600
→ DO.Status updated to 'Partial'
→ Badge: ⚠ Partial: 600/1000
→ D/O masih muncul di list dengan remaining_qty = 400
```

### Scenario 3: Second Invoice (Complete)
```
DO_Qty: 1000
Invoiced_Qty: 600 (from previous invoice)
Remaining_Qty: 400
Status: Partial

User invoice 400 pcs
→ INV created with IV_QTY = 400
→ Total invoiced_qty = 1000
→ DO.Status updated to 'Invoiced'
→ Badge: ✓ Invoiced
→ D/O tidak muncul di list lagi
```

### Scenario 4: Prevent Double Invoice
```
DO_Qty: 1000
Invoiced_Qty: 1000
Remaining_Qty: 0
Status: Invoiced

User tries to select this D/O
→ D/O tidak muncul di list (filtered by remaining_qty > 0)
→ If somehow user bypass frontend, backend will reject:
   "Delivery Order DO-2025-001 is already fully invoiced. DO Qty: 1000, Already Invoiced: 1000"
```

---

## ✨ Benefits

1. **✅ No Schema Changes** - Tidak perlu migration atau alter table
2. **✅ CPS-Compatible** - 100% sesuai dengan behavior CPS Enterprise 2020
3. **✅ Real-Time Tracking** - Calculate dari INV table secara real-time
4. **✅ Audit Trail** - Log lengkap untuk debugging
5. **✅ User-Friendly** - Visual indicator jelas (Open/Partial/Invoiced)
6. **✅ Data Integrity** - Prevent double invoicing dengan validasi berlapis
7. **✅ Backward Compatible** - Tidak break existing functionality

---

## 🔍 Debugging

### Check DO Status
```bash
# Via API
curl "http://localhost/api/invoices/do-status?do_number=DO-2025-001"

# Via Laravel Tinker
php artisan tinker
>>> $do = DB::table('DO')->where('DO_Num', 'DO-2025-001')->first();
>>> $invoiced = DB::table('INV')->where('SO_NUM', $do->SO_Num)->where('IV_STS', '!=', 'Cancelled')->sum('IV_QTY');
>>> echo "DO Qty: {$do->DO_Qty}, Invoiced: {$invoiced}, Remaining: " . ($do->DO_Qty - $invoiced);
```

### Check Logs
```bash
# Laravel log
tail -f storage/logs/laravel.log | grep "DO Invoice Status"

# Look for:
# - "DO Invoice Status Check" (validation)
# - "DO status updated successfully (CPS-compatible)" (after insert)
```

---

## 📝 Notes

1. **Performance:** Query ke INV table dilakukan per D/O, sudah optimal dengan index pada `SO_NUM` dan `IV_STS`
2. **Cancelled Invoices:** Invoice yang di-cancel tidak dihitung dalam `invoiced_qty` (filter: `IV_STS != 'Cancelled'`)
3. **Multiple Invoices:** Satu D/O bisa punya multiple invoices (partial), semua di-sum untuk calculate `invoiced_qty`
4. **Status Sync:** DO.Status selalu sync dengan actual invoiced quantity dari INV table

---

## 🎉 Conclusion

Implementasi ini **100% CPS-compatible** dan mengikuti best practice ERP:
- ✅ Prevent double invoicing
- ✅ Support partial invoice
- ✅ Real-time status tracking
- ✅ No schema changes required
- ✅ Audit trail lengkap

**Tanpa mengubah struktur tabel DO atau membuat tabel baru!** 🚀
