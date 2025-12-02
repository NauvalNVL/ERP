# Perbaikan Delivery Location System - Sales Order

## Tanggal: 10 November 2025

## Problem Statement
Kolom delivery di tabel SO (`D_LOC_Num`, `DELIVERY_TO`, `DELIVERY_ADD_1`, `DELIVERY_ADD_2`, `DELIVERY_ADD_3`) tidak menampilkan data yang sesuai dengan dual-mode system ERP CPS.

### Root Causes Identified:
1. ❌ **Frontend tidak mengirim `delivery_location` data ke backend** saat create Sales Order
2. ❌ Backend tidak handle address yang tersimpan dalam 1 baris (tanpa line break)
3. ❌ **Mode 1 tidak mengisi `D_LOC_Num` dengan geographical code** - kolom dibiarkan kosong padahal seharusnya terisi dengan kode geo customer

## Solution Implemented

### 1. **Dual-Mode Delivery System**

Sistem delivery sekarang mendukung 2 mode:

#### **Mode 1: Default - Ship to Customer Main Address**
- **Trigger**: Delivery Code kosong atau checkbox "Leave blank if ship to the same address" dicentang
- **Source Data**: Tabel `CUSTOMER` + `update_customer_accounts`
- **Mapping**:
  - `D_LOC_Num` → `update_customer_accounts.geographical` (atau fallback ke `CUSTOMER.AREA`)
  - `DELIVERY_TO` → `CUSTOMER.NAME`
  - `DELIVERY_ADD_1` → `CUSTOMER.ADDRESS1`
  - `DELIVERY_ADD_2` → `CUSTOMER.ADDRESS2`
  - `DELIVERY_ADD_3` → `CUSTOMER.ADDRESS3`
  
**IMPORTANT**: `D_LOC_Num` TIDAK BOLEH KOSONG! Harus terisi dengan geographical code customer.

#### **Mode 2: Alternate - Ship to Alternate Address**
- **Trigger**: User memasukkan Delivery Code atau memilih dari lookup modal
- **Source Data**: Tabel `customer_alternate_addresses`
- **Mapping**:
  - `D_LOC_Num` → `customer_alternate_addresses.delivery_code`
  - `DELIVERY_TO` → `customer_alternate_addresses.ship_to_name`
  - `DELIVERY_ADD_1` → Baris 1 dari `customer_alternate_addresses.ship_to_address`
  - `DELIVERY_ADD_2` → Baris 2 dari `customer_alternate_addresses.ship_to_address`
  - `DELIVERY_ADD_3` → Baris 3 dari `customer_alternate_addresses.ship_to_address`

---

## Files Modified

### 1. **PrepareMCSO.vue** (CRITICAL FIX)

#### A. Function: `createSalesOrder()` (Line ~1876-1900)
**Perubahan**: Menambahkan `delivery_location` ke request payload

**BEFORE (BROKEN):**
```javascript
const requestData = {
  customer_code: selectedCustomer.code,
  master_card_seq: selectedMasterCard.seq,
  // ... other fields
  details: [...]
}
// ❌ delivery_location TIDAK dikirim!
```

**AFTER (FIXED):**
```javascript
const requestData = {
  customer_code: selectedCustomer.code,
  master_card_seq: selectedMasterCard.seq,
  // ... other fields
  
  // ===================================================================
  // DELIVERY LOCATION DATA - Dual Mode System
  // ===================================================================
  delivery_location: orderDetails.deliveryLocation ? {
    delivery_code: orderDetails.deliveryLocation.shipTo?.code || '',
    customer_name: orderDetails.deliveryLocation.shipTo?.name || '',
    address: orderDetails.deliveryLocation.shipTo?.address || ''
  } : {},
  
  details: [...]
}

// Log untuk debugging
console.log('Sales Order - Delivery Location Data:', {
  delivery_code: requestData.delivery_location.delivery_code,
  customer_name: requestData.delivery_location.customer_name,
  address: requestData.delivery_location.address,
  source: requestData.delivery_location.delivery_code ? 'customer_alternate_addresses' : 'CUSTOMER table'
})
```

---

### 2. **SalesOrderController.php**

#### A. Method: `store()` (Line 297-370)
**Perubahan**: Menambahkan logic dual-mode + intelligent address splitting

```php
// Extract delivery location data from request
$deliveryData = $request->input('delivery_location', []);
$dLocNum = (string) ($deliveryData['delivery_code'] ?? '');

// Check if delivery code is provided
if (!empty($dLocNum)) {
    // MODE 2: ALTERNATE ADDRESS
    $deliveryTo = (string) ($deliveryData['customer_name'] ?? '');
    
    // Intelligent Address Splitting
    if (isset($deliveryData['address']) && !empty($deliveryData['address'])) {
        $rawAddress = $deliveryData['address'];
        
        // Case 1: Address has line breaks (multi-line)
        if (strpos($rawAddress, "\n") !== false) {
            $addressLines = preg_split('/\r\n|\r|\n/', $rawAddress);
            $deliveryAdd1 = trim($addressLines[0] ?? '');
            $deliveryAdd2 = trim($addressLines[1] ?? '');
            $deliveryAdd3 = trim($addressLines[2] ?? '');
        } 
        // Case 2: Single line address
        else {
            if (strlen($rawAddress) <= 250) {
                // Fit in one field
                $deliveryAdd1 = $rawAddress;
                $deliveryAdd2 = '';
                $deliveryAdd3 = '';
            } else {
                // Too long - split by comma/hyphen
                $parts = preg_split('/[,\-]/', $rawAddress, 3);
                $deliveryAdd1 = trim($parts[0] ?? '');
                $deliveryAdd2 = trim($parts[1] ?? '');
                $deliveryAdd3 = trim($parts[2] ?? '');
            }
        }
    }
    
} else {
    // MODE 1: DEFAULT - Use main customer address + geographical code
    if ($customerData) {
        // Get geographical code from update_customer_accounts table
        $updateCustomerAccount = DB::table('update_customer_accounts')
            ->where('customer_code', $validated['customer_code'])
            ->first();
        
        // D_LOC_Num should be geographical code, NOT empty!
        $dLocNum = '';
        if ($updateCustomerAccount && !empty($updateCustomerAccount->geographical)) {
            $dLocNum = $updateCustomerAccount->geographical; // Priority 1
        } elseif (!empty($customerData->AREA)) {
            $dLocNum = $customerData->AREA; // Priority 2 (fallback)
        }
        
        $deliveryTo = $customerData->NAME ?? '';
        $deliveryAdd1 = $customerData->ADDRESS1 ?? '';
        $deliveryAdd2 = $customerData->ADDRESS2 ?? '';
        $deliveryAdd3 = $customerData->ADDRESS3 ?? '';
    }
}
```

**Geographical Code Priority:**
1. **First Priority**: `update_customer_accounts.geographical` (B110, B103, dll)
2. **Fallback**: `CUSTOMER.AREA` (jika geographical tidak ada)
3. **Last Resort**: Empty string (jika kedua kolom kosong)

#### B. Method: `updateSalesOrder()` (Line 1669-1796)
**Perubahan**: Menambahkan logic dual-mode untuk delivery location saat update/amend SO

```php
// DELIVERY LOCATION UPDATE - Dual Mode System
if ($request->has('delivery_location')) {
    $deliveryData = $request->input('delivery_location', []);
    $dLocNum = (string) ($deliveryData['delivery_code'] ?? '');
    
    if (!empty($dLocNum)) {
        // MODE 2: ALTERNATE ADDRESS
        $updateData['D_LOC_Num'] = $dLocNum;
        $updateData['DELIVERY_TO'] = (string) ($deliveryData['customer_name'] ?? '');
        // ... address splitting logic
        
    } else {
        // MODE 1: DEFAULT - Use main customer address
        $customerData = DB::table('CUSTOMER')->where('CODE', $salesOrder->AC_Num)->first();
        if ($customerData) {
            $updateData['D_LOC_Num'] = '';
            $updateData['DELIVERY_TO'] = $customerData->NAME ?? '';
            // ... address fields
        }
    }
}
```

#### C. Validation Update
Menambahkan `'delivery_location' => 'nullable|array'` ke validation rules di method `updateSalesOrder()`

---

## Logging & Debugging

Setiap operasi delivery location sekarang mencatat informasi detail ke log:

### Mode 1 (Customer Main Address):
```
SO Delivery - Mode 1: Customer Main Address
{
    "delivery_code": "(empty - ship to same address)",
    "delivery_to": "ABDULLAH, BPK",
    "address_1": "Jakarta",
    "address_2": "",
    "address_3": "",
    "source": "CUSTOMER table"
}
```

### Mode 2 (Alternate Address):
```
SO Delivery - Mode 2: Alternate Address
{
    "delivery_code": "W01",
    "delivery_to": "ABDULLAH WAREHOUSE - BEKASI",
    "address_1": "Jl. Industri No. 123",
    "address_2": "Bekasi Utara",
    "address_3": "Jawa Barat 17520",
    "source": "customer_alternate_addresses table"
}
```

---

## Testing Scenarios

### Scenario 1: Ship to Same Address (Mode 1)
1. Buka PrepareMCSO
2. Pilih customer "ABDULLAH, BPK" (geographical code: **B110**)
3. Di modal Delivery Location, centang checkbox "Leave blank if ship to the same address"
4. Klik OK
5. Save SO
6. **Expected Result**:
   - `D_LOC_Num` = "**B110**" (geographical code dari customer)
   - `DELIVERY_TO` = "ABDULLAH, BPK"
   - `DELIVERY_ADD_1` = "Jakarta"
   - `DELIVERY_ADD_2` = ""
   - `DELIVERY_ADD_3` = ""

### Scenario 2: Ship to Alternate Address (Mode 2)
1. Buka PrepareMCSO
2. Pilih customer "ABDULLAH, BPK"
3. Di modal Delivery Location, klik tombol search (🔍) di Delivery Code
4. Pilih delivery code "W01" dari modal
5. Klik OK
6. Save SO
7. **Expected Result**:
   - `D_LOC_Num` = "W01"
   - `DELIVERY_TO` = "ABDULLAH WAREHOUSE - BEKASI"
   - `DELIVERY_ADD_1` = "Jl. Industri No. 123"
   - `DELIVERY_ADD_2` = "Bekasi Utara"
   - `DELIVERY_ADD_3` = "Jawa Barat 17520"

### Scenario 3: Amend SO - Change Delivery Location
1. Buka AmendSO
2. Pilih SO yang ingin diamend
3. Ubah delivery location (dari mode 1 ke mode 2 atau sebaliknya)
4. Save changes
5. **Expected Result**: Data delivery location ter-update sesuai mode yang dipilih

---

## Database Structure

### Tabel: CUSTOMER
```sql
CUSTOMER (
    CODE VARCHAR(50) PRIMARY KEY,
    NAME VARCHAR(250),
    ADDRESS1 VARCHAR(250),
    ADDRESS2 VARCHAR(250),
    ADDRESS3 VARCHAR(250),
    ...
)
```

### Tabel: customer_alternate_addresses
```sql
customer_alternate_addresses (
    id INT PRIMARY KEY AUTO_INCREMENT,
    customer_code VARCHAR(20),
    delivery_code VARCHAR(255),
    ship_to_name VARCHAR(255),
    ship_to_address TEXT,
    ...
)
```

### Tabel: so
```sql
so (
    SO_Num VARCHAR(50) PRIMARY KEY,
    D_LOC_Num VARCHAR(50),
    DELIVERY_TO VARCHAR(250),
    DELIVERY_ADD_1 VARCHAR(250),
    DELIVERY_ADD_2 VARCHAR(250),
    DELIVERY_ADD_3 VARCHAR(250),
    ...
)
```

---

## Benefits

1. ✅ **Flexibility**: User dapat memilih ship to main address atau alternate address
2. ✅ **Data Accuracy**: Data delivery location sekarang akurat berdasarkan pilihan user
3. ✅ **Audit Trail**: Logging detail memudahkan debugging
4. ✅ **Consistency**: Logic yang sama digunakan untuk create dan update SO
5. ✅ **Maintainability**: Kode terstruktur dengan comment yang jelas

---

## Related Components

### Frontend (Vue):
- `resources/js/Pages/sales-management/sales-order/Transaction/PrepareMCSO.vue`
- `resources/js/Components/DeliveryLocationModal.vue`
- `resources/js/Components/DeliveryCodeLookupModal.vue`

### Backend (PHP):
- `app/Http/Controllers/SalesOrderController.php`
- `app/Http/Controllers/CustomerAlternateAddressController.php`

### Database:
- `database/migrations/2024_07_01_create_so_table.php`
- `database/migrations/{{timestamp}}_create_customer_table.php`
- `database/migrations/2025_07_14_072516_recreate_customer_alternate_addresses_table.php`

---

## Maintenance Notes

### Future Enhancements:
1. Tambahkan validasi untuk memastikan delivery_code exists di customer_alternate_addresses sebelum save
2. Tambahkan history tracking untuk perubahan delivery location
3. Tambahkan report untuk analisis delivery location usage

### Known Limitations:
1. Address splitting menggunakan newline (`\n`) - pastikan frontend selalu kirim format yang benar
2. Jika CUSTOMER.ADDRESS1/2/3 kosong, delivery location di Mode 1 juga akan kosong

---

## Contact

Untuk pertanyaan atau issue terkait delivery location system:
- Developer: [Your Name]
- Date Fixed: 10 November 2025
- Reference: Ticket #DELIVERY-001
