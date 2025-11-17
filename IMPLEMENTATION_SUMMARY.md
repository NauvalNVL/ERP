# Master Card Component Data Loading - Implementation Summary

## Problem
Ketika memilih mastercard yang sudah ada, modal Setup MC Component tidak menampilkan data dari Fit1-9 yang sudah tersimpan di database. Hanya Main component yang ditampilkan.

## Solution
Implementasi sistem untuk fetch dan display data component dari database ketika modal dibuka.

## Changes Made

### 1. Frontend - UpdateMcModal.vue

#### a. Added `fetchMcComponentsFromDb()` function
- Fetch component data dari API endpoint `/api/update-mc/master-cards/{mcSeq}/components`
- Transform data dari database ke format yang sesuai dengan UI
- Update `localComponents` dengan data yang di-fetch

**Location:** Line ~2350 (sebelum `onSelectComponent`)

```javascript
const fetchMcComponentsFromDb = async () => {
    // Fetch dari API dengan mc_seq dan customer_code
    // Transform data untuk 10 rows (Main + Fit1-9)
    // Update localComponents dengan data dari database
}
```

#### b. Added watcher untuk modal open event
- Ketika Setup MC Modal dibuka dan `mcLoaded` ada, trigger fetch dari database
- Watcher mendengarkan perubahan `props.showSetupMcModal`

**Location:** Line ~2280 (setelah `watch(mcComponentsToRender)`)

```javascript
watch(() => props.showSetupMcModal, async (newVal) => {
    if (newVal && props.mcLoaded) {
        await fetchMcComponentsFromDb();
    }
});
```

#### c. Updated `mcComponentsToRender` computed property
- Menambahkan logic untuk handle case ketika components belum di-fetch
- Fallback ke props.mcComponents jika database fetch belum dilakukan

### 2. Backend - UpdateMcController.php

#### Added `apiShowComponents()` method
- Endpoint untuk fetch semua components (Main + Fit1-9) untuk MC tertentu
- Query database dengan MCS_Num dan optional customer_code
- Order components: Main first, kemudian Fit1-9
- Return array of components dengan field: c_num, pd, pcs_set, part_num, model, status

**Location:** Line ~1363 (sebelum closing brace)

```php
public function apiShowComponents($mcSeq, Request $request)
{
    // Query MC table untuk semua components dengan MCS_Num yang sama
    // Order by: Main first, then Fit1-9
    // Transform dan return JSON response
}
```

### 3. Routes - routes/api.php

#### Added new route
```php
Route::get('/update-mc/master-cards/{mcSeq}/components', [App\Http\Controllers\UpdateMcController::class, 'apiShowComponents']);
```

**Location:** Line ~387 (setelah route apiShow)

## Data Flow

1. User membuka Setup MC Modal dengan existing MC
2. Watcher mendeteksi `showSetupMcModal = true` dan `mcLoaded` ada
3. `fetchMcComponentsFromDb()` dipanggil
4. Frontend fetch ke `/api/update-mc/master-cards/{mcSeq}/components?customer_code={ac}`
5. Backend query MC table untuk semua components dengan MCS_Num yang sama
6. Backend return array of components (Main + Fit1-9)
7. Frontend transform data dan update `localComponents`
8. UI table di modal menampilkan semua components dengan data dari database

## Database Query

```sql
SELECT * FROM MC 
WHERE MCS_Num = ? 
AND AC_NUM = ? (optional)
ORDER BY CASE WHEN COMP = 'Main' THEN 0 ELSE CAST(SUBSTRING(COMP, 4) AS UNSIGNED) + 1 END
```

## Component Mapping

Database columns → UI fields:
- `COMP` → `c_num` (Main, Fit1, Fit2, ..., Fit9)
- `P_DESIGN` → `pd`
- `PCS_SET` → `pcs_set`
- `PART_NO` → `part_num`
- `MODEL` → `model`
- `STS` → `status`

## Testing

1. Buka Update MC page
2. Pilih customer account
3. Cari/pilih existing mastercard yang memiliki Fit components
4. Klik "Setup MC" button
5. Modal akan menampilkan semua components (Main + Fit1-9) dengan data dari database

## Logging

- `fetchMcComponentsFromDb()`: Log ketika fetch dimulai dan selesai
- `apiShowComponents()`: Log ketika components berhasil di-fetch atau error
- Console log di frontend untuk debugging

## Error Handling

- Jika API call gagal: console.error dan return empty array
- Jika tidak ada components di database: return empty array (tidak error)
- Jika customer_code tidak match: return 404 atau empty array
