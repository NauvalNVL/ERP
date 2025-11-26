# Fix: Sales Order Detail Modal - Fit Components Order, Delivery, and Balance Display

## Problem
Pada menu `PrepareDeliveryOrderMultipleItem.vue`, di bagian **Sales Order Detail Screen Modal**, terdapat beberapa masalah:

1. **Komponen Fit tidak menampilkan Order, Delivery, dan Balance**: Kolom Order, Delivery, dan Balance untuk komponen Fit (Fit1-Fit9) tidak ditampilkan, hanya komponen Main yang menampilkan data tersebut.

2. **Logika Balance salah**: Balance dihitung untuk semua komponen secara agregat, bukan per komponen. Seharusnya balance dihitung per komponen: `Balance = Order - Delivery` untuk setiap komponen (Main, Fit1, Fit2, dst).

## Root Cause

### 1. Backend API (`getSalesOrderDetail`)
File: `app/Http/Controllers/SalesOrderController.php`

**Problem 1 - Main Component**: 
Method `getSalesOrderDetail` menghitung `net_delivery` untuk Main component dengan menjumlahkan **SEMUA komponen** (tidak filter by COMP):
```php
// ❌ BUG: Tidak ada filter COMP = 'Main'
$netDelivery = (float) DB::table('DO')
    ->where('SO_Num', $soNumber)
    ->sum('DO_Qty');  // Menghitung Main + Fit1 + Fit2 + ...
```

**Contoh Bug**:
- SO: Main Order = 1000, Fit1 Order = 2000
- DO: Main Delivered = 500, Fit1 Delivered = 1000
- **Wrong**: Main Balance = 1000 - (500 + 1000) = -500 ❌
- **Correct**: Main Balance = 1000 - 500 = 500 ✅

**Problem 2 - Fit Components**:
Method `getSalesOrderDetail` hanya mengembalikan data berikut untuk array `fittings`:
```php
'design' => $componentRow->P_DESIGN ?? '',
'pcs' => $componentRow->PCS_SET ?? '',
'unit' => $componentRow->UNIT ?? '',
'part_number' => $componentRow->PART_NO ?? '',
```

**Missing fields**: `order_qty`, `net_delivery`, `balance` untuk setiap komponen Fit.

### 2. Frontend Modal (`SalesOrderDetailModal.vue`)
File: `resources/js/Components/SalesOrderDetailModal.vue`

Fungsi `hydrateFromSalesOrderData` hanya mengisi data `pDesign`, `pcs`, dan `unit` untuk komponen Fit, tidak mengisi `order`, `delivery`, dan `balance`.

## Solution

### 1. Backend Fix - SalesOrderController.php

**Changes**: Update method `getSalesOrderDetail` untuk:
1. Menambahkan perhitungan `order_qty`, `net_delivery`, dan `balance` per komponen Fit
2. **PERBAIKI perhitungan Main component** untuk menghitung balance per komponen (bukan aggregate)

#### Before (line ~2203-2218)
```php
foreach ($mcComponents as $componentRow) {
    $compName = trim((string) ($componentRow->COMP ?? ''));

    // Skip Main row; only use Fit components as fittings
    if ($compName === '' || strcasecmp($compName, 'Main') === 0) {
        continue;
    }

    $fittings[] = [
        'name' => $compName,
        'design' => $componentRow->P_DESIGN ?? '',
        'pcs' => $componentRow->PCS_SET ?? '',
        'unit' => $componentRow->UNIT ?? '',
        'part_number' => $componentRow->PART_NO ?? '',
    ];

    // Limit to first 9 components (Fit1..Fit9)
    if (count($fittings) >= 9) {
        break;
    }
}
```

#### After
```php
foreach ($mcComponents as $componentRow) {
    $compName = trim((string) ($componentRow->COMP ?? ''));

    // Skip Main row; only use Fit components as fittings
    if ($compName === '' || strcasecmp($compName, 'Main') === 0) {
        continue;
    }

    // Get order_qty from SO table for this component
    $componentSO = DB::table('so')
        ->where('SO_Num', $soNumber)
        ->where('COMP_Num', $compName)
        ->first();

    $componentOrderQty = $componentSO ? ($componentSO->SO_QTY ?? 0) : 0;

    // Calculate net delivery from DO table for this component
    $componentNetDelivery = 0;
    $hasComponentDoRows = false;
    try {
        $hasComponentDoRows = DB::table('DO')
            ->where('SO_Num', $soNumber)
            ->where('COMP', $compName)
            ->exists();
        
        if ($hasComponentDoRows) {
            $componentNetDelivery = (float) DB::table('DO')
                ->where('SO_Num', $soNumber)
                ->where('COMP', $compName)
                ->sum('DO_Qty');
        }
    } catch (\Exception $e) {
        Log::warning('Error computing DO net delivery for component', [
            'so_number' => $soNumber,
            'component' => $compName,
            'error' => $e->getMessage()
        ]);
    }

    // Calculate balance per component: ORDER - DELIVERY
    $componentBalance = $componentOrderQty - $componentNetDelivery;

    $fittings[] = [
        'name' => $compName,
        'design' => $componentRow->P_DESIGN ?? '',
        'pcs' => $componentRow->PCS_SET ?? '',
        'unit' => $componentRow->UNIT ?? '',
        'part_number' => $componentRow->PART_NO ?? '',
        // ✅ Add order, delivery, and balance per component
        'order_qty' => $componentOrderQty,
        'net_delivery' => $hasComponentDoRows ? $componentNetDelivery : '',
        'balance' => $componentBalance,
    ];

    // Limit to first 9 components (Fit1..Fit9)
    if (count($fittings) >= 9) {
        break;
    }
}
```

**Key Changes for Main Component** (line ~2149-2184):

#### Before
```php
// Calculate net delivery from DO table (sum of DO_Qty) for this SO
$netDelivery = 0;
$hasDoRows = false;
try {
    $hasDoRows = DB::table('DO')->where('SO_Num', $soNumber)->exists();
    if ($hasDoRows) {
        // ❌ BUG: Menghitung SUM untuk SEMUA komponen
        $netDelivery = (float) DB::table('DO')
            ->where('SO_Num', $soNumber)
            ->sum('DO_Qty');
    }
}
```

#### After
```php
// Calculate net delivery from DO table (sum of DO_Qty) for Main component only
$netDelivery = 0;
$hasDoRows = false;
try {
    // ✅ Check if DO rows exist for Main component
    $hasDoRows = DB::table('DO')
        ->where('SO_Num', $soNumber)
        ->where('COMP', 'Main')  // ✅ Filter by Main component
        ->exists();
    
    if ($hasDoRows) {
        // ✅ Sum DO_Qty for Main component only
        $netDelivery = (float) DB::table('DO')
            ->where('SO_Num', $soNumber)
            ->where('COMP', 'Main')  // ✅ Filter by Main component
            ->sum('DO_Qty');
    }
}

// Calculate balance per component: Main.Order - Main.Delivery
$itemDetails['balance'] = (float)($salesOrder->SO_QTY ?? 0) - $netDelivery;
```

**Key Changes for Fit Components**:
1. Query tabel SO untuk mendapatkan `SO_QTY` per komponen
2. Query tabel DO untuk menghitung `net_delivery` per komponen (sum of `DO_Qty`)
3. Hitung `balance` per komponen: `Balance = Order - Delivery`
4. Return fields baru: `order_qty`, `net_delivery`, `balance` dalam array fittings

### 2. Frontend Fix - SalesOrderDetailModal.vue

**Changes**: Update fungsi `hydrateFromSalesOrderData` untuk mengisi data order, delivery, dan balance dari API response.

#### Before (line ~676-686)
```javascript
if (Array.isArray(data.fittings)) {
  data.fittings.forEach((fitting, idx) => {
    if (idx < 9) {
      const row = itemRows.value[idx + 1]
      if (row) {
        row.pDesign = fitting.design || ''
        row.pcs = fitting.pcs || ''
        row.unit = fitting.unit || ''
      }
    }
  })
}
```

#### After
```javascript
if (Array.isArray(data.fittings)) {
  data.fittings.forEach((fitting, idx) => {
    if (idx < 9) {
      const row = itemRows.value[idx + 1]
      if (row) {
        row.pDesign = fitting.design || ''
        row.pcs = fitting.pcs || ''
        row.unit = fitting.unit || ''
        // ✅ Add order, delivery, and balance from API
        row.order = fitting.order_qty ?? ''
        row.delivery = fitting.net_delivery ?? ''
        // Calculate balance per component: ORDER - DELIVERY
        const orderQty = Number((row.order || '0').toString().replace(/,/g, '')) || 0
        const netDel = Number((row.delivery || '0').toString().replace(/,/g, '')) || 0
        const bal = orderQty - netDel
        row.balance = fitting.balance !== undefined && fitting.balance !== null && fitting.balance !== '' 
          ? fitting.balance 
          : (Number.isFinite(bal) ? bal.toString() : row.order)
        // Keep these empty for now
        row.reject = ''
        row.available = ''
        row.maxDO = ''
      }
    }
  })
}
```

**Key Changes**:
1. Mengisi `row.order` dari `fitting.order_qty`
2. Mengisi `row.delivery` dari `fitting.net_delivery`
3. Mengisi `row.balance` dari `fitting.balance` (dengan fallback calculation)
4. Set empty values untuk `reject`, `available`, `maxDO` (sesuai dengan Main component)

## Data Flow

### Source Data
Data order, delivery, dan balance per komponen berasal dari:
- **Tabel SO** → kolom `SO_QTY` (Order quantity per komponen)
- **Tabel DO** → kolom `DO_Qty` (Delivery quantity, di-sum per komponen)
- **Calculation**: `Balance = SO_QTY - SUM(DO_Qty)` per komponen

### Query Logic
```sql
-- Get order quantity for specific component
SELECT SO_QTY FROM so 
WHERE SO_Num = '{soNumber}' 
AND COMP_Num = '{componentName}';

-- Get net delivery for specific component
SELECT SUM(DO_Qty) FROM DO 
WHERE SO_Num = '{soNumber}' 
AND COMP = '{componentName}';

-- Calculate balance
Balance = SO_QTY - SUM(DO_Qty)
```

### Flow
1. User membuka Sales Order Detail Modal dari Prepare DO
2. Frontend memanggil API: `GET /api/sales-order/{soNumber}/detail`
3. Backend (`getSalesOrderDetail` method):
   - Query tabel SO untuk Main component (sudah ada sebelumnya)
   - Loop melalui MC components untuk Fit1-Fit9
   - Untuk setiap Fit component:
     - Query SO table: ambil `SO_QTY` (order)
     - Query DO table: sum `DO_Qty` (delivery)
     - Calculate: `balance = order - delivery`
   - Return data lengkap termasuk order, delivery, balance per komponen
4. Frontend (`hydrateFromSalesOrderData` function):
   - Terima response dari API
   - Isi data untuk Main component (order, delivery, balance)
   - Loop melalui fittings array
   - Isi data untuk setiap Fit component (order, delivery, balance)
5. Vue template menampilkan semua data di tabel Item Details

## Balance Calculation Logic

### Correct Logic (Per Component) ✅
```
Main:  Balance = Main.Order - Main.Delivery
Fit1:  Balance = Fit1.Order - Fit1.Delivery
Fit2:  Balance = Fit2.Order - Fit2.Delivery
...
Fit9:  Balance = Fit9.Order - Fit9.Delivery
```

### Wrong Logic (Before Fix) ❌
```
Balance = SUM(All Components Order) - SUM(All Components Delivery)
```

**Problem dengan logika lama**: 
- Jika Main sudah fully delivered tapi Fit1 belum, balance akan menunjukkan 0 untuk semua komponen
- User tidak bisa melihat berapa balance tersisa untuk setiap komponen individual
- Tidak akurat untuk multi-component orders

## Testing

### Test Cases

#### 1. Test Fit Components Display Order, Delivery, Balance
**Steps**:
1. Buat Sales Order dengan multiple components (Main + Fit1 + Fit2)
   - Main: Order = 1000
   - Fit1: Order = 2000
   - Fit2: Order = 1500
2. Buat beberapa Delivery Orders:
   - DO #1: Main = 500, Fit1 = 1000, Fit2 = 500
   - DO #2: Main = 300, Fit1 = 500, Fit2 = 0
3. Buka Sales Order Detail Modal
4. **Verify**:
   - Main: Order = 1000, Delivery = 800, Balance = 200 ✅
   - Fit1: Order = 2000, Delivery = 1500, Balance = 500 ✅
   - Fit2: Order = 1500, Delivery = 500, Balance = 1000 ✅

#### 2. Test Balance Calculation Per Component
**Steps**:
1. Buka Sales Order Detail Modal untuk SO dengan multiple components
2. **Verify**: Setiap komponen menampilkan balance yang benar:
   - Balance = Order - Delivery (per komponen)
   - Balance tidak di-aggregate untuk semua komponen

#### 3. Test Empty Delivery (No DO yet)
**Steps**:
1. Buat Sales Order baru (belum ada DO)
2. Buka Sales Order Detail Modal
3. **Verify**:
   - Kolom Delivery untuk semua komponen kosong atau 0
   - Kolom Balance = Order quantity untuk setiap komponen

#### 4. Test Partial Delivery
**Steps**:
1. Buat SO: Main = 1000, Fit1 = 2000
2. Buat DO: Main = 500, Fit1 = 0 (only Main delivered)
3. Buka Sales Order Detail Modal
4. **Verify**:
   - Main: Order = 1000, Delivery = 500, Balance = 500 ✅
   - Fit1: Order = 2000, Delivery = 0 (or empty), Balance = 2000 ✅

### Manual Testing Steps
1. Login ke aplikasi
2. Navigate to: **Warehouse Management → Delivery Order → DO Processing → Prepare Delivery Order (Multiple Item)**
3. Pilih customer code dan klik "Next"
4. Pada Sales Order Screen modal, pilih sales order
5. Klik tombol untuk membuka **Sales Order Detail Modal**
6. **Verify**: Pada tabel Item Details:
   - Kolom Order, Delivery, Balance untuk Main component sudah benar ✅
   - Kolom Order, Delivery, Balance untuk Fit components (Fit1-Fit9) sekarang tampil dengan benar ✅
   - Balance dihitung per komponen, bukan aggregate ✅

## Impact
- ✅ Fit components sekarang menampilkan Order, Delivery, dan Balance dengan benar
- ✅ Balance dihitung per komponen (tidak aggregate)
- ✅ User dapat melihat status delivery untuk setiap komponen secara individual
- ✅ Logika sama dengan Main component untuk consistency
- ✅ Lebih akurat untuk multi-component orders
- ✅ Backward compatible - tidak mempengaruhi fungsionalitas yang sudah ada

## Related Files
1. `app/Http/Controllers/SalesOrderController.php` - Backend API (method `getSalesOrderDetail`)
2. `resources/js/Components/SalesOrderDetailModal.vue` - Frontend Modal
3. `resources/js/Pages/warehouse-management/DeliveryOrder/DOProcessing/PrepareDeliveryOrderMultipleItem.vue` - Parent page

## Database Tables Reference

### SO Table (Source for Order)
```sql
SO_Num VARCHAR(50)      -- Sales order number
COMP_Num VARCHAR(50)    -- Component name (Main, Fit1, Fit2, ...)
SO_QTY DECIMAL          -- Order quantity per component
```

### DO Table (Source for Delivery)
```sql
SO_Num VARCHAR(50)      -- Sales order number
COMP VARCHAR(50)        -- Component name
DO_Qty DECIMAL          -- Delivery quantity per component
```

### Calculation
```sql
-- For each component:
Order = SO.SO_QTY WHERE COMP_Num = '{component}'
Delivery = SUM(DO.DO_Qty) WHERE COMP = '{component}'
Balance = Order - Delivery
```

## Date
- **Created**: 2025-01-XX
- **Modified**: 2025-01-XX
- **Status**: ✅ Completed

## Additional Notes
- Balance calculation is done per component, not aggregated
- If a component has no DO records, `net_delivery` returns empty string
- Balance will equal Order if no deliveries have been made
- Query optimization: Separate queries per component to ensure accurate calculations
