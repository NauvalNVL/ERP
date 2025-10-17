# 🔧 Quick Fix Guide - Check Sales Tax Screen

## Masalah
❌ Check Sales Tax Screen menampilkan: **"No active tax codes found"**

## Penyebab
Controller mencari data di tabel `Sales_Tax` yang tidak ada. Seharusnya menggunakan tabel `taxrate` yang sudah ada.

## Solusi (3 Langkah)

### 1️⃣ Run Seeder (Tambah Data Tax)
```bash
php artisan db:seed --class=TaxRateSeeder
```

**Atau double-click file**: `setup-tax-data.bat`

### 2️⃣ Verify Data
```sql
SELECT * FROM taxrate;
```

**Expected**:
```
CODE | NAME             | TAXCODE | RATEPPN
-----|------------------|---------|--------
01   | PPN 11%          | PPN11   | 11.00
02   | PPN 11% + PPh 2% | PPN11   | 11.00
03   | Non PPN          | NONPPN  | 0.00
```

### 3️⃣ Test in Browser
1. Open: http://127.0.0.1:8000/warehouse-management/invoice/iv-processing/prepare-by-do-current-period
2. Select customer
3. Click **"Continue to Prepare"**
4. ✅ Check Sales Tax Screen should now show tax options!

## Files Changed

### ✅ Modified
- `app/Http/Controllers/WarehouseManagement/Invoice/InvoiceController.php`
  - Method `getSalesTaxOptions()` now uses `taxrate` table

### ✅ Created
- `database/seeders/TaxRateSeeder.php` - Seeder untuk data tax
- `setup-tax-data.bat` - Script otomatis setup
- `FIX_CHECK_SALES_TAX_SCREEN.md` - Dokumentasi lengkap
- `QUICK_FIX_GUIDE.md` - Panduan ini

## Expected Result

### Before Fix ❌
```
┌────────────────────────────────┐
│ Check Sales Tax Screen         │
├────────────────────────────────┤
│ No active tax codes found      │
└────────────────────────────────┘
```

### After Fix ✅
```
┌──────────────────────────────────────────────────────┐
│ Check Sales Tax Screen                               │
├──────────┬────────────┬───────┬────────┬─────────┤
│ Tax Code │ Name       │ Apply │ Tax%   │ Include │
├──────────┼────────────┼───────┼────────┼─────────┤
│ ◉ PPN11  │ PPN 11%    │ Yes   │ 11.00% │ No      │
└──────────┴────────────┴───────┴────────┴─────────┘
│ ✓ Selected Tax: PPN11 - PPN 11% (11.00%)            │
│ [Zoom]                          [Exit]  [OK]         │
└──────────────────────────────────────────────────────┘
```

## Troubleshooting

### Seeder Error?
```bash
# Run migrations first
php artisan migrate

# Then run seeder
php artisan db:seed --class=TaxRateSeeder
```

### Still Empty?
```sql
-- Manual insert
INSERT INTO taxrate (CODE, NAME, TAXCODE, TAXNAME, RATEPPH, RATEPPN) 
VALUES ('01', 'PPN 11%', 'PPN11', 'PPN 11%', 0, 11.00);
```

### API Test
```bash
curl http://127.0.0.1:8000/api/invoices/sales-tax-options
```

**Expected Response**:
```json
[
  {
    "code": "PPN11",
    "name": "PPN 11%",
    "rate": 11,
    "apply": true,
    "include": false,
    "status": "Active"
  }
]
```

## Summary

✅ **Fixed**: Controller menggunakan tabel `taxrate` yang benar  
✅ **No New Tables**: Menggunakan tabel existing  
✅ **Data Ready**: Seeder menambahkan sample tax rates  
✅ **CPS Compliant**: Check Sales Tax Screen berfungsi seperti CPS  

---
**Status**: ✅ Fixed and Ready
**Date**: October 16, 2025
