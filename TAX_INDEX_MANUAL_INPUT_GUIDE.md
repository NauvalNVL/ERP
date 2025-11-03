# Tax Index No. Manual Input - Complete Guide

## 📋 Overview
Kolom **Tax Index No.** pada menu **Prepare Invoice by D/Order (Current Period)** sekarang sudah mendukung **manual input** dengan auto-load data dari menu **Define Customer Sales Tax Index**.

---

## ✅ **KONFIRMASI: DATA DIAMBIL DARI MANA?**

### **Sumber Data:**
```
Table: customer_sales_tax_indices
Menu: Define Customer Sales Tax Index
Path: Invoice → Setup → Define Customer Sales Tax Index
```

### **API Endpoint:**
```
GET /api/invoices/customer-tax-indices/{customerCode}
```

### **Backend Controller:**
```php
// File: app/Http/Controllers/Invoice/CustomerSalesTaxIndexController.php
public function getCustomerIndices($customerCode)
{
    // Query ke table customer_sales_tax_indices
    $indices = CustomerSalesTaxIndex::where('customer_code', $customerCode)
        ->with('taxGroup')
        ->orderBy('index_number')
        ->get();
}
```

---

## 🎯 **Cara Menggunakan Manual Input**

### **Step 1: Pastikan Data Sudah Ada di Define Customer Sales Tax Index**

1. Buka menu: **Invoice → Setup → Define Customer Sales Tax Index**
2. Pilih Customer: **000211-08**
3. Tambahkan Tax Index:
   ```
   Index 01 → PPN (PPN 10%)
   Index 02 → PPN11 (PPN11)
   Index 03 → EXEMPT (Tax Exempt)
   ```
4. **SAVE**

### **Step 2: Gunakan di Prepare Invoice**

1. Buka menu: **Prepare Invoice by D/Order (Current Period)**
2. Pilih **Customer: 000211-08**
3. Di kolom **Tax Index No.**, ketik: **"1"** atau **"01"**
4. Tekan **Enter** atau **klik di luar field**

### **Step 3: Sistem Auto-Load**

System akan:
1. ✅ Auto-format: "1" → "01"
2. ✅ Query database: `customer_sales_tax_indices` table
3. ✅ Cari index_number = 1 untuk customer 000211-08
4. ✅ Load data: PPN - PPN 10%
5. ✅ Tampilkan konfirmasi: **✓ Tax Group: PPN - PPN 10%**

---

## 📊 **Console Logging (Untuk Debugging)**

### **Frontend Console (Browser F12)**

Ketika user mengetik Tax Index No., console akan menampilkan:

```
═══════════════════════════════════════════════════════════
📋 FETCHING TAX INDEX FROM DEFINE CUSTOMER SALES TAX INDEX
═══════════════════════════════════════════════════════════
   Customer Code: 000211-08
   Index Number: 1
   API Endpoint: /api/invoices/customer-tax-indices/000211-08
   Data Source: customer_sales_tax_indices table (Define Customer Sales Tax Index menu)

📦 API Response from customer_sales_tax_indices table:
{
  "success": true,
  "data": [
    {
      "index_number": 1,
      "tax_group_code": "PPN",
      "tax_group": { "name": "PPN 10%" },
      "status": "A"
    },
    {
      "index_number": 2,
      "tax_group_code": "PPN11",
      "tax_group": { "name": "PPN11" },
      "status": "A"
    }
  ]
}

✅ SUCCESS! Tax Index Found in Define Customer Sales Tax Index
═══════════════════════════════════════════════════════════
📊 Retrieved Data from customer_sales_tax_indices table:
   Index Number: 1
   Tax Group Code: PPN
   Tax Group Name: PPN 10%
   Status: A
   Source Table: customer_sales_tax_indices
   Source Menu: Define Customer Sales Tax Index
═══════════════════════════════════════════════════════════
```

### **Backend Log (Laravel)**

File: `storage/logs/laravel.log`

```
[2025-11-03 12:10:00] local.INFO: ═══════════════════════════════════════════════════════════
[2025-11-03 12:10:00] local.INFO: 📋 API REQUEST: Get Customer Tax Indices
[2025-11-03 12:10:00] local.INFO:    Endpoint: GET /api/invoices/customer-tax-indices/{customerCode}
[2025-11-03 12:10:00] local.INFO:    Customer Code: 000211-08
[2025-11-03 12:10:00] local.INFO:    Data Source: customer_sales_tax_indices table
[2025-11-03 12:10:00] local.INFO:    Source Menu: Define Customer Sales Tax Index
[2025-11-03 12:10:00] local.INFO: ✅ Query Result: 2 tax indices found
[2025-11-03 12:10:00] local.INFO:    - Index 1: PPN (PPN 10%) - Status: A
[2025-11-03 12:10:00] local.INFO:    - Index 2: PPN11 (PPN11) - Status: A
[2025-11-03 12:10:00] local.INFO: ═══════════════════════════════════════════════════════════
```

---

## ⚠️ **Error Handling**

### **Error 1: No Customer Selected**
```
Alert: "Please select a customer first before entering tax index number."
```
**Solution:** Pilih customer dulu sebelum input tax index.

### **Error 2: Index Not Found**
```
Alert: "Tax Index 03 not found in Define Customer Sales Tax Index.

Available indices for this customer:
  01 - PPN
  02 - PPN11"
```
**Solution:** 
1. Check console untuk list available indices
2. Gunakan index yang tersedia
3. Atau tambahkan index baru di Define Customer Sales Tax Index

### **Error 3: No Indices Defined**
```
Alert: "No tax indices defined for customer 000211-08.

Please add tax indices in:
Invoice → Setup → Define Customer Sales Tax Index"
```
**Solution:** Buat tax index untuk customer ini di menu Define Customer Sales Tax Index.

---

## 🔍 **Verifikasi Database**

### **Query untuk Check Data:**

```sql
-- Check apakah customer punya tax indices
SELECT 
    csti.customer_code,
    csti.index_number,
    csti.tax_group_code,
    tg.name as tax_group_name,
    csti.status
FROM customer_sales_tax_indices csti
JOIN tax_groups tg ON csti.tax_group_code = tg.code
WHERE csti.customer_code = '000211-08'
ORDER BY csti.index_number;
```

**Expected Result:**
```
customer_code | index_number | tax_group_code | tax_group_name | status
--------------|--------------|----------------|----------------|--------
000211-08     | 1            | PPN            | PPN 10%        | A
000211-08     | 2            | PPN11          | PPN11          | A
```

### **Query untuk Insert Test Data:**

```sql
-- Insert sample tax indices
INSERT INTO customer_sales_tax_indices 
(customer_code, index_number, tax_group_code, status, created_at, updated_at) 
VALUES 
('000211-08', 1, 'PPN', 'A', NOW(), NOW()),
('000211-08', 2, 'PPN11', 'A', NOW(), NOW())
ON DUPLICATE KEY UPDATE 
    tax_group_code = VALUES(tax_group_code),
    status = VALUES(status),
    updated_at = NOW();
```

---

## 🎨 **UI States**

### **1. Empty (Required)**
```
┌──────────────────────────────────────┐
│ Tax Index No. *                      │
│ [                        ] [🔍]     │
│ ⚠️ Required: Please select a tax     │
└──────────────────────────────────────┘
```

### **2. Loading**
```
┌──────────────────────────────────────┐
│ Tax Index No. *                      │
│ [01                      ] [🔍]     │
│ ⏳ Loading tax group...              │
└──────────────────────────────────────┘
```

### **3. Success**
```
┌──────────────────────────────────────┐
│ Tax Index No. *                      │
│ [01                      ] [🔍]     │
│ ✓ Tax Group: PPN - PPN 10%           │
└──────────────────────────────────────┘
```

---

## 📝 **Complete Flow Diagram**

```
┌─────────────────────────────────────────────────────────────┐
│ 1. DEFINE TAX INDEX (Setup Menu)                            │
│    Invoice → Setup → Define Customer Sales Tax Index        │
│    - Select Customer: 000211-08                             │
│    - Add Index 01 → PPN                                     │
│    - Add Index 02 → PPN11                                   │
│    - SAVE to customer_sales_tax_indices table               │
└─────────────────────────────────────────────────────────────┘
                            ↓
┌─────────────────────────────────────────────────────────────┐
│ 2. PREPARE INVOICE (Processing Menu)                        │
│    Prepare Invoice by D/Order (Current Period)              │
│    - Select Customer: 000211-08                             │
│    - Type Tax Index No: "1" or "01"                         │
│    - Press Enter or Blur                                    │
└─────────────────────────────────────────────────────────────┘
                            ↓
┌─────────────────────────────────────────────────────────────┐
│ 3. AUTO-FETCH FROM DATABASE                                 │
│    Frontend: fetchTaxIndexByNumber()                        │
│    → API Call: GET /api/invoices/customer-tax-indices/...   │
│    → Backend: CustomerSalesTaxIndexController               │
│    → Query: customer_sales_tax_indices table                │
│    → Find: index_number = 1                                 │
│    → Return: { PPN, PPN 10% }                               │
└─────────────────────────────────────────────────────────────┘
                            ↓
┌─────────────────────────────────────────────────────────────┐
│ 4. DISPLAY RESULT                                           │
│    ✓ Tax Group: PPN - PPN 10%                               │
│    selectedIndexData populated                              │
│    Ready for Continue to Prepare                            │
└─────────────────────────────────────────────────────────────┘
```

---

## ✅ **Summary**

**CONFIRMED: Tax Index No. mengambil data dari:**
1. ✅ **Table:** `customer_sales_tax_indices`
2. ✅ **Menu:** Define Customer Sales Tax Index
3. ✅ **API:** `/api/invoices/customer-tax-indices/{customerCode}`
4. ✅ **Controller:** `CustomerSalesTaxIndexController@getCustomerIndices`

**User dapat:**
1. ✅ Mengetik index number manual (01, 02, dst)
2. ✅ Auto-load tax group dari Define Customer Sales Tax Index
3. ✅ Lihat konfirmasi tax group yang ter-load
4. ✅ Atau browse via modal seperti sebelumnya

**Logging lengkap untuk debugging:**
1. ✅ Frontend console (Browser F12)
2. ✅ Backend log (storage/logs/laravel.log)
3. ✅ Clear error messages dengan solusi

---

**Data 100% diambil dari menu Define Customer Sales Tax Index!** ✅
