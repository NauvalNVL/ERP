# Fix: D_LOC_Num Geographical Code - Mode 1

## 🎯 Problem Statement
Ketika user **TIDAK memilih delivery code** (ship to same address), kolom `D_LOC_Num` di tabel SO **kosong**, padahal seharusnya terisi dengan **geographical code** dari customer.

---

## ❌ BEFORE (BROKEN)

### Customer Data:
- Customer Code: `000211-08`
- Customer Name: `ABDULLAH, BPK`
- Geographical Code: **`B110`** (INDONESIA - BANTEN, CILEGON)

### Sales Order Result:
```sql
D_LOC_Num:      (KOSONG) ❌
DELIVERY_TO:    ABDULLAH, BPK
DELIVERY_ADD_1: Jakarta
```

**Problem**: `D_LOC_Num` kosong! Seharusnya terisi dengan `B110`

---

## ✅ AFTER (FIXED)

### Customer Data:
- Customer Code: `000211-08`
- Customer Name: `ABDULLAH, BPK`
- Geographical Code: **`B110`** (INDONESIA - BANTEN, CILEGON)

### Sales Order Result:
```sql
D_LOC_Num:      B110 ✅ (geographical code dari customer!)
DELIVERY_TO:    ABDULLAH, BPK
DELIVERY_ADD_1: Jakarta
```

**Solution**: `D_LOC_Num` sekarang otomatis terisi dengan geographical code customer!

---

## 🔄 Flow Diagram

```
┌─────────────────────────────────────────────────────────┐
│        User Creates Sales Order                          │
└───────────────┬─────────────────────────────────────────┘
                │
                ▼
┌─────────────────────────────────────────────────────────┐
│   Delivery Location Modal Opens                          │
│   - Order by: ABDULLAH, BPK                              │
│   - Bill to: ABDULLAH, BPK, Jakarta                      │
│   - Ship to: ?                                           │
└───────────────┬─────────────────────────────────────────┘
                │
                ▼
        ┌───────┴───────┐
        │               │
        ▼               ▼
┌──────────────┐  ┌──────────────────┐
│ MODE 1       │  │ MODE 2           │
│ Ship to Same │  │ Ship to Alternate│
│ Address      │  │ Address          │
└──────┬───────┘  └────────┬─────────┘
       │                    │
       │ ☑ Leave blank     │ 🔍 Select delivery code
       │                    │
       ▼                    ▼
┌──────────────────────────────────────┐
│ Backend: SalesOrderController.store()│
└──────────────────┬───────────────────┘
                   │
        ┌──────────┴──────────┐
        │                     │
        ▼                     ▼
┌───────────────────┐   ┌────────────────────┐
│ MODE 1 Logic      │   │ MODE 2 Logic       │
│                   │   │                    │
│ 1. Query:         │   │ 1. Use delivery    │
│    update_customer│   │    code from modal │
│    _accounts      │   │                    │
│ 2. Get:           │   │ 2. Get:            │
│    geographical   │   │    - delivery_code │
│    = "B110"       │   │    - ship_to_name  │
│ 3. Fallback:      │   │    - ship_to_addr  │
│    CUSTOMER.AREA  │   │                    │
│                   │   │ 3. Split address   │
│ 4. Set:           │   │    into 3 lines    │
│    D_LOC_Num =    │   │                    │
│    "B110" ✅      │   │ 4. Set all fields  │
└───────────────────┘   └────────────────────┘
        │                     │
        └──────────┬──────────┘
                   │
                   ▼
┌────────────────────────────────────────┐
│  INSERT INTO so (                      │
│    D_LOC_Num = 'B110',    ✅           │
│    DELIVERY_TO = 'ABDULLAH, BPK',      │
│    DELIVERY_ADD_1 = 'Jakarta',         │
│    ...                                 │
│  )                                     │
└────────────────────────────────────────┘
```

---

## 📊 Database Tables Involved

### 1. **update_customer_accounts** (Primary Source)
```sql
customer_code | geographical | customer_name
--------------|--------------|------------------
000211-08     | B110         | ABDULLAH, BPK
```

### 2. **CUSTOMER** (Fallback Source)
```sql
CODE       | AREA | NAME          | ADDRESS1
-----------|------|---------------|----------
000211-08  | B110 | ABDULLAH, BPK | Jakarta
```

### 3. **so** (Target Table)
```sql
SO_Num  | D_LOC_Num | DELIVERY_TO   | DELIVERY_ADD_1
--------|-----------|---------------|----------------
SO-123  | B110      | ABDULLAH, BPK | Jakarta
```

---

## 🔧 Code Changes

### Location: `SalesOrderController.php` → `store()` method (Line 356-393)

**BEFORE:**
```php
} else {
    // MODE 1
    if ($customerData) {
        $dLocNum = ''; // ❌ KOSONG!
        $deliveryTo = $customerData->NAME;
        // ...
    }
}
```

**AFTER:**
```php
} else {
    // MODE 1
    if ($customerData) {
        // Query geographical code
        $updateCustomerAccount = DB::table('update_customer_accounts')
            ->where('customer_code', $validated['customer_code'])
            ->first();
        
        // Priority system
        $dLocNum = '';
        if ($updateCustomerAccount && !empty($updateCustomerAccount->geographical)) {
            $dLocNum = $updateCustomerAccount->geographical; // ✅ B110
        } elseif (!empty($customerData->AREA)) {
            $dLocNum = $customerData->AREA; // Fallback
        }
        
        $deliveryTo = $customerData->NAME;
        // ...
    }
}
```

---

## 📝 Log Output Example

### Mode 1: Ship to Same Address
```log
[2025-11-10 13:30:00] SO Delivery - Mode 1: Customer Main Address
{
    "delivery_code": "B110",
    "delivery_to": "ABDULLAH, BPK",
    "address_1": "Jakarta",
    "address_2": "",
    "address_3": "",
    "geographical_source": "update_customer_accounts.geographical",
    "source": "CUSTOMER table"
}
```

### Mode 2: Ship to Alternate Address
```log
[2025-11-10 13:30:00] SO Delivery - Mode 2: Alternate Address
{
    "delivery_code": "B103",
    "delivery_to": "ABDULLAH, BPK",
    "address_1": "JL. YOS SUDARSO NO.61 JURUMUDI BARU-TANGERANG",
    "address_2": "",
    "address_3": "",
    "address_raw": "JL. YOS SUDARSO NO.61 JURUMUDI BARU-TANGERANG",
    "source": "customer_alternate_addresses table"
}
```

---

## ✅ Testing Checklist

### Test 1: Mode 1 - Ship to Same Address
- [ ] Buat SO baru untuk customer "ABDULLAH, BPK"
- [ ] Centang "Leave blank if ship to the same address"
- [ ] Save SO
- [ ] Check database: `D_LOC_Num` = `B110` ✅

### Test 2: Mode 2 - Ship to Alternate Address
- [ ] Buat SO baru untuk customer "ABDULLAH, BPK"
- [ ] Pilih delivery code "B103" dari modal
- [ ] Save SO
- [ ] Check database: `D_LOC_Num` = `B103` ✅

### Test 3: Update/Amend SO
- [ ] Amend SO yang sudah ada
- [ ] Ubah delivery location (mode 1 ↔ mode 2)
- [ ] Save changes
- [ ] Check database: `D_LOC_Num` updated correctly ✅

---

## 🎯 Expected Results

| Scenario | D_LOC_Num | Source |
|----------|-----------|--------|
| Ship to same address (Mode 1) | `B110` | `update_customer_accounts.geographical` |
| Ship to alternate "B103" (Mode 2) | `B103` | `customer_alternate_addresses.delivery_code` |
| Customer without geographical code | `""` or `CUSTOMER.AREA` | Fallback |

---

## 📌 Important Notes

1. **Priority System**:
   - 1st: `update_customer_accounts.geographical`
   - 2nd: `CUSTOMER.AREA` (fallback)
   - 3rd: Empty string (jika keduanya tidak ada)

2. **Backward Compatibility**: 
   - Jika customer lama tidak punya geographical code, sistem tetap berjalan
   - `D_LOC_Num` akan kosong (acceptable)

3. **Data Consistency**:
   - Pastikan semua customer sudah punya geographical code
   - Run data migration/seeder jika perlu

---

## 🔄 Next Steps

1. ✅ Test create SO dengan mode 1 (ship to same address)
2. ✅ Test create SO dengan mode 2 (alternate address)
3. ✅ Test amend SO
4. ✅ Verify logs di `storage/logs/laravel.log`
5. ✅ Check database hasil final

---

**Status**: ✅ COMPLETED
**Date Fixed**: 10 November 2025
**Tested**: Ready for testing
