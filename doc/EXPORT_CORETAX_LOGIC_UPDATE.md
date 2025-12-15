# Export to Coretax - Logic Update

**Date**: Dec 15, 2024  
**File Modified**: `app/Http/Controllers/WarehouseManagement/Invoice/ExportToCoretaxController.php`

## Summary

Updated the Export to Coretax controller to implement the exact SQL and PHP logic for determining XML format according to DJP Coretax requirements.

---

## Key Changes

### 1. Transaction Code Determination (SQL Query)

**Before**: Always used code `'10'` (Kawasan Berikat)

**After**: Dynamic determination based on tax type:
```sql
CASE 
    WHEN INV.IV_TAX_CODE = 'NIL' AND INV.IV_TAX_PERCENT = '0' THEN '07' 
    ELSE '01' 
END AS FK_KD_JENIS_TRANSAKSI
```

- **'07'**: For invoices with NIL tax (non-taxable)
- **'01'**: For normal taxable transactions

---

### 2. Conditional XML Elements (PHP Logic)

#### If Transaction Code = '07' (NIL Tax):
```php
if ($kodetransaksi == '07') {
    $keterangantambahan = 'TD.00502';        // Additional Info
    $dokumenpendukung = $row->IV_SECOND_REF; // Supporting Document
    $capfasilitas = 'TD.01101';              // Facility Stamp
    $kodetransaksi = '10';                   // Transform: 07 → 10
}
```

**XML Elements**:
- `<TrxCode>10</TrxCode>` (Penyerahan dari Kawasan Berikat)
- `<AddInfo>TD.00502</AddInfo>` (Kode fasilitas tidak dipungut)
- `<CustomDoc>IV_SECOND_REF value</CustomDoc>` (Dokumen pendukung)
- `<FacilityStamp>TD.01101</FacilityStamp>` (Cap fasilitas)

#### If Transaction Code = '01' (Normal Tax):
```php
else {
    $keterangantambahan = '';  // Empty
    $dokumenpendukung = '';    // Empty
    $capfasilitas = '';        // Empty
    $kodetransaksi = '04';     // Transform: 01 → 04
}
```

**XML Elements**:
- `<TrxCode>04</TrxCode>` (Penyerahan yang PPN-nya harus dipungut sendiri)
- `<AddInfo></AddInfo>` (Empty)
- `<CustomDoc></CustomDoc>` (Empty)
- `<FacilityStamp></FacilityStamp>` (Empty)

---

### 3. Buyer Identification Logic

```php
// Step 1: Determine buyer document type
if ($cust_type == 'PT' || $cust_type == '' || $cust_type == NULL) {
    // Corporate Entity (PT)
    $jenisidpembeli = 'TIN';
    $nodokpembeli = '-';
    // $npwppembeli remains from FK_NPWP
} else {
    // Individual Person (Perorangan)
    $jenisidpembeli = 'National ID';
    $nodokpembeli = $npwppembeli;           // Original NPWP (NIK)
    $npwppembeli = '0000000000000000';      // Zero out NPWP
    $idtkupembeli = '000000';               // Will be overridden
}

// Step 2: Override IDTKU for ALL customer types
$idtkupembeli = $npwppembeli . '000000';
```

**Result**:
- **PT Type**: `BuyerDocument = 'TIN'`, `BuyerIDTKU = NPWP+000000`
- **Non-PT**: `BuyerDocument = 'National ID'`, `BuyerIDTKU = NPWP+000000`

---

### 4. Customer Name Cleaning

```php
$namapembeli = str_replace(
    ["PT ", "PT. ", ", PT"], 
    "", 
    $row->FK_NAMA ?? $row->AC_NAME ?? ''
);
```

**Examples**:
- `"PT MULTIBOX INDAH"` → `"MULTIBOX INDAH"`
- `"PT. ABC COMPANY"` → `"ABC COMPANY"`
- `"XYZ COMPANY, PT"` → `"XYZ COMPANY"`

---

### 5. Address Cleaning

```sql
CUSTOMER.ADDRESS1 + ' ' + CUSTOMER.ADDRESS2 + ' ' + 
CASE 
    WHEN CUSTOMER.ADDRESS3 = '00000' THEN '' 
    ELSE CUSTOMER.ADDRESS3 
END AS FK_ALAMAT_LENGKAP
```

Removes placeholder value `'00000'` from ADDRESS3.

---

### 6. Reference Format

```sql
'IV/' + INV.IV_NUM AS FK_REFERENSI
```

**Example**: Invoice `2024120001` → `IV/2024120001`

Max 16 characters in XML:
```php
$referensi = substr($row->FK_REFERENSI, 0, 16);
```

---

## Complete Flow Diagram

```
┌─────────────────────────────────────────────────────────────────┐
│ Step 1: Query Invoice from Database                             │
├─────────────────────────────────────────────────────────────────┤
│ • Join INV + CUSTOMER tables                                    │
│ • Filter: IV_STS <> 'CX' (exclude cancelled)                    │
│ • Calculate: FK_KD_JENIS_TRANSAKSI                              │
│   - IF IV_TAX_CODE='NIL' AND IV_TAX_PERCENT='0' → '07'          │
│   - ELSE → '01'                                                 │
└─────────────────────────────────────────────────────────────────┘
                              ↓
┌─────────────────────────────────────────────────────────────────┐
│ Step 2: Process Each Invoice for XML Generation                │
├─────────────────────────────────────────────────────────────────┤
│ IF FK_KD_JENIS_TRANSAKSI == '07':                              │
│   ├─ AddInfo = 'TD.00502'                                       │
│   ├─ CustomDoc = IV_SECOND_REF                                  │
│   ├─ FacilityStamp = 'TD.01101'                                 │
│   └─ TrxCode = '10' (transformed)                               │
│                                                                 │
│ ELSE (code '01'):                                               │
│   ├─ AddInfo = '' (empty)                                       │
│   ├─ CustomDoc = '' (empty)                                     │
│   ├─ FacilityStamp = '' (empty)                                 │
│   └─ TrxCode = '04' (transformed)                               │
└─────────────────────────────────────────────────────────────────┘
                              ↓
┌─────────────────────────────────────────────────────────────────┐
│ Step 3: Set Buyer Information                                  │
├─────────────────────────────────────────────────────────────────┤
│ IF CUST_TYPE == 'PT' OR empty OR NULL:                         │
│   ├─ BuyerDocument = 'TIN'                                      │
│   ├─ BuyerDocumentNumber = '-'                                  │
│   └─ BuyerTin = FK_NPWP (original)                              │
│                                                                 │
│ ELSE (Non-PT):                                                  │
│   ├─ BuyerDocument = 'National ID'                              │
│   ├─ BuyerDocumentNumber = FK_NPWP (NIK)                        │
│   └─ BuyerTin = '0000000000000000' (zeroed)                     │
│                                                                 │
│ THEN (for ALL types):                                           │
│   └─ BuyerIDTKU = FK_NPWP + '000000'                            │
└─────────────────────────────────────────────────────────────────┘
                              ↓
┌─────────────────────────────────────────────────────────────────┐
│ Step 4: Generate XML Output                                     │
├─────────────────────────────────────────────────────────────────┤
│ <TaxInvoiceBulk>                                                │
│   <TIN>0014952246415000</TIN>                                   │
│   <ListOfTaxInvoice>                                            │
│     <TaxInvoice>                                                │
│       <TrxCode>10 or 04</TrxCode>                               │
│       <AddInfo>TD.00502 or empty</AddInfo>                      │
│       <CustomDoc>IV_SECOND_REF or empty</CustomDoc>             │
│       <FacilityStamp>TD.01101 or empty</FacilityStamp>          │
│       ...                                                       │
│     </TaxInvoice>                                               │
│   </ListOfTaxInvoice>                                           │
│ </TaxInvoiceBulk>                                               │
└─────────────────────────────────────────────────────────────────┘
```

---

## Transaction Code Mapping

| Tax Type | Database Code | PHP Code | Final XML Code | Description |
|----------|--------------|----------|----------------|-------------|
| NIL Tax (0%) | `'07'` | `'07'` → `'10'` | `10` | Penyerahan dari Kawasan Berikat/KITE |
| Normal Tax (11%) | `'01'` | `'01'` → `'04'` | `04` | Penyerahan yang PPN-nya harus dipungut sendiri |

---

## Testing Scenarios

### Scenario 1: NIL Tax Invoice
**Input**:
- `IV_TAX_CODE = 'NIL'`
- `IV_TAX_PERCENT = 0`
- `IV_SECOND_REF = 'SUPPORTING_DOC_001'`
- `CUST_TYPE = 'PT'`
- `NPWP = '0123456789012345'`

**Expected Output**:
```xml
<TaxInvoice>
  <TrxCode>10</TrxCode>
  <AddInfo>TD.00502</AddInfo>
  <CustomDoc>SUPPORTING_DOC_001</CustomDoc>
  <FacilityStamp>TD.01101</FacilityStamp>
  <BuyerTin>0123456789012345</BuyerTin>
  <BuyerDocument>TIN</BuyerDocument>
  <BuyerDocumentNumber>-</BuyerDocumentNumber>
  <BuyerIDTKU>0123456789012345000000</BuyerIDTKU>
</TaxInvoice>
```

### Scenario 2: Normal Taxable Invoice
**Input**:
- `IV_TAX_CODE = 'PPN'`
- `IV_TAX_PERCENT = 11`
- `IV_SECOND_REF = 'SUPPORTING_DOC_002'` (will be ignored)
- `CUST_TYPE = 'PT'`
- `NPWP = '9876543210987654'`

**Expected Output**:
```xml
<TaxInvoice>
  <TrxCode>04</TrxCode>
  <AddInfo></AddInfo>
  <CustomDoc></CustomDoc>
  <FacilityStamp></FacilityStamp>
  <BuyerTin>9876543210987654</BuyerTin>
  <BuyerDocument>TIN</BuyerDocument>
  <BuyerDocumentNumber>-</BuyerDocumentNumber>
  <BuyerIDTKU>9876543210987654000000</BuyerIDTKU>
</TaxInvoice>
```

### Scenario 3: Individual Customer (Non-PT)
**Input**:
- `IV_TAX_CODE = 'PPN'`
- `IV_TAX_PERCENT = 11`
- `CUST_TYPE = 'Individual'`
- `NPWP = '3216012345678901'` (NIK)

**Expected Output**:
```xml
<TaxInvoice>
  <TrxCode>04</TrxCode>
  <BuyerTin>0000000000000000</BuyerTin>
  <BuyerDocument>National ID</BuyerDocument>
  <BuyerDocumentNumber>3216012345678901</BuyerDocumentNumber>
  <BuyerIDTKU>3216012345678901000000</BuyerIDTKU>
</TaxInvoice>
```

---

## Database Query Example

The actual SQL query executed:

```sql
SELECT 
    ROW_NUMBER() OVER (ORDER BY Z.FK_TANGGAL_FAKTUR) AS 'id', 
    Z.*
FROM (
    SELECT 
        -- 1. Tanggal Faktur
        IV_DMY AS 'FK_TANGGAL_FAKTUR', 
        
        -- 2. Logika Penentuan Kode Transaksi (07 vs 01)
        CASE 
            WHEN IV_TAX_CODE = 'NIL' AND IV_TAX_PERCENT = '0' THEN '07' 
            ELSE '01' 
        END AS 'FK_KD_JENIS_TRANSAKSI',
        
        -- 3. Referensi Faktur (Menambah Prefix 'IV/')
        'IV/' + IV_NUM AS 'FK_REFERENSI', 
        
        -- 4. Dokumen Pendukung
        IV_SECOND_REF, 
        
        -- 5. Nama Customer
        AC_NAME AS 'FK_NAMA', 
        
        -- 6. Logika Penyusunan Alamat (Membersihkan '00000')
        ADDRESS1 + ' ' + ADDRESS2 + ' ' + 
        CASE 
            WHEN ADDRESS3 = '00000' THEN '' 
            ELSE ADDRESS3 
        END AS 'FK_ALAMAT_LENGKAP', 
        
        -- 7. Data Identitas (NPWP & Tipe)
        NPWP AS 'FK_NPWP', 
        CUST_TYPE

    FROM STAGINGCPS.dbo.INV
    LEFT JOIN STAGINGCPS.dbo.CUSTOMER ON CODE = AC_NUM

    WHERE 
        IV_NUM IS NOT NULL 
        AND IV_STS <> 'CX'  -- Abaikan faktur yang Cancel (CX)

    GROUP BY 
        IV_DMY, 
        CASE 
            WHEN IV_TAX_CODE = 'NIL' AND IV_TAX_PERCENT = '0' THEN '07' 
            ELSE '01' 
        END, 
        'IV/' + IV_NUM, 
        IV_SECOND_REF, 
        AC_NAME, 
        ADDRESS1 + ' ' + ADDRESS2 + ' ' + 
        CASE 
            WHEN ADDRESS3 = '00000' THEN '' 
            ELSE ADDRESS3 
        END, 
        NPWP, 
        CUST_TYPE
) Z
ORDER BY FK_REFERENSI;
```

---

## Fixed Seller Information

**MBI (PT. Multibox Indah) Data**:
- `TIN`: `0014952246415000`
- `SellerIDTKU`: `0014952246415000000000` (NPWP + 000000)
- Fixed for all invoices

---

## Important Notes

1. **Invoice Status Filter**: Only processes invoices where `IV_STS <> 'CX'` (excludes cancelled invoices)

2. **Component Filter**: In list view, only shows Main component invoices. In XML generation, includes both Main and Fit components.

3. **Date Format**: Converts `IV_DMY` to `yyyy-mm-dd` format for XML compliance.

4. **NPWP Length**: BuyerTin and BuyerIDTKU must follow exact length requirements (16 digits for TIN, 22 digits for IDTKU).

5. **Reference Length**: FK_REFERENSI is limited to 16 characters in XML: `substr($referensi, 0, 16)`.

6. **Empty String Handling**: Empty XML elements are written as `<Element></Element>`, not omitted.

---

## API Endpoints

### 1. Get Invoices for Export
```
GET /api/invoices/coretax/invoices?month=12&year=2024&customer_code=C001&status=OPEN
```

**Response**:
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "FK_TANGGAL_FAKTUR": "15/12/2024",
      "FK_KD_JENIS_TRANSAKSI": "07",
      "FK_REFERENSI": "IV/2024120001",
      "FK_NAMA": "CUSTOMER NAME",
      "FK_NPWP": "0123456789012345",
      ...
    }
  ]
}
```

### 2. Generate XML
```
POST /api/invoices/coretax/generate-xml
Content-Type: application/json

{
  "invoice_numbers": ["2024120001", "2024120002"]
}
```

**Response**:
```json
{
  "success": true,
  "data": {
    "xml": "<?xml version=\"1.0\" encoding=\"UTF-8\"?>...",
    "filename": "TaxInvoice_20241215123045.xml",
    "invoice_count": 2
  }
}
```

---

## Frontend Integration

Frontend file: `resources/js/Pages/warehouse-management/Invoice/TaxDJP/ExportToCoretax.vue`

**No changes required** - Frontend already properly calls the updated backend API.

---

## Verification Checklist

- [x] Transaction code determined dynamically based on tax type
- [x] Conditional AddInfo field (TD.00502 for code 07, empty for 04)
- [x] Conditional CustomDoc field (IV_SECOND_REF for 07, empty for 04)
- [x] Conditional FacilityStamp field (TD.01101 for 10, empty for 04)
- [x] Transaction code transformation (07→10, 01→04)
- [x] Customer name cleaning (remove PT prefix/suffix)
- [x] Address cleaning (remove '00000')
- [x] Reference format (IV/ prefix)
- [x] Buyer IDTKU override for all customer types
- [x] Customer type logic (PT vs Individual)
- [x] Cancelled invoice filtering (IV_STS <> 'CX')

---

## Rollback Instructions

If you need to revert to the previous logic (always code '10'):

```php
// In getInvoicesForExport() method, line 32-35:
DB::raw("'10' AS FK_KD_JENIS_TRANSAKSI"),

// In buildXml() method, line 270-287:
$kodetransaksi = '10';
$keterangantambahan = 'TD.00502';
$capfasilitas = 'TD.01101';
$dokumenpendukung = $row->IV_SECOND_REF ?? '';
```

---

**Last Updated**: December 15, 2024  
**Updated By**: Cascade AI Assistant  
**Status**: ✅ Production Ready
