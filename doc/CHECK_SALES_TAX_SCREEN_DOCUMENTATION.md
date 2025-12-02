# Check Sales Tax Screen - Documentation

## Overview
**Check Sales Tax Screen** adalah modal validasi pajak yang muncul setelah user mengisi data awal (customer, period, remark, dll.) dan sebelum menampilkan daftar Delivery Orders untuk dipilih.

Modal ini mengikuti workflow CPS Enterprise 2020 untuk memastikan konfigurasi pajak yang benar sebelum membuat invoice.

## Tujuan Utama

1. **Verifikasi Konfigurasi Pajak** - Memastikan tax code yang akan digunakan sudah benar
2. **Validasi Sebelum Invoice** - Cross-check otomatis sebelum membuat record invoice
3. **Transparansi Tarif Pajak** - Menampilkan tarif pajak yang akan diterapkan
4. **Kontrol Tax Inclusion** - Menentukan apakah pajak included atau added

## Alur Sistem

```
┌─────────────────────────────────────────────────────────────────┐
│ 1. User buka menu "Prepare Invoice by D/Order (Current Period)" │
└────────────────────┬────────────────────────────────────────────┘
                     │
                     ▼
┌─────────────────────────────────────────────────────────────────┐
│ 2. User mengisi data awal:                                      │
│    - Current Period                                              │
│    - Update Period                                               │
│    - Customer Code (via lookup)                                  │
│    - Currency (auto-populated)                                   │
│    - Tax Index No. (optional)                                    │
│    - Invoice Date                                                │
│    - 2nd Reference                                               │
│    - Remark                                                      │
└────────────────────┬────────────────────────────────────────────┘
                     │
                     ▼
┌─────────────────────────────────────────────────────────────────┐
│ 3. User klik "Continue to Prepare"                              │
└────────────────────┬────────────────────────────────────────────┘
                     │
                     ▼
┌─────────────────────────────────────────────────────────────────┐
│ 4. ✨ CHECK SALES TAX SCREEN MUNCUL ✨                          │
│                                                                  │
│    Menampilkan tabel:                                            │
│    ┌──────────┬────────┬───────┬──────┬─────────┐              │
│    │ Tax Code │  Name  │ Apply │ Tax% │ Include │              │
│    ├──────────┼────────┼───────┼──────┼─────────┤              │
│    │ PPN11    │ PPN 11%│  Yes  │ 11.00│   No    │ ◄ Selected   │
│    │ PPN10    │ PPN 10%│  Yes  │ 10.00│   No    │              │
│    └──────────┴────────┴───────┴──────┴─────────┘              │
│                                                                  │
│    Buttons: [Zoom] [Exit] [OK]                                  │
└────────────────────┬────────────────────────────────────────────┘
                     │
                     ▼
┌─────────────────────────────────────────────────────────────────┐
│ 5. User memilih tax code dan klik "OK"                          │
└────────────────────┬────────────────────────────────────────────┘
                     │
                     ▼
┌─────────────────────────────────────────────────────────────────┐
│ 6. Sistem update tax_index_no dengan pilihan user               │
└────────────────────┬────────────────────────────────────────────┘
                     │
                     ▼
┌─────────────────────────────────────────────────────────────────┐
│ 7. Delivery Order Selection Screen muncul                       │
│    (PrepareInvoiceFlowModal)                                     │
└────────────────────┬────────────────────────────────────────────┘
                     │
                     ▼
┌─────────────────────────────────────────────────────────────────┐
│ 8. User pilih DO yang akan di-invoice                           │
└────────────────────┬────────────────────────────────────────────┘
                     │
                     ▼
┌─────────────────────────────────────────────────────────────────┐
│ 9. Sistem buat invoice dengan tax code yang sudah dikonfirmasi  │
└─────────────────────────────────────────────────────────────────┘
```

## Komponen yang Dibuat

### 1. CheckSalesTaxModal.vue
**Location**: `resources/js/Components/CheckSalesTaxModal.vue`

**Props**:
- `open` (Boolean) - Kontrol visibility modal
- `customerCode` (String) - Kode customer yang dipilih
- `preselectedTaxCode` (String) - Tax code yang sudah dipilih sebelumnya (auto-select)

**Events**:
- `@close` - Emit ketika user klik Exit atau close button
- `@confirm` - Emit ketika user klik OK dengan data tax yang dipilih

**Features**:
- ✅ Menampilkan daftar sales tax aktif
- ✅ Radio button selection untuk memilih tax
- ✅ Highlight row yang dipilih (background biru)
- ✅ Auto-select tax code jika sudah ada preselection
- ✅ Auto-select first active tax jika belum ada pilihan
- ✅ Badge untuk status Apply (Yes/No)
- ✅ Badge untuk status Include (Yes/No)
- ✅ Format percentage dengan 2 desimal
- ✅ Loading state saat fetch data
- ✅ Empty state jika tidak ada tax aktif
- ✅ Summary box menampilkan tax yang dipilih
- ✅ Validation: OK button disabled jika belum pilih tax

## Struktur Tabel Check Sales Tax

| Column | Description | Example | Format |
|--------|-------------|---------|--------|
| **No Tax Code** | Kode pajak unik | PPN11 | String |
| **Name** | Nama lengkap pajak | PPN 11% | String |
| **Apply** | Status apakah pajak diterapkan | Yes / No | Badge |
| **Tax%** | Tarif pajak dalam persen | 11.00% | Decimal(5,2) |
| **Include** | Pajak sudah termasuk di harga | Yes / No | Badge |

### Penjelasan Kolom:

#### 1. No Tax Code
- Identifier unik untuk tax code
- Digunakan untuk referensi di invoice
- Contoh: `PPN11`, `PPN10`, `PPH23`

#### 2. Name
- Deskripsi lengkap dari tax code
- Membantu user memahami jenis pajak
- Contoh: `PPN 11%`, `PPh Pasal 23`

#### 3. Apply
- **Yes** = Pajak aktif dan akan diterapkan ke invoice
- **No** = Pajak tidak aktif (biasanya tidak ditampilkan)
- Warna badge:
  - Yes = Green background
  - No = Gray background

#### 4. Tax%
- Tarif pajak dalam bentuk persentase
- Format: 2 desimal (11.00%)
- Digunakan untuk kalkulasi: `tax_amount = subtotal × (tax_rate / 100)`

#### 5. Include
- **Yes** = Pajak sudah included di harga jual
  - Total invoice = Harga jual (sudah termasuk pajak)
  - Pajak dihitung dengan formula: `tax = total / (1 + tax_rate) × tax_rate`
- **No** = Pajak ditambahkan di luar harga
  - Total invoice = Harga jual + Pajak
  - Pajak dihitung dengan formula: `tax = subtotal × tax_rate`

## API Endpoint

### GET `/api/invoices/sales-tax-options`

**Response Format**:
```json
[
  {
    "code": "PPN11",
    "name": "PPN 11%",
    "rate": 11.00,
    "status": "Active",
    "apply": 1,
    "include": 0
  },
  {
    "code": "PPN10",
    "name": "PPN 10%",
    "rate": 10.00,
    "status": "Active",
    "apply": 1,
    "include": 0
  }
]
```

**Field Mapping**:
- `code` → Tax Code
- `name` → Tax Name
- `rate` → Tax Percentage (decimal)
- `status` → Status (Active/Inactive)
- `apply` → 1 = Yes, 0 = No
- `include` → 1 = Yes (included), 0 = No (added)

## Database Schema

### Table: Sales_Tax

```sql
CREATE TABLE Sales_Tax (
    tax_code VARCHAR(50) PRIMARY KEY,
    tax_name VARCHAR(250),
    tax_rate DECIMAL(5,2),
    status VARCHAR(50),
    tax_included TINYINT(1) DEFAULT 0,
    -- Other fields...
);
```

**Sample Data**:
```sql
INSERT INTO Sales_Tax (tax_code, tax_name, tax_rate, status, tax_included) VALUES
('PPN11', 'PPN 11%', 11.00, 'Active', 0),
('PPN10', 'PPN 10%', 10.00, 'Active', 0),
('PPH23', 'PPh Pasal 23', 2.00, 'Active', 0);
```

## Integrasi dengan Invoice Preparation

### Flow Data:

1. **User Konfirmasi Tax** di Check Sales Tax Screen
   ```javascript
   {
     code: "PPN11",
     name: "PPN 11%",
     rate: 11.00,
     apply: true,
     include: false
   }
   ```

2. **Tax Code Disimpan** ke `taxIndexNo` state
   ```javascript
   taxIndexNo.value = selectedTax.code // "PPN11"
   ```

3. **Dikirim ke API** saat prepare invoice
   ```javascript
   POST /api/invoices/prepare
   {
     "do_numbers": ["DO-001", "DO-002"],
     "tax_index_no": "PPN11",
     "customer_code": "CUST001",
     // ... other fields
   }
   ```

4. **Disimpan ke Database** INV table
   ```sql
   INSERT INTO INV (
     IV_NUM, 
     IV_TAX_CODE, 
     IV_TAX_PERCENT,
     IV_TRAN_AMT,
     -- ... other fields
   ) VALUES (
     'IV-202510-0001',
     'PPN11',
     11.00,
     1000000,
     -- ...
   );
   ```

## Perhitungan Pajak

### Case 1: Tax NOT Included (include = No)
```
Subtotal:     Rp 1,000,000
Tax (11%):    Rp   110,000  ← (1,000,000 × 11%)
─────────────────────────────
Grand Total:  Rp 1,110,000
```

### Case 2: Tax Included (include = Yes)
```
Total Price:  Rp 1,110,000
Tax (11%):    Rp   110,000  ← (1,110,000 / 1.11 × 0.11)
─────────────────────────────
Subtotal:     Rp 1,000,000
```

## UI/UX Features

### Visual Feedback
1. **Row Highlighting**
   - Selected row: Blue background (`bg-blue-100`)
   - Hover: Gray background (`hover:bg-gray-50`)

2. **Radio Button**
   - Sinkron dengan row selection
   - Click row = select radio
   - Click radio = select row

3. **Status Badges**
   - Apply Yes: Green badge
   - Apply No: Gray badge
   - Include Yes: Blue badge
   - Include No: Gray badge

4. **Summary Box**
   - Muncul setelah user memilih tax
   - Background hijau dengan icon checkmark
   - Menampilkan: Code - Name (Rate)

### Buttons

#### Zoom Button
- **Function**: Menampilkan detail tax (optional)
- **Icon**: Magnifying glass
- **Style**: Secondary button (gray border)

#### Exit Button
- **Function**: Tutup modal tanpa konfirmasi
- **Icon**: X icon
- **Style**: Secondary button
- **Action**: Emit `@close` event

#### OK Button
- **Function**: Konfirmasi pilihan tax
- **Icon**: Checkmark
- **Style**: Primary button (blue)
- **State**: Disabled jika belum pilih tax
- **Action**: Emit `@confirm` event dengan data tax

## Error Handling

### Scenario 1: No Active Tax Found
```
┌─────────────────────────────────────┐
│  No active tax codes found          │
└─────────────────────────────────────┘
```
- OK button tetap disabled
- User harus Exit dan setup tax di master data

### Scenario 2: API Fetch Failed
```javascript
try {
  const res = await fetch('/api/invoices/sales-tax-options')
  // ...
} catch (e) {
  console.error('Failed to fetch tax options:', e)
  taxOptions.value = []
}
```
- Tampilkan empty state
- Log error ke console

### Scenario 3: User Close Without Selection
- Modal ditutup
- Tax code tidak berubah
- DO selection tidak dibuka
- User kembali ke form awal

## Testing Checklist

### Manual Testing

- [ ] **Modal Appearance**
  - [ ] Modal muncul setelah klik "Continue to Prepare"
  - [ ] Modal tidak muncul jika customer belum dipilih
  - [ ] Modal memiliki backdrop blur

- [ ] **Data Loading**
  - [ ] Loading spinner muncul saat fetch data
  - [ ] Data tax ditampilkan setelah loading selesai
  - [ ] Empty state muncul jika tidak ada data

- [ ] **Tax Selection**
  - [ ] Radio button berfungsi
  - [ ] Row highlighting berfungsi
  - [ ] Click row = select tax
  - [ ] Summary box muncul setelah select

- [ ] **Auto Selection**
  - [ ] Preselected tax ter-select otomatis
  - [ ] First active tax ter-select jika tidak ada preselection

- [ ] **Buttons**
  - [ ] Exit button menutup modal
  - [ ] OK button disabled jika belum pilih
  - [ ] OK button enabled setelah pilih
  - [ ] OK button emit event dengan data benar

- [ ] **Integration**
  - [ ] Tax code tersimpan ke state
  - [ ] DO selection modal muncul setelah confirm
  - [ ] Tax code dikirim ke API prepare

### Database Testing

```sql
-- Check Sales_Tax table
SELECT tax_code, tax_name, tax_rate, status, tax_included
FROM Sales_Tax
WHERE status = 'Active'
ORDER BY tax_code;

-- Verify invoice has tax
SELECT IV_NUM, AC_NUM, IV_TAX_CODE, IV_TAX_PERCENT, IV_TRAN_AMT
FROM INV
WHERE IV_TAX_CODE IS NOT NULL
ORDER BY IV_NUM DESC
LIMIT 10;
```

## Troubleshooting

### Issue: Modal tidak muncul
**Solution**:
- Check `hasCustomer` computed property
- Pastikan customer sudah dipilih
- Check console untuk error

### Issue: Tax options kosong
**Solution**:
- Check Sales_Tax table ada data
- Pastikan status = 'Active'
- Check API endpoint `/api/invoices/sales-tax-options`

### Issue: OK button selalu disabled
**Solution**:
- Check `selectedTax` ref tidak null
- Pastikan user sudah klik salah satu row
- Check radio button binding

### Issue: Tax tidak tersimpan ke invoice
**Solution**:
- Check `taxIndexNo` value setelah confirm
- Check payload yang dikirim ke API
- Check controller menerima `tax_index_no`

## Best Practices

### 1. Always Validate Tax Before Invoice
```javascript
// ❌ Bad: Langsung buka DO selection
function openFlow() {
  flowOpen.value = true
}

// ✅ Good: Validasi tax dulu
function openFlow() {
  checkTaxModalOpen.value = true
}
```

### 2. Auto-select Default Tax
```javascript
// Auto-select first active tax
if (!selectedTax.value && taxOptions.value.length > 0) {
  const firstActive = taxOptions.value.find(t => t.apply)
  if (firstActive) {
    selectedTax.value = firstActive
  }
}
```

### 3. Preserve Tax Selection
```javascript
// Emit tax data, bukan hanya code
emit('confirm', {
  code: selectedTax.code,
  name: selectedTax.name,
  rate: selectedTax.rate,
  include: selectedTax.include
})
```

## Future Enhancements

### Phase 1
- [ ] Tax detail zoom view
- [ ] Tax history untuk customer
- [ ] Multiple tax support (tax 1, tax 2)

### Phase 2
- [ ] Tax exemption handling
- [ ] Tax calculation preview
- [ ] Tax report integration

### Phase 3
- [ ] Automatic tax selection based on customer
- [ ] Tax rule engine
- [ ] Tax compliance validation

---
**Created**: October 16, 2025
**Version**: 1.0
**Status**: ✅ Implemented and Ready for Testing
