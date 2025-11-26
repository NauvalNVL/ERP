# Fix: Display Part Number in Sales Order Screen Modal

## Problem
Pada menu `PrepareDeliveryOrderMultipleItem.vue`, di bagian modal sales order screen, ketika user memilih sales order, kolom **Part#** di bagian item details tidak menampilkan data part number dari tabel DO sesuai dengan komponennya (Main + Fit).

## Root Cause
1. **Backend API (`getSalesOrderDetail`)**: Method ini tidak mengembalikan field `part_number` dalam array `fittings` untuk komponen Fit1-Fit9. Hanya mengembalikan `design`, `pcs`, dan `unit`.

2. **Frontend (`SalesOrderScreenModal.vue`)**: Fungsi `loadItemDetailsFromSo` tidak mengisi field `partNumber` dari response API, baik untuk Main maupun untuk Fit components.

## Solution

### 1. Backend Fix - SalesOrderController.php
**File**: `app/Http/Controllers/SalesOrderController.php`

**Changes**: Menambahkan field `part_number` ke dalam array fittings yang dikembalikan oleh method `getSalesOrderDetail`.

```php
// Before (line ~2203-2210)
$fittings[] = [
    'name' => $compName,
    'design' => $componentRow->P_DESIGN ?? '',
    'pcs' => $componentRow->PCS_SET ?? '',
    'unit' => $componentRow->UNIT ?? '',
];

// After
$fittings[] = [
    'name' => $compName,
    'design' => $componentRow->P_DESIGN ?? '',
    'pcs' => $componentRow->PCS_SET ?? '',
    'unit' => $componentRow->UNIT ?? '',
    'part_number' => $componentRow->PART_NO ?? '',  // ✅ Added
];
```

### 2. Frontend Fix - SalesOrderScreenModal.vue
**File**: `resources/js/Components/SalesOrderScreenModal.vue`

**Changes**: Update fungsi `loadItemDetailsFromSo` untuk mengisi field `partNumber` dari response API.

```javascript
// Before (line ~358-391)
const mainRow = itemDetails.value.find(row => row.name === 'Main')
if (mainRow) {
  mainRow.pDesign = details.pd ?? ''
  mainRow.pcs = details.pcs ?? ''
  mainRow.unit = details.unit ?? ''
}

if (Array.isArray(data.fittings)) {
  data.fittings.forEach((fitting, index) => {
    if (index < 9) {
      const row = itemDetails.value[index + 1]
      if (row) {
        row.pDesign = fitting.design || ''
        row.pcs = fitting.pcs || ''
        row.unit = fitting.unit || ''
      }
    }
  })
}

// After
const mainRow = itemDetails.value.find(row => row.name === 'Main')
if (mainRow) {
  mainRow.pDesign = details.pd ?? ''
  mainRow.pcs = details.pcs ?? ''
  mainRow.unit = details.unit ?? ''
  mainRow.partNumber = data.part_number ?? ''  // ✅ Added for Main
}

if (Array.isArray(data.fittings)) {
  data.fittings.forEach((fitting, index) => {
    if (index < 9) {
      const row = itemDetails.value[index + 1]
      if (row) {
        row.pDesign = fitting.design || ''
        row.pcs = fitting.pcs || ''
        row.unit = fitting.unit || ''
        row.partNumber = fitting.part_number || ''  // ✅ Added for Fits
      }
    }
  })
}
```

## Data Flow

### Source Data
Data `part_number` diambil dari:
- **Tabel MC** → kolom `PART_NO`
- Disimpan ke **tabel SO** → kolom `PART_NUMBER` saat sales order dibuat
- Disimpan ke **tabel DO** → kolom `Part_Number` saat delivery order dibuat

### Flow
1. User memilih sales order di modal Sales Order Screen
2. Frontend memanggil API: `GET /api/sales-order/{soNumber}/detail`
3. Backend (`getSalesOrderDetail` method):
   - Query tabel SO untuk mendapatkan data sales order
   - Query tabel MC untuk mendapatkan data komponen (Main + Fit1-Fit9)
   - Return response termasuk `part_number` untuk setiap komponen
4. Frontend (`loadItemDetailsFromSo` function):
   - Menerima response dari API
   - Mengisi field `partNumber` di itemDetails untuk Main dan Fit components
5. Vue template menampilkan data di kolom Part# di tabel Item Details

## Testing

### Test Cases
1. **Test Main Component Part Number**
   - Pilih sales order yang memiliki Main component dengan part number
   - Verify: Kolom Part# untuk row "Main" menampilkan part number yang benar

2. **Test Fit Components Part Number**
   - Pilih sales order yang memiliki multiple components (Main + Fit1, Fit2, dll)
   - Verify: Kolom Part# untuk setiap Fit row menampilkan part number yang sesuai

3. **Test Empty Part Number**
   - Pilih sales order yang tidak memiliki part number
   - Verify: Kolom Part# tetap kosong (tidak error)

### Manual Testing Steps
1. Login ke aplikasi
2. Navigate to: **Warehouse Management → Delivery Order → DO Processing → Prepare Delivery Order (Multiple Item)**
3. Pilih customer code
4. Klik tombol "Next"
5. Pada Sales Order Screen modal:
   - Klik icon table untuk membuka Sales Order Table Modal
   - Pilih salah satu sales order
   - Klik "Proceed to Details"
6. **Verify**: Pada tabel Item Details, kolom Part# untuk Main dan Fit components sekarang menampilkan data part number yang sesuai

## Impact
- ✅ Part number sekarang ditampilkan dengan benar untuk semua komponen (Main + Fit1-9)
- ✅ Data konsisten antara SO, DO, dan MC tables
- ✅ User dapat melihat part number saat prepare delivery order
- ✅ Backward compatible - tidak mempengaruhi fungsionalitas yang sudah ada

## Related Files
1. `app/Http/Controllers/SalesOrderController.php` - Backend API
2. `resources/js/Components/SalesOrderScreenModal.vue` - Frontend Modal
3. `resources/js/Pages/warehouse-management/DeliveryOrder/DOProcessing/PrepareDeliveryOrderMultipleItem.vue` - Parent page
4. `database/migrations/2025_10_07_080137_create_do_table.php` - DO table structure (kolom `Part_Number`)

## Database Schema Reference

### MC Table (Source)
```sql
PART_NO VARCHAR(50) -- Source data untuk part number
COMP VARCHAR(50)    -- Component name (Main, Fit1, Fit2, ...)
P_DESIGN VARCHAR(50)
PCS_SET DECIMAL
UNIT VARCHAR(50)
```

### SO Table (Intermediate)
```sql
PART_NUMBER VARCHAR(50) -- Copied from MC.PART_NO
COMP_Num VARCHAR(50)     -- Component name
P_DESIGN VARCHAR(50)
```

### DO Table (Final)
```sql
Part_Number VARCHAR(50) -- Final destination for part number display
COMP VARCHAR(50)
PD VARCHAR(50)
```

## Date
- **Created**: 2025-01-XX
- **Modified**: 2025-01-XX
- **Status**: ✅ Completed
