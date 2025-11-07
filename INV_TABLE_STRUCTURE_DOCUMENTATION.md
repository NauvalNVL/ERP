# 📋 Dokumentasi Struktur Tabel INV (Invoice)

## 📊 Overview

Tabel `INV` adalah tabel utama yang menyimpan semua data invoice dalam sistem ERP CPS. Tabel ini berisi **114 kolom** yang mencakup informasi lengkap dari periode, customer, produk, dimensi, harga, pajak, hingga audit trail.

---

## 🗂️ Kategori Kolom

Kolom-kolom dalam tabel INV dikelompokkan menjadi beberapa kategori:

1. **Period & Identification** (Periode & Identifikasi)
2. **Customer Information** (Informasi Customer)
3. **Product Details** (Detail Produk)
4. **Dimensions** (Dimensi Produk)
5. **Sales & Organization** (Penjualan & Organisasi)
6. **Order References** (Referensi Order)
7. **Quantities & Pricing** (Kuantitas & Harga)
8. **Amounts** (Jumlah Uang)
9. **Measurements** (Pengukuran M2 & KG)
10. **Tax Information** (Informasi Pajak)
11. **Remarks & Cancellation** (Catatan & Pembatalan)
12. **Audit Trail** (Jejak Audit)
13. **Date Surrogate Keys** (Kunci Tanggal)

---

## 📝 Detail Kolom & Asal Data

### **1. PERIOD & IDENTIFICATION (Periode & Identifikasi)**

| Kolom | Tipe | Asal Data | Keterangan |
|-------|------|-----------|------------|
| `YYYY` | string(50) | **User Input** (Prepare Invoice) | Tahun invoice (contoh: "2025") |
| `MM` | string(50) | **User Input** (Prepare Invoice) | Bulan invoice (contoh: "10" untuk Oktober) |
| `IV_NUM` | string(50) | **Auto-generated** atau **Manual Input** | Nomor invoice unik (contoh: "IV-202510-0001") |
| `IV_STS` | string(50) | **System Generated** | Status invoice: "Prepared", "Printed", "Posted", "Cancelled" |
| `IV_DMY` | string(50) | **User Input** atau **System Date** | Tanggal invoice format DD/MM/YYYY (contoh: "14/10/2025") |

**Proses Input:**
```javascript
// Dari Prepare Invoice Screen
const payload = {
    year: '2025',           // User pilih period
    month: '10',            // User pilih period
    invoice_date: '14/10/2025',  // User input atau default today
    invoice_number_mode: 'auto',  // User pilih: auto atau manual
    manual_invoice_number: ''     // Jika manual, user input
};

// Backend generate IV_NUM
if (mode === 'auto') {
    IV_NUM = `IV-${YYYY}${MM}-${seq}`;  // contoh: IV-202510-0001
}

// Backend set initial status
IV_STS = 'Prepared';  // Status awal
```

---

### **2. CUSTOMER INFORMATION (Informasi Customer)**

| Kolom | Tipe | Asal Data | Keterangan |
|-------|------|-----------|------------|
| `AC_NUM` | string(50) | **DO Table** → `AC_Num` | Kode customer (contoh: "CUST001") |
| `AC_NAME` | string(250) | **DO Table** → `AC_Name` | Nama customer (contoh: "PT. ABC COMPANY") |
| `AR_TERM` | integer | **CUSTOMER Table** → `TERM` | Payment term dalam hari (contoh: 30, 60, 90) |

**Proses Input:**
```php
// Backend: InvoiceController.php - prepare()
$do = DB::table('DO')->where('DO_Num', $doNumber)->first();

// Ambil customer info dari DO
$customerCode = $do->AC_Num;   // → AC_NUM
$customerName = $do->AC_Name;  // → AC_NAME

// Ambil payment term dari CUSTOMER table
$customer = DB::table('CUSTOMER')->where('CODE', $customerCode)->first();
$paymentTerm = (int) round($customer->TERM);  // → AR_TERM
```

---

### **3. PRODUCT DETAILS (Detail Produk)**

| Kolom | Tipe | Asal Data | Keterangan |
|-------|------|-----------|------------|
| `ITEM` | string(50) | **DO Table** → `No` | Nomor item |
| `MCS_NUM` | string(50) | **DO Table** → `MCS_Num` | Master Carton Specification Number |
| `MODEL` | string(250) | **DO Table** → `Model` | Model produk (contoh: "STKF 390 ML MANUAL CARUNG") |
| `PRODUCT` | string(50) | **DO Table** → `Product` | Nama produk |
| `COMP` | string(50) | **DO Table** → `COMP` | Component/Composition |
| `P_DESIGN` | string(50) | **DO Table** → `PD` | Print Design (contoh: "B1", "4C", dll) |
| `PCS_PER_SET` | decimal(18,4) | **DO Table** → `PCS_PER_SET` | Jumlah pieces per set |
| `UNIT` | string(50) | **DO Table** → `Unit` | Satuan (contoh: "Pcs", "Set", "Box") |
| `PART` | string(50) | **DO Table** → `Part_Number` | Part number |
| `FLUTE` | string(50) | **DO Table** → `Flute` | Tipe flute karton (contoh: "BC", "EB") |

**Proses Input:**
```php
// Backend: InvoiceController.php - prepare()
DB::table('INV')->insert([
    'ITEM' => $do->No,
    'MCS_NUM' => $do->MCS_Num,
    'MODEL' => $do->Model,
    'PRODUCT' => $do->Product,
    'COMP' => $do->COMP,
    'P_DESIGN' => $do->PD,
    'PCS_PER_SET' => $do->PCS_PER_SET,
    'UNIT' => $do->Unit,
    'PART' => $do->Part_Number,
    'FLUTE' => $do->Flute,
]);
```

---

### **4. DIMENSIONS (Dimensi Produk)**

| Kolom | Tipe | Asal Data | Keterangan |
|-------|------|-----------|------------|
| `INT_L` | decimal(18,4) | **DO Table** → `INT_L` | Internal Length (Panjang dalam) |
| `INT_W` | decimal(18,4) | **DO Table** → `INT_W` | Internal Width (Lebar dalam) |
| `INT_H` | decimal(18,4) | **DO Table** → `INT_H` | Internal Height (Tinggi dalam) |
| `EXT_L` | decimal(18,4) | **DO Table** → `EXT_L` | External Length (Panjang luar) |
| `EXT_W` | decimal(18,4) | **DO Table** → `EXT_W` | External Width (Lebar luar) |
| `EXT_H` | decimal(18,4) | **DO Table** → `EXT_H` | External Height (Tinggi luar) |

**Contoh Data:**
```
Internal: 38.5 × 28.0 × 24.5 cm
External: 40.0 × 30.0 × 25.0 cm
```

---

### **5. SALES & ORGANIZATION (Penjualan & Organisasi)**

| Kolom | Tipe | Asal Data | Keterangan |
|-------|------|-----------|------------|
| `SLM` | string(50) | **DO Table** → `SLM` | Salesperson code (contoh: "S108") |
| `IND` | string(50) | **DO Table** → `IND` | Industry type |
| `AREA` | string(50) | **DO Table** → `Area1` | Sales area/region |
| `GROUP_` | string(50) | **DO Table** → `Group_` | Product group |

**Proses Input:**
```php
DB::table('INV')->insert([
    'SLM' => $do->SLM,      // Salesperson dari DO
    'IND' => $do->IND,      // Industry
    'AREA' => $do->Area1,   // Area penjualan
    'GROUP_' => $do->Group_,// Group produk
]);
```

---

### **6. ORDER REFERENCES (Referensi Order)**

| Kolom | Tipe | Asal Data | Keterangan |
|-------|------|-----------|------------|
| `IV_SECOND_REF` | string(50) | **User Input** atau **DO Table** → `DO_Num` | Reference kedua (biasanya DO number) |
| `SO_NUM` | string(50) | **DO Table** → `SO_Num` | Sales Order number |
| `SO_TYPE` | string(50) | **DO Table** → `SO_Type` | Tipe SO (contoh: "Regular", "Sample") |
| `SO_DMY` | string(50) | **SO Table** → `SO_DMY` | Tanggal Sales Order (format: DD/MM/YYYY) |
| `PO_NUM` | string(250) | **DO Table** → `PO_Num` | Purchase Order number dari customer |
| `PO_DMY` | string(50) | **DO Table** → `PO_Date` | Tanggal PO customer |
| `LOT_NUM` | string(50) | **DO Table** → `LOT_Num` | Lot/Batch number |

**Proses Input:**
```php
// Get SO_DMY dari SO table (DO table tidak punya SO_Date)
$soData = DB::table('so')->where('SO_Num', $do->SO_Num)->first();
$soDmy = $soData ? $soData->SO_DMY : null;

DB::table('INV')->insert([
    'IV_SECOND_REF' => $secondRef ?? $do->DO_Num,  // User input atau default DO_Num
    'SO_NUM' => $do->SO_Num,
    'SO_TYPE' => $do->SO_Type,
    'SO_DMY' => $soDmy,              // Dari SO table
    'PO_NUM' => $do->PO_Num,
    'PO_DMY' => $do->PO_Date,
    'LOT_NUM' => $do->LOT_Num,
]);
```

---

### **7. QUANTITIES & PRICING (Kuantitas & Harga)**

| Kolom | Tipe | Asal Data | Keterangan |
|-------|------|-----------|------------|
| `SO_PQ1` | string(50) | **DO Table** → `PQ1` | Quantity 1 (dari SO) |
| `SO_PQ2` | string(50) | **DO Table** → `PQ2` | Quantity 2 |
| `SO_PQ3` | string(50) | **DO Table** → `PQ3` | Quantity 3 |
| `SO_PQ4` | string(50) | **DO Table** → `PQ4` | Quantity 4 |
| `SO_PQ5` | string(50) | **DO Table** → `PQ5` | Quantity 5 |
| `IV_QTY` | decimal(18,4) | **DO Table** → `DO_Qty` | Quantity yang di-invoice |
| `IV_UNIT_PRICE` | decimal(18,4) | **DO Table** → `SO_Unit_Price` | Harga per unit |
| `CURR` | string(50) | **DO Table** → `Curr` | Currency code (contoh: "IDR", "USD") |
| `EX_RATE` | decimal(18,6) | **DO Table** → `Ex_Rate` | Exchange rate (contoh: 1.000000 untuk IDR) |

**Proses Input:**
```php
DB::table('INV')->insert([
    'SO_PQ1' => $do->PQ1,
    'SO_PQ2' => $do->PQ2,
    'SO_PQ3' => $do->PQ3,
    'SO_PQ4' => $do->PQ4,
    'SO_PQ5' => $do->PQ5,
    'IV_QTY' => $do->DO_Qty,           // 11,400 pcs
    'IV_UNIT_PRICE' => $do->SO_Unit_Price,  // 1,501.00
    'CURR' => $do->Curr,               // IDR
    'EX_RATE' => $do->Ex_Rate ?? 1.0,  // 1.000000
]);
```

---

### **8. AMOUNTS (Jumlah Uang)**

| Kolom | Tipe | Asal Data | Keterangan |
|-------|------|-----------|------------|
| `IV_TRAN_AMT` | decimal(18,2) | **Calculated** atau **DO Table** → `DO_Tran_Amt` | Transaction amount (subtotal) |
| `IV_BASE_AMT` | decimal(18,2) | **Calculated** atau **DO Table** → `DO_Base_Amt` | Base amount (dalam currency dasar) |

**Proses Perhitungan:**
```php
// Priority 1: Ambil dari DO table
$tranAmount = $do->DO_Tran_Amt;
$baseAmount = $do->DO_Base_Amt;

// Priority 2: Jika DO_Tran_Amt = 0, hitung manual
if ($tranAmount == 0) {
    $doQty = $do->DO_Qty;           // 11,400
    $unitPrice = $do->SO_Unit_Price; // 1,501.00
    $exRate = $do->Ex_Rate ?? 1;     // 1.0
    
    $tranAmount = round($doQty * $unitPrice, 2);  // 11,400 × 1,501 = 17,111,400.00
    $baseAmount = round($tranAmount * $exRate, 2); // 17,111,400 × 1 = 17,111,400.00
}

DB::table('INV')->insert([
    'IV_TRAN_AMT' => $tranAmount,  // 17,111,400.00
    'IV_BASE_AMT' => $baseAmount,  // 17,111,400.00
]);
```

---

### **9. MEASUREMENTS (Pengukuran M2 & KG)**

| Kolom | Tipe | Asal Data | Keterangan |
|-------|------|-----------|------------|
| `MC_GROSS_M2_PER__PCS` | decimal(18,4) | **DO Table** → `MC_Gross_M2_Per_Pcs` | Gross M2 per piece |
| `MC_NET_M2_PER_PCS` | decimal(18,4) | **DO Table** → `MC_Net_M2_Per_Pcs` | Net M2 per piece |
| `TOTAL_IV_GROSS_M2` | decimal(18,4) | **DO Table** → `Total_DO_Gross_M2` | Total gross M2 untuk invoice |
| `TOTAL_IV_NET_M2` | decimal(18,4) | **DO Table** → `Total_DO_Net_M2` | Total net M2 untuk invoice |
| `MC_GROSS_KG_PER_PCS` | decimal(18,4) | **DO Table** → `MC_Gross_Kg_Per_Pcs` | Gross KG per piece |
| `MC_NET_KG_PER_PCS` | decimal(18,4) | **DO Table** → `MC_Net_Kg_Per_Pcs` | Net KG per piece |
| `TOTAL_IV_GROSS_KG` | decimal(18,4) | **DO Table** → `Total_DO_Gross_KG` | Total gross KG untuk invoice |
| `TOTAL_IV_NET_KG` | decimal(18,4) | **Calculated** atau **DO Table** | Total net KG untuk invoice |

**Proses Perhitungan TOTAL_IV_NET_KG:**
```php
// Priority 1: Ambil dari DO table
$totalNetKg = $do->Total_DO_Net_KG;

// Priority 2: Jika tidak ada, hitung dari KG per piece × Qty
if (!$totalNetKg || $totalNetKg == 0) {
    $kgPerPcs = $do->MC_Net_Kg_Per_Pcs;
    $doQty = $do->DO_Qty;
    
    if ($kgPerPcs && $doQty > 0) {
        $totalNetKg = round($kgPerPcs * $doQty, 4);
    }
}

DB::table('INV')->insert([
    'TOTAL_IV_NET_KG' => $totalNetKg,
    // ... kolom M2 & KG lainnya dari DO
]);
```

---

### **10. TAX INFORMATION (Informasi Pajak)**

| Kolom | Tipe | Asal Data | Keterangan |
|-------|------|-----------|------------|
| `IV_TAX_CODE` | string(50) | **User Input** → **taxrate Table** | Kode pajak (contoh: "PPN11", "NIL") |
| `IV_TAX_PERCENT` | decimal(5,2) | **User Input** → **taxrate Table** | Persentase pajak (contoh: 11.00) |
| `IV_REMARK` | string(50) | **User Input** (Prepare Invoice) | Catatan invoice |

**Proses Input:**
```php
// Frontend: User pilih tax di Check Sales Tax Screen
const selectedTax = {
    code: 'PPN11',
    percent: 11.00
};

// Backend: Lookup tax dari taxrate table
$tax = DB::table('taxrate')->where('TAXCODE', 'PPN11')->first();

DB::table('INV')->insert([
    'IV_TAX_CODE' => $tax->TAXCODE,      // 'PPN11'
    'IV_TAX_PERCENT' => $tax->RATEPPN,   // 11.00
    'IV_REMARK' => $remark,              // User input catatan
]);

// Tax amount calculation (tidak disimpan di tabel INV versi ini)
$taxAmount = round($tranAmount * ($taxPercent / 100), 2);
// 17,111,400 × 11% = 1,882,254.00
```

---

### **11. REMARKS & CANCELLATION (Catatan & Pembatalan)**

| Kolom | Tipe | Asal Data | Keterangan |
|-------|------|-----------|------------|
| `CANCELLED_REASON_1` | string(100) | **User Input** (Cancel Invoice) | Alasan pembatalan utama |
| `cANCELLED_REASON_2` | string(100) | **User Input** (Cancel Invoice) | Alasan pembatalan tambahan (optional) |

**Proses Input:**
```php
// Frontend: Cancel Active Invoice Screen
const cancelForm = {
    reason1: 'Customer request untuk ganti spesifikasi',
    reason2: 'Salah input quantity' // optional
};

// Backend: InvoiceController.php - cancelInvoice()
DB::table('INV')->where('IV_NUM', $invoiceNumber)->update([
    'IV_STS' => 'Cancelled',
    'CANCELLED_REASON_1' => $reason1,
    'cANCELLED_REASON_2' => $reason2,  // nullable
    'CX_UID' => Auth::user()->name,
    'CX_DATE' => now()->format('d/m/Y'),
    'CX_TIME' => now()->format('H:i'),
]);
```

---

### **12. AUDIT TRAIL (Jejak Audit)**

Audit trail mencatat siapa, kapan, dan action apa yang dilakukan terhadap invoice.

#### **A. New (Create) - Prefix: NW_**

| Kolom | Tipe | Asal Data | Keterangan |
|-------|------|-----------|------------|
| `NW_UID` | string(50) | **Auth::user()->name** | User yang membuat invoice |
| `NW_DATE` | string(50) | **now()->format('d/m/Y')** | Tanggal pembuatan (contoh: "14/10/2025") |
| `NW_TIME` | string(50) | **now()->format('H:i')** | Waktu pembuatan (contoh: "14:30") |

```php
// Backend: InvoiceController.php - prepare()
'NW_UID' => Auth::user()->name,      // 'john.doe'
'NW_DATE' => now()->format('d/m/Y'), // '14/10/2025'
'NW_TIME' => now()->format('H:i'),   // '14:30'
```

#### **B. Amend (Modify) - Prefix: AM_**

| Kolom | Tipe | Asal Data | Keterangan |
|-------|------|-----------|------------|
| `AM_UID` | string(50) | **Auth::user()->name** | User yang mengubah invoice |
| `AM_DATE` | string(50) | **now()->format('d/m/Y')** | Tanggal perubahan |
| `AM_TIME` | string(50) | **now()->format('H:i')** | Waktu perubahan |

```php
// Backend: InvoiceController.php - update()
'AM_UID' => Auth::user()->name,      // 'jane.smith'
'AM_DATE' => now()->format('d/m/Y'), // '15/10/2025'
'AM_TIME' => now()->format('H:i'),   // '10:15'
```

#### **C. Cancel - Prefix: CX_**

| Kolom | Tipe | Asal Data | Keterangan |
|-------|------|-----------|------------|
| `CX_UID` | string(50) | **Auth::user()->name** | User yang membatalkan invoice |
| `CX_DATE` | string(50) | **now()->format('d/m/Y')** | Tanggal pembatalan |
| `CX_TIME` | string(50) | **now()->format('H:i')** | Waktu pembatalan |

```php
// Backend: InvoiceController.php - cancelInvoice()
'CX_UID' => Auth::user()->name,      // 'admin.user'
'CX_DATE' => now()->format('d/m/Y'), // '16/10/2025'
'CX_TIME' => now()->format('H:i'),   // '09:20'
```

#### **D. Print - Prefix: PT_**

| Kolom | Tipe | Asal Data | Keterangan |
|-------|------|-----------|------------|
| `PT_UID` | string(50) | **Auth::user()->name** | User yang print/export invoice |
| `PT_DATE` | string(50) | **now()->format('d/m/Y')** | Tanggal print |
| `PT_TIME` | string(50) | **now()->format('H:i')** | Waktu print |

```php
// Backend: InvoiceController.php - updatePrintAudit()
'PT_UID' => Auth::user()->name,      // 'print.user'
'PT_DATE' => now()->format('d/m/Y'), // '17/10/2025'
'PT_TIME' => now()->format('H:i'),   // '11:45'
```

---

### **13. DATE SURROGATE KEYS (Kunci Tanggal)**

| Kolom | Tipe | Asal Data | Keterangan |
|-------|------|-----------|------------|
| `IVDateSK` | integer | **DO Table** → `DODateSK` | Invoice date surrogate key untuk BI |
| `SODateSK` | integer | **DO Table** → `SODateSK` | Sales Order date surrogate key |
| `PODateSK` | integer | **DO Table** → `PODateSK` | Purchase Order date surrogate key |

**Format Surrogate Key:**
```
Format: YYYYMMDD sebagai integer
Contoh: 14 Oktober 2025 → 20251014

IVDateSK = 20251014
SODateSK = 20250901
PODateSK = 20250825
```

**Fungsi:** Untuk keperluan Business Intelligence & Data Warehouse, memudahkan join dengan dimension table tanggal.

---

## 🔄 Alur Data Lengkap (Flow)

### **Prepare Invoice → INV Table**

```
┌─────────────────────────────────────────────────────────────┐
│  1. USER INPUT (Frontend - Prepare Invoice)                │
│     - Select Customer                                       │
│     - Select Tax (PPN11, NIL, dll)                         │
│     - Select Delivery Orders (DO)                          │
│     - Input Invoice Date                                   │
│     - Input Remark                                         │
│     - Choose Invoice Number (Auto/Manual)                  │
└─────────────────┬───────────────────────────────────────────┘
                  │
                  ▼
┌─────────────────────────────────────────────────────────────┐
│  2. BACKEND PROCESSING (InvoiceController.php)             │
│     - Generate/Validate Invoice Number                     │
│     - Fetch DO data from DO table                          │
│     - Fetch Customer payment term from CUSTOMER table      │
│     - Fetch SO_DMY from SO table                           │
│     - Calculate amounts if needed                          │
│     - Calculate measurements (KG, M2)                      │
│     - Prepare audit trail (NW_UID, NW_DATE, NW_TIME)      │
└─────────────────┬───────────────────────────────────────────┘
                  │
                  ▼
┌─────────────────────────────────────────────────────────────┐
│  3. INSERT TO INV TABLE                                     │
│     ┌─────────────────────────────────────────────────┐   │
│     │ Period & ID:                                     │   │
│     │   YYYY, MM, IV_NUM, IV_STS, IV_DMY              │   │
│     ├─────────────────────────────────────────────────┤   │
│     │ Customer:                                        │   │
│     │   AC_NUM, AC_NAME, AR_TERM                      │   │
│     ├─────────────────────────────────────────────────┤   │
│     │ Product (FROM DO):                               │   │
│     │   ITEM, MCS_NUM, MODEL, PRODUCT, COMP,          │   │
│     │   P_DESIGN, PCS_PER_SET, UNIT, PART, FLUTE     │   │
│     ├─────────────────────────────────────────────────┤   │
│     │ Dimensions (FROM DO):                            │   │
│     │   INT_L, INT_W, INT_H, EXT_L, EXT_W, EXT_H     │   │
│     ├─────────────────────────────────────────────────┤   │
│     │ Sales (FROM DO):                                 │   │
│     │   SLM, IND, AREA, GROUP_                        │   │
│     ├─────────────────────────────────────────────────┤   │
│     │ Order Refs (FROM DO + SO):                       │   │
│     │   SO_NUM, SO_TYPE, SO_DMY, PO_NUM, PO_DMY,      │   │
│     │   LOT_NUM, IV_SECOND_REF                        │   │
│     ├─────────────────────────────────────────────────┤   │
│     │ Quantities & Price (FROM DO):                    │   │
│     │   IV_QTY, IV_UNIT_PRICE, CURR, EX_RATE,         │   │
│     │   SO_PQ1, SO_PQ2, SO_PQ3, SO_PQ4, SO_PQ5       │   │
│     ├─────────────────────────────────────────────────┤   │
│     │ Amounts (FROM DO or CALCULATED):                 │   │
│     │   IV_TRAN_AMT, IV_BASE_AMT                      │   │
│     ├─────────────────────────────────────────────────┤   │
│     │ Measurements (FROM DO or CALCULATED):            │   │
│     │   All M2 & KG fields                            │   │
│     ├─────────────────────────────────────────────────┤   │
│     │ Tax (FROM USER + taxrate table):                 │   │
│     │   IV_TAX_CODE, IV_TAX_PERCENT, IV_REMARK        │   │
│     ├─────────────────────────────────────────────────┤   │
│     │ Audit Trail (SYSTEM GENERATED):                  │   │
│     │   NW_UID, NW_DATE, NW_TIME                      │   │
│     ├─────────────────────────────────────────────────┤   │
│     │ Date Keys (FROM DO):                             │   │
│     │   IVDateSK, SODateSK, PODateSK                  │   │
│     └─────────────────────────────────────────────────┘   │
└─────────────────────────────────────────────────────────────┘
```

---

## 📊 Tabel Sumber Data

| Sumber | Kolom yang Diambil | Jumlah |
|--------|-------------------|---------|
| **User Input** | YYYY, MM, IV_DMY, IV_REMARK, IV_SECOND_REF, tax selection | ~5 |
| **System Generated** | IV_NUM, IV_STS, NW_UID, NW_DATE, NW_TIME | ~5 |
| **DO Table** | AC_NUM, AC_NAME, ITEM, MCS_NUM, MODEL, PRODUCT, dimensi, quantities, dll | ~80 |
| **CUSTOMER Table** | AR_TERM | ~1 |
| **SO Table** | SO_DMY | ~1 |
| **taxrate Table** | IV_TAX_CODE, IV_TAX_PERCENT | ~2 |
| **Calculated** | IV_TRAN_AMT, IV_BASE_AMT, TOTAL_IV_NET_KG (jika perlu) | ~3 |

**Total: ~97 kolom** (dari 114 kolom tersedia)

---

## 💾 Contoh Data Lengkap

```sql
INSERT INTO INV VALUES (
    -- Period & ID
    YYYY = '2025',
    MM = '10',
    IV_NUM = 'IV-202510-0001',
    IV_STS = 'Prepared',
    IV_DMY = '14/10/2025',
    
    -- Customer
    AR_TERM = 90,
    AC_NUM = 'CUST001',
    AC_NAME = 'SINAR BOSNO GUNONG SLAMAT, PT',
    IV_SECOND_REF = '10-2025-02691',
    
    -- Product (dari DO)
    ITEM = '1',
    MCS_NUM = 'MCS-2025-001',
    MODEL = 'STKF 390 ML MANUAL CARUNG',
    PRODUCT = 'BOX',
    COMP = '1',
    P_DESIGN = 'B1',
    PCS_PER_SET = 1.0000,
    UNIT = 'Pcs',
    PART = 'PART-001',
    FLUTE = 'BC',
    
    -- Dimensions (dari DO)
    INT_L = 38.5000,
    INT_W = 28.0000,
    INT_H = 24.5000,
    EXT_L = 40.0000,
    EXT_W = 30.0000,
    EXT_H = 25.0000,
    
    -- Sales (dari DO)
    SLM = 'S108',
    IND = 'IND01',
    AREA = 'JAKARTA',
    GROUP_ = 'GRP01',
    
    -- Order References (dari DO + SO)
    SO_NUM = '10-2025-00186',
    SO_TYPE = 'Regular',
    SO_DMY = '01/09/2025',
    PO_NUM = '3349/SGB/09/25/KPB-CKG',
    PO_DMY = '25/08/2025',
    LOT_NUM = 'LOT-2025-001',
    
    -- Quantities (dari DO)
    SO_PQ1 = '11400',
    SO_PQ2 = NULL,
    SO_PQ3 = NULL,
    SO_PQ4 = NULL,
    SO_PQ5 = NULL,
    IV_QTY = 11400.0000,
    IV_UNIT_PRICE = 1501.0000,
    CURR = 'IDR',
    EX_RATE = 1.000000,
    
    -- Amounts (dari DO atau calculated)
    IV_TRAN_AMT = 17111400.00,
    IV_BASE_AMT = 17111400.00,
    
    -- Measurements (dari DO)
    MC_NET_M2_PER_PCS = 0.5400,
    TOTAL_IV_NET_M2 = 6156.0000,
    MC_NET_KG_PER_PCS = 0.2500,
    TOTAL_IV_NET_KG = 2850.0000,
    -- (kolom M2 & KG lainnya...)
    
    -- Tax (dari user + taxrate)
    IV_TAX_CODE = 'PPN11',
    IV_TAX_PERCENT = 11.00,
    IV_REMARK = 'Invoice untuk pengiriman Oktober',
    
    -- Cancellation (NULL saat create)
    CANCELLED_REASON_1 = NULL,
    cANCELLED_REASON_2 = NULL,
    
    -- Audit Trail - New
    NW_UID = 'john.doe',
    NW_DATE = '14/10/2025',
    NW_TIME = '14:30',
    
    -- Audit Trail - Amend (NULL saat create)
    AM_UID = NULL,
    AM_DATE = NULL,
    AM_TIME = NULL,
    
    -- Audit Trail - Cancel (NULL saat create)
    CX_UID = NULL,
    CX_DATE = NULL,
    CX_TIME = NULL,
    
    -- Audit Trail - Print (NULL saat create)
    PT_UID = NULL,
    PT_DATE = NULL,
    PT_TIME = NULL,
    
    -- Date Surrogate Keys
    IVDateSK = 20251014,
    SODateSK = 20250901,
    PODateSK = 20250825
);
```

---

## 🔍 Query untuk Melihat Asal Data

```sql
-- Query untuk trace asal data invoice
SELECT 
    -- Invoice Info
    inv.IV_NUM,
    inv.IV_DMY,
    inv.IV_STS,
    
    -- Customer Info (dari DO)
    inv.AC_NUM,
    inv.AC_NAME,
    inv.AR_TERM,  -- dari CUSTOMER table
    
    -- Product Info (dari DO)
    inv.MODEL,
    inv.IV_QTY,
    inv.IV_UNIT_PRICE,
    inv.IV_TRAN_AMT,
    
    -- Order References (dari DO + SO)
    inv.SO_NUM,
    inv.SO_DMY,  -- dari SO table
    inv.PO_NUM,
    inv.IV_SECOND_REF,
    
    -- Tax Info (dari user + taxrate)
    inv.IV_TAX_CODE,
    inv.IV_TAX_PERCENT,
    
    -- Audit Trail
    inv.NW_UID AS created_by,
    inv.NW_DATE AS created_date,
    inv.PT_UID AS printed_by,
    inv.PT_DATE AS printed_date,
    
    -- Sumber Data (JOIN untuk verifikasi)
    do.DO_Num,
    do.AC_Name AS do_customer,
    do.Model AS do_model,
    cust.TERM AS customer_term,
    so.SO_DMY AS so_date
    
FROM INV inv
LEFT JOIN DO do ON inv.IV_SECOND_REF = do.DO_Num
LEFT JOIN CUSTOMER cust ON inv.AC_NUM = cust.CODE
LEFT JOIN SO so ON inv.SO_NUM = so.SO_Num
WHERE inv.IV_NUM = 'IV-202510-0001';
```

---

## ✅ Summary

### **Asal Data Invoice:**

1. **~5% User Input** - Period, Invoice Date, Remark, Tax Selection
2. **~5% System Generated** - Invoice Number, Status, Audit Trail
3. **~80% dari DO Table** - Product, Dimensions, Quantities, References
4. **~5% dari CUSTOMER Table** - Payment Term
5. **~2% dari SO Table** - SO Date
6. **~2% dari taxrate Table** - Tax Code & Percent
7. **~1% Calculated** - Amounts & Measurements (jika perlu)

### **Total Kolom: 114**
- **Period & ID:** 5
- **Customer:** 3
- **Product:** 10
- **Dimensions:** 7
- **Sales:** 4
- **Order Refs:** 7
- **Quantities:** 13
- **Amounts:** 2
- **Measurements:** 8
- **Tax:** 3
- **Cancellation:** 2
- **Audit Trail:** 12 (4 sets × 3 fields)
- **Date Keys:** 3

---

**Dokumentasi ini membantu developer memahami:**
- ✅ Struktur lengkap tabel INV
- ✅ Asal data setiap kolom
- ✅ Flow data dari input sampai tersimpan
- ✅ Relationship dengan tabel lain (DO, CUSTOMER, SO, taxrate)
- ✅ Business logic untuk calculated fields
- ✅ Audit trail mechanism


