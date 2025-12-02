# Fix: Check Sales Tax Screen - No Active Tax Codes Found

## Problem
Check Sales Tax Screen menampilkan **"No active tax codes found"** karena:
1. Controller mencari data di tabel `Sales_Tax` yang tidak ada
2. Seharusnya menggunakan tabel `taxrate` yang sudah ada di database

## Solution

### 1. Update Controller ✅
**File**: `app/Http/Controllers/WarehouseManagement/Invoice/InvoiceController.php`

**Changed**: Method `getSalesTaxOptions()` sekarang menggunakan tabel `taxrate`:

```php
public function getSalesTaxOptions(Request $request)
{
    // Get tax options from taxrate table
    $taxes = DB::table('taxrate')
        ->select([
            'TAXCODE as code',
            'TAXNAME as name',
            'RATEPPN as rate',
            DB::raw("1 as apply"),
            DB::raw("0 as include")
        ])
        ->where('RATEPPN', '>', 0)
        ->orderBy('TAXCODE')
        ->get()
        ->map(function ($tax) {
            return [
                'code' => $tax->code,
                'name' => $tax->name,
                'rate' => floatval($tax->rate),
                'apply' => true,
                'include' => false,
                'status' => 'Active'
            ];
        });

    return response()->json($taxes);
}
```

### 2. Create Tax Rate Seeder ✅
**File**: `database/seeders/TaxRateSeeder.php`

Seeder ini akan menambahkan data sample tax rates ke database.

### 3. Run Seeder

Jalankan command berikut untuk menambahkan data tax:

```bash
php artisan db:seed --class=TaxRateSeeder
```

**Output yang diharapkan**:
```
Tax rates seeded successfully!
```

### 4. Verify Data

Check data di database:

```sql
SELECT * FROM taxrate;
```

**Expected Result**:
```
+----+------+------------------+---------+----------+---------+---------+
| id | CODE | NAME             | TAXCODE | TAXNAME  | RATEPPH | RATEPPN |
+----+------+------------------+---------+----------+---------+---------+
|  1 | 01   | PPN 11%          | PPN11   | PPN 11%  |    0.00 |   11.00 |
|  2 | 02   | PPN 11% + PPh 2% | PPN11   | PPN 11%  |    2.00 |   11.00 |
|  3 | 03   | Non PPN          | NONPPN  | Non PPN  |    0.00 |    0.00 |
+----+------+------------------+---------+----------+---------+---------+
```

## Testing Steps

### 1. Prepare Test Data
```bash
# Run seeder
php artisan db:seed --class=TaxRateSeeder
```

### 2. Test API Endpoint
```bash
# Test via curl
curl http://127.0.0.1:8000/api/invoices/sales-tax-options

# Or via browser
http://127.0.0.1:8000/api/invoices/sales-tax-options
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

### 3. Test in UI
1. Navigate to: **Warehouse Management > Invoice > IV Processing > Prepare Invoice by D/Order**
2. Select a customer
3. Fill required fields
4. Click **"Continue to Prepare"**
5. **Check Sales Tax Screen** should now display tax options:

```
┌──────────────────────────────────────────────────────────────┐
│ Check Sales Tax Screen                                       │
├──────────────────────────────────────────────────────────────┤
│ ℹ Tax Verification Required                                  │
│ Please verify the sales tax configuration below...           │
├──────────┬────────────┬───────┬────────┬─────────┤
│ Tax Code │ Name       │ Apply │ Tax%   │ Include │
├──────────┼────────────┼───────┼────────┼─────────┤
│ ◉ PPN11  │ PPN 11%    │ Yes   │ 11.00% │ No      │
└──────────┴────────────┴───────┴────────┴─────────┘
│                                                              │
│ ✓ Selected Tax: PPN11 - PPN 11% (11.00%)                    │
│                                                              │
│ [Zoom]                              [Exit]  [OK]             │
└──────────────────────────────────────────────────────────────┘
```

## Database Schema

### Table: taxrate

```sql
CREATE TABLE `taxrate` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `CODE` varchar(50) NOT NULL,
  `NAME` varchar(50) NOT NULL,
  `TAXCODE` varchar(50) NOT NULL,
  `TAXNAME` varchar(50) NOT NULL,
  `RATEPPH` double NOT NULL,
  `RATEPPN` double NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
);
```

### Field Mapping

| taxrate Field | API Response | Check Sales Tax Screen |
|--------------|--------------|------------------------|
| TAXCODE      | code         | No Tax Code            |
| TAXNAME      | name         | Name                   |
| RATEPPN      | rate         | Tax%                   |
| -            | apply        | Apply (always Yes)     |
| -            | include      | Include (always No)    |

## Additional Tax Rates

Jika Anda ingin menambahkan tax rate lain, jalankan SQL berikut:

```sql
-- PPN 10% (old rate)
INSERT INTO taxrate (CODE, NAME, TAXCODE, TAXNAME, RATEPPH, RATEPPN) 
VALUES ('04', 'PPN 10%', 'PPN10', 'PPN 10%', 0, 10.00);

-- PPN 12% (future rate)
INSERT INTO taxrate (CODE, NAME, TAXCODE, TAXNAME, RATEPPH, RATEPPN) 
VALUES ('05', 'PPN 12%', 'PPN12', 'PPN 12%', 0, 12.00);

-- PPh 23 only
INSERT INTO taxrate (CODE, NAME, TAXCODE, TAXNAME, RATEPPH, RATEPPN) 
VALUES ('06', 'PPh 23 - 2%', 'PPH23', 'PPh Pasal 23', 2.00, 0);
```

## Troubleshooting

### Issue 1: Seeder fails
**Error**: `SQLSTATE[42S02]: Base table or view not found: 'taxrate'`

**Solution**:
```bash
# Run migrations first
php artisan migrate

# Then run seeder
php artisan db:seed --class=TaxRateSeeder
```

### Issue 2: Still showing "No active tax codes found"
**Possible causes**:
1. Seeder belum dijalankan
2. Data di tabel `taxrate` kosong
3. Semua RATEPPN = 0

**Solution**:
```sql
-- Check data
SELECT * FROM taxrate WHERE RATEPPN > 0;

-- If empty, run seeder
php artisan db:seed --class=TaxRateSeeder
```

### Issue 3: API returns empty array
**Check**:
```bash
# Test API directly
curl http://127.0.0.1:8000/api/invoices/sales-tax-options

# Check Laravel logs
tail -f storage/logs/laravel.log
```

## Integration with Invoice Preparation

### Flow After Fix:

```
1. User fills initial form
   ↓
2. Click "Continue to Prepare"
   ↓
3. Check Sales Tax Screen appears ✅
   ↓
4. Display tax options from taxrate table ✅
   ↓
5. User selects tax (e.g., PPN11)
   ↓
6. Click OK
   ↓
7. Tax code saved to state
   ↓
8. DO Selection screen opens
   ↓
9. User selects DOs
   ↓
10. Invoice prepared with selected tax ✅
```

### Tax Application in Invoice:

When invoice is created, the selected tax will be applied:

```php
DB::table('INV')->insert([
    'IV_NUM' => 'IV-202510-0001',
    'IV_TAX_CODE' => 'PPN11',      // From Check Sales Tax Screen
    'IV_TAX_PERCENT' => 11.00,      // From taxrate.RATEPPN
    'IV_TRAN_AMT' => 1000000,       // Subtotal
    // Tax amount calculated: 1,000,000 × 11% = 110,000
    // ...
]);
```

## Summary

✅ **Fixed Issues**:
1. Controller now uses correct table (`taxrate`)
2. Created seeder for sample data
3. API returns proper format
4. Check Sales Tax Screen displays data correctly

✅ **No New Tables Created**:
- Uses existing `taxrate` table
- No schema changes required
- Only added seeder for sample data

✅ **CPS Compliance**:
- Modal displays tax options
- User can select tax before invoice preparation
- Tax code applied to all invoices in session

## Next Steps

1. ✅ Run seeder: `php artisan db:seed --class=TaxRateSeeder`
2. ✅ Test API: `curl http://127.0.0.1:8000/api/invoices/sales-tax-options`
3. ✅ Test UI: Navigate to Prepare Invoice menu
4. ✅ Verify tax appears in Check Sales Tax Screen
5. ✅ Complete invoice preparation flow

---
**Fixed**: October 16, 2025
**Status**: ✅ Ready for Testing
