# Sales Order Table Modal - Item Details Fix (Fit Components)

## Overview
Perbaikan logika tabel Item Details di modal Sales Order Table agar data ORDER, NET DELIVERY, dan BALANCE juga ter-implement di komponen Fit (F1-F9), tidak hanya di komponen Main saja.

## Problem
Sebelumnya, pada modal **Sales Order Table [Sorted by S/Order#]**, tabel Item Details hanya menampilkan data lengkap untuk komponen Main. Untuk komponen Fit (F1-F9), hanya row PD, PCS, dan UNIT yang diisi, sedangkan row ORDER, NET DELIVERY, dan BALANCE kosong.

## Solution

### File Modified: `resources/js/Components/SalesOrderTableModal.vue`

#### Perubahan pada fungsi `updateOrderInfo()`

**Logika Baru:**
```javascript
// Update fittings (F1-F9) if available
if (data.fittings && data.fittings.length > 0) {
  data.fittings.forEach((fitting, index) => {
    if (index < 9) { // F1-F9
      const colKey = `f${index + 1}`
      
      // PD row - fitting design
      itemDetails.value[0][colKey] = fitting.design || ''
      // PCS row - fitting pcs
      itemDetails.value[1][colKey] = fitting.pcs || ''
      // UNIT row - fitting unit
      itemDetails.value[2][colKey] = fitting.unit || ''
      
      // ORDER row - fitting order quantity
      // Jika API menyediakan order_qty untuk fitting, gunakan itu
      // Jika tidak, hitung dari main order qty * pcs fitting
      if (fitting.order_qty !== undefined && fitting.order_qty !== null) {
        itemDetails.value[3][colKey] = fitting.order_qty
      } else {
        const mainOrderQty = parseFloat(data.item_details.order_qty) || 0
        const fittingPcs = parseFloat(fitting.pcs) || 1
        itemDetails.value[3][colKey] = mainOrderQty * fittingPcs
      }
      
      // NET DELIVERY row - fitting net delivery
      // Jika API menyediakan net_delivery untuk fitting, gunakan itu
      if (fitting.net_delivery !== undefined && fitting.net_delivery !== null) {
        itemDetails.value[4][colKey] = fitting.net_delivery
      } else {
        itemDetails.value[4][colKey] = 0
      }
      
      // BALANCE row - fitting balance (ORDER - NET DELIVERY)
      if (fitting.balance !== undefined && fitting.balance !== null) {
        itemDetails.value[5][colKey] = fitting.balance
      } else {
        const orderQty = parseFloat(itemDetails.value[3][colKey]) || 0
        const netDelivery = parseFloat(itemDetails.value[4][colKey]) || 0
        itemDetails.value[5][colKey] = orderQty - netDelivery
      }
    }
  })
}
```

## How It Works

### 1. **ORDER Quantity (Row 3)**
Untuk setiap komponen Fit:
- **Prioritas 1**: Gunakan `fitting.order_qty` jika tersedia dari API
- **Prioritas 2**: Hitung = Main Order Qty × Fitting PCS
- **Contoh**: Jika Main Order = 1000, Fit1 PCS = 2 → Fit1 ORDER = 2000

### 2. **NET DELIVERY (Row 4)**
Untuk setiap komponen Fit:
- **Prioritas 1**: Gunakan `fitting.net_delivery` jika tersedia dari API
- **Fallback**: Default ke 0 jika tidak tersedia
- Nilai ini menunjukkan total yang sudah dikirim untuk komponen Fit tersebut

### 3. **BALANCE (Row 5)**
Untuk setiap komponen Fit:
- **Prioritas 1**: Gunakan `fitting.balance` jika tersedia dari API
- **Prioritas 2**: Hitung = ORDER - NET DELIVERY
- Nilai ini menunjukkan sisa yang belum dikirim untuk komponen Fit tersebut

## Data Structure

### Tabel Item Details (6 rows × 11 columns):

| ITEM         | MAIN | F1 | F2 | F3 | F4 | F5 | F6 | F7 | F8 | F9 |
|--------------|------|----|----|----|----|----|----|----|----|-----|
| PD           | ✅   | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ | ✅  |
| PCS          | ✅   | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ | ✅  |
| UNIT         | ✅   | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ | ✅  |
| ORDER        | ✅   | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ | ✅  |
| NET DELIVERY | ✅   | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ | ✅  |
| BALANCE      | ✅   | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ | ✅  |

✅ = Sekarang ter-implement dengan benar

## API Response Format

API endpoint `/api/sales-order/{soNumber}/detail` harus mengembalikan:

```javascript
{
  "success": true,
  "data": {
    "order_info": { ... },
    "item_details": {
      "pd": "...",
      "pcs": 1,
      "unit": "KG",
      "order_qty": 1000,
      "net_delivery": 500,
      "balance": 500
    },
    "fittings": [
      {
        "design": "PD-FIT1",
        "pcs": 2,
        "unit": "KG",
        "order_qty": 2000,        // Optional - akan dihitung jika tidak ada
        "net_delivery": 1000,     // Optional - default 0 jika tidak ada
        "balance": 1000           // Optional - akan dihitung jika tidak ada
      },
      {
        "design": "PD-FIT2",
        "pcs": 1,
        "unit": "KG",
        "order_qty": 1000,
        "net_delivery": 500,
        "balance": 500
      }
      // ... F3-F9
    ]
  }
}
```

## Calculation Logic

### Scenario 1: API menyediakan semua data
```javascript
// Data dari API
fitting = {
  design: "PD-FIT1",
  pcs: 2,
  unit: "KG",
  order_qty: 2000,
  net_delivery: 1000,
  balance: 1000
}

// Hasil di tabel
F1 ORDER = 2000 (dari API)
F1 NET DELIVERY = 1000 (dari API)
F1 BALANCE = 1000 (dari API)
```

### Scenario 2: API hanya menyediakan PD, PCS, UNIT
```javascript
// Data dari API
fitting = {
  design: "PD-FIT1",
  pcs: 2,
  unit: "KG"
}

// Main order qty = 1000

// Hasil di tabel (auto-calculate)
F1 ORDER = 1000 × 2 = 2000
F1 NET DELIVERY = 0 (default)
F1 BALANCE = 2000 - 0 = 2000
```

### Scenario 3: API menyediakan order_qty dan net_delivery saja
```javascript
// Data dari API
fitting = {
  design: "PD-FIT1",
  pcs: 2,
  unit: "KG",
  order_qty: 2000,
  net_delivery: 800
}

// Hasil di tabel
F1 ORDER = 2000 (dari API)
F1 NET DELIVERY = 800 (dari API)
F1 BALANCE = 2000 - 800 = 1200 (calculated)
```

## Benefits

1. **Konsistensi Data**: Semua komponen (Main + Fit1-9) memiliki data lengkap
2. **Akurasi Informasi**: User dapat melihat ORDER, NET DELIVERY, dan BALANCE per komponen
3. **Auto-Calculate**: Jika API tidak menyediakan data, sistem akan menghitung otomatis
4. **Flexible**: Mendukung baik data dari API maupun perhitungan otomatis
5. **Better UX**: User mendapat informasi lengkap tentang status order untuk semua komponen

## Testing Guide

### Test Case 1: SO dengan Multiple Components
1. Pilih SO yang memiliki Main + Fit components
2. Klik pada SO di tabel
3. Verifikasi tabel Item Details:
   - Row ORDER harus terisi untuk semua komponen aktif
   - Row NET DELIVERY harus terisi (dari API atau default 0)
   - Row BALANCE harus terisi (dari API atau calculated)

### Test Case 2: Calculation Verification
1. Pilih SO dengan Main ORDER = 1000
2. Lihat Fit1 dengan PCS = 2
3. Verifikasi: F1 ORDER = 2000 (jika tidak dari API)
4. Jika NET DELIVERY = 500, verifikasi BALANCE = 1500

### Test Case 3: Empty Fittings
1. Pilih SO yang hanya memiliki Main component
2. Verifikasi kolom F1-F9 tetap kosong
3. Tidak ada error di console

## Related Files

- `resources/js/Components/SalesOrderTableModal.vue` - Frontend modal
- `app/Http/Controllers/SalesOrderController.php` - API endpoint (getSalesOrderDetail)
- Database: `so` table

## Database Columns Reference

Kolom-kolom di tabel `so` untuk setiap komponen:
- `SO_QTY` - Order quantity per komponen
- `PER_SET` - Pieces per set (sama dengan PCS di UI)
- `UNIT` - Unit of measurement
- `P_DESIGN` - Product design code
- `COMP_Num` - Component name (Main, Fit1, Fit2, etc.)

## Notes

- Logika ini hanya menghitung display data di modal, tidak mengubah data di database
- Jika backend API sudah menyediakan `order_qty`, `net_delivery`, dan `balance` per fitting, data tersebut akan digunakan
- Jika tidak tersedia, frontend akan melakukan auto-calculate untuk ORDER dan BALANCE
- NET DELIVERY default ke 0 jika tidak tersedia (asumsi: belum ada delivery)

## Status

✅ Implementation Complete
✅ Ready for Testing

## Backward Compatibility

Perubahan ini backward compatible karena:
1. Mendukung response API lama (tanpa order_qty/net_delivery/balance di fittings)
2. Mendukung response API baru (dengan order_qty/net_delivery/balance di fittings)
3. Auto-calculate jika data tidak tersedia
4. Tidak mengubah struktur data yang dikirim ke parent component
