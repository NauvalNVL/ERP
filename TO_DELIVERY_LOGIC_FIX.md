# To Delivery Logic Fix - Sales Order Detail Modal

## Overview
Perbaikan logika "To Delivery" pada modal Sales Order Detail Screen di menu Prepare Delivery Order (Multiple Item). Sekarang sistem mendukung dua cara untuk mengisi quantity delivery:

1. **Input via "To Delivery Set"** - Otomatis menghitung untuk semua komponen
2. **Input Manual per Komponen** - Kustomisasi quantity per komponen

## Changes Made

### File: `resources/js/Components/SalesOrderDetailModal.vue`

#### 1. Logika Auto-Calculate dari "To Delivery Set"
Ketika user mengisi field "To Delivery Set", sistem akan otomatis menghitung "To Deliver" untuk setiap komponen berdasarkan rumus:

```
To Deliver (per komponen) = To Delivery Set × PCS (komponen)
```

**Contoh:**
- To Delivery Set = 1000
- Main: PCS = 1 → To Deliver = 1000
- Fit1: PCS = 2 → To Deliver = 2000
- Fit2: PCS = 1 → To Deliver = 1000

**Implementasi:**
```javascript
watch(() => orderDetail.toDeliverySet, (newValue, oldValue) => {
  if (isUpdatingFromDeliverySet.value) return
  
  const deliverySetValue = parseFloat(newValue) || 0
  isUpdatingFromDeliverySet.value = true
  
  itemRows.value.forEach(item => {
    // Hanya update komponen yang memiliki pDesign (komponen aktif)
    if (item.pDesign && item.pDesign.trim() !== '') {
      const pcsValue = parseFloat(item.pcs) || 1
      const calculatedToDeliver = deliverySetValue * pcsValue
      item.toDeliver = calculatedToDeliver > 0 ? calculatedToDeliver.toString() : ''
    } else {
      item.toDeliver = ''
    }
  })
  
  setTimeout(() => {
    isUpdatingFromDeliverySet.value = false
  }, 0)
})
```

#### 2. Logika Input Manual per Komponen
User dapat mengubah nilai "To Deliver" secara manual di kolom tabel. Ketika user mengubah nilai manual, sistem akan:
- Menghitung ulang "To Delivery Set" berdasarkan Main component
- Rumus: `To Delivery Set = Main To Deliver / Main PCS`

**Implementasi:**
```javascript
watchEffect(() => {
  if (!isUpdatingFromDeliverySet.value) {
    const mainItem = itemRows.value.find(item => item.name === 'Main')
    if (mainItem) {
      const mainToDeliver = parseFloat(mainItem.toDeliver) || 0
      const mainPcs = parseFloat(mainItem.pcs) || 1
      
      const calculatedDeliverySet = mainPcs > 0 ? mainToDeliver / mainPcs : 0
      
      const currentDeliverySet = parseFloat(orderDetail.toDeliverySet) || 0
      if (Math.abs(calculatedDeliverySet - currentDeliverySet) > 0.01) {
        orderDetail.toDeliverySet = calculatedDeliverySet > 0 ? calculatedDeliverySet.toString() : '0'
      }
    }
  }
})
```

#### 3. UI Update
- Kolom "To Deliver" tetap editable dan memiliki background kuning untuk menunjukkan bahwa field ini penting
- Handler input diubah dari `handleToDeliverChange` menjadi `handleToDeliverManualChange(item)`

### File: `resources/js/Pages/warehouse-management/DeliveryOrder/DOProcessing/PrepareDeliveryOrderMultipleItem.vue`

#### 4. Penyimpanan Data ke Tabel DO
Data "To Deliver" dari setiap komponen disimpan dengan benar ke tabel DO:

```javascript
const items = Array.isArray(deliveryOrder.itemDetails)
  ? deliveryOrder.itemDetails.map(row => {
      // ... logika mapping komponen ...
      
      let rawToDeliver
      
      if (!hasComponent) {
        rawToDeliver = '0'
      } else if (row && row.toDeliver !== undefined && row.toDeliver !== null && row.toDeliver !== '') {
        // Prioritas 1: nilai To Deliver per baris di detail SO (sudah dikalikan dengan pcs)
        rawToDeliver = row.toDeliver
      } else if (pack && pack.toDel !== undefined && pack.toDel !== null && pack.toDel !== '') {
        // Prioritas 2: nilai To Del dari Packing per komponen
        rawToDeliver = pack.toDel
      } else {
        // Fallback: gunakan baseToDeliver * pcs komponen
        const pcsValue = parseFloat(row.pcs) || 1
        rawToDeliver = baseToDeliver * pcsValue
      }
      
      return {
        name: row.name || '',
        p_design: row.pDesign || '',
        pcs: row.pcs || '',
        unit: row.unit || '',
        to_deliver: normalizeNumber(rawToDeliver),
        // ... packing helpers ...
      }
    })
  : []
```

## How It Works

### Scenario 1: Using "To Delivery Set"
1. User mengisi "To Delivery Set" = 1000
2. Sistem otomatis menghitung untuk setiap komponen:
   - Main (PCS=1): To Deliver = 1000
   - Fit1 (PCS=2): To Deliver = 2000
   - Fit2 (PCS=1): To Deliver = 1000
3. User klik Save → Data tersimpan ke tabel DO

### Scenario 2: Using Manual Input per Component
1. User mengisi kolom "To Deliver" di tabel:
   - Main: 1500
   - Fit1: 3000
   - Fit2: 1500
2. Sistem otomatis menghitung "To Delivery Set" = 1500 (dari Main)
3. User klik Save → Data tersimpan ke tabel DO

### Scenario 3: Mixed (Auto + Manual Adjustment)
1. User mengisi "To Delivery Set" = 1000
2. Sistem auto-calculate semua komponen
3. User ingin custom Fit1, ubah manual menjadi 2500 (instead of 2000)
4. Sistem akan update "To Delivery Set" berdasarkan Main component
5. User klik Save → Data tersimpan sesuai input manual

## Data Flow

```
┌─────────────────────────────┐
│ Sales Order Detail Modal    │
├─────────────────────────────┤
│ 1. User Input:              │
│    - To Delivery Set        │
│    - OR Manual per Komponen │
│                             │
│ 2. Auto-Calculate:          │
│    To Deliver = Set × PCS   │
│                             │
│ 3. Store in itemRows[]      │
│    with toDeliver field     │
└─────────────┬───────────────┘
              │
              ↓
┌─────────────────────────────┐
│ Packing Details Modal       │
├─────────────────────────────┤
│ (Optional packing info)     │
└─────────────┬───────────────┘
              │
              ↓
┌─────────────────────────────┐
│ PrepareDeliveryOrder.vue    │
├─────────────────────────────┤
│ handleSalesOrderDelivery... │
│                             │
│ deliveryOrder.itemDetails   │
│   = detailData.itemRows     │
└─────────────┬───────────────┘
              │
              ↓
┌─────────────────────────────┐
│ saveDeliveryOrder()         │
├─────────────────────────────┤
│ Map itemDetails to items[]  │
│                             │
│ Each item has:              │
│   - name (Main/Fit1-9)      │
│   - p_design                │
│   - pcs                     │
│   - unit                    │
│   - to_deliver (dari input) │
└─────────────┬───────────────┘
              │
              ↓
┌─────────────────────────────┐
│ POST /api/delivery-orders   │
├─────────────────────────────┤
│ Save to DO table per        │
│ component (Main + Fits)     │
└─────────────────────────────┘
```

## Testing Guide

### Test Case 1: Auto-Calculate via "To Delivery Set"
1. Buka menu: Warehouse Management → Delivery Order → Prepare DO (Multiple Item)
2. Pilih Customer
3. Klik Next → Pilih SO
4. Di Sales Order Detail Screen:
   - Isi "To Delivery Set" = 1000
   - Verifikasi kolom "To Deliver" di tabel auto-calculate berdasarkan PCS
5. Klik Save → Verifikasi data tersimpan di tabel DO

### Test Case 2: Manual Input per Component
1. Buka Sales Order Detail Screen
2. Kosongkan "To Delivery Set"
3. Isi manual di kolom "To Deliver":
   - Main: 1500
   - Fit1: 3000
4. Verifikasi "To Delivery Set" auto-calculate = 1500
5. Klik Save → Verifikasi data tersimpan di tabel DO

### Test Case 3: Mixed Input
1. Isi "To Delivery Set" = 1000
2. Edit manual Fit1 dari 2000 menjadi 2500
3. Verifikasi "To Delivery Set" tetap 1000 (dari Main)
4. Klik Save → Verifikasi data tersimpan sesuai input

### Test Case 4: Component without PCS
1. Pilih SO dengan komponen yang tidak memiliki PCS
2. Isi "To Delivery Set" = 1000
3. Verifikasi komponen dengan PCS kosong atau 0 tidak mendapat nilai To Deliver
4. Klik Save → Verifikasi hanya komponen valid yang tersimpan

## Benefits

1. **Flexible Input**: User dapat memilih cara input yang paling mudah
2. **Auto-Calculate**: Menghemat waktu untuk perhitungan manual
3. **Custom per Component**: Mendukung kasus khusus dimana qty per komponen berbeda dari formula standar
4. **Data Integrity**: Nilai To Deliver per komponen tersimpan dengan benar di database
5. **User Friendly**: Sinkronisasi otomatis antara "To Delivery Set" dan kolom "To Deliver"

## Notes

- Komponen yang tidak memiliki `pDesign` (komponen non-aktif) tidak akan mendapat nilai To Deliver
- Sistem menggunakan floating point tolerance (0.01) untuk menghindari masalah presisi
- Flag `isUpdatingFromDeliverySet` digunakan untuk mencegah infinite loop antara watch dan watchEffect
- Formula fallback: jika tidak ada input sama sekali, sistem akan menggunakan `baseToDeliver * pcs` per komponen

## Related Files

- `resources/js/Components/SalesOrderDetailModal.vue`
- `resources/js/Pages/warehouse-management/DeliveryOrder/DOProcessing/PrepareDeliveryOrderMultipleItem.vue`
- `resources/js/Components/PackingDetailsModal.vue`

## Status

✅ Implementation Complete
✅ Ready for Testing
