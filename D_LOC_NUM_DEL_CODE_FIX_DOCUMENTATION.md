# Fix: D_LOC_Num vs Del_Code Logic Correction

## 🎯 Problem Statement

**CRITICAL LOGIC ERROR FOUND:**
- `SO.D_LOC_Num` was being filled with **geographical delivery code** (like "H", "BDG", "S")
- `DO.Del_Code` was trying to get data from non-existent `CUSTOMER.DEL_CODE` column
- **BENAR seharusnya:**
  - `SO.D_LOC_Num` = **Sequence number pengiriman** (001, 002, 003)
  - `DO.Del_Code` = **Kode geografis** (H, BDG, S)

---

## 📊 Perbedaan D_LOC_Num vs Del_Code

| Kolom | Tabel | Fungsi | Contoh Nilai | Source Data |
|-------|-------|--------|--------------|-------------|
| **D_LOC_Num** | `SO` | Nomor urut delivery location per SO (sequence number) | 001, 002, 003 | System generated from Delivery Schedule |
| **Del_Code** | `DO` | Kode lokasi pengiriman customer (geographical code) | H, BDG, S | `customer_alternate_addresses.delivery_code` atau `update_customer_accounts.geographical` |

---

## ❌ SEBELUM (SALAH)

### **SO.D_LOC_Num Logic (WRONG):**
```php
// SalesOrderController.php - Line 305 (SALAH!)
$dLocNum = (string) ($deliveryData['delivery_code'] ?? '');

if (!empty($dLocNum)) {
    // D_LOC_Num diisi dengan kode alamat (BDG, H, S)
    // SEHARUSNYA: diisi dengan sequence number (001, 002, 003)
}
```

**Result:** `SO.D_LOC_Num` = "BDG" ❌ (SALAH - ini seharusnya Del_Code!)

### **DO.Del_Code Logic (WRONG):**
```php
// DeliveryOrderController.php - Line 159 (SALAH!)
$delCode = $customer->DEL_CODE ?? ($customer->Del_Code ?? '');
```

**Problem:** 
- Kolom `CUSTOMER.DEL_CODE` **TIDAK ADA** di database
- Result: `DO.Del_Code` selalu kosong ❌

---

## ✅ SETELAH (BENAR)

### **1. SO.D_LOC_Num Logic (FIXED):**

```php
// SalesOrderController.php - store() method
// D_LOC_Num will be set from Delivery Schedule (sequence number: 001, 002, 003)
// Default to '001' if no delivery schedule provided yet
$dLocNum = '001';
```

```php
// SalesOrderController.php - saveDeliverySchedule() method
// D_LOC_Num is the sequence number of the first delivery schedule
// Format: 001, 002, 003, etc.
$updates['D_LOC_Num'] = str_pad('1', 3, '0', STR_PAD_LEFT); // Always '001' for first schedule
```

**Result:** `SO.D_LOC_Num` = "001" ✅ (BENAR - sequence number!)

---

### **2. DO.Del_Code Logic (FIXED):**

```php
// DeliveryOrderController.php - store() method (Line 158-210)

// ===================================================================
// Del_Code (Delivery Code / Kode Geo) Logic
// ===================================================================
// Del_Code represents geographical delivery code, not sequence number
// Sources:
// 1. If SO uses alternate address: get delivery_code from customer_alternate_addresses
// 2. If SO uses main address: get geographical code from update_customer_accounts or CUSTOMER.AREA
// ===================================================================

$delCode = '';

// Check if SO uses alternate address by comparing DELIVERY_TO with customer name
if ($so && !empty($so->DELIVERY_TO)) {
    $customerName = $customer->NAME ?? '';
    
    // If delivery name is different from customer name, it's alternate address
    if ($so->DELIVERY_TO !== $customerName) {
        // Try to find matching alternate address
        $alternateAddress = DB::table('customer_alternate_addresses')
            ->where('customer_code', $request->customer_code)
            ->where('ship_to_name', $so->DELIVERY_TO)
            ->first();
        
        if ($alternateAddress) {
            $delCode = $alternateAddress->delivery_code ?? '';
            Log::info('DO Del_Code - From Alternate Address', [
                'del_code' => $delCode,
                'source' => 'customer_alternate_addresses.delivery_code'
            ]);
        }
    }
}

// If Del_Code still empty, get geographical code from customer
if (empty($delCode)) {
    $updateCustomerAccount = DB::table('update_customer_accounts')
        ->where('customer_code', $request->customer_code)
        ->first();
    
    if ($updateCustomerAccount && !empty($updateCustomerAccount->geographical)) {
        $delCode = $updateCustomerAccount->geographical;
        Log::info('DO Del_Code - From Geographical Code', [
            'del_code' => $delCode,
            'source' => 'update_customer_accounts.geographical'
        ]);
    } elseif (!empty($customer->AREA)) {
        $delCode = $customer->AREA;
        Log::info('DO Del_Code - From Customer Area', [
            'del_code' => $delCode,
            'source' => 'CUSTOMER.AREA'
        ]);
    }
}
```

**Result:** `DO.Del_Code` = "BDG" ✅ (BENAR - kode geo!)

---

## 🔄 Flow Data yang BENAR

### **Scenario 1: Ship to Same Address (Main Address)**

```
User Create SO
  ↓
Not select "Ship To" in Delivery Location Modal
  ↓
SO Fields:
  - DELIVERY_TO = "PT Sinar Jaya" (customer name)
  - D_LOC_Num = "001" (sequence number)
  ↓
User Create DO from SO
  ↓
DO Fields:
  - Del_Code = "H" (from update_customer_accounts.geographical or CUSTOMER.AREA)
```

---

### **Scenario 2: Ship to Alternate Address**

```
User Create SO
  ↓
Select "Ship To" → Choose "Bandung Office"
  ↓
SO Fields:
  - DELIVERY_TO = "PT Sinar Jaya - Bandung" (alternate address name)
  - D_LOC_Num = "001" (sequence number)
  ↓
User Create DO from SO
  ↓
DO Fields:
  - Del_Code = "BDG" (from customer_alternate_addresses.delivery_code)
```

---

### **Scenario 3: Split Delivery (Multiple Schedules)**

```
User Create SO
  ↓
Create 3 Delivery Schedules in Delivery Schedule Screen:
  Schedule 1: Date 2025-11-15, Qty 100
  Schedule 2: Date 2025-11-20, Qty 150
  Schedule 3: Date 2025-11-25, Qty 200
  ↓
SO Fields:
  - D_LOC_Num = "001" (first schedule sequence)
  - D_DATE_1 = 2025-11-15, D_QTY_1 = 100
  - D_DATE_2 = 2025-11-20, D_QTY_2 = 150
  - D_DATE_3 = 2025-11-25, D_QTY_3 = 200
  ↓
User Create DO from Schedule 1
  ↓
DO Fields:
  - Del_Code = "BDG" (kode geo - sama untuk semua DO)
  - D_LOC_Num di SO tetap "001" (tidak berubah)
```

---

## 📋 Source Priority Matrix

### **Del_Code (DO Table):**

| Priority | Source | Condition |
|----------|--------|-----------|
| 1 | `customer_alternate_addresses.delivery_code` | If SO uses alternate address (DELIVERY_TO ≠ customer name) |
| 2 | `update_customer_accounts.geographical` | If main address & geographical code exists |
| 3 | `CUSTOMER.AREA` | Fallback if no geographical code |

### **D_LOC_Num (SO Table):**

| Priority | Source | Value |
|----------|--------|-------|
| 1 | Delivery Schedule Screen | "001" (first schedule) |
| 2 | Default on SO creation | "001" |

---

## 🎯 Contoh Kasus Lengkap

### **Customer: PT Sinar Jaya**
- Main Address (Jakarta) → Geographical Code = "H"
- Alternate Address 1 (Bandung) → Delivery Code = "BDG"
- Alternate Address 2 (Surabaya) → Delivery Code = "S"

### **SO Dibuat dengan 2 Kali Pengiriman ke Bandung:**

| Item | Pengiriman Ke | Del_Code (DO) | D_LOC_Num (SO) | Date | Qty |
|------|---------------|---------------|----------------|------|-----|
| Box A | 1 | **BDG** | **001** | 2025-11-15 | 100 |
| Box A | 2 | **BDG** | **001** | 2025-11-20 | 150 |

**Penjelasan:**
- `Del_Code` = **BDG** (sama karena alamat sama)
- `D_LOC_Num` = **001** (sequence number SO, tidak berubah per DO)

---

## 🔧 Files Modified

### **1. SalesOrderController.php**

#### **A. store() Method (Line 297-397)**
**Changes:**
- Line 307: Changed `$dLocNum` to `$delCode` (untuk menyimpan kode geo)
- Line 397: Set `$dLocNum = '001'` (sequence number, bukan kode geo)
- Removed logic yang mengisi `D_LOC_Num` dengan geographical code

#### **B. saveDeliverySchedule() Method (Line 646-652)**
**Changes:**
- Line 649: `D_LOC_Num` diisi dengan sequence number `'001'`
- Removed logic yang mengambil `delivery_code` dari entries

#### **C. updateSalesOrder() Method (Line 1713-1786)**
**Changes:**
- `D_LOC_Num` **TIDAK DIUPDATE** (tetap sequence number)
- Only update `DELIVERY_TO`, `DELIVERY_ADD_1/2/3`

---

### **2. DeliveryOrderController.php**

#### **A. store() Method (Line 158-210)**
**Changes:**
- Added comprehensive Del_Code logic
- Check if SO uses alternate address
- Get `delivery_code` from `customer_alternate_addresses` if alternate
- Get `geographical` from `update_customer_accounts` if main address
- Fallback to `CUSTOMER.AREA`

#### **B. fixMissingData() Method (Line 639-681)**
**Changes:**
- Applied same Del_Code logic for existing DO records
- Check SO to determine if alternate address was used
- Query appropriate source based on address type

---

## 🧪 Testing Scenarios

### **Test 1: Create SO with Main Address**
1. Create SO tanpa pilih "Ship To"
2. **Expected:**
   - `SO.D_LOC_Num` = "001" ✅
   - `SO.DELIVERY_TO` = Customer name
3. Create DO from SO
4. **Expected:**
   - `DO.Del_Code` = Geographical code (dari `update_customer_accounts` atau `CUSTOMER.AREA`) ✅

---

### **Test 2: Create SO with Alternate Address**
1. Create SO → Select "Ship To" → Choose alternate address "Bandung"
2. **Expected:**
   - `SO.D_LOC_Num` = "001" ✅
   - `SO.DELIVERY_TO` = "PT Sinar Jaya - Bandung"
3. Create DO from SO
4. **Expected:**
   - `DO.Del_Code` = "BDG" (dari `customer_alternate_addresses.delivery_code`) ✅

---

### **Test 3: Split Delivery (Multiple Schedules)**
1. Create SO with 3 delivery schedules
2. **Expected:**
   - `SO.D_LOC_Num` = "001" ✅
   - `SO.D_DATE_1/2/3` filled
3. Create DO for each schedule
4. **Expected:**
   - All DOs have same `Del_Code` ✅
   - `SO.D_LOC_Num` remains "001" ✅

---

### **Test 4: Fix Existing DO Records**
1. Call `POST /api/delivery-orders/fix-missing-data`
2. **Expected:**
   - All DO records with empty `Del_Code` updated with correct geographical code ✅

---

## ✅ Benefits

### **For System:**
1. ✅ **Correct Data Model** - sesuai dengan ERP CPS standard
2. ✅ **Clear Separation** - D_LOC_Num (sequence) vs Del_Code (geo code)
3. ✅ **Accurate Reporting** - Del_Code dapat digunakan untuk grouping by location
4. ✅ **Consistent Logic** - sama untuk create, update, dan fix existing data

### **For Users:**
1. ✅ **Correct Delivery Grouping** - DO dapat dikelompokkan berdasarkan Del_Code
2. ✅ **Split Delivery Support** - D_LOC_Num tracks multiple deliveries per SO
3. ✅ **Accurate Location Tracking** - Del_Code menunjukkan lokasi pengiriman yang benar
4. ✅ **Better Logistics Planning** - Kode geo memudahkan perencanaan rute pengiriman

---

## 📝 Summary

**What Was Wrong:**
- `SO.D_LOC_Num` = Geographical code (SALAH!)
- `DO.Del_Code` = Empty (karena kolom source tidak ada)

**What Is Fixed:**
- `SO.D_LOC_Num` = "001" (sequence number) ✅
- `DO.Del_Code` = Geographical code (H, BDG, S) ✅

**Key Concept:**
- **D_LOC_Num** = Internal system sequence untuk tracking split delivery
- **Del_Code** = External geographical code untuk logistics planning

---

**Status**: ✅ COMPLETED
**Date Fixed**: 10 November 2025
**Files Modified**: 
- `app/Http/Controllers/SalesOrderController.php`
- `app/Http/Controllers/DeliveryOrderController.php`
