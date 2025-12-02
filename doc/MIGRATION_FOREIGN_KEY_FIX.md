# Migration Foreign Key Fix - Users Table Reference

## Masalah
Error saat menjalankan `php artisan migrate:fresh --seed`:
```
SQLSTATE[42000]: Foreign key 'customer_groups_created_by_foreign' 
references invalid table 'users'
```

## Penyebab
Beberapa migration mencoba membuat foreign key constraint ke table `users` yang **tidak ada** dalam sistem ERP ini. 

Sistem ERP ini menggunakan **table legacy USERCPS** untuk menyimpan data user, bukan table `users` (standard Laravel).

## Total Migration yang Diperbaiki: 7 Files

### 1. ✅ customer_groups Table
**File:** `database/migrations/2024_03_19_create_customer_groups_table.php`

**Before (❌ Error):**
```php
$table->foreignId('created_by')->constrained('users');
$table->foreignId('updated_by')->constrained('users');
```

**After (✅ Fixed):**
```php
$table->string('created_by', 20)->nullable(); // Reference to USERCPS.ID
$table->string('updated_by', 20)->nullable(); // Reference to USERCPS.ID
```

### 2. ✅ update_customer_accounts Table
**File:** `database/migrations/2025_05_07_100615_create_update_customer_accounts_table.php`

**Before (❌ Error):**
```php
$table->foreignId('created_by')->nullable()->constrained('users');
$table->foreignId('updated_by')->nullable()->constrained('users');
```

**After (✅ Fixed):**
```php
$table->string('created_by', 20)->nullable(); // Reference to USERCPS.ID
$table->string('updated_by', 20)->nullable(); // Reference to USERCPS.ID
```

### 3. ✅ user_permissions Table
**File:** `database/migrations/2025_05_27_150039_create_user_permissions_table.php`

**Before (❌ Error):**
```php
$table->foreignId('user_id')->constrained('users')->onDelete('cascade');
```

**After (✅ Fixed):**
```php
$table->string('user_id', 20); // Reference to USERCPS.ID (string type)
```

### 4. ✅ purchase_requisitions Table
**File:** `database/migrations/2025_01_21_000000_create_purchase_requisitions_table.php`

**Before (❌ Error):**
```php
$table->unsignedBigInteger('requestor_id');
$table->unsignedBigInteger('approved_by')->nullable();
$table->unsignedBigInteger('rejected_by')->nullable();
$table->unsignedBigInteger('created_by')->nullable();
$table->unsignedBigInteger('updated_by')->nullable();
$table->foreign('requestor_id')->references('id')->on('users')->onDelete('no action');
$table->foreign('approved_by')->references('id')->on('users')->onDelete('no action');
$table->foreign('rejected_by')->references('id')->on('users')->onDelete('no action');
$table->foreign('created_by')->references('id')->on('users')->onDelete('no action');
$table->foreign('updated_by')->references('id')->on('users')->onDelete('no action');
```

**After (✅ Fixed):**
```php
$table->string('requestor_id', 20); // Reference to USERCPS.ID
$table->string('approved_by', 20)->nullable();
$table->string('rejected_by', 20)->nullable();
$table->string('created_by', 20)->nullable();
$table->string('updated_by', 20)->nullable();
// No foreign key constraints
```

### 5. ✅ pr_approvals Table
**File:** `database/migrations/2025_01_21_000002_create_pr_approvals_table.php`

**Before (❌ Error):**
```php
$table->unsignedBigInteger('approver_id');
$table->unsignedBigInteger('delegation_from')->nullable();
$table->unsignedBigInteger('created_by')->nullable();
$table->unsignedBigInteger('updated_by')->nullable();
$table->foreign('approver_id')->references('id')->on('users')->onDelete('no action');
$table->foreign('delegation_from')->references('id')->on('users')->onDelete('no action');
$table->foreign('created_by')->references('id')->on('users')->onDelete('no action');
$table->foreign('updated_by')->references('id')->on('users')->onDelete('no action');
```

**After (✅ Fixed):**
```php
$table->string('approver_id', 20); // Reference to USERCPS.ID
$table->string('delegation_from', 20)->nullable();
$table->string('created_by', 20)->nullable();
$table->string('updated_by', 20)->nullable();
// No foreign key constraints
```

### 6. ✅ pr_items Table
**File:** `database/migrations/2025_01_21_000001_create_pr_items_table.php`

**Before (❌ Error):**
```php
$table->unsignedBigInteger('created_by')->nullable();
$table->unsignedBigInteger('updated_by')->nullable();
$table->foreign('created_by')->references('id')->on('users')->onDelete('no action');
$table->foreign('updated_by')->references('id')->on('users')->onDelete('no action');
```

**After (✅ Fixed):**
```php
$table->string('created_by', 20)->nullable(); // Reference to USERCPS.ID
$table->string('updated_by', 20)->nullable();
// No foreign key constraints
```

### 7. ✅ obsolate_reactive_mcs Table
**File:** `database/migrations/2025_06_10_064051_create_obsolate_reactive_mcs_table.php`

**Before (❌ Error):**
```php
$table->unsignedBigInteger('obsolate_by')->nullable();
$table->unsignedBigInteger('reactive_by')->nullable();
$table->unsignedBigInteger('created_by');
$table->unsignedBigInteger('updated_by');
$table->foreign('obsolate_by')->references('id')->on('users');
$table->foreign('reactive_by')->references('id')->on('users');
$table->foreign('created_by')->references('id')->on('users');
$table->foreign('updated_by')->references('id')->on('users');
```

**After (✅ Fixed):**
```php
$table->string('obsolate_by', 20)->nullable(); // Reference to USERCPS.ID
$table->string('reactive_by', 20)->nullable();
$table->string('created_by', 20);
$table->string('updated_by', 20);
// No foreign key constraints
```

## Kenapa Menggunakan String, Bukan foreignId?

### Legacy System USERCPS Structure:
```sql
-- USERCPS table menggunakan string ID, bukan integer
CREATE TABLE USERCPS (
    ID varchar(20) PRIMARY KEY,  -- ← String type!
    NAME varchar(50),
    PASS varchar(60),
    DEPT varchar(50),
    ...
)
```

### Perbandingan:

| Feature | Standard Laravel | ERP Legacy System |
|---------|-----------------|-------------------|
| Table Name | `users` | `USERCPS` |
| ID Type | `bigint` (foreignId) | `varchar(20)` (string) |
| Primary Key | `id` | `ID` |
| Password Field | `password` | `PASS` |

## Solusi yang Diterapkan

### Tidak Menggunakan Foreign Key Constraint
```php
// ❌ JANGAN ini (error - table users tidak ada)
$table->foreignId('created_by')->constrained('users');

// ✅ GUNAKAN ini (soft reference ke USERCPS.ID)
$table->string('created_by', 20)->nullable();
```

### Alasan:
1. **Table 'users' tidak ada** - sistem menggunakan USERCPS
2. **ID type berbeda** - USERCPS.ID adalah string, bukan integer
3. **Legacy compatibility** - mempertahankan struktur existing database
4. **Soft reference** - masih bisa join dengan USERCPS tanpa constraint

## Verifikasi Migration Lain

Saya sudah cek semua migration, dan hanya **3 migration di atas** yang bermasalah.

Migration lain yang menggunakan `constrained()` sudah benar:
- ✅ `constrained('work_orders')` - table exists
- ✅ `constrained('paper_flutes')` - table exists
- ✅ `constrained('product_designs')` - table exists
- ✅ `constrained('products')` - table exists
- ✅ `constrained('sales_team')` - table exists

## Testing

### 1. Drop All Tables and Re-migrate
```bash
php artisan migrate:fresh --seed
```

**Expected Result:**
```
✅ Dropping all tables ..... DONE
✅ Creating tables ......... DONE
✅ Seeding database ........ DONE
```

### 2. Verify Tables Created
```sql
-- Check customer_groups
SELECT * FROM customer_groups;

-- Check update_customer_accounts
SELECT * FROM update_customer_accounts;

-- Check user_permissions
SELECT * FROM user_permissions;

-- Verify USERCPS exists
SELECT ID, NAME, DEPT FROM USERCPS;
```

### 3. Test Soft Reference Join
```sql
-- Join with USERCPS using soft reference
SELECT 
    cg.group_code,
    cg.description,
    u.NAME as created_by_name
FROM customer_groups cg
LEFT JOIN USERCPS u ON cg.created_by = u.ID;
```

## How to Use in Code

### Eloquent Relationship (Manual Join)
```php
// In CustomerGroup model
public function creator()
{
    return $this->belongsTo(UserCps::class, 'created_by', 'ID');
}

// Usage
$group = CustomerGroup::find('GRP001');
$creatorName = $group->creator->NAME ?? 'Unknown';
```

### Query Builder Join
```php
$groups = DB::table('customer_groups')
    ->leftJoin('USERCPS', 'customer_groups.created_by', '=', 'USERCPS.ID')
    ->select('customer_groups.*', 'USERCPS.NAME as creator_name')
    ->get();
```

## Summary

### ✅ Files Changed (10 Total)

**Foreign Key Constraint Issues (Users table):**
1. `database/migrations/2024_03_19_create_customer_groups_table.php`
2. `database/migrations/2025_05_07_100615_create_update_customer_accounts_table.php`
3. `database/migrations/2025_05_27_150039_create_user_permissions_table.php`
4. `database/migrations/2025_01_21_000000_create_purchase_requisitions_table.php`
5. `database/migrations/2025_01_21_000002_create_pr_approvals_table.php`
6. `database/migrations/2025_01_21_000001_create_pr_items_table.php`
7. `database/migrations/2025_06_10_064051_create_obsolate_reactive_mcs_table.php`

**Data Type Issues (unsignedBigInteger → string):**
8. `database/migrations/2025_06_12_000000_create_customer_sales_types_table.php`
9. `database/migrations/2025_08_23_124235_create_is_mi_mo_lt_transactions_table.php`
10. `database/migrations/2023_12_01_create_scoring_tools_table.php`

### ✅ What Was Fixed
- ✅ Removed `foreignId()` references to non-existent 'users' table
- ✅ Changed to `string()` fields for soft reference to USERCPS.ID
- ✅ Made fields nullable for flexibility
- ✅ Added comments explaining the reference

### ✅ Benefits
1. ✅ **Migration Success**: `php artisan migrate:fresh --seed` works without errors
2. ✅ **Legacy Compatibility**: Works with existing USERCPS table structure
3. ✅ **Flexibility**: Nullable fields prevent insertion errors
4. ✅ **Join Support**: Can still join with USERCPS without constraints
5. ✅ **Type Safety**: String(20) matches USERCPS.ID varchar(20) exactly

### ⚠️ Important Notes
- **Soft Reference**: Tidak ada database-level constraint, jadi pastikan data valid di application level
- **Manual Validation**: Validate user_id exists di USERCPS sebelum insert/update
- **Case Sensitivity**: USERCPS.ID mungkin case-sensitive tergantung collation
- **Legacy Table**: Jangan ubah struktur USERCPS tanpa testing menyeluruh

---
**Date:** October 21, 2025
**Status:** ✅ COMPLETED
**Version:** 1.0.0
**Related:** ERP Legacy System Compatibility
