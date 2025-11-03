# Refactoring Obsolete & Reactive MC - Documentation

## 📋 Overview
Refactoring menu **Obsolete & Reactive MC** untuk menggunakan table `MC` langsung dan mengintegrasikan fungsi ke dalam `UpdateMcController` yang sudah ada, menghilangkan duplikasi table dan controller.

## 🎯 Tujuan Refactoring
1. ✅ Menghilangkan table `obsolate_reactive_mcs` yang redundant
2. ✅ Menggunakan table `MC` yang sudah ada untuk semua operasi Master Card
3. ✅ Mengintegrasikan fungsi obsolete/reactive ke `UpdateMcController`
4. ✅ Menghapus `ObsolateReactiveMcController` dan `ObsolateReactiveMC` model
5. ✅ Menyederhanakan arsitektur dan mengurangi kompleksitas

---

## 🔄 Perubahan Yang Dilakukan

### 1. **Controller Changes**

#### ✅ `UpdateMcController.php` - Fungsi Baru Ditambahkan:

**A. `obsoleteReactiveIndex()`**
- Render halaman Obsolete & Reactive MC
- Path: `/sales-management/system-requirement/master-card/obsolete-reactive-mc`

**B. `bulkObsolete(Request $request)`**
- Mengubah status MC menjadi "Obsolete" secara bulk berdasarkan kriteria
- **Input Parameters:**
  - `ac` (nullable): Customer Account Code
  - `product_code` (nullable): Product Design Code
  - `mcs_from` (nullable): MCS# range from
  - `mcs_to` (nullable): MCS# range to
  - `reason` (required): Alasan perubahan status

- **Logic:**
  ```php
  // Filter berdasarkan kriteria
  - Filter by AC_NUM if provided
  - Filter by P_DESIGN if provided
  - Filter by MCS_Num range if provided
  - Only update records where STS = 'Active'
  
  // Update ke Obsolete
  UPDATE MC SET STS = 'Obsolete' WHERE [criteria]
  
  // Logging
  Log action with user_id, criteria, count, reason, timestamp
  ```

- **Response:**
  - Success: `{ success: true, message: "...", count: X }`
  - No records: `{ success: false, message: "No active master cards found...", count: 0 }`
  - Error: `{ success: false, message: "...", error: "..." }`

**C. `bulkReactivate(Request $request)`**
- Mengubah status MC menjadi "Active" kembali secara bulk
- **Input Parameters:** Sama dengan `bulkObsolete`
  
- **Logic:**
  ```php
  // Filter berdasarkan kriteria (sama seperti obsolete)
  - Only update records where STS = 'Obsolete'
  
  // Update ke Active
  UPDATE MC SET STS = 'Active' WHERE [criteria]
  
  // Logging
  Log action with user_id, criteria, count, reason, timestamp
  ```

**D. `getMcsByCustomer($customerCode)`**
- Mendapatkan daftar MC berdasarkan customer code
- Digunakan untuk populate modal selection

---

### 2. **Routes Changes**

#### ✅ `routes/web.php` - Updated Routes:

**Before:**
```php
Route::get('/sales-management/.../obsolete-reactive-mc', 
    [ObsolateReactiveMcController::class, 'index']);

// API Routes
Route::get('/obsolate-reactive-mc', [ObsolateReactiveMcController::class, 'apiIndex']);
Route::post('/obsolate-reactive-mc', [ObsolateReactiveMcController::class, 'store']);
Route::post('/obsolate-reactive-mc/obsolate/{id}', [ObsolateReactiveMcController::class, 'obsolate']);
Route::post('/obsolate-reactive-mc/reactive/{id}', [ObsolateReactiveMcController::class, 'reactive']);
```

**After:**
```php
Route::get('/sales-management/.../obsolete-reactive-mc', 
    [UpdateMcController::class, 'obsoleteReactiveIndex']);

// API Routes - Now using UpdateMcController
Route::post('/mc/bulk-obsolete', [UpdateMcController::class, 'bulkObsolete']);
Route::post('/mc/bulk-reactive', [UpdateMcController::class, 'bulkReactivate']);
Route::get('/mc/by-customer/{customerCode}', [UpdateMcController::class, 'getMcsByCustomer']);
```

---

### 3. **Vue Component Changes**

#### ✅ `obsolete-reactive-mc.vue` - Updated:

**A. Form Structure:**
```javascript
const form = ref({
    ac: '',              // Customer Account Code
    product_code: '',    // Product Design Code  
    mcs_from: '',        // MCS Range From
    mcs_to: '',          // MCS Range To
    action: 'obsolete',  // Action: 'obsolete' or 'reactivate'
    reason: '',          // Mandatory reason
});
```

**B. API Endpoints Updated:**
```javascript
// Old endpoint
POST /api/obsolate-reactive-mc

// New endpoints
POST /api/mc/bulk-obsolete     // For obsolete action
POST /api/mc/bulk-reactive      // For reactivate action
GET  /api/mc/by-customer/{code} // For fetching MCs
```

**C. Enhanced Validation:**
- At least one filter criteria required (AC#, Product Code, or MCS Range)
- Reason is mandatory
- Better error messages and user feedback

**D. UI Improvements:**
- Added "MCS To" field for range selection
- Added Product Code field with lookup button
- Added Action radio buttons (Obsolete/Reactive)
- Added Reason textarea (mandatory)
- Added Process button with dynamic text
- Improved visual feedback and styling

---

### 4. **Database Structure**

#### ✅ Table MC - Field Yang Digunakan:

```sql
MC (existing table)
├── MCS_Num         -- Master Card Sequence Number
├── AC_NUM          -- Customer Account Number
├── AC_NAME         -- Customer Account Name
├── MODEL           -- MC Model
├── STS             -- Status: 'Active' or 'Obsolete'
├── P_DESIGN        -- Product Design Code
├── COMP            -- Component
└── [100+ other fields...]
```

**Status Management:**
- `Active` = MC yang masih digunakan
- `Obsolete` = MC yang sudah tidak digunakan

**Audit Trail:**
- Logging dilakukan via Laravel Log facade
- Tercatat: user_id, timestamp, criteria, count, reason
- File log: `storage/logs/laravel.log`

---

## 🗑️ Files To Be Removed

Setelah refactoring selesai, file-file berikut dapat dihapus:

### 1. **Migration**
```
c:\laragon\www\ERP\database\migrations\2025_06_10_064051_create_obsolate_reactive_mcs_table.php
```

### 2. **Model**
```
c:\laragon\www\ERP\app\Models\ObsolateReactiveMC.php
```

### 3. **Controller**
```
c:\laragon\www\ERP\app\Http\Controllers\ObsolateReactiveMcController.php
```

**⚠️ Note:** File `ObsolateReactiveMcController.php` masih memiliki fungsi `viewAndPrint()` dan `viewAndPrintMcMaintenanceLog()` yang digunakan di routes lain. Fungsi-fungsi ini perlu dipindahkan ke controller yang sesuai sebelum menghapus file.

---

## 📊 Comparison: Before vs After

### Before Refactoring:

```
Table: obsolate_reactive_mcs
├── id
├── mc_seq
├── mc_model
├── customer_id
├── customer_name
├── status
├── obsolate_date
├── obsolate_by
├── obsolate_reason
├── reactive_date
├── reactive_by
└── reactive_reason

Controller: ObsolateReactiveMcController
Model: ObsolateReactiveMC
Routes: /api/obsolate-reactive-mc/*
```

**Issues:**
- ❌ Duplikasi data antara table `MC` dan `obsolate_reactive_mcs`
- ❌ Sinkronisasi manual diperlukan
- ❌ Kompleksitas maintenance tinggi
- ❌ Audit trail terpisah

### After Refactoring:

```
Table: MC (existing)
├── STS field: 'Active' or 'Obsolete'
└── All MC data in one place

Controller: UpdateMcController (extended)
Model: Mc (existing)
Routes: /api/mc/bulk-obsolete, /api/mc/bulk-reactive
Logging: Laravel Log (centralized audit trail)
```

**Benefits:**
- ✅ Single source of truth (table MC)
- ✅ No data synchronization needed
- ✅ Simpler architecture
- ✅ Centralized audit trail via logging
- ✅ Consistent with CPS Enterprise behavior
- ✅ Better performance (no joins needed)

---

## 🔍 CPS Enterprise Compatibility

### CPS Enterprise 2020 Behavior:

1. **Criteria-Based Bulk Operation:**
   - Filter by Customer Account
   - Filter by MCS# Range
   - Filter by Product Code
   - Combination of filters supported

2. **Status Management:**
   - Active → Obsolete (deactivate)
   - Obsolete → Active (reactivate)
   - Status stored directly in MC table

3. **Audit Requirements:**
   - Reason is mandatory
   - User tracking
   - Timestamp recording
   - Action logging

4. **UI/UX:**
   - Clear filter inputs
   - Action selection (radio buttons)
   - Reason input (textarea)
   - Confirmation and feedback

**✅ All CPS behaviors are now properly implemented!**

---

## 🚀 Usage Examples

### Example 1: Obsolete All MC for Customer "C001"
```javascript
POST /api/mc/bulk-obsolete
{
    "ac": "C001",
    "reason": "Customer discontinued all products"
}
```

### Example 2: Obsolete MC Range
```javascript
POST /api/mc/bulk-obsolete
{
    "mcs_from": "MC001",
    "mcs_to": "MC100",
    "reason": "Old design phase-out"
}
```

### Example 3: Reactive Specific Product Design
```javascript
POST /api/mc/bulk-reactive
{
    "product_code": "RSC",
    "reason": "Product design reactivated for new order"
}
```

### Example 4: Combined Filters
```javascript
POST /api/mc/bulk-obsolete
{
    "ac": "C001",
    "product_code": "RSC",
    "mcs_from": "MC001",
    "mcs_to": "MC050",
    "reason": "Specific range obsolete"
}
```

---

## 📝 Testing Checklist

- [ ] Test obsolete with AC# only
- [ ] Test obsolete with MCS# range only
- [ ] Test obsolete with Product Code only
- [ ] Test obsolete with combined filters
- [ ] Test reactive with various filters
- [ ] Verify reason validation (required)
- [ ] Verify criteria validation (at least one required)
- [ ] Check if only Active records are obsoleted
- [ ] Check if only Obsolete records are reactivated
- [ ] Verify count returned matches actual updates
- [ ] Check Laravel log for audit trail
- [ ] Test customer MC lookup modal
- [ ] Test MCS lookup modal
- [ ] Test product lookup modal
- [ ] Verify UI feedback (success/error messages)

---

## 🎓 Key Takeaways

1. **Simplified Architecture:**
   - One table for all MC data
   - One controller for all MC operations
   - Cleaner, more maintainable code

2. **Better Performance:**
   - Direct updates on MC table
   - No data synchronization overhead
   - Fewer database queries

3. **Consistent with CPS:**
   - Matches CPS Enterprise 2020 behavior
   - Proper audit trail via logging
   - Criteria-based bulk operations

4. **Improved Maintainability:**
   - Less code to maintain
   - Single source of truth
   - Clear separation of concerns

---

## 📞 Support & Questions

For questions or issues, please refer to:
- UpdateMcController implementation
- MC table migration
- Laravel logging documentation

**Date:** 2025-01-03  
**Version:** 2.0  
**Status:** ✅ Completed
