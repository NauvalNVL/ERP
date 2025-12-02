# 📋 Dokumentasi Table SO (Sales Order) - ERP CPS

## 🎯 Overview

Table **SO (Sales Order)** menyimpan data transaksi Sales Order lengkap dalam sistem ERP CPS.

**Total Kolom:** 92 kolom (termasuk id, timestamps)

---

## 📊 DATA SOURCES MAPPING

| # | Kolom | Type | Source | Keterangan |
|---|-------|------|--------|------------|
| 1 | `id` | BIGINT | Auto Increment | Primary Key |
| 2 | `YYYY` | VARCHAR(50) | System Date | Format: '2025' |
| 3 | `MM` | VARCHAR(50) | System Date | Format: '11' (01-12) |
| 4 | `SO_Num` | VARCHAR(50) | Auto-generated | Format: '2025-11-00001' |
| 5 | `STS` | VARCHAR(50) | System Default | 'Outstanding', 'Closed', 'Cancelled' |
| 6 | `TYPE` | VARCHAR(50) | User Input | `order_type`, default: 'S1' |
| 7 | `SO_DMY` | VARCHAR(50) | System Date | Format: 'DD/MM/YYYY' |
| 8 | `AC_Num` | VARCHAR(50) | User Input | `customer_code` |
| 9 | `AC_NAME` | VARCHAR(250) | `CUSTOMER.NAME` | From CUSTOMER table |
| 10 | `SLM` | VARCHAR(50) | User Input / `CUSTOMER.SLM` | Salesperson code |
| 11 | `IND` | VARCHAR(50) | `CUSTOMER.IND` | Industry |
| 12 | `AREA` | VARCHAR(50) | `CUSTOMER.AREA` | Customer area/region |
| 13 | `GROUP_` | VARCHAR(50) | User Input | `order_group`, default: 'Sales' |
| 14 | `PO_Num` | VARCHAR(50) | User Input | `customer_po_number` |
| 15 | `PO_DATE` | VARCHAR(50) | User Input / System | PO date |
| 16 | `LOT_Num` | VARCHAR(50) | User Input | `lot_number` |
| 17 | `MCS_Num` | VARCHAR(50) | User Input | `master_card_seq` |
| 18 | `MODEL` | VARCHAR(250) | `MC.MODEL` | From Master Card |
| 19 | `PRODUCT` | VARCHAR(250) | User Input / `MC.PRODUCT` | Product code |
| 20 | `COMP_Num` | VARCHAR(50) | `MC.COMP` | Component number |
| 21 | `P_DESIGN` | VARCHAR(50) | `MC.P_DESIGN` | Product design |
| 22 | `PER_SET` | FLOAT | System Default | Default: 1 |
| 23 | `UNIT` | VARCHAR(50) | User Input | `details[0]['uom']` |
| 24 | `PART_NUMBER` | VARCHAR(50) | `MC.PART_NO` | Part number |
| 25 | `INT_L` | FLOAT | `MC.INT_LENGTH` | Internal Length (mm) |
| 26 | `INT_W` | FLOAT | `MC.INT_WIDTH` | Internal Width (mm) |
| 27 | `INT_H` | FLOAT | `MC.INT_HEIGHT` | Internal Height (mm) |
| 28 | `EXT_L` | FLOAT | `MC.EXT_LENGTH` | External Length (mm) |
| 29 | `EXT_W` | FLOAT | `MC.EXT_WIDTH` | External Width (mm) |
| 30 | `EXT_H` | FLOAT | `MC.EXT_HEIGHT` | External Height (mm) |
| 31 | `FLUTE` | VARCHAR(50) | `MC.FLUTE` | Flute type (BC, EB, etc.) |
| 32-36 | `PQ1`-`PQ5` | VARCHAR(50) | `MC.SO_PQ1`-`SO_PQ5` | Paper quality specs |
| 37 | `SO_QTY` | FLOAT | User Input | `details[0]['order_quantity']` |
| 38 | `UNIT_PRICE` | FLOAT | User Input | `details[0]['unit_price']` |
| 39 | `CURR` | VARCHAR(50) | User Input | `currency` |
| 40 | `EX_RATE` | FLOAT | User Input | `exchange_rate`, default: 1 |
| 41 | `AMOUNT` | FLOAT | **Calculated** | `SO_QTY × UNIT_PRICE` |
| 42 | `BASE_AMOUNT` | FLOAT | **Calculated** | `AMOUNT × EX_RATE` |
| 43 | `MC_GROSS_M2_PER_PCS` | FLOAT | `MC.MC_GROSS_M2_PER_PCS` | Gross m² per piece |
| 44 | `MC_NET_M2_PER_PCS` | FLOAT | `MC.MC_NET_M2_PER_PCS` | Net m² per piece |
| 45 | `TOTAL_SO_GROSS_M2` | FLOAT | **Calculated** | `MC_GROSS_M2 × SO_QTY` |
| 46 | `TOTAL_SO_NET_M2` | FLOAT | **Calculated** | `MC_NET_M2 × SO_QTY` |
| 47 | `TOTAL_LM` | VARCHAR(50) | **Calculated** | `(SHEET_LENGTH × QTY) / 1000` |
| 48 | `TOTAL_M3` | INTEGER | **Calculated** | `(L×W×H×QTY) / 1,000,000,000` |
| 49 | `MC_GROSS_KG_PER_PCS` | FLOAT | `MC.MC_GROSS_KG_PER_SET` | Gross kg per piece |
| 50 | `MC_NET_KG_PER_PCS` | FLOAT | `MC.MC_NET_KG_PER_PCS` | Net kg per piece |
| 51 | `TOTAL_SO_GROSS_KG` | FLOAT | **Calculated** | `MC_GROSS_KG × SO_QTY` |
| 52 | `TOTAL_SO_NET_KG` | FLOAT | **Calculated** | `MC_NET_KG × SO_QTY` |
| 53 | `SO_REMARK` | VARCHAR(250) | User Input | `remark` |
| 54 | `SO_INSTRUCTION_1` | VARCHAR(250) | User Input | `instruction1` |
| 55 | `SO_INSTRUCTION_2` | VARCHAR(250) | User Input | `instruction2` |
| 56 | `D_LOC_Num` | VARCHAR(50) | **Kode Geografis** | Priority: alternate addr > geographical > AREA |
| 57 | `DELIVERY_TO` | VARCHAR(250) | Alternate / `CUSTOMER.NAME` | Delivery recipient name |
| 58-60 | `DELIVERY_ADD_1/2/3` | VARCHAR(250) | Alternate / `CUSTOMER.ADDRESS1/2/3` | Delivery address (3 lines) |
| 61-114 | `D_DATE_1`-`D_DATE10` | VARCHAR(50) | User Input | Delivery schedule dates |
| | `D_TIME_1`-`D_TIME10` | VARCHAR(50) | User Input | Delivery schedule times |
| | `D_DUE_1`-`D_DUE10` | VARCHAR(50) | User Input | Due status |
| | `D_QTY_1`-`D_QTY_10` | FLOAT | User Input | Delivery quantities |
| 115-117 | `NW_UID`, `NW_DATE`, `NW_TIME` | VARCHAR(50) | System (Created) | New/Created audit trail |
| 118-120 | `AM_UID`, `AM_DATE`, `AM_TIME` | VARCHAR(50) | System (Updated) | Amended audit trail |
| 121-123 | `CX_UID`, `CX_DATE`, `CX_TIME` | VARCHAR(50) | System (Cancelled) | Cancelled audit trail |
| 124-126 | `PT_UID`, `PT_DATE`, `PT_TIME` | VARCHAR(50) | System (Printed) | Printed audit trail |
| 127 | `SODateSK` | INTEGER | **Calculated** | Format: YYYYMMDD (e.g., 20251110) |
| 128 | `PODateSK` | INTEGER | **Calculated** | Format: YYYYMMDD |
| 129-130 | `created_at`, `updated_at` | TIMESTAMP | Laravel Auto | Laravel timestamps |

---

## 🔢 CALCULATED FIELDS DETAILS

### **AMOUNT**
```php
AMOUNT = SO_QTY × UNIT_PRICE
Example: 1000 × 5000 = 5,000,000
```

### **BASE_AMOUNT**
```php
BASE_AMOUNT = AMOUNT × EX_RATE
Example: 5,000,000 × 1 = 5,000,000 (IDR)
```

### **TOTAL_SO_GROSS_M2**
```php
TOTAL_SO_GROSS_M2 = MC_GROSS_M2_PER_PCS × SO_QTY
Example: 0.45 × 1000 = 450.00 m²
```

### **TOTAL_SO_NET_M2**
```php
TOTAL_SO_NET_M2 = MC_NET_M2_PER_PCS × SO_QTY
Example: 0.40 × 1000 = 400.00 m²
```

### **TOTAL_LM** (Linear Meter)
```php
TOTAL_LM = (SHEET_LENGTH × SO_QTY) / 1,000
// SHEET_LENGTH from MC.SHEET_LENGTH (in mm)
Example: (1200 × 1000) / 1000 = 1200.00 m
```

### **TOTAL_M3** (Cubic Meter / Volume)
```php
TOTAL_M3 = (L × W × H × QTY) / 1,000,000,000
// Dimensions in mm, convert to m³
// Priority: EXT dimensions first, then INT
Example: (500 × 400 × 300 × 100) / 1,000,000,000 = 6 m³
```

### **TOTAL_SO_GROSS_KG**
```php
TOTAL_SO_GROSS_KG = MC_GROSS_KG_PER_PCS × SO_QTY
Example: 0.85 × 1000 = 850.00 kg
```

### **TOTAL_SO_NET_KG**
```php
TOTAL_SO_NET_KG = MC_NET_KG_PER_PCS × SO_QTY
Example: 0.75 × 1000 = 750.00 kg
```

---

## 🚚 D_LOC_Num LOGIC (UPDATED - Kode Geografis)

**Fungsi:** Menyimpan kode geografis lokasi pengiriman (bukan sequence number!)

**Source Priority:**
1. **Alternate Address:** `customer_alternate_addresses.delivery_code`
2. **Main Address:** `update_customer_accounts.geographical`
3. **Fallback:** `CUSTOMER.AREA`

**Example Values:** `'H'`, `'BDG'`, `'S'`, `'JKT'`

**Code:**
```php
$dLocNum = (string) ($deliveryData['delivery_code'] ?? '');

if (empty($dLocNum)) {
    // MODE 1: Main Address
    $updateCustomerAccount = DB::table('update_customer_accounts')
        ->where('customer_code', $customerCode)
        ->first();
    
    if ($updateCustomerAccount && !empty($updateCustomerAccount->geographical)) {
        $dLocNum = $updateCustomerAccount->geographical;
    } elseif (!empty($customerData->AREA)) {
        $dLocNum = $customerData->AREA;
    }
}
```

---

## 📋 DATA FLOW DIAGRAM

```
┌─────────────────┐
│  USER INPUT     │
│  (Frontend)     │
└────────┬────────┘
         │
         ├─ customer_code (AC_Num)
         ├─ master_card_seq (MCS_Num)
         ├─ quantity (SO_QTY)
         ├─ unit_price (UNIT_PRICE)
         └─ delivery_schedules
         │
         ↓
┌─────────────────┐
│ CUSTOMER TABLE  │◄── Query by customer_code
│     Query       │
└────────┬────────┘
         │
         ├─ AC_NAME ← CUSTOMER.NAME
         ├─ SLM ← CUSTOMER.SLM
         ├─ IND ← CUSTOMER.IND
         └─ AREA ← CUSTOMER.AREA
         │
         ↓
┌─────────────────┐
│  MC TABLE       │◄── Query by MCS_Num + AC_Num
│  (Master Card)  │
└────────┬────────┘
         │
         ├─ MODEL, P_DESIGN, PART_NUMBER
         ├─ Dimensions: INT_L/W/H, EXT_L/W/H
         ├─ Material: FLUTE, PQ1-PQ5
         ├─ M2: MC_GROSS_M2, MC_NET_M2
         └─ KG: MC_GROSS_KG, MC_NET_KG
         │
         ↓
┌─────────────────┐
│  CALCULATIONS   │
│  (Backend)      │
└────────┬────────┘
         │
         ├─ AMOUNT = QTY × PRICE
         ├─ TOTAL_M2 = MC_M2 × QTY
         ├─ TOTAL_LM = (SHEET × QTY) / 1000
         ├─ TOTAL_M3 = (L×W×H×QTY) / 1B
         └─ TOTAL_KG = MC_KG × QTY
         │
         ↓
┌─────────────────┐
│   SO TABLE      │
│   (Insert)      │
└─────────────────┘
```

---

## 🎯 KEY TABLES RELATIONSHIP

**SO Table depends on:**
1. **CUSTOMER table** → Customer information (name, area, industry)
2. **MC table** → Product specifications (dimensions, material, calculations)
3. **customer_alternate_addresses** → Alternate delivery addresses (optional)
4. **update_customer_accounts** → Geographical codes (optional)

**SO Table feeds into:**
1. **DO table** → Delivery Orders created from SO
2. **Invoice table** → Invoices created from DO

---

## 📝 IMPORTANT NOTES

1. **D_LOC_Num sekarang menyimpan KODE GEOGRAFIS**, bukan sequence number
2. **TOTAL_M3** stored as INTEGER (rounded)
3. **Schedule 10** menggunakan naming berbeda: `D_DATE10` (no underscore), `D_QTY_10` (with underscore)
4. **Audit trail** menggunakan WIB timezone untuk semua timestamps
5. **Material calculations** (M2, KG, LM, M3) semua dari MC table dan dikalikan dengan SO_QTY

---

**Status:** ✅ DOCUMENTED  
**Date:** 14 November 2025  
**Controller:** `SalesOrderController.php`
