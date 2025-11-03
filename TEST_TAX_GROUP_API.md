# 🧪 Test Tax Group API

## Problem
Data tax group tidak tampil di modal meskipun sudah dibuat.

## Debugging Steps

### 1. Test API Endpoint Langsung

**Via Browser/Postman:**
```
GET http://127.0.0.1:8000/api/invoices/tax-groups
```

**Expected Response:**
```json
{
    "success": true,
    "data": [
        {
            "code": "PPN",
            "name": "PPN 10%",
            "sales_tax_applied": "Y",
            "created_at": "2025-11-01T...",
            "updated_at": "2025-11-01T..."
        }
    ]
}
```

### 2. Check Console Log

Setelah update TaxGroupModal.vue, buka:
1. Define Tax Group menu
2. Klik Table button
3. Buka **Developer Tools** (F12)
4. Lihat **Console** tab

**What to look for:**
```
TaxGroupModal API Response: {...}
✅ Loaded tax groups: 1
```

**OR Error:**
```
❌ Error loading tax groups: ...
Error details: ...
```

### 3. Manual Test via Console

Di browser Developer Tools Console, run:
```javascript
// Test API
fetch('/api/invoices/tax-groups')
  .then(r => r.json())
  .then(d => console.log('API Response:', d))
  .catch(e => console.error('Error:', e))
```

### 4. Verify Database

Check jika data ada di database:
```sql
SELECT * FROM tax_groups;
```

**Via Laravel Tinker:**
```bash
php artisan tinker
```
```php
\App\Models\TaxGroup::all();
```

## Common Issues & Solutions

### Issue 1: Table Empty (No Migration)
**Solution:**
```bash
php artisan migrate --path=database/migrations/2025_11_01_000000_create_tax_groups_table.php
```

### Issue 2: No Data (Not Seeded)
**Solution:**
```
POST http://127.0.0.1:8000/api/invoices/tax-groups/seed
```

### Issue 3: API 404 Not Found
**Check routes:**
```bash
php artisan route:list | grep tax-groups
```

**Expected output:**
```
GET|HEAD  api/invoices/tax-groups ................
POST      api/invoices/tax-groups ................
GET|HEAD  api/invoices/tax-groups/{code} .........
PUT|PATCH api/invoices/tax-groups/{code} .........
DELETE    api/invoices/tax-groups/{code} .........
```

### Issue 4: CORS Error
**Check `.env`:**
```
APP_URL=http://127.0.0.1:8000
```

### Issue 5: Wrong API Format
**Controller should return:**
```php
return response()->json([
    'success' => true,
    'data' => $taxGroups
]);
```

## Quick Fix Commands

### Seed Sample Data
```bash
# Via Artisan (if method exists)
php artisan db:seed --class=TaxGroupSeeder

# Via API
curl -X POST http://127.0.0.1:8000/api/invoices/tax-groups/seed
```

### Create Manual Test Data
```bash
php artisan tinker
```
```php
\App\Models\TaxGroup::create([
    'code' => 'TEST',
    'name' => 'Test Group',
    'sales_tax_applied' => 'Y'
]);
```

### Clear Cache
```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
```

## Test Checklist

- [ ] Migration sudah di-run
- [ ] Table `tax_groups` exists
- [ ] Data ada di database
- [ ] API endpoint `/api/invoices/tax-groups` accessible
- [ ] API returns correct JSON format
- [ ] Console shows successful data load
- [ ] Modal displays data

## Expected Console Output (Success)

```
TaxGroupModal API Response: {success: true, data: Array(5)}
✅ Loaded tax groups: 5
```

## Next Steps After Fix

1. Refresh halaman Define Tax Group
2. Click table button
3. Data should appear
4. Select untuk test integration
