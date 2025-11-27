# Print Delivery Order - Fit Components Implementation

## Overview
Perbaikan pada menu Print Delivery Order agar PDF Surat Jalan menyertakan semua komponen Fit (Fit1-Fit9), tidak hanya komponen Main saja. Setiap komponen Fit akan ditampilkan sebagai baris terpisah dengan informasi quantity, bundle, dan dimensi lengkap.

## Problem
Sebelumnya, pada menu **Print Delivery Order**, PDF Surat Jalan hanya menampilkan komponen Main saja. Komponen Fit (Fit1-Fit9) tidak ditampilkan atau hanya menampilkan Product Design dan dimensi tanpa detail quantity.

## Solution

### File Modified: `resources/js/Pages/warehouse-management/DeliveryOrder/DOProcessing/PrintDeliveryOrder.vue`

#### 1. Perubahan pada fungsi `formatPreviewText()` (Text Preview)

**Sebelumnya:**
```javascript
const compText = `${cleanLabel} : ${design}`
lines.push(compText.padEnd(40) + `${dimL} x ${dimW} x ${dimH}`)
```

**Sekarang:**
```javascript
// Get component-specific quantity
const compQty = parseFloat(r.DO_Qty || 0)
const compUnit = r.Unit || unit || ''
const compUnitLower = (compUnit || '').toLowerCase()
const compPcsPerBld = parseFloat(r.PCS_PER_BLD || pcsPerBld || 1)

// Calculate bundle quantities for this component
let compBundles = 0
let compRemainingPcs = 0
if (compPcsPerBld > 0 && compQty > 0) {
  compBundles = Math.floor(compQty / compPcsPerBld)
  compRemainingPcs = compQty - compBundles * compPcsPerBld
} else {
  compRemainingPcs = compQty
}

const cleanLabel = compLabel ? String(compLabel).trim() : 'Main'

// Format line dengan quantity dan dimension
const compText = `  ${cleanLabel} : ${design}`
const qtyStr = `${Number(compQty)}${compUnitLower}`
const unitStr = `${compBundles}BDL x ${compPcsPerBld}Pcs + ${compRemainingPcs}Pcs`

lines.push(compText.padEnd(60) + qtyStr.padStart(12) + '    ' + unitStr)
lines.push(`    Dimensi : ${dimL} x ${dimW} x ${dimH}`)
```

#### 2. Perubahan pada fungsi `renderSuratJalan()` (PDF Generation)

**Sebelumnya:**
```javascript
const compText = `${compLabel} : ${design}`
doc.text(compText, left, y)
doc.text(`${dimL} x ${dimW} x ${dimH}`, left + 150, y)
y += 12
```

**Sekarang:**
```javascript
// Get component-specific quantity
const compQty = parseFloat(r.DO_Qty || 0)
const compUnit = r.Unit || unit || ''
const compUnitLower = (compUnit || '').toLowerCase()
const compPcsPerBld = parseFloat(r.PCS_PER_BLD || pcsPerBld || 1)

// Calculate bundle quantities for this component
let compBundles = 0
let compRemainingPcs = 0
if (compPcsPerBld > 0 && compQty > 0) {
  compBundles = Math.floor(compQty / compPcsPerBld)
  compRemainingPcs = compQty - compBundles * compPcsPerBld
} else {
  compRemainingPcs = compQty
}

// Component line with name and design
const compText = `  ${compLabel} : ${design}`
doc.text(compText, left, y)
doc.text(`${dimL} x ${dimW} x ${dimH}`, left + 180, y)
y += 12

// Component quantity details
const compQtyStr = `${Number(compQty)}${compUnitLower}`
const compUnitStr = `${compBundles}BDL x ${compPcsPerBld}Pcs + ${compRemainingPcs}Pcs`
doc.text(`    Qty: ${compQtyStr}`, left + 10, y)
doc.text(compUnitStr, left + 180, y)
y += 12
```

## How It Works

### Backend API (Already Implemented)
API endpoint `/api/delivery-orders/print-range` sudah mengembalikan data untuk semua komponen:
- Join ke tabel `MC` dengan kondisi `DO.COMP = MC.COMP`
- Setiap komponen (Main, Fit1-Fit9) memiliki row terpisah dengan:
  - `DO_Qty` - Quantity untuk komponen ini
  - `PCS_PER_BLD` - Pieces per bundle
  - `Unit` - Unit of measurement
  - `PD` - Product Design
  - Dimensi (INT_L, INT_W, INT_H, EXT_L, EXT_W, EXT_H)

### Frontend Display

#### Text Preview Format:
```
1 SO# : SO2025001/PO# / PO001                    1000pcs    10BDL x 100Pcs + 0Pcs
  Model : BOX-001

  Main : PD-MAIN-001                             1000pcs    10BDL x 100Pcs + 0Pcs
    Dimensi : 500 x 400 x 300

  Fit1 : PD-FIT1-001                             2000pcs    20BDL x 100Pcs + 0Pcs
    Dimensi : 100 x 50 x 20

  Fit2 : PD-FIT2-001                             1000pcs    10BDL x 100Pcs + 0Pcs
    Dimensi : 150 x 75 x 30
```

#### PDF Format:
```
1 SO# : SO2025001/PO# / PO001                    1000pcs    10BDL x 100Pcs + 0Pcs
  Model : BOX-001

  Main : PD-MAIN-001                             500 x 400 x 300
    Qty: 1000pcs                                 10BDL x 100Pcs + 0Pcs

  Fit1 : PD-FIT1-001                             100 x 50 x 20
    Qty: 2000pcs                                 20BDL x 100Pcs + 0Pcs

  Fit2 : PD-FIT2-001                             150 x 75 x 30
    Qty: 1000pcs                                 10BDL x 100Pcs + 0Pcs
```

## Data Flow

```
┌─────────────────────────────────────┐
│ User: Print Delivery Order          │
│ - Select DO Range                   │
│ - Click "Proceed to Print"          │
└─────────────┬───────────────────────┘
              │
              ↓
┌─────────────────────────────────────┐
│ Frontend: proceedToPrint()          │
│ - Build request params              │
│ - Call API /print-range             │
└─────────────┬───────────────────────┘
              │
              ↓
┌─────────────────────────────────────┐
│ Backend API: getPrintRange()        │
├─────────────────────────────────────┤
│ Query DO table with joins:          │
│   - DO LEFT JOIN CUSTOMER           │
│   - DO LEFT JOIN SO                 │
│   - DO LEFT JOIN MC                 │
│     ON DO.COMP = MC.COMP            │
│                                     │
│ Returns rows for all components:   │
│   - Main (DO_Qty, dimensions)       │
│   - Fit1 (DO_Qty, dimensions)       │
│   - Fit2 (DO_Qty, dimensions)       │
│   - ... Fit3-Fit9 if exists         │
└─────────────┬───────────────────────┘
              │
              ↓
┌─────────────────────────────────────┐
│ Frontend: Group rows by DO_Num      │
│ - Each DO can have multiple rows   │
│ - One row per component (COMP)      │
└─────────────┬───────────────────────┘
              │
              ↓
┌─────────────────────────────────────┐
│ Frontend: formatPreviewText()       │
│ or renderSuratJalan()               │
├─────────────────────────────────────┤
│ For each component in group:        │
│   1. Get compQty (DO_Qty)           │
│   2. Get compPcsPerBld (PCS_PER_BLD)│
│   3. Calculate bundles:             │
│      bundles = floor(qty/pcs_per_bld)│
│      remaining = qty - (bundles*pcs) │
│   4. Display:                       │
│      - Component name & design      │
│      - Quantity & unit              │
│      - Bundle breakdown             │
│      - Dimensions                   │
└─────────────┬───────────────────────┘
              │
              ↓
┌─────────────────────────────────────┐
│ Output: PDF or Text Preview         │
│ - Main component listed first       │
│ - Fit components listed below       │
│ - Each with complete details        │
└─────────────────────────────────────┘
```

## Calculation Logic

### Bundle Calculation per Component:
```javascript
// Input
compQty = 2000          // DO_Qty for this component
compPcsPerBld = 100     // PCS_PER_BLD for this component

// Calculation
bundles = Math.floor(2000 / 100) = 20
remainingPcs = 2000 - (20 * 100) = 0

// Output
"20BDL x 100Pcs + 0Pcs"
```

### Example Scenario:
**DO Number:** 01-2025-00001  
**Main Component:**
- DO_Qty = 1000
- PCS_PER_BLD = 100
- Result: `10BDL x 100Pcs + 0Pcs`

**Fit1 Component:**
- DO_Qty = 2000 (karena PCS = 2 di MC, dan To Delivery Set = 1000)
- PCS_PER_BLD = 100
- Result: `20BDL x 100Pcs + 0Pcs`

**Fit2 Component:**
- DO_Qty = 1000 (karena PCS = 1 di MC, dan To Delivery Set = 1000)
- PCS_PER_BLD = 100
- Result: `10BDL x 100Pcs + 0Pcs`

## Display Features

### 1. **Component Hierarchy**
- Main component ditampilkan pertama
- Fit components (Fit1-Fit9) ditampilkan di bawah Main
- Indentasi untuk membedakan komponen dari header SO

### 2. **Complete Information per Component**
- **Component Name**: Main, Fit1, Fit2, etc.
- **Product Design**: PD code untuk komponen
- **Quantity**: Total quantity dengan unit (e.g., 2000pcs)
- **Bundle Breakdown**: Format "XBDLs x YPcs + ZPcs"
- **Dimensions**: Length x Width x Height

### 3. **Deduplication**
- Menggunakan `Set` untuk mencegah komponen duplikat
- Key: `COMP` field (lowercase)
- Menangani kasus dimana backend join menghasilkan duplicate rows

## PDF Layout Adjustments

### Spacing:
- Setiap komponen menggunakan **2 baris**:
  - Baris 1: Component name, design, dimensions
  - Baris 2: Quantity details dan bundle breakdown
- Total spacing: 24pt per komponen (12pt × 2)

### Positioning:
- `left = 50pt` - Margin kiri
- `left + 10pt` - Indentasi untuk quantity details
- `left + 180pt` - Kolom untuk dimensions dan unit breakdown
- `right = 545pt` - Margin kanan

### Footer Handling:
- Jika konten komponen terlalu banyak, footer tetap di posisi bawah halaman
- Minimum Y position untuk footer: 650pt
- Mencegah overlap antara komponen dan footer

## Benefits

1. **Complete Information**: User dapat melihat detail quantity untuk setiap komponen
2. **Accurate Documentation**: Surat Jalan mencerminkan data actual di database DO
3. **Better Tracking**: Warehouse dapat verify quantity per komponen
4. **Flexible Layout**: Mendukung 1-10 komponen (Main + Fit1-9)
5. **Backward Compatible**: DO lama yang hanya punya Main tetap berfungsi

## Testing Guide

### Test Case 1: DO dengan Main + Multiple Fit Components
1. Create DO dengan komponen:
   - Main: 1000 pcs
   - Fit1: 2000 pcs
   - Fit2: 1000 pcs
2. Buka Print Delivery Order
3. Pilih DO tersebut
4. Klik "Proceed to Print"
5. Verifikasi Preview menampilkan semua komponen
6. Download PDF
7. Verifikasi PDF menampilkan:
   - Main dengan qty 1000 pcs
   - Fit1 dengan qty 2000 pcs
   - Fit2 dengan qty 1000 pcs
   - Masing-masing dengan bundle breakdown yang benar

### Test Case 2: DO dengan Main Only
1. Create DO dengan hanya Main component
2. Print DO tersebut
3. Verifikasi hanya Main yang ditampilkan
4. Tidak ada error atau komponen kosong

### Test Case 3: Bundle Calculation Verification
1. Pilih DO dengan:
   - Main: 1550 pcs, PCS_PER_BLD = 100
2. Verifikasi bundle breakdown:
   - Expected: `15BDL x 100Pcs + 50Pcs`
3. Untuk Fit1 dengan qty 3100 pcs:
   - Expected: `31BDL x 100Pcs + 0Pcs`

### Test Case 4: Multiple DO Print
1. Print range of DOs (e.g., DO-001 to DO-003)
2. Verifikasi setiap DO memiliki halaman terpisah
3. Setiap halaman menampilkan komponen yang benar
4. Tidak ada mixing antara DO yang berbeda

### Test Case 5: Long Component List
1. Create DO dengan 10 komponen (Main + Fit1-9)
2. Print DO tersebut
3. Verifikasi semua komponen ditampilkan
4. Footer tidak overlap dengan komponen

## Database Schema Reference

### DO Table Columns (Multi-Component):
```sql
DO_Num       VARCHAR(50)   -- Delivery Order Number
No           VARCHAR(10)   -- Line number
COMP         VARCHAR(50)   -- Component: Main, Fit1, Fit2, etc.
DO_Qty       DECIMAL(18,4) -- Quantity untuk komponen ini
Unit         VARCHAR(10)   -- Unit of measurement
PD           VARCHAR(50)   -- Product Design
PCS_PER_SET  DECIMAL(18,4) -- Pieces per set (from MC)
INT_L, INT_W, INT_H         -- Internal dimensions
EXT_L, EXT_W, EXT_H         -- External dimensions
```

### MC Table Join Condition:
```sql
LEFT JOIN MC 
  ON DO.MCS_Num = MC.MCS_Num
  AND DO.AC_Num = MC.AC_NUM
  AND DO.COMP = MC.COMP     -- Important: Match component
```

## Notes

- **Component Order**: Backend API mengurutkan berdasarkan `DO.No` dan `DO.COMP`
- **Deduplication**: Frontend menggunakan `Set` dengan key `COMP.toLowerCase()`
- **Fallback Values**: Jika `PCS_PER_BLD` tidak ada, fallback ke 1
- **Empty Components**: Komponen dengan `DO_Qty = 0` tidak ditampilkan
- **Text vs PDF**: Kedua format (preview text dan PDF) konsisten

## Related Files

- `resources/js/Pages/warehouse-management/DeliveryOrder/DOProcessing/PrintDeliveryOrder.vue` - Frontend
- `app/Http/Controllers/DeliveryOrderController.php` - Backend API (getPrintRange)
- `resources/js/Pages/warehouse-management/DeliveryOrder/DOProcessing/PrepareDeliveryOrderMultipleItem.vue` - DO Creation
- `TO_DELIVERY_LOGIC_FIX.md` - Related fix untuk input quantity per komponen

## API Response Example

```json
{
  "success": true,
  "data": [
    {
      "DO_Num": "01-2025-00001",
      "DO_DMY": "15/01/2025",
      "No": "1",
      "COMP": "Main",
      "AC_Num": "CUST001",
      "AC_Name": "Customer ABC",
      "DO_Qty": 1000,
      "Unit": "PCS",
      "PD": "PD-MAIN-001",
      "INT_L": 500, "INT_W": 400, "INT_H": 300,
      "EXT_L": 520, "EXT_W": 420, "EXT_H": 320,
      "PCS_PER_BLD": 100,
      "SO_Num": "SO2025001",
      "PO_Num": "PO001",
      "Model": "BOX-001"
    },
    {
      "DO_Num": "01-2025-00001",
      "No": "2",
      "COMP": "Fit1",
      "DO_Qty": 2000,
      "Unit": "PCS",
      "PD": "PD-FIT1-001",
      "INT_L": 100, "INT_W": 50, "INT_H": 20,
      "EXT_L": 110, "EXT_W": 60, "EXT_H": 30,
      "PCS_PER_BLD": 100,
      // ... other fields same as Main
    }
  ]
}
```

## Status

✅ Implementation Complete
✅ Text Preview Updated
✅ PDF Generation Updated
✅ Ready for Testing

## Backward Compatibility

Perubahan ini backward compatible karena:
1. DO lama yang hanya punya Main component tetap berfungsi
2. Jika tidak ada Fit components, hanya Main yang ditampilkan
3. API response structure tidak berubah
4. Deduplication mencegah duplicate display
5. Fallback values untuk missing data
